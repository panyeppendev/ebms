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

        if ($step == 0) {
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

    public function getSaldo($id, $step)
    {
        $this->db->select_sum('residual')->from('pockets');
        $this->db->where(['student_id' => $id, 'step' => $step, 'status' => 'CHECKED']);
        $getPocket = $this->db->get()->row_object();

        $this->db->select_sum('amount')->from('deposits');
        $this->db->where(['student_id' => $id, 'step' => $step]);
        $getDeposit = $this->db->get()->row_object();

        return $getPocket->residual + $getDeposit->amount;
    }

    public function getdata()
    {
        $step = $this->step();
        $id = $this->input->post('id', true);

        $checkStudent = $this->db->get_where('students', ['id' => $id])->row_object();
        if (!$checkStudent) {
            return [
                'status' => 400,
                'message' => 'Data santri tidak ditemukan'
            ];
        }

        //GET PACKAGE 
        $checkPackage = $this->db->get_where('packages', [
            'student_id' => $id,
            'step' => $step,
            'package !=' => 'UNKNOWN'
        ])->row_object();

        if (!$checkPackage) {
            return [
                'status' => 200,
                'student' => $checkStudent,
                'package' => 400,
                'package_message' => 'Santri ini belum beli paket pada tahap ke-' . $step
            ];
        }

        $packageID = $checkPackage->id;
        $text = [
            'A' => 150000,
            'B' => 150000,
            'C' => 300000,
            'D' => 300000
        ];
        $amount = $text[$checkPackage->package];

        //GET DEPOSIT
        $this->db->select('SUM(amount) AS total')->from('package_deposit');
        $deposit = $this->db->where('package_id', $packageID)->get()->row_object();
        if (!$deposit) {
            $deposit = 0;
        } else {
            $deposit = $deposit->total;
        }

        //GET POCKET
        $this->db->select('SUM(amount) AS total')->from('package_transaction');
        $pocket = $this->db->where([
            'package_id' => $packageID, 'type' => 'POCKET'
        ])->get()->row_object();
        if (!$pocket) {
            $pocket = 0;
        } else {
            $pocket = $pocket->total;
        }

        return [
            'status' => 200,
            'step' => $step,
            'student' => $checkStudent,
            'package' => 200,
            'package_name' => $checkPackage->package,
            'package_message' => [
                'deposit' => number_format($deposit, 0, ',', '.'),
                'pocket' => number_format($amount - $pocket, 0, ',', '.')
            ]
        ];
    }
}
