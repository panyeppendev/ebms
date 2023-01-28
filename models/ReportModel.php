<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    public function step()
    {
        $package = $this->db->get_where('steps', ['status' => 'PAYMENT'])->row_object();
        if ($package) {
            $package = $package->step;
        } else {
            $package = 0;
        }

        $disbursement = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($disbursement) {
            $disbursement = $disbursement->step;
        } else {
            $disbursement = 0;
        }

        return [
            $package,
            $disbursement
        ];
    }

    public function reportPocket($step)
    {
        $this->db->select('a.package, COUNT(a.id) AS qty, b.amount');
        $this->db->from('packages AS a')->join('package_detail AS b', 'b.package_id = a.id');
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        $this->db->where('b.account_id', 'P11')->group_by('a.package');
        return $this->db->get()->result_object();
    }

    public function besidesPocket($step)
    {
        $this->db->select('COUNT(a.id) AS qty, b.amount, c.name');
        $this->db->from('packages AS a')->join('package_detail AS b', 'b.package_id = a.id');
        $this->db->join('payment_account AS c', 'c.id = b.account_id');
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        $this->db->where('b.account_id !=', 'P11')->group_by('b.account_id');
        return $this->db->get()->result_object();
    }

    public function disbursement($step)
    {
        $startDate = $this->input->post('startDate', true);
        $endDate = $this->input->post('endDate', true);
        $period = $this->dm->getperiod();

        $this->db->select('b.domicile');
        $this->db->select('SUM(IF(c.account_id = "P11", c.amount, 0)) AS pocket');
        $this->db->select('SUM(IF(c.account_id = "P13", c.amount, 0)) AS breakfast');
        $this->db->select('SUM(IF(c.account_id = "P12", c.amount, 0)) AS dpu ');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->join('package_detail AS c', 'c.package_id = a.id');
        $this->db->where([
            'a.period' => $period,
            'a.status !=' => 'INORDER'
        ]);
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        $kredit = $this->db->group_by('b.domicile')->get()->result_object();

        if ($kredit) {
            $data = [];
            foreach ($kredit as $d) {
                $data[] = [
                    'domicile' => $d->domicile,
                    'kredit_pocket' => $d->pocket,
                    'debet_pocket' => $this->debet($step, $d->domicile, $startDate, $endDate)[0],
                    'kredit_breakfast' => $d->breakfast,
                    'debet_breakfast' => $this->debet($step, $d->domicile, $startDate, $endDate)[1],
                    'kredit_dpu' => $d->dpu,
                    'debet_dpu' => $this->debet($step, $d->domicile, $startDate, $endDate)[2]
                ];
            }

            return [
                'status' => 200,
                'data' => $data
            ];
        } else {
            return [
                'status' => 400,
                'data' => []
            ];
        }
    }

    public function debet($step, $domicile, $startDate, $endDate)
    {
        $period = $this->dm->getperiod();
        $this->db->select('b.domicile');
        $this->db->select('SUM(IF(c.type = "POCKET", c.amount, 0)) AS kredit_pocket');
        $this->db->select('SUM(IF(c.type = "BREAKFAST", c.amount, 0)) AS kredit_breakfast');
        $this->db->select('SUM(IF(c.type = "DPU", c.amount, 0)) AS kredit_dpu ');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->join('package_transaction AS c', 'c.package_id = a.id');
        $this->db->where([
            'a.period' => $period,
            'a.status !=' => 'INORDER',
            'b.domicile' => $domicile
        ]);
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        if ($startDate != '' && $endDate != '') {
            $start = date('Y-m-d H:i:s', strtotime($startDate . ' 00:00:00'));
            $end = date('Y-m-d H:i:s', strtotime($endDate . ' 23:59:59'));
            $this->db->where('c.created_at >=', $start);
            $this->db->where('c.created_at <=', $end);
        }
        $data = $this->db->get()->row_object();
        if ($data) {
            $pocket = $data->kredit_pocket;
            $breakfast = $data->kredit_breakfast;
            $dpu = $data->kredit_dpu;
        } else {
            $pocket = 0;
            $breakfast = 0;
            $dpu = 0;
        }

        return [
            $pocket, $breakfast, $dpu
        ];
    }

    public function detailPayment($step)
    {
        $period = $this->dm->getperiod();
        $this->db->select('a.package, b.id, b.name, b.village, b.city, b.domicile');
        $this->db->select('MAX(IF(c.account_id = "P11", c.amount, 0)) AS pocket');
        $this->db->select('MAX(IF(c.account_id = "P12", c.amount, 0)) AS dpu');
        $this->db->select('MAX(IF(c.account_id = "P13", c.amount, 0)) AS breakfast ');
        $this->db->select('MAX(IF(c.account_id = "P15", c.amount, 0)) AS transport ');
        $this->db->select('MAX(IF(c.account_id = "P14", c.amount, 0)) AS admin ');
        $this->db->select('a.amount');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->join('package_detail AS c', 'c.package_id = a.id', 'left');
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        $this->db->where([
            'a.status !=' => 'INORDER',
            'a.period' => $period
        ]);
        return $this->db->group_by('a.id')->get()->result_object();
    }

    public function detailDisbursement($step)
    {
        $startDate = $this->input->post('startDate', true);
        $endDate = $this->input->post('endDate', true);
        $period = $this->dm->getperiod();
        $this->db->select('a.package, b.id, b.name, b.village, b.city, b.domicile');
        $this->db->select('SUM(IF(c.status = "POCKET_CASH", c.amount, 0)) AS pocket_cash');
        $this->db->select('SUM(IF(c.status = "POCKET_CANTEEN", c.amount, 0)) AS pocket_canteen');
        $this->db->select('SUM(IF(c.status = "POCKET_STORE", c.amount, 0)) AS pocket_store');
        $this->db->select('SUM(IF(c.status = "POCKET_LIBRARY", c.amount, 0)) AS pocket_library');
        $this->db->select('SUM(IF(c.status = "BREAKFAST", c.amount, 0)) AS breakfast');
        $this->db->select('SUM(IF(c.status = "MORNING", c.amount, 0)) AS morning');
        $this->db->select('SUM(IF(c.status = "AFTERNOON", c.amount, 0)) AS afternoon');
        $this->db->from('packages AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->join('package_transaction AS c', 'c.package_id = a.id', 'left');
        if ($step != '' && $step != 0) {
            $this->db->where('a.step', $step);
        }
        if ($startDate != '' && $endDate != '') {
            $start = date('Y-m-d H:i:s', strtotime($startDate . ' 00:00:00'));
            $end = date('Y-m-d H:i:s', strtotime($endDate . ' 23:59:59'));
            $this->db->where('c.created_at >=', $start);
            $this->db->where('c.created_at <=', $end);
        }
        $this->db->where([
            'a.status !=' => 'INORDER',
            'a.period' => $period
        ]);
        return $this->db->group_by('a.id')->order_by('b.domicile ASC, a.package ASC')->get()->result_object();
    }

    public function deposit()
    {
        $period = $this->dm->getperiod();
        $packages = $this->db->get_where('packages', [
            'period' => $period,
            'deposit >' => 0
        ])->result_object();
        if ($packages) {
            foreach ($packages as $p) {
                $studentId = $p->student_id;
                $this->db->where([
                    'package_id' => $p->id,
                    'student_id' => NULL,
                    'type' => 'DEPOSIT'
                ])->update('package_transaction', [
                    'student_id' => $studentId
                ]);
            }
        }
        $this->db->select('SUM(a.deposit) AS deposit, b.id, b.name, b.village, b.city, b.domicile');
        $this->db->from('students AS b');
        $this->db->join('packages AS a', 'a.student_id = b.id');
        $this->db->where([
            'a.status !=' => 'INORDER',
            'a.period' => $period,
            'a.deposit >' => 0
        ]);
        $data = $this->db->group_by('a.student_id')->order_by('b.domicile ASC, a.student_id ASC')->get()->result_object();

        $deposits = [];
        if ($data) {
            foreach ($data as $d) {
                $debit = $this->getDebit($d->id);
                $deposits[] = [
                    'id' => $d->id,
                    'name' => $d->name,
                    'village' => $d->village,
                    'city' => $d->city,
                    'domicile' => $d->domicile,
                    'deposit' => $d->deposit,
                    'cash' => $debit->cash,
                    'canteen' => $debit->canteen,
                    'store' => $debit->store,
                    'library' => $debit->library
                ];
            }

            return $deposits;
        }

        return $deposits;
    }

    public function getDebit($id)
    {
        $this->db->select('SUM(IF(status = "DEPOSIT_CASH", amount, 0)) AS cash');
        $this->db->select('SUM(IF(status = "DEPOSIT_CANTEEN", amount, 0)) AS canteen');
        $this->db->select('SUM(IF(status = "DEPOSIT_STORE", amount, 0)) AS store');
        $this->db->select('SUM(IF(status = "DEPOSIT_LIBRARY", amount, 0)) AS library');
        return $this->db->from('package_transaction')->where('student_id', $id)->get()->row_object();
    }

    public function log($type, $date)
    {
        $this->db->select('b.name, b.domicile, a.amount, a.type, a.created_at');
        $this->db->from('package_transaction AS a')->join('packages AS c', 'c.id = a.package_id');
        $this->db->join('students AS b', 'b.id = c.student_id');
        $this->db->where_in('a.status', [
            'POCKET_'.$type,
            'DEPOSIT_'.$type
        ]);
        $this->db->where([
            'DATE(a.created_at)' => $date
        ]);
        return $this->db->get()->result_object();
    }
}
