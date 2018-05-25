<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Voucher</title>
</head>
<body>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
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
?>
      <tr>
        <td style="font-size:22px;font-family:Verdana, Geneva, sans-serif;"><?php echo ucfirst(get_data(HOTEL,array('hotel_id'=>$hotel_id))->row()->property_name); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">Reservation <?php 
        if($curr_cha_id != 2){
        echo '#'.$reservation_code ; 
      }else
      {
        echo '#'.$reser_id;
      }
        ?> </td>
      
      </tr>
      <tr> <td>&nbsp;  </td> </tr>
      <tr>
        <td>
          <section style=" border: 1px solid #d9d9d9;  border-radius: 4px;
    margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
    
          <section style="float: left;height: 100%; padding: 0; position: relative;  width: 46%;">

    <p style="padding: 0 10px;"><label>Channel Name:</label>
      <span>
        <?php echo $channel_name; ?>
      </span></p>

    <p style="padding: 0 10px;"><label>Confirmation number:</label>
      <span>#<?php echo $reservation_code ; ?></span></p>

    <p  style="padding: 0 10px;"><label>Check-in date:</label> <span> <?php echo date('M d,Y',strtotime(str_replace('/','-',$start_date))); ?></span></p>

    <p  style="padding: 0 10px;"><label>Check-out date:</label><span><?php echo date('M d,Y',strtotime(str_replace('/','-',$end_date))); ?></span></p>
    <?php if(isset($adults)){ ?>
       <p  style="padding: 0 10px;"><label>Adults:</label><span><?php echo $adults; ?></span></p>
    <?php }?>
    <?php if(isset($children)){ ?>
       <p  style="padding: 0 10px;"><label>Children:</label><span><?php echo $children; ?></span></p>
    <?php }?>
  <?php $nt = $num_nights; ?>
  </section>
  <section style=" float: left; height: 100%;  padding: 0;width: 46%;">
	
        <p style="padding: 0 10px;"><label>Subtotal:</label><span><?php echo $currency; ?> <?php if($curr_cha_id != 1 && $curr_cha_id != 8) { echo $price*$nt; }else{ echo $price; } ?></span>
        </p>
        <p style="padding: 0 10px;"><label>Grand total:</label><span><?php echo $currency; ?> <?php if($curr_cha_id != 1 && $curr_cha_id != 8) { echo $price*$nt; }else{ echo $price; } ?></span>
        </p>

        <p style="padding: 0 10px;"><label>Status:</label><span><?php echo $status; ?></span>
        </p>

        <p style="padding: 0 10px;"><label>Booked date: </label><span><?php echo date('M d,Y',strtotime($booking_date)); ?><?php if(isset($booking_time)){ echo ":".$booking_time; } ?></span></p>

        
  </section>
  <section class="clearfix"></section>
</section></td>
      </tr>

      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">&nbsp;</td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">Guest details</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><section  style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
  <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">
    <p style="padding: 0 10px;"><label>Name:</label><span><?php echo $guest_name; ?></span></p>

        <p style="padding: 0 10px;"><label>Phone:</label><span><?php echo $mobile; ?> </span></p>
        <p style="padding: 0 10px;"><label>E-mail:</label><span><?php echo $email; ?></span></p>
  </section>
  
      <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">
  
        <p style="padding: 0 10px;"><label>Street address:</label><span><?php echo $street_name; ?></span></p>
		
        <p style="padding: 0 10px;"><label>Country:</label><span><?php echo $country; ?>
      </span></p>
      </section>
    
   <section class="clearfix"></section>
</section></td>
      </tr>
     <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">Additional Channel Details</td>
      </tr>

       <tr>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td><section  style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
  <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">
        <p style="padding: 0 10px;"><label> Commission:</label><span> <?php echo $commission;?>  </span></p>
        <?php if(isset($channel_room_name)){ ?>
        <p style="padding: 0 10px;"><label> Channel Room Name:</label><span> <?php echo $channel_room_name;?>  </span></p>
        <?php } ?>
		
		<?php if(isset($promocode)){ ?>
        <p style="padding: 0 10px;"><label> Promo Code:</label><span> <?php echo $promocode; ?>  </span></p>
        <?php } ?>
		
  </section>
    
   <section class="clearfix"></section>
