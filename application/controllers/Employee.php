<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee extends CI_Controller
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
    public function employee_details_add()
    {
        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        if ($this->session->userdata(SESS_HD . 'user_type') != 'Admin' && $this->session->userdata(SESS_HD . 'user_type') != 'Staff') {
            echo "<h3 style='color:red;'>Permission Denied</h3>";
            exit;
        }

        $data['js'] = 'employee/employee-details-add.inc';
        $data['title'] = 'Employee Details';

        if ($this->input->post('mode') == 'Add') {


            $upload_path_photos = 'Employee-Photos/';
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

            /*--------------------------------------------------
     2. Upload Certificates (MULTIPLE FILES)
  --------------------------------------------------*/

            $upload_path_certificates = 'Employee-Certificates/';
            if (!is_dir($upload_path_certificates)) {
                mkdir($upload_path_certificates, 0777, true);
            }

            $config2['upload_path'] = $upload_path_certificates;
            $config2['allowed_types'] = 'jpg|jpeg|png|gif|bmp|tiff|pdf';
            $config2['max_size'] = 10240;
            $config2['encrypt_name'] = true;

            $this->load->library('upload');

            $certificate_files = []; // store file names here

            if (!empty($_FILES['cert_file']['name'][0])) {

                $filesCount = count($_FILES['cert_file']['name']);

                for ($i = 0; $i < $filesCount; $i++) {

                    $_FILES['single_cert']['name'] = $_FILES['cert_file']['name'][$i];
                    $_FILES['single_cert']['type'] = $_FILES['cert_file']['type'][$i];
                    $_FILES['single_cert']['tmp_name'] = $_FILES['cert_file']['tmp_name'][$i];
                    $_FILES['single_cert']['error'] = $_FILES['cert_file']['error'][$i];
                    $_FILES['single_cert']['size'] = $_FILES['cert_file']['size'][$i];

                    $this->upload->initialize($config2);

                    if ($this->upload->do_upload('single_cert')) {
                        $data = $this->upload->data();
                        $certificate_files[] = $data['file_name'];
                    }
                }
            }

            // Save in DB as comma-separated
            $certificates_photo = implode(',', $certificate_files);

            /*--------------------------------------------------
                3. Create Employee Verification Folder
            --------------------------------------------------*/
            $name_of_Employee = $this->input->post('full_name');
            $name_of_Employee = preg_replace('/[^A-Za-z0-9\-]/', '_', $name_of_Employee);
            $date_with_time = date('Y-m-d-h-i-s');

            $folder_path = 'Employee-Verification-Documents/' . $name_of_Employee . '-' . $date_with_time . '/';

            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            /*--------------------------------------------------
                4. Upload Verification Documents (All in same folder)
            --------------------------------------------------*/
            $aadhar_file = $this->upload_single_file('upload_aadhar', $folder_path);
            $pan_file = $this->upload_single_file('upload_pan', $folder_path);
            $address_file = $this->upload_single_file('upload_address', $folder_path);
            $skill_file = $this->upload_single_file('upload_skill', $folder_path);

            /*--------------------------------------------------
                5. Arrays
            --------------------------------------------------*/
            $issues = $this->input->post('health_issues_id');   // array
            $sports = $this->input->post('sports_list_id');   // array
            $hobbies = $this->input->post('hobbies_list_id'); // array
            $pref_location = $this->input->post('pref_location'); // array
            $disability_id = $this->input->post('disability_id'); // array


            $ins = array(

                'disability_id' => !empty($disability_id) ? implode(',', $disability_id) : '',
                'pref_location' => !empty($pref_location) ? implode(',', $pref_location) : '',
                'health_issues_id' => !empty($issues) ? implode(',', $issues) : '',
                'sports_list_id' => !empty($sports) ? implode(',', $sports) : '',
                'hobbies_list_id' => !empty($hobbies) ? implode(',', $hobbies) : '',


                // Personal
                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'full_name' => $this->input->post('full_name'),
                'guardian_name' => $this->input->post('guardian_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'marital_status' => $this->input->post('marital_status'),

                // Contact
                'mobile_primary' => $this->input->post('mobile_primary'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'whatsapp_number' => $this->input->post('whatsapp_number'),
                'current_address' => $this->input->post('current_address'),

                // IDs
                'aadhar_number' => $this->input->post('aadhar_number'),
                'pan_number' => $this->input->post('pan_number'),

                // Emergency
                'emergency_contact_name' => $this->input->post('emergency_contact_name'),
                'emergency_contact_number' => $this->input->post('emergency_contact_number'),

                // Images
                'photo' => $upload_path_photos . $photo,
                'cert_file' => $upload_path_certificates . $certificates_photo,

                // Skills
                'employee_skill_id' => $this->input->post('employee_skill_id'),
                'employee_category_id' => $this->input->post('employee_category_id'),
                'skill_other' => $this->input->post('skill_other'),
                'exp_level' => $this->input->post('exp_level'),
                'total_exp' => $this->input->post('total_exp'),
                'prev_company' => $this->input->post('prev_company'),
                'salary_type' => $this->input->post('salary_type'),
                'salary' => $this->input->post('salary'),
                'work_time' => $this->input->post('work_time'),
                'work_time_start' => $this->input->post('work_time_start'),
                'work_time_end' => $this->input->post('work_time_end'),
                'location_other' => $this->input->post('location_other'),
                'work_id_no' => $this->input->post('work_id_no'),

                // Family
                'parent_name' => $this->input->post('parent_name'),
                'spouse_name' => $this->input->post('spouse_name'),
                'children_count' => $this->input->post('children_count'),
                'children_ages' => $this->input->post('children_ages'),
                'children_edu' => $this->input->post('children_edu'),

                // Health
                'health_other' => $this->input->post('health_other'),
                'disability' => $this->input->post('disability'),
                'allergy' => $this->input->post('allergy'),
                'allergy_details' => $this->input->post('allergy_details'),

                // Lifestyle
                'smoking' => $this->input->post('smoking'),
                'alcohol' => $this->input->post('alcohol'),
                'fitness' => $this->input->post('fitness'),
                'sports_other' => $this->input->post('sports_other'),
                'sport_level' => $this->input->post('sport_level'),
                'hobby_other' => $this->input->post('hobby_other'),

                // Banking
                'acc_holder' => $this->input->post('acc_holder'),
                'acc_number' => $this->input->post('acc_number'),
                'ifsc' => $this->input->post('ifsc'),
                'upi' => $this->input->post('upi'),

                // All 4 Verification Docs (same folder)
                'upload_aadhar' => $folder_path . $aadhar_file,
                'upload_pan' => $folder_path . $pan_file,
                'upload_address' => $folder_path . $address_file,
                'upload_skill' => $folder_path . $skill_file,

                // Interests
                'ngo_interest' => $this->input->post('ngo_interest'),
                'political_interest' => $this->input->post('political_interest'),

                // Status
                'status' => 'Active'
            );

            $this->db->insert('employee_info', $ins);
            $employee_id = $this->db->insert_id();

            $famil_name = $this->input->post('name');
            $famil_dob = $this->input->post('family_dob');
            $famil_edu = $this->input->post('education');
            $famil_rel = $this->input->post('relationship');

            if (!empty($famil_name)) {
                foreach ($famil_name as $key => $name) {
                    $inc2 = [
                        'employee_id' => $employee_id,
                        'name' => $name,
                        'family_dob' => $famil_dob[$key],
                        'education' => $famil_edu[$key],
                        'relationship' => $famil_rel[$key],
                        'status' => 'Active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('employee_family_info', $inc2);
                }
            }

            $user_name = $this->input->post('user_name');
            $user_pwd = $this->input->post('user_pwd');


            $inc3 = [
                'ref_id' => $employee_id,
                'user_name' => $user_name,
                'user_pwd' => $user_pwd,
                'user_type' => 'Employee',
                'status' => 'Active',
                'level' => '2',
            ];
            $this->db->insert('user_login_info', $inc3);

            $business_id = $this->input->post('business_id');
            $business_location = $this->input->post('business_location');


            $employee_business = [
                'employee_id' => $employee_id,
                'business_id' => $business_id,
                'exper_year' => $this->input->post('exper_year'),
                'business_location' => !empty($business_location) ? implode(',', $business_location) : '',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),

            ];
            $this->db->insert('employee_business_info', $employee_business);



            $talent_id = $this->input->post('talent_id');
            $volunteered_interest_id = $this->input->post('volunteered_interest_id');
            $talent_description = $this->input->post('talent_description');

            if (!empty($talent_id)) {

                foreach ($talent_id as $key => $talent) {

                    // Skip empty rows
                    if (empty($talent)) {
                        continue;
                    }

                    $talent_inc = [
                        'employee_id' => $employee_id,
                        'talent_id' => $talent, // ✅ FIXED
                        'volunteered_interest_id' => $volunteered_interest_id[$key] ?? null,
                        'talent_description' => $talent_description[$key] ?? '',
                        'status' => 'Active',
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $this->db->insert('employee_talent_info', $talent_inc);
                }
            }



            redirect('employee-details-list');

        }

        $sql = "
            SELECT a.*
            FROM employee_category_info a
            where a.status != 'Delete'
                order by a.category_name asc           
          ";

        $query = $this->db->query($sql);
        $data['employee_category_opt'] = array('' => 'Select Category');
        foreach ($query->result_array() as $row) {
            $data['employee_category_opt'][$row['employee_category_id']] = $row['category_name'];
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
            SELECT a.*
            FROM health_issues_info a
            where a.status != 'Delete'
                order by a.health_issues_name asc           
        ";
        $query = $this->db->query($sql);
        $data['health_issues'] = $query->result_array();

        $sql = "
            SELECT a.*
            FROM sports_list_info a
            where a.status != 'Delete'
                order by a.sports_name asc           
        ";
        $query = $this->db->query($sql);
        $data['sports_list'] = $query->result_array();

        $sql = "
            SELECT a.*
            FROM hobbies_list_info a
            where a.status != 'Delete'
                order by a.hobbies_name asc           
        ";
        $query = $this->db->query($sql);
        $data['hobbies_list'] = $query->result_array();

        $sql = "
            SELECT 
                a.*
            FROM talent_info a 
            where a.status != 'Delete'
            order by a.talent_name asc        
        ";

        $query = $this->db->query($sql);
        $data['talents_opt'] = array('' => 'Select Talent');
        foreach ($query->result_array() as $row) {
            $data['talents_opt'][$row['talent_id']] = $row['talent_name'];
        }

        $sql = "
            SELECT 
                a.*
            FROM business_info a 
            where a.status != 'Delete'
            order by a.business_name asc        
        ";

        $query = $this->db->query($sql);
        $data['business_opt'] = array('' => 'Select Business');
        foreach ($query->result_array() as $row) {
            $data['business_opt'][$row['business_id']] = $row['business_name'];
        }

        $sql = "
            SELECT 
                a.*
            FROM talent_info a 
            where a.status != 'Delete'
            order by a.talent_name asc        
        ";

        $query = $this->db->query($sql);
        $data['volunteered_interest_opt'] = array('' => 'Select Interest volunteered');
        foreach ($query->result_array() as $row) {
            $data['volunteered_interest_opt'][$row['talent_id']] = $row['talent_name'];
        }



        $this->load->view('page/employee/employee-details-add', $data);
    }

    private function upload_single_file_edit($input_field, $folder_path, $old_file = '')
    {
        if (!empty($_FILES[$input_field]['name'])) {

            $config['upload_path'] = $folder_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|bmp|tiff|pdf';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload($input_field)) {

                // Delete old file
                if (!empty($old_file) && file_exists($old_file)) {
                    unlink($old_file);
                }

                return $folder_path . $this->upload->data('file_name');
            } else {
                return $old_file;
            }
        }
        return $old_file;
    }

    public function employee_details_edit($employee_id)
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

        $data['js'] = 'employee/employee-details-edit.inc';
        $data['title'] = 'Employee Details Edit';

        // Fetch old record
        $data['rs'] = $this->db->where('employee_id', $employee_id)->get('employee_info')->row_array();

        // if ($this->input->post('mode') == 'Edit') {
        //     echo "<pre>";
        //     print_r($_POST);
        //     echo "</pre>";
        // }

        if ($this->input->post('mode') == 'Edit') {

            $this->load->library('upload');

            /* ======================================================
               1. PHOTO  (single)
            ====================================================== */
            $upload_path_photos = 'Employee-Photos/';
            if (!is_dir($upload_path_photos)) {
                mkdir($upload_path_photos, 0777, true);
            }

            $photo = $data['rs']['photo']; // old photo path

            if (!empty($_FILES['photo']['name'])) {

                $config1 = array(
                    'upload_path' => $upload_path_photos,
                    'allowed_types' => 'jpg|jpeg|png|gif|bmp|tiff|pdf',
                    'max_size' => 10240,
                    'encrypt_name' => TRUE,
                );

                $this->upload->initialize($config1);

                if ($this->upload->do_upload('photo')) {

                    if (!empty($photo) && file_exists($photo)) {
                        unlink($photo);
                    }

                    $photo = $upload_path_photos . $this->upload->data('file_name');
                }
            }

            /* ======================================================
               2. CERTIFICATES (multiple files append)
            ====================================================== */
            $upload_path_certificates = 'Employee-Certificates/';
            if (!is_dir($upload_path_certificates)) {
                mkdir($upload_path_certificates, 0777, true);
            }

            $existing_files = !empty($data['rs']['cert_file'])
                ? explode(',', $data['rs']['cert_file']) : [];

            $new_files = [];

            if (!empty($_FILES['cert_file']['name'][0])) {

                $filesCount = count($_FILES['cert_file']['name']);

                for ($i = 0; $i < $filesCount; $i++) {

                    $_FILES['single_cert']['name'] = $_FILES['cert_file']['name'][$i];
                    $_FILES['single_cert']['type'] = $_FILES['cert_file']['type'][$i];
                    $_FILES['single_cert']['tmp_name'] = $_FILES['cert_file']['tmp_name'][$i];
                    $_FILES['single_cert']['error'] = $_FILES['cert_file']['error'][$i];
                    $_FILES['single_cert']['size'] = $_FILES['cert_file']['size'][$i];

                    $config2 = array(
                        'upload_path' => $upload_path_certificates,
                        'allowed_types' => 'jpg|jpeg|png|gif|bmp|tiff|pdf',
                        'max_size' => 10240,
                        'encrypt_name' => TRUE,
                    );

                    $this->upload->initialize($config2);

                    if ($this->upload->do_upload('single_cert')) {
                        $up = $this->upload->data();
                        $new_files[] = $upload_path_certificates . $up['file_name'];
                    }
                }
            }

            $final_cert_files = array_merge($existing_files, $new_files);
            $certificates_photo = implode(',', $final_cert_files);

            /* ======================================================
               3. OTHER FILES (Aadhar, PAN, Address, Skill)
            ====================================================== */
            $folder_path = 'Employee-Documents/';
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $aadhar_file = $this->upload_single_file_edit('upload_aadhar', $folder_path, $data['rs']['upload_aadhar']);
            $pan_file = $this->upload_single_file_edit('upload_pan', $folder_path, $data['rs']['upload_pan']);
            $address_file = $this->upload_single_file_edit('upload_address', $folder_path, $data['rs']['upload_address']);
            $skill_file = $this->upload_single_file_edit('upload_skill', $folder_path, $data['rs']['upload_skill']);

            /* ======================================================
               COLLECT ARRAY DATA
            ====================================================== */
            $issues = $this->input->post('health_issues_id');
            $sports = $this->input->post('sports_list_id');
            $hobbies = $this->input->post('hobbies_list_id');
            $pref_location = $this->input->post('pref_location');
            $disability_id = $this->input->post('disability_id'); // array


            $upd = array(

                'state_id' => $this->input->post('state_id'),
                'district_id' => $this->input->post('district_id'),
                'full_name' => $this->input->post('full_name'),
                'guardian_name' => $this->input->post('guardian_name'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'marital_status' => $this->input->post('marital_status'),

                // Contact
                'mobile_primary' => $this->input->post('mobile_primary'),
                'mobile_alt' => $this->input->post('mobile_alt'),
                'whatsapp_number' => $this->input->post('whatsapp_number'),
                'current_address' => $this->input->post('current_address'),

                // IDs
                'aadhar_number' => $this->input->post('aadhar_number'),
                'pan_number' => $this->input->post('pan_number'),

                // Emergency
                'emergency_contact_name' => $this->input->post('emergency_contact_name'),
                'emergency_contact_number' => $this->input->post('emergency_contact_number'),

                // Uploads
                'photo' => $photo,
                'cert_file' => $certificates_photo,

                // Skills
                'employee_skill_id' => $this->input->post('employee_skill_id'),
                'employee_category_id' => $this->input->post('employee_category_id'),
                'skill_other' => $this->input->post('skill_other'),
                'exp_level' => $this->input->post('exp_level'),
                'total_exp' => $this->input->post('total_exp'),
                'salary' => $this->input->post('salary'),
                'prev_company' => $this->input->post('prev_company'),
                'salary_type' => $this->input->post('salary_type'),
                'work_time' => $this->input->post('work_time'),
                'work_time_start' => $this->input->post('work_time_start'),
                'work_time_end' => $this->input->post('work_time_end'),
                'pref_location' => !empty($pref_location) ? implode(',', $pref_location) : '',
                'disability_id' => !empty($disability_id) ? implode(',', $disability_id) : '',

                'location_other' => $this->input->post('location_other'),
                'work_id_no' => $this->input->post('work_id_no'),

                // Family
                'parent_name' => $this->input->post('parent_name'),
                'spouse_name' => $this->input->post('spouse_name'),
                'children_count' => $this->input->post('children_count'),
                'children_ages' => $this->input->post('children_ages'),
                'children_edu' => $this->input->post('children_edu'),

                // Health
                'health_issues_id' => !empty($issues) ? implode(',', $issues) : '',
                'health_other' => $this->input->post('health_other'),
                'disability' => $this->input->post('disability'),
                'allergy' => $this->input->post('allergy'),
                'allergy_details' => $this->input->post('allergy_details'),

                // Lifestyle
                'smoking' => $this->input->post('smoking'),
                'alcohol' => $this->input->post('alcohol'),
                'fitness' => $this->input->post('fitness'),
                'sports_list_id' => !empty($sports) ? implode(',', $sports) : '',
                'sports_other' => $this->input->post('sports_other'),
                'sport_level' => $this->input->post('sport_level'),
                'hobbies_list_id' => !empty($hobbies) ? implode(',', $hobbies) : '',
                'hobby_other' => $this->input->post('hobby_other'),

                // Banking
                'acc_holder' => $this->input->post('acc_holder'),
                'acc_number' => $this->input->post('acc_number'),
                'ifsc' => $this->input->post('ifsc'),
                'upi' => $this->input->post('upi'),

                // All Verification Docs
                'upload_aadhar' => $aadhar_file,
                'upload_pan' => $pan_file,
                'upload_address' => $address_file,
                'upload_skill' => $skill_file,

                // Interests
                'ngo_interest' => $this->input->post('ngo_interest'),
                'political_interest' => $this->input->post('political_interest'),
            );

            // Update main employee info
            $this->db->where('employee_id', $employee_id)
                ->update('employee_info', $upd);

            // Family inputs
            $famil_name = $this->input->post('name');
            $famil_dob = $this->input->post('family_dob');
            $famil_edu = $this->input->post('education');
            $famil_rel = $this->input->post('relationship');
            $famil_id = $this->input->post('family_id'); // hidden input array

            if (!empty($famil_name)) {

                foreach ($famil_name as $key => $name) {

                    // Skip empty rows
                    if (empty($name)) {
                        continue;
                    }

                    $id = isset($famil_id[$key]) ? $famil_id[$key] : null;

                    $data = [
                        'employee_id' => $employee_id,
                        'name' => $name,
                        'family_dob' => $famil_dob[$key],
                        'education' => $famil_edu[$key],
                        'relationship' => $famil_rel[$key],
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if (!empty($id)) {

                        // UPDATE
                        $this->db->where('family_id', $id)
                            ->update('employee_family_info', $data);

                    } else {

                        // INSERT
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['status'] = 'Active';

                        $this->db->insert('employee_family_info', $data);
                    }
                }
            }


            $user_name = $this->input->post('user_name');
            $user_pwd = $this->input->post('user_pwd');
            $user_id = $this->input->post('user_id');


            $inc3 = [
                'ref_id' => $employee_id,
                'user_name' => $user_name,
                'user_pwd' => $user_pwd,
                'user_type' => 'Employee',
                'status' => 'Active',
                'level' => '2',
            ];

            if (!empty($user_id)) {
                $this->db->where('user_id', $user_id)
                    ->update('user_login_info', $inc3);
            } else {
                $this->db->insert('user_login_info', $inc3);
            }

            $business_id = $this->input->post('business_id');
            $business_location = $this->input->post('business_location');

            $employee_business = [
                'business_id' => $business_id,
                'exper_year' => $this->input->post('exper_year'),
                'business_location' => !empty($business_location) ? implode(',', $business_location) : '',
                'status' => 'Active',
                'updated_at' => date('Y-m-d H:i:s'),
            ];


            $this->db->where('employee_id', $employee_id);
            $this->db->update('employee_business_info', $employee_business);


            $employee_talent_ids = $this->input->post('employee_talent_id');  // array
            $talent_ids = $this->input->post('talent_id');           // array
            $volunteered_interest_ids = $this->input->post('volunteered_interest_id'); // array
            $talent_descriptions = $this->input->post('talent_description'); // array

            if (!empty($talent_ids)) {
                foreach ($talent_ids as $index => $talent_id) {

                    $employee_talent_id = $employee_talent_ids[$index] ?? null;
                    $volunteered_interest_id = $volunteered_interest_ids[$index] ?? null;
                    $talent_description = $talent_descriptions[$index] ?? null;

                    $talent_inc = [
                        'employee_id' => $employee_id,
                        'talent_id' => $talent_id,
                        'volunteered_interest_id' => $volunteered_interest_id,
                        'talent_description' => $talent_description,
                        'status' => 'Active',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];

                    if (!empty($employee_talent_id)) {
                        // UPDATE existing record
                        $this->db->where('employee_talent_id', $employee_talent_id)
                            ->update('employee_talent_info', $talent_inc);
                    } else {
                        // INSERT new record
                        $this->db->insert('employee_talent_info', $talent_inc);
                    }
                }
            }




            redirect('employee-details-list');


        }

        $data['district_opt'] = ['' => 'Select'];
        $data['area_opt'] = ['' => 'Select'];
        $data['supervisor_opt'] = ['' => 'Select Supervisor'];

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
            SELECT a.*
            FROM employee_category_info a
            WHERE a.status != 'Delete'
            ORDER BY a.category_name ASC           
        ";

        $query = $this->db->query($sql);
        $data['employee_category_opt'] = array('' => 'Select Category');
        foreach ($query->result_array() as $row) {
            $data['employee_category_opt'][$row['employee_category_id']] = $row['category_name'];
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

        $sql = "
            SELECT a.*
            FROM health_issues_info a
            WHERE a.status != 'Delete'
            ORDER BY a.health_issues_name ASC           
        ";
        $query = $this->db->query($sql);
        $data['health_issues'] = $query->result_array();

        $sql = "
            SELECT a.*
            FROM sports_list_info a
            WHERE a.status != 'Delete'
            ORDER BY a.sports_name ASC           
        ";
        $query = $this->db->query($sql);
        $data['sports_list'] = $query->result_array();

        $sql = "
            SELECT a.*
            FROM hobbies_list_info a
            WHERE a.status != 'Delete'
            ORDER BY a.hobbies_name ASC           
        ";
        $query = $this->db->query($sql);
        $data['hobbies_list'] = $query->result_array();

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
                a.supervisor_id,
                a.full_name
            FROM supervisor_info a 
            WHERE a.status != 'Delete'
            ORDER BY a.full_name ASC
        ";

        $query = $this->db->query($sql);

        $data['supervisor_opt'] = ['' => 'Select Supervisor'];
        foreach ($query->result_array() as $row) {
            $data['supervisor_opt'][$row['supervisor_id']] = $row['full_name'];
        }


        $sql = "
            SELECT a.*, 
            a.state_id,
            a.district_id,
            a.pref_location
            FROM employee_info a
            WHERE a.status != 'Delete'
                AND a.employee_id = ?
            ORDER BY a.full_name ASC  
        ";
        $query = $this->db->query($sql, array($employee_id));
        $data['record_list'] = $query->row_array();


        $sql = "
            select 
            b.user_id, 
            b.user_name,
            b.user_pwd
            from employee_info as a
            left join user_login_info as b on a.employee_id = b.ref_id and b.`status`='Active'
            and a.employee_id = ?
        ";
        $query = $this->db->query($sql, array($employee_id));
        $data['login_details'] = $query->row_array();


        $sql = "
           SELECT 
            a.*
            FROM
                employee_business_info as a 
                LEFT JOIN employee_info as b
            ON
                a.employee_id = b.employee_id AND b.`status` = 'Active'
            WHERE a.status='Active'
                AND a.employee_id = ?

        ";
        $query = $this->db->query($sql, array($employee_id));
        $data['business_list'] = $query->row_array();

        $sql = "
            SELECT a.*
            FROM employee_family_info a
            WHERE a.status != 'Delete'
                AND a.employee_id = ?
            ORDER BY a.name ASC
        ";
        $query = $this->db->query($sql, array($employee_id));
        $data['family_list'] = $query->result_array(); // Changed from row_array()

        $sql = "
           SELECT
                a.*
            FROM
                employee_talent_info  a
                LEFT JOIN talent_info as b on a.talent_id = b.talent_id and b.status='Actuve'
            WHERE
                a.status != 'Delete' 
                AND a.employee_id = ?
                ORDER by a.employee_talent_id DESC
        ";
        $query = $this->db->query($sql, array($employee_id));
        $data['talent_list'] = $query->result_array(); // Changed from row_array()



        $sql = "
            SELECT 
                a.*
            FROM talent_info a 
            where a.status != 'Delete'
            order by a.talent_name asc        
        ";

        $query = $this->db->query($sql);
        $data['talents_opt'] = array('' => 'Select Talent');
        foreach ($query->result_array() as $row) {
            $data['talents_opt'][$row['talent_id']] = $row['talent_name'];
        }

        $sql = "
            SELECT 
                a.*
            FROM business_info a 
            where a.status != 'Delete'
            order by a.business_name asc        
        ";

        $query = $this->db->query($sql);
        $data['business_opt'] = array('' => 'Select Business');
        foreach ($query->result_array() as $row) {
            $data['business_opt'][$row['business_id']] = $row['business_name'];
        }

        $sql = "
            SELECT 
                a.*
            FROM talent_info a 
            where a.status != 'Delete'
            order by a.talent_name asc        
        ";

        $query = $this->db->query($sql);
        $data['volunteered_interest_opt'] = array('' => 'Select Interest volunteered');
        foreach ($query->result_array() as $row) {
            $data['volunteered_interest_opt'][$row['talent_id']] = $row['talent_name'];
        }


        $this->load->view('page/employee/employee-details-edit', $data);

    }

    public function employee_details_list()
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

        $data['js'] = 'employee/employee-details-list.inc';
        $data['title'] = 'Employee List';

        $where = "a.status != 'Delete'";

        if ($this->input->post('srch_employee_id') !== null) {
            $data['srch_employee_id'] = $srch_employee_id = $this->input->post('srch_employee_id');
            $this->session->set_userdata('srch_employee_id', $srch_employee_id);
        } elseif ($this->session->userdata('srch_employee_id')) {
            $data['srch_employee_id'] = $srch_employee_id = $this->session->userdata('srch_employee_id');
        } else {
            $data['srch_employee_id'] = $srch_employee_id = '';
        }

        if (!empty($srch_employee_id)) {
            $where .= " AND (a.employee_id = '" . $this->db->escape_str($srch_employee_id) . "')";
        }


        $this->db->from('employee_info as a');
        $this->db->where($where);
        $this->db->where('a.status !=', 'Delete');

        $data['total_records'] = $this->db->count_all_results();


        $this->load->library('pagination');

        $data['sno'] = $this->uri->segment(2, 0);

        $config['base_url'] = trim(site_url('employee-details-list') . '/' . $this->uri->segment(2, 0));
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
            SELECT a.employee_id, a.full_name
            FROM employee_info a
            WHERE a.status != 'Delete'
            ORDER BY a.full_name ASC
        ";

        $query = $this->db->query($sql);
        $data['name_opt'] = array('' => 'All');
        foreach ($query->result_array() as $row) {
            $data['name_opt'][$row['full_name']] = $row['full_name'];
        }

        $sql = "
            SELECT a.*
            FROM employee_info a
            WHERE $where
            ORDER BY a.employee_id DESC
            LIMIT " . $this->uri->segment(2, 0) . "," . $config['per_page'] . "
        ";

        $data['record_list'] = array();

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row) {
            $data['record_list'][] = $row;
        }

        $data['pagination'] = $this->pagination->create_links();


        // =======================
        // LOAD VIEW
        // =======================
        $this->load->view('page/employee/employee-details-list', $data);
    }





    public function get_data()
    {
        $table = $this->input->post('tbl');
        $rec_id = $this->input->post('id');

        $this->db->query('SET SQL_BIG_SELECTS=1');
        $rec_list = array();

        if ($table == 'get-employee-skill-list') {

            $query = $this->db->query(" 
                select  
                a.employee_skill_id,
                a.employee_category_id,
                a.skill_name
                from employee_skill_info  as a
                left join employee_category_info as b on a.employee_category_id = b.employee_category_id and b.`status`='Active'  
                where a.status='Active'
                and a.employee_category_id = '" . $rec_id . "'
                order by a.skill_name ASC 
                 
            ");


            $rec_list = $query->result_array();
        }

        if ($table == 'ge_state_based_district_list') {
            $query = $this->db->query("
                SELECT DISTINCT district_name
                FROM crit_pincode_info
                WHERE status='Active'
                AND state_name = '$rec_id'
                ORDER BY district_name ASC
            ");
            $rec_list = $query->result_array();
        }
        if ($table == 'ge_district_based_area_list') {
            $query = $this->db->query("
                SELECT DISTINCT area_name
                FROM crit_pincode_info
                WHERE status='Active'
                AND district_name = '$rec_id'
                ORDER BY area_name ASC
            ");
            $rec_list = $query->result_array();
        }



        header('Content-Type: application/x-json; charset=utf-8');

        echo (json_encode($rec_list));
    }


    public function delete_record()
    {

        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        date_default_timezone_set("Asia/Calcutta");


        $table = $this->input->post('tbl');
        $rec_id = $this->input->post('id');

        if ($table == 'employee_info') {
            $this->db->where('employee_id', $rec_id);
            $this->db->update('employee_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
      
    }
}
