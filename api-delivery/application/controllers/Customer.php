<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

require_once APPPATH . '/libraries/rest/Rest.php';

class Customer extends Rest
{
    private $cm;
    private $mw;
    private $cp;
    private $fh;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
        $this->load->library('plugins/filehelper');
        // shorten libraries and models
        $this->cm = $this->CustomerModel;
        $this->mw = $this->middleware;
        $this->fh = $this->filehelper;
        $this->cp = $this->crypto;
    }

    // get all data of customer, user: admin
    protected function _get()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->cp->decrypt($token);
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
                        $this->responseOutput('All data', $this->cm->all(), 200, true, true);
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

    // get customer details, user: customer
    protected function user_get()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->cp->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                $this->responseOutput('Customer Data', $this->cm->retrieveData($decrypted['UserID']), 200, true, true);
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // create new customer, user: customer
    protected function _post()
    {
        $postData = $this->json();

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->cp->decrypt($token);

            if (!isset($decrypted))
                throw new Exception("Token is Invalid");
            else if ($decrypted['UserID'] == null || empty($decrypted['UserID']))
                throw new Exception("Token is empty");
            if (!empty($postData['FirstName']) && !empty($postData['LastName']) && !empty($postData['Address']) && !empty($postData['TownCity']) && !empty($postData['State']) && !empty($postData['Zip'])) {
                $res = $this->cm->customerRegister($postData, $decrypted['UserID']);
                if ($res) {
                    $this->response([
                        'success' => true,
                        'message' => 'Customer has been created'
                    ], 200);
                } else {
                    $this->response([
                        'success' => false,
                        'message' => 'Internal Server Errorasd'
                    ], 500);
                }
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

    // update customer details, user: customer
    protected function upload_post()
    {
        $this->validator->validate('UsersModel', $this->post(), ['userID', 'users_email', 'users_password']);
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
                $upload = $this->filehelper->upload("Users/Customer/{$decrypted['UserID']}/pfp/{$currentTimestamp}", 'file', true);
                if (isset($upload['result'])) {
                    $uploadObject = (object) $upload;
                    $path = $uploadObject->result['path'];
                    $res = $this->cm->setPfp($decrypted['UserID'], $path, 'cust_userOwner', 'srdr_customers', 'cust_userPhoto');
                    if ($res) {
                        $this->response([
                            'success' => true,
                            'message' => 'Profile picture has been updated'
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
                        'Error' => 'Internal Server Error',
                        'message' => 'Upload has failed',
                        'results' => $upload
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

    // upload customer image, user: customer
    protected function upload_put()
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
                $currentTimestamp = time();
                $upload = $this->filehelper->upload("Users/Customer/{$decrypted['UserID']}/pfp/{$currentTimestamp}", 'file', true);
                if (isset($upload['result'])) {
                    $uploadObject = (object) $upload;
                    $path = $uploadObject->result['path'];
                    $res = $this->cm->setPfp($decrypted['UserID'], $path, 'customerID', 'srdr_customers', 'cust_userPhoto');
                    if ($res) {
                        $this->response([
                            'success' => true,
                            'message' => 'Profile picture has been updated'
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
                        'Error' => 'Internal Server Error',
                        'message' => 'Upload has failed',
                        'results' => $upload
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
}
