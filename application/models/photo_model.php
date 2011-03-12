<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Photo_model extends CI_Model {
	
	/**
	 * Create a new photo in the database, linked to a specific gallery.
	 * 
	 * @param string $file_name file name with extension
	 * @param string $title title displayed under photo
	 * @param int $g_id gallery id to insert to
	 */
	function create($file_name, $title, $g_id)
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
		
		return $this->db->insert_id();
	}
	
	/**
	 * Get all photos for a specific gallery.
	 * 
	 * @param int $g_id gallery id
	 * @return array of photos
	 */
	function get($g_id)
	{
		$query = $this->db->get_where('photos', array('gallery_id' => $g_id));
		return $query->result_array();
	}
	
	/**
	 * Remove a photo from db & delete from file.
	 * 
	 * @param int $id photo id
	 */
	function delete($id)
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
	
	/**
	 * Change the title of a photo.
	 * 
	 * @param int $id photo id
	 * @param string $title title to change to
	 */
	function edit_title($id, $title)
	{
		$title = array('title' => $title);
		$this->db->where('id', $id);
		$this->db->update('photos', $title);
	}
	
}