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
}
