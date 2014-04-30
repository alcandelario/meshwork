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



// Event Calendar routing

$route['calendar'] = 'calendar';



// Google Map routing

       $route['asset_map'] = 'asset_map';

$route['asset_map/filter'] = 'asset_map/filter/$1';



// Message Posting routing 

 $route['messages/reply/(:any)'] = 'messages/reply/$1';

  $route['messages/view/(:any)'] = 'messages/view/$1';

  $route['messages/edit/(:any)'] = 'messages/edit/$1';

         $route['messages/post'] = 'messages/post';

          $route['messages/set'] = 'messages/set';

       $route['messages/index/'] = 'messages/index/$1';

 $route['messages/index/(:any)'] = 'messages/index/$1';

$route['messages/delete/(:any)'] = 'messages/delete/$1';

              $route['messages'] = 'messages/index';

     

      

// Providers Routing

$route['providers/set_active/(:num)/(:num)'] = 'providers/set_active/$1';

           $route['providers/update/(:any)'] = 'providers/update/$1';

                  $route['providers/create'] = 'providers/create';

             $route['providers/view/(:any)'] = 'providers/view/$1';

                  $route['providers/filter'] = 'providers/filter/$1';

                         $route['providers'] = 'providers';

             

// Individual Org Member Info
$route['users/profile/(:any)'] = 'users/profile/$1';
   $route['users/view/(:any)'] = 'users/view/$1'; 
 $route['users/filter/(:any)'] = 'users/filter/$1';
        $route['users/filter'] = 'users/filter';
  $route['users/index/(:any)'] = 'users/index/$1';
               $route['users'] = 'users';

              

// ion-auth 2 routing ino

                  $route['login'] = 'public/auth/login';

                 $route['logout'] = 'public/auth/logout';

        $route['change_password'] = 'public/auth/change_password';

        $route['forgot_password'] = 'public/auth/forgot_password';

         $route['reset_password'] = 'public/auth/reset_password';

            $route['create_user'] = 'public/auth/create_user';

           $route['create_group'] = 'public/auth/create_group';

      $route['edit_group/(:any)'] = 'public/auth/edit_group/$1';

       $route['edit_user/(:any)'] = 'public/auth/edit_user/$1';

      $route['deactivate/(:any)'] = 'public/auth/deactivate/$1';

        $route['admin_dashboard'] = 'public/auth/index';

        $route['activate/(:any)'] = 'public/auth/activate/$1'; 

        

// Default Routing       

$route['default_controller'] = 'messages';

            $route['(:any)'] = 'pages/view/$1';





/* End of file routes.php */

/* Location: ./application/config/routes.php */