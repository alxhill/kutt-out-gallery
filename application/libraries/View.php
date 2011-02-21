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
		$not_this &= get_instance();
		$not_this->load->model('gallery_model');
		
	}
	
	public function template($template)
	{
		$this->template = $template;
	}
	
	public function title($title)
	{
		$this->title = $title;
	}
	
	public function message($class,$title)
	{
		$this->message = array('class' => $class, 'message' => $message);
	}
	
	public function data($data)
	{
		if(is_array($data))
		{
			$this->data = $data;
		}
	}
	
	public function load()
	{
		if (!(isset($template))
		{
			return FALSE;
		}
		else
		{
			if (isset($this->data))
			{
				foreach ($this->data as $key=>$value)
				{
					$view_data[$key] = $value;
				}
			}
			
			$view_data['title'] = (isset($this->title)) ? 'Kutt Out Studios // ' . $this->title : 'Kutt Out Studios';			
			
			if (isset($this->message['class']) && isset($this->message['message']))
			{
				$view_data['class'] = $this->message['class'];
				$view_data['message'] = $this->message['message'];
			}
			
			$nav_data['galleries'] = $this->get_galleries();
			
			$header_data['logged_in'] = $not_this->session->userdata('logged_in');
			
			$head = $not_this->load->view('gallery/common/header', $header_data, TRUE);
			$nav = $not_this->load->view('gallery/common/nav', $nav_data, TRUE);
			$main = $not_this->load->view('gallery/' . $this->template, $view_data, TRUE);
			$footer = $not_this->load->view('gallery/common/footer','', TRUE);
			
			$this->reset;
			
			echo $head.$nav.$main.$footer;
			
		}
	}
	
	private function get_galleries()
	{
		$galleries = $not_this->gallery_model->get_all_galleries();
		
		foreach ($galleries as $gallery)
		{
			$all_galleries[] = $gallery['name'];
		}
		
		return $all_galleries;
		
	}
	
	private function reset()
	{
		$this->title = null;
		$this->message = null;
		$this->template = null;
		$this->data = null;
	}
}