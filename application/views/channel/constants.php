<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('TBL_USERS','manage_users');
define('TBL_SITE','site_config');
define('TBL_PROPERTY','manage_property');
define('TBL_CHANNEL','manage_channel');
define('TBL_CREDIT','tbl_credit_card');
define('TBL_CUR','currency');
define('TBL_ROOM','room_type');
define('TBL_COUNTRY','country');
define('TBL_STATE','state');
define('TBL_CITY','city');
define('TBL_ACCESS','user_access');
define('TBL_PHOTO','room_photos');
define('TBL_UPDATE','room_update');
define('AMENITIES_TYPE','room_amenities_type');
define('AMENITIES','room_amenities');
define('TBL_PLAN','subscribe_plan');
define('ADMIN','admin');
define('MEAL','meal_plan');
define('RATE','room_refunds');
define('RATE_TYPES','room_rate_types');
define('RATE_TYPES_REFUN','room_rate_types_refund');
define('RATE_BASE','room_rate_types_base');
define('RATE_ADD','room_rate_types_additional');
define('CONFIG','site_config');
define('SUBSCRIBE','subscribers');
define('RESERVATION','manage_reservation');
define('POLICY','policy_types');
define('PCANCEL','policy_cancelation');
define('PDEPOSIT','policy_deposit');
define('POTHERS','policy_others');
define('RESERV','reservation_table');
define('TAX','tax_categories');
define('R_TAX','reservation_tax');
define('OPEATIONS','supported_operations');
define('CHA_PLAN','channel_plan');
define('HOTEL','manage_hotel');
define('ASSIGN','assign_priviledge');
define('MAP','roommapping');
define('WE','welcome_remainder_emails');
define('BILL','bill_info');
define('EX','extras');
define('IN','invoice');
define('EXP_RESERV','import_reservation_EXPEDIA'); 
define('IM_EXP','import_mapping'); 
define('IM_RECO','import_mapping_RECOLINE');
define('CONNECT','user_connect_channel');
define('ICAL','ical_link');
define('BOOKING','import_mapping_BOOKING');
define('REC_RESERV','import_reservation_RECONLINE');
define('IM_GTA','import_mapping_GTA');
define('GTA_RESERV','import_reservation_GTA');
define('ALL','all_channel_connection');
define("MEMBERSHIP","user_membership_plan_details");
define("IM_HOTELBEDS","import_mapping_HOTELBEDS");
define("IM_HOTELBEDS_ROOMS","import_mapping_HOTELBEDS_ROOMS");
define("HBEDS_RESER","import_reservation_HOTELBEDS");

define('BOOK_RESERV','import_reservation_BOOKING'); 
define('BOOK_ROOMS','import_reservation_BOOKING_ROOMS'); 
define('BOOK_ADDON','import_reservation_BOOKING_ADDON'); 
define('ALL_XML','all_channel_xml');
define('B_WAY','booking_one_way_params');
define('CARD','card_details');
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
define('C_URL','channel_urls');
define('IM_BNOW','import_mapping_BNOW');
define('BNOW_RESER','import_reservation_BN0W');
define('IM_TRAVELREPUBLIC', 'import_mapping_travelrepublic');
define('IM_TRAVEL_RESER', 'import_reservation_TRAVELREPUBLIC');
define('IM_TRAVEL_RESER_ROOMS', 'import_reservation_travelrepublic_rooms');
define('MAP_VAL','mapping_values');
define('UAVL','updateAvailability');
define('IM_WBEDS','import_mapping_WBEDS');
define('WBEDS_RESER','import_reservation_WBEDS');
define('ideCHM','HOTELAVAILABILITIES');

//** PMS PARTNER SECTION **//
define('PMS_PART','partners');
define('PMS_PART_HOTEL','parners_hotel');
define('PMS_PART_ROOMS','partners_room_types');
define('PMS_PART_CONNECT','partner_connect_channel');

define('PMS_RECO','pms_import_reconline');
define('PMS_BOOKING','pms_import_booking');
define('PMS_EXP','pms_import_expedia');
define('PMS_EXP_RATE','pms_import_expedia_ratelimit');
define('PMS_EXP_OCCUPANCY','pms_import_expedia_occupancy');
define('PMS_GTA','pms_import_gta');
define('PMS_HBD','pms_import_hotelbeds');
define('PMS_HBD_ROOM','pms_import_hotelbeds_rooms');
define('PMS_MAP','pms_roommapping');
define('PMS_UPDATE','pms_room_update');
define('PMS_MAP_VAL','pms_mapping_field');

define('PMS_REC_RESERV','pms_import_reservation_RECONLINE');
define('PMS_HBD_RESERV', 'pms_import_reservation_HOTELBEDS');
define('PMS_GTA_RESERV','pms_import_reservation_GTA');
define('PMS_EXP_RESER','pms_import_reservation_EXPEDIA');
define('PMS_BOOK_RESERV', 'pms_import_reservation_BOOKING');
define('PMS_BOOK_ROOMS', 'pms_import_reservation_BOOKING_ROOMS');
define('PMS_BOOK_ADDON', 'pms_import_reservation_BOOKING_ADDON');

define('IPWHITELIST', implode(',', include(APPPATH.'config/whitelist.php')));

define('CCTYPES', 'credit_card_types');
define('BANK', 'bank_details');

