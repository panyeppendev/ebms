<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LandingModel extends CI_Model
{
	public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($data) {
            return $data->step;
        } else {
            return 0;
        }
    }

    public function checkSaldo()
    {
        $step = $this->step();
        $id = $this->input->post('id', true);

        $checkStudent = $this->db->get_where('students', ['id' => $id])->row_object();
        if (!$checkStudent) {
            return [
                'status' => 400,
                'message' => 'NIS tidak valid'
            ];
        }

        if ($step === 0 || $step === '0' || $step === '') {
            return [
                'status' => 400,
                'message' => 'Tahap pembelian paket belum diatur'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function getdata()
    {
        $step = $this->step();
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
            'step' => $step,
            'student' => $checkStudent,
            'package' => $this->getDetailPackage($id, $step),
            'deposit' => $this->getDeposit($id),
			'presence' => $this->getPresence($id)
        ];
    }

	public function getDetailPackage($id, $step)
	{
		$period = $this->dm->getperiod();

		$getPackage = $this->db->get_where('packages', [
			'student_id' => $id,
			'step' => $step,
			'package !=' => 'UNKNOWN',
			'period' => $period
		])->row_object();

		if (!$getPackage) {
			return [
				'status' => 400,
				'message' => "Santri ini belum beli paket $step",
			];
		}

		$package = $getPackage->package;
		$idPackage = $getPackage->id;

		if ($package == 'A' || $package == 'B') {
			$limit = 150000;
		} elseif ($package == 'C' || $package == 'D') {
			$limit = 300000;
		} else {
			$limit = 0;
		}

		$this->db->select('status, SUM(amount) as total')->from('package_transaction');
		$result = $this->db->where(['package_id' => $idPackage, 'type' => 'POCKET'])->group_by('status')->get()->result_object();
		$cash = 0;
		$all = 0;
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$status = $item->status;
				$all += $item->total;

				if ($status == 'POCKET_CASH') {
					$cash += $item->total;
				}else{
					$data[] = [
						'status' => $item->status,
						'total' => $item->total
					];
				}
			}
		}
		$nonCash = $all - $cash;

		return [
			'status' => 200,
			'message' => 'Success',
			'info' => [
				'package' => $package,
				'limit' => $limit,
				'cash' => $cash,
				'non_cash' => $nonCash,
				'detail' => $data,
				'residual' => $limit - $all
			]
		];
	}

	public function getDeposit($nis)
	{
		$period = $this->dm->getperiod();

		$kredit = $this->db->select('SUM(deposit) AS deposit')->from('packages')->where([
			'student_id' => $nis, 'period' => $period
		])->get()->row_object();
		if (!$kredit || $kredit->deposit == '') {
			$kredit = 0;
		} else {
			$kredit = $kredit->deposit;
		}

		$this->db->select('status, SUM(amount) as total')->from('package_transaction');
		$result = $this->db->where(['student_id' => $nis, 'type' => 'DEPOSIT'])->group_by('status')->get()->result_object();
		$cash = 0;
		$all = 0;
		$data = [];
		if ($result) {
			foreach ($result as $item) {
				$status = $item->status;
				$all += $item->total;

				if ($status == 'DEPOSIT_CASH') {
					$cash += $item->total;
				}else{
					$data[] = [
						'status' => $item->status,
						'total' => $item->total
					];
				}
			}
		}
		$nonCash = $all - $cash;

		$total = $kredit - $all;
		return [
			'kredit' => $kredit,
			'cash' => $cash,
			'non_cash' => $nonCash,
			'detail' => $data,
			'residual' => $total
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
