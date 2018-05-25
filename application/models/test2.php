

<?php
//844228
/*
$re_details["hotel_channel_id"] = "484034";
$re_details["user_name"] = "cert_484034";
$re_details["user_password"] = "wo0wJSSIql1o89OK74";
$re_details["web_id"] = "wmVfAdDnFDfV9Wey4zSxvmWJ";

*/
 
$re_details["hotel_channel_id"] = "2207564";
$re_details["user_name"] = "hoteratus--osiz";
$re_details["user_password"] = "4/^SLG@nzvuUTZX8-95!H?h._pWIBAkSv?tq=?R)";
$re_details["web_id"] = "3iugwswnjG9NbSM0wcNhHnU6";

/*
  
$re_details["hotel_channel_id"] = "359796";
$re_details["user_name"] = "cert_359796";
$re_details["user_password"] = "nPG5t8vT0CA4dw6fO5";
$re_details["web_id"] = "wmVfAdDnFDfV9Wey4zSxvmWJ";


 /*
$re_details["hotel_channel_id"] = "265022";
$re_details["user_name"] = "cert_265022";
$re_details["user_password"] = "j39e37kL1KEiJF5eZi";
$re_details["web_id"] = "wmVfAdDnFDfV9Wey4zSxvmWJ";

*/


              $xml_data = '<?xml version="1.0" encoding="UTF-8"?>
      <request>
      <username>'.$re_details["user_name"] .'</username>
      <password>'.$re_details["user_password"].'</password>
      <hotel_id>'.$re_details["hotel_channel_id"] .'</hotel_id>
      </request>';
        
               
                    $URL = 'https://secure-supply-xml.booking.com/hotels/xml/reservationssummary';
                    $mail_data = '<strong> Request </strong> <br>';
                    $mail_data .= $xml_data;



                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                   
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);

                    $mail_data .= '<strong> Response </strong> <br>';
                    $mail_data .= $output;    
                   // mail("datahernandez@gmail.com"," Despegar.Com Request and Response ",$mail_data2);
                    //mail("felix@Hoteratus.com"," Despegar.Com Request and Response ",$mail_data2);
                    $response = $data_api->Errors->Error;
 echo "<strong> Request </strong> <br>";
  echo "<br>";                  curl_close($ch); 
 echo "<br>";                   
echo htmlentities($xml_data);
echo "<br>";
echo "<br>";
echo "<br>";
 echo "<strong> Response </strong> <br>";
echo htmlentities($output);

echo "<br>";
echo "<pre>";
print_r($data_api);








/*

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


                         foreach ($RoomTypearray as $key => $rtype) 
                        {                           
                            $IdRoom=$rtype->attributes()->ID;
                            $MaxAdultOccupancy = $rtype->attributes()->MaxAdultOccupancy;
                            $MaxChildOccupancy = $rtype->attributes()->MaxChildOccupancy;
                            $MaxOccupancy = $rtype->attributes()->MaxOccupancy;
                            $MinOccupancy = $rtype->attributes()->MinOccupancy;
                            $StandardOccupancy = $rtype->TypeRoom->attributes()->StandardOccupancy;
                            $Name = $rtype->TypeRoom->attributes()->Name;

                    

                    
                            $data=array(    'import_mapping_id' =>'null',
                                            'user_id' => (int)current_user_type(),
                                            'hotel_id' => (int)hotel_id(),
                                            'channel_id'=>(int)insep_decode($channel_id),
                                            'hotelcode'=>(string)$hotel_id,
                                            'coderoomtype'=>(string)$IdRoom,
                                            'nameroomtype'=>(string)$Name,  
                                            'maximumAdults'=>(string)$MaxAdultOccupancy,
                                            'maximumChilds'=>(string)$MaxChildOccupancy, 
                                            'maximumPaxes'=>(string)$MaxOccupancy,
                                            'minimumPaxes'=>(string)$MinOccupancy,    
                                            'StandardOccupancy'=>(string)$StandardOccupancy  

                                           
                                        );


                      
                               
                                        
                            $first_query=$this->db->query('Select * from import_mapping_despegar where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" ');

                            if($first_query->num_rows >= 1)
                            {
                            $update = $this->db->query('update import_mapping_despegar set nameroomtype = "'.$Name.
                                '", maximumAdults ="'.$MaxAdultOccupancy.'", maximumChilds="'.$MaxChildOccupancy.'", maximumPaxes="'.$MaxOccupancy.'", minimumPaxes="'.$MinOccupancy.'", StandardOccupancy="'.$StandardOccupancy.'"  where channel_id="'.insep_decode($channel_id).'" AND user_id ="'.current_user_type().'" AND hotel_id="'.hotel_id().'" AND hotelCode="'.$hotel_id.'" AND codeRoomType="'.$IdRoom.'" ');

                            }
                            else
                            {


                                $insetar = $this->db->query('insert into import_mapping_despegar (`import_mapping_id`,`user_id`, `hotel_id`, `channel_id`, `HotelCode`,
                                 `coderoomtype`, `nameroomtype`, `maximumAdults`, `maximumChilds`, `maximumPaxes`, `minimumPaxes`, `StandardOccupancy`) values 
                                 (null,'.current_user_type().','.hotel_id().','.insep_decode($channel_id).','.$hotel_id.','.$IdRoom.',"'.
                                    $Name.'",'.$MaxAdultOccupancy.','.$MaxChildOccupancy.','.$MaxOccupancy.','.$MinOccupancy.','.$StandardOccupancy.')');

                             }
                             



                   
                        }



                        $meg['result'] = '1';
                        $meg['content']='Succesfully import room rate information from '.$cha_name.'!!!' ;
                        echo json_encode($meg);
                        return;
                    }

*/

