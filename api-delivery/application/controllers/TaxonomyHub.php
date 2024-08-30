<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH, DELID");

require_once APPPATH . '/libraries/rest/Rest.php';

class TaxonomyHub extends Rest
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TaxonomyHubModel');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
    }

    // Get all data of taxonomies, user: admin
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
                        $res = $this->TaxonomyHubModel->all();
                        if ($res) {
                            $result = (object) $res;
                            $this->response([
                                'success' => true,
                                'message' => 'Data retrieve successful',
                                'results' => $result
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


    // Add new size category of taxonomies, user: admin
    protected function size_post()
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
                        'message' => 'Token is expired'
                    ], 401);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Unauthorized access This perhaps?'
                        ], 401);
                    } else {
                        $this->validator->validate('TaxonomyHubModel', $this->json(), ['SizeCategoryID', 'WeightCategoryID']);
                        $jsonData = $this->validator->sanitize($this->json());
                        $res = $this->TaxonomyHubModel->addSize($jsonData);
                        if ($res) {
                            $this->response([
                                'success' => true,
                                'message' => 'Data successfully added'
                            ], 200);
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'Error adding weight data'
                            ], 500);
                        }
                    }
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access this?'
            ], 401);
        }
    }

    // Add new weight category of taxonomies, user: admin
    protected function weight_post()
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
                        'message' => 'Token is expired'
                    ], 401);
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Unauthorized access'
                        ], 401);
                    } else {
                        $this->validator->validate('TaxonomyHubModel', $this->json(), ['WeightCategoryID', 'SizeCategoryID']);
                        $jsonData = $this->validator->sanitize($this->json());
                        $res = $this->TaxonomyHubModel->addWeight($jsonData);
                        if ($res) {
                            $this->response([
                                'success' => true,
                                'message' => 'Data successfully added'
                            ], 200);
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'Error adding weight data'
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

    // Update all data of taxonomies, user: admin
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
                    // place code where token is not admin
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        // place code where token is admin
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


    // Update size category of taxonomies, user: admin
    protected function size_put()
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
                    // place code where token is not admin
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
                        $this->validator->validate('TaxonomyHubModel', $this->json(), []);
                        $jsonData = $this->validator->sanitize($this->json());
                        if (isset($jsonData)) {
                            $res = $this->TaxonomyHubModel->updateSize($jsonData);
                            if ($res) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been updated'
                                ], 200);
                            } else {
                                $this->response([
                                    'success' => false,
                                    'error' => 'Internal Server Error',
                                    'message' => 'Failed to update data'
                                ], 500);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'field must not be empty'
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


    // Update weight category of taxonomies, user: admin
    protected function weight_put()
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
                    // place code where token is not admin
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
                        $this->validator->validate('TaxonomyHubModel', $this->json(), []);
                        $jsonData = $this->validator->sanitize($this->json());
                        if (isset($jsonData)) {
                            $res = $this->TaxonomyHubModel->updateWeight($jsonData);
                            if ($res) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been updated'
                                ], 200);
                            } else {
                                $this->response([
                                    'success' => false,
                                    'error' => 'Internal Server Error',
                                    'message' => 'Failed to update data'
                                ], 500);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'error' => 'Internal Server Error',
                                'message' => 'field must not be empty'
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


    // Delete size category of taxonomies, user: admin
    protected function size_delete()
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
                        // place code where token is admin
                        $ID = $_SERVER['HTTP_DELID'];
                        if (isset($ID) && !empty($ID)) {
                            $delete = $this->TaxonomyHubModel->deleteSize($ID, $decrypted['data']['UserID']);
                            if ($delete) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been deleted'
                                ], 200);
                            } else {
                                $this->response([
                                    'success' => false,
                                    'message' => 'Error Deleting'
                                ], 500);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Internal Server Error This?'
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


    // Delete weight category of taxonomies, user: admin

    protected function weight_delete()
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
                        // place code where token is admin
                        $ID = $_SERVER['HTTP_DELID'];
                        if (isset($ID) && !empty($ID)) {
                            $delete = $this->TaxonomyHubModel->deleteWeight($ID, $decrypted['data']['UserID']);
                            if ($delete) {
                                $this->response([
                                    'success' => true,
                                    'message' => 'Data has been deleted'
                                ], 200);
                            } else {
                                $this->response([
                                    'success' => false,
                                    'message' => 'Error Deleting'
                                ], 500);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Internal Server Error This?'
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
