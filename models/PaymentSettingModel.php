<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentSettingModel extends CI_Model
{
    public function getSetting()
    {
        $period = $this->dm->getperiod();
        $check = $this->db->get_where('payment_setting', ['period' => $period])->num_rows();
        if ($check > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getRates()
    {
        $period = $this->dm->getperiod();
        return $this->db->get_where('rates', ['period' => $period])->result_object();
    }

    public function loadData()
    {
        $type = $this->input->post('type', true);
        $period = $this->dm->getperiod();

        $this->db->select('b.name, a.account_id, a.id, a.amount, a.type')->from('payment_rate_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where('a.period', $period);
        if ($type != '') {
            $this->db->where('a.type', $type);
        }
        return $this->db->order_by('a.account_id', 'ASC')->get()->result_object();
    }

    public function loadPackage()
    {
        $package = $this->input->post('package', true);
        $period = $this->dm->getperiod();

        $this->db->select('b.name, a.account_id, a.id, a.amount, a.package')->from('payment_package_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where('a.period', $period);
        if ($package != '') {
            $this->db->where('a.package', $package);
        }
        return $this->db->order_by('a.account_id', 'ASC')->get()->result_object();
    }

    public function loadShift()
    {
        return $this->db->get('shifts')->result_object();
    }

    public function accountById()
    {
        $period = $this->dm->getperiod();
        $this->db->select('b.name, a.account_id, a.id, a.amount')->from('payment_rate_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where(['a.period' => $period, 'a.type' => 'NEW']);
        $new = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();

        $this->db->select('b.name, a.account_id, a.id, a.amount')->from('payment_rate_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where(['a.period' => $period, 'a.type' => 'OLD']);
        $old = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();
        return [
            $new,
            $old
        ];
    }

    public function packageByType()
    {
        $period = $this->dm->getperiod();
        $this->db->select('b.name, a.account_id, a.id, a.amount')->from('payment_package_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where(['a.period' => $period, 'a.package' => 'GENERAL']);
        $general = $this->db->order_by('a.account_id', 'ASC')->get()->result_object();

        $this->db->select('b.name, a.account_id, a.id, a.amount')->from('payment_package_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where(['a.period' => $period, 'a.package' => 'AB']);
        $ab = $this->db->order_by('a.account_id', 'ASC')->get()->row_object();

        $this->db->select('b.name, a.account_id, a.id, a.amount')->from('payment_package_detail AS a');
        $this->db->join('payment_account AS b', 'b.id = a.account_id');
        $this->db->where(['a.period' => $period, 'a.package' => 'CD']);
        $cd = $this->db->order_by('a.account_id', 'ASC')->get()->row_object();
        return [
            $general,
            $ab,
            $cd
        ];
    }

    public function setRate()
    {
        $period = $this->dm->getperiod();
        $type = $this->input->post('type', true);
        $checkRate = $this->db->get_where('payment_rate_detail', ['type' => $type])->num_rows();
        if ($checkRate <= 0) {
            $data = $this->db->get_where('payment_account', ['type' => $type])->result_object();
            if ($data) {
                foreach ($data as $row) {
                    $this->db->insert('payment_rate_detail', [
                        'account_id' => $row->id,
                        'amount' => 0,
                        'type' => $type,
                        'period' => $period
                    ]);
                }
            }
        }
    }

    public function saveRate()
    {
        $code = $this->input->post('code', true);
        $amount = str_replace('.', '', $this->input->post('amount', true));
        $data = [];
        foreach ($code as $key => $value) {
            $data[] = [
                'id' => $code[$key],
                'amount' => $amount[$key]
            ];
        }
        $this->db->update_batch('payment_rate_detail', $data, 'id');
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function setPayment()
    {
        $period = $this->dm->getperiod();
        $check = $this->db->get_where('payment_setting', ['period' => $period])->num_rows();
        if ($check <= 0) {
            $this->setRates();
            $this->db->insert('payment_setting', ['period' => $period]);
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 200,
                    'message' => 'Berhasil'
                ];
            } else {
                return [
                    'status' => 400,
                    'message' => 'Ada yang salah di server'
                ];
            }
        } else {
            return [
                'status' => 400,
                'message' => 'Tarif pembayaran sudah diatur sebelumnya'
            ];
        }
    }

    public function setRates()
    {
        $period = $this->dm->getperiod();
        //CHECK CURRENT PERIOD
        $check = $this->db->get_where('rates', ['period' => $period])->num_rows();
        if ($check <= 0) {
            //Rate Payment
            $this->db->select('SUM(amount) AS total, type')->from('payment_rate_detail');
            $this->db->where('period', $period);
            $dataPayment = $this->db->group_by('type')->get()->result_object();
            foreach ($dataPayment as $dp) {
                //SET RATE PAYMENT
                $this->db->insert('rates', [
                    'type' => $dp->type,
                    'amount' => $dp->total,
                    'period' => $period
                ]);
            }

            //RATE PACKAGES
            //TOTAL GENERAL PACKAGES
            $this->db->select_sum('amount')->from('payment_package_detail');
            $this->db->where(['period' => $period, 'package' => 'GENERAL', 'account_id !=' => 'P15']);
            $dGeneral = $this->db->get()->row_object();
            $general = $dGeneral->amount;

            //TOTAL BREAKFAST PACKAGE
            $dBreakfast = $this->db->get_where('payment_package_detail', ['period' => $period, 'account_id' => 'P13'])->row_object();
            $breakfast = $dBreakfast->amount;

            //PACKAGE AB
            $dAB = $this->db->get_where('payment_package_detail', ['period' => $period, 'package' => 'AB'])->row_object();
            $ab = $dAB->amount;

            //PACKAGE CD
            $dCD = $this->db->get_where('payment_package_detail', ['period' => $period, 'package' => 'CD'])->row_object();
            $cd = $dCD->amount;

            //PACKAGE TRANSPORT
            $dTransport = $this->db->get_where('payment_package_detail', ['period' => $period, 'account_id' => 'P15'])->row_object();
            $sd = $dTransport->amount;

            //SET RATE PACKAGE A
            $this->db->insert('rates', [
                'type' => 'A',
                'amount' => ($general + $ab) - $breakfast,
                'period' => $period
            ]);

            //SET RATE PACKAGE B
            $this->db->insert('rates', [
                'type' => 'B',
                'amount' => $general + $ab,
                'period' => $period
            ]);

            //SET RATE PACKAGE C
            $this->db->insert('rates', [
                'type' => 'C',
                'amount' => ($general + $cd) - $breakfast,
                'period' => $period
            ]);

            //SET RATE PACKAGE D
            $this->db->insert('rates', [
                'type' => 'D',
                'amount' => $general + $cd,
                'period' => $period
            ]);

            //SET RATE TRANSPORT SD
            $this->db->insert('rates', [
                'type' => 'SD',
                'amount' => $sd,
                'period' => $period
            ]);
        }
    }

    public function setpackage()
    {
        $period = $this->dm->getperiod();
        $package = $this->input->post('package', true);
        $checkPackage = $this->db->get_where('payment_package_detail', ['package' => $package])->num_rows();
        if ($checkPackage <= 0) {
            if ($package == 'GENERAL') {
                $whereClause = ['type' => 'PACKAGE', 'id !=' => 'P11'];
            } else {
                $whereClause = ['type' => 'PACKAGE', 'id' => 'P11'];
            }

            $data = $this->db->get_where('payment_account', $whereClause)->result_object();
            if ($data) {
                foreach ($data as $row) {
                    $this->db->insert('payment_package_detail', [
                        'account_id' => $row->id,
                        'amount' => 0,
                        'package' => $package,
                        'period' => $period
                    ]);
                }
            }
        }
    }

    public function savePackages()
    {
        $code = $this->input->post('code', true);
        $amount = str_replace('.', '', $this->input->post('amount', true));
        $data = [];
        foreach ($code as $key => $value) {
            $data[] = [
                'id' => $code[$key],
                'amount' => $amount[$key]
            ];
        }
        $this->db->update_batch('payment_package_detail', $data, 'id');
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function savePackage()
    {
        $code = $this->input->post('code', true);
        $amount = str_replace('.', '', $this->input->post('amount', true));
        $this->db->where('id', $code)->update('payment_package_detail', ['amount' => $amount]);
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function saveShift()
    {
        $id = $this->input->post('id', true);
        $begin = $this->input->post('begin', true);
        $finish = $this->input->post('finish', true);
        $data = [];
        foreach ($id as $key => $value) {
            $data[] = [
                'id' => $id[$key],
                'begin' => $begin[$key],
                'finish' => $finish[$key]
            ];
        }
        $this->db->update_batch('shifts', $data, 'id');
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function saveStep()
    {
        $step = $this->input->post('step', true);
        $status = $this->input->post('status', true);
        if ($status == 'PAYMENT') {
            $this->setPackagesInorder($step);
        }
        $this->db->where('status', $status)->delete('steps');
        $this->db->insert('steps', [
            'step' => $step, 'status' => $status, 'start_at' => date('Y-m-d')
        ]);
        if ($this->db->affected_rows() > 0) {
            return ['status' => 200];
        } else {
            return ['status' => 400];
        }
    }

    public function setPackagesInorder($step)
    {
        $period = $this->dm->getperiod();
        $check = $this->db->get_where('packages', [
            'period' => $period, 'step' => $step
        ])->num_rows();
        if ($check <= 0) {
            $get = $this->db->get_where('students', ['status' => 'AKTIF'])->result_object();
            if ($get) {
                $data = [];
                foreach ($get as $d) {
                    $data[] = [
                        'id' => mt_rand(100000000000, 999999999999),
                        'student_id' => $d->id,
                        'period' => $period,
                        'created_at' => date('Y-m-d'),
                        'created_at_hijriah' => getHijri(),
                        'package' => 'UNKNOWN',
                        'step' => $step,
                        'transport' => 0,
                        'amount' => 0,
                        'deposit' => 0,
                        'status' => 'INORDER',
                        'user' => $this->session->userdata('name')
                    ];
                }
                $this->db->insert_batch('packages', $data);
            }
        }
    }

    public function step()
    {
        return [
            $this->db->get_where('steps', ['status' => 'PAYMENT'])->row_object(),
            $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object()
        ];
    }
}
