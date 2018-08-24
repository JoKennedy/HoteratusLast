<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class bulkupdate extends Front_Controller
{
	 public function __construct()
    {
        
        parent::__construct();
        
        //load base libraries, helpers and models
        $this->load->model('bulkupdate_model');
        
      
        
    }
	function is_login()
    { 
        if(!user_id())
        redirect(base_url());
        return;
    }

	function viewBulkUpdate()
	{
		$this->is_login();
    	$data['page_heading'] = 'Bulk Update';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['Rooms']= get_data('manage_property',array('hotel_id'=>hotel_id()))->result_array();
		$data['AllChannelConected']=$this->db->query("SELECT a.*,b.channel_name 
					FROM user_connect_channel a
					left join manage_channel b on a.channel_id=b.channel_id
					where hotel_id=".hotel_id()." order by b.channel_name")->result_array();
		$this->views('channel/bulkupdate',$data);
	}


	function bulkUpdateProcess()
	{	

		 $this->is_login();

		$countdate=count($_POST['date1Edit']);
		$DatesRange=array();
		$result='';
		
		for ($i=0; $i < $countdate ; $i++) { 
			$DatesRange[$i]['startdate']=$_POST['date1Edit'][$i];
			$DatesRange[$i]['enddate']=$_POST['date2Edit'][$i];
		}
		

		$rooms=cleanArray($_POST['room']);
	
		if(count($rooms)>0)
		{
			foreach ($rooms as $roomid => $room) {
				
				$room['room_id']=$roomid;

				if (isset($room['availability'])) {
                                
                    if ($room['availability'] == 0) {
                        $room['stops'] = 1;
                    }
                                
                }
                //confirmar lo de revenue

                foreach ($DatesRange as  $fechas) {


            			$periodo=getDatespecificas($fechas['startdate'],$fechas['enddate'],$_POST['days']);
            		

            			foreach ($periodo['rangos'] as $date) {
            				
            				
	          			                		
	                		$room['startdate']=$date['startdate'];
	                		$room['enddate']=$date['enddate'];
	                		$room['separate']=$date['separate'];
	                	
		                    if(isset($room['price'])!=0 && isset($room['price']) !='')
		                    {

		                        $this->db->query("Update  room_update                                  
		                            set PriceRevenue= ".$room['price']."
		                            where hotel_id=".hotel_id()."
		                            and room_id=".$room['room_id']."
		                            and individual_channel_id=0
		                            and STR_TO_DATE(separate_date ,'%d/%m/%Y')   between '".$date['startdate']."'  and '".$date['enddate']."' " );
		                    }

		                    if(isset($room['availability']) && !isset($room['price']))
		                    {
		                        $revenueprice=  @$this->db->query("select  case when ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-".$room['availability'].")) + PriceRevenue > maximun then maximun else ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-".$room['availability'].")) + PriceRevenue end precio
		                                from
		                                room_update b 
		                                left join manage_property a on a.property_id=b.room_id 
		                                where 
		                                a.hotel_id =".hotel_id()."  
		                                and b.hotel_id=".hotel_id()."   
		                                AND a.revenuertatus =1 
		                                and a.property_id=".$room['room_id']."
		                                and b.individual_channel_id=0
		                                and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between '".$date['startdate']."'  and '".$date['enddate']."' " )->row_array()['precio'];
		                    }

		                    $room['channelids'] =  $_POST['channelid'];


		                    $result .= $this->bulkupdate_model->saveRoomInfo($room);

		                   
		                   
		                }    
	                
                }
                        

              

			}
		}

		$this->session->set_flashdata('bulk_success', $result );
	
	}

	function verifysincro()
	{
		echo getsincro();
	}

	
}