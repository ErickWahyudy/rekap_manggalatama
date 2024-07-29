<?php
/**
 * PHP for Codeigniter
 *
 * @package        	CodeIgniter
 * @pengembang		Kassandra Production (https://kassandra.my.id)
 * @Author			@erikwahyudy
 * @version			3.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Home extends CI_controller
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
	 if($this->session->userdata('user') != TRUE){
    redirect(base_url(''));
     exit;
	};
	  $this->load->model('m_kegiatan');
	  $this->load->model('m_pemasukan');
	  $this->load->model('m_pengeluaran');
}

	public function index($id='')
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

		$view = array('judul'      		=>'Home',
						'aksi'      	=>'lihat',
						'total_pemasukan' => $total_pemasukan,
            			'total_pengeluaran' => $total_pengeluaran,
	  );

	 $this->load->view('user/home',$view);
	}
	
}