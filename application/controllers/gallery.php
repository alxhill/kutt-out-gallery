<?php
class Gallery extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','notification', 'neatr'));
		$this->load->library('session');
		$this->load->model('gallery_model');
	}
	
	/*						   *
	 * 			 			   *
	 * ===[OTHER FUNCTIONS]=== *
	 *						   *
	 *						   */
	
	// Private function to do as described - check if the logged_in cookie is set.
	function _login_check()
	{
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in)
		{
			return array(TRUE, $this->session->userdata('user'));
		}
		else
		{
			return FAlSE;
		}
	}
	
	// Load the index view.
	function index()
	{
		$this->load->view('index');
	}
	
	// Load the home view
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
	
	/*						   *
	 * 			 			   *
	 * ===[PHOTO FUNCTIONS]=== *
	 *						   *
	 *						   */
	
	// Load the upload view if the user is logged in, otherwise prompt them to do so.
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
	
	// Perform the uploading, resizing and storing of images from the upload form.
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
		
		$g_name = $this->gallery_model->get_gallery_name($this->input->post('g_id'));
		
		if ( ! $this->upload->do_upload("photo"))
		{
			$this->session->set_flashdata('error',$this->upload->display_errors());
			redirect($g_name.'/edit');
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
			
			$this->gallery_model->add_image($upload_data['file_name'],$this->input->post('title'),$this->input->post('g_id'));
			
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
	
	/*							 *
	 * 			 				 *
	 * ===[GALLERY FUNCTIONS]=== *
	 *							 *
	 *						  	 */
	// Non visible function to create a new gallery
	function new_gallery()
	{
		if ($this->_login_check())
		{
			$g_title = $this->input->post('title');
			$g_desc = $this->input->post('description');
			$success = $this->gallery_model->create_gallery($g_title,$g_desc);
			if ($success)
			{
				$this->session->set_flashdata('success','You have successfully created  new gallery.');
			}
			redirect('admin');
		}
		else
		{
			$this->load->view('gallery/superview', array('template' => 'home', 'title' => 'Home', 'message' => 'You must be <a href="gallery/login">logged in</a> to view this page.', 'class' => 'error'));
		}
	}
	
	// Non visible function to update a gallery
	function update_gallery()
	{
		if ($this->_login_check())
		{
			$id = $this->input->post('g_id');
			$description = $this->input->post('g_description');
			$name = $this->input->post('g_name');
			if ($this->gallery_model->update_gallery($id, $name, $description))
			{
				$this->session->set_flashdata('success','Gallery updated successfully');
			}
			else
			{
				$this->session->set_flashdata('error','There was a problem performing you request. Please try again.');
			}
			redirect('admin');
		}
		else
		{
			redirect('home');
		}
	} 
	
	// Get and show a gallery as specified by the ID in the URL
	function show_gallery($g_name)
	{
		$gallery_info = $this->gallery_model->get_gallery_info($g_name);
		if (!$gallery_info)
		{
			$data = array(
						'class' => 'notice',
						'message' => "No gallery with the name \"{$g_name}\" could be found.",
						'title' => 'No gallery found',
						'logged_in' => $this->_login_check(),
						'template' => 'login_form'
						);
			$this->load->view('login/superview',$data);
		}
		else
		{
			$all = $this->gallery_model->get_all_images($gallery_info[0]['id']);
			if ( ! $all)
			{
				$data = array(
							'class' => 'notice',
							'message' => 'There are no photos to display',
							'template' => 'login_form',
							'title' => 'No images to display',
							'logged_in' => $this->_login_check()
							);
				$this->load->view('login/superview', $data);
			}
			else
			{
				$data = array(
							'image_data' => $all,
							'title' => $g_name,
							'template' => 'show_gallery',
							'gallery_info' => $gallery_info[0],
							'logged_in' => $this->_login_check()
							);
				$this->load->view('gallery/superview', $data);
			}
		}
	} // END OF PORTRAITS
	
	// Show the edit page for the specified gallery ID
	function edit($g_name)
	{
		if ($this->_login_check())
		{
			$g_info = $this->gallery_model->get_gallery_info($g_name);
			$images = $this->gallery_model->get_all_images($g_info[0]['id']);
			$user = $this->session->userdata('user');
			$data = array(
						'image_data' => $images,
						'user' => $user,
						'title' => 'Admin control panel',
						'template' => 'edit',
						'g_info' => $g_info[0],
						'logged_in' => $this->_login_check()
						);
			if ($this->session->flashdata('success'))
			{
				$data['class'] = 'success';
				$data['message'] = $this->session->flashdata('success');
			}
			else if ($this->session->flashdata('error'))
			{
				$data['class'] = 'error';
				$data['message'] = $this->session->flashdata('error');
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
	} // END OF EDIT
	
	// Show the admin page poplated by all the galleries in the database
	function admin()
	{
		$galleries = $this->gallery_model->get_all_galleries();
		if ($this->session->flashdata('success'))
		{
			$data['class'] = 'success';
			$data['message'] = $this->session->flashdata('success');
		}
		else if ($this->session->flashdata('error'))
		{
			$data['class'] = 'error';
			$data['message'] = $this->session->flashdata('error');
		}
		if ($this->_login_check())
		{
			$this->load->view('gallery/superview', array(
														'title' => 'Galleries',
														'template' => 'admin',
														'galleries' => $galleries,
														'logged_in' => $this->_login_check()
														));
		}
		else
		{
			$data = array(
						 'title' => 'Home',
						 'template' => 'login_form',
						 'class' => 'error',
						 'message' => 'You must be logged in to access this page',
						 'galleries' => $galleries,
						 'logged_in' => $this->_login_check()
						 );
			$this->load->view('login/superview', $data);
		}
	} // END OF ADMIN
	
	/*						  *
	 * 						  *
	 * ===[AJAX FUNCTIONS]=== *
	 *						  *
	 *						  */
	function ajax_delete()
	{
		if (IS_AJAX && $this->_login_check())
		{
			$photo_id = $this->input->post('id');
			$image = $this->gallery_model->delete_image($photo_id);
			if ($image)
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
			echo "This page is not available.";
		}

	}
	
	function ajax_update()
	{
		if (IS_AJAX && $this->_login_check())
		{
			$photo_id = $this->input->post('id');
			$photo_title = $this->input->post('title');
			$this->gallery_model->change_title($photo_id,$photo_title);
		}
		else
		{
			echo "This page is not available";
		}
	}	
	
	function ajax_gallery_delete()
	{
		if (IS_AJAX && $this->_login_check())
		{
			$g_id = $this->input->post('id');
			$gallery = $this->gallery_model->delete_gallery($g_id);
			if ($g_id)
			{
				echo 'The gallery "' . $gallery[0]['name'] . '" was deleted successfully.';
			}
			else
			{
				echo 'An error has occurred - the gallery has not been deleted.';
			}
		}
	}
}