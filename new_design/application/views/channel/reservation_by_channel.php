<style>
.thead-top th
{
border: 0px solid #ddd !important;
}

tr.thead-top
{
border: 0px solid #ddd !important;
}
.bor-top-no{border:0px solid #ddd !important;}
.ui-datepicker
{
z-index:3000 !important;
}
</style>

<?php 

if($user_room_count = $this->inventory_model->user_room_count()!=0)
{

?>
<div class="dash-b4-n calender-n" >
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n"><h4>
Reservations
</h4></div>
</div></div>
</div>

<div class="booking">
<span id="optiondate_val" class="display_none"></span>
<div class="container">

<div class="row">
<div class="col-md-6 col-sm-6">
<div class=""><!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#filter"> Filter Reservation  </button> -->  </div>
</div>
<div class="col-md-6 col-sm-6">
<div class="pull-right">
<?php
if(admin_id()=='')
{
if($User_Type=='2')
{
	if(in_array('2',user_edit()))
	{
		?>
<a href="<?php echo lang_url();?>reservation/export_reservation" title="Download XLSX"> 
<button type="button" class="btn btn-success"> Export XLSX </button> </a> <button type="button" data-toggle="modal" data-target="#myModal2"  class="btn btn-info get_script"> Add Reservation </button>       
		<?php
	}
}
else if($User_Type=='1')
{
?> 
<a href="<?php echo lang_url();?>reservation/export_reservation" title="Download XLSX"> 
<button type="button" class="btn btn-success"> Export XLSX </button> </a> <button type="button" data-toggle="modal" data-target="#myModal2"  class="btn btn-info get_script"> Add Reservation </button> 
<?php } 
}
else if(admin_id()!='' && admin_type()=='1')
{
?>
<a href="<?php echo lang_url();?>reservation/export_reservation" title="Download XLSX"> 
<button type="button" class="btn btn-success"> Export XLSX </button> </a> <button type="button" data-toggle="modal" data-target="#myModal2"  class="btn btn-info get_script"> Add Reservation </button> 
<?php
}

?>
</div>
</div>
</div>
<p>&nbsp;</p>
<div class="row">
<div class="col-md-12 col-sm-12" id="filter_results">
<table class="table table-striped table-bordered table-hover table-full-width bor-top-no" id="sample_13">
  <thead>
  <tr class="thead-top">
  <th class="center" colspan="">&nbsp;</th>
  <th class="center" colspan="">&nbsp;</th>
  <th class="center" colspan="">&nbsp;</th>
  <th class="center" colspan="">&nbsp;</th>
  <th class="center" colspan="">&nbsp;</th>
  <th class="center" colspan="5">&nbsp;</th>
  
 
  </tr>
	<tr>
    <th  style="display:none"> #</th>
	  <th> Status </th>
	  <th> Full name </th>
	  <th> Room Booked </th>
	  <th> Channel </th>
	  <!--<th> User-Status </th>-->
	  <th> Check-in date </th>
	  <th> Check-out date  </th>
	  <th> Booked Date  </th>
	  <th> Reser ID </th>
	  <th> Total Amount</th>
	</tr>
  </thead>
  
  <tbody>
<?php 
	$room_type = $this->reservation_model->get_reservation_list($channel_name);
	// echo '<pre>';
	// print_r($room_type);
	// die;
	if($room_type)
	{	  
		$admin = $this->reservation_model->get_admin();
		$i=1;
		foreach($room_type as $room)
		{
			$status=$room->STATUS;
			if($status==11 || $status == "Book" ||  $status == "Confirmed" || $status == "BOOKING" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm">New booking</button>';
			else if($status==12 || $status == "Modify" || $status == "MODIFIED" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm">Modification</button>';
			else if($status==13 || $status == "Cancel" || $status == "Cancel" || $status == "Canceled" || $status=="Cancelled" || $status == "CANCELED" || $status == "cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm">Cancelled</button>';	
			if(@$room->channel_id==1){$cha_id = 1;}elseif(@$room->channel_id==8){$cha_id = 8;}elseif(@$room->channel_id==11){$cha_id = 11;
			}else if(@$room->channel_id == 5){$cha_id = 5;}else if(@$room->channel_id == 2){$cha_id = 2;}else if(@$room->channel_id == 17){$cha_id = 17;}else if(@$room->channel_id == 15){$cha_id = 15;}
?> 
	<tr>
    <td style="display:none"> <?php  echo $i++;?></td>
	<td><?php echo $status_type;?> </td>
	<td> <a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($cha_id).'/'.insep_encode($room->import_reserv_id); ?>"> <?php echo $room->FIRSTNAME; ?></a> </td>
	<td><?php
    if($room->channel_id == 1)
    {
		$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;

	  	if(!$roomName){
	  		$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$room->roomtypeId,'rateplan_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
	  	}
    }
    else if($room->channel_id == 11)
    {
        $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->re_id))->row()->property_id))->row()->property_name;
    }
    else if($room->channel_id == 8)
    {
        $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->ROOMCODE,'rateplan_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->GTA_id))->row()->property_id))->row()->property_name;
    }else if($room->channel_id == 5){
    	$htb_name = "";
        if(strpos($room->ROOMCODE, ',') !== FALSE){
          $ids = explode(',', $room->ROOMCODE);
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
          $name = get_data(TBL_PROPERTY,array('property_id'=>$room->ROOMCODE));
          if($name->num_rows != 0){
            $roomName = $name->row()->property_name;
          }
        }
    }
    else if($room->channel_id == 2)
    {
         $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping_BOOKING',array('B_room_id'=>$room->roomtypeId, 'B_rate_id' => $room->rateplanid,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
    }
	else if($room->channel_id == 17)
    {
         $roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room->ROOMCODE))->row()->property_name;
    }
    else if($room->channel_id == 15)
    {
         $roomName = @get_data(TBL_PROPERTY,array('property_id'=>$room->ROOMCODE))->row()->property_name;
    }
    if(isset($roomName))
    {
        echo ucfirst($roomName);
    }
    else
    {
        echo '"No Room Set"';
    }
    ?></td>
	   <td>
	   <div>
	  <?php
		 //$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>11))->row();
		 $cha_logo = get_data(TBL_CHANNEL,array('channel_name'=>$channel_name))->row();
		?>
			<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" style="width: 100px">	
			<p class="display_none">Hotelavailabilities</p>
		</div>
	  </td>
	   

      <td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->CHECKIN))); ?></td>
				<td><?php echo date('M d,Y ',strtotime(str_replace("/","-",$room->CHECKOUT))); ?></td>
	   <td>
	   <a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($cha_id).'/'.insep_encode($room->import_reserv_id); ?>"> <?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->RSVCREATE))); ?></a> 
	   </td>	
     <?php if($room->channel_id != 2){  ?>
	   <td><?php echo $room->IDRSV; ?></td>
     <?php }else{ ?>
     <td><?php echo $room->res_id.'-'.$room->IDRSV; ?></td>
     <?php }?>
	   <td><?php echo $room->CURRENCY.' '.$room->REVENUE; ?></td>
				   
	</tr>
		<?php }} ?>

  </tbody>
