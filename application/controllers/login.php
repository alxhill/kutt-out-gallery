<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Login extends Controller {

	//php 5 constructor
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library('session');
		$this->load->model('login_model');
	}
	
	function index() {
		$data = array('title' => 'Log in', 'template' => 'login_form');
		$this->load->view('login/superview',$data);
	}
	
	function submit()
	{
		
	}

}