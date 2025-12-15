<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_log_model extends CI_Model {
  public function insert($data){
    $this->db->insert('time_logs',$data);
    return $this->db->insert_id();
  }
  public function sum_minutes_by_project($project_id){
    $this->db->select_sum('time_logs.minutes','total_minutes');
    $this->db->join('tasks','tasks.id = time_logs.task_id');
    $this->db->where('tasks.project_id', $project_id);
    $row = $this->db->get('time_logs')->row();
    return $row->total_minutes ?: 0;
  }
  public function get_by_task($task_id){
    return $this->db->where('task_id',$task_id)->order_by('created_at','desc')->get('time_logs')->result();
  }
}