</table>
</div>
</div>
</div>
</div>

</div>
<!-- dialog box -->

<!-- Modal -->

<div class="dial2">
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">

 <!--<a aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" id="btnGroupDrop1"> Dropdown  <span class="caret"></span>
	</a>--> 
	<!--<ul aria-labelledby="btnGroupDrop1" class="dropdown-menu">
	  <li><a href="#">Dropdown link</a></li>
	  <li><a href="#">Dropdown link</a></li>
	</ul>-->

  <div class="modal-header">
	<button type="button" id="m12close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	
	<h4 class="modal-title" id="myModalLabel"> Room Search </h4>
	

  </div>

  <div class="modal-body">
  
  
	<div class="row">
		

	<form id="reserve" method="post" novalidate="novalidate">  
	<div class="col-md-4 col-sm-4">
	<div class="blu">
	<img src="<?php echo base_url()?>user_assets/images/man.png" class="img img-responsive pull-left">
	<p>Please select your check-in and check-out dates as well as total numbers of rooms and guests.</p>
	</div>
	</div>
	<div class="col-md-4 col-sm-4">
<div class="form-group box-p">
<div class="input-group">
  <div class="input-group-addon">Check-in date</div>

  <input type="text" class="form-control" id="dp1" name="dp1" value="<?php echo date('d/m/Y');?>">
