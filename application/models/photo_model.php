<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Photo_model extends CI_Model {
	
	/**
	 * Create a new photo in the database, linked to a specific gallery.
	 * 
	 * @param string $file_name file name with extension
	 * @param string $title title displayed under photo
	 * @param int $g_id gallery id to insert to
	 * @return int inserted id
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
		
		//add in the order of the new photo
		$data['order'] = $this->db->select_max('order','`order`')->where('gallery_id',$g_id)->get('photos')->row()->order + 1;

		$this->db->insert('photos', $data);
		
		return $this->db->insert_id();
	}
	
	/**
	 * Get all photos for a specific gallery.
	 * 
	 * @param int $g_id gallery id
	 * @return array of photos
	 */
	function get($g_id,$order = 'asc')
	{
		$this->db->order_by('order',$order);
		$query = $this->db->get_where('photos', array('gallery_id' => $g_id));
		return $query->result();
	}
	
	/**
	 * Remove a photo from db & delete from file.
	 * 
	 * @param int $id photo id
	 * @return object of image details
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
			$image_obj = $image->row();
			unlink($image_obj->file_path);
			unlink($image_obj->file_thumb_path);
			$this->db->delete('photos', array('id' => $id));
			return $image_obj;
		}
	}
	
	/**
	 * Change the title of a photo.
	 * 
	 * @param int $id photo id
	 * @param string $title title to change to
	 */
	function update($id, $title)
	{
		$title = array('title' => $title);
		$this->db->where('id', $id);
		$this->db->update('photos', $title);
	}
	
}