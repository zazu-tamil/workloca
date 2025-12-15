<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->model('User_model');
    $this->load->helper(['url','form']);
    $this->load->library('form_validation');
  }

  public function index(){
    redirect('auth/login');
  }

  public function login(){
    if($this->session->userdata('user_id')){
      redirect('dashboard');
    }

    $data = [];
    if ($this->input->post()) {
      $this->form_validation->set_rules('email','Email','required|valid_email');
      $this->form_validation->set_rules('password','Password','required');
      if ($this->form_validation->run() === TRUE) {
        $email = $this->input->post('email', true);
        $pass = $this->input->post('password', true);
        $user = $this->User_model->get_by_email($email);
        if ($user && password_verify($pass, $user->password) && $user->is_active) {
          $this->session->set_userdata([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'logged_in' => true
          ]);
          redirect('dashboard');
        } else {
          $data['error'] = 'Invalid credentials or inactive user.';
        }
      }
    }

    echo password_hash('Admin@123', PASSWORD_DEFAULT); 
    
    $this->load->view('templates/header');
    $this->load->view('auth/login', $data);
    $this->load->view('templates/footer');
  }

  public function logout(){
    $this->session->sess_destroy();
    redirect('auth/login');
  }
}
