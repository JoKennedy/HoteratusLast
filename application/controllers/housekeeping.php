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
		$data['AllStatus']=$this->db->query("select * from housekeepingstatus where hotelId=0 or HotelId=".hotel_id())->result_array();
    	$this->views('housekeeping/roomstatus',$data);
	}

	function saveStatus()
	{	
		$datos=$_POST;
		$this->housekeeping_model->saveStatus($datos);
	}

	function RoomListHTML()
	{
		
		$status=$_POST['status'];
		$alllist=$this->housekeeping_model->AllRoomS($status);
		$html='';
			$html.= '<div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
              
                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                                <th>Room Number</th>
                                <th>Room Type</th>
                                <th style="text-align:center;" >Status</th>
                            </tr>
                        </thead>
                        <tbody>';
			
							
					
            if (count($alllist)>0) {

                foreach ($alllist as  $value) {

                	$update="'".$value['property_id']."','".$value['RoomNumber']."','".$value['HousekeepingStatusId']."','".$value['property_name']."'";
                    $html.=' <tr id="row'.$value['property_id'].'r'.$value['RoomNumber'].'"scope="row"  style="background-color:'.$value['HousekeepingColor'].'"> <th style="text-align:center;" scope="row"><h4><span class="label label-primary">'.$value['RoomNumber'].'</span></h4> </th> 
                    	<td style="text-align:center;" ><h3><span id="name'.$value['property_id'].'r'.$value['RoomNumber'].'" class="label label-primary">'.$value['property_name'].'</span></h3>  </td> 
                    	<td id="'.$value['property_id'].'r'.$value['RoomNumber'].'" style="text-align:center;"> <h3><span  class="label label-primary"><a style="color:white" onclick="changeStatus('.$update.')" data-toggle="tooltip" data-placement="bottom" title="Change Status">'.$value['HousekeepingStatus'].' <i class="fa fa-exchange-alt"></i></a></span></h3> </td>
                   		 </tr>  ';

                }
                 $html.='</tbody> </table> </div></div></div>';
                 echo $html;
            }
            else
            {
            	$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
            	echo $html;
            }
      
	}

	function updateStatus()
	{	
		$datos=$_POST;
		$this->housekeeping_model->updateStatus($datos);
	}


}