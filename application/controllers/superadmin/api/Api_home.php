<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('m_kegiatan');
        $this->load->model('m_pemasukan');
        $this->load->model('m_pengeluaran');
        $this->load->library('form_validation');
    }

    private $valid_token_api = '123home'; // Token API yang valid

    private function verify_token() {
        $authHeader = $this->input->get_request_header('Authorization');
        if ($authHeader) {
            $arr = explode(" ", $authHeader);
            $token = $arr[1]; // Mengambil token setelah "Bearer"
            try {
                $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
                return $decoded;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
    

    public function api_get_home() {
        // Verifikasi token
        $this->verify_token();

        // Mengambil data kegiatan
        $kegiatan = $this->m_kegiatan->view()->result_array();

        // Mengambil total pemasukan dan pengeluaran per kegiatan
        $total_pemasukan = [];
        $total_pengeluaran = [];

        foreach ($kegiatan as $keg) {
            $id_kegiatan = $keg['id_kegiatan'];
            $pemasukan = $this->db->query("SELECT SUM(nominal) as total_pemasukan FROM tb_pemasukan WHERE id_kegiatan = ?", [$id_kegiatan])->row_array();
            $pengeluaran = $this->db->query("SELECT SUM(nominal) as total_pengeluaran FROM tb_pengeluaran WHERE id_kegiatan = ?", [$id_kegiatan])->row_array();

            $total_pemasukan[$id_kegiatan] = $pemasukan['total_pemasukan'] ?? 0;
            $total_pengeluaran[$id_kegiatan] = $pengeluaran['total_pengeluaran'] ?? 0;
        }

        // Menyiapkan response
        $response = [
            'status' => true,
            'message' => 'Data Home',
            'data' => [
                'kegiatan' => $kegiatan,
                'total_pemasukan' => $total_pemasukan,
                'total_pengeluaran' => $total_pengeluaran
            ]
        ];

        // Mengembalikan response dalam format JSON
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
    }
}
