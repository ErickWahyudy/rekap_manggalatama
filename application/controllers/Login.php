<?php
/* halaman login utama 
   author by Kassandra Production
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Login_m');
        $this->load->model('m_pengaturan');
    }

    public function index()
    {
        if ($this->input->method() == 'post') {
            $nama = $this->input->post('email');
            $no_hp = $this->input->post('email');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Cek data login
            $superadmin = $this->Login_m->Superadmin($nama, $no_hp, $email, md5($password));
            $admin = $this->Login_m->Admin($nama, $no_hp, $email, md5($password));
            $user = $this->Login_m->User($nama, $no_hp, $email, md5($password));

            if ($superadmin->num_rows() > 0) {
                $DataSuperAdmin = $superadmin->row_array();
                $sessionSuperAdmin = array(
                    'superadmin'        => TRUE,
                    'id_pengguna'       => $DataSuperAdmin['id_pengguna'],
                    'email'             => $DataSuperAdmin['email'],
                    'password'          => $DataSuperAdmin['password'],
                    'nama'              => $DataSuperAdmin['nama'],
                    'no_hp'             => $DataSuperAdmin['no_hp'],
                    'keterangan'        => $DataSuperAdmin['keterangan'],
                    'level'             => $DataSuperAdmin['id_level'],
                );
                $this->session->set_userdata($sessionSuperAdmin);
                $response = array(
                    'status' => 'success', 
                    'redirect' => base_url('superadmin/home')
                );

            } elseif ($admin->num_rows() > 0) {
                $DataAdmin = $admin->row_array();
                $sessionAdmin = array(
                    'admin'             => TRUE,
                    'id_pengguna'       => $DataAdmin['id_pengguna'],
                    'email'             => $DataAdmin['email'],
                    'password'          => $DataAdmin['password'],
                    'nama'              => $DataAdmin['nama'],
                    'no_hp'             => $DataAdmin['no_hp'],
                    'keterangan'        => $DataAdmin['keterangan'],
                    'level'             => $DataAdmin['id_level'],
                );
                $this->session->set_userdata($sessionAdmin);
                $response = array(
                    'status' => 'success', 
                    'redirect' => base_url('admin/home')
                );

            } elseif ($user->num_rows() > 0) {
                $DataUser = $user->row_array();
                $sessionUser = array(
                    'user'             => TRUE,
                    'id_pengguna'       => $DataUser['id_pengguna'],
                    'email'             => $DataUser['email'],
                    'password'          => $DataUser['password'],
                    'nama'              => $DataUser['nama'],
                    'no_hp'             => $DataUser['no_hp'],
                    'keterangan'        => $DataUser['keterangan'],
                    'level'             => $DataUser['id_level'],
                );
                $this->session->set_userdata($sessionUser);
                $response = array(
                    'status' => 'success', 
                    'redirect' => base_url('user/home')
                );
            } else {
                // Periksa apakah email/username benar
                $isEmailValid = $this->Login_m->IsEmailValidPengguna($email);

                if ($isEmailValid->num_rows() > 0) {
                    // Jika email benar, maka password salah
                    $response = array('status' => 'error', 'message' => 'Password Salah');
                } else {
                    // Jika email salah, maka email tidak terdaftar
                    $response = array('status' => 'error', 'message' => 'Email Salah atau Tidak Terdaftar');
                }
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

        } else {
            $data = $this->m_pengaturan->view()->row_array();
            $x = array(
                'judul' =>'Login Aplikasi',
                'nama_judul' => $data['nama_judul'],
                'meta_keywords' => $data['meta_keywords'],
                'meta_description' => $data['meta_description'],
                'background' => $data['background'],
            );

            $this->load->view('login', $x);
        }
    }
}
