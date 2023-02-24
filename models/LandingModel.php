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
            'deposit' => $this->getDeposit($id)
        ];
    }

	public function getDetailPackage($id, $step)
	{
		$getPackage = $this->db->get_where('packages', [
			'student_id' => $id,
			'step' => $step,
			'package !=' => 'UNKNOWN'
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

		$cash = $this->db->select('SUM(amount) AS total')->from('package_transaction')->where([
			'package_id' => $idPackage, 'status' => 'POCKET_CASH'
		])->get()->row_object();
		if (($cash && $cash->total != '') || $cash->total != 0) {
			$cash = $cash->total;
		} else {
			$cash = 0;
		}

		$this->db->select('SUM(amount) AS total')->from('package_transaction')->where('package_id', $idPackage);
		$nonCash = $this->db->where_in('status', [
			'POCKET_CANTEEN',
			'POCKET_STORE',
			'POCKET_LIBRARY',
			'POCKET_SECURITY',
			'POCKET_BARBER'
		])->get()->row_object();
		if (($nonCash && $nonCash->total != '') || $nonCash->total != 0) {
			$nonCash = $nonCash->total;
		} else {
			$nonCash = 0;
		}

		return [
			'status' => 200,
			'message' => 'Success',
			'info' => [
				'package' => $package,
				'limit' => $limit,
				'cash' => $cash,
				'non_cash' => $nonCash,
				'residual' => $limit - ($cash + $nonCash)
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

		$this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
		$this->db->join('package_transaction AS b', 'b.package_id = a.id');
		$cash = $this->db->where([
			'a.student_id' => $nis, 'a.period' => $period, 'b.status' => 'DEPOSIT_CASH'
		])->get()->row_object();
		if (!$cash || $cash->amount == '') {
			$cash = 0;
		} else {
			$cash = $cash->amount;
		}

		$this->db->select('a.student_id, SUM(b.amount) AS amount')->from('packages AS a');
		$this->db->join('package_transaction AS b', 'b.package_id = a.id');
		$this->db->where_in('b.status', [
			'DEPOSIT_CANTEEN',
			'DEPOSIT_STORE',
			'DEPOSIT_LIBRARY',
			'DEPOSIT_SECURITY',
			'DEPOSIT_BARBER'
		]);
		$nonCash = $this->db->where([
			'a.student_id' => $nis, 'a.period' => $period
		])->get()->row_object();
		if (!$nonCash || $nonCash->amount == '') {
			$nonCash = 0;
		} else {
			$nonCash = $nonCash->amount;
		}

		$total = $kredit - ($cash + $nonCash);
		return [
			'kredit' => $kredit,
			'cash' => $cash,
			'non_cash' => $nonCash,
			'residual' => $total
		];
	}
}
