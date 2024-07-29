<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pemasukan extends CI_controller
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
   $this->load->model('m_pemasukan');
    $this->load->model('m_kegiatan');
	}

    //Pemasukan
    public function index($value='')
    {
      $id_kegiatan = $this->m_kegiatan->view()->result_array();
      $id_kegiatan = array_column($id_kegiatan, 'id_kegiatan');
      
      $view = array(
        'judul'      => 'Data Pemasukan',
        'aksi'       => 'pemasukan',
        'data'       => $this->m_pemasukan->view($id_kegiatan)->result_array(),
        'kegiatan'   => $this->m_kegiatan->view()->result_array()
      );

      $this->load->view('superadmin/keuangan/pemasukan', $view);
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
     private function id_pemasukan_urut($value='')
     {
     $this->m_pemasukan->id_urut();
     $query   = $this->db->get();
     $data    = $query->row_array();
     $id      = $data['id_pemasukan'];
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


  //API add 
  public function api_add($value='')
  {
    $rules = array(
      array(
        'field' => 'jenis_pemasukan',
        'label' => 'Jenis Pemasukan',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Jenis Pemasukan tidak boleh kosong',
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
        'field' => 'tgl_pemasukan',
        'label' => 'Tanggal Pemasukan',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Tanggal Pemasukan tidak boleh kosong',
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
        'id_pemasukan'       =>$this->id_pemasukan_urut(),
        'jenis_pemasukan'    =>$this->input->post('jenis_pemasukan'),
        'nominal'            =>$this->input->post('nominal'),
        'tgl_pemasukan'      =>$this->input->post('tgl_pemasukan'),
        'id_kegiatan'        =>$this->input->post('id_kegiatan'),
        
      ];
      if ($this->m_pemasukan->add($SQLinsert)) {
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

    // API upload foto ke database dan folder
    public function api_upload($id='')
    {
        if (empty($_FILES['foto']['name'])) {
            $data = [
                'status'  => 'error',
                'message' => 'Tidak Ada File Yang Diupload',
            ];
        } else {
            $fileName = $this->berkas($id);
            if ($fileName) {
                $SQLupdate = [
                    'bukti_transfer' => $fileName
                ];
                if ($this->m_pemasukan->update($id, $SQLupdate)) {
                    $data = [
                        'status'  => 'success',
                        'message' => 'Berhasil Upload File',
                    ];
                } else {
                    $data = [
                        'status'  => 'error',
                        'message' => 'Gagal Upload File',
                    ];
                }
            } else {
                $data = [
                    'status'  => 'error',
                    'message' => 'Gagal Upload File, Kesalahan dalam proses upload.',
                ];
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // Function to handle file upload
    private function berkas($id='')
    {
        if ($_FILES['foto']['name'] != '') {
            $config['upload_path']          = './themes/bukti_transfer/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
            $config['max_size']             = 10000;
            $config['max_width']            = 10000;
            $config['max_height']           = 10000;
            $config['file_name']            = 'bukti_tf_' . uniqid();

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('foto')) {
                $error = $this->upload->display_errors();
                log_message('error', 'File upload error: ' . $error);
                $this->session->set_flashdata('error', $error);
                redirect('superadmin/keuangan/pemasukan');
                return false;
            } else {
                $data = $this->upload->data();
                $this->compress($data['full_path'], $data['full_path'], 10);
                return $data['file_name'];
            }
        } else {
            return '';
        }
    }

    // Function to compress image
    private function compress($source, $destination, $quality)
    {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }

        imagejpeg($image, $destination, $quality);
        return $destination;
    }

      //API edit
      public function api_edit($id='', $SQLupdate='')
      {
        $rules = array(
          array(
        'field' => 'jenis_pemasukan',
        'label' => 'Jenis Pemasukan',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Jenis Pemasukan tidak boleh kosong',
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
        'field' => 'tgl_pemasukan',
        'label' => 'Tanggal Pemasukan',
        'rules' => 'required',
        'errors' => array(
            'required' => 'Tanggal Pemasukan tidak boleh kosong',
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
            'jenis_pemasukan'    =>$this->input->post('jenis_pemasukan'),
            'nominal'            =>$this->input->post('nominal'),
            'tgl_pemasukan'      =>$this->input->post('tgl_pemasukan'),
            'id_kegiatan'        =>$this->input->post('id_kegiatan'),
          ];
          if ($this->m_pemasukan->update($id, $SQLupdate)) {
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
        }else{
          if ($this->m_pemasukan->delete($id)) {
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
	
      //API hapus data dari database dan folder
      public function api_hapus_foto($id='')
      {
        if (empty($id)) {
          $response = [
            'status' => false,
            'message' => 'Tidak ada data'
          ];
        } else {
          $data = $this->m_pemasukan->view_id($id)->row_array();
          $file = $data['bukti_transfer'];
          unlink('./themes/bukti_transfer/' . $file);

          //SQL update
          $SQLupdate = [
            'bukti_transfer'    => ''
          ];
          if ($this->m_pemasukan->update($id, $SQLupdate)) {
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