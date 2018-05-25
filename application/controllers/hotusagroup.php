<?php

class hotusagroup extends Front_Controller {


	private $code; 
	private $user; 
	private $pass; 
	private $url_p;
	private $headers;

	public function __construct()
    {
        
        parent::__construct();


			$this->code='HOT';
			$this->user='Hoteratus_Pro';
			$this->pass='hjhYR32A';
			$this->url_p='http://ws.link.hotelresb2b.com';

			$this->headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
	        $this->headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
	        $this->headers .= "CC: support <felix@hoteratus.com>\r\n";
	        $this->headers .= "BCC: datahernandez@gmail.com\r\n";
	        $this->headers .= "MIME-Version: 1.0\r\n";
	        $this->headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
		
    }

	function getRooms($channel_id='',$cha_name='HotusaGroup')
	{


			$code='HOT';
			$user='HOTERATUS_TEST';
			$pass='psg61oly07';
			$hoteid='644551';
			$username='TEST';
			$password='644551';
			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="http://ws.link.test.hotelresb2b.com/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<m:request_rooms xmlns:m="http://ws.link.test.hotelresb2b.com/hotel">          
				<m:hotel>             
				<m:hotel_code>'.$hoteid.'</m:hotel_code>             
				<m:hotel_user>'.$username.'</m:hotel_user>             
				<m:hotel_pwd>'.$password.'</m:hotel_pwd>          
				</m:hotel>          
				      
				</m:request_rooms>    
				</soapenv:Body> 
				</soapenv:Envelope> ';

			 $headers = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.test.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

				$url='http://ws.link.test.hotelresb2b.com/axis2/services/Link?wsdl';
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r($responseArray);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rooms']['ns2hotel_code'];
                	$rooms=@$responseArray['soapenvBody']['ns2response_rooms']['ns2rooms']['ns2room'];
                	foreach ($rooms as  $value) {
                		$roomcode=$value['ns2room_code'];
                		$roomname=$value['ns2room_name'];
                		$roomdescription=$value['ns2room_description'];
                		$roomadults=$value['ns2room_adults'];
                		$roomchilds=$value['ns2room_childs'];
                		$mealplan='';
                		$roommealvrplan=$value['ns2room_vr_mealplan'];
                		if(count($roommealvrplan)>0)
                		{
                			foreach ($roommealvrplan as  $value) {
                				$mealplan .= $value.',';
                			}
                		}

                		echo $mealplan;
                	}
                	
                }
				
	}

	function getRoomsVar($channel_id='',$hotel_id='',$user_id='')
	{


	        $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<m:request_rooms_var xmlns:m="'.$urlp.'/hotel">          
				<m:hotel>             
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>        
				</m:hotel>          
				      
				</m:request_rooms_var>    
				</soapenv:Body> 
				</soapenv:Envelope> ';

			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata=">>Request:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response".$response;
                $this->send_xml("xml@hoteratus.com","Import Rooms Var Hotusa Group",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error($user_id,$hotel_id,$channel_id,(string)$Errorarray,'Get Rooms GEN',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray;
                    return $meg;
                    

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rooms_var']['ns2hotel_code'];
                	$rooms=@$responseArray['soapenvBody']['ns2response_rooms_var']['ns2rooms']['ns2room'];
                	foreach ($rooms as  $value) {
                		$data['user_id']=$user_id;
                		$data['hotel_id']=$hotel_id;
                		$data['channel_id']=$channel_id;
                		$data['planning']='GEN';
                		$data['roomcode']=(string)$value['ns2room_code'];
                		$data['roomname']=(string)$value['ns2room_name'];
                		$data['roomdescription']=(string)$value['ns2room_description'];
                		$data['roomadults']=(string)$value['ns2room_adults'];
                		$data['roomchilds']=(string)$value['ns2room_childs'];
                		$roommealvrplan=$value['ns2room_vr_mealplans'];
                		if(count($roommealvrplan)>0)
                		{
                			foreach ($roommealvrplan as  $va) {


                				$data['vr_rate']=(string)$va['@attributes']['vr_rate'];

                				$mealplans =$va['ns2room_vr_mealplan']; 

                				if(count($mealplans)>0)
                				{
                					foreach ($mealplans as  $val) {
                						$data['mealplan']=(string)$val;

                						$available = get_data('import_mapping_HOTUSAGROUP',array('hotel_id'=>$hotel_id,'planning'=>$data['planning'],'channel_id'=>$channel_id,'vr_rate'=>$data['vr_rate'],'mealplan'=>$data['mealplan'],'roomcode'=>$data['roomcode']))->row_array();
                					
										if(count($available)==0)
										{	

																		
											$array_keys = array_keys($data);
											fetchColumn('import_mapping_HOTUSAGROUP',$array_keys);
											insert_data('import_mapping_HOTUSAGROUP',$data);
		                				}		
                					}
                					
                				}
                			}

                		}

                		
                		
                	}

                	$meg['result'] = '1';
                	return $meg;
                	
                }				
	}

	function getRoomsFit($channel_id='',$hotel_id='',$user_id='')
	{

			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<m:request_rooms_fit xmlns:m="'.$urlp.'/hotel">          
				<m:hotel>             
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>           
				</m:hotel>               
				</m:request_rooms_fit>    
				</soapenv:Body> 
				</soapenv:Envelope> ';

			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

			 	$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Import Rooms Fit Hotusa Group",$maildata);
                print_r( $responseArray );


                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rooms_fit']['ns2hotel_code'];
                	$rooms=@$responseArray['soapenvBody']['ns2response_rooms_fit']['ns2rooms']['ns2fit_room'];
                	foreach ($rooms as  $value) {
                		$roomcode=$value['ns2room_code'];
                		$roomname=$value['ns2room_name'];
                		$roomdescription=$value['ns2room_description'];
                		$roomadults=$value['ns2room_adults'];
                		$roomchilds=$value['ns2room_childs'];
                		$mealplan='';
                		$roommealvrplan=$value['ns2mealplans'];
                		if(count($roommealvrplan)>0)
                		{
                			foreach ($roommealvrplan as  $va) {
                				$mealplan='';
                				$vr_rate= $va['@attributes']['fit_rate'];
                				$mealplans =$va['ns2mealplan']; 

                				if(count($mealplans)>0)
                				{
                					foreach ($mealplans as  $val) {
                						$mealplan .= $val.',';
                					}
                					
                				}
                				echo $roomname.'<br>';
                				echo $vr_rate.'<br>';
                				echo $mealplan.'<br>'.'<br>'.'<br>' ;
                			}



                		}

                		
                	}
                	
                }				
	}

	function update_room_status($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<ns1:user_validation xmlns:ns1="'.$urlp.'/login">          
				<ns1:usr_code>'.$code.'</ns1:usr_code>          
				<ns1:usr_name>'.$user.'</ns1:usr_name>          
				<ns1:usr_pwd>'.$pass.'</ns1:usr_pwd>          
				<ns1:language>1</ns1:language>       
				</ns1:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<ns2:request_update_room_status xmlns:ns2="'.$urlp.'/hotel">          
				<ns2:hotel>             
				<ns2:hotel_code>'.$ch_details->hotel_channel_id.'</ns2:hotel_code>
				<ns2:hotel_user>'.$ch_details->user_name.'</ns2:hotel_user>
				<ns2:hotel_pwd>'.$ch_details->user_password.'</ns2:hotel_pwd>        
				</ns2:hotel>          
				<ns2:start_date>2018-03-03</ns2:start_date>          
				<ns2:end_date>2018-03-03</ns2:end_date>          
				<ns2:week_days>YYYYYYY</ns2:week_days>          
				<ns2:planning>GEN</ns2:planning>          
				<ns2:room_code>SJ</ns2:room_code>          
				<ns2:status>C</ns2:status>       
				</ns2:request_update_room_status>     
				</soapenv:Body> 
				</soapenv:Envelope> ';

					 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","update Room Status",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {

                	if(@$responseArray['soapenvBody']['ns2response_update']['ns2result'] =='OK' )
                	{
                		echo 'Update Correct';
                	}
           
                	
                }				
	}

	function getRoomsNego($channel_id='',$hotel_id='',$user_id='')
	{

		$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;


			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<m:request_rooms_nego xmlns:m="'.$urlp.'/hotel">          
				<m:hotel>             
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>          
				</m:hotel>          
				      
				</m:request_rooms_nego>    
				</soapenv:Body> 
				</soapenv:Envelope> ';

			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );
			 	$maildata=">>Request:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                 $this->send_xml("xml@hoteratus.com","Import Rooms Nego Hotusa Group",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rooms_nego']['ns2hotel_code'];
                	$rooms=@$responseArray['soapenvBody']['ns2response_rooms_nego']['ns2rooms']['ns2room'];
                	foreach ($rooms as  $value) {
                		$roomcode=$value['ns2room_code'];
                		$roomname=$value['ns2room_name'];
                		$roomdescription=$value['ns2room_description'];
                		$roomadults=$value['ns2room_adults'];
                		$roomchilds=$value['ns2room_childs'];
                		$mealplan='';
                		$roommealvrplan=$value['ns2mealplans'];
                		if(count($roommealvrplan)>0)
                		{
                			foreach ($roommealvrplan as  $va) {
                				$mealplan='';
                				$vr_rate= $va['@attributes']['nego_rate'];
                				$mealplans =$va['ns2mealplan']; 

                				if(count($mealplans)>0)
                				{
                					foreach ($mealplans as  $val) {
                						$mealplan .= $val.',';
                					}
                					
                				}
                				echo $roomname.'<br>';
                				echo $vr_rate.'<br>';
                				echo $mealplan.'<br>'.'<br>'.'<br>' ;
                			}



                		}

                		
                	}
                	
                }				
	}

	function getRates($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
				<m:request_rates xmlns:m="'.$urlp.'/hotel">          
				<m:hotel>             
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>               
				</m:hotel> 
				<m:planning>FIT</m:planning>         			      
				</m:request_rates>    
				</soapenv:Body> 
				</soapenv:Envelope> ';

			 	$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Import Rate",$maildata);
                die;
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rates']['ns2hotel_code'];
                	$rates=@$responseArray['soapenvBody']['ns2response_rates']['ns2rates']['ns2rate'];
                	foreach ($rates as  $value) {
                		$ratevariable=$value['@attributes']['variable'];
                		$ratecode=$value['ns2rate_code'];
                		$ratename=$value['ns2rate_name'];
                		
                
                		echo $ratevariable.'<br>'.$ratecode.'<br>'.$ratename.'<br>';
                	}
                	
                }				
	}
	function update_price($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{


			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>1</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>
				<m:request_update_price xmlns:m="'.$urlp.'/hotel">
					<m:hotel>
					<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
					<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
					<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd> 
					</m:hotel>
					<m:start_date>2018-12-01</m:start_date>
					<m:end_date>2018-12-31</m:end_date>
					<m:rate_code>VB</m:rate_code>
					<m:planning>GEN</m:planning>
					<m:week_days>NNNNNYY</m:week_days>
					<m:price>
					<m:room_code>DB</m:room_code>
					<m:mealplan_code>RO</m:mealplan_code>
					<m:price>125.00</m:price>
					<m:currency>EUR</m:currency>
					</m:price>


				</m:request_update_price>


				</soapenv:Body>
				</soapenv:Envelope> ';

		
			 	$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Update Price Rate",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {

                	if(@$responseArray['soapenvBody']['ns2response_update']['ns2result'] =='OK' )
                	{
                		echo 'Update Correct';
                	}
           
                	
                }				
	}
	function getMealPlans($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{


		  $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
				<m:usr_code>'.$code.'</m:usr_code>          
				<m:usr_name>'.$user.'</m:usr_name>          
				<m:usr_pwd>'.$pass.'</m:usr_pwd>          
				<m:language>2</m:language>       
				</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>
				<m:request_mealplans xmlns:m="'.$urlp.'/hotel">
				</m:request_mealplans>
				</soapenv:Body>
				</soapenv:Envelope> ';

			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
			 	$maildata="Request>>:".$xml_post_string;

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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response>>:".$response ;
                $this->send_xml("xml@hoteratus.com","Import Meals Plan Hotusa Group",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error($user_id,$hotel_id,insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	
                	$mealplan=@$responseArray['soapenvBody']['ns2response_mealplans']['ns2mealplans']['ns2mealplan'];
                	foreach ($mealplan as  $value) {
                		$mealplancode=$value['ns2mealplan_code'];
                		$mealplanname=$value['ns2mealplan_name'];
                		$mealplandescription='';//$value['ns2mealplan_description'];              		         
 	             		echo $mealplancode.'<br>'.$mealplanname.'<br>'.$mealplandescription.'<br>';
                	}
                	
                }				
	}
	function update_generic_rate($channel_id='',$cha_name='HotusaGroup')
	{


			$code='HOT';
			$user='HOTERATUS_TEST';
			$pass='psg61oly07';
			$hoteid='644551';
			$username='TEST';
			$password='644551';
			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<ns1:user_validation xmlns:ns1="http://ws.link.test.hotelresb2b.com/login">          
			<ns1:usr_code>'.$code.'</ns1:usr_code>          
			<ns1:usr_name>'.$user.'</ns1:usr_name>          
			<ns1:usr_pwd>'.$pass.'</ns1:usr_pwd>          
			<ns1:language>1</ns1:language>       
			</ns1:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<ns2:request_update_generic_rate xmlns:ns2="http://ws.link.test.hotelresb2b.com/hotel">
			<ns2:hotel>
			<ns2:hotel_code>'.$hoteid.'</ns2:hotel_code>
			<ns2:hotel_user>'.$username.'</ns2:hotel_user>
			<ns2:hotel_pwd>'.$password.'</ns2:hotel_pwd>
			</ns2:hotel>
			<ns2:start_date>2017-12-05</ns2:start_date>
			<ns2:end_date>2017-12-06</ns2:end_date>
			<ns2:week_days>YYYYYYY</ns2:week_days>
			<ns2:rate_code>VR</ns2:rate_code>
			</ns2:request_update_generic_rate>
			</soapenv:Body>
			</soapenv:Envelope> ';

			 $headers = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.test.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

				$url='http://ws.link.test.hotelresb2b.com/axis2/services/Link?wsdl';
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {

                	if(@$responseArray['soapenvBody']['ns2response_update']['ns2result'] =='OK' )
                	{
                		echo 'Update Correct';
                	}
           			else
           			{
           				echo $responseArray['soapenvBody']['ns2response_update']['ns2descriptions']['ns2description'];
           			}
                	
                }				
	}
	function getAvailability($channel_id='',$cha_name='HotusaGroup')
	{


			$code='HOT';
			$user='HOTERATUS_TEST';
			$pass='psg61oly07';
			$hoteid='644551';
			$username='TEST';
			$password='644551';
			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="http://ws.link.test.hotelresb2b.com/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<m:request_availability xmlns:m="http://ws.link.test.hotelresb2b.com/hotel">
			<m:hotel>
			<m:hotel_code>'.$hoteid.'</m:hotel_code>
			<m:hotel_user>'.$username.'</m:hotel_user>
			<m:hotel_pwd>'.$password.'</m:hotel_pwd>
			</m:hotel>
			<m:start_date>2017-12-14</m:start_date>
			<m:end_date>2017-12-15</m:end_date>
			<m:planning>GEN</m:planning>
			<m:all_rates />
			</m:request_availability>
			</soapenv:Body>
			</soapenv:Envelope> ';

			 $headers = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.test.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

				$url='http://ws.link.test.hotelresb2b.com/axis2/services/Link?wsdl';
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
               	print_r( $responseArray );
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelCode=@$responseArray['soapenvBody']['ns2response_availability']['ns2hotel_code'];
                	$Availability=@$responseArray['soapenvBody']['ns2response_availability']['ns2availability'];
                	foreach ($Availability as  $value) {
                		$date=$value['@attributes']['date'];
           		        $avail= $value['ns2avail'];


           		        foreach ($avail as $val) {
           		        	$attributes=$val['@attributes'];
           		        	$ratecode=$attributes['rate_code'];
           		        	$seld_type=$attributes['seld_type'];
           		        	$avail_num=$attributes['avail_num'];
           		        	$extra_avail_num=$attributes['extra_avail_num'];
           		        	$modify=$attributes['modify'];
           		        	$planning=$attributes['planning'];
           		        	$avail_sequence=$attributes['avail_sequence'];
           		        	$min_days=$attributes['min_days'];
           		        	$avail_type=$attributes['avail_type'];
           		        	$room=$val['ns2room'];

           		        	
           		        	if(@$room['@attributes']['code'] !='')
           		        	{
           		        		
           		        		$roomcode=$room['@attributes']['code'] ;
           		        		$roomclose=@$room['@attributes']['closed'] ;
           		        		foreach ($room['ns2mealplan'] as $value) {
           		        			$mealplancode= $value['@attributes']['code'];
           		        			$mealplanprice= $value['@attributes']['price'];
           		        			$mealplancurrency= $value['@attributes']['currency'];
           		        		}
           		        		
           		        	}
           		        	else
           		        	{
           		        		foreach ($room as $value) 
           		        		{
           		        			$roomcode=$value['@attributes']['code'] ;
	           		        		$roomclose=@$value['@attributes']['closed'] ;
	           		        		foreach ($value['ns2mealplan'] as $valu) {
	           		        			$mealplancode= $valu['@attributes']['code'];
	           		        			$mealplanprice= $valu['@attributes']['price'];
	           		        			$mealplancurrency= $valu['@attributes']['currency'];
	           		        			
	           		        		}
           		        			

           		        		}

           		        	}

           		       

           		        }
 	             		
                	}
                	
                }				
	}
	function getInventory($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{

    	    $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;


			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<m:request_inventory xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd> 
			</m:hotel>
			<m:start_date>2018-12-01</m:start_date>
			<m:end_date>2018-12-31</m:end_date>

			<m:all_rates />
			</m:request_inventory>
			</soapenv:Body>
			</soapenv:Envelope> ';

			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
               print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Import Inventory",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelCode=@$responseArray['soapenvBody']['ns2response_availability']['ns2hotel_code'];
                	$Availability=@$responseArray['soapenvBody']['ns2response_inventory']['ns2availability'];
                	foreach ($Availability['ns2avail'] as  $value) {
                	 	echo $value['ns2room']['@attributes']['code'].'-'.@$value['ns2room']['@attributes']['closed'].',';
                	 } 

                	/*foreach ($Availability as  $value) {
                		$date=$value['@attributes']['date'];
           		        $avail= $value['ns2avail'];
           		        $restrictions=$value['ns2restrictions']['ns2restriction'];
           		        foreach ($restrictions as  $val) {
           		        	$rate=$val['@attributes']['rate'];
           		        	$room=$val['@attributes']['room'];
           		        	$mealplan=$val['@attributes']['mealplan'];
           		        	$closed=$val['@attributes']['closed'];
           		        	$mindays=@$val['ns2min_days'];
           		        	$maxdays=@$val['ns2max_days'];
           		        	$closedarrival=$val['ns2closed_to_arrival'];
           		        	$closeddeparture=$val['ns2closed_to_departure'];

           		        }

           		        foreach ($avail as $val) {
           		        	$attributes=$val['@attributes'];
           		        	$ratecode=$attributes['rate_code'];
           		        	$seld_type=$attributes['seld_type'];
           		        	$avail_num=$attributes['avail_num'];
           		        	$extra_avail_num=$attributes['extra_avail_num'];
           		        	$modify=$attributes['modify'];
           		        	$planning=$attributes['planning'];
           		        	$avail_sequence=$attributes['avail_sequence'];
           		        	$min_days=@$attributes['master_room'];
           		        	$avail_type=$attributes['avail_type'];
           		        	$room=$val['ns2room'];

           		        	
           		        	if(@$room['@attributes']['code'] !='')
           		        	{
           		        		
           		        		$roomcode=$room['@attributes']['code'] ;
           		        		$roomclose=@$room['@attributes']['closed'] ;
           		        		foreach ($room['ns2mealplan'] as $value) {
           		        			$mealplancode= $value['@attributes']['code'];
           		        			$mealplanprice= $value['@attributes']['price'];
           		        			$mealplancurrency= $value['@attributes']['currency'];
           		        		}
           		        		
           		        	}
           		        	else
           		        	{
           		        		foreach ($room as $value) 
           		        		{
           		        			$roomcode=$value['@attributes']['code'] ;
	           		        		$roomclose=@$value['@attributes']['closed'] ;
	           		        		foreach ($value['ns2mealplan'] as $valu) {
	           		        			$mealplancode= $valu['@attributes']['code'];
	           		        			$mealplanprice= $valu['@attributes']['price'];
	           		        			$mealplancurrency= $valu['@attributes']['currency'];
	           		        			
	           		        		}
           		        			

           		        		}

           		        	}

           		       

           		        }
 	             		
                	}
                	*/
                }				
	}
	
	function updateAvailability($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
 			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
			<soapenv:Header>
			<m:user_validation xmlns:m="'.$urlp.'/login">
			<m:usr_code>'.$code.'</m:usr_code>
			<m:usr_name>'.$user.'</m:usr_name>
			<m:usr_pwd>'.$pass.'</m:usr_pwd>
			<m:language>1</m:language>
			</m:user_validation>
			</soapenv:Header>
			<soapenv:Body>
			<m:request_update_availability xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd> 
			</m:hotel>
			<m:start_date>2018-07-01</m:start_date>
			<m:end_date>2018-07-31</m:end_date>
			<m:rate_code>F</m:rate_code>
			<m:planning>FIT</m:planning>
			<m:week_days>YYYYYYY</m:week_days>
			<m:avail_sequence>DB</m:avail_sequence>
			<m:seld_type>C</m:seld_type>
			<m:avail>3</m:avail>
			<m:release>5</m:release>
			</m:request_update_availability>
			</soapenv:Body>
			</soapenv:Envelope> ';

			$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:ws.link.hotelresb2b.com",
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Update Availability",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {

                	if(@$responseArray['soapenvBody']['ns2response_update']['ns2result'] =='OK' )
                	{
                		echo 'Update Correct';
                	}
           			else
           			{
           				echo $responseArray['soapenvBody']['ns2response_update']['ns2descriptions']['ns2description'];
           			}
                	
                }				
	}

	function updateMealplanStatus($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{

			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
			<soapenv:Header>
			<m:user_validation xmlns:m="'.$urlp.'/login">
			<m:usr_code>'.$code.'</m:usr_code>
			<m:usr_name>'.$user.'</m:usr_name>
			<m:usr_pwd>'.$pass.'</m:usr_pwd>
			<m:language>1</m:language>
			</m:user_validation>
			</soapenv:Header>
			<soapenv:Body>
			<m:request_update_mealplan_status xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
				<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
				<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
				<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd> 
			</m:hotel>
			<m:start_date>2018-12-09</m:start_date>
			<m:end_date>2018-12-09</m:end_date>
			<m:week_days>YYYYYYY</m:week_days>
			<m:mealplans_status>
			<m:apply_status status="O">
			<m:rate>VB</m:rate>
			<m:room>DB</m:room>
			<m:mealplan>BB</m:mealplan>
			</m:apply_status>
			</m:mealplans_status>

			</m:request_update_mealplan_status >
			</soapenv:Body>
			</soapenv:Envelope> ';


			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
			 	$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );

                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","update Meal Plan Status",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {

                	if(@$responseArray['soapenvBody']['ns2response_update']['ns2result'] =='OK' )
                	{
                		echo 'Update Correct';
                	}
           			else
           			{
           				echo $responseArray['soapenvBody']['ns2response_update']['ns2descriptions']['ns2description'];
           			}
                	
                }				
	}
	function getBooks($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
			

	        $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			
	
			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<m:request_books xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
			<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
			<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
			<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>
			</m:hotel>
			<m:start_date>2017-12-15</m:start_date>
			<m:end_date>2017-12-15</m:end_date>
			</m:request_books>
			</soapenv:Body>
			</soapenv:Envelope> ';


			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata="Request >>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response>>:".$response;
               	$this->send_xml("xml@hoteratus.com","Import Reservation Hotusa Group",$maildata);
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error($user_id,$hotel_id,$channel_id,(string)$Errorarray,'Get Reservations',date('m/d/Y h:i:s a', time()));
                    $data['Error']	=(string)$Errorarray;
                    return $data;
                }
                else
                {
                	$reservations=@$responseArray['soapenvBody']['ns2response_books']['ns2book'];

                	if(count($reservations)>0)
                	{

                		foreach ($reservations as $value) 
                		{	
	                		if (@$reservations['ns2hotel_code']=='')//VArias reservaciones
	                		{
	                			$data['user_id']=$hotel_id;
			                	$data['hotel_id']=$user_id;
			                	$data['channel_id']=$channel_id;
			                	$data['hotel_code']=(string)$value['ns2hotel_code'];
			                	$data['status']=(string)$value['ns2status'];
			                	$data['start_date']=(string)$value['ns2start_date'];
			                	$data['end_date']=(string)$value['ns2end_date'];
			                	$data['create_date']=(string)$value['ns2create_date'];
			                	$data['create_datetime']=(string)$value['ns2create_dateTime'];
			                	$data['modification_date']=@(string)$value['ns2modification_date'];
			                	$data['book_ref']=(string)$value['ns2book_ref'];
			                	$data['external_book_ref']=(string)@$value['ns2external_book_ref'];
			                	$data['client_name']=(string)$value['ns2client_name'];
			                	$data['price']=(string)$value['ns2price'];
			                	$data['price_excluding_vat']=(string)$value['ns2price_excluding_vat'];
			                	$data['vat_tax_percentage']=(string)$value['ns2vat_tax_percentage'];
			                	
			                	$comments=$value['ns2comment'];
			                	$data['comments']='';

			                	
		                		
		                		$data['payment_type']=(string)@$value['ns2payment_type'];
		                		$data['target_num']=(string)@$value['ns2target_num'];
		                		$data['target_user_name']=(string)@$value['ns2target_user_name'];
		                		$data['target_expiration']=(string)@$value['ns2target_expiration'];
		                		$data['target_type']=(string)@$value['ns2target_type'];
		                		$data['cvv']=(string)@$value['ns2cvv'];
		                		$data['cancel_date']=(string)@$value['ns2cancel_date'];
		                		$data['cancel_dateTime']=(string)@$value['ns2cancel_dateTime'];
		                		$data['cancel_ref']=(string)@$value['ns2cancel_ref'];
		                		$data['cancel_reason']=(string)@$value['ns2cancel_reason'];

		                		$days='';
		                		$hours='';
		                		$nights='';
		                		$percentage='';

		                		if(@$value['ns2cancel_policies']['ns2cancel_policy']['ns2days']!='')
		                		{
		                			$days=(string)$value['ns2cancel_policies']['ns2cancel_policy']['ns2days'];
		                			$hours=(string)$value['ns2cancel_policies']['ns2cancel_policy']['ns2hours'];
		                			$nights=(string)$value['ns2cancel_policies']['ns2cancel_policy']['ns2nights'];
		                			$percentage=(string)$value['ns2cancel_policies']['ns2cancel_policy']['ns2percentage'];
		                		}
		                		else
		                		{
		                			foreach ($value['ns2cancel_policies']['ns2cancel_policy'] as $key ) {
		                				$days.=(string)$key['ns2days'].',';
			                			$hours.=(string)$key['ns2hours'].',';
			                			$nights.=(string)$key['ns2nights'].',';
			                			$percentage.=(string)$key['ns2percentage'].',';
		                			}
		                		}

		          	                $data['days']=$days;
		          	                $data['hours']=$hours;
		          	                $data['nights']=$nights;
		          	                $data['percentage']=$percentage;
		                	}
		                	else//Solo una reservacion
		                	{
		                		$data['user_id']=$hotel_id;
			                	$data['hotel_id']=$user_id;
			                	$data['channel_id']=$channel_id;
			                	$data['hotel_code']=(string)$reservations['ns2hotel_code'];
			                	$data['status']=(string)$reservations['ns2status'];
			                	$data['start_date']=(string)$reservations['ns2start_date'];
			                	$data['end_date']=(string)$reservations['ns2end_date'];
			                	$data['create_date']=(string)$reservations['ns2create_date'];
			                	$data['create_datetime']=(string)$reservations['ns2create_dateTime'];
			                	$data['modification_date']=@(string)$reservations['ns2modification_date'];
			                	$data['book_ref']=(string)$reservations['ns2book_ref'];
			                	$data['external_book_ref']=(string)@$reservations['ns2external_book_ref'];
			                	$data['client_name']=(string)$reservations['ns2client_name'];
			                	$data['price']=(string)$reservations['ns2price'];
			                	$data['price_excluding_vat']=(string)$reservations['ns2price_excluding_vat'];
			                	$data['vat_tax_percentage']=(string)$reservations['ns2vat_tax_percentage'];
			                	
			                	$comments=$reservations['ns2comment'];
			                	$data['comments']='';

			                	
		                		
		                		$data['payment_type']=(string)@$reservations['ns2payment_type'];
		                		$data['target_num']=(string)@$reservations['ns2target_num'];
		                		$data['target_user_name']=(string)@$reservations['ns2target_user_name'];
		                		$data['target_expiration']=(string)@$reservations['ns2target_expiration'];
		                		$data['target_type']=(string)@$reservations['ns2target_type'];
		                		$data['cvv']=(string)@$reservations['ns2cvv'];
		                		$data['cancel_date']=(string)@$reservations['ns2cancel_date'];
		                		$data['cancel_dateTime']=(string)@$reservations['ns2cancel_dateTime'];
		                		$data['cancel_ref']=(string)@$reservations['ns2cancel_ref'];
		                		$data['cancel_reason']=(string)@$reservations['ns2cancel_reason'];

		                		$days='';
		                		$hours='';
		                		$nights='';
		                		$percentage='';	

		                		if(@$reservations['ns2cancel_policies']['ns2cancel_policy']['ns2days']!='')
		                		{
		                			$days=(string)$reservations['ns2cancel_policies']['ns2cancel_policy']['ns2days'];
		                			$hours=(string)$reservations['ns2cancel_policies']['ns2cancel_policy']['ns2hours'];
		                			$nights=(string)$reservations['ns2cancel_policies']['ns2cancel_policy']['ns2nights'];
		                			$percentage=(string)$reservations['ns2cancel_policies']['ns2cancel_policy']['ns2percentage'];
		                		}
		                		else
		                		{
		                			foreach ($reservations['ns2cancel_policies']['ns2cancel_policy'] as $key ) {
		                				$days.=(string)$key['ns2days'].',';
			                			$hours.=(string)$key['ns2hours'].',';
			                			$nights.=(string)$key['ns2nights'].',';
			                			$percentage.=(string)$key['ns2percentage'].',';
		                			}
		                		}

		          	                $data['days']=$days;
		          	                $data['hours']=$hours;
		          	                $data['nights']=$nights;
		          	                $data['percentage']=$percentage;
		                	}

	          	                	
	          	                	
	          	                $available = get_data('import_reservation_HOTUSAGROUP',array('hotel_id'=>$hotel_id,'book_ref'=>$data['book_ref'],'channel_id'=>$channel_id))->row_array();

								if(count($available)==0)
								{	

																
									$array_keys = array_keys($data);
									fetchColumn('import_reservation_HOTUSAGROUP',$array_keys);
									insert_data('import_reservation_HOTUSAGROUP',$data);
									if($data['vat_tax_percentage']>0)
									{
										$tax['hotel_id']=$data['hotel_id'];
										$tax['user_id']=$data['user_id'];
										$tax['reservation_id']=$data['book_ref'];
										$tax['channel_id']=$data['channel_id'];
										$tax['tax_name']='Tax';
										$tax['tax_included']='0';
										$tax['tax_price']=$data['vat_tax_percentage'];

										$array_keys = array_keys($tax);
										fetchColumn(R_TAX,$array_keys);
										insert_data(R_TAX,$tax);
									}
									$id =$this->db->insert_id();
									if (@$value['ns2hotel_code']!='')
									{
										$rooms = $value['ns2rooms']['ns2days'];
									}
									else
									{
										$rooms = $reservations['ns2rooms']['ns2days'];
									}
		                
				                	foreach ($rooms as $val) {
				                		//print_r($val);
				                		if(@$val['@attributes']['day']!='')//mas de uno
				                		{
				                			
				                			if(@$val['ns2room']['ns2habCode']!='')
				                			{	
				                				$detail['book_ref']=$data['book_ref'];
				                				$detail['user_id']=$hotel_id;
				                				$detail['hotel_id']=$user_id;
				                				$detail['channel_id']=$channel_id;
						                		$detail['days']=(string)$val['@attributes']['day'];
							                	$detail['habCode']=(string)$val['ns2room']['ns2habCode'];
							                	$detail['room_adults']=(string)$val['ns2room']['ns2room_adults'];
							                	$detail['room_childs']=(string)$val['ns2room']['ns2room_childs'];
							                	$detail['num_rooms']=(string)$val['ns2room']['ns2num_rooms'];
							                	$detail['mealplan_code']=(string)$val['ns2room']['ns2mealplan_code'];
							                	$detail['roomprice']=(string)$val['ns2room']['ns2price'];
							                	$detail['roomcurrency']=(string)$val['ns2room']['ns2currency'];
							                	$detail['rate_code']=(string)$val['ns2room']['ns2rate_code'];
							                	$detail['remarks']=(string)@$val['ns2room']['ns2remarks'];
							                	$detail['planning']=(string)$val['ns2room']['ns2planning'];
							                	$detail['room_discounttype']=(string)@$val['ns2room']['ns2room_discount']['@attributes']['type'];
							                	$detail['room_discountid']=(string)@$val['ns2room']['ns2room_discount']['@attributes']['id'];

							                	
				                			}
				                			else
				                			{
				                				foreach ($val['ns2room'] as  $valu) {
				                						$detail['book_ref']=$data['book_ref'];
				                						$detail['user_id']=$hotel_id;
				                						$detail['hotel_id']=$user_id;
				                						$detail['channel_id']=$channel_id;
								                		$detail['days']=(string)$val['@attributes']['day'];
									                	$detail['habCode']=(string)$valu['ns2habCode'];
									                	$detail['room_adults']=(string)$valu['ns2room_adults'];
									                	$detail['room_childs']=(string)$valu['ns2room_childs'];
									                	$detail['num_rooms']=(string)$valu['ns2num_rooms'];
									                	$detail['mealplan_code']=(string)$valu['ns2mealplan_code'];
									                	$detail['roomprice']=(string)$valu['ns2price'];
									                	$detail['roomcurrency']=(string)$valu['ns2currency'];
									                	$detail['rate_code']=(string)$valu['ns2rate_code'];
									                	$detail['remarks']=(string)@$valu['ns2remarks'];
									                	$detail['planning']=(string)$valu['ns2planning'];
									                	$detail['room_discounttype']=(string)@$valu['ns2room_discount']['@attributes']['type'];
									                	$detail['room_discountid']=(string)@$valu['ns2room_discount']['@attributes']['id'];
									                	
					                				}
				                			}
				                		}
				                		else
				                		{
				                			if (@$val['day']!='')
				                			{	$detail['book_ref']=$data['book_ref'];
				                				$detail['user_id']=$hotel_id;
				                				$detail['hotel_id']=$user_id;
				                				$detail['channel_id']=$channel_id;
						                		$detail['days']=(string)$val['day'];
							                	$detail['habCode']=(string)$rooms['ns2room']['ns2habCode'];
							                	$detail['room_adults']=(string)$rooms['ns2room']['ns2room_adults'];
							                	$detail['room_childs']=(string)$rooms['ns2room']['ns2room_childs'];
							                	$detail['num_rooms']=(string)$rooms['ns2room']['ns2num_rooms'];
							                	$detail['mealplan_code']=(string)$rooms['ns2room']['ns2mealplan_code'];
							                	$detail['roomprice']=(string)$rooms['ns2room']['ns2price'];
							                	$detail['roomcurrency']=(string)$rooms['ns2room']['ns2currency'];
							                	$detail['rate_code']=(string)$rooms['ns2room']['ns2rate_code'];
							                	$detail['remarks']=(string)@$rooms['ns2room']['ns2remarks'];
							                	$detail['planning']=(string)$rooms['ns2room']['ns2planning'];
							                	$detail['room_discounttype']=(string)@$rooms['ns2room']['ns2room_discount']['@attributes']['type'];
							                	$detail['room_discountid']=(string)@$rooms['ns2room']['ns2room_discount']['@attributes']['id'];
							                	
				                			}
				                			
				                		}

					                	$array_keys = array_keys($detail);
										fetchColumn('import_reservation_HOTUSAGROUP_ROOMS',$array_keys);
										insert_data('import_reservation_HOTUSAGROUP_ROOMS',$detail);



				                	}

				                	if ($data['status']=="C" && $data['cancel_date'] !='' )
									{
										$history = array('channel_id'=>$channel_id,'reservation_id'=>$id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$data['cancel_date'] ,'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));
		    							$res = $this->db->insert('new_history',$history);
									}


								}
								else
								{	
									$id=$available['import_reserv_id'];

									if($data['modification_date']!=$available['modification_date'])
									{


										$array_keys = array_keys($data);
										fetchColumn('import_reservation_HOTUSAGROUP',$array_keys);
										update_data('import_reservation_HOTUSAGROUP',$data,array('hotel_id'=>$hotel_id,'book_ref'=>$data['book_ref'],'channel_id'=>$channel_id));
										

										$this->db->query("delete from import_reservation_HOTUSAGROUP_ROOMS where book_ref =".$data['book_ref']);
										$rooms = $value['ns2rooms']['ns2days'];
			                
					                	foreach ($rooms as $val) {
					                		//print_r($val);
					                		if(@$val['@attributes']['day']!='')//mas de uno
					                		{
					                			
					                			if(@$val['ns2room']['ns2habCode']!='')
					                			{	
					                				$detail['book_ref']=$data['book_ref'];
					                				$detail['user_id']=$hotel_id;
					                				$detail['hotel_id']=$user_id;
					                				$detail['channel_id']=$channel_id;
							                		$detail['days']=(string)$val['@attributes']['day'];
								                	$detail['habCode']=(string)$val['ns2room']['ns2habCode'];
								                	$detail['room_adults']=(string)$val['ns2room']['ns2room_adults'];
								                	$detail['room_childs']=(string)$val['ns2room']['ns2room_childs'];
								                	$detail['num_rooms']=(string)$val['ns2room']['ns2num_rooms'];
								                	$detail['mealplan_code']=(string)$val['ns2room']['ns2mealplan_code'];
								                	$detail['roomprice']=(string)$val['ns2room']['ns2price'];
								                	$detail['roomcurrency']=(string)$val['ns2room']['ns2currency'];
								                	$detail['rate_code']=(string)$val['ns2room']['ns2rate_code'];
								                	$detail['remarks']=(string)@$val['ns2room']['ns2remarks'];
								                	$detail['planning']=(string)$val['ns2room']['ns2planning'];
								                	$detail['room_discounttype']=(string)@$val['ns2room']['ns2room_discount']['@attributes']['type'];
								                	$detail['room_discountid']=(string)@$val['ns2room']['ns2room_discount']['@attributes']['id'];

								                	
					                			}
					                			else
					                			{
					                				foreach ($val['ns2room'] as  $valu) {
					                						$detail['book_ref']=$data['book_ref'];
					                						$detail['user_id']=$hotel_id;
					                						$detail['hotel_id']=$user_id;
					                						$detail['channel_id']=$channel_id;
									                		$detail['days']=(string)$val['@attributes']['day'];
										                	$detail['habCode']=(string)$valu['ns2habCode'];
										                	$detail['room_adults']=(string)$valu['ns2room_adults'];
										                	$detail['room_childs']=(string)$valu['ns2room_childs'];
										                	$detail['num_rooms']=(string)$valu['ns2num_rooms'];
										                	$detail['mealplan_code']=(string)$valu['ns2mealplan_code'];
										                	$detail['roomprice']=(string)$valu['ns2price'];
										                	$detail['roomcurrency']=(string)$valu['ns2currency'];
										                	$detail['rate_code']=(string)$valu['ns2rate_code'];
										                	$detail['remarks']=(string)@$valu['ns2remarks'];
										                	$detail['planning']=(string)$valu['ns2planning'];
										                	$detail['room_discounttype']=(string)@$valu['ns2room_discount']['@attributes']['type'];
										                	$detail['room_discountid']=(string)@$valu['ns2room_discount']['@attributes']['id'];
										                	
						                				}
					                			}
					                		}
					                		else
					                		{
					                			if (@$val['day']!='')
					                			{	$detail['book_ref']=$data['book_ref'];
					                				$detail['user_id']=$hotel_id;
					                				$detail['hotel_id']=$user_id;
					                				$detail['channel_id']=$channel_id;
							                		$detail['days']=(string)$val['day'];
								                	$detail['habCode']=(string)$rooms['ns2room']['ns2habCode'];
								                	$detail['room_adults']=(string)$rooms['ns2room']['ns2room_adults'];
								                	$detail['room_childs']=(string)$rooms['ns2room']['ns2room_childs'];
								                	$detail['num_rooms']=(string)$rooms['ns2room']['ns2num_rooms'];
								                	$detail['mealplan_code']=(string)$rooms['ns2room']['ns2mealplan_code'];
								                	$detail['roomprice']=(string)$rooms['ns2room']['ns2price'];
								                	$detail['roomcurrency']=(string)$rooms['ns2room']['ns2currency'];
								                	$detail['rate_code']=(string)$rooms['ns2room']['ns2rate_code'];
								                	$detail['remarks']=(string)@$rooms['ns2room']['ns2remarks'];
								                	$detail['planning']=(string)$rooms['ns2room']['ns2planning'];
								                	$detail['room_discounttype']=(string)@$rooms['ns2room']['ns2room_discount']['@attributes']['type'];
								                	$detail['room_discountid']=(string)@$rooms['ns2room']['ns2room_discount']['@attributes']['id'];
								                	
					                			}
					                			
					                		}
					                		$array_keys = array_keys($detail);
											fetchColumn('import_reservation_HOTUSAGROUP_ROOMS',$array_keys);
											insert_data('import_reservation_HOTUSAGROUP_ROOMS',$detail);

					                	}

					                	if ($data['status']=="C" && $data['cancel_date'] !='' )
										{
											$history = array('channel_id'=>$channel_id,'reservation_id'=>$id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$data['cancel_date'] ,'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));
			    							$res = $this->db->insert('new_history',$history);
										}
									}
									else if ($data['status']=="C" && $data['cancel_date'] !=$available['cancel_date'] )
									{
											$history = array('channel_id'=>$channel_id,'reservation_id'=>$id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$data['cancel_date'] ,'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));
			    							$res = $this->db->insert('new_history',$history);
									}

								}
                		}

                		$data['succes']='Insert';
                		return $data;
                	}
                	
                		$data['Error']='There is no reservation to import ';
                		return $data;
                	
                }				
	}
	function maptochannel($channel_id,$property_id)
    {
        require_once(APPPATH.'models/hotusagroup_model.php'); 
        $hotusagroup_model         =   new hotusagroup_model();
        $data['available']      =   get_data('import_mapping_HOTUSAGROUP',array('hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
        $data['mapping_values'] =   get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
        $data['hotusagroup']   =    $hotusagroup_model->get_mapping(insep_decode($channel_id),'update');
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        return $data;
    }

    function send_xml($To,$Subject,$data)
    {
    	mail($To, $Subject,$data, $this->headers);
    }

    function getCancelPolicy($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
    {
    	$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

		$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<m:request_cancel_policy xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
			<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
			<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
			<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>
			</m:hotel>
			<m:year>2017</m:year>
			</m:request_cancel_policy>
			</soapenv:Body>
			</soapenv:Envelope> ';


			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
			 	$maildata="Request>>:".$xml_post_string;

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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response>>:".$response ;
                $this->send_xml("xml@hoteratus.com","Import Cancel Policity",$maildata);
    }
     function getRestrictions($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
    {
    	$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

		$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>    
			<soapenv:Body>
			<m:request_restrictions xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
			<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
			<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
			<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>
			</m:hotel>
			<m:start_date>2018-11-15</m:start_date>
			<m:end_date>2018-11-15</m:end_date>
			</m:request_restrictions>
			</soapenv:Body>
			</soapenv:Envelope> ';


			 $header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
			 	$maildata="Request>>:".$xml_post_string;

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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response>>:".$response ;
                $this->send_xml("xml@hoteratus.com","Import Restrictions",$maildata);
                print_r($responseArray  );
    }

    function updateRestrictions($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
    {

    	    $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;


			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>

			<soapenv:Body>
			<m:request_update_restrictions xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
			<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
			<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
			<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>
			</m:hotel>
			<m:dates>
			<m:date_range>
			<m:start_date>2018-11-15</m:start_date>
			<m:end_date>2018-11-15</m:end_date>
			<m:week_days>YYYYYYY</m:week_days>

			<m:restrictions>
			<m:restriction rate="VR" mealplan="OB" room="TW" >
			<m:min_days>7</m:min_days>
			</m:restriction>

			<m:restriction rate="VR" mealplan="OB" room="SG" >
			<m:min_days>7</m:min_days>
			</m:restriction>

			<m:restriction room="DB" >
			<m:closed_to_arrival>Y</m:closed_to_arrival>
			</m:restriction>

			</m:restrictions>
			</m:date_range>
			</m:dates>
			</m:request_update_restrictions>

			</soapenv:Body>

			</soapenv:Envelope> ';

			$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata=">>Request:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response".$response;
                $this->send_xml("xml@hoteratus.com","Update Restrictions",$maildata);
    }
     function deleteRestrictions($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
    {

    	    $ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;


			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
			<soapenv:Header>       
			<m:user_validation xmlns:m="'.$urlp.'/login">          
			<m:usr_code>'.$code.'</m:usr_code>          
			<m:usr_name>'.$user.'</m:usr_name>          
			<m:usr_pwd>'.$pass.'</m:usr_pwd>          
			<m:language>2</m:language>       
			</m:user_validation>    
			</soapenv:Header>

			<soapenv:Body>
			<m:request_delete_restrictions xmlns:m="'.$urlp.'/hotel">
			<m:hotel>
			<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
			<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
			<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>
			</m:hotel>
			<m:dates>
			<m:date_range>
			<m:start_date>2018-11-16</m:start_date>
			<m:end_date>2018-11-16</m:end_date>
			<m:week_days>YYYYYYY</m:week_days>
			<m:restrictions>
			<m:restriction room="TW" />
			<m:restriction room="DB" />
			<m:restriction />
			</m:restrictions>
			</m:date_range>
			</m:dates>
			</m:request_delete_restrictions>
			</soapenv:Body>
			</soapenv:Envelope> ';

			$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );

				$maildata=">>Request:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                $maildata.="Response".$response;
                $this->send_xml("xml@hoteratus.com","Delete Restrictions",$maildata);
    }
    function getDiscounts($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
				<soapenv:Header>       
					<m:user_validation xmlns:m="'.$urlp.'/login">          
						<m:usr_code>'.$code.'</m:usr_code>          
						<m:usr_name>'.$user.'</m:usr_name>          
						<m:usr_pwd>'.$pass.'</m:usr_pwd>          
						<m:language>1</m:language>       
					</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
					<m:request_discounts xmlns:m="'.$urlp.'/hotel">          
						<m:hotel>             
							<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
							<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
							<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>               
						</m:hotel>       			      
					</m:request_discounts>    
				</soapenv:Body> 
			</soapenv:Envelope> ';

			 	$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Import Discount",$maildata);
                die;
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rates']['ns2hotel_code'];
                	$rates=@$responseArray['soapenvBody']['ns2response_rates']['ns2rates']['ns2rate'];
                	foreach ($rates as  $value) {
                		$ratevariable=$value['@attributes']['variable'];
                		$ratecode=$value['ns2rate_code'];
                		$ratename=$value['ns2rate_name'];
                		
                
                		echo $ratevariable.'<br>'.$ratecode.'<br>'.$ratename.'<br>';
                	}
                	
                }				
	}
	 function UpdateDiscount($channel_id='',$cha_name='HotusaGroup',$hotel_id='',$user_id='')
	{
			$ch_details = get_data(CONNECT,array('hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'status'=>'enabled'))->row();

	        if(!$ch_details)
	        {
	        	$data['Enable'] = 'Enable';
	        	return $data;
	        }

	        $url="";						
			if($ch_details->mode == 0){
				$url =$ch_details->test_url;
			}else{
				$url =$ch_details->live_url;
			}  

	    	$code=$this->code;
			$user=$this->user;
			$pass=$this->pass;
			$urlp=$this->url_p;

			$xml_post_string = 
			'<?xml version="1.0" encoding="utf-8"?>
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">    
				<soapenv:Header>       
					<m:user_validation xmlns:m="'.$urlp.'/login">          
						<m:usr_code>'.$code.'</m:usr_code>          
						<m:usr_name>'.$user.'</m:usr_name>          
						<m:usr_pwd>'.$pass.'</m:usr_pwd>          
						<m:language>1</m:language>       
					</m:user_validation>    
				</soapenv:Header>    
				<soapenv:Body>       
					<m:request_update_discount xmlns:m="'.$urlp.'/hotel">          
						<m:hotel>             
							<m:hotel_code>'.$ch_details->hotel_channel_id.'</m:hotel_code>
							<m:hotel_user>'.$ch_details->user_name.'</m:hotel_user>
							<m:hotel_pwd>'.$ch_details->user_password.'</m:hotel_pwd>               
						</m:hotel>       			      
						<m:discount type="A">
						<m:sequence type="1">
						<m:restrict_rooms>
						<m:room>DB</m:room>
						</m:restrict_rooms>
						<m:restrict_mealplans>
						<m:mealplan>BB</m:mealplan>
						</m:restrict_mealplans>
						</m:sequence>
						<m:discount_season>
							<m:amount>20.0</m:amount>
							<m:travelwindow_start_date>2018-09-01</m:travelwindow_start_date>
							<m:travelwindow_end_date>2018-10-01</m:travelwindow_end_date>
							<m:minimum_stay>2</m:minimum_stay>
							<m:min_advance_days>3</m:min_advance_days>
							<m:week_days>NNNNYYY</m:week_days>
							<m:cancellation_penalty>
								<m:days>18</m:days>
	
							<m:nights>1</m:nights>

								</m:cancellation_penalty>
						</m:discount_season>
						<m:discount_booking_dates>
						<m:booking_start_date>2017-12-21</m:booking_start_date>
						<m:booking_end_date>2018-12-31</m:booking_end_date>
						<m:week_days>YYYYYYY</m:week_days>
						</m:discount_booking_dates>
						</m:discount>
					</m:request_update_discount>  
				</soapenv:Body> 
			</soapenv:Envelope> ';

			 	$header = array(
                            "Content-type: text/html; charset=utf-8",
                            "Host:".str_replace("http://","", $urlp),
                            "Content-length: ".strlen($xml_post_string),
                        );
				$maildata="Request>>:".$xml_post_string;
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$ss = curl_getinfo($ch);
				$response = curl_exec($ch);
				curl_close($ch);
				$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $Errorarray= @$responseArray['soapenvBody']['soapenvFault']['detail']['ns3LinkHotelException']['ns3message'];
                print_r( $responseArray );
                $maildata.="Response>>:".$response;
                $this->send_xml("xml@hoteratus.com","Update Discount",$maildata);
                die;
                if($Errorarray!='')
                {
                	$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);

                }
                else
                {
                	$hotelcode=@$responseArray['soapenvBody']['ns2response_rates']['ns2hotel_code'];
                	$rates=@$responseArray['soapenvBody']['ns2response_rates']['ns2rates']['ns2rate'];
                	foreach ($rates as  $value) {
                		$ratevariable=$value['@attributes']['variable'];
                		$ratecode=$value['ns2rate_code'];
                		$ratename=$value['ns2rate_name'];
                		
                
                		echo $ratevariable.'<br>'.$ratecode.'<br>'.$ratename.'<br>';
                	}
                	
                }				
	}
}
?>


