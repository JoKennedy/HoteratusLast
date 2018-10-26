<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class cronjobs extends Front_Controller 
{


	function CloseAllRoomType()
	{	

		$Allinfo=array();
		$CloseDate=date("d/m/Y");
		$ConectionsBooking = $this->db->query("select us.user_name,us.user_password,us.hotel_channel_id, mp.property_name, us.hotel_id, us.user_id, reservation_email as email
		from
		user_connect_channel as us
		LEFT join manage_hotel  mp on us.hotel_id = mp.hotel_id
		where us.channel_id = '2' and us.status ='enabled'")->result_array();

		$ConectionsExpedia = $this->db->query("select us.user_name,us.user_password,us.hotel_channel_id, mp.property_name, us.hotel_id, us.user_id, reservation_email as email, us.mode, us.test_url, us.live_url
		from
		user_connect_channel as us
		LEFT join manage_hotel  mp on us.hotel_id = mp.hotel_id
		where us.channel_id = '1' and us.status ='enabled'  ")->result_array();

		$ConectionsDespegar = $this->db->query("select us.user_name,us.user_password,us.hotel_channel_id, mp.property_name, us.hotel_id, us.user_id, reservation_email as email
		from
		user_connect_channel as us
		LEFT join manage_hotel  mp on us.hotel_id = mp.hotel_id
		where us.channel_id = '36' and us.status ='enabled'")->result_array();
		
		$ConectionsAirbnb = $this->db->query("select us.user_name,us.user_password,us.hotel_channel_id, mp.property_name, us.hotel_id, us.user_id, reservation_email as email
		from
		user_connect_channel as us
		LEFT join manage_hotel  mp on us.hotel_id = mp.hotel_id
		where us.channel_id = '9' and us.status ='enabled'")->result_array();

		if (count($ConectionsBooking) >0 )
		{

			foreach ($ConectionsBooking as  $value) {
				extract($value);

				$room_mapping = get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>2,'enabled'=>'enabled'))->result_array();


				foreach ($room_mapping as $Mapping => $Mappingvalue) 
	    		{

	    			if ($Mappingvalue["import_mapping_id"] !=0)
	    			{
	    				$data['stop_sell']='1';
	    				$data['availability']='0';
	    				$data['open_room']='0';
	    			

	    				$mp_details = get_data('import_mapping_BOOKING',array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=> "2",'import_mapping_id'=>$Mappingvalue["import_mapping_id"]))->row();

	    				$Allinfo[$user_id][$hotel_id]['Booking']['Nombre']=$property_name;
	    				$previosroom=(isset($Allinfo[$user_id][$hotel_id]['Booking']['RoomName'])?$Allinfo[$user_id][$hotel_id]['Booking']['RoomName'].',':'');
	    				$Allinfo[$user_id][$hotel_id]['Booking']['RoomName']=$previosroom.$mp_details->room_name.'-'.$mp_details->rate_name;
	    				$Allinfo[$user_id][$hotel_id]['Email']=$email;

						$xml_data='<?xml version="1.0" encoding="UTF-8"?>
						<request>
						<username>'.$user_name.'</username>
						<password>'.$user_password.'</password>
						<hotel_id>'.$hotel_channel_id.'</hotel_id>
						<room id="'.$mp_details->B_room_id.'">';
						$xml_data .="<date value1='".date("Y-m-d")."' >";	
						$xml_data .= '<rate id="'.$mp_details->B_rate_id.'"/>';
						$xml_data .= '<roomstosell>0</roomstosell><closed>0</closed> </date></room></request>';

						$URL = "https://supply-xml.booking.com/hotels/xml/availability?xml=";
						$ch = curl_init($URL);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);
						$mail_data = '<strong> Response </strong> <br>';
						$mail_data .= $output;

						mail('xml@hoteratus.com',"Day Close Booking".$property_name,$mail_data);
						
	    			}
					

				}


			}

		}

		if (count($ConectionsExpedia) >0 )
		{

			foreach ($ConectionsExpedia as  $value) {
				extract($value);


				if($mode == 0){
					$urls = explode(',', $test_url);
					foreach($urls as $url){
						$path = explode("~",$url);
						$exp[$path[0]] = $path[1];
					}
				}
				else if($mode == 1){
					$urls = explode(',', $live_url);
					foreach($urls as $url){
					$path = explode("~",$url);
					$exp[$path[0]] = $path[1];
				}
				} 

				$room_mapping = get_data(MAP,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>1,'enabled'=>'enabled'))->result_array();


				foreach ($room_mapping as $Mapping => $Mappingvalue) 
	    		{

	    			if ($Mappingvalue["import_mapping_id"] !=0)
	    			{
	    				$data['stop_sell']='1';
	    				$data['availability']='0';
	    				$data['open_room']='0';

	    				$mp_details = get_data('import_mapping',array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel'=> "1",'map_id'=>$Mappingvalue["import_mapping_id"]))->row();

	    				$Allinfo[$user_id][$hotel_id]['Expedia']['Nombre']=$property_name;
	    				$previosroom=(isset($Allinfo[$user_id][$hotel_id]['Booking']['RoomName'])?$Allinfo[$user_id][$hotel_id]['Booking']['RoomName'].',':'');
	    				$Allinfo[$user_id][$hotel_id]['Expedia']['RoomName']=$previosroom.$mp_details->roomtype_name.'-'.$mp_details->name;
	    				$Allinfo[$user_id][$hotel_id]['Email']=$email;
	    				

	    				if($Mappingvalue['explevel'] == "rate")
						{
							$xml = '<?xml version="1.0" encoding="UTF-8"?>
							<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							<Hotel id="'.$mp_details->hotel_channel_id.'"/>
							';

							$xml.=	'<AvailRateUpdate> <DateRange from="'.date("Y-m-d").'" to="'.date("Y-m-d").'" sun="true" mon="true" tue="true" wed="true" thu="true" fri="true" sat="true"/>';

							$xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
							$plan_id=($mp_details->rateAcquisitionType == "Derived"?$mp_details->rateplan_id:$mp_details->rate_type_id);
							$xml .= '<Inventory totalInventoryAvailable="0"/> <RatePlan id="'.$plan_id.'">';

								$xml .= ' </RatePlan> </RoomType> </AvailRateUpdate>';

								$xml .= " </AvailRateUpdateRQ>";

						

						}

						else
						{
							$xml = '<?xml version="1.0" encoding="UTF-8"?>
							<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							<Hotel id="'.$mp_details->hotel_channel_id.'"/>';


								$xml .='<AvailRateUpdate>
								<DateRange from="'.date("Y-m-d").'" to="'.date("Y-m-d").'" sun="true" mon="true" tue="true" wed="true" thu="true" fri="true" sat="true"/>';
								$xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';

								$xml .= '<Inventory totalInventoryAvailable="0"/>';

								$xml .= '</RoomType> </AvailRateUpdate>';

								$xml .= " </AvailRateUpdateRQ>";

						}

						
						
						$URL = trim($exp['urate_avail']);
						$ch = curl_init($URL);
						//curl_setopt($ch, CURLOPT_MUTE, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output = curl_exec($ch);

						$mail_data = '<strong> Response </strong> <br>';
						$mail_data .= $output;

						mail('xml@hoteratus.com',"Day Close Expedia".$property_name,$mail_data);
						
						
	    			}
					

				}


			}

		}

		


		foreach ($Allinfo as $usuarioid =>$usuario ) {
				
				foreach ($usuario  as $hotelid => $hotel) {
						

					$to      = $Allinfo[$usuarioid][$hotelid]['Email'];
					$subject = date("m-d-Y")." Day Close ".$Allinfo[$usuarioid ][$hotelid]['Booking']['Nombre'];
					$message = " <h2> Hotel Name: ". $Allinfo[$usuarioid ][$hotelid]['Booking']['Nombre']." </h2> <br> Rooms closed in Booking:".$Allinfo[$usuarioid][$hotelid]['Booking']['RoomName']." <br> Rooms closed in Expedia: ".(isset($Allinfo[$usuarioid][$hotelid]['Expedia']['RoomName'])?$Allinfo[$usuarioid][$hotelid]['Expedia']['RoomName']:'')."<br> Email:". $Allinfo[$usuarioid][$hotelid]['Email']."<br>";


					$headers = "From: info@hoteratus.com\r\n";
					$headers .= "Reply-To: support@hoteratus.com\r\n";
					$headers .= "CC: xml@hoteratus.com\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
							
	    				//mail($to, $subject, $message, $headers);
	    				mail("xml@hoteratus.com", $subject, $message, $headers);	
	    				
	    				
				}
			}

			echo "ALL ROOM CLOSE, ALL EMAIL SEND";

	}




}


?>