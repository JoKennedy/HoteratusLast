<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');
class housekeeping extends Front_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('housekeeping_model');
	}


	function index()
	{
		redirect(base_url().'channel/dashboard');
	}

	function roomstatus()
	{
    	is_login();
    	$data['page_heading'] = 'Housekeeping-Rooms Status';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['AllRooms']=get_data('manage_property',array('hotel_id'=>hotel_id()))->result_array();
    	$this->views('housekeeping/roomstatus',$data);
	}

	function saveStatus()
	{	
		$datos=$_POST;
		$this->housekeeping_model->saveStatus($datos);
	}
}