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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if($post = $this->input->post())
		{
			$username = $this->security->xss_clean($post['x']);
			$password = $this->security->xss_clean($post['y']);
			$login = $this->LoginModel->admin_login(['x'=>$username,'y'=>$password]);
			if($login){
				redirect(base_url.'Admin');
			}
			else{
				$this->session->set_flashdata('msg','<div class="alert alert-danger">Invalid Login!!</div>');
				redirect(base_url);
			}
		}
		else{
			session_destroy();
			$this->load->view('login');
		}
	}
}
