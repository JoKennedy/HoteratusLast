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
		$data['Rooms']= get_data('manage_property',array('hotel_id'=>hotel_id()))->result_array();
		$data['AllChannelConected']=$this->db->query("SELECT a.*,b.channel_name 
					FROM user_connect_channel a
					left join manage_channel b on a.channel_id=b.channel_id
					where hotel_id=".hotel_id()." order by b.channel_name")->result_array();
		$this->views('channel/bulkupdate',$data);
	}


	function bulkUpdateProcess()
	{
		echo var_dump($_POST);


		return;

		$daterange=count($_POST['date1Edit']);
		for ($i=0; $i < $daterange; $i++) { 

			echo 'Range'.($i+1).'<br>';
			echo 'start'.$_POST['date1Edit'][$i].'<br>';
			echo 'End'.$_POST['date2Edit'][$i].'<br>';
			echo'---------------------<br>';
		}

		$this->session->set_flashdata('bulk_success', 'Bulk update has been updated successfully!!!');
	}

}