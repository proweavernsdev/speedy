<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: LOGINAUTH, Content-Type, PWAUTH, Authorization');

require_once APPPATH . '/libraries/rest/Rest.php';

class Users_v2 extends Rest
{
    private $fb;
    private $sm;
    private $cp;
    private $md;
    private $mw;
    private $br;
    public function __construct()
    {
        // Inherit parent's construct
        parent::__construct();
        // load Model
        $this->load->model('UsersModel_v2');
        $this->load->model('CustomerModel');
        // load libraries
        $this->load->library('sendemail');
        $this->load->library('middleware');
        $this->load->library('plugins/crypto');
        $this->load->library('middlewares/BridgeAPI');
        $this->load->library('middlewares/firebase-php/Firebase_lib');
        // Shorten Libraries and Models
        $this->cp = $this->crypto;
        $this->sm = $this->sendemail;
        $this->br = $this->bridgeapi;
        $this->mw = $this->middleware;
        $this->fb = $this->firebase_lib;
        $this->md = $this->UsersModel_v2;

    }

    // -- Sign-in/Login -- //
    /**
     * When account is unverified, send email verification.
     * When account is verified, account details from Firebase will be saved onto MySQL database
     * HTTP Request: GET
     */
    protected function _get()
    {
        if (isset($_SERVER['HTTP_LOGINAUTH'])) {
            $authorizationHeader = $_SERVER['HTTP_LOGINAUTH'];
            if (strpos($authorizationHeader, 'Basic') === 0) {
                $base64Credentials = substr($authorizationHeader, 5);
                $credentials = base64_decode($base64Credentials);
                $credentialsArray = explode(':', $credentials);
                $email = $credentialsArray[0];
                $password = $credentialsArray[1];
                if (!empty($email) && !empty($password)) {
                    if (strpos($email, ' ') !== false || strpos($password, ' ') !== false) {
                        $this->response([
                            'success' => false,
                            'msg' => 'input field must not contain whitespaces',
                        ], 402);
                    } else {
                        // Place code here...
                        $login = $this->fb->login($email, $password);
                        // Check if UID is present
                        if (isset($login['localId'])) {
                            // Get user record to check for verified email
                            $userRecord = $this->fb->userRecords($login['localId']);
                            // If verify is successful 
                            if ($userRecord) {
                                // Check if email is verified
                                if ($userRecord->emailVerified == true) {
                                    // once verified, check database
                                    $toArray = ['uid' => $login['localId'], 'email' => $email];
                                    // check if data in database is present
                                    $check = $this->md->checkDB($toArray);
                                    if ($check) {
                                        if ($check->users_access_level_id === "0" || $check->users_access_level_id === "1") {
                                            $adminToken = $this->middleware->tokenize([
                                                'UserID' => $check->userID,
                                                'Email' => $check->users_email,
                                                'UserAccess' => $check->users_access_level_id
                                            ]);
                                            $this->response([
                                                'success' => true,
                                                'result' => $adminToken
                                            ], 200);
                                        } else {
                                            $userToken = $this->cp->encrypt([
                                                'UserID' => $check->userID,
                                                'Email' => $check->users_email,
                                                'UserAccess' => $check->users_access_level_id
                                            ]);
                                            $this->response([
                                                'success' => true,
                                                'result' => $userToken
                                            ], 200);
                                        }
                                    } else {
                                        // If Speedy Delivery database query is false
                                        // Create HTTP Request to the Speedy Repair API to check for possible accounts
                                        $body = json_encode(['keypass' => 'laNzFSqL3D', 'email' => $email, 'uid' => $login['localId']]);
                                        $header = ['Content-Type' => 'application/json'];
                                        // Submit HTTP Request to the Speedy Repair API 
                                        $bridge = $this->br->bridgePost('verify', $body, $header);
                                        if ($bridge) {
                                            // $this->responseOutput('this',$bridge,200,true,true);
                                            // If bridge outputs true and has data
                                            // Check if the role is Customer
                                            if ($bridge->user_role == 'Customer') {
                                                // first, create account to db
                                                $loginArray = [
                                                    'uid' => $login['localId'],
                                                    'email' => $bridge->user_email,
                                                    'accessID' => 3
                                                ];
                                                $register = $this->md->registerDB($loginArray);
                                                if ($register) {
                                                    $toArray = [
                                                        'FirstName' => $bridge->user_firstname,
                                                        'LastName' => $bridge->user_lastname,
                                                        'Address' => $bridge->user_address,
                                                        'TownCity' => $bridge->user_city,
                                                        'State' => $bridge->user_state,
                                                        'Zip' => $bridge->user_zip
                                                    ];
                                                    $query = $this->CustomerModel->customerRegister($toArray, $login['localId']);
                                                    if ($query) {
                                                        $this->responseOutput('Account succesfully migrated', [], 200, true);
                                                    }
                                                }
                                            } else {
                                                $this->responseOutput('User not a Customer type', [], 401);
                                            }
                                        } else {
                                            // If bridge outputs false therefor no data
                                            $this->response([
                                                'success' => false,
                                                'message' => 'Account does not exist'
                                            ], 500);
                                        }
                                    }
                                } else {
                                    if (isset($userRecord->email)) {
                                        $verifyLink = $this->fb->getVerification($userRecord->email);
                                        $to_email = $userRecord->email;
                                        $subject = "Subject: Your Email Verification Link (Expires in 5 Minutes)";
                                        $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>
                                                <p>You have initiated the registration process for your account. To complete this process, please use the following verification link:</p>
                                                <p>Link: <span style='font-weight: bold;'> <a href= '" . $verifyLink . "'>link</a> </span></p>
                                                <p>This Link is valid for 5 minutes only and is essential for completing your registration securely. It helps us confirm your identity and maintain the security of your account.</p>
                                                <p>Please use the Link promptly to ensure a successful registration. Remember, it will expire in 5 minutes.</p>
                                                <p>Keep your Link confidential and avoid sharing it with anyone.</p>
                                                <p>If you didn't initiate this registration, please ignore this email.</p>
                                                </div>";
                                        $mail = $this->sm->sendEmail($to_email, $subject, $body);
                                        if ($mail) {
                                            $this->responseOutput('Email is not verified, verification link sent to email', ['email' => $userRecord->email], 200, true, true);
                                        } else {
                                            $this->responseOutput('Internal Server Error', ['email' => $mail], 500, false, true);
                                        }
                                    } else {
                                        $this->responseOutput('Internal Server Error', []);
                                    }
                                }
                            } else {
                                $this->responseOutput('Internal Server Error', []);
                            }
                        } else {
                            $this->response([
                                'success' => false,
                                'message' => 'Account does not exist daw'
                            ], 500);
                        }
                    }
                } else {
                    // If fields are empty
                    $this->response([
                        'success' => false,
                        'message' => 'Fields must not be empty 1'
                    ], 400);
                }
            } else {
                // If the received authentication unsupported
                $this->response([
                    'success' => false,
                    'msg' => 'Unsupported authentication scheme',
                ], 403);
            }
        }
    }

