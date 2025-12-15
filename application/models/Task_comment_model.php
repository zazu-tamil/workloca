<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_comment_model extends CI_Model {
  public function insert($data){
    $this->db->insert('task_comments',$data);
    return $this->db->insert_id();
  }
  public function get_by_task($task_id){
    return $this->db->where('task_id',$task_id)->order_by('created_at','asc')->get('task_comments')->result();
  }
}
