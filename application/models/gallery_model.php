<?php
class Gallery_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}
	
	function add_image($link, $title)
	{
		$explode = explode('.',$link);
		$ext = array_pop($explode);
		$thumb = implode('.',$explode) . '_thumb.' . $ext;
		$data = array('file' => $link, 'title' => $title, 'file_thumb' => $thumb);
		$this->db->insert('photos', $data);
	}
	
	function get_all_images()
	{
		$query = $this->db->get('photos');
		return $query->result_array();
	}
	
	function delete_image($id)
	{
		$image = $this->db->get_where('photos', array('id' => $id));
		if ($image->num_rows() === 0)
		{
			return FALSE;
		}
		else
		{
			// There should be code here to delete the images from the server. This will be added later.
			$this->db->delete('photos', array('id' => $id));
			$image_arr = $image->result_array();
			return $image_arr;
		}
	}
	
	function change_title($id, $title)
	{
		$title = array('title' => $title);
		$this->db->where('id', $id);
		$this->db->update('photos', $title);
	}

}