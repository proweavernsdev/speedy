<?php
class BridgeAPI {
    private $url;

    public function __construct(){
        $this->url = "http://speedyrepairanddelivery.com/api/api/";
    }

    public function bridgePost($link ,$body, $header){
        try{
            // Combine $link with $this->url
            $fullUrl = $this->url . $link;

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $fullUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response = curl_exec($ch);

            // Check for errors
            if(curl_errno($ch)) {
                // Handle error
                echo 'Error: ' . curl_error($ch);
            }

            // Close cURL session
            curl_close($ch);

            // Return response
            return json_decode($response);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
        
    }

    public function bridgeGet($link, $header){
        try{
            // Combine $link with $this->url
            $fullUrl = $this->url . $link;

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options for GET request
            curl_setopt($ch, CURLOPT_URL, $fullUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response = curl_exec($ch);

            // Check for errors
            if(curl_errno($ch)) {
                // Handle error
                echo 'Error: ' . curl_error($ch);
            }

            // Close cURL session
            curl_close($ch);

            // Return response
            return $response;
        }catch(Exception $e){
            return 'Error: '.$e->get_message();
        }
    }
}