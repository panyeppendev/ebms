<?php

use phpDocumentor\Reflection\Types\This;

defined('BASEPATH') or exit('No direct script access allowed');

class ShortModel extends CI_Model
{
    public function step()
    {
        $data = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->row_object();
        if ($data) {
            return [$data->step, $data->start_at];
        } else {
            return [0, date('Y-m-d')];
        }
    }

    public function setting()
    {
        $check = $this->db->get_where('steps', ['status' => 'DISBURSEMENT'])->num_rows();
        if ($check > 0) {
            return 'OPENED';
        } else {
            return 'CLOSED';
        }
    }

    public function reason()
    {
        return $this->db->get_where('reasons', ['type' => 'SHORT'])->result_object();
    }

	public function constitution()
	{
		return $this->db->get_where('constitutions', ['type' => 'PROHIBITION'])->result_object();
	}

    public function checkNis()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
        $step = $this->input->post('step', true);
        $start = $this->step()[1];
        $period = $this->dm->getperiod();
        $masehi = date('Y-m-d');

        //CHECK CARD
        $checkCard = $this->db->get_where('cards', ['id' => $nis])->row_object();
        if (!$checkCard) {
            return [
                'status' => 500,
                'message' => 'Kartu tidak valid'
            ];
        }

        $statusCard = $checkCard->status;
        if ($statusCard != 'ACTIVE') {
            $statusText = ['INACTIVE' => 'belum diaktivasi', 'BLOCKED' => 'sudah diblokir'];
            return [
                'status' => 500,
                'message' => 'Kartu ini ' . $statusText[$statusCard]
            ];
        }

        $nis = $checkCard->student_id;

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->num_rows();
        if ($cekStudent <= 0) {
            return [
                'status' => 500,
                'message' => 'Santri tidak ditemukan'
            ];
        }

		//GET DATA SKORSING
		$suspension = $this->db->get_where('suspensions', [
			'status' => 'ACTIVE', 'student_id' => $nis, 'period' => $this->dm->getperiod()
		])->num_rows();
		if ($suspension > 0) {
			return [
				'status' => 400,
				'message' => 'Santri ini dalam masa skorsing',
				'nis' => $nis
			];
		}

        $checkPermission = $this->db->get_where('permissions', [
            'student_id' => $nis, 'status' => 'ACTIVE'
        ])->row_object();
        if($checkPermission){
            return [
                'status' => 400,
                'message' => 'Santri sedang dalam izin yang aktif',
                'nis' => $nis,
                'package' => 0
            ];
        }

        $checkNominal = $this->checkNominal();
        if ($checkNominal['status'] === 400) {
            return [
                'status' => 400,
                'message' => 'Tarif ijin jarak dekat belum diatur. Hubungi Admin ~',
                'nis' => $nis,
                'package' => 0
            ];
        }

//		$checkPackage = $this->db->get_where('packages', [
//			'student_id' => $nis, 'period' => $period,
//			'step' => $step, 'status' => 'ACTIVE'
//		])->row_object();
//		if (!$checkPackage) {
//			return [
//				'status' => 200,
//				'message' => 'Santri ini tidak punya paket aktif pada tahap saat ini',
//				'nis' => $nis,
//				'package' => 0,
//				'nominal' => $checkNominal['nominal'],
//				'rp' => $checkNominal['rp']
//			];
//		}

		if (date('Y-m-d', strtotime($start)) > $masehi) {
			return [
				'status' => 200,
				'message' => 'Pencairan tahap ' . $step . ' belum dibuka',
				'nis' => $nis,
				'package' => 0,
				'nominal' => $checkNominal['nominal'],
				'rp' => $checkNominal['rp']
			];
		}

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'package' => 0,
            'nominal' => $checkNominal['nominal'],
            'rp' => $checkNominal['rp']
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $package = $this->input->post('package', true);

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();

