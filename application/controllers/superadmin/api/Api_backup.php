<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->dbutil();
        $this->load->helper('file');
    }

    private $token_api = '123backup'; // Token API yang valid

     // Function to verify_token API token
     private function verify_token() {
        $token = $this->input->get_request_header('Authorization');
        if ($token !== 'Bearer ' . $this->token_api) {
            $response = [
                'status' => false,
                'message' => 'Unauthorized access'
            ];
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response))
                        ->set_status_header(401);
        }
        return true;
    }

    //index backup
    public function api_get_view($value='')
    {
    
        if ($this->verify_token() === true) {
            $folder = 'themes/backup/';
            if (!is_dir($folder)) {
                $response = [
                    'status' => false,
                    'message' => 'Backup directory does not exist'
                ];
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($response))
                            ->set_status_header(404);
            }
        
            $files = scandir($folder);
            $backup_files = [];
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $backup_files[] = $file;
                }
            }
        
            if (empty($backup_files)) {
                $response = [
                    'status' => false,
                    'message' => 'No backup files found'
                ];
            } else {
                $response = [
                    'status'        => true,
                    'message'       => 'List of backup files',
                    'data'          => $backup_files
                ];
            }
        
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
        }
    }
    
    
    // API untuk backup database
    public function api_add_backup() {

        if ($this->verify_token() === true) {
            // Nama file backup
            date_default_timezone_set('Asia/Jakarta');
            $timestamp = date('d-F-Y_H-i-s');
            $backup_file_name = 'backup_' . $timestamp . '.sql';
    
            // Konfigurasi backup database
            $db_config = array(
                'format'      => 'sql',
                'filename'    => $backup_file_name,
                'add_drop'    => TRUE,
                'add_insert'  => TRUE,
                'newline'     => "\n",
            );
    
            // Eksekusi backup database
            $backup = $this->dbutil->backup($db_config);
            write_file('themes/backup/' . $backup_file_name, $backup);
    
            if ($backup) {
                $response = [
                    'status' => true,
                    'message' => 'Database backup successful',
                    'filename' => $backup_file_name
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Database backup failed'
                ];
            }
    
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response));
        }
    }
    

    // API untuk restore database
    public function api_restore() {
        $token = $this->input->get_request_header('Authorization');
        $valid_token = $this->valid_token_api;
    
        if ($token !== 'Bearer ' . $valid_token) {
            $response = [
                'status' => false,
                'message' => 'Unauthorized access'
            ];
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response))
                        ->set_status_header(401);
        }
    
        if (!isset($_FILES['file'])) {
            $response = [
                'status' => false,
                'message' => 'File not uploaded'
            ];
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($response))
                        ->set_status_header(400);
        }
    
        $file_path = 'themes/backup/' . $_FILES['file']['name'];
        $sql = file_get_contents($file_path);
    
        // Disable foreign key constraints
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
    
        // Restore database
        $sql_commands = explode(";\n", $sql);
        $this->db->trans_start();
        foreach ($sql_commands as $command) {
            if (trim($command) !== "") {
                $this->db->query($command);
            }
        }
        $this->db->trans_complete();
    
        // Enable foreign key constraints
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    
        if ($this->db->trans_status() === FALSE) {
            $response = [
                'status' => false,
                'message' => 'Failed to restore data'
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Database restored'
            ];
        }
        
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
    }


    

    // API untuk menghapus backup
    public function api_delete_backup($backup = null) {
        if ($this->verify_token() === true) {
            $response = [];
                
        if ($backup !== null) {
            $file_path = FCPATH . 'themes/backup/' . $backup;
            if (unlink($file_path)) {
                $response = [
                    'status' => true,
                    'message' => 'Database backup deleted'
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Database backup failed to delete'
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Invalid backup parameter'
            ];
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
        }
    }
}
