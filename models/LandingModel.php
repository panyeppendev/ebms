<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LandingModel extends CI_Model
{
	public function purchase($id)
	{
		$purchase = $this->db->get_where('purchases', [
			'student_id' => $id, 'status' => 'ACTIVE'
		])->row_object();

		if (!$purchase) {
			return [
				'status' => false
			];
		}

		$pocketAccount = $this->db->get('account_pocket')->row_object();
		if (!$pocketAccount) {
			return [
				'status' => false
			];
		}

		$account = $pocketAccount->account_id;
		$this->db->select_sum('nominal', 'total')->from('expenditures');
		$expenditures = $this->db->where(['student_id' => $id, 'account_id' => $account])->get()->row_object();
		if (!$expenditures) {
			$nominal = 0;
		}else{
			$nominal = $expenditures->total;
		}

		$cash = $this->disbursement($id, 0);
		$credit = $this->disbursement($id, 1);
		$disbursement = $cash + $credit;
		$balance = $nominal - $disbursement;

		return [
			'status' => true,
			'package' => $purchase->package_name,
			'income' => $nominal,
			'cash' => $cash,
			'credit' => $credit,
			'disbursement' => $disbursement,
			'balance' => $balance
		];

	}

	public function disbursement($id, $status)
	{
		$this->db->select('SUM(amount) as total')->from('disbursements');
		$result = $this->db->where([
			'student_id' => $id, 'status' => $status
		])->get()->row_object();

		if ($result) {
			return $result->total;
		}

		return 0;
	}

	public function deposit($id)
	{
		$credit = $this->db->select_sum('amount', 'credit')->from('deposit_credit')->where('student_id', $id)->get()->row_object();
		$cash = $this->db->select_sum('amount', 'cash')->from('deposit_debit')->where([
			'student_id' => $id, 'status' => 0
		])->get()->row_object();
		$debit = $this->db->select_sum('amount', 'debit')->from('deposit_debit')->where([
			'student_id' => $id, 'status' => 1
		])->get()->row_object();

		if ($credit) {
			$credit = $credit->credit;
		}else{
			$credit = 0;
		}

		if ($cash) {
			$cash = $cash->cash;
		}else{
			$cash = 0;
		}

		if ($debit) {
			$debit = $debit->debit;
		}else{
			$debit = 0;
		}

		$totalDebit = $cash + $debit;
		$balance = $credit - $totalDebit;

		return [
			'credit' => $credit,
			'cash' => $cash,
			'debit' => $debit,
			'total' => $totalDebit,
			'balance' => $balance
		];
	}

    public function checkSaldo()
    {
        $id = $this->input->post('id', true);

        $checkStudent = $this->db->get_where('students', ['id' => $id])->row_object();
        if (!$checkStudent) {
            return [
                'status' => 400,
                'message' => 'NIS tidak valid'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function check()
    {
        $id = $this->input->post('id', true);

        $checkStudent = $this->db->get_where('students', ['id' => $id])->row_object();
        if (!$checkStudent) {
            return [
                'status' => 400,
                'message' => 'ID santri tidak valid'
            ];
        }

        return [
            'status' => 200,
            'student' => $checkStudent,
            'purchase' => $this->purchase($id),
            'deposit' => $this->deposit($id),
			'presence' => $this->getPresence($id)
        ];
    }


	public function getPresence($id)
	{
		$period = $this->dm->getperiod();

		return $this->db->get_where('presences', [
			'student_id' => $id, 'period' => $period
		])->result_object();
	}

	public function getdatakamtib()
	{
		$id = $this->input->post('id', true);
		$period = $this->dm->getperiod();

		$checkStudent = $this->db->get_where('students', ['id' => $id])->row_object();
		if (!$checkStudent) {
			return [
				'status' => 400,
				'message' => 'ID santri tidak valid'
			];
		}

		//GET PERMISSION
		$this->db->select('COUNT(id) as total, type')->from('permissions');
		$this->db->where(['student_id' => $id, 'period' => $period]);
		$permission = $this->db->group_by('type')->order_by('type', 'DESC')->get()->result_object();

		$this->db->select('COUNT(id) as total, reason')->from('permissions');
		$this->db->where(['student_id' => $id, 'period' => $period, 'type' => 'LONG']);
		$reason = $this->db->group_by('reason')->order_by('type', 'DESC')->get()->result_object();

		//GET SUSPENSION
		$suspension = $this->db->get_where('suspensions', [
			'student_id' => $id, 'period' => $period, 'status' => 'ACTIVE'
		])->row_object();

		$this->db->select('COUNT(a.id) as total, b.category')->from('punishments as a');
		$this->db->join('constitutions as b', 'b.id = a.constitution_id');
		$this->db->where(['a.student_id' => $id, 'a.period' => $period]);
		$result = $this->db->group_by('b.category')->order_by('b.category', 'ASC')->get()->result_object();

		$overview = [];
		if ($result) {
			foreach ($result as $item) {
				$overview[] = [
					'count' => $item->total,
					'category' => $item->category,
					'detail' => $this->getPunishment($id, $item->category)
				];
			}
		}
		return [
			'status' => 200,
			'student' => $checkStudent,
			'punishment' => $overview,
			'permission' => $permission,
			'reason' => $reason,
			'suspension' => $suspension
		];
	}

	public function getPunishment($id, $category)
	{
		$period = $this->dm->getperiod();

		$this->db->select('b.name, b.category')->from('punishments as a');
		$this->db->join('constitutions as b', 'b.id = a.constitution_id');
		$this->db->where(['a.student_id' => $id, 'a.period' => $period, 'b.category' => $category]);
		return $this->db->order_by('a.created_at', 'DESC')->limit(5)->get()->result_object();
	}
}
