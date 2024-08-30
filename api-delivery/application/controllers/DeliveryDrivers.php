<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

require_once APPPATH . '/libraries/rest/Rest.php';

class DeliveryDrivers extends Rest
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DeliveryDriversModel');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
        $this->load->library('plugins/filehelper');
    }

    //get all data of driver, user: driver
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
                    $res = $this->DeliveryDriversModel->retrieveData($decrypted['UserID']);
                    $this->response([
                        'success' => true,
                        'result' => $res
                    ], 200);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        $res = $this->DeliveryDriversModel->all();
                        $this->response([
                            'success' => true,
                            'result' => $res
                        ], 200);
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorize access'
            ], 401);
        }
    }


    //get all documents of driver, user: driver
    protected function docs_get()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                if (!array_key_exists('expires_at', $decrypted)) {
                    // place code where token is not admin
                    $this->response($this->filehelper->viewfiles("Users/Company/{$decrypted['UserID']}"), 200);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        // place code where token is admin
                        $this->validator->validate('UsersModel', $this->json(), ['userID', 'users_email', 'users_password']);
                        $jsonData = $this->validator->sanitize($this->json());
                        $res = $this->filehelper->viewfiles("Users/Company/{$jsonData['UserID']}");
                        if (!$res) {
                            $this->response([
                                'success' => false,
                                'message' => 'Internal Server Error :D'
                            ], 401);
                        } else {
                            $this->response($res, 200);
                        }
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorize access'
            ], 401);
        }
    }


    //create new driver, user: driver
    protected function _post()
    {
        $this->validator->validate('UsersModel', $this->post(), ['userID', 'users_email', 'users_password', 'users_access_level_id', 'users_company']);
        $postData = $this->validator->sanitize($this->post());

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $validated = $this->crypto->decrypt($token);
            $pattern = '/^[a-zA-Z0-9_]+$/';

            if (!isset($validated))
                throw new Exception("Token is Invalid");
            else if ($validated['UserID'] == null || empty($validated['UserID']))
                throw new Exception("Token is empty");

            if (!empty($postData['FirstName']) && !empty($postData['LastName']) && !empty($postData['Address']) && !empty($postData['TownCity']) && !empty($postData['State']) && !empty($postData['Zip']) && !empty($postData['LicenseNumber'])) {
                // if (preg_match($pattern, $postData['FirstName']) && preg_match($pattern, $postData['LastName']) && preg_match($pattern, $postData['Address']) && preg_match($pattern, $postData['TownCity']) && preg_match($pattern, $postData['State']) && preg_match($pattern, $postData['Zip']) && preg_match($pattern, $postData['LicenseNumber'])) {
                $res = $this->DeliveryDriversModel->DeliveryDriverRegister($validated['UserID'], $postData['FirstName'], $postData['LastName'], $postData['Address'], $postData['TownCity'], $postData['State'], $postData['Zip'], $postData['LicenseNumber']);
                $upld = $this->filehelper->uploadMultiple("Users/Driver/{$validated['UserID']}/docs", 'files', true);
                if ($res && $upld) {
                    $this->response([
                        'success' => true,
                        'message' => 'Driver has been created'
                    ], 200);
                } else {
                    $this->response([
                        success => false,
                        message => 'Internal Server Error'
                    ], 500);
                }
                // } else {
                //     $this->response([
                //         'success' => false,
                //         'message' => 'Input fields must not include white spaces',
                //         'dataReceived' => $postData
                //     ], 401);
                // }
            } else {
                $this->response([
                    'success' => false,
                    'message' => 'Input fields must not be empty',
                    'dataReceived' => $postData
                ], 401);
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorize access'
            ], 401);
        }
    }


    //update the picture of driver, user: driver
    protected function upload_post()
    {
        $this->validator->validate('UsersModel', $this->post(), ['userID', 'users_email', 'users_password', 'users_access_level_id', 'users_company']);
        $postData = $this->validator->sanitize($this->post());

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                $currentTimestamp = time();
                $upload = $this->filehelper->upload("Users/Company/{$decrypted['UserID']}/pfp/{$currentTimestamp}", 'file', true);
                if ($upload) {
                    $uploadObject = (object) $upload;
                    $path = $uploadObject->result['path'];
                    $res = $this->DeliveryDriversModel->setPfp($decrypted['UserID'], $path);
                    if ($res) {
                        $this->response([
                            'success' => true,
                            'message' => 'Profile picture has been updated'
                        ], 200);
                    } else {
                        $this->response([
                            success => false,
                            message => 'Internal Server Error'
                        ], 500);
                    }
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
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // update the status of driver, user: admin
    protected function _put()
    {
        $this->validator->validate('UsersModel', $this->json(), [], true);

        $data = $this->validator->sanitize($this->json(), []);
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
                        'message' => 'Unauthorize access'
                    ], 401);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        $res = $this->DeliveryDriversModel->setStatus($data['DriverID'], $data['Status']);
                        if ($res) {
                            $this->response([
                                'success' => true,
                                'message' => 'Status has been updated'
                            ], 200);
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Status has failed to update'
                            ], 400);
                        }
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorize access'
            ], 401);
        }
    }
}