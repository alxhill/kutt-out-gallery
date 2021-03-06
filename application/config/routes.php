<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There is one reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/

$route['default_controller'] = 'gallery';

$route['home'] = 'gallery/static_page/home';

if (THEME == 'kutt-out')
{
	$route['contact'] = 'gallery/static_page/contact';

	$route['about_me'] = 'gallery/static_page/contact';
}

$route['login'] = 'login/index';

$route['login/submit'] = 'login/submit';

$route['upload'] = 'gallery/add_photo';

$route['gallery/upload'] = 'gallery/upload';

$route['admin'] = 'gallery/admin';

$route['(:any)/edit'] = 'gallery/edit/$1';

$route['(:any)/show'] = 'gallery/show_gallery/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */