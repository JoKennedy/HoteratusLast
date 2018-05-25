<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class api_model extends CI_Model{
  public function __construct()
  {       
      parent::__construct();
      $this->load->model('inventory_model');
  }
public function check_partner($apikey,$email){
	$pass=$apikey;
	$email=$email;
	$sql=$this->db->query('Select * from partners where `apikey`="'.$apikey.'" AND `email`="'.$email.'"');
	if($sql->num_rows==1){
		return $sql->row();
     }else{
     	return "no";
     }
}
public function insert_user($data){
	$email=$data['email_address'];
	$this->db->where('email_address',$email);
	$sql=$this->db->get('manage_users');
	if($sql->num_rows > 0){
      return "no";
	}else{
	 $this->db->insert('manage_users',$data);
      return true;
    }
}

function get_userid($email){
   $this->db->where('email_address',$email);
   $sql=$this->db->get('manage_users');
   if($sql->num_rows>0){
   	return $sql->row()->partner_id;
   } 
}

function getproperty($user,$hotel_id=""){
   if($hotel_id!=""){
    $this->db->where('hotel_id',$hotel_id);   
   }
   $this->db->where('owner_id',$user);
   $sql=$this->db->get('manage_hotel');
   if($sql->num_rows>0){
     return $sql->result();
   } 
}
function getchannel($channel_id=""){
   if($channel_id!=""){
    $this->db->where('channel_id',$channel_id); 	
   }
   $sql=$this->db->get('manage_channel');
   if($sql->num_rows>0){
   	 return $sql->result();
   } 
}

function check_count($uid){
   $this->db->where('partner_id',$uid);
   $sql= $property=$this->db->get('manage_users'); 
   $property=$sql->row()->multiproperty;
   $this->db->where('owner_id',$uid);
   $user= $property=$this->db->get('manage_hotel');
     if($user->num_rows > 0){
         if($property=='Active'){
           return "ok";
         }else{
          return "no";
         }
     }else{
       return "ok";
     }
}

function setproperty($data){
  $this->db->insert('manage_hotel', $data);
  $pid=$this->db->insert_id();
  return $pid;
}

function removemapp($mapps,$hotel_id,$owner_id)
{
    $this->db->where_in('mapping_id',$mapps);
    $this->db->where('owner_id',$owner_id);
    $this->db->where('hotel_id',$hotel_id);
    //$resut = $this->db->get(MAP)->result();
   
    if($this->db->delete(MAP))
    {
       
      return true;
    }else{
      return false;
    }
}



function add_roomtype($data,$rid=""){
  if ($rid!="") {
    $this->db->where('property_id',$rid);
    $this->db->update('manage_property', $data);
    $pid=$rid;
  }else{
    $this->db->insert('manage_property', $data);
    $pid=$this->db->insert_id();
  }
  return $pid; 
}


function check_hotel($pid='',$uid=''){
   if($pid!=""){
     $this->db->where('owner_id',$uid);
     $this->db->where('hotel_id',$pid);
     $fetch=$this->db->get('manage_hotel');
     if($fetch->num_rows > 0 ){
        return "ok";
     }else{
       return "Property Id not found";
     }
   }else{
    return "Property Id missing";
   }
}


public function get_roomtypes($value='')
{
  $this->db->where('hotel_id',$value);
  $fetch = $this->db->get('manage_property');
  if($fetch->num_rows > 0){
       return  $fetch->result();
    }
}

public function setchannel($data)
{
   $channel_id=$data['channel_id'];
   $hotel_id=$data['hotel_id'];
    $check_channel=$this->db->query("Select * from `user_connect_channel` where channel_id=".$channel_id." AND `hotel_id`=".$hotel_id."");
    if($check_channel->num_rows > 0){
         $status=$check_channel->row()->status;
         if($status=='enabled'){
           return "Already Enabled";
         }else{
           $this->db->where('hotel_id',$hotel_id);
           $this->db->where('channel_id',$channel_id);
           $this->db->update('user_connect_channel', $data);  
            return "Updated successfully";
         }
    }else{
      $this->db->insert('user_connect_channel', $data);
      return "Request Sent. Your connetion waiting";
    }
}

public function insert($data){
  $this->db->insert('books', $data);
  return TRUE;
}

// Delete Query
public function delete($id){
  $query = $this->db->query("delete from books where id=$id");
  return TRUE;
}
// Update Query
public function update($data){
  $id= $data['id'];
  $this->db->where('id',$id);
  $this->db->update('books',$data);
}

// PMS Start Gayathri //

  /*function setpmsproperty($data)
  {
      $this->db->insert(PMS_PART_HOTEL, $data);
      $pid=$this->db->insert_id();
      return $pid;
  }*/
  function setpmsproperty($data,$partner_id)
  {
      $this->db->insert(HOTEL, $data);
      $pid=$this->db->insert_id();
      $partner['owner_id'] = $data['owner_id'];
      $partner['partner_id'] = $partner_id;
      $partner['property_id'] = $pid;
      $this->db->insert(PMS_PART_HOTEL,$partner);
      return $pid;
  }

  function insert_rooms($data)
  {
      $this->db->insert(TBL_PROPERTY, $data);
      $pid=$this->db->insert_id();
      return $pid;
  }

  function getChannelRooms($data)
  {
      extract($data);
      $cha_name = get_data(TBL_CHANNEL,('status = "Active" AND channel_id = '.$channel_id))->row()->channel_name;

      if($channel_id=='11')
      {
          if($mode == 0){
              $urls = explode(',', $test_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $reco[$path[0]] = $path[1];
              }
          }else if($mode == 1){
              $urls = explode(',', $live_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $reco[$path[0]] = $path[1];
              }
          }
          $soapUrl = trim($reco['irate_avail']); // asmx URL of WSDL
          $soapUser = $user_name;  //  username
          $soapPassword = $user_password;// password
          $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                              <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                <soap12:Body>
                                  <GetRoomRateCodes xmlns="https://www.reconline.com/">
                                    <User>'.$soapUser.'</User>
                                    <Password>'.$soapPassword.'</Password>
                                    <idHotel>'.$hotel_channel_id.'</idHotel>
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
                  $rdata['user_id'] = $user_id;
                  $rdata['hotel_id'] = $hotel_id;
                  $rdata['channel_id'] = $channel_id;
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
                  
                  $available = get_data(IM_RECO,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$room['IDHOTEL'],'IDROOM'=>$room['IDROOM']))->row_array();
                  if(count($available)!=0)
                  {
                      update_data(IM_RECO,$rdata,array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$room['IDHOTEL'],'IDROOM'=>$room['IDROOM']));
                  }
                  else
                  {
                      insert_data(IM_RECO,$rdata);
                  }
              }
              $meg['result'] = '1';
              $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;
          }
          else
          {
              //echo $Errorarray['WARNINGID'];
              //echo $Errorarray['WARNING'];
              $this->inventory_model->store_error($partner_id,$property_id,insep_decode($channel_id),(string)$Errorarray['WARNING'],'PMS Get Channel',date('m/d/Y h:i:s a', time()));
              $meg['result'] = '0';
              $meg['content']=$Errorarray['WARNING'].' from '.$cha_name.'. Try again!';
              return $meg;
          }
          curl_close($ch); 
      }
      else if($channel_id =='2')
      {
        if($xml_type==2 || $xml_type==3)
        {
          $xml_data ='
                <?xml version="1.0" encoding="UTF-8"?>
                <request>
                <username>'.$user_name.'</username>
                <password>'.$user_password.'</password>
                <hotel_id>'.$hotel_channel_id.'</hotel_id>
                </request>
                ';
          $URL = "https://supply-xml.booking.com/hotels/xml/roomrates";
          $ch = curl_init($URL);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $output = curl_exec($ch);
          $data_api = simplexml_load_string($output); 
          $Errorarray = $data_api->fault;
          if(count($Errorarray)!=0)
          {
            $Errorarray = $data_api->fault->attributes();
            $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Errorarray['string'],'PMS Get Channel',date('m/d/Y h:i:s a', time()));
            $meg['result'] = '0';
            $meg['content']=$Errorarray['string'].' from '.$cha_name.'. Try again!';
            return $meg;
          }
          elseif(count($Errorarray)==0)
          {
            foreach($data_api as $room_details)
            {
              $room_att = $room_details->attributes();
              $bdata['owner_id'] =  $owner_id;
              $bdata['hotel_id'] =  $hotel_id;
              $bdata['channel_id'] = $channel_id;
              $bdata['B_room_id'] = (string)$room_att['id'];
              $bdata['channel_hotel_id'] = (string)$room_att['hotel_id'];
              $bdata['hotel_name']= (string)$room_att['hotel_name'];
              $bdata['max_children'] = (string)$room_att['max_children'];
              $bdata['room_name']= (string)$room_att['room_name'];
              
              $room_available = get_data(BOOKING,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>'0'))->row_array();
              if(count($room_available)!=0)
              {
                update_data(BOOKING,$bdata,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>'0'));
              }
              else
              {
                insert_data(BOOKING,$bdata);
              }
              
              $room_rates = $room_details->rates->rate; 
              foreach($room_rates as $room_rate)
              {
                $rate_att = $room_rate->attributes();
                $brdata['owner_id'] =  $owner_id;
                $brdata['hotel_id'] =  $hotel_id;
                $brdata['channel_id'] = $channel_id;
                $brdata['channel_hotel_id'] = (string)$room_att['hotel_id'];
                $brdata['B_room_id'] = (string)$room_att['id'];
                $brdata['hotel_name']= (string)$room_att['hotel_name'];
                $brdata['room_name']= (string)$room_att['room_name'];
                $brdata['B_rate_id']= (string)$rate_att['id'];
                $brdata['max_persons'] = (string)$rate_att['max_persons'];
                $brdata['policy'] = (string)$rate_att['policy'];
                $brdata['policy_id'] = (string)$rate_att['policy_id'];
                $brdata['rate_name'] = (string)$rate_att['rate_name'];
                
                $rate_available = get_data(BOOKING,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>$rate_att['id']))->row_array();
                if(count($rate_available)!=0)
                {
                  update_data(BOOKING,$brdata,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'channel_hotel_id'=>$room_att['hotel_id'],'B_room_id'=>$room_att['id'],'B_rate_id'=>$rate_att['id']));
                }
                else
                {
                  insert_data(BOOKING,$brdata);
                }
              }
            }
            $meg['result'] = '1';
            $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
            return $meg;
          }
          curl_close($ch); 
        }
        else
        {
          $meg['result'] = '1';
          $meg['content']="Can't import room rate information from ".$cha_name."!!!";
          return $meg;
        }
                
      }
      if($channel_id=='1')
      {          
          if($mode == 0){
              $urls = explode(',', $test_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $exp[$path[0]] = $path[1];
              }
          }else if($mode == 1){
              $urls = explode(',', $live_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $exp[$path[0]] = $path[1];
              }
          }
          $xml_data =' <ProductAvailRateRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/PAR/2013/07">

              <Authentication username="'.$user_name.'" password="'.$user_password.'"/>

              <Hotel id="'.$hotel_channel_id.'"/>

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
          //echo $output;
          $data_api = simplexml_load_string($output);
          //print_r($data_api);
          $response = @$data_api->Error;
          if($response!='')
          {
              $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$response[0],'PMS Get Channel',date('m/d/Y h:i:s a', time()));
              $meg['result'] = '0';
              $meg['content']=$response[0].' from '.$cha_name.'. Try again!';
              return $meg;
          }
          else
          {
              //$this->db->query("truncate import_mapping");
              $Hotelarray = $data_api->ProductList->Hotel;
              $hotel_channel_id=$Hotelarray->attributes()->id;
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
                                  'user_id' => $owner_id,
                                  'channel'=>$channel_id,
                                  'hotel_channel_id'=>$hotel_channel_id,
                                  'hotel_id' => $hotel_id,
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
                              
                  $first_query=$this->db->query('Select * from '.IM_EXP.' where channel="'.$channel_id.'" AND user_id ="'.$user_id.'" AND hotel_id="'.$hotel_id.'" AND hotel_channel_id="'.$hotel_channel_id.'" AND roomtype_id="'.$attr['id'].'" AND rate_type_id=""');
                  if($first_query->num_rows >= 1)
                  {
                      $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'roomtype_id'=>$attr['id'],'rate_type_id'=>''));
                       $this->db->update(IM_EXP,$first_data);
                  }
                  else
                  {
                      $this->db->insert(IM_EXP,$first_data);
                  }

                  foreach ($rateThreshold as $var => $rt) {
                      $data = array(
                              'user_id' => $owner_id,
                              'channel'=>$channel_id,
                              'hotel_channel_id'=>$hotel_channel_id,
                              'hotel_id' => $hotel_id,
                              'roomtype_id'=>(string)$attr['id'],
                              'roomtype_name'=>(string)$attr['name'],
                              'rateType' => (string)$rt->attributes()->type,  
                              'minAmount' => (string)$rt->attributes()->minAmount,
                              'maxAmount' => (string)$rt->attributes()->maxAmount,
                              
                          );
                      $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'roomtype_id'=>$attr['id'],'rateType' => (string)$rt->attributes()->type));
                      $query = $this->db->get(IM_EXP_RATE);
                      if($query->num_rows == 1)
                      {
                           $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'roomtype_id'=>$attr['id'],'rateType' => (string)$rt->attributes()->type));
                           $this->db->update(IM_EXP_RATE,$data);
                      }
                      else
                      {
                          $query2 = $this->db->query('Select * from '.IM_EXP_RATE.' where channel="'.$channel_id.'" AND hotel_channel_id="'.$hotel_channel_id.'" AND roomtype_id="'.$attr['id'].'" AND user_id ="'.$owner_id.'" AND hotel_id="'.$hotel_id.'" AND rateType=""');
                          if($query2->num_rows == 1){
                               $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'roomtype_id'=>$attr['id'],'rateType' => ''));
                               $this->db->update(IM_EXP_RATE,$data);
                          }else{
                              
                              $this->db->insert(IM_EXP_RATE,$data);
                          }
                      }
                  }

                  foreach ($occupancybyage as $var => $oba) {
                      $data = array(

                              'user_id' => $owner_id,
                              'channel'=>$channel_id,
                              'hotel_channel_id'=>$hotel_channel_id,
                              'hotel_id' => $hotel_id,
                              'roomtype_id'=>(string)$attr['id'],
                              'roomtype_name'=>(string)$attr['name'],
                              'ageCategory' => (string)$oba->attributes()->ageCategory,
                              'minage' => (string)$oba->attributes()->minAge,
                              'maxoccupants' => (string)$oba->attributes()->maxOccupants,
                          );
                      $query=$this->db->query('Select * from '.IM_EXP_OCCUPANCY.' where channel="'.$channel_id.'" AND user_id ="'.$owner_id.'" AND hotel_id="'.$hotel_id.'" AND hotel_channel_id="'.$hotel_channel_id.'" AND roomtype_id="'.$attr['id'].'" AND ageCategory="'.$oba->attributes()->ageCategory.'"');
                      if($query->num_rows == 1){

                          $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'roomtype_id'=>$attr['id'], 'ageCategory' => (string)$oba->attributes()->ageCategory));
                           $this->db->update(IM_EXP_OCCUPANCY,$data);
                      }else{
                          $query2=$this->db->query('Select * from '.IM_EXP_OCCUPANCY.' where channel="'.$channel_id.'" AND user_id ="'.$owner_id.'" AND hotel_id="'.$hotel_id.'" AND hotel_channel_id="'.$hotel_channel_id.'" AND roomtype_id="'.$attr['id'].'" AND ageCategory="" ');
                          if($query2->num_rows == 1){
                              $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_id,'roomtype_id'=>$attr['id'],'rate_type_id'=>'','ageCategory' => ''));
                              $this->db->update(IM_EXP_OCCUPANCY,$data);
                          }else{
                              $this->db->insert(IM_EXP_OCCUPANCY,$data);

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
                                      'user_id' => $owner_id,
                                      'channel'=>$channel_id,
                                      'hotel_channel_id'=>$hotel_channel_id,
                                      'hotel_id' => $hotel_id,
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
                                    
                      $query=$this->db->query('Select * from '.IM_EXP.' where channel="'.$channel_id.'" AND user_id ="'.$owner_id.'" AND hotel_id="'.$hotel_id.'" AND hotel_channel_id="'.$hotel_channel_id.'" AND rate_type_id="'.$id.'"');
                      if($query->num_rows==1)
                      {
                           $this->db->where(array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel'=>$channel_id,'hotel_channel_id'=>$hotel_channel_id,'rate_type_id'=>$id));
                           $this->db->update(IM_EXP,$data);
                      }
                      else
                      {
                          $this->db->insert(IM_EXP,$data);
                      }
                  }
              }
              $meg['result'] = '1';
              $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;
          }
          curl_close($ch);   
      }
      else if($channel_id=='8')
      {
          if($mode == 0){
              $urls = explode(',', $test_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $gta[$path[0]] = $path[1];
              }
          }else if($mode == 1){
              $urls = explode(',', $live_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $gta[$path[0]] = $path[1];
              }
          }
          //$soapUrl = "https://hotels.demo.gta-travel.com/supplierapi/rest/rooms/search";
          $sopaUrl = trim($gta['roomsearch']);
          $xml_post_string = '<GTA_RoomsReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
          <User Qualifier="'.$web_id.'" UserName="'.$user_name.'" Password="'.$user_password.'" />
          <Property Id="'.$hotel_channel_id.'" />
          </GTA_RoomsReadRQ>';  
          $ch = curl_init($sopaUrl);
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
              $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Error_Array,'PMS Get Channel',date('m/d/Y h:i:s a', time()));
              $meg['result'] = '0';
              $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
              return $meg;
              curl_close($ch);
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

                          $viji_data['user_id'] = $owner_id;
                          $viji_data['hotel_id'] = $hotel_id;
                          $viji_data['channel_id'] = $channel_id;
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
                      //$hotel_id=$hotel_channel_id;
                       $xml_post_string = '<GTA_RoomRatesReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                              <User Qualifier="'.$web_id.'" UserName="'.$user_name.'" Password="'.$user_password.'" />
                          <Property Id="'.$hotel_channel_id.'" Model="Static"/>
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
                       $palndata = simplexml_load_string($output);
                       $Error_Array = @$palndata->Errors->Error;
                      if($Error_Array!='')
                      {
                          $this->inventory_model->store_error($owner_id,$hotel_id,insep_decode($channel_id),(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                          $meg['result'] = '0';
                          $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                          
                          curl_close($ch);
                          return $meg;
                      }
                       curl_close($ch);



                      $rate_row=$palndata->Room->Contract; 

                      //Fetching the contarct ID              
                       $contarct_id =$rate_row->attributes()->Id;


                      foreach($rate_row->RatePlans->RatePlan as $rateplan){                   

                          $rate_plan_id=$rateplan->attributes()->Id;

                          $mixpax=$rateplan->StaticRates->StaticRate->attributes()->MinPax;
                              $MinNights=$rateplan->StaticRates->StaticRate->attributes()->MinNights;
                          $stayfullperiod=$rateplan->StaticRates->StaticRate->attributes()->StayFullPeriod;
                          $payfullperiod=$rateplan->StaticRates->StaticRate->attributes()->PayFullPeriod;
                          $peakrate=$rateplan->StaticRates->StaticRate->attributes()->PeakRate;
                          

                          // Rateplan if update here;


                          $gta_available = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id))->row_array();
                          $viji_data['rateplan_id'] = $rate_plan_id;
                          $viji_data['contract_id'] = $contarct_id;
                          $viji_data['MinPax'] = $mixpax;
                          $viji_data['stayfullperiod'] = "$stayfullperiod";
                          $viji_data['payfullperiod'] = "$payfullperiod";
                          $viji_data['peakrate'] = "$peakrate";
                          $viji_data['minnights'] = "$MinNights";
                           $viji_data['contract_type'] = "Static";


                          if(count($gta_available)!=0)
                          {
                              update_data(IM_GTA,$viji_data,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>insep_decode($channel_id),'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id,'contract_type'=>'Static'));
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

                              insert_data(PMS_GTA,$viji_data);
                               $this->db->last_query();
                          }                    
                      }
                      $start_date = date('Y-m-d');
                      $end_date = date('Y-m-d', strtotime("+30days"));
                      $roomId=$vv['Id'];
                      $soapUrl = trim($gta['irate']);
                      $soapUser = "HOTELAVAIL";
                      $soapPassword = "HOTELAVAIL";
                     // $hotel_id=$hotel_channel_id;
                       $xml_post_string = '<GTA_RoomRatesReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                              <User Qualifier="'.$web_id.'" UserName="'.$user_name.'" Password="'.$user_password.'" />
                          <Property Id="'.$hotel_channel_id.'" Model="Margin" Start="'.$start_date.'" End="'.$end_date.'"/>
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
                       $palndata = simplexml_load_string($output);
                       $Error_Array = @$palndata->Errors->Error;
                      if($Error_Array!='')
                      {
                          $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                          $meg['result'] = '0';
                          $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                          curl_close($ch);
                          return $meg;
                      }
                       curl_close($ch);
                     

                      $rate_row=$palndata->Room->Contract; 

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


                          $gta_available = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id))->row_array();
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
                              update_data(IM_GTA,$viji_data,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$response_hotel,'ID'=>$vv['Id'],'contract_id'=>$contarct_id,'rateplan_id'=>$rate_plan_id,'contract_type'=>'Margin'));
                             
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
                            //   echo $this->db->last_query(); 
                      
                              
                          }    
                      }
                  }
              }

              $gta_available = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$response_hotel,'contract_type'=>'Static'));

              $soapUrl = trim($gta['rateplansearch']);
              foreach($gta_available->result() as $row){
                    
                  $gta_id= $row->gta_id;
                  $ratepaln_id=$row->rateplan_id;
                  $contract_id=$row->contract_id;

                  $xml_post_string = '<GTA_RatePlanReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                    <User Qualifier="'.$web_id.'" UserName="'.$user_name.'" Password="'.$user_password.'" />
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
                      $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                      $meg['result'] = '0';
                      $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';

                      //echo json_encode($meg);
                      curl_close($ch);
                      return $meg;
                  }
                  curl_close($ch);
                  if(isset($data->Success)){
                     
                       $rateplan_code=$data->Contract->RatePlans->StaticRatePlan->attributes()->Code;
                     
                      $up_data=array();
                      $up_data['rateplan_code']="$rateplan_code";

                      update_data(IM_GTA,$up_data,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'rateplan_id'=>$ratepaln_id));
                  }
              }

              $gta_available = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel_id,'hotel_channel_id'=>$response_hotel,'contract_type'=>'Margin'));

              $soapUrl = trim($gta['rateplansearch']);
              foreach($gta_available->result() as $row){
                    
                  $gta_id= $row->gta_id;
                  $ratepaln_id=$row->rateplan_id;
                  $contract_id=$row->contract_id;

                  $xml_post_string = '<GTA_RatePlanReadRQ xmlns="http://www.gta-travel.com/GTA/2012/05">
                    <User Qualifier="'.$web_id.'" UserName="'.$user_name.'" Password="'.$user_password.'" />
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
                      $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Error_Array,'Bulk Update Save',date('m/d/Y h:i:s a', time()));
                      $meg['result'] = '0';
                      $meg['content']=$Error_Array.' from '.$cha_name.'. Try again!';
                      
                      curl_close($ch);
                      return $meg;
                  }
                  curl_close($ch);
                  if(isset($data->Success)){
                     
                      $rateplan_code=$data->Contract->RatePlans->MarginRatePlan->attributes()->Code;
                      $up_data=array();
                      $up_data['rateplan_code']="$rateplan_code";

                      update_data(IM_GTA,$up_data,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'rateplan_id'=>$ratepaln_id));
                  }
              }    
              $meg['result'] = '1';
              $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;
          }
      }
      else if($channel_id == '5'){
          
          if($mode == 0){
              $urls = explode(',', $test_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $htb[$path[0]] = $path[1];
              }
          }else if($mode == 1){
              $urls = explode(',', $live_url);
              foreach($urls as $url){
                  $path = explode("~",$url);
                  $htb[$path[0]] = $path[1];
              }
          }
          $xml_post_string = '<?xml version="1.0" encoding="UTF-8"?>
              <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
               <getHSIContractList xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
               <HSI_ContractListRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                  <Language>ENG</Language>
                  <Credentials>
                      <User>'.$user_name.'</User>
                      <Password>'.$user_password.'</Password>
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
          curl_setopt($ch, CURLOPT_USERPWD, $user_name.":".$user_password); // username and password - declared at the top of the doc
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
              $this->inventory_model->store_error($owner_id,$hotel_id,$channel_id,(string)$Error->DetailedMessage,'PMS Get Channel',date('m/d/Y h:i:s a', time()));
              $DetailedMessage    = $Error->DetailedMessage; 
              $meg['result'] = '0';
              $meg['content']=$DetailedMessage.' from '.$cha_name.'. Try again!';
              return $meg;
          }else{
              $xml = simplexml_load_string($responseArray['soapenvBody']['ns1getHSIContractList']);
              
              $hotelDetails = $xml->UserContract;
              $auditDetails = $xml->AuditData;
              $data['token'] = $xml->attributes()->echoToken;
              $data['user_id'] = $owner_id;
              $data['hotel_id'] = $hotel_id;
              $data['channel_id'] = $channel_id;

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

                  $isexist = get_data(IM_HOTELBEDS,array('user_id' => $owner_id,'hotel_id' => $hotel_id,'channel_id' => $channel_id,'sequence' => $data['sequence']))->num_rows;
                  if($isexist == 0){
                      insert_data(IM_HOTELBEDS,$data);
                  }else{
                      update_data(IM_HOTELBEDS,$data,array('user_id' => $owner_id,'hotel_id' => $hotel_id,'channel_id' => $channel_id,'sequence' => $data['sequence']));
                  }

                  $xml_contract_detail = '<?xml version="1.0" encoding="UTF-8"?>
                      <soapenv:Envelope soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <soapenv:Body>
                       <getHSIContractDetail xmlns="http://axis.frontend.hsi.hotelbeds.com" xsi:type="xsd:string">
                       <HSI_ContractDetailRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="0123">
                          <Language>ENG</Language>
                          <Credentials>
                          <User>'.$user_name.'</User>
                          <Password>'.$user_password.'</Password>
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
                  curl_setopt($ch, CURLOPT_USERPWD, $user_name.":".$user_password); // username and password - declared at the top of the doc
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

                  foreach ($xml->RateList->Rate->ServiceDaysList as $listRoom) {
                      //print_r($list->ServiceDays->RoomList->HotelRoom);
                      foreach($listRoom->ServiceDays->RoomList->HotelRoom as $rl){
                          $list['user_id'] = $owner_id;
                          $list['hotel_id'] = $hotel_id;
                          $list['channel_id'] = $channel_id;
                          $list['contract_name'] = (string)$data['contract_name'];
                          $list['contract_code'] = $data['contract_code'];
                          $list['sequence'] = $data['sequence'];
                          $list['currency'] = (string)$xml->Currency;
                          $list['roomname'] = (string)$rooms->RoomType->attributes()->code;
                          $list['roomtype'] = (string)$rooms->RoomType;
                          $list['type'] = (string)$rooms->RoomType->attributes()->type;
                          $list['characterstics'] = (string)$rooms->RoomType->attributes()->characteristic;

                          $is_exists = get_data("import_mapping_HOTELBEDS_ROOMS",array('user_id' => $owner_id,'hotel_id' => $hotel_id,'channel_id' => $channel_id,'sequence' => $list['sequence'],'characterstics' => (string)$list['characterstics']))->num_rows;
                          if($is_exists != 0){
                              update_data("import_mapping_HOTELBEDS_ROOMS",$list,array('user_id' => $owner_id,'hotel_id' => $hotel_id,'channel_id' => $channel_id,'sequence' => $list['sequence'],'characterstics' => (string)$list['characterstics']));
                          }else{
                              $this->db->insert("import_mapping_HOTELBEDS_ROOMS",$list);
                              //insert_data("import_mapping_HOTELBEDS_ROOMS",$list);
                          }
                      }
                  }
              }
              $meg['result'] = '1';
              $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;             
          }               
          curl_close($ch);
      }
      /*else if($channel_id == '17')
      {
        $channel_id = insep_encode($channel_id);
        require_once(APPPATH.'controllers/bnow.php'); 
        $callAvailabilities = new Bnow();
        $result = $callAvailabilities->importRooms($channel_id);
        if($result=='Enable')
        {
          $meg['result']  =   '0';
          $meg['content'] = "Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
          return $meg;
        }
        elseif($result=='Insert')
        {
          $meg['result']  =   '1';
          $meg['content'] = 'Succesfully import room rate information from '.$cha_name.'!!!';
          return $meg;
        }
        else
        {
          $meg['result']  = '0';
          $meg['content'] = $result.' from '.$cha_name.'. Try again!';
          return $meg;
        }
      }
      else if($channel_id == '15')
      {
          $channel_id = insep_encode($channel_id);
          require_once(APPPATH.'controllers/travelrepublic.php'); 
          $callAvailabilities = new Travelrepublic();
          $result = $callAvailabilities->importRooms($channel_id);
          if($result=='Enable')
          {
              $meg['result']  =   '0';
              $meg['content'] =   "Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
              return $meg;
          }
          elseif($result=='Insert')
          {
              $meg['result']  =   '1';
              $meg['content'] =   'Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;
          }
          else
          {
              $meg['result']  =   '0';
              $meg['content'] =   $result.' from '.$cha_name.'. Try again!';
              return $meg;
          }
      }
      else if($channel_id == '14')
      {
          $channel_id = insep_encode($channel_id);
          require_once(APPPATH.'controllers/wbeds.php'); 
          $callAvailabilities = new Wbeds();
          $result = $callAvailabilities->importRooms($channel_id);
          if($result=='Enable')
          {
              $meg['result']  =   '0';
              $meg['content'] =   "Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
              return $meg;
          }
          elseif($result=='Insert')
          {
              $meg['result']  =   '1';
              $meg['content'] =   'Succesfully import room rate information from '.$cha_name.'!!!';
              return $meg;
          }
          else
          {
              $meg['result']  =   '0';
              $meg['content'] =   $result.' from '.$cha_name.'. Try again!';
              return $meg;
          }
      }*/
      else
      {
          $meg['result'] = '0';
          $meg['content']='Error during import room rate information from '.$cha_name.'. Try again!';
          return $meg;
      }
  }

  function getMappingRooms($channel_id,$hotel_channel_id,$hotel_id,$user_id)
  {
      $rooms = array();
      if($channel_id == 1)
      {
          $this->db->select('E.map_id, E.roomtype_name, E.code, E.name, E.distributionModel, E.rateAcquisitionType');
          $this->db->where_not_in('E.rateAcquisitionType',"Linked");
          $this->db->where_not_in('E.rateAcquisitionType',"Derived");
          $this->db->where('rate_type_id !=', '');
          $this->db->where(array('user_id'=>$user_id,'hotel_channel_id'=>$hotel_channel_id,'hotel_id'=>$hotel_id));
          $result = $this->db->get(IM_EXP.' as E');
          if($result->num_rows() != 0)
          {
            foreach($result->result_array() as $row)
            {
               $rooms[] = array(
                  'channel_id' => $channel_id,
                  'room_id' => $row['map_id'],
                  'ch_room_name' => $row['roomtype_name'].'-'.$row['distributionModel'].'-'.$row['name'],
                );
            }
          }
      }elseif($channel_id == 11)
      {
          $this->db->select('R.IDROOM, R.CODE, R.re_id');
          $this->db->where(array('user_id'=>$user_id,'hotel_channel_id'=>$hotel_channel_id,'hotel_id'=>$hotel_id));
          $result = $this->db->get(IM_RECO.' as R');
          if($result->num_rows() != 0)
          {
            foreach($result->result_array() as $row)
            {
               $rooms[] = array(
                  'channel_id' => $channel_id,
                  'room_id' => $row['re_id'],
                  'ch_room_name' => $row['CODE'],
                );
            }
          }
      }elseif($channel_id == 5)
      {
          $this->db->select('H.map_id, H.contract_name, H.sequence, H.roomname, H.roomtype');
          $this->db->where(array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_id' => $channel_id));
          $result = $this->db->get(IM_HOTELBEDS_ROOMS.' as H');
          if($result->num_rows() != 0)
          {
            foreach($result->result_array() as $row)
            {
               $rooms[] = array(
                  'channel_id' => $channel_id,
                  'room_id' => $row['map_id'],
                  'ch_room_name' => $row['contract_name'].'-'.$row['roomtype'],
                );
            }
          }
      }elseif($channel_id == 8)
      {
          $this->db->select('G.GTA_id, G.RoomType, G.Description,G.rateplan_code,G.contract_type,G.ID,G.RateBasis,G.MaxOccupancy');
          $this->db->where(array('user_id'=>$user_id,'hotel_id'=>$hotel_id,'hotel_channel_id'=>$hotel_channel_id));
          $result = $this->db->get(IM_GTA.' as G');
          if($result->num_rows() != 0)
          {
            foreach($result->result_array() as $row)
            {
               $rooms[] = array(
                  'channel_id' => $channel_id,
                  'room_id' => $row['GTA_id'],
                  'ch_room_name' => $row['RoomType'].'-'.$row['Description'].'('.$row['rateplan_code'].')-Occupancy('.$row['MaxOccupancy'].')-'.$row['contract_type'],
                );
            }
          }
      }elseif($channel_id == 2)
      {
          $this->db->select('B.import_mapping_id, B.room_name, B.policy_id, B.rate_name,B.B_rate_id');
          $this->db->where_not_in('B.B_rate_id','0');
          $this->db->where(array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'channel_hotel_id'=>$hotel_channel_id));
          $result = $this->db->get(BOOKING.' as B');
          if($result->num_rows() != 0)
          {
            foreach($result->result_array() as $row)
            {
               $rooms[] = array(
                  'channel_id' => $channel_id,
                  'room_id' => $row['import_mapping_id'],
                  'ch_room_name' => $row['room_name'].'-'.$row['rate_name'],
                );
            }
          }
          
      }

      return $rooms;
  }

  function check_room_map_exists($ch_room_id,$RoomTypeId,$channel_id,$hotel_id,$owner_id)
  {
      $this->db->where(array('channel_id'=>$channel_id,'owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'import_mapping_id' => $ch_room_id));
      $isexist = $this->db->get(MAP);
      if($isexist->num_rows() == 0)
      {
        $roomexist = get_data(TBL_PROPERTY,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'property_id' => $RoomTypeId));
        
        if($roomexist->num_rows() != 0)
        {
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
  }

  function update_channels_table($products)
  {
      foreach ($products as $product) {

        extract($product);

        $up_days =  explode(',',$product['days']);
        
        if($product['open_room'] =='' || $product['open_room'] == 0)
        {
            if($product['stop_sell']!='')
            {
                $stop_sale =$product['stop_sell'];
            }
            elseif($product['stop_sell'] =='')
            {
                $stop_sale ='0';
            }
        }
        else if($product['open_room'] == 1)
        {
            $stop_sale ='remove';
        }

        if(@$product['cta'] == 2){
            $product['cta'] = 0;
        }

        if(@$product['ctd'] == 2){
            $product['ctd'] = 0;
        }
        
        if(in_array('1', $up_days)) 
        {
            if(isset($product['cta']) =='' || $product['cta'] == '0')
            {
                $sunday_cta = '0';
            }
            else
            {
                $sunday_cta ='1';
            }
            $exp_sun = 'true';
        }
        else 
        {
            $sunday_cta ='1';
            $exp_sun = 'false';
        }
        if(in_array('2', $up_days)) 
        {
            if(isset($product['cta']) ==''  || $product['cta'] == '0')
            {
                $monday_cta = '0';
            }
            else
            {
                $monday_cta ='1';
            }
            $exp_mon = 'true';
        }
        else 
        {
            $monday_cta ='1';
            $exp_mon = 'false';
        }
        if(in_array('3', $up_days)) 
        {
            if(isset($product['cta']) ==''  || $product['cta'] == '0')
            {
                $tuesday_cta = '0';
            }
            else
            {
                $tuesday_cta ='1';
            }
            $exp_tue = 'true';
        }
        else 
        {
            $tuesday_cta ='1';
            $exp_tue = 'false';
        }
        if(in_array('4', $up_days)) 
        {
            if(isset($product['cta']) ==''  || $product['cta'] == '0')
            {
                $wednesday_cta = '0';
            }
            else
            {
                $wednesday_cta ='1';
                
            }
            $exp_wed = 'true';
        }
        else
         {
            $wednesday_cta ='1';
            $exp_wed = 'false';
        }
        if(in_array('5', $up_days)) 
        {
            if(isset($product['cta']) == ''  || $product['cta'] == '0')
            {
                $thursday_cta ='0';
            }
            else
            {
                $thursday_cta ='1';
            }
            $exp_thur = 'true';
        }
        else 
        {
            $thursday_cta ='1';
            $exp_thur = 'false';
        }
        if(in_array('6', $up_days)) 
        {
            if(isset($product['cta']) == ''  || $product['cta'] == '0')
            {
                $friday_cta ='0';
            }
            else
            {
                $friday_cta ='1';
            }
            $exp_fri = 'true';
        }
        else 
        {
            $friday_cta ='1';
            $exp_fri = 'false';
        }
        if(in_array('7', $up_days)) 
        {
            if(isset($product['cta']) == '' || $product['cta'] == '0')
            {
                $saturday_cta = '0';
            }
            else
            {
                $saturday_cta ='1';
                
            }
            $exp_sat = 'true';
        }
        else 
        {
            $saturday_cta ='1';
            $exp_sat = 'false';
        }
    
        $up_sart_date = date('d.m.Y',strtotime($product['start_date']));
        $up_end_date = date('d.m.Y',strtotime($product['end_date']));
        
        $re_sart_date = $product['start_date'];
        $re_end_date = $product['end_date'];
        
        $hotelbed_start = str_replace('-', '', $re_sart_date);
        $hotelbed_end = str_replace('-', '', $re_end_date);
            
        $datetime1 = new DateTime($re_sart_date);
        $datetime2 = new DateTime($re_end_date);
        $interval = $datetime1->diff($datetime2);
        $number_of_days = $interval->format('%a%')+1;

        if($stop_sale!='remove')
        {
            if($stop_sale!='1')
            {
                if(@$product['availability']!='')
                {
                    $ch_update_avail = trim(str_repeat('='.$product['availability'].',',$number_of_days),',');
                    //echo $ch_update_avail;
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
          if(@$product['availability']!='')
          {
              $ch_update_avail=trim(str_repeat('='.$product['availability'].',',$number_of_days),',');
          }else{
              $ch_update_avail=trim(str_repeat('=1'.',',$number_of_days),',');
          }
        }

        if(@$product['cta'] == "" || $product['cta'] == "0"){
            $exp_cta = "false";
        }else{
            $exp_cta = "true";
        }
        if(@$product['ctd'] == "" || $product['ctd'] == "0"){
            $exp_ctd = "false";
        }else{
            $exp_ctd = "true";
        }

        $channels  = explode(',', $channel_id);
        if(count($channels) != 0){
          foreach ($channels as $channel) {
            
            $count = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->num_rows();
            if($count != 0)
            {
                $room_mapping = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel,'property_id'=>$room_id,'rate_id'=>0,'guest_count'=>0,'refun_type'=>0,'enabled'=>'enabled'))->result();
                      
                if($room_mapping)
                {
                    foreach($room_mapping as $room_value)
                    {
                        if(@$price != ""){
                          if($room_value->rate_conversion != "1"){
                            $rate_converted = 1;
                            if(strpos($room_value->rate_conversion, '.') !== FALSE){
                              $price = $price * $room_value->rate_conversion;
                            }else if(strpos($room_value->rate_conversion, ',') !== FALSE){
                              $mul = str_replace(',', '.', $room_value->rate_conversion);
                              $price = $price * $mul;
                            }else if(is_numeric($room_value->rate_conversion)){
                              $price = $price * $room_value->rate_conversion;
                            }                                  
                          }
                        }
                        if($room_value->channel_id=='11')
                        {
                             if(@$price!='' && $room_value->update_rate == 1)
                             {
                                $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel))->row();

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
                                
                                $mp_details = get_data(IM_RECO,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$room_value->channel_id,'re_id'=>$room_value->import_mapping_id,'hotel_channel_id'=>$ch_details->hotel_channel_id))->row();
                                
                                if(@$price !='')
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
                                                        $ex_price = $product['price'] + $opr[1];
                                                    }else if(is_numeric($opr[0])){
                                                        $ex_price = $product['price'] + $opr[0];
                                                    }else{
                                                        if(strpos($opr[1], '%')){
                                                            $per = explode('%',$opr[1]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($product['price'] * $per[0]) / 100;
                                                                $ex_price = $product['price'] + $per_price;
                                                            }
                                                        }elseif (strpos($opr[0], '%')) {
                                                            $per = explode('%',$opr[0]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($product['price'] * $per[0]) / 100;
                                                                $ex_price = $product['price'] + $per_price;
                                                            }
                                                        }
                                                    }
                                                }elseif (strpos($v, '-') !== FALSE) {
                                                    $opr = explode('-', $v);
                                                    if(is_numeric($opr[1])){
                                                        $ex_price = $product['price'] - $opr[1];
                                                    }elseif (is_numeric($opr[0])) {
                                                        $ex_price = $product['price'] - $opr[0];
                                                    }else{
                                                        if(strpos($opr[1],'%') !== FALSE){
                                                            $per = explode('%',$opr[1]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($product['price'] * $per[0]) / 100;
                                                                $ex_price = $product['price'] - $per_price;
                                                            }
                                                        }elseif (strpos($opr[0],'%') !== FALSE) {
                                                            $per = explode('%',$opr[0]);
                                                            if(is_numeric($per[0])){
                                                                $per_price = ($product['price'] * $per[0]) / 100;
                                                                $ex_price = $product['price'] - $per_price;
                                                            }
                                                        }
                                                    }
                                                }elseif (strpos($v, '%') !== FALSE) {
                                                    $opr = explode('%', $v);
                                                    if(is_numeric($opr[1])){
                                                        $per_price = ($product['price'] * $opr[1]) / 100;
                                                        $ex_price = $product['price'] + $per_price;
                                                    }elseif (is_numeric($opr[0])) {
                                                        $per_price = ($product['price'] * $opr[0]) / 100;
                                                        $ex_price = $product['price'] + $per_price;
                                                    }
                                                }else{
                                                    $ex_price = $product['price'] + $v;
                                                }
                                                
                                                $mapping_fields .= "<".$k.">".$ex_price."</".$k.">";
                                            }else{
                                                $mapping_fields .= "<".$k.">".$v."</".$k.">";
                                            }
                                        }
                                    }   
                                    if(@$product['minimum_stay'] !='')
                                    {
                                        $minimum_stay = $product['minimum_stay'];
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
                                    <EndDate>'.$up_end_date.'</EndDate>
                                    <SingleOcc>'.$product['price'].'</SingleOcc>'.
                                            $mapping_fields.'
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
                                    //echo $xml_post_string;
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
                                            $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$Errorarray['WARNING'],'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                            $response_message[] = array(
                                                'ChannelID' => $room_value->channel_id,
                                                'RoomId' => $room_id,
                                                'Error' => $Errorarray['WARNING'],
                                              );
                                        }
                                        else if(count($soapFault)!='0')
                                        {
                                            $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$soapFault['soapText'],'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                            $response_message[] = array(
                                                'ChannelID' => $room_value->channel_id,
                                                'RoomId' => $room_id,
                                                'Error' => $soapFault['soapText'],
                                              );
                                        }
                                    }
                                    curl_close($ch);
                                
                                }
                                if($ch_update_avail!='')
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
                                        $reconline_availability_response="error";
                                        if(count($Errorarray)!='0')
                                        {
                                            $this->inventory_model->store_error($partner_id,$property_id,$room_value->channel_id,$Errorarray['WARNING'],'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                            $response_message[] = array(
                                                'ChannelID' => $room_value->channel_id,
                                                'RoomId' => $room_id,
                                                'Error' => $Errorarray['WARNING'],
                                              );
                                        }
                                        else if(count($soapFault)!='0')
                                        {
                                            $this->inventory_model->store_error($partner_id,$property_id,$room_value->channel_id,$soapFault['soapText'],'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                            $response_message[] = array(
                                                'ChannelID' => $room_value->channel_id,
                                                'RoomId' => $room_id,
                                                'Error' => $soapFault['soapText'],
                                              );
                                        }
                                        return false;
                                    }
                                    curl_close($ch);
                                }
                            }
            
                        }
                        else if($room_value->channel_id=='1')
                        {
                          $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel))->row();
                          
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
                          $rt_details = get_data('import_mapping_expedia_ratelimit',array('user_id'=>$owner_id,'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>$hotel_id,'channel'=>$room_value->channel_id,'rateType' => 'SellRate'))->row();
                          $oa_details = get_data('import_mapping_expedia_occupancy',array('user_id'=>$owner_id,'hotel_channel_id' => $mp_details->hotel_channel_id,'hotel_id'=>$hotel_id,'channel'=>$room_value->channel_id))->row();

                          if(@$product['minimum_stay'] == ''){
                              $minlos = $mp_details->minLos;
                          }else{
                              $minlos = @$product['minimum_stay'];
                          }
                          $maxLos = $mp_details->maxLos;
                          /*$mapping_values = get_data('mapping_values',array('mapping_id'=>$room_value->mapping_id))->row_array();
                          if($mapping_values){
                              if($mapping_values['label']== "MaxStay" && $mapping_values['value']<=$maxLos){
                                  if($minlos < $mapping_values['value']){
                                      $maxLos = $mapping_values['value'];
                                  }
                              }
                          }*/
                          //echo $minlos;
                          //echo $stop_sale;
                          if($room_value->explevel == "rate"){

                              $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                          <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                          <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                          <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                          <AvailRateUpdate>
                                          <DateRange from="'.$re_sart_date.'" to="'.$re_end_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                              $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';

                              if(@$product['availability'] != ''){
                                  $xml .= '<Inventory totalInventoryAvailable="'.$product['availability'].'"/>';
                              }
                              if($mp_details->rateAcquisitionType == "Derived" || $mp_details->rateAcquisitionType == "Linked"){
                                  $plan_id = $mp_details->rateplan_id;
                              }else{
                                  $plan_id = $mp_details->rate_type_id;
                              }
                              //echo $plan_id;
                              if($stop_sale == 1){
                                  $closed = "true";
                                  $xml .= '<RatePlan id="'.$plan_id.'" closed = "true">';
                              }elseif($stop_sale == "remove"){
                                  $xml .= '<RatePlan id="'.$plan_id.'" closed = "false">';
                              }else{
                                  $xml .= '<RatePlan id="'.$plan_id.'">';
                              }
                              //@$product['price']!='' && @$product['price'] >= (string)$rt_details->minAmount && @$product['price'] <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                              if(@$product['price']!=''){
                                  //echo @$product['price'];
                                  if($mp_details->pricingModel == "PerDayPricingByLengthOfStay"){

                                                                                 
                                      for($i = $minlos; $i<=$maxLos; $i++){
                                          $xml .= '<Rate lengthOfStay="'.$i.'" currency="EUR">
                                                  <PerDay rate="'.@$product['price'].'"/>
                                                  </Rate>';
                                      }
                                      //$xml .= '<Restrictions closedToArrival="'.$exp_cta.'" closedToDeparture="'.$exp_ctd.'"/>';
                                  }elseif ($mp_details->pricingModel == 'PerDayPricing') {                                            
                                                                                
                                      $xml .= '<Rate currency="EUR"> <PerDay rate="'.@$product['price'].'"/>
                                              </Rate> ';
                                  }

                              }else{
                                  if(@$product['price']!=''){

                                      if(@$product['price'] <= (string)$rt_details->minAmount || @$product['price'] >= (string)$rt_details->maxAmount){
                                          $response_message[] = array(
                                              'ChannelID' => $room_value->channel_id,
                                              'RoomId' => $room_id,
                                              'Error' => 'Price must be between '.(string)$rt_details->minAmount.' and '.(string)$rt_details->maxAmount,
                                            );
                                      }
                                  }
                              }

                              
                              if($exp_ctd != "" || $exp_cta != "" || @$product['minimum_stay'] != ""){
                                  $xml .= '<Restrictions';
                                  $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                  $xml .= ' closedToArrival="'.$exp_cta.'"';
                                  
                                  if(@$product['minimum_stay'] != ""){
                                      $xml .= ' minLOS="'.@$product['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                  }
                                  $xml .= ' />';
                              }
                              $xml .= "</RatePlan></RoomType></AvailRateUpdate> </AvailRateUpdateRQ>";
                          }else if($room_value->explevel == "room"){

                              $xml = '<?xml version="1.0" encoding="UTF-8"?>
                                          <AvailRateUpdateRQ xmlns="http://www.expediaconnect.com/EQC/AR/2011/06">
                                          <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
                                          <Hotel id="'.$mp_details->hotel_channel_id.'"/>
                                          <AvailRateUpdate>
                                          <DateRange from="'.$re_sart_date.'" to="'.$re_end_date.'" sun="'.$exp_sun.'" mon="'.$exp_mon.'" tue="'.$exp_tue.'" wed="'.$exp_wed.'" thu="'.$exp_thur.'" fri="'.$exp_fri.'" sat="'.$exp_sat.'"/>';
                              if($stop_sale == 1){
                                  $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="true">';
                              }elseif($stop_sale == "remove"){
                                  $xml .= '<RoomType id="'.$mp_details->roomtype_id.'" closed="false">';
                              }else{
                                  $xml .= '<RoomType id="'.$mp_details->roomtype_id.'">';
                              }
                              if(@$product['availability'] != '' && $room_value->update_availability=='1'){
                                  $xml .= '<Inventory totalInventoryAvailable="'.$product['availability'].'"/>';
                              }
                              $available_plans = $this->db->query("SELECT * FROM ".IM_EXP." WHERE roomtype_id = '".$mp_details->roomtype_id."' AND rate_type_id != ''")->result();
                              foreach($available_plans as $e_plan){
                                  if($e_plan->rateAcquisitionType != "Linked"){

                                      if($e_plan->rateAcquisitionType == "Derived" || $e_plan->rateAcquisitionType == "Linked"){
                                          $plan_id = $e_plan->rateplan_id;
                                      }else{
                                          $plan_id = $e_plan->rate_type_id;
                                      }
                                     // echo $plan_id;

                                      $xml .= '<RatePlan id="'.$plan_id.'">';
                                      //@$product['prev(array)ice']!='' && @$product['price'] >= (string)$rt_details->minAmount && @$product['price'] <= (string)$rt_details->maxAmount && $room_value->update_rate=='1'
                                      if(@$product['price']!=''){
                                          
                                          if($e_plan->pricingModel == "PerDayPricingByLengthOfStay"){
                                                                                         
                                              for($i = $minlos; $i<=$maxLos; $i++){
                                                  $xml .= '<Rate lengthOfStay="'.$i.'" currency="EUR">
                                                          <PerDay rate="'.@$product['price'].'"/>
                                                          </Rate>';
                                              }
                                              //$xml .= '<Restrictions closedToArrival="'.$exp_cta.'" closedToDeparture="'.$exp_ctd.'"/>';
                                          }elseif ($e_plan->pricingModel == 'PerDayPricing') {
                                              
                                                                                        
                                              $xml .= '<Rate currency="EUR"> <PerDay rate="'.@$product['price'].'"/>
                                                      </Rate> ';
                                          }

                                      }else{
                                          if(@$product['price']!=''){
                                              if(@$product['price'] <= (string)$rt_details->minAmount || @$product['price'] >= (string)$rt_details->maxAmount){
                                                  $response_message[] = array(
                                                    'ChannelID' => $room_value->channel_id,
                                                    'RoomId' => $room_id,
                                                    'Error' => 'Price must be between '.(string)$rt_details->minAmount.' and '.(string)$rt_details->maxAmount,
                                                  );
                                              }
                                          }
                                      }

                                      
                                      if($exp_ctd != "" || $exp_cta != "" || @$product['minimum_stay'] != ""){
                                          $xml .= '<Restrictions';
                                          $xml .= ' closedToDeparture="'.$exp_ctd.'"';
                                          $xml .= ' closedToArrival="'.$exp_cta.'"';
                                          
                                          if(@$product['minimum_stay'] != ""){
                                              $xml .= ' minLOS="'.@$product['minimum_stay'].'" maxLOS="'.$maxLos.'"';
                                          }
                                          $xml .= ' />';
                                      }
                                      $xml .= "</RatePlan>";
                                  }
                              }
                              $xml .= "</RoomType></AvailRateUpdate></AvailRateUpdateRQ>";
                          }
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
                          
                          if($response!='')
                          {
                              //echo 'fail';
                              $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,$response,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                              //$this->session->set_flashdata('bulk_error',(string)$response);
                              $expedia_update = "Failed";
                              $response_message[] = array(
                                'ChannelID' => $room_value->channel_id,
                                'RoomId' => $room_id,
                                'Error' => (string)$response,
                              );
                          }
                          else
                          {
                              //echo 'success   ';
                              $expedia_update = "Success";
                          }
                          curl_close($ch);
                                            
                        }else if($room_value->channel_id == '5'){

                          $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel))->row();
                          
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
                              <DateTo date="'.$hotelbed_end.'"/>';
                          if(@$product['availability'] != "" && $room_value->update_availability=='1'){
                            if($stop_sale == 0){
                              $xml_post_string .= '<Room available="'.@$product['availability'].'" quote="'.@$product['availability'].'">';
                            }else if($stop_sale == 1){
                              $xml_post_string .= '<Room available="'.@$product['availability'].'" quote="'.@$product['availability'].'" closed="Y">';
                            }else{
                              $xml_post_string .= '<Room available="'.@$product['availability'].'" quote="'.@$product['availability'].'" closed="N">';
                            }                                  
                          }else{
                            if($stop_sale == 0){
                              $xml_post_string .= '<Room>';
                            }else{
                              $xml_post_string .= '<Room closed="Y">';
                            }   
                          }
                          
                          $xml_post_string .= '<RoomType type="'.$mp_details->type.'" code="'.$mp_details->roomname.'" characteristic="'.$mp_details->characterstics.'"/>';
                          if(@$product['price'] != "" && $room_value->update_rate == '1') {
                            $xml_post_string .= '<Price><Amount>'.$product['price'].'</Amount></Price>';
                          }
                          $xml_post_string .= '</Room></InventoryItem></HSI_ContractInventoryModificationRQ></getHSIContractInventoryModification></soapenv:Body>
                           </soapenv:Envelope>';
                           /* echo $xml_post_string;
                           die; */
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
                              $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$xml->ErrorList->Error->DetailedMessage,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                              
                              $response_message[] = array(
                                  'ChannelID' => $room_value->channel_id,
                                  'RoomId' => $room_id,
                                  'Error' => (string)$xml->ErrorList->Error->DetailedMessage,
                                );

                          }else if($xml->Status != "Y"){
                              $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,'Try Again','PMS Channel Update',date('m/d/Y h:i:s a', time()));
                              $response_message[] = array(
                                  'ChannelID' => $room_value->channel_id,
                                  'RoomId' => $room_id,
                                  'Error' =>  'Channel Update Failed',
                                );
                          }
                          if(@$product['minimum_stay'] != ""){
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
                                      <MinNumberOfDays>'.$product['minimum_stay'].'</MinNumberOfDays>
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
                                  $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$xml->ErrorList->Error->DetailedMessage,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                              
                                  $response_message[] = array(
                                    'ChannelID' => $room_value->channel_id,
                                    'RoomId' => $room_id,
                                    'Error' => (string)$xml->ErrorList->Error->DetailedMessage,
                                  );

                              }else if($xml->Status != "Y"){
                                 $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,'Try Again','PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                  $response_message[] = array(
                                      'ChannelID' => $room_value->channel_id,
                                      'RoomId' => $room_id,
                                      'Error' =>  'Channel Update Failed',
                                    );
                              }
                          }

                        }else if($room_value->channel_id=='8'){

                          $srat_array=explode('-',$product['start_date']);
                          
                          $xml_start_date= $product['start_date'];

                          $enddate_array=explode('-',$product['end_date']);
                          $xml_end_date=$product['end_date'];    
                          $days=$product['days']; 
                          $days_array=explode(',',$days);
                          $dayval="";

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
                          if (in_array("5", $days_array)) {
                             $dayval.=1;
                          }else{
                              $dayval.=0;
                          }
                          if (in_array("6",$days_array)) {
                              $dayval.=1;
                          }else{
                              $dayval.=0;
                          }
                          if (in_array("7", $days_array)) {
                               $dayval.=1;
                          }else{
                              $dayval.=0;
                          }
                          if (in_array("1", $days_array)) {
                              $dayval.=1;
                          }else{
                              $dayval.=0;
                          }
                          $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$channel))->row();

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
                          $mp_details = get_data(IM_GTA,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'GTA_id'=>$room_value->import_mapping_id,'channel_id'=>$room_value->channel_id))->row();
                                    
                          $gtroom_id=$mp_details->ID;
                          $rateplanid=$mp_details->rateplan_id;
                          $MinPax=$mp_details->MinPax;
                          $peakrate=$mp_details->peakrate;
                          $MaxOccupancy=$mp_details->MaxOccupancy;
                          if(@$product['minimum_stay'] == ""){
                              $minnights=$mp_details->minnights;
                          }else{
                              $minnights=@$product['minimum_stay'];
                          }

                          $payfullperiod=$mp_details->payfullperiod;
                                                                      
                          $contract_id=$mp_details->contract_id;  

                          $hotel_channel_id=$mp_details->hotel_channel_id;
                          $contract_type=$mp_details->contract_type;
                                              
                          if(isset($product['price']) && $product['price']!=0 && !empty($product['price'])){

                              $pri=$product['price'];
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
                                  <StaticRoomRate RoomId="'.$gtroom_id.'" Occupancy="'.$MaxOccupancy.'" Nett="'.$pri.'" />
                                  </StaticRate>
                                  </RatePlan>
                                  </GTA_StaticRatesCreateRQ>';
                                  //echo $xml_post_string;
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
                                      $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                      $response_message[] = array(
                                          'ChannelID' => $room_value->channel_id,
                                          'RoomId' => $room_id,
                                          'Error' => (string)$Error_Array,
                                        );
                                  }

                              }else{
                                  $pri=$product['price']; 
                                  $soapUrl = trim($gta['urate_m']);
           
                                  $xml_post_string = '<GTA_MarginRatesUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><RatePlan Id="'.$rateplanid.'"><MarginRates DaysOfWeek="'.$dayval.'" FullPeriod="false"><RoomRate Start="'.$xml_start_date.'" End="'.$xml_end_date.'" RoomId="'.$gtroom_id.'"
                                  Occupancy="'.$MaxOccupancy.'" Gross="'.$pri.'"/>
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
                                      $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                      $response_message[] = array(
                                          'ChannelID' => $room_value->channel_id,
                                          'RoomId' => $room_id,
                                          'Error' => (string)$Error_Array,
                                        );
                                  }                                      
                              }
                          }

                          $srat_array=explode('-',$product['start_date']);
                          $xml_start_date= $product['start_date'];
                          $enddate_array=explode('-',$product['end_date']);
                          $xml_end_date= $product['end_date'];    

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
                          if($stop_sale !='0' ){

                              $begin_date =$xml_start_date;
                              $en_date=$xml_end_date;
                              $period = $this->inventory_model->getDateForSpecificDayBetweenDates($xml_start_date,$xml_end_date,$product['days']);
                              /*$begin = new DateTime($begin_date ); 
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
                              }          */ 
                              $soapUrl=trim($gta['uavail']);           

                              $xml_post_string='<GTA_InventoryUpdateRQ
                                  xmlns = "http://www.gta-travel.com/GTA/2012/05"
                                  xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                  xsi:schemaLocation = "http://www.gta-travel.com/GTA/2012/05
                                  GTA_InventoryUpdateRQhelp.xsd">
                                  <User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" />
                                  <InventoryBlock ContractId = "'.$contract_id.'" PropertyId = "'.$hotel_channel_id.'">
                                      <RoomStyle>';

                              foreach($period as $stdate){
                                  $stdate = date('Y-m-d',strtotime(str_replace('/','-',$stdate)));
                                  $xml_post_string.=' <StayDate Date = "'.$stdate.'">
                                      <Inventory RoomId="'.$gtroom_id.'" >';
                                  if($stop_sale == 1){ 
                                      $xml_post_string .= '<Restriction FlexibleStopSell="true" InventoryType="Flexible"/>';
                                  }else if($stop_sale == "remove" && @$product['availability'] != ""){
                                      $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "'.$product['availability'].'" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';               
                                  }else if($stop_sale == "remove"  && @$product['availability'] == ""){
                                      $xml_post_string .= '<Detail FreeSale = "false" InventoryType = "Flexible" Quantity = "5" ReleaseDays = "0"/><Restriction FlexibleStopSell="false" InventoryType="Flexible"/>';     
                                  }
                                  $xml_post_string .= '</Inventory>
                                  </StayDate> ';
                              }
                              $xml_post_string.=' </RoomStyle></InventoryBlock>
                                      </GTA_InventoryUpdateRQ>';
                              //echo $xml_post_string;
                              $ch = curl_init($soapUrl);
                              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                              curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);

                              $response = curl_exec($ch);
                              $data = simplexml_load_string($response);
                              $Error_Array = @$data->Errors->Error;
                              if($Error_Array!='')
                              {
                                  $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                  $response_message[] = array(
                                      'ChannelID' => $room_value->channel_id,
                                      'RoomId' => $room_id,
                                      'Error' => (string)$Error_Array,
                                    );
                              }

                          }
                          if(@$product['availability']!=''){
                              $begin_date =$xml_start_date;
                              $en_date=$xml_end_date;
                              $period = $this->inventory_model->getDateForSpecificDayBetweenDates($xml_start_date,$xml_end_date,$product['days']);
                              /*$begin = new DateTime($begin_date ); 
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
                              }*/
                              $availability= $product['availability']; 
                              $soapUrl=trim($gta['uavail']);
                              $xml_post_string='<GTA_InventoryUpdateRQ xmlns="http://www.gta-travel.com/GTA/2012/05"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xsi:schemaLocation="http://www.gta-travel.com/GTA/2012/05 GTA_InventoryUpdateRQ.xsd"><User Qualifier="'.$ch_details->web_id.'" UserName="'.$ch_details->user_name.'" Password="'.$ch_details->user_password.'" /><InventoryBlock ContractId="'.$contract_id.'" PropertyId="'.$ch_details->hotel_channel_id.'" ><RoomStyle>';
                              foreach($period as $stdate){
                                  $stdate = date('Y-m-d',strtotime(str_replace('/','-',$stdate)));
                                  $xml_post_string.=' <StayDate Date = "'.$stdate.'"><Inventory RoomId="'.$gtroom_id.'"><Detail FreeSale="false" InventoryType="Flexible" Quantity="'.$availability.'" ReleaseDays="0"/></Inventory></StayDate>';
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
                                  $this->inventory_model->store_error($owner_id,$hotel_id,$room_value->channel_id,(string)$Error_Array,'PMS Channel Update',date('m/d/Y h:i:s a', time()));
                                  $response_message[] = array(
                                      'ChannelID' => $room_value->channel_id,
                                      'RoomId' => $room_id,
                                      'Error' => (string)$Error_Array,
                                    );
                              }
                          }
                        }
                        else if($room_value->channel_id == '2')
                        {
                            $this->load->model("booking_model");
                            $response = $this->booking_model->pms_update($product,$stop_sale,$room_value->import_mapping_id,$room_value->mapping_id);
                            if(isset($response['error']))
                            {
                              $response_message[] = $response['error'];
                            }
                        }
                    }
                }
            }else{
              $response_message[] = array(
                'ChannelID' => $channel,
                'RoomId' => $room_id,
                'Error' => "Room Is Not Mapped",
              );
            }
          }
        }

        $start_date = $product['start_date'];
        $end_date = $product['end_date'];

        if(@$product['days'] != "")
        {
            $period = $this->inventory_model->getDateForSpecificDayBetweenDates($start_date,$end_date,@$product['days']);
        }else{
            $period = $this->inventory_model->getDateForSpecificDayBetweenDates($start_date,$end_date,array('1,2,3,4,5,6,7'));
        }

        foreach($period as $date)
        {            
            if(count($channels) != 0)
            {
                foreach($channels as $con_ch)
                {
                  if($con_ch==2)
                  {
                    $chk_allow = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch))->row()->xml_type;
                  }
                  else
                  { 
                    $chk_allow = '';
                  }
                  if(($con_ch==2 && ($chk_allow==2 || $chk_allow==3))||$con_ch!=2)
                  {
                    $room_mapping = get_data(MAP,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>$con_ch,'property_id'=>$room_id))->result();
                        
                    if($room_mapping)
                    {
                      $ch_available= get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'room_id'=>$product['room_id'],'separate_date'=>$date))->row_array();
                      if(count($ch_available)!=0)
                      { 
                          if(@$product['availability']!='')
                          {
                              $product_cha_avail['availability'] =$product['availability'];
                          }
                          else
                          {
                              $product_cha_avail['availability'] =$ch_available['availability'];
                          }
                          if(@$product['price']!='')
                          {
                              $product_cha_avail['price'] =$product['price'];
                              $product_cha_avails['price'] =$product['price'];
                              
                          }
                          else
                          {
                              $product_cha_avail['price'] =$ch_available['price'];
                              $product_cha_avails['price'] =$ch_available['price'];
                          }
                          if(@$product['minimum_stay']!='')
                          {
                              $product_cha_avail['minimum_stay'] =$product['minimum_stay'];
                          }
                          else
                          {
                              $product_cha_avail['minimum_stay'] =$ch_available['minimum_stay'];
                          }
                          if(isset($product['cta'])!='')
                          {
                              $product_cha_avail['cta'] =$product['cta'];
                          }
                          elseif(isset($product['cta'])=='')
                          {
                              $product_cha_avail['cta'] =$ch_available['cta'];
                              //$product['cta'] ='0';
                          }
                          if(isset($product['ctd'])!='')
                          {
                              $product_cha_avail['ctd'] =$product['ctd'];
                          }
                          elseif(isset($product['ctd'])=='')
                          {
                              $product_cha_avail['ctd'] =$ch_available['ctd'];
                              //$product['ctd'] ='0';
                          }
                          if(isset($product['open_room'])=='')
                          {
                              if(isset($product['stop_sell'])!='')
                              {
                                  $product_cha_avail['stop_sell'] =$product['stop_sell'];
                              }
                              elseif(isset($product['stop_sell'])=='')
                              {
                                  $product_cha_avail['stop_sell'] =$ch_available['stop_sell'];
                                  //$product['stop_sell'] ='0';
                              }
                          }
                          if(isset($product['open_room'])!='')
                          {
                              $product_cha_avail['stop_sell']='0';
                              $product_cha_avail['open_room'] =$product['open_room'];
                          }
                          elseif(isset($product['open_room'])=='')
                          {
                              $product_cha_avail['open_room'] =$ch_available['open_room'];
                              //$product['stop_sell'] ='0';
                          }
                          $product_cha_avail['separate_date'] = $date;
                          $this->db->where('owner_id', $owner_id);
                          $this->db->where('hotel_id', $hotel_id);
                          $this->db->where('room_id', $product['room_id']);
                          $this->db->where('separate_date', $date);
                          $this->db->where('individual_channel_id', $con_ch);
                          $this->db->update(TBL_UPDATE, $product_cha_avail);

                      }
                      else
                      {
                          $product['separate_date'] = $date;
                          $product['individual_channel_id'] = $con_ch;
                          $this->db->insert(TBL_UPDATE, $product);
                      }
                    }
                  }
                }
            }
        }  
      }

      if(isset($response_message))
      {
        return $response_message;
      }else{
        return "success";
      }
  }

  public function getAllocation($startdate,$endDate,$hotel_id,$partner_id,$channel_id)
  {
    //$det=$this->db->query('SELECT * FROM '.TBL_UPDATE.' WHERE str_to_date(separate_date, "%d/%m/%Y") between str_to_date("'.$startdate.'", "%d/%m/%Y") AND str_to_date("'.$endDate.'", "%d/%m/%Y") AND owner_id="'.$partner_id.'" AND hotel_id ="'.$hotel_id.'" AND individual_channel_id = "'.$channel_id.'" ORDER BY U.room_id DESC');  
    if($channel_id == ""){
      $allocations = $this->db->query('SELECT room_update.* FROM '.TBL_UPDATE.' JOIN '.MAP.' on roommapping.property_id = room_update.room_id WHERE (str_to_date(room_update.separate_date, "%d/%m/%Y") >= str_to_date("'.$startdate.'", "%d/%m/%Y")) AND (str_to_date(room_update.separate_date, "%d/%m/%Y") <= str_to_date("'.$endDate.'", "%d/%m/%Y")) AND room_update.owner_id="'.$partner_id.'" AND room_update.hotel_id ="'.$hotel_id.'" AND roommapping.owner_id = "'.$partner_id.'" AND roommapping.hotel_id = "'.$hotel_id.'" AND roommapping.enabled = "enabled"')->result();
    }else{
      $allocations = $this->db->query('SELECT room_update.* FROM '.TBL_UPDATE.' JOIN '.MAP.' on roommapping.property_id = room_update.room_id WHERE (str_to_date(room_update.separate_date, "%d/%m/%Y") >= str_to_date("'.$startdate.'", "%d/%m/%Y")) AND (str_to_date(room_update.separate_date, "%d/%m/%Y") <= str_to_date("'.$endDate.'", "%d/%m/%Y")) AND room_update.owner_id="'.$partner_id.'" AND room_update.hotel_id ="'.$hotel_id.'" AND roommapping.owner_id = "'.$partner_id.'" AND roommapping.hotel_id = "'.$hotel_id.'" AND roommapping.enabled = "enabled" AND room_update.individual_channel_id = "'.$channel_id.'"')->result();
    }
    
    return $allocations;
  }

  /*function getResrvationFromChannel($partner_id,$property_id,$from,$to,$channel_id)
  {
    if($channel_id != "")
    {
       $response[] = $this->getBookings($partner_id,$property_id,$from,$to,insep_encode($channel_id));
    }else{
      $channels = get_data(CONNECT,array('user_id'=>$partner_id,'hotel_id'=>$property_id,'status'=>'enabled'))->result();
      foreach($channels as $channel)
      {
        $response[] = $this->getBookings($partner_id,$property_id,$from,$to,insep_encode($channel->channel_id));
      }
    }
    //print_r($response);
    return $response;
  }*/

  function getBookings($partner_id,$property_id,$from,$to,$channel_id)
  {
    if(insep_decode($channel_id)!='')
    {
      $cha_name = ucfirst(get_data(TBL_CHANNEL,array('channel_id'=>insep_decode($channel_id)))->row()->channel_name);
      $ch_details = get_data(CONNECT,array('user_id'=>$partner_id,'hotel_id'=>$property_id,'channel_id'=>insep_decode($channel_id)))->row();
      
      if($ch_details->mode == 0){
          $urls = explode(',', $ch_details->test_url);
          foreach($urls as $url){
              $path = explode("~",$url);
              $book[$path[0]] = $path[1];
          }
      }else if($ch_details->mode == 1){
          $urls = explode(',', $ch_details->live_url);
          foreach($urls as $url){
              $path = explode("~",$url);
              $book[$path[0]] = $path[1];
          }
      }      
    }
    else
    {
      $cha_name ='';
    }

    if(insep_decode($channel_id)=='8')
    {
      $start = date(DATE_ATOM);
      $start = explode("+", $start);
      $end = date(DATE_ATOM, strtotime("+30 days"));
      $end = explode("+", $end);

      $soapUrl = trim($book['booking']);

      //$soapUrl="https://hotels.demo.gta-travel.com/supplierapi/rest/bookings/search";

      $xml_post_string='<GTA_BookingSearchRQ
      xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
      xmlns:xsd = "http://www.w3.org/2001/XMLSchema"
      xmlns = "http://www.gta-travel.com/GTA/2012/05">
       <User
              Qualifier = "'.$ch_details->web_id.'"
              Password = "'.$ch_details->user_name.'"
              UserName = "'.$ch_details->user_password.'"/>
      <SearchCriteria
      PropertyId = "'.$ch_details->hotel_channel_id.'"
      ModifiedStartDate = "'.$start[0].'"
      ModifiedEndDate = "'.$end[0].'"/>
      </GTA_BookingSearchRQ>';


      $ch = curl_init($soapUrl);
      //curl_setopt($ch, CURLOPT_MUTE, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch); 

      $dataxml = simplexml_load_string($output); 
      $data=$dataxml->Bookings->Booking;
      $meg['8']['reser'] = '';
      if(count($data) != 0){
        foreach($data as $row){
          $res_data=array();
          $user_id= $partner_id;
          $res_data['partner_id'] = "$user_id";
          $res_data['property_id'] = $property_id;
          $status=$row->attributes()->Status;
          $booking_id=$row->attributes()->BookingId;
          $res_data['booking_id']= "$booking_id";
          $ref=$row->attributes()->BookingRef;
          $res_data['booking_ref']= "$ref"; 
          $res_data['status']= "$status";  

          $cid=$row->attributes()->ContractId;
          $res_data['contractid']= "$cid";  
          $ctype=$row->attributes()->ContractType;
          $res_data['contracttype']= "$ctype";  
          $rateplanid=$row->attributes()->RatePlanId;
          $res_data['rateplanid']= "$rateplanid";  
          $rateplancode=$row->attributes()->RatePlanCode;
          $res_data['reateplancode']= "$rateplancode";  
          $rateplan_name=$row->attributes()->RatePlanName;
 
          $res_data['reateplanname']= "$rateplan_name";
          $hotel_id=$row->attributes()->PropertyId ; 
          $res_data['hotelid']= "$hotel_id";  
          $hotel_name=$row->attributes()->PropertyName;
          $res_data['propertyname']= "$hotel_name";
          $cityname= $row->attributes()->City;  
          $res_data['city']="$cityname"; 
          $arrdate=$row->attributes()->ArrivalDate; 
          $res_data['arrdate']= "$arrdate";  
          $depdate=$row->attributes()->DepartureDate;
          $res_data['depdate']= "$depdate"; 
          $nights=$row->attributes()->Nights; 
          $res_data['nights']= "$nights"; 
          $leadname= $row->attributes()->LeadName; 
          $res_data['leadname']="$leadname";  
          $adults=$row->attributes()->TotalAdults;
          $res_data['adults']= "$adults";  
          $children=$row->attributes()->TotalChildren;
          $res_data['children']= "$children";  
          $totalcots=$row->attributes()->TotalCots; ;
          $res_data['totalkcots']= "$totalcots";
          $totalcost=$row->attributes()->TotalCost;  
          $res_data['totalcost']="$totalcost" ;  
          $totalroomcost=$row->attributes()->TotalRoomsCost;
          $res_data['totalroomcost']= "$totalroomcost"; 
          $totaloffers=$row->attributes()->TotalOffers; 
          $res_data['offer']= "$totaloffers"; 
          $totalsuplements=$row->attributes()->TotalSupplements; ; 
          $res_data['totalsubliment']= "$totalsuplements";  
          $total_extra=$row->attributes()->TotalExtras;
          $total_extra="$total_extra";
          $res_data['totalextra']= "$total_extra";  
          $adjestment=$row->attributes()->TotalAdjustments;
          $res_data['adjestments']= "$adjestment";
          $totaltax=$row->attributes()->TotalTax;  
          $res_data['totaltax']= "totaltax";  
          $currencycode=$row->attributes()->CurrencyCode;
          $res_data['currencycode']= "$currencycode";
          $modidate=$row->attributes()->ModifiedDate; 
          $res_data['modifieddate']= "$modidate";  

          $contactDetails = $row->Rooms->Contact;
          if($contactDetails)
          {
            $res_data['name'] = $contactDetails->attributes()->Name;
            $res_data['email'] = $contactDetails->attributes()->Email;
          }
          $res_data['room_id']= "";
          $res_data['roomcategory']= "";
          $res_data['room_qty']= "";
          $res_data['rates']= "";
          $res_data['roomtypes']= "";
          $res_data['room_avail'] = "";
          $res_data['room_costdetils'] = "";

          foreach($row->Rooms->Room as $roomdata)
          {
            $res_data['room_id'] .= (string)$roomdata->attributes()->Id.',';
            $res_data['roomcategory'] .= (string)$roomdata->attributes()->RoomCategory.',';
            $res_data['room_qty'].= (string)$roomdata->attributes()->Quantity.',';
            $res_data['roomtypes'] .= (string)$roomdata->attributes()->RoomType.',';
            $availa = $roomdata->Availability;
            foreach($availa as $avail)
            {
              $res_data['room_avail'] .= (string)$avail->StayDate->attributes()->Date.'##'.(string)$avail->StayDate->attributes()->Available.'~';
            }
            $rates = $roomdata->Rates;
            foreach($rates as $rate)
            {
              foreach($rates->StayDates->attributes() as $key => $val)
              {
                $res_data['room_costdetils'] .=  (string)$key .'##'. (string)$val.'~';
              }
              $res_data['room_costdetils'] .= '&&';            
            }
            $res_data['room_costdetils'] .= ',';
            $res_data['room_avail'] .= ',';
            $res_data['rates']= "";
          }

          $passengers=$row->Passengers->Passenger;
          $passenger_details=[];
          foreach($passengers as $pass ){
            $passarray=array();
            $passarray['name']=$pass->attributes()->Name;
            if(isset($pass->attributes()->Age)){
              $passarray['age']=$pass->attributes()->Age;
            }else{
              $passarray['age']= (object)array('Adult');
            }
            $passenger_details[]=$passarray;
            
          }
          $passenger_array=json_encode($passenger_details);
            $res_data['passenger_names']="$passenger_array";
           $available = get_data(PMS_GTA_RESERV,array('partner_id'=>$partner_id,'property_id'=>$property_id,'hotelid'=>$hotel_id,'booking_id'=>$booking_id))->row_array();
         //  echo $this->db->last_query(); exit;
            if(count($available)==0)
            {
              
                insert_data(PMS_GTA_RESERV,$res_data);

            }
            else
            {
              update_data(PMS_GTA_RESERV,$res_data,array('partner_id'=>$partner_id,'hotelid'=>$hotel_id,'property_id'=>$property_id,'booking_id'=>$booking_id));
              
            }
            $importBookingDetails = get_data(PMS_GTA_RESERV,array('partner_id'=>$partner_id,'hotelid'=>$hotel_id,'property_id'=>$property_id,'booking_id'=>$booking_id))->row_array();
            if(count($importBookingDetails)!=0)
            {
              $arrival = date('Y-m-d',strtotime($importBookingDetails['arrdate']));
                $departure = date('Y-m-d',strtotime($importBookingDetails['depdate']));
              $importBookingDetails = get_data(PMS_GTA,array('ID'=>$importBookingDetails['room_id']),'gta_id')->row_array();
              if(count($importBookingDetails)!=0)
              {
                $roomMappingDetails = get_data(PMS_MAP,array('import_mapping_id'=>$importBookingDetails['gta_id'],'channel_id'=>8))->row_array();
                if(count($roomMappingDetails)!=0)
                {
                  require_once(APPPATH.'controllers/mapping.php'); 
                  $callAvailabilities = new Mapping();
                  $callAvailabilities->importAvailabilities_pms($partner_id,$property_id,insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['room_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['gta_id'],'mapping');
                }
              }
            }
            $meg['8']['reser'] .= $booking_id.',';
            //$this->reservation_log(insep_decode($channel_id),$booking_id,$partner_id,$property_id);
        }
      }
      $meg['8']['result'] = '1';
      $meg['8']['content']='Successfully import reservation from '.$cha_name.'!!!';
        
    }
    if(insep_decode($channel_id)=='11')
    {
      //$soapUrl = "https://test.reconline.com/RecoXML/RecoXML.asmx";
      $soapUrl = trim($book['booking']);
      $soapUser = "HotelAvailCM";  
      $soapPassword = "Hot16_AvXML!"; 
      $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
        <GetBookings xmlns="https://www.reconline.com/">
          <User>'.$ch_details->user_name.'</User>
          <Password>'.$ch_details->user_password.'</Password>
          <idHotel>'.$ch_details->hotel_channel_id.'</idHotel>
          <idSystem>0</idSystem>
          <ForeignPropCode></ForeignPropCode>
          <idRSV></idRSV>
          <StartDate>'.date('01-m-Y').'</StartDate>
          <EndDate></EndDate>
          <StartCreationDate></StartCreationDate>
          <EndCreationDate></EndCreationDate>
        </GetBookings>
        </soap12:Body>
      </soap12:Envelope>
      
      ';  
      $headers = array(
            "Content-type: application/soap+xml; charset=utf-8",
            "Host:www.reconline.com",
            "Content-length: ".strlen($xml_post_string),
          ); 
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
      //echo $xml;
      $xml = simplexml_load_string($xml);
      $json = json_encode($xml);
      $responseArray = json_decode($json,true);
      $Hotelarray = $responseArray['soapBody']['GetBookingsResponse']['GetBookingsResult']['diffgrdiffgram']['NewDataSet']['Bookings'];
      $Errorarray = @$responseArray['soapBody']['GetBookingsResponse']['GetBookingsResult']['diffgrdiffgram']['NewDataSet']['Warning'];
      $meg['11']['reser'] = '';
      //print_r($Hotelarray);
      if(count($Errorarray)!=0)
      {
        
        $meg['11']['result'] = '0';
        $meg['11']['content']=$Errorarray['WARNING'].' from '.$cha_name.'. Try again!';
        //$meg['channel_id'] = 11;
      }
      else if(count($Errorarray)==0)
      {
        foreach($Hotelarray as $reser)
        {
          foreach($reser as $key=>$value)
          {
            $data['partner_id'] = $partner_id;
            $data['property_id'] = $property_id;
            $data[$key] = $value;
          }
          $available = get_data(PMS_REC_RESERV,array('partner_id'=>$partner_id,'property_id'=>$property_id,'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']))->row_array();
          if(count($available)==0)
          {
            insert_data(PMS_REC_RESERV,$data);
          }
          else
          {
            update_data(PMS_REC_RESERV,$data,array('partner_id'=>$partner_id,'property_id'=>$property_id,'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']));
          }
          $meg['11']['reser'] .= $data['IDRSV'].',';
          $importBookingDetails = get_data(PMS_REC_RESERV,array('partner_id'=>$partner_id,'property_id'=>$property_id,'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']))->row_array();
          //print_r($importBookingDetails);
          if(count($importBookingDetails)!=0)
          {
            $arrival = date('Y-m-d',strtotime($importBookingDetails['CHECKIN']));
              $departure = date('Y-m-d',strtotime($importBookingDetails['CHECKOUT']));
            $importBookingDetails = get_data(PMS_RECO,array('CODE'=>$importBookingDetails['ROOMCODE']),'re_id')->row_array();

            if(count($importBookingDetails)!=0)
            {

              $roomMappingDetails = get_data(PMS_MAP,array('import_mapping_id'=>$importBookingDetails['re_id'],'channel_id'=>11))->row_array();
              
              if(count($roomMappingDetails)!=0)
              {
                require_once(APPPATH.'controllers/mapping.php'); 
                $callAvailabilities = new Mapping();
                $callAvailabilities->importAvailabilities_pms($partner_id,$property_id,insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['room_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['re_id'],'mapping');
              }
            }
          }
          //$this->reservation_log(insep_decode($channel_id),$data['IDRSV'],$partner_id,$property_id);
        }
        $meg['11']['result'] = '1';
        $meg['11']['content']='Successfully import reservation from '.$cha_name.'!!!';
      }
    }
    elseif (insep_decode($channel_id) == 1) 
    {
      $xml_data ='<?xml version="1.0" encoding="UTF-8"?>
            <BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
            <Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
            <Hotel id="'.$ch_details->hotel_channel_id.'"/> 
            </BookingRetrievalRQ>
             ';
      $x_r_rq_data['channel_id'] = '1';           
      $x_r_rq_data['user_id'] = $partner_id;            
      $x_r_rq_data['hotel_id'] = $property_id;           
      $x_r_rq_data['message'] = $xml_data;            
      $x_r_rq_data['type'] = 'PMS_EXP_REQ';           
      $x_r_rq_data['section'] = 'PMS_RESER';            
      insert_data(ALL_XML,$x_r_rq_data);
      //$URL = "https://simulator.expediaquickconnect.com/connect/br";
      //$URL = "https://ws.expediaquickconnect.com/connect/br";
      $URL = trim($book['booking']);
      //echo $URL;

      $ch = curl_init($URL);
      //curl_setopt($ch, CURLOPT_MUTE, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output   = curl_exec($ch);
      
      $x_r_rs_data['channel_id'] = '1';           
      $x_r_rs_data['user_id'] = $partner_id;            
      $x_r_rs_data['hotel_id'] = $property_id;           
      $x_r_rs_data['message'] = $output;            
      $x_r_rs_data['type'] = 'PMS_EXP_RES';           
      $x_r_rs_data['section'] = 'PMS_RESER';
      insert_data(ALL_XML,$x_r_rs_data);
      $data_api   = simplexml_load_string($output);
      //print_r($data_api);
      $error =@$data_api->Error;
      $meg['1']['reser'] = "";
      if($error != "")
      {
        $meg['result'] = '0';
        $meg['content']= $error.' from '.$cha_name.'. Try again!';
        $meg['channel_id'] = 1;
      }
      else if($error == "")
      {
        $BookingListing = @$data_api->Bookings;
        if($BookingListing){
          foreach($BookingListing as $Booking)
          { 
            foreach($Booking as $Booking_key=>$book)
            {
              $data['partner_id'] = $partner_id;
              $data['property_id'] = $property_id;

              $bookattr=$book->attributes();
              $data['booking_id'] = $bookattr['id'];
              $data['type'] = (string)$bookattr['type'];
              $result = "";
              for($i = 0; $i < 10; $i++) 
              {
                  $result .= mt_rand(0, 9);
              }
              $data['confirmation_number'] = $result;
              if($data['type'] != "Cancel"){
                $data['created_time'] = (string)$bookattr['createDateTime'];
                $data['source'] = (string)$bookattr['source'];
                $data['status'] = (string)$bookattr['status'];
                
                $Hotel=$book->Hotel->attributes();
                $data['hotelid'] = $Hotel['id'];

                $RoomStay = $book->RoomStay->attributes();
                $data['roomTypeID'] = (string)$RoomStay['roomTypeID'];
                $data['ratePlanID'] = (string)$RoomStay['ratePlanID'];
                
                $StayDate = $book->RoomStay->StayDate;
                $data['arrival'] = (string)$StayDate['arrival'];
                $data['departure'] = (string)$StayDate['departure'];
                
                $GuestCount = $book->RoomStay->GuestCount->attributes();
                $data['adult'] = $GuestCount['adult'];
                if($GuestCount['child'] != NULL){
                  $data['child'] = $GuestCount['child'];
                }else{
                  $data['child'] = "";
                }
                
                
                $Child = $book->RoomStay->GuestCount->Child;
                $data['child_age'] = "";
                if(count($Child)!=0)
                {
                  foreach($Child as $Child_key=>$Child_value)
                  {
                    $Childage = $Child_value->attributes(); 
                    $data['child_age'] .= $Childage['age'].",";
                  }
                }

                $currency = $book->RoomStay->PerDayRates->attributes();
                $data['currency'] = "";
                if(count($currency))
                {
                  $data['currency'] = (string)$currency['currency'];
                }

                $PerDayRates = $book->RoomStay->PerDayRates->PerDayRate;
                $data['stayDate'] = "";
                $data['baseRate'] = "";
                $data['promoName'] = "";

                if(count($PerDayRates)!=0)
                {
                  foreach($PerDayRates as $PerDayRates_key=>$PerDayRates_value)
                  {
                    $PerDayRate = $PerDayRates_value->attributes(); 
                    
                    $data['stayDate'] .= (string)$PerDayRate['stayDate'].",";
                    $data['baseRate'] .= (string)$PerDayRate['baseRate'].",";
                    $data['promoName'] .= (string)$PerDayRate['promoName'].",";
                  }
                }


                $Total = $book->RoomStay->Total->attributes();
                $data['amountAfterTaxes'] = $Total['amountAfterTaxes'];
                $data['amountOfTaxes'] = $Total['amountOfTaxes'];
                if($book->RoomStay->PaymentCard){
                  $cardDetails = $book->RoomStay->PaymentCard->attributes();
                  $data['cardCode'] = "";
                  $data['cardNumber'] = "";                  
                  $data['seriesCode'] = "";
                  $data['expireDate'] = "";
                  if(count($cardDetails) != 0){
                    $data['cardCode'] = (string)$cardDetails['cardCode'];
                    $data['cardNumber'] = (string)$cardDetails['cardNumber'];
                    $data['expireDate'] = (string)$cardDetails['expireDate'];
                    $data['seriesCode'] = (string)$cardDetails['seriesCode'];
                  }

                  $cardHolder = $book->RoomStay->PaymentCard->CardHolder->attributes();
                  $data['name'] = "";
                  $data['address'] = "";
                  $data['city'] = "";
                  $data['stateProv'] = "";
                  $data['country'] = "";
                  $data['postalCode'] = "";
                  if(count($cardHolder) != 0){
                    $data['name'] = (string)$cardHolder['name'];
                    $data['address'] = (string)$cardHolder['address'];
                    $data['city'] = (string)$cardHolder['city'];
                    $data['stateProv'] = (string)$cardHolder['stateProv'];
                    $data['country'] = (string)$cardHolder['country'];
                    $data['postalCode'] = (string)$cardHolder['postalCode'];
                  }
                }                     
                $PrimaryGuestName = $book->PrimaryGuest->Name->attributes();
                $data['givenName'] = (string)$PrimaryGuestName['givenName'];
                $data['middleName'] = (string)$PrimaryGuestName['middleName'];
                $data['surname'] = (string)$PrimaryGuestName['surname'];
                
                $PrimaryGuestPhone = $book->PrimaryGuest->Phone->attributes();
                $data['countryCode'] = (string)$PrimaryGuestPhone['countryCode'];
                $data['cityAreaCode'] = (string)$PrimaryGuestPhone['cityAreaCode'];
                $data['number'] = (string)$PrimaryGuestPhone['number'];
                $data['extension'] = (string)$PrimaryGuestPhone['extension'];

                $PrimaryGuestEmail = $book->PrimaryGuest->Email;
                $data['Email'] = "";
                if($PrimaryGuestEmail!='')
                {
                  $data['Email'] = (string)$PrimaryGuestEmail;
                }
                $data['code'] = "";
                $data['reward_number'] = "";
                if($book->RewardProgram){
                  $RewardProgram  = $book->RewardProgram->attributes();         
                  if($RewardProgram['code'] != ""){
                    $data['code'] = (string)$RewardProgram['code'];
                  }
                  if($RewardProgram['number'] != ""){
                    $data['reward_number'] = (string)$RewardProgram['number'];
                  }
                }
                $data['SpecialRequest'] = "";
                foreach ($book->SpecialRequest as $special) {
                  $data['SpecialRequest'] .= (string)$special.",";
                }
                //$data['SpecialRequest'] = (string)$book->SpecialRequest;
                $available = get_data(PMS_EXP_RESER,array('partner_id'=>$partner_id,'property_id'=>$property_id,'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

                if(count($available)==0)
                {
                  insert_data(PMS_EXP_RESER,$data);
                }
                else
                {
                  update_data(PMS_EXP_RESER,$data,array('partner_id'=>$partner_id,'property_id'=>$property_id,'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
                }
              }else{
                update_data(PMS_EXP_RESER,$data,array('partner_id'=>$partner_id,'property_id'=>$property_id,'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
              }

              
              //$this->reservation_model->send_confirmation_email(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);
              //$this->reservation_log(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);
              $importBookingDetails = get_data(PMS_EXP_RESER,array('partner_id'=>$partner_id,'property_id'=>$property_id,'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();
              if(count($importBookingDetails)!=0)
              {
                $arrival = date('Y-m-d',strtotime($importBookingDetails['arrival'
                  ]));
                $departure = date('Y-m-d',strtotime($importBookingDetails['departure']));
                $mappingDetails   = get_data(PMS_EXP,array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rate_type_id'=>$importBookingDetails['ratePlanID'],'partner_id'=>$partner_id,'property_id'=>$property_id),'exp_id')->row_array();
                if(count($mappingDetails)==0)
                {
                  $mappingDetails = get_data(PMS_EXP,array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rateplan_id'=>$importBookingDetails['ratePlanID'],'partner_id'=>$partner_id,'property_id'=>$property_id),'exp_id')->row_array();
                }
                if(count($mappingDetails)!=0)
                {               
                  $roomMappingDetails = get_data(PMS_MAP,array('import_mapping_id'=>$mappingDetails['exp_id'],'channel_id'=>1))->row_array();
                  if(count($roomMappingDetails)!=0)
                  {
                    require_once(APPPATH.'controllers/mapping.php'); 
                    $callAvailabilities = new Mapping();
                    $callAvailabilities->importAvailabilities_pms($partner_id,$property_id,insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['room_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
                  }
                }
              }
              $confirm = date(DATE_ATOM);
              $confitm_time = explode("+", $confirm);
              $xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09"><Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/><Hotel id="'.$ch_details->hotel_channel_id.'"/><BookingConfirmNumbers>
                <BookingConfirmNumber bookingID="'.$data['booking_id'].'" bookingType="'.$data['type'].'" confirmNumber="'.$data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>
                </BookingConfirmNumbers>
                </BookingConfirmRQ>';
              
              $x_c_rq_data['channel_id']  = '1';            
              $x_c_rq_data['user_id']   = $partner_id;            
              $x_c_rq_data['hotel_id']  = $property_id;            
              $x_c_rq_data['message']   = $xml_confirm;           
              $x_c_rq_data['type']    = 'PMS_REQ_EXP';            
              $x_c_rq_data['section']   = 'PMS_RESER_CONFIRM_EXP';
              insert_data(ALL_XML,$x_c_rq_data);
                
              $URL = trim($book['bookingconfirm']);

              $ch = curl_init($URL);
              //curl_setopt($ch, CURLOPT_MUTE, 1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
              curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $output   = curl_exec($ch);
              $x_c_rs_data['channel_id'] = '1';           
              $x_c_rs_data['user_id'] = $partner_id;       
              $x_c_rs_data['hotel_id'] = $property_id;          
              $x_c_rs_data['message'] = $output;            
              $x_c_rs_data['type'] = 'PMS_RES_EXP';           
              $x_c_rs_data['section'] = 'PMS_RESER_CONFIRM_EXP';
              insert_data(ALL_XML,$x_c_rs_data);
              $data_api   = simplexml_load_string($output);
              $error =@$data_api->Error;
              if($error != "")
              {
                $meg['1']['result'] = '0';
                $meg['1']['content']= $error.' from '.$cha_name.'. Try again!';
              }
              $meg['1']['reser'] = $data['booking_id'].',';
            }
          }
        }
        $meg['1']['result'] = '1';
        $meg['1']['content']='Successfully import reservation from '.$cha_name.'!!!';
      }
    }
    else if(insep_decode($channel_id) == 2) 
    {
      $meg['2']['reser'] = "";
      if($ch_details->xml_type==1 || $ch_details->xml_type==2)
      {
        $xml_data ='<?xml version="1.0" encoding="UTF-8"?>
        <request>
        <username>'.$ch_details->user_name.'</username>
        <password>'.$ch_details->user_password.'</password>
        <hotel_id>'.$ch_details->hotel_channel_id.'</hotel_id>
        </request>';
        $x_r_rq_data['channel_id'] = '2';           
        $x_r_rq_data['user_id'] = $partner_id;
        $x_r_rq_data['hotel_id'] = $property_id;
        $x_r_rq_data['message'] = $xml_data;            
        $x_r_rq_data['type'] = 'PMS_BOOK_REQ';            
        $x_r_rq_data['section'] = 'PMS_RESER';            
        insert_data(ALL_XML,$x_r_rq_data);
        $URL = "https://secure-supply-xml.booking.com/hotels/xml/reservations";
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        
        $x_r_rs_data['channel_id'] = '2';           
        $x_r_rs_data['user_id'] = $partner_id;            
        $x_r_rs_data['hotel_id'] = $property_id;           
        $x_r_rs_data['message'] = $output;            
        $x_r_rs_data['type'] = 'PMS_BOOK_RES';            
        $x_r_rs_data['section'] = 'PMS_RESER';
        insert_data(ALL_XML,$x_r_rs_data);
        $data_api = simplexml_load_string($output);
        /* echo '<pre>';
        print_r($output); 
        print_r($data_api); */
        /* $book_response = '
        $data_api   =   simplexml_load_string($book_response); */
        /* echo '<pre>';
        print_r($data_api); */
        $ruid = "";
         preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
        $end = end($output);
        if(is_array($end)){
            $end_end = end($end);
            $ruid = str_replace("!-- RUID: [", '', $end_end);
            $ruid =  trim(str_replace('] --', '', $ruid));
            //$this->booking_model->store_ruid_booking($ruid,'PMS Reservation Import.');
        }else{
            $ruid = str_replace("!-- RUID: [", '', $end);
            $ruid =  trim(str_replace('] --', '', $ruid));
           //$this->booking_model->store_ruid_booking($ruid,'PMS Reservation Import.');
        } 
        $Error    = @$data_api->fault;
        
        if($Error)
        {
          $Error  = @$data_api->fault->attributes();
          $meg['2']['result'] = '0';
          $meg['2']['content']=$Error['string'].''.$cha_name.'!!!';
        }
        else
        {
          foreach($data_api as $reservation)
          {
            $new_rooms=array();
            foreach($reservation as $key=>$value)
            {
              if($key!='customer' && $key!='reservation_extra_info' && $key!='room')
              {
                $data[$key]         =   (string)$value;
              }
              else if($key=='reservation_extra_info')
              {
                $reservation_extra_info = $reservation->reservation_extra_info->flags;
              }
            }
            if($reservation_extra_info)
            {
              foreach($reservation_extra_info as $booker_value)
              {
                $flag = '';
                foreach($booker_value as $flag_value)
                {
                  $flag .= $flag_value->attributes().'###';
                }
              }
              $data['flags']=trim($flag,'###');
            }
            $user_details           =   get_data(PMS_PART_CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
            if(count($user_details)!=0)
            {
              $data['partner_id']          =   $user_details['partner_id'];
              $data['property_id']     =   $user_details['property_id'];
              $room_datas['partner_id']      =   $user_details['partner_id'];
              $room_datas['property_id']   =   $user_details['property_id'];
            }
            else
            {
              $data['partner_id']          =   0;
              $data['property_id']     =   0;
              $room_datas['partner_id']      =   0;
              $room_datas['property_id']   =   0;
            }
            $customer = $reservation->customer;
            if($customer)
            {
              foreach($customer as $cus_value)
              {
                foreach($cus_value as $cus_key=>$cust_value)
                {
                  if($cus_key == "cc_number" || $cus_key == "cc_type" || $cus_key == "cc_name" || $cus_key == "cc_cvc" || $cus_key == "cc_expiration_date")
                  {
                    if($data['status'] == 'modified'){
                      if($cust_value != ""){
                        $data[$cus_key]         =  (string)safe_b64encode($cust_value);//(string)$cust_value;//(string)insep_encode($cust_value);
                      }
                    }else{
                      $data[$cus_key]         =  (string)safe_b64encode($cust_value);
                    }
                  }else{
                    $data[$cus_key]       =   (string)$cust_value;
                  }
                }
              }
            }
            $room_datas['hotel_id']     = $data['hotel_id'];
            $room_datas['reservation_id']   = $data['id'];
            $room_datas['date_time']    = $data['date'].' '.$data['time'];
            $room_datas['status']       =  $data['status'];
            
            if($data['status'] =='new' || $data['status'] =='modified')
            {
              $book_available = get_data(PMS_BOOK_RESERV,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']))->row_array();
              if(count($book_available)==0)
              {
                insert_data(PMS_BOOK_RESERV,$data);
                $room_datas['import_reserv_id'] = $this->db->insert_id();
              }
              else
              {
                update_data(PMS_BOOK_RESERV,$data,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
              }
              $room_details   = $reservation->room;
              if($room_details)
              {
                foreach($room_details as $room_value)
                {
                  $i=0;
                  $room_datas['day_price_detailss'] = '';
                  $addons = '';
                  foreach($room_value as $room_key=>$addon)
                  {
                    if($room_key!='addons' && $room_key!='price')
                    { 
                      $room_datas[$room_key] =(string)$addon;
                    }
                    if($room_key=='price')
                    {
                      $room_details_price = $reservation->room->price[$i++];
                      //echo ($addon);
                      $price_date = $addon->attributes();
                      //$price_date = $addon;
                      $day_price_detailss='';
                      foreach($price_date as $price_key=>$price_value)
                      {
                        if($price_key=='rate_id')
                        {
                          $room_datas['rate_id'] = (string)$price_value;
                        }
                        //$day_price_detailss .=(string)$price_value.'~';
                        $day_price_detailss .=(string)$price_key.'='.(string)$price_value.'~';
                      }
                      $day_price_detailss.=$addon.'##';
                      $room_datas['day_price_detailss'] .= ($day_price_detailss);
                      
                    }
                    elseif($room_key=='addons')
                    {
                      $addons = $addon;
                    }
                  }
                  $room_datas['day_price_detailss'] = trim($room_datas['day_price_detailss'],'###');
                  $new_rooms[]  =   $room_datas['roomreservation_id'];

                  $room_availale  = get_data(PMS_BOOK_ROOMS,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']))->row_array();
                  $data_addons['roomreservation_id'] = $room_datas['roomreservation_id'];
                  
                  if(count($room_availale) == 0)
                  {
                    $this->db->insert(PMS_BOOK_ROOMS,$room_datas);
                    
                    //$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['partner_id'],$user_details['property_id']);
                    //$data_addons['room_resrv_id'] = $this->db->insert_id();
                  }
                  else
                  {
                    update_data(PMS_BOOK_ROOMS,$room_datas,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']));
                    //$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['partner_id'],$user_details['property_id']);
                  }
                  
                  if($addons)
                  {
                    $join_array=array();
                    $joined_array=array();
                    foreach($addons as $addon)
                    {
                      foreach($addon as $addon_key=>$addon_val)
                      {
                        $data_addon[$addon_key]= (string)$addon_val;
                      }
                      $joined_array=array_merge_recursive($join_array,$data_addon);
                      $join_array=array_merge_recursive($join_array,$data_addon);
                      $data_addons['addons_values'] = json_encode($joined_array);
                    }
                    $available_addons = get_data(PMS_BOOK_ADDON,array('roomreservation_id'=>$data_addons['roomreservation_id']))->row_array();
                    if(count($available_addons)==0)
                    {
                      insert_data(PMS_BOOK_ADDON,$data_addons);
                    }
                    else
                    {
                      update_data(PMS_BOOK_ADDON,$data_addons,array('roomreservation_id'=>$data_addons['roomreservation_id']));
                    }
                  }
                }   
              }
              
              $chk_rooms = get_data(PMS_BOOK_ROOMS,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']),'roomreservation_id')->result_array();
              
              $names = array_column($chk_rooms, 'roomreservation_id');
              
              $result = array_diff($names, $new_rooms);
             
              if($result)
              {
                foreach($result as $un_rooms)
                {
                  $importBookingDetails = get_data(PMS_BOOK_ROOMS,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'roomreservation_id'=>$un_rooms,'hotel_id'=>$data['hotel_id']))->row_array();
                  if(count($importBookingDetails)!=0)
                  {
                    $arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
                    $departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
                    $mappingDetails   = get_data(PMS_BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'partner_id'=>$data['partner_id'],'property_id'=>$data['property_id']),'book_id')->row_array();
                    if(count($mappingDetails)!=0)
                    {               
                      $roomMappingDetails = get_data(PMS_MAP,array('import_mapping_id'=>$mappingDetails['book_id'],'channel_id'=>2))->row_array();
                      if(count($roomMappingDetails)!=0)
                      {
                        require_once(APPPATH.'controllers/mapping.php'); 
                        $callAvailabilities = new Mapping();
                        $callAvailabilities->importAvailabilities_pms($user_details['partner_id'],$user_details['property_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['room_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
                      }
                    }
                  }
                  delete_data(PMS_BOOK_ROOMS,array('roomreservation_id'=>$un_rooms));
                }
              }
            }
            elseif($data['status'] =='cancelled')
            {
              $cancel_data['status']    = $data['status'];
              $room_datas['date_time']  = $data['date'].' '.$data['time'];            
              update_data(PMS_BOOK_RESERV,$cancel_data,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
              
              update_data(PMS_BOOK_ROOMS,$cancel_data,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']));

            }

            $getRoomDetails = get_data(PMS_BOOK_ROOMS,array('partner_id'=>$data['partner_id'],'property_id'=>$data['property_id'],'reservation_id'=>$data['id'],'hotel_id'=>$data['hotel_id']))->result_array();

            if(count($getRoomDetails)!=0)
            {
              foreach($getRoomDetails as $importBookingDetails)
              {
                $arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
                $departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
                $mappingDetails   = get_data(PMS_BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'partner_id'=>$data['partner_id'],'property_id'=>$data['property_id']),'book_id')->row_array();
                if(count($mappingDetails)!=0)
                {               
                  $roomMappingDetails = get_data(PMS_MAP,array('import_mapping_id'=>$mappingDetails['book_id'],'channel_id'=>2))->row_array();
                  if(count($roomMappingDetails)!=0)
                  {
                    require_once(APPPATH.'controllers/mapping.php'); 
                    $callAvailabilities = new Mapping();
                    $callAvailabilities->importAvailabilities_pms($data['partner_id'],$data['property_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['room_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['book_id'],'mapping');
                  }
                }
              }
            }
           // $this->booking_model->send_mail_to_hoteliers($data['id'],$user_details['partner_id'],$user_details['property_id']);
            //$this->booking_model->store_ruid_booking($ruid,'PMS Reservation Import',$data['partner_id'],$data['property_id']);
            $meg['2']['reser'] = $data['id'].',';
          }       
          $meg['2']['result'] = '1';
          $meg['2']['content']='Successfully import reservation from '.$cha_name.'!!!';
          
        }
      }
      else
      {
        $meg['2']['result'] = '1';
        $meg['2']['content']="Can't import reservation from ".$cha_name."!!!";
      }
    }
    if(isset($meg)){
      return $meg;
    }
  }

  function getReservationById($booking_id,$channel_id){
      if($channel_id == 11)
      {
        $reservation =get_data(PMS_REC_RESERV,array('IDRSV'=>$booking_id));
        if($reservation->num_rows != 0)
        {
          return $reservation->row_array();
        }
      }else if($channel_id == 1)
      {
        $reservation =get_data(PMS_EXP_RESER,array('booking_id'=>$booking_id));
        if($reservation->num_rows != 0)
        {
          return $reservation->row_array();
        }
      }else if($channel_id == 2)
      {
        $reservation =get_data(PMS_BOOK_RESERV,array('id'=>$booking_id));
        if($reservation->num_rows != 0)
        {
          $res = $reservation->row_array();
          $resrooms =get_data(PMS_BOOK_ROOMS,array('reservation_id'=>$booking_id));
          if($resrooms->num_rows != 0)
          {
            foreach($resrooms->result_array() as $rooms)
            {
                $res['Rooms'][$rooms['roomreservation_id']]= $rooms;
                $addons = get_data(PMS_BOOK_ADDON,array('roomreservation_id'=> $rooms['roomreservation_id']));
                if($addons->num_rows() != 0){
                  foreach($addons->result_array() as $addon){
                    $res['Rooms'][$rooms['roomreservation_id']]['Addons'][] =  $addon;
                  }
                }
            }
          }
        }
        return $res;
      }else if($channel_id == 8)
      {
        $reservation =get_data(PMS_GTA_RESERV,array('booking_id'=>$booking_id));
        if($reservation->num_rows != 0)
        {
          return $reservation->row_array();
        }
      }else if($channel_id == 5)
      {
        $reservation =get_data(PMS_HBD_RESERV,array('booking_id'=>$booking_id));
        if($reservation->num_rows != 0)
        {
          return $reservation->row_array();
        }
      }
  }

  function getResrvationFromDb($owner_id,$hotel_id,$datefrom,$dateto,$channel_id)
  {
    if($channel_id != "")
    {
      $response[$channel_id] = $this->get_reservation_by_channel($owner_id,$hotel_id,$channel_id,$datefrom,$dateto);
    }else{
      $channels = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'status'=>'enabled'))->result();
      foreach($channels as $channel)
      {
        $response[$channel->channel_id] = $this->get_reservation_by_channel($owner_id,$hotel_id,$channel->channel_id,$datefrom,$dateto);
      }
    }
    return $response;
  }

  function get_reservation_by_channel($owner_id,$hotel_id,$channel_id,$from,$to)
  {
    $response = array();
    if($channel_id == 1)
    {
      if($from != "" && $to != ""){
         $start_date = explode('+',date(DATE_ATOM, strtotime($from)))[0];
         $end_date = explode('+',date(DATE_ATOM, strtotime($to)))[0];
         $sql = "SELECT * FROM ".EXP_RESERV." WHERE created_time >= '".$start_date."' AND created_time <= '".$end_date."' AND hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
      }else{
        $sql = "SELECT * FROM ".EXP_RESERV." WHERE hotel_id = '".$hotel_id."' AND user_id = '".$user_id."'";
      }
      $response = $this->db->query($sql)->result_array();

    }else if($channel_id == 2)
    {
       $start_date = $from;
       $end_date = $to;
       if($from != "" && $to != ""){
         $sql = "SELECT * FROM ".BOOK_RESERV." WHERE date >= '".$start_date."' AND date <= '".$end_date."' AND hotel_hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
       }else{
          $sql = "SELECT * FROM ".BOOK_RESERV." WHERE hotel_hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
       }
       //echo $sql;
       $reservation = $this->db->query($sql);
       $res = array();
       if($reservation->num_rows != 0)
        {
          $resa = $reservation->result_array();
          foreach($resa as $resp){
            $res[$resp['id']] = $resp;
            $resrooms =get_data(BOOK_ROOMS,array('reservation_id'=>$resp['id']));
            if($resrooms->num_rows != 0)
            {
              foreach($resrooms->result_array() as $rooms)
              {
                  $res[$resp['id']]['Rooms'][$rooms['roomreservation_id']]= $rooms;
                  $addons = get_data(BOOK_ADDON,array('roomreservation_id'=> $rooms['roomreservation_id']));
                  if($addons->num_rows() != 0){
                    foreach($addons->result_array() as $addon){
                      $res[$resp['id']]['Rooms'][$rooms['roomreservation_id']]['Addons'][] =  $addon;
                    }
                  }
              }
            }
          }
        }
        $response = $res;
    }else if($channel_id == 8)
    {
      $start_date = explode('+',date(DATE_ATOM, strtotime($from)))[0];
      $end_date = explode('+',date(DATE_ATOM, strtotime($to)))[0];
      if($from != "" && $to != ""){
        $sql = "SELECT * FROM ".GTA_RESERV." WHERE modifieddate >= '".$start_date."' AND modifieddate <= '".$end_date."' AND hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
      }else{
        $sql = "SELECT * FROM ".GTA_RESERV." WHERE modifieddate >= '".$start_date."' AND modifieddate <= '".$end_date."' AND hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
      }
      $response = $this->db->query($sql)->result_array();
    }else if($channel_id == 11)
    {
      $start_date = date(DATE_ATOM, strtotime($from));
      $end_date = date(DATE_ATOM, strtotime($to));
      if($from != "" && $to != ""){
        $sql = "SELECT * FROM ".REC_RESERV." WHERE RSVCREATE >= '".$start_date."' AND RSVCREATE <= '".$end_date."' AND user_id = '".$owner_id."' AND hotel_id = '".$hotel_id."'";
      }else{
        $sql = "SELECT * FROM ".REC_RESERV." WHERE hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
      }
      $response = $this->db->query($sql)->result_array();
    }
    else if($channel_id == 5)
    {
      $start_date = date(DATE_ATOM, strtotime($from));
      $end_date = date(DATE_ATOM, strtotime($to));
      if($from != "" && $to != ""){
        $sql = "SELECT * FROM ".HBEDS_RESER." WHERE timestamp >= '".$start_date."' AND timestamp <= '".$end_date."' AND user_id = '".$owner_id."' AND hotel_id = '".$hotel_id."'";
      }else{
        $sql = "SELECT * FROM ".HBEDS_RESER." WHERE hotel_id = '".$hotel_id."' AND user_id = '".$owner_id."'";
      }
      $response = $this->db->query($sql)->result_array();
    }
    return $response;
  }

  // PMS Booking Request And Response 22/11/2016

  function getResrvationFromDbpms($owner_id,$hotel_id,$datefrom,$dateto,$channel_id)
  {
    if($channel_id != "")
    {
      $response[$channel_id] = $this->get_reservation_by_channel_pms($owner_id,$hotel_id,$channel_id,$datefrom,$dateto);
    }else{
      $channels = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'status'=>'enabled'))->result();
      foreach($channels as $channel)
      {
        $response[$channel->channel_id] = $this->get_reservation_by_channel_pms($owner_id,$hotel_id,$channel->channel_id,$datefrom,$dateto);
      }
      $response[0] = $this->get_reservation_by_channel_pms($owner_id,$hotel_id,0,$datefrom,$dateto);
    }
    return $response;
  }

  function get_reservation_by_channel_pms($owner_id,$hotel_id,$channel_id,$from,$to)
  {
    
    $ch_details = get_data(CONNECT,array('user_id'=>$owner_id,'hotel_id'=>$hotel_id,'channel_id'=>2))->row();
    $bulk_resrevation = array();
    if($channel_id == '0')
    {
      $this->db->select('manage_reservation.reservation_id,manage_reservation.reservation_code,manage_reservation.status,manage_reservation.guest_name,manage_reservation.room_id,manage_reservation.channel_id,manage_reservation.start_date,manage_reservation.end_date,manage_reservation.booking_date,manage_reservation.currency_id,manage_reservation.price,manage_reservation.num_nights,manage_reservation.created_date as current_date_time,card_details.card_type as cctype,card_details.number as ccnumber,card_details.exp_month as ccdate,card_details.exp_year as ccyear,card_details.name as ccname');
      $this->db->order_by('manage_reservation.reservation_id','desc'); 
      $this->db->where('manage_reservation.user_id',$owner_id);
      $this->db->where('manage_reservation.hotel_id',$hotel_id);
      $this->db->join('card_details','card_details.resrv_id = manage_reservation.reservation_id');
      if($from !="" && $to != "")
      {
        $this->db->where('manage_reservation.booking_date >=',$from);
        $this->db->where('manage_reservation.booking_date <=',$to);
      }
      $this->db->where('manage_reservation.channel_id',0);
      
      $res = $this->db->get('manage_reservation');
      
      if($res->num_rows >0)
      {
          $bulk_resrevation = $res->result();
      }
      else
      {
          $bulk_resrevation = array();
      }
      return $bulk_resrevation;
    }
    if($channel_id == '1')
    {
      if($from != "" && $to != ""){
        $start_date = explode('+',date(DATE_ATOM, strtotime($from)))[0];
        $end_date = explode('+',date(DATE_ATOM, strtotime($to)))[0];
        $this->db->where('created_time >= ',$start_date);
        $this->db->where('created_time <= ',$end_date);
      }
        $this->db->order_by('import_reserv_id','desc'); 
        $this->db->where('user_id',$owner_id);
        $this->db->where('hotel_id',$hotel_id);
        $data = $this->db->get('import_reservation_EXPEDIA')->result();
        if($data)
        {
        $res = array();
        foreach($data as $val){
            //print_r($val);
            if($val->type == "Cancel"){
                $status = "Canceled";
            }else{
                $status =$val->type;
            }
            $roomtypeid = $val->roomTypeID;
            $rateplanid = $val->ratePlanID;
            $roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$owner_id,$hotel_id);
            if(count($roomdetails) !=0)
            {
                $roomtypeid = $roomdetails['roomtypeId'];
                $rateplanid = $roomdetails['rateplanid'];
            }
            $map_id = @get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$owner_id,'hotel_id'=>$hotel_id))->row()->map_id;
            $room_id = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$map_id))->row()->property_id))->row()->property_id;
            if(!$room_id)
            {
                $map_id = @get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$owner_id,'hotel_id'=>$hotel_id))->row()->map_id;
                $room_id = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$map_id))->row()->property_id))->row()->property_id;
            }
            $checkin=date('Y/m/d',strtotime($val->arrival));
            $checkout=date('Y/m/d',strtotime($val->departure));
            $nig =_datebetween($checkin,$checkout);
            $res[] = (object)array(
                'reservation_id' => $val->import_reserv_id,
                'reservation_code' => $val->booking_id,
                'status' => $status,
                'guest_name' => $val->givenName.' '.$val->middleName.' '.$val->surname,
                'room_id' => $room_id,
                'channelroomid' => $map_id,
                'channel_id' => $val->channel_id,
                'start_date' => $val->arrival,
                'end_date' => $val->departure,
                'booking_date' =>$val->created_time,
                'currency_id' => $val->currency,
                'price' => $val->amountAfterTaxes,
                'num_nights' => $nig, 
                'ccname' => $val->name,
                'ccnumber' => $val->cardNumber,
                'ccdate' => substr($val->expireDate, 0,2),
                'ccyear' => substr($val->expireDate, -2),
                'cccvc' => $val->cardCode,
                'cctype' => '',
                'current_date_time'=> $val->current_date_time,
            );

        }
        return $res;
        
        }
    }
    else if($channel_id == '11')
    {
        $start_date = date(DATE_ATOM, strtotime($from));
        $end_date = date(DATE_ATOM, strtotime($to));
        if($from != "" && $to != ""){
          $this->db->where('RSVCREATE >=',$start_date);
          $this->db->where('RSVCREATE <=',$end_date);
        }
        $this->db->order_by('import_reserv_id','desc'); 
        $this->db->where('user_id',$owner_id);
        $this->db->where('hotel_id',$hotel_id);
        
        $data = $this->db->get('import_reservation_RECONLINE')->result();
        if($data)
        {
        $reco = array();
        foreach($data as $val){
            //print_r($val);
            if($val->STATUS == 11){
                $status = "Reserved";
            }else if($val->STATUS == 12){
                $status = "Modify";
            }else if($val->STATUS == 13){
                $status = "Confirmed";
            }
            $map_id = @get_data(IM_RECO,array('CODE'=>$val->ROOMCODE,'user_id' => $owner_id,'hotel_id'=>$hotel_id))->row()->re_id;
            $room_id = @get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$map_id))->row()->property_id;
            $checkin=date('Y/m/d',strtotime($val->CHECKIN));
            $checkout=date('Y/m/d',strtotime($val->CHECKOUT));
            $nig =_datebetween($checkin,$checkout);
            $reco[] = (object)array(
                'reservation_id' => $val->import_reserv_id,
                'reservation_code' => $val->IDRSV,
                'status' => $status,
                'guest_name' => $val->FIRSTNAME,
                'room_id' => $room_id,
                'channelroomid' => $map_id,
                'channel_id' => $val->channel_id,
                'start_date' => $val->CHECKIN,
                'end_date' => $val->CHECKOUT,
                'booking_date' =>$val->RSVCREATE,
                'currency_id' => $val->CURRENCY,
                'price' => $val->REVENUE,
                'num_nights' => $nig,
                'ccname' => $val->NAME,
                'ccnumber' => $val->CCNUMBER,
                'ccdate' => substr($val->CCEXPIRYDATE, 0,2),
                'ccyear' => substr($val->CCEXPIRYDATE, -2),
                'cccvc' => '',
                'cctype' => '',
                'current_date_time' => $val->current_date_time,
                
            );

        }
        return $reco;
        }
    }
    else if($channel_id == "8")
    {
        $this->db->order_by('import_reserv_id','desc'); 
        $this->db->where('user_id',$owner_id);
        $this->db->where('hotel_id',$hotel_id);
        $start_date = explode('+',date(DATE_ATOM, strtotime($from)))[0];
        $end_date = explode('+',date(DATE_ATOM, strtotime($to)))[0];
        if($from != "" && $to != ""){

          $this->db->where('modifieddate >=',$start_date);
          $this->db->where('modifieddate <=',$end_date);
          
        }
        $data = $this->db->get('import_reservation_GTA')->result();
        if($data)
        {
            $gta = array();
            foreach($data as $val){
            //print_r($val);
            if($val->status == "Confirmed"){
                $status = "Confirmed";
            }else if($val->status == "Cancelled"){
                $status = "Canceled";
            }
            else {
                $status = "Modify";
            }
            $gta_id = @get_data(IM_GTA,array('ID'=>$val->room_id,'rateplan_id'=>$val->rateplanid,'user_id' => $owner_id,'hotel_id'=>$hotel_id))->row()->GTA_id;
            $gtaid = @get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$gta_id));
            if($gtaid->num_rows != 0){
                $room_id = $gtaid->row()->property_id;
            }else{
                $room_id = 0;
            }
            $checkin=date('Y/m/d',strtotime($val->arrdate));    
            $checkout=date('Y/m/d',strtotime($val->depdate));
            $nig =_datebetween($checkin,$checkout);
            $gta[] = (object)array(
                'reservation_id' => $val->import_reserv_id,
                'reservation_code' => $val->booking_id,
                'status' => $status,
                'guest_name' => $val->leadname,
                'room_id' => $room_id,
                'channelroomid' => $gta_id,
                'channel_id' => $val->channel_id,
                'start_date' => $val->arrdate,
                'end_date' => $val->depdate,
                'booking_date' =>$val->modifieddate,
                'currency_id' => $val->currencycode,
                'price' => $val->totalroomcost,
                'num_nights' => '',
                'ccname' => '',
                'ccnumber' => '',
                'ccdate' => '',
                'ccyear' => '',
                'cccvc' => '',
                'cctype' => '',
                'current_date_time' => $val->current_date_time,
            );

        }
            return $gta;
        }
    }
    else if($channel_id == "5")
    {
        $this->db->order_by('import_reserv_id','desc'); 
        $this->db->where('user_id',$owner_id);
        $this->db->where('hotel_id',$hotel_id);
        $start_date = date(DATE_ATOM, strtotime($from));
        $end_date = date(DATE_ATOM, strtotime($to));
        if($from != "" && $to != ""){
          $this->db->where('timestamp >=',$start_date);
          $this->db->where('timestamp <=',$end_date);
        }
        $data = $this->db->get('import_reservation_HOTELBEDS')->result();
        if($data)
        {
            $gta = array();
            foreach($data as $val)
            {
                //print_r($val);
                if($val->RoomStatus == "BOOKING")
                {
                    $status = "Confirmed";
                }
                else if($val->RoomStatus == "CANCELED")
                {
                    $status = "Canceled";
                }
                else if($val->RoomStatus == "MODIFIED")
                {
                    $status = "Modify";
                }
                    /* $htb_id = get_data(IM_HOTELBEDS_ROOMS,array('contract_name'=>$val->Contract_Name,'contract_code'=>$val->IncomingOffice,'characterstics' => $val->CharacteristicCode,'roomname' => $val->Room_code,'user_id' => user_id(),'hotel_id' => hotel_id())); */
                                
                    $htbb_id = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where contract_name='".$val->Contract_Name."' and contract_code='".$val->IncomingOffice."' and sequence='".$val->Contract_Code."' and user_id='".$owner_id."' and hotel_id='".$hotel_id."' having roomnames ='".$val->Room_code."' AND charactersticss ='".$val->CharacteristicCode."'");
        
                    //echo $this->db->last_query();
                    
                    if($htbb_id->num_rows != 0)
                    {
                        $htb_id = $htbb_id->row()->map_id; 
                        $htbid = get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$htb_id));
                        if($htbid->num_rows != 0)
                        {
                            $room_id = $htbid->row()->property_id;
                        }
                        else
                        {
                            $room_id = 0;
                        }
                    }
                    else
                    {
                        $htb_id = 0;
                        $room_id = 0;
                    }
                    $checkin=date('Y/m/d',strtotime($val->DateFrom));   
                    $checkout=date('Y/m/d',strtotime($val->DateTo));
                    $rateprice = explode(',', $val->Rate_DateFrom);
                    $rateendprice = explode(',', $val->Rate_DateTo);
                    $priceperdate = explode(',', $val->Amount);
                    $currencyprice = explode(',', $val->Currency);
                    $total_amount = 0;
                    for($i=0; $i < count($rateprice); $i++)
                    {
                        $originalstartDate = date('M d,Y',strtotime($rateprice[$i]));
                        $newstartDate = date("Y/m/d", strtotime($originalstartDate));
                        $originalendDate = date('M d,Y',strtotime($rateendprice[$i]));
                        $newendDate = date("Y/m/d", strtotime($originalendDate));
                        $begin = new DateTime($newstartDate);
                        $ends = new DateTime($newendDate);
                        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
                        foreach($daterange as $ran)
                        {
                            $total_amount = $total_amount + $priceperdate[$i];
                        }
                    }
                    $nig =_datebetween($checkin,$checkout);
                    $htb[] = (object)array(
                          'reservation_id' => $val->import_reserv_id,
                          'reservation_code' => $val->RefNumber,
                          'status' => $status,
                          'guest_name' => $val->Holder,
                          'room_id' => $room_id,
                          'channelroomid' => $htb_id,
                          'channel_id' => $val->channel_id,
                          'start_date' => $val->DateFrom,
                          'end_date' => $val->DateTo,
                          'booking_date' =>$val->CreationDate,
                          'currency_id' => $currencyprice[0],
                          'price' => $total_amount,
                          'num_nights' => $nig,
                          'ccname' => '',
                          'ccnumber' => '',
                          'ccdate' => '',
                          'ccyear' => '',  
                          'cccvc' => '',
                          'cctype' => '',   
                          'current_date_time' => $val->current_date_time,
                      );
            }
            
            return $htb;
        }
    }
    else if($channel_id == "2" && ( $ch_details->xml_type == 2 || $ch_details->xml_type == 1 ))
    {
        $this->db->order_by('import_reserv_id','desc'); 
        $this->db->where('user_id',$owner_id);
        $this->db->where('hotel_hotel_id',$hotel_id);
        if($from != "" && $to != "")
        {
          $this->db->where('date >=',$from);
          $this->db->where('date <=', $to);
        }
        $data = $this->db->get('import_reservation_BOOKING')->result();
        if($data)
        {
            $bkg = array();
            foreach($data as $val){
            //print_r($val);
                if($val->status == "new"){
                    $status = "Confirmed";
                }else if($val->status == "modified"){
                    $status = "Modify";
                }else if($val->status == "cancelled"){
                    $status = "Canceled";
                }
                $bk_details = booking_hotel_id();
                $bkg_rooms = get_data("import_reservation_BOOKING_ROOMS",array('reservation_id'=>$val->id, 'user_id'=>$owner_id, 'hotel_hotel_id'=>$hotel_id,'hotel_id'=>$bk_details))->result_array();

                foreach ($bkg_rooms as $bkroom) {
                  $book_id = @get_data("import_mapping_BOOKING",array('B_room_id'=>$bkroom['id'], 'B_rate_id'=>$bkroom['rate_id'],'owner_id' => $owner_id,'hotel_id' => $hotel_id))->row()->import_mapping_id;
                  $roomname = @get_data(MAP,array('channel_id'=>$val->channel_id,'import_mapping_id'=>$book_id));
                    if($roomname->num_rows != 0){
                        $room_id = $roomname->row()->property_id;
                    }else{
                        $room_id = 0;
                    }
                    if($bkroom['guest_name']!='')
                    {
                        $reservation_name   =   $bkroom['guest_name'];
                    }
                    else
                    {
                        $reservation_name   =   get_data(BOOK_RESERV,array('id'=>$bkroom['reservation_id']))->row();
                        $reservation_name   =   $reservation_name->first_name.' '.$reservation_name->last_name;
                    }

                    $bkg[] = (object)array(
                        'reservation_id' => $bkroom['room_res_id'],
                        'res_id' => $bkroom['reservation_id'],
                        'reservation_code' => $bkroom['roomreservation_id'],
                        'status' => $status,
                        'guest_name' => $reservation_name,
                        'room_id' => $room_id,
                        'channelroomid' => $book_id,
                        'channel_id' => $bkroom['channel_id'],
                        'start_date' => $bkroom['arrival_date'],
                        'end_date' => $bkroom['departure_date'],
                        'booking_date' =>$bkroom['date_time'],//$val->date,
                        'currency_id' => $bkroom['currencycode'],
                        'price' => $bkroom['totalprice'],
                        'num_nights' => 1,
                        'ccname' => safe_b64decode($val->cc_name),
                        'ccnumber' => safe_b64decode($val->cc_number),
                        'ccdate' => substr(safe_b64decode($val->cc_expiration_date), 0,2),
                        'ccyear' => substr(safe_b64decode($val->cc_expiration_date), -2), 
                        'cccvc' => safe_b64decode($val->cc_cvc),
                        'cctype' => safe_b64decode($val->cc_type),
                        'current_date_time' => $bkroom['current_date_time'],
                    );
                }                       
            }
            return $bkg;
        }
    }
    else
    {
      return $bulk_resrevation;
    }
  }

  function save_reservation_old($data)
  {
    extract($data);
    $isexist = get_data('manage_reservation',array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
    
    if($isexist->num_rows() == 0)
    {
      $start_date = date('d/m/Y',strtotime($start_date));
      $end_date = date('d/m/Y',strtotime($end_date));
      $taxes = get_data(TAX,array('user_id'=>$data['user_id']))->result_array();
      if(count($taxes)!=0)
      {
          foreach($taxes as $valuue)
          {
              extract($valuue);
              $t_data['user_id']=$user_id;
              $t_data['hotel_id']=$hotel_id;
              $t_data['reservation_id']=$reservation_code;
              $t_data['tax_name'] = $user_name;
              $t_data['tax_included'] = $included_price;
              $t_data['tax_price'] = $tax_rate;
              insert_data(R_TAX,$t_data);
          }
      }
      $room_count=$num_rooms;
      $this->db->set('reservation', 'reservation+'.$room_count.'', FALSE);
      $this->db->where('separate_date >=',$start_date);   
      $this->db->where('separate_date <=',$end_date); 
      $this->db->where('room_id',$room_id); 
      $this->db->update('room_update');
      
      
      //reservation updae
      
      $startDate = DateTime::createFromFormat("d/m/Y",$start_date);

      $endDate = DateTime::createFromFormat("d/m/Y",$end_date);

      $periodInterval = new DateInterval("P1D");

      //$endDate->add( $periodInterval );

      $period = new DatePeriod( $startDate, $periodInterval, $endDate );

      $endDate->add( $periodInterval );
      
      if($status != "Cancel")
      {
        foreach($period as $date)
        {
            $get_available = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id,'individual_channel_id'=>0,'separate_date'=>$date->format("d/m/Y")))->row();
            //$get_available = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")))->row()->availability;
            $diff = $room_count * 1;
            
            $upaval['availability'] = $get_available->availability - $diff ;
            
            $upaval['trigger_cal'] = '1';
            require_once(APPPATH.'controllers/mapping.php'); 
            $callAvailabilities = new Mapping();

            update_data(TBL_UPDATE,$upaval,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'individual_channel_id'=> 0,'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

            //$this->db->query('call  UpdateAvailabilityInMain("'.TBL_UPDATE.'","'.$upaval['availability'].'","'.current_user_type().'","'.hotel_id().'","'.$room_id.'","'.$date->format("d/m/Y").'")');
            $this->db->where('room_update_id !=',$get_available->room_update_id);
            $this->db->where('owner_id',$user_id);
            $this->db->where('hotel_id',$hotel_id);
            $this->db->where('room_id',$room_id);
            $this->db->where('separate_date',$date->format("d/m/Y"));
            //$this->db->set('availability','availability - '.$diff,false);
            $this->db->set('availability','CASE WHEN availability - '.$diff.' >=0 THEN availability-'.$diff.' WHEN availability-'.$diff.' < 0 AND individual_channel_id = 0 THEN availability-'.$diff.' WHEN availability-'.$diff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
            $this->db->update(TBL_UPDATE);

            $channel['channel_id'] = 0;
            $channel['property_id'] = $room_id;
            $channel['rate_id'] = 0;
            $channel['guest_count'] = 0;
            $channel['refun_type'] = 0;
            $channel['start'] = $start_date;
            $channel['end'] = $end_date;
            //update_data(TBL_UPDATE,$upaval,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

            $roomMappingDetails =   get_data(MAP,array('property_id' => $room_id,'owner_id'=>$user_id))->row_array();

            if(count($roomMappingDetails)!=0){
                
                $callAvailabilities->update_channel($user_id,$hotel_id,$upaval,$channel,$date->format("d/m/Y"),$mapping_id = "","manual");
            }

            //$callAvailabilities->update_subrooms($upaval['availability'],$channel,$date->format("d/m/Y"),current_user_type(),hotel_id());
            
        }
      }

      $data=array(
       'reservation_code'=>$reservation_code,
       'hotel_id'=>$hotel_id,
       'user_id'=>$user_id,
       'guest_name'=>$guest_name,
       'last_name'=>$last_name,
       'mobile'=>$mobile,
       'email'=>$email,
       'room_id'=>$room_id,
       'num_nights'=>$num_nights,
       'num_rooms' => $room_count,
       'members_count'=>$members_count,
       'start_date'=>$start_date,
       'end_date'=>$end_date,
       'booking_date'=>$booking_date,
       'price'=>$price,
       'pms' => 1,
       'description'=>$description,
       'currency_id'=>get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->currency))->row()->currency_id,
	   
	   
	   
      );

      if($status != "Book")
      {
        $data['modified_date'] = date('Y-m-d');
        if($status == "Cancel")
        {
          $data['status'] = "Canceled";
          $data['cancel_date'] = date('Y-m-d');
        }
      }
      /*$this->db->insert('manage_reservation',$data);*/
      
      if(insert_data('manage_reservation',$data))
      {

        $id =  $this->db->insert_id();
            
        if($exp_month!='' && $exp_year!='' && $card_number!='' && $card_type!='' && $card_name!='')
        {
          $card=array(
            'exp_month'=>$exp_month,
            'name'=>$card_name,
            'card_type' => $card_type,
            'securitycode' => $securitycode,
            'exp_year'=>$exp_year,
            'number'=>$card_number,
            'user_id'=>$user_id,
            'resrv_id'=>$id,
          );
          $this->db->insert('card_details',$card);
        }
        
        $save_note = array('type'=>'3','created_date'=>date('Y-m-d H:i:s'),'status'=>'unseen','reservation_id'=>$id,'user_id'=>$user_id,'hotel_id'=>$hotel_id);

        $ver = $this->db->insert('notifications',$save_note);
      }
    }else{
      $start_date = date('d/m/Y',strtotime($start_date));
      $end_date = date('d/m/Y',strtotime($end_date));
      $dataupdate=array(
         'reservation_code'=>$reservation_code,
         'hotel_id'=>$hotel_id,
         'user_id'=>$user_id,
         'guest_name'=>$guest_name,
         'last_name'=>$last_name,
         'mobile'=>$mobile,
         'email'=>$email,
         'room_id'=>$room_id,
         'num_nights'=>$num_nights,
         'num_rooms' => $num_rooms,
         'members_count'=>$members_count,
         'start_date'=>$start_date,
         'end_date'=>$end_date,
         'booking_date'=>$booking_date,
         'price'=>$price,
         'description'=>$description,
          'currency_id'=>get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->currency))->row()->currency_id,
      );
      if($status == "Modify" && $isexist->row()->status != "Canceled")
      {
          $dataupdate['modified_date'] = date('Y-m-d');
          
          $old_room = $isexist->row()->room_id;
          $old_room_count = $isexist->row()->num_rooms;
          $old_room_start_date = $isexist->row()->start_date;
          $old_room_end_date = $isexist->row()->end_date;
          if($old_room == $room_id)
          {
            if($old_room_start_date == $start_date && $old_room_end_date == $end_date)
            {
              if($old_room_count != $num_rooms)
              {
                  if($old_room_count > $num_rooms)
                  {
                    $diff = $old_room_count - $num_rooms;
                    $opr = '+';
                  }else{
                    $diff = $num_rooms - $old_room_count;
                    $opr = '-';
                  }
                  $this->updateAvailability($diff,$opr,$dataupdate);
                  update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
              }else{
                update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
              }
            }else{
              $diff = $old_room_count;
              $opr = '+';
              $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date);
              if($old_room_count != $num_rooms)
              {
                  if($old_room_count > $num_rooms)
                  {
                    $diff = $old_room_count - $num_rooms;
                    $opr = '+';
                  }else{
                    $diff = $num_rooms - $old_room_count;
                    $opr = '-';
                  }
                  $this->updateAvailability($diff,$opr,$dataupdate);
                  update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
              }else{
                $diff = $num_rooms;
                $opr = '-';
                $this->updateAvailability($diff,$opr,$dataupdate);
                update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
              }
            }
          }else{
            $diff = $old_room_count;
            $opr = "+";
            $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date,$old_room);
            $diff = $num_rooms;
            $opr = '-';
            $this->updateAvailability($diff,$opr,$dataupdate);
            update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
          }
      }else if($status == "Cancel" && $isexist->row()->status != "Canceled")
      {
          $dataupdate['cancel_date'] = date('Y-m-d');
          $dataupdate['modified_date'] = date('Y-m-d');
          $dataupdate['status'] = 'Canceled';
          $old_room = $isexist->row()->room_id;
          $old_room_count = $isexist->row()->num_rooms;
          $old_room_start_date = $isexist->row()->start_date;
          $old_room_end_date = $isexist->row()->end_date;
          $diff = $old_room_count;
          $opr = "+";
          $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date,$old_room);
          update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id));
      }

    }
    return true;
  }

  // PMS Multiple Room Booking Request And Response 07/12/2016
  function save_reservation($data)
  {
    extract($data);
    $isexist = get_data('manage_reservation',array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
    
    if($isexist->num_rows() == 0)
    {
      $start_date = date('d/m/Y',strtotime($start_date));
      $end_date = date('d/m/Y',strtotime($end_date));
      $taxes = get_data(TAX,array('user_id'=>$data['user_id']))->result_array();
      if(count($taxes)!=0)
      {
          foreach($taxes as $valuue)
          {
              extract($valuue);
              $t_data['user_id']=$user_id;
              $t_data['hotel_id']=$hotel_id;
              $t_data['reservation_id']=$reservation_code;
              $t_data['tax_name'] = $user_name;
              $t_data['tax_included'] = $included_price;
              $t_data['tax_price'] = $tax_rate;
              insert_data(R_TAX,$t_data);
          }
      }
      $room_count=$num_rooms;
      $this->db->set('reservation', 'reservation+'.$room_count.'', FALSE);
      $this->db->where('separate_date >=',$start_date);   
      $this->db->where('separate_date <=',$end_date); 
      $this->db->where('room_id',$room_id); 
      $this->db->update('room_update');
      
      
      //reservation updae
      
      $startDate = DateTime::createFromFormat("d/m/Y",$start_date);

      $endDate = DateTime::createFromFormat("d/m/Y",$end_date);

      $periodInterval = new DateInterval("P1D");

      //$endDate->add( $periodInterval );

      $period = new DatePeriod( $startDate, $periodInterval, $endDate );

      $endDate->add( $periodInterval );
      
      if($status != "Cancel" && $roomstatus != "Cancel")
      {
        foreach($period as $date)
        {
            $get_available = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id,'individual_channel_id'=>0,'separate_date'=>$date->format("d/m/Y")))->row();
            //$get_available = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")))->row()->availability;
            $diff = $room_count * 1;
            
            $upaval['availability'] = $get_available->availability - $diff ;
            
            $upaval['trigger_cal'] = '1';
            require_once(APPPATH.'controllers/mapping.php'); 
            $callAvailabilities = new Mapping();

            update_data(TBL_UPDATE,$upaval,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'individual_channel_id'=> 0,'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

            //$this->db->query('call  UpdateAvailabilityInMain("'.TBL_UPDATE.'","'.$upaval['availability'].'","'.current_user_type().'","'.hotel_id().'","'.$room_id.'","'.$date->format("d/m/Y").'")');
            $this->db->where('room_update_id !=',$get_available->room_update_id);
            $this->db->where('owner_id',$user_id);
            $this->db->where('hotel_id',$hotel_id);
            $this->db->where('room_id',$room_id);
            $this->db->where('separate_date',$date->format("d/m/Y"));
            //$this->db->set('availability','availability - '.$diff,false);
            $this->db->set('availability','CASE WHEN availability - '.$diff.' >=0 THEN availability-'.$diff.' WHEN availability-'.$diff.' < 0 AND individual_channel_id = 0 THEN availability-'.$diff.' WHEN availability-'.$diff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
            $this->db->update(TBL_UPDATE);

            $channel['channel_id'] = 0;
            $channel['property_id'] = $room_id;
            $channel['rate_id'] = 0;
            $channel['guest_count'] = 0;
            $channel['refun_type'] = 0;
            $channel['start'] = $start_date;
            $channel['end'] = $end_date;
            //update_data(TBL_UPDATE,$upaval,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

            $roomMappingDetails =   get_data(MAP,array('property_id' => $room_id,'owner_id'=>$user_id))->row_array();

            if(count($roomMappingDetails)!=0){
                
                $callAvailabilities->update_channel($user_id,$hotel_id,$upaval,$channel,$date->format("d/m/Y"),$mapping_id = "","manual");
            }

            //$callAvailabilities->update_subrooms($upaval['availability'],$channel,$date->format("d/m/Y"),current_user_type(),hotel_id());
            
        }
      }

      $data=array(
       'reservation_code'=>$reservation_code,
       'hotel_id'=>$hotel_id,
       'user_id'=>$user_id,
       'guest_name'=>$guest_name,
       'last_name'=>$last_name,
       'mobile'=>$mobile,
       'email'=>$email,
       'room_id'=>$room_id,
       'num_nights'=>$num_nights,
       'num_rooms' => $room_count,
       'members_count'=>$members_count,
       'start_date'=>$start_date,
       'end_date'=>$end_date,
       'booking_date'=>$booking_date,
       'price'=>$price,
       'pms' => 1,
       'description'=>$description,
       'currency_id'=>get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id))->row()-> currency))->row()->currency_id,
      );

      if($roomstatus != "Book")
      {
        $data['modified_date'] = date('Y-m-d');
        if($status == "Cancel" || $roomstatus != "Cancel")
        {
          $data['status'] = "Canceled";
          $data['cancel_date'] = date('Y-m-d');
        }
      }
      /*$this->db->insert('manage_reservation',$data);*/
      
      if(insert_data('manage_reservation',$data))
      {

        $id =  $this->db->insert_id();
            
        if($exp_month!='' && $exp_year!='' && $card_number!='' && $card_type!='' && $card_name!='')
        {
          $card=array(
            'exp_month'=>$exp_month,
            'name'=>$card_name,
            'card_type' => $card_type,
            'securitycode' => $securitycode,
            'exp_year'=>$exp_year,
            'number'=>$card_number,
            'user_id'=>$user_id,
            'resrv_id'=>$id,
          );
          $this->db->insert('card_details',$card);
        }
        
        $save_note = array('type'=>'3','created_date'=>date('Y-m-d H:i:s'),'status'=>'unseen','reservation_id'=>$id,'user_id'=>$user_id,'hotel_id'=>$hotel_id);

        $ver = $this->db->insert('notifications',$save_note);
      }
    }else{
      $start_date = date('d/m/Y',strtotime($start_date));
      $end_date = date('d/m/Y',strtotime($end_date));
      $dataupdate=array(
         'reservation_code'=>$reservation_code,
         'hotel_id'=>$hotel_id,
         'user_id'=>$user_id,
         'guest_name'=>$guest_name,
         'last_name'=>$last_name,
         'mobile'=>$mobile,
         'email'=>$email,
         'room_id'=>$room_id,
         'num_nights'=>$num_nights,
         'num_rooms' => $num_rooms,
         'members_count'=>$members_count,
         'start_date'=>$start_date,
         'end_date'=>$end_date,
         'booking_date'=>$booking_date,
         'price'=>$price,
         'description'=>$description,
         'currency_id'=>get_data(TBL_CUR,array('currency_id'=>get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->currency))->row()->currency_id,
      );
      if($status == "Modify" && $isexist->row()->status != "Canceled")
      {
        if($roomstatus != "Cancel")
        {
          $dataupdate['modified_date'] = date('Y-m-d');
          
          $old_room = $isexist->row()->room_id;
          $old_room_count = $isexist->row()->num_rooms;
          $old_room_start_date = $isexist->row()->start_date;
          $old_room_end_date = $isexist->row()->end_date;
          if($old_room == $room_id)
          {
            if($old_room_start_date == $start_date && $old_room_end_date == $end_date)
            {
              if($old_room_count != $num_rooms)
              {
                  if($old_room_count > $num_rooms)
                  {
                    $diff = $old_room_count - $num_rooms;
                    $opr = '+';
                  }else{
                    $diff = $num_rooms - $old_room_count;
                    $opr = '-';
                  }
                  $this->updateAvailability($diff,$opr,$dataupdate);
                  update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
              }else{
                update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
              }
            }else{
              $diff = $old_room_count;
              $opr = '+';
              $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date);
              if($old_room_count != $num_rooms)
              {
                  if($old_room_count > $num_rooms)
                  {
                    $diff = $old_room_count - $num_rooms;
                    $opr = '+';
                  }else{
                    $diff = $num_rooms - $old_room_count;
                    $opr = '-';
                  }
                  $this->updateAvailability($diff,$opr,$dataupdate);
                  update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id' => $room_id));
              }else{
                $diff = $num_rooms;
                $opr = '-';
                $this->updateAvailability($diff,$opr,$dataupdate);
                update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
              }
            }
          }else{
            $diff = $old_room_count;
            $opr = "+";
            $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date,$old_room);
            $diff = $num_rooms;
            $opr = '-';
            $this->updateAvailability($diff,$opr,$dataupdate);
            update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
          }
        }else{
          $dataupdate['cancel_date'] = date('Y-m-d');
          $dataupdate['modified_date'] = date('Y-m-d');
          $dataupdate['status'] = 'Canceled';
          $old_room = $isexist->row()->room_id;
          $old_room_count = $isexist->row()->num_rooms;
          $old_room_start_date = $isexist->row()->start_date;
          $old_room_end_date = $isexist->row()->end_date;
          $diff = $old_room_count;
          $opr = "+";
          $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date,$old_room);
          update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));

        }
      }else if($status == "Cancel" && $isexist->row()->status != "Canceled")
      {
          $dataupdate['cancel_date'] = date('Y-m-d');
          $dataupdate['modified_date'] = date('Y-m-d');
          $dataupdate['status'] = 'Canceled';
          $old_room = $isexist->row()->room_id;
          $old_room_count = $isexist->row()->num_rooms;
          $old_room_start_date = $isexist->row()->start_date;
          $old_room_end_date = $isexist->row()->end_date;
          $diff = $old_room_count;
          $opr = "+";
          $this->updateAvailability($diff,$opr,$dataupdate,$old_room_start_date,$old_room_end_date,$old_room);
          update_data('manage_reservation',$dataupdate,array('reservation_code'=>$reservation_code,'user_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id));
      }

    }
    return true;
  }
  // PMS Multiple Booking Request And Response 07/12/2016

  function updateAvailability($diff,$opr,$data,$from ="", $to ="",$roomid = ""){

    extract($data);
    if($from != "")
    {
      $start_date = $from;
    }
    if($to != "")
    {
      $end_date = $to;
    }
    if($roomid != "")
    {
      $room_id = $roomid;
    }
    $startDate = DateTime::createFromFormat("d/m/Y",$start_date);

    $endDate = DateTime::createFromFormat("d/m/Y",$end_date);

    $periodInterval = new DateInterval("P1D");

    //$endDate->add( $periodInterval );

    $period = new DatePeriod( $startDate, $periodInterval, $endDate );

    $endDate->add( $periodInterval );
    
    foreach($period as $date)
    {
        $get_available = get_data(TBL_UPDATE,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'room_id'=>$room_id,'individual_channel_id'=>0,'separate_date'=>$date->format("d/m/Y")))->row();
        //$get_available = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")))->row()->availability;
        
        if($opr == "+"){
          $upaval['availability'] = $get_available->availability + $diff ;
        }else{
          $upaval['availability'] = $get_available->availability - $diff ;
        }
        $upaval['trigger_cal'] = '1';
        require_once(APPPATH.'controllers/mapping.php'); 
        $callAvailabilities = new Mapping();

        update_data(TBL_UPDATE,$upaval,array('owner_id'=>$user_id,'hotel_id'=>$hotel_id,'individual_channel_id'=> 0,'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

        //$this->db->query('call  UpdateAvailabilityInMain("'.TBL_UPDATE.'","'.$upaval['availability'].'","'.current_user_type().'","'.hotel_id().'","'.$room_id.'","'.$date->format("d/m/Y").'")');
        $this->db->where('room_update_id !=',$get_available->room_update_id);
        $this->db->where('owner_id',$user_id);
        $this->db->where('hotel_id',$hotel_id);
        $this->db->where('room_id',$room_id);
        $this->db->where('separate_date',$date->format("d/m/Y"));
        //$this->db->set('availability','availability - '.$diff,false);
        $this->db->set('availability','CASE WHEN availability '.$opr.$diff.' >=0 THEN availability'.$opr.$diff.' WHEN availability'.$opr.$diff.' < 0 AND individual_channel_id = 0 THEN availability'.$opr.$diff.' WHEN availability'.$opr.$diff.' < 0 AND individual_channel_id = 0 THEN 0 END' ,false);
        $this->db->update(TBL_UPDATE);

        $channel['channel_id'] = 0;
        $channel['property_id'] = $room_id;
        $channel['rate_id'] = 0;
        $channel['guest_count'] = 0;
        $channel['refun_type'] = 0;
        $channel['start'] = $start_date;
        $channel['end'] = $end_date;
        //update_data(TBL_UPDATE,$upaval,array('owner_id'=>user_id(),'room_id'=>$room_id,'separate_date'=>$date->format("d/m/Y")));

        $roomMappingDetails =   get_data(MAP,array('property_id' => $room_id,'owner_id'=>$user_id))->row_array();

        if(count($roomMappingDetails)!=0){
            
            $callAvailabilities->update_channel($user_id,$hotel_id,$upaval,$channel,$date->format("d/m/Y"),$mapping_id = "","manual");
        }

        //$callAvailabilities->update_subrooms($upaval['availability'],$channel,$date->format("d/m/Y"),current_user_type(),hotel_id());
        
    }
  }

  // PMS Booking Request And Response 22/11/2016
}




?>