</div>
</div>
<div class="form-group box-p2">
<select class="form-control" id="num_rooms" name="num_rooms">
<?php
for ($i=1; $i<=5; $i++) { 
echo '<option value="'.$i.'">'.$i. 'Rooms</option>';
}
?>
</select>
</div>

	</div>
	<div class="col-md-4 col-sm-4">
	
<div class="form-group box-p">
<div class="input-group">
  <div class="input-group-addon">Check-out date</div>
  <input type="text" class="form-control" id="dp1-p" name="dp2" value="<?php echo date("d/m/Y", strtotime("+1 day"));?>">
</div>
</div>
<div class="form-group box-p2">
<select class="form-control" id="num_person" name="num_person">
<?php
for ($i=1; $i<10; $i++) { 
echo '<option value="'.$i.'">'.$i. 'Adult</option>';

}
?>

</select>
</div>

	</div>
	<div class="bor-dash"></div>
	<div class="modal-footer">
	<button type="button" id="seach_reserve"  class="btn btn-warning">Search</button>
  <button type="button" style="visibility:hidden" id="seach_reserve_show" data-toggle="modal" data-target="#myModal3" class="btn btn-warning">Search</button>
  
  </div>
	</form>
  </div>
  </div>
</div>




</div>
</div>
</div>

<!-- end dialog box -->

<div class="dial2">
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
	<button type="button" id="m13close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">  <span id="detail">sdfsdf</span>       </h4>
  </div>
  <div class="modal-body" id="list_rooms">

  </div>
</div>
</div>
</div>
</div>

