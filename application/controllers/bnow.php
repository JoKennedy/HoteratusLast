<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class Bnow extends Front_Controller {

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

	function importRooms($channel_id)
	{
		$bk_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'status'=>'enabled'))->row();
		if($bk_details)
		{
			$bnowData['user_id']	=	current_user_type();
			$bnowData['hotel_id']	=	hotel_id();
			$bnowData['channel_id']	=	insep_decode($channel_id);
			$xml_post_string = '<?xml version="1.0" encoding="utf-8" ?>
			<HotelProductListGetRQ xmlns="http://www.opentravel.org/OTA/2013/05"
			TimeStamp="2013-05-01T06:39:09" Target="Production" Version="1.1">
			<Authentication UserName="'.$bk_details->user_name.'" Password="'.$bk_details->user_password.'" />
			<HotelProductListRequest HotelCode="'.$bk_details->hotel_channel_id.'" />
			</HotelProductListGetRQ>';
			$C_URL = get_data(C_URL,array('channel_id'=>insep_decode($channel_id)))->row();
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
					$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),$Errors,'Get Channel',date('m/d/Y h:i:s a', time()));
					
					return $Errors;
				}
				else
				{
					$HotelCode = $responseArray['HotelProducts'];
					$HotelProduct = $responseArray['HotelProducts']['HotelProduct'];
					if($HotelCode)
					{
						foreach($HotelCode as $key=>$Hotelvalues)
						{
							if($key=='@attributes')
							{
								$bnowData['HotelCode'] = $Hotelvalues['HotelCode'];
								break;
							}
						}
					}
					if($HotelProduct)
					{
						foreach($HotelProduct as $p_key=>$ProductReference)
						{
							foreach($ProductReference as $a_key=>$attributes)
							{
								if($a_key=='ProductReference')
								{
									foreach($attributes as $attributes_value)
									{
										foreach($attributes_value as $r_key=>$attributes_values)
										{
											$bnowData[$r_key] = $attributes_values;
										}
									}
								}
								else
								{
									$bnowData[$a_key] = $attributes;
								}
							}
							$room_available = get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$bnowData['HotelCode'],'InvTypeCode'=>$bnowData['InvTypeCode'],'RatePlanCode'=>$bnowData['RatePlanCode']))->row_array();
							if(count($room_available)!=0)
							{
								update_data(IM_BNOW,$bnowData,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$bnowData['HotelCode'],'InvTypeCode'=>$bnowData['InvTypeCode'],'RatePlanCode'=>$bnowData['RatePlanCode']));
							}
							else
							{
								insert_data(IM_BNOW,$bnowData);
							}
						}
						return 'Insert';
					}
				}
				curl_close($ch);
			}
		}
		else
		{
			return 'Enable';
		}
	}
	
	function mapping_settings($channel_id)
	{
		
		$data['bnow'] 				= 	$this->bnow_model->get_mapping_rooms($channel_id);
		$booking_all 				= 	$this->bnow_model->get_all_mapping_rooms($channel_id);
		$data['channel_details'] 	=	$this->bnow_model->get_all_mapped_rooms($channel_id);
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
		$data['available']		=	get_data(IM_BNOW,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
		$data['mapping_values']	=	get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
		$data['bnow']	=	$this->bnow_model->get_mapping_rooms(insep_decode($channel_id),'update');
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
	
	function importAvailabilities($channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$mappingid = "",$arrival = "",$departure = "",$mapping='',$importRates='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $property_id = insep_decode($propertyid);
        if($mappingid != "")
		{
            $importDetails = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }
		else
		{
            $importDetails = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
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
			if($importDetails['channel_id'] == 17)
			{
				$this->bnow_model->importAvailabilities(current_user_type(),hotel_id(),$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure,$importRates);
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
			if(insep_decode($channel_id)=='17')
			{
				$C_URL = get_data(C_URL,array('channel_id'=>insep_decode($channel_id)))->row();
				
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
					$xml_post_string = '<ReadRQ xmlns="http://www.opentravel.org/OTA/2013/05" TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
					<ReadRequests>
						<Authentication Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
						<HotelReadRequest HotelCode="'.$ch_details->hotel_channel_id.'" />
						<GlobalReservationReadRequest HotelCode="'.$ch_details->hotel_channel_id.'"/>
					</ReadRequests>
					</ReadRQ>';
					$x_r_rq_data['channel_id'] 	= 	'17';						
					$x_r_rq_data['user_id'] 	=	'0';						
					$x_r_rq_data['hotel_id'] 	=	'0';						
					$x_r_rq_data['message'] 	= 	$xml_post_string;						
					$x_r_rq_data['type'] 		= 	'BNOW_REQ';						
					$x_r_rq_data['section'] 	=	'RESER';						
					//insert_data(ALL_XML,$x_r_rq_data);
					
					mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
					
					$ch	=	curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url[0]);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
					$ss 						=	curl_getinfo($ch);
					$response 					= 	curl_exec($ch);
					
					$x_r_rs_data['channel_id']	=	'17';						
					$x_r_rs_data['user_id'] 	=	'0';						
					$x_r_rs_data['hotel_id'] 	=	'0';						
					$x_r_rs_data['message'] 	=	$response;						
					$x_r_rs_data['type'] 		=	'BNOW_RES';						
					$x_r_rs_data['section'] 	=	'RESER';
					
					mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response);
					
					//insert_data(ALL_XML,$x_r_rs_data);
					
					/* $response = '<?xml version="1.0" encoding="utf-8"?>
<OTA_ResRetrieveRS xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2016-10-05T08:21:57" Target="Production" TransactionIdentifier="4ec7e9be-6b34-4a00-9afd-95e9ad5d7d39" Version="1.1">
    <POS xmlns="http://www.opentravel.org/OTA/2003/05">
        <Source>
        <RequestorID Type="13" ID="747" Primary="true" />
        <BookingChannel Type="7">
            <CompanyName Code="BNOW">BookOnlineNow</CompanyName>
        </BookingChannel>
        </Source>
    </POS>
    <Success/>
    <HotelReservations xmlns="http://www.opentravel.org/OTA/2003/05">
        <HotelReservation CreateDateTime="2016-10-05T11:20:56" LastModifyDateTime="2016-10-05T11:20:56" ResStatus="Commit">
            <RoomStays>
                <RoomStay>
                    <RoomTypes>
                        <RoomType RoomTypeCode="ROOM_2600" NumberOfUnits="1" />
                        <RoomDescription Name="Single" />
                    </RoomTypes>
                    <RatePlans>
                        <RatePlan RatePlanCode="RATE_1310">
                            <MealsIncluded />
                        </RatePlan>
                    </RatePlans>
                    <RoomRates>
                        <RoomRate RatePlanCode="RATE_1310" RoomTypeCode="ROOM_2600" NumberOfUnits="1">
                            <Rates>
                                <Rate EffectiveDate="2016-11-05" ExpireDate="2016-11-06" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="110.00" CurrencyCode="EUR" />
                                </Rate>
                            </Rates>
                        </RoomRate>
                    </RoomRates>
                    <Comments>
                        <Comment>
                            <Text> </Text>
                        </Comment>
                    </Comments>
                    <BasicPropertyInfo HotelCode="demo" HotelName="demo" />
                    <GuestCounts>
                        <GuestCount AgeQualifyingCode="10" Count="1" />
                    </GuestCounts>
                    <TimeSpan Start="2016-11-05" End="2016-11-06" Duration="P1D" />
                </RoomStay>
            </RoomStays>
            <ResGuests>
                <ResGuest AgeQualifyingCode="10" PrimaryIndicator="true">
                    <Profiles>
                        <ProfileInfo>
                            <Profile ProfileType="1">
                                <Customer>
                                    <PersonName>
                                        <GivenName>A</GivenName>
                                        <Surname>Subbaiah</Surname>
                                    </PersonName>
                                    <Telephone PhoneTechType="1" PhoneNumber="9786751889" DefaultInd="true" FormattedInd="false" />
                                    <Email EmailType="1" DefaultInd="true">vijikumar.job@gmail.com</Email>
                                    <Address Type="1"><AddressLine></AddressLine><CityName></CityName><PostalCode></PostalCode><CountryName Code=""></CountryName></Address>
                                    <CompanyInfo />
                                </Customer>
                            </Profile>
                        </ProfileInfo>
                    </Profiles>
                </ResGuest>
            </ResGuests>
            <ResGlobalInfo>
                <TimeSpan Start="2016-11-05" End="2016-11-06" Duration="P1D" />
                <Guarantee GuaranteeCode="Non Guaranteed" GuaranteeType="None" />
                <Total AmountAfterTax="110.00" CurrencyCode="EUR" />
                <HotelReservationIDs>
                    <HotelReservationID ResID_Type="14" ResID_Value="RES6351016R788" />
                </HotelReservationIDs>
            </ResGlobalInfo>
        </HotelReservation>
    </HotelReservations>
</OTA_ResRetrieveRS>' ; */
					
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					
					$xml = simplexml_load_string($xml);
					
					$json = json_encode($xml);
					
					$responseArray = json_decode($json,true);

					$Errors = @$responseArray['Errors']['Error'];
					
					if($Errors!='')
					{
						$data['Error']	=	$Errors;
					}
					else	
					{
						$HotelReservations	=	$responseArray['HotelReservations'];

						if(count($HotelReservations)!=0)
						{
							$Reservation = $HotelReservations['HotelReservation'];
							if(isAssoc($Reservation))
							{
								$Reservation	=	array($Reservation);
							}
							else
							{
								$Reservation	=	$Reservation;
							}

							if($Reservation)
							{
								$i=0;
								foreach($Reservation as $Reservation_key=>$Reservation_value)
								{	
									/* Basic Details Start */
									$bnowdata['user_id']			=	current_user_type();
									$bnowdata['hotel_id ']			=	hotel_id();
									$bnowdata['channel_id']			=	'17';
									/* Basic Details End */
									
									/* Room Attributes Start */
									
									$bnowdata['CreateDateTime']		=	$Reservation_value['@attributes']['CreateDateTime'];
									$bnowdata['LastModifyDateTime']	=	$Reservation_value['@attributes']['LastModifyDateTime'];
									$bnowdata['ResStatus']			=	$Reservation_value['@attributes']['ResStatus'];
									
									/* Room Attributes End */
									
									/* Room RoomType Attributes Start */
									
									$bnowdata['RoomTypeCode']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
									$bnowdata['NumberOfUnits']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
								
									/* Room RoomType Attributes End */
									
									/* Room RoomDescription Attributes Start */
									
									$bnowdata['Name']				=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomDescription']['@attributes']['Name'];
									
									/* Room RoomDescription Attributes End */
									
									/* Room RatePlanCode Attributes Start */
									
									$bnowdata['RatePlanCode']		=	$Reservation_value['RoomStays']['RoomStay']['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
									
									/* Room RatePlanCode Attributes End */
									
									/* Room RoomRates Attributes Start */
									
									$RoomRates	=	@$Reservation_value['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate'];
									if($RoomRates)
									{
										if(isAssoc($RoomRates))
										{
											$RoomRates	=	array($RoomRates);
										}
										else
										{
											$RoomRates	=	$RoomRates;
										}
										$price='';
										foreach($RoomRates as $RoomRatesKey=>$RoomRatesArray)
										{
											$EffectiveDate	=	$RoomRatesArray['@attributes']['EffectiveDate'];
											$ExpireDate		=	$RoomRatesArray['@attributes']['ExpireDate'];
											$UnitMultiplier	=	$RoomRatesArray['@attributes']['UnitMultiplier'];
											$RateTimeUnit	=	$RoomRatesArray['@attributes']['RateTimeUnit'];
											
											$AmountAfterTax	=	$RoomRatesArray['Base']['@attributes']['AmountAfterTax'];
											$CurrencyCode	=	$RoomRatesArray['Base']['@attributes']['CurrencyCode'];
											
											$price	.=	$EffectiveDate.'~'.$ExpireDate.'#'.$UnitMultiplier.'|'.$RateTimeUnit.'/'.$AmountAfterTax.'~'.$CurrencyCode.'###';
										}
										$bnowdata['Price']	=	trim($price,'###');
									}
									
									/* Room RoomRates Attributes End */
									
									/* Room Comments Attributes Start */
									if(!is_array($Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text']))
									{
										$bnowdata['Text']		=	$Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text'];
									}
									else
									{
										$bnowdata['Text']		=	'';
									}
									/* Room Comments Attributes End */
									
									/* Room BasicPropertyInfo Attributes Start */
										
										$bnowdata['HotelCode']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelCode'];
										
										$bnowdata['HotelName']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelName'];
										
									/* Room BasicPropertyInfo Attributes End */
									
									/* Room GuestCounts Attributes Start */
									
										$GuestCounts	=	$Reservation_value['RoomStays']['RoomStay']['GuestCounts']['GuestCount'];
										if(isAssoc($GuestCounts))
										{
											$GuestCounts	=	array($GuestCounts);
										}
										else
										{
											$GuestCounts	=	$GuestCounts;
										}
										if($GuestCounts)
										{
											$Guest='';
											$i=1;
											foreach($GuestCounts as $GuestCountsKey=>$GuestCountsArray)
											{
												$AgeQualifyingCode	=	$GuestCountsArray['@attributes']['AgeQualifyingCode'];
												$Count				=	$GuestCountsArray['@attributes']['Count'];
												$Guest	.=	$AgeQualifyingCode.'#'.$Count.'##';
												if($i==1)
												{
													$bnowdata['Adult']	=	$Count;
												}
												$i++;
											}
											$bnowdata['Guest']	=	trim($Guest,'##');
										}
									/* Room GuestCounts Attributes End */
									
									/* Room TimeSpan Attributes Start */
										$bnowdata['Start']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Start'];
										$bnowdata['End']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['End'];
										$bnowdata['Duration']	=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Duration'];
									/* Room TimeSpan Attributes End */
									
									/* Room ResGuests Attributes Start */
									
										$bnowdata['AgeQualifyingCode']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['AgeQualifyingCode'];
										
										$bnowdata['PrimaryIndicator']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['PrimaryIndicator'];
										
										$bnowdata['ProfileType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['@attributes']['ProfileType'];
										
										$bnowdata['GivenName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
										
										$bnowdata['Surname']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
										
										$bnowdata['PersonName']	=	$bnowdata['GivenName'].' '.$bnowdata['Surname'];
										
										$bnowdata['PhoneTechType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneTechType'];
										
										$bnowdata['PhoneNumber']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneNumber'];
										
										$bnowdata['DefaultInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['DefaultInd'];
										
										$bnowdata['FormattedInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['FormattedInd'];
										
										$bnowdata['Email']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Email'];
										
										$bnowdata['Address_Type']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['@attributes']['Type'];
										
										if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine']))
										{
										
											$bnowdata['AddressLine']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine'];
										}
										else
										{
											$bnowdata['AddressLine']		=	'';
										}
										if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName']))
										{
											$bnowdata['CityName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName'];
										}
										else
										{
											$bnowdata['CityName']		=	'';
										}
										if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode']))
										{
											$bnowdata['PostalCode']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode'];
										}
										else
										{
											$bnowdata['PostalCode']		=	'';
										}
										if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName']))
										{
											$bnowdata['CountryName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName'];
										}
										else
										{
											$bnowdata['CountryName']		=	'';
										}
									/* Room ResGuests Attributes End */
									
									/* Room ResGlobalInfo Attributes End */
									
										$bnowdata['GuaranteeCode']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeCode'];
										
										$bnowdata['GuaranteeType']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeType'];
										
										$GuaranteesAccepted			=	@$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['@attributes'];
										if($GuaranteesAccepted)
										{
											$bnowdata['CardType']		=	$GuaranteesAccepted['CardType'];
											$bnowdata['CardCode']		=	$GuaranteesAccepted['CardCode'];
											$bnowdata['CardNumber']		=	$GuaranteesAccepted['CardNumber'];
											$bnowdata['ExpireDate']		=	$GuaranteesAccepted['ExpireDate'];
											$bnowdata['CardHolderName']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['CardHolderName'];
										}
										
										$bnowdata['AmountAfterTax']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['AmountAfterTax'];
										
										$bnowdata['CurrencyCode']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];
										
										$bnowdata['ResID_Type']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Type'];
										
										$bnowdata['ResID_Value']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Value'];
										
									/* Room ResGlobalInfo Attributes End */
									/* echo '<pre>';
									print_r($bnowdata); */
									
									$array_keys = array_keys($bnowdata);
									
									fetchColumn(BNOW_RESER,$array_keys);
									
									$available = get_data(BNOW_RESER,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']))->row_array();
									
									if(count($available)==0)
									{		
										insert_data(BNOW_RESER,$bnowdata);
									}
									else
									{
										update_data(BNOW_RESER,$bnowdata,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']));
									}
									$checkin=date('Y/m/d',strtotime($bnowdata['Start']));
									$checkout=date('Y/m/d',strtotime($bnowdata['End']));
									$nig =_datebetween($checkin,$checkout);
									$avdata['user_id']				=	current_user_type();
									$avdata['hotel_id']				=	hotel_id();
									$avdata['channel_id']			=	'17';
									$avdata['channel_hotel_id']		=	$bnowdata['HotelCode'];
									$avdata['reservation_id']		=	$bnowdata['ResID_Value'];
									$avdata['start']				=	$bnowdata['Start'];
									$avdata['end']					=	$bnowdata['End'];
									$avdata['relation_one']			=	$bnowdata['RoomTypeCode'];
									$avdata['relation_two']			=	$bnowdata['RatePlanCode'];
									$avdata['difference']			=	$nig;
									$avdata['reservation_status']	=	$bnowdata['ResStatus'];
									insert_data(UAVL,$avdata);
								}

								$this->updateAvailability($bnowdata['HotelCode'],$source='Manual');
							}
						}
						$data['succes']='Insert';
					}
				}
				else
				{
					$data['Enable'] = 'Enable';
				}
			}
			else
			{
				$data['Enable'] = 'Enable';
			}
		}
		else
		{
			$data['Enable'] = 'Enable';
		}
		return $data;
	}
	
	function getReservationCron($channel_id)
	{
		if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) 
		{ 
			
		}
		else
		{
			if(insep_decode($channel_id)=='17')
			{
				$C_URL = get_data(C_URL,array('channel_id'=>insep_decode($channel_id)))->row();
				
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
					
					$bnowUserDetails = get_data(CONNECT,array('channel_id'=>17,'status'=>'enabled'),'user_name,user_password,hotel_channel_id')->result();
					/* echo '<pre>';
					print_r($bnowUserDetails); die; */
					
					if($bnowUserDetails)
					{
						foreach($bnowUserDetails as $ch_details)
						{
							//$ch_details	=	get_data(B_WAY,array('id'=>7,'channel_id'=>17))->row();
							
							$xml_post_string = '<ReadRQ xmlns="http://www.opentravel.org/OTA/2013/05" TimeStamp="'.date('Y-m-d\TH:i:s').'" Target="Production" Version="1.1">
							<ReadRequests>
								<Authentication Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
								<HotelReadRequest HotelCode="'.$ch_details->hotel_channel_id.'" />
								<GlobalReservationReadRequest HotelCode="'.$ch_details->hotel_channel_id.'"/>
							</ReadRequests>
							</ReadRQ>';
							$x_r_rq_data['channel_id'] 	= 	'17';						
							$x_r_rq_data['user_id'] 	=	'0';						
							$x_r_rq_data['hotel_id'] 	=	'0';						
							$x_r_rq_data['message'] 	= 	$xml_post_string;						
							$x_r_rq_data['type'] 		= 	'BNOW_REQ';						
							$x_r_rq_data['section'] 	=	'RESER';						
							//insert_data(ALL_XML,$x_r_rq_data);
							//mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
							
							$ch	=	curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_URL, $url[0]);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
							curl_setopt($ch, CURLOPT_TIMEOUT, 500);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
							curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
							$ss 						=	curl_getinfo($ch);
							$response 					= 	curl_exec($ch);
							
							$x_r_rs_data['channel_id']	=	'17';						
							$x_r_rs_data['user_id'] 	=	'0';						
							$x_r_rs_data['hotel_id'] 	=	'0';						
							$x_r_rs_data['message'] 	=	$response;						
							$x_r_rs_data['type'] 		=	'BNOW_RES';						
							$x_r_rs_data['section'] 	=	'RESER';
							//insert_data(ALL_XML,$x_r_rs_data);
							//mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response);
							
							/* $response = '<?xml version="1.0" encoding="utf-8"?>
			<OTA_ResRetrieveRS xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2016-10-06T05:50:02" Target="Production" TransactionIdentifier="88efc7eb-775f-4a58-be42-9f2846ad0bcb" Version="1.1">
				<POS xmlns="http://www.opentravel.org/OTA/2003/05">
					<Source>
					<RequestorID Type="13" ID="747" Primary="true" />
					<BookingChannel Type="7">
						<CompanyName Code="BNOW">BookOnlineNow</CompanyName>
					</BookingChannel>
					</Source>
				</POS>
				<Success/>
				<HotelReservations xmlns="http://www.opentravel.org/OTA/2003/05">
					<HotelReservation CreateDateTime="2016-10-06T08:49:10" LastModifyDateTime="2016-10-06T08:49:10" ResStatus="Commit">
						<RoomStays>
							<RoomStay>
								<RoomTypes>
									<RoomType RoomTypeCode="ROOM_2600" NumberOfUnits="1" />
									<RoomDescription Name="Single" />
								</RoomTypes>
								<RatePlans>
									<RatePlan RatePlanCode="RATE_1310">
										<MealsIncluded />
									</RatePlan>
								</RatePlans>
								<RoomRates>
									<RoomRate RatePlanCode="RATE_1310" RoomTypeCode="ROOM_2600" NumberOfUnits="1">
										<Rates>
											<Rate EffectiveDate="2016-10-08" ExpireDate="2016-10-09" UnitMultiplier="1" RateTimeUnit="Day">
												<Base AmountAfterTax="100.00" CurrencyCode="EUR" />
											</Rate>
											<Rate EffectiveDate="2016-10-09" ExpireDate="2016-10-10" UnitMultiplier="1" RateTimeUnit="Day">
												<Base AmountAfterTax="100.00" CurrencyCode="EUR" />
											</Rate>
											<Rate EffectiveDate="2016-10-10" ExpireDate="2016-10-11" UnitMultiplier="1" RateTimeUnit="Day">
												<Base AmountAfterTax="100.00" CurrencyCode="EUR" />
											</Rate>
											<Rate EffectiveDate="2016-10-11" ExpireDate="2016-10-12" UnitMultiplier="1" RateTimeUnit="Day">
												<Base AmountAfterTax="100.00" CurrencyCode="EUR" />
											</Rate>
										</Rates>
									</RoomRate>
								</RoomRates>
								<Comments>
									<Comment>
										<Text> </Text>
									</Comment>
								</Comments>
								<BasicPropertyInfo HotelCode="demo" HotelName="demo" />
								<GuestCounts>
									<GuestCount AgeQualifyingCode="10" Count="1" />
								</GuestCounts>
								<TimeSpan Start="2016-10-08" End="2016-10-12" Duration="P4D" />
							</RoomStay>
						</RoomStays>
						<ResGuests>
							<ResGuest AgeQualifyingCode="10" PrimaryIndicator="true">
								<Profiles>
									<ProfileInfo>
										<Profile ProfileType="1">
											<Customer>
												<PersonName>
													<GivenName>C</GivenName>
													<Surname>Cron Reservation</Surname>
												</PersonName>
												<Telephone PhoneTechType="1" PhoneNumber="9786751889" DefaultInd="true" FormattedInd="false" />
												<Email EmailType="1" DefaultInd="true">vijikumar.job@gmail.com</Email>
												<Address Type="1"><AddressLine></AddressLine><CityName></CityName><PostalCode></PostalCode><CountryName Code=""></CountryName></Address>
												<CompanyInfo />
											</Customer>
										</Profile>
									</ProfileInfo>
								</Profiles>
							</ResGuest>
						</ResGuests>
						<ResGlobalInfo>
							<TimeSpan Start="2016-10-08" End="2016-10-12" Duration="P4D" />
							<Guarantee GuaranteeCode="Non Guaranteed" GuaranteeType="None" />
							<Total AmountAfterTax="400.00" CurrencyCode="EUR" />
							<HotelReservationIDs>
								<HotelReservationID ResID_Type="14" ResID_Value="RES6461016R788" />
							</HotelReservationIDs>
						</ResGlobalInfo>
					</HotelReservation>
				</HotelReservations>
			</OTA_ResRetrieveRS>' ; */
							
							$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
							
							$xml = simplexml_load_string($xml);
							
							$json = json_encode($xml);
							
							$responseArray = json_decode($json,true);

							$Errors = @$responseArray['Errors']['Error'];
							
							if($Errors!='')
							{
								mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
								mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response);
								$meg['result'] = '0';
								$meg['content']= $Errors.' from BookOnlineNow Try again!';
								echo json_encode($meg);
							}
							else	
							{
								$HotelReservations	=	$responseArray['HotelReservations'];

								if(count($HotelReservations)!=0)
								{
									mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
									mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response);
									
									$Reservation = $HotelReservations['HotelReservation'];
									if(isAssoc($Reservation))
									{
										$Reservation	=	array($Reservation);
									}
									else
									{
										$Reservation	=	$Reservation;
									}

									if($Reservation)
									{
										$i=0;
										foreach($Reservation as $Reservation_key=>$Reservation_value)
										{	
											/* Basic Details Start */
											$bnowdata['channel_id']			=	'17';
											/* Basic Details End */
											
											/* Room Attributes Start */
											
											$bnowdata['CreateDateTime']		=	$Reservation_value['@attributes']['CreateDateTime'];
											$bnowdata['LastModifyDateTime']	=	$Reservation_value['@attributes']['LastModifyDateTime'];
											$bnowdata['ResStatus']			=	$Reservation_value['@attributes']['ResStatus'];
											
											/* Room Attributes End */
											
											/* Room RoomType Attributes Start */
											
											$bnowdata['RoomTypeCode']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
											$bnowdata['NumberOfUnits']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
										
											/* Room RoomType Attributes End */
											
											/* Room RoomDescription Attributes Start */
											
											$bnowdata['Name']				=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomDescription']['@attributes']['Name'];
											
											/* Room RoomDescription Attributes End */
											
											/* Room RatePlanCode Attributes Start */
											
											$bnowdata['RatePlanCode']		=	$Reservation_value['RoomStays']['RoomStay']['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
											
											/* Room RatePlanCode Attributes End */
											
											/* Room RoomRates Attributes Start */
											
											$RoomRates	=	@$Reservation_value['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate'];
											if($RoomRates)
											{
												if(isAssoc($RoomRates))
												{
													$RoomRates	=	array($RoomRates);
												}
												else
												{
													$RoomRates	=	$RoomRates;
												}
												$price='';
												foreach($RoomRates as $RoomRatesKey=>$RoomRatesArray)
												{
													$EffectiveDate	=	$RoomRatesArray['@attributes']['EffectiveDate'];
													$ExpireDate		=	$RoomRatesArray['@attributes']['ExpireDate'];
													$UnitMultiplier	=	$RoomRatesArray['@attributes']['UnitMultiplier'];
													$RateTimeUnit	=	$RoomRatesArray['@attributes']['RateTimeUnit'];
													
													$AmountAfterTax	=	$RoomRatesArray['Base']['@attributes']['AmountAfterTax'];
													$CurrencyCode	=	$RoomRatesArray['Base']['@attributes']['CurrencyCode'];
													
													$price	.=	$EffectiveDate.'~'.$ExpireDate.'#'.$UnitMultiplier.'|'.$RateTimeUnit.'/'.$AmountAfterTax.'~'.$CurrencyCode.'###';
												}
												$bnowdata['Price']	=	trim($price,'###');
											}
											
											/* Room RoomRates Attributes End */
											
											/* Room Comments Attributes Start */
											if(!is_array($Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text']))
											{
												$bnowdata['Text']		=	$Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text'];
											}
											else
											{
												$bnowdata['Text']		=	'';
											}
											/* Room Comments Attributes End */
											
											/* Room BasicPropertyInfo Attributes Start */
												
												$bnowdata['HotelCode']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelCode'];
												
												$userDetails	=	get_data(CONNECT,array('channel_id'=>17,'hotel_channel_id'=>$bnowdata['HotelCode'],'status'=>'enabled'),'user_id,hotel_id')->row_array();
												
												if($userDetails)
												{
													$bnowdata['user_id']			=	$userDetails['user_id'];
													$bnowdata['hotel_id']			=	$userDetails['hotel_id'];
												}
												else
												{
													$bnowdata['user_id']			=	'0';
													$bnowdata['hotel_id']			=	'0';
												}
												
												$bnowdata['HotelName']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelName'];
												
											/* Room BasicPropertyInfo Attributes End */
											
											/* Room GuestCounts Attributes Start */
											
												$GuestCounts	=	$Reservation_value['RoomStays']['RoomStay']['GuestCounts']['GuestCount'];
												if(isAssoc($GuestCounts))
												{
													$GuestCounts	=	array($GuestCounts);
												}
												else
												{
													$GuestCounts	=	$GuestCounts;
												}
												if($GuestCounts)
												{
													$Guest='';
													$i=1;
													foreach($GuestCounts as $GuestCountsKey=>$GuestCountsArray)
													{
														$AgeQualifyingCode	=	$GuestCountsArray['@attributes']['AgeQualifyingCode'];
														$Count				=	$GuestCountsArray['@attributes']['Count'];
														$Guest	.=	$AgeQualifyingCode.'#'.$Count.'##';
														if($i==1)
														{
															$bnowdata['Adult']	=	$Count;
														}
														$i++;
													}
													$bnowdata['Guest']	=	trim($Guest,'##');
												}
											/* Room GuestCounts Attributes End */
											
											/* Room TimeSpan Attributes Start */
												$bnowdata['Start']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Start'];
												$bnowdata['End']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['End'];
												$bnowdata['Duration']	=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Duration'];
											/* Room TimeSpan Attributes End */
											
											/* Room ResGuests Attributes Start */
											
												$bnowdata['AgeQualifyingCode']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['AgeQualifyingCode'];
												
												$bnowdata['PrimaryIndicator']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['PrimaryIndicator'];
												
												$bnowdata['ProfileType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['@attributes']['ProfileType'];
												
												$bnowdata['GivenName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
												
												$bnowdata['Surname']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
												
												$bnowdata['PersonName']	=	$bnowdata['GivenName'].' '.$bnowdata['Surname'];
												
												$bnowdata['PhoneTechType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneTechType'];
												
												$bnowdata['PhoneNumber']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneNumber'];
												
												$bnowdata['DefaultInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['DefaultInd'];
												
												$bnowdata['FormattedInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['FormattedInd'];
												
												$bnowdata['Email']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Email'];
												
												$bnowdata['Address_Type']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['@attributes']['Type'];
												
												if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine']))
												{
												
													$bnowdata['AddressLine']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine'];
												}
												else
												{
													$bnowdata['AddressLine']		=	'';
												}
												if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName']))
												{
													$bnowdata['CityName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName'];
												}
												else
												{
													$bnowdata['CityName']		=	'';
												}
												if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode']))
												{
													$bnowdata['PostalCode']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode'];
												}
												else
												{
													$bnowdata['PostalCode']		=	'';
												}
												if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName']))
												{
													$bnowdata['CountryName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName'];
												}
												else
												{
													$bnowdata['CountryName']		=	'';
												}
											/* Room ResGuests Attributes End */
											
											/* Room ResGlobalInfo Attributes End */
											
												$bnowdata['GuaranteeCode']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeCode'];
												
												$bnowdata['GuaranteeType']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeType'];
												
												$GuaranteesAccepted			=	@$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['@attributes'];
												if($GuaranteesAccepted)
												{
													$bnowdata['CardType']		=	$GuaranteesAccepted['CardType'];
													$bnowdata['CardCode']		=	$GuaranteesAccepted['CardCode'];
													$bnowdata['CardNumber']		=	$GuaranteesAccepted['CardNumber'];
													$bnowdata['ExpireDate']		=	$GuaranteesAccepted['ExpireDate'];
													$bnowdata['CardHolderName']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['CardHolderName'];
												}
												
												$bnowdata['AmountAfterTax']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['AmountAfterTax'];
												
												$bnowdata['CurrencyCode']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];
												
												$bnowdata['ResID_Type']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Type'];
												
												$bnowdata['ResID_Value']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Value'];
												
											/* Room ResGlobalInfo Attributes End */
											/* echo '<pre>';
											print_r($bnowdata); */
											
											$array_keys = array_keys($bnowdata);
											
											fetchColumn(BNOW_RESER,$array_keys);
											
											$available = get_data(BNOW_RESER,array('user_id'=>$bnowdata['user_id'],'hotel_id'=>$bnowdata['hotel_id'],'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']))->row_array();
											
											if(count($available)==0)
											{		
												insert_data(BNOW_RESER,$bnowdata);
											}
											else
											{
												update_data(BNOW_RESER,$bnowdata,array('user_id'=>$bnowdata['user_id'],'hotel_id'=>$bnowdata['hotel_id'],'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']));
											}
											$checkin=date('Y/m/d',strtotime($bnowdata['Start']));
											$checkout=date('Y/m/d',strtotime($bnowdata['End']));
											$nig =_datebetween($checkin,$checkout);
											$avdata['user_id']				=	$bnowdata['user_id'];
											$avdata['hotel_id']				=	$bnowdata['hotel_id'];
											$avdata['channel_id']			=	'17';
											$avdata['channel_hotel_id']		=	$bnowdata['HotelCode'];
											$avdata['reservation_id']		=	$bnowdata['ResID_Value'];
											$avdata['start']				=	$bnowdata['Start'];
											$avdata['end']					=	$bnowdata['End'];
											$avdata['relation_one']			=	$bnowdata['RoomTypeCode'];
											$avdata['relation_two']			=	$bnowdata['RatePlanCode'];
											$avdata['difference']			=	$nig;
											$avdata['reservation_status']	=	$bnowdata['ResStatus'];
											insert_data(UAVL,$avdata);
										}

										$this->updateAvailability($bnowdata['HotelCode'],$source='Cron');
									}
								}
								$meg['result'] = '1';
								$meg['content']='Successfully import reservation from BookOnlineNow!!!';
								echo json_encode($meg);
							}
						}
					}
				}
				else
				{
					$meg['result'] = '0';
					$meg['content']= $Errors.' from BookOnlineNow Try again!';
					echo json_encode($meg);
				}
			}
			else
			{
				$meg['result'] = '0';
				$meg['content']= $Errors.' from BookOnlineNow Try again!';
				echo json_encode($meg);
			}
		}
	}
	
	function getMissReservationCron()
	{
		$response = '<?xml version="1.0" encoding="utf-8"?>
<OTA_ResRetrieveRS xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2016-11-14T20:47:29" Target="Production" TransactionIdentifier="44c9cd41-3b2a-4f56-8e44-d60c58dd2378" Version="1.1">
    <POS xmlns="http://www.opentravel.org/OTA/2003/05">
        <Source>
        <RequestorID Type="13" ID="747" Primary="true" />
        <BookingChannel Type="7">
            <CompanyName Code="BNOW">BookOnlineNow</CompanyName>
        </BookingChannel>
        </Source>
    </POS>
    <Success/>
    <HotelReservations xmlns="http://www.opentravel.org/OTA/2003/05">
        <HotelReservation CreateDateTime="2016-11-14T22:44:18" LastModifyDateTime="2016-11-14T22:44:18" ResStatus="Commit">
            <RoomStays>
                <RoomStay>
                    <RoomTypes>
                        <RoomType RoomTypeCode="ROOM_6176" NumberOfUnits="1" />
                        <RoomDescription Name="STUDIOS" />
                    </RoomTypes>
                    <RatePlans>
                        <RatePlan RatePlanCode="RATE_7353">
                            <MealsIncluded />
                        </RatePlan>
                    </RatePlans>
                    <RoomRates>
                        <RoomRate RatePlanCode="RATE_7353" RoomTypeCode="ROOM_6176" NumberOfUnits="1">
                            <Rates>
                                <Rate EffectiveDate="2017-06-24" ExpireDate="2017-06-25" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-25" ExpireDate="2017-06-26" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-26" ExpireDate="2017-06-27" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-27" ExpireDate="2017-06-28" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-28" ExpireDate="2017-06-29" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-29" ExpireDate="2017-06-30" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-06-30" ExpireDate="2017-07-01" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="62.47" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-01" ExpireDate="2017-07-02" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-02" ExpireDate="2017-07-03" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-03" ExpireDate="2017-07-04" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-04" ExpireDate="2017-07-05" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-05" ExpireDate="2017-07-06" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-06" ExpireDate="2017-07-07" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                                <Rate EffectiveDate="2017-07-07" ExpireDate="2017-07-08" UnitMultiplier="1" RateTimeUnit="Day">
                                    <Base AmountAfterTax="74.37" CurrencyCode="EUR" />
                                </Rate>
                            </Rates>
                        </RoomRate>
                    </RoomRates>
                    <Comments>
                        <Comment>
                            <Text>How do I book a taxi from the airport to the hotel?</Text>
                        </Comment>
                    </Comments>
                    <BasicPropertyInfo HotelCode="foliahotel" HotelName="foliahotel" />
                    <GuestCounts>
                        <GuestCount AgeQualifyingCode="10" Count="2" />
                    </GuestCounts>
                    <TimeSpan Start="2017-06-24" End="2017-07-08" Duration="P14D" />
                </RoomStay>
            </RoomStays>
            <ResGuests>
                <ResGuest AgeQualifyingCode="10" PrimaryIndicator="true">
                    <Profiles>
                        <ProfileInfo>
                            <Profile ProfileType="1">
                                <Customer>
                                    <PersonName>
                                        <GivenName>Paul</GivenName>
                                        <Surname>Dawes</Surname>
                                    </PersonName>
                                    <Telephone PhoneTechType="1" PhoneNumber="07850726084" DefaultInd="true" FormattedInd="false" />
                                    <Email EmailType="1" DefaultInd="true">dawes409@btinternet.com</Email>
                                    <Address Type="1"><AddressLine>9 Buren Ave</AddressLine><CityName>Canvey Island</CityName><PostalCode>SS8 8NN</PostalCode><CountryName Code="GB">United Kingdom</CountryName></Address>
                                    <CompanyInfo />
                                </Customer>
                            </Profile>
                        </ProfileInfo>
                    </Profiles>
                </ResGuest>
            </ResGuests>
            <ResGlobalInfo>
                <TimeSpan Start="2017-06-24" End="2017-07-08" Duration="P14D" />
                <Guarantee GuaranteeCode="Card Type" GuaranteeType="CC/DC/Voucher">
                    <GuaranteesAccepted>
                        <GuaranteeAccepted>
                            <PaymentCard CardType="" CardCode="" CardNumber="" ExpireDate="">
                                <CardHolderName>NOT AVAILABLE</CardHolderName>
                            </PaymentCard>
                        </GuaranteeAccepted>
                    </GuaranteesAccepted>
                </Guarantee>
                <Total AmountAfterTax="957.88" CurrencyCode="EUR" />
                <HotelReservationIDs>
                    <HotelReservationID ResID_Type="14" ResID_Value="RES2141116R1630" />
                </HotelReservationIDs>
            </ResGlobalInfo>
        </HotelReservation>
    </HotelReservations>
</OTA_ResRetrieveRS>' ;
			
		$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
		
		$xml = simplexml_load_string($xml);
		
		$json = json_encode($xml);
		
		$responseArray = json_decode($json,true);

		$Errors = @$responseArray['Errors']['Error'];
		
		if($Errors!='')
		{
			/* mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
			mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response);
			$meg['result'] = '0';
			$meg['content']= $Errors.' from BookOnlineNow Try again!';
			echo json_encode($meg); */
		}
		else	
		{
			$HotelReservations	=	$responseArray['HotelReservations'];

			if(count($HotelReservations)!=0)
			{
				/* mail("xml@hoteratus.com"," Reservation Request Form BNOW ",$xml_post_string);
				mail("xml@hoteratus.com"," Reservation Response Form BNOW ",$response); */
				
				$Reservation = $HotelReservations['HotelReservation'];
				if(isAssoc($Reservation))
				{
					$Reservation	=	array($Reservation);
				}
				else
				{
					$Reservation	=	$Reservation;
				}

				if($Reservation)
				{
					$i=0;
					foreach($Reservation as $Reservation_key=>$Reservation_value)
					{	
						/* Basic Details Start */
						$bnowdata['channel_id']			=	'17';
						/* Basic Details End */
						
						/* Room Attributes Start */
						
						$bnowdata['CreateDateTime']		=	$Reservation_value['@attributes']['CreateDateTime'];
						$bnowdata['LastModifyDateTime']	=	$Reservation_value['@attributes']['LastModifyDateTime'];
						$bnowdata['ResStatus']			=	$Reservation_value['@attributes']['ResStatus'];
						
						/* Room Attributes End */
						
						/* Room RoomType Attributes Start */
						
						$bnowdata['RoomTypeCode']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];
						$bnowdata['NumberOfUnits']		=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomType']['@attributes']['NumberOfUnits'];
					
						/* Room RoomType Attributes End */
						
						/* Room RoomDescription Attributes Start */
						
						$bnowdata['Name']				=	$Reservation_value['RoomStays']['RoomStay']['RoomTypes']['RoomDescription']['@attributes']['Name'];
						
						/* Room RoomDescription Attributes End */
						
						/* Room RatePlanCode Attributes Start */
						
						$bnowdata['RatePlanCode']		=	$Reservation_value['RoomStays']['RoomStay']['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
						
						/* Room RatePlanCode Attributes End */
						
						/* Room RoomRates Attributes Start */
						
						$RoomRates	=	@$Reservation_value['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate'];
						if($RoomRates)
						{
							if(isAssoc($RoomRates))
							{
								$RoomRates	=	array($RoomRates);
							}
							else
							{
								$RoomRates	=	$RoomRates;
							}
							$price='';
							foreach($RoomRates as $RoomRatesKey=>$RoomRatesArray)
							{
								$EffectiveDate	=	$RoomRatesArray['@attributes']['EffectiveDate'];
								$ExpireDate		=	$RoomRatesArray['@attributes']['ExpireDate'];
								$UnitMultiplier	=	$RoomRatesArray['@attributes']['UnitMultiplier'];
								$RateTimeUnit	=	$RoomRatesArray['@attributes']['RateTimeUnit'];
								
								$AmountAfterTax	=	$RoomRatesArray['Base']['@attributes']['AmountAfterTax'];
								$CurrencyCode	=	$RoomRatesArray['Base']['@attributes']['CurrencyCode'];
								
								$price	.=	$EffectiveDate.'~'.$ExpireDate.'#'.$UnitMultiplier.'|'.$RateTimeUnit.'/'.$AmountAfterTax.'~'.$CurrencyCode.'###';
							}
							$bnowdata['Price']	=	trim($price,'###');
						}
						
						/* Room RoomRates Attributes End */
						
						/* Room Comments Attributes Start */
						if(!is_array($Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text']))
						{
							$bnowdata['Text']		=	$Reservation_value['RoomStays']['RoomStay']['Comments']['Comment']['Text'];
						}
						else
						{
							$bnowdata['Text']		=	'';
						}
						/* Room Comments Attributes End */
						
						/* Room BasicPropertyInfo Attributes Start */
							
							$bnowdata['HotelCode']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelCode'];
							
							$userDetails	=	get_data(CONNECT,array('channel_id'=>17,'hotel_channel_id'=>$bnowdata['HotelCode'],'status'=>'enabled'),'user_id,hotel_id')->row_array();
							
							if($userDetails)
							{
								$bnowdata['user_id']			=	$userDetails['user_id'];
								$bnowdata['hotel_id']			=	$userDetails['hotel_id'];
							}
							else
							{
								$bnowdata['user_id']			=	'0';
								$bnowdata['hotel_id']			=	'0';
							}
							
							$bnowdata['HotelName']		=	$Reservation_value['RoomStays']['RoomStay']['BasicPropertyInfo']['@attributes']['HotelName'];
							
						/* Room BasicPropertyInfo Attributes End */
						
						/* Room GuestCounts Attributes Start */
						
							$GuestCounts	=	$Reservation_value['RoomStays']['RoomStay']['GuestCounts']['GuestCount'];
							if(isAssoc($GuestCounts))
							{
								$GuestCounts	=	array($GuestCounts);
							}
							else
							{
								$GuestCounts	=	$GuestCounts;
							}
							if($GuestCounts)
							{
								$Guest='';
								$i=1;
								foreach($GuestCounts as $GuestCountsKey=>$GuestCountsArray)
								{
									$AgeQualifyingCode	=	$GuestCountsArray['@attributes']['AgeQualifyingCode'];
									$Count				=	$GuestCountsArray['@attributes']['Count'];
									$Guest	.=	$AgeQualifyingCode.'#'.$Count.'##';
									if($i==1)
									{
										$bnowdata['Adult']	=	$Count;
									}
									$i++;
								}
								$bnowdata['Guest']	=	trim($Guest,'##');
							}
						/* Room GuestCounts Attributes End */
						
						/* Room TimeSpan Attributes Start */
							$bnowdata['Start']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Start'];
							$bnowdata['End']		=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['End'];
							$bnowdata['Duration']	=	$Reservation_value['RoomStays']['RoomStay']['TimeSpan']['@attributes']['Duration'];
						/* Room TimeSpan Attributes End */
						
						/* Room ResGuests Attributes Start */
						
							$bnowdata['AgeQualifyingCode']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['AgeQualifyingCode'];
							
							$bnowdata['PrimaryIndicator']	=	$Reservation_value['ResGuests']['ResGuest']['@attributes']['PrimaryIndicator'];
							
							$bnowdata['ProfileType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['@attributes']['ProfileType'];
							
							$bnowdata['GivenName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
							
							$bnowdata['Surname']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];
							
							$bnowdata['PersonName']	=	$bnowdata['GivenName'].' '.$bnowdata['Surname'];
							
							$bnowdata['PhoneTechType']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneTechType'];
							
							$bnowdata['PhoneNumber']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneNumber'];
							
							$bnowdata['DefaultInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['DefaultInd'];
							
							$bnowdata['FormattedInd']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['FormattedInd'];
							
							$bnowdata['Email']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Email'];
							
							$bnowdata['Address_Type']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['@attributes']['Type'];
							
							if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine']))
							{
							
								$bnowdata['AddressLine']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['AddressLine'];
							}
							else
							{
								$bnowdata['AddressLine']		=	'';
							}
							if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName']))
							{
								$bnowdata['CityName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CityName'];
							}
							else
							{
								$bnowdata['CityName']		=	'';
							}
							if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode']))
							{
								$bnowdata['PostalCode']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['PostalCode'];
							}
							else
							{
								$bnowdata['PostalCode']		=	'';
							}
							if(!is_array($Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName']))
							{
								$bnowdata['CountryName']	=	$Reservation_value['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Address']['CountryName'];
							}
							else
							{
								$bnowdata['CountryName']		=	'';
							}
						/* Room ResGuests Attributes End */
						
						/* Room ResGlobalInfo Attributes End */
						
							$bnowdata['GuaranteeCode']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeCode'];
							
							$bnowdata['GuaranteeType']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['@attributes']['GuaranteeType'];
							
							$GuaranteesAccepted			=	@$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['@attributes'];
							if($GuaranteesAccepted)
							{
								$bnowdata['CardType']		=	$GuaranteesAccepted['CardType'];
								$bnowdata['CardCode']		=	$GuaranteesAccepted['CardCode'];
								$bnowdata['CardNumber']		=	$GuaranteesAccepted['CardNumber'];
								$bnowdata['ExpireDate']		=	$GuaranteesAccepted['ExpireDate'];
								$bnowdata['CardHolderName']	=	$Reservation_value['ResGlobalInfo']['Guarantee']['GuaranteesAccepted']['GuaranteeAccepted']['PaymentCard']['CardHolderName'];
							}
							
							$bnowdata['AmountAfterTax']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['AmountAfterTax'];
							
							$bnowdata['CurrencyCode']	=	$Reservation_value['ResGlobalInfo']['Total']['@attributes']['CurrencyCode'];
							
							$bnowdata['ResID_Type']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Type'];
							
							$bnowdata['ResID_Value']		=	$Reservation_value['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Value'];
							
						/* Room ResGlobalInfo Attributes End */
						/* echo '<pre>';
						print_r($bnowdata); */
						
						$array_keys = array_keys($bnowdata);
						
						fetchColumn(BNOW_RESER,$array_keys);
						
						$available = get_data(BNOW_RESER,array('user_id'=>$bnowdata['user_id'],'hotel_id'=>$bnowdata['hotel_id'],'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']))->row_array();
						
						if(count($available)==0)
						{		
							insert_data(BNOW_RESER,$bnowdata);
						}
						else
						{
							update_data(BNOW_RESER,$bnowdata,array('user_id'=>$bnowdata['user_id'],'hotel_id'=>$bnowdata['hotel_id'],'ResID_Value'=>$bnowdata['ResID_Value'],'HotelCode'=>$bnowdata['HotelCode']));
						}
						$checkin=date('Y/m/d',strtotime($bnowdata['Start']));
						$checkout=date('Y/m/d',strtotime($bnowdata['End']));
						$nig =_datebetween($checkin,$checkout);
						$avdata['user_id']				=	$bnowdata['user_id'];
						$avdata['hotel_id']				=	$bnowdata['hotel_id'];
						$avdata['channel_id']			=	'17';
						$avdata['channel_hotel_id']		=	$bnowdata['HotelCode'];
						$avdata['reservation_id']		=	$bnowdata['ResID_Value'];
						$avdata['start']				=	$bnowdata['Start'];
						$avdata['end']					=	$bnowdata['End'];
						$avdata['relation_one']			=	$bnowdata['RoomTypeCode'];
						$avdata['relation_two']			=	$bnowdata['RatePlanCode'];
						$avdata['difference']			=	$bnowdata['NumberOfUnits'];
						$avdata['reservation_status']	=	$bnowdata['ResStatus'];
						insert_data(UAVL,$avdata);
					}

					$this->updateAvailability($bnowdata['HotelCode'],$source='Cron');
				}
			}
			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from BookOnlineNow!!!';
			echo json_encode($meg);
		}
	}
	
	function updateAvailability($HotelCode,$source)
	{
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
		
		$userDetails	=	get_data(CONNECT,array('channel_id'=>17,'hotel_channel_id'=>$HotelCode,'status'=>'enabled'),'user_id,hotel_id')->row_array();
		
		if($userDetails)
		{
			$getRooms	=	get_data(UAVL,array('user_id'=>$userDetails['user_id'],'hotel_id'=>$userDetails['hotel_id'],'channel_id'=>17,'channel_hotel_id'=>$HotelCode,'status'=>1))->result_array();
			/* echo '<pre>';
			print_r($getRooms);
			die; */
			if($getRooms)
			{
				foreach($getRooms as $getRoomsVal)
				{
					extract($getRoomsVal);

					if($reservation_status!='Cancel')
					{
						$updateOldRoom	=	get_data(UAVL,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>17,'channel_hotel_id'=>$channel_hotel_id,'status'=>0,'reservation_id'=>$reservation_id))->row_array();
					}
					else
					{
						$updateOldRoom	=	array();
					}
					
					if(count($updateOldRoom)!=0)
					{
						$getRoomRelation	=	get_data(IM_BNOW,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>17,'HotelCode'=>$channel_hotel_id,'InvTypeCode'=>$updateOldRoom['relation_one'],'RatePlanCode'=>$updateOldRoom['relation_two']),'import_mapping_id')->row_array();
						
						if($getRoomRelation)
						{
							$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>17,'import_mapping_id'=>$getRoomRelation['import_mapping_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
							
							if($getMappedRooms)
							{
								if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
								=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
								{
									$startDate		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['start']);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['end']);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_1	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_1 as $date)
									{
										$date = $date->format("d/m/Y");
									
										$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date),'room_update_id,availability')->row_array();
										
										if(count($available_update_details)!=0)
										{
											$value	=	$available_update_details['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data['trigger_cal']=1;
											
											$ch_update_data['availability']=$value;
											
											update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date));
											
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
									$startDate		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['start']);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['end']);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_2	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_2 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row();
										
										if(count($available_update_details_RESERV)!=0)
										{
											$value	=	$available_update_details_RESERV['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data['trigger_cal']=1;
											
											$ch_update_data['availability']=$value;
											
											update_data(RESERV,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
											
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
									$startDate		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['start']);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['end']);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_3	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
						
									foreach($period_old_3 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date),'room_update_id,availability')->row_array();
										
										if(count($available_update_details_RATE_BASE)!=0)
										{
											$value	=	$available_update_details_RATE_BASE['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data_RATE_BASE['trigger_cal']=1;
											
											$ch_update_data_RATE_BASE['availability']=$value;
											
											update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date));
											
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
									$startDate		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['start']);
									$endDate 		=	DateTime::createFromFormat("Y-m-d",$updateOldRoom['end']);
									$periodInterval = 	new DateInterval( "P1D" );
									$period_old_4	= 	new DatePeriod( $startDate, $periodInterval, $endDate );
									
									foreach($period_old_4 as $date)
									{
										$date = $date->format("d/m/Y");
										
										$available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row_array();
										
										if(count($available_update_details_RATE_ADD)!=0)
										{
											$value	=	$available_update_details_RATE_ADD['availability']+$updateOldRoom['difference'];
											
											$opr	=	'+';
											
											$ch_update_data_RATE_ADD['trigger_cal']=1;
											
											$ch_update_data_RATE_ADD['availability']=$value;
											
											update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
											
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
								$this->availability_model->updateAvailabilityBNOW($updateOldRoom['start'],$updateOldRoom['end'],$user_id,$hotel_id,$getMappedRooms['property_id'],'17');
							}
						}
						
						delete_data(UAVL,array('column_id'=>$updateOldRoom['column_id']));
					}

					$getRoomRelation	=	get_data(IM_BNOW,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>17,'HotelCode'=>$channel_hotel_id,'InvTypeCode'=>$relation_one,'RatePlanCode'=>$relation_two),'import_mapping_id')->
					row_array();
					
					if($getRoomRelation)
					{
						$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>17,'import_mapping_id'=>$getRoomRelation['import_mapping_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
						
						if($getMappedRooms)
						{
							if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
							=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
							{
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_1	=	new DatePeriod( $startDate, $periodInterval, $endDate );
					
								foreach($period_new_1 as $date)
								{
									$date = $date->format("d/m/Y");
								
									$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date),'room_update_id,availability')->row_array();
									
									if(count($available_update_details)!=0)
									{
										if($reservation_status=='Cancel')
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
										
										update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date));
										
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
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_2	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_2 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row();
									
									if(count($available_update_details_RESERV)!=0)
									{
										if($reservation_status=='Cancel')
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
										
										update_data(RESERV,$ch_update_data,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
									
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
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_3	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_3 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date),'room_update_id,availability')->row_array();
									
									if(count($available_update_details_RATE_BASE)!=0)
									{
										if($reservation_status=='Cancel')
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
										
										update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date));
										
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
								$startDate		=	DateTime::createFromFormat("Y-m-d",$start);
								$endDate 		=	DateTime::createFromFormat("Y-m-d",$end);
								$periodInterval	=	new DateInterval( "P1D" );
								$period_new_4	=	new DatePeriod( $startDate, $periodInterval, $endDate );
								
								foreach($period_new_4 as $date)
								{
									$date = $date->format("d/m/Y");
									
									$available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']),'reserv_price_id,availability')->row_array();
									
									if(count($available_update_details_RATE_ADD)!=0)
									{
										if($reservation_status=='Cancel')
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
										
										update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$getMappedRooms['property_id'],'rate_types_id'=>$getMappedRooms['rate_id'],'individual_channel_id'=>17,'separate_date'=>$date,'guest_count'=>$getMappedRooms['guest_count'],'refun_type'=>$getMappedRooms['refun_type']));
										
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
							$this->availability_model->updateAvailabilityBNOW($start,$end,$user_id,$hotel_id,$getMappedRooms['property_id'],'17');
						}
					}
					
					$UAVL['status']	=	'0';
					
					update_data(UAVL,$UAVL,array('column_id'=>$column_id));
					
					if($reservation_status=='Cancel')
					{
						delete_data(UAVL,array('reservation_id'=>$reservation_id));
					}
				}
			}
		}
	}
}
?>