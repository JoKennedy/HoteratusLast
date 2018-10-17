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
		$data['AllStatus']=$this->housekeeping_model->HousekeepingStatusList();
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
		$roomid=$_POST['roomid'];
		$alllist=$this->housekeeping_model->AllRoomS($status,$roomid);
		$html='';
			$html.= '<div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">

                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                                <th>Room Number</th>
																<th class="bulkupdate" style="text-align:center; display:none;">Select</th>
                                <th>Room Type</th>
                                <th style="text-align:center;" >Status</th>
                            </tr>
                        </thead>
                        <tbody>';


            if (count($alllist)>0) {
								$i=0;
                foreach ($alllist as  $value) {
									  $CurrentStatus='<a style="padding: 0px; color:white;" href="#" class="inline_username" data-type="select" data-pk="'
										.$value['property_id'].','.$value['RoomNumber'].'" data-name="HousekeepingStatusId" data-value="'.$value['HousekeepingStatusId'].'" data-source="'.lang_url().'housekeeping/HousekeepingStatusList" data-title="Change Housekeeping Status"></a>';

                    $html.=' <tr id="row'.$value['property_id'].'r'.$i.'" scope="row"  style="background-color:'.$value['HousekeepingColor'].'">
										<th style="text-align:center;" scope="row"><h4><span class="label label-primary">'.$value['RoomNumber'].'</span></h4> </th>
										<td class="bulkupdate" style="text-align:center; display:none;" scope="row"><h4>
										<span class="label label-primary"><input id="'.$value['property_id'].'r'.$i.'" class="select"
										name="select[]" value="'.$value['property_id'].','.$value['RoomNumber'].'" type="checkbox"></span></h4> </td>
                    	<td style="text-align:center;" ><h3><span id="name'.$value['property_id'].'r'.$value['RoomNumber'].'" class="label label-primary">'.$value['property_name'].'</span></h3>  </td>
                    	<td id="'.$value['property_id'].'r'.$value['RoomNumber'].'" style="text-align:center; "> <h3><span  class="label label-primary">'.$CurrentStatus.' <i class="fa fa-exchange-alt"></i></span></h3> </td>
                   		 </tr>  ';
										$i++;
                }

                 $html.='</tbody> </table>  </div></div></div>';
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
	function updateStatusBulk()
	{
		$datos=$_POST;
		$this->housekeeping_model->updateStatusBulk($datos);
	}

	public function HousekeepingStatusList()
	{
			$AllStatus=$this->housekeeping_model->HousekeepingStatusList();

			echo json_encode($AllStatus);
	}


}