//FINAL DE LA IMPORTACION DE HABITACIONES


                    //AQUI INICIA List RoomRates (Room + RatePlan)

/*

        $xml_data = '<OTA_HotelAvailGetRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.000">
                      <HotelAvailRequests>
                        <HotelAvailRequest>
                          <HotelRef HotelCode="'.$re_details["hotel_channel_id"].'"/>
                        </HotelAvailRequest>
                      </HotelAvailRequests>
                    </OTA_HotelAvailGetRQ>';
        
               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/availability/list';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
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

                          foreach ($data_api->AvailStatusMessages->AvailStatusMessage as $key ) 
                          {


                        echo("<pre>");
                        print_r($key->StatusApplicationControl->attributes()->InvCode);
                        print_r($key->StatusApplicationControl->attributes()->RatePlanCode);
                          }
  
                    }


//FINAL DE List RoomRates (Room + RatePlan)

///AQUI INICIA Get Availability and Restrictions
/*
        $xml_data = '<OTA_HotelAvailGetRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.000">
                      <HotelAvailRequests>
                        <HotelAvailRequest>
                          <DateRange Start="2018-12-01" End="2018-12-02" />
                          <HotelRef HotelCode="'.$re_details["hotel_channel_id"].'" />
                        </HotelAvailRequest>
                      </HotelAvailRequests>
                    </OTA_HotelAvailGetRQ>';
        
               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/availability/get';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
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
                        echo("<pre>");
                        print_r($data_api);
                    }
*/
//FINAL DE  Get Availability and Restrictions 

                    //INICIO DE Update Availability and Restrictions

                        //NOTA INVCODE ES EL NUMERO CODIGO DEL TIPO DE HABITACION

/*

                    /// TIPOS DE ACTUALIZACIONES

                  //Update Availability and All Possible Restrictions  
        $xml_data = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                    <AvailStatusMessages HotelCode="282445">
                    <AvailStatusMessage BookingLimit="950" BookingLimitMessageType="SetLimit">
                      <StatusApplicationControl Start="2018-12-01" End="2018-12-10" InvCode="74959" />
                    </AvailStatusMessage>
                    <AvailStatusMessage>
                      <StatusApplicationControl Start="2018-12-01" End="2018-12-02" RatePlanCode="27" InvCode="74959" />
                      <LengthsOfStay>
                        <LengthOfStay Time="3" TimeUnit="Day" MinMaxMessageType="MinLOS" />
                        <LengthOfStay Time="30" TimeUnit="Day" MinMaxMessageType="MaxLOS" />
                      </LengthsOfStay>
                      <RestrictionStatus Restriction="Arrival" Status="Close" />
                    </AvailStatusMessage>
                    <AvailStatusMessage>
                      <StatusApplicationControl Start="2018-12-01" End="2018-12-02" RatePlanCode="27" InvCode="74959" />
                      <RestrictionStatus Restriction="Departure" Status="Close" />
                    </AvailStatusMessage>
                    </AvailStatusMessages>
                    </OTA_HotelAvailNotifRQ>';
        
               //Update Only Availability

        $xml_data = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                      <AvailStatusMessages HotelCode="638049">
                        <AvailStatusMessage BookingLimit="10" BookingLimitMessageType="SetLimit">
                          <StatusApplicationControl Start="2018-12-01" End="2018-12-02" InvCode="121756" />
                        </AvailStatusMessage>
                      </AvailStatusMessages>
                    </OTA_HotelAvailNotifRQ>';

                        ///Update Some Restrictions

        $xml_data = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                      <AvailStatusMessages HotelCode="638049">
                        <AvailStatusMessage>
                          <StatusApplicationControl Start="2018-12-01" End="2018-12-02" RatePlanCode="2" InvCode="121756" />
                          <LengthsOfStay>
                            <LengthOfStay Time="3" TimeUnit="Day" MinMaxMessageType="MinLOS" />
                            <LengthOfStay Time="30" TimeUnit="Day" MinMaxMessageType="MaxLOS" />
                          </LengthsOfStay>
                        </AvailStatusMessage>
                      </AvailStatusMessages>
                    </OTA_HotelAvailNotifRQ>';


                        ///Set Stop-Sell for Room

        $xml_data = '<OTA_HotelAvailNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                      <AvailStatusMessages HotelCode="638049">
                        <AvailStatusMessage>
                          <StatusApplicationControl Start="2018-12-01" End="2018-12-02" RatePlanCode="2" InvCode="121756" />
                          <RestrictionStatus Status="Close"/>
                        </AvailStatusMessage>
                      </AvailStatusMessages>
                    </OTA_HotelAvailNotifRQ>';







                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/availability/update';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
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
                        echo("<pre>");
                        print_r($data_api);
                    }

*/
                                        //GET INVENTORY

