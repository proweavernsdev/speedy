<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

require_once APPPATH . '/libraries/rest/Rest.php';

class Vehicles extends Rest
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('VehicleModel');
        $this->load->library('plugins/crypto');
    }

    // Get all vehicles, user: admin
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
                    // Place code here where token is not admin
                    $res = $this->VehicleModel->all();
                    if ($res) {
                        $this->response([
                            'success' => true,
                            'result' => $res
                        ], 200);
                    } else {
                        $this->response([
                            'success' => false,
                            'message' => 'Internal Server Error'
                        ], 500);
                    }
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        // place code where token is admin
                        $res = $this->VehicleModel->all();
                        if ($res) {
                            $this->response([
                                'success' => true,
                                'result' => $res
                            ], 200);
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Internal Server Error'
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

    // Add new vehicle, user: admin
    protected function _post()
    {
        $this->validator->validate('UsersModel', $this->json(), ['userID', 'users_email', 'users_password']);

        $data = $this->validator->sanitize($this->json());
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $validate = $this->crypto->decrypt($token);

            if ($validate['data']['UserAccess'] != 0) {
                $this->response([
                    'success' => false,
                    'message' => 'Unauthorize access me me?'
                ], 401);
            } else {
                $res = $this->VehicleModel->addVehicle($data);
                if ($res) {
                    $this->response([
                        'success' => true,
                        'message' => 'Vehicle type has been successfully added'
                    ], 200);
                } else {
                    $this->response([
                        'success' => false,
                        'message' => 'Internal Server Error'
                    ], 500);
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorize access me?'
            ], 401);
        }
    }


    // Update vehicle infos, user: admin
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
                    // Place code here where token is not admin
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
                        // place code where token is admin
                        $this->validator->validate('UsersModel', $this->json(), ['userID', 'users_email', 'users_password']);
                        $data = $this->validator->sanitize($this->json());
                        if (isset($data) && !empty($data)) {
                            $res = $this->VehicleModel->updtVehicle($data['TypeID'], $data, $decrypted['data']['UserID']);
                            if ($res) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been updated'
                                ], 200);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Internal Server Error'
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