<div class="dial2">
<div id="myModal4" class="modal fade modal_addpayment" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
	<button type="button" id="m14close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"> Do Quick Reservation  </h4>
  </div>
  <form id="reserve_info" method="post">
  <div class="modal-body">
  
  
  <div class="row">
  <div class="co-md-6 col-sm-7">
  
  <h5> Guest Information </h5>
  <input type="hidden" name="date1" id="date1" />
  <input type="hidden" name="date2" id="date2" />
	<input type="hidden" name="numrooms" id="nrooms" />
	<input type="hidden" name="numpersons" id="npersons" />
	<div class="col-md-12 col-sm-12"><input name="first_name" type="text" class="form-control"  placeholder="Full Name"> </div>
	
	<br>
	<div class="col-md-6 col-sm-6"><input name="phone" type="text" class="form-control"  placeholder="Phone"> </div>
	
	<div class="col-md-6 col-sm-6"><input name="email" type="text" class="form-control"  placeholder="Email"> 
	<div class="checkbox">
	<input type="checkbox" id="guestmail" name="guestmail" value="guestmail" style="margin-left: 0px; margin-top: 3px;">
	<label for="guestmail">Send email to guest</label>
	</div>
	</div>
	
	<div class="col-sm-12">
	<h5 style="margin-top: 0px;">Notes</h5>
	<p> <textarea name="notes" class="form-control" style="height:150px;"></textarea> </p>
	</div>
  </div>
  <div class="co-md-6 col-sm-5">
  <h5> Reservation</h5>
  <?php 
  $currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;
  ?>
  <div class="table-responsive">
  <table class="table">
  <tr>
  <td> Check-in  :   </td>
  <td id="check_in_date">      </td>
  </tr>
  <tr>
  <td >  Check-out :    </td>
  <td id="check_out_date">  </td>
  </tr>
  </table>
  </div>
  <h5> Charges </h5>
  <div id="reservation_price">
  <div class="table-responsive">
  <table class="table">
  <tr>
  <td >  <span class="num_night"></span> <span class="nights"></span>   </td>
  <td id=""> <?php echo $currency;?> <span id="actualamount" class="cal_price"></span>   </td>
  </tr>
   <!--<tr>
  <td >  Number of nights   </td>
  <td id="num_night">   </td>
  </tr>-->
  <?php 
	$taxes = get_data(TAX,array('user_id'=>current_user_type(),'included_price'=>'0'))->result_array();
	if(count($taxes)!=0)
	{
		foreach($taxes as $valuue)
		{
			extract($valuue);
	?>
  <tr>
  <td>  <?php echo $user_name;?> (<?php $tax_rate;?>%):    </td>
  <td class="">  <?php echo $currency;?> <span class="cal_price"> <?php echo $tax_rate;?></span>  </td>
  </tr> 
  <?php }
   } ?>
  
  <tr>
  <td>Grand total :  </td>
  <td id=""> <?php echo $currency;?> <span id="grand_total"></span></td>
  </tr>
  
  </table>
  </div>
  <h3 class="text-center">DUE NOW  </h3>
  <input type="hidden" name="room_id" id="room_id" value=""/>
  <h3  class="text-center" id=""> <?php echo $currency;?><span id="due_now"> </span></h3>
  </div>
  <hr>
 <div align="center"> 
 <button type="button" id="purchase_click" class="btn btn-info text-center"> Book </button> 
  <button type="button" style="visibility:hidden" id="purchase_show"  class="btn btn-info text-center"data-toggle="modal" data-target="#thanks_booking"> Purchase </button> 

 </div>
  <hr>
  
  </div>
  </div>
 <!--  </form> -->
  </div>
  <div class="extra_payment">
				<div class="col-sm-9 form-inline fn center-block">
				<!--<form class="form-inline" id="payment_details">-->
				  <div class="row">
					  <div class="form-group">
						<select class="form-control ignore m_cc_details" name="card_type">
							<option>credit card</option>
              <option value="Master Card">Master Card</option>
              <option value="VISA">VISA</option>
              <option value="Diners">Diners</option>
              <option value="American Express">American Express</option>
						</select>
					  </div>
					  <div class="form-group">
						<input type="password" class="form-control ignore m_cc_details" id="security_code" name="security_code" placeholder="security code">
					  </div>
					  <div class="form-group">
						<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("user_assets/images/card.png"));?>" />
					  </div>
					  
				  </div>
				  <div class="row mt10">
					<div class="form-group">
						<input type="text" class="form-control ignore m_cc_details" id="card_name" name="card_name" placeholder="Cardholder Name">                        
					  </div>
					  <div class="form-group">
						<input type="text" class="form-control ignore m_cc_details" id="card_number" name="card_number" placeholder="Credit Card Number">                        
					  </div>
				  </div>
          <div class="row">
            <div class="form-group">
            <select class="form-control ignore m_cc_details" name="exp_month">
              <option>expiration month</option>
              <?php
              for($i=1;$i<=12;$i++){
                echo '<option>'.$i.'</option>';
              }
              ?>
            </select>
            </div>
            <div class="form-group">
            /
            </div>
            <div class="form-group">
            <select class="form-control ignore m_cc_details" name="exp_year">
              <option>expiration year</option>
              <?php
               $year=date("Y");
              for($i=$year;$i<=$year+15;$i++){
                echo '<option>'.$i.'</option>';
              }
              ?>
            </select>
            </div>
          </div>
				</div>

		  </div>
				</form>
		
   
   <div style="" class="addpayment">
   <button style="" class="btn btn-payment"><span><i class="fa fa-angle-down"></i>add payment<i class="fa fa-angle-down"></i></span></button>
   </div>
  </div>
  
</div>
</div>
</div>
</div>
  </div>
  
</div>
</div>
</div>
</div>

<div class="dial2">
<div class="modal fade" id="thanks_booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Reservation Details </h4>
  </div>
  <div class="modal-body" id="bookresponse">
  </div>
</div>
</div>
</div>
</div>


<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

<div class="modal-dialog" role="document">
<div class="modal-content">

<form class="form-horizontal" id="post_field" onsubmit="return filter_search();"  method="post">


  <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Modal title</h4>
  </div>
  <div class="modal-body">
  
	
	
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label"> Reservation number </label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputEmail3" name="reservation_code">
			</div>
	</div>

	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label"> Customer name </label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputPassword3" name="guest_name">
			</div>
	</div>

<?php 
	$current_date = date('d/m/Y');
	
