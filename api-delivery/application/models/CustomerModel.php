<?php
class CustomerModel extends CI_Model{
    private $schema = [];
    private $table;
    private $user;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'customers';
        $this->user = $_ENV['DB_PREFIX'].'users';
        $this->load->library('plugins/validator');
        $this->load->library('backendsync/usersbs');
        $this->load->library('middlewares/History');
        // $this->schema = $this->validator->createSchema($this->table);
    }

    public function getSchema(){
        return $this->schema;
    }

    public function setPfp($id, $path, $whereColumn, $table, $setColumn){
        return $this->db->where($whereColumn, $id)->update($table, [$setColumn => $path]);
    }

    public function all(){
        return $this->db->get($this->table)->result();
    }

    public function retrieveData($userID){
        return $this->db->select('*')
                    ->from($this->table)
                    ->where('cust_userOwner', $userID)
                    ->get()->row();
    }
    public function customerRegister($data, $userOwner){
        $res = $this->db->insert($this->table, array(
            'cust_userOwner' => $userOwner,
            'cust_firstName' => $data['FirstName'],
            'cust_lastName' => $data['LastName'],
            'cust_address' => $data['Address'],
            'cust_towity' => $data['TownCity'],
            'cust_state' => $data['State'],
            'cust_zip' => $data['Zip']
        ));
        $last_insert_id = $this->db->insert_id();
        if (!$res) {
            return $this->db->error();
        } else {
            $res = $this->db
            ->set(array('users_access_level_id' => 3))
            ->where('userID', $userOwner)
            ->update('srdr_users');
            if($res){
                /*
                $data = $this->db->where('customerID', $last_insert_id)->get($this->table);
                $history = $this->history->inserted($data, $this->table, "Customer Registered");
                if($history){
                    $getData = $this->where('userID', $userOwner)->get($this->user)->row_array();
                    $toArray = [
                        'user_password' => $getData['users_password'],
                        'user_email' => $getData['users_email'],
                        'user_firstname' => $data['cust_firstName'],
                        'user_lastname' => $data['cust_lastName'],
                        'user_address' => $data['cust_lastName'],
                        'user_city' => $data['cust_lastName'],
                        'user_state' => $data['cust_lastName'],
                        'user_zip' => $data['cust_lastName'],
                        'user_role' => 'Customer',
                        'user_address' => $data['cust_lastName'],
                        'user_address' => $data['cust_lastName'],
                    ];
                }
                */
                return true;
            }else{
                return false;
            }
            
        }
    }
    public function updateDetails($custID, $data){
        // create an array to store data with values
        error_log("Updating customer details for customerID: $custID with data: " . json_encode($data));
        $toArray = [];
        // get all keys inside $data
        foreach ($data as $key => $value) {
            // check if key is not empty
            if (!empty($value) && $value != null) {

                error_log("Processing $key: $value");
                // append data to $toArray
                switch ($key) {
                    case 'cust_firstName':
                        $toArray['cust_firstName'] = $value;
                        break;
                    case 'cust_lastName':
                        $toArray['cust_lastName'] = $value;
                        break;
                    case 'cust_address':
                        $toArray['cust_address'] = $value;
                        break;
                    case 'cust_towity':
                        $toArray['cust_towity'] = $value;
                        break;
                    case 'cust_state':
                        $toArray['cust_state'] = $value;
                        break;
                    case 'cust_zip':
                        $toArray['cust_zip'] = $value;
                        break;
                }
            }
        }
        error_log("Final data to update: " . json_encode($toArray));

        $res = $this->db->set($toArray)
                        ->where('customerID', $custID)
                        ->update($this->table);

        if ($this->db->affected_rows() > 0){
            $toArray['success'] = true;
            error_log("Update successful for customerID: $custID");
        }else{
            $toArray['success'] = false;
            $toArray['dbError'] = $this->db->error();
            error_log("Database error while updating customer details: " . json_encode($this->db->error()));
        }
        $toArrayObject = (object) $toArray;

        error_log("Returning from updateDetails: " . json_encode($toArrayObject));
        return $toArrayObject; 
    }
}