<?php


$html = '';

if($curr_cha_id==0)
{ 
      $room_name = $this->reservation_model->get_room_name_id($room_id);
      $room = $room_name->property_name;
      $meal_plan = $room_name->meal_plan;
      if($meal_plan!=0)
      {
            $meal = $this->reservation_model->get_meal_plan_id($room_name->meal_plan);
            $meal_name = $meal->meal_name;
      }
      else
      {
            $meal_name = 'Not Available';
      }
}
if($curr_cha_id==11)
{
      $char = array('0'=>'None', '1'=>'Continental Breakfast', '2'=>'Buffet Breakfast', '3'=>'Half Board', '4'=>'Full Board');
      foreach($char as $letter => $number) 
      {
            if($mealsinc==$letter)
            {
                  $meal_name = $number;
                  break;
            }
      }
}
if($curr_cha_id != 2){
	$resID = $reservation_code ; 
}else{
	$resID = $reser_id;
}
if($curr_cha_id==0)
{
	$typeBooking = 'Manual Booking';
}else{
	$channel_name = $this->reservation_model->get_channel_name($curr_cha_id);
	$typeBooking =  $channel_name->channel_name;
}
$html .= '<h2 class="cls_comhead">'.ucfirst(get_data(HOTEL,array('hotel_id'=>hotel_id()))->row()->property_name).' - REGISTRATION FORM</h2><h3 bgcolor="#333" color="white" align="center"><span>RESERVATION '.$resID.'</span></h3><p></p>
      <table cellspacing="1" cellpadding="1" border="0">
	  <tr>
		  <td align="left"><strong>Channel Name </strong> </td><td>'.$typeBooking.'</td>
		  <td align="left"><strong>Confirmation number </strong></td><td>#'.$reservation_code.'</td></tr>
		<tr>  <td align="left"><strong>Check-in date </strong></td><td>'.date('M d,Y',strtotime(str_replace('/','-',$start_date))).'</td>
		  <td align="left"><strong>Check-out date </strong></td><td> '.date('M d,Y',strtotime(str_replace('/','-',$end_date))).'</td></tr><tr>';
if(isset($adults)){
	$html .= '<td align="left"><strong>Adults </strong></td><td>'.$adults.'</td>';
}
if(isset($children)){ 
	$html .= '<td align="left"><strong>Children </strong></td><td>'.$children.'</td>';        
}
$nt = $num_nights;
$html .= '</tr><tr>';

if($curr_cha_id==0){
	$user	  = get_data(TBL_USERS,array('user_id'=>user_id()))->row(); 
	$currency = $this->reservation_model->get_curreny_name($user->currency); 
	$currency = $currency->currency_code; 
}
 $html .= '<td align="left"><strong>Subtotal </strong></td><td>'.$currency.'';
if($curr_cha_id != 1 && $curr_cha_id != 8&& $curr_cha_id != 5) {
	 $html .= $price*$nt; 
}else{
	$html .= $price; 
} 
$html .= '</td>
      <td align="left"><strong>Grand total </strong></td><td>'.$currency;
if($curr_cha_id != 1 && $curr_cha_id != 8 && $curr_cha_id != 5) { 
	$html .=  $price*$nt;
}else{ 
	$html .=  $price;
}
$html .= '</td></tr>
      <tr><td align="left"><strong>Status </strong></td><td>'.$status.'</td>
      <td align="left"><strong>Booked date </strong></td><td>'.date('M d,Y',strtotime($booking_date));
if(isset($booking_time)){
	$html .= ':'.$booking_time; 
}
$html .= '</td></tr>
      </table>
	  <p></p>
      
      <h3 bgcolor="#333" color="white" align="center"><span>Guest details</span></h3><p></p>
      <table cellspacing="1" cellpadding="1" border="0">
	  <tr>
		  <td align="left"><strong>Name  </strong></td><td>'.$guest_name.'</td>
		  <td align="left"><strong>Phone </strong></td><td>'.$mobile.'</td></tr><tr>
		  <td align="left"><strong>E-mail </strong></td><td>'.$email.'</td>
		  <td align="left"><strong>Street address </strong></td><td>'.$street_name.'</td></tr>';
if($curr_cha_id==0)
{
	if($country!=''){
		$country = $this->reservation_model->get_country_name_id($country);
		$country = $country->country_name;
	}else{
		$country = '----';
	}
}
$html .= '<tr><td align="left"><strong>Country </strong></td><td>'.$country.'</td><td></td><td></td></tr>
      </table><p></p>';
