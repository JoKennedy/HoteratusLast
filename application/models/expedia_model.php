 <?php
ini_set('memory_limit', '-1');
ini_set('display_erros', '1');
class expedia_model extends CI_Model
{
    
    private $currency_code;
    
    public function __construct()
    {
        
        parent::__construct();
        
        if (current_user_type()) {
            $hotel_detail = get_data(HOTEL, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id()
            ))->row()->currency;
            
            if ($hotel_detail != 0) {
                $this->currency_code = get_data(TBL_CUR, array(
                    'currency_id' => $hotel_detail
                ))->row()->currency_code;
                
            }

        }
        
    }
    
    function bulk_update($product, $import_mapping_id, $mapping_id, $price)
    {
        
        
        $up_days = explode(',', $product['days']);
        
        if (in_array('1', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $monday_cta = '0';
            } else {
                $monday_cta = '1';
            }
            $exp_sun = 'true';
        } else {
            $monday_cta = '1';
            $exp_sun    = 'false';
        }
        if (in_array('2', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $tuesday_cta = '0';
            } else {
                $tuesday_cta = '1';
            }
            $exp_mon = 'true';
        } else {
            $tuesday_cta = '1';
            $exp_mon     = 'false';
        }
        if (in_array('3', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $wednesday_cta = '0';
            } else {
                $wednesday_cta = '1';
            }
            $exp_tue = 'true';
        } else {
            $wednesday_cta = '1';
            $exp_tue       = 'false';
        }
        if (in_array('4', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $thursday_cta = '0';
            } else {
                $thursday_cta = '1';
                
            }
            $exp_wed = 'true';
        } else {
            $thursday_cta = '1';
            $exp_wed      = 'false';
        }
        if (in_array('5', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $friday_cta = '0';
            } else {
                $friday_cta = '1';
            }
            $exp_thur = 'true';
        } else {
            $friday_cta = '1';
            $exp_thur   = 'false';
        }
        if (in_array('6', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $saturday_cta = '0';
            } else {
                $saturday_cta = '1';
            }
            $exp_fri = 'true';
        } else {
            $saturday_cta = '1';
            $exp_fri      = 'false';
        }
        if (in_array('7', $up_days)) {
            if (isset($product['cta']) == '' || $product['cta'] == '0' || $product['cta'] == '2') {
                $sunday_cta = '0';
            } else {
                $sunday_cta = '1';
                
            }
            $exp_sat = 'true';
        } else {
            $sunday_cta = '1';
            $exp_sat    = 'false';
        }
        



        if (@$product['cta'] == "" ) {
            $exp_cta = "";
        } else if( $product['cta'] == "0" || $product['cta'] == '2')
        {
            $exp_cta = "false";
        }
        else
        {
             $exp_cta = "true";
        }

        if (@$product['ctd'] == "" ) {
            $exp_ctd = "";
            
        } else if ($product['ctd'] == "0" || $product['ctd'] == '2'){
            $exp_ctd = "false";
        }
        else
        {
            $exp_ctd = "true";
        }
        
        
        $re_sart_date = date('Y-m-d', strtotime(str_replace('/', '-', $product['start_date'])));
        $re_end_date  = date('Y-m-d', strtotime(str_replace('/', '-', $product['end_date'])));
        
        $ch_details = get_data(CONNECT, array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel_id' => 1
        ))->row();
        
        if ($ch_details->mode == 0) {
            $urls = explode(',', $ch_details->test_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $exp[$path[0]] = $path[1];
            }
        } else if ($ch_details->mode == 1) {
            $urls = explode(',', $ch_details->live_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $exp[$path[0]] = $path[1];
            }
        }
        
        $mp_details = get_data('import_mapping', array(
            'user_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'channel' => 1,
            'map_id' => $import_mapping_id
        ))->row();
        
        $rt_details = get_data('import_mapping_expedia_ratelimit', array(
            'user_id' => current_user_type(),
            'hotel_channel_id' => $mp_details->hotel_channel_id,
            'hotel_id' => hotel_id(),
            'channel' => 1,
            'rateType' => 'SellRate'
        ))->row();
        $oa_details = get_data('import_mapping_expedia_occupancy', array(
            'user_id' => current_user_type(),
            'hotel_channel_id' => $mp_details->hotel_channel_id,
            'hotel_id' => hotel_id(),
            'channel' => 1
        ))->row();
        $room_value = get_data('roommapping', array(
            'import_mapping_id' => $import_mapping_id
        ))->row();
        
        
        
        if (@$product['minimum_stay'] == '') {
            
            $minlos = @$product['minimum_stay'];
        }
        $maxLos         = $mp_details->maxLos;
        $mapping_values = get_data('mapping_values', array(
            'mapping_id' => $mapping_id
        ))->row_array();
        
        if ($mapping_values) {
            if ($mapping_values['label'] == "MaxStay" && $mapping_values['value'] <= $maxLos) {
                if (@$product['minimum_stay'] < $mapping_values['value']) {
                    $maxLos = $mapping_values['value'];
                }
            }
        }
        //echo $minlos;
        //echo $stop_sale;
        if ($room_value->explevel == "rate") {
            
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                    <Authentication username="' . $ch_details->user_name . '" password="' . $ch_details->user_password . '"/>
                                                    <Hotel id="' . $mp_details->hotel_channel_id . '"/>
                                                    <AvailRateUpdate>
                                                    <DateRange from="' . $re_sart_date . '" to="' . $re_end_date . '" sun="' . $exp_sun . '" mon="' . $exp_mon . '" tue="' . $exp_tue . '" wed="' . $exp_wed . '" thu="' . $exp_thur . '" fri="' . $exp_fri . '" sat="' . $exp_sat . '"/>';
            $xml .= '<RoomType id="' . $mp_details->roomtype_id . '">';
            
            if (@$product['availability'] != '' && $room_value->update_availability == '1') {
                $xml .= '<Inventory totalInventoryAvailable="' . $product['availability'] . '"/>';
            }
            if ($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked") {
                $plan_id = $mp_details->rateplan_id;
            } else {
                $plan_id = $mp_details->rate_type_id;
            }
            //echo $plan_id;
            if (@$product['stop_sell'] == 1) {
                $closed = "true";
                $xml .= '<RatePlan id="' . $plan_id . '" closed = "true">';
            } elseif (@$product['open_room'] == 1) {
                $xml .= '<RatePlan id="' . $plan_id . '" closed = "false">';
            } else {
                $xml .= '<RatePlan id="' . $plan_id . '">';
            }
            //@$product['price']!='' && @$product['price'] >= (string)$rt_details->minAmount && @$product['price'] <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
            if (@$product['price'] != '' && $room_value->update_rate == '1') {
                //echo @$product['price'];
                if ($mp_details->pricingModel == "PerDayPricingByLengthOfStay") {
                    
                    
                    for ($i = $minlos; $i <= $maxLos; $i++) {
                        $xml .= '<Rate lengthOfStay="' . $i . '" currency="' . $this->currency_code . '">
                                                            <PerDay rate="' . $price . '"/>
                                                            </Rate>';
                    }
                    //$xml .= '<Restrictions closedToArrival="'.$exp_cta.'" closedToDeparture="'.$exp_ctd.'"/>';
                } elseif ($mp_details->pricingModel == 'PerDayPricing') {
                    
                    $xml .= '<Rate currency="' . $this->currency_code . '"> <PerDay rate="' . $price . '"/>
                                                        </Rate> ';
                } elseif ($mp_details->pricingModel == 'OccupancyBasedPricing') {
                    $xml .= '<Rate currency="' . $this->currency_code . '"> <PerOccupancy rate="' . $price . '" occupancy = "2"/></Rate> ';
                    $xml .= '<Rate currency="' . $this->currency_code . '"> <PerOccupancy rate="' . $price . '" occupancy = "1"/></Rate> ';
                }
                
            } else {
                if (@$product['price'] != '' && $room_value->update_rate == '1') {
                    
                    if (@$product['price'] <= (string) $rt_details->minAmount || @$product['price'] >= (string) $rt_details->maxAmount) {
                        $price_error = "Price must be between " . $rt_details->minAmount . " and " . $rt_details->maxAmount;
                    }
                }
            }
            
            
            if ($exp_ctd != "" || $exp_cta != "" || @$product['minimum_stay'] != "") {
                

                $xml .= '<Restrictions';
                $xml .= ($exp_ctd != ""?' closedToDeparture="' . $exp_ctd . '"':'');
                $xml .= ($exp_cta != "" ?' closedToArrival="' . $exp_cta . '"':'');
                
                if (@$product['minimum_stay'] != "") {
                    $xml .= ' minLOS="' . @$product['minimum_stay'] . '" maxLOS="' . $maxLos . '"';
                }
                $xml .= ' />';
            }
            $xml .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
        } else {
            
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                    <Authentication username="' . $ch_details->user_name . '" password="' . $ch_details->user_password . '"/>
                                                    <Hotel id="' . $mp_details->hotel_channel_id . '"/>
                                                    

                                                    <AvailRateUpdate>
                                                    <DateRange from="' . $re_sart_date . '" to="' . $re_end_date . '" sun="' . $exp_sun . '" mon="' . $exp_mon . '" tue="' . $exp_tue . '" wed="' . $exp_wed . '" thu="' . $exp_thur . '" fri="' . $exp_fri . '" sat="' . $exp_sat . '"/>';
            if (@$product['stop_sell'] == "1") {
                $xml .= '<RoomType id="' . $mp_details->roomtype_id . '" closed="true">';
            } elseif (@$product['open_room'] == "1") {
                $xml .= '<RoomType id="' . $mp_details->roomtype_id . '" closed="false">';
            } else {
                $xml .= '<RoomType id="' . $mp_details->roomtype_id . '">';
            }
            
            if (@$product['availability'] != '' && $room_value->update_availability == '1') {
                $xml .= '<Inventory totalInventoryAvailable="' . $product['availability'] . '"/>';
            }
            $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '" . $mp_details->roomtype_id . "' AND rate_type_id != ''")->result();
            foreach ($available_plans as $e_plan) {
                if ($e_plan->rateAcquisitionType != "Linked") {
                    
                    if ($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked") {
                        $plan_id = $e_plan->rateplan_id;
                    } else {
                        $plan_id = $e_plan->rate_type_id;
                    }
                    // echo $plan_id;
                    
                    $xml .= '<RatePlan id="' . $plan_id . '">';
                    //@$product['price']!='' && @$product['price'] >= (string)$rt_details->minAmount && @$product['price'] <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                    if (@$product['price'] != '' && $room_value->update_rate == '1') {
                        
                        if ($e_plan->pricingModel == "PerDayPricingByLengthOfStay") {
                            
                            for ($i = $minlos; $i <= $maxLos; $i++) {
                                $xml .= '<Rate lengthOfStay="' . $i . '" currency="' . $this->currency_code . '">
                                                                    <PerDay rate="' . $price . '"/>
                                                                    </Rate>';
                            }
                            //$xml .= '<Restrictions closedToArrival="'.$exp_cta.'" closedToDeparture="'.$exp_ctd.'"/>';
                        } elseif ($e_plan->pricingModel == 'PerDayPricing') {
                            
                            
                            $xml .= '<Rate currency="' . $this->currency_code . '"> <PerDay rate="' . $price . '"/>
                                                                </Rate> ';
                        } elseif ($e_plan->pricingModel == 'OccupancyBasedPricing') {
                            $xml .= '<Rate currency="' . $this->currency_code . '"> <PerOccupancy rate="' . $price . '" occupancy = "2"/></Rate> ';
                            $xml .= '<Rate currency="' . $this->currency_code . '"> <PerOccupancy rate="' . $room_value->exp_occupancy . '" occupancy = "1"/></Rate> ';
                        }
                        
                    } else {
                        if (@$product['price'] != '' && $room_value->update_rate == '1') {
                            if (@$product['price'] <= (string) $rt_details->minAmount || @$product['price'] >= (string) $rt_details->maxAmount) {
                                $price_error = "Price must be between " . $rt_details->minAmount . " and " . $rt_details->maxAmount;
                            }
                        }
                    }
                    
                    
                    if ($exp_ctd != "" || $exp_cta != "" || @$product['minimum_stay'] != "") {
                        $xml .= '<Restrictions';
                        $xml .= ($exp_ctd != ""?' closedToDeparture="' . $exp_ctd . '"':'');
                        $xml .= ($exp_cta != "" ?' closedToArrival="' . $exp_cta . '"':'');
                        
                        if (@$product['minimum_stay'] != "") {
                            $xml .= ' minLOS="' . @$product['minimum_stay'] . '" maxLOS="' . $maxLos . '"';
                        }
                        $xml .= ' />';
                    }
                    $xml .= "</RatePlan>";
                }
            }
            $xml .= "</RoomType></AvailRateUpdate></AvailRateUpdateRQ>";
        }
        
        $mail_data = '<strong> Request </strong> <br>';
        $mail_data .= $xml;
        $URL = trim($exp['urate_avail']);
        $ch  = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $mail_data .= '<strong> Response </strong> <br>';
        $mail_data .= $output;
        mail("xml@hoteratus.com", " Expedia.Com Request and Response ", $mail_data);
        mail("datahernandez@gmail.com", " Expedia.Com Request and Response " . hotel_id(), $mail_data);
        $data     = simplexml_load_string($output);
        $response = @$data->Error;
        
        if ($response != '') {
            $this->load->model("inventory_model");
            //echo 'fail';
            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $response, 'Bulk Update Save', date('m/d/Y h:i:s a', time()));
            $this->session->set_flashdata('bulk_error', 'Expedia - ' . (string) $response);
            $expedia_update = "Failed";
        } else {
            //echo 'success   ';
            $expedia_update = "Success";
			$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));

        }
        curl_close($ch);
        
        return true;
    }
    
    function SincroCalender($datepicker_full_start, $datepicker_full_end,$userid,$hotelid,$valor)
    {
        $ExpediaErrors = "";

                    $hotel_detail = get_data(HOTEL, array(
                'owner_id' => $userid,
                'hotel_id' => $hotelid
            ))->row()->currency;
            
            if ($hotel_detail != 0) {
                $this->currency_code = get_data(TBL_CUR, array(
                    'currency_id' => $hotel_detail
                ))->row()->currency_code;
                
            }

        $ch_details = get_data(CONNECT, array(
            'user_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => 1
        ))->row();
        if ($ch_details->mode == 0) {
            $urls = explode(',', $ch_details->test_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $exp[$path[0]] = $path[1];
            }
        } else if ($ch_details->mode == 1) {
            $urls = explode(',', $ch_details->live_url);
            foreach ($urls as $url) {
                $path          = explode("~", $url);
                $exp[$path[0]] = $path[1];
            }
        }
        
        $room_mapping = get_data(MAP, array(
            'owner_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => 1,
            'enabled' => 'enabled'
        ))->result_array();
        
        
        foreach ($room_mapping as $Mapping => $Mappingvalue) {
            if ($Mappingvalue["rate_id"] == "0") {
                $Data = $this->db->query("select *  from room_update where owner_id =" . $userid. " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();
                if (count($Data) == 0)
                    return "Verify Dates Range";
                
                $mp_details = get_data('import_mapping', array(
                    'user_id' => $userid,
                    'hotel_id' => $hotelid,
                    'channel' => 1,
                    'map_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $rt_details = get_data('import_mapping_expedia_ratelimit', array(
                    'user_id' => $userid,
                    'hotel_channel_id' => $mp_details->hotel_channel_id,
                    'hotel_id' => $hotelid,
                    'channel' => 1,
                    'rateType' => 'SellRate'
                ))->row();
                $oa_details = get_data('import_mapping_expedia_occupancy', array(
                    'user_id' => $userid,
                    'hotel_channel_id' => $mp_details->hotel_channel_id,
                    'hotel_id' => $hotelid,
                    'channel' => 1
                ))->row();
                $room_value = get_data('roommapping', array(
                    'import_mapping_id' => $Mappingvalue["import_mapping_id"]
                ))->row();
                
                $RateConvertion = $Mappingvalue["rate_conversion"];
                
                if ($room_value->explevel == "rate") {
                    
                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                            <Authentication username="' . $ch_details->user_name . '" password="' . $ch_details->user_password . '"/>
                            <Hotel id="' . $mp_details->hotel_channel_id . '"/>
                            ';
                    
                    foreach ($Data as $key => $value) {
                        $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                        $start_date = $datevalue->format('Y-m-d');
                        $end_date   = date("Y-m-d", strtotime($start_date . "+ 1 days"));
                        
                        $xml .= '<AvailRateUpdate> <DateRange from="' . $start_date . '" to="' . $start_date . '" sun="true" mon="true" tue="true" wed="true" thu="true" fri="true" sat="true"/>';
                        
                        $xml .= '<RoomType id="' . $mp_details->roomtype_id . '">';
                        
                        if ($value['availability'] != '' ) {
                            $xml .= '<Inventory totalInventoryAvailable="' . $value['availability'] . '"/>';
                        }
                        
                        if ($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked") {
                            $plan_id = $mp_details->rateplan_id;
                        } else {
                            $plan_id = $mp_details->rate_type_id;
                        }
                        
                        if ($value['stop_sell'] == 1) {
                            
                            $xml .= '<RatePlan id="' . $plan_id . '" closed = "true">';
                        } elseif ($value['open_room'] == 1) {
                            $xml .= '<RatePlan id="' . $plan_id . '" closed = "false">';
                        } else {
                            
                            $xml .= '<RatePlan id="' . $plan_id . '">';
                        }
                        

                        if ($value['price'] != '' && $valor='All') 
                        {
                            if ($mp_details->pricingModel == "PerDayPricingByLengthOfStay") {
                                
                                for ($i = $minlos; $i <= $maxLos; $i++) {
                                    $xml .= '<Rate lengthOfStay="' . $i . '" currency="' . $this->currency_code . '">
                                                            <PerDay rate="' . $price . '"/>
                                                            </Rate>';
                                }
                                
                            } elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                
                                $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD") . '"> <PerDay rate="' . $value['price'] * $RateConvertion . '"/>
                                                        </Rate> ';
                            } elseif ($mp_details->pricingModel == 'OccupancyBasedPricing') {
                                $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD"). '"> <PerOccupancy rate="' . $value['price'] * $RateConvertion . '" occupancy = "2"/></Rate> ';
                                $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD") . '"> <PerOccupancy rate="' . $value['price'] * $RateConvertion . '" occupancy = "1"/></Rate> ';
                            }
                            
                        }
                        


                        
                        $xml .= '<Restrictions';
                        if ($value['cta'] >= 0) {
                            $xml .= ' closedToArrival="' . ($value['cta'] == 1 ? "true" : "false") . '"';
                            
                        }
                        if ($value['ctd'] >= 0) {
                            $xml .= ' closedToDeparture="' . ($value['ctd'] == 1 ? "true" : "false") . '"';
                            
                        }
                        if ($value['minimum_stay'] >= 0) {
                            $xml .= ' minLOS="' . $value['minimum_stay'] . '"';
                            
                        }


                        
                        $xml .= ' /> </RatePlan></RoomType> </AvailRateUpdate>';
                        
                    }
                    
                    
                } //
                else {
                    
                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                            <Authentication username="' . $ch_details->user_name . '" password="' . $ch_details->user_password . '"/>
                            <Hotel id="' . $mp_details->hotel_channel_id . '"/>';
                    
                    foreach ($Data as $key => $value) {
                        
                        $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                        $start_date = $datevalue->format('Y-m-d');
                        $end_date   = date("Y-m-d", strtotime($start_date . "+ 1 days"));
                        
                        $xml .= '<AvailRateUpdate>
                                <DateRange from="' . $start_date . '" to="' . $start_date . '" sun="true" mon="true" tue="true" wed="true" thu="true" fri="true" sat="true"/>';
                        
                        
                        if ($value['stop_sell'] == "1") {
                            $xml .= '<RoomType id="' . $mp_details->roomtype_id . '" closed="true">';
                        } elseif ($value['open_room'] == "1") {
                            $xml .= '<RoomType id="' . $mp_details->roomtype_id . '" closed="false">';
                        } else {
                            $xml .= '<RoomType id="' . $mp_details->roomtype_id . '">';
                        }
                        
                        
                        if ($value['availability'] != '' ) {
                            $xml .= '<Inventory totalInventoryAvailable="' . $value['availability'] . '"/>';
                        }
                        
                        $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '" . $mp_details->roomtype_id . "' AND rate_type_id != '' and rate_type_id != 0 and hotel_id =" . $hotelid)->result();
                        
                        if (count($available_plans) == 0)
                            return "Verify Dates Range";
                        foreach ($available_plans as $e_plan) {
                            
                            if ($e_plan->rateAcquisitionType != "Linked") {
                                
                                if ($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked") {
                                    $plan_id = $e_plan->rateplan_id;
                                } else {
                                    $plan_id = $e_plan->rate_type_id;
                                }
                                
                                
                                $xml .= '<RatePlan id="' . $plan_id . '">';
                                
                                if ($value['price'] != '' && $valor='All' ) {
                                    
                                    if ($e_plan->pricingModel == "PerDayPricingByLengthOfStay") {
                                        
                                        for ($i = $minlos; $i <= $maxLos; $i++) {
                                            $xml .= '<Rate lengthOfStay="' . $i . '" currency="' . $this->currency_code . '">
                                                                <PerDay rate="' . $value['price'] * $RateConvertion . '"/>
                                                                </Rate>';
                                        }
                                        //$xml .= '<Restrictions closedToArrival="'.$exp_cta.'" closedToDeparture="'.$exp_ctd.'"/>';
                                    } elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                        
                                        
                                        $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD") . '"> <PerDay rate="' . $value['price'] * $RateConvertion . '"/>
                                                            </Rate> ';
                                    } elseif ($e_plan->pricingModel == 'OccupancyBasedPricing') {
                                        $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD") . '"> <PerOccupancy rate="' . $value['price'] * $RateConvertion . '" occupancy = "2"/></Rate> ';
                                        $xml .= '<Rate currency="' . (strlen($this->currency_code)>0?$this->currency_code:"USD") . '"> <PerOccupancy rate="' . $room_value->exp_occupancy . '" occupancy = "1"/></Rate> ';
                                    }
                                    
                                } else {
                                    if ($value['price'] != '' && $valor='All') {
                                        if (@$value['price'] <= (string) $rt_details->minAmount || @$value['price'] >= (string) $rt_details->maxAmount) {
                                            $price_error = "Price must be between " . $rt_details->minAmount . " and " . $rt_details->maxAmount;
                                        }
                                    }
                                }
                                
                                
                                
                                $xml .= '<Restrictions';
                                
                                if ($value['cta'] >= 0) {
                                    $xml .= ' closedToArrival="' . ($value['cta'] == 1 ? "true" : "false") . '"';
                                    
                                }
                                if ($value['ctd'] >= 0) {
                                    $xml .= ' closedToDeparture="' . ($value['ctd'] == 1 ? "true" : "false") . '"';
                                    
                                }
                                if ($value['minimum_stay'] >= 0) {
                                    $xml .= ' minLOS="' . $value['minimum_stay'] . '"';
                                    
                                }
                                
                                
                                $xml .= ' /> </RatePlan>';
                                
                            }
                            
                            
                        }
                        
                        $xml .= '</RoomType> </AvailRateUpdate>';
                        
                        
                    }
                    
                    
                    
                }
            } //
        } ///
        
        $xml .= " </AvailRateUpdateRQ>";
        
        $mail_Precio = '<strong> Request </strong> <br>';
        $mail_Precio .= $xml;
        
        $URL = trim($exp['urate_avail']);
        $ch  = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $mail_Precio .= '<br><br> <strong> Response </strong> <br>';
        $mail_Precio .= $output;
        mail("datahernandez@gmail.com", " Expedia.Com Request and Response Synced price Room Syncro" . $hotelid, $mail_Precio);
        mail("xml@hoteratus.com", " Expedia.Com Request and Response Synced price Room Syncro" . $hotelid, $mail_Precio);
        
        return "Syncro Done Expedia";
    }
    
}

?> 