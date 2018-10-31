<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');

class template extends Front_Controller
{
	public function __construct()
   {
		parent::__construct();	
		
	}


	function admintemplate()
	{
		is_login();
    	$data['page_heading'] = 'Calendar';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$this->views('template/template',$data);
	}


}