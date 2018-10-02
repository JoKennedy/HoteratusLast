<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class housekeeping_model extends CI_Model{
	public function __construct()
	{       
		parent::__construct();

	} 

	function saveStatus($datos)
	{
		$data['Name']=$datos['statusname'];
		$data['Code']=$datos['code'];
		$data['Color']=$datos['color'];
		$data['Active']=1;
		$data['HotelId']=hotel_id();

		$result['success']=false;
		$result['message']='Something went Wrong';
		if(insert_data('housekeepingstatus',$data))
		{
			$result['success']=true;
		}

		echo json_encode($result);
		
	}
}