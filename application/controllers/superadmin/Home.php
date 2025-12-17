<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Home extends CI_controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');
        
        if ($this->session->userdata('superadmin') != TRUE) {
            redirect(base_url(''));
            exit;
        };

        $this->load->model('m_kegiatan');
        $this->load->model('m_pemasukan');
        $this->load->model('m_pengeluaran');
    }

    private $token_api = '123home'; // Ganti dengan token API yang valid

    // Fungsi untuk memanggil API dan mendapatkan data
    public function index($id='') {
        $api_url = base_url('superadmin/api/api_home/api_get_home'); // Ganti URL API sesuai rute API yang dibuat
        $token = $this->token_api;

        // Menggunakan curl untuk memanggil API
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // Mendekode respons API
        $data_home = json_decode($response, true);

        if (isset($data_home['status']) && $data_home['status'] === true) {
            $view = array(
                'judul' => 'Home',
                'aksi' => 'lihat',
                'data' => $data_home['data']['kegiatan'], // Data kegiatan dari API
                'total_pemasukan' => $data_home['data']['total_pemasukan'],
                'total_pengeluaran' => $data_home['data']['total_pengeluaran']
            );
        } else {
            $view = array(
                'judul' => 'Home',
                'aksi' => 'lihat',
                'data' => [], // Jika gagal mengambil data dari API
                'error_message' => $data_home['message'] ?? 'Gagal mengambil data dari API'
            );
        }

        // Memuat view superadmin/home dengan data dari API
        $this->load->view('superadmin/home', $view);
    }

    // Fungsi lain jika diperlukan
}
