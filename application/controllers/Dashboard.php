<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{


    public function index()
    {

        if (!$this->session->userdata(SESS_HD . 'logged_in'))
            redirect();

        date_default_timezone_set("Asia/Calcutta");

        $data = array();

        $data['js'] = 'dash.inc'; 
        
        // $this->db->where('payment_status', 'Paid');
        // $this->db->where('status', 'Active');
        // $data['booking_paid'] = $this->db->count_all_results('evnt_ticket_booking_info'); 

        // $this->db->where('payment_status', 'Pending');
        // $this->db->where('status', 'Active');
        // $data['booking_pending'] = $this->db->count_all_results('evnt_ticket_booking_info');

 
        // $this->db->where('status', 'Active');
        // $data['total_bookings'] = $this->db->count_all_results('evnt_ticket_booking_info');
  
        // $this->db->select_sum('ticket_amount');
        // $this->db->where('payment_status', 'Paid');
        // $this->db->where('status', 'Active');
        // $qry1 = $this->db->get('evnt_ticket_booking_info')->row();
        // $data['total_amount_paid'] = $qry1->ticket_amount ? $qry1->ticket_amount : 0; 
 
        // $this->db->select_sum('ticket_amount');
        // $this->db->where('payment_status', 'Pending');
        // $this->db->where('status', 'Active');
        // $qry2 = $this->db->get('evnt_ticket_booking_info')->row();
        // $data['total_amount_pending'] = $qry2->ticket_amount ? $qry2->ticket_amount : 0;



        $this->load->view('page/dashboard', $data);
    }


}