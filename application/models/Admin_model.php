<?php
class Admin_model extends CI_Model
{
	
	public function add_project($data)
	{
		$this->db->insert('projects',$data);
		mkdir("./uploads/".$data['path']);
	}
	public function get_projects($id=0)
	{
		if($id)
			$this->db->where('id',$id);
		return $this->db->get('projects');
	}
	public function all_users($id=0)
	{
		if($id)
			$this->db->where('id',$id);
		return $this->db->get('login');
	}
}
?>
