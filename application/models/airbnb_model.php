<?php
ini_set('memory_limit', '-1');
ini_set('display_erros', '1');
class airbnb_model extends CI_Model
{
    
    function mailsettings()
    {
        $this->load->library('email');
        $config['wrapchars'] = 76; // Character count to wrap at.
        $config['priority']  = 1; // Character count to wrap at.
        $config['mailtype']  = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
        $config['charset']   = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
        $this->email->initialize($config); 
    }
    
    
    function SincroCalender($datepicker_full_start, $datepicker_full_end,$userid,$hotelid)
    {
        
        $airbnbErrors = "";
        //Dealles de CAnal
        $ch_details   = get_data(CONNECT, array(
            'hotel_id' => $hotelid,
            'channel_id' => "9"
        ))->row();
        //Busco las Habiaciones Conectadas al Canal 9 AIRBNB
        
        $room_mapping    = get_data(MAP, array(
            'hotel_id' => $hotelid,
            'channel_id' => 9,
            'enabled' => 'enabled'
        ))->result_array();
        $buffaccesstoken = $ch_details->other_id;
        include_once('cURL.php');
        $curl = new cURL(TRUE);
        foreach ($room_mapping as $Mapping => $Mappingvalue) {
            if ($Mappingvalue["rate_id"] == "0") {
                
                $Data = $this->db->query("select *  from room_update where owner_id =" . $userid. " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details = get_data('import_mapping_AIRBNB', array(
                    'hotel_id' => $hotelid,
                    'channel_id' => "9",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                $refreshToken = 0;
                if ($ch_details->other_id != '') {
                    $resultFile           = '"access_token":"' . $buffaccesstoken . '"';
                    $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
                } else {
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
                    writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
                }
                
                if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    $AuthToken = $match[1];
                    update_data(CONNECT, array(
                        'other_id' => $AuthToken
                    ), array(
                        'hotel_id' => $hotelid,
                        'channel_id' => '9',
                        'status' => 'enabled'
                    ));
                    foreach ($Data as $key => $value) {
                        $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                        $start_date = $datevalue->format('Y-m-d');
                        
                        $update_date = $start_date;
                        
                        $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                        
                        $formData = array();
                        if ($value["price"] != '') {
                            if ($value["price"] != '0.00') {
                                $price                                       = $value["price"] * $RateConvertion;
                                $formData['daily_price']                     = $price;
                                $formData['demand_based_pricing_overridden'] = true;
                            }
                        }
                        
                        //For Alot Update
                        if ($value['availability'] != '') {
                            if ($value['availability'] > 0) {
                                $formData['availability'] = 'available';
                            } else {
                                $formData['availability'] = 'unavailable';
                            }
                        }
                        if ($value['stop_sell'] != '') {
                            if ($value['stop_sell'] == 1) {
                                $formData['availability'] = 'unavailable';
                            } else {
                                $formData['availability'] = 'available';
                            }
                            if ($value['availability'] != '') {
                                if ($value['availability'] == 0) {
                                    $formData['availability'] = 'unavailable';
                                }
                            }
                        }
                        
                        $content          = json_encode($formData);
                        $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                        $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                        
                        $resultFile = put($url, $content, $curl->headers);
                        writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        if (preg_match('/expired_token/is', $resultFile, $match)) {
                            if ($refreshToken == 0) {
                                $refreshToken           = 1;
                                $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                                $formData['client_id']  = $ch_details->other_id;
                                $formData['locale']     = 'en-US';
                                $formData['currency']   = 'USD';
                                $formData['grant_type'] = 'password';
                                $formData['username']   = $ch_details->user_name;
                                $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                                
                                $tokencontent = '';
                                foreach ($formData as $key => $value) {
                                    $tokencontent .= '&' . $key . '=' . $value;
                                }
                                $tokencontent    = replace_content_hex($tokencontent);
                                $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                                writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                                if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                                    $AuthToken = $match[1];
                                    update_data(CONNECT, array(
                                        'other_id' => $AuthToken
                                    ), array(
                                        'user_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'channel_id' => '9',
                                        'status' => 'enabled'
                                    ));
                                    $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                    $resultFile       = put($url, $content, $curl->headers);
                                    writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                                }
                            }
                        }
                        $this->load->model('inventory_model');
                        if ($resultFile == '') {

                            $this->inventory_model->store_error($userid, $hotelid, '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_date.");
                            $airbnbErrors .= "Could not get success message after update -  and DateRange : $update_date to $update_date.";
                        } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                            
                            $this->inventory_model->store_error($userid, $hotelid, '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                            $airbnbErrors .= $match[1] . "-DateRange : $update_date to $update_date.";
                        } elseif (preg_match('/error/is', $resultFile, $match)) {
                            
                            $this->inventory_model->store_error($userid, $hotelid, '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                            $airbnbErrors .= "Could not get success message after update- and DateRange : $update_date to $update_date.";
                        }
                    }
                } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                   // $this->inventory_model->store_error($userid, $hotelid, '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', $match[1]);
                    $airbnbErrors .= $match[1];
                } else {
                    $this->inventory_model->store_error($userid, $hotelid, '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
                    $airbnbErrors .= 'Access Token not generated.';
                }
            } else {
                $Data = $this->db->query("select *  from room_rate_types_base where owner_id =" . $userid . " and hotel_id =" . hotel_id() . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and rate_types_id = " . $Mappingvalue["rate_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details = get_data('import_mapping_AIRBNB', array(
                    'user_id' => $userid,
                    'hotel_id' => $hotelid,
                    'channel_id' => "9",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $RateConvertion = $Mappingvalue["rate_conversion"];
                $refreshToken   = 0;
                if ($ch_details->other_id != '') {
                    $resultFile           = '"access_token":"' . $buffaccesstoken . '"';
                    $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
                } else {
                    $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';

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
                    writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
                }
                if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    $AuthToken = $match[1];
                    update_data(CONNECT, array(
                        'other_id' => $AuthToken
                    ), array(
                        'user_id' => $userid,
                        'hotel_id' => $hotelid,
                        'channel_id' => '9',
                        'status' => 'enabled'
                    ));
                    foreach ($Data as $key => $value) {
                        $datevalue   = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                        $start_date  = $datevalue->format('Y-m-d');
                        $update_date = $start_date;
                        $url         = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                        
                        $formData = array();
                        if ($value["price"] != '') {
                            if ($value["price"] != '0.00') {
                                $price                                       = $value["price"] * $RateConvertion;
                                $formData['daily_price']                     = $price;
                                $formData['demand_based_pricing_overridden'] = true;
                            }
                        }
                        
                        //For Alot Update
                        if ($value['availability'] != '') {
                            if ($value['availability'] > 0) {
                                $formData['availability'] = 'available';
                            } else {
                                $formData['availability'] = 'unavailable';
                            }
                        }
                        if ($value['stop_sell'] != '') {
                            if ($value['stop_sell'] == 1) {
                                $formData['availability'] = 'unavailable';
                            } else {
                                $formData['availability'] = 'available';
                            }
                            if ($value['availability'] != '') {
                                if ($value['availability'] == 0) {
                                    $formData['availability'] = 'unavailable';
                                }
                            }
                        }
                        
                        $content          = json_encode($formData);
                        $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                        $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                        
                        $resultFile = put($url, $content, $curl->headers);
                        writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        if (preg_match('/expired_token/is', $resultFile, $match)) {
                            if ($refreshToken == 0) {
                                $refreshToken           = 1;
                                $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                                $formData['client_id']  = $ch_details->other_id;
                                $formData['locale']     = 'en-US';
                                $formData['currency']   = 'USD';
                                $formData['grant_type'] = 'password';
                                $formData['username']   = $ch_details->user_name;
                                $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                                
                                $tokencontent = '';
                                foreach ($formData as $key => $value) {
                                    $tokencontent .= '&' . $key . '=' . $value;
                                }
                                $tokencontent    = replace_content_hex($tokencontent);
                                $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                                writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                                if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                                    $AuthToken = $match[1];
                                    update_data(CONNECT, array(
                                        'other_id' => $AuthToken
                                    ), array(
                                        'user_id' => $userid,
                                        'hotel_id' => $hotelid,
                                        'channel_id' => '9',
                                        'status' => 'enabled'
                                    ));
                                    $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                    $resultFile       = put($url, $content, $curl->headers);
                                    writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                                }
                            }
                        }
                        if ($resultFile == '') {
                            
                            $this->inventory_model->store_error($userid, $hotelid, '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_date.");
                            $airbnbErrors .= $match[1] . "Could not get success message after update -  and DateRange : $update_date to $update_date.";
                        } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                            
                            $this->inventory_model->store_error($userid, $hotelid, '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                            $airbnbErrors .= $match[1] . "-DateRange : $update_date to $update_date.";
                        } elseif (preg_match('/error/is', $resultFile, $match)) {
                            
                            $this->inventory_model->store_error($userid, $hotelid, '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                            $airbnbErrors .= "Could not get success message after update- and DateRange : $update_date to $update_date.";
                        }
                    }
                } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    $this->inventory_model->store_error($userid, $hotelid, '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $airbnbErrors .= $match[1];
                    $this->session->set_flashdata('bulk_error', $match[1]);
                } else {
                    $this->inventory_model->store_error($userid, $hotelid, '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
                    $airbnbErrors .= 'Access Token not generated.';
                }
            }
        }
        
        
        return $airbnbErrors;
    }
    function inline_edit_main_calendar($table, $room_id, $rate_type_id, $update_date, $name, $import_mapping_id, $mapping_id, $guest_count, $refunds)
    {
        $channel_id = 9;
        
        if ($table == 'room_update') {
            $update_Details = get_data($table, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'individual_channel_id' => $channel_id,
                'room_id' => $room_id,
                'separate_date' => $update_date
            ))->row();
        } else if ($table == 'reservation_table') {
            $update_Details = get_data($table, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'room_id' => $room_id,
                'separate_date' => $update_date,
                'guest_count' => $guest_count,
                'refun_type' => $refunds,
                'individual_channel_id' => $channel_id
            ))->row();
        } else if ($table == 'room_rate_types_base') {
            $update_Details = get_data($table, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'individual_channel_id' => $channel_id,
                'room_id' => $room_id,
                'rate_types_id' => $rate_type_id,
                'separate_date' => $update_date
            ))->row();
        } else if ($table == 'room_rate_types_additional') {
            $update_Details = get_data($table, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'room_id' => $room_id,
                'rate_types_id' => $rate_type_id,
                'separate_date' => $update_date,
                'guest_count' => $guest_count,
                'refun_type' => $refunds,
                'individual_channel_id' => $channel_id
            ))->row();
        }
        
        $separate_date = date('Y-m-d', strtotime(str_replace('/', '-', $update_Details->separate_date)));
        
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id
        ))->row();
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id,
            'import_mapping_id' => $import_mapping_id
        ))->row();
        
        if ($mp_details->RoomId != 0) {
            $update_date  = $separate_date;
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                
                $formData = array();
                if ($update_Details->price != '') {
                    if ($update_Details->price != '0.00') {
                        $formData['daily_price']                     = $update_Details->price;
                        $formData['demand_based_pricing_overridden'] = true;
                    }
                }
                
                //For Alot Update
                if ($update_Details->availability != '') {
                    if ($update_Details->availability > 0) {
                        $formData['availability'] = 'available';
                    } else {
                        $formData['availability'] = 'unavailable';
                    }
                }
                if ($update_Details->stop_sell != '') {
                    if ($update_Details->stop_sell == 1) {
                        $formData['availability'] = 'unavailable';
                    } else {
                        $formData['availability'] = 'available';
                    }
                    if ($update_Details->availability != '') {
                        if ($update_Details->availability == 0) {
                            $formData['availability'] = 'unavailable';
                        }
                    }
                }
                
                $content          = json_encode($formData);
                $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                
                $resultFile = put($url, $content, $curl->headers);
                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                if (preg_match('/expired_token/is', $resultFile, $match)) {
                    if ($refreshToken == 0) {
                        $refreshToken           = 1;
                        $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                        $formData['client_id']  = $ch_details->other_id;
                        $formData['locale']     = 'en-US';
                        $formData['currency']   = 'USD';
                        $formData['grant_type'] = 'password';
                        $formData['username']   = $ch_details->user_name;
                        $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                        
                        $tokencontent = '';
                        foreach ($formData as $key => $value) {
                            $tokencontent .= '&' . $key . '=' . $value;
                        }
                        $tokencontent    = replace_content_hex($tokencontent);
                        $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                        writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                            $AuthToken = $match[1];
                            update_data(CONNECT, array(
                                'other_id' => $AuthToken
                            ), array(
                                'user_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'channel_id' => '9',
                                'status' => 'enabled'
                            ));
                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                            $resultFile       = put($url, $content, $curl->headers);
                            writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        }
                    }
                }
                if ($resultFile == '') {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_date.");
                } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                } elseif (preg_match('/error/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
        }
    }
    
    function full_calendar_update($ch_data, $udata, $update_date, $priceAmount, $import_mapping_id, $mapping_id)
    {
        extract($ch_data);
        extract($udata);
        
        $channel_id = 9;
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "9"
        ))->row();
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "9",
            'import_mapping_id' => $import_mapping_id
        ))->row();
        
        //        if ($stop_sell == 1 && $open_room == 0) {
        //            $closed = 1;
        //        }else if($stop_sell == 0 && $open_room == 1){
        //            $closed = 0;
        //        }else if($stop_sell == 1 && $open_room == 1){
        //            $closed = 1;
        //        }
        //        else if($stop_sell == 0 && $open_room == 0)
        //        {
        //            $closed = 0;
        //        }
        //        else
        //        {
        //            $closed = 0;
        //        }
        
        if ($mp_details->RoomId != 0) {
            $update_date  = date('Y-m-d', strtotime($update_date));
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                
                $formData = array();
                if ($priceAmount != '') {
                    if ($priceAmount != '0.00') {
                        $formData['daily_price']                     = $priceAmount;
                        $formData['demand_based_pricing_overridden'] = true;
                    }
                }
                
                //For Alot Update
                if ($availability != '') {
                    if ($availability > 0) {
                        $formData['availability'] = 'available';
                    } else {
                        $formData['availability'] = 'unavailable';
                    }
                }
                if ($closed != '') {
                    if ($closed == 1) {
                        $formData['availability'] = 'unavailable';
                    } else {
                        $formData['availability'] = 'available';
                    }
                    if ($availability != '') {
                        if ($availability == 0) {
                            $formData['availability'] = 'unavailable';
                        }
                    }
                }
                
                $content          = json_encode($formData);
                $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                
                $resultFile = put($url, $content, $curl->headers);
                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                if (preg_match('/expired_token/is', $resultFile, $match)) {
                    if ($refreshToken == 0) {
                        $refreshToken           = 1;
                        $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                        $formData['client_id']  = $ch_details->other_id;
                        $formData['locale']     = 'en-US';
                        $formData['currency']   = 'USD';
                        $formData['grant_type'] = 'password';
                        $formData['username']   = $ch_details->user_name;
                        $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                        
                        $tokencontent = '';
                        foreach ($formData as $key => $value) {
                            $tokencontent .= '&' . $key . '=' . $value;
                        }
                        $tokencontent    = replace_content_hex($tokencontent);
                        $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                        writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                            $AuthToken = $match[1];
                            update_data(CONNECT, array(
                                'other_id' => $AuthToken
                            ), array(
                                'user_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'channel_id' => '9',
                                'status' => 'enabled'
                            ));
                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                            $resultFile       = put($url, $content, $curl->headers);
                            writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        }
                    }
                }
                if ($resultFile == '') {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_date.");
                } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                } elseif (preg_match('/error/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
        }
        
    }
    
    function bulk_update($product, $import_mapping_id, $maping_id, $price)
    {
        $up_days        =  @$product['days'];
        $start          = date('Y-m-d', strtotime(str_replace('/', '-', @$product['start_date'])));
        $end            = date('Y-m-d', strtotime(str_replace('/', '-', @$product['end_date'])));
        $period         = $this->getDateForSpecificDayBetweenDates($start, $end, implode(',',$product['days']));
        //print_r($period);
        /* echo $string; die; */
        $ch_details     = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "9"
        ))->row();
        $channel_id     = 9;
        $mp_details     = get_data('import_mapping_AIRBNB', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id,
            'import_mapping_id' => $import_mapping_id
        ))->row();
        $mapping_values = get_data('mapping_values', array(
            'mapping_id' => $maping_id
        ))->row_array();
        
        //$closed = 0;
        if (@$product['stops'] == "1") {
            $closed = 1;
        } elseif (isset($product['stops'])  && $product['stops'] != "1") {
            $closed = 0;
        }
        
        if ($mp_details->RoomId != 0) {
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                $errorFlag = 0;
                foreach ($period as $date) {
                    $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $date . '/' . $date;
                    
                    $formData = array();
                    if ($price != '') {
                        if ($price != '0.00' || $price != '0') {
                            $formData['daily_price']                     = $price;
                            $formData['demand_based_pricing_overridden'] = true;
                        }
                    } elseif (@$product['price'] != '') {
                        if ($product['price'] != '0.00' || $product['price'] != '0') {
                            $formData['daily_price']                     = $product['price'];
                            $formData['demand_based_pricing_overridden'] = true;
                        }
                    }
                    
                    //For Alot Update
                    if (@$product['availability'] != '') {
                        if ($product['availability'] > 0) {
                            $formData['availability'] = 'available';
                        } else {
                            $formData['availability'] = 'unavailable';
                        }
                    }
                    if (@$product['stops'] != '') {
                        if ($product['stops'] == '1') {
                            $formData['availability'] = 'unavailable';
                        }
                    }
                    if (@$product['stops'] != '') {
                        if ($product['stops'] != '1') {
                            $formData['availability'] = 'available';
                        }
                    }
                    
                    $content          = json_encode($formData);
                    $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                    $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                    
                    $resultFile = put($url, $content, $curl->headers);

                    $headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
                    $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
                    $headers .= "CC: support <felix@hoteratus.com>\r\n";
                    $headers .= "BCC: datahernandez@gmail.com\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
                     mail("xml@hoteratus.com", "AIRBNB.com Request and Response ".hotel_id(), $resultFile , $headers);
                    writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                    if (preg_match('/expired_token/is', $resultFile, $match)) {
                        if ($refreshToken == 0) {
                            $refreshToken           = 1;
                            $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                            $formData['client_id']  = $ch_details->other_id;
                            $formData['locale']     = 'en-US';
                            $formData['currency']   = 'USD';
                            $formData['grant_type'] = 'password';
                            $formData['username']   = $ch_details->user_name;
                            $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                            
                            $tokencontent = '';
                            foreach ($formData as $key => $value) {
                                $tokencontent .= '&' . $key . '=' . $value;
                            }
                            $tokencontent    = replace_content_hex($tokencontent);
                            $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                            writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                                $AuthToken = $match[1];
                                update_data(CONNECT, array(
                                    'other_id' => $AuthToken
                                ), array(
                                    'user_id' => current_user_type(),
                                    'hotel_id' => hotel_id(),
                                    'channel_id' => '9',
                                    'status' => 'enabled'
                                ));
                                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                $resultFile       = put($url, $content, $curl->headers);
                                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                            }
                        }
                    }
                    if ($resultFile == '') {
                        $errorFlag = 1;
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $date to $date.", 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $date to $dated.");
                    } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                        $errorFlag = 1;
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $date to $date.", 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $date to $date.");
                    } elseif (preg_match('/error/is', $resultFile, $match)) {
                        $errorFlag = 1;
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $date to $date.", 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $date to $date.");
                    }
                }
                if ($errorFlag == 0) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $errorFlag = 1;
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Bulk Update', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $errorFlag = 1;
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Bulk Update', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
            
            return true;
        }
        
    }
    
    function getDateForSpecificDayBetweenDates($start, $end, $weekday)
    {
        //echo 'start = '.$start.' end = '.$end;
        $weekdays         = "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
        $arr_weekdays     = explode(",", $weekdays);
        $arr_weekdays_day = explode(",", $weekday);
        //print_r($arr_weekdays_day);
        $i                = 1;
        $string           = '';
        foreach ($arr_weekdays_day as $weekdays) {
            if ($weekdays == 1) {
                $weekdays = 0;
            } elseif ($weekdays == 2) {
                $weekdays = 1;
            } elseif ($weekdays == 3) {
                $weekdays = 2;
            } elseif ($weekdays == 4) {
                $weekdays = 3;
            } elseif ($weekdays == 5) {
                $weekdays = 4;
            } elseif ($weekdays == 6) {
                $weekdays = 5;
            } elseif ($weekdays == 7) {
                $weekdays = 6;
            }
            $weekday = $arr_weekdays[$weekdays];
            if (!$weekday)
                $this->inventory_model->store_error(current_user_type(), hotel_id(), "9", 'Invalid WeekDay', 'Bulk Update', date('m/d/Y h:i:s a', time()));
            $starts = strtotime("+0 day", strtotime($start));
            //$starts    = strtotime($start);
            $ends   = strtotime($end);
            //$dateArr = array();
            $friday = strtotime($weekday, $starts);
            while ($friday <= $ends) {
                /*$dateArr[] = date("Y-m-d", $friday);
                $friday = strtotime("+1 weeks", $friday);*/
                $dateArr[] = date("Y-m-d", $friday);
                $date      = date("Y-m-d", $friday);
                $string .= "value" . $i . "='" . $date . "' ";
                $friday = strtotime("+1 weeks", $friday);
                $i++;
            }
            //$dateArr[] = date("Y-m-d", $friday);
        }
        //echo $string.'<br>';
        //return $dateArr;
        return $dateArr;
    }
    
    function update_stopsell($date, $room_id, $import_mapping_id)
    {
        $update_date          = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
        $channel_id           = 9;
        $availability_details = get_data(TBL_UPDATE, array(
            'owner_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'individual_channel_id' => '9',
            'room_id' => $room_id,
            'separate_date' => $date
        ))->row();
        
        if ($availability_details->stop_sell == 0 && $availability_details->open_room == 1) {
            $closed = 0;
        } else if ($availability_details->stop_sell == 1 && $availability_details->open_room == 1) {
            $closed = 1;
        }
        //echo $availability_details->stop_sell;
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id
        ))->row();
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id,
            'import_mapping_id' => $import_mapping_id
        ))->row();
        if ($mp_details->RoomId != 0) {
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                
                $formData = array();
                if ($closed == 0) {
                    $formData['availability'] = 'available';
                } elseif ($closed == 1) {
                    $formData['availability'] = 'unavailable';
                }
                $content          = json_encode($formData);
                $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                
                $resultFile = put($url, $content, $curl->headers);
                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                if (preg_match('/expired_token/is', $resultFile, $match)) {
                    if ($refreshToken == 0) {
                        $refreshToken           = 1;
                        $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                        $formData['client_id']  = $ch_details->other_id;
                        $formData['locale']     = 'en-US';
                        $formData['currency']   = 'USD';
                        $formData['grant_type'] = 'password';
                        $formData['username']   = $ch_details->user_name;
                        $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                        
                        $tokencontent = '';
                        foreach ($formData as $key => $value) {
                            $tokencontent .= '&' . $key . '=' . $value;
                        }
                        $tokencontent    = replace_content_hex($tokencontent);
                        $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                        writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                            $AuthToken = $match[1];
                            update_data(CONNECT, array(
                                'other_id' => $AuthToken
                            ), array(
                                'user_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'channel_id' => '9',
                                'status' => 'enabled'
                            ));
                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                            $resultFile       = put($url, $content, $curl->headers);
                            writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        }
                    }
                }
                if ($resultFile == '') {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_dated.");
                } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                } elseif (preg_match('/error/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
        }
    }
    
    function importAvailabilities($user_id = "", $hotel_id = "", $channel, $mapping, $import_mapping_id, $start_date = "", $end_date = "")
    {
        if ($user_id == "") {
            $user_id = current_user_type();
        }
        if ($hotel_id == "") {
            $hotel_id = hotel_id();
        }
        extract($channel);
        if ($start_date == "") {
            $start_date = date('Y-m-d');
            $ndays      = 30;
        } else if ($start_date != "") {
            $ndays = _datebetween($start_date, $end_date);
        }
        
        $ch_details = get_data(CONNECT, array(
            'user_id' => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "9"
        ))->row();
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => $channel_id,
            'import_mapping_id' => $import_mapping_id,
            'channel_hotel_id' => $ch_details->hotel_channel_id
        ))->row();
        
        $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                <number_of_days>' . $ndays . '</number_of_days>
                <start_date>' . $start_date . '</start_date>
                <room_level>1</room_level>
                </request>';
        //echo $xml_data;
        
        $x_r_rq_data['channel_id'] = '1';
        $x_r_rq_data['user_id']    = '0';
        $x_r_rq_data['hotel_id']   = '0';
        $x_r_rq_data['message']    = $xml_data;
        $x_r_rq_data['type']       = 'REQ';
        $x_r_rq_data['section']    = 'BOOK_IM_AV_REQ';
        insert_data(ALL_XML, $x_r_rq_data);
        $URL = "https://supply-xml.booking.com/hotels/xml/roomrateavailability";
        $ch  = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output                    = curl_exec($ch);
        $x_r_rs_data['channel_id'] = '1';
        $x_r_rs_data['user_id']    = '0';
        $x_r_rs_data['hotel_id']   = '0';
        $x_r_rs_data['message']    = $output;
        $x_r_rs_data['type']       = 'RES';
        $x_r_rs_data['section']    = 'BOOK_IM_AV_REQ';
        insert_data(ALL_XML, $x_r_rs_data);
        $data_api = simplexml_load_string($output);
        
        if ($data_api->fault) {
            $this->inventory_model->store_error($user_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'Import availability', date('m/d/Y h:i:s a', time()));
        } else {
            $this->session->set_flashdata('import_success', 'Successfully Imported Room Availability From Booking.com!!!');
            
            $details = $data_api->room;
            
            foreach ($details as $detail) {
                $roomid = $detail->attributes()->room_id;
                if ($roomid == $mp_details->B_room_id) {
                    foreach ($detail as $date) {
                        $availability = $date->attributes()->rooms_to_sell;
                        $sep_date     = date('d/m/Y', strtotime(str_replace('-', '/', $date->attributes()->value)));
                        require_once(APPPATH . 'controllers/mapping.php');
                        $callAvailabilities = new Mapping();
                        $callAvailabilities->update_channel_calendar($user_id, $hotel_id, $channel, $availability, $sep_date, $mapping);
                    }
                }
            }
        }
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
        //print_r($output);
        $end = end($output);
        if (is_array($end)) {
            $end_end = end($end);
            $ruid    = str_replace("!-- RUID: [", '', $end_end);
            $ruid    = trim(str_replace('] --', '', $ruid));
            $this->store_ruid_airbnb($ruid, 'Import Availability', $user_id, $hotel_id);
        } else {
            $ruid = str_replace("!-- RUID: [", '', $end);
            $ruid = trim(str_replace('] --', '', $ruid));
            $this->store_ruid_airbnb($ruid, 'Import Availability', $user_id, $hotel_id);
        }
        return true;
    }
    
    function update_availability($owner_id = '', $hotel_id = '', $date, $import_mapping_id, $availability)
    {
        if ($owner_id == "") {
            $owner_id = current_user_type();
        }
        if ($hotel_id == "") {
            $hotel_id = hotel_id();
        }
        $update_date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
        
        $ch_details = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "9"
        ))->row();
        $channel_id = 9;
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "9",
            'import_mapping_id' => $import_mapping_id
        ))->row();
        
        if ($mp_details->RoomId != 0) {
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $update_date . '/' . $update_date;
                
                $formData = array();
                if ($availability > 0) {
                    $formData['availability'] = 'available';
                } elseif ($availability <= 0) {
                    $formData['availability'] = 'unavailable';
                }
                
                $content          = json_encode($formData);
                $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                
                $resultFile = put($url, $content, $curl->headers);
                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                if (preg_match('/expired_token/is', $resultFile, $match)) {
                    if ($refreshToken == 0) {
                        $refreshToken           = 1;
                        $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                        $formData['client_id']  = $ch_details->other_id;
                        $formData['locale']     = 'en-US';
                        $formData['currency']   = 'USD';
                        $formData['grant_type'] = 'password';
                        $formData['username']   = $ch_details->user_name;
                        $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                        
                        $tokencontent = '';
                        foreach ($formData as $key => $value) {
                            $tokencontent .= '&' . $key . '=' . $value;
                        }
                        $tokencontent    = replace_content_hex($tokencontent);
                        $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                        writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                            $AuthToken = $match[1];
                            update_data(CONNECT, array(
                                'other_id' => $AuthToken
                            ), array(
                                'user_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'channel_id' => '9',
                                'status' => 'enabled'
                            ));
                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                            $resultFile       = put($url, $content, $curl->headers);
                            writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                        }
                    }
                }
                if ($resultFile == '') {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $update_date to $update_dated.");
                } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $update_date to $update_date.");
                } elseif (preg_match('/error/is', $resultFile, $match)) {
                    
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $update_date to $update_date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $update_date to $update_date.");
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
        }
    }
    
    function send_mail_to_hoteliers($reservation_id, $user_id, $hotel_id)
    {
        $channel_data = get_data("user_connect_channel", array(
            "user_id" => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => 9
        ))->row();
        if ($channel_data) {
            $sitename             = get_data('manage_hotel', array(
                'hotel_id' => $hotel_id,
                'owner_id' => $user_id
            ))->row()->property_name;
            $data['channel_name'] = get_data('manage_channel', array(
                'channel_id' => 9
            ))->row()->channel_name;
            
            $airbnb       = get_data("import_reservation_AIRBNB", array(
                'id' => $reservation_id
            ))->row_array();
            $room_details = get_data("import_reservation_AIRBNB_ROOMS", array(
                'reservation_id' => $reservation_id
            ))->result_array();
            
            $data['reservation_id'] = $airbnb['id'];
            $data['channel_name']   = "Booking.com";
            $data['subtotal']       = $airbnb['totalprice'];
            $data['grand_total']    = $airbnb['totalprice'];
            $data['status']         = $airbnb['status'];
            $data['airbnb_date']    = $airbnb['date'];
            $data['cc_name']        = $airbnb['cc_name'];
            $data['cc_number']      = $airbnb['cc_number'];
            $data['cc_cvc']         = $airbnb['cc_cvc'];
            $data['cc_type']        = $airbnb['cc_type'];
            $data['hotel_name']     = $airbnb['hotel_name'];
            $data['fullname']       = $airbnb['first_name'] . ' ' . $airbnb['last_name'];
            
            if ($airbnb['remarks'] != "") {
                $data['notes'] = $airbnb['remarks'];
            } else {
                $data['notes'] = "NONE";
            }
            
            $data['room_details'] = $room_details;
            $message              = $this->load->view("email/airbnb", $data, TRUE);
            
            $subject = "Reservation #" . $data['reservation_id'] . " From " . $data['channel_name'] . ' For Hotel ' . $airbnb['hotel_name'];
            
            $admin_detail = get_data(TBL_SITE, array(
                'id' => 1
            ))->row();
            
            $this->mailsettings();
            
            $this->email->clear(TRUE);
            
            $this->email->from($admin_detail->email_id);
            
            $this->email->to($channel_data->reservation_email);
            
            $this->email->subject($subject);
            
            $this->email->message($message);
            
            $this->email->send();
            
            //return true;
        }
    }
    
    function getReservationLists($source)
    {
        $this->db->select('Import_reservation_ID, ResID_Value,ResStatus,name, givenName, surname    ,RoomTypeCode,RatePlanCode,channel_id,arrival,departure,LastModifyDateTime,Currency,AmountAfterTax,ImportDate,CreateDateTime');
        $this->db->order_by('Import_reservation_ID', 'desc');
        $this->db->where('user_id', current_user_type());
        $this->db->where('hotel_id', hotel_id());
        $data = $this->db->get('import_reservation_AIRBNB')->result();
        if ($data) {
            $bnow = array();
            foreach ($data as $val) {
                
                $status     = $val->ResStatus;
                $PersonName = $val->name;
                
                $room_id = @get_data(MAP, array(
                    'channel_id' => $val->channel_id,
                    'import_mapping_id' => get_data('import_mapping_AIRBNB', array(
                        'RoomId' => $val->RoomTypeCode,
                        'user_id' => current_user_type(),
                        'hotel_id' => hotel_id()
                    ))->row()->import_mapping_id
                ))->row()->property_id;
                
                $checkin  = date('Y/m/d', strtotime($val->arrival));
                $checkout = date('Y/m/d', strtotime($val->departure));
                $nig      = _datebetween($checkin, $checkout);
                list($bookingdate) = explode(' ', $val->ImportDate);
                if ($source == "all") {
                    $bnow[] = (object) array(
                        'reservation_id' => $val->Import_reservation_ID,
                        'reservation_code' => $val->ResID_Value,
                        'status' => $status,
                        'guest_name' => $PersonName,
                        'room_id' => $room_id,
                        'channel_id' => $val->channel_id,
                        'start_date' => $val->arrival,
                        'end_date' => $val->departure,
                        'booking_date' => $bookingdate,
                        'currency_id' => $val->Currency,
                        'price' => $val->AmountAfterTax,
                        'num_nights' => $nig,
                        'current_date_time' => $val->ImportDate
                    );
                } else if ($source == "separate") {
                    $bnow[] = (object) array(
                        'import_reserv_id' => $val->Import_reservation_ID,
                        'IDRSV' => $val->ResID_Value,
                        'STATUS' => $status,
                        'FIRSTNAME' => $val->name,
                        'ROOMCODE' => $room_id,
                        'channel_id' => $val->channel_id,
                        'CHECKIN' => $val->arrival,
                        'CHECKOUT' => $val->departure,
                        'RSVCREATE' => $bookingdate,
                        'CURRENCY' => $val->Currency,
                        'REVENUE' => $val->AmountAfterTax,
                        'num_nights' => $nig,
                        'current_date_time' => $val->ImportDate
                    );
                }
            }
            return $bnow;
        } else {
            return $bnow = array();
        }
    }
    
    function store_ruid_airbnb($ruid, $place, $user_id = "", $hotel_id = "")
    {
        
        if ($user_id == "") {
            $user_id = current_user_type();
        }
        
        if ($hotel_id == "") {
            $hotel_id = hotel_id();
        }
        $data['ruid']        = (string) $ruid;
        $data['user_id']     = $user_id;
        $data['hotel_id']    = $hotel_id;
        $data['channel_id']  = 9;
        $data['date_time']   = date('m/d/Y h:i:s a', time());
        $data['ruid_occurs'] = $place;
        
        $this->db->insert("airbnb_ruid", $data);
    }
    
    
    function airbnb_no_show($airbnbid, $owner_id, $hotel_id)
    {
        $ch_details = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "9"
        ))->row();
        $res_id     = get_data(BOOK_ROOMS, array(
            'roomreservation_id' => $airbnbid
        ))->row()->reservation_id;
        $URL        = "https://supply-xml.airbnb.com/hotels/xml/reporting";
        $xml_data   = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <reservation_id>' . $res_id . '</reservation_id>
                <report>
                    <is_no_show roomreservation_id="' . $airbnbid . '" />
                </report>
                </request>';
        $ch         = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output   = curl_exec($ch);
        $data_api = simplexml_load_string($output);
        $error    = "";
        if (@$data_api->fault) {
            $error = $data_api->fault->attributes()->string;
        }
        return $error;
    }
    
    
    
    function pms_update($product, $stop_sale, $import_mapping_id, $mapping_id)
    {
        extract($product);
        
        $up_days    = explode(',', @$product['days']);
        $start      = date('Y-m-d', strtotime(str_replace('/', '-', @$product['start_date'])));
        $end        = date('Y-m-d', strtotime(str_replace('/', '-', @$product['end_date'])));
        $period     = $this->getDateForSpecificDayBetweenDates($start, $end, @$product['days']);
        $ch_details = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => 9
        ))->row();
        $channel_id = 9;
        
        $mp_details = get_data('import_mapping_AIRBNB', array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "9",
            'import_mapping_id' => $import_mapping_id
        ))->row();
        
        //$closed = 0;
        if ($stop_sale == 1) {
            $closed = 1;
        } else if ($stop_sale == "remove") {
            $closed = 0;
        }
        
        if ($mp_details->RoomId != 0) {
            $refreshToken = 0;
            include_once('cURL.php');
            $curl = new cURL(TRUE);
            if ($ch_details->other_id != '') {
                $resultFile           = '"access_token":"' . $ch_details->other_id . '"';
                $ch_details->other_id = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
            } else {
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
                writeLog("url=>$url\nRequest=>$content\n\nResponse=>$resultFile");
            }
            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $AuthToken = $match[1];
                update_data(CONNECT, array(
                    'other_id' => $AuthToken
                ), array(
                    'user_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => '9',
                    'status' => 'enabled'
                ));
                foreach ($period as $date) {
                    $url = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $date . '/' . $date;
                    
                    $formData = array();
                    if (@$product['price'] != '') {
                        if ($product['price'] != '0.00') {
                            $formData['daily_price']                     = $product['price'];
                            $formData['demand_based_pricing_overridden'] = true;
                        }
                    }
                    
                    //For Alot Update
                    if (@$product['availability'] != '') {
                        if ($product['availability'] > 0) {
                            $formData['availability'] = 'available';
                        } else {
                            $formData['availability'] = 'unavailable';
                        }
                    }
                    if ($closed != '') {
                        if ($closed == 1) {
                            $formData['availability'] = 'unavailable';
                        } else {
                            $formData['availability'] = 'available';
                        }
                        if (@$product['availability'] != '') {
                            if ($product['availability'] == 0) {
                                $formData['availability'] = 'unavailable';
                            }
                        }
                    }
                    
                    $content          = json_encode($formData);
                    $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                    $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                    
                    $resultFile = put($url, $content, $curl->headers);
                    writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                    if (preg_match('/expired_token/is', $resultFile, $match)) {
                        if ($refreshToken == 0) {
                            $refreshToken           = 1;
                            $tokenurl               = 'https://api.airbnb.com/v1/authorize';
                            $formData['client_id']  = $ch_details->other_id;
                            $formData['locale']     = 'en-US';
                            $formData['currency']   = 'USD';
                            $formData['grant_type'] = 'password';
                            $formData['username']   = $ch_details->user_name;
                            $formData['password']   = preg_replace('/&/is', '%26', $ch_details->user_password);
                            
                            $tokencontent = '';
                            foreach ($formData as $key => $value) {
                                $tokencontent .= '&' . $key . '=' . $value;
                            }
                            $tokencontent    = replace_content_hex($tokencontent);
                            $tokenresultFile = $curl->post($tokenurl, $tokencontent);
                            writeLog("url=>$tokenurl\nRequest=>$tokencontent\n\nResponse=>$tokenresultFile");
                            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $tokenresultFile, $match)) {
                                $AuthToken = $match[1];
                                update_data(CONNECT, array(
                                    'other_id' => $AuthToken
                                ), array(
                                    'user_id' => current_user_type(),
                                    'hotel_id' => hotel_id(),
                                    'channel_id' => '9',
                                    'status' => 'enabled'
                                ));
                                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                $resultFile       = put($url, $content, $curl->headers);
                                writeLog("url=>$url\nAccess TOken=>$AuthToken\nRequest=>$content\n\nResponse=>$resultFile");
                            }
                        }
                    }
                    if ($resultFile == '') {
                        
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update -  and DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', "Could not get success message after update -  and DateRange : $date to $dated.");
                    } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                        
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1] . "-DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', $match[1] . "-DateRange : $date to $date.");
                    } elseif (preg_match('/error/is', $resultFile, $match)) {
                        
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', "Could not get success message after update- and DateRange : $date to $date.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', "Could not get success message after update- and DateRange : $date to $date.");
                    }
                }
            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', $match[1]);
            } else {
                $this->inventory_model->store_error(current_user_type(), hotel_id(), '9', 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', 'Access Token not generated.');
            }
        }
        return $response_message;
        
    }
    
    function importAvailabilities_pms($user_id, $hotel_id, $channel, $mapping, $import_mapping_id, $start_date, $end_date)
    {
        extract($channel);
        if ($start_date == "") {
            $start_date = date('Y-m-d');
            $ndays      = 30;
        } else if ($start_date != "") {
            $ndays = _datebetween($start_date, $end_date);
        }
        
        $ch_details = get_data(PMS_PART_CONNECT, array(
            'partner_id' => $user_id,
            'property_id' => $hotel_id,
            'channel_id' => "9"
        ))->row();
        $channel_id = 9;
        if ($ch_details->xml_type == 1 || $ch_details->xml_type == 2) {
            $mp_details = get_data(PMS_BOOKING, array(
                'partner_id' => $user_id,
                'property_id' => $hotel_id,
                'channel_id' => $channel_id,
                'book_id' => $import_mapping_id,
                'hotel_channel_id' => $ch_details->hotel_channel_id
            ))->row();
            
            $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
                    <request>
                    <username>' . $ch_details->user_name . '</username>
                    <password>' . $ch_details->user_password . '</password>
                    <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                    <number_of_days>' . $ndays . '</number_of_days>
                    <start_date>' . $start_date . '</start_date>
                    <room_level>1</room_level>
                    </request>';
            //echo $xml_data;
            
            $x_r_rq_data['channel_id'] = '1';
            $x_r_rq_data['user_id']    = '0';
            $x_r_rq_data['hotel_id']   = '0';
            $x_r_rq_data['message']    = $xml_data;
            $x_r_rq_data['type']       = 'PMS_REQ';
            $x_r_rq_data['section']    = 'PMS_BOOK_IM_AV_REQ';
            insert_data(ALL_XML, $x_r_rq_data);
            $URL = "https://supply-xml.booking.com/hotels/xml/roomrateavailability";
            $ch  = curl_init($URL);
            //curl_setopt($ch, CURLOPT_MUTE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output                    = curl_exec($ch);
            $x_r_rs_data['channel_id'] = '1';
            $x_r_rs_data['user_id']    = '0';
            $x_r_rs_data['hotel_id']   = '0';
            $x_r_rs_data['message']    = $output;
            $x_r_rs_data['type']       = 'PMS_RES';
            $x_r_rs_data['section']    = 'BOOK_IM_AV_REQ';
            insert_data(ALL_XML, $x_r_rs_data);
            $data_api = simplexml_load_string($output);
            
            if ($data_api->fault) {
                $this->inventory_model->store_error($user_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'PMS Import availability', date('m/d/Y h:i:s a', time()));
            } else {
                $this->session->set_flashdata('import_success', 'Successfully Imported Room Availability From Booking.com!!!');
                
                $details = $data_api->room;
                
                foreach ($details as $detail) {
                    $roomid = $detail->attributes()->room_id;
                    if ($roomid == $mp_details->B_room_id) {
                        foreach ($detail as $date) {
                            $availability = $date->attributes()->rooms_to_sell;
                            $sep_date     = date('d/m/Y', strtotime(str_replace('-', '/', $date->attributes()->value)));
                            require_once(APPPATH . 'controllers/mapping.php');
                            $callAvailabilities = new Mapping();
                            $callAvailabilities->update_channel_calendar($user_id, $hotel_id, $channel, $availability, $sep_date, $mapping);
                        }
                    }
                }
            }
            preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
            //print_r($output);
            $end = end($output);
            if (is_array($end)) {
                $end_end = end($end);
                $ruid    = str_replace("!-- RUID: [", '', $end_end);
                $ruid    = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_airbnb($ruid, 'PMS Import Availability', $user_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_airbnb($ruid, 'PMS Import Availability', $user_id, $hotel_id);
            }
            return true;
        } else {
            return false;
        }
    }
    
    function update_availability_pms($owner_id = '', $hotel_id = '', $date, $import_mapping_id, $availability)
    {
        $update_date = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
        
        $ch_details = get_data(PMS_PART_CONNECT, array(
            'partner_id' => $owner_id,
            'property_id' => $hotel_id,
            'channel_id' => "9"
        ))->row();
        $channel_id = 9;
        
        $mp_details = get_data(PMS_BOOKING, array(
            'partner_id' => $owner_id,
            'property_id' => $hotel_id,
            'channel_id' => "9",
            'book_id' => $import_mapping_id
        ))->row();
        
        if ($mp_details->B_rate_id != 0) {
            $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
            <request>
            <username>' . $ch_details->user_name . '</username>
            <password>' . $ch_details->user_password . '</password>
            <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
            <room id="' . $mp_details->B_room_id . '">
            <date value="' . $update_date . '" >
            <rate id="' . $mp_details->B_rate_id . '"/>';
            $xml_data .= '<roomstosell>' . $availability . '</roomstosell>';
            $xml_data .= '</date></room></request>';
            $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
            $ch  = curl_init($URL);
            //curl_setopt($ch, CURLOPT_MUTE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output   = curl_exec($ch);
            $data_api = simplexml_load_string($output);
            $error    = @$data_api->fault;
            preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
            //print_r($output);
            $end = end($output);
            if (is_array($end)) {
                $end_end = end($end);
                $ruid    = str_replace("!-- RUID: [", '', $end_end);
                $ruid    = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_airbnb($ruid, 'Update Availability', $owner_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_airbnb($ruid, 'Update Availability', $owner_id, $hotel_id);
            }
            if ($error) {
                $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                //$this->session->set_flashdata('bulk_error', (string)$data_api->fault->attributes()->string);
            } else {
                return true;
            }
        } else {
            $subrooms = get_data(PMS_BOOKING, array(
                'partner_id' => $owner_id,
                'property_id' => $hotel_id,
                'channel_id' => $channel_id,
                'B_room_id' => $mp_details->B_room_id
            ))->result();
            
            foreach ($subrooms as $subroom) {
                
                $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                <room id="' . $mp_details->B_room_id . '">
                <date value="' . $update_date . '" >
                <rate id="' . $subroom->B_rate_id . '"/>';
                $xml_data .= '<roomstosell>' . $availability . '</roomstosell>';
                $xml_data .= '</date></room></request>';
                $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                $ch  = curl_init($URL);
                //curl_setopt($ch, CURLOPT_MUTE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output   = curl_exec($ch);
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                
                if ($error) {
                    $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'PMS Availability Update', date('m/d/Y h:i:s a', time()));
                    
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_airbnb($ruid, 'PMS Update Availability', $owner_id, $hotel_id);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_airbnb($ruid, 'PMS Update Availability', $owner_id, $hotel_id);
                }
            }
            return true;
        }
    }
    
    function get_mapping_rooms($channel_id, $type = '')
    {
        if (user_type() == '1' || admin_id() != '' && admin_type() == '1') {
            $owner_id = current_user_type();
        } elseif (user_type() == '2') {
            $owner_id = current_user_type();
        }
        if ($channel_id == '9') {
            if ($type != 'update') {
                $connected_room = get_data(MAP, array(
                    'owner_id' => $owner_id,
                    'hotel_id' => hotel_id(),
                    'channel_id' => $channel_id
                ), 'import_mapping_id')->result_array();
                if (count($connected_room) != 0) {
                    foreach ($connected_room as $import_mapping) {
                        extract($import_mapping);
                        $import[] = $import_mapping_id;
                    }
                } else {
                    $import[] = '';
                }
            } else {
                $import[] = '';
            }
            $clean = cleanArray($import);
            $this->db->select('B.import_mapping_id, B.rate_name, B.nameRoomType');
            if ($clean != '') {
                $this->db->where_not_in('B.import_mapping_id', $import);
            }
            $this->db->where(array(
                'user_id' => $owner_id,
                'hotel_id' => hotel_id()
            ));
            $result = $this->db->get('import_mapping_AIRBNB' . ' as B');
            if ($result != '') {
                return $result->result();
            } else {
                return false;
            }
        }
    }
    function ReservationList($hotelid,$date1,$date2,$status)
    {
        $sta="and case a.ResStatus when 'Cancelled' then 0 when 'new' then 1  when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 when 'Checkin' then 5 when 'Checkout' then 6 else 7 end in ($status) ";

        $airbnb=$this->db->query("SELECT a.Import_reservation_ID reservation_id, ResID_Value reservation_code, case a.ResStatus when 'Cancelled' then 0 when 'new' then 1  when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 when 'Checkin' then 5 when 'Checkout' then 6 else 7 end status,
              a.name  Full_Name , d.property_id room_id , 9 channel_id, arrival start_date,a.RoomNumber, departure end_date,
            ImportDate booking_date, f.currency_id currency_id, AmountAfterTax price, DATEDIFF( departure,arrival) num_nights, 1 num_rooms,ImportDate current_date_time,
            g.channel_name channel_name, e.property_name roomName FROM 
            import_reservation_AIRBNB a
            left join import_mapping_AIRBNB c on a.RoomTypeCode=c.RoomId and a.hotel_id=c.hotel_id
            left join roommapping d on d.channel_id=9 and  c.import_mapping_id=d.import_mapping_id and c.channel_id=d.channel_id
            left join manage_property e on d.property_id = e.property_id
            left join currency f on a.Currency=f.currency_code
            left join manage_channel g on  c.channel_id=g.channel_id
            where 
            a.hotel_id=$hotelid and (arrival between '$date1' and '$date2' ) ".(strlen($status)==0?'':$sta)."
            order by a.ImportDate desc;")->result_array();

            return $airbnb;
    }
    function reservationdetails($channelId,$reservationId,$hotelid)
    {

            $data['LogoReservation']=base64_encode(file_get_contents("uploads/channels/".get_data('manage_channel',array('channel_id'=>$channelId))->row()->logo_channel));

            $result=$this->db->query("select a.Import_reservation_ID reservation_id, ResID_Value reservation_code, RoomTypeCode,0 rate_id, case a.ResStatus when 'Cancelled' then 0 when 'new' then 1  when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 else 5 end statusId, ResStatus status, a.RoomNumber, a.arrival checkin,departure checkout, datediff(a.departure,a.arrival) num_nights,Child max_children,Adult numberofguests, a.name guest_name,AmountAfterTax,a.Currency
             from  import_reservation_AIRBNB a 
             where a.Import_reservation_ID=$reservationId and a.hotel_id=$hotelid")->row_array();

               $roomtype =$this->db->query("select property_name, b.property_id, a.RoomName
                from import_mapping_AIRBNB a
                left join roommapping b on a.import_mapping_id =b.import_mapping_id and b.channel_id = $channelId
                left join manage_property c on b.property_id=c.property_id
                where a.hotel_id =$hotelid   and  a.RoomId =".$result['RoomTypeCode'])->row_array();



            $data['ccname']='';
            $data['ccnumber']='';
            $data['ccmonth']='';
            $data['ccyear']='';
            $data['cccvv']='';
            $data['cctype']='';
            $data['ChannelName']='AIRBNB';
            $data['channelId']=$channelId;
            $data['reservatioID']=$result['reservation_id'];
            $data['reservationNumber']=$result['reservation_code'];
            $data['roomTypeID']=$roomtype['property_id'];
            $data['roomTypeName']=$roomtype['property_name'];
            $data['statusId']=$result['statusId'];
            $data['status']=$result['status'];
            $data['roomNumber']=$result['RoomNumber'];
            $data['checkin']=$result['checkin'];
            $data['checkout']=$result['checkout'];
            $data['arrivalTime']='Undefined';
            $data['numberNight']=$result['num_nights'];
            $data['numberAdults']=$result['numberofguests'];
            $data['numberChilds']=$result['max_children'];
            $data['guestFullName']=$result['guest_name'];
            $data['email']='';
            $data['mobiler']='';
            $data['address']='';
            $data['city']='';
            $data['state']='';
            $data['country']='';
            $data['zipCode']='';
            $data['notes']='';
            $data['commision']=0.00;
            $data['discount']=0.00;
            $data['channelRoomName']=$roomtype['RoomName'];
            $data['mealsInclude']='';
            $price = 0;

            $data['promoCode'] = "No";
            for($i=0; $i<$data['numberNight']; $i++){

                 $data['rateDetailsPrice'][] =number_format($result['AmountAfterTax']/$data['numberNight'], 2, '.', '') ;
            }

            
            $data['currency']=$result['Currency'];
            $data['extrasInfo']=$this->reservation_model->extrasAllTotal($channelId,$result['reservation_id']);
            $data['totalStay']=number_format((float)$result['AmountAfterTax'], 2, '.', ''); 
            $data['grandtotal']=number_format(($result['AmountAfterTax']+$data['extrasInfo']['total']), 2, '.', '');
            $data['extrastoroom']=get_data("room_extras", array("room_id"=>$data['roomTypeID']))->result_array();
            return $data;
    }
    
    
}