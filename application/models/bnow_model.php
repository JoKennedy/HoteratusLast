<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Bnow_model extends CI_Model
{
	function get_mapping_rooms($channel_id,$type='')
	{	
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$owner_id= current_user_type();
		}
		elseif(user_type()=='2')
		{
			$owner_id = current_user_type();
		}
		if($channel_id=='17')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
				if(count($connected_room)!=0)
				{
					foreach($connected_room as $import_mapping)
					{
						extract($import_mapping);
						$import[] = $import_mapping_id;
					}
				}
				else
				{
					$import[] ='';
				}
			}
			else
			{
				$import[] ='';
			}
			$clean = cleanArray($import);
			$this->db->select('B.import_mapping_id, B.RateTypeName, B.RoomTypeName');
			if($clean!='')
			{
				$this->db->where_not_in('B.import_mapping_id',$import);
			}
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get(IM_BNOW.' as B');
			if($result!='')
			{
				return $result->result();
			}
			else
			{
				return false;
			}
		}
	}
	
	function get_all_mapping_rooms($channel_id)
	{	
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
		if($channel_id=='17')
		{
			$count = $this->db->select('import_mapping_id')->from(IM_BNOW)->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'HotelCode'=>$ch_details->hotel_channel_id))->count_all_results();
			return $count;
		}
	}
	
	function get_all_mapped_rooms($channel_id)
	{
		$this->db->select('R.mapping_id,R.owner_id,R.hotel_id,R.property_id,R.rate_id,R.channel_id,R.import_mapping_id,R.guest_count,R.refun_type,R.enabled,R.included_occupancy,R.extra_adult,R.extra_child,R.single_quest,R.update_rate,R.update_availability,R.rate_conversion,R.explevel');
		if($channel_id==11)
		{
			$this->db->join(IM_BNOW.' as BN','R.import_mapping_id=BN.import_mapping_id');
			$this->db->join(CONNECT.' as C','BN.hotel_channel_id=C.hotel_channel_id');
		}
		$this->db->where(array('R.owner_id'=>user_id(),'R.hotel_id'=>hotel_id(),'R.channel_id'=>$channel_id));
		$query = $this->db->get(MAP.' as R');
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function importAvailabilities($user_id = "", $hotel_id = "",$channel,$mapping,$import_mapping_id,$start_date = "",$end_date = "",$importRates='')
    {
        if($user_id == "")
		{
            $user_id = current_user_type();
        }
        if($hotel_id == "")
		{
            $hotel_id = hotel_id();
        }
        extract($channel);
		/* echo '<pre>';
		print_r($channel); */
        if($start_date == "")
		{
			$start_date = date('Y-m-d');
        }
		else if($start_date != "")
        {
			$start_date = $start_date;
        }
		
		if($end_date == "")
		{
			$end_date = date('Y-m-d', strtotime("+30 days"));
        }
		else if($end_date != "")
        {
			$end_date = $end_date;
        }
		       
        $ch_details = get_data(CONNECT,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>"17",'status'=>'enabled'))->row();
		
		if($ch_details)
		{
			$channel_id = 17;                   
			
			$mp_details = get_data(IM_BNOW,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$import_mapping_id,'HotelCode'=>$ch_details->hotel_channel_id))->row();
			
			$map_details	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$import_mapping_id),'explevel')->row();
			/* print_r($map_details);
			die; */

			$xml_data = '<?xml version="1.0" encoding="utf-8" ?>
						<HotelGetRQ xmlns="http://www.opentravel.org/OTA/2013/05"
						TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
						<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
						<HotelGetRequests HotelCode="'.$ch_details->hotel_channel_id.'" >
						<HotelGetRequest>
						<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>
						<ApplicationControl Start="'.$start_date.'" End="'.$end_date.'"/>
						</HotelGetRequest>
						</HotelGetRequests>
						</HotelGetRQ>';
						$x_r_rq_data['channel_id'] 	=	'17';						
						$x_r_rq_data['user_id'] 	= 	'0';						
						$x_r_rq_data['hotel_id']	= 	'0';						
						$x_r_rq_data['message'] 	= 	$xml_data;						
						$x_r_rq_data['type'] 		= 	'REQ';						
						$x_r_rq_data['section'] 	= 	'BNOW_IM_AV_REQ';
						insert_data(ALL_XML,$x_r_rq_data);
						$C_URL = get_data(C_URL,array('channel_id'=>17))->row();
						if($C_URL)
						{
							if($C_URL->mode=='0')
							{
								$url = explode(',',$C_URL->test_url);
								
							}
							else if($C_URL->mode=='1')
							{
								$url = explode(',',$C_URL->live_url);
							}
						}
						$ch			=	curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_URL, $url[0]);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 500);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
						$ss 		= 	curl_getinfo($ch);
						$response 	= 	curl_exec($ch);	
						$x_r_rs_data['channel_id'] 	= 	'17';						
						$x_r_rs_data['user_id'] 	= 	'0';						
						$x_r_rs_data['hotel_id'] 	= 	'0';						
						$x_r_rs_data['message'] 	= 	$response;						
						$x_r_rs_data['type'] 		= 	'RES';						
						$x_r_rs_data['section'] 	=	'BNOW_IM_AV_RES';
						insert_data(ALL_XML,$x_r_rs_data);		
						$data_api = simplexml_load_string($response);
						$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
						$xml = simplexml_load_string($xml);
						$json = json_encode($xml);
						$responseArray = json_decode($json,true);
						/* echo '<pre>';
						print_r($responseArray);
						die; */
						$Errors = @$responseArray['Errors']['Error'];
						if($Errors!='')
						{
							$this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,$Errors,'Import availability',date('m/d/Y h:i:s a', time()));
						}
						else	
						{
							$data['HotelCode'] 	=	(string)$data_api->HotelDataSet->attributes()->HotelCode;
							$HotelDataSet = $responseArray['HotelDataSet']['HotelData'];
							if($HotelDataSet)
							{
								foreach($HotelDataSet as $HotelData)
								{	
									/* $ProductReference = $HotelData['ProductReference'];
									if($ProductReference)
									{
										foreach($ProductReference as $attributes)
										{
											echo 'InvTypeCode'.' = '.$attributes['InvTypeCode'].'<br>';
											echo 'RatePlanCode'.' = '.$attributes['RatePlanCode'].'<br>';
										}
									} */
									$ApplicationControl = $HotelData['ApplicationControl'];
									if($ApplicationControl)
									{
										foreach($ApplicationControl as $attributes)
										{
											//echo 'Start'.' = '.$attributes['Start'].'<br>';
											$Start	=	$attributes['Start'];	
										}
									}
									if($map_details->explevel=='BRP' && $importRates!='')
									{
										$RateAmounts = $HotelData['RateAmounts']['Base'];
										if($RateAmounts)
										{
											foreach($RateAmounts as $attributes)
											{
												//echo 'Amount'.' = '.$attributes['Amount'].'<br>';
												$Allotment	=	$attributes['Amount'];
											}
										}
									}
									if($importRates=='')
									{
										$BookingLimit = $HotelData['BookingLimit']['TransientAllotment'];
										if($BookingLimit)
										{
											foreach($BookingLimit as $attributes)
											{
												//echo 'Allotment'.' = '.$attributes['Allotment'].'<br>';
												$Allotment	= $attributes['Allotment'];	
											}
										}
									}
									/* echo $Amount.' = '.$Start.' = '.$Allotment.'<br>';
									die; */
									$sep_date = date('d/m/Y',strtotime(str_replace('-','/',$Start)));
									/* require_once(APPPATH.'controllers/mapping.php'); 
									$callAvailabilities = new Mapping();
									$callAvailabilities->update_channel_calendar($user_id,$hotel_id,$channel,$Allotment,$sep_date,$mapping); */ 
									$this->update_channel_calendar($user_id,$hotel_id,$channel,$Allotment,$sep_date,$mapping,$importRates);
								}
							}
						}
						return true;
		}
		else
		{
			return 'Enable';
		}
    }
	
	function update_channel_calendar($owner_id,$hotel_id,$channel,$value,$date,$type,$importRates='')
    {
        extract($channel);
        if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0')
		{
            $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
            if($importRates=='')
			{
				$ch_update_data['availability']	=	$value;
			}
			else if($importRates!='')
			{
				$ch_update_data['price']		=	$value;
			}
            if($type != "")
			{
                $ch_update_data['trigger_cal']=1;
            }
            
            if(count($available_update_details)!=0)
            {
                if($type != "")
                {
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details['availability'];

                    if($value > $old_avail)
					{
                        $adiff = $value - $old_avail;
                        $opr = '+';
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail)
					{
                        $adiff = $old_avail - $value;
                        $opr = '-';
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0)
                    {
                        update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'channel_id'=>$channel_id),'mapping_id')->row()->mapping_id;
    					
    					//echo $this->db->last_query();
    					
                        $ch_update_data['availability']=$value;
    					
                        if($type != "")
    					{ 
                            if($opr == '-')
    						{
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('room_update_id !=',$available_update_details['room_update_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(TBL_UPDATE);
                            //echo $this->db->last_query();
                            
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data,$channel,$date,$mapping_id);
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        }
                    }
                }
				else
				{
                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                }
            }
			else
			{
				$ch_update_data['owner_id']					=	$owner_id;
				$ch_update_data['hotel_id']					=	$hotel_id;
				$ch_update_data['room_id']					=	$property_id;
				$ch_update_data['individual_channel_id']	=	$channel_id;
				if($importRates=='')
				{
					$ch_update_data['availability']			=	$value;
				}
				else if($importRates!='')
				{
					$ch_update_data['price']				=	$value;
				}
				$ch_update_data['separate_date']			=	$date;
				insert_data(TBL_UPDATE,$ch_update_data);
			}
           
        }
        // *** End Of TBL UPDATE *** //
        // *** Reservation TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0')
		{
			if($importRates=='')
			{
				$ch_update_data_RESERV['availability'] 	= $value;
			}
			else if($importRates!='')
			{
				if($refun_type==1)
				{
					$ch_update_data_RESERV['refund_amount']		=	$value;
				}
				else if($refun_type==2)
				{
					$ch_update_data_RESERV['non_refund_amount']	=	$value;
				}
			}
            if($type != "")
			{
                $ch_update_data_RESERV['trigger_cal'] = 1;
            }

            $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RESERV)!=0)
            {
                if($type != "")
                {
                    $old_avail = $available_update_details_RESERV->availability;
                    $adiff = 0;
                    $opr = '+';
                    if($value > $old_avail)
					{
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail)
					{
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0)
					{
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'channel_id'=>$channel_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type),'mapping_id')->row()->mapping_id;

                        update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                        $ch_update_data_RESERV['availability'] = $value;
                        if($type != "")
    					{
                            if($opr == '-')
							{
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('reserv_price_id !=',$available_update_details_RESERV['reserv_price_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RESERV);
                           // echo $this->db->last_query();
                            //$this->db->query('call UpdateAvailabilityInSubTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RESERV,$channel,$date,$mapping_id);
                        }
                    }
                }
				else
				{
                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                }             
            }   
			else
			{
				$ch_update_data_RESERV['owner_id']					=	$owner_id;
				$ch_update_data_RESERV['hotel_id']					=	$hotel_id;
				$ch_update_data_RESERV['room_id']					=	$property_id;
				$ch_update_data_RESERV['individual_channel_id']		=	$channel_id;
				if($importRates=='')
				{
					$ch_update_data_RESERV['availability'] 			= 	$value;
				}
				else if($importRates!='')
				{
					if($refun_type==1)
					{
						$ch_update_data_RESERV['refund_amount']		=	$value;
					}
					else if($refun_type==2)
					{
						$ch_update_data_RESERV['non_refund_amount']	=	$value;
					}
				}
				$ch_update_data_RESERV['separate_date']				=	$date;
				$ch_update_data_RESERV['guest_count']				=	$guest_count;
				$ch_update_data_RESERV['refun_type']				=	$refun_type;
				insert_data(RESERV,$ch_update_data_RESERV);
			}                        
            
        }
        // *** End Of Reservation TBL UPDATE *** //
        // *** Base Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0')
		{
			if($importRates=='')
			{
				$ch_update_data_RATE_BASE['availability']	= 	$value;
			}
			else if($importRates!='')
			{
				$ch_update_data_RATE_BASE['price']			=	$value;
			}
            if($type != "")
            {
                $ch_update_data_RATE_BASE['trigger_cal']=1;
            }

            $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
            
            if(count($available_update_details_RATE_BASE)!=0)
            {
                if($type != "")
				{
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details_RATE_BASE['availability'];
                    if($value > $old_avail)
					{
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail)
					{
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }

                    if($adiff != 0)
					{
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'rate_id'=>$rate_id,'channel_id'=>$channel_id),'mapping_id')->row()->mapping_id;

                        update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                        $ch_update_data_RATE_BASE['availability']=$value;
                        
                        if($type != "")
						{
                            if($opr == '-')
							{
                                $this->db->where('availability !=',0);
                            }
                            //$this->db->query('call UpdateAvailabilityInRateBaseTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            $this->db->where('room_update_id !=',$available_update_details_RATE_BASE['room_update_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RATE_BASE);
                            //echo $this->db->last_query();

                             //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_BASE,$channel,$date,$mapping_id);
                        }
                    }
                }
				else
				{
                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                }
            }
			else
			{
				$ch_update_data_RATE_BASE['owner_id']					=	$owner_id;
				$ch_update_data_RATE_BASE['hotel_id']					=	$hotel_id;
				$ch_update_data_RATE_BASE['room_id']					=	$property_id;
				$ch_update_data_RATE_BASE['individual_channel_id']		=	$channel_id;
				if($importRates=='')
				{
					$ch_update_data_RATE_BASE['availability']			= 	$value;
				}
				else if($importRates!='')
				{
					$ch_update_data_RATE_BASE['price']					=	$value;
				}
				$ch_update_data_RATE_BASE['separate_date']				=	$date;
				$ch_update_data_RATE_BASE['rate_types_id']				=	$rate_id;
				insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
			}
        }
        // *** End Of Base Rate TBL UPDATE *** //
        // *** Additional Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0')
		{
			if($importRates=='')
			{
				$ch_update_data_RATE_ADD['availability'] 			= 	$value;
			}
			else if($importRates!='')
			{
				if($refun_type==1)
				{
					$ch_update_data_RATE_ADD['refund_amount']		=	$value;
				}
				else if($refun_type==2)
				{
					$ch_update_data_RATE_ADD['non_refund_amount']	=	$value;
				}
			}
            if($type != "")
            {
                $ch_update_data_RATE_ADD['trigger_cal'] = 1;
            }

            $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RATE_ADD)!=0)
            {
                if($type != "")
				{
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details_RATE_ADD->availability;
                    if($value > $old_avail)
					{
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail)
					{
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0)
					{
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'rate_id'=>$rate_id,'channel_id'=>$channel_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type),'mapping_id')->row()->mapping_id;
                        update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                        $ch_update_data_RATE_ADD['availability'] = $value;
                        if($type != "")
						{
                            if($opr == '-')
							{
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('reserv_price_id !=',$available_update_details_RATE_ADD['reserv_price_id']);   
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RATE_ADD);
                            //echo $this->db->last_query();                    
                            //$this->db->query('call UpdateAvailabilityInRateAddTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_ADD,$channel,$date,$mapping_id);                           
                        }
                    }
                }
				else
				{
                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                }
            }
			else
			{
				$ch_update_data_RATE_ADD['owner_id']					=	$owner_id;
				$ch_update_data_RATE_ADD['hotel_id']					=	$hotel_id;
				$ch_update_data_RATE_ADD['room_id']						=	$property_id;
				$ch_update_data_RATE_ADD['individual_channel_id']		=	$channel_id;
				if($importRates=='')
				{
					$ch_update_data_RATE_ADD['availability'] 			= 	$value;
				}
				else if($importRates!='')
				{
					if($refun_type==1)
					{
						$ch_update_data_RATE_ADD['refund_amount']		=	$value;
					}
					else if($refun_type==2)
					{
						$ch_update_data_RATE_ADD['non_refund_amount']	=	$value;
					}
				}
				$ch_update_data_RATE_ADD['separate_date']				=	$date;
				$ch_update_data_RATE_ADD['rate_types_id']				=	$rate_id;
				$ch_update_data_RATE_ADD['guest_count']					=	$guest_count;
				$ch_update_data_RATE_ADD['refun_type']					=	$refun_type;
				insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
			}
        }
        // *** End OF Additional Rate TBL UPDATE *** //
        return true;
    }
	
	function main_room_bulk_update($product,$stop_sale)
    {
		/*echo '<pre>';
		 print_r($product);
		die; */
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 17;
		
		$up_days 	= explode(',',$product['days']);
		
		if(in_array('1', $up_days)) 
        {
            $bnow_sun = 'true';
        }
        else 
        {
            $bnow_sun = 'false';
        }
        if(in_array('2', $up_days)) 
        {
            $bnow_mon = 'true';
        }
        else 
        {
            $bnow_mon = 'false';
        }
        if(in_array('3', $up_days)) 
        {
            $bnow_tue = 'true';
        }
        else 
        {
            $bnow_tue = 'false';
        }
        if(in_array('4', $up_days)) 
        {
            $bnow_wed = 'true';
        }
        else
        {
            $bnow_wed = 'false';
        }
        if(in_array('5', $up_days)) 
        {
            $bnow_thur = 'true';
        }
        else 
        {
            $bnow_thur = 'false';
        }
        if(in_array('6', $up_days)) 
        {
            $bnow_fri = 'true';
        }
        else 
        {
            $bnow_fri = 'false';
        }
        if(in_array('7', $up_days)) 
        {
            $bnow_sat = 'true';
        }
        else 
        {
            $bnow_sat = 'false';
        }
		
		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$product['start_date'])));
		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$product['end_date'])));
		
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row(); 
			
		$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>@$product['room_id'],'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->count_all_results();

		if($count!=0)
		{
			$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>@$product['room_id'],'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
			
			if($room_mapping)
			{
				$i=1;
				$xml_update	='';
				foreach($room_mapping as $room_value)
				{
					if(@$product['price'] != "")
					{
						if($room_value->rate_conversion != "1")
						{
							$rate_converted = 1;
							
							if(strpos($room_value->rate_conversion, '.') !== FALSE)
							{
								$product['price'] = $product['price'] * $room_value->rate_conversion;
							}
							else if(strpos($room_value->rate_conversion, ',') !== FALSE)
							{
								$mul = str_replace(',', '.', $room_value->rate_conversion);
								$product['price'] = $product['price'] * $mul;
							}
							else if(is_numeric($room_value->rate_conversion))
							{
								$product['price'] = $product['price'] * $room_value->rate_conversion;
							}                                  
						}
					}		
			
					$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row(); 
		
					$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
		
					$bnow_map = get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
		
					$rates_xml = array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
		
					$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
			
					$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';
		
					$rates_mxl 	.= 	'<ApplicationControl Start="'.$start_date.'" End="'.$end_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
					
					if($room_value->update_rate=='1')
					{
						$update_values = 'yes';

						$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
						
						if($mapping_values)
						{
							$label = explode(',', $mapping_values['label']);
							$value = explode(',', $mapping_values['value']);
							if($bnow_map['explevel']=='OBP')
							{
								$set_arr=array_combine($label,$value);
							}
							else if($bnow_map['explevel']=='BRP')
							{
								$set_arr=array_combine($label,$value);
								$set_arr = array_slice($set_arr, -2, 2, true);
								if(@$product['price']!='')
								{
									$prices = number_format((float)@$product['price'], 2, '.', '');
									$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$prices.'"></Base>';
								}
							}
							foreach($set_arr as $k=>$v)
							{
								if($v !='' && @$product['price']!='')
								{
									if(strpos($v, '+') !== FALSE)
									{
										$opr = explode('+', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = @$product['price'] + $opr[1];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else if(is_numeric($opr[0]))
										{
											$ex_price = @$product['price'] + $opr[0];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else
										{
											if(strpos($opr[1], '%'))
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = (@$product['price'] * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0], '%')) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = (@$product['price'] * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '-') !== FALSE) 
									{
										$opr = explode('-', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = @$product['price'] - $opr[1];
										}
										elseif (is_numeric($opr[0])) 
										{
											$ex_price = @$product['price'] - $opr[0];
										}
										else
										{
											if(strpos($opr[1],'%') !== FALSE)
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = (@$product['price'] * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0],'%') !== FALSE) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = (@$product['price'] * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '%') !== FALSE) 
									{
										$opr = explode('%', $v);
										if(is_numeric($opr[1]))
										{
											$per_price = (@$product['price'] * $opr[1]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
										elseif (is_numeric($opr[0])) 
										{
											$per_price 	= (@$product['price'] * $opr[0]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
									}
									else
									{
										$ex_price = @$product['price'] + $v;
										$ex_price = number_format((float)$ex_price, 2, '.', '');
										if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
										{
											
											$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
										}
										else
										{
											$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
										}
									}
								}
							}
						}
						
						$rates_mxl 	.=	'</RateAmounts>	';
					}
					if($room_value->update_availability=='1')
					{
						$update_values = 'yes';
						
						if ($stop_sale == "1") 
						{
							$rates_mxl 	.='<Availability Master="Closed"/>';
						}
						else if($stop_sale == "remove")
						{
							$rates_mxl 	.='<Availability Master="Open"/>';
						}
						
						if(@$product['availability']!='')
						{
							$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$product['availability'].'"/></BookingLimit>';
						}
						
						if(@$product['minimum_stay'] != "")
						{
							$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$product['minimum_stay'].'</MinLoSThrough></BookingRules>';
						}
					}
					else
					{
						//$update_values = 'no';
					}
					$rates_mxl	.=	'</HotelData>';
					$xml_update	.=	$rates_mxl;
				}
				if(@$update_values=='yes')
				{
					$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
										<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
										TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
										<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
										<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
										'.$xml_update.'
										</HotelUpdateRequest>
										</HotelUpdateRQ>
										';
					//echo $xml_post_string;					
					$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
					if($C_URL)
					{
						if($C_URL->mode=='0')
						{
							$url = explode(',',$C_URL->test_url);
							
						}
						else if($C_URL->mode=='1')
						{
							$url = explode(',',$C_URL->live_url);
						}
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_URL, $url[0]);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 500);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
						$ss = curl_getinfo($ch);
						$response = curl_exec($ch);
						//echo $response; die;
						$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
						$xml = simplexml_load_string($xml);
						$json = json_encode($xml);
						$responseArray = json_decode($json,true);
						$Errors = @$responseArray['Errors']['Error'];
						if($Errors!='')
						{
							$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,$Errors,'Bulk Update',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error',$Errors);
						}
						/* else
						{
							return true;
						} */
					}
					/* else
					{
						return false;
					} */
				}
				/* else
				{
					return false;
				} */
			}
			/* else
			{
				return false;
			} */
		}
		/* else
		{
			return false;
		} */
	}
	
	function sub_room_bulk_update($clean,$up_days)
    {
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 17;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
													
		foreach($clean as $id=>$room)
		{
			$sub_rooms['room_id']   = $id;

			foreach($room as $guest_count=>$type)
			{
				$sub_rooms['guest_count'] = $guest_count;
				
				foreach($type as $refun=>$value)
				{
					if(isset($value['open_room'])=='')
					{
						if(isset($value['stop_sell'])!='')
						{
							$stop_sale =$value['stop_sell'];
						}
						elseif(isset($value['stop_sell'])=='')
						{
							$stop_sale ='0';
						}
					}
					else if(isset($value['open_room'])!='')
					{
						$stop_sale ='remove';
					}
					if(in_array('1', $up_days)) 
					{
						$bnow_sun = 'true';
					}
					else 
					{
						$bnow_sun = 'false';
					}
					if(in_array('2', $up_days)) 
					{
						$bnow_mon = 'true';
					}
					else 
					{
						$bnow_mon = 'false';
					}
					if(in_array('3', $up_days)) 
					{
						$bnow_tue = 'true';
					}
					else 
					{
						$bnow_tue = 'false';
					}
					if(in_array('4', $up_days)) 
					{
						$bnow_wed = 'true';
					}
					else
					{
						$bnow_wed = 'false';
					}
					if(in_array('5', $up_days)) 
					{
						$bnow_thur = 'true';
					}
					else 
					{
						$bnow_thur = 'false';
					}
					if(in_array('6', $up_days)) 
					{
						$bnow_fri = 'true';
					}
					else 
					{
						$bnow_fri = 'false';
					}
					if(in_array('7', $up_days)) 
					{
						$bnow_sat = 'true';
					}
					else 
					{
						$bnow_sat = 'false';
					}
					
					if(@$value['non_refund_amount']!='')
					{
						$value_price = $value['non_refund_amount'];
					}
					elseif(@$value['refund_amount']!='')
					{
						$value_price = $value['refund_amount'];
					}
					else
					{
						$value_price = '0';
					}
					
					$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->count_all_results();
					
					if($count!=0)
					{
						$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->result();
						
						if($room_mapping)
						{
							$i=1;
							$xml_update	='';
							foreach($room_mapping as $room_value)
							{
								if($value_price != '0')
								{
									if($room_value->rate_conversion != "1")
									{
										$rate_converted = 1;
										if(strpos($room_value->rate_conversion, '.') !== FALSE)
										{
											$value_price = $value_price * $room_value->rate_conversion;
										}
										elseif (strpos($room_value->rate_conversion, ',') !== FALSE) 
										{
											$mul = str_replace(',', '.', $room_value->rate_conversion);
											$value_price = $value_price * $mul;
										}
										else if(is_numeric($value_price))
										{
											$value_price = $value_price * $room_value->rate_conversion;
										}
									}
								}
								
								$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row(); 
		
								$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
					
								$bnow_map = get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
					
								$rates_xml = array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
					
								$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
								$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';
		
								$rates_mxl 	.= 	'<ApplicationControl Start="'.$start_date.'" End="'.$end_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
								
								if($room_value->update_rate=='1')
								{
									$update_values = 'yes';
									
									$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
									
									if($mapping_values)
									{
										$label = explode(',', $mapping_values['label']);
										$values = explode(',', $mapping_values['value']);
										if($bnow_map['explevel']=='OBP')
										{
											$set_arr=array_combine($label,$values);
										}
										else if($bnow_map['explevel']=='BRP')
										{
											$set_arr=array_combine($label,$values);
											$set_arr = array_slice($set_arr, -2, 2, true);
											if($value_price!='')
											{
												$value_price = number_format((float)$value_price, 2, '.', '');
												$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$value_price.'"></Base>';
											}
										}
										foreach($set_arr as $k=>$v)
										{
											if($v !='' && $value_price!='')
											{
												if(strpos($v, '+') !== FALSE)
												{
													$opr = explode('+', $v);
													if(is_numeric($opr[1]))
													{
														$ex_price = $value_price + $opr[1];
														$ex_price = number_format((float)$ex_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
														}
													}
													else if(is_numeric($opr[0]))
													{
														$ex_price = $value_price + $opr[0];
														$ex_price = number_format((float)$ex_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
														}
													}
													else
													{
														if(strpos($opr[1], '%'))
														{
															$per = explode('%',$opr[1]);
															if(is_numeric($per[0]))
															{
																$per_price = ($value_price * $per[0]) / 100;
																$per_price = number_format((float)$per_price, 2, '.', '');
																if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																{
																	$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																}
																else
																{
																	$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																}
															}
														}
														elseif (strpos($opr[0], '%')) 
														{
															$per = explode('%',$opr[0]);
															if(is_numeric($per[0]))
															{
																$per_price = ($value_price * $per[0]) / 100;
																$per_price = number_format((float)$per_price, 2, '.', '');
																if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																{
																	$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																}
																else
																{
																	$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																}
															}
														}
													}
												}
												elseif (strpos($v, '-') !== FALSE) 
												{
													$opr = explode('-', $v);
													if(is_numeric($opr[1]))
													{
														$ex_price = $value_price - $opr[1];
													}
													elseif (is_numeric($opr[0])) 
													{
														$ex_price = $value_price - $opr[0];
													}
													else
													{
														if(strpos($opr[1],'%') !== FALSE)
														{
															$per = explode('%',$opr[1]);
															if(is_numeric($per[0]))
															{
																$per_price = ($value_price * $per[0]) / 100;
																$per_price = number_format((float)$per_price, 2, '.', '');
																if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																{
																	$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																}
																else
																{
																	$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																}
															}
														}
														elseif (strpos($opr[0],'%') !== FALSE) 
														{
															$per = explode('%',$opr[0]);
															if(is_numeric($per[0]))
															{
																$per_price = ($value_price * $per[0]) / 100;
																$per_price = number_format((float)$per_price, 2, '.', '');
																if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																{
																	$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																}
																else
																{
																	$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																}
															}
														}
													}
												}
												elseif (strpos($v, '%') !== FALSE) 
												{
													$opr = explode('%', $v);
													if(is_numeric($opr[1]))
													{
														$per_price = ($value_price * $opr[1]) / 100;
														$per_price = number_format((float)$per_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
													elseif (is_numeric($opr[0])) 
													{
														$per_price 	= ($value_price * $opr[0]) / 100;
														$per_price  = number_format((float)$per_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
												}
												else
												{
													$ex_price = $value_price + $v;
													$ex_price = number_format((float)$ex_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
													}
												}
											}
										}
									}
									$rates_mxl 	.=	'</RateAmounts>	';
								}
								if($room_value->update_availability=='1')
								{
									$update_values = 'no';
									
									if ($stop_sale == "1") 
									{
										$rates_mxl 	.='<Availability Master="Closed"/>';
									}
									else if($stop_sale == "remove")
									{
										$rates_mxl 	.='<Availability Master="Open"/>';
									}
									
									if(@$value['availability']!='')
									{
										$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$value['availability'].'"/></BookingLimit>';
									}
									
									if(@$value['minimum_stay'] != "")
									{
										$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$value['minimum_stay'].'</MinLoSThrough></BookingRules>';
									}
								}
								else
								{
									$update_values = 'no';
								}
								$rates_mxl	.=	'</HotelData>';
								$xml_update	.=	$rates_mxl;
							}
							if($update_values=='yes')
							{
								$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
													<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
													TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
													<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
													<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
													'.$xml_update.'
													</HotelUpdateRequest>
													</HotelUpdateRQ>
													';
								$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
								if($C_URL)
								{
									if($C_URL->mode=='0')
									{
										$url = explode(',',$C_URL->test_url);
										
									}
									else if($C_URL->mode=='1')
									{
										$url = explode(',',$C_URL->live_url);
									}
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
									curl_setopt($ch, CURLOPT_URL, $url[0]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
									curl_setopt($ch, CURLOPT_TIMEOUT, 500);
									curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
									curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
									curl_setopt($ch, CURLOPT_POST, true);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
									$ss = curl_getinfo($ch);
									$response = curl_exec($ch);
									$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
									$xml = simplexml_load_string($xml);
									$json = json_encode($xml);
									$responseArray = json_decode($json,true);
									$Errors = @$responseArray['Errors']['Error'];
									if($Errors!='')
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,$Errors,'Bulk Update',date('m/d/Y h:i:s a', time()));
										$this->session->set_flashdata('bulk_error',$Errors);
									}
									/* else
									{
										return true;
									} */
								}
								/* else
								{
									return false;
								} */
							}
							/* else
							{
								return false;
							} */
						}
						/* else
						{
							return false;
						} */
					}
					/* else
					{
						return false;
					} */
				}
			}
		}
    }
	
	function main_rate_bulk_update($clean,$up_days)
    {
		/* echo '<pre>';
		print_r($clean); die; */
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 17;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
		
		foreach($clean as $id=>$room)
		{
			foreach($room as $rate_types_id=>$type)
			{
				//echo $rate_types_id;
				if(in_array('1', $up_days)) 
				{
					$bnow_sun = 'true';
				}
				else 
				{
					$bnow_sun = 'false';
				}
				if(in_array('2', $up_days)) 
				{
					$bnow_mon = 'true';
				}
				else 
				{
					$bnow_mon = 'false';
				}
				if(in_array('3', $up_days)) 
				{
					$bnow_tue = 'true';
				}
				else 
				{
					$bnow_tue = 'false';
				}
				if(in_array('4', $up_days)) 
				{
					$bnow_wed = 'true';
				}
				else
				{
					$bnow_wed = 'false';
				}
				if(in_array('5', $up_days)) 
				{
					$bnow_thur = 'true';
				}
				else 
				{
					$bnow_thur = 'false';
				}
				if(in_array('6', $up_days)) 
				{
					$bnow_fri = 'true';
				}
				else 
				{
					$bnow_fri = 'false';
				}
				if(in_array('7', $up_days)) 
				{
					$bnow_sat = 'true';
				}
				else 
				{
					$bnow_sat = 'false';
				}
				if(isset($type['open_room'])=='')
				{
					if(isset($type['stop_sell'])!='')
					{
						$stop_sale =$type['stop_sell'];
					}
					elseif(isset($type['stop_sell'])=='')
					{
						$stop_sale ='0';
					}
				}
				else if(isset($type['open_room'])!='')
				{
					$stop_sale ='remove';
				}
				
				$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>'0','refun_type'=>'0','enabled'=>'enabled'))->count_all_results();
				if($count!=0)
				{
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>'0','refun_type'=>'0','enabled'=>'enabled'))->result();
					//echo '<pre>'; print_r($room_mapping);
					if($room_mapping)
					{
						$i=1;
						$xml_update	='';
						foreach($room_mapping as $room_value)
						{
							//echo $i;
							if(@$type['price'] != '')
							{
								if($room_value->rate_conversion != "1")
								{
									$rate_converted = 1;
									if(strpos($room_value->rate_conversion, '.') !== false)
									{
										$type['price'] = $type['price'] * $room_value->rate_conversion;
									}
									else if(strpos($room_value->rate_conversion, ',') !== FALSE)
									{
										$mul = str_replace(',', '.', $room_value->rate_conversion);
										$type['price'] = $type['price'] * $mul;
									}
									else if(is_numeric($type['price']))
									{
										$type['price'] = $type['price'] * $room_value->rate_conversion;
									}
								}
							}
							
							$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row(); 
		
							$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
				
							$bnow_map = get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
				
							$rates_xml = array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
				
							$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
					
							$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';
				
							$rates_mxl 	.= 	'<ApplicationControl Start="'.$start_date.'" End="'.$end_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
							
							$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
							
							if($room_value->update_rate=='1')
							{
								$update_values = 'yes';
								
								if($mapping_values)
								{
									$label = explode(',', $mapping_values['label']);
									$value = explode(',', $mapping_values['value']);
									if($bnow_map['explevel']=='OBP')
									{
										$set_arr=array_combine($label,$value);
									}
									else if($bnow_map['explevel']=='BRP')
									{
										$set_arr=array_combine($label,$value);
										$set_arr = array_slice($set_arr, -2, 2, true);
										if(@$type['price']!='')
										{
											$prices = number_format((float)@$type['price'], 2, '.', '');
											$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$prices.'"></Base>';
										}
									}
									foreach($set_arr as $k=>$v)
									{
										if($v !='' && @$type['price']!='')
										{
											if(strpos($v, '+') !== FALSE)
											{
												$opr = explode('+', $v);
												if(is_numeric($opr[1]))
												{
													$ex_price = @$type['price'] + $opr[1];
													$ex_price = number_format((float)$ex_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
													}
												}
												else if(is_numeric($opr[0]))
												{
													$ex_price = @$type['price'] + $opr[0];
													$ex_price = number_format((float)$ex_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
													}
												}
												else
												{
													if(strpos($opr[1], '%'))
													{
														$per = explode('%',$opr[1]);
														if(is_numeric($per[0]))
														{
															$per_price = (@$type['price'] * $per[0]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
													}
													elseif (strpos($opr[0], '%')) 
													{
														$per = explode('%',$opr[0]);
														if(is_numeric($per[0]))
														{
															$per_price = (@$type['price'] * $per[0]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
													}
												}
											}
											elseif (strpos($v, '-') !== FALSE) 
											{
												$opr = explode('-', $v);
												if(is_numeric($opr[1]))
												{
													$ex_price = @$type['price'] - $opr[1];
												}
												elseif (is_numeric($opr[0])) 
												{
													$ex_price = @$type['price'] - $opr[0];
												}
												else
												{
													if(strpos($opr[1],'%') !== FALSE)
													{
														$per = explode('%',$opr[1]);
														if(is_numeric($per[0]))
														{
															$per_price = (@$type['price'] * $per[0]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
													}
													elseif (strpos($opr[0],'%') !== FALSE) 
													{
														$per = explode('%',$opr[0]);
														if(is_numeric($per[0]))
														{
															$per_price = (@$type['price'] * $per[0]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
													}
												}
											}
											elseif (strpos($v, '%') !== FALSE) 
											{
												$opr = explode('%', $v);
												if(is_numeric($opr[1]))
												{
													$per_price = (@$type['price'] * $opr[1]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
												elseif (is_numeric($opr[0])) 
												{
													$per_price 	= (@$type['price'] * $opr[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											else
											{
												$ex_price = @$type['price'] + $v;
												$ex_price = number_format((float)$ex_price, 2, '.', '');
												if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
												{
													
													$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
												}
												else
												{
													$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
												}
											}
										}
									}
								}
							
								$rates_mxl 	.=	'</RateAmounts>	';
							}
							if($room_value->update_availability=='1')
							{
							 	$update_values = 'yes';
							
								if ($stop_sale == "1") 
								{
									$rates_mxl 	.='<Availability Master="Closed"/>';
								}
								else if($stop_sale == "remove")
								{
									$rates_mxl 	.='<Availability Master="Open"/>';
								}
								
								if(@$type['availability']!='')
								{
									$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$type['availability'].'"/></BookingLimit>';
								}
								
								if(@$type['minimum_stay'] != "")
								{
									$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$type['minimum_stay'].'</MinLoSThrough></BookingRules>';
								}
							}
							else
							{
								$update_values = 'no';
							}
							$rates_mxl	.=	'</HotelData>';
							$xml_update	.=	$rates_mxl;
						}
						if($update_values=='yes')
						{
							$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
										<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
										TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
										<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
										<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
										'.$xml_update.'
										</HotelUpdateRequest>
										</HotelUpdateRQ>
										';
							//echo $xml_post_string;
							$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
							if($C_URL)
							{
								if($C_URL->mode=='0')
								{
									$url = explode(',',$C_URL->test_url);
									
								}
								else if($C_URL->mode=='1')
								{
									$url = explode(',',$C_URL->live_url);
								}
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt($ch, CURLOPT_URL, $url[0]);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
								curl_setopt($ch, CURLOPT_TIMEOUT, 500);
								curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
								curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
								$ss = curl_getinfo($ch);
								$response = curl_exec($ch);
								//echo $response;
								$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
								$xml = simplexml_load_string($xml);
								$json = json_encode($xml);
								$responseArray = json_decode($json,true);
								$Errors = @$responseArray['Errors']['Error'];
								if($Errors!='')
								{
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,$Errors,'Bulk Update',date('m/d/Y h:i:s a', time()));
									$this->session->set_flashdata('bulk_error',$Errors);
								}
								/* else
								{
									return true;
								} */
							}
							/* else
							{
								return false;
							} */
						}
						/* else
						{
							return false;
						} */
					}
					/* else
					{
						return false;
					} */
				}
				/* else
				{
					return false;
				} */
			}
		}
	}
	
	function sub_rate_bulk_update($clean,$up_days)
	{
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 17;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
		
		foreach($clean as $id=>$rooms)
		{
			$room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$id))->row();
			
			foreach($rooms as $rate_types_id=>$room)
			{
				foreach($room as $guest_count=>$type)
				{
					foreach($type as $refun=>$value)
					{
						if(isset($value['open_room'])=='')
						{
							if(isset($value['stop_sell'])!='')
							{
								$stop_sale =$value['stop_sell'];
							}
							elseif(isset($value['stop_sell'])=='')
							{
								$stop_sale ='0';
							}
						}
						else if(isset($value['open_room'])!='')
						{
							$stop_sale ='remove';
						}
						if(in_array('1', $up_days)) 
						{
							$bnow_sun = 'true';
						}
						else 
						{
							$bnow_sun = 'false';
						}
						if(in_array('2', $up_days)) 
						{
							$bnow_mon = 'true';
						}
						else 
						{
							$bnow_mon = 'false';
						}
						if(in_array('3', $up_days)) 
						{
							$bnow_tue = 'true';
						}
						else 
						{
							$bnow_tue = 'false';
						}
						if(in_array('4', $up_days)) 
						{
							$bnow_wed = 'true';
						}
						else
						{
							$bnow_wed = 'false';
						}
						if(in_array('5', $up_days)) 
						{
							$bnow_thur = 'true';
						}
						else 
						{
							$bnow_thur = 'false';
						}
						if(in_array('6', $up_days)) 
						{
							$bnow_fri = 'true';
						}
						else 
						{
							$bnow_fri = 'false';
						}
						if(in_array('7', $up_days)) 
						{
							$bnow_sat = 'true';
						}
						else 
						{
							$bnow_sat = 'false';
						}
						
						if(@$value['non_refund_amount']!='')
						{
							$value_price = $value['non_refund_amount'];
						}
						elseif(@$value['refund_amount']!='')
						{
							$value_price = $value['refund_amount'];
						}
						else
						{
							$value_price = '0';
						}
						$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->count_all_results();
						if($count!=0)
						{
							$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->result();
							
							if($room_mapping)
							{
								$i=1;
								$xml_update	='';
								foreach($room_mapping as $room_value)
								{
									if($value_price != '0')
									{
										if($room_value->rate_conversion != "1")
										{
											$rate_converted = 1;
											if(strpos($room_value->rate_conversion, '.') !== false)
											{
												$value_price = $value_price * $room_value->rate_conversion;
											}
											else if(strpos($room_value->rate_conversion, ',') !== FALSE)
											{
												$mul = str_replace(',', '.', $room_value->rate_conversion);
												$value_price = $value_price * $mul;
											}
											else if(is_numeric($value_price))
											{
												$value_price = $value_price * $room_value->rate_conversion;
											}
										}
									}
									
									$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row(); 
		
									$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
						
									$bnow_map = get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
						
									$rates_xml = array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
						
									$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
									$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';
			
									$rates_mxl 	.= 	'<ApplicationControl Start="'.$start_date.'" End="'.$end_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
									if($room_value->update_rate=='1')
									{
										$update_values = 'yes';
										
										$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
									
										if($mapping_values)
										{
											$label = explode(',', $mapping_values['label']);
											$values = explode(',', $mapping_values['value']);
											if($bnow_map['explevel']=='OBP')
											{
												$set_arr=array_combine($label,$values);
											}
											else if($bnow_map['explevel']=='BRP')
											{
												$set_arr=array_combine($label,$values);
												$set_arr = array_slice($set_arr, -2, 2, true);
												if($value_price!='')
												{
													$value_price = number_format((float)$value_price, 2, '.', '');
													$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$value_price.'"></Base>';
												}
											}
											foreach($set_arr as $k=>$v)
											{
												if($v !='' && $value_price!='')
												{
													if(strpos($v, '+') !== FALSE)
													{
														$opr = explode('+', $v);
														if(is_numeric($opr[1]))
														{
															$ex_price = $value_price + $opr[1];
															$ex_price = number_format((float)$ex_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
															}
														}
														else if(is_numeric($opr[0]))
														{
															$ex_price = $value_price + $opr[0];
															$ex_price = number_format((float)$ex_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
															}
														}
														else
														{
															if(strpos($opr[1], '%'))
															{
																$per = explode('%',$opr[1]);
																if(is_numeric($per[0]))
																{
																	$per_price = ($value_price * $per[0]) / 100;
																	$per_price = number_format((float)$per_price, 2, '.', '');
																	if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																	{
																		$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																	}
																	else
																	{
																		$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																	}
																}
															}
															elseif (strpos($opr[0], '%')) 
															{
																$per = explode('%',$opr[0]);
																if(is_numeric($per[0]))
																{
																	$per_price = ($value_price * $per[0]) / 100;
																	$per_price = number_format((float)$per_price, 2, '.', '');
																	if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																	{
																		$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																	}
																	else
																	{
																		$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																	}
																}
															}
														}
													}
													elseif (strpos($v, '-') !== FALSE) 
													{
														$opr = explode('-', $v);
														if(is_numeric($opr[1]))
														{
															$ex_price = $value_price - $opr[1];
														}
														elseif (is_numeric($opr[0])) 
														{
															$ex_price = $value_price - $opr[0];
														}
														else
														{
															if(strpos($opr[1],'%') !== FALSE)
															{
																$per = explode('%',$opr[1]);
																if(is_numeric($per[0]))
																{
																	$per_price = ($value_price * $per[0]) / 100;
																	$per_price = number_format((float)$per_price, 2, '.', '');
																	if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																	{
																		$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																	}
																	else
																	{
																		$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																	}
																}
															}
															elseif (strpos($opr[0],'%') !== FALSE) 
															{
																$per = explode('%',$opr[0]);
																if(is_numeric($per[0]))
																{
																	$per_price = ($value_price * $per[0]) / 100;
																	$per_price = number_format((float)$per_price, 2, '.', '');
																	if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
																	{
																		$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
																	}
																	else
																	{
																		$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
																	}
																}
															}
														}
													}
													elseif (strpos($v, '%') !== FALSE) 
													{
														$opr = explode('%', $v);
														if(is_numeric($opr[1]))
														{
															$per_price = ($value_price * $opr[1]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
														elseif (is_numeric($opr[0])) 
														{
															$per_price 	= ($value_price * $opr[0]) / 100;
															$per_price = number_format((float)$per_price, 2, '.', '');
															if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
															{
																$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
															}
															else
															{
																$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
															}
														}
													}
													else
													{
														$ex_price = $value_price + $v;
														$ex_price = number_format((float)$ex_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
														}
													}
												}
											}
										}
									
										$rates_mxl 	.=	'</RateAmounts>	';
									}
									if($room_value->update_availability=='1')
									{
										$update_values = 'no';
										
										if ($stop_sale == "1") 
										{
											$rates_mxl 	.='<Availability Master="Closed"/>';
										}
										else if($stop_sale == "remove")
										{
											$rates_mxl 	.='<Availability Master="Open"/>';
										}
										
										if(@$value['availability']!='')
										{
											$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$value['availability'].'"/></BookingLimit>';
										}
										
										if(@$value['minimum_stay'] != "")
										{
											$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$value['minimum_stay'].'</MinLoSThrough></BookingRules>';
										}
									}
									else
									{
										$update_values='no';
									}
									$rates_mxl	.=	'</HotelData>';
									$xml_update	.=	$rates_mxl;
								}
								if($update_values=='yes')
								{
									$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
													<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
													TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
													<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
													<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
													'.$xml_update.'
													</HotelUpdateRequest>
													</HotelUpdateRQ>
													';
									$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
									if($C_URL)
									{
										if($C_URL->mode=='0')
										{
											$url = explode(',',$C_URL->test_url);
											
										}
										else if($C_URL->mode=='1')
										{
											$url = explode(',',$C_URL->live_url);
										}
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
										curl_setopt($ch, CURLOPT_URL, $url[0]);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
										curl_setopt($ch, CURLOPT_TIMEOUT, 500);
										curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
										curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
										curl_setopt($ch, CURLOPT_POST, true);
										curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
										$ss = curl_getinfo($ch);
										$response = curl_exec($ch);
										$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
										$xml = simplexml_load_string($xml);
										$json = json_encode($xml);
										$responseArray = json_decode($json,true);
										$Errors = @$responseArray['Errors']['Error'];
										if($Errors!='')
										{
											$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,$Errors,'Bulk Update',date('m/d/Y h:i:s a', time()));
											$this->session->set_flashdata('bulk_error',$Errors);
										}
										/* else
										{
											return true;
										} */
									}
									/* else
									{
										return false;
									} */
								}
								/* else
								{
									return false;
								} */
							}
							/* else
							{
								return false;
							} */
						}
						/* else
						{
							return false;
						} */
					}
				}
			}
		}
	}
	
	function inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$con_ch = 17;
		
		if($table=='room_update')
		{
			$udata[$column] = $org_price;

			if($column != "availability")
			{
				$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			}else{
				$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'enabled'=>'enabled','update_availability'=>1))->count_all_results();
			}
			if($update!='stop_sell')
			{
				$available1= get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date))->row_array();
				
				if(count($available1)!=0)
				{ 
					$this->db->where('owner_id', current_user_type());
					$this->db->where('hotel_id', hotel_id());
					$this->db->where('room_id', $room_id);
					$this->db->where('separate_date', $update_date);
					$this->db->where('individual_channel_id', $con_ch);
					$this->db->update(TBL_UPDATE, $udata);
				}
				else
				{
					$udata['owner_id']      = current_user_type();
					$udata['hotel_id']      = hotel_id();
					$udata['room_id']       = $room_id;
					$udata['days']          = '1,2,3,4,5,6,7';
					$udata['separate_date'] = $update_date;
					$udata['individual_channel_id'] = $con_ch;
					$this->db->insert(TBL_UPDATE, $udata);
				}
			}
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='reservation_table')
		{
			$udata[$column] = $org_price;
			
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			if($update!='stop_sell')
			{
				$available1 = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>$con_ch))->row_array();
				
				if(count($available1)!=0)
				{ 
					$this->db->where('owner_id', current_user_type());
					$this->db->where('hotel_id', hotel_id());
					$this->db->where('room_id', $room_id);
					$this->db->where('separate_date', $update_date);
					$this->db->where('guest_count', $guest_count);
					$this->db->where('refun_type', $refunds);
					$this->db->where('individual_channel_id', $con_ch);
					$this->db->update(RESERV, $udata);
				}
				else
				{
					$udata['room_id']       = $room_id;
					$udata['separate_date'] = $update_date;
					$udata['owner_id']      = current_user_type();
					$udata['hotel_id']      = hotel_id();
					$udata['guest_count']   = $guest_count;
					$udata['refun_type']    = $refunds;
					$udata['individual_channel_id'] = $con_ch;
					$this->db->insert(RESERV, $udata);
				}
			}
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;

		}
		else if($table=='room_rate_types_base')
		{
			$udata[$column] = $org_price;

			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
	
			if($update!='stop_sell')
			{
				$available1= get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row_array();
				 
				if(count($available1)!=0)
				{ 
					$this->db->where('owner_id', current_user_type());
					$this->db->where('hotel_id', hotel_id());
					$this->db->where('room_id', $room_id);
					$this->db->where('rate_types_id', $rate_type_id);
					$this->db->where('separate_date', $update_date);
					$this->db->where('individual_channel_id', $con_ch);
					$this->db->update(RATE_BASE, $udata);
				}
				else
				{
					$udata['owner_id']      = current_user_type();
					$udata['hotel_id']      = hotel_id();
					$udata['room_id']       = $room_id;
					$udata['rate_types_id'] = $rate_type_id;
					$udata['days']          = '1,2,3,4,5,6,7';
					$udata['separate_date'] = $update_date;
					$udata['individual_channel_id'] = $con_ch;
					$this->db->insert(RATE_BASE, $udata);
				}
			}
			
			$update_Details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='room_rate_types_additional')
		{
			$udata[$column] = $org_price;

			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
			
			if($update!='stop_sell')
			{ 
				$available1= get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds))->row_array();
				
				if(count($available1)!=0)
				{ 

					$this->db->where('owner_id', current_user_type());
					$this->db->where('hotel_id', hotel_id());
					$this->db->where('room_id', $room_id);
					$this->db->where('rate_types_id', $rate_type_id);
					$this->db->where('separate_date', $update_date);
					$this->db->where('guest_count', $guest_count);
					$this->db->where('refun_type', $refunds);
					$this->db->where('individual_channel_id', $con_ch);
					$this->db->update(RATE_ADD, $udata);
				}
				else
				{
					$udata['room_id']       = $room_id;
					$udata['rate_types_id'] = $rate_type_id;
					$udata['separate_date'] = $update_date;
					$udata['owner_id']      = current_user_type();
					$udata['hotel_id']      = hotel_id();
					$udata['guest_count']   = $guest_count;
					$udata['refun_type']    = $refunds;
					$udata['individual_channel_id'] = $con_ch;
					$this->db->insert(RATE_ADD, $udata);
				}
			}
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;

		}

		$separate_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
		
		if($count!=0)
		{
			if($column == "availability")
			{
				$select = 'update_availability';
			}
			else
			{
				$select = 'update_rate';
			}
			
			if($table=='room_update')
			{
				if($column != "availability"){
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled',$select=>1))->result();
				}else{
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'enabled'=>'enabled',$select=>1))->result();
				}
			}
			if($table=='reservation_table')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled',$select=>1))->result();
			}
			if($table=='room_rate_types_base')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled',$select=>1))->result();
			}
			if($table=='room_rate_types_additional')
			{
				 $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled',$select=>1))->result();
			}
			if($room_mapping)
			{	
				$i=1;
				$xml_update	='';
				foreach($room_mapping as $room_value)
				{
					if($update=='price' && $value_price != '0')
					{
						if($room_value->rate_conversion != "1")
						{
							$rate_converted = 1;
							if(strpos($room_value->rate_conversion, '.') !== false)
							{
								$value_price = $value_price * $room_value->rate_conversion;
							}
							else if(strpos($room_value->rate_conversion, ',') !== FALSE)
							{
								$mul = str_replace(',', '.', $room_value->rate_conversion);
								$value_price = $value_price * $mul;
							}
							else if(is_numeric($value_price))
							{
								$value_price = $value_price * $room_value->rate_conversion;
							}
						}
					}
					
					$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
					
					$bnow_map 	= get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
					
					$rates_xml 	= array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
		
					$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
					
					$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';

					$bnow_sun = 'true';
					$bnow_mon = 'true';
					$bnow_tue = 'true';
					$bnow_wed = 'true';
					$bnow_thur = 'true';
					$bnow_fri = 'true';
					$bnow_sat = 'true';
					
					$rates_mxl 	.= 	'<ApplicationControl Start="'.$separate_date.'" End="'.$separate_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
					
					if($update=='price' && $room_value->update_rate=='1')
					{
						$update_values = 'yes';
						
						$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
						
						if($mapping_values)
						{
							$label = explode(',', $mapping_values['label']);
							$value = explode(',', $mapping_values['value']);

							if($bnow_map['explevel']=='OBP')
							{
								$set_arr=array_combine($label,$value);
							}
							else if($bnow_map['explevel']=='BRP')
							{
								$set_arr=array_combine($label,$value);
								$set_arr = array_slice($set_arr, -2, 2, true);
								if($value_price!='')
								{
									$value_price = number_format((float)$value_price, 2, '.', '');
									$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$value_price.'"></Base>';
								}
							}
							foreach($set_arr as $k=>$v)
							{
								if($v !='' && $value_price!='')
								{
									if(strpos($v, '+') !== FALSE)
									{
										$opr = explode('+', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = $value_price + $opr[1];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else if(is_numeric($opr[0]))
										{
											$ex_price = $value_price + $opr[0];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else
										{
											if(strpos($opr[1], '%'))
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0], '%')) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '-') !== FALSE) 
									{
										$opr = explode('-', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = $value_price - $opr[1];
										}
										elseif (is_numeric($opr[0])) 
										{
											$ex_price = $value_price - $opr[0];
										}
										else
										{
											if(strpos($opr[1],'%') !== FALSE)
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0],'%') !== FALSE) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '%') !== FALSE) 
									{
										$opr = explode('%', $v);
										if(is_numeric($opr[1]))
										{
											$per_price = ($value_price * $opr[1]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
										elseif (is_numeric($opr[0])) 
										{
											$per_price 	= ($value_price * $opr[0]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
									}
									else
									{
										$ex_price = $value_price + $v;
										$ex_price = number_format((float)$ex_price, 2, '.', '');
										if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
										{
											
											$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
										}
										else
										{
											$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
										}
									}
								}
							}
						}
						$rates_mxl 	.=	'</RateAmounts>	';
					}

					if($room_value->update_availability=='1')
					{
						$update_values = 'yes';
						
						if ($update=='stop_sell' && $org_price == 1) 
						{
							$rates_mxl 	.='<Availability Master="Closed"/>';
						}
						else if ($update=='stop_sell' && $org_price == "remove")
						{
							$rates_mxl 	.='<Availability Master="Open"/>';
						}
						
						if($update=='availability' && $value_price!='')
						{
							$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$value_price.'"/></BookingLimit>';
						}
						
						if($update=='minimum_stay' && $value_price!='')
						{
							$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$value_price.'</MinLoSThrough></BookingRules>';
						}
					}
					/* else
					{
						$update_values = 'no';
					} */
					$rates_mxl	.=	'</HotelData>';
					$xml_update	.=	$rates_mxl;
				}
				if(@$update_values=='yes')
				{
					$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
												<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
												TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
												<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
												<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
												'.$xml_update.'
												</HotelUpdateRequest>
												</HotelUpdateRQ>
												';
				
					//echo $xml_post_string;// die;
					$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
					if($C_URL)
					{
						if($C_URL->mode=='0')
						{
							$url = explode(',',$C_URL->test_url);
							
						}
						else if($C_URL->mode=='1')
						{
							$url = explode(',',$C_URL->live_url);
						}
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_URL, $url[0]);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 500);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
						$ss = curl_getinfo($ch);
						$response = curl_exec($ch);
						$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
						$xml = simplexml_load_string($xml);
						$json = json_encode($xml);
						$responseArray = json_decode($json,true);
						$Errors = @$responseArray['Errors']['Error'];
						if($Errors!='')
						{
							$this->inventory_model->store_error(current_user_type(),hotel_id(),$con_ch,$Errors,'Inline Edit At Main Calendar',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error',$Errors);
						}
						/* else
						{
							return true;
						} */
					}
					/* else
					{
						return false;
					} */
				}
				/* else
				{
					return false;
				} */
			}
		}
		/* else
		{
			return false;
		} */
	}
	
	function fullUpdate($source,$period)
	{
		/* echo '<pre>';
		foreach($period as $date)
		{
			echo $date->format("d/m/Y").'<br>';
		}
				
		die; */
		$con_ch = 17;
		
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>17))->row();
		
		$rates_xml 	= array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
		
		//echo '<pre>';
		if($source != "Synchronize")
		{
			$individual_channel_id	=	'17';
		}
		else if($source == "Synchronize")
		{
			$individual_channel_id	=	'0';
		}
		$all_map_rooms= get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'enabled'=>'enabled','channel_id'=>17))->result();
		//print_r($all_map_rooms); die;
		if(count($all_map_rooms)!=0)
		{
			$i=1;
			$xml_update	='';
			foreach ($all_map_rooms as $room_value) 
            {   
				$mp_details 	=	get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();

				$mapping_values = 	get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
				
				$bnow_map 		= 	get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
				
				foreach($period as $date)
                {
					$separate_date = date('Y-m-d',strtotime(str_replace('/','-',$date)));
					
					if($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
					{
						$update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$date))->row();
						if(count($update_details)!=0)
						{
							$value_price	=	$update_details->price;
						}
						else
						{
							$value_price	=	'';
						}
						if($individual_channel_id==17)
						{
							$value_price = $value_price;
						}
						else if($individual_channel_id==0)
						{
							$value_price = $value_price;
							
							if($room_value->rate_conversion != "1")
							{
								$rate_converted = 1;
								if(strpos($room_value->rate_conversion, '.') !== false)
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
								else if(strpos($room_value->rate_conversion, ',') !== FALSE)
								{
									$mul = str_replace(',', '.', $room_value->rate_conversion);
									$value_price = $value_price * $mul;
								}
								else if(is_numeric($value_price))
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
							}
						}
					}
					elseif($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count!='0' && $room_value->refun_type!='0')
					{
						$update_details = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();
						
						if(count($update_details)!=0)
						{		
							if($update_details->refun_type=='1')
							{
								$value_price	=	$update_details->refund_amount;;
							}
							elseif($update_details->refun_type=='2')
							{
								$value_price 	= 	$update_details->non_refund_amount;
							}
						}
						else
						{
							$value_price	=	'';
						}
						
						if($individual_channel_id==17)
						{
							$value_price	=	$value_price;
						}
						else if($individual_channel_id==0)
						{
							$value_price	=	$value_price;
							
							if($room_value->rate_conversion != "1")
							{
								$rate_converted = 1;
								if(strpos($room_value->rate_conversion, '.') !== false)
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
								else if(strpos($room_value->rate_conversion, ',') !== FALSE)
								{
									$mul = str_replace(',', '.', $room_value->rate_conversion);
									$value_price = $value_price * $mul;
								}
								else if(is_numeric($value_price))
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
							}
						}						
					}
					elseif($room_value->property_id!='0' && $room_value->rate_id!='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
					{
						$update_details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$date))->row();
						
						if(count($update_details)!=0)
						{
							$value_price	=	$update_details->price;
						}
						else
						{
							$value_price	=	'';
						}
						if($individual_channel_id==17)
						{
							$value_price	=	$value_price;
						}
						else if($individual_channel_id==0)
						{
							$value_price	=	$value_price;
							
							if($room_value->rate_conversion != "1")
							{
								$rate_converted = 1;
								if(strpos($room_value->rate_conversion, '.') !== false)
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
								else if(strpos($room_value->rate_conversion, ',') !== FALSE)
								{
									$mul = str_replace(',', '.', $room_value->rate_conversion);
									$value_price = $value_price * $mul;
								}
								else if(is_numeric($value_price))
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
							}
						}
					}
					elseif($room_value->property_id!='0' && $room_value->rate_id!='0' && $room_value->guest_count!='0' && $room_value->refun_type!='0')
					{
						$update_details = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();
						
						if(count($update_details)!=0)
						{		
							if($update_details->refun_type=='1')
							{
								$value_price	=	$update_details->refund_amount;;
							}
							elseif($update_details->refun_type=='2')
							{
								$value_price 	= 	$update_details->non_refund_amount;
							}
						}
						else
						{
							$value_price	=	'';
						}
						
						if($individual_channel_id==17)
						{
							$value_price	=	$value_price;
						}
						else if($individual_channel_id==0)
						{
							$value_price	=	$value_price;
							
							if($room_value->rate_conversion != "1")
							{
								$rate_converted = 1;
								if(strpos($room_value->rate_conversion, '.') !== false)
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
								else if(strpos($room_value->rate_conversion, ',') !== FALSE)
								{
									$mul = str_replace(',', '.', $room_value->rate_conversion);
									$value_price = $value_price * $mul;
								}
								else if(is_numeric($value_price))
								{
									$value_price = $value_price * $room_value->rate_conversion;
								}
							}
						}
					}
					if(count($update_details)!=0)
					{
						$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
						
						$availability	=	$update_details->availability;
						$minimum_stay	=	$update_details->minimum_stay;
						$stop_sell		=	$update_details->stop_sell;
						$open_room		=	$update_details->open_room;
						if($stop_sell==0 && $open_room==0)
						{
							$org_price='remove';
						}
						else if($stop_sell==0 && $open_room==1) 
						{
							$org_price='remove';
						}
						else if($stop_sell==1 && $open_room==0) 
						{
							$org_price='1';
						}
						else if($stop_sell==1 && $open_room==1) 
						{
							$org_price='remove';
						}
						$rates_mxl		.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';

						$bnow_sun = 'true';
						$bnow_mon = 'true';
						$bnow_tue = 'true';
						$bnow_wed = 'true';
						$bnow_thur = 'true';
						$bnow_fri = 'true';
						$bnow_sat = 'true';
				
						$rates_mxl 	.= 	'<ApplicationControl Start="'.$separate_date.'" End="'.$separate_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
						
						if($room_value->update_rate=='1')
						{
							$update_values = 'yes';
							
							$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
							
							if($mapping_values)
							{
								$label = explode(',', $mapping_values['label']);
								$value = explode(',', $mapping_values['value']);

								if($bnow_map['explevel']=='OBP')
								{
									$set_arr=array_combine($label,$value);
								}
								else if($bnow_map['explevel']=='BRP')
								{
									$set_arr=array_combine($label,$value);
									$set_arr = array_slice($set_arr, -2, 2, true);
									if($value_price!='')
									{
										$value_price = number_format((float)$value_price, 2, '.', '');
										$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$value_price.'"></Base>';
									}
								}
								foreach($set_arr as $k=>$v)
								{
									if($v !='' && $value_price!='')
									{
										if(strpos($v, '+') !== FALSE)
										{
											$opr = explode('+', $v);
											if(is_numeric($opr[1]))
											{
												$ex_price = $value_price + $opr[1];
												$ex_price = number_format((float)$ex_price, 2, '.', '');
												if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
												{
													$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'
													"></Additional>';
												}
												else
												{
													$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
												}
											}
											else if(is_numeric($opr[0]))
											{
												$ex_price = $value_price + $opr[0];
												$ex_price = number_format((float)$ex_price, 2, '.', '');
												if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
												{
													$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
												}
												else
												{
													$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
												}
											}
											else
											{
												if(strpos($opr[1], '%'))
												{
													$per = explode('%',$opr[1]);
													if(is_numeric($per[0]))
													{
														$per_price = ($value_price * $per[0]) / 100;
														$per_price = number_format((float)$per_price, 21, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
												}
												elseif (strpos($opr[0], '%')) 
												{
													$per = explode('%',$opr[0]);
													if(is_numeric($per[0]))
													{
														$per_price = ($value_price * $per[0]) / 100;
														$per_price = number_format((float)$per_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
												}
											}
										}
										elseif (strpos($v, '-') !== FALSE) 
										{
											$opr = explode('-', $v);
											if(is_numeric($opr[1]))
											{
												$ex_price = $value_price - $opr[1];
											}
											elseif (is_numeric($opr[0])) 
											{
												$ex_price = $value_price - $opr[0];
											}
											else
											{
												if(strpos($opr[1],'%') !== FALSE)
												{
													$per = explode('%',$opr[1]);
													if(is_numeric($per[0]))
													{
														$per_price = ($value_price * $per[0]) / 100;
														$per_price = number_format((float)$per_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
												}
												elseif (strpos($opr[0],'%') !== FALSE) 
												{
													$per = explode('%',$opr[0]);
													if(is_numeric($per[0]))
													{
														$per_price = ($value_price * $per[0]) / 100;
														$per_price = number_format((float)$per_price, 2, '.', '');
														if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
														{
															$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
														}
														else
														{
															$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
														}
													}
												}
											}
										}
										elseif (strpos($v, '%') !== FALSE) 
										{
											$opr = explode('%', $v);
											if(is_numeric($opr[1]))
											{
												$per_price = ($value_price * $opr[1]) / 100;
												$per_price = number_format((float)$per_price, 2, '.', '');
												if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
												{
													$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
												}
												else
												{
													$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
												}
											}
											elseif (is_numeric($opr[0])) 
											{
												$per_price 	= ($value_price * $opr[0]) / 100;
												$per_price = number_format((float)$per_price, 2, '.', '');
												if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
												{
													$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
												}
												else
												{
													$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
												}
											}
										}
										else
										{
											$ex_price = $value_price + $v;
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
									}
								}
							}
							$rates_mxl 	.=	'</RateAmounts>	';
						}
						/* else
						{
							$update_values = 'no';
						} */
						if($room_value->update_availability==1)
						{
							$update_values = 'yes';
							
							if ($org_price == 1) 
							{
								$rates_mxl 	.='<Availability Master="Closed"/>';
							}
							else if ($org_price == "remove")
							{
								$rates_mxl 	.='<Availability Master="Open"/>';
							}
							
							if($availability!='')
							{
								$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$availability.'"/></BookingLimit>';
							}
							
							if($minimum_stay!='')
							{
								$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$minimum_stay.'</MinLoSThrough></BookingRules>';
							}
						}
						/* else
						{
							$update_values = 'no';
						} */
						$rates_mxl	.=	'</HotelData>';
						$xml_update	.=	$rates_mxl;
					}
				}
			}
			if(@$update_values=='yes')
			{
				$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
											<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
											TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
											<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
											<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
											'.$xml_update.'
											</HotelUpdateRequest>
											</HotelUpdateRQ>
											';
			
				
				$C_URL = get_data(C_URL,array('channel_id'=>17))->row();
				if($C_URL)
				{
					if($C_URL->mode=='0')
					{
						$url = explode(',',$C_URL->test_url);
						
					}
					else if($C_URL->mode=='1')
					{
						$url = explode(',',$C_URL->live_url);
					}
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url[0]);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
					$ss = curl_getinfo($ch);
					$response = curl_exec($ch);
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml = simplexml_load_string($xml);
					$json = json_encode($xml);
					$responseArray = json_decode($json,true);
					$Errors = @$responseArray['Errors']['Error'];
					if($Errors!='')
					{
						$this->inventory_model->store_error(current_user_type(),hotel_id(),$con_ch,$Errors,'Inline Edit At Main Calendar',date('m/d/Y h:i:s a', time()));
						$this->session->set_flashdata('bulk_error',$Errors);
					}
					/* else
					{
						return true;
					} */
				}
				/* else
				{
					return false;
				} */
			}
			/* else
			{
				return false;
			} */
		}
	}
	
	function inline_edit_channel_calendar($table,$room_id,$update_date,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$con_ch = 17;
		
		if($table=='room_update')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='reservation_table')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='room_rate_types_base')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
						
			$update_Details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='room_rate_types_additional')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}

		$separate_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
		
		if($count!=0)
		{
			if($table=='room_update')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='reservation_table')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='room_rate_types_base')
			{
				 $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='room_rate_types_additional')
			{
				 $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($room_mapping)
			{	
				$i=1;
				$xml_update	='';
				foreach($room_mapping as $room_value)
				{
					$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
					
					$bnow_map 	= get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
					
					$rates_xml 	= array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
		
					$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
					
					$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';

					$bnow_sun = 'true';
					$bnow_mon = 'true';
					$bnow_tue = 'true';
					$bnow_wed = 'true';
					$bnow_thur = 'true';
					$bnow_fri = 'true';
					$bnow_sat = 'true';
					
					$rates_mxl 	.= 	'<ApplicationControl Start="'.$separate_date.'" End="'.$separate_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
					
					if($update=='price' && $room_value->update_rate=='1')
					{
						$update_values = 'yes';
						
						$rates_mxl 	.=	'<RateAmounts Currency="'.$currency.'">';
						
						if($mapping_values)
						{
							$label = explode(',', $mapping_values['label']);
							$value = explode(',', $mapping_values['value']);

							if($bnow_map['explevel']=='OBP')
							{
								$set_arr=array_combine($label,$value);
							}
							else if($bnow_map['explevel']=='BRP')
							{
								$set_arr=array_combine($label,$value);
								$set_arr = array_slice($set_arr, -2, 2, true);
								if($value_price!='')
								{
									$value_price = number_format((float)$value_price, 2, '.', '');
									$rates_mxl .= '<Base OccupancyCode="SR" Amount="'.$value_price.'"></Base>';
								}
							}
							foreach($set_arr as $k=>$v)
							{
								if($v !='' && $value_price!='')
								{
									if(strpos($v, '+') !== FALSE)
									{
										$opr = explode('+', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = $value_price + $opr[1];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else if(is_numeric($opr[0]))
										{
											$ex_price = $value_price + $opr[0];
											$ex_price = number_format((float)$ex_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
											}
										}
										else
										{
											if(strpos($opr[1], '%'))
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0], '%')) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '-') !== FALSE) 
									{
										$opr = explode('-', $v);
										if(is_numeric($opr[1]))
										{
											$ex_price = $value_price - $opr[1];
										}
										elseif (is_numeric($opr[0])) 
										{
											$ex_price = $value_price - $opr[0];
										}
										else
										{
											if(strpos($opr[1],'%') !== FALSE)
											{
												$per = explode('%',$opr[1]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
											elseif (strpos($opr[0],'%') !== FALSE) 
											{
												$per = explode('%',$opr[0]);
												if(is_numeric($per[0]))
												{
													$per_price = ($value_price * $per[0]) / 100;
													$per_price = number_format((float)$per_price, 2, '.', '');
													if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
													{
														$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
													}
													else
													{
														$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
													}
												}
											}
										}
									}
									elseif (strpos($v, '%') !== FALSE) 
									{
										$opr = explode('%', $v);
										if(is_numeric($opr[1]))
										{
											$per_price = ($value_price * $opr[1]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
										elseif (is_numeric($opr[0])) 
										{
											$per_price 	= ($value_price * $opr[0]) / 100;
											$per_price = number_format((float)$per_price, 2, '.', '');
											if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
											{
												$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Additional>';
											}
											else
											{
												$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$per_price.'"></Base>';
											}
										}
									}
									else
									{
										$ex_price = $value_price + $v;
										$ex_price = number_format((float)$ex_price, 2, '.', '');
										if($k=='ExtraChildRate' || $k=='ExtraAdultRate')
										{
											
											$rates_mxl .= '<Additional OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Additional>';
										}
										else
										{
											$rates_mxl .= '<Base OccupancyCode="'.$rates_xml[$k].'" Amount="'.$ex_price.'"></Base>';
										}
									}
								}
							}
						}
						$rates_mxl 	.=	'</RateAmounts>	';
					}
					if($room_value->update_availability=='1')
					{
						$update_values = 'yes';
						
						if ($update=='stop_sell' && $org_price == 1) 
						{
							$rates_mxl 	.='<Availability Master="Closed"/>';
						}
						else if ($update=='stop_sell' && $org_price == "remove")
						{
							$rates_mxl 	.='<Availability Master="Open"/>';
						}
						
						if($update=='availability' && $value_price!='')
						{
							$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$value_price.'"/></BookingLimit>';
						}
						
						if($update=='minimum_stay' && $value_price!='')
						{
							$rates_mxl	.=	'<BookingRules><MinLoSThrough>'.$value_price.'</MinLoSThrough></BookingRules>';
						}
					}
					/* else
					{
						$update_values = 'no';
					} */
					$rates_mxl	.=	'</HotelData>';
					$xml_update	.=	$rates_mxl;
				}
				if(@$update_values=='yes')
				{
					$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
												<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
												TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
												<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
												<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
												'.$xml_update.'
												</HotelUpdateRequest>
												</HotelUpdateRQ>
												';
					//echo $xml_post_string; die;
					$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
					if($C_URL)
					{
						if($C_URL->mode=='0')
						{
							$url = explode(',',$C_URL->test_url);
							
						}
						else if($C_URL->mode=='1')
						{
							$url = explode(',',$C_URL->live_url);
						}
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_URL, $url[0]);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 500);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
						$ss = curl_getinfo($ch);
						$response = curl_exec($ch);
						$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
						$xml = simplexml_load_string($xml);
						$json = json_encode($xml);
						$responseArray = json_decode($json,true);
						$Errors = @$responseArray['Errors']['Error'];
						if($Errors!='')
						{
							$this->inventory_model->store_error(current_user_type(),hotel_id(),$con_ch,$Errors,'Inline Edit At Main Calendar',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error',$Errors);
						}
						/* else
						{
							return true;
						} */
					}
					/* else
					{
						return false;
					} */
				}
				/* else
				{
					return false;
				} */
			}
		}
		/* else
		{
			return false;
		} */
	}
	
	function reservation_update_no($table,$room_id,$update_date,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		$con_ch = 17;
		
		if($table=='room_update')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='reservation_table')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='room_rate_types_base')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
						
			$update_Details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}
		else if($table=='room_rate_types_additional')
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
			
			$update_Details = get_data($table,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date),''.$column.',separate_date')->row();
			
			$value_price = $update_Details->$column;
		}

		$separate_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
		
		if($count!=0)
		{
			if($table=='room_update')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='reservation_table')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='room_rate_types_base')
			{
				 $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='room_rate_types_additional')
			{
				 $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($room_mapping)
			{	
				$i=1;
				$xml_update	='';
				foreach($room_mapping as $room_value)
				{
					$mp_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
					
					$bnow_map 	= get_data(MAP,array('mapping_id'=>$room_value->mapping_id),'explevel')->row_array();
		
					$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
					
					$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';

					$bnow_sun = 'true';
					$bnow_mon = 'true';
					$bnow_tue = 'true';
					$bnow_wed = 'true';
					$bnow_thur = 'true';
					$bnow_fri = 'true';
					$bnow_sat = 'true';
					
					$rates_mxl 	.= 	'<ApplicationControl Start="'.$separate_date.'" End="'.$separate_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
					
					if($room_value->update_availability=='1')
					{
						$update_values = 'yes';
						
						if ($value_price == 1) 
						{
							$rates_mxl 	.='<Availability Master="Closed"/>';
						}
						else if ($value_price == 0)
						{
							$rates_mxl 	.='<Availability Master="Open"/>';
						}
					}
					/* else
					{
						$update_values = 'no';
					} */
					$rates_mxl	.=	'</HotelData>';
					$xml_update	.=	$rates_mxl;
				}
				if(@$update_values=='yes')
				{
					$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
												<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
												TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
												<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
												<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
												'.$xml_update.'
												</HotelUpdateRequest>
												</HotelUpdateRQ>
												';
					//echo $xml_post_string;
					$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
					if($C_URL)
					{
						if($C_URL->mode=='0')
						{
							$url = explode(',',$C_URL->test_url);
							
						}
						else if($C_URL->mode=='1')
						{
							$url = explode(',',$C_URL->live_url);
						}
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_URL, $url[0]);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 500);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
						$ss = curl_getinfo($ch);
						$response = curl_exec($ch);
						$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
						$xml = simplexml_load_string($xml);
						$json = json_encode($xml);
						$responseArray = json_decode($json,true);
						$Errors = @$responseArray['Errors']['Error'];
						if($Errors!='')
						{
							$this->inventory_model->store_error(current_user_type(),hotel_id(),$con_ch,$Errors,'Inline Edit At Main Calendar',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error',$Errors);
						}
						/* else
						{
							return true;
						} */
					}
					/* else
					{
						return false;
					} */
				}
				/* else
				{
					return false;
				} */
			}
		}
		/* else
		{
			return false;
		} */
	}
	
	function getReservationLists($source)
	{
		$this->db->select('import_reserv_id,ResID_Value,ResStatus,PersonName,RoomTypeCode,RatePlanCode,channel_id,Start,End,LastModifyDateTime,CurrencyCode,AmountAfterTax,current_date_time');
		$this->db->order_by('import_reserv_id','desc'); 
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$data = $this->db->get(BNOW_RESER)->result();
		if($data)
		{
			$bnow = array();
			foreach($data as $val)
			{
				if($val->ResStatus == "Commit")
				{
					$status = "Confirmed";
				}
				else if($val->ResStatus == "Modify")
				{
					$status = "Modify";
				}
				else if($val->ResStatus == "Cancel")
				{
					$status = "Canceled";
				}
				
				$room_id = @get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$val->RoomTypeCode,'RatePlanCode'=>$val->RatePlanCode,'user_id' => current_user_type(),'hotel_id'=>hotel_id()))->row()->
				import_mapping_id))->row()->property_id;
				
				$checkin=date('Y/m/d',strtotime($val->Start));
				$checkout=date('Y/m/d',strtotime($val->End));
				$nig =_datebetween($checkin,$checkout);
				if($source=="all")
				{
					$bnow[] = (object)array(
									'reservation_id' => $val->import_reserv_id,
									'reservation_code' => $val->ResID_Value,
									'status' => $status,
									'guest_name' => $val->PersonName,
									'room_id' => $room_id,
									'channel_id' => $val->channel_id,
									'start_date' => $val->Start,
									'end_date' => $val->End,
									'booking_date' =>$val->LastModifyDateTime,
									'currency_id' => $val->CurrencyCode,
									'price' => $val->AmountAfterTax,
									'num_nights' => $nig,
									'current_date_time' => $val->current_date_time,
								);
				}
				else if($source=="separate")
				{
					$bnow[] = (object)array(
									'import_reserv_id' => $val->import_reserv_id,
									'IDRSV' => $val->ResID_Value,
									'STATUS' => $status,
									'FIRSTNAME' => $val->PersonName,
									'ROOMCODE' => $room_id,
									'channel_id' => $val->channel_id,
									'CHECKIN' => $val->Start,
									'CHECKOUT' => $val->End,
									'RSVCREATE' =>$val->LastModifyDateTime,
									'CURRENCY' => $val->CurrencyCode,
									'REVENUE' => $val->AmountAfterTax,
									'num_nights' => $nig,
									'current_date_time' => $val->current_date_time,
								);
				}
			}
			return $bnow;
		}
		else
		{
			return $bnow=array();
		}
	}

	function getReservationDetails($source,$id)
	{
		$bnowmodel					=	get_data(BNOW_RESER,array('import_reserv_id '=>($id)))->row_array();
		
		$room_id = @get_data(MAP,array('channel_id'=>$bnowmodel['channel_id'],'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$bnowmodel['RoomTypeCode'],'RatePlanCode'=>$bnowmodel['RatePlanCode'],'user_id' =>current_user_type(),'hotel_id'=>hotel_id()))->row()->import_mapping_id))->row()->property_id;
		if($source=='list')
		{
			$data['curr_cha_id'] 		= 	secure('17');
		}
		$data['CC_NAME']			=	$bnowmodel['CardHolderName'];
		$data['CC_NUMBER']			=	safe_b64decode($bnowmodel['CardNumber']);
		$data['CC_DATE']			=	safe_b64decode($bnowmodel['ExpireDate']);
		$data['CC_YEAR']			=	safe_b64decode($bnowmodel['ExpireDate']);
		$data['CC_CVC']             =   safe_b64decode($bnowmodel['CardCode']);
		$data['CC_TYPE']            =   safe_b64decode($bnowmodel['CardType']);
		
		$data['RESER_NUMBER'] 		= 	$bnowmodel['ResID_Value'];
		$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($bnowmodel['LastModifyDateTime']));
		$data['RESER_ID'] 			= 	$bnowmodel['import_reserv_id'];
		
		$data['curr_cha_currency'] 	= 	$bnowmodel['CurrencyCode'];
		$data['guest_name']			= 	$bnowmodel['PersonName'];
		$data['start_date'] 		= 	date('Y/m/d',strtotime($bnowmodel['Start']));
		$data['end_date']			=	date('Y/m/d',strtotime($bnowmodel['End']));
		$data['reservation_code']	= 	$bnowmodel['ResID_Value'];
		$data['ROOMCODE']			=	$room_id;
		if($bnowmodel['ResStatus']=='Commit')
		{
			$data['status'] = 'New booking';
		}
		else if($bnowmodel['ResStatus']=='Modify')
		{
			$data['status'] = 'Modification';
		}
		else if($bnowmodel['ResStatus']=='Cancel')
		{
			$data['status'] = 'Cancellation';
		}
		$data['start_date']			=	$bnowmodel['Start'];
		$data['end_date']			=	$bnowmodel['End'];
		
		$data['CHECKIN']			=	date('Y/m/d',strtotime($bnowmodel['Start']));
		$data['CHECKOUT']			=	date('Y/m/d',strtotime($bnowmodel['End']));
		
		$data['nig'] 				=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);
		$Guest						=	explode('##',$bnowmodel['Guest']);
		if($Guest)
		{
			$i=1;
			foreach($Guest as $value)
			{
				$whatIWant = substr($value, strpos($value, "#") + 1); 
				if($i=='1')
				{
					$data['ADULTS']				= 	$whatIWant;
					$data['members_count']				= 	$whatIWant;
				}
				if($i=='2')
				{
					$data['CHILDREN']			= 	$whatIWant;
				}
				else
				{
					$data['CHILDREN']			= 	0;	
				}				
				$i++;
			}
		}
		$data['description']		= 	$bnowmodel['Text'];
		$data['policy_checin']		= 	'';//$bnowmodel['Start'];
		$data['policy_checout']		= 	'';//$bnowmodel['End'];
		$data['CRIB']				=	'';//$bnowmodel['CRIB'];
		$data['subtotal']			= 	$bnowmodel['AmountAfterTax'];
		$data['CURRENCY']			=	$bnowmodel['CurrencyCode'];

		$data['guest_name'] 		=	$bnowmodel['PersonName'];
		$data['email']				=	$bnowmodel['Email'];
		$data['street_name'] 		=	$bnowmodel['AddressLine'];
		$data['city_name'] 			=	$bnowmodel['CityName'];
		$data['country'] 			=	$bnowmodel['CountryName'];
		$data['phone'] 				=	$bnowmodel['PhoneNumber'];
		$data['commission']	  		= 	'';//$bnowmodel['COMMISSION'];
		$data['mealsinc']			= 	'';//$bnowmodel['MEALSINC'];
		$price 						= 	explode('###',$bnowmodel['Price']);
		if($price)
		{
			foreach($price as $price_val)
			{
				$date		=	explode('~',$price_val);
				$price_day	=	explode('/',$date[1]);	
				$data['perdayprice'][] = array($date[0] => $price_day[1]);
			}
		}
		if($data['CC_NUMBER']=='')
		{
			$data['payment_method'] = 'Cash';
		}
		else
		{
			$data['payment_method'] = 'Credit Card';
		}
		$room						=	get_data(IM_BNOW,array('InvTypeCode'=>$bnowmodel['RoomTypeCode'],'RatePlanCode'=>$bnowmodel['RatePlanCode']),'RoomTypeName,RateTypeName')->row_array();
		if($room)
		{
			$data['channel_room_name']	=	$room['RoomTypeName'].' '.$room['RateTypeName'];
		}
		
		if($source=='print')
		{
			$data['room_id']			=	$room_id;
			$data['num_nights']			=	'1';
			$data['price']				=	$bnowmodel['AmountAfterTax'];
			$data['booking_date']		=	$data['RESER_DATE'];
			$data['mobile']				=	$bnowmodel['PhoneNumber'];
			$data['curr_cha_id'] 		= 	'17';
			$data['currency'] 			= 	$bnowmodel['CurrencyCode'];
			$data['meal_name'] 			= 	'---';
			$data['cancel_description'] = 	'---';
		}
		return $data;
	}
	
	function invoiceCreate($reservation_id)
	{
		$reservation_details = get_data(BNOW_RESER,array('import_reserv_id'=>insep_decode($reservation_id)),'import_reserv_id,hotel_id,user_id,PersonName,Email,AddressLine,CityName,CountryName,Start,End,AmountAfterTax,CurrencyCode,RoomTypeCode,RatePlanCode')->row_array();
		$data['page_heading'] = 'Create Invoice';
		$data['curr_cha_id'] 		= 	secure(17);
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();	
		$data= array_merge($user_details,$data);
		$data['reservation_id'] 	=	$reservation_details['import_reserv_id'];
		$data['hotel_id'] 			=	$reservation_details['hotel_id'];
		$data['user_id']			=	$reservation_details['user_id'];
		$data['guest_name'] 		= 	$reservation_details['PersonName'];
		$data['email'] 				= 	$reservation_details['Email'];
		$data['street_name'] 		= 	$reservation_details['AddressLine'];
		$data['city_name'] 			= 	$reservation_details['CityName'];
		$data['country'] 			= 	$reservation_details['CountryName'];
		$data['start_date'] 		= 	$reservation_details['Start'];
		$data['end_date']			= 	$reservation_details['End'];
		$checkin					=	date('Y/m/d',strtotime($reservation_details['Start']));
		$checkout					=	date('Y/m/d',strtotime($reservation_details['End']));
		$nig =_datebetween($checkin,$checkout);
		$data['num_nights']	 		= 	$nig;
		$data['price'] 				= 	$reservation_details['AmountAfterTax'];
		$data['cha_currency'] 		= 	$reservation_details['CurrencyCode'];
		return $data;
	}
	
	function updateAvailability($owner_id,$hotel_id,$property_id,$sep_date)
	{
		$con_ch	=	17;
		$this->db->where('channel_id','17');
		$this->db->where('owner_id',$owner_id);
		$this->db->where('hotel_id',$hotel_id);
		$this->db->where('property_id',$property_id);
		$this->db->where('enabled','enabled');
		$this->db->where('update_availability','1');
		$mapdetails		=	$this->db->get(MAP);
		$room_mapping	=	$mapdetails->result();
		$update_values  = "No";
		if($room_mapping)
		{
			$i=1;
			$xml_update	='';
			foreach($room_mapping as $room_value)
			{
				if($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
				{
					$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date),'availability')->row_array();
				}
				elseif($room_value->property_id !='0' && $room_value->rate_id =='0' && $room_value->guest_count!='0' && $room_value->refun_type !='0')
				{
					$available_update_details = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type),'availability')->row_array(); 
				}
				elseif($room_value->property_id !='0' && $room_value->rate_id !='0' && $room_value->guest_count =='0' && $room_value->refun_type=='0')
				{
					$available_update_details = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date),'availability')->row_array();
				}
				elseif($room_value->property_id !='0' && $room_value->rate_id !='0' && $room_value->guest_count !='0' && $room_value->refun_type !='0')
				{
					$available_update_details = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type),'availability')->row_array();
				}

				if(count($available_update_details) != 0)
				{
					$availability = $available_update_details['availability'];

					$mp_details = get_data(IM_BNOW,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();
					
					$rates_xml 	= array('SingleAdult'=>'A1','TwoAdults'=>'A2','ThreeAdults'=>'A3','FourAdults'=>'A4','FiveAdults'=>'A5','SixAdults'=>'A6','ExtraChildRate'=>'AC','ExtraAdultRate'=>'AA',);
					
					$rates_mxl	=	'<HotelData ItemIdentifier="'.$i++.'">';
						
					$rates_mxl	.=	'<ProductReference InvTypeCode="'.$mp_details->InvTypeCode.'" RatePlanCode="'.$mp_details->RatePlanCode.'"></ProductReference>';

					$bnow_sun = 'true';
					$bnow_mon = 'true';
					$bnow_tue = 'true';
					$bnow_wed = 'true';
					$bnow_thur = 'true';
					$bnow_fri = 'true';
					$bnow_sat = 'true';
					
					$separate_date = date('Y-m-d',strtotime(str_replace('/','-',$sep_date)));
					
					$rates_mxl 	.= 	'<ApplicationControl Start="'.$separate_date.'" End="'.$separate_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
					
					$rates_mxl	.=	'<BookingLimit><TransientAllotment Allotment="'.$availability.'"/></BookingLimit>';
					
					$rates_mxl	.=	'</HotelData>';
					
					$xml_update	.=	$rates_mxl;
					
					$update_values = 'yes';
				}
			}
			if($update_values=='yes')
			{
				$ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'17'))->row();
		
				$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
											<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
											TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
											<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
											<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
											'.$xml_update.'
											</HotelUpdateRequest>
											</HotelUpdateRQ>
											';
			
				//echo $xml_post_string;// die;
				$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
				if($C_URL)
				{
					if($C_URL->mode=='0')
					{
						$url = explode(',',$C_URL->test_url);
						
					}
					else if($C_URL->mode=='1')
					{
						$url = explode(',',$C_URL->live_url);
					}
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url[0]);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
					$ss = curl_getinfo($ch);
					$response = curl_exec($ch);
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml = simplexml_load_string($xml);
					$json = json_encode($xml);
					$responseArray = json_decode($json,true);
					$Errors = @$responseArray['Errors']['Error'];
					if($Errors!='')
					{
						$this->inventory_model->store_error(current_user_type(),hotel_id(),$con_ch,$Errors,'Inline Edit At Main Calendar',date('m/d/Y h:i:s a', time()));
						$this->session->set_flashdata('bulk_error',$Errors);
					}
					else
					{
						return true;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
	}
	
	function channel_reservation_count($property_id,$start_date,$end_date)
	{
		$roomMappingCheck = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($property_id),'channel_id'=>'17'),'mapping_id,channel_id,import_mapping_id')->result_array();
		//echo $this->db->last_query();
		if(count($roomMappingCheck)!=0)
		{   
			$total_reser_count = 0;
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);
				$chaMapCheck = get_data(IM_BNOW,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'import_mapping_id'=>$import_mapping_id),'InvTypeCode,RatePlanCode')->row_array();
				if(count($chaMapCheck)!=0)
				{
					$chaReserCheckCount = $this->db->query("SELECT COUNT(*) AS `numrows` FROM `".BNOW_RESER."` WHERE ( (DATE_FORMAT(Start,'%Y-%m-%d') >= '".$start_date."' AND DATE_FORMAT(Start,'%Y-%m-%d') <= '".$end_date."') OR ( (DATE_FORMAT(End,'%Y-%m-%d') > '".$start_date."' AND DATE_FORMAT(End,'%Y-%m-%d') <= '".$end_date."') ) ) AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND RoomTypeCode='".$chaMapCheck['InvTypeCode']."' AND RatePlanCode='".$chaMapCheck['RatePlanCode']."' AND ResStatus NOT IN('Cancel')");
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
		$roomMappingCheck = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($property_id),'channel_id'=>'17'),'mapping_id,channel_id,import_mapping_id')->result_array();
		if(count($roomMappingCheck)!=0)
		{
			$i=0;
			$chaReserCheckCount = array();
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);
				if($channel_id==17)
				{
					$chaMapCheck = get_data(IM_BNOW,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'import_mapping_id'=>$import_mapping_id),'InvTypeCode,RatePlanCode')->row_array();
					
					if(count($chaMapCheck)!=0)
					{
						$cahquery = $this->db->query("SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(Start,'%d/%m/%Y') as start_date, DATE_FORMAT(End,'%d/%m/%Y') as end_date ,channel_id,ResID_Value as booking_number FROM `".BNOW_RESER."` WHERE ( (DATE_FORMAT(Start,'%Y-%m-%d') >= '".$start_date."' AND DATE_FORMAT(Start,'%Y-%m-%d') <= '".$end_date."') OR ( (DATE_FORMAT(End,'%Y-%m-%d') > '".$start_date."' AND DATE_FORMAT(End,'%Y-%m-%d') <= '".$end_date."') ) ) AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND RoomTypeCode='".$chaMapCheck['InvTypeCode']."' AND RatePlanCode='".$chaMapCheck['RatePlanCode']."' AND ResStatus NOT IN('Cancel') ORDER BY import_reserv_id DESC");
						if($cahquery)
						{
							$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());
						}
					}
				}
			}
			//echo '<pre>'; print_r($chaReserCheckCount);
			return	$chaReserCheckCount;
		}
	}
	
	function getRoomRelation($property_id,$all_channel_id)
	{
		$roomMappingCheck = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($property_id),'channel_id'=>$all_channel_id),'mapping_id,channel_id,import_mapping_id')->result_array();
       /* echo $this->db->last_query();
         echo '<pre>';
        print_r($roomMappingCheck); */
            if(count($roomMappingCheck)!=0)
            {   
                $RoomRelation = array();
                foreach($roomMappingCheck as $roomMap)
                {
                    extract($roomMap);
					if($channel_id==17)
                    {
						$chaMapCheck = get_data(IM_BNOW,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'import_mapping_id'=>$import_mapping_id),'InvTypeCode,RatePlanCode')->row_array();
                        
                        if(count($chaMapCheck)!=0)
                        {
                            $RoomRelation = array_merge($RoomRelation,$chaMapCheck);
                        }
                    }
				}
				return $RoomRelation;
			}
	}
	
	function getSingleReservationBnow($reservation_id)
   	{
		$cahquery = $this->db->query('SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(Start,"%d/%m/%Y") as start_date, DATE_FORMAT(End,"%d/%m/%Y") as end_date , channel_id , PersonName as guest_name , ResID_Value as reservation_code, DATEDIFF(End,Start) AS num_nights , Adult as members_count , AmountAfterTax as price , RoomTypeCode , RatePlanCode FROM (`'.BNOW_RESER.'`) WHERE (`Start` >= CURDATE() OR `End` >= CURDATE()) AND `user_id` = "'.current_user_type().'" AND `hotel_id` = "'.hotel_id().'" AND `import_reserv_id` = "'.$reservation_id.'"');

		//echo $this->db->last_query().'<br>';
        if($cahquery)
        {
            $chaReserResult = $cahquery->row_array();
        }
        return $chaReserResult;
    }
}