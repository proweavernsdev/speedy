<?php
class FirebaseAPI{
    private $ci;
    public function __construct(){
        $this->ci =& get_instance();
    }

    public function firebaseLogin($data){
        $firebase_api_key = 'AIzaSyAda3Bw4Dr0pXmDBNHwcR4EDWlByL81M4I';
        // $firebase_url = 'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=' . $firebase_api_key;
        $firebase_url = 'https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=' . $firebase_api_key;

        $ch = curl_init($firebase_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'email' => $data['email'],
            'password' => $data['password'],
            'returnSecureToken' => true
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        
        return $result;
    }
}