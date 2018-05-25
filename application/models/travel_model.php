<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Travel_model extends CI_Model
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
		if($channel_id=='15')
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
			$this->db->select('B.map_id, B.Description');
			if($clean!='')
			{
				$this->db->where_not_in('B.map_id',$import);
			}
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get(IM_TRAVELREPUBLIC.' as B');
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
		if($channel_id=='15')
		{
			$count = $this->db->select('map_id')->from(IM_TRAVELREPUBLIC)->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'Id'=>$ch_details->hotel_channel_id))->count_all_results();
			return $count;
		}
	}
	
	function get_all_mapped_rooms($channel_id)
	{
		$this->db->select('R.mapping_id,R.owner_id,R.hotel_id,R.property_id,R.rate_id,R.channel_id,R.import_mapping_id,R.guest_count,R.refun_type,R.enabled,R.included_occupancy,R.extra_adult,R.extra_child,R.single_quest,R.update_rate,R.update_availability,R.rate_conversion,R.explevel');
		if($channel_id==11)
		{
			$this->db->join(IM_TRAVELREPUBLIC.' as BN','R.import_mapping_id=BN.map_id');
			$this->db->join(CONNECT.' as C','BN.Id=C.hotel_channel_id');
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
			$duration = 30;
        }
		else if($end_date != "")
        {
			$checkin=date('Y/m/d',strtotime($start_date));
			$checkout=date('Y/m/d',strtotime($end_date));
			$duration =_datebetween($checkin,$checkout);
        }
		       
        $ch_details = get_data(CONNECT,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>"15",'status'=>'enabled'))->row();
		
		if($ch_details)
		{
			$channel_id = 15;                   
			
			$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'map_id'=>$import_mapping_id,'Id'=>$ch_details->hotel_channel_id))->row();
			$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
			//print_r($C_URL);
			if($C_URL)
			{
				if($C_URL->mode=='0')
				{
					$url = $C_URL->test_url;
					
				}
				else if($C_URL->mode=='1')
				{
					$url = $C_URL->live_url;
				}

				$wcfClient = new SoapClient($url);

				$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="2"/>
				<Establishment Id="'.$ch_details->hotel_channel_id.'" StartDate="'.$start_date.'" Duration="'.$duration.'">
				<RoomTypes>
				<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'"/>
				</RoomTypes>
				</Establishment>
				</Request>');

				$response = $wcfClient->RequestData($args);

				$data = simplexml_load_string($response->RequestDataResult); 
				//print_r($data);
				foreach($data->Establishment as $esta)
				{
					//print_r($esta);
					if($esta->attributes()->Error)
					{
						$this->inventory_model->store_error($user_id,$hotel_id,insep_decode($channel_id),(string)$error->attributes()->Error,'Import Rate And Availabilities',date('m/d/Y h:i:s a', time()));
					}else{

						foreach($esta->RoomTypes as $RoomType)
						{
							//print_r($RoomType);
							foreach ($RoomType->RoomType->Date as $rates) {

								$date = (string)$rates->attributes()->Date;

								$sep_date = date('d/m/Y',strtotime(str_replace('-','/',$date)));

								if($importRates == "")
								{
									$allotment = (string)$rates->attributes()->AvailableRoomCount;
								}else{	

									$allotment = (string)$rates->attributes()->RoomRate;
								}
								$this->update_channel_calendar($user_id,$hotel_id,$channel,$allotment,$sep_date,$mapping,$importRates);

							}
						}
					}
				}

				if($mapping != "")
		        {
		        	//$this->load->model('availability_model');
		        	$this->availability_model->updateAvailabilityBNOW($start_date,$end_date,$user_id,$hotel_id,$property_id,'15');
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
                            
                            //$this->update_channel($owner_id,$hotel_id,$ch_update_data,$channel,$date,$mapping_id);
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
                            //$this->update_channel($owner_id,$hotel_id,$ch_update_data_RESERV,$channel,$date,$mapping_id);
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
                            //$this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_BASE,$channel,$date,$mapping_id);
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
                            //$this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_ADD,$channel,$date,$mapping_id);                           
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
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 15;
		
		$up_days 	= explode(',',$product['days']);
		
		
		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$product['start_date'])));
		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$product['end_date'])));
		
		if(@$product['days'] != "")
        {
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,@$product['days']);
        }else{
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,array('1,2,3,4,5,6,7'));
        }
        
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
			
					$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'map_id'=>$room_value->import_mapping_id))->row(); 
		
					$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();

					$currency = $mp_details->Currency;

					if($mapping_values){

                        if($mapping_values['label']== "BookingCutOff")
                        {
                            $bookingcutoff = $mapping_values['value'];                                        
                        }
                    }
					
					if($room_value->update_rate=='1' || $room_value->update_availability=='1')
					{
						$update_values = 'yes';

						$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
						foreach($periods as $period)
						{
							$roomupdate .= '<Date Date="'.$period.'"';
							if($room_value->update_rate == 1 && @$product['price'] != "")
							{
								$roomupdate .= ' RoomRate = "'.@$product['price'].'"';
							}
							if($room_value->update_availability == 1 && @$product['availability'] != "")
							{
								$roomupdate .= ' AvailableRoomCount="'.$product['availability'].'"';
							}
							if(@$product['minimum_stay'] != "")
							{
								$roomupdate .= ' MinNightsStay="'.$product['minimum_stay'].'"';
							}
							if($bookingcutoff != "")
							{
								$roomupdate .= ' BookingCutOff="'.$bookingcutoff.'"';
							}
							if($stop_sale == "1")
							{
								$roomupdate .= ' ClosedOut="true"';
							}else if($stop_sale == 'remove')
							{
								if(@$product['availability'] == "")
								{
									$sepdate = date("d/m/Y",strtotime($period));
									$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$sepdate))->row_array();

									if($available['availability'])
									{
										$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
									}
								}
								$roomupdate .= ' ClosedOut="false"';
							}

							$roomupdate .= ' />';
						}

						$roomupdate .= '</RoomType>';
					}
					
					else
					{
						$update_values = 'no';
					}

					$xml_update .= $roomupdate;
				}
				if($update_values=='yes')
				{
					if($xml_update != "")
					{
						$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
						if($C_URL)
						{
							if($C_URL->mode=='0')
							{
								$url = $C_URL->test_url;
								
							}
							else if($C_URL->mode=='1')
							{
								$url = $C_URL->live_url;
							}

							$wcfClient = new SoapClient($url);

							$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
								<Establishment Id="'.$ch_details->hotel_channel_id.'">
								<RoomTypes>'.$xml_update.'</RoomTypes>
								<Currency>'.$currency.'</Currency>
								</Establishment>
								</Request>');

							$response = $wcfClient->RequestData($args);
							

							$data = simplexml_load_string($response->RequestDataResult);


							$error = @$data->attributes()->Error;

							if($error != "")
							{
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
								$this->session->set_flashdata('bulk_error',(string)$error);
							}else{
								foreach($data->Establishment as $esta)
								{
									if($esta->attributes()->Error)
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Bulk Update',date('m/d/Y h:i:s a', time()));
										$this->session->set_flashdata('bulk_error',(string)$esta->attributes()->Error);
									}else{
										foreach($esta->RoomTypes as $roomtype)
										{
											foreach($roomtype->RoomType->Date as $dates)
											{
												if((string)$dates->attributes()->Updated == "No")
												{
													$error = (string)$dates->attributes()->Error;
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
													$this->session->set_flashdata('bulk_error',(string)$error);
												}
											}
										}
									}
								}
							}
						}
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
		else
		{
			return false;
		}
	}

	function sub_room_bulk_update($clean,$up_days)
    {
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 15;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));
		$days = implode(',', $up_days);
		
		if(@$days != "")
        {
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }else{
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }

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
								
								$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'map_id'=>$room_value->import_mapping_id))->row(); 
		
								$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
								$currency = $mp_details->Currency;

								if($mapping_values){

			                        if($mapping_values['label']== "BookingCutOff")
			                        {
			                            $bookingcutoff = $mapping_values['value'];                                      
			                        }
			                    }		

								if($room_value->update_rate=='1' || $room_value->update_availability=='1')
								{
									$update_values = 'yes';

									$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
									foreach($periods as $period)
									{
										$roomupdate .= '<Date Date="'.$period.'"';
										if($room_value->update_rate == 1 && @$value_price != "0")
										{
											$roomupdate .= ' RoomRate = "'.@$value_price.'"';
										}
										if($room_value->update_availability == 1 && @$value['availability'] != "")
										{
											$roomupdate .= ' AvailableRoomCount="'.$value['availability'].'"';
										}
										if(@$value['minimum_stay'] != "")
										{
											$roomupdate .= ' MinNightsStay="'.$value['minimum_stay'].'"';
										}
										if($bookingcutoff != "")
										{
											$roomupdate .= ' BookingCutOff="'.$bookingcutoff.'"';
										}
										if($stop_sale == "1")
										{
											$roomupdate .= ' ClosedOut="true"';
										}else if($stop_sale == 'remove')
										{
											if(@$value['availability'] == "")
											{
												$sepdate = date("d/m/Y",strtotime($period));
												$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$id,'separate_date'=>$sepdate))->row_array();

												if($available['availability'])
												{
													$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
												}
											}
											$roomupdate .= ' ClosedOut="false"';
										}

										$roomupdate .= ' />';
									}

									$roomupdate .= '</RoomType>';
								}
								else
								{
									$update_values = 'no';
								}
								$xml_update .= $roomupdate;
							}
							if($update_values=='yes')
							{
								$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
								if($C_URL)
								{
									if($C_URL->mode=='0')
									{
										$url = $C_URL->test_url;
										
									}
									else if($C_URL->mode=='1')
									{
										$url = $C_URL->live_url;
									}

									$wcfClient = new SoapClient($url);

									$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
										<Establishment Id="'.$ch_details->hotel_channel_id.'">
										<RoomTypes>'.$xml_update.'</RoomTypes>
										<Currency>'.$currency.'</Currency>
										</Establishment>
										</Request>');
									$response = $wcfClient->RequestData($args);
									

									$data = simplexml_load_string($response->RequestDataResult);


									$error = @$data->attributes()->Error;

									if($error != "")
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
										$this->session->set_flashdata('bulk_error',(string)$error);
									}else{
										foreach($data->Establishment as $esta)
										{
											if($esta->attributes()->Error)
											{
												$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Bulk Update',date('m/d/Y h:i:s a', time()));
												$this->session->set_flashdata('bulk_error',(string)$esta->attributes()->Error);
											}else{
												foreach($esta->RoomTypes as $roomtype)
												{
													foreach($roomtype->RoomType->Date as $dates)
													{
														if((string)$dates->attributes()->Updated == "No")
														{
															$error = (string)$dates->attributes()->Error;
															$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
															$this->session->set_flashdata('bulk_error',(string)$error);
														}
													}
												}
											}
										}
									}
								}else{
									return false;
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
					else
					{
						return false;
					}
				}
			}
		}
    }
	
	function main_rate_bulk_update($clean,$up_days)
    {
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 15;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));
		$days = implode(',', $up_days);
		
		if(@$days != "")
        {
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }else{
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }

		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
		
		foreach($clean as $id=>$room)
		{
			foreach($room as $rate_types_id=>$type)
			{
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
					
					if($room_mapping)
					{
						$i=1;
						$xml_update	='';
						foreach($room_mapping as $room_value)
						{
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
							
							$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'map_id'=>$room_value->import_mapping_id))->row(); 
		
							$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
							
							$currency = $mp_details->Currency;

							if($mapping_values){

		                        if($mapping_values['label']== "BookingCutOff")
		                        {
		                            $bookingcutoff = $mapping_values['value'];                                        
		                        }
		                    }
							
							if($room_value->update_rate=='1' || $room_value->update_availability=='1')
							{
								$update_values = 'yes';

								$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
								foreach($periods as $period)
								{
									$roomupdate .= '<Date Date="'.$period.'"';
									if($room_value->update_rate == 1 && @$type['price'] != "")
									{
										$roomupdate .= ' RoomRate = "'.@$type['price'].'"';
									}
									if($room_value->update_availability == 1 && @$type['availability'] != "")
									{
										$roomupdate .= ' AvailableRoomCount="'.$type['availability'].'"';
									}
									if(@$type['minimum_stay'] != "")
									{
										$roomupdate .= ' MinNightsStay="'.$type['minimum_stay'].'"';
									}
									if($bookingcutoff != "")
									{
										$roomupdate .= ' BookingCutOff="'.$bookingcutoff.'"';
									}
									if($stop_sale == "1")
									{
										$roomupdate .= ' ClosedOut="true"';
									}else if($stop_sale == 'remove')
									{
										if(@$type['availability'] == "")
										{
											$sepdate = date("d/m/Y",strtotime($period));
											$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$id,'separate_date'=>$sepdate))->row_array();

											if($available['availability'])
											{
												$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
											}
										}
										$roomupdate .= ' ClosedOut="false"';
									}

									$roomupdate .= ' />';
								}

								$roomupdate .= '</RoomType>';
							}
							else
							{
								$update_values = 'no';
							}
							$xml_update .= $roomupdate;
						}
						if($update_values=='yes')
						{
							$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
							if($C_URL)
							{
								if($C_URL->mode=='0')
								{
									$url = $C_URL->test_url;
									
								}
								else if($C_URL->mode=='1')
								{
									$url = $C_URL->live_url;
								}

								$wcfClient = new SoapClient($url);

								$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
									<Establishment Id="'.$ch_details->hotel_channel_id.'">
									<RoomTypes>'.$xml_update.'</RoomTypes>
									<Currency>'.$currency.'</Currency>
									</Establishment>
									</Request>');

								$response = $wcfClient->RequestData($args);

								$data = simplexml_load_string($response->RequestDataResult);


								$error = @$data->attributes()->Error;

								if($error != "")
								{
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
									$this->session->set_flashdata('bulk_error',(string)$error);
								}else{
									foreach($data->Establishment as $esta)
									{
										if($esta->attributes()->Error)
										{
											$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Bulk Update',date('m/d/Y h:i:s a', time()));
											$this->session->set_flashdata('bulk_error',(string)$esta->attributes()->Error);
										}else{
											foreach($esta->RoomTypes as $roomtype)
											{
												foreach($roomtype->RoomType->Date as $dates)
												{
													if((string)$dates->attributes()->Updated == "No")
													{
														$error = (string)$dates->attributes()->Error;
														$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
														$this->session->set_flashdata('bulk_error',(string)$error);
													}
												}
											}
										}
									}
								}
							}else{
								return false;
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
				else
				{
					return false;
				}
			}
		}
	}
	
	function sub_rate_bulk_update($clean,$up_days)
	{
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$channel_id = 15;

		$start_date = date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('start_date'))));

		$end_date	= date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('end_date'))));

		$days = implode(',', $up_days);
		
		if(@$days != "")
        {
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }else{
            $periods = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,$days);
        }
		
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
									
									$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id,'map_id'=>$room_value->import_mapping_id))->row(); 
		
									$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
									$currency = $mp_details->Currency;

									if($mapping_values){

				                        if($mapping_values['label']== "BookingCutOff")
				                        {
				                            $bookingcutoff = $mapping_values['value'];                                        
				                        }
				                    }
									
									if($room_value->update_rate=='1' || $room_value->update_availability=='1')
									{
										$update_values = 'yes';

										$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
										foreach($periods as $period)
										{
											$roomupdate .= '<Date Date="'.$period.'"';
											if($room_value->update_rate == 1 && @$value_price != "0")
											{
												$roomupdate .= ' RoomRate = "'.@$value_price.'"';
											}
											if($room_value->update_availability == 1 && @$value['availability'] != "")
											{
												$roomupdate .= ' AvailableRoomCount="'.$value['availability'].'"';
											}
											if(@$value['minimum_stay'] != "")
											{
												$roomupdate .= ' MinNightsStay="'.$value['minimum_stay'].'"';
											}
											if($bookingcutoff != "")
											{
												$roomupdate .= ' BookingCutOff="'.$bookingcutoff.'"';
											}
											if($stop_sale == "1")
											{
												$roomupdate .= ' ClosedOut="true"';

											}else if($stop_sale == 'remove')
											{
												if(@$value['availability'] == "")
												{
													$sepdate = date("d/m/Y",strtotime($period));
													$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$id,'separate_date'=>$sepdate))->row_array();

													if($available['availability'])
													{
														$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
													}
												}
												$roomupdate .= ' ClosedOut="false"';
											}

											$roomupdate .= ' />';
										}

										$roomupdate .= '</RoomType>';
									}
									else
									{
										$update_values='no';
									}
									$xml_update .= $roomupdate;
								}
								if($update_values=='yes')
								{
									$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
									if($C_URL)
									{
										if($C_URL->mode=='0')
										{
											$url = $C_URL->test_url;
											
										}
										else if($C_URL->mode=='1')
										{
											$url = $C_URL->live_url;
										}

										$wcfClient = new SoapClient($url);

										$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
											<Establishment Id="'.$ch_details->hotel_channel_id.'">
											<RoomTypes>'.$xml_update.'</RoomTypes>
											<Currency>'.$currency.'</Currency>
											</Establishment>
											</Request>');

										$response = $wcfClient->RequestData($args);

										$data = simplexml_load_string($response->RequestDataResult);


										$error = @$data->attributes()->Error;

										if($error != "")
										{
											$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
											$this->session->set_flashdata('bulk_error',(string)$error);
										}else{
											foreach($data->Establishment as $esta)
											{
												if($esta->attributes()->Error)
												{
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Bulk Update',date('m/d/Y h:i:s a', time()));
													$this->session->set_flashdata('bulk_error',(string)$esta->attributes()->Error);
												}else{
													foreach($esta->RoomTypes as $roomtype)
													{
														foreach($roomtype->RoomType->Date as $dates)
														{
															if((string)$dates->attributes()->Updated == "No")
															{
																$error = (string)$dates->attributes()->Error;
																$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
																$this->session->set_flashdata('bulk_error',(string)$error);
															}
														}
													}
												}
											}
										}
									}else{
										return false;
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
						else
						{
							return false;
						}
					}
				}
			}
		}
	} 

	function getDateForSpecificDayBetweenDates($start, $end, $weekday)
    {
        if($weekday != ""){
            $weekdays="Day,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
            $arr_weekdays=explode(",", $weekdays);
            $string = "";
            $arr_weekdays_day = explode(",", $weekday);
            //print_r($arr_weekdays_day);
            $i = 1;
            foreach($arr_weekdays_day as $weekdays)
            {
                $weekday = @$arr_weekdays[$weekdays];
                if(!$weekday)
                $this->inventory_model->store_error(current_user_type(),hotel_id(),"2",'Invalid WeekDay','Bulk Update',date('m/d/Y h:i:s a', time()));
                $starts= strtotime("+0 day", strtotime($start) );
                $ends= strtotime($end);
                //$dateArr = array();
                $friday = strtotime($weekday, $starts);
                
                while($friday <= $ends)
                {
                    $dateArr[] = date("Y-m-d", $friday);
                    $date = date("Y-m-d", $friday);
                    $string .= "value".$i."='".$date."' ";
                    $friday = strtotime("+1 weeks", $friday);
                    $i++;
                }
                //$dateArr[] = date("Y-m-d", $friday);
            }
            //print_r($dateArr);
            return $dateArr;
        }
    }

    function inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		$currency = get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency))->row()->currency_code;
		
		$con_ch = 15;
		
		if($table=='room_update')
		{
			$udata[$column] = $org_price;
			
			if($column != "availability"){
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

			if($column != "availability")
			{
				$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->count_all_results();
			}else{
				$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'enabled'=>'enabled','update_availability'=>1))->count_all_results();
			}
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
			if($table=='room_update')
			{
				if($column != "availability"){
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
				}else{
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'enabled'=>'enabled','update_availability'=>1))->result();
				}
			}
			if($table=='reservation_table')
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled','update_rate'=>1))->result();
			}
			if($table=='room_rate_types_base')
			{
				if($column != "availability"){
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_rate'=>1))->result();
				}else{
					$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'enabled'=>'enabled','update_rate'=>1))->result();
				}
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
					
					$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();

					$currency = $mp_details->Currency;

					if($room_value->update_rate == '1' || $room_value->update_availability == '1')
					{
						$update_values = 'yes';

						$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
					
						$roomupdate .= '<Date Date="'.$separate_date.'"';
						if($room_value->update_rate == 1 && $update == "price")
						{
							$roomupdate .= ' RoomRate = "'.$value_price.'"';
						}
						if($room_value->update_availability == 1 && $update == "availability" && $value_price != "")
						{
							$roomupdate .= ' AvailableRoomCount="'.$value_price.'"';
						}
						if($update == 'minimum_stay' && $value_price != "")
						{
							$roomupdate .= ' MinNightsStay="'.$value_price.'"';
						}
						if($update=='stop_sell' && $org_price == 1)
						{
							$roomupdate .= ' ClosedOut="true"';
						}else if($update=='stop_sell' && $org_price == "remove")
						{
							$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_Details->separate_date))->row_array();

							if($available['availability'])
							{
								$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
							}
							$roomupdate .= ' ClosedOut="false"';
						}

						$roomupdate .= ' />';
					

						$roomupdate .= '</RoomType>';
					}
					else
					{
						$update_values = 'no';
					}
					$xml_update .= $roomupdate;
				}
				$channel_id = '15';
				if($update_values=='yes')
				{
					if($xml_update != "")
					{
						$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
						if($C_URL)
						{
							if($C_URL->mode=='0')
							{
								$url = $C_URL->test_url;
								
							}
							else if($C_URL->mode=='1')
							{
								$url = $C_URL->live_url;
							}

							$wcfClient = new SoapClient($url);

							$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
								<Establishment Id="'.$ch_details->hotel_channel_id.'">
								<RoomTypes>'.$xml_update.'</RoomTypes>
								<Currency>'.$currency.'</Currency>
								</Establishment>
								</Request>');

							$response = $wcfClient->RequestData($args);
							

							$data = simplexml_load_string($response->RequestDataResult);


							$error = @$data->attributes()->Error;

							if($error != "")
							{
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Inline Edit Main Calendar Update',date('m/d/Y h:i:s a', time()));
								
							}else{
								foreach($data->Establishment as $esta)
								{
									if($esta->attributes()->Error)
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Inline Edit Main Calendar Update',date('m/d/Y h:i:s a', time()));

									}else{
										foreach($esta->RoomTypes as $roomtype)
										{
											foreach($roomtype->RoomType->Date as $dates)
											{
												if((string)$dates->attributes()->Updated == "No")
												{
													$error = (string)$dates->attributes()->Error;
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Inline Edit Main Calendar Update',date('m/d/Y h:i:s a', time()));
													
												}
											}
										}
									}
								}
							}
						}
					}
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}

	function fullUpdate($source,$period)
	{
		//$currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->currency_code;
		
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>15))->row();
		
		
		if($source != "Synchronize")
		{
			$individual_channel_id	=	'15';
		}
		else if($source == "Synchronize")
		{
			$individual_channel_id	=	'0';
		}
		$all_map_rooms= get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'enabled'=>'enabled','channel_id'=>15))->result();
		//print_r($all_map_rooms); die;
		if(count($all_map_rooms)!=0)
		{
			$i=1;
			$xml_update	='';
			foreach ($all_map_rooms as $room_value) 
            {   
				$mp_details 	=	get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();

				$mapping_values = 	get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();

				$currency = $mp_details->Currency;

				if($mapping_values){

                    if($mapping_values['label']== "BookingCutOff")
                    {
                        $bookingcutoff = $mapping_values['value'];                                        
                    }
                }

				$roomupdate = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
				
				foreach($period as $date)
                {
					$separate_date = $date;

					$sep_date = date('d/m/Y',strtotime($date));
					
					if($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
					{
						$update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$sep_date))->row();
						if(count($update_details)!=0)
						{
							$value_price	=	$update_details->price;
						}
						else
						{
							$value_price	=	'';
						}
						if($individual_channel_id==15)
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

						if($source == "Synchronize")
						{
							$channel_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>'15','separate_date'=>$sep_date))->row();

							$updatedetails['availability'] = $update_details->availability;
							$updatedetails['price'] = $value_price;
							$updatedetails['minimum_stay'] = $update_details->minimum_stay;
							$updatedetails['cta'] = $update_details->cta;
							$updatedetails['ctd'] = $update_details->ctd;
							$updatedetails['stop_sell'] = $update_details->stop_sell;
							$updatedetails['open_room'] = $update_details->open_room;
							$updatedetails['days'] = $update_details->days;
							if(count($channel_update_details) != 0)
							{
								update_data(TBL_UPDATE,$updatedetails,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>'15','separate_date'=>$sep_date));
							}else{
								$updatedetails['owner_id']= current_user_type();
                               	$updatedetails['hotel_id']= hotel_id();
                               	$updatedetails['room_id']= $room_value->property_id;
                               	$updatedetails['individual_channel_id']= '15';
                               	$updatedetails['start_date']= $this->input->post('datepicker_full_start');
                               	$updatedetails['end_date']= $this->input->post('datepicker_full_end');
                               	$updatedetails['separate_date']=$sep_date;
                               	insert_data(TBL_UPDATE,$updatedetails);
							}
						}

					}
					elseif($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count!='0' && $room_value->refun_type!='0')
					{
						$update_details = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();
						
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
						
						if($individual_channel_id==15)
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

						if($source == "Synchronize")
						{
							$channel_update_details = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>'15','separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();

							$updatedetails['availability'] = $update_details->availability;
							if($update_details->refun_type=='1')
							{
								$updatedetails['refund_amount'] = $value_price;
							}
							elseif($update_details->refun_type=='2')
							{
								$updatedetails['non_refund_amount'] = $value_price;
							}
							$updatedetails['minimum_stay'] = $update_details->minimum_stay;
							$updatedetails['cta'] = $update_details->cta;
							$updatedetails['ctd'] = $update_details->ctd;
							$updatedetails['stop_sell'] = $update_details->stop_sell;
							$updatedetails['open_room'] = $update_details->open_room;
							$updatedetails['days'] = $update_details->days;
							if(count($channel_update_details) != 0)
							{
								update_data(RESERV,$updatedetails,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'individual_channel_id'=>'15','separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type));
							}else{
								$updatedetails['owner_id']= current_user_type();
                               	$updatedetails['hotel_id']= hotel_id();
                               	$updatedetails['room_id']= $room_value->property_id;
                               	$updatedetails['individual_channel_id']= '15';
                               	$updatedetails['start_date']= $this->input->post('datepicker_full_start');
                               	$updatedetails['end_date']= $this->input->post('datepicker_full_end');
                               	$updatedetails['guest_count']=$update_details->guest_count;
                                $updatedetails['refun_type']=$update_details->refun_type;
                               	$updatedetails['separate_date']=$sep_date;
                               	insert_data(RESERV,$updatedetails);
							}
						}						
					}
					elseif($room_value->property_id!='0' && $room_value->rate_id!='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
					{
						$update_details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$sep_date))->row();
						
						if(count($update_details)!=0)
						{
							$value_price	=	$update_details->price;
						}
						else
						{
							$value_price	=	'';
						}
						if($individual_channel_id==15)
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

						if($source == "Synchronize")
						{
							$channel_update_details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>15,'separate_date'=>$sep_date))->row();

							$updatedetails['availability'] = $update_details->availability;
							$updatedetails['price'] = $value_price;
							$updatedetails['minimum_stay'] = $update_details->minimum_stay;
							$updatedetails['cta'] = $update_details->cta;
							$updatedetails['ctd'] = $update_details->ctd;
							$updatedetails['stop_sell'] = $update_details->stop_sell;
							$updatedetails['open_room'] = $update_details->open_room;
							$updatedetails['days'] = $update_details->days;
							if(count($channel_update_details) != 0)
							{
								update_data(RATE_BASE,$updatedetails,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>15,'separate_date'=>$sep_date));
							}else{
								$updatedetails['owner_id']= current_user_type();
                               	$updatedetails['hotel_id']= hotel_id();
                               	$updatedetails['room_id']= $room_value->property_id;
                               	$updatedetails['individual_channel_id']= '15';
                               	$updatedetails['start_date']= $this->input->post('datepicker_full_start');
                               	$updatedetails['end_date']= $this->input->post('datepicker_full_end');
                               	$updatedetails['separate_date']=$sep_date;
                               	insert_data(RATE_BASE,$updatedetails);
							}
						}
					}
					elseif($room_value->property_id!='0' && $room_value->rate_id!='0' && $room_value->guest_count!='0' && $room_value->refun_type!='0')
					{
						$update_details = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$individual_channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();
						
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
						
						if($individual_channel_id==15)
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

						if($source == "Synchronize")
						{
							$channel_update_details = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>15,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row();

							$updatedetails['availability'] = $update_details->availability;
							if($update_details->refun_type=='1')
							{
								$updatedetails['refund_amount'] = $value_price;
							}
							elseif($update_details->refun_type=='2')
							{
								$updatedetails['non_refund_amount'] = $value_price;
							}
							$updatedetails['minimum_stay'] = $update_details->minimum_stay;
							$updatedetails['cta'] = $update_details->cta;
							$updatedetails['ctd'] = $update_details->ctd;
							$updatedetails['stop_sell'] = $update_details->stop_sell;
							$updatedetails['open_room'] = $update_details->open_room;
							$updatedetails['days'] = $update_details->days;
							if(count($channel_update_details) != 0)
							{
								update_data(RATE_ADD,$updatedetails,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>15,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type));
							}else{
								$updatedetails['owner_id']= current_user_type();
                               	$updatedetails['hotel_id']= hotel_id();
                               	$updatedetails['room_id']= $room_value->property_id;
                               	$updatedetails['individual_channel_id']= '15';
                               	$updatedetails['start_date']= $this->input->post('datepicker_full_start');
                               	$updatedetails['end_date']= $this->input->post('datepicker_full_end');
                               	$updatedetails['guest_count']=$update_details->guest_count;
                                $updatedetails['refun_type']=$update_details->refun_type;
                               	$updatedetails['separate_date']=$sep_date;
                               	insert_data(RATE_ADD,$updatedetails);
							}
						}
					}
					if(count($update_details)!=0)
					{

						$update_values = 'yes';
						$roomupdate .= '<Date Date="'.$separate_date.'"';
						
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
						
						if($room_value->update_rate == 1 || $room_value->update_rate ==1)
						{
							if($value_price != 0)
							{
								$roomupdate .= ' RoomRate="'.$value_price.'"';
							}
							if($availability != "")
							{
								$roomupdate .= ' AvailableRoomCount="'.$availability.'"';
							}
							if($minimum_stay != "")
							{
								$roomupdate .= ' MinNightsStay="'.$minimum_stay.'"';
							}
							if($bookingcutoff != "")
							{
								$roomupdate .= ' BookingCutOff="'.$bookingcutoff.'"';
							}
							if($org_price != "")
							{
								if($org_price == "1")
								{
									$roomupdate .= ' ClosedOut="true"';
								}else{
									$roomupdate .= ' ClosedOut="false"';
								}
								
							}
							$roomupdate .= ' />';
						}
						else
						{
							$update_values = 'no';
						}
					}
				}
				$roomupdate .= '</RoomType>';
				$xml_update .= $roomupdate;
			}
			if($update_values=='yes')
			{
				$channel_id = '15';
				if($xml_update != "")
				{
					$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
					if($C_URL)
					{
						if($C_URL->mode=='0')
						{
							$url = $C_URL->test_url;
							
						}
						else if($C_URL->mode=='1')
						{
							$url = $C_URL->live_url;
						}

						$wcfClient = new SoapClient($url);

						$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
							<Establishment Id="'.$ch_details->hotel_channel_id.'">
							<RoomTypes>'.$xml_update.'</RoomTypes>
							<Currency>'.$currency.'</Currency>
							</Establishment>
							</Request>');

						$response = $wcfClient->RequestData($args);
						

						$data = simplexml_load_string($response->RequestDataResult);

						$error = @$data->attributes()->Error;

						if($error != "")
						{
							$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error',(string)$error);
						}else{
							foreach($data->Establishment as $esta)
							{
								if($esta->attributes()->Error)
								{
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Full Update',date('m/d/Y h:i:s a', time()));
									
								}else{
									foreach($esta->RoomTypes as $roomtype)
									{
										foreach($roomtype->RoomType->Date as $dates)
										{
											if((string)$dates->attributes()->Updated == "No")
											{
												$error = (string)$dates->attributes()->Error;
												$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Full Update',date('m/d/Y h:i:s a', time()));
												
											}
										}
									}
								}
							}
						}
					}
				}
			}
			else
			{
				return false;
			}
		}
	}

	function inline_edit_channel_calendar($table,$room_id,$update_date,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		/*$currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->currency_code;*/
		
		$con_ch = 15;
		
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
					$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
					
					
					$currency = $mp_details->Currency;

					if($room_value->update_rate == '1' || $room_value->update_availability == '1')
					{
						$update_values = 'yes';

						$roomupdate  = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
					
						$roomupdate .= '<Date Date="'.$separate_date.'"';
						if($room_value->update_rate == 1 && $update == "price")
						{
							$roomupdate .= ' RoomRate = "'.$value_price.'"';
						}
						if($room_value->update_availability == 1 && $update == "availability" && $value_price != "")
						{
							$roomupdate .= ' AvailableRoomCount="'.$value_price.'"';
						}
						if($update == 'minimum_stay' && $value_price != "")
						{
							$roomupdate .= ' MinNightsStay="'.$value_price.'"';
						}
						if($update=='stop_sell' && $org_price == 1)
						{
							$roomupdate .= ' ClosedOut="true"';
						}else if($update=='stop_sell' && $org_price == "remove")
						{
							$available= get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_Details->separate_date))->row_array();

							if($available['availability'])
							{
								$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
							}
							$roomupdate .= ' ClosedOut="false"';
						}

						$roomupdate .= ' />';
					

						$roomupdate .= '</RoomType>';
					}
					else
					{
						$update_values = 'no';
					}
					$xml_update	.=	$roomupdate;
				}
				if($update_values=='yes')
				{
					if($xml_update != "")
					{
						$C_URL = get_data(C_URL,array('channel_id'=>$con_ch))->row();
						if($C_URL)
						{
							if($C_URL->mode=='0')
							{
								$url = $C_URL->test_url;
								
							}
							else if($C_URL->mode=='1')
							{
								$url = $C_URL->live_url;
							}

							$wcfClient = new SoapClient($url);

							$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
								<Establishment Id="'.$ch_details->hotel_channel_id.'">
								<RoomTypes>'.$xml_update.'</RoomTypes>
								<Currency>'.$currency.'</Currency>
								</Establishment>
								</Request>');

							$response = $wcfClient->RequestData($args);
							

							$data = simplexml_load_string($response->RequestDataResult);


							$error = @$data->attributes()->Error;

							if($error != "")
							{
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Inline Edit Channel Calendar Update',date('m/d/Y h:i:s a', time()));
								
							}else{
								foreach($data->Establishment as $esta)
								{
									if($esta->attributes()->Error)
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Inline Edit Channel Calendar Update',date('m/d/Y h:i:s a', time()));

									}else{
										foreach($esta->RoomTypes as $roomtype)
										{
											foreach($roomtype->RoomType->Date as $dates)
											{
												if((string)$dates->attributes()->Updated == "No")
												{
													$error = (string)$dates->attributes()->Error;
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Inline Edit Channel Calendar Update',date('m/d/Y h:i:s a', time()));
													
												}
											}
										}
									}
								}
							}
						}
					}
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}

	function reservation_update_no($table,$room_id,$update_date,$rate_type_id,$guest_count,$refunds,$column,$update)
	{
		$con_ch = 15;
		
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
					$mp_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();

					$mapping_values = get_data(MAP_VAL,array('mapping_id'=>$room_value->mapping_id))->row_array();
					$currency = $mp_details->Currency;
					
					$roomupdate = '<RoomType RoomTypeId="'.$mp_details->RoomTypeId.'">';
					if($room_value->update_availability=='1')
					{
						$update_values = 'yes';

						$roomupdate .= '<Date Date="'.$separate_date.'"';
						
						if($value_price == "1")
						{
							$roomupdate .= ' ClosedOut="true"';
						}else if($value_price == "0")
						{
							$available= get_data(TBL_UPDATE,array('individual_channel_id'=>15,'room_id'=>$room_id,'separate_date'=>$update_Details->separate_date))->row_array();

							if($available['availability'])
							{
								$roomupdate .= ' AvailableRoomCount="'.$available['availability'].'"';
							}
							
							$roomupdate .= ' ClosedOut="false"';

						}
						$roomupdate .= ' />';
					}
					else
					{
						$update_values = 'no';
					}
					$roomupdate	.=	'</RoomType>';
					$xml_update	.=	$roomupdate;
				}
				if($update_values=='yes')
				{
					$channel_id = '15';
					if($xml_update != "")
					{
						$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
						if($C_URL)
						{
							if($C_URL->mode=='0')
							{
								$url = $C_URL->test_url;
								
							}
							else if($C_URL->mode=='1')
							{
								$url = $C_URL->live_url;
							}

							$wcfClient = new SoapClient($url);

							$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="3"/>
								<Establishment Id="'.$ch_details->hotel_channel_id.'">
								<RoomTypes>'.$xml_update.'</RoomTypes>
								<Currency>'.$currency.'</Currency>
								</Establishment>
								</Request>');

							$response = $wcfClient->RequestData($args);
							

							$data = simplexml_load_string($response->RequestDataResult);


							$error = @$data->attributes()->Error;

							if($error != "")
							{
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Bulk Update',date('m/d/Y h:i:s a', time()));
								$this->session->set_flashdata('bulk_error',(string)$error);
							}else{
								foreach($data->Establishment as $esta)
								{
									if($esta->attributes()->Error)
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$esta->attributes()->Error,'Full Update',date('m/d/Y h:i:s a', time()));
										
									}else{
										foreach($esta->RoomTypes as $roomtype)
										{
											foreach($roomtype->RoomType->Date as $dates)
											{
												if((string)$dates->attributes()->Updated == "No")
												{
													$error = (string)$dates->attributes()->Error;
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$channel_id,(string)$error,'Full Update',date('m/d/Y h:i:s a', time()));
													
												}
											}
										}
									}
								}
							}
						}
					}	
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
		
	}
	
	function getReservationLists($source)
	{
		
		$this->db->order_by('import_reserv_id','desc'); 
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$data = $this->db->get(IM_TRAVEL_RESER)->result();
		if($data)
		{
			$travel = array();
			foreach($data as $val)
			{
				if($val->BookingStatus == "BOOKED")
				{
					$status = "Confirmed";
				}
				else if($val->BookingStatus == "MODIFIED")
				{
					$status = "Modify";
				}
				else if($val->BookingStatus == "CANCELLED")
				{
					$status = "Canceled";
				}

				$travelres = get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'BookingId'=>$val->BookingId))->result_array();
				if(count($travelres) != 0)
				{
					foreach($travelres as $roomdetails)
					{
						$room_ids = explode(',', rtrim($roomdetails['RoomTypeId'],','));
						$boardtypes = explode(',', rtrim($roomdetails['BoardTypeId'],','));
						$specialreq = explode('&&', rtrim($roomdetails['SpecialRequests'],'&&'));
						$totalcosts = explode(',', rtrim($roomdetails['TotalNetCost'],','));
						$statuses = explode(',', rtrim($roomdetails['RoomStatus'],','));
						$occupants = explode('$%$', rtrim($roomdetails['Occupants'],'$%$'));

						for($i=0; $i<count($room_ids); $i++)
						{
							$room_id = @get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room_ids[$i],'Id'=>$val->hotel_channel_id,'user_id' => current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id;
							if($source == "all")
							{
								$travel[] = (object)array(
									'reservation_id' => $val->import_reserv_id.'_'.$i,
									'reservation_code' => $val->BookingId,
									'status' => $status,
									'guest_name' => $val->CustomerTitle.' '.$val->CustomerFirstName.' '.$val->CustomerSurname,
									'room_id' => $room_id,
									'channel_id' => $val->channel_id,
									'start_date' => $val->CheckInDate,
									'end_date' => $val->CheckOutDate,
									'booking_date' =>$val->LastModificationDate,
									'currency_id' => $val->CurrencyCode,
									'price' => $totalcosts[$i],
									'num_nights' => $val->Duration,
									'current_date_time' => $val->current_date_time,
								);
							}else if($source == "separate")
							{
								$travel[] = (object)array(
									'import_reserv_id' => $val->import_reserv_id.'_'.$i,
									'IDRSV' => $val->BookingId,
									'STATUS' => $status,
									'FIRSTNAME' => $val->CustomerTitle.' '.$val->CustomerFirstName.' '.$val->CustomerSurname,
									'ROOMCODE' => $room_id,
									'channel_id' => $val->channel_id,
									'CHECKIN' => $val->CheckInDate,
									'CHECKOUT' => $val->CheckOutDate,
									'RSVCREATE' =>$val->LastModificationDate,
									'CURRENCY' => $val->CurrencyCode,
									'REVENUE' => $totalcosts[$i],
									'num_nights' => $val->Duration,
									'current_date_time' => $val->current_date_time,
								);
							}
						}
					}
				}
			}
			return $travel;
		}
		else
		{
			return $travel=array();
		}
	}


	function getReservationDetails($source,$ids)
	{
		$id_val = explode('_', $ids);
		$id = $id_val[0];
		$i = $id_val[1];
		$travelmodel					=	get_data(IM_TRAVEL_RESER,array('import_reserv_id '=>($id)))->row_array();

		$travelres = get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'BookingId'=>$travelmodel['BookingId']))->result_array();
		if(count($travelres) != 0)
		{
			foreach($travelres as $roomdetails)
			{
				$room_ids = explode(',', rtrim($roomdetails['RoomTypeId'],','));
				$boardtypes = explode(',', rtrim($roomdetails['BoardTypeId'],','));
				$specialreq = explode('###', rtrim($roomdetails['SpecialRequests'],'###'));
				$totalcosts = explode(',', rtrim($roomdetails['TotalNetCost'],','));
				$statuses = explode(',', rtrim($roomdetails['RoomStatus'],','));
				$occupants = explode('$%$', rtrim($roomdetails['Occupants'],'$%$'));
				$perdayprice = explode('$$', rtrim($roomdetails['roomdetails'],'$$'));


				$room_id = @get_data(MAP,array('channel_id'=>$travelmodel['channel_id'],'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room_ids[$i],'Id'=>$travelmodel['hotel_channel_id'],'user_id' => current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id;
				
				if($source=='list')
				{
					$data['curr_cha_id'] 		= 	secure('15');
				}
				$data['CC_NAME']			=	$travelmodel['CustomerTitle'].' '.$travelmodel['CustomerFirstName'].' '.$travelmodel['CustomerSurname'];
				$data['CC_NUMBER']			=	safe_b64decode($travelmodel['CardNumber']);
				$data['CC_DATE']			=	safe_b64decode($travelmodel['ExpiryDateMonth']);
				$data['CC_YEAR']			=	safe_b64decode($travelmodel['ExpiryDateYear']);
				$data['CC_CVC']             =   safe_b64decode($travelmodel['Cvv']);
				$data['typess'] = $i;
				
				
				$data['RESER_NUMBER'] 		= 	$travelmodel['BookingId'];
				$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($travelmodel['LastModificationDate']));
				$data['RESER_ID'] 			= 	$travelmodel['import_reserv_id'].'_'.$i;
				
				$data['curr_cha_currency'] 	= 	$travelmodel['CurrencyCode'];
				$data['guest_name']			= 	$travelmodel['CustomerTitle'].' '.$travelmodel['CustomerFirstName'].' '.$travelmodel['CustomerSurname'];
				$data['start_date'] 		= 	date('Y/m/d',strtotime($travelmodel['CheckInDate']));
				$data['end_date']			=	date('Y/m/d',strtotime($travelmodel['CheckOutDate']));
				$data['reservation_code']	= 	$travelmodel['BookingId'];
				$data['ROOMCODE']			=	$room_id;
				if($travelmodel['BookingStatus']=='BOOKED')
				{
					$data['status'] = 'New booking';
				}
				else if($travelmodel['BookingStatus']=='MODIFIED')
				{
					$data['status'] = 'Modification';
				}
				else if($travelmodel['BookingStatus']=='CANCELLED')
				{
					$data['status'] = 'Cancellation';
				}
				$data['start_date']			=	$travelmodel['CheckInDate'];
				$data['end_date']			=	$travelmodel['CheckOutDate'];
				
				$data['CHECKIN']			=	date('Y/m/d',strtotime($travelmodel['CheckInDate']));
				$data['CHECKOUT']			=	date('Y/m/d',strtotime($travelmodel['CheckOutDate']));
				
				$data['nig'] 				=	$travelmodel['Duration'];
				$Guest						=	explode('&&',rtrim($occupants[$i],'&&'));
				$data['members_count']		= 	count($Guest);
				$adult = 0;
				$child = 0;
				for ($a=0; $a < count($Guest); $a++) { 
					$details = explode('##',rtrim($Guest[$a],'##'));
					$type = explode('=', $details[2]);
					if($type[1] == 'Adult')
					{
						$adult += 1;
					}else{
						$child += 1;
					}

				}
				$data['ADULTS']             = $adult;
				$data['CHILDREN']           = $child;
				
				$data['description']		= 	$specialreq[$i];
				$data['policy_checin']		= 	'';//$bnowmodel['Start'];
				$data['policy_checout']		= 	'';//$bnowmodel['End'];
				$data['CRIB']				=	'';//$bnowmodel['CRIB'];
				$data['subtotal']			= 	$totalcosts[$i];
				$data['price'] 				=   $totalcosts[$i];
				$data['CURRENCY']			=	$travelmodel['CurrencyCode'];

				$data['guest_name'] 		=	$travelmodel['CustomerTitle'].' '.$travelmodel['CustomerFirstName'].' '.$travelmodel['CustomerSurname'];
				$data['email']				=	$travelmodel['CustomerEmail'];
				$data['street_name'] 		=	"";
				$data['city_name'] 			=	"";
				$data['country'] 			=	$travelmodel['CustomerCountryCode'];
				$data['phone'] 				=	$travelmodel['CustomerPhoneHome'];
				$data['commission']	  		= 	'';//$bnowmodel['COMMISSION'];
				$data['mealsinc']			= 	'';//$bnowmodel['MEALSINC'];
				$inbwdays = explode('~~',rtrim($perdayprice[$i],'~~'));
				//$baseRate = explode(',', $reservation_channeldetails['baseRate']);

				for($k=0; $k<count($inbwdays); $k++){
					if($inbwdays[$k] != ""){
						$dayprice = explode('##', $inbwdays[$k]);
						$data['perdayprice'][] = array(
							$dayprice[0] => $dayprice[1],
						);
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
				
				
				if($source=='print')
				{
					$data['room_id']			=	$room_id;
					$data['num_nights']			=	'1';
					$data['price']				=	$totalcosts[$i];
					$data['booking_date']		=	$data['RESER_DATE'];
					$data['mobile']				=	$travelmodel['CustomerPhoneHome'];
					$data['curr_cha_id'] 		= 	'15';
					$data['currency'] 			= 	$travelmodel['CurrencyCode'];
					$data['meal_name'] 			= 	'---';
					$data['cancel_description'] = 	'---';
				}
				return $data;
			}

		}
		
	}

	function invoiceCreate($reservation_id)
	{
		$ids = explode('_',insep_decode($reservation_id));
		$i = $ids[1];
		$reservation_details = get_data(IM_TRAVEL_RESER,array('import_reserv_id'=>$ids[0]))->row_array();

		$roomdetails = get_data(IM_TRAVEL_RESER_ROOMS,array('BookingId'=>$reservation_details['BookingId']))->result_array();

		if(count($roomdetails) != 0)
		{
			foreach($roomdetails as $roomdetail)
			{
				$totalcosts = explode(',', rtrim($roomdetail['TotalNetCost'],','));

				$data['page_heading'] = 'Create Invoice';
				$data['curr_cha_id'] 		= 	secure(15);
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();	
				$data= array_merge($user_details,$data);
				$data['reservation_id'] 	=	$reservation_details['import_reserv_id'].'_'.$i;
				$data['hotel_id'] 			=	$reservation_details['hotel_id'];
				$data['user_id']			=	$reservation_details['user_id'];
				$data['guest_name'] 		= 	$reservation_details['CustomerTitle'].' '.$reservation_details['CustomerFirstName'].' '.$reservation_details['CustomerSurname'];
				$data['email'] 				= 	$reservation_details['CustomerEmail'];
				$data['street_name'] 		= 	"";
				$data['city_name'] 			= 	"";
				$data['country'] 			= 	$reservation_details['CustomerCountryCode'];
				$data['start_date'] 		= 	$reservation_details['CheckInDate'];
				$data['end_date']			= 	$reservation_details['CheckOutDate'];
				
				$nig =$reservation_details['Duration'];
				$data['num_nights']	 		= 	1;
				$data['price'] 				= 	$totalcosts[$i];
				$data['cha_currency'] 		= 	$reservation_details['CurrencyCode'];
			}
		}
		return $data;
	}
}