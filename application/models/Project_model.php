<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {
  public function get_all(){
    return $this->db->order_by('created_at','desc')->get('projects')->result();
  }
  public function get($id){
    return $this->db->where('id',$id)->get('projects')->row();
  }
  public function insert($data){
    $data['slug'] = url_title($data['name'],'dash',true);
    $this->db->insert('projects',$data);
    return $this->db->insert_id();
  }
}
