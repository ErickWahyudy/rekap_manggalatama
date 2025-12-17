<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pengaturan extends CI_controller
{
    private $token_api = '123pengaturan'; // Ganti dengan token API yang valid

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');

        // Cek apakah superadmin
        if ($this->session->userdata('superadmin') != TRUE) {
            redirect(base_url(''));
            exit;
        }
        $this->load->model('m_pengaturan');
    }

    // Menampilkan pengaturan
    public function index($value = '')
    {
        $api_url = base_url('superadmin/api/api_pengaturan/api_get_view');
        $token = $this->token_api;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $data_pengaturan = json_decode($response, true);

        if (isset($data_pengaturan['status']) && $data_pengaturan['status'] === true) {
            $view = array(
                'judul' => 'Pengaturan',
                'data'  => $data_pengaturan['data'],
            );
        } else {
            $view = array(
                'judul' => 'Pengaturan',
                'data'  => [],
                'error_message' => $data_pengaturan['message'] ?? 'Gagal mengambil data dari API'
            );
        }

        $this->load->view('superadmin/pengaturan/form', $view);
    }

    // API edit judul
    public function api_edit($id = '')
    {
        $rules = array(
            array(
                'field' => 'nama_judul',
                'label' => 'Nama Judul',
                'rules' => 'required'
            ),
            array(
                'field' => 'meta_keywords',
                'label' => 'Meta Keywords',
                'rules' => 'required'
            ),
            array(
                'field' => 'meta_description',
                'label' => 'Meta Description',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {
            $response = [
                'status' => false,
                'message' => validation_errors()
            ];
        } else {
            $postData = [
                'nama_judul'       => $this->input->post('nama_judul'),
                'meta_keywords'    => $this->input->post('meta_keywords'),
                'meta_description' => $this->input->post('meta_description')
            ];

            $api_url = base_url('superadmin/api/api_pengaturan/api_edit/' . $id);
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
                    'message' => 'Berhasil mengubah data'
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => $data_response['message'] ?? 'Gagal mengubah data'
                ];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function api_upload($id = '')
    {
        // Mengambil file foto dari form POST
        $foto = $_FILES['foto'];
    
        if (empty($foto['name'])) {
            $response = [
                'status' => false,
                'message' => 'Tidak ada foto yang diupload'
            ];
        } else {
            $api_url = base_url('superadmin/api/api_pengaturan/api_upload/' . $id);
            $token = $this->token_api; 
    
            $curl = curl_init();
    
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
            ]);
    
            // Menambahkan file yang akan diupload
            $file_data = new CURLFile($foto['tmp_name'], $foto['type'], $foto['name']);
            $post_fields = [
                'foto' => $file_data,
            ];
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
            $response = curl_exec($curl);
            curl_close($curl);

            $data_response = json_decode($response, true);
    
            if (isset($data_response['status']) && $data_response['status'] === true) {
                $response = [
                    'status' => true,
                    'message' => 'Berhasil mengupload foto'
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => $data_response['message'] ?? 'Gagal mengupload foto'
                ];
            }
    
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    
    //API hapus data dari database dan folder
    public function api_hapus($id='')
    {
      if(empty($id)){
            $response = [
              'status' => false,
              'message' => 'Data kosong'
            ];
            } else {
                $api_url = base_url('superadmin/api/api_pengaturan/api_hapus/'.$id);
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