/*

                    //DIFERENTES FORMAS DE CONSURTAL

                    ///Return Data for All Rooms

                     $xml_data = '<OTA_HotelInvCountRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.002" >
                                <HotelInvCountRequests>
                                    <HotelInvCountRequest>
                                        <DateRange Start="2018-06-10" End="2018-12-12" />
                                        <HotelRef HotelCode="282445" />
                                    </HotelInvCountRequest>
                                </HotelInvCountRequests>
                            </OTA_HotelInvCountRQ>';
        





        ///Filter by Room
                $xml_data = '<OTA_HotelInvCountRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.002" >
                                <HotelInvCountRequests>
                                    <HotelInvCountRequest>
                                        <DateRange Start="2018-06-10" End="2018-12-12" />
                                        <RoomTypeCandidates>
                                            <RoomTypeCandidate RoomTypeCode="16482" />
                                        </RoomTypeCandidates>
                                        <HotelRef HotelCode="638049" />
                                    </HotelInvCountRequest>
                                </HotelInvCountRequests>
                            </OTA_HotelInvCountRQ>';


///Request Two Date Ranges


                $xml_data = '<OTA_HotelInvCountRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.002" >
                            <HotelInvCountRequests>
                                <HotelInvCountRequest>
                                    <DateRange Start="2018-06-10" End="2018-06-20" />
                                    <HotelRef HotelCode="638049" />
                                </HotelInvCountRequest>
                                <HotelInvCountRequest>
                                    <DateRange Start="2018-07-10" End="2018-07-20" />
                                    <HotelRef HotelCode="638049" />
                                </HotelInvCountRequest>
                            </HotelInvCountRequests>
                        </OTA_HotelInvCountRQ>';



               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/inventory/get';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
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
                        echo("<pre>");
                        print_r($data_api);
                    }



                    */


                    ////Lists Rate Plans

/*
                    
  $xml_data = '<setStopSales>    <userLogin>CMP_USER</userLogin>    <userPassword>CMP_PASSWORD</userPassword>    <establishmentId>CMP_HOTELID</establishmentId>    <contractId>CMP_CONTRACTID</contractId>    <salesClosedConstraints>       <salesClosedConstraint>          <boards/>          <rooms/>          <stayFrom>2016-08-05T00:00:00Z</stayFrom>          <stayTo>2016-08-25T00:00:00Z</stayTo>          <stopSale>Y</stopSale>          <weekDays>XXXXXXX</weekDays>       </salesClosedConstraint>       <salesClosedConstraint>          <boards/>          <rooms/>          <stayFrom>2016-09-05T00:00:00Z</stayFrom>          <stayTo>2016-09-25T00:00:00Z</stayTo>          <stopSale>Y</stopSale>          <weekDays>XXXXXXX</weekDays>       </salesClosedConstraint>    </salesClosedConstraints> </setStopSales>';
        
               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/rate-plans/list';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                   // curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {echo($response);
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {

                          foreach ($data_api->RatePlans->RatePlan as $key => $rtype) 
                          {
                            echo $rtype->attributes()->RatePlanCode;
                            echo $rtype->Description->Text;

                          }
                       
                    }

                    echo "<pre>";
                    print_r($data_api);




///fINAL DE GET RATES

          /*          //////////UPDATE RATE

  $xml_data = '<OTA_HotelRatePlanNotifRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
  <RatePlans HotelCode="282445">
    <RatePlan ChargeTypeCode="19" CurrencyCode="USD" RatePlanCode="27">
      <Rates>
        <Rate Start="2018-12-11" End="2018-12-12" InvCode="74959">
          <BaseByGuestAmts>
            <BaseByGuestAmt AmountBeforeTax="100" NumberOfGuests="2" AgeQualifyingCode="10" />
          </BaseByGuestAmts>
          <AdditionalGuestAmounts>
            <AdditionalGuestAmount Amount="120" AgeQualifyingCode="10" />
          </AdditionalGuestAmounts>
        </Rate>
      </Rates>
    </RatePlan>
  </RatePlans>
</OTA_HotelRatePlanNotifRQ>';
        
               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/rate-plans/update';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {echo($response);
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {
                        echo("<pre>");
                        print_r($data_api);
                    }

                    */

