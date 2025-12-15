<?php
defined('BASEPATH') or exit('No direct script access allowed');

class General extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        //$this->load->view('page/dashboard');
    }


    public function get_data()
    {
        //if(!$this->session->userdata('zazu_logged_in'))  redirect();

        date_default_timezone_set("Asia/Calcutta");


        $table = $this->input->post('tbl');
        $rec_id = $this->input->post('id');


        if ($table == 'user_login_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from user_login_info as a  
                where a.user_id = '" . $rec_id . "'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }


        if ($table == 'employee_skill_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from employee_skill_info as a  
                where a.employee_skill_id = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'employee_category_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from employee_category_info as a  
                where a.employee_category_id = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }


        if ($table == 'get_district_from_state_id') {
            $query = $this->db->query("
                SELECT DISTINCT district_name
                FROM crit_pincode_info
                WHERE status = 'Active'
                AND state_name = '" . $this->db->escape_str($rec_id) . "'
                GROUP BY district_name
                ORDER BY district_name ASC
            ");
            $rec_list = $query->result_array();
        }

        if ($table == 'crit_pincode_info') {
            $query = $this->db->query("
                    SELECT *
                    FROM crit_pincode_info
                    WHERE pincode_id = ?
                    AND status != 'Delete'
                ", [$rec_id]);

            if ($query->num_rows() > 0) {
                $rec_list = $query->row_array();
                echo json_encode([
                    'success' => true,
                    'data' => $rec_list
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No record found'
                ]);
            }
            return;
        }



        if ($table == 'sports_list_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from sports_list_info as a  
                where a.sports_list_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'health_issues_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from health_issues_info as a  
                where a.health_issues_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'hobbies_list_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from hobbies_list_info as a  
                where a.hobbies_list_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'business_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from business_info as a  
                where a.business_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'talent_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from talent_info as a  
                where a.talent_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'disability_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from disability_info as a  
                where a.disability_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }
        if ($table == 'supervisor_terms_info') {
            $query = $this->db->query(" 
                select 
                a.* 
                from supervisor_terms_info as a  
                where a.supervisor_terms_id  = '" . $rec_id . "'
                and a.status != 'Delete'
            ");

            $rec_list = array();

            foreach ($query->result_array() as $row) {
                $rec_list = $row;
            }

        }


        $this->db->close();

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



        if ($table == 'user_login_info') {
            $this->db->where('user_id', $rec_id);
            $this->db->update('user_login_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'evnt_ticket_booking_info') {
            $this->db->where('ticket_booking_id', $rec_id);
            $this->db->update('evnt_ticket_booking_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'employee_skill_info') {
            $this->db->where('employee_skill_id', $rec_id);
            $this->db->update('employee_skill_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'employee_category_info') {
            $this->db->where('employee_category_id', $rec_id);
            $this->db->update('employee_category_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }

        if ($table == 'crit_pincode_info') {
            $this->db->where('pincode_id  ', $rec_id);
            $this->db->update('crit_pincode_info', ['status' => 'Delete']);

            if ($this->db->affected_rows() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Record successfully deleted'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Delete failed or already deleted'
                ]);
            }
            return;
        }


        if ($table == 'talent_info') {
            $this->db->where('talent_id', $rec_id);
            $this->db->update('talent_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }

        if ($table == 'business_info') {
            $this->db->where('business_id', $rec_id);
            $this->db->update('business_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }

        if ($table == 'sports_list_info') {
            $this->db->where('sports_list_id', $rec_id);
            $this->db->update('sports_list_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'health_issues_info') {
            $this->db->where('health_issues_id', $rec_id);
            $this->db->update('health_issues_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'hobbies_list_info') {
            $this->db->where('hobbies_list_id', $rec_id);
            $this->db->update('hobbies_list_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'disability_info') {
            $this->db->where('disability_id', $rec_id);
            $this->db->update('disability_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'employee_family_info') {
            $this->db->where('family_id', $rec_id);
            $this->db->update('employee_family_info', [
                'status' => 'Delete'
            ]);

            echo "Record Deleted Successfully";
            return;
        }
        if ($table == 'employee_talent_info') {
            $this->db->where('employee_talent_id', $rec_id);
            $this->db->update('employee_talent_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'supervisor_terms_info') {
            $this->db->where('supervisor_terms_id', $rec_id);
            $this->db->update('supervisor_terms_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }
        if ($table == 'company_info') {
            $this->db->where('company_id', $rec_id);
            $this->db->update('company_info', array('status' => 'Delete'));
            echo "Record Deleted Successfully";
        }

    }

}