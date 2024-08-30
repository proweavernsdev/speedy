<?php
class CompanyModel extends CI_Model{
    private $schema = [];
    private $sbusrs;
    private $table;
    private $user;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'company';
        $this->user = $_ENV['DB_PREFIX'].'users';
        $this->sbusrs = $_ENV['DB_PREFIX'].'subUsers';
        $this->load->library('plugins/validator');
        $this->schema = $this->validator->createSchema($this->table);
    }

    public function getSchema(){
        return $this->schema;
    }

    public function all(){
        return $this->db->get($this->table)->result();
    }

public function companyRegister($compName, $userOwner, $addr, $towity, $state, $zip){
        $res = $this->db->insert('srdr_company', array(
            'comp_companyName' => $compName,
            'comp_userOwner' => $userOwner,
            'comp_address' => $addr,
            'comp_towity' => $towity,
            'comp_state' => $state,
            'comp_zip' => $zip,
            'status' => 'pending'
        ));
        
        if (!$res) {
            return $this->db->error();
        } else {
            $this->db
            ->set(array('users_access_level_id' => 2))
            ->where('userID', $userOwner)
            ->update('srdr_users');
            return $this->db->affected_rows() > 0;
        }
}

public function updateCompDetails($compID, $data){
    // create an array to store data with values
    $toArray = [];
    // get all keys inside $data
    foreach ($data as $key => $value) {
        // check if key is not empty
        if (!empty($value) && $value != null) {
            // append data to $toArray
            switch ($key) {
                case 'CompanyName':
                    $toArray['comp_companyName'] = $value;
                    break;
                case 'Address':
                    $toArray['comp_address'] = $value;
                    break;
                case 'Towity':
                    $toArray['comp_towity'] = $value;
                    break;
                case 'State':
                    $toArray['comp_state'] = $value;
                    break;
                case 'Zip':
                    $toArray['comp_zip'] = $value;
                    break;
            }
        }
    }
    $res = $this->db->set($toArray)
                    ->where('companyID', $compID)
                    ->update($this->table);
    if ($this->db->affected_rows() > 0){
        $toArray['success'] = true;
        $toArrayObject = (object) $toArray;
        return $toArrayObject;
    }else{
        $toArray['success'] = false;
        $toArray['dbError'] = $this->db->error();
        $toArrayObject = (object) $toArray;
        return $toArrayObject;
    } 
}

public function select($column, $userID, $table){
    return $this->db->select('*')->from($table)->where($column, $userID)->get();
}

public function retrieveData($userID){
        return $this->db->select('*')
                    ->from($this->table)
                    ->where('comp_userOwner', $userID)
                    ->get()->row();
}

public function setStatus($companyID, $status){
        return $this->db->set(['status' => $status])
                        ->where('companyID', $companyID)
                        ->update($this->table);
}

public function setPfp($id, $path, $whereColumn, $table, $setColumn){
    return $this->db->where($whereColumn, $id)->update($table, [$setColumn => $path]);
}

public function registerEmloyee($id, $rawData){
    $hashed = password_hash($rawData['password'], PASSWORD_DEFAULT);
    $newData = ['userID'=>$rawData['uid'], 'users_email' => $rawData['email'], 'users_password' => $hashed, 'users_access_level_id' => 5];
    $usr = $this->db->insert($this->user, $newData);
    $inserted_id = $this->db->insert_id();
    $sbusrsDetail = ['subs_underCompany' => $id, 'subs_userOwner' => $inserted_id, 'subs_firstName' => $rawData['FirstName'], 'subs_lastName' => $rawData['LastName'], 'status' => 'active'];
    $sbusrs = $this->db->insert($this->sbusrs, $sbusrsDetail);
    if($usr && $sbusrs){
        return $this->db->affected_rows() > 0;
    }else{
        return $this->db->error();
    }
}

public function employeeRetrieve($id){
    $result = $this->db->select('*')
                    ->from($this->sbusrs)
                    ->where('subs_underCompany', $id)
                    ->get()
                    ->result_array();
    if (!empty($result)) {
        return $result;
    } else {
        return ['success' => false, 'error' => 'No employees found for the specified company'];
    }
}

public function setEmployeeStatus($subUserID, $status){
    return $this->db->set(['status' => $status])
                    ->where('sub_userID', $subUserID)
                    ->update($this->sbusrs);
}
}