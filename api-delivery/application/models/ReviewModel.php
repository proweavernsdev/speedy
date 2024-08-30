<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReviewModel extends CI_Model
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = $_ENV['DB_PREFIX'] . 'driverReviews'; // Adjust table name as per your database setup
        $this->load->library('plugins/validator');
    }

    public function getReview($review_id)
    {
        return $this->db->select('*')->from($this->table)->where('review_id', $review_id)->get()->row();
    }

    public function addReview($data)
    {
        $this->db->insert($this->table, array(
            'driver_id' => $data['driver_id'],
            'customerID' => $data['customerID'],
            'rating' => $data['rating'],
            'comment' => $data['comment']
        ));
        return $this->db->affected_rows() > 0;
    }

    public function deleteReview($review_id)
    {
        $this->db->where('review_id', $review_id);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    public function getAllReviews()
    {
        return $this->db->select('*')->from($this->table)->get()->result();
    }
}
