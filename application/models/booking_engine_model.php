<?php  

	class booking_engine_model extends CI_model
	{
		public function __construct(){
			parent::__construct();
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
    
	function get_reserve()
    {


		$hotel_id	=	insep_decode($_POST["hotel_id"]);

		$start_date		=	$_POST["dp1"]; 
		
		$end_date	 	=	$_POST["dp2"];
		
		$rooms 			=	$_POST["num_rooms"]; 
		
		$adult 			=	$_POST["num_person"]; 
		
		$child 			=	$_POST["num_child"];
		
		$checkin_date	=	date('Y-m-d',strtotime($start_date));
		
        $checkout_date	=	date('Y-m-d',strtotime($end_date));
		
		//$start			=	strtotime($checkin_date);
		
        //$end 			=	strtotime($checkout_date);

        		
        $nights	 		=    date_diff(date_create($checkin_date), date_create($checkout_date))->format('%d');

        $startDate      =   DateTime::createFromFormat("d/m/Y",$start_date);

        $endDate        =   DateTime::createFromFormat("d/m/Y",$end_date);

        $periodInterval =   new DateInterval( "P1D" );

        //$period         =   new DatePeriod( $startDate, $periodInterval, $endDate );


        $period = $this->getDates(date('Y-m-d',strtotime($_POST['dp1'])),date('Y-m-d',strtotime($_POST['dp2']."-1 days")),'1,2,3,4,5,6,7');
        

        if($period)
        {
            $base_room_result = array();
            $j=0;
            foreach ($period as $key => $value) {

                $baseRoom = $this->db->query('
                                                SELECT P.description , U.room_update_id, U.room_id , U.separate_date , U.minimum_stay , U.price, P.price as base_price , P.image , P.property_name , P.member_count , P.children , P.number_of_bedrooms FROM '.TBL_UPDATE.' U JOIN '.TBL_PROPERTY.' P ON U.room_id = P.property_id WHERE U.separate_date="'.$value.'" AND U.availability >="'.$rooms.'" AND U.minimum_stay <= "'.$nights.'" AND P.member_count >="'.$adult.'" AND P.children >="'.$child.'" AND individual_channel_id =0 AND stop_sell=0 AND P.hotel_id="'.$hotel_id.'" GROUP BY U.room_id ORDER BY U.room_id DESC'
                                             );
               					if($baseRoom->num_rows != 0) {

                    $base_room_value =  $baseRoom->result_array();

                    $total_base_room_result[$j++]= array_merge($base_room_result,$base_room_value);
                }
                else {

                    $total_base_room_result = array();

                    break;
                }
            }

            $sub_room_result = array();
            $i=0;
            foreach ($period as $key => $value) {

                $subRoom = $this->db->query('
                                                SELECT P.description , R.rate_name , U.separate_date , R.uniq_id , U.room_id , U.rate_types_id , U.price , U.minimum_stay , R.price as base_price , P.image , P.property_name , P.member_count , P.children , P.number_of_bedrooms FROM '.RATE_BASE.' U JOIN '.TBL_PROPERTY.' P ON U.room_id = P.property_id JOIN '.RATE_TYPES.' R ON U.room_id = R.room_id WHERE U.separate_date="'.$value.'" AND U.availability >="'.$rooms.'" AND U.minimum_stay <= "'.$nights.'" AND P.member_count >="'.$adult.'" AND P.children >="'.$child.'" AND individual_channel_id =0 AND stop_sell=0 AND P.hotel_id="'.$hotel_id.'" GROUP BY U.room_id ORDER BY U.room_id DESC'
                                             );
                if($subRoom->num_rows != 0) {

                    $sub_room_value =  $subRoom->result_array();

                    $total_sub_room_result[$i++]= array_merge($sub_room_result,$sub_room_value);
                }
                else {

                    $total_sub_room_result = array();

                    break;
                } 
            }
            if(count(@$total_base_room_result)!=0 || count(@$total_sub_room_result)!=0)
            {
				$final_room_result = array_merge($total_base_room_result,$total_sub_room_result);

				if(count($final_room_result)!=0)
				{
					return $final_room_result;
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
		else
		{
			return false;
		}
		}



	function getDates($start, $end, $weekday)
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

    function save_reservation()
    {


        $user_id = get_data(HOTEL,array('hotel_id'=>$_POST['hotelid']))->row()->owner_id;
        

        $Payment_Type =  $_POST['payment_type'];

        if(isset($_POST['email']))
        {
            $guestmail  =   $_POST['email'];
        }
        else
        {
            $guestmail  =   '';
        }

        $date1 = DateTime::createFromFormat('m-d-Y',  $_POST['date1'] );
        $date2 = DateTime::createFromFormat('m-d-Y',  $_POST['date2'] );

        $start_date     =  $date1->format('d-m-Y');

        $end_date       =   $date2->format('d-m-Y');

        $checkin_date   =   str_replace("/","-",$start_date);

        $checkout_date  =   str_replace("/","-",$end_date);

        $start          =   strtotime($checkin_date);

        $end            =   strtotime($checkout_date);

        $nights         =   ceil(abs($end - $start) / 86400);

        $rooms          =   1;

        $adult          =   $_POST['guests'];

        $child          =   $_POST['children'];

        $email          =   $_POST['email'];

        $first_name     =   $_POST['name'];

        $last_name      =   '';

        $phone          =   $_POST['phone'];

        $room_id        =   $_POST['roomid'];

        $rate_type_id   =   $_POST['rateid'];

        $notes          =   $_POST['notes'];

        $street_name    =   $_POST['street_name'];

        $country        =   $_POST['country'];

        $province       =   $_POST['province'];

        $city_name      =   $_POST['city_name'];

        $zipcode        =   $_POST['zipcode'];

        $arrivaltime    =   $_POST['arrivaltime'];

        $get_numrows    =   $this->db->query('SELECT * FROM manage_reservation');


        $reservation_code   =   sprintf('%08d',$get_numrows->num_rows()+100);


        $price          =   $_POST['amount']*$nights ;


        $R_taxes = get_data(TAX,array('hotel_id'=>$_POST['hotelid']))->result_array();


        if(count($R_taxes)!=0)
        {
            foreach($R_taxes as $valuue)
            {
                extract($valuue);

                $t_data['user_id']          =   $user_id;

                $t_data['hotel_id']         =   $_POST['hotelid'];

                $t_data['reservation_id']   =   $reservation_code;

                $t_data['tax_name']         =   $user_name;

                $t_data['tax_included']     =   $included_price;

                $t_data['tax_price']        =   $tax_rate;

                insert_data(R_TAX,$t_data);
            }
        }


        if($Payment_Type=='bt')
            {
                $bank_id      = $_GET['bank_type'];

                $bank_details = get_data('bank_details',array('bank_id'=>$bank_id))->row();

                ///extract($bank_details);

                $badata['account_owner'] = $bank_details->account_owner;
                $badata['currency'] = $bank_details->currency;
                $badata['bank_name'] = $bank_details->bank_name;
                $badata['branch_name'] = $bank_details->branch_name;
                $badata['branch_code'] = $bank_details->branch_code;
                $badata['swift_code'] = $bank_details->swift_code;
                $badata['iban'] = $bank_details->iban;
                $badata['account_number'] = $bank_details->account_number;

                $reference = mt_rand(1000000,99999999);

                $reference_code = $reference;

                $bank_deta = json_encode($badata);
            }

            if($Payment_Type=='bt')
           {
                $reference_code = $reference_code;
           }
           else
           {
                 $reference_code = '';
           }

           if($Payment_Type=='bt')
           {
                $bank_deta = $bank_deta;
           }
           else
           {
                $bank_deta = '';
           }


            $hotel_detail           =   get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>$_POST['hotelid']))->row()->currency;

                if  ($hotel_detail !=0)   {
                    $currencycodes   =   get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->currency_id;
                }
                else
                {
                    $currencycodes   = 1;
                }

        $data   =   array(
                            'reservation_code'=>$reservation_code,

                            'hotel_id'=>$_POST['hotelid'],

                            'user_id'=>$user_id,

                            'guest_name'=>$first_name,

                            'last_name'=>$last_name,

                            'mobile'=>$phone,

                            'email'=>$email,

                            'room_id'=>$room_id,

                            'rate_types_id'=>$rate_type_id,

                            'num_nights'=>$nights,

                            'num_rooms' => $rooms,

                            'members_count'=>$adult,

                            'children'=>$child,

                            'street_name'=>$street_name,

                            'country'=>$country,

                            'province'=>$province,

                            'city_name'=>$city_name,

                            'zipcode'=>$zipcode,

                            'start_date'=>$start_date,

                            'end_date'=>$end_date,

                            'booking_date'=>date('Y-m-d'),

                            'price'=>$price,

                            'description'=>$notes,

                            'price_details'=>insep_decode($_POST['detailsprice']),

                            'payment_method'=>$_POST['payment_type'],

                            'transaction_id'=>'',

                            'reference_code'=>$reference_code,

                            'bank_details'=>$bank_deta,

                            'currency_id'=>$currencycodes,
                            
                            'arrivaltime'=>$arrivaltime,
                            
                        );



        if(insert_data('manage_reservation',$data))
        {
            $id =  $this->db->insert_id();

            $this->load->model("room_auto_model");
            $Roomnumber           = $this->room_auto_model->Assign_room($data['hotel_id'],$room_id,$_POST['date1'],$_POST['date2']);
            $indata['RoomNumber'] = $Roomnumber;

            update_data('manage_reservation',$indata,array('user_id'=> $user_id,'hotel_id'=>$data['hotel_id']  ,'reservation_id' => $id));

            $exp_month=$_POST['exp_month'];
            $exp_year=$_POST['exp_year']; 
            $card_number=$_POST['card_number'];
            $card_type='';
            $card_name=$_POST['card_name'];


/*
            $this->load->model("room_auto_model");
            $Roomnumber =   $this->room_auto_model->Assign_room(0,$id,$data['hotel_id'] );
            
            $indata['RoomNumber']=$Roomnumber;

            update_data('manage_reservation',$indata,array('hotel_id'=>$_POST['hotelid'],'reservation_id'=>$id));
*/

            $extrasmontos = explode(",", $_POST['extrasmontos']);
            $extrasid =explode(",", $_POST['extrasid']);
            $totalextras=0;

            if((count($extrasid) > 0 && count($extrasmontos) > 0) && (count($extrasid) ==count($extrasmontos) ))
            {
                $contar = count($extrasid) ;

                for ($i=0; $i < $contar ; $i++) { 

                    if ($extrasid[$i]!="")
                    {
                          $descrip=get_data('room_extras',array('room_id'=>$room_id,'extra_id'=>$extrasid[$i]))->row()->name; 

                        $dataextra=array('reservation_id'=>$id ,'channel_id'=>0,'description'=>$descrip,'amount'=>$extrasmontos[$i],'extra_date'=>date('Y-m-d'),);
                        $totalextras +=$extrasmontos[$i];
                        insert_data('extras',$dataextra);    
                    }
                             
                }

            }

            $totalextras +=($price*$rooms);

            $roomMappingDetails =   get_data(MAP,array('property_id' => $room_id,'owner_id'=>$user_id,'hotel_id'=>$_POST['hotelid']))->result_array();



            $arrival = date('Y-m-d',strtotime($checkin_date));
            $departure = date('Y-m-d',strtotime($checkout_date."-1 days"));





            if(count($roomMappingDetails)!=0)
            {    

                require_once(APPPATH.'controllers/arrivalreservations.php');
                $callAvailabilities = new arrivalreservations();
                
                $callAvailabilities->updateavailability(0,$room_id, $rate_type_id,$_POST['hotelid'],$arrival,$departure ,'new');              

            }

                if($exp_month!='' && $exp_year!='' && $card_number!='' && $card_name!='')
                {
                    $card=array(
                        'exp_month'=>(string)safe_b64encode($exp_month),
                        'name'=>(string)safe_b64encode($card_name),
                        'card_type' => (string)safe_b64encode($card_type),
                        'securitycode' => (string)safe_b64encode($_POST['security_code']),
                        'exp_year'=>(string)safe_b64encode($exp_year),
                        'number'=>(string)safe_b64encode($card_number),
                        'user_id'=>$user_id,
                        'resrv_id'=>$id,
                    );
                    $this->db->insert('card_details',$card);
                }



            $save_note = array('type'=>'3','created_date'=>date('Y-m-d H:i:s'),'status'=>'unseen','reservation_id'=>$id,'user_id'=>$user_id,'hotel_id'=>$_POST['hotelid']);

            $ver = $this->db->insert('notifications',$save_note);

            


            $property_details = get_data(TBL_USERS,array('user_id'=>get_data('manage_reservation',array('reservation_id'=>$id))->row()->user_id))->row();

            $cancel_details = get_data(PCANCEL,array('user_id'=>$user_id,'hotel_id'=>$_POST['hotelid']))->row();

            $other_details = get_data(POTHERS,array('user_id'=>$user_id,'hotel_id'=>$_POST['hotelid']))->row();

            $propertyname = get_data(HOTEL,array('owner_id' => $user_id,'hotel_id'=>$_POST['hotelid']))->row()->property_name;

            if($other_details->smoking==1)
            {
                $smoke = 'Smoking is allowed';
            }
            else if($other_details->smoking==0)
            {
                $smoke = 'Smoking is not allowed.';
            }

            if($other_details->pets==1)
            {
                $pets = 'Pets are allowed';
            }
            else if($other_details->pets==0)
            {
                $pets = 'No pets allowed';
            }

            if($other_details->valet_parking==1)
            {
                $valet_parking = 'Valet parking is allowed';
            }
            else if($other_details->valet_parking==0)
            {
                $valet_parking = 'Valet parking is not allowed.';
            }

            if($other_details->child_pricing==1)
            {
                $child_pricing = 'Pets child pricing allowed';
            }
            else if($other_details->child_pricing==0)
            {
                $child_pricing = 'No child pricing allowed';
            }

            $admin_detail = get_data(TBL_SITE,array('id'=>1))->row();


            $get_email_info     =   get_mail_template('12');

            $subject            =   $get_email_info['subject'];

            $template           =   $get_email_info['message'];


            $get_email_info1    =   get_mail_template('11');

            $subject1           =   $get_email_info1['subject'];

            $template1          =   $get_email_info1['message'];


            if($Payment_Type=='bt')
            {

                $get_bank_Details = get_data('manage_reservation',array('reservation_id'=>$id))->row();
            
                $Reference_code = $get_bank_Details->reference_code;

                $bank_details = json_decode($get_bank_Details->bank_details);

                $account_owner = $bank_details->account_owner;
                $bank_name = $bank_details->bank_name;
                $branch_name = $bank_details->branch_name;
                $account_number = $bank_details->account_number;
                $iban = $bank_details->iban;


                    $tbl_data1 = '<div class="row">
                <div class="co-md-12 col-sm-12">
                <table class="summaryTable">

                <tbody>

                <tr>

                <th>

                Name

                </th>

                <td>

                <b>'.ucfirst($first_name).'</b>

                </td>

                </tr>
                <tr>

                <th>

               Reference Code

                </th>

                <td>

                <b>'.$Reference_code.'</b>

                </td>

                </tr>
                <tr>

                <th>

                Account Owner

                </th>

                <td>

                <b>'.ucfirst($account_owner).'</b>

                </td>

                </tr>

                <tr>

                <tr>
                <th>
               Bank Name
                </th>
                <td>
                '.$bank_name.'
                </td>
                </tr>

                <th>

                Branch Name

                </th>

                <td>

                '.$branch_name.'

                </td>

                </tr>

                <tr>

                <th>

                Account Nnumber

                </th>

                <td>

                '.$account_number.'

                </td>

                </tr>

                 <tr>

                <th>

                IFSC Code

                </th>

                <td>

                '.$iban.'

                </td>

                </tr>

                </tbody>

                </table>




                </div>

                </div>';
            }
            else
            {
                $tbl_data1 = '<div class="row">
                <div class="co-md-12 col-sm-12">
                <table class="summaryTable">

                <tbody>

                <tr>

                <th>

                Hotel Name

                </th>

                <td>

                <b>'.ucfirst($property_details->property_name).'</b>

                </td>

                </tr>
                <tr>

                <th>

                Confirmation number

                </th>

                <td>

                <b>'.$reservation_code.'</b>

                </td>

                </tr>
                <tr>

                <th>

                Guest Name

                </th>

                <td>

                <b>'.ucfirst($first_name).'</b>

                </td>

                </tr>

                <tr>

                <tr>
                <th>
                No.of Rooms
                </th>
                <td>
                '.$rooms.'
                </td>
                </tr>

                <th>

                Check-in date

                </th>

                <td>

                '.$start_date.'

                </td>

                </tr>

                <tr>

                <th>

                Check-out date

                </th>

                <td>

                '.$end_date.'

                </td>

                </tr>



                <tr>

                <th>

                No.of Nights

                </th>

                <td>

                '.$nights.'

                </td>

                </tr>

                <tr>
                <th>
                Arrival Time
                </th>
                <td>
                '.$arrivaltime.'
                </td>
                </tr>

                <tr>

                <th>

                Order Total

                </th>

                <td>

                '.$totalextras.'

                </td>

                </tr>

                <tr>

                <th>

                Adult Count

                </th>

                <td>

                '.$adult.'

                </td>

                </tr>

                <tr>

                <th>

                Child Count

                </th>

                <td>

                '.$child.'

                </td>

                </tr>

                </tbody>

                </table>

                <h3>Hotel Policies</h3>

                <table class="summaryTable">

                <tbody>

                <tr>

                <th>Cancellation</th>

                <td>

                '.$cancel_details->description.'


                </td>

                </tr>

                <tr>

                <th>Check-in time</th>

                <td>After '.$other_details->check_in_time.' day of arrival.</td>

                </tr>

                <tr>

                <th>Check-out time</th>

                <td>'.$other_details->check_out_time.' upon day of departure.</td>

                </tr>

                <tr>

                <th>Valet parking</th>

                <td>'.$valet_parking.'.</td>

                </tr>

                <tr>

                <th>Smoking</th>

                <td>'.$smoke.'.</td>

                </tr>

                <tr>

                <th>Pets</th>

                <td>'.$pets.'</td>

                </tr>

                <tr>

                <th>Child pricing</th>

                <td>'.$child_pricing.'</td>

                </tr>';

                $new_policy_details = get_data(PADD,array('user_id'=>$user_id,'hotel_id'=>$_POST['hotelid']))->result();

                if($new_policy_details!='')
                {
                    foreach($new_policy_details as $new_policy)
                    {
                        $tbl_data1.=  '<tr>

                        <th>'.$new_policy->policy_name.'</th>

                        <td>'.$new_policy->description.'</td>

                        </tr>';

                    }
                }

               $tbl_data1 .= '</tbody>

                </table>
                </div>

                </div>';

             

                $tbl_data = '<div class="row">

                <div class="co-md-12 col-sm-12">
                <table class="summaryTable">

                <tbody>

                <tr>

                <th>

                Hotel Name

                </th>

                <td>

                <b>'.ucfirst($propertyname).'</b>

                </td>

                </tr>
                <tr>

                <th>

                Confirmation number

                </th>

                <td>

                <b>'.$reservation_code.'</b>

                </td>

                </tr>

                <tr>
                <th>
                No.of Rooms
                </th>
                <td>
                '.$rooms.'
                </td>
                </tr>

                <tr>

                <th>

                Guest Name

                </th>

                <td>

                <b>'.ucfirst($first_name).'</b>

                </td>

                </tr>

                <tr>

                <th>



                Check-in date

                </th>

                <td>

                '.$start_date.'

                </td>

                </tr>

                <tr>

                <th>

                Check-out date

                </th>

                <td>

                '.$end_date.'

                </td>

                </tr>



                <tr>

                <th>

                No.of Nights

                </th>

                <td>

                '.$nights.'

                </td>

                </tr>

                <tr>

                <th>

                Order Total

                </th>

                <td>

                '.$price*$rooms.'

                </td>

                </tr>

                <tr>

                <th>

                Adult Count

                </th>

                <td>

                '.$adult.'

                </td>

                </tr>
                <tr>

                <th>

                Child Count

                </th>

                <td>

                '.$child.'

                </td>

                </tr>


                </tbody>

                </table>

                </div>

                </div>';
            }
echo $tbl_data1;


            if($Payment_Type!='bt')
            {

                $data = array(

                            '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,

                            '###SITENAME###'=>$admin_detail->company_name,

                            '###CONFIRMRESERVATION###'=>$tbl_data,

                            '###SITELINK###'=>base_url(),

                            '###RESERLINK###'=>base_url().'reservation/reservation_print/'.secure(0).'/'.insep_encode($id),

                            '###CONFIRMLINK###'=>base_url().'reservation/admin_confirm/'.insep_encode($id),

                            );

            }

                $data1 = array(

                '###USERNAME###'=>$first_name,

                '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,

                '###SITENAME###'=>$admin_detail->company_name,

                '###STATUS###'=>'Reserved',

                '###PROPERTYUSER###'=>$propertyname,

                '###CONFIRMRESERVATION###'=>$tbl_data1,

                '###SITELINK###'=>base_url(),

                '###RESERLINK###'=>base_url().'reservation/reservation_print/'.secure(0).'/'.insep_encode($id),

                );

                $subject_data = array(
                    '###PROPERTYUSER###'=>$propertyname,
                    '{RESERVATIONCODE}'=>$reservation_code,);

                $subject_data1 = array(
                    '###SITENAME###'=>$admin_detail->company_name,
                    '{RESERVATIONCODE}'=>$reservation_code,
                );

                $subject_new1 = strtr($subject1,$subject_data1);

                $content_pop1 = strtr($template1,$data1);

                $subject_new = strtr($subject,$subject_data);


                $content_pop = strtr($template,$data);
 
               
                $this->mailsettings();

                if($guestmail!='')
                {
                    $this->email->from($admin_detail->email_id);

                    $this->email->to($email);

                    $this->email->subject($subject_new1);

                    $this->email->message($content_pop1);

                    $this->email->send();
                }



                $this->email->from($admin_detail->email_id);

                

                $this->email->to(get_data(HOTEL,array('hotel_id'=>$_POST['hotelid']))->row()->email_address);

                $this->email->subject($subject_new);

                $this->email->message($content_pop);

                $this->email->send();
                return $id;
        }
        else
        {
         return false;
        }      

    }
}
?>