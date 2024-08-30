<?php
class MY_DB extends CI_DB {

    protected $db2;

    public function __get($property) {
        if ($property === 'db2') {
            if (empty($this->db2)) {
                // Load the db2 connection if not already loaded
                $this->db2 = $this->load->database('db2', TRUE);
            }
            return $this->db2;
        }
        return parent::__get($property);
    }
}