        return [
            'student' => $data,
//            'package' => $this->getDetailPackage($package),
//            'pocket' => $this->getPocket($package, $this->getDetailPackage($package)['limit']),
//            'daily' => $this->getPocketDaily($package, $this->getDetailPackage($package)['pocket']),
//            'deposit' => $this->getDeposit($nis)
        ];
    }

    public function getDetailPackage($packageID)
    {
        $getPackage = $this->db->get_where('packages', ['id' => $packageID])->row_object();

        if (!$getPackage) {
            return [
                'status' => 400,
                'message' => 'Data paket tidak valid',
                'pocket' => 0,
                'limit' => 0
            ];
        }

        $package = $getPackage->package;
        $transport = $getPackage->transport;
        $teksTransport = ['', ' + Transport'];
        if ($package == 'A' || $package == 'B') {
            $pocket = 5000;
            $limit = 150000;
        } elseif ($package == 'C' || $package == 'D') {
            $pocket = 10000;
            $limit = 300000;
        } else {
            $pocket = 0;
            $limit = 0;
        }

        return [
            'status' => 200,
            'info' => 'Paket ' . $package . $teksTransport[$transport],
            'pocket' => $pocket,
            'limit' => $limit
        ];
    }

    public function getPocket($package, $limit)
    {
        $cash = $this->db->select('SUM(amount) AS total')->from('package_transaction')->where([
            'package_id' => $package, 'status' => 'POCKET_CASH'
        ])->get()->row_object();
        if (($cash && $cash->total != '') || $cash->total != 0) {
            $cash = $cash->total;
        } else {
            $cash = 0;
        }

        $this->db->select('SUM(amount) AS total')->from('package_transaction')->where('package_id', $package);
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
            'limit' => $limit,
            'cash' => $cash,
            'noncash' => $nonCash,
            'total' => $limit - ($cash + $nonCash)
        ];
    }

    public function getPocketDaily($package, $pocket)
    {
        $start = $this->step()[1];
        $masehi = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        $beforeYesterday = date('Y-m-d', strtotime("-2 day", strtotime(date("Y-m-d"))));

        //CEK SALDO
        $firstDiff = new DateTime($start);
        $secondDiff = new DateTime($masehi);
        $diffDate = $secondDiff->diff($firstDiff);

        //CEK KEMARIN
        $checkYesterday = $this->db->get_where('package_transaction', [
            'DATE(created_at)' => $yesterday, 'type' => 'POCKET',
            'package_id' => $package
        ])->num_rows();

        $residual = 0;
        if ($diffDate->d >= 2) {
            if ($checkYesterday <= 0) {
                $checkBeforeYesterday = $this->db->get_where('package_transaction', [
                    'DATE(created_at)' => $beforeYesterday, 'type' => 'POCKET',
                    'package_id' => $package
                ])->num_rows();

                if ($checkBeforeYesterday <= 0) {
                    $residual = $pocket * 2;
                } else {
                    $residual = $pocket;
                }
            } else {
                $residual;
            }
        }

        $cash = $this->db->select('SUM(amount) AS total')->from('package_transaction')->where([
            'package_id' => $package, 'status' => 'POCKET_CASH', 'DATE(created_at)' => $masehi
        ])->get()->row_object();
        if (($cash && $cash->total != '') || $cash->total != 0) {
            $cash = $cash->total;
        } else {
            $cash = 0;
        }

        $this->db->select('SUM(amount) AS total')->from('package_transaction');
        $this->db->where(['package_id' => $package, 'DATE(created_at)' => $masehi]);
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

        $total = ($pocket + $residual) - ($cash + $nonCash);

        return [
            'limit' => $pocket,
            'residual' => $residual,
            'cash' => $cash,
            'noncash' => $nonCash,
            'total' => $total
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
            'noncash' => $nonCash,
            'total' => $total
        ];
    }

    public function save()
    {
        $package = $this->input->post('package', true);
        $nis = $this->input->post('nis', true);
        $nominal = $this->input->post('nominal', true);
        $step = $this->input->post('step', true);
        $status = $this->input->post('status', true);
        $reason = $this->input->post('reason', true);

		$term = $this->db->get('term_setting')->row_object();
		if (!$term) {
			return [
				'status' => 400,
				'message' => 'Lama izin jarak dekat belum diatur'
			];
		}

		if ($reason === '') {
			return [
				'status' => 400,
				'message' => 'Alasan belum dipilih'
			];
		}

        if ($status == 'POCKET') {
            $detailPackage = $this->getDetailPackage($package);
            if ($detailPackage['status'] == 400) {
                return [
                    'status' => 400,
                    'message' => $detailPackage['message']
                ];
            }

            $pocket = $detailPackage['pocket'];
            $limit = $detailPackage['limit'];

            $getPocket = $this->getPocket($package, $limit);
            $totalPocket = $getPocket['total'];

            $pocketDaily = $this->getPocketDaily($package, $pocket);
            $totalPocketDaily = $pocketDaily['total'];
            if ($totalPocket <= 0) {
                $totalPocketDaily = 0;
            } else {
                $totalPocketDaily;
            }

            $deposit = $this->getDeposit($nis);
            $totalDeposit = $deposit['total'];

            $total = $totalPocketDaily + $totalDeposit;

            if ($nominal > $total) {
                return [
                    'status' => 400,
                    'message' => 'Saldo pencairan tidak cukup'
                ];
            }

            //JIKA LIMIT SUDAH HABIS
            if ($totalPocket <= 0) {
                $this->db->insert('package_transaction', [
                    'student_id' => $nis,
                    'package_id' => $package,
                    'created_at' => date('Y-m-d H:i:s'),
                    'amount' => $nominal,
                    'type' => 'DEPOSIT',
                    'status' => 'DEPOSIT_SECURITY'
                ]);
            } else {
                //JIKA NOMINAL > UANG SAKU HARIAN MAKA DEPOSITS KURANGI
                if ($nominal > $totalPocketDaily) {
                    $depositNominal = $nominal - $totalPocketDaily;
                    $nominal = $totalPocketDaily;
                    $this->db->insert('package_transaction', [
                        'student_id' => $nis,
                        'package_id' => $package,
                        'created_at' => date('Y-m-d H:i:s'),
                        'amount' => $depositNominal,
                        'type' => 'DEPOSIT',
                        'status' => 'DEPOSIT_SECURITY'
                    ]);
                }

                if ($nominal > 0) {
                    $this->db->insert('package_transaction', [
                        'student_id' => $nis,
                        'package_id' => $package,
                        'created_at' => date('Y-m-d H:i:s'),
                        'amount' => $nominal,
                        'type' => 'POCKET',
                        'status' => 'POCKET_SECURITY'
                    ]);
                }
            }
        }

        $this->db->insert('payment_security', [
            'student_id' => $nis,
            'step' => $step,
            'amount' => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'period' => $this->dm->getperiod(),
            'type' => 'SHORT',
            'status' => $status
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        $reference = $this->referenceGenerator();
        $this->db->insert('permissions', [
            'order' => $reference,
            'reference' => $reference,
            'step' => $step,
            'period' => $this->dm->getperiod(),
            'student_id' => $nis,
            'reason' => $reason,
            'created_at' => date('Y-m-d H:i:s'),
            'expired_at' => date('Y-m-d H:i:s', strtotime(' + '.$term->term.' minutes')),
            'type' => 'SHORT',
            'status' => 'ACTIVE',
			'payment' => $status
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'package' => $package
        ];
    }

    public function referenceGenerator()
    {
        $period = $this->dm->getperiod();
        $check = $this->db->order_by('created_at', 'DESC')->get_where('permissions', [
            'period' => $period, 'type' => 'SHORT'
        ])->row_object();
        if ($check) {
            $order = $check->order + 1;
        }else {
            $order = 1;
        }

        return $order;
    }

    public function checkNominal()
    {
        $data = $this->db->get_where('store_setting', [
            'name' => 'SHORT'
        ])->row_object();
        if (!$data) {
            return [
                'status' => 400,
                'nominal' => 0,
                'rp' => 'Rp. '.number_format(0, 0, ',', '.')
            ];
        }

        return [
            'status' => 200,
            'nominal' => $data->price,
            'rp' => 'Rp. '.number_format($data->price, 0, ',', '.')
        ];
    }

    public function loadRecap()
    {
        $name = $this->input->post('name', true);
        $filter = $this->input->post('filter', true);
        $now = date('Y-m-d');
        $this->db->select('SUM(amount) AS amount')->from('payment_security');
        if ($filter != '') {
            $this->db->where('DATE(created_at)', $filter);
        } else {
            $this->db->where('DATE(created_at)', $now);
        }
        $this->db->where('type !=', 'BARBER');
        $amount = $this->db->get()->row_object();
        if ($amount) {
            if ($amount->amount != '' || $amount->amount != 0) {
                $amount = $amount->amount;
            } else {
                $amount = 0;
            }
        } else {
            $amount = 0;
        }

        $this->db->select('a.amount, a.created_at, a.status, a.type, b.name, b.domicile');
        $this->db->from('payment_security AS a');
        $this->db->join('students AS b', 'b.id = a.student_id');
        if ($filter != '') {
            $this->db->where('DATE(a.created_at)', $filter);
        } else {
            $this->db->where('DATE(a.created_at)', $now);
        }
        if ($name != '') {
            $this->db->like('b.name', $name);
        }
        $this->db->where('a.type !=', 'BARBER');
        $result = $this->db->get();

        return [
            $amount,
            $result->num_rows(),
            $result->result_object()
        ];
    }

    public function loadPermission()
    {
        $name = $this->input->post('name', true);
        $status = $this->input->post('status', true);
        $period = $this->dm->getperiod();

        $this->db->select('a.*, b.name, b.village, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal');
        $this->db->from('permissions AS a')->join('students AS b', 'b.id = a.student_id');
        $this->db->where(['a.period' => $period, 'a.type' => 'SHORT']);
        if ($name != '') {
            $this->db->like('b.name', $name);
        }
        if ($status != '') {
            $this->db->where('a.status', $status);
        }
        $result = $this->db->order_by('a.created_at DESC, a.status ASC')->get();
        return [
            $result->num_rows(),
            $result->result_object()
        ];
    }

	public function doBack()
	{
		$id = $this->input->post('id', true);
		$getPermission = $this->db->get_where('permissions', ['id' => $id])->row_object();
		if (!$getPermission) {
			return [
				'status' => 400,
				'message' => 'Data tidak ditemukan'
			];
		}

		if ($getPermission->status !== 'ACTIVE') {
			return [
				'status' => 400,
				'message' => 'Izin sudah diselesaikan sebelumnya'
			];
		}

		$expired = $getPermission->expired_at;
		$newStatus = setDiff($expired);
		$this->db->where('id', $id)->update('permissions', [
			'checked_at' => $newStatus[1], 'status' => $newStatus[0]
		]);

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan data'
			];
		}

		return [
			'status' => 200,
			'message' => $newStatus[0],
			'nis' => $getPermission->student_id
		];

	}

	public function loadConstitution()
	{
		$id = $this->input->post('id', true);
		$getConstitution = $this->db->get_where('constitutions', ['id' => $id])->row_object();
		if (!$getConstitution) {
			return [
				'status' => 400
			];
		}

		$type = $getConstitution->type;
		if ($type === 'OBLIGATION') {
			$chapter = 'Pasal I (Kewajiban-kewajiban)';
		} elseif($type === 'PROHIBITION') {
			$chapter = 'Pasal II (Larangan-larangan)';
		} else {
			$chapter = 'Pasal III (Sanksi-sanksi)';
		}

		$category = $getConstitution->category;
		//GET PENALTY OF CATEGORY
		$getPenalty = $this->db->get_where('constitutions', [
			'type' => 'PENALTY', 'category' => $category
		])->result_object();

		$punishmentStatus = 0;
		$punishment = [];
		if ($getPenalty) {
			$punishmentStatus = 200;
			foreach ($getPenalty as $penalty) {
				$punishment[] = [
					'item' => $penalty->name
				];
			}
		}else{
			$punishmentStatus = 400;
		}

		return [
			'status' => 200,
			'chapter' => $chapter,
			'item' => $getConstitution->name,
			'punishment_status' => $punishmentStatus,
			'punishment' => $punishment
		];

	}

	public function savePunishment()
	{
		$permission = $this->input->post('permission', true);
		$nis = $this->input->post('nis', true);
		$constitution = $this->input->post('constitution', true);

		$getConstitution = $this->db->get_where('constitutions', ['id' => $constitution])->row_object();
		if (!$getConstitution) {
			return [
				'status' => 400,
				'message' => 'Data pasal tidak ditemukan'
			];
		}

		$category = $getConstitution->category;
		//GET PENALTY OF CATEGORY
		$getPenalty = $this->db->get_where('constitutions', [
			'type' => 'PENALTY', 'category' => $category
		])->result_object();
		if (!$getPenalty) {
			return [
				'status' => 400,
				'message' => 'Data tindakan belum diatur'
			];
		}

		$count = ['LOW' => 7, 'MEDIUM' => 5, 'HIGH' => 4];
		$penalty = ['LOW' => 'M-10001', 'MEDIUM' => 'H-10001', 'HIGH' => 'T-10001'];
		$multiple = ['LOW' => 'MEDIUM', 'MEDIUM' => 'HIGH', 'HIGH' => 'TOP'];
		if ($category !== 'TOP') {
			$getPenaltyMultiple = $this->db->get_where('constitutions', [
				'category' => $multiple[$category], 'type' => 'PENALTY'
			]);

			if ($getPenaltyMultiple->num_rows() <= 0) {
				return [
					'status' => 400,
					'message' => 'Data tindakan untuk proses kelipatan pelanggaran belum diatur'
				];
			}
		}

		$this->db->insert('punishments', [
			'student_id' => $nis,
			'constitution_id' => $constitution,
			'period' => $this->dm->getperiod(),
			'created_at' => date('Y-m-d H:i:s')
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan data pelanggaran'
			];
		}
		$id = $this->db->insert_id();
		foreach ($getPenalty as $item) {
			$this->db->insert('punishment_detail', [
				'punishment_id' => $id,
				'constitution_id' => $item->id
			]);
		}

		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal menyimpan data item tindakan'
			];
		}

		$this->db->where('id', $permission)->update('permissions', [
			'status' => 'LATE-DONE'
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal memperbarui data izin'
			];
		}

		if ($category !== 'TOP') {
			//COUNT PELANGGARAN
			$period = $this->dm->getperiod();

			$this->db->select('a.id, b.category')->from('punishments AS a');
			$this->db->join('constitutions AS b', 'b.id = a.constitution_id');
			$punishment = $this->db->where([
				'a.period' => $period, 'a.student_id' => $nis, 'a.status' => 'NOT-ADDED', 'b.category' => $category
			])->get();

			if ($punishment->num_rows() === $count[$category]){
				//ADD TO PUNISHMENT
				$this->db->insert('punishments', [
					'student_id' => $nis,
					'constitution_id' => $penalty[$category],
					'period' => $period,
					'created_at' => date('Y-m-d H:i:s')
				]);
				$idPunishmentMultiple = $this->db->insert_id();
				if ($this->db->affected_rows() <= 0){
					return [
						'status' => 500,
						'message' => 'Gagal menambahkan data tindakan kelipatan'
					];
				}
				//ADD TO PUNISHMMENT DETAIL
				foreach ($getPenaltyMultiple->result_object() as $item) {
					$this->db->insert('punishment_detail', [
						'punishment_id' => $idPunishmentMultiple,
						'constitution_id' => $item->id
					]);
				}
				if ($this->db->affected_rows() <= 0){
					return [
						'status' => 500,
						'message' => 'Gagal menambahkan detail tindakan kelipatan'];
				}

				foreach ($punishment->result_object() as $item) {
					$this->db->where('id', $item->id)->update('punishments', ['status' => 'ADDED']);
				}
				if ($this->db->affected_rows() <= 0){
					return [
						'status' => 500,
						'message' => 'Gagal memperbarui status tindakan'];
				}

				return [
					'status' => 200,
					'message' => 'Data pelanggaran berikut kelipatan berhasil ditambahkan'
				];
			}
		}

		return [
			'status' => 200,
			'message' => 'Data pelanggaran berhasil ditambahkan'
		];
	}

	public function getIdPermission()
	{
		$nis = str_replace('_', '', $this->input->post('nis', true));
		$result = $this->db->order_by('created_at', 'DESC')->get_where('permissions', [
			'student_id' => $nis, 'status' => 'ACTIVE', 'type' => 'SHORT'
		])->row_object();

		if (!$result) {
			return [
				'status' => 400,
				'message' => 'Tidak ditemukan izin yang aktif'
			];
		}

		return [
			'status' => 200,
			'message' => 'SUCCESS',
			'id' => $result->id
		];
	}
}
