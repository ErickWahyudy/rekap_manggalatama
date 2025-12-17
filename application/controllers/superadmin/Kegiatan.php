<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Kegiatan extends CI_controller
{
	function __construct()
	{
	 parent:: __construct();
     $this->load->helper('url');
      // needed ???
      $this->load->database();
      $this->load->library('session');
      $this->load->library('form_validation');
      
	 // error_reporting(0);
	 if($this->session->userdata('superadmin') != TRUE){
     redirect(base_url(''));
     exit;
	};
   $this->load->model('m_kegiatan');
	}

    private $token_api = '123kegiatan'; //ganti dengan token API yang valid

    //Kegiatan
    public function index($value='')
    {
        $api_url = base_url('superadmin/api/api_kegiatan/api_get_view');
        $token = $this->token_api;
    
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        $data_kegiatan = json_decode($response, true);
    
        if (isset($data_kegiatan['status']) && $data_kegiatan['status'] === true) {
            $view = array(
                'judul' => 'Data Kegiatan',
                'aksi'  => 'kegiatan',
                'data'  => $data_kegiatan['data'],
            );
        } else {
            $view = array(
                'judul' => 'Data Kegiatan',
                'aksi'  => 'kegiatan',
                'data'  => [],  
                'error_message' => $data_kegiatan['message'] ?? 'Gagal mengambil data dari API'
            );
        }
    
        $this->load->view('superadmin/kegiatan/lihat', $view);
    }

     //api_add
     public function api_add()
     {
         $rules = array(
             array(
                 'field' => 'nama_kegiatan',
                 'label' => 'Nama Kegiatan',
                 'rules' => 'required',
                 'errors' => array(
                     'required' => 'Nama Kegiatan tidak boleh kosong',
                 ),
             ),
             array(
                 'field' => 'tahun',
                 'label' => 'Tahun',
                 'rules' => 'required',
                 'errors' => array(
                     'required' => 'Tahun tidak boleh kosong',
                 ),
             ),
             array(
                 'field' => 'status',
                 'label' => 'Status',
                 'rules' => 'required',
                 'errors' => array(
                     'required' => 'Status tidak boleh kosong',
                 ),
             ),
         );
         $this->form_validation->set_rules($rules);
     
         if ($this->form_validation->run() == FALSE) {
             $response = [
                 'status' => false,
                 'message' => validation_errors()
             ];
         } else {
             $postData = [
                 'nama_kegiatan' => $this->input->post('nama_kegiatan'),
                 'tahun' => $this->input->post('tahun'),
                 'status' => $this->input->post('status'),
             ];
     
             $api_url = base_url('superadmin/api/api_kegiatan/api_add');
             $token = $this->token_api;
     
             $curl = curl_init();
     
             curl_setopt($curl, CURLOPT_URL, $api_url);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($curl, CURLOPT_POST, true);
             curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
             curl_setopt($curl, CURLOPT_HTTPHEADER, [
                 'Authorization: Bearer ' . $token, 
                 'Content-Type: application/x-www-form-urlencoded'  
             ]);
     
             $response = curl_exec($curl);
     
             curl_close($curl);
     
             $data_response = json_decode($response, true);
     
             if (isset($data_response['status']) && $data_response['status'] === true) {
                 $response = [
                     'status' => true,
                     'message' => 'Berhasil menambahkan data'
                 ];
             } else {
                 $response = [
                     'status' => false,
                     'message' => $data_response['message'] ?? 'Gagal menambahkan data'
                 ];
             }
         }
     
         $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
     }
     

      //API edit
      public function api_edit($id='', $SQLupdate='')
      {
        $rules = array(
          array(
            'field' => 'nama_kegiatan',
            'label' => 'Nama Kegiatan',
            'rules' => 'required'
          ),
          array(
            'field' => 'tahun',
            'label' => 'Tahun',
            'rules' => 'required'
          ),
          array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required'
          )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == FALSE) {
          $response = [
            'status' => false,
            'message' => 'Tidak ada data'
          ];
        } else {
          $postData = [
            'nama_kegiatan'    => $this->input->post('nama_kegiatan'),
            'tahun'            => $this->input->post('tahun'),
            'status'           => $this->input->post('status')
            ];

          $api_url = base_url('superadmin/api/api_kegiatan/api_edit/'.$id);
          $token = $this->token_api;
          
          $curl = curl_init();
     
          curl_setopt($curl, CURLOPT_URL, $api_url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData)); 
          curl_setopt($curl, CURLOPT_HTTPHEADER, [
              'Authorization: Bearer ' . $token, 
              'Content-Type: application/x-www-form-urlencoded' 
          ]);
  
          $response = curl_exec($curl);
  
          curl_close($curl);
  
          $data_response = json_decode($response, true);
  
          if (isset($data_response['status']) && $data_response['status'] === true) {
              $response = [
                  'status' => true,
                  'message' => 'Berhasil menambahkan data'
              ];
          } else {
              $response = [
                  'status' => false,
                  'message' => $data_response['message'] ?? 'Gagal menambahkan data'
              ];
          }
      }
  
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
  }

      //API hapus
      public function api_hapus($id='')
      {
  
          if(empty($id)){
           $response = [
             'status' => false,
             'message' => 'Data kosong'
           ];
          } else {
              $api_url = base_url('superadmin/api/api_kegiatan/api_delete/'.$id);
              $token = $this->token_api;
  
              $curl = curl_init();
  
              curl_setopt($curl, CURLOPT_URL, $api_url);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
          }
  
          $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
      }
	
}