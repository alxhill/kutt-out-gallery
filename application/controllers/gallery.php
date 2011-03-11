<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Main gallery controller - handles all input and output related to galleries.
 * 
 * @package kutt-out-gallery
 * @author Alexander Hill
 * @copyright Copyright (c) 2011 Alexander Hill <http://alxhill.com>
 */
class Gallery extends CI_Controller {
	
	/**
	 * Constructer - loads all necessary libraries, models, etc.
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','notification', 'neatr'));
		$this->load->library(array('session', 'view'));
		$this->load->model('gallery_model', 'gallery');
		$this->load->model('photo_model','photo');
		$this->load->model('video_model','video');
	}
	
	/**
	 * Check if the logged_in cookie is set.
	 * 
	 * @access private
	 * @return boolean 
	 */
	private function _login_check()
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
	
	/**
	 * Load the index page.
	 */	
	function index()
	{
		$this->load->view('index');
	}
	
	/**
	 * Load the home view, add a logout message if necessary.
	 */
	function home()
	{
		if ($this->session->flashdata('logout'))
		{
			$this->view->template('home')->message('success', 'You have successfully logged out.');
			$this->view->load();
		}
		else
		{
			$this->view->template('home');
			$this->view->load();
			
		}
	}
	
	/*		    				   *
	 * =====[PHOTO FUNCTIONS]===== *
	 *			    			   */
		
	/**
	 * Upload function, gets data from post and uploads into a folder/database.
	 */
	function upload()
	{
		// Set up and load the upload library.
		$config['upload_path'] = './assets/upload/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png';
		$config['max_size'] = '20000';
		$config['max_width'] = '1024';
		$config['max_height'] = '800';
		$this->load->library('upload', $config);
		
		// Get the gallery's name.
		$g_name = $this->gallery->name($this->input->post('g_id'));
		
		if ( ! $this->upload->do_upload("photo"))
		{
			$this->session->set_flashdata('error',$this->upload->display_errors());
			redirect($g_name.'/edit');
		}
		else
		{
			$upload_data = $this->upload->data();
			
			// Set the file location for zebra and for 
			$normal_loc = 'assets/upload/' . $upload_data['file_name'];
			$thumb_loc = 'assets/upload/' . $upload_data['raw_name'] . '_thumb' . $upload_data['file_ext'];
			$img = site_url("assets/upload/" . $upload_data['file_name']);
			
			// Resize and crop the image with the zebra library
			$this->load->library('zebra');
			$this->zebra->setup($normal_loc,$thumb_loc, array('preserve_aspect_ratio'=>true,'enable_smaller_images'=>true));
			$this->zebra->resize(120, 80, 3);
			
			// Insert the upload into the right place
			if ($this->input->post('type') == 'video')
			{
				$this->video->create($upload_data['file_name'],$this->input->post('description'),$this->input->post('url'),$this->input->post('g_id'));
			}
			else
			{
				$this->photo->create($upload_data['file_name'],$this->input->post('title'),$this->input->post('g_id'));
			}
			
			// Show the upload view
			$data = array('upload_data' => $this->upload->data(), 'link' => $img, 'g_name' => $g_name);
			$this->view->template('post_upload')->title('Image uploaded')->message('success','Image uploaded successfully!')->data($data)->load();
		}
		
	}
	
	/*							     *
	 * =====[GALLERY FUNCTIONS]===== *
	 *						  	     */
	
	/**
	 * Creates a new gallery in the database based on post data, then redirects the user to the admin page with a success message.
	 */
	function new_gallery()
	{
		if ($this->_login_check())
		{
			$g_title = $this->input->post('title');
			$g_desc = $this->input->post('description');
			$type = $this->input->post('type');
			
			$success = $this->gallery->create($g_title,$g_desc,$type);
			if ($success)
			{
				$this->session->set_flashdata('success','You have successfully created  new gallery.');
			}
			redirect('admin');
		}
		else
		{
			$this->view->template('home')->title('Home')->message('error','You must be <a href="gallery/login">logged in</a> to view this page.');
			$this->view->load();
		}
	}
	
