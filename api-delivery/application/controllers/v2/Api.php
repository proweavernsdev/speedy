<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: PWAUTH, Content-Type");

require_once APPPATH . '/libraries/rest/Rest.php';

class Api extends Rest {
    private $token;
    public function __construct(){
        parent::__construct();
        $this->load->library('plugins/filehelper');
        $this->load->library('middlewares/FirebaseAPI');
        $this->load->library('middlewares/firebase-php/Firebase_lib');
        $this->load->library('plugins/crypto');
        $this->load->library('middleware');

        $access = [
            'SomeAccess' => [
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
    public function testRTDB_get(){
        $data = ['test'=>'my test','name'=>'lyron'];
        $id = [1,2,3,4];
        
        foreach ($id as $i){
            $this->firebase_lib->setReference('SpeedyDelivery/PriorityListing/'.$i);
            $this->firebase_lib->set($data, true);
        }
        $prio = $this->firebase_lib->setReference('SpeedyDelivery/Task/22')->getSnapshot()->getValue();
        $origin = $prio['OriginCoordinates'];
        $this->responseOutput('test',$prio,200,true,true);
    }
    public function test_get(){
        // $data = $this->json();
        // $this->response($this->firebase_lib->getVerification($data['email']), 200);
        $data = [
            'UsersID'=>'DUMMY123',
            'Email'=> 'TEST@DUMMY.COM',
            'UserAccess'=> 3
        ];
        $params = [
            'accessLevel'=> true,
            'levelParams'=> 'SomeAccess',
            'basedOn'=> $data['UserAccess'],
        ];
        try{
            $token = $this->middleware->createToken($data, $params);
            $this->responseOutput('test',json_decode($token),200,true,true);
        } catch (Exception $e){
            $this->responseOutput($e->getMessage());
        }
        
        
    }
    public function checkToken_get(){
        try{
            $this->responseOutput('test',$this->middleware->checkToken()->validate(),200,true,true);
        } catch (Exception $e){
            $this->responseOutput($e->getMessage());
        }
    }

    public function test_post(){
        $data = $this->json();
        $login = $this->firebase_lib->login($data['email'], $data['password']);
        if($login){
            $this->response($this->firebase_lib->userRecords($login['localId']),200);
        }else{
            $this->response([
                'success' => false,
                'data' => $login
            ],500);
        }
    }
    public function testRegisterVerified_post(){
        $data = $this->json();
        $register = $this->firebase_lib->registerVerified($data);
        return $register ? $this->responseOutput('Success',$this->firebase_lib->userRecordsbyEmail($data['email']),200,true,true) : $this->responseOutput('Failed');
    }
    public function crypto_get(){
        $data = ['success' => true, 'message' => 'test'];
        $this->response(json_decode($this->crypto->encrypt($data)), 200);
    }
    public function crypto_post(){
        $data = $this->json();
        $this->response($this->crypto->decrypt($data['token']), 200);
    }
    public function upload_get(){
        $this->response($this->filehelper->viewfiles('Users/Company'),200);
    }

    public function upload_post(){
        $this->response($this->filehelper->uploadMultiple('test', 'files', true), 200);
    }


    public function getInfo_post(){
        $data = $this->json();
    }
}