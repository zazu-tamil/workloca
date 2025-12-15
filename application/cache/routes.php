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
$route['default_controller'] = 'welcome';

$route['login'] = 'welcome/login';
$route['logout'] = 'welcome/logout';

$route['complaint-register'] = "welcome/complaint_register";
 
 
$route['country'] = "welcome/get_ajax_country_list";
$route['city/(:any)'] = "welcome/get_ajax_city_list/$1";

$route['pin'] = "welcome/get_ajax_pincode_list";


$route['pin/(:any)'] = "welcome/get_ajax_pin_list/$1";
$route['location/(:any)'] = "welcome/get_ajax_location_list/$1";
$route['franchisee-info'] = "welcome/get_ajax_franchisee_info";

//$route['pickup-list'] = "welcome/pick_up_list";
//$route['pickup-list/(:num)'] = "welcome/pick_up_list/$1";


$route['pickup-list'] = "welcome/pick_up_list_v2";
$route['pickup-list/(:num)'] = "welcome/pick_up_list_v2/$1";


$route['pb-pickup-list'] = "welcome/pb_pick_up_list";
$route['pb-pickup-list/(:num)'] = "welcome/pb_pick_up_list/$1";

$route['pick-pack-list'] = "welcome/pick_pack_list/$1";
$route['pick-pack-list/(:num)'] = "welcome/pick_pack_list/$1";

$route['customer-pick-pack-list'] = "welcome/customer_pick_pack_list";
$route['customer-pick-pack-list/(:num)'] = "welcome/customer_pick_pack_list/$1";

$route['live-pickup'] = "welcome/live_pickup_list";

$route['mail-test'] = "welcome/mail_test";

$route['todays-pick-dely'] = "welcome/todays_pickup_delivery_list";

$route['pickup-delivery'] = "welcome/pu_pickup_delivery_list";

$route['franch-enquiry'] = "welcome/insert_franchise_enquiry";
$route['franchise-enquiry'] = "welcome/franchise_enquiry_list";
$route['franchise-enquiry/(:num)'] = "welcome/franchise_enquiry_list/$1";

$route['quick-quote-list'] = "welcome/quick_quote_list";
$route['quick-quote-list/(:num)'] = "welcome/quick_quote_list/$1";


$route['country-list'] = "welcome/country_list";
$route['country-list/(:num)'] = "welcome/country_list/$1";

$route['state-list'] = "welcome/state_list";
$route['state-list/(:num)'] = "welcome/state_list/$1";

$route['pay-method-list'] = "welcome/pay_method_list";
$route['pay-method-list/(:num)'] = "welcome/pay_method_list/$1";

$route['service-provider-list'] = "welcome/service_provider_list";
$route['service-provider-list/(:num)'] = "welcome/service_provider_list/$1";

$route['international-service-provider-list'] = "welcome/international_service_provider_list";
$route['international-service-provider-list/(:num)'] = "welcome/international_service_provider_list/$1";

$route['zone-list'] = "welcome/zone_list";
$route['zone-list/(:num)'] = "welcome/zone_list/$1";

$route['sp-zone-country-list'] = "welcome/sp_zone_country_list";
$route['sp-zone-country-list/(:num)'] = "welcome/sp_zone_country_list/$1";

$route['agent-list'] = "welcome/agent_list";
$route['agent-list/(:num)'] = "welcome/agent_list/$1";

$route['pincode-list'] = "welcome/pincode_list";
$route['pincode-list/(:num)'] = "welcome/pincode_list/$1";

$route['international-rate'] = "welcome/international_rate_list";
$route['international-rate/(:num)'] = "welcome/international_rate_list/$1";


$route['international-rate-v2'] = "welcome/international_rate_list_v2";
$route['international-rate-v2/(:num)'] = "welcome/international_rate_list_v2/$1";

$route['package-type-list'] = "welcome/package_type";
$route['package-type-list/(:num)'] = "welcome/package_type/$1";

$route['news-list'] = "welcome/news_list";
$route['news-list/(:num)'] = "welcome/news_list/$1";

$route['package-weight-list'] = "welcome/package_weight";
$route['package-weight-list/(:num)'] = "welcome/package_weight/$1";

$route['get-tracking/(:num)'] = "welcome/get_tracking/$1/$2";

$route['domestic-rate-v2'] = "welcome/domestic_rate_v2";

$route['domestic-rate-v3'] = "welcome/domestic_rate_v3";



//$route['domestic-rate'] = "welcome/domestic_rate";
//$route['domestic-rate/(:num)'] = "welcome/domestic_rate/$1";
$route['packing-charges'] = "welcome/packing_charges";
$route['non-doc-sub-category-charges-list'] = "welcome/non_dox_sub_category_charges";
$route['non-doc-sub-category-charges-list/(:num)'] = "welcome/non_dox_sub_category_charges/$1";

$route['book-my-courier'] = "welcome/bookmycourier";
$route['book-my-courier-v2'] = "welcome/bookmycourier_v2";

$route['quick-quote'] = "welcome/quick_quote";

$route['call-alert/(:num)'] = "welcome/call_alert_sms/$1";

$route['sms-balance'] = "welcome/sms_balance";

$route['get-news'] = "welcome/get_news_updates";

$route['get-charges'] = "welcome/get_courier_charges";
$route['get-charges-v2'] = "welcome/get_courier_charges_v2";

$route['get-charges-v3'] = "welcome/get_courier_charges_v3";