	/**
	 * Updates a gallery based on post data, then redirects the user to the admin page with a message.
	 */
	function update_gallery()
	{
		if ($this->_login_check())
		{
			$id = $this->input->post('g_id');
			$description = $this->input->post('g_description');
			$name = $this->input->post('g_name');
			if ($this->gallery->update($id, $name, $description))
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
	
	/**
	 * Show or hide a gallery, then redirect to admin.
	 * @param string $action The action to perform - show or hide
	 * @param string $g_id The gallery ID to change.
	 */
	function show_hide($action,$g_id)
	{
		if ($action == 'hide')
		{
			$this->gallery->hide($g_id);
		}
		else if ($action == 'show')
		{
			$this->gallery->show($g_id);
		}
		else
		{
			$this->session->set_flashdata('error','Invalid function');
		}
		redirect('admin');
	}
	
	/**
	 * Get and show a gallery from its name.
	 *
	 * @todo Add in an error view to use for displaying errors properly.
	 * @param string $g_name gallery name
	 */
	function show_gallery($g_name)
	{
		$gallery_info = $this->gallery->info($g_name);
		$g_id = $gallery_info[0]['id'];
		
		if (!$gallery_info)
		{
			$this->view->template('login_form')->title('No gallery found')->message('notice','No gallery with the name "'.$g_name.'" could be found.');
			$this->view->load();
		}
		else
		{
			$all = $this->photo->get($g_id);
			if ( ! $all)
			{	
				$this->view->template('login_form')->title('Nothing to display')->message('notice','There is nothing to display. Please upload some content into this gallery.');
				$this->view->load();
			}
			else
			{
				$this->view->template('show_gallery')->title($g_name);
				$data = array('gallery_info' => $gallery_info[0], 'image_data' => $all);
				if ($gallery_info[0]['type'] == 2)
				{
					$data['type'] = 'video';
					$data['videos'] = $this->video->get($g_id);
				}
				else
				{
					$data['type'] = 'photo';
				}
				
				$this->view->data($data)->load();
			}
		}
	}
	
	/**
	 * Show the edit page for the specified gallery ID.
	 *
	 * @param string $g_name gallery name
	 */
	function edit($g_name)
	{
		if ($this->_login_check())
		{
			$g_info = $this->gallery->info($g_name);
			$images = $this->photo->get($g_info[0]['id']);
			$user = $this->session->userdata('user');
			if ($this->session->flashdata('success'))
			{
				$this->view->message('success',$this->session->flashdata('success'));
			}
			else if ($this->session->flashdata('error'))
			{
				$this->view->message('error',$this->session->flashdata('error'));
			}
			$data = array('image_data' => $images, 'user' => $user, 'g_info' => $g_info[0]);
			$this->view->template('edit')->title("Edit gallery {$g_name}")->data($data);
			$this->view->load();
		}
		else
		{
			$this->view->template('login_form')->title('Log in')->message('error','You must be logged in to view this page.');
			$this->view->load();
		}
	}
	
	/**
	 * Admin function to show the admin page with the list of galleries.
	 */
	function admin()
	{
		$galleries = $this->gallery->all();
		if ($this->session->flashdata('success'))
		{
			$this->view->message('success',$this->session->flashdata('success'));
		}
		else if ($this->session->flashdata('error'))
		{
			$this->view->message('success',$this->session->flashdata('success'));
		}
		if ($this->_login_check())
		{
			$this->view->template('admin')->title('Galleries')->data(array('galleries' => $galleries));
			$this->view->load();
		}
		else
		{
			$data = array('galleries' => $galleries);
			$this->view->title('home')->template('login_form')->message('error','You must be logged in to access this page.')->data($data);
			$this->view->load();
		}
	}
	
	/*						      *
	 * =====[AJAX FUNCTIONS]===== *
	 *						      */
	
	/**
	 * Deletes a photo when called through AJAX.
	 */
	function ajax_delete()
	{
		if (IS_AJAX)
		{
			$photo_id = $this->input->post('id');
			$image = $this->photo->delete($photo_id);
			header('Content-type: application/json');
			if ($image)
			{
				$json = array('code' => 0, 'id' => $photo_id, 'title' => $image[0]['title']);
				echo json_encode($json);
			}
			else
			{
				$json = array('code' => 1, 'message' => 'An error has occurred - the image was not deleted.');
				echo json_encode($json);
			}
		}
		else
		{
			redirect('home');
		}

	}
	
	/**
	 * Updates the title of a photo when called through AJAX.
	 */
	function ajax_update()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$photo_id = $this->input->post('id');
				$photo_title = $this->input->post('title');
				$this->photo->edit_title($photo_id,$photo_title);
			
				header('Content-type: application/json');
				echo json_encode(array('code' => 0));
			}
			else
			{
				header('Content-type: application/json');
				echo json_encode(array('code' => 1,'message'=>'You must be logged in to perform this action.'));
			}
		}
		else
		{
			redirect('home');
		}

	}	
	
	/**
	 * Deletes a gallery when called through AJAX.
	 */ 
	function ajax_gallery_delete()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$g_id = $this->input->post('id');
				$gallery = $this->gallery->delete($g_id);
				if ($g_id)
				{
					$json = array('code' => 0, 'message' => 'The gallery "' . $gallery[0]['name'] . '" was deleted successfully.');
					header('Content-type: application/json');
					echo json_encode($json);
				}
				else
				{
					$json = array('code' => 1, 'message' => 'An error has occurred - the gallery has not been deleted.');
					header('Content-type: application/json');
					echo json_encode($json);
				}
			}
			else
			{
				$json = array('code' => 3, 'message' => 'You must be logged in to perform this action.');
				header('Content-type: application/json');
				echo json_encode($json);
			}
		}
		else
		{
			redirect('home');
		}
	}
	

}