<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	 * //@see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
    public function index()
	{
	   
	    $data['js'] = '';
        $data['login'] = true; 
       
       	 
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'User Name', 'required');
        $this->form_validation->set_rules('user_pwd', 'Password', 'required',array('required' => 'You must provide %s.'));
        if ($this->form_validation->run() == FALSE)
        {
             
            $this->load->view('login',$data); 
        }
        else
        {
            //$user_info = $this->check_login($this->input->post('username'),$this->input->post('password'));
            
            //print_r($user_info); 
            
            $user_info = array();
            
           // $query = $this->db->query("select a.investor_club_id , a.name , a.is_admin  from investor_club_info as a where a.user_name='".$this->input->post('username')."' and a.pwd='".$this->input->post('password')."' and a.status = 'Active'");
            
            $sql = "
              select 
              a.user_id as id, 
              a.first_name as  name , 
              a.level , 
              'staff' as typ, 
              0 as reset_flg,
              a.state,
              a.city ,
              a.pp_customer_id 
              from rh_user_info as a  
              where a.user_name = ".$this->db->escape($this->input->post('username'))."
              and a.pwd = '".md5(md5($this->db->escape($this->input->post('user_pwd'))))."'
              and a.status = 'Active' 
            ";
            
            
           /* $query = $this->db->query("
                select id, name, level, typ,reset_flg  from 
                    (
                    	( select a.user_id as id, a.first_name as  name , a.level , 'staff' as typ, 0 as reset_flg  from rh_user_info as a  where a.user_name = '".$this->input->post('username')."' and a.pwd = '".$this->input->post('user_pwd')."' and a.status = 'Active' )  
                    	  
                    ) as lgn
                    order by lgn.typ asc
                    limit 1
            
            ");*/
            $query = $this->db->query($sql); 

            $cnt = $query->num_rows(); 
            
            
             
            $row = $query->row();
            
            if (isset($row))
            { 
                $newdata = array(
                   'm_user_id'  => $row->id,
                   'm_user_name'  => $row->name, 
                   'm_user_type'  => $row->typ, 
                   'm_reset_flg'  => $row->reset_flg, 
                   'm_pstate'  => $row->state, 
                   'm_pcity'  => $row->city, 
                   'm_pp_customer_id'  => $row->pp_customer_id, 
                   'm_is_admin'  => ($row->typ == 'member'? '0' : $row->level), 
                   'm_logged_in' => TRUE
               );
               
                $this->session->set_userdata($newdata);
                
                
              $this->db->insert('crit_user_history_info',array('user_id' => $this->session->userdata('m_user_id') , 'page' => 'Login', 'date_time' => date('Y-m-d H:i:s'))) ; 
                 
                 if($row->level == 1 or $row->level == 5)
                     redirect('dash');   
                 elseif($row->level == 4)
                     redirect('customer-pick-pack-list');
                 elseif($row->level == USER_PICKUP)
                     redirect('pickup-delivery');    
                 else    
                     redirect('pickup-list');
                    // redirect('change-login-pwd');   
            
            } 
            else 
            {
				$data['msg'] = ' Invalid User';
				$data['login'] =false;	                 
				$this->load->view('login',$data);
			} 			 
        } 		
	} 
    
    public function visitor_in($page)
    
    {
        //$this->load->model('visitor_model', 'visitor');
        
        $this->visitor->check_visitor($page);          
      
        echo $this->visitor->get_visitor_count($page);         
     
    }
    
    public function get_ajax_city_list($state_code)
    {             
             
        $query = $this->db->query("select UCASE(a.area) as area  from rh_pincode_list as a where a.state_code = '". $state_code."' group by a.area ");
        
        $values = array();
        
        foreach ($query->result_array() as $row)
        {
         $values[$row['area']] = $row['area'] ;    
        }  
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($values));
       // echo "select UCASE(a.area) as area  from rh_pincode_list as a where a.state_code = '". $state_code."' group by a.area ";
    }
    
    public function get_ajax_pincode_list_old()
    { 
            
            $pin = $this->input->get('term');
            
            $query = $this->db->query("select pincode , area from rh_pincode_list as a where a.pincode like '". $pin."%'  by a.pincode asc order by a.pincode asc ");
            
            
            foreach ($query->result_array() as $row)
            {
             $values[$row['pincode']] = $row['pincode'] . ' - ' . $row['area'];    
            }  
            
             header('Content-Type: application/x-json; charset=utf-8');
            
            echo (json_encode($values));
    }
    
    public function get_ajax_country_list()
    {             
             
        $query = $this->db->query("select a.country_id , a.country_name from rh_country_info as a where a.status = 'Active' order by a.country_name asc ");
        
        
        foreach ($query->result_array() as $row)
        {
         $values[$row['country_id']] = $row['country_name'] ;    
        }  
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($values));
    }
    
    public function get_ajax_pincode_list()
    { 
            
            $pin = $this->input->get('term');
            
            //$query = $this->db->query("select a.pincode ,a.area_name as area from crit_pincode_info as a where a.pincode like '". $pin."%' order by a.pincode , a.area_name asc ");
            $query = $this->db->query("select a.pincode from crit_pincode_info as a where a.pincode like '". $pin."%' group by a.pincode order by a.pincode ");
            
            
            foreach ($query->result_array() as $row)
            {
             //$values[$row['pincode'] . ' - ' . $row['area']] = $row['pincode'] . ' - ' . $row['area'];    
             $values[$row['pincode']] = $row['pincode'];    
            }  
            
             header('Content-Type: application/x-json; charset=utf-8');
            
            echo (json_encode($values));
    }
    
    public function get_ajax_pin_list($area)
    {             
             
        $query = $this->db->query("select a.pincode from rh_pincode_list as a where a.area = '". $area."' group by a.pincode ");
        
        
        foreach ($query->result_array() as $row)
        {
         $values[$row['pincode']] = $row['pincode'] ;    
        }  
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($values));
    }
    
	public function complaint_register()
    {
            $msg  = "Customer: " . $this->input->post('customer_name') . "\n"; 
            $msg .= "Mobile: " . $this->input->post('mobile') . "\n"; 
            $msg .= "Email: " . $this->input->post('email') . "\n"; 
            $msg .= "Type Of Complaint: " . $this->input->post('complaint_type') . "\n"; 
            $msg .= "AWB No: " . $this->input->post('awb_no') . "\n"; 
            $msg .= "City: " . $this->input->post('city') . "\n"; 
            $msg .= "Message: " . $this->input->post('message') . "\n"; 
            
            
             
        
            $this->load->library('email');
                
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            //$config['mailtype'] = 'html';
            
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';  
            
            
            $this->email->initialize($config);
    
            $this->email->from($this->input->post('email'), $this->input->post('customer_name'));
            $this->email->to('marketing@pickmycourier.com');
            //$this->email->to('selvanramesh@gmail.com');
            $this->email->cc('it@pickmycourier.com ,  sm@pickmycourier.com , operations@pickmycourier.com');  
            
            $this->email->subject('Pick My Courier - Complaint Register');
            $this->email->message($msg);
            
            $this->email->send();
            
            
            $this->email->from('marketing@pickmycourier.com');
            $this->email->to($this->input->post('email'));
            $this->email->bcc('it@pickmycourier.com ,  sm@pickmycourier.com, operations@pickmycourier.com'); 
            
            $this->email->subject('Pick My Courier - Complaint Register Successfully! ');
            
            $addt = "Thanks for regsitering a Complaint . Soon Our Team will Contact you and rectify the issue. \n Regard,\n PMC Team\n ";
            
            $this->email->message($msg . "\n" . $addt);
            
            $this->email->send(); 
            
            // $addt = "Thanks for regsitering a Complaint . Soon Our Team will Contact you and rectify the issue. \n Regard,\n PMC Team\n ";
            
            
             
			
            echo "OK"; 
            
    }
    
    public function mail_test()
    {
           
           /* $msg  = "Customer: Test "; 
        
            $this->load->library('email');
                
             //$config['charset'] = 'iso-8859-1';
             //$config['wordwrap'] = TRUE;
             //$config['mailtype'] = 'html';
            //$config['protocol'] = 'mail';
             
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            
            $this->email->initialize($config);
             
           /* $this->email->from($this->input->post('email'), $this->input->post('customer_name'));
            $this->email->to('marketing@pickmycourier.com');
            //$this->email->to('selvanramesh@gmail.com');
            $this->email->cc('it@pickmycourier.com ,  sm@pickmycourier.com , operations@pickmycourier.com');  
            
            $this->email->subject('Pick My Courier - Complaint Register');
            $this->email->message($msg);
            
           // $this->email->send();
             * /
            
            $this->email->from('marketing@pickmycourier.com');
            //$this->email->to($this->input->post('email'));
            $this->email->to('crinfotechcbe@gmail.com');
            $this->email->bcc('it@pickmycourier.com'); 
            
            $this->email->subject('Pick My Courier - Complaint Register Successfully! ' . date('Y-m-d H:i a'));
            
            $addt = "Thanks for regsitering a Complaint . Soon Our Team will Contact you and rectify the issue. \n Regard,\n PMC Team\n ";
            
            $this->email->message($msg . "\n" . $addt);
            
            $this->email->send(); 
			
            //echo "OK"; 
            
            if ( ! $this->email->send())
            {
                echo "Failed <br>"; 
                
                echo $this->email->print_debugger(array('headers'));
            } else {
                 echo "OK"; 
            }  
            
               /* $to = "it@pickmycourier.com";
                $subject = "My subject";
                $txt = "Hello world!";
                $headers = "From: selvanramesh@gmail.com" . "\r\n" .
                "CC: crinfotechcbe@gmail.com";
                
                echo "RET : " . mail($to,$subject,$txt,$headers);  */
            
            
            $this->load->library('email');
            
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            
            $this->email->initialize($config);

            $this->email->from('selvanramesh@gmail.com', 'Tamilselvan');
            $this->email->to('crinfotechcbe111@gmail.com');
            //$this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');
            
            $this->email->subject('Email Test');
            $this->email->message('Testing the email class.');
             
            
            if (!$this->email->send())
            {    
                echo $this->email->print_debugger(); 
            } else {
                echo "Mail Send sts";
                
                echo "werqwer" . $this->email->print_debugger(); 
            }     
                
                
                
            
    }
	
    public function pick_up_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        
        if($this->input->post('mode') == 'Manual')
        {
           /* $ins = array(
                        'courier_type' => $this->input->post('courier_type'),
                        'source_pincode' => $this->input->post('source_pincode'),
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),
                        'destination_pincode' => $this->input->post('destination_pincode'),                       
                        'destination_country' => $this->input->post('destination_country'),                       
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'package_type' => $this->input->post('package_type') ,
                        'package_weight' => $this->input->post('package_weight') ,
                        'package_weight_int' => $this->input->post('package_weight_int') ,
                        'package_length' => $this->input->post('package_length') ,
                        'package_width' => $this->input->post('package_width') ,
                        'package_height' => $this->input->post('package_height') ,
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'remarks' => $this->input->post('remarks') ,
                        'same_as_sender_address' => $this->input->post('same_as_sender_address') ,
                        'contact_person_name' => $this->input->post('contact_person_name') ,
                        'contact_person_mobile' => $this->input->post('contact_person_mobile') ,
                        'pickup_address' => $this->input->post('pickup_address'),
                        'approx_charges' => $this->input->post('approx_charges'),
                        'transport_mode' => $this->input->post('transport_mode'),
                        'packing_required' => $this->input->post('packing_required'),
                        'special_instruction' => $this->input->post('special_instruction'),
                        'booked_date' => date('Y-m-d H:i:s') 
                                             
                );                
          $this->db->insert('rh_pickup_info', $ins); */
          
          $this->bookmycourier('1');
          
          redirect('pickup-list/' .$this->uri->segment(2, 0));          
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            //$old_pay_status = $this->input->post('old_pay_status');
            /*if(($this->input->post('old_pay_status') != $this->input->post('pay_status')) and ($this->input->post('pay_status') == 'Paid') )
            {
                $paid_date = date('Y-m-d');
            } else {
                $paid_date = '';
            }*/
            
             //'paid_date' => $paid_date,
            
            
            $upd= array(
                        'courier_type' => ($this->input->post('courier_type')),
                        'source_pincode' => ($this->input->post('source_pincode')),
                        'sender_name' => ($this->input->post('sender_name')),
                        'sender_phone' => ($this->input->post('sender_phone')),
                        'sender_address' => ($this->input->post('sender_address')),
                        'destination_pincode' => ($this->input->post('destination_pincode')),                       
                        'destination_country' => ($this->input->post('destination_country')),                       
                        'receiver_name' => ($this->input->post('receiver_name')),                       
                        'receiver_phone' => ($this->input->post('receiver_phone')) ,
                        'receiver_address' => ($this->input->post('receiver_address')) ,
                        'package_type' => ($this->input->post('package_type')) ,
                        'package_weight' => ($this->input->post('package_weight')) ,
                        'package_weight_int' => ($this->input->post('package_weight_int')) ,
                        'package_length' => ($this->input->post('package_length')) ,
                        'package_width' => ($this->input->post('package_width')) ,
                        'package_height' => ($this->input->post('package_height')) ,
                        'package_purpose' => ($this->input->post('package_purpose')) ,
                        'package_value' => ($this->input->post('package_value')) ,
                        'remarks' => ($this->input->post('remarks')) ,
                        'same_as_sender_address' => ($this->input->post('same_as_sender_address')) ,
                        'contact_person_name' => ($this->input->post('contact_person_name')) ,
                        'contact_person_mobile' => ($this->input->post('contact_person_mobile')) ,
                        'pickup_address' => ($this->input->post('pickup_address')),
                        'approx_charges' => ($this->input->post('approx_charges')),
                        'transport_mode' => ($this->input->post('transport_mode')),
                        'packing_required' => ($this->input->post('packing_required')),
                        'special_instruction' => ($this->input->post('special_instruction')),
                        'pickup_schedule_timing' => ($this->input->post('pickup_schedule_timing')),
                        'service_provider_id' => ($this->input->post('service_provider_id')),
                        'bill_no' => ($this->input->post('bill_no')),
                        'courier_charges' => ($this->input->post('courier_charges'))  ,
                        'no_of_pcs' => ($this->input->post('no_of_pcs'))  ,
                        'pickup_weight' => ($this->input->post('pickup_weight'))  ,
                        'pickup_date' => ($this->input->post('pickup_date'))  ,
                        'delivered_date' => ($this->input->post('delivered_date'))  ,
                        'ecpl_amt' => ($this->input->post('ecpl_amt'))  ,
                        'pmc_amt' => ($this->input->post('pmc_amt'))  ,
                        'status' => ($this->input->post('status')) ,
                        'pay_status' => ($this->input->post('pay_status')) ,
                        //'tracking_status' => ($this->input->post('pay_status')) ,
                        'pay_method_id' => ($this->input->post('pay_method_id')), 
                        'paid_date' => ($this->input->post('paid_date')),
                        'assign_to' => ($this->input->post('assign_to'))
                                             
                );                
          $this->db->where('pickup_id', ($this->input->post('pickup_id')));  
          $this->db->update('rh_pickup_info', $upd);  
          
          //print_r($upd);
          
          redirect('pickup-list/'. $this->uri->segment(2, 0));
          
        }
        
        if($this->input->post('mode') == 'Pickup')
        {
            $ins = array(
                    'service_provider_id' => $this->input->post('service_provider_id'),
                    'bill_no' => $this->input->post('bill_no'),
                    'courier_charges' => $this->input->post('courier_charges')  ,
                    'status' => 'Picked'                      
            );
            
            $this->db->where('pickup_id',  $this->input->post('pickup_id'));
            $this->db->update('rh_pickup_info', $ins);
            
            redirect('pickup-list/' . $this->uri->segment(2, 0));
            
            //print_r($_POST);
        }
        
        
        $query = $this->db->query("select a.service_provider_id,  a.service_provider_name  from rh_service_provider_info as a where a.status='Active'   order by  a.service_provider_name asc ");
        
        $data['service_provider_opt'] = array('' => 'Select Service Provider'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['service_provider_opt'][$row['service_provider_id']] = $row['service_provider_name']   ;    
        }  
        
        $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
        
        $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
        }   
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        $query = $this->db->query("select user_id, first_name from rh_user_info as a where a.status= 'Active' and level != '4' and a.user_id != '1'  order by first_name asc ");
        
        $data['staff_opt'][] = 'Select Staff';

        foreach ($query->result_array() as $row)
        {
            $data['staff_opt'][$row['user_id']] = $row['first_name'];     
        } 
        
        
        $query = $this->db->query("select country_name , country_id  from rh_country_info as a where a.status= 'Active' order by country_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['destination_country_opt'][$row['country_id']] = $row['country_name'];     
        } 
        
        
        $data['js'] = 'pickup-list.inc'; 
        
        
        $data['status_opt'] = array(
                                    '' => 'All Status',
                                    'Booked' => 'Booked', 
                                    'Picked' => 'Picked', 
                                    'Delivered' => 'Delivered', 
                                    'Cancelled' => 'Cancelled', 
                                    );
                                    
                                    
       if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_frm_date')){
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } 
       
       
       if(isset($_POST['srch_status'])) { 
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ; 
           $this->session->set_userdata('srch_status', $this->input->post('srch_status')); 
       }
       elseif($this->session->userdata('srch_status')){ 
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ; 
       } 
       
       if(isset($_POST['srch_state'])) {
           
           $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;  
           $this->session->set_userdata('srch_state', $this->input->post('srch_state')); 
       }
       elseif($this->session->userdata('srch_state')){  
           $data['srch_state'] = $srch_state= $this->session->userdata('srch_state') ; 
       }
       if(isset($_POST['src_ref_no'])) { 
           $data['src_ref_no'] = $src_ref_no = $this->input->post('src_ref_no') ;   
           $this->session->set_userdata('src_ref_no', $this->input->post('src_ref_no'));
       }
       elseif($this->session->userdata('src_ref_no')){   
           $data['src_ref_no'] = $src_ref_no= $this->session->userdata('src_ref_no') ;
       }  
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_state))
       { 
         $data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
       if(empty($src_ref_no))
       { 
         $data['src_ref_no'] = $src_ref_no = ''; 
       } 
         
        $this->load->library('pagination');
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
        if(!empty($srch_status)) {
            $this->db->where('a.status =', $srch_status); 
        }
        $this->db->where('a.status != ', 'Delete');
        if(!empty($src_ref_no))
           // $this->db->where('a.pickup_id = ', $src_ref_no);
            $this->db->where("( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' )");
        if(empty($src_ref_no))
            $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'"); 
        
        $this->db->from('rh_pickup_info as a');   
        
        if($this->session->userdata('m_is_admin') == USER_PICKUP) { 
            
            $pstate = $this->session->userdata('m_pstate');
            $pcity = $this->session->userdata('m_pcity');            
            
            $this->db->join('crit_pincode_info as b', 'b.pincode = a.source_pincode' , 'left');
            $this->db->where("b.state_name" , $pstate);
            if(!empty($srch_state))
                $this->db->where("b.state_name" , $srch_state);
            $this->db->where("b.district_name" , $pcity);
        } else {
            if(!empty($srch_state)) {
                $this->db->join('(select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as b', 'b.pincode = a.source_pincode' , 'left'); 
                $this->db->where("b.state_name" , $srch_state);
                //$this->db->group_by('b.pincode');
            }    
        }
        //$this->db->query('SET SQL_BIG_SELECTS=1');
        $this->db->group_by('a.pickup_id');
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('pickup-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  
        
        
        if(!empty($srch_state))
                $whr_state = " and e.state_name = '" . $srch_state. "'";
            else
                $whr_state = "";
         
         if($this->session->userdata('m_is_admin') == USER_PICKUP) { 
            
            
            $sql = "
                select  
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '||' , a.contact_person_mobile , '||', a.pickup_address ),concat(a.sender_name , '||' , a.sender_phone , '||', a.sender_address )) as pickup_address,
                (ifnull(a.source_pin_area, a.source_pincode)) as origin1,
                concat(a.source_pincode,' ', e.state_name,' [ ' , e.district_name, ' ]' ) as origin,
                if (a.courier_type = 'Domestic' , ifnull(a.destination_pin_area, a.destination_pincode)  , d.country_name ) as destination ,
                concat( ifnull(a.source_pin_area, a.source_pincode) , '||' , a.sender_name , '||' , a.sender_phone , '||', a.sender_address ) as source_address,
                 a.package_type,
                a.package_weight,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.approx_charges,
                a.courier_charges,
                a.status,
                a.booking_type,
                a.transport_mode,
                a.pay_status,
                ifnull(a.pmc_amt,0) as pmc_amt,
                a.paid_date ,
                a.delivered_date,
                a.tracking_status,
                a.remarks ,
                a.bill_no              
                from rh_pickup_info as a 
                left join rh_country_info as d on d.country_id = a.destination_country 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as e on e.pincode = a.source_pincode
                where 1
                and ". ( (empty($src_ref_no)) ? " DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'" : "1=1" )."
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and e.state_name = '". $pstate ."' and e.district_name = '". $pcity ."' 
                and ". ( (!empty($src_ref_no)) ? " ( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' ) " : "1=1" )."
                $whr_state
                group by a.pickup_id 
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
         } else {
        
        $sql = "
                select  
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '||' , a.contact_person_mobile , '||', a.pickup_address ),concat(a.sender_name , '||' , a.sender_phone , '||', a.sender_address )) as pickup_address,
                (ifnull(a.source_pin_area, a.source_pincode)) as origin1,
                concat(a.source_pincode, ' [ ' , e.district_name, ' ]' ) as origin,
                if(a.courier_type = 'Domestic' , ifnull(a.destination_pin_area, a.destination_pincode)  , d.country_name ) as destination ,
                concat( ifnull(a.source_pin_area, a.source_pincode) , '||' , a.sender_name , '||' , a.sender_phone , '||', a.sender_address ) as source_address,
                 a.package_type,
                a.package_weight,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.approx_charges,
                a.courier_charges,
                a.status,
                a.pay_status,
                a.booking_type,
                a.transport_mode,
                ifnull(a.pmc_amt,0) as pmc_amt,
                a.paid_date,
                a.delivered_date,
                a.tracking_status,
                a.remarks ,
                a.bill_no 
                from rh_pickup_info as a 
                left join rh_country_info as d on d.country_id = a.destination_country 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as e on e.pincode = a.source_pincode
                where 1 
                and ". ( (empty($src_ref_no)) ? " DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'" : "1=1" )."
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1=1" )." 
                and ". ( (!empty($src_ref_no)) ? " ( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' ) " : "1=1" )."
                $whr_state
                group by a.pickup_id
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        // and DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
        }
        
        //a.status = 'Booked'  
        
        //echo $sql;
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pickup-list',$data); 
	}
    
    
    public function pick_up_list_v2()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        
        if($this->input->post('mode') == 'Add PMCL Tracking')
        {
            $tracking_status = $this->input->post('tracking_status');
            $pickup_id = $this->input->post('pickup_id');
            $flg_sms = $this->input->post('flg_sms');
            
            $ins = array(
                    'pickup_id' => $this->input->post('pickup_id'),
                    'tracking_status' => $this->input->post('tracking_status'),
                    'city' => $this->input->post('city')  ,
                    'status_datetime' => $this->input->post('status_datetime'),                      
                    'remarks' => $this->input->post('remarks'), 
                    'created_by' => $this->session->userdata('m_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s') ,                
            );
            
            //$this->db->where('pickup_id',  $this->input->post('pickup_id'));
            $this->db->insert('crit_pmc_tracking_info', $ins); 
            
            $this->db->where('pickup_id', $pickup_id);
            if($this->input->post('tracking_status') == 'Delivered'){            
                $this->db->update('rh_pickup_info', array('tracking_status' => $this->input->post('tracking_status') , 'status' => $this->input->post('tracking_status'), 'delivered_date' => $this->input->post('status_datetime') )); 
            } else {
                $this->db->update('rh_pickup_info', array('tracking_status' => $this->input->post('tracking_status') , 'status' => $this->input->post('tracking_status'))); 
            }
            
            if($flg_sms == 1){
                
                
                $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
                if($this->input->post('tracking_status') == 'Picked') {
                    $sms_text = sprintf(SMS_TS_PICKED,str_pad($pickup_id,5,0,STR_PAD_LEFT),str_pad($pickup_id,5,0,STR_PAD_LEFT));
                    
                    if(strlen($sms_mobile) == '10')
                        $this->send_sms($sms_mobile, $sms_text, '1507162987070345959');
                }
                if($this->input->post('tracking_status') == 'In-Transit'){ 
                    $sms_text = SMS_TS_TRANSIT; 
                    if(strlen($sms_mobile) == '10')
                        $this->send_sms($sms_mobile, $sms_text,'1507162997354689781');
                }
                if($this->input->post('tracking_status') == 'Out For Delivery'){ 
                    
                    $sms_text = sprintf(SMS_TS_OUT_FOR_DELI,str_pad($pickup_id,5,0,STR_PAD_LEFT) ,date('d-m-Y')); 
                    if(strlen($sms_mobile) == '10')
                        $this->send_sms($sms_mobile, $sms_text,'1507162997370532757'); 
                }
                if($this->input->post('tracking_status') == 'Delivered'){
                    $sms_text = sprintf(SMS_TS_DELIVERED,str_pad($pickup_id,5,0,STR_PAD_LEFT), date('d-m-Y H:i') );
            
                    if(strlen($sms_mobile) == '10')
                        $this->send_sms($sms_mobile, $sms_text,'1507162997824686122');
                }
                
                
            }
            
            redirect('pickup-list/' . $this->uri->segment(2, 0));
            
            //print_r($_POST);
        }
        
        if($this->input->post('mode') == 'Manual')
        {
           /* $ins = array(
                        'courier_type' => $this->input->post('courier_type'),
                        'source_pincode' => $this->input->post('source_pincode'),
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),
                        'destination_pincode' => $this->input->post('destination_pincode'),                       
                        'destination_country' => $this->input->post('destination_country'),                       
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'package_type' => $this->input->post('package_type') ,
                        'package_weight' => $this->input->post('package_weight') ,
                        'package_weight_int' => $this->input->post('package_weight_int') ,
                        'package_length' => $this->input->post('package_length') ,
                        'package_width' => $this->input->post('package_width') ,
                        'package_height' => $this->input->post('package_height') ,
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'remarks' => $this->input->post('remarks') ,
                        'same_as_sender_address' => $this->input->post('same_as_sender_address') ,
                        'contact_person_name' => $this->input->post('contact_person_name') ,
                        'contact_person_mobile' => $this->input->post('contact_person_mobile') ,
                        'pickup_address' => $this->input->post('pickup_address'),
                        'approx_charges' => $this->input->post('approx_charges'),
                        'transport_mode' => $this->input->post('transport_mode'),
                        'packing_required' => $this->input->post('packing_required'),
                        'special_instruction' => $this->input->post('special_instruction'),
                        'booked_date' => date('Y-m-d H:i:s') 
                                             
                );                
          $this->db->insert('rh_pickup_info', $ins); */
          
          $this->bookmycourier('1');
          
          redirect('pickup-list/' .$this->uri->segment(2, 0));          
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            //$old_pay_status = $this->input->post('old_pay_status');
            /*if(($this->input->post('old_pay_status') != $this->input->post('pay_status')) and ($this->input->post('pay_status') == 'Paid') )
            {
                $paid_date = date('Y-m-d');
            } else {
                $paid_date = '';
            }*/
            
             //'paid_date' => $paid_date,
            
            
            $upd= array(
                        'courier_type' => ($this->input->post('courier_type')),
                        'source_pincode' => ($this->input->post('source_pincode')),
                        'sender_name' => ($this->input->post('sender_name')),
                        'sender_phone' => ($this->input->post('sender_phone')),
                        'sender_address' => ($this->input->post('sender_address')),
                        'destination_pincode' => ($this->input->post('destination_pincode')),                       
                        'destination_country' => ($this->input->post('destination_country')),                       
                        'receiver_name' => ($this->input->post('receiver_name')),                       
                        'receiver_phone' => ($this->input->post('receiver_phone')) ,
                        'receiver_address' => ($this->input->post('receiver_address')) ,
                        'package_type' => ($this->input->post('package_type')) ,
                        'package_weight' => ($this->input->post('package_weight')) ,
                        'package_weight_int' => ($this->input->post('package_weight_int')) ,
                        'package_length' => ($this->input->post('package_length')) ,
                        'package_width' => ($this->input->post('package_width')) ,
                        'package_height' => ($this->input->post('package_height')) ,
                        'package_purpose' => ($this->input->post('package_purpose')) ,
                        'package_value' => ($this->input->post('package_value')) ,
                        'remarks' => ($this->input->post('remarks')) ,
                        'same_as_sender_address' => ($this->input->post('same_as_sender_address')) ,
                        'contact_person_name' => ($this->input->post('contact_person_name')) ,
                        'contact_person_mobile' => ($this->input->post('contact_person_mobile')) ,
                        'pickup_address' => ($this->input->post('pickup_address')),
                        'approx_charges' => ($this->input->post('approx_charges')),
                        'transport_mode' => ($this->input->post('transport_mode')),
                        'packing_required' => ($this->input->post('packing_required')),
                        'special_instruction' => ($this->input->post('special_instruction')),
                        'pickup_schedule_timing' => ($this->input->post('pickup_schedule_timing')),
                        'service_provider_id' => ($this->input->post('service_provider_id')),
                        'bill_no' => ($this->input->post('bill_no')),
                        'courier_charges' => ($this->input->post('courier_charges'))  ,
                        'no_of_pcs' => ($this->input->post('no_of_pcs'))  ,
                        'pickup_weight' => ($this->input->post('pickup_weight'))  ,
                        'pickup_date' => ($this->input->post('pickup_date'))  ,
                        'delivered_date' => ($this->input->post('delivered_date'))  ,
                        'ecpl_amt' => ($this->input->post('ecpl_amt'))  ,
                        'pmc_amt' => ($this->input->post('pmc_amt'))  ,
                        'status' => ($this->input->post('status')) ,
                        'pay_status' => ($this->input->post('pay_status')) ,
                        //'tracking_status' => ($this->input->post('pay_status')) ,
                        'pay_method_id' => ($this->input->post('pay_method_id')), 
                        'paid_date' => ($this->input->post('paid_date')),
                        'assign_to' => ($this->input->post('assign_to'))
                                             
                );                
          $this->db->where('pickup_id', ($this->input->post('pickup_id')));  
          $this->db->update('rh_pickup_info', $upd);  
          
          //print_r($upd);
          
          redirect('pickup-list/'. $this->uri->segment(2, 0));
          
        }
        
        if($this->input->post('mode') == 'Pickup')
        {
            $ins = array(
                    'service_provider_id' => $this->input->post('service_provider_id'),
                    'bill_no' => $this->input->post('bill_no'),
                    'courier_charges' => $this->input->post('courier_charges')  ,
                    'status' => 'Picked'                      
            );
            
            $this->db->where('pickup_id',  $this->input->post('pickup_id'));
            $this->db->update('rh_pickup_info', $ins);
            
            redirect('pickup-list/' . $this->uri->segment(2, 0));
            
            //print_r($_POST);
        }
        
        
        $query = $this->db->query("select a.service_provider_id,  a.service_provider_name  from rh_service_provider_info as a where a.status='Active'   order by  a.service_provider_name asc ");
        
        $data['service_provider_opt'] = array('' => 'Select Service Provider'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['service_provider_opt'][$row['service_provider_id']] = $row['service_provider_name']   ;    
        }  
        
        $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
        
        $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
        }   
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        $query = $this->db->query("select user_id, first_name from rh_user_info as a where a.status= 'Active' and level != '4' and a.user_id != '1'  order by first_name asc ");
        
        $data['staff_opt'][] = 'Select Staff';

        foreach ($query->result_array() as $row)
        {
            $data['staff_opt'][$row['user_id']] = $row['first_name'];     
        } 
        
        
        $query = $this->db->query("select country_name , country_id  from rh_country_info as a where a.status= 'Active' order by country_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['destination_country_opt'][$row['country_id']] = $row['country_name'];     
        } 
        
        
        $data['js'] = 'pickup-list.inc'; 
        
        
       /* $data['status_opt'] = array(
                                    '' => 'All Status',
                                    'Booked' => 'Booked', 
                                    'Picked' => 'Picked', 
                                    'In-Transit' => 'In-Transit', 
                                    'Delivered' => 'Delivered', 
                                    'Cancelled' => 'Cancelled', 
                                    );*/
                                    
        $query = $this->db->query("
            select 
            a.tracking_status 
            from rh_pickup_info as a 
            where 1=1 
            group by a.tracking_status
            order by a.tracking_status
         ");
        
        $data['status_opt'] = array('' => 'All Status');

        foreach ($query->result_array() as $row)
        {
            $data['status_opt'][$row['tracking_status']] = $row['tracking_status'];     
        }                            
                                    
                                    
       if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_frm_date')){
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } 
       
       
       if(isset($_POST['srch_status'])) { 
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ; 
           $this->session->set_userdata('srch_status', $this->input->post('srch_status')); 
       }
       elseif($this->session->userdata('srch_status')){ 
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ; 
       } 
       
       if(isset($_POST['srch_state'])) {
           
           $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;  
           $this->session->set_userdata('srch_state', $this->input->post('srch_state')); 
       }
       elseif($this->session->userdata('srch_state')){  
           $data['srch_state'] = $srch_state= $this->session->userdata('srch_state') ; 
       }
       if(isset($_POST['src_ref_no'])) { 
           $data['src_ref_no'] = $src_ref_no = $this->input->post('src_ref_no') ;   
           $this->session->set_userdata('src_ref_no', $this->input->post('src_ref_no'));
       }
       elseif($this->session->userdata('src_ref_no')){   
           $data['src_ref_no'] = $src_ref_no= $this->session->userdata('src_ref_no') ;
       }  
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_state))
       { 
         $data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
       if(empty($src_ref_no))
       { 
         $data['src_ref_no'] = $src_ref_no = ''; 
       } 
         
        $this->load->library('pagination');
        
        $this->db->query('SET SQL_BIG_SELECTS=1'); 
        
        if(!empty($srch_status)) {
            //$this->db->where('a.status =', $srch_status); 
            $this->db->where('( a.status ="' . $srch_status . '" or a.tracking_status = "' . $srch_status .'" )');    
        }
        $this->db->where('a.status != ', 'Delete');
        if(!empty($src_ref_no))
           // $this->db->where('a.pickup_id = ', $src_ref_no);
            $this->db->where("( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' )");
        if(empty($src_ref_no))
            $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'"); 
        
        $this->db->from('rh_pickup_info as a');   
        
        if($this->session->userdata('m_is_admin') == USER_PICKUP) { 
            
            $pstate = $this->session->userdata('m_pstate');
            $pcity = $this->session->userdata('m_pcity');            
            
            $this->db->join('crit_pincode_info as b', 'b.pincode = a.source_pincode' , 'left');
            $this->db->where("b.state_name" , $pstate);
            if(!empty($srch_state))
                $this->db->where("b.state_name" , $srch_state);
            $this->db->where("b.district_name" , $pcity);
        } else {
            if(!empty($srch_state)) {
                $this->db->join('(select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as b', 'b.pincode = a.source_pincode' , 'left'); 
                $this->db->where("b.state_name" , $srch_state);
                //$this->db->group_by('b.pincode');
            }    
        }
        //$this->db->query('SET SQL_BIG_SELECTS=1');
        $this->db->group_by('a.pickup_id');
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('pickup-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  
        
        
        if(!empty($srch_state))
                $whr_state = " and e.state_name = '" . $srch_state. "'";
            else
                $whr_state = "";
         
         if($this->session->userdata('m_is_admin') == USER_PICKUP) { 
            
            
            $sql = "
                select  
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '||' , a.contact_person_mobile , '||', a.pickup_address ),concat(a.sender_name , '||' , a.sender_phone , '||', a.sender_address )) as pickup_address,
                (ifnull(a.source_pin_area, a.source_pincode)) as origin1,
                concat(a.source_pincode,' ', e.state_name,' [ ' , e.district_name, ' ]' ) as origin,
                if (a.courier_type = 'Domestic' , ifnull(a.destination_pin_area, a.destination_pincode)  , d.country_name ) as destination ,
                concat( ifnull(a.source_pin_area, a.source_pincode) , '||' , a.sender_name , '||' , a.sender_phone , '||', a.sender_address ) as source_address,
                 a.package_type,
                a.package_weight,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.approx_charges,
                a.courier_charges,
                a.status,
                a.booking_type,
                a.transport_mode,
                a.pay_status,
                ifnull(a.pmc_amt,0) as pmc_amt,
                a.paid_date ,
                a.delivered_date,
                a.tracking_status,
                a.remarks ,
                a.bill_no              
                from rh_pickup_info as a 
                left join rh_country_info as d on d.country_id = a.destination_country 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as e on e.pincode = a.source_pincode
                where 1
                and ". ( (empty($src_ref_no)) ? " DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'" : "1=1" )."
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and e.state_name = '". $pstate ."' and e.district_name = '". $pcity ."' 
                and ". ( (!empty($src_ref_no)) ? " ( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' ) " : "1=1" )."
                $whr_state
                group by a.pickup_id 
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
         } else {
        
        $sql = "
                select  
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '||' , a.contact_person_mobile , '||', a.pickup_address ),concat(a.sender_name , '||' , a.sender_phone , '||', a.sender_address )) as pickup_address,
                (ifnull(a.source_pin_area, a.source_pincode)) as origin1,
                concat(a.source_pincode, ' [ ' , e.district_name, ' ]' ) as origin,
                if(a.courier_type = 'Domestic' , ifnull(a.destination_pin_area, a.destination_pincode)  , d.country_name ) as destination ,
                concat( ifnull(a.source_pin_area, a.source_pincode) , '||' , a.sender_name , '||' , a.sender_phone , '||', a.sender_address ) as source_address,
                 a.package_type,
                a.package_weight,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.approx_charges,
                a.courier_charges,
                a.status,
                a.pay_status,
                a.booking_type,
                a.transport_mode,
                ifnull(a.pmc_amt,0) as pmc_amt,
                a.paid_date,
                a.delivered_date,
                a.tracking_status,
                a.remarks ,
                a.bill_no 
                from rh_pickup_info as a 
                left join rh_country_info as d on d.country_id = a.destination_country 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as e on e.pincode = a.source_pincode
                where 1 
                and ". ( (empty($src_ref_no)) ? " DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'" : "1=1" )."
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " (a.status = '". $srch_status."' or a.tracking_status = '". $srch_status."') " : "1=1" )."
                and ". ( (!empty($src_ref_no)) ? " ( a.pickup_id = '". $src_ref_no."' or a.receiver_phone like '%". $src_ref_no."%' or a.sender_phone like '%". $src_ref_no."%' ) " : "1=1" )."
                $whr_state
                group by a.pickup_id
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        // and DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
        }
        
        //a.status = 'Booked'  
        
        //echo $sql;
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['tracking_opt'] = array(
                                    'Booked' => 'Booked',
                                    'Picked' => 'Picked',
                                    'In-Transit' => 'In-Transit',
                                    'Out For Delivery' => 'Out For Delivery',
                                    'Delivered' => 'Delivered',
                                );
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pickup-list-v2',$data); 
	}
    
    public function pb_pick_up_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        if($this->input->post('mode') == 'Pickup')
        {
            $ins = array(
                    'service_provider_id' => $this->input->post('service_provider_id'),
                    'bill_no' => $this->input->post('bill_no'),
                    'courier_charges' => $this->input->post('courier_charges')  ,
                    'status' => 'Picked'                      
            );
            
            $this->db->where('pickup_id',  $this->input->post('pickup_id'));
            $this->db->update('rh_pickup_info', $ins);
            
            redirect('pb-pickup-list/' . $this->uri->segment(2, 0));
            
            //print_r($_POST);
        }
        
        
        $query = $this->db->query("select a.service_provider_id,  a.service_provider_name  from rh_service_provider_info as a where a.status='Active'   order by  a.service_provider_name asc ");
        
        $data['service_provider_opt'] = array('' => 'Select Service Provider'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['service_provider_opt'][$row['service_provider_id']] = $row['service_provider_name']   ;    
        }  
        
         
        
         
        
        $data['js'] = 'pickup-list.inc'; 
        
        
        $data['status_opt'] = array(
                                    '' => 'All Status',
                                    'Booked' => 'Booked', 
                                    'Picked' => 'Picked', 
                                    'Delivered' => 'Delivered', 
                                    'Cancelled' => 'Cancelled', 
                                    );
                                    
                                    
       if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
          // $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_status', $this->input->post('srch_status'));
           //$this->session->set_userdata('srch_state', $this->input->post('srch_state'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ;
           //$data['srch_state'] = $srch_state= $this->session->userdata('srch_state') ;
       } 
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       /*if(empty($srch_state))
       { 
         $data['srch_state'] = $srch_state = ''; 
       }*/
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
        
         
        $this->load->library('pagination');
        
        
        
        if(!empty($srch_status)) {
            $this->db->where('a.status = ', $srch_status); 
        }
        $this->db->where('a.status != ', 'Delete');
        $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        
        $this->db->from('rh_pickup_info as a');   
        
            $pstate = $this->session->userdata('m_pstate');
            $pcity = $this->session->userdata('m_pcity');            
            
            $this->db->join('( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as b', 'b.pincode = a.source_pincode' , 'left');
            $this->db->where("b.state_name" , $pstate); 
            if(!empty($pcity))
                $this->db->where("b.district_name" , $pcity);
        
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('pb-pickup-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  
        
        
            if(!empty($pcity))
            $whr_state = " and e.district_name = '". $pcity ."'";
            else
            $whr_state = " and 1 ";
         
              
            $sql = "
                select  
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '||' , a.contact_person_mobile , '||', a.pickup_address ),concat(a.sender_name , '||' , a.sender_phone , '||', a.sender_address )) as pickup_address,
                (ifnull(a.source_pin_area, a.source_pincode)) as origin1,
                concat(a.source_pincode,' ', e.state_name,' [ ' , e.district_name, ' ]' ) as origin,
                if (a.courier_type = 'Domestic' , ifnull(a.destination_pin_area, a.destination_pincode)  , d.country_name ) as destination ,
                concat( ifnull(a.source_pin_area, a.source_pincode) , '||' , a.sender_name , '||' , a.sender_phone , '||', a.sender_address ) as source_address,
                 a.package_type,
                a.package_weight,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.status,
                a.booking_type,
                a.transport_mode        
                from rh_pickup_info as a 
                left join rh_country_info as d on d.country_id = a.destination_country 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as e on e.pincode = a.source_pincode
                where DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and e.state_name = '". $pstate ."' 
                $whr_state
                group by a.pickup_id 
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pb-pickup-list',$data); 
	}
    
    public function pick_pack_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        if($this->session->userdata('m_is_admin') == USER_PICKPACK_CUST ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pick-pack.inc';  
        
        
       
       
        
        if($this->input->post('mode') == 'Add')
        {
            //$p_area = '';
            //list($pincode, $p_area) = explode(' - ',$this->input->post('src_pincode'));
            
            $ins = array(
                    'book_date' => $this->input->post('book_date'),
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'src_pincode' => $this->input->post('src_pincode'),
                    'pp_customer_ref_no' => $this->input->post('pp_customer_ref_no'),
                    //'src_area' => $p_area ,
                    'sender_name' => $this->input->post('sender_name'),
                    'sender_mobile' => $this->input->post('sender_mobile'),
                    'sender_address' => $this->input->post('sender_address'),
                    'packing_required' => $this->input->post('packing_required'),
                    'pp_charges' => $this->input->post('pp_charges'),
                    'packed_date' => $this->input->post('packed_date'),
                    'status' => $this->input->post('status'),                          
                    'package_weight' => $this->input->post('package_weight'),                          
                    'pay_status' => $this->input->post('pay_status'),                          
                    'pay_method_id' => $this->input->post('pay_method_id'),                          
                    'paid_date' => $this->input->post('paid_date'),                          
                    'created_by' => $this->session->userdata('m_user_id'),                          
                    'created_date' => date('Y-m-d H:i:s')  ,                          
            );
            
            $this->db->insert('crit_pick_pack_info', $ins); 
            redirect('pick-pack-list/'.$this->uri->segment(2, 0) );
        }
        
        if($this->input->post('mode') == 'Edit')
        {
           // $p_area = '';
            //list($pincode, $p_area) = explode(' - ',$this->input->post('src_pincode'));
            
            $upd = array(
                    'book_date' => $this->input->post('book_date'),
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'src_pincode' => $this->input->post('src_pincode'),
                    'pp_customer_ref_no' => $this->input->post('pp_customer_ref_no'),
                    //'src_area' => $p_area ,
                    'sender_name' => $this->input->post('sender_name'),
                    'sender_mobile' => $this->input->post('sender_mobile'),
                    'sender_address' => $this->input->post('sender_address'),
                    'packing_required' => $this->input->post('packing_required'),
                    'pp_charges' => $this->input->post('pp_charges'),
                    'packed_date' => $this->input->post('packed_date'),
                    'status' => $this->input->post('status'),  
                    'package_weight' => $this->input->post('package_weight'),                          
                    'pay_status' => $this->input->post('pay_status'),                          
                    'pay_method_id' => $this->input->post('pay_method_id'),                          
                    'paid_date' => $this->input->post('paid_date'),            
            );
            
            $this->db->where('pick_pack_id', $this->input->post('pick_pack_id'));
            $this->db->update('crit_pick_pack_info', $upd); 
                            
            redirect('pick-pack-list/' . $this->uri->segment(2, 0)); 
        } 
         
         
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
           $data['srch_customer'] = $srch_customer = $this->input->post('srch_customer') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_status', $this->input->post('srch_status'));
           $this->session->set_userdata('srch_customer', $this->input->post('srch_customer'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ;
           $data['srch_customer'] = $srch_customer= $this->session->userdata('srch_customer') ;
       } 
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_customer))
       { 
         $data['srch_customer'] = $srch_customer = ''; 
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
         
         
        $query = $this->db->query("select a.pp_customer_id,  a.company_name  from crit_pp_customer_info as a where a.status='Active'   order by  a.company_name asc ");
        
        $data['customer_opt'] = array('','Select Customer'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['customer_opt'][$row['pp_customer_id']] = $row['company_name']   ;    
        }  
       
        $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
        
        $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
        }
         
        $query = $this->db->query("select user_id, first_name from rh_user_info as a where a.status= 'Active' and level != '4' and a.user_id != '1'  order by first_name asc ");
        
        $data['staff_opt'][] = 'Select Staff';

        foreach ($query->result_array() as $row)
        {
            $data['staff_opt'][$row['user_id']] = $row['first_name'];     
        }  
         
        
        $this->load->library('pagination');
        
        if(!empty($srch_status)) {
            $this->db->where('a.status = ', $srch_status); 
        }
        if(!empty($srch_customer)) {
            $this->db->where('a.pp_customer_id = ', $srch_customer); 
        }
        $this->db->where('a.status != ', 'Delete');
        $this->db->where("DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        
        $this->db->from('crit_pick_pack_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pick-pack-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.pick_pack_id,
                a.pp_customer_ref_no,
                a.book_date,
                b.company_name as company ,
                a.src_pincode,
                a.src_area,
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                a.packing_required,            
                a.status,
                a.pay_status,
                a.paid_date
                from crit_pick_pack_info as a 
                left join crit_pp_customer_info as b on b.pp_customer_id = a.pp_customer_id
                where DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' 
                and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and ". ( (!empty($srch_customer)) ? " a.pp_customer_id = '". $srch_customer."' " : "1" )." 
                order by a.pick_pack_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pick-pack-list',$data); 
	} 
    
    
    public function customer_pick_pack_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        if($this->session->userdata('m_is_admin') != USER_PICKPACK_CUST ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'customer-pick-pack.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $p_area = '';
            //list($pincode, $p_area) = explode(' - ',$this->input->post('src_pincode'));
            
            $ins = array(
                    'book_date' => $this->input->post('book_date'),
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'pp_customer_ref_no' => $this->input->post('pp_customer_ref_no'),
                    'src_pincode' => $this->input->post('src_pincode'),
                    'src_area' => $p_area ,
                    'sender_name' => $this->input->post('sender_name'),
                    'sender_mobile' => $this->input->post('sender_mobile'),
                    'sender_address' => $this->input->post('sender_address'),
                    'packing_required' => $this->input->post('packing_required'), 
                    'status' => 'Booked',                          
                    'package_weight' => $this->input->post('package_weight'),                 
                    'created_by' => $this->session->userdata('m_user_id'),                          
                    'created_date' => date('Y-m-d H:i:s')  ,                          
            );
            
            $this->db->insert('crit_pick_pack_info', $ins);
            
            $pick_pack_id = str_pad($this->db->insert_id(),5,0,STR_PAD_LEFT);
            
            $query = $this->db->query("select a.company_name  from crit_pp_customer_info as a where a.status='Active' and a.pp_customer_id = '". $this->input->post('pp_customer_id') ."' order by  a.company_name asc ");
           
            foreach ($query->result_array() as $row)
            {
             $pp_company= $row['company_name']   ;    
            }  
            
            $msg =  $pp_company . "\n";
            $msg .= $this->input->post('sender_name') . "\n";
            $msg .= $this->input->post('sender_mobile') . "\n";
            $msg .= $this->input->post('sender_address') . "\n";
            
            $this->send_sms('6374711150',$msg); 
             
            redirect('customer-pick-pack-list/'.$this->uri->segment(2, 0) );
        }
        
        /*if($this->input->post('mode') == 'Edit')
        {
           // $p_area = '';
            //list($pincode, $p_area) = explode(' - ',$this->input->post('src_pincode'));
            
            $upd = array(
                    'book_date' => $this->input->post('book_date'),
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'src_pincode' => $this->input->post('src_pincode'),
                    //'src_area' => $p_area ,
                    'sender_name' => $this->input->post('sender_name'),
                    'sender_mobile' => $this->input->post('sender_mobile'),
                    'sender_address' => $this->input->post('sender_address'),
                    'packing_required' => $this->input->post('packing_required'), 
                    'package_weight' => $this->input->post('package_weight'),                          
                    'pay_status' => $this->input->post('pay_status'),                          
                    'pay_method_id' => $this->input->post('pay_method_id'),                          
                    'paid_date' => $this->input->post('paid_date'),            
            );
            
            $this->db->where('pick_pack_id', $this->input->post('pick_pack_id'));
            $this->db->update('crit_pick_pack_info', $upd); 
                            
            redirect('pick-pack-list/' . $this->uri->segment(2, 0)); 
        } */
         
         
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
          // $data['srch_customer'] = $srch_customer = $this->input->post('srch_customer') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_status', $this->input->post('srch_status'));
           //$this->session->set_userdata('srch_customer', $this->input->post('srch_customer'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ;
           //$data['srch_customer'] = $srch_customer= $this->session->userdata('srch_customer') ;
       } 
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       /*if(empty($srch_customer))
       { 
         $data['srch_customer'] = $srch_customer = ''; 
       }*/
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
         
         
        $query = $this->db->query("select a.pp_customer_id,  a.company_name  from crit_pp_customer_info as a where a.status='Active' and a.pp_customer_id = '". $this->session->userdata('m_pp_customer_id') ."' order by  a.company_name asc ");
        
        $data['customer_opt'] = array('','Select Customer'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['customer_opt'][$row['pp_customer_id']] = $row['company_name']   ;    
        }  
       
         
         
        
        $this->load->library('pagination');
        
        if(!empty($srch_status)) {
            $this->db->where('a.status = ', $srch_status); 
        }
       /* if(!empty($srch_customer)) {
            $this->db->where('a.pp_customer_id = ', $srch_customer); 
        } */
        $this->db->where('a.status != ', 'Delete');
        $this->db->where("DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        
        $this->db->from('crit_pick_pack_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('customer-pick-pack-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.pick_pack_id,
                a.pp_customer_ref_no,
                a.book_date, 
                a.src_pincode,
                a.src_area,
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                if(a.packing_required = '1', 'Yes','No') as packing_required,            
                a.status 
                from crit_pick_pack_info as a 
                left join crit_pp_customer_info as b on b.pp_customer_id = a.pp_customer_id
                where DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' 
                and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and   a.pp_customer_id = '". $this->session->userdata('m_pp_customer_id')."' 
                order by a.pick_pack_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('customer-pick-pack-list',$data); 
	} 
    
    public function pickup_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN  and $this->session->userdata('m_is_admin') != USER_MANAGER) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pickup-report.inc'; 
        
         
        $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
        $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
        $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;
         
          
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
        
          $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
          $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
          $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;
        
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
           
          
        /*$query = $this->db->query(" 
                select 
                a.pickup_id as ID, 
                a.bill_no as AWB,
                b.branch_code as origin,
                if(a.courier_type = 'Domestic' , c.branch_code ,d.country_name) as destination,
                a.no_of_pcs,
                a.pickup_weight as weight,
                a.pickup_date,
                a.delivered_date,
                a.courier_charges,
                a.ecpl_amt,
                a.pmc_amt
                from rh_pickup_info as a
                left join rh_pincode_list as b on b.pincode = a.source_pincode
                left join rh_pincode_list as c on c.pincode = a.destination_pincode
                left join rh_country_info as d on d.country_id = a.destination_country 
                where (a.`status`= 'Picked' or a.`status` = 'Delivered')
                and a.pickup_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
                order by a.pickup_date asc 
        ");*/
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
        $query = $this->db->query(" 
                select 
                a.pickup_id as ID, 
                a.bill_no as AWB,
                c.state_name as origin,
                if(a.courier_type = 'Domestic' , d.state_name ,d.country_name) as destination,
                a.no_of_pcs,
                a.pickup_weight as weight,
                a.pickup_date,
                a.delivered_date,
                a.courier_charges,
                a.ecpl_amt,
                a.pmc_amt
                from rh_pickup_info as a
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_country_info as d on d.country_id = a.destination_country 
                where (a.`status`= 'Picked' or a.`status` = 'Delivered')    
                and a.pickup_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '0' ? '( c.state_name = "'. $srch_state .'" or d.state_name = "'. $srch_state .'" )' : '1') . " 
                order by a.pickup_date asc 
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        }
         
        
        $this->excel->stream('Pickup-Report-'. $srch_frm_date.'-to-'. $srch_to_date . '-'. date('Ymdhis').'.xls', $export_data);
         
       }    
          
          
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
       
          
       
        /*if($this->input->post('state_code')!= '')        
            $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
        else            
            $data['state_code'] = $state_code = '' ;
        
        $query = $this->db->query("
                                     select 
                                        a.state_code,
                                        b.state_name as state, 
                                        a.area,
                                        a.branch_code
                                        from rh_pincode_list as a  
                                         left join rh_states_info as b on b.state_code = a.state_code
                                        where a.status = 'Active'  
                                        group by a.state_code , a.area
                                        order by b.state_name, a.area
                                ");
        
        $data['state_info'][] = 'Select';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state']][$row['area']] = $row['area'];     
        }  
	    //print_r($data['state_info']);
        */
        /*
         $data['sno'] = $this->uri->segment(2, 0);	
     
        
        $this->load->library('pagination');
        
        $this->db->where("(a.`status`= 'Picked' or a.`status` = 'Delivered')");
        $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        $this->db->from('rh_pickup_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        	
        $config['base_url'] = trim(site_url('pickup-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 100;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  
        */
      
       /*$sql = "
                select 
                a.pickup_id, 
                a.bill_no,
                b.branch_code as origin,
                if(a.courier_type = 'Domestic' , c.branch_code ,d.country_name) as destination,
                a.no_of_pcs,
                a.pickup_weight,
                a.pickup_date,
                a.delivered_date,
                a.courier_charges,
                a.ecpl_amt,
                a.pmc_amt
                from rh_pickup_info as a
                left join rh_pincode_list as b on b.pincode = a.source_pincode
                left join rh_pincode_list as c on c.pincode = a.destination_pincode
                left join rh_country_info as d on d.country_id = a.destination_country 
                where (a.`status`= 'Picked' or a.`status` = 'Delivered')
                and a.pickup_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and a.pickup_date != '0000-00-00'
                order by a.pickup_date asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";*/
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
        $sql = " 
                select 
                a.pickup_id, 
                a.bill_no,
                c.state_name as origin,
                if(a.courier_type = 'Domestic' , d.state_name ,d.country_name) as destination,
                a.no_of_pcs,
                a.pickup_weight,
                a.pickup_date,
                a.delivered_date,
                a.courier_charges,
                a.ecpl_amt,
                a.pmc_amt 
                from rh_pickup_info as a
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_country_info as d on d.country_id = a.destination_country 
                where (a.`status`= 'Picked' or a.`status` = 'Delivered')    
                and a.pickup_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '0' ? '( c.state_name = "'. $srch_state .'" or d.state_name = "'. $srch_state .'" )' : '1') . " 
                group by a.pickup_id  
                order by a.pickup_date asc  
                 
        " ;
        
       
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //$data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pickup-report',$data); 
	}
    
    public function pl_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pickup-report.inc'; 
        
         
        $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
        $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
        $data['srch_status'] = $srch_status = $this->input->post('srch_status') ; 
        
          
        $data['tracking_status_opt'] = array(
                                    '' => 'Select Status',
                                    'Booked' => 'Booked', 
                                    'Picked' => 'Picked', 
                                    'In-Transit' => 'In-Transit', 
                                    'Received-HUB' => 'Received-HUB', 
                                    'Out For Delivery' => 'Out For Delivery', 
                                    'Delivered' => 'Delivered' 
                                    );  
        
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
        //a.pickup_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
        
        
        if(!empty($srch_status ))
        {
            $whr = "a.tracking_status in ('". implode("','", $srch_status). "')";
            //echo $whr = "a.tracking_status in (" . $srch_status. ")";
        } else {
            $whr = " 1=1 ";
        }
        
        $sql = " 
                 select
                    a.pickup_id,
                    a.approx_charges as approx_charges,
                    a.courier_charges,
                    sum(c.pickup_charges + c.connection_charges + c.delivery_charges) as act_cost,
                    (a.courier_charges - sum(c.pickup_charges + c.connection_charges + c.delivery_charges)) as pl ,
                    a.tracking_status  
                    from rh_pickup_info as a
                    left join crit_tracking_info as c on c.pickup_id = a.pickup_id
                    where a.tracking_status != 'Cancelled' and  ". $whr ."
                    and DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                    and a.status != 'Delete' 
                    group by a.pickup_id 
                    order by a.pickup_id asc  
                " ;
        
     
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $this->load->view('pl-report',$data); 
	}
    
    public function booking_summary()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
         
        	    
        $data['js'] = 'pickup-report.inc'; 
        
         
        $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
        $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;  
        $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;  
        
          
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $whr = ' and 1';
        if(!empty($srch_state))
        {
            $whr = ' and b.state_name = "' . $srch_state . '"';
        }
        
        if(empty($srch_frm_date))
        {
            $data['srch_frm_date'] = $srch_frm_date = date('Y-m-01');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d');
        }
        
        $sql = " 
                select 
                    b.state_name as state,
                    b.district_name as area,
                    count(a.pickup_id) as cnt ,
                    sum(a.courier_charges) as courier_charges,
                    sum(a.approx_charges) as approx_charges 
                    from rh_pickup_info as a
                    left join ( select q.pincode, q.state_name, q.district_name from  crit_pincode_info as q group by q.pincode ) as b on b.pincode = a.source_pincode
                    where a.status != 'Delete' and a.status != 'Cancelled' and
                    DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                    $whr
                    group by b.state_name , b.district_name
                    order by b.state_name , b.district_name asc  
                " ;
        
     
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['state']][] = $row;     
        } 
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        //$data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        $this->load->view('booking-summary',$data); 
	}
    
    public function courier_booking_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN  and $this->session->userdata('m_is_admin') != USER_MANAGER) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pickup-report.inc'; 
        
        
        $data['status_opt'] = array(
                                    '' => 'All Status',
                                    'Booked' => 'Booked', 
                                    'Picked' => 'Picked', 
                                    'Delivered' => 'Delivered', 
                                    'Cancelled' => 'Cancelled', 
                                    );
        
                                    
                                    
       if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
           $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;
           //$this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
          // $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
          // $this->session->set_userdata('srch_status', $this->input->post('srch_status'));
       }
       /*elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ;
       } */ 
       else {
            $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d');
            $data['srch_to_date'] = $srch_to_date = date('Y-m-d');
            $data['srch_status'] = $srch_status = '';
            $data['srch_state'] = $srch_state = '';
       }                            
        
         
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
         // $this->excel->setActiveSheetIndexByName('Event-Register');
         
         $this->db->query('SET SQL_BIG_SELECTS=1');
          
        $query = $this->db->query(" 
                select DISTINCT
                a.pickup_id as Booking_ID,
                DATE_FORMAT(a.booked_date,'%d-%m-%Y') as booked_date, 
                a.courier_type,
                a.source_pincode,
                c.district_name as source_district,
                c.state_name as source_state,
                a.destination_pincode,
                if(a.courier_type = 'Domestic' , d.state_name ,e.country_name) as destination ,
                a.package_type,
                a.no_of_pcs,
                a.package_weight,
                a.transport_mode, 
                a.courier_charges,
                a.`status`
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_country_info as e on e.country_id = a.destination_country 
                where DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )."
                and  ". ($srch_state != '' ? '( c.state_name = "'. $srch_state .'" or d.state_name = "'. $srch_state .'" )' : '1') . " 
                group by a.pickup_id
                order by a.pickup_id asc 
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        }
        
        $this->excel->stream('Booking-Report-'. $srch_frm_date.'-to-'. $srch_to_date .'.xls', $export_data);
         
       }     
       
       
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
       
         
        
        //$data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
         
          
       
        /*if($this->input->post('srch_status')!= '')        
            $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
        else            
            $data['srch_status'] = $srch_status = '' ;*/
        /*
        $query = $this->db->query("
                                     select 
                                        a.state_code,
                                        b.state_name as state, 
                                        a.area,
                                        a.branch_code
                                        from rh_pincode_list as a  
                                         left join rh_states_info as b on b.state_code = a.state_code
                                        where a.status = 'Active'  
                                        group by a.state_code , a.area
                                        order by b.state_name, a.area
                                ");
        
        $data['state_info'][] = 'Select';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state']][$row['area']] = $row['area'];     
        }  
	    //print_r($data['state_info']);
        */
        
        /*$data['sno'] = $this->uri->segment(2, 0);	
     
        
        $this->load->library('pagination');
        if(!empty($srch_status))
            $this->db->where('a.status = ', $srch_status);
        $this->db->where('a.status != ', 'Delete');
        $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        $this->db->from('rh_pickup_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        	
        $config['base_url'] = trim(site_url('courier-booking-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 50;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  */
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
      
       $sql = "
                select DISTINCT
                a.pickup_id,
                DATE_FORMAT(a.booked_date,'%d-%m-%Y') as booked_date, 
                a.courier_type,
                a.source_pincode,
                c.district_name as source_district,
                c.state_name as source_state,
                a.destination_pincode,
                if(a.courier_type = 'Domestic' , d.state_name ,e.country_name) as destination ,
                a.package_type,
                a.no_of_pcs,
                a.package_weight,
                a.transport_mode, 
                a.courier_charges,
                a.`status`
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_country_info as e on e.country_id = a.destination_country 
                where DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )."
                and  ". ($srch_state != '' ? '( c.state_name = "'. $srch_state .'" or d.state_name = "'. $srch_state .'" )' : '1') . " 
                group by a.pickup_id
                order by a.pickup_id asc  
                       
        ";
        
        //a.status = 'Booked'  
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
       // $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('courier-booking-report',$data); 
	}
    
    public function franchise_enquiry_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'franchise-enquiry.inc';  
        
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $data['srch_state'] = $srch_state = $this->input->post('srch_state') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
           $data['srch_state'] = $srch_state= $this->session->userdata('srch_state') ;
       } 
        
        
       if(empty($srch_state))
       { 
         $data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
          
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
         // $this->excel->setActiveSheetIndexByName('Event-Register');
          
        $query = $this->db->query(" 
                select 
                DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d %h:%i %p') as enquiry_date, 
                a.contact_person_name as contact_person,
                a.email,
                a.mobile,
                a.interested_in,
                a.state,
                a.district,
                a.address,
                a.messages
                from rh_franchise_enquiry_info as a 
                where  DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '' ? '( a.state = "'. $srch_state .'")' : '1') . "
                and a.contact_person_name != ''
                order by a.franchise_enquiry_id desc
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        }
         
        
        $this->excel->stream('Franchise-Enquiry-Report-'. $srch_frm_date.'-to-'. $srch_to_date .'.xls', $export_data);
         
       }  
       
       $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
         
        
        $this->load->library('pagination');
        $this->db->where("a.contact_person_name != '' ");
        $this->db->where("DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        if($srch_state != '') {       
        $this->db->where('a.state = "'. $srch_state .'"');    
        }
        $this->db->from('rh_franchise_enquiry_info as a');   
       /* if($srch_state != '') {       
        $this->db->join('rh_states_info as b','on b.id = a.state_id','left' );    
        } */     
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        	
        $config['base_url'] = trim(site_url('franchise-enquiry-report'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.franchise_enquiry_id,
                DATE_FORMAT(a.franchise_enquiry_date,'%d-%m-%Y %h:%i %p') as enquiry_date,
                a.contact_person_name,
                a.email,
                a.mobile,
                a.interested_in,
                a.state as state,
                a.district as city,
                a.address,
                a.messages
                from rh_franchise_enquiry_info as a  
                where  DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '' ? '( a.state = "'. $srch_state .'")' : '1') . "
                and a.contact_person_name != ''
                order by a.franchise_enquiry_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('franchise-report',$data); 
	}
    
    public function agent_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'agent.inc';  
        
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $data['srch_agent'] = $srch_agent = $this->input->post('srch_agent') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
           $this->session->set_userdata('srch_agent', $this->input->post('srch_agent'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
           $data['srch_agent'] = $srch_agent= $this->session->userdata('srch_agent') ;
       } 
        
        
       if(empty($srch_agent))
       { 
         $data['srch_agent'] = $srch_agent = ''; 
        $where = " ( a.pickup_person_id != '' or a.delivery_person_id != '' )";
       }
       else {
        $where = " ( a.pickup_person_id = '". $srch_agent ."' or a.delivery_person_id = '". $srch_agent ."' )";
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m').'-01' ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
          
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
         // $this->excel->setActiveSheetIndexByName('Event-Register');
          
        $query = $this->db->query(" 
                select 
                a.pickup_id,
                b.source_pincode,
                b.destination_pincode,
                a.pickup_charges,
                a.connection_charges,
                a.delivery_charges
                from crit_tracking_info as a
                left join rh_pickup_info as b on b.pickup_id = a.pickup_id 
                where  DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '' ? '( b.state_name = "'. $srch_state .'")' : '1') . "
                order by a.franchise_enquiry_id desc
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        } 
        
        $this->excel->stream('Franchise-Enquiry-Report-'. $srch_frm_date.'-to-'. $srch_to_date .'.xls', $export_data);
         
       }  
       
        $query = $this->db->query("select a.agent_id,  a.agent_type , a.contact_person , a.state from crit_agent_info as a where a.status = 'Active'  order by a.state , a.contact_person asc ");
        
        $data['agent_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['agent_opt'][$row['state']][$row['agent_id']] = $row['contact_person'] . ' - ' . $row['agent_type'];     
        } 
         
        /*
        $this->load->library('pagination');
        $this->db->where("DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        if($srch_state != '') {       
        $this->db->where(' b.state_name = "'. $srch_state .'"');    
        }
        $this->db->from('rh_franchise_enquiry_info as a');   
        if($srch_state != '') {       
        $this->db->join('rh_states_info as b','on b.id = a.state_id','left' );    
        }      
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        	
        $config['base_url'] = trim(site_url('franchise-enquiry-report'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $sql = "
                select 
                a.pickup_id,
                DATE_FORMAT(b.booked_date,'%d-%m-%Y') as booking_date,
                concat(c.contact_person, ' - ',c.agent_type) as agent,
                b.source_pincode,
                b.destination_pincode,
                a.pickup_charges,
                a.connection_charges,
                a.delivery_charges
                from crit_tracking_info as a
                left join rh_pickup_info as b on b.pickup_id = a.pickup_id 
                left join crit_agent_info as c on (c.agent_id = a.pickup_person_id or c.agent_id = a.delivery_person_id )
                where  DATE_FORMAT(b.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and $where
                order by c.agent_id , a.pickup_id asc 
                              
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['agent']][] = $row;     
        }
        
       // $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('agent-report',$data); 
	}
    
    public function agent_transaction_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'agent.inc';  
        
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $data['srch_agent'] = $srch_agent = $this->input->post('srch_agent') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
           $this->session->set_userdata('srch_agent', $this->input->post('srch_agent'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
           $data['srch_agent'] = $srch_agent= $this->session->userdata('srch_agent') ;
       } 
        
        
       if(empty($srch_agent))
       { 
         $data['srch_agent'] = $srch_agent = ''; 
        $where = " ( a.pickup_person_id != '' or a.delivery_person_id != '' )";
        $where1 = '1';
       }
       else {
        $where = " ( a.pickup_person_id = '". $srch_agent ."' or a.delivery_person_id = '". $srch_agent ."' )";
        $where1 = 'a.agent_id = ' . $srch_agent;
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m').'-01' ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
          
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
         // $this->excel->setActiveSheetIndexByName('Event-Register');
          
        $query = $this->db->query(" 
                select 
                a.pickup_id,
                b.source_pincode,
                b.destination_pincode,
                a.pickup_charges,
                a.connection_charges,
                a.delivery_charges
                from crit_tracking_info as a
                left join rh_pickup_info as b on b.pickup_id = a.pickup_id 
                where  DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and  ". ($srch_state != '' ? '( b.state_name = "'. $srch_state .'")' : '1') . "
                order by a.franchise_enquiry_id desc
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        } 
        
        $this->excel->stream('Franchise-Enquiry-Report-'. $srch_frm_date.'-to-'. $srch_to_date .'.xls', $export_data);
         
       }  
       
        $query = $this->db->query("select a.agent_id,  a.agent_type , a.contact_person , a.state from crit_agent_info as a where a.status = 'Active'  order by a.state , a.contact_person asc ");
        
        $data['agent_opt'][] = 'Select Agent';

        foreach ($query->result_array() as $row)
        {
            $data['agent_opt'][$row['state']][$row['agent_id']] = $row['contact_person'] . ' - ' . $row['agent_type'];     
        } 
         
        /*
        $this->load->library('pagination');
        $this->db->where("DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        if($srch_state != '') {       
        $this->db->where(' b.state_name = "'. $srch_state .'"');    
        }
        $this->db->from('rh_franchise_enquiry_info as a');   
        if($srch_state != '') {       
        $this->db->join('rh_states_info as b','on b.id = a.state_id','left' );    
        }      
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        	
        $config['base_url'] = trim(site_url('franchise-enquiry-report'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
       if(!empty($srch_agent)) {
        
        $sql = "
            select 
            q.t_date,
            q.particular,
            q.total_charges,
            q.total_paid 
            from  
            (
                (
                    select 
                    0 as sort,
                    DATE_FORMAT('". $srch_frm_date ."','%d-%m-%Y') as t_date,
                    'Opening balance b/d ' as particular,                 
                    sum(w.total_charges - w.total_paid ) as total_charges,
                    0 as total_paid from 
                    (
                        (
                        select                
                        sum(a.pickup_charges + a.connection_charges + a.delivery_charges) as total_charges,
                        0 as total_paid
                        from crit_tracking_info as a
                        left join rh_pickup_info as b on b.pickup_id = a.pickup_id 
                        left join crit_agent_info as c on (c.agent_id = a.pickup_person_id or c.agent_id = a.delivery_person_id )
                        where  DATE_FORMAT(b.booked_date,'%Y-%m-%d') < '". $srch_frm_date ."'  
                        and $where
                        group by a.pickup_id  
                        order by a.booked_date asc 
                        ) union all (
                        select  
                        0 as  total_charges,
                        sum(a.paid_amount) as total_paid
                        from crit_agent_payment_info as a
                        left join crit_agent_info as b on b.agent_id = a.agent_id
                        left join crit_pay_method_info as c on c.pay_method_id = b.pay_type
                        where a.payment_date < '". $srch_frm_date ."' 
                        and $where1
                        and a.`status` = 'Paid'
                        order by a.payment_date asc , a.agent_payment_id asc 
                        ) 
                    ) as w 
                  
                ) union all (
                select 
                1 as sort,
                DATE_FORMAT(b.booked_date,'%d-%m-%Y') as t_date,
                a.pickup_id as particular,                 
                (a.pickup_charges + a.connection_charges + a.delivery_charges) as total_charges,
                0 as total_paid
                from crit_tracking_info as a
                left join rh_pickup_info as b on b.pickup_id = a.pickup_id 
                left join crit_agent_info as c on (c.agent_id = a.pickup_person_id or c.agent_id = a.delivery_person_id )
                where  DATE_FORMAT(b.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and $where
                group by a.pickup_id  
                order by a.booked_date asc 
                ) union all (
                select  
                2 as sort,
                a.payment_date as t_date, 
                c.pay_method_name as particular,
                0 as total_charges,
                a.paid_amount as total_paid
                from crit_agent_payment_info as a
                left join crit_agent_info as b on b.agent_id = a.agent_id
                left join crit_pay_method_info as c on c.pay_method_id = b.pay_type
                where a.payment_date between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and $where1
                and a.`status` = 'Paid'
                order by a.payment_date asc , a.agent_payment_id asc 
                ) 
            ) as q
            order by q.sort asc , q.t_date  asc                              
        ";
         
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        } else {
            $data['record_list'] = array();
        }
        
       // $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('agent-transaction-report',$data); 
	}
    
    public function pick_pack_report()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pickup-report.inc'; 
        
         
        if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ;
           $data['srch_customer'] = $srch_customer = $this->input->post('srch_customer') ;
           $data['srch_pay_status'] = $srch_pay_status = $this->input->post('srch_pay_status') ;
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date'));
           $this->session->set_userdata('srch_status', $this->input->post('srch_status'));
           $this->session->set_userdata('srch_customer', $this->input->post('srch_customer'));
           $this->session->set_userdata('srch_pay_status', $this->input->post('srch_pay_status'));
       }
       elseif($this->session->userdata('srch_frm_date')){
          // $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ;
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ;
           $data['srch_customer'] = $srch_customer= $this->session->userdata('srch_customer') ;
           $data['srch_pay_status'] = $srch_pay_status= $this->session->userdata('srch_pay_status') ;
       } 
        
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
       if(empty($srch_customer))
       { 
         $data['srch_customer'] = $srch_customer = ''; 
       }
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       }
          
       if($this->input->post('btn_export') == 'Export To Excel File')
       {
          $this->load->library("Excel");
          $this->excel->setActiveSheetIndex(0);
         // $this->excel->setActiveSheetIndexByName('Event-Register');
          
        $query = $this->db->query(" 
                select 
                a.pick_pack_id as ID,
                a.pp_customer_ref_no as customer_ref_no,
                a.book_date,
                b.company_name as company , 
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                a.packing_required,            
                a.status,
                a.pay_status,
                a.paid_date,
                a.pp_charges as charges,
                a.package_weight,
                a.packed_date
                from crit_pick_pack_info as a 
                left join crit_pp_customer_info as b on b.pp_customer_id = a.pp_customer_id
                where DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' 
                and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and ". ( (!empty($srch_customer)) ? " a.pp_customer_id = '". $srch_customer."' " : "1" )." 
                and ". ( (!empty($srch_pay_status)) ? " a.pay_status = '". $srch_pay_status."' " : "1" )." 
                order by a.book_date asc 
        ");
         
        $export_data = array();  

        foreach ($query->result_array() as $row)
        {
            $export_data[] = $row;     
        }
         
        
        $this->excel->stream('Pick-Pack-Report-'. $srch_frm_date.'-to-'. $srch_to_date .'.xls', $export_data);
         
       }    
          
          
        $query = $this->db->query("select a.pp_customer_id,  a.company_name  from crit_pp_customer_info as a where a.status='Active'   order by  a.company_name asc ");
        
        $data['customer_opt'] = array('','Select Customer'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['customer_opt'][$row['pp_customer_id']] = $row['company_name']   ;    
        }  
        
        $data['sno'] = $this->uri->segment(2, 0);	
     
        /*
        $this->load->library('pagination');
        
        $this->db->where("(a.`status`= 'Picked' or a.`status` = 'Delivered')");
        $this->db->where("DATE_FORMAT(a.booked_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'");
        $this->db->from('rh_pickup_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        	
        $config['base_url'] = trim(site_url('pickup-report/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 100;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);  */
        
      
       $sql = "
                select 
                a.pick_pack_id,
                a.pp_customer_ref_no,
                a.book_date,
                b.company_name as company , 
                a.sender_name,
                a.sender_mobile,
                a.sender_address,
                a.packing_required,            
                a.status,
                a.pay_status,
                a.paid_date,
                a.pp_charges,
                a.package_weight,
                a.packed_date
                from crit_pick_pack_info as a 
                left join crit_pp_customer_info as b on b.pp_customer_id = a.pp_customer_id
                where DATE_FORMAT(a.book_date,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."' 
                and a.`status` != 'Delete' 
                and ". ( (!empty($srch_status)) ? " a.status = '". $srch_status."' " : "1" )." 
                and ". ( (!empty($srch_customer)) ? " a.pp_customer_id = '". $srch_customer."' " : "1" )." 
                and ". ( (!empty($srch_pay_status)) ? " a.pay_status = '". $srch_pay_status."' " : "1" )." 
                order by a.book_date asc 
                                
        "; 
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //$data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pick-pack-report',$data); 
	}
    
    public function get_courier_charges()
    {             
        $source = $this->input->post('source');
        $destination = $this->input->post('destination');
        $weight  = $this->input->post('weight');
        $courier_type  = $this->input->post('courier_type');
        $package_type  = $this->input->post('package_type');
        $c_type  = $this->input->post('c_type');
        $packing_required  = $this->input->post('packing_required');
        
           
           
        if($courier_type == 'Domestic') {     
            /* 
            if(c.addt_weight >= '". $weight ."' , 0, ('". $weight ."'- c.addt_weight) ) as addt_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING('". $weight ."' - c.addt_weight) / c.addt_weight) ) as addt_no_of_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ) as addt_charges_value1,  
            cast( (c.min_charges + (if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ))) as DECIMAL(12,2)) as tot_charges1
            */
            
        $sql = "
        
        select 
            a.source_pincode,
            a.source_area,
            a.source_state_code,
            a.source_br_code,
            b.dest_pincode,
            b.dest_area,
            b.dest_state_code,
            b.dest_br_code,
            if(dest_state_code = source_state_code, 1,0 ) as state,
            if(dest_br_code = source_br_code, 1,0 ) as city,
            c.min_weight,
            c.min_charges,
            c.addt_weight,
            c.addt_charges,
            d.discount_percent,
            ( select w.packing_charge_per_kg from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as packing_charge_per_kg,
            '". $weight ."' as pkg_wt,
            if(c.min_weight <= '". $weight ."', ('". $weight ."' - c.min_weight) , 0 ) as addt_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) , 0 ) as addt_no_of_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ) as addt_charges_value,
            (c.min_charges + (if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ))) as tot_charges
            
            from (
            select 
             1 as mode,
             a.pincode as source_pincode,
             a.area_name as source_area,
             a.state_name as source_state_code, 
             a.district_name as source_br_code 
             from crit_pincode_info as a 
             where (a.pincode = '". $source ."' or concat(a.pincode,' - ', a.area_name ) = '". $source ."') limit 1
            ) as a left join (
             select 
             1 as mode,
             a.pincode as dest_pincode,
             a.area_name as dest_area,
             a.state_name as dest_state_code, 
             a.district_name as dest_br_code
             from crit_pincode_info as a 
             where ( a.pincode = '". $destination ."' or concat(a.pincode,' - ', a.area_name ) = '". $destination ."') limit 1 
            ) as b on b.mode = a.mode
            left join rh_courier_charges_info as c on c.flg_state = if(dest_state_code = source_state_code, 1,0 ) and c.flg_city = if(dest_br_code = source_br_code, 1,0 )
            left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'Domestic') as d on 1=1 
            where c.c_type = '". $c_type ."' and c.`status` = 'Active'
        ";     
             
        } else {
            
            $sql = "            
                 select 
                 f.packing_charge_per_kg,
                 e.package_weight,
                 (f.packing_charge_per_kg * ceil(e.package_weight)) as packing_charge,
                 a.rate as actual_charges,
                 ifnull(d.discount_percent,0) as discount_percent,
                 round((a.rate * ifnull(d.discount_percent,0) /100),2) as discount_amt,
                 round((a.rate - (a.rate * ifnull(d.discount_percent,0) /100)),2) as tot_charges
                 from  crit_international_rate as a  
                 left join crit_package_type as c on c.package_type_id = a.package_type and c.`status` = 'Active'
                 left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'International') as d on 1=1
                 left join crit_package_weight as e on e.package_weight_id = a.package_weight
                 left join ( select w.packing_charge_per_kg from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as f on 1=1 
                 where a.status = 'Active' 
                 and c.package_type = '". $package_type ."' 
                 and a.country = '". $destination ."' 
                 and e.package_weight = '". $weight."'
            ";
			
			//and a.package_weight = '". $weight."'
        }     
             
        $query = $this->db->query($sql);
        
        $charges = array();
        
        foreach ($query->result_array() as $row)
        {
          $charges = $row ;    
        }  
        
        if($courier_type == 'Domestic') {  
        
        if($packing_required == '1')
        {
            if($charges['pkg_wt'] > 1)
                $packing =  ( $charges['packing_charge_per_kg'] * ceil($charges['pkg_wt']));
            else
                $packing =  $charges['packing_charge_per_kg'];   
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           $gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           if($charges['discount_percent'] > 0 ) {
               $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                                <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                                <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                                <tr><td class='text-right'>Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                                <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                                <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                                <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                              </table>" ;
           }  else {
             $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                                <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                                <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                                <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                                <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                                <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                              </table>" ;
           }                             
        
        } else {
           $packing =  0;
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           $gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           if($charges['discount_percent'] > 0 ) {
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
           } else {
            $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
           }
         
        }
         $charges['total'] = $tot;
        } else {
            
           $actual_charges = $charges['actual_charges'];
           $discount_amt = $charges['discount_amt'];
           if($packing_required == '1')
            $packing_charge= $charges['packing_charge'];
           else
            $packing_charge = 0;
           //$disc = ($tot_charges * 5 / 100);
           $gst = (( ($actual_charges + $packing_charge) - $discount_amt  ) * (18/100));
           $tot = (( ($actual_charges + $packing_charge) - $discount_amt  ) + $gst);
           
           if($charges['discount_percent'] > 0 ) {
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Shipment Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($actual_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing_charge,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
           } else {
            $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Shipment Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($actual_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing_charge,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
           }
                                          
           $charges['total'] = $tot;                               
            
        }
        
        //$charges = array('sql' => $sql) + $charges;
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($charges));
        //echo ( ($sql));
        

    }
    
    
    public function get_courier_charges_v2()
    {             
        $source = $this->input->post('source');
        $destination = $this->input->post('destination');
        $weight  = $this->input->post('weight');
        $courier_type  = $this->input->post('courier_type');
        $package_type  = $this->input->post('package_type');
        $c_type  = $this->input->post('c_type');
        $packing_required  = $this->input->post('packing_required');
        
           
           
        if($courier_type == 'Domestic') {     
            /* 
            if(c.addt_weight >= '". $weight ."' , 0, ('". $weight ."'- c.addt_weight) ) as addt_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING('". $weight ."' - c.addt_weight) / c.addt_weight) ) as addt_no_of_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ) as addt_charges_value1,  
            cast( (c.min_charges + (if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ))) as DECIMAL(12,2)) as tot_charges1
            */
            
        $sql = "
        
        select 
            a.source_pincode,
            a.source_area,
            a.source_state_code,
            a.source_br_code,
            b.dest_pincode,
            b.dest_area,
            b.dest_state_code,
            b.dest_br_code,
            if(dest_state_code = source_state_code, 1,0 ) as state,
            if(dest_br_code = source_br_code, 1,0 ) as city,
            c.min_weight,
            c.min_charges,
            c.addt_weight,
            c.addt_charges,
            d.discount_percent,
            ( select w.packing_charge_per_kg from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as packing_charge_per_kg,
            '". $weight ."' as pkg_wt,
            if(c.min_weight <= '". $weight ."', ('". $weight ."' - c.min_weight) , 0 ) as addt_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) , 0 ) as addt_no_of_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ) as addt_charges_value,
            (c.min_charges + (if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ))) as tot_charges,
            (f.packing_charge_per_kg + (if(f.init_weight <= '". $weight ."', CEILING(('". $weight ."' - f.init_weight) / f.addt_wt_pk_ch) * f.addt_packing_charge  , 0 ))) as packing_charge
            
            from (
            select 
             1 as mode,
             a.pincode as source_pincode,
             a.area_name as source_area,
             a.state_name as source_state_code, 
             a.district_name as source_br_code ,
             a.zone as source_zone,
             a.metro as source_metro
             from crit_pincode_info as a 
             where (a.pincode = '". $source ."' or concat(a.pincode,' - ', a.area_name ) = '". $source ."') limit 1
            ) as a left join (
             select 
             1 as mode,
             a.pincode as dest_pincode,
             a.area_name as dest_area,
             a.state_name as dest_state_code, 
             a.district_name as dest_br_code,
             a.zone as dest_zone,
             a.metro as dest_metro
             from crit_pincode_info as a 
             where ( a.pincode = '". $destination ."' or concat(a.pincode,' - ', a.area_name ) = '". $destination ."') limit 1 
            ) as b on b.mode = a.mode
            left join crit_domestic_rate_info as c on 
                c.flg_region = (if(b.dest_zone = a.source_zone,1,0))
            and c.flg_state = (if(b.dest_state_code = a.source_state_code,1,0)) 
            and c.flg_city = (if(b.dest_br_code = a.source_br_code,1,0))             
            and (if(b.dest_zone = a.source_zone,1,c.flg_metro = (if(b.dest_metro = 'Y',1,0)))) 
            left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'Domestic') as d on 1=1 
            left join ( select w.init_weight, w.packing_charge_per_kg , w.addt_weight as addt_wt_pk_ch , w.addt_packing_charge from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as f on 1=1 
            where c.c_type = '". $c_type ."' and c.`status` = 'Active'
        ";     
             
        } else {
            
            $sql = "            
                 select 
                 f.packing_charge_per_kg,
                 e.package_weight,
                 (f.packing_charge_per_kg * ceil(e.package_weight)) as packing_charge1,
                 (f.packing_charge_per_kg + (if(f.init_weight <= ceil(e.package_weight), CEILING((ceil(e.package_weight) - f.init_weight) / f.addt_wt_pk_ch) * f.addt_packing_charge  , 0 ))) as packing_charge
                 a.rate as actual_charges,
                 ifnull(d.discount_percent,0) as discount_percent,
                 round((a.rate * ifnull(d.discount_percent,0) /100),2) as discount_amt,
                 round((a.rate - (a.rate * ifnull(d.discount_percent,0) /100)),2) as tot_charges
                 from  crit_international_rate as a  
                 left join crit_package_type as c on c.package_type_id = a.package_type and c.`status` = 'Active'
                 left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'International') as d on 1=1
                 left join crit_package_weight as e on e.package_weight_id = a.package_weight
                 left join ( select w.init_weight, w.packing_charge_per_kg , w.addt_weight as addt_wt_pk_ch , w.addt_packing_charge from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as f on 1=1 
                 where a.status = 'Active' 
                 and c.package_type = '". $package_type ."' 
                 and a.country = '". $destination ."' 
                 and e.package_weight = '". $weight."'
            ";
			
			//and a.package_weight = '". $weight."'
        }     
             
        $query = $this->db->query($sql);
        
        $charges = array();
        
        foreach ($query->result_array() as $row)
        {
          $charges = $row ;    
        }  
        
        if($courier_type == 'Domestic') {  
        
        if($packing_required == '1')
        {
            /*if($charges['pkg_wt'] > 1)
                $packing =  ( $charges['packing_charge_per_kg'] * ceil($charges['pkg_wt']));
            else
                $packing =  $charges['packing_charge_per_kg']; */  
                
           $packing =  $charges['packing_charge'];   
                
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           //$gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $gst = ($tot_charges  * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ;
        
        } else {
           $packing =  0;
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           //$gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $gst = ($tot_charges   * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
         
        }
         $charges['total'] = $tot;
        } else {
            
           $actual_charges = $charges['actual_charges'];
           $discount_amt = $charges['discount_amt'];
           if($packing_required == '1')
            $packing_charge= $charges['packing_charge'];
           else
            $packing_charge = 0;
           //$disc = ($tot_charges * 5 / 100);
           //$gst = (( ($actual_charges + $packing_charge) - $discount_amt  ) * (18/100));
           $gst = ($actual_charges  * (18/100));
           $tot = (( ($actual_charges + $packing_charge) - $discount_amt  ) + $gst);
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Shipment Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($actual_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing_charge,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
                                          
           $charges['total'] = $tot;                               
            
        }
        
        //$charges = array('sql' => $sql) + $charges;
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($charges));
        //echo ( ($sql));
        

    }
    
    public function get_courier_charges_v3()
    {             
        $source = $this->input->post('source');
        $destination = $this->input->post('destination');
        $weight  = $this->input->post('weight');
        $courier_type  = $this->input->post('courier_type');
        $package_type  = $this->input->post('package_type');
        $c_type  = $this->input->post('c_type');
        $packing_required  = $this->input->post('packing_required');
        
           
           
        if($courier_type == 'Domestic') {     
            /* 
            if(c.addt_weight >= '". $weight ."' , 0, ('". $weight ."'- c.addt_weight) ) as addt_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING('". $weight ."' - c.addt_weight) / c.addt_weight) ) as addt_no_of_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ) as addt_charges_value1,  
            cast( (c.min_charges + (if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ))) as DECIMAL(12,2)) as tot_charges1
            */
            
        $sql = "
        
        select 
            a.source_pincode,
            a.source_area,
            a.source_state_code,
            a.source_br_code,
            b.dest_pincode,
            b.dest_area,
            b.dest_state_code,
            b.dest_br_code,
            if(dest_state_code = source_state_code, 1,0 ) as state,
            if(dest_br_code = source_br_code, 1,0 ) as city,
            c.min_weight,
            c.min_charges,
            c.addt_weight,
            c.addt_charges,
            d.discount_percent,
            ( select w.packing_charge_per_kg from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as packing_charge_per_kg,
            '". $weight ."' as pkg_wt,
            if(c.min_weight <= '". $weight ."', ('". $weight ."' - c.min_weight) , 0 ) as addt_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) , 0 ) as addt_no_of_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ) as addt_charges_value,
            (c.min_charges + (if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ))) as tot_charges,
            (f.packing_charge_per_kg + (if(f.init_weight <= '". $weight ."', CEILING(('". $weight ."' - f.init_weight) / f.addt_wt_pk_ch) * f.addt_packing_charge  , 0 ))) as packing_charge
            
            from (
            select 
             1 as mode,
             a.pincode as source_pincode,
             a.area_name as source_area,
             a.state_name as source_state_code, 
             a.district_name as source_br_code ,
             a.zone as source_zone,
             a.metro as source_metro
             from crit_pincode_info as a 
             where (a.pincode = '". $source ."' or concat(a.pincode,' - ', a.area_name ) = '". $source ."') limit 1
            ) as a left join (
             select 
             1 as mode,
             a.pincode as dest_pincode,
             a.area_name as dest_area,
             a.state_name as dest_state_code, 
             a.district_name as dest_br_code,
             a.zone as dest_zone,
             a.metro as dest_metro
             from crit_pincode_info as a 
             where ( a.pincode = '". $destination ."' or concat(a.pincode,' - ', a.area_name ) = '". $destination ."') limit 1 
            ) as b on b.mode = a.mode
            left join crit_domestic_rate_info_v3 as c on 
                c.flg_region = (if(b.dest_zone = a.source_zone,1,0))
            and c.flg_state = (if(b.dest_state_code = a.source_state_code,1,0)) 
            and c.flg_city = (if(b.dest_br_code = a.source_br_code,1,0))             
            and (if(b.dest_zone = a.source_zone,1,c.flg_metro = (if(b.dest_metro = 'Y',1,0)))) 
            left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'Domestic') as d on 1=1 
            left join ( select w.init_weight, w.packing_charge_per_kg , w.addt_weight as addt_wt_pk_ch , w.addt_packing_charge from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as f on 1=1 
            where c.c_type = '". $c_type ."' and c.`status` = 'Active'
            and c.from_weight <= '". number_format($weight,0) ."'  and c.to_weight >= '". number_format($weight,0) ."' 
        ";     
             
        } else {
            
            $sql = "            
                 select 
                 f.packing_charge_per_kg,
                 e.package_weight,
                 (f.packing_charge_per_kg * ceil(e.package_weight)) as packing_charge1,
                 (f.packing_charge_per_kg + (if(f.init_weight <= ceil(e.package_weight), CEILING((ceil(e.package_weight) - f.init_weight) / f.addt_wt_pk_ch) * f.addt_packing_charge  , 0 ))) as packing_charge,
                 a.rate as actual_charges,
                 ifnull(d.discount_percent,0) as discount_percent,
                 round((a.rate * ifnull(d.discount_percent,0) /100),2) as discount_amt,
                 round((a.rate - (a.rate * ifnull(d.discount_percent,0) /100)),2) as tot_charges
                 from  crit_international_rate as a  
                 left join crit_package_type as c on c.package_type_id = a.package_type and c.`status` = 'Active'
                 left join (select  a.discount_percent from crit_discount_offer_info as a where a.valid_from <= '". date('Y-m-d')."' and a.valid_to >= '". date('Y-m-d')."' and a.courier_type = 'International') as d on 1=1
                 left join crit_package_weight as e on e.package_weight_id = a.package_weight
                 left join ( select w.init_weight, w.packing_charge_per_kg , w.addt_weight as addt_wt_pk_ch , w.addt_packing_charge from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as f on 1=1 
                 where a.status = 'Active' 
                 and c.package_type = '". $package_type ."' 
                 and a.country = '". $destination ."' 
                 and e.package_weight = '". $weight."'
            ";
			
			//and a.package_weight = '". $weight."'
        }     
             
        $query = $this->db->query($sql);
        
        $charges = array();
        
        foreach ($query->result_array() as $row)
        {
          $charges = $row ;    
        }  
        
        if($courier_type == 'Domestic') {  
        
        if($packing_required == '1')
        {
            /*if($charges['pkg_wt'] > 1)
                $packing =  ( $charges['packing_charge_per_kg'] * ceil($charges['pkg_wt']));
            else
                $packing =  $charges['packing_charge_per_kg']; */  
                
           $packing =  $charges['packing_charge'];   
                
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           //$gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $gst = ($tot_charges  * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           
           if($charges['discount_percent'] > 0 ) {
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ;
           } else {
             $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ;
           }                             
                                          
        
        } else {
           $packing =  0;
           $tot_charges = $charges['tot_charges'];
           $discount_amt = ($tot_charges * $charges['discount_percent'] / 100);
           //$gst = ((($tot_charges + $packing) - $discount_amt ) * (18/100));
           $gst = ($tot_charges   * (18/100));
           $tot = ((($tot_charges + $packing) - $discount_amt ) + $gst);
           
           if($charges['discount_percent'] > 0 ) {
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
          } else {
            $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
          }
         
        }
         $charges['total'] = $tot;
        } else {
            
           $actual_charges = $charges['actual_charges'];
           $discount_amt = $charges['discount_amt'];
           if($packing_required == '1')
            $packing_charge= $charges['packing_charge'];
           else
            $packing_charge = 0;
           //$disc = ($tot_charges * 5 / 100);
           //$gst = (( ($actual_charges + $packing_charge) - $discount_amt  ) * (18/100));
           $gst = ($actual_charges  * (18/100));
           $tot = (( ($actual_charges + $packing_charge) - $discount_amt  ) + $gst);
           
           if($charges['discount_percent'] > 0 ) {
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Shipment Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($actual_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>(Diwali) Discount : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($discount_amt,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing_charge,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
                                          
            } else {
                $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Shipment Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($actual_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing_charge,2) ."</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot,2)."</td></tr>
                                          </table>" ; 
            }
                                          
           $charges['total'] = $tot;                               
            
        }
        
        //$charges = array('sql' => $sql) + $charges;
        
        // $this->db->close();
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($charges));
        //echo ( ($sql));
        

    }
    
    public function get_courier_charges_old()
    {             
        $source = $this->input->post('source');
        $destination = $this->input->post('destination');
        $weight  = $this->input->post('weight');
        $courier_type  = $this->input->post('courier_type');
        $package_type  = $this->input->post('package_type');
        $c_type  = $this->input->post('c_type');
        $packing_required  = $this->input->post('packing_required');
        
           
           
        if($courier_type == 'Domestic') {     
            /* 
            if(c.addt_weight >= '". $weight ."' , 0, ('". $weight ."'- c.addt_weight) ) as addt_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING('". $weight ."' - c.addt_weight) / c.addt_weight) ) as addt_no_of_wt1, 
            if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ) as addt_charges_value1,  
            cast( (c.min_charges + (if(c.addt_weight >= '". $weight ."' , 0, (CEILING(('". $weight ."' - c.addt_weight) / c.addt_weight)) * c.addt_charges ))) as DECIMAL(12,2)) as tot_charges1
            */
            
        $sql = "
        
        select 
            a.source_pincode,
            a.source_area,
            a.source_state_code,
            a.source_br_code,
            b.dest_pincode,
            b.dest_area,
            b.dest_state_code,
            b.dest_br_code,
            if(dest_state_code = source_state_code, 1,0 ) as state,
            if(dest_br_code = source_br_code, 1,0 ) as city,
            c.min_weight,
            c.min_charges,
            c.addt_weight,
            c.addt_charges,
            ( select w.packing_charge_per_kg from crit_packing_charge_info as w where w.status ='Active' order by w.created_date desc limit 1) as packing_charge_per_kg,
            '". $weight ."' as pkg_wt,
            if(c.min_weight <= '". $weight ."', ('". $weight ."' - c.min_weight) , 0 ) as addt_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) , 0 ) as addt_no_of_wt,
            if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ) as addt_charges_value,
            (c.min_charges + (if(c.min_weight <= '". $weight ."', CEILING(('". $weight ."' - c.min_weight) / c.addt_weight) * c.addt_charges  , 0 ))) as tot_charges
            
            from (
            select 
             1 as mode,
             a.pincode as source_pincode,
             a.area as source_area,
             a.state_code as source_state_code, 
             a.branch_code as source_br_code 
             from rh_pincode_list as a 
             where a.pincode = '". $source ."'
            ) as a left join (
             select 
             1 as mode,
             a.pincode as dest_pincode,
             a.area as dest_area,
             a.state_code as dest_state_code, 
             a.branch_code as dest_br_code
             from rh_pincode_list as a 
             where a.pincode = '". $destination ."' 
            ) as b on b.mode = a.mode
            left join rh_courier_charges_info as c on c.flg_state = if(dest_state_code = source_state_code, 1,0 ) and c.flg_city = if(dest_br_code = source_br_code, 1,0 )
             
            where c.c_type = '". $c_type ."' and c.`status` = 'Active'
        ";     
             
        } else {
            
            $sql = "            
                select 
                 a.rate as tot_charges
                 from  crit_international_rate as a  
                 left join crit_package_type as c on c.package_type_id = a.package_type and c.`status` = 'Active'
                 where a.status = 'Active' 
                 and c.package_type = '". $package_type ."' 
                 and a.country = '". $destination ."' 
                 and a.package_weight = '". $weight."'
            ";
        }     
             
        $query = $this->db->query($sql);
        
        $charges = array();
        
        foreach ($query->result_array() as $row)
        {
          $charges = $row ;    
        }  
        
        if($courier_type == 'Domestic') {  
        
        if($packing_required == '1')
        {
            if($charges['pkg_wt'] > 1)
                $packing =  ( $charges['packing_charge_per_kg'] * ceil($charges['pkg_wt']));
            else
                $packing =  $charges['packing_charge_per_kg'];   
           $tot_charges = $charges['tot_charges'];
           $gst = (( $tot_charges + $packing ) * (18/100));
           $tot = ($tot_charges + $packing + $gst);
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format(($tot_charges + $packing + $gst),2)."</td></tr>
                                          </table>" ;
        
        } else {
           $packing =  0;
           $tot_charges = $charges['tot_charges'];
           $gst = (( $tot_charges + $packing ) * (18/100));
           $tot = ($tot_charges + $packing + $gst);
           
           $charges['charges_breakup'] = "<table class='table table-striped table-bordered'>
                                            <tr><th colspan='2' class='text-center'>Charges BreakUp</th></tr>
                                            <tr><td class='text-right'>Courier Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($tot_charges,2) . "</td></tr>
                                            <tr><td class='text-right'>Packing Charges : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($packing,2) ."</td></tr>
                                            <tr><td class='text-right'>GST 18% : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format($gst,2) . "</td></tr>
                                            <tr><td class='text-right'>Total : </td><td class='text-right'><i class='fa fa-rupee'></i> " . number_format(($tot_charges + $packing + $gst),2)."</td></tr>
                                          </table>" ; 
         
        }
         $charges['total'] = $tot;
        }
        
        //$charges = array('sql' => $sql) + $charges;
        
        header('Content-Type: application/x-json; charset=utf-8');
        
        echo (json_encode($charges));
        //echo ( ($sql));
        

    }
    
    public function live_pickup_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->input->post('mode') == 'bill_upload'){
            
            $config['upload_path'] = 'awb-upload/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('upload_img'))
            {
                $file_array = $this->upload->data();	
                $image_path	= 'awb-upload/'.$file_array['file_name']; 
           
            }
            else
            {
                 $image_path = '';    
            } 
            
            $upd = array(
                    $this->input->post('upl_field') => $image_path          
            );
            
            $this->db->where('pickup_id', $this->input->post('pickup_id'));
            $this->db->update('rh_pickup_info', $upd); 
            
            redirect('live-pickup');
            
        }
        
        $data['srch_staff_id'] = $srch_staff_id = $this->input->post('srch_staff_id');
        
        
        $query = $this->db->query("select a.user_id, a.first_name  from rh_user_info as a where a.status='Active' and a.level != 4 and a.user_id != '1'   order by  a.first_name asc ");
        
        $data['staff_opt'] = array('All' => 'All'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['staff_opt'][$row['user_id']] = $row['first_name']   ;    
        } 
         
        	    
        $data['js'] = 'live-pickup.inc';           
        
        $sql = "
               select distinct
                a.booked_date,
                e.first_name as assign_to,
                a.pickup_id as ID,
                a.source_pincode as src_pincode,
                c.state_name as src_state,
                c.district_name as src_district,
                a.destination_pincode as dest_pincode,
                d.state_name as dest_state,
                d.district_name as dest_district,
                a.package_weight,
                a.`status`,
                a.awb_img_1,
                a.awb_img_2,
                a.awb_img_3
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_user_info as e on e.user_id = a.assign_to
                where a.`status` != 'Cancelled' and a.`status` !='Delete' and a.status != 'Delivered'
                and a.courier_type = 'Domestic'
                and  ". ($srch_staff_id != '' ? 'a.assign_to = "'. $srch_staff_id .'"' : '1') . " 
                and a.booked_date >= '2019-08-22'
                group by a.pickup_id
                order by a.booked_date asc  
                         
        "; 
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $this->load->view('live-pickup',$data); 
	}
    
    
    public function todays_pickup_delivery_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        	    
        $data['js'] = '';           
        
        $sql = "
               select distinct
                a.booked_date, 
                a.pickup_id as ID,
                a.source_pincode as src_pincode,
                c.state_name as src_state,
                c.district_name as src_district,
                a.destination_pincode as dest_pincode,
                d.state_name as dest_state,
                d.district_name as dest_district,
                a.package_weight,
                a.`status`,
                e.service_provider_name as service_provider,
                a.bill_no  
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_service_provider_info as e on e.service_provider_id = a.service_provider_id
                where a.`status` != 'Cancelled' and a.`status` !='Delete' and a.status != 'Delivered'
                and a.courier_type = 'Domestic' 
                and DATE_FORMAT( a.pickup_schedule_timing,'%Y-%m-%d') = '". date('Y-m-d')."'
                group by a.pickup_id
                order by a.booked_date asc  
                         
        "; 
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['pickup_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['pickup_list'][] = $row;     
        }
        
        
        $sql = "
               select distinct
                a.booked_date, 
                a.pickup_id as ID,
                a.source_pincode as src_pincode,
                c.state_name as src_state,
                c.district_name as src_district,
                a.destination_pincode as dest_pincode,
                d.state_name as dest_state,
                d.district_name as dest_district,
                a.package_weight,
                a.`status`,
                e.service_provider_name as service_provider,
                a.bill_no  
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                left join rh_service_provider_info as e on e.service_provider_id = a.service_provider_id
                where a.`status` != 'Cancelled' and a.`status` !='Delete' and a.status != 'Delivered'
                and a.courier_type = 'Domestic' 
                and a.delivered_date = '". date('Y-m-d')."'
                group by a.pickup_id
                order by a.booked_date asc  
                         
        "; 
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['delivery_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['delivery_list'][] = $row;     
        }
        
        $this->load->view('todays-pickup-delivery-list',$data); 
	}
    
    
    // Pickup Users
    public function pu_pickup_delivery_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        	    
        $data['js'] = 'pickup_delivery.inc';   
        
        
         $pstate = $this->session->userdata('m_pstate');
         $pcity = $this->session->userdata('m_pcity');    
         
        $query = $this->db->query("select a.service_provider_id,  a.service_provider_name  from rh_service_provider_info as a where a.status='Active'   order by  a.service_provider_name asc ");
        
        $data['service_provider_opt'] = array('' => 'Select Service Provider'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['service_provider_opt'][$row['service_provider_id']] = $row['service_provider_name']   ;    
        }   
                
        
        $sql = "
               select distinct
                a.booked_date, 
                a.pickup_id as ID,
                a.courier_type,
                a.package_type,
                a.transport_mode,
                a.sender_name,
                a.sender_phone,
                a.sender_address,
                a.source_pincode as src_pincode,
                c.state_name as src_state,
                c.district_name as src_district,
                a.destination_pincode as dest_pincode,
                d.state_name as dest_state,
                d.district_name as dest_district,
                a.package_weight,
                a.`status` ,
                a.tracking_status
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                where a.`status` != 'Cancelled' and a.`status` !='Delete' and a.status != 'Delivered' 
                and (a.tracking_status = 'Booked' or a.tracking_status = 'Picked')
                and c.state_name = '". $pstate . "'
                and c.district_name = '". $pcity . "'
                and a.booked_date >= '2019-10-01'
                group by a.pickup_id
                order by a.booked_date asc  
                         
        "; 
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['pickup_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['pickup_list'][] = $row;     
        }
        
        
        $sql = "
              select distinct
                a.booked_date, 
                a.pickup_id as ID,
                a.courier_type,
                a.package_type,
                a.transport_mode,
                a.receiver_name,
                a.receiver_phone,
                a.receiver_address,
                a.source_pincode as src_pincode,
                c.state_name as src_state,
                c.district_name as src_district,
                a.destination_pincode as dest_pincode,
                d.state_name as dest_state,
                d.district_name as dest_district,
                a.package_weight,
                a.`status` 
                from rh_pickup_info as a 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode  ) as c on c.pincode = a.source_pincode 
                left join ( select q.state_name ,  q.district_name, q.pincode from crit_pincode_info as q  group by q.state_name ,  q.district_name, q.pincode) as d on d.pincode = a.destination_pincode
                where a.`status` != 'Cancelled' and a.`status` !='Delete' and a.status != 'Delivered' 
                and (a.tracking_status = 'In-Transit' or a.tracking_status = 'Received-HUB' or a.tracking_status = 'Out For Delivery' )
                and d.state_name = '". $pstate . "'
                and d.district_name = '". $pcity . "'
                and a.booked_date >= '2019-10-01'
                group by a.pickup_id
                order by a.booked_date asc  
                         
        "; 
        
         $this->db->query('SET SQL_BIG_SELECTS=1');
         
        $query = $this->db->query($sql);
        
        $data['delivery_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['delivery_list'][] = $row;     
        }
        
        $this->load->view('pu-pickup-delivery-list',$data); 
	}
    
    
    public function insert_franchise_enquiry()
    { 
          $timezone = "Asia/Calcutta";
		  if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);  
                 
          $ins = array(
                        'contact_person_name' => $this->input->post('contact_person_name'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile'),
                        'interested_in' => $this->input->post('interested_in'),
                        'state' => $this->input->post('state_id'),                       
                        'district' => $this->input->post('location_id'),                       
                        'address' => $this->input->post('address') ,
                        'messages' => $this->input->post('message') , 
                        'franchise_enquiry_date' => date('Y-m-d H:i:s') 
                                             
                );                
          $this->db->insert('rh_franchise_enquiry_info', $ins); 
          
          $franchise_enquiry_id = str_pad($this->db->insert_id(),5,0,STR_PAD_LEFT);
          
         
          
         // echo "Successfully!!! Your Franchise Enquiry has been send .<br> Soon We Will Contact you within 24hrs ." ;
          
          
          
          $msg  = $this->get_content('franchise_enquiry', $franchise_enquiry_id);
         
       
            $this->load->library('email');
                
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';  
            
            $this->email->initialize($config);
    
            $this->email->from($this->input->post('email'), $this->input->post('contact_person_name'));
            $this->email->to('sm@pickmycourier.com');
            $this->email->cc('it@pickmycourier.com , santhamurthy@elbex.in , marketing@pickmycourier.com');
            $this->email->bcc('selvanramesh@gmail.com');
            
            $this->email->subject('Pick My Courier - New Franchise Enquiry');
            $this->email->message($msg);
            
            $this->email->send();
			
			
			 echo "OK";
          
          
          /*$this->load->model('RH_model', 'rh_model');
          
          $msg = "Hello ". $this->input->post('sender_name') ." \n Your Courier has been Booked .Your Package will be Pickup by Our Staff. \n Your Booking Ref Number :  " .  $pickup_id . "\n Thanks for Booking ur Courier in Pickmycourier.com ";         
          $this->rh_model->send_sms($this->input->post('sender_phone'),$msg);*/
          
          
          
          
         // print_r($this->input->post()) ;
             
    }
    
     public function quick_quote_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
         
        
        if($this->input->post('mode') == 'status&remarks')
        {
           
            $upd = array(
                    'status' => $this->input->post('status'),
                    'remarks' => $this->input->post('remarks'),    
            );
            
            $this->db->where('quick_quote_id', $this->input->post('quick_quote_id'));
            $this->db->update('crit_quick_quote_info', $upd); 
                            
            redirect('quick-quote-list/' . $this->uri->segment(2, 0)); 
        } 
        	    
        $data['js'] = 'quick-quote.inc';  
        
         if(isset($_POST['srch_frm_date'])) {
           //$data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_frm_date'] = $srch_frm_date = $this->input->post('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->input->post('srch_to_date') ; 
           $this->session->set_userdata('srch_frm_date', $this->input->post('srch_frm_date'));
           $this->session->set_userdata('srch_to_date', $this->input->post('srch_to_date')); 
       }
       elseif($this->session->userdata('srch_frm_date')){
           $data['srch_frm_date'] = $srch_frm_date = $this->session->userdata('srch_frm_date') ;
           $data['srch_to_date'] = $srch_to_date = $this->session->userdata('srch_to_date') ; 
       } 
       
       
       if(isset($_POST['srch_status'])) { 
           $data['srch_status'] = $srch_status = $this->input->post('srch_status') ; 
           $this->session->set_userdata('srch_status', $this->input->post('srch_status')); 
       }
       elseif($this->session->userdata('srch_status')){ 
           $data['srch_status'] = $srch_status = $this->session->userdata('srch_status') ; 
       } 
       
       if(empty($srch_status))
       {
        $data['srch_status'] = $srch_status = '';
        //$data['srch_state'] = $srch_state = ''; 
       }
        
       if(empty($srch_frm_date))
       {
           $data['srch_frm_date'] = $srch_frm_date = date('Y-m-d') ;
           $data['srch_to_date'] = $srch_to_date = date('Y-m-d') ;
       } 
         
        
        $this->load->library('pagination');
        
        if(!empty($srch_status)) {
            $this->db->where('a.status =', $srch_status); 
        }
        $this->db->where("DATE_FORMAT(a.created_datetime,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'"); 
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_quick_quote_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results(); 
        	
        $config['base_url'] = trim(site_url('quick-quote-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 15;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
      $sql = "
                 select 
                 a.quick_quote_id,
                 a.q_org_pincode,
                 a.q_dest_pincode,
                 a.q_pkg_weight,
                 a.q_mobile,
                 a.status,
                 a.remarks,
                 a.created_datetime 
                from crit_quick_quote_info as a  
                where a.status != 'Delete'
                and DATE_FORMAT(a.created_datetime,'%Y-%m-%d') between '". $srch_frm_date ."' and '". $srch_to_date ."'
                and a.status = '". $srch_status ."'
                order by a.created_datetime desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['status_opt'] = array( 
                                'Pending' => 'Pending', 
                                'No response' => 'No response', 
                                'Call Back' => 'Call Back', 
                                'Confirmed' => 'Confirmed',
                                'Cancelled' => 'Cancelled',
                              );
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('quick-quote-list',$data); 
	}
    
    public function franchise_enquiry_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN and $this->session->userdata('m_is_admin') != USER_MARKETING  ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'franchise-enquiry.inc';  
         
        
        $this->load->library('pagination');
        
        $cnt  = $this->db->count_all_results('rh_franchise_enquiry_info');	
        	
        $config['base_url'] = trim(site_url('franchise-enquiry/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 15;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                 select 
                a.franchise_enquiry_id,
                DATE_FORMAT(a.franchise_enquiry_date,'%d-%m-%Y %h:%i %p') as enquiry_date,
                a.contact_person_name,
                a.email,
                a.mobile,
                a.interested_in,
                a.state as state,
                a.district as city,
                a.address,
                a.messages
                from rh_franchise_enquiry_info as a  
                where a.contact_person_name != ''
                order by a.franchise_enquiry_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('franchise-enquiry',$data); 
	}
    
    public function country_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'country.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'country_name' => $this->input->post('country_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('rh_country_info', $ins); 
            redirect('country-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'country_name' => $this->input->post('country_name'),
                    'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('country_id', $this->input->post('country_id'));
            $this->db->update('rh_country_info', $upd); 
                            
            redirect('country-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('rh_country_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('country-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 15;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.country_id,
                a.country_name,                
                a.status
                from rh_country_info as a 
                where status != 'Delete'
                order by a.status asc , a.country_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('country-list',$data); 
	}  
    
    
    public function state_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'state.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'state_name' => $this->input->post('state_name'),
                    'state_code' => $this->input->post('state_code')  ,                          
            );
            
            $this->db->insert('rh_states_info', $ins); 
            redirect('state-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'state_name' => $this->input->post('state_name'),
                    'state_code' => $this->input->post('state_code'),                 
            );
            
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('rh_states_info', $upd); 
                            
            redirect('state-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('rh_states_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('state-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.id,
                a.state_name,                
                a.state_code                
                from rh_states_info as a  
                where status != 'Delete'
                order by a.state_name asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('state-list',$data); 
	}
    
    public function pay_method_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pay-method.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'pay_method_name' => $this->input->post('pay_method_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_pay_method_info', $ins); 
            redirect('pay-method-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'pay_method_name' => $this->input->post('pay_method_name'),
                    'status' => $this->input->post('status')  ,                
            );
            
            $this->db->where('pay_method_id', $this->input->post('pay_method_id'));
            $this->db->update('crit_pay_method_info', $upd); 
                            
            redirect('pay-method-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_pay_method_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('pay-method-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.pay_method_id,
                a.pay_method_name,   
                a.status        
                from crit_pay_method_info as a  
                where status != 'Delete'
                order by a.pay_method_name asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pay-method-list',$data); 
	}
    
    public function gst_invoice_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'invoice.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'invoice_no' => $this->input->post('invoice_no'),
                    'invoice_date' => $this->input->post('invoice_date'),
                    'client_name' => $this->input->post('client_name')  ,                          
                    'address' => $this->input->post('address')  ,                          
                    'state' => $this->input->post('state')  ,                          
                    'client_GSTIN' => $this->input->post('client_GSTIN')  ,                          
                    'contact_no' => $this->input->post('contact_no')  ,                          
                    'way_bill' => $this->input->post('way_bill')  ,                          
                    'weight' => $this->input->post('weight')  ,                          
                    'amount' => ($this->input->post('amount') * 100 / (100 + $this->input->post('GST_percentage')))  ,     
                    'GST_percentage' => $this->input->post('GST_percentage')  ,                     
                    'total_amount' =>  $this->input->post('amount') ,                          
                    'created_by' => $this->session->userdata('m_user_id'),                          
                    'created_date' => date('Y-m-d H:i:s')  ,                       
            );
            
            $this->db->insert('crit_invoice_info', $ins); 
            redirect('gst-invoice');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'invoice_no' => $this->input->post('invoice_no'),
                    'invoice_date' => $this->input->post('invoice_date'),
                    'client_name' => $this->input->post('client_name')  ,                          
                    'address' => $this->input->post('address')  ,                          
                    'state' => $this->input->post('state')  ,                          
                    'client_GSTIN' => $this->input->post('client_GSTIN')  ,                          
                    'contact_no' => $this->input->post('contact_no')  ,                          
                    'way_bill' => $this->input->post('way_bill')  ,                          
                    'weight' => $this->input->post('weight')  ,  
                    'GST_percentage' => $this->input->post('GST_percentage')  ,                     
                    'amount' =>  $this->input->post('amount') ,    
                    'total_amount' => ($this->input->post('amount') + ($this->input->post('amount') *  $this->input->post('GST_percentage')/100))  ,
                    'last_updated' => date('Y-m-d H:i:s')  ,                  
            );
            
            $this->db->where('invoice_id', $this->input->post('invoice_id'));
            $this->db->update('crit_invoice_info', $upd); 
                            
            redirect('gst-invoice/' . $this->uri->segment(2, 0)); 
        }
         
         
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'Select State';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
         
        $query = $this->db->query("select ifnull(max(a.invoice_no),1) as bill_no from crit_invoice_info as a where a.invoice_date between '2019-04-01' and '2020-03-31'");
           
          $data[] =  $bill_no = 0;
           
           foreach ($query->result_array() as $row)
           {
             $bill_no = ($row['bill_no'] + 1);
           } 
          
        $data['invoice_no'] =  $bill_no; 
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_invoice_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('gst-invoice/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.invoice_id,
                a.invoice_no,   
                a.invoice_date,   
                a.client_name,   
                a.state,
                a.way_bill,
                a.weight,
                a.GST_percentage,
                a.total_amount        
                from crit_invoice_info as a  
                where a.status != 'Delete'
                order by a.invoice_date desc , a.invoice_id desc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //print_r($data['record_list']);
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('gst-invoice-list',$data); 
	}
    
    public function print_invoice($invoice_id)
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        }  
        
        $sql = "
                select 
                a.invoice_id,
                a.invoice_no,   
                a.invoice_date,   
                a.client_name,  
                a.address, 
                a.state, 
                a.contact_no, 
                a.client_GSTIN,
                a.way_bill,
                a.weight,
                a.amount,
                a.GST_percentage,
                (a.amount * a.GST_percentage / 100) as gst_amt,
                a.total_amount        
                from crit_invoice_info as a  
                where a.invoice_id = $invoice_id
                order by a.invoice_date desc , a.invoice_id desc               
        "; 
        
        $query = $this->db->query($sql);
        
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list']  = $row;     
        } 
        
        
        $data['rs_word'] = $this->convert_number(round($data['record_list']['total_amount']));
        
        $this->load->view('print-invoice',$data); 
	}
    
    public function generate_invoice($invoice_id) // PDF
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
         
        $this->load->library('Pdf');
        
        $sql = "
                select 
                a.invoice_id,
                a.invoice_no,   
                a.invoice_date,   
                a.client_name,  
                a.address, 
                a.state, 
                a.contact_no, 
                a.client_GSTIN,
                a.way_bill,
                a.weight,
                a.amount,
                a.GST_percentage,
                (a.amount * a.GST_percentage / 100) as gst_amt,
                a.total_amount        
                from crit_invoice_info as a  
                where a.invoice_id = $invoice_id
                order by a.invoice_date desc , a.invoice_id desc               
        "; 
        
        $query = $this->db->query($sql);
        
         $record_list = array();
       
        foreach ($query->result_array() as $row)
        {
            $record_list = $row;     
        } 
        
        
        $rs_word = $this->convert_number(round($record_list['total_amount']));
       
        $cnt = '  
         <!DOCTYPE HTML> 
        <head> 
        	<title>PMC</title>
        </head> 
        <body>
        <table border="0" width="100%" cellpadding="10">
            <tr>
                <td>
                <address>
                  <strong>Pick My Courier & Logistics Pvt Ltd</strong><br>
                  No.258, Avaram Palayam Road, <br>
                  Siddhapudur, New Siddhapudur,<br>
                  Coimbatore-641044,<br>
                   Tamil Nadu, India <br> 
                  <b>GST:33AAHCP8343B1ZN</b>
                </address></td>
                <td align="center"> <img src="'. base_url('images/PMC-logo.jpg').'" alt="" class="img-responsive" width="300" /> </td>
            </tr>
            <tr>
                <td style="padding-left:10px;">
                To, <br>  
                <address>
                  &nbsp;&nbsp;&nbsp;<strong>'. $record_list['client_name'].'</strong><br>
                  &nbsp;&nbsp;&nbsp;'. $record_list['address'].'  <br />
                  &nbsp;&nbsp;&nbsp;<strong>  GST : '.  $record_list['client_GSTIN'].' </strong>
                </address> 
                </td>
                <td> 
                    <br />
                    <br />
                    <b>Invoice No : #'. $record_list['invoice_no'].'</b> <br />
                    <b>Invoice Date :</b> '. date('d-m-Y',strtotime($record_list['invoice_date'])).'<br> 
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="1" width="100%" cellpadding="5">
                      <thead>
                      <tr>
                        <th align="center" width="10%" >#</th>
                        <th align="right"  width="50%">Way Bill</th>
                        <th align="right"  width="20%">Weight</th> 
                        <th align="right"  width="20%">Amount</th>
                      </tr>
                      </thead>
                      <tbody> 
                      <tr>
                        <td align="center">1</td>
                        <td align="left">'. $record_list['way_bill'].'</td>
                        <td align="right">'. number_format($record_list['weight'],2).'</td> 
                        <td align="right">'. number_format($record_list['amount'],2).'</td>  
                      </tr> 
                      <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>
                       <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>
                      <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>
                      <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>
                     <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>
                      <tr>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td> 
                      </tr>  
                      <tr>
                        <th colspan="3" align="right">Sub-Total</th>  
                        <th align="right">'.  number_format($record_list['amount'],2).'</th> 
                      </tr>';
                if($record_list['state'] != 'Tamil Nadu')  {    
                $cnt.='<tr>
                        <th colspan="3" align="right">IGST - '. number_format($record_list['GST_percentage'],2).'%</th>  
                        <th align="right">'. number_format(($record_list['amount'] * $record_list['GST_percentage']/100 ),2).'</th> 
                      </tr>';
                } else {
                    $cnt.='<tr>
                        <th colspan="3" align="right">CGST - '. number_format(($record_list['GST_percentage']/2),2).'%</th>  
                        <th align="right">'. number_format(($record_list['amount'] * ($record_list['GST_percentage']/2)/100 ),2).'</th> 
                      </tr>';
                    $cnt.='<tr>
                        <th colspan="3" align="right">SGST - '. number_format(($record_list['GST_percentage']/2),2).'%</th>  
                        <th align="right">'. number_format(($record_list['amount'] * ($record_list['GST_percentage']/2)/100 ),2).'</th> 
                      </tr>';
                }
                      
                $cnt.='<tr style="border-bottom: 2px solid grey;">
                        <th colspan="3" align="right">Round Off</th>  
                        <th align="right">'. number_format((round($record_list['total_amount']) - ($record_list['amount'] + $record_list['gst_amt'])),2).'</th> 
                      </tr>
                      <tr style="border-bottom: 2px solid grey;">
                        <th colspan="3" align="right">Total</th>  
                        <th align="right">'. number_format(round($record_list['total_amount']),2).'</th> 
                      </tr>
                      </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="left"><strong>Rupees In Words :</strong><br />'. $rs_word.' Only</td>
                <td align="center">
                    <img src="'. base_url('images/pmc-seal.png').'" alt="" class="img-responsive" width="100" /><br />
                    <b>For Pick My Courier & Logistics Pvt Ltd</b>
                </td>
            </tr>
        </table>
        
        </body>
        </html>
        ';
        

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('PMCL - Invoice');
		$pdf->SetHeaderMargin(20);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('PMCL - Tamilselvan');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();
		//$pdf->Write(5, 'Some sample text');
		//$pdf->writeHTML($cnt, true, false, true, false, '');
		$pdf->writeHTML($cnt);
		//$pdf->Output('My-File-Name.pdf', 'I');
		$pdf->Output('pdf_invoice/'. $invoice_id.'.pdf', 'F');  
        
        header("location: ../../pdf_invoice/". $invoice_id. ".pdf ");     
       
         
	}
    
    public function create_invoice($pickup_id)  
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        
         $query = $this->db->query("select ifnull(max(a.invoice_no),1) as bill_no from crit_invoice_info as a where a.invoice_date between '2019-04-01' and '2020-03-31'");
           
          $data[] =  $bill_no = 0;
           
           foreach ($query->result_array() as $row)
           {
             $bill_no = ($row['bill_no'] + 1);
           } 
        
        $sql = "
            insert into crit_invoice_info (
                invoice_no, 
                invoice_date, 
                client_name, 
                address, 
                state, 
                client_GSTIN, 
                contact_no, 
                way_bill, 
                weight, 
                rate, 
                amount,
                GST_percentage, 
                total_amount, 
                created_by, 
                created_date,
                pickup_id
            )
            select 
                '". $bill_no ."' as invoice_no, 
                '". date('Y-m-d') ."' as  invoice_date,
                a.sender_name,
                a.sender_address, 
                '' as state,
                '' as gst,
                a.sender_phone, 
                a.bill_no,
                a.pickup_weight ,
                0 as rate,
                (a.courier_charges * 100 / (118)) as amount,
                '18.00' as GST_percentage,
                a.courier_charges, 
                '". $this->session->userdata('m_user_id') ."' as created_by,
                '". date('Y-m-d H:i:s') ."' as created_date,
                a.pickup_id 
            from rh_pickup_info as a 
            where a.pickup_id = $pickup_id 
        "; 
        
        $query = $this->db->query($sql);
        
       
       $invoice_id = $this->db->insert_id();
       
       echo "Successfully Invoice Created";
       
     }
     
    public function generate_waybill($pick_id, $ret = 0 ) // PDF
	{
	    //if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        
        $sql = "
             select
                a.pickup_id as pickup_ref_no,
                 a.booked_date,
                a.courier_type,
                a.source_pincode,
                a.source_pin_area,
                if(a.courier_type = 'Domestic', a.destination_pincode, b.country_name ) as destination_pincode, 
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '<br>' , a.contact_person_mobile , '<br>', a.pickup_address ),concat(a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address )) as pickup_address,
                a.sender_name,
                a.sender_phone,
                a.sender_address as source_address, 
                a.receiver_name ,
                a.receiver_phone,
                a.receiver_address as destination_address,
                concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), ifnull(c.package_weight_name,concat(a.package_weight,' Kgs')) ) ) as package_details,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.transport_mode,
                (if(a.packing_required = '1', 'Yes','No')) as packing_required, 
                a.special_instruction as package_content ,
                a.tracking_status,
                DATE_FORMAT(a.delivered_date,'%a %d-%m-%Y') as delivered_date
                from rh_pickup_info as a 
                left join rh_country_info as b on b.country_id = a.destination_country
                left join crit_package_weight as c on c.package_weight_id = a.package_weight_int
             where a.pickup_id = '". $pick_id. "'  and a.pickup_id != '2973'
             and a.status != 'Delete'
             order by a.booked_date desc                    
        "; 
        
        $query = $this->db->query($sql);
        
         $record_list = array();
       
        foreach ($query->result_array() as $row)
        {
            $record_list = $row;     
        } 
        
         
        $this->load->library('ciqrcode'); 
        $params['data'] = "https://pickmycourier.com/admin/index.php/generate-waybill-pdf/" . $record_list['pickup_ref_no'];
        $params['level'] = 'L';
        $params['size'] = 2;
        $params['savename'] = FCPATH.'tes.png';
        $this->ciqrcode->generate($params);
        
        $qr_img =  '<img src="'.base_url().'tes.png" />';
         
       
        
        $cnt = '<table class="table maintbl"> 
            <tr>
                <td valign="center" width="33%" align="center" class="llog">
                <div><strong>AWB No : '. $record_list['pickup_ref_no'] .'</strong></div>
                <br>'. $qr_img .'<br>
                Date : '.  date('d-m-Y', strtotime($row['booked_date'])) .'
                </td>
                <td valign="top" colspan="3" align="center" class="clog">
                    <img src="'. base_url('images/PMC-logo.jpg').'" alt="" class="img-responsive" width="200" />
                    <br><em>https://pickmycourier.com</em> <br>
                    <div><strong>PMCL PREMIUM EXPRESS WAY BILL</strong></div>
                </td> 
            </tr>
            <tr>
                <td colspan="2" class="laddr"><strong><span>Origin : '.  $record_list['source_pincode'] .'</span></strong></td>
                <td colspan="2" class="raddr"><strong><span>Destination : '. $record_list['destination_pincode'] .'</span></strong></td>
            </tr>
            <tr>
                <td valign="top" colspan="2" width="50%" class="laddr">
                <table width="100%" class="chltbl table" >
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>:</td>
                        <td>'. $record_list['sender_name'] .'</td>
                    </tr> 
                     <tr>
                        <td><strong>Address</strong></td>
                        <td>:</td>
                        <td>'. str_replace(',',',<br>', $record_list['source_address']).'</td>
                    </tr>
                    <tr>
                        <td><strong>Contact No</strong></td>
                        <td>:</td>
                        <td>'. $record_list['sender_phone'] .'</td>
                    </tr>
                </table>  
                  
                </td>
                <td valign="top" colspan="2" class="raddr"> 
                 <table width="100%" class="chltbl table">
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>:</td>
                        <td>'. $record_list['receiver_name'] .'</td>
                    </tr> 
                     <tr>
                        <td><strong>Address</strong></td>
                        <td>:</td>
                        <td>'. str_replace(',',',<br>', $record_list['destination_address']).'</td>
                    </tr>
                    <tr>
                        <td><strong>Contact No</strong></td>
                        <td>:</td>
                        <td>'. $record_list['receiver_phone'] .'</td>
                    </tr>
                </table>  
                </td> 
            </tr>
             
            <tr> 
                <td align="left" colspan="2" valign="top" class="raddr"><strong>Package Details:</strong> <br>'. $record_list['package_details'].'<br>'. $record_list['package_dimension'].' </td> 
                <td align="left" valign="top" class="raddr"><strong>Package Content:</strong> <br>'. $record_list['package_content'].' </td> 
                <td align="left" valign="top" class="raddr"><strong>Transport Mode:</strong> <br>'. $record_list['transport_mode'].' </td> 
            </tr>
            
        </table>'; 
        
        /*
        <tr>
                <td colspan="4" align="center"><h4 style="color:green;">Current Status : '. $record_list['tracking_status'] .'</h4></td>
            </tr>
            <tr>
                <td colspan="4" >
                    <div class="progress">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'. $record_list['tracking_status'] .'</div>
                    </div>    
                </td>
            </tr>
        */    
       
       if($ret == 0)
        return $cnt;
       else {
        
           
            
             $track_hist = array();
             $record_list['timeline'] = '
                        <li class="timeline-item">
							<div class="timeline-badge success"><i class="fa fa-check"></i></div>
							<div class="timeline-panel">
								<div class="timeline-heading">
									<b class="timeline-title">Booked</b>
									<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>  '. date('h:i a [ M d , Y ]', strtotime($record_list['booked_date'])) .'  </small></p>
								</div> 
							</div>
						</li>';
                        
           $sql ="
               select  
                a.status_datetime as t_date,
                a.tracking_status,
                a.city,
                a.remarks
                from crit_pmc_tracking_info as a 
                where a.tracking_status !='Delete'
                and a.pickup_id = '". $pick_id. "'
                order by a.status_datetime asc
           "; 
            $query = $this->db->query($sql);
            $cnt_sts = $query->num_rows(); 
        
                       
           
           if($cnt_sts > 0 ) {
                foreach ($query->result_array() as $row)
                {
                    $track_hist[] = $row;     
                    $record_list['timeline'] .= '
                            <li class="timeline-item">
    							<div class="timeline-badge success"><i class="fa fa-check"></i></div>
    							<div class="timeline-panel">
    								<div class="timeline-heading">
    									<b class="timeline-title">'. $row['tracking_status'].'</b>
    									<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>  '. date('h:i a [ M d , Y ]', strtotime($row['t_date'])) .' </small><br>'.  $row['city']  .'<br>'.  $row['remarks']  .'</p>
    									 
    								</div> 
    							</div>
    						</li>'; 
                } 
                
            } else {
                
                $sql = "
                     select 
                        a.tracking_status , 
                        a.created_date  ,
                        a.delivered_to,
                        a.delivery_person_id
                        from crit_tracking_info as a 
                        where a.pickup_id = '". $pick_id. "'
                        order by a.created_date asc                   
                "; 
                
                $query = $this->db->query($sql);
                $cnt1 = $query->num_rows(); 
                
                foreach ($query->result_array() as $row)
                {
                    $track_hist[] = $row;     
                    $record_list['timeline'] .= '
                            <li class="timeline-item">
    							<div class="timeline-badge success"><i class="fa fa-check"></i></div>
    							<div class="timeline-panel">
    								<div class="timeline-heading">
    									<b class="timeline-title">'. $row['tracking_status'].'</b>
    									<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>  '. date('h:i a [ M d , Y ]', strtotime($row['created_date'])) .' </small></p>
    								</div> 
    							</div>
    						</li>'; 
                } 
            }
            
        
            $record_list['waybill'] = $cnt;
            if($record_list['tracking_status'] != 'Delivered')
            {
                if(!empty($record_list['delivered_date']))
                    $record_list['edd'] = "<h5>Estimate Delivery Date On <span>" . $record_list['delivered_date'] . " at 20:00<span></h5> ";
                else
                    $record_list['edd'] = '';    
            } else
            {
                $record_list['edd'] = "<h5>On <span>" . $record_list['delivered_date'] . "<span></h5> ";
            }
            
          if($record_list['courier_type'] == 'Domestic'){
            $origin = $this->get_pincode_state_district($record_list['source_pincode']);
            $destination = $this->get_pincode_state_district($record_list['destination_pincode']);
            if(!empty($origin)) { 
            $record_list['origin'] = ' Origin : ' . $record_list['source_pincode'] . ' [ ' . $origin['state'] . ' - ' . strtolower($origin['district']). ' ] ';
            if(isset($destination['state']))
            $record_list['destination'] = ' Destination : ' . $record_list['destination_pincode'] . ' [ ' . $destination['state'] . ' - ' . strtolower($destination['district']) . ' ] ';
            else 
            $record_list['destination'] = ' Destination : ' . $record_list['destination_pincode'] ;
            } else {
                $record_list['origin'] = ' Origin : ' . $record_list['source_pincode']  ;
                $record_list['destination'] = 'Destination : ' . $record_list['destination_pincode'];
             }
          } else { 
            $origin = $this->get_pincode_state_district($record_list['source_pincode']);
             if(!empty($origin)) { 
             $record_list['origin'] = ' Origin : ' . $record_list['source_pincode'] . ' [ ' . $origin['state'] . ' - ' . strtolower($origin['district']) . ' ] ';
             $record_list['destination'] = 'Destination : ' . $record_list['destination_pincode'];
             } else {
                $record_list['origin'] = ' Origin : ' . $record_list['source_pincode']  ;
                $record_list['destination'] = 'Destination : ' . $record_list['destination_pincode'];
             }
          }  
            
            
            
        return  $record_list;
       }
	}
    
    public function generate_waybill_pdf($pick_id) // PDF
	{
	    //if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        
        $sql = "
             select
                a.pickup_id as pickup_ref_no,
                DATE_FORMAT(a.booked_date,'%d-%m-%Y') as booked_date,
                a.courier_type,
                a.source_pincode,
                if(a.courier_type = 'Domestic', a.destination_pincode, b.country_name ) as destination_pincode,
                
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '<br>' , a.contact_person_mobile , '<br>', a.pickup_address ),concat(a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address )) as pickup_address,
                a.sender_name,
                a.sender_phone,
                a.sender_address as source_address, 
                a.receiver_name ,
                a.receiver_phone,
                a.receiver_address as destination_address,
                concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), c.package_weight_name ) ) as package_details,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.transport_mode,
                (if(a.packing_required = '1', 'Yes','No')) as packing_required, 
                a.special_instruction as package_content 
                from rh_pickup_info as a 
                left join rh_country_info as b on b.country_id = a.destination_country
                left join crit_package_weight as c on c.package_weight_id = a.package_weight_int
             where a.pickup_id = '". $pick_id. "'
             and a.status != 'Delete'
             order by a.booked_date desc                    
        "; 
        
        $query = $this->db->query($sql);
        
         $record_list = array();
       
        foreach ($query->result_array() as $row)
        {
            $record_list = $row;     
        } 
        
         
        $this->load->library('ciqrcode'); 
        $params['data'] = "https://pickmycourier.com/admin/index.php/generate-waybill-pdf/" . $record_list['pickup_ref_no'];
        $params['level'] = 'L';
        $params['size'] = 2;
        $params['savename'] = FCPATH.'tes.png';
        $this->ciqrcode->generate($params);
        
        $qr_img =  '<img src="'.base_url().'tes.png" />';
         
       
        
        $cnt = '<table class="table"> 
            <tr>
                <td valign="center" width="33%" align="center" class="llog">
                <div><strong>AWB No : '. $record_list['pickup_ref_no'] .'</strong></div>
                <br>'. $qr_img .'<br>
                Date : '. $record_list['booked_date'] .'
                </td>
                <td valign="top" colspan="3" align="center" class="clog">
                    <img src="'. base_url('images/PMC-logo.jpg').'" alt="" class="img-responsive" width="200" />
                    <br><em>https://pickmycourier.com</em> <br>
                    <div>PMCL PREMIUM EXPRESS WAY BILL</div>
                </td> 
            </tr>
            <tr>
                <td colspan="2" class="laddr"><strong><span>Origin : '.  $record_list['source_pincode'] .'</span></strong></td>
                <td colspan="2" class="raddr"><strong><span>Destination : '. $record_list['destination_pincode'] .'</span></strong></td>
            </tr>
            <tr>
                <td valign="top" colspan="2" width="50%" class="laddr">
                <table width="100%" class="chltbl table">
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>:</td>
                        <td>'. $record_list['sender_name'] .'</td>
                    </tr> 
                     <tr>
                        <td><strong>Address</strong></td>
                        <td>:</td>
                        <td>'. str_replace(',',',<br>', $record_list['source_address']).'</td>
                    </tr>
                    <tr>
                        <td><strong>Contact No</strong></td>
                        <td>:</td>
                        <td>'. $record_list['sender_phone'] .'</td>
                    </tr>
                </table>  
                  
                </td>
                <td valign="top" colspan="2" class="raddr"> 
                 <table width="100%" class="chltbl table" border="0">
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>:</td>
                        <td>'. $record_list['receiver_name'] .'</td>
                    </tr> 
                     <tr>
                        <td><strong>Address</strong></td>
                        <td>:</td>
                        <td>'. str_replace(',',',<br>', $record_list['destination_address']).'</td>
                    </tr>
                    <tr>
                        <td><strong>Contact No</strong></td>
                        <td>:</td>
                        <td>'. $record_list['receiver_phone'] .'</td>
                    </tr>
                </table>  
                </td> 
            </tr>
             
            <tr> 
                <td align="left" colspan="2" valign="top" class="raddr"><strong>Package Details:</strong> <br>'. $record_list['package_details'].'<br>'. $record_list['package_dimension'].' </td> 
                <td align="left" valign="top" class="raddr"><strong>Package Content:</strong> <br>'. $record_list['package_content'].' </td> 
                <td align="left" valign="top" class="raddr"><strong>Transport Mode:</strong> <br>'. $record_list['transport_mode'].' </td> 
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>'; 
         
            
        $head = '  
         <!DOCTYPE HTML> 
        <head> 
        	<title>PMC</title>
            <style>
                 table {border:1px solid silver; font-family: "Times New Roman", Times, serif;margin-top:20px;} 
                .chltbl {border:0px solid silver;padding:10px} 
                .chltbl td {padding:5px; vertical-align:top;} 
                .llog { border-right:1px solid silver; border-bottom:1px solid silver;padding:5px;  }
                .clog { border-right:0px solid silver; border-bottom:1px solid silver;padding:5px;  }
                .rlog { border-right:0px solid silver; border-bottom:1px solid silver;padding:5px;  }
                .laddr { border-right:1px solid silver; border-bottom:1px solid silver;padding:5px; text-transform: capitalize; }
                .raddr { border-right:0px solid silver; border-bottom:1px solid silver;padding:5px; text-transform: capitalize;  }
                .laddr span { color:green; letter-spacing: 2px;} 
                .raddr span { color:red; letter-spacing: 2px; } 
                .clog div { color:#fff; letter-spacing: 2px; background-color:orange; padding:5px;  } 
                .llog div { color:#000; letter-spacing: 2px; background-color:orange; padding:5px; } 
            </style>
        </head> 
        <body> ';
             
        $footer = '</body>
        </html>
        ';    
        
       /* $this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('PMCL - Way Bill');
		$pdf->SetHeaderMargin(10);
		$pdf->SetTopMargin(10);
		$pdf->setFooterMargin(10);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('PMCL - Tamilselvan');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();
		//$pdf->Write(5, 'Some sample text');
		//$pdf->writeHTML($cnt, true, false, true, false, '');
		$pdf->writeHTML( $head . $cnt . $footer);
		//$pdf->Output('My-File-Name.pdf', 'I');
		$pdf->Output('pdf_invoice/PMCL-AWB-'.  $record_list['pickup_ref_no'] .'.pdf', 'F');  
        
        header("location: ../../pdf_invoice/PMCL-AWB-".  $record_list['pickup_ref_no'] . ".pdf "); */
        
        echo  $head . $cnt . $footer;
        

         
	}
    
    public function way_bill($awb)
    {
        $ret_arry = $this->generate_waybill($awb , 1); 
        
        header('Content-Type: application/x-json; charset=utf-8');

       echo (json_encode($ret_arry));  
         
    }
    
    
    public function service_provider_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'service-provider.inc';  
        
        /*$re = $this->get_tracking('101236457',0);
        echo "<pre>";
        print_r($re[0]->ConsignmentDetailsMSTrackList);
        echo "</pre>";
        exit;*/
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'service_provider_name' => $this->input->post('service_provider_name'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('rh_service_provider_info', $ins); 
            redirect('service-provider-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'service_provider_name' => $this->input->post('service_provider_name'),
                    'status' => $this->input->post('status')  ,                
            );
            
            $this->db->where('service_provider_id', $this->input->post('service_provider_id'));
            $this->db->update('rh_service_provider_info', $upd); 
                            
            redirect('service-provider-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('rh_service_provider_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('service-provider-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.service_provider_id,
                a.service_provider_name,   
                a.status        
                from rh_service_provider_info as a  
                where status != 'Delete'
                order by a.service_provider_name asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('service-provider-list',$data); 
	}
    
    public function pincode_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pincode.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                'pincode' => $this->input->post('pincode'),
                'area_name' => $this->input->post('area_name'),
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'status' => $this->input->post('status'),                           
            );
            
            $this->db->insert('crit_pincode_info', $ins); 
            redirect('pincode-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                'pincode' => $this->input->post('pincode'),
                'area_name' => $this->input->post('area_name'),
                'state_name' => $this->input->post('state_name'),
                'district_name' => $this->input->post('district_name'),
                'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('pincode_id', $this->input->post('pincode_id'));
            $this->db->update('crit_pincode_info', $upd); 
                            
            redirect('pincode-list/' . $this->uri->segment(2, 0)); 
        } 
        
        
        $query = $this->db->query("select state_name , state_name as state_code  from crit_pincode_info as a where a.status= 'Active' group by state_name order by state_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state_code']] = $row['state_name'];     
        } 
        
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_area'] = $srch_area = $this->input->post('srch_area');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_area', $this->input->post('srch_area'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_area'] = $srch_area = $this->session->userdata('srch_area') ;
       }
       
       if(!empty($srch_state)){
        $where = " a.state_name = '" . $srch_state . "'";
        $where .= " and (a.area_name like '%" . $srch_area . "%' or a.pincode like '%". $srch_area ."%' or a.district_name like '%". $srch_area ."%') ";
         
       } else {
        $where = " a.state_name = 'Tamil Nadu' ";
        $this->session->set_userdata('srch_state', 'Tamil Nadu');
        $data['srch_state'] = $srch_state =  'Tamil Nadu';
        $data['srch_area'] = $srch_area =  '';
       }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->where($where);
        $this->db->from('crit_pincode_info as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pincode-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 25;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                pincode_id, 
                pincode, 
                state_name, 
                district_name, 
                circle_name, 
                region_name, 
                division_name, 
                area_name as area, 
                `status`
                from crit_pincode_info as a 
                where status != 'Delete' and ". $where ."
                order by  a.pincode  asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        $data['record_list'] = array();
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pincode-list-new',$data); 
	}  
    public function pincode_list_old()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        	    
        $data['js'] = 'pincode.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                'pincode' => $this->input->post('pincode'),
                'area' => $this->input->post('area'),
                'state_code' => $this->input->post('state_code'),
                'status' => $this->input->post('status'),                           
            );
            
            $this->db->insert('rh_pincode_list', $ins); 
            redirect('pincode-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                'pincode' => $this->input->post('pincode'),
                'area' => $this->input->post('area'),
                'state_code' => $this->input->post('state_code'),
                'status' => $this->input->post('status'),                 
            );
            
            $this->db->where('pincode_id', $this->input->post('pincode_id'));
            $this->db->update('rh_pincode_list', $upd); 
                            
            redirect('pincode-list/' . $this->uri->segment(2, 0)); 
        } 
        
        
        $query = $this->db->query("select state_name , state_code  from rh_states_info as a where a.status= 'Active' order by state_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state_code']] = $row['state_name'];     
        } 
        
        
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_area'] = $srch_area = $this->input->post('srch_area');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_area', $this->input->post('srch_area'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ;
           $data['srch_area'] = $srch_area = $this->session->userdata('srch_area') ;
       }
       
       if(!empty($srch_state)){
        $where = " a.state_code = '" . $srch_state . "'";
        $where .= " and (a.area like '%" . $srch_area . "%' or a.pincode like '%". $srch_area ."%') ";
         
       } else {
        $where = " a.state_code = 'TN' ";
        $this->session->set_userdata('srch_state', 'TN');
        $data['srch_state'] = $srch_state =  'TN';
        $data['srch_area'] = $srch_area =  '';
       }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->where($where);
        $this->db->from('rh_pincode_list as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pincode-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 15;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.pincode_id,
                a.pincode,
                a.area,
                a.`status`
                from rh_pincode_list as a 
                where status != 'Delete' and ". $where ."
                order by  a.pincode  asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        $data['record_list'] = array();
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pincode-list-old',$data); 
	} 
    
    public function international_rate_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'international.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
             $ins = array(
                    'country' => $this->input->post('country'),
                    'package_type' => $this->input->post('package_type'),
                    'package_weight' => $this->input->post('package_weight'),
                    'rate' => $this->input->post('rate'),
                    'status' => $this->input->post('status')                      
            );
            
            $this->db->insert('crit_international_rate', $ins);
            
            redirect('international-rate');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'country' => $this->input->post('country'),
                    'package_type' => $this->input->post('package_type'),
                    'package_weight' => $this->input->post('package_weight'),
                    'rate' => $this->input->post('rate'),
                    'status' => $this->input->post('status')                        
            );
            
            $this->db->where('international_rate_id', $this->input->post('international_rate_id'));
            $this->db->update('crit_international_rate', $upd);  
            
            redirect('international-rate/' . $this->uri->segment(2, 0)); 
        } 
        
        
        $query = $this->db->query("select country_name , country_id  from rh_country_info as a where a.status= 'Active' order by country_name asc ");
        
        //$data['state_info'][] = 'Select the State';

        foreach ($query->result_array() as $row)
        {
            $data['country_info'][$row['country_id']] = $row['country_name'];     
        } 
        
        $query = $this->db->query("select a.package_type_id,  a.package_type  from crit_package_type as a where a.status='Active'   order by  a.package_type asc ");
        
        $data['package_type_opt'] = array('','Select Package Type'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['package_type_opt'][$row['package_type_id']] = $row['package_type']   ;    
        }  
        
        $query = $this->db->query("select a.package_weight_id,  a.package_weight_name  from crit_package_weight as a where a.status='Active'   order by  a.package_weight_id asc ");
        
        $data['weight_opt'] = array('','Select Weight'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['weight_opt'][$row['package_weight_id']] = $row['package_weight_name']   ;    
        }  
        
        
       if(isset($_POST['srch_country'])) {
           $data['srch_country'] = $srch_state = $this->input->post('srch_country'); 
           $this->session->set_userdata('srch_country', $this->input->post('srch_country')); 
       }
       if($this->session->userdata('srch_country')){
           $data['srch_country'] = $srch_country = $this->session->userdata('srch_country') ; 
       }
       
       if(!empty($srch_country)){
        $where = " a.country = '" . $srch_country . "'"; 
         
       } else {
        $where = " a.country = '142' ";
        $this->session->set_userdata('srch_country', '142');
        $data['srch_country'] = $srch_country =  '142'; 
       }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->where($where);
        $this->db->from('crit_international_rate as a');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('international-rate/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 15;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = " 
            select
                 a.international_rate_id as id,
                 b.country_name as country,
                 c.package_type,
                 d.package_weight_name,
                 a.rate,
                 a.status
                 from crit_international_rate as a  
                 left join rh_country_info as b on b.country_id = a.country
                 left join crit_package_type as c on c.package_type_id = a.package_type
                 left join crit_package_weight as d on d.package_weight_id = a.package_weight
                 where a.status !='Delete' and a.country = '". $srch_country ."'              
                 order by a.status , b.country_name , c.package_type ,a.package_weight asc
            limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
        ";
        
        $data['record_list'] = array();
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('international-rate',$data); 
	}  
    
    
    public function international_rate_list_v2()
    {
        if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'international-rate-v2.inc';
        
        if($this->input->post('btn_save') == 'Save')
        {
            
            $zone_id = $this->input->post('zone_id');
            $international_service_provider_id = $this->input->post('international_service_provider_id');
            $package_type_id = $this->input->post('package_type_id');
            $package_weight_ids = $this->input->post('package_weight_id');
            $rates = $this->input->post('rate');
             
            foreach($package_weight_ids as $k => $package_weight_id) {
            
                $this->db->where('zone_id', $zone_id);
                $this->db->where('international_service_provider_id', $international_service_provider_id);
                $this->db->where('package_type_id', $package_type_id);
                $this->db->where('package_weight_id', $package_weight_id);
                $this->db->delete('crit_international_rate_info_v2'); 
                
                $ins = array(
                        'zone_id' => $zone_id,
                        'international_service_provider_id' => $international_service_provider_id,
                        'package_type_id' => $package_type_id,
                        'package_weight_id' => $package_weight_id,
                        'rate' => $rates[$k],  
                        'status' => 'Active',                          
                );
                
                $this->db->insert('crit_international_rate_info_v2', $ins);  
            
            }
            redirect('international-rate-v2');
        }
        
       
       if(isset($_POST['srch_pkg_type'])) {
           $data['srch_pkg_type'] = $srch_pkg_type = $this->input->post('srch_pkg_type');
           $data['srch_pkg_weight'] = $srch_pkg_weight = $this->input->post('srch_pkg_weight');
           $data['srch_zone_id'] = $srch_zone_id = $this->input->post('srch_zone_id');
           $data['srch_service_provider_id'] = $srch_service_provider_id = $this->input->post('srch_service_provider_id');
           $this->session->set_userdata('srch_pkg_type', $this->input->post('srch_pkg_type'));
           $this->session->set_userdata('srch_pkg_weight', $this->input->post('srch_pkg_weight'));  
           $this->session->set_userdata('srch_zone_id', $this->input->post('srch_zone_id'));  
           $this->session->set_userdata('srch_service_provider_id', $this->input->post('srch_service_provider_id'));  
       }
       elseif($this->session->userdata('srch_pkg_type')){
           $data['srch_pkg_type'] = $srch_pkg_type = $this->session->userdata('srch_pkg_type') ;
           $data['srch_pkg_weight'] = $srch_pkg_weight = $this->session->userdata('srch_pkg_weight') ;  
           $data['srch_zone_id'] = $srch_zone_id = $this->session->userdata('srch_zone_id') ;  
           $data['srch_service_provider_id'] = $srch_service_provider_id = $this->session->userdata('srch_service_provider_id') ;  
       } else {
            $data['package_weight_opt'] = array();
            $data['srch_pkg_type'] = $srch_pkg_type = '';
            $data['srch_pkg_weight'] = $srch_pkg_weight = '';
            $data['srch_zone_id'] = $srch_zone_id = '';
            $data['srch_service_provider_id'] = $srch_service_provider_id = '';
            $data['record_list'] = array();
            
       }  
        
         if(!empty($srch_service_provider_id)){
            
          $this->db->query('SET SQL_BIG_SELECTS=1');
            
        
           $sql = "
                   select 
                    a.package_weight_id,
                    a.package_weight_range,
                    b.package_type_id,
                    b.package_type as package_type_name,
                    e.zone_id,
                    e.zone_name,
                    c.international_service_provider_id,
                    c.international_service_provider ,
                    c.additional_info,
                    GROUP_CONCAT(g.country_name) as country,
                    d.rate,
                    d.margin_percentage
                    from crit_package_weight_info as a 
                    left join crit_package_type as b on b.package_type_id = a.package_type_id
                    left join crit_international_service_provider_info as c on  1=1 
                    left join crit_zone_info as e on 1=1
                    left join crit_international_rate_info_v2 as d on d.international_service_provider_id = c.international_service_provider_id 
                    and d.package_type_id = b.package_type_id and d.package_weight_id = a.package_weight_id and d.`status` = 'Active' 
                    and d.zone_id = e.zone_id
                    left join crit_sp_zone_country_info as f on f.intl_sp_id = c.international_service_provider_id and f.zone_id = e.zone_id
                    left join rh_country_info as g on FIND_IN_SET(g.country_id ,  f.country_ids)                    
                    where a.status = 'Active' and  b.status = 'Active' and  b.status = 'Active' 
                    and b.package_type_id =  '". $srch_pkg_type ."'
                    and e.zone_id =  '". $srch_zone_id ."' and c.international_service_provider_id = '". $srch_service_provider_id ."'"; 
            if(!empty($srch_pkg_weight))
                $sql.=" and a.package_weight_id  = '". $srch_pkg_weight."'   ";
                    
            $sql .="group by c.international_service_provider_id , e.zone_id ,b.package_type_id, a.package_weight_id  ";
            $sql .="order by c.international_service_provider asc  ";
            
            
           // echo $sql ;
             
            $query = $this->db->query($sql);
           
            $data['record_list'] = array();
            foreach ($query->result_array() as $row)
            {
                $data['record_list'][] = $row;     
            }
        
        
            $sql = "
                    select 
                    a.package_weight_id,
                    a.package_weight_range             
                    from crit_package_weight_info as a 
                    where a.status = 'Active' and a.package_type_id = $srch_pkg_type
                    order by a.package_weight_id asc                 
            "; 
            
            $query = $this->db->query($sql);
           
            foreach ($query->result_array() as $row)
            {
                $data['package_weight_opt'][$row['package_weight_id']] = $row['package_weight_range'];     
            } 
        }
        
        $sql = "
                select 
                a.package_type_id,                
                a.package_type as package_type_name             
                from crit_package_type as a  
                where status = 'Active' 
                order by a.package_type_id asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['package_type_opt'][$row['package_type_id']] = $row['package_type_name'] ;     
        }
        
      /*  $sql = "
                select 
                a.country_id,              
                a.country_name              
                from rh_country_info as a  
                where status = 'Active'
                order by  a.country_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country_id']] = $row['country_name'];     
        }*/
        
        $sql = "
                select 
                a.zone_id,              
                a.zone_name              
                from crit_zone_info as a  
                where status = 'Active'
                order by a.sort, a.zone_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['zone_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['zone_opt'][$row['zone_id']] = $row['zone_name'];     
        }
        
        $sql = "
                select 
                a.international_service_provider_id,              
                a.international_service_provider              
                from crit_international_service_provider_info as a  
                where status = 'Active'
                order by  a.international_service_provider asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['service_provider_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['service_provider_opt'][$row['international_service_provider_id']] = $row['international_service_provider'];     
        }
        
        //$data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('international-rate-v2',$data); 
    }
    
    
    public function package_weight() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'package-weight.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'package_weight_name' => $this->input->post('package_weight'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_package_weight', $ins); 
            redirect('package-weight-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'package_weight_name' => $this->input->post('package_weight'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('package_weight_id', $this->input->post('package_weight_id'));
            $this->db->update('crit_package_weight', $upd); 
                            
            redirect('package-weight-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_package_weight');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('package-weight-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.package_weight_id,
                a.package_weight_name as package_weight,                
                a.status
                from crit_package_weight as a 
                where status != 'Delete'
                order by a.status asc , a.package_weight_id asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('package-weight-list',$data); 
	} 
    
    
    public function non_dox_sub_category_charges() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'non-doc-sub-category-charges.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'category_name' => $this->input->post('category_name'),
                    'sub_category_name' => $this->input->post('sub_category_name'),
                    'fixed_charges' => $this->input->post('fixed_charges'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_non_dox_sub_category_info', $ins); 
            redirect('non-doc-sub-category-charges-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'category_name' => $this->input->post('category_name'),
                    'sub_category_name' => $this->input->post('sub_category_name'),
                    'fixed_charges' => $this->input->post('fixed_charges'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('non_dox_sub_category_id', $this->input->post('non_dox_sub_category_id'));
            $this->db->update('crit_non_dox_sub_category_info', $upd); 
                            
            redirect('non-doc-sub-category-charges-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_non_dox_sub_category_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('non-doc-sub-category-charges-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.non_dox_sub_category_id,
                a.category_name,                
                a.sub_category_name,                
                a.fixed_charges,                
                a.status
                from crit_non_dox_sub_category_info as a 
                where status != 'Delete'
                order by a.status asc ,a.category_name, a.sub_category_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('non-doc-sub-category-charges-list',$data); 
	} 
    
    public function package_type() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'package-type.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'package_type' => $this->input->post('package_type'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_package_type', $ins); 
            redirect('package-type-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'package_type' => $this->input->post('package_type'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('package_type_id', $this->input->post('package_type_id'));
            $this->db->update('crit_package_type', $upd); 
                            
            redirect('package-type-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_package_type');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('package-type-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.package_type_id,
                a.package_type,                
                a.status
                from crit_package_type as a 
                where status != 'Delete'
                order by a.status asc , a.package_type asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('package-type-list',$data); 
	} 
    
    
    
    public function news_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'news.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'news_date' => $this->input->post('news_date'),
                    'news_heading' => $this->input->post('news_heading'),
                    'news_content' => $this->input->post('news_content'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_news_info', $ins); 
            redirect('news-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                   'news_date' => $this->input->post('news_date'),
                    'news_heading' => $this->input->post('news_heading'),
                    'news_content' => $this->input->post('news_content'),
                    'status' => $this->input->post('status')  ,               
            );
            
            $this->db->where('news_id', $this->input->post('news_id'));
            $this->db->update('crit_news_info', $upd); 
                            
            redirect('news-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_news_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('news-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.news_id,
                a.news_date,                
                a.news_heading,                
                a.news_content,                
                a.status
                from crit_news_info as a 
                where status != 'Delete'
                order by a.status asc , a.news_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('news-list',$data); 
	} 
    
    public function pickup_recover() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'pickup-recover.inc';  
        
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Delete');
        $this->db->from('rh_pickup_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pickup-recover/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.pickup_id,
                a.booked_date,
                a.courier_type,
                a.source_pincode,
                a.sender_name,
                a.sender_phone,
                a.sender_address,
                a.destination_pincode,
                a.destination_country,
                a.receiver_name,
                a.receiver_phone,
                a.receiver_address,
                a.package_type,
                a.package_weight,
                a.packing_required
                from rh_pickup_info as a 
                where status = 'Delete'
                order by a.pickup_id desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pickup-recover',$data); 
	} 
    
    
    public function domestic_rate() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'domestic.inc';  
        
       // $this->db->query("update rh_pickup_info as a set a.booked_date = '2020-06-06 18:23:11' where a.pickup_id = 02030 ");
        
       // echo $this->get_distance('636001','641034');
        
        
       /* if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'flg_state' => $this->input->post('flg_state'),
                    'flg_city' => $this->input->post('flg_city'),
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'), 
                    'rate_as_on' => date('Y-m-d'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('rh_courier_charges_info', $ins); 
            redirect('domestic-rate-list');
        }*/
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'flg_state' => $this->input->post('flg_state'),
                    'flg_city' => $this->input->post('flg_city'),
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),              
                    'c_type' => $this->input->post('c_type'),              
                    'rate_as_on' => date('Y-m-d')           
            );
            
            $this->db->where('courier_charges_id', $this->input->post('courier_charges_id'));
            $this->db->update('rh_courier_charges_info', $upd); 
                            
            redirect('domestic-rate'); 
        } 
         
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->from('rh_courier_charges_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('domestic-rate-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.courier_charges_id,
                a.c_type,
                a.rate_as_on,
                a.flg_state, 
                a.flg_city, 
                a.min_weight, 
                a.min_charges, 
                a.addt_weight, 
                a.addt_charges, 
                a.`status`
                from rh_courier_charges_info as a 
                where status = 'Active'
                order by a.status asc , a.flg_state asc , a.flg_city asc
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['c_type']][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('domestic-rate-list',$data); 
	}
    
    public function domestic_rate_v2() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'domestic-v2.inc';  
        
       
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0),  
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),              
                    'c_type' => $this->input->post('c_type'),              
                    'rate_as_on' => date('Y-m-d')           
            );
            
            $this->db->where('domestic_rate_id', $this->input->post('domestic_rate_id'));
            $this->db->update('crit_domestic_rate_info', $upd); 
                            
            redirect('domestic-rate-v2'); 
        } 
         
        /*
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->from('rh_courier_charges_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('domestic-rate-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $sql = "
                select 
                a.domestic_rate_id, 
                a.c_type,
                a.rate_as_on,
                a.flg_region, 
                a.flg_state, 
                a.flg_city, 
                a.flg_metro,
                a.min_weight, 
                a.min_charges, 
                a.addt_weight, 
                a.addt_charges, 
                a.`status`
                from crit_domestic_rate_info as a 
                where status = 'Active'
                order by a.c_type, a.flg_region asc , a.flg_state asc , a.flg_city ,a.flg_metro asc                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['c_type']][] = $row;     
        }
        
         
        
        $this->load->view('domestic-rate-list-v2',$data); 
	}
    
    public function domestic_rate_v3() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN && $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'domestic-v3.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0),  
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),              
                    'c_type' => $this->input->post('c_type'),              
                    'from_weight' => $this->input->post('from_weight'),              
                    'to_weight' => $this->input->post('to_weight'),              
                    'rate_as_on' => date('Y-m-d')           
            );
            
            $this->db->insert('crit_domestic_rate_info_v3', $ins); 
                            
            redirect('domestic-rate-v3'); 
        } 
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'flg_region' => ($this->input->post('flg_region') == 1 ?  1 : 0),
                    'flg_state' => ($this->input->post('flg_state') == 1 ?  1 : 0),
                    'flg_city' => ($this->input->post('flg_city') == 1 ?  1 : 0), 
                    'flg_metro' => ($this->input->post('flg_metro') == 1 ?  1 : 0),  
                    'min_weight' => $this->input->post('min_weight'),
                    'min_charges' => $this->input->post('min_charges'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_charges' => $this->input->post('addt_charges'),              
                    'c_type' => $this->input->post('c_type'),              
                    'from_weight' => $this->input->post('from_weight'),              
                    'to_weight' => $this->input->post('to_weight'),            
                    'rate_as_on' => date('Y-m-d')           
            );
            
            $this->db->where('domestic_rate_id', $this->input->post('domestic_rate_id'));
            $this->db->update('crit_domestic_rate_info_v3', $upd); 
                            
            redirect('domestic-rate-v3'); 
        } 
         
         
        
        $sql = "
                select 
                a.domestic_rate_id, 
                a.c_type,
                a.from_weight,
                a.to_weight,
                a.rate_as_on,
                a.flg_region, 
                a.flg_state, 
                a.flg_city, 
                a.flg_metro,
                a.min_weight, 
                a.min_charges, 
                a.addt_weight, 
                a.addt_charges, 
                a.`status`
                from crit_domestic_rate_info_v3 as a 
                where status = 'Active'
                order by a.c_type,a.from_weight , a.to_weight ,a.flg_region asc , a.flg_state asc , a.flg_city ,a.flg_metro asc                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][$row['c_type']][$row['from_weight'] .' - ' . $row['to_weight']][] = $row;     
        }
        
         
        
        $this->load->view('domestic-rate-list-v3',$data); 
	}
    
    public function packing_charges() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'packing-charges.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            
            $this->db->where('packing_charge_id', $this->input->post('packing_charge_id'));
            $this->db->update('crit_packing_charge_info', array('status' => 'In-Active'));
            
            
            $ins = array(
                    'init_weight' => $this->input->post('init_weight'),
                    'packing_charge_per_kg' => $this->input->post('packing_charge_per_kg'),
                    'addt_weight' => $this->input->post('addt_weight'),
                    'addt_packing_charge' => $this->input->post('addt_packing_charge'),
                    'status' => 'Active'                           
            );
            
            $this->db->insert('crit_packing_charge_info', $ins); 
            redirect('packing-charges');
            
        }
        
        /* 
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->from('crit_packing_charge_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('packing-charges/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   */
        
        $sql = "
                select 
                a.packing_charge_id,
                a.created_date,
                a.init_weight,
                a.packing_charge_per_kg,
                a.addt_weight,
                a.addt_packing_charge 
                from crit_packing_charge_info as a 
                where status = 'Active'
                order by a.packing_charge_per_kg asc 
                           
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        //$data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('packing-charges',$data); 
	} 
    
    public function user_list($id ='')
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN )
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
  
        $data['js'] = 'user.inc';
        
         $data['mod'] = 'Add';
         
         //echo md5(md5('Elbex@PMC20202'));
        
        
            if($this->input->post('mode') == 'Add')
            {
                $ins = array(
                        'user_name' => $this->input->post('user_name'),
                        'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                        'first_name' => $this->input->post('first_name') ,                       
                        'email' => $this->input->post('email') ,                       
                        'mobile' => $this->input->post('mobile') ,                       
                        'state' => $this->input->post('state') ,                       
                        'city' => $this->input->post('serv_city') ,  
                        'level' => 1 ,                     
                       // 'pincodes' => implode(',', $this->input->post('pincode'))                     
                );
                
                $this->db->insert('rh_user_info', $ins); 
                redirect('user-list');
            } 
            
            if($this->input->post('mode') == 'Edit')
            {
                $upd = array(
                        'user_name' => ($this->input->post('user_name')),
                        'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                        'first_name' => $this->input->post('first_name') ,                       
                        'email' => $this->input->post('email') ,                       
                        'mobile' => $this->input->post('mobile') ,                       
                        'state' => $this->input->post('state') ,                       
                        'city' => $this->input->post('serv_city') ,                       
                        'status' => $this->input->post('status')                       
                       // 'pincodes' => implode(',', $this->input->post('pincode'))                     
                );
                
                $this->db->where('user_id', $this->input->post('user_id'));
                $this->db->update('rh_user_info', $upd); 
                                
                redirect('user-list');
                //print_r($upd); exit();
            } 
            
        
        if($id != '') {
        
            $query = $this->db->query("select * from rh_user_info as a  where a.user_id = '". $id."' order by a.status asc ,  a.user_name asc  ");
       
            foreach ($query->result_array() as $row)
            {
                $data['edit_info'] = $row;     
            }
            
            $data['mod'] = 'Edit';
            
           /* $data['serv_city'][] = "Select Area";
            
            $query = $this->db->query("select UCASE(a.area) as area  from rh_pincode_list as a where a.state_code = '". $data['edit_info']['state']."' group by a.area  ");
            
            foreach ($query->result_array() as $row)
            {
                $data['serv_city'][$row['area']] = $row['area'];     
            } 
            
            $query = $this->db->query("select pincode from rh_pincode_list as a where a.area = '". $data['edit_info']['city']."' order by a.pincode asc ");
            
            
            $data['pincode_info'] = array();
            
            foreach ($query->result_array() as $row)
            {
                $data['pincode_info'][$row['pincode']] = $row['pincode'];     
            } */
            
        }
	    
        
        $query = $this->db->query("
        select 
            a.user_id,
            a.first_name ,
            a.user_name , 
            a.email,
            a.mobile,
            a.level,
            a.city, 
            a.status ,
            a.pwd ,
            '' as last_login_date 
         from rh_user_info as a  
         where a.status != 'Delete' and a.level = 1
         order by a.status asc , a.user_name asc  ");
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
    
    
        
        
       /* $query = $this->db->query("select state_name , state_code  from rh_states_info as a where 1=1  order by state_name asc ");
        
        $data['state_info'][] = 'Select';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state_code']] = $row['state_name'];     
        }  
        */
        
        
        $this->load->view('user-list',$data); 
	}   
    
    public function marketing_user_list($id ='')
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
  
        $data['js'] = 'user.inc';
        
         $data['mod'] = 'Add';
        
        
            if($this->input->post('mode') == 'Add')
            {
                $ins = array(
                        'user_name' => $this->input->post('user_name'),
                        'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                        'first_name' => $this->input->post('first_name') ,                       
                        'email' => $this->input->post('email') ,                       
                        'mobile' => $this->input->post('mobile') ,                       
                        'state' => $this->input->post('state') ,                       
                        'city' => $this->input->post('serv_city') ,  
                        'level' => 5 ,                     
                       // 'pincodes' => implode(',', $this->input->post('pincode'))                     
                );
                
                $this->db->insert('rh_user_info', $ins); 
                redirect('marketing-user-list');
            } 
            
            if($this->input->post('mode') == 'Edit')
            {
                $upd = array(
                        'user_name' => $this->input->post('user_name'),
                        'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                        'first_name' => $this->input->post('first_name') ,                       
                        'email' => $this->input->post('email') ,                       
                        'mobile' => $this->input->post('mobile') ,                       
                        'state' => $this->input->post('state') ,                       
                        'city' => $this->input->post('serv_city') ,                       
                        'status' => $this->input->post('status')                       
                       // 'pincodes' => implode(',', $this->input->post('pincode'))                     
                );
                
                $this->db->where('user_id', $this->input->post('user_id'));
                $this->db->update('rh_user_info', $upd); 
                                
                redirect('marketing-user-list');
                //print_r($upd); exit();
            } 
            
        
        if($id != '') {
        
            $query = $this->db->query("select * from rh_user_info as a  where a.user_id = '". $id."' order by a.status asc ,  a.user_name asc  ");
       
            foreach ($query->result_array() as $row)
            {
                $data['edit_info'] = $row;     
            }
            
            $data['mod'] = 'Edit';
            
           /* $data['serv_city'][] = "Select Area";
            
            $query = $this->db->query("select UCASE(a.area) as area  from rh_pincode_list as a where a.state_code = '". $data['edit_info']['state']."' group by a.area  ");
            
            foreach ($query->result_array() as $row)
            {
                $data['serv_city'][$row['area']] = $row['area'];     
            } 
            
            $query = $this->db->query("select pincode from rh_pincode_list as a where a.area = '". $data['edit_info']['city']."' order by a.pincode asc ");
            
            
            $data['pincode_info'] = array();
            
            foreach ($query->result_array() as $row)
            {
                $data['pincode_info'][$row['pincode']] = $row['pincode'];     
            } */
            
        }
	    
        $data['record_list'] = array();
        
        $query = $this->db->query("
        select 
            a.user_id,
            a.first_name ,
            a.user_name , 
            a.email,
            a.mobile,
            a.level,
            a.city, 
            a.status ,
            a.pwd ,
            '' as last_login_date 
         from rh_user_info as a  
         where a.status != 'Delete' and a.level = 5
         order by a.status asc , a.user_name asc  ");
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
    
        
       /* $query = $this->db->query("select state_name , state_code  from rh_states_info as a where 1=1  order by state_name asc ");
        
        $data['state_info'][] = 'Select';

        foreach ($query->result_array() as $row)
        {
            $data['state_info'][$row['state_code']] = $row['state_name'];     
        }  
        */
        
        
        $this->load->view('marketing-user-list',$data); 
	}    
    
    public function tracking_entry($pickup_id) 
    {
        if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
        
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        
        
        if($this->input->post('mode') == 'Edit')
        {
            //$old_pay_status = $this->input->post('old_pay_status');
            /*if(($this->input->post('old_pay_status') != $this->input->post('pay_status')) and ($this->input->post('pay_status') == 'Paid') )
            {
                $paid_date = date('Y-m-d');
            } else {
                $paid_date = '';
            }*/
            
             //'paid_date' => $paid_date,
            
            
            $upd= array(
                        'courier_type' => $this->input->post('courier_type'),
                        'source_pincode' => $this->input->post('source_pincode'),
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),
                        'destination_pincode' => $this->input->post('destination_pincode'),                       
                        'destination_country' => $this->input->post('destination_country'),                       
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'package_type' => $this->input->post('package_type') ,
                        'package_weight' => $this->input->post('package_weight') ,
                        //'package_weight_int' => $this->input->post('package_weight_int') ,
                        'package_length' => $this->input->post('package_length') ,
                        'package_width' => $this->input->post('package_width') ,
                        'package_height' => $this->input->post('package_height') ,
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'remarks' => $this->input->post('remarks') ,
                        //'same_as_sender_address' => $this->input->post('same_as_sender_address') ,
                        'contact_person_name' => $this->input->post('contact_person_name') ,
                        'contact_person_mobile' => $this->input->post('contact_person_mobile') ,
                        'pickup_address' => $this->input->post('pickup_address'),
                        'approx_charges' => $this->input->post('approx_charges'),
                        'transport_mode' => $this->input->post('transport_mode'),
                        'packing_required' => $this->input->post('packing_required'),
                        'special_instruction' => $this->input->post('special_instruction'),
                        'pickup_schedule_timing' => $this->input->post('pickup_schedule_timing'),
                        'service_provider_id' => $this->input->post('service_provider_id'),
                        'bill_no' => $this->input->post('bill_no'),
                        'courier_charges' => $this->input->post('courier_charges')  ,
                        'no_of_pcs' => $this->input->post('no_of_pcs')  ,
                        'pickup_weight' => $this->input->post('pickup_weight')  ,
                        'pickup_date' => $this->input->post('pickup_date')  ,
                        'delivered_date' => $this->input->post('delivered_date')  ,
                        'ecpl_amt' => $this->input->post('ecpl_amt')  ,
                        'pmc_amt' => $this->input->post('pmc_amt')  ,
                        //'status' => ($this->input->post('status')) ,
                        'pay_status' => ($this->input->post('pay_status')) ,
                        //'tracking_status' => ($this->input->post('pay_status')) ,
                        'pay_method_id' => ($this->input->post('pay_method_id')), 
                        'paid_date' => $this->input->post('paid_date'),
                        'assign_to' => $this->input->post('assign_to'),
                        'pickup_registered_by' => $this->input->post('pickup_registered_by')
                                             
                );     
                             
          $this->db->where('pickup_id', $pickup_id);  
          $this->db->update('rh_pickup_info', $upd);  
          
          //print_r($upd);
          
          redirect('tracking-entry/'. $pickup_id);
          
        }
        
        if($this->input->post('mode') == 'Edit Payment')
        { 
            
            $upd= array( 
                         
                        'service_provider_id' => $this->input->post('service_provider_id'),
                        'bill_no' => $this->input->post('bill_no'),
                        'courier_charges' => $this->input->post('courier_charges')  ,
                        'no_of_pcs' => $this->input->post('no_of_pcs')  ,
                        'pickup_weight' => $this->input->post('pickup_weight')  ,
                        'pickup_date' => $this->input->post('pickup_date')  ,
                        'delivered_date' => $this->input->post('delivered_date')  , 
                        'pay_status' => ($this->input->post('pay_status')) , 
                        'pay_method_id' => ($this->input->post('pay_method_id')), 
                        'paid_date' => $this->input->post('paid_date'),
                        'assign_to' => $this->input->post('assign_to') 
                                             
                );     
                             
          $this->db->where('pickup_id', $pickup_id);  
          $this->db->update('rh_pickup_info', $upd);  
          
          //print_r($upd);
          
          redirect('tracking-entry/'. $pickup_id);
          
        }
        
        
        $data['js'] = 'tracking_entry.inc';
         
        if($this->input->post('btn_cancel')== 'Cancelled'){
            
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Cancelled',                    
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Cancelled' , 'status' => 'Cancelled')); 
            
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = sprintf(SMS_TS_CANCELLED,str_pad($pickup_id,5,0,STR_PAD_LEFT));
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text, '1507162986959347576');
            
            header("location: ../tracking-entry/". $pickup_id);
            
        }
        
        if($this->input->post('btn_confirm')== 'Confirmed'){
            
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Confirmed',                    
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Confirmed' , 'status' => 'Confirmed')); 
            
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = sprintf(SMS_TS_CONFIRMED,str_pad($pickup_id,5,0,STR_PAD_LEFT));
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text, '1507162986928955056');
            
            header("location: ../tracking-entry/". $pickup_id);
            
        }
        
        
        if($this->input->post('btn_pick')== 'Picked'){
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Picked',
                    'pickup_person_id' => $this->input->post('pickup_person_id') ,                     
                    'pickup_charges' => $this->input->post('pickup_charges') ,                     
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Picked' , 'status' => 'Picked')); 
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = sprintf(SMS_TS_PICKED,str_pad($pickup_id,5,0,STR_PAD_LEFT),str_pad($pickup_id,5,0,STR_PAD_LEFT));
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text, '1507162987070345959');
            
            header("location: ../tracking-entry/". $pickup_id);
        } 
        
        //$this->send_sms('6374711150', sprintf(SMS_TS_PICKED,'00129','00129'));
        
        if($this->input->post('btn_pick_upd')== 'Picked'){
            
            $upd  = array(
                    'pickup_person_id' => $this->input->post('pickup_person_id') ,                     
                    'pickup_charges' => $this->input->post('pickup_charges') ,                     
                    'updated_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' => $this->input->post('created_date'),
                    'updated_datetime' =>date('Y-m-d H:i:s')                 
            ); 
            $this->db->where('pickup_id', $pickup_id);
            $this->db->where('tracking_status', 'Picked');
            $this->db->update('crit_tracking_info', $upd); 
            
            header("location: ../tracking-entry/". $pickup_id);
        }  
        
        if($this->input->post('btn_transit')== 'In-Transit'){
            
            
            $config['upload_path'] = 'awb-upload/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('connection_bill_photo'))
            {
                $file_array = $this->upload->data();	
                $image_path	= 'awb-upload/'.$file_array['file_name']; 
           
            }
            else
            {
                 $image_path = '';    
            }  
            
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'In-Transit',
                    'service_provider_id' => $this->input->post('service_provider_id') ,                     
                    'connection_charges' => $this->input->post('connection_charges') ,                     
                    'service_provider_awb_photo' =>  $image_path ,                     
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'In-Transit' , 'bill_no' =>$this->input->post('bill_no') )); 
            
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = SMS_TS_TRANSIT;
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text,'1507162997354689781');
            
            header("location: ../tracking-entry/". $pickup_id);
        }
        
        //$this->send_sms('6374711150', sprintf(SMS_TS_TRANSIT,''));
        
        if($this->input->post('btn_transit_upd')== 'In-Transit'){
            
            
            $config['upload_path'] = 'awb-upload/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('connection_bill_photo_upd'))
            {
                $file_array = $this->upload->data();	
                $image_path	= 'awb-upload/'.$file_array['file_name']; 
                $upd1 = array('service_provider_awb_photo' =>  $image_path);
                
                // print_r($_FILES);
                
                
            } else {
                $upd1 = array();
                //print_r(array('error' => $this->upload->display_errors()));
            }
             
            $upd = array( 
                    //'service_provider_id' => $this->input->post('service_provider_id') ,                     
                    'connection_charges' => $this->input->post('connection_charges') ,  
                    'updated_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' => $this->input->post('created_date'),
                    'updated_datetime' =>date('Y-m-d H:i:s')                   
            );  
            
            
            //print_r($upd); 
            //print_r($_FILES); 
            //exit();
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->where('tracking_status', 'In-Transit');
            $this->db->update('crit_tracking_info', $upd + $upd1); 
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('bill_no' =>$this->input->post('bill_no') )); 
            
            
            header("location: ../tracking-entry/". $pickup_id); 
        }
        
        if($this->input->post('btn_received_hud')== 'Received-HUB'){
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Received-HUB',                
                    'received_person_id' => $this->input->post('received_person_id') ,                    
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Received-HUB')); 
            
            header("location: ../tracking-entry/". $pickup_id);
        } 
        
        if($this->input->post('btn_received_hud_upd')== 'Received-HUB'){
            $upd = array( 
                    'updated_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' => $this->input->post('created_date'),
                    'updated_datetime' =>date('Y-m-d H:i:s')                   
            );  
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->where('tracking_status', 'Received-HUB');
            $this->db->update('crit_tracking_info', $upd ); 
            
            header("location: ../tracking-entry/". $pickup_id);
        } 
        
        if($this->input->post('btn_delivery')== 'Out For Delivery'){
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Out For Delivery',                
                    'delivery_person_id' => $this->input->post('delivery_person_id') ,                    
                    'delivery_charges' => $this->input->post('delivery_charges') ,                    
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Out For Delivery'));
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = sprintf(SMS_TS_OUT_FOR_DELI,str_pad($pickup_id,5,0,STR_PAD_LEFT) ,date('d-m-Y'));
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text,'1507162997370532757'); 
            
            header("location: ../tracking-entry/". $pickup_id);
        } 
        
       // $this->send_sms('6374711150', sprintf(SMS_TS_OUT_FOR_DELI,str_pad($pickup_id,5,0,STR_PAD_LEFT) ,date('d-m-Y')));
        
        if($this->input->post('btn_delivery_upd')== 'Out For Delivery'){
            $upd = array( 
                         
                    'delivery_person_id' => $this->input->post('delivery_person_id') ,                    
                    'delivery_charges' => $this->input->post('delivery_charges') ,                    
                    'updated_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' => $this->input->post('created_date'),
                    'updated_datetime' =>date('Y-m-d H:i:s')                     
            );  
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->where('tracking_status', 'Out For Delivery');
            $this->db->update('crit_tracking_info',$upd); 
            
            header("location: ../tracking-entry/". $pickup_id);
        } 
        
        
        if($this->input->post('btn_delivered')== 'Delivered'){
            
            
            $config['upload_path'] = 'awb-upload/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('dlv_id_proof_photo'))
            {
                $file_array = $this->upload->data();	
                $image_path	= 'awb-upload/'.$file_array['file_name']; 
           
            }
            else
            {
                 $image_path = '';    
            }  
            
            $ins = array(
                    'pickup_id' => $pickup_id,
                    'tracking_status' => 'Delivered',
                    'delivered_to' => $this->input->post('delivered_to') ,                     
                    'dlv_id_proof_photo' =>  $image_path ,                     
                    'created_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->update('rh_pickup_info', array('tracking_status' => 'Delivered', 'delivered_date' => date('Y-m-d'), 'status' => 'Delivered'));
            
            $sms_mobile = $this->get_pickup_registered_mobile($pickup_id);
            
            $sms_text = sprintf(SMS_TS_DELIVERED,str_pad($pickup_id,5,0,STR_PAD_LEFT), date('d-m-Y H:i') );
            
            if(strlen($sms_mobile) == '10')
                $this->send_sms($sms_mobile, $sms_text,'1507162997824686122');
            
            header("location: ../tracking-entry/". $pickup_id);
        }
        
       // $this->send_sms('6374711150', sprintf(SMS_TS_DELIVERED,str_pad($pickup_id,5,0,STR_PAD_LEFT), date('d-m-Y H:i')));
        
        if($this->input->post('btn_delivered_upd')== 'Delivered'){
            
            
            $config['upload_path'] = 'awb-upload/';
    		$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('dlv_id_proof_photo'))
            {
                $file_array = $this->upload->data();	
                $image_path	= 'awb-upload/'.$file_array['file_name']; 
                $upd1 = array('dlv_id_proof_photo' =>  $image_path );
            }
            else
            {
                 $image_path = '';    $upd1 = array();
            }  
            
            $upd = array( 
                    'delivered_to' => $this->input->post('delivered_to') ,      
                    'updated_by' => $this->session->userdata('m_user_id') ,                    
                    'created_date' => $this->input->post('created_date'),
                    'updated_datetime' =>date('Y-m-d H:i:s')                   
            );  
            
            $this->db->where('pickup_id', $pickup_id);
            $this->db->where('tracking_status', 'Delivered');
            $this->db->update('crit_tracking_info', $upd + $upd1);
            
            header("location: ../tracking-entry/". $pickup_id);
        }
        
        /* 
        
        $query = $this->db->query("
            select
                a.pickup_id as pickup_ref_no,
                DATE_FORMAT(a.booked_date,'%d-%m-%Y %h:%i %p') as booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.contact_person_name , '<br>' , a.contact_person_mobile , '<br>', a.pickup_address ),concat(a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address )) as pickup_address,
                concat(a.source_pincode , '<br>' , a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address ) as source_address,
                concat(if(a.courier_type = 'Domestic', a.destination_pincode, b.country_name ) , '<br>' , a.receiver_name , '<br>' , a.receiver_phone , '<br>', a.receiver_address ) as destination_address,
                concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), c.package_weight_name ) ) as package_details,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
                a.transport_mode,
                (if(a.packing_required = '1', 'Yes','No')) as packing_required, 
                a.special_instruction as package_content,
                a.tracking_status ,
                a.courier_charges 
                from rh_pickup_info as a 
                left join rh_country_info as b on b.country_id = a.destination_country
                left join crit_package_weight as c on c.package_weight_id = a.package_weight_int
             where a.pickup_id = '". $pickup_id. "'
             and a.status != 'Delete'
             order by a.booked_date desc 
          "
          );  
    
        foreach($query->result_array() as $row)
        {
            $data['consignment_info'] = $row;
             
        } */
            
         $data['booked'] = $this->generate_waybill($pickup_id , 0);   
         
         
         $data['pickup_user'] = false;
         
         if($this->session->userdata('m_is_admin') == USER_PICKUP) { 
            
            $pstate = $this->session->userdata('m_pstate');
            $pcity = $this->session->userdata('m_pcity');  
            
            $data['pickup_user'] = true; $data['pickup_flag'] = false; $data['delivery_flag'] = false; 
            if($data['consignment_info']['tracking_status'] == 'Booked' || $data['consignment_info']['tracking_status'] == 'Picked')
                $data['pickup_flag'] = true; 
            if($data['consignment_info']['tracking_status'] == 'In-Transit' || $data['consignment_info']['tracking_status'] == 'Received-HUB' || $data['consignment_info']['tracking_status'] == 'Out For Delivery')
                $data['delivery_flag'] = true; 
                
         } elseif( $this->session->userdata('m_is_admin') == USER_ADMIN || $this->session->userdata('m_is_admin') == USER_MANAGER)  {
            $data['pickup_flag'] = true; 
            $data['delivery_flag'] = true; 
            
            
            $query = $this->db->query("
                select
                 a.*,
                 concat(a.source_pincode , '<br>' , a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address ) as source_address,
                concat(if(a.courier_type = 'Domestic', a.destination_pincode, b.country_name ) , '<br>' , a.receiver_name , '<br>' , a.receiver_phone , '<br>', a.receiver_address ) as destination_address,
                concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), c.package_weight_name ) ) as package_details,
                (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension                   
                 from rh_pickup_info as a  
                 left join rh_country_info as b on b.country_id = a.destination_country
                left join crit_package_weight as c on c.package_weight_id = a.package_weight_int
                 where a.pickup_id = '". $pickup_id. "'
                 and a.status != 'Delete'
                 order by a.booked_date desc 
              "
              );  
        
            foreach($query->result_array() as $row)
            {
                $data['consignment_edit'] = $row;
                 
            } 
            
            $src_state_city  = $this->get_pincode_state_district($data['consignment_edit']['source_pincode']); 
            
            $data['src_agent_opt'][] = 'Select Agent';
            
            if(!empty($src_state_city)) {
                
                //and a.city = '". $src_state_city['district']."'
            
                $query = $this->db->query("select agent_id, agent_type, contact_person ,state , city from crit_agent_info as a where a.status= 'Active' and a.state = '". $src_state_city['state']."'  order by agent_type,contact_person asc ");
             
                foreach ($query->result_array() as $row)
                {
                    $data['src_agent_opt'][$row['agent_id']] = $row['agent_type'] . ' - ' .$row['contact_person'] . ' [ ' . $row['state'] . '-' . strtolower($row['city']) . ' ]';     
                } 
            } 
            
            $dest_state_city  = $this->get_pincode_state_district($data['consignment_edit']['destination_pincode']); 
            
            $data['dest_agent_opt'][] = 'Select Agent';
            
            if(!empty($dest_state_city)) {
                
                //and a.city = '". $dest_state_city['district']."'
                
                $query = $this->db->query("select agent_id, agent_type, contact_person ,state , city from crit_agent_info as a where a.status= 'Active' and a.state = '". $dest_state_city['state']."'  order by agent_type, contact_person asc ");
             
                foreach ($query->result_array() as $row)
                {
                    $data['dest_agent_opt'][$row['agent_id']] = $row['agent_type'] . ' - ' .$row['contact_person'] . ' [ ' . $row['state'] . '-' .  strtolower($row['city']) . ' ]';     
                }
            }
         
        
            $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
            
            $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
            
            foreach ($query->result_array() as $row)
            {
             $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
            }   
            
            $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
            
            $data['state_opt'][] = 'All';
    
            foreach ($query->result_array() as $row)
            {
                $data['state_opt'][$row['state_name']] = $row['state_name'];     
            } 
            
            $query = $this->db->query("select user_id, first_name from rh_user_info as a where a.status= 'Active' and level != '4' and a.user_id != '1'  order by first_name asc ");
            
            $data['staff_opt'][] = 'Select Staff';
    
            foreach ($query->result_array() as $row)
            {
                $data['staff_opt'][$row['user_id']] = $row['first_name'];     
            } 
            
            
            $query = $this->db->query("select country_name , country_id  from rh_country_info as a where a.status= 'Active' order by country_name asc ");
            
            //$data['state_info'][] = 'Select the State';
    
            foreach ($query->result_array() as $row)
            {
                $data['destination_country_opt'][$row['country_id']] = $row['country_name'];     
            }  
            
            
            
         } else {
            $data['pickup_flag'] = false; 
            $data['delivery_flag'] = false; 
         }
         
       
            
         $sql = "
                select  
                a.pickup_id as ref_no,
                a.tracking_status,
                b.first_name as pickup_person ,
                a.pickup_charges,
                a.created_date as picked_date 
                from crit_tracking_info as a  
                left join rh_user_info as b on b.user_id = a.pickup_person_id
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Picked'
                order by a.created_date asc 
         "; 
         
         $sql = "
                select  
                a.pickup_person_id,
                a.pickup_id as ref_no,
                a.tracking_status,
                concat(b.agent_type, ' - ',b.contact_person) as pickup_person ,
                a.pickup_charges,
                a.created_date as picked_date 
                from crit_tracking_info as a  
                left join crit_agent_info as b on b.agent_id = a.pickup_person_id
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Picked'
                order by a.created_date asc 
         ";
         $query = $this->db->query($sql); 
         
         
        foreach($query->result_array() as $row)
        {
            $data['pickup_person_info']  = $row;             
        } 
       
       $sql = "
                select  
                a.service_provider_id,
                a.pickup_id as ref_no,
                a.tracking_status,
                b.service_provider_name as service_provider , 
                a.connection_charges,
                a.service_provider_awb_photo,
                a.created_date 
                from crit_tracking_info as a  
                left join rh_service_provider_info as b on b.service_provider_id = a.service_provider_id
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'In-Transit'
                order by a.created_date asc 
         ";
         $query = $this->db->query($sql); 
         
         
        foreach($query->result_array() as $row)
        {
            $data['transit_info']  = $row;
             
        }   
         
       $query = $this->db->query("select a.service_provider_id,  a.service_provider_name  from rh_service_provider_info as a where a.status='Active'   order by  a.service_provider_name asc ");
        
        $data['service_provider_opt'] = array('' => 'Select Service Provider'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['service_provider_opt'][$row['service_provider_id']] = $row['service_provider_name']   ;    
        }   
        /*$sql = " 
                select 
                a.tracking_status,
                b.first_name as received_person , 
                a.created_date 
                from crit_tracking_info as a  
                left join rh_user_info as b on b.user_id = a.received_person_id 
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Received-HUB'
                order by a.created_date asc 
         ";*/
         
         $sql = " 
                select 
                a.tracking_status,
                concat(b.agent_type, ' - ',b.contact_person) as received_person , 
                a.created_date 
                from crit_tracking_info as a  
                left join crit_agent_info as b on b.agent_id = a.delivery_person_id
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Received-HUB'
                order by a.created_date asc 
         ";
         $query = $this->db->query($sql); 
         
         
        foreach($query->result_array() as $row)
        {
            $data['received_HUB_info']  = $row;             
        }   
       /* $sql = " 
                select 
                a.tracking_status,
                b.first_name as delivery_person , 
                a.delivery_charges,
                a.created_date 
                from crit_tracking_info as a  
                left join rh_user_info as b on b.user_id = a.delivery_person_id   
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Out For Delivery'
                order by a.created_date asc 
         ";*/
         
        $sql = " 
                select 
                a.delivery_person_id,
                a.tracking_status,
                concat(b.agent_type, ' - ',b.contact_person) as delivery_person , 
                a.delivery_charges,
                a.created_date 
                from crit_tracking_info as a   
                left join crit_agent_info as b on b.agent_id = a.delivery_person_id 
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Out For Delivery'
                order by a.created_date asc 
         ";
         $query = $this->db->query($sql); 
         
        $data['out_delivery_info']  = array(); 
         
        foreach($query->result_array() as $row)
        {
            $data['out_delivery_info']  = $row;             
        } 
        
        $sql = " 
                select 
                a.tracking_status, 
                a.delivered_to,
                a.dlv_id_proof_photo,
                a.created_date 
                from crit_tracking_info as a   
                where a.pickup_id = '". $pickup_id. "' and a.tracking_status = 'Delivered'
                order by a.created_date asc 
         ";
         $query = $this->db->query($sql); 
         
         
        foreach($query->result_array() as $row)
        {
            $data['delivered_info']  = $row;
        }  
         
        
        $this->load->view('tracking-entry',$data); 
    }
    
    public function manager_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'user.inc';
        
        if($this->input->post('mode') == 'Add')
        { 
             $ins = array(
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                    'state' => $this->input->post('state') ,                       
                    'city' => $this->input->post('serv_city') ,   
                    'level' => 2 ,                    
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->insert('rh_user_info', $ins); 
            redirect('manager-list'); 
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('user_id') != '')
        { 
             $ins = array(
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                    'state' => $this->input->post('state') ,                       
                    'city' => $this->input->post('serv_city') ,      
                    'status' => $this->input->post('status')                 
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->where('user_id', $this->input->post('user_id')); 
            $this->db->update('rh_user_info', $ins); 
            redirect('manager-list'); 
           //print_r($ins); exit;
        }
          
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->from('crit_packing_charge_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('manager-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
               select 
                a.user_id,
                a.first_name ,
                a.user_name ,
                a.pwd, 
                a.email,
                a.mobile,
                a.level,
                a.city, 
                a.status , 
                a.state,
                '' as last_login_date 
             from rh_user_info as a  
             where a.status != 'Delete' and a.level = 2
             order by a.status asc , a.user_name asc 
             limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('manager-list',$data); 
	}  
    
    public function agent_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'agent.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'agent_type' => $this->input->post('agent_type'),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    //'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'ac_no' => $this->input->post('ac_no'),
                    'ac_holder_name' => $this->input->post('ac_holder_name'),
                    'ac_bank' => $this->input->post('ac_bank'),
                    'ac_branch' => $this->input->post('ac_branch'),
                    'ifsc_code' => $this->input->post('ifsc_code'),
                    'status' => $this->input->post('status'),
                    'pay_type' => $this->input->post('pay_type'),
                    'pay_mobile' => $this->input->post('pay_mobile'),
                    'created_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_agent_info', $ins); 
            redirect('agent-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array( 
                    'agent_type' => $this->input->post('agent_type'),
                    'contact_person' => $this->input->post('contact_person'),
                    'mobile' => $this->input->post('mobile'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'), 
                    'address' => $this->input->post('address'),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    //'servicable_pincode' => implode(',',$this->input->post('servicable_pincode')),
                    'ac_no' => $this->input->post('ac_no'),
                    'ac_holder_name' => $this->input->post('ac_holder_name'),
                    'ac_bank' => $this->input->post('ac_bank'),
                    'ac_branch' => $this->input->post('ac_branch'),
                    'ifsc_code' => $this->input->post('ifsc_code'),
                    'pay_type' => $this->input->post('pay_type'),
                    'pay_mobile' => $this->input->post('pay_mobile'),
                    'status' => $this->input->post('status'),
                    'updated_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('agent_id', $this->input->post('agent_id'));
            $this->db->update('crit_agent_info', $upd); 
                            
            redirect('agent-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
       /* 
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
        */
        
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
       
       
       $where = '1';

       
        
      /* if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       } 
       */ 
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.state like '%" . $srch_key . "%' or 
                        a.city like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } 
        
        
        $this->db->where('status != ', 'Delete');
        if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_agent_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('agent-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.agent_id, 
                a.agent_type,
                a.contact_person, 
                a.mobile, 
                a.phone, 
                a.email,  
                a.address, 
                a.state, 
                a.city, 
                a.ac_no, 
                a.ac_holder_name, 
                a.ac_bank, 
                a.ac_branch, 
                a.ifsc_code, 
                a.`status`
                from crit_agent_info as a 
                where a.status != 'Delete' 
                and ". $where ."
                order by a.status asc , a.contact_person asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
       // $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
            
        $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
        }   
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('agent-list',$data); 
	} 
    
    public function agent_payment_request()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        /*if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } */
        	    
        $data['js'] = 'agent-pay.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'agent_id' => $this->input->post('agent_id'),
                    'pickup_id' => $this->input->post('pickup_id'),
                    'request_date' => $this->input->post('request_date'),
                    'req_amount' => $this->input->post('req_amount'),
                    'req_remarks' => $this->input->post('req_remarks'), 
                    'req_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_agent_payment_info', $ins); 
            redirect('agent-pay-request');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array( 
                    'agent_id' => $this->input->post('agent_id'),
                    'pickup_id' => $this->input->post('pickup_id'),
                    'request_date' => $this->input->post('request_date'),
                    'req_amount' => $this->input->post('req_amount'),
                    'req_remarks' => $this->input->post('req_remarks'), 
                    //'updated_by' => $this->session->userdata('cr_user_id'),                          
                    //'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('agent_payment_id', $this->input->post('agent_payment_id'));
            $this->db->update('crit_agent_payment_info', $upd); 
                            
            redirect('agent-pay-request/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
       /* 
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
        */
       
       $where = '1';

       
        
      /* if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       }  
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.servicable_pincode like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } */
        
        
        $this->db->where('status != ', 'Delete');
        $this->db->where('status = ', 'Pending');
        if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_agent_payment_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('agent-pay-request/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = " 
                select 
                a.agent_payment_id,
                a.pickup_id,
                a.request_date,
                a.req_amount,
                a.req_remarks, 
                c.agent_type, 
                c.contact_person, 
                c.mobile,  
                c.state, 
                c.city, 
                c.pay_type, 
                c.pay_mobile, 
                c.ac_no, 
                c.ac_holder_name, 
                c.ac_bank, 
                c.ac_branch, 
                c.ifsc_code,
                a.`status`
                from crit_agent_payment_info as a 
                left join crit_agent_info as c on c.agent_id = a.agent_id
                where a.`status` != 'Delete' and a.`status` = 'Pending'  
                order by a.request_date asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
           
        $query = $this->db->query("select a.agent_id,  a.agent_type , a.contact_person , a.state from crit_agent_info as a where a.status = 'Active'  order by a.state , a.contact_person asc ");
        
       // $data['agent_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['agent_opt'][$row['state']][$row['agent_id']] = $row['contact_person'] . ' - ' . $row['agent_type'];     
        } 
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('agent-pay-request',$data); 
	} 
    
    public function agent_payment_approval()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'agent-pay.inc';  
        
        /*if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'agent_id' => $this->input->post('agent_id'),
                    'request_date' => $this->input->post('request_date'),
                    'req_amount' => $this->input->post('req_amount'),
                    'req_remarks' => $this->input->post('req_remarks'), 
                    'req_by' => $this->session->userdata('cr_user_id'),                          
                    'created_datetime' => date('Y-m-d H:i:s')                           
            );
            
            $this->db->insert('crit_agent_payment_info', $ins); 
            redirect('agent-pay-request');
        }*/
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array( 
                    'status' => 'Paid',
                    'pay_mode' => $this->input->post('pay_mode'),
                    'payment_date' => $this->input->post('payment_date'),
                    'paid_amount' => $this->input->post('paid_amount'),
                    'paid_remarks' => $this->input->post('paid_remarks'), 
                    'paid_by' => $this->session->userdata('cr_user_id'),                          
                    'updated_datetime' => date('Y-m-d H:i:s')                 
            );
            
            $this->db->where('agent_payment_id', $this->input->post('agent_payment_id'));
            $this->db->update('crit_agent_payment_info', $upd); 
                            
            redirect('agent-pay-approval/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
       /* 
       if(isset($_POST['srch_state'])) {
           $data['srch_state'] = $srch_state = $this->input->post('srch_state');
           $data['srch_key'] = $srch_key = $this->input->post('srch_key');
           $this->session->set_userdata('srch_state', $this->input->post('srch_state'));
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_state')){
           $data['srch_state'] = $srch_state = $this->session->userdata('srch_state') ; 
       }else {
           $data['srch_state'] = $srch_state = '';
       }
       
       if(isset($_POST['srch_key'])) { 
           $data['srch_key'] = $srch_key = $this->input->post('srch_key'); 
           $this->session->set_userdata('srch_key', $this->input->post('srch_key'));
       }
       elseif($this->session->userdata('srch_key')){ 
           $data['srch_key'] = $srch_key = $this->session->userdata('srch_key') ;
       } else {
         $data['srch_key'] = $srch_key = '';
       }
       
        */
       
       $where = '1';

       
        
      /* if(!empty($srch_state)){
         $where .= " and a.state_code = '". $srch_state ."'";
       }  
       if(!empty($srch_key)) {
         $where .= " and ( 
                        a.servicable_pincode like '%" . $srch_key . "%' or 
                        a.mobile like '%". $srch_key ."%' or 
                        a.contact_person like '%". $srch_key ."%' or 
                        a.email like '%". $srch_key ."%' or 
                        a.phone like '%". $srch_key ."%'
                        ) ";
         
       } */
        
        
        $this->db->where('status != ', 'Delete');
        $this->db->where('status = "Pending" or status = "Paid"');
        if(!empty($srch_key))
            $this->db->where($where);
        $this->db->from('crit_agent_payment_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('agent-pay-approval/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = " 
                select 
                a.agent_payment_id,
                a.pickup_id,
                a.request_date,
                a.req_amount,
                a.req_remarks, 
                a.payment_date,
                a.paid_amount,
                a.paid_remarks,
                a.paid_acknowledge,
                c.agent_type, 
                c.contact_person, 
                c.mobile,  
                c.state, 
                c.city, 
                d.pay_method_name as pay_type, 
                c.pay_mobile, 
                c.ac_no, 
                c.ac_holder_name, 
                c.ac_bank, 
                c.ac_branch, 
                c.ifsc_code,
                a.`status`
                from crit_agent_payment_info as a 
                left join crit_agent_info as c on c.agent_id = a.agent_id
                left join crit_pay_method_info as d on d.pay_method_id = c.pay_type
                where a.`status` != 'Delete' and (a.status = 'Pending' or a.status = 'Paid')  
                order by a.status desc , a.request_date desc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $query = $this->db->query("select a.pay_method_id,  a.pay_method_name  from crit_pay_method_info as a where a.status='Active' order by  a.pay_method_name asc ");
            
        $data['pay_method_opt'] = array('' => 'Select Payment Method'); 
        
        foreach ($query->result_array() as $row)
        {
         $data['pay_method_opt'][$row['pay_method_id']] = $row['pay_method_name']   ;    
        }   
        
              
        $query = $this->db->query("select agent_id, agent_type, contact_person ,state , city from crit_agent_info as a where a.status= 'Active'   order by contact_person asc ");
             
        foreach ($query->result_array() as $row)
        {
            $data['agent_opt'][$row['agent_id']] = $row['agent_type'] . ' - ' .$row['contact_person'] . ' [ ' . $row['state'] . '-' . $row['city'] . ' ]';     
        } 
        
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('agent-pay-approval',$data); 
	} 
    
    
    
    public function pickup_user_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'user.inc';
        
        if($this->input->post('mode') == 'Add')
        { 
             $ins = array(
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                    'state' => $this->input->post('state') ,                       
                    'city' => $this->input->post('city') ,                       
                    'level' => 3 ,                       
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->insert('rh_user_info', $ins); 
            redirect('pickup-user-list'); 
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('user_id') != '')
        { 
             $ins = array(
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                    'state' => $this->input->post('state') ,                       
                    'city' => $this->input->post('city') ,   
                    'status' => $this->input->post('status')                    
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->where('user_id', $this->input->post('user_id')); 
            $this->db->update('rh_user_info', $ins); 
            redirect('pickup-user-list'); 
           //print_r($ins); exit;
        }
          
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->where('level = 3');
        $this->db->from('rh_user_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pickup-user-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
               select 
                a.user_id, 
                a.first_name ,
                a.user_name , 
                a.pwd,
                a.email,
                a.mobile,
                a.level,
                a.city, 
                a.status , 
                a.state,
                a.city,
                '' as last_login_date 
             from rh_user_info as a   
             where a.status != 'Delete' and a.level = 3
             order by a.status asc , a.user_name asc 
             limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pickup-user-list',$data); 
	} 
    
    public function pp_customer_user_list() 
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'user.inc';
        
        if($this->input->post('mode') == 'Add')
        { 
             $ins = array(
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                   // 'state' => $this->input->post('state') ,                       
                  //  'city' => $this->input->post('serv_city') ,   
                    'level' => USER_PICKPACK_CUST ,          // 4          
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->insert('rh_user_info', $ins); 
            redirect('pp-user-list'); 
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('user_id') != '')
        { 
             $ins = array(
                    'pp_customer_id' => $this->input->post('pp_customer_id'),
                    'user_name' => $this->input->post('user_name'),
                    'pwd' => md5(md5($this->db->escape($this->input->post('pwd')))),
                    'first_name' => $this->input->post('first_name') ,                       
                    'email' => $this->input->post('email') ,                       
                    'mobile' => $this->input->post('mobile') ,                       
                   // 'state' => $this->input->post('state') ,                       
                   // 'city' => $this->input->post('serv_city') ,      
                    'status' => $this->input->post('status')                 
                   // 'pincodes' => implode(',', $this->input->post('pincode'))                     
            );
            
            $this->db->where('user_id', $this->input->post('user_id')); 
            $this->db->update('rh_user_info', $ins); 
            redirect('pp-user-list'); 
           //print_r($ins); exit;
        }
          
        
        $this->load->library('pagination');
        
        $this->db->where('status = ', 'Active');
        $this->db->where('level = 4');
        $this->db->from('rh_user_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('pp-user-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
               select 
                a.user_id,
                c.company_name as customer,
                a.first_name ,
                a.user_name ,
                a.pwd, 
                a.email,
                a.mobile,
                a.level,
                a.city, 
                a.status , 
                a.state,
                '' as last_login_date 
             from rh_user_info as a  
             left join crit_pp_customer_info as c on c.pp_customer_id = a.pp_customer_id
             where a.status != 'Delete' and a.level = 4
             order by a.status asc , a.user_name asc 
             limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
        $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $query = $this->db->query("select state_name  from crit_pincode_info as a where 1=1 group by state_name order by state_name asc ");
        
        $data['state_opt'][] = 'All';

        foreach ($query->result_array() as $row)
        {
            $data['state_opt'][$row['state_name']] = $row['state_name'];     
        } 
        
        $query = $this->db->query("select pp_customer_id ,  company_name  from crit_pp_customer_info as a where a.status= 'Active'  order by company_name asc ");
        
        $data['pp_customer_opt'][] = 'Select Pick&Pack Customer';

        foreach ($query->result_array() as $row)
        {
            $data['pp_customer_opt'][$row['pp_customer_id']] = $row['company_name'];     
        } 
        
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pp-user-list',$data); 
	}  
      
    public function sms_balance()
    {
         
        error_reporting(0); 
        $username="PMC_tamil";

        //$password="sts12345@"; 
        $password="7798993"; 
        
       // $sender="PKMYCR"; //ex:INVITE 

        $this->load->library('curl'); 
        
        $ack = $this->curl->simple_get("http://login.bulksmsgateway.in/userbalance.php?user=".$username."&password=".$password."&type=3"); 
            
        $val = json_decode($ack,true);
        
        //return $ack;
        //return $val['remainingcredits']; 
        
        header('Content-Type: application/x-json; charset=utf-8');

       echo (json_encode($val['remainingcredits']));  
        
    }
     
 
    
    public function visitor_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();       
        
       
       $data['js'] = 'visitor.inc';
       
       //echo $this->uri->segment(2, 0);
       $this->load->library('pagination');
       
       $data['total_records'] = $cnt  = $this->db->count_all_results('rh_visitor');	
        	
        $config['base_url'] = trim(site_url('visitor-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
       
        // limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
       
        $query = $this->db->query(" 
                        select 
                        a.visitor_id, 
                        a.ip, 
                        a.page, 
                        a.date_time, 
                        a.country, 
                        a.region, 
                        a.city,  
                        a.user_agent, 
                        a.remarks 
                        from rh_visitor as a
                        order by a.date_time desc 
                       limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
                    ");
         
         
        $data['record_list'] = array(); 

        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $data['pagination'] = $this->pagination->create_links();
       
		$this->load->view('visitor-list',$data);
	}
      
    public function restore_record()  
    {
        
        if(!$this->session->userdata('m_logged_in'))  redirect();  
        
        
        $table = $this->input->post('tbl') ;
        $rec_id =$this->input->post('id'); 
        
         if($table == 'pickup')
         {            
           
            $this->db->where('pickup_id', $rec_id);
            $this->db->update('rh_pickup_info', array('status' => 'Booked'));   
            echo 'Record Successfully Restored'; 
         } 
    } 
     
    public function quick_quote() 
    {
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        $ins = array(
                    'q_org_pincode' => $this->input->post('q_org_pincode'),
                    'q_dest_pincode' => $this->input->post('q_dest_pincode'),
                    'q_pkg_weight' => $this->input->post('q_pkg_weight'),
                    'q_mobile' => $this->input->post('q_mobile'),
                    'status' => 'Pending',                    
                    'created_datetime' =>date('Y-m-d H:i:s')                    
        ); 
        $this->db->insert('crit_quick_quote_info', $ins);
        
        $quick_quote_id = $this->db->insert_id();
        
        echo "Dear Customer,<br>Your Enquiry number - ". $quick_quote_id .".<br> Thanks for registering ,Our Customer support team will contact you shortly.";
    }  
    
    public function insert_record() 
    {
        $table = $this->input->post('tbl') ;
        
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        
        
        if($table == 'tracking_pickup')
         {            
           
            //$this->db->where('pickup_id', $rec_id);
            //$this->db->update('rh_pickup_info', array('status' => 'Delete'));   
            
             $ins = array(
                    'pickup_id' => $this->input->post('pickup_id'),
                    'tracking_status' => 'Picked',
                    'pickup_person_id' => $this->input->post('pickup_person_id') ,                     
                    'pickup_charges' => $this->input->post('pickup_charges') ,                     
                    'created_by' => $this->input->post('created_by') ,                    
                    'created_date' =>date('Y-m-d H:i:s')                    
            ); 
            $this->db->insert('crit_tracking_info', $ins);
            
            
            
            echo 'Record Successfully updated'; 
         } 
         
         if($table == 'pickup_remarks')
         {            
            $this->db->where('pickup_id', $this->input->post('pickup_id'));
            $this->db->update('rh_pickup_info', array('remarks' => $this->input->post('remarks')));   
            echo 'Record Successfully updated'; 
         }  
        
    } 
    public function delete_record()  
    {
        
        if(!$this->session->userdata('m_logged_in'))  redirect();  
        
        
        $table = $this->input->post('tbl') ;
        $rec_id =$this->input->post('id');
        

        
         if($table == 'pickup')
         {            
            /*$this->db->where('pickup_id', $rec_id);
            $this->db->delete('rh_pickup_info');   */
            $this->db->where('pickup_id', $rec_id);
            $this->db->update('rh_pickup_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'country')
         {            
            $this->db->where('country_id', $rec_id);
            $this->db->update('rh_country_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'state')
         {            
            $this->db->where('id', $rec_id);
            $this->db->update('rh_states_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'pincode')
         {            
            $this->db->where('pincode_id', $rec_id);
            $this->db->update('rh_pincode_list', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'international')
         {            
            $this->db->where('international_rate_id', $rec_id);
            $this->db->update('crit_international_rate', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'package_type')
         {            
            $this->db->where('package_type_id', $rec_id);
            $this->db->update('crit_package_type', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'package_weight')
         {            
            $this->db->where('package_weight_id', $rec_id);
            $this->db->update('crit_package_weight', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         if($table == 'non_dox_sub_category_info')
         {            
            $this->db->where('non_dox_sub_category_id', $rec_id);
            $this->db->update('crit_non_dox_sub_category_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'franchise_enquiry')
         {            
            $this->db->where('franchise_enquiry_id', $rec_id);
            $this->db->delete('rh_franchise_enquiry_info');   
            echo 'Record Successfully deleted'; 
         } 
         if($table == 'pay_method')
         {            
            $this->db->where('pay_method_id', $rec_id);
            $this->db->update('crit_pay_method_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         if($table == 'service_provider')
         {            
            $this->db->where('service_provider_id', $rec_id);
            $this->db->update('rh_service_provider_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         if($table == 'pick-pack')
         {            
            $this->db->where('pick_pack_id', $rec_id);
            $this->db->update('crit_pick_pack_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'agent-pay')
         {            
            $this->db->where('agent_payment_id', $rec_id);
            $this->db->update('crit_agent_payment_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         }  
         
         if($table == 'quick_quote_info')
         {            
            $this->db->where('quick_quote_id', $rec_id);
            $this->db->update('crit_quick_quote_info', array('status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         } 
         
         if($table == 'booking-tracking')
         {            
            $this->db->where('pmc_tracking_id', $rec_id);
            $this->db->update('crit_pmc_tracking_info', array('tracking_status' => 'Delete'));   
            echo 'Record Successfully deleted'; 
         }  
          
          
          
    }
    
    public function get_data()  
	{
	   //if(!$this->session->userdata('zazu_logged_in'))  redirect();
       
       $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
       
       $table = $this->input->post('tbl') ;
       $rec_id =$this->input->post('id');
       
       if($table == 'visitor-perdays')
       {
          $query = $this->db->query(" 
                select 
                count(v.v_date) as cnt , 
                v.v_date ,
                v.v_date_num 
                from  
                (
                   select 
                   DATE_FORMAT(a.date_time,'%b %d') as v_date,
                   DATE_FORMAT(a.date_time,'%Y%m%d') as v_date_num,
                   a.ip as v_ip
                   from rh_visitor as a 
                   group by  DATE_FORMAT(a.date_time,'%d-%m-%Y') ,a.ip  
                   order by  DATE_FORMAT(a.date_time,'%d-%m-%Y') desc ,a.ip  
                ) as v 
                group by v.v_date_num  
                order by v.v_date_num  desc 
                limit 10
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['date'][]  = $row['v_date'];  
                //$rec_list[]  = $row;      
            }  
          
       }
       if($table == 'visitor-permonths')
       {
          $query = $this->db->query(" 
                select 
                count(v.v_month) as cnt , 
                v.v_month  
                from  
                (
                   select 
                   DATE_FORMAT(a.date_time,'%b-%Y') as v_month,
                   DATE_FORMAT(a.date_time,'%Y%m') as v_month_num,
                   a.ip as v_ip
                   from rh_visitor as a 
                   group by  DATE_FORMAT(a.date_time,'%m-%Y') ,a.ip  
                ) as v 
                group by v.v_month_num  
                order by v.v_month_num  desc 
                limit 5
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['month'][]  = $row['v_month'];  
                //$rec_list[]  = $row;      
            }  
          
       }
       
       if($table == 'pickup-perdays')
       {
            /* 
                select 
                DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                count(a.pickup_id) as cnt
                from rh_pickup_info as a
                where a.status != 'Delete'
                group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                limit 10
            */
          $query = $this->db->query(" 
                 select 
                    w.v_date_num,
                    w.v_date,
                    sum(cnt)  as cnt
                    from (
                        (
                            select 
                            DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                            DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                            count(a.pickup_id) as cnt
                            from rh_pickup_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                            order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                            limit 10
                        ) union all (
                            select 
                            DATE_FORMAT(a.book_date,'%b %d')  as v_date,
                            DATE_FORMAT(a.book_date,'%Y%m%d') as v_date_num,
                            count(a.pick_pack_id) as cnt
                            from crit_pick_pack_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.book_date,'%d-%m-%Y') 
                            order by DATE_FORMAT(a.book_date,'%Y%m%d') desc 
                            limit 10
                        )
                    ) as w 
                    group by w.v_date_num
                    order by w.v_date_num desc
                    limit 10
            ");
            
            
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['date'][]  = $row['v_date'];  
                //$rec_list[]  = $row;      
            }  
          
       }
       
       if($table == 'pickup-permonths')
       {
            /*
            $query = $this->db->query(" 
                
                select 
                DATE_FORMAT(a.booked_date,'%b %Y')  as v_month,
                DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                count(a.pickup_id) as cnt
                from rh_pickup_info as a
                where a.status != 'Delete'
                group by DATE_FORMAT(a.booked_date,'%b %y') 
                order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                limit 5
                 
            ");
            */
          $query = $this->db->query(" 
                
                select 
                    w.v_month_num,
                    w.v_month,
                    sum(cnt)  as cnt
                    from (
                        (
                            select 
                            DATE_FORMAT(a.booked_date,'%b %Y')  as v_month,
                            DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                            count(a.pickup_id) as cnt
                            from rh_pickup_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.booked_date,'%b %y') 
                            order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                            limit 5
                        ) union all (
                            select 
                            DATE_FORMAT(a.book_date,'%b %Y')  as v_month,
                            DATE_FORMAT(a.book_date,'%Y%m') as v_month_num,
                            count(a.pick_pack_id) as cnt
                            from crit_pick_pack_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.book_date,'%b %y') 
                            order by DATE_FORMAT(a.book_date,'%Y%m') desc 
                            limit 5
                        )
                    ) as w 
                    group by w.v_month_num
                    order by w.v_month_num desc
                    limit 5
                 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['month'][]  = $row['v_month'];  
                //$rec_list[]  = $row;      
            }  
          
       }
       
       if($table == 'domestic-pickup')
       {
          /*$query = $this->db->query("                 
                select 
                b.area as city,
                count(a.pickup_id) as cnt,
                (count(a.pickup_id) / d.total * 100) as p_avg
                from rh_pickup_info as a 
                left join rh_pincode_list as b on b.pincode = a.source_pincode
                left join (select count(pickup_id)  as total  from rh_pickup_info where courier_type = 'Domestic' and DATE_FORMAT(booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m') and status != 'Delete' ) as d on 1=1
                where DATE_FORMAT(a.booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m')
                and a.courier_type = 'Domestic'
                and a.status != 'Delete'
                group by b.area
                limit 10                 
            ");*/
            
            $this->db->query('SET SQL_BIG_SELECTS=1');
               
             
          $query = $this->db->query("                 
                select 
                b.district_name as city,
                count(a.pickup_id) as cnt 
                from rh_pickup_info as a 
                left join ( select q.pincode, q.state_name, q.district_name from  crit_pincode_info as q group by q.pincode ) as b on b.pincode = a.source_pincode
                where DATE_FORMAT(a.booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m')
                and a.courier_type = 'Domestic'
                and a.status != 'Delete'
                group by b.state_name , b.district_name
                limit 10                 
            ");   
            
          
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['city'][]  = $row['city'] ;  
                //$rec_list[]  = $row;      
            }            
       }
       
       if($table == 'international-pickup')
       {
         /* $query = $this->db->query("                 
                select 
                b.area as city,
                count(a.pickup_id) as cnt,
                (count(a.pickup_id) / d.total * 100) as p_avg
                from rh_pickup_info as a 
                left join rh_pincode_list as b on b.pincode = a.source_pincode
                left join (select count(pickup_id)  as total  from rh_pickup_info where status != 'Delete' and courier_type != 'Domestic' and DATE_FORMAT(booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m') ) as d on 1=1
                where DATE_FORMAT(a.booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m')
                and a.courier_type != 'Domestic'
                and a.status != 'Delete'
                group by b.area
                limit 10                 
            "); */
            
             $this->db->query('SET SQL_BIG_SELECTS=1');
              
            
           $query = $this->db->query("                 
                select 
                b.district_name as city,
                count(a.pickup_id) as cnt 
                from rh_pickup_info as a 
                left join ( select q.pincode, q.state_name, q.district_name from  crit_pincode_info as q group by q.pincode ) as b on b.pincode = a.source_pincode
                where DATE_FORMAT(a.booked_date,'%Y%m') = DATE_FORMAT('". date('Y-m-d') ."','%Y%m')
                and a.courier_type != 'Domestic'
                and a.status != 'Delete'
                group by b.state_name , b.district_name
                limit 10                  
            ");  
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['cnt'];  
                $rec_list['city'][]  = $row['city'] ;  
                //$rec_list[]  = $row;      
            }  
          
       }
       
       if($table == 'visitor-pickup')
       {
          $query = $this->db->query("                 
                 select 
                    dh.v_month_num,
                    dh.v_month,
                    sum(dh.v_cnt) as v_cnt,
                    sum(dh.p_cnt) as p_cnt
                    from 
                    (
                    	(
                    		select  
                    		 v.v_month  ,
                    		 v.v_month_num,
                    		 count(v.v_month) as v_cnt ,
                    		 0 as p_cnt
                    		 from  
                    		 (
                    		    select 
                    		    DATE_FORMAT(a.date_time,'%b %Y') as v_month,
                    		    DATE_FORMAT(a.date_time,'%Y%m') as v_month_num,
                    		    a.ip as v_ip
                    		    from rh_visitor as a 
                    		    group by  DATE_FORMAT(a.date_time,'%m-%Y') ,a.ip  
                    		 ) as v 
                    		 group by v.v_month_num  
                    		 order by v.v_month_num  desc 
                    		 limit 5 
                    	 ) union all (
                    	   select 
                    		DATE_FORMAT(a.booked_date,'%b %Y')  as v_month,
                    		DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                    		0 as v_cnt,
                    		count(a.pickup_id) as p_cnt
                    		from rh_pickup_info as a
                            where a.status != 'Delete'
                    		group by DATE_FORMAT(a.booked_date,'%b %y') 
                    		order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                    		limit 5
                    	 )
                     ) as dh
                     group by dh.v_month_num
                     order by dh.v_month_num desc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['v_month'][]  = $row['v_month'];  
                $rec_list['v_cnt'][]  = $row['v_cnt'] ;  
                $rec_list['p_cnt'][]  = $row['p_cnt'] ;  
                //$rec_list[]  = $row;      
            }  
          
       }
       
       if($table == 'visitor-pickup-perday')
       {
          $query = $this->db->query("                 
                   select  
                    v.v_date ,
                    v.v_date_num, 
                    count(v.v_date) as v_cnt ,
                    ifnull(r.cnt,0) as p_cnt
                    from  
                    (
                    select 
                    DATE_FORMAT(a.date_time,'%b %d') as v_date,
                    DATE_FORMAT(a.date_time,'%Y%m%d') as v_date_num,
                    a.ip as v_ip
                    from rh_visitor as a 
                    group by  DATE_FORMAT(a.date_time,'%Y%m%d') ,a.ip  
                    order by  DATE_FORMAT(a.date_time,'%Y%m%d') desc ,a.ip   
                    ) as v 
                    left join ( select DATE_FORMAT(w.booked_date,'%Y%m%d') as p_date , count(w.pickup_id) as cnt from rh_pickup_info as w where w.status != 'Delete' group by DATE_FORMAT(w.booked_date,'%Y%m%d')) as r on  r.p_date = v.v_date_num
                    group by v.v_date_num  
                    order by v.v_date_num  desc  
                    limit 10 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['v_date'][]  = $row['v_date'];  
                $rec_list['v_cnt'][]  = $row['v_cnt'] ;  
                $rec_list['p_cnt'][]  = $row['p_cnt'] ;  
                //$rec_list[]  = $row;      
            }  
          
       }
       if($table == 'pickup-revenue-per-month')
       {
          $query = $this->db->query("                 
                select 
                q.v_month,
                sum(q.pick_cnt) as pick_cnt, 
                sum(q.courier_charges) as courier_charges
                from  (
                                (
                                select 
                                DATE_FORMAT(a.booked_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                                sum(a.courier_charges) as courier_charges,
                                count(a.pickup_id) as pick_cnt
                                from rh_pickup_info as a
                                where (a.`status` = 'Picked' || a.status = 'Delivered')
                                and a.booked_date <= '2019-07-31'  
                                group by DATE_FORMAT(a.booked_date,'%b %y') 
                                order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                                limit 5 
                                )  union all (
                                select 
                                DATE_FORMAT(a.paid_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.paid_date,'%Y%m') as v_month_num,
                                sum(a.courier_charges) as courier_charges,
                                count(a.pickup_id) as pick_cnt
                                from rh_pickup_info as a
                                where (a.`status` = 'Picked' || a.status = 'Delivered')
                                and a.paid_date >= '2019-08-01' 
                                group by DATE_FORMAT(a.paid_date,'%b %y') 
                                order by DATE_FORMAT(a.paid_date,'%Y%m') desc 
                                limit 5
                                ) union all (
                                select 
                                DATE_FORMAT(a.paid_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.paid_date,'%Y%m') as v_month_num,
                                sum(a.pp_charges) as courier_charges ,
                                count(a.pick_pack_id) as pick_cnt
                                from crit_pick_pack_info as a
                                where (a.`status` = 'Packed' || a.status = 'Delivered') 
                                and DATE_FORMAT(a.paid_date,'%Y') != '0000'
                                group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                                order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                                limit 5
                                )
                    ) as q 
                    group by q.v_month_num
                    order by q.v_month_num desc
                    limit 5  
            ");
              
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list['cnt'][]  = $row['pick_cnt'];  
                $rec_list['amt'][]  = $row['courier_charges'] ;    
                $rec_list['month'][]  = $row['v_month'];    
            }  
          
        }
        
        if($table == 'package_weight_int')
       {
            $sql = "
                select 
                 a.package_weight_id , 
                 a.package_weight_name 
                 from crit_package_weight as a 
                 left join crit_international_rate as b on b.package_weight = a.package_weight_id
                 left join crit_package_type as c on c.package_type_id = b.package_type and c.`status` = 'Active'
                 where a.status = 'Active'  
                    and b.country = '". $rec_id."' 
                    and c.package_type = '". $this->input->post('p_type') ."'
                group by b.package_weight
            ";
        
           $query = $this->db->query($sql);
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[$row['package_weight_id']]  = $row['package_weight_name'];     
            }
       }
        
        if($table == 'pickup-revenue-per-day')
       {
            /*
            select 
                DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                sum(a.courier_charges) as courier_charges,
                count(a.pickup_id) as pick_cnt
                from rh_pickup_info as a
                where (a.`status` = 'Picked' || a.status = 'Delivered')
                group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                limit 10  
            */
          $query = $this->db->query("   
                select 
                q.v_date,
                q.v_date_num,  
                sum(q.courier_charges) as courier_charges
                from  
                (
                    (
                    select 
                    DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                    sum(a.courier_charges) as courier_charges 
                    from rh_pickup_info as a
                    where (a.`status` = 'Picked' || a.status = 'Delivered')
                    and a.booked_date <= '2019-07-31'  
                    group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                    limit 7 
                    ) union all (
                    select 
                    DATE_FORMAT(a.paid_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.paid_date,'%Y%m%d') as v_date_num,
                    sum(a.courier_charges) as courier_charges 
                    from rh_pickup_info as a
                    where (a.`status` = 'Picked' || a.status = 'Delivered')
                    and a.paid_date >= '2019-08-01'  
                    group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                    limit 10
                    ) union all (
                    select 
                    DATE_FORMAT(a.paid_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.paid_date,'%Y%m%d') as v_date_num,
                    sum(a.pp_charges) as courier_charges 
                    from crit_pick_pack_info as a
                    where (a.`status` = 'Packed' || a.status = 'Delivered') 
                    group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                    limit 10
                    )
                ) as q
                group by q.v_date_num 
                order by q.v_date_num desc
                limit 10     
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                //$rec_list['cnt'][]  = $row['pick_cnt'];  
                $rec_list['amt'][]  =  $row['courier_charges']; 
                $rec_list['v_date'][]  = $row['v_date'];    
                $rec_list['v_date_num'][]  = $row['v_date_num'];    
            }  
          
        }
       
       
       if($table == 'domestic_rate_info_v3')
       {
            $query = $this->db->query(" 
                select 
                a.*
                from crit_domestic_rate_info_v3 as a
                where a.domestic_rate_id =  $rec_id
                order by  a.domestic_rate_id asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row;     
            }
       }
       
       if($table == 'quick_quote_info')
       {
            $query = $this->db->query(" 
                select 
                a.*
                from crit_quick_quote_info as a
                where a.quick_quote_id =  $rec_id
                order by  a.quick_quote_id asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row;     
            }
       }
       
       if($table == 'user')
       {
            $query = $this->db->query(" 
                select 
                a.*
                from rh_user_info as a
                where a.user_id =  $rec_id
                order by  a.first_name asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row;     
            }
       }
       
       if($table == 'news_info')
       {
            $query = $this->db->query(" 
                select 
                a.*
                from crit_news_info as a
                where a.news_id =  $rec_id
                order by  a.news_date asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row;     
            }
       }
       
       
       if($table == 'agent')
       {
            $query = $this->db->query(" 
                select 
                a.*
                from crit_agent_info as a
                where a.agent_id =  $rec_id
                order by  a.contact_person asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row;     
            }
       }
        
          
       if($table == 'district_list')
       {
          $query = $this->db->query("
            select 
            a.districts_name as city  
            from zazu_districts_info as a
            left join zazu_states_info as b on b.state_id = a.state_id
            where b.state_name = '".$rec_id ."'
            order by a.districts_name asc
          "
          );
             
            $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[]  = $row;     
            }  
          
       }
       
       if($table == 'district_lookup')
       {
          $query = $this->db->query("
            select 
            a.district_name as district
            from crit_pincode_info as a
            where a.state_name = '".$rec_id ."'
            group by a.district_name 
            order by a.district_name asc 
          "
          );
             
            $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[]  = $row;     
            }  
          
       }
       if($table == 'area_list')
       {
          $query = $this->db->query("
          select  
            a.area_name as area
            from zazu_city_area_info as a   
            left join zazu_districts_info as b on b.districts_id = a.districts_id
            where b.districts_name = '".$rec_id ."'
            order by a.area_name asc  
          "
          );
             
            $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[] = $row;     
            }  
          
         }
       
       if($table == 'area')
       {
          $query = $this->db->query("
          select 
            a.city_area_id,
            a.state_id,
            a.districts_id,
            a.city_name,
            a.area_name 
            from zazu_city_area_info as a   
            where a.city_area_id =  '".$rec_id ."'
          "
          );
             
            $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list = $row;     
            }  
          
         } 
         
        if($table == 'pickup_info')
        {
              
            $query = $this->db->query("
            select 
            a.* ,
            DATE_FORMAT(a.pickup_schedule_timing,'%Y-%m-%dT%H:%i') as schedule_time,
            b.invoice_no,
            b.invoice_id
            from rh_pickup_info as a  
            left join crit_invoice_info as b on b.pickup_id = a.pickup_id 
            where a.pickup_id =  '".$rec_id ."'
            "
            );
             
            $rec_list = array();  
            
            foreach($query->result_array() as $row)
            {
                $rec_list = $row;     
            }  
            
            //echo (json_encode($rec_list)); exit;
        
        }
        
        if($table == 'pick_pack_info')
        {
              
            $query = $this->db->query("
            select 
            a.*  
            from crit_pick_pack_info as a   
            where a.pick_pack_id =  '".$rec_id ."'
            "
            );
             
            $rec_list = array();  
            
            foreach($query->result_array() as $row)
            {
                $rec_list = $row;     
            }  
            
            //echo (json_encode($rec_list)); exit;
        
        }
        
        
        
       if($table == 'delivery_alert')
       {
            $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
            $query = $this->db->query("  
                select 
                a.pickup_id as ref_no,
                b.state_name as src_state,
                lower(b.district_name) as src_city,
                c.state_name as dest_state,
                lower(c.district_name) as  dest_city
                from rh_pickup_info as a
                left join ( select q.pincode, q.state_name, q.district_name from  crit_pincode_info as q group by q.pincode ) as b on b.pincode = a.source_pincode
                left join ( select q.pincode, q.state_name, q.district_name from  crit_pincode_info as q group by q.pincode ) as c on c.pincode = a.destination_pincode
                where a.`status` = 'Picked' and a.courier_type = 'Domestic'
                and a.delivered_date = '". date('Y-m-d') ."'  
                order by a.pickup_id asc 
            ");
             
           $rec_list = array();  
           
           //$cnt = $query->num_rows(); 
           //$rec_list[]  = array(); 
            foreach($query->result_array() as $row)
            {
                $rec_list[]  = $row ;
            }  
       } 
       
       if($table == 'pincode-state-district')
       {
            $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
            $query = $this->db->query("  
                select 
                q.state_name as state, 
                q.district_name as district
                from crit_pincode_info as q 
                where q.pincode = '". $rec_id."' 
                and q.`status` = 'Active'
                group by q.state_name , q.district_name 
            ");
             
           $rec_list = array();  
           
            //$cnt = $query->num_rows();  
           
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row ;
            } 
             
       } 
       
       if($table == 'state')
       {
            $query = $this->db->query(" SET SQL_BIG_SELECTS=1 ");
            $query = $this->db->query("  
                select 
                q.state_name as state 
                from crit_pincode_info as q 
                where q.`status` = 'Active'
                group by q.state_name  
            ");
             
           $rec_list = array();  
           
            //$cnt = $query->num_rows();  
           
            foreach($query->result_array() as $row)
            {
                $rec_list[$row['state']]  = $row['state'] ;
            } 
             
       } 
       
       if($table == 'location')
       {
            $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
            $query = $this->db->query("  
                select  
                q.district_name as district
                from crit_pincode_info as q 
                where q.state_name = '". $rec_id."' 
                and q.`status` = 'Active'
                group by q.state_name , q.district_name 
            ");
             
           $rec_list = array();  
           
            //$cnt = $query->num_rows();  
           
            foreach($query->result_array() as $row)
            {
                $rec_list[ucwords(strtolower($row['district']))]  = ucwords(strtolower($row['district'])) ;
            } 
             
       }
       
       if($table == 'load-international-package-type')
       {
          $query = $this->db->query(" 
                select 
                a.package_type as id ,
                c.package_type as value
                from crit_international_rate as a 
                left join crit_package_type as c on c.package_type_id = a.package_type 
                where a.country =  '".$rec_id . "' 
                group by a.package_type 
                order by c.package_type asc 
                 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[$row['value']]  = $row['value'];     
            }  
          
       }
       
       if($table == 'load-international-package-weight')
       {
          $query = $this->db->query(" 
                select 
                a.package_weight as id ,
                d.package_weight_name as value,
                d.package_weight
                from crit_international_rate as a 
                left join crit_package_type as c on c.package_type_id = a.package_type 
                left join crit_package_weight as d on d.package_weight_id = a.package_weight 
                where a.country = '". $this->input->post('country') ."' and  c.package_type = '". $rec_id ."' and d.`status` = 'Active'
                group by a.package_weight 
                order by d.package_weight asc 
                 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[$row['id']]  = $row['value'];     
            }  
          
       }
       
       if($table == 'account_head')
       {
          $query = $this->db->query(" 
                select
                a.account_head_id as id,
                a.account_head_name as value
                from crit_account_head as a 
                where a.type =  '".$rec_id . "'
                order by a.account_head_name asc 
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[]  = $row;     
            }  
          
       }
           
        if($table == 'sub_account_head')
        {
              $query = $this->db->query(" 
                    select
                    a.sub_account_head_id, 
                    a.type,
                    a.account_head_id,
                    a.sub_account_head_name,
                    a.status
                    from crit_sub_account_head as a 
                    where a.sub_account_head_id =  '".$rec_id . "'
                    order by a.sub_account_head_name asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list  = $row;     
                }                
        } 
        
        
        if($table == 'load_sub_account_head')
        {
              $query = $this->db->query(" 
                    select
                    a.sub_account_head_id as id,  
                    a.sub_account_head_name as value 
                    from crit_sub_account_head as a 
                    where a.account_head_id =  '".$rec_id . "' 
                    and a.type = '". $this->input->post('typ') ."' 
                    and a.status='Active'
                    order by a.sub_account_head_name asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list[] = $row;     
                }  
              
        } 
       
       
        if($table == 'cash_inward')
        {
              $query = $this->db->query(" 
                    select
                    a.cash_inward_id, 
                    a.inward_date,
                    a.account_head_id,
                    a.sub_account_head_id,
                    a.amount,
                    a.remarks
                    from crit_cash_inward as a 
                    where a.cash_inward_id =  '".$rec_id . "'
                    order by a.cash_inward_id asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list  = $row;     
                }  
              
        }     
        
        if($table == 'cash_outward')
        {
              $query = $this->db->query(" 
                    select
                    a.cash_outward_id, 
                    a.outward_date,
                    a.account_head_id,
                    a.sub_account_head_id,
                    a.amount,
                    a.cash_received_by,
                    a.remarks
                    from crit_cash_outward as a 
                    where a.cash_outward_id =  '".$rec_id . "'
                    order by a.cash_outward_id asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list  = $row;     
                }  
              
        }
        
        if($table == 'invoice')
        {
              $query = $this->db->query(" 
                    select  
                    a.invoice_id,
                    a.invoice_no,   
                    a.invoice_date,   
                    a.client_name,  
                    a.address, 
                    a.state, 
                    a.contact_no, 
                    a.client_GSTIN,
                    a.way_bill,
                    a.weight,
                    a.amount,
                    a.GST_percentage,
                    a.total_amount        
                    from crit_invoice_info as a 
                    where a.invoice_id =  '".$rec_id . "'
                    order by a.invoice_id asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list  = $row;     
                }  
              
        }
        
        if($table == 'agent_payment')
        {
              $query = $this->db->query(" 
                    select  
                    a.agent_payment_id,
                    a.agent_id,  
                    a.pickup_id, 
                    a.payment_date,   
                    a.request_date,  
                    a.pay_mode, 
                    a.req_amount, 
                    a.paid_amount, 
                    a.paid_acknowledge, 
                    a.req_remarks,
                    a.paid_remarks,
                    a.status  
                    from crit_agent_payment_info as a 
                    where a.agent_payment_id =  '".$rec_id . "'
                    order by a.agent_payment_id asc 
                ");
                 
               $rec_list = array();  
        
                foreach($query->result_array() as $row)
                {
                    $rec_list  = $row;     
                }  
              
        }
        
        
        
       
       /*if($table == 'pincode')
       {
            $query = $this->db->query(" select a.pincode as id , a.area_name as area  from crit_pincode_info as a where a.pincode = '". $rec_id."' order by a.pincode asc ");
             
           $rec_list = array();  
           
           $cnt = $query->num_rows();
           
           if($cnt == 1) { 
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row ;
            }
            } else  {
                $rec_list['id'] = 0;
            }
       }*/
       
       $this->db->close();
       
       header('Content-Type: application/x-json; charset=utf-8');

       echo (json_encode($rec_list));  
	}
    
    private function get_pickup_registered_mobile($pickup_id)
    {
        $sql = "select a.pickup_registered_by , a.sender_phone , a.receiver_phone   from rh_pickup_info a where a.pickup_id = ". $pickup_id;
        
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row)
            {
                if($row['pickup_registered_by'] == 'Sender'){
                    $mobile  = $row['sender_phone'] ;
                } elseif($row['pickup_registered_by'] == 'Receiver') {
                    $mobile  = $row['receiver_phone'] ;
                } else {
                    $mobile  = $row['sender_phone'] ;
                }   
            }
            
         return $mobile ; 
    }
    
    private function get_pincode_state_district($pincode)
    {
        $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
            $query = $this->db->query("  
                select 
                q.state_name as state, 
                q.district_name as district
                from crit_pincode_info as q 
                where q.pincode = '". $pincode."' 
                and q.`status` = 'Active'
                group by q.state_name , q.district_name 
            ");
             
           $rec_list = array();  
           
            //$cnt = $query->num_rows();  
           
            foreach($query->result_array() as $row)
            {
                $rec_list  = $row ;
            }
            
         return $rec_list ;   
    }
    
    public function get_content($table = '', $rec_id = '')
    {
       //if(!$this->session->userdata('m_logged_in'))  redirect();
       
       if(empty($table) && empty($rec_id)) {
           $table = $this->input->post('tbl') ;
           $rec_id = $this->input->post('id'); 
           $flg = true;
       } else {
        $flg = false;
       } 
        
       
       if($table == 'pickup-view')
       {
          $query = $this->db->query("
            select  
            a.pickup_id,
            a.booked_date,
            a.courier_type,
            if(a.same_as_sender_address != 1 , concat(a.contact_person_name , ' - ' , a.contact_person_mobile , '<br>', a.pickup_address ),concat(a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address )) as pickup_address,
            concat(ifnull(a.source_pin_area, a.source_pincode) , '<br>' , a.sender_name , ' - ' , a.sender_phone , '<br>', a.sender_address ) as source_address,
            concat(if(a.courier_type = 'Domestic', ifnull(a.destination_pin_area, a.destination_pincode), b.country_name ) , '<br>' , a.receiver_name , ' - ' , a.receiver_phone , '<br>', a.receiver_address ) as destination_address,
            concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), c.package_weight_name ) ) as package_details,
            (if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
            a.transport_mode,
            (if(a.packing_required = '1', 'Yes','No')) as packing_required, 
            a.approx_charges,
            a.charges_breakup,
            a.special_instruction,
            a.pickup_registered_by,
            a.status,
            e.service_provider_name as service_provider,
            a.bill_no as awb_no,
            a.courier_charges,
            a.pickup_date,
            a.paid_date,
            a.delivered_date
            from rh_pickup_info as a 
            left join rh_country_info as b on b.country_id = a.destination_country
            left join crit_package_weight as c on c.package_weight_id = a.package_weight_int 
            left join rh_service_provider_info as e on e.service_provider_id = a.service_provider_id 
            where a.pickup_id   = '" . $rec_id . "'
            and a.status != 'Delete'
            order by a.pickup_id desc
          "
          ); 
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[]  = $row;     
            }  
          
       }
       
       if($table == 'pickup_info')
       {
        //concat(a.source_pincode , '<br>' , a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address ) as source_address,
        //(if(a.package_length != '' , concat(a.package_length , 'X' , a.package_width , ' X ', a.package_height),'') ) as package_dimension,
          $query = $this->db->query("
            select
                a.pickup_id as pickup_ref_no,
                DATE_FORMAT(a.booked_date,'%d-%m-%Y %h:%i %p') as booked_date,
                a.courier_type,
                if(a.same_as_sender_address != 1 , concat(a.source_pincode, '<br>',a.contact_person_name , '<br>' , a.contact_person_mobile , '<br>', a.pickup_address ),concat(a.source_pincode, '<br>', a.sender_name , '<br>' , a.sender_phone , '<br>', a.sender_address )) as pickup_address,
                concat(if(a.courier_type = 'Domestic', a.destination_pincode, b.country_name ) , '<br>' , a.receiver_name , '<br>' , a.receiver_phone , '<br>', a.receiver_address ) as destination_address,
                concat(a.package_type,' , ',  if(a.courier_type = 'Domestic', concat(a.package_weight,' Kgs'), c.package_weight_name ) ) as package_details,
                a.transport_mode,
                (if(a.packing_required = '1', 'Yes','No')) as packing_required, 
                concat(a.package_length,' x ', a.package_width , ' x ', a.package_height) as dimension,
                a.special_instruction as package_content ,
                a.pickup_registered_by,
                a.pickup_schedule_timing as pickup_schedule_date,
                a.approx_charges,
                a.charges_breakup                 
                from rh_pickup_info as a 
                left join rh_country_info as b on b.country_id = a.destination_country
                left join crit_package_weight as c on c.package_weight_id = a.package_weight_int
             where a.pickup_id = '". $rec_id. "'
             and a.status != 'Delete'
             order by a.booked_date desc 
          "
          ); 
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[] = $row;
                /*foreach($row as $fld => $val){
                    $rec_list[$fld]  = $val;     
                }*/
            }  
          
       }
       
       if($table == 'franchise_enquiry')
       {
         /* $query = $this->db->query("
            select 
                DATE_FORMAT(a.franchise_enquiry_date,'%d-%m-%Y %h:%i %p') as enquiry_date,
                a.contact_person_name,
                a.email,
                a.mobile,
                a.interested_in,
                b.state_name as state,
                c.city_name as city,
                a.address,
                a.messages
                from rh_franchise_enquiry_info as a 
                left join rh_states_info as b on b.id = a.state_id
                left join rh_location_info as c on c.location_id = a.location_id
                where a.franchise_enquiry_id = '". $rec_id. "' 
                order by a.franchise_enquiry_id desc
          "
          ); */
          
          $query = $this->db->query("
            select 
                DATE_FORMAT(a.franchise_enquiry_date,'%d-%m-%Y %h:%i %p') as enquiry_date,
                a.contact_person_name,
                a.email,
                a.mobile,
                a.interested_in,
                a.state,
                a.district,
                a.address,
                a.messages
                from rh_franchise_enquiry_info as a  
                where a.franchise_enquiry_id = '". $rec_id. "' 
                order by a.franchise_enquiry_id desc
          "
          ); 
             
            $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                $rec_list[] = $row;
                /*foreach($row as $fld => $val){
                    $rec_list[$fld]  = $val;     
                }*/
            }  
          
       }
       
       
       
       if($table == 'todays_log')
       {
         $sql = "
                select  
                c.first_name as user_name,
                DATE_FORMAT(max(q.login_time),'%h:%i %p') as login_time,
                DATE_FORMAT(max(q.logout_time),'%h:%i %p') as logout_time
                from (
                (
                select 
                a.user_id,
                a.page,
                min(a.date_time) as login_time,
                '' as logout_time 
                from crit_user_history_info as a
                where DATE_FORMAT(a.date_time,'%Y-%m-%d') = '". date('Y-m-d') ."' 
                and a.page = 'Login'
                group by a.user_id  
                )
                union all (
                select 
                a.user_id,
                a.page,
                '' as login_time,
                max(a.date_time) as logout_time 
                from crit_user_history_info as a
                where DATE_FORMAT(a.date_time,'%Y-%m-%d') = '". date('Y-m-d') ."' 
                and a.page = 'Logout'
                group by a.user_id 
                )
                ) as q
                left join rh_user_info as c on c.user_id = q.user_id
                group by q.user_id
                order by c.first_name asc ";
                
                $query = $this->db->query($sql);
                $rec_list = array();  
    
                foreach($query->result_array() as $row)
                {
                    $rec_list[] = $row; 
                }
                
       }
       
       if($table == 'tracking_pickup')
       {
         $sql = "select  
                    a.pickup_id as ref_no,
                    a.tracking_status,
                    b.first_name as pickup_person ,
                    a.pickup_charges,
                    a.created_date 
                    from crit_tracking_info as a  
                    left join rh_user_info as b on b.user_id = a.pickup_person_id
                    where a.pickup_id = '". $rec_id. "' and a.tracking_status = '". $this->input->post('status') ."' 
                    order by a.created_date asc 
                ";
                
                $query = $this->db->query($sql);
                $rec_list = array();  
    
                foreach($query->result_array() as $row)
                {
                    $rec_list[] = $row; 
                }
                
       }
       
       if($table == 'agent-view')
       {
         $sql = "select  
                    a.agent_type, 
                    a.contact_person as agent_name, 
                    a.mobile, 
                    a.phone, 
                    a.email, 
                    a.address, 
                    a.state, 
                    a.city, 
                    a.aadhar_no,  
                    a.pay_type, 
                    a.pay_mobile, 
                    a.ac_no, 
                    a.ac_holder_name, 
                    a.ac_bank, 
                    a.ac_branch, 
                    a.ifsc_code,
                    a.`status` 
                    from crit_agent_info as a   
                    where a.agent_id = '". $rec_id. "' 
                    order by a.agent_id asc 
                ";
                
                $query = $this->db->query($sql);
                $rec_list = array();  
    
                foreach($query->result_array() as $row)
                {
                    $rec_list[] = $row; 
                }
                
       }
        
        
       
       
      if(!empty($rec_list)) {
        
        if(count($rec_list) > 1 ) {
       
           $content = '
           <table class="table table-bordered table-responsive table-striped" id="sts" width="100%">
             <thead>
                <tr>';
                $content .= '<th>S.No</th>';
                foreach($rec_list[0] as $fld => $val) { 
                    if($fld != 'id' && $fld != 'tbl')
                        $content .= '<th class="text-capitalize">'.  str_replace('_',' ',$fld) .'</th>';
                }  
                 //$content .= '<th>Action</th>';
           $content .= '</tr>
              </thead>  
              <tbody>';
                foreach($rec_list as $k => $info) {  
                   $content .= '<tr>
                      <td>'.($k+1).'</td>';
                    foreach($rec_list[0] as $fld => $val) { 
                        if($fld != 'id') {
                             if($fld != 'tbl')
                                $content .= '<td>'. $info[$fld]  .'</td>';
                        }
                            
                    }                  
                    //$content .= '<td><button class="btn btn-warning btn_chld_del" value="'. $info['id']  .'" data="'. $info['tbl']  .'"><i class="fa fa-remove"></i></button></td>';    
                   $content .= '</tr>';     
                  }  
              $content .= '
              </tbody>  
            </table>';
            } else
            {
                $content = ' <table class="table table-striped table-bordered" border="1" width="100%">  ';
                $content .= '<tr><th colspan="2">'.  strtoupper(str_replace('_',' ', $table)) .'</th></tr>';
                foreach($rec_list[0] as $fld => $val) { 
                    if($fld != 'id' && $fld != 'tbl')
                    {
                        $content .= '<tr>';      
                        $content .= '<th>'. strtoupper(str_replace('_',' ',$fld)) .'</th>';
                        $content .= '<td>'.  $val.'</td>';
                        $content .= '</tr>';  
                    }
                }   
                $content .= '</table>';              
            }
        } else {
            $content = "<strong>No Record Found</strong>";
        }
         
        if(!$flg)
            return $content;  
        else
            echo $content;     
       
    }
    
    public function get_content_v2($table = '', $rec_id = '')
    {
        
       if(empty($table) && empty($rec_id)) {
           $table = $this->input->post('tbl') ;
           $rec_id = $this->input->post('id'); 
           $edit_mode = $this->input->post('edit_mode'); 
           $del_mode = $this->input->post('del_mode'); 
           $flg = true;
       } else {
        $flg = false;
       }
       
       
        
      if($table == 'booking-tracking')
       {
         $sql = " 
            select 
            a.pmc_tracking_id as id,
            a.status_datetime as t_date,
            a.tracking_status,
            a.city,
            a.remarks
            from crit_pmc_tracking_info as a 
            where a.tracking_status !='Delete'
            and a.pickup_id =  '". $rec_id. "'
            order by a.status_datetime asc ";
                
            $query = $this->db->query($sql);
            $rec_list = array();  

            foreach($query->result_array() as $row)
            {
                $rec_list[] = $row; 
            }
                
       } 
        
       
       
        
        
       

       
       
       if(!empty($rec_list)) {
        
        if(count($rec_list) >= 1  ) {
       
           $content = '
           <table class="table table-bordered table-responsive table-striped" id="sts">
             <thead>
                <tr>';
                $content .= '<th>S.No</th>';
                foreach($rec_list[0] as $fld => $val) { 
                    if($fld != 'id' && $fld != 'tbl')
                        $content .= '<th class="text-capitalize">'.   str_replace('_',' ',$fld) .'</th>';
                } 
                if($edit_mode == 1)  
                   $content .= '<th>Edit</th>';
                if($del_mode == 1) 
                   $content .= '<th>Delete</th>'; 
           $content .= '</tr>
              </thead>  
              <tbody>';
                foreach($rec_list as $k => $info) {  
                   $content .= '<tr>
                      <td>'.($k+1).'</td>';
                    foreach($rec_list[0] as $fld => $val) { 
                        if($fld != 'id') {
                             if($fld != 'tbl')
                                $content .= '<td>'. $info[$fld]  .'</td>';
                        }
                            
                    } 
                    if($edit_mode == 1)                 
                        $content .= '<td><button type="button" class="btn btn-info btn-sm btn_chld_edit" value="'. $info['id']  .'" data="'. $table  .'"><i class="fa fa-edit"></i></button></td>';    
                    if($del_mode == 1)  
                        $content .= '<td><button type="button" class="btn btn-danger btn-sm btn_chld_del" value="'. $info['id']  .'" data="'. $table  .'"><i class="fa fa-remove"></i></button></td>';    
                   $content .= '</tr>';     
                  }  
              $content .= '
              </tbody>  
            </table>';
            } else
            {
                $content = ' <table class="table table-bordered table-responsive table-striped">  ';
                $content .= '<tr><th colspan="2">'.  strtoupper(str_replace('_',' ', $table)) .'</th></tr>';
                foreach($rec_list[0] as $fld => $val) { 
                    if($fld != 'id' && $fld != 'tbl')
                    {
                        $content .= '<tr>';      
                        $content .= '<th>'. strtoupper(str_replace('_',' ',$fld)) .'</th>';
                        $content .= '<td>'.  $val.'</td>';
                        $content .= '</tr>';  
                    }
                }   
                if($edit_mode == 1)                 
                        $content .= '<tr><th>Edit</th><td><button type="button" class="btn btn-info btn-sm btn_chld_edit" value="'. $rec_list[0]['id']  .'" data="'. $table  .'"><i class="fa fa-edit"></i></button></td></tr>';    
                    if($del_mode == 1)  
                        $content .= '<tr><th>Delete</th><td><button type="button" class="btn btn-danger btn-sm btn_chld_del" value="'. $rec_list[0]['id']  .'" data="'. $table  .'"><i class="fa fa-remove"></i></button></td></tr>';
                
                $content .= '</table>';              
            }
        } else {
            $content = "<strong>No Record Found</strong>";
        }
         
        if(!$flg)
            return $content;  
        else
            echo $content;    
       
    }
    
    public function my_dash()
    {
        if(!$this->session->userdata('m_logged_in'))  redirect();
        
         $timezone = "Asia/Calcutta";
		 if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
         
         $data['js'] = 'my_dash.inc';
         
            $sql = 
             "  
             SELECT
                DATE_FORMAT(a.booked_date, '%d-%m-%Y [%a]') AS b_day,
                COUNT(a.pickup_id) AS cnt,
                SUM(a.courier_charges) AS courier_charges,
                SUM(a.approx_charges) AS approx_charges
            FROM
                rh_pickup_info AS a
            WHERE
                a.status != 'Delete' AND a.status != 'Cancelled'
            GROUP BY
                DATE_FORMAT(a.booked_date, '%Y-%m-%d')
            ORDER BY
                DATE_FORMAT(a.booked_date, '%Y-%m-%d')
            DESC
            LIMIT 10;
            ";  
            
            
            $query = $this->db->query($sql);
             
           $data['last_booking_list'] = array();  
            
    
            foreach($query->result_array() as $row)
            {
                $data['last_booking_list'][] = $row;      
            } 
            
            $sql ="
            select 
            DATE_FORMAT(a.booked_date,'%b-%Y') as b_month,
            count(a.pickup_id) as cnt ,
            sum(a.courier_charges) as courier_charges ,
            sum(a.approx_charges) as approx_charges,
            b.visitor
            from rh_pickup_info as a 
            left join (
             select
            z.v_month,
            count(z.ip) as visitor 
            from 
            (
            select DISTINCT
            DATE_FORMAT(a.date_time,'%Y%m') as v_month,
            a.ip 
            from rh_visitor as a
            where DATE_FORMAT(a.date_time,'%Y%m') between DATE_FORMAT(DATE_SUB(now(),INTERVAL 12 month),'%Y%m') and DATE_FORMAT(now(),'%Y%m')
            group by DATE_FORMAT(a.date_time,'%Y%m')  , a.ip
            order by DATE_FORMAT(a.date_time,'%Y%m')  desc
            ) as z 
            group by z.v_month
            order by z.v_month desc  
            limit 12 
            ) as b on b.v_month = DATE_FORMAT(a.booked_date,'%Y%m')
            where a.status != 'Delete' and a.status != 'Cancelled'   
            group by  DATE_FORMAT(a.booked_date,'%Y%m')
            order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
            limit 12 
            ";
            
            $query = $this->db->query($sql);
             
           $data['booking_summary'] = array();  
            
    
            foreach($query->result_array() as $row)
            {
                $data['booking_summary'][] = $row;      
            } 
            
            
            
          $this->load->view('my_dash',$data);      
        
        
        
    }
    
    public function dash()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        
        
        
        //$this->send_sms('7092889602','test sms');
        
        //$wa_status = $this->send_whatsapp_msg('PMC-001' ,'917092889602','API - Test');
        //$wa_status = $this->curl_get('https://www.waboxapp.com/api/send/chat' , array('token' => 'c4094b05efd18670fd45fa6b35d22a365da988cc433cc','uid' => '916374711150','to' => '917092889602','custom_uid'=>'PMCL-004','text'=>'API TEST'));
        
        //print_r($wa_status); exit;
        
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN and $this->session->userdata('m_is_admin') != USER_MARKETING and $this->session->userdata('m_is_admin') != USER_MANAGER ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        
        $timezone = "Asia/Calcutta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
        
        
        
       //print_r($this->get_pincode_state_district('530002')); exit;
        
       // $db_api = $this->load->database('db_api', TRUE);
    	
	    //echo date('Y-m-d');
        
        //$this->send_sms('9952823214','Domestic Pickup\n Non-Document:0.1Kg\n Address : Vaibhav, 9920263652, Unit 4 & 5, Capella Block, Ascendas The V IT Park, HITEC City, Hyderabad');
        
        
         
        
        $data['js'] = 'dash.inc';
        
        $data['polar_curr_mon_status'] = array();        
        $data['polar_curr_mon_count'] = array(); 
        
        // PickUps
        
        $query = $this->db->query(" 
                 select 
                    w.v_date_num,
                    w.v_date,
                    sum(cnt)  as cnt
                    from (
                        (
                            select 
                            DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                            DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                            count(a.pickup_id) as cnt
                            from rh_pickup_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                            order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                            limit 10
                        ) union all (
                            select 
                            DATE_FORMAT(a.book_date,'%b %d')  as v_date,
                            DATE_FORMAT(a.book_date,'%Y%m%d') as v_date_num,
                            count(a.pick_pack_id) as cnt
                            from crit_pick_pack_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.book_date,'%d-%m-%Y') 
                            order by DATE_FORMAT(a.book_date,'%Y%m%d') desc 
                            limit 10
                        )
                    ) as w 
                    group by w.v_date_num
                    order by w.v_date_num desc
                    limit 10
            "); 
           
            foreach($query->result_array() as $row)
            {
                $data['p_d_cnt'][]  = $row['cnt'];  
                $data['p_d_date'][]  = $row['v_date'];  
                //$rec_list[]  = $row;      
            }   
             
         $query = $this->db->query(" 
                
                select 
                    w.v_month_num,
                    w.v_month,
                    sum(cnt)  as cnt
                    from (
                        (
                            select 
                            DATE_FORMAT(a.booked_date,'%b %Y')  as v_month,
                            DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                            count(a.pickup_id) as cnt
                            from rh_pickup_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.booked_date,'%b %y') 
                            order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                            limit 10
                        ) union all (
                            select 
                            DATE_FORMAT(a.book_date,'%b %Y')  as v_month,
                            DATE_FORMAT(a.book_date,'%Y%m') as v_month_num,
                            count(a.pick_pack_id) as cnt
                            from crit_pick_pack_info as a
                            where a.status != 'Delete'
                            group by DATE_FORMAT(a.book_date,'%b %y') 
                            order by DATE_FORMAT(a.book_date,'%Y%m') desc 
                            limit 10
                        )
                    ) as w 
                    group by w.v_month_num
                    order by w.v_month_num desc
                    limit 10
                 
            ");
              
    
            foreach($query->result_array() as $row)
            {
                $data['p_m_cnt'][]  = $row['cnt'];  
                $data['p_m_month'][]  = $row['v_month'];  
                //$rec_list[]  = $row;      
            }             
        
        
        // Vistors
        
        $query = $this->db->query(" 
                select 
                count(v.v_date) as cnt , 
                v.v_date ,
                v.v_date_num 
                from  
                (
                   select 
                   DATE_FORMAT(a.date_time,'%b %d') as v_date,
                   DATE_FORMAT(a.date_time,'%Y%m%d') as v_date_num,
                   a.ip as v_ip
                   from rh_visitor as a 
                   group by  DATE_FORMAT(a.date_time,'%d-%m-%Y') ,a.ip  
                   order by  DATE_FORMAT(a.date_time,'%d-%m-%Y') desc ,a.ip  
                ) as v 
                group by v.v_date_num  
                order by v.v_date_num  desc 
                limit 10
            ");
             
        
            foreach($query->result_array() as $row)
            {
                $data['v_per_d_cnt'][]  = $row['cnt'];  
                $data['v_per_date'][]  = $row['v_date'];   
            } 
        
        
          $query = $this->db->query(" 
                select 
                count(v.v_month) as cnt , 
                v.v_month  
                from  
                (
                   select 
                   DATE_FORMAT(a.date_time,'%b-%Y') as v_month,
                   DATE_FORMAT(a.date_time,'%Y%m') as v_month_num,
                   a.ip as v_ip
                   from rh_visitor as a 
                   group by  DATE_FORMAT(a.date_time,'%m-%Y') ,a.ip  
                ) as v 
                group by v.v_month_num  
                order by v.v_month_num  desc 
                limit 10
            ");  
    
            foreach($query->result_array() as $row)
            {
                $data['v_per_m_cnt'][]  = $row['cnt'];  
                $data['v_per_month'][]  = $row['v_month']; 
            }  
        
        
        
            
       if($this->session->userdata('m_is_admin') != USER_MANAGER )  {   
            
       // Revenue
       
        $query = $this->db->query("   
                select 
                q.v_date,
                q.v_date_num,  
                sum(q.courier_charges) as courier_charges
                from  
                (
                    (
                    select 
                    DATE_FORMAT(a.booked_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.booked_date,'%Y%m%d') as v_date_num,
                    sum(a.courier_charges) as courier_charges 
                    from rh_pickup_info as a
                    where (a.`status` = 'Picked' || a.status = 'Delivered')
                    and a.booked_date <= '2019-07-31'  
                    group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                    limit 7 
                    ) union all (
                    select 
                    DATE_FORMAT(a.paid_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.paid_date,'%Y%m%d') as v_date_num,
                    sum(a.courier_charges) as courier_charges 
                    from rh_pickup_info as a
                    where (a.`status` = 'Picked' || a.status = 'Delivered')
                    and a.paid_date >= '2019-08-01'  
                    group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                    limit 10
                    ) union all (
                    select 
                    DATE_FORMAT(a.paid_date,'%b %d')  as v_date,
                    DATE_FORMAT(a.paid_date,'%Y%m%d') as v_date_num,
                    sum(a.pp_charges) as courier_charges 
                    from crit_pick_pack_info as a
                    where (a.`status` = 'Packed' || a.status = 'Delivered') 
                    group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                    order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                    limit 10
                    )
                ) as q
                group by q.v_date_num 
                order by q.v_date_num desc
                limit 10     
            ");
             
           $rec_list = array();  
    
            foreach($query->result_array() as $row)
            {
                //$rec_list['cnt'][]  = $row['pick_cnt'];  
                $data['today_revenue'][$row['v_date_num']]  =  $row['courier_charges']; 
                
                $data['revenue_per_day_amt'][]  =  $row['courier_charges']; 
                $data['revenue_per_day_date'][]  = $row['v_date'];    
                $data['revenue_per_day_date_num'][]  = $row['v_date_num'];    
            }  
          
             
            
        $query = $this->db->query("                 
                select 
                q.v_month,
                sum(q.pick_cnt) as pick_cnt, 
                sum(q.courier_charges) as courier_charges
                from  (
                                (
                                select 
                                DATE_FORMAT(a.booked_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.booked_date,'%Y%m') as v_month_num,
                                sum(a.courier_charges) as courier_charges,
                                count(a.pickup_id) as pick_cnt
                                from rh_pickup_info as a
                                where (a.`status` = 'Picked' || a.status = 'Delivered')
                                and a.booked_date <= '2019-07-31'  
                                group by DATE_FORMAT(a.booked_date,'%b %y') 
                                order by DATE_FORMAT(a.booked_date,'%Y%m') desc 
                                limit 10 
                                )  union all (
                                select 
                                DATE_FORMAT(a.paid_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.paid_date,'%Y%m') as v_month_num,
                                sum(a.courier_charges) as courier_charges,
                                count(a.pickup_id) as pick_cnt
                                from rh_pickup_info as a
                                where (a.`status` = 'Picked' || a.status = 'Delivered')
                                and a.paid_date >= '2019-08-01' 
                                group by DATE_FORMAT(a.paid_date,'%b %y') 
                                order by DATE_FORMAT(a.paid_date,'%Y%m') desc 
                                limit 10
                                ) union all (
                                select 
                                DATE_FORMAT(a.paid_date,'%b\'%y')  as v_month,
                                DATE_FORMAT(a.paid_date,'%Y%m') as v_month_num,
                                sum(a.pp_charges) as courier_charges ,
                                count(a.pick_pack_id) as pick_cnt
                                from crit_pick_pack_info as a
                                where (a.`status` = 'Packed' || a.status = 'Delivered') 
                                and DATE_FORMAT(a.paid_date,'%Y') != '0000'
                                group by DATE_FORMAT(a.paid_date,'%d-%m-%Y') 
                                order by DATE_FORMAT(a.paid_date,'%Y%m%d') desc 
                                limit 10
                                )
                    ) as q 
                    group by q.v_month_num
                    order by q.v_month_num desc
                    limit 10  
            ");
              
              
    
            foreach($query->result_array() as $row)
            {
                 
                $data['revenue_per_month_amt'][]  = $row['courier_charges'] ;    
                $data['revenue_per_month'][]  = $row['v_month'];   
                //$data['revenue_per_month'][] = $row;  
            } 
            
        } else {
                $data['revenue_per_day_amt']= 0; 
                $data['revenue_per_day_date']=0;    
                $data['revenue_per_day_date_num']=0; 
                $data['revenue_per_month_amt']=0;    
                $data['revenue_per_month']=0;      
        }
            
            
            $query = $this->db->query("select  
                    a.`status`,  
                    count(a.pickup_id) as cnt 
                    from rh_pickup_info as a 
                    where DATE_FORMAT(a.booked_date ,'%Y%m')  = '". date('Ym') ."'
                    and a.`status` != 'Delete' and a.`status` != 'Cancelled'
                    group by  a.`status`
                    order by  a.`status`
                    "); 
              
                 
            foreach($query->result_array() as $row)
            {
                 
                $data['polar_curr_mon_status'][]  = $row['status'] ;    
                $data['polar_curr_mon_count'][]  = $row['cnt'];    
            }     
            
            
            $query = $this->db->query("select  
                    a.`status`,  
                    count(a.pickup_id) as cnt ,
                    DATE_FORMAT(a.booked_date ,'%b-%Y') as prev_mon                    
                    from rh_pickup_info as a 
                    where DATE_FORMAT(a.booked_date ,'%Y%m')  = DATE_FORMAT(DATE_SUB('". date('Y-m-d') ."', INTERVAL 1 month),'%Y%m')
                    and a.`status` != 'Delete' and a.`status` != 'Cancelled'
                    group by  a.`status`
                    order by  a.`status`
                    "); 
                    
            foreach($query->result_array() as $row)
            {
                 
                $data['polar_prev_mon_status'][]  = $row['status'] ;    
                $data['polar_prev_mon_count'][]  = $row['cnt'];    
                $data['polar_prev_mon'] = $row['prev_mon'];    
            }        
        
        
        $sql = " 
        select 
        DATE_FORMAT(a.date_time,'%d-%m-%Y') as v_date,
        a.ip as v_ip
        from rh_visitor as a 
        group by  DATE_FORMAT(a.date_time,'%d-%m-%Y') ,a.ip   
        ";
            
        $query = $this->db->query($sql); 
        
        $data['total_visitor'] = $query->num_rows();
         
        $sql = " 
        select 
        DATE_FORMAT(a.date_time,'%d-%m-%Y') as v_date,
        a.ip as v_ip
        from rh_visitor as a 
        where DATE_FORMAT(a.date_time,'%Y-%m-%d') = '". date('Y-m-d') ."'
        group by  DATE_FORMAT(a.date_time,'%d-%m-%Y') ,a.ip   
        ";
            
        $query = $this->db->query($sql); 
        
        $data['todays_visitor'] = $query->num_rows();
        
        
        $sql = " 
        select 
            count(a.franchise_enquiry_id) as cnt
            from rh_franchise_enquiry_info as a 
            where DATE_FORMAT(a.franchise_enquiry_date,'%Y-%m-%d') = '". date('Y-m-d') ."'
            group by DATE_FORMAT(a.franchise_enquiry_date,'%d-%m-%Y')  
        ";
            
        $query = $this->db->query($sql); 
        
        $data['todays_franchisee'] = 0;
        
        //$data['todays_franchisee'] = $query->num_rows();
        
        foreach ($query->result_array() as $row)
        {
            $data['todays_franchisee'] = $row['cnt'];     
        }
        
        $sql = " 
         select sum(w.cnt) as cnt from (
            (
            select 
            count(a.pickup_id) as cnt 
            from rh_pickup_info as a 
            where DATE_FORMAT(a.booked_date,'%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.status != 'Delete'
            group by DATE_FORMAT(a.booked_date,'%Y-%m-%d')
            ) union all (
            select 
            count(a.pick_pack_id) as cnt 
            from crit_pick_pack_info as a 
            where DATE_FORMAT(a.book_date,'%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.status != 'Delete'
            group by DATE_FORMAT(a.book_date,'%Y-%m-%d')
            )
         ) as w 
           
        ";
            
        $query = $this->db->query($sql); 
        
        $data['todays_pickup'] = 0;
        
        //echo $data['todays_pickup'] = $query->num_rows();
        foreach ($query->result_array() as $row)
        {
            $data['todays_pickup'] = $row['cnt'];     
        }
         
        
       /* $query = $this->db->query(" 
            select 
            c.state_name as state,
            b.area,
            count(a.pickup_id) as cnt 
            from rh_pickup_info as a
            left join rh_pincode_list as b on b.pincode = a.source_pincode
            left join rh_states_info as c on c.state_code = b.state_code
            where date_format(a.booked_date , '%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.courier_type = 'Domestic'
            and a.status != 'Delete'
            group by b.state_code, b.area
            order by b.state_code, b.area asc 
        "); */
        
        $this->db->query('SET SQL_BIG_SELECTS=1');
        
         $query = $this->db->query(" 
            select 
            b.state_name as state,
            b.district_name as area,
            count(a.pickup_id) as cnt 
            from rh_pickup_info as a
            left join crit_pincode_info as b on b.pincode = a.source_pincode
            where date_format(a.booked_date , '%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.courier_type = 'Domestic'
            and a.status != 'Delete'
            group by b.state_name , b.district_name
            order by b.state_name , b.district_name asc 
        "); 
        
        
        $data['domestic_pick_up_summary'] = array();
        
        foreach ($query->result_array() as $row)
        {
            $data['domestic_pick_up_summary'][] = $row;     
        }
         
       /* $query = $db_api->query(" 
            select 
            c.state_name as state,
            b.area,
            count(a.pickup_id) as cnt 
            from rh_pickup_info as a
            left join rh_pincode_list as b on b.pincode = a.source_pincode
            left join rh_states_info as c on c.state_code = b.state_code
            where date_format(a.booked_date , '%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.courier_type = 'International'
            and a.status != 'Delete'
            group by b.state_code, b.area
            order by b.state_code, b.area asc 
        "); */
        
          $this->db->query('SET SQL_BIG_SELECTS=1');
        
         $query = $this->db->query(" 
            select 
            b.state_name as state,
            b.district_name as area,
            count(a.pickup_id) as cnt 
            from rh_pickup_info as a
            left join crit_pincode_info as b on b.pincode = a.source_pincode
            where date_format(a.booked_date , '%Y-%m-%d') = '". date('Y-m-d') ."'
            and a.courier_type = 'International'
            and a.status != 'Delete'
            group by b.state_name , b.district_name
            order by b.state_name , b.district_name asc 
        "); 
        
        
        
        $data['international_pick_up_summary'] = array();
        
         foreach ($query->result_array() as $row)
        {
            $data['international_pick_up_summary'][] = $row;     
        } 
        
        
         $query = $this->db->query(" 
            select 
            c.state_name as state,
            b.area,
            count(a.pickup_id) as cnt
            from rh_pickup_info as a
            left join rh_pincode_list as b on b.pincode = a.destination_pincode
            left join rh_states_info as c on c.state_code = b.state_code
            where date_format(a.booked_date , '%Y-%m-%d') = '". date('Y-m-d') ."'
            group by b.state_code, b.area
            order by b.state_code, b.area asc 
        ");
        
        foreach ($query->result_array() as $row)
        {
            $data['destination_summary'][] = $row;     
        } 
        
        
         $sql = "select 
                DATE_FORMAT(a.booked_date,'%d-%m-%Y')  as booking_date,
                count(a.pickup_id) as cnt
                from rh_pickup_info as a
                group by DATE_FORMAT(a.booked_date,'%d-%m-%Y') 
                order by DATE_FORMAT(a.booked_date,'%Y%m%d') desc 
                limit 4 
                ";
                
         $query = $this->db->query($sql);        
         

        foreach ($query->result_array() as $row)
        {
            $data['pick_up_count'][] = $row;     
        }
        
          
        
        $query = $this->db->query("select date_format(a.date_time , '%d-%m-%Y') as v_date , count(*) as cnt from rh_visitor as a where 1=1 group by date_format(a.date_time , '%Y-%m-%d') order by date_format(a.date_time , '%Y-%m-%d') desc  LIMIT 4 ");    
         
        $data['dash_visitor_list'] = array();     

        foreach ($query->result_array() as $row)
        {
            $data['dash_visitor_list'][] = $row;     
        } 
        
        $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
        $query = $this->db->query("  
            select 
            a.pickup_id as ref_no,
            b.state_name as src_state,
            lower(b.district_name) as src_city,
            c.state_name as dest_state,
            lower(c.district_name) as  dest_city
            from rh_pickup_info as a
            left join crit_pincode_info as b on b.pincode = a.source_pincode
            left join crit_pincode_info as c on c.pincode = a.destination_pincode
            where a.`status` = 'Picked' and a.courier_type = 'Domestic'
            and a.delivered_date = '". date('Y-m-d') ."'  
            order by a.pickup_id asc 
        ");
         
       $rec_list = array();  
       
       $data['todays_delivery'] = array();
       
       //$cnt = $query->num_rows(); 
       //$rec_list[]  = array(); 
         foreach($query->result_array() as $row)
        {
            $data['todays_delivery'][]  = $row ;
        }  
        
       $query = $this->db->query(" set SQL_BIG_SELECTS=1 ");
         $query = $this->db->query("  
            select 
            a.pickup_id as ref_no,
            b.state_name as src_state,
            lower(b.district_name) as src_city,
            c.state_name as dest_state,
            lower(c.district_name) as  dest_city
            from rh_pickup_info as a
            left join crit_pincode_info  as b on b.pincode = a.source_pincode
            left join  crit_pincode_info as c on c.pincode = a.destination_pincode
            where a.`status` = 'Booked' and a.courier_type = 'Domestic'
            and a.pickup_date = '". date('Y-m-d') ."'  
            order by a.pickup_id asc 
        ");
         
       $rec_list = array();  
       
       $data['todays_pick'] = array();
       
       //$cnt = $query->num_rows(); 
       //$rec_list[]  = array(); 
         foreach($query->result_array() as $row)
        {
            $data['todays_pick'][]  = $row ;
        }  
         
        
        $this->db->close();
        
       /* echo "<pre>";
        print_r($data);
        echo "</pre>";*/
        
	    $this->load->view('dash',$data); 
	}
    
    public function bookmycourier_v2()
    {
        if($this->input->post('courier_type') == 'Domestic') {
            
            list($source_pincode, $source_area) = explode(' - ', $this->input->post('source_pincode'));
            list($destination_pincode, $destination_area) = explode(' - ', $this->input->post('destination_pincode'));
            
            $ins = array(
                        'courier_type' => $this->input->post('courier_type'),
                        'booking_type' => 'Online',
                        'source_pincode' => $source_pincode,
                        'source_pin_area' => ((isset($source_area)) ? $source_area : ''),
                        'destination_pincode' => $destination_pincode,  
                        'destination_pin_area' => ((isset($destination_area)) ? $destination_area : ''),
                        'package_type' => $this->input->post('package_type') ,
                        'package_weight' => $this->input->post('package_weight') ,
                        'package_length' => $this->input->post('length') ,
                        'package_width' => $this->input->post('width') ,
                        'package_height' => $this->input->post('height') ,
                        'transport_mode' => $this->input->post('t_type'),
                        'packing_required' => $this->input->post('packing_required'),
                        'approx_charges' => $this->input->post('approx_charges'), 
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),                         
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'pickup_registered_by' => $this->input->post('pickup_registered_by'),
                        'pickup_schedule_timing' => $this->input->post('pickup_schedule_timing'), 
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'special_instruction' => $this->input->post('special_instruction'),  
                        'booked_date' => date('Y-m-d H:i:s') 
                                             
                ); 
                     
           } else {
            
                list($source_pincode, $source_area) = explode(' - ', $this->input->post('source_pincode_inter'));
                
                $ins = array(
                        'courier_type' => $this->input->post('courier_type'),
                        'booking_type' => 'Online',
                        'source_pincode' => $source_pincode,
                        'source_pin_area' => ((isset($source_area)) ? $source_area : ''),
                        
                        'destination_country' => $this->input->post('destination_country'),
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),  
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'package_type' => $this->input->post('package_type_inter') , 
                        'package_weight_int' => $this->input->post('package_weight_int') ,
                        'package_length' => $this->input->post('length_inter') ,
                        'package_width' => $this->input->post('width_inter') ,
                        'package_height' => $this->input->post('height_inter') ,
                        'packing_required' => $this->input->post('packing_required_int'),
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'transport_mode' => 'Air',
                        'approx_charges' => $this->input->post('approx_charges'), 
                        'pickup_registered_by' => $this->input->post('pickup_registered_by'),
                        'pickup_schedule_timing' => $this->input->post('pickup_schedule_timing'), 
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'special_instruction' => $this->input->post('special_instruction'),  
                        'booked_date' => date('Y-m-d H:i:s')  
                                             
                );
            
           }               
          $this->db->insert('rh_pickup_info', $ins); 
          
          $pickup_id = str_pad($this->db->insert_id(),5,0,STR_PAD_LEFT); 
         
          //echo "Successfully!!! Your Courier has been Booked .<br> Soon Your Package will be Pickup by Our Staff within 24hrs . <br> <i>Booking Ref Number :  " .  $pickup_id . "</i>" ;
          echo " Your courier has been booked successfully!!! <br /> Soon your package will be picked up by our executive within 24 Hrs. <br> <i>Booking Ref Number :  " .  $pickup_id . "</i>" ;
        /* echo "<pre>"; 
         print_r($ins);
         print_r($_POST);
         echo "</pre>";*/
    } 
    
    
    public function bookmycourier($flg = '')
    { 
         
        
          $ins = array(
                        'courier_type' => $this->input->post('courier_type'),
                        'booking_type' => $this->input->post('booking_type'),
                        'source_pincode' => $this->input->post('source_pincode'),
                        'source_pin_area' => $this->input->post('source_pin_area'),
                        'destination_pin_area' => $this->input->post('destination_pin_area'),
                        'sender_name' => $this->input->post('sender_name'),
                        'sender_phone' => $this->input->post('sender_phone'),
                        'sender_address' => $this->input->post('sender_address'),
                        'destination_pincode' => $this->input->post('destination_pincode'),                       
                        'destination_country' => $this->input->post('destination_country'),                       
                        'receiver_name' => $this->input->post('receiver_name'),                       
                        'receiver_phone' => $this->input->post('receiver_phone') ,
                        'receiver_address' => $this->input->post('receiver_address') ,
                        'package_type' => $this->input->post('package_type') ,
                        'package_weight' => $this->input->post('package_weight') ,
                        'package_weight_int' => $this->input->post('package_weight_int') ,
                        'package_length' => $this->input->post('package_length') ,
                        'package_width' => $this->input->post('package_width') ,
                        'package_height' => $this->input->post('package_height') ,
                        'package_purpose' => $this->input->post('package_purpose') ,
                        'package_value' => $this->input->post('package_value') ,
                        'remarks' => $this->input->post('remarks') ,
                        'same_as_sender_address' => $this->input->post('same_as_sender_address') ,
                        'contact_person_name' => $this->input->post('contact_person_name') ,
                        'contact_person_mobile' => $this->input->post('contact_person_mobile') ,
                        'pickup_address' => $this->input->post('pickup_address'),
                        'approx_charges' => $this->input->post('approx_charges'),
                        'transport_mode' => $this->input->post('transport_mode'),
                        'packing_required' => $this->input->post('packing_required'),
                        'special_instruction' => $this->input->post('special_instruction'),
                        'pickup_schedule_timing' => $this->input->post('pickup_schedule_timing'), 
                        'assign_to' => $this->input->post('assign_to'),
                        'pickup_registered_by' => $this->input->post('pickup_registered_by'),
                        'charges_breakup' => $this->input->post('charges_breakup'),
                        'booked_date' => date('Y-m-d H:i:s') 
                                             
                );                
          $this->db->insert('rh_pickup_info', $ins); 
          
          $pickup_id = str_pad($this->db->insert_id(),5,0,STR_PAD_LEFT);
          //$pickup_id = $this->db->insert_id();
          
          if($flg == '')
            echo "Successfully!!! Your Courier has been Booked .<br> Soon Your Package will be Pickup by Our Staff within 24hrs . <br> <i>Booking Ref Number :  " .  $pickup_id . "</i>" ;
          
          
          
          $msg  = $this->get_content('pickup_info', $pickup_id);
         
       
            $this->load->library('email');
                
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail'; 
            
            $this->email->initialize($config);
    
            $this->email->from('it@pickmycourier.com', 'No-reply');
            $this->email->to('sm@pickmycourier.com');
            $this->email->cc('it@pickmycourier.com ,  marketing@pickmycourier.com');
            $this->email->bcc('operations@pickmycourier.com');
            
            $this->email->subject('Pick My Courier - New Booking');
            $this->email->message($msg);
            
            $this->email->send();
          
          
          /*$this->load->model('RH_model', 'rh_model');
          
          $msg = "Hello ". $this->input->post('sender_name') ." \n Your Courier has been Booked .Your Package will be Pickup by Our Staff. \n Your Booking Ref Number :  " .  $pickup_id . "\n Thanks for Booking ur Courier in Pickmycourier.com ";         
          $this->rh_model->send_sms($this->input->post('sender_phone'),$msg);*/
          
          //print_r($this->get_pincode_state_district('636001'));
          /*$source = $this->get_pincode_state_district($this->input->post('source_pincode'));
          if($this->input->post('courier_type') == 'Domestic')
            $destination = $this->get_pincode_state_district($this->input->post('destination_pincode'));*/
          
          //$sms_msg = $this->input->post('courier_type') . " Pickup\n ";
          if($this->input->post('package_type')== 'Document')
            $package_type = "Dox";
          elseif($this->input->post('package_type')== 'Non-Document')
            $package_type = "N-Dox"; 
          else
            $package_type = $this->input->post('package_type');   
          
          
		  
		  
		  
		  
		  
         /* $sms_msg  = "PMCL - New Booking\n";
          $sms_msg .=  "Reference ID:". $pickup_id . "\n";
          $sms_msg .= $package_type . ":". $this->input->post('package_weight') . "Kg\n ";
          $sms_msg .= "Reg by:". $this->input->post('pickup_registered_by') . "\n "; */
		  
		  
		  $sms_msg  = "PMCL - New Booking\n";
		  $sms_msg .= "Reference ID:" . $pickup_id ."\n";
		  $sms_msg .= $package_type." : ".$this->input->post('package_weight')."kg\n";
		  $sms_msg .= "Reg by: ". $this->input->post('pickup_registered_by')."\n";
		  
		   if($this->input->post('pickup_registered_by') == 'Sender') {
			$sms_msg .= $this->input->post('sender_name')."\n" ;
			$sms_msg .= $this->input->post('sender_phone')."\n";
			$reg_mobile = $this->input->post('sender_phone');
			$reg_name = $this->input->post('sender_name');
		   }  else  {
			$sms_msg .= $this->input->post('receiver_name')."\n" ;
			$sms_msg .= $this->input->post('receiver_phone')."\n" ;
			$reg_mobile = $this->input->post('receiver_phone');
			$reg_name = $this->input->post('receiver_name');
           }
          
          if($this->input->post('courier_type') == 'Domestic') { 
           
              $sms_msg .= "O:" . $this->input->post('source_pincode')."\n"  ;  
              $sms_msg .= "D:" . $this->input->post('destination_pincode')."\n" ;  
          }
          else { 
              $sms_msg .= "O:" . $this->input->post('source_pincode')."\n" ;
              $sms_msg .= "D:-\n" ;
          }
          
         
          
          //$this->send_sms('9952823214',$sms_msg);
          
          //$this->send_sms('6374711150,6374711160',$sms_msg);
          
          //$this->send_sms('6374711150',$sms_msg,'1507163003183201481');
          $this->send_sms('9952823214',$sms_msg,'1507163003183201481');
          
          
          //$c_msg = "Dear ". $this->input->post('sender_name') .", \nYour Courier has been booked successfully in PICK MY COUIRER. \n Your Booking Ref No# " .  $pickup_id . ".\n Thanks for choosing Us!\n If any Query +91 6374711150";         
          /*$c_msg = "Dear ". $this->input->post('sender_name') .", \nYour Courier is registered successfully!!.\n Our pickup executive will pick your courier in next 24 hours.Your Booking Ref No# " .  $pickup_id . ".\n Thanks for choosing Us!\n If any Query <a href='https://wa.me/916374711150'>+91 6374711150</a>";  */   
		  
		  
         /* if(strlen($this->input->post('sender_phone')) == '10') {
            $c_msg = "Dear ". $this->input->post('sender_name') .", \nYour Courier is registered successfully!.\n Our executive will pick your courier in next 24Hrs. Booking Ref No#" .  $pickup_id . ".\nThanks for choosing Us!\nIf any Query +91 6374711150\n For Whatsapp: https://wa.me/916374711150 ";    
            $this->send_sms($this->input->post('sender_phone'), $c_msg);
          }*/
		  
		  if(strlen($reg_mobile) == '10') {
            //$c_msg = "Dear ". $reg_name .", \nYour Courier is registered successfully!.\n Our executive will pick your courier in next 24Hrs. Booking Ref No#" .  $pickup_id . ".\nThanks for choosing Us!\nIf any Query +91 6374711150\n For Whatsapp: https://wa.me/916374711150 ";    
            $c_msg = "Dear Customer, Thanks for registering with PICKMYCOURIER! your shipment booking ID ". $pickup_id." booked on ".date('d-m-Y H:i:s')." .Soon our team will contact for confirmation.";
			//$this->send_sms($reg_mobile, $c_msg,'1507162910977739350');
          }
          
          //$wa_status = $this->send_whatsapp_msg('PMC-'. $pickup_id ,$this->input->post('sender_phone'),$c_msg);
          
         // print_r($this->input->post()) ;
             
    }
    
    public function call_alert_sms($missed_call)
    {
        ECHO $c_msg = "Pick My Courier Customer Care has missed call from customer ". $missed_call;
		//echo $this->send_sms('6374711150', $c_msg,'1507163368987427729');
    }
    
    
    public function international_service_provider_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'international-service-provider.inc';  
        
        /*$re = $this->get_tracking('101236457',0);
        echo "<pre>";
        print_r($re[0]->ConsignmentDetailsMSTrackList);
        echo "</pre>";
        exit;*/
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'international_service_provider' => $this->input->post('international_service_provider'),
                   // 'additional_info' => $this->input->post('additional_info'),
                    'fsc_percentage' => $this->input->post('fsc_percentage'),
                    'gst_percentage' => $this->input->post('gst_percentage'),
                    'dc_percentage' => $this->input->post('dc_percentage'),
                    'logo_url' => $this->input->post('logo_url'),
                    'tracking_url' => $this->input->post('tracking_url'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_international_service_provider_info', $ins); 
            redirect('international-service-provider-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                   'international_service_provider' => $this->input->post('international_service_provider'),
                   // 'additional_info' => $this->input->post('additional_info'),
                    'fsc_percentage' => $this->input->post('fsc_percentage'),
                    'gst_percentage' => $this->input->post('gst_percentage'),
                    'dc_percentage' => $this->input->post('dc_percentage'),
                    'logo_url' => $this->input->post('logo_url'),
                    'tracking_url' => $this->input->post('tracking_url'),
                    'status' => $this->input->post('status')  ,            
            );
            
            $this->db->where('international_service_provider_id', $this->input->post('international_service_provider_id'));
            $this->db->update('crit_international_service_provider_info', $upd); 
                            
            redirect('international-service-provider-list/' . $this->uri->segment(2, 0)); 
        }
         
        
        $this->load->library('pagination');
        
        $this->db->where('status != ', 'Delete');
        $this->db->from('crit_international_service_provider_info');
        $data['total_records'] = $cnt  = $this->db->count_all_results();
        
        $data['sno'] = $this->uri->segment(2, 0);	
        	
        $config['base_url'] = trim(site_url('international-service-provider-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        // a.status = 'Active'
        
        $sql = "
                select 
                a.international_service_provider_id,
                a.international_service_provider,   
                a.fsc_percentage,   
                a.gst_percentage,   
                a.dc_percentage,   
                a.status        
                from crit_international_service_provider_info as a  
                where status != 'Delete'
                order by a.international_service_provider asc  
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
        
         $data['record_list'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('international-service-provider-list',$data); 
	}
    
    public function zone_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
         
        	    
        $data['js'] = 'zone.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'zone_name' => $this->input->post('zone_name'),
                    'sort' => $this->input->post('sort'),
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_zone_info', $ins); 
            redirect('zone-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'zone_name' => $this->input->post('zone_name'),
                    'sort' => $this->input->post('sort'),
                    'status' => $this->input->post('status'),               
            );
            
            $this->db->where('zone_id', $this->input->post('zone_id'));
            $this->db->update('crit_zone_info', $upd); 
                            
            redirect('zone-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
        $this->db->where('status != ', 'Delete'); 
        $this->db->from('crit_zone_info');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('zone-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
        $sql = "
                select 
                a.zone_id,
                a.zone_name,    
                a.sort,            
                a.status
                from crit_zone_info as a 
                where status != 'Delete'
                order by a.status asc ,a.sort,  a.zone_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('zone-list',$data); 
	}
     public function sp_zone_country_list()
	{
	    if(!$this->session->userdata('m_logged_in'))  redirect();
        
        if($this->session->userdata('m_is_admin') != USER_ADMIN ) 
        {
            echo "<h3 style='color:red;'>Permission Denied</h3>"; exit;
        } 
        	    
        $data['js'] = 'sp-zone-country.inc';  
        
        if($this->input->post('mode') == 'Add')
        {
            $ins = array(
                    'intl_sp_id' => $this->input->post('intl_sp_id'),
                    'zone_id' => $this->input->post('zone_id'),
                    'covid_chrg_per_kg' => $this->input->post('covid_chrg_per_kg'),
                    'country_ids' => implode(',', $this->input->post('country_ids')), 
                    'status' => $this->input->post('status')  ,                          
            );
            
            $this->db->insert('crit_sp_zone_country_info', $ins); 
            redirect('sp-zone-country-list');
        }
        
        if($this->input->post('mode') == 'Edit')
        {
            $upd = array(
                    'intl_sp_id' => $this->input->post('intl_sp_id'),
                    'zone_id' => $this->input->post('zone_id'),
                    'covid_chrg_per_kg' => $this->input->post('covid_chrg_per_kg'),
                    'country_ids' => implode(',', $this->input->post('country_ids')), 
                    'status' => $this->input->post('status')  ,                
            );
            
            $this->db->where('sp_zone_country_id', $this->input->post('sp_zone_country_id'));
            $this->db->update('crit_sp_zone_country_info', $upd); 
                            
            redirect('sp-zone-country-list/' . $this->uri->segment(2, 0)); 
        } 
         
        
        $this->load->library('pagination');
        
        
       if(isset($_POST['srch_country_id'])) {
           $data['srch_country_id'] = $srch_country_id = $this->input->post('srch_country_id'); 
           $this->session->set_userdata('srch_country_id', $this->input->post('srch_country_id')); 
       }
       elseif($this->session->userdata('srch_country_id')){
           $data['srch_country_id'] = $srch_country_id = $this->session->userdata('srch_country_id') ; 
       }
       
       if(isset($_POST['srch_intl_sp_id'])) {
           $data['srch_intl_sp_id'] = $srch_intl_sp_id = $this->input->post('srch_intl_sp_id'); 
           $this->session->set_userdata('srch_intl_sp_id', $this->input->post('srch_intl_sp_id')); 
       }
       elseif($this->session->userdata('srch_intl_sp_id')){
           $data['srch_intl_sp_id'] = $srch_intl_sp_id = $this->session->userdata('srch_intl_sp_id') ; 
       } 
        
        
       $where = " 1=1 ";
       if(!empty($srch_country_id)){
        $where .= " and FIND_IN_SET( '" . $srch_country_id . "', a.country_ids) "; 
         
       } else {
        $this->session->set_userdata('srch_country_id', '');
        $data['srch_country_id'] = $srch_country_id =  ''; 
       }
       
       if(!empty($srch_intl_sp_id)){
        $where .= " and a.intl_sp_id = '" . $srch_intl_sp_id . "'"; 
         
       } else {
        $this->session->set_userdata('srch_intl_sp_id', '');
        $data['srch_intl_sp_id'] = $srch_intl_sp_id =  ''; 
       }
        
        
        $this->db->where('a.status != ', 'Delete'); 
        $this->db->where($where); 
        $this->db->from('crit_sp_zone_country_info as a');         
        $data['total_records'] = $cnt  = $this->db->count_all_results();  
        
        $data['sno'] = $this->uri->segment(2, 0);		
        	
        $config['base_url'] = trim(site_url('sp-zone-country-list/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
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
        $config['prev_link'] =  "Prev";
        $config['next_link'] =  "Next";
        $this->pagination->initialize($config);   
        
      $sql = "
                select 
                a.sp_zone_country_id,
                b.international_service_provider,
                c.zone_name,  
                GROUP_CONCAT(d.country_name) as country, 
                a.covid_chrg_per_kg,
                a.status
                from crit_sp_zone_country_info as a 
                left join crit_international_service_provider_info as b on b.international_service_provider_id = a.intl_sp_id
                left join crit_zone_info as c on c.zone_id = a.zone_id 
                left join rh_country_info as d on FIND_IN_SET(d.country_id ,  a.country_ids) 
                where a.status != 'Delete' and $where
                group by a.sp_zone_country_id 
                order by a.status asc ,b.international_service_provider , c.zone_name asc 
                limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."                
        ";
        
        //a.status = 'Booked'  
        
        $query = $this->db->query($sql);
       
        $data['record_list'] = array();
        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        }
        
        $sql = "
                select 
                a.zone_id,              
                a.zone_name              
                from crit_zone_info as a  
                where status = 'Active'
                order by a.sort, a.zone_name asc                 
        "; 
        
        $query = $this->db->query($sql);
       
        foreach ($query->result_array() as $row)
        {
            $data['zone_opt'][$row['zone_id']] = $row['zone_name'];     
        }
        
        $sql = "
                select 
                a.country_id,              
                a.country_name              
                from rh_country_info as a  
                where status = 'Active'
                order by  a.country_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['country_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['country_opt'][$row['country_id']] = $row['country_name'];     
        }
        
        $sql = "
                select 
                a.zone_id,              
                a.zone_name              
                from crit_zone_info as a  
                where status = 'Active'
                order by a.sort, a.zone_name asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['zone_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['zone_opt'][$row['zone_id']] = $row['zone_name'];     
        }
        
        $sql = "
                select 
                a.international_service_provider_id,              
                a.international_service_provider              
                from crit_international_service_provider_info as a  
                where status = 'Active'
                order by  a.international_service_provider asc                 
        "; 
        
        $query = $this->db->query($sql);
        
        $data['service_provider_opt'] = array();
       
        foreach ($query->result_array() as $row)
        {
            $data['service_provider_opt'][$row['international_service_provider_id']] = $row['international_service_provider'];     
        }
        
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('sp-zone-country-list',$data); 
	} 
    
    public function day_report()
	{
	   if(!$this->session->userdata('zazu_logged_in'))  redirect();
       
        //echo 'sdfsdf'. $this->uri->segment(1,0); exit;
       
        $data['js'] = 'chart.inc';
        
        $data['from_date'] = $from_date = $this->input->post('from_date') ;
        
        if(empty($from_date)) {
            $data['from_date'] = $from_date = date('Y-m-d');
        }
         
        
        $sql = " 
            select
            a.chit_group_name as chit_group,
            DATE_FORMAT(concat(b.chit_month,'-01'), '%M-%Y')  as chit_month ,  
            sum(b.month_chit_amount) as chit_amt,
            ifnull(c.collection_amount,0) as collection_amount,
            ( (sum(b.month_chit_amount) ) - ifnull(c.collection_amount,0) ) as outstanding 
            from sm_chit_group_info as a
            left join sm_chit_group_dividend_info as b on b.chit_group_id = a.chit_group_id
            left join sm_chit_group_member_info as d on d.chit_group_id = a.chit_group_id  and d.status = 'Active'
            left join sm_customer_info as e on e.customer_id = d.customer_id
            left join ( select z.chit_group_id , z.chit_group_dividend_id , z.customer_id, sum(z.collection_amount) as collection_amount  from sm_chit_collection_info as z where z.collection_date between DATE_FORMAT( z.collection_date , '%Y-%m-01') and '". $from_date."'    group by z.chit_group_id , z.chit_group_dividend_id , z.customer_id ) as c on c.chit_group_id = a.chit_group_id and c.chit_group_dividend_id = b.chit_group_dividend_id and c.customer_id = d.customer_id
            where  DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m')  =  DATE_FORMAT( '". $from_date."' , '%Y%m') 
            group by a.chit_group_id , b.chit_group_dividend_id  
            order by  DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m') asc , e.customer_name asc 
        ";
        
        $query = $this->db->query($sql); 
             
     
        foreach($query->result_array() as $row)
        {
             $data['record_list'][] = $row;     
        }  
        
        
         $sql = " 
            select
            a.chit_group_name as chit_group,
            DATE_FORMAT(concat(b.chit_month,'-01'), '%M-%Y')  as chit_month ,  
            sum(b.month_chit_amount) as chit_amt,
            ifnull(c.collection_amount,0) as collection_amount,
            ( (sum(b.month_chit_amount) ) - ifnull(c.collection_amount,0) ) as outstanding 
            from sm_chit_group_info as a
            left join sm_chit_group_dividend_info as b on b.chit_group_id = a.chit_group_id
            left join sm_chit_group_member_info as d on d.chit_group_id = a.chit_group_id  and d.status = 'Active' 
            left join ( select z.chit_group_id , z.chit_group_dividend_id ,  sum(z.collection_amount) as collection_amount  from sm_chit_collection_info as z where z.collection_date <= '". $from_date ."' group by z.chit_group_id , z.chit_group_dividend_id  ) as c on c.chit_group_id = a.chit_group_id and c.chit_group_dividend_id = b.chit_group_dividend_id  
            where DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m')  <=  DATE_FORMAT( '". $from_date."' , '%Y%m') 
            group by a.chit_group_id , b.chit_group_dividend_id  
            order by a.chit_group_id desc, DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m') asc 
        ";
        
        $query = $this->db->query($sql); 
             
     
        foreach($query->result_array() as $row)
        {
            if($row['outstanding']!= 0)
             $data['outstanding_list'][$row['chit_group']][] = $row;     
        }  
        
        $sql1= "
            select
            a.chit_group_name,
            b.chit_month  as chit_month , 
            e.customer_name as chit_taken_by,
            b.committed_date,
            b.chit_payable,
            c.collection_amount
            from sm_chit_group_info as a
            left join sm_chit_group_dividend_info as b on b.chit_group_id = a.chit_group_id 
            left join sm_customer_info as e on e.customer_id = b.customer_id
            left join ( select z.chit_group_id , z.chit_group_dividend_id ,  sum(z.collection_amount) as collection_amount  from sm_chit_collection_info as z where z.collection_date <= '". $from_date ."' group by z.chit_group_id , z.chit_group_dividend_id  ) as c on c.chit_group_id = a.chit_group_id and c.chit_group_dividend_id = b.chit_group_dividend_id  
            where DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m')  =  DATE_FORMAT( '". $from_date ."' , '%Y%m') 
            order by DATE_FORMAT(concat(b.chit_month,'-01'), '%Y%m') asc
        ";
        
        $query = $this->db->query($sql1); 
             
     
        foreach($query->result_array() as $row)
        {
             $data['committed_list'][] = $row;     
        } 
        
        $sql_op ="
         select   
         (sum(z.cash_in) - sum(z.cash_out)) as opening 
         from 
         (
        
             (
               select 
                1 as sort,
                a.inward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                a.amount as cash_in,
                0 as cash_out
                from cash_inward as a
                left join account_head as b on b.account_head_id = a.account_head_id
                left join sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.inward_date < '". $from_date."' 
                order by a.inward_date asc , a.cash_inward_id 
             ) union all (
                select 
                2 as sort,
                a.collection_date as t_date,
                concat(d.customer_name ,' : ', b.chit_group_name , ' - ', c.chit_month, ' <br> ' , a.remarks) as particular,
                a.collection_amount as cash_in,
                0 as cash_out
                from sm_chit_collection_info as a
                left join sm_chit_group_info as b on b.chit_group_id = a.chit_group_id
                left join sm_chit_group_dividend_info as c on c.chit_group_dividend_id = a.chit_group_dividend_id
                left join sm_customer_info as d on d.customer_id = a.customer_id
                where a.collection_date < '". $from_date."' 
                order by a.collection_date asc , a.chit_collection_id asc          
              ) union all (          
                select 
                3 as sort,
                a.payment_date as t_date,
                concat(d.customer_name ,' : ', b.chit_group_name , ' - ', c.chit_month, ' <br> ' , a.remarks) as particular,
                0 as cash_in,
                a.amount as cash_out 
                from sm_chit_payment_info as a
                left join sm_chit_group_dividend_info as c on c.chit_group_dividend_id = a.chit_group_dividend_id
                left join sm_chit_group_info as b on b.chit_group_id = c.chit_group_id
                left join sm_customer_info as d on d.customer_id = a.chit_taken_by
                where a.payment_date < '". $from_date."' 
                order by a.payment_date asc , a.chit_payment_id asc 
              ) union all (
                select 
                4 as sort,
                a.outward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                0 as cash_in,
                a.amount as cash_out
                from cash_outward as a
                left join account_head as b on b.account_head_id = a.account_head_id
                left join sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.outward_date < '". $from_date."' 
                order by a.outward_date asc , a.cash_outward_id 
              )
          ) as z
                        
        ";
        
        $query = $this->db->query($sql_op); 
             
     
        foreach($query->result_array() as $row)
        {
             $data['day_opening'] = $row;     
        } 
        
        $sql_op ="
         select   
         sum(z.cash_in) inward ,
         sum(z.cash_out) as outward 
         from 
         (
        
             (
               select 
                1 as sort,
                a.inward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                a.amount as cash_in,
                0 as cash_out
                from cash_inward as a
                left join account_head as b on b.account_head_id = a.account_head_id
                left join sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.inward_date = '". $from_date."' 
                order by a.inward_date asc , a.cash_inward_id 
             ) union all (
                select 
                2 as sort,
                a.collection_date as t_date,
                concat(d.customer_name ,' : ', b.chit_group_name , ' - ', c.chit_month, ' <br> ' , a.remarks) as particular,
                a.collection_amount as cash_in,
                0 as cash_out
                from sm_chit_collection_info as a
                left join sm_chit_group_info as b on b.chit_group_id = a.chit_group_id
                left join sm_chit_group_dividend_info as c on c.chit_group_dividend_id = a.chit_group_dividend_id
                left join sm_customer_info as d on d.customer_id = a.customer_id
                where a.collection_date = '". $from_date."' 
                order by a.collection_date asc , a.chit_collection_id asc          
              ) union all (          
                select 
                3 as sort,
                a.payment_date as t_date,
                concat(d.customer_name ,' : ', b.chit_group_name , ' - ', c.chit_month, ' <br> ' , a.remarks) as particular,
                0 as cash_in,
                a.amount as cash_out 
                from sm_chit_payment_info as a
                left join sm_chit_group_dividend_info as c on c.chit_group_dividend_id = a.chit_group_dividend_id
                left join sm_chit_group_info as b on b.chit_group_id = c.chit_group_id
                left join sm_customer_info as d on d.customer_id = a.chit_taken_by
                where a.payment_date = '". $from_date."' 
                order by a.payment_date asc , a.chit_payment_id asc 
              ) union all (
                select 
                4 as sort,
                a.outward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                0 as cash_in,
                a.amount as cash_out
                from cash_outward as a
                left join account_head as b on b.account_head_id = a.account_head_id
                left join sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.outward_date = '". $from_date."' 
                order by a.outward_date asc , a.cash_outward_id 
              )
          ) as z
                        
        ";
        
        $query = $this->db->query($sql_op); 
             
     
        foreach($query->result_array() as $row)
        {
             $data['in_out'] = $row;     
        } 
       
		$this->load->view('day-report' , $data);
	}
    
    public function account_head_list()
	{
	   if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
       if($this->input->post('mode') == 'Add' && $this->input->post('account_head_name') != '')
        {
            $ins = array(
                    'account_head_name' => $this->input->post('account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status')                      
            );
            
            $this->db->insert('crit_account_head', $ins);
            
            redirect('account-head');
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('account_head_name') != '')
        {
            $upd = array(
                     'account_head_name' => $this->input->post('account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status')                     
            );
            
            $this->db->where('account_head_id', $this->input->post('account_head_id'));
            $this->db->update('crit_account_head', $upd);  
            
            redirect('account-head');
        }
       
       $data['js'] = 'account.inc';
       
       //echo $this->uri->segment(2, 0);
       $this->load->library('pagination');
       
        $cnt  = $this->db->count_all_results('crit_account_head');	
        $data['total_records'] = $cnt;
        	
        $config['base_url'] = trim(site_url('account-head/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);   
       
        $query = $this->db->query(" 
            select 
            a.account_head_id,
            a.account_head_name,
            a.type, 
            a.status
            from crit_account_head as a 
            order by a.status, a.account_head_name asc 
            limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
        ");
         
         
        $data['record_list'] = array(); 

        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $data['pagination'] = $this->pagination->create_links();
       
		$this->load->view('account-head',$data);
	}
    
    public function sub_account_head_list()
	{
	   if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
       if($this->input->post('mode') == 'Add' && $this->input->post('account_head_id') != '')
        {
            $ins = array(
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_name' => $this->input->post('sub_account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status')                      
            );
            
            $this->db->insert('crit_sub_account_head', $ins);
            
            redirect('sub-account-head');
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('account_head_id') != '')
        {
            $upd = array(
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_name' => $this->input->post('sub_account_head_name'),
                    'type' => $this->input->post('type'),
                    'status' => $this->input->post('status')                     
            );
            
            $this->db->where('sub_account_head_id', $this->input->post('sub_account_head_id'));
            $this->db->update('crit_sub_account_head', $upd);  
            
            redirect('sub-account-head');
        }
       
       $data['js'] = 'sub-account.inc';
       
       //echo $this->uri->segment(2, 0);
       $this->load->library('pagination');
       
       $data['total_records'] = $cnt  = $this->db->count_all_results('crit_sub_account_head');	
        	
        $config['base_url'] = trim(site_url('sub-account-head/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);   
       
        $query = $this->db->query(" 
            select 
            a.sub_account_head_id,
            b.account_head_name as account_head,
            a.sub_account_head_name,
            a.type, 
            a.status
            from crit_sub_account_head as a
            left join crit_account_head as b on b.account_head_id = a.account_head_id
            order by a.status, a.sub_account_head_name asc 
            limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
        ");
         
         
        $data['record_list'] = array(); 

        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $data['pagination'] = $this->pagination->create_links();
       
		$this->load->view('sub-account-head',$data);
	}
    
    public function cash_in()
	{
	   if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
       if($this->input->post('mode') == 'Add' && $this->input->post('inward_date') != '')
        {
            $ins = array(
                    'inward_date' => $this->input->post('inward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks')                      
            );
            
            $this->db->insert('crit_cash_inward', $ins);
            
            redirect('cash-in');
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('inward_date') != '')
        {
            $upd = array(
                    'inward_date' => $this->input->post('inward_date'),
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'remarks' => $this->input->post('remarks')                   
            );
            
            $this->db->where('cash_inward_id', $this->input->post('cash_inward_id'));
            $this->db->update('crit_cash_inward', $upd);  
            
            redirect('cash-in');
        }
       
       $data['js'] = 'cash_inward.inc';
       
       //echo $this->uri->segment(2, 0);
       $this->load->library('pagination');
       
       $data['total_records'] = $cnt  = $this->db->count_all_results('crit_cash_inward');	
        	
        $config['base_url'] = trim(site_url('cash-in/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);   
       
        $query = $this->db->query(" 
                select 
                a.cash_inward_id,
                a.inward_date,
                b.account_head_name,
                c.sub_account_head_name,
                a.amount,
                a.remarks  
                from crit_cash_inward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id = a.sub_account_head_id 
                order by a.inward_date desc , a.cash_inward_id desc 
            limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
        ");
         
         
        $data['record_list'] = array(); 

        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $query = $this->db->query("select  a.account_head_id as id, a.account_head_name as value from crit_account_head as a where a.type = 'Inward' and a.status='Active' order by a.account_head_name asc ");
        
        $data['account_head_opt'] = array('Select Account Head');
        
        foreach ($query->result_array() as $row)
        {
         $data['account_head_opt'][$row['id']] = $row['value'] ;    
        }  
        
        $data['pagination'] = $this->pagination->create_links();
       
		$this->load->view('cash-inward',$data);
	}
 
    public function cash_out()
	{
	   if(!$this->session->userdata('m_logged_in'))  redirect(); 
        
       if($this->input->post('mode') == 'Add' && $this->input->post('outward_date') != '')
        {
            $ins = array(
                    'outward_date' => $this->input->post('outward_date'), 
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'cash_received_by' => $this->input->post('cash_received_by'),
                    'remarks' => $this->input->post('remarks')                      
            );
            
            $this->db->insert('crit_cash_outward', $ins);
            
            redirect('cash-out');
        }
        
        if($this->input->post('mode') == 'Edit' && $this->input->post('outward_date') != '')
        {
            $upd = array(
                    'outward_date' => $this->input->post('outward_date'), 
                    'account_head_id' => $this->input->post('account_head_id'),
                    'sub_account_head_id' => $this->input->post('sub_account_head_id'),
                    'amount' => $this->input->post('amount'),
                    'cash_received_by' => $this->input->post('cash_received_by'),
                    'remarks' => $this->input->post('remarks')                  
            );
            
            $this->db->where('cash_outward_id', $this->input->post('cash_outward_id'));
            $this->db->update('crit_cash_outward', $upd);  
            
            redirect('cash-out');
        }
       
       $data['js'] = 'cash_outward.inc';
       
        
       
       //echo $this->uri->segment(2, 0);
       $this->load->library('pagination');
       
        $data['total_records'] = $cnt  = $this->db->count_all_results('crit_cash_outward');	
        	
        $config['base_url'] = trim(site_url('cash-out/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 10;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);   
       
        $query = $this->db->query(" 
                select 
                a.cash_outward_id, 
                a.outward_date,
                b.account_head_name,
                c.sub_account_head_name,
                a.amount,
                a.cash_received_by,
                a.remarks  
                from crit_cash_outward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id = a.sub_account_head_id  
                order by a.outward_date desc , a.cash_outward_id desc 
            limit ". $this->uri->segment(2, 0) .",". $config['per_page'] ."
        ");
         
         
        $data['record_list'] = array(); 

        foreach ($query->result_array() as $row)
        {
            $data['record_list'][] = $row;     
        } 
        
        $query = $this->db->query("select  a.account_head_id as id, a.account_head_name as value from crit_account_head as a where a.type = 'Outward' and a.status='Active' order by a.account_head_name asc ");
        
        $data['account_head_opt'] = array('Select Account Head');
        
        foreach ($query->result_array() as $row)
        {
         $data['account_head_opt'][$row['id']] = $row['value'] ;    
        }  
        
        $data['pagination'] = $this->pagination->create_links();
       
		$this->load->view('cash-outward',$data);
	}
    
    public function cash_ledger()
    {
       if(!$this->session->userdata('m_logged_in'))  redirect();   
        
       $data['from_date'] = $from_date = $this->input->post('from_date');
       $data['to_date'] = $to_date = $this->input->post('to_date');
       
       /*if($this->input->post('from_date') == '')  
       {
          $data['from_date'] = $from_date = $this->session->userdata('from_date');
          $data['to_date'] = $to_date = $this->session->userdata('to_date');
       }
       else
       {
          $this->session->set_userdata('from_date', $this->input->post('from_date'));
          $this->session->set_userdata('to_date', $this->input->post('to_date'));
          
          
       }*/
       
       if(empty($from_date)) 
       {
         $data['from_date'] =  $from_date = date('Y-m-d');
         $data['to_date'] =  $to_date = date('Y-m-d');
       }
       
      // echo $from_date; exit;
        
       /* $this->load->library('pagination');
       
         
        $this->db->where("collection_date between '". $from_date ."' and  '". $to_date."'");            
        $this->db->from('sm_chit_collection_info');    
        $data['records'] = $cnt  = $this->db->count_all_results();	
       	
        $config['base_url'] = trim(site_url('chit-collection/'), '/'. $this->uri->segment(2, 0));
        $config['total_rows'] = $cnt;
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        //$config['num_links'] = 2; 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);   */
        
       $sql_op ="
         select 
         '". $from_date ."' as t_date,
         'Opening Balance' as particular,
         (sum(z.cash_in) - sum(z.cash_out)) as cash_in,
         0 as cash_out
         from 
         (
        
             (
               select 
                1 as sort,
                a.inward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                a.amount as cash_in,
                0 as cash_out
                from crit_cash_inward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.inward_date < '". $from_date."' 
                order by a.inward_date asc , a.cash_inward_id 
             )   union all (
                select 
                4 as sort,
                a.outward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                0 as cash_in,
                a.amount as cash_out
                from crit_cash_outward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.outward_date < '". $from_date."' 
                order by a.outward_date asc , a.cash_outward_id 
              )
          ) as z
                        
        ";
        
        
        $sql_tr ="
         select 
         z.t_date,
         z.particular,
         (z.cash_in) as cash_in,
         (z.cash_out) as cash_out
         from 
         (
        
             (
               select 
                1 as sort,
                a.inward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                a.amount as cash_in,
                0 as cash_out
                from crit_cash_inward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.inward_date between '". $from_date."' and  '". $to_date."'
                order by a.inward_date asc , a.cash_inward_id 
             ) union all (
                select 
                4 as sort,
                a.outward_date as t_date,
                CONCAT(b.account_head_name,' - ', c.sub_account_head_name , '<br>', a.remarks ) as particular,
                0 as cash_in,
                a.amount as cash_out
                from crit_cash_outward as a
                left join crit_account_head as b on b.account_head_id = a.account_head_id
                left join crit_sub_account_head as c on c.sub_account_head_id  = a.sub_account_head_id
                where a.outward_date between '". $from_date."' and  '". $to_date."'
                order by a.outward_date asc , a.cash_outward_id 
              )
          ) as z
          order by z.t_date asc , z.sort asc             
        ";
        
        
        $sql = "
        select 
         q.t_date,
         q.particular,
         (q.cash_in) as cash_in,
         (q.cash_out) as cash_out
         from (
                (". $sql_op.") union all (". $sql_tr.") 
              ) as q
         order by q.t_date asc      
            ";
        
        $query = $this->db->query($sql);
         
       $data['record_list'] = array();  

        foreach ($query->result_array() as $row)
        {
           //$data['record_list'][$row['collection_date']][] = $row;     
           $data['record_list'][] = $row;     
        } 
        
       
		$this->load->view('cash-ledger',$data);
    }
    
    
    public function profit_loss_report()
	{
	   if(!$this->session->userdata('zazu_logged_in'))  redirect();
       
        //echo 'sdfsdf'. $this->uri->segment(1,0); exit;
        
        $query = $this->db->query("select  a.chit_group_id , a.chit_group_name from sm_chit_group_info as a where 1=1 order by a.end_date desc ");
        
        $data['chit_opt'] = array('' => 'Select Chit Group');
        
        foreach ($query->result_array() as $row)
        {
         $data['chit_opt'][$row['chit_group_id']] = $row['chit_group_name'] ;    
        } 
       
        $data['js'] = 'profit.inc';
        
        $data['chit_group_id'] = $chit_group_id = $this->input->post('chit_group_id') ;
        
        if(empty($chit_group_id)) {
            $data['chit_group_id'] = $chit_group_id =  2;
        }
         
        
        $sql = " 
            select 
            a.chit_group_name,
            a.chit_group_members as no_of_months,
            a.chit_amount,
            a.start_date,
            a.end_date ,
            (if('". date('Y-m-d') ."' < a.end_date, PERIOD_DIFF(DATE_FORMAT('". date('Y-m-d') ."','%Y%m'),DATE_FORMAT(a.start_date,'%Y%m')), PERIOD_DIFF(DATE_FORMAT(a.end_date,'%Y%m'),DATE_FORMAT(a.start_date,'%Y%m'))) + 1 ) as curr_no_month,
            c.start_coll,
            c.last_coll,
            (a.chit_group_members * b.tot_chit_amount ) as net_chit_amount,
            c.tot_collection_amount as net_collection_amount ,
            b.tot_paid_amount as net_paid_amount ,
            (a.chit_amount * a.chit_group_members * b.commission /100) as tot_commission_amt1, 
            (a.chit_amount * (if('". date('Y-m-d') ."' < a.end_date, PERIOD_DIFF(DATE_FORMAT('". date('Y-m-d') ."','%Y%m'),DATE_FORMAT(a.start_date,'%Y%m')), PERIOD_DIFF(DATE_FORMAT(a.end_date,'%Y%m'),DATE_FORMAT(a.start_date,'%Y%m'))) + 1 ) * b.commission /100) as tot_commission_amt, 
            ((a.chit_group_members * b.tot_chit_amount ) - c.tot_collection_amount ) as chit_outstanding,
            d.exp_amount,
            e.loss_amount,
            b.tot_chit_payable,
            (((a.chit_group_members * b.tot_chit_amount ) - b.tot_paid_amount ) + (a.chit_amount * a.chit_group_members * b.commission /100)) as pl
            from sm_chit_group_info as a
            left join ( select w.chit_group_id , sum(w.month_chit_amount) as tot_chit_amount , sum(w1.amount) as tot_paid_amount, w.commission , sum(w.chit_payable )  as tot_chit_payable  from sm_chit_group_dividend_info as w left join sm_chit_payment_info as w1 on w1.chit_group_dividend_id = w.chit_group_dividend_id where DATE_FORMAT(concat(w.chit_month,'-01'),'%Y%m') <= DATE_FORMAT('". date('Y-m-d') ."','%Y%m') group by w.chit_group_id ) as b on b.chit_group_id = a.chit_group_id
            left join ( select p.chit_group_id,sum(p.collection_amount) as tot_collection_amount, min(p.collection_date) as start_coll, max(p.collection_date) as last_coll from sm_chit_collection_info as p group by p.chit_group_id ) as c on c.chit_group_id = a.chit_group_id
            left join ( select s.chit_group_id , sum(s.amount) as exp_amount from cash_outward as s where s.account_head_id not in (1) group by s.chit_group_id ) as d on d.chit_group_id = a.chit_group_id
            left join ( select u.chit_group_id , sum(u.amount) as loss_amount from sm_loss_account_info as u where 1=1  group by u.chit_group_id ) as e on e.chit_group_id = a.chit_group_id
            where a.chit_group_id = '". $chit_group_id."'
            group by a.chit_group_id
            order by a.chit_group_id desc

        ";
        
        $query = $this->db->query($sql); 
             
     
        foreach($query->result_array() as $row)
        {
             $data['record_list'][] = $row;     
        }  
        
        
       
		$this->load->view('profit-loss-report' , $data);
	}
    
    private function send_sms_old($sms_mobile, $smstext)
    {
       //if(!$this->session->userdata('m_logged_in'))  redirect();  
        
        $username="PMC_tamil";

        //$password="sts12345@";
        $password="7798993";
        
        $sender="PKMYCR";   
        
        

        $this->load->library('curl');

  
        $ack = 0; 
            
        $ack = $this->curl->simple_get("http://login.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($sms_mobile)."&message=".urlencode($smstext)."&sender=".urlencode($sender)."&type=".urlencode('3'));
          
        $val = json_decode($ack,true); 
        
        return $val['status'];
        
        
    }
	
	private function send_sms($sms_mobile, $smstext,$template_id)
    {
       //if(!$this->session->userdata('m_logged_in'))  redirect();  
        
        $username="PMC_tamil";

        //$password="sts12345@";
        $password="7798993";
        
        $sender="PKMYCR";   
        
        

        $this->load->library('curl');

  
        $ack = 0; 
            
        //$ack = $this->curl->simple_get("http://login.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($sms_mobile)."&message=".urlencode($smstext)."&sender=".urlencode($sender)."&type=".urlencode('3'));
		
		$url="http://api.bulksmsgateway.in/sendmessage.php?user=". urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($sms_mobile)."&message=".urlencode($smstext)."&sender=".urlencode($sender)."&type=".urlencode('3')."&template_id=".urlencode($template_id);
		
        $ack = $this->curl->simple_get($url);
          
        $val = json_decode($ack,true); 
        
        return $val['status'];
        
        
    }
    
    public function get_tracking($awb, $ref_no = 0)
    {
       
        $_param= array(
           "ConsignmentNo" => $awb,
           "CompanyId" => 1,
           "CustomerRefNo" => $ref_no,
           "ConsignmentYear" => 0
        );
        
        $postData = '';
        //create name value pairs seperated by &
        foreach($_param as $k => $v) 
        { 
          $postData .= $k . '='.$v.'&'; 
        }
        rtrim($postData, '&');
        
        $url =  "http://cmswebapi.elbextrack.in/V2/TrackConsignmentNoForListV2/";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

        $output=curl_exec($ch);

        curl_close($ch);

       // return json_decode($output);
        $result = json_decode($output);
        //$content =  "<pre>" . print_r($result)."</pre>";  exit;
        
        $content = '<h3>Tracking Information</h3>';
        if($result[0]->IsError == 'false') {
            
            if(isset($result[0]->CustomerName ) and $result[0]->CustomerName != '') {
                $content .= '
                   <table class="table table-bordered table-responsive table-striped">
                     <thead>
                        <tr>
                            <th>Consignor Details</th>
                            <th>Consignee Details</th>
                     </thead>  
                     <tbody> 
                        <tr>
                            <td>
                            '. $result[0]->CustomerName.'<br>
                            '. $result[0]->CustomerAddress.'<br>
                            '. $result[0]->CustomerCity.'<br>
                            </td>
                            <td>
                            '. $result[0]->ConsigneeName.'<br>
                            '. $result[0]->ConsigneeAddress.'<br>
                            '. $result[0]->ConsigneeCity.'<br>
                            </td>
                        </tr>
                     </tbody>
                   </table> 
                        ';
            }
            
            $content .= '
                    
                   <table class="table table-bordered table-responsive table-striped" > 
                       
                     <tbody> 
                        <tr>
                            <th>Consignment Number</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->ConsignmentNo.'</td> 
                        </tr>
                        <tr>
                            <th>Booking Date</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->DateofBooking.'</td> 
                        </tr> 
                        <tr>
                            <th>Origin</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->ConsOriginLcn.'</td> 
                        </tr>
                        <tr>
                            <th>Destination</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->ConsDestLcn.'</td> 
                        </tr>
                        <tr>
                            <th>Current Status</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->CurrentStatusName.'</td> 
                        </tr>
                        <tr>
                            <th>Current Location</th>
                            <td>'. $result[0]->ConsignmentDetailsMSTrackList->CurrLocation.'</td> 
                        </tr> 
                     </tbody>
                   </table> 
                        ';
                  $content .= '
                    <h3>Tracking History</h3>
                   <table class="table table-bordered table-responsive table-striped">
                     <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                            <th>Status</th>
                     </thead>  
                     <tbody>';
                      foreach($result[0]->ConsignmentHistoryMSTrackList as $i => $history){
                        $content .= '   
                            <tr>
                                <td> '. ($i+1) .'</td> 
                                <td> '. $history->CurrentStatusDate.'</td> 
                                <td> '. $history->DispatchedOrReceivedLocation.'</td> 
                                <td> '. $history->StatusName.'</td> 
                            </tr>';
                      }
                  $content .= '      
                     </tbody>
                   </table> 
                        ';  
                        
                if(!empty($result[0]->PODImageMSTrack)) {          
                    $content .= "<h3>Way Bill Image</h3>";        
                    $content .= "<a href='". $result[0]->PODImageMSTrack."' target='_blank'><img src='". $result[0]->PODImageMSTrack."' alt='' class='img-responsive img-rounded' ></a>";        
                }       
        
        } else {
            $content .= '
                   <table class="table table-responsive"> 
                     <tbody> 
                        <tr>
                            <td style="color:red;">
                            '. $result[0]->Message.' 
                            </td> 
                        </tr>
                     </tbody>
                   </table> 
                        ';
        }
        
        echo $content;
         
        
    }
    
    // 
    
    public function get_location()
    {
        if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            
            //$url =  'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=true&key=AIzaSyB5NiRM2WjaICCn5b3uyZRvA2vBxDMbZjs';
            echo $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false"; 
                $curl_return= $this->curl_get($url);

                $obj=json_decode($curl_return);
                print_r($obj);
                
            
            
            
            //send request and receive json data by latitude and longitude
           // $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
            //$url =  'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=true&key=AIzaSyB5NiRM2WjaICCn5b3uyZRvA2vBxDMbZjs';
            /*$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";  
            $json = @file_get_contents($url);
            $data = json_decode($json);
            $status = $data->status;
            
            //if request status is successful
            if($status == "OK"){
                //get address from json data
                $location = $data->results[0]->formatted_address;
            }else{
                $location =  '';
            }*/
            
           
            //return address to ajax 
            //echo $location;
            //echo $url;
           // print_r($data);
        }
    }
    
    public function get_distance($source_pincode = '', $destination_pincode = '')
    {
        if(empty($source_pincode))
            $source_pincode = $this->input->post('source_pincode');
        if(empty($destination_pincode))     
            $destination_pincode = $this->input->post('destination_pincode'); 
            
       //$distance =  $this->curl_get('http://gstserver.com/tools/ajax-calculate-distance.php',array('o' => $source_pincode,'d'=> $destination_pincode));
       
       $this->load->library('curl'); 
        
       $distance = $this->curl->simple_get("http://gstserver.com/tools/ajax-calculate-distance.php?o=". $source_pincode . "&d=". $destination_pincode); 
       
       $distance = str_replace('h2','span',$distance);    
       $distance = str_replace('Km','Km<br>',$distance);    
       
       $distance = strip_tags($distance ,'<p></p><br>') ;
        
       echo $distance; 
        
       /*
        $.ajax({
               type: "GET",
                url: 'http://gstserver.com/tools/ajax-calculate-distance.php',
                data: {o: origin, d: destination},
                success: function(data){
                    $(".calculateDistance_error").html(data);
                }
        });
        
        */
        
    }
    
    public function get_news_updates()
    { 
        
        
            $query = $this->db->query(" 
                select 
                DATE_FORMAT(a.news_date,'%M') as n_month,
                DATE_FORMAT(a.news_date,'%d') as n_date,
                a.news_heading,
                a.news_content
                from crit_news_info as a
                where a.status = 'Active'
                order by  a.news_date asc 
            ");
             
             $rec_list = '';  
        
            foreach($query->result_array() as $row)
            {
               $rec_list .= '
               <div class="row item">
                  <div class="col-md-3">
                    <div class="current-date">
    					<p>'. $row['n_month'].'</p>
    					<p class="date">'. $row['n_date'].'</p>
    				</div>
                  </div>
                  <div class="col-md-9">
                    <div class="info">
                        <b style="color:#FF6600">'. $row['news_heading'].'</b>
    					<p>'. $row['news_content'].'</p>
    					 
    				</div>
                  </div>
                </div>
                 ';
                   
            }
            
            echo $rec_list;
        
    }
    
    public function get_pincode_state_city($pincode)
    { 
        
       $this->load->library('curl'); 
        
       $distance = $this->curl->simple_get("https://api.postalpincode.in/pincode/". $pincode ); 
       
       echo "<pre>";
       print_r($distance); 
       echo "</pre>";
        
        
    }
    
    private function send_whatsapp_msg($custom_uid , $to , $msg)
    {
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.waboxapp.com/api/send/chat");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "token=c4094b05efd18670fd45fa6b35d22a365da988cc433cc&uid=916374711150&to=$to&custom_uid=$custom_uid&text=". urlencode($msg));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);
        
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close ($ch);
        */
        
       $this->load->library('curl'); 
        
       $info = $this->curl->simple_get("https://www.waboxapp.com/api/send/chat?token=c4094b05efd18670fd45fa6b35d22a365da988cc433cc&uid=916374711150&to=$to&custom_uid=$custom_uid&text=". urlencode($msg)); 
      
        return $info;
    }
    
    function curl_get($url,  array $options = array())
    {
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4
        );
    
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
	
	public function qmail_test()
    { 
          
          
          $franchise_enquiry_id = str_pad('749',5,0,STR_PAD_LEFT);
       
          
          $msg  = $this->get_content('franchise_enquiry', $franchise_enquiry_id);
         
       
            $this->load->library('email');
                
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            
            $this->email->initialize($config);
    
            $this->email->from('herriticqagencies@gmail.com', 'RAPETI H R DURGA PRAVEEN KUMAR');
            //$this->email->to('sm@pickmycourier.com');
            $this->email->to('it@pickmycourier.com');
            //$this->email->cc('it@pickmycourier.com , santhamurthy@elbex.in , marketing@pickmycourier.com');
            $this->email->bcc('selvanramesh@gmail.com');
            
            $this->email->subject('Pick My Courier - New Franchise Enquiry');
            $this->email->message($msg);
            
            if ( ! $this->email->send())
            {
                    echo "Failed"; 
            } else {
                 echo "OK"; 
            } 
           
             
    }
    
    
    public function logout()
	{	 
	    
	    $this->db->insert('crit_user_history_info',array('user_id' => $this->session->userdata('m_user_id') , 'page' => 'Logout' , 'date_time' => date('Y-m-d H:i:s'))) ; 
       
	    $this->session->unset_userdata('m_logged_in');
        $this->session->unset_userdata('m_user_id');
        $this->session->unset_userdata('m_user_name');
        $this->session->unset_userdata('m_mobile');
        $this->session->unset_userdata('m_last_login');
        $this->session->unset_userdata('m_is_admin');
		$this->session->sess_destroy();
	    redirect('', 'refresh');
	}
    
    public function convert_number($number) {
        if (($number < 0) || ($number > 999999999)){
        return "$number out of script range";
        }

      

	  $lakhs = floor($number / 100000);  /* lakhs (giga) */
      $number -= $lakhs * 100000;
      
      $thousands = floor($number / 1000);     /* Thousands (kilo) */
      $number -= $thousands * 1000;
      $hundreds = floor($number / 100);      /* Hundreds (hecto) */
      $number -= $hundreds * 100;
      $tens = floor($number / 10);       /* Tens (deca) */
      $ones = $number % 10;               /* Ones */
      $res = "";
      
      //echo "<hr>".$lakhs;
	 

     if ($lakhs){
	    	//ECHO 'zxcvzcv'. $lakhs;
        $res .= $this->convert_number($lakhs) ;
        //$res.=($lakhs >10) ? " Lakhs":" Lakh";
		$res.=($lakhs>1)?" Lakhs":" Lakh";
      }

	  if($thousands){
        $res .= (empty($res) ? "" : " ") .
        $this->convert_number($thousands) . " Thousand";
      }

	  if ($hundreds){
        $res .= (empty($res) ? "" : " ") .
        $this->convert_number($hundreds) . " Hundred";
      }

	  $arr_ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
      "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
      "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
      "Nineteen");
      $arr_tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "eighty", "Ninety");

    if ($tens || $ones){
        if (!empty($res)){
            $res .= " and ";
        }

        if ($tens < 2){
            $res .= $arr_ones[$tens * 10 + $ones];
        }
        else{
            $res .= $arr_tens[$tens];
            if ($ones){
                $res .= " " . $arr_ones[$ones];
            }
        }
    }

    if (empty($res)){
        $res = "zero";
    }

    return $res; 
    }  
    
}