// FINAL UPDATE

/*
                    ///RESERVAACIONES

                    //TIPO DE LLAMADAS

                    //Get Confirmed Reservations by Arrival 
  $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
              <ReadRequests>
                <HotelReadRequest HotelCode="282445">
                  <SelectionCriteria SelectionType="All" DateType="ArrivalDate" Start="2016-01-02" End="2016-01-03" ResStatus="Book"/>
                </HotelReadRequest>
              </ReadRequests>
            </OTA_ReadRQ>';
        
//Get Undelivered Reservations

          $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                     <ReadRequests>
                        <HotelReadRequest HotelCode="282445">
                          <SelectionCriteria SelectionType="Undelivered"/>
                        </HotelReadRequest>
                      </ReadRequests>
                    </OTA_ReadRQ>';

//Get Confirmed Reservations by Creation

    $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                  <ReadRequests>
                    <HotelReadRequest HotelCode="282445">
                      <SelectionCriteria SelectionType="All" DateType="CreateDate" Start="2015-10-13" End="2015-10-14" ResStatus="Book"/>
                    </HotelReadRequest>
                  </ReadRequests>
                </OTA_ReadRQ>';

//Get Confirmed Reservations by Last Update 


  $xml_data = '<OTA_ReadRQ xmlns="http://www.despegar.com/hotels/ota/v1/" Version="1.0">
                  <ReadRequests>
                    <HotelReadRequest HotelCode="'.$re_details["hotel_channel_id"].'">
                      <SelectionCriteria SelectionType="All" DateType="LastUpdateDate" Start="2015-10-13" End="2015-10-14" ResStatus="Book"/>
                    </HotelReadRequest>
                  </ReadRequests>
                </OTA_ReadRQ>';

               
                    $URL = 'https://channel-sandbox.despegar.com/v1/hotels/reservations/get';

                    //$URL = 'https://channel-sandbox.despegar.com/v1/hotels/reservations/notif';

                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Authorization: Channel-User api-key="'.$re_details["web_id"].'", username="'.$re_details["user_name"].'", password="'.$re_details["user_password"].'", hotel="'.$re_details["hotel_channel_id"].'"','Content-Type: application/xml' ));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    $data_api = simplexml_load_string($output);
                    $response = $data_api->Errors->Error;
                    curl_close($ch); 


                    if($response!='')
                    {echo($response);
                        $this->inventory_model->store_error(current_user_type(),hotel_id(),insep_decode($channel_id),(string)$response[0],'Get Channel',date('m/d/Y h:i:s a', time()));
                        $meg['result'] = '0';
                        $meg['content']=$response[0].' from '.$cha_name.'. Try again!'."'.$re_details->user_password.'";
                        echo json_encode($meg);
                    }
                    else
                    {
                       $BookingListing = $data_api->ReservationsList;
                       
                       foreach($BookingListing as $Bookings)

                        {

                            foreach ($Bookings->HotelReservation as $reservationdata)
                             {

                             
                             echo("<pre>");
                            $ResGlobalInfo=$reservationdata->ResGlobalInfo;
                             $RoomStay= $reservationdata->RoomStays->RoomStay;
                            $RoomRate=$RoomStay->RoomRates->RoomRate;

                           echo $reservationdata->ResGuests->ResGuest->Profiles->ProfileInfo->Profile->Customer->PersonName->GivenName;


                               


                             }   
                         
                            

                            
                          
                            echo("<pre>");
                            print_r($Bookings);
                        }


                        echo("<pre>");
                        print_r($data_api);
                    }


                    

/* TAMBIEN SE PUEDEN ENVIAR RESERVAS DESDE EL CANAL

Booking Handling (Push) Examples

These examples cannot be tested in the sandbox environment, but show the different message types that your endpoint may receive.

New Prepaid Reservation
New Cash Reservation
Modified Reservation with Guest Credit Card
Canceled Reservation


*/
?>