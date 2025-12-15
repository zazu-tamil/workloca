<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
{

    public function index()
    {
        $this->load->view('page/dashboard');
    }



    public function company_add()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if ($this->session->userdata(SESS_HD . 'user_type') != 'Admin' && $this->session->userdata(SESS_HD . 'user_type') != 'Staff') {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }

        $data['js'] = 'company/company-add.inc';
        $data['title'] = 'Company Add';

        if ($this->input->post('mode') == 'Add') {

            $upload_path_logo = 'company-logo/';
            if (!is_dir($upload_path_logo)) {
                mkdir($upload_path_logo, 0777, true);
            }

            $config1['upload_path'] = $upload_path_logo;
            $config1['allowed_types'] = 'jpg|jpeg|png|gif|bmp|tiff|pdf';
            $config1['max_size'] = 10240;
            $config1['encrypt_name'] = true;

            $this->load->library('upload');
            $this->upload->initialize($config1);

            $logo = '';
            if (!empty($_FILES['photo']['name'])) {
                if ($this->upload->do_upload('photo')) {
                    $photo = $this->upload->data('file_name');
                }
            }

            $pref_location = $this->input->post('area');

            $ins = array(
                'company_name' => $this->input->post('company_name'),
                'mobile' => $this->input->post('mobile'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'email' => $this->input->post('email'),
                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'area' => implode(',', $pref_location),
                'address' => $this->input->post('address'),
                'photo' => $upload_path_logo . $logo,   // set after upload
                'gst_number' => $this->input->post('gst_number'),
                'pan_number' => $this->input->post('pan_number'),
                'website' => $this->input->post('website'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('company_info', $ins);
            redirect('company-list');

        }

        $sql = "
            SELECT 
            a.employee_skill_id,
            a.skill_name,
            b.category_name
            FROM employee_skill_info a
            left join employee_category_info as b on a.employee_category_id = b.employee_category_id 
            where a.status != 'Delete'
            order by a.skill_name asc        
        ";

        $query = $this->db->query($sql);
        $data['employee_skill_opt'] = array('' => 'Select Skill');
        foreach ($query->result_array() as $row) {
            $data['employee_skill_opt'][$row['employee_skill_id']] = $row['skill_name'] . ' (' . $row['category_name'] . ')';
        }
        $sql = "
            SELECT 
                a.*
            FROM industry_info a 
            where a.status != 'Delete'
            order by a.industry_name asc        
        ";

        $query = $this->db->query($sql);
        $data['industry_opt'] = array('' => 'Select Industry');
        foreach ($query->result_array() as $row) {
            $data['industry_opt'][$row['industry_id']] = $row['industry_name'];
        }



        $data['pincode_opt'] = ['' => 'Select'];
        $data['district_opt'] = ['' => 'Select'];
        $data['area_opt'] = ['' => 'Select'];

        $sql = "
            SELECT 
                state_name
            FROM crit_pincode_info
            WHERE status = 'Active'
            GROUP BY state_name
            ORDER BY state_name ASC";
        $query = $this->db->query($sql);
        $data['state_opt'] = ['' => 'Select'];
        foreach ($query->result_array() as $row) {
            $data['state_opt'][$row['state_name']] = $row['state_name'];
        }

        $this->load->view('page/company/company-add', $data);
    }

    public function company_edit($company_id)
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if ($this->session->userdata(SESS_HD . 'user_type') != 'Admin' && $this->session->userdata(SESS_HD . 'user_type') != 'Staff') {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }

        $data['js'] = 'company/company-edit.inc';
        $data['title'] = 'Company Edit';

        if ($this->input->post('mode') == 'Edit') {

            $upload_path_photos = 'company-logo';
            if (!is_dir($upload_path_photos)) {
                mkdir($upload_path_photos, 0777, true);
            }

            $config['upload_path'] = $upload_path_photos;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);


            $pref_location = $this->input->post('area');

            $upd = array(
                'company_name' => $this->input->post('company_name'),
                'mobile' => $this->input->post('mobile'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'email' => $this->input->post('email'),
                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'area' => implode(',', $pref_location),
                'address' => $this->input->post('address'),
                'gst_number' => $this->input->post('gst_number'),
                'pan_number' => $this->input->post('pan_number'),
                'website' => $this->input->post('website'),
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if (!empty($_FILES['photo']['name'])) {
                if ($this->upload->do_upload('photo')) {
                    $upd['photo'] = $upload_path_photos . '/' . $this->upload->data('file_name');
                }
            }
            $this->db->where('company_id', $this->input->post('company_id'));
            $this->db->update('company_info', $upd);

            redirect('company-list');

        }
        $data['pincode_opt'] = ['' => 'Select'];
        $data['district_opt'] = ['' => 'Select'];
        $data['area_opt'] = ['' => 'Select'];

        $sql = "
            SELECT 
                state_name
            FROM crit_pincode_info
            WHERE status = 'Active'
            GROUP BY state_name
            ORDER BY state_name ASC";
        $query = $this->db->query($sql);
        $data['state_opt'] = ['' => 'Select'];
        foreach ($query->result_array() as $row) {
            $data['state_opt'][$row['state_name']] = $row['state_name'];
        }

        $sql = "
            SELECT a.*, 
            a.state_id,
            a.district_id,
            a.area
            FROM company_info a
            WHERE a.status != 'Delete'
                AND a.company_id = ?
            ORDER BY a.company_name ASC  
        ";
        $query = $this->db->query($sql, array($company_id));
        $data['record_list'] = $query->row_array();


        $this->load->view('page/company/company-edit', $data);
    }


    public function company_list()
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

        $data['js'] = 'company/company-list.inc';
        $data['title'] = 'Company List';

        // Define filter condition early
        $where = "a.status != 'Delete'";

        // Filters (Company, Customer, Project, Vendor)
        if ($this->input->post('srch_company_id') !== null) {
            $data['srch_company_id'] = $srch_company_id = $this->input->post('srch_company_id');
            $this->session->set_userdata('srch_company_id', $srch_company_id);
        } elseif ($this->session->userdata('srch_company_id')) {
            $data['srch_company_id'] = $srch_company_id = $this->session->userdata('srch_company_id');
        } else {
            $data['srch_company_id'] = $srch_company_id = '';
        }

        if (!empty($srch_company_id)) {
            $where .= " AND (a.company_id = '" . $this->db->escape_str($srch_company_id) . "')";
        }


        $this->db->from('company_info as a');
        $this->db->where($where);
        $this->db->where('a.status !=', 'Delete');

        $data['total_records'] = $this->db->count_all_results();


        $this->load->library('pagination');

        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = site_url('company-list');
        $config['total_rows'] = $data['total_records'];
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;

        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
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
            SELECT a.company_id, a.company_name
            FROM company_info a
            WHERE a.status != 'Delete'
            ORDER BY a.company_name ASC
        ";

        $query = $this->db->query($sql);
        $data['company_opt'] = array('' => 'All');
        foreach ($query->result_array() as $row) {
            $data['company_opt'][$row['company_id']] = $row['company_name'];
        }

        $sql = "
            SELECT a.* 
            FROM company_info a
            WHERE $where
            ORDER BY a.company_id DESC
            LIMIT " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "
        ";

        $data['record_list'] = array();
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }

        $data['pagination'] = $this->pagination->create_links();


        $this->load->view('page/company/company-list', $data);
    }

    public function delete_record()
    {

        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        date_default_timezone_set("Asia/Calcutta");


        $table = $this->input->post('tbl');
        $rec_id = $this->input->post('id');

        if ($table == 'company_info') {
            $this->db->where('company_id', $rec_id);
            $this->db->update('company_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
    }
}