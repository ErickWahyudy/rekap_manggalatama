<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Api_pengaturan extends CI_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('m_pengaturan');
    }

    private $token_api = '123pengaturan'; // Token API yang valid

    // Function to verify_token API token
    private function verify_token()
    {
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

    // Judul
    public function api_get_view($value = '')
    {
        if ($this->verify_token()) {
            $response = [];

            $data_kegiatan = $this->m_pengaturan->view()->result_array();

            $response = [
                'status' => true,
                'message' => 'Data Kegiatan',
                'data' => $data_kegiatan
            ];

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }


    // API edit judul
    public function api_edit($id = '')
    {
        if ($this->verify_token() === true) {

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
                $response = ['status' => false, 'message' => 'Tidak ada data'];
            } else {
                $SQLupdate = [
                    'nama_judul' => $this->input->post('nama_judul'),
                    'meta_keywords' => $this->input->post('meta_keywords'),
                    'meta_description' => $this->input->post('meta_description')
                ];
                if ($this->m_pengaturan->update($id, $SQLupdate)) {
                    $response = ['status' => true, 'message' => 'Berhasil mengubah data'];
                } else {
                    $response = ['status' => false, 'message' => 'Gagal mengubah data'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    // Fungsi untuk mengompres ukuran gambar
    private function compress($source, $destination, $quality)
    {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);
        imagejpeg($image, $destination, $quality);
        return $destination;
    }

    // Fungsi untuk upload dan kompres berkas
    private function berkas($id = '')
    {
        if ($_FILES['foto']['name'] != '') {
            $config['upload_path']          = './themes/foto_background/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
            $config['max_size']             = 10000;
            $config['max_width']            = 10000;
            $config['max_height']           = 10000;
            $config['file_name']            = 'background_' . uniqid();
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('foto')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
                redirect('superadmin/judul');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $this->compress($data['upload_data']['full_path'], $data['upload_data']['full_path'], 90);
                return $data['upload_data']['file_name'];
            }
        } else {
            return '';
        }
    }

    // API untuk upload foto ke database dan folder
    public function api_upload($id = '', $SQLupdate = '')
    {
        if ($this->verify_token() === true) {
            $response = [];
            if (empty($id)) {
                $response = [
                    'status' => false,
                    'message' => 'Tidak ada data'
                ];
            } else {
                $file = $this->berkas($id);
                $SQLupdate = [
                    'background' => $file
                ];
                if ($this->m_pengaturan->update($id, $SQLupdate)) {
                    $response = [
                        'status' => true,
                        'message' => 'Berhasil mengupload data'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Gagal mengupload data'
                    ];
                }
            }
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    // API hapus data dari database dan folder
    public function api_hapus($id = '', $SQLupdate = '')
    {
        if ($this->verify_token() === true) {
            $response = [];

            if (empty($id)) {
                $response = [
                    'status' => false,
                    'message' => 'Tidak ada data'
                ];
            } else {
                $data = $this->m_pengaturan->view_id($id)->row_array();
                $file = $data['background'];
                unlink('./themes/foto_background/' . $file);

                //SQL update
                $SQLupdate = [
                    'background'    => ''
                ];
                if ($this->m_pengaturan->update($id, $SQLupdate)) {
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
}
