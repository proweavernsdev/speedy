<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/middlewares/firebase-php/vendor/autoload.php';

use Kreait\Firebase\Factory;

class Firebase_lib {
    protected $firebase;
    protected $auth;
    protected $database;
    protected $ref;
    protected $snap;

    public function __construct() {
        /**
         *  Read the contents of the service account JSON file
         */
        $serviceAccountJson = file_get_contents(APPPATH . 'libraries/middlewares/firebase-php/speedyrepair-6f70d-firebase-adminsdk-d331j-da60094083.json');

        /**
         *  Initialize Firebase
         */
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountJson)
            ->withDatabaseUri('https://speedyrepair-6f70d-default-rtdb.firebaseio.com/');

        /**
         *  Initialize Firebase Services
         */      
        $this->auth = $this->firebase->createAuth();
        $this->database = $this->firebase->createDatabase();
    }

    // Test method to verify connection to Firebase
    public function testConnection() {
        try {
            // Access a reference in the Firebase Realtime Database
            $reference = $this->database->getReference('/');
            $snapshot = $reference->getSnapshot();

            // Check if data is retrieved successfully
            if ($snapshot->exists()) {
                return ['success' => true, 'msg' => 'succesfully connected'];
            } else {
                return ['success' => false, 'msg' => 'connection was unsuccesfull'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }

/**
 *  Onwards below are functions for
 *      Firebase Authenticate.
 * 
 */

    /**
     * Firebase Authenticate Login/Sign-in with password
     */
    public function login($email, $password){
        try {
            $res = $this->auth->signInWithEmailAndPassword($email, $password)->data();
            return $res;
        } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            // Incorrect credentials
            throw new Exception('Error: '. $e->getMessage());
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            // User does not exist
            throw new Exception('Error: '. $e->getMessage());
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            // General Firebase auth exception
            if (strpos($e->getMessage(), 'INVALID_EMAIL') !== false) {
                throw new Exception('Error: '. $e->getMessage());
            }
            throw new Exception('Error: '. $e->getMessage());
        } catch (\Exception $e) {
            // General error
            throw new Exception('Error: '. $e->getMessage());
        }
    }
    /**
     * Firebase Authenticate Register/Sign-up
     */
    public function register($data){
        $userProperties = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
        $createdUser = $this->auth->createUser($userProperties);
        if($createdUser){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Firebase Authenticate Register/Sign-up with already verified email
     */
    public function registerVerified($data){
        try{
            $userProperties = [
                'email' => $data['email'],
                'emailVerified' => true,
                'password' => $data['password']
            ];
            $createdUser = $this->auth->createUser($userProperties);
            return $createdUser ? true : false;
        }catch(Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }
    /**
     *  Returns User Record
     */
    public function userRecords($uid){
        try {
            return $this->auth->getUser($uid);
        }catch(Exception $e){
            throw new Exception('Error getting user record: '. $e->getMessage());
        }
    }
    /**
     *  Checks User Record by email and returns User Records 
     */
    public function userRecordsbyEmail($email){
        try {
            return $this->auth->getUserByEmail($email);
        }catch(Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }
    /**
     *  Gets the link for email verification
     */
    public function getVerification($email){
        try{
            return $this->auth->getEmailVerificationlink($email);
        }catch(Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }
    /**
     *  Sends email verification Link to email
     */
    public function verifyEmail($email){
        try{
            return $this->auth->sendEmailVerificationLink($email);
        }catch(Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }
    /**
     * Gets password reset Link
     */
    public function getPassResetLink($email){
        try{
            return $this->auth->getPasswordResetLink($email);
        }catch (Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }

/**
 *  Onwards below are functions for
 *    Firebase Real Time Database
 * 
 */
    
    public function setReference($set){
        try{
            $this->ref = $this->database->getReference($set);
            return $this;
        }catch(\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }
    
    public function getSnapshot(){
        try{
            $this->snap = $this->ref->getSnapshot();
            return $this;
        }catch(\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }

    public function set($data, $chaining = false){
        try{
            $this->ref->set($data);
            return $chaining ? $this : true;
        }catch(\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }
    public function update($data, $chaining = false){
        try{
            $this->ref->update($data);
            return $chaining ? $this : true;
        }catch(\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }

    public function getValue(){
        try{
            return $this->snap->getValue(); 
        }catch(\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }
/*
    public function getTestSnapshot(){
        try{
            $snapshot = $this->ref->getSnapshot();
            $data = $snapshot->getValue();
            return $data;
        }catch (Exception $e){
            return ['success' => false, 'msg' => 'Error: ' . $e->getMessage()];
        }
    }
    public function setTestData(){
        try{
            $this->ref->set([
                'name'=>'test',
                'email'=>'test@example.com'
            ]);
            
            return true;
        }catch (\Kreait\Firebase\Exception\DatabaseException $e){
            return 'Error:' . $e->getMessage();
        }
    }
    */
    // Add other Firebase methods here...
}
