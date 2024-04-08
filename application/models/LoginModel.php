<?php
/**
 * 
 */
class LoginModel extends CI_Model
{
	private $info;
	function __construct()
	{
		parent::__construct();
		$this->load->library('Detect');
		$this->info = new Detect;
	}
	public function admin_login($data)
	{
		$this->db->where($data);
		$res = $this->db->get('login');
		if($res->num_rows()){
			$r = $res->row();
			$data = array(
							'id'=>$r->id,
							'name'=>$r->name,
							'username'=>$r->x,
							'role'=>$r->role,
							'admin_login'=>true,
			);
			$this->session->set_userdata($data);
			$loginInfo = array(
							'login_id'=>$r->id,
							'login_type'=>$r->role,
							'login_time'=>time(),
							'browser'=>$this->info->browser(),
							'device'=>$this->info->systemInfo()['device'],
							'ip_address' =>$this->info->IP(),
						);
			$this->db->insert('notifications',$loginInfo);
			return 1;
		}
		else
		{
			return 0;
		}
	}
}
?>
