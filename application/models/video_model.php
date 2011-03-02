<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {
	
	/**
	 * Create a new video once a photo has been uploaded.
	 * 
	 * @param string $title video title. May be irrelevant as photo has a title.
	 * @param int $video vimeo video url
	 * @param int $p_id thumbnail photo id
	 * @param int $g_id gallery id
	 */
	function create($title,$video,$p_id,$g_id)
	{
		$data = array('title' => $title,'photo_id' => $p_id, 'gallery_id' => $g_id, 'video_url' => $video);
		$this->db->insert('videos',$data);
	}
	
	/**
	 * Get all videos in a specific gallery.
	 * 
	 * @param int $g_id gallery id
	 * @return array of videos.
	 */
	function get($g_id)
	{
		$result = $this->db->get_where('videos',array('gallery_id',$g_id));
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

