<?php
class Gallery extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','notification', 'neatr'));
		$this->load->library(array('session', 'view'));
		$this->load->model('gallery_model');
	}
	
	/*						   *
	 * 			 			   *
	 * ===[OTHER FUNCTIONS]=== *
	 *						   *
	 *						   */
	
	// Private function to do as described - check if the logged_in cookie is set.
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
			$this->view->template('home')->message('success', 'You have successfully logged out.');
			$this->view->load();
		}
		else
		{
			$this->view->template('home');
			$this->view->load();
			
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
				$this->view->title('Upload a new image')->message('success',$this->session->flashdata('login'))->template('upload');
				$this->view->load();
			}
			else
			{
				$this->view->title('Upload a new image')->template('upload');
				$this->view->load();
			}
		}
		else
		{
			$this->view->template('login_form')->title('Log in')->message('error','You must be logged in to view this page');
			$this->view->load();
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
			
			$data = array('upload_data' => $this->upload->data(), 'link' => $link);
			$this->view->template('post_upload')->title('Image uploaded')->message('success','Image uploaded successfully!')->data($data);
			$this->view->load();
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
			$this->view->template('home')->title('Home')->message('error','You must be <a href="gallery/login">logged in</a> to view this page.');
			$this->view->load();
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
	
	function show_hide($action,$g_id)
	{
		if ($action == 'hide')
		{
			$this->gallery_model->hide_gallery($g_id);
		}
		else if ($action == 'show')
		{
			$this->gallery_model->show_gallery($g_id);
		}
		else
		{
			$this->session->set_flashdata('error','Invalid function');
		}
		redirect('admin');
	}
	
	// Get and show a gallery as specified by the ID in the URL
	function show_gallery($g_name)
	{
		$gallery_info = $this->gallery_model->get_gallery_info($g_name);
		if (!$gallery_info)
		{
			$this->view->template('login_form')->title('No gallery found')->message('notice',"No gallery with the name \"{$g_name}\" could be found.");
			$this->view->load();
		}
		else
		{
			$all = $this->gallery_model->get_all_images($gallery_info[0]['id']);
			if ( ! $all)
			{	
				$this->view->template('login_form')->title('No images to display')->message('notice','There are no photos to display');
				$this->view->load();
			}
			else
			{
				$this->view->template('show_gallery')->title($g_name)->data(array('gallery_info' => $gallery_info[0], 'image_data' => $all));
				$this->view->load();
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
	} // END OF EDIT
	
	// Show the admin page poplated by all the galleries in the database
	function admin()
	{
		$galleries = $this->gallery_model->get_all_galleries();
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
	} // END OF ADMIN
	
	/*						  *
	 * 						  *
	 * ===[AJAX FUNCTIONS]=== *
	 *						  *
	 *						  */
	
	function ajax_delete()
	{
		if (IS_AJAX)
		{
			$photo_id = $this->input->post('id');
			$image = $this->gallery_model->delete_image($photo_id);
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
	
	function ajax_update()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$photo_id = $this->input->post('id');
				$photo_title = $this->input->post('title');
				$this->gallery_model->change_title($photo_id,$photo_title);
			
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
	
	function ajax_gallery_delete()
	{
		if (IS_AJAX)
		{
			if ($this->_login_check())
			{
				$g_id = $this->input->post('id');
				$gallery = $this->gallery_model->delete_gallery($g_id);
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