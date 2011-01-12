<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Login_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	function new_user($user,$pass)
	{
		$q = $this->db->get_where('users', array('username' => $user));
		if ($q->num_rows() > 0)
		{
			return array('success' => FALSE, 'error' => 'User already exists');
		}
		else
		{
			$insert = array('username' => $user, 'password' => sha1($pass));
			$this->db->insert('users', $insert);
			return array('success' => TRUE);
		}
	}
	
	function sign_in($user,$pass)
	{
		$user_pass = array('username' => $user, 'password' => sha1($pass));
		$auth = $this->db->get_where('users', $user_pass);
		if ($auth->num_rows === 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}