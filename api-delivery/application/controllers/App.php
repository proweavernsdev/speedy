<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

require_once APPPATH . '/libraries/rest/Rest.php';

class App extends Rest
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppModel');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
    }

    // get details of all fees and vat, user: admin
    protected function _get()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Unauthorize access'
                ], 401);
            } else {
                if (!array_key_exists('expires_at', $decrypted)) {
                    $this->response([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 401);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        $res = $this->AppModel->get_all();
                        if ($res) {
                            $this->response([
                                'success' => true,
                                'message' => 'Data retrieve successful',
                                'results' => $res
                            ], 200);
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'Unable to retrieve data'
                            ], 500);
                        }
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // update fee and vat, user: admin
    protected function _put()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Unauthorize access'
                ], 401);
            } else {
                if (!array_key_exists('expires_at', $decrypted)) {
                    $this->response([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 401);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        $this->validator->validate('TaxonomyHubModel', $this->json(), ['SizeCategoryID', 'WeightCategoryID']);
                        $jsonData = $this->validator->sanitize($this->json());
                        if (!empty($jsonData)) {
                            $res = $this->AppModel->updateFees($jsonData, $decrypted['data']['UserID']);
                            if ($res) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been updated'
                                ], 200);
                            } else {
                                $this->response([
                                    'success' => false,
                                    'message' => 'Internal Server Error'
                                ], 500);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'Fields must not be empty'
                            ], 500);
                        }
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }
}