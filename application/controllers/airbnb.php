 <?php
ini_set('memory_limit', '-1');
ini_set('display_errors', '1');
defined('BASEPATH') OR exit('No direct script access allowed');

class Airbnb extends Front_Controller
{

    function is_login()
    {
        if (!user_id())
            redirect(base_url());
        return;
    }

    function is_admin()
    {
        if (!admin_id())
            redirect(base_url());
        return;
    }

    function importRooms($channel_id)
    {
        $bk_details = get_data(CONNECT, array(
            'hotel_id' => hotel_id(),
            'channel_id' => insep_decode($channel_id),
            'status' => 'enabled'
        ))->row();
        if ($bk_details) {
            $airbnbData['user_id']    = current_user_type();
            $airbnbData['hotel_id']   = hotel_id();
            $airbnbData['channel_id'] = insep_decode($channel_id);

            include_once('cURL.php');
            $curl           = new cURL();
	    $bk_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            $url            = 'https://api.airbnb.com/v2/listings?currency=USD&locale=en-US&_limit=50&_offset=0&has_availability=false&client_id=' . $bk_details->other_id . '&user_id=' . $bk_details->hotel_channel_id . '&_format=v1_legacy_long';
            $roomTypeResult = $curl->get($url);
            $resStr = "airbnb_xml Room Request-response: $url---<br /><br />$roomTypeResult<br>";
            //print "resStr=>$resStr";
            mail("datahernandez@gmail.com"," airbnb.Com Request and Response ".hotel_id(),$roomTypeResult );
            //$this->showResultAirbnb('airbnb_xml',$resStr,3);
            $listings = array();
            if (preg_match('/({\s*"metadata".*)/is', $roomTypeResult, $match)) {
                $listings = json_decode($match[1], true);

            } elseif (preg_match('/({\s*"listings".*)/is', $roomTypeResult, $match)) {
                $listings = json_decode(trim($match[1]), true);
            }
            if (count($listings) > 0) {
                foreach ($listings['listings'] as $key => $value) {
                    $id   = '';
                    $name = '';
                    if (array_key_exists('id', $value)) {
                        $id = $value['id'];
                    }
                    if (array_key_exists('name', $value)) {
                        $name = $value['name'];
                    }
                    if ($id != '' and $name != '') {
                        $finalRoomArr[trim($id)] = utf8_decode(trim($name));
                    }
                }
            } else {
                $outStr = 'Unable to fetch Rooms.';
            }
            if (preg_match('/error_message\W+(.*?)"/is', $roomTypeResult, $match)) {
                $outStr = $match[1];
            }

            if (isset($finalRoomArr)) {
                if (count($finalRoomArr) > 0) {
                    foreach ($finalRoomArr as $room_id => $roomname) {
                        $airbnbData['RoomId']   = $room_id;
                        $airbnbData['RoomName'] = $roomname;
                        $room_available         = get_data(IM_AIRBNB, array(
                            'user_id' => current_user_type(),
                            'hotel_id' => hotel_id(),
                            'channel_id' => insep_decode($channel_id),
                            'RoomId' => $room_id,
                            'RoomName' => $roomname
                        ))->row_array();
                        if (count($room_available) != 0) {
                            ;
                            //update_data(IM_AIRBNB,$airbnbData,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$airbnbData['HotelCode'],'InvTypeCode'=>$airbnbData['InvTypeCode'],'RatePlanCode'=>$airbnbData['RatePlanCode']));
                        } else {
                            insert_data(IM_AIRBNB, $airbnbData);
                        }
                    }
                    return 'Insert';
                } else {
                    return $outStr;
                }
            }
            return $outStr.'No Rooms Found';
        } else {
            return 'Enable';
        }
    }

    function mapping_settings($channel_id)
    {

        $data['airbnb']          = $this->airbnb_model->get_mapping_rooms($channel_id);
        $booking_all             = $this->airbnb_model->get_all_mapping_rooms($channel_id);
        $data['channel_details'] = $this->airbnb_model->get_all_mapped_rooms($channel_id);
        if ($booking_all == '0') {
            $data['import_need'] = " Need to import the room for mapping!!!";
        }
        $user_details = get_data(TBL_USERS, array(
            'user_id' => user_id()
        ))->row_array();
        $data         = array_merge($user_details, $data);
        return $data;
    }

    function maptochannel($channel_id, $property_id)
    {

        require_once(APPPATH.'models/airbnb_model.php'); 
        $airbnb_model         =   new airbnb_model();
        $data['available']      = get_data(IM_AIRBNB, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => insep_decode($channel_id)
        ))->row_array();
        $data['mapping_values'] = get_data("mapping_values", array(
            'mapping_id' => insep_decode($property_id)
        ))->row_array();