    protected function test_info_get()
    {
        log_message('debug', 'test_info_get function called'); 
    }

    // -- Decrypt Token -- //
    /**
     * Decrypts token and outputs the data inside the token
     * HTTP Request: GET
     */

    protected function info_get()
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
                $this->responseOutput('User data request successful', $decrypted, 200, true, true);
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    protected function proto_info_get()
    {
        try {
            $data = $this->mw->checkToken()->getData();
            $this->responseOutput('Process Successful', $data, 200, true, true);
        } catch (Exception $e) {
            $this->responseOutput($e->getMessage());
        }
    }

    // -- Sign-up/Register -- //
    /**
     * Register new accounts to Firebase
     * Send email verification after successful registration
     * HTTP Request: POST
     */
    protected function _post()
    {
        $data = $this->json();

        // Check if fields are empty
        if (empty($data['email']) || empty($data['password'])) {
            return $this->responseOutput('Fields must not be empty');
        }

        // Check for whitespace in fields
        if (strpos($data['email'], ' ') !== false || strpos($data['password'], ' ') !== false) {
            return $this->responseOutput('Fields must not have whitespaces');
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->responseOutput('Invalid email format');
        }

        // Attempt to register user
        $reg = $this->fb->register($data);
        if (!$reg) {
            return $this->responseOutput('Something went wrong, Account creation failed');
        }

        // Fetch user records by email
        $fbAcc = $this->fb->userRecordsbyEmail($data['email']);
        if (!$fbAcc) {
            return $this->responseOutput('Account does not exist');
        }

        // Output user data
        $this->responseOutput('Get data', [$fbAcc->uid, $fbAcc->email], 200, true, true);

        // Register user in the database
        $register = $this->md->registerDB([
            'uid' => $fbAcc->uid,
            'email' => $fbAcc->email,
            'password' => $data['password'],
            'accessID' => null
        ]);

        if (!$register) {
            return $this->responseOutput('Something went wrong, Account creation failed');
        }

        // Get verification link
        $verifyLink = $this->fb->getVerification($fbAcc->email);
        if (!$verifyLink) {
            return $this->responseOutput('Internal Server Error', ['email' => $verifyLink], 500, false, true);
        }

        // Send verification email
        $to_email = $fbAcc->email;
        $subject = "Subject: Your Email Verification Link (Expires in 5 Minutes)";
        $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>
                <p>You have initiated the registration process for your account. To complete this process, please use the following verification link:</p>
                <p>Link: <span style='font-weight: bold;'><a href='" . htmlspecialchars($verifyLink) . "'>link</a></span></p>
                <p>This Link is valid for 5 minutes only and is essential for completing your registration securely. It helps us confirm your identity and maintain the security of your account.</p>
                <p>Please use the Link promptly to ensure a successful registration. Remember, it will expire in 5 minutes.</p>
                <p>Keep your Link confidential and avoid sharing it with anyone.</p>
                <p>If you didn't initiate this registration, please ignore this email.</p>
              </div>";

        $mail = $this->sm->sendEmail($to_email, $subject, $body);
        if ($mail) {
            return $this->responseOutput('Email verification link has been sent', [], 200, true);
        } else {
            return $this->responseOutput('Something went wrong, Email not sent');
        }
    }


