<?php
class TaxonomyHubModel extends CI_Model{
    private $schema = [];
    private $size;
    private $weight;

    public function __construct(){
        parent::__construct();
        $this->size = $_ENV['DB_PREFIX'].'sizeCategory';
        $this->weight = $_ENV['DB_PREFIX'].'weightCategory';
        $this->load->library('plugins/validator');
        $this->load->library('middlewares/History');
    }

    public function getSchema(){
        return $this->schema;
    }

    public function all(){
        $size = $this->db->get($this->size)->result();
        $weight = $this->db->get($this->weight)->result();
        
        if(isset($size) && isset($weight)){
            return array('size'=>$size, 
                        'weight'=>$weight);
        }else{
            return false;
        }
    }
    
    public function addSize($data){
        $dataObject = (object) $data;
        $toArray = array('sc_categoryName' => $dataObject->Category,
                        'sc_minSize' => $dataObject->Min,
                        'sc_maxSize' => $dataObject->Max,
                        'sc_setFee' => $dataObject->SetFee);
        $res = $this->db->insert($this->size, $toArray);
        return $this->db->affected_rows() > 0;
    }
    public function addWeight($data){
        $dataObject = (object) $data;
        $toArray = array('wc_categoryName' => $dataObject->Category,
                        'wc_minWeight' => $dataObject->Min,
                        'wc_maxWeight' => $dataObject->Max,
                        'wc_setFee' => $dataObject->SetFee);
        $res = $this->db->insert($this->weight, $toArray);
        return $this->db->affected_rows() > 0;
    }

    public function updateWeight($data){
        $toArray = [];
        // get all keys inside $data
        foreach ($data as $key => $value) {
            // check if value inside key is not empty
            if (!empty($value) && $value != null) {
                // append data to $toArray
                switch ($key) {
                    case 'Category':
                        $toArray['wc_categoryName'] = $value;
                        break;
                    case 'Min':
                        $toArray['wc_minWeight'] = $value;
                        break;
                    case 'Max':
                        $toArray['wc_maxWeight'] = $value;
                        break;
                    case 'SetFee':
                        $toArray['wc_setFee'] = $value;
                        break;
                }
            }
        }
        $res = $this->db->set($toArray)
                        ->where('WeightCategoryID', $data['ID'])
                        ->update($this->weight);
        if ($this->db->affected_rows() > 0) {
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

    public function updateSize($data){
        $toArray = [];
        // get all keys inside $data
        foreach ($data as $key => $value) {
            // check if value inside key is not empty
            if (!empty($value) && $value != null) {
                // append data to $toArray
                switch ($key) {
                    case 'Category':
                        $toArray['sc_categoryName'] = $value;
                        break;
                    case 'Min':
                        $toArray['sc_minSize'] = $value;
                        break;
                    case 'Max':
                        $toArray['sc_maxSize'] = $value;
                        break;
                    case 'SetFee':
                        $toArray['sc_setFee'] = $value;
                        break;
                }
            }
        }
        $res = $this->db->set($toArray)
                        ->where('SizeCategoryID', $data['ID'])
                        ->update($this->size);

        if ($this->db->affected_rows() > 0) {
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

    public function deleteSize($data, $id){
        $preDlt = $this->db->select('*')->from($this->size)->where('SizeCategoryID', $data)->get()->result_array();
        $delete = $this->db->delete($this->size, ['SizeCategoryID' => $data]);
        if($delete){
            $history = $this->history->deleted($preDlt[0], $this->size, 'Size Category deleted', $id);
            if($history) return true;
            else return false;
        }else return false;
    }
    public function deleteWeight($data, $id){
        $preDlt = $this->db->select('*')->from($this->weight)->where('WeightCategoryID', $data)->get()->result_array();
        $delete = $this->db->delete($this->weight, ['WeightCategoryID' => $data]);
        if($delete){
            $history = $this->history->deleted($preDlt[0], $this->weight, 'Weight Category deleted', $id);
            if($history) return true;
            else return false;
        }else return false;
    }
}