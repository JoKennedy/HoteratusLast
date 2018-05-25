<?php  
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class inventory_model extends CI_Model
{

	private $currency_code; 
	 
    public function __construct()
    {
        
        parent::__construct();

		if(current_user_type())
		{
			$hotel_detail			=	get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency;
		
            if  ($hotel_detail !=0)   {    
			$this->currency_code	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->currency_code;
            
            }

		}
		
    }
	
    function count_room($property_id)
    {
        $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
        where(array('property_type'=>$property_id))->count_all_results();
        return $count;
    }
    
    function mailsettings()
    {     
        $this->load->library('email');  
        $config['wrapchars'] = 76;  // Character count to wrap at.
        $config['priority'] = 1;  // Character count to wrap at.
        $config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
        $config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
        $this->email->initialize($config);      
    } 
          
    function save($product, $options=false, $categories=false)
    {


         if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
            {
                $userID = user_id();
            }
            else if(user_type()=='2')
            {
                $userID = owner_id();
            }

       
       
            $start_date = date('Y-m-d',strtotime(str_replace('/','-',$product['start_date'])));
            $end_date = date('Y-m-d',strtotime(str_replace('/','-',$product['end_date'])));
           



        if(@$product['days'] != "")
        {
            $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,@$product['days']);
        }else{
            $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,'1,2,3,4,5,6,7');
        }

        $all_channel_id = $this->input->post('channel_id');

        //Para Canal Local
         if($this->input->post('maincal') != '')
            {
                foreach($period as $date)
                {
                    $available1= get_data(TBL_UPDATE,array('individual_channel_id'=>'0','room_id'=>$product['room_id'],'separate_date'=>$date,'hotel_id'=>hotel_id()))->row_array();

                    
                        if(count($available1)!=0)
                        { 
                            if(@$product['availability']!='')
                            {
                                $product_avail['availability'] =$product['availability'];
                                if(@$product['availability']=='0')
                                 {
                                     $product_avail['stop_sell'] ="1";
                                }
                                else
                                {
                                    $product_avail['stop_sell'] ="0";
                                }
                            }
                            
                            if(@$product['price']!='')
                            {
                                $product_avail['price'] =$product['price'];                      
                            }

                            if(@$product['minimum_stay']!='')
                            {
                                $product_avail['minimum_stay'] =$product['minimum_stay'];
                            }

                            if(isset($product['cta'])!='')
                            {
                                $product_avail['cta'] =$product['cta'];
                            }

                            if(isset($product['ctd'])!='')
                            {
                                $product_avail['ctd'] =$product['ctd'];
                            }

                            if(isset($product['stop_sell'])!='')
                            {
                                $product_avail['stop_sell'] =$product['stop_sell'];
                                $product_avail['open_room'] ="0";

                                if(@$product['availability']=='0')
                                {
                                     $product_avail['stop_sell'] ="1";
                                     $product_avail['open_room'] ="0";
                                }
                            }
                            elseif(isset($product['open_room'])!='')
                            {
                                $product_avail['stop_sell']='0';
                                $product_avail['open_room']='1';
                            }

                            $product_avail['separate_date'] = $date;
                            $product_avail['trigger_cal'] = 2;

                            $this->db->where('owner_id', $userID );
                            $this->db->where('hotel_id', hotel_id());
                            $this->db->where('room_id', $product['room_id']);
                            $this->db->where('separate_date', $date);
                            $this->db->where('individual_channel_id', '0');
                            $this->db->update(TBL_UPDATE, $product_avail);
                        }
                        else
                        {
                            $product['owner_id'] = $userID;
                            $product['trigger_cal'] = 2;
                            $product['hotel_id'] = hotel_id();
                            $product['separate_date'] = $date;
                            $product['individual_channel_id']= '0';
                            $this->db->insert(TBL_UPDATE, $product);
                        }
                }
            }

        //Todos los canales Disponibles calendario Local
            if($all_channel_id)
            {
                foreach($all_channel_id as $channel_id)
                {
                    if($channel_id==36)
                    {   
                     
                      $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->result();

                      if ($room_mapping)
                      {
                       $rate_conversion = $room_mapping[0]->rate_conversion;

                       if(@$product['price']!='')
                        {
                            $price = $product['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
                       
                        foreach($period as $date)
                        {
                            $available1= get_data(TBL_UPDATE,array('individual_channel_id'=>'36','room_id'=>$product['room_id'],'separate_date'=>$date))->row_array();
                            
                            if(count($available1)!=0)
                                { 
                                    if(@$product['availability']!='')
                                    {
                                        $product_avail['availability'] =$product['availability'];
                                          if(@$product['availability']=='0')
                                             {
                                                 $product_avail['stop_sell'] ="1";
                                            }
                                            else
                                            {
                                                $product_avail['stop_sell'] ="0";
                                            }
                                    }
                                    
                                    if(@$product['price']!='')
                                    {
                                        $product_avail['price'] =$product['price']*$rate_conversion;                      
                                    }

                                    if(@$product['minimum_stay']!='')
                                    {
                                        $product_avail['minimum_stay'] =$product['minimum_stay'];
                                    }

                                    if(isset($product['cta'])!='')
                                    {
                                        $product_avail['cta'] =$product['cta'];
                                    }

                                    if(isset($product['ctd'])!='')
                                    {
                                        $product_avail['ctd'] =$product['ctd'];
                                    }

                                    if(isset($product['stop_sell'])!='')
                                    {
                                            $product_avail['stop_sell'] =$product['stop_sell'];

                                          if(@$product['availability']=='0')
                                             {
                                                 $product_avail['stop_sell'] ="1";
                                            }
                                    }

                                    if(isset($product['open_room'])!='')
                                    {
                                        $product_avail['stop_sell']='0';
                                        
                                    }

                                    $product_avail['separate_date'] = $date;
                                    $product_avail['trigger_cal'] = 0;

                                    $this->db->where('owner_id', $userID );
                                    $this->db->where('hotel_id', hotel_id());
                                    $this->db->where('room_id', $product['room_id']);
                                    $this->db->where('separate_date', $date);
                                    $this->db->where('individual_channel_id', $channel_id);
                                    $this->db->update(TBL_UPDATE, $product_avail);
                                }
                                else
                                {
                                    $product['owner_id'] = $userID;
                                    $product['trigger_cal'] = 0;
                                    $product['hotel_id'] = hotel_id();
                                    $product['separate_date'] = $date;
                                    $product['individual_channel_id']= "$channel_id";
                                    $this->db->insert(TBL_UPDATE, $product);
                                }
                        }

                        $this->load->model("despegar_model");
                        $this->despegar_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                      }
                     
                    }
                    if($channel_id==1)
                    {
                        
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->result();
                     $rate_conversion = $room_mapping[0]->rate_conversion;

                        if(@$product['price']!='')
                        {
                            $price = $product['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }
                      if ($room_mapping)
                      {
                           
                           
                            foreach($period as $date)
                            {
                                $available1= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$date))->row_array();
                                
                                if(count($available1)!=0)
                                    { 
                                        if(@$product['availability']!='')
                                        {
                                            $product_avail['availability'] =$product['availability'];
                                              if(@$product['availability']=='0')
                                                 {
                                                     $product_avail['stop_sell'] ="1";
                                                }
                                                else
                                                {
                                                    $product_avail['stop_sell'] ="0";
                                                }
                                        }
                                        
                                        if(@$product['price']!='')
                                        {
                                            $product_avail['price'] =$product['price']*$rate_conversion;                      
                                        }

                                        if(@$product['minimum_stay']!='')
                                        {
                                            $product_avail['minimum_stay'] =$product['minimum_stay'];
                                        }

                                        if(isset($product['cta'])!='')
                                        {
                                            $product_avail['cta'] =$product['cta'];
                                        }

                                        if(isset($product['ctd'])!='')
                                        {
                                            $product_avail['ctd'] =$product['ctd'];
                                        }

                                        if(isset($product['stop_sell'])!='')
                                        {
                                                $product_avail['stop_sell'] =$product['stop_sell'];

                                              if(@$product['availability']=='0')
                                                 {
                                                     $product_avail['stop_sell'] ="1";
                                                }
                                        }

                                        if(isset($product['open_room'])!='')
                                        {
                                            $product_avail['stop_sell']='0';
                                            
                                        }

                                        $product_avail['separate_date'] = $date;
                                        $product_avail['trigger_cal'] = 0;

                                        $this->db->where('owner_id', $userID );
                                        $this->db->where('hotel_id', hotel_id());
                                        $this->db->where('room_id', $product['room_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->where('individual_channel_id', $channel_id);
                                        $this->db->update(TBL_UPDATE, $product_avail);
                                    }
                                    else
                                    {
                                        $product['owner_id'] = $userID;
                                        $product['trigger_cal'] = 0;
                                        $product['hotel_id'] = hotel_id();
                                        $product['separate_date'] = $date;
                                        $product['individual_channel_id']= "$channel_id";
                                        $this->db->insert(TBL_UPDATE, $product);
                                    }
                            }

                      }

                      $this->load->model("expedia_model");
                        $this->expedia_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                    }
                    if($channel_id==2)
                    {
                           
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->result();
                     $rate_conversion = $room_mapping[0]->rate_conversion;

                       if(@$product['price']!='')
                        {
                            $price = $product['price']*$rate_conversion;                     
                        }
                        else
                        {
                         $price ="0";   
                        }

                      if ($room_mapping)
                      {
                           
                           
                            foreach($period as $date)
                            {
                                $available1= get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$date))->row_array();
                                
                                if(count($available1)!=0)
                                    { 
                                        if(@$product['availability']!='')
                                        {
                                            $product_avail['availability'] =$product['availability'];
                                              if(@$product['availability']=='0')
                                                 {
                                                     $product_avail['stop_sell'] ="1";
                                                }
                                                else
                                                {
                                                    $product_avail['stop_sell'] ="0";
                                                }
                                        }
                                        
                                        if(@$product['price']!='')
                                        {
                                            $product_avail['price'] =$product['price']*$rate_conversion;                      
                                        }

                                        if(@$product['minimum_stay']!='')
                                        {
                                            $product_avail['minimum_stay'] =$product['minimum_stay'];
                                        }

                                        if(isset($product['cta'])!='')
                                        {
                                            $product_avail['cta'] =$product['cta'];
                                        }

                                        if(isset($product['ctd'])!='')
                                        {
                                            $product_avail['ctd'] =$product['ctd'];
                                        }

                                        if(isset($product['stop_sell'])!='')
                                        {
                                                $product_avail['stop_sell'] =$product['stop_sell'];

                                              if(@$product['availability']=='0')
                                                 {
                                                     $product_avail['stop_sell'] ="1";
                                                }
                                        }

                                        if(isset($product['open_room'])!='')
                                        {
                                            $product_avail['stop_sell']='0';
                                            
                                        }

                                        $product_avail['separate_date'] = $date;
                                        $product_avail['trigger_cal'] = 0;

                                        $this->db->where('owner_id', $userID );
                                        $this->db->where('hotel_id', hotel_id());
                                        $this->db->where('room_id', $product['room_id']);
                                        $this->db->where('separate_date', $date);
                                        $this->db->where('individual_channel_id', $channel_id);
                                        $this->db->update(TBL_UPDATE, $product_avail);
                                    }
                                    else
                                    {
                                        $product['owner_id'] = $userID;
                                        $product['trigger_cal'] = 0;
                                        $product['hotel_id'] = hotel_id();
                                        $product['separate_date'] = $date;
                                        $product['individual_channel_id']= "$channel_id";
                                        $this->db->insert(TBL_UPDATE, $product);
                                    }
                            }

                            $chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row()->xml_type;

                            if($chk_allow==2 || $chk_allow==3)
                            {
                                $this->booking_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                            }
                      }

                      
                    }

                }
            }
       
       



    }



    function subsave($product=false, $options=false, $categories=false)
    {
          

    
        $sub_roomm  = $this->input->post('room');

        $clean=$this->cleanArray($sub_roomm);

      
        if(is_array($clean))
        {
            $sub_roomd['start_date'] = $this->input->post('start_date');
            
            $sub_roomd['end_date'] = $this->input->post('end_date');
            
            if($this->input->post('days')!='')
            {
                $sub_rooms['days']    = implode(',',$this->input->post('days'));
            }
            else
            {
                $sub_rooms['days']    = ('1,2,3,4,5,6,7');
            }
            
            if($this->input->post('channel_id')!="")
            {
                $channel_id = $this->input->post('channel_id');
                $up_channel_id = $this->input->post('channel_id');
            }
            else
            {
                $channel_id='';
                $up_channel_id='';
            }
            
            $up_days =  explode(',',$sub_rooms['days']);
    
            $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $up_end_date = date('d.m.Y',strtotime(str_replace('/','-',$sub_roomd['end_date'])));

            
            $re_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $re_end_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['end_date'])));
            
            $hotelbed_start = str_replace('-', '', $re_sart_date);
            $hotelbed_end = str_replace('-', '', $re_end_date);
            
            $datetime1 = new DateTime($re_sart_date);
            $datetime2 = new DateTime($re_end_date);
            $interval = $datetime1->diff($datetime2);
            $number_of_days = $interval->format('%a%')+1;
            $channel_bnow = '';
            $channel_travel = "";
            $channel_wbeds = "";


            if($channel_id!='')
            {
				if (($key = array_search('17', $channel_id)) !== false) 
				{
					unset($channel_id[$key]);
					$channel_bnow = 'bnow';
				}
				
                if(($key = array_search('15', $channel_id)) !== false)
                {
                    unset($channel_id[$key]);
                    $channel_travel = 'travel';
                }

                if(($key = array_search('14', $channel_id)) !== false)
                {
                    unset($channel_id[$key]);
                    $channel_wbeds = 'wbeds';
                }
                foreach($channel_id as $con_channel)
                {

                    foreach($clean as $id=>$room)
                    {
                        $sub_rooms['room_id']   = $id;
                        
                        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$id))->row();
                        if(count($room_details)!= 0){
                            if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                        }else{
                            $meal_plan = 0;
                        }
                        foreach($room as $guest_count=>$type)
                        {
                             
                            $sub_rooms['guest_count'] = $guest_count;
                            
                            foreach($type as $refun=>$value)
                            {
                                if(@$value['cta'] == 2){
                                    $value['cta'] = 0;
                                }

                                if(@$value['ctd'] == 2){
                                    $value['ctd'] = 0;
                                }
                                if(isset($value['open_room'])=='')
                                {
                                    if(isset($value['stop_sell'])!='')
                                    {
                                        $stop_sale =$value['stop_sell'];
                                    }
                                    elseif(isset($value['stop_sell'])=='')
                                    {
                                        $stop_sale ='0';
                                    }
                                }
                                else if(isset($value['open_room'])!='')
                                {
                                    $stop_sale ='remove';
                                }
                                
                                if(in_array('1', $up_days)) 
                                {
                                    if(isset($value['cta']) =='' || $value['cta'] == '0')
                                    {
                                        $monday_cta ='0';
                                    }
                                    else
                                    {
                                        $monday_cta ='1';
                                    }
                                    $exp_sun = 'true';
                                }
                                else 
                                {
                                    $monday_cta ='1';
                                    $exp_sun = 'false';
                                }
                                if(in_array('2', $up_days)) 
                                {
                                    if(isset($value['cta']) =='' || $value['cta'] == '0')
                                    {
                                        $tuesday_cta ='0';
                                    }
                                    else
                                    {
                                        $tuesday_cta ='1';
                                    }
                                    $exp_mon = 'true';
                                }
                                else 
                                {
                                    $tuesday_cta ='1';
                                    $exp_mon = 'false';
                                }
                                if(in_array('3', $up_days)) 
                                {
                                    if(isset($value['cta']) =='' || $value['cta'] == '0')
                                    {
                                        $wednesday_cta ='0';
                                    }
                                    else
                                    {
                                        $wednesday_cta ='1';
                                    }
                                    $exp_tue = 'true';
                                }
                                else 
                                {
                                    $wednesday_cta ='1';
                                    $exp_tue = 'false';
                                }
                                if(in_array('4', $up_days)) 
                                {
                                    if(isset($value['cta'])=='' || $value['cta'] == '0')
                                    {
                                        $thursday_cta = '0';
                                    }
                                    else
                                    {
                                        $thursday_cta ='1';
                                        
                                    }
                                    $exp_wed = 'true';
                                }
                                else
                                 {
                                    $thursday_cta ='1';
                                    $exp_wed = 'false';
                                }
                                if(in_array('5', $up_days)) 
                                {
                                    if(isset($value['cta'])=='' || $value['cta'] == '0')
                                    {
                                        $friday_cta = '0';
                                    }
                                    else
                                    {
                                        $friday_cta ='1';
                                    }
                                    $exp_thur = 'true';
                                }
                                else 
                                {
                                    $friday_cta ='1';
                                    $exp_thur = 'false';
                                }
                                if(in_array('6', $up_days)) 
                                {
                                    if(isset($value['cta'])=='' || $value['cta'] == '0')
                                    {
                                        $saturday_cta = '0';
                                    }
                                    else
                                    {
                                        $saturday_cta ='1';
                                    }
                                    $exp_fri = 'true';
                                }
                                else 
                                {
                                    $saturday_cta ='1';
                                    $exp_fri = 'false';
                                }
                                if(in_array('7', $up_days)) 
                                {
                                    if(isset($value['cta'])=='' || $value['cta'] == '0')
                                    {
                                        $sunday_cta = '0';
                                    }
                                    else
                                    {
                                        $sunday_cta ='1';
                                        
                                    }
                                    $exp_sat = 'true';
                                }
                                else 
                                {
                                    $sunday_cta ='1';
                                    $exp_sat = 'false';
                                }
                                if($stop_sale!='remove')
                                {
                                    if($stop_sale!='1')
                                    {
                                        if(@$value['availability']!='')
                                        {
                                            $ch_update_avail = trim(str_repeat('='.$value['availability'].',',$number_of_days),',');
                                            $exp_available = @$value['availability'];
                                        }
                                        else
                                        {
                                            $ch_update_avail = '';
                                            $exp_available = '';
                                        }
                                    }
                                    else if($stop_sale=='1')
                                    {
                                        $ch_update_avail=trim(str_repeat('=-100'.',',$number_of_days),',');
                                        $exp_available = '';
                                    }
                                    else
                                    {
                                        $ch_update_avail='';
                                        $exp_available = '';
                                    }
                                }
                                elseif($stop_sale=='remove')
                                {
                                    $ch_update_avail=trim(str_repeat('=1'.',',$number_of_days),',');
                                }
                                
                                if(@$value['minimum_stay']!='')
                                {
                                    $minimum_stay = $value['minimum_stay'];
                                }
                                else
                                {
                                    $minimum_stay = 0;
                                }
                                
                                if(@$value['non_refund_amount']!='')
                                {
                                    $value_price = $value['non_refund_amount'];
                                }
                                elseif(@$value['refund_amount']!='')
                                {
                                    $value_price = $value['refund_amount'];
                                }
                                else
                                {
                                    $value_price = '0';
                                }
                                $original_price = $value_price;
                                if(@$value['cta'] == "0" || @$value['cta'] != ""){
                                    $exp_cta = "false";
                                }else if(@$value['cta'] == "1"){
                                    $exp_cta = "true";
                                }
                                if(@$value['ctd'] == "0" || @$value['ctd'] != ""){
                                    $exp_ctd = "false";
                                }else if(@$value['ctd'] == "1"){
                                    $exp_ctd = "true";
                                }
                                $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel,'property_id'=>$id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->count_all_results();
                                

                                if($count!=0)
                                {
                                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel,'property_id'=>$id,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->result();
                                    
                                    if($room_mapping)
                                    {
                                        foreach($room_mapping as $room_value)
                                        {
                                            if($value_price != '0'){
                                                if($room_value->rate_conversion != "1"){
                                                    $rate_converted = 1;
                                                    if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                                        $value_price = $value_price * $room_value->rate_conversion;
                                                    }elseif (strpos($room_value->rate_conversion, ',') !== FALSE) {
                                                        $mul = str_replace(',', '.', $room_value->rate_conversion);
                                                        $value_price = $value_price * $mul;
                                                    }else if(is_numeric($value_price)){
                                                        $value_price = $value_price * $room_value->rate_conversion;
                                                    }
                                                }
                                            }
                                            if($room_value->channel_id=='11')
                                            {
                                                if($value_price!='0' || $ch_update_avail!='')
                                                {
                                                    
                                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();
                                                if($ch_details->mode == 0){
                                                    $urls = explode(',', $ch_details->test_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $reco[$path[0]] = $path[1];
                                                    }
                                                }else if($ch_details->mode == 1){
                                                    $urls = explode(',', $ch_details->live_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $reco[$path[0]] = $path[1];
                                                    }
                                                }  
                                                
                                                $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                                                
                                                if($value_price!='0' && $room_value->update_rate=='1')
                                                {
                                                    $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                    
                                                    if($mapping_values)
                                                    {
                                                        $label=$mapping_values['label'];
                                                        $val=$mapping_values['value'];  
                                                        $label_split=explode(",",$label);
                                                        $val_split=explode(",",$val);
                                                        $set_arr=array_combine($label_split,$val_split);
                                                        $i=0;
                                                        $mapping_fields='';
                                                        foreach($set_arr as $k=>$v)
                                                        {
                                                            if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
                                                                if(strpos($v, '+') !== FALSE){
                                                                    $opr = explode('+', $v);
                                                                    if(is_numeric($opr[1])){
                                                                        $ex_price = $value_price + $opr[1];
                                                                    }else if(is_numeric($opr[0])){
                                                                        $ex_price = $value_price + $opr[0];
                                                                    }else{
                                                                        if(strpos($opr[1], '%')){
                                                                            $per = explode('%',$opr[1]);
                                                                            if(is_numeric($per[0])){
                                                                                $per_price = ($value_price * $per[0]) / 100;
                                                                                $ex_price = $value_price + $per_price;
                                                                            }
                                                                        }elseif (strpos($opr[0], '%')) {
                                                                            $per = explode('%',$opr[0]);
                                                                            if(is_numeric($per[0])){
                                                                                $per_price = ($value_price * $per[0]) / 100;
                                                                                $ex_price = $value_price + $per_price;
                                                                            }
                                                                        }
                                                                    }
                                                                }elseif (strpos($v, '-') !== FALSE) {
                                                                    $opr = explode('-', $v);
                                                                    if(is_numeric($opr[1])){
                                                                        $ex_price = $value_price - $opr[1];
                                                                    }elseif (is_numeric($opr[0])) {
                                                                        $ex_price = $value_price - $opr[0];
                                                                    }else{
                                                                        if(strpos($opr[1],'%') !== FALSE){
                                                                            $per = explode('%',$opr[1]);
                                                                            if(is_numeric($per[0])){
                                                                                $per_price = ($value_price * $per[0]) / 100;
                                                                                $ex_price = $value_price - $per_price;
                                                                            }
                                                                        }elseif (strpos($opr[0],'%') !== FALSE) {
                                                                            $per = explode('%',$opr[0]);
                                                                            if(is_numeric($per[0])){
                                                                                $per_price = ($value_price * $per[0]) / 100;
                                                                                $ex_price = $value_price - $per_price;
                                                                            }
                                                                        }
                                                                    }
                                                                }elseif (strpos($v, '%') !== FALSE) {
                                                                    $opr = explode('%', $v);
                                                                    if(is_numeric($opr[1])){
                                                                        $per_price = ($value_price * $opr[1]) / 100;
                                                                        $ex_price = $value_price + $per_price;
                                                                    }elseif (is_numeric($opr[0])) {
                                                                        $per_price = ($value_price * $opr[0]) / 100;
                                                                        $ex_price = $value_price + $per_price;
                                                                    }
                                                                }else{
                                                                    $ex_price = $value_price + $v;
                                                                }
                                                                //echo $k ."=". $ex_price."<br>";
                                                                $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                                            }else{
                                                                $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                                            }
                                                        }
                                                    }
                                                    $url = trim($reco['urate_avail']);
                                                    $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                                    <soap12:Body>
                                                    <UpdateRates xmlns="https://www.reconline.com/">
                                                    <User>'.$ch_details->user_name.'</User>
                                                    <Password>'.$ch_details->user_password.'</Password>
                                                    <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                                    <idSystem>0</idSystem>
                                                    <ForeignPropCode></ForeignPropCode>
                                                    <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                                    <ExcludeRateLevels></ExcludeRateLevels>
                                                    <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                                    <ExcludeRoomTypes></ExcludeRoomTypes>
                                                    <RateType>1</RateType>
                                                    <StartDate>'.$up_sart_date.'</StartDate>
                                                    <EndDate>'.$up_end_date.'</EndDate>
                                                    <SingleOcc>'.$value_price.'</SingleOcc>'.
                                                    $mapping_fields.'
                                                    <Meals>'.$meal_plan.'</Meals>
                                                    <MinStay>'.$minimum_stay.'</MinStay>
                                                    <BlockStay>0</BlockStay>
                                                    <Guarantee>0</Guarantee>
                                                    <Cancel></Cancel>
                                                    <CTAMonday>'.$monday_cta.'</CTAMonday>
                                                    <CTATuesday>'.$tuesday_cta.'</CTATuesday>
                                                    <CTAWednesday>'.$wednesday_cta.'</CTAWednesday>
                                                    <CTAThursday>'.$thursday_cta.'</CTAThursday>
                                                    <CTAFriday>'.$friday_cta.'</CTAFriday>
                                                    <CTASaturday>'.$saturday_cta.'</CTASaturday>
                                                    <CTASunday>'.$sunday_cta.'</CTASunday>
                                                    </UpdateRates>
                                                    </soap12:Body>
                                                    </soap12:Envelope>';
                                                    $headers = array(
                                                                        "Content-type: application/soap+xml; charset=utf-8",
                                                                        "Host:www.reconline.com",
                                                                        "Content-length: ".strlen($xml_post_string),
                                                                        ); 
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                    $ss = curl_getinfo($ch);                
                                                    $response = curl_exec($ch);

                                                    $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                    $xml = simplexml_load_string($xml);
                                                    $json = json_encode($xml);
                                                    $responseArray = json_decode($json,true);
                                                    $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                                    $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                                    if(count($Errorarray)=='0' && count($soapFault)=='0')
                                                    {
                                                        $reconline_price_response = "success";
                                                    }
                                                    else 
                                                    {
                                                        $reconline_price_response = "error";
                                                        if(count($Errorarray)!='0')
                                                        {
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                                        }
                                                        else if(count($soapFault)!='0')
                                                        {      
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                                        }
                                                    }
                                                    curl_close($ch);
                                                }
                                                if($ch_update_avail!='' && $room_value->update_availability=='1')
                                                {
                                                    $url = trim($reco['urate_avail']);
                                                    $xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
                                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                                    <soap12:Body>
                                                    <UpdateAvail xmlns="https://www.reconline.com/"> 
                                                    <User>'.$ch_details->user_name.'</User>
                                                    <Password>'.$ch_details->user_password.'</Password>
                                                    <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                                    <idSystem>0</idSystem>
                                                    <ForeignPropCode></ForeignPropCode>
                                                    <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                                    <ExcludeRateLevels></ExcludeRateLevels>
                                                    <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                                    <ExcludeRoomTypes></ExcludeRoomTypes>
                                                    <AvailMode>0</AvailMode>
                                                    <StartDate>'.$up_sart_date.'</StartDate>
                                                    <Availability>'.$ch_update_avail.'</Availability>
                                                    </UpdateAvail>
                                                    </soap12:Body>
                                                    </soap12:Envelope>';
                                                    $headers_avail = array(
                                                                        "Content-type: application/soap+xml; charset=utf-8",
                                                                        "Host:www.reconline.com",
                                                                        "Content-length: ".strlen($xml_post_string_update),
                                                                        ); 
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
                                                    $ss = curl_getinfo($ch);                
                                                    $response = curl_exec($ch);
                                                    $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                    $xml = simplexml_load_string($xml);
                                                    $json = json_encode($xml);
                                                    $responseArray = json_decode($json,true);
                                                    $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                                    $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                                    if(count($Errorarray)=='0' && count($soapFault)=='0')
                                                    {
                                                        $reconline_availability_response="success";
                                                    }
                                                    else 
                                                    {
                                                        $reconline_price_response = "error";
                                                        if(count($Errorarray)!='0')
                                                        {
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                                        }
                                                        else if(count($soapFault)!='0')
                                                        {      
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                                        }
                                                        return false;
                                                    }
                                                    curl_close($ch);
                                                }
                                            
                                                }   
                                            }
											else if($room_value->channel_id == '5')
											{
                                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                                                if($ch_details->mode == 0){
                                                    $urls = explode(',', $ch_details->test_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $htb[$path[0]] = $path[1];
                                                    }
                                                }else if($ch_details->mode == 1){
                                                    $urls = explode(',', $ch_details->live_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $htb[$path[0]] = $path[1];
                                                    }
                                                }  
                                                    
                                                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                                $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                $maxnum = 99;
                                                if($mapping_values){
                                                    if($mapping_values['label']== "MaximumNoOfDays")
                                                    {
                                                        $maxnum = $mapping_values['value'];                                        
                                                    }
                                                }


                                                $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                                    <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                                    <getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                                    <HSI_ContractInventoryModificationRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                                                    <Language>ENG</Language>
                                                    <Credentials>
                                                        <User>'.$ch_details->user_name.'</User>
                                                        <Password>'.$ch_details->user_password.'</Password>
                                                    </Credentials>
                                                    <Contract>
                                                        <Name>'.$mp_details->contract_name.'</Name>
                                                        <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                                        <Sequence>'.$mp_details->sequence.'</Sequence>
                                                    </Contract>
                                                    <InventoryItem>
                                                        <DateFrom date="'.$hotelbed_start.'"/>
                                                        <DateTo date="'.$hotelbed_end.'"/>
                                                    <RateCode>0</RateCode>';
													if(@$value['availability'] != "" && $room_value->update_availability=='1'){
													if($stop_sale=="0"){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'">';
													}else if($stop_sale =="1"){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'" closed="Y">';
													}else if($stop_sale=='remove'){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'" closed="N">';
													}
													else
													{
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'">';
													}									
												}else{
													
													if($stop_sale=="0")
													{
														$xml_post_string .= '<Room>';
													}
													else if($stop_sale=="1")
													{
														$xml_post_string .= '<Room closed="Y">';
													}
													else if($stop_sale=='remove')
													{
														$xml_post_string .= '<Room closed="N">';
													}
													else
													{
														$xml_post_string .= '<Room>';
													}									
												}
                                                
                                                $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                                if($value_price != "" && $room_value->update_rate == '1')   {
                                                    $xml_post_string .= '<Price><Amount>'.$value_price.'</Amount></Price>';
                                                }
                                                $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                                                 </soapenv:Envelope>';
                                                $headers = array(
                                                "SOAPAction:no-action",
                                                "Content-length: ".strlen($xml_post_string),
                                                ); 
                                                $url = trim($htb['urate_avail']);

                                                // PHP cURL  for https connection with auth
                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                curl_setopt($ch, CURLOPT_POST, true);
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                $ss = curl_getinfo($ch);                
                                                $response = curl_exec($ch);
                                                //echo $response;
                                                //echo "<pre>";
                                                
                                                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                $xml_parse = simplexml_load_string($xmlreplace);
                                                $json = json_encode($xml_parse);
                                                $responseArray = json_decode($json,true);
                                                //print_r($responseArray);

                                                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                                                //print_r($xml);
                                                if($xml->ErrorList->Error){
                                                   $status = $xml->ErrorList->Error;
                                                    if($xml->ErrorList->Error){
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                        $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                                    }
 
                                                }else if($xml->Status != "Y"){
                                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,'Try Again','Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    $this->session->set_flashdata('bulk_error', "Try Again");
                                                }
                                                if(@$value['minimum_stay'] != ""){
                                                    $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                                        <soapenv:Envelope
                                                        soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                                                        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                                        <getHSIContractDetailModification
                                                        xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                                        <HSI_ContractDetailModificationRQ>
                                                        <Language>ENG</Language>
                                                        <Credentials>
                                                        <User>'.$ch_details->user_name.'</User>
                                                        <Password>'.$ch_details->user_password.'</Password>
                                                        </Credentials>
                                                        <Contract>
                                                        <Name>'.$mp_details->contract_name.'</Name>
                                                        <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                                        <Sequence>'.$mp_details->sequence.'</Sequence>
                                                        </Contract>
                                                        <MinimumStayList>
                                                        <MinimumSt ay>
                                                        <DateFrom date="'.$hotelbed_start.'"/>
                                                        <DateTo date="'.$hotelbed_end.'"/>
                                                        <MinNumberOfDays>'.$value['minimum_stay'].'</MinNumberOfDays>
                                                        <MaxNumberOfDays>'.$maxnum.'</MaxNumberOfDays>
                                                        <RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>
                                                        </MinimumStay>
                                                        </MinimumStayList>
                                                        </HSI_ContractDetailModificationRQ>
                                                        </getHSIContractDetailModification>
                                                        </soapenv:Body>
                                                        </soapenv:Envelope>';

                                                    $headers = array(
                                                    "SOAPAction:no-action",
                                                    "Content-length: ".strlen($xml_post_string),
                                                    ); 
                                                    $url = trim($htb['urate_avail']);

                                                    // PHP cURL  for https connection with auth
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                                                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                    curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                    $ss = curl_getinfo($ch);                
                                                    $response = curl_exec($ch);
                                                    //echo $response;
                                                    
                                                    $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                    $xml_parse = simplexml_load_string($xmlreplace);
                                                    $json = json_encode($xml_parse);
                                                    $responseArray = json_decode($json,true);

                                                    $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractDetailModification']);

                                                    if($xml->ErrorList->Error){ 
                                                        $status = $xml->ErrorList->Error;
                                                        if($xml->ErrorList->Error){
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                                        }
                                                    }
                                                    if($xml->Status != "Y"){
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,'Try Again','Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                        $this->session->set_flashdata('bulk_error', "Try Again");
                                                    }
                                                }

                                            }
											elseif($room_value->channel_id=='8')
                                            {
                                                $sub_roomd['start_date'];
                                                $sub_roomd['end_date'];
                                                $srat_array=explode('/',$sub_roomd['start_date']);
                                                $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];

                                                $enddate_array=explode('/',$sub_roomd['end_date']);

                                                $xml_end_date=$enddate_array[2].'-'.$enddate_array[1].'-'.$enddate_array[0];

                                                $days=$value['days']; 

                                                $days_array=explode(',',$days);
                                                $dayval="";
                                                if (in_array("1", $days_array)) {
                                                    $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("2", $days_array)) {
                                                   $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("3", $days_array)) {
                                                   $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("4", $days_array)) {
                                                   $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("5",$days_array)) {
                                                    $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("6", $days_array)) {
                                                   $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }
                                                if (in_array("7", $days_array)) {
                                                    $dayval.=1;
                                                }else{
                                                    $dayval.=0;
                                                }

                                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();
                                                if($ch_details->mode == 0){
                                                    $urls = explode(',', $ch_details->test_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $gta[$path[0]] = $path[1];
                                                    }
                                                }else if($ch_details->mode == 1){
                                                    $urls = explode(',', $ch_details->live_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $gta[$path[0]] = $path[1];
                                                    }
                                                }  
                                            
                                                $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                                $gt_room_id=$mp_details->ID;
                                                $rateplanid=$mp_details->rateplan_id;
                                                $MinPax=$mp_details->MinPax;
                                                $peakrate=$mp_details->peakrate;
                                                $MaxOccupancy=$mp_details->MaxOccupancy;
                                                if(@$value['minimum_stay']!='')
                                                {
                                                    $minnights = $value['minimum_stay'];
                                                    $this->db->where('GTA_id', $room_value->import_mapping_id);
                                                    $updatemin=array('minnights'=>$minnights);
                                                    $this->db->update('import_mapping_GTA', $updatemin);
                                                }else{
                                                    $minnights=$mp_details->minnights;
                                                }
                                                $payfullperiod=$mp_details->payfullperiod;  

                                                if($value_price!='0' && $room_value->update_rate=='1')
                                                {
                                                if($contract_type=="Static"){
                                                    $soapUrl = trim($gta['urate_s']);
                                                    $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                                    xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                                    GTA_RateCreateRQ.xsd">
                                                    <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'"/>
                                                    <RatePlan Id="'.$rateplanid.'">
                                                    <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                                    DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                                    PeakRate="'.$peakrate.'">
                                                    <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                                    </StaticRate>
                                                    </RatePlan>
                                                    </GTA_StaticRatesCreateRQ>';

                                                    $ch = curl_init($soapUrl);

                                                    //curl_setopt($ch, CURLOPT_MUTE, 1);
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    $output = curl_exec($ch);
                                                      curl_close($ch);  
                                                    $data = simplexml_load_string($output);
                                                    $Error_Array = @$data->Errors->Error;
                                                    if($Error_Array!='')
                                                    {
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Error_Array,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    }
                                                }else{
                                                    $soapUrl = trim($gta['urate_m']);
                             
                                                    $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" FullPeriod="false"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'"
                                                        Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/>
                                                        </MarginRates></RatePlan>
                                                        </GTA_MarginRatesUpdateRQ>';              
                                                    $ch = curl_init($soapUrl);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                                    $response = curl_exec($ch);
                                                    $data = simplexml_load_string($response);
                                                    $Error_Array = @$data->Errors->Error;
                                                    if($Error_Array!='')
                                                    {
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    }            
                                                }
                                                }
                                                if($stop_sale!='0' && $room_value->update_availability == "1"){

                                                    $begin_date =$xml_start_date;
                                                    $en_date=$xml_end_date;
                                                    $begin = new DateTime($begin_date ); 
                                                    $end = new DateTime($en_date);
                                                    $radate = new DatePeriod($begin, new DateInterval('P1D'), $end);
                                                   $daterange = [];
                                                     foreach($radate as $date){
                                                            $daterange[]= $date->format("Y-m-d");
                                                    }
                                                    if(count($daterange)>0){
                                                        $exp_edate=explode('-', $en_date);
                                                        $edate=$exp_edate[0].'-'.$exp_edate[1].'-'.$exp_edate[2];
                                                        $daterange[]=$edate;
                                                     }else{
                                                        $exp_date=explode('-', $begin_date);
                                                        $bdate=$exp_date[0].'/'.$exp_date[1].'/'.$exp_date[2];
                                                        $daterange[]=$bdate;
                                                     }
                                                    $soapUrl=trim($gta['uavail']);           
                                                    $xml_post_string='<GTA_InventoryUpdateRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05  GTA_InventoryUpdateRQhelp.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                                        <InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$ch_details->hotel_channel_id.'">
                                                            <RoomStyle>';
                                                    foreach($daterange as $stdate){
                                                        $xml_post_string .= '<StayDate Date = "'.$stdate.'"><Inventory RoomId = "'.$gt_room_id.'">';
                                                        if($stop_sale == "1"){ 
                                                            $xml_post_string .= '<Restriction FlexibleStopSell="true" InventoryType="Flexible"/>';
                                                        }else if($stop_sale == "remove" && @$value['availability'] != ""){
                                                            $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "'.$value['availability'].'" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';               
                                                        }else if($stop_sale == "remove"  && @$value['availability'] == ""){
                                                            $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "1" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';
                                                        }

                                                        $xml_post_string .= '</Inventory></StayDate> ';
                                                    }
                                                    $xml_post_string.=' </RoomStyle>
                                                         </InventoryBlock>
                                                        </GTA_InventoryUpdateRQ>';
                                                    $ch = curl_init($soapUrl);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                                    $response = curl_exec($ch); 
                                                    $data = simplexml_load_string($response); 
                                                    $Error_Array = @$data->Errors->Error;
                                                    if($Error_Array!='')
                                                    {
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Error_Array,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    }
                                                }
                  
                                                if(@$value['availability']!='' && $room_value->update_availability == '1'){
                                                    $begin_date =$xml_start_date;
                                                    $en_date=$xml_end_date;
                                                    $begin = new DateTime($begin_date ); 
                                                    $end = new DateTime($en_date);
                                                    $radate = new DatePeriod($begin, new DateInterval('P1D'), $end);
                                                    $daterange = [];
                                                    foreach($radate as $date){
                                                        $daterange[]= $date->format("Y-m-d");
                                                    }
                                                    if(count($daterange)>0){
                                                        $exp_edate=explode('-', $en_date);
                                                        $edate=$exp_edate[0].'-'.$exp_edate[1].'-'.$exp_edate[2];
                                                        $daterange[]=$edate;
                                                    }else
                                                    {
                                                        $exp_date=explode('-', $begin_date);
                                                        $bdate=$exp_date[0].'/'.$exp_date[1].'/'.$exp_date[2];
                                                        $daterange[]=$bdate;
                                                    }
                                                                
                                                    $availability= $value['availability']; 
                                                    $soapUrl=trim($gta['uavail']);
                                                    $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05    GTA_InventoryUpdateRQ.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" ><RoomStyle>';
                                                    foreach($daterange as $stdate){
                                                        $xml_post_string.=' <StayDate Date = "'.$stdate.'"><Inventory RoomId="'.$gt_room_id.'"><Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$availability.'" ReleaseDays="0"/></Inventory></StayDate>';
                                                    }

                                                    $xml_post_string.='</RoomStyle>
                                                        </InventoryBlock>
                                                        </GTA_InventoryUpdateRQ>';

                                                    $ch = curl_init($soapUrl);

                                                    //$ch = curl_init($this->_serviceUrl . $id);

                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                                    $response = curl_exec($ch); 
                                                    $data = simplexml_load_string($response); 
                                                    $Error_Array = @$data->Errors->Error;
                                                    if($Error_Array!='')
                                                    {
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Error_Array,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    }
                                                }

                                            }
                                            else if($room_value->channel_id == '1')
                                            {
                                                //Aqui Expedia


                                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();
                                                if($ch_details->mode == 0){
                                                    $urls = explode(',', $ch_details->test_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $exp[$path[0]] = $path[1];
                                                    }
                                                }else if($ch_details->mode == 1){
                                                    $urls = explode(',', $ch_details->live_url);
                                                    foreach($urls as $url){
                                                        $path = explode("~",$url);
                                                        $exp[$path[0]] = $path[1];
                                                    }
                                                }  
                                                $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();

                                                $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id' => $mp_details->hotel_channel_id,'channel'=>$room_value->channel_id,'rateType' => 'SellRate'))->row();

                                                $oa_details = get_data('import_mapping_expedia_occupancy',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id' => $mp_details->hotel_channel_id,'channel'=>$room_value->channel_id))->row();
                                                $minlos = $mp_details->minLos;
                                                $maxLos = $mp_details->maxLos;
                                                $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                if($mapping_values){
                                                    if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
                                                        if($minlos < $mapping_values['value']){
                                                            $maxLos = $mapping_values['value'];
                                                        }
                                                    }
                                                }
                                                $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                                        <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                                        <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                                        <AvailRateUpdate>';
                                                $xml .= '<DateRange from="'.$re_sart_date.'" to="'.$re_end_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                                if($room_value->explevel == "rate"){ 
                                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                                    if($ch_update_avail!='' && $room_value->update_availability =='1'){
                                                         $xml .= '<Inventory totalInventoryAvailable="'.$value['availability'].'"/>';
                                                    }
                                                    if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                                        $plan_id = $mp_details->rateplan_id;
                                                    }else{
                                                        $plan_id = $mp_details->rate_type_id;
                                                    }

                                                    if($stop_sale == "1"){
                                                         $xml .= '<RatePlan id="'.$plan_id.'" closed="true">';
                                                    }else if($stop_sale == "0"){
                                                         $xml .= '<RatePlan id="'.$plan_id.'">';
                                                    }else if($stop_sale == "remove"){
                                                        $xml .= '<RatePlan id="'.$plan_id.'" closed="false">';
                                                    }
                                                    //$value_price!='0' && $value_price >=(string)$rt_details->minAmount && $value_price <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'   

                                                    
                                                    if($value_price!='0' && $room_value->update_rate=='1'){

                                                        if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                                        for($i = $minlos; $i<=$maxLos; $i++){
                                                            $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                    <PerDay rate="'.$value_price.'"/>
                                                                    </Rate>';

                                                        }
                                                        }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$value_price.'"/>
                                                                    </Rate> ';
                                                        }
														elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
														{
															$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$value_price.'" occupancy = "2"/></Rate> ';
															$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';

														}
                                                    }else{
                                                        if($value_price !='' && $room_value->update_rate=='1'){
                                                            if($value_price <= (string)$rt_details->minAmount || @$value_price >= (string)$rt_details->maxAmount){
                                                                $this->session->set_flashdata("price_error", "Price must be between ".$rt_details->minAmount." and ".$rt_details->maxAmount);
                                                            }
                                                        }
                                                    }
                                                    
                                                    if($exp_ctd != "" || $exp_cta != "" || @$value['minimum_stay'] != ""){
                                                        $xml .= '<Restrictions';
                                                        $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                                        $xml .= ' closedToArrival="'.$exp_cta.'"';
                                                        
                                                        if(@$value['minimum_stay'] != ""){
                                                            $xml .= ' minLOS="'.@$value['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                                        }
                                                        $xml .= ' />';
                                                    }
                                                    $xml .= "</RatePlan>";
                                                }else if($room_value->explevel == "room"){

                                                    if($stop_sale == "1"){
                                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                                                    }else if($stop_sale == "remove"){
                                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                                                    }else if($stop_sale == "0"){
                                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                                    }
                                                    if($ch_update_avail!='' && $room_value->update_availability =='1'){
                                                         $xml .= '<Inventory totalInventoryAvailable="'.$value['availability'].'"/>';
                                                    }
                                                    $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                                    foreach($available_plans as $e_plan){

                                                        if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                                            $plan_id = $e_plan->rateplan_id;
                                                        }else{
                                                            $plan_id = $e_plan->rate_type_id;
                                                        }

                                                        $xml .= '<RatePlan id="'.$plan_id.'">';      
                                                        if($value_price!='0' && $room_value->update_rate=='1'){
                                                            if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                        <PerDay rate="'.$value_price.'"/>
                                                                        </Rate>';

                                                            }
                                                            }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                                                $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$value_price.'"/>
                                                                        </Rate> ';
                                                            }
															elseif($e_plan->pricingModel == 'OccupancyBasedPricing')
															{
																$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$value_price.'" occupancy = "2"/></Rate> ';
																$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
															}
                                                        }else{
                                                            if($value_price !='' && $room_value->update_rate=='1'){
                                                                if($value_price <= (string)$rt_details->minAmount || @$value_price >= (string)$rt_details->maxAmount){
                                                                    $this->session->set_flashdata("price_error", "Price must be between ".$rt_details->minAmount." and ".$rt_details->maxAmount);
                                                                }
                                                            }
                                                        }
                                                        
                                                        if($exp_ctd != "" || $exp_cta != "" || @$value['minimum_stay'] != ""){
                                                            $xml .= '<Restrictions';
                                                            $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                                            $xml .= ' closedToArrival="'.$exp_cta.'"';
                                                            
                                                            if(@$value['minimum_stay'] != ""){
                                                                $xml .= ' minLOS="'.@$value['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                                            }
                                                            $xml .= ' />';
                                                        }
                                                        $xml .= "</RatePlan>";
                                                    }
                                                }
                                                $xml .="</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
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
                                                $data = simplexml_load_string($output); 
                                                $response = $data->Error;
                                                
                                                if($response!='')
                                                {
                                                   // echo 'fail';
                                                    $expedia_update = "Failed";
                                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Bulk Update Sub Save',date('m/d/Y h:i:s a', time()));
                                                    $this->session->set_flashdata('bulk_error',(string)$response);
                                                }
                                                else
                                                {
                                                   // echo 'success   ';
                                                    $expedia_update = "Success";
                                                }
                                                
                                                curl_close($ch);                                    
                                            }
                                            else if($room_value->channel_id == '2')//aqui hacer esto
                                            {
                                                $chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row()->xml_type;
												if($chk_allow==2 || $chk_allow==3)
												{
													$value['start_date'] = $this->input->post('start_date');
													$value['end_date'] = $this->input->post('end_date');
													$value['price'] = @$value_price;
													$value['days'] = $sub_rooms['days'];
													$this->load->model("booking_model");
													$this->booking_model->bulk_update($value,$stop_sale,$room_value->import_mapping_id,$room_value->mapping_id);
												}
                                            }
                                            else if ($room_value->channel_id == '36')
                                             {
                                                    $value['start_date'] = $this->input->post('start_date');
                                                    $value['end_date'] = $this->input->post('end_date');
                                                    $value['price'] = @$value_price;
                                                    $value['days'] = $sub_rooms['days'];
                                                    $this->load->model("despegar_model");
                                                    $this->despegar_model->bulk_update($value,$stop_sale,$room_value->import_mapping_id,$room_value->mapping_id);
                                            }

                                            if(isset($rate_converted) == 1){                                
                                                $rateMul[$id.$room_value->channel_id] = $value_price;
                                                $value_price = $original_price;
                                            }/*else{
                                                $rateMul[$id.$room_value->channel_id] = @$value_price;
                                            }*/
                                            //print_r($rateMul);
                                        }
                                    }
                                }
                            
                            }
                            $roomname = get_data(TBL_PROPERTY,array('property_id'=>$id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
                            $ratename = $guest_count;
                            $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
                            $username = ucfirst($user->fname).' '.ucfirst($user->lname);
                            $productdetails = "";
                            $channelsname = "";
							$type = array_column($type, 'refund_amount');
                            foreach($type as $key => $value)
                            {
                                $productdetails .= ' '.ucfirst($key).":".$value.",";
                            }
                            if($channel_id != ""){
                                $channelsname .= "Channels:";
                                foreach ($channel_id as $channelid) {
                                    $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channelid))->row()->channel_name.',';
                                }
                            }
                            $message = "Location:Bulk Update, Start Date:".$sub_roomd['start_date'].", End Date:".$sub_roomd['end_date'].", Room:".$roomname.'-'.$ratename.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
                            
                            $this->write_log($message);
                        }
                    }
                }
				if($channel_bnow!='')
				{
					$this->bnow_model->sub_room_bulk_update($clean,$up_days);
				}
                if($channel_travel != "")
                {
                    $this->travel_model->sub_room_bulk_update($clean,$up_days);
                }
                if($channel_wbeds != "")
                {
                    $this->wbeds_model->sub_room_bulk_update($clean,$up_days);
                }
            }
            /* $startDate = DateTime::createFromFormat("d/m/Y",$sub_roomd['start_date']);
            $endDate = DateTime::createFromFormat("d/m/Y",$sub_roomd['end_date']);
            $periodInterval = new DateInterval( "P1D" );
            $endDate->add( $periodInterval );
			$period = new DatePeriod( $startDate, $periodInterval, $endDate );
            $endDate->add( $periodInterval );*/
