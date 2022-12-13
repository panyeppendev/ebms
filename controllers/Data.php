<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
    }

    public function provinces()
    {
        if (isset($_GET['term'])) {
            $result = $this->dm->provinces($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label'         => $row->name,
                        'description'   => $row->id,
                    );
                echo json_encode($arr_result);
            }
        }
    }

    public function cities($id)
    {
        if (isset($_GET['term'])) {
            $result = $this->dm->cities($id, $_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label'         => $row->name,
                        'description'   => $row->id,
                    );
                echo json_encode($arr_result);
            }
        }
    }

    public function districts($id)
    {
        if (isset($_GET['term'])) {
            $result = $this->dm->districts($id, $_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label'         => $row->name,
                        'description'   => $row->id,
                    );
                echo json_encode($arr_result);
            }
        }
    }

    public function villages($id)
    {
        if (isset($_GET['term'])) {
            $result = $this->dm->villages($id, $_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = array(
                        'label'         => $row->name,
                        'description'   => $row->postal_code
                    );
                echo json_encode($arr_result);
            }
        }
    }
}
