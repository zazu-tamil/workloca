<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supervisor extends CI_Controller
{

    public function index()
    {
        $this->load->view('page/dashboard');
    }

    private function upload_single_file($input_field, $folder_path)
    {
        if (!empty($_FILES[$input_field]['name'])) {

            $config['upload_path'] = $folder_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|bmp|tiff|pdf';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload($input_field)) {
                return $this->upload->data('file_name');
            } else {
                return '';
            }
        }
        return '';
    }

    public function supervisor_details_add()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if ($this->session->userdata(SESS_HD . 'user_type') != 'Admin' && $this->session->userdata(SESS_HD . 'user_type') != 'Staff') {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }

        $data['js'] = 'supervisor/supervisor-details-add.inc';
        $data['title'] = 'Supervisor Details List';

        if ($this->input->post('mode') == 'Add') {

            // echo "<pre>";
            // print_r($_POST);
            // echo "</pre>";


            $upload_path_photos = 'Supervisor-Photos/';
            if (!is_dir($upload_path_photos)) {
                mkdir($upload_path_photos, 0777, true);
            }

            $config1['upload_path'] = $upload_path_photos;
            $config1['allowed_types'] = 'jpg|jpeg|png|gif|bmp|tiff|pdf';
            $config1['max_size'] = 10240;
            $config1['encrypt_name'] = true;

            $this->load->library('upload');
            $this->upload->initialize($config1);

            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                if ($this->upload->do_upload('photo')) {
                    $photo = $this->upload->data('file_name');
                }
            }



            $name_of_Supervisor = $this->input->post('full_name');
            $name_of_Supervisor = preg_replace('/[^A-Za-z0-9\-]/', '_', $name_of_Supervisor);
            $date_with_time = date('Y-m-d-h-i-s');

            $folder_path = 'Supervisor-Documents/';

            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }


            $aadhar_file = $this->upload_single_file('upload_aadhaar', $folder_path);
            $pan_file = $this->upload_single_file('upload_pan', $folder_path);
            // $address_file = $this->upload_single_file('upload_address', $folder_path);
            // $skill_file = $this->upload_single_file('upload_skill', $folder_path);



            $pref_location = $this->input->post('pref_location'); // array
            $employee_skill_id = $this->input->post('employee_skill_id'); // array
            $language_id = $this->input->post('language_id'); // array

            $ins = array(

                'pref_location' => !empty($pref_location) ? implode(',', $pref_location) : '',
                'employee_skill_id' => !empty($employee_skill_id) ? implode(',', $employee_skill_id) : '',
                'language_id' => !empty($language_id) ? implode(',', $language_id) : '',


                // Personal
                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'full_name' => $this->input->post('full_name'),
                'parent_name' => $this->input->post('parent_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'marital_status' => $this->input->post('marital_status'),

                // Contact
                'mobile' => $this->input->post('mobile'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'email' => $this->input->post('email'),
                'emergency_contact' => $this->input->post('emergency_contact'),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'experience_years' => $this->input->post('experience_years'),

                // IDs
                'availability' => $this->input->post('availability'),
                'shift_preference' => $this->input->post('shift_preference'),



                // Images
                'photo' => $upload_path_photos . $photo,

                'salary_type' => $this->input->post('salary_type'),
                'salary' => $this->input->post('salary'),


                // Banking
                'bank_name' => $this->input->post('bank_name'),
                'acc_holder_name' => $this->input->post('acc_holder_name'),
                'account_number' => $this->input->post('account_number'),
                'ifsc_code' => $this->input->post('ifsc_code'),

                'branch' => $this->input->post('branch'),
                'terms_accepted' => $this->input->post('terms_accepted'),
                'nda_agreement' => $this->input->post('nda_agreement'),
                'background_check' => $this->input->post('background_check'),
                'industry_id' => $this->input->post('industry_id'),

                // All 4 Verification Docs (same folder)
                'upload_aadhaar' => $folder_path . $aadhar_file,
                'upload_pan' => $folder_path . $pan_file,
                //'upload_address' => $folder_path . $address_file,
                //'upload_skill' => $folder_path . $skill_file, 

                'status' => 'Active',
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert('supervisor_info', $ins);
            $supervisor_id = $this->db->insert_id();

            $user_name = $this->input->post('user_name');
            $user_pwd = $this->input->post('user_pwd');


            $inc3 = [
                'ref_id' => $supervisor_id,
                'user_name' => $user_name,
                'user_pwd' => $user_pwd,
                'user_type' => 'Supervisor',
                'status' => 'Active',
                'level' => '3',
            ];
            $this->db->insert('user_login_info', $inc3);

            redirect('supervisor-details-list');

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
            FROM language_info a 
            where a.status != 'Delete'
            order by a.language_name asc        
        ";

        $query = $this->db->query($sql);
        $data['language_opt'] = array('' => 'Select Language');
        foreach ($query->result_array() as $row) {
            $data['language_opt'][$row['language_id']] = $row['language_name'];
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

        $sql = "
            SELECT a.*
            FROM disability_info a
            WHERE a.status != 'Delete'
            ORDER BY a.disability_name ASC
        ";

        $query = $this->db->query($sql);

        $data['disabilityes_opt'] = array('' => 'Select Disability');

        foreach ($query->result_array() as $row) {
            $data['disabilityes_opt'][$row['disability_id']] = $row['disability_name'];
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
            SELECT 
                terms_accepted,
                nda_agreement,
                background_check,
                supervisor_terms_id
            FROM supervisor_terms_info
            WHERE status = 'Active' 
            ORDER BY supervisor_terms_id desc";
        $query = $this->db->query($sql);    
        $data['terms_and_conditions'] = $this->db->query($sql)->row_array();

        $this->load->view('page/supervisor/supervisor-details-add', $data);
    }


    private function upload_single_file_edit($input_field, $folder_path, $old_file = '')
    {
        if (!empty($_FILES[$input_field]['name'])) {

            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $config = [
                'upload_path' => $folder_path,
                'allowed_types' => 'jpg|jpeg|png|gif|bmp|tiff|pdf',
                'max_size' => 10240,
                'encrypt_name' => TRUE
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload($input_field)) {

                if (!empty($old_file) && file_exists($old_file)) {
                    unlink($old_file);
                }

                return $folder_path . $this->upload->data('file_name');
            }
        }

        return $old_file;
    }

    public function supervisor_details_edit($supervisor_id)
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in')) {
            redirect();
        }

        if (
            $this->session->userdata(SESS_HD . 'user_type') != 'Admin' &&
            $this->session->userdata(SESS_HD . 'user_type') != 'Staff'
        ) {
            show_error('Permission Denied', 403);
        }

        $data['js'] = 'supervisor/supervisor-details-edit.inc';
        $data['title'] = 'Supervisor Details Edit';
        $data['rs'] = $this->db
            ->where('supervisor_id', $supervisor_id)
            ->get('supervisor_info')
            ->row_array();

        if ($this->input->post('mode') == 'Edit') {
            $pref_location = $this->input->post('pref_location');
            $employee_skill_id = $this->input->post('employee_skill_id');
            $language_id = $this->input->post('language_id');
            // 1. Handle file uploads
            $upload_path_photos = 'Supervisor-Photos';
            if (!is_dir($upload_path_photos)) {
                mkdir($upload_path_photos, 0777, true);
            }

            $config['upload_path'] = $upload_path_photos;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);



            $doc_path = 'Supervisor-Documents/';

            $aadhaar_file = $this->upload_single_file_edit(
                'upload_aadhaar',
                $doc_path,
                $data['rs']['upload_aadhaar']
            );

            $pan_file = $this->upload_single_file_edit(
                'upload_pan',
                $doc_path,
                $data['rs']['upload_pan']
            );

            $upd = [



                // MULTI SELECT
                'pref_location' => !empty($pref_location) ? implode(',', $pref_location) : '',
                'employee_skill_id' => !empty($employee_skill_id) ? implode(',', $employee_skill_id) : '',
                'language_id' => !empty($language_id) ? implode(',', $language_id) : '',

                // PERSONAL
                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'full_name' => $this->input->post('full_name'),
                'parent_name' => $this->input->post('parent_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'marital_status' => $this->input->post('marital_status'),

                // CONTACT
                'mobile' => $this->input->post('mobile'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'email' => $this->input->post('email'),
                'emergency_contact' => $this->input->post('emergency_contact'),
                'current_address' => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'industry_id' => $this->input->post('industry_id'),
                'experience_years' => $this->input->post('experience_years'),

                // JOB
                'availability' => $this->input->post('availability'),
                'shift_preference' => $this->input->post('shift_preference'),

                // FILES 
                'upload_aadhaar' => $aadhaar_file,
                'upload_pan' => $pan_file,

                // SALARY
                'salary_type' => $this->input->post('salary_type'),
                'salary' => $this->input->post('salary'),

                // BANK
                'bank_name' => $this->input->post('bank_name'),
                'acc_holder_name' => $this->input->post('acc_holder_name'),
                'account_number' => $this->input->post('account_number'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'branch' => $this->input->post('branch'),

                // DECLARATION
                'terms_accepted' => $this->input->post('terms_accepted') ? 1 : 0,
                'nda_agreement' => $this->input->post('nda_agreement') ? 1 : 0,
                'background_check' => $this->input->post('background_check') ? 1 : 0,

                'status' => 'Active',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (!empty($_FILES['photo']['name'])) {
                if ($this->upload->do_upload('photo')) {
                    $upd['photo'] = $upload_path_photos . '/' . $this->upload->data('file_name');
                }
            }

            $this->db->where('supervisor_id', $supervisor_id)
                ->update('supervisor_info', $upd);

            $user_id = $this->input->post('user_id');
            $login = [
                'ref_id' => $supervisor_id,
                'user_name' => $this->input->post('user_name'),
                'user_pwd' => $this->input->post('user_pwd'),
                'user_type' => 'Supervisor',
                'level' => 3,
                'status' => 'Active'
            ];

            if (!empty($user_id)) {
                $this->db->where('user_id', $user_id)
                    ->update('user_login_info', $login);
            } else {
                $this->db->insert('user_login_info', $login);
            }

            //redirect('supervisor-details-list');
        }

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
            FROM language_info a 
            where a.status != 'Delete'
            order by a.language_name asc        
        ";

        $query = $this->db->query($sql);
        $data['language_opt'] = array('' => 'Select Language');
        foreach ($query->result_array() as $row) {
            $data['language_opt'][$row['language_id']] = $row['language_name'];
        }
        $sql = "
            SELECT a.*
            FROM supervisor_info a
            WHERE a.status != 'Delete'
                AND a.supervisor_id = ?
            ORDER BY a.full_name ASC  
        ";
        $query = $this->db->query($sql, array($supervisor_id));
        $data['supervisor'] = $query->row_array();


        $sql = "
            select 
            b.user_id, 
            b.user_name,
            b.user_pwd
            from supervisor_info as a
            left join user_login_info as b on a.supervisor_id = b.ref_id and b.`status`='Active'
            and a.supervisor_id = ?
        ";
        $query = $this->db->query($sql, array($supervisor_id));
        $data['login_details'] = $query->row_array();

        $this->load->view('page/supervisor/supervisor-details-edit', $data);
    }


    public function supervisor_details_list()
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

        $data['js'] = 'supervisor/supervisor-details-list.inc';
        $data['title'] = 'Supervisor List';

          // Define filter condition early
        $where = "a.status != 'Delete'";

        // Filters (Company, Customer, Project, Vendor)
        if ($this->input->post('srch_supervisor_id') !== null) {
            $data['srch_supervisor_id'] = $srch_supervisor_id = $this->input->post('srch_supervisor_id');
            $this->session->set_userdata('srch_supervisor_id', $srch_supervisor_id);
        } elseif ($this->session->userdata('srch_supervisor_id')) {
            $data['srch_supervisor_id'] = $srch_supervisor_id = $this->session->userdata('srch_supervisor_id');
        } else {
            $data['srch_supervisor_id'] = $srch_supervisor_id = '';
        }

        if (!empty($srch_supervisor_id)) {
            $where .= " AND (a.supervisor_id = '" . $this->db->escape_str($srch_supervisor_id) . "')";
        }

         
        $this->db->from('supervisor_info as a');
        $this->db->where($where);
        $this->db->where('a.status !=', 'Delete');

        $data['total_records'] = $this->db->count_all_results();


        $this->load->library('pagination');

        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('supervisor-details-list') . '/' . $this->uri->segment(2, 0));
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
            SELECT a.supervisor_id, a.full_name
            FROM supervisor_info a
            WHERE a.status != 'Delete'
            ORDER BY a.full_name ASC
        ";

        $query = $this->db->query($sql);
        $data['name_opt'] = array('' => 'All');
        foreach ($query->result_array() as $row) {
            $data['name_opt'][$row['supervisor_id']] = $row['full_name'];
        }

        $sql = "
            SELECT a.* 
            FROM supervisor_info a
            WHERE $where
            ORDER BY a.supervisor_id DESC
            LIMIT " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "
        ";

        $data['record_list'] = array(); 
        $query = $this->db->query($sql); 
        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }

        $data['pagination'] = $this->pagination->create_links();


        $this->load->view('page/supervisor/supervisor-details-list', $data);
    }

    public function delete_record()
    {

        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        date_default_timezone_set("Asia/Calcutta");


        $table = $this->input->post('tbl');
        $rec_id = $this->input->post('id');

        if ($table == 'supervisor_info') {
            $this->db->where('supervisor_id', $rec_id);
            $this->db->update('supervisor_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
    }
}