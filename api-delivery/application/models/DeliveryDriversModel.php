<?php
class DeliveryDriversModel extends CI_Model{
    private $schema = [];
    private $table;
    private $user;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'deliveryDriver';
        $this->user = $_ENV['DB_PREFIX'].'users';
        $this->load->library('plugins/validator');
        $this->schema = $this->validator->createSchema($this->table);
    }

    public function getSchema(){
        return $this->schema;
    }

    public function all(){
        return $this->db->get($this->table)->result();
    }

    public function retrieveData($userID){
        $res = $this->db->select('*')
                        ->from($this->table)
                        ->where('driverUserOwner', $userID)
                        ->get()->row();
        if ($res){
            return $res;
        }else{
            return false;
        }
    }

    public function DeliveryDriverRegister($driverUserOwner, $driverFname, $driverLname, $driverAddress, $driverTownCity, $driverState, $driverZip, $driversLicenseNumber){
        $res = $this->db->insert($this->table, array(
            'driverUserOwner' => $driverUserOwner,
            'driver_firstName' => $driverFname,
            'driver_lastName' => $driverLname,
            'driver_address' => $driverAddress,
            'driver_towity' => $driverTownCity,
            'driver_state' => $driverState,
            'driver_zip' => $driverZip,
            'driver_driversLicenseNumber' => $driversLicenseNumber,
            'status' => 'pending'
        ));
        
        if (!$res) {
            return $this->db->error();
        } else {
            $this->db
            ->set(array('users_access_level_id' => 4))
            ->where('userID', $driverUserOwner)
            ->update('srdr_users');
            return $this->db->affected_rows() > 0;
        }
    }
    
    public function setStatus($driverID, $status){
        return $this->db->set(['status' => $status])
                        ->where('driverID', $driverID)
                        ->update($this->table);
   }
   public function setPfp($id, $path){
    return $this->db->where('comp_userOwner', $id)->update($this->table, ['comp_businessPhoto' => $path]);
   }
}