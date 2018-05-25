<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Hotelbeds_model extends CI_Model
{
	function channel_reservation_count($property_id,$start_date,$end_date)
	{
		$start_date = date('Ymd',strtotime(str_replace('-','',$start_date)));
		
		$end_date = date('Ymd',strtotime(str_replace('-','',$end_date)));
		
		$roomMappingCheck = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($property_id),'channel_id'=>'5'),'mapping_id,channel_id,import_mapping_id')->result_array();
		/* echo $this->db->last_query();
		echo '<pre>';
		print_r($roomMappingCheck); */
		if(count($roomMappingCheck)!=0)
		{   
			$total_reser_count = 0;
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);

				$chaMapCheck = $this->db->query("SELECT sequence,contract_name,contract_code, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where user_id='".current_user_type()."' and hotel_id='".hotel_id()."' and map_id='".$import_mapping_id."'")->row_array();
				
				/* echo '<pre>';
				print_r($chaMapCheck); */
				
				if(count($chaMapCheck)!=0)
				{
					$chaReserCheckCount = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `".HBEDS_RESER."` WHERE ( (DATE_FORMAT(DateFrom,'%Y%m%d') >= '".$start_date."' AND DATE_FORMAT(DateFrom,'%Y%m%d') <= '".$end_date."') OR ( (DATE_FORMAT(DateTo,'%Y%m%d') > '".$start_date."' AND DATE_FORMAT(DateTo,'%Y%m%d') <= '".$end_date."') ) ) AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND Contract_Code='".$chaMapCheck['sequence']."' AND Contract_Name='".$chaMapCheck['contract_name']."' AND IncomingOffice='".$chaMapCheck['contract_code']."' AND Room_code='".$chaMapCheck['roomnames']."' AND CharacteristicCode='".$chaMapCheck['charactersticss']."' AND RoomStatus NOT IN('CANCELED')");
					/* echo $this->db->last_query(); */							
					if($chaReserCheckCount)
					{
						$chaReserCheckCount = $chaReserCheckCount->row();	
						if($chaReserCheckCount)
						{
							$chaReserCheckCount = $chaReserCheckCount->numrows;
						}
						else
						{
							$chaReserCheckCount = 0;
						}
					}
					else
					{
						$chaReserCheckCount = 0;
					}
					$total_reser_count =$total_reser_count+$chaReserCheckCount;
				}
			}
			return $total_reser_count;
		}
	}
	
	function channel_reservation_result($property_id,$start_date,$end_date)
	{
		$start_date = date('Ymd',strtotime(str_replace('-','',$start_date)));
		
		$end_date = date('Ymd',strtotime(str_replace('-','',$end_date)));
		
		$roomMappingCheck = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($property_id),'channel_id'=>'5'),'mapping_id,channel_id,import_mapping_id')->result_array();
		if(count($roomMappingCheck)!=0)
		{
			$i=0;
			$chaReserCheckCount = array();
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);
				if($channel_id==5)
				{
					$chaMapCheck = $this->db->query("SELECT sequence,contract_name,contract_code, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where user_id='".current_user_type()."' and hotel_id='".hotel_id()."' and map_id='".$import_mapping_id."'")->row_array();

					if(count($chaMapCheck)!=0)
					{
						$cahquery = $this->db->query("SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(DateFrom,'%d/%m/%Y') as start_date, DATE_FORMAT(DateTo,'%d/%m/%Y') as end_date ,channel_id,RefNumber as booking_number FROM `".HBEDS_RESER."` WHERE ( (DATE_FORMAT(DateFrom,'%Y%m%d') >= '".$start_date."' AND DATE_FORMAT(DateFrom,'%Y%m%d') <= '".$end_date."') OR ( (DATE_FORMAT(DateTo,'%Y%m%d') > '".$start_date."' AND DATE_FORMAT(DateTo,'%Y%m%d') <= '".$end_date."') ) ) AND Contract_Name='".$chaMapCheck['contract_name']."' AND IncomingOffice='".$chaMapCheck['contract_code']."' AND Room_code='".$chaMapCheck['roomnames']."' AND Contract_Code='".$chaMapCheck['sequence']."' AND CharacteristicCode='".$chaMapCheck['charactersticss']."' AND RoomStatus NOT IN('CANCELED') ORDER BY import_reserv_id DESC");
						/* echo $this->db->last_query(); */	
						if($cahquery)
						{
							$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());
						}
					}
				}
			}
			return	$chaReserCheckCount;
		}
	}
		
	function getSingleReservationHotelbeds($reservation_id)
   	{
		$CURDATE = date('Ymd');
		$cahquery = $this->db->query('SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(DateFrom,"%d/%m/%Y") as start_date, DATE_FORMAT(DateTo,"%d/%m/%Y") as end_date , channel_id , Holder as guest_name , RefNumber as reservation_code, DATEDIFF(DateTo,DateFrom) AS num_nights , AdultCount as members_count , Amount as price , Room_code , IncomingOffice, Contract_Name, CharacteristicCode, Contract_Code FROM (`'.HBEDS_RESER.'`) WHERE (`DateFrom` >= "'.$CURDATE.'" OR `DateTo` >= "'.$CURDATE.'") AND `user_id` = "'.current_user_type().'" AND `hotel_id` = "'.hotel_id().'" AND `import_reserv_id` = "'.$reservation_id.'"');

		//echo $this->db->last_query().'<br>';
        if($cahquery)
        {
            $chaReserResult = $cahquery->row_array();
        }
        return $chaReserResult;
    }

	function updateAvailability($HotelCode,$source)
	{
		$channel_id	=	'5';
		
		if($source=='Manual')
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
		}
		
		$userDetails	=	get_data(CONNECT,array('channel_id'=>$channel_id,'hotel_channel_id'=>$HotelCode,'status'=>'enabled'),'user_id,hotel_id')->row_array();
		if($userDetails)
		{
			$getRooms	=	get_data(UAVL,array('user_id'=>$userDetails['user_id'],'hotel_id'=>$userDetails['hotel_id'],'channel_id'=>$channel_id,'channel_hotel_id'=>$HotelCode,'status'=>1))->result_array();
			/* echo '<pre>';
			print_r($getRooms);
			die; */
			if($getRooms)
			{
				foreach($getRooms as $getRoomsVal)
				{
					extract($getRoomsVal);
					
					$channel_start	=	date_create($start);
					$channel_start 	=	date_format($channel_start,"Y-m-d");

					$channel_end	=	date_create($end);
					$channel_end 	=	date_format($channel_end,"Y-m-d");	
					
					$room_detais	=	explode(',',$relation_one);
					
					if($reservation_status!='CANCELED')
					{
						$updateOldRoom	=	get_data(UAVL,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$channel_hotel_id,'status'=>0,'reservation_id'=>$reservation_id,'reservation_status'=>'CANCELED','relation_two'=>$relation_two))->row_array();
					}
					else
					{
						$updateOldRoom	=	array();
					}
					
					if(count($updateOldRoom)!=0)
					{
						$getRoomRelation = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where sequence='".$room_detais[4]."' and contract_name='".$room_detais[1]."' and contract_code='".$room_detais[0]."' and user_id='".$user_id."' and hotel_id='".$hotel_id."' having roomnames ='".$room_detais[3]."' AND charactersticss ='".$room_detais[2]."'")->row_array();
						
						if($getRoomRelation)
						{
							$channel_c_start	=	date_create($updateOldRoom['start']);
							$channel_c_start 	=	date_format($channel_start,"Y-m-d");

							$channel_c_end	=	date_create($updateOldRoom['end']);
							$channel_c_end 	=	date_format($channel_end,"Y-m-d");
					
							$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$getRoomRelation['map_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
							
							if($getMappedRooms)
							{
								if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
								=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
								{
									$date	=	date_create($updateOldRoom['start']);
									$start 	=	date_format($date,"Y-m-d");
									
									$date	=	date_create($updateOldRoom['end']);
									$end 	=	date_format($date,"Y-m-d");
									
									$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_1	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_1 as $date)
									{
										$date = $date->format("d/m/Y");
									
										$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date),'room_update_id,availability')->row_array();
										
										if(count($available_update_details)!=0)
										{
											$value	=	$available_update_details['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data['trigger_cal']=1;
											
											$ch_update_data['availability']=$value;
											
											update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date));
											
											$this->db->where('room_update_id !=',$available_update_details['room_update_id']);
											$this->db->where('owner_id',$user_id);
											$this->db->where('hotel_id',$hotel_id);
											$this->db->where('room_id',$getMappedRooms['property_id']);
											$this->db->where('separate_date',$date);
											$this->db->set('availability','CASE WHEN availability '.$opr.' '.$updateOldRoom['difference'].' >=0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
											
											$this->db->update(TBL_UPDATE);
										}										
									}
								}
								else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']=='0' && $getMappedRooms['guest_count']!='0' && $getMappedRooms['refun_type']!='0')
								{
									$date	=	date_create($updateOldRoom['start']);
									$start 	=	date_format($date,"Y-m-d");
									
									$date	=	date_create($updateOldRoom['end']);
									$end 	=	date_format($date,"Y-m-d");
									
									$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_2	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_2 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row();
										
										if(count($available_update_details_RESERV)!=0)
										{
											$value	=	$available_update_details_RESERV['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data['trigger_cal']=1;
											
											$ch_update_data['availability']=$value;
											
											update_data(RESERV,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
											
											$this->db->where('reserv_price_id !=',$available_update_details_RESERV['reserv_price_id']);
											$this->db->where('owner_id',$user_id);
											$this->db->where('hotel_id',$hotel_id);
											$this->db->where('room_id',$getMappedRooms['property_id']);
											$this->db->where('separate_date',$date);
											$this->db->set('availability','CASE WHEN availability '.$opr.' '.$updateOldRoom['difference'].' >=0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
											
											$this->db->update(RESERV);
										}										
									}
								}
								else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']!='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
								{
									$date	=	date_create($updateOldRoom['start']);
									$start 	=	date_format($date,"Y-m-d");
									
									$date	=	date_create($updateOldRoom['end']);
									$end 	=	date_format($date,"Y-m-d");
									
									$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_3	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
						
									foreach($period_old_3 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date),'room_update_id,availability')->row_array();
										
										if(count($available_update_details_RATE_BASE)!=0)
										{
											$value	=	$available_update_details_RATE_BASE['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data_RATE_BASE['trigger_cal']=1;
											
											$ch_update_data_RATE_BASE['availability']=$value;
											
											update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date));
											
											$this->db->where('room_update_id !=',$available_update_details_RATE_BASE['room_update_id']);
											$this->db->where('owner_id',$user_id);
											$this->db->where('hotel_id',$hotel_id);
											$this->db->where('room_id',$getMappedRooms['property_id']);
											$this->db->where('separate_date',$date);
											$this->db->set('availability','CASE WHEN availability '.$opr.' '.$updateOldRoom['difference'].' >=0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
											
											$this->db->update(RATE_BASE);
										
										}										
									}
								}
								else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']!='0' && $getMappedRooms['guest_count']!='0' && $getMappedRooms['refun_type']!='0')
								{
									$date	=	date_create($updateOldRoom['start']);
									$start 	=	date_format($date,"Y-m-d");
									
									$date	=	date_create($updateOldRoom['end']);
									$end 	=	date_format($date,"Y-m-d");
									
									$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_4	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_4 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row_array();
										
										if(count($available_update_details_RATE_ADD)!=0)
										{
											$value	=	$available_update_details_RATE_ADD['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data_RATE_ADD['trigger_cal']=1;
											
											$ch_update_data_RATE_ADD['availability']=$value;
											
											update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
											
											$this->db->where('reserv_price_id !=',$available_update_details_RATE_ADD['reserv_price_id']);
											$this->db->where('owner_id',$user_id);
											$this->db->where('hotel_id',$hotel_id);
											$this->db->where('room_id',$getMappedRooms['property_id']);
											$this->db->where('separate_date',$date);
											$this->db->set('availability','CASE WHEN availability '.$opr.' '.$updateOldRoom['difference'].' >=0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$updateOldRoom['difference'].' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
											
											$this->db->update(RATE_ADD);
										}										
									}
								}
								$this->availability_model->updateAvailabilityBNOW($channel_c_start,$channel_c_end,$user_id,$hotel_id,$getMappedRooms['property_id'],'5');
							}
						}
						delete_data(UAVL,array('column_id'=>$updateOldRoom['column_id']));
					}

					$getRoomRelation = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where sequence='".$room_detais[4]."' and contract_name='".$room_detais[1]."' and contract_code='".$room_detais[0]."' and user_id='".$user_id."' and hotel_id='".$hotel_id."' having roomnames ='".$room_detais[3]."' AND charactersticss ='".$room_detais[2]."'")->row_array();
					//echo '<pre>';
					//echo $this->db->last_query();
					/* echo '<pre>';
					print_r($getRoomRelation);
					die; */
					if($getRoomRelation)
					{
						$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$getRoomRelation['map_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
						
						if($getMappedRooms)
						{
							if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
							=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
							{
								$date	=	date_create($start);
								$start 	=	date_format($date,"Y-m-d");
								
								$date	=	date_create($end);
								$end 	=	date_format($date,"Y-m-d");
								
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_1	=	new DatePeriod( $startDate, $periodInterval, $endDate );
					 
								foreach($period_new_1 as $date)
								{
									$date = $date->format("d/m/Y");

									$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date),'room_update_id,availability')->row_array();
									
									if(count($available_update_details)!=0)
									{
										if($reservation_status=='CANCELED')
										{
											$opr	=	'+';
											$value	=	$available_update_details['availability']+$difference;
										}
										else
										{
											$opr	=	'-';
											$value	=	$available_update_details['availability']-$difference;
										}
										
										$ch_update_data['trigger_cal']=1;
										
										$ch_update_data['availability']=$value;
										
										update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date));
										
										$this->db->where('room_update_id !=',$available_update_details['room_update_id']);
										$this->db->where('owner_id',$user_id);
										$this->db->where('hotel_id',$hotel_id);
										$this->db->where('room_id',$getMappedRooms['property_id']);
										$this->db->where('separate_date',$date);
										$this->db->set('availability','CASE WHEN availability '.$opr.' '.$difference.' >=0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
										
										$this->db->update(TBL_UPDATE);
									}										
								}
							}
							else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']=='0' && $getMappedRooms['guest_count']!='0' && $getMappedRooms['refun_type']!='0')
							{
								$date	=	date_create($start);
								$start 	=	date_format($date,"Y-m-d");
								
								$date	=	date_create($end);
								$end 	=	date_format($date,"Y-m-d");
								
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_2	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_2 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row();
									
									if(count($available_update_details_RESERV)!=0)
									{
										if($reservation_status=='CANCELED')
										{
											$opr	=	'+';
											$value	=	$available_update_details_RESERV['availability']+$difference;
										}
										else
										{
											$opr	=	'-';
											$value	=	$available_update_details_RESERV['availability']-$difference;
										}
										
										$ch_update_data['trigger_cal']=1;
										
										$ch_update_data['availability']=$value;
										
										update_data(RESERV,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
									
										$this->db->where('reserv_price_id !=',$available_update_details_RESERV['reserv_price_id']);
										$this->db->where('owner_id',$user_id);
										$this->db->where('hotel_id',$hotel_id);
										$this->db->where('room_id',$getMappedRooms['property_id']);
										$this->db->where('separate_date',$date);
										$this->db->set('availability','CASE WHEN availability '.$opr.' '.$difference.' >=0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
										
										$this->db->update(RESERV);
									}										
								}
							}
							else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']!='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
							{
								$date	=	date_create($start);
								$start 	=	date_format($date,"Y-m-d");
								
								$date	=	date_create($end);
								$end 	=	date_format($date,"Y-m-d");
								
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_3	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_3 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date),'room_update_id,availability')->row_array();
									
									if(count($available_update_details_RATE_BASE)!=0)
									{
										if($reservation_status=='CANCELED')
										{
											$opr	=	'+';
											$value	=	$available_update_details_RATE_BASE['availability']+$difference;
										}
										else
										{
											$opr	=	'-';
											$value	=	$available_update_details_RATE_BASE['availability']-$difference;
										}
										
										$ch_update_data_RATE_BASE['trigger_cal']=1;
										
										$ch_update_data_RATE_BASE['availability']=$value;
										
										update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date));
										
										$this->db->where('room_update_id !=',$available_update_details_RATE_BASE['room_update_id']);
										$this->db->where('owner_id',$user_id);
										$this->db->where('hotel_id',$hotel_id);
										$this->db->where('room_id',$getMappedRooms['property_id']);
										$this->db->where('separate_date',$date);
										$this->db->set('availability','CASE WHEN availability '.$opr.' '.$difference.' >=0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
										
										$this->db->update(RATE_BASE);
									
									}										
								}
							}
							else if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']!='0' && $getMappedRooms['guest_count']!='0' && $getMappedRooms['refun_type']!='0')
							{
								$date	=	date_create($start);
								$start 	=	date_format($date,"Y-m-d");
								
								$date	=	date_create($end);
								$end 	=	date_format($date,"Y-m-d");
								
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_4	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_4 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row_array();
									
									if(count($available_update_details_RATE_ADD)!=0)
									{
										if($reservation_status=='CANCELED')
										{
											$opr	=	'+';
											$value	=	$available_update_details_RATE_ADD['availability']+$difference;
										}
										else
										{
											$opr	=	'-';
											$value	=	$available_update_details_RATE_ADD['availability']-$difference;
										}
										
										$ch_update_data_RATE_ADD['trigger_cal']=1;
										
										$ch_update_data_RATE_ADD['availability']=$value;
										
										update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
										
										$this->db->where('reserv_price_id !=',$available_update_details_RATE_ADD['reserv_price_id']);
										$this->db->where('owner_id',$user_id);
										$this->db->where('hotel_id',$hotel_id);
										$this->db->where('room_id',$getMappedRooms['property_id']);
										$this->db->where('separate_date',$date);
										$this->db->set('availability','CASE WHEN availability '.$opr.' '.$difference.' >=0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id = 0 THEN availability '.$opr.' '.$difference.' WHEN availability '.$opr.' '.$value.' < 0 AND individual_channel_id != 0 THEN 0 END' ,false);
										
										$this->db->update(RATE_ADD);
									}										
								}
							}
							
							$this->availability_model->updateAvailabilityBNOW($channel_start,$channel_end,$user_id,$hotel_id,$getMappedRooms['property_id'],'5');
						}
					}
					
					$UAVL['status']	=	'0';
					
					update_data(UAVL,$UAVL,array('column_id'=>$column_id));
					
					if($reservation_status=='CANCELED')
					{
						delete_data(UAVL,array('reservation_id'=>$reservation_id,'reservation_status'=>'CANCELED'));
					}
				}
			}
		}
	}
}