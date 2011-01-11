<?php
class Gallery_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function add_image($link, $title)
	{
		$data = array('file' => $link, 'title' => $title);
		$this->db->insert('gallery', $data);
	}
	
	function get_all_images()
	{
		$query = $this->db->get('gallery');
		return $query->result_array();
	}

}