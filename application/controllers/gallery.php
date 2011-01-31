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
		if ($this->session->flashdata('logout'))
		{
			$this->load->view('gallery/superview', array(
														'title' => 'Kutt Out Studio',
														'template' => 'home',
														'class' => 'success',
														'message' => 'You have successfully logged out.',
														'logged_in' => $this->_login_check()
														));
		}
		else
		{
			$this->load->view('gallery/superview', array(
														'title' => 'Kutt Out Studio',
														'template' => 'home',
														'logged_in' => $this->_login_check()
														));
		}
	}
		
	function add_photo()
	{
		if ($this->_login_check())
		{
			if ($this->session->flashdata('login'))
			{
				$this->load->view('gallery/superview', array(
															'title' => 'Upload a new image',
															'template' => 'upload',
															'class' => 'success',
															'message' => $this->session->flashdata('login'),
															'logged_in' => $this->_login_check()
															));
			}
			else
			{
				$this->load->view('gallery/superview', array(
															'title' => 'Upload a new image',
															'template' => 'upload',
															'logged_in' => $this->_login_check()
															));
			}
		}
		else
		{
			$this->load->view('login/superview', array(
													'title' => 'Log in',
													'template' => 'login_form',
													'class' => 'error',
													'message' => 'You must be logged in to view this page.',
													'logged_in' => $this->_login_check()
													));
		}
	}

	function upload()
	{
		$config = array(
						'upload_path' => './assets/upload/',
						'allowed_types' => 'gif|jpeg|jpg|png',
						'max_size' => '20000',
						'max_width' => '1024',
						'max_height' => '800'
						);
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload("photo"))
		{
			$errors = array(
							'message' => $this->upload->display_errors(),
							'class' => 'error',
							'title' => 'Upload failed',
							'logged_in' => $this->_login_check()
							);
			
			$errors['template'] = $this->_login_check() ? 'upload' : 'home';
			$this->load->view('gallery/superview',$errors);
		}
		else
		{
			$upload_data = $this->upload->data();
			
			$normal_loc = 'assets/upload/' . $upload_data['file_name'];
			$thumb_loc = 'assets/upload/' . $upload_data['raw_name'] . '_thumb' . $upload_data['file_ext'];
			$link = site_url("assets/upload/" . $upload_data['file_name']);
			
			//resize and crop the image with the zebra library
			$this->load->library('zebra');
			$this->zebra->setup($normal_loc,$thumb_loc, array('preserve_aspect_ratio'=>true,'enable_smaller_images'=>true));
			$this->zebra->resize(120, 80, 3);
			
			$this->gallery_model->add_image($upload_data['file_name'],$this->input->post('title'));
			$success = array(
							 'message' => 'Image successfully uploaded!',
							 'class' => 'success',
							 'upload_data' => $this->upload->data(),
							 'link' => $link,
							 'template' => 'post_upload',
							 'title' => 'Image uploaded',
						   	 'logged_in' => $this->_login_check()
							 );
			$this->load->view('gallery/superview',$success);
		}
		
	} // END OF UPLOAD
	
	// =====[Update this! Change it to a generic gallery function that works based on IDs that get mapped to the relevant gallery. Update the model to support this also.]=====
	
	function show_gallery($gallery_id)
	{
		$all = $this->gallery_model->get_all_images($gallery_id);
		if ( ! $all)
		{
			$data = array(
						'class' => 'notice',
						'message' => 'There are no photos to display',
						'template' => 'login_form',
						'title' => 'No images to display',
						'logged_in' => $this->_login_check());
			$this->load->view('login/superview', $data);
		}
		else
		{
			$data = array(
						'image_data' => $all,
						'title' => 'Gallery',
						'template' => 'show_gallery',
						'logged_in' => $this->_login_check()
						);
			$this->load->view('gallery/superview', $data);
		}
	} // END OF PORTRAITS
	
	function admin()
	{
		if ($this->_login_check())
		{
			$images = $this->gallery_model->get_all_images();
			$user = $this->session->userdata('user');
			$data = array(
						'image_data' => $images,
						'user' => $user,
						'title' => 'Admin control panel',
						'template' => 'admin',
						'logged_in' => $this->_login_check()
						);
			if ($this->session->flashdata('login'))
			{
				$data['class'] = 'success';
				$data['message'] = $this->session->flashdata('login');
			}
			$this->load->view('gallery/superview', $data);
		}
		else
		{
			$this->load->view('login/superview', array(
													'title' => 'Log in',
													'template' => 'login_form',
													'class' => 'error',
													'message' => 'You must be logged in to view this page.',
													'logged_in' => $this->_login_check()
													));
		}
	} // END OF ADMIN
	
	function ajax_delete()
	{
		if (IS_AJAX)
		{
			$photo_id = $this->input->post('id');
			$image = $this->gallery_model->delete_image($photo_id);
			if (!$image == FALSE)
			{
				echo 'Image with ID ' . $photo_id . ' ("'. $image[0]['title'] .'") has been deleted.';
			}
			else
			{
				echo "An error has occurred - the image was not deleted.";
			}
		}
		else
		{
			redirect('home');
		}

	}
	
	function ajax_update()
	{
		if (IS_AJAX)
		{
			$photo_id = $this->input->post('id');
			$photo_title = $this->input->post('title');
			$this->gallery_model->change_title($photo_id,$photo_title);
		}
		else
		{
			redirect('home');
		}
	}

}