?>

	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label"> Check-in date   </label>
			<div class="col-sm-10">
				<input type="text" class="form-control" value="<?php echo $current_date;?>" id="dp1-p1"  name="start_date" required>
			</div>
	</div> 
	

	

	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label">Check-out date </label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="dp1-p2" value="<?php echo $current_date; ?>" name="end_date">
			</div>
	</div>

<a class="btn btn-primary" role="button" data-toggle="collapse" href="#filterMore" aria-expanded="false" aria-controls="collapseExample">
More
</a>

<div class="collapse" id="filterMore">



	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label"> Booked date  </label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="inputPassword3" name="booking_date">
			</div>
	</div>

	<div class="form-group">
		<label for="inputPassword3" class="col-sm-2 control-label"> Status </label>
			<div class="col-sm-10">
				<select class="form-control" name="">
					<option>  All </option>
				</select>
			</div>
	</div>
</div>




  </div>
  <div class="modal-footer">
  
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" name="search" value="search"><span id="filt_search">Search</span></button>
	
  </div>
  </form>
</div>
</div>
</div>

<?php 
}
else
{
?>
<div class="dash-b4">
<div class="col-md-12 col-sm-12">
<div class="bb1">
<br>
<div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
<h2 class="text-center">You don't have any room types yet</h2>
<p class="pad-top-20 text-center"><!--You can apply your different pricing strategies and increase your sales by using rate types--></p>
<br>
<div class="res-p"><center>
<a class="btn btn-primary" href="<?php echo lang_url();?>channel/manage_rooms"><i class="fa fa-plus"></i>  Add room type</a>
</center></div>
</div>
</div>
</div>
<?php 
}
?>
</body>

<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/jquery.min.js"></script> 

<script type="text/javascript">
$('#seach_reserve').click(function()
{
$("#heading_loader").fadeIn("slow");
if($('#reserve').valid())
{
	var checkin_d=$('#dp1').val();
	var checkout_d=$('#dp1-p').val();
	var num_rooms=$('#num_rooms').val();
	var num_person=$('#num_person').val();
	if(num_rooms>num_person)
	{
		$("#seach_reserve_show").trigger('click');
		$("#heading_loader").fadeOut("slow");
		$('#myModal3').find('#detail').html("OOPS!!!");
		var html1="Guest count must be equal or greater than room count<br></br></br>*Close the window to search again";
		$('#myModal3').find('#list_rooms').html(html1);
	}
	else
	{
		$.ajax({
		data : $("#reserve").serialize(),
		url: "<?php echo lang_url();?>reservation/get_reservation", 
		success: function(result){
		$("#heading_loader").fadeOut("slow");
		$("#seach_reserve_show").trigger('click');
		var gethtml=result.split("~~~");
		$('#myModal3').find('#list_rooms').html(gethtml[0]);
		$('#myModal3').find('#detail').html(checkin_d +" To "+checkout_d +" - "+gethtml[1]+" Nights");
		$('#check_in_date').html(checkin_d); 
		$('#check_out_date').html(checkout_d); 
		$('#optiondate_val').html(gethtml[2]);  	   
		$('#date1').val(checkin_d);
		$('#date2').val(checkout_d);
        $('#nrooms').val(num_rooms);
        $('#npersons').val(num_person);
		getreserve();
		}
		});
	}
}
});