if($curr_cha_id!=0){ 
      $html .= '<h3 bgcolor="#333" color="white" align="center"><span>Additional Channel Details</span></h3>
      <p></p>
		  <table cellspacing="1" cellpadding="1" border="0">
		  <tr>
			  <td align="left"><strong>Commission </strong></td><td>'.$commission.'</td></tr>';
	if(isset($channel_room_name)){ 
		  $html .= '<tr><td align="left"><strong>Channel Room Name </strong></td><td>'.$channel_room_name.'</td></tr>';
	}
	if(isset($promocode)){
		  $html .= '<tr><td align="left"><strong>Promo Code </strong></td><td>'.$promocode.'</td></tr>';   
	}
	$html .= '</table>';
}else{
	  $html .= '<h3 bgcolor="#333" color="white" align="center"><span>Additional Channel Details</span></h3>
<p></p>
      <div class="clearfix">
      ------
      </div>';

}
 $html .= '
      <p></p>
      <h3 bgcolor="#333" color="white" align="center"><span>Room Details</span></h3>
      <p></p>      
      <table cellspacing="1" cellpadding="1" border="0">
		<tr>
		<td align="left"><strong>Guest count </strong></td><td>'.$members_count.'</td>
		<td align="left"><strong>Child count </strong></td><td>'.(isset($children)?$children:"0").'</td>
		</tr>
		<tr>
		<td align="left"><strong>Meal plan </strong></td><td>'.$meal_name.'</td>
		<td align="left"><strong>Check-in date </strong></td><td>'.date('M d,Y',strtotime(str_replace('/','-',$start_date))).'</td>
		</tr>
		<tr>
		<td align="left"><strong>Check-out date </strong></td><td>'.date('M d,Y',strtotime(str_replace('/','-',$end_date))).'</td>
		<td align="left"><strong>Total </strong></td><td>'.$currency.''.(($curr_cha_id != 1 && $curr_cha_id != 8 && $curr_cha_id != 5)? $price*$num_nights : $price).'</td>
		</tr>
		<tr>
		<td colspan="4"></td>
		</tr>
		<tr>      
			<td colspan="4" align="center"><h4>Daily Price</h4></td>
		</tr>
		<tr>
			<td colspan="4" style="padding:50px">
			  <table cellspacing="1" cellpadding="1" border="0" style="border-left:1px solid #f5f3f3;border-bottom:1px solid #f5f3f3;border-right:1px solid #f5f3f3;border-top:1px solid #f5f3f3;">
			  <thead>
			  <tr bgcolor="#333">
			  <th color="white">&nbsp;&nbsp;&nbsp;Date</th>
			  <th color="white">&nbsp;&nbsp;&nbsp;Price</th>
			  </tr>
			  </thead>
			  <tbody>';
if($curr_cha_id != 1 && $curr_cha_id != 8 && $curr_cha_id != 2 && $curr_cha_id != 17  && $curr_cha_id != 15 && $curr_cha_id != 5 ) {
	$originalstartDate = date('M d,Y',strtotime(str_replace('/','-',$start_date)));
	$newstartDate = date("Y/m/d", strtotime($originalstartDate));
	$originalendDate = date('M d,Y',strtotime(str_replace('/','-',$end_date)));
	$newendDate = date("Y/m/d", strtotime($originalendDate));
	$begin = new DateTime($newstartDate);
	$ends = new DateTime($newendDate);
	$daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
	foreach($daterange as $date){
		$html .= '<tr>
		<td>'.$date->format("M d, Y").'<br></td>
		<td> Adult rate '.$currency.''.($curr_cha_id=='5' ? number_format((float)$price/$num_nights, 2, '.', '') : $price).'</td>
		</tr>';
    }
}else { 
   if(is_numeric($price)){
      if(isset($perdayprice)){ 
			foreach($perdayprice as $key => $value){
				foreach ($value as $date => $val) {
				  $html .= '<tr>
				  <td>'.date('M d, Y' , strtotime($date)).'<br></td>
				  <td>'.($curr_cha_id=='5' ? number_format((float)$val, 2, '.', '') : $val).' '.$currency.'</td>
				  </tr>';  
				} 
			}
	   } 
   }else{ 

        $html .= '<td>'.date('M d, Y' , strtotime($start_date)). " - ".date('M d, Y' , strtotime($end_date)).'</td>

       <td>'.$price.'</td> ';
   } 
}
$html .= '      	  </tbody>
				  </table>
			</td></tr></table>';
if(isset($keylength) != 0){
    $html .= '<p></p><h3 bgcolor="#333" color="white" align="center"><span>Extras</span></h3>
    <p></p>
        <table cellspacing="1" cellpadding="1" border="0">';    
        for($i=0;$i<$keylength;$i++){
          foreach ($extradetails as $key => $extras) {
				if($key == "name" || $key == "totalprice" || $key =="persons"){
					$html .= '<tr><td align="left"><strong>Name </strong></td><td>'.ucfirst($key).'</td>';

					$html .= '<td align="left"><strong>Totalprice </strong></td><td>';
					if(is_array($extras)){
						$html .= $extras[$i];
					}else{
						$html .= $extras;
					} 
					if($key == "totalprice"){
						$html .= $currency;
					}
					$html .= '</td></tr>';
				}
		 }		 
      } 
	  $html .= '</table>'; 

}

