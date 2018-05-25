<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class hotusagroup_model extends CI_Model
{

	function reservation_channel($channel_id='',$reservation_id='')
	{
			$bookdetails = get_data('import_reservation_HOTUSAGROUP',array('import_reserv_id '=>($reservation_id),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row_array();
		

			//$roomdetails = $this->db->query(" SELECT b.* FROM import_mapping_HOTUSAGROUP as a left join roommapping as b on a.map_id = b.import_mapping_id and b.channel_id=19 where a.roomtype_id =".$bookdetails['room_id']." and rate_type_id=".$bookdetails['rateplan_id'])->row_array();

			$data['CC_NAME']=	$bookdetails['target_user_name'];
			$data['CC_NUMBER']=	$bookdetails['target_num'];
			$data['CC_DATE']=	substr($bookdetails['target_expiration'], 0,2);
			$data['CC_YEAR']=	substr($bookdetails['target_expiration'], -2);
			$data['CC_CVC']=	$bookdetails['cvv'];
			

			$data['RESER_NUMBER']= 	$bookdetails['book_ref'];
			$data['RESER_DATE']= 	date('M d,Y',strtotime($bookdetails['create_date']));
			$data['RESER_ID']= 	$bookdetails['import_reserv_id'];
			$data['roomtypeId']= '1';  //$roomdetails['property_id'];
			$data['rateplanid']= '1';  //$roomdetails['rate_id'];

			$data['curr_cha_currency']= '';	//buscar roomdetails $bookdetails['currency'];
			$data['guest_name']= 	$bookdetails['client_name'];
			//$data['start_date']= 	date('Y/m/d',strtotime($bookdetails['start_date']));
			//$data['end_date']=	date('Y/m/d',strtotime($bookdetails['end_date']));
			$data['channel_room_name']=''; //$bookdetails['room_type'].'-'.$bookdetails['rateplan_name'] ;
			$data['reservation_code']= 	$bookdetails['book_ref'];
			$data['RoomNumber']	= 	$bookdetails['RoomNumber'];
			$data['ROOMCODE']=	'';
			$data['promotion_name']='';//$bookdetails['promotion_name'];
			$data['channel_namexml']='';//$bookdetails['channel_name'];
			
			if($bookdetails['status']=='C')
			{
				$data['status'] = 'New Booking';
			}
			else if($bookdetails['modification_date']!='')
			{
				$data['status'] = 'Modification';
			}
			else if($bookdetails['status']=='B')
			{
				$data['status'] = 'Cancellation';
			}

			$data['start_date']=	$bookdetails['start_date'];
			$data['end_date']=	$bookdetails['end_date'];

			$data['CHECKIN']=	date('Y/m/d',strtotime($bookdetails['start_date']));
			$data['CHECKOUT']=	date('Y/m/d',strtotime($bookdetails['end_date']));

			$data['nig']=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);
			/*
			$inbwdays = explode(',',$bookdetails['pricedate']);
			$baseRate = explode(',', $bookdetails['pricemoney']);

			for($i=0; $i<count($inbwdays); $i++){
				if($inbwdays[$i] != ""){
					$data['perdayprice'][] = array(
						$inbwdays[$i] => $baseRate[$i],
					);
				}
			}
			*/
			$data['inbwdays']= $bookdetails['pricedate'];
			$data['baseRate']= $bookdetails['pricemoney'];
			$data['tax']= 0;

			$data['ADULTS']= '';	//$bookdetails['adults']; buscar en detalles
			$data['CHILDREN']= '';	//$bookdetails['children'];

			$data['description']= 	$bookdetails['comments'];
			$data['policy_checin']= 	'';//$bookdetails['CORRCHECKIN'];
			$data['policy_checout']= 	'';//$bookdetails['CORRCHECKOUT'];
			$data['CRIB']='';//	$bookdetails['child_age'];
			$data['subtotal']= 	$bookdetails['price_excluding_vat'];
			$data['discount']= 	'';
			$data['CURRENCY']=	'';

			$data['email']=	'';//$bookdetails['email'];
			$data['street_name']='';//	$bookdetails['address'];
			$data['city_name']='';//	$bookdetails['city'];
			$data['country']='';//	$bookdetails['country'];
			$data['phone']=	'';//$bookdetails['phone'];
			$data['commission']	= 0;
			$data['mealsinc']= 	'';//$bookdetails['MEALSINC'];
			$data['price']= $bookdetails['price'];
			
			if($bookdetails['payment_type']=='20')
			{
				$data['payment_method']='Virtual Credit Card Payment';
			}
			else if($bookdetails['payment_type']=='27')
			{
				$data['payment_method']='Voucher Payment';
			}
			else if($bookdetails['payment_type']=='25' || $bookdetails['payment_type']=='44' || $bookdetails['payment_type']=='45' )
			{
				$data['payment_method']='Direct Bill';
			}
			else if($bookdetails['payment_type']=='12')
			{
				$data['payment_method']='Direct Payment';
			}
			else
			{
				$data['payment_method']='';
			}
	}

	function get_mapping($channel_id,$type='')
	{
		

	
		if($type!='update')
		{
			$connected_room = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->result_array();
			if(count($connected_room)!=0)
			{
				foreach($connected_room as $import_mapping)
				{
					extract($import_mapping);
					$import[] = $import_mapping_id;
				}
			}
			else
			{
				$import[] ='';
			}
		}
		else
		{
			$import[] ='';
		}
		$clean = $this->cleanArray($import);
		
		/* ----- Get Sub Rooms ------ */
		$this->db->select('E.map_id, E.roomname, E.roomcode, E.vr_rate, E.mealplan');
		if($clean!='')
		{
			$this->db->where_not_in('E.map_id',$import);
		}
	
		$this->db->where(array('hotel_id'=>hotel_id(),'channel_id'=>$channel_id));
		$result = $this->db->get('import_mapping_HOTUSAGROUP as E');
		/* ----- End Of Get Sub Rooms ------ */
		if($result!='')
		{
			return $result->result();
		}
		else
		{
			return false;
		}
	}

	function get_all_mapping_rooms($channel_id)
	{

		$count = $this->db->select('map_id')->from('import_mapping_HOTUSAGROUP')->where(array('hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->count_all_results();
			return $count;
	}

	function get_all_mapped_rooms($channel_id)
	{
		$this->db->select('R.mapping_id,R.owner_id,R.hotel_id,R.property_id,R.rate_id,R.channel_id,R.import_mapping_id,R.guest_count,R.refun_type,R.enabled,R.included_occupancy,R.extra_adult,R.extra_child,R.single_quest,R.update_rate,R.update_availability,R.rate_conversion,R.explevel');
		$this->db->join('import_mapping_HOTUSAGROUP as B','R.import_mapping_id=B.map_id');
		$this->db->where(array('B.channel_id'=>$channel_id,'R.hotel_id'=>hotel_id(),'R.channel_id'=>$channel_id));
		$query = $this->db->get(MAP.' as R');
		
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}

	}
	 function cleanArray($array)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $sub_array)
            {
                $result = $this->cleanArray($sub_array);
                if ($result == '')
				{
                    unset($array[$key]);
                }
                else
                {
                    $array[$key] = $result;
                }
            }
        }
        if ($array == NULL && $array == FALSE && $array == '' || $array == array())
		//if (empty($array))
        {
            return false;
        }
        return $array;
    }
     function getReservationLists($source,$channel_id)
    {
    	
    	
        $this->db->select('import_reserv_id, book_ref,status, 	client_name ,start_date,end_date,last_update,price,create_date,modification_date');
        $this->db->order_by('import_reserv_id', 'desc');
        $this->db->where('hotel_id', hotel_id());
       $this->db->where('channel_id', $channel_id);
        $data = $this->db->get('import_reservation_HOTUSAGROUP')->result();
        if ($data) {
            $bnow = array();
            foreach ($data as $val) {
                
                $status     = ($val->modification_date !=""?"Modify":($val->status=='B'?"Cancelled":"Confirmed"));
                $PersonName =  $val->client_name;
                
               /* $room_id = @get_data(MAP, array( 'channel_id' => 19,
                    'import_mapping_id' => get_data('import_mapping_AGODA', array(
                        'roomtype_id' => $val->room_id,
                        'rate_type_id' => $val->rateplan_id,
                        'hotel_id' => hotel_id()
                    ))->row()->map_id
                ))->row()->property_id;*/
                $room_id =0;
                $checkin  = date('Y/m/d', strtotime($val->start_date));
                $checkout = date('Y/m/d', strtotime($val->end_date));
                $nig      = _datebetween($checkin, $checkout);
                if ($source == "all") {
                    $bnow[] = (object) array(
                        'reservation_id' => $val->import_reserv_id,
                        'reservation_code' => $val->book_ref,
                        'status' => $status,
                        'guest_name' => $PersonName,
                        'room_id' => $room_id,
                        'channel_id' => $channel_id,
                        'start_date' => $val->start_date,
                        'end_date' => $val->end_date,
                        'booking_date' => $val->create_date,
                        'currency_id' => '',//$val->currency,
                        'price' => $val->price,
                        'num_nights' => $nig,
                        'current_date_time' => $val->last_update
                    );
                } else if ($source == "separate") {
                    $bnow[] = (object) array(
                        'import_reserv_id' => $val->import_reserv_id,
                        'IDRSV' => $val->book_ref,
                        'STATUS' => $status,
                        'FIRSTNAME' => $val->PersonName,
                        'ROOMCODE' => $room_id,
                        'channel_id' => $channel_id,
                        'CHECKIN' => $val->start_date,
                        'CHECKOUT' => $val->end_date,
                        'RSVCREATE' => $val->create_date,
                        'CURRENCY' => '',//$val->currency,
                        'REVENUE' => $val->price,
                        'num_nights' => $nig,
                        'current_date_time' => $val->last_update
                    );
                }
            }
            return $bnow;
        } else {
            return $bnow = array();
        }
    }
     function getReservationDetails($source, $id,$channel_id)
    {
        $despegarm = get_data('import_reservation_HOTUSAGROUP', array(
            'import_reserv_id ' => ($id),'channel_id'=>$channel_id
        ))->row_array();
        
        /*
        $room_id = @get_data(MAP, array(
            'channel_id' => $despegarm['channel_id'],
            'import_mapping_id' => get_data('import_mapping_DESPEGAR', array(
                'codeRoomType' => $despegarm['RoomTypeCode'],
                'RateTypeCode' => $despegarm['RatePlanCode'],
                'user_id' => current_user_type(),
                'hotel_id' => hotel_id()
            ))->row()->import_mapping_id
        ))->row()->property_id;
*/
        $room_id =0;
        if ($source == 'list') {
            $data['curr_cha_id'] = secure($channel_id);
        }
        $data['CC_NAME']   = $despegarm['target_user_name'];
        $data['CC_NUMBER'] = ($despegarm['target_num']);
        $data['CC_DATE']   = substr(($despegarm['target_expiration']), 0, 2);
        $data['CC_YEAR']   = substr($despegarm['target_expiration'], 2);
        $data['CC_CVC']    = ($despegarm['cvv']);
        $data['CC_TYPE']   = ($despegarm['target_type']);
        
        $data['RESER_NUMBER'] = $despegarm['book_ref'];
        $data['RESER_DATE']   = date('M d,Y', strtotime($despegarm['last_update']));
        $data['RESER_ID']     = $despegarm['import_reserv_id'];
        
        $data['curr_cha_currency'] =''; //$despegarm['Currency'];
        $data['guest_name']        = $despegarm['client_name'];
        $data['start_date']        = date('Y/m/d', strtotime($despegarm['start_date']));
        $data['end_date']          = date('Y/m/d', strtotime($despegarm['end_date']));
        $data['reservation_code']  = $despegarm['book_ref'];
        $data['ROOMCODE']          = $room_id;
        
        $data['roomtypeId']= '1';  //$roomdetails['property_id'];
		$data['rateplanid']= '1'; 
        	
		if($despegarm['modification_date']!='')
		{
			$data['status'] = 'Modification';
		}
		else if($despegarm['status']=='B')
		{
			$data['status'] = 'Cancellation';
		}
    	else if($despegarm['status']=='C')
		{
			$data['status'] = 'New Booking';
		}

        $data['start_date'] = $despegarm['start_date'];
        $data['end_date']   = $despegarm['end_date'];
        
        $data['CHECKIN']  = date('Y/m/d', strtotime($despegarm['start_date']));
        $data['CHECKOUT'] = date('Y/m/d', strtotime($despegarm['end_date']));
        
        $data['nig'] = _datebetween($data['CHECKIN'], $data['CHECKOUT']);
        //$Guest                        =    explode('##',0);
        
        $data['ADULTS']     =0; //$despegarm['Adult'];
        $data['CHILDREN']   = 0;//$despegarm['Child'];
        $data['RoomNumber'] = $despegarm['RoomNumber'];
        
        
        $data['description']    = ''; //$despegarm['Text'];
        $data['policy_checin']  = ''; //$despegarm['Start'];
        $data['policy_checout'] = ''; //$despegarm['End'];
        $data['CRIB']           = ''; //$despegarm['CRIB'];
        $data['subtotal']       = $despegarm['price_excluding_vat'];
        $data['CURRENCY']       = '';//$despegarm['Currency'];
        
        $data['email']          = ''; //$despegarm['Email'];
        $data['street_name']    = ''; //$despegarm['AddressLine'];
        $data['city_name']      = ''; //$despegarm['CityName'];
        $data['country']        = ''; //$despegarm['CountryName'];
        $data['phone']          = ''; //$despegarm['PhoneNumber'];
        $data['commission']     = ''; //$despegarm['COMMISSION'];
        $data['mealsinc']       = ''; //$despegarm['MEALSINC'];
        $data['price']          = $despegarm['price'];
        $data['reservation_id'] = $despegarm['import_reserv_id'];
        $data['members_count']  = 0;//$despegarm['Adult'];
        $data['children']       = 0;//$despegarm['Child'];
        $data['grandtotal']  =$despegarm['price'];
         $data['tax']  =$despegarm['price']-($despegarm['price_excluding_vat']);
        /*
        $inbwdays = explode(',', $despegarm['stayDate']);
        $baseRate = explode(',', $despegarm['baseRate']);
        
        for ($i = 0; $i < count($inbwdays); $i++) {
            if ($inbwdays[$i] != "") {
                $data['perdayprice'][] = array(
                    $inbwdays[$i] => $baseRate[$i]
                );
            }
        }
        */
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
        
       if($despegarm['payment_type']=='20')
			{
				$data['payment_method']='Virtual Credit Card Payment';
			}
			else if($despegarm['payment_type']=='27')
			{
				$data['payment_method']='Voucher Payment';
			}
			else if($despegarm['payment_type']=='25' || $despegarm['payment_type']=='44' || $despegarm['payment_type']=='45' )
			{
				$data['payment_method']='Direct Bill';
			}
			else if($despegarm['payment_type']=='12')
			{
				$data['payment_method']='Direct Payment';
			}
			else
			{
				$data['payment_method']='';
			}

        /*
        $room = get_data('import_mapping_DESPEGAR', array(
            'codeRoomType' => $despegarm['RoomTypeCode'],
            'RateTypeCode' => $despegarm['RatePlanCode']
        ), 'nameRoomType , rate_name')->row_array();
        if ($room) {
            $data['channel_room_name'] = $room['nameRoomType'] . ' ' . $room['rate_name'];
        }
        */
         $data['channel_room_name'] ='';
        if ($source == 'print') {
            $data['room_id']            = $room_id;
            $data['num_nights']         = '1';
            $data['price']              = $despegarm['price'];
            $data['booking_date']       = $data['create_date'];
            $data['mobile']             = ''; //$despegarm['PhoneNumber'];
            $data['curr_cha_id']        = $channel_id;
            $data['currency']           ='';// $despegarm['Currency'];
            $data['meal_name']          = '---';
            $data['cancel_description'] = '---';
        }
        return $data;
    }
    
    
}