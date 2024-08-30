<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/plugins/validator.php';
require_once APPPATH . 'libraries/middleware.php';
class Rest extends CI_Controller{
    public $validator;
    public $middleware;
    public $qb;
    function __construct() {
        parent::__construct();
        $this->load->model('QueryBuilder');
        $this->validator = new Validator();
        $this->middleware = new Middleware();

        $this->qb = $this->QueryBuilder;

        $access = [
            'AccessLevelData' => [
                'SuperAdmin'=>0,
                'Admin'=>1,
                'Company'=>2,
                'Customer'=>3,
                'Driver'=>4,
                'Employee'=>5
            ]
        ];

        $this->middleware->setAccessLevel($access);
    }

    // Create a remap to redirect HTTP response
    function _remap($ciMethod, $params = array()) {
        // Determine the HTTP method used in the request (e.g., GET, POST, etc.)
        $method = '_'.strtolower($_SERVER['REQUEST_METHOD']);

        // If the HTTP method is OPTIONS, return early without performing any further actions
        if($method == '_options') return;
    
        // Check if the requested CI method is not 'index'
        if($ciMethod != 'index'){
            // Construct the name of the method to call based on the requested CI method and HTTP method
            $subMethod = $ciMethod.$method;
            // If the constructed method does not exist in the current class, respond with a 404 error
            if(!method_exists($this, $subMethod)) $this->response(['success'=>false,'msg'=>'Resource not found'], 404);
            // If the method exists, call it
            else $this->$subMethod();
        }else {
            // If the requested CI method is 'index'
            // Check if the method corresponding to the HTTP method exists in the current class
            if(!method_exists($this, $method)) $this->response(['success'=>false,'msg'=>'Resource not found'], 404);
            // If the method exists, call it
            else $this->$method();
        }

    }

    function response($object,$statusCode = 500){
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($object);
    }

    function responseOutput($message, $additionalData = [], $statusCode = 500, $success = false, $optional = false){
        // echo http code response
        http_response_code($statusCode);
        // Set header
        header("Content-Type: application/json");
        // If optional is true
        if($optional == true){ 
            // build response
            $response =[
                'success' => $success,
                'message' => $message,
                'data'=> $additionalData
            ];
            echo json_encode($response);
            return;
        }
        // build response
        $response = [
            'success' => $success,
            'message' => $message
        ];
        // echo response
        echo json_encode($response);
    }

    function json($key = null){
        $json_data = file_get_contents('php://input');
        $raw = json_decode($json_data, true);
        return $this->requestBody($raw, $key);
    }


    function post($key = null){
        return $this->requestBody($this->input->post(), $key);
    }

    function get($key = null){
        return $this->requestBody($this->input->get(), $key);
    }

    private function requestBody($data,$key){
        if($key == null) return $data;
        elseif(gettype($key) == 'string') return isset($data[$key]) ? $data[$key] : NULL;
        elseif(is_array($key)) {
            $returnArr = [];
            foreach($key as $k) $returnArr[$k] = isset($data[$k]) ? $data[$k] : NULL;
            return $returnArr;
        }
    }

}