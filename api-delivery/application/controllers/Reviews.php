<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Set headers for CORS
header('Accept: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, PWAUTH");

// Check for OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once APPPATH . '/libraries/rest/Rest.php';

class Reviews extends Rest
{
    private $reviewModel;
    private $cp;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReviewModel');
        $this->load->library('plugins/crypto');
        // Shorten libraries and models
        $this->reviewModel = $this->ReviewModel;
        $this->cp = $this->crypto;
    }

    // Get all reviews
    protected function _get()
    {
        // Implement authentication and authorization logic if needed
        if (isset($_SERVER['HTTP_PWAUTH'])) {
            $token = $_SERVER['HTTP_PWAUTH'];
            $decrypted = $this->cp->decrypt($token);

            if (!$decrypted) {
                $this->response([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
                return;
            }

            $reviews = $this->reviewModel->getAllReviews();
            if ($reviews) {
                $this->response([
                    'success' => true,
                    'result' => $reviews
                ], 200);
            } else {
                $this->response([
                    'success' => false,
                    'message' => 'No reviews found'
                ], 404);
            }
        } else {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }
    }

    // Add a new review
    protected function _post()
    {
        $data = $this->json();
        // Check for token
        if (!isset($_SERVER['HTTP_PWAUTH'])) {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
            return;
        }

        // Decrypt the token
        $token = $_SERVER['HTTP_PWAUTH'];
        $decrypted = $this->cp->decrypt($token);

        if (!$decrypted) {
            $this->response([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
            return;
        }

        // Validate required fields
        if (
            empty($data['driver_id']) || empty($data['customerID']) || empty($data['rating']) || empty($data['comment'])
        ) {
            $this->response([
                'success' => false,
                'message' => 'Missing required fields'
            ], 400);
            return;
        }

        // Prepare review data
        $reviewData = [
            'driver_id' => $data['driver_id'],
            'customerID' => $data['customerID'],
            'rating' => $data['rating'],
            'comment' => $data['comment']
        ];

        // Add the review to the database
        $result = $this->reviewModel->addReview($reviewData);

        if ($result) {
            $this->response([
                'success' => true,
                'message' => 'Review has been successfully added'
            ], 200);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Failed to add review'
            ], 500);
        }
    }

    // Delete a review
    protected function _delete()
    {
        $review_id = $this->uri->segment(3); // Assuming review ID is passed in the URL segment
        if (!$review_id) {
            $this->response([
                'success' => false,
                'message' => 'Review ID is required'
            ], 400);
            return;
        }

        $res = $this->reviewModel->deleteReview($review_id);
        if ($res) {
            $this->response([
                'success' => true,
                'message' => 'Review has been deleted'
            ], 200);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Failed to delete review'
            ], 500);
        }
    }
}