    // -- Reset Password -- //
    /**
     *  Request Password Reset
     *  Uppon request, email will be sent
     *  This is for Admin and Users
     *  HTTP Request: PUT
     */
    protected function _put()
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
                // Check if token has no expiry, this is to indicate as a normal User
                if (!array_key_exists('expires_at', $decrypted)) {
                    if (empty($decrypted['Email'])) {
                        $this->responseOutput('Fields must not be empty', ['Email' => $decrypted->data], 401, false, true);
                    } else {
                        if (strpos($decrypted['Email'], ' ') == true) {
                            $this->responseOutput('Fields must not have whitespaces');
                        } else {
                            if (!filter_var($decrypted['Email'], FILTER_VALIDATE_EMAIL))
                                $this->responseOutput('Invalid email format');
                            $verifyLink = $this->fb->getPassResetLink($decrypted['Email']);
                            $to_email = $decrypted['Email'];
                            $subject = "Subject: Your Password Reset Link (Expires in 5 Minutes)";
                            $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>
                                    <p>You have initiated the password reset process for your account. To complete this process, please use the following verification link:</p>
                                    <p>Link: <span style='font-weight: bold;'> <a href= '" . $verifyLink . "'>link</a> </span></p>
                                    <p>This Link is valid for 5 minutes only and is essential for completing your password reset securely. It helps us confirm your identity and maintain the security of your account.</p>
                                    <p>Please use the Link promptly to ensure a successful password reset. Remember, it will expire in 5 minutes.</p>
                                    <p>Keep your Link confidential and avoid sharing it with anyone.</p>
                                    <p>If you didn't initiate this password reset, please ignore this email.</p>
                                    </div>";
                            $mail = $this->sm->sendEmail($to_email, $subject, $body);
                            if ($mail) {
                                $this->responseOutput('Password reset link has been sent', [], 200, true);
                            } else {
                                $this->responseOutput('Something went wrong, Email not sent');
                            }
                        }
                    }
                } else {
                    // Check if token has expiry, this is to indicate user is Admin
                    // Check if the expiry has passed
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        if (empty($decrypted['data']['Email'])) {
                            $this->responseOutput('Fields must not be empty', ['Email' => $decrypted['data']['Email']], 401, false, true);
                        } else {
                            if (strpos($decrypted['data']['Email'], ' ') == true) {
                                $this->responseOutput('Fields must not have whitespaces');
                            } else {
                                if (!filter_var($decrypted['data']['Email'], FILTER_VALIDATE_EMAIL))
                                    $this->responseOutput('Invalid email format');
                                $verifyLink = $this->fb->getPassResetLink($decrypted['data']['Email']);
                                $to_email = $decrypted['data']['Email'];
                                $subject = "Subject: Your Password Reset Link (Expires in 5 Minutes)";
                                $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>
                                        <p>You have initiated the password reset process for your account. To complete this process, please use the following verification link:</p>
                                        <p>Link: <span style='font-weight: bold;'> <a href= '" . $verifyLink . "'>link</a> </span></p>
                                        <p>This Link is valid for 5 minutes only and is essential for completing your password reset securely. It helps us confirm your identity and maintain the security of your account.</p>
                                        <p>Please use the Link promptly to ensure a successful password reset. Remember, it will expire in 5 minutes.</p>
                                        <p>Keep your Link confidential and avoid sharing it with anyone.</p>
                                        <p>If you didn't initiate this password reset, please ignore this email.</p>
                                        </div>";
                                $mail = $this->sm->sendEmail($to_email, $subject, $body);
                                if ($mail) {
                                    $this->responseOutput('Password reset link has been sent', [], 200, true);
                                } else {
                                    $this->responseOutput('Something went wrong, Email not sent');
                                }
                            }
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