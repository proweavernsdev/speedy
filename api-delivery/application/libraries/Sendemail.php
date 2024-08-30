<?php

// require_once(APPPATH.'libraries/PHPMailer/PHPMailerAutoload.php');
///public_html/wangelmobile/application
class Sendemail
{
    public function __construct()
    {
        $this->apikey = '51f62c91-c1c3-406a-8f84-416c79c73550';
        $this->from_email = 'email@proweaverforms.com ';
    }

    function sendEmail($to_email, $subject, $body)
    {
        // echo json_encode(array($to_email, $subject, $body));
        $sent = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.postmarkapp.com/email");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Postmark-Server-Token: ' . $this->apikey
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);

        $vars = array(
            "From" => $this->from_email,
            "To" => $to_email,
            "Subject" => $subject,
            "HtmlBody" => $body,
            "MessageStream" => "outbound"
        );
        $vars = json_encode($vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $result = curl_exec($ch);
        $response = json_decode($result, true);
        if (curl_errno($ch)) {
            // echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if (empty($response['results']['id'])) {
            $sent = true;
        }

        return $sent;
    }
}
