<?php
class Gallery_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}
	
	function add_image($file_name, $title, $g_id)
	{
		//code to work out the name of the thumb
		$name_explode = explode('.', $file_name);
		$ext = array_pop($name_explode);
		$thumb_name = implode('.', $name_explode) . '_thumb.' . $ext;
		
		//create the file names
		$data['file_name'] = $file_name;
		$data['file_thumb_name'] = $thumb_name;
		
		//create the file paths
		$data['file_path'] = 'assets/upload/' . $file_name;
		$data['file_thumb_path'] = 'assets/upload/' . $thumb_name;
		
		//create the file links
		$data['file_link'] = site_url("assets/upload/" . $file_name);
		$data['file_thumb_link'] = site_url("assets/upload/" . $thumb_name);
		
		//add in the title & gallery id
		$data['title'] = $title;
		$data['gallery_id'] = $g_id;
		
		$this->db->insert('photos', $data);
	}
	
	function get_all_images($g_id)
	{
		$query = $this->db->get_where('photos', array('gallery_id' => $g_id));
		return $query->result_array();
	}
	
	function get_gallery_info($g_id)
	{
		$query = $this->db->get_where('galleries', array('id' => $g_id));
		$result = $query->result_array();
		return $result;
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
			$image_array = $image->result_array();
			unlink($image_array[0]['file_path']);
			unlink($image_array[0]['file_thumb_path']);
			$this->db->delete('photos', array('id' => $id));
			return $image_array;
		}
	}
	
	function change_title($id, $title)
	{
		$title = array('title' => $title);
		$this->db->where('id', $id);
		$this->db->update('photos', $title);
	}
	
	function get_all_galleries()
	{
		$return = $this->db->get('galleries');
		return $return->result_array();
	}

}