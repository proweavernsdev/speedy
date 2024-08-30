<?php
class UsersModel extends CI_Model{
    private $schema = [];
    private $table;
    private $otp;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'users';
        $this->otp = $_ENV['DB_PREFIX'].'otp';
        $this->load->library('plugins/validator');
        $this->load->library('middlewares/History');
        $this->load->library('sendemail');
        $this->load->library('backendsync/usersbs');
        $this->schema = $this->validator->createSchema($this->table);

        $this->schema['userID']->field = 'id';

        $this->schema['users_email']->field = 'email';
        $this->schema['users_password']->field = 'password';


    }

    public function getSchema(){
        return $this->schema;
    }

    public function login($email, $password){
        $res = $this->db
            ->select('*')
            ->from('srdr_users')
            ->where('users_email', $email)
            ->get();
        
        if ($res->num_rows() == 1){
            $users = $res->row();
            if (password_verify($password, $users->users_password)){
                return $users;
            }
            else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function test(){
        return $this->history->test();
    }

    public function register($rawData){
        $hashed = password_hash($rawData['password'], PASSWORD_DEFAULT);
        $newData = ['users_email' => $rawData['email'], 'users_password' => $hashed];
        $res = $this->db->insert($this->table, $newData);

        // $this->usersbs->userRegister($newData);


        if($res){
            $inserted_id = $this->db->insert_id();
            $query = $this->db->get_where($this->table, ['userID'=> $inserted_id])->result_array();
            $description = 'New user added';
            // $query[0] to access the first group of array
            $this->history->inserted($query[0], $this->table, $description);
            return true;
        }else{
            return false;
        }
    }

    public function userRoleUpdate($accessLevelID, $userID){
    $this->db
        ->set(array('users_access_level_id' => $accessLevelID))
        ->where('userID', $userID)
        ->update('srdr_users');
    return $this->db->affected_rows() > 0;
    }

    public function changePassword($id, $newPassword){
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $preUpdt = $this->db->select('*')->from($this->table)->where('userID', $id)->get()->result_array();
        $updt = $this->db
            ->set(['users_password' => $hashedNewPassword])
            ->where('userID', $id)
            ->update($this->table);
        if($updt){
            $postUpdt = $this->db->select('*')->from($this->table)->where('userID', $id)->get()->result_array();
            $description = 'Updated data';
            $history = $this->history->updated($preUpdt[0], $postUpdt[0], $id, $description);
            if($history){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

        // return $this->db->affected_rows() >0;
    }
    public function createAndSaveOTP(){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charLength = strlen($chars);
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $randomString .= $chars[rand(0, $charLength - 1)];
        }
        $newData = ['otp_code' => $randomString];
        $this->db->insert($this->otp, $newData);

        return $randomString; 
    }
    public function verifyOTP($otp){
        $res = $this->db
                ->select('*')
                ->from($this->otp)
                ->where('otp_code', $otp)
                ->get()->row();
        if ($this->db->affected_rows() > 0){
            $this->db
                ->where('otp_code', $otp)
                ->delete($this->otp);
            return true;
            
        }else{
            return false;
        }
    }

    public function deleteOTP($otp){
        $del = $this->db
                ->where('otp_code', $otp)
                ->delete($this->otp);
        return $this->db->affected_rows() > 0;
    }

    public function select($column, $userID){
        return $this->db->select('*')->from($this->table)->where($column, $userID)->get();
    }
}