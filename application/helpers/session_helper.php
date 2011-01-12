<?php
$this->load->model('login_model');
$this->load->library('session');

function log_in($user,$pass)
{
	if ($this->login_model->sign_in($user,$pass))
	{
		$login = array('username' => $user, 'logged_in' => TRUE);
		$this->session->set_userdata($login);
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
	$this->session->unset_userdata($destroy);
	return TRUE;
}

function is_signed_in()
{
	return $this->session->userdata('logged_in') ? $this->session->userdata('username') : FALSE;
}

?>