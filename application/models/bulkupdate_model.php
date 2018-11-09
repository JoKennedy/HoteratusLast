<?php
ini_set('memory_limit', '-1');
ini_set('display_erros','1');
class bulkupdate_model extends CI_Model
{

	
	function saveRoomInfo($room)
	{	

		$userid=user_id();
		$hotelid=hotel_id();
		$ChannelsInfo='';
		$ChannelsErros='';
	

		foreach ($room['channelids'] as  $channelid) {
			if ($channelid==0) {
					$stringupdate='';
					if(@$room['availability']!='')
					{
						$roominfo['availability'] =$room['availability'];
						$stringupdate.=(strlen($stringupdate)>0?',':'')."availability=".$room['availability'];
						if(@$room['availability']=='0')
						{	
							$stringupdate.=(strlen($stringupdate)>0?',':'')."stop_sell=1,open_room=0";
							$roominfo['stop_sell'] ="1";
							$roominfo['open_room']='0';
						}
					}
					if(@$room['price']!='')
					{
						$roominfo['price'] =$room['price'];     
						$roominfo['PriceRevenue']= $room['price'];  
						$stringupdate.=(strlen($stringupdate)>0?',':'')."price=".$room['price'].",PriceRevenue=".$room['price'];               
					}
					if(@$room['minimumstay']!='')
					{
						$roominfo['minimum_stay'] =$room['minimumstay'];
						$stringupdate.=(strlen($stringupdate)>0?',':'')."minimum_stay=".$room['minimumstay'];
					}
					if(isset($room['cta'])!='')
					{
						$roominfo['cta'] =$room['cta'];
						$stringupdate.=(strlen($stringupdate)>0?',':'')."cta=".$room['cta'];
					}

					if(isset($room['ctd'])!='')
					{
						$roominfo['ctd'] =$room['ctd'];
						$stringupdate.=(strlen($stringupdate)>0?',':'')."ctd=".$room['ctd'];
					}

					if(isset($room['stops'])!='')
					{
						$roominfo['stop_sell'] =($room['stops']!=1?'0':'1');
						$roominfo['open_room'] =($room['stops']==1?'0':'1');
						$stringupdate.=(strlen($stringupdate)>0?',':'')."stop_sell=".($room['stops']!=1?'0':'1').",open_room=".($room['stops']==1?'0':'1');
						if(@$room['availability']=='0')
						{
							$roominfo['stop_sell'] ="1";
							$roominfo['open_room']='0';
							$stringupdate.=(strlen($stringupdate)>0?',':'')."stop_sell=1,open_room=0";
						}
					}


					$this->db->query("update ".TBL_UPDATE." set ".$stringupdate." where room_id=".$room['room_id']." and  (STR_TO_DATE(separate_date ,'%d/%m/%Y') between '".$room['start_date']."' and '".$room['end_date']."')");					

				$date_exists=$this->db->query("select * from ".TBL_UPDATE." where room_id=".$room['room_id']." and  (STR_TO_DATE(separate_date ,'%d/%m/%Y') between '".$room['start_date']."' and '".$room['end_date']."')")->result_array();

				foreach ($room['separate'] as  $date) {

					
					$infoid= array_search(date('d/m/Y',strtotime($date)), array_column($date_exists, 'separate_date'));

					if($infoid===false)
					{ 
						
						$roominfo['separate_date'] = date('d/m/Y',strtotime($date));
						$roominfo['trigger_cal'] = 0;
						$roominfo['room_id'] =$room['room_id'];
						$roominfo['hotel_id'] = $hotelid;
						$roominfo['owner_id'] = $userid;
						$roominfo['individual_channel_id']= '0';
						insert_data(TBL_UPDATE, $roominfo);
					}
				

                }

                $ChannelsInfo .='Correctly updated hoteratus calendar <br>';
			}
			else if($channelid==1)
			{
				 $room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->row_array();

                     $rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              

                      $this->load->model("expedia_model");
                        $this->expedia_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
			}
			else if($channelid==2)
			{

                           
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->row_array();
                     $rate_conversion = $room_mapping['rate_conversion'];

                       if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }

                        $chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row()->xml_type;

                        if($chk_allow==2 || $chk_allow==3)
                        {
                            $this->booking_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
                        }         
			}
			else if($channelid==9)
			{
				$room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->row_array();

                     	$rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              

                      $this->load->model("airbnb_model");
                        $this->airbnb_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
			
			}
			else if($channelid==19)
			{
				$room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->row_array();

                     	$rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              

                      $this->load->model("agoda_model");
                        $this->agoda_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
			
			}
		}


