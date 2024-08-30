<?php
class BookServiceModel extends CI_Model{
    private $table;

    public function __construct(){
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'].'customers';
    }

}