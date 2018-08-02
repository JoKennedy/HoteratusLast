<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class bulkupdate extends Front_Controller
{
	function is_login()
    { 
        if(!user_id())
        redirect(base_url());
        return;
    }

	function viewBulkUpdate()
	{
		$this->is_login();
    	$data['page_heading'] = 'Bulk Update';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$this->views('channel/bulkupdate.php',$data);
	}

}