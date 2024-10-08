<?php
class Member extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_login();
    }

    private function _login()
    {
        $email = htmlspecialchars($this->input->post('email', true));
        $password = $this->input->post('password', true);
        $user = $this->ModelUser->cekData(['email' => $email])->row_array();

        // Jika usernya ada
        if ($user) {
            // Jika user sudah aktif
            if ($user['is_active'] == 1) {
                // Cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'id_user' => $user['id'],
                        'nama' => $user['nama']
                    ];
                    $this->session->set_userdata($data);
                    redirect('home');
                } else {
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                    redirect('home');
                }
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                redirect('home');
            }
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
            redirect('home');
        }
    }

    public function daftar()
    {
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [
            'required' => 'Nama Belum diisi!!'
        ]);
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required', [
            'required' => 'Alamat Belum diisi!!'
        ]);
        $this->form_validation->set_rules('email', 'Alamat Email', 'required|trim|valid_email|is_unique[user.email]', [
            'valid_email' => 'Email Tidak Benar!!',
            'required' => 'Email Belum diisi!!',
            'is_unique' => 'Email Sudah Terdaftar!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password Tidak Sama!!',
            'min_length' => 'Password Terlalu Pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            // Jika validasi gagal, redirect kembali
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Pendaftaran gagal, mohon periksa input Anda!</div>');
            redirect(base_url());
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'alamat' => $this->input->post('alamat', true),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'tanggal_input' => time()
            ];

            $this->ModelUser->simpanData($data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat!! akun anggota anda sudah dibuat.</div>');
            redirect(base_url());
        }
    }
    public function myProfil()
{
$data['judul'] = 'Profil Saya';
$user = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
foreach ($user as $a) {
$data = [
'image' => $user['image'],

'user' => $user['nama'],
'email' => $user['email'],
'tanggal_input' => $user['tanggal_input'],
];
}
$this->load->view('templates/templates-user/header', $data);
$this->load->view('member/index', $data);
$this->load->view('templates/templates-user/modal');
$this->load->view('templates/templates-user/footer', $data);
}
}
