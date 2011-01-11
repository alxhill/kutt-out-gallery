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
		$this->load->view('gallery/superview', array('title' => 'Upload a new image', 'template' => 'upload'));
	}

	function upload()
	{
		$config = array('upload_path' => './assets/upload/',
						'allowed_types' => 'gif|jpeg|jpg|png',
						'max_size' => '20000');
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload("photo"))
		{
			$errors = array('message' => $this->upload->display_errors(), 'class' => 'error', 'template' => 'upload', 'title' => 'Upload failed');
			$this->load->view('gallery/superview',$errors);
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
							 'link' => $link,
							 'template' => 'post_upload',
							 'title' => 'Image uploaded'
							 );
			$this->load->view('gallery/superview',$success);
		}
		
	} // END OF UPLOAD
	
	function show()
	{
		$all = $this->gallery_model->get_all_images();
		if ( ! $all)
		{
			$data = array('class' => 'notice','message' => 'There are no photos to display', 'template' => 'upload', 'title' => 'No images to display');
			$this->load->view('gallery/superview', $data);
		}
		else
		{
			$data = array('image_data' => $all, 'title' => 'Gallery view', 'template' => 'show_gallery');
			$this->load->view('gallery/superview', $data);
		}		
	}

}