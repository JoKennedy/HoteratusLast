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

				foreach ($room['separate'] as  $date) {
					# code...
				
					$info= get_data(TBL_UPDATE,array('individual_channel_id'=>'0','room_id'=>$room['room_id'],'separate_date'=>date('d/m/Y',strtotime($date)),'hotel_id'=>$hotelid))->row_array();
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
						update_data(TBL_UPDATE,$roominfo,array("hotel_id"=>$hotelid,"individual_channel_id"=>0,"separate_date"=>date('d/m/Y',strtotime($date)),'room_id'=>$room['room_id']));					}
					else
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
	
}