</section></td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666">&nbsp;</td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666"> Billing info </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><section  style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
  <section class="box">
    <p style="padding-left:10px;"><label>Payment method:</label><span> <?php echo $payment_method; ?></span></p>
    <?php if(isset($cvv)){?>

      <p style="padding-left:10px;"><label>CVV:</label><span> <?php echo $cvv; ?></span></p>
    <?php } ?>
  </section>
  <section style="float: left; height: 100%; padding: 0; position: relative;
    width: 46%;">

  </section>

  <section class="clearfix"></section>
</section></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666;"> Room Details  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
   
      <tr>
        <td><section style="border: 1px solid #d9d9d9; border-radius: 4px;
    margin-bottom: 10px;   padding: 0;    position: relative;">
    <section style="float: left;  height: 100%; padding: 0; position: relative;
    width: 46%;font-family:Verdana, Geneva, sans-serif;font-size:13px;">
    <p style="padding: 0 10px;"><label>Guest count:</label><span>
      <?php echo $members_count; ?>
    </span></p>

    <p style="padding: 0 10px;"><label>Meal plan
      :</label><span> <?php echo $meal_name; ?></span>
    </p>

   <!-- <p style="padding: 0 10px;"><label>Extras:</label><span>
      Not available
    </span>-->
     
    </p>
    <section class="clearfix"></section>
  </section>
  <section style="  float: left; height: 100%; padding: 0; position: relative;
    width: 46%;font-family:Verdana, Geneva, sans-serif;font-size:13px;">
    <p style="padding: 0 10px;"><label>Check-in date:</label><span> <?php echo date('M d,Y',strtotime(str_replace('/','-',$start_date))); ?></span></p>

    <p style="padding: 0 10px;"><label>Check-out date:</label><span> <?php echo date('M d,Y',strtotime(str_replace('/','-',$end_date))); ?></span></p>
  
    <p style="padding: 0 10px;"><label>Total
      :</label><span> <?php echo $currency; ?> <?php if($curr_cha_id != 1 && $curr_cha_id != 8){ echo $price*$num_nights; }else{ echo $price; } ?></span>
    </p>
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
      <?php if($curr_cha_id != 1 && $curr_cha_id != 8 && $curr_cha_id != 2 && $curr_cha_id != 17 ) { ?>
      <?php $originalstartDate = date('M d,Y',strtotime(str_replace('/','-',$start_date)));
         $newstartDate = date("Y/m/d", strtotime($originalstartDate));
         $originalendDate = date('M d,Y',strtotime(str_replace('/','-',$end_date)));
         $newendDate = date("Y/m/d", strtotime($originalendDate));
         $begin = new DateTime($newstartDate);
         $ends = new DateTime($newendDate);
             $daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
         foreach($daterange as $date){
          
    ?>
            <tr>
              <td style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;">

    <?php echo $date->format("M d, Y") . "<br>"; ?> </td>
    
                  <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;">

                        Adult rate
                            <span class="subtext">(Basic deal)</span>
                        
                        <?php echo $currency; ?> <?php echo $price; ?>
                  </td>
            </tr>
          <?php } ?>
          <?php }else { ?>
          <?php if(isset($perdayprice)){ ?>
          <?php foreach($perdayprice as $key => $value){
          foreach ($value as $date => $val) { ?>
            <tr>
              <td style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;">

    <?php echo date('M d, Y' , strtotime($date)). "<br>"; ?> </td>
    
                  <td  style="border: 1px solid #d9d9d9; line-height: 1.42857;
    padding: 8px; width: 50%;">

                        <?php echo $val; ?> <?php echo $currency; ?> 
                  </td>
            </tr>
          <?php } } } }?>
      </tbody></table>
    </div>

