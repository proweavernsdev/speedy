<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Accept: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: LOGINAUTH, Content-Type, PWAUTH');

require_once APPPATH . '/libraries/rest/Rest.php';

class BookService extends Rest
{
    private $bsm;
    private $fb;
    private $cp;
    public function __construct()
    {
        parent::__construct();
        // Load libraries and models
        $this->load->library('middlewares/firebase-php/Firebase_lib');
        $this->load->library('plugins/crypto');
        $this->load->model('BookServiceModel');
        // Shorten the libraries and models
        $this->bsm = $this->BookServiceModel;
        $this->fb = $this->firebase_lib;
        $this->cp = $this->crypto;
    }

    //view booking services, user: company, employee, customer
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

                    /**
                     *  Check if the User is Company, Sub-user or Customer
                     */
                    switch ($decrypted['UserAccess']) {
                        case 2:
                            // Company
                            $company = $this->qb->setColumnPrefix('comp_')->select('company', true, ['userOwner' => $decrypted['UserID']]);
                            $bh = $this->qb->setColumnPrefix('bh_')->select('bookingHistory', true, ['company_order' => $company[0]->companyID]);
                            $bh ? $this->responseOutput('Retrieval success', $bh, 200, true, true) : $this->responseOutput("Retrieval unsuccessful");
                            break;
                        case 3:
                            // Sub-user
                            $bh = $this->qb->setColumnPrefix('bh_')->select('bookingHistory', true, ['user_initiator' => $decrypted['UserID']]);
                            $bh ? $this->responseOutput('Retrieval success', $bh, 200, true, true) : $this->responseOutput('Retrieval unsuccessful');
                            break;
                        case 5:
                            // Customer
                            $bh = $this->qb->setColumnPrefix('bh_')->select('bookingHistory', true, ['user_initiator' => $decrypted['UserID']]);
                            $bh ? $this->responseOutput('Retrieval success', $bh, 200, true, true) : $this->responseOutput('Retrieval unsuccessful');
                            break;
                    }
                } else {
                    if (time() >= $decrypted['expires_at']) {
                        $this->response([
                            'success' => false,
                            'message' => 'Token is expired'
                        ], 401);
                    } else {
                        // place code where token is admin
                        $bh = $this->qb->setColumnPrefix('bh_')->select('bookingHistory');
                        $bh ? $this->responseOutput('Retrieval success', $bh, 200, true, true) : $this->responseOutput('Retrieval unsuccessful');
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

    //create booking services, user: customer, company, employee
    protected function _post()
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
                /**
                 *  Retrieve the data from the HTTP request body
                 */
                $data = $this->json();

                /**
                 *  Check if the retrieved data is empty
                 */
                if (!empty($data['SizeCategoryID']) && !empty($data['WeightCategoryID']) && !empty($data['OriginCoordinates']) && !empty($data['DestinationCoordinates']) && !empty($data['Distance']) && !empty($data['Notes']) && !empty($data['VehicleTypeID']) && !empty($data['Priority'])) {
                    /**
                     *  Load all the data from the database using QueryBuilder based on the inputed data taken from the HTTP request body
                     */
                    $appFees = $this->qb->select('appFees');
                    $vehicleTypes = $this->qb->select('deliveryVehicleType', true, ['vehicleTypeID' => $data['VehicleTypeID']]);
                    $sizeCategory = $this->qb->select('sizeCategory', true, ['SizeCategoryID' => $data['SizeCategoryID']]);
                    $weightCategory = $this->qb->select('weightCategory', true, ['WeightCategoryID' => $data['WeightCategoryID']]);

                    /**
                     *  Check if the Distance is greater than the selected vehicle 
                     */
                    if ($data['Distance'] >= $vehicleTypes[0]->vehicle_baseDistance) {
                        $excess = $data['Distance'] - $vehicleTypes[0]->vehicle_baseDistance;
                        $calculated = round($excess * $vehicleTypes[0]->vehicle_distanceFee, 2);
                        $totalDistanceFee = number_format($calculated + $vehicleTypes[0]->vehicle_baseFee, 2, '.', '');
                    } else {
                        $totalDistanceFee = $vehicleTypes[0]->vehicle_baseFee;
                        $excess = 0;
                    }

                    /**
                     *  Get the Coordinates for both Origin and Destination.
                     *  Since the coordinates are bundled together, we need a way to separate them, the format for the bundled coordinates are long:lat, in order to extract
                     *  both Longitude and Latitude, we use the function explode() and then our separator would be a colon symbol (':')
                     */
                    $originCoordinates = explode(':', $data['OriginCoordinates']);
                    $destinationCoordinates = explode(':', $data['DestinationCoordinates']);

                    /**
                     *  Calculate the raw total by adding all the applicable fees
                     */
                    $calculatedTotal = $totalDistanceFee + $appFees[0]->af_appFees + $appFees[0]->af_vat + $sizeCategory[0]->sc_setFee + $weightCategory[0]->wc_setFee;

                    /**
                     *  Since the calculated data would be too long as it is not rounded to the nearest 100ths by default, it is rounded through the php function
                     *  round(calculation, 2), another problem is that when it is outputed as JSON format, the round function won't take effect, therefor we change the format
                     *  through number_format() function
                     */
                    $total = number_format(round($calculatedTotal, 2), 2, '.', '');

                    /**
                     *  This variable is where we store all the data to be inserted into the database
                     */
                    $toArray = [
                        'bh_sizeCategory' => $sizeCategory[0]->SizeCategoryID,
                        'bh_weightCategory' => $weightCategory[0]->WeightCategoryID,
                        'bh_originLong' => $originCoordinates[0],
                        'bh_originLat' => $originCoordinates[1],
                        'bh_destinationLong' => $destinationCoordinates[0],
                        'bh_destinationLat' => $destinationCoordinates[1],
                        'bh_distance' => $data['Distance'],
                        'bh_distanceFee' => $totalDistanceFee,
                        'bh_notes' => $data['Notes'],
                        'bh_user_initiator' => $decrypted['UserID'],
                        'bh_vehicleType' => $vehicleTypes[0]->vehicleTypeID,
                        'bh_appFees' => $appFees[0]->af_appFees,
                        'bh_vat' => $appFees[0]->af_vat,
                        'bh_total' => $total
                    ];

                    /**
                     *  Check if the User is Company or Employee
                     *  2 => Company
                     *  5 => Employee
                     */
                    switch ($decrypted['UserAccess']) {
                        case 2:
                            /**
                             *  Retrieve company data
                             */
                            $company = $this->qb->setColumnPrefix('comp_')->select('company', true, ['userOwner' => $decrypted['UserID']]);
                            /**
                             *  From the company data, retrieve company ID
                             */
                            $toArray['bh_company_order'] = $company[0]->companyID;
                            break;
                        case 5:
                            /**
                             *  Retrieve the employee data
                             */
                            $employee = $this->qb->setColumnPrefix('subs_')->select('subUsers', true, ['userOwner' => $decrypted['UserID']]);
                            /**
                             *  From the employee data, retrieve the company the employee is under and add to the variable to be inserted into the database
                             */
                            $toArray['bh_company_order'] = $employee[0]->subs_underCompany;
                            break;
                    }

                    /**
                     *  Check if the variable to insert is set and is not empty
                     */
                    if (isset($toArray) && !empty($toArray)) {
                        /**
                         *  Insert to database through Query Builder
                         */
                        $res = $this->qb->insert('bookingHistory', $toArray, true);
                        /**
                         *  Check if insert query is successful 
                         */
                        if ($res) {
                            /**
                             *  Set Reference for Firebase to access
                             */
                            $prio = $this->fb->setReference('SpeedyDelivery/PriorityList/' . $data['Priority']);
                            /**
                             *  Get the value
                             */
                            $prio = $prio->getSnapshot()->getValue();
                            /**
                             *  Set data to be inserted into Firebase
                             */
                            $toFirebase = [
                                $res => [
                                    'Points' => $prio,
                                    'User' => $decrypted['UserID'],
                                    'OriginCoordinates' => [
                                        'long' => $originCoordinates[0],
                                        'lat' => $originCoordinates[1]
                                    ],
                                    'DestinationCoordinates' => [
                                        'long' => $destinationCoordinates[0],
                                        'lat' => $destinationCoordinates[1]
                                    ],
                                    'Status' => 'In-Pool'
                                ]
                            ];
                            /**
                             *  Insert data into Firebase
                             */
                            $submit = $this->fb->setReference('SpeedyDelivery/Task')->update($toFirebase);
                            /**
                             *  Return output success
                             */
                            return $submit ? $this->responseOutput('Succesfully inserted data', [], 200, true) : $this->responseOutput('Data insertion unsuccessful');
                        }
                    }
                } else {
                    /**
                     *  Return an error once any of the fields are empty
                     */
                    $this->responseOutput('Empty Fields');
                }
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // update the status of delivery, user: driver
    protected function _put()
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
                /**
                 *  Check user access if it is not 4
                 */
                if ($decrypted['UserAccess'] != 4) {
                    $this->responseOutput('Unauthorized access');
                } else {
                    try {
                        /**
                         *  Retrieve the HTTP request body as JSON format
                         */
                        $data = $this->json();
                        /**
                         *  Check if driver is active in a task
                         */
                        $taskChecker = $this->qb->setColumnPrefix('bh_')->select('bookingHistory', true, ['driver' => $decrypted['UserID']]);
                        if (!empty($taskChecker[0])) {
                            if ($taskChecker[0]->bh_booking_status = 'On-Going')
                                throw new Exception('Action not allowed');
                        }
                        /**
                         *  Query to database if data provided is correct
                         */
                        $bhData = $this->qb->select('bookingHistory', true, ['bookingID' => $data['BookingID']]);
                        /**
                         *  Check if query is successful
                         */
                        if (!$bhData)
                            throw new Exception('Database Error');
                        /**
                         *  Update database table
                         *  format: update($tbl, $data, $condition=false, parameters=[])
                         */
                        $query = $this->qb->update('bookingHistory', ['bh_driver' => $decrypted['UserID'], 'bh_booking_status' => 'On-going'], true, ['bookingID' => $bhData[0]->bookingID]);
                        /**
                         *  Check if query is successful
                         */
                        if (!$query)
                            throw new Exception('Database query error');
                        /**
                         *  Retrieve the firebase realtime database data
                         */
                        $fbData = $this->fb->setReference('SpeedyDelivery/Task/' . $bhData[0]->bookingID)->getSnapshot()->getValue();
                        /**
                         *  Set the variables for ease of access to the retrieved data from firebase realtime database
                         */
                        $destination = $fbData['DestinationCoordinates'];
                        $origin = $fbData['OriginCoordinates'];
                        $user = $fbData['User'];
                        /**
                         *  Set data to be inserted into Firebase
                         */
                        $toFirebase = [
                            $bhData[0]->bookingID => [
                                'DestinationCoordinates' => $destination,
                                'OriginCoordinates' => $origin,
                                'User' => $user,
                                'Driver' => $decrypted['UserID'],
                                'TransactionStatus' => $bhData[0]->bh_transaction_status,
                                'Status' => 'On-going'
                            ]
                        ];
                        /**
                         *  Insert data into Firebase
                         */
                        $submit = $this->fb->setReference('SpeedyDelivery/Task')->update($toFirebase);
                        $submit ? $this->responseOutput('Task successfuly taken', [], 200, true) : $this->responseOutput('Task unsuccessfuly taken');
                    } catch (Exception $e) {
                        $this->responseOutput($e->getMessage());
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
    protected function complete_put()
    {

    }

    protected function uploadImg_post()
    {
        $this->load->helper('url'); 
        $imageUploadSuccess = false;
        if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';  // Accepting image files only
            $config['max_size'] = 10240;  // 10MB max file size
            $config['file_name'] = $_FILES['file']['name'];
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
        
            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $baseUrl = base_url();
                $imagePath = $baseUrl . 'uploads/' . $uploadData['file_name'];
                $imageUploadSuccess = true;
            } else {
                $error = $this->upload->display_errors();
                echo json_encode(array('status' => 'error', 'message' => $error));
                return;
            }
        }
        if ($imageUploadSuccess) {
            $response = array('status' => 'success', 'message' => 'Image uploaded successfully.', 'file_path' => $imagePath);
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to upload image.');
        }
        echo json_encode($response);
    }
}