<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PackageModel extends CI_Model
{
    public function step()
    {
        return $this->db->get_where('steps', ['status' => 'PAYMENT'])->row_object();
    }

    public function loadData()
    {
        $period = $this->dm->getperiod();
        $name = $this->input->post('name', true);
        $step = $this->input->post('step', true);
        $status = $this->input->post('status', true);
        $domicile = $this->input->post('domicile', true);

        $this->db->select('a.id AS id_package, a.amount, a.package, a.student_id, a.activated_at, a.expired_at, a.transport, a.status, b.id, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal, b.name, b.village, b.city')->from('packages AS a');
        $this->db->join('students AS b', 'a.student_id = b.id');
        $this->db->where(['a.period' => $period, 'a.step' => $step]);
        if ($name != '') {
            $this->db->like('b.name', $name);
        }
        if ($status != '') {
            $this->db->where('a.status', $status);
        }
        if ($domicile != '') {
            $this->db->where('b.domicile', $domicile);
        }

        return $this->db->order_by('a.status', 'ASC')->get()->result_object();
    }

    public function checkId()
    {
        $period = $this->dm->getperiod();
        $id = str_replace('_', '', $this->input->post('id', true));
        $step = $this->input->post('step', true);

        $check = $this->db->get_where('students', [
            'id' => $id, 'status' => 'AKTIF'
        ])->row_object();

        if (!$check) {
            return [
                'status' => 400,
                'message' => 'Santri tidak ditemukan'
            ];
        }

        $checkPackage = $this->db->get_where('packages', [
            'student_id' => $id, 'period' => $period,
            'step' => $step, 'status !=' => 'INORDER'
        ])->num_rows();

        if ($checkPackage > 0) {
            return [
                'status' => 400,
                'message' => 'Santri sudah membeli paket'
            ];
        }

        $levelOfFormal = $check->level_of_formal;
        return [
            'status' => 200,
            'message' => 'Sukses',
            'level' => ($levelOfFormal == 'SD'),
            'id' => $id
        ];
    }

    public function showCheck()
    {
        $id = $this->input->post('id', true);
        return $this->db->get_where('students', ['id' => $id])->row_object();
    }

    public function showRate()
    {
        $package = $this->input->post('package', true);
        $level = $this->input->post('level', true);

        return $this->getRate($package, $level);
    }

    public function getRate($package, $level)
    {
        $period = $this->dm->getperiod();
        $qTransport = $this->db->get_where('rates', ['type' => 'SD', 'period' => $period])->row_object();
        $qPackage = $this->db->get_where('rates', ['type' => $package, 'period' => $period])->row_object();
        if ($level == 'true') {
            return [
                'package' => $qPackage->amount,
                'transport' => $qTransport->amount,
                'total' => $qPackage->amount + $qTransport->amount,
                'status' => 1
            ];
        } else {
            return [
                'package' => $qPackage->amount,
                'transport' => 0,
                'total' => $qPackage->amount,
                'status' => 0
            ];
        }
    }

    public function savePackage()
    {
        $period = $this->dm->getperiod();
        $invoice = $this->idGenerator();
        $id = $this->input->post('id_student', true);
        $step = $this->input->post('step', true);
        $level = $this->input->post('level_of_formal', true);
        $package = $this->input->post('package', true);

        if ($package == '') {
            return [
                'status' => 400,
                'data' => 0,
                'step' => 0,
                'message' => 'Pastikan paket sudah dipilih'
            ];
        }

        $rates = $this->getRate($package, $level);
        $conditions = ['student_id' => $id, 'period' => $period, 'step' => $step];
        $checkPackage = $this->db->get_where('packages', $conditions)->num_rows();

        if ($checkPackage > 0) {
            $data = [
                'id' => $invoice,
                'created_at' => date('Y-m-d'),
                'created_at_hijriah' => getHijri(),
                'package' => $package,
                'transport' => ($rates['transport'] <= 0) ? 0 : 1,
                'amount' => $rates['total'],
                // 'total' => $rates['total'],
                'status' => 'INACTIVE',
                'user' => $this->session->userdata('name')
            ];
            $this->db->where($conditions)->update('packages', $data);
        } else {
            $data = [
                'id' => $invoice,
                'student_id' => $id,
                'period' => $period,
                'created_at' => date('Y-m-d'),
                'created_at_hijriah' => getHijri(),
                'package' => $package,
                'step' => $step,
                'transport' => ($rates['transport'] <= 0) ? 0 : 1,
                'amount' => $rates['total'],
                // 'total' => $rates['total'],
                'status' => 'INACTIVE',
                'user' => $this->session->userdata('name')
            ];
            $this->db->insert('packages', $data);
        }

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'data' => 0,
                'step' => 0,
                'message' => 'Gagal saat mencoba menyimpan data. Ulangi lagi!'
            ];
        }

        $detailPackage = $this->setDetailPackage($invoice, $package, $level);
        if ($detailPackage <= 0) {
            return [
                'status' => 400,
                'data' => 0,
                'step' => 0,
                'message' => 'Gagal saat mencoba menyimpan data. Ulangi lagi!'
            ];
        }

        return [
            'status' => 200,
            'data' => $invoice,
            'step' => $step,
            'message' => 'Sukses'
        ];
    }

    public function setDetailPackage($id, $package, $level)
    {
        $period = $this->dm->getperiod();
        $this->db->select('*')->from('payment_package_detail');
        if ($package == 'A' || $package == 'B') {
            $this->db->where_in('package', ['GENERAL', 'AB']);
        } else {
            $this->db->where_in('package', ['GENERAL', 'CD']);
        }

        if ($package == 'A' || $package == 'C') {
            $this->db->where('account_id !=', 'P13');
        }

        if ($level == 'false') {
            $this->db->where('account_id !=', 'P15');
        }
        $this->db->where('period', $period);
        $data = $this->db->order_by('account_id', 'ASC')->get()->result_object();
        if (!$data) {
            return 0;
        }

        foreach ($data as $d) {
            $this->db->insert('package_detail', [
                'package_id' => $id,
                'account_id' => $d->account_id,
                'amount' => $d->amount
            ]);
        }

        return $this->db->affected_rows();
    }

    public function idGenerator()
    {
        $now = getMasehiExplode();
        return $now[2] . $now[1] . $now[0] . mt_rand(0000000, 9999999);
    }

    public function deletePackage()
    {
        $id = $this->input->post('id', true);
        $this->db->where('id', $id)->delete('packages');
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function activePackage()
    {
        $id = $this->input->post('id', true);
        $actived = date('Y-m-d');
        $tenor = mktime(0, 0, 0, date('n'), date('j') + 30, date('Y'));
        $expired = date('Y-m-d', $tenor);

        $check = $this->db->get_where('packages', ['id' => $id])->num_rows();
        if (!$check || $check <= 0) {
            return [
                'status' => 400,
                'message' => 'Data paket tidak valid'
            ];
        }

        $this->db->where('id', $id)->update('packages', [
            'activated_at' => $actived,
            'expired_at' => $expired,
            'status' => 'ACTIVE'
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal..! Kesalahan server'
            ];
        }
        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function packageActivation()
    {
        $step = $this->input->post('step', true);
        $period = $this->dm->getperiod();
        $actived = date('Y-m-d');
        $tenor = mktime(0, 0, 0, date('n'), date('j') + 30, date('Y'));
        $expired = date('Y-m-d', $tenor);

        $conditions = ['period' => $period, 'step' => $step, 'status' => 'INACTIVE'];
        $check = $this->db->get_where('packages', $conditions)->num_rows();
        if (!$check || $check <= 0) {
            return [
                'status' => 400,
                'message' => 'Tidak ada paket yang perlu diaktifkan'
            ];
        }

        $this->db->where($conditions)->update('packages', [
            'activated_at' => $actived,
            'expired_at' => $expired,
            'status' => 'ACTIVE'
        ]);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal! Kesalahan server'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function dataPrint($package)
    {
        $getPackage = $this->db->get_where('packages', [
            'id' => $package
        ])->row_object();
        $id = $getPackage->student_id;
        $student = $this->db->get_where('students', ['id' => $id])->row_object();

        $this->db->select('a.*, b.name')->from('package_detail AS a');
        $this->db->join('payment_account AS b', 'a.account_id = b.id');
        $this->db->where('a.package_id', $package);
        $detail = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();

        return [$getPackage, $student, $detail];
    }
}
