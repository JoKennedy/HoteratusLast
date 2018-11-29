<?php


class Mapping extends Front_Controller {
    
    public function __construct()
    {       
        parent::__construct();
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
         // echo 'hroiig';exit;
        if(!user_id())
        {
        $data['page_heading'] = 'Home';
        $this->view('channel/index',$data);
        }
        else
        {
            redirect('mapping/connectlist','refresh');
        }
    }
    function connectlist(){
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }   
        if(user_type()=='1' || user_type()=='2' && in_array('6',user_view()) || admin_id()!='' && admin_type()=='1'){   
        $data['page_heading'] = 'Connect channel';
        $user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
        $data['connect_channel']= $this->mapping_model->connect_hotel();
        $data= array_merge($user_details,$data);
        $this->views('channel/channel_list',$data);
        }
        else
        {
            redirect(base_url());
        }
        
    } 

    function SaveConvertion()
    {

         if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        
 


        if(isset($_POST['ids']) &&  $_POST['Conversion']!= "" )
        {
            $this->mapping_model->SaveConvertion();
            
            $this->session->set_flashdata('map_update','Rate Convertion Updated Successfully!!!');
            redirect('mapping/settings/'.insep_encode($_POST['channel_id']),'refresh');
        }
        else
        {
            $this->session->set_flashdata('map_remove','Rate Convertion Updated Error!!!');
            redirect('mapping/settings/'.insep_encode($_POST['channel_id']),'refresh');
        }

    
    
        
    }

    function propertyRead($channelID)
    {
        if($channelID==8)
        {
            $this->load->model("gta_model");
            $this->gta_model->propertyRead();
        }
    }
    
    function setting($id=''){
        if($id=='') return;
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }    
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $this->session->set_userdata('connect',$id);
        redirect('mapping/settings','refresh');
    }
   

    function settings($id='')
    {
		
		if($id=='') return;
       if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if(user_type()=='1' || user_type()=='2' && in_array('6',user_view()) || admin_id()!='' && admin_type()=='1')
		{   
        $data['page_heading'] = 'Room Mapping';
        $connect = insep_decode($id);
        $data['channeldetails']=$this->db->query("Select * from  manage_channel where channel_id=".$connect."")->row_array();
        $data['channel_id']=$connect;
       // $data['channel_details']=$this->mapping_model->mapped_rooms($connect,'fetch');
        $data['not_channel_details']=$this->mapping_model->not_mapped_rooms($connect,'fetch');
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        $this->session->set_userdata('map_to_channel','map_to_channel');
        
        if($connect=='1')
        {
			//$this->getchannel($id);
            $data['expedia'] 			= 	$this->mapping_model->get_mapping_rooms($connect);
            $expedia_all 				= 	$this->mapping_model->get_all_mapping_rooms($connect);
			$data['channel_details'] 	=	$this->mapping_model->get_all_mapped_rooms($connect);
            if($expedia_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
        }


         elseif($connect=='36')
        {
			//$this->getchannel($id);
            $data['despegar']            =   $this->mapping_model->get_mapping_rooms($connect);
            $despegar_all                =   $this->mapping_model->get_all_mapping_rooms($connect);
            $data['channel_details']    =   $this->mapping_model->get_all_mapped_rooms($connect);

            if($despegar_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
        }
         elseif($connect=='40' || $connect=='41' || $connect=='42')
        {
            //$this->getchannel($id);
            require_once(APPPATH.'models/hotusagroup_model.php'); 
            $hotusa = new hotusagroup_model();
            $data['hotusagroup']  =   $hotusa->get_mapping($connect);
            $hotusagroup_all   =   $hotusa->get_all_mapping_rooms($connect);
            $data['channel_details']    =   $hotusa->get_all_mapped_rooms($connect);

            if($hotusagroup_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
        }

        else if($connect=='11')
        {
			//$this->getchannel($id);
            $data['reconline'] 			= 	$this->mapping_model->get_mapping_rooms($connect);
            $reconline_all 				= 	$this->mapping_model->get_all_mapping_rooms($connect);
			$data['channel_details'] 	=	$this->mapping_model->get_all_mapped_rooms($connect);			
            if($reconline_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
        }
        else if($connect=='2')
        {
			//$this->getchannel($id);
			$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row();
			if($connect==2 && ( $ch_details->xml_type==3 || $ch_details->xml_type==2) )
			{
				$data['booking'] 			= 	$this->mapping_model->get_mapping_rooms($connect);
				$booking_all 				= 	$this->mapping_model->get_all_mapping_rooms($connect);
				$data['channel_details'] 	=	$this->mapping_model->get_all_mapped_rooms($connect);
				if($booking_all=='0')
				{
					$data['import_need'] = " Need to import the room for mapping!!!";
				}
			}
			else
			{
				$channel = get_data(TBL_CHANNEL,array('channel_id'=>$connect))->row()->seo_url;
				redirect('channel/view_channel/'.$channel,'refresh');
			}
        }
        else if($connect=='8')
        {
			//$this->getchannel($id);
            $data['gta'] 				= 	$this->mapping_model->get_mapping_rooms($connect);
            $booking_all 				= 	$this->mapping_model->get_all_mapping_rooms($connect);
			$data['channel_details'] 	=	$this->mapping_model->get_all_mapped_rooms($connect);
            if($booking_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
            
        }
	else if($connect=='9')
        {
			//$this->getchannel($id);
            $data['airbnb'] 			= $this->mapping_model->get_mapping_rooms($connect);
			
            $booking_all 				= $this->mapping_model->get_all_mapping_rooms($connect);
			$data['channel_details'] 	= $this->mapping_model->get_all_mapped_rooms($connect);
            if($booking_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
            
        }
		else if($connect=='19')
        {

		    //$this->getchannel($id);
            $data['agoda']=$this->mapping_model->get_mapping_rooms($connect);
            $agoda_all  = $this->mapping_model->get_all_mapping_rooms($connect);
            $data['channel_details'] = $this->mapping_model->get_all_mapped_rooms($connect);
            if($agoda_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }

            //  require_once(APPPATH.'controllers/agoda.php'); 
            //  $callAvailabilities = new agoda();
            
            
            // $data['agoda'] = $callAvailabilities->get_mapping_rooms($connect);
        
            
            //$this->views('channel/agoda_channel_seating',$data);
            
            
        }
		else if($connect == '5')
		{
			//$this->getchannel($id);
            $data['hotelbeds'] 			= 	$this->mapping_model->get_mapping_rooms($connect);
            $booking_all 				= 	$this->mapping_model->get_all_mapping_rooms($connect);
			$data['channel_details'] 	=	$this->mapping_model->get_all_mapped_rooms($connect);
            if($booking_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
        }
		else if($connect == '17')
		{
			//$this->getchannel($id);
			require_once(APPPATH.'controllers/bnow.php'); 
			$callAvailabilities = new Bnow();
			$mapping_settings = $callAvailabilities->mapping_settings($connect);
			$data['bnow'] 				= 	$mapping_settings['bnow'];
			$data['channel_details'] 	= 	$mapping_settings['channel_details'];
			if(count(@$mapping_settings['import_need']))
			{
				$data['import_need']	=	$mapping_settings['import_need'];
			}
        }
        else if($connect == '15')
        {
			//$this->getchannel($id);
            require_once(APPPATH.'controllers/travelrepublic.php'); 
            $callAvailabilities = new Travelrepublic();
            $mapping_settings = $callAvailabilities->mapping_settings($connect);
            $data['travel']               =   $mapping_settings['travel'];
            $data['channel_details']    =   $mapping_settings['channel_details'];
            if(count(@$mapping_settings['import_need']))
            {
                $data['import_need']    =   $mapping_settings['import_need'];
            }
        }
		else if($connect == '14')
        {
		
            //$this->getchannel($id);
            $data['wbeds']                =   $this->wbeds_model->get_mapping_rooms($connect);
            $booking_all                =   $this->wbeds_model->get_all_mapping_rooms($connect);
            $data['channel_details']    =   $this->wbeds_model->get_all_mapped_rooms($connect);
            if($booking_all=='0')
            {
                $data['import_need'] = " Need to import the room for mapping!!!";
            }
            

        }
        else
        {
			$data['channel_details']	=	array();
            $data['import_need'] 		= 	" Need to import the room for mapping!!!";
        }
		
		    
            //$this->views('channel/channel_settings',$data);
	  //   	if($connect!='19')
			// {
		
            $this->views('channel/channel_new_settings',$data);
		//	}
		
        }
        else
        {
            redirect(base_url());
        }
    }
	
    function savemappingroom()
    {
        $conversion=$_POST['convertion'];
        $converpromo=($_POST['convertion']*(1+($_POST['promotion']/100)));
        $i=0;
        if($_POST['promotion']==0 || strlen($_POST['promotion'])==0){
            $converpromo=$conversion;
        }else
        {
            for ($i=0; $i <10000; $i++) { 

               if(($converpromo-($converpromo*($_POST['promotion']/100)))>=$conversion)
               {

                 break;
               }
               $converpromo+=0.01;
            }
        }
            

        $data['owner_id']=user_id();
        $data['hotel_id']=hotel_id();
        $data['property_id']=$_POST['roomid'];
        $data['rate_id']=0;
        $data['import_mapping_id']=$_POST['roomchannelid'];
        $data['updatetypes']=implode(',',$_POST['opt']);
        $data['rate_conversion']=$converpromo;
        $data['channel_id']=$_POST['channelid'];
        $data['promotion']=$_POST['promotion'];
         $data['currencypromotion']=$_POST['convertion'];
        if(insert_data(MAP,$data))
        {
             echo json_encode(array('success'=>true));
        }
        else
        {
             echo json_encode(array('success'=>false,'message'=>"Something went wrong"));
        }

    }
    function updatemappingroom()
    {
        $conversion=$_POST['convertionup'];
        $converpromo=($_POST['convertionup']*(1+($_POST['promotionup']/100)));
        $i=0;
        if($_POST['promotionup']==0 || strlen($_POST['promotionup'])==0){
            $converpromo=$conversion;
        }else
        {
            for ($i=0; $i <10000; $i++) { 

               if(($converpromo-($converpromo*($_POST['promotionup']/100)))>=$conversion)
               {

                 break;
               }
               $converpromo+=0.01;
            }
        }
          
        $data['property_id']=$_POST['roomidup'];
        $data['updatetypes']=implode(',',$_POST['optup']);
        $data['rate_conversion']=$converpromo;
        $data['enabled']=(isset($_POST['statusid'])?'enabled':'disabled');
        $data['promotion']=$_POST['promotionup'];
        $data['currencypromotion']=$_POST['convertionup'];

        if(update_data(MAP,$data,array('mapping_id'=>$_POST['mapping_id'])))
        {
             echo json_encode(array('success'=>true));
        }
        else
        {
             echo json_encode(array('success'=>false,'message'=>"Something went wrong"));
        }

    }
    function deletemappingroom()
    {

       
        $this->db->query("delete from ".MAP." where mapping_id=".$_POST['id']);

        echo json_encode(array('success'=>true));
    }
    function saveNRoom()
    {
       

        $data['user_id']=user_id();
        $data['hotel_id']=hotel_id();
        $data['channel_id']=9;
        $data['RoomId']=$_POST['nroomid'];
        $data['RoomName']=$_POST['nroomname'];
    

        if(insert_data('import_mapping_AIRBNB',$data))
        {
             echo json_encode(array('success'=>true));
        }
        else
        {
             echo json_encode(array('success'=>false,'message'=>"Something went wrong"));
        }

    }
    function mappingRooms($channelID)
    {   
        $this->is_login();
        $data['page_heading'] = 'Room Mapping';
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
        $channelID=insep_decode($channelID);
        $data['channelinfo']=get_data('manage_channel',array('channel_id'=>$channelID))->row_array();
        $data['AllRooms']=get_data('manage_property',array('hotel_id'=>hotel_id()))->result_array();
        if($channelID=='1')
        {
            $data['roomsunmapped']            =   $this->mapping_model->get_mapping_rooms($channelID);
            $data['roomsmapped']    =   $this->mapping_model->get_all_mapped_rooms($channelID);
        }
        else if($channelID=='2')
        {
                $data['roomsunmapped'] =$this->mapping_model->get_mapping_rooms($channelID);
                $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);       
        }
         else if($channelID=='8')
        {
                $data['roomsunmapped'] =$this->mapping_model->get_mapping_rooms($channelID);
                $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);           
        }
        else if($channelID=='9')
        {
            $data['roomsunmapped'] = $this->mapping_model->get_mapping_rooms($channelID);
            $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);            
        }
        else if($channelID=='14')
        {
            $data['roomsunmapped']=$this->mapping_model->get_mapping_rooms($channelID);
            $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);
        }
         else if($channelID=='19')
        {
            $data['roomsunmapped']=$this->mapping_model->get_mapping_rooms($channelID);
            $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);
        }
        else if($channelID=='40' || $channelID=='41' || $channelID=='42')
        {
            $data['roomsunmapped']=$this->mapping_model->get_mapping_rooms($channelID);
            $data['roomsmapped'] = $this->mapping_model->get_all_mapped_rooms($channelID);
        }




        $this->views('channel/mappingrooms',$data);
    }
	
	
	
   function insertNewRoom(){
		$room_id				  = $this->input->post('roomId');
		$roomname				  = $this->input->post('roomName');
		$airbnbData['user_id']    = current_user_type();
		$airbnbData['hotel_id']   = hotel_id();
		$airbnbData['channel_id'] = '9';
		$airbnbData['RoomId']   = $room_id;
		$airbnbData['RoomName'] = $roomname;
		
          
		$room_available         = get_data(IM_AIRBNB, array(
			'user_id' => current_user_type(),
			'hotel_id' => hotel_id(),
			'channel_id' => '9',
			'RoomId' => $room_id,
			'RoomName' => $roomname
		))->row_array();
		if (count($room_available) != 0) {
			echo 'Room Already Exists';
			//update_data(IM_AIRBNB,$airbnbData,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'HotelCode'=>$airbnbData['HotelCode'],'InvTypeCode'=>$airbnbData['InvTypeCode'],'RatePlanCode'=>$airbnbData['RatePlanCode']));
		} else {
			insert_data(IM_AIRBNB, $airbnbData);
			echo 'Room Saved';
		}
	}
	
	
	
	public function insert_agoda_room()
	{
		
		
		
		$data=$_POST;
		
		
		$room_name=$data['roomName'];
		$room_id=$data['roomId'];
		
		
		$agodaData['user_id']    = current_user_type();
		$agodaData['hotel_id']   = hotel_id();
		$agodaData['channel_id'] = '19';
		$agodaData['RoomId']   = $room_id;
		$agodaData['RoomName'] = $room_name;
		$agodaData['num_persons'] =30;
		$agodaData['min_rate'] =200;
		
		
		
		$this->load->model("agoda_model");
		$num_rows=$this->agoda_model->get_room_available($agodaData);
		if($num_rows!=0)
		{
			
			
		   echo 'Room Already Exists';	
			
			
		}
		else
		{
			
			$abc=$this->agoda_model->insert_agoda_room($agodaData);
			
			
			
			if($abc==1)
				
			
			{
				    echo "<h3 style=color:green>Room Added sucessefully</h3>";
				
			}
			
			
		
			
		}
		
		
		
		
		
		

		// $room_available = get_data(IM_AIRBNB, array(
			// 'user_id' => current_user_type(),
			// 'hotel_id' => hotel_id(),
			// 'channel_id' => '9',
			// 'RoomId' => $room_id,
			// 'RoomName' => $roomname
		// ))->row_array();
		// if (count($room_available) != 0) {
			// echo 'Room Already Exists';
			
		// } else {
			// insert_data(IM_AIRBNB, $airbnbData);
			// echo 'Room Saved';
		// }
		
		
		
	}

	function updateRoom(){
		$room_id				  = $this->input->post('roomId');
		$roomname				  = $this->input->post('roomName');
		$id						  = $this->input->post('id');

		$airbnbData['RoomId']   = $room_id;
		$airbnbData['RoomName'] = $roomname;
		$room_available         = get_data(IM_AIRBNB, array(
			'import_mapping_id' => $id
		))->row_array();
		if (count($room_available) != 0) {
			if(update_data(IM_AIRBNB, $airbnbData,array('import_mapping_id' => $id))){
				echo 'Updated SuccesFully';
			}else{
				echo 'Unable to update.';
			}
		}
	}
	function deleteRoom(){
		$room_id				  = $this->input->post('roomId');
		$roomname				  = $this->input->post('roomName');
		$id						  = $this->input->post('id');

		$airbnbData['RoomId']   = $room_id;
		$airbnbData['RoomName'] = $roomname;
		$room_available         = get_data(IM_AIRBNB, array(
			'import_mapping_id' => $id
		))->row_array();
		if (count($room_available) != 0) {
			if(delete_data(IM_AIRBNB,array('import_mapping_id' => $id))){
				echo 'Deleted SuccesFully';
			}else{
				echo 'Unable to Delete.';
			}
		}
	}
    function remove_map($channel_id='',$mapping_id,$property_id)
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()) || admin_id()!='' && admin_type()=='1'){   
        $connect = $channel_id;
        $remove_map=$this->mapping_model->removemap(insep_decode($connect),insep_decode($mapping_id),unsecure($property_id));
        delete_data('mapping_values',array('mapping_id'=>insep_decode($mapping_id)));
        // $this->session->set_userdata('removemap',$id);
        $this->session->set_flashdata('map_remove','Room has been deleted successfully!!!');
        redirect('mapping/settings/'.$connect,'refresh');
        }
        else
        {
            redirect(base_url());
        }
    }

    function mapchannel($id='',$owner_id=""){
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }   
        $user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
        $this->session->set_userdata('mapchannel',$id);
        $this->session->set_userdata('owner_id',$owner_id);
        redirect('mapping/maptochannel','refresh');
    }

    function maptochannel($channel_id,$property_id,$rate_id="")
    {   
        //echo insep_decode($channel_id); die;
        $data['mapping_values'] ="";
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()) || admin_id()!='' && admin_type()=='1')
        {
            $main_room = $this->uri->segment(6);
            if(insep_decode($channel_id)=='1')
            {
                $result = $this->mapping_model->import_check($channel_id);
                $data['expedia'] = $this->mapping_model->get_mapping_rooms(insep_decode($channel_id),'update');
                $data['mapping_values'] =get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
                $data['explevel'] = get_data(MAP,array('mapping_id'=>insep_decode($property_id),'channel_id' => insep_decode($channel_id)))->row()->explevel;
            }
      
            else if(insep_decode($channel_id)=='11')
            {
                $available = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
                $data['reconline'] = $this->mapping_model->get_mapping_rooms(insep_decode($channel_id),'update');
                $data['mapping_values'] =get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
            else if(insep_decode($channel_id)=='2')
            {
                $available = get_data(BOOKING,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
                $data['mapping_values'] =get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
                $data['booking'] = $this->mapping_model->get_mapping_rooms(insep_decode($channel_id),'update');
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
            else if(insep_decode($channel_id)=='8')
            {
                $available = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
                $data['gta'] = $this->mapping_model->get_mapping_rooms(insep_decode($channel_id),'update');
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
            else if(insep_decode($channel_id)=='5')
            {
                $available = get_data('import_mapping_HOTELBEDS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
                $data['mapping_values'] =get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
                $data['hotelbeds'] = $this->mapping_model->get_mapping_rooms(insep_decode($channel_id),'update');
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
			else if(insep_decode($channel_id)=='17')
            {
				require_once(APPPATH.'controllers/bnow.php'); 
				$callAvailabilities 		= 	new Bnow();
				$mapping_settings 			= 	$callAvailabilities->maptochannel($channel_id,$property_id);
				$data['bnow'] 				= 	$mapping_settings['bnow'];
                $available 					= 	$mapping_settings['available'];
				$data['mapping_values'] 	=	$mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
			else if(insep_decode($channel_id)=='15')
            {
                require_once(APPPATH.'controllers/travelrepublic.php'); 
                $callAvailabilities         =   new Travelrepublic();
                $mapping_settings           =   $callAvailabilities->maptochannel($channel_id,$property_id);
                $data['travel']               =   $mapping_settings['travel'];
                $available                  =   $mapping_settings['available'];
                $data['mapping_values']     =   $mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
            else if(insep_decode($channel_id)=='14')
            {
                require_once(APPPATH.'controllers/wbeds.php'); 
                $callAvailabilities         =   new wbeds();
                $mapping_settings           =   $callAvailabilities->maptochannel($channel_id,$property_id);
                $data['wbeds']               =   $mapping_settings['wbeds'];
                $available                  =   $mapping_settings['available'];
                $data['mapping_values']     =   $mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
            else if(insep_decode($channel_id)=='36')
            {
                require_once(APPPATH.'controllers/despegar.php'); 
                $callAvailabilities         =   new despegar();
                $mapping_settings           =   $callAvailabilities->maptochannel($channel_id,$property_id);
                $data['despegar']           =  $mapping_settings['despegar'];
                $available                  =   $mapping_settings['available'];
                $data['mapping_values']     =   $mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
              else if(insep_decode($channel_id)=='40' || insep_decode($channel_id)=='41' || insep_decode($channel_id)=='42')
            {
                require_once(APPPATH.'controllers/hotusagroup.php'); 
                $callAvailabilities         =   new hotusagroup();
                $mapping_settings           =   $callAvailabilities->maptochannel($channel_id,$property_id);
                $data['hotusagroup']           =  $mapping_settings['hotusagroup'];
                $available                  =   $mapping_settings['available'];
                $data['mapping_values']     =   $mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }
             else if(insep_decode($channel_id)=='19')
            {
                require_once(APPPATH.'controllers/agoda.php'); 
                $callAvailabilities         =   new agoda();
                $mapping_settings           =   $callAvailabilities->maptochannel($channel_id,$property_id);
                $data['agoda']           =  $mapping_settings['agoda'];
                $available                  =   $mapping_settings['available'];
                $data['mapping_values']     =   $mapping_settings['mapping_values'];
                if(count($available)!='0')
                {
                    $result=$available;
                }
                else
                {
                    $result='';
                }
            }

            else
            {
                $result='';
            }
            
            if($result=='')
            {
             $this->session->set_flashdata('uncheck','Need to import the channel');
                redirect('mapping/settings/'.$channel_id,'refresh');
                
            }
            
            if($rate_id=='update')
            {
                $this->session->set_userdata('map_to_channel','map_to_channel');
            }
            else
            {
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
            if($this->session->userdata('map_to_channel')!='')
            {
                if($rate_id!='update')
                {
                    $this->session->unset_userdata('map_to_channel');
                }
                if($main_room=='main_room' || $main_room=='sub_room' || $main_room=='update')
                {
                    $data['page_heading'] = 'Update Mapping';
                    $property_id = insep_decode($property_id);
                    $connect = insep_decode($channel_id);
                    $owner_id = current_user_type();

					$data['property_name']=$this->db->query("Select * from  manage_hotel where owner_id=".$owner_id." AND hotel_id=".hotel_id()."")->row()->property_name;
					$data['channelname']=$this->db->query("Select * from  manage_channel where channel_id=".$connect."")->row()->channel_name;
					$data['channel_id']=$connect;
					$data['property_id']=$property_id;
					if($rate_id=='update')
					{
						if($connect == 1){
							$query = $this->db->query('SELECT * FROM roommapping, import_mapping where roommapping.mapping_id = '.$property_id.' AND roommapping.owner_id='.current_user_type().' AND roommapping.hotel_id='.hotel_id().' AND roommapping.channel_id='.$connect.' AND import_mapping.map_id = roommapping.import_mapping_id');
						}else{
							$query=$this->db->query('Select * from roommapping where mapping_id='.$property_id.' AND owner_id='.current_user_type().' AND hotel_id='.hotel_id().' AND channel_id='.$connect.'');
						}
					}
					else
					{
						$query='no';
					}

                    
                    $data['result']="";
                    if($query!='no')
                    {
                        if($query->num_rows==1)
                        {
                            $data['result']=$query->result();
                        }
                    }
                    $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                    $data= array_merge($user_details,$data);
                    $data['property_id'] = ($property_id);
                    $data['channel_id']= ($channel_id);
                    if($main_room=='sub_room')
                    {
                        $data['map_room_method'] = $main_room;
                        $data['map_guest_count'] = $this->input->post('guest_count');
                        $data['map_refun_type']  = $this->input->post('refun_type');
                    }
                    $data['connected_room'] = $this->mapping_model->connected_room($connect);
                    $data['not_channel_details']=$this->mapping_model->not_mapped_rooms($connect,'fetch');
                    $this->views('channel/maptochannel',$data);
                }
                else if($main_room==$rate_id)
                {
                    $data['page_heading'] = 'Map to channel';
                    $rate_id = insep_decode($rate_id);
                    $property_id = insep_decode($property_id);
                    $connect = insep_decode($channel_id);
                    $owner_id = current_user_type();

					$data['property_name']=$this->db->query("Select * from  manage_hotel where owner_id=".$owner_id." AND hotel_id=".hotel_id()."")->row()->property_name;
					
					$data['channelname']=$this->db->query("Select * from  manage_channel where channel_id=".$connect."")->row()->channel_name;
					
					$data['channel_id']=$connect;
					$data['property_id']=$property_id;
					if($rate_id=='update')
					{
						$query=$this->db->query('Select * from roommapping where mapping_id='.$property_id.' AND owner_id='.current_user_type().' AND hotel_id='.hotel_id().' AND channel_id='.$connect.'');
					}
					else
					{
						$query='no';
					}
                    
                    $data['result']="";
                    if($query!='no')
                    {
                        if($query->num_rows==1)
                        {
                         $data['result']=$query->result();
                        }
                    }
                    $user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
                    $data= array_merge($user_details,$data);
                    $data['property_id'] = ($property_id);
                    $data['channel_id']= ($channel_id);
                    if($this->input->post('sub_rate_room_map')=='sub_rate_room_map')
                    {
                        $data['map_room_method'] = 'sub_rate_room_map';
                        $data['map_guest_count'] = $this->input->post('guest_count');
                        $data['map_refun_type']  = $this->input->post('refun_type');
                    }
                    else
                    {
                        $data['map_room_method'] ='';
                    }
                    $data['connected_room'] = $this->mapping_model->connected_room($connect);
                    $data['not_channel_details']=$this->mapping_model->not_mapped_rooms($connect,'fetch');

                    $this->views('channel/maptochannel',$data);
                }
            }
            else
            {
                
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    function save_mapping()
    {
		
		
		// echo "<pre>";
		// $data=$_POST;
		// print_r($data);
		// die;
		
		
		
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if($this->input->post('mapping_insert')=='')
        {
            $this->session->set_flashdata('map_update','Room Updated Successfully!!!');
        }
        else
        {
            $this->session->set_flashdata('map_insert','Room Mapped Successfully!!!');
        }
        if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()) || admin_id()!='' && admin_type()=='1')
        {
			
		  
		if(isset($_POST['save']))
		{
			$data=$_POST;
		    $channel_id=$data['chan_id'];
			require_once(APPPATH.'controllers/agoda.php'); 
			$callAvailabilities = new agoda();
			$result=$callAvailabilities->save_mapping($data);
			
			if($result==1)
			{
			$this->session->set_flashdata('message', 'Room Mapped Successfully!!!');
			redirect('mapping/settings/'.$this->input->post('chan_id'),'refresh');
					
			}
		  
		}
	
		
			
			
            $this->mapping_model->save_mapping();
            redirect('mapping/settings/'.$this->input->post('channel_id'),'refresh');
        }
        else
        { 
            redirect(base_url());
        }
    }
    
   function export_map($id,$property_id)
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
         if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()))
         {    
         $this->load->helper('csv_helper');
         $t_date=date('Y-m-d');
                $filename="stock_on_hand-".$t_date.".xls";
                $connect = insep_decode($id);
                $property_id = insep_decode($property_id);
                $result=$this->mapping_model->get_roommapping($connect,$property_id);
                 $channel_name = get_data(TBL_CHANNEL,array('channel_id'=>$connect))->row()->channel_name;
                 $date=date('Y-m-d h:m:sa');
                 $bdata['download_date']=$date;
                 update_data(TBL_CHANNEL,$bdata,array('channel_id'=>$connect));
                 $test = $channel_name."  Room mapping";
                 $test.="\n";
                 $test .="Property Name \t Room name \t Enabled \t Included Occupancy \t Extra Adult \t Extra Child \t Single Guest \t";
                  $test.="\n";
                   if(isset($result)){
                  foreach($result as $row){
                  $owner_id=$row->owner_id;
                  //get property name
                 $uname = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->user_name;
                 $pname = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->property_name;
               
                  $property_id=$row->property_id;
                 //get_propertname
               
                 $room_name = get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->property_name;
                  $enabled=$row->enabled;
                  $included_occupancy=$row->included_occupancy;
                  $extra_adult=$row->extra_adult;
                  $extra_child=$row->extra_child;
                  $single_quest=$row->single_quest;
                  $test.=$pname."\t".$room_name."\t".$enabled."\t".$included_occupancy."\t".$extra_adult."\t".$extra_child."\t".$single_quest."\t";
                   $test.="\n";
                 }
               }
                 header("Content-type: application/csv");
                //header("Content-type: application/vnd.ms-word");
                //header("Content-type: text/plain");
                header('Content-Type: text/plain; charset=utf-8');
                header("Content-Disposition: attachment; filename=".$filename);
                header("Pragma: no-cache");
                $this->load->helper('file');
                write_file('./uploads/'.$filename, $test);
                $data = file_get_contents("./uploads/".$filename);
                //echo $data;
                //die;
                $urfile="Your_stock_on_hand.csv";
                $this->load->helper('download');
                force_download($urfile, $data); 
                redirect('mapping/settings','refresh');
         }
         else
         {
             redirect(base_url());
         }

            
    }
    
    // connect_channel...
    function connect_channel()
	{
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
	
        if($this->input->post('save_clicking'))
		{
			$channel_id = insep_decode($this->input->post('channel_id')); 
			$channel = get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row()->seo_url;
			$result = $this->mapping_model->connect_channel($channel_id);
			if($result)
			{   
				 redirect('channel/view_channel/'.$channel,'refresh');
			}
			else
			{
				redirect('channel/view_channel/'.$channel,'refresh');
			}
        }
		else
		{
			redirect(base_url());
		}
    }


// 22/12/2015...


    function get_rate_types()

    {
        // echo 'hi hello';die;

    $prop_id = $_REQUEST['val'];
    
    // $get_room = $this->mapping_model->get_rooms($prop_id);

    $get_rate=$this->mapping_model->get_ratetypes($prop_id);     

    echo '<option value="">Select the rate types</option>';

    foreach($get_rate as $val)

    {

     echo '<option value='.$val->rate_type_id.'  name="'.$val->rate_name.'">'.$val->rate_name.'</option>';

    }      

    }
	
	function downsp()
	{
		$this->load->dbutil();		
		$backup =& $this->dbutil->backup();		
		$this->load->helper('file');
		write_file('/images/mybackup.gz', $backup);		
		$this->load->helper('download');
		force_download('mybackup.gz', $backup);
	}
	
    
    function getchannel($channel_id = '')
    {
		
       
            $this->is_login();
   
			if($channel_id == ''){
				extract($this->input->post());
			}


            if($channel_id!='')
            {
                $cha_name = ucfirst(get_data(TBL_CHANNEL,array('channel_id'=>insep_decode($channel_id)))->row()->channel_name);

            }
            else
            {
                $cha_name ='';
            }
            /*----------- Expedia Import ------------------ */
            if(insep_decode($channel_id)=='1')
            {
                $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
                $urls = explode(',', $re_details->test_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $exp[$path[0]] = $path[1];
                    }
                /*if($re_details->mode == 0){
                    $urls = explode(',', $re_details->test_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $exp[$path[0]] = $path[1];
                    }
                }else if($re_details->mode == 1){
                    $urls = explode(',', $re_details->live_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $exp[$path[0]] = $path[1];
                    }
                }*/
				/* echo ' mode = '.$re_details->mode;  */
                    $xml_data =' <ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">

                        <Authentication username="'.$re_details->user_name.'" password="'.$re_details->user_password.'"/>

                        <Hotel id="'.$re_details->hotel_channel_id.'"/>

                        <ParamSet>

                            <ProductRetrieval returnRateLink="true" returnRoomAttributes="true" returnRatePlanAttributes="true" returnCompensation="true"/>

                        </ParamSet>

                    </ProductAvailRateRetrievalRQ>
                    ';
        
                    //$URL = "https://ws.expediaquickconnect.com/connect/parr";
                    $URL = trim($exp['irate_avail']);
                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
					/* echo 'output'.$output;
					echo '<pre>';
					print_r($data_api);
					die; */
						
                    $response = @$data_api->Error;
					
                    if($response!='')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!';
                        echo json_encode($meg);
                    }
                    else
                    {
                        //$this->db->query("truncate import_mapping");
                        $Hotelarray = $data_api->ProductList->Hotel;
						/* echo '<pre>';
					print_r($Hotelarray);
					die; */
                        $hotel_id=$Hotelarray->attributes()->id;
                        $hotel_name=$Hotelarray->attributes()->name;
                        $RoomTypearray = $data_api->ProductList->RoomType;
                        $rate_id="";
                        $id="";
                        $code="";
                        $roomtype_name="";
                        $name="";
                        $status="";
                        $type="";
                        $distributionModel="";
                        $rateAcquisitionType="";
                        $pricingModel="";
                        foreach ($RoomTypearray as $key => $rtype) 
                        {
                            $attr=$rtype->attributes();
                            $ratePlan=$rtype->RatePlan;
                            $rateThreshold = $rtype->RateThreshold;
                            $bedType = $rtype->BedType->attributes();
                            $occupancybyage = $rtype->OccupancyByAge;
                            
                            $first_data=array(
                                            'user_id' => current_user_type(),
                                            'channel'=>insep_decode($channel_id),
                                            'hotel_channel_id'=>$hotel_id,
                                            'hotel_id' => hotel_id(),
                                            'roomtype_id'=>(string)$attr['id'],
                                            'roomtype_name'=>(string)$attr['name'],
                                            'rate_type_id'=>'',    
                                            'code'=>(string)$attr['code'],
                                            'name'=>'',
                                            'status'=>(string)$attr['status'], 
                                            'type'=>'',    
                                            'distributionModel'=>'',    
                                            'rateAcquisitionType'=>'',    
                                            'pricingModel'=>'',
                                            'rateType' => '',
                                            'minLos' => '',
                                            'maxLos' => '',
                                            'minAmount'=>'',
                                            'maxAmount' => '',
                                            'minadvBook' => '',
                                            'maxadvBook' => '',
                                            'bedtype_id' => (string)$bedType['id'],
                                            'bedtype_name' =>(string)$bedType['name'],
                                            'ageCategory' => '',
                                            'minage' =>'',
                                            'maxoccupants' => ''
                                        );
                                        
                            $first_query=$this->db->query('Select * from import_mapping where channel="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotel_channel_id="'.$hotel_id.'" AND roomtype_id="'.$attr['id'].'" AND rate_type_id=""');
                            if($first_query->num_rows >= 1)
                            {
                                $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id
                                (),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rate_type_id'=>''));
                                 $this->db->update('import_mapping',$first_data);
                            }
                            else
                            {
                                $this->db->insert('import_mapping',$first_data);
                            }

                            foreach ($rateThreshold as $var => $rt) {
                                $data = array(
                                        'user_id' => current_user_type(),
                                        'channel'=>insep_decode($channel_id),
                                        'hotel_channel_id'=>$hotel_id,
                                        'hotel_id' => hotel_id(),
                                        'roomtype_id'=>(string)$attr['id'],
                                        'roomtype_name'=>(string)$attr['name'],
                                        'rateType' => (string)$rt->attributes()->type,  
                                        'minAmount' => (string)$rt->attributes()->minAmount,
                                        'maxAmount' => (string)$rt->attributes()->maxAmount,
                                        
                                    );
                                $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rateType' => (string)$rt->attributes()->type));
                                $query = $this->db->get('import_mapping_expedia_ratelimit');
                                if($query->num_rows == 1)
                                {
                                     $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rateType' => (string)$rt->attributes()->type));
                                     $this->db->update('import_mapping_expedia_ratelimit',$data);
                                }
                                else
                                {
                                    $query2 = $this->db->query('Select * from import_mapping_expedia_ratelimit where channel="'.insep_decode($channel_id).'" AND hotel_channel_id="'.$hotel_id.'" AND roomtype_id="'.$attr['id'].'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND rateType=""');
                                    if($query2->num_rows == 1){
                                         $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rateType' => ''));
                                         $this->db->update('import_mapping_expedia_ratelimit',$data);
                                    }else{
                                        
                                        $this->db->insert('import_mapping_expedia_ratelimit',$data);
                                    }
                                }
                            }

                            foreach ($occupancybyage as $var => $oba) {
                                $data = array(

                                        'user_id' => current_user_type(),
                                        'channel'=>insep_decode($channel_id),
                                        'hotel_channel_id'=>$hotel_id,
                                        'hotel_id' => hotel_id(),
                                        'roomtype_id'=>(string)$attr['id'],
                                        'roomtype_name'=>(string)$attr['name'],
                                        'ageCategory' => (string)$oba->attributes()->ageCategory,
                                        'minage' => (string)$oba->attributes()->minAge,
                                        'maxoccupants' => (string)$oba->attributes()->maxOccupants,
                                    );
                                $query=$this->db->query('Select * from import_mapping_expedia_occupancy where channel="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotel_channel_id="'.$hotel_id.'" AND roomtype_id="'.$attr['id'].'" AND ageCategory="'.$oba->attributes()->ageCategory.'"');
                                if($query->num_rows == 1){

                                    $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'], 'ageCategory' => (string)$oba->attributes()->ageCategory));
                                     $this->db->update('import_mapping_expedia_occupancy',$data);
                                }else{
                                    $query2=$this->db->query('Select * from import_mapping_expedia_occupancy where channel="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotel_channel_id="'.$hotel_id.'" AND roomtype_id="'.$attr['id'].'" AND ageCategory="" ');
                                    if($query2->num_rows == 1){
                                        $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rate_type_id'=>'','ageCategory' => ''));
                                        $this->db->update('import_mapping_expedia_occupancy',$data);
                                    }else{
                                        $this->db->insert('import_mapping_expedia_occupancy',$data);

                                    }
                                }
                            }
                            
                            foreach ($ratePlan as $var=>$rp) 
                            {
                                $rate_id=(string)$attr['id'];
                                $roomtype_name=(string)$attr['name'];
                                $id=(string)$rp->attributes()->id;
                                $parent_id = (string)$rp->attributes()->parentId;
                                $code=(string)$rp->attributes()->code;
                                $name =(string)$rp->attributes()->name;
                                $status =(string)$rp->attributes()->status;
                                $type =(string)$rp->attributes()->type;
                                $distributionModel=(string)$rp->attributes()->distributionModel;
                                $rateAcquisitionType=(string)$rp->attributes()->rateAcquisitionType;
                                $pricingModel=(string)$rp->attributes()->pricingModel;
                                $minLos = (string)$rp->attributes()->minLOSDefault;
                                $maxLos = (string)$rp->attributes()->maxLOSDefault;
                                $minadvBook = (string)$rp->attributes()->minAdvBookDays;
                                $maxadvBook = (string)$rp->attributes()->maxAdvBookDays;

                                $data=array(
                                                'user_id' => current_user_type(),
                                                'channel'=>insep_decode($channel_id),
                                                'hotel_channel_id'=>$hotel_id,
                                                'hotel_id' => hotel_id(),
                                                'roomtype_name'=>$roomtype_name,
                                                'roomtype_id'=>$rate_id,
                                                'rateplan_id' => $parent_id,
                                                'rate_type_id'=>$id,    
                                                'code'=>$code,
                                                'name'=>$name,
                                                'status'=>$status, 
                                                'type'=>$type,    
                                                'distributionModel'=>$distributionModel,    
                                                'rateAcquisitionType'=>$rateAcquisitionType,    
                                                'pricingModel'=>$pricingModel,
                                                'minLos' => $minLos,
                                                'maxLos' => $maxLos,
                                                'minadvBook' => $minadvBook,
                                                'maxadvBook' => $maxadvBook,
                                              );
                                              
                                $query=$this->db->query('Select * from import_mapping where channel="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotel_channel_id="'.$hotel_id.'" AND rate_type_id="'.$id.'"');
                                if($query->num_rows==1)
                                {
                                     $this->db->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>$hotel_id,'rate_type_id'=>$id));
                                     $this->db->update('import_mapping',$data);
                                }
                                else
                                {
                                    $this->db->insert('import_mapping',$data);
                                }
                            }
                        }
                        $meg['result'] = '1';
                        $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
                        echo json_encode($meg);
                    }
                    curl_close($ch);   
            }
            /*----------- End Of Expedia Import ------------------ */
            else if(insep_decode($channel_id)=='40' || insep_decode($channel_id)=='41' || insep_decode($channel_id)=='42')
            {
                require_once(APPPATH.'controllers/hotusagroup.php'); 
                $hotusa = new hotusagroup();
                $result =   $hotusa->getRoomsVar(insep_decode($channel_id),hotel_id(),current_user_type());
                if(@$result['result']==0)
                {
                     $results['content'] =$result['content'].' from '.$cha_name.'. Try again!';
                }
                else if(@$result['Enable'] == 'Enable')
                {
                    $results['content'] ='XML connection DeActive from '.$cha_name.'. Try again!';
                }
                else if(@$result['result']==0)
                {
                    $results['content'] ='All Rooms Imported from '.$cha_name.'!!!';
                }
                else
                {
                    $results['content'] =$cha_name.'. Try again!';
                }
                echo json_encode($results);
                return;

            }
            /*----------- Agoda Import ------------------ */
            else if(insep_decode($channel_id)=='19')
            {
               $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>19))->row();
                
                if($re_details->mode == 0){
                      $urls = $re_details->test_url;
                  }else{
                      $urls =$re_details->live_url;
                  }

                  $xml_data ='<?xml version="1.0" encoding="UTF-8"?>
                      <request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="5">
                        <criteria>
                          <property id="'.$re_details->hotel_channel_id.'"/>
                        </criteria>
                      </request>';

                      $Email_data= 'Request >>'.$xml_data;
                  $URL = trim($urls);
                  $ch = curl_init();
                  curl_setopt( $ch, CURLOPT_URL, $URL );
                  curl_setopt( $ch, CURLOPT_POST, true );
                  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                  curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data);
                  $output = curl_exec($ch);
                  $Email_data   .= 'Response >>'.$output;
                  mail("Datahernandez@gmail.com","Agodatest",$Email_data);
                  $data_api = simplexml_load_string($output);
                    $response = @$data_api->errors;
                    //print_r($data_api);
                    if($response!='')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!';
                        echo json_encode($meg);
                    }
                    else
                    {
                        //$this->db->query("truncate import_mapping");
                      
                        $Hotelarray = @$data_api->property;
                        $hotel_id=$Hotelarray['id'];
                        $hotel_name=@$Hotelarray['name'];
                        if ($hotel_name!='')
                        {
                             $RoomTypearray = $Hotelarray->rooms->room;
                             $currency= $Hotelarray['currency'];
                                $RatePlanarray = $Hotelarray->rateplans->rateplan;
                                $Productsarray = $Hotelarray->products->product;
                                 for ($pro_i=0;$pro_i<count($Productsarray);$pro_i++) 
                                {   
                                    $attr=$Productsarray[$pro_i];
                                    $rateplan='';$roomplan='';
                                    for ($rate_i=0;$rate_i<count($RatePlanarray);$rate_i++) 
                                    {
                                        if((string)$RatePlanarray[$rate_i]['rateplan_id']==(string)$attr['rateplan_id'])
                                        {
                                            $rateplan=$RatePlanarray[$rate_i];
                                            break;
                                        }

                                    }
                                    for ($rate_i=0;$rate_i<count($RoomTypearray);$rate_i++) 
                                    {
                                        if((string)$RoomTypearray[$rate_i]['room_id']==(string)$attr['room_id'])
                                        {
                                            $roomplan=$RoomTypearray[$rate_i];
                                            break;
                                        }

                                    }
                                    // $ratePlan=$rtype->RatePlan;
                                    // $rateThreshold = $rtype->RateThreshold;
                                    // $bedType = $rtype->BedType->attributes();
                                    // $occupancybyage = $rtype->OccupancyByAge;
                                    
                                    $first_data=array(
                                                    'user_id' => current_user_type(),
                                                    'channel'=>insep_decode($channel_id),
                                                    'hotel_channel_id'=>(string)$hotel_id,
                                                    'hotel_id' => hotel_id(),
                                                    'roomtype_id'=>(string)$attr['room_id'],
                                                    'roomtype_name'=>(string)$roomplan['room_name'],
                                                    'rate_type_id'=>(string)$attr['rateplan_id'],    
                                                    'code'=>'',
                                                    'name'=>(string)$rateplan['rateplan_name'],
                                                    'status'=>'Active', 
                                                    'type'=>'',    
                                                    'distributionModel'=>(string)$Hotelarray['occupancy_model'],    
                                                    'rateAcquisitionType'=>'',    
                                                    'pricingModel'=>(string)$Hotelarray['rate_type'],
                                                    'rateType' =>(string)$Hotelarray['rate_type'],
                                                    'minLos' =>(string)$rateplan['min_los'],
                                                    'maxLos' => (string)$rateplan['max_los'],
                                                    'minAmount'=>(string)$roomplan['min_rate'],
                                                    'maxAmount' => (string)$roomplan['max_rate'],
                                                    'minadvBook' =>  (string)$rateplan['min_adv_days'],
                                                    'maxadvBook' =>  (string)$rateplan['max_adv_days'],
                                                    'bedtype_id' => '',
                                                    'bedtype_name' =>'',
                                                    'ageCategory' => '',
                                                    'minage' =>'',
                                                    'maxoccupants' =>(string)$roomplan['total_persons'],
                                                    'currency'=>(string)$currency
                                                );
                                    
                                    $first_query=$this->db->query('Select * from import_mapping_AGODA where channel="'.insep_decode($channel_id).'" AND  hotel_id="'.hotel_id().'" AND hotel_channel_id="'.(string)$hotel_id.'" AND roomtype_id="'.(string)$attr['room_id'].'" AND rate_type_id="'.(string)$attr['rateplan_id'].'"');
                                    if($first_query->num_rows >= 1)
                                    {
                                        $this->db->where(array('hotel_id'=>hotel_id(),'channel'=>insep_decode($channel_id),'hotel_channel_id'=>(string)$hotel_id,'roomtype_id'=>(string)$attr['room_id'],'rate_type_id'=>(string)$attr['rateplan_id']));
                                         $this->db->update('import_mapping_AGODA',$first_data);
                                    }
                                    else
                                    {
                                        $this->db->insert('import_mapping_AGODA',$first_data);
                                    }

                                }
                                $meg['result'] = '1';
                                $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
                                //echo json_encode($meg);
                        }
                       
                    }



                    curl_close($ch);   
            }
            /*----------- End Of Agoda Import ------------------ */

             /*----------- DESPEGAR Import ------------------ */
            else if(insep_decode($channel_id)=='36')
            {
                
                $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
                

                //importar los rateType los nombres



                 


                $xml_data = '<OTA_HotelRatePlanRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="2">
                  <RatePlans>
                    <RatePlan>
                      <HotelRef HotelCode="'.$re_details->hotel_channel_id.'"/>
                    </RatePlan>
                  </RatePlans>
                </OTA_HotelRatePlanRQ>';
        
               
                    $URL = 'https://channel.despegar.com/v1/hotels/rate-plans/list';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="'.$re_details->user_name.'", password="'.$re_details->user_password.'", hotel="'.$re_details->hotel_channel_id.'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {

                          foreach ($data_api->RatePlans->RatePlan as $key => $rtype) 
                          {
                            $RatePlanCode= $rtype->attributes()->RatePlanCode;
                            $Text= $rtype->Description->Text;
                            $CurrencyCode=$rtype->attributes()->CurrencyCode;
                            $ChargeTypeCode=$rtype->attributes()->ChargeTypeCode;
                                    



                                    $first_query=$this->db->query('Select * from despegar_rate where  user_id ="'.current_user_type().'" AND hotelid="'.hotel_id().'" AND RatePlanCode="'.$RatePlanCode.'" AND HotelCode="'.$re_details->hotel_channel_id.'" ');


                            if($first_query->num_rows >= 1)
                            {

                            $update = $this->db->query('update despegar_rate set RatePlanCode = "'.$RatePlanCode.
                                '", rateplanname ="'.$Text.'", CurrencyCode ="'.$CurrencyCode.'",ChargeTypeCode="'.$ChargeTypeCode.'" where  user_id ="'.current_user_type().'" AND hotelid="'.hotel_id().'" AND RatePlanCode="'.$RatePlanCode.'" AND HotelCode="'.$re_details->hotel_channel_id.'" ');

                            }
                            else
                            {
                            $bdata['user_id'] =  current_user_type();
                            $bdata['hotelid'] =  hotel_id();
                            $bdata['RatePlanCode']= (string)$RatePlanCode;
                             $bdata['rateplanname']= (string)$Text;
                             $bdata['HotelCode']= (string)$re_details->hotel_channel_id;
                             $bdata['CurrencyCode']= (string)$CurrencyCode;
                             $bdata['ChargeTypeCode']= (string)$ChargeTypeCode;

                                insert_data("despegar_rate",$bdata);


                             }

                          }
                       
                    }



                    //Inportar las habitaciones mas su ratetype


                         $xml_data = '<OTA_HotelAvailGetRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.000">
                      <HotelAvailRequests>
                        <HotelAvailRequest>
                          <HotelRef HotelCode="'.$re_details->hotel_channel_id.'"/>
                        </HotelAvailRequest>
                      </HotelAvailRequests>
                    </OTA_HotelAvailGetRQ>';
        
               
                    $URL = 'https://channel.despegar.com/v1/hotels/availability/list';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="'.$re_details->user_name.'", password="'.$re_details->user_password.'", hotel="'.$re_details->hotel_channel_id.'"','Content-Type: application/xml'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {
                        $this->inventory_model->store_error(current_user_type(),ohtel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {

                          foreach ($data_api->AvailStatusMessages->AvailStatusMessage as $key => $rtype ) 
                          {


                            $InvCode=$rtype->StatusApplicationControl->attributes()->InvCode;
                            $RatePlanCode=$rtype->StatusApplicationControl->attributes()->RatePlanCode;


                            $first_querys=$this->db->query('Select * from despegar_roomrates where  user_id ="'.current_user_type().'" AND hotelid="'.hotel_id().'" AND RatePlanCode="'.$RatePlanCode.'" AND invcode="'.$InvCode.'"');


                            if($first_querys->num_rows >= 1)
                            {

                                $update = $this->db->query('update despegar_roomrates set RatePlanCode = "'.$RatePlanCode.
                                '", InvCode ="'.$InvCode.' where  user_id ="'.current_user_type().'" AND hotelid="'.hotel_id().'" AND RatePlanCode="'.$RatePlanCode.'" AND InvCode="'.$InvCode.'"');

                            }
                            else
                            {
                                $bdata['user_id'] =  current_user_type();
                                $bdata['hotelid'] =  hotel_id();
                                $bdata['invcode']= (string)$InvCode;
                                $bdata['rateplancode']= (string)$RatePlanCode;


                                insert_data("despegar_roomrates",$bdata);
                             }


                          }
  
                    }


       






                $xml_data = '<OTA_HotelDescriptiveInfoRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.002" >
                                  <HotelDescriptiveInfos>
                                    <HotelDescriptiveInfo HotelCode="'.$re_details->hotel_channel_id.'" />
                                  </HotelDescriptiveInfos>
                                </OTA_HotelDescriptiveInfoRQ>';
        
                    //$URL = "https://ws.expediaquickconnect.com/connect/parr";
                    $URL = 'https://channel.despegar.com/v1/hotels/hotel-info/get';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="'.$re_details->user_name.'", password="'.$re_details->user_password.'", hotel="'.$re_details->hotel_channel_id.'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {
                       $Hotelarray = $data_api->HotelDescriptiveContents->HotelDescriptiveContent;
                         $hotel_id=$Hotelarray->attributes()->HotelCode;
                         $HotelName = $Hotelarray->attributes()->HotelName;
                        $RoomTypearray = $Hotelarray->FacilityInfo->GuestRooms->GuestRoom;
                        $TaxPolicyType =$Hotelarray->Policies->Policy->TaxPolicies->TaxPolicy->attributes()->Type;


                         foreach ($RoomTypearray as $key => $rtype) 
                        {                           
                            $IdRoom=$rtype->attributes()->ID;
                            $MaxAdultOccupancy = $rtype->attributes()->MaxAdultOccupancy;
                            $MaxChildOccupancy = $rtype->attributes()->MaxChildOccupancy;
                            $MaxOccupancy = $rtype->attributes()->MaxOccupancy;
                            $MinOccupancy = $rtype->attributes()->MinOccupancy;
                            $StandardOccupancy = $rtype->TypeRoom->attributes()->StandardOccupancy;
                            $Name = $rtype->TypeRoom->attributes()->Name;




                      
                            $ratetypeall = $this->db->query('select a.RatePlanCode,rateplanname from despegar_roomrates as a left join despegar_rate as b on a.rateplancode = b.rateplancode and a.hotelid = b.hotelid where  a.user_id ="'.current_user_type().'" AND a.hotelid="'.hotel_id().'" AND InvCode="'.$IdRoom.'"');

                                    $first_querys=$this->db->query('Select * from import_mapping_DESPEGAR where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" and ratetypecode=0 ');

                                              if($first_querys->num_rows >= 1)
                                            {
                                            $update = $this->db->query('update import_mapping_DESPEGAR set nameroomtype = "'.$Name.'", maximumAdults ="'.$MaxAdultOccupancy.'", maximumChilds="'.$MaxChildOccupancy.'", hotel_name="'.$HotelName.'",maximumPaxes="'.$MaxOccupancy.'", minimumPaxes="'.$MinOccupancy.'", StandardOccupancy="'.$StandardOccupancy.'",TaxPolicyType="'.$TaxPolicyType.'"   where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" and ratetypecode = 0');

                                            }
                                            else
                                            {

                                                $bdata['user_id'] =  current_user_type();
                                                $bdata['hotel_id'] =  hotel_id();
                                                $bdata['channel_id']= (int)insep_decode($channel_id);
                                                $bdata['HotelCode']= (string)$hotel_id;
                                                $bdata['coderoomtype']= (string)$IdRoom;
                                                $bdata['nameroomtype']= (string)$Name;
                                                $bdata['maximumAdults']= (string)$MaxAdultOccupancy;

                                                $bdata['maximumChilds'] =  (string)$MaxChildOccupancy;
                                                $bdata['maximumPaxes'] =  (string)$MaxOccupancy;
                                                $bdata['minimumPaxes']= (string)$MinOccupancy;
                                                $bdata['StandardOccupancy']= (string)$StandardOccupancy;
                                                $bdata['hotel_name']= (string)$HotelName;
                                                $bdata['Rate_Name']= (string)"";
                                                $bdata['ratetypecode']= (string)"0";
                                                $bdata['TaxPolicyType']= (string)$TaxPolicyType;
                                                 

                                                insert_data("import_mapping_DESPEGAR",$bdata);



                                             }



                                        foreach($ratetypeall->result() as $row)

                                        {


                                            $first_query=$this->db->query('Select * from import_mapping_DESPEGAR where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" and ratetypecode ="'.$row->RatePlanCode.'"');



                                            if($first_query->num_rows >= 1)
                                            {
                                            $update = $this->db->query('update import_mapping_DESPEGAR set nameroomtype = "'.$Name.'", maximumAdults ="'.$MaxAdultOccupancy.'", maximumChilds="'.$MaxChildOccupancy.'", hotel_name="'.$HotelName.'",maximumPaxes="'.$MaxOccupancy.'", minimumPaxes="'.$MinOccupancy.'", StandardOccupancy="'.$StandardOccupancy.'", Rate_Name ="'.$row->rateplanname.'",TaxPolicyType="'.$TaxPolicyType.'"   where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" and ratetypecode = "'.$row->RatePlanCode.'"');

                                            }
                                            else
                                            {

                                                $bdata['user_id'] =  current_user_type();
                                                $bdata['hotel_id'] =  hotel_id();
                                                $bdata['channel_id']= (int)insep_decode($channel_id);
                                                $bdata['HotelCode']= (string)$hotel_id;
                                                $bdata['coderoomtype']= (string)$IdRoom;
                                                $bdata['nameroomtype']= (string)$Name;
                                                $bdata['maximumAdults']= (string)$MaxAdultOccupancy;

                                                $bdata['maximumChilds'] =  (string)$MaxChildOccupancy;
                                                $bdata['maximumPaxes'] =  (string)$MaxOccupancy;
                                                $bdata['minimumPaxes']= (string)$MinOccupancy;
                                                $bdata['StandardOccupancy']= (string)$StandardOccupancy;
                                                $bdata['hotel_name']= (string)$HotelName;
                                                $bdata['Rate_Name']= (string)$row->rateplanname;
                                                $bdata['ratetypecode']= (string)$row->RatePlanCode;

                                                insert_data("import_mapping_DESPEGAR",$bdata);

                                             }

                                        }


                        }




                        $meg['result'] = '1';
                        $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!' ;
                        echo json_encode($meg);
                        
                    }




            }
            /*----------- End Of DESPEGAR Import ------------------ */
            else if(insep_decode($channel_id)=='11')
            {
                $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
                if($re_details->mode == 0){
                    $urls = explode(',', $re_details->test_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $reco[$path[0]] = $path[1];
                    }
                }else if($re_details->mode == 1){
                    $urls = explode(',', $re_details->live_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $reco[$path[0]] = $path[1];
                    }
                }
                $soapUrl = trim($reco['irate_avail']); // asmx URL of WSDL
                $soapUser = $re_details->user_name;  //  username
                $soapPassword = $re_details->user_password;// password
                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                      <soap12:Body>
                                        <GetRoomRateCodes xmlns="https://www.reconline.com/">
                                          <User>'.$soapUser.'</User>
                                          <Password>'.$soapPassword.'</Password>
                                          <idHotel>'.$re_details->hotel_channel_id.'</idHotel>
                                          <idSystem>0</idSystem>
                                          <ForeignPropCode></ForeignPropCode>
                                        </GetRoomRateCodes>
                                      </soap12:Body>
                                    </soap12:Envelope>
                                ';
                $headers = array(
                            "Content-type: application/soap+xml; charset=utf-8",
                            "Host:www.reconline.com",
                            "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL
        
                $url = $soapUrl;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
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
                $Errorarray = @$responseArray['soapBody']['GetRoomRateCodesResponse']['GetRoomRateCodesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                if($Errorarray!='')
                {
                    $counts = count($Errorarray);
                }
                else
                {
                    $counts = '0';
                    $Hotelarray = $responseArray['soapBody']['GetRoomRateCodesResponse']['GetRoomRateCodesResult']['diffgrdiffgram']['NewDataSet']['Room'];
                    $RateCodes  = $responseArray['soapBody']['GetRoomRateCodesResponse']['GetRoomRateCodesResult']['diffgrdiffgram']['NewDataSet']['RateCodes'];
                }
                if(count($Errorarray)=='0')
                {
                    foreach($Hotelarray as $id=>$room)
                    {
                        //echo $room['CODE'].' - '.$room['IDROOM'];
                        $rdata['user_id'] = current_user_type();
                        $rdata['hotel_id'] = hotel_id();
                        $rdata['channel_id'] = insep_decode($channel_id);
                        $rdata['hotel_channel_id'] = $room['IDHOTEL'];
                        $rdata['IDROOM'] = $room['IDROOM'];
                        $rdata['CODE'] = $room['CODE'];
                        $rdata['NORMBED'] = $room['NORMBED'];
                        $rdata['MAXBED'] = $room['MAXBED'];
                        $rdata['CRIB'] = $room['CRIB'];
                        $rdata['TEXT'] = $room['TEXT'];
                        
                        $rdata['IDRATELEVEL'] = $RateCodes['IDRATELEVEL'];
                        $rdata['RLCODE'] = $RateCodes['RLCODE'];
                        $rdata['IDRATECODE'] = $RateCodes['IDRATECODE'];
                        $rdata['RCCODE'] = $RateCodes['RCCODE'];
                        $rdata['RateCodes_TEXT'] = $RateCodes['TEXT'];
                        $rdata['PUBLICRATE'] = $RateCodes['PUBLICRATE'];
                        $rdata['AVAILTYPE'] = $RateCodes['AVAILTYPE'];
                        $rdata['AVAILINDEPENDENT'] = $RateCodes['AVAILINDEPENDENT'];
                        $rdata['RATEDEPMAIN']= $RateCodes['RATEDEPMAIN'];
                        $rdata['OFFMAINPCT'] = $RateCodes['OFFMAINPCT'];
                        $rdata['RATELOCKED'] = $RateCodes['RATELOCKED'];
                        
                        $available = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$room['IDHOTEL'],'IDROOM'=>$room['IDROOM']))->row_array();
                        if(count($available)!=0)
                        {
                            update_data(IM_RECO,$rdata,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$room['IDHOTEL'],'IDROOM'=>$room['IDROOM']));
                        }
                        else
                        {
                            insert_data(IM_RECO,$rdata);
                        }
                    }
                    $meg['result'] = '1';
                    $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
                    echo json_encode($meg);
                }
                else
                {
                    //echo $Errorarray['WARNINGID'];
                    //echo $Errorarray['WARNING'];
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray['WARNING'],'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Errorarray['WARNING'].' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);
                }
                curl_close($ch); 
            }
            else if(insep_decode($channel_id)=='2')
            {
                $bk_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
				if($bk_details->xml_type==2 || $bk_details->xml_type==3)
				{
					$xml_data ='
								<?xml version="1.0" encoding="UTF-8"?>
								<request>
								<username>'.$bk_details->user_name.'</username>
								<password>'.$bk_details->user_password.'</password>
								<hotel_id>'.$bk_details->hotel_channel_id.'</hotel_id>
								</request>
								';
					/* echo $xml_data; die; */
					$URL = "https://supply-xml.booking.com/hotels/xml/roomrates";
					$ch = curl_init($URL);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
					$data_api = simplexml_load_string($output); 
					/* echo '<pre>';
					print_r($data_api); die; */

                    mail("datahernandez@gmail.com"," mapping de booking",$output);
					$Errorarray = $data_api->fault;
					if(count($Errorarray)!=0)
					{
						$Errorarray = $data_api->fault->attributes();
						$this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Errorarray['string'],'Get Channel',date('m/d/Y h:i:s a', time()));
						$meg['result'] = '0';
						$meg['content']=$Errorarray['string'].' from '.$cha_name.'. Try again!';
						echo json_encode($meg);
					}
					elseif(count($Errorarray)==0)
					{
						foreach($data_api as $room_details)
						{
							$room_att = $room_details->attributes();
							$bdata['owner_id'] =  current_user_type();
							$bdata['hotel_id'] =  hotel_id();
							$bdata['channel_id'] = insep_decode($channel_id);
							$bdata['B_room_id'] = (string)$room_att['id'];
							$bdata['channel_hotel_id'] = (string)$room_att['hotel_id'];
							$bdata['hotel_name']= (string)$room_att['hotel_name'];
							$bdata['max_children'] = (string)$room_att['max_children'];
							$bdata['room_name']= (string)$room_att['room_name'];
                            $bdata['B_rate_id']= '0';
							
							$room_available = get_data(BOOKING,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>'0'))->row_array();
							if(count($room_available)!=0)
							{
								update_data(BOOKING,$bdata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>'0'));
							}
							else
							{
								insert_data(BOOKING,$bdata);
							}
							
							$room_rates = $room_details->rates->rate; 
							foreach($room_rates as $room_rate)
							{
								$rate_att = $room_rate->attributes();
								$brdata['owner_id'] =  current_user_type();
								$brdata['hotel_id'] =  hotel_id();
								$brdata['channel_id'] = insep_decode($channel_id);
								$brdata['channel_hotel_id'] = (string)$room_att['hotel_id'];
								$brdata['B_room_id'] = (string)$room_att['id'];
								$brdata['hotel_name']= (string)$room_att['hotel_name'];
								$brdata['room_name']= (string)$room_att['room_name'];
								$brdata['B_rate_id']= (string)$rate_att['id'];
								$brdata['max_persons'] = (string)$rate_att['max_persons'];
								$brdata['policy'] = (string)$rate_att['policy'];
								$brdata['policy_id'] = (string)$rate_att['policy_id'];
								$brdata['rate_name'] = (string)$rate_att['rate_name'];
								
								$rate_available = get_data(BOOKING,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>$rate_att['id']))->row_array();
								if(count($rate_available)!=0)
								{
									update_data(BOOKING,$brdata,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>$rate_att['id']));
								}
								else
								{
									insert_data(BOOKING,$brdata);
								}
							}
						}
						$meg['result'] = '1';
						$meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
						echo json_encode($meg);
					}
					curl_close($ch); 
				}
				else
				{
					$meg['result'] = '1';
					$meg['content']="Can't import room rate information from ".$cha_name."!!!";
					echo json_encode($meg);
				}
                
            }
            else if(insep_decode($channel_id)=='8')
            {
                $gt_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
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
                //$soapUrl = "https://hotels.demo.gta-travel.com/supplierapi/rest/rooms/search";
                $soapUrl = trim($gta['roomsearch']);
                $xml_post_string = '<GTA_RoomsReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                <Property Id="'.$gt_details->hotel_channel_id.'" />
                </GTA_RoomsReadRQ>';  
			//echo $xml_post_string;	
                $ch = curl_init($soapUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $data = simplexml_load_string($output); 
                        curl_close($ch);
                $Error_Array = @$data->Errors->Error;
                if($Error_Array!='')
                {
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error_Array,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $meg['result'] = '0';
                    $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);
                }
                else
                {
                    $response_hotel = $data->Property->attributes();
                    $response_room = $data->Property;
                    foreach($response_room as $room_value)
                    {
                        foreach($room_value->Room as $vv)
                        {
                            foreach($vv->attributes() as $key=>$value)
                            {

                                $viji_data['user_id'] = current_user_type();
                                $viji_data['hotel_id'] = hotel_id();
                                $viji_data['channel_id'] = insep_decode($channel_id);
                                $viji_data['hotel_channel_id'] = $response_hotel;
                                $viji_data[$key] =(string) $value;
                            }

                           // Get the contact id and Rateplan id

                            $start_date = date('Y-m-d');
                            $end_date = date('Y-m-d', strtotime("+30days"));
                            $roomId=$vv['Id'];
                            $soapUrl = trim($gta['irate']);
                            $soapUser = "HOTELAVAIL";
                            $soapPassword = "HOTELAVAIL";
                            $hotel_id=$gt_details->hotel_channel_id;
                             $xml_post_string = '<GTA_RoomRatesReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                                    <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                <Property Id="'.$hotel_id.'" Model="Static"/>
                                <Rooms>
                                <Room Id="'.$roomId.'"/>
                                </Rooms>
                                </GTA_RoomRatesReadRQ>';


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
                             $palndata = simplexml_load_string($output);
                             $Error_Array = @$palndata->Errors->Error;
                            if($Error_Array!='')
                            {
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                                $meg['result'] = '0';
                                $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                                echo json_encode($meg);
                                
                            }
                            $rate_row=$palndata->Room->Contract; 

                            //Fetching the contarct ID
                            if($rate_row)
                            {           
                                $contarct_id =$rate_row->attributes()->Id;


                                foreach($rate_row->RatePlans->RatePlan as $rateplan){                   

                                    $rate_plan_id=$rateplan->attributes()->Id;

                                    $mixpax=$rateplan->StaticRates->StaticRate->attributes()->MinPax;
                                        $MinNights=$rateplan->StaticRates->StaticRate->attributes()->MinNights;
                                    $stayfullperiod=$rateplan->StaticRates->StaticRate->attributes()->StayFullPeriod;
                                    $payfullperiod=$rateplan->StaticRates->StaticRate->attributes()->PayFullPeriod;
                                    $peakrate=$rateplan->StaticRates->StaticRate->attributes()->PeakRate;
                                    

                                    // Rateplan if update here;


                                    $gta_available = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id))->row_array();
                                                 $viji_data['rateplan_id'] = $rate_plan_id;
                                    $viji_data['contract_id'] = $contarct_id;
                                    $viji_data['MinPax'] = $mixpax;
                                    $viji_data['stayfullperiod'] = "$stayfullperiod";
                                    $viji_data['payfullperiod'] = "$payfullperiod";
                                    $viji_data['peakrate'] = "$peakrate";
                                    $viji_data['minnights'] = "$MinNights";
                                    $viji_data['contract_type'] = "Static";
                                    $array_keys = array_keys($viji_data);
                                    fetchColumn(IM_GTA,$array_keys);

                                    if(count($gta_available)!=0)
                                    {

                                        update_data(IM_GTA,$viji_data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id,'contract_type'=>'Static'));
                                        //echo $this->db->last_query();
                                        //exit;
                                    }
                                    else
                                    {

                                        $viji_data['rateplan_id'] = $rate_plan_id;
                                        $viji_data['contract_id'] = $contarct_id;
                                        $viji_data['MinPax'] = $mixpax;
                                        $viji_data['stayfullperiod'] = "$stayfullperiod";
                                        $viji_data['payfullperiod'] = "$payfullperiod";
                                        $viji_data['peakrate'] = "$peakrate";
                                        $viji_data['minnights'] = "$MinNights";

                                        insert_data(IM_GTA,$viji_data);
                                         $this->db->last_query();
                                    }                    
                                }
                            }
                            $start_date = date('Y-m-d');
                            $end_date = date('Y-m-d', strtotime("+30days"));
                            $roomId=$vv['Id'];
                            $soapUrl = trim($gta['irate']);
                            $soapUser = "HOTELAVAIL";
                            $soapPassword = "HOTELAVAIL";
                            $hotel_id=$gt_details->hotel_channel_id;
                             $xml_post_string = '<GTA_RoomRatesReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                                    <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                                <Property Id="'.$hotel_id.'" Model="Margin" Start="'.$start_date.'" End="'.$end_date.'"/>
                                <Rooms>
                                <Room Id="'.$roomId.'"/>
                                </Rooms>
                                </GTA_RoomRatesReadRQ>';


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
                             $palndata = simplexml_load_string($output);
                             $Error_Array = @$palndata->Errors->Error;
                            if($Error_Array!='')
                            {
                                $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                                $meg['result'] = '0';
                                $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                                echo json_encode($meg);
                            }
                           

                            $rate_row=$palndata->Room->Contract; 
                            if($rate_row){
                                //Fetching the contarct ID              
                                 $contarct_id =$rate_row->attributes()->Id;

                                foreach($rate_row->RatePlans->RatePlan as $rateplan){  

                                              

                                    $rate_plan_id=$rateplan->attributes()->Id;
                                   //echo  $mixpax=$rateplan->StaticRates->StaticRate->attributes()->MinPax;
                                    $MinNights=$rateplan->MarginRates->MarginRate->attributes()->MinNights;
                                    $stayfullperiod=$rateplan->MarginRates->MarginRate->attributes()->FullPeriod;
                                    $payfullperiod=$rateplan->MarginRates->MarginRate->attributes()->PayFullPeriod;
                                  //  $peakrate=$rateplan->StaticRates->StaticRate->attributes()->PeakRate;

                                    // Rateplan if update here;


                                    $gta_available = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id))->row_array();
                                                 $viji_data['rateplan_id'] = $rate_plan_id;
                                    $viji_data['contract_id'] = $contarct_id;
                                   // $viji_data['MinPax'] = $mixpax;
                                    $viji_data['stayfullperiod'] = "$stayfullperiod";
                                    //viji_data['payfullperiod'] = "$payfullperiod";
                                    //$viji_data['peakrate'] = "$peakrate";
                                    //$viji_data['minnights'] = "$MinNights";
                                    $viji_data['contract_type'] = "Margin";


                                    if(count($gta_available)!=0)
                                    {
                                        update_data(IM_GTA,$viji_data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id,'contract_type'=>'Margin'));
                                         $this->db->last_query();
                                       
                                    }
                                    else
                                    {

                                        $viji_data['rateplan_id'] = $rate_plan_id;
                                        $viji_data['contract_id'] = $contarct_id;
                                        //$viji_data['MinPax'] = $mixpax;
                                        $viji_data['stayfullperiod'] = $stayfullperiod;
                                     //   $viji_data['payfullperiod'] = $payfullperiod;
                                        //$viji_data['peakrate'] = "$peakrate";
                                        $viji_data['minnights'] = $MinNights;
                                        

                                        insert_data(IM_GTA,$viji_data);
                                         $this->db->last_query();
                                      //   echo $this->db->last_query(); 
                                
                                        
                                    }    
                                }
                            }
                        }
                    }

                    $gta_available = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'contract_type'=>'Static'));

                    $soapUrl = trim($gta['rateplansearch']);
                    foreach($gta_available->result() as $row){
                          
                        $gta_id= $row->GTA_id;
                        $ratepaln_id=$row->rateplan_id;
                        $contract_id=$row->contract_id;

                        $xml_post_string = '<GTA_RatePlanReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                          <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                            <Contract Id="'.$contract_id.'">
                            <RatePlan Id="'.$ratepaln_id.'"/>
                            </Contract>
                            </GTA_RatePlanReadRQ>';  


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
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                            $meg['result'] = '0';
                            $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                            echo json_encode($meg);
                        }
                        if(isset($data->Success)){
                           
                             $rateplan_code=$data->Contract->RatePlans->StaticRatePlan->attributes()->Code;
                           
                            $up_data=array();
                            $up_data['rateplan_code']="$rateplan_code";

                            update_data(IM_GTA,$up_data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rateplan_id'=>$ratepaln_id));
                        }
                    }

                    $gta_available = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'contract_type'=>'Margin'));

                    $soapUrl = trim($gta['rateplansearch']);
                    foreach($gta_available->result() as $row){
                          
                        $gta_id= $row->GTA_id;
                        $ratepaln_id=$row->rateplan_id;
                        $contract_id=$row->contract_id;

                        $xml_post_string = '<GTA_RatePlanReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                          <User Qualifier="'.$gt_details->web_id.'" UserName="'.$gt_details->user_name.'" Password="'.$gt_details->user_password.'" />
                            <Contract Id="'.$contract_id.'">
                            <RatePlan Id="'.$ratepaln_id.'"/>
                            </Contract>
                            </GTA_RatePlanReadRQ>';  


                        $ch = curl_init($soapUrl);
                        //curl_setopt($ch, CURLOPT_MUTE, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $output = curl_exec($ch);
                        $data = simplexml_load_string($output); 

                   
                        $Error_Array = @$data->Errors->Error;
                        if($Error_Array!='')
                        {
                            $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                            $meg['result'] = '0';
                            $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                            echo json_encode($meg);
                            curl_close($ch);
                        }
                        curl_close($ch);
                        if(isset($data->Success)){
                           
                            $rateplan_code=$data->Contract->RatePlans->MarginRatePlan->attributes()->Code;
                            $up_data=array();
                            $up_data['rateplan_code']="$rateplan_code";

                            update_data(IM_GTA,$up_data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rateplan_id'=>$ratepaln_id));
                        }
                    }    
                    $meg['result'] = '1';
                    $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
                    echo json_encode($meg);
                }
            }
			else if(insep_decode($channel_id) == '5')
			{
                $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
                
                if($re_details->mode == 0){
                    $urls = explode(',', $re_details->test_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $htb[$path[0]] = $path[1];
                    }
                }else if($re_details->mode == 1){
                    $urls = explode(',', $re_details->live_url);
                    foreach($urls as $url){
                        $path = explode("~",$url);
                        $htb[$path[0]] = $path[1];
                    }
                }
                $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
                    <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                     <getHSIContractList xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                     <HSI_ContractListRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="'.date('U').'">
                        <Language>ENG</Language>
                        <Credentials>
                            <User>'.$re_details->user_name.'</User>
                            <Password>'.$re_details->user_password.'</Password>
                        </Credentials>
                       </HSI_ContractListRQ>
                     </getHSIContractList>
                     </soapenv:Body>
                     </soapenv:Envelope>';

                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_post_string),
                ); 
                //$url = "http://testapi.interface-xml.com/cratos/ws/HSI";
                $url = trim($htb['irate_avail']);
                
                // PHP cURL  for https connection with auth
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $re_details->user_name.":".$re_details->user_password); // username and password - declared at the top of the doc
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss                 = curl_getinfo($ch);                
                $response           = curl_exec($ch);
                $xmlreplace         = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse          = simplexml_load_string($xmlreplace);
                $json               = json_encode($xml_parse);
                $responseArray      = json_decode($json,true);
                $ErrorList          = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractList']);
                
                $Error              = @$ErrorList->ErrorList->Error;
                if($Error)
                {
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$Error->DetailedMessage,'Get Channel',date('m/d/Y h:i:s a', time()));
                    $DetailedMessage    = $Error->DetailedMessage; 
                    $meg['result'] = '0';
                    $meg['content']=$DetailedMessage.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);
                }else{
                    $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractList']);
                    $hotelDetails = $xml->UserContract;
                    $auditDetails = $xml->AuditData;
                    $data['token'] = $xml->attributes()->echoToken;
                    $data['user_id'] = current_user_type();
                    $data['hotel_id'] = hotel_id();
                    $data['channel_id'] = insep_decode($channel_id);

                    foreach ($auditDetails as $audit) {
                        $data['processtime'] = (string)$audit->ProcessTime;
                        $data['Timestamp'] = (string)$audit->Timestamp;
                        $data['RequestHost'] = (string)$audit->RequestHost;
                        $data['ServerName'] = (string)$audit->ServerName;
                        $data['ServerId'] = (string)$audit->ServerId;
                        $data['SchemaRelease'] = (string)$audit->SchemaRelease;
                        $data['HydraCoreRelease'] = (string)$audit->HydraCoreRelease;
                        $data['HydraEnumerationsRelease'] = (string)$audit->HydraEnumerationsRelease;
                        $data['MerlinRelease'] = (string)$audit->MerlinRelease;
                    }

                    foreach ($hotelDetails as $val) {
                        $contract = $val->Contract;
                        $data['contract_name'] = (string)$contract->Name;
                        $data['contract_code'] = $contract->IncomingOffice->attributes()->code;
                        $data['sequence'] = $contract->Sequence;
                        $data['commentlist'] = (string)$contract->CommentList->Comment;

                        $hotel = $val->Hotel;
                        $data['hotel_code'] = $hotel->Code;
                        $data['hotel_name'] = (string)$hotel->Name;
                        
                        $supplier = $val->Supplier;
                        $data['supplier_code'] = $supplier->Code;
                        $data['supplier_name'] = (string)$supplier->Name;

                        $isexist = get_data("import_mapping_HOTELBEDS",array('user_id' => current_user_type(),'hotel_id' => hotel_id(),'channel_id' => insep_decode($channel_id),'sequence' => $data['sequence']))->num_rows;
                        if($isexist == 0){
                            insert_data('import_mapping_HOTELBEDS',$data);
                        }else{
                            update_data('import_mapping_HOTELBEDS',$data,array('user_id' => current_user_type(),'hotel_id' => hotel_id(),'channel_id' => insep_decode($channel_id),'sequence' => $data['sequence']));
                        }

                        $xml_contract_detail = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                             <getHSIContractDetail xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                             <HSI_ContractDetailRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                                <Language>ENG</Language>
                                <Credentials>
                                <User>'.$re_details->user_name.'</User>
                                <Password>'.$re_details->user_password.'</Password>
                                </Credentials>
                                <Contract>
                                <Name>'.$data['contract_name'].'</Name>
                                <IncomingOffice code="'.$data['contract_code'].'"/>
                                <Sequence>'.$data['sequence'].'</Sequence>
                                </Contract>
                                </HSI_ContractDetailRQ></getHSIContractDetail>
                             </soapenv:Body>
                             </soapenv:Envelope>';
                        $headers = array(
                        "SOAPAction:no-action",
                        "Content-length: ".strlen($xml_contract_detail),
                        ); 

                        // PHP cURL  for https connection with auth
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_USERPWD, $re_details->user_name.":".$re_details->user_password); // username and password - declared at the top of the doc
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 12);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_contract_detail); // the SOAP request
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $ss = curl_getinfo($ch);                
                        $response = curl_exec($ch);
                        $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                        $xml_parse = simplexml_load_string($xmlreplace);
                        $json = json_encode($xml_parse);
                        $responseArray = json_decode($json,true);

                        $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractDetail']);
                        foreach($xml->RoomList->HotelRoom as $rooms){
                            //print_r($list->ServiceDays->RoomList->HotelRoom);                        
                            $list['user_id'] = current_user_type();
                            $list['hotel_id'] = hotel_id();
                            $list['channel_id'] = insep_decode($channel_id);
                            $list['contract_name'] = (string)$data['contract_name'];
                            $list['contract_code'] = $data['contract_code'];
                            $list['sequence'] = $data['sequence'];
                            $list['currency'] = (string)$xml->Currency;
                            $list['roomname'] = (string)$rooms->RoomType->attributes()->code;
                            $list['roomtype'] = (string)$rooms->RoomType;
                            $list['type'] = (string)$rooms->RoomType->attributes()->type;
                            $list['characterstics'] = (string)$rooms->RoomType->attributes()->characteristic;

                            $is_exists = get_data("import_mapping_HOTELBEDS_ROOMS",array('user_id' => current_user_type(),'hotel_id' => hotel_id(),'channel_id' => insep_decode($channel_id),'sequence' => $list['sequence'],'roomname'=>$list['roomname'],'characterstics' => (string)$list['characterstics']))->num_rows;
                            if($is_exists != 0){
                                update_data("import_mapping_HOTELBEDS_ROOMS",$list,array('user_id' => current_user_type(),'hotel_id' => hotel_id(),'channel_id' => insep_decode($channel_id),'sequence' => $list['sequence'],'roomname'=>$list['roomname'],'characterstics' => (string)$list['characterstics']));
                            }else{
                                $this->db->insert("import_mapping_HOTELBEDS_ROOMS",$list);
                                //insert_data("import_mapping_HOTELBEDS_ROOMS",$list);
                            }
                            
                       
                        }
                        
                    }
                    $meg['result'] = '1';
                    $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
                    echo json_encode($meg);             
                }               
                curl_close($ch);
            }
            else if(insep_decode($channel_id) == '17')
			{
				require_once(APPPATH.'controllers/bnow.php'); 
				$callAvailabilities = new Bnow();
				$result = $callAvailabilities->importRooms($channel_id);
				if($result=='Enable')
				{
					$meg['result']	= 	'0';
					$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
					echo json_encode($meg);
				}
				elseif($result=='Insert')
				{
					$meg['result']	= 	'1';
					$meg['content']	=	'Succesfully import room rate information from '.$cha_name.'!!!';
					echo json_encode($meg);
				}
				else
				{
					$meg['result'] 	=	'0';
					$meg['content']	=	$result.' from '.$cha_name.'. Try again!';
					echo json_encode($meg);
				}
			}
    	    else if(insep_decode($channel_id) == '9')
    		{
    			require_once(APPPATH.'controllers/airbnb.php');

    			$callAvailabilities = new Airbnb();
    			$result = $callAvailabilities->importRooms($channel_id);
    			if($result=='Enable')
    			{
    				$meg['result']	= 	'0';
    				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
    				echo json_encode($meg);
    			}elseif($result=='Insert'){
    				$meg['result']	= 	'1';
    				$meg['content']	=	'Succesfully import room rate information from '.$cha_name.'!!!';
    				echo json_encode($meg);
    			}else{
    				$meg['result'] 	=	'0';
    				$meg['content']	=	$result.' from '.$cha_name.'. Try again!';
    				echo json_encode($meg);
    			}
    		}
            else if(insep_decode($channel_id) == '15')
            {
                require_once(APPPATH.'controllers/travelrepublic.php'); 
                $callAvailabilities = new Travelrepublic();
                $result = $callAvailabilities->importRooms($channel_id);
                if($result=='Enable')
                {
                    $meg['result']  =   '0';
                    $meg['content'] =   "Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
                    echo json_encode($meg);
                }
                elseif($result=='Insert')
                {
                    $meg['result']  =   '1';
                    $meg['content'] =   'Succesfully import room rate information from '.$cha_name.'!!!';
                    echo json_encode($meg);
                }
                else
                {
                    $meg['result']  =   '0';
                    $meg['content'] =   $result.' from '.$cha_name.'. Try again!';
                    echo json_encode($meg);
                }
            }
			else if(insep_decode($channel_id) == '14')
			{
				require_once(APPPATH.'controllers/wbeds.php'); 
				$callAvailabilities = new wbeds();
				$result = $callAvailabilities->importRooms($channel_id);
				if($result=='Enable')
				{
					$meg['result']	= 	'0';
					$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
					echo json_encode($meg);
				}
				elseif($result=='Insert')
				{
					$meg['result']	= 	'1';
					$meg['content']	=	'Succesfully import room rate information from '.$cha_name.'!!!';
					echo json_encode($meg);
				}
				else
				{
					$meg['result'] 	=	'0';
					$meg['content']	=	$result.' from '.$cha_name.'. Try again!';
					echo json_encode($meg);
				}
			}
			else
            {
                $meg['result'] = '0';
                $meg['content']='Error during import room rate information from '.$cha_name.'. Try again!';
                echo json_encode($meg);
            }
       
	}
    
    
    function importRates($channel_id,$propertyid,$rate_id,$guest_count,$refun_type)
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $property_id = insep_decode($propertyid);
        $importDetails = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        $start_date = date('d.m.Y');

        $end_date = date('d.m.Y', strtotime("+30 days"));

        $exp_start_date = date('Y-m-d');
        $exp_end_date = date('Y-m-d', strtotime("+30 days"));

        $hotelbed_start_date = str_replace('-', '', $exp_start_date);
        $hotelbed_end_date = str_replace('-', '', $exp_end_date);


        $start = date('d/m/Y');
        $end = date('d/m/Y', strtotime("+30 days"));
        $channel['guest'] = $guest_count;
        $channel['refund'] = $refun_type;
        $channel['rate'] = $rate_id;
        if(count($importDetails)!=0)
        {
            if($importDetails['channel_id'] == 11)
			{

                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();

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

                $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'re_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();

                $url = trim($reco['irate_avail']);
                $xml_rate = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                                <GetRates xmlns="https://www.reconline.com/">
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
                                  <StartDate>'.$start_date.'</StartDate>
                                  <EndDate>'.$end_date.'</EndDate>
                                  <ChangesSince></ChangesSince>
                                </GetRates>
                              </soap12:Body>
                            </soap12:Envelope>';
                $headers = array(
                    "Content-type: application/soap+xml; charset=utf-8",
                    "Host:www.reconline.com",
                    "Content-length: ".strlen($xml_rate),
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_rate);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $ss = curl_getinfo($ch);    
                $response = curl_exec($ch);
                $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $getRates = $responseArray['soapBody']['GetRatesResponse']['GetRatesResult']['diffgrdiffgram']['NewDataSet']['Rate'];
                $Errorarray = @$responseArray['soapBody']['GetRatesResponse']['GetRatesResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                if(count($Errorarray)=='0' && count($soapFault)=='0')
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Rate From Reconline!!!');
                    $reconline_availability_response="success";
                }
                else 
                {
                    $reconline_availability_response="error";
                    if(count($Errorarray)!='0')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$Errorarray['WARNING'],'importRates',date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('import_rate_error',$Errorarray['WARNING']);
                    }
                    else if(count($soapFault)!='0')
                    {  
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$soapFault['soapText'],'importRates',date('m/d/Y h:i:s a', time()));    
                        $this->session->set_flashdata('import_rate_error',$soapFault['soapText']);
                    }
                    return false;
                } 
                
                curl_close($ch);

                if($reconline_availability_response == "success"){

                    foreach ($getRates as $det) {
                        if(isset($det['BEGINPERIOD'])){
                            $begin_date = date("d/m/Y",strtotime($det['BEGINPERIOD']));
                            $en_date = date("d/m/Y",strtotime($det['ENDPERIOD']));
                            $rate = $det['SINGLEOCCUPANCY'];
                        }else{
                            $begin_date = date("d/m/Y",strtotime($getRates['BEGINPERIOD']));
                            $en_date = date("d/m/Y",strtotime($getRates['ENDPERIOD']));
                            $rate = $getRates['SINGLEOCCUPANCY'];
                        }
                        $startDate = DateTime::createFromFormat("d/m/Y",$begin_date);
						$en_date = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d',strtotime(str_replace("/","-",$en_date))))));
                        $endDate = DateTime::createFromFormat("d/m/Y",$en_date);
                        $periodInterval = new DateInterval( "P1D" ); // 1-day, though can be more sophisticated rule
                        //$endDate->add( $periodInterval );
                        $period = new DatePeriod( $startDate, $periodInterval, $endDate );

                        foreach($period as $date){
                            // *** Main TBL UPDATE *** //
                            if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date->format("d/m/Y")));
                                
                                if($update_details){
                                    $ch_update_data['price']=$rate;

                                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date->format("d/m/Y")));
                                                                
                                    $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y")))->row_array();
                                   
                                    if(count($available_update_details)!=0)
                                    {
                                        update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y")));
                                    }
                                    else 
                                    {
                                       $ch_update_data['owner_id']= current_user_type();
                                       $ch_update_data['hotel_id']= hotel_id();
                                       $ch_update_data['room_id']= $property_id;
                                       $ch_update_data['individual_channel_id']= $importDetails['channel_id'];
                                       $ch_update_data['start_date']= $begin_date;
                                       $ch_update_data['end_date']= $en_date;
                                       $ch_update_data['separate_date']=$date->format("d/m/Y");
                                       insert_data(TBL_UPDATE,$ch_update_data);
                                    }

                                    $updated_data = get_data(TBL_UPDATE,array('individual_channel_id'=>$importDetails['channel_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$date->format("d/m/Y")))->result_array();
                                }else{
                                    $ch_update_data['price']=$rate;
                                    $ch_update_data['owner_id']= current_user_type();
                                    $ch_update_data['hotel_id']= hotel_id();
                                    $ch_update_data['room_id']= $property_id;
                                    $ch_update_data['individual_channel_id']= 0;
                                    $ch_update_data['start_date']= $begin_date;
                                    $ch_update_data['end_date']= $en_date;
                                    $ch_update_data['separate_date']=$date->format("d/m/Y");
                                    insert_data(TBL_UPDATE,$ch_update_data);
                                }
                                $channel['guest'] = $guest_count;
                                $channel['refund'] = $refun_type;
                                $channel['rate'] = $rate_id;
                                if(isset($updated_data)){
                                    $update_cannels = $this->inventory_model->channel_calendar_update($updated_data,$channel);
                                }
                               
                            }

                            // *** End Of TBL UPDATE *** //
                            // *** Reservation TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date->format("d/m/Y"),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                

                                if($update_details_RESERV)
                                {
                                    if($update_details_RESERV->refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RESERV->refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }

                                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date->format("d/m/Y"),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                
                                    $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y"),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                    if(count($available_update_details_RESERV)!=0)
                                    {
                                      update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y"),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }
                                    else 
                                    {
                                        $ch_update_data_RESERV['owner_id']= current_user_type();
                                        $ch_update_data_RESERV['hotel_id']= hotel_id();
                                        $ch_update_data_RESERV['room_id']= $property_id;
                                        $ch_update_data_RESERV['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RESERV['separate_date']=$date->format("d/m/Y");
                                        $ch_update_data_RESERV['guest_count']=$update_details_RESERV->guest_count;
                                        $ch_update_data_RESERV['refun_type']=$update_details_RESERV->refun_type;
                                        insert_data(RESERV,$ch_update_data_RESERV);
                                    }
                                    $updated_data = get_data(RESERV,array('individual_channel_id'=>$importDetails['channel_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$date->format("d/m/Y")))->result_array();
                                }else{
                                    if($refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }
                                    $ch_update_data_RESERV['owner_id']= current_user_type();
                                    $ch_update_data_RESERV['hotel_id']= hotel_id();
                                    $ch_update_data_RESERV['room_id']= $property_id;
                                    $ch_update_data_RESERV['individual_channel_id']= 0;
                                    $ch_update_data_RESERV['separate_date']=$date->format("d/m/Y");
                                    $ch_update_data_RESERV['guest_count']=$guest_count;
                                    $ch_update_data_RESERV['refun_type']=$refun_type;
                                    insert_data(RESERV,$ch_update_data_RESERV);
                                }
                                $channel['guest'] = $guest_count;
                                $channel['refund'] = $refun_type;
                                $channel['rate'] = $rate_id;
                                if(isset($updated_data)){
                                    $update_cannels = $this->inventory_model->channel_calendar_update($updated_data,$channel);
                                }
                            }
                            // *** End Of Reservation TBL UPDATE *** //
                            // *** Base Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format("d/m/Y")))->row();
                                if($update_details_RATE_BASE){
                                    $ch_update_data_RATE_BASE['price']=$rate;

                                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=> '0','separate_date'=>$date->format("d/m/Y")));

                                    $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y")))->row_array();
                                    if(count($available_update_details_RATE_BASE)!=0){

                                        update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format("d/m/Y")));
                                    }else{
                                        $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_BASE['room_id']= $property_id;
                                        $ch_update_data_RATE_BASE['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                        $ch_update_data_RATE_BASE['end_date']= $en_date;
                                        $ch_update_data_RATE_BASE['separate_date']=$date->format("d/m/Y");
                                        insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                    }
                                    $updated_data = get_data(RATE_BASE,array('individual_channel_id'=>$importDetails['channel_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$date->format("d/m/Y")))->result_array();
                                }else{
                                    $ch_update_data_RATE_BASE['price']=$rate;
                                    $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_BASE['room_id']= $property_id;
                                    $ch_update_data_RATE_BASE['individual_channel_id']=0;
                                    $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                    $ch_update_data_RATE_BASE['end_date']= $en_date;
                                    $ch_update_data_RATE_BASE['separate_date']=$date->format("d/m/Y");
                                    insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                }
                                $channel['guest'] = $guest_count;
                                $channel['refund'] = $refun_type;
                                $channel['rate'] = $rate_id;
                                if(isset($updated_data)){
                                    $update_cannels = $this->inventory_model->channel_calendar_update($updated_data,$channel);
                                }

                            }
                            // *** End Of Base Rate TBL UPDATE *** //
                            // *** Additional Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                if($update_details_RATE_ADD){
                                    if($update_details_RATE_ADD->refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RATE_ADD->refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                    }

                                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));

                                    $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                    if(count($available_update_details_RATE_ADD)!=0){

                                        update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }else{
                                        $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                        $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_ADD['room_id']= $property_id;
                                        $ch_update_data_RATE_ADD['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RATE_ADD['separate_date']=$date->format("d/m/Y");
                                        $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                        $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                        insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                                    }
                                    $updated_data = get_data(RATE_ADD,array('individual_channel_id'=>$importDetails['channel_id'],'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$date->format("d/m/Y")))->result_array();
                                }else{
                                    if($refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                    }
                                    $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                    $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_ADD['room_id']= $property_id;
                                    $ch_update_data_RATE_ADD['individual_channel_id']=0;
                                    $ch_update_data_RATE_ADD['separate_date']=$date->format("d/m/Y");
                                    $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                    $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                    insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                                }
                                $channel['guest'] = $guest_count;
                                $channel['refund'] = $refun_type;
                                $channel['rate'] = $rate_id;
                                if(isset($updated_data)){
                                    $update_cannels = $this->inventory_model->channel_calendar_update($updated_data,$channel);
                                }
                            }
                            // *** End OF Additional Rate TBL UPDATE *** //
                        }                   
                    }               
                    // *** Mapping Values Update *** //             
                    $map_details = get_data('mapping_values',array('user_id' => current_user_type(),'hotel_id' => hotel_id(), 'mapping_id' => $importDetails['import_mapping_id']))->row();
                    
                    /*foreach($getRates as $key => $value){
                        foreach ($value as $key => $val) {
                            $lable .= $key.",";
                            if($value[$key] != ""){
                                $la_value .= $value[$key].',';
                            }else{
                                $la_value .= ",";
                            }
                            $title .= "title_".$key.",";
                        }
                    }*/

                    /*foreach($getRates as $value){
                        $lable = "";
                        $lable .= "DoubleOcc,TripleOcc,DoublePlusChild,RollawayAdult,RollawayChild,MaxStay,Crib,Advance";
                        
                        $la_value = "";
                        $title ="";
                        if(isset($value['DOUBLEOCCUPANCY'])){
                            $la_value .= $value['DOUBLEOCCUPANCY'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['TRIPLEOCCUPANCY'])){
                            $la_value .= $value['TRIPLEOCCUPANCY'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['DOUBLEPLUSCHILD'])){
                            $la_value .= $value['DOUBLEPLUSCHILD'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['ROLLAWAYADULT'])){
                            $la_value .= $value['ROLLAWAYADULT'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['ROLLAWAYCHILD'])){
                            $la_value .= $value['ROLLAWAYCHILD'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['MAXDAY'])){
                            $la_value .= $value['MAXDAY'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['CRIB'])){
                            $la_value .= $value['CRIB'].',';
                        }else{
                            $la_value .= ',';
                        }
                        if(isset($value['ADVANCE'])){
                            $la_value .= $value['ADVANCE'];
                        }else{
                            $la_value .= ',';
                        }                       
                    }*/
                    /*if(count($map_details) == 0){
                        $data_dynamic=array(
                            'label'=>rtrim($lable,","),
                            'value'=>substr($la_value,0,-1),
                            'title'=>rtrim($title,","),
                            'hotel_id'=>hotel_id(),
                            'user_id'=>user_id(),
                            'mapping_id'=>$importDetails['import_mapping_id']
                        );
                        
                        $this->db->insert('mapping_values',$data_dynamic);
                    }else{
                        $data_dynamic=array(
                            'label'=>rtrim($lable,","),
                            'value'=>substr($la_value,0,-1),
                            'title'=>rtrim($title,","),
                            'hotel_id'=>hotel_id(),
                            'user_id'=>user_id(),
                            'mapping_id'=>$importDetails['import_mapping_id']
                        );
                        $this->db->where('mapping_id',$importDetails['import_mapping_id']);
                        $this->db->update('mapping_values',$data_dynamic);
                    }*/             
                    // *** End OF Mapping Values Update *** //  
                }           
            }
			else if($importDetails['channel_id'] == 1)
			{

                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();   
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
                $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();

                $xml_data = '<ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">
                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                    <Hotel id="'.$ch_details->hotel_channel_id.'"/>
                    <ParamSet>
                 <AvailRateRetrieval from="'.$exp_start_date.'" to="'.$exp_end_date.'">
                    <RoomType id="'.$mp_details->roomtype_id.'" >';
                $xml_data .= '<RatePlan id="'.$mp_details->rate_type_id.'"/>';
                 $xml_data .= '</RoomType>
                </AvailRateRetrieval>
                </ParamSet>
                </ProductAvailRateRetrievalRQ>';



                $URL = trim($exp['irate_avail']);
                $ch = curl_init($URL);
                //curl_setopt($ch, CURLOPT_MUTE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                //echo $output;
                $data = simplexml_load_string($output);
                $response = $data->Error;
                if($response!='')
                {
                   $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$response,'importRates',date('m/d/Y h:i:s a', time()));
                    $expedia_update = "Failed";
                }
                
                //echo "<pre>";
                //print_r($data);
                    
                curl_close($ch);

                $dataval=$data->AvailRateList->AvailRate;
                foreach($dataval as $row)
                {
                    $rateplan=$row->RoomType;
                    

                    $rate_date=$rateplan->RatePlan;
                    $datedata=$rate_date->attributes();
                
                    $getdate=$row->attributes()->date;
                    
                    $date = date('d/m/Y',strtotime(str_replace('-','/',$getdate)));
                    foreach($rate_date as $rate_row)
                    {
                        $ratearray = $rate_row->Rate;
                        //print_r($ratearray);
                        foreach($ratearray as $pr){
                            //print_r($pr);
                            
                            foreach($pr as $r_val)
                                $rate= $r_val->attributes()->rate;
                            // *** Main TBL UPDATE *** //
                            if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));

                                if($update_details){
                                    $ch_update_data['price']=$rate;

                                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));  
                                                                
                                    $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                                    if(count($available_update_details)!=0)
                                    {
                                        update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                    }
                                    else 
                                    {
                                       $ch_update_data['owner_id']= current_user_type();
                                       $ch_update_data['hotel_id']= hotel_id();
                                       $ch_update_data['room_id']= $property_id;
                                       $ch_update_data['individual_channel_id']= $importDetails['channel_id'];
                                       $ch_update_data['start_date']= $start;
                                       $ch_update_data['end_date']= $end;
                                       $ch_update_data['separate_date']=$date;
                                     
                                       insert_data(TBL_UPDATE,$ch_update_data);
                                        $this->db->last_query();
                                    
                                    }
                                }else{
                                    $ch_update_data['price']=$rate;
                                    $ch_update_data['owner_id']= current_user_type();
                                    $ch_update_data['hotel_id']= hotel_id();
                                    $ch_update_data['room_id']= $property_id;
                                    $ch_update_data['individual_channel_id']= 0;
                                    $ch_update_data['start_date']= $start;
                                    $ch_update_data['end_date']= $end;
                                    $ch_update_data['separate_date']=$date;
                                    insert_data(TBL_UPDATE,$ch_update_data);
                                }
                               
                            }
                            // *** End Of TBL UPDATE *** //
                            // *** Reservation TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                if($update_details_RESERV)
                                {
                                    if($update_details_RESERV->refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RESERV->refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }

                                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                
                                    $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                    if(count($available_update_details_RESERV)!=0)
                                    {
                                      update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }
                                    else 
                                    {
                                        $ch_update_data_RESERV['owner_id']= current_user_type();
                                        $ch_update_data_RESERV['hotel_id']= hotel_id();
                                        $ch_update_data_RESERV['room_id']= $property_id;
                                        $ch_update_data_RESERV['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RESERV['separate_date']=$date;
                                        $ch_update_data_RESERV['guest_count']=$update_details_RESERV->guest_count;
                                        $ch_update_data_RESERV['refun_type']=$update_details_RESERV->refun_type;
                                        
                                        insert_data(RESERV,$ch_update_data_RESERV);
                                    }
                                    
                                }else{
                                    if($refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }
                                    $ch_update_data_RESERV['owner_id']= current_user_type();
                                    $ch_update_data_RESERV['hotel_id']= hotel_id();
                                    $ch_update_data_RESERV['room_id']= $property_id;
                                    $ch_update_data_RESERV['individual_channel_id']= 0;
                                    $ch_update_data_RESERV['separate_date']=$date;
                                    $ch_update_data_RESERV['guest_count']=$guest_count;
                                    $ch_update_data_RESERV['refun_type']=$refun_type;
                                    insert_data(RESERV,$ch_update_data_RESERV);
                                    //    echo $this->db->last_query();
                                }
                            }
                            // *** End Of Reservation TBL UPDATE *** //
                            // *** Base Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();
                                if($update_details_RATE_BASE){
                                    $ch_update_data_RATE_BASE['price']=$rate;

                                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=> '0','separate_date'=>$date));

                                    $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                                    if(count($available_update_details_RATE_BASE)!=0){

                                        update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                    }else{
                                        $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_BASE['room_id']= $property_id;
                                        $ch_update_data_RATE_BASE['individual_channel_id']= $importDetails['channel_id'];;
                                        $ch_update_data_RATE_BASE['start_date']= $start;
                                        $ch_update_data_RATE_BASE['end_date']= $end;
                                        $ch_update_data_RATE_BASE['separate_date']=$date;

                                        insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                    }
                                }else{
                                    $ch_update_data_RATE_BASE['price']=$rate;
                                    $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_BASE['room_id']= $property_id;
                                    $ch_update_data_RATE_BASE['individual_channel_id']= 0;
                                    $ch_update_data_RATE_BASE['start_date']= $start;
                                    $ch_update_data_RATE_BASE['end_date']= $end;
                                    $ch_update_data_RATE_BASE['separate_date']=$date;
                                    insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                }

                            }
                            // *** End Of Base Rate TBL UPDATE *** //
                            // *** Additional Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                if($update_details_RATE_ADD){
                                    if($update_details_RATE_ADD->refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RATE_ADD->refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate;; 
                                    }

                                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));

                                    $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                    if(count($available_update_details_RATE_ADD)!=0){

                                        update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }else{
                                        $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                        $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_ADD['room_id']= $property_id;
                                        $ch_update_data_RATE_ADD['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RATE_ADD['separate_date']=$date;
                                        $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                        $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                        insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                                    }
                                }else{
                                    if($refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate;; 
                                    }
                                    $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                    $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_ADD['room_id']= $property_id;
                                    $ch_update_data_RATE_ADD['individual_channel_id']= 0;
                                    $ch_update_data_RATE_ADD['separate_date']=$date;
                                    $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                    $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                    insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                                }
                            }
                            // *** End OF Additional Rate TBL UPDATE *** //
                            break;

                        }
                        
                    }

                                    
                }   
            }
			else if($importDetails['channel_id'] == 5)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();  

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

                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id']))->row();

                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventory xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryRQ>
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
                            <DateFrom date="'.$hotelbed_start_date.'"/>
                            <DateTo date="'.$hotelbed_end_date.'"/>
                            </HSI_ContractInventoryRQ>
                            </getHSIContractInventory>
                            </soapenv:Body>
                            </soapenv:Envelope>';

                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_data),
                ); 
                $url = trim($htb['irate_avail']);

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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss = curl_getinfo($ch);                
                $response = curl_exec($ch);
                
                //echo '<pre>';
                //echo $response;
                
                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse = simplexml_load_string($xmlreplace);
                $json = json_encode($xml_parse);
                $responseArray = json_decode($json,true);
                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventory']);
                if($xml->ErrorList->Error)
                {
                    $error = (string)$xml->ErrorList->Error->DetailedMessage;
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$error,'importRates',date('m/d/Y h:i:s a', time()));
                                       //die();
                    $this->session->set_flashdata('import_rate_error', $error);
                    //return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Rate From Hotelbeds!!!');
                    $hotelbed_availability_response="success";
                    foreach ($xml->InventoryItem as $dataval) 
					{
                        $date = date('d/m/Y',strtotime($dataval->DateFrom->attributes()->date));
                        foreach ($dataval->Room as $details) 
						{
                            //print_r($detail_s->RoomType);
                            foreach($details->RoomType as $room_name)
							{
								if($room_name->attributes()->code == $mp_details->roomname && $room_name->attributes()->characteristic == $mp_details->characterstics)
								{
									$rate = $details->Price->Amount;
									// *** Main TBL UPDATE *** //
									if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0')
									{

										$update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));

										if($update_details){
											$ch_update_data['price']=$rate;

											update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));  
																		
											$available_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
											if(count($available_update_details)!=0)
											{
												update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
											}
											else 
											{
											   $ch_update_data['owner_id']= current_user_type();
											   $ch_update_data['hotel_id']= hotel_id();
											   $ch_update_data['room_id']= $property_id;
											   $ch_update_data['individual_channel_id']= $importDetails['channel_id'];
											   $ch_update_data['start_date']= $start;
											   $ch_update_data['end_date']= $end;
											   $ch_update_data['separate_date']=$date;
											 
											   insert_data(TBL_UPDATE,$ch_update_data);                             
											}
										}else{
											$ch_update_data['price']= $rate;
											$ch_update_data['owner_id']= current_user_type();
											$ch_update_data['hotel_id']= hotel_id();
											$ch_update_data['room_id']= $property_id;
											$ch_update_data['individual_channel_id']= 0;
											$ch_update_data['start_date']= $start;
											$ch_update_data['end_date']= $end;
											$ch_update_data['separate_date']=$date;
											insert_data(TBL_UPDATE,$ch_update_data);
										}
									   
									}
									// *** End Of TBL UPDATE *** //
									// *** Reservation TBL UPDATE *** //
									elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0')
									{

										$update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

										if($update_details_RESERV)
										{
											if($update_details_RESERV->refun_type=='1')
											{
												$ch_update_data_RESERV['refund_amount']=$rate;
											}
											elseif($update_details_RESERV->refun_type=='2')
											{
												$ch_update_data_RESERV['non_refund_amount']=$rate;
											}

											update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
										
											$available_update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

											if(count($available_update_details_RESERV)!=0)
											{
											  update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
											}
											else 
											{
												$ch_update_data_RESERV['owner_id']= current_user_type();
												$ch_update_data_RESERV['hotel_id']= hotel_id();
												$ch_update_data_RESERV['room_id']= $property_id;
												$ch_update_data_RESERV['individual_channel_id']= $importDetails['channel_id'];
												$ch_update_data_RESERV['separate_date']=$date;
												$ch_update_data_RESERV['guest_count']=$update_details_RESERV->guest_count;
												$ch_update_data_RESERV['refun_type']=$update_details_RESERV->refun_type;
												
												insert_data(RESERV,$ch_update_data_RESERV);
											}
											
										}else{
											if($refun_type=='1')
											{
												$ch_update_data_RESERV['refund_amount']=$rate;
											}
											elseif($refun_type=='2')
											{
												$ch_update_data_RESERV['non_refund_amount']=$rate;
											}
											$ch_update_data_RESERV['owner_id']= current_user_type();
											$ch_update_data_RESERV['hotel_id']= hotel_id();
											$ch_update_data_RESERV['room_id']= $property_id;
											$ch_update_data_RESERV['individual_channel_id']= 0;
											$ch_update_data_RESERV['separate_date']=$date;
											$ch_update_data_RESERV['guest_count']=$guest_count;
											$ch_update_data_RESERV['refun_type']=$refun_type;
											insert_data(RESERV,$ch_update_data_RESERV);
												//echo $this->db->last_query();
										}
									}
									// *** End Of Reservation TBL UPDATE *** //
									// *** Base Rate TBL UPDATE *** //
									elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0')
									{

										$update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();
										if($update_details_RATE_BASE){
											$ch_update_data_RATE_BASE['price']=$rate;

											update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=> '0','separate_date'=>$date));

											$available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
											if(count($available_update_details_RATE_BASE)!=0){

												update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
											}else{
												$ch_update_data_RATE_BASE['hotel_id']= hotel_id();
												$ch_update_data_RATE_BASE['room_id']= $property_id;
												$ch_update_data_RATE_BASE['individual_channel_id']= $importDetails['channel_id'];;
												$ch_update_data_RATE_BASE['start_date']= $start;
												$ch_update_data_RATE_BASE['end_date']= $end;
												$ch_update_data_RATE_BASE['separate_date']=$date;

												insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
											}
										}else{
											$ch_update_data_RATE_BASE['price']=$rate;
											$ch_update_data_RATE_BASE['hotel_id']= hotel_id();
											$ch_update_data_RATE_BASE['room_id']= $property_id;
											$ch_update_data_RATE_BASE['individual_channel_id']= 0;
											$ch_update_data_RATE_BASE['start_date']= $start;
											$ch_update_data_RATE_BASE['end_date']= $end;
											$ch_update_data_RATE_BASE['separate_date']=$date;
											insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
										}

									}
									// *** End Of Base Rate TBL UPDATE *** //
									// *** Additional Rate TBL UPDATE *** //
									elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0')
									{

										$update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
										if($update_details_RATE_ADD){
											if($update_details_RATE_ADD->refun_type=='1'){
												$ch_update_data_RATE_ADD['refund_amount']=$rate;
											}
											elseif($update_details_RATE_ADD->refun_type=='2'){
												$ch_update_data_RATE_ADD['non_refund_amount']=$rate;; 
											}

											update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));

											$available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
											if(count($available_update_details_RATE_ADD)!=0){

												update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
											}else{
												$ch_update_data_RATE_ADD['owner_id']= current_user_type();
												$ch_update_data_RATE_ADD['hotel_id']= hotel_id();
												$ch_update_data_RATE_ADD['room_id']= $property_id;
												$ch_update_data_RATE_ADD['individual_channel_id']= $importDetails['channel_id'];
												$ch_update_data_RATE_ADD['separate_date']=$date;
												$ch_update_data_RATE_ADD['guest_count']=$guest_count;
												$ch_update_data_RATE_ADD['refun_type']=$refun_type;
												insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
											}
										}else{
											if($refun_type=='1'){
												$ch_update_data_RATE_ADD['refund_amount']=$rate;
											}
											elseif($refun_type=='2'){
												$ch_update_data_RATE_ADD['non_refund_amount']=$rate;; 
											}
											$ch_update_data_RATE_ADD['owner_id']= current_user_type();
											$ch_update_data_RATE_ADD['hotel_id']= hotel_id();
											$ch_update_data_RATE_ADD['room_id']= $property_id;
											$ch_update_data_RATE_ADD['individual_channel_id']= 0;
											$ch_update_data_RATE_ADD['separate_date']=$date;
											$ch_update_data_RATE_ADD['guest_count']=$guest_count;
											$ch_update_data_RATE_ADD['refun_type']=$refun_type;
											insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
										}
									}
								}
							}
                        }
                    }
                }
            }
            elseif($importDetails['channel_id'] == 8){

                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();

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
                $mp_details = get_data(IM_GTA,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'GTA_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                $roomid=$mp_details->Id;
                
                $hotel_id=$mp_details->hotel_channel_id;

                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime("+30days"));
                $soapUrl = trim($gta['irate']);
                $soapUser = "HOTELAVAIL";
                $soapPassword = "HOTELAVAIL";
                $xml_post_string = '<GTA_RoomRatesReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'"/>
                <Property Id="'.$hotel_id.'" Model="'.$mp_details->contract_type.'" Start="'.$start_date.'" End="'.$end_date.'"/>
                <Rooms>
                <Room Id="'.$roomid.'"/>
                </Rooms>
                </GTA_RoomRatesReadRQ>';  
                $ch = curl_init($soapUrl);
                //curl_setopt($ch, CURLOPT_MUTE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);

                $data = simplexml_load_string($output); 
               // print_r($data);

                $Error_Array = @$data->Errors->Error;
                if($Error_Array!='')
                {
                    $this->inventory_model->store_error($$importDetails['channel_id'],(string)$Error_Array,'importRates',date('m/d/Y h:i:s a', time()));
                }

                if($mp_details->contract_type == "Margin"){
                    $marginrates = $data->Room->Contract->RatePlans->RatePlan->MarginRates->MarginRate;
                    foreach ($marginrates as $marginrate) {
                        foreach($marginrate->MarginRoomRate as $rate_detail){
                            $en_date = $begin_date = $date = date("d/m/Y",strtotime($rate_detail->attributes()->Start));
                            $rate= $rate_detail->attributes()->Gross;

                             // *** Main TBL UPDATE *** //
                           if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));
                                

                                if($update_details->num_rows != 0){

                                
                                    $ch_update_data['price']=$rate;

                                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));
                                                                
                                    $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                                   
                                    if(count($available_update_details)!=0)
                                    {
                                        update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                    }
                                    else 
                                    {
                                       $ch_update_data['owner_id']= current_user_type();
                                       $ch_update_data['hotel_id']= hotel_id();
                                       $ch_update_data['room_id']= $property_id;
                                       $ch_update_data['individual_channel_id']= $importDetails['channel_id'];
                                       $ch_update_data['start_date']= $begin_date;
                                       $ch_update_data['end_date']= $en_date;
                                       $ch_update_data['separate_date']=$date;
                                
                                       insert_data(TBL_UPDATE,$ch_update_data);
                                       
                                    }

                                }else{
                                    
                                    $ch_update_data['price']=$rate;
                                    $ch_update_data['owner_id']= current_user_type();
                                    $ch_update_data['hotel_id']= hotel_id();
                                    $ch_update_data['room_id']= $property_id;
                                    $ch_update_data['individual_channel_id']= 0;
                                    $ch_update_data['start_date']= $begin_date;
                                    $ch_update_data['end_date']= $en_date;
                                    $ch_update_data['separate_date']=$date;
                                    
                                    insert_data(TBL_UPDATE,$ch_update_data);

                                }
                               
                            }

                            // *** End Of TBL UPDATE *** //
                            // *** Reservation TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                

                                if($update_details_RESERV)
                                {
                                    if($update_details_RESERV->refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RESERV->refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }

                                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                
                                    $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                    if(count($available_update_details_RESERV)!=0)
                                    {
                                      update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }
                                    else 
                                    {
                                        $ch_update_data_RESERV['owner_id']= current_user_type();
                                        $ch_update_data_RESERV['hotel_id']= hotel_id();
                                        $ch_update_data_RESERV['room_id']= $property_id;
                                        $ch_update_data_RESERV['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RESERV['separate_date']=$date;
                                        $ch_update_data_RESERV['guest_count']=$update_details_RESERV->guest_count;
                                        $ch_update_data_RESERV['refun_type']=$update_details_RESERV->refun_type;
                                        insert_data(RESERV,$ch_update_data_RESERV);

                                    }
                                    
                                }else{
                                    if($refun_type=='1')
                                    {
                                        $ch_update_data_RESERV['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2')
                                    {
                                        $ch_update_data_RESERV['non_refund_amount']=$rate;
                                    }
                                    $ch_update_data_RESERV['owner_id']= current_user_type();
                                    $ch_update_data_RESERV['hotel_id']= hotel_id();
                                    $ch_update_data_RESERV['room_id']= $property_id;
                                    $ch_update_data_RESERV['individual_channel_id']= 0;
                                    $ch_update_data_RESERV['separate_date']=$date;
                                    $ch_update_data_RESERV['guest_count']=$guest_count;
                                    $ch_update_data_RESERV['refun_type']=$refun_type;
                                    insert_data(RESERV,$ch_update_data_RESERV);
                            
                                }
                            }
                            // *** End Of Reservation TBL UPDATE *** //
                            // *** Base Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){

                                $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();
                                if($update_details_RATE_BASE){
                                    $ch_update_data_RATE_BASE['price']=$rate;

                                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=> '0','separate_date'=>$date));

                                    $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                                    if(count($available_update_details_RATE_BASE)!=0){

                                        update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                    }else{
                                        $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_BASE['room_id']= $property_id;
                                        $ch_update_data_RATE_BASE['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                        $ch_update_data_RATE_BASE['end_date']= $en_date;
                                        $ch_update_data_RATE_BASE['separate_date']=$date;
                                        insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                    
                                    }
                                }else{
                                    $ch_update_data_RATE_BASE['price']=$rate;
                                    $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_BASE['room_id']= $property_id;
                                    $ch_update_data_RATE_BASE['individual_channel_id']=0;
                                    $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                    $ch_update_data_RATE_BASE['end_date']= $en_date;
                                    $ch_update_data_RATE_BASE['separate_date']=$date;
                                    insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                  
                                }

                            }
                            // *** End Of Base Rate TBL UPDATE *** //
                            // *** Additional Rate TBL UPDATE *** //
                            elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

                                $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                if($update_details_RATE_ADD){
                                    if($update_details_RATE_ADD->refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($update_details_RATE_ADD->refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                    }

                                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));

                                    $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                    if(count($available_update_details_RATE_ADD)!=0){

                                        update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                    }else{
                                        $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                        $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                        $ch_update_data_RATE_ADD['room_id']= $property_id;
                                        $ch_update_data_RATE_ADD['individual_channel_id']= $importDetails['channel_id'];
                                        $ch_update_data_RATE_ADD['separate_date']=$date;
                                        $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                        $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                        insert_data(RATE_ADD,$ch_update_data_RATE_ADD);

                                    }
                                }else{
                                    if($refun_type=='1'){
                                        $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                    }
                                    elseif($refun_type=='2'){
                                        $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                    }
                                    $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                    $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_ADD['room_id']= $property_id;
                                    $ch_update_data_RATE_ADD['individual_channel_id']=0;
                                    $ch_update_data_RATE_ADD['separate_date']=$date;
                                    $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                    $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                    insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                                 
                                }
                            }
                            // *** End OF Additional Rate TBL UPDATE *** // 
                        }
                    }
                }
                if($mp_details->contract_type == "Static"){
                    $staticrates=$data->Room->Contract->RatePlans->RatePlan->StaticRates->StaticRate;
                    foreach ($staticrates as $staticrate) {
                        $en_date = $begin_date = $date = date("d/m/Y",strtotime($staticrate->attributes()->Start));
                        $rate= $staticrate->StaticRoomRate->attributes()->Nett;
                        // *** Main TBL UPDATE *** //
                        if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0'){

                            $update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));
                            

                            if($update_details->num_rows != 0){

                            
                                $ch_update_data['price']=$rate;

                                update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));
                                                            
                                $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                               
                                if(count($available_update_details)!=0)
                                {
                                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                }
                                else 
                                {
                                   $ch_update_data['owner_id']= current_user_type();
                                   $ch_update_data['hotel_id']= hotel_id();
                                   $ch_update_data['room_id']= $property_id;
                                   $ch_update_data['individual_channel_id']= $importDetails['channel_id'];
                                   $ch_update_data['start_date']= $begin_date;
                                   $ch_update_data['end_date']= $en_date;
                                   $ch_update_data['separate_date']=$date;
                            
                                   insert_data(TBL_UPDATE,$ch_update_data);
                                   
                                }

                            }else{
                                
                                $ch_update_data['price']=$rate;
                                $ch_update_data['owner_id']= current_user_type();
                                $ch_update_data['hotel_id']= hotel_id();
                                $ch_update_data['room_id']= $property_id;
                                $ch_update_data['individual_channel_id']= 0;
                                $ch_update_data['start_date']= $begin_date;
                                $ch_update_data['end_date']= $en_date;
                                $ch_update_data['separate_date']=$date;
                                
                                insert_data(TBL_UPDATE,$ch_update_data);

                            }
                           
                        }

                        // *** End Of TBL UPDATE *** //
                        // *** Reservation TBL UPDATE *** //
                        elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

                            $update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                            

                            if($update_details_RESERV)
                            {
                                if($update_details_RESERV->refun_type=='1')
                                {
                                    $ch_update_data_RESERV['refund_amount']=$rate;
                                }
                                elseif($update_details_RESERV->refun_type=='2')
                                {
                                    $ch_update_data_RESERV['non_refund_amount']=$rate;
                                }

                                update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                            
                                $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

                                if(count($available_update_details_RESERV)!=0)
                                {
                                  update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                }
                                else 
                                {
                                    $ch_update_data_RESERV['owner_id']= current_user_type();
                                    $ch_update_data_RESERV['hotel_id']= hotel_id();
                                    $ch_update_data_RESERV['room_id']= $property_id;
                                    $ch_update_data_RESERV['individual_channel_id']= $importDetails['channel_id'];
                                    $ch_update_data_RESERV['separate_date']=$date;
                                    $ch_update_data_RESERV['guest_count']=$update_details_RESERV->guest_count;
                                    $ch_update_data_RESERV['refun_type']=$update_details_RESERV->refun_type;
                                    insert_data(RESERV,$ch_update_data_RESERV);

                                }
                                
                            }else{
                                if($refun_type=='1')
                                {
                                    $ch_update_data_RESERV['refund_amount']=$rate;
                                }
                                elseif($refun_type=='2')
                                {
                                    $ch_update_data_RESERV['non_refund_amount']=$rate;
                                }
                                $ch_update_data_RESERV['owner_id']= current_user_type();
                                $ch_update_data_RESERV['hotel_id']= hotel_id();
                                $ch_update_data_RESERV['room_id']= $property_id;
                                $ch_update_data_RESERV['individual_channel_id']= 0;
                                $ch_update_data_RESERV['separate_date']=$date;
                                $ch_update_data_RESERV['guest_count']=$guest_count;
                                $ch_update_data_RESERV['refun_type']=$refun_type;
                                insert_data(RESERV,$ch_update_data_RESERV);
                        
                            }
                        }
                        // *** End Of Reservation TBL UPDATE *** //
                        // *** Base Rate TBL UPDATE *** //
                        elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){

                            $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();
                            if($update_details_RATE_BASE){
                                $ch_update_data_RATE_BASE['price']=$rate;

                                update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=> '0','separate_date'=>$date));

                                $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date))->row_array();
                                if(count($available_update_details_RATE_BASE)!=0){

                                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date));
                                }else{
                                    $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_BASE['room_id']= $property_id;
                                    $ch_update_data_RATE_BASE['individual_channel_id']= $importDetails['channel_id'];
                                    $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                    $ch_update_data_RATE_BASE['end_date']= $en_date;
                                    $ch_update_data_RATE_BASE['separate_date']=$date;
                                    insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                                
                                }
                            }else{
                                $ch_update_data_RATE_BASE['price']=$rate;
                                $ch_update_data_RATE_BASE['hotel_id']= hotel_id();
                                $ch_update_data_RATE_BASE['room_id']= $property_id;
                                $ch_update_data_RATE_BASE['individual_channel_id']=0;
                                $ch_update_data_RATE_BASE['start_date']= $begin_date;
                                $ch_update_data_RATE_BASE['end_date']= $en_date;
                                $ch_update_data_RATE_BASE['separate_date']=$date;
                                insert_data(RATE_BASE,$ch_update_data_RATE_BASE);
                              
                            }

                        }
                        // *** End Of Base Rate TBL UPDATE *** //
                        // *** Additional Rate TBL UPDATE *** //
                        elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

                            $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                            if($update_details_RATE_ADD){
                                if($update_details_RATE_ADD->refun_type=='1'){
                                    $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                }
                                elseif($update_details_RATE_ADD->refun_type=='2'){
                                    $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                }

                                update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));

                                $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();
                                if(count($available_update_details_RATE_ADD)!=0){

                                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$importDetails['channel_id'],'separate_date'=>$date->format('d/m/Y'),'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                                }else{
                                    $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                    $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                    $ch_update_data_RATE_ADD['room_id']= $property_id;
                                    $ch_update_data_RATE_ADD['individual_channel_id']= $importDetails['channel_id'];
                                    $ch_update_data_RATE_ADD['separate_date']=$date;
                                    $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                    $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                    insert_data(RATE_ADD,$ch_update_data_RATE_ADD);

                                }
                            }else{
                                if($refun_type=='1'){
                                    $ch_update_data_RATE_ADD['refund_amount']=$rate;
                                }
                                elseif($refun_type=='2'){
                                    $ch_update_data_RATE_ADD['non_refund_amount']=$rate; 
                                }
                                $ch_update_data_RATE_ADD['owner_id']= current_user_type();
                                $ch_update_data_RATE_ADD['hotel_id']= hotel_id();
                                $ch_update_data_RATE_ADD['room_id']= $property_id;
                                $ch_update_data_RATE_ADD['individual_channel_id']=0;
                                $ch_update_data_RATE_ADD['separate_date']=$date;
                                $ch_update_data_RATE_ADD['guest_count']=$guest_count;
                                $ch_update_data_RATE_ADD['refun_type']=$refun_type;
                                insert_data(RATE_ADD,$ch_update_data_RATE_ADD);
                             
                            }
                        }
                        // *** End OF Additional Rate TBL UPDATE *** // 
                    }
                }
            }
            redirect('mapping/settings/'.$channel_id,'refresh');        
        }
        else
        {
            redirect(base_url());
        }
    }

    function importAvailabilities($channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$arrival = "",$departure = "",$mappingid = "",$mapping='')
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $property_id = insep_decode($propertyid);
        if($mappingid != ""){
            $importDetails = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }else{
            $importDetails = get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        }
        if($arrival != "" && $departure != ""){

            $start_date = date('d.m.Y',strtotime($arrival));
            $end_date = date('d.m.Y',strtotime($departure));

            $exp_start_date = $arrival;
            $exp_end_date = $departure;

            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);

            $start = date('d/m/Y',strtotime(str_replace('-', '/', $arrival)));
            $end = date('d/m/Y', strtotime(str_replace('-', '/', $departure)));

        }else{
            $start_date = date('d.m.Y');

            $end_date = date('d.m.Y', strtotime("+30 days"));

            $exp_start_date = date('Y-m-d');
            $exp_end_date = date('Y-m-d', strtotime("+30 days"));

            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);

            $start = date('d/m/Y');
            $end = date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id'] = insep_decode($channel_id);
        $channel['property_id'] = $property_id;
        $channel['rate_id'] = $rate_id;
        $channel['guest_count'] = $guest_count;
        $channel['refun_type'] = $refun_type;
        $channel['start'] = $start;
        $channel['end'] = $end;

        if(count($importDetails)!=0){
            if($importDetails['channel_id'] == 11)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data(IM_RECO,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'re_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();

                $url = trim($reco['irate_avail']);

                $xml_rate = '<?xml version="1.0" encoding="utf-8"?>
                                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                <soap12:Body>
                                <GetAvail xmlns="https://www.reconline.com/">
                                <User>'.$ch_details->user_name.'</User>
                                  <Password>'.$ch_details->user_password.'</Password>
                                  <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                                  <idSystem>0</idSystem>
                                  <ForeignPropCode></ForeignPropCode>
                                  <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                                  <ExcludeRateLevels></ExcludeRateLevels>
                                  <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                                  <ExcludeRoomTypes></ExcludeRoomTypes>
                                  <StartDate>'.$start_date.'</StartDate>
                                  <EndDate>'.$end_date.'</EndDate>
                                </GetAvail>
                              </soap12:Body>
                            </soap12:Envelope>';
                $headers = array(
                    "Content-type: application/soap+xml; charset=utf-8",
                    "Host:www.reconline.com",
                    "Content-length: ".strlen($xml_rate),
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_rate);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $ss = curl_getinfo($ch);    
                $response = curl_exec($ch);
                $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml = simplexml_load_string($xml);
                $json = json_encode($xml);
                $responseArray = json_decode($json,true);
                $getAvail = $responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Availability']['AVAIL']; 
                $Errorarray = @$responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Warning'];
                $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
                if(count($Errorarray)=='0' && count($soapFault)=='0')
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                    $reconline_availability_response="success";
                }
                else 
                {
                    $reconline_availability_response="error";
                    if(count($Errorarray)!='0')
                    {
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$Errorarray['WARNING'],'Import Availabilities',date('m/d/Y h:i:s a', time()));
                        $this->session->set_flashdata('import_rate_error',$Errorarray['WARNING']);
                    }
                    else if(count($soapFault)!='0')
                    {   
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$soapFault['soapText'],'Import Availabilities',date('m/d/Y h:i:s a', time()));   
                        $this->session->set_flashdata('import_rate_error',$soapFault['soapText']);
                    }
                    return false;
                } 
                curl_close($ch);
                if($reconline_availability_response == "success"){
                    $avail = explode(":", $getAvail);   
                    $ab_value = explode(",", $avail[1]);
                    foreach ($ab_value as $key => $value) {
                        $date = date('d/m/Y', strtotime("+".$key." days"));
                        $this->update_channel_calendar(current_user_type(),hotel_id(),$channel,$value,$date,$mapping);
                    }
                }
            }
			else if($importDetails['channel_id'] == 1)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();

                $url = trim($exp['irate_avail']);

                $xml_data = '<ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">
                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                        <Hotel id="'.$ch_details->hotel_channel_id.'"/>
                        <ParamSet>
                        <AvailRateRetrieval from="'.$exp_start_date.'" to="'.$exp_end_date.'">
                        <RoomType id="'.$mp_details->roomtype_id.'" >';
                        
                $xml_data .= '<RatePlan id="'.$mp_details->rate_type_id.'"/>';
                $xml_data .= '</RoomType>
                </AvailRateRetrieval>
                </ParamSet>
                </ProductAvailRateRetrievalRQ>';

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                //echo $output;
                $data = simplexml_load_string($output); 
                //echo '<pre>';
                //print_r($data);
                $response = $data->Error;
                $avail = $data->AvailRateList->AvailRate;

                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($response!='')
                {
                    //echo $response;
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$response,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    $expedia_availability_response="error";
                    //return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Expedia!!!');
                    $expedia_availability_response="success";
                }

                if($expedia_availability_response == "success"){

                    foreach($avail as $data){
                        
                        $date = $data->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime(str_replace('-','/',$date)));
                        $availability =  $data->RoomType->Inventory->attributes()->totalInventoryAvailable;

                        $this->update_channel_calendar(current_user_type(),hotel_id(),$channel,$availability,$sep_date,$mapping);
                    }
                }
            }
			else if($importDetails['channel_id'] == 5)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();   

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
                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id']))->row();

                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventory xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryRQ>
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
                            <DateFrom date="'.$hotelbed_start_date.'"/>
                            <DateTo date="'.$hotelbed_end_date.'"/>
                            </HSI_ContractInventoryRQ>
                            </getHSIContractInventory>
                            </soapenv:Body>
                            </soapenv:Envelope>';

                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_data),
                ); 
                $url = trim($htb['irate_avail']);

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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss = curl_getinfo($ch);                
                $response = curl_exec($ch);
                
                //echo '<pre>';
                //echo $response;
                
                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse = simplexml_load_string($xmlreplace);
                $json = json_encode($xml_parse);
                $responseArray = json_decode($json,true);
                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventory']);

                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($xml->ErrorList->Error)
                {
                    //echo $response;
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$xml->ErrorList->Error->DetailedMessage,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Hotelbeds!!!');
                    $hotelbed_availability_response="success";
                }

                if($hotelbed_availability_response == "success"){

                    foreach($xml->InventoryItem as $dataval){
                        
                        $date = $dataval->DateFrom->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime($date));
                        foreach ($dataval->Room as $details) {
                        //print_r($detail_s->RoomType);
                            foreach($details->RoomType as $room_name)
                                //print_r($room_name->attributes());
                            if($room_name->attributes()->code == $mp_details->roomname && $room_name->attributes()->characteristic == $mp_details->characterstics){

                                $availability =  $details->attributes()->available;


                                $this->update_channel_calendar(current_user_type(),hotel_id(),$channel,$availability,$sep_date,$mapping);
                                
                            }
                        }                       

                    }
                }
            }
			else if($importDetails['channel_id'] == 8)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping_GTA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$importDetails['channel_id'],'GTA_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();

                 $room_id=$mp_details->Id;
                 $rateplanid=$mp_details->rateplan_id;
                 $MinPax=$mp_details->MinPax;
                 $peakrate=$mp_details->peakrate;
                 $MaxOccupancy=$mp_details->MaxOccupancy;
                 $minnights=$mp_details->minnights;
                 $payfullperiod=$mp_details->payfullperiod;
                 $contract_id=$mp_details->contract_id; 
                 $hotel_channel_id=$mp_details->hotel_channel_id;
                 $contract_type = $mp_details->contract_type;

                $soapUrl = trim($gta['iavail']);
                $xml_post_string = '<GTA_InventoryReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"   xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 TA_InventoryReadRQ.xsd">
                     <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                     <Inventory ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'" FromDate = "'.$exp_start_date.'" ToDate = "'.$exp_end_date.'" DaysOfWeek = "1111111"/>
                    <RoomTypes>
                    <RoomType RoomId = "'.$room_id.'"/>
                    </RoomTypes>
                    </GTA_InventoryReadRQ>
                    ';

                $ch = curl_init($soapUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $resdata = simplexml_load_string($output);
                //echo "<pre>";
                //print_r($resdata);
               
                if(isset($resdata->Errors->Error))
                {
                    $this->inventory_model->store_error(current_user_type(),hotel_id(),$importDetails['channel_id'],(string)$resdata->Errors->Error,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From GTA!!!');
                    $gta_availability_response="success";
                }
                if($gta_availability_response == "success"){
                    $availabilities = $resdata->InventoryBlock->StayDate;
                    foreach ($availabilities as $avail) {
                        $sep_date = date("d/m/Y", strtotime($avail->attributes()->Date));
                        $availability = $avail->Inventory->InventoryDetail->attributes()->TotalQuantityAvailable;

                        $this->update_channel_calendar(current_user_type(),hotel_id(),$channel,$availability,$sep_date,$mapping);
                        
                    }
                }
            }
			else if($importDetails['channel_id'] == 2)
			{
				$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row();
				if($ch_details->xml_type==1 || $ch_details->xml_type==2)
				{
					$this->booking_model->importAvailabilities(current_user_type(),hotel_id(),$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure);
				}
            }
			else if($importDetails['channel_id'] == 17)
			{
				$this->bnow_model->importAvailabilities(current_user_type(),hotel_id(),$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure);
            }
            if($mapping == "")
			{
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
			else
			{
                return true;
            }
        }
		else
        {
            if($mapping == "")
			{
                redirect(base_url());
            }
			else
			{
                return true;
            }
        }
    }
    
    function importAvailabilities_Cron($owner_id,$hotel_id,$channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$arrival = "",$departure = "",$mappingid = "",$mapping='')
    {
		if ($channel_id == 1) {
			makeLog('expediaResv_RespLog_'.$hotel_id.'.txt',"importAvailabilities_Cron=>Params\n$owner_id--$hotel_id--$channel_id--$propertyid--$rate_id--$guest_count--$refun_type--$arrival = \"\"--$departure = \"\"--$mappingid = \"\"--$mapping=''---\n");
		} else if ($channel_id == 2) {
			makeLog('bookingsResv_RespLog_'.$hotel_id.'.txt',"importAvailabilities_Cron=>Params\n$owner_id--$hotel_id--$channel_id--$propertyid--$rate_id--$guest_count--$$refun_type--$arrival = \"\"--$departure = \"\"--$mappingid = \"\"--$mapping=''---\n");
		}
        $property_id = insep_decode($propertyid);

		if($mappingid != "")
		{
            $importDetails = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }
		else
		{
            $importDetails = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        }
        if($arrival != "" && $departure != "")
        {
            $start_date = date('d.m.Y',strtotime($arrival));
            $end_date = date('d.m.Y',strtotime($departure));
            $exp_start_date = $arrival;
            $exp_end_date = $departure;
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y',strtotime(str_replace('-', '/', $arrival)));
            $end = date('d/m/Y', strtotime(str_replace('-', '/', $departure)));
        }
        else
        {
            $start_date = date('d.m.Y');
            $end_date = date('d.m.Y', strtotime("+30 days"));
            $exp_start_date = date('Y-m-d');
            $exp_end_date = date('Y-m-d', strtotime("+30 days"));
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y');
            $end = date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id'] = insep_decode($channel_id);
        $channel['property_id'] = $property_id;
        $channel['rate_id'] = $rate_id;
        $channel['guest_count'] = $guest_count;
        $channel['refun_type'] = $refun_type;
        $channel['start'] = $start;
        $channel['end'] = $end;
        
        if(count($importDetails)!=0)
        {
            if($importDetails['channel_id'] == 11)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
                if($ch_details->mode == 0)
                {
                    $urls = explode(',', $ch_details->test_url);
                    foreach($urls as $url)
                    {
                    $path = explode("~",$url);
                    $reco[$path[0]] = $path[1];
                    }
                }
                else if($ch_details->mode == 1)
                {
                    $urls = explode(',', $ch_details->live_url);
                    foreach($urls as $url)
                    {
                        $path = explode("~",$url);
                        $reco[$path[0]] = $path[1];
                    }
                } 
                
				$mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'re_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
            
				$url = trim($reco['irate_avail']);
            
				$xml_rate = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                            <GetAvail xmlns="https://www.reconline.com/">
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                            <idSystem>0</idSystem>
                            <ForeignPropCode></ForeignPropCode>
                            <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                            <ExcludeRateLevels></ExcludeRateLevels>
                            <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                            <ExcludeRoomTypes></ExcludeRoomTypes>
                            <StartDate>'.$start_date.'</StartDate>
                            <EndDate>'.$end_date.'</EndDate>
                            </GetAvail>
                            </soap12:Body>
                            </soap12:Envelope>
                        ';
            $headers = array(
                                "Content-type: application/soap+xml; charset=utf-8",
                                "Host:www.reconline.com",
                                "Content-length: ".strlen($xml_rate),
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_rate);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $ss = curl_getinfo($ch);    
            $response = curl_exec($ch);
            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
            $xml = simplexml_load_string($xml);
            $json = json_encode($xml);
            $responseArray = json_decode($json,true);
            $getAvail = $responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Availability']['AVAIL']; 
            $Errorarray = @$responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Warning'];
            $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
            if(count($Errorarray)=='0' && count($soapFault)=='0')
            {
                $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                $reconline_availability_response="success";
            }
            else 
            {
                $reconline_availability_response="error";
                if(count($Errorarray)!='0')
                {
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$Errorarray['WARNING'],'importAvailabilities_Cron',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error',$Errorarray['WARNING']);
                }
                else if(count($soapFault)!='0')
                {   
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$soapFault['soapText'],'importAvailabilities_Cron',date('m/d/Y h:i:s a', time()));   
                    $this->session->set_flashdata('import_rate_error',$soapFault['soapText']);
                }
                return false;
            } 
            curl_close($ch);
            if($reconline_availability_response == "success")
            {
                $avail = explode(":", $getAvail);   
                $ab_value = explode(",", $avail[1]);
                foreach ($ab_value as $key => $value) 
				{
                    $date = date('d/m/Y', strtotime("+".$key." days"));
                    $this->update_channel_calendar($owner_id,$hotel_id,$channel,$value,$date,$mapping);
                }
            }
            }
            else if($importDetails['channel_id'] == 1)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
        
                $url = trim($exp['irate_avail']);
        
                $xml_data = '<ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">
                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                        <Hotel id="'.$ch_details->hotel_channel_id.'"/>
                        <ParamSet>
                        <AvailRateRetrieval from="'.$exp_start_date.'" to="'.$exp_end_date.'">
                        <RoomType id="'.$mp_details->roomtype_id.'" >';
                        
                $xml_data .= '<RatePlan id="'.$mp_details->rate_type_id.'"/>';
                $xml_data .= '</RoomType>
                </AvailRateRetrieval>
                </ParamSet>
                </ProductAvailRateRetrievalRQ>';
        
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                //echo $output;
                $data = simplexml_load_string($output); 
                //echo '<pre>';
                //print_r($data);
                $response = $data->Error;
                $avail = $data->AvailRateList->AvailRate;
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($response!='')
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$response,'importAvailabilities_Cron',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                    $expedia_availability_response = "error";
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Expedia!!!');
                    $expedia_availability_response="success";
                }
        
                if($expedia_availability_response == "success"){
        
                    foreach($avail as $data){
                        
                        $date = $data->attributes()->date;
                        //$sep_date = date('d/m/Y',strtotime(str_replace('-','/',$date)));
						$sep_date = date('d/m/Y',strtotime($date));
						
                        $availability =  $data->RoomType->Inventory->attributes()->totalInventoryAvailable;
                        $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 5)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();   
        
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
                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id']))->row();
        
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventory xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryRQ>
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
                            <DateFrom date="'.$hotelbed_start_date.'"/>
                            <DateTo date="'.$hotelbed_end_date.'"/>
                            </HSI_ContractInventoryRQ>
                            </getHSIContractInventory>
                            </soapenv:Body>
                            </soapenv:Envelope>';
        
                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_data),
                ); 
                $url = trim($htb['irate_avail']);
        
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss = curl_getinfo($ch);                
                $response = curl_exec($ch);
                
                //echo '<pre>';
                //echo $response;
                
                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse = simplexml_load_string($xmlreplace);
                $json = json_encode($xml_parse);
                $responseArray = json_decode($json,true);
                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventory']);
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($xml->ErrorList->Error)
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$xml->ErrorList->Error->DetailedMessage,'importAvailabilities_Cron',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                    $hotelbed_availability_response = "Error";
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Hotelbeds!!!');
                    $hotelbed_availability_response="success";
                }
        
                if($hotelbed_availability_response == "success"){
        
                    foreach($xml->InventoryItem as $dataval){
                        
                        $date = $dataval->DateFrom->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime($date));
                        foreach ($dataval->Room as $details) {

                        //print_r($detail_s->RoomType);
                            foreach($details->RoomType as $room_name)
                            if($room_name->attributes()->code == $mp_details->roomname && $room_name->attributes()->characteristic == $mp_details->characterstics){
                                
                                $availability =  $details->attributes()->available;
                                
                                $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                            }
                        }                       
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 8)
			{
                
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping_GTA',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'GTA_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
        
                 $room_id=$mp_details->Id;
                 $rateplanid=$mp_details->rateplan_id;
                 $MinPax=$mp_details->MinPax;
                 $peakrate=$mp_details->peakrate;
                 $MaxOccupancy=$mp_details->MaxOccupancy;
                 $minnights=$mp_details->minnights;
                 $payfullperiod=$mp_details->payfullperiod;
                 $contract_id=$mp_details->contract_id; 
                 $hotel_channel_id=$mp_details->hotel_channel_id;
                 $contract_type = $mp_details->contract_type;
        
                $soapUrl = trim($gta['iavail']);
                $xml_post_string = '<GTA_InventoryReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"   xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 TA_InventoryReadRQ.xsd">
                     <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                     <Inventory ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'" FromDate = "'.$exp_start_date.'" ToDate = "'.$exp_end_date.'" DaysOfWeek = "1111111"/>
                    <RoomTypes>
                    <RoomType RoomId = "'.$room_id.'"/>
                    </RoomTypes>
                    </GTA_InventoryReadRQ>
                    ';
        
                $ch = curl_init($soapUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $resdata = simplexml_load_string($output);
               
                if(isset($resdata->Errors->Error))
                {
						  
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$resdata->Errors->Error,'importAvailabilities_Cron_GTA',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    $gta_availability_response="error";
                    return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From GTA!!!');
                    $gta_availability_response="success";
                }
                if($gta_availability_response == "success"){
                    $availabilities = $resdata->InventoryBlock->StayDate;
                    foreach ($availabilities as $avail) {
                        $sep_date = date("d/m/Y", strtotime($avail->attributes()->Date));
                       
                        if($avail->Inventory){
                            $availability = @$avail->Inventory->InventoryDetail->attributes()->TotalQuantityAvailable;
                            if($availability != ""){
                                $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                            }
                        }
                    }
                }
            }
            else if($importDetails['channel_id'] == 2)
			{
                
                $this->booking_model->importAvailabilities($owner_id,$hotel_id,$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure);
            }
            if($mapping == "")
            {
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
            else
            {
                return true;
            }
        }
        else
        {
            if($mapping == "")
            {
                redirect(base_url());
            }
            else
            {
                return true;
            }
        }
    }
	
	function importAvailabilities_Cron_Test($owner_id,$hotel_id,$channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$arrival = "",$departure = "",$mappingid = "",$mapping='')
    {
        $property_id = insep_decode($propertyid);

		if($mappingid != "")
		{
            $importDetails = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }
		else
		{
            $importDetails = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        }
		/* echo '<pre>';
							print_r($importDetails); */
		
        if($arrival != "" && $departure != "")
        {
            $start_date = date('d.m.Y',strtotime($arrival));
            $end_date = date('d.m.Y',strtotime($departure));
            $exp_start_date = $arrival;
            $exp_end_date = $departure;
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y',strtotime(str_replace('-', '/', $arrival)));
            $end = date('d/m/Y', strtotime(str_replace('-', '/', $departure)));
        }
        else
        {
            $start_date = date('d.m.Y');
            $end_date = date('d.m.Y', strtotime("+30 days"));
            $exp_start_date = date('Y-m-d');
            $exp_end_date = date('Y-m-d', strtotime("+30 days"));
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y');
            $end = date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id'] = insep_decode($channel_id);
        $channel['property_id'] = $property_id;
        $channel['rate_id'] = $rate_id;
        $channel['guest_count'] = $guest_count;
        $channel['refun_type'] = $refun_type;
        $channel['start'] = $start;
        $channel['end'] = $end;
        
        if(count($importDetails)!=0)
        {
            if($importDetails['channel_id'] == 11)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
                if($ch_details->mode == 0)
                {
                    $urls = explode(',', $ch_details->test_url);
                    foreach($urls as $url)
                    {
                    $path = explode("~",$url);
                    $reco[$path[0]] = $path[1];
                    }
                }
                else if($ch_details->mode == 1)
                {
                    $urls = explode(',', $ch_details->live_url);
                    foreach($urls as $url)
                    {
                        $path = explode("~",$url);
                        $reco[$path[0]] = $path[1];
                    }
                } 
                
				$mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'re_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
            
				$url = trim($reco['irate_avail']);
            
				$xml_rate = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                            <GetAvail xmlns="https://www.reconline.com/">
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                            <idSystem>0</idSystem>
                            <ForeignPropCode></ForeignPropCode>
                            <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                            <ExcludeRateLevels></ExcludeRateLevels>
                            <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                            <ExcludeRoomTypes></ExcludeRoomTypes>
                            <StartDate>'.$start_date.'</StartDate>
                            <EndDate>'.$end_date.'</EndDate>
                            </GetAvail>
                            </soap12:Body>
                            </soap12:Envelope>
                        ';
            $headers = array(
                                "Content-type: application/soap+xml; charset=utf-8",
                                "Host:www.reconline.com",
                                "Content-length: ".strlen($xml_rate),
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_rate);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $ss = curl_getinfo($ch);    
            $response = curl_exec($ch);
            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
            $xml = simplexml_load_string($xml);
            $json = json_encode($xml);
            $responseArray = json_decode($json,true);
            $getAvail = $responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Availability']['AVAIL']; 
            $Errorarray = @$responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Warning'];
            $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
            if(count($Errorarray)=='0' && count($soapFault)=='0')
            {
                $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                $reconline_availability_response="success";
            }
            else 
            {
                $reconline_availability_response="error";
                if(count($Errorarray)!='0')
                {
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$Errorarray['WARNING'],'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error',$Errorarray['WARNING']);
                }
                else if(count($soapFault)!='0')
                {   
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$soapFault['soapText'],'Import Availabilities',date('m/d/Y h:i:s a', time()));   
                    $this->session->set_flashdata('import_rate_error',$soapFault['soapText']);
                }
                return false;
            } 
            curl_close($ch);
            if($reconline_availability_response == "success")
            {
                $avail = explode(":", $getAvail);   
                $ab_value = explode(",", $avail[1]);
                foreach ($ab_value as $key => $value) 
				{
                    $date = date('d/m/Y', strtotime("+".$key." days"));
                    $this->update_channel_calendar($owner_id,$hotel_id,$channel,$value,$date,$mapping);
                }
            }
            }
            else if($importDetails['channel_id'] == 1)
			{
				echo $importDetails['channel_id'];
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
				//print_r($mp_details);
                $url = trim($exp['irate_avail']);
        
                $xml_data = '<ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">
                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                        <Hotel id="'.$ch_details->hotel_channel_id.'"/>
                        <ParamSet>
                        <AvailRateRetrieval from="'.$exp_start_date.'" to="'.$exp_end_date.'">
                        <RoomType id="'.$mp_details->roomtype_id.'" >';
                        
                $xml_data .= '<RatePlan id="'.$mp_details->rate_type_id.'"/>';
                $xml_data .= '</RoomType>
                </AvailRateRetrieval>
                </ParamSet>
                </ProductAvailRateRetrievalRQ>';
        
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                //echo $output;
                $data = simplexml_load_string($output); 
                /* echo '<pre>';
                print_r($data);
				die; */
                $response = $data->Error;
                $avail = $data->AvailRateList->AvailRate;
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($response!='')
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$response,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                    $expedia_availability_response = "error";
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                    $expedia_availability_response="success";
                }
        
                if($expedia_availability_response == "success"){
				
				/* echo '<pre>';
               // print_r($data);
                print_r($avail);
				echo 'sdfsdf'; */
				
        
                    foreach($avail as $data){
                        
                        $date = $data->attributes()->date;
						$sep_date = date('d/m/Y',strtotime($date));
                        $availability =  $data->RoomType->Inventory->attributes()->totalInventoryAvailable;
                        $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 5)
			{
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();   
        
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
                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'map_id'=>$importDetails['import_mapping_id']))->row();
        
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventory xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryRQ>
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
                            <DateFrom date="'.$hotelbed_start_date.'"/>
                            <DateTo date="'.$hotelbed_end_date.'"/>
                            </HSI_ContractInventoryRQ>
                            </getHSIContractInventory>
                            </soapenv:Body>
                            </soapenv:Envelope>';
        
                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_data),
                ); 
                $url = trim($htb['irate_avail']);
        
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss = curl_getinfo($ch);                
                $response = curl_exec($ch);
                
                //echo '<pre>';
                //echo $response;
                
                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse = simplexml_load_string($xmlreplace);
                $json = json_encode($xml_parse);
                $responseArray = json_decode($json,true);
                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventory']);
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($xml->ErrorList->Error)
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$xml->ErrorList->Error->DetailedMessage,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                    $hotelbed_availability_response = "Error";
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Hotelbeds!!!');
                    $hotelbed_availability_response="success";
                }
        
                if($hotelbed_availability_response == "success"){
        
                    foreach($xml->InventoryItem as $dataval){
                        
                        $date = $dataval->DateFrom->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime($date));
                        foreach ($dataval->Room as $details) {
                        //print_r($detail_s->RoomType);
                            foreach($details->RoomType as $room_name)
                            if($room_name->attributes()->code == $mp_details->roomname && $room_name->attributes()->characteristic == $mp_details->characterstics){
        
                                $availability =  $details->attributes()->available;
                                $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                            }
                        }                       
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 8)
			{
                
                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data('import_mapping_GTA',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'GTA_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
        
                 $room_id=$mp_details->Id;
                 $rateplanid=$mp_details->rateplan_id;
                 $MinPax=$mp_details->MinPax;
                 $peakrate=$mp_details->peakrate;
                 $MaxOccupancy=$mp_details->MaxOccupancy;
                 $minnights=$mp_details->minnights;
                 $payfullperiod=$mp_details->payfullperiod;
                 $contract_id=$mp_details->contract_id; 
                 $hotel_channel_id=$mp_details->hotel_channel_id;
                 $contract_type = $mp_details->contract_type;
        
                $soapUrl = trim($gta['iavail']);
                $xml_post_string = '<GTA_InventoryReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"   xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 TA_InventoryReadRQ.xsd">
                     <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                     <Inventory ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'" FromDate = "'.$exp_start_date.'" ToDate = "'.$exp_end_date.'" DaysOfWeek = "1111111"/>
                    <RoomTypes>
                    <RoomType RoomId = "'.$room_id.'"/>
                    </RoomTypes>
                    </GTA_InventoryReadRQ>
                    ';
        
                $ch = curl_init($soapUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $resdata = simplexml_load_string($output);
               
                if(isset($resdata->Errors->Error))
                {
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$resdata->Errors->Error,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    $gta_availability_response="error";
                    return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From GTA!!!');
                    $gta_availability_response="success";
                }
                if($gta_availability_response == "success"){
                    $availabilities = $resdata->InventoryBlock->StayDate;
                    foreach ($availabilities as $avail) {
                        $sep_date = date("d/m/Y", strtotime($avail->attributes()->Date));
                       
                        if($avail->Inventory){
                            $availability = @$avail->Inventory->InventoryDetail->attributes()->TotalQuantityAvailable;
                            if($availability != ""){
                                $this->update_channel_calendar($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                            }
                        }
                    }
                }
            }
            else if($importDetails['channel_id'] == 2)
			{
                
                $this->booking_model->importAvailabilities($owner_id,$hotel_id,$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure);
            }
            if($mapping == "")
            {
                redirect('mapping/settings/'.$channel_id,'refresh');
            }
            else
            {
                return true;
            }
        }
        else
        {
            if($mapping == "")
            {
                redirect(base_url());
            }
            else
            {
                return true;
            }
        }
    }
    
    function GetDays($sStartDate='2016-03-31', $sEndDate='2016-04-02')
    {  
    
        $datetime1 = new DateTime('2016-03-31');
        $datetime2 = new DateTime('2016-04-02');
        $interval = $datetime1->diff($datetime2);
        echo $interval->format('%a%')+1; die;
        // Firstly, format the provided dates.  
        // This function works best with YYYY-MM-DD  
        // but other date formats will work thanks  
        // to strtotime().  
        $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
        $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  
        
        // Start the variable off with the start date  
        $aDays[] = $sStartDate;  
        
        // Set a 'temp' variable, sCurrentDate, with  
        // the start date - before beginning the loop  
        $sCurrentDate = $sStartDate;  
        
        // While the current date is less than the end date  
        while($sCurrentDate < $sEndDate){  
        // Add a day to the current date  
        $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
        
        // Add this new day to the aDays array  
        $aDays[] = $sCurrentDate;  
        }  
        
        // Once the loop has finished, return the  
        // array of days.  
        echo $aDays;  
    }
    
        //GetDays('2007-01-01', '2007-01-31'); 

    function getExpediaLevel(){
        $mapping_id = $this->input->post('mapping_id');
        $data = $this->mapping_model->getExpediaLevel($mapping_id);
        echo json_encode($data);
    }

    function update_subrooms($availability,$channel,$sep_date,$user_id,$hotel_id){
        extract($channel);

        if($property_id != 0 && $rate_id == 0 && $guest_count == 0){
            $main = 0; $guest = 1; $rate_base = 1; $rate_add = 1;
        }else if($property_id != 0 && $rate_id == 0 && $guest_count != 0){
            $main = 1; $guest = 0; $rate_base = 1; $rate_add = 1;
        }else if($property_id != 0 && $rate_id != 0 && $guest_count == 0){
            $main = 1; $guest = 1; $rate_base = 0; $rate_add = 1;
        }else if($property_id != 0 && $rate_id != 0 && $guest_count != 0){
            $main = 1; $guest = 1; $rate_base = 1; $rate_add = 0;
        }
        $avail['availability'] = $availability;
        if($guest != 0){
            $reserve = $this->db->query("SELECT * FROM reservation_table WHERE owner_id = '".$user_id."' AND hotel_id = '".$hotel_id."' AND room_id='".$property_id."' GROUP BY guest_count")->result();
            foreach($reserve as $details){
                $data['channel_id'] = $channel_id;
                $data['guest_count'] = $details->guest_count;                        
                $data['rate_id'] = $details->rate_types_id;
                $data['refun_type'] = $details->refun_type;
                $data['property_id'] = $property_id;
                $data['start'] = $start;
                $data['end'] = $end;
                $this->update_channel($user_id,$hotel_id,$avail,$data,$sep_date);
            }
        }

        if($rate_base != 0){
            $reserve = $this->db->query("SELECT * FROM `room_rate_types_base` WHERE owner_id = '".$user_id."' AND hotel_id = '".$hotel_id."' AND room_id='".$property_id."' GROUP BY rate_types_id;")->result();

            foreach($reserve as $details){
                $data['channel_id'] = $channel_id;
                $data['guest_count'] = $guest_count;                     
                $data['rate_id'] = $details->rate_types_id;
                $data['refun_type'] = $refun_type;
                $data['property_id'] = $property_id;
                $data['start'] = $start;
                $data['end'] = $end;
                $this->update_channel($user_id,$hotel_id,$avail,$data,$sep_date);

            }
        }
        
        if($rate_add != 0){
            $reserve = $this->db->query("SELECT * FROM `room_rate_types_additional` WHERE owner_id = '".$user_id."' AND hotel_id='".$hotel_id."' AND room_id = '".$property_id."' GROUP BY guest_count")->result();

            foreach($reserve as $details){

                $data['channel_id'] = $channel_id;
                $data['guest_count'] = $details->guest_count;                        
                $data['rate_id'] = $details->rate_types_id;
                $data['refun_type'] = $details->refun_type;
                $data['property_id'] = $property_id;
                $data['start'] = $start;
                $data['end'] = $end;
                $this->update_channel($user_id,$hotel_id,$avail,$data,$sep_date);

            }
            // RATE ADDITIONAL TABLE UPDATE //
        }

        if($main != 0){
            // MAIN TABLE UPDATE //
            $reserve = $this->db->query("SELECT * FROM `room_update` where owner_id = '".$user_id."' AND hotel_id='".$hotel_id."' AND room_id = '".$property_id."' GROUP BY room_id")->result();

            foreach($reserve as $details){

                $data['channel_id'] = $channel_id;
                $data['guest_count'] = $guest_count;                        
                $data['rate_id'] = $rate_id;
                $data['refun_type'] = $refun_type;
                $data['property_id'] = $property_id;
                $data['start'] = $start;
                $data['end'] = $end;
                $this->update_channel($user_id,$hotel_id,$avail,$data,$sep_date);

            }           
            // MAIN TABLE UPDATE //
        }
        
    }


    /*function update_channel($owner_id,$hotel_id,$udata,$ch_data,$sep_date,$manual="")
    {
        extract($udata);
        extract($ch_data);

        $ch_id = $channel_id;
        $all_channel = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id))->result_array(); 
        if(count($all_channel) != "")
        {
            foreach($all_channel as $con_ch)
            {
                extract($con_ch);
                $con_ch = $channel_id;
                if($con_ch != $ch_id){
                    $room_mapping = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch,'property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type,'enabled'=>'enabled'))->result();
                    if($room_mapping){

                        foreach($room_mapping as $room_value){

                            if($room_value->channel_id == 1){
                                $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data('import_mapping',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                if($availability != "" && $room_value->update_availability=='1'){
                                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    $xml .= '<DateRange from="'.$update_date.'" to="'.$update_date.'"/>';
                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    
                                    $xml .= '<Inventory totalInventoryAvailable="'.$availability.'"/>';
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
                                    //echo $response;
                                    if($response!='')
                                    {
                                       // echo 'fail';
                                        $this->inventory_model->store_error($room_value->channel_id,(string)$response,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                       // echo 'success   ';
                                        $expedia_update = "Success";
                                    }
                                    curl_close($ch);
                                }
                            }else if($room_value->channel_id == 11){
                                
                                $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id))->row();
                                if($availability!='' && $room_value->update_availability=='1')
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
                                    <Availability>='.$availability.'</Availability>
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
                                            $this->inventory_model->store_error($room_value->channel_id,(string)$Errorarray['WARNING'],'Import Availabilities',date('m/d/Y h:i:s a', time()));
                                            $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {     
                                            $this->inventory_model->store_error($room_value->channel_id,(string)$soapFault['soapText'],'Import Availabilities',date('m/d/Y h:i:s a', time())); 
                                            $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                        //return false;
                                    }
                                    curl_close($ch);
                                } 
                            }else if($room_value->channel_id == 5){

                                $hotelbed_start = str_replace('-', '', $update_date);
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id))->row();
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
                                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                if($availability != "" && $room_value->update_availability=='1')
                                {
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
                                    $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'">'; 
                                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                               
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
                                        $this->inventory_model->store_error($room_value->channel_id,(string)$status->DetailedMessage,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                    }else if($xml->Status != "Y"){
                                        $this->inventory_model->store_error($room_value->channel_id,(string)$xml->Status,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error', "Try Again");
                                    }
                                }
                            }else if($room_value->channel_id == 8){
                                $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data('import_mapping_GTA',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                          
                                $room_id=$mp_details->ID;
                                $rateplanid=$mp_details->rateplan_id;
                                $MinPax=$mp_details->MinPax;
                                $peakrate=$mp_details->peakrate;
                                $MaxOccupancy=$mp_details->MaxOccupancy;
                                $minnights=$mp_details->minnights;
                                $payfullperiod=$mp_details->payfullperiod;
                                                                            
                                $contract_id=$mp_details->contract_id;  

                                $hotel_channel_id=$mp_details->hotel_channel_id;
                               
                                $soapUrl= trim($gta['uavail']);
                                if($availability != "" && $room_value->update_availability=='1')
                                {
                                    $xml_post_string='<GTA_InventoryUpdateRQ
                                            xmlns = "http://www.gta-travel.com/GTA/2012/05"
                                            xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                            xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05
                                            GTA_InventoryUpdateRQhelp.xsd">
                                            <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                                    $xml_post_string .='<StayDate Date = "'.$update_date.'">
                                            <Inventory RoomId="'.$room_id.'" >
                                            <Detail InventoryType = "Flexible" Quantity="'.$availability.'" FreeSale = "false" ReleaseDays = "0"/></Inventory>
                                            </StayDate> ';
                                    $xml_post_string .='</RoomStyle>
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
                                        $this->inventory_model->store_error($room_value->channel_id,(string)$Error_Array,'Import Availabilities',date('m/d/Y h:i:s a', time()));
                                    }
                                }

                            }
                            else if($room_value->channel_id == 2){

                                if($availability != "" && $room_value->update_availability=='1'){                                
                                    $this->booking_model->update_availability($sep_date,$room_value->import_mapping_id,$availability);
                                }
                            }
                        }
                        /*if($manual == ""){
                            $ch_data['channel_id'] = $con_ch;
                            $this->update_channel_calendar($owner_id,$hotel_id,$ch_data,$availability,$sep_date,'availability');
                        }*/
                /*    }
                }                
            }
        }
        return true;
    }*/

    /*function update_channel_calendar($owner_id,$hotel_id,$channel,$value,$date,$type)
    {
        extract($channel);
        
        if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0'){

            $update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));

            $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
            
            $ch_update_data['availability']=$value;
            if($type != ""){
                $ch_update_data['trigger_cal']=1;
            }
            
            if(count($available_update_details)!=0)
            {
                $adiff = 0;
                $opr = 'add';
                $old_avail = $available_update_details['availability'];

                if($value > $old_avail){
                    $adiff = $value - $old_avail;
                    $opr = 'add';
                    $value = $old_avail + $adiff;
                }
                else if($value < $old_avail){
                    $adiff = $old_avail - $value;
                    $opr = 'sub';
                    $value = $old_avail - $adiff;
                }
                if($adiff != 0)
                {
                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                    $ch_update_data['availability']=$value;
                    if($type != ""){ 
                        
                        $this->update_channel($owner_id,$hotel_id,$ch_update_data,$channel,$date);
                        $this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);

                        $this->db->query('call UpdateAvailabilityInMainTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")'); 
                        
                    }
                }
            }
           
        }
        // *** End Of TBL UPDATE *** //
        // *** Reservation TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

            $ch_update_data_RESERV['availability'] = $value;
            if($type != ""){
                $ch_update_data_RESERV['trigger_cal'] = 1;
            }

            $update_details_RESERV = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RESERV)!=0)
            {
                $old_avail = $available_update_details_RESERV->availability;
                $adiff = 0;
                $opr = 'add';
                if($value > $old_avail){
                    $adiff = $value - $old_avail;
                    $opr = "add";
                    $value = $old_avail + $adiff;
                }
                else if($value < $old_avail){
                    $adiff = $old_avail - $value;
                    $opr = "sub";
                    $value = $old_avail - $adiff;
                }
                if($adiff != 0){
                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));

                    $ch_update_data_RESERV['availability'] = $value;
                    if($type != ""){
                        
                        $this->db->query('call UpdateAvailabilityInSubTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');

                        $this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        $this->update_channel($owner_id,$hotel_id,$ch_update_data_RESERV,$channel,$date);
                    }
                }                
            }                           
            
        }
        // *** End Of Reservation TBL UPDATE *** //
        // *** Base Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){

            $ch_update_data_RATE_BASE['availability']=$value;

            if($type != "")
            {
                $ch_update_data_RATE_BASE['trigger_cal']=1;
            }

            $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();

            $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();

            
            if(count($available_update_details_RATE_BASE)!=0)
            {
                $adiff = 0;
                $opr = 'add';
                $old_avail = $available_update_details_RATE_BASE['availability'];
                if($value > $old_avail){
                    $adiff = $value - $old_avail;
                    $opr = "add";
                    $value = $old_avail + $adiff;
                }
                else if($value < $old_avail){
                    $adiff = $old_avail - $value;
                    $opr = "sub";
                    $value = $old_avail - $adiff;
                }

                if($adiff != 0){
                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                    $ch_update_data_RATE_BASE['availability']=$value;
                    
                    if($type != ""){
                        $this->db->query('call UpdateAvailabilityInRateBaseTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');

                        $this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_BASE,$channel,$date);
                    }
                }
            }
        }
        // *** End Of Base Rate TBL UPDATE *** //
        // *** Additional Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

            $ch_update_data_RATE_ADD['availability'] = $value;
            if($type != "")
            {
                $ch_update_data_RATE_ADD['trigger_cal'] = 1;
            }

            $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RATE_ADD)!=0)
            {
                $adiff = 0;
                $opr = 'add';
                $old_avail = $available_update_details_RATE_ADD->availability;
                if($value > $old_avail){
                    $adiff = $value - $old_avail;
                    $opr = "add";
                    $value = $old_avail + $adiff;
                }
                else if($value < $old_avail){
                    $adiff = $old_avail - $value;
                    $opr = "sub";
                    $value = $old_avail + $adiff;
                }
                if($adiff != 0){
                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                    $ch_update_data_RATE_ADD['availability'] = $value;
                    if($type != ""){                        
                        $this->db->query('call UpdateAvailabilityInRateAddTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                        $this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_ADD,$channel,$date);                    
                    }
                }
            }
        }
        // *** End OF Additional Rate TBL UPDATE *** //
        return true;
    }*/

    

    function update_channel_calendar($owner_id,$hotel_id,$channel,$value,$date,$type)
    {
        extract($channel);
       /*  echo '<pre>';
		print_r($channel); 
		echo $value; */

        if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0')
		{

            $update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date));

            $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
            
            $ch_update_data['availability']=$value;
            if($type != ""){
                $ch_update_data['trigger_cal']=1;
            }
            
            if(count($available_update_details)!=0)
            {
                if($type != "")
                {
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details['availability'];

                    if($value > $old_avail){
                        $adiff = $value - $old_avail;
                        $opr = '+';
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail){
                        $adiff = $old_avail - $value;
                        $opr = '-';
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0)
                    {
                        update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'channel_id'=>$channel_id),'mapping_id')->row()->mapping_id;
    					
    					//echo $this->db->last_query();
    					
                        $ch_update_data['availability']=$value;
    					
                        if($type != "")
    					{ 
                            if($opr == '-')
    						{
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('room_update_id !=',$available_update_details['room_update_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(TBL_UPDATE);
                            //echo $this->db->last_query();
							 if ($channel->channel_id == 1){								 
	                             makeLog('expediaResv_RespLog_'.$hotel_id.'.txt',"Before update_channel PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 } else if ($channel->channel_id == 2){
								 
	                             makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"Before update_channel PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 }
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data,$channel,$date,$mapping_id);
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        }
                    }
                }
				else
				{
                    update_data(TBL_UPDATE,$ch_update_data,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                }
            }
			else
			{
				$ch_update_data['owner_id']					=	$owner_id;
				$ch_update_data['hotel_id']					=	$hotel_id;
				$ch_update_data['room_id']					=	$property_id;
				$ch_update_data['individual_channel_id']	=	$channel_id;
				$ch_update_data['availability']				=	$value;
				$ch_update_data['separate_date']			=	$date;
				insert_data(TBL_UPDATE,$ch_update_data);
			}
           
        }
        // *** End Of TBL UPDATE *** //
        // *** Reservation TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id=='0' && $guest_count!='0' && $refun_type!='0'){

            $ch_update_data_RESERV['availability'] = $value;
            if($type != ""){
                $ch_update_data_RESERV['trigger_cal'] = 1;
            }

            $update_details_RESERV = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            $available_update_details_RESERV = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RESERV)!=0)
            {
                if($type != "")
                {
                    $old_avail = $available_update_details_RESERV->availability;
                    $adiff = 0;
                    $opr = '+';
                    if($value > $old_avail){
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail){
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0){
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'channel_id'=>$channel_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type),'mapping_id')->row()->mapping_id;

                        update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                        $ch_update_data_RESERV['availability'] = $value;
                        if($type != "")
    					{

                            if($opr == '-'){
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('reserv_price_id !=',$available_update_details_RESERV['reserv_price_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RESERV);
                           // echo $this->db->last_query();
                            //$this->db->query('call UpdateAvailabilityInSubTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
							if ($channel->channel_id == 1){								 
								makeLog('expediaResv_RespLog_'.$hotel_id.'.txt',"Before update_channel1 PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data_RESERV)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 } else if ($channel->channel_id == 2){
								makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"Before update_channel1 PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data_RESERV)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 }
							
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RESERV,$channel,$date,$mapping_id);
                        }
                    }
                }else{
                    update_data(RESERV,$ch_update_data_RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                }             
            }   
			else
			{
				$ch_update_data['owner_id']					=	$owner_id;
				$ch_update_data['hotel_id']					=	$hotel_id;
				$ch_update_data['room_id']					=	$property_id;
				$ch_update_data['individual_channel_id']	=	$channel_id;
				$ch_update_data['availability']				=	$value;
				$ch_update_data['separate_date']			=	$date;
				$ch_update_data['guest_count']				=	$guest_count;
				$ch_update_data['refun_type']				=	$refun_type;
				insert_data(RESERV,$ch_update_data);
			}                        
            
        }
        // *** End Of Reservation TBL UPDATE *** //
        // *** Base Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count=='0' && $refun_type=='0'){


            $ch_update_data_RATE_BASE['availability']=$value;

            if($type != "")
            {
                $ch_update_data_RATE_BASE['trigger_cal']=1;
            }

            $update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date))->row();
			//echo $date;
            $available_update_details_RATE_BASE = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
			/* echo $this->db->last_query();

            print_r($available_update_details_RATE_BASE);
			die; */
            if(count($available_update_details_RATE_BASE)!=0)
            {
                if($type != ""){
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details_RATE_BASE['availability'];
                    if($value > $old_avail){
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail){
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }

                    if($adiff != 0){
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'rate_id'=>$rate_id,'channel_id'=>$channel_id),'mapping_id')->row()->mapping_id;

                        update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                        $ch_update_data_RATE_BASE['availability']=$value;
                        
                        if($type != ""){
                            if($opr == '-'){
                                $this->db->where('availability !=',0);
                            }
                            //$this->db->query('call UpdateAvailabilityInRateBaseTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            $this->db->where('room_update_id !=',$available_update_details_RATE_BASE['room_update_id']);
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RATE_BASE);
                            //echo $this->db->last_query();

                             //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_BASE,$channel,$date,$mapping_id);
                        }
                    }
                }else{
                    update_data(RATE_BASE,$ch_update_data_RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                }
            }
			else
			{
				$ch_update_data['owner_id']					=	$owner_id;
				$ch_update_data['hotel_id']					=	$hotel_id;
				$ch_update_data['room_id']					=	$property_id;
				$ch_update_data['individual_channel_id']	=	$channel_id;
				$ch_update_data['availability']				=	$value;
				$ch_update_data['separate_date']			=	$date;
				$ch_update_data['rate_types_id']			=	$rate_id;
				insert_data(RATE_BASE,$ch_update_data);
			}
        }
        // *** End Of Base Rate TBL UPDATE *** //
        // *** Additional Rate TBL UPDATE *** //
        elseif($property_id!='0' && $rate_id!='0' && $guest_count!='0' && $refun_type!='0'){

            $ch_update_data_RATE_ADD['availability'] = $value;
            if($type != "")
            {
                $ch_update_data_RATE_ADD['trigger_cal'] = 1;
            }

            $update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>'0','separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            $available_update_details_RATE_ADD = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row();

            if(count($available_update_details_RATE_ADD)!=0)
            {
                if($type != ""){
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details_RATE_ADD->availability;
                    if($value > $old_avail){
                        $adiff = $value - $old_avail;
                        $opr = "+";
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail){
                        $adiff = $old_avail - $value;
                        $opr = "-";
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0){
                        $mapping_id = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id'=>$property_id,'rate_id'=>$rate_id,'channel_id'=>$channel_id,'guest_count'=>$guest_count,'refun_type'=>$refun_type),'mapping_id')->row()->mapping_id;
                        update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                        $ch_update_data_RATE_ADD['availability'] = $value;
                        if($type != ""){
                            if($opr == '-'){
                                $this->db->where('availability !=',0);
                            }
                            $this->db->where('reserv_price_id !=',$available_update_details_RATE_ADD['reserv_price_id']);   
                            $this->db->where('owner_id',$owner_id);
                            $this->db->where('hotel_id',$hotel_id);
                            $this->db->where('room_id',$property_id);
                            $this->db->where('separate_date',$date);
                            //$this->db->set('availability','availability'.$opr.$adiff,false);
                            $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                            $this->db->update(RATE_ADD);
                            //echo $this->db->last_query();                    
                            //$this->db->query('call UpdateAvailabilityInRateAddTable("'.$adiff.'","'.$owner_id.'","'.$hotel_id.'","'.$property_id.'","'.$date.'","'.$opr.'","'.$channel_id.'")');
                            //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
							if ($channel->channel_id == 1){								 
								makeLog('expediaResv_RespLog_'.$hotel_id.'.txt',"Before update_channel3 PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data_RATE_ADD)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 } else if ($channel->channel_id == 2){
								makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"Before update_channel3 PARAMS=>--".$owner_id."--".$hotel_id."--".json_encode($ch_update_data_RATE_ADD)."--".json_encode($channel)."--".$date."--".$mapping_id."---\n");
							 }
							
                            $this->update_channel($owner_id,$hotel_id,$ch_update_data_RATE_ADD,$channel,$date,$mapping_id);                           
                        }
                    }
                }else{
                    update_data(RATE_ADD,$ch_update_data_RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$property_id,'rate_types_id'=>$rate_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date,'guest_count'=>$guest_count,'refun_type'=>$refun_type));
                }
            }
			else
			{
				$ch_update_data['owner_id']					=	$owner_id;
				$ch_update_data['hotel_id']					=	$hotel_id;
				$ch_update_data['room_id']					=	$property_id;
				$ch_update_data['individual_channel_id']	=	$channel_id;
				$ch_update_data['availability']				=	$value;
				$ch_update_data['separate_date']			=	$date;
				$ch_update_data['rate_types_id']			=	$rate_id;
				$ch_update_data['guest_count']				=	$guest_count;
				$ch_update_data['refun_type']				=	$refun_type;
				insert_data(RATE_ADD,$ch_update_data);
			}
        }
        // *** End OF Additional Rate TBL UPDATE *** //
        return true;
    }

    function update_channel($owner_id,$hotel_id,$udata,$ch_data,$sep_date,$mapping_id,$manual="")
    {
        extract($udata);
        extract($ch_data);

        $stop_sale = false;
        $ch_id = $channel_id;
        /*$all_channel = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id))->result_array(); 
        if(count($all_channel) != "")
        {*/
            /*foreach($all_channel as $con_ch)
            {*/
            
                if(intval($availability) < 1){
                    $stop_sale = true;
                }

                if($mapping_id != ""){
                    $this->db->where('mapping_id !=',$mapping_id);
                }
                $this->db->where('channel_id !=',17);
                $this->db->where('owner_id',$owner_id);
                $this->db->where('hotel_id',$hotel_id);
                $this->db->where('property_id',$property_id);
                $this->db->where('enabled','enabled');
                $mapdetails = $this->db->get(MAP);
                $room_mapping = $mapdetails->result();
                /*$room_mapping = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch,'property_id'=>$property_id,'enabled'=>'enabled'))->result();*/
                if($room_mapping){

                    foreach($room_mapping as $room_value)
                    {
                        if($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
                        {
                            $available_update_details = get_data(TBL_UPDATE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date))->row_array();
                        }
                        elseif($room_value->property_id !='0' && $room_value->rate_id =='0' && $room_value->guest_count!='0' && $room_value->refun_type !='0')
                        {
                            $available_update_details = get_data(RESERV,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row_array(); 
                        }
                        elseif($room_value->property_id !='0' && $room_value->rate_id !='0' && $room_value->guest_count =='0' && $room_value->refun_type=='0')
                        {
                            $available_update_details = get_data(RATE_BASE,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date))->row_array();
                        }
                        elseif($room_value->property_id !='0' && $room_value->rate_id !='0' && $room_value->guest_count !='0' && $room_value->refun_type !='0')
                        {
                            $available_update_details = get_data(RATE_ADD,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'room_id'=>$room_value->property_id,'rate_types_id'=>$room_value->rate_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date,'guest_count'=>$room_value->guest_count,'refun_type'=>$room_value->refun_type))->row_array();
                        }
                        if(count($available_update_details) != 0){
                            $availability = $available_update_details['availability'];
                            $con_ch = $room_value->channel_id;
                            if($room_value->channel_id == 1){
                                $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data('import_mapping',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                if($availability != "" && $room_value->update_availability=='1'){
                                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                            <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                            <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                            <AvailRateUpdate>';
                                    $xml .= '<DateRange from="'.$update_date.'" to="'.$update_date.'"/>';
                                    $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                                    
                                    $xml .= '<Inventory totalInventoryAvailable="'.$availability.'"/>';

                                    if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                        $plan_id = $mp_details->rateplan_id;
                                    }else{
                                        $plan_id = $mp_details->rate_type_id;
                                    }
                                    if($stop_sale){
                                        $xml .= '<RatePlan id="'.$plan_id.'" closed = "true">';
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
                                    $response = @$data->Error;
                                    //echo $response;
                                    if($response!='')
                                    {
                                       // echo 'fail';
                                        $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$response,'update_channel',date('m/d/Y h:i:s a', time()));
                                        $expedia_update = "Failed";
                                    }
                                    else
                                    {
                                       // echo 'success   ';
                                        $expedia_update = "Success";
                                    }
                                    curl_close($ch);
                                }
                            }else if($room_value->channel_id == 11){
                                
                                $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id))->row();
                                if($availability!='' && $room_value->update_availability=='1')
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
                                    <Availability>='.$availability.'</Availability>
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
                                            $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Errorarray['WARNING'],'update_channel',date('m/d/Y h:i:s a', time()));
                                            $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                        }
                                        else if(count($soapFault)!='0')
                                        {     
                                            $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$soapFault['soapText'],'update_channel',date('m/d/Y h:i:s a', time())); 
                                            $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                        }
                                        //return false;
                                    }
                                    curl_close($ch);
                                } 
                            }else if($room_value->channel_id == 5){

                                $hotelbed_start = str_replace('-', '', $update_date);
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id))->row();
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
                                $mp_details = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'map_id'=>$room_value->import_mapping_id))->row();
                                if($availability != "" && $room_value->update_availability=='1')
                                {
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
                                        if($stop_sale){
                                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'" closed="Y">'; 
                                        }else{
                                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'" closed="N">'; 
                                        }
                                    
                                        $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                               
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
                                        $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$status->DetailedMessage,'update_channel',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                                    }else if($xml->Status != "Y"){
                                        $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$xml->Status,'update_channel',date('m/d/Y h:i:s a', time()));
                                        $this->session->set_flashdata('bulk_error', "Try Again");
                                    }
                                }
                            }else if($room_value->channel_id == 8){
                                $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                                $mp_details = get_data('import_mapping_GTA',array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                          
                                $room_id=$mp_details->Id;
                                $rateplanid=$mp_details->rateplan_id;
                                $MinPax=$mp_details->MinPax;
                                $peakrate=$mp_details->peakrate;
                                $MaxOccupancy=$mp_details->MaxOccupancy;
                                $minnights=$mp_details->minnights;
                                $payfullperiod=$mp_details->payfullperiod;
                                                                            
                                $contract_id=$mp_details->contract_id;  

                                $hotel_channel_id=$mp_details->hotel_channel_id;
                               
                                $soapUrl= trim($gta['uavail']);
                                if($availability != "" && $room_value->update_availability=='1')
                                {
                                    $xml_post_string='<GTA_InventoryUpdateRQ
                                            xmlns = "http://www.gta-travel.com/GTA/2012/05"
                                            xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                            xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05
                                            GTA_InventoryUpdateRQhelp.xsd">
                                            <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                                    $xml_post_string .='<StayDate Date = "'.$update_date.'">
                                            <Inventory RoomId="'.$room_id.'" >
                                            <Detail InventoryType = "Flexible" Quantity="'.$availability.'" FreeSale = "false" ReleaseDays = "0"/>';
                                            if($stop_sale){
                                                $xml_post_string.= '<Restriction FlexibleStopSell = "true" InventoryType = "Flexible"/>';
                                            }
                                    $xml_post_string .='</Inventory>
                                            </StayDate> ';
                                    $xml_post_string .='</RoomStyle>
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
                                        $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'update_channel',date('m/d/Y h:i:s a', time()));
                                    }
                                }
    	
                            } else if($room_value->channel_id == 9){
								if($availability != "" && $room_value->update_availability=='1'){ 
									$price = '';
									$product['start_date'] = $sep_date;
									$product['end_date'] = $sep_date;
									$product['days'] = '1,2,3,4,5,6,7';
									$product['price']	= '';
									$product['availability'] = $availability;
									$product['stop_sell'] = '';
									$product['open_room'] = '';
									$this->load->model("airbnb_model");
									$this->airbnb_model->bulk_update($product, $room_value->import_mapping_id, 0, $price);
								}
							} else if($room_value->channel_id == 2){

                                if($availability != "" && $room_value->update_availability=='1'){                       makeLog('bookingResv_RespLog_'.$hotel_id.'.txt',"Before update_availability PARAMS=>--".$owner_id."--".$hotel_id."--".$sep_date."--".$room_value->import_mapping_id."--".$availability."---\n");   
                                                                
                                    $this->booking_model->update_availability($owner_id,$hotel_id,$sep_date,$room_value->import_mapping_id,$availability);
                                }
                            }
                        }
                    }
                    /*if($manual == ""){
                        $ch_data['channel_id'] = $con_ch;
                        $this->update_channel_calendar($owner_id,$hotel_id,$ch_data,$availability,$sep_date,'availability');
                    }*/
                }
				
				$this->bnow_model->updateAvailability($owner_id,$hotel_id,$property_id,$sep_date);
				
                           
            //}
        /*}*/
        return true;
    }
	
	function delete_duplicate_record()
	{
		/*
		$main_query = $this->db->query("SELECT u.room_update_id, u.owner_id, u.hotel_id, u.room_id, u.individual_channel_id, u.separate_date FROM room_update u INNER JOIN ( SELECT MIN(room_update_id) as min_id ,room_id, individual_channel_id , separate_date, COUNT(*) FROM room_update GROUP BY room_id ,individual_channel_id, separate_date HAVING COUNT(*) > 1) temp ON temp.separate_date = u.separate_date AND temp.individual_channel_id = u.individual_channel_id AND temp.room_id = u.room_id and room_update_id!=min_id ORDER BY separate_date, individual_channel_id , room_id ,room_update_id");
		
		if($main_query)
		{
			$main_result = $main_query->result_array();
			if($main_result)
			{
				$i=1;
				foreach($main_result as $main_value)
				{
					extract($main_value);
					echo $i++.' = Main_room_update_id = '.$room_update_id.'<br>';
					delete_data('room_update',array('room_update_id'=>$room_update_id));
				}
			}
		}
		
		$main_subquery = $this->db->query("SELECT u.reserv_price_id, u.owner_id, u.hotel_id, u.room_id, u.individual_channel_id, u.separate_date, u.guest_count,u.refun_type FROM reservation_table u INNER JOIN ( SELECT MIN(reserv_price_id) as min_id ,room_id, individual_channel_id , separate_date, guest_count, refun_type, COUNT(*) FROM reservation_table GROUP BY room_id ,individual_channel_id, separate_date HAVING COUNT(*) > 1) temp ON temp.separate_date = u.separate_date AND temp.individual_channel_id = u.individual_channel_id AND temp.room_id = u.room_id AND temp.guest_count = u.guest_count AND temp.refun_type = u.refun_type and reserv_price_id!=min_id ORDER BY separate_date, individual_channel_id , room_id ,reserv_price_id");
		
		if($main_subquery)
		{
			$main_sub_result = $main_subquery->result_array();
			if($main_sub_result)
			{
				$i=1;
				foreach($main_sub_result as $main_sub_value)
				{
					extract($main_sub_value);
					echo $i++.' = Main_sub_room_update_id = '.$reserv_price_id.'<br>';
					delete_data('reservation_table',array('reserv_price_id'=>$reserv_price_id));
				}
			}
		}
		
		$main_rate_query = $this->db->query("SELECT u.room_update_id, u.owner_id, u.hotel_id, u.room_id, u.individual_channel_id, u.separate_date,u.rate_types_id FROM room_rate_types_base u INNER JOIN ( SELECT MIN(room_update_id) as min_id ,room_id, individual_channel_id , separate_date,rate_types_id, COUNT(*) FROM room_rate_types_base GROUP BY room_id ,individual_channel_id, separate_date HAVING COUNT(*) > 1) temp ON temp.separate_date = u.separate_date AND temp.individual_channel_id = u.individual_channel_id AND temp.room_id = u.room_id AND temp.rate_types_id = u.rate_types_id  and room_update_id!=min_id ORDER BY separate_date, individual_channel_id , room_id ,room_update_id");
		
		if($main_rate_query)
		{
			$main_rate_result = $main_rate_query->result_array();
			if($main_rate_result)
			{
				$i=1;
				foreach($main_rate_result as $main_rate_value)
				{
					extract($main_rate_value);
					echo $i++.' = Main_rate_room_update_id = '.$room_update_id.'<br>';
					delete_data('room_rate_types_base',array('room_update_id'=>$room_update_id));
				}
			}
		}

		$main_rate_sub_query = $this->db->query("SELECT u.reserv_price_id, u.owner_id, u.hotel_id, u.room_id, u.individual_channel_id, u.separate_date, u.rate_types_id,u.guest_count,u.refun_type FROM room_rate_types_additional u INNER JOIN ( SELECT MIN(reserv_price_id) as min_id ,room_id, individual_channel_id , separate_date, rate_types_id, guest_count, refun_type, COUNT(*) FROM room_rate_types_additional GROUP BY room_id ,individual_channel_id, separate_date HAVING COUNT(*) > 1) temp ON temp.separate_date = u.separate_date AND temp.individual_channel_id = u.individual_channel_id AND temp.room_id = u.room_id AND temp.rate_types_id = u.rate_types_id AND temp.guest_count = u.guest_count AND temp.refun_type = u.refun_type and reserv_price_id!=min_id ORDER BY separate_date, individual_channel_id , room_id ,reserv_price_id");
		
		if($main_rate_sub_query)
		{
			$main_rate_sub_result = $main_rate_sub_query->result_array();
			if($main_rate_sub_result)
			{
				$i=1;
				foreach($main_rate_sub_result as $main_rate_sub_value)
				{
					extract($main_rate_sub_value);
					echo $i++.' = Main_rate_sub_room_update_id = '.$reserv_price_id.'<br>';
					delete_data('room_rate_types_additional',array('reserv_price_id'=>$reserv_price_id));
				}
			}
		}
		*/
	}

    function callproc(){
        $this->db->query('CALL  UpdateAvailabilityInMain("'.TBL_UPDATE.'",50,11,11,50,"02/07/2016")');
    }

    function getOverBooking(){

        $this->db->where('hotel_id !=', '11');
        $this->db->where('owner_id !=', '11');
        $hoteldetails = get_data(HOTEL)->result_array();
        foreach($hoteldetails as $hotel){
            $user_id = $hotel['owner_id'];
            $hotel_id = $hotel['hotel_id'];
            $overbook = $this->mapping_model->getOverBooking($user_id,$hotel_id);
            
            if(count($overbook) != 0){

                uasort($overbook,function($a,$b)
                {
                    return strtotime($a->separate_date)>strtotime($b->separate_date)?1:-1;
                });
                $data['user_id'] = $hotel['owner_id'];
                $data['hotel_id'] = $hotel['hotel_id'];
                $data['sendto'] = 1;
                $data['type'] = 1;
                $content = "";
                foreach($overbook as $ovrbook){
                    $roomname = get_data(TBL_PROPERTY,array('property_id' => $ovrbook->room_id))->row()->property_name;
                    $roomrecord[$roomname][] = $ovrbook->separate_date;
                    /*$content .= $ovrbook->separate_date.',';
                    $cstatus['status'] = 0;*/
                    //update_data("overbook",$cstatus,array('id'=>$ovrbook->id));
                }
                foreach($roomrecord as $room => $dates){
                    $content .= "<b>".$room ."</b>-";
                    $date_content = "";
                    foreach ($dates as $date) {
                        $date_content .= $date.',';
                    }                    
                    $content .= rtrim($date_content,',');
                    $content .= "<br>";
                }
                $data['content'] = "<b>You have Overbooking at the following date(s)</b>,<br><br>".rtrim($content,',');
                $data['created_date'] = date("Y-m-d H:i:s");
                $data['status'] = "unseen";
                $data['expire_alert'] = 2;
                $data['title']  = 'You have Overbooking';

                $notf = get_data("notifications", array('expire_alert'=>2, 'user_id'=>$hotel['owner_id'], 'hotel_id'=>$hotel['hotel_id']))->num_rows;
                if($notf == 0){
                    $this->db->insert("notifications",$data);
                }else{
                    update_data("notifications",$data,array('expire_alert'=>2, 'user_id'=>$hotel['owner_id'], 'hotel_id'=>$hotel['hotel_id']));
                }
            }else{
                $notf = get_data("notifications", array('expire_alert'=>2, 'user_id'=>$hotel['owner_id'], 'hotel_id'=>$hotel['hotel_id']))->num_rows;
                if($notf != 0){
                    $this->db->where("expire_alert",2);
                    $this->db->where("user_id",$hotel['owner_id']);
                    $this->db->where("hotel_id", $hotel['hotel_id']);
                    $this->db->delete("notifications");
                }
            }
        }
    }

    function importAvailabilities_pms($owner_id,$hotel_id,$channel_id,$propertyid,$rate_id,$guest_count,$refun_type,$arrival = "",$departure = "",$mappingid = "",$mapping='')
    {
        $property_id = insep_decode($propertyid);

        if($mappingid != "")
        {
            $importDetails = get_data(PMS_MAP,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type,'import_mapping_id'=>$mappingid))->row_array();
        }
        else
        {
            $importDetails = get_data(PMS_MAP,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>insep_decode($propertyid),'rate_id'=>$rate_id,'channel_id'=>insep_decode($channel_id),'guest_count'=>$guest_count,'refun_type'=>$refun_type))->row_array();
        }
        
        if($arrival != "" && $departure != "")
        {
            $start_date = date('d.m.Y',strtotime($arrival));
            $end_date = date('d.m.Y',strtotime($departure));
            $exp_start_date = $arrival;
            $exp_end_date = $departure;
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y',strtotime(str_replace('-', '/', $arrival)));
            $end = date('d/m/Y', strtotime(str_replace('-', '/', $departure)));
        }
        else
        {
            $start_date = date('d.m.Y');
            $end_date = date('d.m.Y', strtotime("+30 days"));
            $exp_start_date = date('Y-m-d');
            $exp_end_date = date('Y-m-d', strtotime("+30 days"));
            $hotelbed_start_date = str_replace('-', '', $exp_start_date);
            $hotelbed_end_date = str_replace('-', '', $exp_end_date);
            $start = date('d/m/Y');
            $end = date('d/m/Y', strtotime("+30 days"));
        }
        $channel['channel_id'] = insep_decode($channel_id);
        $channel['property_id'] = $property_id;
        $channel['rate_id'] = $rate_id;
        $channel['guest_count'] = $guest_count;
        $channel['refun_type'] = $refun_type;
        $channel['start'] = $start;
        $channel['end'] = $end;
        
        if(count($importDetails)!=0)
        {
            if($importDetails['channel_id'] == 11)
            {
                $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
                if($ch_details->mode == 0)
                {
                    $urls = explode(',', $ch_details->test_url);
                    foreach($urls as $url)
                    {
                    $path = explode("~",$url);
                    $reco[$path[0]] = $path[1];
                    }
                }
                else if($ch_details->mode == 1)
                {
                    $urls = explode(',', $ch_details->live_url);
                    foreach($urls as $url)
                    {
                        $path = explode("~",$url);
                        $reco[$path[0]] = $path[1];
                    }
                } 
                
                $mp_details = get_data(PMS_RECO,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'re_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
            
                $url = trim($reco['irate_avail']);
            
                $xml_rate = '<?xml version="1.0" encoding="utf-8"?>
                            <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                            <soap12:Body>
                            <GetAvail xmlns="https://www.reconline.com/">
                            <User>'.$ch_details->user_name.'</User>
                            <Password>'.$ch_details->user_password.'</Password>
                            <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
                            <idSystem>0</idSystem>
                            <ForeignPropCode></ForeignPropCode>
                            <IncludeRateLevels>'.$mp_details->RLCODE.'</IncludeRateLevels>
                            <ExcludeRateLevels></ExcludeRateLevels>
                            <IncludeRoomTypes>'.$mp_details->CODE.'</IncludeRoomTypes>
                            <ExcludeRoomTypes></ExcludeRoomTypes>
                            <StartDate>'.$start_date.'</StartDate>
                            <EndDate>'.$end_date.'</EndDate>
                            </GetAvail>
                            </soap12:Body>
                            </soap12:Envelope>
                        ';
            $headers = array(
                                "Content-type: application/soap+xml; charset=utf-8",
                                "Host:www.reconline.com",
                                "Content-length: ".strlen($xml_rate),
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_rate);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $ss = curl_getinfo($ch);    
            $response = curl_exec($ch);
            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
            $xml = simplexml_load_string($xml);
            $json = json_encode($xml);
            $responseArray = json_decode($json,true);
            $getAvail = $responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Availability']['AVAIL']; 
            $Errorarray = @$responseArray['soapBody']['GetAvailResponse']['GetAvailResult']['diffgrdiffgram']['NewDataSet']['Warning'];
            $soapFault =  @$responseArray['soapBody']['soapFault']['soapReason'];
            if(count($Errorarray)=='0' && count($soapFault)=='0')
            {
                $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                $reconline_availability_response="success";
            }
            else 
            {
                $reconline_availability_response="error";
                if(count($Errorarray)!='0')
                {
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$Errorarray['WARNING'],'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error',$Errorarray['WARNING']);
                }
                else if(count($soapFault)!='0')
                {   
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$soapFault['soapText'],'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));   
                    $this->session->set_flashdata('import_rate_error',$soapFault['soapText']);
                }
                return false;
            } 
            curl_close($ch);
            if($reconline_availability_response == "success")
            {
                $avail = explode(":", $getAvail);   
                $ab_value = explode(",", $avail[1]);
                foreach ($ab_value as $key => $value) 
                {
                    $date = date('d/m/Y', strtotime("+".$key." days"));
                    $this->update_channel_calendar_pms($owner_id,$hotel_id,$channel,$value,$date,$mapping);
                }
            }
            }
            else if($importDetails['channel_id'] == 1)
            {
                $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data(PMS_EXP,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel'=>$importDetails['channel_id'],'exp_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                
                $url = trim($exp['irate_avail']);
        
                $xml_data = '<ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">
                        <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                        <Hotel id="'.$ch_details->hotel_channel_id.'"/>
                        <ParamSet>
                        <AvailRateRetrieval from="'.$exp_start_date.'" to="'.$exp_end_date.'">
                        <RoomType id="'.$mp_details->roomtype_id.'" >';
                        
                $xml_data .= '<RatePlan id="'.$mp_details->rate_type_id.'"/>';
                $xml_data .= '</RoomType>
                </AvailRateRetrieval>
                </ParamSet>
                </ProductAvailRateRetrievalRQ>';
        
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                //echo $output;
                $data = simplexml_load_string($output); 
                //echo '<pre>';
                //print_r($data);
                $response = $data->Error;
                $avail = $data->AvailRateList->AvailRate;
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($response!='')
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$response,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                    $expedia_availability_response = "error";
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Reconline!!!');
                    $expedia_availability_response="success";
                }
        
                if($expedia_availability_response == "success"){
        
                    foreach($avail as $data){
                        
                        $date = $data->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime(str_replace('-','/',$date)));
                        $availability =  $data->RoomType->Inventory->attributes()->totalInventoryAvailable;
                        $this->update_channel_calendar_pms($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 5)
            {
                $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();   
        
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
                $mp_details = get_data(PMS_HBD_ROOM,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'htb_id'=>$importDetails['import_mapping_id']))->row();
        
                $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
                            <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                            <getHSIContractInventory xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                            <HSI_ContractInventoryRQ>
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
                            <DateFrom date="'.$hotelbed_start_date.'"/>
                            <DateTo date="'.$hotelbed_end_date.'"/>
                            </HSI_ContractInventoryRQ>
                            </getHSIContractInventory>
                            </soapenv:Body>
                            </soapenv:Envelope>';
        
                $headers = array(
                "SOAPAction:no-action",
                "Content-length: ".strlen($xml_data),
                ); 
                $url = trim($htb['irate_avail']);
        
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
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $ss = curl_getinfo($ch);                
                $response = curl_exec($ch);
                
                //echo '<pre>';
                //echo $response;
                
                $xmlreplace = preg_replace("/(<\/?)(\w+):([^>]*>)/",'$1$2$3',$response);
                $xml_parse = simplexml_load_string($xmlreplace);
                $json = json_encode($xml_parse);
                $responseArray = json_decode($json,true);
                $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractInventory']);
        
                //print_r($avail);
                //  print_r($avail->RoomType->Inventory->attributes());
                
                if($xml->ErrorList->Error)
                {
                    //echo $response;
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$xml->ErrorList->Error->DetailedMessage,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    //return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From Hotelbeds!!!');
                    $hotelbed_availability_response="success";
                }
        
                if($hotelbed_availability_response == "success"){
        
                    foreach($xml->InventoryItem as $dataval){
                        
                        $date = $dataval->DateFrom->attributes()->date;
                        $sep_date = date('d/m/Y',strtotime($date));
                        foreach ($dataval->Room as $details) {
                        //print_r($detail_s->RoomType);
                            foreach($details->RoomType as $room_name)
                            if($room_name->attributes()->code == $mp_details->roomname && $room_name->attributes()->characteristic == $mp_details->characterstics){
        
                                $availability =  $details->attributes()->available;
                                $this->update_channel_calendar_pms($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                            }
                        }                       
        
                    }
                }
            }
            else if($importDetails['channel_id'] == 8)
            {
                
                $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id']))->row();
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
                $mp_details = get_data(PMS_GTA,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$importDetails['channel_id'],'gta_id'=>$importDetails['import_mapping_id'],'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
        
                 $room_id=$mp_details->Id;
                 $rateplanid=$mp_details->rateplan_id;
                 $MinPax=$mp_details->MinPax;
                 $peakrate=$mp_details->peakrate;
                 $MaxOccupancy=$mp_details->MaxOccupancy;
                 $minnights=$mp_details->minnights;
                 $payfullperiod=$mp_details->payfullperiod;
                 $contract_id=$mp_details->contract_id; 
                 $hotel_channel_id=$mp_details->hotel_channel_id;
                 $contract_type = $mp_details->contract_type;
        
                $soapUrl = trim($gta['iavail']);
                $xml_post_string = '<GTA_InventoryReadRQ xmlns = "http://www.gta-travel.com/GTA/2012/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"   xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05 TA_InventoryReadRQ.xsd">
                     <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                     <Inventory ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'" FromDate = "'.$exp_start_date.'" ToDate = "'.$exp_end_date.'" DaysOfWeek = "1111111"/>
                    <RoomTypes>
                    <RoomType RoomId = "'.$room_id.'"/>
                    </RoomTypes>
                    </GTA_InventoryReadRQ>
                    ';
        
                $ch = curl_init($soapUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                $resdata = simplexml_load_string($output);
                //echo "<pre>";
                //print_r($resdata);
               
                if(isset($resdata->Errors->Error))
                {
                    $this->inventory_model->store_error($owner_id,$hotel_id,$importDetails['channel_id'],(string)$resdata->Errors->Error,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                    $this->session->set_flashdata('import_rate_error', 'Try Again!..');
                    return false;
                }
                else
                {
                    $this->session->set_flashdata('import_success','Successfully Imported Room Availability From GTA!!!');
                    $gta_availability_response="success";
                }
                if($gta_availability_response == "success"){
                    $availabilities = $resdata->InventoryBlock->StayDate;
                    foreach ($availabilities as $avail) {
                        $sep_date = date("d/m/Y", strtotime($avail->attributes()->Date));
                        $availability = $avail->Inventory->InventoryDetail->attributes()->TotalQuantityAvailable;
                        $this->update_channel_calendar_pms($owner_id,$hotel_id,$channel,$availability,$sep_date,$mapping);
                    }
                }
            }
            else if($importDetails['channel_id'] == 2)
            {
                
                $this->booking_model->importAvailabilities_pms($owner_id,$hotel_id,$channel,$mapping,$importDetails['import_mapping_id'],$arrival,$departure);
            }
            return true;
        }
        else
        {
            return true;
        }
    }

    function update_channel_calendar_pms($owner_id,$hotel_id,$channel,$value,$date,$type)
    {
        extract($channel);
        
        if($property_id!='0' && $rate_id=='0' && $guest_count=='0' && $refun_type=='0')
        {
            $available_update_details = get_data(PMS_UPDATE,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date))->row_array();
            //echo $this->db->last_query();
            
            $ch_update_data['availability']=$value;
            /*if($type != ""){
                $ch_update_data['trigger_cal']=1;
            }
            */
            if(count($available_update_details)!=0)
            {
                if($type != "")
                {
                    $adiff = 0;
                    $opr = '+';
                    $old_avail = $available_update_details['availability'];
                    if($value > $old_avail){
                        $adiff = $value - $old_avail;
                        $opr = '+';
                        $value = $old_avail + $adiff;
                    }
                    else if($value < $old_avail){
                        $adiff = $old_avail - $value;
                        $opr = '-';
                        $value = $old_avail - $adiff;
                    }
                    if($adiff != 0)
                    {
                        update_data(PMS_UPDATE,$ch_update_data,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                        $mapping_id = get_data(PMS_MAP,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>$property_id,'channel_id'=>$channel_id),'map_id')->row()->map_id;
                        $ch_update_data['availability']=$value;
                        
                        if($opr == '-')
                        {
                            $this->db->where('availability !=',0);
                        }
                        $this->db->where('room_update_id !=',$available_update_details['room_update_id']);
                        $this->db->where('partner_id',$owner_id);
                        $this->db->where('property_id',$hotel_id);
                        $this->db->where('room_id',$property_id);
                        $this->db->where('separate_date',$date);
                        //$this->db->set('availability','availability'.$opr.$adiff,false);
                        $this->db->set('availability','CASE WHEN availability'.$opr.$adiff.' >=0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$adiff.' WHEN availability'.$opr.$adiff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
                        $this->db->update(PMS_UPDATE);
                        //echo $this->db->last_query();
                        
                        $this->update_channel_pms($owner_id,$hotel_id,$ch_update_data,$channel,$date,$mapping_id);
                        //$this->update_subrooms($value,$channel,$date,$owner_id,$hotel_id);
                        
                    }
                }else{
                    update_data(PMS_UPDATE,$ch_update_data,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>$property_id,'individual_channel_id'=>$channel_id,'separate_date'=>$date));
                }
            }
           
        }
       
        return true;
    }

    function update_channel_pms($owner_id,$hotel_id,$udata,$ch_data,$sep_date,$mapping_id,$manual="")
    {
        extract($udata);
        extract($ch_data);

        $ch_id = $channel_id;
        
        if($mapping_id != ""){
            $this->db->where('map_id !=',$mapping_id);
        }
        $this->db->where('partner_id',$owner_id);
        $this->db->where('property_id',$hotel_id);
        $this->db->where('room_id',$property_id);
        $this->db->where('status','enabled');
        $mapdetails = $this->db->get(PMS_MAP);
        $room_mapping = $mapdetails->result();
        /*$room_mapping = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch,'property_id'=>$property_id,'enabled'=>'enabled'))->result();*/
        if($room_mapping){

            foreach($room_mapping as $room_value)
            {
                $available_update_details = array();
                if($room_value->property_id!='0' && $room_value->rate_id=='0' && $room_value->guest_count=='0' && $room_value->refun_type=='0')
                {
                    $available_update_details = get_data(PMS_UPDATE,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'room_id'=>$room_value->property_id,'individual_channel_id'=>$room_value->channel_id,'separate_date'=>$sep_date))->row_array();
                }
                if(count($available_update_details) != 0)
                {
                    $availability = $available_update_details['availability'];
                    $con_ch = $room_value->channel_id;
                    if($room_value->channel_id == 1){
                        $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                        $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                        $mp_details = get_data(PMS_EXP,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel'=>$room_value->channel_id,'exp_id'=>$room_value->import_mapping_id))->row();
                        if($availability != ""){
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                    <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                    <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                    <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                    <AvailRateUpdate>';
                            $xml .= '<DateRange from="'.$update_date.'" to="'.$update_date.'"/>';
                            $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                            
                            $xml .= '<Inventory totalInventoryAvailable="'.$availability.'"/>';
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
                            //echo $response;
                            if($response!='')
                            {
                               // echo 'fail';
                                $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$response,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                                $expedia_update = "Failed";
                            }
                            else
                            {
                               // echo 'success   ';
                                $expedia_update = "Success";
                            }
                            curl_close($ch);
                        }
                    }else if($room_value->channel_id == 11){
                        
                        $up_sart_date = date('d.m.Y',strtotime(str_replace('/','-',$sep_date)));
                        $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                        $mp_details = get_data(PMS_RECO,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id))->row();
                        if($availability!='')
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
                            <Availability>='.$availability.'</Availability>
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
                                    $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Errorarray['WARNING'],'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                                    $this->session->set_flashdata('bulk_error',$Errorarray['WARNING']);
                                }
                                else if(count($soapFault)!='0')
                                {     
                                    $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$soapFault['soapText'],'PMS Import Availabilities',date('m/d/Y h:i:s a', time())); 
                                    $this->session->set_flashdata('bulk_error',$soapFault['soapText']);
                                }
                                //return false;
                            }
                            curl_close($ch);
                        } 
                    }else if($room_value->channel_id == 5){

                        $hotelbed_start = str_replace('-', '', $update_date);
                        $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$room_value->channel_id))->row();
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
                        $mp_details = get_data(PMS_HBD_ROOM,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'htb_id'=>$room_value->import_mapping_id))->row();
                        if($availability != "")
                        {
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
                            $xml_post_string .= '<Room available="'.$availability.'" quote="'.$availability.'">'; 
                                $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                       
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
                                $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$status->DetailedMessage,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error',(string)$status->DetailedMessage);
                            }else if($xml->Status != "Y"){
                                $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$xml->Status,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                                $this->session->set_flashdata('bulk_error', "Try Again");
                            }
                        }
                    }else if($room_value->channel_id == 8){
                        $update_date = date("Y-m-d", strtotime(str_replace('/', '-', $sep_date)));
                        $ch_details = get_data(PMS_PART_CONNECT,array('partner_id'=>$owner_id,'property_id'=>$hotel_id,'channel_id'=>$con_ch))->row();
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
                        $mp_details = get_data(PMS_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'gta_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                  
                        $room_id=$mp_details->Id;
                        $rateplanid=$mp_details->rateplan_id;
                        $MinPax=$mp_details->MinPax;
                        $peakrate=$mp_details->peakrate;
                        $MaxOccupancy=$mp_details->MaxOccupancy;
                        $minnights=$mp_details->minnights;
                        $payfullperiod=$mp_details->payfullperiod;
                                                                    
                        $contract_id=$mp_details->contract_id;  

                        $hotel_channel_id=$mp_details->hotel_channel_id;
                       
                        $soapUrl= trim($gta['uavail']);
                        if($availability != "" && $room_value->update_availability=='1')
                        {
                            $xml_post_string='<GTA_InventoryUpdateRQ
                                    xmlns = "http://www.gta-travel.com/GTA/2012/05"
                                    xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05
                                    GTA_InventoryUpdateRQhelp.xsd">
                                    <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'"><RoomStyle>';
                            $xml_post_string .='<StayDate Date = "'.$update_date.'">
                                    <Inventory RoomId="'.$room_id.'" >
                                    <Detail InventoryType = "Flexible" Quantity="'.$availability.'" FreeSale = "false" ReleaseDays = "0"/></Inventory>
                                    </StayDate> ';
                            $xml_post_string .='</RoomStyle>
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
                                $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'PMS Import Availabilities',date('m/d/Y h:i:s a', time()));
                            }
                        }

                    }
                    else if($room_value->channel_id == 2){

                        if($availability != ""){                                
                            $this->booking_model->update_availability_pms($owner_id,$hotel_id,$sep_date,$room_value->import_mapping_id,$availability);
                        }
                    }
                }
            }
            /*if($manual == ""){
                $ch_data['channel_id'] = $con_ch;
                $this->update_channel_calendar($owner_id,$hotel_id,$ch_data,$availability,$sep_date,'availability');
            }*/
        }
              
        return true;
    }


}
?>
