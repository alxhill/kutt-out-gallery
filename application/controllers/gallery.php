<?php
class Gallery extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','notification'));
		$this->load->library('session');
		$this->load->model('gallery_model');
	}
	
	function _login_check()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in)
		{
			$return = array(TRUE, $this->session->userdata('user'));
			return $return;
		}
		else
		{
			return FAlSE;
		}
	}
	
	function index()
	{
		$this->load->view('index');
	}
	
	function home()
	{
		$this->load->view('gallery/superview', array('title' => 'Kutt Out Studio', 'template' => 'home'));
	}
	
	function add_photo()
	{
		if ($this->_login_check())
		{
			if ($this->session->flashdata('login'))
			{
				$this->load->view('gallery/superview', array('title' => 'Upload a new image', 'template' => 'upload', 'class' => 'success', 'message' => $this->session->flashdata('login')));
			}
			else
			{
				$this->load->view('gallery/superview', array('title' => 'Upload a new image', 'template' => 'upload' ));
			}
		}
		else
		{
			$this->load->view('login/superview', array('title' => 'Log in', 'template' => 'login_form', 'class' => 'error', 'message' => 'You must be logged in to view this page.'));
		}
	}

	function upload()
	{
		$config = array('upload_path' => './assets/upload/',
						'allowed_types' => 'gif|jpeg|jpg|png',
						'max_size' => '20000',
						'max_width' => '1024',
						'max_height' => '800');
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
			
			// create a tumbnail for the image
			$config_img = array(
								'source_image' => 'assets/upload/' . $upload_data['file_name'],
								'create_thumb' => TRUE,
								'maintain_ratio' => TRUE,
								'width' => 100,
								'height' => 60
								);
			$this->load->library('image_lib', $config_img);
			if ( ! $this->image_lib->resize())
			{
				print_r($this->image_lib->display_errors());
			}
			
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
	
	function portraits()
	{
		$all = $this->gallery_model->get_all_images();
		if ( ! $all)
		{
			$data = array('class' => 'notice','message' => 'There are no photos to display', 'template' => 'login_form', 'title' => 'No images to display');
			$this->load->view('login/superview', $data);
		}
		else
		{
			$data = array('image_data' => $all, 'title' => 'Gallery', 'template' => 'show_gallery');
			$this->load->view('gallery/superview', $data);
		}
	} // END OF PORTRAITS
	
	function admin()
	{
		if ($this->_login_check())
		{
			$images = $this->gallery_model->get_all_images();
			$user = $this->session->userdata('user');
			$data = array('image_data' => $images, 'user' => $user, 'title' => 'Admin control panel', 'template' => 'admin');
			$this->load->view('gallery/superview', $data);
		}
		else
		{
			$this->load->view('login/superview', array('title' => 'Log in', 'template' => 'login_form', 'class' => 'error', 'message' => 'You must be logged in to view this page.'));
		}
	} // END OF ADMIN
	
	function ajax_delete()
	{
		$photo_id = $this->input->post('id');
		$image = $this->gallery_model->delete_image($photo_id);
		echo 'Image with ID ' . $photo_id . ' ("'. $image[0]['title'] .'") has been deleted.';
		//print_r($image);
	}
	
}