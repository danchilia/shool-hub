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
$route['default_controller'] = 'install';
$route['404_override'] = 'errors';
$route['translate_uri_dashes'] = FALSE;
$route['purchase-orders'] = 'PurchaseOrder/index';
$route['purchase-orders/create'] = 'PurchaseOrder/create';
$route['purchase-orders/view/(:num)'] = 'PurchaseOrder/view/$1';
$route['purchase-orders/edit/(:num)'] = 'PurchaseOrder/edit/$1';
$route['purchase-orders/submit_for_approval/(:num)'] = 'PurchaseOrder/submit_for_approval/$1';
$route['purchase-orders/approve/(:num)'] = 'PurchaseOrder/approve/$1';
$route['purchase-orders/reject/(:num)'] = 'PurchaseOrder/reject/$1';
$route['purchase-orders/mark_sent/(:num)'] = 'PurchaseOrder/mark_sent/$1';
$route['purchase-orders/mark_delivered/(:num)'] = 'PurchaseOrder/mark_delivered/$1';
$route['purchase-orders/mark_paid/(:num)'] = 'PurchaseOrder/mark_paid/$1';
$route['purchase-orders/cancel/(:num)'] = 'PurchaseOrder/cancel/$1';
$route['purchase-orders/send_email/(:num)'] = 'PurchaseOrder/send_email/$1';
$route['purchase-orders/print_lpo/(:num)'] = 'PurchaseOrder/print_lpo/$1';
$route['purchase-orders/suppliers'] = 'PurchaseOrder/suppliers';
$route['purchase-orders/supplier_delete/(:num)'] = 'PurchaseOrder/supplier_delete/$1';
$route['mpesa-callback/stk'] = 'mpesa_callback/stk_callback';
$route['biometric-api/push'] = 'biometric_api/push';
$route['biometric-api/ping'] = 'biometric_api/ping';
$route['virtual_class'] = 'VirtualClass/index';
$route['virtual_class/(:any)'] = 'VirtualClass/$1';
$route['bus_tracking'] = 'BusTracking/index';
$route['bus_tracking/(:any)'] = 'BusTracking/$1';
$route['bus-api/location'] = 'Bus_api/location';
$route['attendance/save_student_ajax'] = 'attendance/save_student_ajax';
$route['attendance/save_staff_ajax'] = 'attendance/save_staff_ajax';
