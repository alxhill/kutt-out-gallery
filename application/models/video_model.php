<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {
	
	/**
	 * Create a new video in the database, and upload a photo using the photo model. Uses the Vimeo oEmbed API to get title & embed code.
	 * 
	 * @param string $file_name file name of uploaded thumbnail
	 * @param string $description video description.
	 * @param int $video_url vimeo video url
	 * @param int $g_id gallery id
	 */
	function create($file_name,$description,$video_url,$g_id)
	{
		// Check if the url is a valid vimeo url as accepted by the API - requires http://, www. or both + vimeo.com/ + 6 - 10 digit number.
		$pattern = '/\A((((http:\/\/){1})|(www\.){1})|(http:\/\/www\.))vimeo\.com\/\d{6,10}\/?\z/';
		if (preg_match($pattern,$video_url))
		{
			// Insert the standard data into the data array
			$data = array('gallery_id' => $g_id, 'video_url' => $video_url, 'description' => $description);
			
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
				$data['embed_code'] = $json->html;
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
	function get($g_id)
	{
		$this->db->join('photos','photos.id = videos.photo_id');
		$result = $this->db->get('videos',array('gallery_id' => $g_id));
		return $result->result_array();
	}
	
	/**
	 * Delete a video based on id.
	 *
	 * @todo delete the corresponding thumbnail photo
	 * @param int $v_id video id
	 */
	function delete($v_id)
	{
		$this->db->select('photo_id')->where('id',$v_id);
		$photo_id_db = $this->db->get_where('videos');
		$p_id = $photo_id_db->result()->row()->photo_id;
		
		$this->db->delete('videos',array('id',$v_id));
	}
	
	/**
	 * Get the corresponding thumbnail for a video.
	 * 
	 * @param int $v_id video id
	 * @return object consisting of photo and video data from db.
	 */
	function thumbnail($v_id)
	{
		$this->db->join('videos', 'videos.photo_id = photos.id');
		$result = $this->db->get('photos');
		return $result->row();
	}
	
}

