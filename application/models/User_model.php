<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function get_by_email($email)
    {
        return $this->db->where('email', $email)->get('users')->row();
    }
    
    public function get_all()
    {
        return $this->db->order_by('name', 'asc')->get('users')->result();
    }
    
    public function insert($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function check_login($username, $password)
    {
        $this->db->where('user_name', $username);
        $this->db->where('status', 'Active');
        $query = $this->db->get('user_login_info');
        $user = $query->row();

        // ✅ Plain text match (since DB passwords are not hashed)
        if ($user && $user->user_pwd === $password) {
            return $user;
        }
        return false;
    }

    public function get_user($id)
    {
        return $this->db->get_where('user_login_info', ['user_id' => $id])->row();
    }
}
