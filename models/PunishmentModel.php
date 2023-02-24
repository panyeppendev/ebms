<?php

use phpDocumentor\Reflection\Types\This;

defined('BASEPATH') or exit('No direct script access allowed');

class PunishmentModel extends CI_Model
{
	public function constitution()
	{
		return $this->db->get_where('constitutions', [
			'type' => 'PROHIBITION'
		])->result_object();
	}

    public function checkNis()
    {
        $nis = str_replace('_', '', $this->input->post('nis', true));
		$constitution = $this->input->post('constitution', true);
		$double = $this->input->post('double', true);
		$period = $this->dm->getperiod();

		if ($nis === '' || $constitution === '') {
			return [
				'status' => 500,
				'message' => 'Pastikan NIS dan pelanggaran sudah diisi/dipilih'
			];
		}


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
                'message' => 'Data Santri tidak ditemukan'
            ];
        }

		//GET CURRENT CONSTITUTION
		$getConstitution = $this->db->get_where('constitutions', ['id' => $constitution])->row_object();
		if (!$getConstitution) {
			return [
				'status' => 400,
				'message' => 'Data Pelanggaran tidak ditemukan',
				'nis' => $nis,
				'constitution' => null
			];
		}
		$category = $getConstitution->category;
		$penalty = $this->db->get_where('constitutions', [
			'category' => $category, 'type' => 'PENALTY'
		]);
		if ($penalty->num_rows() <= 0) {
			return [
				'status' => 400,
				'message' => 'Data tindakan tidak ditemukan',
				'nis' => $nis,
				'constitution' => null
			];
		}

		//SET DOUBLE
		$doubleValue = [
			'LOW' => 7,
			'MEDIUM' => 5,
			'HIGH' => 4
		];

		$text = [
			'LOW' => 'ringan',
			'MEDIUM' => 'sedang',
			'HIGH' => 'berat',
			'TOP' => 'sangat berat'
		];

		$categoryChange = [
			'LOW' => 'LOW',
			'MEDIUM' => 'LOW',
			'HIGH' => 'MEDIUM',
			'TOP' => 'HIGH'
		];

		//COUNT PELANGGARAN
		$this->db->select('COUNT(a.id) AS total, b.category')->from('punishments AS a');
		$this->db->join('constitutions AS b', 'b.id = a.constitution_id');
		$punishment = $this->db->where([
			'a.period' => $period, 'a.student_id' => $nis, 'a.status' => 'NOT-ADDED', 'b.category !=' => 'TOP'
		])->group_by('b.category')->get()->result_object();
		if ($punishment) {
			if ($double === 'NO' || ($double === 'YES' && $category === 'LOW')) {
				foreach ($punishment as $item) {
				$total = (int)$item->total;
				$category = $item->category;
				if ($category === 'LOW' && $total === 7) {
					return [
						'status' => 400,
						'message' => 'Santri ini sudah 7 kali melakukan <b>pelanggaran ringan</b> dan belum diproses pada tingkat pelanggaran yang lebih tinggi',
						'nis' => $nis,
						'constitution' => $constitution
					];
					break;
				}

				if ($category === 'MEDIUM' && $total === 5) {
					return [
						'status' => 400,
						'message' => 'Santri ini sudah 5 kali melakukan <b>pelanggaran sedang</b> dan belum diproses pada tingkat pelanggaran yang lebih tinggi',
						'nis' => $nis,
						'constitution' => $constitution
					];
					break;
				}

				if ($category === 'HIGH' && $total === 4) {
					return [
						'status' => 400,
						'message' => 'Santri ini sudah 4 kali melakukan <b>pelanggaran berat</b> dan belum diproses pada tingkat pelanggaran yang lebih tinggi',
						'nis' => $nis,
						'constitution' => $constitution
					];
					break;
				}
			}
			}
		}

		if ($double === 'YES' && $category !== 'LOW') {
			$this->db->select('a.id, b.category')->from('punishments AS a');
			$this->db->join('constitutions AS b', 'b.id = a.constitution_id');
			$punishmentByCategory = $this->db->where([
				'a.period' => $period, 'a.student_id' => $nis, 'a.status' => 'NOT-ADDED', 'b.category' => $categoryChange[$category]
			])->get();
			if ($punishmentByCategory->num_rows() !== $doubleValue[$categoryChange[$category]]) {
				return [
					'status' => 400,
					'message' => 'Tidak ada pelanggaran '.$text[$categoryChange[$category]].' sebanyak '. $doubleValue[$categoryChange[$category]] .' kali',
					'nis' => $nis,
					'constitution' => $constitution
				];
			}

			if ($punishmentByCategory->result_object()) {
				foreach ($punishmentByCategory->result_object() as $item) {
					$id = $item->id;
					$this->db->where('id', $id)->update('punishments', [
						'status' => 'ADDED'
					]);
				}
				if ($this->db->affected_rows() <= 0) {
					return [
						'status' => 500,
						'message' => 'Gagal saat memperbarui status ta\'zir'
					];
				}
			}
		}

		//ADD TO PUNISHMENT
		$this->db->insert('punishments', [
			'student_id' => $nis,
			'constitution_id' => $constitution,
			'period' => $period,
			'created_at' => date('Y-m-d H:i:s')
		]);
		$idPunishment = $this->db->insert_id();
		if ($this->db->affected_rows() <= 0){
			return [
				'status' => 500,
				'message' => 'Gagal menambahkan data tindakan'
			];
		}
		//ADD TO PUNISHMMENT DETAIL
		foreach ($penalty->result_object() as $item) {
			$this->db->insert('punishment_detail', [
				'punishment_id' => $idPunishment,
				'constitution_id' => $item->id
			]);
		}
		if ($this->db->affected_rows() <= 0){
			return [
				'status' => 500,
				'message' => 'Gagal menambahkan detail tindakan'
			];
		}

		//ADD TO SUSPENSION
		if ($category === 'TOP') {
			$this->db->insert('suspensions', [
				'student_id' => $nis,
				'constitution_id' => $constitution,
				'punishment_id' => $idPunishment,
				'period' => $period,
				'created_at' => date('Y-m-d H:i:s')
			]);
			if ($this->db->affected_rows() <= 0){
				return [
					'status' => 500,
					'message' => 'Gagal menambahkan data skorsing'
				];
			}

			return [
				'status' => 200,
				'message' => 'Data ta\'zir sekaligus <b>skorsing</b> berhasil ditambahkan. Lihat menu skorsing',
				'nis' => $nis,
				'constitution' => $constitution
			];
		}else{
			return [
				'status' => 200,
				'message' => 'Data ta\'zir berhasil ditambahkan',
				'nis' => $nis,
				'constitution' => $constitution
			];
		}
    }

    public function getData()
    {
        $nis = $this->input->post('nis', true);
        $constitution = $this->input->post('constitution', true);
		$period = $this->dm->getperiod();

        $data = $this->db->get_where('students', [
            'id' => $nis, 'status' => 'AKTIF'
        ])->row_object();

		$getConstitution = $this->db->get_where('constitutions', ['id' => $constitution])->row_object();
		if ($getConstitution) {
			$constitutionName = $getConstitution->name;
			$category = $getConstitution->category;
		}else{
			$constitutionName = 'Data pelanggaran tidak ditemukan';
			$category = null;
		}

		$penalty = $this->db->get_where('constitutions', [
			'category' => $category, 'type' => 'PENALTY'
		])->result_object();

		$this->db->select('COUNT(a.id) AS total, b.category')->from('punishments AS a');
		$this->db->join('constitutions AS b', 'b.id = a.constitution_id');
		$punishment = $this->db->where([
			'a.period' => $period, 'a.student_id' => $nis, 'a.status' => 'NOT-ADDED', 'b.category !=' => 'TOP'
		])->group_by('b.category')->order_by('b.category', 'ASC')->get()->result_object();

        return [
			'status' => $this->input->post('status', true),
			'message' => $this->input->post('message', true),
            'student' => $data,
			'constitution' => $getConstitution->name,
			'penalty' => $penalty,
			'punishment' => $punishment
        ];
    }

    public function save()
    {
        $nis = $this->input->post('nis', true);
        $nominal = $this->input->post('nominal', true);
        $step = $this->input->post('step', true);
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
			'payment' => 'CASH'
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

    public function loadRecap()
    {
		$period = $this->dm->getperiod();
        $filter = $this->input->post('filter', true);
        $now = date('Y-m-d');

		$this->db->select('COUNT(a.id) AS total, a.category')->from('constitutions AS a');
		$this->db->join('punishments AS b', 'b.constitution_id = a.id');
		$this->db->where('b.period', $period);
		if ($filter != '') {
			$this->db->where('DATE(b.created_at)', $filter);
		}
		$punishments = $this->db->group_by('a.category')->get()->result_object();
		$low = 0;
		$medium = 0;
		$high = 0;
		$top = 0;
		if ($punishments) {
			foreach ($punishments as $data) {
				if ($data->category === 'LOW'){
					$low += $data->total;
				} elseif ($data->category === 'MEDIUM') {
					$medium += $data->total;
				} elseif ($data->category === 'HIGH') {
					$high += $data->total;
				} else {
					$top += $data->total;
				}
			}
		}

		return [
			'low' => $low,
			'medium' => $medium,
			'high' => $high,
			'top' => $top
		];
    }

	public function loadDetail()
	{
		$period = $this->dm->getperiod();
		$filter = $this->input->post('filter', true);
		$category = $this->input->post('category', true);
		$name = $this->input->post('name', true);

		$this->db->select('a.created_at, b.name, b.domicile, c.name AS constitution');
		$this->db->from('punishments AS a');
		$this->db->join('students AS b', 'b.id = a.student_id');
		$this->db->join('constitutions AS c', 'c.id = a.constitution_id');
		if ($filter !== '') {
			$this->db->where('DATE(a.created_at)', $filter);
		}
		if ($name !== '') {
			$this->db->like('b.name', $name);
		}
		$this->db->where(['a.period' => $period, 'c.category' => $category]);
		return $this->db->order_by('a.created_at', 'ASC')->get()->result_object();
	}

	public function loadPunishment()
	{
		$period = $this->dm->getperiod();
		$name = $this->input->post('name', true);
		$category = $this->input->post('category', true);

		$this->db->select('a.*, b.name AS constitution, b.type, b.category, c.name, c.domicile, c.village, c.city, c.class, c.level, c.class_of_formal, c.level_of_formal')->from('punishments AS a');
		$this->db->join('constitutions AS b', 'b.id = a.constitution_id');
		$this->db->join('students AS c', 'c.id = a.student_id');
		$this->db->where('a.period', $period);
		if ($name !== '') {
			$this->db->like('c.name', $name);
		}
		if ($category !== '') {
			$this->db->where('b.category', $category);
		}
		$result = $this->db->order_by('a.created_at', 'DESC')->get();

		return [
			$result->num_rows(),
			$result->result_object()
		];
	}
}
