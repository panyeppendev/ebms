<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('MenuModel', 'mm');
        $this->load->model('RoleModel', 'rm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Menage Menu',
			'roles' => $this->rm->roles()
        ];
        $this->load->view('menu/menu', $data);
    }

    public function getdata()
    {
        $data = [
            'datas' => $this->mm->getdata()
        ];
        $this->load->view('menu/ajax-data', $data);
    }

    public function save()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => 400,
                'errors' => [
                    'name' => form_error('name'),
                    'icon' => form_error('icon'),
                    'url' => form_error('url')
                ]
            ];
        } else {
            $result = $this->mm->save();
            if ($result <= 0) {
                $response = [
                    'status' => 500
                ];
            } else {
                $response = [
                    'status' => 200
                ];
            }
        }

        echo json_encode($response);
    }

    public function updatestatus()
    {
        $result = $this->mm->updatestatus();
        if ($result > 0) {
            $response = [
                'status' => 200
            ];
        } else {
            $response = [
                'status' => 400
            ];
        }

        echo json_encode($response);
    }

    public function saveSet()
    {
        $result = $this->mm->saveSet();

        echo json_encode($result);
    }

    public function deleteRoleMenu()
    {
        $result = $this->mm->deleteRoleMenu();


        echo json_encode($result);
    }
}
