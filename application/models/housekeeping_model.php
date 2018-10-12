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

	function AllRoomS($statusid=0,$roomid=0)
	{	//RoomStatusHousekeeping(RoomId,RoomNumber)
		$allRoom=$this->db->query("select * from manage_property where ($roomid=0 or property_id =$roomid ) and hotel_id=".hotel_id())->result_array();
		$allRoomS=array();

		$i=0;
		foreach ($allRoom as $value) {

			$RoomsNumber=(explode(",", $value['existing_room_number']));

			foreach ($RoomsNumber as $Number) {

				if(strlen($Number)>0)
				{
					$Room=$this->db->query("select `RoomStatusHousekeepingString` (".$value['property_id'].",'$Number') status,`RoomStatusHousekeeping` (".$value['property_id'].",'$Number') statusid ,`RoomStatusHousekeepingColor` (".$value['property_id'].",'$Number') Color ")->row_array();
					if($statusid==-1)
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

		$pk=explode(',',$datos['pk']);
		$value=$datos['value'];

		$data['RoomId']=$pk[0];
		$data['RoomNumber']=$pk[1];
		$data['HousekeepingStatusId']=$value;
		$data['UserId']=user_id();

		$result['success']=false;
		$result['message']='Something went Wrong';
		if(insert_data('housekeepingroomstatus',$data))
		{	$color=$this->db->query("select Color from housekeepingstatus where HousekeepingStatusId=".$value)->row_array();
			$result['success']=true;
			$result['color']=$color['Color'];
		}

		echo json_encode($result);
	}
	function updateStatusBulk($datos)
	{

		try {
			$statusid=$datos['statusbulk'];
			$roomToUpdate=$datos['select'];
			$result['success']=true;
			$result['message']='Something went Wrong';
			foreach ($roomToUpdate as  $value) {
				$room=explode(',',$value);
				$data['RoomId']=$room[0];
				$data['RoomNumber']=$room[1];
				$data['HousekeepingStatusId']=$statusid;
				$data['UserId']=user_id();
				insert_data('housekeepingroomstatus',$data);
			}
			$color=$this->db->query("select Color,Name from housekeepingstatus where HousekeepingStatusId=".$statusid)->row_array();
			$result['color']=$color['Color'];
			$result['name']=$color['Name'];
			$result['id']=$statusid;
			echo json_encode($result);
		} catch (\Exception $e)
		{
			$result['success']=false;
			echo json_encode($result);
		}



	}
	public function HousekeepingStatusList()
	{
		$AllStatus=$this->db->query("select HousekeepingStatusId value, Name text from housekeepingstatus where HotelId=0 or HotelId =".hotel_id())->result_array();
		return $AllStatus;

	}
}
