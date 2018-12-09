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
		ini_set('max_execution_time', 60000);

		$countdate=count($_POST['date1Edit']);
		$DatesRange=array();
		$result='';
		
		for ($i=0; $i < $countdate ; $i++) { 
			$DatesRange[$i]['startdate']=$_POST['date1Edit'][$i];
			$DatesRange[$i]['enddate']=$_POST['date2Edit'][$i];
		}
		
		$rooms=$this->cleanArray($_POST['room']);
		$subrooms=$this->cleanArray($_POST['subroom']);
		//var_dump($rooms);
		if($rooms)
		{

			foreach ($rooms as $roomid => $room) {
				
				$room['room_id']=$roomid;
				$room['days']=$_POST['days'];
				if (isset($room['availability'])) {
                                
                    if ($room['availability'] == 0) {
                        $room['stops'] = 1;
                    }
                                
                }



                //confirmar lo de revenue

                foreach ($DatesRange as  $fechas) {


            			$periodo=getDatespecificas($fechas['startdate'],$fechas['enddate'],$_POST['days']);
            		

            			foreach ($periodo['rangos'] as $date) {
            							                		
	                		$room['start_date']=$date['startdate'];
	                		$room['end_date']=$date['enddate'];
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
		if(is_array($subrooms))
		{
			foreach ($subrooms as $roomid => $ratetype) {
				
				

				
				$subroom['room_id']=$roomid;
				$subroom['days']=$_POST['days'];
				
        
                //confirmar lo de revenue

                foreach ($DatesRange as  $fechas) {

            			$periodo=getDatespecificas($fechas['startdate'],$fechas['enddate'],$_POST['days']);

            			foreach ($periodo['rangos'] as $date) {
            							                		
	                		$subroom['start_date']=$date['startdate'];
	                		$subroom['end_date']=$date['enddate'];
	                		$subroom['separate']=$date['separate'];

	                   
	                		
	                		foreach ($ratetype as $key=>  $rate) {

	                			$subroom['ratetypeid']=$key;
	                			if (isset($rate['availability'])) {
                                
				                    if ($rate['availability'] == 0) {
				                        $rate['stops'] = 1;
				                    }
				                                
				                }
				               
                				if(isset($rate['price'])!=0 && isset($rate['price']) !='')
			                    {

			                        $this->db->query("Update  room_rate_types_base                                  
			                            set PriceRevenue= ".$rate['price']."
			                            where hotel_id=".hotel_id()."
			                            and room_id=".$subroom['room_id']."
			                            and rate_types_id=".$key."
			                            and individual_channel_id=0
			                            and STR_TO_DATE(separate_date ,'%d/%m/%Y')   between '".$date['startdate']."'  and '".$date['enddate']."' " );
			                    }
			                    

			                    if(isset($rate['availability']) && !isset($rate['price']))
			                    {
			                        $revenueprice=  @$this->db->query("select  case when ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-".$rate['availability'].")) + PriceRevenue > maximun then maximun else ((((percentage/100)*PriceRevenue)/existing_room_count)*(existing_room_count-".$rate['availability'].")) + PriceRevenue end precio
			                                from
			                                room_rate_types_base b 
			                                left join manage_property a on a.property_id=b.room_id 
			                                where 
			                                a.hotel_id =".hotel_id()."  
			                                and b.hotel_id=".hotel_id()."   
			                                AND a.revenuertatus =1 
			                                and a.property_id=".$subroom['room_id']."
			                                and b.rate_types_id=".$key."
			                                and b.individual_channel_id=0
			                                and STR_TO_DATE(b.separate_date ,'%d/%m/%Y')   between '".$date['startdate']."'  and '".$date['enddate']."' " )->row_array()['precio'];
			                        
			                    }

			                    $subroom['channelids'] =  $_POST['channelid'];

			                    $rateinfo=array_merge($subroom,$rate);

			                    $result .= $this->bulkupdate_model->savesubRoomInfo($rateinfo);
			                    return;
	                		}	
	                			
		                }    
                }
                        
			}
		}
		$this->session->set_flashdata('bulk_success', $result );
	
	}
	function bulkUpdateAnalisis()
	{	

//var_dump($jsoninfo['2018-10-01']['minimum']);

		 $rules=$this->db->query("select * from TarifaSugerida")->result_array();
		/*minimum,occupation,roomavailable,roomid*/

		$this->is_login();
		ini_set('max_execution_time', 60000);

		$Hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$_POST['channel_id']." and active=1 and main=1")->row_array();
		$jsoninfo=json_decode($_POST['jsoninfo'],true);
		
		$countdate=count($_POST['date1Edit']);
		$DatesRange=array();
		$result='';
		
		for ($i=0; $i < $countdate ; $i++) { 
			$DatesRange[$i]['startdate']=$_POST['date1Edit'][$i];
			$DatesRange[$i]['enddate']=$_POST['date2Edit'][$i];
		}
		

        foreach ($DatesRange as  $fechas) {


    			$periodo=getDatespecificas($fechas['startdate'],$fechas['enddate'],array(1,2,3,4,5,6,7));
    		

    			foreach ($periodo['rangos'] as $date) {
    							                		

    				foreach ($date['separate'] as $value) {
    					
    					  	if($jsoninfo[$value]['mainprice']==0 || $jsoninfo[$value]['roomavailable']==0)
				              {
				                $money=0;
				              }
				              else if($jsoninfo[$value]['mainprice']<=$jsoninfo[$value]['minimum'])
				              {		
				              		$per=0;
					                if($jsoninfo[$value]['avg']==0)
					                {
					                  foreach ($rules as  $rule) {
					                    if(($rule['Min']<=round($jsoninfo[$value]['occupation'],2) && $rule['Max']>=round($jsoninfo[$value]['occupation'],2)) && $rule['Sold']==1)
					                    {
					                      $per=$rule['Percentage']/100;
					                      break;
					                    }
					                  }

					                  $money=$jsoninfo[$value]['minimum']+($jsoninfo[$value]['minimum']*$per);
					    
					                }
					                else
					                {
					                  $money=0;
					                }
				              }
				              else {

				                 $per=0;
					                  foreach ($rules as  $rule) {
					                    if(($rule['Min']<=round($jsoninfo[$value]['occupation'],2) && $rule['Max']>=round($jsoninfo[$value]['occupation'],2)) && $rule['Sold']==0)
					                    {
					                      $per=$rule['Percentage']/100;
					                      break;
					                    }
					                  }
					                  # TarifaSugeridaId, Min, Max, Percentage, Sold
					                $money=round($jsoninfo[$value]['minimum']+($jsoninfo[$value]['minimum']*$per),2);
					                //round($roominfo[$datecurrent]['occupation'],2);
					                
					              }
									                
				              

				              if($money>0)
				              {
				              		 $this->db->query("Update  room_update                                  
			                            set PriceRevenue=$money ,
			                            price =$money
			                            where hotel_id=".hotel_id()."
			                            and room_id=".$jsoninfo[$value]['roomid']."
			                            and individual_channel_id=0
			                            and STR_TO_DATE(separate_date ,'%d/%m/%Y')= '$value' " );
				              }

    					
    				}

                }        
        }
                    
          
          

		echo('Main Channel Updated');
		
	
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
	function verifysincro()
	{
		echo getsincro();
	}
        
        function savechangeReservation(){
            $startDate = date('d/m/Y', strtotime($this->input->post('startDate')));
            $endDate = date('d/m/Y', strtotime($this->input->post('endDate')));
            
            $rows = array('RoomNumber' => $this->input->post('roomnumber'),
                          'room_id' => $this->input->post('room_id'),
                          'start_date' => $startDate,
                          'end_date' => $endDate,
                          'price_details' => $this->input->post('price'));

            update_data('manage_reservation', $rows, 
                    array('reservation_id'=>$this->input->post('reservation')));
            
            $latToomUpdateId = explode(",", substr($this->input->post('latToomUpdateId'), 0, -1));
            $this->db->set('availability', 'availability+1', FALSE);
            $this->db->where_in('room_update_id', $latToomUpdateId);
            $this->db->update('room_update');
                        
            $room_update_id = explode(",", substr($this->input->post('room_update_id'), 0, -1));
            $this->db->set('availability', 'availability-1', FALSE);
            $this->db->where_in('room_update_id', $room_update_id);
            $this->db->update('room_update');   
        }
                
	function savechangecalendar()
	{
        
        $name=$_POST['name'];
        $value=$_POST['value'];
        $pk=explode(',',$_POST['pk']);

        $date=$pk[0];
        $roomid=$pk[1];
        $rateid=$pk[2];
        $hotelid=$pk[3];

      	$datos[$name]=$value;
      	$condicion['hotel_id']=$hotelid;
      	$condicion['individual_channel_id']=0;
      	$condicion['room_id']=$roomid;
      	$condicion['separate_date']=date('d/m/Y',strtotime($date));

      	if($name=='price')
      	{
      		$datos['PriceRevenue']=$value;
      	}else if($name=='availability' && $value==0)
      	{
      		$datos['stop_sell']=1;
      	}
		//print_r($datos);
		//print_r($condicion);
      	if ($rateid==0) {
      		update_data('room_update',$datos,$condicion);
      	}
      	else
      	{
      		update_data('room_rate_types_base',$datos,$condicion);
      	}

      	$this->sincronizar($pk);


	}
	function sincronizar($info)
    {
       
		$canales=$this->db->query("select channel_id from roommapping where property_id=".$info[1])->result_array();

		$inicialdate=$info[0];
		$FinalDate=$info[0];
		$hotelid=$info[3];
		$userid=user_id();

		foreach ($canales as $value) {
			$Channelid=$value['channel_id'];
		    if ($Channelid == 36) {
		        $this->load->model("despegar_model");
		        $this->despegar_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
		        
		        
		    } elseif ($Channelid == 1) {
		        $this->load->model("expedia_model");
		        $this->expedia_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid,'All');
		        
		    } elseif ($Channelid == 2) {
		        
		        $this->load->model("booking_model");
		        $this->booking_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);


		    } elseif ($Channelid == 9) {
		        
		        $this->load->model("airbnb_model");
		        $FinalDate=date('y-m-d',strtotime($FinalDate.'+1 days'));
		        $this->airbnb_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
		    }elseif ($Channelid == 19) {
		        
		        $this->load->model("agoda_model");
		        $this->agoda_model->SincroCalender($inicialdate, $FinalDate,$userid, $hotelid);
		    }
		
        }
        
	}
	
}