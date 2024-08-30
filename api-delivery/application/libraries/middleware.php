<?php
require_once APPPATH . '/libraries/plugins/crypto.php';
class Middleware{
    private $table;
    private $ci;
    private $cp; 
    private $token;
    private $headerValue;
    private $accessLevel;

    public function __construct(){
        $this->ci =& get_instance();
        $this->ci->crypto = new Crypto();
        $this->cp = $this->ci->load->library('plugins/crypto');
        $this->table = 'tokens';
    }
    
    public function setAccessLevel($parameter = []){
        $this->accessLevel = $parameter;
        return $this;
    }

    public function createToken($data, $parameter = []){
        $this->ci->load->library('plugins/crypto');
        $token = [
            'data'=>$data,
            'created_at'=>time()
        ];

        if(!isset($parameter['expiry']))$token['expires_at'] = time()+900;
        elseif(empty($parameter['expiry']))$token['expires_at'] = time()+900;

        if(isset($parameter['accessLevel']) && $parameter['accessLevel'] == true){
            if(!isset($this->accessLevel[$parameter['levelParams']])) throw new Exception('Access Level Parameters does not exist');
            foreach ($this->accessLevel[$parameter['levelParams']] as $key => $value)
                if($parameter['basedOn'] == $value){
                    $token['AccessLevel'] = $key;
                    break;
                }else continue;
            
        }
        
        $token = $this->ci->crypto->encrypt($token);

        if(isset($parameter['saveToDB'])){
            if($parameter['saveToDB'] == true)
            $this->ci->db->insert($this->table, ['token'=>$token]);
        }
        
        return $token;
    }
    public function checkToken($return = false){
        if(!isset($_SERVER['HTTP_PWAUTH']))throw new Exception('No Token');
        elseif(empty($_SERVER['HTTP_PWAUTH']))throw new Exception('Empty Token');
        
        $this->token = $_SERVER['HTTP_PWAUTH'];

        return $return ? $this->token : $this;
    }

    public function validate(){
        try{
            if(!isset($this->token)) throw new Exception('Token does not exist');
            elseif(empty($this->token)) throw new Exception('Token is empty');
            
            $decrypted = (object) $this->ci->crypto->decrypt($this->token);
            
            if(!$decrypted)throw new Exception('Decryption failed');
            
            if(!isset($decrypted->expires_at)) throw new Exception('The provided token is invalid');
            
        if(time() >= $decrypted->expires_at){
            if(time() >= $decrypted->expires_at+600) throw new GlobalDataException('The token has expired', ['renewable'=>false, 'message'=>'The token has surpassed the renewable time frame']);
            else{
                $renewed = $this->renew();
                throw new GlobalDataException('The token has expired', ['renewable'=>true, 'data'=>json_decode($renewed)]);
            }
        }
        
        return $decrypted;
        } catch (GlobalDataException $e){
            http_response_code(403);
            header("Content-Type: application/json");

            die(json_encode([
                'success' => false,
                'msg' => $e->getMessage(),
                'data'=> $e->getData(),
            ]));
        }
    }

    public function renew(){
        $decrypted = (object) $this->ci->crypto->decrypt($this->token);
        if(!$decrypted)throw new Exception('Decryption failed');
        $token = [
            'data'=> $decrypted->data,
            'created_at'=>time(),
            'expires_at'=>time()+900
        ];
        if(isset($decrypted->AccessLevel)) $token['AccessLevel'] = $decrypted->AccessLevel;
        $reEncrypt = $this->ci->crypto->encrypt($token);
        if(!$reEncrypt)throw new Exception('Failed to encrypt');
        return $reEncrypt;
    }

    public function getData(){
        $decrypted = $this->validate();

        return (object) $decrypted->data;
    }

    public function getParams(){
        $decrypted = $this->validate();

        $params = ['expires_at'=>$decrypted->expires_at, 'AccessLevel'=>$decrypted->AccessLevel];
        return (object) $params;
    }
    public function getAccessLevel(){
        return $this->accessLevel;
    }


