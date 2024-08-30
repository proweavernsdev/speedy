<?php
class QueryBuilder extends CI_Model {
    private $prefix;
    private $clPrefix;    
    public function __construct(){
        parent::__construct();
        $this->prefix = $_ENV['DB_PREFIX'];
    }

    // Set the Tabl
    public function setColumnPrefix($prefix){
        $this->clPrefix = $prefix;
        return $this;
    }
    public function removeColumnPrefix(){
        $this->clPrefix = null;
        return $this;
    }
    public function select($table, $condition = false, $parameter = []){
        // Declare the table
        $query = $this->db->select('*')->from($this->prefix.$table);
    
        // Check if conditions are provided
        if($condition == true){
            // Determine the prefix to use for the conditions
            $prefix = ($this->clPrefix !== null) ? $this->clPrefix : null;
            
            // Apply the conditions
            foreach($parameter as $key=>$value){
                // Build the query with the specified conditions
                $query = $query->where($prefix.$key, $value);
            }
        }
        // Execute the query
        $query = $query->get();
        // Check if any rows are returned
        return ($query->num_rows() > 0) ? $query->result() : false; 
        
    }
    public function insert($table, $data, $condition = false){
        // Insert data into the specified table
        $res = $this->db->insert($this->prefix.$table, $data);
        if($res){
            /**
             *  Check condition if true or false
             *  if condition = true, return last inserted ID
             *  else return affected rows
             */
            return $condition ?  $this->db->insert_id() : $this->db->affected_rows();
        }
    }
    public function update($table, $data, $condition = false, $parameter){
        // Initialize the query builder instance
        $query = $this->db;
    
        // Check if conditions are provided
        if($condition == true){
            // Determine the prefix to use for the conditions
            $prefix = ($this->clPrefix !== null) ? $this->clPrefix : null;
            
            // Apply the conditions
            foreach($parameter as $key=>$value){
                // Build the query with the specified conditions
                $query = $query->where($prefix.$key, $value);
            }
        }
    
        // Perform the update operation
        return $query->update($table, $data);
    }
}