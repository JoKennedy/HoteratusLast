<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Availability_model extends CI_Model
{
	/*  Update Availability Bnow To Other API Start */
	
	function updateAvailabilityBNOW($start,$end,$owner_id,$hotel_id,$property_id,$from_channel_id)
	{
		//echo $property_id; 
		if(preg_match('/^\d\d\d\d\/\d\d\/\d\d$/is',trim($start),$match)){
			$start = preg_replace('/\//is','-',$start);
		}
		if(preg_match('/^\d\d\d\d\/\d\d\/\d\d$/is',trim($end),$match)){
			$end = preg_replace('/\//is','-',$end);
		}
		$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
		$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
		$periodInterval	=	new DateInterval( "P1D" );
		$period			=	new DatePeriod( $startDate, $periodInterval, $endDate );
		
		$getMappedRooms	=	get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id !='=>$from_channel_id,'property_id'=>$property_id,'enabled'=>'enabled','update_availability'=>1),'import_mapping_id,property_id,rate_id,guest_count,refun_type,channel_id')->result();
		/* echo '<pre>';
		print_r($getMappedRooms); */
		//die;
		if($getMappedRooms)
		{
			$book_xml_data	=	'';
			$bookin_value	=	'';
			$exp_xml_data	=	'';
			foreach($getMappedRooms as $room_value)
			{
				if($room_value->channel_id=='2')
				{
					$Book_mp_details = get_data(BOOKING,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();

					$book_xml_data.='<room id="'.$Book_mp_details->B_room_id.'">';
				}
				else if($room_value->channel_id=='1')
				{
					$exp_mp_details = get_data(IM_EXP,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
				}
				else if($room_value->channel_id=='5')
				{
					$hbeds_details = get_data(IM_HOTELBEDS_ROOMS,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
				}
				else if($room_value->channel_id=='8')
				{
					$gta_details = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
				}
				else if($room_value->channel_id=='17')
				{
					$bnow_details = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row();
				}
				else if($room_value->channel_id=='15')
				{
					$travel_details = get_data(IM_TRAVELREPUBLIC,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row(); 
				}
				else if($room_value->channel_id=='14')
				{
					$wbeds_details = get_data(IM_WBEDS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $room_value->channel_id,'import_mapping_id'=>$room_value->import_mapping_id))->row(); 
				}
				else if ($room_value->channel_id == '9') 
				{
				    $airbnb_details = get_data(IM_AIRBNB, array(
					'user_id' => current_user_type(),
					'hotel_id' => hotel_id(),
					'channel_id' => $room_value->channel_id,
					'import_mapping_id' => $room_value->import_mapping_id
				    ))->row();
				}
				
				$update_availability	=	'';
				$hbeds_xml_data	=	'';
				$gta_xml_data	=	'';
				$bnow_xml_data	=	'';
				$travel_xml_data	=	'';
				$i=0;
				foreach($period as $date)
				{
					$i++;
					$sep_date 	=	$date->format("d/m/Y");
					$book_date	=	$date->format("Y-m-d");
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
					if($room_value->channel_id=='11' && @$available_update_details['availability']!='')
					{
						$update_availability	.=	$available_update_details['availability'].',=';
					}
					else if($room_value->channel_id=='2' && @$available_update_details['availability']!='')
					{
						$update_booking		=	'yes';
						
						$book_xml_data	.=	'<date value="'.$book_date.'">
						<rate id="'.$Book_mp_details->B_rate_id.'"/>
						<roomstosell>'.$available_update_details['availability'].'</roomstosell>
						</date>';
					}
					else if($room_value->channel_id=='1' && @$available_update_details['availability']!='')
					{
						$update_expedia		=	'yes';
						
						$exp_xml_data	.=	'<AvailRateUpdate>
											<DateRange from="'.$book_date.'" to="'.$book_date.'" />
											<RoomType id="'.$exp_mp_details->roomtype_id.'">
											<Inventory totalInventoryAvailable="'.$available_update_details['availability'].'" />
											</RoomType>
											</AvailRateUpdate>';
					}
					else if($room_value->channel_id=='5' && @$available_update_details['availability']!='')
					{
						$hbeds_date	=	$date->format("Ymd");
						$hbeds_xml_data	.=	'<InventoryItem>
											<DateFrom date="'.$hbeds_date.'"/>
											<DateTo date="'.$hbeds_date.'"/>
											<Room available="'.$available_update_details['availability'].'" quote="'.$available_update_details['availability'].'">
											<RoomType type="'.$hbeds_details->type.'" code="'.$hbeds_details->roomname.'" characteristic="'.$hbeds_details->characterstics.'"/>
											</Room>
											</InventoryItem>';
					}
					else if($room_value->channel_id=='8' && @$available_update_details['availability']!='')
					{
						$gta_xml_data	.=	'<StayDate Date="'.$book_date.'">
						<Inventory RoomId="'.$gta_details->Id.'" >
						<Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$available_update_details['availability'].'" ReleaseDays="0"/>
						</Inventory>
						</StayDate>';
					}
					else if($room_value->channel_id=='17' && @$available_update_details['availability']!='')
					{
						$update_bnow		=	'yes';
						
						$bnow_xml_data	.=	'<HotelData ItemIdentifier="'.$i.'">';
						
						$bnow_xml_data	.=	'<ProductReference InvTypeCode="'.$bnow_details->InvTypeCode.'" RatePlanCode="'.$bnow_details->RatePlanCode.'"></ProductReference>';
						
						$bnow_sun 	= 'true';
						$bnow_mon 	= 'true';
						$bnow_tue 	= 'true';
						$bnow_wed 	= 'true';
						$bnow_thur	= 'true';
						$bnow_fri 	= 'true';
						$bnow_sat 	= 'true';
						
						$bnow_xml_data 	.= 	'<ApplicationControl Start="'.$book_date.'" End="'.$book_date.'" Sun="'.$bnow_sun.'" Mon="'.$bnow_mon.'" Tue="'.$bnow_tue.'" Wed="'.$bnow_wed.'" Thu="'.$bnow_thur.'" Fri="'.$bnow_fri.'" Sat="'.$bnow_sat.'" />';
						
						$bnow_xml_data	.=	'<BookingLimit><TransientAllotment Allotment="'.$available_update_details['availability'].'"/></BookingLimit>';
						
						$bnow_xml_data	.=	'</HotelData>';
					}
					else if($room_value->channel_id=='15' && @$available_update_details['availability']!='')
					{
						$update_travel		=	'yes';
						$travel_xml_data	.=	'<RoomType RoomTypeId="'.$travel_details->RoomTypeId.'">
												<Date Date="'.$book_date.'" AvailableRoomCount="'.$available_update_details['availability'].'" />
												</RoomType>';
					}
					if ($room_value->channel_id == '9' && @$available_update_details['availability'] != '') {
						$ch_details = get_data(CONNECT, array(
						    'user_id' => $owner_id,
						    'hotel_id' => $hotel_id,
						    'channel_id' => $room_value->channel_id
						))->row();
						include('cURL.php');
						$date                   = $book_date;
						$curl                   = new cURL(TRUE);
						$url                    = 'https://api.airbnb.com/v1/authorize';
						$formData['client_id']  = $ch_details->other_id;
						$formData['locale']     = 'en-US';
						$formData['currency']   = 'USD';
						$formData['grant_type'] = 'password';
						$formData['username']   = $ch_details->user_name;
						$formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);

						$content = '';
						foreach ($formData as $key => $value) {
						    $content .= '&' . $key . '=' . $value;
						}
						$content    = replace_content_hex($content);
						$resultFile = $curl->post($url, $content);

						if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
						    $AuthToken = $match[1];
						    foreach ($period as $date) {
							$url = 'https://api.airbnb.com/v2/calendars/' . $airbnb_details->RoomId . '/' . $date . '/' . $date;

							$formData = array();

							//For Alot Update
							if ($available_update_details['availability'] != '') {
							    if ($available_update_details['availability'] > 0) {
								$formData['availability'] = 'available';
							    } else {
								$formData['availability'] = 'unavailable';
							    }
							}

							//print_r($formData);
							$content          = json_encode($formData);
							$curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
							$curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";

							$resultFile = put($url, $content, $curl->headers);
							if ($resultFile == '') {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							} elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "-DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							} elseif (preg_match('/error/is', $resultFile, $match)) {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							}
						    }
						} elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
						    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
						} else {
						    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
						}
					    }
				}
				if($room_value->channel_id=='2')
				{
					$book_xml_data .='</room>';
				}
				if($room_value->channel_id=='11')
				{
					$up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$start)));
					
					$availability	=	trim($update_availability,',=');
					
					$ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id))->row();
					
					if($ch_details->mode == 0)
					{
						$urls = explode(',', $ch_details->test_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$reco[$path[0]] = $path[1];
						}
					}
					else if($ch_details->mode == 1)
					{
						$urls = explode(',', $ch_details->live_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$reco[$path[0]] = $path[1];
						}
					} 

					$url = trim($reco['urate_avail']);
					
					$mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
					
					$xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
					<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
					<soap12:Body>
					<UpdateAvail xmlns="https://www.reconline.com/"> 
					<User>'.$ch_details->user_name.'</User>
					<Password>'.$ch_details->user_password.'</Password>
					<idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
					<idSystem>0</idSystem>
					<ForeignPropCode></ForeignPropCode>
					<IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
					<ExcludeRateLevels></ExcludeRateLevels>
					<IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
					<ExcludeRoomTypes></ExcludeRoomTypes>
					<AvailMode>0</AvailMode>
					<StartDate>'.$up_sart_date.'</StartDate>
					<Availability>='.$availability.'</Availability>
					</UpdateAvail>
					</soap12:Body>
					</soap12:Envelope>';
					$headers_avail = array(
					"Content-type: application/soap+xml; charset=utf-8",
					"Host:www.reconline.com",
					"Content-length: ".strlen($xml_post_string_update),
					); 
					
					//echo $xml_post_string_update; //die;
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
					$ss = curl_getinfo($ch);                
					$response = curl_exec($ch);
					
					//echo $response;
					
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml = simplexml_load_string($xml);
					$json = json_encode($xml);
					$responseArray = json_decode($json,true);
					$Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
					$soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
					if(count($Errorarray)=='0' && count($soapFault)=='0')
					{
						$reconline_availability_response="success";
					}
					else 
					{
						$reconline_availability_response="error";

						if(count($Errorarray)!='0')
						{
							$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Errorarray['WARNING'],'Bnow To Reconline',date('m/d/Y h:i:s a', time()));
						}
						else if(count($soapFault)!='0')
						{  
							$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$soapFault['soapText'],'Bnow To Reconline',date('m/d/Y h:i:s a', time()));    
						}
						return false;
					}
					curl_close($ch);
				}
				else if($room_value->channel_id=='5')
				{
					$ch_details	=	get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'5'))->row();
					
					if($ch_details->mode == 0)
					{
						$urls = explode(',', $ch_details->test_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$htb[$path[0]] = $path[1];
						}
					}
					else if($ch_details->mode == 1)
					{
						$urls = explode(',', $ch_details->live_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$htb[$path[0]] = $path[1];
						}
					} 
					
					$hxml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
					<soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> 
					<soapenv:Body>
					<getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
					<HSI_ContractInventoryModificationRQ xmlns="http://axis.frontend.hsi.hotelbeds.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.hotelbeds.com/schemas/2005/06/messages /HSI_ContractInventoryModificationRQ.xsd" echoToken="'.date('U').'">
					<Language>ENG</Language>
					<Credentials>
					<User>'.$ch_details->user_name.'</User>
					<Password>'.$ch_details->user_password.'</Password>
					</Credentials>
					<Contract>
					<Name>'.$hbeds_details->contract_name.'</Name>
					<IncomingOffice code="'.$hbeds_details->contract_code.'"/>
					<Sequence>'.$hbeds_details->sequence.'</Sequence>
					</Contract>'.$hbeds_xml_data.'
					</HSI_ContractInventoryModificationRQ>
					</getHSIContractInventoryModification>
					</soapenv:Body>
					</soapenv:Envelope>' ; 
					
					//echo $hxml_post_string;
					
					$headers = array(
								"SOAPAction:no-action",
								"Content-length: ".strlen($hxml_post_string),
								);
								
					$url = trim($htb['urate_avail']);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $hxml_post_string);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_getinfo($ch, CURLINFO_HTTP_CODE);
					$ss = curl_getinfo($ch);                
					$response = curl_exec($ch);
					
					//echo $response; 
					
					$xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml_parse = simplexml_load_string($xmlreplace);
					$json = json_encode($xml_parse);
					$responseArray = json_decode($json,true);
					$xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
					if($xml->ErrorList->Error)
					{
						$status = @$xml->ErrorList->Error->DetailedMessage;
						$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$xml->ErrorList->Error->DetailedMessage,'Bnow To HotelBeds',date('m/d/Y h:i:s a', time()));
					}
					else if($xml->Status != "Y")
					{
						$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,'Try Again','Bnow To HotelBeds',date('m/d/Y h:i:s a', time()));
					}
				}
				else if($room_value->channel_id=='8')
				{
					$ch_details	=	get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'8'))->row();
					if($ch_details->mode == 0)
					{
						$urls = explode(',', $ch_details->test_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$gta[$path[0]] = $path[1];
						}
					}
					else if($ch_details->mode == 1)
					{
						$urls = explode(',', $ch_details->live_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							$gta[$path[0]] = $path[1];
						}
					}

					$gta_post_string='<GTA_InventoryUpdateRQ
					xmlns = "http://www.gta-travel.com/GTA/2012/05"
					xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
					xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05
					GTA_InventoryUpdateRQhelp.xsd">
					<User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
					<InventoryBlock ContractId = "'.$gta_details->contract_id.'" PropertyId = "'.$gta_details->hotel_channel_id.'">
					<RoomStyle>';
					$gta_post_string.=$gta_xml_data;
					$gta_post_string.=' </RoomStyle></InventoryBlock></GTA_InventoryUpdateRQ>';
					
					//echo $gta_post_string;
					
					$soapUrl	=	trim($gta['uavail']);
					$ch = curl_init($soapUrl);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $gta_post_string);
					$response = curl_exec($ch); 
					
					//echo $response;
					
					$data = simplexml_load_string($response);
					$Error_Array = @$data->Errors->Error;
					if($Error_Array!='')
					{
						$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$Error_Array,'Bnow To GTA',date('m/d/Y h:i:s a', time()));
					}
				}
				else if($room_value->channel_id=='14')
				{
					$ch_details	=	get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'14'))->row();
					
					$C_URL = get_data(C_URL,array('channel_id'=>'14'))->row();
			
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
					
					$wxml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
				    <soapenv:Header/>
				    <soapenv:Body>
					  <impl:updateInventoryRQ>
						 <impl:updateInventoryRQRow>
							<impl:idCliePor>'.$ch_details->user_name.'</impl:idCliePor>
							<impl:pwCliePor>'.$ch_details->user_password.'</impl:pwCliePor>
							<impl:ideCHM>'.ideCHM.'</impl:ideCHM>
							<impl:codeRate>'.$wbeds_details->codeRate.'</impl:codeRate>
							<impl:referenceHotel>'.$wbeds_details->HotelCode.'</impl:referenceHotel>
							<impl:beginDate>'.$sep_date.'</impl:beginDate>
							<impl:endDate>'.$sep_date.'</impl:endDate>
							<impl:codeRoomType>'.$wbeds_details->codeRoomType.'</impl:codeRoomType>
							<impl:codeRoomFeature>'.$wbeds_details->codeRoomFeature.'</impl:codeRoomFeature>
							<impl:typeInventory>99</impl:typeInventory>
							<impl:statusInventory>A</impl:statusInventory>
							<impl:allotment>'.$available_update_details['availability'].'</impl:allotment>
						 </impl:updateInventoryRQRow>
					  </impl:updateInventoryRQ>
				    </soapenv:Body>
				</soapenv:Envelope>' ;
					
					//echo $gta_post_string;
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url[0]);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $wxml_post_string);
					$ss 		=	curl_getinfo($ch);                
					$response 	=	curl_exec($ch);
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml = simplexml_load_string($xml);
					$json = json_encode($xml);
					$responseArray = json_decode($json,true);
					$Error = @$responseArray['soapenvBody']['updateInventoryRS']['updateInventoryRSRow']['error'];
					$Fault = @$responseArray['soapenvBody']['soapenvFault']['faultstring'];
					if(($Error!='' && $Error!='OK') || $Fault!='')
					{
						if($Error!='')
						{
							$value=$Error;
						}
						else if($Fault!='')
						{
							$value=$Fault;
						}
						else
						{
							$value='';
						}
						$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$value,'WBEDS',date('m/d/Y h:i:s a', time()));
					}
				}
			}
			if(@$update_booking=='yes')
			{
				$ch_details	=	get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'2'))->row();
				
				$xml_post_string_update='<?xml version="1.0" encoding="UTF-8"?>
				<request>
				<username>'.$ch_details->user_name.'</username>
				<password>'.$ch_details->user_password.'</password>
				<hotel_id>'.$ch_details->hotel_channel_id.'</hotel_id>'.$book_xml_data;
				$xml_post_string_update.='</request>';
				//echo $xml_post_string_update;
				$URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);    
				//echo $output;				
				$data_api = simplexml_load_string($output); 
				$error = @$data_api->fault;
				if($error)
				{
					$this->inventory_model->store_error($owner_id,$hotel_id,'2',(string)$data_api->fault->attributes()->string,'Bnow To Booking.com',date('m/d/Y h:i:s a', time()));
				}
				preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
				$end = end($output);
				if(is_array($end))
				{
					$end_end = end($end);
					$ruid = str_replace("!-- RUID: [", '', $end_end);
					$ruid =  trim(str_replace('] --', '', $ruid));
					$this->booking_model->store_ruid_booking($ruid,'bulk_update',$owner_id,$hotel_id);
				}
				else
				{
					$ruid = str_replace("!-- RUID: [", '', $end);
					$ruid =  trim(str_replace('] --', '', $ruid));
					$this->booking_model->store_ruid_booking($ruid,'bulk_update',$owner_id,$hotel_id);
				}
			}
			if(@$update_expedia=='yes')
			{
				$ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'1'))->row();
				
				if($ch_details->mode == 0)
				{
					$urls = explode(',', $ch_details->test_url);
					foreach($urls as $url)
					{
						$path = explode("~",$url);
						$exp[$path[0]] = $path[1];
					}
				}
				else if($ch_details->mode == 1)
				{
					$urls = explode(',', $ch_details->live_url);
					foreach($urls as $url)
					{
						$path = explode("~",$url);
						$exp[$path[0]] = $path[1];
					}
				}  
				$URL = trim($exp['urate_avail']);
				$exp_xml	= '<?xml version="1.0" encoding="UTF-8"?>
				<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
				<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
				<Hotel id="'.$exp_mp_details->hotel_channel_id.'"/>';
				$exp_xml	.=	$exp_xml_data;
				$exp_xml	.='</AvailRateUpdateRQ>';
				//echo $exp_xml;
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$exp_xml");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				//echo $output;
				$data = simplexml_load_string($output); 
				$response = @$data->Error;
				if($response!='')
				{
					$this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$response,'Bnow To Expedia',date('m/d/Y h:i:s a', time()));
				}
			}
			if(@$update_bnow=='yes')
			{
				$ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'17'))->row();
				
				$bnow_xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
									<HotelUpdateRQ xmlns="http://www.opentravel.org/OTA/2013/05"
									TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
									<Authentication UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
									<HotelUpdateRequest HotelCode="'.$ch_details->hotel_channel_id.'" UpdateType="Partial" >
									'.$bnow_xml_data.'
									</HotelUpdateRequest>
									</HotelUpdateRQ>
									';
				//echo $bnow_xml_post_string;					
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
					curl_setopt($ch, CURLOPT_POSTFIELDS, $bnow_xml_post_string);
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
						$this->inventory_model->store_error($owner_id,$hotel_id,'17',$Errors,'Update Availability From Other Channels',date('m/d/Y h:i:s a', time()));
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
			if(@$update_travel=='yes')
			{
				$ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>'15'))->row();
			
				$C_URL = get_data(C_URL,array('channel_id'=>15))->row();
				
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
					<RoomTypes>'.$travel_xml_data.'</RoomTypes>
					<Currency>'.$travel_details->Currency.'</Currency>
					</Establishment>
					</Request>');

					$response 	=	$wcfClient->RequestData($args);
					
					echo htmlspecialchars($response->RequestDataResult);
					
					$data 		=	simplexml_load_string($response->RequestDataResult);

					$Errors 	= 	@$data->attributes()->Error;
					
					if($Errors!='')
					{
						$this->inventory_model->store_error($owner_id,$hotel_id,'15',(string)$Errors,'Update Availability From Other Channels',date('m/d/Y h:i:s a', time()));
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
		}
	}
	
	/*  Update Availability Bnow To Other API End */
}
