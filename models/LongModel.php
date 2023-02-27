<?php

use phpDocumentor\Reflection\Types\This;

defined('BASEPATH') or exit('No direct script access allowed');

class LongModel extends CI_Model
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
        return $this->db->get_where('reasons', ['type' => 'LONG'])->result_object();
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

        $cekStudent = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->num_rows();
        if ($cekStudent <= 0) {
            return [
                'status' => 500,
                'message' => 'Data Santri tidak ditemukan'
            ];
        }

		if (date('Y-m-d', strtotime($start)) > $masehi) {
			return [
				'status' => 400,
				'message' => 'Pencairan tahap ' . $step . ' belum dibuka',
				'nis' => $nis
			];
		}

        $checkPackage = $this->db->get_where('packages', [
            'student_id' => $nis, 'period' => $period,
            'step' => $step, 'status' => 'ACTIVE'
        ])->row_object();
        if (!$checkPackage) {
            return [
                'status' => 400,
                'message' => 'Santri ini tidak punya paket aktif pada tahap saat ini',
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
                'nis' => $nis
            ];
        }

        $checkNominal = $this->checkNominal();
        if ($checkNominal['status'] === 400) {
            return [
                'status' => 400,
                'message' => 'Tarif surat jarak jauh belum diatur. Hubungi Admin ~',
                'nis' => $nis
            ];
        }

		//GET DATA SKORSING
		$suspension = $this->db->get_where('suspensions', [
			'status' => 'ACTIVE', 'student_id' => $nis, 'period' => $period
		])->num_rows();
		if ($suspension > 0) {
			return [
				'status' => 401,
				'message' => 'Santri ini dalam masa skorsing',
				'nis' => $nis,
				'nominal' => $checkNominal['nominal'],
				'rp' => $checkNominal['rp']
			];
		}

        return [
            'status' => 200,
            'message' => 'Sukses',
            'nis' => $nis,
            'nominal' => $checkNominal['nominal'],
            'rp' => $checkNominal['rp']
        ];
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();

        return [
            'student' => $data
        ];
    }

    public function save()
    {
        $nis = $this->input->post('nis', true);
        $nominal = $this->input->post('nominal', true);
        $step = $this->input->post('step', true);
        $note = $this->input->post('note', true);
		$reason = $this->input->post('reason', true);
		$date = $this->input->post('date', true);
		$time = $this->input->post('time', true);
		$expired = date('Y-m-d H:i:s', strtotime($date . $time));

		if ($reason === '' || $date === '' || $time === '') {
			return [
				'status' => 400,
				'message' => 'Pastikan semua inputan sudah diisi/dipilih'
			];
		}

        $this->db->insert('payment_security', [
            'student_id' => $nis,
            'step' => $step,
            'amount' => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'period' => $this->dm->getperiod(),
            'type' => 'LONG',
            'status' => 'CASH'
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        $generator = $this->referenceGenerator();
        $this->db->insert('permissions', [
            'order' => $generator[0],
            'reference' => $generator[1],
            'step' => $step,
            'period' => $this->dm->getperiod(),
            'student_id' => $nis,
            'reason' => $reason,
            'created_at' => date('Y-m-d H:i:s'),
            'expired_at' => $expired,
            'type' => 'LONG',
            'status' => 'ACTIVE',
			'payment' => 'CASH',
			'note' => $note
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
            'nis' => $nis
        ];
    }

    public function referenceGenerator()
    {
        $period = $this->dm->getperiod();
		$month = getHijriExplode();
		$textMonth = [
			'01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV', '05' => 'V', '06' => 'VI',
			'07' => 'VII', '08' => 'VIII', '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII'
		];
        $check = $this->db->order_by('created_at', 'DESC')->get_where('permissions', [
            'period' => $period, 'type' => 'LONG'
        ])->row_object();
        if ($check) {
            $order = $check->order + 1;
			$reference = sprintf('%03d', $order).'/Kamtib/A.01/'.$textMonth[$month[1]].'/'.$month[0];
        }else {
            $order = 1;
			$reference = '001/Kamtib/A.01/'.$textMonth[$month[1]].'/'.$month[0];
        }

        return [
			$order,
			$reference
		];
    }

    public function checkNominal()
    {
        $data = $this->db->get_where('store_setting', [
            'name' => 'LONG'
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
        $this->db->where('type', 'LONG');
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
        $this->db->where('a.type', 'LONG');
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
        $this->db->where(['a.period' => $period, 'a.type' => 'LONG']);
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

		$count = ['LOW' => 2, 'MEDIUM' => 5, 'HIGH' => 4];
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

	public function closePrint()
	{
		$id = $this->input->post('id', true);
		$this->db->where('id', $id)->update('permissions', [
			'printed_at' => date('Y-m-d H:i:s')
		]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Gagal memperbarui data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Sukses'
		];
	}

	public function getIdPermission()
	{
		$nis = str_replace('_', '', $this->input->post('nis', true));
		$result = $this->db->order_by('created_at', 'DESC')->get_where('permissions', [
			'student_id' => $nis, 'status' => 'ACTIVE', 'type' => 'LONG'
		])->row_object();

		if (!$result) {
			return [
				'status' => 400,
				'message' => 'Tidak ditemukan izin jarak jauh yang aktif'
			];
		}

		return [
			'status' => 200,
			'message' => 'SUCCESS',
			'id' => $result->id
		];
	}

	public function loadPermissionById($id)
	{
		$this->db->select('a.*, DATE(a.created_at) as created_at, DATE(a.expired_at) as expired_at, a.expired_at as date_expired, a.note, b.name, b.date_of_birth, b.place_of_birth, b.address, b.village, b.district, b.city, b.domicile, b.class, b.level, b.class_of_formal, b.level_of_formal, b.father');
		$this->db->from('permissions AS a')->join('students AS b', 'b.id = a.student_id');
		return $this->db->where('a.id', $id)->get()->row_object();
	}
}
