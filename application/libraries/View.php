<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * View controller - adds options for templates, titles, messages and data, as well as loading all necessary data for all views.
 * 
 * @package kutt-out-gallery
 * @author Alexander Hill
 * @copyright Copyright (c) 2011 Alexander Hill <http://alxhill.com>
 */
class View {
	
	/**
	 * Private variables, each contain as described and are set by their corresponding function
	 * 
	 * @access private
	 */
	private $template = null;
	private $title = null;
	private $message = null;
	private $data = null;
	public $gallery_type = null;
	
	/**
	 * Add a template to the final output - templates are located in views/gallery/
	 * 
	 * @param string $template template to use
	 */
	public function template($template)
	{
		$this->template = $template;
		return $this;
	}
	
	/**
	 * Add a title to the output.
	 * 
	 * @param string $title
	 */
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * Add a message to be displayed on the page loaded.
	 * 
	 * @param string $class class of the message - success, error or notice.
	 * @param string $message message contents
	 */
	public function message($class,$message)
	{
		$this->message = array('class' => $class, 'message' => $message);
		return $this;
	}
	
	/**
	 * Add data to send to the view
	 * 
	 * @param array $data named array of data
	 */
	public function data($data)
	{
		if(is_array($data))
		{
			$this->data = $data;
			return $this;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Load the view - get all input data and load it into a view.
	 * 
	 * @return bool
	 */
	public function load()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		
		// Exit if no template has been set - no view can be loaded if so.
		if (!(isset($this->template)))
		{
			return FALSE;
			exit;
		}
		else
		{
			$view_data = null;
			if (isset($this->data))
			{
				foreach ($this->data as $key=>$value)
				{
					$view_data[$key] = $value;
				}
			}
			
			// If the title is set preface it with Kutt Out Studios // - otherwise without the slashes alone.
			$header_data['title'] = (isset($this->title)) ? 'Kutt Out Studios // ' . $this->title : 'Kutt Out Studios';			
			
			// If a message is set, put it into the header data.
			if (isset($this->message['class']) && isset($this->message['message']))
			{
				$header_data['class'] = $this->message['class'];
				$header_data['message'] = $this->message['message'];
			}
			
			$header_data['template'] = $this->template;
			$header_data['logged_in'] = $CI->session->userdata('logged_in');
			$header_data['gallery_type'] = $this->gallery_type;
			
			$nav_data['galleries'] = $this->get_galleries();
			
			// Load strings of all the views into the $view array, then load the simple echo view.
			$view['header'] = $CI->load->view('gallery/common/header', $header_data, TRUE);
			$view['nav'] = $CI->load->view('gallery/common/nav', $nav_data, TRUE);
			$view['main'] = $CI->load->view('gallery/' . $this->template, $view_data, TRUE);
			$view['footer'] = $CI->load->view('gallery/common/footer','', TRUE);
			
			$CI->load->view('view', $view);
			
			// Reset the varibles after displaying the view.
			$this->reset();
			
			return TRUE;
			
		}
	}
	
	/**
	 * Reset all variables.
	 */
	public function reset()
	{
		$this->title = null;
		$this->message = null;
		$this->template = null;
		$this->data = null;
	}
	
	/**
	 * Get the galleries for the nav bar.
	 * 
	 * @access private
	 */
	private function get_galleries()
	{
		$CI =& get_instance();
		$CI->load->model('gallery_model','gallery');

		$galleries = $CI->gallery->all(TRUE);
		
		foreach ($galleries as $gallery)
		{
			$all_galleries[] = array('name' => $gallery['name'],'id' => $gallery['id']);
		}
		
		return $all_galleries;
		
	}
	
}