</section></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <?php if(isset($keylength) != 0){ ?>
      <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666;"> Extras  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><section style=" border: 1px solid #d9d9d9; border-radius: 4px; margin-bottom: 10px; padding: 0;clear:both;overflow:hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
   
    <?php 
    
        for($i=0;$i<$keylength;$i++){
          ?>
           <section style="float: left;height: 100%; padding: 0; position: relative;  width: 46%;">
          <?php
          foreach ($extradetails as $key => $extras) { ?>
          <?php if($key == "name" || $key == "totalprice" || $key =="persons"){?>
            <p style="padding: 0 10px;"><label><?php echo ucfirst($key); ?></label>:<span>
            <?php if(is_array($extras)){ ?>
             <?php echo $extras[$i]; ?>
             <?php }else{
              echo $extras;
             } ?>
             <?php if($key == "totalprice"){ echo $currency; }?></span></p>
         <?php } } ?>
         </section>
       <?php }
    ?>
</section></td>
 
      </tr>
      <?php } ?>
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
        <td><section style=" border: 1px solid #d9d9d9;  border-radius: 4px;
    margin-bottom: 10px;  padding: 0;  position: relative;">
  <p style="padding: 0 10px;font-family:Verdana, Geneva, sans-serif;font-size:12px;"><?php if($description!='') { echo $description; } else { echo 'No notes provide...';} ?>
  </p>
</section></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
     <tr>
        <td  style="font-size:16px;font-family:Verdana, Geneva, sans-serif;font-weight:bold;color:#666;"> Policies  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><section style="border: 1px solid rgb(217, 217, 217); border-radius: 4px; margin-bottom: 10px; padding: 0px; position: relative; clear: both; overflow: hidden;font-family:Verdana, Geneva, sans-serif;font-size:12px;">
<?php if($curr_cha_id != 2){ ?>  
<section style="float: left; height: 100%;   padding: 0;  position: relative; width: 46%;">
<?php 
if($curr_cha_id==11)
{
	$cancel_description ="Cancelations or changes made after";
}
?>
<p style="padding: 0 10px;">
<label>Cancellation:</label>
<span>
    <?php echo $cancel_description; ?>
</span>
</p>
<p  style="padding: 0 10px;"><label>Check-in time:</label><span> After <?php echo date('M d,Y',strtotime(str_replace('/','-',$policy_checin))); ?> day of arrival.</span></p>
<p  style="padding: 0 10px;"><label>Check-out time:</label><span> <?php  echo date('M d,Y',strtotime(str_replace('/','-',$policy_checout))); ?> upon day of departure.</span></p>
</section>
<?php } ?>
  <?php
  if($curr_cha_id == 2)
  {
	  ?>
  <section style=" float: left; height: 100%; padding: 0;  position: relative;
    width: 46%;">
    <p style="padding: 0 10px;"><label>Smoking:</label><span><?php if($smoke == '1' && $curr_cha_id == 0){echo 'Smoking is Allowed';}else if($smoke == 0 && $curr_cha_id == 0){echo 'Smoking is not Allowed';}else if($smoke == "" && $curr_cha_id == 0){echo "No Preferences";} ?><?php if($smoke == '1' && $curr_cha_id == 2){echo 'Yes';}else if($smoke == 0 && $curr_cha_id == 2){echo 'No';}else if($smoke == "" && $curr_cha_id == 2){echo "No Preferences";} ?></span>
    </p>
    
    <?php if(isset($pets)){ ?>
    <p style="padding: 0 10px;"><label>Pets:</label><span><?php if($pets=='1'){echo 'Pets are Allowed';}else{echo 'No Pets Allowed';} ?></span></p>
    <?php } ?>
  </section>
  <?php if(isset($info)){ ?>
  <section style=" float: left; height: 100%; padding: 0;  position: relative;
    width: 100%;">
    <p style="padding: 0 10px;"><label>Info:</label><span><?php if($info !=''){echo $info; } ?></span></p>
    </section>
    <?php } ?>
  <?php
  }
  ?>
  <section class="clearfix"></section>
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