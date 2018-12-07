<?php	

class arrivalreservations extends CI_Controller{



		function updateavailability($channelid="",$roomid="", $rateid =0,$hotelid="",$date1="",$date2="",$new_cancel="",$cantidad=1)
		{


			$valor=($new_cancel=="new"?"-":"+");
		

			$inicialdate=$date1;
			$FinalDate=$date2;
			
			$Result="";

			$userid= $this->db->query("select owner_id  from manage_hotel where hotel_id =$hotelid" )->row()->owner_id;
	
			if($rateid>0)
			{
				$this->db->query("update room_rate_types_base set trigger_cal = 0,availability=availability ".$valor." $cantidad , stop_sell = case when (availability  ) <= 0 then 1 else 0 end ,open_room = case when (availability )> 0 then 1 else 0 end   where  hotel_id =$hotelid and individual_channel_id =0 and STR_TO_DATE(separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d')  and room_id=$roomid and rate_types_id = $rateid" );

				$this->db->query("Update  room_rate_types_base b 
					left join manage_property a on a.property_id=b.room_id 
				
					set b.price=case when ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue > maximun then maximun else ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue end
						where 
					a.hotel_id =$hotelid  
					and b.hotel_id=$hotelid 
					AND a.revenuertatus =1 
                    and a.property_id=$roomid
                    and b.rate_types_id = $rateid
					and b.individual_channel_id=0
					and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d') " );
				
				$this->db->query("update room_update set trigger_cal = 0,availability=availability ".$valor." $cantidad , stop_sell = case when (availability  ) <= 0 then 1 else 0 end ,open_room = case when (availability )> 0 then 1 else 0 end   where  hotel_id =$hotelid and individual_channel_id =0 and STR_TO_DATE(separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d')  and room_id=$roomid" );


				$this->db->query("Update  room_update b 
					left join manage_property a on a.property_id=b.room_id 
				
					set b.price=case when ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue > maximun then maximun else ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue end
						where 
					a.hotel_id =$hotelid  
					and b.hotel_id=$hotelid 
					AND a.revenuertatus =1 
                    and a.property_id=$roomid
					and b.individual_channel_id=0
					and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d') " );



			}
			else
			{
			
				$this->db->query("update room_update set trigger_cal = 0,availability=availability ".$valor." $cantidad , stop_sell = case when (availability  ) <= 0 then 1 else 0 end ,open_room = case when (availability )> 0 then 1 else 0 end   where  hotel_id =$hotelid and individual_channel_id =0 and STR_TO_DATE(separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d')  and room_id=$roomid" );

				$this->db->query("Update  room_update b 
					left join manage_property a on a.property_id=b.room_id 
				
					set b.price=case when ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue > maximun then maximun else ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-b.availability)) + PriceRevenue end
						where 
					a.hotel_id =$hotelid  
					and b.hotel_id=$hotelid 
					AND a.revenuertatus =1 
                    and a.property_id=$roomid
					and b.individual_channel_id=0
					and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d') " );

			}

			$canales= $this->db->query(" select * from user_connect_channel where hotel_id=$hotelid and status='enabled'" )->result_array();

	
				foreach ($canales as $value) {
					
					$Channelid = $value['channel_id'];

					 if ($Channelid == 36) {
                        $this->load->model("despegar_model");
                        $Result .= $this->despegar_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
                        
                        
                    } elseif ($Channelid == 1) {
                        $this->load->model("expedia_model");
                        $Result .= $this->expedia_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid,'No');
                        
                    } elseif ($Channelid == 2) {
                        
                        $this->load->model("booking_model");
                        $Result .= $this->booking_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);


                    } elseif ($Channelid == 9) {
                        
                        $this->load->model("airbnb_model");
                        $Result .= $this->airbnb_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
                    }elseif ($Channelid == 19) {
                        
                        $this->load->model("agoda_model");
                        $Result .= $this->agoda_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
                    }

				}


				//echo $Result;
				return;
			

		}
		function revenue($hotelid='',$roomid='',$date1="",$date2="")
		{
			

			

					
					$this->db->query("Update  room_update b 
					left join manage_property a on a.property_id=b.room_id 
				
					set b.price=case when ((((percentage/100)*minimun)/existing_room_count)*(existing_room_count-b.availability)) + minimun > maximun then maximun else ((((percentage/100)*minimun)/existing_room_count)*(existing_room_count-b.availability)) + minimun end
						where 
					a.hotel_id =$hotelid  
					and b.hotel_id=$hotelid 
					AND a.revenuertatus =1 
                    and a.property_id=$roomid
					and b.individual_channel_id=0
					and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between STR_TO_DATE('$date1' ,'%Y-%m-%d')  and STR_TO_DATE('$date2' ,'%Y-%m-%d') " );		

		}

		function test()
		{
			echo ucfirst(@get_data(TBL_CHANNEL,array('channel_id'=>''))->row()->channel_name);
		}

	}


?>