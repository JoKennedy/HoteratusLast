<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Booking.Com</title>
</head>
<body>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="font-size:22px;font-family:Verdana, Geneva, sans-serif;"><?php echo $channel_name; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  align="center" style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">Reservation #<?php echo $reservation_id; ?> </td>
      </tr>
      <tr> <td>&nbsp;  </td> </tr>
      <tr>
        <td>
          <section style=" border: 1px solid #d9d9d9;  border-radius: 4px;
    margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
    
          <section style="float: left;height: 100%; padding: 0; position: relative;  width: 46%;">

    <p style="padding: 0 10px;"><label>Channel Name:</label>
      <span><?php echo $channel_name; ?></span></p>
      <p style="padding: 0 10px;"><label>Status:</label><span><?php echo $status; ?></span>
        </p>

        <p style="padding: 0 10px;"><label>Booked date: </label><span><?php echo $booking_date; ?></span></p>

    <!-- <p  style="padding: 0 10px;"><label>Check-in date:</label> <span><?php echo $checkin; ?></span></p>

    <p  style="padding: 0 10px;"><label>Check-out date:</label><span><?php echo $checkout;?></span></p> -->
    </section>
  <section style=" float: left; height: 100%;  padding: 0;width: 46%;">
	        <p style="padding: 0 10px;"><label>Subtotal:</label><span> <?php echo $subtotal;?></span>
        </p>
        <p style="padding: 0 10px;"><label>Grand total:</label><span> <?php echo $grand_total;?></span>
        </p>
        <p style="padding: 0 10px;"><label>Grand total:</label><span> <?php echo $grand_total;?></span>
        </p>

      
  </section>
<section style=" float: left; height: 100%;  padding: 0;width: 46%;">
<p style="padding: 0 10px;"><label>Hotel Name:</label>
      <span><?php echo $hotel_name; ?></span></p>
      <p style="padding: 0 10px;"><label>Full Name:</label>
      <span><?php echo $fullname; ?></span></p>
  </section>
  <section class="clearfix"></section>
