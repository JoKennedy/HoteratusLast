 <?php

class despegar_model extends CI_Model
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
        
                            
                       
        $DespegarErrors = "";
        //Dealles de CAnal 
        $ch_details     = get_data(CONNECT, array(
            'user_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => "36"
        ))->row();
        //Busco las Habiaciones Conectadas al Canal 36 Despegar
        
        $room_mapping = get_data(MAP, array(
            'owner_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => 36,
            'enabled' => 'enabled'
        ))->result_array();
        
        foreach ($room_mapping as $Mapping => $Mappingvalue) {
            
            if ($Mappingvalue["rate_id"] == "0") {
                
                $Data = $this->db->query("select *  from room_update where owner_id =" . $userid. " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details     = get_data('import_mapping_DESPEGAR', array(
                    'user_id' => $userid,
                    'hotel_id' => $hotelid,
                    'channel_id' => "36",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                $Rate = $this->db->query('select * from despegar_rate where  user_id ="' . $userid . '" AND hotelid="' . $hotelid. '" AND HotelCode="' . $ch_details->hotel_channel_id . '" and RatePlanCode ="' . $mp_details->RateTypeCode . '"')->row();
                
                $ChargeTypeCode = $Rate->ChargeTypeCode;
                $CurrencyCode   = $Rate->CurrencyCode;
                
                $xml_Precio = '<OTA_HotelRatePlanNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                        <RatePlans HotelCode="' . $ch_details->hotel_channel_id . '">
                        <RatePlan ChargeTypeCode="' . $ChargeTypeCode . '" CurrencyCode="' . $CurrencyCode . '" RatePlanCode="' . $mp_details->RateTypeCode . '">
                        <Rates>';
                
                $xml_AvaRest = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                <AvailStatusMessages HotelCode="' . $ch_details->hotel_channel_id . '">';
                
                foreach ($Data as $key => $value) {
                    
                    $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                    $start_date = $datevalue->format('Y-m-d');
                    $end_date   = date("Y-m-d", strtotime($start_date . "+ 1 days"));
                    
                    //Actualizar Precios                
                    
                    if ($value["price"] >= 0) {
                        $price = $value["price"] * $RateConvertion;
                        
                        
                        if ($ChargeTypeCode == 19) {
                            if ($mp_details->TaxPolicyType == "Exclusive") {
                                $xml_Precio .= '<Rate Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '"> <BaseByGuestAmts> <BaseByGuestAmt AmountBeforeTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" /> </BaseByGuestAmts>
                                    </Rate>';
                            } else {
                                
                                $xml_Precio .= '<Rate Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '"> <BaseByGuestAmts> <BaseByGuestAmt AmountAfterTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" /> </BaseByGuestAmts>
                                </Rate>';
                            }
                        }
                    }
                    
                    
                    
                    //Restrinciones y Disponibilidad
                    if ($value["availability"] >= 0) {
                        $xml_AvaRest .= '<AvailStatusMessage BookingLimit="' . $value['availability'] . '" BookingLimitMessageType="SetLimit">
                            <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '" />
                            </AvailStatusMessage>';
                    }
                    
                    $xml_AvaRest .= '<AvailStatusMessage>
                          <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />';
                    
                    if ($value['minimum_stay'] >= 0) {
                        $xml_AvaRest .= '<LengthsOfStay> <LengthOfStay Time="' . $value['minimum_stay'] . '" TimeUnit="Day" MinMaxMessageType="MinLOS" /> </LengthsOfStay>';
                    }
                    
                    if ($value['cta'] >= 0) {
                        $xml_AvaRest .= '<RestrictionStatus Restriction="Arrival" Status="' . ($value['cta'] == 1 ? "Close" : "Open") . '" /> ';
                    }
                    
                    $xml_AvaRest .= ' </AvailStatusMessage>';
                    
                    
                    if ($value['ctd'] >= 0) {
                        $xml_AvaRest .= ' <AvailStatusMessage>
                        <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                        <RestrictionStatus Restriction="Departure" Status="' . ($value['ctd'] == 1 ? "Close" : "Open") . '" />
                        </AvailStatusMessage>';
                    }
                    
                    if ($value['stop_sell'] >= 0) {
                        $xml_AvaRest .= ' <AvailStatusMessage>
                        <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                        <RestrictionStatus Status="' . ($value['stop_sell'] == 1 ? "Close" : "Open") . '" />
                        </AvailStatusMessage>';
                    }
                    
                    
                } //
                
                
                $xml_Precio .= '</Rates>
                            </RatePlan>
                            </RatePlans>
                            </OTA_HotelRatePlanNotifRQ>';
                
                $xml_AvaRest .= '</AvailStatusMessages>
                            </OTA_HotelAvailNotifRQ>';
                
                $mail_Precio = '<strong> Request </strong> <br>';
                $mail_Precio .= $xml_Precio;
                $mail_Disponibilidad = '<strong> Request </strong> <br>';
                $mail_Disponibilidad .= $xml_AvaRest;
                //LLamada para Actuaizar Precios de la Sincronizacion
                
                $URL2 = 'https://channel.despegar.com/v1/hotels/rate-plans/update';
                
                $ch2 = curl_init($URL2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch2, CURLOPT_POSTFIELDS, "$xml_Precio");
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                $output2 = curl_exec($ch2);
                $mail_Precio .= '<strong> Response </strong> <br>';
                $mail_Precio .= $output2;
                 //mail($to, $subject, $message, $headers);

                        mail("xml@hoteratus.com", " Despegar.Com Request and Response Synced price Room Syncro", $mail_Precio, $headers);
              
                $data_api2 = simplexml_load_string($output2);
                $response2 = $data_api2->Errors->Error;
                curl_close($ch2);
                
                if ($response2 != '') {
                    $this->inventory_model->store_error($userid,$hotelid, 36, (string) $response2, 'Sincro Calender', date('m/d/Y h:i:s a', time()));
                    $DespegarErrors .= "***Despegar Error Update Price:" . $response2;
                } else {
                    $DespegarErrors .= "*Despegar Synced price";
                }
                
                //LLamada Para Sincronizar Calendario
                $URL = 'https://channel.despegar.com/v1/hotels/availability/update';
                
                $ch = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_AvaRest");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_Disponibilidad .= '<strong> Response </strong> <br>';
                $mail_Disponibilidad .= $output;
                mail("xml@hoteratus.com", " Despegar.Com Request and Response Room Syncro ", $mail_Disponibilidad, $headers);
                $data_api = simplexml_load_string($output);
                $response = $data_api->Errors->Error;
                curl_close($ch);
                
                
                if ($response) {
                    $this->inventory_model->store_error($userid, $hotelid, 36, (string) $response, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $DespegarErrors .= "*Error Sincro Availability and All Possible Restrictions Rooms" . $response;
                } else {
                    $DespegarErrors .= "*Despegar Synced Availability and All Possible Restrictions Rooms";
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
                
            }
            ///RAte type envios de estos
            else {
                $Data = $this->db->query("select *  from room_rate_types_base where owner_id =" . $userid . " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and rate_types_id = " . $Mappingvalue["rate_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                
                $mp_details     = get_data('import_mapping_DESPEGAR', array(
                    'user_id' => $userid,
                    'hotel_id' => $hotelid,
                    'channel_id' => "36",
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                $Rate = $this->db->query('select * from despegar_rate where  user_id ="' . $userid . '" AND hotelid="' . $hotelid . '" AND HotelCode="' . $ch_details->hotel_channel_id . '" and RatePlanCode ="' . $mp_details->RateTypeCode . '"')->row();
                
                $ChargeTypeCode = $Rate->ChargeTypeCode;
                $CurrencyCode   = $Rate->CurrencyCode;
                
                $xml_Precio = '<OTA_HotelRatePlanNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                        <RatePlans HotelCode="' . $ch_details->hotel_channel_id . '">
                        <RatePlan ChargeTypeCode="' . $ChargeTypeCode . '" CurrencyCode="' . $CurrencyCode . '" RatePlanCode="' . $mp_details->RateTypeCode . '">
                        <Rates>';
                
                $xml_AvaRest = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                <AvailStatusMessages HotelCode="' . $ch_details->hotel_channel_id . '">';
                
                foreach ($Data as $key => $value) {
                    
                    $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                    $start_date = $datevalue->format('Y-m-d');
                    $end_date   = date("Y-m-d", strtotime($start_date . "+ 1 days"));
                    
                    //Actualizar Precios                
                    
                    if ($value["price"] >= 0) {
                        $price = $value["price"] * $RateConvertion;
                        
                        
                        if ($ChargeTypeCode == 19) {
                            if ($mp_details->TaxPolicyType == "Exclusive") {
                                $xml_Precio .= '<Rate Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '"> <BaseByGuestAmts> <BaseByGuestAmt AmountBeforeTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" /> </BaseByGuestAmts>
                                    </Rate>';
                            } else {
                                
                                $xml_Precio .= '<Rate Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '"> <BaseByGuestAmts> <BaseByGuestAmt AmountAfterTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" /> </BaseByGuestAmts>
                                </Rate>';
                            }
                        }
                    }
                    
                    
                    
                    //Restrinciones y Disponibilidad
                    if ($value["availability"] >= 0) {
                        $xml_AvaRest .= '<AvailStatusMessage BookingLimit="' . $value['availability'] . '" BookingLimitMessageType="SetLimit">
                            <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '" />
                            </AvailStatusMessage>';
                    }
                    
                    $xml_AvaRest .= '<AvailStatusMessage>
                          <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />';
                    
                    if ($value['minimum_stay'] >= 0) {
                        $xml_AvaRest .= '<LengthsOfStay> <LengthOfStay Time="' . $value['minimum_stay'] . '" TimeUnit="Day" MinMaxMessageType="MinLOS" /> </LengthsOfStay>';
                    }
                    
                    if ($value['cta'] >= 0) {
                        $xml_AvaRest .= '<RestrictionStatus Restriction="Arrival" Status="' . ($value['cta'] == 1 ? "Close" : "Open") . '" /> ';
                    }
                    
                    $xml_AvaRest .= ' </AvailStatusMessage>';
                    
                    
                    if ($value['ctd'] >= 0) {
                        $xml_AvaRest .= ' <AvailStatusMessage>
                        <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                        <RestrictionStatus Restriction="Departure" Status="' . ($value['ctd'] == 1 ? "Close" : "Open") . '" />
                        </AvailStatusMessage>';
                    }
                    
                    if ($value['stop_sell'] >= 0) {
                        $xml_AvaRest .= ' <AvailStatusMessage>
                        <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                        <RestrictionStatus Status="' . ($value['stop_sell'] == 1 ? "Close" : "Open") . '" />
                        </AvailStatusMessage>';
                    }
                    
                    
                } //
                
                
                $xml_Precio .= '</Rates>
                            </RatePlan>
                            </RatePlans>
                            </OTA_HotelRatePlanNotifRQ>';
                
                $xml_AvaRest .= '</AvailStatusMessages>
                            </OTA_HotelAvailNotifRQ>';
                
                $mail_Precio = '<strong> Request </strong> <br>';
                $mail_Precio .= $xml_Precio;
                $mail_Disponibilidad = '<strong> Request </strong> <br>';
                $mail_Disponibilidad .= $xml_AvaRest;
                //LLamada para Actuaizar Precios de la Sincronizacion
                
                $URL2 = 'https://channel.despegar.com/v1/hotels/rate-plans/update';
                
                $ch2 = curl_init($URL2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch2, CURLOPT_POSTFIELDS, "$xml_Precio");
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                $output2 = curl_exec($ch2);
                $mail_Precio .= '<strong> Response </strong> <br>';
                $mail_Precio .= $output2;
                mail("xml@hoteratus.com", " Despegar.Com Request and Response Synced price RATETYPE Syncro", $mail_Precio, $headers);
                $data_api2 = simplexml_load_string($output2);
                $response2 = $data_api2->Errors->Error;
                curl_close($ch2);
                
                if ($response2 != '') {
                    $this->inventory_model->store_error($userid, $hotelid, 36, (string) $response2, 'Sincro Calender', date('m/d/Y h:i:s a', time()));
                    $DespegarErrors .= "***Despegar Error Update Price:" . $response2;
                } else {
                    $DespegarErrors .= "*Despegar Synced price";
                }
                
                //LLamada Para Sincronizar Calendario
                $URL = 'https://channel.despegar.com/v1/hotels/availability/update';
                
                $ch = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_AvaRest");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_Disponibilidad .= '<strong> Response </strong> <br>';
                $mail_Disponibilidad .= $output;
                mail("xml@hoteratus.com", " Despegar.Com Request and Response RATETYPE Syncro ", $mail_Disponibilidad, $headers);
                $data_api = simplexml_load_string($output);
                $response = $data_api->Errors->Error;
                curl_close($ch);
                
                
                if ($response) {
                    $this->inventory_model->store_error($userid, $hotelid, 36, (string) $response, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $DespegarErrors .= "*Error Sincro Availability and All Possible Restrictions Rooms" . $response;
                } else {
                    $DespegarErrors .= "*Despegar Synced Availability and All Possible Restrictions Rooms";
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
                
            }
            
        }
        
        
        
        return $DespegarErrors;
    }
    
    function bulk_update($product, $import_mapping_id, $maping_id, $price)
    {
        

     
        $headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
                    $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
                    $headers .= "CC: support <felix@hoteratus.com>\r\n";
                    $headers .= "BCC: datahernandez@gmail.com\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        
        
        
        
        $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $product['start_date'])));
        $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $product['end_date'])));
        $end_date   = date("Y-m-d", strtotime($end_date . "+ 1 days"));
        
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "36"
        ))->row();
        $channel_id = 36;
        
        $mp_details = get_data('import_mapping_DESPEGAR', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "36",
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
            }
        }
        
        //$closed = 0;
        
        $errorFlag = 0;
        if ($mp_details->RateTypeCode != 0) {
            
            
            if (@$product['price'] != "") {
                
                
                $Rate = $this->db->query('select * from despegar_rate where  user_id ="' . current_user_type() . '" AND hotelid="' . hotel_id() . '" AND HotelCode="' . $ch_details->hotel_channel_id . '" and RatePlanCode ="' . $mp_details->RateTypeCode . '"')->result();
                
                $ChargeTypeCode = "";
                $CurrencyCode   = "";
                foreach ($Rate as $key) {
                    $ChargeTypeCode = $key->ChargeTypeCode;
                    $CurrencyCode   = $key->CurrencyCode;
                    
                }
                
                $xml_data2 = '<OTA_HotelRatePlanNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                  <RatePlans HotelCode="' . $ch_details->hotel_channel_id . '">
                    <RatePlan ChargeTypeCode="' . $ChargeTypeCode . '" CurrencyCode="' . $CurrencyCode . '" RatePlanCode="' . $mp_details->RateTypeCode . '">
                      <Rates>
                        <Rate Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '">
                          <BaseByGuestAmts>';
                
                if ($ChargeTypeCode == 19) {
                    if ($mp_details->TaxPolicyType == "Exclusive") {
                        $xml_data2 .= '<BaseByGuestAmt AmountBeforeTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" />';
                    } else {
                        $xml_data2 .= '<BaseByGuestAmt AmountAfterTax="' . $price . '" NumberOfGuests="' . $mp_details->maximumPaxes . '" AgeQualifyingCode="10" />';
                    }
                    
                    $xml_data2 .= '</BaseByGuestAmts>
                                    
                                    </Rate>
                                  </Rates>
                                </RatePlan>
                              </RatePlans>
                            </OTA_HotelRatePlanNotifRQ>';
                }
                
                
                
                
                $mail_data2 = '<strong> Request </strong> <br>';
                $mail_data2 .= $xml_data2;
                $URL2 = 'https://channel.despegar.com/v1/hotels/rate-plans/update';
                
                $ch2 = curl_init($URL2);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch2, CURLOPT_POSTFIELDS, "$xml_data2");
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                $output2 = curl_exec($ch2);
                $mail_data2 .= '<strong> Response </strong> <br>';
                $mail_data2 .= $output2;
                mail("xml@hoteratus.com", " Despegar.Com Request and Response ", $mail_data2, $headers);
                $data_api2 = simplexml_load_string($output2);
                $response2 = $data_api2->Errors->Error;
                curl_close($ch2);
                
                
                if ($response2 != '') {
                    $errorFlag = 1;
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $response2, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', (string) $response2);
                }
                
                
            }
            
            
            
            
            $xml_data = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                  <AvailStatusMessages HotelCode="' . $ch_details->hotel_channel_id . '">';
            
            if (@$product['availability'] != "") {
                if ($product['availability'] == "0") {
                    $product['stop_sell'] = "1";
                }
                
                $xml_data .= '<AvailStatusMessage BookingLimit="' . $product['availability'] . '" BookingLimitMessageType="SetLimit">
                                <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" InvCode="' . $mp_details->codeRoomType . '" />
                                </AvailStatusMessage>';
            }
            
            
            if (is_numeric(@$product['cta']) || @$product['minimum_stay'] != "" || $maximum_stay > 1) {
                $xml_data .= '<AvailStatusMessage>
                                <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />';
                
                
                
                if (@$product['minimum_stay'] != "" || $maximum_stay > 1) {
                    $xml_data .= '<LengthsOfStay>';
                    if (@$product['minimum_stay'] != "") {
                        $xml_data .= '<LengthOfStay Time="' . $product['minimum_stay'] . '" TimeUnit="Day" MinMaxMessageType="MinLOS" />';
                    }
                    
                    if (isset($maximum_stay)) {
                        if ($maximum_stay > 1) {
                            $xml_data .= '<LengthOfStay Time="' . $maximum_stay . '" TimeUnit="Day" MinMaxMessageType="MaxLOS" />';
                        }
                        
                    }
                    
                    $xml_data .= '</LengthsOfStay>';
                }
                
                
                if (is_numeric(@$product['cta'])) {
                    $xml_data .= '<RestrictionStatus Restriction="Arrival" Status="' . ($product['cta'] == 1 ? "Close" : "Open") . '" /> ';
                }
                
                $xml_data .= ' </AvailStatusMessage>';
            }
            
            
            if (is_numeric(@$product['ctd'])) {
                $xml_data .= ' <AvailStatusMessage>
                                <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                                <RestrictionStatus Restriction="Departure" Status="' . ($product['ctd'] == 1 ? "Close" : "Open") . '" />
                            </AvailStatusMessage>';
            }
            
            if (@$product['stop_sell'] == "1") {
                $xml_data .= ' <AvailStatusMessage>
                                <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                                <RestrictionStatus Status="Close" />
                            </AvailStatusMessage>';
            } else if (@$product['open_room'] == "1") {
                $xml_data .= '<AvailStatusMessage>
                                <StatusApplicationControl Start="' . $start_date . '" End="' . $end_date . '" RatePlanCode="' . $mp_details->RateTypeCode . '" InvCode="' . $mp_details->codeRoomType . '" />
                                <RestrictionStatus Status="Open" />
                            </AvailStatusMessage>';
            }
            
            
            $xml_data .= '</AvailStatusMessages>
                            </OTA_HotelAvailNotifRQ>';
            
            $mail_data = '<strong> Request </strong> <br>';
            $mail_data .= $xml_data;
            
            if (@$product['availability'] != "" || is_numeric(@$product['cta']) || is_numeric(@$product['ctd']) || @$product['stop_sell'] == "1" || @$product['open_room'] == "1" || @$product['minimum_stay']) {
                
                
                $URL = 'https://channel.despegar.com/v1/hotels/availability/update';
                
                $ch = curl_init($URL);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="' . $ch_details->user_name . '", password="' . $ch_details->user_password . '", hotel="' . $ch_details->hotel_channel_id . '"',
                    'Content-Type: application/xml'
                ));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $mail_data .= '<strong> Response </strong> <br>';
                $mail_data .= $output;
                 mail("xml@hoteratus.com", " Despegar.Com Request and Response ", $mail_data, $headers);
                $data_api = simplexml_load_string($output);
                $response = $data_api->Errors->Error;
                curl_close($ch);
                
                
                if ($response) {
                    $errorFlag = 1;
                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $response, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('bulk_error', (string) $response);
                }
                
                
            }
            
            if ($errorFlag == 0) {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
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
    
    function getReservationLists($source)
    {
        $this->db->select('Import_reservation_ID, ResID_Value,ResStatus, givenName, surname    ,RoomTypeCode,RatePlanCode,channel_id,arrival,departure,LastModifyDateTime,Currency,AmountAfterTax,ImportDate,CreateDateTime');
        $this->db->order_by('Import_reservation_ID', 'desc');
        $this->db->where('user_id', current_user_type());
        $this->db->where('hotel_id', hotel_id());
        $data = $this->db->get('import_reservation_DESPEGAR')->result();
        if ($data) {
            $bnow = array();
            foreach ($data as $val) {
                
                $status     = $val->ResStatus;
                $PersonName = $val->givenName . ' ' . $val->surname;
                
                $room_id = @get_data(MAP, array(
                    'channel_id' => $val->channel_id,
                    'import_mapping_id' => get_data('import_mapping_DESPEGAR', array(
                        'codeRoomType' => $val->RoomTypeCode,
                        'RateTypeCode' => $val->RatePlanCode,
                        'user_id' => current_user_type(),
                        'hotel_id' => hotel_id()
                    ))->row()->import_mapping_id
                ))->row()->property_id;
                
                $checkin  = date('Y/m/d', strtotime($val->arrival));
                $checkout = date('Y/m/d', strtotime($val->departure));
                $nig      = _datebetween($checkin, $checkout);
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
                        'booking_date' => $val->CreateDateTime,
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
                        'FIRSTNAME' => $val->PersonName,
                        'ROOMCODE' => $room_id,
                        'channel_id' => $val->channel_id,
                        'CHECKIN' => $val->arrival,
                        'CHECKOUT' => $val->departure,
                        'RSVCREATE' => $val->CreateDateTime,
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
    
    function getReservationDetails($source, $id)
    {
        $despegarm = get_data('import_reservation_DESPEGAR', array(
            'import_reservation_id ' => ($id)
        ))->row_array();
        
        $room_id = @get_data(MAP, array(
            'channel_id' => $despegarm['channel_id'],
            'import_mapping_id' => get_data('import_mapping_DESPEGAR', array(
                'codeRoomType' => $despegarm['RoomTypeCode'],
                'RateTypeCode' => $despegarm['RatePlanCode'],
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id()
            ))->row()->import_mapping_id
        ))->row()->property_id;
        if ($source == 'list') {
            $data['curr_cha_id'] = secure('36');
        }
        $data['CC_NAME']   = $despegarm['name'];
        $data['CC_NUMBER'] = ($despegarm['cardNumber']);
        $data['CC_DATE']   = substr(($despegarm['expireDate']), 0, 2);
        $data['CC_YEAR']   = substr($despegarm['expireDate'], 2);
        $data['CC_CVC']    = ($despegarm['cardCode']);
        $data['CC_TYPE']   = ($despegarm['CardType']);
        
        $data['RESER_NUMBER'] = $despegarm['ResID_Value'];
        $data['RESER_DATE']   = date('M d,Y', strtotime($despegarm['LastModifyDateTime']));
        $data['RESER_ID']     = $despegarm['Import_reservation_ID'];
        
        $data['curr_cha_currency'] = $despegarm['Currency'];
        $data['guest_name']        = $despegarm['givenName'] . ' ' . $despegarm['surname'];
        $data['start_date']        = date('Y/m/d', strtotime($despegarm['arrival']));
        $data['end_date']          = date('Y/m/d', strtotime($despegarm['departure']));
        $data['reservation_code']  = $despegarm['ResID_Value'];
        $data['ROOMCODE']          = $room_id;
        
        $data['status'] = $despegarm['ResStatus'];
        
        $data['start_date'] = $despegarm['arrival'];
        $data['end_date']   = $despegarm['departure'];
        
        $data['CHECKIN']  = date('Y/m/d', strtotime($despegarm['arrival']));
        $data['CHECKOUT'] = date('Y/m/d', strtotime($despegarm['departure']));
        
        $data['nig'] = _datebetween($data['CHECKIN'], $data['CHECKOUT']);
        //$Guest                        =    explode('##',0);
        
        $data['ADULTS']     = $despegarm['Adult'];
        $data['CHILDREN']   = $despegarm['Child'];
        $data['RoomNumber'] = $despegarm['RoomNumber'];
        
        
        $data['description']    = ''; //$despegarm['Text'];
        $data['policy_checin']  = ''; //$despegarm['Start'];
        $data['policy_checout'] = ''; //$despegarm['End'];
        $data['CRIB']           = ''; //$despegarm['CRIB'];
        $data['subtotal']       = $despegarm['AmountAfterTax'];
        $data['CURRENCY']       = $despegarm['Currency'];
        
        $data['guest_name']     = $despegarm['givenName'] . ' ' . $despegarm['surname'];
        $data['email']          = ''; //$despegarm['Email'];
        $data['street_name']    = ''; //$despegarm['AddressLine'];
        $data['city_name']      = ''; //$despegarm['CityName'];
        $data['country']        = ''; //$despegarm['CountryName'];
        $data['phone']          = ''; //$despegarm['PhoneNumber'];
        $data['commission']     = ''; //$despegarm['COMMISSION'];
        $data['mealsinc']       = ''; //$despegarm['MEALSINC'];
        $data['price']          = $despegarm['AmountAfterTax'];
        $data['reservation_id'] = $despegarm['Import_reservation_ID'];
        $data['members_count']  = $despegarm['Adult'];
        $data['children']       = $despegarm['Child'];
        
        $inbwdays = explode(',', $despegarm['stayDate']);
        $baseRate = explode(',', $despegarm['baseRate']);
        
        for ($i = 0; $i < count($inbwdays); $i++) {
            if ($inbwdays[$i] != "") {
                $data['perdayprice'][] = array(
                    $inbwdays[$i] => $baseRate[$i]
                );
            }
        }
        /*    if($price)
        {
        foreach($price as $price_val)
        {
        $date        =    explode('~',$price_val);
        $price_day    =    explode('/',$date[1]);    
        $data['perdayprice'][] = array($date[0] => $price_day[1]);
        }
        }
        */
        
        if ($data['CC_NUMBER'] == '') {
            $data['payment_method'] = 'Cash';
        } else {
            $data['payment_method'] = 'Credit Card';
        }
        
        $room = get_data('import_mapping_DESPEGAR', array(
            'codeRoomType' => $despegarm['RoomTypeCode'],
            'RateTypeCode' => $despegarm['RatePlanCode']
        ), 'nameRoomType , rate_name')->row_array();
        if ($room) {
            $data['channel_room_name'] = $room['nameRoomType'] . ' ' . $room['rate_name'];
        }
        
        if ($source == 'print') {
            $data['room_id']            = $room_id;
            $data['num_nights']         = '1';
            $data['price']              = $despegarm['AmountAfterTax'];
            $data['booking_date']       = $data['RESER_DATE'];
            $data['mobile']             = ''; //$despegarm['PhoneNumber'];
            $data['curr_cha_id']        = '36';
            $data['currency']           = $despegarm['Currency'];
            $data['meal_name']          = '---';
            $data['cancel_description'] = '---';
        }
        return $data;
    }
    
    function get_mapping_rooms($channel_id, $type = '')
    {
        if (user_type() == '1' || admin_id() != '' && admin_type() == '1') {
            $owner_id = current_user_type();
        } elseif (user_type() == '2') {
            $owner_id = current_user_type();
        }
        if ($channel_id == '36') {
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
            $result = $this->db->get('import_mapping_DESPEGAR' . ' as B');
            if ($result != '') {
                return $result->result();
            } else {
                return false;
            }
        }
    }
    
    
} //Final 