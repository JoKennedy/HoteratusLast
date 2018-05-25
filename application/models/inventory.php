<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends Front_Controller {
    
	private $currency_code; 
	 
    public function __construct()
    {
        
        parent::__construct();

        //load base libraries, helpers and models
        $this->load->database();

        //if SSL is enabled in config force it here.
       /* if (config_item('ssl_support') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'))
        {
            $CI =& get_instance();
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            redirect($CI->uri->uri_string());
        }*/
		
		if(current_user_type())
		{
			$hotel_detail			=	get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency;
			
			$this->currency_code	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->currency_code;
		}
		
    }
    function is_login()
    {
        if(!user_id())
        redirect(base_url());
        return;
    }
    function is_admin()
    {   
        if(!admin_id()) 
        redirect(base_url());
        return;
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
    public function index()
    {
        if(!user_id())
        {
        $data['page_heading'] = 'Home';
        $this->view('channel/index',$data);
        }
        else
        {
            redirect('inventory/inventory_dashboard','refresh');
        }
    }
    /* function inventory_dashboard()
    {
        $this->is_login();
        $data['page_heading'] = 'Manage Inventory';
        $data['inventory'] = 'inventory';
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($data,$user_details);
        $this->views('channel/inventory',$data);
    } */
    
    function get_channel_room($pro_id)
    {

            $get_channel_id = explode(',',get_data(TBL_PROPERTY,array('property_id'=>$pro_id))->row()->connected_channel);
            $get_channel = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
            if(count($get_channel_id)!='')
            {
                foreach($get_channel as $channel)
                {
                    extract($channel);
                    if(in_array($channel_id,$get_channel_id))
                    {
        ?>
      <div class="checkbox">
         <label>    <input type="checkbox" checked="checked" class="channel_single" name="channel_single[]" value="<?php echo $channel_id; ?>"> <?php echo $channel_name?>  </label>
      </div>
      
        <?php       }
                }
            }
            else
            {
          ?>
          <div class="checkbox text-danger">
         <label> No connected channel in active </label>
      </div>
          <?php 
            }
    }
    
    function single_room()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        /*echo '<pre>';
        print_r($this->input->post());
        exit;*/
        extract($this->input->post());
        if(isset($channel_all)!='')
        {
            $data['all'] = $this->session->set_userdata('channel_all',$channel_all);
        }
        else
        {
            $channel_all='';
            $data['all'] = $this->session->set_userdata('channel_all',$channel_all);
        }
        if(isset($channel_single)!='')
        {       
            $data['single'] = $this->session->set_userdata('channel_single',$channel_single);
        }
        else
        {
            $channel_single=array();
            $data['single'] = $this->session->set_userdata('channel_single',$channel_single);
        }
        if(isset($property_single)!='')
        {
            $data['property_single'] = $this->session->set_userdata('property_single',$property_single);
        }
        else
        {
            $property_single='';
            $data['property_single'] = $this->session->set_userdata('property_single',$property_single);
        }
        
        redirect('inventory/single_update','refresh');
        
        
    }
    
    function single_update($mode='',$id='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        switch ($mode)
        {
            
            case 'update':
            extract($this->input->post());
            $available = get_data(TBL_UPDATE,array('room_id'=>($up_property)))->row_array();
            if(count($available)==0)
            {
                $idata['room_id']    = $up_property;
                $data['property_single'] = $this->session->set_userdata('property_single',$up_property);
                if(isset($up_channel)!='')
                {
                    $idata['channel_id'] = implode(',',$up_channel);
                    $data['single'] = $this->session->set_userdata('channel_single',$up_channel);
                }
                else
                {
                    $idata['channel_id'] = '';
                    $up_channel='';
                    $data['single'] = $this->session->set_userdata('channel_single',$up_channel);
                }
                $idata['start_date'] = $start_date;
                $idata['end_date']   = $end_date;
                if($availability=='')
                {
                    $idata['availability'] = 1;
                }
                else
                {
                    $idata['availability'] = $availability;
                }
                if($price=='')
                {
                    $idata['price']      = 100;
                }
                else
                {
                    $idata['price']      = $price;
                }
                $idata['minimum_stay'] = $minimum_stay;
                $idata['stop_sell']    = $stop_sell;
                $idata['cta']          = $cta;
                $idata['ctd']          = $ctd;
                if(isset($days)!='')
                {
                    $idata['days']         = implode(',',$days);
                }
                else
                {
                    $idata['days']         = '1,2,3,4,5,6,7';
                }
                if(insert_data(TBL_UPDATE,$idata))
                {
                    redirect('inventory/single_update','refresh');
                }
            }
            else
            {
                $idata['room_id']    = $up_property;
                $this->session->set_userdata('property_single',$up_property);
                if(isset($up_channel)!='')
                {
                    $idata['channel_id'] = implode(',',$up_channel);
                    $this->session->set_userdata('channel_single',$up_channel);
                }
                else
                {
                    $idata['channel_id'] = '';
                    $up_channel='';
                    $this->session->set_userdata('channel_single',$up_channel);
                }
                $idata['start_date'] = $start_date;
                $idata['end_date']   = $end_date;
                if($availability=='')
                {
                    $idata['availability'] = $available['availability'];
                }
                else
                {
                    $idata['availability'] = $availability;
                }
                if($price=='')
                {
                    $idata['price']      = $available['availability'];
                }
                else
                {
                    $idata['price']      = $price;
                }
                $idata['minimum_stay'] = $minimum_stay;
                $idata['stop_sell']    = $stop_sell;
                $idata['cta']   = $cta;
                $idata['ctd']   = $ctd;
                if(isset($days)!='')
                {
                    $idata['days']         = implode(',',$days);
                }
                else
                {
                    $idata['days']         = '1,2,3,4,5,6,7';
                }
                if(update_data(TBL_UPDATE,$idata,array('room_id'=>($up_property))))
                {
                    redirect('inventory/single_update','refresh');
                }
            }
            
            break;
            default:
            $data['all'] = $this->session->userdata('channel_all');
            $data['single'] = $this->session->userdata('channel_single');
            $data['property_single'] = $this->session->userdata('property_single');
            $data['page_heading'] = 'Single Update';
            $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
            $data= array_merge($data,$user_details);
            $this->views('channel/'.uri(3),$data);
            break;
        }
    }

    function bulk_update($mode='',$id='')
    {
	    if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        switch($mode)
        {
            case 'update':
            
            if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
            {

                $rooms  = $this->input->post('room');
                $array = array();
				$array = $this->inventory_model->cleanArray($rooms);

                $rates  = $this->input->post('rate');
                $arrayrate = array();
                $arrayrate = $this->inventory_model->cleanArray($rates);
                


                if(is_array($array) && count($array) != 0)
                {

                    foreach($array as $id=>$room)
                    {
                        foreach($room as $key=>$value){
                            if (!in_array($key, $this->input->post('updatevalue'))){
                                unset($room[$key]);
                            }
                        }

                        $room['room_id']    = $id;
                        $room['start_date'] = $this->input->post('start_date');
                        $room['end_date'] = $this->input->post('end_date');

                        if(isset($room['availability']) ){
                           
                            if($room['availability']==0)
                            {
                               $room['stop_sell'] = 1; 
                            }
                            
                        }

                        if($this->input->post('days')!='')
                        {
                            $room['days']     = implode(',',$this->input->post('days'));
                        }
                        else
                        {
                            $room['days']     = ('1,2,3,4,5,6,7');
                        }


                        if($this->input->post('channel_id')!="")
                        {
                            $room['channel_id'] = implode(',',$this->input->post('channel_id'));
                        }

	                    $this->inventory_model->save($room);
                    }
                }


               
                if(is_array($arrayrate) && count($arrayrate) != 0)
                {

                    foreach($arrayrate as $id=>$room)
                    {   
                        
                        foreach ($room as $rateid=>$rate) 
                        {
                            foreach($rate as $key=>$value)
                            {
                                if (!in_array($key, $this->input->post('updatevalue')))
                                {
                                    unset($rate[$key]);
                                }
                            }

                            $rate['room_id']    = $id;
                            $rate['rate_id']    = $rateid;
                            $rate['start_date'] = $this->input->post('start_date');
                            $rate['end_date'] = $this->input->post('end_date');



                             if(isset($rate['availability']) ){
                                if($rate['availability']==0)
                                {
                                   $rate['stop_sell'] = 1; 
                            }
                            
                        }
                            if($this->input->post('days')!='')
                            {
                                $rate['days']     = implode(',',$this->input->post('days'));
                            }
                            else
                            {
                                $rate['days']     = ('1,2,3,4,5,6,7');
                            }


                            if($this->input->post('channel_id')!="")
                            {
                                $rate['channel_id'] = implode(',',$this->input->post('channel_id'));
                            }

                           $this->inventory_model->ratesave($rate);

                        }
                        
                    }
                }


               
			//	$this->inventory_model->subsave();
    
                
			/*	               
                $this->inventory_model->subratesave();
               
                if($this->input->post('channel_id')!='')                       
                {
                    foreach ($this->input->post('channel_id') as $channel) {
                        if($channel == 1 && $rooms !=''){
                           
                            foreach($rooms as $id => $room){

                                $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel,'property_id'=> $id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                                foreach($room_mapping as $room_value){
                                    $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                    $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'rateType' => 'SellRate'))->row();
									if($rt_details)
									{
                                    if((string)$rt_details->minAmount != "0.00" && (string)$rt_details->maxAmount != "0.00"){
                                        if(@$room['price']!='' && $room_value->update_rate=='1'){
                                            if(@$room['price'] <= (string)$rt_details->minAmount || @$room['price'] >= (string)$rt_details->maxAmount){
                                                $price_error = "Expedia Price must be between ".$rt_details->minAmount." and ".$rt_details->maxAmount;
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

                if(isset($price_error)){
                    $this->session->set_flashdata("bulk_error",$price_error);
                }else{
                    if($this->session->flashdata('bulk_error')=='')
                    {
                        $this->session->set_flashdata('bulk_success','Bulk update has been updated successfully!!!');
                    }
                }
		
                if($this->input->post('redirect_url')=='')
                {
                    redirect('inventory/bulk_update','refresh');
                }
                else if($this->input->post('redirect_url')=='reservation_yes')
                {
                    redirect('inventory/advance_update','refresh');
                }
                else if($this->input->post('redirect_url')=='reservation_no')
                {
                    redirect('inventory/advance_update/reservation_no','refresh');
                }
            }
            else 
            {
                redirect(base_url());
            }
            break;

            default:
                if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
                {
                    $data['page_heading'] = 'Bulk Update';
                    $user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
                    $data= array_merge($data,$user_details);
                    $this->db->order_by('property_id','desc');
                    $data['bulk_room'] = get_data(TBL_PROPERTY,array('status'=>'Active','owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->result_array();
                    $data['con_cha'] = $this->channel_model->user_channel_hotel();
                    $data['redirect_url'] = $mode;
                    $data['hotel_detail'] = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()),'currency')->row_array();;
                    $this->views('channel/'.uri(3),$data);
                }
                else 
                {
                    redirect(base_url());
                }
                break;
        }
    }
    
    function saveuserconfig()
    {
        extract($this->input->post());

        $available1= get_data('ConfigUsers',array('userid'=>current_user_type(),'Hotelid'=>hotel_id()))->row_array();
        $dato['CalenderShowR'] = $valor;
        
        if(count($available1)!=0)
        {
            $this->db->where('userid',current_user_type());
            $this->db->where('Hotelid',hotel_id());
            $this->db->update('ConfigUsers', $dato);
        }
        else
        {   $dato['userid'] = current_user_type();
             $dato['Hotelid'] = hotel_id();
            $this->db->insert('ConfigUsers', $dato);
        }
        

         
    }
    function main_full_update()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
       $main_full_update = $this->inventory_model->main_full_update();
      /* if($main_full_update)
        {

           $meg['result'] = '1';
          $meg['content']='Refresh all channels has beed update successfully!!!';
          echo json_encode($meg);
        }
        else
        {
         $meg['result'] = '0';
          $meg['content']='Refresh all channels error doing. Please try again!!!';
          echo json_encode($meg);  
        }*/
    }
    function main_full_update_modal()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $data['mapped_rooms']=$this->channel_model->user_channel_hotel();
        $this->load->view('channel/main_full_update',$data);
    }    
    function reservation_update_no()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
		{
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
        {
            extract($this->input->post());
            $this->inventory_model->reservation_update_no();
            redirect('inventory/advance_update/reservation_no','refresh');
        }
		else
		{
		   redirect(base_url());
		}
        /* echo '<pre>';
        print_r($this->input->post());
        foreach($room as $id=>$rooms)
        {
            echo 'room_id ='.$id.'<br>';
            foreach($rooms as $key=>$date_val)
            {
                echo 'Column ='.$key.'<br>';
                foreach($date_val as $date_key=>$date_value)
                {
                    echo 'date = '.$date_key.'<br>';
                    echo 'value = '.$date_value.'<br>';
                }
            }
        } */
        
    }
    
    function room_types($mode='',$id='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
		{
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
		{
			switch($mode)
			{
				case 'view':
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1' )
				{
					$available = get_data(TBL_PROPERTY,array('property_id'=>(insep_decode($id)),'owner_id'=>user_id(),'hotel_id'=>hotel_id()))->row_array();
				}
				else if(user_type()=='2')
				{
					$available = get_data(TBL_PROPERTY,array('property_id'=>(insep_decode($id)),'owner_id'=>owner_id(),'hotel_id'=>hotel_id()))->row_array();
				}
				if(count($available)!=0)
				{
					$data['count_room_photos'] = $this->inventory_model->count_room_photos($id);
					$data['page_heading'] = 'Room Type';
					$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
					$data= array_merge($data,$user_details);
					$data= array_merge($data,$available);
					if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
					{
						$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'status'=>'Active'))->result_array();
					}
					else if(user_type()=='2')
					{
						$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'status'=>'Active'))->result_array();
					}
					$data['inactive'] = array();
					$data['sub_users'] = array();
					$data['credit_card'] = array();
					$this->reservation_model->room_ICAL($id);
					$this->views('channel/'.uri(3),$data);
				}
				else
				{
					redirect('channel/manage_rooms','refresh');
				}
				break;
			}
		}
    }
    
    function update_amenities()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $udata['amenities']       = implode(',',$update_amenities);
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        $room_amenities = update_data(TBL_PROPERTY,$udata,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'property_id'=>$property_id));
        }
        else if(user_type()=='2' || admin_id()!='' && admin_type()=='1'){
        $room_amenities = update_data(TBL_PROPERTY,$udata,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'property_id'=>$property_id));    
        }
        if($room_amenities)
        {
            echo '0';   
        }
        else
        {
            echo '1';
        }
        
        
    }
    
    function rate_management()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
       
       /*  if(get_data(HOTEL,array('owner_id'=>user_id(),'hotel_id'=>hotel_id()))->row()->subscribe_status==1)
        {
            $data['page_heading'] = 'Rate Management';
            $data= array_merge($data,$user_details);
            redirect('channel/manage_rooms','refresh');
        }
        else
        {  */  
            if($this->input->post('ical_plan')){
                $planid = $this->input->post('ical_plan');
                if(!check_plan($planid,'check'))
                {
                    redirect(base_url());
                }
                $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                if($planid!='')
                {
                    $data['pay_type']       = insep_encode($planid);
                    $data['page_heading']   = 'Billing';
                    
                    $subscribe_plan = check_plan($planid,'get');//get_data(TBL_PLAN,array('plan_id'=>$this->session->userdata('pay_type')))->row_array();
                    
                    $currency_tbl = get_data(TBL_CUR,array('currency_id'=>$subscribe_plan['currency']))->row_array();
                    
                    $data = array_merge($data,$user_details);
                    $data = array_merge($data,$subscribe_plan);
                    $data = array_merge($data,$currency_tbl);
                    
                    $data['bill'] = $this->reservation_model->billing_details();
                    
                    $data['card_count'] = $this->db->select('id ')->from(TBL_CREDIT)->
                             where(array('user_id'=>user_id()))->count_all_results();
                    $data['subscribe_channel_id'] = 0;
                    if($data['card_count']!=0)
                    {
                        $data['cards'] = get_data(TBL_CREDIT,array('user_id'=>user_id()))->result_array();
                    }
                    $this->views('channel/billing',$data);
                }
                else
                {
                    redirect('inventory/rate_management','refresh');
                }
            }elseif($this->input->post('check_plan')=='')
            {
                $data['page_heading'] = 'Subscribe';
                $data= array_merge($data,$user_details);
				if(user_membership(1)==0)
				{
					if(user_membership(2)==0)
					{
						$data['plan_details'] = "NO";
					}
					elseif(user_membership(2)!=0)
					{
						$data['plan_details'] = user_membership(3);
					}
				}
				else
				{
					$data['plan_details'] = user_membership(4);
				}
				$data['user_subscribe_pladn'] = user_membership(5);
                $this->views('channel/subscribe',$data);
            }
            else
            {
                extract($this->input->post());
				/* echo '<pre>';
				print_r($this->input->post()); */
				$data['subscribe_channel_id'] = implode(',',$subscribe_channel_id);
				//echo $subscribe_channel_ids = insep_encode($subscribe_channel_id).'<br>'; 
				//echo insep_decode($subscribe_channel_ids);
				/* if(user_membership(5)!=0)
				{
					redirect('inventory/rate_management','refresh');
				} */
				if(!check_plan(insep_decode($check_plan),'check'))
				{
					redirect(base_url());
				}
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				if($check_plan!='')
				{
					$data['pay_type'] 		= $check_plan;
					$data['page_heading'] 	= 'Billing';
					
					$subscribe_plan = check_plan(insep_decode($check_plan),'get');//get_data(TBL_PLAN,array('plan_id'=>$this->session->userdata('pay_type')))->row_array();
					
					$currency_tbl = get_data(TBL_CUR,array('currency_id'=>$subscribe_plan['currency']))->row_array();
					
					$data = array_merge($data,$user_details);
					$data = array_merge($data,$subscribe_plan);
					$data = array_merge($data,$currency_tbl);
					
					$data['bill'] = $this->reservation_model->billing_details();
					
					$data['card_count'] = $this->db->select('id ')->from(TBL_CREDIT)->
							 where(array('user_id'=>current_user_type()))->count_all_results();
					
					if($data['card_count']!=0)
					{
						$data['cards'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
					}
					$this->views('channel/billing',$data);
				}
				else
				{
					redirect('inventory/rate_management','refresh');
				}
				
				/* die;

                //$this->session->set_userdata('pay_type',$plan);
                redirect('inventory/rate_management_billing/'.$check_plan,'refresh'); */
            }
        /* } */
    }
    
    
    function rate_management_billing($check_plan)
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(!check_plan(insep_decode($check_plan),'check'))
		{
			redirect(base_url());
		}
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        if($check_plan!='')
        {
            $data['pay_type'] 		= $check_plan;
            $data['page_heading'] 	= 'Billing';
            
            $subscribe_plan = check_plan(insep_decode($check_plan),'get');//get_data(TBL_PLAN,array('plan_id'=>$this->session->userdata('pay_type')))->row_array();
            
            $currency_tbl = get_data(TBL_CUR,array('currency_id'=>$subscribe_plan['currency']))->row_array();
            
            $data = array_merge($data,$user_details);
            $data = array_merge($data,$subscribe_plan);
            $data = array_merge($data,$currency_tbl);
			
			$data['bill'] = $this->reservation_model->billing_details();
            
            $data['card_count'] = $this->db->select('id ')->from(TBL_CREDIT)->
                     where(array('user_id'=>current_ruser_type()))->count_all_results();
            
            if($data['card_count']!=0)
            {
                $data['cards'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
            }

            $this->views('channel/billing',$data);
        }
        else
        {
            redirect('inventory/rate_management','refresh');
        }
    }
	// 07/06/2016 vijikumar modified
    function Buy_Now($payment_id='')
    {
		
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
       
        if($subscribe_plan_id!='' && $subscribe_channel_id!='')
        {
            extract($this->input->post());
            
           /*  echo '<pre>';
            
            print_r($this->input->post());
            
            exit; */
       
        
            $rdata = array(
                
            'user_id'=>current_user_type(),
            
            'hotel_id'=>hotel_id(),

            'email_address'=>$this->input->post('email_address'),
            
            'town'=>$this->input->post('town'),

                    'company_name'=>$this->input->post('property_name'),

            'mobile'=>$this->input->post('mobile'),
            
            'vat'=>$vat,
            
            'reg_num'=>$reg_num,
            
            'zip_code'=>$zip_code,
            
            'address'=>$address

        	);
        		$avilable = get_data(BILL,array("user_id"=>current_user_type(),"hotel_id"=>hotel_id()))->row_array();
				if(count($avilable)!=0)
				{
            		update_data(BILL,$rdata,array('user_id'=>current_user_type()));
				}
				else{
					insert_data(BILL,$rdata);
				}
        
            if($this->input->post('free_subscribe')=='')
            {
				$channels 					= get_data(TBL_CHANNEL,array('status'=>'Active'),'channel_id')->result_array();
				$channel_id                 = array_column($channels, 'channel_id');
                if($subscribe_channel_id != 0){
                    $check_channels             = explode(',',$subscribe_channel_id);  
                    $result                     = array_intersect($check_channels,$channel_id);
                    $connect_channels           = implode(',',$result);
                    if(!$connect_channels)
                    {
                        redirect(base_url());
                    }
                }else{
                    $connect_channels = 0;
                }
                if($this->input->post('pay_paypal')!='')
                {
                    $this->load->library('paypal_class');
					
                    $admin_setting = get_data(ADMIN)->row();
					
                    $payment_details = get_data(TBL_PLAN,array('plan_id'=>insep_decode($subscribe_plan_id)))->row();
                    
					if($admin_setting->paypalmode =='0')
                    {
                        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                    }
                    else
                    {
                        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';

                    }
					
					$payment_id = $subscribe_plan_id
					
					?>
					<form action="<?php echo $paypal_url; ?>" method="post" name="frmPayPal1">
					<input type="hidden" name="business" value="<?php echo $admin_setting->paypal_emailid; ?>">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="rm" value="2">
					<input type="hidden" name="item_name" value="<?php echo 'Buy '.$payment_details->plan_name.' Package';?>">
					<input type="hidden" name="item_number" value="1">
					<input type="hidden" name="amount" value="<?php echo $payment_details->plan_price; ?>">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="handling" value="0">
					<input type="hidden" name="cancel_return" value="<?php echo lang_url().'user/Payment_Cancel';?>">
					<input type="hidden" name="return" value="<?php echo lang_url().'inventory/Payment_Success/'.$payment_id; ?>">
					<input type="hidden" name="custom" value="<?php echo $connect_channels; ?>">
					<input type="submit" id="pay_now" style="display:none;">
					</form> 
					<center>
					<div id="preloader" style="display:block !important;">
					  Loading Please wait....
					</div>
					<center>
					<script>
						document.getElementById("pay_now").click();
					</script>
					<?php
                   /*  if($admin_setting->paypalmode =='0')
                    {
                        $this->paypal_class->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                    }
                    else
                    {
                        $this->paypal_class->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';

                    }
                    $payment_id = $subscribe_plan_id;
					$this->paypal_class->add_field('custom', $connect_channels);
                    $this->paypal_class->add_field('currency_code', 'USD');
                    $this->paypal_class->add_field('business', $admin_setting->paypal_emailid);
                    $this->paypal_class->add_field('return', base_url().'inventory/Payment_Success/'.$payment_id); // return url
                    $this->paypal_class->add_field('cancel_return', base_url().'user/Payment_Cancel'); // cancel url
                    $this->paypal_class->add_field('notify_url', base_url().'index.php/validate/validatePaypal'); // notify url
                    $this->paypal_class->add_field('item_name', 'Buy '.$payment_details->plan_name.' Package');
                    $this->paypal_class->add_field('amount',$payment_details->plan_price);
                    $this->paypal_class->add_field('quantity', 1);
                    $this->paypal_class->submit_paypal_post(); */
                }
                else
                {
					//$use_existing_card = "false";
                    if($this->input->post('use_existing_card')=='true')
                    {
                        $user_details 		= get_data(TBL_USERS,array('user_id'=>user_id()))->row();
                        
                        $card_details 		= get_data(TBL_CREDIT,array('id'=>$existing_card))->row();
                        
                        $payment_details 	= get_data(TBL_PLAN,array('plan_id'=>insep_decode($subscribe_plan_id)))->row();
                        
                        $state_s    =   $user_details->town;

                        $country_c  =   get_data(TBL_COUNTRY,array('id'=>$user_details->country))->row()->country_name;
            
                        $ucity      =   $user_details->address;
            
                        $address    =   $user_details->address;
            
                        $ph         =   $user_details->mobile;
            
                        $usermail   =   $user_details->email_address;
            
                        $username   =   (string)safe_b64decode($card_details->c_fname);
                        
                        $last_name 	= 	(string)safe_b64decode($card_details->c_lname);
            
                        $paymentmethod      =   (string)safe_b64decode($card_details->card_type);
            
                        $card_code          =   (string)safe_b64decode($card_details->cvv);
            
                        $card_number        =   (string)safe_b64decode($card_details->card_number);
            
                        $exp_month          =   (string)safe_b64decode($card_details->exp_month);
            
                        $exp_year           =   (string)safe_b64decode($card_details->exp_year);
            
                        $price              =   $payment_details->plan_price;
            
                        //$txn_id               =   $this->input->post('txn_id');
                    }
                    elseif($this->input->post('use_existing_card')=='false' || $this->input->post('use_existing_card')=='')
                    {
                        $cdata['user_id']       = current_user_type();
                        $cdata['c_fname']       = (string)safe_b64encode($c_fname);
                        $cdata['c_lname']       = (string)safe_b64encode($c_lname);
                        $cdata['card_number']   = (string)safe_b64encode($card_number);
                        $cdata['exp_month']     = (string)safe_b64encode($month);
                        $cdata['exp_year']      = (string)safe_b64encode($year);
                        $cdata['cvv']           = (string)safe_b64encode($cvv);
                        $cdata['card_type']     = (string)safe_b64encode('mastercard');//$payment_card;
                        
                        if(insert_data(TBL_CREDIT,$cdata))
                        {
                            $existing_card_id 	= $this->db->insert_id();
                            
                            $user_details 		= get_data(TBL_USERS,array('user_id'=>user_id()))->row();
                        
                            $card_details 		= get_data(TBL_CREDIT,array('id'=>$existing_card_id))->row();
                            
                            $payment_details 	= get_data(TBL_PLAN,array('plan_id'=>insep_decode($subscribe_plan_id)))->row();
                            
                            $state_s    =   $user_details->town;
    
                            $country_c  =   get_data(TBL_COUNTRY,array('id'=>$user_details->country))->row()->country_name;
                
                            $ucity      =   $user_details->address;
                
                            $address    =   $user_details->address;
                
                            $ph         =   $user_details->mobile;
                
                            $usermail   =   $user_details->email_address;
                
                            $username   =   (string)safe_b64decode($card_details->c_fname);
                
                            $last_name = 	(string)safe_b64decode($card_details->c_lname);
                            
                            $paymentmethod      =   (string)safe_b64decode($card_details->card_type);
                
                            $card_code          =   (string)safe_b64decode($card_details->cvv);
                
                            $card_number        =   (string)safe_b64decode($card_details->card_number);
                
                            $exp_month          =   (string)safe_b64decode($card_details->exp_month);
                
                            $exp_year           =   (string)safe_b64decode($card_details->exp_year);
                
                            $price              =   $payment_details->plan_price;   
                        }
                    }
                    
                    //echo $paymentmethod;
                    
                    if(($paymentmethod=="visa") || ($paymentmethod=="amex") || ($paymentmethod=="mastercard") || ($paymentmethod=="discover") )  
                        
                    {        
                            $this->load->library('Authorize_net');                       
            
                            $cardtype=$paymentmethod;   
            
                            $expiry_month=$exp_month;  
            
                            $expiry_year=$exp_year;  
            
                            $expiry_date=$expiry_month."/".$expiry_year;
            
                            // Authorize.net lib 
                            
                            $x_first_name =$username;
            
                            $x_last_name=$last_name;
            
                            $x_address=$address;
            
                            $x_city=$ucity;       
            
                            $x_state=$state_s; 
            
                            //$x_state      =$this->input->post('x_state');
            
                            $x_zip="";  
            
                            $x_country=$country_c;
            
                            $x_phone=$ph; 
            
                            $x_card_num=$card_number;  
            
                            $x_exp_date=$expiry_date; 
            
                            $x_card_code=$card_code; 
            
                            $amount=$price;  
            
                            $x_desc="subscription";  
            
                            $auth_net = array(
            
                            'x_card_num'            => $x_card_num,  
            
                            'x_exp_date'            => $x_exp_date, 
            
                            'x_card_code'           => $x_card_code, 
            
                            'x_description'         =>$x_desc,    
            
                            'x_amount'              => $amount,  
            
                            'x_first_name'          => $x_first_name, 
            
                            'x_last_name'           => $x_last_name, 
            
                            'x_address'             => $x_address,
            
                            'x_city'                => $x_city,
            
                            'x_state'               =>$x_state,
            
                            'x_zip'                 => $x_zip, 
            
                            'x_country'             =>$x_country,  
            
                            'x_phone'               => $x_phone,
            
                            'x_email'               => $usermail, 
            
                            //'x_customer_ip'           => $this->input->ip_address(),  
            
                            );
                            
                           /*  echo '<pre>';
                            print_r($auth_net); */
                            
            
                            if($paymentmethod == "amex") 
            
                            { 
            
                                $this->session->set_userdata('sess_customer_paymentmethod','amex'); 
            
                                $pattern = "/^([34|37]{2})([0-9]{13})$/";//American Express 
            
                                if (preg_match($pattern,$card_number)) { $verified = true;  } else { $verified = false;     }
            
                            }
            
                            elseif($paymentmethod == "discover")
            
                            {
            
                                $this->session->set_userdata('sess_customer_paymentmethod','discover');
            
                                $pattern = "/^([6011]{4})([0-9]{12})$/";//Discover Card
            
                                if (preg_match($pattern,$card_number)) {  $verified = true;     } else {    $verified = false;  }
            
                            } 
            
                            elseif($paymentmethod == "mastercard")
            
                            { 
                                //echo 'exit;';
                            
                                $this->session->set_userdata('sess_customer_paymentmethod','mastercard');
            
                                $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";//Mastercard
            
                                if (preg_match($pattern,$card_number)) {  $verified = true;     } else {    $verified = false;  }
            
                            } 
            
                            elseif($paymentmethod == "visa") 
            
                            {
            
                                $this->session->set_userdata('sess_customer_paymentmethod','visa');
            
                                $pattern = "/^([4]{1})([0-9]{12,15})$/";//Visa
            
                                if (preg_match($pattern,$card_number))  {   $verified = true;   }   else {  $verified = false;  }
            
                            } 
            
                            else 
            
                            {   
            
                                $verified = false; 
            
                            }
            
                            
                            if($verified==true)
            
                            {                   
            
            
                                $this->authorize_net->setData($auth_net);  
            
                                // Try to AUTH_CAPTURE
            
                                if( $this->authorize_net->authorizeAndCapture() )
            
                                {                    
									/* echo	'sdfsdfds'; */
                                    $transaction_id=$this->authorize_net->getTransactionId();
									/* echo 	insep_decode($subscribe_plan_id); die; */
                                    $results            =   $this->inventory_model->updatetransaction(insep_decode($subscribe_plan_id),$transaction_id,$connect_channels);
            /* echo $results; die; */
                                    if($results)
            
                                    {
                                        $this->session->set_flashdata('success',"Money Paid..Ordered Products will be deliveried in one week");

                                        redirect('inventory/Payment_Success/'.$subscribe_plan_id,'refresh');
            
                                    }
            
                                }
            
                                else 
            
                                {   
                                  
                                        $this->session->set_flashdata('error',"Invalid card number");

                                        redirect('inventory/Payment_Success/'.$subscribe_plan_id,'refresh');
            
                                    //$this->authorize_net->getError();                             
            
                                }
            
                            }   
            
                            else
            
                            {   
                            
                                    $this->session->set_flashdata('error',"Please check your Card Number. It is invalid");
			
                                    redirect('inventory/Payment_Success/'.$subscribe_plan_id,'refresh');
                            }
            
                        }
                }
            }
            elseif($free_subscribe!='')
            {
                if(!check_plan(insep_decode($subscribe_plan_id),'check'))
				{
					redirect(base_url());
				}
                $payment_id = $subscribe_plan_id;
                $data['buy_plan_id']  = insep_decode($payment_id);
					
                $plan_details = get_data(TBL_PLAN,array('plan_id'=>insep_decode($payment_id)))->row();
                $plan_duration = $plan_details->plan_types;
                
              if($plan_duration=='Free')
                {
                    $plan 						= $plan_details->plan_price;
                  $plan_du 					= 'days';
					$data['buy_plan_price']		= $plan;
					$data['buy_plan_type']		= "Free";
					$data['buy_plan_currency']	= $plan_details->currency;
					$data['buy_plan_account']	= '1';
					$data['transaction_id'] 	= rand(0,9999999);
					$data['total_channels'] 	= $plan_details->number_of_channels;
					$channels 					= get_data(TBL_CHANNEL,array('status'=>'Active'),'channel_id')->result_array();
					$check_channels 			= explode(',',$subscribe_channel_id);
					$channel_id 				= array_column($channels, 'channel_id');
					$result						= array_intersect($check_channels,$channel_id);
					$connect_channels 			= implode(',',$result);
                  $data['connect_channels']	= $connect_channels;
					$data['payment_method'] 	= 'FREE';
					$data['payment_status'] 	= '1';
					if(user_membership(1)==0)
					{
						$data['plan_status'] 		= '1';
						$data['plan_from'] 			= date('Y-m-d');
			     		$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
					}
					else
					{
						$ex_plan_channel = user_membership(4);
						if($ex_plan_channel['total_channels']==$plan_details->number_of_channels || $ex_plan_channel['total_channels'] > $plan_details->number_of_channels)
						{
							$plan_from					= date($ex_plan_channel['plan_to'], strtotime(' +1 day'));
							$data['plan_status'] 		= '3';
							$data['plan_from'] 			= $plan_from;
			     			//$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
                            $dtime = strtotime($plan_from);
                            $data['plan_to']            = date("Y-m-d", strtotime("+".$plan.' '.$plan_du,$dtime));
						}
						else if($ex_plan_channel['total_channels']<$plan_details->number_of_channels)
						{
							$data['plan_status'] 		= '1';
							$data['plan_from'] 			= date('Y-m-d');
			     			$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
							update_data(MEMBERSHIP,array('plan_status'=>'2'),array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
						}
					}
                    $data['user_id'] 			= current_user_type();
                    $data['hotel_id']	 		= hotel_id();
                    if(insert_data(MEMBERSHIP,$data))
                    {
                        //$this->session_unset_userdata('pay_type');
                        //$this->inventory_model->payment_success_mail(user_id(),$payment_id);
                        //$this->set_sessions();
                        //$this->load->view('user/Payment_Success',$data);
                        redirect('inventory/Payment_Success/'.($payment_id),'refresh');
                        
                        echo 'Payment_Succsess';
                    }
                    else
                    {
                        echo 'errror';
                    }
                }
            }
        }
        else
        {
            redirect(base_url());
        }
        
    }
    // 07/06/2016 vijikumar modified
    function Payment_Success($payment_id='')
    {
        error_reporting(0);
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if($payment_id!='')
        { 
            if($_REQUEST['txn_id']!='')
            {
				/* echo '<pre>';
				print_r($_REQUEST);
				die; */
                $plan_details = get_data(TBL_PLAN,array('plan_id'=>insep_decode($payment_id)))->row();
                $plan_duration = $plan_details->plan_types;
                
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
				
				$data['buy_plan_id']  		= insep_decode($payment_id);
				$data['buy_plan_price']		= $_REQUEST['mc_gross'];
				$data['buy_plan_type']		= $plan_duration;
				$data['buy_plan_currency']	= $plan_details->currency;
				$data['buy_plan_account']	= '1';
				$data['transaction_id'] 	= $_REQUEST['txn_id'];
				$data['total_channels'] 	= $plan_details->number_of_channels;
				$data['connect_channels']	= $_REQUEST['custom'];
				$data['payment_method'] 	= 'PAYPALL';
				$data['payment_status'] 	= '1';
				if(user_membership(1)==0)
				{
					$data['plan_status'] 		= '1';
					$data['plan_from'] 			= date('Y-m-d');
             		$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
				}
				else
				{
					$ex_plan_channel = user_membership(4);
					if($ex_plan_channel['total_channels']==$plan_details->number_of_channels || $ex_plan_channel['total_channels'] > $plan_details->number_of_channels)
					{
						$plan_from					= date($ex_plan_channel['plan_to'], strtotime(' +1 day'));
						$data['plan_status'] 		= '3';
						$data['plan_from'] 			= $plan_from;
             			//$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
                        $dtime = strtotime($plan_from);
                        $data['plan_to']            = date("Y-m-d", strtotime("+".$plan.' '.$plan_du,$dtime));
					}
					else if($ex_plan_channel['total_channels']<$plan_details->number_of_channels)
					{
						$data['plan_status'] 		= '1';
						$data['plan_from'] 			= date('Y-m-d');
             			$data['plan_to'] 			= date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
						update_data(MEMBERSHIP,array('plan_status'=>'2'),array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
					}
				}
				$data['user_id'] 			= current_user_type();
				$data['hotel_id']	 		= hotel_id();
				$check_transaction 			= get_data(MEMBERSHIP,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'payment_method'=>'PAYPALL','transaction_id'=>$_REQUEST['txn_id']))->row_array();

				if(count($check_transaction)==0)
				{
					if(insert_data(MEMBERSHIP,$data))
					{
						$this->inventory_model->payment_success_mail(current_user_type(),$payment_id);
						//$this->set_sessions();
						$data['page_heading'] 	= 'Payment Success';
						$user_details 			= get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
						$data 					= array_merge($data,$user_details);
						//die;
						$this->views('channel/Payment_Success',$data);
					}
					else
					{
						echo 'errror';
					}
				}
				else{
					$this->views('channel/Payment_Success',$data);
				}
            }
            else
            {
				/* echo '<pre>';
				print_r($_REQUEST);
				die; */
				
                $data['page_heading'] 	= 'Payment Success';
                $user_mail 				= $this->inventory_model->payment_success_mail(current_user_type(),$payment_id);
                $user_details 			= get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                $data 					= array_merge($data,$user_details);
                $this->views('channel/Payment_Success',$data);
                //redirect('home','refresh');
            }
         }
		 else
		 {
            redirect(base_url());
		 }
    }
    
    function Payment_Cancel()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        /*if($_REQUEST['txn_id']=='')
        {*/
            $this->load->view('user/Payment_Cancel');
        /*}*/
        /*else
        {
            //  $this->load->view('user/Payment_Cancel');
            redirect('home','refresh');
        }*/
    }
    
    function update_more_options()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $mdata['selling_period'] = $selling_period;
        $mdata['droc'] = $droc;
        $mdata['children'] = $children;
        $mdata['number_of_bedrooms'] = $number_of_bedrooms;
        $mdata['number_of_bathrooms'] = $number_of_bathrooms;
        $mdata['area'] = $area;
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
        {
            $update_data = update_data(TBL_PROPERTY,$mdata,array('hotel_id'=>hotel_id(),'owner_id'=>user_id(),'property_id'=>insep_decode($property_id)));
        }
        else if(user_type()=='2'){
            if(in_array('4',user_edit()))
            {
                $update_data = update_data(TBL_PROPERTY,$mdata,array('hotel_id'=>hotel_id(),'owner_id'=>owner_id(),'property_id'=>insep_decode($property_id)));
            }
            else
            {
                redirect(base_url());
            }
        }
        if($update_data)
        {
            redirect('inventory/room_types/view/'.$property_id);
        }
    }
    
    function update_basic_rooms()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $bdata['property_name'] = $this->input->post('property_name_'.insep_decode($property_id));
        $bdata['room_capacity'] = $room_capacity;
        $bdata['existing_room_count'] = $existing_room_count;
        $bdata['member_count'] = $member_count;
        $bdata['meal_plan'] = $meal_plan;
        $bdata['description'] = $description;
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        $adult_count = get_data(TBL_PROPERTY,array('hotel_id'=>hotel_id(),'owner_id'=>user_id(),'property_id'=>insep_decode($property_id)))->row()->member_count;
        if(update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>user_id())))
        {
            if($adult_count < $member_count)
            {
                $add_room_count = $member_count - $adult_count;
                for($i=1;$i<=$add_room_count;$i++)
                {
                    $ndata['room_id'] = insep_decode($property_id);
                    $ndata['user_id'] = current_user_type();
                    $ndata['hotel_id'] = hotel_id();
                    insert_data('room_refunds',$ndata);
                }
            }
            redirect('inventory/room_types/view/'.$property_id);
        }
        }
        else if(user_type()=='2'){
            if(in_array('4',user_edit())){
        $adult_count = get_data(TBL_PROPERTY,array('hotel_id'=>hotel_id(),'owner_id'=>owner_id(),'property_id'=>insep_decode($property_id)))->row()->member_count;
        if(update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>owner_id())))
        {
            if($adult_count < $member_count)
            {
                $add_room_count = $member_count - $adult_count;
                for($i=1;$i<=$add_room_count;$i++)
                {
                    $ndata['room_id'] = insep_decode($property_id);
                    $ndata['user_id'] = owner_id();
                    $ndata['hotel_id'] = hotel_id();
                    insert_data('room_refunds',$ndata);
                }
            }
            redirect('inventory/room_types/view/'.$property_id);
        }
            }
            else
            {
                redirect(base_url());
            }
        }
    }

    function update_numbers_rooms(){
 if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $bdata['existing_room_number'] = $existing_room_number;
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        $adult_count = get_data(TBL_PROPERTY,array('hotel_id'=>hotel_id(),'owner_id'=>user_id(),'property_id'=>insep_decode($property_id)))->row()->member_count;
        if(update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>user_id())))
        {
            redirect('inventory/room_types/view/'.$property_id);
        }
        }
        else if(user_type()=='2'){
            if(in_array('4',user_edit())){
        $adult_count = get_data(TBL_PROPERTY,array('hotel_id'=>hotel_id(),'owner_id'=>owner_id(),'property_id'=>insep_decode($property_id)))->row()->member_count;
        if(update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>owner_id())))
        {

            redirect('inventory/room_types/view/'.$property_id);
        }
            }
            else
            {
                redirect(base_url());
            }
        }
    }
    
    function master_rate_update ()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $bdata['pricing_type'] = $pricing_type; 
        if(isset($non_r)!='')
        {
            $bdata['non_refund'] = ($non_r);    
        }
        else
        {
            $bdata['non_refund'] = 0;
        }
        $bdata['price'] = $price;
        $rdata['pricing_type'] = $pricing_type;
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
            update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>user_id()));
            update_data(RATE_TYPES,$rdata,array('room_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'user_id'=>user_id()));
        }
        else if(user_type()=='2'){
            if(in_array('4',user_edit())){
            update_data(TBL_PROPERTY,$bdata,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>owner_id()));    
            update_data(RATE_TYPES,$rdata,array('room_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'user_id'=>owner_id()));
             }
             else
             {
                 redirect(base_url());
             }
        }
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        $add_room = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>user_id()))->row();
        }
        else if(user_type()=='2'){
            $add_room = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($property_id),'hotel_id'=>hotel_id(),'owner_id'=>owner_id()))->row();
        }
        if($add_room->pricing_type==2)
        {
            delete_data(RATE,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'room_id'=>insep_decode($property_id)));
            for($i=1;$i<=$add_room->member_count;$i++)
            {   
                        $ndata['room_unq_id'] = insep_decode($property_id).'_'.$i;
                        $ndata['room_id'] = insep_decode($property_id);
                        $ndata['method'] = $this->input->post('method_'.$i);
                        $ndata['type'] =$this->input->post('type_'.$i);
                        $ndata['dis_amount']=$this->input->post('d_amt_'.$i);
                        $ndata['total_amount'] =$this->input->post('h_total_'.$i);
                        if(isset($non_r)!='')
                        {
                            $ndata['n_method'] = $this->input->post('n_method_'.$i);
                            $ndata['n_type'] =$this->input->post('n_type_'.$i);
                            $ndata['n_dis_amount'] = $this->input->post('n_d_amt_'.$i);
                            $ndata['d_total_amount'] = $this->input->post('n_h_total_'.$i);
                        }
                        $ndata['created'] = date('y-m-d H:i:s');
                        if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                        {
                            $ndata['user_id'] = user_id();
                        }
                        else if(user_type()=='2'){
                            if(in_array('4',user_edit()))
                           {
                            $ndata['user_id'] = owner_id();
                           }
                           else
                           {
                               redirect(base_url());
                           }
                        }
                        $ndata['hotel_id'] = hotel_id();
                        
                        insert_data('room_refunds',$ndata);
                        
            }
        }
        else if($add_room->pricing_type==1 && $add_room->non_refund==1)
        {
            $i=1;
                        if(isset($non_r)!='')
                        {
                            $ndata['room_id'] = insep_decode($property_id);
                            $ndata['room_unq_id'] = insep_decode($property_id).'_'.$i;
                            if($this->input->post('r_n_method_'.$i)!='')
                            {
                                $ndata['n_method'] = $this->input->post('r_n_method_'.$i);
                                $ndata['n_type'] =$this->input->post('r_n_type_'.$i);
                                $ndata['n_dis_amount'] = $this->input->post('r_n_d_amt_'.$i);
                                $ndata['d_total_amount'] = $this->input->post('r_n_h_total_'.$i);
                            }
                            $ndata['created'] = date('y-m-d H:i:s');
                            if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
                                $ndata['user_id'] = user_id();
                            }
                            else if(user_type()=='2'){
                                $ndata['user_id'] = owner_id();
                            }
                            $ndata['hotel_id'] = hotel_id();
                            if(delete_data(RATE,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'room_id'=>insep_decode($property_id))))
                            {       
                                insert_data('room_refunds',$ndata);
                                
                            }
                        }
                        else
                        {
                            delete_data(RATE,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'room_id'=>insep_decode($property_id)));
                        }
        }
        redirect('inventory/room_types/view/'.$property_id);
    }
    
    function manage_photos($mode='',$id='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        switch($mode)
        {
            case 'get':
            // if(user_type()=='1'){
            $available = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($hotel_id)))->row_array();
            /*}
            else if(user_type()=='2'){
            $available = get_data(TBL_PROPERTY,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($hotel_id)))->row_array();   
            }*/
            if(count($available)!=0)
            {   
                $datas['hotel_photos'] = get_data(TBL_PHOTO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($hotel_id)))->row_array();
                $this->partial('channel/room_photo',$datas);
            }
            else
            {
                echo '0';
            }
            break;
            case 'new':
               $name_array = array();
               $count = count($_FILES['hotel_image']['size']); // input field name
               foreach($_FILES as $key=>$value)
               for($s=0; $s<=$count-1; $s++) 
               {
                    $_FILES['hotel_image']['name']=$value['name'][$s];
                    $_FILES['hotel_image']['type']    = $value['type'][$s];
                    $_FILES['hotel_image']['tmp_name'] = $value['tmp_name'][$s];
                    $_FILES['hotel_image']['error']       = $value['error'][$s];
                    $_FILES['hotel_image']['size']    = $value['size'][$s];   
                    $config['upload_path'] = 'uploads/room_photos/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $ext = explode(".", $_FILES['hotel_image']["name"]);
                    $extension = end($ext);
                    $encrypt=md5(date('Y-m-d H:i:s').rand(0,10));
                    $image_name=$encrypt.'.'.$extension;
                    $config['file_name']=$image_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->do_upload('hotel_image');
                    $data = $this->upload->data();
                    $name_array[] = $image_name;
                    image_resizer("uploads/room_photos/",$image_name, 650, 366);
               }
                $names= implode(',',$name_array);
                $files = array('name'=> $names);
                foreach($files as $filename)  
                $result= $this->inventory_model->add_photo($filename);
                if($result)
                {
                    // if(user_type()=='1'){
                    $data['hotel_photos'] = get_data(TBL_PHOTO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($hotel_id)))->row_array();
                    /*}
                    else if(user_type()=='2'){
                    $data['hotel_photos'] = get_data(TBL_PHOTO,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'room_id'=>insep_decode($hotel_id)))->row_array();    
                    }*/
                    $this->partial('channel/room_photo',$data);
                }
            break;
            case 'remove':
            // if(user_type()=='1'){
            $value = get_data(TBL_PHOTO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'photo_id'=>insep_decode($photo_id)))->row()->photo_names;
            /*}
            else if(user_type()=='2'){
            $value = get_data(TBL_PHOTO,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'photo_id'=>insep_decode($photo_id)))->row()->photo_names;   
            }*/
            $original_val = explode(',',$value);
            $devl_id = insep_decode($image);
            foreach($original_val as $val)
            {
                if(($key = array_search($devl_id, $original_val)) !== false) {
                    unset($original_val[$key]);
                    $test_array = array_values($original_val);
                }
            }
            $insert_value=implode(',',$test_array);
            $updata['photo_names'] = $insert_value;
            // if(user_type()=='1'){
            if(update_data(TBL_PHOTO,$updata,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'photo_id'=>insep_decode($photo_id))))
            {
                unlink('uploads/room_photos/'.$devl_id);
                $datas['hotel_photos'] = get_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)))->row_array();
                $this->partial('channel/room_photo',$datas);
            }
            /*}else if(user_type()=='2'){
            if(update_data(TBL_PHOTO,$updata,array('user_id'=>owner_id(),'hotel_id'=>hotel_id(),'photo_id'=>insep_decode($photo_id))))
            {
                unlink('uploads/room_photos/'.$devl_id);
                $datas['hotel_photos'] = get_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)))->row_array();
                $this->partial('channel/room_photo',$datas);
            }   
        }*/
            break;
        }
    }
    
    function manage_rate_types($mode='',$id='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $data['page_heading'] = 'Manage Rooms';
        switch($mode)
        {
            case'add':
            extract($this->input->post());
            $u_id = rand(0,99999);
            $idata['room_id'] = insep_decode($property_id);
            // echo $idata['room_id'] = insep_decode($property_id); die;
            $idata['uniq_id'] = $u_id;
            $idata['rate_name']=$property_name;
            $idata['pricing_type'] =$pricing_type;
            $idata['droc'] =$droc;
            $idata['meal_plan'] =$meal_plan;
            $idata['price'] = $price;
            $idata['user_id'] = current_user_type();
            $idata['hotel_id'] = hotel_id();
            if(isset($non_r)!='')
            {
                $idata['non_refund'] = $non_r;
                $non_refund = '1';
            }
            $idata['created'] = date('y-m-d H:i:s');
            if(insert_data(RATE_TYPES,$idata))
            {
                $room_id = $this->db->insert_id();

                $add_room = get_data(RATE_TYPES,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'uniq_id'=>$u_id))->row();

                if($add_room->pricing_type==2)
                {
                    for($i=1;$i<=insep_decode($member_count);$i++)
                    {       
                                $ndata['room_id'] = insep_decode($property_id);     
                                // $ndata['room_id'] = insep_decode($property_id);
                                $ndata['uniq_id'] = $u_id;
                                $ndata['method'] = $this->input->post('method_'.$i);
                                $ndata['type'] =$this->input->post('type_'.$i);
                                $ndata['dis_amount']=$this->input->post('d_amt_'.$i);
                                $ndata['total_amount'] =$this->input->post('h_total_'.$i);
                                if(isset($non_r)!='')
                                {
                                    $ndata['n_method'] = $this->input->post('n_method_'.$i);
                                    $ndata['n_type'] =$this->input->post('n_type_'.$i);
                                    $ndata['n_dis_amount'] = $this->input->post('n_d_amt_'.$i);
                                    $ndata['d_total_amount'] = $this->input->post('n_h_total_'.$i);
                                }
                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
                                    $ndata['user_id']=user_id();
                                }
                                else if(user_type()=='2'){
                                    if(in_array('4',user_edit()))
                                    {
                                    $ndata['user_id']=owner_id();
                                    }
                                    else
                                    {
                                        redirect(base_url());
                                    }
                                }
                                $ndata['hotel_id']=hotel_id();
                                insert_data(RATE_TYPES_REFUN,$ndata);
                    }
                }
                else if($add_room->pricing_type==1)/* && $add_room->non_refund==1)*/
                {
                    $i=1;
                                $ndata['room_id'] = insep_decode($property_id);
                                // $ndata['room_id'] = insep_decode($property_id);
                                $ndata['method'] = $this->input->post('r_method_'.$i);
                                $ndata['type'] =$this->input->post('r_type_'.$i);
                                $ndata['dis_amount']=$this->input->post('r_d_amt_'.$i);
                                $ndata['total_amount'] =$this->input->post('r_h_total_'.$i);
                                $ndata['uniq_id'] = $u_id;
                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                {
                                $ndata['user_id']=user_id();
                                }
                                else if(user_type()=='2'){
                                    if(in_array('4',user_edit()))
                                    {
                                      $ndata['user_id']=owner_id(); 
                                    }
                                    else
                                    {
                                        redirect(base_url());
                                    }
                                }
                                $ndata['hotel_id']=hotel_id();
                                if(isset($non_r)!='')
                                {
                                    if($this->input->post('r_n_method_'.$i)!='')
                                    {
                                        $ndata['room_id'] = insep_decode($property_id);
                                        $ndata['n_method'] = $this->input->post('r_n_method_'.$i);
                                        $ndata['n_type'] =$this->input->post('r_n_type_'.$i);
                                        $ndata['n_dis_amount'] = $this->input->post('r_n_d_amt_'.$i);
                                        $ndata['d_total_amount'] = $this->input->post('r_n_h_total_'.$i);
                                        
                                    }
                                }
                                insert_data(RATE_TYPES_REFUN,$ndata);
                }
                $this->session->set_flashdata('profile','Room added successfully!!!');
                redirect('inventory/room_types/view/'.($property_id),'refresh');
            }
            break;
            case "edit":
            $available = get_data(RATE_TYPES,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'uniq_id'=>(($id))))->row_array();
			if($available['rate_type_id']!=""){
                $rate_type=$available['rate_type_id'];
                $room_id=$available['room_id'];
             $file=$this->inventory_model->roomtype_ICAL($room_id,$rate_type);
             $data['file_name']=$file;
            }
            if(count($available)!=0)
            {
                $data = array_merge($data,$available);
                $data['types_refund'] = get_data(RATE_TYPES_REFUN,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'uniq_id'=>$id))->result_array();
                $this->load->view('channel/rate_types',$data);
            }
            break;
            case'update':
            extract($this->input->post());
            $idata['rate_name']=$property_name;
            $idata['pricing_type'] =$pricing_type;
            $idata['droc'] =$droc;
            $idata['meal_plan'] =$meal_plan;
            $idata['price'] = $price;
            if(isset($non_r)!='')
            {
                $idata['non_refund'] = $non_r;
                $non_refund = '1';
            }
            else
            {
                $idata['non_refund'] = '0';
            }
            $idata['modified'] = date('y-m-d H:i:s');
            if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
            $room_rate = update_data(RATE_TYPES,$idata,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'uniq_id'=>$uniq_id));
            }
            else if(user_type()=='2'){
                if(in_array('4',user_edit()))
                {
                    $room_rate = update_data(RATE_TYPES,$idata,array('hotel_id'=>hotel_id(),'user_id'=>owner_id(),'uniq_id'=>$uniq_id));    
                }
                else
                {
                    redirect(base_url());
                }
           }
            if($room_rate)
            {
                $room_id = $property_id;
                $add_room = get_data(RATE_TYPES,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'uniq_id'=>$uniq_id))->row();
					if($add_room->pricing_type==2)
                {
                    for($i=1;$i<=$member_count;$i++)
                    {       
                            $ndata['method'] = $this->input->post('method_'.$i);
                        $ndata['type'] =$this->input->post('type_'.$i);
                        $ndata['dis_amount']=$this->input->post('d_amt_'.$i);
                        $ndata['total_amount'] =$this->input->post('h_total_'.$i);
                        if(isset($non_r)!='')
                        {
                            $ndata['n_method'] = $this->input->post('n_method_'.$i);
                        $ndata['n_type'] =$this->input->post('n_type_'.$i);
                        $ndata['n_dis_amount'] = $this->input->post('n_d_amt_'.$i);
                        $ndata['d_total_amount'] = $this->input->post('n_h_total_'.$i);
                        }
                                update_data(RATE_TYPES_REFUN,$ndata,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'rate_type_id'=>$this->input->post('rate_type_id_'.$i)));
                    }
                }
                else if($add_room->pricing_type==1) /*&& $add_room->non_refund==1)*/
                {
                    $i=1;
                    /*for($i=1;$i<=$member_count;$i++)
                    {   */
                                $ndata['method'] = $this->input->post('r_e_method_'.$i);
                                $ndata['type'] =$this->input->post('r_e_type_'.$i);
                                $ndata['dis_amount']=$this->input->post('r_e_d_amt_'.$i);
                                $ndata['total_amount'] =$this->input->post('r_e_h_total_'.$i);
                                if(isset($non_r)!='')
                                {
                                    if($this->input->post('r_n_method_'.$i)!='')
                                    {
                                        $ndata['n_method'] = $this->input->post('r_n_method_'.$i);
                                        $ndata['n_type'] =$this->input->post('r_n_type_'.$i);
                                        $ndata['n_dis_amount'] = $this->input->post('r_n_d_amt_'.$i);
                                        $ndata['d_total_amount'] = $this->input->post('r_n_h_total_'.$i);
                                    }
                                }
                                if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                                {
									update_data(RATE_TYPES_REFUN,$ndata,array('hotel_id'=>hotel_id(),'user_id'=>current_user_type(),'rate_type_id'=>$rate_type_id));
                                }
                                else if(user_type()=='2'){
                                    if(in_array('4',user_edit()))
                                    {
                                        update_data(RATE_TYPES_REFUN,$ndata,array('hotel_id'=>hotel_id(),'user_id'=>owner_id(),'rate_type_id'=>$rate_type_id)); 
                                    }
                                    else
                                    {
                                        redirect(base_url());
                                    }
                                } 
                    /*}*/
                }
                
                $this->session->set_flashdata('profile','Room added successfully!!!');
                redirect('inventory/room_types/view/'.insep_encode($property_id),'refresh');
            }
            break;
            case'delete':
            extract($this->input->post());
            $available = get_data(RATE_TYPES,array('uniq_id'=>insep_decode($unq_id),'room_id'=>insep_decode($room_id),'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row_array();   

            if(count($available)!=0)
            {
                if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
                {
                    $map_exists = get_data(MAP,array('property_id'=>insep_decode($room_id),'rate_id'=>$available['rate_type_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->num_rows();           
                    if($map_exists == 0)
                    {
                        delete_data(RATE_TYPES,array('uniq_id'=>insep_decode($unq_id),'room_id'=>insep_decode($room_id),'user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
                        delete_data(RATE_TYPES_REFUN,array('uniq_id'=>insep_decode($unq_id),'room_id'=>insep_decode($room_id),'user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
                        echo '1';
                    }else{
                        echo '4';
                    }
                }
                else if(user_type()=='2')
                {
                    if(in_array('4',user_edit()))
                    {
                        $map_exists = get_data(MAP,array('property_id'=>insep_decode($room_id),'rate_id'=>$available['rate_type_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->num_rows();           
                        if($map_exists == 0)
                        {
                            delete_data(RATE_TYPES,array('uniq_id'=>insep_decode($unq_id),'room_id'=>insep_decode($room_id),'user_id'=>owner_id(),'hotel_id'=>hotel_id()));
                            delete_data(RATE_TYPES_REFUN,array('uniq_id'=>insep_decode($unq_id),'room_id'=>insep_decode($room_id),'user_id'=>owner_id(),'hotel_id'=>hotel_id()));
                            echo '1';
                        }else{
                            echo '4';
                        }
                     }
                     else
                     {
                         redirect(base_url());
                     }
               }
            }
            else
            {
                echo '3';
            }
            
            break;
        }
    }

	/**
	* Modify: 04/01/2017 Subbaiah
	*
	* Controller: inventory		(/application/controllers/inventory.php) :: advance_update
	*
	* Models: inventory_advance_update_model		(/application/models/inventory_advance_update_model.php)
	*
	* Views: channel/inventory/advance_update		(/application/views/channel/inventory/advance_update.php)
	*
	* Tables: TBL_USERS (manage_users) :: TBL_PROPERTY (manage_property) :: TBL_UPDATE (room_update) :: RATE_TYPES (room_rate_types)
	*
	*/
	function advance_update($mode='',$id='')
	{
		if(admin_id()=='')
		{ 
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		
		if( !(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1') )
		{
			redirect(base_url());
		}
		
		if( $mode === 'reservation_no' )
		{
        $data['action']=$mode;
        switch($mode)
        {
            case"reservation_no":
            if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
            {
                $data['page_heading'] = 'Advance Update';
                $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                $data= array_merge($data,$user_details);
                $this->db->order_by('property_id','desc');
				$data['all_room'] = get_data(TBL_PROPERTY,array('status'=>'Active','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'),'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
                $data['all_channel'] = $this->channel_model->user_channel_hotel();
                $this->views('channel/calender_yes',$data);
                //$this->views('channel/'.uri(3),$data);
            }
            else
            {
                redirect(base_url());
            }
            break;
            default:
            if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=="1")
            {
                $data['page_heading'] = 'Advance Update';
                $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                $data= array_merge($data,$user_details);
                $this->db->order_by('property_id','desc');
				$data['all_room'] = get_data(TBL_PROPERTY,array('status'=>'Active','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'),'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
                //$data['all_channel'] = $this->channel_model->user_channel_hotel();
                $this->views('channel/calender_yes',$data);
            }
            else
            {
                redirect(base_url());
            }
            //$this->views('channel/'.uri(3),$data);
            break;
        }

			//$this->views('channel/inventory/advance_update_reservation_no', $startDate, $endDate);
		}
		else
		{
			$startDate = new DateTime();
			
			$endDate = new DateTime();
			
			$interval = new DateInterval('P1M');
	
			$endDate->add($interval);
	
			$interval = new DateInterval('P1D');
	
			$endDate->add($interval);
	
			$model = $this->inventory_advance_update_model;
			
			$model->setStartDate($startDate );

			$model->setEndDate($endDate);

			$this->views('channel/inventory/advance_update_reservetion', array_merge(
				get_data(TBL_USERS,array('user_id'=>user_id()))->row_array(),
				[
				'page_heading' => 'Advance Update',
				],
				$this->inventory_advance_update_model->getCalendarData()
			));
		}
	}
 
	
	/*Calendar View Functionality Start\*/

	/**
	* Modify: 04/01/2017 Subbaiah
	*
	* Controller: inventory		(/application/controllers/inventory.php) :: change_month
	*
	* Models: inventory_advance_update_model		(/application/models/inventory_advance_update_model.php)
	*
	* Views: channel/inventory/advance_update		(/application/views/channel/inventory/change_month.php)
	*
	* Tables: TBL_USERS (manage_users) :: TBL_PROPERTY (manage_property) :: TBL_UPDATE (room_update) :: RATE_TYPES (room_rate_types)
	*
	*/
    function change_month()
    {
 		if(!IS_AJAX) 
		{
			$this->load->view('admin/404');
		}
		else
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
                
				extract($this->input->post());

				$startDate = DateTime::createFromFormat('d/m/Y', $nr_pr_date);

				$endDate = clone $startDate;
				
				$interval = new DateInterval('P1M');
		
				$endDate->add($interval);

				$model = $this->inventory_advance_update_model;
				
				$model->setStartDate($startDate );

				$model->setEndDate($endDate);                     

               //$this->load->view('channel/inventory/change_month',$this->inventory_advance_update_model->getCalendarData());

				$this->load->view('channel/inventory/change_month',
					$this->inventory_advance_update_model->getCalendarData()
				);
			}
			else
			{
				echo '0';
			}
		}
    }

    function change_month_no()
    {
		if(!IS_AJAX) 
		{
			$this->load->view('admin/404');
		}
		else
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
			{ 
				extract($this->input->post());
				$data['nr_pr_date'] = $nr_pr_date;
				$data['channel_id'] = $channel_id;
				$data['rate_id'] = $current_rate;
				//$data['method']     = $method change_month_no;    
				$this->load->view('channel/calendar_change_month_no',$data);
			}
			else 
			{
				echo '0';
			}
		}
    }


    
	/**
	* Modify: 04/01/2017 Subbaiah
	*
	* Controller: inventory		(/application/controllers/inventory.php) :: customize_calender
	*
	* Models: inventory_advance_update_model		(/application/models/inventory_advance_update_model.php)
	*
	* Views: channel/inventory/advance_update		(/application/views/channel/inventory/change_month.php)
	*
	* Tables: TBL_USERS (manage_users) :: TBL_PROPERTY (manage_property) :: TBL_UPDATE (room_update) :: RATE_TYPES (room_rate_types)
	*
	*/
	function customize_calender_new()
    {
		if(!IS_AJAX) 
		{
			$this->load->view('admin/404');
		}
		else
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
			{
				extract($this->input->post());

				$model = $this->inventory_advance_update_model;
				
				$model->setStartDate( DateTime::createFromFormat('d/m/Y', $start_date) );

				$model->setEndDate( DateTime::createFromFormat('d/m/Y', $end_date) );

				$this->load->view('channel/inventory/customize_calender',
					$this->inventory_advance_update_model->getCalendarData()
				);
			}
			else
			{
				echo '0';
			}
		}
    }
	
	function customize_calender()
    {
		if(!IS_AJAX) 
		{
			$this->load->view('admin/404');
		}
		else
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
			{
				extract($this->input->post());
				$data['get_data'] = 'get_data';
				$data['start_date'] = $start_date;
				$data['end_date']   = $end_date ;
				//$data['room_map'] = $room_map;
				//$data['channels'] = $channel_id;
				//$data['days']     = $days;
				//customize_date
				$this->load->view('channel/calendar_customize_date',$data);
			}
			else
			{
				echo '0';
			}
		}
    }
    
    
    function customize_calender_no()
    {
		if(!IS_AJAX) 
		{
			$this->load->view('admin/404');
		}
		else
		{
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if(user_type()=='1' || user_type()=='2' && in_array('3',user_view()) || admin_id()!='' && admin_type()=='1')
			{
				extract($this->input->post());
				$data['get_data'] = 'get_data';
				$data['start_date'] = $start_date;
				$data['end_date']   = $end_date ;
				$data['channel_id'] = $custom_channel_id;
				$data['rate_id'] = $custom_channel_rate_id;
				//$data['room_map'] = $room_map;
				//$data['channels'] = $channel_id;
				//$data['days']     = $days;
				//customize_date_no
				$this->load->view('channel/calendar_customize_date_no',$data);
			}
			else
			{
				echo '0';
			}
		}
    }
    /*Calendar View Functionality End\*/
    
    function single_date_update()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $data['get_data'] = 'get_data';
        $single_data = get_data(TBL_UPDATE,array('room_id'=>$room_id,'separate_date'=>$up_date))->row_array();
        $data = array_merge($data,$single_data);
        $this->load->view('channel/single_date_update',$data);
    }
    
    function inline_edit()
    {
       if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $room_id = substr($pk, strpos( $pk,"-") + 1); 
        $date = explode("-", $pk);
        $update_date = $date[0];
        if($name=='price')
        {
            $udata['price'] = $value;
        }
        else if($name=='availability')
        {
            $udata['availability'] = $value;
        }
        else if($name=='minimum_stay')
        {
            $udata['minimum_stay'] = $value;
        }
        if(update_data(TBL_UPDATE,$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date)))
        {
            echo '1';
        }
        else
        {
        
        }
        //  print_r($this->input->post());
    }
    
    /*Main Calendar Functionality Start\*/
    
    function inline_edit_no()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());

        $room_id = substr($pk, strpos( $pk,"-") + 1); 

        $date = explode("-", $pk);
        
        $update_date = $date[0];
        
        if($name=='price')
        {
            $udata['price']	=	$value;
			
            $org_price 		= 	$value;
			
			$update			=	'price';
			
			$column			=	'price';
        }
        else if($name=='availability')
        {
            $udata['availability']	= 	$value;
			
            $udata['trigger_cal'] 	=	 3;
			
			$column					=	'availability';
			
			$org_price	 			= 	$value;
			
			$update					=	'availability';
        }
        else if($name=='minimum_stay')
        {
            $udata['minimum_stay'] 	= 	$value;
			
			$column					=	'minimum_stay';
				
			$org_price	 			= 	$value;
			
			$update					=	'minimum_stay';
        }
        
        $available = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date,'individual_channel_id' => 0))->row_array();
        
        if(count($available)!='0')
        {
            if(update_data(TBL_UPDATE,$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date,'individual_channel_id'=>0)))
            {
               // echo '1';
            }
            else
            {
            
            }
        }
        else if(count($available=='0'))
        {
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['room_id']       = $room_id;
            $udata['days']          = '1,2,3,4,5,6,7';
            $udata['separate_date'] = $update_date;
            $udata['individual_channel_id'] = 0;
            if(insert_data(TBL_UPDATE,$udata))
            {
                //echo '1';
            }
            else
            {
            
            }
        }

        $all_channel = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'),'channel_id')->result_array();

		$all_channel = array_column($all_channel, 'channel_id');
        $channel_bnow = '';
        $channel_travel = '';
        $channel_wbeds = '';
		if (($key = array_search('17', $all_channel)) !== false) 
		{
			unset($all_channel[$key]);
			$channel_bnow = 'bnow';
		}
		if(($key = array_search('15', $all_channel)) !== false)
		{
			unset($all_channel[$key]);
            $channel_travel = 'travel';
		}
        if(($key = array_search('14', $all_channel)) !== false)
        {
            unset($all_channel[$key]);
            $channel_wbeds = 'wbeds';
        }
        if(count($all_channel!=0))
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
					$available1= get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date))->row_array();
					if($name == "price"){
						$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
						//print_r($room_mapping);
						if($room_mapping){

							foreach($room_mapping as $room_value){
								if($room_value->rate_conversion != "1"){
									if(strpos($room_value->rate_conversion, '.') !== FALSE){
										$udata['price'] = $value * $room_value->rate_conversion;
									}else if(strpos($room_value->rate_conversion, ',') !== FALSE){
										$mul = str_replace(',', '.', $room_value->rate_conversion);
										$udata['price'] = $value * $mul;
									}else if(is_numeric($room_value->rate_conversion)){
										$udata['price'] = $value * $room_value->rate_conversion;
									}  
									
								}else{
									$udata['price'] = $org_price;
								}
							}
						}else{
							$udata['price'] = $org_price;
						}
					}
					if($name == 'availability'){
						$udata['trigger_cal'] = 0;
					}
					if(count($available1)!=0)
					{ 
                        $udata['individual_channel_id'] = $con_ch;
						$this->db->where('owner_id', current_user_type());
						$this->db->where('hotel_id', hotel_id());
						$this->db->where('room_id', $room_id);
						$this->db->where('separate_date', $update_date);
						$this->db->where('individual_channel_id', $con_ch);
						$this->db->update(TBL_UPDATE, $udata);
					}
					else
					{
						$udata['owner_id']      = current_user_type();
						$udata['hotel_id']      = hotel_id();
						$udata['room_id']       = $room_id;
						$udata['days']          = '1,2,3,4,5,6,7';
						$udata['separate_date'] = $update_date;
						$udata['individual_channel_id'] = $con_ch;
						$this->db->insert(TBL_UPDATE, $udata);
					}
					//echo $room_id;
			
					if($name=='price' || $name=='availability')
					{
						//print_r(array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'property_id'=>$room_id));
						$room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_id))->row();
						//print_r($room_details);
						if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
						
						if($name == 'price')
						{
							$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->count_all_results();
						}
						if($name == 'availability')
						{
							$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'enabled'=>'enabled'))->count_all_results();
						}
						
						if($count!=0)
						{
							if($name == 'price')
							{
								$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
							}
							if($name == 'availability')
							{
								$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'enabled'=>'enabled'))->result();
							}
					   
							if($room_mapping)
							{
								foreach($room_mapping as $room_value)
								{
									//echo $room_value->channel_id."<br>";
									$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'separate_date'=>$update_date))->row();
									
									if($room_value->channel_id=='11')
									{
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
										$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
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
										
										if($name=='price' && $room_value->update_rate=='1')
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
											   // echo $product['price'];
												foreach($set_arr as $k=>$v)
												{
													if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
														if(strpos($v, '+') !== FALSE){
															$opr = explode('+', $v);
															if(is_numeric($opr[1])){
																$ex_price = $update_Details->price + $opr[1];
															}else if(is_numeric($opr[0])){
																$ex_price = $update_Details->price + $opr[0];
															}else{
																if(strpos($opr[1], '%')){
																	$per = explode('%',$opr[1]);
																	if(is_numeric($per[0])){
																		$per_price = ($update_Details->price * $per[0]) / 100;
																		$ex_price = $update_Details->price + $per_price;
																	}
																}elseif (strpos($opr[0], '%')) {
																	$per = explode('%',$opr[0]);
																	if(is_numeric($per[0])){
																		$per_price = ($update_Details->price * $per[0]) / 100;
																		$ex_price = $update_Details->price + $per_price;
																	}
																}
															}
														}elseif (strpos($v, '-') !== FALSE) {
															$opr = explode('-', $v);
															if(is_numeric($opr[1])){
																$ex_price = $update_Details->price - $opr[1];
															}elseif (is_numeric($opr[0])) {
																$ex_price = $update_Details->price - $opr[0];
															}else{
																if(strpos($opr[1],'%') !== FALSE){
																	$per = explode('%',$opr[1]);
																	if(is_numeric($per[0])){
																		$per_price = ($update_Details->price * $per[0]) / 100;
																		$ex_price = $update_Details->price - $per_price;
																	}
																}elseif (strpos($opr[0],'%') !== FALSE) {
																	$per = explode('%',$opr[0]);
																	if(is_numeric($per[0])){
																		$per_price = ($update_Details->price * $per[0]) / 100;
																		$ex_price = $update_Details->price - $per_price;
																	}
																}
															}
														}elseif (strpos($v, '%') !== FALSE) {
															$opr = explode('%', $v);
															if(is_numeric($opr[1])){
																$per_price = ($update_Details->price * $opr[1]) / 100;
																$ex_price = $update_Details->price + $per_price;
															}elseif (is_numeric($opr[0])) {
																$per_price = ($update_Details->price * $opr[0]) / 100;
																$ex_price = $update_Details->price + $per_price;
															}
														}else{
															$ex_price = $update_Details->price + $v;
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
											<EndDate>'.$up_sart_date.'</EndDate>
											<SingleOcc>'.$update_Details->price.'</SingleOcc>'.
											$mapping_fields.'
											<Meals>'.$meal_plan.'</Meals>
											<MinStay>'.$update_Details->minimum_stay.'</MinStay>
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
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline edit',date('m/d/Y h:i:s a', time()));
													$this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
												}
												else if(count($soapFault)!='0')
												{     
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline edit',date('m/d/Y h:i:s a', time())); 
													$this->session->set_flashdata('bulk_error',$soapFault['soapText']);
												}
											}
											curl_close($ch);
										}
										else if($name=='availability' && $room_value->update_availability=='1')
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
											<Availability>='.$update_Details->availability.'</Availability>
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
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline edit',date('m/d/Y h:i:s a', time())); 
													echo $Errorarray['WARNING'];
												}
												else if(count($soapFault)!='0')
												{      
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline edit',date('m/d/Y h:i:s a', time())); 
													echo $soapFault['soapText'];
												}
												return false;
											}
											curl_close($ch);
										}
									}
									else if($room_value->channel_id=='1')
									{
										$exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
										
										$up_days =  explode(',',$update_Details->days);
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
										$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

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
										//$name=='price' && $update_Details->price >= (string)$rt_details->minAmount && $update_Details->price <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
										if($name=='price' && $room_value->update_rate=='1'){
											$xml = '<?xml version="1.0" encoding="UTF-8"?>
													<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
													<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
													<Hotel id="'.$mp_details->hotel_channel_id.'"/>
													<AvailRateUpdate>';
											if(!empty($up_days)){
												$xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
											}else{
												$xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
											}
											if($room_value->explevel == "rate"){
												$xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
												if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
													$xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
												}else{
													$xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
												}
												if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
													for($i = $minlos; $i<=$maxLos; $i++){
														$xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
																<PerDay rate="'.$update_Details->price.'"/>
																</Rate>';
													}
												}elseif ($mp_details->pricingModel == 'PerDayPricing') {
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
															</Rate> ';
												}
												elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
												{
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
												}
												$xml .= "</RatePlan>";
											}else if($room_value->explevel == "room"){
												$xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
												$available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
												foreach ($available_plans as $e_plan) {
													if($e_plan->rateAcquisitionType != "Linked"){

														if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
															$xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
														}else{
															$xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
														}
														
														if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
															for($i = $minlos; $i<=$maxLos; $i++){
																$xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
																		<PerDay rate="'.$update_Details->price.'"/>
																		</Rate>';
															}
														}elseif ($e_plan->pricingModel == 'PerDayPricing') {
															$xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
																	</Rate> ';
														}
														elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
														{
															$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
															$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
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
												$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline edit',date('m/d/Y h:i:s a', time())); 
												$expedia_update = "Failed";
											}
											else
											{
											   // echo 'success   ';
												$expedia_update = "Success";
											}

											curl_close($ch);

										}

										if($name == "availability" && $room_value->update_availability =='1'){
											$xmlA = '<?xml version="1.0" encoding="UTF-8"?>
												 <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
													<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
													<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
													<Hotel id="'.$mp_details->hotel_channel_id.'"/>
													<AvailRateUpdate>';
											if(!empty($up_days)){
												$xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
											}else{
												$xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
											}
											$xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
											$xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
											if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
												$xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
											}else{
												$xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
											}
											$xmlA .= "</RatePlan>
														</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
											$URL = trim($exp['urate_avail']);
											$ch = curl_init($URL);
											//curl_setopt($ch, CURLOPT_MUTE, 1);
											curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
											curl_setopt($ch, CURLOPT_POST, 1);
											curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
											curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
											$output = curl_exec($ch);
											$data = simplexml_load_string($output); 
											$response = @$data->Error;
											//echo $response;
											if($response!='')
											{
												//echo 'fail';
												$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline edit',date('m/d/Y h:i:s a', time())); 
												$expedia_update = "Failed";
											}
											else
											{
												//echo 'success   ';
												$expedia_update = "Success";
											}

											curl_close($ch);
										}
									   
									}
									else if($room_value->channel_id == '5')
									{
										$date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
										$up_sart_date = str_replace('-', '', $date);

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
												<DateFrom date="'.$up_sart_date.'"/>
												<DateTo date="'.$up_sart_date.'"/>';
										if($update_Details->availability != "" && $room_value->update_availability=='1' && $name=='availability'){
											$xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">';
																		   
										}else{
											$xml_post_string .= '<Room>';
											 
										}
										
										$xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
										if($update_Details->price != "" && $room_value->update_rate == '1' && $name=='price'){
											$xml_post_string .= '<Price><Amount>'.$update_Details->price.'</Amount></Price>';
										}
										$xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
										 </soapenv:Envelope>';
										$headers = array(
										"SOAPAction:no-action",
										"Content-length: ".strlen($xml_post_string),
										); 
										$url = trim($htb['urate_avail']);
										echo 	$xml_post_string;	
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
											$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline edit',date('m/d/Y h:i:s a', time())); 
											echo (string)$status->DetailedMessage;
											//$this->session->set_flashdata('bulk_error',$status->DetailedMessage);
										}elseif($xml->Status != "Y"){
											$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline edit',date('m/d/Y h:i:s a', time())); 
											echo (string)$status->DetailedMessage;
											//$this->session->set_flashdata('bulk_error', "Try Again");
										}

									}
									else if($room_value->channel_id=='8')
									{   
										if($name=='availability' && $room_value->update_availability == '1'){
											$gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
											if($gt_details->mode == 0){
												$urls = explode(',', $gt_details->test_url);
												foreach($urls as $url){
													$path = explode("~",$url);
													$gta[$path[0]] = $path[1];
												}
											}else if($gt_details->mode == 1){
												$urls = explode(',', $gt_details->live_url);
												foreach($urls as $url){
													$path = explode("~",$url);
													$gta[$path[0]] = $path[1];
												}
											}    
										   
											$mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),
											'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,
													'channel_id'=>$room_value->channel_id))->row();
											//$soapUrl = trim($gta['urate_s']);
											$gt_room_id=$mp_details->ID;
											$rateplanid=$mp_details->rateplan_id;
											$MinPax=$mp_details->MinPax;
											$peakrate=$mp_details->peakrate;
											$MaxOccupancy=$mp_details->MaxOccupancy;
											$contract_id=$mp_details->contract_id;
											$minnights=$mp_details->minnights;
											$payfullperiod=$mp_details->payfullperiod;
										 
											$datearr=explode('/',$update_date); 
											$expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];
											$availability= $update_Details->availability; 

											$soapUrl=trim($gta['uavail']);
											$xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
											xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
											xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'"/><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$gt_details->hotel_channel_id.'" ><RoomStyle>';
											$xml_post_string.=' <StayDate Date = "'. $expdate.'">
											<Inventory RoomId="'.$gt_room_id.'" >
											<Detail FreeSale="false" InventoryType="Flexible"
											Quantity="'.$availability.'" ReleaseDays="0"/>
											</Inventory>
											</StayDate>';
											
	 
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
												$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
											} 
										}
										if($name=='price' && $room_value->update_rate=='1')
										{
											$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
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
										
										   $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
											
										   $srat_array=explode('/',$update_date);
										   $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
										   $xml_end_date =$xml_start_date; 

										   $days='1,2,3,4,5,6,7';
										   $days_array=explode(',',$update_Details->days);
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
											}if (in_array("4", $days_array)) {
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
											$pri=$udata['price'];
														  
											$gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
											if($gt_details->mode == 0){
												$urls = explode(',', $gt_details->test_url);
												foreach($urls as $url){
													$path = explode("~",$url);
													$gta[$path[0]] = $path[1];
												}
											}else if($gt_details->mode == 1){
												$urls = explode(',', $gt_details->live_url);
												foreach($urls as $url){
													$path = explode("~",$url);
													$gta[$path[0]] = $path[1];
												}
											}    
											$mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),
											'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,
											'channel_id'=>$room_value->channel_id))->row();
											$soapUrl = trim($gta['urate_s']);
											$gt_room_id=$mp_details->ID;
											$rateplanid=$mp_details->rateplan_id;
											$MinPax=$mp_details->MinPax;
											$peakrate=$mp_details->peakrate;
											$MaxOccupancy=$mp_details->MaxOccupancy;
											$minnights=$mp_details->minnights;
											$payfullperiod=$mp_details->payfullperiod;
											$contract_type=$mp_details->contract_type;
											$value_price=$update_Details->price; 

											if($contract_type=="Static"){ 
												//echo $contract_type;
												$soapUrl = trim($gta['urate_s']);
												$xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
													xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
													xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
													GTA_RateCreateRQ.xsd">
												   <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
													<RatePlan Id="'.$rateplanid.'">
													<StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
													DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
													PeakRate="'.$peakrate.'">
													<StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
													</StaticRate></RatePlan></GTA_StaticRatesCreateRQ>';
												//echo $value_price;
												$ch = curl_init($soapUrl);
												curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
												curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
												curl_setopt($ch, CURLOPT_POST, 1);
												curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
												curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
												$output = curl_exec($ch);
												curl_close($ch);    
												$data = simplexml_load_string($output);
												//print_r($data);
												$Error_Array = @$data->Errors->Error;
												if($Error_Array!='')
												{
													$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
												} 
											}else{
												$soapUrl = trim($gta['urate_m']);

												$xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

												$ch = curl_init($soapUrl);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
												curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

												$response = curl_exec($ch);
												$data = simplexml_load_string($response);

												$Error_Array = @$data->Errors->Error;
												if($Error_Array!='')
												{
													$this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
												}         
											}
										}
									}
									elseif ($room_value->channel_id == '9') 
									{
									    $channle_id     = 9;
									    $airbnb_details = get_data(CONNECT, array(
										'user_id' => current_user_type(),
										'hotel_id' => hotel_id(),
										'channel_id' => $channle_id
									    ))->row();
									    $mp_details     = get_data('import_mapping_AIRBNB', array(
										'user_id' => current_user_type(),
										'hotel_id' => hotel_id(),
										'channel_id' => $room_value->channel_id,
										'import_mapping_id' => $room_value->import_mapping_id
									    ))->row();

									    $start = changeFormat($update_date, 0);
									    $end   = $start;
									    include('cURL.php');

									    $curl                   = new cURL(TRUE);
									    $url                    = 'https://api.airbnb.com/v1/authorize';
									    $formData['client_id']  = $airbnb_details->other_id;
									    $formData['locale']     = 'en-US';
									    $formData['currency']   = 'USD';
									    $formData['grant_type'] = 'password';
									    $formData['username']   = $airbnb_details->user_name;
									    $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);

									    $content = '';
									    foreach ($formData as $key => $value) {
										$content .= '&' . $key . '=' . $value;
									    }
									    $content    = replace_content_hex($content);
									    $resultFile = $curl->post($url, $content);
									    if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
										$AuthToken = $match[1];
										$url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;

										$formData = array();
										if ($name == 'price') {
										    if ($udata['price'] != '0.00') {
											$formData['daily_price']                     = $udata['price'];
											$formData['demand_based_pricing_overridden'] = true;
										    }
										}

										//For Alot Update
										if ($name == 'availability') {
										    if ($udata['availability'] > 0) {
											$formData['availability'] = 'available';
										    } else {
											$formData['availability'] = 'unavailable';
										    }
										}
										if ($name == 'stop_sell') {
										    if ($udata['stop_sell'] == '1') {
											$formData['availability'] = 'unavailable';
										    } else {
											$formData['availability'] = 'available';
										    }
										    if ($udata['availability'] != '') {
											if ($udata['availability'] == 0) {
											    $formData['availability'] = 'unavailable';
											}
										    }
										}

										$content          = json_encode($formData);
										$curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
										$curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";

										$resultFile = put($url, $content, $curl->headers);
										if ($resultFile == '') {

										    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
										} elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {

										    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
										} elseif (preg_match('/error/is', $resultFile, $match)) {

										    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
										}
									    } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
										$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
									    } else {
										$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
									    }
									}
									else if($room_value->channel_id == '2')
									{
										$table = TBL_UPDATE;
										$this->booking_model->inline_edit_main_calendar($table,$room_id,$rate_type_id='0',$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count='0',$refunds='0');
									}
									//$update_Details->price = $original_price;
								}
							}
						}
					}
				}
            }
		}
		if($channel_bnow!='')
		{
			$table = TBL_UPDATE;
			$this->bnow_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
		}
        if($channel_travel!='')
        {
            $table = TBL_UPDATE;
            $this->travel_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
        }
        if($channel_wbeds == 'wbeds')
        {
            $table = TBL_UPDATE;
            $this->wbeds_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        $channels = get_data(MAP,array('property_id'=>$room_id,'hotel_id'=>hotel_id(),'owner_id'=>current_user_type()),'channel_id')->result_array();
        $channelarray = array();
        if(count($channels) != 0)
        {
           foreach ($channels as $channel) {
                if(!in_array($channel['channel_id'], $channelarray)){
                    $channelarray[] = $channel['channel_id'];
                    $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel['channel_id']))->row()->channel_name.',';
                }
           }
        }
        $message = "Location:Calendar Page, Date:".$updateDate.", Room:".$roomname.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
	}
    
    function inline_edit_guest()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_id = explode(',',$date[1]);
        $room_ids = $room_id[0];
        $refund = explode('~',$room_id[1]);
        $guest_count = $refund[0];
        $refunds = $refund[1];
        
        $available = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>'0'))->row_array();
			
        if($name == "price")
        {
            if($refunds=='1')
            {
                $udata['refund_amount'] 	= 	$value;
				
				$column						=	'refund_amount';
			}
            else if($refunds=='2')
            {
                $udata['non_refund_amount'] = 	$value;
				
				$column						=	'non_refund_amount';
            }
			
			$org_price	=	$value;
			
			$update		=	'price';
        }
        if($name == "availability")
		{

            $udata['availability'] 	= 	$value;
			
			$column					=	'availability';
			
			$org_price	 			= 	$value;
			
			$update					=	'availability';
        }
        
        if(count($available)!='0')
        {
            if(update_data(RESERV,$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds)))
            {
                //echo '1';
            }
        }
        else if(count($available=='0'))
        {
            $udata['room_id']       = $room_ids;
            $udata['separate_date'] = $update_date;
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['guest_count']   = $guest_count;
            $udata['refun_type']    = $refunds;
            $udata['individual_channel_id'] = '0';            
            if(insert_data('reservation_table',$udata))
            {
                //echo '1';
            }
        }
		
        $all_channel = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'),'channel_id')->result_array();
		
		$all_channel = array_column($all_channel, 'channel_id');
        $channel_bnow = '';
        $channel_travel = '';
		if (($key = array_search('17', $all_channel)) !== false) 
		{
			unset($all_channel[$key]);
			$channel_bnow = 'bnow';
		}
		if(($key = array_search('15', $all_channel)) !== false)
        {
            unset($all_channel[$key]);
            $channel_travel = 'travel';
        }
        if(($key = array_search('14', $all_channel)) !== false)
        {
            unset($all_channel[$key]);
            $channel_travel = 'wbeds';
        }
        if(count($all_channel!=0))
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
				$available1= get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds))->row_array();
                if($name == "price"){
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                    
                    if($room_mapping){

                        foreach($room_mapping as $room_value){
                            if($room_value->rate_conversion != "1"){
                                if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                    $price = $value * $room_value->rate_conversion;
                                }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                    $mul = str_replace(',', '.', $room_value->rate_conversion);
                                    $price = $value * $mul;
                                }else if(is_numeric($room_value->rate_conversion)){
                                    $price = $value * $room_value->rate_conversion;
                                }
                                if($refunds=='1')
                                {
                                    $udata['refund_amount'] = $price;
                                }
                                else if($refunds=='2')
                                {
                                    $udata['non_refund_amount'] = $price;
                                }
                            }else{
                                if($refunds=='1')
                                {
                                    $udata['refund_amount'] = $value;
                                }
                                else if($refunds=='2')
                                {
                                    $udata['non_refund_amount'] = $value;
                                }
                            }
                        }
                    }else{
                        if($refunds=='1')
                        {
                            $udata['refund_amount'] = $value;
                        }
                        else if($refunds=='2')
                        {
                            $udata['non_refund_amount'] = $value;
                        }
                    }
                }
                 
                if(count($available1)!=0)
                { 
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->where('owner_id', current_user_type());
                    $this->db->where('hotel_id', hotel_id());
                    $this->db->where('room_id', $room_ids);
                    $this->db->where('separate_date', $update_date);
                    $this->db->where('guest_count', $guest_count);
                    $this->db->where('refun_type', $refunds);
                    $this->db->where('individual_channel_id', $con_ch);
                    $this->db->update(RESERV, $udata);
                }
                else
                {
                    $udata['room_id']       = $room_ids;
                    $udata['separate_date'] = $update_date;
                    $udata['owner_id']      = current_user_type();
                    $udata['hotel_id']      = hotel_id();
                    $udata['guest_count']   = $guest_count;
                    $udata['refun_type']    = $refunds;
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->insert(RESERV, $udata);
                }
                $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_ids))->row();
                
                if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                
                $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
                
                if($count!=0)
                {
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                
                    if($room_mapping)
                    {
                        foreach($room_mapping as $room_value)
                        {   
                            if($room_value->channel_id=='11')
                            {
                                $update_Details = get_data(RESERV,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                
                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
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
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

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
                                
                                if($room_value->update_rate=='1')
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
                                        //echo $product['price'];
                                        foreach($set_arr as $k=>$v)
                                        {
                                            if($k == "DoubleOcc" || $k == "TripleOcc" || $k == "DoublePlusChild"){
                                                if(strpos($v, '+') !== FALSE){
                                                    $opr = explode('+', $v);
                                                    if(is_numeric($opr[1])){
                                                        $ex_price = $update_Detailsprice + $opr[1];
                                                    }else if(is_numeric($opr[0])){
                                                        $ex_price = $update_Detailsprice + $opr[0];
                                                    }else{
                                                        if(strpos($opr[1], '%')){
                                                            $per = explode('%',$opr[1]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                                $ex_price = $update_Detailsprice + $per_price;
                                                            }
                                                        }elseif (strpos($opr[0], '%')) {
                                                            $per = explode('%',$opr[0]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                                $ex_price = $update_Detailsprice + $per_price;
                                                            }
                                                        }
                                                    }
                                                }elseif (strpos($v, '-') !== FALSE) {
                                                    $opr = explode('-', $v);
                                                    if(is_numeric($opr[1])){
                                                        $ex_price = $update_Detailsprice - $opr[1];
                                                    }elseif (is_numeric($opr[0])) {
                                                        $ex_price = $update_Detailsprice - $opr[0];
                                                    }else{
                                                        if(strpos($opr[1],'%') !== FALSE){
                                                            $per = explode('%',$opr[1]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                                $ex_price = $update_Detailsprice - $per_price;
                                                            }
                                                        }elseif (strpos($opr[0],'%') !== FALSE) {
                                                            $per = explode('%',$opr[0]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                                $ex_price = $update_Detailsprice - $per_price;
                                                            }
                                                        }
                                                    }
                                                }elseif (strpos($v, '%') !== FALSE) {
                                                    $opr = explode('%', $v);
                                                    if(is_numeric($opr[1])){
                                                        $per_price = ($update_Detailsprice * $opr[1]) / 100;
                                                        $ex_price = $update_Detailsprice + $per_price;
                                                    }elseif (is_numeric($opr[0])) {
                                                        $per_price = ($update_Detailsprice * $opr[0]) / 100;
                                                        $ex_price = $update_Detailsprice + $per_price;
                                                    }
                                                }else{
                                                    $ex_price = $update_Detailsprice + $v;
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
                                    <EndDate>'.$up_sart_date.'</EndDate>
                                    <SingleOcc>'.$update_Detailsprice.'</SingleOcc>'.
                                    $mapping_fields.'
                                    <Meals>'.$meal_plan.'</Meals>
                                    <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                           // $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {   

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                           // $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                    }
                                    curl_close($ch);
                                }
                                /*else if($name=='availability')
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
                                    <Availability>='.$update_Details->availability.'</Availability>
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
                                            $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {      
                                            $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                        return false;
                                    }
                                    curl_close($ch);
                                }*/
                            }
                            elseif($room_value->channel_id=='1')
                            {
                                $update_Details = get_data(RESERV,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                
                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
                                }
                                $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                
                                $up_days =  explode(',',$update_Details->days);
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
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
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
                                if($name=='price' && $room_value->update_rate=='1'){
                                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    if(!empty($up_days)){
                                        $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                    }else{
                                        $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                    }
                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    if($room_value->explevel == "rate"){
                                        if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                            $xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                        }else{
                                            $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                        }
                                        
                                        if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                        <PerDay rate="'.$update_Detailsprice.'"/>
                                                        </Rate>';
                                            }
                                        }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
                                                    </Rate> ';
                                        }
										elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
										{
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
										}
                                        $xml .= "</RatePlan>";
                                    }else if($room_value->explevel == "room"){
                                        $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                        foreach ($available_plans as $e_plan) {
                                            if($e_plan->rateAcquisitionType != "Linked"){

                                                if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                                    $xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                                }else{
                                                    $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                                }
                                                
                                                if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                    for($i = $minlos; $i<=$maxLos; $i++){
                                                        $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                <PerDay rate="'.$update_Detailsprice.'"/>
                                                                </Rate>';
                                                    }
                                                }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
                                                            </Rate> ';
                                                }
												elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
												{
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
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
                                    //echo $response;
                                    if($response!='')
                                    {
                                       // echo 'fail';
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                       // echo 'success   ';
                                        $expedia_update = "Success";
                                    }

                                    curl_close($ch);

                                }

                                if($name == "availability" && $room_value->update_availability =='1'){
                                    $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                         <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    if(!empty($up_days)){
                                        $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                    }else{
                                        $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                    }
                                    $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                                    if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                        $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                    }else{
                                        $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                    }
                                    $xmlA .= "</RatePlan>
                                                </RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                                    $URL = trim($exp['urate_avail']);
                                    $ch = curl_init($URL);
                                    //curl_setopt($ch, CURLOPT_MUTE, 1);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $output = curl_exec($ch);
                                    $data = simplexml_load_string($output); 
                                    $response = $data->Error;
                                    //echo $response;
                                    if($response!='')
                                    {
                                        //echo 'fail';
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                        //echo 'success   ';
                                        $expedia_update = "Success";
                                    }

                                    curl_close($ch);
                                }
                            }
							else if($room_value->channel_id == '5')
							{

                                $update_Details = get_data(RESERV,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                
                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
                                }
                                  
                                $date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                $up_sart_date = str_replace('-', '', $date);

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
                                        <DateFrom date="'.$up_sart_date.'"/>
                                        <DateTo date="'.$up_sart_date.'"/>';
                                if($update_Details->availability != "" && $room_value->update_availability=='1'){
                                    $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">';
                                }else{
                                    $xml_post_string .= '<Room>';                                   
                                }
                                
                                $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                if($update_Detailsprice != "" && $room_value->update_rate == '1'){
                                    $xml_post_string .= '<Price><Amount>'.$update_Detailsprice.'</Amount></Price>';
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
                                
                                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                                $xml_parse = simplexml_load_string($xmlreplace);
                                $json = json_encode($xml_parse);
                                $responseArray = json_decode($json,true);
                                //print_r($responseArray);

                                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                                $status = $xml->ErrorList->Error;
                                if($xml->ErrorList->Error){
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
									echo (string)$status->DetailedMessage;
                                   //$this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                                }else if($xml->Status != "Y"){
                                   $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
								   echo (string)$status->DetailedMessage;
                                    //$this->session->set_flashdata('bulk_error', "Try Again");
                                }                              

                            }
							elseif($room_value->channel_id=='8')
							{
                                if($name=='price' && $room_value->update_rate=='1')
                                {
                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
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
                                
                                   $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
                                    
                                   $srat_array=explode('/',$update_date);
                                   $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
                                   $xml_end_date =$xml_start_date; 
                                   $update_Details = get_data(RESERV,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                
                                    if($update_Details->refun_type=='1')
                                    {
                                        $update_Detailsprice=$update_Details->refund_amount;
                                    }
                                    else if($update_Details->refun_type=='2')
                                    {
                                        $update_Detailsprice=$update_Details->non_refund_amount;
                                    }

                                   $days='1,2,3,4,5,6,7';
                                   $days_array=explode(',',$update_Details->days);
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
                                    }if (in_array("4", $days_array)) {
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
                                                         
                                    $pri=$value;

                                                  
                                    $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();


                                    $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),
                                        'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,
                                            'channel_id'=>$room_value->channel_id))->row();
                                    $soapUrl = trim($gta['urate_s']);
                                    $gt_room_id=$mp_details->ID;
                                    $rateplanid=$mp_details->rateplan_id;
                                    $MinPax=$mp_details->MinPax;
                                    $peakrate=$mp_details->peakrate;
                                    $MaxOccupancy=$mp_details->MaxOccupancy;
                                    $minnights=$mp_details->minnights;
                                    $payfullperiod=$mp_details->payfullperiod;
                                    $contract_type=$mp_details->contract_type;
                                    $value_price=$update_Detailsprice;  

                                    if($contract_type = "Static"){        

                                        $soapUrl = trim($gta['urate_s']);
                                        $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                        xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_RateCreateRQ.xsd">
                                          <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                            <RatePlan Id="'.$rateplanid.'">
                                            <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                            DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                            PeakRate="'.$peakrate.'">
                                            <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                            </StaticRate>
                                            </RatePlan>
                                            </GTA_StaticRatesCreateRQ>';

                                                            
                                        $ch = curl_init($soapUrl);
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
                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        }  
                                    }else{

                                        $soapUrl = trim($gta['urate_m']);

                                        $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                        $ch = curl_init($soapUrl);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                        $response = curl_exec($ch);
                                        $data = simplexml_load_string($response);
                                        $Error_Array = @$data->Errors->Error;
                                        if($Error_Array!='')
                                        {
                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                        }         
                                    }      
                                }

                            }
				 elseif ($room_value->channel_id == '9') {
                                        $channle_id     = $room_value->channel_id;
                                        $airbnb_details = get_data(CONNECT, array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $channle_id
                                        ))->row();
                                        $mp_details     = get_data('import_mapping_AIRBNB', array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $room_value->channel_id,
                                            'import_mapping_id' => $room_value->import_mapping_id
                                        ))->row();
                                        
                                        $start = changeFormat($update_date, 0);
                                        $end   = $start;
                                        include('cURL.php');
                                        
                                        $curl                   = new cURL(TRUE);
                                        $url                    = 'https://api.airbnb.com/v1/authorize';
                                        $formData['client_id']  = $airbnb_details->other_id;
                                        $formData['locale']     = 'en-US';
                                        $formData['currency']   = 'USD';
                                        $formData['grant_type'] = 'password';
                                        $formData['username']   = $airbnb_details->user_name;
                                        $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);
                                        
                                        $content = '';
                                        foreach ($formData as $key => $value) {
                                            $content .= '&' . $key . '=' . $value;
                                        }
                                        $content    = replace_content_hex($content);
                                        $resultFile = $curl->post($url, $content);
                                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                            $AuthToken = $match[1];
                                            $url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;
                                            
                                            $formData = array();
                                            if ($name == 'price') {
                                                if ($udata['price'] != '0.00') {
                                                    $formData['daily_price']                     = $udata['price'];
                                                    $formData['demand_based_pricing_overridden'] = true;
                                                }
                                            }
                                            
                                            //For Alot Update
                                            if ($name == 'availability') {
                                                if ($udata['availability'] > 0) {
                                                    $formData['availability'] = 'available';
                                                } else {
                                                    $formData['availability'] = 'unavailable';
                                                }
                                            }
                                            if ($name == 'stop_sell') {
                                                if ($udata['stop_sell'] == '1') {
                                                    $formData['availability'] = 'unavailable';
                                                } else {
                                                    $formData['availability'] = 'available';
                                                }
                                                if ($udata['availability'] != '') {
                                                    if ($udata['availability'] == 0) {
                                                        $formData['availability'] = 'unavailable';
                                                    }
                                                }
                                            }
                                            
                                            $content          = json_encode($formData);
                                            $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                            
                                            $resultFile = put($url, $content, $curl->headers);
                                            if ($resultFile == '') {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            } elseif (preg_match('/error/is', $resultFile, $match)) {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            }
                                        } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        } else {
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        }
                                    }
				else if($room_value->channel_id == '2')
							{
								$table = RESERV;
								$this->booking_model->inline_edit_main_calendar($table,$room_ids,$rate_type_id='0',$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count,$refunds);
							}
                        }
                    }
                }
				}
            }
        }
		if($channel_bnow!='')
		{
			$table = RESERV;
			$this->bnow_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id=0,$guest_count,$refunds,$column,$update);
		}
        if($channel_travel =='travel')
        {
            $table = RESERV;
            $this->travel_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id=0,$guest_count,$refunds,$column,$update);
        }
        if($channel_travel =='wbeds')
        {
            $table = RESERV;
            $this->wbeds_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id=0,$guest_count,$refunds,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_ids,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        $channels = get_data(MAP,array('property_id'=>$room_ids,'hotel_id'=>hotel_id(),'owner_id'=>current_user_type(),'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'),'channel_id')->result_array();
        $channelarray = array();
        if(count($channels) != 0)
        {
           foreach ($channels as $channel) {
                if(!in_array($channel['channel_id'], $channelarray)){
                    $channelarray[] = $channel['channel_id'];
                    $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel['channel_id']))->row()->channel_name.',';
                }
           }
        }
        $message = "Location:Calendar Page, Date:".$updateDate.", Room:".$roomname.'-'.$guest_count.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
	}
    
    function inline_rate_edit_no()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $room_ids = explode('~',substr($pk, strpos( $pk,"-") + 1));
        $room_id = $room_ids[0]; 
        $rate_type_id=$room_ids[1];
        $date = explode("-", $pk);
        $update_date = $date[0];
        if($name=='price')
        {
            $udata['price'] = $value;
			
			$column					=	'price';
			
			$org_price	 			= 	$value;
			
			$update					=	'price';
        }
        else if($name=='availability')
        {
            $udata['availability']  = 	$value;
			
			$column					=	'availability';
			
			$org_price	 			= 	$value;
			
			$update					=	'availability';
        }
        else if($name=='minimum_stay')
        {
            $udata['minimum_stay'] 	= 	$value;
			
			$column					=	'minimum_stay';
			
			$org_price	 			= 	$value;
			
			$update					=	'minimum_stay';
        }
        
        $available = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'individual_channel_id' => 0))->row_array();
        
        if(count($available)!='0')
        {
            if(update_data(RATE_BASE,$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'individual_channel_id' => 0)))
            {
                //echo '1';
            }
            else
            {
            
            }
        }
        else if(count($available=='0'))
        {
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['room_id']       = $room_id;
            $udata['rate_types_id'] = $rate_type_id;
            $udata['days']          = '1,2,3,4,5,6,7';
            $udata['separate_date'] = $update_date;
            $udata['individual_channel_id'] = 0;
            if(insert_data(RATE_BASE,$udata))
            {
                //echo '1';
            }
            else
            {
            
            }
        }        
        
        $all_channel = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'),'channel_id')->result_array();
		
		$all_channel = array_column($all_channel, 'channel_id');

        $channel_bnow = '';
        $channel_travel = '';
		
		if (($key = array_search('17', $all_channel)) !== false) 
		{
			unset($all_channel[$key]);
			$channel_bnow = 'bnow';
		}
		if (($key = array_search('15', $all_channel)) !== false) 
        {
            unset($all_channel[$key]);
            $channel_travel = 'travel';
        }
        if (($key = array_search('14', $all_channel)) !== false) 
        {
            unset($all_channel[$key]);
            $channel_travel = 'wbeds';
        }
        if(count($all_channel!=0))
        {
            foreach($all_channel as $con_ch)
            {
				//extract($con_ch);
				//$con_ch = $con_ch;
				if($con_ch==2)
				{
					$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row()->xml_type;
				}
				else
				{	
					$chk_allow = '';
				}
				if(($con_ch==2 && ($chk_allow==2 || $chk_allow==3))||$con_ch != 2)
				{
                $available1= get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row_array();
                if($name == "price"){
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                    if($room_mapping){

                        foreach($room_mapping as $room_value){
                            if($room_value->rate_conversion != "1"){
                                if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                    $udata['price'] = $value * $room_value->rate_conversion;
                                }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                    $mul = str_replace(',', '.', $room_value->rate_conversion);
                                    $udata['price'] = $value * $mul;
                                }else if(is_numeric($room_value->rate_conversion)){
                                    $udata['price'] = $value * $room_value->rate_conversion;
                                }  
                            }else{
                                $udata['price'] = $value;
                            }
                        }
                    }else{
                        $udata['price'] = $value;
                    }
                }
                if(count($available1)!=0)
                { 
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->where('owner_id', current_user_type());
                    $this->db->where('hotel_id', hotel_id());
                    $this->db->where('room_id', $room_id);
                    $this->db->where('rate_types_id', $rate_type_id);
                    $this->db->where('separate_date', $update_date);
                    $this->db->where('individual_channel_id', $con_ch);
                    $this->db->update(RATE_BASE, $udata);
                }
                else
                {
                    $udata['owner_id']      = current_user_type();
                    $udata['hotel_id']      = hotel_id();
                    $udata['room_id']       = $room_id;
                    $udata['rate_types_id'] = $rate_type_id;
                    $udata['days']          = '1,2,3,4,5,6,7';
                    $udata['separate_date'] = $update_date;
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->insert(RATE_BASE, $udata);
                }
                if($name=='price' || $name=='availability')
                {
                    $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_id))->row();
                    
                    if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                    
                    $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->count_all_results();
                    
                    if($count!=0)
                    {
                        $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_id,'rate_id'=>$rate_type_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                    
                        if($room_mapping)
                        {
                            foreach($room_mapping as $room_value)
                            {   
                                if($room_value->channel_id=='11')
                                {
                                    $update_Details = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row();
                                    
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
                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
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
                                    
                                    if($name=='price' && $room_value->update_rate=='1')
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
                                                            $ex_price = $update_Details->price + $opr[1];
                                                        }else if(is_numeric($opr[0])){
                                                            $ex_price = $update_Details->price + $opr[0];
                                                        }else{
                                                            if(strpos($opr[1], '%')){
                                                                $per = explode('%',$opr[1]);
                                                                if(is_numeric($per[0])){
                                                                    $per_price = ($update_Details->price * $per[0]) / 100;
                                                                    $ex_price = $update_Details->price + $per_price;
                                                                }
                                                            }elseif (strpos($opr[0], '%')) {
                                                                $per = explode('%',$opr[0]);
                                                                if(is_numeric($per[0])){
                                                                    $per_price = ($update_Details->price * $per[0]) / 100;
                                                                    $ex_price = $update_Details->price + $per_price;
                                                                }
                                                            }
                                                        }
                                                    }elseif (strpos($v, '-') !== FALSE) {
                                                        $opr = explode('-', $v);
                                                        if(is_numeric($opr[1])){
                                                            $ex_price = $update_Details->price - $opr[1];
                                                        }elseif (is_numeric($opr[0])) {
                                                            $ex_price = $update_Details->price - $opr[0];
                                                        }else{
                                                            if(strpos($opr[1],'%') !== FALSE){
                                                                $per = explode('%',$opr[1]);
                                                                if(is_numeric($per[0])){
                                                                    $per_price = ($update_Details->price * $per[0]) / 100;
                                                                    $ex_price = $update_Details->price - $per_price;
                                                                }
                                                            }elseif (strpos($opr[0],'%') !== FALSE) {
                                                                $per = explode('%',$opr[0]);
                                                                if(is_numeric($per[0])){
                                                                    $per_price = ($update_Details->price * $per[0]) / 100;
                                                                    $ex_price = $update_Details->price - $per_price;
                                                                }
                                                            }
                                                        }
                                                    }elseif (strpos($v, '%') !== FALSE) {
                                                        $opr = explode('%', $v);
                                                        if(is_numeric($opr[1])){
                                                            $per_price = ($update_Details->price * $opr[1]) / 100;
                                                            $ex_price = $update_Details->price + $per_price;
                                                        }elseif (is_numeric($opr[0])) {
                                                            $per_price = ($update_Details->price * $opr[0]) / 100;
                                                            $ex_price = $update_Details->price + $per_price;
                                                        }
                                                    }else{
                                                        $ex_price = $update_Details->price + $v;
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
                                        <EndDate>'.$up_sart_date.'</EndDate>
                                        <SingleOcc>'.$update_Details->price.'</SingleOcc>'.
                                        $mapping_fields.'
                                        <Meals>'.$meal_plan.'</Meals>
                                        <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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

                                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Erroarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                               // $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                            }
                                            else if(count($soapFault)!='0')
                                            { 
                                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                              //  $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                            }
                                        }
                                        curl_close($ch);
                                    }
                                    else if($name=='availability' && $room_value->update_availability=='1')
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
                                        <Availability>='.$update_Details->availability.'</Availability>
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

                                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                                //$this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                            }
                                            else if(count($soapFault)!='0')
                                            { 
                                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                               // $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                            }
                                            return false;
                                        }
                                        curl_close($ch);
                                    }
                                }
                                else if($room_value->channel_id=='1')
                                {
                                    $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row();
                                    $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                    
                                    $up_days =  explode(',',$update_Details->days);
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
                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

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
                                    if($name=='price' && $room_value->update_rate=='1'){
                                        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                                <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                                <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                                <AvailRateUpdate>';
                                        if(!empty($up_days)){
                                            $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                        }else{
                                            $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                        }                                        

                                        $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                        if($room_value->explevel == "rate"){
                                            if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                                $xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                            }else{
                                                $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                            }
                                            if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                                for($i = $minlos; $i<=$maxLos; $i++){
                                                    $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                            <PerDay rate="'.$update_Details->price.'"/>
                                                            </Rate>';
                                                }
                                            }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                                $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
                                                        </Rate> ';
                                            }
											elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
											{
												$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
												$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
											}
                                            $xml .= "</RatePlan>";
                                        }else if($room_value->explevel == "room"){

                                            $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                            foreach ($available_plans as $e_plan) {
                                                if($e_plan->rateAcquisitionType != "Linked"){

                                                    if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                                        $xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                                    }else{
                                                        $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                                    }
                                                    if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                        for($i = $minlos; $i<=$maxLos; $i++){
                                                            $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                    <PerDay rate="'.$update_Details->price.'"/>
                                                                    </Rate>';
                                                        }
                                                    }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                                        $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/>
                                                                </Rate> ';
                                                    }
													elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
													{
														$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
														$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
													}
                                                    $xml .= "</RatePlan>";
                                                }
                                            }
                                        }
                                        $xml.="</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
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
                                        //echo $response;
                                        if($response!='')
                                        {
                                           // echo 'fail';

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                            $expedia_update = "Failed";
                                        }
                                        else
                                        {
                                           // echo 'success   ';
                                            $expedia_update = "Success";
                                        }

                                        curl_close($ch);

                                    }

                                    if($name == "availability" && $room_value->update_availability=='1'){
                                        $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                             <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                                <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                                <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                                <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                                <AvailRateUpdate>';
                                        if(!empty($up_days)){
                                            $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                        }else{
                                            $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                        }
                                        $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                        $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                                        if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                            $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                        }else{
                                            $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                        }
                                        $xmlA .= "</RatePlan>
                                                    </RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                                        $URL = trim($exp['urate_avail']);
                                        $ch = curl_init($URL);
                                        //curl_setopt($ch, CURLOPT_MUTE, 1);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        $output = curl_exec($ch);
                                        $data = simplexml_load_string($output); 
                                        $response = $data->Error;
                                        //echo $response;
                                        if($response!='')
                                        {
                                            //echo 'fail';

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                            $expedia_update = "Failed";
                                        }
                                        else
                                        {
                                            //echo 'success   ';
                                            $expedia_update = "Success";
                                        }

                                        curl_close($ch);
                                    }
                                }
								else if($room_value->channel_id == '5')
								{

                                    $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row();
                                    
                                    $date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                    $up_sart_date = str_replace('-', '', $date);

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
                                            <DateFrom date="'.$up_sart_date.'"/>
                                            <DateTo date="'.$up_sart_date.'"/>';
                                    if($update_Details->availability != "" && $room_value->update_availability=='1'){
                                        $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">';
                                                                       
                                    }else{
                                        $xml_post_string .= '<Room>';
                                         
                                    }
                                    
                                    $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                    if($update_Details->price != "" && $room_value->update_rate == '1'){
                                        $xml_post_string .= '<Price><Amount>'.$update_Details->price.'</Amount></Price>';
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
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
										echo (string)$status->DetailedMessage;
                                       // $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                                    }else if($xml->Status != "Y"){
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
										echo (string)$status->DetailedMessage;
                                       // $this->session->set_flashdata('bulk_error', "Try Again");
                                    }

                                }
								else if($room_value->channel_id=='8')
                                {
                                    $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

                                    if($gt_details->mode == 0){
                                        $urls = explode(',', $gt_details->test_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    }else if($gt_details->mode == 1){
                                        $urls = explode(',', $gt_details->live_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    } 

                                    $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$con_ch,'room_id'=>$room_id,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date))->row();
                                    $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                
                                    $xml_start_date=$exp_start_date; 
                                    $xml_end_date= $exp_start_date; 
                           
                                    $up_days =  explode(',',$update_Details->days);
                                    $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
                                    
                                    $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->row();
                                 
                                    $days='1,2,3,4,5,6,7';
                                    $days_array=explode(',',$update_Details->days);
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
                                    }if (in_array("4", $days_array)) {
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

                                    if($name=='availability' && $room_value->update_availability == '1'){

                                       $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
                                       if($gt_details->mode == 0){
                                            $urls = explode(',', $gt_details->test_url);
                                            foreach($urls as $url){
                                                $path = explode("~",$url);
                                                $gta[$path[0]] = $path[1];
                                            }
                                        }else if($gt_details->mode == 1){
                                            $urls = explode(',', $gt_details->live_url);
                                            foreach($urls as $url){
                                                $path = explode("~",$url);
                                                $gta[$path[0]] = $path[1];
                                            }
                                        } 
                                        $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),
                                            'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,
                                                'channel_id'=>$room_value->channel_id))->row();
                                        $soapUrl = trim($gta['uavail']);
                                        $gt_room_id=$mp_details->ID;
                                        $rateplanid=$mp_details->rateplan_id;
                                        $MinPax=$mp_details->MinPax;
                                        $peakrate=$mp_details->peakrate;
                                        $MaxOccupancy=$mp_details->MaxOccupancy;
                                        $minnights=$mp_details->minnights;
                                         $hotel_channel_id=$mp_details->hotel_channel_id;
                                        $contract_id=$mp_details->contract_id;
                                        $payfullperiod=$mp_details->payfullperiod;
                                         
                                        $datearr=explode('/',$update_date); 
                                        $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                                        $availability= $udata['availability']; 

                                        $soapUrl=trim($gta['uavail']);
                                        $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                        xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                        GTA_InventoryUpdateRQ.xsd">
                                              <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                        <InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" >
                                        <RoomStyle>';
                                        $xml_post_string.=' <StayDate Date = "'. $expdate.'">

                                        <Inventory RoomId="'.$gt_room_id.'" >
                                        <Detail FreeSale="false" InventoryType="Flexible"
                                        Quantity="'.$udata['availability'].'" ReleaseDays="0"/>
                                        </Inventory>
                                        </StayDate>';
                                        

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
                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        } 
                                    }
                                    if($name=='price' && $room_value->update_rate == '1'){
                                        $pri=$udata['price'] ;;
                                        $soapUrl = trim($gta['urate_s']);
                                        $gt_room_id=$mp_details->ID;
                                        $rateplanid=$mp_details->rateplan_id;
                                        $MinPax=$mp_details->MinPax;
                                        $peakrate=$mp_details->peakrate;
                                        $MaxOccupancy=$mp_details->MaxOccupancy;
                                        $minnights=$mp_details->minnights;
                                        $payfullperiod=$mp_details->payfullperiod;
                                        $contract_type=$mp_details->contract_type;
                                        $value_price=$value;

                                        if($contract_type == "Static"){
                                            $soapUrl = trim($gta['urate_s']);
                                            $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                            xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                            GTA_RateCreateRQ.xsd">
                                            <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                            <RatePlan Id="'.$rateplanid.'">
                                            <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                            DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                            PeakRate="'.$peakrate.'">
                                            <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                            </StaticRate>
                                            </RatePlan>
                                            </GTA_StaticRatesCreateRQ>';
                                            $ch = curl_init($soapUrl);
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
                                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                            }
                                        }else{
                                            $soapUrl = trim($gta['urate_m']);

                                            $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                            $ch = curl_init($soapUrl);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                            $response = curl_exec($ch);
                                            $data = simplexml_load_string($response);
                                            $Error_Array = @$data->Errors->Error;
                                            if($Error_Array!='')
                                            {
                                                $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                            }         
                                        }  
                                    }
                                }
				    elseif ($room_value->channel_id == '9') {
                                            $channle_id     = $room_value->channel_id;
                                            $airbnb_details = get_data(CONNECT, array(
                                                'user_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'channel_id' => $channle_id
                                            ))->row();
                                            $mp_details     = get_data('import_mapping_AIRBNB', array(
                                                'user_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'channel_id' => $room_value->channel_id,
                                                'import_mapping_id' => $room_value->import_mapping_id
                                            ))->row();
                                            
                                            $start = changeFormat($update_date, 0);
                                            $end   = $start;
                                            include('cURL.php');
                                            
                                            $curl                   = new cURL(TRUE);
                                            $url                    = 'https://api.airbnb.com/v1/authorize';
                                            $formData['client_id']  = $airbnb_details->other_id;
                                            $formData['locale']     = 'en-US';
                                            $formData['currency']   = 'USD';
                                            $formData['grant_type'] = 'password';
                                            $formData['username']   = $airbnb_details->user_name;
                                            $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);
                                            
                                            $content = '';
                                            foreach ($formData as $key => $value) {
                                                $content .= '&' . $key . '=' . $value;
                                            }
                                            $content    = replace_content_hex($content);
                                            $resultFile = $curl->post($url, $content);
                                            if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                                $AuthToken = $match[1];
                                                $url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;
                                                
                                                $formData = array();
                                                if ($name == 'price') {
                                                    if ($udata['price'] != '0.00') {
                                                        $formData['daily_price']                     = $udata['price'];
                                                        $formData['demand_based_pricing_overridden'] = true;
                                                    }
                                                }
                                                
                                                //For Alot Update
                                                if ($name == 'availability') {
                                                    if ($udata['availability'] > 0) {
                                                        $formData['availability'] = 'available';
                                                    } else {
                                                        $formData['availability'] = 'unavailable';
                                                    }
                                                }
                                                if ($name == 'stop_sell') {
                                                    if ($udata['stop_sell'] == '1') {
                                                        $formData['availability'] = 'unavailable';
                                                    } else {
                                                        $formData['availability'] = 'available';
                                                    }
                                                    if ($udata['availability'] != '') {
                                                        if ($udata['availability'] == 0) {
                                                            $formData['availability'] = 'unavailable';
                                                        }
                                                    }
                                                }
                                                
                                                $content          = json_encode($formData);
                                                $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                                                $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                                
                                                $resultFile = put($url, $content, $curl->headers);
                                                if ($resultFile == '') {
                                                    
                                                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                                } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                                    
                                                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                                } elseif (preg_match('/error/is', $resultFile, $match)) {
                                                    
                                                    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                                }
                                            } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            } else {
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            }
                                        }
								else if($room_value->channel_id == '2')
								{
									$table = RATE_BASE;
									$this->booking_model->inline_edit_main_calendar($table,$room_id,$rate_type_id,$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count='0',$refunds='0');
								}
                            }
                        }
                    }
                }
				}
            }
        }
		if($channel_bnow!='')
		{
			$table = RATE_BASE;
			$this->bnow_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id,$guest_count=0,$refunds=0,$column,$update);
		}
        if($channel_travel!='')
        {
            $table = RATE_BASE;
            $this->travel_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id,$guest_count=0,$refunds=0,$column,$update);
        }
        if($channel_travel=='wbeds')
        {
            $table = RATE_BASE;
            $this->wbeds_model->inline_edit_main_calendar($table,$room_id,$update_date,$org_price,$rate_type_id,$guest_count=0,$refunds=0,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $ratename = get_data(RATE_TYPES,array('rate_type_id'=>$rate_type_id,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->rate_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        $channels = get_data(MAP,array('property_id'=>$room_id,'rate_id'=>$rate_type_id,'hotel_id'=>hotel_id(),'owner_id'=>current_user_type(),'enabled'=>'enabled'),'channel_id')->result_array();
        $channelarray = array();
        if(count($channels) != 0)
        {
            foreach ($channels as $channel) {
                if(!in_array($channel['channel_id'], $channelarray)){
                    $channelarray[] = $channel['channel_id'];
                    $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel['channel_id']))->row()->channel_name.',';
                }
            }
        }
        $message = "Location:Calendar Page, Date:".$updateDate.", Room:".$roomname.'-'.$ratename.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
	}
    
    function inline_rate_edit_guest()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
		{
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_id = explode(',',$date[1]);
        $room_ids = $room_id[0];
        $refund = explode('~',$room_id[1]);
        $guest_count = $refund[0];
        $reee = explode('>',$refund[1]);
        $refunds = $reee[0];
        $rate_type_id = $reee[1];
        $available = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>'0'))->row_array();
        if($name = "price")
		{
            if($refunds=='1')
            {
                $udata['refund_amount'] 	= 	$value;
				$column						=	'refund_amount';
            }
            else if($refunds=='2')
            {
                $udata['non_refund_amount']	= 	$value;
				$column						=	'non_refund_amount';
            }
			
			$org_price	=	$value;
			
			$update					=	'price';
        }
        if($name == "availability")
		{
            $udata['availability'] = $value;
			
			$column					=	'availability';
			
			$org_price	 			= 	$value;
			
			$update					=	'availability';
        }
        
        if(count($available)!='0')
        {
            if(update_data(RATE_ADD,$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>0)))
            {
                //echo '1';
            }
            
        }
        else if(count($available=='0'))
        {
            $udata['room_id']       = $room_ids;
            $udata['rate_types_id'] = $rate_type_id;
            $udata['separate_date'] = $update_date;
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['guest_count']   = $guest_count;
            $udata['refun_type']    = $refunds;
            $udata['individual_channel_id'] = '0';
            if($refunds=='1')
            {
                $udata['refund_amount'] = $value;
            }
            else if($refunds=='2')
            {
                $udata['non_refund_amount'] = $value;
            }
            if(insert_data(RATE_ADD,$udata))
            {
                //echo '1';
            }
            
        }

        $all_channel = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'),'channel_id')->result_array();
		
		$all_channel = array_column($all_channel, 'channel_id');
        $channel_bnow = '';
        $channel_travel = '';
		
		if (($key = array_search('17', $all_channel)) !== false) 
		{
			unset($all_channel[$key]);
			$channel_bnow = 'bnow';
		}
		if (($key = array_search('15', $all_channel)) !== false) 
        {
            unset($all_channel[$key]);
            $channel_travel = 'bnow';
        }
		if (($key = array_search('14', $all_channel)) !== false) 
        {
            unset($all_channel[$key]);
            $channel_travel = 'wbeds';
        }
        if(count($all_channel!=0))
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
                $available1= get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds))->row_array();
                 if($name == "price"){
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                    
                    if($room_mapping){

                        foreach($room_mapping as $room_value){
                            if($room_value->rate_conversion != "1"){
                                if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                    $price = $value * $room_value->rate_conversion;
                                }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                    $mul = str_replace(',', '.', $room_value->rate_conversion);
                                    $price = $value * $mul;
                                }else if(is_numeric($room_value->rate_conversion)){
                                    $price = $value * $room_value->rate_conversion;
                                }
                                if($refunds=='1')
                                {
                                    $udata['refund_amount'] = $price;
                                }
                                else if($refunds=='2')
                                {
                                    $udata['non_refund_amount'] = $price;
                                }
                            }else{
                                $price = $value;
                                if($refunds=='1')
                                {
                                    $udata['refund_amount'] = $price;
                                }
                                else if($refunds=='2')
                                {
                                    $udata['non_refund_amount'] = $price;
                                }
                            }
                        }
                    }else{
                        if($refunds=='1')
                        {
                            $udata['refund_amount'] = $value;
                        }
                        else if($refunds=='2')
                        {
                            $udata['non_refund_amount'] = $value;
                        }
                    }
                }
                if(count($available1)!=0)
                { 
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->where('owner_id', current_user_type());
                    $this->db->where('hotel_id', hotel_id());
                    $this->db->where('room_id', $room_ids);
                    $this->db->where('rate_types_id', $rate_type_id);
                    $this->db->where('separate_date', $update_date);
                    $this->db->where('guest_count', $guest_count);
                    $this->db->where('refun_type', $refunds);
                    $this->db->where('individual_channel_id', $con_ch);
                    $this->db->update(RATE_ADD, $udata);
                }
                else
                {
                    $udata['room_id']       = $room_ids;
                    $udata['rate_types_id'] = $rate_type_id;
                    $udata['separate_date'] = $update_date;
                    $udata['owner_id']      = current_user_type();
                    $udata['hotel_id']      = hotel_id();
                    $udata['guest_count']   = $guest_count;
                    $udata['refun_type']    = $refunds;
                    $udata['individual_channel_id'] = $con_ch;
                    $this->db->insert(RATE_ADD, $udata);
                }
                
                $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_ids))->row();
                if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
                
                $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
                
                if($count!=0)
                {
                    $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch,'property_id'=>$room_ids,'rate_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                
                    if($room_mapping)
                    {
                        foreach($room_mapping as $room_value)
                        {   
                            if($room_value->channel_id=='11')
                            {
                                $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                
                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
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
                                                    $ex_price = $update_Detailsprice + $opr[1];
                                                }else if(is_numeric($opr[0])){
                                                    $ex_price = $update_Detailsprice + $opr[0];
                                                }else{
                                                    if(strpos($opr[1], '%')){
                                                        $per = explode('%',$opr[1]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                            $ex_price = $update_Detailsprice + $per_price;
                                                        }
                                                    }elseif (strpos($opr[0], '%')) {
                                                        $per = explode('%',$opr[0]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                            $ex_price = $update_Detailsprice + $per_price;
                                                        }
                                                    }
                                                }
                                            }elseif (strpos($v, '-') !== FALSE) {
                                                $opr = explode('-', $v);
                                                if(is_numeric($opr[1])){
                                                    $ex_price = $update_Detailsprice - $opr[1];
                                                }elseif (is_numeric($opr[0])) {
                                                    $ex_price = $update_Detailsprice - $opr[0];
                                                }else{
                                                    if(strpos($opr[1],'%') !== FALSE){
                                                        $per = explode('%',$opr[1]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                            $ex_price = $update_Detailsprice - $per_price;
                                                        }
                                                    }elseif (strpos($opr[0],'%') !== FALSE) {
                                                        $per = explode('%',$opr[0]);
                                                        if(is_numeric($per[0])){
                                                            $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                            $ex_price = $update_Detailsprice - $per_price;
                                                        }
                                                    }
                                                }
                                            }elseif (strpos($v, '%') !== FALSE) {
                                                $opr = explode('%', $v);
                                                if(is_numeric($opr[1])){
                                                    $per_price = ($update_Detailsprice * $opr[1]) / 100;
                                                    $ex_price = $update_Detailsprice + $per_price;
                                                }elseif (is_numeric($opr[0])) {
                                                    $per_price = ($update_Detailsprice * $opr[0]) / 100;
                                                    $ex_price = $update_Detailsprice + $per_price;
                                                }
                                            }else{
                                                $ex_price = $update_Detailsprice + $v;
                                            }
                                            
                                            $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                        }else{
                                            $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                        }
                                    }
                                }

                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
                                
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
                                
                                if($room_value->update_rate=='1')
                                {
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
                                    <SingleOcc>'.$update_Detailsprice.'</SingleOcc>'.
                                    $mapping_fields.'
                                    <Meals>'.$meal_plan.'</Meals>
                                    <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                           // $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {      

                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                           // $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                    }
                                    curl_close($ch);
                                }
                                /*else if($name=='availability')
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
                                    <Availability>='.$update_Details->availability.'</Availability>
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
                                            $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {      
                                            $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                        return false;
                                    }
                                    curl_close($ch);
                                }*/
                            }
                            else if($room_value->channel_id=='1')
                            {
                                $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
                                }
                                $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                
                                $up_days =  explode(',',$update_Details->days);
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
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

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
                                if($name=='price' && $room_value->update_rate=='1'){
                                    // echo $update_Detailsprice;
                                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    if(!empty($up_days)){
                                        $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                    }else{
                                        $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                    }
                                    
                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    if($room_value->explevel == "rate"){
                                        if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                            $xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                        }else{
                                            $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                        }
                                        if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                        <PerDay rate="'.$update_Detailsprice.'"/>
                                                        </Rate>';
                                            }
                                        }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/>
                                                    </Rate> ';
                                        }
										elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
										{
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
										}
                                        $xml .= "</RatePlan>";
                                    }else if($room_value == "room"){
                                        $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                        foreach ($available_plans as $e_plan) {
                                            if($e_plan->rateAcquisitionType != "Linked"){

                                                if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                                    $xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                                }else{
                                                    $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                                }
                                                if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                    for($i = $minlos; $i<=$maxLos; $i++){
                                                        $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                                <PerDay rate="'.$update_Detailsprice.'"/>
                                                                </Rate>';
                                                    }
                                                }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/>
                                                            </Rate> ';
                                                }
												elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
												{
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
													$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
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
                                    //echo $response;
                                    if($response!='')
                                    {
                                       // echo 'fail';
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                       // echo 'success   ';
                                        $expedia_update = "Success";
                                    }

                                    curl_close($ch);

                                }

                                if($name == "availability" && $room_value->update_availability=='1')
								{
                                    $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                         <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    if(!empty($up_days)){
                                        $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                                    }else{
                                        $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                                    }
                                    $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                                    if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                        $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                    }else{
                                        $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                    }
                                    $xmlA .= "</RatePlan>
                                                </RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                                    $URL = trim($exp['urate_avail']);
                                    $ch = curl_init($URL);
                                    //curl_setopt($ch, CURLOPT_MUTE, 1);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $output = curl_exec($ch);
                                    $data = simplexml_load_string($output); 
                                    $response = $data->Error;
                                    //echo $response;
                                    if($response!='')
                                    {
                                        //echo 'fail';
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                        //echo 'success   ';
                                        $expedia_update = "Success";
                                    }

                                    curl_close($ch);
                                }
                            }
							else if($room_value->channel_id == '5')
							{

                                $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();

                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
                                }
                                $date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                $up_sart_date = str_replace('-', '', $date);

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
                                        <DateFrom date="'.$up_sart_date.'"/>
                                        <DateTo date="'.$up_sart_date.'"/>';
                                if($update_Details->availability != "" && $room_value->update_availability=='1'){
                                    $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">';
                                                                   
                                }else{
                                    $xml_post_string .= '<Room>';
                                     
                                }
                                
                                $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                                if($update_Detailsprice != "" && $room_value->update_rate == '1'){
                                    $xml_post_string .= '<Price><Amount>'.$update_Detailsprice.'</Amount></Price>';
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
									echo (string)$status->DetailedMessage;
                                   // $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                                }else if($xml->Status != "Y"){
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
									echo (string)$status->DetailedMessage;
                                    //$this->session->set_flashdata('bulk_error', "Try Again");
                                }

                            }
							else if($room_value->channel_id=='8')
							{
                                $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();

                                if($gt_details->mode == 0){
                                    $urls = explode(',', $gt_details->test_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $gta[$path[0]] = $path[1];
                                    }
                                }else if($gt_details->mode == 1){
                                    $urls = explode(',', $gt_details->live_url);
                                    foreach($urls as $url){
                                        $path = explode("~",$url);
                                        $gta[$path[0]] = $path[1];
                                    }
                                } 
                                $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$con_ch,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();

                                if($update_Details->refun_type=='1')
                                {
                                    $update_Detailsprice=$update_Details->refund_amount;
                                }
                                else if($update_Details->refun_type=='2')
                                {
                                    $update_Detailsprice=$update_Details->non_refund_amount;
                                }

                                $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                            
                                $xml_start_date=$exp_start_date; 
                                $xml_end_date= $exp_start_date; 
                       
                                $up_days =  explode(',',$update_Details->days);
                                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
                                
                                $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->row();
                             
                                $days='1,2,3,4,5,6,7';
                                $days_array=explode(',',$update_Details->days);
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
                                }if (in_array("4", $days_array)) {
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

                                if($name=='availability' && $room_value->update_availability == '1'){

                                   $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row();
                                   if($gt_details->mode == 0){
                                        $urls = explode(',', $gt_details->test_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    }else if($gt_details->mode == 1){
                                        $urls = explode(',', $gt_details->live_url);
                                        foreach($urls as $url){
                                            $path = explode("~",$url);
                                            $gta[$path[0]] = $path[1];
                                        }
                                    } 
                                    $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),
                                        'hotel_id'=>hotel_id(),'GTA_id'=>$room_value->import_mapping_id,
                                            'channel_id'=>$room_value->channel_id))->row();
                                    $soapUrl = trim($gta['urate_s']);
                                    $gt_room_id=$mp_details->ID;
                                    $rateplanid=$mp_details->rateplan_id;
                                    $MinPax=$mp_details->MinPax;
                                    $peakrate=$mp_details->peakrate;
                                    $MaxOccupancy=$mp_details->MaxOccupancy;
                                    $minnights=$mp_details->minnights;
                                     $hotel_channel_id=$mp_details->hotel_channel_id;
                                    $contract_id=$mp_details->contract_id;
                                    $payfullperiod=$mp_details->payfullperiod;
                                     
                                    $datearr=explode('/',$update_date); 
                                    $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                                    $availability= $update_Details->availability; 

                                    $soapUrl=trim($gta['uavil']);
                                    $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                    GTA_InventoryUpdateRQ.xsd">
                                          <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                    <InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" >
                                    <RoomStyle>';
                                
                                          $xml_post_string.=' <StayDate Date = "'. $expdate.'">

                                    <Inventory RoomId="'.$gt_room_id.'" >
                                    <Detail FreeSale="false" InventoryType="Flexible"
                                    Quantity="'.$availability.'" ReleaseDays="0"/>
                                    </Inventory>
                                    </StayDate>';
                                    

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
                                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    } 
                                }
                                if($name=='price' && $room_value->update_rate == '1'){

                                    $soapUrl = trim($gta['urate_s']);
                                    $gt_room_id=$mp_details->ID;
                                    $rateplanid=$mp_details->rateplan_id;
                                    $MinPax=$mp_details->MinPax;
                                    $peakrate=$mp_details->peakrate;
                                    $MaxOccupancy=$mp_details->MaxOccupancy;
                                    $minnights=$mp_details->minnights;
                                    $payfullperiod=$mp_details->payfullperiod;
                                    $contract_type=$mp_details->contract_type;
                                    $value_price=$update_Detailsprice;

                                    if($contract_type == "Static"){
                                        $soapUrl = trim($gta['urate_s']);
                                        $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                        xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                        GTA_RateCreateRQ.xsd">
                                        <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                        <RatePlan Id="'.$rateplanid.'">
                                        <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                        DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                        PeakRate="'.$peakrate.'">
                                        <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                        </StaticRate>
                                        </RatePlan>
                                        </GTA_StaticRatesCreateRQ>';
                                        $ch = curl_init($soapUrl);
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
                                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                        }
                                    }else{
                                        $soapUrl = trim($gta['urate_m']);

                                        $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                        $ch = curl_init($soapUrl);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                        $response = curl_exec($ch);
                                        $data = simplexml_load_string($response);
                                        $Error_Array = @$data->Errors->Error;
                                        if($Error_Array!='')
                                        {
                                            $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                        }         
                                    }  
                                }

                            }
				elseif ($room_value->channel_id == '9') {
                                        $channle_id = $room_value->channel_id;
                                        $airbnb_details = get_data(CONNECT, array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $channle_id
                                        ))->row();
                                        $mp_details     = get_data('import_mapping_AIRBNB', array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $room_value->channel_id,
                                            'import_mapping_id' => $room_value->import_mapping_id
                                        ))->row();
                                        
                                        $start = changeFormat($update_date, 0);
                                        $end   = $start;
                                        include('cURL.php');
                                        
                                        $curl                   = new cURL(TRUE);
                                        $url                    = 'https://api.airbnb.com/v1/authorize';
                                        $formData['client_id']  = $airbnb_details->other_id;
                                        $formData['locale']     = 'en-US';
                                        $formData['currency']   = 'USD';
                                        $formData['grant_type'] = 'password';
                                        $formData['username']   = $airbnb_details->user_name;
                                        $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);
                                        
                                        $content = '';
                                        foreach ($formData as $key => $value) {
                                            $content .= '&' . $key . '=' . $value;
                                        }
                                        $content    = replace_content_hex($content);
                                        $resultFile = $curl->post($url, $content);
                                        if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                            $AuthToken = $match[1];
                                            $url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;
                                            
                                            $formData = array();
                                            if ($name == 'price') {
                                                if ($udata['price'] != '0.00') {
                                                    $formData['daily_price']                     = $udata['price'];
                                                    $formData['demand_based_pricing_overridden'] = true;
                                                }
                                            }
                                            
                                            //For Alot Update
                                            if ($name == 'availability') {
                                                if ($udata['availability'] > 0) {
                                                    $formData['availability'] = 'available';
                                                } else {
                                                    $formData['availability'] = 'unavailable';
                                                }
                                            }
                                            if ($name == 'stop_sell') {
                                                if ($udata['stop_sell'] == '1') {
                                                    $formData['availability'] = 'unavailable';
                                                } else {
                                                    $formData['availability'] = 'available';
                                                }
                                                if ($udata['availability'] != '') {
                                                    if ($udata['availability'] == 0) {
                                                        $formData['availability'] = 'unavailable';
                                                    }
                                                }
                                            }
                                            
                                            $content          = json_encode($formData);
                                            $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                                            $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                            
                                            $resultFile = put($url, $content, $curl->headers);
                                            if ($resultFile == '') {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            } elseif (preg_match('/error/is', $resultFile, $match)) {
                                                
                                                $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                            }
                                        } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        } else {
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        }
                                    }
							else if($room_value->channel_id == '2')
							{
								$table = RATE_ADD;
								$this->booking_model->inline_edit_main_calendar($table,$room_ids,$rate_type_id,$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count,$refunds);
							}
                        }
                    }
                }
				}
                
            }
        }
		if($channel_bnow!='')
		{
			$table = RATE_ADD;
			$this->bnow_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id,$guest_count,$refunds,$column,$update);
		}
        if($channel_travel =='travel')
        {
            $table = RATE_ADD;
            $this->travel_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id,$guest_count,$refunds,$column,$update);
        }
        if($channel_travel =='wbeds')
        {
            $table = RATE_ADD;
            $this->wbeds_model->inline_edit_main_calendar($table,$room_ids,$update_date,$org_price,$rate_type_id,$guest_count,$refunds,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_ids,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $ratename = get_data(RATE_TYPES,array('rate_type_id'=>$rate_type_id,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->rate_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        $channels = get_data(MAP,array('property_id'=>$room_ids,'rate_id'=>$rate_type_id,'hotel_id'=>hotel_id(),'guest_count'=>$guest_count,'refun_type'=>$refunds,'owner_id'=>current_user_type(),'enabled'=>'enabled'),'channel_id')->result_array();
        $channelarray = array();
        if(count($channels) != 0)
        {
           foreach ($channels as $channel) {
                if(!in_array($channel['channel_id'], $channelarray)){
                    $channelarray[] = $channel['channel_id'];
                    $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel['channel_id']))->row()->channel_name.',';
                }
           }
        }
        $message = "Location:Calendar Page, Date:".$updateDate.", Room:".$roomname.' '.$ratename.' '.$guest_count.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
	}
    
    /*Main Calendar Functionality Stop */
    
    /*Channel Calendar Functionality Start\*/
    
    function inline_edit_channel()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
		{
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_ids = explode(',',$date[1]);
        $refund = explode('~',$room_ids[0]);
        $room_id = $refund[0];
        $gtaroom=  $room_id ;
        $channle_id = $refund[1];
		if($channle_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row()->xml_type;
		}
		else
		{	
			$chk_allow = '';
		}
		if(($channle_id==2 && ($chk_allow==2 || $chk_allow==3))||$channle_id!=2)
		{
			/* echo 'ssss'; */
        if($name=='price')
        {
            $udata['price'] = 	$value;
			
			$update			=	'price';
			
			$column			=	'price';
        }
        else if($name=='availability')
        {
            $udata['availability'] 	=	 $value;
			
			$column					=	'availability';
			
			$update					=	'availability';
        }
        else if($name=='minimum_stay')
        {
            $udata['minimum_stay'] 	= 	$value;
			
			$column					=	'minimum_stay';
			
			$update					=	'minimum_stay';
        }
        $available = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date))->row_array();
        if($name == "price"){
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                 
            if($room_mapping){

                foreach($room_mapping as $room_value){
                    if($room_value->rate_conversion != "1"){
                        if(strpos($room_value->rate_conversion, '.') !== FALSE){
                            $price = $value * $room_value->rate_conversion;
                        }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                            $mul = str_replace(',', '.', $room_value->rate_conversion);
                            $price = $value * $mul;
                        }else if(is_numeric($room_value->rate_conversion)){
                            $price = $value * $room_value->rate_conversion;
                        }
                        $udata['price'] = $price;
                    }else{
                        $udata['price'] = $value;
                    }
                }
            }else{
                $udata['price'] = $value;
            }
        }
        if(count($available)!='0')
        {
            $udata['individual_channel_id'] = $channle_id;
            if(update_data(TBL_UPDATE,$udata,array('individual_channel_id'=>$channle_id,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_id,'separate_date'=>$update_date)))
            {
                //echo '1';
            }
            else
            {
				
            }
        }
        else if(count($available=='0'))
        {
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['room_id']       = $room_id;
            $udata['individual_channel_id']     = $channle_id;
            $udata['days']          = '1,2,3,4,5,6,7';
            $udata['separate_date'] = $update_date;
            if(insert_data(TBL_UPDATE,$udata))
            {
               //echo '1';
            }
            else
            {
				
            }
        }
    
        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_id))->row();
        
        if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
        
		if($channle_id!=17 && $channle_id != 15 && $channle_id != 14)
		{
			$count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->count_all_results();
			if($count!=0)
			{
				$room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
			
				if($room_mapping)
				{
					foreach($room_mapping as $room_value)
					{   
						if($room_value->channel_id=='11')
						{
							if($name=='price' || $name=='availability' )
							{  
							$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row();
							$up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$update_Details->separate_date)));
							
							$up_days =  explode(',',$update_Details->days);
							 
							if(in_array('1', $up_days)) 
							{
								if($update_Details->cta == 1){
									$monday_cta = '01';
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
							$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();

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
							
							if($name=='price' && $room_value->update_rate=='1')
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
													$ex_price = $update_Details->price + $opr[1];
												}else if(is_numeric($opr[0])){
													$ex_price = $update_Details->price + $opr[0];
												}else{
													if(strpos($opr[1], '%')){
														$per = explode('%',$opr[1]);
														if(is_numeric($per[0])){
															$per_price = ($update_Details->price * $per[0]) / 100;
															$ex_price = $update_Details->price + $per_price;
														}
													}elseif (strpos($opr[0], '%')) {
														$per = explode('%',$opr[0]);
														if(is_numeric($per[0])){
															$per_price = ($update_Details->price * $per[0]) / 100;
															$ex_price = $update_Details->price + $per_price;
														}
													}
												}
											}elseif (strpos($v, '-') !== FALSE) {
												$opr = explode('-', $v);
												if(is_numeric($opr[1])){
													$ex_price = $update_Details->price - $opr[1];
												}elseif (is_numeric($opr[0])) {
													$ex_price = $update_Details->price - $opr[0];
												}else{
													if(strpos($opr[1],'%') !== FALSE){
														$per = explode('%',$opr[1]);
														if(is_numeric($per[0])){
															$per_price = ($update_Details->price * $per[0]) / 100;
															$ex_price = $update_Details->price - $per_price;
														}
													}elseif (strpos($opr[0],'%') !== FALSE) {
														$per = explode('%',$opr[0]);
														if(is_numeric($per[0])){
															$per_price = ($update_Details->price * $per[0]) / 100;
															$ex_price = $update_Details->price - $per_price;
														}
													}
												}
											}elseif (strpos($v, '%') !== FALSE) {
												$opr = explode('%', $v);
												if(is_numeric($opr[1])){
													$per_price = ($update_Details->price * $opr[1]) / 100;
													$ex_price = $update_Details->price + $per_price;
												}elseif (is_numeric($opr[0])) {
													$per_price = ($update_Details->price * $opr[0]) / 100;
													$ex_price = $update_Details->price + $per_price;
												}
											}else{
												$ex_price = $update_Details->price + $v;
											}
											echo $ex_price;
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
								<EndDate>'.$up_sart_date.'</EndDate>
								<SingleOcc>'.$update_Details->price.'</SingleOcc>'.
								$mapping_fields.'
								<Meals>'.$meal_plan.'</Meals>
								<MinStay>'.$update_Details->minimum_stay.'</MinStay>
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
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
										$this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
									}
									else if(count($soapFault)!='0')
									{      
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
										$this->session->set_flashdata('bulk_error',$soapFault['soapText']);
									}
								}
								curl_close($ch);
							}
							else if($name=='availability' && $room_value->update_availability=='1')
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
								<Availability>='.$update_Details->availability.'</Availability>
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
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
										echo $Errorarray['WARNING'];
									}
									else if(count($soapFault)!='0')
									{  
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));    
										echo $soapFault['soapText'];
									}
									return false;
								}
								curl_close($ch);
							}
							}
						}
						elseif($room_value->channel_id=='1')
						{
							/* echo 'fsfsd'; */
							$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row();
							$exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
										
							$up_days =  explode(',',$update_Details->days);
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
							$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();
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
							$rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'rateType' => 'SellRate'))->row();
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
							//$name=='price' && $update_Details->price >= (string)$rt_details->minAmount && $update_Details->price <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'                     
							if($name=='price' && $room_value->update_rate=='1'){
								$xml = '<?xml version="1.0" encoding="UTF-8"?>
										<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
										<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
										<Hotel id="'.$mp_details->hotel_channel_id.'"/>
										<AvailRateUpdate>';
								if(!empty($up_days)){
									$xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
								}else{
									$xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
								}
											
								$xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
								if($room_value->explevel == "rate"){
									if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
										$xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
									}else{
										$xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
									}
									/* echo $mp_details->pricingModel; */
									if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
										for($i = $minlos; $i<=$maxLos; $i++){
											$xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
												<PerDay rate="'.$update_Details->price.'"/>
												 </Rate>';
										}
									}elseif ($mp_details->pricingModel == 'PerDayPricing') {
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/></Rate> ';
									}elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
									{
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
									}
									$xml .= "</RatePlan>";
								}else if($room_value->explevel == "room"){

									$available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
									foreach ($available_plans as $e_plan) {
										if($e_plan->rateAcquisitionType != "Linked"){
											if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
												$xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
											}else{
												$xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
											}
											echo $e_plan->pricingModel;
											if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
												for($i = $minlos; $i<=$maxLos; $i++){
													$xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
														<PerDay rate="'.$update_Details->price.'"/>
														 </Rate>';
												}
											}elseif ($mp_details->pricingModel == 'PerDayPricing') {
												$xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/></Rate> ';
											}elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
											{
												$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
												$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
											}
											$xml .= "</RatePlan>";
										}
									}
								}
								$xml .="</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
								$URL = trim($exp['urate_avail']);
								/* echo $URL; */
								/* echo $xml; */
								/* $URL	=	"https://services.expediapartnercentral.com/eqc/ar"; */
								/* echo $URL; */
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
								/* echo $output; */
								if($response!='')
								{
									// echo 'fail';
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
									$expedia_update = "Failed";
								}
								else
								{
									// echo 'success   ';
									$expedia_update = "Success";
								}

								curl_close($ch);
							}

							if($name == "availability" && $room_value->update_availability =='1'){
								$xmlA = '<?xml version="1.0" encoding="UTF-8"?>
										<!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
										<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
										<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
										<Hotel id="'.$mp_details->hotel_channel_id.'"/>
										<AvailRateUpdate>';
								if(!empty($up_days)){
									$xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
								}else{
									$xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
								}
								$xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
								$xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
								if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
									$xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
								}else{
									$xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
								}
								$xmlA .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
								$URL = trim($exp['urate_avail']);
								$ch = curl_init($URL);
								//curl_setopt($ch, CURLOPT_MUTE, 1);
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt($ch, CURLOPT_POST, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
								curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								$output = curl_exec($ch);
								$data = simplexml_load_string($output); 
								$response = $data->Error;
								//echo $response;
								if($response!='')
								{
									//echo 'fail';
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
									$expedia_update = "Failed";
								}
								else
								{
									//echo 'success   ';
									$expedia_update = "Success";
								}
								curl_close($ch);
							}
							if($name == "minimum_stay"){
								$xmlA = '<?xml version="1.0" encoding="UTF-8"?>
										<!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
										<AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
										<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
										<Hotel id="'.$mp_details->hotel_channel_id.'"/>
										<AvailRateUpdate>';
								if(!empty($up_days)){
									$xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
								}else{
									$xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
								}
								$xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
								if($room_value->explevel == "rate"){
									if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
										$xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
									}else{
										$xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
									}
									$xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
								}else if($room_value->explevel == "room"){
									$available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
									foreach ($available_plans as $e_plan) {
										if($e_plan->rateAcquisitionType != "Linked"){
											if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
												$xmlA .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
											}else{
												$xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
											}
											$xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
										}
									}
								}
								$xmlA .='</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>';
								$URL = trim($exp['urate_avail']);
								$ch = curl_init($URL);
								//curl_setopt($ch, CURLOPT_MUTE, 1);
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt($ch, CURLOPT_POST, 1);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
								curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								$output = curl_exec($ch);
								$data = simplexml_load_string($output); 
								$response = $data->Error;
								echo $response;
								if($response!='')
								{
									// echo 'fail';
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
									$expedia_update = "Failed";
								}
								else
								{
									// echo 'success   ';
									$expedia_update = "Success";
								}

								curl_close($ch);
							}
						}
						elseif($room_value->channel_id == '5')
						{

							$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row();
							$up_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));

							$hotelbed_start = str_replace('-', '', $up_sart_date);

							$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();
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
									<DateTo date="'.$hotelbed_start.'"/>';
							if($name == "availability" && $room_value->update_availability=='1'){
								$xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">'; 
							}else{
								$xml_post_string .= '<Room>';
							}
							
							$xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
							if($name == "price" && $room_value->update_rate == '1') {
								$xml_post_string .= '<Price><Amount>'.$update_Details->price.'</Amount></Price>';
							}
							$xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
							 </soapenv:Envelope>';
							$headers = array(
							"SOAPAction:no-action",
							"Content-length: ".strlen($xml_post_string),
							); 
							$url = trim($htb['urate_avail']);
							//echo $xml_post_string;
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
							//print_r($responseArray);

							$xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
							$status = $xml->ErrorList->Error;
							if($xml->ErrorList->Error){
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
								//$this->session->set_flashdata('bulk_error',$status->DetailedMessage);
							}else if($xml->Status != "Y"){
								$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
								$this->session->set_flashdata('bulk_error', "Try Again");
							}

							if($name == "minimum_stay"){
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
									<MinNumberOfDays>'.$update_Details->minimum_stay.'</MinNumberOfDays>
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
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
									$this->session->set_flashdata('bulk_error',$status->DetailedMessage);
								}else if($xml->Status != "Y"){
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
									$this->session->set_flashdata('bulk_error', "Try Again");
								}
							}

						}
						elseif($room_value->channel_id == '8')
						{

							$gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();
							if($gt_details->mode == 0){
								$urls = explode(',', $gt_details->test_url);
								foreach($urls as $url){
									$path = explode("~",$url);
									$gta[$path[0]] = $path[1];
								}
							}else if($gt_details->mode == 1){
								$urls = explode(',', $gt_details->live_url);
								foreach($urls as $url){
									$path = explode("~",$url);
									$gta[$path[0]] = $path[1];
								}
							} 
							$mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

							$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row();

							$gt_room_id=$mp_details->ID;            
							$rateplanid=$mp_details->rateplan_id;
							$MinPax=$mp_details->MinPax;
							$peakrate=$mp_details->peakrate;
							$MaxOccupancy=$mp_details->MaxOccupancy;
							if(@$update['minimum_stay']!='')
							{
								$minnights = $update['minimum_stay'];
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
							// Updateing minum night stay
							/*if($name=='minimum_stay'){

								$datearr=explode('/',$update_date); 
								$expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

								$update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$gtaroom,'separate_date'=>$update_date))->row();
								$up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$update_Details->separate_date)));
								if( $update_Details !=""){
									$minnights=$_POST['value'];
									$value_price=$update_Details->price; 

									$mp_details = get_data(IM_GTA,array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

									$soapUrl = trim($gta['urate_s']);
									$gt_room_id=$mp_details->ID;
									$rateplanid=$mp_details->rateplan_id;
									$MinPax=$mp_details->MinPax;
									$peakrate=$mp_details->peakrate;
									$MaxOccupancy=$mp_details->MaxOccupancy;
									
									$payfullperiod=$mp_details->payfullperiod;
									 
									$soapUrl = trim($gta['urate_s']);
									 $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
									xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
									xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
									GTA_RateCreateRQ.xsd">
									<User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
									<RatePlan Id="'.$rateplanid.'">
									<StaticRate Start="'.$expdate.'" End="'.$expdate.'"
									DaysOfWeek="1111111" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
									PeakRate="'.$peakrate.'">
									<StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
									</StaticRate>
									</RatePlan>
									</GTA_StaticRatesCreateRQ>';
																	 

									$ch = curl_init($soapUrl);
									curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
									curl_setopt($ch, CURLOPT_POST, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
									curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									echo $output = curl_exec($ch);
									curl_close($ch);    
									$data = simplexml_load_string($output);
									$Error_Array = @$data->Errors->Error;
									if($Error_Array!='')
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
									}

								}
							}*/
							if($name=='availability' && $room_value->update_availability == '1'){
								$datearr=explode('/',$update_date); 
								$expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

								$availability= $update_Details->availability; 

								$soapUrl=trim($gta['uavail']);
								$xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd">
										<User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" >
								<RoomStyle>';
							
								$xml_post_string.=' <StayDate Date = "'. $expdate.'">
								<Inventory RoomId="'.$gt_room_id.'" >
								<Detail FreeSale="false" InventoryType="Flexible"
								Quantity="'.$availability.'" ReleaseDays="0"/>
								</Inventory>
								</StayDate>';
								

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
									$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
								}

							}
							// End availability

							if($name=='price' && $room_value->update_rate=='1')
							{
							   $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
							
							   $srat_array=explode('/',$update_date);
							   $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
							   $xml_end_date =$xml_start_date; 
							   $update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row();

							   $days='1,2,3,4,5,6,7';
							   $days_array=explode(',',$update_Details->days);
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
								}if (in_array("4", $days_array)) {
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
								$pri=$update_Details->price;  
								$mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

								//$soapUrl = trim($gta['urate_s']);
								$gt_room_id=$mp_details->ID;
								$rateplanid=$mp_details->rateplan_id;
								$MinPax=$mp_details->MinPax;
								$peakrate=$mp_details->peakrate;
								$MaxOccupancy=$mp_details->MaxOccupancy;
								$minnights=$mp_details->minnights;
								$payfullperiod=$mp_details->payfullperiod;
								$contract_type=$mp_details->contract_type;
								$value_price=$update_Details->price;         
								if($contract_type == "Static"){
									$soapUrl = trim($gta['urate_s']);
									$xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
									xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
									xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
									GTA_RateCreateRQ.xsd">
									<User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
									<RatePlan Id="'.$rateplanid.'">
									<StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
									DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
									PeakRate="'.$peakrate.'">

									<StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
									</StaticRate>
									</RatePlan>
									</GTA_StaticRatesCreateRQ>';  

									$ch = curl_init($soapUrl);
									curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
									curl_setopt($ch, CURLOPT_POST, 1);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
									curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									$output = curl_exec($ch);
									curl_close($ch);    
									$data = simplexml_load_string($output);
									//print_r( $data); exit;
									$Error_Array = @$data->Errors->Error;
									if($Error_Array!='')
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
									}
								}else{
									$soapUrl = trim($gta['urate_m']);

									$xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" MinNights="'.$minnights.'" FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

									$ch = curl_init($soapUrl);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
									curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

									$response = curl_exec($ch);
									$data = simplexml_load_string($response);
									$Error_Array = @$data->Errors->Error;
									//print_r($Error_Array);
									if($Error_Array!='')
									{
										$this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
									}         
								}  
									
							}
						}
						elseif ($room_value->channel_id == '9') {
						    $channle_id = $room_value->channel_id;
						    $airbnb_details = get_data(CONNECT, array(
							'user_id' => current_user_type(),
							'hotel_id' => hotel_id(),
							'channel_id' => $channle_id
						    ))->row();
						    $mp_details     = get_data('import_mapping_AIRBNB', array(
							'user_id' => current_user_type(),
							'hotel_id' => hotel_id(),
							'channel_id' => $room_value->channel_id,
							'import_mapping_id' => $room_value->import_mapping_id
						    ))->row();

						    $start = changeFormat($update_date, 0);
						    $end   = $start;
						    include('cURL.php');

						    $curl                   = new cURL(TRUE);
						    $url                    = 'https://api.airbnb.com/v1/authorize';
						    $formData['client_id']  = $airbnb_details->other_id;
						    $formData['locale']     = 'en-US';
						    $formData['currency']   = 'USD';
						    $formData['grant_type'] = 'password';
						    $formData['username']   = $airbnb_details->user_name;
						    $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);

						    $content = '';
						    foreach ($formData as $key => $value) {
							$content .= '&' . $key . '=' . $value;
						    }
						    $content    = replace_content_hex($content);
						    $resultFile = $curl->post($url, $content);
						    if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
							$AuthToken = $match[1];
							$url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;

							$formData = array();
							if ($name == 'price') {
							    if ($udata['price'] != '0.00') {
								$formData['daily_price']                     = $udata['price'];
								$formData['demand_based_pricing_overridden'] = true;
							    }
							}

							//For Alot Update
							if ($name == 'availability') {
							    if ($udata['availability'] > 0) {
								$formData['availability'] = 'available';
							    } else {
								$formData['availability'] = 'unavailable';
							    }
							}
							if ($name == 'stop_sell') {
							    if ($udata['stop_sell'] == '1') {
								$formData['availability'] = 'unavailable';
							    } else {
								$formData['availability'] = 'available';
							    }
							    if ($udata['availability'] != '') {
								if ($udata['availability'] == 0) {
								    $formData['availability'] = 'unavailable';
								}
							    }
							}

							$content          = json_encode($formData);
							$curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
							$curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";

							$resultFile = put($url, $content, $curl->headers);
							if ($resultFile == '') {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							} elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							} elseif (preg_match('/error/is', $resultFile, $match)) {

							    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
							}
						    } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
							$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
						    } else {
							$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
						    }
						}
						else if($room_value->channel_id == '2')
						{
							$table = TBL_UPDATE;
							$this->booking_model->inline_edit_main_calendar($table,$room_id,$rate_id='0',$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count='0',$refunds='0');
						}
					}
				}
			}
		}
		else if($channle_id==17)
		{
			$table = TBL_UPDATE;
			$this->bnow_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
		}
        else if($channle_id==15)
        {
            $table = TBL_UPDATE;
            $this->travel_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
        }
        else if($channle_id==14)
        {
            $table = TBL_UPDATE;
            $this->wbeds_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_type_id=0,$guest_count=0,$refunds=0,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
       
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        
        $channelsname .= " Channels:";
        $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channle_id))->row()->channel_name.',';
        $channelarray = array();
        
        $message = "Location:Channel Calendar Page, Date:".$updateDate.", Room:".$roomname.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
		else
		{
			echo 'sdsdfffdsd';
		}
		}
	}
    
    function inline_edit_guest_channel()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_id = explode(',',$date[1]);
        $room_ids = $room_id[0];
        $gtaroom=  $room_id ;
        $gtaroom=  $room_id ;
        $refund = explode('~',$room_id[1]);
        $channel = explode('|',$refund[1]);
        $guest_count = $refund[0];
        $refunds = $channel[0];
        $channel_id = $channel[1];
		if($channel_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row()->xml_type;
		}
		else
		{	
			$chk_allow = '';
		}
		if(($channel_id==2 && ($chk_allow==2 || $chk_allow==3))||$channel_id!=2)
		{
        $available = get_data('reservation_table',array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>$channel_id))->row_array();
        if(count($available)!='0')
        {
            if($name=='price')
            {
                if($refunds=='1')
                {
                    $udata['refund_amount'] = $value;
					
					$column	=	'refund_amount';
                }
                else if($refunds=='2')
                {
                    $udata['non_refund_amount'] = $value;
					
					$column	=	'non_refund_amount';
                }

				$update		=	'price';
            }
            else
            {
                $udata['availability'] = $value;
				
				$update		=	'availability';
				
				$column		=	'availability';
            }
			
            if($name == "price"){
                $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                        
                if($room_mapping){

                    foreach($room_mapping as $room_value){
                        if($room_value->rate_conversion != "1"){
                            if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                $price = $value * $room_value->rate_conversion;
                            }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                $mul = str_replace(',', '.', $room_value->rate_conversion);
                                $price = $value * $mul;
                            }else if(is_numeric($room_value->rate_conversion)){
                                $price = $value * $room_value->rate_conversion;
                            }
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }else{
                            $price = $value;
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }
                    }
                }else{
                    if($refunds=='1')
                    {
                        $udata['refund_amount'] = $value;
                    }
                    else if($refunds=='2')
                    {
                        $udata['non_refund_amount'] = $value;
                    }
                }
            }
            $udata['individual_channel_id'] = $channel_id;
            if(update_data('reservation_table',$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>$channel_id)))
            {
                //echo '1';
            }
        }
        else if(count($available=='0'))
        {
            $udata['room_id']       = $room_ids;
            $udata['separate_date'] = $update_date;
            $udata['owner_id']      = current_user_type();
            $udata['hotel_id']      = hotel_id();
            $udata['guest_count']   = $guest_count;
            $udata['refun_type']    = $refunds;
            $udata['individual_channel_id'] = $channel_id;
            if($name=='price')
            {
                if($refunds=='1')
                {
                    $udata['refund_amount'] = $value;
					
					$column	=	'refund_amount';
                }
                else if($refunds=='2')
                {
                    $udata['non_refund_amount'] = $value;
					
					$column	=	'non_refund_amount';
                }
				
				$update		=	'price';
            }
            else if($name=='availability')
            {
                $udata['availability'] = $value;
				
				$column		=	'availability';
				
				$update		=	'availability';
            }
            if($name == "price"){
                $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>0,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                        
                if($room_mapping){

                    foreach($room_mapping as $room_value){
                        if($room_value->rate_conversion != "1"){
                            if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                $price = $value * $room_value->rate_conversion;
                            }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                $mul = str_replace(',', '.', $room_value->rate_conversion);
                                $price = $value * $mul;
                            }else if(is_numeric($room_value->rate_conversion)){
                                $price = $value * $room_value->rate_conversion;
                            }
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }else{
                            $price = $value;
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }
                    }
                }else{
                    if($refunds=='1')
                    {
                        $udata['refund_amount'] = $value;
                    }
                    else if($refunds=='2')
                    {
                        $udata['non_refund_amount'] = $value;
                    }
                }
            }
            if(insert_data('reservation_table',$udata))
            {
                //echo '1';
            }
        }
        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_ids))->row();
                
        if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
        
        $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
        if($channel_id!=17 && $channel_id!=15 && $channel_id != 14)
		{
        if($count!=0)
        {
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>'0','guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
        
            if($room_mapping)
            {
                foreach($room_mapping as $room_value)
                {   
                    if($room_value->channel_id=='11')
                    {
                        $update_Details = get_data(RESERV,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
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
                        
                        if($room_value->update_rate=='1')
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
                                                $ex_price = $update_Detailsprice + $opr[1];
                                            }else if(is_numeric($opr[0])){
                                                $ex_price = $update_Detailsprice + $opr[0];
                                            }else{
                                                if(strpos($opr[1], '%')){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                        $ex_price = $update_Detailsprice + $per_price;
                                                    }
                                                }elseif (strpos($opr[0], '%')) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                        $ex_price = $update_Detailsprice + $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '-') !== FALSE) {
                                            $opr = explode('-', $v);
                                            if(is_numeric($opr[1])){
                                                $ex_price = $update_Detailsprice - $opr[1];
                                            }elseif (is_numeric($opr[0])) {
                                                $ex_price = $update_Detailsprice - $opr[0];
                                            }else{
                                                if(strpos($opr[1],'%') !== FALSE){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                        $ex_price = $update_Detailsprice - $per_price;
                                                    }
                                                }elseif (strpos($opr[0],'%') !== FALSE) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                        $ex_price = $update_Detailsprice - $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '%') !== FALSE) {
                                            $opr = explode('%', $v);
                                            if(is_numeric($opr[1])){
                                                $per_price = ($update_Detailsprice * $opr[1]) / 100;
                                                $ex_price = $update_Detailsprice + $per_price;
                                            }elseif (is_numeric($opr[0])) {
                                                $per_price = ($update_Detailsprice * $opr[0]) / 100;
                                                $ex_price = $update_Detailsprice + $per_price;
                                            }
                                        }else{
                                            $ex_price = $update_Detailsprice + $v;
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
                            <EndDate>'.$up_sart_date.'</EndDate>
                            <SingleOcc>'.$update_Detailsprice.'</SingleOcc>'.
                            $mapping_fields.'
                            <Meals>'.$meal_plan.'</Meals>
                            <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {  
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                            }
                            curl_close($ch);
                        }
                        /*else if($name=='availability')
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
                            <Availability>='.$update_Details->availability.'</Availability>
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
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                                return false;
                            }
                            curl_close($ch);
                        }*/
                    }
                    elseif($room_value->channel_id=='1')
                    {
                        $update_Details = get_data(RESERV,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }
                        $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                    
                        $up_days =  explode(',',$update_Details->days);
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
                        $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>current_user_type(),'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>hotel_id(),'channel'=>$room_value->channel_id,'rateType' => 'SellRate'))->row();
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
                        //$name=='price' && $update_Detailsprice >= (string)$rt_details->minAmount && $update_Detailsprice <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                        if($name=='price' && $room_value->update_rate=='1'){
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                                        
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                    $xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                }else{
                                    $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                }

                                if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                    for($i = $minlos; $i<=$maxLos; $i++){
                                    $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                            <PerDay rate="'.$update_Detailsprice.'"/>
                                             </Rate>';
                                                }
                                }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/></Rate> ';
                                }
								elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
								{
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
								}
                                $xml .= "</RatePlan>";
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                    if($e_plan->rateAcquisitionType != "Linked"){
                                        if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                            $xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                        }else{
                                            $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        }
                                        if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                    <PerDay rate="'.$update_Detailsprice.'"/>
                                                     </Rate>';
                                            }
                                        }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Details->price.'"/></Rate> ';
                                        }
										elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
										{
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Details->price.'" occupancy = "2"/></Rate> ';
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
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
                            //echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                               $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }

                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                            if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                            }else{
                                $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                            }
                            $xmlA .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                            $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            echo $response;
                            if($response!='')
                            {
                                //echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                //echo 'success   ';
                                $expedia_update = "Success";
                            }
                            curl_close($ch);
                        }
                        if($name == "minimum_stay"){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                    $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                }else{
                                    $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                }
                                $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                    if($e_plan->rateAcquisitionType != "Linked"){
                                        if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                            $xmlA .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                        }else{
                                            $xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        }
                                        $xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';   
                                    } 
                                }
                            }
                            $xmlA .= '</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>';
                             $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }
                    }
					elseif($room_value->channel_id == '5')
					{

                        $update_Details = get_data(RESERV,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }

                        $up_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));

                        $hotelbed_start = str_replace('-', '', $up_sart_date);

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
                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">'; 
                        }else{
                            $xml_post_string .= '<Room>';
                        }
                        
                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                        if($name == "price" && $room_value->update_rate == '1') {
                            $xml_post_string .= '<Price><Amount>'.$update_Detailsprice.'</Amount></Price>';
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
                        
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);
                        //print_r($responseArray);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                        $status = $xml->ErrorList->Error;
                        if($xml->ErrorList->Error){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                        }else if($xml->Status != "Y"){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Try Again");
                        }

                        if($name == "minimum_stay"){
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
                                <MinNumberOfDays>'.$update_Details->minimum_stay.'</MinNumberOfDays>
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
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                            }else if($xml->Status != "Y"){
                                 $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error', "Try Again");
                            }
                        }

                    }
					elseif($room_value->channel_id=='8')
                    {
                        $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                        if($gt_details->mode == 0){
                            $urls = explode(',', $gt_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        }else if($gt_details->mode == 1){
                            $urls = explode(',', $gt_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        }             
                        $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->row();
                        $gt_room_id=$mp_details->ID;
                        $rateplanid=$mp_details->rateplan_id;
                        $MinPax=$mp_details->MinPax;
                        $peakrate=$mp_details->peakrate;
                        $MaxOccupancy=$mp_details->MaxOccupancy;
                        $minnights=$mp_details->minnights;
                        $payfullperiod=$mp_details->payfullperiod;
                        $value_price=$update_Detailsprice; 
                        $contract_type=$mp_details->contract_type;
                        $update_Details = get_data(RESERV,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();

                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }

                        if($name=='price' && $room_value->update_rate=='1')
                        {          
                            $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
                                        
                            $srat_array=explode('/',$update_date);
                            $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
                            $xml_end_date =$xml_start_date; 
                            
                            $days='1,2,3,4,5,6,7';
                            $days_array=explode(',',$update_Details->days);
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
                            }if (in_array("4", $days_array)) {
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
                            $mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

                            $soapUrl = trim($gta['urate_s']);
                            
                            if($contract_type == "Static"){         

                                $soapUrl = trim($gta['urate_s']);
                                $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                        xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                        GTA_RateCreateRQ.xsd">
                                      <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                        <RatePlan Id="'.$rateplanid.'">
                                        <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                        DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                        PeakRate="'.$peakrate.'">
                                        <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                        </StaticRate>
                                        </RatePlan>
                                        </GTA_StaticRatesCreateRQ>';  
                                $ch = curl_init($soapUrl);
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                }
                            }else{
                                $soapUrl = trim($gta['urate_m']);

                                $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'"  FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                $ch = curl_init($soapUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                $response = curl_exec($ch);
                                $data = simplexml_load_string($response);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                }         
                            }  

                        } 

                        if($name=='availability' && $room_value->update_availability == '1'){
                            $datearr=explode('/',$update_date); 
                            $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                            $availability= $update_Details->availability; 

                            $soapUrl=trim($gta['uavil']);
                            $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd">
                                    <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$gt_details->hotel_channel_id.'" >
                            <RoomStyle>';
                        
                            $xml_post_string.=' <StayDate Date = "'. $expdate.'">
                            <Inventory RoomId="'.$gt_room_id.'" >
                            <Detail FreeSale="false" InventoryType="Flexible"
                            Quantity="'.$availability.'" ReleaseDays="0"/>
                            </Inventory>
                            </StayDate>';
                            

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
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            }

                        }
                    }
			 elseif ($room_value->channel_id == '9') {
			    $airbnb_details = get_data(CONNECT, array(
				'user_id' => current_user_type(),
				'hotel_id' => hotel_id(),
				'channel_id' => $room_value->channel_id
			    ))->row();
			    $mp_details     = get_data('import_mapping_AIRBNB', array(
				'user_id' => current_user_type(),
				'hotel_id' => hotel_id(),
				'channel_id' => $room_value->channel_id,
				'import_mapping_id' => $room_value->import_mapping_id
			    ))->row();

			    $start = changeFormat($update_date, 0);
			    $end   = $start;
			    include('cURL.php');

			    $curl                   = new cURL(TRUE);
			    $url                    = 'https://api.airbnb.com/v1/authorize';
			    $formData['client_id']  = $airbnb_details->other_id;
			    $formData['locale']     = 'en-US';
			    $formData['currency']   = 'USD';
			    $formData['grant_type'] = 'password';
			    $formData['username']   = $airbnb_details->user_name;
			    $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);

			    $content = '';
			    foreach ($formData as $key => $value) {
				$content .= '&' . $key . '=' . $value;
			    }
			    $content    = replace_content_hex($content);
			    $resultFile = $curl->post($url, $content);
			    if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
				$AuthToken = $match[1];
				$url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;

				$formData = array();
				if ($name == 'price') {
				    if ($udata['price'] != '0.00') {
					$formData['daily_price']                     = $udata['price'];
					$formData['demand_based_pricing_overridden'] = true;
				    }
				}

				//For Alot Update
				if ($name == 'availability') {
				    if ($udata['availability'] > 0) {
					$formData['availability'] = 'available';
				    } else {
					$formData['availability'] = 'unavailable';
				    }
				}
				if ($name == 'stop_sell') {
				    if ($udata['stop_sell'] == '1') {
					$formData['availability'] = 'unavailable';
				    } else {
					$formData['availability'] = 'available';
				    }
				    if ($udata['availability'] != '') {
					if ($udata['availability'] == 0) {
					    $formData['availability'] = 'unavailable';
					}
				    }
				}

				$content          = json_encode($formData);
				$curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
				$curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";

				$resultFile = put($url, $content, $curl->headers);
				if ($resultFile == '') {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				} elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				} elseif (preg_match('/error/is', $resultFile, $match)) {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				}
			    } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
			    } else {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
			    }
			}
					else if($room_value->channel_id == '2')
					{
						$table = RESERV;
						$this->booking_model->inline_edit_main_calendar($table,$room_ids,$rate_id='0',$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count,$refunds);
					}
                }
            }
        }
		}
		else if($channel_id==17)
		{
			$table = RESERV;
			$this->bnow_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_type_id=0,$guest_count,$refunds,$column,$update);
		}
        else if($channel_id==15)
        {
            $table = RESERV;
            $this->travel_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_type_id=0,$guest_count,$refunds,$column,$update);
        }
		else if($channel_id==14)
        {
            $table = RESERV;
            $this->wbeds_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_type_id=0,$guest_count,$refunds,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_ids,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        
        $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row()->channel_name.',';
        
        $message = "Location:Channel Calendar Page, Date:".$updateDate.", Room:".$roomname.'-'.$guest_count.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
		}
	}
    
    function inline_edit_channel_type()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_ids = explode(',',$date[1]);
        $refund = explode('~',$room_ids[0]);
        $room_id = $refund[0];
        $gtaroom=  $room_id ;
        $channle_id = $refund[1];
        $rate_id = $refund[2];
		if($channle_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row()->xml_type;
		}
		else
		{	
			$chk_allow = '';
		}
		if(($channle_id==2 && ($chk_allow==2 || $chk_allow==3))||$channle_id!=2)
		{
        if($name=='price')
        {
            $udata['price']	=	$value;
			
			$column			=	'price';
			
			$update			=	'price';
        }
        else if($name=='availability')
        {
            $udata['availability']	= 	$value;
			
			$column					=	'availability';
			
			$update					=	'availability';
        }
        else if($name=='minimum_stay')
        {
            $udata['minimum_stay']	=	 $value;
			
			$column					=	'minimum_stay';
			
			$update					=	'minimum_stay';
        }
        $available = get_data('room_rate_types_base',array('individual_channel_id'=>$channle_id,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$room_id,'separate_date'=>$update_date))->row_array();
        if($name == "price"){
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>$rate_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                    
            if($room_mapping){

                foreach($room_mapping as $room_value){
                    if($room_value->rate_conversion != "1"){
                        if(strpos($room_value->rate_conversion, '.') !== FALSE){
                            $price = $value * $room_value->rate_conversion;
                        }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                            $mul = str_replace(',', '.', $room_value->rate_conversion);
                            $price = $value * $mul;
                        }else if(is_numeric($room_value->rate_conversion)){
                            $price = $value * $room_value->rate_conversion;
                        }
                        $udata['price'] = $price;
                    }else{
                        $udata['price'] = $value;
                    }
                }
            }else{
                $udata['price'] = $value;
            }
        }
        $udata['individual_channel_id'] = $channle_id;
        if(count($available)!='0')
        {
            if(update_data('room_rate_types_base',$udata,array('individual_channel_id'=>$channle_id,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$room_id,'separate_date'=>$update_date))){ /* echo '1'; */ }
        }
        else if(count($available=='0'))
        {
            $udata['owner_id']        = current_user_type();
            $udata['hotel_id']        = hotel_id();
            $udata['room_id']        = $room_id;
            $udata['rate_types_id']        = $rate_id;
            $udata['individual_channel_id']        = $channle_id;
            $udata['days']          = '1,2,3,4,5,6,7';
            $udata['separate_date'] = $update_date;
            if(insert_data('room_rate_types_base',$udata))
            {
                //echo '1';
            }
            else
            {
    
            }
        }
    
        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_id))->row();
        
        if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
        
        $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>$rate_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->count_all_results();
        if($channle_id!=17 && $channle_id != 15 && $channle_id != 14)
		{
        if($count!=0)
        {
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id,'property_id'=>$room_id,'rate_id'=>$rate_id,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
        
            if($room_mapping)
            {
                foreach($room_mapping as $room_value)
                {   
                    if($room_value->channel_id=='11')
                    {
                        if($name=='price' || $name=='availability'){

                        $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'rate_types_id'=>$rate_id,'separate_date'=>$update_date))->row();
                        
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
                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();
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
                        
                        if($name=='price' && $room_value->update_rate=='1')
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
                                                $ex_price = $update_Details->price + $opr[1];
                                            }else if(is_numeric($opr[0])){
                                                $ex_price = $update_Details->price + $opr[0];
                                            }else{
                                                if(strpos($opr[1], '%')){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Details->price * $per[0]) / 100;
                                                        $ex_price = $update_Details->price + $per_price;
                                                    }
                                                }elseif (strpos($opr[0], '%')) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Details->price * $per[0]) / 100;
                                                        $ex_price = $update_Details->price + $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '-') !== FALSE) {
                                            $opr = explode('-', $v);
                                            if(is_numeric($opr[1])){
                                                $ex_price = $update_Details->price - $opr[1];
                                            }elseif (is_numeric($opr[0])) {
                                                $ex_price = $update_Details->price - $opr[0];
                                            }else{
                                                if(strpos($opr[1],'%') !== FALSE){
                                                    $per = explode('%',$opr[1]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Details->price * $per[0]) / 100;
                                                        $ex_price = $update_Details->price - $per_price;
                                                    }
                                                }elseif (strpos($opr[0],'%') !== FALSE) {
                                                    $per = explode('%',$opr[0]);
                                                    if(is_numeric($per[0])){
                                                        $per_price = ($update_Details->price * $per[0]) / 100;
                                                        $ex_price = $update_Details->price - $per_price;
                                                    }
                                                }
                                            }
                                        }elseif (strpos($v, '%') !== FALSE) {
                                            $opr = explode('%', $v);
                                            if(is_numeric($opr[1])){
                                                $per_price = ($update_Details->price * $opr[1]) / 100;
                                                $ex_price = $update_Details->price + $per_price;
                                            }elseif (is_numeric($opr[0])) {
                                                $per_price = ($update_Details->price * $opr[0]) / 100;
                                                $ex_price = $update_Details->price + $per_price;
                                            }
                                        }else{
                                            $ex_price = $update_Details->price + $v;
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
                            <EndDate>'.$up_sart_date.'</EndDate>
                            <SingleOcc>'.$update_Details->price.'</SingleOcc>'.
                            $mapping_fields.'
                            <Meals>'.$meal_plan.'</Meals>
                            <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {    
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));  
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                            }
                            curl_close($ch);
                        }
                        else if($name=='availability' && $room_value->update_availability=='1')
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
                            <Availability>='.$update_Details->availability.'</Availability>
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                                return false;
                            }
                            curl_close($ch);
                        }
                        }
                    }
                    elseif($room_value->channel_id=='1')
                    {
                        $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'rate_types_id'=>$rate_id,'separate_date'=>$update_date))->row();

                        $update_Detailsprice=$update_Details->price;
                        
                        $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                    
                        $up_days =  explode(',',$update_Details->days);
                        //print_r($up_days);
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
                        if($name=='price' && $room_value->update_rate=='1'){
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                                        
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                    $xml .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                }else{
                                    $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                }
                                if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                    for($i = $minlos; $i<=$maxLos; $i++){
                                    $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                            <PerDay rate="'.$update_Detailsprice.'"/>
                                             </Rate>';
                                                }
                                }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/></Rate> ';
                                }
								elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
								{
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
								}
                                $xml .= "</RatePlan>";
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                    if($e_plan->rateAcquisitionType != "Linked"){

                                        if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                            $xml .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                        }else{
                                            $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        }
                                        if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                            for($i = $minlos; $i<=$maxLos; $i++){
                                                $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                    <PerDay rate="'.$update_Detailsprice.'"/>
                                                     </Rate>';
                                            }
                                        }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                            $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/></Rate> ';
                                        }
										elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
										{
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
											$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
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
                            //echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }

                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                            if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                            }else{
                                $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                            }
                            $xmlA .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                            $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            //echo $response;
                            if($response!='')
                            {
                                //echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                //echo 'success   ';
                                $expedia_update = "Success";
                            }
                            curl_close($ch);
                        }
                        if($name == "minimum_stay"){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                if($mp_details->rateAcquisitionType == "Linked" || $mp_details->rateAcquisitionType == "Derived"){
                                    $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                                }else{
                                    $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                }
                                $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                    if($e_plan->rateAcquisitionType != "Linked"){

                                        if($e_plan->rateAcquisitionType == "Linked" || $e_plan->rateAcquisitionType == "Derived"){
                                            $xmlA .= '<RatePlan id="'.$e_plan->rateplan_id.'">';
                                        }else{
                                            $xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        }
                                        $xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                        $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
                                    }
                                }
                            }
                            $xmlA .= '</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>';
                            $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            //echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }
                    }
					elseif($room_value->channel_id == '5')
					{

                       $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$channle_id,'room_id'=>$room_id,'rate_types_id'=>$rate_id,'separate_date'=>$update_date))->row();

                        $up_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));

                        $hotelbed_start = str_replace('-', '', $up_sart_date);

                        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channle_id))->row();
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
                                <DateTo date="'.$hotelbed_start.'"/>';
                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">'; 
                        }else{
                            $xml_post_string .= '<Room>';
                        }
                        
                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                        if($name == "price" && $room_value->update_rate == '1') {
                            $xml_post_string .= '<Price><Amount>'.$update_Details->price.'</Amount></Price>';
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
                        
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);
                        //print_r($responseArray);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);

                        $status = $xml->ErrorList->Error;
                        if($xml->ErrorList->Error){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                        }else if($xml->Status != "Y"){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Try Again");
                        }

                        if($name == "minimum_stay"){
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
                                <MinNumberOfDays>'.$update_Details->minimum_stay.'</MinNumberOfDays>
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
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                //$this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                            }else if($xml->Status != "Y"){
                                 $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error', "Try Again");
                            }
                        }

                    }
					elseif($room_value->channel_id=='8')
                    {
                        $channel_id = $room_value->channel_id;
                        $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id))->row();
                        if($gt_details->mode == 0){
                            $urls = explode(',', $gt_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $gta[$path[0]] = $path[1];
                            }
                        }else if($gt_details->mode == 1){
                            $urls = explode(',', $gt_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $gta[$path[0]] = $path[1];
                            }
                        }            
                        $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->row();

                        $gt_room_id=$mp_details->ID;            
                        $rateplanid=$mp_details->rateplan_id;
                        $MinPax=$mp_details->MinPax;
                        $peakrate=$mp_details->peakrate;
                        $MaxOccupancy=$mp_details->MaxOccupancy;
                        $update_Details = get_data(RATE_BASE,array('individual_channel_id'=>$channel_id,'room_id'=>$room_id,'rate_types_id'=>$rate_id,'separate_date'=>$update_date))->row();

                        $update_Detailsprice=$update_Details->price;

                        $payfullperiod=$mp_details->payfullperiod;
                        $contract_id=$mp_details->contract_id;  

                        $hotel_channel_id=$mp_details->hotel_channel_id;
                        
                        // Updating availability

                        if($name=='availability' && $room_value->update_availability == '1'){
                            $datearr=explode('/',$update_date); 
                            $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                            $soapUrl=trim($gta['uavil']);
                            $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                            GTA_InventoryUpdateRQ.xsd">
                                  <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                            <InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" >
                            <RoomStyle>';
                        
                                  $xml_post_string.=' <StayDate Date = "'. $expdate.'">

                            <Inventory RoomId="'.$gt_room_id.'" >
                            <Detail FreeSale="true" InventoryType="Flexible"
                            Quantity="'.$update_Details->availability.'" ReleaseDays="1"/>
                            </Inventory>
                            </StayDate>';
                            

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
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            }
                        }
                        if($name=='price' && $room_value->update_rate=='1')
                        {
                           $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
                        
                           $srat_array=explode('/',$update_date);
                           $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
                           $xml_end_date =$xml_start_date; 

                           $days='1,2,3,4,5,6,7';
                           $days_array=explode(',',$update_Details->days);
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
                            }if (in_array("4", $days_array)) {
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
                           $mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

                            $soapUrl = trim($gta['urate_s']);
                            $gt_room_id=$mp_details->ID;
                            $rateplanid=$mp_details->rateplan_id;
                            $MinPax=$mp_details->MinPax;
                            $peakrate=$mp_details->peakrate;
                            $MaxOccupancy=$mp_details->MaxOccupancy;
                            $minnights=$mp_details->minnights;
                            $payfullperiod=$mp_details->payfullperiod;
                            $value_price=$update_Detailsprice; 
                            $contract_type=$mp_details->contract_type;         

                            if($contract_type == "Static"){
                                $soapUrl = trim($gta['urate_s']);
                                $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                GTA_RateCreateRQ.xsd">
                              <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                <RatePlan Id="'.$rateplanid.'">
                                <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                PeakRate="'.$peakrate.'">
                                <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                </StaticRate>
                                </RatePlan>
                                </GTA_StaticRatesCreateRQ>';
                                                                             

                                $ch = curl_init($soapUrl);
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                }
                            }else{
                                $soapUrl = trim($gta['urate_m']);

                                $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'"  FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                $ch = curl_init($soapUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                $response = curl_exec($ch);

                                $data = simplexml_load_string($response);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                }         
                            }
                        }   
                    }
			 elseif ($room_value->channel_id == '9') {
			    $airbnb_details = get_data(CONNECT, array(
				'user_id' => current_user_type(),
				'hotel_id' => hotel_id(),
				'channel_id' => $room_value->channel_id
			    ))->row();
			    $mp_details     = get_data('import_mapping_AIRBNB', array(
				'user_id' => current_user_type(),
				'hotel_id' => hotel_id(),
				'channel_id' => $room_value->channel_id,
				'import_mapping_id' => $room_value->import_mapping_id
			    ))->row();

			    $start = changeFormat($update_date, 0);
			    $end   = $start;
			    include('cURL.php');

			    $curl                   = new cURL(TRUE);
			    $url                    = 'https://api.airbnb.com/v1/authorize';
			    $formData['client_id']  = $airbnb_details->other_id;
			    $formData['locale']     = 'en-US';
			    $formData['currency']   = 'USD';
			    $formData['grant_type'] = 'password';
			    $formData['username']   = $airbnb_details->user_name;
			    $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);

			    $content = '';
			    foreach ($formData as $key => $value) {
				$content .= '&' . $key . '=' . $value;
			    }
			    $content    = replace_content_hex($content);
			    $resultFile = $curl->post($url, $content);
			    if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
				$AuthToken = $match[1];
				$url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;

				$formData = array();
				if ($name == 'price') {
				    if ($udata['price'] != '0.00') {
					$formData['daily_price']                     = $udata['price'];
					$formData['demand_based_pricing_overridden'] = true;
				    }
				}

				//For Alot Update
				if ($name == 'availability') {
				    if ($udata['availability'] > 0) {
					$formData['availability'] = 'available';
				    } else {
					$formData['availability'] = 'unavailable';
				    }
				}
				if ($name == 'stop_sell') {
				    if ($udata['stop_sell'] == '1') {
					$formData['availability'] = 'unavailable';
				    } else {
					$formData['availability'] = 'available';
				    }
				    if ($udata['availability'] != '') {
					if ($udata['availability'] == 0) {
					    $formData['availability'] = 'unavailable';
					}
				    }
				}

				$content          = json_encode($formData);
				$curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
				$curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";

				$resultFile = put($url, $content, $curl->headers);
				if ($resultFile == '') {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				} elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				} elseif (preg_match('/error/is', $resultFile, $match)) {

				    $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
				}
			    } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
			    } else {
				$this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
			    }
			}
					else if($room_value->channel_id == '2')
					{
						$table = RATE_BASE;
						$this->booking_model->inline_edit_main_calendar($table,$room_id,$rate_id,$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count='0',$refunds='0');
					}
                }
            }
        }
		}
		else if($channle_id==17)
		{
			$table = RATE_BASE;
			$this->bnow_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_id,$guest_count=0,$refunds=0,$column,$update);
		}
        else if($channle_id==15)
        {
            $table = RATE_BASE;
            $this->travel_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_id,$guest_count=0,$refunds=0,$column,$update);
        }
        else if($channle_id==14)
        {
            $table = RATE_BASE;
            $this->wbeds_model->inline_edit_channel_calendar($table,$room_id,$update_date,$rate_id,$guest_count=0,$refunds=0,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $ratename = get_data(RATE_TYPES,array('rate_type_id'=>$rate_id,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->rate_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        
        $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channle_id))->row()->channel_name.',';
        $message = "Location:Channel Calendar Page, Date:".$updateDate.", Room:".$roomname.'-'.$ratename.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
		}
	}
    
    function inline_edit_guest_channel_type()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
		{
        extract($this->input->post());
        $date = explode("-", $pk);
        $update_date = $date[0];
        $room_id = explode(',',$date[1]);
        $gtaroom=  $room_id ;
        $room_ids = $room_id[0];
        $refund = explode('~',$room_id[1]);
        $channel = explode('|',$refund[1]);
        $guest_count = $refund[0];
        $refunds = $channel[0];
        $channel_id = $channel[1];
        $rate_id = $channel[2];
		if($channel_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row()->xml_type;
		}
		else
		{	
			$chk_allow = '';
		}
		if(($channel_id==2 && ($chk_allow==2 || $chk_allow==3))||$channel_id!=2)
		{
        $available = get_data('room_rate_types_additional',array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'rate_types_id'=>$rate_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>$channel_id))->row_array();
        if(count($available)!='0')
        {
            if($name=='price')
            {
                if($refunds=='1')
                {
                    $udata['refund_amount'] = $value;
					
					$column	=	'refund_amount';
                }
                else if($refunds=='2')
                {
                    $udata['non_refund_amount'] = $value;
					
					$column	=	'non_refund_amount';
                }
				
				$update	=	'price';
            }
            else
            {
                $udata['availability'] = $value;
				
				$column	=	'availability';
				
				$update	=	'availability';
            }
            if($name == "price"){
                $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                        
                if($room_mapping){

                    foreach($room_mapping as $room_value){
                        if($room_value->rate_conversion != "1"){
                            if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                $price = $value * $room_value->rate_conversion;
                            }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                $mul = str_replace(',', '.', $room_value->rate_conversion);
                                $price = $value * $mul;
                            }else if(is_numeric($room_value->rate_conversion)){
                                $price = $value * $room_value->rate_conversion;
                            }
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }else{
                            $price = $value;
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }
                    }
                }else{
                    if($refunds=='1')
                    {
                        $udata['refund_amount'] = $value;
                    }
                    else if($refunds=='2')
                    {
                        $udata['non_refund_amount'] = $value;
                    }
                }
            }
            $udata['individual_channel_id'] = $channel_id;
            if(update_data('room_rate_types_additional',$udata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$room_ids,'rate_types_id'=>$rate_id,'separate_date'=>$update_date,'guest_count'=>$guest_count,'refun_type'=>$refunds,'individual_channel_id'=>$channel_id)))
            {
                //echo '1';
            }
        }
        else if(count($available=='0'))
        {
            $udata['room_id']         = $room_ids;
            $udata['separate_date'] = $update_date;
            $udata['owner_id']        = current_user_type();
            $udata['hotel_id']        = hotel_id();
            $udata['guest_count']     = $guest_count;
            $udata['refun_type']     = $refunds;
            $udata['rate_types_id']     = $rate_id;
            $udata['individual_channel_id'] = $channel_id;
            if($name=='price')
            {
                if($refunds=='1')
                {
                    $udata['refund_amount'] = $value;
					
					$column	=	'refund_amount';
                }
                else if($refunds=='2')
                {
                    $udata['non_refund_amount'] = $value;
					
					$column	=	'non_refund_amount';
                }
				
				$update	=	'price';
            }
            else if($name=='availability')
            {
                $udata['availability'] = $value;
				
				$column	=	'availability';
				
				$update	=	'availability';
            }

            if($name == "price"){
                $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
                        
                if($room_mapping){

                    foreach($room_mapping as $room_value){
                        if($room_value->rate_conversion != "1"){
                            if(strpos($room_value->rate_conversion, '.') !== FALSE){
                                $price = $value * $room_value->rate_conversion;
                            }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                                $mul = str_replace(',', '.', $room_value->rate_conversion);
                                $price = $value * $mul;
                            }else if(is_numeric($room_value->rate_conversion)){
                                $price = $value * $room_value->rate_conversion;
                            }
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }else{
                            $price = $value;
                            if($refunds=='1')
                            {
                                $udata['refund_amount'] = $price;
                            }
                            else if($refunds=='2')
                            {
                                $udata['non_refund_amount'] = $price;
                            }
                        }
                    }
                }else{
                    if($refunds=='1')
                    {
                        $udata['refund_amount'] = $value;
                    }
                    else if($refunds=='2')
                    {
                        $udata['non_refund_amount'] = $value;
                    }
                }
            }
            if(insert_data('room_rate_types_additional',$udata))
            {
                //echo '1';
            }
        }
        
        $room_details = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>$room_ids))->row();
                
        if($room_details->meal_plan=='1' || $room_details->meal_plan=='0'){$meal_plan=0;}elseif($room_details->meal_plan=='2'){$meal_plan=1;}elseif($room_details->meal_plan=='3'){$meal_plan=3;}elseif($room_details->meal_plan=='4'){$meal_plan=0;}elseif($room_details->meal_plan=='5' || $room_details->meal_plan=='6'){$meal_plan=2;}
        
        $count = $this->db->select('mapping_id')->from(MAP)->where(array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->count_all_results();
        if($channel_id!=17 && $channel_id!=15 && $channel_id != 14)
		{
        if($count!=0)
        {
            $room_mapping = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id,'property_id'=>$room_ids,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'enabled'=>'enabled'))->result();
        
            if($room_mapping)
            {
                foreach($room_mapping as $room_value)
                {   
                    if($room_value->channel_id=='11')
                    {
                        $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'rate_types_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
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
                                            $ex_price = $update_Detailsprice + $opr[1];
                                        }else if(is_numeric($opr[0])){
                                            $ex_price = $update_Detailsprice + $opr[0];
                                        }else{
                                            if(strpos($opr[1], '%')){
                                                $per = explode('%',$opr[1]);
                                                if(is_numeric($per[0])){
                                                    $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                    $ex_price = $update_Detailsprice + $per_price;
                                                }
                                            }elseif (strpos($opr[0], '%')) {
                                                $per = explode('%',$opr[0]);
                                                if(is_numeric($per[0])){
                                                    $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                    $ex_price = $update_Detailsprice + $per_price;
                                                }
                                            }
                                        }
                                    }elseif (strpos($v, '-') !== FALSE) {
                                        $opr = explode('-', $v);
                                        if(is_numeric($opr[1])){
                                            $ex_price = $update_Detailsprice - $opr[1];
                                        }elseif (is_numeric($opr[0])) {
                                            $ex_price = $update_Detailsprice - $opr[0];
                                        }else{
                                            if(strpos($opr[1],'%') !== FALSE){
                                                $per = explode('%',$opr[1]);
                                                if(is_numeric($per[0])){
                                                    $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                    $ex_price = $update_Detailsprice - $per_price;
                                                }
                                            }elseif (strpos($opr[0],'%') !== FALSE) {
                                                $per = explode('%',$opr[0]);
                                                if(is_numeric($per[0])){
                                                    $per_price = ($update_Detailsprice * $per[0]) / 100;
                                                    $ex_price = $update_Detailsprice - $per_price;
                                                }
                                            }
                                        }
                                    }elseif (strpos($v, '%') !== FALSE) {
                                        $opr = explode('%', $v);
                                        if(is_numeric($opr[1])){
                                            $per_price = ($update_Detailsprice * $opr[1]) / 100;
                                            $ex_price = $update_Detailsprice + $per_price;
                                        }elseif (is_numeric($opr[0])) {
                                            $per_price = ($update_Detailsprice * $opr[0]) / 100;
                                            $ex_price = $update_Detailsprice + $per_price;
                                        }
                                    }else{
                                        $ex_price = $update_Detailsprice + $v;
                                    }
                                    
                                    $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                }else{
                                    $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                }
                            }
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
                        
                        if($room_value->update_rate=='1')
                        {
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
                            <SingleOcc>'.$update_Detailsprice.'</SingleOcc>'.
                            $mapping_fields.'
                            <Meals>'.$meal_plan.'</Meals>
                            <MinStay>'.$update_Details->minimum_stay.'</MinStay>
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Errorarray['WARNING'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$soapFault['soapText'],'Inline Edit',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                            }
                            curl_close($ch);
                        }
                        /*else if($name=='availability')
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
                            <Availability>='.$update_Details->availability.'</Availability>
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
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {      
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                                return false;
                            }
                            curl_close($ch);
                        }*/
                    }
                    elseif($room_value->channel_id=='1')
                    {
                        $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'rate_types_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }
                        $exp_start_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));
                                    
                        $up_days =  explode(',',$update_Details->days);
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
                        if($name=='price' && $room_value->update_rate=='1'){
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xml .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xml .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                                        
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                $xml .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){
                                    for($i = $minlos; $i<=$maxLos; $i++){
                                    $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                            <PerDay rate="'.$update_Detailsprice.'"/>
                                             </Rate>';
                                                }
                                }elseif ($mp_details->pricingModel == 'PerDayPricing') {
                                    $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/></Rate> ';
                                }
								elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
								{
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
									$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
								}
                                $xml .= "</RatePlan>";
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                     $xml .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                    if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                        for($i = $minlos; $i<=$maxLos; $i++){
                                        $xml .= '<Rate lengthOfStay="'.$i.'" currency="'.$this->currency_code.'">
                                                <PerDay rate="'.$update_Detailsprice.'"/>
                                                 </Rate>';
                                                    }
                                    }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                        $xml .= '<Rate currency="'.$this->currency_code.'"> <PerDay rate="'.$update_Detailsprice.'"/></Rate> ';
                                    }
									elseif($mp_details->pricingModel == 'OccupancyBasedPricing')
									{
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$update_Detailsprice.'" occupancy = "2"/></Rate> ';
										$xml .= '<Rate currency="'.$this->currency_code.'"> <PerOccupancy rate="'.$room_value->exp_occupancy.'" occupancy = "1"/></Rate> ';
									}
                                    $xml .= "</RatePlan>";
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
                            //echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }

                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            $xmlA .= '<Inventory totalInventoryAvailable="'.$update_Details->availability.'"/>';
                            if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                $xmlA .= '<RatePlan id="'.$mp_details->rateplan_id.'">';
                            }else{
                                $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                            }
                            $xmlA .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                            $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            //echo $response;
                            if($response!='')
                            {
                                //echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                //echo 'success   ';
                                $expedia_update = "Success";
                            }
                            curl_close($ch);
                        }
                        if($name == "minimum_stay"){
                            $xmlA = '<?xml version="1.0" encoding="UTF-8"?>
                                    <!--Sample AR request message:updating rates and restrictions, triggering warnings in the response, for August 2012-->
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            if(!empty($up_days)){
                                $xmlA .= '<DateRange from="'.$exp_start_date.'" to="'.$exp_start_date.'"/>';
                            }else{
                                $xmlA .= '<DateRange from="'.$exp_sart_date.'" to="'.$exp_start_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                            }
                            $xmlA .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            if($room_value->explevel == "rate"){
                                $xmlA .= '<RatePlan id="'.$mp_details->rate_type_id.'">';
                                $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
                            }else if($room_value->explevel == "room"){
                                $available_plans = $this->db->query("SELECT * FROM import_mapping WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                                foreach ($available_plans as $e_plan) {
                                    $xmlA .= '<RatePlan id="'.$e_plan->rate_type_id.'">';
                                    $xmlA .= '<Restrictions minLOS="'.$update_Details->minimum_stay.'" maxLOS="'.$maxLos.'"/></RatePlan>';
                                }
                            }
                            $xmlA .= '</RoomType></AvailRateUpdate> </AvailRateUpdateRQ>';
                             $URL = trim($exp['urate_avail']);
                            $ch = curl_init($URL);
                            //curl_setopt($ch, CURLOPT_MUTE, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$xmlA");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $data = simplexml_load_string($output); 
                            $response = $data->Error;
                            //echo $response;
                            if($response!='')
                            {
                                // echo 'fail';
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$response,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                                // echo 'success   ';
                                $expedia_update = "Success";
                            }

                            curl_close($ch);
                        }
                    }
					elseif($room_value->channel_id == '5')
					{
                        $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'rate_types_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }

                        $up_sart_date = date('Y-m-d',strtotime(str_replace('/','-',$update_Details->separate_date)));

                        $hotelbed_start = str_replace('-', '', $up_sart_date);

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
                        if($name == "availability" && $room_value->update_availability=='1'){
                            $xml_post_string .= '<Room available="'.$update_Details->availability.'" quote="'.$update_Details->availability.'">'; 
                        }else{
                            $xml_post_string .= '<Room>';
                        }
                        
                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                        if($name == "price" && $room_value->update_rate == '1') {
                            $xml_post_string .= '<Price><Amount>'.$update_Detailsprice.'</Amount></Price>';
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
                        
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);
                        //print_r($responseArray);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventoryModification']);
                        
                        $status = $xml->ErrorList->Error;
                        if($xml->ErrorList->Error){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                        }else if($xml->Status != "Y"){
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            $this->session->set_flashdata('bulk_error', "Try Again");
                        }

                        if($name == "minimum_stay"){
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
                                <MinNumberOfDays>'.$update_Details->minimum_stay.'</MinNumberOfDays>
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
                            if($status != ""){
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$status->DetailedMessage,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error',$status->DetailedMessage);
                            }else if($xml->Status != "Y"){
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$xml->Status,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error', "Try Again");
                            }
                        }

                    }
					elseif($room_value->channel_id=='8')
                    {
                        $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row();
                        if($gt_details->mode == 0){
                            $urls = explode(',', $gt_details->test_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        }else if($gt_details->mode == 1){
                            $urls = explode(',', $gt_details->live_url);
                            foreach($urls as $url){
                                $path = explode("~",$url);
                                $reco[$path[0]] = $path[1];
                            }
                        }            
                        $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->row();

                        $update_Details = get_data(RATE_ADD,array('individual_channel_id'=>$channel_id,'room_id'=>$room_ids,'rate_types_id'=>$rate_type_id,'guest_count'=>$guest_count,'refun_type'=>$refunds,'separate_date'=>$update_date))->row();
                        
                        if($update_Details->refun_type=='1')
                        {
                            $update_Detailsprice=$update_Details->refund_amount;
                        }
                        else if($update_Details->refun_type=='2')
                        {
                            $update_Detailsprice=$update_Details->non_refund_amount;
                        }

                        $gt_room_id=$mp_details->ID;            
                        $rateplanid=$mp_details->rateplan_id;
                        $MinPax=$mp_details->MinPax;
                        $peakrate=$mp_details->peakrate;
                        $MaxOccupancy=$mp_details->MaxOccupancy;
                        
                        $payfullperiod=$mp_details->payfullperiod;
                        $contract_id=$mp_details->contract_id;  
                        $hotel_channel_id=$mp_details->hotel_channel_id;

                                    // Updateing minum night stay
                        /*if($name=='minimum_stay'){

                            $datearr=explode('/',$update_date); 
                                         $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                            $update_Details = get_data(TBL_UPDATE,array('individual_channel_id'=>$channle_id,'room_id'=>$gtaroom,'separate_date'=>$update_date))->row();
                            $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$update_Details->separate_date)));
                            if($update_Details !=""){
                                $minnights=$_POST['value'];
                                $value_price=$update_Details->price; 

                                $mp_details = get_data(IM_GTA,array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

                                $soapUrl = trim($gta['urate_s']);
                                $gt_room_id=$mp_details->ID;
                                $rateplanid=$mp_details->rateplan_id;
                                $MinPax=$mp_details->MinPax;
                                $peakrate=$mp_details->peakrate;
                                $MaxOccupancy=$mp_details->MaxOccupancy;
                                
                                $payfullperiod=$mp_details->payfullperiod;
                                             
                                $soapUrl = trim($gta['urate_s']);
                                $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                            xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                                            GTA_RateCreateRQ.xsd">
                                          <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                            <RatePlan Id="'.$rateplanid.'">
                                            <StaticRate Start="'.$expdate.'" End="'.$expdate.'"
                                            DaysOfWeek="1111111" MinNights="'.$minnights.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                            PeakRate="'.$peakrate.'">
                                            <StaticRoomRate RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                            </StaticRate>
                                            </RatePlan>
                                            </GTA_StaticRatesCreateRQ>';
                                                                             

                                $ch = curl_init($soapUrl);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                echo $output = curl_exec($ch);
                                curl_close($ch);    
                                $data = simplexml_load_string($output);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                }
                            } 
                        }*/
                        if($name=='availability' && $room_value->update_availability == '1'){
                            $datearr=explode('/',$update_date); 
                            $expdate=$datearr[2].'-'.$datearr[1].'-'.$datearr[0];

                            $availability= $udata['availability']; 

                            $soapUrl=trim($gta['uavail']);
                            $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05
                            GTA_InventoryUpdateRQ.xsd">
                                  <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                            <InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$hotel_channel_id.'" >
                            <RoomStyle>';
                        
                                  $xml_post_string.=' <StayDate Date = "'. $expdate.'">

                            <Inventory RoomId="'.$gt_room_id.'" >
                            <Detail FreeSale="false" Invento1ryType="Flexible"
                            Quantity="'.$update_Details->availability.'" ReleaseDays="0"/>
                            </Inventory>
                            </StayDate>';
                            

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
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                            }

                        }
                        // End availability

                        if($name=='price' && $room_value->update_rate=='1')
                        {                           
                    
                           $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id))->result();
                        
                           $srat_array=explode('/',$update_date);
                           $xml_start_date=$srat_array[2].'-'.$srat_array[1].'-'.$srat_array[0];
                           $xml_end_date =$xml_start_date; 

                           $days='1,2,3,4,5,6,7';
                           $days_array=explode(',',$update_Details->days);
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
                            }if (in_array("4", $days_array)) {
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
                                  

                           $mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$room_value->channel_id,'GTA_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$gt_details->hotel_channel_id))->row();

                            $soapUrl = trim($gta['urate_s']);
                            $room_id=$mp_details->ID;
                            $rateplanid=$mp_details->rateplan_id;
                            $MinPax=$mp_details->MinPax;
                            $peakrate=$mp_details->peakrate;
                            $MaxOccupancy=$mp_details->MaxOccupancy;
                            $minnights=$mp_details->minnights;
                            $payfullperiod=$mp_details->payfullperiod;
                              $value_price=$update_Detailsprice;   
                            $contract_type = $mp_details->contract_type;       

                            if($contract_type == "Static"){
                                $soapUrl = trim($gta['urate_s']);
                                $xml_post_string = '<GTA_StaticRatesCreateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_RateCreateRQ.xsd">
                                <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                <RatePlan Id="'.$rateplanid.'">
                                <StaticRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'"
                                DaysOfWeek="'.$dayval.'" MinPax="'.$MinPax.'" FullPeriod="'.$payfullperiod.'"
                                PeakRate="'.$peakrate.'">
                                <StaticRoomRate RoomId="'.$room_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$value_price.'" />
                                </StaticRate>
                                </RatePlan>
                                </GTA_StaticRatesCreateRQ>';
                                                                 

                                $ch = curl_init($soapUrl);
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
                                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit',date('m/d/Y h:i:s a', time()));
                                }
                            }else{
                                $soapUrl = trim($gta['urate_m']);

                                $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'"  FullPeriod="'.$payfullperiod.'"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gt_room_id.'" Occupancy="'.$MaxOccupancy.'" Gross="'.$value_price.'"/></MarginRates></RatePlan></GTA_MarginRatesUpdateRQ>';

                                $ch = curl_init($soapUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                                $response = curl_exec($ch);
                                $data = simplexml_load_string($response);
                                $Error_Array = @$data->Errors->Error;
                                if($Error_Array!='')
                                {
                                    $this->store_error(current_user_type(),hotel_id(),$room_value->channel_id,(string)$Error_Array,'Inline Edit No',date('m/d/Y h:i:s a', time()));
                                }         
                            }
                        } 
                    }
			elseif ($room_value->channel_id == '9') {
                                    $airbnb_details = get_data(CONNECT, array(
                                        'user_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'channel_id' => $room_value->channel_id
                                    ))->row();
                                    $mp_details     = get_data('import_mapping_AIRBNB', array(
                                        'user_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'channel_id' => $room_value->channel_id,
                                        'import_mapping_id' => $room_value->import_mapping_id
                                    ))->row();
                                    
                                    $start = changeFormat($update_date, 0);
                                    $end   = $start;
                                    include('cURL.php');
                                    
                                    $curl                   = new cURL(TRUE);
                                    $url                    = 'https://api.airbnb.com/v1/authorize';
                                    $formData['client_id']  = $airbnb_details->other_id;
                                    $formData['locale']     = 'en-US';
                                    $formData['currency']   = 'USD';
                                    $formData['grant_type'] = 'password';
                                    $formData['username']   = $airbnb_details->user_name;
                                    $formData['password']   = preg_replace('/&/is', '%26', $airbnb_details->user_password);
                                    
                                    $content = '';
                                    foreach ($formData as $key => $value) {
                                        $content .= '&' . $key . '=' . $value;
                                    }
                                    $content    = replace_content_hex($content);
                                    $resultFile = $curl->post($url, $content);
                                    if (preg_match('/"access_token"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                        $AuthToken = $match[1];
                                        $url       = 'https://api.airbnb.com/v2/calendars/' . $mp_details->RoomId . '/' . $start . '/' . $start;
                                        
                                        $formData = array();
                                        if ($name == 'price') {
                                            if ($udata['price'] != '0.00') {
                                                $formData['daily_price']                     = $udata['price'];
                                                $formData['demand_based_pricing_overridden'] = true;
                                            }
                                        }
                                        
                                        //For Alot Update
                                        if ($name == 'availability') {
                                            if ($udata['availability'] > 0) {
                                                $formData['availability'] = 'available';
                                            } else {
                                                $formData['availability'] = 'unavailable';
                                            }
                                        }
                                        if ($name == 'stop_sell') {
                                            if ($udata['stop_sell'] == '1') {
                                                $formData['availability'] = 'unavailable';
                                            } else {
                                                $formData['availability'] = 'available';
                                            }
                                            if ($udata['availability'] != '') {
                                                if ($udata['availability'] == 0) {
                                                    $formData['availability'] = 'unavailable';
                                                }
                                            }
                                        }
                                        
                                        $content          = json_encode($formData);
                                        $curl->headers[2] = "Content-Type: application/json; charset=UTF-8";
                                        $curl->headers[3] = "X-Airbnb-OAuth-Token: $AuthToken";
                                        
                                        $resultFile = put($url, $content, $curl->headers);
                                        if ($resultFile == '') {
                                            
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update -  and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        } elseif (preg_match('/"error_details"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                            
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1] . "- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        } elseif (preg_match('/error/is', $resultFile, $match)) {
                                            
                                            $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, "Could not get success message after update- and DateRange : $start to $end.", 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                        }
                                    } elseif (preg_match('/"error_message"\s*:\s*"(.*?)"/is', $resultFile, $match)) {
                                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, $match[1], 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                    } else {
                                        $this->inventory_model->store_error(current_user_type(), hotel_id(), $room_value->channel_id, 'Access Token not generated.', 'Inline Edit No', date('m/d/Y h:i:s a', time()));
                                    }
                                }
					else if($room_value->channel_id == '2')
					{
						$table = RATE_ADD;
						$this->booking_model->inline_edit_main_calendar($table,$room_ids,$rate_id,$update_date,$name,$room_value->import_mapping_id,$room_value->mapping_id,$guest_count,$refunds);
					}
                }
            }
        }
		}
		else if($channel_id==17)
		{
			$table = RATE_ADD;
			$this->bnow_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_id,$guest_count,$refunds,$column,$update);
		}
        else if($channel_id==15)
        {
            $table = RATE_ADD;
            $this->travel_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_id,$guest_count,$refunds,$column,$update);
        }
        else if($channel_id==14)
        {
            $table = RATE_ADD;
            $this->wbeds_model->inline_edit_channel_calendar($table,$room_ids,$update_date,$rate_id,$guest_count,$refunds,$column,$update);
        }
        $roomname = get_data(TBL_PROPERTY,array('property_id'=>$room_id,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->property_name;
        $ratename = get_data(RATE_TYPES,array('rate_type_id'=>$rate_id,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->rate_name;
        $user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
        $username = ucfirst($user->fname).' '.ucfirst($user->lname);
        $productdetails = "";
        $channelsname = "";
        if($name){
            $productdetails .= " ".ucfirst($name).":".$value.',';
        }
        $channelsname .= "Channels:";
        $updateDate = date('Y-m-d',strtotime(str_replace('/','-',$update_date)));
        
        $channelsname .= get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row()->channel_name.',';
        $message = "Location:Channel Calendar Page, Date:".$updateDate.", Room:".$roomname.' '.$ratename.' '.$guest_count.','.$productdetails.' '.$channelsname.' IP:'.$this->input->ip_address().' User:'.$username;
        
        $this->inventory_model->write_log($message);
		}
		}
	}
    
    /*Channel Calendar Functionality Stop */
    
    function update_stopsell_main()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
        {
            $update_stopsell_main = $this->inventory_model->update_stopsell_main();
            if($update_stopsell_main)
            {
                //echo '1';
            }
            else
            {
                //echo '0';
            }
        }
        else 
        {
           // echo '0';
        }
    }
    
    function update_stopsell_main_rate()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
		if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') 
        {
            $update_stopsell_main = $this->inventory_model->update_stopsell_main_rate();
            if($update_stopsell_main)
            {
                echo '1';
            }
            else
            {
                echo '0';
            }
        }
        else
        {
            echo '0';
        }
    }
   
    
    function get_reservation_details()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        extract($this->input->post());
        $data['get_data'] = 'get_data';
        $reservation = get_data(RESERVATION,array('reservation_id'=>$reservation_id))->row_array();
        $data = array_merge($reservation,$data);
        $this->load->view('channel/reservation_user_details',$data);
    }
    
    function get_other_amount(){
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }

   $val=$_REQUEST['val'];

   $id=$_REQUEST['id'];

   $type=$_REQUEST['type'];

   $split_id=explode("_", $id);

   $sql=$this->db->query('SELECT r.*,p.pricing_type FROM room_refunds as r  JOIN manage_property as p on r.room_id='.$split_id[1].' AND r.room_id=p.property_id');



