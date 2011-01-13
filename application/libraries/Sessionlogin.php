<?php
class Session_helper {
	function __construct()
	{
		$CI =& get_instance();
		
		$CI->load->model('login_model');
		$CI->load->library('session');
	}
	
	function log_in($user,$pass)
	{
		if ($CI->login_model->sign_in($user,$pass))
		{
			$login = array('username' => $user, 'logged_in' => TRUE);
			$CI->session->set_userdata($login);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function log_out()
	{
		$destroy = array('username' => '', 'logged_in' => '');
		$CI->session->unset_userdata($destroy);
		return TRUE;
	}

	function is_signed_in()
	{
		return $CI->session->userdata('logged_in') ? $CI->session->userdata('username') : FALSE;
	}
}