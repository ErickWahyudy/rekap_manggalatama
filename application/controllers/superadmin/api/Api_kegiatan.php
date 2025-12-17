<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_kegiatan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('m_kegiatan');
        $this->load->library('form_validation');
    }

    private $valid_token_api = '123kegiatan'; // Token API yang valid


    public function api_get_view() {
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

        $data_kegiatan = $this->m_kegiatan->view_kegiatan()->result_array();

        $response = [
            'status' => true,
            'message' => 'Data Kegiatan',
            'data' => $data_kegiatan
        ];

        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
    }

    private function acak_id($panjang)
    {
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($i = 0; $i < $panjang; $i++) {
            $pos = rand(0, strlen($karakter) - 1);
            $string .= $karakter{$pos};
        }
        return $string;
    }
    
     //mengambil id kegiatan urut terakhir
     private function id_kegiatan_urut($value='')
     {
     $this->m_kegiatan->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_kegiatan'];
     $karakter= $this->acak_id(6);
     $urut    = substr($id, 1, 3);
     $tambah  = (int) $urut + 1;
     
     if (strlen($tambah) == 1){
     $newID = "K"."00".$tambah.$karakter;
         }else if (strlen($tambah) == 2){
         $newID = "K"."0".$tambah.$karakter;
             }else (strlen($tambah) == 3){
             $newID = "K".$tambah.$karakter
             };
         return $newID;
     }

     //API add 
     public function api_add($value='')
     {
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
             $SQLinsert = [
                 'id_kegiatan'    => $this->id_kegiatan_urut(),
                 'nama_kegiatan'  => $this->input->post('nama_kegiatan'),
                 'tahun'          => $this->input->post('tahun'),
                 'status'         => $this->input->post('status'),
             ];
     
             if ($this->m_kegiatan->add($SQLinsert)) {
                 $response = [
                     'status' => true,
                     'message' => 'Berhasil menambahkan data'
                 ];
             } else {
                 $response = [
                     'status' => false,
                     'message' => 'Gagal menambahkan data'
                 ];
             }
         }
     
         $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
     }



     public function api_edit($id = '', $SQLupdate = '')
     {
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
      };
  
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
          $SQLupdate = [
              'nama_kegiatan'  => $this->input->post('nama_kegiatan'),
              'tahun'          => $this->input->post('tahun'),
              'status'         => $this->input->post('status'),
          ];
  
          if ($this->m_kegiatan->update($id, $SQLupdate)) {
              $response = [
                  'status' => true,
                  'message' => 'Berhasil menambahkan data'
              ];
          } else {
              $response = [
                  'status' => false,
                  'message' => 'Gagal menambahkan data'
              ];
          }
      }
  
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
  }     

     //API hapus
     public function api_delete($id='')
     {
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

         if(empty($id)){
          $response = [
            'status' => false,
            'message' => 'Data kosong'
          ];
        }else{
          if ($this->m_kegiatan->delete($id)) {
            $response = [
              'status' => true,
              'message' => 'Berhasil menghapus data'
            ];
          } else {
            $response = [
              'status' => false,
              'message' => 'Gagal menghapus data'
            ];
          }
        }
        $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
      }
     

     
}
