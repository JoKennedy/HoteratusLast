<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');
class POS extends Front_Controller {

	function is_login()
	{
		if(!user_id())
		redirect(base_url()); 
		return;
	}
	function mailsettings()
    {
        $this->load->library('email');
        $config['wrapchars'] = 76; // Character count to wrap at.
        $config['priority']  = 1; // Character count to wrap at.
        $config['mailtype']  = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
        $config['charset']   = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
        $this->email->initialize($config);
    }

	function ordenhtml($data)
	{
		$html='';
		$grandtotal=0.00;
		$i=0;
		$result='';
	    foreach ($data as  $value) {
	        $i++;
		    $html.=' <tr id="'.$value['itemid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td>'.$value['itemname'].'  </td> <td>'.number_format($value['price'], 2, '.', '').'</td> <td>'.$value['qty'].'</td> <td>'.number_format($value['price']*$value['qty'], 2, '.', '').'</td>
		    	 <td  align="center"><a id="'.$value['itemid'].'" onclick="additem('."'".$value['itemid']."','".$value['isitem']."'".');"> <i class="fa fa-plus"></i></a> </td> <td align="center"><a id="'.$value['itemid'].'" onclick="deleteitem('."'".$value['itemid']."','".$value['isitem']."'".');"> <i class="fa fa-trash-o"></i></a> </td>  </tr>  ';
		    $grandtotal+=($value['price']*$value['qty']);
	   }
	   	$result['grandtotal']=$grandtotal;
	   	$result['html']=$html;
		return $result;
	}

	function viewpos($hotelid,$posid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'All The Tables';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		
		$data['AllTable']=$this->db->query("SELECT a.*,(select count(*)  from mypostablereservation b where b.mypostableid=a.postableid and  datetimereservation='$today' ) appointment, (select count(*) from orderslist  where mypostableid= a.postableid and active =1 ) used FROM mypostable a where  myposId=$posid ")->result_array();
		$data['AllTurns']=$this->db->query("select * from posturns where posid=$posid ")->result_array();
		$data['Turnuser']=$this->db->query("select * from posturnusers where posid=$posid and userid=".user_id())->row_array();
 
		switch ($data['Posinfo']['postypeID']) {
			case '1':
				$this->views('Restaurant/main',$data);
				break;
			case '3':
				$this->views('Restaurant/main',$data);
				break;
			default:
				$this->views('Restaurant/mainnormal',$data);
				break;
		}
		
	}
	function saveposturnuser()
	{	$turnid=$_POST['id'];
		$posid=$_POST['posid'];

		$posuser=$this->db->query("select * from posturnusers where posid=$posid and userid=".user_id())->row_array();


		if(count($posuser)>0)
		{
			update_data('posturnusers',array('turnid'=>$turnid),array('posid'=>$posid,'userid'=>user_id()));
		}
		else
		{
			insert_data('posturnusers',array('turnid'=>$turnid,'posid'=>$posid,'userid'=>user_id()));
		}

		echo json_encode(array('success'=>true));

	}
	public function ReservationShow($roomnumber,$date1, $roomtypeid)
	{
		$hotel_id=hotel_id();
		$repuesta='';
		$contador=0;
		
		

		for ($i=0; $i <=23 ; $i++) { 
		
			$fecha=date('Y-m-d',strtotime($date1."+$i days"));
			$result =$this->db->query("SELECT datediff(STR_TO_DATE(end_date ,'%d/%m/%Y'),STR_TO_DATE(start_date ,'%d/%m/%Y')) noche,RoomNumber, 0 channelid,reservation_id, STR_TO_DATE(start_date ,'%d/%m/%Y') date1, STR_TO_DATE(end_date ,'%d/%m/%Y') date2 ,status FROM `manage_reservation` WHERE  '$fecha' between STR_TO_DATE(start_date ,'%d/%m/%Y') and DATE_ADD(STR_TO_DATE(end_date ,'%d/%m/%Y'), INTERVAL -1 DAY)
			and hotel_id=$hotel_id and RoomNumber='$roomnumber' and room_id=$roomtypeid and status <> 'Canceled' and status <> 'No Show' ")->row_array();

			if(!isset($result['noche']))
			{	$path="uploads/small/channels_booking.gif";
				$result=$this->db->query("SELECT datediff(a.departure_date,a.arrival_date) noche, a.arrival_date date1 , a.departure_date date2, a.RoomNumber, 2 channelid, a.room_res_id reservation_id, a.status
					from import_reservation_BOOKING_ROOMS a
					left join import_reservation_BOOKING b on a.import_reserv_id=b.import_reserv_id
					left join import_mapping_BOOKING c on a.id= c.B_room_id and a.rate_id = c.B_rate_id
					left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=2
					left join manage_property e on d.property_id = e.property_id
					WHERE  '$fecha' between a.arrival_date and 
					DATE_ADD(a.departure_date, INTERVAL -1 DAY)
					and a.hotel_hotel_id=$hotel_id and a.RoomNumber='$roomnumber' and e.property_id=$roomtypeid and a.status <>'cancelled'")->row_array();
			}
			


			if (isset($result['noche'])){

				if(strtotime($result['date1'])<date('Y-m-d'))
				{
					$result['date1']=date('Y-m-d');
				}
				$result['noche']= ceil(abs(strtotime($result['date2']) - strtotime($result['date1'])) / 86400);

				$color=($result['status']=='Checkin'?'#096fbf':($result['status']=='Checkout'?'#FF5733':'#52c748') )  ;
				
				if ($contador>0) {
					$repuesta .= '<td bgcolor="#E5E7E9" COLSPAN="'.$contador.'" > </td>';
					$contador=0;
				}
				$repuesta .= '<td bgcolor="'.$color.'" COLSPAN="'.$result['noche'].'" ><a style="font-size:6px; " href="'.site_url('reservation/reservationdetails/'.secure($result['channelid']).'/'.insep_encode($result['reservation_id'])).'"> <img src="data:image/png;base64,'. base64_encode(file_get_contents($path)).'">  </a></td>';

				$i += $result['noche']-1;
				
			}
			else
			{
				$contador++;
				
			}
		}

		if($contador>0)
		{
			$repuesta .= '<td bgcolor="#E5E7E9" COLSPAN="'.$contador.'" > </td>';
		}

		return $repuesta;


	}
	function calendarFull()
	{
		
				
		$ss=0;
		$cta=0;
		$ctd=0;
		$showr=0;

		$dataini=date('Y-m-d',strtotime($_POST['dateC']));
		

		$date1=$dataini;
		$date2=date('Y-m-d',strtotime($date1.'+31 days'));
		$fecha = new DateTime();
		$fecha->modify('last day of this month');
		$lastday= $fecha->format('d');
		$posid=$_POST['posid'];

		
		$hotel_id=hotel_id();

		
		$html='<table    class="tablanew" border=1 cellspacing=0 cellpadding=2 bordercolor="#B2BABB" > ';
		 $header1='<thead> <tr> <th style="text-align:center; "> Table Name </th>';
		 $header2=' <thead> <tr>  <th bgcolor="#E5E7E9"></th>';

		 $mes= '';
		 for ($i=0; $i <=23 ; $i++) { 
		 	

		 	
		 	 if ($i==0) {
		 	 	
		 	 	$header1 .='<th  COLSPAN="24" style="text-align:center; margin: 15px; padding: 15px;"><strong>'.$_POST['dateC'].'</strong> </th>';
		 	

		 	 }
		 	 
		 	 $header2.= '<th bgcolor="'.($i%2?'#FBFCFC':'#E5E7E9').'" style=" font-size:15px; text-align:center;  margin: 10px; padding: 5px;">'.$i.':00</th>';	
		 	 

		 }

		
		 $header1 .=' </tr> </thead> ';
		 $header2 .='	</tr> </thead>';
		 


		$room=$this->db->query("SELECT * FROM mypostable where myposid=$posid")->result_array();

		$body='<tbody>';

		foreach ($room as $value) {
			$precio='<tr>';
			$avai='<tr>';
			$min = '<tr>';
			
			$body .='<tr>  <td ROWSPAN="4" style="margin: 5px; padding:5px;">'.$value['description'].'</td> </tr> ';
			$room2='';

			$dato=null;

			$datos= $this->db->query("SELECT * FROM mypostablereservation where datetimereservation='".date('Y-m-d'). "' and mypostableid= ".$value['postableid'])->result_array(); 
			

			 for ($i=0; $i <=23 ; $i++) { 


			 		foreach ($datos as $value) {

			 			if("$i:00:00"==$value['starttime1'])
			 			{
			 				$dato=$value;
			 				break;
			 			}
			 			else
			 			{
			 				$dato=null;
			 			}
			 			
			 		}
			 	$isOpen=$this->isOpenPos($posid,$dataini,"$i:00:00");
			
			 	$color=(isset($dato['starttime1']) || $isOpen ==0?'#FF5733':'#52c748');
		 		$precio.='<td bgcolor="'.$color.'" style="font-size: 12px; text-align:center;" >'.(isset($dato['starttime1']) || $isOpen ==0?($isOpen==0?'C':'R'):'A').'</td>';  

		 	}
	
				$precio.='</tr>';
				$room2.='</tr>';
				$body .=$precio.$avai.$min.($showr==1?$room2:'');
			
		}


		$body .='</tbody> </table>';
		echo  $html.$header1.$header2.$body;

	}
	function isOpenPos($posid,$date1,$hour1)
	{	
		$day= date('N', strtotime($date1));
		$avai=$this->db->query("SELECT count(*) avai from myposschedule where posid=$posid and daysofweek= $day and time('$hour1') between startdate and enddate")->row_array()['avai'];
		return $avai;

	}
	function viewCreationtable($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'New Table';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllTable']=$this->db->query("SELECT a.*,(select count(*)  from mypostablereservation b where b.mypostableid=a.postableid and  datetimereservation='$today' ) appointment, (select count(*) from orderslist  where mypostableid= a.postableid and active =1 ) used FROM mypostable a where  myposId=$posid ")->result_array();
		$this->views('Restaurant/creationtable',$data);
	}
	function viewCategories($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Categories';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllCategories']=$this->db->query("SELECT * FROM itemcategory  where  posId=$posid ")->result_array();
		$this->views('Restaurant/categories',$data);
	}
	function viewReports($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Sales Report';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();

		$this->views('Restaurant/reports',$data);
	}
	function salesReport()
	{

		$date1=date('Y-m-d',strtotime(getpost('startdate')));
		$date2=date('Y-m-d',strtotime(getpost('enddate')));
		$type=getpost('type');
		$posid=getpost('posid');
		$types=array('1'=>'Group by Date','2'=>'Group by Users and Date','3'=>'Group by Item','4'=>'Group by Category','5'=>'Summarized Report','6'=>'Detailed Report','7'=>'Group by Order','8'=>'Cancelations','9'=>'Group by Employee and Date');

		switch ($type) {
			case '1':
					$result=$this->db->query("SELECT count(orderslistid) totalordernumber ,convert(datetime,date) datee, sum(totalorder(a.orderslistid)) TotalOrder
						FROM orderslist a 
						left join mypostable b on a.mypostableid=b.postableid
						left join mystaffpos c on a.StaffCode=c.mystaffposid
						left join manage_users d on a.userid=d.user_id
						where a.active=2 and b.myposid=$posid and convert(datetime,date) between '$date1' and '$date2'
						group by convert(datetime,date)
						order  by convert(datetime,date) asc ")->result_array();
				$html='';
				if(count($result)>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">'.$types[$type].'</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th>Date</th>
															<th>Total Order #</th>
															<th>Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($result as  $value) {
						$i++;
						$total+=$value['TotalOrder'];
						$html.=' <tr  class="'.($i%2?'active':'success').'"> 
								<th scope="row">'.$i.' </th> 
								<td>'.date('m/d/Y',strtotime($value['datee'])).'</td>
								<td>'.number_format($value['totalordernumber'], 2, '.', ',').'</td>
								<td>'.number_format($value['TotalOrder'], 2, '.', ',').'</td>  
								 </tr>';


					}
					
					$html.='</tbody>
									</table>
									</div> 									
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total:'.number_format($total, 2, '.', ',').'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}

				echo json_encode(array('html'=>$html));
				
				break;
			case '2':
					$result=$this->db->query("SELECT count(orderslistid) totalordernumber ,convert(datetime,date) datee,sum( totalorder(a.orderslistid)) TotalOrder,concat(d.fname,' ',d.lname) Billedby
						FROM orderslist a 
						left join mypostable b on a.mypostableid=b.postableid
						left join mystaffpos c on a.StaffCode=c.mystaffposid
						left join manage_users d on a.userid=d.user_id
						where a.active=2 and b.myposid=$posid and convert(datetime,date) between '$date1' and '$date2'
						group by convert(datetime,date), concat(d.fname,' ',d.lname)
						order  by convert(datetime,date) asc ")->result_array();
				$html='';
				if(count($result)>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">'.$types[$type].'</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th>Date</th>
															<th>Billed By</th>
															<th>Total Order #</th>
															<th>Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($result as  $value) {
						$i++;
						$total+=$value['TotalOrder'];
						$html.=' <tr  class="'.($i%2?'active':'success').'"> 
								<th scope="row">'.$i.' </th> 
								<td>'.date('m/d/Y',strtotime($value['datee'])).'</td>
								<td>'.$value['Billedby'].'</td>
								<td>'.number_format($value['totalordernumber'], 2, '.', ',').'</td>
								<td>'.number_format($value['TotalOrder'], 2, '.', ',').'</td>  
								 </tr>';


					}
					
					$html.='</tbody>
									</table>
									</div> 									
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total:'.number_format($total, 2, '.', ',').'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}

				echo json_encode(array('html'=>$html));
				
				break;
			case '7':

				$result=$this->db->query("SELECT orderslistid ordernumber,a.datetime,b.description tablename,concat(c.firstname,' ', c.lastname) attended, totalorder(a.orderslistid) TotalOrder,concat(d.fname,' ',d.lname) Billedby
					FROM orderslist a 
					left join mypostable b on a.mypostableid=b.postableid
					left join mystaffpos c on a.StaffCode=c.mystaffposid
					left join manage_users d on a.userid=d.user_id
					where a.active=2 and b.myposid=$posid and convert(datetime,date) between '$date1' and '$date2'
					order  by convert(datetime,date) asc ")->result_array();
				$html='';
				if(count($result)>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">'.$types[$type].'</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th>Order Number</th>
															<th>Date Time</th>
															<th>Table Name</th>
															<th>Attended by</th>
															<th>Total Order</th>
															<th>Billed By</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($result as  $value) {
						$i++;
						$total+=$value['TotalOrder'];
						$html.=' <tr  class="'.($i%2?'active':'success').'"> 
								<th scope="row">'.$i.' </th> 
								<td>'.$value['ordernumber'].'</td> 
								<td>'.date('m/d/Y->h:m',strtotime($value['datetime'])).'</td>
								<td>'.$value['tablename'].'</td>
								<td>'.$value['attended'].'</td>
								<td>'.number_format($value['TotalOrder'], 2, '.', ',').'</td>
								<td>'.$value['Billedby'].'</td>
								 </tr>';


					}
					
					$html.='</tbody>
									</table>
									</div> 									
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total:'.number_format($total, 2, '.', ',').'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}

				echo json_encode(array('html'=>$html));
				break;

			case '8':

			$result=$this->db->query("SELECT orderslistid ordernumber,a.datetime,b.description tablename,concat(c.firstname,' ', c.lastname) attended, totalorder(a.orderslistid) TotalOrder,concat(d.fname,' ',d.lname) Billedby, reasoncancelled
				FROM orderslist a 
				left join mypostable b on a.mypostableid=b.postableid
				left join mystaffpos c on a.StaffCode=c.mystaffposid
				left join manage_users d on a.userid=d.user_id
				where a.active=0 and b.myposid=$posid and convert(datetime,date) between '$date1' and '$date2'
				order  by convert(datetime,date) asc ")->result_array();
			$html='';
			if(count($result)>0)
			{
				$html='<div><div style="float: right;" class="buttons-ui">
				            <a onclick="Export()" class="btn green">Export</a>
				        </div> <div class="clearfix"></div><center><h1><span class="label label-danger">'.$types[$type].'</span></h1></center></div>';
				$html.='<div class="graph">
						<div class="table-responsive">
								<div class="clearfix"></div>
								<table id="myTable" class="table table-bordered">
										<thead>
												<tr>	<th>#</th>
														<th>Order Number</th>
														<th>Date Time</th>
														<th>Table Name</th>
														<th>Attended by</th>
														<th>Total Order</th>
														<th>Billed By</th>
														<th>Comments</th>
												</tr>
																	 </thead>
										<tbody>';
				$i=0;
				$total=0;
				foreach ($result as  $value) {
					$i++;
					$total+=$value['TotalOrder'];
					$html.=' <tr  class="'.($i%2?'active':'success').'"> 
							<th scope="row">'.$i.' </th> 
							<td>'.$value['ordernumber'].'</td> 
							<td>'.date('m/d/Y->h:m',strtotime($value['datetime'])).'</td>
							<td>'.$value['tablename'].'</td>
							<td>'.$value['attended'].'</td>
							<td>'.number_format($value['TotalOrder'], 2, '.', ',').'</td>
							<td>'.$value['Billedby'].'</td>
							<td>'.$value['reasoncancelled'].'</td>
							 </tr>';


				}
				
				$html.='</tbody>
								</table>
								</div> 									
								</div>';
				$html.='<div><center><h1><strong class="label label-danger">Total:'.number_format($total, 2, '.', ',').'<strong></h1></center></div>';
			}
			else
			{
				$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
			}

			echo json_encode(array('html'=>$html));
			break;

				
			case '9':
				$result=$this->db->query("SELECT count(orderslistid) totalordernumber ,convert(datetime,date) datee,concat(c.firstname,' ', c.lastname) attended, sum(totalorder(a.orderslistid)) TotalOrder
						FROM orderslist a 
						left join mypostable b on a.mypostableid=b.postableid
						left join mystaffpos c on a.StaffCode=c.mystaffposid
						left join manage_users d on a.userid=d.user_id
						where a.active=2 and b.myposid=$posid and convert(datetime,date) between '$date1' and '$date2'
						group by convert(datetime,date),concat(c.firstname,' ', c.lastname)
						order  by convert(datetime,date) asc ")->result_array();
				$html='';
				if(count($result)>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">'.$types[$type].'</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th>Date</th>
															<th>Attended By</th>
															<th>Total Order #</th>
															<th>Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($result as  $value) {
						$i++;
						$total+=$value['TotalOrder'];
						$html.=' <tr  class="'.($i%2?'active':'success').'"> 
								<th scope="row">'.$i.' </th> 
								<td>'.date('m/d/Y',strtotime($value['datee'])).'</td>
								<td>'.$value['attended'].'</td>
								<td>'.number_format($value['totalordernumber'], 2, '.', ',').'</td>
								<td>'.number_format($value['TotalOrder'], 2, '.', ',').'</td>  
								 </tr>';


					}
					
					$html.='</tbody>
									</table>
									</div> 									
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total:'.number_format($total, 2, '.', ',').'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}

				echo json_encode(array('html'=>$html));
				
				break;
			default:
				$result='';
				echo json_encode(array('html'=>'<center><h1><span class="label label-danger">No Record Found</span></h1></center>'));
				break;
		}
   
	}
	function viewProducts($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Products';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllCategories']=$this->db->query("SELECT * FROM itemcategory  where  posId=$posid ")->result_array();
		$data['AllUnits']=$this->db->query("SELECT * FROM units  order by name ")->result_array();
		$data['ALLProducts']=$this->db->query("SELECT a.*, b.name Categoryname,  precioActual (a.itemPosId ,1) price, c.name unitname
												FROM itempos a
												left join itemcategory b on a.itemcategoryID = b.itemcategoryid
												left join units c on a.unitid = c.unitid
												where b.posid=$posid ")->result_array();
		$this->views('Restaurant/products',$data);
	}
	function saveMeasurement()
	{	
		$data['name']=getpost('Mname');
		$data['Symbol']=getpost('Symbol');
		$data['Equivalente']=getpost('Equivalente');

		$result['success']=false;
		$result['message']='Something went Wrong';

		if(insert_data('units',$data))
		{
			$result['success']=true;
		}
		echo json_encode($result);
	}
	function viewLocalConfig($hotelid,$posid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Configurations';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllSchedule']=$this->db->query("SELECT * from myposschedule where posid=$posid order by daysofweek ")->result_array();
		$this->views('Restaurant/localconfig',$data);
	}
	function viewTurns($hotelid,$posid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Turns';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllTurns']=$this->db->query("select * from posturns where posid=$posid ")->result_array();
		$this->views('Restaurant/turns',$data);
	}

	function saveTurn()
	{
		$result['success']=false;
		$result['message']='Something went wrong';

		$data['posid']=getpost('posid');
		$data['name']=getpost('name');
		$data['active']=1;

		if(insert_data('posturns',$data))
		{
			$result['success']=true;
		}

		echo json_encode($result);
	}
	function updateTurn()
	{
		$result['success']=false;
		$result['message']='Something went wrong';

		$data['name']=getpost('nameU');
		$data['active']=(isset($_POST['statusid'])?1:0);

		if(update_data('posturns',$data,array("posturnid"=>getpost('turnid'))))
		{
			$result['success']=true;
		}

		echo json_encode($result);
	}

	function viewTurnDetails($hotelid,$posid,$turnid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$turnid=insep_decode($turnid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Turn Details';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllDetails']=$this->db->query("SELECT a.*, case when posturndetailid is not null then 1 else 0 end  selected, c.name Category
		FROM 
		itempos a
		left join posturndetails b on a.itemPosId=b.itemid and isitem=1 and b.posturnid=$turnid
		left join itemcategory c on a.itemcategoryid = c.itemcategoryID
		where c.posid=$posid  order by c.name, a.name ")->result_array();
		$data['turninfo']=get_data('posturns',array('posturnid'=>$turnid))->row_array();
		$this->views('Restaurant/turndetails',$data);
	}
	function saveTurnDetails()
	{
		$turnid=$_POST['turnid'];
		$isitem=$_POST['isitem'];
		if(isset($_POST['productid']))
		{
			$scriptsql='';
			$scriptsql .=" insert into posturndetails (posturnid, itemid, isitem, active) values ";
			$scripv='';
			foreach ($_POST['productid'] as  $value) {
				
				$scripv .=($scripv!=''?",($turnid,$value,$isitem,1)":"($turnid,$value,$isitem,1)");
			}

			if(strlen($scripv)>0)
			{	
				$this->db->query("delete from posturndetails where posturnid=$turnid");
				$this->db->query($scriptsql.$scripv);

				echo json_encode(array("success"=>true,"message"=>"Something went wrong"));
				return;
			}
			else
			{
				echo json_encode(array("success"=>false,"message"=>"Something went wrong"));
				return;
			}
		}
		else
		{
			echo json_encode(array("success"=>false,"message"=>"You must select a product to Continue"));
			return;
		}




	}

	function SaveLocalConfig()
	{
		
		$data['posid']=$_POST['posid'];
		$data['daysofweek']=$_POST['day'];
		$data['startdate']=date('H:00:00',strtotime($_POST['hourtime1']));
		$data['enddate']=date('H:00:00',strtotime($_POST['hourtime2']));

		$result=array('success'=>false,'message'=>'Something went Wrong');
		if(insert_data('myposschedule',$data))
		{
			$result['success']=true;
		}

		echo json_encode($result);

	}
	function viewEmployeeschedule($hotelid,$posid,$employeeid=1)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Configurations';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllSchedule']=$this->db->query("SELECT * from staffschedule where staffid=$employeeid order by daysofweek ")->result_array();
		$data['staffinfo']=get_data('mystaffpos',array('mystaffposid'=>$employeeid))->row_array();
		$this->views('Restaurant/employeeschedule',$data);
	}
	function SaveEmployeeSchedule()
	{
		
		$data['staffid']=$_POST['staffid'];
		$data['daysofweek']=$_POST['day'];
		$data['startdate']=date('H:00:00',strtotime($_POST['hourtime1']));
		$data['enddate']=date('H:00:00',strtotime($_POST['hourtime2']));

		$result=array('success'=>false,'message'=>'Something went Wrong');
		if(insert_data('staffschedule',$data))
		{
			$result['success']=true;
		}

		echo json_encode($result);

	}
	function viewRecipes($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Recipes';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLProducts']=$this->db->query("SELECT a.*, b.name Categoryname,precioActual (a.itemPosId ,1)  price, c.name unitname
												FROM itempos a
												left join itemcategory b on a.itemcategoryID = b.itemcategoryid
												left join units c on a.unitid = c.unitid
												where b.posid=$posid ")->result_array();
		$data['ALLRecipes']=$this->db->query("SELECT a.*,  precioActual (a.recipeid ,0)  price
												FROM Recipes a
												where a.posid=$posid and a.active=1 ")->result_array();

		$this->views('Restaurant/recipes',$data);


		#detalle de recetas
		/*select a.name, b.quantity,c.name, (select price from itemprice  where itemid=b.itemid ORDER BY `datetime` DESC LIMIT 1)  unitary, 
		b.quantity*(select price from itemprice  where itemid=b.itemid ORDER BY `datetime` DESC LIMIT 1) importe,
		d.name UnitName
		from recipes a
		left join recipedetails b on a.recipeid =b.recipeid
		left join itempos c on b.itemid=c.itemposid
		left join units d on c.unitid =d.unitid*/
	}
	function viewEmployees($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Employees';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllStaffType']=$this->db->query("SELECT * FROM stafftype where hotelid=$hotelid ")->result_array();
		$data['AllStaff']=$this->db->query("SELECT a.*, b.name stafftypename, concat(firstname,' ' , lastname) fullname 
											FROM mystaffpos a
											left join stafftype b on a.stafftypeid = b.stafftypeid  
											where  a.hotelid=$hotelid ")->result_array();
		$this->views('Restaurant/employes',$data);
	}
	function viewSuppliers($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Suppliers';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllSuppliers']=$this->db->query("SELECT * FROM suppliers  where  myposid=$posid ")->result_array();
		$this->views('Restaurant/suppliers',$data);
	}
	function viewtable($hotelid,$posid,$tableid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$tableid=insep_decode($tableid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Table';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['TableInfo']=$this->db->query("SELECT * FROM mypostable  where postableid =$tableid ")->row_array();
		$data['payment']=$this->reservation_model->payment();
		$data['Currencies']=$this->db->query("SELECT * FROM `currency` ORDER BY `currency`.`currency_code` ASC ")->result_array();
		$data['currency']='USD';
		$data['OrderInfo']=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty, case when b.isitem=1 then c.name else d.name end  itemname,ifnull((SELECT  price FROM itemprice WHERE ITEMID= b.itemid and isitem = case when b.isitem=1 then 1 else 0 end AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) price,
            b.isitem
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=$tableid  and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();

		$data['StaffInfo']=$this->db->query("SELECT a.*, b.name occupation
			FROM mystaffpos a
			left join stafftype b on a.stafftypeid = b.stafftypeid
			where a.hotelid =$hotelid and a.active=1 and isWorking(a.mystaffposid)>0")->result_array();

		$data['waiter']=(count($data['OrderInfo'])==0 || $data['OrderInfo'][0]['StaffCode']==0 ?'':$this->db->query("select concat(firstname,' ',lastname) name from mystaffpos where mystaffposid =".$data['OrderInfo'][0]['StaffCode'])->row()->name);

		$data['Categories']=$this->db->query("select distinct a.* 
							from itemcategory a
							left join itempos b on a.itemcategoryID=b.itemcategoryid
							 
							left join posturndetails c on b.itemPosId= c.itemid
							left join posturnusers d on c.posturnid=d.posturnuserid
							
							where
							 d.posid=$posid and d.userid =".user_id() )->result_array();
		$data['Recipes']=$this->db->query("SELECT * from Recipes where posid= $posid")->result_array();
 
		
		$this->views('Restaurant/viewtable',$data);
	}
	function totaldueorder()
	{	$tableid=getpost('tableid');
		$totaldiscount=getpost('discount');
		$Order=$this->db->query("select ordersListid
            from orderslist a 
            where a.mypostableid=$tableid  and a.active =1 limit 1")->row()->ordersListid;
		$orderinfo=$this->db->query("select  ifnull(totalorder($Order),0)-(ifnull(totalpaidorder($Order),0)+$totaldiscount) totaldue")->row_array();
		echo json_encode($orderinfo);
	}
	function PaymentApplication()
	{
	
		switch (getpost('paymentTypeId')) {

			case '1':
			$data['paymenttypeid']=getpost('paymentTypeId');
			$data['providerid']=getpost('providerid');
			$data['ccholder']=getpost('ccholder');
			$data['ccnumberlast']=getpost('ccnumber');
			$data['amount']=getpost('amountdue');
			$data['Description']=getpost('Description');
			$data['currency']=getpost('currency');
			$data['discount']=getpost('discountP');
			$data['userid']=user_id();
			$tableid=getpost('tableid');

			$Order=$this->db->query("select ordersListid
            from orderslist a 
            where a.mypostableid=$tableid  and a.active =1 limit 1")->row()->ordersListid;
			$data['ordenid']=$Order;
			if(insert_data("ordenpayments",$data))
			{
				$orderinfo=$this->db->query("select totalpaidorder($Order) totalpaid, totalorder($Order) totalorder")->row_array();

				if($orderinfo["totalpaid"]>=$orderinfo["totalorder"])
				{
					update_data("orderslist",array("active"=>2),array("ordersListid"=>$Order));
					echo json_encode(array("success"=>true,"payment"=>"Complete"));
				}
				else
				{
					echo json_encode(array("success"=>true,"payment"=>"Partial","totaldue"=>($orderinfo["totalorder"]-$orderinfo["totalpaid"])));
				}
			}
			else
			{
				echo json_encode(array("success"=>false,"payment"=>"Something went Wrong"));
			}


				break;

			case '2':
				die;
				if ($_POST['providerid']==1) {
					require_once(APPPATH.'controllers/Stripe_payment.php');
					$stripe = new Stripe_payment();

					echo $stripe->checkout();

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
	function viewTask($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Task';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLTask']=$this->db->query("select a.* , concat(firstname,' ', lastname) staffname
			from Task a
			left join  mystaffpos b on a.staffid=mystaffposid
			where a.hotelid =$hotelid ")->result_array();

		$data['StaffInfo']=$this->db->query("SELECT a.*, b.name occupation
			FROM mystaffpos a
			left join stafftype b on a.stafftypeid = b.stafftypeid
			where a.hotelid =$hotelid ")->result_array();

	
		
		$this->views('Restaurant/task',$data);
	}
	function viewSellGiftCard($hotelid,$posid)
	{
		$this->is_login();
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$userid=user_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Sell Gift Card';
    	$user_details = get_data(TBL_USERS,array('user_id'=>$userid))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllGiftCard']=$this->db->query("select * from giftcard where hotelid =$hotelid and userid=$userid")->result_array();
		
		$this->views('Restaurant/giftcard',$data);
	}
	function viewAdminGiftCard($hotelid,$posid)
	{
		$this->is_login();
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$userid=user_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Admin Gift Card';
    	$user_details = get_data(TBL_USERS,array('user_id'=>$userid))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllGiftCard']=$this->db->query("select *,giftcardamountused(giftcardid) amountused from giftcard where hotelid =$hotelid order by giftcardnumber desc")->result_array();
		
		$this->views('Restaurant/admingiftcard',$data);
	}

	function viewAdminStation($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Stations';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLStations']=$this->db->query("select a.* , concat(firstname,' ', lastname) supervisor
			from stations a
			left join  mystaffpos b on a.supervisorid=b.mystaffposid
			where a.posid =$posid ")->result_array();

		$data['StaffInfo']=$this->db->query("SELECT a.*, b.name occupation
			FROM mystaffpos a
			left join stafftype b on a.stafftypeid = b.stafftypeid
			where a.hotelid =$hotelid ")->result_array();


		$data['AllTable']=$this->db->query("SELECT * FROM mypostable  where  myposId=$posid ")->result_array();

	
		$this->views('Restaurant/adminstation',$data);
	}
	function viewBillingConfiguration($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Table';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLTask']=$this->db->query("select a.* , concat(firstname,' ', lastname) staffname
			from Task a
			left join  mystaffpos b on a.staffid=mystaffposid
			where a.hotelid =$hotelid ")->result_array();

		$data['StaffInfo']=$this->db->query("SELECT a.*, b.name occupation
			FROM mystaffpos a
			left join stafftype b on a.stafftypeid = b.stafftypeid
			where a.hotelid =$hotelid ")->result_array();

	
		
		$this->views('Restaurant/giftcard',$data);
	}
	function viewInventory($hotelid,$posid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Inventory';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllInventory']=$this->db->query("SELECT a.*, b.name Categoryname,  precioActual (a.itemPosId ,1) price, c.name unitname,existenciaProducto (a.itemposid) existencia
												FROM itempos a
												left join itemcategory b on a.itemcategoryID = b.itemcategoryid
												left join units c on a.unitid = c.unitid
												where b.posid=$posid")->result_array();


		$this->views('Restaurant/inventory',$data);
	}
	function saveStock()
	{
		$result['success']=false;
		$result['message']="Something went wrong";

		$kardex['itemid']=$_POST['productids'];
		$kardex['orderid']=0;
		$kardex['qty']=$_POST['stocks'];
		$kardex['isitem']=1;
		$kardex['type']=1;
		if(insert_data('kardex',$kardex))
		{
			$result['success']=true;
		}
		echo json_encode($result);
	}
	function showstock()
	{

		$alllist=$this->db->query("select * from kardex where itemid = ".$_POST['id']." order by datetimemove desc")->result_array();
		$html='';
			$html.= '<div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
              
                    <table id="myTable2" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                            	<th>#</th>
                                <th>Date</th>
                                <th>Transation Type</th>
                                <th>Qty</th>

                            </tr>
                        </thead>
                        <tbody>';
			
							
					
                            if (count($alllist)>0) {
                            	$i=0;
                                foreach ($alllist as  $value) {
                                	$i++;
                                    $html.=' <tr scope="row"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.'</span> </th>
										<td>'.date('m/d/Y',strtotime($value['datetimemove'])).' </td>
										<td>'.($value['type']==0?'Invoiced':($value['type']==2?'Remove Item':'Increment Stock')).' </td>
										<td>'.$value['QTY'].' </td>
                                      </tr>  ';

                                }
                                 $html.='</tbody> </table> </div></div></div>';
                                 echo $html;
                            }
                            else
                            {
                            	$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
                            	echo $html;
                            }
                      
                 
              
	
	}
	function viewReservation($hotelid,$posid)
	{
		$this->is_login();
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$userid=user_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Reservations List';
    	$user_details = get_data(TBL_USERS,array('user_id'=>$userid))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLReservation']=$this->db->query("select a.*, b.description tablename,case when Roomid is null then 'Out House' else 'In House' end marketingp from mypostablereservation a
			left join mypostable b on a.mypostableid=b.postableid
            left join mypos c on a.mypostableid =c.myposId
			where b.myposId=$posid
            order by datetimereservation desc,starttime1 desc")->result_array();

		$data['AllTable']=$this->db->query("SELECT * FROM mypostable  where  myposId=$posid ")->result_array();
		$this->views('Restaurant/reservation',$data);
	}

	function viewCalendar($hotelid,$posid)
	{
		$this->is_login();
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$userid=user_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Calendar';
    	$user_details = get_data(TBL_USERS,array('user_id'=>$userid))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();

		$data['AllTable']=$this->db->query("SELECT * FROM mypostable  where  myposId=$posid ")->result_array();
		$this->views('Restaurant/calendar',$data);
	}



	function allitem($catid='')
	{
		$html='';
		if($catid=='')
		{
			$catid=$_POST['catid'];
			$posid=$_POST['posid'];
		}

		$allitem=$this->db->query("	select c.* from
						posturnusers a 
						left join posturndetails  b on a.turnid=b.posturnid or a.turnid=0
						left join  itempos c on b.itemid=c.itemPosId 
						where
						c.itemcategoryid=$catid and  a.posid=$posid and c.active=1 and a.userid=".user_id())->result_array();

		if (count($allitem)==0) {

			$html= '<h4 align="center"> This category does not have assigned items </4>';
		}
		else
		{
			foreach ($allitem as  $value) {

				$html .= '<div  class="col-md-2 div-img">
                                    <a onclick="additem('."this.id".',1);" id="'.$value['itemPosId'].'"><img class="img"  src="'.$value['photo'].'"></a>
                                    <h4> '.$value['name'].' </h4>

                                </div>';
			}
		}

		echo $html.' <div class="clearfix"></div>';
	}
	function allRecipe($posid='')
	{
		$html='';
		if($posid=='')
		{
			$posid=$_POST['posid'];
		}

		$allRec=$this->db->query("SELECT * from Recipes where posid=$posid  and active=1")->result_array();


			foreach ($allRec as  $value) {

				$html .= '<div  class="col-md-4 div-img">
                                    <a onclick="additem('."this.id".',0);" id="'.$value['recipeid'].'"><img class="img"  src="'.$value['photo'].'"></a>
                                    <h4> '.$value['name'].' </h4>

                                </div> <br>';
			}

		echo $html.' <div class="clearfix"></div>';
	}
	function additem()
	{

		$itemid=$_POST['itemid'];
		$tableid=$_POST['tableid'];
		$isitem=$_POST['isitem'];
		$html='';
		$ordenid='';
		$data= array();

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1")->row_array();

		if(count($OrderInfo)>0)
		{
			$ordenid=$OrderInfo['ordersListid'];
			$info['ordersListid']=$ordenid;
			$info['itemid']=$itemid;
			$info['qty']=1;
			$info['isitem']=$isitem;

			if(insert_data('orderlistdetails',$info))
			{
				if($info['isitem']==1)
				{
					$kardex['itemid']=$info['itemid'];
					$kardex['orderid']=$info['ordersListid'];
					$kardex['qty']=1;
					$kardex['isitem']=$info['isitem'];
					$kardex['type']=0;
					insert_data('kardex',$kardex);
				}
				else
				{
					$recipe=$this->db->query("select b.itemid,b.quantity 
											from Recipes a
											left join recipedetails b on a.recipeid=b.recipeid
											where a.recipeid= ".$info['itemid'])->result_array();

					foreach ($recipe as  $value) {

						$kardex['itemid']=$value['itemid'];
						$kardex['orderid']=$ordenid;
						$kardex['qty']=$value['quantity'];
						$kardex['isitem']=1;
						$kardex['type']=0;
						insert_data('kardex',$kardex);
					}
				}
				
			}

		}
		else
		{
			$main['mypostableid']= $tableid;
			$main['StaffCode']= '';
			$main['active']= 1;
			$main['userid']=user_id();
			insert_data('orderslist',$main);

			$info['ordersListid']=$this->db->insert_id();
			$info['itemid']=$itemid;
			$info['qty']=1;
			$info['isitem']=$isitem;

			if(insert_data('orderlistdetails',$info))
			{
				if($info['isitem']==1)
				{
					$kardex['itemid']=$info['itemid'];
					$kardex['orderid']=$info['ordersListid'];
					$kardex['qty']=1;
					$kardex['isitem']=$info['isitem'];
					$kardex['type']=0;
					insert_data('kardex',$kardex);
				}
				else
				{
					$recipe=$this->db->query("select b.itemid,b.quantity 
											from Recipes a
											left join recipedetails b on a.recipeid=b.recipeid
											where a.recipeid= ".$info['itemid'])->result_array();

					foreach ($recipe as  $value) {

						$kardex['itemid']=$value['itemid'];
						$kardex['orderid']=$ordenid;
						$kardex['qty']=$value['quantity'];
						$kardex['isitem']=1;
						$kardex['type']=0;
						insert_data('kardex',$kardex);
					}
				}

			}




		}

		$OrderInfo=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty, case when b.isitem=1 then c.name else d.name end  itemname,ifnull((SELECT  price FROM itemprice WHERE ITEMID= b.itemid and isitem = case when b.isitem=1 then 1 else 0 end AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) price,
            b.isitem
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=$tableid  and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();




	     if (count($OrderInfo)>0) {


	    	$datahtml=$this->ordenhtml($OrderInfo);

		}

		$data['total']='<h2><strong>Total Due:</strong>'.number_format($datahtml['grandtotal'], 2, '.', '').'</h2>' ;
		$data['html']=$datahtml['html'];
		$data['success']=true;
		echo json_encode($data);

	}
	function deleteitem()
	{
		$this->is_login();
		$itemid=$_POST['itemid'];
		$tableid=$_POST['tableid'];
		$isitem=$_POST['isitem'];
		$html='';
		$ordenid='';
		$data= array();

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if(count($OrderInfo)>0)
		{
			$ordenid=$OrderInfo['ordersListid'];
			$info['ordersListid']=$ordenid;
			$info['itemid']=$itemid;
			$info['qty']=1;
			$this->db->query("delete  from orderlistdetails where itemid=$itemid and ordersListid=$ordenid and isitem = $isitem limit 1");

			if($isitem=='1')
			{
				
				$kardex['itemid']=$itemid;
				$kardex['orderid']=$ordenid;
				$kardex['qty']=1;
				$kardex['isitem']=1;
				$kardex['type']=2;//Delete item de Orden
				insert_data('kardex',$kardex);
			}
			else
			{
				$recipe=$this->db->query("select b.itemid,b.quantity 
											from Recipes a
											left join recipedetails b on a.recipeid=b.recipeid
											where a.recipeid= ".$itemid)->result_array();

					foreach ($recipe as  $value) {

						$kardex['itemid']=$value['itemid'];
						$kardex['orderid']=$ordenid;
						$kardex['qty']=$value['quantity'];
						$kardex['isitem']=1;
						$kardex['type']=2;
						insert_data('kardex',$kardex);
					}
			}

				$OrderInfo=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty, case when b.isitem=1 then c.name else d.name end  itemname,ifnull((SELECT  price FROM itemprice WHERE ITEMID= b.itemid and isitem = case when b.isitem=1 then 1 else 0 end AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) price,
            b.isitem
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=$tableid  and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();

		}


	     if (count($OrderInfo)>0 && strlen($OrderInfo[0]['itemid'])>0 ) {

	    	$datahtml=$this->ordenhtml($OrderInfo);
		}


		$data['total']='<h2><strong>Total Due:</strong>'.number_format((isset($datahtml['grandtotal'])?$datahtml['grandtotal']:'0'), 2, '.', '').'</h2>' ;
		$data['html']=(isset($datahtml['html'])?$datahtml['html']:'');
		$data['success']=true;
		echo json_encode($data);

	}
	function exists_staff()
	{

		$tableid=$_POST['tableid'];

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (!isset($OrderInfo['StaffCode'])) {

			$result['status']='-1';

		}
		else
		{
			if(strlen(isset($OrderInfo['StaffCode']))>0)
			{
				$result['status']='1';
			}
			else
			{
				$result['status']='0';
			}
		}

		echo json_encode($result);

	}
	function addstaff()
	{
		$tableid=$_POST['tableid'];
		$staffid=$_POST['staffid'];
		$orderid='';
		$oldstaff='';

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (!isset($OrderInfo['StaffCode'])) {

			$main['mypostableid']= $tableid;
			$main['StaffCode']= '';
			$main['active']= 1;
			$main['userid']= user_id();
			insert_data('orderslist',$main);
			$oldstaff='';
			$orderid=$this->db->insert_id();
		}
		else
		{
			$orderid=$OrderInfo['ordersListid'];
			$oldstaff=$OrderInfo['StaffCode'];
		}

			$update['StaffCode']=$staffid;

			$staff=get_data('mystaffpos',array('mystaffposid'=>$staffid))->row_array();

			$result['staff']='';
			if(count($staff)>0)
			{
				$result['staff']='<h3><strong>Waiter/s:</strong>'.$staff['firstname'].' '.$staff['lastname'].'</h3>';
			}
			else
			{
				$result['staff']='<h3><strong>Waiter/s:</strong> Not Assigned</h3>';
			}

			update_data('orderslist',$update,array('ordersListid'=>$orderid));

			echo json_encode($result);

	}

	function cancelorden()
	{


		$tableid=$_POST['tableid'];
		$reason=$_POST['reason'];
		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (isset($OrderInfo['ordersListid'])) {
			$orderid=$OrderInfo['ordersListid'];
		}
		else {
			$data['result']=false;
			echo json_encode($data);
			return;
		}

		$OrdenDetail=$this->db->query("SELECT b.itemid,sum(b.qty) qty, b.isitem
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=1  and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();

		if(count($OrdenDetail)>0 && isset($OrdenDetail['itemid']))
		{
			foreach ($OrdenDetail as  $value) {

				if ($value['isitem']==1) {

						$kardex['itemid']=$value['itemid'];
						$kardex['orderid']=$orderid;
						$kardex['qty']=$value['qty'];
						$kardex['isitem']=1;
						$kardex['type']=2;
						insert_data('kardex',$kardex);
					
				}
				else
				{
					$recipe=$this->db->query("select b.itemid,b.quantity 
											from Recipes a
											left join recipedetails b on a.recipeid=b.recipeid
											where a.recipeid= ".$value['itemid'])->result_array();

					foreach ($recipe as  $value2) {
						$kardex['itemid']=$value2['itemid'];
						$kardex['orderid']=$orderid;
						$kardex['qty']=$value['qty']*$value2['quantity'];
						$kardex['isitem']=0;
						$kardex['type']=2;
						insert_data('kardex',$kardex);
					}
				}
				# code...
				# itemid, qty, isitem

			}
		}

		$update['active']=0;
		$update['reasoncancelled']=$reason;


		if(update_data('orderslist',$update,array('ordersListid'=>$orderid)))
		{
			$data['result']=true;
		}
		else {
			$data['result']=false;
		}
		echo json_encode($data);
		return;
	}
	function availabletable()
	{
		$myposid=$_POST['posid'];

		$available=$this->db->query("select *
									from mypostable a
									where
									(select count(*) from orderslist where mypostableid= a.postableid and active= 1 )=0
									and myposid=$myposid")->result_array();


		if (count($available)==0) {
			$data['html']='<h1 align="center">No Available Table</h1>';
			$data['result']=false;
			echo json_encode($data);
			return;
		}

		$html='';
		$html.='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Table Name</th>
												<th>Capacity</th>
												<th>Change</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($available as  $value) {
						$i++;
						$html.=' <tr id="table'.$value['postableid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['description'].'  </td> <td>'.$value['qtyPerson'].'</td>
								<td  align="center"><a id="'.$value['postableid'].'" onclick="changetable('."this.id".');">
								 <i class="fa fa-check-circle fa-2x"></i></a> </td> </tr>';


					}
					$html.='</tbody>
									</table>
									</div> </div>';
					$data['html']=$html;
					$data['result']=true;
					echo json_encode($data);
	}

	function changetable()
	{
			$oldtable=$_POST['oldid'];
			$newtable=$_POST['newid'];
			$available=$this->db->query("SELECT * from orderslist  where mypostableid= $newtable and active =1 limit 1 ")->result_array();

			if (count($available)>0) {
				$data['result']=1;
				echo json_encode($data);
			}
			$orderid=$this->db->query("SELECT * from orderslist  where mypostableid= $oldtable and active =1 limit 1 ")->row_array()['ordersListid'];
			$update['mypostableid']=$newtable;
			if(update_data('orderslist',$update,array('ordersListid'=>$orderid)))
			{
				$data['result']=0;
				$data['url']=site_url('pos/viewtable/'.secure(hotel_id()).'/'.insep_encode($_POST['posid']).'/'.insep_encode($newtable));
				echo json_encode($data);
			}
	}
	function savePOS()
	{
			$data['hotelId']=hotel_id();
			$data['userid']=user_id();
			$data['active']=1;
			$data['postypeID']=$_POST['typeposid'];
			$data['description']=$_POST['posname'];
			if(insert_data('mypos',$data))
			{
				echo "0";
			}
			else {
				echo "1";
			}
	}
	function saveTable()
	{
			$data['myposid']=$_POST['postid'];
			$data['active']=1;
			$data['qtyPerson']=$_POST['Capacity'];
			$data['description']=$_POST['tablename'];
			$data['averagetimeuse']=$_POST['usetime'];

			if(insert_data('mypostable',$data))
			{
				echo "0";
			}
			else {
				echo "1";
			}

	}
	function updateTable()
	{
			$data['myposid']=$_POST['postidup'];
			$data['active']=1;
			$data['qtyPerson']=$_POST['Capacityup'];
			$data['description']=$_POST['tablenameup'];
			$data['averagetimeuse']=$_POST['usetimeup'];
			
			if(update_data('mypostable',$data,array('postableid' =>$_POST['tableidup'] )))
			{
				echo "0";
			}
			else {
				echo "1";
			}

	}
	function LoadImage()
	{
		echo (var_dump($_FILES));
		echo (var_dump($_POST));
		return;
		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      echo "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      echo "Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        echo "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        echo "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$nombre;
		        move_uploaded_file($ruta_provisional, $src);
		        echo '<img id="pathimagen" src="/'.$src.'">';
		    }
		}
	}
	function saveCategory()
	{		
		$result["result"]='';

		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posid'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
				$data['posid']=$_POST['posid'];
				$data['name']=$_POST['categoryname'];
				$data['active']=1;

				if(insert_data('itemcategory',$data))
				{
					$result["result"]= "0";
				}
				else 
				{
					$result["result"]= "1";
				}

		    }

		    echo json_encode($result);

		}
	
	}
	function updateCategory()
	{		
		$result["result"]='';
		if (strlen($_FILES["photoup"]["name"])>0)
		{

		    $file = $_FILES["photoup"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]="Error, el archivo no es una imagen"; 
		     
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]= "Error, el tamaño máximo permitido es un 1MB";
		        
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]="Error la anchura y la altura maxima permitida es 500px";
		         
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]="Error la anchura y la altura mínima permitida es 60px";
		          return;
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posidup'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
		    }
		}

				if(strlen( $result["result"])==0)
				{
					$data['posid']=$_POST['posidup'];
					$data['name']=$_POST['categorynameup'];
					update_data('itemcategory',$data,array('itemcategoryID' =>$_POST['itemcategoryidup'] ));
					$result["result"]=$result["result"]="0";
				}
			
				echo json_encode($result);
			
	}
	function saveProduct()
	{		
		$result["result"]='';

		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Product/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['Categoryid'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
				$data['itemcategoryid']=$_POST['Categoryid'];
				$data['name']=$_POST['productname'];
				$data['type']=$_POST['type'];
				$data['code']='item';
				$data['brand']=$_POST['brand'];;
				$data['model']=$_POST['model'];;
				$data['stock']=$_POST['stock'];;
				$data['unitid']=$_POST['unitid'];
				$data['description']=$_POST['description'];
				$data['active']=1;

				if(insert_data('itempos',$data))
				{
					
					
					$datal['itemid']=$this->db->insert_id();
					$datal['price']=$_POST['pricen'];
					$datal['isitem']=1;
					insert_data('itemprice',$datal);

					$result["result"]= "0";
				}
				else 
				{
					$result["result"]= "1";
				}

		    }

		    echo json_encode($result);

		}
	
	}
	function updateProduct()
	{		
		$result["result"]='';


		if (isset($_FILES["Imageup"]["name"]) && $_FILES["Imageup"]["size"] >0 )
		{

		    $file = $_FILES["Imageup"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Product/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]="Error, el archivo no es una imagen"; 
		     
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]= "Error, el tamaño máximo permitido es un 1MB";
		        
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]="Error la anchura y la altura maxima permitida es 500px";
		         
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]="Error la anchura y la altura mínima permitida es 60px";
		          return;
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posidup'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
		    }
		}

		if(strlen( $result["result"])==0)
		{
			$data['itemcategoryid']=(int)$_POST['Categoryidup'];
			$data['type']=$_POST['typeup'];
			$data['name']=$_POST['productnameup'];
			$data['brand']=$_POST['brandup'];
			$data['model']=$_POST['modelup'];
			$data['stock']=$_POST['stockup'];
			$data['unitid']=$_POST['unitidup'];
			$data['description']=$_POST['descriptionup'];
			update_data('itempos',$data,array('itemPosId' =>$_POST['itemPosId'] ));
			$result["result"]="0";
		}


				echo json_encode($result);
			
	}
	function saveSupplier()
	{		
		$result["result"]='';

	    $data['companyname']=$_POST['cname'];
		$data['representativename']=$_POST['rname'];
		$data['address']=$_POST['address'];
		$data['phone']=$_POST['phone'];
		$data['cellphone']=$_POST['cphone'];
		$data['email']=$_POST['email'];
		$data['active']=1;
		$data['myposid']=$_POST['posid'];


		if(insert_data('suppliers',$data))
		{
			$result["result"]= "0";
		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

	}
	function updateSupplier()
	{		
		$result["result"]='';

	    $data['companyname']=$_POST['cnameup'];
		$data['representativename']=$_POST['rnameup'];
		$data['address']=$_POST['addressup'];
		$data['phone']=$_POST['phoneup'];
		$data['cellphone']=$_POST['cphoneup'];
		$data['email']=$_POST['emailup'];
		


		if(update_data('suppliers',$data,array('supplierID' =>$_POST['supplierID'])))
		{
			$result["result"]= "0";
		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

		# supplierID, myposid, companyname, representativename, address, phone, cellphone, email, active

	}
	function saveEmployee()
	{		
		$result["result"]='';

		
		if (isset($_FILES["Image"]) && strlen($_FILES["Image"]["name"])>0)
		{

		    $file = $_FILES["Image"];
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Employee/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	

		        $src = $carpeta.$_POST['posid'].$_POST['name'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;

		    }

		}


			if( $result["result"]=="")
			{
				$data['firstname']=$_POST['name'];
				$data['lastname']=$_POST['lastname'];
				$data['gender']=$_POST['gender'];
				$data['stafftypeid']=$_POST['staffType'];
				$data['hotelid']=hotel_id();
				$data['active']=1;
				$data['email']=$_POST['email'];
			    if(insert_data('mystaffpos',$data))
				{
					$result["result"]="0";
				}
				else 
				{
					$result["result"]= "1";
				}
			}
			
		    echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}
	function updateEmployee()
	{		
		$result["result"]='';

		
		if (isset($_FILES["Imageup"]) && strlen($_FILES["Imageup"]["name"])>0)
		{

		    $file = $_FILES["Image"];
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Employee/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	

		        $src = $carpeta.$_POST['posid'].$_POST['name'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;

		    }

		}


			if( $result["result"]=="")
			{
				$data['firstname']=$_POST['nameup'];
				$data['lastname']=$_POST['lastnameup'];
				$data['gender']=$_POST['genderup'];
				$data['stafftypeid']=$_POST['staffTypeup'];
				$data['active']=(isset($_POST['statusid'])?1:0);
				$data['email']=$_POST['emailup'];
			    if(update_data('mystaffpos',$data,array('mystaffposid' =>$_POST['mystaffposid'])))
				{
					$result["result"]="0";
				}
				else 
				{
					$result["result"]= "1";
				}
			}
			
		    echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}
	function pricehistory()
	{
		$itemPosId=$_POST['itemPosId'];
		$isitem=$_POST['type'];

		$available=$this->db->query("select * from itemprice where itemid = $itemPosId and isitem=$isitem order by datetime desc ")->result_array();

		if (count($available)==0) {
			$data['html']='<h1 align="center">No Available Price</h1>';
			$data['result']=false;
			echo json_encode($data);
			return;
		}

		$html='';
		$html.='<br>
				<div  class="graph">
				<div style="height: 200px"  class="table-responsive">
						
						<table class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Price </th>
												<th>Date Time</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($available as  $value) {
						$i++;
						$html.=' <tr class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['price'].'  </td> <td>'.date("F j, Y, g:i a",strtotime($value['datetime'])).'</td>  </tr>';


					}
					$html.='</tbody>
									</table>
									</div> 
									</div>';
					$data['html']=$html;
					$data['result']=true;
					echo json_encode($data);
	}
	function savePrice()
	{		
		$result["result"]='';

		$data['itemid']=$_POST['itemid'];
		$data['price']=$_POST['price'];
		$data['isitem']=$_POST['type'];

		if(insert_data('itemprice',$data))
		{
			$result["result"]="0";
		}
		else 
		{
			$result["result"]= "1";
		}


		echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}
	function Productinfo()
	{
		$result=$this->db->query("select  a.*, IFNULL((select price from itemprice  where itemid=a.itemPosId ORDER BY `datetime` DESC LIMIT 1),0)  unitary,
		d.name UnitName 
		from itempos a 
		left join units d on a.unitid =d.unitid
        where itemPosId =".$_POST['itemid'])->row_array();

        echo json_encode($result);


	}
	function detailsRecipeTable()
	{

		$html='  <table id="allProduct" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>';
            

        $html= '</tbody>
        		</table>';
	}

	function saveRecipe()
	{
		$result['result']='1';

		$data["name"]= $_POST["name"];
		$data["posid"]=$_POST["posid"];
		$data["active"]=1;
		$data["photo"]="/user_assets/images/Categories/recipedefault.png";
		insert_data("Recipes",$data);
		$idRecipe=$this->db->insert_id();

		if($idRecipe)
		{
			$datal['itemid']=$idRecipe;
			$datal['price']=$_POST['price'];
			$datal['isitem']=0;
			insert_data('itemprice',$datal);

			foreach ($_POST['info'] as $value) {
				
				$datad['recipeid']=$idRecipe;
				$datad['itemid']=$value['id'];
				$datad['quantity']=$value['qty'];
				insert_data('recipedetails',$datad);
			}
			$result['result']='0';
			

		}
		
		echo json_encode($result);

	}

	function recipeInfo()
	{
		$id=$_POST['id'];
		$result['html']='';
		$datos =$this->db->query("select  b.quantity,c.name, (select price from itemprice  where itemid=b.itemid and isitem=1 ORDER BY `datetime` DESC LIMIT 1)  unitary, 
		b.quantity*(select price from itemprice  where itemid=b.itemid and isitem=1 ORDER BY `datetime` DESC LIMIT 1) importe,
		d.name UnitName, b.itemid
		from Recipes a
		left join recipedetails b on a.recipeid =b.recipeid
		left join itempos c on b.itemid=c.itemposid
		left join units d on c.unitid =d.unitid
        where a.recipeid=$id")->result_array();

        foreach ($datos as $value) {
        	$result['html'] .='<tr id="trup'.trim($value['itemid']).'"> <td> '.$value['name'].'</td><td> '.$value['UnitName'].'</td><td> '.$value['quantity'].'</td><td> '.$value['unitary'].'</td><td> ' .$value['importe']. '</td> <td><a id="'.$value['itemid']. '" onclick="deleteitemup(this.id);"> <i class="fa fa-trash-o"></i></a></td></tr>';
        }

        echo json_encode($result);
	}

	function updateRecipe()
	{
		$result['result']='1';
		$idRecipe=$_POST["recipeid"];
		$data["name"]= $_POST["name"];
		$data["active"]=1;
		$data["photo"]="/user_assets/images/Categories/recipedefault.png";
		update_data("Recipes",$data,array("recipeid"=>$idRecipe));

		if($idRecipe)
		{
			$this->db->query("delete from recipedetails where recipeid =$idRecipe");
			foreach ($_POST['info'] as $value) {
				
				$datad['recipeid']=$idRecipe;
				$datad['itemid']=$value['id'];
				$datad['quantity']=$value['qty'];
				insert_data('recipedetails',$datad);
			}
			$result['result']='0';
			

		}
		
		echo json_encode($result);

	}

	function reservationinhouse($date1='',$type=0)
	{
		$hotelid=hotel_id();
		if($date1=='')
		{
			$date1=date('Y-m-d');
		}
		$totalReservation=array();
		$hoteratus=$this->db->query("SELECT hotel_id, Roomnumber, guest_name, 0 channelid,reservation_id ,reservation_code reservation_number
					FROM manage_reservation 
					where STR_TO_DATE(end_date ,'%d/%m/%Y') >='$date1' and STR_TO_DATE(start_date ,'%d/%m/%Y')  <='$date1'
					 and hotel_id =$hotelid and  status ='Checkin' ")->result_array();
		$totalReservation = array_merge($totalReservation,$hoteratus);

		$booking=$this->db->query("SELECT hotel_hotel_id hotel_id, Roomnumber, guest_name, channel_id channelid,room_res_id reservation_id , concat(reservation_id,'-',roomreservation_id)  reservation_number
		FROM import_reservation_BOOKING_ROOMS 
		where arrival_date <='$date1' and departure_date >='$date1'
		and status <>'cancelled' and hotel_hotel_id =$hotelid ")->result_array();
		$totalReservation = array_merge($totalReservation,$booking);


		$expedia=$this->db->query("SELECT hotel_id, Roomnumber,`name` guest_name, channel_id channelid,import_reserv_id reservation_id, `number` 						reservation_number
									FROM import_reservation_EXPEDIA 
									where departure >='$date1' 
									and arrival <='$date1' 
									and type <>'Cancel' and hotel_id =$hotelid ")->result_array();
		$totalReservation = array_merge($totalReservation,$expedia);

		$despegar=$this->db->query("SELECT hotel_id,'' Roomnumber,`name` guest_name, channel_id channelid,Import_reservation_ID reservation_id,
									ResID_Value reservation_number
									FROM import_reservation_DESPEGAR 
									where departure >='$date1' 
									and arrival <='$date1'
									and ResStatus <>'Cancel' and hotel_id =$hotelid ")->result_array();
		$totalReservation = array_merge($totalReservation,$despegar);

		$airbnb=$this->db->query("SELECT hotel_id,'' Roomnumber,`name` guest_name, channel_id channelid,Import_reservation_ID reservation_id ,
								ResID_Value reservation_number
								FROM import_reservation_AIRBNB 
								where departure >='$date1'
								and arrival <='$date1'
								and ResStatus <>'Cancelled' and hotel_id =$hotelid ")->result_array();
		$totalReservation = array_merge($totalReservation,$airbnb);



		if(isset($_POST['returnhtml']) || true )
		{

			if (count($totalReservation)==0) {
				$data['html']='There are no In House Reservation';
				$data['result']=false;
				echo json_encode($data);
				return;
			}

			$html='';
			$html.='<div class="graph">
					<div class="table-responsive">
							<div class="clearfix"></div>
							<table id="inhouse" class="table table-bordered">
									<thead>
											<tr>
													<th>#</th>
													<th>Full Name</th>
													<th>Room Number</th>
													<th>'.($type==0?'Charge':'Book').'</th>
											</tr>
																 </thead>
									<tbody>';
						$i=0;
						foreach ($totalReservation as  $value) {
							$i++;
							$book="'".$value['guest_name']."','".$value['Roomnumber']."'";
							$html.=' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$value['reservation_number'].
								' </th> <td>'.$value['guest_name'].'  </td> <td>'.$value['Roomnumber'].'</td>
									<td  align="center"><a  onclick="'.($type==0?'chargetoRoom('."'".$value['reservation_id']."','".$value['channelid']."'".')':'bookroom('.$book.')').';">
									 <i class="fa fa-check-circle fa-2x"></i></a> </td> </tr>';


						}
						$html.='</tbody>
										</table>';


				$data['html']=$html;
				$data['result']=true;
				echo json_encode($data);
				return;

		}
		

		
	}

	public function tabledue($tableid)
	{
		$data=array();
		$result=$this->db->query("SELECT sum(b.qty) * ifnull((SELECT  price FROM itemprice WHERE ITEMID= b.itemid and isitem = case when b.isitem=1 then 1 else 0 end AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) totaldue, a.ordersListid
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=$tableid  and a.active =1 group by b.itemid")->result_array();

			$total=0;
			foreach ($result as  $value) {
				
				$total +=$value['totaldue'];
				$data['ordenid']=$value['ordersListid'];
			}

			$data['total']=$total;
			return $data;
	}

	function chargeInvoicetoRoom()
	{	$tableid=$_POST['tableid'];
		$reservationId=$_POST['resid'];
		$channelID=$_POST['channelid'];
		$invoice=get_data('reservationinvoice',array('channelId'=>$channelID ,'reservationId'=>$reservationId))->result_array();
		
		if (count($invoice)>0) {
            $invoice=$invoice[0];
            $number=$invoice['Number'];
            $invoiceId=$invoice['reservationinvoiceid'];
       
        }
        else
        {	$data['result']=2; //no tiene factura creada
            return json_encode($data);
        }

        if ( $invoiceId>0) {
        	
        }
        $tabledue=$this->tabledue($_POST['tableid']);
        $datainvoice= array('reservationinvoiceId'=>$invoiceId,'item' =>'POS' ,'qty' =>1, 'description' =>'Charge from POS '.$_POST['namepos'], 'total'=>$tabledue['total'],'tax'=>0,'productid'=>$tabledue['ordenid']);
       
       if(insert_data('reservationinvoicedetails',$datainvoice))
       {
       		update_data('orderslist',array("active"=>2),array('mypostableid' =>$tableid,'active'=>1));
       		$data['result']=0;
       }
       else
       {
       		$data['result']=1;
       }

		echo json_encode($data);
	}

	function saveTask()
	{		
		$result["result"]='';

	    $data['hotelid']=hotel_id();
		$data['staffid']=$_POST['staffid'];
		$data['Description']=$_POST['description'];
		$data['proccess']=$_POST['process'];
		$data['active']=1;
		$data['enddate']=date('Y-m-d',strtotime($_POST['deadline']));
		$data['endtime']=date('H:00:00',strtotime($_POST['endtime']));


		if(insert_data('Task',$data))
		{
			$result["result"]= "0";

			$staffinfo=$this->db->query("select * from mystaffpos where mystaffposid=".$data['staffid'])->row_array();
			$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();


			if(strlen($staffinfo['email'])!=0)
			{
				$para = $staffinfo['email'];
				$titulo = 'New assigned task';
			   	$cabeceras = 'MIME-Version: 1.0' . "\r\n";
	    		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	    		$cabeceras .="From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
	    		$enviado = mail($para, $titulo, $this->templateTask($staffinfo,$data,$titulo), $cabeceras);
			}

			
     

		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

	}
	function updateTask()
	{		
		$result["result"]='';

		$data['staffid']=$_POST['staffidup'];
		$data['Description']=$_POST['descriptionup'];
		$data['proccess']=$_POST['processup'];
		$data['enddate']=date('Y-m-d',strtotime($_POST['deadlineup']));
		$data['endtime']=date('H:00:00',strtotime($_POST['endtimeup']));



		if(update_data('Task',$data,array("taskid"=>$_POST['taskid'])))
		{
			$result["result"]= "0";
				$staffinfo=$this->db->query("select * from mystaffpos where mystaffposid=".$data['staffid'])->row_array();
			$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();


			if(strlen($staffinfo['email'])!=0)
			{
				$para = $staffinfo['email'];
				$titulo = 'Updated assigned task';
			   	$cabeceras = 'MIME-Version: 1.0' . "\r\n";
	    		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	    		$cabeceras .="From: Hoteratus (XML Conection)  <xml@hoteratus.com> \r\n";
	    		$enviado = mail($para, $titulo, $this->templateTask($staffinfo,$data,$titulo), $cabeceras);
			}
		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

	}
	function templateTask($infostaff,$data,$titulo)
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				<title>'.$titulo.'</title>
				<style type="text/css">

			@media screen and (max-width: 600px) {
			    table[class="container"] {
			        width: 95% !important;
			    }
			}

				#outlook a {padding:0;}
					body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
					.ExternalClass {width:100%;}
					.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
					#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
					img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
					a img {border:none;}
					.image_fix {display:block;}
					p {margin: 1em 0;}
					h1, h2, h3, h4, h5, h6 {color: black !important;}

					h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

					h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
						color: red !important; 
					 }

					h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
						color: purple !important; 
					}

					table td {border-collapse: collapse;}

					table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

					a {color: #000;}

					@media only screen and (max-device-width: 480px) {

						a[href^="tel"], a[href^="sms"] {
									text-decoration: none;
									color: black; /* or whatever your want */
									pointer-events: none;
									cursor: default;
								}

						.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
									text-decoration: default;
									color: orange !important; /* or whatever your want */
									pointer-events: auto;
									cursor: default;
								}
					}


					@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
						a[href^="tel"], a[href^="sms"] {
									text-decoration: none;
									color: blue; /* or whatever your want */
									pointer-events: none;
									cursor: default;
								}

						.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
									text-decoration: default;
									color: orange !important;
									pointer-events: auto;
									cursor: default;
								}
					}

					@media only screen and (-webkit-min-device-pixel-ratio: 2) {
						/* Put your iPhone 4g styles in here */
					}

					@media only screen and (-webkit-device-pixel-ratio:.75){
						/* Put CSS for low density (ldpi) Android layouts in here */
					}
					@media only screen and (-webkit-device-pixel-ratio:1){
						/* Put CSS for medium density (mdpi) Android layouts in here */
					}
					@media only screen and (-webkit-device-pixel-ratio:1.5){
						/* Put CSS for high density (hdpi) Android layouts in here */
					}
					/* end Android targeting */
					h2{
						color:#181818;
						font-family:Helvetica, Arial, sans-serif;
						font-size:22px;
						line-height: 22px;
						font-weight: normal;
					}
					a.link1{

					}
					a.link2{
						color:#fff;
						text-decoration:none;
						font-family:Helvetica, Arial, sans-serif;
						font-size:16px;
						color:#fff;border-radius:4px;
					}
					p{
						color:#555;
						font-family:Helvetica, Arial, sans-serif;
						font-size:16px;
						line-height:160%;
					}
				</style>

			<script type="colorScheme" class="swatch active">
			  {
			    "name":"Default",
			    "bgBody":"ffffff",
			    "link":"fff",
			    "color":"555555",
			    "bgItem":"ffffff",
			    "title":"181818"
			  }
			</script>

			</head>
			<body>
				
				<table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class="bgBody">
				<tr>
					<td>
				<table cellpadding="0" width="620" class="container" align="center" cellspacing="0" border="0">
				<tr>
					<td>
			

					<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
						<tr>
							<td class="movableContentContainer bgItem">

								<div class="movableContent">
									<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
										<tr height="40">
											<td width="200">&nbsp;</td>
											<td width="200">&nbsp;</td>
											<td width="200">&nbsp;</td>
										</tr>
										<tr>
											<td width="200" valign="top">&nbsp;</td>
											<td width="200" valign="top" align="center">
												<div class="contentEditableContainer contentImageEditable">
								                	<div class="contentEditable" align="center" >
								                  		<img src="'.base_url().'user_asset/back/images/logo.png" width="155" height="155"  alt="Logo"  data-default="placeholder" />
								                	</div>
								              	</div>
											</td>
											<td width="200" valign="top">&nbsp;</td>
										</tr>
										<tr height="25">
											<td width="200">&nbsp;</td>
											<td width="200">&nbsp;</td>
											<td width="200">&nbsp;</td>
										</tr>
									</table>
								</div>

								<div class="movableContent">
									<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
										<tr>
											<td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
												<div class="contentEditableContainer contentTextEditable">
								                	<div class="contentEditable" align="center" >
								                  		<h2 >'.$titulo.'</h2>
								                	</div>
								              	</div>
											</td>
										</tr>
										<tr>
											<td width="100">&nbsp;</td>
											<td width="400" align="center">
												<div class="contentEditableContainer contentTextEditable">
								                	<div class="contentEditable" align="left" >
								                  		<p >Hi '.$infostaff['firstname'].' '.$infostaff['lastname'].',
								                  			<br/>
								                  			<br/>
															'.$data['Description'].'.</p>
								                	</div>
								              	</div>
											</td>
											<td width="100">&nbsp;</td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
										<tr>
											<td width="200">&nbsp;</td>
											<td width="200" align="center" style="padding-top:25px;">
												<table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
													<tr>
														<td bgcolor="#ED006F" align="center" style="border-radius:4px;" width="200" height="50">
															<div class="contentEditableContainer contentTextEditable">
											                	<div class="contentEditable" align="center" >
											                  		<a target="_blank" href="#" class="link2">Click here to see it</a>
											                	</div>
											              	</div>
														</td>
													</tr>
												</table>
											</td>
											<td width="200">&nbsp;</td>
										</tr>
									</table>
								</div>


								<div class="movableContent">
									<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
										<tr>
											<td width="100%" colspan="2" style="padding-top:65px;">
												<hr style="height:1px;border:none;color:#333;background-color:#ddd;" />
											</td>
										</tr>
										<tr>
											<td width="60%" height="70" valign="middle" style="padding-bottom:20px;">
												<div class="contentEditableContainer contentTextEditable">
								                	<div class="contentEditable" align="left" >
								                  		<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">Sent to [email] by [CLIENTS.COMPANY_NAME]</span>
														<br/>
														<span style="font-size:11px;color:#555;font-family:Helvetica, Arial, sans-serif;line-height:200%;">[CLIENTS.ADDRESS] | [CLIENTS.PHONE]</span>
														<br/>
														<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">
														<a target="_blank" href="[FORWARD]" style="text-decoration:none;color:#555">Forward to a friend</a>
														</span>
														<br/>
														<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">
														<a target="_blank" href="[UNSUBSCRIBE]" style="text-decoration:none;color:#555">click here to unsubscribe</a></span>
								                	</div>
								              	</div>
											</td>
											<td width="40%" height="70" align="right" valign="top" align="right" style="padding-bottom:20px;">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
													<tr>
														<td width="57%"></td>
														<td valign="top" width="34">
															<div class="contentEditableContainer contentFacebookEditable" style="display:inline;">
										                        <div class="contentEditable" >
										                            <img src="'.base_url().'user_asset/back/images/facebook.png" data-default="placeholder" data-max-width="30" data-customIcon="true" width="30" height="30" alt="facebook" style="margin-right:40x;">
										                        </div>
										                    </div>
														</td>
														<td valign="top" width="34">
															<div class="contentEditableContainer contentTwitterEditable" style="display:inline;">
										                      <div class="contentEditable" >
										                        <img src="'.base_url().'user_asset/back/images/twitter.png" data-default="placeholder" data-max-width="30" data-customIcon="true" width="30" height="30" alt="twitter" style="margin-right:40x;">
										                      </div>
										                    </div>
														</td>
														<td valign="top" width="34">
															<div class="contentEditableContainer contentImageEditable" style="display:inline;">
										                      <div class="contentEditable" >
										                        <a target="_blank" href="#" data-default="placeholder"  style="text-decoration:none;">
																	<img src="'.base_url().'user_asset/back/images/pinterest.png" width="30" height="30" data-max-width="30" alt="pinterest" style="margin-right:40x;" />
																</a>
										                      </div>
										                    </div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</div>


							</td>
						</tr>
					</table>

					
					

				</td></tr></table>
				
					</td>
				</tr>
				</table>


			</body>
			</html>';
	}
	function infoStation()
	{

		$stationid=$_POST['stationid'];
		$posid=$_POST['posid'];
		$hotelid=hotel_id();


		$sql=$this->db->query("SELECT *,  case when b.tableid is null then 0 else 1 end used
					FROM mypostable a
					left join  stationtable b on a.postableid=b.tableid
					where  myposId=$posid
					and postableid  not in (select  ifnull(`tableid`,0) 
					from stations c
					left join  stationtable d on c.stationid=d.stationid
					where posid =$posid and c.stationid <> $stationid)")->result_array();

		$sql2=$this->db->query("SELECT *,  case when b.staffid is null then 0 else 1 end used,c.name position
								FROM mystaffpos a
								left join  stationstaff b on a.mystaffposid=b.staffid
								left join stafftype c on a.stafftypeid = c.stafftypeid  
								where  a.hotelid=$hotelid
								and a.mystaffposid  not in (select ifnull(`staffid`,0)
								from stations c
								left join  stationstaff d on c.stationid=d.stationid
								where posid =$posid and c.stationid <> $stationid)")->result_array();

		$html='';

		if (count($sql)==0) {

			$result['htmltable']='<h3>All tables are assigned </h3>';
		}
		else
		{
			$html='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Table Name</th>
												<th>Capacity</th>
												<th>Select</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($sql as  $value) {
						$i++;
						$html.=' <tr id="table'.$value['postableid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['description'].'  </td> <td>'.$value['qtyPerson'].'</td>
								<td  align="center"> <input id="selectedtableid[]" name="selectedtableid[]" value="'.$value['postableid'].'" type="checkbox" '.($value['used']==1?'checked':'').'> </td> </tr>';


					}
					$html.='</tbody>
									</table>
									</div> </div>';
	
			$result['htmltable']=$html;
		}

		if (count($sql2)==0) {

			$result['htmlstaff']='<h3>All tables are assigned </h3>';
		}
		else
		{
			$html2='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Employee Name</th>
												<th>Position</th>
												<th>Select</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($sql2 as  $value) {
						$i++;
						$html2.=' <tr id="'.$value['mystaffposid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['firstname'].' '.$value['lastname'].'  </td> <td>'.$value['position'].'</td>
								<td  align="center"> <input name="selectedstaffid[]" value="'.$value['mystaffposid'].'" type="checkbox" '.($value['used']==1?'checked':'').'> </td> </tr>';


					}
					$html2.='</tbody>
									</table>
									</div> </div>
									 <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPagerstaff"></ul> </div>';
	
			$result['htmlstaff']=$html2;
		}

		
			echo json_encode($result);
			return;
	}
	function saveStation()
	{


		//validaciones
		if (!isset($_POST['selectedtableid'])) {
			$result['success']=false;
			$result['msg']='Select a Table to Continue!!';
			echo json_encode($result);
			return;
		}
		else if (!isset($_POST['selectedstaffid'])) {
			
			$result['success']=false;
			$result['msg']='Select a Employee to Continue!!';
			echo json_encode($result);
			return;
		}

		$data['name']=$_POST['name'];
		$data['supervisorid']=$_POST['staffid'];
		$data['active']=1;
		$data['posid']=$_POST['posid'];



		if(insert_data('stations',$data))
		{
			$id=getinsert_id();

			foreach ($_POST['selectedtableid'] as  $value) {
				insert_data("stationtable",array('stationid'=>$id,'tableid'=>$value));
			}
			foreach ($_POST['selectedstaffid'] as  $value) {
				insert_data("stationstaff",array('stationid'=>$id,'staffid'=>$value));
			}

			$result['success']=true;
		}
		else
		{
			$result['success']=false;
			$result['msg']='Something went wrong!!';
		}
		echo json_encode($result);
		return;

	}
	function updateStation()
	{


		//validaciones
		if (!isset($_POST['selectedtableid'])) {
			$result['success']=false;
			$result['msg']='Select a Table to Continue!!';
			echo json_encode($result);
			return;
		}
		else if (!isset($_POST['selectedstaffid'])) {
			
			$result['success']=false;
			$result['msg']='Select a Employee to Continue!!';
			echo json_encode($result);
			return;
		}

		$data['name']=$_POST['nameup'];
		$data['supervisorid']=$_POST['staffidup'];
		$data['active']=1;

		update_data('stations',$data,array("stationid"=>$_POST['stationid']));
		$id=$_POST['stationid'];
		$this->db->query("delete from stationtable where stationid=$id; ");
		$this->db->query("delete from stationstaff where stationid=$id;  ");
		

		foreach ($_POST['selectedtableid'] as  $value) {
			insert_data("stationtable",array('stationid'=>$id,'tableid'=>$value));
		}
		foreach ($_POST['selectedstaffid'] as  $value) {
			insert_data("stationstaff",array('stationid'=>$id,'staffid'=>$value));
		}

		$result['success']=true;
		echo json_encode($result);
		return;

	}
	function saveGiftCard()
	{
		# giftcardid, hotelid, giftcardnumber, amount, secrectcode, userid, creationdate
		$hotelid=hotel_id();
		$number=$this->db->query("select count(*)+1 number from giftcard where hotelid=$hotelid")->row_array()['number'];
	    $str =  uniqid('');
		$data['amount']=$_POST['amount'];
		$data['hotelid']=$hotelid;
		$data['giftcardnumber']=$number;
		$data['secrectcode']=strtoupper(sprintf('%04s-%04s-%05s',substr($str, 0, 4),substr($str, 4, 4),substr($str, 8, 6))); 
		$data['assignedto']=$_POST['assignedto'];
		$data['buyername']=$_POST['buyername'];
		$data['transferable']=(isset($_POST['rtype'])?1:0);
		$data['userid']=user_id();

		if(insert_data('giftcard',$data))
		{
			$result['success']=true;
		}
		else
		{
			$result['success']=false;
		}
		echo json_encode($result);
		return;
	}
	function saveReservation()
	{
		
		$data['datetimereservation']=date('Y-m-d',strtotime($_POST['deadline']));
		$data['starttime1']=date('H:00:00',strtotime($_POST['hourtime1']));
		$data['starttime2']=date('H:00:00',strtotime($_POST['hourtime2']));
		$data['mypostableid']=$_POST['tableid'];
		$data['Roomid']=(strlen($_POST['roomid'])==0?null:$_POST['roomid']);
		$data['signer']=$_POST['signer'];
		$total=$this->db->query("SELECT count(*) total
							FROM mypostablereservation a
							left join mypostable b on a.mypostableid=b.postableid
							where mypostableid=".$data['mypostableid']."
							and datetimereservation='".$data['datetimereservation']."' 
							and time('".date('H:00:00',strtotime($data['starttime1']))."') between starttime1 and  SUBTIME( starttime2,'00:01') ")->row()->total;


		if($total>0)
		{
			$result['success']=false;
			$result['msg']='This table is occupied for that date and time, Select other Table or Time';
		}
		else if($this->isOpenPos($_POST['posid'],$data['datetimereservation'],date('H:00:00',strtotime($data['starttime1'])))==0)
		{
			$result['success']=false;
			$result['msg']='The post is closed at this time';
		}
		else
		{
			if(insert_data('mypostablereservation',$data))
			{
				$result['success']=true;
			}
			else
			{
				$result['success']=false;
				$result['msg']='Something went wrong!!';
			}
		}
		

		echo json_encode($result);
		return;
	}
	
	function updateReservation()
	{
		
		$data['datetimereservation']=$_POST['deadlineup'];
		$data['starttime1']=$_POST['hourtimeup'];
		$data['mypostableid']=$_POST['tableidup'];
		$data['Roomid']=(strlen($_POST['roomidup'])==0?null:$_POST['roomidup']);
		$data['signer']=$_POST['signerup'];
		$resid=$_POST['resid'];
		$total=$this->db->query("SELECT count(*) total
							FROM mypostablereservation a
							left join mypostable b on a.mypostableid=b.postableid
							where mypostableid=".$data['mypostableid']."
							and datetimereservation='".$data['datetimereservation']."' 
							and mypostablereservationid <> $resid
							and time('".$data['starttime']."') between starttime and time(time(starttime) + time(averagetimeuse))")->row()->total;

		if($total>0)
		{
			$result['success']=false;
			$result['msg']='This table is occupied for that date and time, Select other Table or Time';
		}
		else
		{
			if(update_data('mypostablereservation',$data,array('mypostablereservationid'=>$resid)))
			{
				$result['success']=true;
			}
			else
			{
				$result['success']=false;
				$result['msg']='Something went wrong!!';
			}
		}
		

		echo json_encode($result);
		return;
	}
	function saveRole()
	{
		
		$data['name']=$_POST['rolename'];
		$data['active']=1;
		$data['hotelID']=hotel_id();

			if(insert_data('stafftype',$data))
			{
				$result['success']=true;
			}
			else
			{
				$result['success']=false;
				$result['msg']='Something went wrong!!';
			}
		
		echo json_encode($result);
		return;
	}
	function updateRole()
	{
		
		$data['name']=$_POST['rolenameup'];

			if(update_data('stafftype',$data,array("stafftypeid"=>$_POST['roleidup'])))
			{
				$result['success']=true;
			}
			else
			{
				$result['success']=false;
				$result['msg']='Something went wrong!!';
			}
		
		echo json_encode($result);
		return;
	}
	function InvoicePOS()
	{
		$posid=$_POST['posid'];
		$tableid=$_POST['tableid'];

		$Posinfo=get_data('mypos',array('myposId'=>$posid))->row_array();
		$billing=get_data("bill_info",array('hotel_id'=>hotel_id()))->row_array();
		$country=get_data("country",array('id'=>$billing['country']))->row_array()['country_name'];
		$userdata=get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		 $html='<div   style="float: left;">
                    <h3><b>'.$Posinfo['description'].'</b></h3>

                </div>
                <div  style="float: right; " >
                    <img style=" width:150px; height:100px;" id="logo" src="'.base_url().$billing['Logo'].'" alt="Logo" />
                </div>
                <div class="clearfix"></div>
                	<hr>
                <div>
                    <p class="col-md-8">'.$billing['town'].'<br>
	         		'.$billing['address'].'<br>
	         		'.$country.'<br>
	         		<strong>Phone: </strong>'.$billing['mobile'].'<br>
	         		<strong>Email: </strong>'.$billing['email_address'].'</p  
					<p class="col-md-4" >
						<table>
							<tbody>
								<tr><td><strong>Date:</strong> </td> <td>'.date('M d,Y').' </td></tr>
								<tr><td><strong>INVOICE#: </strong> </td> <td> </td></tr>
							</tbody>
						</table> 
					</p>
                </div>
                <div class="clearfix"></div>
                <hr>
              <div class="col-md-8">
				    <div class="col-md-12 form-group1">
				        <strong>BILL TO:</strong>
				    </div>
				    <div class="col-md-8 form-group1">
				        <label class="control-label">Customer Name</label>
				        <input style="background:white; color:black;" name="address" id="address" type="text" placeholder="Customer Name" required="">
				    </div>
				    <div class="col-md-4 form-group1">
				        <label class="control-label">No. of Persons</label>
				        <input style="background:white; color:black;" name="address" id="address" type="text" placeholder="No. of Persons" onkeypress="return justNumbers(event);" required="">
				    </div>
				</div>
				<div class="col-md-4">
				<table>
						<tbody>
							<tr><td><strong>Time:</strong> </td> <td>'.date('m/d/Y H:m').' </td></tr>
							<tr><td><strong>Serve: </strong> </td> <td>'.$userdata['fname'].' '.$userdata['lname'].' </td></tr>
						</tbody>
					</table>
				</div>
				<hr>
				<div class="col-md-12" style="text-align:center;">
					<h5> Welcome to '.$Posinfo['description'].'</h5>
				</div>
				';

		$table='';

		$OrderInfo=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty, case when b.isitem=1 then c.name else d.name end  itemname,ifnull((SELECT  price FROM itemprice WHERE ITEMID= b.itemid and isitem = case when b.isitem=1 then 1 else 0 end AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) price,
            b.isitem, case when b.isitem=1 then  c.description else '' end description
            from orderslist a 
            left join  orderlistdetails b on a.ordersListid = b.ordersListid 
            left join itempos c on b.itemid=c.itemPosId and b.isitem=1
            left join Recipes d on b.itemid=d.recipeid and b.isitem=0
            where a.mypostableid=$tableid  and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();

		if(count($OrderInfo)>0)
		{
			$table='<div class="clearfix"></div>
			<div class="graph">
				<div class="table-responsive">
						
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>Item</th>
												<th>Description</th>
												<th>Qty</th>
												<th>Rate</th>
												<th>Amount</th>
										</tr>
								</thead>
								<tbody>';
					$i=0;
					$subtotal=0;
					foreach ($OrderInfo as  $value) {
						$i++;
						$table.=' <tr  class="'.($i%2?'active':'success').'">  <td>'.$value['itemname'].'  </td> <td>'.$value['description'].'</td> <td> '.number_format($value['qty'], 2, '.', '').'</td> <td>'.number_format($value['price'], 2, '.', '').' </td> <td style="text-align:right; width:10%;">'.number_format($value['qty']*$value['price'], 2, '.', ''). '</td></tr>';
						$subtotal+=($value['qty']*$value['price']);

					}
					$table.=' <tr >  <td  style="border-top-style:hidden;border-left-style:hidden;border-bottom-style:hidden; text-align:right;" colspan="4"> <strong>SUBTOTAL</strong> </td> <td style="text-align:right;">'.number_format($subtotal, 2, '.', '').' </td> </tr>
					<tr> <td style="border-top-style:hidden;border-left-style:hidden;border-bottom-style:hidden; text-align:right;" colspan="4"> <strong>TAX RATE</strong> </td> <td> <input style="text-align:right; background:white; color:black; border-style:hidden; width:100%;" onkeypress="return justNumbers(event);"  type="text" value="0.00" > </td> </tr>
					<tr> <td style="border-top-style:hidden;border-left-style:hidden;border-bottom-style:hidden; text-align:right;" colspan="4"> <strong>SALES TAX</strong> </td> <td> <input style="text-align:right; background:white; color:black; border-style:hidden; width:100%;" onkeypress="return justNumbers(event);"  type="text" value="0.00" ></td> </tr>
					<tr> <td style="border-top-style:hidden;border-left-style:hidden;border-bottom-style:hidden; text-align:right;" colspan="4"> <strong>DISCOUNT</strong> </td> <td> <input style="text-align:right; background:white; color:black; border-style:hidden; width:100%;" onkeypress="return justNumbers(event); " onblur="discountpp()" id="discount" type="text" value="0.00" ></td> </tr>
					<tr> <td style="border-top-style:hidden;border-left-style:hidden;border-bottom-style:hidden; text-align:right;" colspan="4"> <strong>TOTAL</strong> </td> <td id="totaltopay2" style="text-align:right;">'.number_format($subtotal, 2, '.', '').' </td> </tr>';

					$table.='</tbody>
									</table>
									</div> </div> <div class="clearfix"></div>
									<div class="buttons-ui">
			                            <a onclick="payInvoice()" class="btn blue"><i class="fa fa-credit-card"></i> Pay</a>
			                            <input id="totaltopay" type="hidden" value="'.number_format($subtotal, 2, '.', '').'" />
			                        </div>
									';
		}


        $result['html']=$html.$table;
        $result['result']=true;

        echo json_encode($result);



	}
	
}