function book_this_room(id)
{
$("#heading_loader").fadeIn("slow");
var amount=$('#res_'+id).attr('data-amount');
var grand=$('#res_'+id).attr('data-grand');
var night=$('#res_'+id).attr('data-night');
var room_id=$('#res_'+id).attr('data-room');
if(night=='1' || night==1)
{
	$('.nights').html('Night');
}
else
{
	$('.nights').html('Nights');
}
var grand=$('#changed_price_'+id).html();
var classe= $('#changed_price_'+id).attr('class');
var split=grand.replace(classe,'');
grand=split;
//$('#actualamount').html(grand); 
//$('#grand_total').html(grand); 
$('#num_night').html(night);
$('.num_night').html(night);
$('#due_now').html(grand);
$('#room_id').val(room_id);
var vales=0;
$('.cal_price').each(function()
{
	vales =parseInt(vales)+parseInt($(this).text());
});
s = vales;
 //$('#grand_total').html(s);
$('#due_now').html(s);
$.ajax({
url:'<?php echo lang_url();?>reservation/reservation_price',
type:'post',
data:'price='+grand+"&nights="+night+"&amount="+amount+"&room_id="+room_id,
success:function(result)
{
	var gethtml=result.split("~~~");
	$('#reservation_price').html(result);
	var d=$('#optiondate_val').html();
	$('.optiondate').html(d);
	$("#heading_loader").fadeOut("slow");
	$("#detail_info")[0].click();
},
});
}
function getreserve(){
$('.change_price span').on('click', function() {  
$(this).next('.inr_cont').slideToggle();
});

$('.change_amount').click(function(){
var id=this.id;
var replace=id.replace('change_amount_','');
var val=$('#change_amount_'+replace).parent().parent().find('input').val();
var currency=$('#changed_price_'+replace).attr('class');
var night=$('#grand_tol_'+replace).attr('class');
var grant=parseFloat(night)*parseFloat(val);
$('#changed_price_'+replace).html(currency+""+val);
$('#grand_tol_'+replace).html(currency+ +grant);
$('#actualamount').html(grant); 
$('#grand_total').html(grant);
$('.inr_cont').hide();
});


}
$(document).ready(function(){


$.validator.addMethod('positiveNumber',
function(value) {
return Number(value) > 0;
}, 'Enter a positive number.');

jQuery.validator.addMethod("lettersonly", 
function(value, element) {
 return this.optional(element) || /^[a-z,""]+$/i.test(value);
}, "Letters only please");

/*jQuery.validator.addMethod("customemail", function(value, element) 
{
	return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
}, "Please enter a valid email address.");*/

//custom validation rule
$.validator.addMethod("customemail", 
function(value, element) {
	return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
}, 
"Sorry, I've enabled very strict email validation"
);
jQuery.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[0-9\+]+$/i.test(value);
			}, "Numbers, and plues only please");
			
$('#reserve_info').validate({
ignore: ".ignore",
rules:
{
first_name:
{
  required:true,
},
last_name:
{
  required:true,
},
phone:
{
  required:true,
  alphanumeric : true
},
email:
{
  required:true,
  customemail:true
},
card_type:
{
	required:true
},
security_code:
{
	required:true
},
card_name:
{
	required:true
},
card_number:
{
	required:true,
	creditcard: true   
},
exp_month:
{
	required:true
},
exp_year:
{
	required:true
}
},
errorPlacement: function (error, element) {
  return false;
},
highlight: function (element) { // hightlight error inputs
      $(element)
        .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
    },
unhighlight: function (element) { // revert the change done by hightlight
      $(element)
        .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
    },
});
$('#reserve').validate({
rules:
{
dp1:
{
required:true

},
dp2:
{
required:true

}		
},
errorPlacement: function (error, element) {
return false;
},
highlight: function (element) { // hightlight error inputs
	  $(element)
		  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
  },
unhighlight: function (element) { // revert the change done by hightlight
	  $(element)
		  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group 
  },
});
});

$('#purchase_click').click(function(){
  
	if($('#reserve_info').valid())
	{
		$("#heading_loader").fadeIn("slow");
		$.ajax({
					data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
					url: "<?php echo lang_url();?>reservation/save_reservation", 
					success: function(result)
					{
						$('#m14close').trigger('click');
						$('#m12close').trigger('click');
						$('#m13close').trigger('click');
						$("#heading_loader").fadeOut("slow");
						$("#purchase_show").trigger('click');
						$('#thanks_booking').find('#bookresponse').html(result);
					}
				});
	}
})


</script>

<script>
function filter_search(){
 $('#filt_search').html('');
 $.ajax({
	type:"POST",
	url:"<?php echo lang_url(); ?>reservation/filter_res",
	data:$('#post_field').serialize(),
	beforeSend:function(){
		$('#filt_search').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
		$('#filt_search').attr('disabled',true);
	},
	complete:function(){
		$('#filt_search').html('search');
		$('#filt_search').attr('disabled',false);
	},
	success:function(msg){
		$('#filter_results').html(msg);
		$('#filter').modal('hide');
	}
 });
 return false;
}




