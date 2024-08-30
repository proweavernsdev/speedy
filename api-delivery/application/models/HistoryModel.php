<?php
class HistoryModel extends CI_Model{
    private $schema = [];
    private $table;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'history';
        $this->load->library('plugins/validator');
    }

    public function get_all(){
        return $this->db->get($this->table)->result();
    }

}