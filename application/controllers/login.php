<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library('session', 'sessionlogin');
		$this->load->model('login_model');
	}
	
	function index() {
		$data = array('title' => 'Log in', 'template' => 'login_form');
		$this->load->view('login/superview',$data);
	}
	
	function submit()
	{
		$user = $this->input->post('username');
		$pass = $this->input->post('password');
		if (($user == 'boydcape') && ($pass == 'kutt-0ut'))
		{
			
		}
		else
		{
			$this->load->view('gallery/superview', array('title' => 'Log in', 'template' => 'upload', 'class' => error, 'message' => 'There was a problem logging you in. Please try again.'));
		}
		
	}

}