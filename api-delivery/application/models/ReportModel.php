<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'] . 'reports'; // Adjust table name as per your database setup
    }

    public function addReport($data)
    {
        $this->db->insert($this->table, array(
            'report_id' => $data['report_id'],
            'rep_tracking_num' => $data['rep_tracking_num'],
            'rep_issue_type' => $data['rep_issue_type'],
            'rep_description' => $data['rep_description'],
            'rep_report_file' => $data['rep_report_file']

        ));
        return $this->db->affected_rows() > 0;
    }
}