//if(isset($ruid) != ""){
//      $html .= '  <tr>
//      <td> </td>
//      <td>'.($ruid!=''?$ruid:'').'</td>
//      </tr>';
//}
      
      $html .= '<p></p><h3 bgcolor="#333" color="white" align="center"><span>Notes</span></h3>
      <p></p>
      '.($description!=''?$description:'No notes provide...').'
	  <p></p>
      <h3 bgcolor="#333" color="white" align="center"><span>Policies</span></h3><p></p>
	 <table cellspacing="1" cellpadding="1" border="0">';
if($curr_cha_id != 2){  
	if($curr_cha_id==0)
	{
		  $cancel_details = get_data(PCANCEL,array('user_id'=>user_id()))->row(); 
		  $other_details = get_data(POTHERS,array('user_id'=>user_id()))->row();
		  $smoke = $other_details->smoking;
		  $pets = $other_details->pets;
		  $cancel_description = $cancel_details->description;
		  $policy_checin = $other_details->check_in_time;
		  $policy_checout   =$other_details->check_out_time;
	}
	elseif($curr_cha_id==11)
	{
		  $cancel_description ="Cancelations or changes made after";
	}
      
    $html .= '<tr><td align="left"><strong>Cancellation </strong></td><td>'.$cancel_description.'</td> 
      <td align="left"><strong>Check-in time </strong></td><td>After '.date('M d,Y',strtotime(str_replace('/','-',$policy_checin))).' day of arrival.</td></tr><tr> 
      <td align="left"><strong>Check-out time </strong></td><td>'.date('M d,Y',strtotime(str_replace('/','-',$policy_checout))).' upon day of departure.</td><td></td> </tr>';
}
if($curr_cha_id==0 || $curr_cha_id == 2)
{

    $html .= '<tr><td align="left"><strong>Smoking &nbsp;</strong></td><td>';
    if($smoke == '1' && $curr_cha_id == 0)
	{
		$html .=  'Smoking is Allowed';
	}else if($smoke == 0 && $curr_cha_id == 0){
		$html .=  'Smoking is not Allowed';
	}else if($smoke == "" && $curr_cha_id == 0){
		$html .=  "No Preferences";
	}
	if($smoke == '1' && $curr_cha_id == 2){
		$html .=  'Yes';
	}else if($smoke == 0 && $curr_cha_id == 2){
		$html .=  'No';
	}else if($smoke == "" && $curr_cha_id == 2){
		$html .=  "No Preferences";
	}
    $html .= '</td></tr>';
	//$flg	= 0;
    if(isset($pets)){
	   //$flg	= 1;
       $html .= '<tr><td align="left"><strong>Pets &nbsp;</strong></td><td>';
       if($pets=='1'){
		   $html .= 'Pets are Allowed';
	   }else{
		   $html .= 'No Pets Allowed';
	   }

       $html .= '</td></tr>';
    }
	if(isset($info)){ 
	  //$flg	= 1;
      $html .= '<tr><td align="left"><strong>Info &nbsp;</strong></td><td>';
	  if($info !=''){$html .=  $info; }
	  $html .= '</td></tr>';
    }
//	if($flg == 0){
//		$html .= '</tr>';
//	}

}
//$html .= '</table><p></p>';
$html .= '</table><p></p><table cellspacing="1" cellpadding="1" border="0">';
for($i = 1;$i <= $members_count;$i++){
	$html .= '<tr><td><strong>Guest '.$i.' &nbsp;&nbsp;&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _</strong></td><td> <span></span></td>';
	if(($i+1) <= $members_count){
		$html .= '<td><strong>Guest '.($i+1).' &nbsp;&nbsp;&nbsp; _ _ _ _ _ _ _ _ _ _ _ _ _ _</strong></td><td> <span> </span></td></tr>';
		$i++;
	}else{
		$html .= '</tr>';
	}
}
$html .= '</table>';
include 'tcpdf/tcpdf.php';
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(8, 5, 8, true);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);
//$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 1, 'color' => array(0, 0, 0));
//$pdf->Line(105, 5, 105, 152, $style);
//$pdf->SetTextColor('5','255','255','255',TRUE);
$pdf->writeHTML($html,1, false, '');
$pdf->lastPage();
$pdf->Output('registration_form.pdf');
return TRUE;