<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Laporan_keuangan extends CI_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');

        if($this->session->userdata('user') != TRUE){
            redirect(base_url(''));
            exit;
        }
        $this->load->model('m_pemasukan');
        $this->load->model('m_pengeluaran');
        $this->load->model('M_kegiatan');
    }

    // Pemasukan
    public function index($value='')
    {
        // Mengambil data kegiatan
        $kegiatan = $this->db->query("SELECT * FROM tb_kegiatan")->result_array();

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

        $view = array(
            'judul' => 'Data Pemasukan',
            'aksi' => 'pemasukan',
            'data' => $kegiatan,
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran,
        );

        $this->load->view('user/keuangan/laporan', $view);
    }
}
