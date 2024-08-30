<?php
class VehicleModel extends CI_Model{
    private $schema = [];
    private $table;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'deliveryVehicleType';
        $this->load->library('plugins/validator');
        $this->load->library('middlewares/History');
        $this->schema = $this->validator->createSchema($this->table);
    }

    public function getSchema(){
        return $this->schema;
    }

    public function all(){
        return $this->db->get($this->table)->result();
    }

    public function addVehicle($data){
        $this->db->insert($this->table, array(
            'vehic_type' => $data['VehicleType'],
            'vehicle_baseFee' => $data['BaseFee'],
            'vehicle_distanceFee' => $data['DistanceFee'],
            'vehicle_baseDistance' => $data['BaseDistance']
        ));
        return $this->db->affected_rows() > 0;
    }

    public function updtVehicle($vehicleID, $data, $id){
        // Get the primary key column name and value
        $primaryKeyColumnName = key($data);
        $primaryKeyValue = current($data);

        $preUpdt = $this->db->where('vehicleTypeID', $vehicleID)->get($this->table)->result_array();
        $toArray = [];
        foreach ($data as $key => $value) {

            if ($key === $primaryKeyColumnName) {
                continue;
            }

            // check if value inside key is not empty
            if (!empty($value) && $value != null) {
                // append data to $toArray
                switch ($key) {
                    case 'VehicleType':
                        $toArray['vehic_type'] = $value;
                        break;
                    case 'BaseFee':
                        $toArray['vehicle_baseFee'] = $value;
                        break;
                    case 'DistanceFee':
                        $toArray['vehicle_distanceFee'] = $value;
                        break;
                    case 'BaseDistance':
                        $toArray['vehicle_baseDistance'] = $value;
                        break;
                }
            }
        }
        $res = $this->db->set($toArray)->where('vehicleTypeID', $vehicleID)->update($this->table);
        if($res) {
            $postUpdt = $this->db->where('vehicleTypeID', $vehicleID)->get($this->table)->result_array();
            $description = "Updated vehicle type";
            $history = $this->history->updated($postUpdt[0], $preUpdt[0], $this->table, $description, $id);
            if($history) return true;
            else return false;
        }
        else return false;
    }
    
}