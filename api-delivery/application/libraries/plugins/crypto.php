<?php
class Crypto{
    private $key;

    public function __construct(){
        $this->key = "cC4QLxMWi3xpNvDX";  
    }

    public function decrypt($enc){
        $enc = preg_split('/\./',$enc);
        
        $jsondata = array(
            'ct' => $enc[0],
            'iv' => $enc[1],
            's' => $enc[2],
        );
        
        $salt = hex2bin($jsondata["s"]);
        $ct = base64_decode($jsondata["ct"]);
        $iv  = hex2bin($jsondata["iv"]);
        $concatedPassphrase = $this->key.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);

    }
    
    public function encrypt($value, $separator='.', $separator2='.'){
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx.$this->key.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = base64_encode($encrypted_data).$separator.bin2hex($iv).$separator2.bin2hex($salt);
        return json_encode($data);
    }

    public function getParamsToArray($data){
        $params = $this->decrypt($data);
        $params = explode('&',$params);
        $array = array();
        for($x=0;$x<count($params);$x++){
            $a = explode('=',$params[$x],2);
            if(isset($a[1])) $array[$a[0]] = $a[1];
        }

        return $array;
    }

    public function decryptToJSON($data){
        $data = $this->decrypt($data);
        $data = (array) json_decode($data);
        return $data;
    }
}
