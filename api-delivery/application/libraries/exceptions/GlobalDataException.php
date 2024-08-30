<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalDataException extends Exception
{
    private $data;

    public function __construct($message = "", $data = [], $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}