<?php
class UsersModel_v2 extends CI_Model{
    private $table;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'users';
        $this->load->library('plugins/validator');
        $this->load->library('middlewares/History');
        $this->load->library('backendsync/usersbs');
    }

    public function checkDB($data){
        $res = $this->db->select('*')
                        ->from($this->table)
                        ->where('userID', $data['uid'])
                        ->where('users_email', $data['email'])
                        ->get();
        if($res->num_rows() > 0){
            return $res->row();
        }else{
            return false;
        }
    }

    public function registerDB($data){
        if(isset($data['password'])){
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $res = $this->db
            ->insert($this->table, [
                'userID'=> $data['uid'],
                'users_email'=>$data['email'],
                'users_password'=>$hashed,
                'users_access_level_id'=>$data['accessID']
            ]);
        }else{
            $res = $this->db
            ->insert($this->table, [
                'userID'=> $data['uid'],
                'users_email'=>$data['email'],
                'users_access_level_id'=>$data['accessID']
            ]);
        }
        if($this->db->affected_rows() > 0) return $res;
        else throw new Exception('Error inserting');
    }
}