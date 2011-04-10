<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {
	
	/**
	 * Create a new video in the database, and upload a photo using the photo model. Uses the Vimeo oEmbed API to get title & embed code.
	 * 
	 * @param string $file_name file name of uploaded thumbnail
	 * @param string $description video description.
	 * @param int $video_url vimeo video url
	 * @param int $g_id gallery id
	 * @return int of new video id or false on failure
	 */
	function create($file_name,$description,$video_url,$g_id)
	{
		// Check if the url is a valid vimeo url as accepted by the API - requires http://, www. or both + vimeo.com/ + 6 - 10 digit number.
		$pattern = '/\A((((http:\/\/){1})|(www\.){1})|(http:\/\/www\.))vimeo\.com\/\d{6,10}\/?\z/';
		if (preg_match($pattern,$video_url))
		{
			// Insert the standard data into the data array
			$data = array('gallery_id' => $g_id, 'vimeo_url' => $video_url, 'description' => $description);
			
			// Open the file, check if it's valid, if so decode it to an object.
			$file = fopen('http://vimeo.com/api/oembed.json?url=' . $video_url,'r');
			if (!$file)
			{
				return FALSE;
				exit;
			}
			$file_text = '';
			while (!feof ($file))
			{
				$file_text .= fgets($file);
			}
			$json = json_decode($file_text);
			
			// If there's a title and embed code (i.e if there's an actual video), put them into the data array and insert it into the database.
			if (isset($json->title) && isset($json->html))
			{
				$data['title'] = $json->title;
				$data['video_id'] = $json->video_id;
				
				// Add the thumbnail into the photo gallery & the id into the data array
				$this->load->model('photo_model','photo');
				$data['photo_id'] = $this->photo->create($file_name,$data['title'],$g_id);
								
				$this->db->insert('videos',$data);
				return $this->db->insert_id();
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Get all videos in a specific gallery.
	 * 
	 * @param int $g_id gallery id
	 * @return array of videos.
	 */
	function get($g_id,$order = 'asc')
	{
		$this->db->join('videos','photos.id = videos.photo_id')->order_by('order',$order);
		$result = $this->db->get_where('photos',array('videos.gallery_id' => $g_id));
		return $result->result();
	}
	
	/**
	 * Delete a video based on id.
	 *
	 * @param int $v_id video id
	 * @return object of deleted photo info, false on failure
	 */
	function delete($v_id)
	{
		// Load the photo model
		$this->load->model('photo_model','photo');
		
		// Get the right photo id
		$this->db->select('photo_id')->where('id',$v_id);
		$photo_id_db = $this->db->get('videos');
		if ($photo_id_db->num_rows() <= 0)
		{
			return FALSE;
			exit;
		}
		else
		{
			$p_id = $photo_id_db->row()->photo_id;
		}

		// Delete the corresponding photo
		$this->photo->delete($p_id);
		
		// Get an array with the video info
		$vid_obj = $this->db->get_where('videos',array('id' => $v_id))->row();
		if ($vid_obj)
		{
			$this->db->delete('videos',array('id' => $v_id));
			return $vid_obj;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	/**
	 * Update the title and description of a video.
	 * 
	 * @param int $id video id
	 * @param string $title title to update to
	 * @param string $description description to update
	 */
	function update($id,$title, $description)
	{
		$update['title'] = $title;
		$update['description'] = $description;
		$this->db->where('id',$id);
		$this->db->update('videos',$update);
	}
		
}

