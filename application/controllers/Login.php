<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        // Already logged in → dashboard
        if ($this->session->userdata(SESS_HD . 'logged_in')) {
            redirect('dash');
        }

        $data['js'] = '';
        $data['login'] = true;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'User Name', 'required|trim');
        $this->form_validation->set_rules('user_pwd', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('page/login', $data);

        } else {

            $sql = "
            SELECT 
                a.user_id as id,
                a.user_name,
                a.user_type
            FROM user_login_info a
            WHERE a.user_name = ?
              AND a.user_pwd = ?
              AND a.status = 'Active'
        ";

            $query = $this->db->query($sql, [
                $this->input->post('user_name'),
                $this->input->post('user_pwd')
            ]);

            $row = $query->row();

            if ($row) {

                // Session
                $this->session->set_userdata([
                    SESS_HD . 'user_id' => $row->id,
                    SESS_HD . 'user_name' => $row->user_name,
                    SESS_HD . 'user_type' => $row->user_type,
                    SESS_HD . 'logged_in' => TRUE,
                    SESS_HD . 'login_time' => time()
                ]);

                // Flash success
                $this->session->set_flashdata(
                    'login_success',
                    'Welcome back, ' . ucfirst($row->user_name) . '!'
                );

                // ⚠️ IMPORTANT: Load view (NO redirect)
                $this->load->view('page/login', $data);

            } else {

                $data['msg'] = 'Invalid username or password';
                $this->load->view('page/login', $data);
            }
        }
    }




    public function logout($reason = 'manual')
    {


        // Flash message
        if ($reason === 'timeout') {
            $this->session->set_flashdata('session_expired', 'Your session has expired due to inactivity. Please log in again.');
        } else {
            $this->session->set_flashdata('logout_msg', 'You have been logged out successfully.');
        }

        $this->session->sess_destroy();
        redirect('');
    }


    public function user_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if ($this->session->userdata(SESS_HD . 'level') != 'Admin') {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }

        $data['js'] = 'user.inc';


        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'user_name' => $this->input->post('user_name'),
                'user_pwd' => $this->input->post('user_pwd'),
                'level' => $this->input->post('level'),
                'edit_flg' => $this->input->post('edit_flg'),
                'status' => $this->input->post('status')
            );

            $this->db->insert('user_login', $ins);
            redirect('user-list');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'user_name' => $this->input->post('user_name'),
                'user_pwd' => $this->input->post('user_pwd'),
                'level' => $this->input->post('level'),
                'edit_flg' => $this->input->post('edit_flg'),
                'status' => $this->input->post('status')
            );

            $this->db->where('user_id', $this->input->post('user_id'));
            $this->db->update('user_login', $upd);

            redirect('user-list/' . $this->uri->segment(2, 0));
        }


        $this->load->library('pagination');

        $this->db->where('status != ', 'Delete');
        $this->db->from('user_login');
        $data['total_records'] = $cnt = $this->db->count_all_results();

        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('user-list/'), '/' . $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = "Prev";
        $config['next_link'] = "Next";
        $this->pagination->initialize($config);

        $sql = "
                select 
                a.*             
                from user_login as a  
                where a.status != 'Delete'
                order by a.status , a.level,  a.user_name
                limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }


        $data['user_type_opt'] = array('Admin' => 'Admin', 'User' => 'User');



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/user-list', $data);
    }

    public function change_password()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        $data['js'] = 'change-password.inc';

        $data['user_name'] = $this->session->userdata(SESS_HD . 'user_name');
        $data['login_name'] = $this->session->userdata(SESS_HD . 'user_name');
        $data['user_id'] = $this->session->userdata(SESS_HD . 'user_id');

        $this->load->view('page/change-password', $data);
    }

}