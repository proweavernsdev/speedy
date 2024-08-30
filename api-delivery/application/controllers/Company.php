<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

require_once APPPATH . '/libraries/rest/Rest.php';

class Company extends Rest
{
    private $sm;
    private $mw;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CompanyModel');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
        $this->load->library('plugins/filehelper');
        $this->load->library('middlewares/firebase-php/Firebase_lib');
        // Shorten Libraries and Models
        $this->fb = $this->firebase_lib;
        $this->mw = $this->middleware;
    }

    // =============== GET =============== // // =============== GET =============== // // =============== GET =============== //
// =============== GET =============== // // =============== GET =============== // // =============== GET =============== //
// =============== GET =============== // // =============== GET =============== // // =============== GET =============== //
    protected function test_get()
    {
        try {
            $this->mw->checkToken();
            $params = $this->mw->getParams();
            $data = $this->mw->getData();
            if ($params->AccessLevel == 'Admin') {
                $res = $this->CompanyModel->all();
                $this->responseOutput('Data Retrieved', $res, 200, true, true);
            } elseif ($params->AccessLevel == 'Company') {
                $res = $this->CompanyModel->retrieveData($data->UserID);
                $this->responseOutput('Data Retrieved', $res, 200, true, true);
            } else
                throw new Exception('Unauthorized access');
        } catch (Exception $e) {
            $this->responseOutput($e->getMessage());
        }
    }

    protected function test_doc_get()
    {
        try {
            $this->mw->checkToken();
            $params = $this->mw->getParams();
            $data = $this->mw->getData();
            if ($params->AccessLevel == 'Admin') {
                $jsonData = $this->json();
                $res = $this->filehelper->viewfiles("Users/Company/{$jsonData['UserID']}");
                if (!$res) {
                    $this->response([
                        'success' => false,
                        'message' => 'Internal Server Error'
                    ], 401);
                } else {
                    $this->response($res, 200);
                }
            } elseif ($params->AccessLevel == 'Company') {
                $res = $this->filehelper->viewfiles("Users/Company/{$data->UserID}");
                $this->responseOutput('Files retrieved', $res, 200, true, true);
            } else
                throw new Exception('Unauthorized access');
        } catch (Exception $e) {
            $this->responseOutput($e->getMessage());
        }
    }

    protected function test_employee_get()
    {

    }

    // COMPANY USER BROWSE, USER:COMPANY
    protected function _get()
    {
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid token'
                ], 401);
            } else {
                if (!array_key_exists('expires_at', $decrypted)) {
                    $res = $this->CompanyModel->retrieveData($decrypted['UserID']);
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
                        $res = $this->CompanyModel->all();
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

    // COMPANY DOCS BROWSE, USER:COMPANY, ADMIN
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
                                'message' => 'Internal Server Error'
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

    // COMPANY EMPLOYEE BROWSE, USER:COMPANY
    protected function employee_get()
    {
        $uri_segments = $this->uri->segments;
        if (in_array('pfp', $uri_segments)) {
            // If the URI contains '/pfp', call the pfp_post() method
            $this->pfp_get();
            return; // Exit to prevent further execution
        }

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!isset($decrypted['UserAccess']))
                throw new Exception("Unauthorize Access");
            elseif ($decrypted['UserAccess'] != 2)
                throw new Exception("Unauthorize Access");
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                $query = $this->CompanyModel->select('comp_userOwner', $decrypted['UserID'], 'srdr_company')->row();
                if ($query) {
                    $res = $this->CompanyModel->employeeRetrieve($query->companyID);
                    if (!isset($res['success'])) {
                        $this->response([
                            'success' => true,
                            'results' => $res
                        ], 200);
                    } else {
                        $this->response([
                            'success' => false,
                            'message' => 'Internal Server Error',
                            'result' => $res
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

    protected function pfp_get()
    {
        $uri_segments = $this->uri->segments;
        if (!in_array('employee', $uri_segments)) {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
        $this->response([
            'success' => true,
            'message' => 'I am success'
        ], 200);
    }

    // =============== POST =============== // // =============== POST =============== // // =============== POST =============== //
// =============== POST =============== // // =============== POST =============== // // =============== POST =============== //
// =============== POST =============== // // =============== POST =============== // // =============== POST =============== //

    // COMPANY USER CREATE, USER:COMPANY
    protected function _post()
    {
        $this->validator->validate('UsersModel', $this->post(), ['userID', 'users_email', 'users_password', 'users_access_level_id']);
        $postData = $this->validator->sanitize($this->post());

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            $pattern = '/^[a-zA-Z0-9_]+$/';

            if (!isset($decrypted))
                throw new Exception("Token is Invalid");
            else if ($decrypted['UserID'] == null || empty($decrypted['UserID']))
                throw new Exception("Token is empty");

            if (!empty($postData['CompName']) && !empty($postData['CompAddr']) && !empty($postData['TownCity']) && !empty($postData['CompState']) && !empty($postData['CompZip'])) {
                // if(preg_match($pattern, $postData['CompName']) && preg_match($pattern, $postData['CompAddr']) && preg_match($pattern, $postData['TownCity']) && preg_match($pattern, $postData['CompState']) && preg_match($pattern, $postData['CompZip'])){
                $res = $this->CompanyModel->companyRegister($postData['CompName'], $decrypted['UserID'], $postData['CompAddr'], $postData['TownCity'], $postData['CompState'], $postData['CompZip']);
                $upld = $this->filehelper->uploadMultiple("Users/Company/{$decrypted['UserID']}/docs", 'files', true);
                if ($res && $upld) {
                    $this->response([
                        'success' => true,
                        'message' => 'Company has been created'
                    ], 200);
                } else {
                    $this->response([
                        'success' => false,
                        'message' => 'Internal Server Error'
                    ], 500);
                }
                // }else{
                //     $this->response([
                //         'success' => false,
                //         'message' => 'Input fields must not include white spaces',
                //         'dataReceived' => $postData
                //     ],401);
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

    // COMPANY BUSINESS PHOTO CREATE, USER:COMPANY
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
                $upload = $this->filehelper->upload("Users/Company/{$decrypted['UserID']}/pfp/{$currentTimestamp}", 'file', true);
                if (isset($upload['result'])) {
                    $uploadObject = (object) $upload;
                    $path = $uploadObject->result['path'];
                    $res = $this->CompanyModel->setPfp($decrypted['UserID'], $path, 'comp_userOwner', 'srdr_company', 'comp_businessPhoto');
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
    // COMPANY EMPLOYEE CREATE, USER:COMPANY
    protected function employee_post()
    {
        $uri_segments = $this->uri->segments;
        if (in_array('pfp', $uri_segments)) {
            // If the URI contains '/pfp', call the pfp_post() method
            $this->pfp_post();
            return; // Exit to prevent further execution
        }

        $this->validator->validate('CompanyModel', $this->json(), ['companyID', 'comp_userOwner', 'comp_companyName', 'comp_address', 'comp_towity', 'comp_state', 'comp_zip']);
        $data = $this->validator->sanitize($this->json());

        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!isset($decrypted['UserAccess']))
                throw new Exception("Unauthorize Access");
            elseif ($decrypted['UserAccess'] != 2)
                throw new Exception("Unauthorize Access");
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                if (!empty($data['email']) && !empty($data['password']) && !empty($data['FirstName']) && !empty($data['LastName'])) {
                    $query = $this->CompanyModel->select('comp_userOwner', $decrypted['UserID'], 'srdr_company')->row();
                    if ($query) {
                        $reg = $this->fb->registerVerified($data);
                        if ($reg) {
                            $fbAcc = $this->fb->userRecordsbyEmail($data['email']);
                            $newData = [
                                'uid' => $fbAcc->uid,
                                'email' => $fbAcc->email,
                                'password' => $data['password'],
                                'FirstName' => $data['FirstName'],
                                'LastName' => $data['LastName']
                            ];
                            $res = $this->CompanyModel->registerEmloyee($query->companyID, $newData);
                            if ($res) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Account has been added'
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
                                'message' => 'Internal Server Error'
                            ], 500);
                        }
                    } else {
                        $this->response([
                            'success' => false,
                            'message' => 'Internal Server Error'
                        ], 500);
                    }
                } else {
                    $this->response([
                        'success' => false,
                        'message' => 'Input fields must not be empty'
                    ], 400);
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // COMPANY PROFILE PHOTO CREATE, USER:COMPANY
    protected function pfp_post()
    {
        $uri_segments = $this->uri->segments;
        if (!in_array('employee', $uri_segments)) {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted || $decrypted['UserAccess'] != 5) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token',
                    'data' => $decrypted
                ], 401);
            } else {
                $this->validator->validate('CompanyModel', $this->post(), ['companyID', 'comp_userOwner', 'comp_companyName', 'comp_address', 'comp_towity', 'comp_state', 'comp_zip']);
                $data = $this->validator->sanitize($this->post());
                $query = $this->CompanyModel->select('subs_userOwner', $decrypted['UserID'], 'srdr_subUsers')->row();
                if ($query) {
                    $compQuery = $this->CompanyModel->select('companyID', $query->subs_underCompany, 'srdr_company')->row();
                    if ($compQuery) {
                        $timeStamp = time();
                        $upld = $this->filehelper->upload("Users/Company/{$compQuery->comp_userOwner}/employee/{$decrypted['UserID']}/pfp/{$timeStamp}", 'file', true);
                        if ($upld) {
                            $uploadObject = (object) $upld;
                            $path = $uploadObject->result['path'];
                            $res = $this->CompanyModel->setPfp($decrypted['UserID'], $path, 'subs_userOwner', 'srdr_subUsers', 'subs_userPhoto');
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
                                'message' => 'Internal Server Error'
                            ], 500);
                        }
                    } else {
                        $this->response([
                            'success' => false,
                            'message' => 'Internal Server Error'
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

    // =============== PUT =============== // // =============== PUT =============== // // =============== PUT =============== //
// =============== PUT =============== // // =============== PUT =============== // // =============== PUT =============== //
// =============== PUT =============== // // =============== PUT =============== // // =============== PUT =============== //
    // COMPANY USER UPDATE, USER:COMPANY, ADMIN
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
                    'message' => 'Invalid Token'
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
                        $res = $this->CompanyModel->setStatus($data['CompanyID'], $data['Status']);
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

    // COMPANY DETAILS UPDATE, USER:COMPANY 
    protected function update_put()
    {
        $this->validator->validate('CompanyModel', $this->json(), [], true);

        $data = $this->validator->sanitize($this->json(), []);
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                $query = $this->CompanyModel->select('comp_userOWner', $decrypted['UserID'], 'srdr_company')->row();
                if ($query) {
                    $res = $this->CompanyModel->updateCompDetails($query->companyID, $data);
                    if ($res->success == true) {
                        $this->response([
                            'success' => true,
                            'message' => 'Update success'
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

    // COMPANY EMPLOYEE DETAILS UPDATE, USER:COMPANY
    protected function employee_put()
    {
        $this->validator->validate('UsersModel', $this->json(), [], true);

        $data = $this->validator->sanitize($this->json(), []);
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->crypto->decrypt($token);
            if (!isset($decrypted['UserAccess']))
                throw new Exception("Unauthorize Access");
            elseif ($decrypted['UserAccess'] != 2)
                throw new Exception("Unauthorize Access");
            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], 401);
            } else {
                if (!array_key_exists('expires_at', $decrypted)) {
                    $res = $this->CompanyModel->setEmployeeStatus($data['sub_userID'], $data['Status']);
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
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        $res = $this->CompanyModel->setEmployeeStatus($data['sub_userID'], $data['Status']);
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