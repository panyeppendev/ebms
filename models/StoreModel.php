<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StoreModel extends CI_Model
{
    public function save()
    {
        $this->db->empty_table('store_setting');

        $name = str_replace('.', '', $this->input->post('name', true));

        if ($name) {
            $check = 0;
            foreach ($name as $key => $value) {
                if ($value <= 0) {
                    $check++;
                }
            }
            if ($check <= 0) {
                foreach ($name as $key => $value) {
                    $this->db->insert('store_setting', [
                        'name' => $key,
                        'price' => $value,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }else {
                return [
                    'status' => 400,
                    'message' => 'Tidak boleh kosong'
                ];
            }
        }
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Terjadi kesalahan. Coba refresh halaman'
            ];
        }

        return [
            'status' => 200,
            'message' => ''
        ];
    }

    public function loadData()
    {
        return $this->db->get('store_setting')->result_object();
    }

    public function saveReason()
    {
        $reason = $this->input->post('reason', true);
        $type = $this->input->post('type', true);
        if ($reason == '' || $type == '') {
            return [
                'status' => 400,
                'message' => 'Pastikan semua sudah diisi'
            ];
        }

        $this->db->insert('reasons', [
            'name' => ucfirst($reason),
            'type' => $type
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function deleteReason()
    {
        $id = $this->input->post('id', true);

        $check = $this->db->get_where('reasons', ['id' => $id])->num_rows();
        if ($check <= 0) {
            return [
                'status' => 400,
                'message' => 'Data tidak valid'
            ];
        }

        $this->db->where('id', $id)->delete('reasons');
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Gagal menyimpan data'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function loadReason()
    {
        $type = $this->input->post('type', true);

        if ($type == '') {
            return $this->db->get('reasons')->result_object();
        }else {
            return $this->db->get_where('reasons', ['type' => $type])->result_object();
        }

    }

	public function saveTerm()
	{
		$minute = $this->input->post('minute', true);
		$this->db->empty_table('term_setting');
		$this->db->insert('term_setting', ['term' => (int)$minute]);
		if ($this->db->affected_rows() <= 0) {
			return [
				'status' => 200,
				'message' => 'Server gagal menyimpan data'
			];
		}

		return [
			'status' => 200,
			'message' => 'Success'
		];
	}

	public function loadTerm()
	{
		$result = $this->db->get('term_setting')->row_object();
		if (!$result) {
			return 'Belum diatur';
		}

		return $result->term.' menit';
	}
}
