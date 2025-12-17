<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Backup extends CI_controller
{
	function __construct()
	{
	 parent:: __construct();
   $this->load->helper('url');
   // needed ???
   $this->load->database();
   $this->load->library('session');
   $this->load->dbutil(); // Load Database Utility Library
  $this->load->helper('file'); // Load File Helper
	 // error_reporting(0);
	 if($this->session->userdata('superadmin') != TRUE){
    redirect(base_url(''));
     exit;
	};
   $this->load->model('m_admin'); 
}

private $token_api = '123backup'; //ganti dengan token API yang valid


public function index() {
        $api_url = base_url('superadmin/api/api_backup/api_get_view');
        $token = $this->token_api;
    
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        $data_backup = json_decode($response, true);
    
        if (isset($data_backup['status']) && $data_backup['status'] === true) {
            $view = array(
                'judul' => 'Backup Database',
                'aksi'  => 'backup',
                'files'  => $data_backup['data'],
            );
        } else {
            $view = array(
                'judul' => 'Backup Database',
                'aksi'  => 'backup',
                'files'  => [],  
                'error_message' => $data_backup['message'] ?? 'Gagal mengambil data dari API'
            );
        }

    $this->load->view('superadmin/backup/form',$view);
}
    

  public function backupDatabase() {
    $api_url = base_url('superadmin/api/api_backup/api_add_backup');
    $token = $this->token_api;

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $data_response = json_decode($response, true);

    if (isset($data_response['status']) && $data_response['status'] === true) {
        $response = [
            'status' => true,
            'message' => 'Berhasil melakukan backup database'
        ];
    } else {
        $response = [
            'status' => false,
            'message' => $data_response['message'] ?? 'Gagal melakukan backup database'
        ];
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));

}

    public function restoreDatabase() {
        $file = $_FILES['file']; // Mengambil file dari form

        if(empty($file)) {
            $response = [
                'status' => false,
                'message' => 'Data kosong'
            ];
        } else {
            $api_url = base_url('superadmin/api/api_backup/api_restore');
            $token = $this->token_api;

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token
            ]);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'file' => new CURLFile($file['tmp_name'], $file['type'], $file['name'])
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $data_response = json_decode($response, true);

            if (isset($data_response['status']) && $data_response['status'] === true) {
                $response = [
                    'status' => true,
                    'message' => 'Berhasil melakukan restore database'
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => $data_response['message'] ?? 'Gagal melakukan restore database'
                ];
            }
        }
        // Set response content type to JSON
        $this->output->set_content_type('application/json');
        // Encode the response as JSON and send it to the client
        $this->output->set_output(json_encode($response));
    }



public function hapusBackup() {
    $backup = $this->input->post('backup'); // Ambil nilai dari parameter backup

    if(empty($backup)) {
        $response = [
          'status' => false,
          'message' => 'Data kosong'
        ];
       } else {
           $api_url = base_url('superadmin/api/api_backup/api_delete_backup/'.$backup);
           $token = $this->token_api;

           $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token
                ]);

                $response = curl_exec($curl);

                curl_close($curl);

                $data_response = json_decode($response, true);

                if (isset($data_response['status']) && $data_response['status'] === true) {
                    $response = [
                        'status' => true,
                        'message' => 'Berhasil menghapus data'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'message' => $data_response['message'] ?? 'Gagal menghapus data'
                    ];
                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }
        }



}