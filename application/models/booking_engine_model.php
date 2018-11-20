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
    function findRoomsAvailable()
    {




        $start_date     =  date('Y-m-d',strtotime($_POST['dp1']));

        $end_date       =  date('Y-m-d',strtotime($_POST['dp2']."-1 days"));

        $rooms          =  $_POST['num_rooms'];

        $adult          =   $_POST['num_person'];

        $child          =   $_POST['num_child'];

        $start          =   strtotime($start_date);

        $end            =   strtotime($_POST['dp2']);

        $nights         =   ceil(abs($end - $start) / 86400);

        $hotel_id=insep_decode($_POST['hotel_id']);


        $result=$this->db->query("SELECT P.description , U.room_update_id, U.room_id , U.separate_date ,
                U.minimum_stay , sum( ifnull(U.price,0)  ) totalprice,
               sum( ifnull(U.price,0)  )/(count(*)) avgprice, P.price as base_price , P.image , P.property_name ,  P.member_count , P.children , P.number_of_bedrooms,P.existing_room_count,
                count(*) available, min(U.availability) roomAvailability
                FROM room_update U
                LEFT JOIN manage_property P ON U.room_id = P.property_id
                WHERE str_to_date(U.separate_date,'%d/%m/%Y') between '$start_date' and '$end_date'
                AND U.availability >=$rooms
                AND U.minimum_stay <= $nights AND P.member_count >=$adult
                AND P.children >=$child AND individual_channel_id =0 and ifnull(U.price,0)>0
                AND stop_sell='0' AND P.hotel_id=".$hotel_id."
								AND P.showwidget=1
                GROUP BY U.room_id ORDER BY P.`order` ")->result_array();



        $available= '';

        if (count($result)>=0 ) {
            $i=0;
            foreach ($result as $value) {

                if ( $value['available']>=$nights && $value['totalprice']>0) {
                    $available[$i]=$value ;

                     $rate=$this->db->query("SELECT P.description , U.room_update_id, U.room_id , U.separate_date ,
                    U.minimum_stay , sum( ifnull(U.price,0)  ) totalprice,
                   sum( ifnull(U.price,0)  )/(count(*)) avgprice, P.price as base_price , P.image , P.property_name ,  P.member_count , P.children , P.number_of_bedrooms,P.existing_room_count,
                    count(*) available, min(U.availability) roomAvailability, r.name,U.rate_types_id
                    FROM room_rate_types_base U
                    LEFT JOIN manage_property P ON U.room_id = P.property_id
                    LEFT JOIN ratetype r ON U.rate_types_id = r.ratetypeid
                    WHERE str_to_date(U.separate_date,'%d/%m/%Y') between '$start_date' and '$end_date'
                    AND U.availability >=$rooms
                    and U.room_id = ".$value['room_id']."
                    AND U.minimum_stay <= $nights AND P.member_count >=$adult
                    AND P.children >=$child AND individual_channel_id =0 and ifnull(U.price,0)>0
                    AND stop_sell='0' AND P.hotel_id=".$hotel_id."
                    GROUP BY U.rate_types_id ORDER BY U.rate_types_id DESC")->result_array();

                     if (count($rate)>=0 ) {
                        $y=0;
                        foreach ($rate as $valu) {

                            if ( $valu['available']>=$nights && $valu['totalprice']>0) {
                                $available[$i]['rate'][$y]=$valu ;
                                $y++;
                            }
                        }
                    }
                    $i++;
                }
            }
        }

        return $available;

    }
    function saveReservation()
    {

        $nights  =  ceil(abs(strtotime($_POST['checkout'] )- strtotime($_POST['checkin'] )) / 86400);

        $checkout_date= date('Y-m-d',strtotime($_POST['checkout']."-1 days"));

       $hotelid=$_POST['hotelid'];

        if($_POST['rateid']==0)
        {
             $result=$this->db->query("SELECT U.price,str_to_date(U.separate_date,'%d/%m/%Y') datecurrent
                FROM room_update U
                LEFT JOIN manage_property P ON U.room_id = P.property_id
                WHERE str_to_date(U.separate_date,'%d/%m/%Y') between '".$_POST['checkin']."' and '".$checkout_date."'
                AND U.availability >=".$_POST['numroom']."
                AND U.minimum_stay <= $nights AND P.member_count >=".$_POST['adult']."
                AND P.children >=".$_POST['child']." AND individual_channel_id =0
                AND stop_sell=0 AND P.hotel_id=".$hotelid." and U.room_id=".$_POST['roomid']."
                ORDER BY str_to_date(U.separate_date,'%d/%m/%Y') ASC")->result_array();
        }
        else
        {
              $result=$this->db->query("SELECT U.price,str_to_date(U.separate_date,'%d/%m/%Y') datecurrent
                FROM room_rate_types_base U
                LEFT JOIN manage_property P ON U.room_id = P.property_id
                WHERE str_to_date(U.separate_date,'%d/%m/%Y') between '".$_POST['checkin']."' and '".$checkout_date."'
                AND U.availability >=".$_POST['numroom']."
                AND U.minimum_stay <= $nights AND P.member_count >=".$_POST['adult']."
                AND P.children >=".$_POST['child']." AND individual_channel_id =0
                AND stop_sell=0 AND P.hotel_id=".$hotelid." and U.room_id=".$_POST['roomid']."
                and U.rate_types_id =".$_POST['rateid']."
                ORDER BY str_to_date(U.separate_date,'%d/%m/%Y') ASC")->result_array();
        }

        if(count($result)>0)
        {
            if (count($result)>=$_POST['numroom']) {

                $currencycodes=0;
               $currencycodes = get_data(HOTEL,array('hotel_id'=>$hotelid))->row()->currency;
               $prices=0;
               $pricesdetails='';

               foreach ($result as $value) {

                   $prices += $value['price'];

                   $pricesdetails .= (strlen($pricesdetails)>0?',':'').$value['price'];
               }

                if  ($currencycodes==0)   {
                   $currencycodes   = 1;
                }

               $code = $this->db->query("SELECT max(reservation_code) code FROM manage_reservation where hotel_id=".$hotelid."")->row_array();

                if(isset($code['code'])){$reservation_code   =   sprintf('%08d',$code['code']+1);}else{$reservation_code = sprintf('%08d',1);}


                $data['hotel_id']=$hotelid;
                $data['user_id']=0;
                $data['reservation_code']=$reservation_code;
                $data['guest_name']=$_POST['firstname'];
                $data['last_name']=$_POST['lastname'];
                $data['mobile']=$_POST['phone'];
                $data['country']=$_POST['countryid'];
                $data['province']=$_POST['state'];
                $data['street_name']=$_POST['address'];
                $data['city_name']=$_POST['city'];
                $data['zipcode']=$_POST['zipcode'];
                $data['email']=$_POST['email'];
                $data['description']=$_POST['note'];
                $data['room_id']=$_POST['roomid'];
                $data['rate_types_id']=$_POST['rateid'];
                $data['num_rooms']=1;
                $data['num_nights']=$nights;
                $data['members_count']=$_POST['adult'];
                $data['children']=$_POST['child'];
                $data['start_date']=date('d/m/Y',strtotime($_POST['checkin']));
                $data['end_date']=date('d/m/Y',strtotime($_POST['checkout']));
                $data['booking_date']=date('Y-m-d');
                $data['channel_id']=0;
                $data['arrivaltime']=$_POST['arrival'];
                $data['price']=$prices;
                $data['price_details']=$pricesdetails;
                $data['currency_id']=$currencycodes;
                $data['PaymentMethodId']=$_POST['paymentTypeId'];
                $data['ProviderId']=$_POST['providerid'];
                $data['CurrencyCode']=$_POST['currency'];
                $data['guestname']=(isset($_POST['guestname'])?implode(',', $_POST['guestname']):'');
                $data['sourceid']=$_POST['sourceid'];

                if ($data['PaymentMethodId']>1) {

                    require_once(APPPATH.'controllers/tokenex.php');
                    $tokenex = new tokenex();
                    $data['ccholder']=safe_b64encode($_POST['ccholder']);
                    $data['ccnumber']=safe_b64encode($tokenex->Tokenizar($_POST['ccnumber']));
                    $data['cccvv']=safe_b64encode($_POST['cccvv']);
                    $data['ccmonth']=safe_b64encode($_POST['ccmonth']);
                    $data['ccyear']=safe_b64encode($_POST['ccyear']);

                }

                $taxes=$this->db->query("select * from taxcategories where hotelid=".$data['hotel_id'])->result_array();
                $taxinfo="";
                foreach ($taxes as $tax) {
                    $taxinfo.=(strlen($taxinfo)>0?',':'').$tax['taxid']."*".$tax['taxrate']."*".$tax['includedprice'];
                }
                $data['taxes']=$taxinfo;

                if(insert_data('manage_reservation',$data))
                {
                    $id =  getinsert_id();

                    $history = array('channel_id'=>0,'Userid'=> $data['user_id'],'reservation_id'=>$id,'description'=>'Reservation Created by guest '.$data['guest_name'].' '.$data['last_name'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>$prices,'extra_id'=>0);
                    insert_data('new_history',$history);

                    $this->load->model("room_auto_model");

                    $indata['RoomNumber'] =  $this->room_auto_model->Assign_room($data['hotel_id'],$data['room_id'],$_POST['checkin'],$_POST['checkout'] );

                   if (strlen($indata['RoomNumber'])>0) {
                        $roomnumberdata['hotelid']=$data['hotel_id'];
                        $roomnumberdata['roomid']=$data['room_id'];
                        $roomnumberdata['checkin']=$_POST['checkin'];
                        $roomnumberdata['checkout']=$_POST['checkout'];
                        $roomnumberdata['roomnumber']=$indata['RoomNumber'];
                        $roomnumberdata['reservationid']=$id;
                        $roomnumberdata['channelid']=0;
                        $roomnumberdata['active']=1;
                        insert_data('roomnumberused',$roomnumberdata);
                        update_data('manage_reservation',$indata,array('hotel_id'=> $data['hotel_id'],'reservation_id' => $id));
                    }
                    $response['success']=true;
                    $response['reservationid']=$id;
                    $response['url']=site_url('reservation/reservationdetails/'.secure(0).'/'.insep_encode($id));
                    return $response;

                }

            }
            else
            {
                $response['success']=false;
                $response['availability']=0;
                return false;
            }
        }
        else
        {   $response['success']=false;
            $response['availability']=0;
            return false;
        }
    }
	function get_reserve()
    {


		   $rooms          =  $_POST['num_rooms'];
            $adult          =   $_POST['num_person'];
            $start_date     = date('d-m-Y', strtotime( $_POST['dp1'])); ;

            $end_date       =   date('d-m-Y', strtotime( $_POST['dp2'])); ;
            $nights         =   ceil(abs(strtotime( $_POST['dp2']) - strtotime( $_POST['dp1'])) / 86400);

            $available      =   $this->findRoomsAvailable();

            $html='';
            $hotel_id=insep_decode($_POST['hotel_id']);

            $inidate=date('Y-m-d', strtotime( $_POST['dp1']));
            $enddate=date('Y-m-d', strtotime( $_POST['dp2']));

            $currency=$this->db->query("SELECT ifnull(b.symbol,'$') symbol FROM manage_hotel a
                left join currency b on a.currency =b.currency_id
                where a.hotel_id=".$hotel_id)->row()->symbol;

            if($available)
            {
                foreach ($available as $key => $value) {
                $roomimage=$this->db->query("select photo_names from room_photos where room_id=".$value['room_id'])->result_array();


                $bookininfo="'".$value['room_id']."','0','".$inidate."','".$enddate."','".$adult ."','".$rooms.
                "','".$_POST['num_child']."','".$nights."','".number_format ( $value['totalprice'] , 2 ,  "." , "" )."'";
                $html .= '<div>
                <div class="row">
                    <div class="col-md-3">
                        <div>
                             <div class="fotorama">';

                             if(count($roomimage)>0)
                             {
                                 foreach ($roomimage as $key => $image) {
                                    $html .= ' <a href="javascript:;"><img src="'.base_url().$image['photo_names'].'" class="img-responsive" alt=""></a>';
                                }
                             }
                            else
                            {
                              $html .= ' <a href="javascript:;"><img src="'.base_url().('uploads/room_photos/noimage.jpg').'" class="img-responsive" alt=""></a>';
                               $html .= ' <a href="javascript:;"><img src="'.base_url().('uploads/room_photos/noimage.jpg').'" class="img-responsive" alt=""></a>';
                            }

                              $html .='</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h3 style="text-align: center;"><span class="label label-primary">'.$value['property_name'].'</span></h3>
                        <p>'.$this->lang->line('maximumadults').':'.$value['member_count'].'</p>
                         <p>'.$this->lang->line('maximumchildren').':'.$value['children'].'</p>
                        <p>'.$this->lang->line('available').':'.$value['roomAvailability'].'</p>
                        <button type="button" onclick="showdetails('."'room".$value['room_id']."'".')" class="btn btn-xs btn-info">'.$this->lang->line('roomdetails').'</button>
                    </div>

                    <div class="col-md-5" style="text-align: right;">
                        <div class="col-md-12" >
                            <label>'.$this->lang->line('avgpernight').'</label>

                            <h3 id="price_'.$value['room_id'].'r0">'.$currency.number_format ( $value['avgprice'] , 2 ,  "." , "," ).'</h3>
                             <button onclick="reservethis('.$bookininfo.",'".$value['room_id']."r0'".')" type="button"  class="btn btn-xs btn-info">'.$this->lang->line('bookthisroom').'</button>
                         </div>';
                         if (isset($value['rate'])) {

                             foreach ($value['rate'] as  $rate) {
                                $bookininfo="'".$value['room_id']."','".$rate['rate_types_id']."','".$inidate."','".$enddate."','".$_POST['num_person']."','".$_POST['num_rooms'].
                                "','".$_POST['num_child']."','".$nights."','".number_format ( $rate['totalprice'] , 2 ,  "." , "" )."'";
                                $html .='<div class="col-md-12">
                                        <h3>'.$rate['name'].'</h3>
                                        <label>'.$this->lang->line('avgpernight').'</label>

                                        <h3  id="price_'.$value['room_id'].'r'.$rate['rate_types_id'].'">'.$currency.number_format ( $rate['avgprice'] , 2 ,  "." , "," ).'</h3>
                                         <button onclick="reservethis('.$bookininfo.",'".$value['room_id']."r".$rate['rate_types_id']."'".')" type="button"  class="btn btn-xs btn-warning">'.$this->lang->line('bookthisroom').'</button>
                                     </div>';
                             }

                        }


               $html .= '</div>
                    <div class="clearfix"></div>
                </div>


                  <div class="clearfix"></div>
                 <div id="room'.$value['room_id'].'" class="row" style="display: none;">
                    <hr style="color: #148dec;" align="center" noshade="noshade" size="14" width="80%" />
                    <div class="col-md-6" style="margin-left:5px; float: left;">
                        <h4 style="color:#148dec;">'.$this->lang->line('description').'</h4>
                        <p style="text-align:justify">'.$value['description'].'</p>
                    </div>
                    <div class="col-md-4" style="text-align: left; float: right;">
                        <div>
                            <label ><strong>'.$this->lang->line('checkin').':</strong>'.$start_date.'&nbsp;</label>
                        </div>
                        <div>
                            <label><strong>'.$this->lang->line('checkout').':</strong>:'.$end_date.'</label>
                        </div>
                        <div>
                            <label><strong>'.$this->lang->line('rooms').':</strong>'.$rooms .'</label>
                        </div>
                        <div>
                            <label><strong>'.$this->lang->line('guest').':</strong>'.$adult.'</label>
                        </div>
                        <div>
                            <label><strong>'.$this->lang->line('nights').':</strong>'.$nights.'</label>
                        </div>
                        <div>
                            <label><strong>'.$this->lang->line('total').':</strong>'.number_format ( $value['totalprice'] , 2 ,  "." , "," ).'</label>
                        </div>

                    </div>

                </div>
                <div class="bor-dash mar-bot20"></div>
                </div>
                 <div class="clearfix"></div>
                 ';


                }
            }
            else
            {
                $html .= '<div class="room_info">
                        <div class="row" style="padding:30px; text-align:center;"><h1><span class="label label-danger">'.$this->lang->line('noroom').'..</span></h1></div></div>';

            }

        $data['detail']=$html."<script>
                    $('.change_price span').on('click', function(e) {
                        $(this).next('.inr_cont').slideToggle();
                    });
                    $('.change_amount').click(function(e){
                        var id=this.id;
                        var replace=id.replace('b_','');
                        $('#price_'+replace).html('".$currency."'+parseFloat($('#new_'+replace).val()));

                        $('.inr_cont').hide();
                    });

                    $('.close_amount').click(function(){

                        $('.inr_cont').hide();
                    });
                </script>
                 " ;
        $data['header']="Date Range: $start_date To $end_date";

        return ($data);

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