//fetch roomtype
   $sql1=$this->db->query('SELECT r.*,s.uniq_id,s.room_id,s.non_refund from room_rate_types_refund as r JOIN room_rate_types as s Where s.room_id='.$split_id[1].' AND s.uniq_id=r.uniq_id' )->result();
 
   $uniq_id="";
   $refund_sec="";
   $nonsec="";
   $nonfirst="";
   foreach ($sql1 as $rate_type) {
    $method=$rate_type->method;
    $type=$rate_type->type;
    $dis_amount=$rate_type->dis_amount;

    //non_refund
    $nr_method=$rate_type->n_method;
    $nr_type=$rate_type->n_type;
    $nr_dis_amount=$rate_type->n_dis_amount;
    if($rate_type->non_refund==0){
     if($method=="+"){
       if($type=="%"){
        $rbamount= $val+$val*($dis_amount/100);
       }else{
        $rbamount= $val+$dis_amount;
       }
         $uniq_id =$rate_type->uniq_id."=".$rbamount."=REFUND~";
    }else{
     if($type=="%"){
        $rbamount= $val-$val*($dis_amount/100);
       }else{
        $rbamount= $val-$dis_amount;
       }
        $uniq_id =$rate_type->uniq_id."=".$rbamount."=REFUND~";
    }
      $refund_sec .=$uniq_id;
   }else{
        //non refund type
     if($method=="+"){
           if($type=="%"){
            $rbamount= $val+$val*($dis_amount/100);
           }else{
            $rbamount= $val+$dis_amount;
           }
     $uniq_id =$rate_type->uniq_id."=".$rbamount."=ROOMB~";
    }else{
         if($type=="%"){
            $rbamount= $val-$val*($dis_amount/100);
           }else{
            $rbamount= $val-$dis_amount;
           }
     $uniq_id =$rate_type->uniq_id."=".$rbamount."=ROOMB~";      
    }
       $nonfirst .=$uniq_id;
    if($nr_method=="+"){
           if($nr_type=="%"){
            $rbamount= $val+$val*($nr_dis_amount/100);
           }else{
            $rbamount= $val+$nr_dis_amount;
           }
      $uniq_id =$rate_type->uniq_id."=".$rbamount."=NROOMB~";     
    }else{
         if($nr_type=="%"){
            $rbamount= $val-$val*($nr_dis_amount/100);
           }else{
            $rbamount= $val-$nr_dis_amount;
           }
    $uniq_id =$rate_type->uniq_id."=".$rbamount."=NROOMB~";
    }
     $nonsec .=$uniq_id;

   }
 }

  echo  $refund_sec.$nonfirst.$nonsec."UNIQID";


   $refund_amount1="";

   $nrefund_amount1="";





   foreach ($sql->result() as $amt) {

    $method=$amt->method;

    $pricing_type=$amt->pricing_type;
    
    if($pricing_type=='1'){

        $n_method= $amt->n_method;

        $n_type=  $amt->n_type;

        $n_dis_amount=  $amt->n_dis_amount;
        $nrefund_amount="";
         if($n_method=="+"){

           if($n_type=="%"){

            $nrefund_amount= $val+$val*($n_dis_amount/100);

           }else{

            $nrefund_amount= $val+$n_dis_amount;

           }

         }else{

             if($n_type=="%"){

            $nrefund_amount= $val-$val*($n_dis_amount/100);

           }else{

             $nrefund_amount= $val-$n_dis_amount;

           }

         }
         $nrefund_amount1=$nrefund_amount;  
         echo 'price1^^'.$nrefund_amount1;



    }else{

    $type= $amt->type;

    $dis_amount=  $amt->dis_amount;




     if($method=="+"){

       if($type=="%"){

        $refund_amount = $val+$val*($dis_amount/100);

       }else{

        $refund_amount = $val+$dis_amount;

       }

     }else{

         if($type=="%"){

        $refund_amount = $val-$val*($dis_amount/100);

       }else{

        $refund_amount = $val-$dis_amount;

       }

     }

    $refund_amount1 .=$refund_amount."~";

    $n_method= $amt->n_method;

    $n_type=  $amt->n_type;

    $n_dis_amount=  $amt->n_dis_amount;

    if($n_method=="+"){

       if($n_type=="%"){

        $nrefund_amount= $val+$val*($n_dis_amount/100);

       }else{

        $nrefund_amount= $val+$n_dis_amount;

       }

     }else{

         if($n_type=="%"){

        $nrefund_amount= $val-$val*($n_dis_amount/100);

       }else{

         $nrefund_amount= $val-$n_dis_amount;

       }

     }

     $nrefund_amount1 .=$nrefund_amount."~";

    }



 }
   echo $refund_amount1."^^".$nrefund_amount1;
    }
    
    function get_other_rate(){
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
   $val=$_REQUEST['val'];
   $id=$_REQUEST['id'];
   $split_id=explode("_", $id);
    $sql=$this->db->query('SELECT r.*,t.pricing_type FROM `room_rate_types_refund` as r JOIN `room_rate_types` as t ON r.uniq_id='.$split_id[1].' AND r.uniq_id=t.uniq_id');
          if($sql->num_rows>0){
            $nrefund_amount1='';
            $refund_amount1='';
            $nrefund_amount='';
            $refund_amount='';
            $pricing_type="";
            foreach ($sql->result() as $rate) {
                 $n_method=$rate->n_method;
                 $n_type=$rate->n_type;
                 $pricing_type=$rate->pricing_type;
                 $n_dis_amount=$rate->n_dis_amount;
                  $method=$rate->method;
                 $type=$rate->type;
                 $dis_amount=$rate->dis_amount;
                if($pricing_type=='1'){
                //room based type
                     if($n_method=="+"){
                       if($n_type=="%"){
                        $nrefund_amount= $val+$val*($n_dis_amount/100);
                       }else{
                        $nrefund_amount= $val+$n_dis_amount;
                       }
                     }else{
                        if($n_type=="%"){
                        $nrefund_amount= $val-$val*($n_dis_amount/100);
                       }else{
                        $nrefund_amount= $val-$n_dis_amount;
                       }
                     }
                    }else{
               //Guest based type
                if($method=="+"){
                   if($type=="%"){
                     $refund_amount = $val+$val*($dis_amount/100);
                    }else{
                     $refund_amount = $val+$dis_amount;
                   }
                 }else{
                    if($n_type=="%"){
                     $refund_amount = $val-$val*($dis_amount/100);
                    }else{
                     $refund_amount = $val-$dis_amount;
                   }
                 }
                  $refund_amount1 .=$refund_amount."~";
                 if($n_method=="+"){
                   if($n_type=="%"){
                     $nrefund_amount = $val+$val*($n_dis_amount/100);
                    }else{
                     $nrefund_amount = $val+$n_dis_amount;
                   }
                 }else{
                    if($n_type=="%"){
                     $nrefund_amount = $val-$val*($n_dis_amount/100);
                    }else{
                     $nrefund_amount = $val-$n_dis_amount;
                   }
                 }

                  $nrefund_amount1 .=$nrefund_amount."~";
             } 

            }
            
            if($pricing_type=="1"){
                  echo $nrefund_amount; 
            }else{
                echo $refund_amount1."^".$nrefund_amount1;
            }

           }else{
            echo "no";
          }
         
        }
		function get_plan_details()
		{
			$plan_id	=	$this->input->post('plan_id');
			
            if(admin_id()=='')
            {
                $this->is_login();
            }
            else
            {
                $this->is_admin();
            }
			if($plan_id!='')
			{
				if(check_plan($plan_id,'check'))
				{
					$plan_details 	= check_plan($plan_id,'get');
					$currency		= get_data(TBL_CUR,array('currency_id'=>$plan_details['currency']))->row()->currency_code;
					if($plan_details['plan_types']=='Month')
					{
						$plan = 1;
						$plan_du = 'months';
					}
					elseif($plan_details['plan_types']=='Year')
					{
						$plan = 1;
						$plan_du = 'years';
					}
					elseif($plan_details['plan_types']=='Free')
					{
						$plan = $plan_details['plan_price'];
						$plan_du = 'days';
						$currency = 'days';
					}
					
					$data['plan_name'] 	= $plan_details['plan_name'].' ( '.$plan_details['plan_price'].' '.$currency.' / '.$plan_details['plan_types'].' ) ';
					
					
					$exist_plan = get_data(MEMBERSHIP, array('user_id'=>current_user_type(),'hotel_id' => hotel_id(),'plan_status' => 1));

                    if($exist_plan->num_rows == 1){
                        $exist_wait_plan = get_data(MEMBERSHIP,array('user_id'=>current_user_type(),'hotel_id' => hotel_id(),'plan_status' => 3));
                        /*if($exist_wait_plan->num_rows != 0){
                            $plan_to = $exist_wait_plan->row()->plan_to;
                            $dtime  = strtotime(str_replace('-','/',$plan_to));
                            $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du",$dtime));
                        }else{
                            $plan_to = $exist_plan->row()->plan_to;
                            $dtime  = strtotime(str_replace('-','/',$plan_to));
                            $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du",$dtime));
                        }*/
                        if($plan_details['number_of_channels'] != 0){
                            if($exist_plan->row()->total_channels >= $plan_details['number_of_channels']){
                                $plan_to = $exist_plan->row()->plan_to;
                                $dtime  = strtotime(str_replace('-','/',$plan_to));
                                $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du",$dtime));
                            }else{
                                $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du"));
                            }
                        }else{
                            $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du"));
                        }
                    }else{
                        $data['expires_date'] = date("d/m/Y", strtotime("+$plan $plan_du"));
                    }
						
					echo json_encode($data);
				}
				else
				{
					echo 'NO';
				}
			}
			else
			{
				echo 'NO';
			}
		}
		function select_channels_details()
		{
			$plan_id	=	$this->input->post('plan_id');
			
			if(admin_id()=='')
            {
                $this->is_login();
            }
            else
            {
                $this->is_admin();
            }
			if($plan_id!='')
			{
				if(check_plan($plan_id,'check'))
				{
					$data['all_channels']	=	get_all_channels_available();
					$data['plan_details']	=	check_plan($plan_id,'get');	
					if($data['plan_details']['number_of_channels'] != 0){
                       $this->load->view('channel/subscribe_channels',$data);
                    }else{
                        echo 0;
                    }
				}
				else
				{
					echo "No plans to be selected";
				}
			}
			else
			{
				echo "No plans to be selected";
			}
		}
        
   


    
    
    /*Start testing \*/
    
    function test($id='')
    {
		$this->inventory_model->test_model_function();
		exit;
        $today = date('d/m/Y');
        $tomorrow = date('d/m/Y',strtotime($today . "+1 months"));
        $startDate = DateTime::createFromFormat("d/m/Y",$today);
        $endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
        $periodInterval = new DateInterval( "P1D" ); // 1-day, though can be more sophisticated rule
        $endDate->add( $periodInterval );
        $period = new DatePeriod( $startDate, $periodInterval, $endDate );
        $endDate->add( $periodInterval );
        $dates = array();
        $months = array();
        foreach($period as $date){
        $dates[] = $date->format("d/m/Y");
        $months[] = date('F Y',strtotime($date->format("Y/m/d")));
        }
        echo '<pre>';
        print_r($dates);
        print_r($months);
        
        /* $closing_date ='10';

echo $dispute_start = date('Y-m-d',strtotime('+'.$closing_date.' days'));

exit; */

// displaying dates between 2 dates..

$date1 = '29/08/2013';

$date2 = '03/09/2013';

function returnDates($fromdate, $todate) {

$fromdate = \DateTime::createFromFormat('d/m/Y', $fromdate);

$todate = \DateTime::createFromFormat('d/m/Y', $todate);

return new \DatePeriod(

   $fromdate,

   new \DateInterval('P1D'),

   $todate->modify('+1 day')

);

}

$datePeriod = returnDates($date1, $date2);

$array = array();

foreach($datePeriod as $date) {

// echo $date->format('d/m/Y'), PHP_EOL; //PHP_EOL --> end of line..

  $array[] = $date->format('d/m/Y');

}

echo "<pre>";

print_r($array);
    exit;
        echo insep_encode($id);
        
        $query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = 'vijikumar.job@gmail.com' UNION ALL SELECT                      'property_id' AS t2 FROM ".TBL_PROPERTY." WHERE email = 'vijikumar.job@gmail.com') as total ")->row_array();
                echo $query['total'];
                
        $value = '1438586156829.jpeg|1438586156262.jpeg|1438586156120.jpeg';
       
        $original_val = explode('|',$value);
       
        $devl_id = '1438586156829.jpeg';
       
         foreach($original_val as $val)
            {
                if(($key = array_search($devl_id, $original_val)) !== false) {
                    unset($original_val[$key]);
                    $test_array = array_values($original_val);
                }
            }

            print_r($test_array);
            
            
            // Current timestamp is assumed, so these find first and last day of THIS month
