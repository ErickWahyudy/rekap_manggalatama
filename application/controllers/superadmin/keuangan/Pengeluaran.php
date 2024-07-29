<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pengeluaran extends CI_controller
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
   $this->load->model('m_pengeluaran');
    $this->load->model('m_kegiatan');
	}

    //Pengelauaran
    public function index($value='')
    {
      $id_kegiatan = $this->m_kegiatan->view()->result_array();
      $id_kegiatan = array_column($id_kegiatan, 'id_kegiatan');

      $view = array(
                    'judul'      =>'Data Pengeluaran',
                    'aksi'      =>'pengeluaran',
                    'data'      =>$this->m_pengeluaran->view($id_kegiatan)->result_array(),
                    'kegiatan'  =>$this->m_kegiatan->view()->result_array(),
                  );

      $this->load->view('superadmin/keuangan/pengeluaran',$view);
    }

    private function acak_id($panjang)
    {
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $string = '';
        for ($i = 0; $i < $panjang; $i++) {
            $pos = rand(0, strlen($karakter) - 1);
            $string .= $karakter{$pos};
        }
        return $string;
    }
    
     //mengambil id kegiatan urut terakhir
     private function id_pengeluaran_urut($value='')
     {
     $this->m_pengeluaran->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_pengeluaran'];
     $karakter= $this->acak_id(6);
     $urut    = substr($id, 1, 3);
     $tambah  = (int) $urut + 1;
     
     if (strlen($tambah) == 1){
     $newID = "P"."00".$tambah.$karakter;
         }else if (strlen($tambah) == 2){
         $newID = "P"."0".$tambah.$karakter;
             }else (strlen($tambah) == 3){
             $newID = "K".$tambah.$karakter
             };
         return $newID;
     }

    //mengompres ukuran gambar
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

    private function berkas($id='')
  {
    if ($_FILES['foto']['name'] != '') {
    $config['upload_path']          = './themes/bukti_nota/';
    $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
    $config['max_size']             = 10000;
    $config['max_width']            = 10000;
    $config['max_height']           = 10000;
    $config['file_name']            = 'header_' . uniqid();
    $this->load->library('upload', $config);
    if (!$this->upload->do_upload('foto')) {
      $error = array('error' => $this->upload->display_errors());
      $this->session->set_flashdata('error', $error['error']);
      redirect('superadmin/kegiatan/pengeluaran');
    } else {
      $data = array('upload_data' => $this->upload->data());
      $this->compress($data['upload_data']['full_path'], $data['upload_data']['full_path'], 10);
      return $data['upload_data']['file_name'];
    }
  } else {
    return '';
  }
  }

  //API add 
  public function api_add($value='')
  {
    $rules = array(
      array(
        'field' => 'jenis_pengeluaran',
        'label' => 'Jenis pengeluaran',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Jenis pengeluaran tidak boleh kosong',
        ),
      ),

      array(
        'field' => 'nominal',
        'label' => 'Nominal',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Nominal tidak boleh kosong',
        ),
      ),

      array(
        'field' => 'tgl_pengeluaran',
        'label' => 'Tanggal pengeluaran',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Tanggal pengeluaran tidak boleh kosong',
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
        'id_pengeluaran'       =>$this->id_pengeluaran_urut(),
        'jenis_pengeluaran'    =>$this->input->post('jenis_pengeluaran'),
        'nominal'              =>$this->input->post('nominal'),
        'tgl_pengeluaran'      =>$this->input->post('tgl_pengeluaran'),
        'id_kegiatan'          =>$this->input->post('id_kegiatan'),
        'bukti_nota'           =>$this->berkas(),
        
      ];
      if ($this->m_pengeluaran->add($SQLinsert)) {
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

      //API edit
      public function api_edit($id='', $SQLupdate='')
      {
        $rules = array(
          array(
        'field' => 'jenis_pengeluaran',
        'label' => 'Jenis pengeluaran',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Jenis pengeluaran tidak boleh kosong',
        ),
      ),

      array(
        'field' => 'nominal',
        'label' => 'Nominal',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Nominal tidak boleh kosong',
        ),
      ),

      array(
        'field' => 'tgl_pengeluaran',
        'label' => 'Tanggal pengeluaran',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Tanggal pengeluaran tidak boleh kosong',
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
            'jenis_pengeluaran'    =>$this->input->post('jenis_pengeluaran'),
            'nominal'            =>$this->input->post('nominal'),
            'tgl_pengeluaran'      =>$this->input->post('tgl_pengeluaran'),
            'id_kegiatan'        =>$this->input->post('id_kegiatan'),
          ];
          if ($this->m_pengeluaran->update($id, $SQLupdate)) {
            $response = [
              'status' => true,
              'message' => 'Berhasil mengubah data'
            ];
          } else {
            $response = [
              'status' => false,
              'message' => 'Gagal mengubah data'
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
        // Mendapatkan nama file foto dari database
        $data = $this->m_pengeluaran->view_id($id)->row_array();
        $file = $data['bukti_nota'];

        // Menghapus file dari folder
        if ($file && file_exists('./themes/bukti_nota/' . $file)) {
            unlink('./themes/bukti_nota/' . $file);
        }

        // Menghapus data dari database
        if ($this->m_pengeluaran->delete($id)) {
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