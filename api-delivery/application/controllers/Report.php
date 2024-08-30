<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Set headers for CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

// Check for OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once APPPATH . '/libraries/rest/Rest.php';

class Report extends Rest
{
    private $reportModel;
    private $cryptoCode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReportModel');
        $this->load->library('plugins/crypto');
        $this->reportModel = $this->ReportModel;
        $this->cryptoCode = $this->crypto;
    }

    public function _post()
    {
        $data = $this->json();

        if (!isset($_SERVER['HTTP_PWAUTH'])) {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
            return;
        }

        $token = $_SERVER['HTTP_PWAUTH'];
        $decrypted = $this->cryptoCode->decrypt($token);

        if (!$decrypted) {
            $this->response([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
            return;
        }

        if (empty($data['report_id']) || empty($data['rep_issue_type']) || empty($data['rep_description']) || empty($data['rep_tracking_num'])) {
            $this->response([
                'success' => false,
                'message' => 'Missing required fields'
            ], 400);
            return;
        } elseif (!is_numeric($data['report_id'])) {
            $this->response([
                'success' => false,
                'message' => 'Invalid report ID'
            ], 400);
            return;
        }

        $reportData = [
            'report_id' => $data['report_id'],
            'rep_tracking_num' => $data['rep_tracking_num'],
            'rep_issue_type' => $data['rep_issue_type'],
            'rep_description' => $data['rep_description'],
            'rep_report_file' => $data['rep_report_file']
        ];

        $reportAdded = $this->reportModel->addReport($reportData);

        if ($reportAdded) {
            $this->response([
                'success' => true,
                'message' => 'Report added successfully'
            ], 200);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Failed to add report'
            ], 500);
        }
    }
}
