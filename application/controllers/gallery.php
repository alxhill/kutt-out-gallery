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
		$this->load->library(array('session', 'view','form_validation'));
		$this->load->model('gallery_model', 'gallery');
		$this->load->model('photo_model','photo');
		$this->load->model('video_model','video');
		$this->view->theme('tobyelwes');
	}
	
	/**
	 * Check if the logged_in cookie is set.
	 * 
	 * @access private
	 * @return bool
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
	 * Load static pages, often uses routes to make cleaner URLs.
	 * 
	 * @param string $page page to display.
	 */
	function static_page($page)
	{
		if ($this->session->flashdata('logout'))
		{
			$this->view->message('success', 'You have successfully logged out.')->load('home');
			exit;
		}
		elseif (file_exists(APPPATH.'views/'.$this->view->theme.'/'.$page.'.php'))
		{
			$this->view->load($page);
		}
		else
		{
			echo 'Looking for:'.APPPATH.'views/'.$this->view->theme.$page.'.php';
			$this->view->template('error')->data(array('head' => 'Page not found','description' => 'No page matching that name could be found'))->load();
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
		if (!$_POST)
		{
			redirect('admin');
		}
		
		// Set up and load the upload library.
		$config['upload_path'] = './assets/upload/';
		$config['allowed_types'] = 'gif|jpeg|jpg|png';
		$config['max_size'] = '20000';
		$config['max_width'] = '1024';
		$config['max_height'] = '800';
		$this->load->library('upload', $config);
		
		// Get the gallery's name.
		$g_name = $this->gallery->name($this->input->post('g_id'));
		
		if ( ! $this->upload->do_upload('photo'))
		{
			$this->session->set_flashdata('error',$this->upload->display_errors());
			redirect($g_name.'/edit');
		}
		else
		{
			$upload_data = $this->upload->data();
			
			// Set the file location for zebra and for the post upload view.
			$normal_loc = 'assets/upload/' . $upload_data['file_name'];
			$thumb_loc = 'assets/upload/' . $upload_data['raw_name'] . '_thumb' . $upload_data['file_ext'];
			$img = site_url("assets/upload/" . $upload_data['file_name']);
			
			// Check if a custom thumbnail has been uploaded, then either use that or resize the default image.
			if ($this->input->post('custom_thumbnail'))
			{
				$t_config['upload_path'] = $config['upload_path'];
				$t_config['allowed_types'] = $config['allowed_types'];
				$t_config['file_name'] = $upload_data['raw_name'] . '_thumb' . $upload_data['file_ext'];
				$t_config['max_size'] = '2000';
				$t_config['max_width'] = '120';
				$t_config['max_height'] = '80';
				
				$this->upload->initialize($t_config);
				
				if ( ! $this->upload->do_upload('thumbnail'))
				{
					$this->session->set_flashdata('error',$this->upload->display_errors());
					redirect($g_name.'/edit');
				}
				
				
			}
			else
			{
				// Resize and crop the image with the zebra library
				$this->load->library('zebra');
				$this->zebra->setup($normal_loc,$thumb_loc, array('preserve_aspect_ratio' => TRUE, 'enable_smaller_images' => TRUE));
				$this->zebra->resize(120, 80, 3);
			}
			
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
			// Get the post data
			$g_title = $this->input->post('title');
			$g_desc = $this->input->post('description');
			$type = $this->input->post('type');
			
			// Set up the form validation
			$this->form_validation->set_rules('title', 'gallery name', 'required|max_length[10]');
			$this->form_validation->set_rules('description', 'gallery description', 'max_length[150]');
			
			// Run the validation and redirect to admin if an error occurs.
			if ( ! $this->form_validation->run())
			{
				$this->session->set_flashdata('error', validation_errors());
				redirect('admin');
			}
			
			// Create the gallery
			$success = $this->gallery->create($g_title,$g_desc,$type);
			if ($success)
			{
				$this->session->set_flashdata('success','You have successfully created new gallery.');
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
			// Validation rules
			$this->form_validation->set_rules('g_name','gallery name','required|max_length[10]');
			$this->form_validation->set_rules('g_description','gallery description','max_length[120]');
			
			// Check the rules work, otherwise return to the gallery with an error
			if (!$this->form_validation->run())
			{
				$this->session->set_flashdata('error',validation_errors());
				redirect('admin');
			}
			
			// Get the post data
			$id = $this->input->post('g_id');
			$description = $this->input->post('g_description');
			$name = $this->input->post('g_name');
			
			//update the gallery
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
	 * 
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
	 * @param string $g_name gallery name
	 */
	function show_gallery($g_name)
	{
		$g_name = rawurldecode($g_name);
		
		$gallery_info = $this->gallery->info($g_name);
		$g_id = $gallery_info->id;
		
		if (!$gallery_info)
		{
			$this->view->template('error')->title('No gallery found')->data(array('head' => 'Not found', 'description' => "A gallery called {$g_name} could not be found."));
			$this->view->load();
		}
		else
		{
			$all = $this->photo->get($g_id);
			if ( ! $all)
			{	
				$this->view->template('error')->title('No gallery found')->data(array('head' => 'No content', 'description' => "There is no content to display in this gallery."));
				$this->view->load();
			}
			else
			{
				
				$data = array('gallery_info' => $gallery_info, 'image_data' => $all);
				if ($gallery_info->type == 2)
				{
					$data['type'] = 'video';
					$data['video_data'] = $this->video->get($g_id,'asc');
					$this->view->gallery_type = 'video';
				}
				else
				{
					$data['type'] = 'photo';
					$this->view->gallery_type = 'photo';
				}
				
				$this->view->data($data)->title($g_name)->load('show_gallery');
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
		// If there's a space in the name, replace it with a normal space.
		$g_name = rawurldecode($g_name);
		
		if ($this->_login_check())
		{
			// Check if any flashdata is present - if so, display it as a message.
			if ($this->session->flashdata('success'))
			{
				$this->view->message('success',$this->session->flashdata('success'));
			}
			else if ($this->session->flashdata('error'))
			{
				$this->view->message('error',$this->session->flashdata('error'));
			}
			
			$g_info = $this->gallery->info($g_name);
			$data['user'] = $this->session->userdata('user');
			$data['g_info'] = $g_info;
			
			$g_info = $this->gallery->info($g_name);
			if ($g_info->type == 1)
			{
				$data['image_data'] = $this->photo->get($g_info->id,'asc');
			}
			elseif ($g_info->type == 2)
			{
				$data['video_data'] = $this->video->get($g_info->id,'asc');
			}
			$this->view->data($data)->title("Edit gallery {$g_name}")->load('edit');
		}
		else
		{
			$this->view->template('error')->title('Not permitted')->data(array('head' => 'Forbidden', 'description' => 'You do not have permission to view this page'));
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
			$this->view->message('error',$this->session->flashdata('error'));
		}
		
		if ($this->_login_check())
		{
			$this->view->template('admin')->title('Galleries')->data(array('galleries' => $galleries));
			$this->view->load();
		}
		else
		{
			$this->view->template('error')->title('Not permitted')->data(array('head' => 'Forbidden', 'description' => 'You do not have permission to view this page'));
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
			if ($this->_login_check())
			{
				$id = $this->input->post('id');
				$type = $this->input->post('type');
			
				header('Content-type: application/json');
			
				if ($type == 'photo')
				{
					$photo = $this->photo->delete($id);
					if ($photo)
					{
						$json = array('code' => 0, 'id' => $id, 'title' => $photo->title);
					}
					else
					{
						$json = array('code' => 1, 'message' => 'A error deleting the photo has occurred.');
					}
				}
				elseif ($type == 'video')
				{
					$video = $this->video->delete($id);
					if ($video)
					{
						$json = array('code' => 0, 'id' => $id, 'title' => $video->title);
					}
					else
					{
						$json = array('code' => 1, 'message' => 'An error deleting the video has occurred.');
					}
				}
				else
				{
					$json = array('code' => 1, 'message' => 'An error has occurred - unrecognised type.');				
				}
			
			}
			else
			{
				$json = array('code' => 1, 'message' => 'You must be logged in to send this request.');
			}
			
			echo json_encode($json);
			
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
			header('Content-type: application/json');
			
			if ($this->_login_check())
			{
				$id = $this->input->post('id');
				$title = $this->input->post('title');
				$type = $this->input->post('type');
				if ($type == 'photo')
				{
					$this->photo->update($id,$title);
					$json = array('code' => 0);
				}
				elseif ($type == 'video')
				{
					$this->video->update($id,$title,$this->input->post('description'));
					$json = array('code' => 0);
				}
				else
				{
					$json = array('code' => 1, 'message' => 'Unrecognised data type.');
				}
			
			}
			else
			{
				$json = array('code' => 1,'message'=>'You must be logged in to perform this action.');
			}
			
			echo json_encode($json);
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
				if ($gallery)
				{
					$json = array('code' => 0, 'message' => 'The gallery "' . $gallery->name . '" was deleted successfully.');
				}
				else
				{
					$json = array('code' => 1, 'message' => 'An error has occurred - the gallery has not been deleted.');
				}
			}
			else
			{
				$json = array('code' => 3, 'message' => 'You must be logged in to perform this action.');
			}
			
			header('Content-type: application/json');
			echo json_encode($json);
			
		}
		else
		{
			redirect('home');
		}
	}
	
	/**
	 * Reorders a set of photos or videos based on post data. Works only when called through AJAX.
	 */
	function  ajax_reorder()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$key = array_keys($_POST);
				if(is_string($key = $key[0]))
				{
					
					// Set up some variables to use later
					$parts = explode('_', $key);
					$type = $parts[0];
					$g_id = $parts[1];
					
					// Grab the POST data for the key
					$post_array = $this->input->post($key);
					
					// Get the $order to have the key and ids only 
					foreach($post_array as $key => $val)
					{
						$order[$key] = substr($val,7);
					}
					unset($order[0]);
					
					// Call the right reorder function
					if ($type == 'photo')
					{
						$success = $this->photo->order($g_id, $order);
					}
					elseif ($type == 'video')
					{
						$success = $this->video->order($g_id, $order);
					}
					
					if ($success)
					{
						$json = array('code' => 0);
					}
					else
					{
						$json = array('code' => 1, 'message' => 'There was an error reordering the elements. Please try again.');
					}
					
				}
				else
				{
					$json = array('code' => 1, 'message' => 'There was a problem performing your request. Please refresh and try again.');
				}
				
			}
			else
			{
				$json = array('code' => 3, 'message' => 'You must be logged in to perform this action.');
			}
			
			header('Content-type: application/json');
			echo json_encode($json);
			
		}
		else
		{
			redirect('home');
		}
	}
	
	/**
	 * Reorder the galleries based on post data when called through AJAX.
	 */
	function ajax_reorder_galleries()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$post_array = $this->input->post('gallery');
				
				
				foreach ($post_array as $gallery)
				{
					$order[] = substr($gallery,8);
				}
				$this->gallery->order($order);
				
				$json = array('code' => 0);
				
				//$json = array('code' => -1,'dump' => $order);
			}
			else
			{
				$json = array('code' => 3, 'message' => 'You must be logged in to perform this action.');
			}
			
			header('Content-type: application/json');
			echo json_encode($json);
			
		}
		else
		{
			redirect('home');
		}
	}
	
}
