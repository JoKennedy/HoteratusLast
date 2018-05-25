<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class despegar extends Front_Controller {

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


function getReservation($channel_id)
    {
        
	//<SelectionCriteria SelectionType="All" DateType="LastUpdateDate" Start="'.date('Y-m-d', strtotime("-30 days")).'" End="'.date('Y-m-d').'" ResStatus="Book"/>
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }

        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>36,'status'=>'enabled'))->row();

        if($ch_details)
        {




                $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                              <ReadRequests>
                                <HotelReadRequest HotelCode="'.$ch_details->hotel_channel_id.'">
                                  <SelectionCriteria SelectionType="All" DateType="LastUpdateDate" Start="1999-01-01" End="'.date('Y-m-d').'" ResStatus="Book"/>
                                </HotelReadRequest>
                              </ReadRequests>
                            </OTA_ReadRQ>';
        
               
                     $URL = 'https://channel.despegar.com/v1/hotels/reservations/get';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="'.$ch_details->user_name.'", 	password="'.$ch_details->user_password.'", hotel="'.$ch_details->hotel_channel_id.'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 
                    
                    if($response!='')
                    {
                        $datas['Error'] = $response;
                    }
                    else
                    {
                        $BookingListing = $data_api->ReservationsList;
                        foreach($BookingListing as $Booking)
                        {   
                            foreach( $Booking->HotelReservation as $reservationdata )
                            {
                                $data['user_id'] = current_user_type();
                                $data['hotel_id'] = hotel_id();
                                $data['channel_id']=36;

                                $data['ResStatus'] =(string)$reservationdata->attributes()->ResStatus;
                                $data['CreateDateTime'] =(string) $reservationdata->attributes()->CreateDateTime;
                                $data['LastModifyDateTime'] = (string)$reservationdata->attributes()->LastModifyDateTime;
                                $data['ISOCountry'] =(string)$reservationdata->POS->Source->attributes()->ISOCountry;
                                $ResGlobalInfo= $reservationdata->ResGlobalInfo;
                                $data['ResID_Type'] =(string)$ResGlobalInfo->HotelReservationIDs->HotelReservationID->attributes()->ResID_Type;
                                $data['ResID_Value'] =(string)$ResGlobalInfo->HotelReservationIDs->HotelReservationID->attributes()->ResID_Value;
                                $result = "";
                                for($i = 0; $i < 10; $i++) 
                                {
                                $result .= mt_rand(0, 9);
                                }
                                $data['confirmation_number'] =(string)$result;

                                if($data['ResStatus'] != "Cancel")
                                {

                                    $RoomStay= $reservationdata->RoomStays->RoomStay;
                                    $data['Hotelid'] = $RoomStay->BasicPropertyInfo->attributes()->HotelCode;
                                    $RoomRate=$RoomStay->RoomRates->RoomRate;

                                    $data['RoomTypeCode'] = (string)$RoomRate->attributes()->RoomTypeCode;
                                    $data['RatePlanCode'] = (string)$RoomRate->attributes()->RatePlanCode;
                                    $data['NumberOfUnits'] = (string)$RoomRate->attributes()->NumberOfUnits;



                                    $data['arrival'] = (string)$RoomStay->TimeSpan->attributes()->Start;
                                    $data['departure'] = (string)$RoomStay->TimeSpan->attributes()->End;
                                    $data['Adult'] ="0";
                                    $data['Child'] ="0";
                                    $data['Infant'] ="0";
                                    $data['Baby'] ="0";


                                    foreach ($RoomStay->GuestCounts->GuestCount as $key ) 
                                    {
                                        if ($key->attributes()->AgeQualifyingCode == "10")
                                        {
                                        $data['Adult'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "8")
                                        {
                                        $data['Child'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "7")
                                        {
                                        $data['Infant'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "3")
                                        {
                                        $data['Baby'] =(string)$key->attributes()->Count;
                                        }
                                    }

                                    $data['Currency'] =(string)$RoomStay->Total->attributes()->CurrencyCode;

                                    $data['stayDate'] = "";
                                    $data['baseRate'] = "";
                                    $data['promoName'] = "";

                                    foreach ($RoomRate->Rates->Rate as $key )
                                    {
                                        $data['stayDate'] .= (string)$key->attributes()->EffectiveDate.",";
                                        $data['baseRate'] .= (string)$key->Total->attributes()->AmountAfterTax.",";
                                    }

                                    $data['AmountAfterTax'] = $RoomStay->Total->attributes()->AmountAfterTax;
                                    $data['amountOfTaxes'] = "0.00";

                                       $data['GuaranteeTypeCode'] ="";
                                        $data['expireDate'] = "";
                                        $data['CardType'] ="";
                                        $data['cardCode']="";
                                        $data['cardNumber'] ="";
                                        $data['name'] ="";

                                    if($ResGlobalInfo->Guarantee)
                                    {

                                        if($ResGlobalInfo->Guarantee->attributes()->GuaranteeType=="None") //Pago de pendiendo el tipo de garantia
                                        {

                                        }
                                        else
                                        {
                                            $PaymentCard =$ResGlobalInfo->Guarantee->GuaranteesAccepted->PaymentCard;
                                            $data['expireDate'] =(string) $PaymentCard->attributes()->ExpireDate;
                                            $data['CardType'] =(string) $PaymentCard->CardType;
                                            $data['cardCode']=(string) $PaymentCard->SeriesCode->attributes()->Mask;
                                            $data['cardNumber'] =(string) $PaymentCard->CardNumber->attributes()->Mask;
                                            $data['name'] =(string) $PaymentCard->CardHolderName;
                                        }
                                    }
                                    else
                                    {
                                        $AcceptedPayment = $ResGlobalInfo->DepositPayments->GuaranteePayment->AcceptedPayments->AcceptedPayment;
                                        $data['GuaranteeTypeCode'] =(string)$AcceptedPayment->attributes()->GuaranteeTypeCode;
                                        $PaymentCard = $ResGlobalInfo->DepositPayments->GuaranteePayment->AcceptedPayments->AcceptedPayment->PaymentCard;
                                        $data['expireDate'] = (string)$PaymentCard->attributes()->ExpireDate;
                                        $data['CardType'] =(string)$PaymentCard->CardType;
                                        $data['cardCode']=(string)$PaymentCard->SeriesCode->PlainText;
                                        $data['cardNumber'] =(string)$PaymentCard->CardNumber->PlainText;
                                        $data['name'] =(string)$PaymentCard->CardHolderName;
                                    }


                                    $PersonName =$reservationdata->ResGuests->ResGuest->Profiles->ProfileInfo->Profile->Customer->PersonName;
                                    $data['givenName'] =(string)$PersonName->GivenName;
                                    $data['middleName'] = "";
                                    $data['surname'] =(string)$PersonName->Surname;


                                    $available = get_data('import_reservation_DESPEGAR',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotelid'=>$data['Hotelid'],'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']))->row_array();

                                    if(count($available)==0)
                                    {
                                    $array_keys = array_keys($data);
                                    fetchColumn('import_reservation_DESPEGAR',$array_keys);
                                    insert_data('import_reservation_DESPEGAR',$data);
                                    }
                                    else
                                    {
                                    $array_keys = array_keys($data);
                                    fetchColumn('import_reservation_DESPEGAR',$array_keys);
                                    update_data('import_reservation_DESPEGAR',$data,array('user_id'=>current_user_type(),'hotelid'=>$data['Hotelid'],'hotel_id'=>hotel_id(),'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']));
                                    }

                                    if($data['ResStatus'] == "Modify")
                                    {
                                    $resid = get_data('import_reservation_DESPEGAR',array('user_id'=>current_user_type(),'hotelid'=>$data['Hotelid'],'hotel_id'=>hotel_id(),'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']))->row()->Import_reservation_ID;

                                    $history = array('channel_id'=>36,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['CreateDateTime'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

                                    $res = $this->db->insert('new_history',$history);
                                    }
                           

                                }//Final de diferente a Cancel

                            }//Fnal REservatondata

                        }//Final Bokings
                    
                    
                      $CAll =  $this->getReservationCancel(36);
                     
                        $datas['succes'] ='Insert';
                    }
           


                       
         }
        else
        {
            $datas['Enable'] = 'Enable';
        }

return $datas;

    }

function getReservationCancel($channel_id)
    {
        
    //<SelectionCriteria SelectionType="All" DateType="LastUpdateDate" Start="'.date('Y-m-d', strtotime("-30 days")).'" End="'.date('Y-m-d').'" ResStatus="Book"/>
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }

        $ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>36,'status'=>'enabled'))->row();

        if($ch_details)
        {




                $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                              <ReadRequests>
                                <HotelReadRequest HotelCode="'.$ch_details->hotel_channel_id.'">
                                  <SelectionCriteria SelectionType="All" DateType="LastUpdateDate" Start="1999-01-01" End="'.date('Y-m-d').'" ResStatus="Cancel"/>
                                </HotelReadRequest>
                              </ReadRequests>
                            </OTA_ReadRQ>';
        
               
                     $URL = 'https://channel.despegar.com/v1/hotels/reservations/get';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="wmVfAdDnFDfV9Wey4zSxvmWJ", username="'.$ch_details->user_name.'",   password="'.$ch_details->user_password.'", hotel="'.$ch_details->hotel_channel_id.'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 
                    
                    if($response!='')
                    {
                        $datas['Error'] = $response;
                    }
                    else
                    {
                        $BookingListing = $data_api->ReservationsList;
                        foreach($BookingListing as $Booking)
                        {   
                            foreach( $Booking->HotelReservation as $reservationdata )
                            {
                                $data['user_id'] = current_user_type();
                                $data['hotel_id'] = hotel_id();
                                $data['channel_id']=36;

                                $data['ResStatus'] =(string)$reservationdata->attributes()->ResStatus;
                                $data['CreateDateTime'] =(string) $reservationdata->attributes()->CreateDateTime;
                                $data['LastModifyDateTime'] = (string)$reservationdata->attributes()->LastModifyDateTime;
                                $data['ISOCountry'] =(string)$reservationdata->POS->Source->attributes()->ISOCountry;
                                $ResGlobalInfo= $reservationdata->ResGlobalInfo;
                                $data['ResID_Type'] =(string)$ResGlobalInfo->HotelReservationIDs->HotelReservationID->attributes()->ResID_Type;
                                $data['ResID_Value'] =(string)$ResGlobalInfo->HotelReservationIDs->HotelReservationID->attributes()->ResID_Value;
                                $result = "";
                                for($i = 0; $i < 10; $i++) 
                                {
                                $result .= mt_rand(0, 9);
                                }
                                $data['confirmation_number'] =(string)$result;

                                if($data['ResStatus'] == "Cancel")
                                {

                                    $RoomStay= $reservationdata->RoomStays->RoomStay;
                                    $data['Hotelid'] = $RoomStay->BasicPropertyInfo->attributes()->HotelCode;
                                    $RoomRate=$RoomStay->RoomRates->RoomRate;

                                    $data['RoomTypeCode'] = (string)$RoomRate->attributes()->RoomTypeCode;
                                    $data['RatePlanCode'] = (string)$RoomRate->attributes()->RatePlanCode;
                                    $data['NumberOfUnits'] = (string)$RoomRate->attributes()->NumberOfUnits;



                                    $data['arrival'] = (string)$RoomStay->TimeSpan->attributes()->Start;
                                    $data['departure'] = (string)$RoomStay->TimeSpan->attributes()->End;
                                    $data['Adult'] ="0";
                                    $data['Child'] ="0";
                                    $data['Infant'] ="0";
                                    $data['Baby'] ="0";


                                    foreach ($RoomStay->GuestCounts->GuestCount as $key ) 
                                    {
                                        if ($key->attributes()->AgeQualifyingCode == "10")
                                        {
                                        $data['Adult'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "8")
                                        {
                                        $data['Child'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "7")
                                        {
                                        $data['Infant'] =(string)$key->attributes()->Count;
                                        }
                                        elseif ($key->attributes()->AgeQualifyingCode == "3")
                                        {
                                        $data['Baby'] =(string)$key->attributes()->Count;
                                        }
                                    }

                                    $data['Currency'] =(string)$RoomStay->Total->attributes()->CurrencyCode;

                                    $data['stayDate'] = "";
                                    $data['baseRate'] = "";
                                    $data['promoName'] = "";

                                    foreach ($RoomRate->Rates->Rate as $key )
                                    {
                                        $data['stayDate'] .= (string)$key->attributes()->EffectiveDate.",";
                                        $data['baseRate'] .= (string)$key->Total->attributes()->AmountAfterTax.",";
                                    }

                                    $data['AmountAfterTax'] = $RoomStay->Total->attributes()->AmountAfterTax;
                                    $data['amountOfTaxes'] = "0.00";

                                       $data['GuaranteeTypeCode'] ="";
                                        $data['expireDate'] = "";
                                        $data['CardType'] ="";
                                        $data['cardCode']="";
                                        $data['cardNumber'] ="";
                                        $data['name'] ="";

                                    if($ResGlobalInfo->Guarantee)
                                    {

                                        if($ResGlobalInfo->Guarantee->attributes()->GuaranteeType=="None") //Pago de pendiendo el tipo de garantia
                                        {

                                        }
                                        else
                                        {
                                            $PaymentCard =$ResGlobalInfo->Guarantee->GuaranteesAccepted->PaymentCard;
                                            $data['expireDate'] =(string) $PaymentCard->attributes()->ExpireDate;
                                            $data['CardType'] =(string) $PaymentCard->CardType;
                                            $data['cardCode']=(string) $PaymentCard->SeriesCode->attributes()->Mask;
                                            $data['cardNumber'] =(string) $PaymentCard->CardNumber->attributes()->Mask;
                                            $data['name'] =(string) $PaymentCard->CardHolderName;
                                        }
                                    }
                                    else
                                    {
                                        $AcceptedPayment = $ResGlobalInfo->DepositPayments->GuaranteePayment->AcceptedPayments->AcceptedPayment;
                                        $data['GuaranteeTypeCode'] =(string)$AcceptedPayment->attributes()->GuaranteeTypeCode;
                                        $PaymentCard = $ResGlobalInfo->DepositPayments->GuaranteePayment->AcceptedPayments->AcceptedPayment->PaymentCard;
                                        $data['expireDate'] = (string)$PaymentCard->attributes()->ExpireDate;
                                        $data['CardType'] =(string)$PaymentCard->CardType;
                                        $data['cardCode']=(string)$PaymentCard->SeriesCode->PlainText;
                                        $data['cardNumber'] =(string)$PaymentCard->CardNumber->PlainText;
                                        $data['name'] =(string)$PaymentCard->CardHolderName;
                                    }


                                    $PersonName =$reservationdata->ResGuests->ResGuest->Profiles->ProfileInfo->Profile->Customer->PersonName;
                                    $data['givenName'] =(string)$PersonName->GivenName;
                                    $data['middleName'] = "";
                                    $data['surname'] =(string)$PersonName->Surname;


                                    $available = get_data('import_reservation_DESPEGAR',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'hotelid'=>$data['Hotelid'],'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']))->row_array();

                                    if(count($available)==0)
                                    {
                                    $array_keys = array_keys($data);
                                    fetchColumn('import_reservation_DESPEGAR',$array_keys);
                                    insert_data('import_reservation_DESPEGAR',$data);
                                    }
                                    else
                                    {
                                    $array_keys = array_keys($data);
                                    fetchColumn('import_reservation_DESPEGAR',$array_keys);
                                    update_data('import_reservation_DESPEGAR',$data,array('user_id'=>current_user_type(),'hotelid'=>$data['Hotelid'],'hotel_id'=>hotel_id(),'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']));
                                    }

                                    if($data['ResStatus'] == "Modify")
                                    {
                                    $resid = get_data('import_reservation_DESPEGAR',array('user_id'=>current_user_type(),'hotelid'=>$data['Hotelid'],'hotel_id'=>hotel_id(),'ResID_Type'=>$data['ResID_Type'],'ResID_Value'=>$data['ResID_Value']))->row()->Import_reservation_ID;

                                    $history = array('channel_id'=>36,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['CreateDateTime'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

                                    $res = $this->db->insert('new_history',$history);
                                    }
                           

                                }//Final de diferente a Cancel

                            }//Fnal REservatondata

                        }//Final Bokings
                    
                    
                    
                     
                        $datas['succes'] ='Insert';
                    }
           


                       
         }
        else
        {
            $datas['Enable'] = 'Enable';
        }

return $datas;

    }


    function maptochannel($channel_id,$property_id)
    {
         require_once(APPPATH.'models/despegar_model.php'); 
         $despegar_model         =   new despegar_model();
        $data['available']      =   get_data('import_mapping_DESPEGAR',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row_array();
        $data['mapping_values'] =   get_data("mapping_values",array('mapping_id'=>insep_decode($property_id)))->row_array();
        $data['despegar']   =    $despegar_model->get_mapping_rooms(insep_decode($channel_id),'update');
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        return $data;
    }

    function getchannel()
    {

                $re_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>36))->row();
                

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
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),36,(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content'].=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        
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
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),36,(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content'].=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        
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
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),36,(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content'].=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                      
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
                                            $update = $this->db->query('update import_mapping_DESPEGAR set nameroomtype = "'.$Name.'", maximumAdults ="'.$MaxAdultOccupancy.'", maximumChilds="'.$MaxChildOccupancy.'", hotel_name="'.$HotelName.'",maximumPaxes="'.$MaxOccupancy.'", minimumPaxes="'.$MinOccupancy.'", StandardOccupancy="'.$StandardOccupancy.'", Rate_Name ="'.$row->rateplanname.'",TaxPolicyType="'.$TaxPolicyType.'"   where channel_id=36 AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" and ratetypecode = "'.$row->RatePlanCode.'"');

                                            }
                                            else
                                            {

                                                $bdata['user_id'] =  current_user_type();
                                                $bdata['hotel_id'] =  hotel_id();
                                                $bdata['channel_id']= (int)36;
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
                        $meg['content'].='Succesfully import room rate information from '.$cha_name.'!!!' ;
                        return $meg;
                        
                    }


    }

}
?>