<?php


class Usersbs{


    public function userRegister($userInfo){ 
        $url = "http://speedyrepairanddelivery.com/api/_v2_UsersApi/register";

        $payload = [
            'user_uid_hashed' => $userInfo['UID'],
            'user_email' => $userInfo['Email'],
            'user_firstname' => $userInfo['FirstName'],
            'user_middlename' => $userInfo['MiddleName'],
            'user_lastname' => $userInfo['LastName'],
            'user_password' => $userInfo['Password'],
            'user_address' => $userInfo['Address'],
            'user_city' => $userInfo['City'],
            'user_state' => $userInfo['State'],
            'user_zip' => $userInfo['ZIP'],
            'user_role ' => 'Customer',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    }
}