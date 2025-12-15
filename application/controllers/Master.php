<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

    public function index()
    {
        $this->load->view('page/dashboard');
    }

    public function user_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'user-list.inc';


        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'user_name' => $this->input->post('user_name'),
                'user_pwd' => $this->input->post('user_pwd'),
                'user_type' => 'Admin',
                'ref_id' => '0',
                'status' => $this->input->post('status')
            );

            $this->db->insert('user_login_info', $ins);
            redirect('user-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'user_name' => $this->input->post('user_name'),
                'user_pwd' => $this->input->post('user_pwd'),
                'user_type' => 'Admin',
                'ref_id' => '0',
                'status' => $this->input->post('status')
            );

            $this->db->where('user_id', $this->input->post('user_id'));
            $this->db->update('user_login_info', $upd);

            redirect('user-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->where('user_id !=', '1');
        $this->db->from('user_login_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('user-list') . '/' . $this->uri->segment(2, 0));
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
            SELECT *
            FROM user_login_info
            WHERE status != 'Delete'
            and user_id != '1'
            order by user_id desc 
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/user-list', $data);
    }
    public function employee_category_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'employee-category-list.inc';

        $data['title'] = 'Employee Category List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'category_name' => $this->input->post('category_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('employee_category_info', $ins);
            redirect('employee-category-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'category_name' => $this->input->post('category_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('employee_category_id', $this->input->post('employee_category_id'));
            $this->db->update('employee_category_info', $upd);

            redirect('employee-category-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('employee_category_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('employee-category-list') . '/' . $this->uri->segment(2, 0));
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
            SELECT a.*
            FROM employee_category_info a
            where a.status != 'Delete'
            order by a.employee_category_id desc           
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/employee-category-list', $data);
    }
    public function employee_skill_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'employee-skill-list.inc';

        $data['title'] = 'Employee Skill List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'employee_category_id' => $this->input->post('employee_category_id'),
                'skill_name' => $this->input->post('skill_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('employee_skill_info', $ins);
            redirect('employee-skill-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'employee_category_id' => $this->input->post('employee_category_id'),
                'skill_name' => $this->input->post('skill_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('employee_skill_id', $this->input->post('employee_skill_id'));
            $this->db->update('employee_skill_info', $upd);

            redirect('employee-skill-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('employee_skill_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('employee-skill-list') . '/' . $this->uri->segment(2, 0));
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

        //category_opt 
        $sql = "
            SELECT a.*
            FROM employee_category_info a
            where a.status != 'Delete'
                order by a.category_name asc           
          ";

        $query = $this->db->query($sql);
        $data['category_opt'] = array('' => 'Select Category');
        foreach ($query->result_array() as $row) {
            $data['category_opt'][$row['employee_category_id']] = $row['category_name'];
        }

        $sql = "
            SELECT
                a.employee_skill_id,
                a.skill_name,
                b.employee_category_id,
                b.category_name,
                 a.status
            FROM employee_skill_info a
            left join employee_category_info b on b.employee_category_id = a.employee_category_id and b.status != 'Delete'
            where a.status != 'Delete'
            order by a.employee_skill_id desc           
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/employee-skill-list', $data);
    }
    public function pincode_list()
    {

        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['s_url'] = 'pincode-list';

        // Handle Add operation
        if ($this->input->post('mode') == 'Add') {
            $ins = [
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'pincode' => $this->input->post('pincode'),
                'status' => $this->input->post('status'),
            ];
            $this->db->insert('crit_pincode_info', $ins);
            $this->session->set_flashdata('alert_success', 'Pincode list Successfully Added');
            redirect($data['s_url']);
        }

        // Handle Edit operation
        if ($this->input->post('mode') == 'Edit') {
            $upd = [
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'pincode' => $this->input->post('pincode'),
                'status' => $this->input->post('status'),
            ];
            $this->db->where('pincode_id', $this->input->post('pincode_id'));
            $this->db->update('crit_pincode_info', $upd);
            $this->session->set_flashdata('alert_success', 'Pincode list Name Successfully Updated');
            redirect($data['s_url'] . '/' . $this->uri->segment(2, 0));
        }

        $data['js'] = 'pincode-list.inc';
        $data['title'] = 'Pincode List';

        // Search filters (state and district)
        if ($this->input->post('srch_state_id') !== null) {
            $data['srch_state_id'] = $srch_state_id = $this->input->post('srch_state_id');
            $this->session->set_userdata('srch_state_id', $srch_state_id);
        } elseif ($this->session->userdata('srch_state_id')) {
            $data['srch_state_id'] = $srch_state_id = $this->session->userdata('srch_state_id');
        } else {
            $data['srch_state_id'] = $srch_state_id = 'Tamil Nadu';
        }

        if ($this->input->post('srch_district_id') !== null) {
            $data['srch_district_id'] = $srch_district_id = $this->input->post('srch_district_id');
            $this->session->set_userdata('srch_district_id', $srch_district_id);
        } elseif ($this->session->userdata('srch_district_id')) {
            $data['srch_district_id'] = $srch_district_id = $this->session->userdata('srch_district_id');
        } else {
            $data['srch_district_id'] = $srch_district_id = 'Coimbatore';
        }

        // WHERE conditions
        $where = "1";
        if (!empty($srch_state_id)) {
            $where .= " AND a.state_name = " . $this->db->escape($srch_state_id);
        }
        if (!empty($srch_district_id)) {
            $where .= " AND a.district_name = " . $this->db->escape($srch_district_id);
        }

        // Pagination setup
        $this->load->library('pagination');
        $this->db->from('crit_pincode_info AS a');
        $this->db->where('status !=', 'Delete');
        $this->db->where($where);
        $data['total_records'] = $cnt = $this->db->count_all_results();

        $data['sno'] = $this->uri->segment(2, 0);
        $config['base_url'] = site_url($data['s_url']);
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        $config['attributes'] = ['class' => 'page-link'];
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

        // Fetch records
        $sql = "
            SELECT a.* 
            FROM crit_pincode_info AS a 
            WHERE a.status = 'Active' 
            AND a.status != 'Delete' 
            AND $where
            GROUP BY a.state_name, a.district_name, a.pincode 
            ORDER BY a.pincode_id desc 
            LIMIT " . intval($this->uri->segment(2, 0)) . ", " . intval($config['per_page']) . "
        ";
        $data['record_list'] = $this->db->query($sql)->result_array();

        // Dropdown - States
        $data['state_opt'] = ['' => 'All'];
        $states = $this->db->query("
            SELECT state_name 
            FROM crit_pincode_info 
            WHERE status = 'Active'
            GROUP BY state_name
            ORDER BY state_name ASC
        ")->result_array();

        foreach ($states as $row) {
            $data['state_opt'][$row['state_name']] = $row['state_name'];
        }

        // Dropdown - Districts
        $data['district_opt'] = ['' => 'All'];
        if (!empty($srch_state_id)) {
            $districts = $this->db->query("
                SELECT district_name 
                FROM crit_pincode_info 
                WHERE status = 'Active' 
                AND state_name = " . $this->db->escape($srch_state_id) . "
                GROUP BY district_name 
                ORDER BY district_name ASC
            ")->result_array();

            foreach ($districts as $row) {
                $data['district_opt'][$row['district_name']] = $row['district_name'];
            }
        }

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('page/master/pincode-list', $data);
    }
    public function sports_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'sports-list.inc';

        $data['title'] = 'Sports List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'sports_name' => $this->input->post('sports_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('sports_list_info', $ins);
            redirect('sports-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'sports_name' => $this->input->post('sports_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('sports_list_id', $this->input->post('sports_list_id'));
            $this->db->update('sports_list_info', $upd);

            redirect('sports-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('sports_list_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('sports-list');
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
            SELECT
            a.*
            FROM sports_list_info a
            where a.status != 'Delete'
            order by a.sports_list_id desc           
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/sports-list', $data);
    }
    public function health_issues_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'health-issues-list.inc';

        $data['title'] = 'Health Issues List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'health_issues_name' => $this->input->post('health_issues_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('health_issues_info', $ins);
            redirect('health-issues-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'health_issues_name' => $this->input->post('health_issues_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('health_issues_id', $this->input->post('health_issues_id'));
            $this->db->update('health_issues_info', $upd);

            redirect('health-issues-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('health_issues_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('health-issues-list');
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
            SELECT
            a.*
            FROM health_issues_info a
            where a.status != 'Delete'
            order by a.health_issues_id desc     
           limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "          
         ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/health-issues-list', $data);
    }

    public function hobbies_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'hobbies-list.inc';

        $data['title'] = 'Hobbies List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'hobbies_name' => $this->input->post('hobbies_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('hobbies_list_info', $ins);
            redirect('hobbies-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'hobbies_name' => $this->input->post('hobbies_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('hobbies_list_id', $this->input->post('hobbies_list_id'));
            $this->db->update('hobbies_list_info', $upd);

            redirect('hobbies-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('hobbies_list_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('hobbies-list');
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
            SELECT
            a.*
            FROM hobbies_list_info a
            where a.status != 'Delete'
            order by a.hobbies_list_id desc     
           limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "          
         ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/hobbies-list', $data);
    }
    public function business_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'business-list.inc';

        $data['title'] = 'Business List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'business_name' => $this->input->post('business_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('business_info', $ins);
            redirect('business-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'business_name' => $this->input->post('business_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('business_id', $this->input->post('business_id'));
            $this->db->update('business_info', $upd);

            redirect('business-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('business_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('business-list');
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
            SELECT
            a.*
            FROM business_info a
            where a.status != 'Delete'
            order by a.business_id desc     
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "          
         ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/business-list', $data);
    }

    public function talent_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'talent-list.inc';

        $data['title'] = 'Talent List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'talent_name' => $this->input->post('talent_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('talent_info', $ins);
            redirect('talent-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'talent_name' => $this->input->post('talent_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('talent_id', $this->input->post('talent_id'));
            $this->db->update('talent_info', $upd);

            redirect('talent-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('talent_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('talent-list');
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
            SELECT
            a.*
            FROM talent_info a
            where a.status != 'Delete'
            order by a.talent_id desc     
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "          
         ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/talent-list', $data);
    }
    public function disability_list()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['js'] = 'disability-list.inc';

        $data['title'] = 'Disability List';

        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'disability_name' => $this->input->post('disability_name'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('disability_info', $ins);
            redirect('disability-list/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'disability_name' => $this->input->post('disability_name'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('disability_id', $this->input->post('disability_id'));
            $this->db->update('disability_info', $upd);

            redirect('disability-list/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete');
        $this->db->from('disability_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('disability-list');
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
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
            SELECT
            a.*
            FROM disability_info a
            where a.status != 'Delete'
            order by a.disability_id desc     
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "          
         ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/disability-list', $data);
    }
    public function supervisor_terms()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin'
            && $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }


        $data['title'] = 'supervisor terms';
        $data['js'] = 'supervisor-terms.inc';


        if ($this->input->post('mode') == 'Add') {
            $ins = array(
                'terms_accepted' => $this->input->post('terms_accepted'),
                'nda_agreement' => $this->input->post('nda_agreement'),
                'background_check' =>  $this->input->post('background_check'), 
                'status' =>'Active',
                'created_at' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('supervisor_terms_info', $ins);
            redirect('supervisor-terms/');
        }

        if ($this->input->post('mode') == 'Edit') {
            $upd = array(
                'terms_accepted' => $this->input->post('terms_accepted'),
                'nda_agreement' => $this->input->post('nda_agreement'),
                'background_check' =>  $this->input->post('background_check'), 
                'status' => $this->input->post('status'), 
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->db->where('supervisor_terms_id', $this->input->post('supervisor_terms_id'));
            $this->db->update('supervisor_terms_info', $upd);

             redirect('supervisor-terms/');
        }


        $this->load->library('pagination');

        $this->db->where('status !=', 'Delete'); 
        $this->db->from('supervisor_terms_info');
        $data['total_records'] = $cnt = $this->db->count_all_results();


        $data['sno'] = $this->uri->segment(2);

        $config['base_url'] = site_url('supervisor-terms');
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
            SELECT *
            FROM supervisor_terms_info
            WHERE status != 'Delete' 
            order by supervisor_terms_id desc 
            limit " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "                
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }



        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('page/master/supervisor-terms', $data);
    }
}