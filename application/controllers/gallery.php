<?php
class Gallery extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library('session');
		$this->load->model('gallery_model');
	}
	
	function add_photo()
	{
		$this->load->view('upload');
	}

	function upload()
	{
		$config = array('upload_path' => './assets/upload/',
						'allowed_types' => 'gif|jpeg|jpg|png',
						'max_size' => '20000');
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload("photo"))
		{
			$errors = array('message' => $this->upload->display_errors(), 'class' => 'error');
			$this->load->view('upload',$errors);
		}
		else
		{
			$upload_data = $this->upload->data();
			$link = site_url("assets/upload/" . $upload_data['file_name']);
			$title = $this->input->post('title');
			$this->gallery_model->add_image($link,$title);
			$success = array(
							 'message' => 'Image successfully uploaded!',
							 'class' => 'success',
							 'upload_data' => $this->upload->data(),
							 'link' => $link
							 );
			$this->load->view('post_upload',$success);
		}
		
	} // END OF UPLOAD
	
	function show()
	{
		$all = $this->gallery_model->get_all_images();
		print_r($all); //**UNFINISHED FUNCTION**//
	}

}