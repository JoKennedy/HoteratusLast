<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class api extends CI_Controller {
 public function __construct()
 {
    parent::__construct();	
	/* echo insep_decode('AcPPMIo_6R3fHu6lWj6XAOHh2kqxH_238SNoNWc5n3w'); die; */
/*	if(uri(3)!='getResrvationCronFromExpdiaTest')
	{
		$ip = $this->input->ip_address();
		if( !in_array($ip, explode(',', str_replace(' ', '', IPWHITELIST))) )
		{
			mail("datahernandez@gmail.com","Ilegal Access From Hoteratus",$ip);
			?>
			<img width="1350" height="600" src="data:image/png;base64,<?php echo base64_encode(file_get_contents('user_assets/images/under.jpg'));?>" class="img-responsive" data="<?php echo insep_encode($ip); ?>">
			<?php
			die;
		}
	}*/
	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
	$this->output->set_header("Pragma: no-cache"); 	
	$data=$this->admin_model->generall();
	$this->load->model('api_model');
 }

   function index()
   {
   	 	$this->load->view('channel/document');
	
   }
 function integration()
   {

      $data['integration']=$this->db->get('integration_cms')->result();
      $data['page_heading'] = 'PMS Integration';
      $this->load->view('channel/pmsintegration',$data);  
  
   }

   /*function CreateLogin(){
     $dataPOST = trim(file_get_contents('php://input'));
     $xmlData = simplexml_load_string($dataPOST);
     $auth=$xmlData->Auth;
     $apikey=$auth->ApiKey;
     $ApiEmail=$auth->ApiEmail;
     $data=array();
     try{
        if(isset($auth)){
         $ApiEmail=$auth->ApiEmail;
         //check partner
        $check=$this->api_model->check_partner($apikey,$ApiEmail);
      
         if($check=="no"){
           throw new Exception("Authendication failed");
         }else{
             $ApiEmail=$auth->ApiEmail;
             $CreateLogin=$xmlData->CreateLogin;
             if($CreateLogin->Yourname=="" || $CreateLogin->PropertyName=="" || $CreateLogin->Phone=="" || $CreateLogin->Username=="" || $CreateLogin->Password=="")
             {
               throw new Exception("Field missing");
             }
             else{
              $t_hasher = new PasswordHash(8, FALSE);
              $pass = $t_hasher->HashPassword($CreateLogin->Password);
               $data1=array(
                'fname'=>(string)$CreateLogin->Yourname, 
                'lname'=>(string)$CreateLogin->Lastname,
                'property_name'=>(string)$CreateLogin->PropertyName,
                'mobile'=>(string)$CreateLogin->Phone,
                'web_site'=>(string)$CreateLogin->Website,
                'email_address'=>$ApiEmail, 
                'user_name'=>(string)$CreateLogin->Username,
                'password'=>(string)$pass,
                'acc_active'=>1
                );
              $check=$this->api_model->insert_user($data1);
              if($check=="no"){
              $data['failure']="Email id already exist";
              }else{
              $data['success']="ok";
              }
           }
         }
       }
      }
     catch (Exception $e) {
       $data['failure']=$e->getMessage();
     }
     echo json_encode($data);
   }*/

function setProperty(){
  $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $Property=$xmlData->Property;
  $response ='<?xml version="1.0" encoding="utf-8"?>';
  try{

      $check=$this->api_model->check_partner($apikey,$ApiEmail);
      if($check=="no"){
        throw new Exception("Authendication failed");
      }else{
      //get hotelier id
          $user_id=$check->user_type_id;
          $fname=$check->firstname;
          $lname=$check->lastname;
          $phone=$check->phone;
          $website=$check->website;
          //add proper
           $pid="";
          if($Property){
            // Check Property count
            $check_property = $this->api_model->check_count($user_id);
            if($check_property=="no"){
              throw new Exception("Multi property not applicable this account");
            }else{
                     $data = array(
                           'owner_id'=>$user_id,
                           'hotel_type'=>1,
                           'fname'=>$fname,
                           'lname'=>$lname,
                           'mobile'=>$phone,
                           'address'=>(string)$Property->Address,
                           'property_name'=>(string)$Property->Name,
                           'town'=>(string)$Property->City,
                           'web_site'=>$website,
                         );
                    $pid=$this->api_model->setproperty($data);
                     $response.='<SetPropertiesResponse><Status>true</Status>';
                    $response.='<Property>
                            <Id>'.$pid.'</Id>
                            <Name>'.(string)$Property->Name.'</Name>
                            </Property>';
                 $response.='</SetPropertiesResponse>';
                 }
        }
    }
  }
  catch (Exception $e) {
  $response.='<SetPropertiesResponse><Status>'.$e->getMessage().'</Status></SetPropertiesResponse>';
  }
  echo $response;
}

function setRooms(){
  $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $propertyId=(int)$xmlData->propertyId;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $RoomTypes=$xmlData->RoomTypes;
  $response ='';
  try{

      $check=$this->api_model->check_partner($apikey,$ApiEmail);
      if($check=="no"){
        throw new Exception("Authendication failed");
      }else{
      //get partner Details
          $user_id=$check->user_type_id;
          $fname=$check->firstname;
          $lname=$check->lastname;
          $phone=$check->phone;
          $website=$check->website;
      //add proper
          $pid="";
          //check property id
          $check_hotel=$this->api_model->check_hotel($propertyId,$user_id);
          if($check_hotel!='ok'){
             throw new Exception($check_hotel);
          }else{
               $rtypes=$RoomTypes->RoomType;
               $response.='<RoomTypes>';
               foreach ($rtypes as $key => $value) {
                if($value->Name=="" || $value->OccupancyAdults=="" || 
                  $value->OccupancyChildren=="" || $value->SellingPeriod=="" || $value->Price=="" ||
                  $value->PriceType==""){
                     $response.='<RoomType>';
                    $response.='<Success>false</Success>';
                     $response.='<RoomTypeIdMessage>Check your request.Field missing</RoomTypeIdMessage>';
                     $response.='</RoomType>';
                   }else{
                     $RoomTypeId="";
                     $RoomTypeId=(int)$value->RoomTypeId;

                     $data= array(
                       'owner_id'=>$user_id,
                       'hotel_id'=>$propertyId,
                       'property_name'=>(string)$value->Name,
                       'member_count'=>(int)$value->OccupancyAdults,
                       'children'=>(int)$value->OccupancyChildren,
                       'selling_period'=>(int)$value->SellingPeriod,
                       'description'=>(string)$value->Description,
                       'price'=>(int)$value->Price,
                       'pricing_type'=>(int)$value->PriceType,
                   );
                   $rid=$this->api_model->add_roomtype($data,$RoomTypeId);
                   $response.='<RoomType>';
                   $response.='<Success>true</Success>';
                   $response.='<RoomTypeId>'.$rid.'</RoomTypeId>';
                   $response.='<RoomTypeIdMessage>Successfully inserted</RoomTypeIdMessage>';
                   $response.='</RoomType>';
                  }
                }
                $response.='</RoomTypes>';   
          }
          
    }
  }
  catch (Exception $e) {
  $response.='<?xml version="1.0" encoding="utf-8"?><SetPropertiesResponse><Status>'.$e->getMessage().'</Status></SetPropertiesResponse>';
  }

  echo '<?xml version="1.0" encoding="utf-8"?><SetPropertiesResponse>'.$response.'</SetPropertiesResponse>';
}

//get Roomtypes
function getRoomTypes(){
    $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $Property=(int)$xmlData->propertyId;
  $response ='<?xml version="1.0" encoding="utf-8"?>';
  $data=array();
  try{

    $check=$this->api_model->check_partner($apikey,$ApiEmail);
    if($check=="no"){
      throw new Exception("Authendication failed");
    }else{
    //get hotelier id
      $user_id=$this->api_model->get_userid($ApiEmail);
      // get properties
      if($Property==""){
         throw new Exception("Check your Request , Property Id missing");
      }else{
        $property_arr=$this->api_model->getproperty($user_id,$Property);
        $response.='<GetRoomTypesResponse>';
        if($property_arr){
          //get room types
        $roomtypes_arr=$this->api_model->get_roomtypes($Property);
        if($roomtypes_arr){
        $response.='<Status>true</Status><RoomTypes>';
          foreach($roomtypes_arr as $ch){
            $response.='<RoomType>
                          <Id>'.$ch->property_id.'</Id>
                          <Name>'.$ch->property_name.'</Name>
                          <OccupancyAdults>'.$ch->member_count.'</OccupancyAdults>
                          <OccupancyChildren>'.$ch->children.'</OccupancyChildren>
                          <SellingPeriod>'.$ch->selling_period.'</SellingPeriod>
                          <Description>'.$ch->description.'</Description>
                          <Price>'.$ch->price.'</Price>
                          <PriceType>'.$ch->pricing_type.'</PriceType>
                         </RoomType>';
          }
      $response.='</RoomTypes>';
       }else{
        $response.='<RoomTypes>No Roomtypes found</RoomTypes>'; 
       }
      }else{
        $response.='<RoomTypes>Property Not Exist</RoomTypes>';
      }
        $response.='</GetRoomTypesResponse>';
    } 
   }
  //}
  }
  catch (Exception $e) {
    $response.='<GetRoomTypesResponse><Status>'.$e->getMessage().'</Status></GetRoomTypesResponse>';
  }
  echo $response;
}


//get Roomtypes




function getProperties(){
    $dataPOST = trim(file_get_contents('php://input'));
	$xmlData = simplexml_load_string($dataPOST);
	$apikey=$xmlData->ApiKey;
	$ApiEmail=(string)$xmlData->ApiEmail;
	$Property=$xmlData->Property;
	$response ='<?xml version="1.0" encoding="utf-8"?>';
	$data=array();
	try{

		$check=$this->api_model->check_partner($apikey,$ApiEmail);
		if($check=="no"){
		  throw new Exception("Authendication failed");
		}else{
		//get hotelier id
			$user_id=$this->api_model->get_userid($ApiEmail);
			// get properties
			if($Property=="All"){
			   $property=$this->api_model->getproperty($user_id);
			}else{
			   $property=$this->api_model->getproperty($user_id,$Property); 
			}
			$response.='<GetPropertiesResponse><Status>true</Status>';
			if($channel){
			$response.='<Properties>';
				foreach($channel as $ch){
				$currency_name=$this->db->where('currency_id',$ch->currency)->row()->currency_name;
				$response.='<Property>
				              <Id>'.$ch->hotel_id.'</Id>
				              <Name>'.$ch->property_name.'</Name>
				              <Address>'.$ch->address.'</Address>
				              <Website>'.$ch->web_site.'</Website>
				              <Currency>'.$currency_name.'</Currency>
				             </Property>';
				}
			$response.='</Properties>';
			}else{
			$response.='<Properties>No Properties found</Properties>';
			}
			$response.='</GetPropertiesResponse>';
		}
	//}
	}
	catch (Exception $e) {
	  $response.='<GetPropertiesResponse><Status>'.$e->getMessage().'</Status></GetPropertiesResponse>';
	}
	echo $response;
}


function setChannels(){
  $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $Property=$xmlData->PropertyId;
  $response ='<?xml version="1.0" encoding="utf-8"?>';
  $data=array();
  try{

    $check=$this->api_model->check_partner($apikey,$ApiEmail);
    if($check=="no"){
      throw new Exception("Authendication failed");
    }else{
      $user_id=$this->api_model->get_userid($ApiEmail);
      $property=$this->api_model->getproperty($user_id,$Property); 
       $response.='<SetChannelResponse>';
       if($property){
         $channels =$xmlData->Channels;
         foreach ($channels->Channel as $key => $value) {
               $channel_id=(int)$value->ChannelId;
               //check channel
               $check_channel=$this->api_model->getchannel($channel_id);
              if($check_channel){
                //add to connect channel
                $UserName=(string)$value->UserName;
                $UserPassword=(string)$value->UserPassword;
                $ReservationEmail=(string)$value->ReservationEmail;
                $data=array(
                   'user_id'=>$user_id,
                   'hotel_id'=>$Property,
                   'channel_id'=>$channel_id,
                   'status'=>'waiting',
                   'user_name'=>$UserName,
                   'user_password'=>$UserPassword,
                   'reservation_email'=>$ReservationEmail,
                   );
                   $res=$this->api_model->setchannel($data);
                   $response.='<Channel>';
                   $response.='<Success>true</Success>';
                   $response.='<ChannelId>'.$channel_id.'</ChannelId>';
                   $response.='<ChannelIdMessage>'.$res.'</ChannelIdMessage>';
                   $response.='</Channel>';
              
              }else{
                   $response.='<Channel>';
                   $response.='<Success>false</Success>';
                   $response.='<ChannelId>'.$channel_id.'</ChannelId>';
                   $response.='<ChannelIdMessage>Channel Id not exist</ChannelIdMessage>';
                   $response.='</Channel>';
              }
         }
       }else{
         $response.='<Status>Property Not found</Status>';
       }
        $response.='</SetChannelResponse>';
      }
  
  }
  catch (Exception $e) {
    $response.='<SetChannelResponse><Status>'.$e->getMessage().'</Status></SetChannelResponse>';
  }
  echo $response;
   
}


function getAvailability(){
    $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $Property=(int)$xmlData->propertyId;
  $from=(int)$xmlData->From;
  $to=(int)$xmlData->To;
  $response ='<?xml version="1.0" encoding="utf-8"?>';
  $data=array();
  try{

    $check=$this->api_model->check_partner($apikey,$ApiEmail);
    if($check=="no"){
      throw new Exception("Authendication failed");
    }else{
    //get hotelier id
      $user_id=$this->api_model->get_userid($ApiEmail);
      // get properties
      if($Property=="" || $from=="" || $to==""){
         throw new Exception("Check your Request , Field missing");
      }else{
        $property_arr=$this->api_model->getproperty($user_id,$Property);
        $response.='<GetAvailability>';
        if($property_arr){
        $roomtypes_arr=$this->api_model->get_roomtypes($Property);
        if($roomtypes_arr){
        $response.='<Status>true</Status><RoomTypes>';
          foreach($roomtypes_arr as $ch){
            $response.='<RoomType>
                          <Id>'.$ch->property_id.'</Id>
                          <Name>'.$ch->property_name.'</Name>
                          <OccupancyAdults>'.$ch->member_count.'</OccupancyAdults>
                          <OccupancyChildren>'.$ch->children.'</OccupancyChildren>
                          <SellingPeriod>'.$ch->selling_period.'</SellingPeriod>
                          <Description>'.$ch->description.'</Description>
                          <Price>'.$ch->price.'</Price>
                          <PriceType>'.$ch->pricing_type.'</PriceType>
                         </RoomType>';
          }
      $response.='</RoomTypes>';
       }else{
        $response.='<RoomTypes>No Roomtypes found</RoomTypes>'; 
       }
      }else{
        $response.='<RoomTypes>Property Not Exist</RoomTypes>';
      }
        $response.='</GetAvailability>';
    } 
   }
  //}
  }
  catch (Exception $e) {
    $response.='<GetAvailability><Status>'.$e->getMessage().'</Status></GetAvailability>';
  }
  echo $response;
}

function setAvailability(){
  $dataPOST = trim(file_get_contents('php://input'));
  $xmlData = simplexml_load_string($dataPOST);
  $apikey=$xmlData->ApiKey;
  $ApiEmail=(string)$xmlData->ApiEmail;
  $Property=(int)$xmlData->propertyId;
  $from=(int)$xmlData->From;
  $to=(int)$xmlData->To;
  $response ='<?xml version="1.0" encoding="utf-8"?>';
  $data=array();
  try{
    $check=$this->api_model->check_partner($apikey,$ApiEmail);
    if($check=="no"){
      throw new Exception("Authendication failed");
    }else{
    //get hotelier id
      $user_id=$this->api_model->get_userid($ApiEmail);
      // get properties
      if($Property=="" || $from=="" || $to==""){
         throw new Exception("Check your Request , Field missing");
      }else{
        $property_arr=$this->api_model->getproperty($user_id,$Property);
        $response.='<GetRoomTypesResponse>';
        if($property_arr){
        $roomtypes_arr=$this->api_model->get_roomtypes($Property);
        if($roomtypes_arr){
        $response.='<Status>true</Status><RoomTypes>';
          foreach($roomtypes_arr as $ch){
            $response.='<RoomType>
                          <Id>'.$ch->property_id.'</Id>
                          <Name>'.$ch->property_name.'</Name>
                          <OccupancyAdults>'.$ch->member_count.'</OccupancyAdults>
                          <OccupancyChildren>'.$ch->children.'</OccupancyChildren>
                          <SellingPeriod>'.$ch->selling_period.'</SellingPeriod>
                          <Description>'.$ch->description.'</Description>
                          <Price>'.$ch->price.'</Price>
                          <PriceType>'.$ch->pricing_type.'</PriceType>
                         </RoomType>';
          }
      $response.='</RoomTypes>';
       }else{
        $response.='<RoomTypes>No Roomtypes found</RoomTypes>'; 
       }
      }else{
        $response.='<RoomTypes>Property Not Exist</RoomTypes>';
      }
        $response.='</GetRoomTypesResponse>';
    } 
   }
  //}
  }
  catch (Exception $e) {
    $response.='<GetRoomTypesResponse><Status>'.$e->getMessage().'</Status></GetRoomTypesResponse>';
  }
  echo $response;
}
}
?>
