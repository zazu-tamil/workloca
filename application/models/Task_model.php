<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model {
  public function get_recent($limit=10){
    return $this->db->order_by('created_at','desc')->limit($limit)->get('tasks')->result();
  }

  public function get_by_project_grouped($project_id){
    $rows = $this->db->where('project_id',$project_id)->order_by('priority','desc')->get('tasks')->result();
    $grouped = ['todo'=>[], 'in_progress'=>[], 'review'=>[], 'done'=>[], 'blocked'=>[]];
    foreach($rows as $r){
      $s = $r->status ?: 'todo';
      if (!isset($grouped[$s])) $grouped[$s] = [];
      $grouped[$s][] = $r;
    }
    return $grouped;
  }

  public function insert($data){
    $this->db->insert('tasks',$data);
    return $this->db->insert_id();
  }

  public function update($id, $data){
    $this->db->where('id',$id)->update('tasks',$data);
  }

  public function get($id){
    return $this->db->where('id',$id)->get('tasks')->row();
  }
}
