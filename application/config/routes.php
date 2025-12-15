<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['logout'] = 'login/logout';
$route['dash'] = 'dashboard';

$route['change-password'] = 'login/change_password';
$route['dash'] = 'dashboard';
$route['get-data'] = 'general/get_data';
$route['update-data'] = 'general/update_data';
$route['insert-data'] = 'general/insert_data';
$route['delete-record'] = 'general/delete_record';
$route['get-content'] = 'general/get_content';

$route['employee-details-add'] = 'employee/employee_details_add';
$route['employee-details-edit/(:num)'] = 'employee/employee_details_edit/$1';
$route['employee-details-list'] = 'employee/employee_details_list';

$route['supervisor-details-list'] = 'supervisor/supervisor_details_list';
$route['supervisor-details-edit/(:num)'] = 'supervisor/supervisor_details_edit/$1';
$route['supervisor-details-add'] = 'supervisor/supervisor_details_add';


$route['employee-category-list'] = 'master/employee_category_list';
$route['employee-category-list/(:num)'] = 'master/employee_category_list/$1';

$route['employee-skill-list'] = 'master/employee_skill_list';
$route['employee-skill-list/(:num)'] = 'master/employee_skill_list/$1';


$route['pincode-list'] = 'master/pincode_list';
$route['pincode-list/(:num)'] = 'master/pincode_list/$1';


$route['sports-list'] = 'master/sports_list';
$route['sports-list/(:num)'] = 'master/sports_list/$1';


$route['health-issues-list'] = 'master/health_issues_list';
$route['health-issues-list/(:num)'] = 'master/health_issues_list/$1';

$route['disability-list'] = 'master/disability_list';
$route['disability-list/(:num)'] = 'master/disability_list/$1';


$route['hobbies-list'] = 'master/hobbies_list';
$route['hobbies-list/(:num)'] = 'master/hobbies_list/$1';

$route['business-list'] = 'master/business_list';
$route['business-list/(:num)'] = 'master/business_list/$1';

$route['talent-list'] = 'master/talent_list';
$route['talent-list/(:num)'] = 'master/talent_list/$1';


$route['user-list'] = 'master/user_list';
$route['user-list/(:num)'] = 'master/user_list/$1';

  
$route['supervisor-terms'] = 'master/supervisor_terms';
$route['supervisor-terms/(:num)'] = 'master/supervisor_terms/$1';
 
$route['company-add'] = 'company/company_add'; 
$route['company-list'] = 'company/company_list';
 