    public function checkHeader($header, $return){
        if(!isset($_SERVER['HTTP_'.$header]))throw new Exception('No Header');
        elseif(empty($_SERVER['HTTP_'.$header]))throw new Exception('Empty Token');
        
        $this->headerValue = $_SERVER['HTTP_'.$header];

        return $return ? $this->headerValue : $this;
    }
    public function getHeaderValue(){
        return $this->headerValue;
    }


/*  =============================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================*/

    public function tokenize($data, $saveToDb = false,$expiry = 900){
        $this->ci->load->library('plugins/crypto');
        $token = [
            'data' => $data,
            'created_at' => time(),
            'expires_at' => time()+$expiry
        ];

        $token = $this->ci->crypto->encrypt($token);

        if($saveToDb)
            $this->ci->db->insert($this->table, ['token'=>$token]);
        

        return $token;
    }

    public function renewToken($token, $throwExceptionIfFailed = false){
        try{

            $this->ci->load->library('plugins/crypto');
            
            $decrypted = (object) $this->ci->crypto->decrypt($token);

            if($decrypted == false){
                if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                else return false;
            }
                

            if(!isset($decrypted->expires_at)){
                if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                else return false;
            }

            if(time() >= $decrypted->expires_at){
                if(time() >= $decrypted->expires_at+600){
                    $renewed = $this->tokenize($decrypted->data);
                    return $renewed;
                }else{
                    if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                    else return false;
                }
            }

            return $decrypted;

        }catch(Exception $ex){
            http_response_code(403);
            header("Content-Type: application/json");

            die(json_encode([
                'success' => false,
                'msg' => $ex->getMessage()
            ]));
        }
    }

    public function validateToken($token, $throwExceptionIfFailed = false){
        try{

            $this->ci->load->library('plugins/crypto');
            
            $decrypted = (object) $this->ci->crypto->decrypt($token);

            if($decrypted == false){
                if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                else return false;
            }
                

            if(!isset($decrypted->expires_at)){
                if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                else return false;
            }

            if(time() >= $decrypted->expires_at){
                if($throwExceptionIfFailed !== false) throw new Exception('The provided token is invalid');
                else return false;
            }

            return $decrypted;

        }catch(Exception $ex){
            http_response_code(403);
            header("Content-Type: application/json");

            die(json_encode([
                'success' => false,
                'msg' => $ex->getMessage()
            ]));
        }
    }

    public function logout(){
        try{
            $this->isLoggedIn();

            $this->ci->db->where('token',$_SERVER['HTTP_PWAUTH'])->delete($this->table);
        }catch(Exception $ex){
            http_response_code(403);
            header("Content-Type: application/json");

            die(json_encode([
                'success' => false,
                'msg' => $ex->getMessage()
            ]));
        }
    }

    public function isLoggedIn(){
        try{
            if(empty($_SERVER['HTTP_PWAUTH']))
            throw new Exception('Accessing this resource is forbidden');

            $token = $_SERVER['HTTP_PWAUTH'];

            $this->ci->load->library('plugins/crypto');
            
            $decrypted = (object) $this->ci->crypto->decrypt($token);

            if($decrypted == false)
                throw new Exception('Accessing this resource is forbidden');

            if(!isset($decrypted->expires_at))
                throw new Exception('Accessing this resource is forbidden');

            if(time() >= $decrypted->expires_at){
                throw new Exception('Authorization token has expired');
                $this->ci->db->where('token',$token)->delete($this->table);
            }


            $isMatch = $this->ci->db->from($this->table)->where('token', json_encode($token))->get()->num_rows();
            
            if($isMatch != 1)
                throw new Exception('Accessing this resource is forbidden');

            return $decrypted;


        }catch(Exception $ex){
            http_response_code(403);
            header("Content-Type: application/json");

            die(json_encode([
                'success' => false,
                'msg' => $ex->getMessage()
            ]));
        }
    }
}