$route['get-distance'] = "welcome/get_distance";
$route['get-distance/(:num)'] = "welcome/get_distance/$1/$2";

$route['pickup-report'] = "welcome/pickup_report";
$route['pl-report'] = "welcome/pl_report";
$route['booking-summary'] = "welcome/booking_summary";

$route['courier-booking-report'] = "welcome/courier_booking_report";
$route['courier-booking-report/(:num)'] = "welcome/courier_booking_report/$1"; 

$route['franchise-enquiry-report'] = "welcome/franchise_enquiry_report"; 
$route['franchise-enquiry-report/(:num)'] = "welcome/franchise_enquiry_report/$1"; 

$route['agent-report'] = "welcome/agent_report"; 
$route['agent-report/(:num)'] = "welcome/agent_report/$1"; 

$route['agent-transaction-report'] = "welcome/agent_transaction_report"; 
$route['agent-transaction-report/(:num)'] = "welcome/agent_transaction_report/$1"; 

$route['agent-pay-request'] = "welcome/agent_payment_request"; 
$route['agent-pay-request/(:num)'] = "welcome/agent_payment_request/$1"; 

$route['agent-pay-approval'] = "welcome/agent_payment_approval"; 
$route['agent-pay-approval/(:num)'] = "welcome/agent_payment_approval/$1"; 

$route['pick-pack-report'] = "welcome/pick_pack_report"; 
$route['pick-pack-report/(:num)'] = "welcome/pick_pack_report/$1"; 

$route['pickup-recover'] = "welcome/pickup_recover"; 
$route['pickup-recover/(:num)'] = "welcome/pickup_recover/$1"; 

$route['service-request'] = 'welcome/insert_service_request';

$route['work-category'] = 'welcome/work_category_list';
$route['work-category/(:any)'] = 'welcome/work_category_list/$1';

$route['cash-in'] = 'welcome/cash_in';
$route['cash-in/(:any)'] = 'welcome/cash_in/$1';

$route['cash-out'] = 'welcome/cash_out';
$route['cash-out/(:any)'] = 'welcome/cash_out/$1';

$route['cash-ledger'] = 'welcome/cash_ledger'; 

$route['account-head'] = 'welcome/account_head_list';
$route['account-head/(:any)'] = 'welcome/account_head_list/$1';

$route['sub-account-head'] = 'welcome/sub_account_head_list';
$route['sub-account-head/(:any)'] = 'welcome/sub_account_head_list/$1';

$route['service-type'] = 'welcome/service_type_list'; 
$route['service-type/(:any)'] = 'welcome/service_type_list/$1';

$route['referral-partner'] = 'welcome/referral_partner';
$route['referral-partner/(:any)'] = 'welcome/referral_partner/$1';

$route['service-request'] = 'welcome/service_request_list';
$route['service-request/(:any)'] = 'welcome/service_request_list/$1';

$route['gst-invoice'] = 'welcome/gst_invoice_list';
$route['gst-invoice/(:any)'] = 'welcome/gst_invoice_list/$1';
$route['print-invoice/(:any)'] = 'welcome/print_invoice/$1';
$route['generate-invoice/(:any)'] = 'welcome/generate_invoice/$1';

$route['generate-waybill/(:any)'] = 'welcome/generate_waybill/$1';
$route['waybill/(:any)'] = 'welcome/way_bill/$1';

$route['generate-waybill-pdf/(:any)'] = 'welcome/generate_waybill_pdf/$1';

$route['create-invoice/(:any)'] = 'welcome/create_invoice/$1';

$route['tracking-entry/(:any)'] = 'welcome/tracking_entry/$1';

$route['get-location'] = 'welcome/get_location';

$route['get-pin-location/(:any)'] = 'welcome/get_pincode_state_city/$1';

$route['dash-old'] = 'welcome/dash';

$route['my-dash'] = 'welcome/my_dash';

$route['dash'] = 'dashboard';
$route['dash-v2'] = 'dashboard';

$route['delete-record'] = 'welcome/delete_record';
$route['insert-record'] = 'welcome/insert_record';
$route['restore-record'] = 'welcome/restore_record';
$route['visitor-in/(:any)'] = 'welcome/visitor_in/$1';

$route['visitor-list'] = 'welcome/visitor_list';
$route['visitor-list/(:any)'] = 'welcome/visitor_list/$1';

$route['customer-list'] = 'welcome/customer_list';
$route['customer-list/(:any)'] = 'welcome/customer_list/$1';

$route['get-data'] = 'welcome/get_data';
$route['get-content'] = 'welcome/get_content';
$route['get-content-v2'] = 'welcome/get_content_v2';

$route['user-list'] = 'welcome/user_list';
$route['user-list/(:any)'] = 'welcome/user_list/$1'; 

$route['manager-list'] = 'welcome/manager_list';
$route['manager-list/(:any)'] = 'welcome/manager_list/$1';

$route['pickup-user-list'] = 'welcome/pickup_user_list';
$route['pickup-user-list/(:any)'] = 'welcome/pickup_user_list/$1';
   
$route['pp-user-list'] = 'welcome/pp_customer_user_list';
$route['pp-user-list/(:any)'] = 'welcome/pp_customer_user_list/$1';   

$route['marketing-user-list'] = 'welcome/marketing_user_list';
$route['marketing-user-list/(:any)'] = 'welcome/marketing_user_list/$1';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
