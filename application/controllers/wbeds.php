<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class wbeds extends Front_Controller {

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

    function test2()
    {

	    $url = "http://live.suppliers.gsisservices.com/GServices/wsSuppliers";
	    $soap_request = '<?xml version="1.0" encoding="utf-8"?>
    		<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
			<soapenv:Header/>
			<soapenv:Body>
			<impl:getHotelsSuppliersRQ>
			<impl:getHotelsSuppliersRQRow>
			<impl:idCliePor>HTRTEST</impl:idCliePor>
			<impl:pwCliePor>VNZU6Q044N</impl:pwCliePor>
			<impl:ideCHM>HOTERATUS</impl:ideCHM>
			</impl:getHotelsSuppliersRQRow>
			</impl:getHotelsSuppliersRQ>
			</soapenv:Body>
			</soapenv:Envelope>';
	    $header = array(
	        "Content-type: text/xml;charset=\"utf-8\"",
	        "Accept: text/xml",
	        "Cache-Control: no-cache",
	        "Pragma: no-cache",
	        "SOAPAction: \"http://tracmedia.org/InTheLife\"",
	        "Content-length: ".strlen($soap_request),
	    );
	    
	    $soap_do = curl_init();
	    curl_setopt($soap_do, CURLOPT_URL,"http://live.suppliers.gsisservices.com/GServices/wsSuppliers" );
	    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
	    curl_setopt($soap_do, CURLOPT_POST,           true );
	    curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $soap_request);
	    curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
	    $result = curl_exec($soap_do);
	    print_r($result);
	    die;
	    $airings = array();
	    
	    if($result === false) {
	        $err = 'Curl error: ' . curl_error($soap_do);
	        curl_close($soap_do);
	        print $err;
	    } else {
	        curl_close($soap_do);
	        $content = value_in("InTheLifeResult",$result);
	        $contentXML = html_entity_decode($content);
	        
	        $xml = simplexml_load_string($contentXML);
	        
	        foreach($xml->carriage[0] as $airing) {
	            
	            $tmp['station'] = strval($airing->station);
	            $tmp['date'] = strval($airing->date);
	            $tmp['time'] = strval($airing->time);
	            $tmp['timezone'] = strval($airing->timezone);
	            $tmp['episodenum'] = strval($airing->episodenum);
	            $tmp['episodetitle'] = strval($airing->episodetitle);
	            $airings[] = $tmp;
	        }
		
	    }
	    print_r($airings);
	
    }
    function test()
    {
    		$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
    		<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
			<soapenv:Header/>
			<soapenv:Body>
			<impl:getHotelsSuppliersRQ>
			<impl:getHotelsSuppliersRQRow>
			<impl:idCliePor>HTRTEST</impl:idCliePor>
			<impl:pwCliePor>VNZU6Q044N</impl:pwCliePor>
			<impl:ideCHM>HOTERATUS</impl:ideCHM>
			</impl:getHotelsSuppliersRQRow>
			</impl:getHotelsSuppliersRQ>
			</soapenv:Body>
			</soapenv:Envelope>';

		
						$url='http://live.suppliers.gsisservices.com/GServices/wsSuppliers';
			 $headers = array(
                            "Content-type: application/json",
                            "Host:test.suppliers.gsisservices.com",
                            "Content-length: ".strlen($xml_post_string),
                        );


		
				$ch = curl_init();
				//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($ch, CURLOPT_TIMEOUT, 500);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
				//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				print_r($response);
				

				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
				$xml = simplexml_load_string($xml);
				$json = json_encode($xml);
				$responseArray = json_decode($json,true);
				print_r($responseArray);
				print_r($response);
				print_r($ss);

    }
	function importRooms($channel_id)
	{
		$bk_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'status'=>'enabled'))->row();
		if($bk_details)
		{
			$bnowData['user_id']	=	current_user_type();
			$bnowData['hotel_id']	=	hotel_id();
			$bnowData['channel_id']	=	insep_decode($channel_id);
			$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
								<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
								<soapenv:Header/>
								<soapenv:Body>
								<impl:getRoomsRQ>
								<impl:getRoomsRQRow>
								<impl:idCliePor>'.$bk_details->user_name.'</impl:idCliePor>
								<impl:pwCliePor>'.$bk_details->user_password.'</impl:pwCliePor>
								<impl:referenceHotel>'.$bk_details->hotel_channel_id.'</impl:referenceHotel>
								<impl:codeSupplier>'.$bk_details->cmid.'</impl:codeSupplier>
								</impl:getRoomsRQRow>
								</impl:getRoomsRQ>
								</soapenv:Body>
								</soapenv:Envelope>';
			
			
			
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
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_URL, $url);
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
				 mail("Datahernandez@gmail.com","Welcombest",$xml_post_string.'repuest>>:'.$response );
				$error = @$responseArray['soapenvBody']['getRoomsRS']['getRoomsRSRow']['error'];
				$fault = @$responseArray['soapenvBody']['soapenvFault']['faultstring'];
				if($error)
				{
					$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),$error,'Get Channel',date('m/d/Y h:i:s a', time()));
					
					return $error;
				}
				else if($fault)
				{
					$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),$fault,'Get Channel',date('m/d/Y h:i:s a', time()));
					
					return $fault;
				}
				else
				{
					$result = $responseArray['soapenvBody']['getRoomsRS']['getRoomsRSRow']['curOutRoomsRow'];
					if($result)
					{
						$data['user_id']	=	current_user_type();
						$data['hotel_id']	=	hotel_id();
						$data['channel_id']	=	insep_decode($channel_id);
						$data['HotelCode']	=	$bk_details->hotel_channel_id;
						
						foreach($result as $key=>$roomAttr)
						{
							foreach($roomAttr as $roomKey=>$roomValue)
							{
								if($roomKey!='curOutRoomsRateRow')
								{
									$data[$roomKey]	=	$roomValue;
								}
								elseif($roomKey=='curOutRoomsRateRow')
								{
									if(isAssoc($roomValue))
									{
										$roomRate	=	array($roomValue);
									}
									else
									{
										$roomRate	=	$roomValue;
									}
									foreach($roomRate as $rateAttr)
									{
										foreach($rateAttr as $rateKey=>$rateValue)
										{
											if($rateKey!='requiredPaxes' && $rateKey!='curOutBoardsRateRow')
											{
												$data[$rateKey]	=	$rateValue;
											}
										}
										
										$count = $this->db->select('import_mapping_id')->from(IM_WBEDS)->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$bk_details->hotel_channel_id,'codeRoomType'=>$data['codeRoomType'],'codeRate'=>$data['codeRate'],'codeRoomFeature'=>$data['codeRoomFeature']))->count_all_results();
										if($count==0)
										{
											$array_keys = array_keys($data);
											fetchColumn(IM_WBEDS,$array_keys);
											insert_data(IM_WBEDS,$data);
										}
										else
										{
											$array_keys = array_keys($data);
											fetchColumn(IM_WBEDS,$array_keys);
											update_data(IM_WBEDS,$data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$bk_details->hotel_channel_id,'codeRoomType'=>$data['codeRoomType'],'codeRate'=>$data['codeRate'],'codeRoomFeature'=>$data['codeRoomFeature']));
										}
									}
								}
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
		$data['wbeds'] 				= 	$this->wbeds_model->get_mapping_rooms($channel_id);
		$booking_all 				= 	$this->wbeds_model->get_all_mapping_rooms($channel_id);
		$data['channel_details'] 	=	$this->wbeds_model->get_all_mapped_rooms($channel_id);
		if($booking_all=='0')
		{
			$data['import_need'] = "Need to import the room for mapping!!!";
		}
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
		return $data;
	}
	
	function maptochannel($channel_id,$property_id)
	{
		$data['available']		=	get_data(IM_WBEDS,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
		$data['mapping_values']	=	get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
		$data['wbeds']	=	$this->wbeds_model->get_mapping_rooms(insep_decode($channel_id),'update');
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
			if($importDetails['channel_id'] == 14)
			{
				$this->wbeds_model->importAvailabilities(current_user_type(),hotel_id(),$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure,$importRates);
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
			if(insep_decode($channel_id)=='14')
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
/* 					echo $url[0]; 
					echo '<pre>';
					print_r($ch_details) */;
					
					$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
				    <soapenv:Header/>
				    <soapenv:Body>
					  <impl:getReservationsReportRQ>
						 <impl:getReservationsReportRQRow>
							<impl:idCliePor>'.$ch_details->user_name.'</impl:idCliePor>
							<impl:pwCliePor>'.$ch_details->user_password.'</impl:pwCliePor>
							<impl:ideCHM>'.ideCHM.'</impl:ideCHM>
							<impl:referenceHotel>'.$ch_details->hotel_channel_id.'</impl:referenceHotel>
							<impl:codeSupplier>'.$ch_details->cmid.'</impl:codeSupplier>
							<impl:beginDate>'.date('d/m/Y').'</impl:beginDate>
							<impl:endDate>'.date('d/m/Y').'</impl:endDate>
							<impl:typeDate>C</impl:typeDate>
							<impl:typeRequest>N</impl:typeRequest>
						 </impl:getReservationsReportRQRow>
					  </impl:getReservationsReportRQ>
				    </soapenv:Body>
				    </soapenv:Envelope>';
					/* echo $xml_post_string;
					die; */
					$x_r_rq_data['channel_id'] 	= 	'14';						
					$x_r_rq_data['user_id'] 	=	'0';						
					$x_r_rq_data['hotel_id'] 	=	'0';						
					$x_r_rq_data['message'] 	= 	$xml_post_string;						
					$x_r_rq_data['type'] 		= 	'WBEDS_REQ';						
					$x_r_rq_data['section'] 	=	'RESER';						
					//insert_data(ALL_XML,$x_r_rq_data);
					
					/* mail("xml@hoteratus.com"," Reservation Request Form WBEDS",$xml_post_string);
					
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
					$x_r_rs_data['type'] 		=	'WBEDS_RES';						
					$x_r_rs_data['section'] 	=	'RESER';
					
					mail("xml@hoteratus.com"," Reservation Response Form WBEDS ",$response); */
					
					//insert_data(ALL_XML,$x_r_rs_data);
					
					$response = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <soapenv:Body>
        <getReservationsReportRS xmlns="http://impl.wsSuppliers.integracion/">
            <getReservationsReportRSRow>
                <tokenId>20160725112709108151</tokenId>
                <curOutHotelReservationsRow>
                    <resGlobalInfoRow>
                        <createDateTime>22-12-2016 14:37:43</createDateTime>
                        <modificationDateTime>22-07-2016 14:38:45</modificationDateTime>
                        <reservationStatus>CONFIRMATION</reservationStatus>
                        <reservationStatusCode>C</reservationStatusCode>
                        <reservationID>Y1ZPYB</reservationID>
                        <dateCheckIn>27-12-2016</dateCheckIn>
                        <dateCheckOut>28-12-2016</dateCheckOut>
                        <guestName>TESTMRTEST</guestName>
                        <methodPayment>Credit</methodPayment>
                    </resGlobalInfoRow>
                    <roomsStaysRow>
                        <curOutRoomsStayRow>
                            <roomStayGlobalInfoRow>
                                <roomTypeCode>DBL</roomTypeCode>
                                <accommodationTypeCode>02-WL</accommodationTypeCode>
                                <roomStayName> DOUBLE (TWIN/DOUBLE) Caracteristica generica. </roomStayName>
                                <numberOfUnits>1</numberOfUnits>
                                <systemSale>FREE SALE</systemSale>
                                <timeSpanRow>
                                    <start>27/12/2016</start>
                                    <end>28/12/2016</end>
                                </timeSpanRow>
                            </roomStayGlobalInfoRow>
                            <mealPlanRow>
                                <mealPlanCode>BB</mealPlanCode>
                                <mealPlanName>Bed and breakfast</mealPlanName>
                            </mealPlanRow>
                            <ratesRow>
                                <curOutRatesRow>
                                    <effectiveDate>27/12/2016</effectiveDate>
                                    <expireDate>28/12/2016</expireDate>
                                    <nameRate>BAR BB</nameRate>
                                    <codeRate>BAR</codeRate>
                                    <totalDayAmount>150,48</totalDayAmount>
                                    <currencyCode>EUR</currencyCode>
                                </curOutRatesRow>
                                <totalAmount>150,48</totalAmount>
                                <currencyCode>EUR</currencyCode>
                            </ratesRow>
                            <guestCountsRow>
                                <numberAdults>2</numberAdults>
                                <numberChilds>0</numberChilds>
                                <numberInfants>0</numberInfants>
                            </guestCountsRow>
                            <resGuestRow>
                                <curOutPersonNameRow>
                                    <givenName>TESTMR</givenName>
                                    <surname>TEST </surname>
                                    <sex>H</sex>
                                    <age>30</age>
                                </curOutPersonNameRow>
                                <curOutPersonNameRow>
                                    <givenName>AAB</givenName>
                                    <surname>AAAB </surname>
                                    <sex>H</sex>
                                    <age>30</age>
                                </curOutPersonNameRow>
                            </resGuestRow>
                        </curOutRoomsStayRow>
                        <resumeStaysRow>
                            <totalUnits>1</totalUnits>
                            <totalAdults>2</totalAdults>
                            <totalChilds>0</totalChilds>
                            <totalInfants>0</totalInfants>
                            <totalAmount>150,48</totalAmount>
                            <currencyCode>EUR</currencyCode>
                        </resumeStaysRow>
                        <comments>(27/12/2016-28/12/2016) SPECIAL OFFER NON REFUNDABLE swimming pool close 04/04/17 till 04/05/17 -- 722/11/16 till 27/11/17 -- 13/12/17 till 18/12/17 POSSIBLE REGIONAL LOCAL TAXES TO PAY IN DESTINATION. THE AMOUNT MAY VARY DEPENDING ON THE HOTEL TYPE, CATEGORY, SEASON AND/OR LOCATION. SWIMMING POOL CLOSED : 2015: FROM THE 23RD UNTIL THE 27TH OF NOVEMBER INCLUDED. 2016: FROM THE 7TH UNTIL THE 13TH OF MARCH INCLUDED, AND FROM THE 21ST UNTIL THE 27TH OF NOVEMBER INCLUDED. BOOKED AND PAYABLE BY GLOBALIA TRAVEL CLUB SPAIN SLU
                        </comments>
                        <basicPropertyInfoRow>
                            <hotelCode>15426</hotelCode>
                            <chainCode>VHOUS</chainCode>
                            <brandCode/>
                        </basicPropertyInfoRow>
                    </roomsStaysRow>
                </curOutHotelReservationsRow>
            </getReservationsReportRSRow>
        </getReservationsReportRS>
    </soapenv:Body>
</soapenv:Envelope>';
					
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					
					$xml = simplexml_load_string($xml);
					
					$json = json_encode($xml);
					
					$responseArray = json_decode($json,true);

					if(is_array($responseArray))
					{ 
						$savewbedsbooking = $this->savewbedsbooking($responseArray);

						$data['succes'] = $savewbedsbooking;
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
	
	function savewbedsbooking($responseArray)
	{
		//echo '<pre>';
		if(is_array($responseArray))
		{
			$tokenId = @$responseArray['soapenvBody']['getReservationsReportRS']['getReservationsReportRSRow'];
			$data['tokenId']			=	$tokenId['tokenId'];
			$curOutHotelReservationsRow = @$responseArray['soapenvBody']['getReservationsReportRS']['getReservationsReportRSRow']['curOutHotelReservationsRow'];
			
			if(is_array($curOutHotelReservationsRow))
			{
				if(isAssoc($curOutHotelReservationsRow))
				{
					$curOutHotelReservationsRow	=	array($curOutHotelReservationsRow);
				}
				else
				{
					$curOutHotelReservationsRow	=	$curOutHotelReservationsRow;
				}
				foreach($curOutHotelReservationsRow as $resGlobalInfoRow_Key=>$resGlobalInfoRow_Value)
				{
					$resGlobalInfoRow 	=	@$resGlobalInfoRow_Value['resGlobalInfoRow'];
					
					if($resGlobalInfoRow)
					{
						$data['createDateTime'] 		=	@$resGlobalInfoRow['createDateTime'];
						$data['modificationDateTime'] 	=	@$resGlobalInfoRow['modificationDateTime'];
						$data['reservationStatus']		=	@$resGlobalInfoRow['reservationStatus']=='CANCELLATION' ? $resGlobalInfoRow['reservationStatus'] : 'CONFIRMATION';//$resGlobalInfoRow['reservationStatus'];
						$data['reservationStatusCode']	=	@$resGlobalInfoRow['reservationStatusCode'];
						$data['reservationID'] 			=	@$resGlobalInfoRow['reservationID'];
						$data['dateCheckIn'] 			=	@$resGlobalInfoRow['dateCheckIn'];
						$data['dateCheckOut'] 			=	@$resGlobalInfoRow['dateCheckOut'];
						$data['guestName'] 				=	@$resGlobalInfoRow['guestName'];
						/* $data['givenName'] 				=	$resGlobalInfoRow['givenName'];
						$data['surname'] 				=	$resGlobalInfoRow['surname']; */
						!is_array(@$resGlobalInfoRow['givenName'])?$data['givenName']	=	@$resGlobalInfoRow['givenName']:$data['givenName']	= @$resGlobalInfoRow['givenName'][0];
						!is_array(@$resGlobalInfoRow['surname'])?$data['surname']	=	@$resGlobalInfoRow['surname']:$data['surname']	= @$resGlobalInfoRow['surname'][0];
						$data['methodPayment'] 				=	@$resGlobalInfoRow['methodPayment'];
					}
					
					$basicPropertyInfoRow	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['basicPropertyInfoRow'];
					
					if($basicPropertyInfoRow)
					{
						!is_array(@$basicPropertyInfoRow['hotelCode'])?$data['hotelCode']	=	@$basicPropertyInfoRow['hotelCode']:$data['hotelCode']	= '';
						!is_array(@$basicPropertyInfoRow['chainCode'])?$data['chainCode']	=	@$basicPropertyInfoRow['chainCode']:$data['chainCode']	= '';
						!is_array(@$basicPropertyInfoRow['brandCode'])?$data['brandCode']	=	@$basicPropertyInfoRow['brandCode']:$data['brandCode']	= '';
						
						$user_details						= 	get_data(CONNECT,array('channel_id'=>'14','hotel_channel_id'=>$data['hotelCode']),'user_id,hotel_id')->row_array();

						if(count($user_details)!=0)
						{
							$data['user_id']  				= 	$user_details['user_id'];
							$data['hotel_id'] 				= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  				= 	0;
							$data['hotel_id'] 				= 	0;
						}
					}
					$data['channel_id'] = 14;
					$beforeRoomsStaysRow	=	@$resGlobalInfoRow_Value['beforeRoomsStaysRow'];
					
					if($beforeRoomsStaysRow)
					{
						if(isAssoc($beforeRoomsStaysRow))
						{
							$beforeRoomsStaysRow	=	array($beforeRoomsStaysRow);
						}
						else
						{
							$beforeRoomsStaysRow	=	$beforeRoomsStaysRow;
						}
						$beforeRoomsStaysRow  = $beforeRoomsStaysRow[0];
						$roomStayGlobalInfoRow	=	@$beforeRoomsStaysRow['curOutBeforeRoomsStayRow'];
						if($roomStayGlobalInfoRow)
						{
							if(isAssoc($roomStayGlobalInfoRow))
							{
								$roomStayGlobalInfoRow	=	array($roomStayGlobalInfoRow);
							}
							else
							{
								$roomStayGlobalInfoRow	=	$roomStayGlobalInfoRow;
							}
							if($roomStayGlobalInfoRow)
							{
								$udata='';
								$udata['user_id']			=	$data['user_id'];
								$udata['hotel_id']			=	$data['hotel_id'];
								$udata['channel_id']		=	'14';
								foreach($roomStayGlobalInfoRow as $roomStayGlobalInfoRow_Key)
								{	
									$udata['tokenId'] 				=	@$data['tokenId'];
									$udata['createDateTime'] 		=	@$data['createDateTime'];
									$udata['modificationDateTime'] 	=	@$data['modificationDateTime'];
									$udata['reservationStatus']		=	'AMENDMENT';//$data['reservationStatus'];
									$udata['reservationStatusCode']	=	@$data['reservationStatusCode'];
									$udata['reservationID'] 		=	@$data['reservationID'];
									$udata['dateCheckIn'] 			=	@$data['dateCheckIn'];
									$udata['dateCheckOut'] 			=	@$data['dateCheckOut'];
									$udata['guestName'] 			=	@$data['guestName'];
									$udata['givenName'] 			=	@$data['givenName'];
									$udata['surname'] 				=	@$data['surname'];
									$udata['methodPayment'] 		=	@$data['methodPayment'];
									
									if($data['hotelCode']!='')
									{	
										$udata['hotelCode']	=	@$data['hotelCode'];
									}
									if($data['chainCode']!='')
									{	
										$udata['chainCode']	=	@$data['chainCode'];
									}
									if($data['brandCode']!='')
									{	
										$udata['brandCode']	=	@$data['brandCode'];
									}
									
									foreach($roomStayGlobalInfoRow_Key as $key=>$roomStayGlobalInfoRow_Value)
									{
										if($key=='roomStayGlobalInfoRow')
										{
											$udata['roomTypeCode'] 			=	@$roomStayGlobalInfoRow_Value['roomTypeCode'];
											$udata['accommodationTypeCode']	=	@$roomStayGlobalInfoRow_Value['accommodationTypeCode'];
											$udata['roomStayName'] 			=	@$roomStayGlobalInfoRow_Value['roomStayName'];
											$udata['numberOfUnits'] 		=	@$roomStayGlobalInfoRow_Value['numberOfUnits'];
											$udata['timeSpanRow_start'] 	=	@$roomStayGlobalInfoRow_Value['timeSpanRow']['start'];
											$udata['timeSpanRow_end'] 		=	@$roomStayGlobalInfoRow_Value['timeSpanRow']['end'];
										}
										if($key=='mealPlanRow')
										{
											$udata['mealPlanCode'] 			=	@$roomStayGlobalInfoRow_Value['mealPlanCode'];
											$udata['mealPlanName']			=	@$roomStayGlobalInfoRow_Value['mealPlanName'];
										}
										if($key=='ratesRow')
										{
											$udata['totalAmount']			=	@$roomStayGlobalInfoRow_Value['totalAmount'];
											$udata['currencyCode']			=	@$roomStayGlobalInfoRow_Value['currencyCode'];
										}
										if($key=='guestCountsRow')
										{
											$udata['numberAdults'] 			=	@$roomStayGlobalInfoRow_Value['numberAdults'];
											$udata['numberChilds']			=	@$roomStayGlobalInfoRow_Value['numberChilds'];
											$udata['nameRnumberInfantsate'] =	@$roomStayGlobalInfoRow_Value['numberInfants'];
										}
										if($key=='resGuestRow')
										{
											$uresGuestRow	=	@$roomStayGlobalInfoRow_Value['curOutPersonNameRow'];
											if(isAssoc($uresGuestRow))
											{
												$uresGuestRow	=	array($uresGuestRow);
											}
											else
											{
												$uresGuestRow	=	$uresGuestRow;
											}
											if($uresGuestRow)
											{
												$ugivenName='';
												$usurname='';
												$usex='';
												$uage='';
												$ucountryName='';
												foreach($uresGuestRow as $uresGuestRow_Key => $uresGuestRow_Value)
												{
													$ugivenName		.=	@$uresGuestRow_Value['givenName'].',';
													$usurname		.=	@$uresGuestRow_Value['surname'].',';
													$usex			.=	@$uresGuestRow_Value['sex'].',';
													$uage			.=	@$uresGuestRow_Value['age'].',';
													$ucountryName	.=	@$uresGuestRow_Value['countryName'].',';
												}
												$udata['G_givenName'] 	=	trim(@$ugivenName,',');
												$udata['G_surname'] 	=	trim(@$usurname,',');
												$udata['G_sex'] 		=	trim(@$usex,',');
												$udata['G_age'] 		=	trim(@$uage,',');
												$udata['G_countryName']	=	trim(@$ucountryName,',');
											}
										}
									}

									$udata 		= array_filter($udata);
					
									$array_keys = array_keys($udata);
										
									fetchColumn(WBEDS_RESER,$array_keys);
									
									$upavailable = get_data(WBEDS_RESER,array('user_id'=>$udata['user_id'],'hotel_id'=>$udata['hotel_id'],'reservationID'=>$udata['reservationID'],'hotelCode'=>$udata['hotelCode'],'roomTypeCode'=>$udata['roomTypeCode'],'accommodationTypeCode' => $udata['accommodationTypeCode']),'hotelCode,codeRate')->row_array();
									
									if(count($upavailable) > 0)
									{
										update_data(WBEDS_RESER,$udata,array('user_id'=>$udata['user_id'],'hotel_id'=>$udata['hotel_id'],'reservationID'=>$udata['reservationID'],'hotelCode'=>$upavailable['hotelCode'],'roomTypeCode'=>$udata['roomTypeCode'],'accommodationTypeCode'=>$udata['accommodationTypeCode'],'codeRate'=>$upavailable['codeRate']));
										
										$uavdata['user_id']				=	$udata['user_id'];
										$uavdata['hotel_id']			=	$udata['hotel_id'];
										$uavdata['channel_id']			=	'14';
										$uavdata['channel_hotel_id']	=	$udata['hotelCode'];
										$uavdata['reservation_id']		=	$udata['reservationID'];
										$uavdata['start']				=	$udata['timeSpanRow_start'];
										$uavdata['end']					=	$udata['timeSpanRow_end'];
										$uavdata['relation_one']		=	$udata['roomTypeCode'];
										$uavdata['relation_two']		=	$udata['accommodationTypeCode'].','.$upavailable['codeRate'];
										$uavdata['difference']			=	$udata['numberOfUnits'];
										$uavdata['reservation_status']	=	$udata['reservationStatus'];
										
										insert_data(UAVL,$uavdata);
									}
								}
							}
						}
					}
					
					$roomsStaysRow 		= 	$resGlobalInfoRow_Value['roomsStaysRow']['curOutRoomsStayRow']['roomStayGlobalInfoRow'];
					
					if($roomsStaysRow)
					{
						$data['roomTypeCode'] 			=	@$roomsStaysRow['roomTypeCode'];
						$data['accommodationTypeCode']	=	@$roomsStaysRow['accommodationTypeCode'];
						$data['roomStayName'] 			=	@$roomsStaysRow['roomStayName'];
						$data['numberOfUnits'] 			=	@$roomsStaysRow['numberOfUnits'];
						$data['systemSale'] 			=	@$roomsStaysRow['systemSale'];
						$data['timeSpanRow_start'] 		=	@$roomsStaysRow['timeSpanRow']['start'];
						$data['timeSpanRow_end'] 		=	@$roomsStaysRow['timeSpanRow']['end'];
					}
					
					$mealPlanRow 		= 	@$resGlobalInfoRow_Value['roomsStaysRow']['curOutRoomsStayRow']['mealPlanRow'];
					
					if($mealPlanRow)
					{
						$data['mealPlanCode'] 			=	@$mealPlanRow['mealPlanCode'];
						$data['mealPlanName']			=	@$mealPlanRow['mealPlanName'];
					}
					
					$ratesRow	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['curOutRoomsStayRow']['ratesRow'];
					
					if($ratesRow)
					{
						$data['effectiveDate'] 			=	@$ratesRow['curOutRatesRow']['effectiveDate'];
						$data['expireDate']				=	@$ratesRow['curOutRatesRow']['expireDate'];
						$data['nameRate'] 				=	@$ratesRow['curOutRatesRow']['nameRate'];
						$data['codeRate']				=	@$ratesRow['curOutRatesRow']['codeRate'];
						$data['totalDayAmount'] 		=	@$ratesRow['curOutRatesRow']['totalDayAmount'];
						!is_array(@$ratesRow['totalAmount'])?$data['totalAmount']	=	@$ratesRow['totalAmount']:$data['totalAmount']	= '';
						!is_array(@$ratesRow['currencyCode'])?$data['tcurrencyCode']	=	@$ratesRow['currencyCode']:$data['tcurrencyCode']	= '';
					}
					
					$guestCountsRow	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['curOutRoomsStayRow']['guestCountsRow'];
					
					if($guestCountsRow)
					{
						$data['numberAdults'] 			=	@$guestCountsRow['numberAdults'];
						$data['numberChilds']			=	@$guestCountsRow['numberChilds'];
						$data['nameRnumberInfantsate'] 	=	@$guestCountsRow['numberInfants'];
					}
					
					$resGuestRow	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['curOutRoomsStayRow']['resGuestRow']['curOutPersonNameRow'];
					
					if(isAssoc($resGuestRow))
					{
						$resGuestRow	=	array($resGuestRow);
					}
					else
					{
						$resGuestRow	=	$resGuestRow;
					}
					if($resGuestRow)
					{
						$givenName='';
						$surname='';
						$sex='';
						$age='';
						$countryName='';
						foreach($resGuestRow as $resGuestRow_Key => $resGuestRow_Value)
						{
							$givenName		.=	@$resGuestRow_Value['givenName'].',';
							$surname		.=	@$resGuestRow_Value['surname'].',';
							$sex			.=	@$resGuestRow_Value['sex'].',';
							$age			.=	@$resGuestRow_Value['age'].',';
							$countryName	.=	@$resGuestRow_Value['countryName'].',';
						}
						$data['G_givenName'] 	=	trim(@$givenName,',');
						$data['G_surname'] 		=	trim(@$surname,',');
						$data['G_sex'] 			=	trim(@$sex,',');
						$data['G_age'] 			=	trim(@$age,',');
						$data['G_countryName']	=	trim(@$countryName,',');
					}
					
					$resumeStaysRow	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['resumeStaysRow'];
					
					if($resumeStaysRow)
					{
						$data['totalUnits'] 	=	@$resumeStaysRow['totalUnits'];
						$data['totalAdults']	=	@$resumeStaysRow['totalAdults'];
						$data['totalChilds'] 	=	@$resumeStaysRow['totalChilds'];
						$data['totalInfants'] 	=	@$resumeStaysRow['totalInfants'];
						!is_array(@$resumeStaysRow['totalAmount'])?$data['totalAmount']	=	@$resumeStaysRow['totalAmount']:$data['totalAmount']	= '';
						!is_array(@$resumeStaysRow['currencyCode'])?$data['tcurrencyCode']	=	@$resumeStaysRow['currencyCode']:$data['tcurrencyCode']	= '';
					}
					
					$comments	= 	@$resGlobalInfoRow_Value['roomsStaysRow']['comments'];
					
					if($comments)
					{
						$data['comments']	=	$comments;
					}
					
					$data 		= array_filter($data);
					
					$array_keys = array_keys($data);
						
					fetchColumn(WBEDS_RESER,$array_keys);
					
					if($data['reservationStatus']=='CONFIRMATION')
					{
						$inavailable = get_data(WBEDS_RESER,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'reservationID'=>$data['reservationID'],'hotelCode'=>$data['hotelCode'],'roomTypeCode'=>$data['roomTypeCode'],'reservationStatus'=>$data['reservationStatus'],'accommodationTypeCode' =>$data['accommodationTypeCode'],'codeRate' =>$data['codeRate']),'import_reserv_id')->row_array();
						
						if(count($inavailable)==0)
						{
							insert_data(WBEDS_RESER,$data);
							
							$avdata['user_id']				=	$data['user_id'];
							$avdata['hotel_id']				=	$data['hotel_id'];
							$avdata['channel_id']			=	'14';
							$avdata['channel_hotel_id']		=	$data['hotelCode'];
							$avdata['reservation_id']		=	$data['reservationID'];
							$avdata['start']				=	$data['timeSpanRow_start'];
							$avdata['end']					=	$data['timeSpanRow_end'];
							$avdata['relation_one']			=	$data['roomTypeCode'];
							$avdata['relation_two']			=	$data['accommodationTypeCode'].','.$data['codeRate'];
							$avdata['difference']			=	$data['numberOfUnits'];
							$avdata['reservation_status']	=	$data['reservationStatus'];
							
							insert_data(UAVL,$avdata);
						}
					}
					if($data['reservationStatus']=='CANCELLATION')
					{
						$inavailable = get_data(WBEDS_RESER,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'reservationID'=>$data['reservationID'],'hotelCode'=>$data['hotelCode'],'roomTypeCode'=>$data['roomTypeCode'],'accommodationTypeCode' => $data['accommodationTypeCode'],'codeRate' =>$data['codeRate']),'import_reserv_id')->row_array();
						
						if(count($inavailable) > 0)
						{
							update_data(WBEDS_RESER,$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'reservationID'=>$data['reservationID'],'hotelCode'=>$data['hotelCode'],'roomTypeCode'=>$data['roomTypeCode'],'accommodationTypeCode' => $data['accommodationTypeCode'],'codeRate' =>$data['codeRate']));
							
							$cavdata['user_id']				=	$data['user_id'];
							$cavdata['hotel_id']			=	$data['hotel_id'];
							$cavdata['channel_id']			=	'14';
							$cavdata['channel_hotel_id']	=	$data['hotelCode'];
							$cavdata['reservation_id']		=	$data['reservationID'];
							$cavdata['start']				=	$data['timeSpanRow_start'];
							$cavdata['end']					=	$data['timeSpanRow_end'];
							$cavdata['relation_one']		=	$data['roomTypeCode'];
							$cavdata['relation_two']		=	$data['accommodationTypeCode'].','.$data['codeRate'];
							$cavdata['difference']			=	$data['numberOfUnits'];
							$cavdata['reservation_status']	=	$data['reservationStatus'];
							
							insert_data(UAVL,$cavdata);
						}
					}
				}
				
				$this->updateAvailability($data['hotelCode'],$source='Manual');
			}

			return 'Insert';
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
		
		$channel_id	=	14;
		
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

					if($reservation_status!='CANCELLATION')
					{
						$updateOldRoom	=	get_data(UAVL,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$channel_hotel_id,'status'=>0,'reservation_id'=>$reservation_id))->row_array();
					}
					else
					{
						$updateOldRoom	=	array();
					}
					
					if(count($updateOldRoom)!=0)
					{
						$getRoomRelation	=	get_data(IM_WBEDS,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'HotelCode'=>$channel_hotel_id,'codeRoomType'=>$updateOldRoom['relation_one'],'codeRoomFeature'=>$updateOldRoom['relation_two']),'import_mapping_id')->row_array();
						
						if($getRoomRelation)
						{
							$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$getRoomRelation['import_mapping_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
							
							if($getMappedRooms)
							{
								if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
								=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
								{
									$start	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['start']))));
									$end 	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['end']))));
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
									$start	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['start']))));
									$end 	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['end']))));
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
									$start	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['start']))));
									$end 	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['end']))));
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
									$start	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['start']))));
									$end 	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['end']))));
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
								
							$start	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['start']))));
							$end 	= date('Y-m-d',strtotime((str_replace('/','-',$updateOldRoom['end']))));
							$this->availability_model->updateAvailabilityBNOW($start,$end,$user_id,$hotel_id,$getMappedRooms['property_id'],$channel_id);
							
							}
						}

						delete_data(UAVL,array('column_id'=>$updateOldRoom['column_id']));
					}
					
					$relation = explode(',',$relation_two);
					
					$getRoomRelation	=	get_data(IM_WBEDS,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'HotelCode'=>$channel_hotel_id,'codeRoomType'=>$relation_one,'codeRoomFeature'=>$relation[0],'codeRate'=>$relation[1]),'import_mapping_id')->row_array();
					
					if($getRoomRelation)
					{
						$getMappedRooms	=	get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'import_mapping_id'=>$getRoomRelation['import_mapping_id']),'property_id,rate_id,guest_count,refun_type')->row_array();
						
						if($getMappedRooms)
						{
							if($getMappedRooms['property_id']!='0' && $getMappedRooms['rate_id']
							=='0' && $getMappedRooms['guest_count']=='0' && $getMappedRooms['refun_type']=='0')
							{
								$start	= date('Y-m-d',strtotime((str_replace('/','-',$start))));
								$end 	= date('Y-m-d',strtotime((str_replace('/','-',$end))));
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
										if($reservation_status=='CANCELLATION')
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
								$start	= date('Y-m-d',strtotime((str_replace('/','-',$start))));
								$end 	= date('Y-m-d',strtotime((str_replace('/','-',$end))));
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
										if($reservation_status=='CANCELLATION')
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
								$start	= date('Y-m-d',strtotime((str_replace('/','-',$start))));
								$end 	= date('Y-m-d',strtotime((str_replace('/','-',$end))));
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
										if($reservation_status=='CANCELLATION')
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
								$start	= date('Y-m-d',strtotime((str_replace('/','-',$start))));
								$end 	= date('Y-m-d',strtotime((str_replace('/','-',$end))));
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
										if($reservation_status=='CANCELLATION')
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
							$start	= date('Y-m-d',strtotime((str_replace('/','-',$start))));
							$end 	= date('Y-m-d',strtotime((str_replace('/','-',$end))));
							$this->availability_model->updateAvailabilityBNOW($start,$end,$user_id,$hotel_id,$getMappedRooms['property_id'],$channel_id);
						}
					}
					
					$UAVL['status']	=	'0';
					
					update_data(UAVL,$UAVL,array('column_id'=>$column_id));
					
					if($reservation_status=='CANCELLATION')
					{
						delete_data(UAVL,array('reservation_id'=>$reservation_id));
					}
				}
			}
		}
	}
	
	function updateAvailability_old($HotelCode,$source)
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