$('body').click(function(){

if($('#myModal2').hasClass('in')){

$.datepicker.regional[""].dateFormat = 'dd/mm/yy';

$.datepicker.setDefaults($.datepicker.regional['']);

}else{

$.datepicker.regional[""].dateFormat = 'M d,yy';

$.datepicker.setDefaults($.datepicker.regional['']);

}

});

/*$('#dp1').click(function(){

setTimeout(function(){

 $('.ui-datepicker-prev').trigger('click');

},100)

// $('.ui-datepicker-prev').click();

})*/

$().ready(function()
{
setTimeout(function(){
		$('#sample_13_wrapper').find('.col1').find('.search_init').find("option:first").text('All Reservations');
		$('#sample_13_wrapper').find('.col1').find('.search_init').val('<?php echo $channel_name;?>');
		$('#sample_13_wrapper').find('.col2').find('.search_init').find("option:first").text('All Status');
		$('#sample_13_wrapper').find('.col1').find('.search_init').find("option:first").val('');
		$('#sample_13_wrapper').find('.col2').find('.search_init').removeAttr('onchange');
	},800);
});

$('.addpayment .btn').on('click', function(e) { 
	$('.extra_payment').slideToggle();
	e.preventDefault(); 
	$("#heading_loader").fadeIn("slow");
	var card_type=$('#card_type').val();
	var security_code=$('#security_code').val();
	var card_name=$('#card_name').val();
	var card_number=$('#card_number').val();
	var exp_month=$('#exp_month').val();
	var exp_year=$('#exp_year').val();
	setTimeout(function(){	
	if($('.extra_payment').css('display') == 'block')
	{
		$("#heading_loader").fadeOut("slow");
		$('#card_type').removeClass('ignore');
		$('#security_code').removeClass('ignore');
		$('#card_name').removeClass('ignore');
		$('#card_number').removeClass('ignore');
		$('#exp_month').removeClass('ignore');
		$('#exp_year').removeClass('ignore');
		if(card_type!='' && security_code!="" && card_name!="" && card_number!="" && exp_month!=""&& exp_year!=""  && $('#reserve_info').valid())
		{
			$("#heading_loader").fadeIn("slow");
			$.ajax({
			data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
			url: "<?php echo lang_url();?>reservation/save_reservation", 
			success: function(result){
			$('#m12close').trigger('click');
			$('#m13close').trigger('click');
			$('#m14close').trigger('click');
			$('.extra_payment').slideToggle();
			$("#heading_loader").fadeOut("slow");
			$("#purchase_show").trigger('click');
			$('#thanks_booking').find('#bookresponse').html(result);
			}
			});
		}
	}
	else if($('.extra_payment').css('display') == 'none')
	{
		//$('.m_cc_details').val('');
		$('#card_type').removeClass('customErrorClass');
		$('#security_code').removeClass('customErrorClass');
		$('#card_name').removeClass('customErrorClass');
		$('#card_number').removeClass('customErrorClass');
		$('#exp_month').removeClass('customErrorClass');
		$('#exp_year').removeClass('customErrorClass');
		
		$('#card_type').addClass('ignore');
		$('#security_code').addClass('ignore');
		$('#card_name').addClass('ignore');
		$('#card_number').addClass('ignore');
		$('#exp_month').addClass('ignore');
		$('#exp_year').addClass('ignore');
		
		if(card_type!='' && security_code!="" && card_name!="" && card_number!="" && exp_month!=""&& exp_year!=""  && $('#reserve_info').valid())
		{
			$("#heading_loader").fadeIn("slow");
			$.ajax({
			data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
			url: "<?php echo lang_url();?>reservation/save_reservation", 
			success: function(result){
			$('#m12close').trigger('click');
			$('#m13close').trigger('click');
			$('#m14close').trigger('click');
			$('.extra_payment').slideToggle();
			$("#heading_loader").fadeOut("slow");
			$("#purchase_show").trigger('click');
			$('#thanks_booking').find('#bookresponse').html(result);
			}
			});
		}
		else
		{
			$("#heading_loader").fadeOut("slow");
		}
	}
	},500);
	$(this).find('i').toggleClass('fa-angle-down fa-angle-up');
});




</script>
</html>


