<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
require APPPATH.'/libraries/Passwordhash.php';

class Pmschannelmanager extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('api_model');
    }

    /*function setProperty_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);

        $Property = $xmlData->SetProperty->Property;        
       
        if($Property)
        {
            $apikey = (string)$xmlData->ApiKey;
            $password = (string)$xmlData->Password;
            $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
            if($partner->num_rows() != 0)
            {
                $property_exists = get_data(HOTEL,array('email_address'=>(string)$Property->Email));
                if($property_exists->num_rows == 0)
                {
                    if($Property->Address != "" && $Property->Name != "" && $Property->City != "" && $Property->Mobile != "" && $Property->Email != "" && $Property->Url != "")
                    {
                        $multiproperty = get_data("manage_users",array('user_id'=>$property_exists->row()->owner_id));
                        if($multiproperty->row()->multiproperty == "Active"){
                            $num_hotels = get_data("subscribe_plan",array('plan_id'=>$multiproperty->row()->plan_id),'number_of_hotels')->row()->number_of_hotels;
                            if(count($hotel) < $num_hotels){
                                $data = array(
                                    'owner_id'=>$property_exists->row()->owner_id,
                                    'hotel_type'=>'0',
                                    'address'=>(string)$Property->Address,
                                    'property_name'=>(string)$Property->Name,
                                    'town'=>(string)$Property->City,
                                    'mobile' =>(string)$Property->Mobile,
                                    'email_address' =>(string)$Property->Email,
                                    'web_site' =>(string)$Property->Url,
                                    'country' => (string)$Property->Country,
                                    'status'=>'1'
                                );
                                $pid=$this->api_model->setpmsproperty($data,$partner->row()->partnar_id);
                                if($pid)
                                {
                                    $success = array(
                                            'Status' => 'true',
                                            'Property' => array(
                                                    'Id' => $pid,
                                                    'Name' => (string)$Property->Name,
                                                ),
                                        );
                                    $this->response(array('SetProperty'=>$success), 200);
                                }
                            }else{
                                $success = array(
                                        'Status' => 'false',
                                        'Error' => 'You cannot add Property more than '.$num_hotels,
                                    );
                                $this->response(array('SetProperty'=>$success), 200);
                            }
                        }else{
                            $data = array(
                                'owner_id'=>$property_exists->row()->owner_id,
                                'hotel_type'=>'0',
                                'address'=>(string)$Property->Address,
                                'property_name'=>(string)$Property->Name,
                                'town'=>(string)$Property->City,
                                'mobile' =>(string)$Property->Mobile,
                                'email_address' =>(string)$Property->Email,
                                'web_site' =>(string)$Property->Url,
                                'country' => (string)$Property->Country,
                                'status'=>'1'
                            );
                            $pid=$this->api_model->setpmsproperty($data,$partner->row()->partnar_id);
                            if($pid)
                            {
                                $success = array(
                                        'Status' => 'true',
                                        'Property' => array(
                                                'Id' => $pid,
                                                'Name' => (string)$Property->Name,
                                            ),
                                    );
                                $this->response(array('SetProperty'=>$success), 200);
                            }
                        }
                    }else{
                        $error = array(
                                    'Status' => 'false',
                                    'Error' => 'Incorrect XML Format',
                                );
                        $this->response(array('SetProperty'=>$error), 404);
                    }
                }else{
                    $error = array(
                            'Status' => 'false',
                            'Error' => 'Email Id is already used by another Property',
                        );
                    $this->response($error, 404);
                }
            }else{
                $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect Api or Password',
                        );
                $this->response($error, 404);
            }
            
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Incorrect XML Format',
                    );
            $this->response($error, 404);
        }
    }*/

    function getProperty_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $properties = array();
        $Property = $xmlData->GetProperty->Property; 

        if($Property == "All")
        {
            $apikey = (string)$xmlData->ApiKey;
            $password = (string)$xmlData->Password;
            $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
            if($partner->num_rows() != 0)
            {
                $property = get_data(PMS_PART_HOTEL,array('partner_id'=>$partner->row()->partnar_id))->result();
                $properties = array();
                foreach($property as $val){
                    $value = get_data(HOTEL,array('hotel_id'=>$val->property_id,'owner_id'=>$val->owner_id))->row();

                    if(count($value) != 0)
                    {
                        $properties[] = array(
                            'Id' => $value->hotel_id,
                            'Name' => $value->property_name,
                            'Address' => $value->address,
                            'Mobile' => $value->mobile,
                            'Email' => $value->email_address,
                            'Url' => $value->web_site,
                        );
                    }
                }

                $this->response(array('GetProperty'=>$properties), 200,'Property');
            }
        }else if($Property){
            $apikey = (string)$xmlData->ApiKey;
            $password = (string)$xmlData->Password;
            $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
            if($partner->num_rows() != 0)
            {
                $property = get_data(PMS_PART_HOTEL,array('partner_id'=>$partner->row()->partnar_id,'id'=>(string)$Property))->result();
                foreach($property as $val){
                    $value = get_data(HOTEL,array('hotel_id'=>$val->property_id,'owner_id'=>$val->owner_id))->row();
                    if(count($value) != 0)
                    {
                        $properties[] = array(
                            'Id' => $value->hotel_id,
                            'Name' => $value->property_name,
                            'Address' => $value->address,
                            'Mobile' => $value->mobile,
                            'Email' => $value->email_address,
                            'Url' => $value->web_site,
                        );
                    }
                }

                /*foreach ($property as $key => $value) {
                    unset($property[$key]);
                    $property['Property'][] = (object)$value;
                }*/
                //$data=array_fill_keys($property, 'Property')
                $this->response(array('GetProperty'=>$properties), 200,'Property');
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Incorrect XML Format',
                    );
            $this->response($error, 404);
        }
        
    }

    function setRoom_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $requiredField = array('Name','OccupancyAdults','OccupancyChildren','SellingPeriod','Price','PriceType');
        $hotel_id = (string)$xmlData->SetRooms->propertyId;
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $rooms = $xmlData->SetRooms->RoomTypes->RoomType;
                foreach ($rooms as $key => $value) {
                    $data = array(
                        'owner_id' => $property_exists->row()->owner_id,
                        'hotel_id' => $hotel_id,
                    );
                    foreach($value as $attr => $val)
                    {
                        $data[$attr] = (string)$val;

                        if($data[$attr] == "")
                        {
                            if($attr != "Description")
                            {
                                $error = array(
                                            'Status' => 'false',
                                            'Error' => $attr." Field is Incorrect",
                                        );
                                $this->response(array('SetRooms'=>$error), 404);
                            }
                        }
                    }
                    if(isset($data['RoomTypeId']))
                    {
                        $id = $data['RoomTypeId'];
                        unset($data['RoomTypeId']);
                        if(isset($data['SellingPeriod']) && !in_array($data['SellingPeriod'], array('1','2','3')))
                        {
                            $error = array(
                                        'Status' => 'false',
                                        'Error' => "SellingPeriod Field is Incorrect",
                                    );
                            $this->response(array('SetRooms'=>$error), 404);
                        }

                        if(isset($data['PriceType']) && !in_array($data['PriceType'], array('1','2')))
                        {
                            $error = array(
                                        'Status' => 'false',
                                        'Error' => "PriceType Field is Incorrect",
                                    );
                            $this->response(array('SetRooms'=>$error), 404);
                        }
                        $data['property_name'] = $data['Name'];unset($data['Name']);
                        $data['member_count'] = $data['OccupancyAdults']; unset($data['OccupancyAdults']);
                        $data['children'] = $data['OccupancyChildren'];unset($data['OccupancyChildren']);
                        $data['pricing_type'] = $data['PriceType'];unset($data['PriceType']);
                        $data['selling_period'] = $data['SellingPeriod'];unset($data['SellingPeriod']);
                        $data['description'] = $data['Description'];unset($data['Description']);
                        $data['price'] = $data['Price'];unset($data['Price']);
                        $data['droc'] = 1;
                        $data['non_refund'] = "";
                        $data['status'] = "Active";
                        $data['created_date'] = date('y-m-d H:i:s');

                        if(update_data(TBL_PROPERTY,$data,array('property_id'=>$id))){
                            $message['RoomTypes'][] = array(
                                    'Success' => "true",
                                    'RoomTypeId' => $id,
                                    'RoomTypeIdMessage' => 'Successfully Updated',
                                );                            
                        }else{
                            $message['RoomTypes'][] = array(
                                    'Success' => "false",
                                    'RoomTypeId' => $id,
                                    'RoomTypeIdMessage' => 'Check your request.Field missing',
                                );
                        }
                    }else{
                        foreach($requiredField as $field)
                        {
                            if(!in_array($field, array_keys($data)))
                            {
                                $error = array(
                                            'Status' => 'false',
                                            'Error' => $field." Field is Missing",
                                        );
                                $this->response(array('SetRooms'=>$error), 404);
                            }
                        }

                        if(!in_array($data['SellingPeriod'], array('1','2','3')))
                        {
                            $error = array(
                                        'Status' => 'false',
                                        'Error' => "SellingPeriod Field is Incorrect",
                                    );
                            $this->response(array('SetRooms'=>$error), 404);
                        }

                        if(!in_array($data['PriceType'], array('1','2')))
                        {
                            $error = array(
                                        'Status' => 'false',
                                        'Error' => "PriceType Field is Incorrect",
                                    );
                            $this->response(array('SetRooms'=>$error), 404);
                        }
                        $data['property_name'] = $data['Name'];unset($data['Name']);
                        $data['member_count'] = $data['OccupancyAdults']; unset($data['OccupancyAdults']);
                        $data['children'] = $data['OccupancyChildren'];unset($data['OccupancyChildren']);
                        $data['pricing_type'] = $data['PriceType'];unset($data['PriceType']);
                        $data['selling_period'] = $data['SellingPeriod'];unset($data['SellingPeriod']);
                        $data['description'] = $data['Description'];unset($data['Description']);
                        $data['price'] = $data['Price'];unset($data['Price']);
                        $data['droc'] = 1;
                        $data['non_refund'] = "";
                        $data['status'] = "Active";
                        $data['created_date'] = date('y-m-d H:i:s');
                        
                        $id  = $this->api_model->insert_rooms($data);
                        if($id)
                        {
                            $message['RoomTypes'][] = array(
                                    'Success' => "true",
                                    'RoomTypeId' => $id,
                                    'RoomTypeIdMessage' => 'Successfully Inserted',
                                );   
                        }else{
                            $message['RoomTypes'][] = array(
                                    'Success' => "false",
                                    'RoomTypeId' => $id,
                                    'RoomTypeIdMessage' => 'Check your request.Field missing',
                                );
                        }
                    }
                }

                $this->response(array('SetRooms'=>$message),200,'RoomType');

            }else{
                $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect Property ID',
                        );
                $this->response(array('SetRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('SetRooms'=>$error), 404);
        }
    }

    function getRoom_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $hotel_id = (string)$xmlData->GetRooms->propertyId;
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));

            if($property_exists->num_rows() != 0)
            {
                $propertyroom_exists = get_data(TBL_PROPERTY,array('hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id))->num_rows();
                if($propertyroom_exists != 0){
                    $rooms = $xmlData->GetRooms->RoomTypes;
                    if(isset($rooms->RoomTypeId))
                    {
                        foreach ($rooms->RoomTypeId as $id) {
                            $roomdetail = get_data(TBL_PROPERTY,array('property_id'=>(string)$id,'hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id));
                            if($roomdetail->num_rows != 0){
                                $room = $roomdetail->row_array();
                                $message['RoomTypes'][] = array(
                                    'Id' => $room['property_id'],
                                    'Name'=> $room['property_name'],
                                    'OccupancyAdults'=> $room['member_count'],
                                    'OccupancyChildren'=> $room['children'],
                                    'SellingPeriod' => $room['selling_period'],
                                    'Description' => $room['description'],
                                    'Price'=> $room['price'],
                                    'PriceType' => $room['pricing_type'],
                                );
                            }else{
                                $message['RoomTypes'][] = array(
                                    'Id' => (string)$id,
                                    'Error' => "Room Not Exists",                                  
                                );
                            }
                        }
                    }else{
                        $rooms = get_data(TBL_PROPERTY,array('hotel_id'=>$hotel_id,'owner_id' => $property_exists->row()->owner_id))->result_array();
                        foreach($rooms as $room){
                            $message['RoomTypes'][] = array(
                                'Id' => $room['property_id'],
                                'Name'=> $room['property_name'],
                                'OccupancyAdults'=> $room['member_count'],
                                'OccupancyChildren'=> $room['children'],
                                'SellingPeriod' => $room['selling_period'],
                                'Description' => $room['description'],
                                'Price'=> $room['price'],
                                'PriceType' => $room['pricing_type'],
                            );
                        }
                    }
                    $this->response(array('GetRooms'=>$message),200,'RoomType');
                }else{
                    $error = array(
                                'Status' => 'false',
                                'Error' => 'No Room Types Found Under the Property',
                            );
                    $this->response(array('GetRooms'=>$error), 404);
                }
                
            }else{
                $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect Property ID',
                        );
                $this->response(array('GetRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetRooms'=>$error), 404);
        }
    }

    function removeRoom_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $hotel_id = (string)$xmlData->RemoveRooms->propertyId;
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $propertyroom_exists = get_data(TBL_PROPERTY,array('hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id))->num_rows();
                if($propertyroom_exists != 0){
                    $rooms = $xmlData->RemoveRooms->RoomTypes;
                    if(isset($rooms->RoomTypeId))
                    {
                        foreach ($rooms->RoomTypeId as $id) {
                            $roomdetail = get_data(TBL_PROPERTY,array('property_id'=>(string)$id,'hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id));
                            if($roomdetail->num_rows != 0){
                                $map_exists = get_data(MAP,array('property_id'=>(string)$id,'owner_id'=>$property_exists->row()->owner_id,'hotel_id'=>$hotel_id))->num_rows();
                                if($map_exists == 0)
                                {
                                    if(delete_data(TBL_PROPERTY,array('property_id'=>(string)$id)))
                                    { 
                                        $message['RoomTypes'][] = array(
                                            'RoomTypeId' => (string)$id,
                                            'Success' => 'Deleted Successfully',
                                        );
                                    }
                                }else{
                                    $message['RoomTypes'][] = array(
                                        'RoomTypeId' => (string)$id,
                                        'Error' => 'You cannot delete the Rooms untill you delete the mapping of this room',
                                    );
                                }
                            }else{
                                $message['RoomTypes'][] = array(
                                    'Id' => (string)$id,
                                    'Error' => "Room Not Exists",                                  
                                );
                            }
                        }
                        $this->response(array('RemoveRooms'=>$message), 404,'RoomType');
                    }else{
                        $properties = get_data(TBL_PROPERTY,array('hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id))->result();
                        if(count($properties) != 0)
                        {
                            foreach($properties as $row)
                            {
                                $map_exists = get_data(MAP,array('property_id'=>$row->property_id,'owner_id'=>$property_exists->row()->owner_id,'hotel_id'=>$hotel_id))->num_rows();
                                if($map_exists == 0)
                                {
                                    if(delete_data(TBL_PROPERTY,array('property_id'=>$row->property_id)))
                                    { 
                                        $message['RoomTypes'][] = array(
                                            'RoomTypeId' => $row->property_id,
                                            'Success' => 'Deleted Successfully',
                                        );
                                    }
                                }else{
                                    $message['RoomTypes'][] = array(
                                        'RoomTypeId' => $row->property_id,
                                        'Error' => 'You cannot delete the Rooms untill you delete the mapping of this room',
                                    );
                                }
                            }
                        }
                        $this->response(array('RemoveRooms'=>$message), 404);
                    }
                }else{
                    $error = array(
                                'Status' => 'false',
                                'Error' => 'No Room Types Found Under the Property',
                            );
                    $this->response(array('RemoveRooms'=>$error), 404);
                }

            }else{
                $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect Property ID',
                        );
                $this->response(array('RemoveRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('RemoveRooms'=>$error), 404);
        }
    }

    function getChannel_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->GetChannels->propertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                if($xmlData->GetChannels->Channels){
                    $ChannelDetails = (string)$xmlData->GetChannels->Channels;
                    if($ChannelDetails == "All")
                    {
                        $channels = get_data(CONNECT,array('hotel_id'=>$hotel_id,'user_id'=>$property_exists->row()->owner_id,'status'=>'enabled'))->result_array();
                        if(count($channels) != 0)
                        {
                            foreach($channels as $channel)
                            {
                                $cha_name = get_data(TBL_CHANNEL,array('channel_id'=>$channel['channel_id']))->row()->channel_name;
                                $message['Channels'][] = array(
                                        'Id' => $channel['channel_id'],
                                        'Name' => $cha_name,
                                        'HotelChannelId' => $channel['hotel_channel_id'],
                                        'Status' => $channel['status']
                                    ); 
                            }
                        }else{
                            $message['Channels'][] = array(
                                'Status' => 'false',
                                'Error' => 'No Channels Found',
                            ); 
                        }
                        $this->response(array('GetChannels'=>$message),200,'Channel');
                    }else{

                        if($xmlData->GetChannels->Channels->ChannelId)
                        {
                            $Ids = $xmlData->GetChannels->Channels->ChannelId;
                            foreach($Ids as $id)
                            {
                                $channels = get_data(CONNECT,array('channel_id'=>$id,'hotel_id'=>$hotel_id,'user_id'=>$property_exists->row()->owner_id,'status'=>'enabled'));
                                $cha_name = get_data(TBL_CHANNEL,array('channel_id'=>$id))->row()->channel_name;
                                if($channels->num_rows() != 0){
                                    $channel = $channels->row_array();
                                    $message['Channels'][] = array(
                                        'Id' => $channel['channel_id'],
                                        'Name' => $cha_name,
                                        'HotelChannelId' => $channel['hotel_channel_id'],
                                        'Status' => $channel['status']
                                    );
                                }else{
                                    $message['Channels'][] = array(
                                        'Id' => (string)$id,
                                        'Message' => 'Channel Not Exists',
                                    );
                                }
                            }
                            $this->response(array('GetChannels'=>$message),200,'Channel');
                        }else{

                            $error = array(
                                'Status' => 'false',
                                'Error' => 'Incorrect XML Format',
                            );
                            $this->response(array('GetChannels'=>$error), 404);
                        }
                    }
                }else{
                    $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect XML Format',
                        );
                    $this->response(array('GetChannels'=>$error), 404);
                }
            }else{
                $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect Property ID',
                        );
                $this->response(array('GetChannels'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetChannels'=>$error), 404);
        }
    }

    function setChannel_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->SetChannels->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                if($xmlData->SetChannels->Channels->Channel){
                    $channels = $xmlData->SetChannels->Channels->Channel;
                    foreach($channels as $channel)
                    {
                        $channels = get_data(TBL_CHANNEL,('status = "Active" AND channel_id = '.(string)$channel->ChannelId));
                        if($channels->num_rows() != 0)
                        {
                            if((string)$channel->ChannelId == 8 && isset($channel->WEBID) === FALSE)
                            {
                                $error = array(
                                    'Status' => 'false',
                                    'ChannelId' => (string)$channel->ChannelId,
                                    'Error' => 'WEBID Field Missing',
                                );
                                $this->response(array('SetChannel'=>$error), 404);
                                exit;
                            }
                            if((string)$channel->ChannelId == 2 && isset($channel->XMLConnectivity) === FALSE)
                            {
                                $error = array(
                                    'Status' => 'false',
                                    'ChannelId' => (string)$channel->ChannelId,
                                    'Error' => 'XMLConnectivity Field Missing',
                                );
                                $this->response(array('SetChannel'=>$error), 404);
                                exit;
                            }
                            if((string)$channel->ChannelId == 1 || (string)$channel->ChannelId == 2 || (string)$channel->ChannelId == 15)
                            {
                                $channel_credentials = get_data(TBL_CHANNEL,array('channel_id' => (string)$channel->ChannelId))->row();
                                $username = (string)$channel_credentials->channel_username;
                                $userpass = (string)$channel_credentials->channel_password;
                                $cmid = $channel_credentials->cmid;
                            }
                            else{
                                $username = (string)$channel->UserName;
                                $userpass = (string)$channel->UserPassword;
                                $cmid = "";
                            }
                            $get_urls = get_data("channel_urls",array("channel_id" => (string)$channel->ChannelId))->row();
                            $test_url = $get_urls->test_url;
                            $live_url = $get_urls->live_url;
                            $mode = $get_urls->mode;
                            $channeldata = array(
                                    'user_id' => $property_exists->row()->owner_id,
                                    'hotel_id' => $hotel_id,
                                    'channel_id' => (string)$channel->ChannelId,
                                    'hotel_channel_id'=>(string)$channel->HotelChannelId,
                                    'user_name' => $username,
                                    'user_password' => $userpass,
                                    'reservation_email' => (string)$channel->ReservationEmail,
                                    'cmid' => $cmid,
                                    'connect_date' => date('Y-m-d H:i:s'),
                                    'live_url' => $live_url,
                                    'mode' => $mode,
                                    'test_url' => $test_url,
                                );
                            if($channel->WEBID)
                            {
                                $channeldata['web_id'] = (string)$channel->WEBID;
                            }
                            if($channel->XMLConnectivity)
                            {
                                $channeldata['xml_type'] = (string)$channel->XMLConnectivity;
                            }
                            $connect_exists = get_data(CONNECT,array('channel_id' => (string)$channel->ChannelId,'user_id'=>$property_exists->row()->owner_id,'hotel_id'=>$hotel_id));
                            if($connect_exists->num_rows == 0){
                                insert_data(CONNECT,$channeldata);
                            }else{
                                update_data(CONNECT,$channeldata,array('channel_id' => (string)$channel->ChannelId,'user_id'=>$property_exists->row()->owner_id,'hotel_id'=>$hotel_id));
                            }
                            $response = $this->api_model->getChannelRooms($channeldata);
                            if($response['result'] == 0){
                                $message['Channels'][] = array(
                                    'Id' => (string)$channel->ChannelId,
                                    'Error' => $response['content'],
                                );
                            }else{
                                $message['Channels'][] = array(
                                    'Id' => (string)$channel->ChannelId,
                                    'Message' => $response['content'],
                                );
                            }
                        }else{
                            $message['Channels'][] = array(
                                'Id' => (string)$channel->ChannelId,
                                'Message' => 'Channel Not Exists',
                            );                        
                        }
                    }
                    $this->response(array('SetChannel'=>$message), 200);
                }else{
                    $error = array(
                        'Status' => 'false',
                        'Error' => 'Incorrect XML Format',
                    );
                    $this->response(array('SetChannel'=>$error), 404);
                }

            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('SetChannel'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('SetChannel'=>$error), 404);
        }
    }

    function getChannelRooms_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->ChannelRooms->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        $message = array();
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                if($xmlData->ChannelRooms->Rooms){
                    $channels = $xmlData->ChannelRooms->Rooms->Channel;
                    if($xmlData->ChannelRooms->Rooms->Channel->ChannelId && $xmlData->ChannelRooms->Rooms->Channel->HotelChannelId)
                    {
                        foreach($channels as $channel)
                        {
                            $enabled = get_data(CONNECT,array('user_id'=>$property_exists->row()->owner_id,'hotel_id' => $hotel_id,'channel_id'=>(string)$channel->ChannelId,'status'=>'enabled'))->num_rows();
                            if($enabled != 0)
                            {
                                $Rooms = $this->api_model->getMappingRooms((string)$channel->ChannelId,(string)$channel->HotelChannelId,$hotel_id,$property_exists->row()->owner_id);
                                if(count($Rooms) != 0)
                                {
                                    foreach($Rooms as $room)
                                    {
                                        if(count($room) != 0){
                                            $message['Rooms'][] = array(
                                                'ChannelId' => $room['channel_id'],
                                                'RoomID' => $room['room_id'],
                                                'RoomName' => $room['ch_room_name'],
                                            );
                                        }
                                    }
                                }else{
                                    $message['Rooms'][] = array(
                                        'ChannelId' => (string)$channel->channel_id,
                                        'Error' => "Invalid Entry",
                                    );
                                }
                            }else{
                                $message['Rooms'][] = array(
                                    'ChannelId' => (string)$channel->channel_id,
                                    'Error' => "Channel Disabled",
                                );
                            }
                        }
                        $this->response(array('ChannelRooms'=>$message), 200);
                    }else{
                        $error = array(
                            'Status' => 'false',
                            'Error' => 'Incorrect XML Format',
                        );
                        $this->response(array('ChannelRooms'=>$error), 404);
                    }
                }else{
                    $error = array(
                        'Status' => 'false',
                        'Error' => 'Incorrect XML Format',
                    );
                    $this->response(array('ChannelRooms'=>$error), 404);
                }
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('ChannelRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('ChannelRooms'=>$error), 404);
        }
    }
    

    function mapRooms_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->MapRooms->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $map = $xmlData->MapRooms->MapRoom;
                foreach($map as $detail => $val)
                {
                    $is_not_exist = $this->api_model->check_room_map_exists((string)$val->ChannelRoomId,(string)$val->RoomTypeId,(string)$val->ChannelId,$hotel_id,$property_exists->row()->owner_id);
                    if($is_not_exist)
                    {
                        $mapfields = get_data(PMS_MAP_VAL,array('channel_id'=>(string)$val->ChannelId));
                        $maplabel = "";
                        $mapvalue = "";
                        $title = "";
                        foreach($val as $key => $value)
                        {
                            if($mapfields->num_rows != 0)
                            {
                                $mappingvalues = $mapfields->row()->mapping_fields;
                                $map = explode(',', $mappingvalues);
                                if(in_array($key, $map))
                                {
                                    $maplabel .= $key.',';
                                    $mapvalue .= $value.',';
                                }

                            }        
                        }
                        if($mapfields->num_rows != 0)
                        {
                            $title = $mapfields->row()->title;
                        }
                        $maplabel = rtrim($maplabel,',');
                        $mapvalue = rtrim($mapvalue,',');
                        
                        if($maplabel != "")
                        {
                            if($maplabel == $mapfields->row()->mapping_fields)
                            {
                                $mapdetails = array(
                                    'owner_id' => $property_exists->row()->owner_id,
                                    'hotel_id' => $hotel_id,
                                    'channel_id' => (string)$val->ChannelId,
                                    'property_id' => (string)$val->RoomTypeId,
                                    'import_mapping_id' => (string)$val->ChannelRoomId,
                                    'rate_id'=>0,
                                    'enabled'=>(string)$val->Status,
                                    'guest_count'=>0, 
                                    'refun_type'=>0,  
                                    'update_rate'=>(string)$val->UpdateRate,  
                                    'update_availability'=>(string)$val->UpdateAvailability,
                                    'rate_conversion' => (string)$val->RateConversion,
                                    'explevel' => 'room',
                                );
                                $this->db->insert(MAP,$mapdetails);
                                $map_id = $this->db->insert_id();
                                $mapping_values = array(
                                        'user_id' => $property_exists->row()->owner_id,
                                        'hotel_id' => $hotel_id,
                                        'mapping_id' => $map_id,
                                        'label' => $maplabel,
                                        'value' => $mapvalue,
                                        'created_date' => date('Y-m-d H:i:s'),
                                        'title' => $title,
                                    );
                                insert_data(MAP_VAL,$mapping_values);

                                $message[] = array(
                                        'Status' => 'true',
                                        'MapId' => $map_id,
                                        'ChannelId' => (string)$val->ChannelId,
                                        'RoomTypeId' => (string)$val->RoomTypeId,
                                        'ChannelRoomId' => (string)$val->ChannelRoomId,
                                        'Message' => 'Room Mapped Successfully',
                                    );
                                
                            }else{
                                $message[] = array(
                                    'Status' => 'false',
                                    'ChannelId' => (string)$val->ChannelId,
                                    'RoomTypeId' => (string)$val->RoomTypeId,
                                    'ChannelRoomId' => (string)$val->ChannelRoomId,
                                    'Message' => 'Incorrect XML Format',
                                );
                            }
                        }else{
                            $message[] = array(
                                'Status' => 'false',
                                'ChannelId' => (string)$val->ChannelId,
                                'RoomTypeId' => (string)$val->RoomTypeId,
                                'ChannelRoomId' => (string)$val->ChannelRoomId,
                                'Message' => 'Incorrect XML Format',
                            );
                        }
                    }else{
                        $message[] = array(
                            'Status' => 'false',
                            'ChannelId' => (string)$val->ChannelId,
                            'RoomTypeId' => (string)$val->RoomTypeId,
                            'ChannelRoomId' => (string)$val->ChannelRoomId,
                            'Message' => 'Invalid ChannelRoomId or RoomTypeId',
                        );                        
                    }                    
                }
                $this->response(array('MapRooms'=>$message),200,'Map');
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('MapRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('MapRooms'=>$error), 404);
        }
    }

    function getMappedRooms_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->GetMappedRooms->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $mappedrooms = (string)$xmlData->GetMappedRooms->MappedRooms;
                if($mappedrooms == "All")
                {
                    $mapped_rooms = get_data(MAP,array('hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id,'enabled'=>'enabled'))->result_array();
                    foreach($mapped_rooms as $rooms)
                    {
                        $message['Rooms'][] = array(
                                'Id' => $rooms['channel_id'],
                                'MapId' => $rooms['mapping_id'],
                                'RoomTypeId' => $rooms['property_id'],
                                'ChannelRoomId' => $rooms['import_mapping_id'],
                                'Status' => $rooms['enabled']
                            ); 
                    }
                    $this->response(array('GetMappedRooms'=>$message),200,'Channel');
                }else{
                    if($xmlData->GetMappedRooms->MappedRooms->Channels)
                    {
                        $channels = $xmlData->GetMappedRooms->MappedRooms->Channels->ChannelId;
                        foreach($channels as $channel)
                        {
                            $mapped_rooms = get_data(MAP,array('hotel_id'=>$hotel_id,'owner_id'=>$property_exists->row()->owner_id,'enabled'=>'enabled','channel_id'=>(string)$channel))->result_array();
                            foreach($mapped_rooms as $rooms)
                            {
                                $message['Rooms'][] = array(
                                        'Id' => $rooms['channel_id'],
                                        'MapId' => $rooms['mapping_id'],
                                        'RoomTypeId' => $rooms['property_id'],
                                        'ChannelRoomId' => $rooms['import_mapping_id'],
                                        'Status' => $rooms['enabled']
                                    ); 
                            }   
                        }
                        $this->response(array('GetMappedRooms'=>$message),200,'Channel');
                    }else{
                        $error = array(
                            'Status' => 'false',
                            'Error' => 'Invalid XML Format',
                        );
                        $this->response(array('GetMappedRooms'=>$error), 404);
                    }
                }
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('GetMappedRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetMappedRooms'=>$error), 404);
        }
    }

    function removeMappedRooms_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->RemoveMappedRooms->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                if($xmlData->RemoveMappedRooms->RemoveRooms->MapId)
                {
                    $mappingIds = $xmlData->RemoveMappedRooms->RemoveRooms->MapId;
                    $mapps = array();
                    foreach($mappingIds as $mapping_id)
                    {
                        $mapps[] = $mapping_id;
                    }
                    
                    if($this->api_model->removemapp($mapps,$hotel_id,$property_exists->row()->owner_id))
                    {
                        $error = array(
                            'Status' => 'true',
                            'Error' => 'Deleted Successfully',
                        );
                        $this->response(array('RemoveMappedRooms'=>$error), 404);
                    }else{
                        $error = array(
                            'Status' => 'false',
                            'Error' => 'Something went wrong.Check your Request',
                        );
                        $this->response(array('RemoveMappedRooms'=>$error), 404);
                    }
                }else{
                    $error = array(
                        'Status' => 'false',
                        'Error' => 'MapId is Missing',
                    );
                    $this->response(array('RemoveMappedRooms'=>$error), 404);
                }
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('RemoveMappedRooms'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('RemoveMappedRooms'=>$error), 404);
        }
    }

    function setAllocation_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        $hotel_id = (string)$xmlData->SetAllocation->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $allocations = $xmlData->SetAllocation->Allocations->Allocation;
                $daterange = $xmlData->SetAllocation->DateRange;
                foreach($allocations as $allocation)
                {
                    $days = "";
                    $Weekdays = $allocation->Weekdays;
                    $channels = $allocation->Channels->Channel;
                    $channel_id = "";
                    foreach($channels as $channel)
                    {
                        $channel_id .= $channel.',';
                    }
                    $channel_id = rtrim($channel_id,',');
                    foreach($Weekdays as $Weekday)
                    {
                        if($Weekday->Sun == 1){
                            $days .= '1,';
                        }
                        if($Weekday->Mon == 1){
                            $days .= '2,';
                        }
                        if($Weekday->Tue == 1){
                            $days .= '3,';
                        }
                        if($Weekday->Wed == 1){
                            $days .= '4,';
                        }
                        if($Weekday->Thu == 1){
                            $days .= '5,';
                        }
                        if($Weekday->Fri == 1){
                            $days .= '6,';
                        }
                        if($Weekday->Sat == 1){
                            $days .= '7,';
                        }
                    }
                    $days = rtrim($days,',');
                    $products[] = array(
                            'start_date' => (string)$daterange->StartDate,
                            'end_date' => (string)$daterange->EndDate,
                            'hotel_id'=> $hotel_id,
                            'owner_id' => $property_exists->row()->owner_id,
                            'days' => $days,
                            'channel_id' => $channel_id,
                            'room_id' => (string)$allocation->RoomTypeId,
                            'availability' => (string)$allocation->Availability,
                            'minimum_stay' => (string)$allocation->Min,
                            'price' => (string)$allocation->Price,
                            'cta' => (string)$allocation->CTA,
                            'ctd' => (string)$allocation->CTD,
                            'stop_sell' => (string)$allocation->StopSell,
                            'open_room' => (string)$allocation->OpenRoom,
                        );
                }
                //print_r($products);
                $response = $this->api_model->update_channels_table($products);
                if($response == "success")
                {
                    $error = array(
                            'Status' => 'true',
                            'Success' => 'Updated Successfully',
                        );
                    $this->response(array('SetAllocation'=>$error), 200);
                }else{
                    if(is_array($response))
                    {
                        foreach($response as $mes)
                        {
                            $message[] = array(
                                    'Status' => 'false',
                                    'ChannelId' => $mes['ChannelID'],
                                    'RoomId' => $mes['RoomId'],
                                    'Error' => $mes['Error'],
                                );
                        }
                        $this->response(array('SetAllocation'=>$message), 200,'Allocations');
                    }else{
                        $this->response(array('SetAllocation'=>$response), 200,'Allocations');
                    }
                }
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('SetAllocation'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('SetAllocation'=>$error), 404);
        }
    }

    function getAllocation_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        
        $hotel_id = (string)$xmlData->GetAllocations->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $dateFrom = (string)$xmlData->GetAllocations->DateFrom;
                $dateTo = (string)$xmlData->GetAllocations->DateTo;

                $dateFrom = date('d/m/Y',strtotime($dateFrom));
                $dateTo = date('d/m/Y',strtotime($dateTo));

                if($xmlData->GetAllocations->ChannelID)
                {
                    $channelids = $xmlData->GetAllocations->ChannelID;
                }else{
                    $channelids = array();
                }
                if(count($channelids) != 0){
                    foreach($channelids as $channelid)
                    {
                        $responses[] = $this->api_model->getAllocation($dateFrom,$dateTo,$hotel_id,$property_exists->row()->owner_id,$channelid);
                    }
                }else{
                    $responses[] = $this->api_model->getAllocation($dateFrom,$dateTo,$hotel_id,$property_exists->row()->owner_id,"");
                }

                foreach($responses as $response)
                {
                    foreach($response as $key => $value)
                    {
                        $room_id =  $value->room_id;
                        $channel_id = $value->individual_channel_id;
                        /*$data['RoomAvailability'][$room_id][$channel_id]['attributes'] = array(
                                'RoomId' => $room_id,
                                'ChannelID' => $channel_id,
                                'From' => $dateFrom,
                                'To' => $dateTo,
                            );*/


                        $data[$room_id][$channel_id][] = array(
                                'Availability' => $value->availability,
                                'Price' => $value->price,
                                'MinimumStay' => $value->minimum_stay,
                                'CTA' => $value->cta,
                                'CTD' => $value->ctd,
                                'StopSell' => $value->stop_sell,
                                'OpenRoom' => $value->open_room,
                                'Days' => $value->days,
                                'Date' => $value->separate_date,
                            );
                        
                    }
                }
                foreach($data as $room => $details)
                {

                    foreach($details as $channel => $val)
                    {

                        $roomdata['Allocation'][$room][$channel]['attributes'] = array(
                                'RoomId' => $room,
                                'ChannelID' => $channel,
                            );

                        foreach($val as $v){
                            $dated = date('Y-m-d',strtotime(str_replace('/','-',$v['Date'])));
                            //$roomdata['Allocation'][$room][$channel]['Date']['attributes'][] =

                            $roomdata['Allocation'][$room][$channel]['Date day="'.$dated.'"'] = array(
                                    'Availability' => $v['Availability'],
                                    'Price' => $v['Price'],
                                    'MinimumStay' => $v['MinimumStay'],
                                    'CTA' => $v['CTA'],
                                    'CTD' => $v['CTD'],
                                    'StopSell' => $v['StopSell'],
                                    'OpenRoom' => $v['OpenRoom'],
                                );
                        }
                    }
                    //print_r($roomdata);

                    //$data['RoomAvailability']['attributes']
                }
                foreach($roomdata as $rd => $cd)
                {
                    //print_r($cd);
                    foreach($cd as $cont => $con)
                    {
                        //print_r($con);
                        foreach($con as $cn)
                        {
                            //print_r($cn);
                            $finalarray[] = $cn;
                        }
                    }
                }
                $this->response(array('GetAllocation' => $finalarray),200,'RoomAvailability');
                //print_r($data);
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('GetAllocation'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetAllocation'=>$error), 404);
        }
    }

    function xml_check_post()
    {
        echo "djfh";
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        print_r($xmlData);
        /*$data['RoomAvailability'] = array();

        $data['RoomAvailability']['attributes'] = array(
                'RoomId' => 25,
                'Date' => '08-08-2015',
            );

        $data['RoomAvailability']['DayAvailability']['attributes'] = array(
                'Alot' => 1,
            );
        $data['RoomAvailability']['DayAvailability'][] = array(
                'Alotdfdsfsd' => 1,
            );*/
        //$this->response(array('Availability' => $data), 200);
    } 

    function getBookings_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        
        $hotel_id = (string)$xmlData->GetBookings->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id,'owner_id'=>$partner->row()->owner_id));
            if($property_exists->num_rows() != 0)
            {
                $partner_id = $partner->row()->partner_id;
                $owner_id = $partner->row()->owner_id;
                $dateFrom = (string)$xmlData->GetBookings->DateFrom;
                $dateTo = (string)$xmlData->GetBookings->DateTo;
                $channel_id = "";
                $channel_id = $xmlData->GetBookings->ChannelID;
                if($xmlData->GetBookings->ChannelID){
                    foreach($xmlData->GetBookings->ChannelID as $channel_id)
                    {
                        //echo $channel_id;
                        $response = $this->api_model->getResrvationFromChannel($owner_id,$hotel_id,$dateFrom,$dateTo,(string)$channel_id);
                        //print_r($response);
                        foreach ($response as $key => $value) {
                            if(!empty($value)){
                                foreach($value as $channel => $val)
                                {
                                    if($val['result'] == 1 && $val['reser'] != "")
                                    {
                                        $booking_ids = rtrim($val['reser'] , ',');
                                        $booking_ids = explode(',', $val['reser']);

                                        foreach($booking_ids as $booking_id)
                                        {
                                            if($booking_id != ""){
                                                $getRe =  $this->api_model->getReservationById($booking_id,$channel);
                                                $channel_resp[] = $this->getSingleXMLResponse($getRe,$channel);
                                            }
                                        }
                                        
                                    }else{
                                        $channel_resp[] = array(
                                                'ChannelId' => $channel,
                                                'Status' => 'false',
                                                'Error' => $val['content'],
                                            );
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $response = $this->api_model->getResrvationFromChannel($partner_id,$hotel_id,$dateFrom,$dateTo,(string)$channel_id);
                    foreach ($response as $key => $value) {
                        if(!empty($value)){
                            foreach($value as $channel => $val)
                            {
                                if($val['result'] == 1 && $val['reser'] != "")
                                {
                                    $booking_ids = rtrim($val['reser'] , ',');
                                    $booking_ids = explode(',', $val['reser']);

                                    foreach($booking_ids as $booking_id)
                                    {
                                        if($booking_id != ""){
                                            $getRe =  $this->api_model->getReservationById($booking_id,$channel);
                                            $channel_resp[] = $this->getSingleXMLResponse($getRe,$channel);
                                        }
                                    }
                                }else{
                                    $channel_resp[] = array(
                                            'ChannelId' => $channel,
                                            'Status' => 'false',
                                            'Error' => $val['content'],
                                        );
                                }
                            }
                        }
                    }
                }
                $this->response(array('GetBookings'=>$channel_resp), 200);
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('GetBookings'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetBookings'=>$error), 404);
        }
    }

    function getXMLResponse($response,$id)
    {
       // print_r($response);
        if($id == 11)
        {
            foreach($response as $room => $value){

                foreach($value as $resp => $val)
                {
                    if($resp == "import_reserv_id" || $resp == "user_id" || $resp == "hotel_id")
                    {
                        unset($value[$resp]);
                    }else if($resp == "channel_id")
                    {
                        $XMLresponse['ChannelId'] = $value['channel_id'];
                        
                        unset($value['channel_id']);
                    }else{                    
                        $XMLresponse[$resp] = $value[$resp];
                    }
                }
                $bookingresponse[] = $XMLresponse;
            }
            return $bookingresponse;
        }
        if($id == 1)
        {
            foreach($response as $resid => $value)
            {
                foreach($value as $resp => $val)
                {
                    if($resp == "import_reserv_id" || $resp == "user_id" || $resp == "hotel_id")
                    {
                        unset($value[$resp]);
                    }
                    $XMLresponse['ChannelID'] = $value['channel_id'];
                    $XMLresponse['Booking']['attributes'] = array(
                            'Id' => $value['booking_id'],
                            'type' => $value['type'],
                            'createDateTime' => $value['created_time'],
                            'source' => $value['source'],
                            'status' => $value['status'],
                        );
                    $XMLresponse['Hotel']['attributes'] = array(
                            'Id' => $value['hotelid'],
                        );
                    $XMLresponse['RoomStay']['attributes'] = array(
                            'roomTypeID' => $value['roomTypeID'],
                            'ratePlanID' => $value['ratePlanID'],
                        );
                    $XMLresponse['RoomStay']['StayDate']['attributes'] = array(
                            'arrival' => $value['arrival'],
                            'departure' => $value['departure'],
                        );
                    $XMLresponse['RoomStay']['GuestCount']['attributes'] = array(
                            'adult' => $value['adult'],
                            'child' => $value['child'],
                            'childAge' => $value['child_age'],
                        );
                    $XMLresponse['RoomStay']['PerDayRates']['attributes'] = array(
                            'currency' => $value['currency'],
                        );
                    $staydates = explode(',', $value['stayDate']);
                    $baserates = explode(',', $value['baseRate']);
                    $promonames = explode(',', $value['promoName']);

                    for($i=0; $i<count($staydates); $i++)
                    {
                        $XMLresponse['RoomStay']['PerDayRates'][$i]['attributes'] = array(
                                'stayDate' => $staydates[$i],
                                'baseRate' => $baserates[$i],
                                'promoName' => $promonames[$i],
                            ); 
                    }
                    $XMLresponse['RoomStay']['Total']['attributes'] = array(
                            'amountAfterTaxes' => $value['amountAfterTaxes'],
                            'amountOfTaxes' => $value['amountOfTaxes'],
                            'currency' => $value['currency'],
                        );
                    $XMLresponse['RoomStay']['PaymentCard']['attributes'] = array(
                            'cardCode' => $value['cardCode'],
                            'cardNumber' => $value['cardNumber'],
                            'expireDate' => $value['expireDate'],
                        );
                    $XMLresponse['RoomStay']['PaymentCard']['CardHolder']['attributes'] = array(
                            'name' => $value['name'],
                            'address' => $value['address'],
                            'city' => $value['city'],
                            'stateProv' => $value['stateProv'],
                            'country' => $value['country'],
                            'postalCode' => $value['postalCode'],
                        );  
                    $XMLresponse['PrimaryGuest']['Name']['attributes'] = array(
                            'givenName' => $value['givenName'],
                            'middleName' => $value['middleName'],
                            'surname' => $value['surname'],
                        );

                    $specialrequest = explode(',', $value['SpecialRequest']);
                    for($i=0; $i<count($specialrequest); $i++)
                    {
                        $XMLresponse['SpecialRequests'][$i]= $specialrequest[$i];
                    }
                }
                $bookingresponse[] = $XMLresponse;
            }
            return $bookingresponse;
        }
        if($id == 8)
        {
            foreach($response as $value){
                foreach($value as $resp => $val)
                {
                    if($resp == "import_reserv_id" || $resp == "user_id" || $resp == "hotel_id")
                    {
                        unset($value[$resp]);
                    }
                    $XMLresponse['ChannelID'] = $value['channel_id'];
                    $XMLresponse['Booking']['attributes'] = array(
                            'Id' => $value['booking_id'],
                            'BookingRef' => $value['booking_ref'],
                            'Status' => $value['status'],
                            'ContractId' => $value['contractid'],
                            'ContractType' => $value['contracttype'],
                            'RatePlanId' => $value['rateplanid'],
                            'RatePlanCode' => $value['reateplancode'],
                            'RatePlanName' => $value['reateplanname'],
                            'PropertyId' => $value['hotelid'],
                            'PropertyName' => $value['propertyname'],
                            'City' => $value['city'],
                            'ArrivalDate' => $value['arrdate'],
                            'DepartureDate' => $value['depdate'],
                            'Nights' => $value['nights'],
                            'LeadName' => $value['leadname'],
                            'TotalAdults' => $value['adults'],
                            'TotalChildren' => $value['children'],
                            'TotalCots' => $value['totalkcots'],
                            'TotalCost' => $value['totalcost'],
                            'TotalRoomsCost' => $value['totalroomcost'],
                            'TotalOffers' => $value['offer'],
                            'TotalSupplements' => $value['totalsubliment'],
                            'TotalExtras' => $value['totalextra'],
                            'TotalAdjustments' => $value['adjestments'],
                            'TotalTax' => $value['totaltax'],
                            'CurrencyCode' => $value['currencycode'],
                            'ModifiedDate' => $value['modifieddate'],
                        );
                    /*$XMLresponse['Booking']['Contact']['attributes'] = array(
                            'Name' => $value['name'],
                            'Email' => $value['email'],
                        );*/
                    $rooms = explode(',', rtrim($value['room_id'],','));
                    $categories = explode(',', rtrim($value['roomcategory'],','));                
                    
                    $quantities = explode(',', rtrim($value['room_qty'],','));
                    $roomcost = json_decode($value['room_costdetils']);
                    
                    for($i=0; $i<count($rooms); $i++)
                    {
                        $XMLresponse['Booking']['Rooms'][$i]['attributes'] = array(
                                'Id' => $rooms[$i],
                                'RoomCategory' => $categories[$i],
                                'Quantity' => $quantities[$i],
                            );
                        
                        $k = 0;
                        foreach($roomcost as $cost)
                        {
                            foreach($cost as $key => $val)
                            {
                                if($key == "date")
                                {
                                    $XMLresponse['Booking']['Rooms'][$i]['Rates']['StayDates'][$k]['attributes'] = array(
                                        $key => $val,
                                    );
                                }else{
                                    
                                    if(is_object($val))
                                    {
                                        foreach($val as $v)
                                            $XMLresponse['Booking']['Rooms'][$i]['Rates']['StayDates'][$k][$key] = (string)$v;
                                               
                                    }else{
                                        $XMLresponse['Booking']['Rooms'][$i]['Rates']['StayDates'][$k][$key] = $val;
                                    }
                                }
                            }
                                $k++;
                        }
                        
                    }
                    $passengers = json_decode($value['passenger_names']);
                    $o = 1;
                    foreach($passengers as $pass)
                    {
                        foreach($pass->name as $name)
                        

                        if(!is_object($pass->age))
                        {
                            $XMLresponse['Booking']['Passengers'][$o]['attributes'] = array(
                                    'Name' => (string)$name,
                                    'Age' => (string)$pass->age,
                                );
                        }else{
                            foreach($pass->age as $age)
                            $XMLresponse['Booking']['Passengers'][$o]['attributes'] = array(
                                    'Name' => (string)$name,
                                    'Age' => (string)$age,
                                );
                        }
                        $o++;
                    }
                    $XMLresponse['Booking']['ModifiedDate']['attributes'] = array(
                            'Date' => $value['modifieddate'],
                        );
                    //print_r($XMLresponse);

                }
                $bookingresponse[] = $XMLresponse;
            }
            
            return $bookingresponse;
        }
        if($id == 2)
        {
            foreach($response as $room => $value)
            {
                //print_r($value);
                foreach($value as $resp => $val){
                    if($resp == "import_reserv_id" || $resp == "user_id" || $resp == "hotel_hotel_id")
                    {
                        unset($value[$resp]);
                    }
                    $XMLresponse['ChannelID'] = $value['channel_id'];

                    $XMLresponse['reservations']['reservation']['commissionamount'] = $value['commissionamount'];
                    $XMLresponse['reservations']['reservation']['currencycode'] = $value['currencycode'];
                    $XMLresponse['reservations']['reservation']['customer']['address'] = $value['address'];
                    $XMLresponse['reservations']['reservation']['customer']['cc_cvc'] = $value['cc_cvc'];
                    $XMLresponse['reservations']['reservation']['customer']['cc_expiration_date'] = $value['cc_expiration_date'];
                    $XMLresponse['reservations']['reservation']['customer']['cc_name'] = $value['cc_name'];
                    $XMLresponse['reservations']['reservation']['customer']['cc_number'] = $value['cc_number'];
                    $XMLresponse['reservations']['reservation']['customer']['cc_type'] = $value['cc_type'];
                    $XMLresponse['reservations']['reservation']['customer']['city'] = $value['city'];
                    $XMLresponse['reservations']['reservation']['customer']['company'] = $value['company'];
                    $XMLresponse['reservations']['reservation']['customer']['countrycode'] = $value['countrycode'];
                    $XMLresponse['reservations']['reservation']['customer']['dc_issue_number'] = $value['dc_issue_number'];
                    $XMLresponse['reservations']['reservation']['customer']['dc_start_date'] = $value['dc_start_date'];
                    $XMLresponse['reservations']['reservation']['customer']['email'] = $value['email'];
                    $XMLresponse['reservations']['reservation']['customer']['first_name'] = $value['first_name'];
                    $XMLresponse['reservations']['reservation']['customer']['last_name'] = $value['last_name'];
                    $XMLresponse['reservations']['reservation']['customer']['remarks'] = $value['remarks'];
                    $XMLresponse['reservations']['reservation']['customer']['telephone'] = $value['telephone'];
                    $XMLresponse['reservations']['reservation']['customer']['zip'] = $value['zip'];
                    $XMLresponse['reservations']['reservation']['date'] = $value['date'];
                    $XMLresponse['reservations']['reservation']['hotel_id'] = $value['hotel_id'];   
                    $XMLresponse['reservations']['reservation']['hotel_name'] = $value['hotel_name'];
                    $XMLresponse['reservations']['reservation']['id'] = $value['id']; 
                    $flags = explode('###', $value['flags']);
                    for($i=0;$i<count($flags);$i++)
                    {
                        $XMLresponse['reservations']['reservation']['reservation_extra_info']['flags'][$i]['attributes'] = array(
                                'name' => $flags[$i],
                            );
                    }
                    $l = 1;
                    foreach($value['Rooms'] as $roomd)
                    {
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['arrival_date'] = $roomd['arrival_date'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['commissionamount'] = $roomd['commissionamount'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['currencycode'] = $roomd['currencycode'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['departure_date'] = $roomd['departure_date'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['extra_info'] = $roomd['extra_info'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['facilities'] = $roomd['facilities'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['facilities'] = $roomd['facilities'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['guest_name'] = $roomd['guest_name'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['id'] = $roomd['id'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['info'] = $roomd['info'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['max_children'] = $roomd['max_children'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['meal_plan'] = $roomd['meal_plan'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['name'] = $roomd['name'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['numberofguests'] = $roomd['numberofguests'];
                        $pricesa = explode('##', $roomd['day_price_detailss']);
                        $array = array();
                        for($i=0; $i<count($pricesa); $i++)
                        {
                            $prices = explode('~', $pricesa[$i]);
                            for($j=0;$j<count($prices); $j++){
                                if(strpos($prices[$j],'=') !== FALSE)
                                {
                                    $attr = explode('=', $prices[$j]);
                                    $array[$attr[0]] = $attr[1];
                                    //$XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['price']['attributes'] = array(
                                            //$attr[0] => $attr[1],
                                        //);
                                }else{
                                    $XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['attributes']['aomunt'] = $prices[$j];
                                }
                                foreach($array as $ke => $va){
                                    $XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['attributes'][$ke] = $va;
                                }
                            }
                        }

                        $XMLresponse['reservations']['reservation']['rooms'][$l]['remarks'] = $roomd['remarks'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['roomreservation_id'] = $roomd['roomreservation_id'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['smoking'] = $roomd['smoking'];
                        $XMLresponse['reservations']['reservation']['rooms'][$l]['totalprice'] = $roomd['totalprice'];
                        if(isset($roomd['Addons']))
                        {
                            $a = 1;
                            foreach($roomd['Addons'] as $addon)
                            {
                                $add = json_decode($addon['addons_values']);
                                foreach($add as $k=>$v)
                                {
                                   $XMLresponse['reservations']['reservation']['rooms'][$l]['addons'][$a][$k] = $v;
                                }
                                $a++;
                            }
                        }
                        $l++;
                    }
                    $XMLresponse['reservations']['reservation']['status'] = $value['status'];
                    $XMLresponse['reservations']['reservation']['time'] = $value['time'];
                    $XMLresponse['reservations']['reservation']['totalprice'] = $value['totalprice'];
                    if($value['total_cancellation_fee'] != 0)
                    {
                        $XMLresponse['reservations']['reservation']['total_cancellation_fee'] = $value['total_cancellation_fee'];
                    }                    
                }
                $bookingresponse[] = $XMLresponse;
            }
            return $bookingresponse;
        }
        if($id == 5)
        {
            foreach($response as $room => $value)
            {
                foreach($value as $resp => $val){
                    if($resp == "import_reserv_id" || $resp == "user_id" || $resp == "hotel_id")
                    {
                        unset($value[$resp]);
                    }
                    $XMLresponse['ChannelID'] = $value['channel_id'];
                    $XMLresponse['Booking']['attributes'] = array(
                            'echoToken' => $value['echoToken'],
                        );
                    $XMLresponse['Booking']['Establishment']['attributes'] = array(
                            'code' => $value['Establishment_code'],
                        );
                    $XMLresponse['Booking']['Establishment']['Reference'] = array(
                            'FileNumber' => $value['FileNumber'],
                            'IncomingOffice' => $value['IncomingOffice'],
                            'RefNumber' => $value['RefNumber'],
                        );
                    $XMLresponse['Booking']['Establishment']['Status'] = $value['Status'];
                    $XMLresponse['Booking']['Establishment']['CreationDate'] = $value['CreationDate'];
                    $XMLresponse['Booking']['Establishment']['CheckInDate'] = $value['CheckInDate'];
                    $XMLresponse['Booking']['Establishment']['LOS'] = $value['LOS'];
                    $XMLresponse['Booking']['Establishment']['EstablishmentInfo'] = array(
                            'Code' => $value['EstablishmentInfo_Code'],
                            'Name' => $value['EstablishmentInfo_Name'],
                        );
                    $XMLresponse['Booking']['Establishment']['Room']['attributes'] = array(
                            'code' => $value['Room_code'],
                        );
                    $XMLresponse['Booking']['Establishment']['Room'] = array(
                            'Type' => $value['Room_Type'],
                            'BoardTypeCode' => $value['BoardTypeCode'],
                            'BoardType' => $value['BoardType'],
                            'CharacteristicCode' => $value['CharacteristicCode'],
                            'Characteristic' => $value['Characteristic'],
                            'Remarks' =>  $value['Remarks'],
                            'BaseBoardTypeCode' => $value['BaseBoardTypeCode'],
                            'BaseBoardType' => $value['BaseBoardType'],
                        );
                    $XMLresponse['Booking']['Establishment']['Room']['Occupancy'] = array(
                            'AdultCount' => $value['AdultCount'],
                            'ChildCount' => $value['ChildCount'],
                        );
                    $XMLresponse['Booking']['Establishment']['Room']['Contract'] = array(
                            'Code' => $value['Contract_Code'],
                            'Name' => $value['Contract_Name'],
                            'Description' => $value['Contract_Description'],
                        );
                    $XMLresponse['Booking']['Establishment']['Room']['DateFrom'] = $value['DateFrom'];
                    $XMLresponse['Booking']['Establishment']['Room']['DateTo'] = $value['DateTo'];
                    $currency = explode(',', $value['Currency']);
                    $rateprice = explode(',', $value['Rate_DateFrom']);
                    $ratecode = explode(',', $value['Rate_code']);
                    $rateendprice = explode(',', $value['Rate_DateTo']);
                    $priceperdate = explode(',', $value['Amount']);
                    for($i=0;$i<count($ratecode);$i++)
                    {
                        $XMLresponse['Booking']['Establishment']['Room']['Rates'][$i]['attributes'] = array(
                                'code' => $ratecode[$i],
                            );
                        $XMLresponse['Booking']['Establishment']['Room']['Rates'][$i] = array(
                                'DateFrom' => $rateprice[$i],
                                'DateTo' => $rateendprice[$i],
                                'Amount' => $priceperdate[$i],
                                'Currency' => $currency[$i],
                            );
                    }         
                    $XMLresponse['Booking']['Establishment']['Room']['NumberOfUnits'] = $value['NumberOfUnits'];
                    $XMLresponse['Booking']['Establishment']['Room']['Status'] = $value['RoomStatus'];
                    $XMLresponse['Booking']['Establishment']['Room']['OrderNumber'] = $value['OrderNumber'];
                    $XMLresponse['Booking']['Establishment']['Holder'] = $value['Holder'];

                    $bookingresponse[] = $XMLresponse;

                }
            }
            return $bookingresponse;
        }

    }
    function getSingleXMLResponse($response,$id)
    {
       // print_r($response);
        if($id == 11)
        {
            foreach($response as $resp => $value){
                
                if($resp == "import_reserv_id" || $resp == "partner_id" || $resp == "property_id")
                {
                    unset($response[$resp]);
                }else if($resp == "channel_id")
                {
                    $XMLresponse['ChannelId'] = $response['channel_id'];
                    
                    unset($response['channel_id']);
                }else{                    
                    $XMLresponse[$resp] = $response[$resp];
                }
                
            }
            return $XMLresponse;
        }
        if($id == 1)
        {
            foreach($response as $resp => $value)
            {
            
                if($resp == "import_reserv_id" || $resp == "partner_id" || $resp == "property_id")
                {
                    unset($response[$resp]);
                }
                $XMLresponse['ChannelID'] = $response['channel_id'];
                $XMLresponse['Booking']['attributes'] = array(
                        'Id' => $response['booking_id'],
                        'type' => $response['type'],
                        'createDateTime' => $response['created_time'],
                        'source' => $response['source'],
                        'status' => $response['status'],
                    );
                $XMLresponse['Hotel']['attributes'] = array(
                        'Id' => $response['hotelid'],
                    );
                $XMLresponse['RoomStay']['attributes'] = array(
                        'roomTypeID' => $response['roomTypeID'],
                        'ratePlanID' => $response['ratePlanID'],
                    );
                $XMLresponse['RoomStay']['StayDate']['attributes'] = array(
                        'arrival' => $response['arrival'],
                        'departure' => $response['departure'],
                    );
                $XMLresponse['RoomStay']['GuestCount']['attributes'] = array(
                        'adult' => $response['adult'],
                        'child' => $response['child'],
                        'childAge' => $response['child_age'],
                    );
                $XMLresponse['RoomStay']['PerDayRates']['attributes'] = array(
                        'currency' => $response['currency'],
                    );
                $staydates = explode(',', $response['stayDate']);
                $baserates = explode(',', $response['baseRate']);
                $promonames = explode(',', $response['promoName']);

                for($i=0; $i<count($staydates); $i++)
                {
                    $XMLresponse['RoomStay']['PerDayRates'][$i]['attributes'] = array(
                            'stayDate' => $staydates[$i],
                            'baseRate' => $baserates[$i],
                            'promoName' => $promonames[$i],
                        ); 
                }
                $XMLresponse['RoomStay']['Total']['attributes'] = array(
                        'amountAfterTaxes' => $response['amountAfterTaxes'],
                        'amountOfTaxes' => $response['amountOfTaxes'],
                        'currency' => $response['currency'],
                    );
                $XMLresponse['RoomStay']['PaymentCard']['attributes'] = array(
                        'cardCode' => $response['cardCode'],
                        'cardNumber' => $response['cardNumber'],
                        'seriesCode' => $response['seriesCode'],
                        'expireDate' => $response['expireDate'],
                    );
                $XMLresponse['RoomStay']['PaymentCard']['CardHolder']['attributes'] = array(
                        'name' => $response['name'],
                        'address' => $response['address'],
                        'city' => $response['city'],
                        'stateProv' => $response['stateProv'],
                        'country' => $response['country'],
                        'postalCode' => $response['postalCode'],
                    );  
                $XMLresponse['PrimaryGuest']['Name']['attributes'] = array(
                        'givenName' => $response['givenName'],
                        'middleName' => $response['middleName'],
                        'surname' => $response['surname'],
                    );

                $specialrequest = explode(',', $response['SpecialRequest']);
                for($i=0; $i<count($specialrequest); $i++)
                {
                    $XMLresponse['SpecialRequests'][$i]= $specialrequest[$i];
                }
                
               
            }
            return $XMLresponse;
        }
        if($id == 8)
        {
            foreach($response as $resp => $value){
                if($resp == "import_reserv_id" || $resp == "partner_id" || $resp == "property_id")
                {
                    unset($response[$resp]);
                }
                $XMLresponse['ChannelID'] = $response['channel_id'];
                $XMLresponse['Booking']['attributes'] = array(
                        'Id' => $response['booking_id'],
                        'BookingRef' => $response['booking_ref'],
                        'Status' => $response['status'],
                        'ContractId' => $response['contractid'],
                        'ContractType' => $response['contracttype'],
                        'RatePlanId' => $response['rateplanid'],
                        'RatePlanCode' => $response['reateplancode'],
                        'RatePlanName' => $response['reateplanname'],
                        'PropertyId' => $response['hotelid'],
                        'PropertyName' => $response['propertyname'],
                        'City' => $response['city'],
                        'ArrivalDate' => $response['arrdate'],
                        'DepartureDate' => $response['depdate'],
                        'Nights' => $response['nights'],
                        'LeadName' => $response['leadname'],
                        'TotalAdults' => $response['adults'],
                        'TotalChildren' => $response['children'],
                        'TotalCots' => $response['totalkcots'],
                        'TotalCost' => $response['totalcost'],
                        'TotalRoomsCost' => $response['totalroomcost'],
                        'TotalOffers' => $response['offer'],
                        'TotalSupplements' => $response['totalsubliment'],
                        'TotalExtras' => $response['totalextra'],
                        'TotalAdjustments' => $response['adjestments'],
                        'TotalTax' => $response['totaltax'],
                        'CurrencyCode' => $response['currencycode'],
                        'ModifiedDate' => $response['modifieddate'],
                    );
                $XMLresponse['Booking']['Contact']['attributes'] = array(
                        'Name' => $response['name'],
                        'Email' => $response['email'],
                    );
                $rooms = explode(',', rtrim($response['room_id'],','));
                $categories = explode(',', rtrim($response['roomcategory'],','));                
                $roomtypes = explode(',', rtrim($response['roomtypes'],','));
                $quantities = explode(',', rtrim($response['room_qty'],','));
                $roomcost = explode(',', rtrim($response['room_costdetils'],','));
                $roomavail = explode(',', rtrim($response['room_avail'],','));

                for($i=0; $i<count($rooms); $i++)
                {
                    $XMLresponse['Booking']['Rooms'][$i]['attributes'] = array(
                            'Id' => $rooms[$i],
                            'RoomCategory' => $categories[$i],
                            'RoomType'=> $roomtypes[$i],
                            'Quantity' => $quantities[$i],
                        );
                    $avail = explode('~', rtrim($roomavail[$i],'~'));
                    for($j=0;$j<count($avail);$j++)
                    {
                        $availa = explode("##", $avail[$j]);
                        $XMLresponse['Booking']['Rooms'][$i]['Availability']['StayDates'][$j]['attributes'] = array(
                                'Date' => $availa[0],
                                'Available' => $availa[1],
                            );
                    }
                    $rates = explode('&&', rtrim($roomcost[$i],'&&'));
                    for($k=0; $k<count($rates); $k++)
                    {
                        $rate = explode('~', rtrim($rates[$k],'~'));
                        for($m=0; $m<count($rate); $m++)
                        {
                            $field = explode('##', $rate[$m]);
                            $XMLresponse['Booking']['Rooms'][$i]['Rates']['StayDates'][$k]['attributes'][$m] = array(
                                $field[0] => $field[1],
                            );
                        }                        
                    }
                    
                }
                $passengers = json_decode($response['passenger_names']);
                $o = 1;
                foreach($passengers as $pass)
                {
                    foreach($pass->name as $name)
                    foreach($pass->age as $age)
                    $XMLresponse['Booking']['Passengers'][$o]['attributes'] = array(
                            'Name' => (string)$name,
                            'Age' => (string)$age,
                        );
                    $o++;
                }
                $XMLresponse['Booking']['ModifiedDate']['attributes'] = array(
                        'Date' => $response['modifieddate'],
                    );
            }
            return $XMLresponse;
        }
        if($id == 2)
        {
            foreach($response as $resp => $value)
            {

                if($resp == "import_reserv_id" || $resp == "partner_id" || $resp == "property_id")
                {
                    unset($response[$resp]);
                }
                $XMLresponse['ChannelID'] = $response['channel_id'];

                $XMLresponse['reservations']['reservation']['commissionamount'] = $response['commissionamount'];
                $XMLresponse['reservations']['reservation']['currencycode'] = $response['currencycode'];
                $XMLresponse['reservations']['reservation']['customer']['address'] = $response['address'];
                $XMLresponse['reservations']['reservation']['customer']['cc_cvc'] = $response['cc_cvc'];
                $XMLresponse['reservations']['reservation']['customer']['cc_expiration_date'] = $response['cc_expiration_date'];
                $XMLresponse['reservations']['reservation']['customer']['cc_name'] = $response['cc_name'];
                $XMLresponse['reservations']['reservation']['customer']['cc_number'] = $response['cc_number'];
                $XMLresponse['reservations']['reservation']['customer']['cc_type'] = $responseresponse['cc_type'];
                $XMLresponse['reservations']['reservation']['customer']['city'] = $response['city'];
                $XMLresponse['reservations']['reservation']['customer']['company'] = $response['company'];
                $XMLresponse['reservations']['reservation']['customer']['countrycode'] = $response['countrycode'];
                $XMLresponse['reservations']['reservation']['customer']['dc_issue_number'] = $response['dc_issue_number'];
                $XMLresponse['reservations']['reservation']['customer']['dc_start_date'] = $response['dc_start_date'];
                $XMLresponse['reservations']['reservation']['customer']['email'] = $response['email'];
                $XMLresponse['reservations']['reservation']['customer']['first_name'] = $response['first_name'];
                $XMLresponse['reservations']['reservation']['customer']['last_name'] = $response['last_name'];
                $XMLresponse['reservations']['reservation']['customer']['remarks'] = $response['remarks'];
                $XMLresponse['reservations']['reservation']['customer']['telephone'] = $response['telephone'];
                $XMLresponse['reservations']['reservation']['customer']['zip'] = $response['zip'];
                $XMLresponse['reservations']['reservation']['date'] = $response['date'];
                $XMLresponse['reservations']['reservation']['hotel_id'] = $response['hotel_id'];   
                $XMLresponse['reservations']['reservation']['hotel_name'] = $response['hotel_name'];
                $XMLresponse['reservations']['reservation']['id'] = $response['id']; 
                $flags = explode('###', $response['flags']);
                for($i=0;$i<count($flags);$i++)
                {
                    $XMLresponse['reservations']['reservation']['reservation_extra_info']['flags'][$i]['attributes'] = array(
                            'name' => $flags[$i],
                        );
                }
                $l = 1;
                foreach($value['Rooms'] as $roomd)
                {
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['arrival_date'] = $roomd['arrival_date'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['commissionamount'] = $roomd['commissionamount'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['currencycode'] = $roomd['currencycode'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['departure_date'] = $roomd['departure_date'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['extra_info'] = $roomd['extra_info'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['facilities'] = $roomd['facilities'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['facilities'] = $roomd['facilities'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['guest_name'] = $roomd['guest_name'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['id'] = $roomd['id'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['info'] = $roomd['info'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['max_children'] = $roomd['max_children'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['meal_plan'] = $roomd['meal_plan'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['name'] = $roomd['name'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['numberofguests'] = $roomd['numberofguests'];
                    $pricesa = explode('##', $roomd['day_price_detailss']);
                    for($i=0; $i<count($pricesa); $i++)
                    {
                        $prices = explode('~', $pricesa[$i]);
                        for($j=0;$j<count($prices); $j++){
                            if(strpos($prices[$j],'=') !== FALSE)
                            {
                                $attr = explode('=', $prices[$j]);
                                $array[$attr[0]] = $attr[1];
                                //$XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['price']['attributes'] = array(
                                        //$attr[0] => $attr[1],
                                    //);
                            }else{
                                $XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['attributes']['aomunt'] = $prices[$j];
                            }
                            foreach($array as $ke => $va){
                                $XMLresponse['reservations']['reservation']['rooms'][$l]['prices'][$i]['attributes'][$ke] = $va;
                            }
                        }
                    }

                    $XMLresponse['reservations']['reservation']['rooms'][$l]['remarks'] = $roomd['remarks'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['roomreservation_id'] = $roomd['roomreservation_id'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['smoking'] = $roomd['smoking'];
                    $XMLresponse['reservations']['reservation']['rooms'][$l]['totalprice'] = $roomd['totalprice'];
                    if(isset($roomd['Addons']))
                    {
                        $a = 1;
                        foreach($roomd['Addons'] as $addon)
                        {
                            $add = json_decode($addon['addons_values']);
                            foreach($add as $k=>$v)
                            {
                               $XMLresponse['reservations']['reservation']['rooms'][$l]['addons'][$a][$k] = $v;
                            }
                            $a++;
                        }
                    }
                    $l++;
                }
                $XMLresponse['reservations']['reservation']['status'] = $response['status'];
                $XMLresponse['reservations']['reservation']['time'] = $response['time'];
                $XMLresponse['reservations']['reservation']['totalprice'] = $response['totalprice'];
                if($value['total_cancellation_fee'] != 0)
                {
                    $XMLresponse['reservations']['reservation']['total_cancellation_fee'] = $response['total_cancellation_fee'];
                }   
            }
            /*print_r($bookingresponse);
            die;*/
            return $XMLresponse;
        }

    }

    /*function getAllBookings_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        
        $hotel_id = (string)$xmlData->GetBookings->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $partner_id = $partner->row()->partnar_id;
                $owner_id = $property_exists->row()->owner_id;
                $dateFrom = $xmlData->GetBookings->DateFrom;
                $dateTo = $xmlData->GetBookings->DateTo;
                if($dateFrom != "" && $dateTo != "")
                {
                    if(strtotime($dateFrom) > strtotime($dateTo))
                    {
                        $error = array(
                                'Status' => 'false',
                                'Error' => 'From Date Must be less than the To Date',
                            );
                        $this->response(array('GetBookings'=>$error), 404);
                        exit;
                    }
                }
                $channel_id = "";
                $channel_id = $xmlData->GetBookings->ChannelID;
                if($xmlData->GetBookings->ChannelID){
                    foreach($xmlData->GetBookings->ChannelID as $channel_id)
                    {
                        $response = $this->api_model->getResrvationFromDb($owner_id,$hotel_id,$dateFrom,$dateTo,(string)$channel_id);
                    }
                }else{
                    $response = $this->api_model->getResrvationFromDb($owner_id,$hotel_id,$dateFrom,$dateTo,$channel_id);
                }
                foreach ($response as $channel => $value) {
                    //print_r($value);

                    if(count($value) != 0){
                        $dataresponse = $this->getXMLResponse($response[$channel],$channel);
                        
                        if(count($dataresponse) != 0)
                        {
                            foreach($dataresponse as $daresp)
                            {
                                $channel_resp[] = $daresp;
                            }
                        }
                    }else{
                        $channel_resp[] = array(
                                'ChannelId' => $channel,
                                'Status' => 'true',
                                'Message' => "Zero Results",
                            );
                    }
                }
                $this->response(array('GetBookings'=>$channel_resp),200);
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('GetBookings'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetBookings'=>$error), 404);
        }
    }*/

    function getAllBookings_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        
        $hotel_id = (string)$xmlData->GetBookings->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $partner_id = $partner->row()->partnar_id;
                $owner_id = $property_exists->row()->owner_id;
                $dateFrom = $xmlData->GetBookings->DateFrom;
                $dateTo = $xmlData->GetBookings->DateTo;
                if($dateFrom != "" && $dateTo != "")
                {
                    if(strtotime($dateFrom) > strtotime($dateTo))
                    {
                        $error = array(
                                'Status' => 'false',
                                'Error' => 'From Date Must be less than the To Date',
                            );
                        $this->response(array('GetBookings'=>$error), 404);
                        exit;
                    }
                }
                $channel_id = "";
                $channel_id = $xmlData->GetBookings->ChannelID;
                if($xmlData->GetBookings->ChannelID){
                    foreach($xmlData->GetBookings->ChannelID as $channel_id)
                    {
                        $response = $this->api_model->getResrvationFromDbpms($owner_id,$hotel_id,$dateFrom,$dateTo,(string)$channel_id);
                    }
                }else{
                    $response = $this->api_model->getResrvationFromDbpms($owner_id,$hotel_id,$dateFrom,$dateTo,$channel_id);
                }
                foreach($response as $channel => $value)
                {
                    if(count($value) != 0)
                    {
                        foreach($value as $val){
                           
                            $channel_resp['Bookings'][] = array(
                                    'ChannelId' => $val->channel_id,
                                    'ReservaionId' => $val->reservation_id,
                                    'ReservationCode' => $val->reservation_code,
                                    'Status' => $val->status,
                                    'GuestName' => $val->guest_name,
                                    'RoomTypeId' => $val->room_id,
                                    'ChannelRoomId' => $val->channelroomid,
                                    'CheckIn' => date('Y-m-d',strtotime($val->start_date)),
                                    'CheckOut' => date('Y-m-d',strtotime($val->end_date)),
                                    'BookingDate' => $val->booking_date,
                                    'Currency' => $val->currency_id,
                                    'Price' => $val->price,
                                    'CCName' => $val->ccname,
                                    'CCNumber' => $val->ccnumber,
                                    'CCCvv' => $val->cccvc,
                                    'CCType' => $val->cctype,
                                    'CCDate' => $val->ccdate,
                                    'CCYear' => $val->ccyear,
                                ); 
                        }
                    }
                }
                
                $this->response(array('GetBookings'=>$channel_resp),200);
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('GetBookings'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('GetBookings'=>$error), 404);
        }
    }

    function setBookings_post()
    {
        $resp = trim(file_get_contents('php://input'));
        $xmlData = simplexml_load_string($resp);
        $apikey = (string)$xmlData->ApiKey;
        $password = (string)$xmlData->Password;
        
        $hotel_id = (string)$xmlData->SetBookings->PropertyId;
        $partner = get_data(PMS_PART,array('apikey'=>$apikey,'password' => $password));
        if($partner->num_rows() != 0)
        {
            $property_exists = get_data(PMS_PART_HOTEL,array('property_id'=>$hotel_id,'partner_id'=>$partner->row()->partnar_id));
            if($property_exists->num_rows() != 0)
            {
                $bookings = $xmlData->SetBookings->Bookings->Booking;
               
                if($this->validate($bookings))
                {
                    foreach($bookings as $booking)
                    {
                        $checkin = date('Y/m/d',strtotime($booking->CheckIn));
                        $checkout = date('Y/m/d',strtotime($booking->CheckOut));
                
                        $nights =   _datebetween($checkin,$checkout);
                        if($booking->Rooms)
                        {
                            $rooms = @$booking->Rooms->Room;
                            if(count($rooms) != 0)
                            {
                                foreach($rooms as $room)
                                {
                                    $data=array(
                                        'reservation_code'=> (string)$booking->ReservationId,
                                        'hotel_id'=>$hotel_id,
                                        'user_id'=>$property_exists->row()->owner_id,
                                        'guest_name'=>(string)$booking->Customer->Name,
                                        'last_name'=>'',
                                        'mobile'=>(string)$booking->Customer->Telephone,
                                        'email'=>(string)$booking->Customer->Email,
                                        'room_id'=>(string)$room->RoomTypeId,
                                        'num_nights'=>$nights,
                                        'num_rooms' => (string)$room->RoomsPerday,
                                        'members_count'=>(string)$room->MembersCount,
                                        'start_date'=>(string)$booking->CheckIn,
                                        'end_date'=>(string)$booking->CheckOut,
                                        'booking_date'=>(string)$booking->Date,
                                        'price'=>(string)$room->Price,
                                        'status' => (string)$booking->Status,
                                        'description'=>(string)$booking->Description,
                                        'currency_id'=>get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>$property_exists->row()->owner_id))->row()->currency))->row()->currency_id,
                                        'exp_month'=>(string)safe_b64encode(@$booking->PaymentCard->CardExpiryMonth),
                                        'card_name'=>(string)safe_b64encode(@$booking->PaymentCard->Name),
                                        'card_type' => (string)safe_b64encode(@$booking->PaymentCard->CardType),
                                        'securitycode' => (string)safe_b64encode(@$booking->PaymentCard->Cvv),
                                        'exp_year'=>(string)safe_b64encode(@$booking->PaymentCard->CardExpiryYear),
                                        'card_number'=>(string)safe_b64encode(@$booking->PaymentCard->CardNumber),
                                    );
                                    $this->api_model->save_reservation($data);
                                   
                                }
                            }else{
                                $error = array(
                                    'Status' => 'false',
                                    'Error' => 'Incorrect XML Request',
                                );
                                $this->response(array('SetBookings'=>$error), 404);
                            }
                        }else{
                             $error = array(
                                    'Status' => 'false',
                                    'Error' => 'Incorrect XML Request',
                                );
                            $this->response(array('SetBookings'=>$error), 404);
                        }   
                    }
                    $error = array(
                            'Status' => 'true',
                            'Success' => 'Reservation Added Successfully',
                        );
                    $this->response(array('SetBookings'=>$error), 200);
                }else{
                    $error = array(
                        'Status' => 'false',
                        'Error' => 'Incorrect XML Request',
                    );
                    $this->response(array('SetBookings'=>$error), 404);
                }
            }else{
                $error = array(
                        'Status' => 'false',
                        'Error' => 'Invalid Property Id',
                    );
                $this->response(array('SetBookings'=>$error), 404);
            }
        }else{
            $error = array(
                        'Status' => 'false',
                        'Error' => 'Authentication Failed',
                    );
            $this->response(array('SetBookings'=>$error), 404);
        }
    }

    function validate($array)
    {
        $booking = array('ReservationId','CheckIn','CheckOut','Status','Date','Rooms','Customer','PaymentCard');
        $rooms = array('RoomTypeId','RoomsPerday','MembersCount','Price','Currency');
        $customer = array('Name','Email','Telephone');
        $payment = array('Name','Cvv','CardNumber','CardType','CardExpiryMonth','CardExpiryYear');
        foreach($array as $value)
        {
            foreach($value as $key => $val)
            {
                if(in_array($key, $booking))
                {
                    if($key == 'Rooms')
                    {
                        foreach($val->Room as $room)
                        {
                            foreach($rooms as $req)
                            {
                                if(!$room->$req)
                                {
                                    echo "Rooms";
                                    return false;
                                }
                            }
                        }
                    }else if($key == "Customer")
                    {
                        foreach($customer as $reqcus)
                        {
                            if(!$val->$reqcus)
                            {
                                
                                return false;
                            }
                        }
                    }else if($key == "PaymentCard")
                    {
                        foreach($payment as $reqpay)
                        {
                            if(!$val->$reqpay)
                            {
                                return false;
                            }
                        }
                    }
                }else{
                    if($key != "PaymentCard")
                    {
                        return false;
                    }
                }
            }

            $start_date = strtotime($value->CheckIn);
            $end_date = strtotime($value->CheckOut);

            if($start_date > $end_date)
            {
                
                return false;
            }

        }
        return true;
    }
}
