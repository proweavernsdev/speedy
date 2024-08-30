<?php
class AppModel extends CI_Model{
    private $schema = [];
    private $table;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'appFees';
        $this->load->library('plugins/validator');
        $this->load->library('middlewares/History');
    }

    public function get_all(){
        return $this->db->get($this->table)->result();
    }

    public function updateFees($data, $id){
        // Declare variable array
        $toArray = [];
        // Check each data inside $data for its key and value
        foreach($data as $key => $value){
            // Check if value inside $key is empty
            if(!empty($value) || $value != null){
                // Check if the Key equals any of the cases and append to previously declared variable array
                switch ($key){
                    case 'AppFee':
                        $toArray['af_appFees'] = $value;
                        break;
                    case 'VAT':
                        $toArray['af_vat'] = $value;
                        break;
                }
            }else{
                continue;
            }
        }
        // Get the pre updated values
        $preUpdt = $this->db->get($this->table)->result_array();
        // Update database
        $update = $this->db->set($toArray)->update($this->table);
        // Check if update was successful
        if($update){
            // Get the post updated values
            $postUpdt = $this->db->get($this->table)->result_array();
            // Declare string variable
            $description = 'Updated Fees';
            // Add activity to History
            $updtHistory = $this->history->updated($postUpdt[0], $preUpdt[0], $this->table, $description, $id);
            // Check if successful
            if($updtHistory){
                return true;
            }else{
                return $updtHistory;
            }
        }else{
            // Return false when update isn't successful
            return false;
        }
    }
}