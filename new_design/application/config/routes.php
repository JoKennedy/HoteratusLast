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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']	=	"channel";
$route['404_override']			=	'my404';
$route['translate_uri_dashes']	=	TRUE;

/* 
$route['^(en|sp|fr|it|ge)/channel']	=	"my404";
$route['^(en|sp|fr|it|ge)/channel/(:any)']	=	"my404";
$route['^(en|sp|fr|it|ge)/channel/(:any)/(:any)']	=	"my404";
$route['^(en|sp|fr|it|ge)/channel/(:any)/(:any)/(:any)']	=	"my404";
$route['^(en|sp|fr|it|ge)/channel/(:any)/(:any)/(:any)/(:any)']	=	"my404";

$route['^(en|sp|fr|it|ge)/admins']	=	"admin/index";
$route['^(en|sp|fr|it|ge)/admins/(:any)']	=	"admin/$1";
$route['^(en|sp|fr|it|ge)/admins/(:any)']	=	"admin/$2";
$route['^(en|sp|fr|it|ge)/admins/(:any)/(:any)']	=	"admin/$1/$2";
$route['^(en|sp|fr|it|ge)/admins/(:any)/(:any)/(:any)']	=	"admin/$1/$2/$3";
$route['^(en|sp|fr|it|ge)/admins/(:any)/(:any)/(:any)/(:any)']	=	"admin/$1/$2/$3/$4";


$route['^(en|sp|fr|it|ge)/(:any)'] 	=	"channel/$2";
$route['^(en|sp|fr|it|ge)/(:any)/(:any)']	=	"channel/$1/$2";
$route['^(en|sp|fr|it|ge)/(:any)/(:any)/(:any)']	=	"channel/$1/$2/$3";
$route['^(en|sp|fr|it|ge)/(:any)/(:any)/(:any)/(:any)']	=	"channel/$1/$2/$3/$4"; */


// URI like '/en/about' -> use controller 'about'
$route['^(en|sp|fr|it|ge)/(.+)$'] = "$2";

// '/en', '/de', '/fr' and '/nl' URIs -> use default controller
$route['^(en|sp|fr|it|ge)$'] = $route['default_controller'];

/* $route['npif_0912'] = "npif_0912/index";
$route['npif_0912/(:any)'] = "npif_0912/$1";
$route['npif_0912/(:any)/(:any)'] = "npif_0912/$1/$2";
$route['npif_0912/(:any)/(:any)/(:any)'] = "npif_0912/$1/$2/$3";
$route['npif_0912/(:any)/(:any)/(:any)/(:any)'] = "npif_0912/$1/$2/$3/$4"; */

/* $route['^(en|es|ro)/(:any)'] = "channel/$1";
$route['^(en|es|ro)/(:any)/(:any)'] = "channel/$1/$2";
$route['^(en|es|ro)/(:any)/(:any)/(:any)'] = "channel/$1/$2/$3";
$route['^(en|es|ro)/(:any)/(:any)/(:any)/(:any)'] = "channel/$1/$2/$3/$4";

$route['^(en|sp|fr|it|ge)/login/(.+)$'] 		=	"channel/login";

$route['^(en|sp|fr|it|ge)/(.+)$']       = 	"$2";
$route['^(en|sp|fr|it|ge)$'] 			= 	$route['default_controller'];
 */



/* $route['en/login'] = "channel/login"; */



/* End of file routes.php */
/* Location: ./application/config/routes.php */