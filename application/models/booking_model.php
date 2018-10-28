 <?php
ini_set('memory_limit', '-1');
ini_set('display_erros', '1');
class booking_model extends CI_Model
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

        $headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
        $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
        $headers .= "CC: support <felix@hoteratus.com>\r\n";
        $headers .= "BCC: datahernandez@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $BookingErrors = "";
        //Dealles de CAnal 

      
        $ch_details    = get_data(CONNECT, array(
            'user_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => "2"
        ))->row();
        //Busco las Habiaciones Conectadas al Canal 36 Despegar
        
        $room_mapping = get_data(MAP, array(
            'owner_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => 2,
            'enabled' => 'enabled'
        ))->result_array();
        
        foreach ($room_mapping as $Mapping => $Mappingvalue) {
            
            if ($Mappingvalue["rate_id"] == "0") {
                
                $Data = $this->db->query("select *  from room_update where owner_id =$userid and hotel_id = $hotelid and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details = get_data('import_mapping_BOOKING', array(
                    'owner_id' => $userid,
                    'hotel_id' => $hotelid,
                    'channel_id' => "2",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                    <request>
                    <username>' . $ch_details->user_name . '</username>
                    <password>' . $ch_details->user_password . '</password>
                    <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                    <room id="' . $mp_details->B_room_id . '">';
                
                
                foreach ($Data as $key => $value) {
                    
                    
                    $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                    $start_date = $datevalue->format('Y-m-d');
                    $end_date   = date("Y-m-d", strtotime($start_date));
                    
                    $xml_data .= "<date value1='" . $start_date . "' >'";
                    $xml_data .= '<rate id="' . $mp_details->B_rate_id . '"/>';
                    
                    //Restrinciones y Disponibilidad
                    if ($value['availability'] >= 0) {
                        $xml_data .= '<roomstosell>' . $value['availability'] . '</roomstosell>';
                    }
                    
                    if ($value["price"] >= 0) {
                        $price = $value["price"] * $RateConvertion;
                        $xml_data .= '<price>' . $price . '</price>';
                    }
                    
                    if ($value["stop_sell"] >= 0) {
                        $xml_data .= '<closed>' . ($value['stop_sell'] == 1 ? 1 : 0) . '</closed>';
                    }
                    
                    if ($value['minimum_stay'] >= 0) {
                        $xml_data .= '<minimumstay>' . $value['minimum_stay'] . '</minimumstay>';
                    }
                    
                    if (is_numeric($value['cta'])) {
                        $xml_data .= '<closedonarrival>' . $value['cta'] . '</closedonarrival>';
                    }
                    
                    if (is_numeric($value['ctd'])) {
                        $xml_data .= '<closedondeparture>' . $value['ctd'] . '</closedondeparture>';
                    }
                    
                    $xml_data .= '</date>';
                    
                } //
                $xml_data .= '</room></request>';
                
                $mail_data = '<strong> Request </strong> <br>';
                $mail_data .= $xml_data;
                
                $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                $ch  = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_data .= '<strong> Response </strong> <br>';
                $mail_data .= $output;
                 mail("xml@hoteratus.com", " Booking.Com Request and Response Rooms Syncro" . $hotelid, $mail_data, $headers);
 
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                /* echo($output); */
                if ($error) {
                    $this->inventory_model->store_error($userid, $hotelid, 2, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $BookingErrors .= "* Booking Error Sincro Rooms" . $error;
                } else {
                    $BookingErrors .= "*Booking Synced Rooms";
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                $end = end($output);
                
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', $userid, $hotelid);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update',$userid, $hotelid);
                }
                
                
                
                
                
                // Para enviar un correo HTML, debe establecerse la cabecera Content-type
                $cabeceras = 'MIME-Version: 1.0' . "\r\n";
                $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                
                // Cabeceras adicionales
                $cabeceras .= 'To: xml_conecteion <xml@hoteratus.com>' . "\r\n";
                $cabeceras .= 'From: Info <info@hoteratus.com>' . "\r\n";
                $cabeceras .= 'Cc: support <felix@hoteratus>' . "\r\n";
                $cabeceras .= 'Bcc: datahernandez@gmail.com' . "\r\n";
                
                // Enviarlo
                //mail("datahernandez@gmail.com", " Despegar.Com Sincro Request and Response ", $mail_Precio,$cabeceras);
            } else {
                $Data = $this->db->query("select *  from room_rate_types_base where owner_id =" .$userid . " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and rate_types_id = " . $Mappingvalue["rate_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details = get_data('import_mapping_BOOKING', array(
                    'owner_id' => $userid ,
                    'hotel_id' => $hotelid,
                    'channel_id' => "2",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                    <request>
                    <username>' . $ch_details->user_name . '</username>
                    <password>' . $ch_details->user_password . '</password>
                    <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                    <room id="' . $mp_details->B_room_id . '">';
                
                
                foreach ($Data as $key => $value) {
                    $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                    $start_date = $datevalue->format('Y-m-d');
                    $end_date   = date("Y-m-d", strtotime($start_date));
                    
                    $xml_data .= "<date value1='" . $start_date . "' >'";
                    $xml_data .= '<rate id="' . $mp_details->B_rate_id . '"/>';
                    
                    //Restrinciones y Disponibilidad
                    if ($value['availability'] >= 0) {
                        $xml_data .= '<roomstosell>' . $value['availability'] . '</roomstosell>';
                    }
                    
                    if ($value["price"] >= 0) {
                        $price = $value["price"] * $RateConvertion;
                        $xml_data .= '<price>' . $price . '</price>';
                    }
                    
                    if ($value["stop_sell"] >= 0) {
                        $xml_data .= '<closed>' . ($value['stop_sell'] == 1 ? 1 : 0) . '</closed>';
                    }
                    
                    if ($value['minimum_stay'] >= 0) {
                        $xml_data .= '<minimumstay>' . $value['minimum_stay'] . '</minimumstay>';
                    }
                    
                    if (is_numeric($value['cta'])) {
                        $xml_data .= '<closedonarrival>' . $value['cta'] . '</closedonarrival>';
                    }
                    
                    if (is_numeric($value['ctd'])) {
                        $xml_data .= '<closedondeparture>' . $value['ctd'] . '</closedondeparture>';
                    }
                    
                    $xml_data .= '</date>';
                    
                } //
                $xml_data .= '</room></request>';
                
                $mail_data = '<strong> Request </strong> <br>';
                $mail_data .= $xml_data;
                
                $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                $ch  = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_data .= '<strong> Response </strong> <br>';
                $mail_data .= $output;
                mail("xml@hoteratus.com", " Booking.Com Request and Response RATETYPE Syncro" . $hotelid, $mail_data, $headers);
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                /* echo($output); */
                if ($error) {
                    $this->inventory_model->store_error($userid, $hotelid, $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $BookingErrors .= "* Booking Error Sincro RATETYPE" . $error;
                } else {
                    $BookingErrors .= "*Booking Synced RATETYPE";
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                $end = end($output);
                
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', $userid, $hotelid);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', $userid, $hotelid);
                }
            }
        }
        
        
        return $BookingErrors;
    }
    function inline_edit_main_calendar($table, $room_id, $rate_type_id, $update_date, $name, $import_mapping_id, $mapping_id, $guest_count, $refunds)
    {
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            $channel_id = 2;
            
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
            
            $mp_details = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id,
                'import_mapping_id' => $import_mapping_id
            ))->row();
            
            $mapping_values = get_data('mapping_values', array(
                'mapping_id' => $mapping_id
            ))->row_array();
            /* echo $this->db->last_query();
            print_r($mapping_values);*/
            
            if ($mapping_values) {
                $label   = explode(',', $mapping_values['label']);
                $value   = explode(',', $mapping_values['value']);
                $set_arr = array_combine($label, $value);
                foreach ($set_arr as $k => $v) {
                    if ($k == "MaxStay") {
                        $maximum_stay = $v;
                    }
                    if ($k == "SingleUse" && $update_Details->price != "") {
                        if ($v != "") {
                            if (strpos($v, '+') !== FALSE) {
                                $opr = explode('+', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $update_Details->price + $opr[1];
                                } else if (is_numeric($opr[0])) {
                                    $ex_price = $update_Details->price + $opr[0];
                                } else {
                                    if (strpos($opr[1], '%')) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($update_Details->price * $per[0]) / 100;
                                            $ex_price  = $update_Details->price + $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%')) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($update_Details->price * $per[0]) / 100;
                                            $ex_price  = $update_Details->price + $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '-') !== FALSE) {
                                $opr = explode('-', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $update_Details->price - $opr[1];
                                } elseif (is_numeric($opr[0])) {
                                    $ex_price = $update_Details->price - $opr[0];
                                } else {
                                    if (strpos($opr[1], '%') !== FALSE) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($update_Details->price * $per[0]) / 100;
                                            $ex_price  = $update_Details->price - $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%') !== FALSE) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($update_Details->price * $per[0]) / 100;
                                            $ex_price  = $update_Details->price - $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '%') !== FALSE) {
                                $opr = explode('%', $v);
                                if (is_numeric($opr[1])) {
                                    $per_price = ($$update_Details->price * $opr[1]) / 100;
                                    $ex_price  = $update_Details->price + $per_price;
                                } elseif (is_numeric($opr[0])) {
                                    $per_price = ($update_Details->price * $opr[0]) / 100;
                                    $ex_price  = $update_Details->price + $per_price;
                                }
                            } else {
                                $ex_price = $update_Details->price + $v;
                            }
                            $single_user_price = $ex_price;
                        }
                    }
                }
            }
            
            if ($mp_details->B_rate_id != 0) {
                $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
            <request>
            <username>' . $ch_details->user_name . '</username>
            <password>' . $ch_details->user_password . '</password>
            <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
            <room id="' . $mp_details->B_room_id . '">
            <date value="' . $separate_date . '" >
            <rate id="' . $mp_details->B_rate_id . '"/>';
                
                $xml_data .= '<roomstosell>' . $update_Details->availability . '</roomstosell>';
                
                $xml_data .= '<price>' . $update_Details->price . '</price>';
                if (isset($single_user_price)) {
                    $xml_data .= '<price1>' . $single_user_price . '</price1>';
                }
                $xml_data .= '<closed>' . $update_Details->stop_sell . '</closed> 
                                <minimumstay>' . $update_Details->minimum_stay . '</minimumstay>';
                
                if (isset($maximum_stay)) {
                    $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                }
                $xml_data .= '<closedonarrival>' . $update_Details->cta . '</closedonarrival>
                                <closedondeparture>' . $update_Details->ctd . '</closedondeparture>';
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
                if (@$data_api->fault) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Inline edit', date('m/d/Y h:i:s a', time()));
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Inline Edit', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Inline Edit', current_user_type(), hotel_id());
                }
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
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
                <date value="' . $separate_date . '" >
                <rate id="' . $subroom->B_rate_id . '"/>';
                    
                    $xml_data .= '<roomstosell>' . $update_Details->availability . '</roomstosell>';
                    
                    
                    $xml_data .= '<roomstosell>' . $update_Details->availability . '</roomstosell>';
                    
                    $xml_data .= '<price>' . $update_Details->price . '</price>';
                    if (isset($single_user_price)) {
                        $xml_data .= '<price1>' . $single_user_price . '</price1>';
                    }
                    $xml_data .= '<closed>' . $update_Details->stop_sell . '</closed> 
                                    <minimumstay>' . $update_Details->minimum_stay . '</minimumstay>';
                    
                    if (isset($maximum_stay)) {
                        $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                    }
                    $xml_data .= '<closedonarrival>' . $update_Details->cta . '</closedonarrival>
                                    <closedondeparture>' . $update_Details->ctd . '</closedondeparture>';
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
                    if (@$data_api->fault) {
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Inline edit', date('m/d/Y h:i:s a', time()));
                    }
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    //print_r($output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Inline Edit', current_user_type(), hotel_id());
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Inline Edit', current_user_type(), hotel_id());
                    }
                }
            }
        }
    }
    
    function full_calendar_update($ch_data, $udata, $update_date, $priceAmount, $import_mapping_id, $mapping_id)
    {
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            extract($ch_data);
            extract($udata);
            
            $channel_id = 2;
            $ch_details = get_data(CONNECT, array(
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => "2"
            ))->row();
            
            $mp_details     = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => "2",
                'import_mapping_id' => $import_mapping_id
            ))->row();
            $mapping_values = get_data('mapping_values', array(
                'mapping_id' => $mapping_id
            ))->row_array();
            if ($mapping_values) {
                $label   = explode(',', $mapping_values['label']);
                $value   = explode(',', $mapping_values['value']);
                $set_arr = array_combine($label, $value);
                foreach ($set_arr as $k => $v) {
                    if ($k == "MaxStay") {
                        $maximum_stay = $v;
                    }
                    if ($k == "SingleUse" && $priceAmount != "") {
                        if ($v != "") {
                            if (strpos($v, '+') !== FALSE) {
                                $opr = explode('+', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $priceAmount + $opr[1];
                                } else if (is_numeric($opr[0])) {
                                    $ex_price = $priceAmount + $opr[0];
                                } else {
                                    if (strpos($opr[1], '%')) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($priceAmount * $per[0]) / 100;
                                            $ex_price  = $priceAmount + $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%')) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($priceAmount * $per[0]) / 100;
                                            $ex_price  = $priceAmount + $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '-') !== FALSE) {
                                $opr = explode('-', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $priceAmount - $opr[1];
                                } elseif (is_numeric($opr[0])) {
                                    $ex_price = $priceAmount - $opr[0];
                                } else {
                                    if (strpos($opr[1], '%') !== FALSE) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($priceAmount * $per[0]) / 100;
                                            $ex_price  = $priceAmount - $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%') !== FALSE) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($priceAmount * $per[0]) / 100;
                                            $ex_price  = $priceAmount - $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '%') !== FALSE) {
                                $opr = explode('%', $v);
                                if (is_numeric($opr[1])) {
                                    $per_price = ($priceAmount * $opr[1]) / 100;
                                    $ex_price  = $priceAmount + $per_price;
                                } elseif (is_numeric($opr[0])) {
                                    $per_price = ($priceAmount * $opr[0]) / 100;
                                    $ex_price  = $priceAmount + $per_price;
                                }
                            } else {
                                $ex_price = $priceAmount + $v;
                            }
                            $single_user_price = $ex_price;
                        }
                    }
                }
            }
            if ($stop_sell == 1 && $open_room == 0) {
                $closed = 1;
            } else if ($stop_sell == 0 && $open_room == 1) {
                $closed = 0;
            } else if ($stop_sell == 1 && $open_room == 1) {
                $closed = 1;
            } else if ($stop_sell == 0 && $open_room == 0) {
                $closed = 0;
            } else {
                $closed = 0;
            }
            
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
                $xml_data .= '<price>' . $priceAmount . '</price>';
                if (isset($single_user_price)) {
                    $xml_data .= '<price1>' . $single_user_price . '</price1>';
                }
                $xml_data .= '<closed>' . $closed . '</closed>
                                <minimumstay>' . $minimum_stay . '</minimumstay>';
                if (isset($maximum_stay)) {
                    $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                }
                
                $xml_data .= '<closedonarrival>' . $cta . '</closedonarrival>
                                <closedondeparture>' . $ctd . '</closedondeparture>';
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
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Inline edit', date('m/d/Y h:i:s a', time()));
                }
                
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Full Calendar Update', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Full Calendar Update', current_user_type(), hotel_id());
                }
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => $channel_id,
                    'B_room_id' => $mp_details->B_room_id
                ))->result();
                foreach ($subrooms as $subroom) {
                    
                    if ($subroom->B_rate_id != 0) {
                        
                        $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
                        <request>
                        <username>' . $ch_details->user_name . '</username>
                        <password>' . $ch_details->user_password . '</password>
                        <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                        <room id="' . $mp_details->B_room_id . '">
                        <date value="' . $update_date . '" >
                        <rate id="' . $subroom->B_rate_id . '"/>';
                        $xml_data .= '<roomstosell>' . $availability . '</roomstosell>';
                        $xml_data .= '<price>' . $priceAmount . '</price>';
                        if (isset($single_user_price)) {
                            $xml_data .= '<price1>' . $single_user_price . '</price1>';
                        }
                        $xml_data .= '<closed>' . $closed . '</closed>
                                    <minimumstay>' . $minimum_stay . '</minimumstay>';
                        if (isset($maximum_stay)) {
                            $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                        }
                        
                        $xml_data .= '<closedonarrival>' . $cta . '</closedonarrival>
                                    <closedondeparture>' . $ctd . '</closedondeparture>';
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
                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Full Update', date('m/d/Y h:i:s a', time()));
                        }
                        
                        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                        //print_r($output);
                        $end = end($output);
                        if (is_array($end)) {
                            $end_end = end($end);
                            $ruid    = str_replace("!-- RUID: [", '', $end_end);
                            $ruid    = trim(str_replace('] --', '', $ruid));
                            $this->store_ruid_booking($ruid, 'Full Calendar Update', current_user_type(), hotel_id());
                        } else {
                            $ruid = str_replace("!-- RUID: [", '', $end);
                            $ruid = trim(str_replace('] --', '', $ruid));
                            $this->store_ruid_booking($ruid, 'Full Calendar Update', current_user_type(), hotel_id());
                        }
                    }
                }
            }
        }
        
    }
    
    function bulk_update($product, $import_mapping_id, $maping_id, $price)
    {

        $headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
        $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
        $headers .= "CC: support <felix@hoteratus.com>\r\n";
        $headers .= "BCC: datahernandez@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;


        if ($chk_allow == 2 || $chk_allow == 3) {
            $up_days    =$product['days'];
            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', @$product['start_date'])));
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', @$product['end_date'])));
            
            if (@$product['days'] != "") {
                //echo 'first'; die;
                $string = $this->getDateForSpecificDayBetweenDates($start_date, $end_date, implode(',',$product['days']));
            } else {
                /* echo 'second'; die; */
                $string = "from='" . $start_date . "' to='" . $end_date . "'";
            }
            /* echo $string; die; */
            $ch_details = get_data(CONNECT, array(
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => "2"
            ))->row();
            $channel_id = 2;
            
            $mp_details     = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => "2",
                'import_mapping_id' => $import_mapping_id
            ))->row();
            $mapping_values = get_data('mapping_values', array(
                'mapping_id' => $maping_id
            ))->row_array();
            
            if ($mapping_values) {
                $label   = explode(',', $mapping_values['label']);
                $value   = explode(',', $mapping_values['value']);
                $set_arr = array_combine($label, $value);
                foreach ($set_arr as $k => $v) {
                    if ($k == "MaxStay") {
                        $maximum_stay = $v;
                    }
                    if ($k == "SingleUse" && $price != "") {
                        if ($v != "") {
                            if (strpos($v, '+') !== FALSE) {
                                $opr = explode('+', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $price + $opr[1];
                                } else if (is_numeric($opr[0])) {
                                    $ex_price = $price + $opr[0];
                                } else {
                                    if (strpos($opr[1], '%')) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($price * $per[0]) / 100;
                                            $ex_price  = $price + $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%')) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($price * $per[0]) / 100;
                                            $ex_price  = $price + $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '-') !== FALSE) {
                                $opr = explode('-', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = $price - $opr[1];
                                } elseif (is_numeric($opr[0])) {
                                    $ex_price = $price - $opr[0];
                                } else {
                                    if (strpos($opr[1], '%') !== FALSE) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($price * $per[0]) / 100;
                                            $ex_price  = $price - $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%') !== FALSE) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = ($price * $per[0]) / 100;
                                            $ex_price  = $price - $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '%') !== FALSE) {
                                $opr = explode('%', $v);
                                if (is_numeric($opr[1])) {
                                    $per_price = (@$product['price'] * $opr[1]) / 100;
                                    $ex_price  = @$product['price'] + $per_price;
                                } elseif (is_numeric($opr[0])) {
                                    $per_price = (@$product['price'] * $opr[0]) / 100;
                                    $ex_price  = @$product['price'] + $per_price;
                                }
                            } else {
                                $ex_price = $price + $v;
                            }
                            $single_user_price = $ex_price;
                        }
                    }
                }
            }
            
            //$closed = 0;
            if (@$product['stops'] == "1") {
                $closed = 1;
            } else if (@$product['stops'] != "1") {
                $closed = 0;
            }
            
            if ($mp_details->B_rate_id != 0) {
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                <room id="' . $mp_details->B_room_id . '">
                <date ' . $string . '>
                <rate id="' . $mp_details->B_rate_id . '"/>';
                if (@$product['availability'] != "") {
                    $xml_data .= '<roomstosell>' . @$product['availability'] . '</roomstosell>';
                    if (@$product['availability'] == 0) {
                        $closed = 0;
                    }
                }
                if (@$product['price'] != "") {
                    $xml_data .= '<price>' . $price . '</price>';
                }
                if (isset($single_user_price)) {
                    $xml_data .= '<price1>' . $single_user_price . '</price1>';
                }
                if (isset($closed)) {
                    $xml_data .= '<closed>' . $closed . '</closed>';
                }
                if (@$product['minimumstay'] != "") {
                    $xml_data .= '<minimumstay>' . @$product['minimumstay'] . '</minimumstay>';
                }
                if (isset($maximum_stay)) {

                    if (isset($maximum_stay)) {
                        if(strlen($maximum_stay)>0)
                        {
                            $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                        }
                        
                    }
                    
                }
                if ( @$product['cta'] != "") {
                    $xml_data .= '<closedonarrival>' . (@$product['cta']==2?0:@$product['cta']). '</closedonarrival>';
                }
                if (@$product['ctd']!= "") {
                    $xml_data .= '<closedondeparture>' .(@$product['ctd']==2?0:@$product['ctd']). '</closedondeparture>';
                }
                $xml_data .= '</date></room></request>';
                /* echo $xml_data;  */
                $mail_data = '<strong> Request </strong> <br>';
                $mail_data .= $xml_data;
                /* mail("test2osiz@gmail.com"," Booking.Com Request ",$xml_data); */
                $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                $ch  = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_data .= '<strong> Response </strong> <br>';
                $mail_data .= $output;
                 mail("xml@hoteratus.com", " Booking.Com Request and Response " .hotel_id() , $mail_data, $headers);
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                /* echo($output); */
                if ($error) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', (string) $data_api->fault->attributes()->string);
                } else {
					$this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
				}
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', current_user_type(), hotel_id());
                }

                return true;
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
                    'channel_id' => $channel_id,
                    'B_room_id' => $mp_details->B_room_id
                ))->result();
                
                foreach ($subrooms as $subroom) {
                    
                    $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                    <request>
                    <username>' . $ch_details->user_name . '</username>
                    <password>' . $ch_details->user_password . '</password>

                    <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                    <room id="' . $mp_details->B_room_id . '">

                    <date ' . $string . '>
                    <rate id="' . $subroom->B_rate_id . '"/>';
                    if (@$product['availability'] != "") {
                        $xml_data .= '<roomstosell>' . @$product['availability'] . '</roomstosell>';
                    }
                    if (@$product['price'] != "") {
                        $xml_data .= '<price>' . $price . '</price>';
                    }
                    if (isset($single_user_price)) {
                        $xml_data .= '<price1>' . $single_user_price . '</price1>';
                    }
                    if (isset($closed)) {
                        $xml_data .= '<closed>' . $closed . '</closed>';
                    }
                    if (@$product['minimumstay'] != "") {
                        $xml_data .= '<minimumstay>' . @$product['minimumstay'] . '</minimumstay>';
                    }
                    if (isset($maximum_stay)) {
                        if(strlen($maximum_stay)>0)
                        {
                            $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                        }
                        
                    }
                    if (is_numeric(@$product['cta'])) {
                        $xml_data .= '<closedonarrival>' .(@$product['cta']==2?0:@$product['cta']). '</closedonarrival>';
                    }
                    if (is_numeric(@$product['ctd'])) {
                        $xml_data .= '<closedondeparture>' . (@$product['ctd']==2?0:@$product['ctd']) . '</closedondeparture>';
                    }
                    $xml_data .= '</date></room></request>';
                    $mail_data = '<strong> Request </strong> <br>';
                    $mail_data .= $xml_data;
                    /* echo $xml_data; */
                    /* mail("test2osiz@gmail.com"," Booking.Com Request ",$xml_data); */
                    $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                    $ch  = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    //print_r($output);
                    $mail_data .= '<strong> Response </strong> <br>';
                    $mail_data .= $output;

                         mail("xml@hoteratus.com", " Booking.Com Request and Response " . $hotelid, $mail_data, $headers);
                    $data_api = simplexml_load_string($output);
                    $error    = @$data_api->fault;
                    
                    if ($error) {
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', (string) $data_api->fault->attributes()->string);
                    } else {
						$this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
					}
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'bulk_update', current_user_type(), hotel_id());
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'bulk_update', current_user_type(), hotel_id());
                    }
                }
                return true;
            }
        } else {
            return false;
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
                $this->inventory_model->store_error(current_user_type(), hotel_id(), "2", 'Invalid WeekDay', 'Bulk Update', date('m/d/Y h:i:s a', time()));
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
        return $string;
    }
    
    function update_stopsell($date, $room_id, $import_mapping_id)
    {
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            $update_date          = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
            $channel_id           = 2;
            $availability_details = get_data(TBL_UPDATE, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'individual_channel_id' => '2',
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
            
            $mp_details = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id,
                'import_mapping_id' => $import_mapping_id
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
                if (isset($closed)) {
                    $xml_data .= '<closed>' . $closed . '</closed>';
                }
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
                if ($data_api->fault) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update Stop Sell', date('m/d/Y h:i:s a', time()));
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update Stop Sell', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update Stop Sell', current_user_type(), hotel_id());
                }
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
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
                    if (isset($closed)) {
                        $xml_data .= '<closed>' . $closed . '</closed>';
                    }
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
                    if ($data_api->fault) {
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update Stop Sell', date('m/d/Y h:i:s a', time()));
                    }
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    //print_r($output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update Stop Sell', current_user_type(), hotel_id());
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update Stop Sell', current_user_type(), hotel_id());
                    }
                }
                
            }
        }
    }
    
    function update_cta($date, $room_id, $import_mapping_id)
    {
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            $update_date          = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
            $channel_id           = 2;
            $availability_details = get_data(TBL_UPDATE, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'individual_channel_id' => '2',
                'room_id' => $room_id,
                'separate_date' => $date
            ))->row();
            
            if ($availability_details->cta == 0) {
                $closed = 0;
            } else if ($availability_details->cta == 1) {
                $closed = 1;
            }
            //echo $availability_details->stop_sell;
            $ch_details = get_data(CONNECT, array(
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id
            ))->row();
            
            $mp_details = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id,
                'import_mapping_id' => $import_mapping_id
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
                if (isset($closed)) {
                    $xml_data .= '<closedonarrival>' . $closed . '</closedonarrival>';
                }
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
                if ($data_api->fault) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update CTA', date('m/d/Y h:i:s a', time()));
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update CTA', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update CTA', current_user_type(), hotel_id());
                }
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
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
                    if (isset($closed)) {
                        $xml_data .= '<closedonarrival>' . $closed . '</closedonarrival>';
                    }
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
                    if ($data_api->fault) {
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update CTA', date('m/d/Y h:i:s a', time()));
                    }
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    //print_r($output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update CTA', current_user_type(), hotel_id());
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update CTA', current_user_type(), hotel_id());
                    }
                }
                
            }
        }
    }
    
    function update_ctd($date, $room_id, $import_mapping_id)
    {
        $chk_allow = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            $update_date          = date('Y-m-d', strtotime(str_replace('/', '-', $date)));
            $channel_id           = 2;
            $availability_details = get_data(TBL_UPDATE, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'individual_channel_id' => '2',
                'room_id' => $room_id,
                'separate_date' => $date
            ))->row();
            
            if ($availability_details->ctd == 0) {
                $closed = 0;
            } else if ($availability_details->ctd == 1) {
                $closed = 1;
            }
            //echo $availability_details->stop_sell;
            $ch_details = get_data(CONNECT, array(
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id
            ))->row();
            
            $mp_details = get_data('import_mapping_BOOKING', array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'channel_id' => $channel_id,
                'import_mapping_id' => $import_mapping_id
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
                if (isset($closed)) {
                    $xml_data .= '<closedondeparture>' . $closed . '</closedondeparture>';
                }
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
                if ($data_api->fault) {
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update CTD', date('m/d/Y h:i:s a', time()));
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update CTD', current_user_type(), hotel_id());
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update CTD', current_user_type(), hotel_id());
                }
            } else {
                $subrooms = get_data('import_mapping_BOOKING', array(
                    'owner_id' => current_user_type(),
                    'hotel_id' => hotel_id(),
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
                    if (isset($closed)) {
                        $xml_data .= '<closedondeparture>' . $closed . '</closedondeparture>';
                    }
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
                    if ($data_api->fault) {
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $data_api->fault->attributes()->string, 'Update CTD', date('m/d/Y h:i:s a', time()));
                    }
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    //print_r($output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update CTD', current_user_type(), hotel_id());
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'Update CTD', current_user_type(), hotel_id());
                    }
                }
                
            }
        }
    }
    
    function importAvailabilities($user_id = "", $hotel_id = "", $channel, $mapping, $import_mapping_id, $start_date = "", $end_date = "")
    {
        /*$pagina_anterior=$_SERVER['HTTP_REFERER'];
        $host= $_SERVER["HTTP_HOST"];
        $url= $_SERVER["REQUEST_URI"];
        $self= "http://" . $host . $url;
        header("Location: $pagina_anterior");
        */
        
        
        if ($user_id == "") {
            $user_id = current_user_type();
        }
        if ($hotel_id == "") {
            $hotel_id = hotel_id();
        }
        makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"IN importAvailabilities\n");
        extract($channel);
        $AllTime = 390;
        if ($start_date == "") {
            $start_date = date('Y-m-d');
            $ndays      = 30;
        } else if ($start_date != "") {
            $ndays = _datebetween($start_date, $end_date);
        }
        
        
        
        $ch_details   = get_data(CONNECT, array(
            'user_id' => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "2"
        ))->row();
        $channel_id   = 2;
        $mp_details   = get_data('import_mapping_BOOKING', array(
            'owner_id' => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => $channel_id,
            'import_mapping_id' => $import_mapping_id,
            'channel_hotel_id' => $ch_details->hotel_channel_id
        ))->row();
        $Diascontados = 0;
        
        
        if (($ch_details->xml_type == 1 || $ch_details->xml_type == 2) && $start_date == "") {
            
            
            
            while ($Diascontados <= $AllTime) {
                $fecha = date("Y-m-d", strtotime("$start_date + " . $Diascontados . " days"));
                
                $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>  
                        <request>
                        <username>' . $ch_details->user_name . '</username>
                        <password>' . $ch_details->user_password . '</password>
                        <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                        <number_of_days>' . $ndays . '</number_of_days>
                        <start_date>' . $fecha . '</start_date>
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
                makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"importAvailabilities URL=>$URL\nXML=>$xml_data\nResponse=>$output\n");
                $x_r_rs_data['channel_id'] = '2';
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
                                makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"Before update_channel_calendar PARAMS=>--".$user_id."--".$hotel_id."--".json_encode($channel)."--".$availability."--".$sep_date."--".$mapping."---\n");
                                $callAvailabilities->update_channel_calendar($user_id, $hotel_id, $channel, $availability, $sep_date, $mapping);
                                $channel['channel_id'] = 0;
                                makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"Before update_channel_calendar1 PARAMS=>--".$user_id."--".$hotel_id."--".json_encode($channel)."--".$availability."--".$sep_date."--".$mapping."---\n");
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
                    $this->store_ruid_booking($ruid, 'Import Availability', $user_id, $hotel_id);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Import Availability', $user_id, $hotel_id);
                }
                
                
                $Diascontados = $Diascontados + 30;
                
                if ($Diascontados > $AllTime) {
                    echo 'Main Calender update from ' . $start_date . ' to ' . $fecha;
                    
                }
                
            }
            
        } elseif ($start_date != "") {
            
            
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
            makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"importAvailabilities2 URL=>$URL\nXML=>$xml_data\nResponse=>$output\n");
            $x_r_rs_data['channel_id'] = '2';
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
                            makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"Before update_channel_calendar2 PARAMS=>--".$user_id."--".$hotel_id."--".json_encode($channel)."--".$availability."--".$sep_date."--".$mapping."---\n");
                            $callAvailabilities->update_channel_calendar($user_id, $hotel_id, $channel, $availability, $sep_date, $mapping);
                            $channel['channel_id'] = 0;
                            makeLog('bookingResv_RespLog_'.$hotel_id .'.txt',"Before update_channel_calendar3 PARAMS=>--".$user_id."--".$hotel_id."--".json_encode($channel)."--".$availability."--".$sep_date."--".$mapping."---\n");
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
                $this->store_ruid_booking($ruid, 'Import Availability', $user_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_booking($ruid, 'Import Availability', $user_id, $hotel_id);
            }
        } else {
            return false;
        }
        
        
        
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
            'channel_id' => "2"
        ))->row();
        $channel_id = 2;
        
        $mp_details = get_data('import_mapping_BOOKING', array(
            'owner_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "2",
            'import_mapping_id' => $import_mapping_id
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
            makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"IN update_availability URL=>$URL\nXML=>$xml_data\nResponse=>$output\n"); 
            $data_api = simplexml_load_string($output);
            $error    = @$data_api->fault;
            preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
            //print_r($output);
            $end = end($output);
            if (is_array($end)) {
                $end_end = end($end);
                $ruid    = str_replace("!-- RUID: [", '', $end_end);
                $ruid    = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
            }
            if ($error) {
                $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                $this->session->set_flashdata('bulk_error', (string) $data_api->fault->attributes()->string);
            } else {
                return true;
            }
        } else {
            $subrooms = get_data('import_mapping_BOOKING', array(
                'owner_id' => $owner_id,
                'hotel_id' => $hotel_id,
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
                makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"IN update_availability1 URL=>$URL\nXML=>$xml_data\nResponse=>$output\n");
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                
                if ($error) {
                    $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'Availability Update', date('m/d/Y h:i:s a', time()));
                    
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                //print_r($output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
                }
            }
            return true;
        }
    }
    
    function send_mail_to_hoteliers($reservation_id, $user_id, $hotel_id)
    {
        $channel_data = get_data("user_connect_channel", array(
            "user_id" => $user_id,
            'hotel_id' => $hotel_id,
            'channel_id' => 2
        ))->row();
        
        if ($channel_data) {
            $sitename             = get_data('manage_hotel', array(
                'hotel_id' => $hotel_id,
                'owner_id' => $user_id
            ))->row()->property_name;
            $data['channel_name'] = get_data('manage_channel', array(
                'channel_id' => 2
            ))->row()->channel_name;
            
            $booking      = get_data("import_reservation_BOOKING", array(
                'id' => $reservation_id
            ))->row_array();
            $room_details = get_data("import_reservation_BOOKING_ROOMS", array(
                'reservation_id' => $reservation_id
            ))->result_array();
            
            
            $data['reservation_id'] = $booking['id'];
            $data['channel_name']   = "Booking.com";
            $data['subtotal']       = $booking['totalprice'];
            $data['grand_total']    = $booking['totalprice'];
            $data['status']         = $booking['status'];
            $data['booking_date']   = $booking['date'];
            $data['cc_name']        = $booking['cc_name'];
            $data['cc_number']      = $booking['cc_number'];
            $data['cc_cvc']         = $booking['cc_cvc'];
            $data['cc_type']        = $booking['cc_type'];
            $data['hotel_name']     = $booking['hotel_name'];
            $data['fullname']       = $booking['first_name'] . ' ' . $booking['last_name'];
            
            if ($booking['remarks'] != "") {
                $data['notes'] = $booking['remarks'];
            } else {
                $data['notes'] = "NONE";
            }
            
            $data['room_details'] = $room_details;
            $message              = $this->load->view("email/booking", $data, TRUE);
            
            $subject = "Reservation #" . $data['reservation_id'] . " From " . $data['channel_name'] . ' For Hotel ' . $booking['hotel_name'] . '  Status:' . $booking['status'];
            
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
            
            $headers = "From: info@hoteratus.com\r\n";
            $headers .= "Reply-To: info@hoteratus.com\r\n";
            $headers .= "CC: xml@hoteratus.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            
            mail("felix@hoteratus.com", $subject, $message, $headers);
            
            
        }
    }
    
    function store_ruid_booking($ruid, $place, $user_id = "", $hotel_id = "")
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
        $data['channel_id']  = 2;
        $data['date_time']   = date('m/d/Y h:i:s a', time());
        $data['ruid_occurs'] = $place;
        
        $this->db->insert("booking_ruid", $data);
    }
    
    function invalid_cc_request($bookingid, $owner_id, $hotel_id)
    {
        $ch_details = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "2"
        ))->row();
        $URL        = "https://supply-xml.booking.com/hotels/xml/reporting";
        $xml_data   = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <reservation_id>' . $bookingid . '</reservation_id>
                <report>cc_is_invalid</report></request>';
        
        $ch = curl_init($URL);
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
    
    function booking_no_show($bookingid, $owner_id, $hotel_id)
    {
        $ch_details = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => "2"
        ))->row();
        $res_id     = get_data(BOOK_ROOMS, array(
            'roomreservation_id' => $bookingid
        ))->row()->reservation_id;
        $URL        = "https://supply-xml.booking.com/hotels/xml/reporting";
        $xml_data   = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password> 
                <reservation_id>' . $res_id . '</reservation_id>              
                <report>
                    <is_no_show roomreservation_id="' . $bookingid . '" />
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
        $chk_allow = get_data(CONNECT, array(
            'user_id' => $owner_id,
            'hotel_id' => $hotel_id,
            'channel_id' => 2
        ))->row()->xml_type;
        if ($chk_allow == 2 || $chk_allow == 3) {
            $up_days    = explode(',', @$product['days']);
            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', @$product['start_date'])));
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', @$product['end_date'])));
            
            if (@$product['days'] != "") {
                //echo 'first'; die;
                $string = $this->getDateForSpecificDayBetweenDates($start_date, $end_date, @$product['days']);
            } else {
                //echo 'second'; die;
                $string = "from='" . $start_date . "' to='" . $end_date . "'";
            }
            $ch_details = get_data(CONNECT, array(
                'user_id' => $owner_id,
                'hotel_id' => $hotel_id,
                'channel_id' => 2
            ))->row();
            $channel_id = 2;
            
            $mp_details     = get_data(BOOKING, array(
                'owner_id' => $owner_id,
                'hotel_id' => $hotel_id,
                'channel_id' => "2",
                'import_mapping_id' => $import_mapping_id
            ))->row();
            $mapping_values = get_data('mapping_values', array(
                'mapping_id' => $mapping_id
            ))->row_array();
            if ($mapping_values) {
                $label   = explode(',', $mapping_values['label']);
                $value   = explode(',', $mapping_values['value']);
                $set_arr = array_combine($label, $value);
                foreach ($set_arr as $k => $v) {
                    if ($k == "MaxStay") {
                        $maximum_stay = $v;
                    }
                    if ($k == "SingleUse" && @$product['price'] != "") {
                        if ($v != "") {
                            if (strpos($v, '+') !== FALSE) {
                                $opr = explode('+', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = @$product['price'] + $opr[1];
                                } else if (is_numeric($opr[0])) {
                                    $ex_price = @$product['price'] + $opr[0];
                                } else {
                                    if (strpos($opr[1], '%')) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = (@$product['price'] * $per[0]) / 100;
                                            $ex_price  = @$product['price'] + $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%')) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = (@$product['price'] * $per[0]) / 100;
                                            $ex_price  = @$product['price'] + $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '-') !== FALSE) {
                                $opr = explode('-', $v);
                                if (is_numeric($opr[1])) {
                                    $ex_price = @$product['price'] - $opr[1];
                                } elseif (is_numeric($opr[0])) {
                                    $ex_price = @$product['price'] - $opr[0];
                                } else {
                                    if (strpos($opr[1], '%') !== FALSE) {
                                        $per = explode('%', $opr[1]);
                                        if (is_numeric($per[0])) {
                                            $per_price = (@$product['price'] * $per[0]) / 100;
                                            $ex_price  = @$product['price'] - $per_price;
                                        }
                                    } elseif (strpos($opr[0], '%') !== FALSE) {
                                        $per = explode('%', $opr[0]);
                                        if (is_numeric($per[0])) {
                                            $per_price = (@$product['price'] * $per[0]) / 100;
                                            $ex_price  = @$product['price'] - $per_price;
                                        }
                                    }
                                }
                            } elseif (strpos($v, '%') !== FALSE) {
                                $opr = explode('%', $v);
                                if (is_numeric($opr[1])) {
                                    $per_price = (@$product['price'] * $opr[1]) / 100;
                                    $ex_price  = @$product['price'] + $per_price;
                                } elseif (is_numeric($opr[0])) {
                                    $per_price = (@$product['price'] * $opr[0]) / 100;
                                    $ex_price  = @$product['price'] + $per_price;
                                }
                            } else {
                                $ex_price = @$product['price'] + $v;
                            }
                            $single_user_price = $ex_price;
                        }
                    }
                }
            }
            
            //$closed = 0;
            if ($stop_sale == 1) {
                $closed = 1;
            } else if ($stop_sale == "remove") {
                $closed = 0;
            }
            
            if ($mp_details->B_rate_id != 0) {
                $xml_data = '=<?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>' . $ch_details->user_name . '</username>
                <password>' . $ch_details->user_password . '</password>
                <hotel_id>' . $ch_details->hotel_channel_id . '</hotel_id>
                <room id="' . $mp_details->B_room_id . '">
                <date ' . $string . '>
                <rate id="' . $mp_details->B_rate_id . '"/>';
                if (@$product['availability'] != "") {
                    $xml_data .= '<roomstosell>' . @$product['availability'] . '</roomstosell>';
                }
                if (@$product['price'] != "") {
                    $xml_data .= '<price>' . @$product['price'] . '</price>';
                }
                if (isset($single_user_price)) {
                    $xml_data .= '<price1>' . $single_user_price . '</price1>';
                }
                if (isset($closed)) {
                    $xml_data .= '<closed>' . $closed . '</closed>';
                }
                if (@$product['minimum_stay'] != "") {
                    $xml_data .= '<minimumstay>' . @$product['minimum_stay'] . '</minimumstay>';
                }
                if (isset($maximum_stay)) {
                    $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                }
                if (is_numeric(@$product['cta'])) {
                    $xml_data .= '<closedonarrival>' . @$product['cta'] . '</closedonarrival>';
                }
                if (is_numeric(@$product['ctd'])) {
                    $xml_data .= '<closedondeparture>' . @$product['ctd'] . '</closedondeparture>';
                }
                $xml_data .= '</date></room></request>';
                $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                $ch  = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                
                $data_api = simplexml_load_string($output);
                $error    = @$data_api->fault;
                
                if ($error) {
                    $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'PMS Bulk Update', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', (string) $data_api->fault->attributes()->string);
                    $response_message['error'] = array(
                        'ChannelID' => $channel_id,
                        'RoomId' => $room_id,
                        'Error' => (string) $data_api->fault->attributes()->string
                    );
                }
                preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                $end = end($output);
                if (is_array($end)) {
                    $end_end = end($end);
                    $ruid    = str_replace("!-- RUID: [", '', $end_end);
                    $ruid    = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', $owner_id, $hotel_id);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'bulk_update', $owner_id, $hotel_id);
                }
                //return true; 
            } else {
                $subrooms = get_data(BOOKING, array(
                    'owner_id' => $owner_id,
                    'hotel_id' => $hotel_id,
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
                    <date ' . $string . '>
                    <rate id="' . $subroom->B_rate_id . '"/>';
                    if (@$product['availability'] != "") {
                        $xml_data .= '<roomstosell>' . @$product['availability'] . '</roomstosell>';
                    }
                    if (@$product['price'] != "") {
                        $xml_data .= '<price>' . @$product['price'] . '</price>';
                    }
                    if (isset($single_user_price)) {
                        $xml_data .= '<price1>' . $single_user_price . '</price1>';
                    }
                    if (isset($closed)) {
                        $xml_data .= '<closed>' . $closed . '</closed>';
                    }
                    if (@$product['minimum_stay'] != "") {
                        $xml_data .= '<minimumstay>' . @$product['minimum_stay'] . '</minimumstay>';
                    }
                    if (isset($maximum_stay)) {
                        $xml_data .= '<maximumstay>' . $maximum_stay . '</maximumstay>';
                    }
                    if (is_numeric(@$product['cta'])) {
                        $xml_data .= '<closedonarrival>' . @$product['cta'] . '</closedonarrival>';
                    }
                    if (is_numeric(@$product['ctd'])) {
                        $xml_data .= '<closedondeparture>' . @$product['ctd'] . '</closedondeparture>';
                    }
                    $xml_data .= '</date></room></request>';
                    $URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
                    $ch  = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output   = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $error    = @$data_api->fault;
                    
                    if ($error) {
                        $this->inventory_model->store_error($owner_id, $hotel_id, $channel_id, (string) $data_api->fault->attributes()->string, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $response_message['error'] = array(
                            'ChannelID' => $channel_id,
                            'RoomId' => $room_id,
                            'Error' => (string) $data_api->fault->attributes()->string
                        );
                    }
                    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
                    $end = end($output);
                    if (is_array($end)) {
                        $end_end = end($end);
                        $ruid    = str_replace("!-- RUID: [", '', $end_end);
                        $ruid    = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'PMS bulk_update', $owner_id, $hotel_id);
                    } else {
                        $ruid = str_replace("!-- RUID: [", '', $end);
                        $ruid = trim(str_replace('] --', '', $ruid));
                        $this->store_ruid_booking($ruid, 'PMS bulk_update', $owner_id, $hotel_id);
                    }
                }
                
            }
            return $response_message;
        } else {
            $response_message[] = array(
                'ChannelID' => 2,
                'RoomId' => $room_id,
                'Error' => "User cannot update Rate & Availability"
            );
            return $response_message;
        }
        
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
            'channel_id' => "2"
        ))->row();
        $channel_id = 2;
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
                $this->store_ruid_booking($ruid, 'PMS Import Availability', $user_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_booking($ruid, 'PMS Import Availability', $user_id, $hotel_id);
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
            'channel_id' => "2"
        ))->row();
        $channel_id = 2;
        
        $mp_details = get_data(PMS_BOOKING, array(
            'partner_id' => $owner_id,
            'property_id' => $hotel_id,
            'channel_id' => "2",
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
                $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
            } else {
                $ruid = str_replace("!-- RUID: [", '', $end);
                $ruid = trim(str_replace('] --', '', $ruid));
                $this->store_ruid_booking($ruid, 'Update Availability', $owner_id, $hotel_id);
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
                    $this->store_ruid_booking($ruid, 'PMS Update Availability', $owner_id, $hotel_id);
                } else {
                    $ruid = str_replace("!-- RUID: [", '', $end);
                    $ruid = trim(str_replace('] --', '', $ruid));
                    $this->store_ruid_booking($ruid, 'PMS Update Availability', $owner_id, $hotel_id);
                }
            }
            return true;
        }
    }

    function ReservationList($hotelid,$date1,$date2,$status)
    {
         $sta="and case a.status when 'cancelled' then 0 when 'new' then 1 when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 when 'Checkin' then 5 when 'Checkout' then 6 else 7 end in($status)";

        $booking=$this->db->query("SELECT b.room_res_id reservation_id, concat(a.id,'-',roomreservation_id) reservation_code, case a.status when 'cancelled' then 0 when 'new' then 1 when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 when 'Checkin' then 5 when 'Checkout' then 6 else 7 end status,
              guest_name  Full_Name , d.property_id room_id , 2 channel_id, arrival_date start_date,b.RoomNumber RoomNumber, departure_date end_date,
            b.date_time booking_date, f.currency_id currency_id, b.totalprice price, DATEDIFF( departure_date,arrival_date) num_nights, 1 num_rooms,b.current_date_time,
            g.channel_name channel_name, e.property_name roomName FROM 
            import_reservation_BOOKING a
            left join import_reservation_BOOKING_ROOMS b on a.id=b.reservation_id
            left join import_mapping_BOOKING c on b.id=c.B_room_id and b.rate_id=c.B_rate_id and a.hotel_hotel_id=c.hotel_id
            left join roommapping d on d.channel_id=2 and  c.import_mapping_id=d.import_mapping_id and c.channel_id=d.channel_id
            left join manage_property e on d.property_id = e.property_id
            left join currency f on a.currencycode=f.currency_code
            left join manage_channel g on  c.channel_id=g.channel_id
            where 
            a.hotel_hotel_id=$hotelid and (arrival_date between '$date1' and '$date2' ) ".(strlen($status)==0?'':$sta)."
            order by b.current_date_time desc;")->result_array();

            return $booking;
    }
    function reservationdetails($channelId,$reservationId,$hotelid)
    {

            $data['LogoReservation']=base64_encode(file_get_contents("uploads/channels/".get_data('manage_channel',array('channel_id'=>$channelId))->row()->logo_channel));

            $result=$this->db->query("select a.room_res_id reservation_id, concat(a.reservation_id,'-',roomreservation_id) reservation_code, a.id,rate_id, case a.status when 'cancelled' then 0 when 'new' then 1 when 'modified' then 2 when 'No Show' then 3 when 'Confirmed' then 4 else 5 end statusId, a.status, a.RoomNumber, a.arrival_date checkin,departure_date checkout, datediff(a.departure_date,a.arrival_date) num_nights,max_children,a.numberofguests, a.guest_name,b.email,b.telephone,b.address,b.city,b.countrycode,
                b.zip,b.remarks,a.commissionamount,a.name, a.day_price_detailss, b.currencycode, a.totalprice,cc_number,cc_name,cc_expiration_date,cc_cvc,cc_type
             from  import_reservation_BOOKING_ROOMS a 
             left join import_reservation_BOOKING b on a.reservation_id=b.id
             where a.room_res_id=$reservationId and a.hotel_hotel_id=$hotelid")->row_array();

               $roomtype =$this->db->query("select property_name, b.property_id
                from import_mapping_BOOKING a
                left join roommapping b on a.import_mapping_id =b.import_mapping_id and b.channel_id = $channelId
                left join manage_property c on b.property_id=c.property_id
                where a.hotel_id =$hotelid   and  a.B_room_id =".$result['id']." and  a.B_rate_id = ".$result['rate_id'])->row_array();

            require_once(APPPATH.'controllers/tokenex.php');
            $tokenex = new tokenex();
            $Valor=safe_b64decode($result['cc_number']);

            $data['ccname']=safe_b64decode($result['cc_name']);
            $data['ccnumber']=(strlen($Valor)>20?$Valor: $tokenex->Detokenizar($Valor));
            $data['ccmonth']=substr(safe_b64decode($result['cc_expiration_date']), 0,2);
            $data['ccyear']=substr(safe_b64decode($result['cc_expiration_date']), -2);
            $data['cccvv']=safe_b64decode($result['cc_cvc']);
            $data['cctype']=safe_b64decode($result['cc_type']);
            $data['ChannelName']='Booking.com';
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
            $data['email']=$result['email'];
            $data['mobiler']=$result['telephone'];
            $data['address']=$result['address'];
            $data['city']=$result['city'];
            $data['state']=$result['city'];
            $data['country']=$result['countrycode'];
            $data['zipCode']=$result['zip'];
            $data['notes']=$result['remarks'];
            $data['commision']=$result['commissionamount'];
            $data['discount']=0.00;
            $data['channelRoomName']=$result['name'];
            $data['mealsInclude']='';
            $inbwdays = explode('##',$result['day_price_detailss']);
            $price = 0;

            $data['promoCode'] = "No";
            for($i=0; $i<count($inbwdays); $i++){
                if($inbwdays[$i] != ""){
                    $baseRate = explode('~', $inbwdays[$i]);
                    $price = $price + end($baseRate);
                    $searchword = 'rewritten_from_name=';
                    $matches = array_values(array_filter($baseRate, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }));
                    if(count($matches)=='0')
                    {
                        $data['rateDetailsPrice'][] =number_format((float)end($baseRate), 2, '.', '') ;
                    }
                    else
                    {
                        $data['promoCode'] = "Yes";
                        $data['rateDetailsPrice'][] =number_format((float)end($baseRate), 2, '.', '').'-'.str_replace('rewritten_from_name=','',$matches[0]);
                    }
                }
            }

            
            $data['currency']=$result['currencycode'];
            $data['extrasInfo']=$this->reservation_model->extrasAllTotal($channelId,$result['reservation_id']);
            $data['totalStay']=number_format((float)$result['totalprice'], 2, '.', ''); 
            $data['grandtotal']=number_format(($data['totalStay']+$data['extrasInfo']['total']), 2, '.', '');
            $data['extrastoroom']=get_data("room_extras", array("room_id"=>$data['roomTypeID']))->result_array();
            return $data;
    }
}