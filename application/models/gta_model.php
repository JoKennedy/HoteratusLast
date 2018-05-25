<?php
ini_set('memory_limit', '-1');
ini_set('display_erros', '1');
class gta_model extends CI_Model
{
    private $DemoURL;
    private $LiveURL;


     public function __construct()
    {
        
        parent::__construct();

            $this->DemoURL ='https://hotels.demo.gta-travel.com';
            $this->LiveURL='https://hotels.gta-travel.com';
        
    }
    
    function mailsettings()
    {
        $this->load->library('email');
        $config['wrapchars'] = 76; // Character count to wrap at.
        $config['priority']  = 1; // Character count to wrap at.
        $config['mailtype']  = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
        $config['charset']   = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
        $this->email->initialize($config);
    }
    
    function bulk_update($product, $import_mapping_id, $maping_id, $price)
    {
        $days_array = explode(',', @$product['days']);
        $dayval     = "";
        if (in_array("1", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("2", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("3", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("4", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("5", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("6", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        if (in_array("7", $days_array)) {
            $dayval .= 1;
        } else {
            $dayval .= 0;
        }
        $start      = date('Y-m-d', strtotime(str_replace('/', '-', @$product['start_date'])));
        $end        = date('Y-m-d', strtotime(str_replace('/', '-', @$product['end_date'])));
        $period     = $this->inventory_model->getDateForSpecificDayBetweenDates($start, $end, @$product['days']);
        $channel_id = 8;
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => "$channel_id"
        ))->row();
        
        if ($ch_details->mode == 0) {
            $urls = explode(',', $ch_details->test_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $gta[$path[0]] = $path[1];
            }
        } else if ($ch_details->mode == 1) {
            $urls = explode(',', $ch_details->live_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $gta[$path[0]] = $path[1];
            }
        }
        $mp_details = get_data(IM_GTA, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => $channel_id,
            'GTA_id' => "$import_mapping_id"
        ))->row();
        
        //$closed = 0;
        if (@$product['stop_sell'] == "1") {
            $closed = 1;
        } elseif (@$product['open_room'] == "1") {
            $closed = 0;
        }
        $gt_room_id    = $mp_details->Id;
        $rateplanid    = $mp_details->rateplan_id;
        $MinPax        = $mp_details->MinPax;
        $peakrate      = $mp_details->peakrate;
        $MaxOccupancy  = $mp_details->MaxOccupancy;
        $contract_id   = $mp_details->contract_id;
        $minnights     = $mp_details->minnights;
        $payfullperiod = $mp_details->payfullperiod;
        if ($gt_room_id != 0) {
			$errorFlag = 0;
            foreach ($period as $date) {
                $date     = date('Y-m-d', strtotime($date));
                $formData = array();
                $updprice = '';
                if ($price != '') {
                    if ($price != '0.00' || $price != '0') {
                        $updprice = $price;
                    }
                } elseif (@$product['price'] != '') {
                    if ($product['price'] != '0.00' || $product['price'] != '0') {
                        $updprice = $price;
                    }
                }
                $minNights = '';
                if (@$product['minimum_stay'] != '') {
                    $minNights = ' MinNights="' . $product['minimum_stay'] . '" ';
                } else if ($minnights != '') {
                    $minNights = ' MinNights="' . $minnights . '" ';
                }
                if ($payfullperiod == '') {
                    $payfullperiod = 'false';
                }
                if ($updprice != '') {
                    $soapUrl         = trim($gta['urate_m']);
                    $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="' . $ch_details->web_id . '" UserName="' . $ch_details->user_name . '" Password="' . $ch_details->user_password . '" /><RatePlan Id="' . $rateplanid . '"><MarginRates DaysOfWeek="' . $dayval . '"' . $minNights . 'FullPeriod="' . $payfullperiod . '"><RoomRate Start="' . $date . '" End="' . $date . '" RoomId="' . $gt_room_id . '" Occupancy="' . $MaxOccupancy . '" Gross="' . $updprice . '"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';
                    $response        = $this->put($soapUrl, $xml_post_string);
                    makeLog('gtaUpdateLog.txt', "BULK_UPDATE Price URL=>$soapUrl\nContent=>$xml_post_string\nResponse=>$response\n");
                    $data        = simplexml_load_string($response);
                    $Error_Array = @$data->Errors->Error;
                    if ($Error_Array != '') {
						$errorFlag = 1;
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $Error_Array, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', (string) $Error_Array);
                    }
                }
                
                //For Alot Update
                $alot = '';
                if (@$product['availability'] != '') {
                    $alot = $product['availability'];
                }
                if (@$product['stop_sell'] != '') {
                    if ($product['stop_sell'] == '1') {
                        $alot = '0';
                    }
                }
                if (@$product['open_room'] != '') {
                    if ($product['open_room'] == '1') {
                        $alot = '1';
                    }
                }
                
                if ($alot != '') {
                    $alotsoapUrl     = trim($gta['uavail']);
                    $xml_post_string = '<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd"><User Qualifier="' . $ch_details->web_id . '" UserName="' . $ch_details->user_name . '" Password="' . $ch_details->user_password . '"/><InventoryBlock ContractId="' . $contract_id . '" PropertyId="' . $ch_details->hotel_channel_id . '" ><RoomStyle>
                    <StayDate Date = "' . $date . '">
                    <Inventory RoomId="' . $gt_room_id . '" >
                    <Detail FreeSale="false" InventoryType="Flexible"
                    Quantity="' . $alot . '" ReleaseDays="0"/>
                    </Inventory>
                    </StayDate></RoomStyle>
                    </InventoryBlock>
                    </GTA_InventoryUpdateRQ>';
                    $response        = $this->put($alotsoapUrl, $xml_post_string);
                    makeLog('gtaUpdateLog.txt', "BULK_UPDATE Alot URL=>$alotsoapUrl\nContent=>$xml_post_string\nResponse=>$response\n");
                    $data        = simplexml_load_string($response);
                    $Error_Array = @$data->Errors->Error;
                    if ($Error_Array != '') {
						$errorFlag = 1;
                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, (string) $Error_Array, 'Bulk Update', date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', (string) $Error_Array);
                    }
                }
            }

			if ($errorFlag == 0) {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
			}
        }
        
    }
    
    function put($soapUrl, $xml_post_string)
    {
        $ch = curl_init($soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
        return $response = curl_exec($ch);
    }

    function post($soapUrl, $xml_post_string)
    {
        $ch = curl_init($soapUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return $response = curl_exec($ch);
    }

    function propertyRead()
    {



            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/property/search';
            $Qualifier='CPTHOLB';
            $UserName='XMLUSER';
            $Password='HOTERATUS';

/*
            echo 'PropertyRead';
            $xml_post_string = '<GTA_PropertyReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" > <User Qualifier="'. $Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'" /> </GTA_PropertyReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
          
            $data        = simplexml_load_string($response);

            print_r($response );



            echo 'PropertyReadDetails <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/propertyDetails/search';

            $xml_post_string = '<GTA_PropertyDetailsReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" > <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'" /> <Property Id="10493"/> </GTA_PropertyDetailsReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response );



            echo 'GTA_StaticDataReadRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/staticData/search';

            $xml_post_string = '<GTA_StaticDataReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05" > <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'"/> <Item Category="CHANNEL-TYPE"/> </GTA_StaticDataReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );

             echo 'GTA_ContractReadRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/contracts/search';

            $xml_post_string = '<GTA_ContractReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'" /> <Property Id="10493" /> </GTA_ContractReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );



             echo 'GTA_RoomsReadRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/rooms/search';

            $xml_post_string = '<GTA_RoomsReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'" /> <Property Id="10493" /> </GTA_RoomsReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );


     echo 'GTA_StaticRatesReadRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/staticRates/search';

            $xml_post_string = '<GTA_StaticRatesReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 GTA_StaticRatesReadRQ.xsd"> <User Qualifier = "'.$Qualifier.'" UserName = "'.$UserName.'" Password = "'.$Password.'"/> <RatePlan Id = "11838" Start = "2017-11-29" /> </GTA_StaticRatesReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );



            echo 'GTA_StaticRatesCreateRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/staticRates';

            $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_RateCreateRQ.xsd"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'"/> <RatePlan Id="11838"> <StaticRate Start="2017-11-29" End="2018-03-31" DaysOfWeek="1111111" MinNights="1" MinPax="1" FullPeriod="false" PeakRate="false"> <StaticRoomRate RoomId="119406" Occupancy="4" Nett="300.99" /></StaticRate> </RatePlan> </GTA_StaticRatesCreateRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );



             echo 'GTA_StaticRatesUpdateRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/staticRates';

            $xml_post_string = '<GTA_StaticRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_StaticRatesUpdateRQ.xsd"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'"/> <RatePlan Id="11838"> <StaticRate Start="2017-11-29" End="2017-11-30" DaysOfWeek="1111111" MinNights="1" MinPax="1" FullPeriod="false" PeakRate="false"> <StaticRoomRate RoomId="119406" Occupancy="4" Nett="500.99"/> </StaticRate> </RatePlan> </GTA_StaticRatesUpdateRQ>';

            $response        = $this->put($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );


       echo 'GTA_StaticPeakRatesDeleteRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/staticPeakRates/delete';

            $xml_post_string = '<GTA_StaticPeakRatesDeleteRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_StaticRatesDeleteRQ.xsd"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'"/> <RatePlan Id="11838"> <StaticPeakRate Start="2017-11-29" End="2017-11-30" MinNights="1" MinPax="1" FullPeriod="false"/> </RatePlan> </GTA_StaticPeakRatesDeleteRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );

        


       echo 'GTA_MarginRatesReadRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/marginRates/search';

            $xml_post_string = '<GTA_MarginRatesReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"> <User Qualifier = "'.$Qualifier.'" UserName = "'.$UserName.'" Password = "'.$Password.'"/> <RatePlan Id = "11838" Start = "2017-11-29" End = "2017-11-30"/> </GTA_MarginRatesReadRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );
   */ 
     echo 'GTA_MarginRatesCreateRQ <br> <br> <br>';


            $alotsoapUrl  = $this->DemoURL.'/supplierapi/rest/marginRates';

            $xml_post_string = '<GTA_MarginRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <User Qualifier="'.$Qualifier.'" UserName="'.$UserName.'" Password="'.$Password.'"/> <RatePlan Id="11838"> <MarginRates DaysOfWeek="1111111" MinNights="1" MaxNights = "5" FullPeriod="false" Start="2017-12-01" End="2018-01-31"> <RoomRate RoomId="49764" Occupancy="4" Margin="35" Gross="199"/> </MarginRates> </RatePlan> </GTA_MarginRatesCreateRQ>';

            $response        = $this->post($alotsoapUrl, $xml_post_string);
            $data        = simplexml_load_string($response);

            print_r($response  );



    }


    
}