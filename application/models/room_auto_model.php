<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class room_auto_model extends CI_Model
{
	function Assign_room($HotelID='',$roomid,$checkin,$checkout)
	{
			$checkout= date('Y-m-d',strtotime($checkout."-1 days"));
			$AllRoomNumbers=$this->db->query("select existing_room_number from manage_property where property_id=$roomid")->row_array();

			if(isset($AllRoomNumbers['existing_room_number']))
			{
				$AllRoomNumbers= explode(',', $AllRoomNumbers['existing_room_number'] );

				$AllRoomUseds=$this->db->query("select roomnumber from roomnumberused where roomid=$roomid and (checkin between '$checkin' and  '$checkout' or checkout-1 between '$checkin' and  '$checkout')")->result_array();
				$AllRoomUsed='';
				
				foreach ($AllRoomUseds as $value) {
					$AllRoomUsed .= (strlen($AllRoomUsed)>0?',':'').$value['roomnumber'];
				}
				$AllRoomUsed = explode(',',$AllRoomUsed );

				
				$valor='';
				foreach ($AllRoomNumbers as  $value) 
				{
					
					if(!in_array($value, $AllRoomUsed))
					{
						$valor= $value;
						return $valor;
						break;
						
					}
				}


				return $valor;

			}
			else
			{
				return '';
			}			
			
	}
	function allRoomAvailable($HotelID='',$roomid,$checkin,$checkout)
	{
		$checkout= date('Y-m-d',strtotime($checkout."-1 days"));
			$AllRoomNumbers=$this->db->query("select existing_room_number from manage_property where property_id=$roomid")->row_array();

			if(isset($AllRoomNumbers['existing_room_number']))
			{
				$AllRoomNumbers= explode(',', $AllRoomNumbers['existing_room_number'] );

				$AllRoomUseds=$this->db->query("select roomnumber from roomnumberused where roomid=$roomid and (checkin between '$checkin' and  '$checkout' or checkout-1 between '$checkin' and  '$checkout')")->result_array();
				$AllRoomUsed='';

				foreach ($AllRoomUseds as $value) {
					
					if(strlen(trim($value['roomnumber']))>0)
					{
						$AllRoomUsed .= (strlen($AllRoomUsed)>0?',':'').$value['roomnumber'];
					}
					
				}

			
				if (strlen($AllRoomUsed)>0) {
					$AllRoomUsed = explode(',',$AllRoomUsed );
				}
				else
				{
					$AllRoomUsed=array();
					return $AllRoomNumbers;
				}
				

				
				$valor='';
				foreach ($AllRoomNumbers as  $value) 
				{
					if (strlen(trim($value))>0) {
						if(!in_array($value, $AllRoomUsed))
						{	
							$valor .=  (strlen($valor)>0?',':'').$value;						
						}
					}
					
				}

				if(strlen($valor)>0)
				{
					$allRoomsAvailable=explode(',',$valor );
					return $allRoomsAvailable;
				}
				else
				{
					return array();
				}

			}
			else
			{
				return array();
			}
	}

	function RoomUsed($RoomId='',$ArrivalDate='',$DepartureDate='',$hotelID='')
	{
		$AllRoomUsed=array();

		$Booking = $this->db->query("select  distinct a.RoomNumber from import_reservation_BOOKING_ROOMS a
									left join import_mapping_BOOKING b on a.id=b.B_room_id and a.rate_id = b.B_rate_id
									left join roommapping c on b.import_mapping_id = c.import_mapping_id 
									left join manage_property d on c.property_id=d.property_id
									where a.hotel_hotel_id =$hotelID and  arrival_date between STR_TO_DATE('$ArrivalDate' ,'%Y-%m-%d') and STR_TO_DATE('$DepartureDate' ,'%Y-%m-%d') and d.property_id = ".$RoomId)->result_array();


		$AllRoomUsed =array_merge($Booking,$AllRoomUsed);

		$Expedia=$this->db->query("select  distinct a.RoomNumber from import_reservation_EXPEDIA a
									left join import_mapping b on a.roomTypeID=b.roomtype_id 
									left join roommapping c on b.map_id = c.import_mapping_id 
									left join manage_property d on c.property_id=d.property_id
									where a.hotel_id =$hotelID and  arrival between STR_TO_DATE('$ArrivalDate' ,'%Y-%m-%d') and STR_TO_DATE('$DepartureDate' ,'%Y-%m-%d') and d.property_id = ".$RoomId)->result_array();

		$AllRoomUsed =array_merge($Expedia,$AllRoomUsed);

		$Manual=$this->db->query("select  distinct a.RoomNumber from manage_reservation a
		left join manage_property d on a.room_id=d.property_id
		where a.hotel_id =$hotelID and  STR_TO_DATE(a.start_date ,'%d/%m/%Y')  between STR_TO_DATE('$ArrivalDate' ,'%d/%m/%Y') and STR_TO_DATE('$DepartureDate' ,'%d/%m/%Y') and d.property_id = ".$RoomId)->result_array();

		$AllRoomUsed =array_merge($Manual,$AllRoomUsed);



		$Despegar='';
		$Airbnb='';


		return $AllRoomUsed;
		

	}




}
?>