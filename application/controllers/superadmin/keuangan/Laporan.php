<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Laporan extends CI_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('form_validation');

        if($this->session->userdata('superadmin') != TRUE){
            redirect(base_url(''));
            exit;
        }
        $this->load->model('m_pemasukan');
        $this->load->model('m_pengeluaran');
        $this->load->model('m_kegiatan');
    }

    // Pemasukan
    public function index($value='')
    {
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

        $view = array(
            'judul' => 'Data Pemasukan',
            'aksi' => 'pemasukan',
            'data' => $kegiatan,
            'kegiatan' => $kegiatan,
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran,
        );

        $this->load->view('superadmin/keuangan/laporan', $view);
    }

    public function detail_pengeluaran($id_kegiatan)
    {
        $data = $this->m_pengeluaran->view_by_id_kegiatan($id_kegiatan)->result_array();
        $kegiatan = $this->m_kegiatan->view_id($id_kegiatan)->row_array();
        $view = array(
            'judul' => 'Detail Pengeluaran',
            'aksi' => 'pengeluaran',
            'data' => $data,
            'kegiatan' => $kegiatan,
        );

        $this->load->view('superadmin/keuangan/detail_pengeluaran', $view);
    }

    public function detail_pemasukan($id_kegiatan)
    {
        $data = $this->m_pemasukan->view_by_id_kegiatan($id_kegiatan)->result_array();
        $kegiatan = $this->m_kegiatan->view_id($id_kegiatan)->row_array();
        $view = array(
            'judul' => 'Detail Pemasukan',
            'aksi' => 'pemasukan',
            'data' => $data,
            'kegiatan' => $kegiatan,
        );

        $this->load->view('superadmin/keuangan/detail_pemasukan', $view);
    }
}