echo $first_day_this_month = date('d'); // hard-coded '01' for first day
echo  $last_day_this_month  = date('t');

// With timestamp, this gets last day of April 2010
$last_day_april_2010 = date('m-t-Y', strtotime('April 21, 2010'));
           
           
           

    }
    
    
    function democat()
    {
     
      $maincat_cnt=$this->inventory_model->getallexist_subcatcntbymaincat($catid='1'); 
        echo " maincat_cnt ".$maincat_cnt; 
          $end_catlimit=$maincat_cnt-8; 
          $start_catlimit=8;
          
          
              $maincat_cnt12=$this->inventory_model->getallexist_subcatcntallcat($catid='1',$start_catlimit,$end_catlimit); 
      echo " <br>  maincat_cnt12  $maincat_cnt12 "; 
        
    }
    
    function save($product, $options=false, $categories=false)
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
            if ($product['id'])
        {
            $this->db->where('id', $product['id']);
            $this->db->update('products', $product);

            $id = $product['id'];
            for($iii=1;$iii<=90;$iii++)
            {
            $instructioniid=$this->input->post('inst_name'.$iii);
            $descriptioniid=$this->input->post('desction'.$iii);
            $in_ids=$this->input->post('in_ids'.$iii);
            if($instructioniid!="")
            {
            $this->db->where('in_id',$in_ids);
            $query  =   $this->db->get('inst'); 
            if($query->num_rows >= 1)
            { 
            $this->db->where('in_id',$in_ids);
            $this->db->where('product_id',$id);
            $this->db->update('inst', array('ins'=>$instructioniid,'ins_desc'=>$descriptioniid));
            }
            else
            {
            $this->db->insert('inst', array('product_id'=>$id,'ins'=>$instructioniid,'ins_desc'=>$descriptioniid));
            }
            //echo $this->db->last_query(); die;
            }
            }
        } 
        else
        {
            $this->db->insert('products', $product);
            $id = $this->db->insert_id();
            for($iii=1;$iii<=90;$iii++)
            {
            $instructioniid=$this->input->post('inst_name'.$iii);
            $descriptioniid=$this->input->post('desction'.$iii);
            if($instructioniid!="")
            {
            $this->db->insert('inst', array('product_id'=>$id,'ins'=>$instructioniid,'ins_desc'=>$descriptioniid));
            }
            }
        }


        //loop through the product options and add them to the db
        if($options !== false)
        {
            $obj =& get_instance();
            $obj->load->model('Option_model');

            // wipe the slate
            $obj->Option_model->clear_options($id);

            // save edited values
            $count = 1;
            foreach ($options as $option)
            {
                $values = $option['values'];
                unset($option['values']);
                $option['product_id'] = $id;
                $option['sequence'] = $count;

                $obj->Option_model->save_option($option, $values);
                $count++;
            }
        }
        
        if($categories !== false)
        {
            if($product['id'])
            {
                //get all the categories that the product is in
                $cats   = $this->get_product_categories($id);
                
                //generate cat_id array
                $ids    = array();
                foreach($cats as $c)
                {
                    $ids[]  = $c->id;
                }

                //eliminate categories that products are no longer in
                foreach($ids as $c)
                {
                    if(!in_array($c, $categories))
                    {
                        $this->db->delete('category_products', array('product_id'=>$id,'category_id'=>$c));
                    }
                }
                
                //add products to new categories
                foreach($categories as $c)
                {
                    if(!in_array($c, $ids))
                    {
                        $this->db->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
                    }
                }
            }
            else
            {
                //new product add them all
                foreach($categories as $c)
                {
                    $this->db->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
                }
            }
        }
        
        
        //return the product id
        return $id;
    }
    
    function bulk_save()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $sup_admin=$this->session->userdata('admin');
        if(isset($sup_admin['id']))
        {
        $acc=$this->admin_model->get_access('20',$sup_admin['id']);
        }
                //echo $acc;
        if($acc=='1')
        {
            $products   = $this->input->post('product');
            
            if(!$products)
            {
                $this->session->set_flashdata('error',  lang('error_bulk_no_products'));
                redirect($this->config->item('admin_folder').'/products');
            }
                    
            foreach($products as $id=>$product)
            {
                $product['id']  = $id;
                $this->Product_model->save($product);
            }
            
            $this->session->set_flashdata('message', lang('message_bulk_update'));
            redirect($this->config->item('admin_folder').'/products');
        }
        else
        {
            $data['page_title'] =  'Error Found';
            $this->view($this->config->item('admin_folder').'/page_no',$data);
        }
    }

    function log_test()
    {
        require_once(APPPATH.'controllers/logging.php');
        $log = new Logging();
        // set path and name of log file (optional)
        $path = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']).'/'.APPPATH."logs/log_".current_user_type().".txt";
        if(!file_exists($path))
        {
            $myfile = fopen($path, "wb");
        }
        //echo $this->input->ip_address();
        echo $_SERVER['HTTP_X_FORWARDED_FOR'];
        $log->lfile($path);
        $name = "Log Test";
        // write message to the log file
        for($ii = 0; $ii <= 5; $ii++)
        {
            $log->lwrite('Inline Edit -'.current_user_type().'-'.hotel_id()."-".$name);
        }
        
        // close log file
        $log->lclose();
    }

    function extras($mode = ''){
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        switch ($mode) {
            case 'add':
                extract($this->input->post()); 
                $data['room_id'] = insep_decode($room_id);
                $data['name'] = $name;
                $data['price'] =$price;
                $data['structure'] =$structure;
                $data['taxes'] =$taxes;
                $this->db->insert('room_extras',$data);
                redirect('inventory/room_types/view/'.$room_id);
            break;
            case 'del':
                $data['extra_id'] = $this->input->post('id');
                $this->db->delete('room_extras', $data);
        }
    }
}
