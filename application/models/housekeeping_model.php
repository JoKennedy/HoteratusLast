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

	function AllRoomS($statusid=0)
	{	//RoomStatusHousekeeping(RoomId,RoomNumber)
		$allRoom=$this->db->query("select * from manage_property where hotel_id=".hotel_id())->result_array();
		$allRoomS=array();

		$i=0;
		foreach ($allRoom as $value) {
			
			$RoomsNumber=(explode(",", $value['existing_room_number']));

			foreach ($RoomsNumber as $Number) {

				if(strlen($Number)>0)
				{
					$Room=$this->db->query("select `RoomStatusHousekeepingString` (".$value['property_id'].",'$Number') status,`RoomStatusHousekeeping` (".$value['property_id'].",'$Number') statusid ,`RoomStatusHousekeepingColor` (".$value['property_id'].",'$Number') Color ")->row_array();
					if($statusid==0)
					{
						$value['HousekeepingStatus']=$Room['status'];
						$value['HousekeepingStatusId']=$Room['statusid'];
						$value['HousekeepingColor']=$Room['Color'];
						$value['RoomNumber']=$Number;
						$allRoomS[$i]=$value;
						$i++;
					}
					else if($Room['statusid']==$statusid)
					{
						$value['HousekeepingStatus']=$Room['status'];
						$value['HousekeepingStatusId']=$Room['statusid'];
						$value['HousekeepingColor']=$Room['Color'];
						$value['RoomNumber']=$Number;
						$allRoomS[$i]=$value;
						$i++;
					}
				}
				
			}
			
		}

		return $allRoomS;
	}
	function updateStatus($datos)
	{

		
		$data['RoomId']=$datos['roomid'];
		$data['RoomNumber']=$datos['RoomNumber'];
		$data['HousekeepingStatusId']=$datos['StatusId'];
		$data['UserId']=user_id();

		$result['success']=false;
		$result['message']='Something went Wrong';
		if(insert_data('housekeepingroomstatus',$data))
		{
			$result['success']=true;
		}
		
		echo json_encode($result);
	}
}