        $data['airbnb']         = $airbnb_model->get_mapping_rooms(insep_decode($channel_id), 'update');
        $user_details           = get_data(TBL_USERS, array(
            'user_id' => user_id()
        ))->row_array();
        $data                   = array_merge($user_details, $data);
        return $data;
    }

    function importRates($channel_id, $propertyid, $rate_id, $guest_count, $refun_type, $mappingid = "", $arrival = "", $departure = "", $mapping = '')
    {
        if (admin_id() == '') {
            $this->is_login();
        } else {
            $this->is_admin();
        }
        $this->importAvailabilities($channel_id, $propertyid, $rate_id, $guest_count, $refun_type, $mappingid, $arrival = "", $departure = "", $mapping = '', 'importRates');
    }

    function importAvailabilities($channel_id, $propertyid, $rate_id, $guest_count, $refun_type, $mappingid = "", $arrival = "", $departure = "", $mapping = '', $importRates = '')
    {
        if (admin_id() == '') {
            $this->is_login();
        } else {
            $this->is_admin();
        }
        $property_id = insep_decode($propertyid);
        if ($mappingid != "") {
            $importDetails = get_data(MAP, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'property_id' => insep_decode($propertyid),
                'rate_id' => $rate_id,
                'channel_id' => insep_decode($channel_id),
                'guest_count' => $guest_count,
                'refun_type' => $refun_type,
                'import_mapping_id' => $mappingid
            ))->row_array();
        } else {
            $importDetails = get_data(MAP, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'property_id' => insep_decode($propertyid),
                'rate_id' => $rate_id,
                'channel_id' => insep_decode($channel_id),
                'guest_count' => $guest_count,
                'refun_type' => $refun_type
            ))->row_array();
        }
        if ($arrival != "" && $departure != "") {
            $start = date('d/m/Y', strtotime(str_replace('-', '/', $arrival)));
            $end   = date('d/m/Y', strtotime(str_replace('-', '/', $departure)));
        } else {
            $start_date          = date('d.m.Y');
            $end_date            = date('d.m.Y', strtotime("+30 days"));
            $exp_start_date      = date('Y-m-d');
            $exp_end_date        = date('Y-m-d', strtotime("+30 days"));
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date   = str_replace('-', '', $exp_end_date);
            $start               = date('d/m/Y');
            $end                 = date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id']  = insep_decode($channel_id);
        $channel['property_id'] = $property_id;
        $channel['rate_id']     = $rate_id;
        $channel['guest_count'] = $guest_count;
        $channel['refun_type']  = $refun_type;
        $channel['start']       = $start;
        $channel['end']         = $end;

        if (count($importDetails) != 0) {
            if ($importDetails['channel_id'] == 9) {
                $this->airbnb_model->importAvailabilities(current_user_type(), hotel_id(), $channel, $mapping, $importDetails['import_mapping_id'], $arrival, $departure, $importRates);
            }
            if ($mapping == "") {
                redirect('mapping/settings/' . $channel_id, 'refresh');
            } else {
                return true;
            }
        } else {
            if ($mapping == "") {
                redirect(base_url());
            } else {
                return true;
            }
        }
    }

    function getReservation($channel_id)
    {
        //$channel_id = insep_encode($channel_id);
        if (admin_id() == '') {
            $this->is_login();
        } else {
            $this->is_admin();
        }
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => insep_decode($channel_id),
            'status' => 'enabled'
        ))->row();
        //echo"ch_details=>$ch_details";
        $room_limit = 50;
		$refreshToken = 0;
        if ($ch_details) {
            //echo"gdasgfsfgshf";
            if (insep_decode($channel_id) == '9') {
                include_once('cURL.php');
				$curl						= new cURL(TRUE);
				if($ch_details->other_id != ''){
					$resultFile				= '"access_token":"'.$ch_details->other_id.'"';
					$ch_details->other_id   = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
				}else{
					$ch_details->other_id   = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';					
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
				}

                if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    $AuthToken  = $match[1];
					update_data(CONNECT, array('other_id'=>$AuthToken), array(
						'user_id' => current_user_type(),
						'hotel_id' => hotel_id(),
						'channel_id' => '9',
						'status' => 'enabled'
					));
                    $roomArray  = array();
                    $url        = 'https://api.airbnb.com/v2/listings?currency=USD&locale=en-US&_limit=' . $room_limit . '&_offset=0&has_availability=false&client_id=' . $ch_details->other_id . '&user_id=' . $ch_details->hotel_channel_id . '&_format=v1_legacy_long';
                    //print"url=>$url\n";
                    $resultFile = $curl->get($url);
		    $errormsg = '';
		    if(preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
			$errormsg .= trim($match[1]);
		    }
                    //print"resultFile=>$resultFile\n";
                    if ($resultFile != '') {
                        if (preg_match('/({"metadata".*)/is', $resultFile, $match) or preg_match('/({"listings".*)/is', $resultFile, $match)) {
                            $listings = json_decode($match[1], true);
                            if (count($listings) > 0) {
                                foreach ($listings['listings'] as $key => $value) {
                                    $id   = '';
                                    $name = '';
                                    if (array_key_exists('id', $value)) {
                                        $id = $value['id'];
                                    }
                                    if (array_key_exists('name', $value)) {
                                        $name = $value['name'];
                                    }
                                    if ($id != '' and $name != '') {
                                        $roomArray[trim($id)] = utf8_decode(trim($name));
                                    }
                                }
                            }
                        }
                    }
                    if(count($roomArray) == 0){
								$room_details = get_data(IM_AIRBNB, array(
									'user_id' => current_user_type(),
									'hotel_id' => hotel_id(),
									'channel_id' => insep_decode($channel_id)
								))->result();
								 if ($room_details) {
									foreach ($room_details as $row) {
										$roomArray[$row->RoomId] = $row->RoomName;
									}
								 }
							}
                    $startDt = date_create(date('Y-m-d'));
                    date_sub($startDt, date_interval_create_from_date_string("5 days"));
                    $startDt = date_format($startDt, "Y-m-d");

                    $endDt = date_create(date('Y-m-d'));
                    date_add($endDt, date_interval_create_from_date_string("365 days"));
                    $endDt = date_format($endDt, "Y-m-d");

                    foreach ($roomArray as $roomId => $roomName) {
                        $request          = '{"operations":[{"method":"GET","path":"/calendar_days","query":{"start_date":"' . $startDt . '","listing_id":"' . $roomId . '","_format":"host_calendar","end_date":"' . $endDt . '"}},{"method":"GET","path":"/dynamic_pricing_controls/' . $roomId . '","query":{}}]}';
                        $url              = 'https://api.airbnb.com/v2/batch/?client_id=' . $ch_details->other_id . '&locale=en-US&currency=USD';
                        $curl->headers[2] = 'Content-Type: application/json; charset=UTF-8';
                        $curl->headers[3] = 'X-Airbnb-OAuth-Token: ' . $AuthToken;
                        $resultFile       = $curl->post($url, $request);
			if(preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
			   $errormsg .= trim($match[1]);
		        }			
                        //print "<pre>";
                        //print"resultFile=>$resultFile\n\n";
						if (preg_match('/expired_token/is', $resultFile, $match)) {
							if($refreshToken == 0){
								$refreshToken			= 1;
								$tokenurl               = 'https://api.airbnb.com/v1/authorize';
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
								$tokenresultFile = $curl->post($tokenurl, $content);

								if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
									$AuthToken		  = $match[1];
									update_data(CONNECT, array('other_id'=>$AuthToken), array(
										'user_id' => current_user_type(),
										'hotel_id' => hotel_id(),
										'channel_id' => '9',
										'status' => 'enabled'
									));
									$request          = '{"operations":[{"method":"GET","path":"/calendar_days","query":{"start_date":"' . $startDt . '","listing_id":"' . $roomId . '","_format":"host_calendar","end_date":"' . $endDt . '"}},{"method":"GET","path":"/dynamic_pricing_controls/' . $roomId . '","query":{}}]}';
									$url              = 'https://api.airbnb.com/v2/batch/?client_id=' . $ch_details->other_id . '&locale=en-US&currency=USD';
									$curl->headers[2] = 'Content-Type: application/json; charset=UTF-8';
									$curl->headers[3] = 'X-Airbnb-OAuth-Token: ' . $AuthToken;
									$resultFile       = $curl->post($url, $request);
									if(preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
									    $errormsg .= trim($match[1]);
								        }
								}
							}
						}
                        if (preg_match('/"native_currency":"(.*?)"/is', $resultFile, $match)) {
                            $currency = $match[1];
                        }
						
                        while (preg_match('/\"reservation\":{(.*?)\"date\"/is', $resultFile, $match)) {
                            $tempFile   = $match[1];
                            $resultFile = after($match[0], $resultFile);
                            $bkId       = '';
                            if (preg_match('/"confirmation_code":"(.*?)"/is', $tempFile, $match)) {
                                $bkId = $match[1];
                            }

                            $rqDt = '';
                            if (preg_match('/"start_date":"(.*?)"/is', $tempFile, $match)) {
                                $rqDt = $match[1];
                            }

                            $desc = '';
                            if (preg_match('/"status":"(.*?)"/is', $tempFile, $match)) {
                                $desc = $match[1];
                            }

                            $guest_name = '';
                            if (preg_match('/"full_name":"(.*?)"/is', $tempFile, $match)) {
                                $guest_name = $match[1];
                            }
				if($guest_name == ''){
					if (preg_match('/"first_name":"(.*?)"/is', $tempFile, $match)) {
						$guest_name = $match[1];
					}
				}

                            $nights = '';
                            if (preg_match('/"nights":(\d+),/is', $tempFile, $match)) {
                                $nights = $match[1];
                            }

                            $guests = '';
                            if (preg_match('/"number_of_guests":(\d+),/is', $tempFile, $match)) {
                                $guests = $match[1];
                            }

                            $total = '';
                            if (preg_match('/"formatted_host_base_price":"(.*?)",/is', $tempFile, $match)) {
                                $total = $match[1];
                                $total = preg_replace('/[^0-9.]/', '', $total);
                            }
                            //print"rqDt=>$rqDt\nbkId=>$bkId\n";

                            if ($bkId != '') {
                                if (dateCompare($rqDt, date('Y-m-d')) >= 0) {
                                    $endDt = date_create($rqDt);
                                    date_add($endDt, date_interval_create_from_date_string($nights . " days"));
                                    $endDt                    = date_format($endDt, "Y/m/d");
                                    /* Basic Details Start */
                                    $airbnbdata['user_id']    = current_user_type();
                                    $airbnbdata['hotel_id ']  = hotel_id();
                                    $airbnbdata['channel_id'] = '9';
                                    /* Basic Details End */

                                    /* Room Attributes Start */
                                    if (!preg_match('/accepted/is', $desc)) {
										$desc = 'Cancelled';
									} else {
										$desc = 'Confirmed';
									}
                                    $checkin                    = date('Y/m/d', strtotime($rqDt));
                                    $checkout                   = date('Y/m/d', strtotime($endDt));
                                    $airbnbdata['ResStatus']    = $desc;
                                    $airbnbdata['arrival']      = $checkin;
                                    $airbnbdata['departure']    = $checkout;
                                    $airbnbdata['name']         = $guest_name;
                                    $airbnbdata['Currency']     = $currency;
                                    $airbnbdata['baseRate']     = $total;
                                    $airbnbdata['Adult']        = $guests;
									$airbnbdata['AmountAfterTax'] = $total;
                                    $airbnbdata['ResID_Value']  = $bkId;
                                    $airbnbdata['HotelCode']    = $ch_details->hotel_channel_id;
                                    $airbnbdata['RoomTypeCode'] = $roomId;
                                    /* Room ResGlobalInfo Attributes End */
                                    /* echo '<pre>';
                                    print_r($airbnbdata); */
                                    //print"airbnbdata=>";print_r($airbnbdata);

                                    $array_keys = array_keys($airbnbdata);

                                    fetchColumn(airbnb_RESER, $array_keys);

                                    $available = get_data(airbnb_RESER, array(
                                        'user_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'ResID_Value' => $airbnbdata['ResID_Value'],
                                        'HotelCode' => $airbnbdata['HotelCode']
                                    ))->row_array();

                                    if (count($available) == 0) {
                                        insert_data(airbnb_RESER, $airbnbdata);
                                    } else {
                                        update_data(airbnb_RESER, $airbnbdata, array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'ResID_Value' => $airbnbdata['ResID_Value'],
                                            'HotelCode' => $airbnbdata['HotelCode']
                                        ));
                                    }
                                    $checkin                      = date('Y/m/d', strtotime($airbnbdata['arrival']));
                                    $checkout                     = date('Y/m/d', strtotime($airbnbdata['departure']));
                                    $nig                          = _datebetween($checkin, $checkout);
                                    $avdata['user_id']            = current_user_type();
                                    $avdata['hotel_id']           = hotel_id();
                                    $avdata['channel_id']         = '9';
                                    $avdata['channel_hotel_id']   = $airbnbdata['HotelCode'];
                                    $avdata['reservation_id']     = $airbnbdata['ResID_Value'];
                                    $avdata['start']              = $airbnbdata['arrival'];
                                    $avdata['end']                = $airbnbdata['departure'];
                                    $avdata['relation_one']       = $airbnbdata['RoomTypeCode'];
                                    $avdata['relation_two']       = '';
                                    $avdata['difference']         = $nig;
                                    $avdata['reservation_status'] = $airbnbdata['ResStatus'];
                                    //print_r($avdata);
                                    insert_data(UAVL, $avdata);
                                    $this->updateAvailability($airbnbdata['HotelCode'], $source = 'Manual');
                                }
                            }
                        }
                    }
                    if($errormsg != ''){
			$data['Error'] = $errormsg;
		    }else{
			$data['succes'] = 'Insert';
		    }
                } elseif (preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
                    $data['Error'] = trim($match[1]) . ' from Airbnb Try again!';
                } else {
                    $data['Error'] = 'Unknow Error From Airbnb';
                }
            } else {
                $data['Enable'] = 'Enable';
            }
        } else {
            $data['Enable'] = 'Enable';
        }
        return $data;
    }

    function getReservationCron($channel_id='9')
    {
        if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) {

        } else {
            $Errors ='';
            if (insep_decode($channel_id) == '9') {

                $airbnbUserDetails = get_data(CONNECT, array(
                    'channel_id' => 9,
                    'status' => 'enabled'
                ), 'user_name,user_password,hotel_channel_id')->result();
                /* echo '<pre>';
                print_r($airbnbUserDetails); die; */
                include_once('cURL.php');
                $curl       = new cURL(TRUE);
                $room_limit = 50;
				
                if ($airbnbUserDetails) {
                    foreach ($airbnbUserDetails as $ch_details) {
						$refreshToken = 0;
						makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt',"Authorize\n");
                        if($ch_details->other_id != ''){
							$resultFile				= '"access_token":"'.$ch_details->other_id.'"';
							$ch_details->other_id   = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
						}else{
							$ch_details->other_id   = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';							
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
							makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt'," URL=>$url\nContent=>$content\nResponse=>$resultFile\n");
						}

                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                            $AuthToken  = $match[1];
							update_data(CONNECT, array('other_id'=>$AuthToken), array(
								'user_id' => current_user_type(),
								'hotel_id' => hotel_id(),
								'channel_id' => '9',
								'status' => 'enabled'
							));
                            $roomArray  = array();
                            $url        = 'https://api.airbnb.com/v2/listings?currency=USD&locale=en-US&_limit=' . $room_limit . '&_offset=0&has_availability=false&client_id=' . $ch_details->other_id . '&user_id=' . $ch_details->hotel_channel_id . '&_format=v1_legacy_long';
                            //print"url=>$url\n";
                            $resultFile = $curl->get($url);
							makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt'," URL1=>$url\nResponse=>$resultFile\n");
			    $errormsg = '';
			    if(preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
			      $errormsg .= trim($match[1]);
			    }
                            if ($resultFile != '') {
                                if (preg_match('/({"metadata".*)/is', $resultFile, $match) or preg_match('/({"listings".*)/is', $resultFile, $match)) {
                                    $listings = json_decode($match[1], true);
                                    if (count($listings) > 0) {
                                        foreach ($listings['listings'] as $key => $value) {
                                            $id   = '';
                                            $name = '';
                                            if (array_key_exists('id', $value)) {
                                                $id = $value['id'];
                                            }
                                            if (array_key_exists('name', $value)) {
                                                $name = $value['name'];
                                            }
                                            if ($id != '' and $name != '') {
                                                $roomArray[trim($id)] = utf8_decode(trim($name));
                                            }
                                        }
                                    }
                                }
                            }
                            if(count($roomArray) == 0){
								$room_details = get_data(IM_AIRBNB, array(
									'user_id' => current_user_type(),
									'hotel_id' => hotel_id(),
									'channel_id' => insep_decode($channel_id)
								))->result();
								 if ($room_details) {
									foreach ($room_details as $row) {
										$roomArray[$row->RoomId] = $row->RoomName;
									}
								 }
							}
                            $startDt = date_create(date('Y-m-d'));
                            date_sub($startDt, date_interval_create_from_date_string("5 days"));
                            $startDt = date_format($startDt, "Y-m-d");

                            $endDt = date_create(date('Y-m-d'));
                            date_add($endDt, date_interval_create_from_date_string("365 days"));
                            $endDt = date_format($endDt, "Y-m-d");

                            foreach ($airbnb->roomArray as $roomId => $roomName) {
                                $request          = '{"operations":[{"method":"GET","path":"/calendar_days","query":{"start_date":"' . $startDt . '","listing_id":"' . $roomId . '","_format":"host_calendar","end_date":"' . $endDt . '"}},{"method":"GET","path":"/dynamic_pricing_controls/' . $roomId . '","query":{}}]}';
                                $url              = 'https://api.airbnb.com/v2/batch/?client_id=' . $ch_details->other_id . '&locale=en-US&currency=USD';
                                $curl->headers[2] = 'Content-Type: application/json; charset=UTF-8';
                                $curl->headers[3] = 'X-Airbnb-OAuth-Token: ' . $AuthToken;
                                $result           = $curl->post($url, $request);
								makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt'," URL2=>$url\nContent=>$request\nResponse=>$resultFile\n");
			        if(preg_match('/"error_message":"(.*?)"/is', $result, $match)) {
					$errormsg .= trim($match[1]);
				}
								if (preg_match('/expired_token/is', $result, $match)) {
									if($refreshToken == 0){
										$refreshToken			= 1;
										$tokenurl               = 'https://api.airbnb.com/v1/authorize';
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
										$tokenresultFile = $curl->post($tokenurl, $content);
										makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt'," tokenresultFile URL=>$tokenurl\nContent=>$content\nResponse=>$tokenresultFile\n");
										if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
											$AuthToken		  = $match[1];
											update_data(CONNECT, array('other_id'=>$AuthToken), array(
												'user_id' => current_user_type(),
												'hotel_id' => hotel_id(),
												'channel_id' => '9',
												'status' => 'enabled'
											));
											$request          = '{"operations":[{"method":"GET","path":"/calendar_days","query":{"start_date":"' . $startDt . '","listing_id":"' . $roomId . '","_format":"host_calendar","end_date":"' . $endDt . '"}},{"method":"GET","path":"/dynamic_pricing_controls/' . $roomId . '","query":{}}]}';
											$url              = 'https://api.airbnb.com/v2/batch/?client_id=' . $ch_details->other_id . '&locale=en-US&currency=USD';
											$curl->headers[2] = 'Content-Type: application/json; charset=UTF-8';
											$curl->headers[3] = 'X-Airbnb-OAuth-Token: ' . $AuthToken;
											$result           = $curl->post($url, $request);
											makeLog('AirbnbResv_RespLog_'.$ch_details->hotel_id.'.txt'," result URL=>$tokenurl\nContent=>$request\nResponse=>$result\n");
											if(preg_match('/"error_message":"(.*?)"/is', $result, $match)) {
												$errormsg .= trim($match[1]);
											}
										}
									}
								}
                                if (preg_match('/"native_currency":"(.*?)"/is', $resultFile, $match)) {
                                    $currency = $match[1];
                                }
                                while (preg_match('/\"reservation\":{(.*?)\"date\"/is', $resultFile, $match)) {
                                    $tempFile   = $match[1];
                                    $resultFile = after($match[0], $resultFile);
                                    $bkId       = '';
                                    if (preg_match('/"confirmation_code":"(.*?)"/is', $tempFile, $match)) {
                                        $bkId = $match[1];
                                    }

                                    $rqDt = '';
                                    if (preg_match('/"start_date":"(.*?)"/is', $tempFile, $match)) {
                                        $rqDt = $match[1];
                                    }

                                    $desc = '';
                                    if (preg_match('/"status":"(.*?)"/is', $tempFile, $match)) {
                                        $desc = $match[1];
                                    }

                                    $guest_name = '';
                                    if (preg_match('/"full_name":"(.*?)"/is', $tempFile, $match)) {
                                        $guest_name = $match[1];
                                    }
					
				    if($guest_name == ''){
				         if (preg_match('/"first_name":"(.*?)"/is', $tempFile, $match)) {
				           $guest_name = $match[1];
				        }
				   }

                                    $nights = '';
                                    if (preg_match('/"nights":(\d+),/is', $tempFile, $match)) {
                                        $nights = $match[1];
                                    }

                                    $guests = '';
                                    if (preg_match('/"number_of_guests":(\d+),/is', $tempFile, $match)) {
                                        $guests = $match[1];
                                    }

                                    $total = '';
                                    if (preg_match('/"formatted_host_base_price":"(.*?)",/is', $tempFile, $match)) {
                                        $total = $match[1];
                                        $total = preg_replace('/[^0-9.]/', '', $total);
                                    }
                                    if ($bkId != '') {
                                        if (dateCompare1($rqDt, date('d/m/Y')) >= 0) {

                                            $endDt = date_create($rqDt);
                                            date_add($endDt, date_interval_create_from_date_string($nights . " days"));
                                            date_format($endDt, "Y/m/d");
                                            /* Basic Details Start */
                                            $airbnbdata['user_id']    = $ch_details->user_id;
                                            $airbnbdata['hotel_id ']  = $ch_details->hotel_id;
                                            $airbnbdata['channel_id'] = '9';
                                            /* Basic Details End */

                                            /* Room Attributes Start */
                                            if (!preg_match('/accepted/is', $desc)) {
                                                $desc = 'Cancelled';
                                            } else {
                                                $desc = 'Confirmed';
                                            }
                                            $checkin                    = date('Y/m/d', strtotime($rqDt));
                                            $checkout                   = date('Y/m/d', strtotime($endDt));
                                            $airbnbdata['ResStatus']    = $desc;
                                            $airbnbdata['arrival']      = $checkin;
                                            $airbnbdata['departure']    = $checkout;
                                            $airbnbdata['name']         = $guest_name;
                                            $airbnbdata['Currency']     = $currency;
                                            $airbnbdata['baseRate']     = $total;
                                            $airbnbdata['Adult']        = $guests;
											$airbnbdata['AmountAfterTax'] = $total;
                                            $airbnbdata['ResID_Value']  = $bkId;
                                            $airbnbdata['HotelCode']    = $ch_details->hotel_channel_id;
                                            $airbnbdata['RoomTypeCode'] = $roomId;
                                            /* Room ResGlobalInfo Attributes End */
                                            /* echo '<pre>';
                                            print_r($airbnbdata); */

                                            $array_keys = array_keys($airbnbdata);

                                            fetchColumn(airbnb_RESER, $array_keys);

                                            $available = get_data(airbnb_RESER, array(
                                                'user_id' => $airbnbdata['user_id'],
                                                'hotel_id' => $airbnbdata['hotel_id'],
                                                'ResID_Value' => $airbnbdata['ResID_Value'],
                                                'HotelCode' => $airbnbdata['HotelCode']
                                            ))->row_array();

                                            if (count($available) == 0) {
                                                insert_data(airbnb_RESER, $airbnbdata);
                                            } else {
                                                update_data(airbnb_RESER, $airbnbdata, array(
                                                    'user_id' => $airbnbdata['user_id'],
                                                    'hotel_id' => $airbnbdata['hotel_id'],
                                                    'ResID_Value' => $airbnbdata['ResID_Value'],
                                                    'HotelCode' => $airbnbdata['HotelCode']
                                                ));
                                            }
                                            $checkin                      = date('Y/m/d', strtotime($airbnbdata['arrival']));
                                            $checkout                     = date('Y/m/d', strtotime($airbnbdata['departure']));
                                            $nig                          = _datebetween($checkin, $checkout);
                                            $avdata['user_id']            = current_user_type();
                                            $avdata['hotel_id']           = hotel_id();
                                            $avdata['channel_id']         = '9';
                                            $avdata['channel_hotel_id']   = $airbnbdata['HotelCode'];
                                            $avdata['reservation_id']     = $airbnbdata['ResID_Value'];
                                            $avdata['start']              = $airbnbdata['arrival'];
                                            $avdata['end']                = $airbnbdata['departure'];
                                            $avdata['relation_one']       = $airbnbdata['RoomTypeCode'];
                                            $avdata['relation_two']       = $airbnbdata['RatePlanCode'];
                                            $avdata['difference']         = $nig;
                                            $avdata['reservation_status'] = $airbnbdata['ResStatus'];
                                            insert_data(UAVL, $avdata);
                                            $this->updateAvailability($airbnbdata['HotelCode'], $source = 'Manual');
                                        }
                                    }
                                }
                            }
			   if($errormsg != ''){
			       $data['Error'] = $errormsg;
			   }else{
			       $data['succes'] = 'Insert';
			   }
                        } elseif (preg_match('/"error_message":"(.*?)"/is', $resultFile, $match)) {
                            $meg['result']  = '0';
                            $meg['content'] = trim($match[1]) . ' from Airbnb Try again!';
                            echo json_encode($meg);
                        } else {
                            $meg['result']  = '0';
                            $meg['content'] = $Errors . ' from Airbnb Try again!';
                            echo json_encode($meg);
                        }
                    }
                }
            } else {
                $meg['result']  = '0';
                $meg['content'] = $Errors . ' from Airbnb Try again!';
                echo json_encode($meg);
            }
        }
    }

    function updateAvailability($HotelCode, $source)
    {
        if ($source == 'Manual') {
            if (admin_id() == '') {
                $this->is_login();
            } else {
                $this->is_admin();
            }
        }

        $userDetails = get_data(CONNECT, array(
            'channel_id' => 9,
            'hotel_channel_id' => $HotelCode,
            'status' => 'enabled'
        ), 'user_id,hotel_id')->row_array();

        if ($userDetails) {
            $getRooms = get_data(UAVL, array(
                'user_id' => $userDetails['user_id'],
                'hotel_id' => $userDetails['hotel_id'],
                'channel_id' => 9,
                'channel_hotel_id' => $HotelCode,
                'status' => 1
            ))->result_array();
            /* echo '<pre>';
            print_r($getRooms);
            die; */
            //print_r($getRooms);
            if ($getRooms) {
                foreach ($getRooms as $getRoomsVal) {
                    //print_r($getRoomsVal);
                    extract($getRoomsVal);

                    if ($reservation_status != 'Cancel') {
                        $updateOldRoom = get_data(UAVL, array(
                            'user_id' => $user_id,
                            'hotel_id' => $hotel_id,
                            'channel_id' => 9,
                            'channel_hotel_id' => $channel_hotel_id,
                            'status' => 1,
                            'reservation_id' => $reservation_id
                        ))->row_array();
                    } else {
                        $updateOldRoom = array();
                    }
                    //print "OLD ROOMDATA=>";print_r($updateOldRoom);
                    if (count($updateOldRoom) != 0) {
                        ##$getRoomRelation    =    get_data(IM_AIRBNB,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>9,'HotelCode'=>$channel_hotel_id,'InvTypeCode'=>$updateOldRoom['relation_one'],'RatePlanCode'=>$updateOldRoom['relation_two']),'import_mapping_id')->row_array();
                        $getRoomRelation = get_data(IM_AIRBNB, array(
                            'user_id' => $user_id,
                            'hotel_id' => $hotel_id,
                            'channel_id' => 9,
                            'RoomId' => $updateOldRoom['relation_one']
                        ), 'import_mapping_id')->row_array();

                        if ($getRoomRelation) {
                            $getMappedRooms = get_data(MAP, array(
                                'owner_id' => $user_id,
                                'hotel_id' => $hotel_id,
                                'channel_id' => 9,
                                'import_mapping_id' => $getRoomRelation['import_mapping_id']
                            ), 'property_id,rate_id,guest_count,refun_type')->row_array();

                            if ($getMappedRooms) {
                                if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] == '0' && $getMappedRooms['guest_count'] == '0' && $getMappedRooms['refun_type'] == '0') {
                                    //print"updateOldRoom=>";print_r($updateOldRoom);
                                    $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                    $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                    //print"startDate=>$startDate\nendDate=>$endDate\n";
                                    $periodInterval = new DateInterval("P1D");
                                    $period_old_1   = new DatePeriod($startDate, $periodInterval, $endDate);

                                    foreach ($period_old_1 as $date) {
                                        $date = $date->format("d/m/Y");

                                        $available_update_details = get_data(TBL_UPDATE, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date
                                        ), 'room_update_id,availability')->row_array();

                                        if (count($available_update_details) != 0) {
                                            $value = $available_update_details['availability'] + $updateOldRoom['difference'];

                                            $opr = '+';

                                            $ch_update_data['trigger_cal'] = 1;

                                            $ch_update_data['availability'] = $value;

                                            update_data(TBL_UPDATE, $ch_update_data, array(
                                                'owner_id' => $user_id,
                                                'hotel_id' => $hotel_id,
                                                'room_id' => $getMappedRooms['property_id'],
                                                'individual_channel_id' => 9,
                                                'separate_date' => $date
                                            ));

                                            $this->db->where('room_update_id !=', $available_update_details['room_update_id']);
                                            $this->db->where('owner_id', $user_id);
                                            $this->db->where('hotel_id', $hotel_id);
                                            $this->db->where('room_id', $getMappedRooms['property_id']);
                                            $this->db->where('separate_date', $date);
                                            $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' >=0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                            $this->db->update(TBL_UPDATE);
                                        }
                                    }
                                } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] == '0' && $getMappedRooms['guest_count'] != '0' && $getMappedRooms['refun_type'] != '0') {
                                    $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                    $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                    $periodInterval = new DateInterval("P1D");
                                    $period_old_2   = new DatePeriod($startDate, $periodInterval, $endDate);

                                    foreach ($period_old_2 as $date) {
                                        $date = $date->format("d/m/Y");

                                        $available_update_details_RESERV = get_data(RESERV, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date,
                                            'guest_count' => $getMappedRooms['guest_count'],
                                            'refun_type' => $getMappedRooms['refun_type']
                                        ), 'reserv_price_id,availability')->row();

                                        if (count($available_update_details_RESERV) != 0) {
                                            $value = $available_update_details_RESERV['availability'] + $updateOldRoom['difference'];

                                            $opr = '+';

                                            $ch_update_data['trigger_cal'] = 1;

                                            $ch_update_data['availability'] = $value;

                                            update_data(RESERV, $ch_update_data, array(
                                                'owner_id' => $user_id,
                                                'hotel_id' => $hotel_id,
                                                'room_id' => $getMappedRooms['property_id'],
                                                'individual_channel_id' => 9,
                                                'separate_date' => $date,
                                                'guest_count' => $getMappedRooms['guest_count'],
                                                'refun_type' => $getMappedRooms['refun_type']
                                            ));

                                            $this->db->where('reserv_price_id !=', $available_update_details_RESERV['reserv_price_id']);
                                            $this->db->where('owner_id', $user_id);
                                            $this->db->where('hotel_id', $hotel_id);
                                            $this->db->where('room_id', $getMappedRooms['property_id']);
                                            $this->db->where('separate_date', $date);
                                            $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' >=0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                            $this->db->update(RESERV);
                                        }
                                    }
                                } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] != '0' && $getMappedRooms['guest_count'] == '0' && $getMappedRooms['refun_type'] == '0') {
                                    $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                    $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                    $periodInterval = new DateInterval("P1D");
                                    $period_old_3   = new DatePeriod($startDate, $periodInterval, $endDate);

                                    foreach ($period_old_3 as $date) {
                                        $date = $date->format("d/m/Y");

                                        $available_update_details_RATE_BASE = get_data(RATE_BASE, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'rate_types_id' => $getMappedRooms['rate_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date
                                        ), 'room_update_id,availability')->row_array();

                                        if (count($available_update_details_RATE_BASE) != 0) {
                                            $value = $available_update_details_RATE_BASE['availability'] + $updateOldRoom['difference'];

                                            $opr = '+';

                                            $ch_update_data_RATE_BASE['trigger_cal'] = 1;

                                            $ch_update_data_RATE_BASE['availability'] = $value;

                                            update_data(RATE_BASE, $ch_update_data_RATE_BASE, array(
                                                'owner_id' => $user_id,
                                                'hotel_id' => $hotel_id,
                                                'room_id' => $getMappedRooms['property_id'],
                                                'rate_types_id' => $getMappedRooms['rate_id'],
                                                'individual_channel_id' => 9,
                                                'separate_date' => $date
                                            ));

                                            $this->db->where('room_update_id !=', $available_update_details_RATE_BASE['room_update_id']);
                                            $this->db->where('owner_id', $user_id);
                                            $this->db->where('hotel_id', $hotel_id);
                                            $this->db->where('room_id', $getMappedRooms['property_id']);
                                            $this->db->where('separate_date', $date);
                                            $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' >=0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                            $this->db->update(RATE_BASE);

                                        }
                                    }
                                } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] != '0' && $getMappedRooms['guest_count'] != '0' && $getMappedRooms['refun_type'] != '0') {
                                    $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                    $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                    $periodInterval = new DateInterval("P1D");
                                    $period_old_4   = new DatePeriod($startDate, $periodInterval, $endDate);

                                    foreach ($period_old_4 as $date) {
                                        $date = $date->format("d/m/Y");

                                        $available_update_details_RATE_ADD = get_data(RATE_ADD, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'rate_types_id' => $getMappedRooms['rate_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date,
                                            'guest_count' => $getMappedRooms['guest_count'],
                                            'refun_type' => $getMappedRooms['refun_type']
                                        ), 'reserv_price_id,availability')->row_array();

                                        if (count($available_update_details_RATE_ADD) != 0) {
                                            $value = $available_update_details_RATE_ADD['availability'] + $updateOldRoom['difference'];

                                            $opr = '+';

                                            $ch_update_data_RATE_ADD['trigger_cal'] = 1;

                                            $ch_update_data_RATE_ADD['availability'] = $value;

                                            update_data(RATE_ADD, $ch_update_data_RATE_ADD, array(
                                                'owner_id' => $user_id,
                                                'hotel_id' => $hotel_id,
                                                'room_id' => $getMappedRooms['property_id'],
                                                'rate_types_id' => $getMappedRooms['rate_id'],
                                                'individual_channel_id' => 9,
                                                'separate_date' => $date,
                                                'guest_count' => $getMappedRooms['guest_count'],
                                                'refun_type' => $getMappedRooms['refun_type']
                                            ));

                                            $this->db->where('reserv_price_id !=', $available_update_details_RATE_ADD['reserv_price_id']);
                                            $this->db->where('owner_id', $user_id);
                                            $this->db->where('hotel_id', $hotel_id);
                                            $this->db->where('room_id', $getMappedRooms['property_id']);
                                            $this->db->where('separate_date', $date);
                                            $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' >=0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $updateOldRoom['difference'] . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                            $this->db->update(RATE_ADD);
                                        }
                                    }
                                }
                                ##$this->availability_model->updateAvailabilityBNOW($updateOldRoom['start'],$updateOldRoom['end'],$user_id,$hotel_id,$getMappedRooms['property_id'],'9');
                            }
                        }

                        delete_data(UAVL, array(
                            'column_id' => $updateOldRoom['column_id']
                        ));
                    }

                    ##$getRoomRelation    =    get_data(IM_AIRBNB,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>9,'HotelCode'=>$channel_hotel_id,'InvTypeCode'=>$relation_one,'RatePlanCode'=>$relation_two),'import_mapping_id')->row_array();
                    $getRoomRelation = get_data(IM_AIRBNB, array(
                        'user_id' => $user_id,
                        'hotel_id' => $hotel_id,
                        'channel_id' => 9,
                        'RoomId' => $relation_one
                    ), 'import_mapping_id')->row_array();

                    if ($getRoomRelation) {
                        $getMappedRooms = get_data(MAP, array(
                            'owner_id' => $user_id,
                            'hotel_id' => $hotel_id,
                            'channel_id' => 9,
                            'import_mapping_id' => $getRoomRelation['import_mapping_id']
                        ), 'property_id,rate_id,guest_count,refun_type')->row_array();

                        if ($getMappedRooms) {
                            $start = $updateOldRoom['start'];
                            $end   = $updateOldRoom['end'];
                            if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] == '0' && $getMappedRooms['guest_count'] == '0' && $getMappedRooms['refun_type'] == '0') {
                                $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                $periodInterval = new DateInterval("P1D");
                                //print"periodInterval=>".$periodInterval;
                                $period_new_1   = new DatePeriod($startDate, $periodInterval, $endDate);

                                foreach ($period_new_1 as $date) {
                                    $date = $date->format("d/m/Y");

                                    $available_update_details = get_data(TBL_UPDATE, array(
                                        'owner_id' => $user_id,
                                        'hotel_id' => $hotel_id,
                                        'room_id' => $getMappedRooms['property_id'],
                                        'individual_channel_id' => 9,
                                        'separate_date' => $date
                                    ), 'room_update_id,availability')->row_array();

                                    if (count($available_update_details) != 0) {
                                        if ($reservation_status == 'Cancel') {
                                            $opr   = '+';
                                            $value = $available_update_details['availability'] + $difference;
                                        } else {
                                            $opr   = '-';
                                            $value = $available_update_details['availability'] - $difference;
                                        }

                                        $ch_update_data['trigger_cal'] = 1;

                                        $ch_update_data['availability'] = $value;

                                        update_data(TBL_UPDATE, $ch_update_data, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date
                                        ));

                                        $this->db->where('room_update_id !=', $available_update_details['room_update_id']);
                                        $this->db->where('owner_id', $user_id);
                                        $this->db->where('hotel_id', $hotel_id);
                                        $this->db->where('room_id', $getMappedRooms['property_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $difference . ' >=0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                        $this->db->update(TBL_UPDATE);
                                    }
                                }
                            } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] == '0' && $getMappedRooms['guest_count'] != '0' && $getMappedRooms['refun_type'] != '0') {
                                $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                $periodInterval = new DateInterval("P1D");
                                $period_new_2   = new DatePeriod($startDate, $periodInterval, $endDate);

                                foreach ($period_new_2 as $date) {
                                    $date = $date->format("d/m/Y");

                                    $available_update_details_RESERV = get_data(RESERV, array(
                                        'owner_id' => $user_id,
                                        'hotel_id' => $hotel_id,
                                        'room_id' => $getMappedRooms['property_id'],
                                        'individual_channel_id' => 9,
                                        'separate_date' => $date,
                                        'guest_count' => $getMappedRooms['guest_count'],
                                        'refun_type' => $getMappedRooms['refun_type']
                                    ), 'reserv_price_id,availability')->row();

                                    if (count($available_update_details_RESERV) != 0) {
                                        if ($reservation_status == 'Cancel') {
                                            $opr   = '+';
                                            $value = $available_update_details_RESERV['availability'] + $difference;
                                        } else {
                                            $opr   = '-';
                                            $value = $available_update_details_RESERV['availability'] - $difference;
                                        }

                                        $ch_update_data['trigger_cal'] = 1;

                                        $ch_update_data['availability'] = $value;

                                        update_data(RESERV, $ch_update_data, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date,
                                            'guest_count' => $getMappedRooms['guest_count'],
                                            'refun_type' => $getMappedRooms['refun_type']
                                        ));

                                        $this->db->where('reserv_price_id !=', $available_update_details_RESERV['reserv_price_id']);
                                        $this->db->where('owner_id', $user_id);
                                        $this->db->where('hotel_id', $hotel_id);
                                        $this->db->where('room_id', $getMappedRooms['property_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $difference . ' >=0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                        $this->db->update(RESERV);
                                    }
                                }
                            } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] != '0' && $getMappedRooms['guest_count'] == '0' && $getMappedRooms['refun_type'] == '0') {
                                $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                $periodInterval = new DateInterval("P1D");
                                $period_new_3   = new DatePeriod($startDate, $periodInterval, $endDate);

                                foreach ($period_new_3 as $date) {
                                    $date = $date->format("d/m/Y");

                                    $available_update_details_RATE_BASE = get_data(RATE_BASE, array(
                                        'owner_id' => $user_id,
                                        'hotel_id' => $hotel_id,
                                        'room_id' => $getMappedRooms['property_id'],
                                        'rate_types_id' => $getMappedRooms['rate_id'],
                                        'individual_channel_id' => 9,
                                        'separate_date' => $date
                                    ), 'room_update_id,availability')->row_array();

                                    if (count($available_update_details_RATE_BASE) != 0) {
                                        if ($reservation_status == 'Cancel') {
                                            $opr   = '+';
                                            $value = $available_update_details_RATE_BASE['availability'] + $difference;
                                        } else {
                                            $opr   = '-';
                                            $value = $available_update_details_RATE_BASE['availability'] - $difference;
                                        }

                                        $ch_update_data_RATE_BASE['trigger_cal'] = 1;

                                        $ch_update_data_RATE_BASE['availability'] = $value;

                                        update_data(RATE_BASE, $ch_update_data_RATE_BASE, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'rate_types_id' => $getMappedRooms['rate_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date
                                        ));

                                        $this->db->where('room_update_id !=', $available_update_details_RATE_BASE['room_update_id']);
                                        $this->db->where('owner_id', $user_id);
                                        $this->db->where('hotel_id', $hotel_id);
                                        $this->db->where('room_id', $getMappedRooms['property_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $difference . ' >=0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                        $this->db->update(RATE_BASE);

                                    }
                                }
                            } else if ($getMappedRooms['property_id'] != '0' && $getMappedRooms['rate_id'] != '0' && $getMappedRooms['guest_count'] != '0' && $getMappedRooms['refun_type'] != '0') {
                                $startDate      = DateTime::createFromFormat("Y/m/d", $updateOldRoom['start']);
                                $endDate        = DateTime::createFromFormat("Y/m/d", $updateOldRoom['end']);
                                $periodInterval = new DateInterval("P1D");
                                $period_new_4   = new DatePeriod($startDate, $periodInterval, $endDate);

                                foreach ($period_new_4 as $date) {
                                    $date = $date->format("d/m/Y");

                                    $available_update_details_RATE_ADD = get_data(RATE_ADD, array(
                                        'owner_id' => $user_id,
                                        'hotel_id' => $hotel_id,
                                        'room_id' => $getMappedRooms['property_id'],
                                        'rate_types_id' => $getMappedRooms['rate_id'],
                                        'individual_channel_id' => 9,
                                        'separate_date' => $date,
                                        'guest_count' => $getMappedRooms['guest_count'],
                                        'refun_type' => $getMappedRooms['refun_type']
                                    ), 'reserv_price_id,availability')->row_array();

                                    if (count($available_update_details_RATE_ADD) != 0) {
                                        if ($reservation_status == 'Cancel') {
                                            $opr   = '+';
                                            $value = $available_update_details_RATE_ADD['availability'] + $difference;
                                        } else {
                                            $opr   = '-';
                                            $value = $available_update_details_RATE_ADD['availability'] - $difference;
                                        }

                                        $ch_update_data_RATE_ADD['trigger_cal'] = 1;

                                        $ch_update_data_RATE_ADD['availability'] = $value;

                                        update_data(RATE_ADD, $ch_update_data_RATE_ADD, array(
                                            'owner_id' => $user_id,
                                            'hotel_id' => $hotel_id,
                                            'room_id' => $getMappedRooms['property_id'],
                                            'rate_types_id' => $getMappedRooms['rate_id'],
                                            'individual_channel_id' => 9,
                                            'separate_date' => $date,
                                            'guest_count' => $getMappedRooms['guest_count'],
                                            'refun_type' => $getMappedRooms['refun_type']
                                        ));

                                        $this->db->where('reserv_price_id !=', $available_update_details_RATE_ADD['reserv_price_id']);
                                        $this->db->where('owner_id', $user_id);
                                        $this->db->where('hotel_id', $hotel_id);
                                        $this->db->where('room_id', $getMappedRooms['property_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->set('availability', 'CASE WHEN availability ' . $opr . ' ' . $difference . ' >=0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id = 0 THEN availability ' . $opr . ' ' . $difference . ' WHEN availability ' . $opr . ' ' . $value . ' < 0 AND individual_channel_id != 0 THEN 0 END', false);

                                        $this->db->update(RATE_ADD);
                                    }
                                }
                            }
                            $this->availability_model->updateAvailabilityBNOW($start, $end, $user_id, $hotel_id, $getMappedRooms['property_id'], '9');
                        }
                    }

                    $UAVL['status'] = '0';

                    update_data(UAVL, $UAVL, array(
                        'column_id' => $column_id
                    ));

                    if ($reservation_status == 'Cancel') {
                        delete_data(UAVL, array(
                            'reservation_id' => $reservation_id
                        ));
                    }
                }
            }
        }
    }
}
?>
