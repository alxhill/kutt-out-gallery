<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Provides access to the galleries database, including adding, editing and updating galleries.
*
* @package kutt-out-gallery
* @author Alexander Hill <http://alxhill.com>
* @copyright Copyright (c) 2011 Alexander Hill <http://alxhill.com>
*/
class Gallery_model extends CI_Model {
	
	/**
	* Constructor - load stuff
	*/
	function __construct()
	{
		parent::__construct();
		$this->load->helper('file', 'neatr');
	}
	
	/**
	 * Gets info regarding a specific gallery - name, type, visibility etc.
	 * 
	 * @param string or int $g_val gallery name or id
	 * @return object - row of results, false on invalid $g_val type
	 */
	function info($g_val)
	{
		if (is_string($g_val))
		{
			$query = $this->db->get_where('galleries', array('name' => $g_val));
		}
		else if (is_int($g_val))
		{
			$query = $this->db->get_where('galleries', array('id' => $g_val));
		}
		else
		{
			return FALSE;
		}
		
		$result = $query->row();
		return $result;
		
	}
	
	/**
	 * Gets the name of a gallery from its id,
	 * 
	 * @param int $g_id gallery id
	 * @return string of gallery name
	 */
	function name($g_id)
	{
		$query = $this->db->get_where('galleries', array('id' => $g_id));
		return $query->row()->name;
	}
	
	/**
	 * Get all galleries, option for getting visible galleries only as well as changing the order in which to change galleries.
	 * 
	 * @param bool $visible should it return only visible galleries, defaults to false.
	 * @return object of results
	 */
	function all($visible = FALSE, $order = 'asc')
	{
		if ($visible)
		{
			$this->db->where('visible',1);
		}
		$return = $this->db->order_by('order', $order)->get('galleries')->result();
		return $return;
	}
	
	/**
	 * Create a new gallery.
	 * 
	 * @param $g_name gallery name
	 * @param $g_desc gallery description
	 */
	function create($g_name, $g_desc, $type = 1)
	{
		$insert = array('name' => $g_name, 'description' => $g_desc, 'type' => $type);
		$this->db->insert('galleries', $insert);
		return TRUE;
	}
	
	/**
	 * Updates the title for a gallery, optionally the description.
	 * 
	 * @param $g_id gallery id
	 * @param $g_new_name name to change to
	 * @param $g_new_description description to update, optional
	 */
	function update($g_id,$g_new_name,$g_new_description = null)
	{
		$this->db->where('id', $g_id);
		$data['name'] = $g_new_name;
		if ($g_new_description)
		{
			$data['description'] = $g_new_description;
		}
		$this->db->update('galleries',$data);
		
		return TRUE;
	}
	
	/**
	 * Deletes a gallery from its ID
	 * 
	 * @param $g_id gallery id
	 * @return object of info for deleted gallery, false on failure
	 */
	function delete($g_id)
	{
		$gallery = $this->db->get_where('galleries', array('id' => $g_id));
		if ($gallery->num_rows() === 0)
		{
			return FALSE;
		}
		else
		{
			$g_row = $gallery->row();
			$this->db->delete('galleries', array('id' => $g_id));
			
			$g_photos = $this->db->get_where('photos', array('gallery_id' => $g_id));
			
			$this->load->model('photo_model','photo');
			foreach ($g_photos->result() as $row)
			{
				$this->photo->delete($row->id);
			}
			
			return $g_row;
		}
	}
	
	/**
	 * Set a gallery to 'show' status. Opposite of {@link hide()}.
	 * 
	 * @param $g_id gallery id
	 */
	function show($g_id)
	{
		$this->db->where('id', $g_id);
		$this->db->update('galleries', array('visible' => 1));
		return TRUE;
	}
	
	/**
	 * Set a gallery to 'hide' status. Opposite of {@link show()}.
	 * 
	 * @param $g_id gallery id
	 */
	function hide($g_id)
	{
		$this->db->where('id', $g_id);
		$this->db->update('galleries', array('visible' => 0));
		return TRUE;
	}
	
	/**
	 * Change the order of the galleries
	 * 
	 * @param array $order array of the order of the galleries
	 */
	function order($order)
	{
		foreach ($order as $new_order => $id)
		{
			$this->db->where('id', $id)->update('galleries', array('order' => $new_order));
		}
		
		return TRUE;
	}
	
}