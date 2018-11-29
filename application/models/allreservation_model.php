<?php
ini_set('memory_limit', '-1');
ini_set('display_erros','1');
class allreservation_model extends CI_Model
{

	function allreservation()
	{
		$booking=$this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id FROM `manage_reservation`   AS e 
			where  e.hotel_id = ".hotel_id()." order by e.reservation_id desc");

/*
		"select a.status,a.guest_name,'Booking.com' AS channel, DATE_FORMAT(a.arrival_date,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure_date,'%d/%m/%Y') as end_date, a.date_time as booking_date, concat(a.id,'-',a.roomreservation_id) reservation_code,
			a.totalprice as price, b.email as user_email, telephone as mobile, 
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			2 as channel_id , b.currencycode  as currency_id
			from import_reservation_BOOKING_ROOMS a
			left join import_reservation_BOOKING b on a.import_reserv_id=b.import_reserv_id
			left join import_mapping_BOOKING c on a.id= c.B_room_id and a.rate_id = c.B_rate_id
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=2
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_hotel_id=".hotel_id()." order by arrival_date desc"

"select a.ResStatus as status,a.name as guest_name,'AIRBNB' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.ImportDate as booking_date, a.ResID_Value reservation_code,
			a.AmountAfterTax as price, '' as user_email, '' as mobile, 
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			9 as channel_id , a.Currency  as currency_id
			from import_reservation_AIRBNB a
			left join import_mapping_AIRBNB c on a.RoomTypeCode=c.Roomid
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=9
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_id=".hotel_id()." order by arrival desc"

"select a.type as status,  concat(a.givenName,' ',a.surname) as guest_name,'EXPEDIA' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.created_time as booking_date, a.booking_id reservation_code,
			a.amountAfterTaxes as price, a.Email as user_email, a.number as mobile, 
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			1 as channel_id , a.currency  as currency_id
			from import_reservation_EXPEDIA a
			left join import_mapping_AIRBNB c on a.RoomTypeCode=c.Roomid
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=9
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_id=".hotel_id()." order by arrival desc"


*/



	}
}	