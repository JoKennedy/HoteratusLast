<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class agoda_model extends CI_Model
{
	private $currency_code; 

	public function __construct()
    {
        
        parent::__construct();

		if(current_user_type())
		{
			$hotel_detail=get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency;
		
            if  ($hotel_detail !=0)   {    
			$this->currency_code	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->currency_code;
            
            }

		}
		
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
	
	
	function get_room_available($agodaData)
	{
		
	   $this->db->select('*');
	   $this->db->where("user_id",$agodaData['user_id']);
	   $this->db->where("hotel_id",$agodaData['hotel_id']);
	  
	   $this->db->where("channel_id",$agodaData['channel_id']);
	   $this->db->where("room_id",$agodaData['RoomId']);
	   $this->db->where("room_name",$agodaData['RoomName']);
	   $this->db->from('import_mapping_AGODA');
	   $query=$this->db->get();
	   return  $query->num_rows();
	}
	
	public function insert_agoda_room($data)
	{
	    
	      $data=array(
		  'room_id'=>$data['RoomId'],
		  'user_id'=>$data['user_id'],
		  'num_persons'=>$data['num_persons'],
		  'min_rate'=>$data['min_rate'],
		  'hotel_id'=>$data['hotel_id'],
		  'room_name'=>$data['RoomName'],
		  'channel_id'=>$data['channel_id']
		  );
		$query=$this->db->insert('import_mapping_AGODA',$data);
		return $query;
		 
	   
	}
	   
	
	
function get_mapping_rooms($channel_id, $type = '')
{
	

	 
	  


            if ($type != 'update') {
                $connected_room = get_data(MAP, array(
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
            $this->db->select('B.map_id, B.roomtype_name, B.name');
            if ($clean != '') {
                $this->db->where_not_in('B.map_id', $import);
            }
            $this->db->where(array(
                'hotel_id' => hotel_id()
            ));
            $result = $this->db->get('import_mapping_AGODA' . ' as B');
            if ($result != '') {
                return $result->result();
            } else {
                return false;
            }
        
}

function getAgodaRoom($roomtypeid,$rateplanid,$hotelid)
{
	

	$query=$this->db->query("select * from import_mapping_AGODA where hotel_id = $hotelid and roomtype_id =$roomtypeid and rate_type_id=$rateplanid")->row_array();
		return $query;
}



function upadte_room($data)
{
	
	
	
	    $room_name=$data['roomName'];
		$room_id=$data['roomId'];
		$id=$data['id'];
	$this->db->set('room_name',$room_name);
	$this->db->set('room_id',$room_id);
    $this->db->where('import_mapping_id',$id);
    $this->db->update('import_mapping_AGODA');
    $result =  $this->db->affected_rows(); 
	
     return $result;
	 
		
	
	
}

function delete_room($id)
{
	
	
	$result=$this->db->delete('import_mapping_AGODA', array('import_mapping_id' => $id)); 
	return $result;
	
}


function save_mapping($data)
{
	
	
	$hotel_id 		= 	hotel_id();
	$user_id 		= 	current_user_type();
	
	
 $insert_data=array(
    'owner_id'=>$user_id,
	'hotel_id'=>$hotel_id,
	'property_id'=>$data['property_id'],
	'rate_id'=>$data['ratepaln_id'],
	'channel_id'=>insep_decode($data['chan_id']),
	'import_mapping_id'=>$data['import_mapping_id'],
	'enabled'=>$data['optionenable'],
	'price_type'=>$data['price_type'],
	'rate_conversion'=>$data['rate_conversion'],
	'ChargeType'=>$data['ChargeTypeID'],
	'Adults'=>$data['Adult1'],
	'Children'=>$data['Children1'],
	'Infants'=>$data['Infants1'],
	'extra_infants'=>$this->input->post('extra_infants'),
	'extra_children'=>$this->input->post('extra_children'),
	'extra_adults'=>$this->input->post('extra_adults'),
	'minimum_rate'=>$data['min_rate'],
	'min_ios'=>$data['min_ios'],
	'max_ios'=>$data['max_ios'],
	'min_days_adv'=>$data['min_adv_days'],
	'max_days_adv'=>$data['max_adv_days']
	

);	


 $result=$this->db->insert(MAP,$insert_data);
  return  $result;
 
}




	function bulk_update($product,$import_mapping_id,$mapping_id,$price)
	{


		$headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
	    $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
	    $headers .= "CC: support <felix@hoteratus.com>\r\n";
	    $headers .= "BCC: datahernandez@gmail.com\r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";

	    $up_days =  $product['days'];

	    if(in_array('1', $up_days)) 
	    {
	        $exp_sun = 'true';
	    }
	    else 
	    {
	        $exp_sun = 'false';
	    }
	    if(in_array('2', $up_days)) 
	    {
	        $exp_mon = 'true';
	    }
	    else 
	    {
	        $exp_mon = 'false';
	    }
	    if(in_array('3', $up_days)) 
	    {
	        $exp_tue = 'true';
	    }
	    else 
	    {
	        $exp_tue = 'false';
	    }
	    if(in_array('4', $up_days)) 
	    {
	        $exp_wed = 'true';
	    }
	    else
	    {
	        $exp_wed = 'false';
	    }
	    if(in_array('5', $up_days)) 
	    {
	        $exp_thur = 'true';
	    }
	    else 
	    {
	        $exp_thur = 'false';
	    }
	    if(in_array('6', $up_days)) 
	    {
	        $exp_fri = 'true';
	    }
	    else 
	    {
	        $exp_fri = 'false';
	    }
	    if(in_array('7', $up_days)) 
	    {
	        $exp_sat = 'true';
	    }
	    else 
	    {
	        $exp_sat = 'false';
	    }
		
	    
	    $re_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$product['start_date'])));
	    $re_end_date = date('Y-m-d',strtotime(str_replace('/','-',$product['end_date'])));
	    

		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>19))->row();
		$urls="";						
		if($ch_details->mode == 0){
			$urls =$ch_details->test_url;
		}else if($ch_details->mode == 1){
			$urls =$ch_details->live_url;
		}   

		$mp_details = get_data('import_mapping_AGODA',array('hotel_id'=>hotel_id(),'channel'=>19,'map_id'=>$import_mapping_id))->row();

		$room_value = get_data('roommapping',array('import_mapping_id'=>$import_mapping_id,'channel_id'=>19))->row();
		$RateConvertion = $room_value->rate_conversion;

		if(@$product['minimumstay'] == ''){
		
			$minlos = @$product['minimumstay'];
		}

		$maxLos = $mp_details->maxLos;
		$mapping_values = get_data('mapping_values',array('mapping_id'=>$mapping_id))->row_array();

		if($mapping_values){
			if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
				if(@$product['minimumstay'] < $mapping_values['value']){
					$maxLos = $mapping_values['value'];
				}
			}
		}
			$xml='<?xml version="1.0" encoding="UTF-8"?>
					<request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="1">
						<criteria property_id="'.$mp_details->hotel_channel_id.'">';

			if (isset($product['availability']) || is_numeric(@$product['ctd']) || is_numeric(@$product['cta']) || @$product['stops'] != "" )
			{
				$xml.='<inventory>
								<update room_id="'.$mp_details->roomtype_id.'">
									<date_range from="'.$re_sart_date.'" to="'.$re_end_date.'">';
						if($exp_mon)						
							$xml.='<dow>1</dow>';
						if($exp_tue)						
							$xml.='<dow>2</dow>';
						if($exp_wed)						
							$xml.='<dow>3</dow>';
						if($exp_thur)						
							$xml.='<dow>4</dow>';
						if($exp_fri)						
							$xml.='<dow>5</dow>';
						if($exp_sat)						
							$xml.='<dow>6</dow>';
						if($exp_sun)						
							$xml.='<dow>7</dow>';
						$xml.='</date_range>';

						if (isset($product['availability']) )
						{
							$xml.='<allotment>'.$product['availability'].'</allotment>';
						}
						

						if(is_numeric(@$product['ctd']) || is_numeric(@$product['cta']) || @$product['stops'] != "")
							{
								$xml.='<restrictions>';
								if( @$product['stops'] != "" )
								{
									$xml.='<closed>'.(@$product['stops']==1?'true':'false').'</closed>';
								}
								
								if(is_numeric(@$product['ctd']))
								{
									$xml.='<ctd>'.(@$product['ctd']==1?'true':'false').'</ctd>';
								}

								if(is_numeric(@$product['cta']))
								{
									$xml.='<cta>'.(@$product['ctd']==1?'true':'false').'</cta>';
								}
									
								$xml.='</restrictions>';
								
							}

					
							$xml.='		</update>
									</inventory>';
			}
			if (@$product['price'] != "" || @$product['minimumstay'] != "") 
			{
					$xml.='<rate>
						<update room_id="'.$mp_details->roomtype_id.'" rateplan_id="'.$mp_details->rate_type_id.'">
							<date_range from="'.$re_sart_date.'" to="'.$re_end_date.'">
							</date_range>';
					if (@$product['price'] != "")
					{
						$xml.='	<prices currency="'.$mp_details->currency.'"> <occupancy default="'.$product['price']*$RateConvertion .'"></occupancy> </prices>';
					}

					if (@$product['minimumstay'] != "")
					{
						$xml.='<restrictions> <los> <min>'.$product['minimumstay'].' </min> </los> </restrictions>';
					}

					$xml.='		</update>
							</rate>';

			}


						$xml.='</criteria>
						</request>';

						

		$mail_data = $xml;
		$URL = trim($urls);
		$ch = curl_init();
	  	curl_setopt( $ch, CURLOPT_URL, $URL );
	  	curl_setopt( $ch, CURLOPT_POST, true );
	 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	  	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml);
		$output = curl_exec($ch);
		$mail_data .= '<strong> Response </strong> <br>';
		$mail_data .= $output;				
		 mail("xml@hoteratus.com", "agoda.com Request and Response ".hotel_id(), $mail_data, $headers);
		$data = simplexml_load_string($output); 
		$response = @$data->Error;
		
		if($response!='')
		{ 
			$this->load->model("inventory_model");
			//echo 'fail';
			$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
			$this->session->set_flashdata('bulk_error','Agoda - '.(string)$response);
			$agoda_update = "Failed";
		}
		else
		{
			//echo 'success   ';
			$agoda_update = "Success";
			$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Bulk update has been updated successfully!!!', 'Bulk Update', date('m/d/Y h:i:s a', time()));
		}
		curl_close($ch);

		return true; 
	}

	function SincroCalender($datepicker_full_start,$datepicker_full_end,$userid,$hotelid)
	{
		$AgodaErrors="";
		$mail_data='';
        $headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
        $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
        $headers .= "CC: support <felix@hoteratus.com>\r\n";
       // $headers .= "BCC: datahernandez@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";

		 $ch_details     = get_data(CONNECT, array(
            'user_id' => $userid,
            'hotel_id' => $hotelid,
            'channel_id' => "19"
        ))->row();

		$url="";						
		if($ch_details->mode == 0){
			$url =$ch_details->test_url;
		}else if($ch_details->mode == 1){
			$url =$ch_details->live_url;
		}   


		$room_mapping = get_data(MAP,array('owner_id'=>$userid,'hotel_id'=>$hotelid,'channel_id'=>19,'enabled'=>'enabled'))->result_array();



		 foreach ($room_mapping as $Mapping => $Mappingvalue) {

		 	$mp_details = get_data('import_mapping_AGODA',array('hotel_id'=>$hotelid,'channel'=>19,'map_id'=>$Mappingvalue["import_mapping_id"]))->row();
		 	$RateConvertion = $Mappingvalue["rate_conversion"];
		 	if($Mappingvalue["rate_id"]=="0"){
		 		 $Data = $this->db->query("select *  from room_update where owner_id =" . $userid. " and hotel_id =" . $hotelid . " and individual_channel_id =0 and room_id =" . $Mappingvalue["property_id"] . " and STR_TO_DATE(separate_date, '%d/%m/%Y') BETWEEN '" . $datepicker_full_start . "' and '" . $datepicker_full_end . "' order by STR_TO_DATE(separate_date, '%d/%m/%Y') asc")->result_array();

		 		 


                foreach ($Data as $key => $value) 
                {	
                	$xml='<?xml version="1.0" encoding="UTF-8"?>
					<request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="1">
						<criteria property_id="'.$mp_details->hotel_channel_id.'">';
                    
                    $datevalue  = DateTime::createFromFormat('d/m/Y', $value["separate_date"]);
                    $start_date = $datevalue->format('Y-m-d');
                    $end_date   = date("Y-m-d", strtotime($start_date . "+ 1 days"));


                    	if (isset($value['availability']) || is_numeric(@$value['ctd']) || is_numeric(@$value['cta']) || @$value['stop_sell'] == "1" || @$value['open_room'] == "1")
						{
							$xml.='<inventory>
									<update room_id="'.$mp_details->roomtype_id.'">
										<date_values>
										<value>'.$start_date.'</value>
										</date_values>';

									if (isset($value['availability']) )
									{
										$xml.='<allotment>'.$value['availability'].'</allotment>';
									}
									

									if(is_numeric(@$value['ctd']) || is_numeric(@$value['cta']) || @$value['stop_sell'] == "1" || @$value['open_room'] == "1")
										{
											$xml.='<restrictions>';
											
											$xml.='<closed>'.(@$value['stop_sell']==1?'true':'false').'</closed>';
											
											
											if(is_numeric(@$value['ctd']))
											{
												$xml.='<ctd>'.(@$value['ctd']==1?'true':'false').'</ctd>';
											}

											if(is_numeric(@$value['cta']))
											{
												$xml.='<cta>'.(@$value['ctd']==1?'true':'false').'</cta>';
											}
												
											$xml.='</restrictions>';
											
										}

								
										$xml.='		</update>
												</inventory>';
						}

						if (@$value['price'] != "" || @$value['minimum_stay'] != "") 
						{
							$xml.='<rate>								
								<update room_id="'.$mp_details->roomtype_id.'" rateplan_id="'.$mp_details->rate_type_id.'">
								<date_values>
								<value>'.$start_date.'</value>
								</date_values>';
									
							if (@$value['price'] != "")
							{
								$xml.='	<prices currency="'.$mp_details->currency.'"> <occupancy default="'.$value['price']*$RateConvertion.'"></occupancy> </prices>';
							}

							if (@$value['minimum_stay'] != "")
							{
								$xml.='<restrictions> <los> <min>'.$value['minimum_stay'].' </min> </los> </restrictions>';
							}

							$xml.='		</update>
									</rate>';

						}


						$xml.='</criteria>
						</request>';

		                $mail_data .= $xml;
						$url = trim($url);
						$ch = curl_init();
					  	curl_setopt( $ch, CURLOPT_URL, $url );
					  	curl_setopt( $ch, CURLOPT_POST, true );
					 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
					  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					  	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml);
						$output = curl_exec($ch);
						curl_close($ch);
						$mail_data .= '<strong> Response </strong> <br>';
						$mail_data .= $output;				
					
						$data = simplexml_load_string($output); 
						$response = @$data->Error;
						
						if($response!='')
						{ 
							$this->load->model("inventory_model");
							//echo 'fail';
							$this->inventory_model->store_error($userid,$hotelid,$room_value->channel_id,$response,'SincroCalender Update Save',date('m/d/Y h:i:s a', time()));
							$this->session->set_flashdata('bulk_error','Agoda - '.(string)$response);
							$AgodaErrors .= "Failed".$response;
						}
						else
						{
							$AgodaErrors.="Sincro Correct $start_date <br>";
						}

				

                }
				
                	 mail("xml@hoteratus.com", "agoda.com Sincro ".$hotelid, $mail_data, $headers);
    			


			}
		 }//
		 
		
		  return $AgodaErrors;
	}


	 function getReservationLists($source)
    {
        $this->db->select('import_reserv_id, booking_id,status, 	firstname, lastname    ,room_id,rateplan_id,arrival,departure,last_action,currency,net_inclusive_amt,booking_date, status');
        $this->db->order_by('import_reserv_id', 'desc');
        $this->db->where('hotel_id', hotel_id());
        $data = $this->db->get('import_reservation_AGODA')->result();
        if ($data) {
            $bnow = array();
            foreach ($data as $val) {
                
                $status     = $val->status;
                $PersonName = $val->firstname . ' ' . $val->lastname;
                
                $room_id = @get_data(MAP, array(
                    'channel_id' => 19,
                    'import_mapping_id' => get_data('import_mapping_AGODA', array(
                        'roomtype_id' => $val->room_id,
                        'rate_type_id' => $val->rateplan_id,
                        'hotel_id' => hotel_id()
                    ))->row()->map_id
                ))->row()->property_id;
                
                $checkin  = date('Y/m/d', strtotime($val->arrival));
                $checkout = date('Y/m/d', strtotime($val->departure));
                $nig      = _datebetween($checkin, $checkout);
                if ($source == "all") {
                    $bnow[] = (object) array(
                        'reservation_id' => $val->import_reserv_id,
                        'reservation_code' => $val->booking_id,
                        'status' => $status,
                        'guest_name' => $PersonName,
                        'room_id' => $room_id,
                        'channel_id' => 19,
                        'start_date' => $val->arrival,
                        'end_date' => $val->departure,
                        'booking_date' => $val->booking_date,
                        'currency_id' => $val->currency,
                        'price' => $val->net_inclusive_amt,
                        'num_nights' => $nig,
                        'current_date_time' => $val->last_action
                    );
                } else if ($source == "separate") {
                    $bnow[] = (object) array(
                        'import_reserv_id' => $val->import_reserv_id,
                        'IDRSV' => $val->booking_id,
                        'STATUS' => $status,
                        'FIRSTNAME' => $val->PersonName,
                        'ROOMCODE' => $room_id,
                        'channel_id' => 19,
                        'CHECKIN' => $val->arrival,
                        'CHECKOUT' => $val->departure,
                        'RSVCREATE' => $val->booking_date,
                        'CURRENCY' => $val->currency,
                        'REVENUE' => $val->net_inclusive_amt,
                        'num_nights' => $nig,
                        'current_date_time' => $val->last_action
                    );
                }
            }
            return $bnow;
        } else {
            return $bnow = array();
        }
    }

     function send_confirmation_email($channel_id,$id,$user_id,$hotel_id)
    {
        $channel_data = get_data("user_connect_channel", array("user_id" => $user_id,'hotel_id'=>$hotel_id,'channel_id' => $channel_id))->row();

        $data = get_data("import_reservation_AGODA", array('user_id' => $user_id,'hotel_id' => $hotel_id,'booking_id' => $id))->row_array();
        if($channel_data){

            $get_email_info     =   get_mail_template('20');

            $email_subject1= $status + ' ' + $get_email_info['subject'];

            $email_content1= $get_email_info['message'];

            //$row=get_data(USERS,array('user_id'=>user_id()));
            if($data['type'] == "Book"){
                $status = "New Booking";
            }else if($data['type'] == "Modify"){
                $status = "Modified";
            }else if($data['type'] == "Cancel"){
                $status = "Canceled";
            }

            $staydate = explode(',', $data['stayDate']);
            $baserate = explode(',', $data['baseRate']);
            $dailyprice = "";

            for($i=0; $i<count($staydate); $i++){
                if($staydate[$i] != ""){

                    $dailyprice .= '<tr><td style="border: 1px solid #d9d9d9; line-height: 1.42857;padding: 8px; width: 50%;">'.date('M d, Y',strtotime(str_replace('/','-',$staydate[$i]))).'<br></td>
                        <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;   padding: 8px; width: 50%;">Adult rate<span class="subtext">(Basic deal)</span>'.$data['currency'].' '.$baserate[$i].'</td></tr>';
                }
            }

            $sitename = get_data('manage_hotel', array('hotel_id'=> $hotel_id,'owner_id' =>$user_id))->row()->property_name;
            $channel_name = get_data('manage_channel',array('channel_id' => $data['channel_id']))->row()->channel_name;

            $sub = array(
                        '###CHANNEL###' => $channel_name,
                    );

            $aa=array(

                '###SITENAME###' => $sitename,

                '###CHANNEL###' => $channel_name,

                '###NAME###'=>$data['givenName'].' '.$data['middleName'].' '.$data['surname'],

                '###RESERID###'=>$data['booking_id'],

                '###STATUS###'=>$status,

                '###ADDRESS###' => $data['address'].','.$data['city'].','.$data['stateProv'],
                '###COUNTRY###' => $data['country'],

                '###CHECKIN###'=>date('M d, Y',strtotime(str_replace('/','-',$data['arrival']))),

                '###CHECKOUT###'=>date('M d, Y',strtotime(str_replace('/','-',$data['departure']))),

                '###CURRENCY###'=>$data['currency'],

                '###SUBTOTAL###'=>$data['amountAfterTaxes'],

                '###GRANDTOTAL###'=> $data['amountAfterTaxes'],

                '###CREATEDDATE###' => $data['created_time'],

                '###MOBILE### ' =>   $data['cityAreaCode'].$data['number'].$data['extension'],
                '###EMAIL###' => $data['Email'],

                '###COMMISION###' => 'None',

                '###PAYMENT###' => 'Card Payment',

                '###GUESTCOUNT###' => $data['adult'] + $data['child'],

                '###MEALPLAN###' => 'No',

                '###DAILYPRICE###' => $dailyprice,

                '###NOTES###' => $data['SpecialRequest'],

            );

            $email_content=strtr($email_content1,$aa);

			if($email_content!='')
			{
				$subject = strtr($email_subject1,$sub);

				$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();

				$this->mailsettings();

				$this->email->clear(TRUE);

				$this->email->from($admin_detail->email_id);

				$this->email->to($channel_data->reservation_email);

				$this->email->subject($subject);

				$this->email->message($email_content);

				$this->email->send();
			}
            //return true;
        }

    }
}