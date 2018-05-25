<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class room_auto_model extends CI_Model
{
	function Assign_room($ChannelID='',$ReservationID='',$HotelID='')
	{

		$RoomNumbers='';
		$RoomUsed ='';


		if($ChannelID==2)
		{
			$RoomNumbers = $this->db->query('select d.existing_room_number,a.*,d.property_id
			from  import_reservation_BOOKING_ROOMS a 
			left join import_mapping_BOOKING b on a.id=b.B_room_id and a.rate_id = b.B_rate_id
			left join roommapping c on b.import_mapping_id = c.import_mapping_id 
			left join manage_property d on c.property_id=d.property_id
			where  a.hotel_hotel_id ='.$HotelID.' and a.roomreservation_id ='.$ReservationID)->row_array();

			$RoomUsed = $this->RoomUsed($RoomNumbers['property_id'],$RoomNumbers['arrival_date'],$RoomNumbers['departure_date'],$HotelID);

		}
		elseif($ChannelID==1)
		{
			$RoomNumbers = $this->db->query('select d.existing_room_number,a.*,d.property_id
			from  import_reservation_EXPEDIA a 
			left join import_mapping b on a.roomTypeID=b.roomtype_id 
			left join roommapping c on b.map_id = c.import_mapping_id 
			left join manage_property d on c.property_id=d.property_id
			where  a.hotel_id ='.$HotelID.' and a.booking_id ='.$ReservationID)->row_array();
			$RoomUsed = $this->RoomUsed($RoomNumbers['property_id'],$RoomNumbers['arrival'],$RoomNumbers['departure'],$HotelID);

		}

		elseif($ChannelID==0)
		{
			$RoomNumbers = $this->db->query('select d.existing_room_number,d.property_id,start_date as arrival , end_date as departure
			from  manage_reservation a
			left join manage_property d on a.room_id=d.property_id
			where  a.hotel_id ='.$HotelID.' and a.reservation_id ='.$ReservationID)->row_array();

			$RoomUsed = $this->RoomUsed($RoomNumbers['property_id'],$RoomNumbers['arrival'],$RoomNumbers['departure'],$HotelID);
		}



			$AllRoomNumbers= explode(',',$RoomNumbers['existing_room_number'] );
			$AllRoomUsed='';
			$valor='';

			foreach ($RoomUsed as  $value) {
					$AllRoomUsed .= $value['RoomNumber'].',';

			
			}

			 $AllRoomUsed = explode(',', $AllRoomUsed  );
			 


			foreach ($AllRoomNumbers as  $value2) 
			{
				
				if(in_array($value2, $AllRoomUsed))
				{

					
				}
				else
				{
					$valor= $value2;
					return $valor;
					break;
				}

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