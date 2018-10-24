<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class agoda extends Front_Controller {

	function is_login()
    { 
        if(!user_id())
        redirect(base_url());
        return;
    }
   
   	function is_admin()
    {   
        if(!admin_id())
        redirect(base_url());
        return;
    }
	
	function get_mapping_rooms($connect)
	{

		$this->load->model('agoda_model');
		$data=$this->agoda_model->get_mapping_rooms($connect);
		return $data;
		
	}
	
	function updateRoom()
	{
		
		
		   $data=$_POST;
		   $this->load->model('agoda_model');
		   $update_result=$this->agoda_model->upadte_room($data);
		    if($update_result==1)
			{
				
				
				  echo "<h3 style=color:green>update successfully completed.</h3>";
				
			}
	}
	
	
	function deleteRoom()
	{
		
		
	   $id=$this->input->post('id');
	   $this->load->model('agoda_model');
	   $result=$this->agoda_model->delete_room($id);
	   if($result==1)
	   {
		   
		   echo "<h3 style=color:green>Delete successfully completed.</h3>";  
		   
	   }
	}
	
	
	function save_mapping($data)
	{
		
		$this->load->model('agoda_model');
		$result=$this->agoda_model->save_mapping($data);
		return $result;
	}
	
	
	function maptochannel($channel_id,$property_id)
    {
        require_once(APPPATH.'models/agoda_model.php'); 
        $agoda_model         =   new agoda_model();
        $data['available']      =   get_data('import_mapping_AGODA',array('hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id)))->row_array();
        $data['mapping_values'] =   get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
        $data['agoda']   =    $agoda_model->get_mapping_rooms(insep_decode($channel_id),'update');
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        return $data;
    }

	
}