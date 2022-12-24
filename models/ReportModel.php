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
}
