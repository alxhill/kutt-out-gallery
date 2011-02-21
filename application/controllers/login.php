<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library(array('session','view'));
		$this->load->model('login_model');
	}
	
	function index()
	{
		$this->view->template('login_form')->title('Log in');
		$this->view->load();
	}
	
	function submit()
	{
		$user = $this->input->post('username');
		$pass = $this->input->post('password');
		if (($user == 'boydcape') && ($pass == 'kutt-0ut'))
		{
			$this->session->set_userdata('logged_in', TRUE);
			$this->session->set_userdata('user', $user);
			$this->session->set_flashdata('success', 'You have successfully logged in!');
			redirect('admin');
		}
		else
		{
			$this->session->set_userdata('logged_in', FALSE);
			$this->view->template('login_form')->title('Log in')->message('error','Log in details are incorrect. Please try again.');
			$this->view->load();
		}
		
	}
	
	function logout()
	{
		if ($this->session->userdata('logged_in'))
		{
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('user');
			$this->session->set_flashdata('logout',TRUE);
			redirect('home');
		}
	}
	

}