		return $ChannelsInfo;
	}
	function savesubRoomInfo($room)
	{	
	

		$userid=user_id();
		$hotelid=hotel_id();
		$ChannelsInfo='';
		$ChannelsErros='';
		foreach ($room['channelids'] as  $channelid) {
			if ($channelid==0) {

				foreach ($room['separate'] as  $date) {
					# code...
				
					$info= get_data('room_rate_types_base',array('individual_channel_id'=>'0','room_id'=>$room['room_id'],'rate_types_id'=>$room['ratetypeid'],'separate_date'=>date('d/m/Y',strtotime($date)),'hotel_id'=>$hotelid))->row_array();
				

					//datos de informacion
					
					if(@$room['availability']!='')
					{
						$roominfo['availability'] =$room['availability'];
						if(@$room['availability']=='0')
						{
							$roominfo['stop_sell'] ="1";
							$roominfo['open_room']='0';
						}
					}
					if(@$room['price']!='')
					{
						$roominfo['price'] =$room['price'];   
						$roominfo['PriceRevenue']= $room['price'];                     
					}
					if(@$room['minimumstay']!='')
					{
						$roominfo['minimum_stay'] =$room['minimumstay'];
					}
					if(isset($room['cta'])!='')
					{
						$roominfo['cta'] =$room['cta'];
					}

					if(isset($room['ctd'])!='')
					{
						$roominfo['ctd'] =$room['ctd'];
					}

					if(isset($room['stops'])!='')
					{
						$roominfo['stop_sell'] =($room['stops']!=1?'0':'1');
						$roominfo['open_room'] =($room['stops']==1?'0':'1');

						if(@$room['availability']=='0')
						{
							$roominfo['stop_sell'] ="1";
							$roominfo['open_room']='0';
						}
					}

					if(count($info)!=0)
					{ 
						update_data('room_rate_types_base',$roominfo,array("hotel_id"=>$hotelid,"individual_channel_id"=>0,"separate_date"=>date('d/m/Y',strtotime($date)),'room_id'=>$room['room_id'],'rate_types_id'=>$room['ratetypeid']));					}
					else
					{   
						$roominfo['separate_date'] = date('d/m/Y',strtotime($date));
						$roominfo['trigger_cal'] = 0;
						$roominfo['room_id'] =$room['room_id'];
						$roominfo['rate_types_id'] =$room['ratetypeid'];
						$roominfo['hotel_id'] = $hotelid;
						$roominfo['owner_id'] = $userid;
						$roominfo['individual_channel_id']= '0';
						insert_data('room_rate_types_base', $roominfo);
						

					}

                }

                $ChannelsInfo .='Correctly Ratetype update hoteratus calendar <br>';
			}
			else if($channelid==1)
			{
				 $room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>$room['ratetypeid'],'enabled'=>'enabled'))->row_array();

                     $rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              
                        if(count($room_mapping )>0)
                        {
	                        $this->load->model("expedia_model");
	                       	 $this->expedia_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
                        }
                      	
			}
			else if($channelid==2)
			{

                           
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>$room['ratetypeid'],'enabled'=>'enabled'))->row_array();

                     	if(count($room_mapping )>0)
                        {
	                     	$rate_conversion = $room_mapping['rate_conversion'];

	                       if(@$room['price']!='')
	                        {
	                            $price = $room['price']*$rate_conversion;                     
	                        }
	                        else
	                        {
	                         $price ="0";   
	                        }

	                        $chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row()->xml_type;

	                        if($chk_allow==2 || $chk_allow==3)
	                        {
	                            $this->booking_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
	                        } 
                        }	        
			}
			else if($channelid==9)
			{
				$room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>$room['ratetypeid'],'enabled'=>'enabled'))->row_array();
				if(count($room_mapping )>0)
                {
                     	$rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              

                      $this->load->model("airbnb_model");
                      $this->airbnb_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
                }  
			
			}
			else if($channelid==19)
			{
				$room_mapping = get_data(MAP,array('hotel_id'=>hotel_id(),'channel_id'=>$channelid,'property_id'=>$room['room_id'],'rate_id'=>$room['ratetypeid'],'enabled'=>'enabled'))->row_array();
				if(count($room_mapping )>0)
                {
                     	$rate_conversion = $room_mapping['rate_conversion'];

                        if(@$room['price']!='')
                        {
                            $price = $room['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
              

                      $this->load->model("agoda_model");
                        $this->agoda_model->bulk_update($room,$room_mapping['import_mapping_id'],$room_mapping['mapping_id'],$price);
                    }
			
			}
		}


		return $ChannelsInfo;
	}
}


