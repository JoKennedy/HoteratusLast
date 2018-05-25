<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class Travelrepublic extends Front_Controller {

	function is_login()
    { 
        if(!user_id())
        redirect(base_url());
        return;
    }
   
    function is_admin()
    {   
        if(!admin_id())
        redirect(base_url());
        return;
    }

    function mailsettings_xml()
	{ 	  
		$this->load->library('email');  
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'text'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);	 	    
	}
    
    
    function importRooms($channel_id)
	{
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'status'=>'enabled'))->row();
		if($ch_details)
		{
			$C_URL = get_data(C_URL,array('channel_id'=>insep_decode($channel_id)))->row();
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

				$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="1"/><Establishment Id="'.$ch_details->hotel_channel_id.'"/></Request>');

				$response = $wcfClient->RequestData($args);
				$data = simplexml_load_string($response->RequestDataResult);
				$iserror = false;
				foreach ($data->Establishment as $error) {

					if($error->attributes()->Error)
					{
						$iserror = true;
						$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$error->attributes()->Error,'Import Rooms',date('m/d/Y h:i:s a', time()));
						return (string)$error->attributes()->Error;
					}

				}

				if(!$iserror)
				{
					foreach($data->Establishment as $travel)
					{
						//print_r($travel);
						$tb_details['user_id'] = current_user_type();
						$tb_details['hotel_id'] = hotel_id();
						$tb_details['Id'] = (string)$travel->attributes()->Id;
						$tb_details['BoardTypeId'] = "";
						$tb_details['BoardDescription'] = "";

						foreach ($travel->BoardTypes->BoardType as $board) {
							
							$tb_details['BoardTypeId'] .= (string)$board->attributes()->BoardTypeId.'~';
							$tb_details['BoardDescription'] .= (string)$board->attributes()->Description.'~';

						}

						$tb_details['Currency'] = (string)$travel->Currency;

						foreach($travel->RoomTypes->RoomType as $roomtype)
						{
							foreach($roomtype->attributes() as $key => $val)
							{
								$tb_details[$key] = (string)$val;
							}
							$exists = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'Id'=>$tb_details['Id'],'RoomTypeId'=> $tb_details['RoomTypeId']));
							if($exists->num_rows == 0)
							{
								insert_data(IM_TRAVELREPUBLIC,$tb_details);
							}else{
								update_data(IM_TRAVELREPUBLIC,$tb_details,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'Id'=>$tb_details['Id'],'RoomTypeId'=> $tb_details['RoomTypeId']));
							}
						}
					}
					return 'Insert';
				}
			}
			
		}
		else
		{
			return 'Enable';
		}
	}

	function mapping_settings($channel_id)
	{
		
		$data['travel'] 			= 	$this->travel_model->get_mapping_rooms($channel_id);
		$booking_all 				= 	$this->travel_model->get_all_mapping_rooms($channel_id);
		$data['channel_details'] 	=	$this->travel_model->get_all_mapped_rooms($channel_id);
		if($booking_all=='0')
		{
			$data['import_need'] = " Need to import the room for mapping!!!";
		}
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
		return $data;
	}

	function maptochannel($channel_id,$property_id)
	{
		$data['available']		=	get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
		$data['mapping_values']	=	get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
		$data['travel']	=	$this->travel_model->get_mapping_rooms(insep_decode($channel_id),'update');
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
		return $data;
	}

	function importRates($channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$mappingid = "",$arrival = "",$departure = "",$mapping='')
	{
		if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		$this->importAvailabilities($channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$mappingid,$arrival = "",$departure = "",$mapping='','importRates');
	}

	function importAvailabilities($channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$mappingid = "",$arrival = "",$departure = "",$mapping='',$importRates='',$user_id ="",$hotel_id = "")
    {
        if($user_id == "")
        {
        	$user_id = current_user_type();
        }
        if($hotel_id == "")
        {
        	$hotel_id = hotel_id();
        }
        $property_id = insep_decode($propertyid);
        if($mappingid != "")
		{
            $importDetails = get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }
		else
		{
            $importDetails = get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        }

        if($arrival != "" && $departure != "")
		{
            $start 					= 	date('d/m/Y',strtotime(str_replace('-', '/', $arrival)));
            $end 					= 	date('d/m/Y', strtotime(str_replace('-', '/', $departure)));
        }
		else
        {
            $start_date 			= 	date('d.m.Y');
            $end_date 				= 	date('d.m.Y', strtotime("+30 days"));
            $exp_start_date 		= 	date('Y-m-d');
            $exp_end_date 			= 	date('Y-m-d', strtotime("+30 days"));
            $hotelbed_start_date 	= 	str_replace('-', '', $exp_start_date);
            $hotelbed_end_date 		= 	str_replace('-', '', $exp_end_date);
            $start 					= 	date('d/m/Y');
            $end 					= 	date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id'] 		= 	insep_decode($channel_id);
        $channel['property_id'] 	= 	$property_id;
        $channel['rate_id'] 		= 	$rate_id;
        $channel['guest_count'] 	= 	$guest_count;
        $channel['refun_type'] 		= 	$refun_type;
        $channel['start']		 	= 	$start;
        $channel['end'] 			= 	$end;

        
        if(count($importDetails)!=0)
		{
			if($importDetails['channel_id'] == 15)
			{
				$this->travel_model->importAvailabilities($user_id,$hotel_id,$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure,$importRates);
            }
            if($mapping == "")
			{
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
			else
			{
                return true;
            }
        }
		else
        {
            if($mapping == "")
			{
                redirect(base_url());
            }
			else
			{
                return true;
            }
        }
    }

    function getReservation($channel_id)
	{
		if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }

		$ch_details	= get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'status'=>'enabled'))->row();
		
		if($ch_details)
		{
			if(insep_decode($channel_id)=='15')
			{
				$C_URL = get_data(C_URL,array('channel_id'=>insep_decode($channel_id)))->row();
				
				if($C_URL)
				{
					if($ch_details->mode=='0')
					{
						$url = $C_URL->test_url;
						
					}
					else if($ch_details->mode=='1')
					{
						$url = $C_URL->live_url;
					}
					date_default_timezone_set('Europe/London');
					//$stime = date("Y-m-d H:i",strtotime("-30 days")); 
					$ttime = date("Y-m-d H:i",strtotime("+30 days"));
					$stime = date("Y-m-d H:i"); 

					$wcfClient = new SoapClient($url);

					$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="4"/><Establishment Id="'.$ch_details->hotel_channel_id.'" LastModificationDate="'.$stime.'"  ReturnCardDetails="true" ReturnCvv="true"></Establishment></Request>');
					$response = $wcfClient->RequestData($args);

					$x_r_rq_data['channel_id'] 	= 	'15';						
					$x_r_rq_data['user_id'] 	=	'0';						
					$x_r_rq_data['hotel_id'] 	=	'0';						
					$x_r_rq_data['message'] 	= 	$args['requestDocument'];					
					$x_r_rq_data['type'] 		= 	'TRAVEL_REQ';						
					$x_r_rq_data['section'] 	=	'RESER';						
					//insert_data(ALL_XML,$x_r_rq_data);
					
					$xml_post_res = htmlspecialchars($response->RequestDataResult);
					
					$x_r_rs_data['channel_id']	=	'15';						
					$x_r_rs_data['user_id'] 	=	'0';						
					$x_r_rs_data['hotel_id'] 	=	'0';						
					$x_r_rs_data['message'] 	=	$xml_post_res;						
					$x_r_rs_data['type'] 		=	'TRAVEL_RES';						
					$x_r_rs_data['section'] 	=	'RESER';
					//insert_data(ALL_XML,$x_r_rs_data);
					$bookings = simplexml_load_string($response->RequestDataResult);
					//print_r($bookings);
					//$bookings = simplexml_load_string($response);
					foreach($bookings as $booking)
					{
						if($booking->attributes()->Error)
						{
							$this->reservationxmlmail($x_r_rs_data);
							$this->inventory_model->store_error(current_user_type(),hotel_id(),15,(string)$booking->attributes()->Error,'Reservation From Channel',date('m/d/Y h:i:s a', time()));
							$res['Error'] = (string)$booking->attributes()->Error;
						}else{
							if($booking->Bookings){
								if(@$booking->Bookings->Booking)
								{
									$this->reservationxmlmail($x_r_rs_data);
								}
								foreach($booking->Bookings as $books)
								{
									foreach($books as $book)
									{
										$roomdata['user_id'] = $data['user_id'] = current_user_type();
										$roomdata['hotel_id'] = $data['hotel_id']= hotel_id();
										$roomdata['hotel_channel_id'] = $data['hotel_channel_id'] = (string)$booking->attributes()->Id;
										$data['channel_id'] = 15;

										foreach($book->attributes() as $key => $val)
										{
											$data[$key] = (string)$val;
										}
										if($book->Payment)
										{
											foreach($book->Payment->attributes() as $key => $val)
											{
												$data[$key] = safe_b64encode($val);
											}
										}
										$roomdata['CheckInDate'] = $data['CheckInDate'];
										$data['CheckOutDate'] = date('Y-m-d', strtotime("+".$data['Duration'].' days',strtotime($data['CheckInDate'])));
										$roomdata['CheckOutDate'] = $data['CheckOutDate'];
										$roomdata['current_date_time'] = $data['current_date_time'] = date("Y-m-d H:i:s");
										$travelbook = get_data(IM_TRAVEL_RESER,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->num_rows();
										if($travelbook == 0)
										{
											insert_data(IM_TRAVEL_RESER,$data);
										}else{
											update_data(IM_TRAVEL_RESER,$data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));
										}
										$roomdata['BookingId'] = $data['BookingId'];
										
										$totalcost = 0;
										$perdayprice = "";
										$occupant_count = 0;
										foreach($book->Rooms as $rooms)
										{
											$roomid = array();
											$roomdata['Occupants'] = "";
											$roomdata['RoomTypeId'] = "";
											$roomdata['BoardTypeId'] = "";
											$roomdata['SpecialRequests'] = "";
											$roomdata['TotalNetCost'] = "";
											$roomdata['RoomStatus'] = "";

											foreach($rooms->Room as $room)
											{
												$totalcost = $totalcost + (string)$room->attributes()->TotalNetCost;
												$roomdata['RoomTypeId'] .= (string)$room->attributes()->RoomTypeId.',';
												$roomdata['BoardTypeId'] .= (string)$room->attributes()->BoardTypeId.',';
												$roomdata['SpecialRequests'] .= (string)$room->attributes()->SpecialRequests.'###';
												$roomdata['TotalNetCost'] .= (string)$room->attributes()->TotalNetCost.',';
												$roomdata['RoomStatus'] .= (string)$room->attributes()->RoomStatus.',';
												
												$roomid[] = (string)$room->attributes()->RoomTypeId;
												$roomdata['roomcount'] = count($roomid);
												
												foreach($room->Occupants->Occupant as $occupant)
												{
													foreach($occupant->attributes() as $key => $val)
													{
														$roomdata['Occupants'] .= $key."=".$val.'##';
													}
													$occupant_count = $occupant_count + 1;
													$roomdata['Occupants'] .= "&&";
												}

												$roomdata['Occupants'] .= "$%$";

												$price = (string)$room->attributes()->TotalNetCost / $data['Duration'];
												
												for($k = 0; $k < $data['Duration']; $k++)
												{
													$day = date('d/m/Y', strtotime("+".$k.' days',strtotime($data['CheckInDate'])));
													
													if($price != "")
													{
														$perdayprice .= $day."##".$price."~~";
													}
												}
												$perdayprice .= "$$";
											}
										}
										$roomdata['roomdetails'] = $perdayprice;
										$travelbookrooms = get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->num_rows();
										if($travelbookrooms == 0)
										{
											insert_data(IM_TRAVEL_RESER_ROOMS,$roomdata);
										}else{
											update_data(IM_TRAVEL_RESER_ROOMS,$roomdata,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));
										}
										$upate['totalprice'] = $totalcost;
										$update['adult'] = $occupant_count;
										update_data(IM_TRAVEL_RESER,$upate,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));

										//$this->reservation_model->send_confirmation_email(15,$data['BookingId'],$data['user_id'],$data['hotel_id']);
										$this->reservation_log(15,$data['BookingId'],current_user_type(),hotel_id());
										$importBookingDetails	=	get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->row_array();
										//print_r($importBookingDetails);
										if(count($importBookingDetails)!=0)
										{
											$arrival = date('Y-m-d',strtotime($importBookingDetails['CheckInDate'
												]));
											$departure = date('Y-m-d',strtotime($importBookingDetails['CheckOutDate']));
											
											$roomtypeid = explode(',', rtrim($importBookingDetails['RoomTypeId'],','));
											$rooms = array();
											for($i=0; $i<count($roomtypeid); $i++)
											{	
												if(!in_array($roomtypeid[$i], $rooms))
												{
													$rooms[] = $roomtypeid[$i];
													$mappingDetails		=	get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$roomtypeid[$i],'user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'Id'=>$importBookingDetails['hotel_channel_id']),'map_id')->row_array();
													
													if(count($mappingDetails)!=0)
													{								
														$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>15))->row_array();
														if(count($roomMappingDetails)!=0)
														{
															$this->importAvailabilities(insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$mappingDetails['map_id'],$arrival,$departure,'mapping');
														}
													}
												}
											}
											
										}
									}
								}
								$res['succes'] = 'Insert';
							}
						}
					}
					
				}
				else
				{
					$res['Enable'] = 'Enable';
				}
			}
			else
			{
				$res['Enable'] = 'Enable';
			}
		}
		else
		{
			$res['Enable'] = 'Enable';
		}
		return $res;
	}


	function reservation_log($channel_id,$booking_id,$user_id,$hotel_id)
	{
		if($user_id == ""){
			$user_id = current_user_type();
		}
		if($hotel_id == ""){
			$hotel_id = hotel_id();
		}
		$rese_id= $booking_id;

		$user_details = get_data(TBL_USERS,array('user_id'=>$user_id))->row_array();
		$username = ucfirst($user_details['fname']).' '.ucfirst($user_details['lname']);
		
		$curr_cha_id = $channel_id; 
		
		if($curr_cha_id==15)
		{
			$print_details = get_data(IM_TRAVEL_RESER_ROOMS,array('BookingId'=>$rese_id))->row_array();
			$booking_details = get_data(IM_TRAVEL_RESER,array('BookingId' => $print_details['BookingId']))->row_array();
			
			if(count($print_details)!=0)
			{
				$ID = $print_details['BookingId'];
				$ChannelName = "Travelrepublic";
				$checkin = $print_details['CheckInDate'];
				$checkout = $print_details['CheckOutDate'];
				$price = $booking_details['CurrencyCode'].$booking_details['totalprice'];
				$name = $booking_details['CustomerTitle'].' '.$booking_details['CustomerFirstName'].' '.$booking_details['CustomerSurname'];
				$roomids  =  explode(',', rtrim($print_details['RoomTypeId'],','));
				$roomName = "";
				for($i = 0; $i < count($roomids); $i++)
				{
					$map = get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$roomids[$i],'Id'=>$print_details['hotel_channel_id'],'user_id'=>$user_id,'hotel_id' => $hotel_id));
	                if($map->num_rows != 0){
	                  $map_id = $map->row()->map_id;
	                  $prop_id = get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>$map_id));
	                  if($prop_id->num_rows != 0){
	                    $prop_id = $prop_id->row()->property_id;
	                    $roomName .= get_data(TBL_PROPERTY,array('property_id'=>$prop_id))->row()->property_name.',';
	                  }
	                 
	                }
	            }

				if($booking_details['BookingStatus']== "BOOKED")
				{
					$status = 'New booking';
				}
				else if($booking_details['BookingStatus']== "MODIFIED")
				{
					$status = 'Modification';
				}
				else if($booking_details['BookingStatus']== "CANCELLED")
				{
					$status = 'Cancellation';
				}
			}
		}
		if(!isset($roomName)){
			$roomName = "No Room Set";
		}
		$message = "Location:Channel Reservation,Reservation Id:".$ID.", Name:".$name.", Check In Date:".$checkin.", Check Out Date:".$checkout.", Room:".$roomName.", Price:".$price.", Booking Status:".$status.", Channel:".$ChannelName." IP:".$this->input->ip_address()." User:".$username;
        $this->inventory_model->write_log($message,$user_id);
	}

	function reservationxmlmail($data)
    {
    	insert_data(ALL_XML,$data);
    	$cha_name = ucfirst(get_data(TBL_CHANNEL,array('channel_id'=>$data['channel_id']))->row()->channel_name);

		$message = $this->load->view("email/xmlemail",$data,TRUE);

        $subject = "Reservation From ".$cha_name; 

        $admin_detail = get_data(TBL_SITE,array('id'=>1))->row();

        $this->mailsettings_xml();   

        $this->email->from($admin_detail->email_id);

        $this->email->to($admin_detail->xmlemail); 

        $this->email->subject($subject); 

        $this->email->message($message);

        $this->email->send(); 
    }

    function getReservationCron()
	{
		$channelDetails = get_data(CONNECT,array('channel_id'=>15,'status'=>'enabled','mode'=>1))->result();
		$cha_name = "Travelrepublic";
		foreach($channelDetails as $ch_details)
		{
			$C_URL = get_data(C_URL,array('channel_id'=>15))->row();
			$user_id = $ch_details->user_id;
			$hotel_id = $ch_details->hotel_id;
			
			if($C_URL)
			{
				if($ch_details->mode=='0')
				{
					$url = $C_URL->test_url;
					
				}
				else if($ch_details->mode=='1')
				{
					$url = $C_URL->live_url;
				}
				date_default_timezone_set('Europe/London');
				$stime = date("Y-m-d H:i"); 
				//$ttime = date("Y-m-d H:i",strtotime("+30 days"));

				$wcfClient = new SoapClient($url);

				$args = array('requestDocument' =>'<Request><Authentication CMId="'.$ch_details->cmid.'" Guid="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" Function="4"/><Establishment Id="'.$ch_details->hotel_channel_id.'" LastModificationDate="'.$stime.'"  ReturnCardDetails="true" ReturnCvv="true"></Establishment></Request>');
				$response = $wcfClient->RequestData($args);

				$x_r_rq_data['channel_id'] 	= 	'15';						
				$x_r_rq_data['user_id'] 	=	'0';						
				$x_r_rq_data['hotel_id'] 	=	'0';						
				$x_r_rq_data['message'] 	= 	$args['requestDocument'];					
				$x_r_rq_data['type'] 		= 	'TRAVEL_REQ';						
				$x_r_rq_data['section'] 	=	'RESER';						
				//insert_data(ALL_XML,$x_r_rq_data);
				
				$xml_post_res = htmlspecialchars($response->RequestDataResult);
				
				$x_r_rs_data['channel_id']	=	'15';						
				$x_r_rs_data['user_id'] 	=	'0';						
				$x_r_rs_data['hotel_id'] 	=	'0';						
				$x_r_rs_data['message'] 	=	$xml_post_res;						
				$x_r_rs_data['type'] 		=	'TRAVEL_RES';						
				$x_r_rs_data['section'] 	=	'RESER';
				//insert_data(ALL_XML,$x_r_rs_data);
				$bookings = simplexml_load_string($response->RequestDataResult);
				//print_r($bookings);
				//$bookings = simplexml_load_string($response);
				foreach($bookings as $booking)
				{
					if($booking->attributes()->Error)
					{
						$this->reservationxmlmail($x_r_rs_data);
						$this->inventory_model->store_error($user_id,$hotel_id,15,(string)$booking->attributes()->Error,'Reservation From Channel',date('m/d/Y h:i:s a', time()));
						$res['Error'] = (string)$booking->attributes()->Error;
					}else{
						if($booking->Bookings){
							if(count(@$booking->Bookings->Booking)){
								$this->reservationxmlmail($x_r_rs_data);
							}
							foreach($booking->Bookings as $books)
							{
								foreach($books as $book)
								{
									$roomdata['user_id'] = $data['user_id'] = $user_id;
									$roomdata['hotel_id'] = $data['hotel_id']= $hotel_id;
									$roomdata['hotel_channel_id'] = $data['hotel_channel_id'] = (string)$booking->attributes()->Id;
									$data['channel_id'] = 15;

									foreach($book->attributes() as $key => $val)
									{
										$data[$key] = (string)$val;
									}
									if($book->Payment)
									{
										foreach($book->Payment->attributes() as $key => $val)
										{
											$data[$key] = safe_b64encode($val);
										}
									}
									$roomdata['CheckInDate'] = $data['CheckInDate'];
									$data['CheckOutDate'] = date('Y-m-d', strtotime("+".$data['Duration'].' days',strtotime($data['CheckInDate'])));
									$roomdata['CheckOutDate'] = $data['CheckOutDate'];
									$roomdata['current_date_time'] = $data['current_date_time'] = date("Y-m-d H:i:s");
									$travelbook = get_data(IM_TRAVEL_RESER,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->num_rows();
									if($travelbook == 0)
									{
										insert_data(IM_TRAVEL_RESER,$data);
									}else{
										update_data(IM_TRAVEL_RESER,$data,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));
									}
									$roomdata['BookingId'] = $data['BookingId'];
									
									$totalcost = 0;
									$perdayprice = "";
									$occupant_count = 0;
									foreach($book->Rooms as $rooms)
									{
										$roomid = array();
										$roomdata['Occupants'] = "";
										$roomdata['RoomTypeId'] = "";
										$roomdata['BoardTypeId'] = "";
										$roomdata['SpecialRequests'] = "";
										$roomdata['TotalNetCost'] = "";
										$roomdata['RoomStatus'] = "";

										foreach($rooms->Room as $room)
										{
											$totalcost = $totalcost + (string)$room->attributes()->TotalNetCost;
											$roomdata['RoomTypeId'] .= (string)$room->attributes()->RoomTypeId.',';
											$roomdata['BoardTypeId'] .= (string)$room->attributes()->BoardTypeId.',';
											$roomdata['SpecialRequests'] .= (string)$room->attributes()->SpecialRequests.'###';
											$roomdata['TotalNetCost'] .= (string)$room->attributes()->TotalNetCost.',';
											$roomdata['RoomStatus'] .= (string)$room->attributes()->RoomStatus.',';
											
											$roomid[] = (string)$room->attributes()->RoomTypeId;
											$roomdata['roomcount'] = count($roomid);
											
											foreach($room->Occupants->Occupant as $occupant)
											{
												foreach($occupant->attributes() as $key => $val)
												{
													$roomdata['Occupants'] .= $key."=".$val.'##';
												}
												$occupant_count = $occupant_count + 1;
												$roomdata['Occupants'] .= "&&";
											}

											$roomdata['Occupants'] .= "$%$";

											$price = (string)$room->attributes()->TotalNetCost / $data['Duration'];
											
											for($k = 0; $k < $data['Duration']; $k++)
											{
												$day = date('d/m/Y', strtotime("+".$k.' days',strtotime($data['CheckInDate'])));
												
												if($price != "")
												{
													$perdayprice .= $day."##".$price."~~";
												}
											}
											$perdayprice .= "$$";
										}
									}
									$roomdata['roomdetails'] = $perdayprice;
									$travelbookrooms = get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->num_rows();
									if($travelbookrooms == 0)
									{
										insert_data(IM_TRAVEL_RESER_ROOMS,$roomdata);
									}else{
										update_data(IM_TRAVEL_RESER_ROOMS,$roomdata,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));
									}
									$upate['totalprice'] = $totalcost;
									$update['adult'] = $occupant_count;
									update_data(IM_TRAVEL_RESER,$upate,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']));

									//$this->reservation_model->send_confirmation_email(15,$data['BookingId'],$data['user_id'],$data['hotel_id']);
									$this->reservation_log(15,$data['BookingId'],$user_id,$hotel_id);
									$importBookingDetails	=	get_data(IM_TRAVEL_RESER_ROOMS,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$data['hotel_channel_id'],'BookingId'=>$data['BookingId']))->row_array();
									//print_r($importBookingDetails);
									if(count($importBookingDetails)!=0)
									{
										$arrival = date('Y-m-d',strtotime($importBookingDetails['CheckInDate'
											]));
										$departure = date('Y-m-d',strtotime($importBookingDetails['CheckOutDate']));
										
										$roomtypeid = explode(',', rtrim($importBookingDetails['RoomTypeId'],','));
										$rooms = array();
										for($i=0; $i<count($roomtypeid); $i++)
										{	
											if(!in_array($roomtypeid[$i], $rooms))
											{
												$rooms[] = $roomtypeid[$i];
												$mappingDetails		=	get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$roomtypeid[$i],'user_id'=>$user_id,'hotel_id'=>$hotel_id,'Id'=>$importBookingDetails['hotel_channel_id']),'map_id')->row_array();
												
												if(count($mappingDetails)!=0)
												{								
													$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>15))->row_array();
													if(count($roomMappingDetails)!=0)
													{
														$this->importAvailabilities(insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$mappingDetails['map_id'],$arrival,$departure,'mapping',$user_id,$hotel_id);
													}
												}
											}
										}
										
									}
								}
							}
						}
					}
				}
				
			}			
			
		}	

		$meg['result'] = '1';
		$meg['content']='Successfully import reservation from '.$cha_name.'!!!';
		echo json_encode($meg);	
	}
     
}