</section></td>
      </tr>

      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">&nbsp;</td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">Room Details</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?php $j = 1; ?>
      <?php foreach ($room_details as $details) { 
          extract($details);
        ?> 
        <tr>
        <td>Room <?php echo $j; ?></td>
      </tr>
      <tr>
        <td>
        <section  style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
          <section style="float: left; height: 100%; padding: 0; position: relative;
            width: 46%;">
            <p style="padding: 0 10px;"><label>Name:</label><span><?php echo $guest_name; ?></span></p>
            <p style="padding: 0 10px;"><label>Check-in:</label><span><?php echo $arrival_date; ?></span></p>
            <p style="padding: 0 10px;"><label>Check-out:</label><span><?php echo $departure_date;?></span></p>

                <p style="padding: 0 10px;"><label>Room Type:</label><span><?php echo $name; ?></span></p>
                <p style="padding: 0 10px;"><label>Number of persons:</label><span><?php echo $numberofguests; ?></span></p>
                <p style="padding: 0 10px;"><label>Number of nights:</label><span>
                <?php $checkin=date('Y/m/d',strtotime($arrival_date));
          $checkout=date('Y/m/d',strtotime($departure_date));
          $nig =_datebetween($checkin,$checkout); ?>
                <?php echo $nig; ?></span></p>
          </section>
  
      <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">
      <p style="padding: 0 10px;"><label>Arrival:</label><span><?php echo date('M d,Y',strtotime($arrival_date)); ?></span></p>
		  <p style="padding: 0 10px;"><label>Departure:</label><span><?php echo date('M d,Y',strtotime($departure_date)); ?></span></p>
      <p style="padding: 0 10px;"><label>Currency:</label><span><?php echo $currencycode; ?></span></p>
      <p style="padding: 0 10px;"><label>Total Price:</label><span><?php echo $totalprice; ?></span></p>
      <p style="padding: 0 10px;"><label>Commission:</label><span><?php echo $commissionamount; ?></span></p>
      </section>
    
   <section class="clearfix"></section>
    <section style="clear:both;overflow:hidden"></section>
  
  <div style="border-radius: 4px;  display: block;   margin-top: 10px;    overflow: hidden;">
      <h4 class="print_title">Daily Price</h4>
      <table cellspacing="0" border="0"  style="border-collapse: collapse;
    border-radius: 4px;  border-spacing: 0;  left: 1%; margin-bottom: 10px;
    padding: 0;   position: relative; font-family:Verdana, Geneva, sans-serif;font-size:12px;   width: 98%;">
        <tbody><tr class="first">
          <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><strong>Date</strong></td>
          <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><strong>Price</strong></td>
        </tr>
        <?php $perdayprice = explode('##', $day_price_detailss);
              for($i=0; $i<count($perdayprice); $i++){
                if($perdayprice[$i] != ""){
                  $details = explode("~", $perdayprice[$i]); 
                  $searchword = 'rewritten_from_name=';
            $matches = array_values(array_filter($details, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }));
            if(count($matches)=='0')
            {
              $data['perdayprice'][] = array(
              current(str_replace('date=','',$details)) => end($details),
              ); ?> 
              <td style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><?php echo date('M d,Y' , strtotime(current(str_replace('date=','',$details)))); ?><br> </td>
                  <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><?php echo end($details);?><?php echo $currencycode; ?></td>
            <?php
            }
            else
            {
              
              $data['promocode'] = "Yes";
              $data['perdayprice'][] = array(
              current(str_replace('date=','',$details)) => str_replace('rewritten_from_name=','',$matches[0]).' '.end($details),
              ); ?> 
                 <td style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><?php echo date('M d,Y' , strtotime(current(str_replace('date=','',$details)))); ?><br> </td>
    
                  <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;"><span class="subtext"><?php echo str_replace('rewritten_from_name=','',$matches[0]); ?></span><?php echo $details[3];?><?php echo $currencycode; ?></td>
              <?php
            }
                  ?>
                    <tr>
              </tr>
               <?php }
              }
        ?>
                            
                </tbody></table>
    </div>

</section>
</td>
      </tr>
      <?php $j++; ?>
      <?php } ?>
      
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">&nbsp;</td>
      </tr>
      <!-- <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666"> Billing info </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <section  style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
  <section class="box">
  <?php if($cc_name != ""){ ?>

    <p style="padding-left:10px;"><label>Payment method:</label><span> Credit Card</span></p>
    <p style="padding-left:10px;"><label>Credit Card Name:</label><span> <?php echo $cc_name; ?></span></p>
    <p style="padding-left:10px;"><label>Credit Card Number:</label><span> <?php echo $cc_number; ?></span></p>
  <?php }else{ ?>
    <p style="padding-left:10px;"><label>Payment method:</label><span> Cash</span></p>
    
  <?php } ?>
  </section> 
  <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">

  </section>

  <section class="clearfix"></section>
</section>
</td>
      </tr> -->
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?php if(isset($ruid) != ""){ ?>
          <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666;"> RUID  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><section style=" border: 1px solid #d9d9d9;  border-radius: 4px;
    margin-bottom: 10px;  padding: 0;  position: relative;">
  <p style="padding: 0 10px;font-family:Verdana, Geneva, sans-serif;font-size:12px;"><?php if($ruid!='') { echo $ruid; } ?>
  </p>
</section></td>
      </tr>
      <?php } ?>
       <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666;"> Notes  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <section style=" border: 1px solid #d9d9d9;  border-radius: 4px;
    margin-bottom: 10px;  padding: 0;  position: relative;">
  <p style="padding: 0 10px;font-family:Verdana, Geneva, sans-serif;font-size:12px;"><?php echo $notes; ?></p>
</section></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
     
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
 </table>

</body>
</html>