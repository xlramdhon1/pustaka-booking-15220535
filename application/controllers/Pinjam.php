<?php if (!defined('BASEPATH')) exit('No Direct Script Access Allowed');
class Pinjam extends CI_Controller
{
public function __construct()
{
parent::__construct();
cek_login();
cek_user();
}
public function index()
{
}
public function daftarBooking()
{
$data['judul'] = "Daftar Booking";
$data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
$data['pinjam'] = $this->db->query("select*from booking")->result_array();
$this->load->view('templates/header', $data);
$this->load->view('templates/sidebar', $data);
$this->load->view('templates/topbar', $data);
$this->load->view('booking/daftar-booking', $data);
$this->load->view('templates/footer');
}
}