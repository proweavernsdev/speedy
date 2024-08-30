<?php

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('UsersModel');
	}

	public function index(){
		if($_SERVER['REQUEST_METHOD'] == 'GET') $this->create();
		else if($_SERVER['REQUEST_METHOD'] == 'POST') $this->read();
		else if($_SERVER['REQUEST_METHOD'] == 'PUT') $this->update();
		else if($_SERVER['REQUEST_METHOD'] == 'DELETE') $this->delete();
	}

	private function create(){

	}

	private function read(){
		
	}

	private function update(){
		
	}

	private function delete(){
		
	}
}
