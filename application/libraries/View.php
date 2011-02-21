<?php

/*
	View controller for showing views. Adds spedific data, and allows selection of a custom title, template, message and data. 
	
*/

class View {
	
	private $template = null;
	private $title = null;
	private $message = null;
	private $data = null;
	
	function __construct()
	{
	}
	
	public function template($template)
	{
		$this->template = $template;
		return $this;
	}
	
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}
	
	public function message($class,$message)
	{
		$this->message = array('class' => $class, 'message' => $message);
		return $this;
	}
	
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
	
	public function load()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		
		if (!(isset($this->template)))
		{
			return FALSE;
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
			
			$header_data['title'] = (isset($this->title)) ? 'Kutt Out Studios // ' . $this->title : 'Kutt Out Studios';			
			
			if (isset($this->message['class']) && isset($this->message['message']))
			{
				$view_data['class'] = $this->message['class'];
				$view_data['message'] = $this->message['message'];
			}
			
			$nav_data['galleries'] = $this->get_galleries();
			
			$header_data['logged_in'] = $CI->session->userdata('logged_in');
			
			$view['header'] = $CI->load->view('gallery/common/header', $header_data, TRUE);
			$view['nav'] = $CI->load->view('gallery/common/nav', $nav_data, TRUE);
			$view['main'] = $CI->load->view('gallery/' . $this->template, $view_data, TRUE);
			$view['footer'] = $CI->load->view('gallery/common/footer','', TRUE);
			
			$CI->load->view('view', $view);
			
			$this->reset();
			
			return TRUE;
			
		}
	}
	
	public function reset()
	{
		$this->title = null;
		$this->message = null;
		$this->template = null;
		$this->data = null;
	}
	
	private function get_galleries()
	{
		$CI =& get_instance();
		$CI->load->model('gallery_model');

		$galleries = $CI->gallery_model->get_all_galleries();
		
		foreach ($galleries as $gallery)
		{
			$all_galleries[] = $gallery['name'];
		}
		
		return $all_galleries;
		
	}
	
}