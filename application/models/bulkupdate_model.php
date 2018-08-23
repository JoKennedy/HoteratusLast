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
						$roominfo['stop_sell'] =($room['stops']==2?'0':'1');
						$roominfo['open_room'] =($room['stops']==1?'0':'1');

						if(@$room['availability']=='0')
						{
							$roominfo['stop_sell'] ="1";
							$roominfo['open_room']='0';
						}
					}



					if(count($info)!=0)
					{ 
						update_data(TBL_UPDATE,$roominfo,array("hotel_id"=>$hotelid,"individual_channel_id"=>0,"separate_date"=>date('d/m/Y',strtotime($date)),'room_id'=>$room['room_id']));
					}
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
		}


		return $ChannelsInfo;
	}
	
}