<?php

class Filehelper{
    private $ci;
    private $basepath;
    private $rootURL;

    public function __construct(){
        $this->ci =& get_instance();
        $this->basepath = $_ENV['FILE_BASEPATH'];
        $this->rootURL = [
            'protocol' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http",
            'domain' => $_SERVER['HTTP_HOST']
        ];
    }

    public function viewfiles($path = '', $createWhenNonExistent = false){
        $dirPath = FCPATH . $this->basepath . $path;
        
        $this->ci->load->helper('directory');

        if(!is_dir($dirPath) && $createWhenNonExistent){
            mkdir($dirPath,0777);
            $res = directory_map($dirPath);
        } else if(!is_dir($dirPath) && !$createWhenNonExistent)
            return [
                'success' => false,
                'result' => 'Path does not exist!'
            ];
        
        $res = directory_map($dirPath);

        return [
            'success' => $res == false ? false : true,
            'location' => $dirPath,
            'result' => $res
        ];
    }


    public function upload($path = '', $fieldName = 'file', $createWhenNonExistent = false) {
        
        // Set the path to the uploads directory
        $dirPath = FCPATH . $this->basepath . $path;
        // Set configuration options for file uploads
        $config['upload_path'] = $dirPath;
        $config['allowed_types'] = $_ENV['FILE_ALLOWED_TYPES'];
        $config['encrypt_name'] = $_ENV['FILE_ENCRYPT_NAME'] === 'true' ? true : false;
        $config['max_size'] = $_ENV['FILE_MAX_SIZE'];

         // If the directory does not exist, create it and set permissions
         if (!is_dir($dirPath) && $createWhenNonExistent) {
            mkdir($dirPath, 0777, true);
            chmod($dirPath, 0777);
        } else if(!is_dir($dirPath) && !$createWhenNonExistent)
            return [
                'success' => false,
                'result' => 'Path does not exist!'
            ];
    
        // Load the file upload library and configure it with the given options
        $this->ci->load->library('upload', $config);
    
        // Attempt to upload the file, and return an error message if it fails
        if (!$this->ci->upload->do_upload($fieldName)) {
            $errString = $this->ci->upload->display_errors();
            return (array(
                'success' => false,
                'msg' => $errString
            ));
            return;
        }
        // If the upload succeeds, return the file name and path as a JSON-encoded response

        
        return (array(
            'success' => true,
            'result' => array(
                'file_name' => $this->ci->upload->data()['file_name'],
                'path' => "{$this->rootURL['protocol']}://{$this->rootURL['domain']}/api-delivery/{$this->basepath}/{$path}/{$this->ci->upload->data()['file_name']}"
            )
        ));
        
    }

    public function uploadMultiple($path, $fieldName = 'files', $createWhenNonExistent = false){
        $results = [];
        
        foreach($_FILES[$fieldName]['name'] as $index => $fileName) {
            // Set the file upload parameters for the current file
            $_FILES["{$fieldName}_{$index}"]['name'] = $_FILES[$fieldName]['name'][$index];
            $_FILES["{$fieldName}_{$index}"]['type'] = $_FILES[$fieldName]['type'][$index];
            $_FILES["{$fieldName}_{$index}"]['tmp_name'] = $_FILES[$fieldName]['tmp_name'][$index];
            $_FILES["{$fieldName}_{$index}"]['error'] = $_FILES[$fieldName]['error'][$index];
            $_FILES["{$fieldName}_{$index}"]['size'] = $_FILES[$fieldName]['size'][$index];

            $results[] = $this->upload($path, "{$fieldName}_{$index}", $createWhenNonExistent);
        
        }

        return [
            'success_count' => array_reduce($results, function($oldCount,$item){
                if($item['success']) return $oldCount+1;
                return $oldCount;
            },0),
            'total' => count($_FILES[$fieldName]['name']),
            'results' => $results
        ];
    }

    public function delete($path) {
        // Set the path to the file to be deleted
        $dirPath = FCPATH . $this->basepath . $path;

        if(!file_exists($dirPath))
            return [
                'success' => false,
                'result' => 'File does not exist!'
            ];
    
        // Attempt to delete the file
        if(unlink($dirPath))
            return [
                'success' => true,
                'msg' => 'File deleted'
            ];
        else [
            'success' => false,
            'msg' => 'Failed to delete file'
        ];
    } 


}