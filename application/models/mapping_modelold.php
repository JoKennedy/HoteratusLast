<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Mapping_model extends CI_Model
{
	function connected_channel($channel_id='')
	{
		if($channel_id!="")
		{
         $this->db->where('status','Active');
         $this->db->where('user_id','Active');
         $this->db->where('channel_id',$channel_id);
		 $query=$this->db->get('manage_channel');
		}
		else
		{
		 $this->db->where('status','Active');
		 $query=$this->db->get('manage_channel');
		}
		if($query->num_rows>0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function connect_hotel()
	{	
		$hotel_id = hotel_id();
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());
		}
		$this->db->select('channel_id,status,xml_type');
		$this->db->where('hotel_id',$hotel_id);
		$res = $this->db->get(' user_connect_channel');
		//echo $this->db->last_query(); die;
		if($res->num_rows > 0)
		{
			return $res->result();
		}
		else{
			return false;
		}
	}
	
	function channel_connect($connect)
	{
		$this->db->where('channel_id',$connect);
		$res = $this->db->get('manage_channel');
		if($res->num_rows > 0)
		{
			return $res->row();
		}
			return false;
	}
	
    function mapped_hotel($connected_channel,$type="")
	{
		$user_id=current_user_type();
		if($type=="fetch"){
	        $this->db->where_in('connected_channel',$connected_channel);
	        $this->db->where('user_id',$user_id);
	        $query=$this->db->get('manage_users'); 
	        return $query->result();
		}else{
			$where = "FIND_IN_SET('".$connected_channel."', connected_channel)";  
	        $this->db->where( $where );
	         $query=$this->db->get('manage_users');
	        return $query->num_rows;
        }
	}

	function all_rooms($connected_channel,$property_id)
	{
	   $hotel_id = hotel_id();
 	   $user_id=current_user_type();
		    $where = "FIND_IN_SET('".$connected_channel."', connected_channel)"; 
	        $this->db->where( $where );
			if(user_type()=='1'){
				$this->db->where('owner_id',$user_id);
			}
			else if(user_type()=='2'){
				$this->db->where('owner_id',current_user_type());
			}
			$this->db->where('hotel_id',$hotel_id);
			$this->db->where('property_id',$property_id);
	        $query=$this->db->get('manage_property'); 
	        return $query->result();
	}
	
	function mapped_rooms($connected_channel,$type="")
	{
		$hotel_id = hotel_id();
		$user_id=current_user_type();
		if($type=="fetch")
		{
	        $where = "FIND_IN_SET('".$connected_channel."', connected_channel)"; 
	        $this->db->where( $where );
	        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->db->where('owner_id',$user_id); 
			}
			else if(user_type()=='2'){
				$this->db->where('owner_id',current_user_type()); 
			}
			$this->db->where('hotel_id',$hotel_id);
			$this->db->where('status','Active');
	        $query=$this->db->get('manage_property'); 
	        return $query->result();
		}
		else
		{
	        $where = "FIND_IN_SET('".$connected_channel."', connected_channel)";   $this->db->where( $where );
	        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->db->where('owner_id',$user_id); 
			}
			else if(user_type()=='2'){
				$this->db->where('owner_id',owner_id()); 
			}
			$this->db->where('hotel_id',$hotel_id);
			$this->db->where('status','Active');
	        $query=$this->db->get('manage_property'); 
	        return $query->num_rows;
        }
	}
	
	function not_mapped_rooms($connected_channel,$type="")
	{
		$hotel_id = hotel_id();
		$user_id=current_user_type();
		if($type=="fetch")
		{
	        //$where = "!FIND_IN_SET('".$connected_channel."', connected_channel)";
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->db->where('owner_id',$user_id); 
			}
			else if(user_type()=='2'){
				$this->db->where('owner_id',current_user_type()); 
			}
			$this->db->where('hotel_id',$hotel_id);
			$this->db->where('status','Active');
	       // $this->db->where( $where );
	        $query=$this->db->get('manage_property'); 
	        return $query->result();
		}
		else
		{
	        $where = "!FIND_IN_SET('".$connected_channel."', connected_channel)";
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->db->where('owner_id',$user_id); 
			}
			else if(user_type()=='2')
			{
				$this->db->where('owner_id',owner_id()); 
			}
			$this->db->where('hotel_id',$hotel_id);
			$this->db->where('status','Active');
	        $this->db->where( $where );
	        $query=$this->db->get('manage_property'); 
	        return $query->num_rows;
        }
	}
	
	function removemap($connect,$mapping_id,$property_id)
	{ 
		
		
      //$this->db->query("UPDATE manage_property SET connected_channel=(select replace(connected_channel,'".$connect."','')) WHERE property_id='".$property_id."' AND FIND_IN_SET('".$connect."',connected_channel)");
		/* if(user_type()=='1')
	  	{ */
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$property_id,'channel_id'=>$connect))->count_all_results();
			if($count=='0')
			{
				$this->db->query("UPDATE manage_property SET connected_channel=(select replace(connected_channel,'".$connect."','')) WHERE property_id='".$property_id."' AND FIND_IN_SET('".$connect."',connected_channel)");
			}
			
			delete_data('roommapping',array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'mapping_id'=>$mapping_id,'channel_id'=>$connect));
	  /*	}
	  	 else if(user_type()=='2')
	  	{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$property_id,'channel_id'=>$connect))->count_all_results();
			if($count=='0')
			{
				$this->db->query("UPDATE manage_property SET connected_channel=(select replace(connected_channel,'".$connect."','')) WHERE property_id='".$property_id."' AND FIND_IN_SET('".$connect."',connected_channel)");
			}
			
			delete_data('roommapping',array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'mapping_id'=>$mapping_id,'channel_id'=>$connect));  
	  	} */
	}
	
    function get_roommapping($channel_id,$property_id)
	{
    	$user_id=current_user_type();
		$hotel_id = hotel_id();
		if(user_type()=='1'){
			$this->db->where('owner_id',$user_id);
		}
		else if(user_type()=='2'){
			$this->db->where('owner_id',current_user_type());
		}
		$this->db->where('hotel_id',$hotel_id);
		$this->db->where('property_id',$property_id);	
    	$this->db->where('channel_id',$channel_id);
        $query=$this->db->get('roommapping');
	      if($query->num_rows>0)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
    }

	function save_mapping()
	{	
		$hotel_id 		= 	hotel_id();
		$user_id 		= 	current_user_type();
		$channel_id		= 	insep_decode($this->input->post('channel_id'));
		$property_id	=	insep_decode($this->input->post('room_ids')); 
		if($this->input->post('guest_count')!='')
		{
			$guest_count = $this->input->post('guest_count'); 
		}
		else
		{
			$guest_count = '';
		}
		if($this->input->post('refun_type')!='')
		{
			$refun_type	= $this->input->post('refun_type');
		}
		else
		{
			$refun_type = '';
		}

		$channeldetails=$this->db->query("Select * from  manage_channel where channel_id=".$channel_id."")->row_array();
			          
		$mapping_val=$channeldetails['mapping_requirements'];
		
		if($this->input->post('rate_conversion')!='')
		{
			$rate_conversion = $this->input->post('rate_conversion');
		}
		else
		{
			$rate_conversion	=	1;
		}
		if($channel_id==1)
		{
			$levelmapp	=	$this->input->post('levelmapp');
		}
		else if($channel_id==17)
		{
			$levelmapp	=	$this->input->post('price_type');
		}
		else
		{
			$levelmapp	=	'0';
		}
		$data=array(
						'owner_id'=>$user_id,
						'hotel_id'=>$hotel_id,
						'rate_id'=>$this->input->post('rate_type'),
						'property_id'=>$property_id,    
						'enabled'=>$this->input->post('optionenable'),
						'channel_id'=>$channel_id,
						'guest_count'=>$guest_count, 
						'refun_type'=>$refun_type,  
						'included_occupancy'=>$this->input->post('included_occupancy'),    
						'extra_adult'=>$this->input->post('extra_adult'),    
						'extra_child'=>$this->input->post('extra_child'),
						'single_quest'=>$this->input->post('single_guest'),
						'update_rate'=>$this->input->post('rate'),	
						'update_availability'=>$this->input->post('availabilites'),
						'import_mapping_id'=>$this->input->post('import_mapping_id'),
						'rate_conversion' => $rate_conversion,
						'explevel' => $levelmapp,
					);
		$lable="";
		$value="";
		$title="";
		$data_dynamic=array();
		if($mapping_val!="")
		{
			$full_value=explode(",",$mapping_val);
			$i=1;
			foreach($full_value as $val)
			{
				
				$field_split=explode("-",$val);
				if($channel_id==17 && $levelmapp=='BRP')
				{	
					if($i==7 || $i==8)
					{
						$val=trim($field_split[0]);
					}
				}
				else
				{
					$val=trim($field_split[0]);
				}
				$lable .=$val.",";
				if($this->input->post($val)=='')
				{
					$value .=',';
				}
				else
				{
					$value .=$this->input->post($val).",";
				}
				$title .=$this->input->post("title_".$val).",";
				$i++;
			}
		}
		if($this->input->post('mapping_id')!='')
		{
			$mapping_id = $this->input->post('mapping_id');
			$query=$this->db->query('Select * from roommapping where mapping_id='.$mapping_id.' AND owner_id='.current_user_type().' AND hotel_id='.hotel_id().' AND channel_id='.$channel_id.'');
			
			if($query->num_rows==1)
			{
				$this->db->where('hotel_id',hotel_id());
			
				$this->db->where('owner_id',current_user_type());
	
				$data_dynamic=array(
										'label'=>rtrim($lable,","),
										'value'=>substr($value,0,-1),
										'hotel_id'=>$hotel_id,
										'user_id'=>$user_id,
										'mapping_id'=>$mapping_id
									);
							
				$this->db->where('mapping_id',$mapping_id);
				$this->db->where('channel_id',$channel_id);
				$this->db->update('roommapping',$data);
				
				$this->db->where('mapping_id',$mapping_id);
				$this->db->update('mapping_values',$data_dynamic);
			}
		}
		else
		{
			$this->db->insert('roommapping',$data);
			$mapping_id= $this->db->insert_id();
			$data_dynamic=array(
									'label'=>rtrim($lable,","),
									'value'=>substr($value,0,-1),
									'title'=>rtrim($title,","),
									'hotel_id'=>$hotel_id,
									'user_id'=>$user_id,
									'mapping_id'=>$mapping_id
								);
			$this->db->insert('mapping_values',$data_dynamic);

			$this->db->query('update manage_property set connected_channel=concat(connected_channel,",'.$channel_id.'") where property_id='.$property_id.' AND hotel_id = '.hotel_id().' AND owner_id = '.current_user_type().''); 
	
		}
	}
	
	function connect_channel($channel_id)
	{
		$user_connect = $this->input->post('user_connect');
		$this->db->where('channel_id',$channel_id);
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$ver = $this->db->get('user_connect_channel');
		$get_urls = get_data("channel_urls",array("channel_id" => $channel_id))->row();
		if(count($get_urls) != 0)
		{
			$test_url = $get_urls->test_url;
			$live_url = $get_urls->live_url;
			$mode = $get_urls->mode;
		}
		else
		{
			$test_url = "";
			$live_url = "";
			$mode = "";
		}
		if($ver->num_rows()==0)
		{
			if($channel_id == '14' )
			{
				$C_URL = get_data(C_URL,array('channel_id'=>$channel_id))->row();
				
				if($C_URL)
				{
					if($C_URL->mode=='0')
					{
						$url = explode(',',$C_URL->test_url);
						
					}
					else if($C_URL->mode=='1')
					{
						$url = explode(',',$C_URL->live_url);
					}
					
					$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:impl="http://impl.wsSuppliers.integracion/">
					<soapenv:Header/>
					<soapenv:Body>
						<impl:getHotelsSuppliersRQ>
							<impl:getHotelsSuppliersRQRow>
								<impl:idCliePor>'.$this->input->post('user_name').'</impl:idCliePor>
								<impl:pwCliePor>'.$this->input->post('user_password').'</impl:pwCliePor>
								<impl:ideCHM>'.ideCHM.'</impl:ideCHM>
							</impl:getHotelsSuppliersRQRow>
						</impl:getHotelsSuppliersRQ>
					</soapenv:Body>
					</soapenv:Envelope>';
					$ch				=	curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_URL, $url[0]);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_TIMEOUT, 500);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
					$ss 			=	curl_getinfo($ch);		        
					$response 		=	curl_exec($ch);
					$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
					$xml = simplexml_load_string($xml);
					$json = json_encode($xml);
					$responseArray = json_decode($json,true);
					if(is_array($responseArray))
					{
						$Error	=	@$responseArray['soapenvBody']['getHotelsSuppliersRS']['getHotelsSuppliersRSRow']['error'];
						
						if($Error=='')
						{
							$codeSupplier = $responseArray['soapenvBody']['getHotelsSuppliersRS']['getHotelsSuppliersRSRow']['curOutHotelsSuppliersRow']['codeSupplier'];
							
							$referenceHotel = $responseArray['soapenvBody']['getHotelsSuppliersRS']['getHotelsSuppliersRSRow']['curOutHotelsSuppliersRow']['referenceHotel'];
						}
						else 
						{
							$codeSupplier = '';
							$referenceHotel = '';
						}
					}
					else 
					{
						$codeSupplier = '';
						$referenceHotel = '';
					}
					curl_close($ch);	
				}
				else
				{
					$codeSupplier = '';
					$referenceHotel = '';
				}
				
				$hotel_channel_id = $referenceHotel;
			}
			else
			{
				$hotel_channel_id = $this->input->post('hotel_channel_id');
			}
			$status = $this->input->post('optionenable');
			$rate_conversion = $this->input->post('rate_conversion');
			$user_name = $this->input->post('user_name');
			$user_password = $this->input->post('user_password');
			$email_address = $this->input->post('email_address');
			
			if($this->input->post('cmid'))
			{
				$cmid = $this->input->post('cmid');
			}
			else
			{
				if($channel_id == '14' )
				{
					$cmid = $codeSupplier;
				}
				else
				{
					$cmid = "";
				}
			}
			if(isset($_POST['web_id']))
			{
				$web_id=$this->input->post('web_id');
			}
			if(isset($web_id)&&$web_id!="")
			{
				$web_id = $this->input->post('web_id');
			}
			else
			{
				$web_id="";
			}
			
			if($this->input->post('xml_select'))
			{
				$xml_type = $this->input->post('xml_select');
			}
			else
			{
				$xml_type = 0;
			}
			
			$data = array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'status'=>$status,'rate_multiplier'=>$rate_conversion,'user_name'=>$user_name,'reservation_email'=>$email_address,'user_password'=>$user_password,'hotel_channel_id'=>$hotel_channel_id,'connect_date'=>date('Y-m-d H:i:s'),'web_id'=>$web_id,'test_url' => $test_url, 'live_url'=>$live_url,'mode'=>$mode,'xml_type'=>$xml_type,'cmid'=>$cmid);

			$res = $this->db->insert('user_connect_channel',$data);
			if($res)
			{
				update_channel($channel_id,$status);
				return true;
			}
		}
		else
		{
			$status = $this->input->post('optionenable');
			$rate_conversion = $this->input->post('rate_conversion');
			$user_name = $this->input->post('user_name');
			$user_password = $this->input->post('user_password');
			$email_address = $this->input->post('email_address');
			$hotel_channel_id = $this->input->post('hotel_channel_id');
			if(isset($_POST['web_id']))
			{
				$web_id=$this->input->post('web_id');
			}
			if(isset($web_id)&&$web_id!="")
			{
				$web_id = $this->input->post('web_id');
			}else
			{
				$web_id="";
			}
			if($this->input->post('xml_select'))
			{
				$xml_type = $this->input->post('xml_select');
			}
			else
			{
				$xml_type = 0;
			}
            if($this->input->post('cmid'))
			{
				$cmid = $this->input->post('cmid');
				$cdata['cmid'] = $cmid;
			}
			else
			{
				if($channel_id == '14' )
				{
					$cdata = array();
				}
				else
				{
					$cdata['cmid'] = $cmid;
				}
			}   
			
			$data = array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'status'=>$status,'rate_multiplier'=>$rate_conversion,'user_name'=>$user_name,'reservation_email'=>$email_address,'user_password'=>$user_password,'hotel_channel_id'=>$hotel_channel_id,'web_id'=>$web_id,'test_url' => $test_url, 'live_url'=>$live_url,'mode'=>$mode,'xml_type'=>$xml_type);
			
			$data = array_merge($data,$cdata);
		
			$this->db->where('user_connect_id',$user_connect);
			$ves = $this->db->update('user_connect_channel',$data);
			if($ves)
			{
				update_channel($channel_id,$status);
				return true;
			}
		}
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('manage_hotel');
		if($res->num_rows == 1)
		{
			$chan = $res->row();
			$channels = $chan->connected_channel;
			if($channels)
			{
			   $channel_det = $channels.','.$channel_id;
			}
			else
			{
			   $channel_det = $channel_id;
			}
			$result = array('connected_channel'=>$channel_det);
			$this->db->where('owner_id',current_user_type());
			$this->db->where('hotel_id',hotel_id());
			$res = $this->db->update('manage_hotel',$result);
		}
		else
		{
			$this->db->insert('manage_hotel',$result);
		}
		if($res)
		{
			return true;
		}
			return false;
	}
	
	function get_channel($cha){
		$chan = implode(',',$cha);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$this->db->where('user_id',current_user_type());	
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());
		}
		$this->db->where('hotel_id',hotel_id());
		$this->db->where('channel_id',$chan);
		$res = $this->db->get('user_connect_channel');
		if($res->num_rows == 1)
		{
			return $res->row();
		}
			return false;
	}
	// all channels...
	function all_channels($cha)
	{
		$chann = explode(',',$cha);
		$this->db->where_not_in('channel_id',$chann);	
		$this->db->where('status != ""');
		$res = $this->db->get('manage_channel');
		
		if($res->num_rows > 0)
		{
			return $res->result();
		}
		else{
			return false;
		}
	}
	// un_connected_channels..
	function un_connected_channels(){
		$this->db->select('channel_id');
		$this->db->where('hotel_id',hotel_id());
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());	
		}
		$res = $this->db->get('user_connect_channel');
		
		
		if($res->num_rows!=0){
			$channel_ids = "";
			foreach ($res->result() as $channel) {
				$channel_ids .= $channel->channel_id.",";
			}
			$channel_ids = rtrim($channel_ids,',');
			return $channel_ids;
		}
		else{
			return false;
		}
	}

	// 19/12/2015...

	function get_rate_types($rate_id){
			// echo 'ghfiff'; die;
		if(user_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());
		}
			$this->db->where('droc',1);
			$this->db->where('hotel_id',hotel_id());
			$this->db->where('room_id',$rate_id);
			$res = $this->db->get('room_rate_types');
			if($res->num_rows>0){
				return 	$res->result();
			 }
			else {
				return false;
			}
	}

	function get_room_id($property_id){
		if(user_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());	
		}
			$this->db->where('hotel_id',hotel_id());
			$this->db->where('room_id',$property_id);
			$res = $this->db->get('room_rate_types');
			if($res->num_rows>0){
				return $res->result();
			}
			else{
				return false;
			}
	}

	function select_rooms($property_id){
		if(user_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',current_user_type());
		}
			$this->db->where('hotel_id',hotel_id());
			$this->db->where('room_id',$property_id);
			$res = $this->db->get('room_rate_types');
			if($res->num_rows>0){
				return $res->result();
			}
			else{
				return false;
			}
	}

	// 22/12/2015....

    function get_ratetypes($id="")

    {

    if($id!="")

    {

    	if(user_type()=='1'){

       	$this->db->where('user_id',current_user_type());

   		}else if(user_type()=='2'){

			$this->db->where('user_id',current_user_type());   			
   		}

       $this->db->where('hotel_id',hotel_id());

       $this->db->where('room_id',$id);

       $rex=$this->db->get('room_rate_types');

      /* echo '<pre>';
   	   print_r($rex->result()); die;*/


    }

    if($rex->num_rows>=0)

    {

       return $rex->result();

    }

    else
    {

       return false;

    }

    }


    function get_rooms($prop_id){
    	if(user_type()=='1'){
    		$this->db->where('owner_id',current_user_type());
    	}
    	else if(user_type()=='2'){
    		$this->db->where('owner_id',current_user_type());	
    	}
    		$this->db->where('hotel_id',hotel_id());
    		$this->db->where('property_id',$prop_id);
    		$rex = $this->db->get('roommapping');
    		if($rex->num_rows==1){
    			return $rex->row();
    		}
    			return false;
    }

     // 02/02/2016...
    function import_check($channel_id)
    {
    	 $this->db->where('channel',insep_decode($channel_id));
    	 $ver = $this->db->get('import_mapping');
    	 if($ver->num_rows()>0)
    	 {
    	 	return $ver->row();
    	 }
    	 else
    	 {
    	 	return false;
    	 }
    }
	
	function get_room_name($id_room)
	{
		$this->db->where('owner_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$this->db->where('channel_id',11);
		$this->db->where('room_name',$id_room);
		$ver = $this->db->get(IM_RECO);
		if($ver->num_rows()==1)
		{
			return $ver->row();
		}
		else
		{
			return false;
		}
	}
	
	/* Start Subbaiah\*/
	
	//22/02/2016
	
	function get_mapping_rooms($channel_id,$type='')
	{	
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$owner_id= current_user_type();
		}
		elseif(user_type()=='2')
		{
			$owner_id = current_user_type();
		}
		if($channel_id=='11')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			$this->db->select('R.IDROOM, R.CODE, R.re_id');
			if($clean!='')
			{
				$this->db->where_not_in('R.re_id',$import);
			}
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get(IM_RECO.' as R');
			if($result!='')
			{
				return $result->result();
			}
			else
			{
				return false;
			}
		}
		elseif($channel_id=='1')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			$this->db->select('E.map_id, E.roomtype_name, E.roomtype_id, E.code, E.name, E.distributionModel, E.rateAcquisitionType');
			if($clean!='')
			{
				$this->db->where_not_in('E.map_id',$import);
			}
			//$this->db->where_not_in('E.distributionModel',"ExpediaCollect");
			$this->db->where_not_in('E.rateAcquisitionType',"Linked");
			$this->db->where_not_in('E.rateAcquisitionType',"Derived");
			$this->db->where('rate_type_id !=', '');
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get('import_mapping'.' as E');
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
		elseif($channel_id=='2')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			$bk_details = booking_hotel_id();
			$this->db->select('B.import_mapping_id, B.room_name, B.policy_id, B.rate_name,B.B_rate_id');
			if($clean!='')
			{
				$this->db->where_not_in('B.import_mapping_id',$import);
			}
		
			$this->db->where_not_in('B.B_rate_id','0');
			$this->db->where(array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_hotel_id'=>$bk_details));
			$result = $this->db->get(BOOKING.' as B');
			if($result!='')
			{
				return $result->result();
			}
			else
			{
				return false;
			}
		}
		elseif($channel_id=='8')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			
			$this->db->select('G.GTA_id, G.RoomType, G.Description,G.rateplan_code,G.contract_type,G.ID,G.RateBasis,G.MaxOccupancy');
			if($clean!='')
			{
				$this->db->where_not_in('G.GTA_id',$import);
			}
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get(IM_GTA.' as G');
			if($result!='')
			{
				return $result->result();
			}
			else
			{
				return false;
			}
		}
		elseif($channel_id=='5')
		{
			if($type!='update')
			{
				$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			$this->db->select('H.map_id, H.contract_name, H.sequence, H.roomname, H.roomtype, H.characterstics');
			if($clean!='')
			{
				$this->db->where_not_in('H.map_id',$import);
			}
			$this->db->where(array('user_id'=>$owner_id,'hotel_id'=>hotel_id()));
			$result = $this->db->get('import_mapping_HOTELBEDS_ROOMS'.' as H');
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
	}
	
	function get_all_mapping_rooms($channel_id)
	{	
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$owner_id= current_user_type();
		}
		elseif(user_type()=='2')
		{
			$owner_id = current_user_type();
		}
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
		
		if($channel_id=='11')
		{
			$count = $this->db->select('re_id')->from(IM_RECO)->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$ch_details->hotel_channel_id))->count_all_results();
			return $count;
		}
		elseif($channel_id=='1')
		{
			$count = $this->db->select('map_id')->from('import_mapping')->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$ch_details->hotel_channel_id))->count_all_results();
			return $count;
		}
		elseif($channel_id=='2')
		{
			$bk_details = booking_hotel_id();
			$count = $this->db->select('import_mapping_id')->from(BOOKING)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_hotel_id'=>$bk_details))->count_all_results();
			return $count;
		}
		elseif($channel_id=='8')
		{
			$count = $this->db->select('GTA_id')->from(IM_GTA)->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id'=>$ch_details->hotel_channel_id))->count_all_results();
			return $count;
		}
		elseif($channel_id=='5')
		{
			$count = $this->db->select('map_id')->from('import_mapping_HOTELBEDS_ROOMS')->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->count_all_results();
			return $count;
		}
	}
	
	function get_all_mapped_rooms($channel_id)
	{
		$this->db->select('R.mapping_id,R.owner_id,R.hotel_id,R.property_id,R.rate_id,R.channel_id,R.import_mapping_id,R.guest_count,R.refun_type,R.enabled,R.included_occupancy,R.extra_adult,R.extra_child,R.single_quest,R.update_rate,R.update_availability,R.rate_conversion,R.explevel');
		if($channel_id==2)
		{
			$this->db->join(BOOKING.' as B','R.import_mapping_id=B.import_mapping_id');
			$this->db->join(CONNECT.' as C','B.channel_hotel_id=C.hotel_channel_id');
		}
		else if($channel_id==1)
		{
			$this->db->join(IM_EXP.' as E','R.import_mapping_id=E.map_id');
			$this->db->join(CONNECT.' as C','E.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==11)
		{
			$this->db->join(IM_RECO.' as RE','R.import_mapping_id=RE.re_id');
			$this->db->join(CONNECT.' as C','RE.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==8)
		{
			$this->db->join(IM_GTA.' as G','R.import_mapping_id=G.GTA_id');
			$this->db->join(CONNECT.' as C','G.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==5)
		{
			$this->db->join(IM_HOTELBEDS_ROOMS.' as HB','R.import_mapping_id=HB.map_id');
			//$this->db->join(CONNECT.' as C','G.hotel_channel_id=C.hotel_channel_id');
		}
		$this->db->where(array('R.owner_id'=>current_user_type(),'R.hotel_id'=>hotel_id(),'R.channel_id'=>$channel_id));
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
	
	function get_all_mapped_rooms_count($channel_id)
	{
		$this->db->select('R.mapping_id');
		if($channel_id==2)
		{
			$this->db->join(BOOKING.' as B','R.import_mapping_id=B.import_mapping_id');
			$this->db->join(CONNECT.' as C','B.channel_hotel_id=C.hotel_channel_id');
		}
		else if($channel_id==1)
		{
			$this->db->join(IM_EXP.' as E','R.import_mapping_id=E.map_id');
			$this->db->join(CONNECT.' as C','E.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==11)
		{
			$this->db->join(IM_RECO.' as RE','R.import_mapping_id=RE.re_id');
			$this->db->join(CONNECT.' as C','RE.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==8)
		{
			$this->db->join(IM_GTA.' as G','R.import_mapping_id=G.GTA_id');
			$this->db->join(CONNECT.' as C','G.hotel_channel_id=C.hotel_channel_id');
		}
		else if($channel_id==5)
		{
			$this->db->join(IM_HOTELBEDS_ROOMS.' as HB','R.import_mapping_id=HB.map_id');
			//$this->db->join(CONNECT.' as C','G.hotel_channel_id=C.hotel_channel_id');
		}
		$this->db->where(array('R.owner_id'=>current_user_type(),'R.hotel_id'=>hotel_id(),'R.channel_id'=>$channel_id));
		$query = $this->db->get(MAP.' as R');
		/* echo $this->db->last_query(); */
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function connected_room($channel_id)
	{
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$owner_id= current_user_type();
		}
		elseif(user_type()=='2')
		{
			$owner_id = current_user_type();
		}
		$connected_room = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>hotel_id(),'channel_id'=>$channel_id),'import_mapping_id')->result_array();
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
			$import[]='';
		}
		return $import;
	}
	/* End Subbaiah */	

	function getExpediaLevel($id){

		$this->db->select("rateAcquisitionType");
		$this->db->where('map_id',$id);
		$query = $this->db->get("import_mapping");
		return $query->result();
	}
	function cleanArray($array)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $sub_array)
            {
                $result = $this->cleanArray($sub_array);
                if ($result === false)
                {
                    unset($array[$key]);
                }
                else
                {
                    $array[$key] = $result;
                }
            }
        }

        if (empty($array))
        {
            return false;
        }

        return $array;
    }

    function getOverBooking($user_id,$hotel_id){
    	$current = date('d/m/Y');
    	
    	$sql = "SELECT * FROM overbook WHERE separate_date >= '".$current."' AND status = 1 AND user_id = '".$user_id."' AND hotel_id = '".$hotel_id."'";
    	$res = $this->db->query($sql);
    	return $res->result();
    }
}
