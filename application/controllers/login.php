<?php 
if (! defined('BASEPATH')) exit('No direct script access');

/**
 * Manages logging in and out of the system and setting of session data
 * 
 * @package kutt-out-gallery
 * @author Alexander Hill
 * @copyright Copyright (c) 2011 Alexander Hill <http://alxhill.com>
 */
class Login extends CI_Controller {
	
	/**
	 * Constructor - loads stuff
	 */
	function __construct() {
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library(array('session','view'));
		$this->load->library('view');
		$this->view->theme(THEME);
	}
	
	/**
	 * Loads the login page
	 */
	function index()
	{
		$this->view->template('login_form')->title('Log in');
		$this->view->load();
	}
	
	/**
	 * Handles a submit, checks it agains the user and pass and then sets the necessary session data.
	 */
	function submit()
	{
		$user = $this->input->post('username');
		$pass = $this->input->post('password');
		
		// Set the user and pass based on the theme
		$t_u = THEME == 'kutt-out' ? 'boydcape' : 'toby';
		$t_p = THEME == 'kutt-out' ? 'kutt-0ut' : 'pass';
		
		if (($user == $t_u) && ($pass == $t_p))
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
	
	/**
	 * Sets the necessary session data to log the user out.
	 */
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