/*
            $start_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $end_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['end_date'])));

            if(@$sub_rooms['days'] != "")
            {
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,@$sub_rooms['days']);
            }else{
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,'1,2,3,4,5,6,7');
            }

            foreach($clean as $id=>$room)
            {
                $sub_rooms['room_id']   = $id;
                
                $sub_rooms_cha['room_id'] = $id;
                
                foreach($room as $guest_count=>$type)
                {
                    $sub_rooms['guest_count'] = $guest_count;
                    
                    $sub_rooms_cha['guest_count'] = $guest_count;
                    
                    foreach($type as $refun=>$value)
                    {
                        foreach($period as $date)
                        {
                            if($this->input->post('maincal') != '')
                            {
                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                {
                                    $available = get_data(RESERV,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>'0','separate_date'=>$date))->row_array();
                                }
                                else if(user_type()=='2')
                                {
                                    $ch_available = get_data(RESERV,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>'0','separate_date'=>$date))->row_array();
                                }
                                if(count($available)==0)
                                {
                                    $sub_rooms['separate_date']=$date;
                                    
                                    $sub_rooms['refun_type']   = $refun;
                                    
                                    if(@$value['non_refund_amount']!='')
                                    {
                                        $sub_rooms['non_refund_amount'] = $value['non_refund_amount'];
                                    }
                                    else
                                    {
                                        $sub_rooms['non_refund_amount'] = '';
                                    }
                                    if(@$value['refund_amount']!='')
                                    {
                                        $sub_rooms['refund_amount'] = $value['refund_amount'];
                                    }
                                    else
                                    {
                                        $sub_rooms['refund_amount'] = '';
                                    }
                                    
                             
                                    if(@$value['minimum_stay']!='')
                                    {
                                        $sub_rooms['minimum_stay'] = $value['minimum_stay'];
                                    }
                                    else
                                    {
                                        $sub_rooms['minimum_stay'] = '';
                                    }
                                    if(@$value['cta']!='')
                                    {
                                        $sub_rooms['cta'] = $value['cta'];
                                    }
                                    else
                                    {
                                        $sub_rooms['cta'] = '';
                                    }
                                        
                                    if(@$value['ctd']!='')
                                    {
                                        $sub_rooms['ctd'] = $value['ctd'];
                                    }
                                    else
                                    {
                                        $sub_rooms['ctd'] = '';
                                    }
                                    if(@$value['stop_sell']!='')
                                    {
                                        $sub_rooms['stop_sell'] = $value['stop_sell'];
                                        $sub_rooms['open_room'] = 0;
                                    }
                                    else
                                    {
                                        $sub_rooms['stop_sell'] = '';
                                    }
                                    if(@$value['open_room']!='')
                                    {
                                        $sub_rooms['open_room'] = $value['open_room'];
                                        $sub_rooms['stop_sell'] = 0;
                                    }
                                    else
                                    {
                                        $sub_rooms['open_room'] = '';
                                    }
                                    if(user_type()=='1')
                                    {
                                        $sub_rooms['owner_id'] = user_id();
                                    }
                                    else if(user_type()=='2')
                                    {
                                        $sub_rooms['owner_id'] = owner_id();
                                    }
                                    $sub_rooms['hotel_id'] = hotel_id();
                                    $sub_rooms['individual_channel_id'] = '0';
                                    $this->db->insert(RESERV, $sub_rooms);
                                }
                                else
                                {
                                    if(@$value['non_refund_amount']!='')
                                    {
                                        $sub_rooms_up['non_refund_amount'] = $value['non_refund_amount'];
                                    }
                                    else
                                    {
                                        $sub_rooms_up['non_refund_amount'] = $available['non_refund_amount'];
                                    }
                                    if(@$value['refund_amount']!='')
                                    {
                                        $sub_rooms_up['refund_amount'] = $value['refund_amount'];
                                    }
                                    else
                                    {
                                        $sub_rooms_up['refund_amount'] = $available['refund_amount'];
                                    }
                                    
                                   
                                    if(@$value['minimum_stay']!='')
                                    {
                                        $sub_rooms_up['minimum_stay'] = $value['minimum_stay'];
                                    }
                                    else
                                    {
                                        $sub_rooms_up['minimum_stay'] = $available['minimum_stay'];
                                    }
                                    if(@$value['cta']!='')
                                    {
                                        $sub_rooms_up['cta'] = $value['cta'];
                                    }
                                    else
                                    {
                                        $sub_rooms_up['cta'] = $available['cta'];
                                    }
                                        
                                    if(@$value['ctd']!='')
                                    {
                                        $sub_rooms_up['ctd'] = $value['ctd'];
                                    }
                                    else
                                    {
                                        $sub_rooms_up['ctd'] = $available['ctd'];
                                    }
                                    if(@$value['stop_sell']!='')
                                    {
                                        $sub_rooms_up['stop_sell'] = $value['stop_sell'];
                                        $sub_rooms_up['open_room'] = 0;
                                    }
                                    else
                                    {
                                        $sub_rooms_up['stop_sell'] = $available['stop_sell'];
                                    }
                                    if(@$value['open_room']!='')
                                    {
                                        $sub_rooms_up['open_room'] = $value['open_room'];
                                        $sub_rooms_up['stop_sell'] = 0;
                                    }
                                    else
                                    {
                                        $sub_rooms_up['open_room'] = $available['open_room'];
                                    }
                                    if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                    {
                                        $this->db->where('owner_id', user_id());
                                    }
                                    else if(user_type()=='2')
                                    {
                                        $this->db->where('owner_id', owner_id());
                                    }
                                    $this->db->where('hotel_id', hotel_id());
                                    $this->db->where('room_id', $id);
                                    $this->db->where('separate_date', $date);
                                    $this->db->where('refun_type', $refun);
                                    $this->db->where('guest_count', $guest_count);
                                    $this->db->where('individual_channel_id','0');
                                    $this->db->update(RESERV, $sub_rooms_up);
                                }
                            }
                            
                            if($up_channel_id!='')
                            {
                                foreach($up_channel_id as $con_channel)
                                {  
                                    if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                    {
                                        $ch_available = get_data(RESERV,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$date))->row_array();
                                    }
                                    else if(user_type()=='2')
                                    {
                                        $ch_available = get_data(RESERV,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$date))->row_array();
                                    }
                                    if($con_channel=='11')
                                    {
                                        
                                            if(count($ch_available)==0)
                                            {
                                                $sub_rooms_cha['separate_date']=$date;
                                                
                                                $sub_rooms_cha['refun_type']   = $refun;
                                                
                                                if(@$value['non_refund_amount']!='')
                                                {
                                                    if(isset($rateMul[$id.$con_channel])){
                                                        $sub_rooms_cha['non_refund_amount'] = $rateMul[$id.$con_channel];
                                                    }else{
                                                        $sub_rooms_cha['non_refund_amount'] = $value['non_refund_amount'];
                                                    }
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['non_refund_amount'] = '';
                                                }
                                                if(@$value['refund_amount']!='')
                                                {
                                                    if(isset($rateMul[$id.$con_channel])){
                                                        $sub_rooms_cha['refund_amount'] = $rateMul[$id.$con_channel];
                                                    }else{
                                                        $sub_rooms_cha['refund_amount'] = $value['refund_amount'];
                                                    }
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['refund_amount'] = '';
                                                }
                                                
                                                if(@$value['availability']!='')
                                                {
                                                    $sub_rooms_cha['availability'] = $value['availability'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['availability'] = '';
                                                }
                                                if(@$value['minimum_stay']!='')
                                                {
                                                    $sub_rooms_cha['minimum_stay'] = $value['minimum_stay'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['minimum_stay'] = '';
                                                }
                                                if(@$value['cta']!='')
                                                {
                                                    $sub_rooms_cha['cta'] = $value['cta'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['cta'] = '';
                                                }
                                                    
                                                if(@$value['ctd']!='')
                                                {
                                                    $sub_rooms_cha['ctd'] = $value['ctd'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['ctd'] = '';

                                                }
                                                if(@$value['stop_sell']!='')
                                                {
                                                    $sub_rooms_cha['stop_sell'] = $value['stop_sell'];
                                                    $sub_rooms_cha['open_room'] = 0;
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['stop_sell'] = '';
                                                }
                                                if(@$value['open_room']!='')
                                                {
                                                    $sub_rooms_cha['open_room'] = $value['open_room'];
                                                    $sub_rooms_cha['stop_sell'] = 0;
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha['open_room'] = '';
                                                }
                                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                                {
                                                    $sub_rooms_cha['owner_id'] = user_id();
                                                }
                                                else if(user_type()=='2')
                                                {
                                                    $sub_rooms_cha['owner_id'] = owner_id();
                                                }
                                                $sub_rooms_cha['hotel_id'] = hotel_id();
                                                $sub_rooms_cha['individual_channel_id'] = $con_channel;
                                                $this->db->insert(RESERV, $sub_rooms_cha);
                                            }
                                            else
                                            {
                                                if(@$value['non_refund_amount']!='')
                                                {
                                                    $sub_rooms_cha_up['non_refund_amount'] = $value['non_refund_amount'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['non_refund_amount'] = $ch_available['non_refund_amount'];
                                                }
                                                if(@$value['refund_amount']!='')
                                                {
                                                    $sub_rooms_cha_up['refund_amount'] = $value['refund_amount'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['refund_amount'] = $ch_available['refund_amount'];
                                                }
                                                
                                                if(@$value['availability']!='')
                                                {
                                                    $sub_rooms_cha_up['availability'] = $value['availability'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['availability'] = $ch_available['availability'];
                                                }
                                                if(@$value['minimum_stay']!='')
                                                {
                                                    $sub_rooms_cha_up['minimum_stay'] = $value['minimum_stay'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['minimum_stay'] = $ch_available['minimum_stay'];
                                                }
                                                if(@$value['cta']!='')
                                                {
                                                    $sub_rooms_cha_up['cta'] = $value['cta'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['cta'] = $ch_available['cta'];
                                                }
                                                    
                                                if(@$value['ctd']!='')
                                                {
                                                    $sub_rooms_cha_up['ctd'] = $value['ctd'];
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['ctd'] = $ch_available['ctd'];
                                                }
                                                if(@$value['stop_sell']!='')
                                                {
                                                    $sub_rooms_cha_up['stop_sell'] = $value['stop_sell'];
                                                     $sub_rooms_cha_up['open_room'] = 0;
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['stop_sell'] = $ch_available['stop_sell'];
                                                }
                                                if(@$value['open_room']!='')
                                                {
                                                    $sub_rooms_cha_up['open_room'] = $value['open_room'];
                                                    $sub_rooms_cha_up['stop_sell'] = 0;
                                                }
                                                else
                                                {
                                                    $sub_rooms_cha_up['open_room'] = $ch_available['open_room'];
                                                }
                                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1' )
                                                {
                                                    $this->db->where('owner_id', user_id());
                                                }
                                                else if(user_type()=='2')
                                                {
                                                    $this->db->where('owner_id', owner_id());
                                                }
                                                $this->db->where('hotel_id', hotel_id());
                                                $this->db->where('room_id', $id);
                                                $this->db->where('separate_date', $date);
                                                $this->db->where('refun_type', $refun);
                                                $this->db->where('guest_count', $guest_count);
                                                $this->db->where('individual_channel_id',$con_channel);
                                                $this->db->update(RESERV, $sub_rooms_cha_up);
                                            }
                                        
                                    }

                                    else
                                    {
										if($con_channel==2)
										{
											$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row()->xml_type;
										}
										else
										{
											$chk_allow='';
										}
										if(($con_channel==2 && ($chk_allow==2 || $chk_allow==3))||$con_channel!=2)
										{
											if(count($ch_available)==0)
											{
												//$sub_rooms_cha['separate_date']=$date->format("d/m/Y");
												$sub_rooms_cha['separate_date']=$date;
												$sub_rooms_cha['refun_type']   = $refun;
												if(@$value['non_refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$sub_rooms_cha['non_refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$sub_rooms_cha['non_refund_amount'] = $value['non_refund_amount'];
													}
												}
												else
												{
													$sub_rooms_cha['non_refund_amount'] = '';
												}
												if(@$value['refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$sub_rooms_cha['refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$sub_rooms_cha['refund_amount'] = $value['refund_amount'];
													}
												}
												else
												{
													$sub_rooms_cha['refund_amount'] = '';
												}
												
												if(@$value['availability']!='')
												{
													$sub_rooms_cha['availability'] = $value['availability'];
												}
												else
												{
													$sub_rooms_cha['availability'] = '';
												}
												if(@$value['minimum_stay']!='')
												{
													$sub_rooms_cha['minimum_stay'] = $value['minimum_stay'];
												}
												else
												{
													$sub_rooms_cha['minimum_stay'] = '';
												}
												if(@$value['cta']!='')
												{
													$sub_rooms_cha['cta'] = $value['cta'];
												}
												else
												{
													$sub_rooms_cha['cta'] = '';
												}
													
												if(@$value['ctd']!='')
												{
													$sub_rooms_cha['ctd'] = $value['ctd'];
												}
												else
												{
													$sub_rooms_cha['ctd'] = '';
												}
												if(@$value['stop_sell']!='')
												{
													$sub_rooms_cha['stop_sell'] = $value['stop_sell'];
													$sub_rooms_cha['open_room'] = 0;
												}
												else
												{
													$sub_rooms_cha['stop_sell'] = '';
												}
												if(@$value['open_room']!='')
												{
													$sub_rooms_cha['open_room'] = $value['open_room'];
													$sub_rooms_cha['stop_sell'] = 0;
												}
												else
												{
													$sub_rooms_cha['open_room'] = '';
												}
												if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
												{
													$sub_rooms_cha['owner_id'] = user_id();
												}
												else if(user_type()=='2')
												{
													$sub_rooms_cha['owner_id'] = owner_id();
												}
												$sub_rooms_cha['hotel_id'] = hotel_id();
												$sub_rooms_cha['individual_channel_id'] = $con_channel;
												$this->db->insert(RESERV, $sub_rooms_cha);
											}
											else
											{
												if(@$value['non_refund_amount']!='')
												{
													$sub_rooms_cha_up['non_refund_amount'] = $value['non_refund_amount'];
												}
												else
												{
													$sub_rooms_cha_up['non_refund_amount'] = $ch_available['non_refund_amount'];
												}
												if(@$value['refund_amount']!='')
												{
													$sub_rooms_cha_up['refund_amount'] = $value['refund_amount'];
												}
												else
												{
													$sub_rooms_cha_up['refund_amount'] = $ch_available['refund_amount'];
												}
												
												if(@$value['availability']!='')
												{
													$sub_rooms_cha_up['availability'] = $value['availability'];
												}
												else
												{
													$sub_rooms_cha_up['availability'] = $ch_available['availability'];
												}
												if(@$value['minimum_stay']!='')
												{
													$sub_rooms_cha_up['minimum_stay'] = $value['minimum_stay'];
												}
												else
												{
													$sub_rooms_cha_up['minimum_stay'] = $ch_available['minimum_stay'];
												}
												if(@$value['cta']!='')
												{
													$sub_rooms_cha_up['cta'] = $value['cta'];
												}
												else
												{
													$sub_rooms_cha_up['cta'] = $ch_available['cta'];
												}
													
												if(@$value['ctd']!='')
												{
													$sub_rooms_cha_up['ctd'] = $value['ctd'];
												}
												else
												{
													$sub_rooms_cha_up['ctd'] = $ch_available['ctd'];
												}
												if(@$value['stop_sell']!='')
												{
													$sub_rooms_cha_up['stop_sell'] = $value['stop_sell'];
													$sub_rooms_cha_up['open_room'] = 0;
												}
												else
												{
													$sub_rooms_cha_up['stop_sell'] = $ch_available['stop_sell'];
												}
												if(@$value['open_room']!='')
												{
													$sub_rooms_cha_up['open_room'] = $value['open_room'];
													$sub_rooms_cha_up['stop_sell'] = 0;
												}
												else
												{
													$sub_rooms_cha_up['open_room'] = $ch_available['open_room'];
												}
												if(user_type()=='1' || admin_id()!='' && admin_type()=='1' )
												{
													$this->db->where('owner_id', user_id());
												}
												else if(user_type()=='2')
												{
													$this->db->where('owner_id', owner_id());
												}
												$this->db->where('hotel_id', hotel_id());
												$this->db->where('room_id', $id);
												$this->db->where('separate_date', $date);
												$this->db->where('refun_type', $refun);
												$this->db->where('guest_count', $guest_count);
												$this->db->where('individual_channel_id',$con_channel);
												$this->db->update(RESERV, $sub_rooms_cha_up);
											}
										}
                                    }
                                }
                            }
                        }
                    }

                }

            }
            */
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function ratesave($product=false, $options=false, $categories=false)
    {


            if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
            {
                $userID = user_id();
            }
            else if(user_type()=='2')
            {
                $userID = owner_id();
            }

       
       
            $start_date = date('Y-m-d',strtotime(str_replace('/','-',$product['start_date'])));
            $end_date = date('Y-m-d',strtotime(str_replace('/','-',$product['end_date'])));
           



            if(@$product['days'] != "")
            {
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,@$product['days']);
            }else{
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,'1,2,3,4,5,6,7');
            }

            $all_channel_id = $this->input->post('channel_id');

            //Para Canal Local
            if($this->input->post('maincal') != '')
            {
                foreach($period as $date)
                {
                    $available1= get_data(RATE_BASE,array('individual_channel_id'=>'0','room_id'=>$product['room_id'],'separate_date'=>$date,'owner_id'=>$userID,'hotel_id'=>hotel_id(),'rate_types_id'=>$product['rate_id'] ))->row_array();

                        
                                //datos de informacion
                            if(@$product['availability']!='')
                            {
                                $product_avail['availability'] =$product['availability'];
                                if(@$product['availability']=='0')
                                 {
                                     $product_avail['stop_sell'] ="1";
                                }
                                else
                                {
                                    $product_avail['stop_sell'] ="0";
                                }
                            }
                            
                            if(@$product['price']!='')
                            {
                                $product_avail['price'] =$product['price'];                      
                            }

                            if(@$product['minimum_stay']!='')
                            {
                                $product_avail['minimum_stay'] =$product['minimum_stay'];
                            }

                            if(isset($product['cta'])!='')
                            {
                                $product_avail['cta'] =$product['cta'];
                            }

                            if(isset($product['ctd'])!='')
                            {
                                $product_avail['ctd'] =$product['ctd'];
                            }

                            if(isset($product['stop_sell'])!='')
                            {
                                $product_avail['stop_sell'] =$product['stop_sell'];

                                if(@$product['availability']=='0')
                                {
                                     $product_avail['stop_sell'] ="1";
                                     $product_avail['open_room']='0';
                                }
                            }

                            if(isset($product['open_room'])!='')
                            {
                                $product_avail['stop_sell']='0';
                                $product_avail['open_room']='1';
                            }




                             

                        if(count($available1)!=0)
                        { 
                            $this->db->where('owner_id', $userID );
                            $this->db->where('hotel_id', hotel_id());
                            $this->db->where('room_id', $product['room_id']);
                            $this->db->where('rate_types_id', $product['rate_id']);
                            $this->db->where('separate_date', $date);
                            $this->db->where('individual_channel_id', '0');
                            $this->db->update(RATE_BASE, $product_avail);
                        }
                        else
                        {   $product_avail['separate_date'] = $date;
                            $product_avail['trigger_cal'] = 0;
                            $product_avail['room_id'] =$product['room_id'];
                            $product_avail['rate_types_id'] =$product['rate_id'];
                            $product_avail['owner_id'] = $userID;
                            $product_avail['hotel_id'] = hotel_id();
                            $product_avail['individual_channel_id']= '0';
                            $this->db->insert(RATE_BASE, $product_avail);
                        }
                }
            }

                 //Todos los canales Disponibles calendario Local
            if($all_channel_id)
            {
                foreach($all_channel_id as $channel_id)
                {
                    if($channel_id==36)
                    {   
                     
                      $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>$product['rate_id'],'enabled'=>'enabled'))->result();

                      if ($room_mapping)
                      {
                           $rate_conversion = $room_mapping[0]->rate_conversion;

                           if(@$product['price']!='')
                            {
                                $price = $product['price']*$rate_conversion;                     
                            }
                            else
                            {
                             $price ="0";   
                            }
                       
                        foreach($period as $date)
                        {
                            $available1= get_data(RATE_BASE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$date,'owner_id'=>$userID,'hotel_id'=>hotel_id(),'rate_types_id'=>$product['rate_id'] ))->row_array();


                                //datos de informacion
                                    if(@$product['availability']!='')
                                    {
                                        $product_avail['availability'] =$product['availability'];
                                        if(@$product['availability']=='0')
                                         {
                                             $product_avail['stop_sell'] ="1";
                                        }
                                        else
                                        {
                                            $product_avail['stop_sell'] ="0";
                                        }
                                    }
                                    
                                    if(@$product['price']!='')
                                    {
                                        $product_avail['price'] =$price;                      
                                    }

                                    if(@$product['minimum_stay']!='')
                                    {
                                        $product_avail['minimum_stay'] =$product['minimum_stay'];
                                    }

                                    if(isset($product['cta'])!='')
                                    {
                                        $product_avail['cta'] =$product['cta'];
                                    }

                                    if(isset($product['ctd'])!='')
                                    {
                                        $product_avail['ctd'] =$product['ctd'];
                                    }

                                    if(isset($product['stop_sell'])!='')
                                    {
                                        $product_avail['stop_sell'] =$product['stop_sell'];

                                        if(@$product['availability']=='0')
                                        {
                                             $product_avail['stop_sell'] ="1";
                                             $product_avail['open_room']='0';
                                        }
                                    }

                                    if(isset($product['open_room'])!='')
                                    {
                                        $product_avail['stop_sell']='0';
                                        $product_avail['open_room']='1';
                                    }




                                     

                                if(count($available1)!=0)
                                { 
                                    $this->db->where('owner_id', $userID );
                                    $this->db->where('hotel_id', hotel_id());
                                    $this->db->where('room_id', $product['room_id']);
                                    $this->db->where('rate_types_id', $product['rate_id']);
                                    $this->db->where('separate_date', $date);
                                    $this->db->where('individual_channel_id', "$channel_id");
                                    $this->db->update(RATE_BASE, $product_avail);
                                   
                                }
                                else
                                {   $product_avail['separate_date'] = $date;
                                    $product_avail['trigger_cal'] = 0;
                                    $product_avail['room_id'] =$product['room_id'];
                                    $product_avail['rate_types_id'] =$product['rate_id'];
                                    $product_avail['owner_id'] = $userID;
                                    $product_avail['hotel_id'] = hotel_id();
                                    $product_avail['individual_channel_id']= "$channel_id";
                                    $this->db->insert(RATE_BASE, $product_avail);
                                }
                        }

                            $this->load->model("despegar_model");
                            $this->despegar_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                      }
                     
                    }
                    if($channel_id==1)
                    {
                        
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->result();
         
                      if ($room_mapping)
                      {
                            $rate_conversion = $room_mapping[0]->rate_conversion;

                            if(@$product['price']!='')
                            {
                                $price = $product['price']*$rate_conversion;                     
                            }
                            else
                            {
                                $price ="0";   
                            }

                            foreach($period as $date)
                            {

                                 $available1= get_data(RATE_BASE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$date,'owner_id'=>$userID,'hotel_id'=>hotel_id(),'rate_types_id'=>$product['rate_id'] ))->row_array();


                                //datos de informacion
                                    if(@$product['availability']!='')
                                    {
                                        $product_avail['availability'] =$product['availability'];
                                        if(@$product['availability']=='0')
                                         {
                                             $product_avail['stop_sell'] ="1";
                                        }
                                        else
                                        {
                                            $product_avail['stop_sell'] ="0";
                                        }
                                    }
                                    
                                    if(@$product['price']!='')
                                    {
                                        $product_avail['price'] =$price;                      
                                    }

                                    if(@$product['minimum_stay']!='')
                                    {
                                        $product_avail['minimum_stay'] =$product['minimum_stay'];
                                    }

                                    if(isset($product['cta'])!='')
                                    {
                                        $product_avail['cta'] =$product['cta'];
                                    }

                                    if(isset($product['ctd'])!='')
                                    {
                                        $product_avail['ctd'] =$product['ctd'];
                                    }

                                    if(isset($product['stop_sell'])!='')
                                    {
                                        $product_avail['stop_sell'] =$product['stop_sell'];

                                        if(@$product['availability']=='0')
                                        {
                                             $product_avail['stop_sell'] ="1";
                                             $product_avail['open_room']='0';
                                        }
                                    }

                                    if(isset($product['open_room'])!='')
                                    {
                                        $product_avail['stop_sell']='0';
                                        $product_avail['open_room']='1';
                                    }




                                     

                                if(count($available1)!=0)
                                { 
                                    $this->db->where('owner_id', $userID );
                                    $this->db->where('hotel_id', hotel_id());
                                    $this->db->where('room_id', $product['room_id']);
                                    $this->db->where('rate_types_id', $product['rate_id']);
                                    $this->db->where('separate_date', $date);
                                    $this->db->where('individual_channel_id', "$channel_id");
                                    $this->db->update(RATE_BASE, $product_avail);
                                   
                                }
                                else
                                {   $product_avail['separate_date'] = $date;
                                    $product_avail['trigger_cal'] = 0;
                                    $product_avail['room_id'] =$product['room_id'];
                                    $product_avail['rate_types_id'] =$product['rate_id'];
                                    $product_avail['owner_id'] = $userID;
                                    $product_avail['hotel_id'] = hotel_id();
                                    $product_avail['individual_channel_id']= "$channel_id";
                                    $this->db->insert(RATE_BASE, $product_avail);
                                }
                                
                            }

                      }

                      $this->load->model("expedia_model");
                        $this->expedia_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                    }
                    if($channel_id==2)
                    {
                           
                     $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$product['room_id'],'rate_id'=>0,'enabled'=>'enabled'))->result();
                     

                      if ($room_mapping)
                      {
                           $rate_conversion = $room_mapping[0]->rate_conversion;

                           if(@$product['price']!='')
                            {
                                $price = $product['price']*$rate_conversion;                     
                            }
                            else
                            {
                                $price ="0";   
                            }
                           
                            foreach($period as $date)
                            {
                               
                            $available1= get_data(RATE_BASE,array('individual_channel_id'=>$channel_id,'room_id'=>$product['room_id'],'separate_date'=>$date,'owner_id'=>$userID,'hotel_id'=>hotel_id(),'rate_types_id'=>$product['rate_id'] ))->row_array();


                                //datos de informacion
                                    if(@$product['availability']!='')
                                    {
                                        $product_avail['availability'] =$product['availability'];
                                        if(@$product['availability']=='0')
                                         {
                                             $product_avail['stop_sell'] ="1";
                                        }
                                        else
                                        {
                                            $product_avail['stop_sell'] ="0";
                                        }
                                    }
                                    
                                    if(@$product['price']!='')
                                    {
                                        $product_avail['price'] =$price;                      
                                    }

                                    if(@$product['minimum_stay']!='')
                                    {
                                        $product_avail['minimum_stay'] =$product['minimum_stay'];
                                    }

                                    if(isset($product['cta'])!='')
                                    {
                                        $product_avail['cta'] =$product['cta'];
                                    }

                                    if(isset($product['ctd'])!='')
                                    {
                                        $product_avail['ctd'] =$product['ctd'];
                                    }

                                    if(isset($product['stop_sell'])!='')
                                    {
                                        $product_avail['stop_sell'] =$product['stop_sell'];

                                        if(@$product['availability']=='0')
                                        {
                                             $product_avail['stop_sell'] ="1";
                                             $product_avail['open_room']='0';
                                        }
                                    }

                                    if(isset($product['open_room'])!='')
                                    {
                                        $product_avail['stop_sell']='0';
                                        $product_avail['open_room']='1';
                                    }




                                     

                                if(count($available1)!=0)
                                { 
                                    $this->db->where('owner_id', $userID );
                                    $this->db->where('hotel_id', hotel_id());
                                    $this->db->where('room_id', $product['room_id']);
                                    $this->db->where('rate_types_id', $product['rate_id']);
                                    $this->db->where('separate_date', $date);
                                    $this->db->where('individual_channel_id', "$channel_id");
                                    $this->db->update(RATE_BASE, $product_avail);
                                   
                                }
                                else
                                {   $product_avail['separate_date'] = $date;
                                    $product_avail['trigger_cal'] = 0;
                                    $product_avail['room_id'] =$product['room_id'];
                                    $product_avail['rate_types_id'] =$product['rate_id'];
                                    $product_avail['owner_id'] = $userID;
                                    $product_avail['hotel_id'] = hotel_id();
                                    $product_avail['individual_channel_id']= "$channel_id";
                                    $this->db->insert(RATE_BASE, $product_avail);
                                }
                            }

                            $chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row()->xml_type;

                            if($chk_allow==2 || $chk_allow==3)
                            {
                                $this->booking_model->bulk_update($product,$room_mapping[0]->import_mapping_id,$room_mapping[0]->mapping_id,$price);
                            }
                      }

                      
                    }

                }
            }


            return true;
     
    }
    
    function subratesave($product=false, $options=false, $categories=false)
    {
        //echo "I am in subratesave";
        $sub_roomm  = $this->input->post('sub_rate');
        
        $clean=$this->cleanArray($sub_roomm);
        
        if(is_array($clean))
        {
            $sub_roomd['start_date'] = $this->input->post('start_date');
                
            $sub_roomd['end_date'] = $this->input->post('end_date');
    
            if($this->input->post('channel_id')!="")
            {
                $channel_id = $this->input->post('channel_id');
                $up_channel_id = $this->input->post('channel_id');
            }
            else
            {
                $channel_id = '';
                $up_channel_id = '';
            }
            
            if($this->input->post('days')!='')
            {
                $sub_rooms['days']    = implode(',',$this->input->post('days'));
            }
            else
            {
                $sub_rooms['days']    = ('1,2,3,4,5,6,7');
            }
            
            $up_days =  explode(',',$sub_rooms['days']);
    
            $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $up_end_date = date('d.m.Y',strtotime(str_replace('/','-',$sub_roomd['end_date'])));

            
            $re_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $re_end_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['end_date'])));
            
            $hotelbed_start = str_replace('-', '', $re_sart_date);
            $hotelbed_end = str_replace('-', '', $re_end_date);
            
            $datetime1 = new DateTime($re_sart_date);
            $datetime2 = new DateTime($re_end_date);
            $interval = $datetime1->diff($datetime2);
            $number_of_days = $interval->format('%a%')+1;
            $channel_bnow = '';
            $channel_travel = '';
            $channel_wbeds = '';
            if($channel_id!='')
            {
				if (($key = array_search('17', $channel_id)) !== false) 
				{
					unset($channel_id[$key]);
					$channel_bnow = 'bnow';
				}
				if(($key = array_search('15', $channel_id)) !== false){
                    unset($channel_id[$key]);
                    $channel_travel = 'travel';
                }

                if(($key = array_search('14', $channel_id)) !== false){
                    unset($channel_id[$key]);
                    $channel_wbeds = 'wbeds';
                }
                foreach($channel_id as $con_channel)
                {
                    foreach($clean as $id=>$rooms)
                    {
                        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$id))->row();
                        
                        foreach($rooms as $rate_types_id=>$room)
                        {
                            $rate_details = get_data(RATE_TYPES,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_type_id'=>$rate_types_id,'room_id'=>$id))->row();
                            if(count($rate_details) != 0){
                                if($rate_details->meal_plan=='1' || $rate_details->meal_plan=='0'){$meal_plan=0;}elseif($rate_details->meal_plan=='2'){$meal_plan=1;}elseif($rate_details->meal_plan=='3'){$meal_plan=3;}elseif($rate_details->meal_plan=='4'){$meal_plan=0;}elseif($rate_details->meal_plan=='5' || $rate_details->meal_plan=='6'){$meal_plan=2;}
                            }else{
                                $meal_plan = 0;
                            }
                            foreach($room as $guest_count=>$type)
                            {
                                foreach($type as $refun=>$value)
                                {
                                    if(@$value['cta'] == 2){
                                        $value['cta'] = 0;
                                    }
                                    if(@$value['ctd'] == 2){
                                        $value['ctd'] = 0;
                                    }
                                    if(isset($value['open_room'])=='')
                                    {
                                        if(isset($value['stop_sell'])!='')
                                        {
                                            $stop_sale =$value['stop_sell'];
                                        }
                                        elseif(isset($value['stop_sell'])=='')
                                        {
                                            $stop_sale ='0';
                                        }
                                    }
                                    else if(isset($value['open_room'])!='')
                                    {
                                        $stop_sale ='remove';
                                    }
                                    
                                    if(in_array('1', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $monday_cta = '0';
                                        }
                                        else
                                        {
                                            $monday_cta ='1';
                                        }
                                        $exp_sun = 'true';
                                    }
                                    else 
                                    {
                                        $monday_cta ='1';
                                        $exp_sun = 'false';
                                    }
                                    if(in_array('2', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $tuesday_cta ='0';
                                        }
                                        else
                                        {
                                            $tuesday_cta ='1';
                                        }
                                        $exp_mon = 'true';
                                    }
                                    else 
                                    {
                                        $tuesday_cta ='1';
                                        $exp_mon = 'false';
                                    }
                                    if(in_array('3', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $wednesday_cta = '0';
                                        }
                                        else
                                        {
                                            $wednesday_cta ='1';
                                        }
                                        $exp_tue = 'true';
                                    }
                                    else 
                                    {
                                        $wednesday_cta ='1';
                                        $exp_tue = 'false';
                                    }
                                    if(in_array('4', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $thursday_cta ='0';
                                        }
                                        else
                                        {
                                            $thursday_cta ='1';
                                            
                                        }
                                        $exp_wed = 'true';
                                    }
                                    else
                                     {
                                        $thursday_cta ='1';
                                        $exp_wed = 'false';
                                    }
                                    if(in_array('5', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $friday_cta ='0';
                                        }
                                        else
                                        {
                                            $friday_cta ='1';
                                        }
                                        $exp_thur = 'true';
                                    }
                                    else 
                                    {
                                        $friday_cta ='1';
                                        $exp_thur = 'false';
                                    }
                                    if(in_array('6', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $saturday_cta = '0';
                                        }
                                        else
                                        {
                                            $saturday_cta ='1';
                                        }
                                        $exp_fri = 'true';
                                    }
                                    else 
                                    {
                                        $saturday_cta ='0';
                                        $exp_fri = 'false';
                                    }
                                    if(in_array('7', $up_days)) 
                                    {
                                        if(isset($value['cta'])=='' || $value['cta'] == '0')
                                        {
                                            $sunday_cta = '0';
                                        }
                                        else
                                        {
                                            $sunday_cta ='1';
                                            
                                        }
                                        $exp_sat = 'true';
                                    }
                                    else 
                                    {
                                        $sunday_cta ='1';
                                        $exp_sat = 'false';
                                    }
                                    if($stop_sale!='remove')
                                    {
                                        if($stop_sale!='1')
                                        {
                                            if(@$product['availability']!='')
                                            {
                                                $ch_update_avail = trim(str_repeat('='.$value['availability'].',',$number_of_days),',');
                                                $exp_available = @$product['availability'];
                                            }
                                            else
                                            {
                                                $ch_update_avail = '';
                                                $exp_available = '';
                                            }
                                        }
                                        else if($stop_sale=='1')
                                        {
                                            $ch_update_avail=trim(str_repeat('=-100'.',',$number_of_days),',');
                                            $exp_available = '';
                                        }
                                        else
                                        {
                                            $ch_update_avail='';
                                            $exp_available = '';
                                        }
                                    }
                                    elseif($stop_sale=='remove')
                                    {
                                        $ch_update_avail=trim(str_repeat('=1'.',',$number_of_days),',');
                                    }
                                    if(@$value['minimum_stay']!='')
                                    {
                                        $minimum_stay = $value['minimum_stay'];
                                    }
                                    else
                                    {
                                        $minimum_stay = 0;
                                    }
                                    
                                    if(@$value['non_refund_amount']!='')
                                    {
                                        $value_price = $value['non_refund_amount'];
                                    }
                                    elseif(@$value['refund_amount']!='')
                                    {
                                        $value_price = $value['refund_amount'];
                                    }
                                    else
                                    {
                                        $value_price = '0';
                                    }
                                    $original_price = $value_price;
                                    if(@$value['cta'] == "0" || @$value['cta'] != ""){
                                        $exp_cta = "false";
                                    }else{
                                        $exp_cta = "true";
                                    }
                                    if(@$value['ctd'] == "0" || @$value['ctd'] != ""){
                                        $exp_ctd = "false";
                                    }else{
                                        $exp_ctd = "true";
                                    }
                                    $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->count_all_results();
                                        
                                    if($count!=0)
                                    {
                                        $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel,'property_id'=>$id,'rate_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'enabled'=>'enabled'))->result();
                                        
                                        if($room_mapping)
                                        {
                                            foreach($room_mapping as $room_value)
                                            {
                                                if($value_price != '0'){
                                                    if($room_value->rate_conversion != "1"){
                                                        $rate_converted = 1;
                                                        if(strpos($room_value->rate_conversion, '.') !== false){
                                                            $value_price = $value_price * $room_value->rate_conversion;
                                                        }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                                            $mul = str_replace(',', '.', $room_value->rate_conversion);
                                                            $value_price = $value_price * $mul;
                                                        }else if(is_numeric($value_price)){
                                                            $value_price = $value_price * $room_value->rate_conversion;
                                                        }
                                                    }
                                                }
                                                if($room_value->channel_id=='11')
                                                {
                                                    if($value_price!='0' || $ch_update_avail!='')
                                                    {
                                                        
                                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();
                                                    if($ch_details->mode == 0){
                                                        $urls = explode(',', $ch_details->test_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $reco[$path[0]] = $path[1];
                                                        }
                                                    }else if($ch_details->mode == 1){
                                                        $urls = explode(',', $ch_details->live_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $reco[$path[0]] = $path[1];
                                                        }
                                                    }  
                                    
                                                    $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                                                    if(@$value['non_refund_amount']!='')
                                                    {
                                                        $value_price = $value['non_refund_amount'];
                                                    }
                                                    elseif(@$value['refund_amount']!='')
                                                    {
                                                        $value_price = $value['refund_amount'];
                                                    }
                                                    else
                                                    {
                                                        $value_price = '0';
                                                    }
                                                    if($value_price!='0' && $room_value->update_rate=='1')
                                                    {
                                                        $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                        if($mapping_values)
                                                        {
                                                            $label=$mapping_values['label'];
                                                            $val=$mapping_values['value'];  
                                                            $label_split=explode(",",$label);
                                                            $val_split=explode(",",$val);
                                                            $set_arr=array_combine($label_split,$val_split);
                                                            $i=0;
                                                            $mapping_fields='';
                                                            foreach($set_arr as $k=>$v)
                                                            {
                                                                if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
                                                                    if(strpos($v, '+') !== FALSE){
                                                                        $opr = explode('+', $v);
                                                                        if(is_numeric($opr[1])){
                                                                            $ex_price = $value_price + $opr[1];
                                                                        }else if(is_numeric($opr[0])){
                                                                            $ex_price = $value_price + $opr[0];
                                                                        }else{
                                                                            if(strpos($opr[1], '%')){
                                                                                $per = explode('%',$opr[1]);
                                                                                if(is_numeric($per[0])){
                                                                                    $per_price = ($value_price * $per[0]) / 100;
                                                                                    $ex_price = $value_price + $per_price;
                                                                                }
                                                                            }elseif (strpos($opr[0], '%')) {
                                                                                $per = explode('%',$opr[0]);
                                                                                if(is_numeric($per[0])){
                                                                                    $per_price = ($value_price * $per[0]) / 100;
                                                                                    $ex_price = $value_price + $per_price;
                                                                                }
                                                                            }
                                                                        }
                                                                    }elseif (strpos($v, '-') !== FALSE) {
                                                                        $opr = explode('-', $v);
                                                                        if(is_numeric($opr[1])){
                                                                            $ex_price = $value_price - $opr[1];
                                                                        }elseif (is_numeric($opr[0])) {
                                                                            $ex_price = $value_price - $opr[0];
                                                                        }else{
                                                                            if(strpos($opr[1],'%') !== FALSE){
                                                                                $per = explode('%',$opr[1]);
                                                                                if(is_numeric($per[0])){
                                                                                    $per_price = ($value_price * $per[0]) / 100;
                                                                                    $ex_price =$value_price - $per_price;
                                                                                }
                                                                            }elseif (strpos($opr[0],'%') !== FALSE) {
                                                                                $per = explode('%',$opr[0]);
                                                                                if(is_numeric($per[0])){
                                                                                    $per_price = ($value_price * $per[0]) / 100;
                                                                                    $ex_price = $value_price - $per_price;
                                                                                }
                                                                            }
                                                                        }
                                                                    }elseif (strpos($v, '%') !== FALSE) {
                                                                        $opr = explode('%', $v);
                                                                        if(is_numeric($opr[1])){
                                                                            $per_price = ($value_price * $opr[1]) / 100;
                                                                            $ex_price = $value_price + $per_price;
                                                                        }elseif (is_numeric($opr[0])) {
                                                                            $per_price = ($value_price* $opr[0]) / 100;
                                                                            $ex_price = $value_price + $per_price;
                                                                        }
                                                                    }else{
                                                                        $ex_price = $value_price + $v;
                                                                    }
                                                                    
                                                                    $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                                                }else{
                                                                    $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                                                }
                                                            }
                                                        }
                                                        $url = trim($reco['urate_avail']);
                                                        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                                        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                                        <soap12:Body>
                                                        <UpdateRates xmlns="https://www.reconline.com/">
                                                        <User>'.$ch_details->user_name.'</User>
                                                        <Password>'.$ch_details->user_password.'</Password>
                                                        <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                                        <idSystem>0</idSystem>
                                                        <ForeignPropCode></ForeignPropCode>
                                                        <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                                        <ExcludeRateLevels></ExcludeRateLevels>
                                                        <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                                        <ExcludeRoomTypes></ExcludeRoomTypes>
                                                        <RateType>1</RateType>
                                                        <StartDate>'.$up_sart_date.'</StartDate>
                                                        <EndDate>'.$up_end_date.'</EndDate>
                                                        <SingleOcc>'.$value_price.'</SingleOcc>'.
                                                        $mapping_fields.'
                                                        <Meals>'.$meal_plan.'</Meals>
                                                        <MinStay>'.$minimum_stay.'</MinStay>
                                                        <BlockStay>0</BlockStay>
                                                        <Guarantee>0</Guarantee>
                                                        <Cancel></Cancel>
                                                        <CTAMonday>'.$monday_cta.'</CTAMonday>
                                                        <CTATuesday>'.$tuesday_cta.'</CTATuesday>
                                                        <CTAWednesday>'.$wednesday_cta.'</CTAWednesday>
                                                        <CTAThursday>'.$thursday_cta.'</CTAThursday>
                                                        <CTAFriday>'.$friday_cta.'</CTAFriday>
                                                        <CTASaturday>'.$saturday_cta.'</CTASaturday>
                                                        <CTASunday>'.$sunday_cta.'</CTASunday>
                                                        </UpdateRates>
                                                        </soap12:Body>
                                                        </soap12:Envelope>';
                                                        $headers = array(
                                                                            "Content-type: application/soap+xml; charset=utf-8",
                                                                            "Host:www.reconline.com",
                                                                            "Content-length: ".strlen($xml_post_string),
                                                                            ); 
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                        curl_setopt($ch, CURLOPT_URL, $url);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                        curl_setopt($ch, CURLOPT_POST, true);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                        $ss = curl_getinfo($ch);                
                                                        $response = curl_exec($ch);
                                                        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                        $xml = simplexml_load_string($xml);
                                                        $json = json_encode($xml);
                                                        $responseArray = json_decode($json,true);
                                                        $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                                        $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                                        if(count($Errorarray)=='0' && count($soapFault)=='0')
                                                        {
                                                            $reconline_price_response = "success";
                                                        }
                                                        else 
                                                        {
                                                            $reconline_price_response = "error";
                                                            if(count($Errorarray)!='0')
                                                            {
                                                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));
                                                                $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                                            }
                                                            else if(count($soapFault)!='0')
                                                            {    
                                                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));  
                                                                $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                                            }
                                                        }
                                                        curl_close($ch);
                                                    }
                                                    if($ch_update_avail!='' && $room_value->update_availability=='1')
                                                    {
                                                        $url = trim($reco['urate_avail']);
                                                        $xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
                                                        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                                        <soap12:Body>
                                                        <UpdateAvail xmlns="https://www.reconline.com/"> 
                                                        <User>'.$ch_details->user_name.'</User>
                                                        <Password>'.$ch_details->user_password.'</Password>
                                                        <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                                        <idSystem>0</idSystem>
                                                        <ForeignPropCode></ForeignPropCode>
                                                        <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                                        <ExcludeRateLevels></ExcludeRateLevels>
                                                        <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                                        <ExcludeRoomTypes></ExcludeRoomTypes>
                                                        <AvailMode>0</AvailMode>
                                                        <StartDate>'.$up_sart_date.'</StartDate>
                                                        <Availability>'.$ch_update_avail.'</Availability>
                                                        </UpdateAvail>
                                                        </soap12:Body>
                                                        </soap12:Envelope>';
                                                        $headers_avail = array(
                                                                            "Content-type: application/soap+xml; charset=utf-8",
                                                                            "Host:www.reconline.com",
                                                                            "Content-length: ".strlen($xml_post_string_update),
                                                                            ); 
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                        curl_setopt($ch, CURLOPT_URL, $url);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                        curl_setopt($ch, CURLOPT_POST, true);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
                                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
                                                        $ss = curl_getinfo($ch);                
                                                        $response = curl_exec($ch);
                                                        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                        $xml = simplexml_load_string($xml);
                                                        $json = json_encode($xml);
                                                        $responseArray = json_decode($json,true);
                                                        $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                                        $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                                        if(count($Errorarray)=='0' && count($soapFault)=='0')
                                                        {
                                                            $reconline_availability_response="success";
                                                        }
                                                        else 
                                                        {
                                                            $reconline_price_response = "error";
                                                            if(count($Errorarray)!='0')
                                                            {
                                                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));
                                                                $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                                            }
                                                            else if(count($soapFault)!='0')
                                                            {    
                                                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));  
                                                                $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                                            }
                                                            return false;
                                                        }
                                                        curl_close($ch);
                                                    }
                                                
                                                    }
                                                }
                                                else if($room_value->channel_id=='1')
                                                {
                                                   $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();
                                                   if($ch_details->mode == 0){
                                                        $urls = explode(',', $ch_details->test_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $exp[$path[0]] = $path[1];
                                                        }
                                                    }else if($ch_details->mode == 1){
                                                        $urls = explode(',', $ch_details->live_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $exp[$path[0]] = $path[1];
                                                        }
                                                    } 
                                
                                                   $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                                    $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'hotel_channel_id' => $mp_details->hotel_channel_id,'rateType' => 'SellRate'))->row();
                                                    $oa_details = get_data('import_mapping_expedia_occupancy',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotel_channel_id' => $mp_details->hotel_channel_id,'channel'=>$room_value->channel_id))->row();
                                                    $minlos = $mp_details->minLos;
                                                    $maxLos = $mp_details->maxLos;
                                                    $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                    if($mapping_values){
                                                        if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
                                                            if($minlos < $mapping_values['value']){
                                                                $maxLos = $mapping_values['value'];
                                                            }
                                                        }
                                                    }
                                                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                                            <AvailRateUpdate>';
                                                    $xml .= '<DateRange from="'.$re_sart_date.'" to="'.$re_end_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>'; 
                                                    if($room_value->explevel == "rate"){
                                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" >';
                                                        if($ch_update_avail!='' && $room_value->update_availability =='1'){
                                                             $xml .= '<Inventory totalInventoryAvailable="'.$value['availability'].'"/>';
                                                        }
                                                        if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                                            $plan_id = $mp_details->rateplan_id;
                                                        }else{
                                                            $plan_id = $mp_details->rate_type_id;
                                                        }
                                                        if($stop_sale == "1"){
                                                            $xml .= '<RatePlan id="'.$plan_id.'" closed="true">';
                                                        }else if($stop_sale == "0"){
                                                            $xml .= '<RatePlan id="'.$plan_id.'">';
                                                        }else if($stop_sale == "remove"){
                                                            $xml .= '<RatePlan id="'.$plan_id.'" closed="false">';
                                                        }
                                                        //$value_price!='0' && $value_price >=(string)$rt_details->minAmount && $value_price <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                                                        if($value_price!='0' && $room_value->update_rate=='1'){
                                                            if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                        <PerDay rate="'.$value_price.'"/>
                                                                        </Rate>';
                                                            }
                                                            }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                                                $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$value_price.'"/>
                                                                        </Rate> ';
                                                            }
															elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
															{
																$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$value_price.'" occupancy = "2"/></Rate> ';
																$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
															}
                                                        }else{
                                                            if($value_price != "0" && $room_value->update_rate=='1'){
                                                                if($value_price <=(string)$rt_details->minAmount && $value_price >= (string)$rt_details->maxAmount){
                                                                    $this->session->set_flashdata("price_error", "Price must be between ".$rt_details->minAmount." and ".$rt_details->maxAmount);
                                                                }
                                                            }
                                                        }
                                                    
                                                        if($exp_ctd != "" || $exp_cta != "" || @$value['minimum_stay'] != ""){
                                                            $xml .= '<Restrictions';
                                                            $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                                            $xml .= ' closedToArrival="'.$exp_cta.'"';
                                                            
                                                            if(@$value['minimum_stay'] != ""){
                                                                $xml .= ' minLOS="'.@$value['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                                            }
                                                            $xml .= ' />';
                                                        }
                                                        $xml .= "</RatePlan>";
                                                    }else if($room_value->explevel == "room"){
                                                        if($stop_sale == "1"){
                                                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                                                        }else if($stop_sale == "0"){
                                                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                                        }else if($stop_sale == "remove"){
                                                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                                                        }
                                                        if($ch_update_avail!='' && $room_value->update_availability =='1'){
                                                             $xml .= '<Inventory totalInventoryAvailable="'.$value['availability'].'"/>';
                                                        }
                                                        $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                                        foreach($available_plans as $e_plan){
                                                            if($e_plan->rateAcquisitionType != "Linked"){

                                                                if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                                                    $plan_id = $e_plan->rateplan_id;
                                                                }else{
                                                                    $plan_id = $e_plan->rate_type_id;
                                                                }
                                                                $xml .= '<RatePlan id="'.$plan_id.'">';

                                                                if($value_price!='0' && $room_value->update_rate=='1'){
                                                                    if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                                    for($i = $minlos; $i<=$maxLos; $i++){
                                                                        $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                                <PerDay rate="'.$value_price.'"/>
                                                                                </Rate>';
                                                                    }
                                                                    }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                                                        $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$value_price.'"/>
                                                                                </Rate> ';
                                                                    }
																	elseif($e_plan->pricingModel == 'OccupancyBasedPricing')
																	{
																		$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$value_price.'" occupancy = "2"/></Rate> ';
																		$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
																	}
                                                                }else{
                                                                    if($value_price != "0" && $room_value->update_rate=='1'){
                                                                        if($value_price <=(string)$rt_details->minAmount && $value_price >= (string)$rt_details->maxAmount){
                                                                            $this->session->set_flashdata("price_error", "Price must be between ".$rt_details->minAmount." and ".$rt_details->maxAmount);
                                                                        }
                                                                    }
                                                                }
                                                            
                                                                if($exp_ctd != "" || $exp_cta != "" || @$value['minimum_stay'] != ""){
                                                                    $xml .= '<Restrictions';
                                                                    $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                                                    $xml .= ' closedToArrival="'.$exp_cta.'"';
                                                                    
                                                                    if(@$value['minimum_stay'] != ""){
                                                                        $xml .= ' minLOS="'.@$value['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                                                    }
                                                                    $xml .= ' />';
                                                                }
                                                                $xml .= "</RatePlan>";
                                                            }
                                                        }
                                                    }

                                                    $xml .= "</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
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
                                                    $data = simplexml_load_string($output); 
                                                    $response = $data->Error;
                                                    if($response!='')
                                                    {
                                                       // echo 'fail';
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));
                                                        $this->session->set_flashdata('bulk_error',(string)$response);
                                                        $expedia_update = "Failed";
                                                    }
                                                    else
                                                    {
                                                       // echo 'success   ';
                                                        $expedia_update = "Success";
                                                    }

                                                    curl_close($ch);


                                                }
												else if($room_value->channel_id == '5')
												{
                                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                                                    if($ch_details->mode == 0){
                                                        $urls = explode(',', $ch_details->test_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $htb[$path[0]] = $path[1];
                                                        }
                                                    }else if($ch_details->mode == 1){
                                                        $urls = explode(',', $ch_details->live_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $htb[$path[0]] = $path[1];
                                                        }
                                                    }  
                                                    $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                                    $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                                    $maxnum = 99;
                                                    if($mapping_values){
                                                        if($mapping_values['label']== "MaximumNoOfDays")
                                                        {
                                                            $maxnum = $mapping_values['value'];                                        
                                                        }
                                                    }


                                                    $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                                        <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                                        <getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                                        <HSI_ContractInventoryModificationRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                                                        <Language>ENG</Language>
                                                        <Credentials>
                                                            <User>'.$ch_details->user_name.'</User>
                                                            <Password>'.$ch_details->user_password.'</Password>
                                                        </Credentials>
                                                        <Contract>
                                                            <Name>'.$mp_details->contract_name.'</Name>
                                                            <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                                            <Sequence>'.$mp_details->sequence.'</Sequence>
                                                        </Contract>
                                                        <InventoryItem>
                                                            <DateFrom date="'.$hotelbed_start.'"/>
                                                            <DateTo date="'.$hotelbed_end.'"/>
                                                            <RateCode>0</RateCode>';
                                                    if(@$value['availability'] != "" && $room_value->update_availability=='1'){
													if($stop_sale=="0"){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'">';
													}else if($stop_sale =="1"){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'" closed="Y">';
													}else if($stop_sale=='remove'){
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'" closed="N">';
													}
													else
													{
														$xml_post_string .= '<Room available="'.@$value['availability'].'" quote="'.@$value['availability'].'">';
													}									
												}else{
													
													if($stop_sale=="0")
													{
														$xml_post_string .= '<Room>';
													}
													else if($stop_sale=="1")
													{
														$xml_post_string .= '<Room closed="Y">';
													}
													else if($stop_sale=='remove')
													{
														$xml_post_string .= '<Room closed="N">';
													}
													else
													{
														$xml_post_string .= '<Room>';
													}									
												}
                                                    
                                                    $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                                    if($value_price != "" && $room_value->update_rate == '1'){
                                                        $xml_post_string .= '<Price><Amount>'.$value_price.'</Amount></Price>';
                                                    }
                                                    $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                                                     </soapenv:Envelope>';
                                                    $headers = array(
                                                    "SOAPAction:no-action",
                                                    "Content-length: ".strlen($xml_post_string),
                                                    ); 
                                                    $url = trim($htb['urate_avail']);

                                                    // PHP cURL  for https connection with auth
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                                                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                    curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                    curl_setopt($ch, CURLOPT_POST, true);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                    curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                    $ss = curl_getinfo($ch);                
                                                    $response = curl_exec($ch);
                                                    //echo $response;
                                                    //echo "<pre>";
                                                    
                                                    $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                    $xml_parse = simplexml_load_string($xmlreplace);
                                                    $json = json_encode($xml_parse);
                                                    $responseArray = json_decode($json,true);
                                                    //print_r($responseArray);

                                                    $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                                                    //print_r($xml);
                                                    $status = $xml->ErrorList->Error;
                                                    if($xml->ErrorList->Error){
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));
                                                        $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                                    }else if($xml->Status != "Y"){
                                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,'Try Again','Bulk Update Sub Rate Save',date('m/d/Y h:i:s a', time()));
                                                        $this->session->set_flashdata('bulk_error', "Try Again");
                                                    }

                                                    if(@$value['minimum_stay'] != ""){
                                                        $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                                            <soapenv:Envelope
                                                            soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                                                            xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                                                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                                            <getHSIContractDetailModification
                                                            xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                                            <HSI_ContractDetailModificationRQ>
                                                            <Language>ENG</Language>
                                                            <Credentials>
                                                            <User>'.$ch_details->user_name.'</User>
                                                            <Password>'.$ch_details->user_password.'</Password>
                                                            </Credentials>
                                                            <Contract>
                                                            <Name>'.$mp_details->contract_name.'</Name>
                                                            <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                                            <Sequence>'.$mp_details->sequence.'</Sequence>
                                                            </Contract>
                                                            <MinimumStayList>
                                                            <MinimumStay>
                                                            <DateFrom date="'.$hotelbed_start.'"/>
                                                            <DateTo date="'.$hotelbed_end.'"/>
                                                            <MinNumberOfDays>'.$value['minimum_stay'].'</MinNumberOfDays>
                                                            <MaxNumberOfDays>'.$maxnum.'</MaxNumberOfDays>
                                                            <RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>
                                                            </MinimumStay>
                                                            </MinimumStayList>
                                                            </HSI_ContractDetailModificationRQ>
                                                            </getHSIContractDetailModification>
                                                            </soapenv:Body>
                                                            </soapenv:Envelope>';

                                                        $headers = array(
                                                        "SOAPAction:no-action",
                                                        "Content-length: ".strlen($xml_post_string),
                                                        ); 
                                                        $url = trim($htb['urate_avail']);

                                                        // PHP cURL  for https connection with auth
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                        curl_setopt($ch, CURLOPT_URL, $url);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                                                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                                        curl_setopt($ch, CURLOPT_POST, true);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                        curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                        $ss = curl_getinfo($ch);                
                                                        $response = curl_exec($ch);
                                                        //echo $response;
                                                        
                                                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                                        $xml_parse = simplexml_load_string($xmlreplace);
                                                        $json = json_encode($xml_parse);
                                                        $responseArray = json_decode($json,true);

                                                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractDetailModification']);
                                                       // print_r($xml);  
                                                        $status = $xml->ErrorList->Error;
                                                        if($xml->ErrorList->Error){
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Bulk Update Sub  Rate Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                                        }else if($xml->Status != "Y"){
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,'Try Again','Bulk Update Sub Rate Save',date('m/d/Y h:i:s a', time()));
                                                            $this->session->set_flashdata('bulk_error', "Try Again");
                                                        }
                                                    }

                                                }
												else if($room_value->channel_id=='8')
												{
                                                   $srat_array=explode('/',$sub_roomd['start_date']);
                                                    $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0]; 
                                                    $enddate_array=explode('/',$sub_roomd['end_date']);
                                                    $xml_end_date=$enddate_array[2].'-'.$enddate_array[1].'-'.$enddate_array[0];   
                                                    $days=$sub_rooms['days'];
                                                    $days_array=explode(',',$days);
                                                    $dayval="";

                                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row();

                                                    if($ch_details->mode == 0){
                                                        $urls = explode(',', $ch_details->test_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $gta[$path[0]] = $path[1];
                                                        }
                                                    }else if($ch_details->mode == 1){
                                                        $urls = explode(',', $ch_details->live_url);
                                                        foreach($urls as $url){
                                                            $path = explode("~",$url);
                                                            $gta[$path[0]] = $path[1];
                                                        }
                                                    } 
                                    
                                                    $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                                     
                                                    $gt_room_id=$mp_details->ID;            
                                                    $rateplanid=$mp_details->rateplan_id;
                                                    $MinPax=$mp_details->MinPax;
                                                    $peakrate=$mp_details->peakrate;
                                                    $MaxOccupancy=$mp_details->MaxOccupancy;
                                                    $contract_type=$mp_details->contract_type;
                                                    if(@$value['minimum_stay']!='')
                                                    {
                                                        $minnights = $value['minimum_stay'];
                                                        $this->db->where('GTA_id', $room_value->import_mapping_id);
                                                        $updatemin=array('minnights'=>$minnights);
                                                        $this->db->update('import_mapping_GTA', $updatemin);
                                                    }else
                                                    {
                                                        $minnights=$mp_details->minnights;
                                                    }
                                                    $payfullperiod=$mp_details->payfullperiod;
                                                    $contract_id=$mp_details->contract_id;  

                                                    $hotel_channel_id=$mp_details->hotel_channel_id;

                                                    if (in_array("1", $days_array)) {
                                                     $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }

                                                    if (in_array("2", $days_array)) {
                                                       $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if (in_array("3", $days_array)) {
                                                       $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if (in_array("4", $days_array)) {
                                                       $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if (in_array("5",$days_array)) {
                                                        $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if (in_array("6", $days_array)) {
                                                       $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if (in_array("7", $days_array)) {
                                                        $dayval.=1;
                                                    }else{
                                                        $dayval.=0;
                                                    }
                                                    if($value_price!=0 && $value_price!="" && $value_price >0 && $room_value->update_rate == '1'){            
                                                        $pri=$value_price;

                                                        if($contract_type=="Static"){  

                                                            $soapUrl = trim($gta['urate_s']);
                                                            $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                                            xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                                            GTA_RateCreateRQ.xsd">
                                                             <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                                            <RatePlan Id="'.$rateplanid.'">
                                                            <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                                            DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                                            PeakRate="'.$peakrate.'">
                                                            <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                                            </StaticRate>
                                                            </RatePlan>
                                                            </GTA_StaticRatesCreateRQ>';
                                                                                               

                                                            $ch = curl_init($soapUrl);

                                                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                                            curl_setopt($ch, CURLOPT_POST, 1);
                                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            $output = curl_exec($ch);
                                                              curl_close($ch);  
                                                            $data = simplexml_load_string($output);
                                                        }else{

                                                            $pri=$value_price; 
                                                            $soapUrl = trim($gta['urate_m']);
                                                            $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                                                                <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                                                <RatePlan Id="'.$rateplanid.'">
                                                                <MarginRates DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'"   FullPeriod="false">
                                                                <RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'"  Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';
                              
                                                            $ch = curl_init($soapUrl);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                                            $response = curl_exec($ch);
                                                            $data = simplexml_load_string($response); 
                                                        }   

                                                    }

                                                    if($stop_sale!='1' && $room_value->update_availability == '1')
                                                    {
                                                        $srat_array=explode('/',$sub_roomd['start_date']);
                                                        $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0]; 
                                                        $enddate_array=explode('/',$sub_roomd['end_date']);
                                                        $xml_end_date=$enddate_array[2].'-'.$enddate_array[1].'-'.$enddate_array[0];   
                                                        $begin_date =$xml_start_date;
                                                              $en_date=$xml_end_date;
                                                    
                                                        $begin = new DateTime($begin_date ); 
                                                        $end = new DateTime($en_date);
                                                        $radate = new DatePeriod($begin, new DateInterval('P1D'), $end);
                                                        $daterange = [];
                                                        foreach($radate as $date){
                                                             $daterange[]= $date->format("Y-m-d");
                                                        }
                                                        if(count($daterange)>0){
                                                            $exp_edate=explode('-', $en_date);
                                                            
                                                            $edate=$exp_edate[0].'-'.$exp_edate[1].'-'.$exp_edate[2];
                                                            $daterange[]=$edate;
                                                            
                                                          
                                                        }else
                                                        {
                                                            
                                                          $exp_date=explode('-', $begin_date);
                                                          $bdate=$exp_date[0].'/'.$exp_date[1].'/'.$exp_date[2];
                                                          $daterange[]=$bdate;
                                                        }
                                                        $soapUrl=trim($gta['uavail']);

                                                        $xml_post_string='<GTA_InventoryUpdateRQ
                                                            xmlns = "http://www.gta-travel.com/GTA/2012/05"
                                                            xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                            xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05  GTA_InventoryUpdateRQhelp.xsd">
                                                             <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                                            <InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'">
                                                                <RoomStyle>';
                                            
                                                        foreach($daterange as $stdate){
                                                            
                                                            $xml_post_string.=' <StayDate Date = "'.$stdate.'"><Inventory RoomId = "'.$gt_room_id.'">';
                                                            if($stop_sale == "1"){
                                                                $xml_post_string  .= '<Restriction FlexibleStopSell = "true" InventoryType = "Flexible"/>';
                                                            }else if($stop_sale == "remove" && @$value['availability'] != ""){
                                                                $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "'.$value['availability'].'" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';
                                                            }else if($stop_sale == "remove" && $value['availability']){
                                                                $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "1" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';
                                                            }
                                                            $xml_post_string .= '</Inventory></StayDate>';
                                                        }
                                                        $xml_post_string.=' </RoomStyle>
                                                                </InventoryBlock>
                                                            </GTA_InventoryUpdateRQ>';
                                                        $ch = curl_init($soapUrl);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                                        $response = curl_exec($ch); 
                                                        $data = simplexml_load_string($response);
                                                        $Error_Array = @$data->Errors->Error;
                                                        if($Error_Array!='')
                                                        {
                                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Error_Array,'Bulk Update Rate Save',date('m/d/Y h:i:s a', time()));
                                                        }
                                                    }
                                                    if(@$value['availability']!='' && $room_value->update_availability == '1'){

                                                        $begin_date =$xml_start_date;
                                                         $en_date=$xml_end_date;
                                                        $begin = new DateTime($begin_date ); 
                                                        $end = new DateTime($en_date);
                                                        $radate = new DatePeriod($begin, new DateInterval('P1D'), $end);
                                                        $daterange = [];
                                                        foreach($radate as $date){
                                                             $daterange[]= $date->format("Y-m-d");
                                                        }
                                                        if(count($daterange)>0){
                                                            $exp_edate=explode('-', $en_date);
                                                            $edate=$exp_edate[0].'-'.$exp_edate[1].'-'.$exp_edate[2];
                                                            $daterange[]=$edate; 
                                                        }else
                                                        {
                                                            
                                                          $exp_date=explode('-', $begin_date);
                                                          $bdate=$exp_date[0].'/'.$exp_date[1].'/'.$exp_date[2];
                                                          $daterange[]=$bdate;
                                                        }
                                                        $availability= $value['availability']; 

                                                        $soapUrl=trim($gta['uavail']);
                                                        $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"    xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05   GTA_InventoryUpdateRQ.xsd">  <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /> <InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$ch_details->hotel_channel_id.'" ><RoomStyle>';
                                                        foreach($daterange as $stdate){
                                                            $xml_post_string.=' <StayDate Date = "'.$stdate.'"> <Inventory RoomId="'.$gt_room_id.'" ><Detail FreeSale="false" InventoryType="Flexible"    Quantity="'.$availability.'" ReleaseDays="0"/></Inventory></StayDate>';
                                                        }
                                                        $xml_post_string.='</RoomStyle>
                                                                        </InventoryBlock>
                                                                        </GTA_InventoryUpdateRQ>';
                                                        $ch = curl_init($soapUrl);

                                                        //$ch = curl_init($this->_serviceUrl . $id);

                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                                        $response = curl_exec($ch); 
                                                        $data = simplexml_load_string($response); 
                                                    } 
                                                }
                                                else if($room_value->channel_id == '2')
                                                {



													$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row()->xml_type;
													if($chk_allow==2 || $chk_allow==3)
													{
														$value['price'] = @$value_price;
														$value['start_date'] = $this->input->post('start_date');
														$value['end_date'] = $this->input->post('end_date');
														$value['days'] = $sub_rooms['days'];
														$this->load->model("booking_model");
														$this->booking_model->bulk_update($value,$stop_sale,$room_value->import_mapping_id,$room_value->mapping_id);
													}
                                                }

                                                else if ($room_value->channel_id == '36')
                                             {
                                                    $value['start_date'] = $this->input->post('start_date');
                                                    $value['end_date'] = $this->input->post('end_date');
                                                    $value['price'] = @$value_price;
                                                    $value['days'] = $sub_rooms['days'];
                                                    $this->load->model("despegar_model");
                                                    $this->despegar_model->bulk_update($value,$stop_sale,$room_value->import_mapping_id,$room_value->mapping_id);
                                            }

                                                if(isset($rate_converted) == 1){                                
                                                    $rateMul[$id.$room_value->channel_id] = $value_price;
                                                    $value_price = $original_price;
                                                }/*else{
                                                    $rateMul[$id.$room_value->channel_id] = @$value_price;
                                                }*/
                                                //print_r($rateMul);
                                            }
                                        }
                                    }
                                }
								$roomname = get_data(TBL_PROPERTY,array('property_id'=>$id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
								$ratename = get_data(RATE_TYPES,array('rate_type_id'=>$rate_types_id,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->rate_name;
								$user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
								$username = ucfirst($user->fname).' '.ucfirst($user->lname);
								$productdetails = "";
								$channelsname = "";
								$type = array_column($type, 'refund_amount');
								foreach($type as $key => $value)
								{
									$productdetails .= ' '.ucfirst($key).":".$value.",";

									/*if($key != "room_id" && $key != "start_date" && $key != "end_date" && $key != "days" && $key !="individual_channel_id" && $key != "channel_id"){
										$productdetails .= ' '.ucfirst($key).":".$value.",";
									}
									*/
								}
								if($channel_id != ""){
									$channelsname .= "Channels:";
									foreach ($channel_id as $channelid) {
										$channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channelid))->row()->channel_name.',';
									}
								}
								$message = "Location:Bulk Update, Start Date:".$sub_roomd['start_date'].", End Date:".$sub_roomd['end_date'].", Room:".$roomname.'-'.$ratename.'-'.$guest_count.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
								
								$this->write_log($message);
                            }
                        }
                    }
                }
				if($channel_bnow!='')
				{
					$this->bnow_model->sub_rate_bulk_update($clean,$up_days);
				}
                if($channel_travel != "")
                {
                    $this->travel_model->sub_rate_bulk_update($clean,$up_days);   
                }
                if($channel_wbeds != "")
                {
                    $this->wbeds_model->sub_rate_bulk_update($clean,$up_days);   
                }
            }
            /* $startDate = DateTime::createFromFormat("d/m/Y",$sub_roomd['start_date']);
            $endDate = DateTime::createFromFormat("d/m/Y",$sub_roomd['end_date']);
            $periodInterval = new DateInterval( "P1D" );
            $endDate->add( $periodInterval );
            $period = new DatePeriod( $startDate, $periodInterval, $endDate );
            $endDate->add( $periodInterval ); */

            $start_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['start_date'])));
            $end_date = date('Y-m-d',strtotime(str_replace('/','-',$sub_roomd['end_date'])));

            if(@$sub_rooms['days'] != "")
            {
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,@$sub_rooms['days']);
            }else{
                $period = $this->getDateForSpecificDayBetweenDates($start_date,$end_date,'1,2,3,4,5,6,7');
            }
            foreach($clean as $id=>$rooms)
            {
                $sub_rooms['room_id']   = $id;
                
                $ch_sub_rooms['room_id']   = $id;
                
                foreach($rooms as $rate_types_id=>$room)
                {
                    $sub_rooms['rate_types_id'] = $rate_types_id;
                    
                    $ch_sub_rooms['rate_types_id'] = $rate_types_id;
                    
                    foreach($room as $guest_count=>$type)
                    {
                        $sub_rooms['guest_count'] = $guest_count;
                        
                        $ch_sub_rooms['guest_count'] = $guest_count;
                    
                        foreach($type as $refun=>$value)
                        {
                            foreach($period as $date)
                            {
                                if($this->input->post('maincal') != '')
                                {
                                    if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                    {
                                        $available = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$id,'rate_types_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>'0','separate_date'=>$date))->row_array();
                                    }
                                    else if(user_type()=='2')
                                    {
                                        $available = get_data(RATE_ADD,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'rate_types_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>'0','separate_date'=>$date))->row_array();
                                    }
                                    if(count($available)==0)
                                    {
                                        if($this->input->post('days')!='')
                                        {
                                            $sub_rooms['days']    = implode(',',$this->input->post('days'));
                                        }
                                        else
                                        {
                                            $sub_rooms['days']    = ('1,2,3,4,5,6,7');
                                        }
                                        $sub_rooms['separate_date']=$date;
                                        $sub_rooms['refun_type']   = $refun;
                                        if(@$value['non_refund_amount']!='')
                                        {
                                            $sub_rooms['non_refund_amount'] = $value['non_refund_amount'];
                                        }
                                        else
                                        {
                                            $sub_rooms['non_refund_amount'] = '';
                                        }
                                        if(@$value['refund_amount']!='')
                                        {
                                            $sub_rooms['refund_amount'] = $value['refund_amount'];
                                        }
                                        else
                                        {
                                            $sub_rooms['refund_amount'] = '';
                                        }
                                        
                                        /*if(@$value['availability']!='')
                                        {
                                            $sub_rooms['availability'] = $value['availability'];
                                        }
                                        else
                                        {
                                            $sub_rooms['availability'] = '';
                                        }*/
                                        if(@$value['minimum_stay']!='')
                                        {
                                            $sub_rooms['minimum_stay'] = $value['minimum_stay'];
                                        }
                                        else
                                        {
                                            $sub_rooms['minimum_stay'] = '';
                                        }
                                        if(@$value['cta']!='')
                                        {
                                            $sub_rooms['cta'] = $value['cta'];
                                        }
                                        else
                                        {
                                            $sub_rooms['cta'] = '';
                                        }
                                            
                                        if(@$value['ctd']!='')
                                        {
                                            $sub_rooms['ctd'] = $value['ctd'];
                                        }
                                        else
                                        {
                                            $sub_rooms['ctd'] = '';
                                        }
                                        if(@$value['stop_sell']!='')
                                        {
                                            $sub_rooms['stop_sell'] = $value['stop_sell'];
                                        }
                                        else
                                        {
                                            $sub_rooms['stop_sell'] = '';
                                        }
                                        if(@$value['open_room']=='')
                                        {
                                            if(@$value['stop_sell']!='')
                                            {
                                                $sub_rooms_up['stop_sell'] = $value['stop_sell'];
                                                $sub_rooms_up['open_room'] = 0;
                                            }
                                            else
                                            {
                                                $sub_rooms_up['stop_sell'] = '';
                                            }
                                        }
                                        if(@$value['open_room']!='')
                                        {
                                            $sub_rooms_up['open_room'] = $value['open_room'];
                                            $sub_rooms['stop_sell'] = 0;
                                        }
                                        else
                                        {
                                            $sub_rooms_up['open_room'] = '';
                                        }
                                        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                        {
                                            $sub_rooms['owner_id'] = user_id();
                                        }
                                        else if(user_type()=='2')
                                        {
                                            $sub_rooms['owner_id'] = owner_id();
                                        }
                                        $sub_rooms['hotel_id'] = hotel_id();
                                        $sub_rooms['individual_channel_id'] = '0';
                                        $this->db->insert(RATE_ADD, $sub_rooms);
                                    }
                                    else
                                    {
                                        if($this->input->post('days')!='')
                                        {
                                            $sub_rooms_up['days']     = implode(',',$this->input->post('days'));
                                        }
                                        else
                                        {
                                            $sub_rooms_up['days']     = ('1,2,3,4,5,6,7');
                                        }
                                        if(@$value['non_refund_amount']!='')
                                        {
                                            $sub_rooms_up['non_refund_amount'] = $value['non_refund_amount'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['non_refund_amount'] = $available['non_refund_amount'];
                                        }
                                        if(@$value['refund_amount']!='')
                                        {
                                            $sub_rooms_up['refund_amount'] = $value['refund_amount'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['refund_amount'] = $available['refund_amount'];
                                        }
                                        
                                        /*if(@$value['availability']!='')
                                        {
                                            $sub_rooms_up['availability'] = $value['availability'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['availability'] = $available['availability'];
                                        }*/
                                        if(@$value['minimum_stay']!='')
                                        {
                                            $sub_rooms_up['minimum_stay'] = $value['minimum_stay'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['minimum_stay'] = $available['minimum_stay'];
                                        }
                                        if(@$value['cta']!='')
                                        {
                                            $sub_rooms_up['cta'] = $value['cta'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['cta'] = $available['cta'];
                                        }
                                            
                                        if(@$value['ctd']!='')
                                        {
                                            $sub_rooms_up['ctd'] = $value['ctd'];
                                        }
                                        else
                                        {
                                            $sub_rooms_up['ctd'] = $available['ctd'];
                                        }
                                        if(@$value['open_room']=='')
                                        {
                                            if(@$value['stop_sell']!='')
                                            {
                                                $sub_rooms_up['stop_sell'] = $value['stop_sell'];
                                                 $sub_rooms_up['open_room'] = 0;
                                            }
                                            else
                                            {
                                                $sub_rooms_up['stop_sell'] = $available['stop_sell'];
                                            }
                                        }
                                        if(@$value['open_room']!='')
                                        {
                                            $sub_rooms_up['open_room'] = $value['open_room'];
                                            $sub_rooms_up['stop_sell'] = 0;
                                        }
                                        else
                                        {
                                            $sub_rooms_up['open_room'] = $available['open_room'];
                                        }
                                        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                        {
                                            $this->db->where('owner_id', user_id());
                                        }
                                        else if(user_type()=='2')
                                        {
                                            $this->db->where('owner_id', owner_id());
                                        }
                                        $this->db->where('hotel_id', hotel_id());
                                        $this->db->where('room_id', $id);
                                        $this->db->where('rate_types_id', $rate_types_id);
                                        $this->db->where('separate_date', $date);
                                        $this->db->where('refun_type', $refun);
                                        $this->db->where('guest_count', $guest_count);
                                        $this->db->where('individual_channel_id','0');
                                        $this->db->update(RATE_ADD, $sub_rooms_up);
                                    }
                                }
                                if($up_channel_id!='')
                                {
                                    foreach($up_channel_id as $con_channel)
                                    {
										if($con_channel==2)
										{
											$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_channel))->row()->xml_type;
										}
										else
										{	
											$chk_allow = '';
										}
										if(($con_channel==2 && ($chk_allow==2 || $chk_allow==3))||$con_channel!=2)
										{
											if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
											{
												$ch_available = get_data(RATE_ADD,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'rate_types_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$date))->row_array();
											}
											else if(user_type()=='2')
											{
												$ch_available = get_data(RATE_ADD,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>$id,'rate_types_id'=>$rate_types_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$date))->row_array();
											}
											if(count($ch_available)==0)
											{
												if($this->input->post('days')!='')
												{
													$ch_sub_rooms['days']    = implode(',',$this->input->post('days'));
												}
												else
												{
													$ch_sub_rooms['days']    = ('1,2,3,4,5,6,7');
												}
												$ch_sub_rooms['separate_date']=$date;
												$ch_sub_rooms['refun_type']   = $refun;
												if(@$value['non_refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$ch_sub_rooms['non_refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$ch_sub_rooms['non_refund_amount'] = $value['non_refund_amount'];
													}
													
												}
												else
												{
													$ch_sub_rooms['non_refund_amount'] = '';
												}
												if(@$value['refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$ch_sub_rooms['refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$ch_sub_rooms['refund_amount'] = $value['refund_amount'];
													}
												}
												else
												{
													$ch_sub_rooms['refund_amount'] = '';
												}
												
												if(@$value['availability']!='')
												{
													$ch_sub_rooms['availability'] = $value['availability'];
												}
												else
												{
													$ch_sub_rooms['availability'] = '';
												}
												if(@$value['minimum_stay']!='')
												{
													$ch_sub_rooms['minimum_stay'] = $value['minimum_stay'];
												}
												else
												{
													$ch_sub_rooms['minimum_stay'] = '';
												}
												if(@$value['cta']!='')
												{
													$ch_sub_rooms['cta'] = $value['cta'];
												}
												else
												{
													$ch_sub_rooms['cta'] = '';
												}
													
												if(@$value['ctd']!='')
												{
													$ch_sub_rooms['ctd'] = $value['ctd'];
												}
												else
												{
													$ch_sub_rooms['ctd'] = '';
												}
												if(@$value['stop_sell']!='')
												{
													$ch_sub_rooms['stop_sell'] = $value['stop_sell'];
												}
												else
												{
													$ch_sub_rooms['stop_sell'] = '';
												}
												if(@$value['open_room']=='')
												{
													if(@$value['stop_sell']!='')
													{
														$ch_sub_rooms_up['stop_sell'] = $value['stop_sell'];
														$ch_sub_rooms_up['open_room'] = 0;
													}
													else
													{
														$ch_sub_rooms_up['stop_sell'] = '';
													}
												}
												if(@$value['open_room']!='')
												{
													$ch_sub_rooms_up['open_room'] = $value['open_room'];
													$ch_sub_rooms_up['stop_sell'] = 0;
												}
												else
												{
													$ch_sub_rooms_up['open_room'] = '';
												}
												if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
												{
													$ch_sub_rooms['owner_id'] = user_id();
												}
												else if(user_type()=='2')
												{
													$ch_sub_rooms['owner_id'] = owner_id();
												}
												$ch_sub_rooms['hotel_id'] = hotel_id();
												$ch_sub_rooms['individual_channel_id'] = $con_channel;
												$this->db->insert(RATE_ADD, $ch_sub_rooms);
											}
											else
											{
												if($this->input->post('days')!='')
												{
													$ch_sub_rooms_up['days']     = implode(',',$this->input->post('days'));
												}
												else
												{
													$ch_sub_rooms_up['days']     = ('1,2,3,4,5,6,7');
												}
												if(@$value['non_refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$ch_sub_rooms_up['non_refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$ch_sub_rooms_up['non_refund_amount'] = $value['non_refund_amount'];
													}
												}
												else
												{
													$ch_sub_rooms_up['non_refund_amount'] = $ch_available['non_refund_amount'];
												}
												if(@$value['refund_amount']!='')
												{
													if(isset($rateMul[$id.$con_channel])){
														$ch_sub_rooms_up['refund_amount'] = $rateMul[$id.$con_channel];
													}else{
														$ch_sub_rooms_up['refund_amount'] = $value['refund_amount'];
													}
												}
												else
												{
													$ch_sub_rooms_up['refund_amount'] = $ch_available['refund_amount'];
												}
												
												if(@$value['availability']!='')
												{
													$ch_sub_rooms_up['availability'] = $value['availability'];
												}
												else
												{
													$ch_sub_rooms_up['availability'] = $ch_available['availability'];
												}
												if(@$value['minimum_stay']!='')
												{
													$ch_sub_rooms_up['minimum_stay'] = $value['minimum_stay'];
												}
												else
												{
													$ch_sub_rooms_up['minimum_stay'] = $ch_available['minimum_stay'];
												}
												if(@$value['cta']!='')
												{
													$ch_sub_rooms_up['cta'] = $value['cta'];
												}
												else
												{
													$ch_sub_rooms_up['cta'] = $ch_available['cta'];
												}
													
												if(@$value['ctd']!='')
												{
													$ch_sub_rooms_up['ctd'] = $value['ctd'];
												}
												else
												{
													$ch_sub_rooms_up['ctd'] = $ch_available['ctd'];
												}
												if(@$value['open_room']=='')
												{
													if(@$value['stop_sell']!='')
													{
														$ch_sub_rooms_up['stop_sell'] = $value['stop_sell'];
														$ch_sub_rooms_up['open_room'] = 0;
													}
													else
													{
														$ch_sub_rooms_up['stop_sell'] = $ch_available['stop_sell'];
													}
												}
												if(@$value['open_room']!='')
												{
													$ch_sub_rooms_up['open_room'] = $value['open_room'];
													$ch_sub_rooms_up['stop_sell'] = 0;
												}
												else
												{
													$ch_sub_rooms_up['open_room'] = $ch_available['open_room'];
												}
												if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
												{
													$this->db->where('owner_id', user_id());
												}
												else if(user_type()=='2')
												{
													$this->db->where('owner_id', owner_id());
												}
												$this->db->where('hotel_id', hotel_id());
												$this->db->where('room_id', $id);
												$this->db->where('rate_types_id', $rate_types_id);
												$this->db->where('separate_date', $date);
												$this->db->where('refun_type', $refun);
												$this->db->where('guest_count', $guest_count);
												$this->db->where('individual_channel_id',$con_channel);
												$this->db->update(RATE_ADD, $ch_sub_rooms_up);
											}
										}
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    
	function write_log($message,$user_id="")
	{
        if($user_id == "")
		{
            $user_id = current_user_type();
        }
        require_once(APPPATH.'controllers/logging.php');
        $log = new Logging();
        $path = APPPATH."views/logs/log_".$user_id.".txt";
        if(!file_exists($path))
        {
            //echo "hi";
            $myfile = fopen($path, "wb");
        }

        $log->lfile($path);           
        // write message to the log file
        $log->lwrite($message);            
        // close log file
        $log->lclose();
    }
    function update_stopsell_main()
    {
		$all_channel = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'),'channel_id')->result_array();		
		
		$all_channel = array_column($all_channel, 'channel_id');
        $channel_bnow = '';
        $channel_travel = '';
		
		if (($key = array_search('17', $all_channel)) !== false) 
		{
			//unset($all_channel[$key]);
			$channel_bnow = 'bnow';
		}
		if (($key = array_search('15', $all_channel)) !== false) 
        {
            //unset($all_channel[$key]);
            $channel_travel = 'travel';
        }
        if (($key = array_search('14', $all_channel)) !== false) 
        {
            //unset($all_channel[$key]);
            $channel_wbeds = 'wbeds';
        }
		//print_r($all_channel); die;
        if($this->input->post('alter_checkbox')!='')
        {
            $exp = explode(',',$this->input->post('alter_checkbox'));
            
            foreach($exp as $val)
            {
                $array_alter1 = str_replace(array('room','][','[',']'), array('','~~','',''), $val);
                $array_alter = explode('~~',$array_alter1);
                $un_roomid = $array_alter[0];
                $un_column = $array_alter[1];
                $un_date   = $array_alter[2];
                
                $s_available = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'individual_channel_id'=>0,'separate_date'=>$un_date),$un_column)->row_array();
                
                if(count($s_available)!=0)
                {
                    if($s_available[$un_column]==1)
                    {
                        $undata[$un_column]     =	'0';
						$org_price				=	'remove';
                    }
                    else if($s_available[$un_column]==0)
                    {
                        $undata[$un_column]     = 	'1';
						$org_price				=	'1';
                    }
					$update	=	'stop_sell';
					$column	=	'stop_sell';
                    if(update_data(TBL_UPDATE,$undata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'individual_channel_id'=>0,'separate_date'=>$un_date)))
                    {
                        //echo $this->db->last_query();
                        
                    }
                }
                else
                {
                    $m_in_data['owner_id'] = current_user_type();
                    $m_in_data['hotel_id'] = hotel_id();
                    $m_in_data['room_id'] = $un_roomid;
                    $m_in_data['individual_channel_id']='0';
                    $m_in_data['separate_date']=$un_date;
                    $m_in_data[$un_column]='1';
                    if(insert_data(TBL_UPDATE,$m_in_data))
                    {
                            
                    }
                }
                if(count($all_channel) != "")
                {
                    foreach($all_channel as $con_ch)
                    {
                        //extract($con_ch);
                        $con_ch = $con_ch;
						if($con_ch==2)
						{
							$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row()->xml_type;
						}
						else
						{	
							$chk_allow = '';
						}
						if(($con_ch==2 && ($chk_allow==2 || $chk_allow==3))||$con_ch!=2)
						{
                        $ch_available = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$un_roomid,'separate_date'=>$un_date))->row_array();
                        
                        if(count($ch_available)==0)
                        {
                            $ch_sub_rooms['room_id'] = $un_roomid;
                            $ch_sub_rooms['separate_date']=$un_date;
                            $ch_sub_rooms[$un_column] = 1;
                            $ch_sub_rooms['owner_id'] = current_user_type();
                            $ch_sub_rooms['hotel_id'] = hotel_id();
                            $ch_sub_rooms['individual_channel_id'] = $con_ch;
                            $this->db->insert(TBL_UPDATE, $ch_sub_rooms);
                        }
                        else
                        {
							$c_available = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'individual_channel_id'=>0,'separate_date'=>$un_date),$un_column)->row_array();
							
							//print_r($c_available);
							
                            if($c_available[$un_column]==1)
                            {
                                $ch_sub_rooms_up[$un_column]     = '1';
                            }
                            else if($c_available[$un_column]==0)
                            {
                                $ch_sub_rooms_up[$un_column]     = '0';
                            }
							//print_r($ch_sub_rooms_up);
                            $this->db->where('owner_id', current_user_type());
                            $this->db->where('hotel_id', hotel_id());
                            $this->db->where('room_id', $un_roomid);
                            $this->db->where('separate_date', $un_date);
                            $this->db->where('individual_channel_id',$con_ch);
                            $this->db->update(TBL_UPDATE, $ch_sub_rooms_up);
                        }
						}
                    }
                }
				//echo $this->db->last_query(); die;
                $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$un_roomid,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_availability'=>1,'channel_id !='=>17))->count_all_results();
       
				if($count!=0)
                {
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$un_roomid,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled','update_availability'=>1,'channel_id !='=>17))->result();
                    
                    if($room_mapping)
                    {
                        foreach($room_mapping as $room_value)
                        {
                            if($room_value->channel_id=='11')
                            {
                                $availability_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$room_value->channel_id,'room_id'=>$room_value->property_id,'separate_date'=>$un_date))->row();
                
                                if($availability_details->stop_sell=='0')
                                {
                                    if($availability_details->availability!='')
                                    {
                                        $ch_update_avail = '='.$availability_details->availability;
                                    }
                                    else 
                                    {
                                        $ch_update_avail='=0';
                                    }
                                }
                                else if($availability_details->stop_sell=='1')
                                {
                                    $ch_update_avail='=-100';
                                }
                                
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                                if($ch_details->mode == 0){
                                    $urls = explode(',', $ch_details->test_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $reco[$path[0]] = $path[1];
                                    }
                                }else if($ch_details->mode == 1){
                                    $urls = explode(',', $ch_details->live_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $reco[$path[0]] = $path[1];
                                    }
                                }      
                                $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                                
                                $url = trim($reco['urate_avail']);
                                $xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
                                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                <soap12:Body>
                                <UpdateAvail xmlns="https://www.reconline.com/"> 
                                <User>'.$ch_details->user_name.'</User>
                                <Password>'.$ch_details->user_password.'</Password>
                                <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                <idSystem>0</idSystem>
                                <ForeignPropCode></ForeignPropCode>
                                <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                <ExcludeRateLevels></ExcludeRateLevels>
                                <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                <ExcludeRoomTypes></ExcludeRoomTypes>
                                <AvailMode>0</AvailMode>
                                <StartDate>'.$un_date.'</StartDate>
                                <Availability>'.$ch_update_avail.'</Availability>
                                </UpdateAvail>
                                </soap12:Body>
                                </soap12:Envelope>';
                                $headers_avail = array(
                                                    "Content-type: application/soap+xml; charset=utf-8",
                                                    "Host:www.reconline.com",
                                                    "Content-length: ".strlen($xml_post_string_update),
                                                    ); 
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
                                $ss = curl_getinfo($ch);                
                                $response = curl_exec($ch);
                                $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                $xml = simplexml_load_string($xml);
                                $json = json_encode($xml);
                                $responseArray = json_decode($json,true);
                                $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                if(count($Errorarray)=='0' && count($soapFault)=='0')
                                {
                                    $reconline_availability_response="success";
                                }
                                else 
                                {
                                    $reconline_availability_response="error";
                                    if(count($Errorarray)!='0')
                                    {
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Stop Sell Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                    }
                                    else if(count($soapFault)!='0')
                                    {      
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Stop Sell Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                    }
                                }
                                curl_close($ch);
                            }
							elseif($room_value->channel_id == "1")
							{
                                $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$un_date)));
                                $availability_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$room_value->channel_id,'room_id'=>$room_value->property_id,'separate_date'=>$un_date))->row();
                                
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();

                                if($ch_details->mode == 0){
                                    $urls = explode(',', $ch_details->test_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $exp[$path[0]] = $path[1];
                                    }
                                }else if($ch_details->mode == 1){
                                    $urls = explode(',', $ch_details->live_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $exp[$path[0]] = $path[1];
                                    }
                                }      
                                
                                $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                                $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                        <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                        <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                        <AvailRateUpdate>';
                                $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                if($room_value->explevel == "room"){
                                    if($availability_details->stop_sell=='0')
                                    {
                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                                    }elseif ($availability_details->stop_sell == '1') {
                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                                    }
                                }
                                else if($room_value->explevel == "rate"){
                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                        $plan_id = $mp_details->rateplan_id;
                                    }else{
                                        $plan_id = $mp_details->rate_type_id;
                                    }
                                    if($availability_details->stop_sell=='0')
                                    {
                                        $xml .= '<RatePlan id="'.$plan_id.'" closed="false"></RatePlan>';
                                    }elseif ($availability_details->stop_sell == '1') {
                                        $xml .= '<RatePlan id="'.$plan_id.'" closed="true"></RatePlan>';
                                    }                                    
                                }
                                
                                $xml .= "</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                                $URL = trim($exp['urate_avail']);
                                $ch = curl_init($URL);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                $output = curl_exec($ch);
                                $data = simplexml_load_string($output); 
                                $response = $data->Error;
                                if($response!='')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Stop Sell Update',date('m/d/Y h:i:s a', time()));
                                    $expedia_update = "Failed";
                                }
                                else
                                {
                                    $expedia_update = "Success";
                                }
                                curl_close($ch);
                            }
							elseif($room_value->channel_id=='8')
                            {

                                 $date_arr= explode('/',$un_date) ;
                                 $stdate=$date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0]; 
                                 $availability_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$room_value->channel_id,'room_id'=>$room_value->property_id,'separate_date'=>$un_date))->row();
                                 
                                
                                 $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                                 if($ch_details->mode == 0){
                                        $urls = explode(',', $ch_details->test_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    }else if($ch_details->mode == 1){
                                        $urls = explode(',', $ch_details->live_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    }      
                                 $mp_details = get_data("import_mapping_GTA",array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                               
                                     if($availability_details->stop_sell=='0')
                                     {
                                        $status="false";
                                     }elseif($availability_details->stop_sell=='1'){
                                            $status="true";
                                     }   


                                     $contract_id=$mp_details->contract_id;
                                     $hotel_channel_id=$mp_details->hotel_channel_id;
                                     $gt_room_id=$mp_details->ID; 
                                      $soapUrl=trim($gta['uavail']);           

                                    $xml_post_string='<GTA_InventoryUpdateRQ  xmlns = "http://www.gta-travel.com/GTA/2012/05"  xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQhelp.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                                                
                                    $xml_post_string.=' <StayDate Date = "'.$stdate.'"><Inventory RoomId = "'.$gt_room_id.'"><Detail FreeSale="false" InventoryType="Flexible"
                                        Quantity="'.$availability_details->availability.'" ReleaseDays="0"/><Restriction FlexibleStopSell = "'.$status.'"  InventoryType = "Flexible"/></Inventory></StayDate> ';
            
                                    $xml_post_string.=' </RoomStyle>
                                            </InventoryBlock>
                                        </GTA_InventoryUpdateRQ>';
                                    $ch = curl_init($soapUrl);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                     $response = curl_exec($ch); 
                                    $data = simplexml_load_string($response); 
                                                    

                                    if(isset($data->Success))
                                    {
                                        /*$gtaresponse="success"; 
                                         $this->session->set_flashdata('bulk_error',(string)$gtaresponse);
                                        echo "OK";  */
                                    }else
                                    {

                                          /*   $this->session->set_flashdata('bulk_error',"Bulk update success");
                                        echo "Failed"; */
                                    }
                                    curl_close($ch);
                            } 
                            elseif($room_value->channel_id == '2')
							{
								$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row()->xml_type;
								if($chk_allow==2 || $chk_allow==3)
								{
									$this->load->model("booking_model");
									$this->booking_model->update_stopsell($un_date,$room_value->property_id,$room_value->import_mapping_id);
								}
                            }
							else if($room_value->channel_id=='5')
                            {
                                $hotelbed_start = date('Y-m-d',strtotime(str_replace('/','-',$un_date)));
								$hotelbed_start = str_replace('-', '', $hotelbed_start);
                                $availability_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$room_value->channel_id,'room_id'=>$room_value->property_id,'separate_date'=>$un_date))->row();
                                                
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();

                                if($ch_details->mode == 0){
                                    $urls = explode(',', $ch_details->test_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $htb[$path[0]] = $path[1];
                                    }
                                }else if($ch_details->mode == 1){
                                    $urls = explode(',', $ch_details->live_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $htb[$path[0]] = $path[1];
                                    }
                                }  
                                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                

                                $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                    <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                    <getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                    <HSI_ContractInventoryModificationRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                                    <Language>ENG</Language>
                                    <Credentials>
                                        <User>'.$ch_details->user_name.'</User>
                                        <Password>'.$ch_details->user_password.'</Password>
                                    </Credentials>
                                    <Contract>
                                        <Name>'.$mp_details->contract_name.'</Name>
                                        <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                        <Sequence>'.$mp_details->sequence.'</Sequence>
                                    </Contract>
                                    <InventoryItem>
                                        <DateFrom date="'.$hotelbed_start.'"/>
                                        <DateTo date="'.$hotelbed_start.'"/>';
                                
                                if($availability_details->stop_sell=='0'){
                                    $xml_post_string .= '<Room closed="N">';
                                }else if($availability_details->stop_sell=='1'){
                                    $xml_post_string .= '<Room closed="Y">';
                                } 
                                
                                $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                
                                $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                                 </soapenv:Envelope>';
                                 // echo $xml_post_string;
                               /*  die; */

                                $headers = array(
                                "SOAPAction:no-action",
                                "Content-length: ".strlen($xml_post_string),
                                ); 
                                $url = trim($htb['urate_avail']);

                                // PHP cURL  for https connection with auth
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                $ss = curl_getinfo($ch);                
                                $response = curl_exec($ch);
                                
                                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                $xml_parse = simplexml_load_string($xmlreplace);
                                $json = json_encode($xml_parse);
                                $responseArray = json_decode($json,true);
                                //print_r($responseArray);

                                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                                //print_r($xml);                                
                                if($xml->ErrorList->Error){
                                    $status = @$xml->ErrorList->Error->DetailedMessage;
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->ErrorList->Error->DetailedMessage,'Update Stop Sale',date('m/d/Y h:i:s a', time()));
                                    

                                }else if($xml->Status != "Y"){
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,'Try Again','Update Stop Sale',date('m/d/Y h:i:s a', time()));
                                    
                                }
                            }
                        }
                    }
                }
				if($channel_bnow!='')
				{
					$table = TBL_UPDATE;
					$this->bnow_model->inline_edit_main_calendar($table,$un_roomid,$un_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
				}
                if($channel_travel != "")
                {
                    $table = TBL_UPDATE;
                    $this->travel_model->inline_edit_main_calendar($table,$un_roomid,$un_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                }
                if($channel_wbeds != "")
                {
                    $table = TBL_UPDATE;
                    $this->wbeds_model->inline_edit_main_calendar($table,$un_roomid,$un_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                }
            }
        }
		return true;
    }
    
    function main_full_update()
    {
        extract($this->input->post());

        if(!isset($channel_id))
        {
            print '<script language="JavaScript">'; 
            print 'alert("Not Channels Selected");'; 
            print '</script>'; 
            return;
        }


        $start = $datepicker_full_start;
        $end   = $datepicker_full_end;
		$datepicker_full_start = date('Y-m-d',strtotime(str_replace('/','-',$datepicker_full_start)));
        $datepicker_full_end = date('Y-m-d',strtotime(str_replace('/','-',$datepicker_full_end)));
		$period = $this->getDateForSpecificDayBetweenDates($datepicker_full_start,$datepicker_full_end,"1,2,3,4,5,6,7");
	   
        $datetime1 = new DateTime($datepicker_full_start);
        $datetime2 = new DateTime($datepicker_full_end);
        $interval = $datetime1->diff($datetime2);
        $dias = $interval->format('%R%a');
        $DespegarErrors='';
        $BookingErrors='';
        $ExpediaErrors='';
        $inicialdate = $datepicker_full_start;

            while ($dias > 0) {
                
                $FinalDate = strtotime (($dias>=90?'+90 day':'+'.$dias.' day') , strtotime ( $inicialdate) ) ;
                $FinalDate  = date ( 'Y-m-d' , $FinalDate );
                
                    if($optionsRadios=='select') 
                    {
                               

                        foreach ($channel_id as $Channelid) {
                            if($Channelid==36){
                                $this->load->model("despegar_model");
                                $DespegarErrors .=$this->despegar_model->SincroCalender($inicialdate,$FinalDate);

                                
                            }
                            elseif($Channelid==1){
                                $this->load->model("expedia_model");
                                $ExpediaErrors .=$this->expedia_model->SincroCalender($inicialdate,$FinalDate);

                            }
                            elseif ($Channelid==2) {

                                $this->load->model("booking_model");
                                $BookingErrors .=$this->booking_model->SincroCalender($inicialdate,$FinalDate);
                            }
                        }

                    }
                  
                
                $inicialdate = strtotime (($dias>=90?'+90 day':'+'.$dias.' day') , strtotime ( $FinalDate) ) ;
                $inicialdate  = date ( 'Y-m-d' , $inicialdate  );
                $dias =$dias-90;
            }

            foreach ($channel_id as $Channelid) {
                            if($Channelid==36){
                                print '<script language="JavaScript">'; 
                                print 'alert("'.$DespegarErrors.'");'; 
                                print '</script>'; 
                            }
                            elseif($Channelid==1){
                                print '<script language="JavaScript">'; 
                                print 'alert("'.$ExpediaErrors.'");'; 
                                print '</script>'; 

                            }
                            elseif ($Channelid==2) {

                                print '<script language="JavaScript">'; 
                                print 'alert("'.$BookingErrors.'");'; 
                                print '</script>'; 
                            }
                        }    

	}
    
    function reservation_update_no()
    {
		$con_ch = $this->input->post('channe_id_update');
		if($con_ch==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row()->xml_type;
		}
		else
		{	
			$chk_allow = '';
		}
		if(($con_ch==2 && ($chk_allow==2 || $chk_allow==3))||$con_ch!=2)
		{
			if($this->input->post('alter_checkbox')!='')
			{
				$exp = explode(',',$this->input->post('alter_checkbox'));
				foreach($exp as $val)
				{
					$array_alter1 = str_replace(array('room','][','[',']'), array('','~~','',''), $val);
					$array_alter = explode('~~',$array_alter1);
					$un_roomid = $array_alter[0];
					$un_column = $array_alter[1];
					$un_date   = $array_alter[2];
					if($un_column=='stop_sell')
					{
						$undata[$un_column]     = '0';
						$undata['open_room']    = '1';
					}
					else
					{
						$undata[$un_column]     = '0';
					}
					if(update_data(TBL_UPDATE,$undata,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'separate_date'=>$un_date)))
					{
						$updated_data = get_data(TBL_UPDATE,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'separate_date'=>$un_date))->result_array();
					}
					$channel['guest'] = 0;
					$channel['refund'] = 0;
					$channel['rate'] = 0;
					//print_r($updated_data);
					//echo "<br/>";
					if($con_ch!=17 && $con_ch!=15 && $con_ch != 14)
					{
						$this->channel_calendar_update($updated_data,$channel);
					}
					elseif($con_ch==17)
					{
						$table	=	TBL_UPDATE;
						$column	=	'stop_sell';
						$update	=	'stop_sell';
						$this->bnow_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
					}
                    elseif($con_ch==15)
                    {
                        $table  =   TBL_UPDATE;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->travel_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                    }
                    elseif($con_ch==14)
                    {
                        $table  =   TBL_UPDATE;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->wbeds_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                    }
				}
			}
			
			if($this->input->post('alter_checkbox_refund')!='')
			{
				$exp = explode(',',$this->input->post('alter_checkbox_refund'));
				foreach($exp as $val)
				{               
					$array_alter1 = str_replace(array('channel_room','][','[',']'), array('','~~','',''), $val);
					$array_alter = explode('~~',$array_alter1);
					$un_roomid  = $array_alter[0];
					$un_guest   = $array_alter[1];
					$un_refund  = $array_alter[2];
					$un_date    = $array_alter[3];
					$un_column  = $array_alter[4];
					if($un_column=='stop_sell')
					{
						$undata[$un_column]     = '0';
						$undata['open_room']    = '1';
					}
					else
					{
						$undata[$un_column]     = '0';
					}
					if(update_data(RESERV,$undata,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'separate_date'=>$un_date,'guest_count'=>$un_guest,'refun_type'=>$un_refund)))
					{
						$updated_data = get_data(RESERV, array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'separate_date'=>$un_date,'guest_count'=>$un_guest,'refun_type'=>$un_refund))->result_array();
					}
					$channel['guest'] = $un_guest;
					$channel['refund'] = $un_refund;
					$channel['rate'] = 0;
					//print_r($updated_data);
					//echo "<br/>";
					if($con_ch!=17 && $con_ch!=15 && $con_ch != 14)
					{
						$this->channel_calendar_update($updated_data,$channel);
					}
					elseif($con_ch==17)
					{
						$table	=	RESERV;
						$column	=	'stop_sell';
						$update	=	'stop_sell';
						$this->bnow_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$un_guest,$un_refund,$column,$update);
					}
                    elseif($con_ch==15)
                    {
                        $table  =   RESERV;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->travel_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                    }
                    elseif($con_ch==14)
                    {
                        $table  =   RESERV;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->wbeds_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                    }
				}
			}
			
			if($this->input->post('alter_checkbox_rate')!='')
			{
				$exp = explode(',',$this->input->post('alter_checkbox_rate'));
				foreach($exp as $val)
				{
					$array_alter1 = str_replace(array('room_rate','][','[',']'), array('','~~','',''), $val);
					$array_alter = explode('~~',$array_alter1);
					$un_roomid = $array_alter[0];
					$rate_type_id   = $array_alter[1];
					$un_column = $array_alter[2];
					$un_date   = $array_alter[3];
					if($un_column=='stop_sell')
					{
						$undata[$un_column]     = '0';
						$undata['open_room']    = '1';
					}
					else
					{
						$undata[$un_column]     = '0';
					}
					if(update_data(RATE_BASE,$undata,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'rate_types_id'=>$rate_type_id,'separate_date'=>$un_date)))
					{
						$updated_data = get_data(RATE_BASE,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'rate_types_id'=>$rate_type_id,'separate_date'=>$un_date))->result_array();
					}
					$channel['guest'] = 0;
					$channel['refund'] = 0;
					$channel['rate'] = $rate_type_id;
					//print_r($updated_data);
					//echo "<br/>";
					if($con_ch!=17 && $con_ch!=15 && $con_ch != 14)
					{
						$this->channel_calendar_update($updated_data,$channel);
					}
					elseif($con_ch==17)
					{
						$table	=	RATE_BASE;
						$column	=	'stop_sell';
						$update	=	'stop_sell';
						$this->bnow_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
					}
                    elseif($con_ch==15)
                    {
                        $table  =   RATE_BASE;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->travel_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                    }
                    elseif($con_ch==14)
                    {
                        $table  =   RATE_BASE;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->wbeds_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                    }
				}
			}
        
			if($this->input->post('alter_checkbox_rate_refund')!='')
			{
				$exp = explode(',',$this->input->post('alter_checkbox_rate_refund'));
				foreach($exp as $val)
				{               
					$array_alter1 = str_replace(array('channel_room_rate','][','[',']'), array('','~~','',''), $val);
					$array_alter    = explode('~~',$array_alter1);
					$un_roomid      = $array_alter[0];
					$un_rate_type_id   = $array_alter[1];
					$un_guest       = $array_alter[2];
					$un_refund      = $array_alter[3];
					$un_date        = $array_alter[4];
					$un_column      = $array_alter[5];
					if($un_column=='stop_sell')
					{
						$undata[$un_column]     = '0';
						$undata['open_room']    = '1';
					}
					else
					{
						$undata[$un_column]     = '0';
					}
					if(update_data(RATE_ADD,$undata,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'rate_types_id'=>$un_rate_type_id,'separate_date'=>$un_date,'guest_count'=>$un_guest,'refun_type'=>$un_refund)))
					{
						$update_data = get_data(RATE_ADD,array('individual_channel_id'=>$this->input->post('channe_id_update'),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$un_roomid,'rate_types_id'=>$un_rate_type_id,'separate_date'=>$un_date,'guest_count'=>$un_guest,'refun_type'=>$un_refund))->result_array();
					}
					$channel['guest'] = $un_guest;
					$channel['refund'] = $un_refund;
					$channel['rate'] = $un_rate_type_id;
					//print_r($updated_data);
					//echo "<br/>";
					if($con_ch!=17 && $con_ch != 15 && $con_ch != 14)
					{
						$this->channel_calendar_update($updated_data,$channel);
					}
					elseif($con_ch==17)
					{
						$table	=	RATE_ADD;
						$column	=	'stop_sell';
						$update	=	'stop_sell';
						$this->bnow_model->reservation_update_no($table,$un_roomid,$un_date,$un_rate_type_id,$un_guest,$un_refund,$column,$update);
					}
                    elseif($con_ch==15)
                    {
                        $table  =   RATE_ADD;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->travel_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                    }
                    elseif($con_ch==14)
                    {
                        $table  =   RATE_ADD;
                        $column =   'stop_sell';
                        $update =   'stop_sell';
                        $this->wbeds_model->reservation_update_no($table,$un_roomid,$un_date,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                    }
				}
			}

			@extract($this->input->post());
			//print_r($room);
			/*      
			new_room

			new_channel_room
			
			new_room_rate
			
			new_channel_room_rate
			*/
			if(isset($new_room))
			{
				foreach($new_room as $room_id=>$rooms)
				{
					$available_dates = get_data(TBL_UPDATE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id))->result_array();
					if(count($available_dates)!=0)
					{
						$ext_dats[]='';
						foreach($available_dates as $_dates)
						{
							extract($_dates);
							if(!in_array($separate_date ,$ext_dats))
							{
								$ext_dats[]=$separate_date;
								
							}
						}
					}
					else
					{
						$ext_dats[]='';
					}
					//print_r($available_dates); 
					foreach($rooms as $update_key=>$date_val)
					{
						$up_col = $update_key; 
						$new_date[]='';
						foreach($date_val as $date_key=>$date_value)
						{
							$udata = '';  
							$new_date[]=$date_key;
							if(in_array($date_key,$ext_dats))
							{
								$available = get_data(TBL_UPDATE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$date_key))->row_array();
								if(count($available)!='0')
								{
									if($up_col=='stop_sell')
									{
										$udata[$up_col]     = $date_value;
										$udata['open_room'] = '0';
									}
									else
									{
										$udata[$up_col]     = $date_value;
									}
									$udata['individual_channel_id']   = $channe_id_update;
									$udata['days']          = '1,2,3,4,5,6,7';
									if(update_data(TBL_UPDATE,$udata,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$date_key)))
									{
										
									}
									$updated_data = get_data(TBL_UPDATE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$date_key))->result_array();
									
								}
							}
							else
							{
								$idata='';
								$idata['room_id']      = $room_id;
								if($update_key=='stop_sell')
								{
									$idata[$update_key]     = $date_value;
									$idata['open_room'] = '0';
								}
								else
								{
									$idata[$update_key]     = $date_value;
								}
								$idata['separate_date']= $date_key;
								$idata['owner_id']= current_user_type();
								$idata['hotel_id']= hotel_id();
								$idata['individual_channel_id']   = $channe_id_update;
								$idata['days']         = '1,2,3,4,5,6,7';
								if(insert_data(TBL_UPDATE,$idata))
								{
									$updated_data = get_data(TBL_UPDATE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$date_key))->result_array();
								} 
							}
							$channel['guest'] = 0;
							$channel['refund'] = 0;
							$channel['rate'] = 0;
							//print_r($updated_data);
							if($con_ch!=17 && $con_ch != 15 && $con_ch != 14)
							{
								$this->channel_calendar_update($updated_data,$channel);
							}
							else if($con_ch==17)
							{
								$table	=	TBL_UPDATE;
								$column	=	'stop_sell';
								$update	=	'stop_sell';
								$this->bnow_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
							}
                            else if($con_ch==15)
                            {
                                $table  =   TBL_UPDATE;
                                $column =   'stop_sell';
                                $update =   'stop_sell';
                                $this->travel_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                            }
                            else if($con_ch==14)
                            {
                                $table  =   TBL_UPDATE;
                                $column =   'stop_sell';
                                $update =   'stop_sell';
                                $this->wbeds_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
                            }
						}
						$arr1 = array_filter($new_date);
						$arr2 = array_filter($ext_dats);
						$final = array_diff($arr2,$arr1);
					}
				}
			}
        
			if(isset($new_channel_room))
			{
				$con_channel = $channe_id_update;
				foreach($new_channel_room as $id=>$room)
				{
					$sub_rooms['room_id']   = $id;
					foreach($room as $guest_count=>$type)
					{
						$sub_rooms['guest_count'] = $guest_count;
						foreach($type as $refun=>$value)
						{   
							$sub_rooms['refun_type'] = $refun;  
							foreach($value as $up_date=>$field)
							{
								$sub_rooms['separate_date'] = $up_date; 
								foreach($field as $column=>$field_val)
								{
		
									$available = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$up_date))->row_array();
						  
									if(count($available)==0)
									{
										if($column=='stop_sell')
										{
											$sub_rooms[$column]     = $field_val;
											$sub_rooms['open_room'] = '0';
										}
										else
										{
											$sub_rooms[$column]     = $field_val;
										}
										$sub_rooms['owner_id'] = current_user_type();
										$sub_rooms['hotel_id'] = hotel_id();
										$sub_rooms['individual_channel_id'] = $con_channel;
										$this->db->insert(RESERV, $sub_rooms);
									}
									else
									{
										if($column=='stop_sell')
										{
											$sub_rooms_up[$column]     = $field_val;
											$sub_rooms_up['open_room'] = '0';
										}
										else
										{
											$sub_rooms_up[$column]     = $field_val;
										}
										$this->db->where('owner_id', current_user_type());
										$this->db->where('hotel_id', hotel_id());
										$this->db->where('room_id', $id);
										$this->db->where('separate_date', $up_date);
										$this->db->where('refun_type', $refun);
										$this->db->where('guest_count', $guest_count);
										$this->db->where('individual_channel_id',$con_channel);
										$this->db->update(RESERV, $sub_rooms_up);


									}
									$updated_data = get_data(RESERV,array('individual_channel_id'=>$con_channel,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$id,'separate_date'=>$up_date))->result_array();

									$channel['guest'] = $guest_count;
									$channel['refund'] = $refun;
									$channel['rate'] = 0;
									if($con_channel!=17 && $con_channel!=15 && $con_channel != 14)
									{
										$this->channel_calendar_update($updated_data,$channel); 
									}
									else if($con_channel==17)
									{
										$table	=	RESERV;
										$columns	=	'stop_sell';
										$update	=	'stop_sell';
										$this->bnow_model->reservation_update_no($table,$id,$up_date,$rate_type_id=0,$guest_count,$refun,$columns,$update);
									}
                                    else if($con_channel==15)
                                    {
                                        $table  =   RESERV;
                                        $columns    =   'stop_sell';
                                        $update =   'stop_sell';
                                        $this->travel_model->reservation_update_no($table,$id,$up_date,$rate_type_id=0,$guest_count,$refun,$columns,$update);
                                    }
                                    else if($con_channel==14)
                                    {
                                        $table  =   RESERV;
                                        $columns    =   'stop_sell';
                                        $update =   'stop_sell';
                                        $this->wbeds_model->reservation_update_no($table,$id,$up_date,$rate_type_id=0,$guest_count,$refun,$columns,$update);
                                    }
								}
							}
						}
					}
				}
			}
        
			if(isset($new_room_rate))
			{ 
                $con_channel = $channe_id_update;
				foreach($new_room_rate as $room_id=>$room)
				{
					foreach($room as $rate_type_id=>$rooms)
					{
						$available_dates = get_data(RATE_BASE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id))->result_array();
						if(count($available_dates)!=0)
						{
							$ext_dats[]='';
							foreach($available_dates as $_dates)
							{
								extract($_dates);
								if(!in_array($separate_date,$ext_dats))
								{
									$ext_dats[]=$separate_date;
									
								}
							}
						}
						else
						{
							$ext_dats[]='';
						}
						foreach($rooms as $update_key=>$date_val)
						{
							$up_col = $update_key;
							$new_date[]='';
							foreach($date_val as $date_key=>$date_value)
							{
								$udata = '';
								$new_date[]=$date_key;
								if(in_array($date_key,$ext_dats))
								{
									$available = get_data(RATE_BASE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$date_key))->row_array();
									
									if(count($available)!='0')
									{
										if($up_col=='stop_sell')
										{
											$udata[$up_col]     = $date_value;
											$udata['open_room'] = '0';
										}
										else
										{
											$udata[$up_col]     = $date_value;
										}
										$udata['individual_channel_id']   = $channe_id_update;
										$udata['days']          = '1,2,3,4,5,6,7';
										if(update_data(RATE_BASE,$udata,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$date_key)))
										{
										}
									}
								}
								else
								{
									$idata='';
									$idata['room_id']      = $room_id;
									$idata['rate_types_id']    = $rate_type_id;
									if($update_key=='stop_sell')
									{
										$idata[$update_key]     = $date_value;
										$idata['open_room'] = '0';
									}
									else
									{
										$idata[$update_key]     = $date_value;
									}
									$idata['separate_date']= $date_key;
									$idata['owner_id']= current_user_type();
									$idata['hotel_id']= hotel_id();
									$idata['individual_channel_id']   = $channe_id_update;
									$idata['days']         = '1,2,3,4,5,6,7';
									if(insert_data(RATE_BASE,$idata))
									{
									} 
								}
								$updated_data = get_data(RATE_BASE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$date_key))->result_array();

								$channel['guest'] = 0;
								$channel['refund'] = 0;
								$channel['rate'] = $rate_type_id;
                                
								if($con_channel!=17 && $con_channel != 15 && $con_channel != 14)
								{
									$this->channel_calendar_update($updated_data,$channel);
								}
								else if($con_channel==17)
								{
									$table	=	RATE_BASE;
									$column	=	'stop_sell';
									$update	=	'stop_sell';
									$this->bnow_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
								}
                                else if($con_channel==15)
                                {
                                    $table  =   RATE_BASE;
                                    $column =   'stop_sell';
                                    $update =   'stop_sell';
                                    $this->travel_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                                }
                                else if($con_channel==14)
                                {
                                    $table  =   RATE_BASE;
                                    $column =   'stop_sell';
                                    $update =   'stop_sell';
                                    $this->wbeds_model->reservation_update_no($table,$room_id,$date_key,$rate_type_id,$un_guest=0,$un_refund=0,$column,$update);
                                }
							}
							$arr1 = array_filter($new_date);
							$arr2 = array_filter($ext_dats);
							$final = array_diff($arr2,$arr1);
						
						}
					}
				}
			}
        
			if(isset($new_channel_room_rate))
			{
				$con_channel = $channe_id_update;
				foreach($new_channel_room_rate as $id=>$rooms)
				{
					$sub_rooms['room_id']   = $id;
					
					foreach($rooms as $rate_type_id=>$room)
					{
						$sub_rooms['rate_types_id'] = $rate_type_id;
						
						foreach($room as $guest_count=>$type)
						{
							$sub_rooms['guest_count'] = $guest_count;
							foreach($type as $refun=>$value)
							{   
								$sub_rooms['refun_type'] = $refun;  
								foreach($value as $up_date=>$field)
								{
									$sub_rooms['separate_date'] = $up_date; 
									foreach($field as $column=>$field_val)
									{
										$available = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$id,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refun,'individual_channel_id'=>$con_channel,'separate_date'=>$up_date))->row_array();
										if(count($available)==0)
										{
											if($column=='stop_sell')
											{
												$sub_rooms_up[$column]     = $field_val;
												$sub_rooms_up['open_room'] = '0';
											}
											else
											{
												$sub_rooms_up[$column]     = $field_val;
											}
											$sub_rooms['owner_id'] = current_user_type();
											$sub_rooms['hotel_id'] = hotel_id();
											$sub_rooms['individual_channel_id'] = $con_channel;
											$this->db->insert(RATE_ADD, $sub_rooms);
										}
										else
										{
											if($column=='stop_sell')
											{
												$sub_rooms_up[$column]     = $field_val;
												$sub_rooms_up['open_room'] = '0';
											}
											else
											{
												$sub_rooms_up[$column]     = $field_val;
											}
											$this->db->where('owner_id', current_user_type());
											$this->db->where('hotel_id', hotel_id());
											$this->db->where('room_id', $id);
											$this->db->where('rate_types_id', $rate_type_id);
											$this->db->where('separate_date', $up_date);
											$this->db->where('refun_type', $refun);
											$this->db->where('guest_count', $guest_count);
											$this->db->where('individual_channel_id',$con_channel);
											$this->db->update(RATE_ADD, $sub_rooms_up);
										}

										$updated_data = get_data(RATE_BASE,array('individual_channel_id'=>$channe_id_update,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$date_key))->result_array();

										$channel['guest'] = $guest_count;
										$channel['refund'] = $refun;
										$channel['rate'] = $rate_type_id;
										
										if($con_channel!=17 && $con_channel != 15 && $con_channel != 14)
										{
											$this->channel_calendar_update($updated_data,$channel); 
										}
										else if($con_channel==17)
										{
											$table	=	RATE_ADD;
											$columns	=	'stop_sell';
											$update	=	'stop_sell';
											$this->bnow_model->reservation_update_no($table,$id,$up_date,$rate_type_id,$guest_count,$refun,$columns,$update);
										}
                                        else if($con_channel==15)
                                        {
                                            $table  =   RATE_ADD;
                                            $columns    =   'stop_sell';
                                            $update =   'stop_sell';
                                            $this->travel_model->reservation_update_no($table,$id,$up_date,$rate_type_id,$guest_count,$refun,$columns,$update);
                                        }
                                        else if($con_channel==14)
                                        {
                                            $table  =   RATE_ADD;
                                            $columns    =   'stop_sell';
                                            $update =   'stop_sell';
                                            $this->wbeds_model->reservation_update_no($table,$id,$up_date,$rate_type_id,$guest_count,$refun,$columns,$update);
                                        }
									}
								}
							}
						}
					}
				}
			}
			return true;
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
    
    function getallexist_subcatcntbymaincat($catid)
    {  
      // AMENITIES_TYPE   AMENITIES 
        
          $this->db->where('type_id',$catid);  
          $query=$this->db->get("room_amenities");
          if($query->num_rows()>1)
          {
               $cnt=$query->num_rows();
                return $cnt; 
               
          }
          else
          {
                return $cnt=0; 
          }
    }
    
    function payment_success_mail($payment_id)
    {
        
        $get_email_info     =   get_mail_template('10');
        
        $email_subject1= $get_email_info['subject'];

        $email_content1= $get_email_info['message']; 
        if(insep_decode($payment_id)==1)
        {
            $plan_names = get_data(TBL_PLAN,array('plan_id'=>insep_decode($payment_id)))->row();
            $plan_name = $plan_names->plan_price.' '.$plan_names->plan_name;
        }
        else        
        {
            $plan_name = get_data(TBL_PLAN,array('plan_id'=>insep_decode($payment_id)))->row()->plan_name;
        }

        $row=get_data(TBL_USERS,array('user_id'=>current_user_type()))->row();

        $aa=array(                   

        '###USERNAME###'=>$row->fname.' '.$row->lname,      

        '###id###'=>$row->transaction_id, 

        '###TYPE###'=>$plan_name, 

        '###Validity###'=>$row->plan_to, 

        '###status###'=>'Success',       

        ); 

        $email_content=strtr($email_content1,$aa);
        //print_r($email_content);
        $admin_mail = get_data('site_config',array('id'=>'1'))->row(); 

        $this->mailsettings();   

        $this->email->from($admin_mail->email_id);

        $this->email->to($row->email_address); 

        $this->email->subject($email_subject1); 

        $this->email->message($email_content);

        if($this->email->send())
        {
            return true;
        }
        else{
            return false;
        }
        

    }
    
    function updatetransaction($subscribe_plan_id,$transaction_id,$connect_channels)
    {
		$payment_id 			=	$subscribe_plan_id;
		
		$plan_details 			=	get_data(TBL_PLAN,array('plan_id'=>$payment_id))->row();
		
		/* echo 'payment_id'.$payment_id;
		
		echo '<pre>';
		
		print_r($plan_details); die; */
		
        $plan_duration 			= 	$plan_details->plan_types;
       		
		if($plan_duration=='Free')
		{
			$plan = $plan_details->plan_price;
			
			$plan_du = 'days';
		}
		elseif($plan_duration=='Month')
		{
			$plan = 1;
			
			$plan_du = 'months';
		}
		elseif($plan_duration=='Year')
		{
			$plan = 1;
			
			$plan_du = 'years';
		}
		
		$data['buy_plan_id']  		=	$payment_id;
		
		$data['buy_plan_price']		=	$plan_details->plan_price;
		
		$data['buy_plan_type']		= 	$plan_duration;
		
		$data['buy_plan_currency']	=	$plan_details->currency;
		
		$data['buy_plan_account']	=	'1';
		
		$data['transaction_id'] 	= 	$transaction_id;
		
		$data['total_channels'] 	= 	$plan_details->number_of_channels;
		
		$data['connect_channels']	= 	$connect_channels;
		
		$data['payment_method'] 	= 	'CRIDIT CARD';
		
		$data['payment_status'] 	= 	'1';
		
		if(user_membership(1)==0)
		{
			$data['plan_status'] 		=	'1';
			
			$data['plan_from'] 			=	date('Y-m-d');
			
			$data['plan_to'] 			= 	date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
		}
		else
		{
			$ex_plan_channel = user_membership(4);
			
			if($ex_plan_channel['total_channels']==$plan_details->number_of_channels || $ex_plan_channel['total_channels'] > $plan_details->number_of_channels)
			{
				$plan_from				=	date($ex_plan_channel['plan_to'], strtotime(' +1 day'));
				
				$data['plan_status']	= 	'3';
				
				$data['plan_from'] 		= 	$plan_from;
				
				$dtime 					= 	strtotime($plan_from);
				
				$data['plan_to']        =	date("Y-m-d", strtotime("+".$plan.' '.$plan_du,$dtime));
			}
			else if($ex_plan_channel['total_channels']<$plan_details->number_of_channels)
			{
				$data['plan_status']	=	'1';
				
				$data['plan_from']		=	date('Y-m-d');
				
				$data['plan_to'] 		=	date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
				
				update_data(MEMBERSHIP,array('plan_status'=>'2'),array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
			}
		}
		
		$data['user_id']		= 	current_user_type();
		
		$data['hotel_id']	 	= 	hotel_id();
		
		$check_transaction 		=	get_data(MEMBERSHIP,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'payment_method'=>'PAYPALL','transaction_id'=>$transaction_id))->row_array();
		
		if(count($check_transaction)==0)
		{
			if(insert_data(MEMBERSHIP,$data))
			{
				/* $this->inventory_model->payment_success_mail(current_user_type(),$payment_id);
				//$this->set_sessions();
				$data['page_heading'] 	= 'Payment Success';
				$user_details 			= get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data 					= array_merge($data,$user_details);
				//die; */
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
				
    }
    
    function getallexist_subcatcntallcat($catid,$start_catlimit,$end_catlimit)
    {
        
           $this->db->where('type_id',$catid);   
            $this->db->limit($end_catlimit,$start_catlimit);   
          $query=$this->db->get("room_amenities");
          if($query->num_rows()>1)
          {
               $cnt=$query->result_array();  
                return $cnt; 
               
          }
          else
          {
                return $cnt=0; 
          }
          
    }
    
    function count_room_photos($property_id)
    {
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        $count = $this->db->select('photo_id')->from(TBL_PHOTO)->
        where(array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($property_id)))->count_all_results();
        }
        else if(user_type()=='2'){
        $count = $this->db->select('photo_id')->from(TBL_PHOTO)->
        where(array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($property_id)))->count_all_results();  
        }
        return $count;
    }
    
    function add_photo($filename)
    {
        // if(user_type()=='1'){
        $ho_id = get_data(TBL_PHOTO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))))->row_array();
        /*}
        else if(user_type()=='2'){
        $ho_id = get_data(TBL_PHOTO,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))))->row_array();  
        }*/
        if(count($ho_id)!=0)
        {
            // if(user_type()=='1'){
            $value = get_data(TBL_PHOTO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))))->row()->photo_names;
            /*}
            else if(user_type()=='2'){
            $value = get_data(TBL_PHOTO,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))))->row()->photo_names;
            }*/
            $udata['room_id']=insep_decode($this->input->post('hotel_id'));
            $udata['photo_names'] = $value.','.$filename;
            // if(user_type()=='1'){
            $room_photo = update_data(TBL_PHOTO,$udata,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))));
            /*}
            else if(user_type()=='2'){
            $room_photo = update_data(TBL_PHOTO,$udata,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($this->input->post('hotel_id'))));
            }*/
            if($room_photo)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $idata['room_id']=insep_decode($this->input->post('hotel_id'));
            $idata['photo_names'] = $filename;
            // if(user_-type()=='1'){
            $idata['user_id'] = current_user_type();
            /*}
            else if(user_type()=='2'){
            $idata['user_id'] = owner_id();
            }*/
            $idata['hotel_id'] = hotel_id();
            if(insert_data(TBL_PHOTO,$idata))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        
    }

    function count_rate_types($property_id)
    {
        $count = $this->db->select('rate_type_id')->from(RATE_TYPES)->
        where(array('room_id'=>insep_decode($property_id)))->count_all_results();
        return $count;
    }

    function reservation_count($property_id,$start_date,$end_date)
    {
        if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
        {
            $manualreservation = $this->db->query("SELECT reservation_id FROM `".RESERVATION."` WHERE ( (DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') >= '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') OR ( (DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') > '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') ) ) AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND room_id='".insep_decode($property_id)."' AND status NOT IN('Canceled')");
            $chaConfirmCheckCount =  $manualreservation->num_rows();
            return  $chaConfirmCheckCount;
        }
    }
    
    function channel_reservation_count($property_id)
    {
 		$roomMappingCheck = get_data(MAP,array('property_id'=>insep_decode($property_id)),'mapping_id,channel_id,import_mapping_id')->result_array();
		if(count($roomMappingCheck)!=0)
		{
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);
				if($channel_id==11)
				{   
					$chaMapCheck = get_data(IM_RECO,array('re_id'=>$import_mapping_id),'CODE')->row_array();
					if(count($chaMapCheck)!=0)
					{
						$chaReserCheckCount = $this->db->select('import_reserv_id')->from(REC_RESERV)->
						where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'ROOMCODE'=>$chaMapCheck['CODE']))->count_all_results();
					}
				}
				else
				{
					$chaReserCheckCount=array();
				}
			}
		}
        return $chaReserCheckCount;
    }
	
	function channel_reservation_result($property_id)
    {
		$roomMappingCheck = get_data(MAP,array('property_id'=>insep_decode($property_id)),'mapping_id,channel_id,import_mapping_id')->result_array();
		if(count($roomMappingCheck)!=0)
		{
			foreach($roomMappingCheck as $roomMap)
			{
				extract($roomMap);
				if($channel_id==11)
				{
					$chaMapCheck = get_data(IM_RECO,array('re_id'=>$import_mapping_id),'CODE')->row_array();
					if(count($chaMapCheck)!=0)
					{
						$cahquery = $this->db->query('SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(CHECKIN,"%d/%m/%Y") as start_date, DATE_FORMAT(CHECKOUT,"%d/%m/%Y") as end_date ,channel_id FROM (`import_reservation_RECONLINE`) WHERE `user_id` = "'.current_user_type().'" AND `hotel_id` = "'.hotel_id().'" AND `ROOMCODE` = "'.$chaMapCheck['CODE'].'"');
						if($cahquery)
						{
							$chaReserCheckCount = $cahquery->result();
						}
						else
						{
							$chaReserCheckCount = array();
						}
					}
					else
					{
						$chaReserCheckCount = array();
					}
				}
				else
				{
					$chaReserCheckCount = array();
				}
			}
		}
		else
		{
			$chaReserCheckCount = array();
		}
        return $chaReserCheckCount;
    }
    
    function getSingleReservationChannel($reservation_id,$room_id)
    {
        $cahquery = $this->db->query('SELECT `import_reserv_id` as reservation_id, DATE_FORMAT(CHECKIN,"%d/%m/%Y") as start_date, DATE_FORMAT(CHECKOUT,"%d/%m/%Y") as end_date , channel_id , FIRSTNAME as guest_name , IDRSV as reservation_code, DATEDIFF(CHECKOUT,CHECKIN) AS num_nights , ADULTS as members_count , CORRRATE as price , ROOMCODE FROM (`import_reservation_RECONLINE`) WHERE `user_id` = "'.current_user_type().'" AND `hotel_id` = "'.hotel_id().'" AND `import_reserv_id` = "'.$reservation_id.'" AND `ROOMCODE` = "'.$room_id.'"');
        if($cahquery)
        {
            $chaReserResult = $cahquery->row_array();
        }
        return $chaReserResult;
    }
   
	function getRoomRelation($property_id)
    {
        $roomMappingCheck = get_data(MAP,array('property_id'=>insep_decode($property_id)),'mapping_id,channel_id,import_mapping_id')->result_array();
            if(count($roomMappingCheck)!=0)
            {
                foreach($roomMappingCheck as $roomMap)
                {
                    extract($roomMap);
                    if($channel_id==11)
                    {
                        $chaMapCheck = get_data(IM_RECO,array('re_id'=>$import_mapping_id),'CODE')->row_array();
                        if(count($chaMapCheck)!=0)
                        {
                            return $chaMapCheck['CODE'];
                        }
                    }
                }
            }
    }

    function reser_meal_plan($room_id)
    {
        $this->db->select('b.meal_name');
        $this->db->join(MEAL.' as b','a.meal_plan=b.meal_id');
        $query = $this->db->get(TBL_PROPERTY.' as a');
        if($query)
        {
        return $query->row()->meal_name;
        }

        else
        {
        return false;
        }
    }
    
    function user_room_count()
    {
        if(user_type()=='1'){
            $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
            where(array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->count_all_results();
        return $count;
         }
         else if(user_type()=='2'){
             $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
             where(array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->count_all_results();
            return $count;
         }
         else if(admin_id()!='' && admin_type()=='1')
         {
             $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
             where(array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->count_all_results();
        return $count;
         }
    }

    function user_room_count1(){
        if(user_type()=='1'){
            $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
            where(array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->count_all_results();
            return $count;
        }
        else if (user_type()=='2'){
            $count = $this->db->select('property_id')->from(TBL_PROPERTY)->
            where(array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->count_all_results();
            return $count;
        }
    }
    // 10/12/2015....start
    
	function get_connected_channel($room_id){
        $this->db->where('owner_id',current_user_type());
        $this->db->where('hotel_id',hotel_id());
        $this->db->where('property_id',$room_id);
        $res = $this->db->get('manage_property');
        if($res->num_rows>0)
        {
            return $res->row_array();
        }
            return false;
    }
    
    // connected channels...
    function connected_channel($connected)  
    {
        $connect= explode(',',$connected);
        $this->db->where('owner_id',current_user_type());
        $this->db->where('hotel_id',hotel_id());
        $this->db->where_in('channel_id',$connect);
        $res = $this->db->get('roommapping');
        if($res->num_rows>0){
            return $res->result();
        }
            return false;
    }
    
    // get channels...
    function get_channels($cha){
        $this->db->where_in('channel_id',$cha);
        $res = $this->db->get('manage_channel');
        if($res->num_rows>0){
            return $res->result();
        }
            return false;
    }
    
	function user_channel_mapping()
    {
       $this->db->select('M.channel_id,C.channel_id,C.channel_name');  
       $this->db->join(TBL_CHANNEL.' as C','M.channel_id=C.channel_id');
	   $this->db->where(array('M.owner_id'=>current_user_type(),'M.hotel_id'=>hotel_id()));
      	$this->db->group_by('M.channel_id');
       $all_map_rooms= $this->db->get(MAP.' as M');
       if($all_map_rooms)
       {
           return $all_map_rooms->result_array();
       }
       else {
           return FALSE;
       }
    }
   
	function roomtype_ICAL($room_id='',$rate_types_id='')
    {
        $ic_room_id = $room_id;
        $hotel_details = get_data(HOTEL,array('hotel_id'=>hotel_id()))->row_array();
        $country_details = get_data(TBL_COUNTRY,array('id'=>$hotel_details['country']))->row()->country_name;
		$this->db->select('separate_date,availability,stop_sell');
        $this->db->where('owner_id',current_user_type());
        $this->db->where('hotel_id',hotel_id());
        $this->db->where('individual_channel_id','0');
        $this->db->where('room_id',$room_id);
        $this->db->where('rate_types_id',$rate_types_id);
        $data_ical = $this->db->get('room_rate_types_base')->result_array();
        if(count($data_ical)!=0)
        {
            $schedules = "BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH";
            $i=0;
            foreach($data_ical as $value)
            {
                extract($value);
                $i++;
				if($availability==0 || $stop_sell==1)
				{
					$startDate = date('Y-m-d',strtotime(str_replace('/','-',$separate_date)));
					$strDate = strtotime(date('Y-m-d',strtotime(str_replace('/','-',$separate_date))));
					$endDate = strtotime(date('Y-m-d',strtotime(str_replace('/','-',$separate_date))));
					$schedules .= "\nBEGIN:VEVENT";
					$schedules .= "\nUID:" . time().rand(11111, 99999);
					$schedules .= "\nDTSTAMP:" . date("Y-m-d H:i:s");
					$schedules .= "\nLOCATION:".$hotel_details['address'].' , '.$hotel_details['town'].' , '.$country_details;
					$schedules .= "\nURL;VALUE=".$hotel_details['web_site'];
					$schedules .= "\nDTSTART:" . date("Ymd", $strDate)."T".date("His")."Z";
					$schedules .= "\nDTEND:". date("Ymd",$endDate)."T".date("His")."Z";
					$schedules .= "\nSUMMARY:BOOKED";
					/* if(all_ical_count($startDate)==0)
					{
						$schedules .= "\nSUMMARY:".$availability.' AVAILABILITY';
					}
					else
					{
						$availabilitys=0;
						$schedules .= "\nSUMMARY:".$availabilitys.' RESERVATION';
					} */
					$schedules .= "\nEND:VEVENT";
					$schedules .= "\n";
				}
            }
            $schedules .= "\nEND:VCALENDAR";
            $available = get_data(ICAL,array('rate_type_id'=>$rate_types_id))->row_array();
            if(count($available)!=0)
            {
                $file_name = $available['ical_link'];
                file_put_contents("uploads/ICAL/roomtype/".$file_name,$schedules);
            }
            else
            {
                $file_name = time().rand(11111, 99999).'_aval_'.$ic_room_id.'_'.$rate_types_id.'.ics';
                $ic_data['ical_link'] = $file_name;
                $ic_data['room_id']   = $ic_room_id;
                $ic_data['rate_type_id']   = $rate_types_id;
                insert_data(ICAL,$ic_data);
                file_put_contents("uploads/ICAL/roomtype/".$file_name,$schedules);    
            }
                    return $file_name;
            }
            else
            {
                return false;
            }
    }  

	/* --- Channel Update Functionality Begin--- */
    function channel_update($udata,$chdata, $date)
	{
        extract($udata);
        extract($chdata);
        
        if(isset($price)){
            $priceAmount = $price;
        }elseif (isset($refund_amount)) {
            $priceAmount = $refund_amount;
        }elseif (isset($non_refund_amount)) {
            $priceAmount = $non_refund_amount;
        }
        $update_date = $date;
        $exp_date = date('Y-m-d',strtotime(str_replace('/', '-', $update_date)));

        $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type,'enabled'=>'enabled'))->result();

        if(isset($open_room) == 0 )
        {
            if(isset($stop_sell)!= 0)
            {
                $stop_sale =$stop_sell;
            }
            elseif(isset($stop_sell)== 0)
            {
                $stop_sale ='0';
            }
        }
        else if(isset($open_room) != 0)
        {
            $stop_sale ='0';
        }
        if($stop_sale=='0')
        {
            if($availability!='')
            {
                $ch_update_avail = $availability;
            }
            else 
            {
                $ch_update_avail='';
            }
        }
        else
        {
            $ch_update_avail='-100';
        }
        if($room_mapping)
		{
            foreach($room_mapping as $room_value)
			{ 

                if($room_value->channel_id == 1)
				{
                    $up_days =  explode(',',$days);
                    if(in_array('1', $up_days)) 
                    {
                        $exp_mon = "true";
                    }
                    else 
                    {
                        $exp_mon = 'false';
                    }
                    if(in_array('2', $up_days)) 
                    {
                        $exp_tue = 'true'; 
                    }
                    else 
                    {
                        $exp_tue = 'false';
                    }
                    if(in_array('3', $up_days)) 
                    {
                        $exp_wed = 'true';
                    }
                    else 
                    {
                        $exp_wed = 'false';
                    }
                    if(in_array('4', $up_days)) 
                    {
                        $exp_thur = 'true';
                    }
                    else 
                    {
                        $exp_thur = 'false';
                    }
                    if(in_array('5', $up_days)) 
                    {
                        $exp_fri = 'true';
                    }
                    else 
                    {
                        $exp_fri = 'false';
                    }
                    if(in_array('6', $up_days)) 
                    {
                        $exp_sat = 'true';
                    }
                    else 
                    {
                        $exp_sat = 'false';
                    }
                    if(in_array('7', $up_days)) 
                    {
                        $exp_sun = 'true';
                    }
                    else 
                    {
                        $exp_sun = 'false';
                    }
                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                    if($ch_details->mode == 0){
                        $urls = explode(',', $ch_details->test_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $exp[$path[0]] = $path[1];
                        }
                    }else if($ch_details->mode == 1){
                        $urls = explode(',', $ch_details->live_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $exp[$path[0]] = $path[1];
                        }
                    } 
                                    
                    $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                    $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'hotel_channel_id' => $mp_details->hotel_channel_id,'rateType' => 'SellRate'))->row();
                    $oa_details = get_data('import_mapping_expedia_occupancy',array('user_id'=>current_user_type(),'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id))->row();
                    
                    $minlos = $mp_details->minLos;
                    $maxLos = $mp_details->maxLos;
                    $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                    if($mapping_values){
                        if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
                            if($minlos < $mapping_values['value']){
                                $maxLos = $mapping_values['value'];
                            }
                        }
                    }
                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                            <AvailRateUpdate>';
                    if(!empty($up_days)){
                        $xml .= '<DateRange from="'.$exp_date.'" to="'.$exp_date.'"/>';
                    }else{
                        $xml .= '<DateRange from="'.$exp_date.'" to="'.$exp_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                    }
                    if($room_value->explevel == "rate"){

                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                        if($availability != "" && $room_value->update_availability=='1'){
                            $xml .= '<Inventory totalInventoryAvailable="'.$availability.'"/>';
                        }

                        if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                            $plan_id = $mp_details->rateplan_id;
                        }else{
                            $plan_id = $mp_details->rate_type_id;
                        }

                        if ($stop_sell == 1 && $open_room == 0) {
                            $xml .= '<RatePlan id="'.$plan_id.'" closed="true">';
                        }else if ($stop_sell == 0 && $open_room == 1) {
                           $xml .= '<RatePlan id="'.$plan_id.'" closed="false">';
                        }else{
                            $xml .= '<RatePlan id="'.$plan_id.'">';
                        } 
                        if($priceAmount != '' && $room_value->update_rate=='1'){
                            if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                for($i = $minlos; $i<=$maxLos; $i++){
                                    $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                            <PerDay rate="'.$priceAmount.'"/>
                                            </Rate>';
                                }
                            }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$priceAmount.'"/>
                                        </Rate> ';
                            }
							elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
							{
								$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$priceAmount.'" occupancy = "2"/></Rate> ';
								$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
							}
                        }

                        $xml .= '<Restrictions';
                        if($ctd == 1){
                            $xml .= ' closedToDeparture="true"';
                        }else if($ctd == 0){
                            $xml .= ' closedToDeparture="false"';
                        }
                        if($cta == 1){
                            $xml .= ' closedToArrival="true"';
                        }else if($cta == 0){
                            $xml .= ' closedToArrival="false"';
                        }
                        if($minimum_stay != "" && $minimum_stay != 0){
                            $xml .= ' minLOS="'.$minimum_stay.'" maxLOS="'.$maxLos.'"';
                        }
                        $xml .= ' />';

                        $xml .= "</RatePlan>";
                    }else if($room_value->explevel == "room"){
                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                        if ($stop_sell == 1 && $open_room == 0) {
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                        }else if ($stop_sell == 0 && $open_room == 1) {
                           $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                        }else{
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                        } 

                        if($availability != "" && $room_value->update_availability=='1'){
                            $xml .= '<Inventory totalInventoryAvailable="'.$availability.'"/>';
                        }
                        
                        $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                        foreach($available_plans as $e_plan){
                            if($e_plan->rateAcquisitionType != "Linked"){

                                if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                    $plan_id = $e_plan->rateplan_id;
                                }else{
                                    $plan_id = $e_plan->rate_type_id;
                                }
                                $xml .= '<RatePlan id="'.$plan_id.'">';
                                if($priceAmount != '' && $room_value->update_rate=='1'){
                                    if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                        for($i = $minlos; $i<=$maxLos; $i++){
                                            $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                    <PerDay rate="'.$priceAmount.'"/>
                                                    </Rate>';
                                        }
                                    }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                        $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$priceAmount.'"/>
                                                </Rate> ';
                                    }
									elseif($e_plan->pricingModel == 'OccupancyBasedPricing')
									{
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$priceAmount.'" occupancy = "2"/></Rate> ';
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
									}
                                }

                                $xml .= '<Restrictions';
                                if($ctd == 1){
                                    $xml .= ' closedToDeparture="true"';
                                }else if($ctd == 0){
                                    $xml .= ' closedToDeparture="false"';
                                }
                                if($cta == 1){
                                    $xml .= ' closedToArrival="true"';
                                }else if($cta == 0){
                                    $xml .= ' closedToArrival="false"';
                                }
                                if($minimum_stay != "" && $minimum_stay != 0){
                                    $xml .= ' minLOS="'.$minimum_stay.'" maxLOS="'.$maxLos.'"';
                                }
                                $xml .= ' />';

                                $xml .= "</RatePlan>";
                            }
                        }
                    }
                    $xml .="</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
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
                    $data = simplexml_load_string($output); 
                    @$response = $data->Error;
                    //echo $response;
                    if($response!='')
                    {
                       // echo 'fail';
                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                        $expedia_update = "Failed";
                    }
                    else
                    {
                       // echo 'success   ';
                        $expedia_update = "Success";
                    }
                    curl_close($ch);
                }
				else if($room_value->channel_id == 11)
				{

                    if($priceAmount!='' || $availability!=''){

                        $update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channel_id,'room_id'=>$property_id,'separate_date'=>$date))->row();
                        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$property_id))->row();
                        if(count($room_details) != 0)
                        {
                            if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                        }else{
                            $meal_plan = 0;
                        }

                        $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$update_Details->separate_date)));
                        
                        $up_days =  explode(',',$update_Details->days);
                         
                        if(in_array('1', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $monday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $monday_cta = '0';
                            }
                            $monday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $monday_cta ='1';
                            $monday_ss='0';
                        }
                        if(in_array('2', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $tuesday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $tuesday_cta = '0';
                            }
                            $tuesday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $tuesday_cta ='1';
                            $tuesday_ss='0';
                        }
                        if(in_array('3', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $wednesday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $wednesday_cta = '0';
                            }
                            
                            $wednesday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $wednesday_cta ='1';
                            $wednesday_ss='0';
                        }
                        if(in_array('4', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $thursday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $thursday_cta = '0';
                            }
                            
                            $thursday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $thursday_cta ='1';
                            $thursday_ss='0';
                        }
                        if(in_array('5', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $friday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $friday_cta = '0';
                            }
                            
                            $friday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $friday_cta ='1';
                            $friday_ss='0';
                        }
                        if(in_array('6', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $saturday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $saturday_cta = '0';
                            }
                            
                            $saturday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $saturday_cta ='1';
                            $saturday_ss='0';
                        }
                        if(in_array('7', $up_days)) 
                        {
                            if($update_Details->cta == 1){
                                $sunday_cta = '1';
                            }else if($update_Details->cta == 0){
                                $sunday_cta = '0';
                            }
                            $sunday_ss = $update_Details->stop_sell;
                        }
                        else 
                        {
                            $sunday_cta ='1';
                            $sunday_ss='0';
                        }
                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                        if($ch_details->mode == 0){
                            $urls = explode(',', $ch_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        }else if($ch_details->mode == 1){
                            $urls = explode(',', $ch_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        } 
                        $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                        if($priceAmount!='' && $room_value->update_rate=='1'){
                            $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                            if($mapping_values)
                            {
                                $label=$mapping_values['label'];
                                $val=$mapping_values['value'];  
                                $label_split=explode(",",$label);
                                $val_split=explode(",",$val);
                                $set_arr=array_combine($label_split,$val_split);
                                $i=0;
                                $mapping_fields='';
                                foreach($set_arr as $k=>$v)
                                {
                                    if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
                                        if(strpos($v, '+') !== FALSE){
                                            $opr = explode('+', $v);
                                            if(is_numeric($opr[1])){
                                                $ex_price = $priceAmount + $opr[1];
                                            }else if(is_numeric($opr[0])){
                                                $ex_price = $priceAmount + $opr[0];
                                            }else{
                                                if(strpos($opr[1], '%')){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($priceAmount * $per[0]) / 100;
                                                        $ex_price = $priceAmount + $per_price;
                                                    }
                                                }elseif (strpos($opr[0], '%')) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($priceAmount * $per[0]) / 100;
                                                        $ex_price = $priceAmount + $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '-') !== FALSE) {
                                            $opr = explode('-', $v);
                                            if(is_numeric($opr[1])){
                                                $ex_price = $priceAmount - $opr[1];
                                            }elseif (is_numeric($opr[0])) {
                                                $ex_price = $priceAmount - $opr[0];
                                            }else{
                                                if(strpos($opr[1],'%') !== FALSE){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($priceAmount * $per[0]) / 100;
                                                        $ex_price = $priceAmount - $per_price;
                                                    }
                                                }elseif (strpos($opr[0],'%') !== FALSE) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($priceAmount * $per[0]) / 100;
                                                        $ex_price = $priceAmount - $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '%') !== FALSE) {
                                            $opr = explode('%', $v);
                                            if(is_numeric($opr[1])){
                                                $per_price = ($priceAmount * $opr[1]) / 100;
                                                $ex_price = $priceAmount + $per_price;
                                            }elseif (is_numeric($opr[0])) {
                                                $per_price = ($priceAmount * $opr[0]) / 100;
                                                $ex_price = $priceAmount + $per_price;
                                            }
                                        }else{
                                            $ex_price = $priceAmount + $v;
                                        }
                                        
                                        $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                    }else{
                                        $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                    }
                                }
                            }
                            if($minimum_stay!='')
                            {
                                $minimum_stay = $minimum_stay;
                            }
                            else
                            {
                                $minimum_stay = 0;
                            }
                            
                            $url = trim($reco['urate_avail']);
                            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                            <UpdateRates xmlns="https://www.reconline.com/">
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                            <idSystem>0</idSystem>
                            <ForeignPropCode></ForeignPropCode>
                            <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                            <ExcludeRateLevels></ExcludeRateLevels>
                            <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                            <ExcludeRoomTypes></ExcludeRoomTypes>
                            <RateType>1</RateType>
                            <StartDate>'.$up_sart_date.'</StartDate>
                            <EndDate>'.$up_sart_date.'</EndDate>
                            <SingleOcc>'.$priceAmount.'</SingleOcc>'.
                                $mapping_fields.'
                            <Meals>'.$meal_plan.'</Meals>
                            <MinStay>'.$minimum_stay.'</MinStay>
                            <BlockStay>0</BlockStay>
                            <Guarantee>0</Guarantee>
                            <Cancel></Cancel>
                            <CTAMonday>'.$monday_cta.'</CTAMonday>
                            <CTATuesday>'.$tuesday_cta.'</CTATuesday>
                            <CTAWednesday>'.$wednesday_cta.'</CTAWednesday>
                            <CTAThursday>'.$thursday_cta.'</CTAThursday>
                            <CTAFriday>'.$friday_cta.'</CTAFriday>
                            <CTASaturday>'.$saturday_cta.'</CTASaturday>
                            <CTASunday>'.$sunday_cta.'</CTASunday>
                            </UpdateRates>
                            </soap12:Body>
                            </soap12:Envelope>';
                            $headers = array(
                                                "Content-type: application/soap+xml; charset=utf-8",
                                                "Host:www.reconline.com",
                                                "Content-length: ".strlen($xml_post_string),
                                                ); 
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            $ss = curl_getinfo($ch);                
                            $response = curl_exec($ch);
                            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                            $xml = simplexml_load_string($xml);
                            $json = json_encode($xml);
                            $responseArray = json_decode($json,true);
                            $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                            $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                            if(count($Errorarray)=='0' && count($soapFault)=='0')
                            {
                                $reconline_price_response = "success";
                            }
                            else 
                            {
                                $reconline_price_response = "error";
                                if(count($Errorarray)!='0')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Channel Update',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Channel Update',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                }
                            }
                            curl_close($ch);
                        }
                        
                        if($ch_update_avail!='' && $room_value->update_availability=='1')
                        {
                            $url = trim($reco['urate_avail']);
                            $xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                            <UpdateAvail xmlns="https://www.reconline.com/"> 
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                            <idSystem>0</idSystem>
                            <ForeignPropCode></ForeignPropCode>
                            <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                            <ExcludeRateLevels></ExcludeRateLevels>
                            <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                            <ExcludeRoomTypes></ExcludeRoomTypes>
                            <AvailMode>0</AvailMode>
                            <StartDate>'.$up_sart_date.'</StartDate>
                            <Availability>='.$ch_update_avail.'</Availability>
                            </UpdateAvail>
                            </soap12:Body>
                            </soap12:Envelope>';
                            $headers_avail = array(
                                                "Content-type: application/soap+xml; charset=utf-8",
                                                "Host:www.reconline.com",
                                                "Content-length: ".strlen($xml_post_string_update),
                                                );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
                            $ss = curl_getinfo($ch);                
                            $response = curl_exec($ch);
                            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                            $xml = simplexml_load_string($xml);
                            $json = json_encode($xml);
                            $responseArray = json_decode($json,true);
                            $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                            $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                            if(count($Errorarray)=='0' && count($soapFault)=='0')
                            {
                                $reconline_availability_response="success";
                            }
                            else 
                            {
                                $reconline_availability_response="error";
                                if(count($Errorarray)!='0')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Channel Update',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Channel Update',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                }
                                return false;
                            }
                            curl_close($ch);
                        }           
                    }
                }
				else if($room_value->channel_id == 5)
				{

                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                    if($ch_details->mode == 0){
                        $urls = explode(',', $ch_details->test_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $htb[$path[0]] = $path[1];
                        }
                    }else if($ch_details->mode == 1){
                        $urls = explode(',', $ch_details->live_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $htb[$path[0]] = $path[1];
                        }
                    }             
                    $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                    $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                    $maxnum = 99;
                    if($mapping_values){
                        if($mapping_values['label']== "MaximumNoOfDays")
                        {
                            $maxnum = $mapping_values['value'];                                        
                        }
                    }

                    $hotelbed_start = str_replace('-', '', $exp_date);

                    $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                        <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                        <getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                        <HSI_ContractInventoryModificationRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                        <Language>ENG</Language>
                        <Credentials>
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                        </Credentials>
                        <Contract>
                            <Name>'.$mp_details->contract_name.'</Name>
                            <IncomingOffice code="'.$mp_details->contract_code.'"/>
                            <Sequence>'.$mp_details->sequence.'</Sequence>
                        </Contract>
                        <InventoryItem>
                            <DateFrom date="'.$hotelbed_start.'"/>
                            <DateTo date="'.$hotelbed_start.'"/>';
                    if($availability != "" && $room_value->update_availability=='1'){
                        if($stop_sell == 0 && $open_room == 1){
                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'">';
                        }else if($stop_sell == 1 && $open_room == 0){
                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'" closed="Y">';
                        }else{
                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'">';
                        }                                   
                    }else{ 
                        if($stop_sell == 0 && $open_room == 1){
                            $xml_post_string .= '<Room>';
                        }else if($stop_sell == 1 && $open_room == 0){
                            $xml_post_string .= '<Room closed="Y">';
                        }else{
                            $xml_post_string .= '<Room>';
                        }   
                    }
                    
                    $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                    if($priceAmount != "" && $room_value->update_rate == '1' && $priceAmount!=0)   {
                        $xml_post_string .= '<Price><Amount>'.$priceAmount.'</Amount></Price>';
                    }
                    $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                     </soapenv:Envelope>';
                    $headers = array(
                        "SOAPAction:no-action",
                        "Content-length: ".strlen($xml_post_string),
                    ); 
                    $url = trim($htb['urate_avail']);

                    // PHP cURL  for https connection with auth
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $ss = curl_getinfo($ch);                
                    $response = curl_exec($ch);
                    //echo $response;
                    //echo "<pre>";
                    
                    $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                    $xml_parse = simplexml_load_string($xmlreplace);
                    $json = json_encode($xml_parse);
                    $responseArray = json_decode($json,true);
                    //print_r($responseArray);

                    $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                    //print_r($xml);
                    

                    if($xml->ErrorList){
                        $status = $xml->ErrorList->Error;
                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Channel Update',date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                    }else if(@$xml->Status != "Y"){
                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$xml->Status,'Channel Update',date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('bulk_error', "Try Again");
                    }

                    if($minimum_stay != ""){
                        $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope
                            soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                            xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractDetailModification
                            xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractDetailModificationRQ>
                            <Language>ENG</Language>
                            <Credentials>
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            </Credentials>
                            <Contract>
                            <Name>'.$mp_details->contract_name.'</Name>
                            <IncomingOffice code="'.$mp_details->contract_code.'"/>
                            <Sequence>'.$mp_details->sequence.'</Sequence>
                            </Contract>
                            <MinimumStayList>
                            <MinimumStay>
                            <DateFrom date="'.$hotelbed_start.'"/>
                            <DateTo date="'.$hotelbed_start.'"/>
                            <MinNumberOfDays>'.$minimum_stay.'</MinNumberOfDays>
                            <MaxNumberOfDays>'.$maxnum.'</MaxNumberOfDays>
                            <RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>
                            </MinimumStay>
                            </MinimumStayList>
                            </HSI_ContractDetailModificationRQ>
                            </getHSIContractDetailModification>
                            </soapenv:Body>
                            </soapenv:Envelope>';

                        $headers = array(
                        "SOAPAction:no-action",
                        "Content-length: ".strlen($xml_post_string),
                        ); 
                        $url = trim($htb['urate_avail']);

                        // PHP cURL  for https connection with auth
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $ss = curl_getinfo($ch);                
                        $response = curl_exec($ch);
                        //echo $response;
                        
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractDetailModification']);
                        //print_r($xml);  
                        
                        if($xml->ErrorList){
                            $status = $xml->ErrorList->Error;
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Channel Update',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                        }else if(@$xml->Status != "Y"){
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$xml->Status,'Channel Update',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Try Again");
                        }
                    }


                }
				else if($room_value->channel_id == 8)
				{
                    $up_days =  explode(',',$days);
                    if(in_array('1', $up_days)) 
                    {
                        $dayval = "1";
                    }
                    else 
                    {
                        $dayval = '0';
                    }
                    if(in_array('2', $up_days)) 
                    {
                        $dayval .= '1'; 
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    if(in_array('3', $up_days)) 
                    {
                        $dayval .= '1';
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    if(in_array('4', $up_days)) 
                    {
                        $dayval .= '1';
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    if(in_array('5', $up_days)) 
                    {
                        $dayval .= '1';
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    if(in_array('6', $up_days)) 
                    {
                        $dayval .= '1';
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    if(in_array('7', $up_days)) 
                    {
                        $dayval .= '1';
                    }
                    else 
                    {
                        $dayval .= '0';
                    }
                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                    if($ch_details->mode == 0){
                        $urls = explode(',', $ch_details->test_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $gta[$path[0]] = $path[1];
                        }
                    }else if($ch_details->mode == 1){
                        $urls = explode(',', $ch_details->live_url);
                        foreach($urls as $url){
                            $path = explode("~",$url);
                            $gta[$path[0]] = $path[1];
                        }
                    } 
                                
                    $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                          
                    $gt_room_id=$mp_details->ID;
                    $rateplanid=$mp_details->rateplan_id;
                    $MinPax=$mp_details->MinPax;
                    $peakrate=$mp_details->peakrate;
                    $MaxOccupancy=$mp_details->MaxOccupancy;
                 
                    $payfullperiod=$mp_details->payfullperiod;
                                                                
                    $contract_id=$mp_details->contract_id; 
                    $contract_type = $mp_details->contract_type; 

                    $hotel_channel_id=$mp_details->hotel_channel_id;

                    if($priceAmount!=0 && $room_value->update_rate == '1'){

                        $pri=$priceAmount; 
                        if($contract_type == "Static"){
                            $soapUrl = trim($gta['urate_s']);
                            $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                GTA_RateCreateRQ.xsd">
                                  <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                <RatePlan Id="'.$rateplanid.'">
                                <StaticRate Start="'.$exp_date.'" End="'.$exp_date.'"
                                DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                PeakRate="'.$peakrate.'">
                                <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$pri.'" />
                                </StaticRate>
                                </RatePlan>
                                </GTA_StaticRatesCreateRQ>';
                                                               

                            $ch = curl_init($soapUrl);

                                //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                $output = curl_exec($ch);
                                  curl_close($ch);  
                            $data = simplexml_load_string($output);
                            $Error_Array = @$data->Errors->Error;
                            if($Error_Array!='')
                            {
                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                            }
                        }else if($contract_type == "Margin"){

                            $soapUrl = trim($gta['urate_m']);

                            $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$exp_date.'" End="'.$exp_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                            $ch = curl_init($soapUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                            $response = curl_exec($ch);
                            $data = simplexml_load_string($response);
                            $Error_Array = @$data->Errors->Error;

                            if($Error_Array!='')
                            {
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                            }         
                        }
                    }
                    if($availability!=0 && $room_value->update_rate == '1'){
                        $soapUrl=trim($gta['uavail']);
                        $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05  GTA_InventoryUpdateRQ.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$ch_details->hotel_channel_id.'" ><RoomStyle>';
                        $xml_post_string.='<StayDate Date = "'.$exp_date .'">
                            <Inventory RoomId="'.$gt_room_id.'" ><Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$availability.'" ReleaseDays="0"/>
                            </Inventory>
                            </StayDate>';
                        $xml_post_string.='</RoomStyle></InventoryBlock>
                                    </GTA_InventoryUpdateRQ>';
                        $ch = curl_init($soapUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                        $response = curl_exec($ch); 
                        $data = simplexml_load_string($response);
                        $Error_Array = @$data->Errors->Error;
                        if($Error_Array!='')
                        {
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Channel Update',date('m/d/Y h:i:s a', time()));
                        }                        
                    }
                    if($room_value->update_availability == '1'){
                        $soapUrl=trim($gta['uavail']);
                        $xml_post_string='<GTA_InventoryUpdateRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQhelp.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                            <InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                        $xml_post_string.=' <StayDate Date = "'.$exp_date.'"><Inventory RoomId = "'.$gt_room_id.'">';
                        if($stop_sell == 0 && $open_room == 1 && $availability != ""){ 
                            $xml_post_string .= '<Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$availability.'" ReleaseDays="0"/>';
                            $xml_post_string.= '<Restriction FlexibleStopSell = "false" InventoryType = "Flexible"/>';
                        }else if($stop_sell == 0 && $open_room == 1 && $availability == ""){
                            $xml_post_string .= '<Detail FreeSale="false" InventoryType="Flexible" Quantity="5" ReleaseDays="0"/>';
                            $xml_post_string.= '<Restriction FlexibleStopSell = "false" InventoryType = "Flexible"/>';
                        }
                        if($stop_sell == 1 && $open_room == 0){ 
                            $xml_post_string.= '<Restriction FlexibleStopSell = "true" InventoryType = "Flexible"/>';
                        }
                        $xml_post_string.=' </Inventory></StayDate> ';
                        $xml_post_string.=' </RoomStyle></InventoryBlock></GTA_InventoryUpdateRQ>';
                        $ch = curl_init($soapUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                        $response = curl_exec($ch); 
                        $data = simplexml_load_string($response);
                        $Error_Array = @$data->Errors->Error;
                        if($Error_Array!='')
                        {
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Channel Update',date('m/d/Y h:i:s a', time()));
                        }
                    }
                }
				else if($room_value->channel_id == 2)
                {   
					$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row()->xml_type;
					if($chk_allow==2 || $chk_allow==3)
					{
						$this->load->model('booking_model');
						$this->booking_model->full_calendar_update($chdata,$udata,$exp_date,$priceAmount,$room_value->import_mapping_id,$room_value->mapping_id);
					}
                }
            }
        }

    }
    /* --- End Of Channel Update Functionality --- */

    /* --- Channel Calendar Update Begin --- */
    function channel_calendar_update($update_data, $chdata){
        extract($chdata);

        foreach ($update_data as $udata) {
            if(isset($udata['price'])){
                $price = $udata['price'];
            }elseif (isset($udata['refund_amount'])) {
                $price = $udata['refund_amount'];
            }elseif (isset($udata['non_refund_amount'])) {
                $price = $udata['non_refund_amount'];
            }
            $update_date = date('Y-m-d',strtotime(str_replace('/','-',$udata['separate_date'])));
            $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$udata['separate_date'])));
            
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$udata['individual_channel_id'],'property_id'=>$udata['room_id'],'rate_id'=>$rate,'guest_count'=>$guest,'refun_type'=>$refund,'enabled'=>'enabled'))->result();
            
            if(isset($udata['open_room'])== 0 )
            {
                if(isset($udata['stop_sell'])!= 0)
                {
                    $stop_sale =$udata['stop_sell'];
                }
                elseif(isset($udata['stop_sell'])== 0)
                {
                    $stop_sale ='0';
                }
            }
            else if(isset($udata['open_room'])!= 0)
            {
                $stop_sale ='0';
            }
            if($stop_sale=='0')
            {
                if($udata['availability']!='')
                {
                    $ch_update_avail = $udata['availability'];
                }
                else 
                {
                    $ch_update_avail='';
                }
            }
            else
            {
                $ch_update_avail='-100';
            }
            if($room_mapping){
                foreach($room_mapping as $room_value){

                    if($room_value->channel_id == 1)
					{
                        $up_days =  explode(',',$udata['days']);
                        if(in_array('1', $up_days)) 
                        {
                            $exp_mon = "true";
                        }
                        else 
                        {
                            $exp_mon = 'false';
                        }
                        if(in_array('2', $up_days)) 
                        {
                            $exp_tue = 'true'; 
                        }
                        else 
                        {
                            $exp_tue = 'false';
                        }
                        if(in_array('3', $up_days)) 
                        {
                            $exp_wed = 'true';
                        }
                        else 
                        {
                            $exp_wed = 'false';
                        }
                        if(in_array('4', $up_days)) 
                        {
                            $exp_thur = 'true';
                        }
                        else 
                        {
                            $exp_thur = 'false';
                        }
                        if(in_array('5', $up_days)) 
                        {
                            $exp_fri = 'true';
                        }
                        else 
                        {
                            $exp_fri = 'false';
                        }
                        if(in_array('6', $up_days)) 
                        {
                            $exp_sat = 'true';
                        }
                        else 
                        {
                            $exp_sat = 'false';
                        }
                        if(in_array('7', $up_days)) 
                        {
                            $exp_sun = 'true';
                        }
                        else 
                        {
                            $exp_sun = 'false';
                        }
                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$udata['individual_channel_id']))->row();
                        if($ch_details->mode == 0){
                            $urls = explode(',', $ch_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $exp[$path[0]] = $path[1];
                            }
                        }else if($ch_details->mode == 1){
                            $urls = explode(',', $ch_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $exp[$path[0]] = $path[1];
                            }
                        } 
                                        
                        $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                        $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'hotel_channel_id' => $mp_details->hotel_channel_id,'rateType' => 'SellRate'))->row();
                        $oa_details = get_data('import_mapping_expedia_occupancy',array('user_id'=>current_user_type(),'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id))->row();
                        
                        $minlos = $mp_details->minLos;
                        $maxLos = $mp_details->maxLos;
                        $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                        if($mapping_values){
                            if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
                                if($minlos < $mapping_values['value']){
                                    $maxLos = $mapping_values['value'];
                                }
                            }
                        }
                        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                <AvailRateUpdate>';
                        if(!empty($up_days)){
                            $xml .= '<DateRange from="'.$update_date.'" to="'.$update_date.'"/>';
                        }else{
                            $xml .= '<DateRange from="'.$update_date.'" to="'.$update_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                        }
                        if($room_value->explevel == "rate"){
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($udata['availability'] != "" && $room_value->update_availability=='1'){
                                $xml .= '<Inventory totalInventoryAvailable="'.$udata['availability'].'"/>';
                            }
                            if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                $plan_id = $mp_details->rateplan_id;
                            }else{
                                $plan_id = $mp_details->rate_type_id;
                            }

                            if ($udata['stop_sell'] == 1 && $udata['open_room'] == 0) {
                               $xml .= '<RatePlan id="'.$plan_id.'" closed="true">';
                            }else if ($udata['stop_sell'] == 0 && $udata['open_room'] == 1) {
                                $xml .= '<RatePlan id="'.$plan_id.'" closed="false">';
                            }else if($udata['stop_sell'] == 1 && $udata['open_room'] == 1){
                                $xml .= '<RoomType id="'.$plan_id.'" closed="true">';
                            }else{
                                $xml .= '<RatePlan id="'.$plan_id.'">';
                            }
                            //$price != '' && $price >= (string)$rt_details->minAmount && $price <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                            if($price != '' && $room_value->update_rate=='1'){
                                if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                    for($i = $minlos; $i<=$maxLos; $i++){
                                        $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                <PerDay rate="'.$price.'"/>
                                                </Rate>';
                                    }
                                }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$price.'"/>
                                            </Rate> ';
                                }
								elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
								{
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$price.'" occupancy = "2"/></Rate> ';
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
								}
                            }

                            $xml .= '<Restrictions';
                            if($udata['ctd'] == 1){
                                $xml .= ' closedToDeparture="true"';
                            }else if($udata['ctd'] == 0){
                                $xml .= ' closedToDeparture="false"';
                            }
                            if($udata['cta'] == 1){
                                $xml .= ' closedToArrival="true"';
                            }else if($udata['cta'] == 0){
                                $xml .= ' closedToArrival="false"';
                            }
                            if($udata['minimum_stay'] != "" && $udata['minimum_stay'] != 0){
                                $xml .= ' minLOS="'.$udata['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                            }
                            $xml .= ' />';

                            $xml .= "</RatePlan>";
                        }else if($room_value->explevel == "room"){

                            if ($udata['stop_sell'] == 1 && $udata['open_room'] == 0) {
                               $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                            }else if ($udata['stop_sell'] == 0 && $udata['open_room'] == 1) {
                                $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                            }else if($udata['stop_sell'] == 1 && $udata['open_room'] == 1){
                                $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                            }else{
                                $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            }
                            
                            if($udata['availability'] != "" && $room_value->update_availability=='1'){
                                $xml .= '<Inventory totalInventoryAvailable="'.$udata['availability'].'"/>';
                            }

                            $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                            foreach($available_plans as $e_plan){
                                if($e_plan->rateAcquisitionType != "Linked"){

                                    if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                        $plan_id = $e_plan->rateplan_id;
                                    }else{
                                        $plan_id = $e_plan->rate_type_id;
                                    }
                                    $xml .= '<RatePlan id="'.$plan_id.'">';

                                    if($price != '' && $room_value->update_rate=='1'){
                                        if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                        <PerDay rate="'.$price.'"/>
                                                        </Rate>';
                                            }
                                        }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$price.'"/>
                                                    </Rate> ';
                                        }
										elseif($e_plan->pricingModel == 'OccupancyBasedPricing')
										{
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$price.'" occupancy = "2"/></Rate> ';
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
										}
                                    }

                                    $xml .= '<Restrictions';
                                    if($udata['ctd'] == 1){
                                        $xml .= ' closedToDeparture="true"';
                                    }else if($udata['ctd'] == 0){
                                        $xml .= ' closedToDeparture="false"';
                                    }
                                    if($udata['cta'] == 1){
                                        $xml .= ' closedToArrival="true"';
                                    }else if($udata['cta'] == 0){
                                        $xml .= ' closedToArrival="false"';
                                    }
                                    if($udata['minimum_stay'] != "" && $udata['minimum_stay'] != 0){
                                        $xml .= ' minLOS="'.$udata['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                    }
                                    $xml .= ' />';

                                    $xml .= "</RatePlan>";
                                }
                            }
                        }
                        $xml .= "</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
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
                        $data = simplexml_load_string($output); 
                        $response = $data->Error;
                        if($response!='')
                        {
                           // echo 'fail';
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$response,'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                            $expedia_update = "Failed";
                        }
                        else
                        {
                           // echo 'success   ';
                            $expedia_update = "Success";
                        }

                        curl_close($ch);
                    }
					else if($room_value->channel_id == 11)
					{

                        if($price!='' || $ch_update_avail!=''){

                            $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$udata['room_id']))->row();
                            if(count($room_details) != 0)
                            {
                                if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                            }else{
                                $meal_plan = 0;
                            }
                            $up_days =  explode(',',$udata['days']);
                             
                            if(in_array('1', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $monday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $monday_cta = '0';
                                }
                                $monday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $monday_cta ='1';
                                $monday_ss='0';
                            }
                            if(in_array('2', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $tuesday_cta = '0';
                                }else if($udata['cta'] == 0){
                                    $tuesday_cta = '1';
                                }
                                $tuesday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $tuesday_cta ='1';
                                $tuesday_ss='0';
                            }
                            if(in_array('3', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $wednesday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $wednesday_cta = '0';
                                }
                                
                                $wednesday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $wednesday_cta ='1';
                                $wednesday_ss='0';
                            }
                            if(in_array('4', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $thursday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $thursday_cta = '0';
                                }
                                
                                $thursday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $thursday_cta ='1';
                                $thursday_ss='0';
                            }
                            if(in_array('5', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $friday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $friday_cta = '0';
                                }
                                
                                $friday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $friday_cta ='1';
                                $friday_ss='0';
                            }
                            if(in_array('6', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $saturday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $saturday_cta = '0';
                                }
                                
                                $saturday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $saturday_cta ='1';
                                $saturday_ss='0';
                            }
                            if(in_array('7', $up_days)) 
                            {
                                if($udata['cta'] == 1){
                                    $sunday_cta = '1';
                                }else if($udata['cta'] == 0){
                                    $sunday_cta = '0';
                                }
                                $sunday_ss = $udata['stop_sell'];
                            }
                            else 
                            {
                                $sunday_cta ='1';
                                $sunday_ss='0';
                            }
                            $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$udata['individual_channel_id']))->row();
                            if($ch_details->mode == 0){
                                $urls = explode(',', $ch_details->test_url);
                                foreach($urls as $url){
                                    $path = explode("~",$url);
                                    $reco[$path[0]] = $path[1];
                                }
                            }else if($ch_details->mode == 1){
                                $urls = explode(',', $ch_details->live_url);
                                foreach($urls as $url){
                                    $path = explode("~",$url);
                                    $reco[$path[0]] = $path[1];
                                }
                            } 
                            $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                            if($price!='' && $room_value->update_rate=='1'){
                                $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                                if($mapping_values)
                                {
                                    $label=$mapping_values['label'];
                                    $val=$mapping_values['value'];  
                                    $label_split=explode(",",$label);
                                    $val_split=explode(",",$val);
                                    $set_arr=array_combine($label_split,$val_split);
                                    $i=0;
                                    $mapping_fields='';
                                    foreach($set_arr as $k=>$v)
                                    {
                                        if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
                                            if(strpos($v, '+') !== FALSE){
                                                $opr = explode('+', $v);
                                                if(is_numeric($opr[1])){
                                                    $ex_price = $price + $opr[1];
                                                }else if(is_numeric($opr[0])){
                                                    $ex_price = $price + $opr[0];
                                                }else{
                                                    if(strpos($opr[1], '%')){
                                                        $per = explode('%',$opr[1]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($price * $per[0]) / 100;
                                                            $ex_price = $price + $per_price;
                                                        }
                                                    }elseif (strpos($opr[0], '%')) {
                                                        $per = explode('%',$opr[0]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($price * $per[0]) / 100;
                                                            $ex_price = $price + $per_price;
                                                        }
                                                    }
                                                }
                                            }elseif (strpos($v, '-') !== FALSE) {
                                                $opr = explode('-', $v);
                                                if(is_numeric($opr[1])){
                                                    $ex_price = $price - $opr[1];
                                                }elseif (is_numeric($opr[0])) {
                                                    $ex_price = $price - $opr[0];
                                                }else{
                                                    if(strpos($opr[1],'%') !== FALSE){
                                                        $per = explode('%',$opr[1]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($price * $per[0]) / 100;
                                                            $ex_price = $price - $per_price;
                                                        }
                                                    }elseif (strpos($opr[0],'%') !== FALSE) {
                                                        $per = explode('%',$opr[0]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($price * $per[0]) / 100;
                                                            $ex_price = $price - $per_price;
                                                        }
                                                    }
                                                }
                                            }elseif (strpos($v, '%') !== FALSE) {
                                                $opr = explode('%', $v);
                                                if(is_numeric($opr[1])){
                                                    $per_price = ($price * $opr[1]) / 100;
                                                    $ex_price = $price + $per_price;
                                                }elseif (is_numeric($opr[0])) {
                                                    $per_price = ($price * $opr[0]) / 100;
                                                    $ex_price = $price + $per_price;
                                                }
                                            }else{
                                                $ex_price = $price + $v;
                                            }
                                            
                                            $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                        }else{
                                            $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                        }
                                    }
                                }
                                if($udata['minimum_stay']!='')
                                {
                                    $minimum_stay = $udata['minimum_stay'];
                                }
                                else
                                {
                                    $minimum_stay = 0;
                                }
                                
                                $url = trim($reco['urate_avail']);
                                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                <soap12:Body>
                                <UpdateRates xmlns="https://www.reconline.com/">
                                <User>'.$ch_details->user_name.'</User>
                                <Password>'.$ch_details->user_password.'</Password>
                                <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                <idSystem>0</idSystem>
                                <ForeignPropCode></ForeignPropCode>
                                <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                <ExcludeRateLevels></ExcludeRateLevels>
                                <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                <ExcludeRoomTypes></ExcludeRoomTypes>
                                <RateType>1</RateType>
                                <StartDate>'.$up_sart_date.'</StartDate>
                                <EndDate>'.$up_sart_date.'</EndDate>
                                <SingleOcc>'.$price.'</SingleOcc>'.
                                    $mapping_fields.'
                                <Meals>'.$meal_plan.'</Meals>
                                <MinStay>'.$minimum_stay.'</MinStay>
                                <BlockStay>0</BlockStay>
                                <Guarantee>0</Guarantee>
                                <Cancel></Cancel>
                                <CTAMonday>'.$monday_cta.'</CTAMonday>
                                <CTATuesday>'.$tuesday_cta.'</CTATuesday>
                                <CTAWednesday>'.$wednesday_cta.'</CTAWednesday>
                                <CTAThursday>'.$thursday_cta.'</CTAThursday>
                                <CTAFriday>'.$friday_cta.'</CTAFriday>
                                <CTASaturday>'.$saturday_cta.'</CTASaturday>
                                <CTASunday>'.$sunday_cta.'</CTASunday>
                                </UpdateRates>
                                </soap12:Body>
                                </soap12:Envelope>';
                                $headers = array(
                                                    "Content-type: application/soap+xml; charset=utf-8",
                                                    "Host:www.reconline.com",
                                                    "Content-length: ".strlen($xml_post_string),
                                                    ); 
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                $ss = curl_getinfo($ch);                
                                $response = curl_exec($ch);
                                $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                $xml = simplexml_load_string($xml);
                                $json = json_encode($xml);
                                $responseArray = json_decode($json,true);
                                $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                if(count($Errorarray)=='0' && count($soapFault)=='0')
                                {
                                    $reconline_price_response = "success";
                                }
                                else 
                                {
                                    $reconline_price_response = "error";
                                    if(count($Errorarray)!='0')
                                    {
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                    }
                                    else if(count($soapFault)!='0')
                                    {      
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                    }
                                }
                                curl_close($ch);
                            }
                            
                            if($ch_update_avail!='' && $room_value->update_availability=='1')
                            {
                                $url = trim($reco['urate_avail']);
                                $xml_post_string_update = '<?xml version="1.0" encoding="utf-8"?>
                                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                <soap12:Body>
                                <UpdateAvail xmlns="https://www.reconline.com/"> 
                                <User>'.$ch_details->user_name.'</User>
                                <Password>'.$ch_details->user_password.'</Password>
                                <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                <idSystem>0</idSystem>
                                <ForeignPropCode></ForeignPropCode>
                                <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                <ExcludeRateLevels></ExcludeRateLevels>
                                <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                <ExcludeRoomTypes></ExcludeRoomTypes>
                                <AvailMode>0</AvailMode>
                                <StartDate>'.$up_sart_date.'</StartDate>
                                <Availability>='.$ch_update_avail.'</Availability>
                                </UpdateAvail>
                                </soap12:Body>
                                </soap12:Envelope>';
                                $headers_avail = array(
                                                    "Content-type: application/soap+xml; charset=utf-8",
                                                    "Host:www.reconline.com",
                                                    "Content-length: ".strlen($xml_post_string_update),
                                                    );

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string_update);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_avail);
                                $ss = curl_getinfo($ch);                
                                $response = curl_exec($ch);
                                $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                $xml = simplexml_load_string($xml);
                                $json = json_encode($xml);
                                $responseArray = json_decode($json,true);
                                $Errorarray = @$responseArray['soapBody']['UpdateRatesResponse']['UpdateRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                                $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                                if(count($Errorarray)=='0' && count($soapFault)=='0')
                                {
                                    $reconline_availability_response="success";
                                }
                                else 
                                {
                                    $reconline_availability_response="error";
                                    if(count($Errorarray)!='0')
                                    {
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$Errorarray['WARNING'],'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$Errorarray['WARNING']);
                                    }
                                    else if(count($soapFault)!='0')
                                    {      
                                        $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$soapFault['soapText'],'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$soapFault['soapText']);
                                    }
                                    return false;
                                }
                                curl_close($ch);
                            }           
                        }
                                     
                    }
					else if($room_value->channel_id == 5)
					{
                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                        if($ch_details->mode == 0){
                            $urls = explode(',', $ch_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $htb[$path[0]] = $path[1];
                            }
                        }else if($ch_details->mode == 1){
                            $urls = explode(',', $ch_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $htb[$path[0]] = $path[1];
                            }
                        } 
                                        
                        $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                        $mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                        $maxnum = 99;
                        if($mapping_values){
                            if($mapping_values['label']== "MaximumNoOfDays")
                            {
                                $maxnum = $mapping_values['value'];                                        
                            }
                        }


                        $hotelbed_start = str_replace('-', '', $update_date);

                        $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventoryModification xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryModificationRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                            <Language>ENG</Language>
                            <Credentials>
                                <User>'.$ch_details->user_name.'</User>
                                <Password>'.$ch_details->user_password.'</Password>
                            </Credentials>
                            <Contract>
                                <Name>'.$mp_details->contract_name.'</Name>
                                <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                <Sequence>'.$mp_details->sequence.'</Sequence>
                            </Contract>
                            <InventoryItem>
                                <DateFrom date="'.$hotelbed_start.'"/>
                                <DateTo date="'.$hotelbed_start.'"/>';
                        if($udata['availability'] != "" && $room_value->update_availability=='1'){
						
                            if($udata['stop_sell'] == 0 && $udata['open_room'] == 1){
                                $xml_post_string .= '<Room available="'.$udata['availability'].'" quote="'.$udata['availability'].'" closed="N">';
                            }else if($udata['stop_sell'] == 1 && $udata['open_room'] == 0){
                                $xml_post_string .= '<Room available="'.$udata['availability'].'" quote="'.$udata['availability'].'" closed="Y">';
                            }else{
		                        $xml_post_string .= '<Room available="'.$udata['availability'].'" quote="'.$udata['availability'].'">';
                            }                                   
                        }else{
                            if($udata['stop_sell'] == 0 && $udata['open_room'] == 1){
                                $xml_post_string .= '<Room>';
                            }else if($udata['stop_sell'] == 1 && $udata['open_room'] == 0){
                                $xml_post_string .= '<Room closed="Y">';
                            }else{
                                $xml_post_string .= '<Room>';
                            }   
                        }
                        
                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                        if($price != "" && $room_value->update_rate == '1') {
                            $xml_post_string .= '<Price><Amount>'.$price.'</Amount></Price>';
                        }
                        $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                         </soapenv:Envelope>';
                        $headers = array(
                            "SOAPAction:no-action",
                            "Content-length: ".strlen($xml_post_string),
                        ); 
                        $url = trim($htb['urate_avail']);
						//echo $xml_post_string ; 
                        // PHP cURL  for https connection with auth
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $ss = curl_getinfo($ch);                
                        $response = curl_exec($ch);
						//echo $response;
                        //echo "<pre>";
                        //die;
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);
                        //print_r($responseArray);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                        //print_r($xml);
                        $status = $xml->ErrorList->Error;
                        if($xml->ErrorList->Error){
                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                        }else if($xml->Status != "Y"){
                             $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$xml->Status,'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Try Again");
                        }

                        if($udata['minimum_stay'] != ""){
                            $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                                <soapenv:Envelope
                                soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
                                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                                <getHSIContractDetailModification
                                xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                                <HSI_ContractDetailModificationRQ>
                                <Language>ENG</Language>
                                <Credentials>
                                <User>'.$ch_details->user_name.'</User>
                                <Password>'.$ch_details->user_password.'</Password>
                                </Credentials>
                                <Contract>
                                <Name>'.$mp_details->contract_name.'</Name>
                                <IncomingOffice code="'.$mp_details->contract_code.'"/>
                                <Sequence>'.$mp_details->sequence.'</Sequence>
                                </Contract>
                                <MinimumStayList>
                                <MinimumStay>
                                <DateFrom date="'.$hotelbed_start.'"/>
                                <DateTo date="'.$hotelbed_start.'"/>
                                <MinNumberOfDays>'.$udata['minimum_stay'].'</MinNumberOfDays>
                                <MaxNumberOfDays>'.$maxnum.'</MaxNumberOfDays>
                                <RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>
                                </MinimumStay>
                                </MinimumStayList>
                                </HSI_ContractDetailModificationRQ>
                                </getHSIContractDetailModification>
                                </soapenv:Body>
                                </soapenv:Envelope>';

                            $headers = array(
                            "SOAPAction:no-action",
                            "Content-length: ".strlen($xml_post_string),
                            ); 
                            $url = trim($htb['urate_avail']);

                            // PHP cURL  for https connection with auth
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_USERPWD, $ch_details->user_name.":".$ch_details->user_password); // username and password - declared at the top of the doc
                            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                            curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            $ss = curl_getinfo($ch);                
                            $response = curl_exec($ch);
                            //echo $response;
                            
                            $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                            $xml_parse = simplexml_load_string($xmlreplace);
                            $json = json_encode($xml_parse);
                            $responseArray = json_decode($json,true);

                            $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractDetailModification']);
                            //print_r($xml);  
                            $status = $xml->ErrorList->Error;
                            if($xml->ErrorList->Error){
                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$status->DetailedMessage,'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                            }else if($xml->Status != "Y"){
                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,$xml->Status,'Channel Calendar Update',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error', "Try Again");
                            }
                        }


                    }
					else if($room_value->channel_id == 8)
					{
                        $up_days =  explode(',',$udata['days']);
                        $dayval = "";
                        if(in_array('1', $up_days)) 
                        {
                            $dayval .= "1";
                        }
                        else 
                        {
                            $dayval .= '0';
                        }
                        if(in_array('2', $up_days)) 
                        {
                            $dayval .= '1'; 
                        }
                        else 
                        {
                            $dayval .= '0';
                        }
                        if(in_array('3', $up_days)) 
                        {
                            $dayval .= '1';
                        }
                        else 
                        {
                            $dayval .= '0';
                        }
                        if(in_array('4', $up_days)) 
                        {
                            $dayval .= '1';
                        }
                        else 
                        {
                            $dayval .= '0';
                        }
                        if(in_array('5', $up_days)) 
                        {
                            $dayval = '1';
                        }
                        else 
                        {
                            $dayval = '0';
                        }
                        if(in_array('6', $up_days)) 
                        {
                            $dayval = '1';
                        }
                        else 
                        {
                            $dayval = '0';
                        }
                        if(in_array('7', $up_days)) 
                        {
                            $dayval = '1';
                        }
                        else 
                        {
                            $dayval = '0';
                        }
                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                        if($ch_details->mode == 0){
                            $urls = explode(',', $ch_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $gta[$path[0]] = $path[1];
                            }
                        }else if($ch_details->mode == 1){
                            $urls = explode(',', $ch_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $gta[$path[0]] = $path[1];
                            }
                        } 
                        $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();                                          
                        $room_id=$mp_details->ID;
                        $rateplanid=$mp_details->rateplan_id;
                        $MinPax=$mp_details->MinPax;
                        $peakrate=$mp_details->peakrate;
                        $MaxOccupancy=$mp_details->MaxOccupancy;
                        $minnights=$mp_details->minnights;
                        $payfullperiod=$mp_details->payfullperiod;
                        
                        if($udata['minimum_stay']!='')
                        {
                            $minnights = $udata['minimum_stay'];
                        }                            
                        $contract_id=$mp_details->contract_id;
                        $contract_type = $mp_details->contract_type;  
                        $hotel_channel_id=$mp_details->hotel_channel_id;
                        if(isset($price) &&   $price!=0 && !$price!=0 && $room_value->update_rate == '1'){
                            if($contract_type == "Static"){

                                $pri=$price;     
                                $soapUrl = trim($gta['urate_s']);
                                $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                    GTA_RateCreateRQ.xsd">
                                      <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                    <RatePlan Id="'.$rateplanid.'">
                                    <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                    DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                    PeakRate="'.$peakrate.'">
                                    <StaticRoomRate RoomId="'.$room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$pri.'" />
                                    </StaticRate>
                                    </RatePlan>
                                    </GTA_StaticRatesCreateRQ>';   

                                $ch = curl_init($soapUrl);

                                    //curl_setopt($ch, CURLOPT_MUTE, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $output = curl_exec($ch);
                                      curl_close($ch);  
                                $data = simplexml_load_string($output);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                                }
                            }else if($contract_type == "Margin"){

                                $soapUrl = trim($gta['urate_m']);

                                $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'"   FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                $ch = curl_init($soapUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                $response = curl_exec($ch);
                                $data = simplexml_load_string($response);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                }         
                            }
                        }
                        if($udata['availability']!=0 && $room_value->update_availability == '1'){ 
                            $soapUrl=trim($gta['uavail']);
                            $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" ><RoomStyle>';
                            $xml_post_string.=' <StayDate Date = "'.$update_date .'">
                                <Inventory RoomId="'.$room_id.'" ><Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$udata['availability'].'" ReleaseDays="0"/></Inventory> </StayDate>';
                            $xml_post_string.='</RoomStyle></InventoryBlock>
                            </GTA_InventoryUpdateRQ>';

                            $ch = curl_init($soapUrl);

                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                            $response = curl_exec($ch); 
                            $data = simplexml_load_string($response);
                            $Error_Array = @$data->Errors->Error;
                            if($Error_Array!='')
                            {
                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                            }
                        }
                        if($room_value->update_availability == '1'){
                            $soapUrl=trim($gta['uavail']);
                            $xml_post_string='<GTA_InventoryUpdateRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQhelp.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                <InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                            $xml_post_string.=' <StayDate Date = "'.$update_date.'"><Inventory RoomId = "'.$room_id.'">';
                            if($udata['stop_sell'] == 0 && $udata['open_room'] == 1 && $udata['availability'] != ""){ 
                                $xml_post_string .= '<Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$udata['availability'].'" ReleaseDays="0"/>';
                                $xml_post_string.= '<Restriction FlexibleStopSell = "false" InventoryType = "Flexible"/>';
                            }else if($udata['stop_sell'] == 0 && $udata['open_room'] == 1 && $udata['availability'] == ""){
                                $xml_post_string .= '<Detail FreeSale="false" InventoryType="Flexible" Quantity="5" ReleaseDays="0"/>';
                                $xml_post_string.= '<Restriction FlexibleStopSell = "false" InventoryType = "Flexible"/>';
                            }
                            if($udata['stop_sell'] == 1 && $udata['open_room'] == 0){ 
                                $xml_post_string.= '<Restriction FlexibleStopSell = "true" InventoryType = "Flexible"/>';
                            }
                            if($udata['stop_sell'] == 1 && $udata['open_room'] == 1){
                                $xml_post_string.= '<Restriction FlexibleStopSell = "true" InventoryType = "Flexible"/>';
                            }
                            $xml_post_string.=' </Inventory></StayDate> ';
                            $xml_post_string.=' </RoomStyle></InventoryBlock></GTA_InventoryUpdateRQ>';
                            $ch = curl_init($soapUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                            $response = curl_exec($ch); 
                            $data = simplexml_load_string($response);
                            $Error_Array = @$data->Errors->Error;
                            if($Error_Array!='')
                            {
                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Channel Update',date('m/d/Y h:i:s a', time()));
                            }
                        }
                    }
                    else if($room_value->channel_id == 2)
					{
                        $chdata['channel_id'] =$room_value->channel_id;
                        $this->load->model("booking_model");
                        $this->booking_model->full_calendar_update($chdata,$udata,$update_date,$price,$room_value->import_mapping_id,$room_value->mapping_id);
                    }
                }
            }

        }
    }
    /* --- End Of Channel Calendar Update --- */

    function store_error($owner_id='',$hotel_id='',$channel_id,$message,$error_occurs,$datetime)
	{
		if($owner_id == ""){
            $owner_id = current_user_type();
        }
        if($hotel_id == ""){
            $hotel_id = hotel_id();
        }
        $data['user_id'] =$owner_id;
        $data['hotel_id'] = $hotel_id;
        $data['channel_id'] = $channel_id;
        $data['error_message'] = (string)$message;
        $data['error_date_time'] = $datetime;
        $data['error_occurs'] = $error_occurs;

        $this->db->insert("channel_error",$data);
    }

    function getDateForSpecificDayBetweenDates($start, $end, $weekday)
    {
        if($weekday != ""){
            $weekdays="Day,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
            $arr_weekdays=explode(",", $weekdays);
            $string = "";
            $arr_weekdays_day = explode(",", $weekday);
            $i = 1;
            foreach($arr_weekdays_day as $weekdays)
            {
                $weekday = @$arr_weekdays[$weekdays];
                if(!$weekday)
                $this->inventory_model->store_error(current_user_type(),hotel_id(),"2",'Invalid WeekDay','Bulk Update',date('m/d/Y h:i:s a', time()));
                $starts= strtotime("+0 day", strtotime($start) );
                $ends= strtotime($end);
                //$dateArr = array();
                $friday = strtotime($weekday, $starts);
                while($friday <= $ends)
                {
                    $dateArr[] = date("d/m/Y", $friday);
                    $date = date("Y-m-d", $friday);
                    $string .= "value".$i."='".$date."' ";
                    $friday = strtotime("+1 weeks", $friday);
                    $i++;
                }
                //$dateArr[] = date("Y-m-d", $friday);
            }
            //print_r($dateArr);
            return $dateArr;
        }
    }
	
	function test_model_function()
	{
		echo ' currency_code '.$this->currency_code;
	}

    
    // 10/12/2015....end
}  // end class
