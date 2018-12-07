 <?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class reservation extends Front_Controller {

	private $currency_code;

	public function __construct()
	{
		parent::__construct();

		//load base libraries, helpers and models
		$this->session->keep_flashdata('error');
		$this->session->keep_flashdata('success');
		// $this->load->library( 'paypal_lib' );
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

			if ($hotel_detail  !=0)
			{

				$this->currency_code	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->symbol;
			}

		}

	}
	function is_login()
	{
		if(!user_id())
		redirect(base_url());
		return;
	}

	function testt()
	{

		$output=' <result timestamp="1513099354273">
      <bookings count="1" iataid="96637800">
      <booking property_id="567792" property_name="PCLN Hotel Test - Do Not Book" booking_id="242889924" booking_date="2017-11-10T18:19:00+07:00" last_action="2017-11-10T18:20:03+07:00" arrival="2017-11-19" departure="2017-11-20" status="CancelBooking" acknowledge="0" room_id="15314062" room_type="Classic Family" channel_id="1" channel_name="Retail" rateplan_id="1055064" rateplan_name="EU Test - unmapped" promotion_id="146400602" promotion_name="Limited time offer. Rate includes 50% discount!" room_count="1" adults="2" children="0" extrabeds="0" cxl_code="1D1N_1N">

            <customer first_name="JOe" last_name="JOe Joe" nationality="Australia"/>
            <benefits>
                  <benefit benefit_id="74" benefit_name="Spa discount"/>
                </benefits>


            <prices currency="EUR" sell_inclusive_amt="28.496934">
              <price date="" sell_inclusive_amt="28.496934" type="Cancellation"/>
            </prices>
            <paymentmodel>2</paymentmodel>
          </booking>
		    </bookings>
		    </result>';
				  $data_bookingapi= simplexml_load_string($output);
				 print_r( $data_bookingapi);
				 die;
				 $Hotelbook=$data_bookingapi->bookings->booking;
				 $data['user_id']=13;
										$data['hotel_id']=13;
								$data['hotelcode'] =(string)$Hotelbook['property_id'];
								$data['property_name'] =(string)$Hotelbook['property_name'];
								$data['booking_id'] =(string)$Hotelbook['booking_id'];
								$data['booking_date'] =(string)$Hotelbook['booking_date'];
								$data['last_action'] =(string)$Hotelbook['last_action'];
								$data['arrival'] =(string)$Hotelbook['arrival'];
								$data['departure'] =(string)$Hotelbook['departure'];
								$data['status'] =(string)$Hotelbook['status'];
								$data['acknowledge'] =(string)$Hotelbook['acknowledge'];
								$data['room_id'] =(string)$Hotelbook['room_id'];
								$data['room_type'] =(string)$Hotelbook['room_type'];
								$data['channel_code'] =(string)$Hotelbook['channel_id'];
								$data['channel_name'] =(string)$Hotelbook['channel_name'];
								$data['rateplan_id'] =(string)$Hotelbook['rateplan_id'];
								$data['rateplan_name'] =(string)$Hotelbook['rateplan_name'];
								$data['promotion_id'] =(string)$Hotelbook['promotion_id'];
								$data['promotion_name'] =(string)$Hotelbook['promotion_name'];
								$data['room_count'] =(string)$Hotelbook['room_count'];
								$data['adults'] =(string)$Hotelbook['adults'];
								$data['children'] =(string)$Hotelbook['children'];
								$data['extrabeds'] =(string)$Hotelbook['extrabeds'];
								$data['cxl_code'] =(string)$Hotelbook['cxl_code'];


								$customer = $Hotelbook->customer;
								$data['firstname'] = (string)$customer['first_name'];
								$data['lastname'] = (string)$customer['last_name'];
								$data['nationality'] = (string)$customer['nationality'];
								$data['email'] = (string)$customer['email'];
								$data['phone'] = (string)$customer['phone'];



								$requests=$Hotelbook->requests->request;
								$request='';
								if(count($requests)>0)
								{
									foreach ($requests as  $value) {
										$request .=$value['request_name']."\n";
									}
								}

								$data['requests']=$request;


								$cardDetails = $Hotelbook->payment;
								$data['card_type']=(string)(string)@$cardDetails['card_type'];
								$data['card_number']=(string)@$cardDetails['card_number'];
								$data['card_name']=(string)@$cardDetails['card_name'];
								$data['card_exp']=(string)@$cardDetails['card_exp'];
								$data['card_cvv']=(string)@$cardDetails['card_cvv'];


								$prices = $Hotelbook->prices;

								$data['currency'] = (string)$prices['currency'];
								$data['net_inclusive_amt'] = (string)$prices['net_inclusive_amt'];
								$data['refsell_amt'] = (string)$prices['refsell_amt'];

								$price=$prices->price;
								$pricedate='';
								$pricemoney='';
								if(count($price)>0)
								{
									foreach ($price as  $value) {
										$pricedate .=(string)$value['date'].",";
										$pricemoney .=(string)$value['net_inclusive_amt'].",";

									}
								}
								$data['pricedate']=$pricedate;
								$data['pricemoney']=$pricemoney;
			print_r($data);
			echo $pricemoney;
								$available = get_data('import_reservation_AGODA',array('hotel_id'=>13,'booking_id'=>$data['booking_id'],'hotelcode'=>$data['hotelcode']))->row_array();
								if(count($available)==0)
								{	$id =$this->db->insert_id();
									$array_keys = array_keys($data);
									fetchColumn('import_reservation_AGODA',$array_keys);
									insert_data('import_reservation_AGODA',$data);

									$benefits = $Hotelbook->benefits->benefit;
									if(count($benefits)>0)
									{

										foreach ($benefits as  $value)
										{
											 $dataextra=array('reservation_id'=> $id,'channel_id'=>19,'description'=>$value['benefit_name'],'amount'=>0,'extra_date'=>date('Y-m-d'),);
						                        insert_data('extras',$dataextra);
										}
									}

								}
								else
								{
									$array_keys = array_keys($data);
									fetchColumn('import_reservation_AGODA',$array_keys);
									update_data('import_reservation_AGODA',$data,array('hotel_id'=>13,'booking_id'=>$data['booking_id'],'hotelcode'=>$data['hotelcode']));
									$id=$available['import_reserv_id'];


									$benefits = $Hotelbook->benefits->benefit;
									if(count($benefits)>0)
									{

										foreach ($benefits as  $value)
										{	$available = get_data('extras',array('channel_id'=>19,'description'=>(string)$value['benefit_name']))->row_array();

											if(count($available)==0)
											{
													 $dataextra=array('reservation_id'=> $id ,'channel_id'=>19,'description'=>(string)$value['benefit_name'],'amount'=>0,'extra_date'=>date('Y-m-d'),);
						                        	insert_data('extras',$dataextra);

											}

										}
									}

								}



	}
	function is_admin()
	{
		if(!admin_id())\
		redirect(base_url());
		return;
	}

	function findRoomsAvailable()
	{


			$rooms          =  $_POST['numrooms'];
			$adult          =   $_POST['numadult'];
			$start_date     = date('d-m-Y', strtotime( $_POST['date1Edit'])); ;

        	$end_date       =   date('d-m-Y', strtotime( $_POST['date2Edit'])); ;
        	$nights         =   ceil(abs(strtotime( $_POST['date2Edit']) - strtotime( $_POST['date1Edit'])) / 86400);

			$available		=	$this->reservation_model->findRoomsAvailable();


			$html='';

			$inidate=date('Y-m-d', strtotime( $_POST['date1Edit']));
			$enddate=date('Y-m-d', strtotime( $_POST['date2Edit']));
			if($available)
			{
				foreach ($available as $key => $value) {
				$roomimage=$this->db->query("select photo_names from room_photos where room_id=".$value['room_id'])->result_array();


				$bookininfo="'".$value['room_id']."','0','".$inidate."','".$enddate."','".$_POST['numadult']."','".$_POST['numrooms'].
				"','".$_POST['numchild']."','".$nights."','".$value['totalprice']."'";
				$html .= '<div>
				<div class="row">
                    <div class="col-md-3">
                        <div>
                            <a href="javascript:;"><img src="'.base_url().(count($roomimage)>0?$roomimage[0]['photo_names']:'uploads/room_photos/noimage.jpg').'" class="img-responsive" alt=""></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h3 style="text-align: center;"><span class="label label-primary">'.$value['property_name'].'</span></h3>
                        <p>Maximun Adult:'.$value['member_count'].'</p>
                         <p>Maximun Child:'.$value['children'].'</p>
                        <p>Rooms Available:'.$value['roomAvailability'].'</p>
                        <button type="button" onclick="showdetails('."'room".$value['room_id']."'".')" class="btn btn-xs btn-info">Room Details</button>
                    </div>

                    <div class="col-md-4" style="text-align: right;">
                    	<div class="col-md-12">
                    		<label>Avg. per night</label>
                    		<h2 class="change_price">
                                            <span>'.$this->lang->line('changeprice').'?</span>
                                            <div class="inr_cont" style="display: none; '.(isset($value['rate'])?'position:relative;':'').'">
                                            <center><p style="font-size:14px; ">'.$this->lang->line('changeprice').'</p></center>
                                            <input id="new_'.$value['room_id'].'r0" class="form-control" style="height:50px; color:black; font-size:16px;" step="10" min="20" max="'.$value['avgprice'].'" value="'.$value['avgprice'].'" required="" type="number">
                                            <a id="b_'.$value['room_id'].'r0" class="change_amount"><i class="fa fa-check"></i></a>
                                            <a class="close_amount"><i class="fa fa-remove"></i></i></a>
                                            </div>
                            </h2>


	                        <h3 id="price_'.$value['room_id'].'r0">'.number_format ( $value['avgprice'] , 2 ,  "." , "," ).'</h3>
	                         <button onclick="reservethis('.$bookininfo.",'".$value['room_id']."r0'".')" type="button"  class="btn btn-xs btn-info">Book This Room</button>
                         </div>';
                         if (isset($value['rate'])) {

	                         foreach ($value['rate'] as  $rate) {
	                         	$bookininfo="'".$value['room_id']."','".$rate['rate_types_id']."','".$inidate."','".$enddate."','".$_POST['numadult']."','".$_POST['numrooms'].
								"','".$_POST['numchild']."','".$nights."','".$rate['totalprice']."'";
	                         	$html .='<div class="col-md-12">
	                         			<h3>'.$rate['name'].'</h3>
				                        <label>Avg. per night</label>
				                      <h2 class="change_price">
                                            <span>'.$this->lang->line('changeprice').'?</span>
                                            <div class="inr_cont" style="display: none;">
                                            <center><p style="font-size:14px;">'.$this->lang->line('changeprice').'</p></center>
                                            <input id="new_'.$value['room_id'].'r'.$rate['rate_types_id'].'" class="form-control" style="height:50px; color:black; font-size:16px;"  step="10" min="20" max="'.$value['avgprice'].'" value="'.$value['avgprice'].'" required="" type="number">
                                            <a  id="b_'.$value['room_id'].'r'.$rate['rate_types_id'].'" class="change_amount"><i class="fa fa-check"></i></a>
                                            <a class="close_amount"><i class="fa fa-remove"></i></i></a>
                                            </div>
                                        </h2>
				                        <h3  id="price_'.$value['room_id'].'r'.$rate['rate_types_id'].'">'.number_format ( $rate['avgprice'] , 2 ,  "." , "," ).'</h3>
				                         <button onclick="reservethis('.$bookininfo.",'".$value['room_id']."r".$rate['rate_types_id']."'".')" type="button"  class="btn btn-xs btn-warning">Book This Rate</button>
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
                        <h4 style="color:#148dec;">Descripcion</h4>
                        <p style="text-align:justify">'.$value['description'].'</p>
                    </div>
                    <div class="col-md-4" style="text-align: left; float: right;">
                        <div>
                            <label ><strong>Check-in:</strong>'.$start_date.'&nbsp;</label>
                        </div>
                        <div>
                            <label><strong>Check-out:</strong>:'.$end_date.'</label>
                        </div>
                        <div>
                            <label><strong>Rooms:</strong>'.$rooms .'</label>
                        </div>
                        <div>
                            <label><strong>Guest:</strong>'.$adult.'</label>
                        </div>
                        <div>
                            <label><strong>Nights:</strong>'.$nights.'</label>
                        </div>
                        <div>
                            <label><strong>Total:</strong>'.number_format ( $value['totalprice'] , 2 ,  "." , "," ).'</label>
                        </div>

                    </div>

                </div>
                <hr size="10">
                </div>
                 <div class="clearfix"></div>
                 ';


				}
			}
			else
			{
				$html .= '<div class="room_info">
						<div class="row" style="padding:30px; text-align:center;"> No Rooms are available..</div></div>';

			}

		$data['detail']=$html."<script>
					$('.change_price span').on('click', function(e) {
					    $(this).next('.inr_cont').slideToggle();
					});
					$('.change_amount').click(function(e){
						var id=this.id;
						var replace=id.replace('b_','');
						$('#price_'+replace).html(parseFloat($('#new_'+replace).val()));

						$('.inr_cont').hide();
					});

					$('.close_amount').click(function(){

						$('.inr_cont').hide();
					});
				</script>
                 " ;
		$data['header']="Date Range: $start_date To $end_date";
		$data['taxes']=json_encode( $taxes=$this->db->query("select * from taxcategories where hotelid=".hotel_id())->result_array());

		echo json_encode($data);
	}
	function findroomtypesavailable()
	{

		$nights =0;
		if(date('Y-m-d',strtotime($_POST['date1Edit']))<date('Y-m-d') && date('Y-m-d',strtotime($_POST['date2Edit']))>date('Y-m-d') )
		{
			$_POST['date1Edit']=date('Y-m-d');
	        $nights         =   ceil(abs(strtotime($_POST['date2Edit']) - strtotime($_POST['date1Edit'])) / 86400);

		}

		$result		=	$this->reservation_model->findRoomsAvailable();

		$available ='';
        if (count($result)>=0 ) {
            $i=0;
            foreach ($result as $value) {


            	if($value['room_id']!=$_POST['roomtype'])
            	{


                    if ($value['avgprice']>$_POST['avg']) {
                        $value['upgrade']=1;
                    }
                    else if ($value['avgprice']<$_POST['avg']) {
                        $value['upgrade']=-1;
                    }
                    else if ($value['avgprice']==$_POST['avg']) {
                        $value['upgrade']=0;
                    }
                    $value['chargenight']=$value['avgprice'];
                    $available[$i]=$value;
                    $i++;
                }

            }
        }

        if(is_array($available))
         {
         	$html='';
			$html.='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table  class="table table-bordered">
								<thead>
										<tr>
												<th>Room Type</th>
												<th style="text-align:center;">Category</th>
												<th style="text-align:center;">Change</th>
										</tr>
															 </thead>
								<tbody>';
			$i=0;
			foreach ($available as  $value) {
				$i++;
				$html.=' <tr  class="'.($i%2?'active':'success').'"> <td>'.$value['property_name'].'  </td>
						<td  align="center">'.($value['upgrade']==1?'Upgrade':($value['upgrade']==0?'Same Level':'Downgrade')).' </td>
						<td  align="center"><a  onclick="changeroomtype('."'".$value['room_id']."','$nights','".$value['upgrade']."','".number_format($value['chargenight'], 2, '.', '')."'".');">
						 <i class="fa fa-check-circle fa-2x"></i></a> </td> </tr>';


			}
			$html.='</tbody></table></div> </div>';

			echo json_encode(array('success'=>true,'html'=>$html));
			return;
         }
     echo json_encode(array('success'=>false,'message'=>"There aren't Rooms Available for this date range"));


	}
	function changeroomtype()
	{

		$upgrade=$_POST['upgrade'];
		$channelid=$_POST['channelid'];
		$reservationid=$_POST['resid'];
		$nights =0;
		if(date('Y-m-d',strtotime($_POST['date1Edit']))<date('Y-m-d') && date('Y-m-d',strtotime($_POST['date2Edit']))>date('Y-m-d') )
		{
			$_POST['date1Edit']=date('Y-m-d');
	        $nights         =   ceil(abs(strtotime($_POST['date2Edit']) - strtotime($_POST['date1Edit'])) / 86400);

		}
		else
		{
			$nights         =   ceil(abs(strtotime($_POST['date2Edit']) - strtotime($_POST['date1Edit'])) / 86400);
		}
		$checkout_date=date('Y-m-d',strtotime($_POST['date2Edit']."-1 days"));
		require_once(APPPATH.'controllers/arrivalreservations.php');
        $callAvailabilities = new arrivalreservations();

        $callAvailabilities->updateavailability(0,$_POST['roomtype'], 0,hotel_id(),$_POST['date1Edit'], $checkout_date ,'changer');



        require_once(APPPATH.'models/room_auto_model.php');
        $roomassig = new room_auto_model();

        $this->load->model("room_auto_model");
        $RoomNumber =  $roomassig->Assign_room(hotel_id(),$_POST['nroomtype'],$_POST['date1Edit'],$_POST['date2Edit'] );



         $roomused=$this->db->query("select * from roomnumberused where reservationid=$reservationid and channelid=$channelid")->row_array();


		if(count($roomused)>0)
		{
			$history = array('channel_id'=>$channelid,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Room type Changed and Room Number Changed '.$roomused['roomnumber'].' To '.$RoomNumber.'  by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	    	insert_data('new_history',$history);

			update_data('roomnumberused',array('roomnumber'=>$RoomNumber,'roomid'=>$_POST['nroomtype']),array('roomnumberusedid'=>$roomused['roomnumberusedid']));
		}
		else
		{
			$history = array('channel_id'=>$channelid,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Room type Changed and The Room Number Assinged is ['.$RoomNumber.']  by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	    	insert_data('new_history',$history);

	    	$roominfo['checkin']=$_POST['date1Edit'];
	    	$roominfo['checkout']=$_POST['date2Edit'];
	    	$roominfo['roomid']=$_POST['nroomtype'];
	    	$roominfo['roomnumber']=$RoomNumber;
	    	$roominfo['hotelid']=hotel_id();
	    	$roominfo['reservationid']=$reservationid;
	    	$roominfo['channelid']=$channelid;
	    	$roominfo['active']=1;
			insert_data('roomnumberused',$roominfo);

		}
		 $indata='';

		 $nuevototal=0;
         $indata['RoomNumber']=$RoomNumber;
         $indata['room_id']=$_POST['nroomtype'];
		if ($channelid==0) {
			if($upgrade==1)
	        {
	        	if ($_POST['opt']==1) {
	        		# code...
	        	}
	        	else if ($_POST['opt']==2 || $_POST['opt']==3 ) {
	        		$reserinfo=$this->db->query("select price_details from manage_reservation where reservation_id=$reservationid")->row_array();

	        		$allprices=explode(',', $reserinfo['price_details']);

	        		$newallprice='';
	        		$total=count($allprices)-$nights;
	        		$i=1;
	        		foreach ($allprices as  $value) {
	        			if ($i<=$total) {

	        				$newallprice .=(strlen($newallprice)>0?',':'').$value;
	        				$nuevototal +=$value;

	        			}
	        			$i++;
	        		}

	        		for ($i=0; $i < $nights; $i++) {
	        			$newallprice .=(strlen($newallprice)>0?',':'').$_POST['nprice'];
	        			$nuevototal +=$_POST['nprice'];
	        		}

	        		$indata['price_details']=$newallprice;
	        		$indata['price']=$nuevototal;
	        		$indata['description']=$nights;

	        	}

	        }
			update_data('manage_reservation', $indata,array('reservation_id'=>$reservationid));
			$this->db->query("update reservationinvoice  a left join reservationinvoicedetails b on a.reservationinvoiceid=b.reservationinvoiceId
	        			set b.total =".$_POST['nprice']." where item='booking' and str_to_date(description,'%M %d, %Y') >='".$_POST['date1Edit']."' and channelId=0 and reservationId=$reservationid");
		}
		else if($channelid==1) {
			update_data('import_reservation_EXPEDIA',array('RoomNumber'=>$RoomNumber),array('import_reserv_id'=>$reservationid));
		}
		else if($channelid==2) {
			update_data('import_reservation_BOOKING_ROOMS',array('RoomNumber'=>$RoomNumber),array('room_res_id'=>$reservationid));
		}
		 $callAvailabilities->updateavailability(0,$_POST['nroomtype'], 0,hotel_id(),$_POST['date1Edit'], $checkout_date ,'new');
		echo json_encode(array('success'=>true));




	}
	function Reservation_changedate($reservationid)
	{



		$ChannelID=unsecure($_POST['ChannelID']);
		$arrivalold = date('Y-m-d',strtotime($_POST['Checkinold']));
		$departureold = date('Y-m-d',strtotime($_POST['Checkoutold']."-1 days"));
		$arrival = date('Y-m-d',strtotime(str_replace ( "/","-",$_POST['dp1'])));
		$departure = date('Y-m-d',strtotime(str_replace ( "/","-",$_POST['dp2'])."-1 days"));
		$departurenormal = date('Y-m-d',strtotime(str_replace ( "/","-",$_POST['dp2'])));
		$hotelid=hotel_id();
		$RoomQTY=$_POST['RoomQTY'];
		$nights=date_diff(date_create($arrival), date_create($departurenormal))->format('%d');
		$adult=$_POST['Adult'];
		$child=($_POST['Child']==''?0:$_POST['Child']);
		$room_id=$_POST['RoomID'];
		$reservation_id=insep_decode($reservationid);
		$RateID=$_POST['RateID'];
		$RateIDcanal=$_POST['RateIDcanal'];
		$pricepordia=$_POST['price']/$_POST['nights'];
		$ratedescription= str_replace("$pricepordia","",$_POST['ratedescription']);
		$totalprice=$pricepordia*$nights;
		$arrivalnormal=$arrival;

		$detalledays="";




		$baseRoom = $this->db->query("SELECT U.room_id FROM room_update U JOIN ".TBL_PROPERTY." P ON U.room_id = P.property_id WHERE STR_TO_DATE(U.separate_date ,'%d/%m/%Y')  between '$arrival' and '$departure' and   U.availability >=$RoomQTY AND U.minimum_stay <= $nights  AND P.member_count >=$adult AND P.children >=$child AND individual_channel_id =0 AND stop_sell=0 AND P.hotel_id=$hotelid and U.room_id=$room_id  ORDER BY U.room_id DESC")->result_array();



		if ($nights<1 or $arrival <date('Y-m-d'))
		{
			print '<script language="JavaScript">';
			print 'alert("Invalid Date Range");';
			print '</script>';

			if($ChannelID==0)
			{
				redirect('reservation/reservation_order/'.$reservationid,'refresh');

			}
			else
			{
				redirect('reservation/reservation_channel/'.$_POST['ChannelID'].'/'.$reservationid,'refresh');
			}

			return;
		}



		if(count($baseRoom) < $nights) {

			print '<script language="JavaScript">';
			print 'alert("Not Rooms availability in this date range");';
			print '</script>';
			if($ChannelID==0)
			{
				redirect('reservation/reservation_order/'.$reservationid,'refresh');

			}
			else
			{
				redirect('reservation/reservation_channel/'.$_POST['ChannelID'].'/'.$reservationid,'refresh');
			}

			return;
		}
		else
		{

		require_once(APPPATH.'controllers/arrivalreservations.php');
		$callAvailabilities = new arrivalreservations();

		$callAvailabilities->updateavailability($ChannelID,$room_id ,$RateID,$hotelid,$arrivalold,$departureold  ,'Cancel');


		if($ChannelID==0)
		{
			 	while ($arrival <= $departure)
		 		{
	             	$detalledays .=(strlen($detalledays)>0?",":"")."$pricepordia";
	           		$arrival=date('Y-m-d',strtotime($arrival."+1 days"));
	       		}

	       $data = array('start_date'=>date('d/m/Y',strtotime($arrivalnormal)),'end_date'=>date('d/m/Y',strtotime($departurenormal)),'price_details'=>$detalledays,'price'=>$totalprice,'status'=>'modified','modified_date'=>date('Y-m-d'), 'num_nights'=>$nights);

	    	  $this->db->where('reservation_id',$reservation_id);

            	$ver = $this->db->update('manage_reservation',$data);

           	if($ver)
            {
                 $history = array('channel_id'=>0,'reservation_id'=>$reservation_id,'description'=>"Change Date: Old date $arrivalold to $departureold New Date: $arrivalnormal to $departurenormal  ",'amount'=>'','extra_date'=>date('Y-m-d'),'extra_id'=>'0','history_date'=>date('Y-m-d H:i:s'),'UserID'=>user_id());

                $res = $this->db->insert('new_history',$history);


            }

		}
		elseif ($ChannelID==1)
		{
			# code...
		}
		elseif ($ChannelID==2)
		{

	            while ($arrival <= $departure)
		 		{
	             	$detalledays .=(strlen($detalledays)>0?"##":"")."date=$arrival~genius_rate=no~rate_id=$RateIDcanal~rewritten_from_id=0~rewritten_from_name=$ratedescription~$pricepordia";
	           		$arrival=date('Y-m-d',strtotime($arrival."+1 days"));
	       		}






			$data = array('arrival_date'=>$arrivalnormal,'departure_date'=>$departurenormal,'day_price_detailss'=>$detalledays,'totalprice'=>$totalprice,'status'=>'modified');

            $this->db->where('room_res_id',$reservation_id);

            $ver = $this->db->update('import_reservation_BOOKING_ROOMS',$data);

             $id= $this->db->query("select reservation_id from import_reservation_BOOKING_ROOMS where room_res_id = $reservation_id ")->row_array();


             $data = array('totalprice'=>$totalprice,'status'=>'modified');
            $this->db->where('id',$id['reservation_id']);

            $ver = $this->db->update('import_reservation_BOOKING',$data);

            if(count($id)>0)
            {
                 $history = array('channel_id'=>2,'reservation_id'=>$reservation_id,'description'=>"Change Date: Old date $arrivalold to $departureold New Date: $arrivalnormal to $departurenormal  ",'amount'=>'','extra_date'=>date('Y-m-d'),'extra_id'=>'0','history_date'=>date('Y-m-d H:i:s'),'UserID'=>user_id());

                $res = $this->db->insert('new_history',$history);


            }


		}

		$callAvailabilities->updateavailability($ChannelID,$room_id ,$RateID,$hotelid,$arrivalnormal,$departure ,'new');

			if($ChannelID==0)
			{
				redirect('reservation/reservation_order/'.$reservationid,'refresh');

			}
			else
			{
				redirect('reservation/reservation_channel/'.$_POST['ChannelID'].'/'.$reservationid,'refresh');
			}

			return;


		}

	}

	function change_room_revenue()
	{



		if($_POST['opt']==1)
		{
			$data = array('revenuertatus'=>$_POST['revenuertatus'],'maximun'=>$_POST['maximun'],'percentage'=>$_POST['percentage'],'minimun'=>$_POST['minimun'] );
		}
		elseif ($_POST['opt']==2) {
			$data = array('maximun'=>$_POST['valor']);
		}
		elseif ($_POST['opt']==3) {
			$data = array('percentage'=>$_POST['valor']);
		}
		elseif ($_POST['opt']==4) {
			$data = array('minimun'=>$_POST['valor']);
		}

		$this->db->where('property_id',$_POST['id']);
		$ver = $this->db->update('manage_property',$data);
	}

	function Reservation_ChangeStatus($ReservationID,$ChannelID,$status)
	{


		$reservation_id=insep_decode($ReservationID);
		$status=insep_decode($status);
		$ChannelID=unsecure(insep_decode($ChannelID));

		if($ChannelID ==0)
		{

		}
		elseif($ChannelID ==1)
		{

		}
		elseif($ChannelID ==2)
		{
			$result = $this->reservation_model->Reservation_ChangeStatus($reservation_id,$status,$ChannelID );


		}

		if($result)
		{

			$this->session->set_flashdata('success','Status update Successfully');
		}
		else
		{
			$this->session->set_flashdata('error', 'Unmodified Status');
		}
		redirect('reservation/reservation_channel/'.secure($ChannelID).'/'.$ReservationID,'refresh');


	}

	function changeStatus()
	{
		$Mensaje='';
		$statusid=$_POST['statusid'];
		$reservationid=$_POST['resid'];
		$inforest=$this->db->query("SELECT room_id,rate_types_id,str_to_date(start_date,'%d/%m/%Y') checkin, str_to_date(end_date,'%d/%m/%Y') checkout FROM manage_reservation where reservation_id=".$reservationid)->row_array();
		$checkout_date= date('Y-m-d',strtotime($inforest['checkout']."-1 days"));

		require_once(APPPATH.'controllers/arrivalreservations.php');
        $callAvailabilities = new arrivalreservations();


		if($statusid==0)
		{
			$data['status']='Canceled';
			update_data('manage_reservation',$data,array("reservation_id"=>$reservationid));
			 $history = array('channel_id'=>0,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Reservation Canceled by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
             insert_data('new_history',$history);
            update_data('roomnumberused',array('active'=>0),array("reservationid"=>$reservationid,'channelid'=>0));
          $callAvailabilities->updateavailability(0,$inforest['room_id'], $inforest['rate_types_id'],hotel_id(),$inforest['checkin'], $checkout_date ,'cancel');
		}
		if($statusid==3)
		{
			$data['status']='No Show';
			update_data('manage_reservation',$data,array("reservation_id"=>$reservationid));
			 $history = array('channel_id'=>0,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Status Change To No Show by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
             insert_data('new_history',$history);
             update_data('roomnumberused',array('active'=>0),array("reservationid"=>$reservationid,'channelid'=>0));
          $callAvailabilities->updateavailability(0,$inforest['room_id'], $inforest['rate_types_id'],hotel_id(),$inforest['checkin'], $checkout_date ,'No Show');
		}
		if($statusid==5)
		{
			$data['status']='Checkin';
			update_data('manage_reservation',$data,array("reservation_id"=>$reservationid));
			 $history = array('channel_id'=>0,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Status Change To Check-in by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
             insert_data('new_history',$history);
		}
		if($statusid==6)
		{
			$total=$this->db->query("SELECT  sum(b.total)+sum(b.tax) deudaTotal FROM
					reservationinvoice a
					left join reservationinvoicedetails b on a.reservationinvoiceid=b.reservationinvoiceid
					where reservationid=".$reservationid)->row()->deudaTotal;
			$pagos=$this->db->query("select ifnull(sum(amount),0) pagototal from reservationpaymenttype
			where reservationinvoiceid in (SELECT reservationinvoiceid FROM
			reservationinvoice where  reservationid=".$reservationid.")")->row()->pagototal;

			if($total>$pagos)
			{
				$Mensaje='This reservation has a pending balance $'.number_format(($total-$pagos), 2, '.', ',');
				echo json_encode(array("success"=>false,"message"=>$Mensaje));
				return;
			}
			else
			{
				$data['status']='Checkout';
				update_data('manage_reservation',$data,array("reservation_id"=>$reservationid));
				 $history = array('channel_id'=>0,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Status Change To Check-out by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	             insert_data('new_history',$history);
			}


		}

		echo json_encode(array("success"=>true,"message"=>$Mensaje));
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
			redirect('inventory/inventory_dashboard','refresh');
		}
	}
	// sharmila..
	 // add  rservation ...
	function add_reservation(){

		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('start_date')!=''){
		$data['page_heading'] = 'Reservation';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->reservation_model->get_room();
		$this->load->view('channel/room_list',$data);
		} else{
				$data['page_heading'] = 'Reservation';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$this->views('channel/reservation1',$data);
		}
	}

    // reservation list..
/*	function reservationlist()
	{
		$data['page_heading'] = 'ReservationList';
		if(admin_id()=='')
		{
			$this->is_login();

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->views('channel/reservation_list',$data);
			}
			elseif(user_type()=='2')
			{
				if(in_array('2',user_view()))
				{
					$data['user_view']=user_view();
					$data['user_edit']=user_edit();
					$this->views('channel/reservation_list',$data);
				}
				else
				{
					redirect(base_url());
				}
			}
		}
		else if(admin_id()!='' && admin_type()=='1')
		{
			$this->is_admin();
			$this->views('channel/reservation_list',$data);
		}
	}*/
	function PaymentApplication()
	{
		switch ($_POST['paymentTypeId']) {
			case '1':

				$result= $this->reservation_model->invoicepaymentapply($_POST['invoiceid'],$_POST['paymentTypeId'],$_POST['amountdue'],$_POST['providerid'],$_POST['username'],$_POST['currency']);

				echo json_encode($result);
				break;

			case '2':
				if ($_POST['providerid']==1) {
					require_once(APPPATH.'controllers/Stripe_payment.php');
					$stripe = new Stripe_payment();

					$stripe->checkout();

				}
				break;
			case '3':
				# code...
				break;
			default:
				# code...
				break;
		}

	}
	function invoicepaymentapply()
	{
		$reservationinvoiceid=$_POST['reservationinvoiceid'];
		$id=explode(",",$_POST['paymenttypeid']);
		$paymenttypeid=$id[1];
		$amount=$_POST['amount'];
		$paymentmethod=$_POST['paymentmethod'];
		$username=$_POST['username'];
		$result=$this->reservation_model->invoicepaymentapply($reservationinvoiceid,$paymenttypeid,$amount,$paymentmethod,$username);
		echo $result;
	    return ;

	}
	function reservationinvoicecreate()
	{
		$channelID=unsecure($_POST['channelId']);
		$ReservationID=insep_decode($_POST['reservationId']);
		$userName=$_POST['username'];
		$reservationdetails=$this->reservation_model->reservationdetails($channelID,$ReservationID);
		$result=$this->reservation_model->reservationinvoicecreate($channelID,$ReservationID,$userName,$reservationdetails);
	    echo $result;
	    return;

	}
	function printreservation($chanel,$resid,$idioma)
	{
			$channelID=unsecure($chanel);
			$ReservationID=insep_decode($resid);

			$billing=$this->db->query("select * from bill_info where hotel_id=".hotel_id())->row_array();
			$data['Logo']=base_url()."uploads/room_photos/noimage.jpg";
			$data['Reservation']=$this->reservation_model->reservationdetails($channelID,$ReservationID);
			$data['roominfo']=$this->db->query("select a.*, b.meal_name
				from manage_property a
				left join meal_plan b on a.meal_plan=b.meal_id
				where property_id=".$data['Reservation']['roomTypeID'])->row_array();

			$data['policies']=$this->db->query("select * from policies where hotelid=".hotel_id())->result_array();
			$data['language']=insep_decode($idioma);
			$this->view('channel/sheetcheckin',$data);

	}
	function saveExtras()
	{
		$channelID=$_POST['channelId'];
		$extrasId=$_POST['extraId'];
		$reservationId=insep_decode($_POST['reservationId']);
		$userName=$_POST['userName'];
		$data=$this->reservation_model->saveExtras($channelID,$extrasId,$reservationId,$userName);

		echo json_encode($data);
		return;
	}
	function tst()
	{
		require_once(APPPATH.'controllers/sendemail.php');
            $sendemail = new sendemail();
       		$sendemail->sendmailreservation('2908,2909,2910');

		return;
	}
	function ValidateCreditCard()
	{
		require_once(APPPATH.'controllers/ValidateCreditCard.php');


		$credit_card_user    = $_POST['cccard']; // VISA

		// $credit_card_user = "5370517873215895"; // MASTER CARD

		// $credit_card_user = "372249624477942";  // American Express

		// $credit_card_user = "6011103438289080"; // Discover Card

		// $credit_card_user = "30350727682943";   // Diners Club Carte Blanche

		// $credit_card_user = "36612758986350";   // Diners Club International

		// $credit_card_user = "3370728773640984"; // JCB

		 if (ValidateCreditCard::validateFormatCreditCard($credit_card_user) && ValidateCreditCard::calculateLuhn($credit_card_user))
		 {
		 	$result['success']=true;
		 	echo json_encode($result);
		 }
		 else
		 {$result['success']=false;
		 	echo json_encode($result);
		 }

	}
	function saveReservation()
	{

		$allroom=$_POST['numroom'];
		$allReservationId='';
		for ($i=0; $i < $allroom; $i++) {
			$_POST['numroom']=1;
			$result=$this->reservation_model->saveReservation();

			$allReservationId .=(strlen($allReservationId)>2?',':'').$result['reservationid'];

			$channelID=0;
			$ReservationID=$result['reservationid'];
			$userName=$_POST['username'];
			$reservationdetails=$this->reservation_model->reservationdetails($channelID,$ReservationID);
			$this->reservation_model->reservationinvoicecreate($channelID,$ReservationID,$userName,$reservationdetails);
		}


		if(!$result['success'])
		{
			echo json_encode($result);
		}
		else
		{
			$checkout_date= date('Y-m-d',strtotime($_POST['checkout']."-1 days"));
			require_once(APPPATH.'controllers/arrivalreservations.php');
            $callAvailabilities = new arrivalreservations();
       		$callAvailabilities->updateavailability(0,$_POST['roomid'], $_POST['rateid'],hotel_id(),$_POST['checkin'], $checkout_date ,'new',$allroom);
		}

		if(isset($_POST['sendemail']) && ENVIRONMENT=='production')
		{
			require_once(APPPATH.'controllers/sendemail.php');
            $sendemail = new sendemail();
       		$sendemail->sendmailreservation($allReservationId);
		}


		echo json_encode($result);
	}
	function updatereservation()
	{
		$strim=$_POST['mainguest'];
		$pos= strpos($strim, ' ');
		$name=trim(substr($strim,0, $pos));
		$lastname=trim(substr($strim, $pos));
		$guest=null;
		if(isset($_POST['guest']))
		{
			$guest=implode(',', $_POST['guest']);
		}

		$this->reservation_model->updatereservation($_POST['canalid'],$_POST['reservaid'],$name,$lastname,$guest);

	}
	function invoiceheader()
	{
		$this->is_login();
		$invoice=get_data("reservationinvoice",array('reservationinvoiceid'=>$_POST['id']))->row_array();
		$invoicedetails=get_data("reservationinvoicedetails",array('reservationinvoiceId'=>$_POST['id']))->result_array();
		$billing=get_data("bill_info",array('hotel_id'=>hotel_id()))->row_array();

		if(!isset($billing['country']))
		{
			$billing['company_name']='No config';
			$billing['address']='No config';
			$country='No config';
			$billing['mobile']='No config';
			$billing['email_address']='No config';
			$billing['town']='No config';

		}
		else
		{
			$country=get_data("country",array('id'=>$billing['country']))->row_array()['country_name'];
		}

   		$html='<div  class="graph-form">
					<div class="text-left" style="float: left; width:50%; ">
						<h4> Invoice</h4>
					</div>
					<div class="text-right" style="float: right; width:50%;">
						<h4>#'.str_repeat('0',8-strlen($invoice['Number'])).$invoice['Number'].'/'.date('M d,Y').'</h4>
					</div>
				</div>
				 <div class="clearfix">

					<div class="graph-form">
				<div   class="text-left col-md-6 form-group1"> ';

         $html .='<h4>'.$billing['company_name'].'</h4>
         		<p>'.$billing['town'].'<br>
         		'.$billing['address'].'<br>
         		'.$country.'<br>
         		<strong>Phone: </strong>'.$billing['mobile'].'<br>
         		<strong>Email: </strong>'.$billing['email_address'].'</p>';

         $html .='</div>
         <div class="text-left col-md-6 form-group1">
         		<h4>Bill To:</h4>
         		<strong>Name: </strong>'.$_POST['name'].'<br>
         		<strong>Address: </strong>'.$_POST['address'].'<br>
         		<strong>City: </strong>'.$_POST['city'].'<br>
         		<strong>Country: </strong>'.$_POST['country'].'<br>
         		<strong>Email: </strong>'.$_POST['email'].'<br>
          </div>
           <div class="clearfix">
          </div> ';


          $table='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Item</th>
												<th>Description</th>
												<th>Total</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					$totaltax=0;
					$subtotal=0;
					if(count($invoicedetails)>0)
					{
						foreach ($invoicedetails as  $value) {
							$i++;
							$table.=' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
								' </th> <td>'.$value['item'].'  </td> <td>'.$value['description'].'</td>
									<td  align="right"> '.number_format($value['total'], 2, '.', '').' </td> </tr>';

								$totaltax +=$value['tax'];
								$subtotal +=$value['total'];
						}
					}

					$table.='</tbody>
									</table>
									</div> </div>';

					$footer='<div id="footerfac" class="col-md-6 graph-2 second">
							    <div class="panel panel-primary">
							        <div class="panel-heading"></div>
							        <div class="panel-body">
							            <div class="tables">
							                <table class="table">
							                    <tbody>
							                        <tr>
							                            <td> <strong>Sub Total:&nbsp</strong></td>
							                            <td style="text-align: right;">
							                                '.number_format($subtotal, 2, '.', '').'
							                            </td>
							                        </tr>
							                        <tr>
							                            <td> <strong>Total Tax:&nbsp</strong></td>
							                            <td style="text-align: right;">
							                                '.number_format($totaltax, 2, '.', '').'
							                            </td>
							                        </tr>
							                    </tbody>
							                </table>
							                <div align="center">
							                    <h2><strong>Total Due:</strong> '.number_format($totaltax+$subtotal, 2, '.', '').'</h2>
							                </div>
							            </div>
							        </div>
							    </div>
							</div>

							<div id="botonprint" class="text-left col-md-6 form-group1">
								<a onclick="imprimir('."'headerinvoice'".')" class="btn btn-lg blue hidden-print margin-bottom-5">
			                      Print <i class="fa fa-print"></i>
			                      </a>
							</div>
							<div class="clearfix"></div>';

//number_format($value['price'], 2, '.', '')

         $data['html']=$html.$table.$footer;
        echo json_encode($data);

	}

	function invoicebody()
	{
		$_POST['id'];
	}
	function reservationdetails($channelID,$ReservationID)
	{
		$this->is_login();
		$data['page_heading'] = 'Reservation Details';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['reservationdetails']=$this->reservation_model->reservationdetails(unsecure($channelID),insep_decode($ReservationID));
		$data['historyInfo']=$this->reservation_model->historyInfo(unsecure($channelID),insep_decode($ReservationID));

		$data['Invoice']=$this->reservation_model->reservationInvoice(unsecure($channelID),insep_decode($ReservationID));
		$data['payment']=$this->reservation_model->payment();
		$data['Currencies']=$this->db->query("SELECT * FROM `currency` ORDER BY `currency`.`currency_code` ASC ")->result_array();
		$data['ALLUsersNotes']=$this->reservation_model->AllUsersNotes(insep_decode($ReservationID),unsecure($channelID));
		$this->views('channel/reservationdetails',$data);

		//detalles de reservas

	}
	function addnoteuser()
	{
		$info['reservationid']=$_POST['resid'];
		$info['channelid']=$_POST['channelid'];
		$info['userid']=user_id();
		$info['hotelid']=hotel_id();
		$info['description']=$_POST['note'];
		$info['active']=1;
		insert_data('reservationnotes',$info);
		$history = array('channel_id'=>$info['channelid'],'Userid'=> user_id(),'reservation_id'=>$info['reservationid'],'description'=>'Note Added by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	    insert_data('new_history',$history);

	    echo json_encode(array('success'=>true));
	}
	function RoomsAvailables()
	{
		 $this->load->model("room_auto_model");
         $available=$this->room_auto_model->allRoomAvailable(hotel_id(),$_POST['room_id'],$_POST['checkin'],$_POST['checkout'] );

         if(count($available)>0)
         {
         	$html='';
			$html.='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table  class="table table-bordered">
								<thead>
										<tr>
												<th>Room Number</th>
												<th style="text-align:center;">Assing</th>
										</tr>
															 </thead>
								<tbody>';
			$i=0;
			foreach ($available as  $value) {
				$i++;
				$html.=' <tr  class="'.($i%2?'active':'success').'"> <td>'.$value.'  </td>
						<td  align="center"><a  onclick="assingNumber('."'".$value."'".');">
						 <i class="fa fa-check-circle fa-2x"></i></a> </td> </tr>';


			}
			$html.='</tbody></table></div> </div>';

			echo json_encode(array('success'=>true,'html'=>$html));
         }
         else
         {
         	echo json_encode(array('success'=>false,'message'=>"There aren't Rooms Available for this date range"));
         }

	}
	function assingRoomNumbers()
	{
		$channelid=$_POST['channelid'];
		$reservationid=$_POST['resid'];
		$RoomNumber=$_POST['roomnumber'];
		$roomused=$this->db->query("select * from roomnumberused where reservationid=$reservationid and channelid=$channelid")->row_array();

		if(count($roomused)>0)
		{
			$history = array('channel_id'=>$channelid,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'Room Number Changed '.$roomused['roomnumber'].' To '.$RoomNumber.'  by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	    	insert_data('new_history',$history);

			update_data('roomnumberused',array('roomnumber'=>$RoomNumber),array('roomnumberusedid'=>$roomused['roomnumberusedid']));
		}
		else
		{
			$history = array('channel_id'=>$channelid,'Userid'=> user_id(),'reservation_id'=>$reservationid,'description'=>'The Room Number Assinged is ['.$RoomNumber.']  by '.$_POST['username'],'history_date'=>date('Y-m-d H:i:s'),'amount'=>0,'extra_id'=>1);
	    	insert_data('new_history',$history);

	    	$roominfo['checkin']=$_POST['checkin'];
	    	$roominfo['checkout']=$_POST['checkout'];
	    	$roominfo['roomid']=$_POST['roomtype'];
	    	$roominfo['roomnumber']=$RoomNumber;
	    	$roominfo['hotelid']=hotel_id();
	    	$roominfo['reservationid']=$reservationid;
	    	$roominfo['channelid']=$channelid;
	    	$roominfo['active']=1;
			insert_data('roomnumberused',$roominfo);

		}

		if ($channelid==0) {

			update_data('manage_reservation',array('RoomNumber'=>$RoomNumber),array('reservation_id'=>$reservationid));
		}
		else if($channelid==1) {
			update_data('import_reservation_EXPEDIA',array('RoomNumber'=>$RoomNumber),array('import_reserv_id'=>$reservationid));
		}
		else if($channelid==2) {
			update_data('import_reservation_BOOKING_ROOMS',array('RoomNumber'=>$RoomNumber),array('room_res_id'=>$reservationid));
		}

		echo json_encode(array('success'=>true));
	}
	// reservation list..
	function reservationlist()
	{


		$this->is_login();
    	$data['page_heading'] = 'Reservation List';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['payment']=$this->reservation_model->payment();
		$data['Currencies']=$this->db->query("SELECT * FROM `currency` ORDER BY `currency`.`currency_code` ASC ")->result_array();
		$id=array_search($data['HotelInfo']['currency'], array_column($data['Currencies'],'currency_id'));
		$data['currency']=(isset($data['Currencies'][$id]['currency_code'])?$data['Currencies'][$id]['currency_code']:'USD');
		$data['AllChannel']=$this->channel_model->allChannelsConnect();
		$this->views('channel/reservationlist',$data);

	}
	function reservationlisthtml()
	{
		$date1=(strlen($_POST['date1'])==0?'1999-01-01':date('Y-m-d',strtotime($_POST['date1'])));
		$date2=(strlen($_POST['date2'])==0?'3000-01-01':date('Y-m-d',strtotime($_POST['date2'])));
		$channels=$_POST['channels'];
		$status=$_POST['status'];
		$alllist=$this->reservation_model->AllReservationList($date1,$date2,$channels,$status);


		$html='';
			$html.= '<div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">

                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                                <th>Status</th>
                                <th>Full Name</th>
                                <th width="10%">Room Booked</th>
                                <th>Room #</th>
                                <th>Channel</th>
                                <th>Checkin</th>
                                <th>Checkout</th>
                                <th>Booked</th>
                                <th>Reservation #</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>';



                            if (count($alllist['info'])>0) {
                            	$allLogo=$alllist['logo'];
                                foreach ($alllist['info'] as  $value) {
                                    $class_status=($value['status']==0?'danger':($value['status']==1?'success':($value['status']==2?'warning':($value['status']==3?'default':($value['status']==4?'success':($value['status']==5?'primary':($value['status']==6?'warning':'active')))))));

                                    $show_status=($value['status']==0?'Canceled':($value['status']==1?'Reserved':($value['status']==2?'Modified':($value['status']==3?'No Show':($value['status']==4?'Confirmed':($value['status']==5?'Check-in':($value['status']==6?'Check-out':'Unchecked')))))));

                                    $roomNameC='<a style="padding: 0px;" href="#" class="inline_username" data-type="select" data-pk="'.$value['reservation_id'].'" data-value="'.$value['room_id'].'" data-source="'.lang_url().'channel/allroomtest" title="Select Room Type"></a>';

                                    $html.=' <tr scope="row" class="active"> <th scope="row"><h5><span class="label label-'.$class_status.'">'.$show_status.'</span></h5> </th> <td> <a href="'.site_url('reservation/reservationdetails/'.secure($value['channel_id']).'/'.insep_encode($value['reservation_id'])).'">'.$value['Full_Name'].' </a> </td> <td>'.$roomNameC.'</td> <td>'.$value['RoomNumber'].'</td>
                                    <td style="text-align:center;"> <img  src="data:image/png;base64,'.$allLogo['LogoReservation'.$value['channel_id']].'">     <p style ="color: rgba(0, 0, 0, 0);">'.$value['channel_id'].'</p> </td>  <td>'.date('m/d/Y',strtotime($value['start_date'])).'</td> <td>'.date('m/d/Y',strtotime($value['end_date'])).'</td> <td>'.date('m/d/Y',strtotime($value['booking_date'])).'</td> <td>'.$value['reservation_code'].'</td> <td>'.number_format ( $value['price'] , 2 ,  "." , "," ).'</td> </tr>  ';

                                }
                                 $html.='</tbody> </table> </div></div></div>';
                                 echo $html;
                            }
                            else
                            {
                            	$html='<center><h1><span class="label label-danger">'.infolang('norecordfound').'</span></h1></center>';
                            	echo $html;
                            }





	}

	function table_mapping(){
		$channels = $this->db->get('manage_channel')->result();
		foreach ($channels as $channel) {
			$channel_name = strtoupper($channel->channel_name);
			echo "<pre>";
			echo $channel_name."<br>";
			echo $this->db->table_exists('import_reservation_'.$channel_name)	;
			if($this->db->table_exists('import_reservation_'.$channel_name)){
				$fields = $this->db->list_fields('import_reservation_'.$channel_name);

			}
			// /var_dump( $this->db->table_exists('import_reservation_'.$channel_name) );
		}
	}
	function adminagencies()
	{
		is_login();
    	$data['page_heading'] =$this->lang->line('agenciesmanager');
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['Allagencycategories']=get_data('agencycategories',array('hotelid'=>hotel_id()))->result_array();
		$this->views('channel/agenciesmanager',$data);
	}
	function saveagencycategory()
	{
		$data['name']=$_POST['name'];
		$data['active']=1;
		$data['hotelid']=hotel_id();
		$result['success']=true;
		if(insert_data('agencycategories',$data))
		{
			$result['success']=true;
		}
		echo json_encode($result);
		# agencycategoryid, name, active, hotelid

	}
	function updateagency()
	{

		$data['CategoryId']=$_POST['categoryid'];
		$data['Name']=$_POST['agencyname'];
		$data['CommissionValue']=$_POST['amount'];
		$data['CommissionType']=$_POST['CommissionType'];
		$data['Active']=isset($_POST['active'])?1:0;
		$result['success']=false;
		if(update_data('agencies',$data,array('hotelid'=>hotel_id(),'AgencyId'=>$_POST['id'])))
		{
			$result['success']=true;
		}

		echo json_encode($result);
	}
	function saveagency()
	{
		$data['HotelId']=hotel_id();
		$data['CategoryId']=$_POST['acategoryid'];
		$data['Name']=$_POST['agencyname'];
		$data['CommissionValue']=$_POST['amount'];
		$data['CommissionType']=$_POST['CommissionType'];
		$data['Active']=1;
		$result['success']=false;
		if(insert_data('agencies',$data))
		{
			$result['success']=true;
		}

		echo json_encode($result);
	}
	function agenciesHTML()
	{
		$categoryid=$_POST['categoryid'];


		$AllAgencies=$this->db->query("select a.*, b.name categoryname
			from agencies a
			left join agencycategories b on a.CategoryId=b.agencycategoryid
			where a.hotelid=".hotel_id()."
			and (CategoryId=$categoryid or $categoryid=0) ")->result_array();

		$html='';
			$html.= '<div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">

                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                                <th>'.$this->lang->line('agencyname').'</th>
                                <th>'.$this->lang->line('categoriesagency').'</th>
                                <th>'.$this->lang->line('group').'</th>
                                <th>'.$this->lang->line('commissiontype').'</th>
                                <th>'.$this->lang->line('status').'</th>
                                <th>'.$this->lang->line('edit').'</th>
                            </tr>
                        </thead>
                        <tbody>';



                            if (count($AllAgencies)>0) {

                                foreach ($AllAgencies as  $value) {

                                	$update="'".$value['AgencyId']."','".$value['CategoryId']."','".$value['groupid']."','".$value['Name']."','".$value['CommissionValue']."','".$value['CommissionType']."','".$value['Active']."'";

                                    $html.=' <tr scope="row" class="default">  <td>'.$value['Name'].' </td>
									<td>'.$value['categoryname'].'</td>
									<td>'.($value['groupid']==1?$this->lang->line('retailers'):$this->lang->line('wholesalers')).'</td>
									<td>'.($value['CommissionType']==0?$this->lang->line('netrate'):($value['CommissionType']==1?$this->lang->line('money'):$this->lang->line('percentage'))).'</td>
									<td>'.($value['Active']==1?$this->lang->line('active'):$this->lang->line('disabled')).'</td>
									<td> <a href="javascript:;" onclick="updateagency('.$update.')" > <i class="fa fa-edit"></i></a></td>
                                   	 </tr>  ';

                                }
                                 $html.='</tbody> </table> </div></div></div>';
                                 echo $html;
                            }
                            else
                            {
                            	$html='<center><h1><span class="label label-danger">'.$this->lang->line('norecordfound').'</span></h1></center>';
                            	echo $html;
                            }


	}
	function updateagencycategory()
	{
		$data['name']=$_POST['name'];
		$data['active']=$_POST['active'];
		$result['success']=true;
		if(update_data('agencycategories',$data,array('agencycategoryid'=>$_POST['id'])))
		{
			$result['success']=true;
		}
		echo json_encode($result);
		# agencycategoryid, name, active, hotelid

	}
	// reservation order ...
	function reservation_order($id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		if($id=='')
		{
			redirect('reservation/reservationlist','refresh');
		}

		$id 		= insep_decode($id);

		$channel	= 0;
		$data['curr_cha_id'] = secure(0);
		$data['reservation_details'] = get_data(RESERVATION,array('reservation_id'=>($id)))->row_array();

		$data['bill'] = $this->reservation_model->billing_details();

		$data['extra'] = $this->reservation_model->get_extras($channel,$id);

		$data['extra_count'] = $this->reservation_model->extra_count($channel,$id);

		$data['invoice_count'] = $this->reservation_model->invoice_count($channel,$id);

		$data['invoice'] = $this->reservation_model->get_invoices($channel,$id);

		$data['add_pay'] = $this->reservation_model->get_payment_paid($channel,$id);

		$data['welcome'] = get_data(WE,array('mail_type'=>1,'channel_id'=>$channel,'reservation_id'=>$id))->row_array();

		$data['remainder'] = get_data(WE,array('mail_type'=>2,'channel_id'=>$channel,'reservation_id'=>$id))->row_array();

		if($this->input->post('save'))
		{
			$reser_id = $this->input->post('reserve_id');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$result = $this->reservation_model->edit_reservation($reser_id);
			}
			else if(user_type()=='2')
			{
				if(in_array('2',user_edit()))
				{
					$result = $this->reservation_model->edit_reservation($reser_id);
				}
				else
				{
					redirect(base_url());
				}
			}
			if($result)
			{
				$this->session->set_flashdata('success', 'successfully updated.');

				redirect('reservation/reservation_order/'.insep_encode($reser_id),'refresh');

			}
			else
			{
				$this->session->set_flashdata('error', 'Not Updated.');

				redirect('reservation/reservation_order/'.insep_encode($reser_id),'refresh');
			}
		}
		else
		{
			$data['page_heading'] = 'Reservation Order';

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

			$data= array_merge($user_details,$data);

			$data['reser_id'] = insep_encode($id);

			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->views('channel/reservation_order',$data);
			}
			elseif(user_type()=='2')
			{
				$data['user_view']=user_view();
				$data['user_edit']=user_edit();
				if(in_array('2',user_view()))
				{
					$this->views('channel/reservation_order',$data);
				}
				else
				{
					redirect(base_url());
				}
			}
		}
	}

	function reservation_channel($channel_id,$reservation_id)
	{

		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		$id 		= insep_decode($reservation_id);

		$channel	= unsecure($channel_id);

		$data['curr_cha_id'] = $channel_id;

		  $currencys = $this->db->query("SELECT * FROM `currency` ORDER BY `currency`.`currency_code` ASC ")->result_array();
		  $data['CURRENCYS'] = $currencys;

		if($channel==11)
		{
			$reservation_channeldetails = get_data('import_reservation_RECONLINE',array('import_reserv_id '=>($id)))->row_array();

			$data['CC_NAME']			=	$reservation_channeldetails['NAME'];
			$data['CC_NUMBER']			=	$reservation_channeldetails['CCNUMBER'];
			$data['CC_DATE']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], 0,2);
			$data['CC_YEAR']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], -2);

			$data['RESER_NUMBER'] 		= 	$reservation_channeldetails['IDRSV'];
			$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($reservation_channeldetails['RSVCREATE']));
			$data['RESER_ID'] 			= 	$reservation_channeldetails['import_reserv_id'];

			$data['curr_cha_currency'] 	= 	$reservation_channeldetails['CURRENCY'];
			$data['guest_name']			= 	$reservation_channeldetails['FIRSTNAME'];
			$data['start_date'] 		= 	date('Y/m/d',strtotime($reservation_channeldetails['CHECKIN']));
			$data['end_date']			=	date('Y/m/d',strtotime($reservation_channeldetails['CHECKOUT']));
			$data['reservation_code']	= 	$reservation_channeldetails['IDRSV'];
			$data['ROOMCODE']			=	$reservation_channeldetails['ROOMCODE'];
			if($reservation_channeldetails['STATUS']==11)
			{
				$data['status'] = 'New booking';
			}
			else if($reservation_channeldetails['STATUS']==12)
			{
				$data['status'] = 'Modification';
			}
			else if($reservation_channeldetails['STATUS']==13)
			{
				$data['status'] = 'Cancellation';
			}
			$data['start_date']			=	$reservation_channeldetails['CHECKIN'];
			$data['end_date']			=	$reservation_channeldetails['CHECKOUT'];

			$data['CHECKIN']			=	date('Y/m/d',strtotime($reservation_channeldetails['CHECKIN']));
			$data['CHECKOUT']			=	date('Y/m/d',strtotime($reservation_channeldetails['CHECKOUT']));

			$data['nig'] 				=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);

			$data['ADULTS']				= 	$reservation_channeldetails['ADULTS'];
			$data['CHILDREN']			= 	$reservation_channeldetails['CHILDREN'];

			$data['description']		= 	$reservation_channeldetails['REMARK'];
			$data['policy_checin']		= 	$reservation_channeldetails['CORRCHECKIN'];
			$data['policy_checout']		= 	$reservation_channeldetails['CORRCHECKOUT'];
			$data['CRIB']				=	$reservation_channeldetails['CRIB'];
			$data['subtotal']			= 	$reservation_channeldetails['CORRRATE']*$data['nig'];
			$data['CURRENCY']			=	$reservation_channeldetails['CURRENCY'];

			$data['guest_name'] 		=	$reservation_channeldetails['FIRSTNAME'];
			$data['email']				=	$reservation_channeldetails['EMAIL'];
			$data['street_name'] 		=	$reservation_channeldetails['ADDRESS1'];
			$data['city_name'] 			=	$reservation_channeldetails['CITY'];
			$data['country'] 			=	$reservation_channeldetails['COUNTRY'];
			$data['phone'] 				=	$reservation_channeldetails['TELEPHONE1'];
			$data['commission']	  		= 	$reservation_channeldetails['COMMISSION'];
			$data['mealsinc']			= 	$reservation_channeldetails['MEALSINC'];
			$data['description']		= 	$reservation_channeldetails['REMARK'];
			$data['price'] 				= 	$reservation_channeldetails['CORRRATE'];
			if($reservation_channeldetails['CCIDCARDORGANISATION']='0')
			{
				$data['payment_method'] = 'Cash';
			}
			else
			{
				$data['payment_method'] = 'Credit Card';
			}
		}
		elseif($channel==19)
		{

			$this->load->model('agoda_model');
			$reservation_channeldetails = get_data('import_reservation_AGODA',array('import_reserv_id '=>($id)))->row_array();


			$roomdetails = $this->db->query(" SELECT b.* FROM import_mapping_AGODA as a left join roommapping as b on a.map_id = b.import_mapping_id and b.channel_id=19 where a.roomtype_id =".$reservation_channeldetails['room_id']." and rate_type_id=".$reservation_channeldetails['rateplan_id'])->row_array();

			$data['CC_NAME']=	$reservation_channeldetails['card_name'];
			$data['CC_NUMBER']=	$reservation_channeldetails['card_number'];
			$data['CC_DATE']=	substr($reservation_channeldetails['card_exp'], 0,2);
			$data['CC_YEAR']=	substr($reservation_channeldetails['card_exp'], -2);
			$data['CC_CVC']=	$reservation_channeldetails['card_cvv'];


			$data['RESER_NUMBER']= 	$reservation_channeldetails['booking_id'];
			$data['RESER_DATE']= 	date('M d,Y',strtotime($reservation_channeldetails['booking_date']));
			$data['RESER_ID']= 	$reservation_channeldetails['import_reserv_id'];
			$data['roomtypeId']=   $roomdetails['property_id'];
			$data['rateplanid']=  $roomdetails['rate_id'];

			$data['curr_cha_currency']= 	$reservation_channeldetails['currency'];
			$data['guest_name']= 	$reservation_channeldetails['firstname'].' '.$reservation_channeldetails['lastname'];
			$data['start_date']= 	date('Y/m/d',strtotime($reservation_channeldetails['arrival']));
			$data['end_date']=	date('Y/m/d',strtotime($reservation_channeldetails['departure']));
			$data['channel_room_name']= $reservation_channeldetails['room_type'].'-'.$reservation_channeldetails['rateplan_name'] ;
			$data['reservation_code']= 	$reservation_channeldetails['booking_id'];
			$data['RoomNumber']	= 	$reservation_channeldetails['RoomNumber'];
			$data['ROOMCODE']=	'';
			$data['promotion_name']=$reservation_channeldetails['promotion_name'];
			$data['channel_namexml']=$reservation_channeldetails['channel_name'];

			if($reservation_channeldetails['status']=='ConfirmBooking')
			{
				$data['status'] = 'New Booking';
			}
			else if($reservation_channeldetails['status']=='AmendBooking')
			{
				$data['status'] = 'Modification';
			}
			else if($reservation_channeldetails['status']=='CancelBooking')
			{
				$data['status'] = 'Cancellation';
			}
			$data['start_date']=	$reservation_channeldetails['arrival'];
			$data['end_date']=	$reservation_channeldetails['departure'];

			$data['CHECKIN']=	date('Y/m/d',strtotime($reservation_channeldetails['arrival']));
			$data['CHECKOUT']=	date('Y/m/d',strtotime($reservation_channeldetails['departure']));

			$data['nig']=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);

			$inbwdays = explode(',',$reservation_channeldetails['pricedate']);
			$baseRate = explode(',', $reservation_channeldetails['pricemoney']);

			for($i=0; $i<count($inbwdays); $i++){
				if($inbwdays[$i] != ""){
					$data['perdayprice'][] = array(
						$inbwdays[$i] => $baseRate[$i],
					);
				}
			}

			$data['inbwdays']= $reservation_channeldetails['pricedate'];
			$data['baseRate']= $reservation_channeldetails['pricemoney'];
			$data['tax']= 0;

			$data['ADULTS']= 	$reservation_channeldetails['adults'];
			$data['CHILDREN']= 	$reservation_channeldetails['children'];

			$data['description']= 	$reservation_channeldetails['requests'];
			$data['policy_checin']= 	'';//$reservation_channeldetails['CORRCHECKIN'];
			$data['policy_checout']= 	'';//$reservation_channeldetails['CORRCHECKOUT'];
			$data['CRIB']='';//	$reservation_channeldetails['child_age'];
			$data['subtotal']= 	$reservation_channeldetails['net_inclusive_amt'];
			$data['discount']= 	($reservation_channeldetails['refsell_amt']==0 || $reservation_channeldetails['refsell_amt'] ==''?0: $reservation_channeldetails['refsell_amt']-$reservation_channeldetails['net_inclusive_amt']);
			$data['CURRENCY']=	$reservation_channeldetails['currency'];

			$data['email']=	$reservation_channeldetails['email'];
			$data['street_name']='';//	$reservation_channeldetails['address'];
			$data['city_name']='';//	$reservation_channeldetails['city'];
			$data['country']='';//	$reservation_channeldetails['country'];
			$data['phone']=	$reservation_channeldetails['phone'];
			$data['commission']	= 0;
			$data['mealsinc']= 	'';//$reservation_channeldetails['MEALSINC'];
			$data['price']= $reservation_channeldetails['net_inclusive_amt'];
			$data['payment_method']=($reservation_channeldetails['paymentmodel']=='1'?'Merchance booking (Agoda Collect)':'Agency booking (Property Collect)');
			/*if($reservation_channeldetails['CCIDCARDORGANISATION']='0')
			{
				$data['payment_method'] = 'Cash';
			}
			else
			{
				$data['payment_method'] = 'Credit Card';
			}*/
		}
		elseif($channel==40 || $channel==41 || $channel==42)
		{

			require_once(APPPATH.'models/hotusagroup_model.php');
					$hotusagroup = new hotusagroup_model();

			$data	=	$hotusagroup->getReservationDetails($source='list',$id,$channel);
		}
		elseif($channel==8)
		{
			error_reporting(0);

			$reservation_channeldetails = get_data('import_reservation_GTA',array('import_reserv_id '=>($id)))->row_array();

			$data['CC_NAME']			=	"";
			$data['CC_NUMBER']			=	"";
			$data['CC_DATE']			=	"";
			$data['CC_YEAR']			=	"";

			$data['RESER_NUMBER'] 		= 	$reservation_channeldetails['booking_id'];
			$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($reservation_channeldetails['modifieddate']));
			$data['RESER_ID'] 			= 	$reservation_channeldetails['import_reserv_id'];

			$data['curr_cha_currency'] 	= 	$reservation_channeldetails['currencycode'];
			$data['guest_name']			= 	$reservation_channeldetails['leadname'];
			$data['start_date'] 		= 	date('Y/m/d',strtotime($reservation_channeldetails['arrdate']));
			$data['end_date']			=	date('Y/m/d',strtotime($reservation_channeldetails['depdate']));
			$data['reservation_code']	= 	$reservation_channeldetails['booking_ref'];
			$data['ROOMCODE']			=	$reservation_channeldetails['room_id'];
			$data['rateplanid']			=	$reservation_channeldetails['rateplanid'];
			if($reservation_channeldetails['status']=="Confirmed")
			{
				$data['status'] = 'New booking';
			}
			else if($reservation_channeldetails['status']=='Modified')
			{
				$data['status'] = 'Modification';
			}
			else if($reservation_channeldetails['status']=="Cancelled")
			{
				$data['status'] = 'Cancellation';
			}
			$data['start_date']			=	$reservation_channeldetails['arrdate'];
			$data['end_date']			=	$reservation_channeldetails['depdate'];

			$data['CHECKIN']			=	date('Y/m/d',strtotime($reservation_channeldetails['arrdate']));
			$data['CHECKOUT']			=	date('Y/m/d',strtotime($reservation_channeldetails['depdate']));

			$data['nig'] 				=	$reservation_channeldetails['nights'];

			$data['ADULTS']				= 	$reservation_channeldetails['adults'];
			$data['CHILDREN']			= 	$reservation_channeldetails['children'];

			$data['description']		= 	"";
			$data['policy_checin']		= 	"";
			$data['policy_checout']		= 	"";
			$data['CRIB']				=	"";
			$data['subtotal']			= 	$reservation_channeldetails['totalroomcost'];
			$data['CURRENCY']			=	$reservation_channeldetails['currencycode'];

			$data['guest_name'] 		=	$reservation_channeldetails['leadname'];
			$data['email']				=	"";
			$data['street_name'] 		=	"";
			$data['city_name'] 			=	$reservation_channeldetails['city'];
			$data['country'] 			=	"";
			$data['phone'] 				=	"";
			$data['commission']	  		= 	"";
			$data['mealsinc']			= 	"";
			$data['description']		= 	"";
			$data['price'] 				= 	$reservation_channeldetails['totalcost'];
			$data['is_card']            =   0;

			if($reservation_channeldetails['offer'] != "0.00"){
				$data['offer'] = $reservation_channeldetails['offer'];
			}

			$perdayprice = json_decode($reservation_channeldetails['room_costdetils']);

			foreach ($perdayprice as $perdaypri){
				foreach($perdaypri->NettCost as $rate)
				$data['perdayprice'][] = array(
					$perdaypri->date => $rate,
				);
			}
			//print_r($data['perdayprice']);
			//die;
			//if($reservation_channeldetails['CCIDCARDORGANISATION']='0')
			//{
				$data['payment_method'] = 'Cash';
			//}
			//else
			//{
			//	$data['payment_method'] = 'Credit Card';
			//}
		}
		elseif ($channel == 9) {
            error_reporting(0);

            $reservation_channeldetails = get_data('import_reservation_AIRBNB', array(
                'Import_reservation_ID' => ($id)
            ))->row_array();
            //print_r($reservation_channeldetails);
            $data['CC_NAME']   = "";
            $data['CC_NUMBER'] = "";
            $data['CC_DATE']   = "";
            $data['CC_YEAR']   = "";

            $data['RESER_NUMBER'] = $reservation_channeldetails['ResID_Value'];
            list($reserdate)    = explode(' ', $reservation_channeldetails['ImportDate']);
            $data['RESER_DATE']   = date('M d,Y', strtotime($reserdate));
            $data['RESER_ID']     = $reservation_channeldetails['Import_reservation_ID'];

            $data['curr_cha_currency'] = $reservation_channeldetails['Currency'];
            $data['guest_name']        = $reservation_channeldetails['name'];
            $data['start_date']        = date('Y/m/d', strtotime($reservation_channeldetails['arrival']));
            $data['end_date']          = date('Y/m/d', strtotime($reservation_channeldetails['departure']));
            $data['reservation_code']  = $reservation_channeldetails['ResID_Value'];
            $data['ROOMCODE']          = $reservation_channeldetails['RoomTypeCode'];
            $data['rateplanid']        = $reservation_channeldetails['rateplanid'];
            if ($reservation_channeldetails['status'] == "new") {
                $data['status'] = 'New booking';
            } else if ($reservation_channeldetails['status'] == 'Modified') {
                $data['status'] = 'Modification';
            } else if ($reservation_channeldetails['status'] == "Cancelled") {
                $data['status'] = 'Cancellation';
            }
            $data['start_date'] = $reservation_channeldetails['arrival'];
            $data['end_date']   = $reservation_channeldetails['departure'];

            $data['CHECKIN']  = date('Y/m/d', strtotime($reservation_channeldetails['arrival']));
            $data['CHECKOUT'] = date('Y/m/d', strtotime($reservation_channeldetails['departure']));

            $data['nig'] = $reservation_channeldetails['nights'];

            $data['ADULTS']   = $reservation_channeldetails['Adult'];
            $data['CHILDREN'] = $reservation_channeldetails['Child'];

            $data['description']    = "";
            $data['policy_checin']  = "";
            $data['policy_checout'] = "";
            $data['CRIB']           = "";
            $data['subtotal']       = $reservation_channeldetails['AmountAfterTax'];
            $data['CURRENCY']       = $reservation_channeldetails['Currency'];

            $data['guest_name']  = $reservation_channeldetails['name'];
            $data['email']       = "";
            $data['street_name'] = "";
            $data['city_name']   = "";
            $data['country']     = "";
            $data['phone']       = "";
            $data['commission']  = "";
            $data['mealsinc']    = "";
            $data['description'] = "";
            $data['price']       = $reservation_channeldetails['AmountAfterTax'];
            $data['is_card']     = 0;
            //print_r($data['perdayprice']);
            //die;
            //if($reservation_channeldetails['CCIDCARDORGANISATION']='0')
            //{
            $data['payment_method'] = 'Cash';
            //}
            //else
            //{
            //    $data['payment_method'] = 'Credit Card';
            //}
        }
		else if($channel==1)
		{
			$reservation_channeldetails = get_data('import_reservation_EXPEDIA',array('import_reserv_id '=>($id)))->row_array();
			$this->db->where('user_id',current_user_type());
			$this->db->where('hotel_id',hotel_id());
			$this->db->where('roomtype_id',$reservation_channeldetails['roomTypeID']);
			$this->db->where('rate_type_id',$reservation_channeldetails['ratePlanID']);
			$details = $this->db->get("import_mapping")->row();
			if(count($details) == 0)
			{
				$this->db->where('user_id',current_user_type());
				$this->db->where('hotel_id',hotel_id());
				$this->db->where('roomtype_id',$reservation_channeldetails['roomTypeID']);
				$this->db->where('rateplan_id',$reservation_channeldetails['ratePlanID']);
				$details = $this->db->get("import_mapping")->row();
			}

			$roomdetails = getExpediaRoom($reservation_channeldetails['roomTypeID'],$reservation_channeldetails['ratePlanID'],current_user_type(),hotel_id());

			$data['CC_NAME']			=	$reservation_channeldetails['name'];
			$data['CC_NUMBER']			=	$reservation_channeldetails['cardNumber'];
			$data['CC_DATE']			=	substr($reservation_channeldetails['expireDate'], 0,2);
			$data['CC_YEAR']			=	substr($reservation_channeldetails['expireDate'], -2);
			$data['CC_CVC']				=  $reservation_channeldetails['SeriesCode'];

			$data['RESER_NUMBER'] 		= 	$reservation_channeldetails['booking_id'];
			$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($reservation_channeldetails['created_time']));
			$data['RESER_ID'] 			= 	$reservation_channeldetails['import_reserv_id'];
			$data['roomtypeId']         =   $roomdetails['roomtypeId'];
			$data['rateplanid']         =   $roomdetails['rateplanid'];

			$data['curr_cha_currency'] 	= 	$reservation_channeldetails['currency'];
			$data['guest_name']			= 	$reservation_channeldetails['givenName'].' '.$reservation_channeldetails['middleName'].' '.$reservation_channeldetails['surname'];
			$data['start_date'] 		= 	date('Y/m/d',strtotime($reservation_channeldetails['arrival']));
			$data['end_date']			=	date('Y/m/d',strtotime($reservation_channeldetails['departure']));
			$data['channel_room_name']      =  @$details->roomtype_name.'-'.@$details->code.'-'.@$details->distributionModel;
			$data['reservation_code']	= 	@$reservation_channeldetails['booking_id'];
			$data['RoomNumber']	= 	$reservation_channeldetails['RoomNumber'];
			$data['ROOMCODE']			=	'';//$reservation_channeldetails['ROOMCODE'];
			if($reservation_channeldetails['type']=='Book')
			{
				$data['status'] = 'New booking';
			}
			else if($reservation_channeldetails['type']=='Modify')
			{
				$data['status'] = 'Modification';
			}
			else if($reservation_channeldetails['type']=='Cancel')
			{
				$data['status'] = 'Cancellation';
			}
			$data['start_date']			=	$reservation_channeldetails['arrival'];
			$data['end_date']			=	$reservation_channeldetails['departure'];

			$data['CHECKIN']			=	date('Y/m/d',strtotime($reservation_channeldetails['arrival']));
			$data['CHECKOUT']			=	date('Y/m/d',strtotime($reservation_channeldetails['departure']));

			$data['nig'] 				=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);

			$inbwdays = explode(',',$reservation_channeldetails['stayDate']);
			$baseRate = explode(',', $reservation_channeldetails['baseRate']);

			for($i=0; $i<count($inbwdays); $i++){
				if($inbwdays[$i] != ""){
					$data['perdayprice'][] = array(
						$inbwdays[$i] => $baseRate[$i],
					);
				}
			}

			$data['inbwdays']           = $reservation_channeldetails['stayDate'];
			$data['baseRate']           = $reservation_channeldetails['baseRate'];
			$data['tax']                = $reservation_channeldetails['amountOfTaxes'];

			$data['ADULTS']				= 	$reservation_channeldetails['adult'];
			$data['CHILDREN']			= 	$reservation_channeldetails['child'];

			$data['description']		= 	$reservation_channeldetails['Remarks'];
			$data['policy_checin']		= 	'';//$reservation_channeldetails['CORRCHECKIN'];
			$data['policy_checout']		= 	'';//$reservation_channeldetails['CORRCHECKOUT'];
			$data['CRIB']				=	$reservation_channeldetails['child_age'];
			$data['subtotal']			= 	$reservation_channeldetails['amountAfterTaxes'] - $reservation_channeldetails['amountOfTaxes'];
			$data['CURRENCY']			=	$reservation_channeldetails['currency'];

			$data['email']				=	$reservation_channeldetails['Email'];
			$data['street_name'] 		=	$reservation_channeldetails['address'];
			$data['city_name'] 			=	$reservation_channeldetails['city'];
			$data['country'] 			=	$reservation_channeldetails['country'];
			$data['phone'] 				=	$reservation_channeldetails['number'];
			$data['commission']	  		= 	'';//$reservation_channeldetails['COMMISSION'];
			$data['mealsinc']			= 	'';//$reservation_channeldetails['MEALSINC'];
			$data['price'] 				= 	$reservation_channeldetails['amountAfterTaxes'];
			$data['payment_method']		= 	'Cash';
			/*if($reservation_channeldetails['CCIDCARDORGANISATION']='0')
			{
				$data['payment_method'] = 'Cash';
			}
			else
			{
				$data['payment_method'] = 'Credit Card';
			}*/
		}
		else if($channel== 5)
		{
			$reservation_channeldetails = get_data('import_reservation_HOTELBEDS',array('import_reserv_id '=>($id)))->row_array();

			$data['CC_NAME']			=	"";
			$data['CC_NUMBER']			=	"";
			$data['CC_DATE']			=	"";
			$data['CC_YEAR']			=	"";

			$data['RESER_NUMBER'] 		= 	$reservation_channeldetails['RefNumber'];
			$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($reservation_channeldetails['CreationDate']));
			$data['RESER_ID'] 			= 	$reservation_channeldetails['import_reserv_id'];

			$data['curr_cha_currency'] 	= 	$reservation_channeldetails['Currency'];
			$data['guest_name']			= 	$reservation_channeldetails['Customer_Name'];
			$data['booker_name']		= 	$reservation_channeldetails['Holder'];
			$data['start_date'] 		= 	date('Y/m/d',strtotime($reservation_channeldetails['DateFrom']));
			$data['end_date']			=	date('Y/m/d',strtotime($reservation_channeldetails['DateTo']));
			$data['reservation_code']	= 	$reservation_channeldetails['RefNumber'];
			$data['ROOMCODE']			=	"";
			if($reservation_channeldetails['RoomStatus']== "BOOKING")
			{
				$data['status'] = 'New booking';
			}
			else if($reservation_channeldetails['RoomStatus']== "MODIFIED")
			{
				$data['status'] = 'Modification';
			}
			else if($reservation_channeldetails['RoomStatus']== "CANCELED")
			{
				$data['status'] = 'Cancellation';
			}

			$htb_id = $this->db->query("SELECT map_id,roomname, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where contract_name='".$reservation_channeldetails['Contract_Name']."' and contract_code='".$reservation_channeldetails['IncomingOffice']."' and sequence='".$reservation_channeldetails['Contract_Code']."' and user_id='".current_user_type()."' and hotel_id='".hotel_id()."' having roomnames ='".$reservation_channeldetails['Room_code']."' AND charactersticss ='".$reservation_channeldetails['CharacteristicCode']."'");

			if($htb_id->num_rows != 0)
			{
				$roomcode = $htb_id->row()->roomname;
				$htb_id = $htb_id->row()->map_id;
				$htbid = get_data(MAP,array('channel_id'=>$reservation_channeldetails['channel_id'],'import_mapping_id'=>$htb_id));
				if($htbid->num_rows != 0)
				{
					$room_id = $htbid->row()->property_id;
				}
				else
				{
					$room_id = "0";
				}
			}
			else
			{
				$room_id = "0";
				$roomcode = "";
			}
			$totamount = $reservation_channeldetails['TAmount'];
			$Description = $reservation_channeldetails['Description'];
			$adult = $reservation_channeldetails['AdultCount'];
			$child = $reservation_channeldetails['ChildCount'] + $reservation_channeldetails['BabyCount'];
			$checkin = $reservation_channeldetails['DateFrom'];
			$checkout = $reservation_channeldetails['DateTo'];
			$name = $reservation_channeldetails['Customer_Name'];

			if($totamount!='')
			{
				$currency = explode(',', $reservation_channeldetails['Currency']);
				$rateprice = explode(',', $reservation_channeldetails['Rate_DateFrom']);
				$rateendprice = explode(',', $reservation_channeldetails['Rate_DateTo']);
				$priceperdate = explode(',', $reservation_channeldetails['Amount']);

				$total_amount = 0;

				for($i=0; $i<count($rateprice); $i++)
				{
					$nig	=	_datebetween($rateprice[$i],$rateendprice[$i]);
					$originalstartDate = date('M d,Y',strtotime($rateprice[$i]));
					$newstartDate = date("Y/m/d", strtotime($originalstartDate));
					$originalendDate = date('M d,Y',strtotime($rateendprice[$i]));
					$newendDate = date("Y/m/d", strtotime($originalendDate));
					$begin = new DateTime($newstartDate);
					$ends = new DateTime($newendDate);
					$daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
					foreach($daterange as $ran)
					{
						$string = date('Y-m-d',strtotime(str_replace('/','-',$ran->format('M d, Y'))));
						$data['perdayprice'][] = array(
								$string  => $priceperdate[$i]/$nig,
							);
						$total_amount = $total_amount + $priceperdate[$i];
					}
				}
				$data['subtotal']			= 	$totamount;/* *$data['nig']; */
				$data['CURRENCY']			=	$currency[0];
				$data['price'] 				= 	$totamount;
			}
			else
			{
				$data['subtotal']			= 	$Description;
				$data['CURRENCY']			=	'';
				$data['price'] 				= 	$Description;
			}
			$data['start_date']			=	$checkin;
			$data['end_date']			=	$checkout;

			$data['CHECKIN']			=	date('Y/m/d',strtotime($checkin));
			$data['CHECKOUT']			=	date('Y/m/d',strtotime($checkout));

			$data['nig'] 				=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);
			$data['is_card']        	= 	0;
			$data['ADULTS']				= 	$adult;
			$data['room_id']            = 	$room_id;
			$data['CHILDREN']			= 	$child;

			$data['description']		= 	$reservation_channeldetails['Remarks'];
			$data['policy_checin']		= 	$checkin;
			$data['policy_checout']		= 	$checkout;
			$data['CRIB']				=	"";


			$data['guest_name'] 		=	rtrim($name,',');
			$data['email']				=	"";
			$data['street_name'] 		=	"";
			$data['city_name'] 			=	"";
			$data['country'] 			=	$reservation_channeldetails['TTOO_market'];
			$data['phone'] 				=	"";
			$data['commission']	  		= 	"";
			$data['mealsinc']			= 	$reservation_channeldetails['BaseBoardTypeCode'];
			///$data['description']		= 	"";
			$data['price'] 				= 	$totamount;
			$data['payment_method'] 	= 	'Cash';
			$data['Contract_Details'] 	= 	$reservation_channeldetails['Contract_Code'].', '.$reservation_channeldetails['Contract_Name'];
			$data['channel_room_name'] 	= 	$reservation_channeldetails['Characteristic'];
			$data['roomcode'] 			= 	$roomcode;

		}
		else if($channel==2)
		{
			$reservation_channeldetails = get_data('import_reservation_BOOKING_ROOMS',array('room_res_id' => $id))->row_array();

			$booking_table = get_data('import_reservation_BOOKING', array('id'=>$reservation_channeldetails['reservation_id']))->row_array();
			$rate_type = get_data('import_mapping_BOOKING',array('B_room_id'=>$reservation_channeldetails['id'],'B_rate_id'=>$reservation_channeldetails['rate_id']))->row()->rate_name;

				require_once(APPPATH.'controllers/tokenex.php');
					$tokenex = new tokenex();
					$Valor=safe_b64decode($booking_table['cc_number']);

			$data['CC_NAME']			=	safe_b64decode($booking_table['cc_name']);
			$data['CC_NUMBER']			=	(strlen($Valor)>20?$Valor: $tokenex->Detokenizar($Valor));
			$data['CC_DATE']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), 0,2);
			$data['CC_YEAR']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), -2);
			$data['CC_CVC']             =   safe_b64decode($booking_table['cc_cvc']);
			$data['CC_TYPE']            =   safe_b64decode($booking_table['cc_type']);


			$data['RESER_NUMBER'] 		= 	$reservation_channeldetails['reservation_id'];
			$data['RESER_DATE'] 		= 	date('M d,Y',strtotime($booking_table['date']));
			$data['RESER_ID'] 			= 	$reservation_channeldetails['room_res_id'];
			$data['roomtypeId']         =   $reservation_channeldetails['id'];
			$data['rateplanid']         =   $reservation_channeldetails['rate_id'];
			$data['curr_cha_currency'] 	= 	$reservation_channeldetails['currencycode'];
			$data['guest_name']			= 	$reservation_channeldetails['guest_name'];
			$data['booker_name'] 		=	$booking_table['first_name'].' '.$booking_table['last_name'];
			$data['booking_date']       =   $booking_table['date'].'-'.$booking_table['time'];

			$data['channel_room_name']          =   $reservation_channeldetails['name'];
			if($rate_type != ""){
				$data['channel_room_name']  .= "-".$rate_type;
			}
			$data['start_date'] 		= 	date('Y/m/d',strtotime($reservation_channeldetails['arrival_date']));
			$data['end_date']			=	date('Y/m/d',strtotime($reservation_channeldetails['departure_date']));
			$data['reservation_code']	= 	$reservation_channeldetails['roomreservation_id'];
			$data['RoomNumber']	= 	$reservation_channeldetails['RoomNumber'];
			$data['reservationid']		= 	$reservation_channeldetails['reservation_id'];
			$data['ROOMCODE']			=	'';//$reservation_channeldetails['ROOMCODE'];
			if($booking_table['status']=='new')
			{
				$data['status'] = 'New booking';
			}
			else if($booking_table['status']=='modified')
			{
				$data['status'] = 'Modification';
			}
			else if($booking_table['status']=='cancelled')
			{
				$data['status'] = 'Cancellation';
			}

			else if($booking_table['status']=='Checkin')
			{
				$data['status'] = 'Check In';
			}
			else if($booking_table['status']=='Checkout')
			{
				$data['status'] = 'Check Out';
			}
			$data['start_date']			=	$reservation_channeldetails['arrival_date'];
			$data['end_date']			=	$reservation_channeldetails['departure_date'];

			$data['CHECKIN']			=	date('Y/m/d',strtotime($reservation_channeldetails['arrival_date']));
			$data['CHECKOUT']			=	date('Y/m/d',strtotime($reservation_channeldetails['departure_date']));

			$data['nig'] 				=	_datebetween($data['CHECKIN'],$data['CHECKOUT']);

			$inbwdays = explode('##',$reservation_channeldetails['day_price_detailss']);
			$price = 0;
			/*for($i=0; $i<count($inbwdays); $i++){
				if($inbwdays[$i] != ""){
					$baseRate = explode('~', $inbwdays[$i]);
					$len = count($baseRate);
					$price = $price + $baseRate[$len-1];
					$data['perdayprice'][] = array(
						$baseRate[0] => $baseRate[$len-1],
					);
				}
			}*/
			$data['promocode'] = "No";
			for($i=0; $i<count($inbwdays); $i++){
				if($inbwdays[$i] != ""){
					$baseRate = explode('~', $inbwdays[$i]);
					$price = $price + end($baseRate);
					$searchword = 'rewritten_from_name=';
					$matches = array_values(array_filter($baseRate, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }));
					if(count($matches)=='0')
					{
						$data['perdayprice'][] = array(
						current(str_replace('date=','',$baseRate)) => end($baseRate),
						);
					}
					else
					{
						$data['promocode'] = "Yes";
						$data['perdayprice'][] = array(
						current(str_replace('date=','',$baseRate)) => str_replace('rewritten_from_name=','',$matches[0]).' '.end($baseRate),
						);
					}
				}
			}
			$data['inbwdays']           = $reservation_channeldetails['arrival_date'];
			$data['baseRate']           = $reservation_channeldetails['totalprice'];/*
			$data['tax']                = $reservation_channeldetails['commissionamount'];*/
			$addon = get_data("import_reservation_BOOKING_ADDON", array('roomreservation_id' => $reservation_channeldetails['roomreservation_id']));
			$data['addon'] = 0;
			if($addon->num_rows != 0)
			{
				$addon = $addon->row_array();
				$addons = json_decode($addon['addons_values']);

				foreach($addons as $key => $val)
				{
					if($key == "totalprice")
					{
						if(is_array($val))
						{
							foreach($val as $aprice)
							{
								$data['addon'] = $data['addon'] + $aprice;
							}
						}
						else
						{
							$data['addon'] = $data['addon'] + $val;
						}
					}
				}
			}
			$data['ADULTS']				=	$reservation_channeldetails['numberofguests'];
			$data['CHILDREN']			= 	'';

			$data['description']		= 	$booking_table['remarks'];
			$data['policy_checin']		= 	'';//$reservation_channeldetails['CORRCHECKIN'];
			$data['policy_checout']		= 	'';//$reservation_channeldetails['CORRCHECKOUT'];
			$data['CRIB']				=	'';
			$data['subtotal']			= 	$price;//$reservation_channeldetails['totalprice'] - $data['addon'];
			$data['grandtotal']			= 	$reservation_channeldetails['totalprice'];
			$data['CURRENCY']			=	$reservation_channeldetails['currencycode'];

			$data['guest_name'] 		=	$booking_table['first_name'].' '.$booking_table['last_name'];
			$data['email']				=	$booking_table['email'];
			$data['street_name'] 		=	$booking_table['address'];
			$data['city_name'] 			=	$booking_table['city'];
			$data['country'] 			=	$booking_table['countrycode'];
			$data['phone'] 				=	$booking_table['telephone'];
			$data['commission']	  		= 	$reservation_channeldetails['commissionamount'];
			$data['mealsinc']			= 	$reservation_channeldetails['meal_plan'];
			$data['description']		= 	$booking_table['remarks'];
			$data['price'] 				= 	$price;

			if($booking_table['cc_name'] == '')
			{
				$data['payment_method'] = 'Cash';
			}
			else
			{
				$data['payment_method'] = 'Credit Card';
			}
		}
		else if($channel==17)
		{
			$data	=	$this->bnow_model->getReservationDetails($source='list',$id);
		}
		else if($channel==15)
		{
			$data	=	$this->travel_model->getReservationDetails($source='list',$id);
		}

		else if($channel==36)
		{	$this->load->model("despegar_model");
			$data	=	$this->despegar_model->getReservationDetails($source='list',$id);
		}





		$data['bill'] = $this->reservation_model->billing_details();

		$data['extra'] = $this->reservation_model->get_extras($channel,$id);

		$data['extra_count'] = $this->reservation_model->extra_count($channel,$id);

		$data['invoice_count'] = $this->reservation_model->invoice_count($channel,$id);

		$data['invoice'] = $this->reservation_model->get_invoices($channel,$id);

		$data['add_pay_count'] = $this->reservation_model->payment_paid_count($channel,$id);

		$data['add_pay'] = $this->reservation_model->get_payment_paid($channel,$id);

		$data['welcome'] = get_data(WE,array('mail_type'=>1,'channel_id'=>$channel,'reservation_id'=>$id))->row_array();

		$data['remainder'] = get_data(WE,array('mail_type'=>2,'channel_id'=>$channel,'reservation_id'=>$id))->row_array();

		if($this->input->post('save'))
		{
			$reser_id = $this->input->post('reserve_id');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$result = $this->reservation_model->edit_reservation($reser_id);
			}
			else if(user_type()=='2')
			{
				if(in_array('2',user_edit()))
				{
					$result = $this->reservation_model->edit_reservation($reser_id);
				}
				else
				{
					redirect(base_url());
				}
			}
			if($result)
			{
				$this->session->set_flashdata('success', 'successfully updated.');

				redirect('reservation/reservation_order/'.insep_encode($reser_id),'refresh');

			}
			else
			{
				$this->session->set_flashdata('error', 'Not Updated.');

				redirect('reservation/reservation_order/'.insep_encode($reser_id),'refresh');
			}
		}
		else
		{
			$data['page_heading'] = 'Reservation Channel';

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

			$data= array_merge($user_details,$data);

			$data['reser_id'] = insep_encode($id);

			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$this->views('channel/reservation_channel',$data);
			}
			elseif(user_type()=='2')
			{
				$data['user_view']=user_view();
				$data['user_edit']=user_edit();
				if(in_array('2',user_view()))
				{
					$this->views('channel/reservation_channel',$data);
				}
				else
				{
					redirect(base_url());
				}
			}
		}
	}

	// reservation print...
	function reservation_print($channel_id,$id)
	{
		$rese_id= insep_decode($id);
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(!hotel_id())
		{
			redirect(base_url());
		}
		$data['page_heading'] = 'Print Voucher';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$curr_cha_id = unsecure($channel_id);
		$data['curr_cha_id'] = $curr_cha_id;
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			if($curr_cha_id==0)
			{
				$print_details = $this->reservation_model->get_all_room_list($rese_id);
				if($print_details)
				{
					$data['reservation_code'] 	= $print_details->reservation_code;
					$data['start_date']			= $print_details->start_date;
					$data['end_date']			= $print_details->end_date;
					$data['num_nights']			= $print_details->num_nights;
					// PMS Reservation Showing 22/11/2016
					/* if($print_details->pms != 1){
						$data['price']				= $print_details->price;
					}else{ */
						$data['price']				= $print_details->price / $print_details->num_nights;
					/* } */
					// PMS Reservation Showing 22/11/2016
					$data['reservation_id'] 	= $print_details->reservation_id;
					$data['guest_name'] 		= $print_details->guest_name;
					$data['email'] 				= $print_details->email;
					$data['street_name'] 		= $print_details->street_name;
					$data['city_name'] 			= $print_details->city_name;
					$data['country'] 			= $print_details->country;
					if($print_details->status==1)
					{
						$data['status'] 		= 'Confirmed';
					}
					else
					{
						$data['status'] 		= 'Reserved';
					}
					$data['booking_date']		= $print_details->booking_date;
					$data['room_id']			= $print_details->room_id;
					if($print_details->payment_method==1)
					{
						$data['payment_method']		= 'Cash';
					}
					else
					{
						$data['payment_method']		= 'Checque';
					}
					$data['members_count']			= $print_details->members_count;
					$data['children']				= $print_details->children;
					$data['description']			= $print_details->description;

				}
				else
				{
					redirect(base_url());
				}
			}
			elseif($curr_cha_id==11)
			{
				$print_details = get_data(REC_RESERV,array('import_reserv_id'=>$rese_id))->row_array();
				if(count($print_details)!=0)
				{
					$data['reservation_code'] 	= $print_details['IDRSV'];
					$data['start_date']			= $print_details['CHECKIN'];
					$data['end_date']			= $print_details['CHECKOUT'];
					$checkin=date('Y/m/d',strtotime($print_details['CHECKIN']));
					$checkout=date('Y/m/d',strtotime($print_details['CHECKOUT']));
					$nig =_datebetween($checkin,$checkout);
					$data['num_nights'] = $nig;
					$data['currency']	= $print_details['CURRENCY'];
					$data['price'] 		= $print_details['REVENUE'];

					$data['reservation_id'] = $print_details['import_reserv_id'];
					$data['guest_name'] = $print_details['FIRSTNAME'];
					$data['email'] = $print_details['EMAIL'];
					$data['street_name'] = $print_details['ADDRESS1'];
					$data['city_name'] = $print_details['CITY'];
					$data['country'] = $print_details['COUNTRY'];
					if($print_details['STATUS']==11)
					{
						$data['status'] = 'New booking';
					}
					else if($print_details['STATUS']==12)
					{
						$data['status'] = 'Modification';
					}
					else if($print_details['STATUS']==13)
					{
						$data['status'] = 'Cancellation';
					}
					$data['booking_date'] 	= $print_details['RSVCREATE'];
					$data['commission']	  	= $print_details['COMMISSION'];
					$data['mealsinc']		= $print_details['MEALSINC'];
					if($print_details['CCIDCARDORGANISATION']='0')
					{
						$data['payment_method'] = 'Cash';
					}
					else
					{
						$data['payment_method'] = 'Credit Card';
					}
					$data['members_count']		= $print_details['ADULTS'];
					$data['description']		= $print_details['REMARK'];
					$data['policy_checin']		= $print_details['CORRCHECKIN'];
					$data['policy_checout']		= $print_details['CORRCHECKOUT'];
				}
				else
				{
					redirect(base_url());
				}
			}elseif($curr_cha_id==1){
				$print_details = get_data('import_reservation_EXPEDIA',array('import_reserv_id'=>$rese_id))->row_array();
				if(count($print_details)!=0)
				{
					$this->db->where('user_id',current_user_type());
					$this->db->where('hotel_id',hotel_id());
					$this->db->where('roomtype_id',$print_details['roomTypeID']);
					$this->db->where('rate_type_id',$print_details['ratePlanID']);
					$details = $this->db->get("import_mapping")->row();
					if(count($details) == 0)
					{
						$this->db->where('user_id',current_user_type());
						$this->db->where('hotel_id',hotel_id());
						$this->db->where('roomtype_id',$print_details['roomTypeID']);
						$this->db->where('rateplan_id',$print_details['ratePlanID']);
						$details = $this->db->get("import_mapping")->row();
					}
					$data['channel_room_name']      =(isset($details->roomtype_name)?$details->roomtype_name:"").'-'.(isset($details->code)?$details->code:"").'-'.(isset($details->distributionModel)?$details->distributionModel:"");

					$data['reservation_code'] 	= $print_details['booking_id'];
					$data['start_date']			= $print_details['arrival'];
					$data['end_date']			= $print_details['departure'];
					$checkin=date('Y/m/d',strtotime($print_details['arrival']));
					$checkout=date('Y/m/d',strtotime($print_details['departure']));
					$nig =_datebetween($checkin,$checkout);
					$data['num_nights'] = $nig;
					$data['currency']	= $print_details['currency'];
					$data['price'] 		= $print_details['amountAfterTaxes'];

					$data['reservation_id'] = $print_details['import_reserv_id'];
					$data['guest_name'] = $print_details['givenName'].' '.$print_details['middleName'].' '.$print_details['surname'];

					$data['email'] = $print_details['Email'];
					$data['street_name'] = $print_details['address'];
					$data['city_name'] = $print_details['city'];
					$data['country'] = $print_details['country'];

					$inbwdays = explode(',',$print_details['stayDate']);
					$baseRate = explode(',', $print_details['baseRate']);

					for($i=0; $i<count($inbwdays); $i++){
						if($inbwdays[$i] != ""){
							$data['perdayprice'][] = array(
								$inbwdays[$i] => $baseRate[$i],
							);
						}
					}
					if($print_details['type']== "Book")
					{
						$data['status'] = 'New booking';
					}
					else if($print_details['type']== "Modify")
					{
						$data['status'] = 'Modification';
					}
					else if($print_details['type']== "Cancel")
					{
						$data['status'] = 'Cancellation';
					}
					$data['booking_date'] 	= $print_details['created_time'];
					$data['commission']	  	= "NONE";
					$data['mealsinc']		= "";
					if($print_details['cardCode'] == "")
					{
						$data['payment_method'] = 'Cash';
					}
					else
					{
						$data['payment_method'] = 'Credit Card';
					}
					$data['members_count']		= $print_details['adult'];
					$data['description']		= "";
					$data['cancel_description'] = "";
					$data['meal_name'] = "None";
					$data['policy_checin']		= $print_details['arrival'];
					$data['policy_checout']		= $print_details['departure'];
				}
				else
				{
					redirect(base_url());
				}
			}elseif($curr_cha_id==8){
				$print_details = get_data('import_reservation_GTA',array('import_reserv_id'=>$rese_id))->row_array();
				if(count($print_details)!=0)
				{
					$data['reservation_code'] 	= $print_details['booking_id'];
					$data['start_date']			= $print_details['arrdate'];
					$data['end_date']			= $print_details['depdate'];
					$checkin=date('Y/m/d',strtotime($print_details['arrdate']));
					$checkout=date('Y/m/d',strtotime($print_details['depdate']));
					$nig =_datebetween($checkin,$checkout);
					$data['num_nights'] = $nig;
					$data['currency']	= $print_details['currencycode'];
					$data['price'] 		= $print_details['totalcost'];

					$data['reservation_id'] = $print_details['import_reserv_id'];
					$data['guest_name'] = $print_details['leadname'];
					$data['email'] = "";
					$data['street_name'] = "";
					$data['city_name'] = $print_details['city'];
					$data['country'] = "";
					if($print_details['offer'] != "0.00"){
						$data['offer'] = $print_details['offer'];
					}

					$perdayprice = json_decode($print_details['room_costdetils']);

					foreach ($perdayprice as $perdaypri){
						foreach($perdaypri->NettCost as $rate)
						$data['perdayprice'][] = array(
							$perdaypri->date => $rate,
						);
					}

					if($print_details['status']== "Confirmed")
					{
						$data['status'] = 'New booking';
					}
					else if($print_details['status']== "Canceled")
					{
						$data['status'] = 'Cancellation';
					}
					else
					{
						$data['status'] = 'Modification';
					}
					$data['booking_date'] 	= $print_details['modifieddate'];
					$data['commission']	  	= "NONE";
					$data['mealsinc']		= "";
					$data['payment_method'] = 'Cash';
					$data['members_count']		= $print_details['adults'];
					$data['description']		= "";
					$data['cancel_description'] = "";
					$data['meal_name'] = "None";
					$data['policy_checin']		= $print_details['arrdate'];
					$data['policy_checout']		= $print_details['depdate'];
				}
				else
				{
					redirect(base_url());
				}
			}elseif($curr_cha_id==5){

				$reservation_details = get_data('import_reservation_HOTELBEDS',array('import_reserv_id'=>$rese_id))->row_array();

				$totamount 	= $reservation_details['TAmount'];
				$Description 	= $reservation_details['Description'];
				$adult 		= $reservation_details['AdultCount'];
				$child 		= $reservation_details['ChildCount'] + $reservation_details['BabyCount'];
				$checkin 	= $reservation_details['DateFrom'];
				$checkout 	= $reservation_details['DateTo'];
				$currency 	= explode(',', $reservation_details['Currency']);
				$name 		= $reservation_details['Customer_Name'];
				if(count($reservation_details)!=0)
				{
					$data['reservation_code'] 	= $reservation_details['echoToken'];
					$data['start_date']			= $checkin;
					$data['end_date']			= $checkout;
					$checkin=date('Y/m/d',strtotime($checkin));
					$checkout=date('Y/m/d',strtotime($checkout));
					$nig =_datebetween($checkin,$checkout);
					$data['num_nights'] = $nig;
					if($totamount!='')
					{
						$rateprice = explode(',', $reservation_details['Rate_DateFrom']);
						$rateendprice = explode(',', $reservation_details['Rate_DateTo']);
						$priceperdate = explode(',', $reservation_details['Amount']);

						$total_amount = 0;

						for($i=0; $i<count($rateprice); $i++)
						{
							$nig	=	_datebetween($rateprice[$i],$rateendprice[$i]);
							$originalstartDate = date('M d,Y',strtotime($rateprice[$i]));
							$newstartDate = date("Y/m/d", strtotime($originalstartDate));
							$originalendDate = date('M d,Y',strtotime($rateendprice[$i]));
							$newendDate = date("Y/m/d", strtotime($originalendDate));
							$begin = new DateTime($newstartDate);
							$ends = new DateTime($newendDate);
							$daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
							foreach($daterange as $ran)
							{
								$string = date('Y-m-d',strtotime(str_replace('/','-',$ran->format('M d, Y'))));
								$data['perdayprice'][] = array(
										$string  => $priceperdate[$i]/$nig,
									);
								$total_amount = $total_amount + $priceperdate[$i];
							}
						}
						$data['currency']	= $currency[0];
						$data['price'] 		= $totamount;
					}
					else
					{
						$data['price'] 		= $Description;
						$data['currency']	= '';
					}

					$data['reservation_id'] = $reservation_details['import_reserv_id'];
					$data['guest_name'] = $name;
					$data['email'] = "";
					$data['street_name'] = "";
					$data['city_name'] = "";
					$data['country'] = "";

					if($reservation_details['Status']== "BOOKING")
					{
						$data['status'] = 'New booking';
					}
					else if($reservation_details['Status']== "MODIFIED")
					{
						$data['status'] = 'Modification';
					}
					else if($reservation_details['Status']== "CANCELED]")
					{
						$data['status'] = 'Cancellation';
					}
					$data['booking_date'] 	= $reservation_details['CreationDate'];
					$data['commission']	  	= "NONE";
					$data['mealsinc']		= "";
					$data['payment_method'] = 'Cash';

					$data['members_count']		= $adult;
					$data['description']		= $reservation_details['Remarks'];
					//$data['description']		= "";
					$data['cancel_description'] = "";
					$data['meal_name'] = "None";
					$data['policy_checin']		= $checkin;
					$data['policy_checout']		= $checkout;
				}
				else
				{
					redirect(base_url());
				}
			}
			elseif($curr_cha_id==2)
			{
				$print_details = get_data('import_reservation_BOOKING_ROOMS',array('room_res_id'=>$rese_id))->row_array();
				$booking_details = get_data('import_reservation_BOOKING',array('id' => $print_details['reservation_id']))->row_array();
				$rate_type = get_data('import_mapping_BOOKING',array('B_room_id'=>$print_details['id'],'B_rate_id'=>$print_details['rate_id']))->row()->rate_name;
				if(count($print_details)!=0)
				{
					$data['reservation_code'] 	= $print_details['roomreservation_id'];
					$data['reser_id'] = $print_details['reservation_id'];
					$data['start_date']			= $print_details['arrival_date'];
					$data['end_date']			= $print_details['departure_date'];
					$checkin=date('Y/m/d',strtotime($print_details['arrival_date']));
					$checkout=date('Y/m/d',strtotime($print_details['departure_date']));
					$nig =_datebetween($checkin,$checkout);
					$data['num_nights'] = 1;
					$data['currency']	= $print_details['currencycode'];
					$data['price'] 		= $print_details['totalprice'];
					$data['channel_room_name']  =   $print_details['name'];
					if($rate_type != ""){
						$data['channel_room_name'] .= "-".$rate_type;
					}
					$data['adults']= $print_details['numberofguests'];
					$data['children'] = $print_details['max_children'];
					$data['reservation_id'] = $print_details['room_res_id'];
					$data['guest_name'] = $print_details['guest_name'];
					$data['email'] = $booking_details['email'];
					$data['street_name'] = $booking_details['address'];
					$data['city_name'] = $booking_details['city'];
					$data['country'] = $booking_details['countrycode'];
					$data['flags'] = $booking_details['flags'];
					$data['smoke'] = $print_details['smoking'];
					$data['info'] = $print_details['info'];

					$inbwdays = explode('##',$print_details['day_price_detailss']);

					/* for($i=0; $i<count($inbwdays); $i++){
						if($inbwdays[$i] != ""){
							$baseRate = explode('~', $inbwdays[$i]);
							$len = count($baseRate);
							$price = $price + $baseRate[$len-1];
							$data['perdayprice'][] = array(
								$baseRate[0] => $baseRate[$len-1],
							);
						}
					} */
					$data['promocode'] = "No";
					for($i=0; $i<count($inbwdays); $i++){
						if($inbwdays[$i] != ""){
							$baseRate = explode('~', $inbwdays[$i]);
							$searchword = 'rewritten_from_name=';
							$matches = array_values(array_filter($baseRate, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }));
							if(count($matches)=='0')
							{
								$data['perdayprice'][] = array(
								current(str_replace('date=','',$baseRate)) => end($baseRate),
								);
							}
							else
							{
								$data['promocode'] = "Yes";
								$data['perdayprice'][] = array(
								current(str_replace('date=','',$baseRate)) => str_replace('rewritten_from_name=','',$matches[0]).' '.end($baseRate),
								);
							}
						}
					}

					if($booking_details['status']== "new")
					{
						$data['status'] = 'New booking';
					}
					else if($booking_details['status']== "modified")
					{
						$data['status'] = 'Modification';
					}
					else if($booking_details['status']== "cancelled")
					{
						$data['status'] = 'Cancellation';
					}
					$data['booking_date'] 	= $booking_details['date'];
					$data['booking_time'] 	= $booking_details['time'];
					$data['commission']	  	= $print_details['commissionamount'];
					$data['mealsinc']		= $print_details['meal_plan'];
					if($booking_details['cc_name'] == "")
					{
						$data['payment_method'] = 'Cash';
					}
					else
					{
						$data['payment_method'] = 'Credit Card';
					}
					$data['members_count']		= $print_details['numberofguests'];
					$data['description']		= $booking_details['remarks'];
					$data['cancel_description'] = "";
					$data['meal_name'] = $print_details['meal_plan'];
					if($booking_details['cc_cvc'] != ""){
						$data['cvv'] = safe_b64decode($booking_details['cc_cvc']);
					}
					$data['policy_checin']		= $print_details['arrival_date'];
					$data['policy_checout']		= $print_details['departure_date'];
					$addon = get_data("import_reservation_BOOKING_ADDON", array('roomreservation_id' => $print_details['roomreservation_id']));
					if($addon->num_rows != 0){
						$addon = $addon->row_array();
						$addons = json_decode($addon['addons_values']);

						foreach($addons as $key => $value){
							$data['keylength'] = count($value);
							$data['extradetails'][$key] = $value;
						}
					}
				}
				else
				{
					redirect(base_url());
				}
			}
			else if($curr_cha_id==17)
			{
				$print_details =	$this->bnow_model->getReservationDetails($source='print',$rese_id);
				if($data)
				{
					$data					=	$print_details;
				}
				else
				{
					redirect(base_url());
				}
			}
			else if($curr_cha_id==15)
			{
				$print_details =	$this->travel_model->getReservationDetails($source='print',$rese_id);
				if($data)
				{
					$data					=	$print_details;
				}
				else
				{
					redirect(base_url());
				}
			}
			else if($curr_cha_id==36)
			{	$this->load->model("despegar_model");
				$print_details =	$this->despegar_model->getReservationDetails($source='print',$rese_id);
				if($data)
				{
					$data					=	$print_details;
				}
				else
				{
					redirect(base_url());
				}
			}


		}
		else
		{
			redirect(base_url());
		}
		$this->load->view('channel/reservation_print',$data);
	}

	// reservation adjustment...
	 function reservation_adjustments($id){
			if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
			if($this->input->post('save')){
				$reser_id = insep_decode($id);
				$data['page_heading'] = 'ReservationOrder';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$result = $this->reservation_model->add_adjustments();
				if($result){
					$this->session->set_flashdata('success','Adjustments added successfully');
					redirect('reservation/reservation_adjustments/'.($id),'refresh');
							}
				else{
				$this->session->set_flashdata('error','Error occured while adding adjustments');
				redirect('reservation/reservation_adjustments/'.($id),'refresh');
					}
			$this->views('channel/reservation_order',$data);
			}
			else{
				$data['page_heading'] = 'ReservationOrder';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$this->views('channel/reservation_order',$data);
				}
	 }

	 // Edit adjustments...
	 function edit_adjustments($id){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		 if($this->input->post('save')){
			$update_id = insep_decode($this->input->post('adjust_id'));
			$data['page_heading'] = 'ReservationOrder';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$result = $this->reservation_model->edit_adjustments($update_id);
			// print_r($result);die;
			if($result){
				$this->session->set_flashdata('success','Adjustments updated successfully');
				redirect('reservation/reservation_order/'.($id),'refresh');
					   }
			else{
				$this->session->set_flashdata('error','Error occured while updating adjustments');
				redirect('reservation/reservation_order/'.($id),'refresh');
			    }
			$this->views('channel/reservation_order',$data);
			}
			else{
				$data['page_heading'] = 'ReservationOrder';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$this->views('channel/reservation_order',$data);
			}
	 }


	  // get adjustments...

	 function getadjustment(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		 // echo 'sdsdsd';die;
		if($this->input->post('adjust_id')!=''){
		$adjust_id = ($this->input->post('adjust_id'));
		$data['page_heading'] = 'ReservationOrder';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->reservation_model->get_adjustments($adjust_id);
		$this->load->view('channel/edit_adjust',$data);
		}else{
			$data['page_heading'] = 'ReservationOrder';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/reservation_order',$data);
		}
	 }
	 // delete adjustments...
	 function delete_adjustments($id=null){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('delete_reserve')){
		$del_id = $this->input->post('delete_reserve');
		$des = $this->input->post('des');
		$res = $this->input->post('res');
		$data['page_heading'] = 'Reservation Order';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$result = $this->reservation_model->delete_adjustment($del_id);
		if($result){
			//$this->session->set_flashdata('success','Adjustments deleted successfully');
			//redirect('reservation/reservation_order/'.insep_encode($id),'refresh');
		}else{
			//$this->session->set_flashdata('error','Error occured while delete your adjustments');
			//redirect('reservation/reservation_order/'.insep_encode($id),'refresh');
		}
		$this->views('channel/reservation_order',$data);
	 }else{
				$data['page_heading'] = 'ReservationOrder';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$this->views('channel/reservation_order',$data);
	 }
	}

	// payment list ...
	function payment_list()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$this->views('channel/payment_list',$data);
	    }
	    else if(user_type()=='2')
	    {
	    		if(in_array('2', user_view()))
			{
				$this->views('channel/payment_list',$data);
	    	}
	    	else
	    	{
	    		redirect(base_url());
	    	}
	     }
	}
	// bank tranfer...
	function payment_bank(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->security->xss_clean($this->input->post('save'))){
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$result = $this->reservation_model->add_bankdetails();
			if($result){
				$this->session->set_flashdata('success','Bank Details added successfully');
				redirect('reservation/payment_bank','refresh');
			}else{
				$this->session->set_flashdata('error','Error Occured while adding your bank details ');
				redirect('reservation/payment_bank','refresh');
			}
			$this->views('channel/banktransfer',$data);
		}
		else{
				$data['page_heading'] = 'PaymentList';
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data= array_merge($user_details,$data);
				$this->views('channel/banktransfer',$data);
			}
	}

	// edit bank details...
	function edit_bankdetails(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('bank_id')!=''){
		$bank_id = $this->input->post('bank_id');
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->reservation_model->get_bankdetails($bank_id);
		$this->load->view('channel/bank_edit',$data);
		}else{
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/banktransfer',$data);
		}
	}

	function edit_bankdetailss(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('save')){
		$bank_id = $this->input->post('bank_id');
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
		   $result = $this->reservation_model->edit_bankdetails($bank_id);
	    }
	    else if(user_type()=='2')
	    {
	    	if(in_array('4', user_edit()))
	    	{
	          $result = $this->reservation_model->edit_bankdetails($bank_id);
	        }
	        else
	        {
	        	redirect(base_url());
	        }
	    }
		if($result){
			$this->session->set_flashdata('success','Bank Details Updated Successfully');
			redirect('reservation/payment_bank','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while updating your bank details');
			redirect('reservation/payment_bank','refresh');
		}
		$this->views('channel/banktransfer',$data);
		}else{
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/banktransfer',$data);
		}
	}

	// delete bank details...
	function delete_bankinfo(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('del_bank')){
			$del = $this->input->post('del_bank');
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
			   $result = $this->reservation_model->delete_bank_info($del);
			}
			else if(user_type()=='2')
			{
				if(in_array('4', user_edit()))
				{
				  $result = $this->reservation_model->delete_bank_info($del);
				}
				else
				{
					redirect(base_url());
				}
			}
			if($result){
				$this->session->set_flashdata('success','Bank Information Deleted successfully');
				redirect('reservation/payment_bank','refresh');
			}else{
				$this->session->set_flashdata('error','Error Occured while delete bank information');
				redirect('reservation/payment_bank','refresh');
			}
			$this->views('channel/banktransfer',$data);
		 }else{
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/banktransfer',$data);
		}
	}


	// policies...
	function payment_policy(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'PaymentPolicy';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$this->views('channel/policies',$data);
	}

	// tax categories...
	function tax_categories(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'TaxCategory';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$this->views('channel/tax_categories',$data);
	}

	// bank list page...
	function get_bank_details(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('save')){
		$data['page_heading'] = 'Bank Details';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
		  $result = $this->reservation_model->get_bankdetails();
		  $result = $this->reservation_model->add_pay_name();
	    }
	    else if(user_type()=='2')
	    {
	      if(in_array('4', user_edit()))
	      {
	      $result = $this->reservation_model->get_bankdetails();
		  $result = $this->reservation_model->add_pay_name();
		  }
	    }
		if($result){
			$this->session->set_flashdata('success','Bank details updated successfully');
			redirect('reservation/payment_bank','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while updating bank details');
			redirect('reservation/payment_bank','refresh');
		}
		$this->views('channel/banktransfer',$data);
		}
		else
		{
			extract($this->input->post());

			$bank_details	=	get_data(BANK,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'bank_id'=>$bank_type))->row_array();

			if(count($bank_details)!=0)
			{
				$data['result'] = '1';
				$data['content'] = $bank_details;
			}
			else
			{
				$data['result'] = '0';
			}
			echo json_encode($data);
		}
	}

  // bank status...

function bank_status()
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

		if($this->security->xss_clean($status_method)=='active')
		{
			$udata['status'] = '1';
		}
		elseif($this->security->xss_clean($status_method)=='passive')
		{
			$udata['status'] = '0';
		}
		if($this->security->xss_clean($status_type)=='banktransfer')
		{
			if(update_data('bank_info',$udata,array('user_id'=>current_user_type())))
			{
				$bank_det = get_data('bank_info',array('user_id'=>current_user_type()))->row();
				if($status_method=='active')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="bank_active" id="bank_active" type="banktransfer" method="passive" data-id="<?php echo $bank_det->bank_info_id;?>">
				<input type="hidden" id="bank_current_status" value="<?php echo $bank_det->status?>" />
				<div class="onoffswitch-wrap ">
				<div class="onoffswitch">
				<input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
				<label for="active-channel" class="onoffswitch-label"></label>
				</div>
				<label class="switch-label" for="active-channel">Active</label>
				</div>
				</a>
				<?php
				}
				elseif($status_method=='passive')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_passive" id="bank_active" type="banktransfer" method="active" data-id="<?php echo $bank_det->bank_info_id;?>">
        		<input type="hidden" id="bank_current_status" value="<?php echo $bank_det->status?>" />
				<div class="onoffswitch-wrap switch-label-deactivate">
          		<div class="onoffswitch">
			    <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				<label for="active-channel" class="onoffswitch-label"></label>
		        </div>
         		<label class="switch-label" for="active-channel">Passive</label>
        		</div>
				</a>
				<?php
				}

			}
		}
	}

	// get payment details...
	function get_payment_method(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('provider')!=''){
			$data['page_heading'] = 'PaymentList';
				$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
				$data= array_merge($user_details,$data);
			if($this->input->post('provider')==2)
			{
				$hotel_id = $this->session->userdata('ch_hotel_id');
				$data['page_view']  = 'add';
				$bdata['pay_method_name'] = $this->input->post('method_name');
				$bdata['user_id']	= current_user_type();
				$bdata['hotel_id']	= $hotel_id;
				if(insert_data('paypal_details',$bdata))
				{
					$bank_id = $this->db->insert_id();
					$bank_details = get_data('paypal_details',array('user_id'=>current_user_type()))->row_array();

					$paypal_main = get_data('payment_list',array('pay_id'=>($this->input->post('provider'))))->row()->user_id;
					$udata['user_id'] = $paypal_main.','.hotel_id();
					update_data('payment_list',$udata,array('pay_id'=>($this->input->post('provider'))));
					redirect('reservation/edit_paypal/','refresh');
				}
				//$result = $this->reservation_model->get_payment();
				/*$data = array_merge($data,$bank_details);
				$this->views('channel/payment_method',$data);*/
			}else if($this->input->post('provider')==1)
			{
				$hotel_id = $this->session->userdata('ch_hotel_id');
				$bdata['user_id']	= current_user_type();
				$bdata['hotel_id']	= $hotel_id;
				$bdata['pay_id']    = $this->input->post('provider');
				$bdata['user_name'] = $this->security->xss_clean($this->input->post('method_name'));
				if(insert_data('bank_info',$bdata))
				{
					$bank_id = ($this->db->insert_id());

					/*$bank_details = get_data('bank_info',array('user_id'=>user_id()))->row_array();*/

					$paypal_main = get_data('payment_list',array('pay_id'=>($this->input->post('provider'))))->row()->user_id;

					$udata['user_id'] = $paypal_main.','.hotel_id();

					update_data('payment_list',$udata,array('pay_id'=>($this->input->post('provider'))));

					redirect('reservation/view_bank_transfer/','refresh');
				}
				/*$data = array_merge($data,$bank_details);
				$this->views('channel/banktransfer',$data);*/
			}
		}
		else
		{
			redirect('reservation/payment_list','refresh');

		}
	}
	function view_bank_transfer($bank_id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['view'] = 'view';
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
		$data= array_merge($user_details,$data);

		$bank_details = get_data('bank_info',array('user_id'=>current_user_type()))->row_array();
		$data = array_merge($data,$bank_details);
		$this->views('channel/banktransfer',$data);

	}

	function view_paypal_details()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		$data['page_heading'] = 'PaymentList';
		$data['page_view']  = 'edit';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data['pay'] = get_data('paypal_details',array('user_id'=>current_user_type()))->row();
		$data = array_merge($data,$user_details);
		$this->views('channel/payment_method',$data);
	}
	function add_paypal(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->security->xss_clean($this->input->post('save'))){
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$result = $this->reservation_model->add_paypal();
		if($result){
			$this->session->set_flashdata('success','Paypal Details Added Successfully');
			redirect('reservation/payment_list','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while adding paypal details');
			redirect('reservation/payment_list','refresh');
		}
		$this->views('channel/payment_method',$data);
		}else{
			$data['page_heading'] = 'PaymentList';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/payment_list',$data);
		}
	}
	// edit paypal details...
	function edit_paypal(){
		if(admin_id()=='')
		{
		   $this->is_login();
		}
		else
		{
		   $this->is_admin();
		}
		$data['page_heading'] = 'PaymentList';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['page_view'] = 'edit';
		$data['pay'] = $this->reservation_model->get_paypal();
		if($this->input->post('save')){
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
			  $result = $this->reservation_model->edit_paypal();
			}
			else if(user_type()=='2')
			{
				if(in_array('4', user_edit()))
				{
				  $result = $this->reservation_model->edit_paypal();
				}
				else
				{
					redirect(base_url());
				}
			}
			if($result){
				$this->session->set_flashdata('success','Paypal Details Updated Successfully');
				redirect('reservation/payment_list','refresh');
			}else{
				$this->session->set_flashdata('error','Error Occured while updating paypal details');
				redirect('reservation/payment_list','refresh');
			}
		}
		$this->views('channel/payment_method',$data);
	}

	// add tax category...

	function add_taxcategory(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('save')){
		$data['page_heading'] = 'TaxCategory';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){

		  $result = $this->reservation_model->add_taxcategories();
	    }
	    else if(user_type()=='2')
	    {
	    	if(in_array('4', user_edit()))
	    	{
 		      $result = $this->reservation_model->add_taxcategories();
 		    }
 		    else
 		    {
 		    	redirect(base_url());
 		    }
	    }
		if($result){
			$this->session->set_flashdata('success','Tax Categories Added Successfully');
			redirect('reservation/tax_categories','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while Adding Tax Categories');
			redirect('reservation/tax_categories','refresh');
		}
		$this->views('channel/tax_categories',$data);
		}
	}


	// get tax categories...

	  function gettax(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		 // echo 'sdsdsd';die;
		if($this->input->post('tax_id')!=''){
		$tax_id = ($this->input->post('tax_id'));
		$data['page_heading'] = 'ReservationOrder';

		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->reservation_model->get_tax($tax_id);
		$this->load->view('channel/tax_edit',$data);
		}else{
			$data['page_heading'] = 'ReservationOrder';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			$this->views('channel/payment_list',$data);
		}
	 }

	// edit tax categories...

	function edit_taxcategory(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->security->xss_clean($this->input->post('saveas'))){
		$tax_id = $this->input->post('tax_id');
		$data['page_heading'] = 'TaxCategory';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
		   $result = $this->reservation_model->edit_taxcategories($tax_id);
	    }
	    else if(user_type()=='2')
	    {
	    	if(in_array('4',user_edit()))
	    	{
	    		$result = $this->reservation_model->edit_taxcategories($tax_id);
	    	}
	    	else
	    	{
	    		redirect(base_url());
	    	}
	    }
		if($result){
			$this->session->set_flashdata('success','TaxCategories Updated Successfully');
			redirect('reservation/tax_categories','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while Updating TaxCategories');
			redirect('reservation/tax_categories','refresh');
		}
		$this->views('channel/tax_categories',$data);
		}
	}

	// delete tax categories..
	function delete_taxcategories(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		// echo $this->input->post('del_tax'); die;
		if($this->input->post('del_tax')!=''){
	  	$del = $this->input->post('del_tax');
		$data['page_heading'] = 'TaxCategories';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$result = $this->reservation_model->delete_taxcategory($del);
     	}
     	else if(user_type()=='2')
     	{
     		if(in_array('4',user_edit()))
     		{
     			$result = $this->reservation_model->delete_taxcategory($del);
     		}
     		else
     		{
     			redirect(base_url());
     		}
     	}
		if($result){
			$this->session->set_flashdata('success','TaxCategories Deleted Successfully');
			redirect('reservation/tax_categories','refresh');
		}else{
			$this->session->set_flashdata('error','Error Occured while Deleting TaxCategories');
			redirect('reservation/tax_categories','refresh');
		}
		$this->views('channel/tax_categories',$data);
		}
	}

	// resend_confirmation...
function resend_confirmation()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
	{
		if($this->input->post('id'))
		{
			$id  = $this->input->post('id');
			$result = $this->reservation_model->change_status();
			if($result)
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
	else
	{
		echo '0';
	}
}

	function admin_confirm($reservation_id)
	{
		$data = array('status'=>'Confirmed');
		$this->db->where('reservation_id',insep_decode($reservation_id));
		$sel = $this->db->update('manage_reservation',$data);
		if($sel)
		{
			redirect(base_url());
		}
		else
		{
			redirect(base_url());
		}
	}

	// filter ..
	function filter_res(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->security->xss_clean($this->input->post('guest_name')))
		{
		$data['page_heading'] = 'Filter Results';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->reservation_model->res_filter();
		$this->load->view('channel/ajax_filter',$data);
		}
	}

	// export to excel...

	function export_reservation()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		$filename = "Reservations-".time().".xls";

		$chaReserCheckCount = array();

		$query = $this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id ,
			case when b.property_name is null then 'No Room Set' else b.property_name end  roomname
			FROM `manage_reservation`   AS e
			left join manage_property b on e.room_id = b.property_id
			where  e.hotel_id = ".hotel_id()." order by DATE_FORMAT(STR_TO_DATE(e.start_date,'%d/%m/%Y'),'%Y-%m-%d') desc");

		$booking=$this->db->query("select a.status,a.guest_name,'Booking.com' AS channel, DATE_FORMAT(a.arrival_date,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure_date,'%d/%m/%Y') as end_date, a.date_time as booking_date, concat(a.reservation_id,'-',a.roomreservation_id) reservation_code,
			a.totalprice as price, b.email as user_email, telephone as mobile,
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			2 as channel_id , b.currencycode  as currency_id
			from import_reservation_BOOKING_ROOMS a
			left join import_reservation_BOOKING b on a.import_reserv_id=b.import_reserv_id
			left join import_mapping_BOOKING c on a.id= c.B_room_id and a.rate_id = c.B_rate_id
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=2
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_hotel_id=".hotel_id()." order by arrival_date desc")->result();
		$airbnb=$this->db->query("select a.ResStatus as status,a.name as guest_name,'AIRBNB' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.ImportDate as booking_date, a.ResID_Value reservation_code,
			a.AmountAfterTax as price, '' as user_email, '' as mobile,
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			9 as channel_id , a.Currency  as currency_id
			from import_reservation_AIRBNB a
			left join import_mapping_AIRBNB c on a.RoomTypeCode=c.Roomid
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=9
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_id=".hotel_id()." order by arrival desc")->result();

		$expedia=$this->db->query("select a.type as status,  concat(a.givenName,' ',a.surname) as guest_name,'EXPEDIA' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.created_time as booking_date, a.booking_id reservation_code,
			a.amountAfterTaxes as price, a.Email as user_email, a.number as mobile,
			'' roomname,
			1 as channel_id , a.currency  as currency_id
			from import_reservation_EXPEDIA a
			where a.hotel_id=".hotel_id()." order by arrival desc")->result();

		$chaReserCheckCount = array_merge($chaReserCheckCount,$query->result());
		$chaReserCheckCount = array_merge($chaReserCheckCount,$booking);
		$chaReserCheckCount = array_merge($chaReserCheckCount,$airbnb);
		$chaReserCheckCount = array_merge($chaReserCheckCount,$expedia);


		$test	=	'<table width="100%" style="width: 100%;">

					<thead>

						<tr>

						<th style="width: 106px; text-align:left;">Status</th>

						<th style="width: 106px; text-align:left;">Name</th>

						<th style="width: 106px; text-align:left;">Mail</th>

						<th style="width: 106px; text-align:left;">Mobile</th>

						<th style="width: 106px; text-align:left;">Room</th>

						<th style="width: 106px; text-align:left;">Channel</th>

						<th style="width: 106px; text-align:left;">Check In</th>

						<th style="width: 106px; text-align:left;">Check Out</th>

						<th style="width: 106px; text-align:left;">Booked Date</th>

						<th style="width: 106px; text-align:left;">Reservation Id</th>

						<th style="width: 106px; text-align:left;">Amount</th>

						</tr>

					</thead>

					<tbody>';

					foreach($chaReserCheckCount as $row)
					{
						$user_email	=	$row->user_email;

						 $mobile	=	$row->mobile;


						$roomName	=	$row->roomname;


						if($row->channel_id==0)
						{
							$price	=	get_data(TBL_CUR,array('currency_id'=>$row->currency_id))->row()->symbol.' '.number_format($row->price);
						}
						else
						{
							$price	=	$row->currency_id.' '.$row->price;
						}

						$test	.='<tr>
						<td>'.$row->status.'</td>
						<td>'.$row->guest_name.'</td>
						<td>'.$user_email.'</td>
						<td>'.$mobile.'</td>
						<td>'.$roomName.'</td>
						<td>'.$row->channel.'</td>
						<td>'.$row->start_date.'</td>
						<td>'.$row->end_date.'</td>
						<td>'.date('M d,Y h:i:s A',strtotime(str_replace("/","-",$row->booking_date))).'</td>
						<td>'.$row->reservation_code.'</td>
						<td>'.$price.'</td></tr>';
					}

					$test	.='</tbody>
							</table>';
					header('Content-Encoding: UTF-8');
					header('Content-type: application/csv; charset=UTF-8');
					header("Content-type: application/vnd.ms-excel");
					header("Content-Disposition: attachment; filename=".$filename);
					echo $test;


	}
	function export_reservationPDF()
	{

		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}



		$chaReserCheckCount = array();

		$query = $this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id ,
			case when b.property_name is null then 'No Room Set' else b.property_name end  roomname
			FROM `manage_reservation`   AS e
			left join manage_property b on e.room_id = b.property_id
			where  e.hotel_id = ".hotel_id()." order by DATE_FORMAT(STR_TO_DATE(e.start_date,'%d/%m/%Y'),'%Y-%m-%d') desc");

		$booking=$this->db->query("select a.status,a.guest_name,'Booking.com' AS channel, DATE_FORMAT(a.arrival_date,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure_date,'%d/%m/%Y') as end_date, a.date_time as booking_date, concat(a.reservation_id,'-',a.roomreservation_id) reservation_code,
			a.totalprice as price, b.email as user_email, telephone as mobile,
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			2 as channel_id , b.currencycode  as currency_id
			from import_reservation_BOOKING_ROOMS a
			left join import_reservation_BOOKING b on a.import_reserv_id=b.import_reserv_id
			left join import_mapping_BOOKING c on a.id= c.B_room_id and a.rate_id = c.B_rate_id
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=2
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_hotel_id=".hotel_id()." order by arrival_date desc")->result();
		$airbnb=$this->db->query("select a.ResStatus as status,a.name as guest_name,'AIRBNB' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.ImportDate as booking_date, a.ResID_Value reservation_code,
			a.AmountAfterTax as price, '' as user_email, '' as mobile,
			case when e.property_name is null then 'No Room Set' else e.property_name end  roomname,
			9 as channel_id , a.Currency  as currency_id
			from import_reservation_AIRBNB a
			left join import_mapping_AIRBNB c on a.RoomTypeCode=c.Roomid
			left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=9
			left join manage_property e on d.property_id = e.property_id
		 where a.hotel_id=".hotel_id()." order by arrival desc")->result();

		$expedia=$this->db->query("select a.type as status,  concat(a.givenName,' ',a.surname) as guest_name,'EXPEDIA' AS channel, DATE_FORMAT(a.arrival,'%d/%m/%Y') as start_date , DATE_FORMAT(a.departure,'%d/%m/%Y') as end_date, a.created_time as booking_date, a.booking_id reservation_code,
			a.amountAfterTaxes as price, a.Email as user_email, a.number as mobile,
			'' roomname,
			1 as channel_id , a.currency  as currency_id
			from import_reservation_EXPEDIA a
			where a.hotel_id=".hotel_id()." order by arrival desc")->result();

		$chaReserCheckCount = array_merge($chaReserCheckCount,$query->result());
		$chaReserCheckCount = array_merge($chaReserCheckCount,$booking);
		$chaReserCheckCount = array_merge($chaReserCheckCount,$airbnb);
		$chaReserCheckCount = array_merge($chaReserCheckCount,$expedia);


		$test	=	'<table border="1" width="100%" style="width: 100%; border-collapse:separate;border-spacing:2px;border-color:#ddd;">

					<thead>

						<tr>

						<th style="width: 106px; text-align:left;">Status</th>

						<th style="width: 106px; text-align:left;">Name</th>

						<th style="width: 106px; text-align:left;">Mail</th>

						<th style="width: 106px; text-align:left;">Mobile</th>


						<th style="width: 106px; text-align:left;">Amount</th>

						</tr>

					</thead>

					<tbody>';

					foreach($chaReserCheckCount as $row)
					{
						$user_email	=	$row->user_email;

						 $mobile	=	$row->mobile;


						$roomName	=	$row->roomname;


						if($row->channel_id==0)
						{
							$price	=	get_data(TBL_CUR,array('currency_id'=>$row->currency_id))->row()->symbol.' '.number_format($row->price);
						}
						else
						{
							$price	=	$row->currency_id.' '.$row->price;
						}

						$test	.='<tr>
						<td>'.$row->status.'</td>
						<td>'.$row->guest_name.'</td>
						<td>'.$user_email.'</td>
						<td>'.$mobile.'</td>

						<td>'.$price.'</td></tr>';
					}

					$test	.='</tbody>
							</table>';
require_once('application/libraries/pdf/PDF.php');



$pdf = new PDF();
// Primera página
$pdf->AddPage();
$pdf->SetFont('Arial','',20);

$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);
$pdf->WriteHTML('<table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#000000" style="border-collapse:collapse;border-color:#ddd;">

<tr>

<td>&nbsp;</td>

<td>&nbsp;</td>

</tr>

<tr><td>&nbsp;</td>

<td>&nbsp;</td>

</tr>

</table>');
$pdf->Output('D');



	}

	function export_reservation_old()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$t_date = date('Y-m-d');
		$filename = "Reservations-".$t_date.".xls";
		$this->load->dbutil();
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\n";
		$hotel_id = hotel_id();
		$user_id = current_user_type();
		$data='';
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$query = $this->db->query("select e.status,e.guest_name, 'HotelAvailability' AS channel, e.start_date,e.end_date,e.booking_date,e.reservation_code,e.price,e.email,e.mobile FROM `manage_reservation` AS e  where e.hotel_id = '".$hotel_id."' AND e.user_id = '".$user_id."'");
			$data .= $this->dbutil->csv_from_result($query, $delimiter, $newline);
			$this->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query');
			$this->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
			$this->db->where(array('C.user_id'=>current_user_type(),'C.hotel_id'=>hotel_id()));
			$query = $this->db->get(ALL.' as A');
			if($query)
			{
				$all_api_new_book = $query->result_array();
			}
			foreach($all_api_new_book as $table_field)
			{
				extract($table_field);
				$select = explode(',',$fetch_query_count);
				if($channel_id==2)
				{
					$hotel_id = 'hotel_hotel_id';
				}
				else
				{
					$hotel_id = 'hotel_id';
				}
				$cahquery = $this->db->query('SELECT ( CASE WHEN R.'.$select[2].' ="11" THEN "Reservation" WHEN R.'.$select[2].' ="12" THEN "Modification" WHEN R.'.$select[2].' ="12" THEN "Cancel" ELSE R.'.$select[2].' END )as status , R.'.$select[3].' as guest_name , C.channel_name as channel, DATE_FORMAT(R.'.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT(R.'.$select[6].',"%d/%m/%Y") as end_date FROM ('.$channel_table_name.' AS R) JOIN '.TBL_CHANNEL.' AS C ON R.channel_id = C.channel_id WHERE R.user_id = "'.current_user_type().'" AND R.'.$hotel_id.' = "'.hotel_id().'"');
				$data .= mb_convert_encoding(ltrim(strstr($this->dbutil->csv_from_result($cahquery,',', "\r\n"), "\r\n")), "UTF-8");
				//$data .= $this->dbutil->csv_from_result($cahquery, $delimiter, $newline);
			}
			force_download($filename, $data);
		}
		else if(user_type()=='2')
		{
			if(in_array('2',user_edit()))
			{
				$query = $this->db->query("select e.status,e.guest_name, 'HotelAvailability' AS channel, e.start_date,e.end_date,e.booking_date,e.reservation_code,e.price,e.email,e.mobile FROM `manage_reservation` AS e  where e.hotel_id = '".$hotel_id."' AND e.user_id = '".$user_id."'");

				$data .= $this->dbutil->csv_from_result($query, $delimiter, $newline);
				$data .= $this->dbutil->csv_from_result($query, $delimiter, $newline);
				force_download($filename, $data);
			}
			else
			{
				redirect('reservation/reservationlist','refresh');
			}
		}
		else if(admin_id()!='' && admin_type()=='1')
		{
			$query = $this->db->query("select e.status,e.guest_name, 'HotelAvailability' AS channel, e.start_date,e.end_date,e.booking_date,e.reservation_code,e.price,e.email,e.mobile FROM `manage_reservation` AS e where e.hotel_id = '".$hotel_id."' AND e.user_id = '".$user_id."'");

			$data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
			force_download($filename, $data);
		}
	}

  function edit_cash(){

   if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}

   if($this->input->post('save')){

   $data['page_heading'] = 'Payment list';

   $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

   $data= array_merge($user_details,$data);

   $result = $this->reservation_model->edit_cashdet();

   if($result){

       $this->session->set_flashdata('success','Cash details Updated successfully');

       redirect('reservation/payment_list','refresh');

   }else{

       $this->session->set_flashdata('error','Error occured while updating cash details');

       redirect('reservation/payment_list','refresh');

   }

   $this->views('channel/payment_list',$data);

}

}

// cash active ...

	function cash_status()

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



	   if($status_method=='active')

	   {

		   $udata['status'] = '1';

	   }

	   elseif($status_method=='passive')

	   {

		   $udata['status'] = '0';

	   }



	   if($status_type=='cash')

	   {



		   if(update_data('cash_details',$udata,array('user_id'=>current_user_type())))

		   {

			   $cash_details = get_data('cash_details',array('user_id'=>current_user_type()))->row();

			   if($status_method=='active')

			   {

			   ?>

			   <a data-remotes="true" href="javascript:;" class="cash_active" id="cash_active" type="cash" method="passive" data-id="<?php echo $cash_details->cash_id;?>">

			   <input type="hidden" id="cash_current_status" value="<?php echo $cash_details->status?>" />

			   <div class="onoffswitch-wrap ">

			   <div class="onoffswitch">

			   <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">

			   <label for="active-channel" class="onoffswitch-label"></label>

			   </div>

			   <label class="switch-label" for="active-channel">Active</label>

			   </div>

			   </a>

			   <?php

			   }

			   elseif($status_method=='passive')

			   {

			   ?>


			   <a data-remotes="true" href="javascript:;" class="cls_passive" id="cash_active" type="cash" method="active" data-id="<?php echo $cash_details->cash_id;?>">

			   <input type="hidden" id="cash_current_status" value="<?php echo $cash_details->status?>" />

			   <div class="onoffswitch-wrap switch-label-deactivate">

				 <div class="onoffswitch">

			   <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">

			   <label for="active-channel" class="onoffswitch-label"></label>

			   </div>

				<label class="switch-label" for="active-channel">Passive</label>

			   </div>

			   </a>

			   <?php

			   }

		   }

	   }
	}

function user_status_info(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		 // echo 'sdsdsd';die;
		// echo $this->input->post('reservation_id');die;
		if($this->input->post('reservation_id')!=''){
		$reservation_id = ($this->input->post('reservation_id'));
		$user_det = $this->input->post('user_det');
		$data['page_heading'] = 'ReservationOrder';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$this->reservation_model->user_status($reservation_id);
		$this->views('channel/reservation_order',$data);
		}
	}

function report_revenue()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Repots On Revenue';
	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$country_head[]="Price";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);

		$today_date = $y.'-'.$m.'-'.$h;

		$hotel_id = hotel_id();

		$query = $this->db->query("SELECT SUM(`price`) AS price, `booking_date` , `country` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."'");

		$hh = $query->row('price');

		$api_price = all_api_price($today_date,'Revenue','Price');

		if($hh!='')
		{
			$price=$hh+$api_price;
		}
		else
		{
			$price=0+$api_price;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];
				if(array_key_exists($va['country_name'],$countrya))
				{
					$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				}
				else
				{
					$countrya[$va['country_name']]=$va['prices'];
				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$data['channels'] = 'all';
	$this->views('channel/revenue',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function last_seven_days()
{
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}

	$data['page_heading'] = 'ReservationList';

	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();

	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_revenue');

	$channels = $this->input->post('channel_revenue');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);

		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);

		$periodInterval = new DateInterval("P1D");

		$endDate->add( $periodInterval );

		$period = new DatePeriod( $startDate, $periodInterval, $endDate );

		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");

			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT SUM(`price`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."'");

				$hh = $query->row('price');
				$api_price = all_api_price($today_date,'Revenue','Price');
				if($hh!='')
				{
					$price=$hh+$api_price;
				}
				else
				{
					$price=0+$api_price;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Revenue','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					}
					else
					{
						$countrya[$va['country_name']]=$va['prices'];
					}
				}
			}

			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			foreach($period as $date)
			{
				if($i==1)
				{
				  $start=$date->format("d");
				  $data['start_dates']=$date->format("d/m/Y");
				}
				//$date->format("d/m/Y").'<br>';
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(`price`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		}
	 	else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT SUM(`price`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
						$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
	 	else if($days=="today")
		{
			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT SUM(`price`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;

				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/revenue_ajax',$data);
	}
}

function nights_revenue()
{
    if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Reports On Nights';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="Country";
	$country_head[]="Nights";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	$hotel_id = hotel_id();
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);
		$today_date = $y.'-'.$m.'-'.$h;
		$query = $this->db->query("SELECT SUM(`num_nights`) AS price, `booking_date` , `country` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
		$con_query = $this->db->query("SELECT SUM(U.num_nights) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."'");
		$hh = $query->row('price');
		$api_price = all_api_price($today_date,'Nights','Price');
		if($hh!='')
		{
		   $price=$hh+$api_price;
		}
		else
		{
		   $price=0+$api_price;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Nights','Country_Price'));
		if(count($cc)!=0)
		{
		   foreach($cc as $va)
		   {
			   $country_name = $va['country_name'];
			   if(array_key_exists($va['country_name'],$countrya))
			   {
				   $countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
			   }
			   else
			   {
				   $countrya[$va['country_name']]=$va['prices'];
			   }
		   }
	   }
   }
   if($countrya)
   {
	   foreach($countrya as $key=>$val)
	   {
		   $final_country[]=$key;
		   $final_country[]=$val;
	   }
   }
   $country_chunk=array_merge($country_head,$final_country);
   $view_county=array_chunk($country_chunk,2);
   $data['graphnew']=json_encode($view_county);
   $data['graph']=json_encode($graph);
   $data['graph1']=json_encode($graph1);
   $data['days'] = 'today';
   $this->views('channel/revenue_nights',$data);
   }
   else
	{
		redirect(base_url());
	}
}

function nights_last_seven_days()
{
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	$data['page_heading'] = 'ReservationList';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_night');

	$channels = $this->input->post('channel_night');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);
		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);
		$periodInterval = new DateInterval("P1D");
		$endDate->add( $periodInterval );
		$period = new DatePeriod( $startDate, $periodInterval, $endDate );
		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");
			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT SUM(`num_nights`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$con_query = $this->db->query("SELECT SUM(U.num_nights) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
				$hh = $query->row('price');
				$api_price = all_api_price($today_date,'Nights','Price');
				if($hh!='')
				{
					$price=$hh+$api_price;
				}
				else
				{
					$price=0+$api_price;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Nights','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Revenue','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
				   $country_name = $va['country_name'];
				   if(array_key_exists($va['country_name'],$countrya))
				   {
					   $countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				   }
				   else
				   {
					   $countrya[$va['country_name']]=$va['prices'];
				   }
				}
			}
			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
			   $final_country[]=$key;
			   $final_country[]=$val;
			}
		}
	}
	else if($from_date=='' && $to_date=='' && $days!='' && $channels!='')
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');
			$today_date = date('Y/m/d');
			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));
			$startDate = DateTime::createFromFormat("d/m/Y",$today);
			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
			$periodInterval = new DateInterval("P1D");
			$endDate->add( $periodInterval );
			$period = new DatePeriod( $endDate, $periodInterval, $startDate );
			$i=1;
			foreach($period as $date)
			{
				if($i==1)
				{
					$start=$date->format("d");
					$data['start_dates']=$date->format("d/m/Y");
				}
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc= array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(`num_nights`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.num_nights) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Nights','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Nights','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		}
		else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');
			$today_date = date('Y/m/d');
			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));
			$startDate = DateTime::createFromFormat("d/m/Y",$today);
			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
			$periodInterval = new DateInterval("P1D");
			$endDate->add( $periodInterval );
			$period = new DatePeriod( $endDate, $periodInterval, $startDate );
			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(`num_nights`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.num_nights) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Nights','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Nights','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
						   $countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
						   $countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
		else if($days=="today")
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(`num_nights`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT SUM(U.num_nights) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Nights','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Nights','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
				   foreach($cc as $va)
				   {
					   $country_name = $va['country_name'];
					   if(array_key_exists($va['country_name'],$countrya))
					   {
						   $countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					   }
					   else
					   {
						   $countrya[$va['country_name']]=$va['prices'];
					   }
				   }
				}
			}
			if($countrya)
			{
			   foreach($countrya as $key=>$val)
			   {
				   $final_country[]=$key;
				   $final_country[]=$val;
			   }
			}
			$data['days'] = 'today';
		}
    }
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/revenue_nights_ajax',$data);
	}
}

function average_revenue()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Reports On Average Revenue';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$country_head[]="Price";
	$hotel_id =hotel_id();
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);
		$today_date = $y.'-'.$m.'-'.$h;

		$query = $this->db->query("SELECT SUM(`price`) AS price, COUNT(`user_id`) AS user_id , `booking_date` , `country` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$con_query = $this->db->query("SELECT SUM(U.price) AS prices, COUNT(`user_id`) AS user_id , U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
		$api_price = all_api_price($today_date,'Average','Price');
		$amount = $query->row('price') + @$api_price['price'];
		$count_user = $query->row('user_id')+ @$api_price['user_id'];
		if($amount!='' && $count_user!='')
		{
			$price = $amount/$count_user;
		}
		else
		{
			$price=0;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price'));
		/* echo '<pre>';
		print_r($cc); */
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];
				if(array_key_exists($va['country_name'],$countrya))
				{
					if($va['user_id']!=0)
					{
						$countrya[$va['country_name']] 	=  $countrya[$va['country_name']]+$va['prices']/$va['user_id'];
					}
					else
					{
						$countrya[$va['country_name']] 	=  $countrya[$va['country_name']]+$va['prices'];
					}
				}
				else
				{
					if($va['user_id']!=0)
					{
						$countrya[$va['country_name']]	=	$va['prices']/$va['user_id'];
					}
					else
					{
						$countrya[$va['country_name']]	=	$va['prices'];
					}

				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$this->views('channel/average_revenue',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function average_last_seven_days()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'ReservationList';

	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_average');

	$channels = $this->input->post('channel_average');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

    if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);
		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);
		$periodInterval = new DateInterval("P1D");
		$endDate->add( $periodInterval );
		$period = new DatePeriod( $startDate, $periodInterval, $endDate );
		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");
			$cc	= array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT SUM(price) AS price, COUNT(user_id) AS user_id , booking_date , country FROM (manage_reservation)WHERE user_id = '".current_user_type()."' AND booking_date = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$con_query = $this->db->query("SELECT U.price AS prices, COUNT(`user_id`) AS user_id ,U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
				$api_price 	= all_api_price($today_date,'Average','Price');
				$amount 	= $query->row('price') + @$api_price['price'];
				$count_user = $query->row('user_id') + @$api_price['user_id'];
				if($amount!='' && $count_user!='')
				{
					$price = $amount/$count_user;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price'));
			}
			else
			{
				$api_price 	= 	all_api_price($today_date,'Average','Price',$channels);
				$amount		=	$api_price['price'];
				$count_user	=	$api_price['user_id'];
				if($amount!='' && $count_user!='')
				{
					$price = $amount/$count_user;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						if($va['user_id']!=0)
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices']/$va['user_id'];
						}
						else
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
					}
					else
					{
						if($va['user_id']!=0)
						{
							$countrya[$va['country_name']]=$va['prices']/$va['user_id'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;
			$today = date('d/m/Y');
			$today_date = date('Y/m/d');
			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));
			$startDate = DateTime::createFromFormat("d/m/Y",$today);
			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
			$periodInterval = new DateInterval("P1D");
			$endDate->add( $periodInterval );
			$period = new DatePeriod( $endDate, $periodInterval, $startDate );
			$i=1;
			foreach($period as $date)
			{
				if($i==1)
				{
					$start=$date->format("d");
					$data['start_dates']=$date->format("d/m/Y");
				}
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(price) AS price, COUNT(user_id) AS user_id , booking_date , country FROM (manage_reservation)WHERE user_id = '".current_user_type()."' AND booking_date = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT U.price AS prices, COUNT(`user_id`) AS user_id ,U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$api_price = all_api_price($today_date,'Average','Price');
					$amount = $query->row('price') + @$api_price['price'];
					$count_user = $query->row('user_id') + @$api_price['user_id'];
					if($amount!='' && $count_user!='')
					{
						$price = $amount/$count_user;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price'));
				}
				else
				{
					$api_price 	= 	all_api_price($today_date,'Average','Price',$channels);
					if($api_price)
					{
						$amount		=	$api_price['price'];
						$count_user	=	$api_price['user_id'];
						if($amount!='' && $count_user!='')
						{
							$price = $amount/$count_user;
						}
						else
						{
							$price=0;
						}
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;

				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
							}
						}
						else
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']]=$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']]=$va['prices'];
							}
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
				$final_country[]=$key;
				$final_country[]=$val;
				}
			}
		}
		else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;
			$today = date('d/m/Y');
			$today_date = date('Y/m/d');
			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));
			$startDate = DateTime::createFromFormat("d/m/Y",$today);
			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
			$periodInterval = new DateInterval("P1D");
			$endDate->add( $periodInterval );
			$period = new DatePeriod( $endDate, $periodInterval, $startDate );
			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(price) AS price, COUNT(user_id) AS user_id , booking_date , country FROM (manage_reservation)WHERE user_id = '".current_user_type()."' AND booking_date = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT U.price AS prices, COUNT(`user_id`) AS user_id ,U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");
					$api_price = all_api_price($today_date,'Average','Price');
					$amount = $query->row('price') + @$api_price['price'];
					$count_user = $query->row('user_id') + @$api_price['user_id'];
					if($amount!='' && $count_user!='')
					{
						$price = $amount/$count_user;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price'));
				}
				else
				{
					$api_price 	= 	all_api_price($today_date,'Average','Price',$channels);
					if($api_price)
					{
						$amount		=	$api_price['price'];
						$count_user	=	$api_price['user_id'];
						if($amount!='' && $count_user!='')
						{
							$price = $amount/$count_user;
						}
						else
						{
							$price=0;
						}
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
							}
						}
						else
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']]=$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']]=$va['prices'];
							}
						}
				   }
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
		else if($days=="today")
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;
			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT SUM(price) AS price, COUNT(user_id) AS user_id , booking_date , country FROM (manage_reservation)WHERE user_id = '".current_user_type()."' AND booking_date = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT U.price AS prices, COUNT(`user_id`) AS user_id ,U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$api_price = all_api_price($today_date,'Average','Price');
					$amount = $query->row('price') + @$api_price['price'];
					$count_user = $query->row('user_id') + @$api_price['user_id'];
					if($amount!='' && $count_user!='')
					{
						$price = $amount/$count_user;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price'));
				}
				else
				{

					$api_price 	= 	all_api_price($today_date,'Average','Price',$channels);
					if($api_price)
					{
						$amount		=	$api_price['price'];
						$count_user	=	$api_price['user_id'];
						if($amount!='' && $count_user!='')
						{
							$price = $amount/$count_user;
						}
						else
						{
							$price=0;
						}
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Average','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
							}
						}
						else
						{
							if($va['user_id']!=0)
							{
								$countrya[$va['country_name']]=$va['prices']/$va['user_id'];
							}
							else
							{
								$countrya[$va['country_name']]=$va['prices'];
							}
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/average_ajax',$data);
	}
}

function report_reservation()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Reports on Reservation';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$hotel_id = hotel_id();
	$country_head[]="Price";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);
		$today_date = $y.'-'.$m.'-'.$h;

		$query = $this->db->query("SELECT COUNT(`reservation_id`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$con_query = $this->db->query("SELECT COUNT(U.reservation_id) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$hh = $query->row('price') + all_api_price($today_date,'Reservation','Price');

		if($hh!='')
		{
			$price=$hh;
		}
		else
		{
			$price=0;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price'));
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];

				if(array_key_exists($va['country_name'],$countrya))
				{
					$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				}
				else
				{
					$countrya[$va['country_name']]=$va['prices'];
				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$this->views('channel/reservation_graph',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function reservation_graph()
{
	//	echo 'dfdfdf';die;
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'ReservationList';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_reseravtion');

	$channels = $this->input->post('channel_reservation');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);

		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);

		$periodInterval = new DateInterval("P1D");

		$endDate->add( $periodInterval );

		$period = new DatePeriod( $startDate, $periodInterval, $endDate );

		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");
			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT COUNT(`reservation_id`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$con_query = $this->db->query("SELECT COUNT(U.reservation_id) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$hh = $query->row('price') + all_api_price($today_date,'Reservation','Price');
				if($hh!='')
				{
					$price=$hh;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Reservation','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					}
					else
					{
						$countrya[$va['country_name']]=$va['prices'];
					}
				}
			}

			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;

			foreach($period as $date)
			{
				if($i==1)
				{
					$start=$date->format("d");
					$data['start_dates']=$date->format("d/m/Y");
				}
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`reservation_id`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.reservation_id) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Reservation','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Reservation','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		}
		else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`reservation_id`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.reservation_id) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Reservation','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Reservation','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
		else if($days=="today")
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`reservation_id`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.reservation_id) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Reservation','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Reservation','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Reservation','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))

						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/reservation_ajax',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function report_guest()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Reports on Guests';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$hotel_id = hotel_id();
	$country_head[]="Price";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);
		$today_date = $y.'-'.$m.'-'.$h;

		$query = $this->db->query("SELECT COUNT(`members_count`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$con_query = $this->db->query("SELECT COUNT(U.members_count) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

		$hh = $query->row('price') + all_api_price($today_date,'Guests','Price');
		if($hh!='')
		{
			$price=$hh;
		}
		else
		{
			$price=0;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price'));
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];
				if(array_key_exists($va['country_name'],$countrya))
				{
					$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				}
				else
				{
					$countrya[$va['country_name']]=$va['prices'];
				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$this->views('channel/guest',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function guest_graph()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'ReservationList';
	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_guests');

	$channels = $this->input->post('channel_guests');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);

		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);

		$periodInterval = new DateInterval("P1D");

		$endDate->add( $periodInterval );

		$period = new DatePeriod( $startDate, $periodInterval, $endDate );

		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");
			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT COUNT(`members_count`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$con_query = $this->db->query("SELECT COUNT(U.members_count) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

				$hh = $query->row('price') + all_api_price($today_date,'Guests','Price');
				if($hh!='')
				{
					$price=$hh;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Guests','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					}
					else
					{
						$countrya[$va['country_name']]=$va['prices'];
					}
				}
			}
			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;

			foreach($period as $date)
			{
				if($i==1)
				{
					$start=$date->format("d");
					$data['start_dates']=$date->format("d/m/Y");
				}
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`members_count`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.members_count) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Guests','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Guests','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		 }
		else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array ();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`members_count`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.members_count) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Guests','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Guests','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
		else if($days=="today")
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT COUNT(`members_count`) as price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$con_query = $this->db->query("SELECT COUNT(U.members_count) AS prices, U.booking_date , U.country , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."'");

					$hh = $query->row('price') + all_api_price($today_date,'Guests','Price');
					if($hh!='')
					{
						$price=$hh;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Guests','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Guests','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/guest_ajax',$data);
	}
}

	/*Start Subbaiah \*/
	function payment_status()
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

		if($this->security->xss_clean($status_method)=='active')
		{
			$udata['status'] = '1';
		}
		elseif($this->security->xss_clean($status_method)=='passive')
		{
			$udata['status'] = '0';
		}
		if($this->security->xss_clean($status_type)=='paypal')
		{

			if(update_data('paypal_details',$udata,array('user_id'=>current_user_type())))
			{
				$paypal_details = get_data('paypal_details',array('user_id'=>current_user_type()))->row();
				if($this->security->xss_clean($status_method)=='active')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="paypal_active" id="paypal_active" type="paypal" method="passive" data-id="<?php echo $paypal_details->paypal_id;?>">
				<input type="hidden" id="deposit_current_status" value="<?php echo $paypal_details->status?>" />
				<div class="onoffswitch-wrap ">
				<div class="onoffswitch">
				<input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
				<label for="active-channel" class="onoffswitch-label"></label>
				</div>
				<label class="switch-label" for="active-channel">Active</label>
				</div>
				</a>
				<?php
				}
				elseif($this->security->xss_clean($status_method)=='passive')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_passive" id="paypal_active" type="paypal_active" method="active" data-id="<?php echo $paypal_details->paypal_id;?>">
        		<input type="hidden" id="deposit_current_status" value="<?php echo $paypal_details->status?>" />
				<div class="onoffswitch-wrap switch-label-deactivate">
          		<div class="onoffswitch">
			    <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				<label for="active-channel" class="onoffswitch-label"></label>
		        </div>
         		<label class="switch-label" for="active-channel">Passive</label>
        		</div>
				</a>
				<?php
				}

			}
		}
		elseif($this->security->xss_clean($status_type)=='cancels')
		{

			if(update_data(PCANCEL,$udata,array('user_id'=>current_user_type())))
			{
				$cancel_details = get_data(PCANCEL,array('user_id'=>current_user_type()))->row();
				if($status_method=='active')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="passive" data-id="<?php echo $cancel_details->policy_id;?>">
				<input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
				<div class="onoffswitch-wrap ">
				<div class="onoffswitch">
				<input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
				<label for="active-channel" class="onoffswitch-label"></label>
				</div>
				<label class="switch-label" for="active-channel">Active</label>
				</div>
				</a>
				<?php
				}
				elseif($this->security->xss_clean($status_method)=='passive')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="active" data-id="<?php echo $cancel_details->policy_id;?>">
        		<input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
				<div class="onoffswitch-wrap switch-label-deactivate">
          		<div class="onoffswitch">
			    <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				<label for="active-channel" class="onoffswitch-label"></label>
		        </div>
         		<label class="switch-label" for="active-channel">Passive</label>
        		</div>
				</a>
				<?php
				}

			}
		}


	}

/*Start Subbaiah \*/

function get_reservation()
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
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			$checkin_date	=	str_replace("/","-",$this->input->get('dp1'));

			$prev			=	date('d-m-Y', strtotime('-1 day', strtotime($checkin_date)));

			$checkout_date	=	str_replace("/","-",$this->input->get('dp2'));

			$start 			=	strtotime($checkin_date);

			$end 			= 	strtotime($checkout_date);

			$nights 		= 	ceil(abs($end - $start) / 86400);

			$rooms 			=	$this->input->get('num_rooms');

			$adult 			=	$this->input->get('num_person');

			$child 			=	$this->input->get('num_child');

			$currency		=	$this->currency_code;

			$resrve			=	$this->reservation_model->get_reserve();

			if($resrve)
			{
				/* echo '<pre>'; */
				$a1=array("a"=>"red","b"=>"green");
				$a2=array("c"=>"blue","b"=>"yellow");
				$oneDimensionalArray = call_user_func_array('array_merge', $resrve);
				//echo array_sum(array_column($oneDimensionalArray, 'price'));
				/* print_r($oneDimensionalArray); */
				$newArray = array();
				$newArray1 = array();
				$i=0;
				foreach($oneDimensionalArray as $room_value) {
					if(@$room_value['rate_types_id']=='')
					{
						if(empty($room_value['price']) || $room_value['price']=='0.00')
						{
							$newArray[$room_value['room_id'].'_'.$i]	=	$room_value['base_price'];
						}
						else
						{
							$newArray[$room_value['room_id'].'_'.$i]	=	$room_value['price'];
						}
					}
					else
					{
						if(empty($room_value['price']) || $room_value['price']=='0.00')
						{
							$newArray1[$room_value['room_id'].'_'.$room_value['rate_types_id'].'_'.$i]	=	$room_value['base_price'];
						}
						else
						{
							$newArray1[$room_value['room_id'].'_'.$room_value['rate_types_id'].'_'.$i]	=	$room_value['price'];
						}
					}
					$i++;
				}
				/*echo $room_day_price;
				echo $rate_day_price;*/
				/*print_r($newArray);
				print_r($newArray1);*/
				$price = 0;
				foreach($newArray as $key => $value){
				    $exp_key = explode('_', $key);
				    if($exp_key[0] == '3'){
				    	$price = $price + $value;
				         $arr_result[$exp_key[0]] = $price;
				    }
				}

				/*print_r($arr_result);
				echo '</pre>';	*/
				$i	=	0;
				$dis_room_id=array();
				$dis_rate_id=array();
				$room_price=0;
				foreach($oneDimensionalArray as $room_value) {

					$i++;

					if(@$room_value['rate_types_id']=='')
					{
						$room_name	=	$room_value['property_name'];

					}
					else
					{
						if($room_value['rate_name']=='')
						{
							$rate_name	=	'#'.$room_value['uniq_id'];
						}
						else
						{
							$rate_name	=	$room_value['rate_name'];
						}

						$room_name	=	$room_value['property_name'].' ( '.$rate_name.' ) ';
 					}

					if($room_value['image']=='')
					{
						$room_photo	=	$this->reservation_model->get_photo($room_value['room_id']);

						if($room_photo=="no") {

							if($room_value['image']=="") {

								$photo	=	'uploads/room_photos/noimage.jpg';

							}else {

								$photo	=	'uploads/room_type/'.$room_value['image'];
							}
						}else {

							$photo	=	'uploads/room_photos/'.$room_photo;
						}
					}
					else
					{
						$photo	=	'uploads/room_type/'.$room_value['image'];
					}
						if(!in_array($room_value['room_id'],$dis_room_id) || !in_array(@$room_value['rate_types_id'],$dis_rate_id))
						{

						if(@$room_value['rate_types_id']=='')
						{
							$price		=	0;
							$price_d	=	'';
							foreach($newArray as $key => $value){
							    $exp_key = explode('_', $key);
							    if($exp_key[0] == $room_value['room_id']){
									$price_d	.=	$value.',';
							    	$arr_result_day[$exp_key[0]] = $price_d;
							    	$price 		=	$price + $value;
							        $arr_result[$exp_key[0]] = $price;
							    }
							}
						}
						else
						{
							$price 		=	0;
							$price_d	=	'';
							foreach($newArray1 as $key => $value){
							    $exp_key = explode('_', $key);
							    if($exp_key[0] == $room_value['room_id']){
									$price_d	.=	$value.',';
							    	$arr_result_day[$exp_key[0]] = $price_d;
							    	$price 		= 	$price + $value;
							        $arr_result[$exp_key[0]] = $price;
							    }
							}
						}

						echo	'<div class="room_info">
											<div class="row">
											<div class="col-md-3 col-sm-3">
											<div><a href="javascript:;"><img src="'.base_url().$photo.'" class="img-responsive" alt=""></a></div>
											</div>
											<div class="col-md-6 col-sm-6">
											<h4>'.$room_name.'</h4>
											<p> '.$room_value['member_count'].' Member Count </p>
											<p>Number of bedroom : '.$room_value['existing_room_count']	.'</p>
											<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExampled_'.$room_value['room_id'].$i.'" aria-expanded="false" aria-controls="collapseExample"> Room Detail </button>
											</div>
											<div class="col-md-3 col-sm-3">
											<h6>Avg. per night</h6>
											<h2 class="change_price">
											<span>change price ?</span>
											<div class="inr_cont">
											<p>change price</p>
											<input class="form-control" name="Q2age" id="Q2age" step="10" min="20" max="'.$arr_result[$room_value['room_id']].'" value="'.$arr_result[$room_value['room_id']].'" required="" type="number">
											<p><button id="change_amount_'.$room_value['room_id'].$i.'" class="change_amount btn btn-primary"><i class="fa fa-check"></i></button> <button class="close_amount btn btn-default"><i class="fa fa-remove"></i></button></p>
											</div></h2>
											<h2 id="changed_price_'.$room_value['room_id'].$i.'" class="'.$currency.'">'.$currency.''.$arr_result[$room_value['room_id']].'</h2>
											<button type="button" data-rate="'.@$room_value['rate_types_id'].'" data-room="'.$room_value['room_id'].'" data-grand="'.$arr_result[$room_value['room_id']].'" data-night="'.$nights.'" data-amount="'.$arr_result[$room_value['room_id']].'" data-price="'.insep_encode($arr_result_day[$room_value['room_id']]).'" id="res_'.$room_value['room_id'].$i.'" onclick=book_this_room("'.$room_value['room_id'].$i.'")   class="btn btn-info "> Book This Room</button>
											<button type="button" style="visibility:hidden" id="detail_info" data-target="#myModal4" data-toggle="modal" class="btn btn-info"> Book This Room</button>
											</div>
											</div>
											<div class="row">
											<div class="col-md-12 col-sm-12">
											<div class="collapse" id="collapseExampled_'.$room_value['room_id'].$i.'">
											<div class="more-info" style="display: block;">
											<div class="miProperties">    <div class="miProperties">
											<span class="detailtitle"><b>Description</b></span><br><span class="descDetail">
											'.$room_value['description'].'</span>
											</div>
											<div class="clear20"></div>
											</div>
											<div class="miSummary">
											<ul>
											<li>Check-in date<span>'.$checkin_date.'</span></li>
											<li>Check-out date<span> '.$checkout_date.'</span></li>
											<div class="clear10"></div>
											<li>Rooms<span>'.$rooms.'</span></li>
											<li>Guests<span>'.$adult.'</span></li>
											<li>Nights<span>'.$nights.'</span></li>
											<div class="clear10"></div>
											<li><b>Total</b><span><b> '.$currency.''.$arr_result[$room_value['room_id']].'</b></span></li>
											</ul>
											</div>
											<div class="clear"></div>
											<div class="clear15"></div>
											</div>
											</div>
											</div>
											</div>
											<div class="bor-dash mar-bot20"></div>
								</div>';

					}
					array_push($dis_room_id,$room_value['room_id']);
					array_push($dis_rate_id,@$room_value['rate_types_id']);
				}
				echo "~~~".$nights."~~~".$prev;
			}
			else
			{
				echo '<div class="room_info">
						<div class="row" style="padding:30px;"> No Rooms are available..</div></div>';
				echo "~~~".$nights."~~~".$prev;
			}
		}
	}
}

function reservation_price()
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
		extract($this->input->post());
		/* echo '<pre>';
		print_r($this->input->post());
		die; */
		$data['price']  = $this->security->xss_clean($price);
		$data['night'] = $nights;
		if($nights==1)
		{
			$data['nights'] = 'Night';
		}
		else
		{
			$data['nights'] = 'Nights';
		}
		$data['amount'] 				=	$amount;
		$data['price_day'] 				=	$price_day;
		$data['room_id'] 				=	$room_id;
		$data['rate_type_id']			=	$rate_type_id;
		$this->load->view('channel/reservation_price',$data);
	}
}

function save_reservation()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	//echo 'cc';

	$pay_error['result']='';
	if($this->input->get('payment_type')=='cash')
	{
		$transaction_id			=	'';
		$save_reserve			=	$this->reservation_model->save_reservation($transaction_id);
		$pay_error['result']	=	"0";
	}

	if($this->input->get('payment_type')!='pp'){

		if($pay_error['result']=='0')
		{
			if($save_reserve=="overbook")
			{
				$pay_error['message']	=	 '<div class="row" align="center">
					  <div class="co-md-12 col-sm-12">
						 <h5>You are in over booking.Try booking another day</h5>
					</div></div>';
			}

			if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
				$user_id		=	current_user_type();
			}

			$get_reservation	=	$this->reservation_model->get_reservation_details($save_reserve);

			$cancel_details	 	=	get_data(PCANCEL,array('user_id'=>$user_id))->row();

			$other_details 		=	get_data(POTHERS,array('user_id'=>$user_id))->row();



			if($other_details->smoking==1)
			{
				$smoke = 'Smoking is allowed';
			}
			else if($other_details->smoking==0)
			{
				$smoke = 'Smoking is not allowed.';
			}
			if($other_details->smoking==1)
			{
				$pets = 'Pets are allowed';
			}
			else if($other_details->smoking==0)
			{
				$pets = 'No pets allowed';
			}



			foreach ($get_reservation as $reser)
			{
				$room_name = get_data(TBL_PROPERTY,array("property_id"=>$reser->room_id))->row()->property_name;
				$user_details = get_data(TBL_USERS,array('user_id'=>$user_id))->row_array();
				$username = ucfirst($user_details['fname']).' '.ucfirst($user_details['lname']);




	                $add = 0;
	                $total_extras_result = $this->reservation_model->total_extras_result(0,$reser->reservation_id);
	                if($total_extras_result){
		                foreach($total_extras_result as $total){
		                $total_ex = $total->amount;
		                $add = $total_ex + $add;
		                }
	                }

                     $add = $add +($reser->price*$reser->num_rooms);

				$message = "Location:Manual Reservation,Reservation Id:".$reser->reservation_code.", Name:".ucfirst($reser->guest_name).", Check In Date:".$reser->start_date.", Check Out Date:".$reser->end_date.", Room:".$room_name.", Price:".$reser->price.", Booking Status:New Booking, IP:".$this->input->ip_address()." User:".$username;

				$this->inventory_model->write_log($message);

				$pay_error['message']	=	 ' <div class="row" align="center">
				<div class="co-md-12 col-sm-12">

				<h5> Thank you '.ucfirst($reser->guest_name).' your order is complete!
				Your confirmation number is #'.$reser->reservation_code.' </h5>

				<table class="summaryTable">
				<tbody>
				<tr>
				<th>
				Confirmation number
				</th>
				<td>
				<b>'.$reser->reservation_code.'</b>
				</td>
				</tr>
				<tr>
				<th>
				No.of Rooms
				</th>
				<td>
				'.$reser->num_rooms.'
				</td>
				</tr>
				<tr>
				<th>
				No.of Adult
				</th>
				<td>
				'.$reser->members_count.'
				</td>
				</tr>
				<tr>
				<th>
				No.of Child
				</th>
				<td>
				'.$reser->children.'
				</td>
				</tr>
				<tr>
				<th>
				Room Type
				</th>
				<td>
				'.$room_name.'
				</td>
				</tr>
				<tr>
				<th>
				Check-in date
				</th>
				<td>
				'.$reser->start_date.'
				</td>
				</tr>
				<tr>
				<th>
				Check-out date
				</th>
				<td>
				'.$reser->end_date.'
				</td>
				</tr>

				<tr>
				<th>
				No.of Nights
				</th>
				<td>
				'.$reser->num_nights.'
				</td>
				</tr>

				<tr>
				<th>
				Arrival Time
				</th>
				<td>
				'.$reser->arrivaltime.'
				</td>
				</tr>

				<tr>
				<th>
				Order Total
				</th>
				<td>
				'.$add.'
				</td>
				</tr>
				</tbody>

				</table>
				<p>&nbsp;</p>
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

				<th>Smoking</th>

				<td>'.$smoke.'.</td>

				</tr>

				<tr>

				<th>Pets</th>

				<td>'.$pets.'</td>

				</tr>

				</tbody>
				</table>


				</div>

				</div>
				<button> Close </button> ';
			}

			echo json_encode($pay_error);
			return;
		}
		else
		{
			echo json_encode($pay_error);
			return;
		}
	}

	echo json_encode($pay_error);
	return;
}



	function add_extras()
	{

		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
	    	if($this->security->xss_clean($this->input->post('add')))
			{
				$reser_id = insep_encode($this->input->post('reservation_id'));

				$curr_cha_id = $this->input->post('curr_cha_id');

				$data['page_heading'] = 'ReservationOrder';

				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

				$data= array_merge($user_details,$data);

				$result = $this->reservation_model->add_extras($reser_id);

				if($result)
				{
					$this->session->set_flashdata('success', 'Extras Added successfully.');
				}
				else
				{
					$this->session->set_flashdata('error', 'Not Updated.');

				}
				if(unsecure($curr_cha_id)==0)
				{
					redirect('reservation/reservation_order/'.$reser_id,'refresh');
				}
				else if(unsecure($curr_cha_id)==11)
				{
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}else if(unsecure($curr_cha_id)==8)
				{
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}elseif (unsecure($curr_cha_id) == 1) {
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}elseif (unsecure($curr_cha_id) == 2) {
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}
				elseif (unsecure($curr_cha_id) == 17) {
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}
				elseif (unsecure($curr_cha_id) == 15) {
					redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
				}
			}
			else
			{
				$data['page_heading'] = 'ReservationOrder';

				$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();

				$data= array_merge($user_details,$data);

				$this->views('channel/reservation_order',$data);
			}
		}
		else
		{
			redirect(base_url());
		}
	}



	function get_extras()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		// echo 'dddsf';die;

		if($this->input->post('extra_id')!=''){

		$extra_id = ($this->input->post('extra_id'));

		$data['page_heading'] = 'ReservationOrder';

		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

		$data= array_merge($user_details,$data);

		$data['result'] = $this->reservation_model->get_extras_id($extra_id);

		$this->load->view('channel/edit_extra',$data);

		}else{

			$data['page_heading'] = 'ReservationOrder';

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

			$data= array_merge($user_details,$data);

			$this->views('channel/reservation_order',$data);

		}

	 }



	function edit_extras()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->security->xss_clean($this->input->post('save')))
		{
			$reser_id = insep_encode($this->input->post('reservation_id'));

			$curr_cha_id = $this->input->post('curr_cha_id');

			$data['page_heading'] = 'ReservationOrder';

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

			$data= array_merge($user_details,$data);

			$result = $this->reservation_model->edit_extras($reser_id);

			if($result)
			{
				$this->session->set_flashdata('success', 'Extras Updated successfully.');
			}
			else
			{
				$this->session->set_flashdata('error', 'Not Updated.');
			}
			if(unsecure($curr_cha_id)==0)
			{
				redirect('reservation/reservation_order/'.$reser_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==11)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==1)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reser_id,'refresh');
			}
		}
		else
		{
			$data['page_heading'] = 'ReservationOrder';

			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

			$data= array_merge($user_details,$data);

			$this->views('channel/reservation_order',$data);
		}
	}



	 function delete_extra(){

	 	if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

	 	if($this->input->post('extra_id')!=''){

	 		$reser_id = $this->input->post('reservation_id');

	 		$description = $this->security->xss_clean($this->input->post('description'));

	 		$extra_id = $this->input->post('extra_id');

	 		$channel_id=$this->input->post('channelId');

			$result = $this->reservation_model->delete_extras($extra_id,$reser_id,$channel_id,$description);

			if($result){
				$data['type']='success';
				$data['message']='Extras Deleted successfully.';
			}
			else{

				$data['type']='error';
				$data['message']='Not Updated.';
			}

			echo json_encode($data);

	 	}



	 }



 	function update_notes()
 	{

	 	if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

	 	if($this->input->post('reservation_id')!='')
		{

			$reservation_id = $this->input->post('reservation_id');

			$description = $this->security->xss_clean($this->input->post('user_note'));
			if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()))
			{
				$result = $this->reservation_model->update_notes($reservation_id,$description);
			}
			else
			{
				redirect(base_url());
			}
			if($result)
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

	function update_notes_channel()
 	{

	 	if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

	 	if($this->input->post('reservation_id')!='')
		{

			$reservation_id = $this->input->post('reservation_id');

			$description = $this->security->xss_clean($this->input->post('user_note'));

			$ChannelID=$this->input->post('ChannelID');


			if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()))
			{

				$result = $this->reservation_model->update_notes_channel($reservation_id,$description,$ChannelID);

				/*
				$history = array('channel_id'=>2,'reservation_id'=>$reservation_id,'description'=>"Modify Notes",'amount'=>'','extra_date'=>date('Y-m-d'),'extra_id'=>'0','history_date'=>date('Y-m-d H:i:s'),'Username'=>$username);

            	$res = $this->db->insert('new_history',$history); */
			}
			else
			{
				redirect(base_url());
			}
			if($result)
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
         // 28/01/2016..
	function insert_payment($id=null)
	{
		$this->is_login();
		$id = insep_decode($id);
		extract($this->input->post());
		$result = $this->reservation_model->insert_payment($id);
		if($result)
		{
			$this->session->set_flashdata('success', 'Payment Added successfully.');
		}
		else
		{
				$this->session->set_flashdata('error', 'Error Occured while Adding Payment.');
		}
		if(unsecure($curr_cha_id)=='0')
		{
			redirect('reservation/reservation_order/'.insep_encode($id),'refresh');
		}
		elseif(unsecure($curr_cha_id)=='11')
		{
			redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.insep_encode($id),'refresh');
		}
		elseif(unsecure($curr_cha_id)=='1')
		{
			redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.insep_encode($id),'refresh');
		}elseif(unsecure($curr_cha_id)=='8')
		{
			redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.insep_encode($id),'refresh');
		}elseif(unsecure($curr_cha_id)=='2')
		{
			redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.insep_encode($id),'refresh');
		}
		elseif(unsecure($curr_cha_id)=='17')
		{
			redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.insep_encode($id),'refresh');
		}
	}

	function delete_payment()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || (admin_id()!='' && admin_type()=='1') || user_type()=='2' && in_array('2',user_edit()))
		{
			if($this->input->post('payment_id')!='')
			{
				$payment_id = $this->input->post('payment_id');
				$reservation_id = $this->input->post('reservation_id');
				$result = $this->reservation_model->delete_payment($payment_id);
				if($result)
				{
					echo '1';
				}
				else
				{
					echo '0';
				}
			}
		}
		else
		{
			echo '0';
		}
	}

	function sendMail()
    {
		$config = Array(
							'protocol' => 'smtp',

							'smtp_host' => 'ssl://smtp.googlemail.com',

							'smtp_port' => 465,

							'smtp_user' => 'krishna@osiztechnologies.com', // change it to yours

							'smtp_pass' => 'fool@1234', // change it to yours

							'mailtype' => 'html',

							'charset' => 'iso-8859-1',

							'wordwrap' => TRUE
						);

		$message = '';

		$this->load->library('email', $config);

		$this->email->set_newline("\r\n");

		$this->email->from('vijikumar.job@gmail.com'); // change it to yours

		$this->email->to('vijikumar.job@gmail.com');// change it to yours

		$this->email->subject('Resume from JobsBuddy for your Job posting');

		$this->email->message($message);

		if($this->email->send())
		{
			echo 'Email sent.';
		}
		else
		{
			show_error($this->email->print_debugger());
		}
    }

	function invoice_create($channel_id,$reservation_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			$data['page_heading'] = 'Create Invoice';
			$data['curr_cha_id'] = $channel_id;
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$reservation_details = array();
			$data= array_merge($user_details,$data);
			if(unsecure($channel_id)==0)
			{
				$reservation_details = get_data(RESERVATION,array('reservation_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['reservation_id'];
				$data['guest_name'] = $reservation_details['guest_name'];
				$data['email'] = $reservation_details['email'];
				$data['street_name'] = $reservation_details['street_name'];
				$data['city_name'] = $reservation_details['city_name'];
				$data['country'] = $reservation_details['country'];
				$data['start_date'] = $reservation_details['start_date'];
				$data['end_date'] = $reservation_details['end_date'];
				$data['num_nights'] = $reservation_details['num_nights'];
				$data['price'] = $reservation_details['price'];
				$data['num_rooms'] = $reservation_details['num_rooms'];
			}
			else if(unsecure($channel_id)==11)
			{
				$reservation_details = get_data(REC_RESERV,array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['FIRSTNAME'];
				$data['email'] = $reservation_details['EMAIL'];
				$data['street_name'] = $reservation_details['ADDRESS1'];
				$data['city_name'] = $reservation_details['CITY'];
				$data['country'] = $reservation_details['COUNTRY'];
				$data['start_date'] = $reservation_details['CHECKIN'];
				$data['end_date'] = $reservation_details['CHECKOUT'];
				$checkin=date('Y/m/d',strtotime($reservation_details['CHECKIN']));
				$checkout=date('Y/m/d',strtotime($reservation_details['CHECKOUT']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $reservation_details['CORRRATE'];
				$data['cha_currency'] = $reservation_details['CURRENCY'];
			}
			else if(unsecure($channel_id) == 1)
			{
				$reservation_details = get_data('import_reservation_EXPEDIA',array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['name'];
				$data['email'] = $reservation_details['Email'];
				$data['street_name'] = $reservation_details['address'];
				$data['city_name'] = $reservation_details['city'];
				$data['country'] = $reservation_details['country'];
				$data['start_date'] = $reservation_details['arrival'];
				$data['end_date'] = $reservation_details['departure'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrival']));
				$checkout=date('Y/m/d',strtotime($reservation_details['departure']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['totalprice'] = $reservation_details['amountAfterTaxes'] - $reservation_details['amountOfTaxes'];
				$tax = ($reservation_details['amountOfTaxes'] *  100) / $data['totalprice'];
				$data['tax'] = $tax;
				$data['price'] = $reservation_details['amountAfterTaxes'];

				$data['cha_currency'] = $reservation_details['currency'];
			}
			else if(unsecure($channel_id)==8)
			{
				$reservation_details = get_data("import_reservation_GTA",array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['leadname'];
				$data['email'] = "";
				$data['street_name'] ="";
				$data['city_name'] = $reservation_details['city'];
				$data['country'] = "";
				$data['start_date'] = $reservation_details['arrdate'];
				$data['end_date'] = $reservation_details['depdate'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrdate']));
				$checkout=date('Y/m/d',strtotime($reservation_details['depdate']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $reservation_details['totalcost'];
				$data['totalprice'] = $reservation_details['totalcost'];
				$data['cha_currency'] = $reservation_details['currencycode'];
			}
			else if(unsecure($channel_id)== 5)
			{
				$reservation_details = get_data("import_reservation_HOTELBEDS",array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$totamount = 0;
				$room_id = "";
				$adult = 0;
				$child = 0;
				if(strpos($reservation_details['Room_code'], ',') !== FALSE){
					$h_roomname = explode(',', $reservation_details['Room_code']);
					$h_adult = explode(',', $reservation_details['AdultCount']);
					$h_child = explode(',', $reservation_details['ChildCount']);
					$h_baby = explode(',', $reservation_details['BabyCount']);
					$h_char = explode(',', $reservation_details['CharacteristicCode']);
					$h_status = explode(',', $reservation_details['RoomStatus']);
					$h_name = explode('~', $reservation_details['Customer_Name']);
					$h_cont = explode(',', $reservation_details['Contract_Name']);
					$h_arrival = explode(',', $reservation_details['DateFrom']);
					$h_depar = explode(',', $reservation_details['DateTo']);
					$h_amount = explode(',', $reservation_details['Amount']);
					$h_currency = explode(',', $reservation_details['Currency']);

					for($i=0; $i<count($h_roomname); $i++){

						$totamount = $totamount + $h_amount[$i];
						$adult = $adult + $h_adult[$i];
						$child = $child + $h_child[$i] + $h_baby[$i];

					}
					$checkin = $h_arrival[0];
					$checkout = $h_depar[0];
					$currency = $h_currency[0];
					$name = str_replace('~', '', $reservation_details['Customer_Name']);
				}else{
					$totamount = $reservation_details['Amount'];
					$adult = $reservation_details['AdultCount'];
					$child = $reservation_details['ChildCount'] + $reservation_details['BabyCount'];
					$checkin = $reservation_details['DateFrom'];
					$checkout = $reservation_details['DateTo'];
					$currency = $reservation_details['Currency'];
					$name = $reservation_details['Customer_Name'];
				}
				$data['guest_name'] = rtrim($name,',');
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = "";
				$data['country'] = "";
				$data['start_date'] = $checkin;
				$data['end_date'] = $checkout;
				$checkin=date('Y/m/d',strtotime($checkin));
				$checkout=date('Y/m/d',strtotime($checkout));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $totamount;
				$data['cha_currency'] = $currency;

			}
			else if(unsecure($channel_id) == 2)
			{
				$reservation_details = get_data('import_reservation_BOOKING_ROOMS',array('room_res_id'=>insep_decode($reservation_id)))->row_array();

				$booking_details = get_data('import_reservation_BOOKING', array('id' => $reservation_details['reservation_id']))->row_array();
				$data['reservation_id'] = $reservation_details['room_res_id'];
				$data['guest_name'] = $reservation_details['guest_name'];
				$data['email'] = $booking_details['email'];
				$data['street_name'] = $booking_details['address'];
				$data['city_name'] = $booking_details['city'];
				$data['country'] = $booking_details['countrycode'];
				$data['start_date'] = $reservation_details['arrival_date'];
				$data['end_date'] = $reservation_details['departure_date'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrival_date']));
				$checkout=date('Y/m/d',strtotime($reservation_details['departure_date']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = 1;
				$data['totalprice'] = $reservation_details['totalprice'];

				$data['price'] = $reservation_details['totalprice'];

				$data['cha_currency'] = $reservation_details['currencycode'];
			}
			else if(unsecure($channel_id) == 17)
			{
				$reservation_details	=	$this->bnow_model->invoiceCreate($reservation_id);
				$data	=	$reservation_details;
			}
			else if(unsecure($channel_id) == 15)
			{
				$reservation_details	=	$this->travel_model->invoiceCreate($reservation_id);
				$data	=	$reservation_details;
			}
			if(unsecure($channel_id)==8)
			{
				$data['hotel_details']  = get_data(HOTEL,array('hotel_id'=>$reservation_details['hotelid']))->row_array();
				$data['user_details']   = get_data(TBL_USERS,array('user_id'=>$reservation_details['user_id']))->row_array();
				$data['bill_details']	= get_data(BILL,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row_array();
			}
			elseif(count($reservation_details)!=0)
			{
				$data['hotel_details']  = get_data(HOTEL,array('hotel_id'=>$reservation_details['hotel_id']))->row_array();
				$data['user_details']   = get_data(TBL_USERS,array('user_id'=>$reservation_details['user_id']))->row_array();
				$data['bill_details']	= get_data(BILL,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row_array();
			}
			else
			{
				redirect('reservation/reservationlist','refresh');
			}
			$data['invoice_number'] = '0H'.rand(0,9999999);
			$data['extra_count'] = $this->reservation_model->extra_count(unsecure($channel_id),insep_decode($reservation_id));
			if($data['extra_count']!=0)
			{
				$data['extra'] = $this->reservation_model->get_extras(unsecure($channel_id),insep_decode($reservation_id));
			}
			$this->load->view('channel/invoice_create',$data);
		}
		else
		{
			redirect('reservation/reservation_order/'.$reservation_id,'refresh');
		}
	}

	function welcome_email()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			extract($this->input->post());
			$channel_id = unsecure($curr_cha_id);
			$available = get_data(WE,array('channel_id'=>$channel_id,'mail_type'=>1,'reservation_id'=>insep_decode($reservation_id)))->row_array();

			//mysql_real_escape_string($email_message);
			if(count($available)=='0')
			{
				$data['user_email'] = $this->security->xss_clean($user_email);
				$data['email_title'] = $this->security->xss_clean($email_title);
				$data['email_message'] = $this->security->xss_clean($email_message);
				$data['copy_message'] = implode(',',$this->security->xss_clean($copy_message));
				$data['remainder_days'] = '0';
				$data['reservation_id'] = insep_decode($reservation_id);
				$data['channel_id'] = $channel_id;
				$data['mail_type'] = '1';
				if(insert_data(WE,$data))
				{
					$this->reservation_model->welcome_mail($reservation_id);
				}
			}
			else
			{
				$data['user_email'] = $this->security->xss_clean($user_email);
				$data['email_title'] = $this->security->xss_clean($email_title);
				$data['email_message'] = $this->security->xss_clean($email_message);
				$data['copy_message'] = implode(',',$this->security->xss_clean($copy_message));
				$data['remainder_days'] = '0';

				if(update_data(WE,$data,array('channel_id'=>$channel_id,'mail_type'=>1,'reservation_id'=>insep_decode($reservation_id))))
				{
					 $this->reservation_model->welcome_mail($reservation_id);
				}
			}
			if(unsecure($curr_cha_id)==0)
			{
				redirect('reservation/reservation_order/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==11)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}else if(unsecure($curr_cha_id)==1)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}else if(unsecure($curr_cha_id)==8)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==2)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==17)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==15)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
		}
		else
		{
			redirect(base_url());
		}

	}

	function reminder_email()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			extract($this->input->post());
			/*echo '<pre>';
			print_r($this->input->post());
			exit;*/
			$channel_id = unsecure($curr_cha_id);
			if($mail_type=='save')
			{
				$available = get_data(WE,array('channel_id'=>$channel_id,'mail_type'=>2,'reservation_id'=>insep_decode($reservation_id)))->row_array();
				if(count($available)=='0')
				{
					$data['user_email'] = $this->security->xss_clean($user_email);
					$data['email_title'] = $this->security->xss_clean($email_title);
					$data['email_message'] = $this->security->xss_clean($email_message);
					$data['copy_message'] = implode(',',$this->security->xss_clean($copy_message));
					$data['remainder_days'] = $remainder_days;
					$data['reservation_id'] = insep_decode($reservation_id);
					$data['channel_id'] = $channel_id;
					$data['mail_type'] = '2';
					if(insert_data(WE,$data))
					{
					}
				}
				else
				{
					$data['user_email'] = $this->security->xss_clean($user_email);
					$data['email_title'] = $this->security->xss_clean($email_title);
					$data['email_message'] = $this->security->xss_clean($email_message);
					$data['copy_message'] = implode(',',$this->security->xss_clean($copy_message));
					$data['remainder_days'] = $remainder_days;
					if(update_data(WE,$data,array('channel_id'=>$channel_id,'mail_type'=>2,'reservation_id'=>insep_decode($reservation_id))))
					{
					}
				}
			}
			else if($mail_type=='send')
			{
				// $this->reservation_model->remainder_mail();
			}
			if(unsecure($curr_cha_id)==0)
			{
				redirect('reservation/reservation_order/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==11)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==1)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==8)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==2)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==17)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
			else if(unsecure($curr_cha_id)==15)
			{
				redirect('reservation/reservation_channel/'.$curr_cha_id.'/'.$reservation_id,'refresh');
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	function invoice_all_country()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$all_hotel = get_data(TBL_COUNTRY)->result_array();
		if(count($all_hotel)!=0)
		{
			$coun='';
			 foreach($all_hotel as $hotel)
			 {
				   extract($hotel);
				   $ho_name[$id]=$country_name;
			 }
			 echo json_encode($ho_name);
		  }
	}

	function edit_guest_details()
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

		if($this->security->xss_clean($name)=='guest_name')
		{
			$data['guest_name'] = $value;
		}
		else if($this->security->xss_clean($name)=='email')
		{
			$data['email'] = $value;
		}
		else if($this->security->xss_clean($name)=='street_name')
		{
			$data['street_name'] = $value;
		}
		else if($this->security->xss_clean($name)=='city_name')
		{
			$data['city_name'] = $value;
		}
		else if($this->security->xss_clean($name)=='country')
		{
			$data['country'] = $value;
		}
		if(update_data(RESERVATION,$data,array('reservation_id'=>$pk)))
		{
			echo '1';
		}
		else
		{
			echo '2';
		}
	}

	function add_ex_invoice()
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
/*		echo '<pre>';
		print_r($this->input->post());
		die;*/
		for($i=0; $i<=$in_val;$i++)
		{
			if($this->security->xss_clean($this->input->post('eitemName_'.$i))!='' && $this->security->xss_clean($this->input->post('etotal_'.$i))!='')
			{
				$data['reservation_id'] = insep_decode($this->input->post('reservation_id'));
				$data['description']    = $this->security->xss_clean($this->input->post('eitemName_'.$i));
				$data['amount']    		= $this->security->xss_clean($this->input->post('etotal_'.$i));
				$data['extra_date']    	= date('Y-m-d H:i:s');
				$data['channel_id']		= unsecure($curr_cha_id);
				insert_data(EX,$data);

				$dhata['reservation_id'] = insep_decode($this->input->post('reservation_id'));
				$dhata['description']    = $this->security->xss_clean($this->input->post('eitemName_'.$i));
				$dhata['amount']    		= $this->security->xss_clean($this->input->post('etotal_'.$i));
				$dhata['extra_date']    = date('Y-m-d H:i:s');
				$dhata['history_date']    = date('Y-m-d H:i:s');
				$dhata['channel_id']		= unsecure($curr_cha_id);
				insert_data('new_history',$dhata);

			}
		}
		$available = get_data(IN,array('channel_id'=>unsecure($curr_cha_id),'reservation_id'=>insep_decode($reservation_id)))->row_array();
		if(count($available)=='0')
		{
			$indata['reservation_id'] 	= insep_decode($this->input->post('reservation_id'));
			$indata['invoice_id'] 		= ($this->input->post('invoice'));
			$indata['created']    	  	= date('Y-m-d H:i:s');
			$indata['channel_id']		= unsecure($curr_cha_id);
			insert_data(IN,$indata);
		}
		redirect('reservation/invoice_edit/'.$curr_cha_id.'/'.$this->input->post('reservation_id'),'refresh');
	}

	function invoice_edit($channel_id,$reservation_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			$data['page_heading'] = 'Edit Invoice';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$reservation_details = array();
			$data= array_merge($user_details,$data);
			$data['curr_cha_id'] = $channel_id;
			if(unsecure($channel_id)==0)
			{
				$reservation_details 	=	get_data(RESERVATION,array('reservation_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = 	$reservation_details['reservation_id'];
				$data['guest_name'] 	= 	$reservation_details['guest_name'];
				$data['email'] 			= 	$reservation_details['email'];
				$data['street_name'] 	= 	$reservation_details['street_name'];
				$data['city_name'] 		= 	$reservation_details['city_name'];
				$data['country'] 		= 	$reservation_details['country'];
				$data['start_date'] 	= 	$reservation_details['start_date'];
				$data['end_date'] 		= 	$reservation_details['end_date'];
				$data['num_nights'] 	= 	$reservation_details['num_nights'];
				$data['price'] 			= 	$reservation_details['price'];
				$data['num_rooms'] 		= 	$reservation_details['num_rooms'];


			}
			else if(unsecure($channel_id)==11)
			{
				$reservation_details = get_data(REC_RESERV,array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['FIRSTNAME'];
				$data['email'] = $reservation_details['EMAIL'];
				$data['street_name'] = $reservation_details['ADDRESS1'];
				$data['city_name'] = $reservation_details['CITY'];
				$data['country'] = $reservation_details['COUNTRY'];

				$data['start_date'] = $reservation_details['CHECKIN'];
				$data['end_date'] = $reservation_details['CHECKOUT'];
				$checkin=date('Y/m/d',strtotime($reservation_details['CHECKIN']));
				$checkout=date('Y/m/d',strtotime($reservation_details['CHECKOUT']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $reservation_details['CORRRATE'];
				$data['cha_currency'] = $reservation_details['CURRENCY'];
			}
			else if(unsecure($channel_id)==1)
			{
				$reservation_details = get_data('import_reservation_EXPEDIA',array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['name'];
				$data['email'] = $reservation_details['Email'];
				$data['street_name'] = $reservation_details['address'];
				$data['city_name'] = $reservation_details['city'];
				$data['country'] = $reservation_details['country'];

				$data['start_date'] = $reservation_details['arrival'];
				$data['end_date'] = $reservation_details['departure'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrival']));
				$checkout=date('Y/m/d',strtotime($reservation_details['departure']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $reservation_details['amountAfterTaxes'];
				$tax = ($reservation_details['amountOfTaxes'] *  100) / $reservation_details['amountAfterTaxes'];
				$data['tax'] = round($tax);
				$data['totalprice'] = $reservation_details['amountAfterTaxes'] - $reservation_details['amountOfTaxes'];
				$data['cha_currency'] = $reservation_details['currency'];
			}
			else if(unsecure($channel_id)==8)
			{
				$reservation_details = get_data("import_reservation_GTA",array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();

				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $reservation_details['leadname'];
				$data['email'] = "";
				$data['street_name'] ="";
				$data['city_name'] = $reservation_details['city'];
				$data['country'] = "";

				$data['start_date'] = $reservation_details['arrdate'];
				$data['end_date'] = $reservation_details['depdate'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrdate']));
				$checkout=date('Y/m/d',strtotime($reservation_details['depdate']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $reservation_details['totalcost'];
				$data['totalprice'] = $reservation_details['totalcost'];
				$data['cha_currency'] = $reservation_details['currencycode'];


			}
			else if(unsecure($channel_id)== 5)
			{
				$reservation_details = get_data("import_reservation_HOTELBEDS",array('import_reserv_id'=>insep_decode($reservation_id)))->row_array();
				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$totamount = 0;
				$room_id = "";
				$adult = 0;
				$child = 0;
				if(strpos($reservation_details['Room_code'], ',') !== FALSE){
					$h_roomname = explode(',', $reservation_details['Room_code']);
					$h_adult = explode(',', $reservation_details['AdultCount']);
					$h_child = explode(',', $reservation_details['ChildCount']);
					$h_baby = explode(',', $reservation_details['BabyCount']);
					$h_char = explode(',', $reservation_details['CharacteristicCode']);
					$h_status = explode(',', $reservation_details['RoomStatus']);
					$h_name = explode('~', $reservation_details['Customer_Name']);
					$h_cont = explode(',', $reservation_details['Contract_Name']);
					$h_arrival = explode(',', $reservation_details['DateFrom']);
					$h_depar = explode(',', $reservation_details['DateTo']);
					$h_amount = explode(',', $reservation_details['Amount']);
					$h_currency = explode(',', $reservation_details['Currency']);

					for($i=0; $i<count($h_roomname); $i++){

						$totamount = $totamount + $h_amount[$i];
						$adult = $adult + $h_adult[$i];
						$child = $child + $h_child[$i] + $h_baby[$i];

					}
					$checkin = $h_arrival[0];
					$checkout = $h_depar[0];
					$currency = $h_currency[0];
					$name = str_replace('~', '', $reservation_details['Customer_Name']);
				}else{
					$totamount = $reservation_details['Amount'];
					$adult = $reservation_details['AdultCount'];
					$child = $reservation_details['ChildCount'] + $reservation_details['BabyCount'];
					$checkin = $reservation_details['DateFrom'];
					$checkout = $reservation_details['DateTo'];
					$currency = $reservation_details['Currency'];
					$name = $reservation_details['Customer_Name'];
				}
				$data['guest_name'] = rtrim($name,',');
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = "";
				$data['country'] = "";
				$data['start_date'] = $checkin;
				$data['end_date'] = $checkout;
				$checkin=date('Y/m/d',strtotime($checkin));
				$checkout=date('Y/m/d',strtotime($checkout));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['price'] = $totamount;
				$data['cha_currency'] = $currency;

			}
			else if(unsecure($channel_id) == 2)
			{
				$reservation_details = get_data('import_reservation_BOOKING_ROOMS',array('room_res_id'=>insep_decode($reservation_id)))->row_array();
				$booking_details = get_data('import_reservation_BOOKING', array('id' => $reservation_details['reservation_id']))->row_array();
				$data['reservation_id'] = $reservation_details['room_res_id'];
				$data['guest_name'] = $reservation_details['guest_name'];
				$data['email'] = $booking_details['email'];
				$data['street_name'] = $booking_details['address'];
				$data['city_name'] = $booking_details['city'];
				$data['country'] = $booking_details['countrycode'];
				$data['start_date'] = $reservation_details['arrival_date'];
				$data['end_date'] = $reservation_details['departure_date'];
				$checkin=date('Y/m/d',strtotime($reservation_details['arrival_date']));
				$checkout=date('Y/m/d',strtotime($reservation_details['departure_date']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = 1;
				$data['totalprice'] = $reservation_details['totalprice'];

				$data['price'] = $reservation_details['totalprice'];

				$data['cha_currency'] = $reservation_details['currencycode'];
			}
			else if(unsecure($channel_id) == 17)
			{
				$reservation_details	=	$this->bnow_model->invoiceCreate($reservation_id);
				$data	=	$reservation_details;
			}
			else if(unsecure($channel_id) == 15)
			{
				$reservation_details	=	$this->travel_model->invoiceCreate($reservation_id);
				$data	=	$reservation_details;
			}
			if(count($reservation_details)!=0)
			{
				$data['hotel_details']  = get_data(HOTEL,array('hotel_id'=>$reservation_details['hotel_id']))->row_array();
				$data['user_details']   = get_data(TBL_USERS,array('user_id'=>$reservation_details['user_id']))->row_array();
				$data['bill_details']	= get_data(BILL,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row_array();
			}
			else
			{
				redirect('reservation/reservationlist','refresh');
			}
			$data['invoice_number'] = get_data(IN,array('reservation_id'=>insep_decode($reservation_id)))->row()->invoice_id;
			$data['extra_count'] = $this->reservation_model->extra_count(unsecure($channel_id),insep_decode($reservation_id));
			if($data['extra_count']!=0)
			{
				$data['extra'] = $this->reservation_model->get_extras(unsecure($channel_id),insep_decode($reservation_id));
			}
			$this->load->view('channel/invoice_create',$data);
		}
		else
		{
			redirect('reservation/reservation_order/'.$reservation_id,'refresh');
		}
	}

	function invoice_delete()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$invoice_id = $this->input->post('invoice_id');
		if($invoice_id!='')
		{
			$reservation_id = $this->input->post('reservation_id');
			$result = $this->reservation_model->invoice_delete($invoice_id);
			if($result)
			{
				$this->session->set_flashdata('success','Invoice Details Deleted Successfully');
				redirect('reservation/reservation_order/'.insep_encode($reservation_id),'refresh');
			}
			else
			{
				$this->session->set_flashdata('error','Error Occured While deleting Invoice details');
				redirect('reservation/reservation_order/'.insep_encode($reservation_id),'refresh');
			}
		}
	}

	function update_reservation()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$reservation_id = $this->input->post('reservation_id');
		if($reservation_id!='')
		{
		$reservation_id = $this->input->post('reservation_id');
		$result = $this->reservation_model->update_reservation();
		if($result)
		{
			$this->session->set_flashdata('success','Reservation Updated Successfully');
			redirect('reservation/reservation_order/'.insep_encode($reservation_id),'refresh');
		}
		else
		{
			$this->session->set_flashdata('error','Error Occured while Updating Reservation');
			redirect('reservation/reservation_order/'.insep_encode($reservation_id),'refresh');
		}

	}

	}





 function save_carddetails(){
    	$save=$this->reservation_model->save_card();
        echo "ok";
    }
function password_check()
{


	if(admin_id()!='' && admin_type()=='1')
	{
		$passwod = get_data('manage_admin_details',array('id'=>admin_id(),'user_type'=>admin_type()))->row()->password;
		if($passwod == $_REQUEST['password'])
		{
			/*$this->db->where('user_id',user_id());
			$sql=$this->db->get('card_details')->result();
			echo json_encode($sql);*/
			$data = array();

			$channel = unsecure($this->input->post('channel_id'));
			$id = $this->input->post('resr_id');
			if($channel == 0){
				$this->db->where('manage_reservation.reservation_id',$id);
				$this->db->JOIN('card_details','card_details.resrv_id = manage_reservation.reservation_id');
				$reservation_channeldetails = $this->db->get('manage_reservation')->result_array();
				echo $this->db->last_query();
				$data['CC_NAME']			=	$reservation_channeldetails['name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['number'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['exp_month'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['exp_year'], -2);
				$data['URL'] = '';
			}
			if($channel==11)
			{
				$reservation_channeldetails = get_data('import_reservation_RECONLINE',array('IDRSV '=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['NAME'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['CCNUMBER'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], -2);
				$data['URL'] = '';

			}elseif($channel==8)
			{
				error_reporting(0);

				$reservation_channeldetails = get_data('import_reservation_GTA',array('booking_id '=>($id)))->row_array();

				$data['CC_NAME']			=	"";
				$data['CC_NUMBER']			=	"";
				$data['CC_DATE']			=	"";
				$data['CC_YEAR']			=	"";
				$data['URL'] = '';
			}
			elseif($channel==19)
			{

				$reservation_channeldetails = get_data('import_reservation_AGODA',array('booking_id'=>($id)))->row_array();
				$data['CC_NAME']			=	$reservation_channeldetails['card_name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['card_number'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['card_exp'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['card_exp'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['card_type'];
				$data['CC_CVC']             = $reservation_channeldetails['card_cvv'];
			}
			else if($channel==1)
			{
				$reservation_channeldetails = get_data('import_reservation_EXPEDIA',array('booking_id '=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['cardNumber'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['expireDate'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['expireDate'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['cardCode'];
				$data['CC_CVC']             = $reservation_channeldetails['SeriesCode'];
			}
			else if($channel== 5)
			{
				$reservation_channeldetails = get_data('import_reservation_HOTELBEDS',array('echoToken '=>($id)))->row_array();

				$data['CC_NAME']			=	"";
				$data['CC_NUMBER']			=	"";
				$data['CC_DATE']			=	"";
				$data['CC_YEAR']			=	"";
				$data['URL'] = '';

			}
			else if($channel==2)
			{
				$booking_table = get_data('import_reservation_BOOKING', array('id'=>$id))->row_array();


				$sign = hash('sha256','DpgdQJn5ZPOeZnjRmhazUWbZLWN6KbvKdGkLOolCTlOoWArY2L'.'3000011638'.safe_b64decode($booking_table['cc_number']));
				$data['CC_NAME']			=	safe_b64decode($booking_table['cc_name']);
				$data['CC_DATE']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), 0,2);
				$data['CC_YEAR']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), -2);
				$data['CC_CVC']             =   safe_b64decode($booking_table['cc_cvc']);
				$data['CC_TYPE']            =   safe_b64decode($booking_table['cc_type']);
				if (strlen(safe_b64decode($booking_table['cc_number']))>5)
				{
					$data['URL'] = 'https://payment.datatrans.biz/upp/jsp/noShow.jsp?merchantId=3000011638&aliasCC='.safe_b64decode($booking_table['cc_number']).'&aliasCVV='.safe_b64decode($booking_table['cc_cvc']).'&sign='.$sign;
					require_once(APPPATH.'controllers/tokenex.php');
					$tokenex = new tokenex();
					$data['CC_NUMBER'] ='';
				}
				else
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode("Does Not Have");
					$data['CC_NUMBER']			=	'Not Credit Card';
				}


			}

				else if($channel==36)
			{
				$reservation_channeldetails = get_data('import_reservation_DESPEGAR',array('Resid_value'=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['cardNumber'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['expireDate'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['expireDate'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['CardType'];
				if(strlen($reservation_channeldetails['cardCode'])>1)
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode($reservation_channeldetails['cardCode']);
				}
				else
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode("Does Not Have");
				}


			}
			echo json_encode($data);
		}
		else
		{
			echo 'no';
		}
	}
	else if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
	{
		$pass=$_REQUEST['password'];
    	$this->db->where('user_id',current_user_type());
        $res = $this->db->get('manage_users');
		$result = $res->row();
		$t_hasher = new PasswordHash(8, FALSE);
		$hash = $result->password;
		$check = $t_hasher->CheckPassword($pass, $hash);
		if($check)
		{
			$data = array();
			$channel = unsecure($this->input->post('channel_id'));
			$id = $this->input->post('resr_id');
			if($channel == 0){
				$this->db->where('resrv_id',$id);
				$reservation_channeldetails = $this->db->get('card_details')->row_array();
				$data['CC_NAME']			=	safe_b64decode($reservation_channeldetails['name']);
				$data['CC_NUMBER']			=	safe_b64decode($reservation_channeldetails['number']);
				$data['CC_DATE']			=	substr(safe_b64decode($reservation_channeldetails['exp_month']), 0,2);
				$data['CC_YEAR']			=	substr(safe_b64decode($reservation_channeldetails['exp_year']), -2);
				$data['CC_TYPE']            =   safe_b64decode($reservation_channeldetails['card_type']);
			}
			if($channel==11)
			{
				$reservation_channeldetails = get_data('import_reservation_RECONLINE',array('IDRSV '=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['NAME'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['CCNUMBER'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['CCEXPIRYDATE'], -2);

			}
			elseif($channel==8)
			{
				error_reporting(0);

				$reservation_channeldetails = get_data('import_reservation_GTA',array('booking_id '=>($id)))->row_array();

				$data['CC_NAME']			=	"";
				$data['CC_NUMBER']			=	"";
				$data['CC_DATE']			=	"";
				$data['CC_YEAR']			=	"";
			}
			else if($channel==1)
			{
				$reservation_channeldetails = get_data('import_reservation_EXPEDIA',array('booking_id '=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['cardNumber'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['expireDate'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['expireDate'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['cardCode'];
				$data['CC_CVC']             = $reservation_channeldetails['SeriesCode'];


			}
			else if($channel==19)
			{

				$reservation_channeldetails = get_data('import_reservation_AGODA',array('booking_id'=>$id))->row_array();
				$data['CC_NAME']			=	$reservation_channeldetails['card_name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['card_number'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['card_exp'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['card_exp'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['card_type'];
				$data['CC_CVC']             = $reservation_channeldetails['card_cvv'];
			}
			else if($channel==36)
			{
				$reservation_channeldetails = get_data('import_reservation_DESPEGAR',array('Resid_value'=>($id)))->row_array();

				$data['CC_NAME']			=	$reservation_channeldetails['name'];
				$data['CC_NUMBER']			=	$reservation_channeldetails['cardNumber'];
				$data['CC_DATE']			=	substr($reservation_channeldetails['expireDate'], 0,2);
				$data['CC_YEAR']			=	substr($reservation_channeldetails['expireDate'], -2);
				$data['CC_TYPE']            =   $reservation_channeldetails['CardType'];

				if(strlen($reservation_channeldetails['cardCode'])>1)
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode($reservation_channeldetails['cardCode']);
				}
				else
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode("Does Not Have");
				}

			}
			else if($channel== 5)
			{
				$reservation_channeldetails = get_data('import_reservation_HOTELBEDS',array('echoToken '=>($id)))->row_array();

				$data['CC_NAME']			=	"";
				$data['CC_NUMBER']			=	"";
				$data['CC_DATE']			=	"";
				$data['CC_YEAR']			=	"";

			}
			else if($channel==2)
			{
				$booking_table = get_data('import_reservation_BOOKING', array('id'=>$id))->row_array();


				$sign = hash('sha256','DpgdQJn5ZPOeZnjRmhazUWbZLWN6KbvKdGkLOolCTlOoWArY2L'.'3000011638'.safe_b64decode($booking_table['cc_number']));
				$data['CC_NAME']			=	safe_b64decode($booking_table['cc_name']);
				$data['CC_DATE']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), 0,2);
				$data['CC_YEAR']			=	substr(safe_b64decode($booking_table['cc_expiration_date']), -2);
				$data['CC_CVC']             =   safe_b64decode($booking_table['cc_cvc']);
				$data['CC_TYPE']            =   safe_b64decode($booking_table['cc_type']);
				if (strlen(safe_b64decode($booking_table['cc_number']))>5)
				{
					$data['URL'] = 'https://payment.datatrans.biz/upp/jsp/noShow.jsp?merchantId=3000011638&aliasCC='.safe_b64decode($booking_table['cc_number']).'&aliasCVV='.safe_b64decode($booking_table['cc_cvc']).'&sign='.$sign;
					require_once(APPPATH.'controllers/tokenex.php');
					$tokenex = new tokenex();
					$Valor=safe_b64decode($booking_table['cc_number']);
					//$data['CC_NUMBER']=(strlen($Valor)>20?safe_b64decode($booking_table['cc_number']): $tokenex->Detokenizar(safe_b64decode($Valor)));

					$data['CC_NUMBER']=(strlen($Valor)>20?$Valor: $tokenex->Detokenizar($Valor));


				}
				else
				{
					$data['URL'] = lang_url()."reservation/Showcvc/".safe_b64encode("Does Not Have");
					$data['CC_NUMBER']			=	'Not Credit Card';
				}


			}


			else if($channel==17)
			{
				$bnow_table = get_data(BNOW_RESER, array('ResID_Value'=>$id))->row_array();

				$data['CC_NAME']			=	$bnow_table['CardHolderName'];
				$data['CC_NUMBER']			=	safe_b64decode($bnow_table['CardNumber']);
				$data['CC_DATE']			=	substr(safe_b64decode($bnow_table['ExpireDate']), 0,2);
				$data['CC_YEAR']			=	substr(safe_b64decode($bnow_table['ExpireDate']), -2);
				$data['CC_CVC']             =   safe_b64decode($bnow_table['CardCode']);
				$data['CC_TYPE']            =   safe_b64decode($bnow_table['CardType']);
			}
			echo json_encode($data);

		}
		else
		{
			echo "no";
		}
	}
	else
	{
		echo 'noo';
	}
}


	  function Showcvc($cvc)
	  {		$data['cvc']=safe_b64decode($cvc);
	  		$this->load->view('channel/cvc',$data);
	  }
	  function reservation_today_count()
	  {
		  if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
		  $reservation_today_count= $this->reservation_model->reservationcounts('reserve');
		  ?>
          <span data-counter="counterup" data-value="<?php echo $reservation_today_count;?>" id="reservation_today_count"><?php echo $reservation_today_count;?></span>
          <?php
	  }
	  function cancelation_today_count()
	  {
		  if(admin_id()=='')
			{
				$this->is_login();
			}
			else
			{
				$this->is_admin();
			}
		  $cancel_today_count= $this->reservation_model->reservationcounts('cancel');
		  ?>
          <span data-counter="counterup" data-value="<?php echo $cancel_today_count;?>" id="reservation_today_count"><?php echo $cancel_today_count;?></span>
          <?php
	  }

       // 08/02/2016..
    function update_announcement()
    {
    	$note_id = $this->input->post('note_id');
		$udata['status']='seen';
		if(update_data('notifications',$udata,array('n_id'=>$note_id)));
    }
	function dashboard_modal_window()
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
		$data['view_modal']=$view_modal;
		$this->load->view('channel/dashboard_modal_window',$data);
	}

	function reservation_count()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$result = $this->reservation_model->reservationcounts('reserve');
		echo $result;
	}

	function new_reservation_count()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$this->reservation_model->reservationcounts('reserve');
		$this->load->view('channel/notification_reservation');
	}

	function cancellation_count()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$result = $this->reservation_model->reservationcounts('cancel');
		echo $result;
	}

	function new_cancellation_count()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$this->reservation_model->reservationcounts('cancel');
		$this->load->view('channel/notification_reservation');
	}
	function show_credit_card()
	{
		?>
        <div class="modal-body">
         <div class="form-group" >
           <input type="password" class="form-control " id="password_credit" name="password_credit" placeholder="Enter your password">
         <span id="err_msg" style="color:red"></span>
        </div>
         </div>
        <?php
	}

	/*Subbaiah Get Reservation Functionality Start*/

	function getHotelbedsReservation()
	{
		$cuando 	= $_POST['BMS_XML'];
		$cuando 	= urldecode($cuando);
		mail("xml@hoteratus.com"," Reservation Form Hotel Beds ",$cuando);
		$data_api 	= simplexml_load_string($cuando);
		/*echo '<pre>';
		print_r($data_api);
		die; */
		$attributes 				= 	$data_api->attributes();

		(string)$attributes['echoToken']!='' ? $data['echoToken'] = (string)$attributes['echoToken'] : $data['echoToken']='';

		(string)$attributes['timestamp']!='' ? $data['timestamp'] = (string)$attributes['timestamp'] : $data['timestamp']='';

		$Establishment				=	$data_api->Establishment->attributes();

		(string)$Establishment['code']!='' ? $data['Establishment_code'] = (string)$Establishment['code'] : $data['Establishment_code']='';

		$user_details = get_data(CONNECT,array('hotel_channel_id'=>$data['Establishment_code'],'channel_id'=>5),'user_id,hotel_id')->row_array();

		if(count($user_details)!=0)
		{
			$data['user_id']	=	$user_details['user_id'];
			$data['hotel_id']	=	$user_details['hotel_id'];
		}
		else
		{
			$data['user_id']	=	'11';
			$data['hotel_id']	=	'11';
		}

		$Reference					=	$data_api->Establishment->Reference;

		(string)$Reference->FileNumber!='' ? $data['FileNumber'] = (string)$Reference->FileNumber : $data['FileNumber']='';

		(string)$Reference->IncomingOffice!='' ? $data['IncomingOffice'] = (string)$Reference->IncomingOffice : $data['IncomingOffice']='';

		(string)$Reference->RefNumber!='' ? $data['RefNumber'] = (string)$Reference->RefNumber : $data['RefNumber']='';

  		(string)$data_api->Establishment->Status!='' ? $data['Status'] = (string)$data_api->Establishment->Status : $data['Status']='';

		$CreationDate				=	$data_api->Establishment->CreationDate->attributes();

		(string)$CreationDate['date']!='' ? $data['CreationDate'] = (string)$CreationDate['date'] : $data['date']='';

		$CheckInDate				=	$data_api->Establishment->CheckInDate->attributes();

		(string)$CheckInDate['date']!='' ? $data['CheckInDate'] = (string)$CheckInDate['date'] : $data['CheckInDate']='';


		(string)$data_api->Establishment->LOS!='' ? $data['LOS'] = (string)$data_api->Establishment->LOS : $data['LOS']='';

		$EstablishmentInfo			=	$data_api->Establishment->EstablishmentInfo;

		(string)$EstablishmentInfo->Code!='' ? $data['EstablishmentInfo_Code'] = (string)$EstablishmentInfo->Code : $data['EstablishmentInfo_Code']='';

		(string)$EstablishmentInfo->Name!='' ? $data['EstablishmentInfo_Name'] = (string)$EstablishmentInfo->Name : $data['EstablishmentInfo_Name']='';

		(string)$data_api->Establishment->Holder!='' ? $data['Holder'] = (string)$data_api->Establishment->Holder : $data['Holder']='';

		$TTOO_code				=	$data_api->TTOO->attributes();

		(string)$TTOO_code['TTOO_code']!='' ? $data['TTOO_code'] = (string)$TTOO_code['TTOO_code'] : $data['TTOO_code']='';

		(string)$data_api->TTOO->market!='' ? $data['TTOO_market'] = (string)$data_api->TTOO->market : $data['TTOO_market']='';

		(string)$data_api->TTOO->branch!='' ? $data['TTOO_branch'] = (string)$data_api->TTOO->branch : $data['TTOO_branch']='';

		(string)$data_api->TTOO->BranchSalesName!='' ? $data['TTOO_BranchSalesName'] = (string)$data_api->TTOO->BranchSalesName : $data['TTOO_BranchSalesName']='';

		(string)$data_api->ContractingOffice->CompanyCode!='' ? $data['CompanyCode'] = (string)$data_api->ContractingOffice->CompanyCode : $data['CompanyCode']='';

		(string)$data_api->ContractingOffice->CompanyName!='' ? $data['CompanyName'] = (string)$data_api->ContractingOffice->CompanyName : $data['CompanyName']='';

		$allroom				= $data_api->Establishment->RoomList;

		if($allroom)
		{
			foreach($allroom as $roomlist)
			{
				foreach($roomlist as $rooms)
				{
					(string)$rooms->attributes()!='' ? @$data['Room_code'] = (string)$rooms->attributes() : @$data['Room_code']='';

					(string)$rooms->Type!='' ? @$data['Room_Type'] = (string)$rooms->Type : @$data['Room_Type']='';

					(string)$rooms->BoardTypeCode!='' ? @$data['BoardTypeCode'] = (string)$rooms->BoardTypeCode : @$data['BoardTypeCode']='';

					(string)$rooms->BoardType!='' ? @$data['BoardType'] = (string)$rooms->BoardType : @$data['BoardType']='';

					(string)$rooms->CharacteristicCode!='' ? @$data['CharacteristicCode'] = (string)$rooms->CharacteristicCode : @$data['CharacteristicCode']='';

					(string)$rooms->Characteristic!='' ? @$data['Characteristic'] = (string)$rooms->Characteristic : @$data['Characteristic']='';

					(string)$rooms->Remarks!='' ? @$data['Remarks'] = (string)$rooms->Remarks : @$data['Remarks']='';

					(string)$rooms->BaseBoardTypeCode!='' ? @$data['BaseBoardTypeCode'] = (string)$rooms->BaseBoardTypeCode : @$data['BaseBoardTypeCode']='';

					(string)$rooms->BaseBoardType!='' ? @$data['BaseBoardType'] = (string)$rooms->BaseBoardType : @$data['BaseBoardType']='';

					(string)$rooms->AdultCount!='' ? @$data['AdultCount'] = (string)$rooms->AdultCount : @$data['AdultCount']='';

					(string)$rooms->ChildCount!='' ? @$data['ChildCount'] = (string)$rooms->ChildCount : @$data['ChildCount']='';

					(string)$rooms->BabyCount!='' ? @$data['BabyCount'] = (string)$rooms->BabyCount : @$data['BabyCount']='';

					$all_customer = $rooms->Occupancy->GuestList->Customer;

					if($all_customer)
					{
						$Customer_type='';
						$Customer_Name='';
						foreach($all_customer as $Customer)
						{
							$Customer->attributes()!='' ? @$Customer_type .= $Customer->attributes().',' : @$Customer_type .='';

							$Customer->Name!='' ? @$Customer_Name .= $Customer->Name.',' : @$Customer_Name .='';
						}

						trim($Customer_type,',')!='' ? @$data['Customer_type'] = trim($Customer_type,',') : @$data['Customer_type']='';

						trim($Customer_Name,',')!='' ? @$data['Customer_Name'] = trim($Customer_Name,',') : @$data['Customer_Name']='';
					}
					else
					{
						@$data['Customer_type'] 	=	'';
						@$data['Customer_Name'] 	=	'';
					}

					(string)$rooms->Contract->Code!='' ? @$data['Contract_Code'] = (string)$rooms->Contract->Code : @$data['Contract_Code']='';

					(string)$rooms->Contract->Name!='' ? @$data['Contract_Name'] = (string)$rooms->Contract->Name : @$data['Contract_Name']='';

					(string)$rooms->Contract->Description!='' ? @$data['Contract_Description'] = (string)$rooms->Contract->Description : @$data['Contract_Description']='';

					(string)$rooms->DateFrom->attributes()!='' ? @$data['DateFrom'] = (string)$rooms->DateFrom->attributes() : @$data['DateFrom']='';

					(string)$rooms->DateTo->attributes()!='' ? @$data['DateTo'] = (string)$rooms->DateTo->attributes() : @$data['DateTo']='';

					$RateList	=	@$rooms->RateList;

					if($RateList)
					{
						$array  	= 	json_decode( json_encode($RateList) , 1);

						$array		=	$array['Rate'];

						if(isAssoc($array))
						{
							$RateList	=	array($array);
						}
						else
						{
							$RateList	=	$array;
						}
						$Rate_code='';
						$Rate_DateFrom='';
						$Rate_DateTo='';
						$Description='';
						$Amount='';
						$TAmount='0';
						$Currency='';
						foreach($RateList as $RateValue)
						{
							$Rate_code .= $RateValue['@attributes']['code'].',';
							$Rate_DateFrom.=$RateValue['DateFrom']['@attributes']['date'].',';
							$Rate_DateTo.=$RateValue['DateTo']['@attributes']['date'].',';
							$Description.=(string)@$RateValue['Description'].',';
							$TAmount =$TAmount + (string)@$RateValue['Amount'];
							$Currency.=(string)@$RateValue['Currency'].',';
							$Amount.=(string)@$RateValue['Amount'].',';
						}
						trim($Rate_code,',')!='' ? @$data['Rate_code'] = trim($Rate_code,',') : @$data['Rate_code']='';
						trim($Rate_DateFrom,',')!='' ? @$data['Rate_DateFrom'] = trim($Rate_DateFrom,',') : @$data['Rate_DateFrom']='';
						trim($Rate_DateTo,',')!='' ? @$data['Rate_DateTo'] = trim($Rate_DateTo,',') : @$data['Rate_DateTo']='';
						trim($Description,',')!='' ? @$data['Description'] = trim($Description,',') : @$data['Description']='';
						trim($Amount,',')!='' ? @$data['Amount'] = trim($Amount,',') : @$data['Amount']='';
						trim($TAmount,',')!='' ? @$data['TAmount'] = trim($TAmount,',') : @$data['TAmount']='';
						trim($Currency,',')!='' ? @$data['Currency'] = trim($Currency,',') : @$data['Currency']='';
					}

					(string)$rooms->NumberOfUnits!='' ? @$data['NumberOfUnits'] = (string)$rooms->NumberOfUnits : @$data['NumberOfUnits']='';

					(string)$rooms->OrderNumber!='' ? @$data['OrderNumber'] = (string)$rooms->OrderNumber : @$data['OrderNumber']='';

					(string)$rooms->Status!='' ? @$data['RoomStatus'] = (string)$rooms->Status : @$data['RoomStatus']='';

					if(@$data['RoomStatus']=='BOOKING')
					{
						//print_r($data);

						$data = array_filter($data);

						$bwhere = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$bwhere .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$bwhere .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$bwhere .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$bwhere .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['Status'])){
						$bwhere .= " and Status = '".$data['Status']."'";
						}
						if(!empty($data['Room_code'])){
						$bwhere .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$bwhere .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$bwhere .= " and Contract_Code = '".$data['Contract_Code']."'";
						}
						if(!empty($data['Contract_Name'])){
						$bwhere .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$bwhere .= " and OrderNumber = '".$data['OrderNumber']."'";
						}
						//print_r($data);

						$count = $this->db->select('import_reserv_id')->from(HBEDS_RESER)->where($bwhere)->count_all_results();

						if($count==0)
						{
							insert_data(HBEDS_RESER,$data);

							$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['RefNumber'],5);
						}
						else
						{
							update_data(HBEDS_RESER,$data,$bwhere);
						}

						$booking_status = "success";
					}
					else if(@$data['RoomStatus']=='MODIFIED')
					{
						//print_r($data);

						$data = array_filter($data);
						$del_val = '**';
						$data = array_filter($data, function($e) use ($del_val) {
						return ($e !== $del_val);
						});

						//print_r($data);

						$where = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$where .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$where .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$where .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$where .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['RoomStatus'])){
						$where .= " and RoomStatus = 'CANCELED'";
						}
						if(!empty($data['Room_code'])){
						$where .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$where .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$Contract_Code = explode('|',$data['Contract_Code']);
						$where .= " and Contract_Code = '".$Contract_Code[0]."'";
						$data['Contract_Code']	= $Contract_Code[0];
						}
						if(!empty($data['Contract_Name'])){
						$where .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$where .= " and OrderNumber = '".$data['OrderNumber']."'";
						}

						$count = $this->db->select('import_reserv_id ')->from(HBEDS_RESER)->where($where)->count_all_results();

						if($count=='0')
						{
							$where	=	str_replace("RoomStatus = 'CANCELED' and","",$where);

							//update_data(HBEDS_RESER,$data,$where);

							$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['RefNumber'],5);
						}

						$booking_status = "success";
					}
					else if(@$data['RoomStatus']=='CANCELED')
					{
						@$data['ConfirmationNumber']	=	(string)$rooms->ConfirmationNumber;
						//print_r($data);
						$cdata['RoomStatus']			=	$data['RoomStatus'];

						if($data['ConfirmationNumber'])
						{
							$cdata['ConfirmationNumber']	=	$data['ConfirmationNumber'];
						}
						else
						{
							$cdata['ConfirmationNumber']	=	'';
						}

						$cwhere = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$cwhere .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$cwhere .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$cwhere .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$cwhere .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['Room_code'])){
						$cwhere .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$cwhere .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$cwhere .= " and Contract_Code = '".$data['Contract_Code']."'";
						}
						if(!empty($data['Contract_Name'])){
						$cwhere .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$cwhere .= " and OrderNumber = '".$data['OrderNumber']."'";
						}
						//print_r($cdata);
						update_data(HBEDS_RESER,$cdata,$cwhere);

						$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['RefNumber'],5);

						$booking_status = "success";
					}
					$importBookingDetails	=	get_data(HBEDS_RESER,array('echoToken'=>$data['echoToken'],'Establishment_code'=>$data['Establishment_code'],'FileNumber'=>$data['FileNumber'],'RefNumber'=>$data['RefNumber'],'RoomStatus'=>$data['Status'],'Room_code'=>$data['Room_code'],'CharacteristicCode'=>$data['CharacteristicCode'],'Contract_Code'=>$data['Contract_Code'],'Contract_Name'=>$data['Contract_Name'],'OrderNumber'=>$data['OrderNumber']))->row_array();

					if(count($importBookingDetails)!=0)
					{
						$arrival = date('Y-m-d',strtotime($importBookingDetails['DateFrom']));
						$departure = date('Y-m-d',strtotime($importBookingDetails['DateTo']));

                        $mapdata = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where contract_name='".$data['Contract_Name']."' and contract_code='".$data['IncomingOffice']."' and sequence='".$data['Contract_Code']."' and user_id='".$data['user_id']."' and hotel_id='".$data['hotel_id']."' having roomnames ='".$data['Room_code']."' AND charactersticss ='".$data['CharacteristicCode']."'");
						//echo $this->db->last_query();
						$mappingDetails = $mapdata->row_array();

						if(count($mappingDetails)!=0)
						{
							$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>5))->row_array();

							if(count($roomMappingDetails)!=0)
							{
								require_once(APPPATH.'controllers/mapping.php');
								$callAvailabilities = new Mapping();
								$callAvailabilities->importAvailabilities_Cron($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
							}
						}
					}
					//print_r($data);
				}
			}
		}
		else
		{
			$booking_status = "error";
		}

		$response = '<BMSBookingNotifRS timestamp="'.$data['timestamp'].'" echoToken="'.$data['echoToken'].'" xmlns="http://messages.bms.hotelbeds.com"
		xmlns:ns="http://types.messages.bms.hotelbeds.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://messages.bms.hotelbeds.com BMS_BookingNotifRS.xsd">';
		try {
				if($booking_status === "success")
				{
					$response.='<ResponseStatus>PROCESSED</ResponseStatus>
								<ns:ProviderInfo>
							<ns:BookingID>TEST</ns:BookingID>
							<ns:BookingIDDescription/>
							<ns:DateTime date="'.date('Y-m-d').'" time="'.date('h:m:s').'"/>
						</ns:ProviderInfo>
					</BMSBookingNotifRS>';
				}
				else if($booking_status === "error")
				{
					throw new Exception("<ResponseStatus>ERROR</ResponseStatus>");
				}
				else
				{
					throw new Exception("<ResponseStatus>NOT_PROCESSED</ResponseStatus>");
				}
		}
		catch(Exception $e)
		{

			$response.=$e->getMessage().'
								<ns:ProviderInfo>
							<ns:BookingID>TEST</ns:BookingID>
							<ns:BookingIDDescription/>
							<ns:DateTime date="'.date('Y-m-d').'" time="'.date('h:m:s').'"/>
						</ns:ProviderInfo>
					</BMSBookingNotifRS>';
		}
		echo $response;
	}

	function getHotelbedsReservationTest()
	{
		/* $cuando 	= $_POST['BMS_XML'];
		$cuando 	= urldecode($cuando);
		mail("xml@hoteratus.com"," Reservation Form Hotel Beds ",$cuando);
		$data_api 	= simplexml_load_string($cuando); */
		$result= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<BMSBookingNotifRQ xmlns="http://messages.bms.hotelbeds.com" echoToken="11479385764361412005" timestamp="2016-11-15 15:16:05.0">
    <Establishment xmlns="http://types.messages.bms.hotelbeds.com" code="107760">
        <Reference>
            <FileNumber>1412005</FileNumber>
            <IncomingOffice>436</IncomingOffice>
            <RefNumber>436-1412005</RefNumber>
        </Reference>
        <Status>CANCELED</Status>
        <CreationDate date="20161026" />
        <CheckInDate date="20170601" />
        <LOS>0</LOS>
        <EstablishmentInfo>
            <Code>0000141664</Code>
            <Name>FOLIASTUDIOS APTS HTL</Name>
        </EstablishmentInfo>
        <RoomList>
            <Room code="STU">
                <Type>STUDIO</Type>
                <BoardTypeCode>SC</BoardTypeCode>
                <BoardType>SELF CATERING</BoardType>
                <CharacteristicCode>ST</CharacteristicCode>
                <Characteristic>STANDARD</Characteristic>
                <BaseBoardTypeCode>SC</BaseBoardTypeCode>
                <BaseBoardType>SELF CATERING</BaseBoardType>
                <Occupancy>
                    <AdultCount>2</AdultCount>
                    <ChildCount>0</ChildCount>
                    <BabyCount>0</BabyCount>
                </Occupancy>
                <Contract>
                    <Code>143693</Code>
                    <Name>GR-FIT</Name>
                    <Description>FROM ALLOCATION</Description>
                </Contract>
                <DateFrom date="20170601" />
                <DateTo date="20170613" />
                <RateList>
                    <Rate code="**">
                        <DateFrom date="20170601" />
                        <DateTo date="20170613" />
                        <Description>GR-FIT</Description>
                    </Rate>
                </RateList>
                <GuaranteeList/>
                <SupplementList/>
                <DiscountList/>
                <NumberOfUnits>1</NumberOfUnits>
                <Status>CANCELED</Status>
                <OrderNumber>1</OrderNumber>
                <ConfirmationNumber>271016</ConfirmationNumber>
            </Room>
        </RoomList>
        <Holder>LYNNE RICHARDS</Holder>
    </Establishment>
    <FreeList/>
    <TTOO xmlns="http://types.messages.bms.hotelbeds.com" code="**">
        <market>United Kingdom</market>
        <branch>**</branch>
        <BranchSalesName>**</BranchSalesName>
    </TTOO>
    <ProviderInfoList/>
    <ContractingOffice xmlns="http://types.messages.bms.hotelbeds.com">
        <CompanyCode>E16</CompanyCode>
        <CompanyName>HOTELBEDS PRODUCT, S.L.U</CompanyName>
    </ContractingOffice>
</BMSBookingNotifRQ>
';

		$data_api 	= simplexml_load_string($result);
		/* echo '<pre>';
		print_r($data_api);
		die; */
		$attributes 				= 	$data_api->attributes();

		(string)$attributes['echoToken']!='' ? $data['echoToken'] = (string)$attributes['echoToken'] : $data['echoToken']='';

		(string)$attributes['timestamp']!='' ? $data['timestamp'] = (string)$attributes['timestamp'] : $data['timestamp']='';

		$Establishment				=	$data_api->Establishment->attributes();

		(string)$Establishment['code']!='' ? $data['Establishment_code'] = (string)$Establishment['code'] : $data['Establishment_code']='';

		$user_details = get_data(CONNECT,array('hotel_channel_id'=>$data['Establishment_code'],'channel_id'=>5),'user_id,hotel_id')->row_array();

		if(count($user_details)!=0)
		{
			$data['user_id']	=	$user_details['user_id'];
			$data['hotel_id']	=	$user_details['hotel_id'];
		}
		else
		{
			$data['user_id']	=	'11';
			$data['hotel_id']	=	'11';
		}

		$Reference					=	$data_api->Establishment->Reference;

		(string)$Reference->FileNumber!='' ? $data['FileNumber'] = (string)$Reference->FileNumber : $data['FileNumber']='';

		(string)$Reference->IncomingOffice!='' ? $data['IncomingOffice'] = (string)$Reference->IncomingOffice : $data['IncomingOffice']='';

		(string)$Reference->RefNumber!='' ? $data['RefNumber'] = (string)$Reference->RefNumber : $data['RefNumber']='';

  		(string)$data_api->Establishment->Status!='' ? $data['Status'] = (string)$data_api->Establishment->Status : $data['Status']='';

		$CreationDate				=	$data_api->Establishment->CreationDate->attributes();

		(string)$CreationDate['date']!='' ? $data['CreationDate'] = (string)$CreationDate['date'] : $data['date']='';

		$CheckInDate				=	$data_api->Establishment->CheckInDate->attributes();

		(string)$CheckInDate['date']!='' ? $data['CheckInDate'] = (string)$CheckInDate['date'] : $data['CheckInDate']='';


		(string)$data_api->Establishment->LOS!='' ? $data['LOS'] = (string)$data_api->Establishment->LOS : $data['LOS']='';

		$EstablishmentInfo			=	$data_api->Establishment->EstablishmentInfo;

		(string)$EstablishmentInfo->Code!='' ? $data['EstablishmentInfo_Code'] = (string)$EstablishmentInfo->Code : $data['EstablishmentInfo_Code']='';

		(string)$EstablishmentInfo->Name!='' ? $data['EstablishmentInfo_Name'] = (string)$EstablishmentInfo->Name : $data['EstablishmentInfo_Name']='';

		(string)$data_api->Establishment->Holder!='' ? $data['Holder'] = (string)$data_api->Establishment->Holder : $data['Holder']='';

		$TTOO_code				=	$data_api->TTOO->attributes();

		(string)$TTOO_code['TTOO_code']!='' ? $data['TTOO_code'] = (string)$TTOO_code['TTOO_code'] : $data['TTOO_code']='';

		(string)$data_api->TTOO->market!='' ? $data['TTOO_market'] = (string)$data_api->TTOO->market : $data['TTOO_market']='';

		(string)$data_api->TTOO->branch!='' ? $data['TTOO_branch'] = (string)$data_api->TTOO->branch : $data['TTOO_branch']='';

		(string)$data_api->TTOO->BranchSalesName!='' ? $data['TTOO_BranchSalesName'] = (string)$data_api->TTOO->BranchSalesName : $data['TTOO_BranchSalesName']='';

		(string)$data_api->ContractingOffice->CompanyCode!='' ? $data['CompanyCode'] = (string)$data_api->ContractingOffice->CompanyCode : $data['CompanyCode']='';

		(string)$data_api->ContractingOffice->CompanyName!='' ? $data['CompanyName'] = (string)$data_api->ContractingOffice->CompanyName : $data['CompanyName']='';

		$allroom				= $data_api->Establishment->RoomList;

		if($allroom)
		{
			foreach($allroom as $roomlist)
			{
				foreach($roomlist as $rooms)
				{
					(string)$rooms->attributes()!='' ? @$data['Room_code'] = (string)$rooms->attributes() : @$data['Room_code']='';

					(string)$rooms->Type!='' ? @$data['Room_Type'] = (string)$rooms->Type : @$data['Room_Type']='';

					(string)$rooms->BoardTypeCode!='' ? @$data['BoardTypeCode'] = (string)$rooms->BoardTypeCode : @$data['BoardTypeCode']='';

					(string)$rooms->BoardType!='' ? @$data['BoardType'] = (string)$rooms->BoardType : @$data['BoardType']='';

					(string)$rooms->CharacteristicCode!='' ? @$data['CharacteristicCode'] = (string)$rooms->CharacteristicCode : @$data['CharacteristicCode']='';

					(string)$rooms->Characteristic!='' ? @$data['Characteristic'] = (string)$rooms->Characteristic : @$data['Characteristic']='';

					(string)$rooms->Remarks!='' ? @$data['Remarks'] = (string)$rooms->Remarks : @$data['Remarks']='';

					(string)$rooms->BaseBoardTypeCode!='' ? @$data['BaseBoardTypeCode'] = (string)$rooms->BaseBoardTypeCode : @$data['BaseBoardTypeCode']='';

					(string)$rooms->BaseBoardType!='' ? @$data['BaseBoardType'] = (string)$rooms->BaseBoardType : @$data['BaseBoardType']='';

					(string)$rooms->AdultCount!='' ? @$data['AdultCount'] = (string)$rooms->AdultCount : @$data['AdultCount']='';

					(string)$rooms->ChildCount!='' ? @$data['ChildCount'] = (string)$rooms->ChildCount : @$data['ChildCount']='';

					(string)$rooms->BabyCount!='' ? @$data['BabyCount'] = (string)$rooms->BabyCount : @$data['BabyCount']='';

					$all_customer = $rooms->Occupancy->GuestList->Customer;

					if($all_customer)
					{
						$Customer_type='';
						$Customer_Name='';
						foreach($all_customer as $Customer)
						{
							$Customer->attributes()!='' ? @$Customer_type .= $Customer->attributes().',' : @$Customer_type .='';

							$Customer->Name!='' ? @$Customer_Name .= $Customer->Name.',' : @$Customer_Name .='';
						}

						trim($Customer_type,',')!='' ? @$data['Customer_type'] = trim($Customer_type,',') : @$data['Customer_type']='';

						trim($Customer_Name,',')!='' ? @$data['Customer_Name'] = trim($Customer_Name,',') : @$data['Customer_Name']='';
					}
					else
					{
						@$data['Customer_type'] 	=	'';
						@$data['Customer_Name'] 	=	'';
					}

					(string)$rooms->Contract->Code!='' ? @$data['Contract_Code'] = (string)$rooms->Contract->Code : @$data['Contract_Code']='';

					(string)$rooms->Contract->Name!='' ? @$data['Contract_Name'] = (string)$rooms->Contract->Name : @$data['Contract_Name']='';

					(string)$rooms->Contract->Description!='' ? @$data['Contract_Description'] = (string)$rooms->Contract->Description : @$data['Contract_Description']='';

					(string)$rooms->DateFrom->attributes()!='' ? @$data['DateFrom'] = (string)$rooms->DateFrom->attributes() : @$data['DateFrom']='';

					(string)$rooms->DateTo->attributes()!='' ? @$data['DateTo'] = (string)$rooms->DateTo->attributes() : @$data['DateTo']='';

					$RateList	=	@$rooms->RateList;

					if($RateList)
					{
						$array  	= 	json_decode( json_encode($RateList) , 1);

						$array		=	$array['Rate'];

						if(isAssoc($array))
						{
							$RateList	=	array($array);
						}
						else
						{
							$RateList	=	$array;
						}
						$Rate_code='';
						$Rate_DateFrom='';
						$Rate_DateTo='';
						$Description='';
						$Amount='';
						$TAmount='0';
						$Currency='';
						foreach($RateList as $RateValue)
						{
							$Rate_code .= $RateValue['@attributes']['code'].',';
							$Rate_DateFrom.=$RateValue['DateFrom']['@attributes']['date'].',';
							$Rate_DateTo.=$RateValue['DateTo']['@attributes']['date'].',';
							$Description.=(string)@$RateValue['Description'].',';
							$TAmount =$TAmount + (string)@$RateValue['Amount'];
							$Currency.=(string)@$RateValue['Currency'].',';
							$Amount.=(string)@$RateValue['Amount'].',';
						}
						trim($Rate_code,',')!='' ? @$data['Rate_code'] = trim($Rate_code,',') : @$data['Rate_code']='';
						trim($Rate_DateFrom,',')!='' ? @$data['Rate_DateFrom'] = trim($Rate_DateFrom,',') : @$data['Rate_DateFrom']='';
						trim($Rate_DateTo,',')!='' ? @$data['Rate_DateTo'] = trim($Rate_DateTo,',') : @$data['Rate_DateTo']='';
						trim($Description,',')!='' ? @$data['Description'] = trim($Description,',') : @$data['Description']='';
						trim($Amount,',')!='' ? @$data['Amount'] = trim($Amount,',') : @$data['Amount']='';
						trim($TAmount,',')!='' ? @$data['TAmount'] = trim($TAmount,',') : @$data['TAmount']='';
						trim($Currency,',')!='' ? @$data['Currency'] = trim($Currency,',') : @$data['Currency']='';
					}

					(string)$rooms->NumberOfUnits!='' ? @$data['NumberOfUnits'] = (string)$rooms->NumberOfUnits : @$data['NumberOfUnits']='';

					(string)$rooms->OrderNumber!='' ? @$data['OrderNumber'] = (string)$rooms->OrderNumber : @$data['OrderNumber']='';

					(string)$rooms->Status!='' ? @$data['RoomStatus'] = (string)$rooms->Status : @$data['RoomStatus']='';

					if(@$data['RoomStatus']=='BOOKING')
					{
						//print_r($data);

						$data = array_filter($data);

						$bwhere = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$bwhere .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$bwhere .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$bwhere .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$bwhere .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['Status'])){
						$bwhere .= " and Status = '".$data['Status']."'";
						}
						if(!empty($data['Room_code'])){
						$bwhere .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$bwhere .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$bwhere .= " and Contract_Code = '".$data['Contract_Code']."'";
						}
						if(!empty($data['Contract_Name'])){
						$bwhere .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$bwhere .= " and OrderNumber = '".$data['OrderNumber']."'";
						}
						//print_r($data);

						$count = $this->db->select('import_reserv_id')->from(HBEDS_RESER)->where($bwhere)->count_all_results();

						if($count==0)
						{
							insert_data(HBEDS_RESER,$data);

							//echo $this->db->last_query().'<br>';
							/* $checkin	=	date('Y/m/d',strtotime($data['DateFrom']));
							$checkout	=	date('Y/m/d',strtotime($data['DateTo']));
							$nig 		=	_datebetween($checkin,$checkout);
							$avdata['user_id']				=	$data['user_id'];
							$avdata['hotel_id']				=	$data['hotel_id'];
							$avdata['channel_id']			=	'5';
							$avdata['channel_hotel_id']		=	$data['Establishment_code'];
							$avdata['reservation_id']		=	$data['echoToken'];
							$avdata['start']				=	$data['DateFrom'];
							$avdata['end']					=	$data['DateTo'];
							$avdata['relation_one']			=	$data['IncomingOffice'].','.$data['Contract_Name'].','.$data['CharacteristicCode'].','.$data['Room_code'].','.$data['Contract_Code'];
							$avdata['relation_two']			=	$data['OrderNumber'];
							$avdata['difference']			=	$nig;
							$avdata['reservation_status']	=	$data['RoomStatus']; */
							//insert_data(UAVL,$avdata);
							//$this->hotelbeds_model->updateAvailability($data['Establishment_code'],$source='Cron');

							//$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['echoToken'],5);
						}
						else
						{
							update_data(HBEDS_RESER,$data,$bwhere);
						}

						$booking_status = "success";
					}
					else if(@$data['RoomStatus']=='MODIFIED')
					{
						//print_r($data);

						$data = array_filter($data);
						$del_val = '**';
						$data = array_filter($data, function($e) use ($del_val) {
						return ($e !== $del_val);
						});

						//print_r($data);

						$where = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$where .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$where .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$where .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$where .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['RoomStatus'])){
						$where .= " and RoomStatus = 'CANCELED'";
						}
						if(!empty($data['Room_code'])){
						$where .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$where .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$Contract_Code = explode('|',$data['Contract_Code']);
						$where .= " and Contract_Code = '".$Contract_Code[0]."'";
						$data['Contract_Code']	= $Contract_Code[0];
						}
						if(!empty($data['Contract_Name'])){
						$where .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$where .= " and OrderNumber = '".$data['OrderNumber']."'";
						}
						//echo $where;
						///$count = $this->db->select('import_reserv_id ')->from(HBEDS_RESER)->where(array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'echoToken'=>$data['echoToken'],'Establishment_code'=>$data['Establishment_code'],'FileNumber'=>$data['FileNumber'],'RefNumber'=>$data['RefNumber'],'RoomStatus'=>'CANCELED','Room_code'=>$data['Room_code'],'CharacteristicCode'=>$data['CharacteristicCode'],'Contract_Code'=>$data['Contract_Code'],'Contract_Name'=>$data['Contract_Name'],'OrderNumber'=>$data['OrderNumber']))->count_all_results();
						$count = $this->db->select('import_reserv_id ')->from(HBEDS_RESER)->where($where)->count_all_results();
						echo $count;
						if($count=='0')
						{
							$where	=	str_replace("RoomStatus = 'CANCELED' and","",$where);

							update_data(HBEDS_RESER,$data,$where);

							//echo $this->db->last_query();
							/* $checkin	=	date('Y/m/d',strtotime($data['DateFrom']));
							$checkout	=	date('Y/m/d',strtotime($data['DateTo']));
							$nig 		=	_datebetween($checkin,$checkout);
							$avdata['user_id']				=	$data['user_id'];
							$avdata['hotel_id']				=	$data['hotel_id'];
							$avdata['channel_id']			=	'5';
							$avdata['channel_hotel_id']		=	$data['Establishment_code'];
							$avdata['reservation_id']		=	$data['echoToken'];
							$avdata['start']				=	$data['DateFrom'];
							$avdata['end']					=	$data['DateTo'];
							$avdata['relation_one']			=	$data['IncomingOffice'].','.$data['Contract_Name'].','.$data['CharacteristicCode'].','.$data['Room_code'].','.$data['Contract_Code'];
							$avdata['relation_two']			=	$data['OrderNumber'];
							$avdata['difference']			=	$nig;
							$avdata['reservation_status']	=	$data['RoomStatus']; */
							//insert_data(UAVL,$avdata);
							//$this->hotelbeds_model->updateAvailability($data['Establishment_code'],$source='Cron');
							$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['echoToken'],5);
						}

						//echo $this->db->last_query().'<br>';

						$booking_status = "success";
					}
					else if(@$data['RoomStatus']=='CANCELED')
					{
						@$data['ConfirmationNumber']	=	(string)$rooms->ConfirmationNumber;
						//print_r($data);
						$cdata['RoomStatus']			=	$data['RoomStatus'];

						if($data['ConfirmationNumber'])
						{
							$cdata['ConfirmationNumber']	=	$data['ConfirmationNumber'];
						}
						else
						{
							$cdata['ConfirmationNumber']	=	'';
						}

						$cwhere = "user_id = '".$data['user_id']."' and hotel_id = '".$data['hotel_id']."'";

						if(!empty($data['echoToken'])){
						$cwhere .= " and echoToken = '".$data['echoToken']."'";
						}
						if(!empty($data['Establishment_code'])){
						$cwhere .= " and Establishment_code = '".$data['Establishment_code']."'";
						}
						if(!empty($data['FileNumber'])){
						$cwhere .= " and FileNumber = '".$data['FileNumber']."'";
						}
						if(!empty($data['RefNumber'])){
						$cwhere .= " and RefNumber = '".$data['RefNumber']."'";
						}
						if(!empty($data['Room_code'])){
						$cwhere .= " and Room_code = '".$data['Room_code']."'";
						}
						if(!empty($data['CharacteristicCode'])){
						$cwhere .= " and CharacteristicCode = '".$data['CharacteristicCode']."'";
						}
						if(!empty($data['Contract_Code'])){
						$cwhere .= " and Contract_Code = '".$data['Contract_Code']."'";
						}
						if(!empty($data['Contract_Name'])){
						$cwhere .= " and Contract_Name = '".$data['Contract_Name']."'";
						}
						if(!empty($data['OrderNumber'])){
						$cwhere .= " and OrderNumber = '".$data['OrderNumber']."'";
						}
						//print_r($cdata);
						update_data(HBEDS_RESER,$cdata,$cwhere);

						/* $checkin	=	date('Y/m/d',strtotime($data['DateFrom']));
						$checkout	=	date('Y/m/d',strtotime($data['DateTo']));
						$nig 		=	_datebetween($checkin,$checkout);
						$avdata['user_id']				=	$data['user_id'];
						$avdata['hotel_id']				=	$data['hotel_id'];
						$avdata['channel_id']			=	'5';
						$avdata['channel_hotel_id']		=	$data['Establishment_code'];
						$avdata['reservation_id']		=	$data['echoToken'];
						$avdata['start']				=	$data['DateFrom'];
						$avdata['end']					=	$data['DateTo'];
						$avdata['relation_one']			=	$data['IncomingOffice'].','.$data['Contract_Name'].','.$data['CharacteristicCode'].','.$data['Room_code'].','.$data['Contract_Code'];
						$avdata['relation_two']			=	$data['OrderNumber'];
						$avdata['difference']			=	$nig;
						$avdata['reservation_status']	=	$data['RoomStatus']; */
						//insert_data(UAVL,$avdata);

						//$this->hotelbeds_model->updateAvailability($data['Establishment_code'],$source='Cron');
						$this->send_confirmation_mail($data['user_id'],$data['hotel_id'],$data['echoToken'],5);

						$booking_status = "success";
					}
					$importBookingDetails	=	get_data(HBEDS_RESER,array('echoToken'=>$data['echoToken'],'Establishment_code'=>$data['Establishment_code'],'FileNumber'=>$data['FileNumber'],'RefNumber'=>$data['RefNumber'],'RoomStatus'=>$data['Status'],'Room_code'=>$data['Room_code'],'CharacteristicCode'=>$data['CharacteristicCode'],'Contract_Code'=>$data['Contract_Code'],'Contract_Name'=>$data['Contract_Name'],'OrderNumber'=>$data['OrderNumber']))->row_array();

					if(count($importBookingDetails)!=0)
					{
						$arrival = date('Y-m-d',strtotime($importBookingDetails['DateFrom']));
						$departure = date('Y-m-d',strtotime($importBookingDetails['DateTo']));

                        $mapdata = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `".IM_HOTELBEDS_ROOMS."` where contract_name='".$data['Contract_Name']."' and contract_code='".$data['IncomingOffice']."' and sequence='".$data['Contract_Code']."' and user_id='".$data['user_id']."' and hotel_id='".$data['hotel_id']."' having roomnames ='".$data['Room_code']."' AND charactersticss ='".$data['CharacteristicCode']."'");
						//echo $this->db->last_query();
						$mappingDetails = $mapdata->row_array();

						if(count($mappingDetails)!=0)
						{
							$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>5))->row_array();

							if(count($roomMappingDetails)!=0)
							{
								require_once(APPPATH.'controllers/mapping.php');
								$callAvailabilities = new Mapping();
								$callAvailabilities->importAvailabilities_Cron($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
							}
						}
					}
					//print_r($data);
				}
			}
		}
		else
		{
			$booking_status = "error";
		}

		$response = '<BMSBookingNotifRS timestamp="'.$data['timestamp'].'" echoToken="'.$data['echoToken'].'" xmlns="http://messages.bms.hotelbeds.com"
		xmlns:ns="http://types.messages.bms.hotelbeds.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://messages.bms.hotelbeds.com BMS_BookingNotifRS.xsd">';
		try {
				if($booking_status === "success")
				{
					$response.='<ResponseStatus>PROCESSED</ResponseStatus>
								<ns:ProviderInfo>
							<ns:BookingID>TEST</ns:BookingID>
							<ns:BookingIDDescription/>
							<ns:DateTime date="'.date('Y-m-d').'" time="'.date('h:m:s').'"/>
						</ns:ProviderInfo>
					</BMSBookingNotifRS>';
				}
				else if($booking_status === "error")
				{
					throw new Exception("<ResponseStatus>ERROR</ResponseStatus>");
				}
				else
				{
					throw new Exception("<ResponseStatus>NOT_PROCESSED</ResponseStatus>");
				}
		}
		catch(Exception $e)
		{

			$response.=$e->getMessage().'
								<ns:ProviderInfo>
							<ns:BookingID>TEST</ns:BookingID>
							<ns:BookingIDDescription/>
							<ns:DateTime date="'.date('Y-m-d').'" time="'.date('h:m:s').'"/>
						</ns:ProviderInfo>
					</BMSBookingNotifRS>';
		}
		echo $response;
	}

	/* function getHotelbedsReservationTest()
	{
		$cuando 	= $_POST['BMS_XML'];
		if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR'])
		{
			mail("xml@hoteratus.com","Check Cron Only Hotle Beds ",'Invalid Request');
			echo 'Invalid Request';
		}
		else
		{
			mail("xml@hoteratus.com","Check Cron Only Hotle Beds ",'Invalid Request');
			echo ' ! Invalid Request';
		}
		die;
		$cuando 	= $_POST['BMS_XML'];

		$cuando 	= urldecode($cuando);

		//$msg = wordwrap($cuando,70);

		if(mail("xml@hoteratus.com","Test Mail Form Hotle Beds ",$cuando))
		{
			echo 'success';
		}
		else
		{
			echo 'error';
		}
	} */

	function getAllOtaReservations(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'enabled'))->result_array();
		if ($ch_details) {
			foreach ($ch_details as $details)
			{
				echo $this->getResrvationFromChannel(insep_encode($details['channel_id']));
			}
		}
		redirect('reservation/reservationlist','refresh');
	}

	function getResrvationFromChannel($channel_id)
	{


		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(insep_decode($channel_id)!='')
		{
			$cha_name = ucfirst(get_data(TBL_CHANNEL,array('channel_id'=>insep_decode($channel_id)))->row()->channel_name);

			if(insep_decode($channel_id)!=17 && insep_decode($channel_id)!=15 && insep_decode($channel_id)!=14)
			{
				$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
				if($ch_details)
				{
					if($ch_details->mode == 0)
					{
						$urls = explode(',', $ch_details->test_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							if(array_key_exists(1,$path)){
								$bookurl[$path[0]] = $path[1];
							}
						}
					}
					else if($ch_details->mode == 1)
					{
						$urls = explode(',', $ch_details->live_url);
						foreach($urls as $url)
						{
							$path = explode("~",$url);
							if(array_key_exists(1,$path)){
								$bookurl[$path[0]] = $path[1];
							}
						}
					}
				}
			}
		}
		else
		{
			$cha_name ='';
		}

		if(insep_decode($channel_id)==8)
		{
			$start = date(DATE_ATOM);
			$start = explode("+", $start);
			$end = date(DATE_ATOM, strtotime("-30 days"));
			$end = explode("+", $end);

			$soapUrl = trim($bookurl['booking']);

			//$soapUrl="https://hotels.demo.gta-travel.com/supplierapi/rest/bookings/search";

			$xml_post_string='<GTA_BookingSearchRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.gta-travel.com/GTA/2012/05">
				<User Qualifier="'.$ch_details->web_id.'" Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
				<SearchCriteria
					PropertyId="'.$ch_details->hotel_channel_id.'"
					ModifiedStartDate="'.$end[0].'"
					ModifiedEndDate="'.$start[0].'"/>
			</GTA_BookingSearchRQ>';
			/*$xml_post_string='<GTA_BookingSearchRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.gta-travel.com/GTA/2012/05">
				<User Qualifier="'.$ch_details->web_id.'" Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
				<SearchCriteria PropertyId="'.$ch_details->hotel_channel_id.'" ModifiedStartDate="2016-07-01T00:00:00"/>
			</GTA_BookingSearchRQ>';*/

			$x_r_rq_data['channel_id'] = '8';
			$x_r_rq_data['user_id'] = current_user_type();
			$x_r_rq_data['hotel_id'] = hotel_id();
			$x_r_rq_data['message'] = $xml_post_string;
			$x_r_rq_data['type'] = 'GTA_REQ';
			$x_r_rq_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rq_data);
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
			makeLog('gtaReservationLog.txt', "RESERVATIOM URL=>$soapUrl\n\nContent=>$xml_post_string\n\nResponse=>$output\n");
			$x_r_rs_data['channel_id'] = '8';
			$x_r_rs_data['user_id'] = current_user_type();
			$x_r_rs_data['hotel_id'] = hotel_id();
			$x_r_rs_data['message'] = $output;
			$x_r_rs_data['type'] = 'GTA_RES';
			$x_r_rs_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rs_data);
			/* echo '<pre>';
			print_r($output); */
			$data 		=	simplexml_load_string($output);
			$Error		=	@$data->Errors->Error;
			$Warning	=	@$data->Warnings->Warning;
			$data		=	@$data->Bookings->Booking;
			if(count($data) != 0 || $Error != "" || $Warning != "")
			{
				$this->reservationxmlmail($x_r_rs_data);
			}
			if(isset($Error))
			{
				$meg['result'] = '0';
				$meg['content']	= $Error.' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
			else if(isset($Warning))
			{
				$meg['result'] = '0';
				$meg['content']	= $Warning.' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
			else if(isset($data))
			{
				if($data)
				{
					foreach($data as $row)
					{
						$res_data=array();
						$user_id=current_user_type();
						$res_data['user_id'] = "$user_id";
						$res_data['hotel_id'] = hotel_id();
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

						$room_id=$row->Rooms->Room->attributes()->Id;
						$res_data['room_id']="$room_id";
						$roomcategory=$row->Rooms->Room->attributes()->RoomCategory;
						$res_data['roomcategory']="$roomcategory";
						//$res_data['roomcategory']=$row->Rooms->Room->attributes()->RoomType;
						$qty=$row->Rooms->Room->attributes()->Quantity;
						$res_data['room_qty']="$qty";

						$rates=$row->Rooms->Room->Rates;
						$room_array=array();
						foreach($rates as $rate)
						{
							foreach($rate->StayDates as $roomrow)
							{
								$value=array();
								$dateval=$roomrow->attributes()->Date;
								$datearry=explode('-',$dateval);
								$date=$datearry[2].'/'.$datearry[1].'/'.$datearry[0];
								$value['date']=$date;
								$value['Tax']=$roomrow->attributes()->Tax;
								$value['NettCost']=$roomrow->attributes()->NettCost;
								$value['Adhoced']=$roomrow->attributes()->Adhoced;
								$value['Supplement1ExtraBedNettCost']=$roomrow->attributes()->Supplement1ExtraBedNettCost;
								$value['Supplement2ExtraBedNettCost']=$roomrow->attributes()->Supplement2ExtraBedNettCost;
								$value['SupplementSharingBedNettCost']=$roomrow->attributes()->SupplementSharingBedNettCost;
								$value['SupplementCotNettCost']=$roomrow->attributes()->SupplementCotNettCost;
								$value['SupplementTax']=$roomrow->attributes()->SupplementTax;
								$room_array[]=$value;
							}
						}
						$room_costdetils=json_encode($room_array);
						$res_data['room_costdetils']="$room_costdetils";

						$passengers=$row->Passengers->Passenger;
						$passenger_details=array();
						foreach($passengers as $pass )
						{
							$passarray=array();
							$passarray['name']=$pass->attributes()->Name;
							if(isset($pass->attributes()->Age))
							{
								$passarray['age']=$pass->attributes()->Age;
							}
							else
							{
								$passarray['age']="Adult";
							}
							$passenger_details[]=$passarray;

						}
						$passenger_array=json_encode($passenger_details);
						$res_data['passenger_names']="$passenger_array";
						$available = get_data('import_reservation_GTA',array('user_id'=>$user_id,'hotel_id'=>hotel_id(),'hotelid'=>$hotel_id,'booking_id'=>$booking_id))->row_array();
						//echo $this->db->last_query(); exit;
						if(count($available)==0)
						{
							insert_data('import_reservation_GTA',$res_data);
						}
						else
						{
							update_data('import_reservation_GTA',$res_data,array('user_id'=>current_user_type(),'hotelid'=>$hotel_id,'hotel_id'=>hotel_id(),'booking_id'=>$booking_id));
						}
						$importBookingDetails = get_data('import_reservation_GTA',array('user_id'=>current_user_type(),'hotelid'=>$hotel_id,'hotel_id'=>hotel_id(),'booking_id'=>$booking_id))->row_array();
			            if(count($importBookingDetails)!=0)
			            {
			                $arrival = date('Y-m-d',strtotime($importBookingDetails['arrdate']));
			                $departure = date('Y-m-d',strtotime($importBookingDetails['depdate']));
			                $importBookingDetails = get_data(IM_GTA,array('ID'=>$importBookingDetails['room_id']),'GTA_id')->row_array();
			                if(count($importBookingDetails)!=0)
			                {
			                    $roomMappingDetails = get_data(MAP,array('import_mapping_id'=>$importBookingDetails['GTA_id'],'channel_id'=>8))->row_array();

				                if(count($roomMappingDetails)!=0)
				                {
				                  require_once(APPPATH.'controllers/mapping.php');
				                  $callAvailabilities = new Mapping();
				                  $callAvailabilities->importAvailabilities_Cron(current_user_type(),hotel_id(),insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['GTA_id'],'mapping');
				                }
			              	}
			            }
						$this->reservation_log(insep_decode($channel_id),$booking_id,current_user_type(),hotel_id());
						$this->send_confirmation_mail(current_user_type(),hotel_id(),$booking_id,8);
					}
					$meg['result'] 	= 	'1';
					$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
					echo json_encode($meg);
				}
				else
				{
					$meg['result'] = '0';
					$meg['content']="Can't import reservation from '.$cha_name.'!!!";
					echo json_encode($meg);
				}
			}
			else
			{
				$meg['result'] = '0';
				$meg['content']="Can't import reservation from '.$cha_name.'!!!";
				echo json_encode($meg);
			}
		}
		else if(insep_decode($channel_id)==11)
		{
			//$soapUrl = "https://test.reconline.com/RecoXML/RecoXML.asmx";
			$soapUrl = trim($bookurl['booking']);
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
			$x_r_rq_data['channel_id'] = '11';
			$x_r_rq_data['user_id'] = current_user_type();
			$x_r_rq_data['hotel_id'] = hotel_id();
			$x_r_rq_data['message'] = $xml_post_string;
			$x_r_rq_data['type'] = 'RECO_REQ';
			$x_r_rq_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rq_data);
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
			$x_r_rs_data['channel_id'] = '11';
			$x_r_rs_data['user_id'] = current_user_type();
			$x_r_rs_data['hotel_id'] = hotel_id();
			$x_r_rs_data['message'] = $xml;
			$x_r_rs_data['type'] = 'RECO_RES';
			$x_r_rs_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rs_data);

			$xml = simplexml_load_string($xml);
			$json = json_encode($xml);
			$responseArray = json_decode($json,true);
			$Hotelarray = $responseArray['soapBody']['GetBookingsResponse']['GetBookingsResult']['diffgrdiffgram']['NewDataSet']['Bookings'];
			$Errorarray = @$responseArray['soapBody']['GetBookingsResponse']['GetBookingsResult']['diffgrdiffgram']['NewDataSet']['Warning'];
			if(count($Errorarray) != 0 || count($Hotelarray))
			{
				$this->reservationxmlmail($x_r_rs_data);
			}
			if(count($Errorarray)!=0)
			{
				$meg['result'] = '0';
				$meg['content']=$Errorarray['WARNING'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
			else if(count($Errorarray)==0)
			{
				foreach($Hotelarray as $reser)
				{
					foreach($reser as $key=>$value)
					{
						$data['user_id'] = current_user_type();
						$data['hotel_id'] = hotel_id();
						$data[$key] = $value;
					}
					$available = get_data(REC_RESERV,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']))->row_array();
					if(count($available)==0)
					{
						insert_data(REC_RESERV,$data);
					}
					else
					{
						update_data(REC_RESERV,$data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']));
					}
					$importBookingDetails	=	get_data(REC_RESERV,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'IDRSV'=>$data['IDRSV'],'IDHOTEL'=>$data['IDHOTEL']))->row_array();
					if(count($importBookingDetails)!=0)
					{
						$arrival = date('Y-m-d',strtotime($importBookingDetails['CHECKIN']));
							$departure = date('Y-m-d',strtotime($importBookingDetails['CHECKOUT']));
						$importBookingDetails	=	get_data(IM_RECO,array('CODE'=>$importBookingDetails['ROOMCODE']),'re_id')->row_array();
						if(count($importBookingDetails)!=0)
						{
							$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$importBookingDetails['re_id'],'channel_id'=>11))->row_array();
							if(count($roomMappingDetails)!=0)
							{
								require_once(APPPATH.'controllers/mapping.php');
								$callAvailabilities = new Mapping();
								$callAvailabilities->importAvailabilities(insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['re_id'],'mapping');
							}
						}
					}
					$this->reservation_log(insep_decode($channel_id),$data['IDRSV'],current_user_type(),hotel_id());
				}
				$meg['result'] = '1';
				$meg['content']='Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
		}
		else if (insep_decode($channel_id) == 1)
		{
			$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
						<BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
						<Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/>
						<Hotel id="'.$ch_details->hotel_channel_id.'"/>
						</BookingRetrievalRQ>
					   ';
			/* echo $xml_data; */ /*<Hotel id="'.$ch_details->hotel_channel_id.'"/>  */
			$x_r_rq_data['channel_id'] = '1';
			$x_r_rq_data['user_id'] = '0';
			$x_r_rq_data['hotel_id'] = '0';
			$x_r_rq_data['message'] = $xml_data;
			$x_r_rq_data['type'] = 'EXP_REQ';
			$x_r_rq_data['section'] = 'RESER';
			//insert_data(ALL_XML,$x_r_rq_data);
			//$this->reservationxmlmail($x_r_rq_data);

			//$URL = "https://simulator.expediaquickconnect.com/connect/br";
			//$URL = "https://ws.expediaquickconnect.com/connect/br";
			$URL = trim($bookurl['booking']);
			//echo $URL;

			$ch = curl_init($URL);
			//curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output		= curl_exec($ch);
			/* $output		= '<?xml version="1.0" encoding="UTF-8"?>
<BookingRetrievalRS xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
    <Bookings>
        <Booking id="795123984" type="Cancel" createDateTime="2017-02-17T04:54:00Z" source="A-Expedia" status="pending" confirmNumber="Electronic booking
received">
            <Hotel id="17103231" />
            <RoomStay roomTypeID="0" ratePlanID="0">
                <StayDate arrival="2017-03-01" departure="2017-03-02" />
                <GuestCount adult="2" />
                <PerDayRates>
                    <PerDayRate stayDate="2017-03-01" baseRate="0.00" />
                </PerDayRates>
                <Total amountAfterTaxes="0.00" amountOfTaxes="0.00" />
            </RoomStay>
            <PrimaryGuest>
                <Name givenName="Subbaiah" surname="A" />
            </PrimaryGuest>
        </Booking>
    </Bookings>
</BookingRetrievalRS>';
			echo $output;  */
			$x_r_rs_data['channel_id'] = '1';
			$x_r_rs_data['user_id'] = '0';
			$x_r_rs_data['hotel_id'] = '0';
			$x_r_rs_data['message'] = $output;
			$x_r_rs_data['type'] = 'EXP_RES';
			$x_r_rs_data['section'] = 'RESER';
			//insert_data(ALL_XML,$x_r_rs_data);
			//$this->reservationxmlmail($x_r_rs_data);

			$data_api 	= simplexml_load_string($output);

			//print_r($data_api);
			$error =@$data_api->Error;
			$BookingListing = @$data_api->Bookings->Booking;

			if($error != "" || count($BookingListing) != "")
			{
				$this->reservationxmlmail($x_r_rs_data);
			}
			if($error != "")
			{
				$meg['result'] = '0';
				$meg['content']= $error.' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
			else if($error == "")
			{
				$BookingListing = $data_api->Bookings;
				foreach($BookingListing as $Booking)
				{
					foreach($Booking as $Booking_key=>$book)
					{
						$data['user_id'] = current_user_type();
						$data['hotel_id'] = hotel_id();

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
								$data['expireDate'] = "";
								if(count($cardDetails) != 0){
									$data['cardCode'] = (string)$cardDetails['cardCode'];
									$data['cardNumber'] = (string)$cardDetails['cardNumber'];
									$data['expireDate'] = (string)$cardDetails['expireDate'];
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

							/*foreach ($$book->SpecialRequest as $special) {
								$data['SpecialRequest'] = (string)$special.",";
							}*/
							$data['SpecialRequest'] = (string)$book->SpecialRequest;
							$available = get_data('import_reservation_EXPEDIA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

							if(count($available)==0)
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								insert_data('import_reservation_EXPEDIA',$data);
							}
							else
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								update_data('import_reservation_EXPEDIA',$data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
							}

							if($data['type'] == "Modify")
							{
								$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

								$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['created_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

	        					$res = $this->db->insert('new_history',$history);
							}

						}else{
							$Hotel=$book->Hotel->attributes();
							$data['hotelid'] = $Hotel['id'];
							$data['created_time'] = (string)$bookattr['createDateTime'];
							update_data('import_reservation_EXPEDIA',$data,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));

							$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

							$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$data['created_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

        					$res = $this->db->insert('new_history',$history);
						}


						$this->reservation_model->send_confirmation_email(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);
						$this->reservation_log(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);
						$importBookingDetails	=	get_data('import_reservation_EXPEDIA',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();
						if(count($importBookingDetails)!=0)
						{
							$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival'
								]));
							$departure = date('Y-m-d',strtotime($importBookingDetails['departure']));

							$roomtypeid = $importBookingDetails['roomTypeID'];
							$rateplanid = $importBookingDetails['ratePlanID'];
							$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,current_user_type(),hotel_id());

							if(count($roomdetails) != 0)
							{
								$roomtypeid = $roomdetails['roomtypeId'];
								$rateplanid = $roomdetails['rateplanid'];
							}

							$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()),'map_id')->row_array();

							if(count($mappingDetails)!=0)
							{
								$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>1))->row_array();
								if(count($roomMappingDetails)!=0)
								{
									require_once(APPPATH.'controllers/mapping.php');
									$callAvailabilities = new Mapping();
									$callAvailabilities->importAvailabilities(insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
								}
							}
						}
						$confirm = date(DATE_ATOM);
						$confitm_time = explode("+", $confirm);
						$xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09"><Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/><Hotel id="'.$ch_details->hotel_channel_id.'"/><BookingConfirmNumbers>
							<BookingConfirmNumber bookingID="'.$data['booking_id'].'" bookingType="'.$data['type'].'" confirmNumber="'.$data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>
							</BookingConfirmNumbers>
							</BookingConfirmRQ>';

						$x_c_rq_data['channel_id'] 	= '1';
						$x_c_rq_data['user_id'] 	= '0';
						$x_c_rq_data['hotel_id'] 	= '0';
						$x_c_rq_data['message'] 	= $xml_confirm;
						$x_c_rq_data['type'] 		= 'REQ_EXP';
						$x_c_rq_data['section']		= 'RESER_CONFIRM_EXP';
						//insert_data(ALL_XML,$x_c_rq_data);

						$URL = trim($bookurl['bookingconfirm']);

						$ch = curl_init($URL);
						//curl_setopt($ch, CURLOPT_MUTE, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output		= curl_exec($ch);
						$x_c_rs_data['channel_id'] = '1';
						$x_c_rs_data['user_id'] = '0';
						$x_c_rs_data['hotel_id'] = '0';
						$x_c_rs_data['message'] = $output;
						$x_c_rs_data['type'] = 'RES_EXP';
						$x_c_rs_data['section'] = 'RESER_CONFIRM_EXP';

						mail("xml@hoteratus.com"," BookingConfirmNumber ",$x_c_rs_data);


						//insert_data(ALL_XML,$x_c_rs_data);

						//$this->reservationxmlmail($x_c_rs_data);
						$data_api 	= simplexml_load_string($output);
						$error =@$data_api->Error;
						if($error != "")
						{
							$meg['result'] = '0';
							$meg['content']= $error.' from '.$cha_name.'. Try again!';
							echo json_encode($meg);
						}
					}
				}
				$meg['result'] = '1';
				$meg['content']='Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
		}
		else if(insep_decode($channel_id) == 2)
		{
			if($ch_details->xml_type==1 || $ch_details->xml_type==2)
			{
				$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
				<request>
				<username>'.$ch_details->user_name.'</username>
				<password>'.$ch_details->user_password.'</password>
				<hotel_id>'.$ch_details->hotel_channel_id.'</hotel_id>
				</request>';
				/* echo $xml_data; */
				$x_r_rq_data['channel_id'] = '2';
				$x_r_rq_data['user_id'] = '0';
				$x_r_rq_data['hotel_id'] = '0';
				$x_r_rq_data['message'] = $xml_data;
				$x_r_rq_data['type'] = 'BOOK_REQ';
				$x_r_rq_data['section'] = 'RESER';
				//insert_data(ALL_XML,$x_r_rq_data);
				//$this->reservationxmlmail($x_r_rq_data);

				$URL = "https://secure-supply-xml.booking.com/hotels/xml/reservations";
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: text/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				/* echo $output; */
				$x_r_rs_data['channel_id'] = '2';
				$x_r_rs_data['user_id'] = '0';
				$x_r_rs_data['hotel_id'] = '0';
				$x_r_rs_data['message'] = $output;
				$x_r_rs_data['type'] = 'BOOK_RES';
				$x_r_rs_data['section'] = 'RESER';
				//insert_data(ALL_XML,$x_r_rs_data);
				//$this->reservationxmlmail($x_r_rs_data);
				$data_api = simplexml_load_string($output);
				$ruid = "";
				preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
				$end = end($output);

		        if(is_array($end)){
		            $end_end = end($end);
		            $ruid = str_replace("!-- RUID: [", '', $end_end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		           // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
		        else{
		            $ruid = str_replace("!-- RUID: [", '', $end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		          // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
	        $this->saveBooking_Booking($data_api,$ruid,$cha_name="Booking.com",$hotel_channel_id='',$x_r_rs_data);
			}
			else
			{
				$meg['result'] = '1';
				$meg['content']="Can't import reservation from '.$cha_name.'!!!";
				echo json_encode($meg);
			}
			}
		else if(insep_decode($channel_id) == 17)
		{
			require_once(APPPATH.'controllers/bnow.php');

			$callReservation	=	new bnow();

			$result		=	$callReservation->getReservation($channel_id);

			if(@$result['Enable']=='Enable')
			{
				$meg['result']	= 	'0';
				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
				echo json_encode($meg);
			}
			elseif(@$result['succes']=='Insert')
			{
				$meg['result']	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			elseif(@$result['Error'])
			{
				$meg['result']	= 	'0';
				$meg['content']	= $result['Error'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
		}
		else if(insep_decode($channel_id) == 40 || insep_decode($channel_id) == 41 || insep_decode($channel_id) == 42)
		{
			require_once(APPPATH.'controllers/hotusagroup.php');

			$callReservation	=	new hotusagroup();

			$result		=	$callReservation->getBooks(insep_decode($channel_id),$cha_name,hotel_id(),current_user_type());

			if(@$result['Enable']=='Enable')
			{
				$meg['result']	= 	'0';
				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
				echo json_encode($meg);
			}
			elseif(@$result['succes']=='Insert')
			{
				$meg['result']	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			elseif(@$result['Error']!="")
			{
				$meg['result']	= 	'0';
				$meg['content']	= $result['Error'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}

			return;
		}
			else if(insep_decode($channel_id)==19)
		{

					$headers = "From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
                    $headers .= "Reply-To: Info <info@hoteratus.com>\r\n";
                    $headers .= "CC: support <felix@hoteratus.com>\r\n";
                    $headers .= "BCC: datahernandez@gmail.com\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";

            $this->load->model('agoda_model');

			$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>19))->row();
			$urls="";
			if($ch_details->mode == 0){
				$urls =$ch_details->test_url;
			}else{
				$urls =$ch_details->live_url;
			}
			//$start = date(DATE_ATOM, strtotime("-50 days"));
			$start = date(DATE_ATOM,strtotime("-50 days"));
			$end = date(DATE_ATOM, strtotime("+30 days"));
			$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
                      <request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="3">
                         <criteria from="'.$start.'" to="'.$end.'">
                          <property id="'.$ch_details->hotel_channel_id.'"/>
                        </criteria>
                      </request>';

            $x_r_rq_data['channel_id'] = '19';
			$x_r_rq_data['user_id'] = current_user_type();
			$x_r_rq_data['hotel_id'] = hotel_id();
			$x_r_rq_data['message'] = $xml_data;
			$x_r_rq_data['type'] = 'AGD_REQ';
			$x_r_rq_data['section'] = 'RESER';
			$URL = trim($urls);
			$ch = curl_init();
		  	curl_setopt( $ch, CURLOPT_URL, $URL );
		  	curl_setopt( $ch, CURLOPT_POST, true );
		 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		  	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data);
			$output = curl_exec($ch);
			$x_r_rs_data['channel_id'] = '19';
			$x_r_rs_data['user_id'] =current_user_type();
			$x_r_rs_data['hotel_id'] = hotel_id();
			$x_r_rs_data['message'] = $output;
			$x_r_rs_data['type'] = 'AGD_RES';
			$x_r_rs_data['section'] = 'RESER';

			$data_api 	= simplexml_load_string($output);
			mail("xml@hoteratus.com", "Agoda Reservations",$xml_data.$output , $headers);
			$error =@$data_api->errors;
			$BookingListing = @$data_api->properties->property[0]->bookings->booking;
			if($error != "" || count($BookingListing) != "")
			{
				$this->reservationxmlmail($x_r_rs_data);
			}
			if($error != "")
			{
				$meg['result'] = '0';
				$meg['content']= $error.' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
			else if($error == "")
			{
				foreach($BookingListing as $Booking_key=>$book)
				{


					if($book['status'] != ""){

						$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
		                      <request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="4">
		                         <criteria>
		                          <property id="'.$ch_details->hotel_channel_id.'">
		                          	<booking id="'. (string)$book['booking_id'].'"/>
		                          </property>
		                        </criteria>
		                      </request>';
							$ch = curl_init();
						  	curl_setopt( $ch, CURLOPT_URL, $URL );
						  	curl_setopt( $ch, CURLOPT_POST, true );
						 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						  	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data);
							$output = curl_exec($ch);
							$data_bookingapi= simplexml_load_string($output);
							mail("xml@hoteratus.com", "Agoda Reservations",$xml_data.$output.$data_bookingapi , $headers);
							$error =@$data_bookingapi->errors;
							if($error != "")
							{
								$this->reservationxmlmail($x_r_rs_data);
								$meg['result'] = '0';
								$meg['content']= $error.' from '.$cha_name.'. Try again!';
								echo json_encode($meg);
							}
							else if($error == "")
							{

								$Hotelbook=$data_bookingapi->bookings->booking;
								$data['user_id']=current_user_type();
								$data['hotel_id']=hotel_id();
								$data['hotelcode'] =(string)$Hotelbook['property_id'];
								$data['property_name'] =(string)$Hotelbook['property_name'];
								$data['booking_id'] =(string)$Hotelbook['booking_id'];
								$data['booking_date'] =(string)$Hotelbook['booking_date'];
								$data['last_action'] =(string)$Hotelbook['last_action'];
								$data['arrival'] =(string)$Hotelbook['arrival'];
								$data['departure'] =(string)$Hotelbook['departure'];
								$data['status'] =(string)$Hotelbook['status'];
								$data['acknowledge'] =(string)$Hotelbook['acknowledge'];
								$data['room_id'] =(string)$Hotelbook['room_id'];
								$data['room_type'] =(string)$Hotelbook['room_type'];
								$data['channel_code'] =(string)$Hotelbook['channel_id'];
								$data['channel_name'] =(string)$Hotelbook['channel_name'];
								$data['rateplan_id'] =(string)$Hotelbook['rateplan_id'];
								$data['rateplan_name'] =(string)$Hotelbook['rateplan_name'];
								$data['promotion_id'] =(string)$Hotelbook['promotion_id'];
								$data['promotion_name'] =(string)$Hotelbook['promotion_name'];
								$data['room_count'] =(string)$Hotelbook['room_count'];
								$data['adults'] =(string)$Hotelbook['adults'];
								$data['children'] =(string)$Hotelbook['children'];
								$data['extrabeds'] =(string)$Hotelbook['extrabeds'];
								$data['cxl_code'] =(string)$Hotelbook['cxl_code'];


								$customer = $Hotelbook->customer;
								$data['firstname'] = (string)$customer['first_name'];
								$data['lastname'] = (string)$customer['last_name'];
								$data['nationality'] = (string)$customer['nationality'];
								$data['email'] = (string)$customer['email'];
								$data['phone'] = (string)$customer['phone'];




								$requests=$Hotelbook->requests->request;
								$request='';
								if(count($requests)>0)
								{
									foreach ($requests as  $value) {
										$request .=$value['request_name']."\n";
									}
								}

								$data['requests']=$request;


								$cardDetails = $Hotelbook->payment;
								$data['card_type']=(string)(string)@$cardDetails['card_type'];
								$data['card_number']=(string)@$cardDetails['card_number'];
								$data['card_name']=(string)@$cardDetails['card_name'];
								$data['card_exp']=(string)@$cardDetails['card_exp'];
								$data['card_cvv']=(string)@$cardDetails['card_cvv'];


								$prices = $Hotelbook->prices;

								$data['currency'] = (string)$prices['currency'];
								$data['net_inclusive_amt'] = (string)$prices['net_inclusive_amt'];
								$data['refsell_amt'] = (string)$prices['refsell_amt'];

								$price=$prices->price;
								$pricedate='';
								$pricemoney='';
								if(count($price)>0)
								{
									foreach ($price as  $value) {
										$pricedate .=(string)$value['date'].",";
										$pricemoney .=(string)$value['net_inclusive_amt'].",";

									}
								}
								$data['pricedate']=$pricedate;
								$data['pricemoney']=$pricemoney;
								$data['paymentmodel']=(string)$Hotelbook->paymentmodel;


								$available = get_data('import_reservation_AGODA',array('hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id']))->row_array();
								if(count($available)==0)
								{
									$result = "";
									for($i = 0; $i < 10; $i++)
									{
									    $result .= mt_rand(0, 9);
									}
									$data['confirmation_number'] = $result;

									$array_keys = array_keys($data);
									fetchColumn('import_reservation_AGODA',$array_keys);
									insert_data('import_reservation_AGODA',$data);
									$id =$this->db->insert_id();

									$benefits = $Hotelbook->benefits->benefit;
									if(count($benefits)>0)
									{

										foreach ($benefits as  $value)
										{
											 $dataextra=array('reservation_id'=> $id,'channel_id'=>19,'description'=>(string)$value['benefit_name'],'amount'=>0,'extra_date'=>date('Y-m-d'));
						                        insert_data('extras',$dataextra);

										}
									}

									if($data['status']=='ConfirmBooking')
									{


										$importBookingDetails	=	get_data('import_reservation_AGODA',array('hotel_id'=>hotel_id(),'booking_id'=>$book['booking_id']))->row_array();


										if(count($importBookingDetails)!=0)
										{
											$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival'
												]));
											$departure = date('Y-m-d',strtotime($importBookingDetails['departure']."-1 days"));


									            $roomMappingDetails = $this->db->query("SELECT B.property_id,B.rate_id FROM import_mapping_AGODA as A left join roommapping as B on A.map_id =B.import_mapping_id and B.channel_id=19
													where A.roomtype_id=".$importBookingDetails['room_id']." and A.rate_type_ID=".$importBookingDetails['rateplan_id']." and A.hotel_id =".hotel_id())->row_array();




									            if(count($roomMappingDetails)!=0)
									            {

									                require_once(APPPATH.'controllers/arrivalreservations.php');
									                $callAvailabilities = new arrivalreservations();

									                $callAvailabilities->updateavailability(0,$roomMappingDetails['property_id'],$roomMappingDetails['rate_id'],hotel_id(),$arrival,$departure ,'new');

									            }

										}
									}


								}
								elseif ($available['last_action']!=$data['last_action'])
								{
									$array_keys = array_keys($data);
									fetchColumn('import_reservation_AGODA',$array_keys);
									update_data('import_reservation_AGODA',$data,array('hotel_id'=>hotel_id(),'booking_id'=>$data['booking_id'],'hotelcode'=>$data['hotelcode']));
									$id=$available['import_reserv_id'];


									$benefits = $Hotelbook->benefits->benefit;
									if(count($benefits)>0)
									{

										foreach ($benefits as  $value)
										{	$available = get_data('extras',array('channel_id'=>19,'description'=>(string)$value['benefit_name']))->row_array();

											if(count($available)==0)
											{
													 $dataextra=array('reservation_id'=> $id ,'channel_id'=>19,'description'=>(string)$value['benefit_name'],'amount'=>0,'extra_date'=>date('Y-m-d'),);
						                        	insert_data('extras',$dataextra);

											}

										}
									}
									if ($data['status']=="CancelBooking" )
									{
										$history = array('channel_id'=>19,'reservation_id'=>$id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$data['last_action'] ,'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));
		    							$res = $this->db->insert('new_history',$history);

		    							$importBookingDetails	=	get_data('import_reservation_AGODA',array('hotel_id'=>hotel_id(),'booking_id'=>$book['booking_id']))->row_array();


											if(count($importBookingDetails)!=0)
											{
												$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival'
													]));
												$departure = date('Y-m-d',strtotime($importBookingDetails['departure']."-1 days"));


										            $roomMappingDetails = $this->db->query("SELECT B.property_id,B.rate_id FROM import_mapping_AGODA as A left join roommapping as B on A.map_id =B.import_mapping_id and B.channel_id=19
														where A.roomtype_id=".$importBookingDetails['room_id']." and A.rate_type_ID=".$importBookingDetails['rateplan_id']." and A.hotel_id =".hotel_id())->row_array();




										            if(count($roomMappingDetails)!=0)
										            {

										                require_once(APPPATH.'controllers/arrivalreservations.php');
										                $callAvailabilities = new arrivalreservations();

										                $callAvailabilities->updateavailability(0,$roomMappingDetails['property_id'],$roomMappingDetails['rate_id'],hotel_id(),$arrival,$departure ,'cancel');

										            }

											}
									}

								}





							}
					}

					//este bloque en comentario para ver si es necesario confirmar las reservaciones
					/*
					$confirm = date(DATE_ATOM);
					$confitm_time = explode("+", $confirm);
					$xml_confirm ='<?xml version="1.0" encoding="UTF-8"?>
	                      <request timestamp="'.strtotime(date('Y-m-d H:i')).'" type="7">
	                         <confirmations>
	                          <confirmation property_id="'.$ch_details->hotel_channel_id.'">
	                             <booking booking_id="'. (string)$book['booking_id'].'" status="'.$data['type'].'" supplierconfirmation="'.$data['confirmation_number'].'"/> 	<booking id="">
	                          </confirmation>
	                        </confirmations>
	                      </request>';
					$x_c_rq_data['channel_id'] 	= '19';
					$x_c_rq_data['user_id'] 	= '0';
					$x_c_rq_data['hotel_id'] 	= '0';
					$x_c_rq_data['message'] 	= $xml_confirm;
					$x_c_rq_data['type'] 		= 'REQ_AGD';
					$x_c_rq_data['section']		= 'RESER_CONFIRM_AGD';
					$ch = curl_init();
				  	curl_setopt( $ch, CURLOPT_URL, $URL );
				  	curl_setopt( $ch, CURLOPT_POST, true );
				 	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				  	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				  	curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_confirm);
					$output = curl_exec($ch);
					$x_c_rs_data['channel_id'] = '19';
					$x_c_rs_data['user_id'] = '0';
					$x_c_rs_data['hotel_id'] = '0';
					$x_c_rs_data['message'] = $output;
					$x_c_rs_data['type'] = 'RES_AGD';
					$x_c_rs_data['section'] = 'RESER_CONFIRM_AGD';
					mail("xml@hoteratus.com"," BookingConfirmNumber ",$x_c_rs_data);
					$data_apicon 	= simplexml_load_string($output);
					$error =@$data_apicon->errors;
					if($error != "")
					{
						$meg['result'] = '0';
						$meg['content']= $error.' from '.$cha_name.'. Try again!';
						echo json_encode($meg);
					}
					*/

					//$this->reservation_model->send_confirmation_email(19,$data['booking_id'],$data['user_id'],$data['hotel_id']);
					//$this->reservation_log(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);


				}
			}

			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from '.$cha_name.'!!!';
			echo json_encode($meg);
		}
		else if (insep_decode($channel_id) == 9) {


		    require_once(APPPATH . 'controllers/airbnb.php');

		    $callReservation = new Airbnb();

		    $result = $callReservation->getReservation($channel_id);

		    if (@$result['Enable'] == 'Enable') {
			$meg['result']  = '0';
			$meg['content'] = "Can't import rooms at " . $cha_name . ". Because this channel disabled.!!!";
			echo json_encode($meg);
		    } elseif (@$result['succes'] == 'Insert') {
			$meg['result']  = '1';
			$meg['content'] = 'Successfully import reservation from ' . $cha_name . '!!!';
			echo json_encode($meg);
		    } elseif (@$result['Error']) {
			$meg['result']  = '0';
			$meg['content'] = $result['Error'] . ' from ' . $cha_name . '. Try again!';
			echo json_encode($meg);
		    }

		    return;
		}
		else if(insep_decode($channel_id) == 15)
		{
			require_once(APPPATH.'controllers/travelrepublic.php');

			$callReservation	=	new travelrepublic();

			$result		=	$callReservation->getReservation($channel_id);

			if(@$result['Enable']=='Enable')
			{
				$meg['result']	= 	'0';
				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
				echo json_encode($meg);
			}
			elseif(@$result['succes']=='Insert')
			{
				$meg['result']	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			elseif(@$result['Error'])
			{
				$meg['result']	= 	'0';
				$meg['content']	= $result['Error'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
		}
		else if(insep_decode($channel_id) == 14)
		{
			require_once(APPPATH.'controllers/wbeds.php');

			$callReservation	=	new wbeds();

			$result		=	$callReservation->getReservation($channel_id);

			if(@$result['Enable']=='Enable')
			{
				$meg['result']	= 	'0';
				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
				echo json_encode($meg);
			}
			elseif(@$result['succes']=='Insert')
			{
				$meg['result']	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			elseif(@$result['Error'])
			{
				$meg['result']	= 	'0';
				$meg['content']	= $result['Error'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}
		}
			else if(insep_decode($channel_id) == 36)
		{


			require_once(APPPATH.'controllers/despegar.php');

			$callReservation	=	new despegar();

			$result		=	$callReservation->getReservation($channel_id);

			if(@$result['Enable']=='Enable')
			{
				$meg['result']	= 	'0';
				$meg['content']	=	"Can't import rooms at ".$cha_name.". Because this channel disabled.!!!";
				echo json_encode($meg);
			}
			elseif(@$result['succes']=='Insert')
			{
				$meg['result']	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			elseif(@$result['Error'])
			{
				$meg['result']	= 	'0';
				$meg['content']	= $result['Error'].' from '.$cha_name.'. Try again!';
				echo json_encode($meg);
			}

			return;
		}
		else
		{
			//echo 'sfsdf';
			$meg['result'] = '0';
			$meg['content']="Can't import reservation from '.$cha_name.'!!!";
			echo json_encode($meg);
		}
	}

	function getResrvationSummery($channel_id)
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
		if(insep_decode($channel_id)!='')
		{
			$cha_name = ucfirst(get_data(TBL_CHANNEL,array('channel_id'=>insep_decode($channel_id)))->row()->channel_name);
			$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->row();
		}
		else
		{
			$cha_name ='';
		}
		if(insep_decode($channel_id) == 2)
		{
			if($ch_details->xml_type==1 || $ch_details->xml_type==2)
			{
			$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
			<request>
			<username>'.$ch_details->user_name.'</username>
			<password>'.$ch_details->user_password.'</password>
			<hotel_id>'.$ch_details->hotel_channel_id.'</hotel_id>
			</request>';
			$x_r_rq_data['channel_id'] = '2';
			$x_r_rq_data['user_id'] = '0';
			$x_r_rq_data['hotel_id'] = '0';
			$x_r_rq_data['message'] = $xml_data;
			$x_r_rq_data['type'] = 'BOOK_REQ_SUMM';
			$x_r_rq_data['section'] = 'RESER';

			$URL = "https://secure-supply-xml.booking.com/hotels/xml/reservationssummary";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: text/xml'  ));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			$x_r_rs_data['channel_id'] = '2';
			$x_r_rs_data['user_id'] = '0';
			$x_r_rs_data['hotel_id'] = '0';
			$x_r_rs_data['message'] = $output;
			$x_r_rs_data['type'] = 'BOOK_RES_SUMM';
			$x_r_rs_data['section'] = 'RESER';
			//nsert_data(ALL_XML,$x_r_rs_data);
			//$this->reservationxmlmail($x_r_rs_data);
			$data_api = simplexml_load_string($output);



			$ruid = "";
			 preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
			$end = end($output);
	        if(is_array($end)){
	            $end_end = end($end);
	            $ruid = str_replace("!-- RUID: [", '', $end_end);
	            $ruid =  trim(str_replace('] --', '', $ruid));
	           // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
	        }else{
	            $ruid = str_replace("!-- RUID: [", '', $end);
	            $ruid =  trim(str_replace('] --', '', $ruid));
	          // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
	        }
	        $this->saveBooking_Booking($data_api,$ruid,$cha_name="Booking.com",$ch_details->hotel_channel_id,$x_r_rs_data);
	        $this->session->set_flashdata('map_update','import reservations from Booking.com!!!');
			}
			else
			{
				$meg['result'] = '1';
				$meg['content']="Can't import reservations from '.$cha_name.'!!!";
				echo json_encode($meg);
			}
		}


		}
	}

	function getResrvationCronFromBooking($channel_id='')
	{

		$AllConections = $this->db->query("select us.user_name,us.user_password,us.hotel_channel_id, mp.property_name, us.hotel_id, us.user_id
											from
											user_connect_channel as us
											LEFT join manage_hotel  mp on us.hotel_id = mp.hotel_id
											where us.channel_id = '2' and us.status ='enabled'")->result_array();
		$Mensaje = '';

		if (count($AllConections) >0 )

		{

			foreach ($AllConections as  $value) {
				extract($value);

				$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
				<request>
				<username>'.$user_name.'</username>
				<password>'.$user_password.'</password>
				<hotel_id>'.$hotel_channel_id.'</hotel_id>
				</request>';
				/* echo $xml_data; */
				$x_r_rq_data['channel_id'] = '2';
				$x_r_rq_data['user_id'] = '0';
				$x_r_rq_data['hotel_id'] = '0';
				$x_r_rq_data['message'] = $xml_data;
				$x_r_rq_data['type'] = 'BOOK_REQ';
				$x_r_rq_data['section'] = 'RESER';


				$URL = "https://secure-supply-xml.booking.com/hotels/xml/reservations";
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: text/xml'  ));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				/* echo $output; */


				$x_r_rs_data['channel_id'] = '2';
				$x_r_rs_data['user_id'] = '0';
				$x_r_rs_data['hotel_id'] = '0';
				$x_r_rs_data['message'] = $output;
				$x_r_rs_data['type'] = 'BOOK_RES';
				$x_r_rs_data['section'] = 'RESER';

				$data_api = simplexml_load_string($output);
				$ruid = "";
				preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
				$end = end($output);

		        if(is_array($end)){
		            $end_end = end($end);
		            $ruid = str_replace("!-- RUID: [", '', $end_end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		           // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
		        else{
		            $ruid = str_replace("!-- RUID: [", '', $end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		          // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
					$Mensaje .= '\n Successfully import '.count($data_api).' reservations Hotel: '.$property_name.' from Booking.com!!!';
		        if (count($data_api)>0 && strlen($data_api) > 0)
		       {
		       	 	$conect=0;

		       		foreach ($data_api as $value ) {
		     			if ($value['string']=='Authorization Required' || $value['string']=="Access denied for hotel $hotel_channel_id"  )
		     			{
		     				$conect=1;
		     			}
		     		}

		     		if ($conect==1)
		     		{

						$subject = $property_name."  XML status change to XML deactivated";
						$message = " Dear all, <br> <br> <br>  The property mentioned in the subject has been deactivated on XML by request of themselves. Please note that the accommodation needs to have a working fax or email in order to receive future reservations. <br> <br>  <br>  Hoteratus Team";


						$headers = "From: info@hoteratus.com\r\n";
						$headers .= "Reply-To: info@hoteratus.com\r\n";
						$headers .= "CC: xml@hoteratus.com\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	    				//mail($to, $subject, $message, $headers);
	    				mail('felix@hoteratus.com', $subject, $message, $headers);
	    				//mail("xml@hoteratus.com", $subject, $message, $headers);

	    				$this->db->query("update user_connect_channel set status ='disabled' where hotel_id = $hotel_id and channel_id =2");
		     		}
		     		else
		     		{
		     				$this->saveBooking_Booking_Cron($data_api,$ruid,$cha_name="Booking.com",$hotel_channel_id=$hotel_id,$x_r_rs_data);
		       				mail("datahernandez@gmail.com"," Reservaciones importadas hotel_id = $hotel_id ".count($data_api),count($data_api).' '.$property_name);
		     		}

				}


			}



				$meg['content']=$Mensaje;
				echo json_encode($meg);


		}

		else
		{

		}
	}



	function getResrvationCronFromBookingTest($channel_id='')
	{

		$Mensaje='';
					$hotel_id=44;
									$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
									<request>
									<username>18</username>
									<password>18</password>
									<hotel_id>18</hotel_id>
									</request>';
									/* echo $xml_data; */
									$x_r_rq_data['channel_id'] = '2';
									$x_r_rq_data['user_id'] = '0';
									$x_r_rq_data['hotel_id'] = '0';
									$x_r_rq_data['message'] = $xml_data;
									$x_r_rq_data['type'] = 'BOOK_REQ';
									$x_r_rq_data['section'] = 'RESER';


									$output ='<reservations>
 <reservation>
 <booked_at>2018-11-22T10:44:49+00:00</booked_at>
 <commissionamount>0</commissionamount>
 <currencycode>ZAR</currencycode>
 <customer>
 <address>118 van wijk</address>
 <cc_cvc></cc_cvc>
 <cc_expiration_date></cc_expiration_date>
 <cc_name></cc_name>
 <cc_number></cc_number>
 <cc_type></cc_type>
 <city>Nelspruit</city>
 <company></company>
 <countrycode>za</countrycode>
 <dc_issue_number></dc_issue_number>
 <dc_start_date></dc_start_date>
 <email>smamba.337761@guest.booking.com</email>
 <first_name>Selby</first_name>
 <last_name>Mamba</last_name>
 <remarks>You have a booker that would like free parking. (based on
availability)
Checking in 19:30</remarks>
 <telephone>+27 82 827 9136</telephone>
 <zip></zip>
 </customer>
 <date>2018-11-22</date>
 <guest_counts></guest_counts>
 <hotel_id>2485972</hotel_id>
 <hotel_name>Jeanettes Villa at Westlake</hotel_name>
 <id>1908936752</id>
 <modified_at>2018-11-22T11:54:28+00:00</modified_at>
 <status>cancelled</status>
 <time>11:44:49</time>
 <totalprice>0</totalprice>
 </reservation>
</reservations>
<!-- RUID:
[UmFuZG9tSVYkc2RlIyh9YQGHVMjHjATW6G8fNLGSbiULrreim4PtUF9x7UTVNUTkOligyc+w/o
89QdQQxSj7ZlfNo/1n4+H0]
-->';

				$x_r_rs_data['channel_id'] = '2';
				$x_r_rs_data['user_id'] = '0';
				$x_r_rs_data['hotel_id'] = '0';
				$x_r_rs_data['message'] = $output;
				$x_r_rs_data['type'] = 'BOOK_RES';
				$x_r_rs_data['section'] = 'RESER';

				$data_api = simplexml_load_string($output);

				$ruid = "";
				preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
				$end = end($output);

		        if(is_array($end)){
		            $end_end = end($end);
		            $ruid = str_replace("!-- RUID: [", '', $end_end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		           // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
		        else{
		            $ruid = str_replace("!-- RUID: [", '', $end);
		            $ruid =  trim(str_replace('] --', '', $ruid));
		          // $this->booking_model->store_ruid_booking($ruid,'Reservation Import');
		        }
					$Mensaje .= '\n Successfully import '.count($data_api).' reservations Hotel: 44 test from Booking.com!!!';
		        if (count($data_api)>0)
		       {
		       	$this->saveBooking_Booking_Cron($data_api,$ruid,$cha_name="Booking.com",$hotel_channel_id=$hotel_id,$x_r_rs_data);
		       	mail("datahernandez@gmail.com"," Reservaciones importadas".count($data_api),count($data_api).' Test');
				}






				$meg['content']=$Mensaje;
				echo json_encode($meg);



	}


	function getResrvationCronFromBooking_OneWay($channel_id='')
	{
		$cron_details = get_data(B_WAY,array('id'=>5,'type'=>5))->row_array();
		if(count($cron_details)!=0)
		{
			$user_name 		= 	$cron_details['username'];
			$user_password 	= 	$cron_details['password'];
		}
		else
		{
			$user_name 		= 	'';
			$user_password 	= 	'';
		}
		$xml_data ='<?xml version="1.0" encoding="UTF-8"?>
		<request>
		<username>'.$user_name.'</username>
		<password>'.$user_password.'</password>
		</request>';
		$x_r_rq_data['channel_id'] = '2';
		$x_r_rq_data['user_id'] = '0';
		$x_r_rq_data['hotel_id'] = '0';
		$x_r_rq_data['message'] = $xml_data;
		$x_r_rq_data['type'] = 'BOOK_REQ';
		$x_r_rq_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rq_data);
		//$this->reservationxmlmail($x_r_rq_data);
			$URL = "https://api.pci-proxy.com/v1/pull";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('X-CC-URL: https://secure-supply-xml.booking.com/hotels/xml/reservations',"X-CC-SIGN: 170426055204280709","X-CC-MERCHANT-ID: 3000011638",'Content-Type: text/xml'  ));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
		$x_r_rs_data['channel_id'] = '2';
		$x_r_rs_data['user_id'] = '0';
		$x_r_rs_data['hotel_id'] = '0';
		$x_r_rs_data['message'] = $output;
		$x_r_rs_data['type'] = 'BOOK_RES';
		$x_r_rs_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rs_data);
		// echo '<pre>';

		//print_r($output);

		$data_api = simplexml_load_string($output);
		/*print_r($data_api);*/

		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
		//print_r($output);
		$end = end($output);
		$ruid = "";
		if(is_array($end)){
			$end_end = end($end);
			$ruid = str_replace("!-- RUID: [", '', $end_end);
			$ruid =  trim(str_replace('] --', '', $ruid));
		}else{
			$ruid = str_replace("!-- RUID: [", '', $end);
			$ruid =  trim(str_replace('] --', '', $ruid));
		}
		$this->saveBooking_Booking($data_api,$ruid,$cha_name="Booking.com",$hotel_channel_id='',$x_r_rs_data);
	}

	function saveBooking_Booking($data_api,$ruid,$cha_name,$hotel_channel_id,$x_r_rs_data)
	{
		if($hotel_channel_id!='')
		{
			$data['hotel_id'] = $hotel_channel_id;

			$data['status'] = 'new';

			$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
			$Error		=	@$data_api->fault;
			if($Error)
			{
				$this->reservationxmlmail($x_r_rs_data);
				$Error	=	@$data_api->fault->attributes();
				$meg['result'] = '0';
				$meg['content']=$Error['string'].''.$cha_name.'!!!';
				echo json_encode($meg);
			}
			else
			{
				if(count($data_api) != 0)
				{
					$this->reservationxmlmail($x_r_rs_data);
				}
				foreach($data_api as $reservation)
				{
					$new_rooms=array();
					foreach($reservation as $key=>$value)
					{
						if($key!='customer' && $key!='reservation_extra_info' && $key!='room')
						{
							$data[$key] 				= 	(string)$value;
						}
						else if($key=='reservation_extra_info')
						{
							$reservation_extra_info = $reservation->reservation_extra_info->flags;
						}
					}
					$count = $this->db->select('import_reserv_id ')->from(BOOK_RESERV)->where(array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>@$data['id']))->count_all_results();

					if($count==0)
					{
						if(isset($reservation_extra_info))
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
						$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  				= 	$user_details['user_id'];
							$data['hotel_hotel_id'] 		= 	$user_details['hotel_id'];
							$room_datas['user_id'] 			= 	$user_details['user_id'];
							$room_datas['hotel_hotel_id'] 	= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  				= 	0;
							$data['hotel_hotel_id'] 		= 	0;
							$room_datas['user_id'] 			= 	0;
							$room_datas['hotel_hotel_id'] 	= 	0;
						}
						$customer = $reservation->customer;
						if($customer)
						{
							foreach($customer as $cus_value)
							{
								foreach($cus_value as $cus_key=>$cust_value)
								{
									if($cus_key == "cc_number" || $cus_key == "cc_type" || $cus_key == "cc_name" || $cus_key == "cc_cvc" ||$cus_key == "cc_expiration_date")
									{
										if($data['status'] == 'modified')
										{
											if($cust_value != "")
											{
												if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}

											}
										}
										else
										{
												if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
										}
									}
									else
									{
										$data[$cus_key] 			= 	(string)$cust_value;
									}
								}
							}
						}
						$room_datas['hotel_id'] 		= $data['hotel_id'];
						$room_datas['reservation_id'] 	= @$data['id'];
						$room_datas['date_time'] 		= @$data['date'].' '.@$data['time'];
						$room_datas['status'] 			= $data['status'];

						if($data['status'] =='new')
						{
							insert_data(BOOK_RESERV,$data);
							$room_datas['import_reserv_id'] = $this->db->insert_id();

							$room_details 	=	$reservation->room;
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
											$room_details_price =	$reservation->room->price[$i++];
											$price_date = $addon->attributes();
											$day_price_detailss='';
											foreach($price_date as $price_key=>$price_value)
											{
												if($price_key=='rate_id')
												{
													$room_datas['rate_id'] = (string)$price_value;
												}
												$day_price_detailss .=(string)$price_key.'='.(string)$price_value.'~';
											}
											$day_price_detailss.=$addon.'##';
											$room_datas['day_price_detailss'] .= ($day_price_detailss);

										}
									}
									$room_datas['day_price_detailss'] = trim($room_datas['day_price_detailss'],'###');
									$new_rooms[] 	= 	$room_datas['roomreservation_id'];
									insert_data(BOOK_ROOMS,$room_datas);
									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);
								}
							}

							$getRoomDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'reservation_id'=>@$data['id'],'hotel_id'=>$data['hotel_id']))->result_array();

							if(count($getRoomDetails)!=0)
							{
								foreach($getRoomDetails as $importBookingDetails)
								{
									$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
									$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
									$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();
									if(count($mappingDetails)!=0)
									{
										$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
										if(count($roomMappingDetails)!=0)
										{
											require_once(APPPATH.'controllers/mapping.php');
											$callAvailabilities = new Mapping();
											$callAvailabilities->importAvailabilities_Cron($user_details['user_id'],$user_details['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
										}
									}
								}
							}
						}
					}
				}
				$meg['result'] = '1';
				$meg['content']='Successfully import '.count($data_api).' reservations from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
		}
		else
		{
			$Error		=	@$data_api->fault;
			if($Error)
			{
				$this->reservationxmlmail($x_r_rs_data);
				$Error	=	@$data_api->fault->attributes();
				$meg['result'] = '0';
				$meg['content']=$Error['string'].''.$cha_name.'!!!';
				echo json_encode($meg);
			}
			else
			{
				if(count($data_api) != 0)
				{
					$this->reservationxmlmail($x_r_rs_data);
				}
				foreach($data_api as $reservation)
				{
					$new_rooms=array();
					foreach($reservation as $key=>$value)
					{
						if($key!='customer' && $key!='reservation_extra_info' && $key!='room')
						{
							$data[$key] 				= 	(string)$value;
						}
						else if($key=='reservation_extra_info')
						{
							$reservation_extra_info = $reservation->reservation_extra_info->flags;
						}
					}
					if(isset($reservation_extra_info))
					{
						if($reservation_extra_info != "")
						{
							foreach($reservation_extra_info as $booker_value)
							{
								$flag = '';
								foreach($booker_value as $flag_value)
								{
									$flag .= $flag_value->attributes().'###';
								}
							}
							if(isset($flags)){
								$data['flags']=trim($flag,'###');
							}
						}else{
							$extradet = "";
							foreach($reservation->reservation_extra_info as $extrainfo)
							{
								foreach($extrainfo as $info => $value)
								{
									if($info == "payer")
									{
										foreach($value as $val)
										{
											foreach($val->payment->attributes() as $key => $v)
											{
												$extradet .= $key.'='.$v."###";
											}
										}
									}
								}
							}
							$data['extradet'] = rtrim($extradet,'###');
						}
					}
					$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
					if(count($user_details)!=0)
					{
						$data['user_id']  				= 	$user_details['user_id'];
						$data['hotel_hotel_id'] 		= 	$user_details['hotel_id'];
						$room_datas['user_id'] 			= 	$user_details['user_id'];
						$room_datas['hotel_hotel_id'] 	= 	$user_details['hotel_id'];
					}
					else
					{
						$user_details['user_id'] = $data['user_id']	= 0;
						$user_details['hotel_id'] = $data['hotel_hotel_id'] = 0;
						$room_datas['user_id'] 			= 	0;
						$room_datas['hotel_hotel_id'] 	= 	0;
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

											if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
										}
									}else{
											if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
									}
								}else{
									$data[$cus_key] 			= 	(string)$cust_value;
								}
							}
						}
					}
					$room_datas['hotel_id'] 		= $data['hotel_id'];
					$room_datas['reservation_id'] 	= $data['id'];
					$room_datas['date_time'] 		= $data['date'].' '.$data['time'];
					$room_datas['status'] 			= $data['status'];
					if(isset($data['modified_at'])){
						$room_datas['modified_at'] 			=  $data['modified_at'];
					}

					if($data['status'] =='new' || $data['status'] =='modified')
					{
						$book_available = get_data(BOOK_RESERV,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']))->row_array();
						if(count($book_available)==0)
						{
							$array_keys = array_keys($data);
							fetchColumn(BOOK_RESERV,$array_keys);
							insert_data(BOOK_RESERV,$data);
							$room_datas['import_reserv_id'] = $this->db->insert_id();
						}
						else
						{
							$array_keys = array_keys($data);
							fetchColumn(BOOK_RESERV,$array_keys);
							update_data(BOOK_RESERV,$data,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
						}
						$room_details 	=	$reservation->room;
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
										$room_details_price =	$reservation->room->price[$i++];
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
								$new_rooms[] 	= 	$room_datas['roomreservation_id'];
								$room_availale	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']))->row_array();
								$data_addons['roomreservation_id'] = $room_datas['roomreservation_id'];
								if(count($room_availale)==0)
								{
									$array_keys = array_keys($room_datas);
									fetchColumn(BOOK_ROOMS,$array_keys);
									insert_data(BOOK_ROOMS,$room_datas);
									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);
									//$data_addons['room_resrv_id'] = $this->db->insert_id();
								}
								else
								{
									$array_keys = array_keys($room_datas);
									fetchColumn(BOOK_ROOMS,$array_keys);
									update_data(BOOK_ROOMS,$room_datas,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']));
									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);

								}

								if($data['status'] =='modified')
								{
									$resid = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']))->row()->room_res_id;

									$history = array('channel_id'=>2,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['modified_at'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

		        					$res = $this->db->insert('new_history',$history);
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
									$available_addons = get_data(BOOK_ADDON,array('roomreservation_id'=>$data_addons['roomreservation_id']))->row_array();
									if(count($available_addons)==0)
									{
										$array_keys = array_keys($data_addons);
										fetchColumn(BOOK_ROOMS,$array_keys);
										insert_data(BOOK_ADDON,$data_addons);
									}
									else
									{
										$array_keys = array_keys($data_addons);
										fetchColumn(BOOK_ROOMS,$array_keys);
										update_data(BOOK_ADDON,$data_addons,array('roomreservation_id'=>$data_addons['roomreservation_id']));
									}
								}
							}
						}

						$chk_rooms = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']),'roomreservation_id')->result_array();

						$names = array_column($chk_rooms, 'roomreservation_id');

						$result = array_diff($names, $new_rooms);

						if($result)
						{
							foreach($result as $un_rooms)
							{
								$importBookingDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'roomreservation_id'=>$un_rooms,'hotel_id'=>$data['hotel_id']))->row_array();
								if(count($importBookingDetails)!=0)
								{
									$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
									$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
									$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();
									if(count($mappingDetails)!=0)
									{
										$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
										if(count($roomMappingDetails)!=0)
										{
											require_once(APPPATH.'controllers/mapping.php');
											$callAvailabilities = new Mapping();
											$callAvailabilities->importAvailabilities_Cron($user_details['user_id'],$user_details['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
										}
									}
								}
								delete_data(BOOK_ROOMS,array('roomreservation_id'=>$un_rooms));
							}
						}
					}
					elseif($data['status'] =='cancelled')
					{
						$cancel_data['status'] 		= $data['status'];

						if(isset($data['modified_at'])){
							$cancel_data['modified_at']      = $data['modified_at'];
						}

						$room_datas['date_time']	= $data['date'].' '.$data['time'];
						$book_available = get_data(BOOK_RESERV,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']))->row_array();
						if(count($book_available)==0)
						{
							insert_data(BOOK_RESERV,$data);

						}else{
							update_data(BOOK_RESERV,$cancel_data,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
						}

						update_data(BOOK_ROOMS,$cancel_data,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']));

						$isbook = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']));
						if($isbook->num_rows != 0)
						{
							foreach($isbook->result() as $bdata)
							{
								$history = array('channel_id'=>2,'reservation_id'=>$bdata->room_res_id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$room_datas['date_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

								$res = $this->db->insert('new_history',$history);

								$this->reservation_log(2,$bdata->roomreservation_id,$user_details['user_id'],$user_details['hotel_id']);
							}
						}
					}

					$getRoomDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'reservation_id'=>$data['id'],'hotel_id'=>$data['hotel_id']))->result_array();

					if(count($getRoomDetails)!=0)
					{
						foreach($getRoomDetails as $importBookingDetails)
						{
							$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
							$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
							$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();
							if(count($mappingDetails)!=0)
							{
								$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
								if(count($roomMappingDetails)!=0)
								{
									require_once(APPPATH.'controllers/mapping.php');
									$callAvailabilities = new Mapping();
									$callAvailabilities->importAvailabilities_Cron($user_details['user_id'],$user_details['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
								}
							}
						}
					}
					$this->booking_model->send_mail_to_hoteliers($data['id'],$user_details['user_id'],$user_details['hotel_id']);
					$this->booking_model->store_ruid_booking($ruid,'Reservation Import',$user_details['user_id'],$user_details['hotel_id']);
				}
				$meg['result'] = '1';
				$meg['content']='Successfully import '.count($data_api).' reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
		}
	}
	function saveBooking_Booking_Cron($data_api,$ruid,$cha_name,$hotel_channel_id,$x_r_rs_data)
	{
		if($hotel_channel_id=='')
		{
			$data['hotel_id'] = $hotel_channel_id;

			$data['status'] = 'new';

			$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
			$Error		=	@$data_api->fault;
			if($Error)
			{
				$this->reservationxmlmail($x_r_rs_data);
				$Error	=	@$data_api->fault->attributes();
				$meg['result'] = '0';
				$meg['content']=$Error['string'].''.$cha_name.'!!!';
				echo json_encode($meg);
			}
			else
			{
				if(count($data_api) != 0)
				{
					$this->reservationxmlmail($x_r_rs_data);
				}
				foreach($data_api as $reservation)
				{
					$new_rooms=array();
					foreach($reservation as $key=>$value)
					{
						if($key!='customer' && $key!='reservation_extra_info' && $key!='room')
						{
							$data[$key] 				= 	(string)$value;
						}
						else if($key=='reservation_extra_info')
						{
							$reservation_extra_info = $reservation->reservation_extra_info->flags;
						}
					}
					$count = $this->db->select('import_reserv_id ')->from(BOOK_RESERV)->where(array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>@$data['id']))->count_all_results();

					if($count==0)
					{
						if(isset($reservation_extra_info))
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
						$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_channel_id'=>$data['hotel_id']))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  				= 	$user_details['user_id'];
							$data['hotel_hotel_id'] 		= 	$user_details['hotel_id'];
							$room_datas['user_id'] 			= 	$user_details['user_id'];
							$room_datas['hotel_hotel_id'] 	= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  				= 	0;
							$data['hotel_hotel_id'] 		= 	0;
							$room_datas['user_id'] 			= 	0;
							$room_datas['hotel_hotel_id'] 	= 	0;
						}
						$customer = $reservation->customer;
						if($customer)
						{
							foreach($customer as $cus_value)
							{
								foreach($cus_value as $cus_key=>$cust_value)
								{
									if($cus_key == "cc_number" || $cus_key == "cc_type" || $cus_key == "cc_name" || $cus_key == "cc_cvc" ||$cus_key == "cc_expiration_date")
									{
										if($data['status'] == 'modified')
										{
											if($cust_value != "")
											{
												if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
											}
										}
										else
										{
											if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
										}
									}
									else
									{
										$data[$cus_key] 			= 	(string)$cust_value;
									}
								}
							}
						}
						$room_datas['hotel_id'] 		= $data['hotel_id'];
						$room_datas['reservation_id'] 	= @$data['id'];
						$room_datas['date_time'] 		= @$data['date'].' '.@$data['time'];
						$room_datas['status'] 			= $data['status'];


						if($data['status'] =='new')
						{
							insert_data(BOOK_RESERV,$data);
							$room_datas['import_reserv_id'] = $this->db->insert_id();


							$room_details 	=	$reservation->room;
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
											$room_details_price =	$reservation->room->price[$i++];
											$price_date = $addon->attributes();
											$day_price_detailss='';
											foreach($price_date as $price_key=>$price_value)
											{
												if($price_key=='rate_id')
												{
													$room_datas['rate_id'] = (string)$price_value;
												}
												$day_price_detailss .=(string)$price_key.'='.(string)$price_value.'~';
											}
											$day_price_detailss.=$addon.'##';
											$room_datas['day_price_detailss'] .= ($day_price_detailss);

										}
									}
									$room_datas['day_price_detailss'] = trim($room_datas['day_price_detailss'],'###');
									$new_rooms[] 	= 	$room_datas['roomreservation_id'];
									insert_data(BOOK_ROOMS,$room_datas);

									$this->load->model("room_auto_model");
									$Roomnumber	=	$this->room_auto_model->Assign_room(2,$room_datas['roomreservation_id'],$room_datas['hotel_id'] );
									$indata['RoomNumber']=$Roomnumber;

									update_data(BOOK_ROOMS,$indata,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'roomreservation_id'=>$room_datas['roomreservation_id']));



									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);
								}
							}

							$getRoomDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'reservation_id'=>@$data['id'],'hotel_id'=>$data['hotel_id']))->result_array();

							if(count($getRoomDetails)!=0)
							{
								foreach($getRoomDetails as $importBookingDetails)
								{
									$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
									$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
									$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();
									if(count($mappingDetails)!=0)
									{
										$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
										if(count($roomMappingDetails)!=0)
										{
											require_once(APPPATH.'controllers/mapping.php');
											$callAvailabilities = new Mapping();
											$callAvailabilities->importAvailabilities_Cron($user_details['user_id'],$user_details['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
										}
									}
								}
							}
						}
					}
				}

			}
		}
		else
		{

			$Error		=	@$data_api->fault;
			if($Error)
			{
				$this->reservationxmlmail($x_r_rs_data);
				$Error	=	@$data_api->fault->attributes();
				$meg['result'] = '0';
				$meg['content']=$Error['string'].''.$cha_name.'!!!';
				echo json_encode($meg);
			}
			else
			{
				if(count($data_api) != 0)
				{
					$this->reservationxmlmail($x_r_rs_data);
				}

				foreach($data_api as $reservation)
				{
					$new_rooms=array();
					foreach($reservation as $key=>$value)
					{
						if($key!='customer' && $key!='reservation_extra_info' && $key!='room')
						{
							$data[$key] 				= 	(string)$value;
						}
						else if($key=='reservation_extra_info')
						{
							$reservation_extra_info = $reservation->reservation_extra_info->flags;
						}
					}

					if(isset($reservation_extra_info))
					{
						if($reservation_extra_info != "")
						{
							foreach($reservation_extra_info as $booker_value)
							{
								$flag = '';
								foreach($booker_value as $flag_value)
								{
									$flag .= $flag_value->attributes().'###';
								}
							}
							if(isset($flags)){
								$data['flags']=trim($flag,'###');
							}
						}else{
							$extradet = "";
							foreach($reservation->reservation_extra_info as $extrainfo)
							{
								foreach($extrainfo as $info => $value)
								{
									if($info == "payer")
									{
										foreach($value as $val)
										{
											foreach($val->payment->attributes() as $key => $v)
											{
												$extradet .= $key.'='.$v."###";
											}
										}
									}
								}
							}
							$data['extradet'] = rtrim($extradet,'###');
						}
					}


					$user_details						= 	get_data(CONNECT,array('channel_id'=>'2','hotel_id'=>$hotel_channel_id))->row_array();


					if(count($user_details)!=0)
					{
						$data['user_id']  				= 	$user_details['user_id'];
						$data['hotel_hotel_id'] 		= 	$user_details['hotel_id'];
						$room_datas['user_id'] 			= 	$user_details['user_id'];
						$room_datas['hotel_hotel_id'] 	= 	$user_details['hotel_id'];
					}
					else
					{
						$user_details['user_id'] = $data['user_id']	= 0;
						$user_details['hotel_id'] = $data['hotel_hotel_id'] = 0;
						$room_datas['user_id'] 			= 	0;
						$room_datas['hotel_hotel_id'] 	= 	0;
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
											if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
										}
									}else{
										if($cus_key == "cc_number" )
												{
													require_once(APPPATH.'controllers/tokenex.php');

													$tokenex = new tokenex();
													$data[$cus_key]   =(string)safe_b64encode($tokenex->tokenizar($cust_value));

												}
												else
												{
													$data[$cus_key]         =  (string)safe_b64encode($cust_value);
												}
									}
								}else{
									$data[$cus_key] 			= 	(string)$cust_value;
								}
							}
						}
					}
					$room_datas['hotel_id'] 		= $data['hotel_id'];
					$room_datas['reservation_id'] 	= $data['id'];
					$room_datas['date_time'] 		= $data['date'].' '.$data['time'];
					$room_datas['status'] 			= $data['status'];


					if(isset($data['modified_at'])){
						$room_datas['modified_at'] 			=  $data['modified_at'];
					}

					if($data['status'] =='new' || $data['status'] =='modified')
					{
						$book_available = get_data(BOOK_RESERV,array('hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']))->row_array();

            if(count($book_available)==0)
						{
							$array_keys = array_keys($data);
							fetchColumn(BOOK_RESERV,$array_keys);
							insert_data(BOOK_RESERV,$data);
							$room_datas['import_reserv_id'] = $this->db->insert_id();
						}
						else
						{
							$array_keys = array_keys($data);
							fetchColumn(BOOK_RESERV,$array_keys);
							update_data(BOOK_RESERV,$data,array('hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
              $room_datas['import_reserv_id'] = $book_available['import_reserv_id'];
						}
						$room_details 	=	$reservation->room;
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
										$room_details_price =	$reservation->room->price[$i++];
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
								$new_rooms[] 	= 	$room_datas['roomreservation_id'];
								$room_availale	=	$this->db->query(" select * from import_reservation_BOOKING_ROOMS where hotel_id=".$data['hotel_id']." and hotel_hotel_id=".$user_details['hotel_id'].
                " and reservation_id=".$data['id']." and roomreservation_id=".$room_datas['roomreservation_id'])->result_array();

                $data_addons['roomreservation_id'] = $room_datas['roomreservation_id'];


								if(count($room_availale)==0)
								{
									$array_keys = array_keys($room_datas);
									fetchColumn(BOOK_ROOMS,$array_keys);
									insert_data(BOOK_ROOMS,$room_datas);
									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);
									//$data_addons['room_resrv_id'] = $this->db->insert_id();
								}
								else
								{
									$array_keys = array_keys($room_datas);
									fetchColumn(BOOK_ROOMS,$array_keys);
									update_data(BOOK_ROOMS,$room_datas,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']));
									$this->reservation_log(2,$room_datas['roomreservation_id'],$user_details['user_id'],$user_details['hotel_id']);

								}

								if($data['status'] =='modified')
								{
									$resid = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id'],'roomreservation_id'=>$room_datas['roomreservation_id']))->row()->room_res_id;

									$history = array('channel_id'=>2,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['modified_at'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

		        					$res = $this->db->insert('new_history',$history);
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
									$available_addons = get_data(BOOK_ADDON,array('roomreservation_id'=>$data_addons['roomreservation_id']))->row_array();
									if(count($available_addons)==0)
									{
										$array_keys = array_keys($data_addons);
										fetchColumn(BOOK_ROOMS,$array_keys);
										insert_data(BOOK_ADDON,$data_addons);
									}
									else
									{
										$array_keys = array_keys($data_addons);
										fetchColumn(BOOK_ROOMS,$array_keys);
										update_data(BOOK_ADDON,$data_addons,array('roomreservation_id'=>$data_addons['roomreservation_id']));
									}
								}
							}
						}

						$chk_rooms = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']),'roomreservation_id')->result_array();

						$names = array_column($chk_rooms, 'roomreservation_id');

						$result = array_diff($names, $new_rooms);



						if($result)
						{
							foreach($result as $un_rooms)
							{
								$importBookingDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'roomreservation_id'=>$un_rooms,'hotel_id'=>$data['hotel_id']))->row_array();
								if(count($importBookingDetails)!=0)
								{
									$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
									$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']));
									$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();
									if(count($mappingDetails)!=0)
									{
										$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
										if(count($roomMappingDetails)!=0)
										{
										//	require_once(APPPATH.'controllers/mapping.php');
											//$callAvailabilities = new Mapping();
											//$callAvailabilities->importAvailabilities_Cron($user_details['user_id'],$user_details['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['import_mapping_id'],'mapping');
										}
									}
								}
								//delete_data(BOOK_ROOMS,array('roomreservation_id'=>$un_rooms));
							}
						}
					}
					elseif($data['status'] =='cancelled')
					{
						$cancel_data['status'] 		= $data['status'];

						if(isset($data['modified_at'])){
							$cancel_data['modified_at']      = $data['modified_at'];
						}

						$room_datas['date_time']	= $data['date'].' '.$data['time'];
						$book_available = get_data(BOOK_RESERV,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']))->row_array();
						if(count($book_available)==0)
						{
							insert_data(BOOK_RESERV,$data);

						}else{
							update_data(BOOK_RESERV,$cancel_data,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'id'=>$data['id']));
						}

						update_data(BOOK_ROOMS,$cancel_data,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']));

						$isbook = get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'hotel_id'=>$data['hotel_id'],'reservation_id'=>$data['id']));
						if($isbook->num_rows != 0)
						{
							foreach($isbook->result() as $bdata)
							{
								$history = array('channel_id'=>2,'reservation_id'=>$bdata->room_res_id,'description'=>"Reservation Cancelled",'amount'=>'','extra_date'=>$room_datas['date_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

								$res = $this->db->insert('new_history',$history);

								$this->reservation_log(2,$bdata->roomreservation_id,$user_details['user_id'],$user_details['hotel_id']);
							}
						}
					}


					if ($data['status'] =='cancelled' || $data['status'] =='new' )
					{


						$getRoomDetails	=	get_data(BOOK_ROOMS,array('user_id'=>$user_details['user_id'],'hotel_hotel_id'=>$user_details['hotel_id'],'reservation_id'=>$data['id'],'hotel_id'=>$data['hotel_id']))->result_array();


						if(count($getRoomDetails)!=0)
						{
							foreach($getRoomDetails as $importBookingDetails)
							{
								$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival_date']));
								$departure = date('Y-m-d',strtotime($importBookingDetails['departure_date']."-1 days"));
								$mappingDetails		=	get_data(BOOKING,array('B_room_id'=>$importBookingDetails['id'],'B_rate_id'=>$importBookingDetails['rate_id'],'owner_id'=>$user_details['user_id'],'hotel_id'=>$user_details['hotel_id']),'import_mapping_id')->row_array();


								if(count($mappingDetails)!=0)
								{
									$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['import_mapping_id'],'channel_id'=>2))->row_array();
									if(count($roomMappingDetails)!=0)
									{
										require_once(APPPATH.'controllers/arrivalreservations.php');
										$callAvailabilities = new arrivalreservations();

										$callAvailabilities->updateavailability(2,$roomMappingDetails['property_id'], $roomMappingDetails['rate_id'],$user_details['hotel_id'],$arrival,$departure ,$data['status']);
									}
								}
							}
						}
					}


					$this->booking_model->send_mail_to_hoteliers($data['id'],$user_details['user_id'],$user_details['hotel_id']);
					$this->booking_model->store_ruid_booking($ruid,'Reservation Import',$user_details['user_id'],$user_details['hotel_id']);
				}

			}
		}
	}

	function getResrvationCronFromExpdia($channel_id='')
	{
		//EQC2836662
		//rb55uz35
		$user_name 		= 'EQC_hoteratus';
		$user_password 	= 'QeCA5tc2';
		$xml_data 		= '<?xml version="1.0" encoding="UTF-8"?>
							<BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							</BookingRetrievalRQ>
						  ';
		$x_r_rq_data['channel_id'] = '1';
		$x_r_rq_data['user_id'] = '0';
		$x_r_rq_data['hotel_id'] = '0';
		$x_r_rq_data['message'] = $xml_data;
		$x_r_rq_data['type'] = 'REQ';
		$x_r_rq_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rq_data);
		//$this->reservationxmlmail($x_r_rq_data);
		$URL = "https://ws.expediaquickconnect.com/connect/br";
		$ch = curl_init($URL);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output			= curl_exec($ch);
		$x_r_rs_data['channel_id'] = '1';
		$x_r_rs_data['user_id'] = '0';
		$x_r_rs_data['hotel_id'] = '0';
		$x_r_rs_data['message'] = $output;
		$x_r_rs_data['type'] = 'RES';
		$x_r_rs_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rs_data);
		//$this->reservationxmlmail($x_r_rs_data);
		$data_api 		= 	simplexml_load_string($output);
		//print_r($data_api);
		$error 			=	@$data_api->Error;
		$BookingListing = 	@$data_api->Bookings->Booking;
		if($error !="" || count($BookingListing) != 0)
		{
			$this->reservationxmlmail($x_r_rs_data);
		}
		if($error != "")
		{
			$meg['result'] = '0';
			$meg['content']= $error.' from Expedia Try again!';
			echo json_encode($meg);
		}
		else if($error == "")
		{
			$BookingListing = @$data_api->Bookings;
			if($BookingListing){
				foreach($BookingListing as $Booking)
				{
					foreach($Booking as $Booking_key=>$book)
					{
						$bookattr						=	$book->attributes();
						$data['booking_id'] 			= 	$bookattr['id'];
						$data['type'] 					=	(string)$bookattr['type'];
						$result 						= 	rand(0,999999999);
						$data['confirmation_number'] 	= 	$result;
						$Hotel						=	$book->Hotel->attributes();
						$data['hotelid'] 			= 	$Hotel['id'];

						$hotel_id = $Hotel['id'];
						$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  		= 	$user_details['user_id'];
							$data['hotel_id'] 		= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  		= 	0;
							$data['hotel_id'] 		= 	0;
						}
						$data['created_time'] 		=	(string)$bookattr['createDateTime'];
						if($data['type'] != "Cancel")
						{
							/*$data['created_time'] 		=	(string)$bookattr['createDateTime'];*/
							$data['source'] 			= 	(string)$bookattr['source'];
							$data['status'] 			= 	(string)$bookattr['status'];

							$Hotel						=	$book->Hotel->attributes();
							$data['hotelid'] 			= 	$Hotel['id'];

							$hotel_id = $Hotel['id'];
							$confirm_data['hotelid_'.$hotel_id] = array(
																		'booking_id'=>$data['booking_id'],
																		'type'=>$data['type'],
																		'confirmation_number'=>$result
																	 );

							$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
							if(count($user_details)!=0)
							{
								$data['user_id']  		= 	$user_details['user_id'];
								$data['hotel_id'] 		= 	$user_details['hotel_id'];
							}
							else
							{
								$data['user_id']  		= 	0;
								$data['hotel_id'] 		= 	0;
							}
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
								$data['expireDate'] = "";
								$data['SeriesCode'] = "";
								if(count($cardDetails) != 0){
									$data['cardCode'] = (string)$cardDetails['cardCode'];
									$data['cardNumber'] = (string)$cardDetails['cardNumber'];
									$data['expireDate'] = (string)$cardDetails['expireDate'];
									$data['SeriesCode'] = (string)$cardDetails['SeriesCode'];
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
							$data['SpecialRequest'] = (string)$book->SpecialRequest;

							$available = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

							if(count($available)==0)
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								insert_data('import_reservation_EXPEDIA',$data);

								$room_datas['import_reserv_id'] = $this->db->insert_id();

									$this->load->model("room_auto_model");
									$Roomnumber	=	$this->room_auto_model->Assign_room(1,$room_datas['roomreservation_id'],$data['hotel_id'] );
									$indata['RoomNumber']=$Roomnumber;

									update_data('import_reservation_EXPEDIA',$indata,array('hotel_id'=>$data['hotel_id'],'import_reserv_id'=>$room_datas['import_reserv_id']));

							}
							else
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
							}

							if($data['type'] == "Modify")
							{
								$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

								$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['created_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

	        					$res = $this->db->insert('new_history',$history);
							}

						}
						else
						{
							$confirm_data['hotelid_'.$hotel_id] = array(
																		'booking_id'=>$data['booking_id'],
																		'type'=>$data['type'],
																		'confirmation_number'=>$result
																	 );
							update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));

							$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

							$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Canelled",'extra_id'=>2,'amount'=>'','extra_date'=>$data['created_time'],'history_date'=>date('Y-m-d H:i:s'));

	        				$res = $this->db->insert('new_history',$history);
						}
						$this->reservation_model->send_confirmation_email(1,$data['booking_id'],$data['user_id'],$data['hotel_id']);
						$this->reservation_log(1,$data['booking_id'],$data['user_id'],$data['hotel_id']);

						$importBookingDetails	=	get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

						if(count($importBookingDetails)!=0)
						{
							$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival']));
							$departure = date('Y-m-d',strtotime($importBookingDetails['departure']));
							$roomtypeid = $importBookingDetails['roomTypeID'];
							$rateplanid = $importBookingDetails['ratePlanID'];
							$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$data['user_id'],$data['hotel_id']);

							if(count($roomdetails) != 0)
							{
								$roomtypeid = $roomdetails['roomtypeId'];
								$rateplanid = $roomdetails['rateplanid'];
							}

							$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'map_id')->row_array();

							if(count($mappingDetails)!=0)
							{
								$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>1))->row_array();
								if(count($roomMappingDetails)!=0)
								{
									require_once(APPPATH.'controllers/mapping.php');
									$callAvailabilities = new Mapping();
									$callAvailabilities->importAvailabilities_Cron($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
								}
							}
						}
					}
				}

				if(@$confirm_data)
				{

					foreach($confirm_data as $key=>$c_data)
					{
						$confirm = date(DATE_ATOM);
						$confitm_time = explode("+", $confirm);
						$hotel_channel_id = explode('_', $key);
						$hotel_id = '<Hotel id="'.$hotel_channel_id[1].'"/>';
						$confirm_xml = "";
						foreach($c_data as $val)
						{
							$confirm_xml .= '<BookingConfirmNumber bookingID="'.$c_data['booking_id'].'" bookingType="'.$c_data['type'].'" confirmNumber="'.$c_data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>';
						}
						$xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09">
										<Authentication username="'.$user_name.'" password="'.$user_password.'"/>'.$hotel_id.'
										<BookingConfirmNumbers>'.$confirm_xml.'
										</BookingConfirmNumbers>
										</BookingConfirmRQ>
										';
						$x_c_rq_data['channel_id'] 	= '1';
						$x_c_rq_data['user_id'] 	= '0';
						$x_c_rq_data['hotel_id'] 	= '0';
						$x_c_rq_data['message'] 	= $xml_confirm;
						$x_c_rq_data['type'] = 'REQ_EXP';
						$x_c_rq_data['section'] = 'RESER_CONFIRM_EXP';
						//insert_data(ALL_XML,$x_c_rq_data);
						$URL = "https://ws.expediaquickconnect.com/connect/bc";

						$ch = curl_init($URL);
						//curl_setopt($ch, CURLOPT_MUTE, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output		= curl_exec($ch);
						$x_c_rs_data['channel_id'] = '1';
						$x_c_rs_data['user_id'] = '0';
						$x_c_rs_data['hotel_id'] = '0';
						$x_c_rs_data['message'] = $output;
						$x_c_rs_data['type'] = 'RES_EXP';
						$x_c_rs_data['section'] = 'RESER_CONFIRM_EXP';
						//insert_data(ALL_XML,$x_c_rs_data);
						$this->reservationxmlmail($x_c_rs_data);
						$data_api 	= simplexml_load_string($output);
						$error =@$data_api->Error;
						if($error != "")
						{
							$meg['result'] = '0';
							$meg['content']= $error.' from '.$cha_name.'. Try again!';
							echo json_encode($meg);
						}
					}
				}
			}
			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from Expedia!!!';
			echo json_encode($meg);
		}
	}

	function getMissedReservationFromBooking($channel_id = '')
	{
		$output = '<reservations>
  <reservation>
    <booked_at>2016-10-06T14:16:23+00:00</booked_at>
    <commissionamount>61</commissionamount>
    <currencycode>EUR</currencycode>
    <customer>
      <address></address>
      <cc_cvc>554</cc_cvc>
      <cc_expiration_date>12/2017</cc_expiration_date>
      <cc_name>Kym  Dickenson</cc_name>
      <cc_number>5566259496464428</cc_number>
      <cc_type>Booking virtual card (MasterCard)</cc_type>
      <city>.</city>
      <company></company>
      <countrycode>gb</countrycode>
      <dc_issue_number>71515258</dc_issue_number>
      <dc_start_date></dc_start_date>
      <email>kdicke.869677@guest.booking.com</email>
      <first_name>Kym</first_name>
      <last_name>Dickenson</last_name>
      <remarks> Approximate time of arrival: between 14:00 and 15:00 hours
BED PREFERENCE:Studio (2 Adults): 1 double - Studio (2 Adults): 1 double


</remarks>
      <telephone>+4407783380998</telephone>
      <zip></zip>
    </customer>
    <date>2016-10-06</date>
    <hotel_id>12398</hotel_id>
    <hotel_name>Aretousa Villas</hotel_name>
    <id>1953162786</id>
    <modified_at>2016-10-06T14:16:25+00:00</modified_at>
    <reservation_extra_info>
      <payer>
        <payments>
          <payment amount="305.0000"
                   currency="EUR"
                   payment_type="payment_via_Booking.com"
                   payout_type="Booking virtual credit card" />
        </payments>
      </payer>
    </reservation_extra_info>
    <room>
      <arrival_date>2017-05-30</arrival_date>
      <commissionamount>61</commissionamount>
      <currencycode>EUR</currencycode>
      <departure_date>2017-06-06</departure_date>
      <extra_info>Air-conditioned studio with cable TV, fridge, electric kettle, netbook provided upon request. Also, it has a spacious private balcony with pool or mountain view. Some of these rooms have a safe and a kitchenette with cooking utensils. Some are traditional Cycladic rooms.</extra_info>
      <facilities>Shower, Safety Deposit Box, Air Conditioning, Hairdryer, Kitchenette, Balcony, Refrigerator, Toilet, Patio, Heating, Cable Channels, Flat-screen TV, Tile/Marble floor, View, Electric kettle, Kitchenware</facilities>
      <guest_name>Kym  Dickenson</guest_name>
      <id>1239801</id>
      <info>Enjoy a convenient Breakfast at the property for &amp;#x20AC;&amp;nbsp;6 per person, per night. Children and Extra Bed Policy: All children are welcome. One child under 2 years stays free of charge in a childs cot/crib. One child from 2 to 12 years is charged 20 % of the room stay per night in an extra bed. The maximum number of extra beds/childrens cots permitted in a room is 1.  Deposit Policy: The total price of the reservation may be charged anytime after booking.  Cancellation Policy: Please note, if cancelled, modified or in case of no-show, the total price of the reservation will be charged. </info>
      <max_children>0</max_children>
      <meal_plan>Enjoy a convenient Breakfast at the property for EUR 6 per person, per night.</meal_plan>
      <name>Studio (2 Adults)</name>
      <numberofguests>1</numberofguests>
      <price date="2017-05-30"
             genius_rate="no"
             rate_id="957874">40</price>
      <price date="2017-05-31"
             genius_rate="no"
             rate_id="957874">40</price>
      <price date="2017-06-01"
             genius_rate="no"
             rate_id="957874">45</price>
      <price date="2017-06-02"
             genius_rate="no"
             rate_id="957874">45</price>
      <price date="2017-06-03"
             genius_rate="no"
             rate_id="957874">45</price>
      <price date="2017-06-04"
             genius_rate="no"
             rate_id="957874">45</price>
      <price date="2017-06-05"
             genius_rate="no"
             rate_id="957874">45</price>
      <remarks></remarks>
      <roomreservation_id>1122055379</roomreservation_id>
      <smoking>0</smoking>
      <totalprice>305</totalprice>
    </room>
    <status>new</status>
    <time>16:16:23</time>
    <totalprice>305</totalprice>
  </reservation>
</reservations>
<!-- RUID: [UmFuZG9tSVYkc2RlIyh9YTqKzsSwXvwJ/kOTGdmQUj4Ph27qlM+XoBNABqRtVPZsVRZi/Wpf/4VZJhGiCGqc6ldVFXfgCpNy] -->';

		$data_api = simplexml_load_string($output);
		$x_r_rs_data = "";
		/*print_r($data_api);*/

		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($output), $output);
		//print_r($output);
		$end = end($output);
		$ruid = "";
		if(is_array($end)){
			$end_end = end($end);
			$ruid = str_replace("!-- RUID: [", '', $end_end);
			$ruid =  trim(str_replace('] --', '', $ruid));
		}else{
			$ruid = str_replace("!-- RUID: [", '', $end);
			$ruid =  trim(str_replace('] --', '', $ruid));
		}
		$this->saveBooking_Booking($data_api,$ruid,$cha_name="Booking.com",$hotel_channel_id='',$x_r_rs_data);
	}

	function getMissedResrvationFromExpdia($channel_id='')
	{
		//EQC2836662
		//rb55uz35
		/*user_name 		= 'EQC_hoteratus';
		/*$user_password 	= 'QeCA5tc2';
		$xml_data 		= '<?xml version="1.0" encoding="UTF-8"?>
							<BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							</BookingRetrievalRQ>
						  ';
		$x_r_rq_data['channel_id'] = '1';
		$x_r_rq_data['user_id'] = '0';
		$x_r_rq_data['hotel_id'] = '0';
		$x_r_rq_data['message'] = $xml_data;
		$x_r_rq_data['type'] = 'REQ';
		$x_r_rq_data['section'] = 'RESER';
		insert_data(ALL_XML,$x_r_rq_data);
		$URL = "https://ws.expediaquickconnect.com/connect/br";
		$ch = curl_init($URL);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output			= curl_exec($ch);
		$x_r_rs_data['channel_id'] = '1';
		$x_r_rs_data['user_id'] = '0';
		$x_r_rs_data['hotel_id'] = '0';
		$x_r_rs_data['message'] = $output;
		$x_r_rs_data['type'] = 'RES';
		$x_r_rs_data['section'] = 'RESER';
		insert_data(ALL_XML,$x_r_rs_data);*/
		$output = '<?xml version="1.0" encoding="UTF-8"?>


<BookingRetrievalRS xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
    <Bookings>
        <Booking id="752295783" type="Book" createDateTime="2016-11-10T07:55:00Z" source="A-Hotels.com" status="pending">
            <Hotel id="4430964" />
            <RoomStay roomTypeID="200443054" ratePlanID="208494338A">
                <StayDate arrival="2017-07-28" departure="2017-08-11" />
                <GuestCount adult="2" child="2" />
                <PerDayRates currency="EUR">
                    <PerDayRate stayDate="2017-07-28" baseRate="98.23" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-07-29" baseRate="98.23" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-07-30" baseRate="98.23" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-07-31" baseRate="98.23" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-01" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-02" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-03" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-04" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-05" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-06" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-07" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-08" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-09" baseRate="106.22" extraPersonFees="16.48" />
                    <PerDayRate stayDate="2017-08-10" baseRate="106.22" extraPersonFees="16.48" />
                </PerDayRates>
                <Total amountAfterTaxes="1685.84" currency="EUR" />
                <PaymentCard cardCode="VI" cardNumber="4979582479794527" seriesCode="247" expireDate="0918">
                    <CardHolder name="cinthia lang" address="ADDRESS NOT AVAILABLE" city="XXXXX" country="GB" postalCode="00000" />
                </PaymentCard>
            </RoomStay>
            <PrimaryGuest>
                <Name givenName="cinthia" surname="lang" />
                <Phone countryCode="0" cityAreaCode="0" number="0609687212" />
                <Email>lang.cinthia@free.fr</Email>
            </PrimaryGuest>
            <SpecialRequest code="5">Hotel Collect Booking Collect Payment From Guest
            </SpecialRequest>
            <SpecialRequest code="2.1">Non-Smoking</SpecialRequest>
            <SpecialRequest code="1.13">1 double bed</SpecialRequest>
        </Booking>
    </Bookings>
</BookingRetrievalRS>';
		$data_api 		= simplexml_load_string($output);
		//print_r($data_api);
		$error 			=@$data_api->Error;
		if($error != "")
		{
			$meg['result'] = '0';
			$meg['content']= $error.' from Expedia Try again!';
			echo json_encode($meg);
		}
		else if($error == "")
		{
			$BookingListing = @$data_api->Bookings;
			if($BookingListing){
				foreach($BookingListing as $Booking)
				{
					foreach($Booking as $Booking_key=>$book)
					{
						$bookattr						=	$book->attributes();
						$data['booking_id'] 			= 	$bookattr['id'];
						$data['type'] 					=	(string)$bookattr['type'];
						$result 						= 	rand(0,999999999);
						$data['confirmation_number'] 	= 	$result;
						$Hotel						=	$book->Hotel->attributes();
						$data['hotelid'] 			= 	$Hotel['id'];

						$hotel_id = $Hotel['id'];
						$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  		= 	$user_details['user_id'];
							$data['hotel_id'] 		= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  		= 	0;
							$data['hotel_id'] 		= 	0;
						}
						if($data['type'] != "Cancel")
						{
							$data['created_time'] 		=	(string)$bookattr['createDateTime'];
							$data['source'] 			= 	(string)$bookattr['source'];
							$data['status'] 			= 	(string)$bookattr['status'];

							$Hotel						=	$book->Hotel->attributes();
							$data['hotelid'] 			= 	$Hotel['id'];

							$hotel_id = $Hotel['id'];
							$confirm_data['hotelid_'.$hotel_id] = array(
																		'booking_id'=>$data['booking_id'],
																		'type'=>$data['type'],
																		'confirmation_number'=>$result
																	 );

							$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
							if(count($user_details)!=0)
							{
								$data['user_id']  		= 	$user_details['user_id'];
								$data['hotel_id'] 		= 	$user_details['hotel_id'];
							}
							else
							{
								$data['user_id']  		= 	0;
								$data['hotel_id'] 		= 	0;
							}
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
								$data['expireDate'] = "";
								if(count($cardDetails) != 0){
									$data['cardCode'] = (string)$cardDetails['cardCode'];
									$data['cardNumber'] = (string)$cardDetails['cardNumber'];
									$data['expireDate'] = (string)$cardDetails['expireDate'];
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
							$data['SpecialRequest'] = (string)$book->SpecialRequest;
							$available = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

							if(count($available)==0)
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								//insert_data('import_reservation_EXPEDIA',$data);
							}
							else
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								//update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
							}

							if($data['type'] == "Modify")
							{
								$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

								$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['created_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

	        					//$res = $this->db->insert('new_history',$history);
							}

						}
						else
						{
							//update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));

							$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

							$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Canelled",'extra_id'=>2,'amount'=>'','extra_date'=>$data['created_time'],'history_date'=>date('Y-m-d H:i:s'));

	        				//$res = $this->db->insert('new_history',$history);
						}
						//$this->reservation_model->send_confirmation_email(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);
						//$this->reservation_log(1,$data['booking_id'],$data['user_id'],$data['hotel_id']);

						$importBookingDetails	=	get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

						if(count($importBookingDetails)!=0)
						{
							$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival']));
							$departure = date('Y-m-d',strtotime($importBookingDetails['departure']));
							$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rate_type_id'=>$importBookingDetails['ratePlanID'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']))->row_array();
							if(count($mappingDetails)!=0){
								if($mappingDetails['rateAcquisitionType'] != "SellRate")
								{
									if($mappingDetails['rateAcquisitionType'] == "Linked")
									{
										$roomtypeid = get_data("import_mapping",array('rate_type_id'=>$mappingDetails['rateplan_id'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'roomtype_id')->row()->roomtype_id;
	                                    $rateplanid = $mappingDetails['rateplan_id'];
									}else if($mappingDetails['rateAcquisitionType']== "Derived")
									{
										$roomtypeid = $importBookingDetails['roomTypeID'];
	                                    $type = get_data("import_mapping",array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$mappingDetails['rateplan_id'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']))->row();
	                                    $rateplanid = $mappingDetails['rateplan_id'];
	                                    if($type->rateAcquisitionType == "Linked")
	                                    {
	                                        $rateplanid = $type->rateplan_id;
	                                        $roomtypeid = get_data("import_mapping",array('rate_type_id'=>$type->rateplan_id,'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'roomtype_id')->row()->roomtype_id;

	                                    }
									}
									$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'map_id')->row_array();
								}
							}
							if(count($mappingDetails)==0)
							{
								$mappingDetails	=	get_data('import_mapping',array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rateplan_id'=>$importBookingDetails['ratePlanID'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']))->row_array();
								if($mappingDetails['rateAcquisitionType'] != "SellRate")
								{
									if($mappingDetails['rateAcquisitionType'] == "Linked")
									{
										$roomtypeid = get_data("import_mapping",array('rate_type_id'=>$mappingDetails['rateplan_id'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'roomtype_id')->row()->roomtype_id;
	                                    $rateplanid = $mappingDetails['rateplan_id'];
									}else if($mappingDetails['rateAcquisitionType']== "Derived")
									{
										$roomtypeid = $importBookingDetails['roomTypeID'];
	                                    $type = get_data("import_mapping",array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$mappingDetails['rateplan_id'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']))->row();
	                                    $rateplanid = $mappingDetails['rateplan_id'];
	                                    if($type->rateAcquisitionType == "Linked")
	                                    {
	                                        $rateplanid = $type->rateplan_id;
	                                        $roomtypeid = get_data("import_mapping",array('rate_type_id'=>$type->rateplan_id,'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'roomtype_id')->row()->roomtype_id;

	                                    }
									}
									$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'map_id')->row_array();
								}
							}
							echo '<pre>';
							print_r($mappingDetails);
							if(count($mappingDetails)!=0)
							{
								$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>1))->row_array();

								print_r($roomMappingDetails);
								if(count($roomMappingDetails)!=0)
								{
									require_once(APPPATH.'controllers/mapping.php');
									$callAvailabilities = new Mapping();
									$callAvailabilities->importAvailabilities_Cron_Test($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
								}
							}
						}
					}
				}

				/* if(@$confirm_data)
				{

					foreach($confirm_data as $key=>$c_data)
					{
						$confirm = date(DATE_ATOM);
						$confitm_time = explode("+", $confirm);
						$hotel_channel_id = explode('_', $key);
						$hotel_id = '<Hotel id="'.$hotel_channel_id[1].'"/>';
						$confirm_xml = "";
						foreach($c_data as $val)
						{
							$confirm_xml .= '<BookingConfirmNumber bookingID="'.$c_data['booking_id'].'" bookingType="'.$c_data['type'].'" confirmNumber="'.$c_data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>';
						}
						$xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09">
										<Authentication username="'.$user_name.'" password="'.$user_password.'"/>'.$hotel_id.'
										<BookingConfirmNumbers>'.$confirm_xml.'
										</BookingConfirmNumbers>
										</BookingConfirmRQ>
										';
						$x_c_rq_data['channel_id'] 	= '1';
						$x_c_rq_data['user_id'] 	= '0';
						$x_c_rq_data['hotel_id'] 	= '0';
						$x_c_rq_data['message'] 	= $xml_confirm;
						$x_c_rq_data['type'] = 'REQ_EXP';
						$x_c_rq_data['section'] = 'RESER_CONFIRM_EXP';
						insert_data(ALL_XML,$x_c_rq_data);
						$URL = "https://ws.expediaquickconnect.com/connect/bc";

						$ch = curl_init($URL);
						//curl_setopt($ch, CURLOPT_MUTE, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output		= curl_exec($ch);
						$x_c_rs_data['channel_id'] = '1';
						$x_c_rs_data['user_id'] = '0';
						$x_c_rs_data['hotel_id'] = '0';
						$x_c_rs_data['message'] = $output;
						$x_c_rs_data['type'] = 'RES_EXP';
						$x_c_rs_data['section'] = 'RESER_CONFIRM_EXP';
						insert_data(ALL_XML,$x_c_rs_data);
						$data_api 	= simplexml_load_string($output);
						$error =@$data_api->Error;
						if($error != "")
						{
							$meg['result'] = '0';
							$meg['content']= $error.' from '.$cha_name.'. Try again!';
							echo json_encode($meg);
						}
					}
				} */
			}
			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from Expedia!!!';
			echo json_encode($meg);
		}
	}

	function reservation_log($channel_id,$booking_id,$user_id,$hotel_id)
	{
		if($user_id == ""){
			$user_id = current_user_type();
		}
		if($hotel_id == ""){
			$hotel_id = hotel_id();
		}
		$rese_id= $booking_id;

		$user_details = get_data(TBL_USERS,array('user_id'=>$user_id))->row_array();
		$username = ucfirst($user_details['fname']).' '.ucfirst($user_details['lname']);

		$curr_cha_id = $channel_id;

		if($curr_cha_id==11)
		{
			$print_details = get_data(REC_RESERV,array('IDRSV'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$ID = $print_details['IDRSV'];
				$ChannelName = "Reconline";
				$checkin = date('Y/m/d',strtotime($print_details['CHECKIN']));
				$checkout = date('Y/m/d',strtotime($print_details['CHECKOUT']));
				$price = $print_details['CURRENCY'].$print_details['REVENUE'];
				$name = $print_details['FIRSTNAME'];
				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$print_details['ROOMCODE'],'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->property_name;

				if($print_details['STATUS']==11)
				{
					$status =  'New booking';
				}
				else if($print_details['STATUS']==12)
				{
					$status =  'Modification';
				}
				else if($print_details['STATUS']==13)
				{
					$status =  'Cancellation';
				}
			}
			$channel_data = get_data("user_connect_channel",array("user_id" => $user_id,'hotel_id'=>$hotel_id,'channel_id' => 11))->row();
		}elseif($curr_cha_id==1){
			$print_details = get_data('import_reservation_EXPEDIA',array('booking_id'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$ID = $print_details['booking_id'];
				$ChannelName = "Expedia";
				$checkin = $print_details['arrival'];
				$checkout = $print_details['departure'];
				$price = $print_details['currency'].$print_details['amountAfterTaxes'];
				$name = $print_details['givenName'].' '.$print_details['surname'];
				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$print_details['roomTypeID'],'rate_type_id'=>$print_details['ratePlanID'],'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

                if(!$roomName){
                  $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$print_details['roomTypeID'],'rateplan_id'=>$print_details['ratePlanID'],'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;
                }

				if($print_details['type']=="Book")
				{
					$status =  'New booking';
				}
				else if($print_details['type']=="Modify")
				{
					$status =  'Modification';
				}
				else if($print_details['type']=="Cancel")
				{
					$status =  'Cancellation';
				}
			}

		}elseif($curr_cha_id==8){
			$print_details = get_data('import_reservation_GTA',array('booking_id'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$ID = $print_details['booking_id'];
				$ChannelName = "GTA";
				$checkin = $print_details['arrdate'];
				$checkout = $print_details['depdate'];
				$price = $print_details['currencycode'].$print_details['totalcost'];
				$name = $print_details['leadname'];

				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$print_details['room_id'],'rateplan_id'=>$print_details['rateplanid'],'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->GTA_id))->row()->property_id))->row()->property_name;

				if($print_details['status']== "Confirmed")
				{
					$status = 'New booking';
				}
				else if($print_details['status']== "Canceled")
				{
					$status = 'Cancellation';
				}
				else
				{
					$status = 'Modification';
				}
			}

		}elseif($curr_cha_id==5){

			$reservation_details = get_data('import_reservation_HOTELBEDS',array('echoToken'=>$rese_id))->row_array();
			$totamount = 0;
			$room_id = "";
			if(strpos($reservation_details['Room_code'], ',') !== FALSE){
				$h_roomname = explode(',', $reservation_details['Room_code']);
				$h_adult = explode(',', $reservation_details['AdultCount']);
				$h_child = explode(',', $reservation_details['ChildCount']);
				$h_baby = explode(',', $reservation_details['BabyCount']);
				$h_char = explode(',', $reservation_details['CharacteristicCode']);
				$h_status = explode(',', $reservation_details['RoomStatus']);
				$h_name = explode('~', $reservation_details['Customer_Name']);
				$h_cont = explode(',', $reservation_details['Contract_Name']);
				$h_arrival = explode(',', $reservation_details['DateFrom']);
				$h_depar = explode(',', $reservation_details['DateTo']);
				$h_amount = explode(',', $reservation_details['Amount']);
				$h_currency = explode(',', $reservation_details['Currency']);

				for($i=0; $i<count($h_roomname); $i++){
					$htb_id = get_data("import_mapping_HOTELBEDS_ROOMS",array('contract_name'=>$h_cont[$i],'contract_code'=>$reservation_details['IncomingOffice'],'characterstics' => $h_char[$i], 'roomname' => $h_roomname[$i],'user_id' => $user_id,'hotel_id' => $hotel_id));
					if($htb_id->num_rows != 0){
						$htb_id = $htb_id->row()->map_id;
						$htbid = get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>$htb_id));
						if($htbid->num_rows != 0){
							$room_id .= $htbid->row()->property_id.",";
						}else{
							$room_id .= "0".",";
						}
					}else{
						$room_id .= "0".",";
					}
					$totamount = $totamount + $h_amount[$i];
				}

				$checkin = $h_arrival[0];
				$checkout = $h_depar[0];
				$currency = $h_currency[0];
				$name = str_replace('~', ',', $reservation_details['Customer_Name']);
			}else{
				$htb_id = get_data("import_mapping_HOTELBEDS_ROOMS",array('contract_name'=>$reservation_details['Contract_Name'],'contract_code'=>$reservation_details['IncomingOffice'],'characterstics' => $reservation_details['CharacteristicCode'], 'roomname' => $reservation_details['Room_code'],'user_id' => $user_id,'hotel_id' => $hotel_id));
				if($htb_id->num_rows != 0){
					$htb_id = $htb_id->row()->map_id;
					$htbid = get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>$htb_id));
					if($htbid->num_rows != 0){
						$room_id .= $htbid->row()->property_id;
					}else{
						$room_id .= "0";
					}
				}else{
					$room_id .= "0";
				}
				$totamount = $reservation_details['Amount'];
				$checkin = $reservation_details['DateFrom'];
				$checkout = $reservation_details['DateTo'];
				$currency = $reservation_details['Currency'];
				$name = $reservation_details['Customer_Name'];
			}
			if(count($reservation_details)!=0)
			{
				$ID = $reservation_details['echoToken'];
				$ChannelName = "Hotelbeds";
				$checkin = $checkin;
				$checkout = $checkout;
				$price = $currency.$totamount;
				$name = $name;

				$htb_name = "";
                if(strpos($room_id, ',') !== FALSE){
                  $ids = explode(',', $room_id);
                  for($i=0; $i<count($ids); $i++){
                    $name = get_data(TBL_PROPERTY,array('property_id'=>$ids[$i]));
                    if($name->num_rows != 0){
                      $htb_name .= $name->row()->property_name." ";
                    }
                  }
                  if($htb_name != ""){
                    $roomName = $htb_name;
                  }
                }else{
                  $name = get_data(TBL_PROPERTY,array('property_id'=>$room_id));
                  if($name->num_rows != 0){
                    $roomName = $name->row()->property_name;
                  }
                }

				if($reservation_details['Status']== "BOOKING")
				{
					$status = 'New booking';
				}
				else if($reservation_details['Status']== "MODIFIED")
				{
					$status = 'Modification';
				}
				else if($reservation_details['Status']== "CANCELED]")
				{
					$status = 'Cancellation';
				}
			}
		}
		elseif($curr_cha_id==2)
		{
			$print_details = get_data('import_reservation_BOOKING_ROOMS',array('roomreservation_id'=>$rese_id))->row_array();
			$booking_details = get_data('import_reservation_BOOKING',array('id' => $print_details['reservation_id']))->row_array();
			$rate_type = get_data('import_mapping_BOOKING',array('B_room_id'=>$print_details['id'],'B_rate_id'=>$print_details['rate_id']))->row()->rate_name;
			if(count($print_details)!=0)
			{
				$ID = $print_details['reservation_id'].'-'.$print_details['roomreservation_id'];
				$ChannelName = "Booking.com";
				$checkin = $print_details['arrival_date'];
				$checkout = $print_details['departure_date'];
				$price = $print_details['currencycode'].$print_details['totalprice'];
				$name = $print_details['guest_name'];

				$map = get_data('import_mapping_BOOKING',array('B_room_id'=>$print_details['id'],'B_rate_id'=>$print_details['rate_id'],'owner_id'=>$user_id,'hotel_id' => $hotel_id));
                if($map->num_rows != 0){
                  $map_id = $map->row()->import_mapping_id;
                  $prop_id = get_data(MAP,array('channel_id'=>$curr_cha_id,'import_mapping_id'=>$map_id));
                  if($prop_id->num_rows != 0){
                    $prop_id = $prop_id->row()->property_id;
                    $roomName = get_data(TBL_PROPERTY,array('property_id'=>$prop_id))->row()->property_name;
                  }

                }

				if($booking_details['status']== "new")
				{
					$status = 'New booking';
				}
				else if($booking_details['status']== "modified")
				{
					$status = 'Modification';
				}
				else if($booking_details['status']== "cancelled")
				{
					$status = 'Cancellation';
				}
			}
		}
		if(!isset($roomName)){
			$roomName = "No Room Set";
		}
		$message = "Location:Channel Reservation,Reservation Id:".$ID.", Name:".$name.", Check In Date:".$checkin.", Check Out Date:".$checkout.", Room:".$roomName.", Price:".$price.", Booking Status:".$status.", Channel:".$ChannelName." IP:".$this->input->ip_address()." User:".$username;

        $this->inventory_model->write_log($message,$user_id);
	}

	function getResrvationCronFromExpdiaTest($channel_id='')
	{
		$user_name 		= 	'EQC_Hoteratus';
		$user_password 	= 	'aj67dy58';
		$xml_data 		= 	'<?xml version="1.0" encoding="UTF-8"?>
							<BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							</BookingRetrievalRQ>
							';
		$x_r_rq_data['channel_id'] = '1';
		$x_r_rq_data['user_id'] = '0';
		$x_r_rq_data['hotel_id'] = '0';
		$x_r_rq_data['message'] = $xml_data;
		$x_r_rq_data['type'] = 'REQ';
		$x_r_rq_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rq_data);
		//$this->reservationxmlmail($x_r_rq_data);
		$URL = "https://services.expediapartnercentral.com/eqc/br";
		$ch = curl_init($URL);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output			= curl_exec($ch);
		$x_r_rs_data['channel_id'] = '1';
		$x_r_rs_data['user_id'] = '0';
		$x_r_rs_data['hotel_id'] = '0';
		$x_r_rs_data['message'] = $output;
		$x_r_rs_data['type'] = 'RES';
		$x_r_rs_data['section'] = 'RESER';
		//insert_data(ALL_XML,$x_r_rs_data);
		//$this->reservationxmlmail($x_r_rs_data);
		$data_api 		= 	simplexml_load_string($output);
		//print_r($data_api);
		$error 			=	@$data_api->Error;
		$BookingListing = 	@$data_api->Bookings->Booking;
		if($error !="" || count($BookingListing) != 0)
		{
			$this->reservationxmlmail($x_r_rs_data);
		}
		if($error != "")
		{
			$meg['result'] = '0';
			$meg['content']= $error.' from Expedia Try again!';
			echo json_encode($meg);
		}
		else if($error == "")
		{
			$BookingListing = @$data_api->Bookings;
			if($BookingListing){
				foreach($BookingListing as $Booking)
				{
					foreach($Booking as $Booking_key=>$book)
					{
						$bookattr						=	$book->attributes();
						$data['booking_id'] 			= 	$bookattr['id'];
						$data['type'] 					=	(string)$bookattr['type'];
						$result 						= 	rand(0,999999999);
						$data['confirmation_number'] 	= 	$result;
						$Hotel						=	$book->Hotel->attributes();
						$data['hotelid'] 			= 	$Hotel['id'];

						$hotel_id = $Hotel['id'];
						$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  		= 	$user_details['user_id'];
							$data['hotel_id'] 		= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  		= 	0;
							$data['hotel_id'] 		= 	0;
						}
						$data['created_time'] 		=	(string)$bookattr['createDateTime'];
						if($data['type'] != "Cancel")
						{
							/*$data['created_time'] 		=	(string)$bookattr['createDateTime'];*/
							$data['source'] 			= 	(string)$bookattr['source'];
							$data['status'] 			= 	(string)$bookattr['status'];

							$Hotel						=	$book->Hotel->attributes();
							$data['hotelid'] 			= 	$Hotel['id'];

							$hotel_id = $Hotel['id'];
							$confirm_data['hotelid_'.$hotel_id] = array(
																		'booking_id'=>$data['booking_id'],
																		'type'=>$data['type'],
																		'confirmation_number'=>$result
																	 );

							$user_details	= get_data(CONNECT,array('hotel_channel_id'=>$data['hotelid'],'channel_id'=>'1'))->row_array();
							if(count($user_details)!=0)
							{
								$data['user_id']  		= 	$user_details['user_id'];
								$data['hotel_id'] 		= 	$user_details['hotel_id'];
							}
							else
							{
								$data['user_id']  		= 	0;
								$data['hotel_id'] 		= 	0;
							}
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
								$data['expireDate'] = "";
								if(count($cardDetails) != 0){
									$data['cardCode'] = (string)$cardDetails['cardCode'];
									$data['cardNumber'] = (string)$cardDetails['cardNumber'];
									$data['expireDate'] = (string)$cardDetails['expireDate'];
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
							$data['SpecialRequest'] = (string)$book->SpecialRequest;
							$available = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

							if(count($available)==0)
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								insert_data('import_reservation_EXPEDIA',$data);
							}
							else
							{
								$array_keys = array_keys($data);
								fetchColumn('import_reservation_EXPEDIA',$array_keys);
								update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
							}

							if($data['type'] == "Modify")
							{
								$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

								$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Modified",'amount'=>'','extra_date'=>$data['created_time'],'extra_id'=>2,'history_date'=>date('Y-m-d H:i:s'));

	        					$res = $this->db->insert('new_history',$history);
							}

						}
						else
						{
							$confirm_data['hotelid_'.$hotel_id] = array(
																		'booking_id'=>$data['booking_id'],
																		'type'=>$data['type'],
																		'confirmation_number'=>$result
																	 );
							update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));

							$resid = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row()->import_reserv_id;

							$history = array('channel_id'=>1,'reservation_id'=>$resid,'description'=>"Reservation Canelled",'extra_id'=>2,'amount'=>'','extra_date'=>$data['created_time'],'history_date'=>date('Y-m-d H:i:s'));

	        				$res = $this->db->insert('new_history',$history);
						}
						$this->reservation_model->send_confirmation_email(1,$data['booking_id'],$data['user_id'],$data['hotel_id']);
						$this->reservation_log(1,$data['booking_id'],$data['user_id'],$data['hotel_id']);

						$importBookingDetails	=	get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

						if(count($importBookingDetails)!=0)
						{
							$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival']));
							$departure = date('Y-m-d',strtotime($importBookingDetails['departure']));
							$roomtypeid = $importBookingDetails['roomTypeID'];
							$rateplanid = $importBookingDetails['ratePlanID'];
							$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$data['user_id'],$data['hotel_id']);

							if(count($roomdetails) != 0)
							{
								$roomtypeid = $roomdetails['roomtypeId'];
								$rateplanid = $roomdetails['rateplanid'];
							}

							$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()),'map_id')->row_array();

							if(count($mappingDetails)!=0)
							{
								$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>1))->row_array();
								if(count($roomMappingDetails)!=0)
								{
									require_once(APPPATH.'controllers/mapping.php');
									$callAvailabilities = new Mapping();
									$callAvailabilities->importAvailabilities_Cron($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$mappingDetails['map_id'],'mapping');
								}
							}
						}
					}
				}

				if(@$confirm_data)
				{

					foreach($confirm_data as $key=>$c_data)
					{
						$confirm = date(DATE_ATOM);
						$confitm_time = explode("+", $confirm);
						$hotel_channel_id = explode('_', $key);
						$hotel_id = '<Hotel id="'.$hotel_channel_id[1].'"/>';
						$confirm_xml = "";
						foreach($c_data as $val)
						{
							$confirm_xml .= '<BookingConfirmNumber bookingID="'.$c_data['booking_id'].'" bookingType="'.$c_data['type'].'" confirmNumber="'.$c_data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>';
						}
						$xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09">
										<Authentication username="'.$user_name.'" password="'.$user_password.'"/>'.$hotel_id.'
										<BookingConfirmNumbers>'.$confirm_xml.'
										</BookingConfirmNumbers>
										</BookingConfirmRQ>
										';
						$x_c_rq_data['channel_id'] 	= '1';
						$x_c_rq_data['user_id'] 	= '0';
						$x_c_rq_data['hotel_id'] 	= '0';
						$x_c_rq_data['message'] 	= $xml_confirm;
						$x_c_rq_data['type'] = 'REQ_EXP';
						$x_c_rq_data['section'] = 'RESER_CONFIRM_EXP';
						//insert_data(ALL_XML,$x_c_rq_data);
						$URL = "https://services.expediapartnercentral.com/eqc/bc";

						$ch = curl_init($URL);
						//curl_setopt($ch, CURLOPT_MUTE, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
						curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$output		= curl_exec($ch);
						$x_c_rs_data['channel_id'] = '1';
						$x_c_rs_data['user_id'] = '0';
						$x_c_rs_data['hotel_id'] = '0';
						$x_c_rs_data['message'] = $output;
						$x_c_rs_data['type'] = 'RES_EXP';
						$x_c_rs_data['section'] = 'RESER_CONFIRM_EXP';
						//insert_data(ALL_XML,$x_c_rs_data);
						$this->reservationxmlmail($x_c_rs_data);
						$data_api 	= simplexml_load_string($output);
						$error =@$data_api->Error;
						if($error != "")
						{
							$meg['result'] = '0';
							$meg['content']= $error.' from '.$cha_name.'. Try again!';
							echo json_encode($meg);
						}
					}
				}
			}
			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from Expedia!!!';
			echo json_encode($meg);
		}
	}

	function getResrvationCronFromExpdiaTest_old()
	{
		$channel_id		=	'1';
		$user_name 		= 	'EQC17103231hotel';
		$user_password 	= 	'Octorate123!';
		$xml_data 		= 	'<?xml version="1.0" encoding="UTF-8"?>
							<BookingRetrievalRQ xmlns="http://www.expediaconnect.com/EQC/BR/2014/01">
							<Authentication username="'.$user_name.'" password="'.$user_password.'"/>
							</BookingRetrievalRQ>
						  ';
		/* mail("xml@hoteratus.com"," Reservation Form Expedia Test Request",$xml_data);	 */
		$URL = "https://ws.expediaquickconnect.com/connect/br";
		$ch = curl_init($URL);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output			= curl_exec($ch);
		$x_r_rs_data['channel_id'] 	=	'1';
		$x_r_rs_data['user_id'] 	=	'0';
		$x_r_rs_data['hotel_id'] 	=	'0';
		$x_r_rs_data['message']		=	$output;
		$x_r_rs_data['type'] 		=	'RES';
		$x_r_rs_data['section'] 	=	'RESER';
		/* mail("xml@hoteratus.com"," Reservation Form Expedia Test Response ",$output); */
		$data_api 		= simplexml_load_string($output);
		//print_r($data_api);
		$error 			=	@$data_api->Error;
		$BookingListing = 	@$data_api->Bookings->Booking;
		if($error != "")
		{

			$meg['result'] = '0';
			$meg['content']= $error.' from Expedia Try again!';
			echo json_encode($meg);
		}
		else if($error == "")
		{
			$this->reservationxmlmail($x_r_rs_data);
			$BookingListing = $data_api->Bookings;
			foreach($BookingListing as $Booking)
			{
				foreach($Booking as $Booking_key=>$book)
				{
					$bookattr						=	$book->attributes();
					$data['booking_id'] 			= 	$bookattr['id'];
					$data['type'] 					=	(string)$bookattr['type'];
					$result 						= 	rand(0,999999999);
					$data['confirmation_number'] 	= 	$result;
					if($data['type'] != "Cancel")
					{
						$data['created_time'] 		=	(string)$bookattr['createDateTime'];
						$data['source'] 			= 	(string)$bookattr['source'];
						$data['status'] 			= 	(string)$bookattr['status'];

						$Hotel						=	$book->Hotel->attributes();
						$data['hotelid'] 			= 	$Hotel['id'];
						$hotel_id					=	$Hotel['id'];

						@$confirm_data[$hotel_id][] = array(
																'booking_id'=>$data['booking_id'],
																'type'=>$data['type'],
																'confirmation_number'=>$result
															);

						$user_details	= get_data(CONNECT,array('channel_id'=>'1','hotel_channel_id'=>$data['hotelid']))->row_array();
						if(count($user_details)!=0)
						{
							$data['user_id']  		= 	$user_details['user_id'];
							$data['hotel_id'] 		= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  		= 	0;
							$data['hotel_id'] 		= 	0;
						}
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
							$data['expireDate'] = "";
							$data['SeriesCode'] = "";
							if(count($cardDetails) != 0){
								$data['cardCode'] = (string)$cardDetails['cardCode'];
								$data['cardNumber'] = (string)$cardDetails['cardNumber'];
								$data['expireDate'] = (string)$cardDetails['expireDate'];
								$data['SeriesCode'] = (string)$cardDetails['SeriesCode'];
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
						$data['SpecialRequest'] = (string)$book->SpecialRequest;
						$available = get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

						if(count($available)==0)
						{
							$array_keys = array_keys($data);
							fetchColumn('import_reservation_EXPEDIA',$array_keys);
							insert_data('import_reservation_EXPEDIA',$data);
						}
						else
						{
							$array_keys = array_keys($data);
							fetchColumn('import_reservation_EXPEDIA',$array_keys);
							update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
						}
					}
					else
					{
						$Hotel						=	$book->Hotel->attributes();
						$data['hotelid'] 			= 	$Hotel['id'];
						$hotel_id					=	$Hotel['id'];

						@$confirm_data[$hotel_id][] = array(
																'booking_id'=>$data['booking_id'],
																'type'=>$data['type'],
																'confirmation_number'=>$result
															);

						$user_details	= get_data(CONNECT,array('channel_id'=>'1','hotel_channel_id'=>$data['hotelid']))->row_array();

						if(count($user_details)!=0)
						{
							$data['user_id']  		= 	$user_details['user_id'];
							$data['hotel_id'] 		= 	$user_details['hotel_id'];
						}
						else
						{
							$data['user_id']  		= 	0;
							$data['hotel_id'] 		= 	0;
						}

						update_data('import_reservation_EXPEDIA',$data,array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']));
					}
					//$this->reservation_model->send_confirmation_email(insep_decode($channel_id),$data['booking_id'],$data['user_id'],$data['hotel_id']);

					$this->reservation_model->send_confirmation_email($channel_id,$data['booking_id'],$data['user_id'],$data['hotel_id']);

					$importBookingDetails	=	get_data('import_reservation_EXPEDIA',array('user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id'],'booking_id'=>$data['booking_id'],'hotelid'=>$data['hotelid']))->row_array();

					if(count($importBookingDetails)!=0)
					{
						$arrival = date('Y-m-d',strtotime($importBookingDetails['arrival']));
						$departure = date('Y-m-d',strtotime($importBookingDetails['departure']));
						$mappingDetails		=	get_data('import_mapping',array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rateplan_id'=>$importBookingDetails['ratePlanID'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'map_id')->row_array();
						if(count($mappingDetails)==0)
						{
							$mappingDetails	=	get_data('import_mapping',array('roomtype_id'=>$importBookingDetails['roomTypeID'],'rate_type_id'=>$importBookingDetails['ratePlanID'],'user_id'=>$data['user_id'],'hotel_id'=>$data['hotel_id']),'map_id')->row_array();
						}
						if(count($mappingDetails)!=0)
						{
							$roomMappingDetails	=	get_data(MAP,array('import_mapping_id'=>$mappingDetails['map_id'],'channel_id'=>1))->row_array();
							if(count($roomMappingDetails)!=0)
							{
								require_once(APPPATH.'controllers/mapping.php');
								$callAvailabilities = new Mapping();
								$callAvailabilities->importAvailabilities_Cron($data['user_id'],$data['hotel_id'],insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,'mapping');
							}
						}
					}
				}
			}

			if(@$confirm_data)
			{
				foreach($confirm_data as $key=>$c_data)
				{
					$hotel_id = '<Hotel id="'.$key.'"/>';
					foreach($c_data as $val)
					{
						$confirm_xml .= '<BookingConfirmNumber bookingID="'.$c_data['booking_id'].'" bookingType="'.$c_data['type'].'" confirmNumber="'.$c_data['confirmation_number'].'" confirmTime="'.$confitm_time[0].'"/>';
					}
					echo $xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09">
									<Authentication username="'.$user_name.'" password="'.$user_password.'"/>'.$hotel_id.'
									<BookingConfirmNumbers>'.$confirm_xml.'
									</BookingConfirmNumbers>
									</BookingConfirmRQ>
									';
					$URL = "https://ws.expediaquickconnect.com/connect/bc";

					$ch = curl_init($URL);
					//curl_setopt($ch, CURLOPT_MUTE, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
					curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output		= curl_exec($ch);
					$data_api 	= simplexml_load_string($output);
					$error =@$data_api->Error;
					if($error != "")
					{
						$meg['result'] = '0';
						$meg['content']= $error.' from '.$cha_name.'. Try again!';
						echo json_encode($meg);
					}
				}
			}
			$meg['result'] = '1';
			$meg['content']='Successfully import reservation from Expedia!!!';
			echo json_encode($meg);
		}
	}

	/*Subbaiah Get Reservation Functionality End*/

	function fetchColumn()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$result = $this->db->query("SHOW COLUMNS from ".REC_RESERV);
		if($result)
		{
			$query = $result->result();
			foreach($query as $value)
			{
				$column_name[]=$value->Field;
			}
			if(in_array('import_reserv_id',$column_name))
			{
				echo 'sdfsdf';
			}
			else
			{
				echo 'mmm';
			}
		}
	}

	function test_mail()
	{
		// the message
		$msg = "First line of text\nSecond line of text";

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);

		// send email
		//mail("renuka@osiztechnologies.com","My subject",$msg);

		$this->mailsettings();

		$this->email->from('hotelavailabil@server1.hoteratus.com','email_content_title');

		$this->email->to('renuka@osiztechnologies.com');

		$this->email->subject('My subject');

		$this->email->message($msg);

		$this->email->send();
	}

	public function confirmbook()
	{
		/* echo current_user_type().' = ='.hotel_id(); die; */
	$ch_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>1))->row();
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
	$confirm = date(DATE_ATOM);
	$confitm_time = explode("+", $confirm);
	$xml_confirm = '<BookingConfirmRQ xmlns="http://www.expediaconnect.com/EQC/BC/2007/09"><Authentication username="'.$ch_details->user_name.'" password="'.$ch_details->user_password.'"/><Hotel id="'.$ch_details->hotel_channel_id.'"/><BookingConfirmNumbers>
		<BookingConfirmNumber bookingID="820774852" bookingType="Book" confirmNumber="'.rand(0,999999).'" confirmTime="'.$confitm_time[0].'"/>
		</BookingConfirmNumbers>
		</BookingConfirmRQ>';
	/* print_r( $xml_confirm ); die;	 539468 */

	mail("test2osiz@gmail.com"," BookingConfirmNumber ",$xml_confirm);


	$URL = trim($book['bookingconfirm']);
	/* echo $URL; die; */
	$ch = curl_init($URL);
	//curl_setopt($ch, CURLOPT_MUTE, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_confirm");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output		= curl_exec($ch);
	$data_api 	= simplexml_load_string($output);
	print_r($data_api);
	$error =@$data_api->Error;
	if($error != "")
	{
		$meg['result'] = '0';
		$meg['content']= $error.' from '.$cha_name.'. Try again!';
		echo json_encode($meg);
	}
	}

	public function update_subscribe_plan_cron(){

		$plans = get_data("user_membership_plan_details", array('plan_status' => 1))->result();
		foreach($plans as $plan){
			$current_date = date('Y-m-d');
			$plan_to = $plan->plan_to;

			$days = strtotime($plan_to) - strtotime($current_date);
			$days = floor($days/(60*60*24));
			if($days <= 3){
				$wait = get_data("user_membership_plan_details", array('plan_status' => 3,'user_id'=>$plan->user_id,'hotel_id' => $plan->hotel_id));
				if($wait->num_rows != 0){
					if($days == 0){
						$this->reservation_model->update_plan_status($plan->user_buy_id,2);
						$this->reservation_model->update_plan_status($wait->row()->user_buy_id,1);
					}
					$notf = get_data("notifications", array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id))->num_rows;
					if($notf != 0){
						$this->db->where("expire_alert",1);
						$this->db->where("user_id",$plan->user_id);
						$this->db->where("hotel_id", $plan->hotel_id);
						$this->db->delete("notifications");
					}
				}else{
					if($days > 0){

						$data['user_id'] = $plan->user_id;
						$data['hotel_id'] = $plan->hotel_id;
						$data['sendto'] = 1;
						$data['type'] = 1;
						$data['content'] = "Make Sure to Renew Your Membership before it expires. Your Current Membership plan expires in ".$days." day(s).To Renew your membership plan click <a href='".base_url()."inventory/rate_management'>HERE</a>";
						$data['created_date'] = date("Y-m-d H:i:s");
						$data['status'] = "unseen";
						$data['expire_alert'] = 1;
						$data['title']  = 'Membership Plan Expires In '.$days.' days';
						$notf = get_data("notifications", array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id))->num_rows;
						if($notf == 0){
							$this->db->insert("notifications",$data);
						}else{
							update_data("notifications",$data,array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id));
						}

					}else{

						$data['user_id'] = $plan->user_id;
						$data['hotel_id'] = $plan->hotel_id;
						$data['sendto'] = 1;
						$data['type'] = 1;
						$data['content'] = "Your Membership Plan Expired. To Renew your membership plan click <a href='".base_url()."inventory/rate_management'>HERE</a>";
						$data['created_date'] = date("Y-m-d H:i:s");
						$data['status'] = "unseen";
						$data['expire_alert'] = 1;
						$data['title']  = 'Membership Plan Expired';
						$notf = get_data("notifications", array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id))->num_rows;
						if($notf == 0){
							$this->db->insert("notifications",$data);
						}else{
							update_data("notifications",$data,array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id));
						}
						$this->reservation_model->update_plan_status($plan->user_buy_id,2);
					}
				}
			}else{
				$notf = get_data("notifications", array('expire_alert'=>1, 'user_id'=>$plan->user_id, 'hotel_id'=>$plan->hotel_id))->num_rows;
				if($notf != 0){
					$this->db->where("expire_alert",1);
					$this->db->where("user_id",$plan->user_id);
					$this->db->where("hotel_id", $plan->hotel_id);
					$this->db->delete("notifications");
				}
			}
		}
	}

	// Send Arrival And Departure Emails //

	function send_arrival_departure_mail_Cron(){
		$hoteldetails = get_data(HOTEL,array('status'=>'1'))->result_array();

		$types = array('arrival','depature');
		foreach($hoteldetails as $hotel){

			$total_arrival_departure = array();
			foreach($types as $type){
				$arrdep = $this->reservation_model->get_todays_arrival_departure($type,$hotel['owner_id'],$hotel['hotel_id']);

				if(count($arrdep) != 0){
					//print_r($arrdep);
					$total_arrival_departure[$type][] = $arrdep;
				}
			}
			$arrivaltable = "";
			$departuretable = "";
			$user_id = $hotel['owner_id'];
			$hotel_id = $hotel['hotel_id'];
			$roomName = "No Room Set";
			$Fullname="";

			if(count($total_arrival_departure) != 0)
			{

				if(isset($total_arrival_departure['arrival']))
				{
					foreach ($total_arrival_departure['arrival'] as $arrivals)
					{
						if(count($arrivals) != 0){
							foreach($arrivals as $arrival)
							{
								if($arrival->channel_id == 0)
								{
									$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$arrival->room_id))->row()->property_name;
									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>$arrival->room_id))->row()->image;
									$channel_name = "hoteratus";
								}
								else if($arrival->channel_id == 1)
								{
									$this->db->where('user_id',$user_id);
		                            $this->db->where('hotel_id',$hotel_id);
		                            $this->db->where('rate_type_id',$arrival->rateplanid);
		                            $this->db->where('roomtype_id',$arrival->roomtypeId);
		                            $details = $this->db->get('import_mapping')->row();
		                            $roomtypeid = $arrival->roomtypeId;
									$rateplanid = $arrival->rateplanid;

									$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
		                            if(count($roomdetails) !=0)
		                            {
		                                $roomtypeid = $roomdetails['roomtypeId'];
		                                $rateplanid = $roomdetails['rateplanid'];
		                            }

									$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;
									$roomImage = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									$Fullname="";
									if(!$roomName)
									{
										$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

										$roomImage = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									}
									$channel_name = "Expedia";
								}
								else if($arrival->channel_id == 11)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$arrival->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$arrival->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->image;

									$channel_name = "Reconline";
								}
								else if($arrival->channel_id == 8)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$arrival->room_id,'rateplan_id'=>$arrival->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$arrival->room_id,'rateplan_id'=>$arrival->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->image;

									$channel_name = "GTA";
								}
								elseif($arrival->channel_id==2)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$arrival->id,'B_rate_id'=>$arrival->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$arrival->id,'B_rate_id'=>$arrival->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->image;

									$channel_name = "Booking.com";

									$reservation_name = get_data(BOOK_RESERV,array('id'=>get_data(BOOK_RESERV,array('room_res_id'=>$arrival->reservation_id))->row()->reservation_id))->row();
									$Fullname	= $reservation_name->first_name.' '.$reservation_name->last_name;

									if($reservation_name->first_name == '' && $reservation_name->last_name == ''){
										$Fullname = $this->db->query("SELECT concat_ws (' ', first_name,last_name)  Fullname  FROM import_reservation_BOOKING_ROOMS as a
									left join import_reservation_BOOKING as b on a.import_reserv_id = b.import_reserv_id
									WHERE `roomreservation_id`=".$arrival->reservation_code)->row()->Fullname;
									}

								}elseif ($arrival->channel_id == 17) {
									$room_id = @get_data(MAP,array('channel_id'=>$bnowmodel['channel_id'],'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$roomtypeid,'RatePlanCode'=>$rateplanid,'user_id' =>$user_id,'hotel_id'=>$hotel_id))->row()->import_mapping_id))->row()->property_id;
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->property_name;
									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->image;

									$channel_name = "BNOW";
								}

								if($arrival->email){
									$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
									$image = base_url().'uploads/logo/'.$admin_detail->site_logo;
									if ($roomImage != '' && $roomImage != 0) {
										$image = base_url().'uploads/room_photos/'.$roomImage;
									}
									$content = array(
										'###HOTELNAME###' => $hotel['property_name'],
										'###GUESTNAME###' => ucfirst((strlen($Fullname)==0?$arrival->guest_name:$Fullname)),
										'###SITENAME###' => $admin_detail->company_name,
										'###TOWN###' => $hotel['town'],
										'###ADDRESS###' => $hotel['address'],
										'###ZIP###' => $hotel['zip_code'],
										'###EMAIL###' => $hotel['email_address'],
										'###MOBILE###' => $hotel['mobile'],
										'###RESERVATIONCODE###' => $arrival->reservation_code,
										'###COMPANYLOGO###' =>  $image,
									);
									$get_email_info     = get_mail_template('32');

									$email_subject1		= $get_email_info['subject'];
									$email_subject1		= strtr($email_subject1,$content);
									$email_content1		= $get_email_info['message'];
									$email_content		= strtr($email_content1,$content);

									$subject			= $email_subject1;

									$this->mailsettings();

									$this->email->from($admin_detail->email_id);

									$this->email->to($arrival->email);

									//$this->email->cc($cc);

									$this->email->subject($subject);

									$this->email->message($email_content);

									$this->email->send();
								}
								$arrivaltable .= "<tr><td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".ucfirst((strlen($Fullname)==0?$arrival->guest_name:$Fullname))."</td>";
								$arrivaltable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".ucfirst($roomName)."</td>";
								$arrivaltable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".$channel_name."</td>";
								$arrivaltable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".date('M d,Y',strtotime(str_replace("/","-",$arrival->start_date)))."</td>";
								$arrivaltable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".date('M d,Y',strtotime(str_replace("/","-",$arrival->end_date)))."</td>";
								$arrivaltable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".$arrival->reservation_code."</td>";
								$arrivaltable .= "<td style='border-top:1px solid rgb(221,221,221);padding:3px'>".$arrival->price."</td></tr>";
							}
						}else{
							$arrivaltable .= "<tr><td colspan = '7' style='text-align:center;border-top:1px solid rgb(221,221,221);padding:3px'>No Arrivals Today!!!..</td></tr>";

						}
					}
				}else{
					$arrivaltable .= "<tr><td colspan = '7' style='text-align:center;border-top:1px solid rgb(221,221,221);padding:3px'>No Arrivals Today!!!..</td></tr>";
				}
				if(isset($total_arrival_departure['depature']))
				{
					foreach ($total_arrival_departure['depature'] as $departures)
					{
						//print_r($departures);
						if(count($departures) != 0){
							foreach($departures as $departure)
							{
								$Fullname="";
								if($departure->channel_id == 0)
								{
									$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$departure->room_id))->row()->property_name;
									$channel_name = "hoteratus";
								}
								else if($departure->channel_id == 1)
								{
									$this->db->where('user_id',$user_id);
		                            $this->db->where('hotel_id',$hotel_id);
		                            $this->db->where('rate_type_id',$departure->rateplanid);
		                            $this->db->where('roomtype_id',$departure->roomtypeId);
		                            $details = $this->db->get('import_mapping')->row();
		                            $roomtypeid = $departure->roomtypeId;
									$rateplanid = $departure->rateplanid;

									$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
		                            if(count($roomdetails) !=0)
		                            {
		                                $roomtypeid = $roomdetails['roomtypeId'];
		                                $rateplanid = $roomdetails['rateplanid'];
		                            }

									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;
									if(!$roomName){
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;
									}

									$channel_name = "Expedia";
								}
								else if($departure->channel_id == 11)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$departure->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->property_name;
									$channel_name = "Reconline";
								}
								else if($departure->channel_id == 8)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$departure->room_id,'rateplan_id'=>$departure->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->property_name;
									$channel_name = "GTA";
								}
								elseif($departure->channel_id==2)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$departure->id,'B_rate_id'=>$departure->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
									$channel_name = "Booking.com";

									$reservation_name = get_data(BOOK_RESERV,array('id'=>get_data(BOOK_RESERV,array('room_res_id'=>$departure->reservation_id))->row()->reservation_id))->row();
									$Fullname = $reservation_name->first_name.' '.$reservation_name->last_name;

									if($reservation_name->first_name == '' && $reservation_name->last_name == ''){
										$Fullname = $this->db->query("SELECT concat_ws (' ', first_name,last_name)  Fullname  FROM import_reservation_BOOKING_ROOMS as a
									left join import_reservation_BOOKING as b on a.import_reserv_id = b.import_reserv_id
									WHERE `roomreservation_id`=".$arrival->reservation_code)->row()->Fullname;
									}


								}
								elseif ($departure->channel_id == 17) {
									$room_id = @get_data(MAP,array('channel_id'=>$bnowmodel['channel_id'],'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$roomtypeid,'RatePlanCode'=>$rateplanid,'user_id' =>$user_id,'hotel_id'=>$hotel_id))->row()->import_mapping_id))->row()->property_id;
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->property_name;
									$channel_name = "BNOW";
								}

								$departuretable .= "<tr><td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".ucfirst((strlen($Fullname)==0?$departure->guest_name:$Fullname))."</td>";
								$departuretable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".ucfirst($roomName)."</td>";
								$departuretable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".$channel_name."</td>";
								$departuretable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".date('M d,Y',strtotime(str_replace("/","-",$departure->start_date)))."</td>";
								$departuretable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".date('M d,Y',strtotime(str_replace("/","-",$departure->end_date)))."</td>";
								$departuretable .= "<td style='border-right:1px solid rgb(221,221,221);border-top:1px solid rgb(221,221,221);padding:3px'>".$departure->reservation_code."</td>";
								$departuretable .= "<td style='border-top:1px solid rgb(221,221,221);padding:3px'>".$departure->price."</td></tr>";
							}
						}else{
							$departuretable .= "<tr><td colspan = '7' style='text-align:center;border-top:1px solid rgb(221,221,221);padding:3px'>No Departures Today!!!..</td></tr>";
						}
					}
				}else{
					$departuretable .= "<tr><td colspan = '7' style='text-align:center;border-top:1px solid rgb(221,221,221);padding:3px'>No Departures Today!!!..</td></tr>";
				}


				$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
				if($arrivaltable != "" || $departuretable != "")
				{
					$content = array(
							'###HOTELNAME###' => $hotel['property_name'],
							'###ARRIVALS###' => $arrivaltable,
							'###DEPARTURES###' => $departuretable,
							'###SITENAME###' => $admin_detail->company_name,
							'###COMPANYLOGO###' =>  base_url().'uploads/logo/'.$admin_detail->site_logo,
						);
					$subusers = get_data(TBL_USERS,array('User_Type'=>2,'owner_id'=>$hotel['owner_id'],'acc_active'=>1))->result_array();
					$cc = "";
					$ccemails = array();
					foreach ($subusers as $user) {
						$previl = get_data(ASSIGN,array('user_id'=>$user['user_id'],'hotel_id'=>$hotel['hotel_id'],'owner_id'=>$hotel['owner_id']))->row_array();
						if($previl){
							$previledges = json_decode($previl['access']);
							foreach ($previledges as $value) {
								foreach($value as $val){
									if($val == 2){
										if(!in_array($user['email_address'], $ccemails)){
											$ccemails[] = $user['email_address'];
											$cc .= $user['email_address'].",";
										}
									}
								}
							}
						}
					}
					$cc = rtrim($cc,',');

					$get_email_info     =   get_mail_template('21');

		            $email_subject1= $get_email_info['subject'];

		            $email_content1= $get_email_info['message'];
		            $email_content=strtr($email_content1,$content);

		            $subject = $email_subject1;

		            $this->mailsettings();

		            $this->email->from($admin_detail->email_id);

		            $this->email->to($hotel['email_address']);

		            $this->email->cc($cc);

		            $this->email->subject($subject);

		            $this->email->message($email_content);

		            $this->email->send();
		        }
		   	}
		}


		echo "All Email Arrival and Departure Send";
	}

	function send_arrival_reminder_mail_Cron(){
		$hoteldetails = get_data(HOTEL,array('status'=>'1'))->result_array();

		$types = array('arrival');
		//print_r($hoteldetails);
		foreach($hoteldetails as $hotel){
			$total_arrival_departure = array();
			foreach($types as $type){
				$arrdep = $this->reservation_model->get_todays_arrival_departure($type,$hotel['owner_id'],$hotel['hotel_id'],'reminder');

				if(count($arrdep) != 0){
					//print_r($arrdep);
					$total_arrival_departure[$type][] = $arrdep;
				}
			}
			$arrivaltable = "";
			$departuretable = "";
			$user_id = $hotel['owner_id'];
			$hotel_id = $hotel['hotel_id'];
			$roomName = "No Room Set";
			$Fullname="";
			//echo $this->db->last_query();
			//print_r($total_arrival_departure);
			//continue;
			if(count($total_arrival_departure) != 0)
			{
				if(isset($total_arrival_departure['arrival']))
				{
					foreach ($total_arrival_departure['arrival'] as $arrivals)
					{
						//print_r($arrivals);
						if(count($arrivals) != 0){
							foreach($arrivals as $arrival)
							{
								$Fullname="";
								if($arrival->channel_id == 0)
								{
									$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$arrival->room_id))->row()->property_name;

									$roomImage= @get_data(TBL_PROPERTY,array('property_id'=>$arrival->room_id))->row()->image;
									$channel_name = "hoteratus";
								}
								else if($arrival->channel_id == 1)
								{
									$this->db->where('user_id',$user_id);
		                            $this->db->where('hotel_id',$hotel_id);
		                            $this->db->where('rate_type_id',$arrival->rateplanid);
		                            $this->db->where('roomtype_id',$arrival->roomtypeId);
		                            $details = $this->db->get('import_mapping')->row();
		                            $roomtypeid = $arrival->roomtypeId;
									$rateplanid = $arrival->rateplanid;

									$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
		                            if(count($roomdetails) !=0)
		                            {
		                                $roomtypeid = $roomdetails['roomtypeId'];
		                                $rateplanid = $roomdetails['rateplanid'];
		                            }

									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									if(!$roomName){
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

										$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									}

									$channel_name = "Expedia";
								}
								else if($arrival->channel_id == 11)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$arrival->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$arrival->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->image;
									$channel_name = "Reconline";
								}
								else if($arrival->channel_id == 8)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$arrival->room_id,'rateplan_id'=>$arrival->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$arrival->room_id,'rateplan_id'=>$arrival->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->image;
									$channel_name = "GTA";
								}
								elseif($arrival->channel_id==2)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$arrival->id,'B_rate_id'=>$arrival->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$arrival->id,'B_rate_id'=>$arrival->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$arrival->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$arrival->id,'B_rate_id'=>$arrival->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->image;

									$channel_name = "Booking.com";

									$Fullname = $this->db->query("SELECT concat_ws (' ', first_name,last_name)  Fullname  FROM import_reservation_BOOKING_ROOMS as a
									left join import_reservation_BOOKING as b on a.import_reserv_id = b.import_reserv_id
									WHERE `roomreservation_id`=".$arrival->reservation_code)->row()->Fullname;


								}
								elseif ($arrival->channel_id == 17) {
									$room_id = @get_data(MAP,array('channel_id'=>$bnowmodel['channel_id'],'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$roomtypeid,'RatePlanCode'=>$rateplanid,'user_id' =>$user_id,'hotel_id'=>$hotel_id))->row()->import_mapping_id))->row()->property_id;

									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->property_name;
									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->image;
									$channel_name = "BNOW";
								}
								if($arrival->email){
									echo "Going to send mail";
									$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
									$image = base_url().'uploads/logo/'.$admin_detail->site_logo;
									if ($roomImage != '' && $roomImage != 0) {
										$image = base_url().'uploads/room_photos/'.$roomImage;
									}
									$content = array(
										'###HOTELNAME###' => $hotel['property_name'],
										'###GUESTNAME###' => ucfirst((strlen($Fullname)==0?$arrival->guest_name:$Fullname)),
										'###SITENAME###' => $admin_detail->company_name,
										'###TOWN###' => $hotel['town'],
										'###ADDRESS###' => $hotel['address'],
										'###ZIP###' => $hotel['zip_code'],
										'###EMAIL###' => $hotel['email_address'],
										'###MOBILE###' => $hotel['mobile'],
										'###RESERVATIONCODE###' => $arrival->reservation_code,
										'###COMPANYLOGO###' =>  $image,
									);
									$get_email_info     = get_mail_template('33');

									$email_subject1		= $get_email_info['subject'];
									$email_subject1		= strtr($email_subject1,$content);
									$email_content1		= $get_email_info['message'];
									$email_content		= strtr($email_content1,$content);

									$subject			= $email_subject1;

									$this->mailsettings();

									$this->email->from($admin_detail->email_id);

									$this->email->to($arrival->email);

									//$this->email->cc($cc);

									$this->email->subject($subject);

									$this->email->message($email_content);

									$this->email->send();
								}
							}
						}
					}
				}
		   	}
		}

		echo "All Reminder Arrivals Mails Send";
	}

	function send_departure_thank_mail_Cron(){
		$hoteldetails = get_data(HOTEL,array('status'=>'1'))->result_array();

		$types = array('depature');
		//print_r($hoteldetails);
		foreach($hoteldetails as $hotel){
			if($hotel['hotel_id'] != '25'){
				continue;
			}
			$total_arrival_departure = array();
			foreach($types as $type){
				$arrdep = $this->reservation_model->get_todays_arrival_departure($type,$hotel['owner_id'],$hotel['hotel_id'],'thanking');

				if(count($arrdep) != 0){
					//print_r($arrdep);
					$total_arrival_departure[$type][] = $arrdep;
				}
			}
			$arrivaltable = "";
			$departuretable = "";
			$user_id = $hotel['owner_id'];
			$hotel_id = $hotel['hotel_id'];
			$roomName = "No Room Set";
			$Fullname="";
			//echo $this->db->last_query();
			//print_r($total_arrival_departure);
			//continue;
			if(count($total_arrival_departure) != 0)
			{
				if(isset($total_arrival_departure['depature']))
				{
					foreach ($total_arrival_departure['depature'] as $departures)
					{
						//print_r($departures);
						if(count($departures) != 0){
							foreach($departures as $departure)
							{
								$Fullname="";
								if($departure->channel_id == 0)
								{
									$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$departure->room_id))->row()->property_name;

									$roomImage= @get_data(TBL_PROPERTY,array('property_id'=>$departure->room_id))->row()->image;
									$channel_name = "hoteratus";
								}
								else if($departure->channel_id == 1)
								{
									$this->db->where('user_id',$user_id);
		                            $this->db->where('hotel_id',$hotel_id);
		                            $this->db->where('rate_type_id',$departure->rateplanid);
		                            $this->db->where('roomtype_id',$departure->roomtypeId);
		                            $details = $this->db->get('import_mapping')->row();
		                            $roomtypeid = $departure->roomtypeId;
									$rateplanid = $departure->rateplanid;

									$roomdetails = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
		                            if(count($roomdetails) !=0)
		                            {
		                                $roomtypeid = $roomdetails['roomtypeId'];
		                                $rateplanid = $roomdetails['rateplanid'];
		                            }

									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									if(!$roomName){
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->property_name;

										$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row()->map_id))->row()->property_id))->row()->image;
									}

									$channel_name = "Expedia";
								}
								else if($departure->channel_id == 11)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$departure->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$departure->ROOMCODE,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->re_id))->row()->property_id))->row()->image;
									$channel_name = "Reconline";
								}
								else if($departure->channel_id == 8)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$departure->room_id,'rateplan_id'=>$departure->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$departure->room_id,'rateplan_id'=>$departure->rateplanid,'user_id'=>$user_id,'hotel_id' => $hotel_id))->row()->GTA_id))->row()->property_id))->row()->image;
									$channel_name = "GTA";
								}
								elseif($departure->channel_id==2)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$departure->id,'B_rate_id'=>$departure->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$departure->id,'B_rate_id'=>$departure->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$departure->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$departure->id,'B_rate_id'=>$departure->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->image;

									$channel_name = "Booking.com";

									$Fullname = $this->db->query("SELECT concat_ws (' ', first_name,last_name)  Fullname  FROM import_reservation_BOOKING_ROOMS as a
									left join import_reservation_BOOKING as b on a.import_reserv_id = b.import_reserv_id
									WHERE `roomreservation_id`=".$departure->reservation_code)->row()->Fullname;


								}
								elseif ($departure->channel_id == 17) {
									$room_id = @get_data(MAP,array('channel_id'=>$bnowmodel['channel_id'],'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$roomtypeid,'RatePlanCode'=>$rateplanid,'user_id' =>$user_id,'hotel_id'=>$hotel_id))->row()->import_mapping_id))->row()->property_id;

									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->property_name;
									$roomImage = @get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->image;
									$channel_name = "BNOW";
								}
								//echo "ROOMIMAGE=>$roomImage";
								$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
								$image = base_url().'uploads/logo/'.$admin_detail->site_logo;
								if ($roomImage != '' && $roomImage != 0) {
									$image = base_url().'uploads/room_photos/'.$roomImage;
								}
								$content = array(
									'###HOTELNAME###' => $hotel['property_name'],
									'###GUESTNAME###' => ucfirst((strlen($Fullname)==0?$departure->guest_name:$Fullname)),
									'###ROOMNAME###' => ucfirst($roomName),
									'###START###' => date('M d,Y',strtotime(str_replace("/","-",$departure->start_date))),
									'###END###' => date('M d,Y',strtotime(str_replace("/","-",$departure->end_date))),
									'###PRICE###' => $departure->price,
									'###SITENAME###' => $admin_detail->company_name,
									'###RESERVATIONCODE###' => $departure->reservation_code,
									'###COMPANYLOGO###' =>  $image,
								);
								//print_R($content);

								if($departure->email){
									$get_email_info     = get_mail_template('31');

									$email_subject1		= $get_email_info['subject'];
									$email_subject1		= strtr($email_subject1,$content);
									$email_content1		= $get_email_info['message'];
									$email_content		= strtr($email_content1,$content);

									$subject			= $email_subject1;

									$this->mailsettings();

									$this->email->from($admin_detail->email_id);

									$this->email->to($departure->email);

									//$this->email->cc($cc);

									$this->email->subject($subject);

									$this->email->message($email_content);

									$this->email->send();
								}
							}
						}
					}
				}
		   	}
		}

		echo "All Thanking Departure Mails Send";
	}

	function bookingreport($type,$bookingid,$res_id){

		if($type == "cc"){

			$res = $this->booking_model->invalid_cc_request(insep_decode($bookingid),current_user_type(),hotel_id());
		}else if($type == "noshow"){
			$res = $this->booking_model->booking_no_show(insep_decode($bookingid),current_user_type(),hotel_id());
		}

		if($res != ""){
			$this->session->set_flashdata('error', (string)$res);

		}else{
			$this->session->set_flashdata('success','Notification Sent Successfully');
		}
		redirect('reservation/reservation_channel/'.secure(2).'/'.$res_id,'refresh');
	}

	/* Remove Credit Card Details Function Start */
	function removeCreditCardDetails()
	{
		//echo '<pre>';
		$manualReservation	=	$this->db->query("SELECT `reservation_id` as del_reservation_id FROM (manage_reservation) WHERE DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') <= ( CURDATE() - INTERVAL 2 DAY )");
		if($manualReservation)
		{
			$manualReservationResult	=	$manualReservation->result_array();
			if(count($manualReservationResult)!=0)
			{
				foreach($manualReservationResult as $res)
				{
					extract($res);
					delete_data(CARD,array('resrv_id'=>$del_reservation_id));
				}
			}
		}
		//echo $this->db->last_query().'<br> <br>';

		$this->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.card_dtails,A.fetch_query_count');
		$this->db->where(array('A.card_dtails !='=>'0'));
		$query = $this->db->get(ALL.' as A');
		if($query)
		{
			$result	=	$query->result_array();
			if(count($result)!=0)
			{
				foreach($result as $value)
				{
					extract($value);
					$select			=	explode(',',$fetch_query_count);
					$update_field 	= 	explode(',',$card_dtails);
					$update_column_field='';
					foreach($update_field as $up_column)
					{
						$update_column_field[$up_column] = '';
					}
					if($channel_id==2)
					{
						$group = " GROUP BY reservation_id";
					}
					else
					{
						$group = '';
					}
					$cahquery = $this->db->query('SELECT `'.$select[0].'` as reservation_id, `'.$select[15].'`  FROM ('.$channel_table_name.') WHERE DATE_FORMAT('.$select[6].',"%Y-%m-%d") <= ( CURDATE() - INTERVAL 2 DAY ) '.$group.'');
					//echo $this->db->last_query().'<br> <br>';
					if($cahquery)
					{
						$chaReserCheckCount = $cahquery->result_array();
						if(count($chaReserCheckCount)!=0)
						{
							foreach($chaReserCheckCount as $del_value)
							{
								extract($del_value);
								if($channel_id==2)
								{
									$select_id = get_data($channel_table_name,array($select[0]=>$reservation_id))->row()->reservation_id.'<br>';
									$current_date_time_book	=	get_data(BOOK_RESERV,array('id'=>$select_id))->row()->current_date_time;
									$update_column_field['current_date_time'] = $current_date_time_book;
									update_data(BOOK_RESERV,$update_column_field,array('id'=>$select_id));
									//echo $this->db->last_query().'<br> <br>';
									//print_r($update_column_field);
								}
								else
								{
									$update_column_field['current_date_time'] = $current_date_time;
									update_data($channel_table_name,$update_column_field,array($select[0]=>$reservation_id));
									//echo $this->db->last_query().'<br> <br>';
									//print_r($update_column_field);
								}
							}
						}
					}
				}
			}
		}
	}
	/* Remove Credit Card Details Function End */

	function getCronReservationFromGTA()
	{
		$channelDetails = get_data(CONNECT,array('channel_id'=>8,'status'=>'enabled','mode'=>1))->result();
		$cha_name = "GTA";
		foreach($channelDetails as $ch_details)
		{
			$start = date(DATE_ATOM);
			$start = explode("+", $start);
			$end = date(DATE_ATOM, strtotime("-30 days"));
			$end = explode("+", $end);

			$soapUrl="https://hotels.gta-travel.com/supplierapi/rest/bookings/search";

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
					ModifiedStartDate="'.$end[0].'"
					dModifiedEndDate="'.$start[0].'"/>
			</GTA_BookingSearchRQ>';
			$x_r_rq_data['channel_id'] = '8';
			$x_r_rq_data['user_id'] = '0';
			$x_r_rq_data['hotel_id'] = '0';
			$x_r_rq_data['message'] = $xml_post_string;
			$x_r_rq_data['type'] = 'GTA_REQ';
			$x_r_rq_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rq_data);

			$ch = curl_init($soapUrl);
			//curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			makeLog('gtaReservationLog.txt', "RESERVATIOM URL=>$soapUrl\n\nContent=>$xml_post_string\n\nResponse=>$output\n");
			$x_r_rs_data['channel_id'] = '8';
			$x_r_rs_data['user_id'] = '0';
			$x_r_rs_data['hotel_id'] = '0';
			$x_r_rs_data['message'] = $output;
			$x_r_rs_data['type'] = 'GTA_RES';
			$x_r_rs_data['section'] = 'RESER';
			//$this->reservationxmlmail($x_r_rs_data);
			$data = simplexml_load_string($output);

			$data=$data->Bookings->Booking;
			if(count($data) != 0)
			{
				$this->reservationxmlmail($x_r_rs_data);
			}
			if(count($data) != 0)
			{
				foreach($data as $row){
					$res_data=array();
					$user_id=$ch_details->user_id;
					$hotel_hotel_id = $ch_details->hotel_id;
					$res_data['user_id'] = "$user_id";
					$res_data['hotel_id'] = $hotel_hotel_id;
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

					$room_id=$row->Rooms->Room->attributes()->Id;
					$res_data['room_id']="$room_id";
					$roomcategory=$row->Rooms->Room->attributes()->RoomCategory;
					$res_data['roomcategory']="$roomcategory";
					//	$res_data['roomcategory']=$row->Rooms->Room->attributes()->RoomType;
					$qty=$row->Rooms->Room->attributes()->Quantity;
					$res_data['room_qty']="$qty";

					$rates=$row->Rooms->Room->Rates;
					$room_array=array();
					foreach($rates as $rate)
					{
						foreach($rates->StayDates as $roomrow){

							$value=array();
							$dateval=$roomrow->attributes()->Date;
							$datearry=explode('-',$dateval);
							$date=$datearry[2].'/'.$datearry[1].'/'.$datearry[0];
							$value['date']=$date;
							$value['Tax']=$roomrow->attributes()->Tax;
							$value['NettCost']=$roomrow->attributes()->NettCost;
							$value['Adhoced']=$roomrow->attributes()->Adhoced;
							$value['Supplement1ExtraBedNettCost']=$roomrow->attributes()->Supplement1ExtraBedNettCost;
							$value['Supplement2ExtraBedNettCost']=$roomrow->attributes()->Supplement2ExtraBedNettCost;
							$value['SupplementSharingBedNettCost']=$roomrow->attributes()->SupplementSharingBedNettCost;
							$value['SupplementCotNettCost']=$roomrow->attributes()->SupplementCotNettCost;
							$value['SupplementTax']=$roomrow->attributes()->SupplementTax;
							$room_array[]=$value;
						}
					}

					$room_costdetils=json_encode($room_array);
				    $res_data['room_costdetils']="$room_costdetils";
					$passengers=$row->Passengers->Passenger;
					$passenger_details=array();
					foreach($passengers as $pass ){
						$passarray=array();
						$passarray['name']=$pass->attributes()->Name;
						if(isset($pass->attributes()->Age)){
							$passarray['age']=$pass->attributes()->Age;
						}else{
							$passarray['age']="Adult";
						}
						$passenger_details[]=$passarray;

					}
					$passenger_array=json_encode($passenger_details);
				    $res_data['passenger_names']="$passenger_array";
				    $available = get_data('import_reservation_GTA',array('user_id'=>$user_id,'hotel_id'=>$hotel_hotel_id,'hotelid'=>$hotel_id,'booking_id'=>$booking_id))->row_array();
				 	//  echo $this->db->last_query(); exit;
					if(count($available)==0)
					{

							insert_data('import_reservation_GTA',$res_data);

					}
					else
					{
						update_data('import_reservation_GTA',$res_data,array('user_id'=>$user_id,'hotelid'=>$hotel_id,'hotel_id'=>$hotel_hotel_id,'booking_id'=>$booking_id));

					}
					$importBookingDetails = get_data('import_reservation_GTA',array('user_id'=>$user_id,'hotelid'=>$hotel_id,'hotel_id'=>$hotel_hotel_id,'booking_id'=>$booking_id))->row_array();
		            if(count($importBookingDetails)!=0)
		            {
		                $arrival = date('Y-m-d',strtotime($importBookingDetails['arrdate']));
		                $departure = date('Y-m-d',strtotime($importBookingDetails['depdate']));
		                $importBookingDetails = get_data(IM_GTA,array('ID'=>$importBookingDetails['room_id']),'GTA_id')->row_array();
		                if(count($importBookingDetails)!=0)
		                {
		                    $roomMappingDetails = get_data(MAP,array('import_mapping_id'=>$importBookingDetails['GTA_id'],'channel_id'=>8))->row_array();
			                if(count($roomMappingDetails)!=0)
			                {
			                  require_once(APPPATH.'controllers/mapping.php');
			                  $callAvailabilities = new Mapping();
			                  $callAvailabilities->importAvailabilities_Cron($user_id,$hotel_hotel_id,insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['GTA_id'],'mapping');
			                }
		              	}
		            }
					$this->reservation_log(8,$booking_id,$user_id,$hotel_hotel_id);
					$this->send_confirmation_mail($user_id,$hotel_id,$booking_id,8);
				}
			}
		}
		$meg['result'] = '1';
		$meg['content']='Successfully import reservation from '.$cha_name.'!!!';
		echo json_encode($meg);
	}

	function getRoomtypeid()
    {
        $data = getExpediaRoom('201425478','207056798A','18599','301');
        echo "<pre>";
        print_r($data);
    }

    function mailsettings_xml()
	{
		$this->load->library('email');
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'text'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);
	}

    function reservationxmlmail($data="")
    {
    	//insert_data(ALL_XML,$data);

    	if ($data['channel_id'] !='')
    	{
    			if ($data['channel_id'] =='2')
    			{
    				$cha_name="Booking.com";
    			}
    			else
    			{
    				$cha_name=get_data('manage_channel', array('channel_id'=>$data['channel_id']))->row()->channel_name;
    			}


    			mail("datahernandez@gmail.com","Canal nomber",$cha_name );

    	}
    	else
    	{
    		$cha_name=$data['channel_id'];
    	}


		$message = $this->load->view("email/xmlemail",$data,TRUE);

		if($message!='')
		{
			$subject = "Reservation From ".$cha_name;

			//$admin_detail = get_data('site_config',array('id'=>1))->row();

			$this->mailsettings_xml();

			$this->email->from("info@hoteratus.com");

			$this->email->to("xml@hoteratus.com");

			$this->email->subject($subject);

			$this->email->message($message);

			$this->email->send();
		}
    }



    function send_confirmation_mail($user_id,$hotel_id,$rese_id,$curr_cha_id)
    {
    	if($curr_cha_id==11)
		{
			$print_details = get_data(REC_RESERV,array('IDRSV'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$data['reservation_code'] 	= $print_details['IDRSV'];
				$data['channel_name']       = "Reconline";
				$data['start_date']			= $print_details['CHECKIN'];
				$data['end_date']			= $print_details['CHECKOUT'];
				$checkin=date('Y/m/d',strtotime($print_details['CHECKIN']));
				$checkout=date('Y/m/d',strtotime($print_details['CHECKOUT']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['currency']	= $print_details['CURRENCY'];
				$data['price'] 		= $print_details['REVENUE'];

				$data['reservation_id'] = $print_details['import_reserv_id'];
				$data['guest_name'] = $print_details['FIRSTNAME'];
				$data['email'] = $print_details['EMAIL'];
				$data['street_name'] = $print_details['ADDRESS1'];
				$data['city_name'] = $print_details['CITY'];
				$data['country'] = $print_details['COUNTRY'];
				if($print_details['STATUS']==11)
				{
					$data['status'] = 'New booking';
				}
				else if($print_details['STATUS']==12)
				{
					$data['status'] = 'Modification';
				}
				else if($print_details['STATUS']==13)
				{
					$data['status'] = 'Cancellation';
				}
				$data['booking_date'] 	= $print_details['RSVCREATE'];
				$data['commission']	  	= $print_details['COMMISSION'];
				$data['mealsinc']		= $print_details['MEALSINC'];
				if($print_details['CCIDCARDORGANISATION']='0')
				{
					$data['payment_method'] = 'Cash';
				}
				else
				{
					$data['payment_method'] = 'Credit Card';
				}
				$data['members_count']		= $print_details['ADULTS'];
				$data['description']		= $print_details['REMARK'];
				$data['policy_checin']		= $print_details['CORRCHECKIN'];
				$data['policy_checout']		= $print_details['CORRCHECKOUT'];
			}
		}elseif($curr_cha_id==8){

			$print_details = get_data('import_reservation_GTA',array('booking_id'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$data['reservation_code'] 	= $print_details['booking_id'];
				$data['start_date']			= $print_details['arrdate'];
				$data['channel_name']       = "GTA";
				$data['end_date']			= $print_details['depdate'];
				$checkin=date('Y/m/d',strtotime($print_details['arrdate']));
				$checkout=date('Y/m/d',strtotime($print_details['depdate']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['currency']	= $print_details['currencycode'];
				$data['price'] 		= $print_details['totalcost'];

				$data['reservation_id'] = $print_details['import_reserv_id'];
				$data['guest_name'] = $print_details['leadname'];
				$data['mobile']   = "";
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = $print_details['city'];
				$data['country'] = "";
				if($print_details['offer'] != "0.00"){
					$data['offer'] = $print_details['offer'];
				}

				$perdayprice = json_decode($print_details['room_costdetils']);

				foreach ($perdayprice as $perdaypri){
					foreach($perdaypri->NettCost as $rate)
					$data['perdayprice'][] = array(
						$perdaypri->date => $rate,
					);
				}

				if($print_details['status']== "Confirmed")
				{
					$data['status'] = 'New booking';
				}
				else if($print_details['status']== "Canceled")
				{
					$data['status'] = 'Cancellation';
				}
				else
				{
					$data['status'] = 'Modification';
				}
				$data['booking_date'] 	= $print_details['modifieddate'];
				$data['commission']	  	= "NONE";
				$data['mealsinc']		= "";
				$data['payment_method'] = 'Cash';
				$data['members_count']		= $print_details['adults'];
				$data['description']		= "";
				$data['cancel_description'] = "";
				$data['meal_name'] = "None";
				$data['policy_checin']		= $print_details['arrdate'];
				$data['policy_checout']		= $print_details['depdate'];
			}
		}elseif($curr_cha_id==5){

			$reservation_details = get_data('import_reservation_HOTELBEDS',array('RefNumber'=>$rese_id))->row_array();
			if(count($reservation_details)!=0)
			{
				$totamount 	= $reservation_details['Amount'];
				$adult 		= $reservation_details['AdultCount'];
				$child 		= $reservation_details['ChildCount'] + $reservation_details['BabyCount'];
				$checkin 	= $reservation_details['DateFrom'];
				$checkout 	= $reservation_details['DateTo'];
				$currency 	= explode(',', $reservation_details['Currency']);
				$name 		= $reservation_details['Customer_Name'];
				$data['reservation_code'] 	= $reservation_details['RefNumber'];
				$data['start_date']			= $checkin;
				$data['end_date']			= $checkout;
				$checkin=date('Y/m/d',strtotime($checkin));
				$checkout=date('Y/m/d',strtotime($checkout));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$rateprice = explode(',', $reservation_details['Rate_DateFrom']);
	            $rateendprice = explode(',', $reservation_details['Rate_DateTo']);
	            $priceperdate = explode(',', $reservation_details['Amount']);

	            $total_amount = 0;

	            for($i=0; $i<count($rateprice); $i++){
					$originalstartDate = date('M d,Y',strtotime($rateprice[$i]));
	              	$newstartDate = date("Y/m/d", strtotime($originalstartDate));
	              	$originalendDate = date('M d,Y',strtotime($rateendprice[$i]));
	              	$newendDate = date("Y/m/d", strtotime($originalendDate));
	              	$begin = new DateTime($newstartDate);
	              	$ends = new DateTime($newendDate);
	              	$daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
	              	foreach($daterange as $ran)
	              	{
	              		$string = date('Y-m-d',strtotime(str_replace('/','-',$ran->format('M d, Y'))));
	                	$data['perdayprice'][] = array(
	                			$string  => $priceperdate[$i],
	                		);
	                	$total_amount = $total_amount + $priceperdate[$i];
	                }
				}
				$data['currency']	= $currency[0];
				$data['price'] 		= $total_amount;

				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $name;
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = "";
				$data['country'] = "";

				if($reservation_details['Status']== "BOOKING")
				{
					$data['status'] = 'New booking';
				}
				else if($reservation_details['Status']== "MODIFIED")
				{
					$data['status'] = 'Modification';
				}
				else if($reservation_details['Status']== "CANCELED]")
				{
					$data['status'] = 'Cancellation';
				}
				$data['booking_date'] 	= $reservation_details['CreationDate'];
				$data['commission']	  	= "NONE";
				$data['mealsinc']		= "";
				$data['payment_method'] = 'Cash';

				$data['members_count']		= $adult;
				$data['description']		= $reservation_details['Remarks'];
				//$data['description']		= "";
				$data['cancel_description'] = "";
				$data['meal_name'] = "None";
				$data['policy_checin']		= $checkin;
				$data['policy_checout']		= $checkout;
			}
		}
		else if($curr_cha_id==17)
		{
			$print_details =	$this->bnow_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "BNOW";
				$data					=	$print_details;
			}
		}
		else if($curr_cha_id==15)
		{
			$print_details =	$this->travel_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "Travelrepublic";
				$data					=	$print_details;
			}
		}
		else if($curr_cha_id==36)
		{	$this->load->model("despegar_model");
			$print_details =	$this->despegar_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "Despegar";
				$data					=	$print_details;
			}
		}

		$channel_data = get_data("user_connect_channel",array("user_id" => $user_id,'hotel_id'=>$hotel_id,'channel_id' => $curr_cha_id))->row();
		if($channel_data)
		{
			if(count($data) != 0)
			{
				$data['hotel_id'] = $hotel_id;
				$data['user_id'] = $user_id;
				$data['curr_cha_id'] = $curr_cha_id;

				$message = $this->load->view("email/reservationemail",$data,TRUE);

		        $subject = "Reservation #".$data['reservation_code']." From ".$data['channel_name'];

		        $admin_detail = get_data(TBL_SITE,array('id'=>1))->row();

		        $this->mailsettings();

		        $this->email->from($admin_detail->email_id);

		        $this->email->to($channel_data->reservation_email);

		        //$this->email->bcc('thirupathi@osiztechnologies.com');
		        $this->email->bcc('felix@hoteratus.com');

		        $this->email->subject($subject);

		        $this->email->message($message);

	       		$this->email->send();
	       	}
       	}
    }

	function getMissedReservationFromGTA()
	{

		$start = date(DATE_ATOM);
		$start = explode("+", $start);
		$end = date(DATE_ATOM, strtotime("-30 days"));
		$end = explode("+", $end);

		//$soapUrl = trim($book['booking']);
		$soapUrl="https://hotels.gta-travel.com/supplierapi/rest/bookings/search";


		//$soapUrl="https://hotels.demo.gta-travel.com/supplierapi/rest/bookings/search";

		$xml_post_string='<GTA_BookingSearchRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.gta-travel.com/GTA/2012/05">
			<User Qualifier="'.$ch_details->web_id.'" Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
			<SearchCriteria
				PropertyId="'.$ch_details->hotel_channel_id.'"
				ModifiedStartDate="'.$end[0].'"
				ModifiedEndDate="'.$start[0].'"/>
		</GTA_BookingSearchRQ>';
		/*$xml_post_string='<GTA_BookingSearchRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.gta-travel.com/GTA/2012/05">
			<User Qualifier="'.$ch_details->web_id.'" Password="'.$ch_details->user_password.'" UserName="'.$ch_details->user_name.'" />
			<SearchCriteria PropertyId="'.$ch_details->hotel_channel_id.'" ModifiedStartDate="2016-07-01T00:00:00"/>
		</GTA_BookingSearchRQ>';*/

		$x_r_rq_data['channel_id'] = '8';
		$x_r_rq_data['user_id'] = current_user_type();
		$x_r_rq_data['hotel_id'] = hotel_id();
		$x_r_rq_data['message'] = $xml_post_string;
		$x_r_rq_data['type'] = 'GTA_REQ';
		$x_r_rq_data['section'] = 'RESER';
		//$this->reservationxmlmail($x_r_rq_data);
		//echo $xml_post_string;
		/*$ch = curl_init($soapUrl);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$x_r_rs_data['channel_id'] = '8';
		$x_r_rs_data['user_id'] = current_user_type();
		$x_r_rs_data['hotel_id'] = hotel_id();
		$x_r_rs_data['message'] = $output;
		$x_r_rs_data['type'] = 'GTA_RES';
		$x_r_rs_data['section'] = 'RESER';*/
		//$this->reservationxmlmail($x_r_rs_data);
		/* echo '<pre>';
		print_r($output); */
		$output  = '<GTA_BookingSearchRS User="AKRT97:hotelavail" Version="1.30" Env="I" xmlns="http://www.gta-travel
.com/GTA/2012/05">
 <Success/>
 <Bookings PropertyId="116373">

  <Booking BookingId="28887236" BookingRef="028/18712493/1" Status="Cancelled" ContractId="134564" ContractType
="Margin" RatePlanId="172754" RatePlanCode="BAR" RatePlanName="Bed and Breakfast" PropertyId="116373"
 PropertyName="Apanemo Hotel" City="AKRT" ArrivalDate="2016-09-20" DepartureDate="2016-09-23" Nights
="3" LeadName="BARBARA HONKIS" TotalAdults="2" TotalChildren="0" TotalCots="0" TotalCost="472.5" TotalRoomsCost
="472.50" TotalOffers="0.00" TotalSupplements="0.00" TotalExtras="472.50" TotalAdjustments="0" TotalTax
="0" CancellationFee="472.5" CancellationFeeAdjustments="0" CurrencyCode="EUR" ModifiedDate="2016-10-10T04
:56:51">
   <Contact Name="Emmanuel Pampolina" Email="lon.fitinbound.g@gta-travel.com"/>
   <Rooms>
    <Room Id="274477" RoomCategory="EXEC" RoomType="SUITE" RoomView="OTHER" Quantity="1"/>
   </Rooms>
   <Passengers>
    <Passenger Name="BARBARA HONKIS" RoomTag="1"/>
    <Passenger Name="BARBARADois HONKIS" RoomTag="1"/>
   </Passengers>
   <Queues>
    <Queue Id="Cancellation" Name="Cancellations to register" ModifiedDate="2016-10-10T04:56:51"/>
   </Queues>
  </Booking>
</Bookings>
</GTA_BookingSearchRS>';
		$data 		=	simplexml_load_string($output);
		$Error		=	@$data->Errors->Error;
		$Warning	=	@$data->Warnings->Warning;
		$data		=	@$data->Bookings->Booking;
		if(count($data) != 0 || $Error != "" || $Warning != "")
		{
			//$this->reservationxmlmail($x_r_rs_data);
		}
		if(isset($Error))
		{
			$meg['result'] = '0';
			$meg['content']	= $Error.' from '.$cha_name.'. Try again!';
			echo json_encode($meg);
		}
		else if(isset($Warning))
		{
			$meg['result'] = '0';
			$meg['content']	= $Warning.' from '.$cha_name.'. Try again!';
			echo json_encode($meg);
		}
		else if(isset($data))
		{
			if($data)
			{
				foreach($data as $row)
				{
					$res_data=array();
					$user_id=current_user_type();
					$res_data['user_id'] = "$user_id";
					$res_data['hotel_id'] = hotel_id();
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

					$room_id=$row->Rooms->Room->attributes()->Id;
					$res_data['room_id']="$room_id";
					$roomcategory=$row->Rooms->Room->attributes()->RoomCategory;
					$res_data['roomcategory']="$roomcategory";
					//$res_data['roomcategory']=$row->Rooms->Room->attributes()->RoomType;
					$qty=$row->Rooms->Room->attributes()->Quantity;
					$res_data['room_qty']="$qty";

					$rates=$row->Rooms->Room->Rates;
					$room_array=array();
					foreach($rates as $rate)
					{
						foreach($rate->StayDates as $roomrow)
						{
							$value=array();
							$dateval=$roomrow->attributes()->Date;
							$datearry=explode('-',$dateval);
							$date=$datearry[2].'/'.$datearry[1].'/'.$datearry[0];
							$value['date']=$date;
							$value['Tax']=$roomrow->attributes()->Tax;
							$value['NettCost']=$roomrow->attributes()->NettCost;
							$value['Adhoced']=$roomrow->attributes()->Adhoced;
							$value['Supplement1ExtraBedNettCost']=$roomrow->attributes()->Supplement1ExtraBedNettCost;
							$value['Supplement2ExtraBedNettCost']=$roomrow->attributes()->Supplement2ExtraBedNettCost;
							$value['SupplementSharingBedNettCost']=$roomrow->attributes()->SupplementSharingBedNettCost;
							$value['SupplementCotNettCost']=$roomrow->attributes()->SupplementCotNettCost;
							$value['SupplementTax']=$roomrow->attributes()->SupplementTax;
							$room_array[]=$value;
						}
					}
					$room_costdetils=json_encode($room_array);
					$res_data['room_costdetils']="$room_costdetils";

					$passengers=$row->Passengers->Passenger;
					$passenger_details=array();
					foreach($passengers as $pass )
					{
						$passarray=array();
						$passarray['name']=$pass->attributes()->Name;
						if(isset($pass->attributes()->Age))
						{
							$passarray['age']=$pass->attributes()->Age;
						}
						else
						{
							$passarray['age']="Adult";
						}
						$passenger_details[]=$passarray;

					}
					$passenger_array=json_encode($passenger_details);
					$res_data['passenger_names']="$passenger_array";
					$available = get_data('import_reservation_GTA',array('user_id'=>$user_id,'hotel_id'=>hotel_id(),'hotelid'=>$hotel_id,'booking_id'=>$booking_id))->row_array();
					//echo $this->db->last_query(); exit;
					if(count($available)==0)
					{
						insert_data('import_reservation_GTA',$res_data);
					}
					else
					{
						update_data('import_reservation_GTA',$res_data,array('user_id'=>current_user_type(),'hotelid'=>$hotel_id,'hotel_id'=>hotel_id(),'booking_id'=>$booking_id));
					}
					$importBookingDetails = get_data('import_reservation_GTA',array('user_id'=>current_user_type(),'hotelid'=>$hotel_id,'hotel_id'=>hotel_id(),'booking_id'=>$booking_id))->row_array();

		            if(count($importBookingDetails)!=0)
		            {
		                $arrival = date('Y-m-d',strtotime($importBookingDetails['arrdate']));
		                $departure = date('Y-m-d',strtotime($importBookingDetails['depdate']));
		                $importBookingDetails = get_data(IM_GTA,array('ID'=>$importBookingDetails['room_id']),'GTA_id')->row_array();
		                if(count($importBookingDetails)!=0)
		                {
		                    $roomMappingDetails = get_data(MAP,array('import_mapping_id'=>$importBookingDetails['GTA_id'],'channel_id'=>8))->row_array();

			                if(count($roomMappingDetails)!=0)
			                {
			                  require_once(APPPATH.'controllers/mapping.php');
			                  $callAvailabilities = new Mapping();
			                  $callAvailabilities->importAvailabilities_Cron('18612','314',insep_encode($roomMappingDetails['channel_id']),insep_encode($roomMappingDetails['property_id']),$roomMappingDetails['rate_id'],$roomMappingDetails['guest_count'],$roomMappingDetails['refun_type'],$arrival,$departure,$importBookingDetails['GTA_id'],'mapping');
			                }
		              	}
		            }
					$this->reservation_log(8,$booking_id,current_user_type(),hotel_id());
					$this->send_confirmation_mail_test(current_user_type(),hotel_id(),$booking_id,8);
				}
				$meg['result'] 	= 	'1';
				$meg['content']	=	'Successfully import reservation from '.$cha_name.'!!!';
				echo json_encode($meg);
			}
			else
			{
				$meg['result'] = '0';
				$meg['content']="Can't import reservation from '.$cha_name.'!!!";
				echo json_encode($meg);
			}
		}
		else
		{
			$meg['result'] = '0';
			$meg['content']="Can't import reservation from '.$cha_name.'!!!";
			echo json_encode($meg);
		}

	}

	function send_confirmation_mail_test($user_id,$hotel_id,$rese_id,$curr_cha_id)
    {
    	if($curr_cha_id==11)
		{
			$print_details = get_data(REC_RESERV,array('IDRSV'=>$rese_id))->row_array();
			if(count($print_details)!=0)
			{
				$data['reservation_code'] 	= $print_details['IDRSV'];
				$data['channel_name']       = "Reconline";
				$data['start_date']			= $print_details['CHECKIN'];
				$data['end_date']			= $print_details['CHECKOUT'];
				$checkin=date('Y/m/d',strtotime($print_details['CHECKIN']));
				$checkout=date('Y/m/d',strtotime($print_details['CHECKOUT']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['currency']	= $print_details['CURRENCY'];
				$data['price'] 		= $print_details['REVENUE'];

				$data['reservation_id'] = $print_details['import_reserv_id'];
				$data['guest_name'] = $print_details['FIRSTNAME'];
				$data['email'] = $print_details['EMAIL'];
				$data['street_name'] = $print_details['ADDRESS1'];
				$data['city_name'] = $print_details['CITY'];
				$data['country'] = $print_details['COUNTRY'];
				if($print_details['STATUS']==11)
				{
					$data['status'] = 'New booking';
				}
				else if($print_details['STATUS']==12)
				{
					$data['status'] = 'Modification';
				}
				else if($print_details['STATUS']==13)
				{
					$data['status'] = 'Cancellation';
				}
				$data['booking_date'] 	= $print_details['RSVCREATE'];
				$data['commission']	  	= $print_details['COMMISSION'];
				$data['mealsinc']		= $print_details['MEALSINC'];
				if($print_details['CCIDCARDORGANISATION']='0')
				{
					$data['payment_method'] = 'Cash';
				}
				else
				{
					$data['payment_method'] = 'Credit Card';
				}
				$data['members_count']		= $print_details['ADULTS'];
				$data['description']		= $print_details['REMARK'];
				$data['policy_checin']		= $print_details['CORRCHECKIN'];
				$data['policy_checout']		= $print_details['CORRCHECKOUT'];
			}
		}elseif($curr_cha_id==8){

			$print_details = get_data('import_reservation_GTA',array('booking_id'=>$rese_id))->row_array();

			if(count($print_details)!=0)
			{
				$data['reservation_code'] 	= $print_details['booking_id'];
				$data['start_date']			= $print_details['arrdate'];
				$data['channel_name']       = "GTA";
				$data['end_date']			= $print_details['depdate'];
				$checkin=date('Y/m/d',strtotime($print_details['arrdate']));
				$checkout=date('Y/m/d',strtotime($print_details['depdate']));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['currency']	= $print_details['currencycode'];
				$data['price'] 		= $print_details['totalcost'];

				$data['reservation_id'] = $print_details['import_reserv_id'];
				$data['guest_name'] = $print_details['leadname'];
				$data['mobile']   = "";
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = $print_details['city'];
				$data['country'] = "";
				if($print_details['offer'] != "0.00"){
					$data['offer'] = $print_details['offer'];
				}

				$perdayprice = json_decode($print_details['room_costdetils']);

				foreach ($perdayprice as $perdaypri){
					foreach($perdaypri->NettCost as $rate)
					$data['perdayprice'][] = array(
						$perdaypri->date => $rate,
					);
				}

				if($print_details['status']== "Confirmed")
				{
					$data['status'] = 'New booking';
				}
				else if($print_details['status']== "Canceled")
				{
					$data['status'] = 'Cancellation';
				}
				else
				{
					$data['status'] = 'Modification';
				}
				$data['booking_date'] 	= $print_details['modifieddate'];
				$data['commission']	  	= "NONE";
				$data['mealsinc']		= "";
				$data['payment_method'] = 'Cash';
				$data['members_count']		= $print_details['adults'];
				$data['description']		= "";
				$data['cancel_description'] = "";
				$data['meal_name'] = "None";
				$data['policy_checin']		= $print_details['arrdate'];
				$data['policy_checout']		= $print_details['depdate'];
			}
		}elseif($curr_cha_id==5){

			$reservation_details = get_data('import_reservation_HOTELBEDS',array('echoToken'=>$rese_id))->row_array();
			$totamount = 0;
			$room_id = "";
			$adult = 0;
			$child = 0;
			if(strpos($reservation_details['Room_code'], ',') !== FALSE){
				$h_roomname = explode(',', $reservation_details['Room_code']);
				$h_adult = explode(',', $reservation_details['AdultCount']);
				$h_child = explode(',', $reservation_details['ChildCount']);
				$h_baby = explode(',', $reservation_details['BabyCount']);
				$h_char = explode(',', $reservation_details['CharacteristicCode']);
				$h_status = explode(',', $reservation_details['RoomStatus']);
				$h_name = explode('~', $reservation_details['Customer_Name']);
				$h_cont = explode(',', $reservation_details['Contract_Name']);
				$h_arrival = explode(',', $reservation_details['DateFrom']);
				$h_depar = explode(',', $reservation_details['DateTo']);
				$h_amount = explode(',', $reservation_details['Amount']);
				$h_currency = explode(',', $reservation_details['Currency']);

				for($i=0; $i<count($h_roomname); $i++){

					$totamount = $totamount + $h_amount[$i];
					$adult = $adult + $h_adult[$i];
					$child = $child + $h_child[$i] + $h_baby[$i];

				}
				$checkin = $h_arrival[0];
				$checkout = $h_depar[0];
				$currency = $h_currency[0];
				$name = str_replace('~', ',', $reservation_details['Customer_Name']);
			}else{
				$totamount = $reservation_details['Amount'];
				$adult = $reservation_details['AdultCount'];
				$child = $reservation_details['ChildCount'] + $reservation_details['BabyCount'];
				$checkin = $reservation_details['DateFrom'];
				$checkout = $reservation_details['DateTo'];
				$currency = $reservation_details['Currency'];
				$name = $reservation_details['Customer_Name'];
			}
			if(count($reservation_details)!=0)
			{
				$data['reservation_code'] 	= $reservation_details['echoToken'];
				$data['start_date']			= $checkin;
				$data['channel_name']       = "Hotelbeds";
				$data['end_date']			= $checkout;
				$checkin=date('Y/m/d',strtotime($checkin));
				$checkout=date('Y/m/d',strtotime($checkout));
				$nig =_datebetween($checkin,$checkout);
				$data['num_nights'] = $nig;
				$data['currency']	= $currency;
				$data['price'] 		= $totamount;

				$data['reservation_id'] = $reservation_details['import_reserv_id'];
				$data['guest_name'] = $name;
				$data['email'] = "";
				$data['street_name'] = "";
				$data['city_name'] = "";
				$data['country'] = "";

				if($reservation_details['Status']== "BOOKING")
				{
					$data['status'] = 'New booking';
				}
				else if($reservation_details['Status']== "MODIFIED")
				{
					$data['status'] = 'Modification';
				}
				else if($reservation_details['Status']== "CANCELED")
				{
					$data['status'] = 'Cancellation';
				}
				$data['booking_date'] 	= $reservation_details['CreationDate'];
				$data['commission']	  	= "NONE";
				$data['mealsinc']		= "";
				$data['payment_method'] = 'Cash';

				$data['members_count']		= $adult;
				$data['description']		= "";
				$data['cancel_description'] = "";
				$data['meal_name'] = "None";
				$data['policy_checin']		= $checkin;
				$data['policy_checout']		= $checkout;
			}
		}
		else if($curr_cha_id==17)
		{
			$print_details =	$this->bnow_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "BNOW";
				$data					=	$print_details;
			}
		}
		else if($curr_cha_id==15)
		{
			$print_details =	$this->travel_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "Travelrepublic";
				$data					=	$print_details;
			}
		}
		else if($curr_cha_id==36)
		{	$this->load->model("despegar_model");

			$print_details =	$this->despegar_model->getReservationDetails($source='print',$rese_id);
			if($data)
			{
				$data['channel_name']   =   "Despegar";
				$data					=	$print_details;
			}
		}



		$channel_data = get_data("user_connect_channel",array("user_id" => $user_id,'hotel_id'=>$hotel_id,'channel_id' => $curr_cha_id))->row();
		if($channel_data)
		{
			if(count($data) != 0)
			{
				$data['hotel_id'] = $hotel_id;
				$data['user_id'] = $user_id;
				$data['curr_cha_id'] = $curr_cha_id;

				$message = $this->load->view("email/reservationemail",$data,TRUE);

		        $subject = "Reservation #".$data['reservation_code']." From ".$data['channel_name'];

		        $admin_detail = get_data(TBL_SITE,array('id'=>1))->row();

		        $this->mailsettings();

		        $this->email->from($admin_detail->email_id);

		        //$this->email->to($channel_data->reservation_email);
		        $this->email->to('cron@hoteratus.com');

		        //$this->email->cc('cron@hoteratus.com');

		        $this->email->subject($subject);

		        $this->email->message($message);

	       		$this->email->send();
	       	}
       	}
    }

	function reservation_status()
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

		$data['user_status']	=	$method;

		if(update_data(RESERVATION,$data,array('reservation_id'=>$id)))
		{
			echo '1';
		}
		else
		{
			echo '0';
		}
	}

	/* function test_cron()
	{
		$data['username'] = 'username';
		$data['password'] = 'password';
		insert_data('booking_one_way_params',$data);
		mail("test2osiz@gmail.com"," Test Test Test ",' Test Test Test Test Test Test Test Test Test Test Test Test');
		$data['sds'] = 'test';
		echo json_encode($data);

	} */

	function report_noshows()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Repots On No shows';
	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$country_head[]="Price";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);

		$today_date = $y.'-'.$m.'-'.$h;

		$hotel_id = hotel_id();

		$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` , `country` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'No Show'");



		$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.status ,U.reservation_id , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."' AND U.status = 'No Show'");

		$hh = $query->row('price');

		//$api_price = all_api_price($today_date,'No Shows','Price');

		$api_price = '';



		if($hh!='')
		{
			$price=$hh+$api_price;
		}
		else
		{
			$price=0+$api_price;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];
				if(array_key_exists($va['country_name'],$countrya))
				{
					$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				}
				else
				{
					$countrya[$va['country_name']]=$va['prices'];
				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$data['channels'] = 'all';
	/*echo '<pre>';
	print_r($data['graphnew']);die;*/
	$this->views('channel/no_shows',$data);
	}
	else
	{
		redirect(base_url());
	}
}

function noshow_graph()
{
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}

	$data['page_heading'] = 'ReservationList';

	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();

	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->input->post('report_noshows');

	$channels = $this->input->post('channel_noshows');

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);

		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);

		$periodInterval = new DateInterval("P1D");

		$endDate->add( $periodInterval );

		$period = new DatePeriod( $startDate, $periodInterval, $endDate );

		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");

			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'No Show'");

				$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , U.reservation_id , U.status , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."' AND U.status = 'No Show'");

				$hh = $query->row('price');
				//$api_price = all_api_price($today_date,'No Shows','Price');
				$api_price = '';
				if($hh!='')
				{
					$price=$hh+$api_price;
				}
				else
				{
					$price=0+$api_price;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Revenue','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					}
					else
					{
						$countrya[$va['country_name']]=$va['prices'];
					}
				}
			}

			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			foreach($period as $date)
			{
				if($i==1)
				{
				  $start=$date->format("d");
				  $data['start_dates']=$date->format("d/m/Y");
				}
				//$date->format("d/m/Y").'<br>';
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'No Show'");

					$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'No Show'");
					$hh = $query->row('price');
					//$api_price = all_api_price($today_date,'Revenue','Price');
					$api_price = '';
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		}
	 	else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'No Show'");

					$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'No Show'");

					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					//$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					$api_price = '';
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
						$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
	 	else if($days=="today")
		{
			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'No Show'");


					$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'No Show'");

					$hh = $query->row('price');
					//$api_price = all_api_price($today_date,'Revenue','Price');
					$api_price = '';
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					//$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					$api_price = '';
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;

				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/noshow_ajax',$data);
	}
}


function report_cancellation()
{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	$data['page_heading'] = 'Repots On Cancellation';
	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();
	$data= array_merge($user_details,$data);
	$graph =array();
	$graph1 = array();
	$countrya = array();
	$country_head[]="country";
	$country_head[]="Price";
	$final_country = array();
	$total_days = date('t');
	$today_date = date('Y-m-d');
	for($h=1;$h<=$total_days;$h++)
	{
		$y = date('Y'); $m = date('m');
		$h = sprintf("%02s", $h);

		$today_date = $y.'-'.$m.'-'.$h;

		$hotel_id = hotel_id();

		$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` , `country` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'Canceled'");




		$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.status ,U.reservation_id , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."' AND U.status = 'Canceled'");

		$hh = $query->row('price');

		//$api_price = all_api_price($today_date,'No Shows','Price');

		$api_price = '';



		if($hh!='')
		{
			$price=$hh+$api_price;
		}
		else
		{
			$price=0+$api_price;
		}
		$graph[]=$today_date;
		$graph1[]=$price;
		$cc = array();
		$cc = array_merge($cc,$con_query->result_array());
		$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
		if(count($cc)!=0)
		{
			foreach($cc as $va)
			{
				$country_name = $va['country_name'];
				if(array_key_exists($va['country_name'],$countrya))
				{
					$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
				}
				else
				{
					$countrya[$va['country_name']]=$va['prices'];
				}
			}
		}
	}
	if($countrya)
	{
		foreach($countrya as $key=>$val)
		{
			$final_country[]=$key;
			$final_country[]=$val;
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$data['days'] = 'today';
	$data['channels'] = 'all';
	/*echo '<pre>';
	print_r($data);die;*/
	$this->views('channel/report_cancellation',$data);
	}
	else
	{
		redirect(base_url());
	}
}


function cancel_graph()
{
	if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
	{
	if(admin_id()=='')
	{
		$this->is_login();
	}
	else
	{
		$this->is_admin();
	}

	$data['page_heading'] = 'ReservationList';

	$user_details = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();

	$data= array_merge($user_details,$data);

	$hotel_id = hotel_id();

	$days = $this->security->xss_clean($this->input->post('report_cancellation'));

	$channels = $this->security->xss_clean($this->input->post('channel_cancellation'));

	$from_date = $this->input->post('from_date');

	$to_date = $this->input->post('to_date');

	if($from_date!='' && $to_date!='' && $days!='' && $channels!='')
	{
		$data['days'] = $days;

		$data['channel_plan'] = $channels;

		$data['from_date'] = $from_date;

		$data['to_date'] = $to_date;

		$startDate = DateTime::createFromFormat("d/m/Y",$from_date);

		$endDate = DateTime::createFromFormat("d/m/Y",$to_date);

		$periodInterval = new DateInterval("P1D");

		$endDate->add( $periodInterval );

		$period = new DatePeriod( $startDate, $periodInterval, $endDate );

		$i=1;
		$graph = array();
		$graph1 = array();
		$countrya = array();
		$country_head[]="country";
		$country_head[]="Price";
		$final_country = array();
		foreach($period as $date)
		{
			$today_date	=	$date->format("Y-m-d");

			$cc = array();
			if($channels=='all')
			{
				$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'Canceled'");


				$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , U.reservation_id , U.status , C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `hotel_id` = '".$hotel_id."' AND `booking_date` = '".$today_date."' AND U.status = 'Canceled'");

				$hh = $query->row('price');
				//$api_price = all_api_price($today_date,'No Shows','Price');
				$api_price = '';
				if($hh!='')
				{
					$price=$hh+$api_price;
				}
				else
				{
					$price=0+$api_price;
				}
				$cc = array_merge($cc,$con_query->result_array());
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
			}
			else
			{
				$api_price = all_api_price($today_date,'Revenue','Price',$channels);
				if($api_price!='')
				{
					$price=$api_price;
				}
				else
				{
					$price=0;
				}
				$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
			}
			$graph[]=$today_date;
			$graph1[]=$price;
			if(count($cc)!=0)
			{
				foreach($cc as $va)
				{
					$country_name = $va['country_name'];
					if(array_key_exists($va['country_name'],$countrya))
					{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
					}
					else
					{
						$countrya[$va['country_name']]=$va['prices'];
					}
				}
			}

			if($i==1)
			{
				$start=$date->format("d");
				$data['start_dates']=$date->format("d/m/Y");
			}
			$i++;
		}
		$data['end_dates']= $date->format("d/m/Y");
		if($countrya)
		{
			foreach($countrya as $key=>$val)
			{
				$final_country[]=$key;
				$final_country[]=$val;
			}
		}
	}
	else
	{
		if($days=='7')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-8 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			foreach($period as $date)
			{
				if($i==1)
				{
				  $start=$date->format("d");
				  $data['start_dates']=$date->format("d/m/Y");
				}
				//$date->format("d/m/Y").'<br>';
				$i++;
			}
			$end = $date->format("d");
			$data['end_dates']= $date->format("d/m/Y");
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			foreach($period as $date)
			{
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=='all')
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'Canceled'");

					$con_query = $this->db->query("SELECT SUM(U.price) AS prices, U.booking_date , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'Canceled'");
					$hh = $query->row('price');
					//$api_price = all_api_price($today_date,'Revenue','Price');
					$api_price = '';
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
		}
	 	else if($days=='30')
		{
			$data['days'] = $days;

			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$today = date('d/m/Y');

			$today_date = date('Y/m/d');

			$tomorrow = date('d/m/Y',strtotime($today_date."-30 days"));

			$startDate = DateTime::createFromFormat("d/m/Y",$today);

			$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);

			$periodInterval = new DateInterval("P1D");

			$endDate->add( $periodInterval );

			$period = new DatePeriod( $endDate, $periodInterval, $startDate );

			$i=1;
			$graph = array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();
			foreach($period as $date)
			{
				if($i==1)
				{
					$data['start_dates']=$date->format("d/m/Y");
				}
				$today_date=$date->format("Y-m-d");
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'Canceled'");



					$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'Canceled'");



					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					//$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					$api_price = '';
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;
				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
						$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
						$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
				$i++;
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['end_dates']= $date->format("d/m/Y");
		}
	 	else if($days=="today")
		{
			$data['channel_plan'] = $channels;

			$data['from_date'] = $from_date;

			$data['to_date'] = $to_date;

			$graph =array();
			$graph1 = array();
			$countrya = array();
			$country_head[]="country";
			$country_head[]="Price";
			$final_country = array();

			$total_days = date('t');
			$today_date = date('Y-m-d');
			for($h=1;$h<=$total_days;$h++)
			{
				$y = date('Y'); $m = date('m');
				$h = sprintf("%02s", $h);
				$today_date = $y.'-'.$m.'-'.$h;
				$cc = array();
				if($channels=="all")
				{
					$query = $this->db->query("SELECT Count(`reservation_id`) AS price, `booking_date` FROM (`manage_reservation`)WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND `status` = 'Canceled'");

					$con_query = $this->db->query("SELECT Count(U.reservation_id) AS prices, U.booking_date , U.country , U.country , U.reservation_id, U.status, C.country_name FROM manage_reservation U JOIN country C ON U.country = C.id WHERE `user_id` =  '".current_user_type()."' AND `booking_date` = '".$today_date."' AND `hotel_id` = '".$hotel_id."' AND U.status = 'Canceled'");

					$hh = $query->row('price');
					$api_price = all_api_price($today_date,'Revenue','Price');
					if($hh!='')
					{
						$price=$hh+$api_price;
					}
					else
					{
						$price=0+$api_price;
					}
					$cc = array_merge($cc,$con_query->result_array());
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price'));
				}
				else
				{
					$api_price = all_api_price($today_date,'Revenue','Price',$channels);
					if($api_price!='')
					{
						$price=$api_price;
					}
					else
					{
						$price=0;
					}
					$cc = array_merge($cc,all_api_price($today_date,'Revenue','Country_Price',$channels));
				}
				$graph[]=$today_date;
				$graph1[]=$price;

				if(count($cc)!=0)
				{
					foreach($cc as $va)
					{
						$country_name = $va['country_name'];
						if(array_key_exists($va['country_name'],$countrya))
						{
							$countrya[$va['country_name']] =  $countrya[$va['country_name']]+$va['prices'];
						}
						else
						{
							$countrya[$va['country_name']]=$va['prices'];
						}
					}
				}
			}
			if($countrya)
			{
				foreach($countrya as $key=>$val)
				{
					$final_country[]=$key;
					$final_country[]=$val;
				}
			}
			$data['days'] = 'today';
		}
	}
	$country_chunk=array_merge($country_head,$final_country);
	$view_county=array_chunk($country_chunk,2);
	$data['graphnew']=json_encode($view_county);
	$data['graph']=json_encode($graph);
	$data['graph1']=json_encode($graph1);
	$this->load->view('channel/cancel_ajax',$data);
	}
}


function ReservationPayment_Success()
    {
        if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        if($_REQUEST['txn_id']!='')
        {

			$txn_id = $_REQUEST['txn_id'];
			$price = $_REQUEST['mc_gross'];
			$user_det = $_REQUEST['custom'];
			$user_details = explode('|',$user_det);

			$first_name = $user_details[0];
			$email = $user_details[1];
			$hotel_id = $user_details[2];
			$user_id = $user_details[3];
			$reservation_code = $user_details[4];
			$num_rooms = $user_details[5];
			$child = $user_details[6];
			$start_date = $user_details[7];
			$end_date = $user_details[8];
			$phone = $user_details[9];
			$room_id = $user_details[10];
			$rate_type_id = $user_details[11];
			$nights = $user_details[12];
			$rooms = $user_details[13];
			$adult = $user_details[14];
			$street_name = $user_details[15];
			$country = $user_details[16];
			$province = $user_details[17];
			$city_name = $user_details[18];
			$zipcode = $user_details[19];
			$booking_date = $user_details[20];
			$notes = $user_details[21];
			$price_details = $user_details[22];
			$currency = $user_details[23];
			$guestmail = $user_details[24];

			$idata  =   array(
                            'reservation_code'=>$reservation_code,

                            'hotel_id'=>$hotel_id,

                            'user_id'=>$user_id,

                            'guest_name'=>$first_name,

                            'last_name'=>$first_name,

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

                            'price_details'=>$price_details,

                            'transaction_id'=>$txn_id,

                            'currency_id'=>$currency,
                        );
		if(insert_data('manage_reservation',$idata))
        {
            $id =  $this->db->insert_id();

        $save_note = array('type'=>'3','created_date'=>date('Y-m-d H:i:s'),'status'=>'unseen','reservation_id'=>$id,'user_id'=>$user_id,'hotel_id'=>hotel_id());

        $ver = $this->db->insert('notifications',$save_note);


        $property_details = get_data(TBL_USERS,array('user_id'=>get_data('manage_reservation',array('reservation_id'=>$id))->row()->user_id))->row();



        $cancel_details = get_data(PCANCEL,array('user_id'=>$user_id,'hotel_id'=>hotel_id()))->row();

        $other_details = get_data(POTHERS,array('user_id'=>$user_id,'hotel_id'=>hotel_id()))->row();

        $propertyname = get_data(HOTEL,array('owner_id' => $user_id,'hotel_id'=>hotel_id()))->row()->property_name;

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


        $get_email_info1   	=   get_mail_template('11');

        $subject1          	=   $get_email_info1['subject'];

        $template1         	=   $get_email_info1['message'];

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

	</tr>

</tbody>

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

      	  $data = array(

                '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,

                '###SITENAME###'=>$admin_detail->company_name,

                '###CONFIRMRESERVATION###'=>$tbl_data,

                '###SITELINK###'=>base_url(),

                '###RESERLINK###'=>base_url().'reservation/reservation_print/'.secure(0).'/'.insep_encode($id),

                '###CONFIRMLINK###'=>base_url().'reservation/admin_confirm/'.insep_encode($id),

                );

		    $data1 = array(

		               // '###USERNAME###'=>$first_name.' '.$last_name,
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

			$this->email->to(get_data(HOTEL,array('hotel_id'=>hotel_id()))->row()->email_address);

			$this->email->subject($subject_new);

			$this->email->message($content_pop);

			$this->email->send();
            if($id!='')
			{
				$pay_error['result']	=	"0";
			}
			else
			{
				$pay_error['result']	=	"1";
			}
	 if($pay_error['result']=='0')
	{

		$save_reserve	=	$id;

		if($save_reserve=="overbook")
		{
			$pay_error['message']	=	 '<div class="row" align="center">
				  <div class="co-md-12 col-sm-12">
					 <h5>You are in over booking.Try booking another day</h5>
				</div></div>';
		}

		if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			$user_id		=	current_user_type();
		}

		$data['get_reservation']	=	$this->reservation_model->get_reservation_details($save_reserve);

		$data['cancel_details']	 	=	get_data(PCANCEL,array('user_id'=>$user_id))->row();

		$data['other_details'] 		=	get_data(POTHERS,array('user_id'=>$user_id))->row();


		$data['page_heading'] = 'Payment Success';
		$this->views('channel/paypal_reservation',$data);
	}
	else
	{
		$this->load->view('channel/paypal_reservation',$data);

	}



        }
        }
		 else
		 {
            redirect(base_url());
		 }

  }


  /*    10/04/2017  changes start*/

  function update_room()
	{
		$reservation_id = $this->input->post('reservation_id');
		$room_id = $this->input->post('room_id');
		$channel_id = $this->input->post('channel_id');

		if ($channel_id==0)
		{
			$Room = array('room_id'=>$room_id);
			$update_room = update_data(RESERVATION,$Room,array('reservation_id'=>$reservation_id,'user_id'=>user_id(),'hotel_id'=>hotel_id()));
		}
		elseif($channel_id==2)
		{

			$RoomIDs = $this->db->query("SELECT  B_room_id FROM roommapping as r left join import_mapping_BOOKING as i on r.import_mapping_id =i.import_mapping_id WHERE r.property_id = ".$room_id." and r.channel_id =2 and r.hotel_id =".hotel_id())->row()->B_room_id;

			$Room = array('id'=>$RoomIDs);

			$update_room = update_data('import_reservation_BOOKING_ROOMS',$Room,array('room_res_id'=>$reservation_id,'user_id'=>user_id(),'hotel_hotel_id'=>hotel_id()));
		}

		if($update_room)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
function change_roomnumbers()
	{
		$reservation_id = $this->input->post('reservation_id');
		$room_id = $this->input->post('room_id');
		$channel_id = $this->input->post('channel_id');

		if($room_id=="Unassigned room number"){$room_id=NUll;}
		if ($channel_id==0)
		{
			$Room = array('RoomNumber'=>$room_id);

			$update_room = update_data('manage_reservation',$Room,array('reservation_code'=>$reservation_id,'user_id'=>user_id(),'hotel_id'=>hotel_id()));
		}
		elseif($channel_id==2)
		{

			$Room = array('RoomNumber'=>$room_id);

			$update_room = update_data('import_reservation_BOOKING_ROOMS',$Room,array('roomreservation_id'=>$reservation_id,'user_id'=>user_id(),'hotel_hotel_id'=>hotel_id()));
		}
		elseif($channel_id==1)
		{
			$Room = array('RoomNumber'=>$room_id);

			$update_room = update_data('import_reservation_EXPEDIA',$Room,array('booking_id'=>$reservation_id,'user_id'=>user_id(),'hotel_id'=>hotel_id()));
		}
		if($update_room)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}



  /*  10/04/2017 changes end */

}
?>
