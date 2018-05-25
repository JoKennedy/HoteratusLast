 <div class="content container-fluid pad_adjust  mar-top-30">
    <div class="row">

  <div class="col-md-11 col-sm-11 col-xs-12 cls_cmpilrigh">
  <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Manage Channels</h4>
  </div>  
  



  
<div class="bg-neww">
<div class="tab-content">
<!-- Profile Start-->
<div class="tab-pane <?php if(uri(3)=='my_account') {?>active <?php } ?>" id="tab_default_1"><!-- tab1 -->
<div class="pa-n nn2"><h4><a href="#">My Account</a>
    <i class="fa fa-angle-right"></i>
    Profile 
</h4></div>
<div class="box-m new-s">
<div class="row">
<div class="col-md-12 row-pad-top-20">
<?php 
if($this->session->flashdata('profile')!='')
{
	
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<form id="register_form2" method="post" action="<?php echo lang_url();?>channel/my_account">
<div class="row proper2">
<div class="col-md-2 col-sm-2">Your Name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Family Name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Town</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="town" name="town" value="<?php echo $town?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Address</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="address" name="address" value="<?php echo $address;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">ZIP Code</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $zip_code;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Phone</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $mobile;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Property name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="property_name" name="property_name" value="<?php echo $property_name;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Url</div>
<div class="col-md-6 col-sm-6"><input type="url" class="form-control" id="web_site" name="web_site" value="<?php echo $web_site;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Email</div>
<div class="col-md-6 col-sm-6"><input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $email_address;?>"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Currency</div>
<div class="col-md-6 col-sm-6">
<?php $currencys = get_data(TBL_CUR)->result_array();?>
<select name="currency" class="form-control">
<?php foreach($currencys as $cur) { 
extract($cur);?>
   <option value="<?php echo $currency_id; ?>" <?php if($currency_id==$currency) { ?> selected="selected" <?php } ?>> <?php echo $currency_name;?> </option>
   <?php } ?>
</select>
</div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Country </div>
<div class="col-md-6 col-sm-6"><select class="form-control" name="country">
  <?php $countrys = get_data('country')->result_array();
	foreach($countrys as $value) { 
	extract($value);?>
    <option value="<?php echo $id;?>" <?php if($id==$country) { ?> selected="selected" <?php } ?>><?php echo $country_name;?></option>
    <?php } ?>
</select></div>
</div>
<div class="row button">
<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1"><input class="btn btn-primary" type="submit" value="Submit"></div>
</div>
</form>
</div>
</div>
</div>               
</div>
<!-- Profile End-->
<!-- Credit Start -->
<div class="tab-pane <?php if(uri(3)=='manage_credit_cards') {?>active	<?php } ?>" id="tab_default_2">
<div class="col-md-12 col-sm-12" style="padding-left:0;">
<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="javascript:;">My Account</a>
    <i class="fa fa-angle-right"></i>
    Credit Card
</h4></div>
<div class="box-m">
<div class="row">
<div class="col-md-12 button">
<?php 
if($this->session->flashdata('profile')!='')
{
	
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>

<div class="room-type s1">
<a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#M_credit" href="javascript:;" data-backdrop="static" data-keyboard="false">Add New <i class="fa fa-plus"> </i></a>
<br/><br/>
<h3>Active Credit Cards </h3>
<br/>
<div class="table table-responsive">
<table class="table table-bordered" id="tableData">
<thead>
<tr class="info">
<th>#</th>
<th>Card Number</th>
<th>Exp.</th>
<th>CVV</th>
<th>Billing Zip</th>
<th>Action</th>
</tr>
</thead>
<?php if(count($credit_card)!=0) {
	$i=1;
	foreach($credit_card as $value) { extract($value); ?>
<tr id="tr_<?php echo $i?>">
<td><?php echo $i;?></td>
<td><?php echo $card_number;?></td>
<td><?php $date = '0'.$exp_month.'/'.date('d').'/'.date('Y'); echo date('M',strtotime($date)).' '.$exp_year;?></td>
<td><?php echo $cvv;?></td>
<td><?php echo $billing_zip;?></td>
<td><a href="javascript:;" data-toggle="modal" data-target="#M_credit_<?php echo $i;?>" data-backdrop="static" data-keyboard="false"> <i class="fa fa-pencil"></i></a> &nbsp; <a href="<?php echo lang_url();?>channel/manage_credit_cards/delete/<?php echo insep_encode($id);?>" class=""> <i class="fa fa-trash-o text-danger" custom="<?php echo $i;?>"> </i></a>
<div class="modal fade dialog-2 " id="M_credit_<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Edit your credit card details</h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
    <form role="form" class="form-horizontal cls_mar_top" name="card" id="card1" method="post" action="<?php echo lang_url();?>channel/manage_credit_cards/update">
    <input type="hidden" value="<?php echo insep_encode($id);?>" name="card_id" id="card_id" />
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Credit card number <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder="Credit card number" id="card_number" name="card_number" class="form-control" value="<?php echo $card_number;?>">
    </div>
  </div>
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Exp. month <span class="errors">*</span></p>
    <div class="col-sm-6">
   
      <select name="month" class="form-control" >
      <option value=""></option>
      <?php for($ii=1; $ii<=12; $ii++) { ?>
      <option value="<?php echo $ii;?>" <?php if($ii==$exp_month) { ?> selected="selected" <?php } ?>><?php echo $ii;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 Exp. year <span class="errors">*</span></p>
    <div class="col-sm-6">
  
     <select name="year" class="form-control" >
     <option value=""></option>
     <?php for($j=date('Y'); $j<=2028; $j++) { ?>
      <option value="<?php echo $j;?>" <?php if($j==$exp_year) { ?> selected="selected" <?php } ?>><?php echo $j;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
CVV <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="password" placeholder="CVV" id="cvv" name="cvv" class="form-control" value="<?php echo $cvv; ?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3"> Billing zip  <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder=" Billing zip" id="bill_zip" name="bill_zip"class="form-control" value="<?php echo $billing_zip;?>">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Update Card</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
  
</form>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
</td>
</tr>
<?php $i++;} }else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No credit card data found...</td>
</tr>
<?php } ?>
</table>
</div>
<br/><br/>
<h3><!--Disabled Room Types--></h3>
<br/>
<div class="table table-responsive">
<!--<table class="table table-bordered">
<tr class="info">
<th>#</th>
<th>Room Type</th>
<th>Dormitory</th>
<th>Occupancy</th>
<th># Rooms</th>
<th>Accommodations</th>
</tr>
<tr>
<td>1</td>
<td><a href="#">double bed room</a></td>
<td>No</td>
<td>4 people</td>
<td>2</td>
<td>2</td>
</tr>
</table>-->
</div>
</div>
</div>
</div>
</div>               
              
              </div>
              
             
              
              </div>
</div>
<!-- Credit End -->
<!-- Users Start -->
<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>


<div class="tab-pane <?php if(uri(3)=='manage_channel'){ ?>active  <?php } ?>" id="tab_default_5">
<?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">

<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times;</span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<?php 
if($this->session->flashdata('error')!='')
{
  
?>
<div class="alert alert-warning">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times;
</span></button>
<?php echo $this->session->flashdata('error');?>
</div>
<?php } ?>
<?php if($can_add_channel == 1){ ?>
<div class="verify_det clearfix">      
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{?>
<a class="cls_com_blubtn pull-right"href="<?php echo lang_url(); ?>channel/channel_listing">Add Channel <i class="fa fa-plus"> </i></a>
<?php } ?>
 

  </div> 
  <?php } ?>


<div class="">

<div class="clk_history dep_his pay_tab_clr">               
<!-- <?php if($can_add_channel == 1){ ?>
<a class="btn btn-primary hvr-sweep-to-right" href="<?php echo lang_url(); ?>channel/channel_listing/">Add Channel <i class="fa fa-plus"> </i></a>
<?php } ?> -->
<div class="table-responsive">

<table class="table table-bordered" id="tableData3">
<thead>
<tr class="info">
<th>#</th>
<th>Channel Name</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if(count($channeldata) != 0){
  $i = 1;
 ?>
<?php foreach($channeldata as $get){ ?>
<tr>
  <td><?php echo $i; ?></td>
  <td><?php echo $get->channelname; ?></td>
    <!--<td><?php //echo $get->email_address; ?></td>-->
   <td><?php if($get->status=='0') { ?> <span class="label label-sm label-danger">Disabled</span> <?php }elseif($get->status=='1') { ?> <span class="label label-sm label-success">Enabled</span> <?php } ?></td>

<td>
    <a href="<?php echo lang_url();?>channel/channel_status/<?php echo insep_encode($get->channel_id);?>/<?php echo insep_encode($get->plan_id); ?>" class="">
    <?php if($get->status=='0') { ?>
    <i class="fa fa-check-square-o" title="Enable this Channel"> </i>
    <?php } else { ?>
    <i class="fa fa-ban" title="Disable this Channel"> </i>
    <?php } ?>
    </a>     <!-- Edit User-->
  

 </td>  
</tr>
   <?php 
   $i++;
   }  } else { ?>
<tr>
<td colspan="4" class="text-center text-danger">No Channels Connected..</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>


</div>
</div>
<?php } ?>
<!-- Users End -->

<!-- Rooms Start -->
<div class="tab-pane <?php if(uri(3)=='manage_rooms') {?>active	<?php } ?>" id="tab_default_3">
<div class="col-md-12 col-sm-12" style="padding-left:0;">
<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>manage_property">My Property</a>
    <i class="fa fa-angle-right"></i>
    Room types
    </h4></div>
<div class="box-m">
<div class="row">
<div class="col-md-12 button">
<?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<div class="room-type s1">
<a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#M_room" href="javascript:;" data-backdrop="static" data-keyboard="false">Add New <i class="fa fa-plus"> </i></a>
<br/><br/>
<h3>Active Room Types </h3>
<br/>
<div class="table table-responsive">
<table class="table table-bordered" id="tableData1">
<thead>
<tr class="info">
<th>#</th>
<!--<th>Room type</th>-->
<th>Room name</th>
<th>Pricing type</th>
<th>Meal plan</th>
<th>Room capacity</th>
<th>Adult capacity</th>
</tr>
</thead>
<?php if(count($active)!=0) { 
$ii=1;
/*echo '<pre>';
print_r($active);*/
foreach($active as $acc) {
	extract($acc);?>
<tr>
<td><?php echo $ii;?></td>
<!--<td><?php //echo ucfirst(get_data(TBL_ROOM,array('room_id'=>$property_type))->row()->room_type);?></td>-->
<td><a href="<?php echo lang_url();?>inventory/room_types/view/<?php echo insep_encode($property_id);?>"><?php echo ucfirst($property_name);?></a></td>
<td><?php if($pricing_type==1) { echo '<span class="label label-sm label-info">Room based pricing</span>';} else if($pricing_type=='2'){ echo '<span class="label label-sm label-success">Guest based pricing</span>';}?></td>
<td><?php if($meal_plan==0){ echo 'Not available';} else { echo $meal_plans = get_data(MEAL,array('meal_status'=>1,'meal_id'=>$meal_plan))->row()->meal_name;} ?></td>
<td><?php echo $existing_room_count;/*if($status=='Active'){?> <label class="label label-success"> Active </label><?php } else {  ?> <label class="label label-danger"> De-active </label> <?php }*/ ?></td>
<td> <a href="<?php echo lang_url();?>inventory/room_types/view/<?php echo insep_encode($property_id);?>">
<?php echo $member_count;	?> </a>
	<!--<a href="javascript:;" data-toggle="modal" data-target="#M_edit_r_<?php echo $ii;?>" data-backdrop="static" data-keyboard="false"> <i class="fa fa-pencil"></i></a> &nbsp;
    
    <a href="<?php echo lang_url();?>channel/manage_rooms/status/<?php echo insep_encode($property_id);?>"> <?php if($status=='Active'){?><i class="fa fa-lock" title="Dactive this room"> </i><?php } else { ?> <i class="fa fa-unlock" title="Active this room"> </i> <?php } ?></a> &nbsp;
    
    <a href="javascript:;" data-toggle="modal" data-target="#M_view_<?php echo $ii;?>" data-backdrio="static" data-keyboard="false"> <i class="fa fa-eye" title="View this room"> </i> </a> &nbsp; 
    
    <a href="javascript:;" data-toggle="modal" data-target="#M_clone_<?php echo $ii;?>" data-backdrio="static" data-keyboard="false"> <i class="fa fa-copy" title="Clone this room"> </i> </a> &nbsp;
    
    <a href="javascript:;" class="room_photo" data-id="<?php echo insep_encode($property_id)?>"> <i class="fa fa-picture-o" title="View/Add Photos"> </i> </a> &nbsp;
    
    <a href="<?php echo lang_url();?>channel/manage_rooms/delete/<?php echo insep_encode($property_id);?>" class=""> <i class="fa fa-trash-o text-danger" custom="<?php echo $i;?>"> </i></a>-->
    
    <!-- Edit Room-->
    <!--<div class="modal fade dialog-3 " id="M_edit_r_<?php echo $ii?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update room</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
   
  <form role="form" class="form-horizontal cls_mar_top" name="room_form" id="room_form_<?php echo $ii;?>" method="post" action="<?php echo lang_url();?>channel/manage_rooms/update/<?php echo insep_encode($property_id);?>" enctype="multipart/form-data">
  <input type="hidden" value="<?php echo count($active);?>" id="total_room" name="total_room" />
  <div class="col-md-6">
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Room Name" id="property_name_<?php echo $ii?>" name="property_name_<?php echo $ii;?>" class="form-control" value="<?php echo $property_name;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Room Type <span class="errors">*</span></p>
    <div class="col-sm-7">
		   
      <select name="property_type" id="property_type" class="form-control" >
	  <?php $room_types = get_data(TBL_ROOM,array('status'=>'1'))->result_array();
	  foreach($room_types as $room) 
	  { extract($room); ?>
      <option value="<?php echo $room_id;?>" <?php if($room_id==$property_type){?> selected="selected" <?php } ?>><?php echo $room_type;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No. of Members can occupy  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="member_count" id="member_count" class="form-control" >
       <?php for($i=1; $i<=34; $i++) { ?>
      <option value="<?php echo $i;?>" <?php if($i==$member_count){?> selected="selected" <?php } ?> ><?php echo $i;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 Children  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="children" id="children" class="form-control" >
     <?php for($c=0; $c<=9; $c++) { ?>
      <option value="<?php echo $c;?>" <?php if($c==$children) { ?> selected="selected" <?php } ?>><?php echo $c;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="price" id="price" name="price" class="form-control" value="<?php echo $price;?>">
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No of Existing Rooms  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="existing_room_count" id="existing_room_count" class="form-control" >
     <?php for($e=1; $e<=99; $e++) { ?>
      <option value="<?php echo $e;?>"<?php if($e==$existing_room_count){ ?> selected="selected" <?php } ?>><?php echo $e;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Description <span class="errors">*</span></p>
    <div class="col-sm-7">
    <textarea class="form-control" name="description" id="description"><?php echo $description;?></textarea>
    </div>
  </div>
  </div>
  <div class="col-md-6">
  
   <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Country <span class="errors">*</span></p>
    <div class="col-sm-7">
    <select class="form-control" name="country" id="edit_country">
    <?php $countrys = get_data('country')->result_array();
	foreach($countrys as $value) 
	{ 
	extract($value); ?>
    <option value="<?php echo $id;?>" <?php if($id==$country) { ?> selected="selected"<?php } ?>><?php echo $country_name;?></option>
    <?php } ?>
	</select>
    </div>
  </div>
  
    <div id="add_state_div_edit">
    <?php
    $states = get_data(TBL_STATE,array('country_id'=>$country))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 State  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="state" id="edit_state" class="form-control" >
       <?php foreach($states as $s_val) { extract($s_val);?>
      <option value="<?php echo $id;?>" <?php if($id==$state){?> selected="selected" <?php } ?>><?php echo $state_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
    </div>
  
    <div id="add_city_div_edit">
    <?php
    $ctys = get_data(TBL_CITY,array('state_id'=>$state))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 City  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="city" id="edit_city" class="form-control" >
     <?php foreach($ctys as $c_val) { extract($c_val);?>
      <option value="<?php echo $id;?>" <?php if($id==$city){?> selected="selected"	<?php } ?>><?php echo $city_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
    </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Address <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="address" id="address" name="address" class="form-control" value="<?php echo $address?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Zip <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="zip" id="zip" name="zip" class="form-control" value="<?php echo $zip?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Email <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="email" placeholder="Email" id="edit_email_<?php echo $ii?>" name="email_<?php echo $ii?>" class="form-control" value="<?php echo $email?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Mobile <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="mobile" id="edit_phone_<?php echo $ii?>" name="mobile_<?php echo $ii?>" class="form-control" value="<?php echo $mobile?>">
    </div>
  </div>
  
  
  <div class="form-group">	
    <p class="col-sm-4 control-label" for="inputPassword3"> Image  <span class="errors"> </span></p>
    <div class="col-sm-7">
      <input type="file" name="room_image" id="room_image" class="form-control"/> <br />
      <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_type/".$image));?>"/>
    </div>
  </div>
   
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Update Room</button> 
     
    </div>
  </div>
  </div>
  
</form>
  
   
   </div>
   
   
      </div>
      
    </div>
  </div>
</div>-->
    <!-- View Room-->
    <!--<div class="modal fade dialog-3 " id="M_view_<?php echo $ii;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">View room</h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
  <div class="row">
     <form role="form" class="form-horizontal cls_mar_top" name="view_room" id="view_room" method="post" enctype="multipart/form-data">
  
     <div class="col-md-6">
     <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name :<span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"> <?php echo ucfirst($property_name);?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Room Type :<span class="errors"> </span></p>
    <div class="col-sm-7">
		<p class="mar-top7"> <?php echo ucfirst(get_data(TBL_ROOM,array('room_id'=>$property_type))->row()->room_type);?></p>   
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No. of Members can occupy :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $member_count;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 Children :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $children;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price :<span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"> <?php echo $price;?></p>
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No of Existing Rooms :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $existing_room_count;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Description :<span class="errors"> </span></p>
    <div class="col-sm-7">
    <p class="mar-top7"> <?php echo $description;?> </p>
    </div>
  </div>
     </div>
     <div class="col-md-6">
     
      <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Country :<span class="errors"></span></p>
    <div class="col-sm-7">
    <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name);?></p>
    </div>
  </div>
  
    <div id="add_state_div_edit">
           <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 State :<span class="errors"> </span></p>
    <div class="col-sm-7">
  <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_STATE,array('id'=>$state))->row()->state_name);?> </p>
     
    </div>
  </div>
    </div>
  
    <div id="add_city_div_edit">
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 City :<span class="errors"> </span></p>
    <div class="col-sm-7">
  <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_CITY,array('id'=>$city))->row()->city_name);?> </p>
      
    </div>
  </div>
    </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Address : <span class="errors"></span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $address?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Zip : <span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"><?php echo $zip?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Email : <span class="errors"> </span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $email?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Mobile : <span class="errors"></span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $mobile?></p>
    </div>
  </div>
  
   <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3"> Image :  <span class="errors"> </span></p>
    <div class="col-sm-6">
      <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_type/".$image));?>"/>
    </div>
  </div>
   
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" data-dismiss="modal" aria-label="Close" type="button">Cancel</button>
    </div>
  </div>
  </div>
</form>
</div>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>-->
	<!-- clone Room-->
    <!--<div class="modal fade dialog-2 " id="M_clone_<?php echo $ii;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Clone room</h4>
      </div>
      <div class="modal-body sign-up-m">
  <div class="row">
  
  <form role="form" class="form-horizontal cls_mar_top" name="room_form" id="room_clone_<?php echo $ii;?>" method="post" action="<?php echo lang_url();?>channel/manage_rooms/clone" enctype="multipart/form-data">
  <input type="hidden" value="<?php echo count($active);?>" id="total_room" name="total_room" />
  <input type="hidden" value="<?php echo insep_encode($property_id)?>" id="property_id" name="property_id" />
  <div class="col-md-12">
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Room Name" id="property_name_c<?php echo $ii?>" name="property_name_c<?php echo $ii;?>" class="form-control">
    </div>
  </div>
 <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="price" id="price" name="price" class="form-control">
    </div>
  </div>
  
  
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Clone Room</button> 
    </div>
  </div>
  
  
  </div>
  
</form>
</div>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>-->
 </td>	
</tr>
<?php  $ii++;} } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No active room data found...</td>
</tr>
<?php } ?>
</table>
</div>
<!--<br/><br/>
<h3>Disabled Room Types</h3>
<br/>-->
<?php /*?><div class="table table-responsive">
<table class="table table-bordered" id="tableData2">
<thead>
<tr class="info">
<th>#</th>
<th>Room Type</th>
<th>Room Name</th>
<th>Room Price</th>
<th>Room Status</th>
<th>Action</th>
</tr>
</thead>
<?php if(count($inactive)!=0){
	$ii=1;
	foreach($inactive as $iacc) {
	extract($iacc);?>
<tr>
<td><?php echo $ii;?></td>
<td><?php echo ucfirst(get_data(TBL_ROOM,array('room_id'=>$property_type))->row()->room_type);?></td>
<td><?php echo ucfirst($property_name);?></td>
<td><?php echo $price; ?></td>
<td><?php if($status=='Active'){?> <label class="label label-success"> Active </label><?php } else {  ?> <label class="label label-danger"> De-active </label> <?php } ?></td>
<td><a href="javascript:;" data-toggle="modal" data-target="#M_edit_i_<?php echo $ii;?>" data-backdrop="static" data-keyboard="false"> <i class="fa fa-pencil"></i></a> &nbsp; 
	<a href="<?php echo lang_url();?>channel/manage_rooms/status/<?php echo insep_encode($property_id);?>"> <?php if($status=='Active'){?><i class="fa fa-lock" title="Dactive this room"> </i><?php } else { ?> <i class="fa fa-unlock" title="Active this room"> </i> <?php } ?></a> &nbsp; 
    
    <a href="javascripot:;" data-toggle="modal" data-target="#M_view_i<?php echo $ii;?>" data-backdrio="static" data-keyboard="false"> <i class="fa fa-eye" title="View this room"> </i> </a> &nbsp;
    
    <a href="javascript:;" data-toggle="modal" data-target="#M_clone_i<?php echo $ii;?>" data-backdrio="static" data-keyboard="false"> <i class="fa fa-copy" title="Clone this room"> </i> </a> &nbsp;
    
    <a href="javascript:;" class="room_photo" data-id="<?php echo insep_encode($property_id)?>"> <i class="fa fa-picture-o" title="View/Add Photos"> </i> </a> &nbsp;
    
	<a href="<?php echo lang_url();?>channel/manage_rooms/delete/<?php echo insep_encode($property_id);?>" class=""> <i class="fa fa-trash-o text-danger" custom="<?php echo $i;?>"> </i></a>
    <!-- Edit Room-->
    <div class="modal fade dialog-3 " id="M_edit_i_<?php echo $ii?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update room</h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
  
<div class="row">
  <form role="form" class="form-horizontal cls_mar_top" name="room_form" id="room_form_i_<?php echo $ii;?>" method="post" action="<?php echo lang_url();?>channel/manage_rooms/update/<?php echo insep_encode($property_id);?>" enctype="multipart/form-data">
  <input type="hidden" value="<?php echo count($active);?>" id="total_room" name="total_room" />
  <div class="col-md-6">
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Room Name" id="property_names_<?php echo $ii?>" name="property_name_<?php echo $ii;?>" class="form-control" value="<?php echo $property_name;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Room Type <span class="errors">*</span></p>
    <div class="col-sm-7">
		   
      <select name="property_type" id="property_type" class="form-control" >
	  <?php $room_types = get_data(TBL_ROOM,array('status'=>'1'))->result_array();
	  foreach($room_types as $room) 
	  { extract($room); ?>
      <option value="<?php echo $room_id;?>" <?php if($room_id==$property_type){?> selected="selected" <?php } ?>><?php echo $room_type;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No. of Members can occupy  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="member_count" id="member_count" class="form-control" >
       <?php for($i=1; $i<=34; $i++) { ?>
      <option value="<?php echo $i;?>" <?php if($i==$member_count){?> selected="selected" <?php } ?> ><?php echo $i;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 Children  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="children" id="children" class="form-control" >
     <?php for($c=0; $c<=9; $c++) { ?>
      <option value="<?php echo $c;?>" <?php if($c==$children) { ?> selected="selected" <?php } ?>><?php echo $c;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="price" id="price" name="price" class="form-control" value="<?php echo $price;?>">
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No of Existing Rooms  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="existing_room_count" id="existing_room_count" class="form-control" >
     <?php for($e=1; $e<=99; $e++) { ?>
      <option value="<?php echo $e;?>"<?php if($e==$existing_room_count){ ?> selected="selected" <?php } ?>><?php echo $e;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Description <span class="errors">*</span></p>
    <div class="col-sm-7">
    <textarea class="form-control" name="description" id="description"><?php echo $description;?></textarea>
    </div>
  </div>
  </div>
  <div class="col-md-6">
  
   <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Country <span class="errors">*</span></p>
    <div class="col-sm-7">
    <select class="form-control" name="country" id="edit_country">
    <?php $countrys = get_data('country')->result_array();
	foreach($countrys as $value) { 
	extract($value); ?>
    <option value="<?php echo $id;?>" <?php if($id==$country) { ?> selected="selected"<?php } ?>><?php echo $country_name;?></option>
    <?php } ?>
	</select>
    </div>
  </div>
  
    <div id="add_state_div_edit">
    <?php
    $states = get_data(TBL_STATE,array('country_id'=>$country))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 State  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="state" id="edit_state" class="form-control" >
       <?php foreach($states as $s_val) { extract($s_val);?>
      <option value="<?php echo $id;?>" <?php if($id==$state){?> selected="selected" <?php } ?>><?php echo $state_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
    </div>
  
    <div id="add_city_div_edit">
    <?php
    $ctys = get_data(TBL_CITY,array('state_id'=>$state))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 City  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="city" id="edit_city" class="form-control" >
     <?php foreach($ctys as $c_val) { extract($c_val);?>
      <option value="<?php echo $id;?>" <?php if($id==$city){?> selected="selected"	<?php } ?>><?php echo $city_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
    </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Address <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="address" id="address" name="address" class="form-control" value="<?php echo $address?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Zip <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="zip" id="zip" name="zip" class="form-control" value="<?php echo $zip?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Email <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="email" placeholder="Email" id="edit_emails_<?php echo $ii?>" name="email_<?php echo $ii?>" class="form-control" value="<?php echo $email?>">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Mobile <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="mobile" id="edit_phones_<?php echo $ii?>" name="mobile_<?php echo $ii?>" class="form-control" value="<?php echo $mobile?>">
    </div>
  </div>
  
  
  <div class="form-group">	
    <p class="col-sm-4 control-label" for="inputPassword3"> Image  <span class="errors"> </span></p>
    <div class="col-sm-7">
      <input type="file" name="room_image" id="room_image" class="form-control"/> <br />
      <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_type/".$image));?>"/>
    </div>
  </div>
   
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Update Room</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  </div>
  
</form>
  
   
   </div>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
    <!-- View Room-->
    <div class="modal fade dialog-3 " id="M_view_i<?php echo $ii;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">View room</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
     <form role="form" class="form-horizontal cls_mar_top" name="view_room" id="view_room" method="post" enctype="multipart/form-data">
  
     <div class="col-md-6">
     <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name :<span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"> <?php echo ucfirst($property_name);?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Room Type :<span class="errors"> </span></p>
    <div class="col-sm-7">
		<p class="mar-top7"> <?php echo ucfirst(get_data(TBL_ROOM,array('room_id'=>$property_type))->row()->room_type);?></p>   
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No. of Members can occupy :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $member_count;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 Children :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $children;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price :<span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"> <?php echo $price;?></p>
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 No of Existing Rooms :<span class="errors"> </span></p>
    <div class="col-sm-7">
  
     <p class="mar-top7"> <?php echo $existing_room_count;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Description :<span class="errors"> </span></p>
    <div class="col-sm-7">
    <p class="mar-top7"> <?php echo $description;?> </p>
    </div>
  </div>
     </div>
     <div class="col-md-6">
     
      <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Country :<span class="errors"></span></p>
    <div class="col-sm-7">
    <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name);?></p>
    </div>
  </div>
  
    <div id="add_state_div_edit">
           <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 State :<span class="errors"> </span></p>
    <div class="col-sm-7">
  <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_STATE,array('id'=>$state))->row()->state_name);?> </p>
     
    </div>
  </div>
    </div>
  
    <div id="add_city_div_edit">
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
 City :<span class="errors"> </span></p>
    <div class="col-sm-7">
  <p class="mar-top7"> <?php echo ucfirst(get_data(TBL_CITY,array('id'=>$city))->row()->city_name);?> </p>
      
    </div>
  </div>
    </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Address : <span class="errors"></span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $address?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Zip : <span class="errors"> </span></p>
    <div class="col-sm-7">
      <p class="mar-top7"><?php echo $zip?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Email : <span class="errors"> </span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $email?></p>
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Mobile : <span class="errors"></span></p>
    <div class="col-sm-7">
     <p class="mar-top7"><?php echo $mobile?></p>
    </div>
  </div>
  
   <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3"> Image :  <span class="errors"> </span></p>
    <div class="col-sm-6">
      <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_type/".$image));?>"/>
    </div>
  </div>
   
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" data-dismiss="modal" aria-label="Close" type="button">Cancel</button>
    </div>
  </div>
  </div>
</form>
</div>
      </div>
    </div>
  </div>
</div>
	<!-- clone Room-->
    <div class="modal fade dialog-2 " id="M_clone_i<?php echo $ii;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Clone room</h4>
      </div>
      <div class="modal-body sign-up-m">
  <div class="row">
  
  <form role="form" class="form-horizontal cls_mar_top" name="room_form" id="room_clone_i<?php echo $ii;?>" method="post" action="<?php echo lang_url();?>channel/manage_rooms/clone" enctype="multipart/form-data">
  <input type="hidden" value="<?php echo count($active);?>" id="total_room" name="total_room" />
  <input type="hidden" value="<?php echo insep_encode($property_id)?>" id="property_id" name="property_id" />
  <div class="col-md-12">
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Room Name" id="property_name_i<?php echo $ii?>" name="property_name_c<?php echo $ii;?>" class="form-control">
    </div>
  </div>
 <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="price" id="price" name="price" class="form-control">
    </div>
  </div>
  
  
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Clone Room</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
  
  </div>
  
</form>
</div>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
    </td>
</tr>
<?php $ii++;}} else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No inactive room data found...</td>
</tr>
<?php } ?>
</table>
</div><?php */?>
</div>
</div>
</div>
</div>               
              
              </div>
              
             
              
              </div>
</div>
<!-- Rooms Start -->
<!-- Users Start -->
<div class="tab-pane <?php if(uri(3)=='manage_users') {?>active	<?php } ?>" id="tab_default_4">
<div class="col-md-12 col-sm-12" style="padding-left:0;">
<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="javascript:;">My Account</a>
    <i class="fa fa-angle-right"></i>
    Users
</h4></div>
<div class="box-m">
<div class="row">
<div class="col-md-12 button">
<?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<div class="room-type s1">
<a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#M_user" href="javascript:;" data-backdrop="static" data-keyboard="false">Add New <i class="fa fa-plus"> </i></a>
<br/><br/>
<h3>Manage Users</h3>
<br/>
<div class="table table-responsive">
<table class="table table-bordered" id="tableData3">
<thead>
<tr class="info">
<th>#</th>
<th>User Name</th>
<th>Access</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<?php if(count($sub_users)!=0) { 
$ii=1;
foreach($sub_users as $users) {
	/*extract($acc)*/;?>
<tr>
<td><?php echo $ii;?></td>
<td><?php echo ucfirst($users->user_name);?></td>
<td><?php 
$user_acc='';
foreach(explode(',',$users->access) as $p_acc){
$user_acc.= get_data(TBL_ACCESS,array('acc_id'=>$p_acc))->row()->acc_name.' , ';
}
echo trim($user_acc,' , ');?></td>
<td> <?php if(get_status($users->acc_active)->new_status=='0') { ?><label class="label label-success"> <?php echo get_status($users->acc_active)->string;?> </label> <?php } else { ?> <label class="label label-danger"> <?php echo get_status($users->acc_active)->string;?> </label><?php } ?></td>
<td>
	<a href="javascript:;" data-toggle="modal" data-target="#M_user_edit<?php echo $ii;?>" data-backdrop="static" data-keyboard="false"> <i class="fa fa-pencil" title="Edit this user"></i></a> &nbsp;
      <a href="<?php echo lang_url();?>channel/manage_users/status/<?php echo insep_encode($users->user_id);?>"> <?php if($users->acc_active=='1'){?><i class="fa fa-lock" title="Dactive this user"> </i><?php } else { ?> <i class="fa fa-unlock" title="Active this user"> </i> <?php } ?></a>&nbsp;
    &nbsp;
    <a href="<?php echo lang_url();?>channel/manage_users/delete/<?php echo insep_encode($users->user_id);?>" class=""> <i class="fa fa-trash-o text-danger" title="Delete this user" custom="<?php echo $i;?>"> </i></a>
    <!-- Edit User-->
	<div class="modal fade dialog-2 " id="M_user_edit<?php echo $ii;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update User</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="user_form_<?php echo $ii;?>" id="user_form_<?php echo $ii;?>" method="post" action="<?php echo lang_url();?>channel/manage_users/update/<?php echo insep_encode($users->user_id);?> " enctype="multipart/form-data">
      <input type="hidden" name="total_user" id="total_user" value="<?php echo count($sub_users); ?>" />
      <div class="col-md-12">
  
	  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
User Name <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="User Name" id="user_name_e<?php echo $ii;?>" name="user_name_e<?php echo $ii;?>" class="form-control" value="<?php echo $users->user_name?>">
    </div>
  </div>
	  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
User Access <span class="errors">*</span></p>
    <div class="col-sm-7 nf-select">
    <?php 
		$user_access = explode(',',$users->access);
		$access = get_data(TBL_ACCESS,array('status'=>1))->result_array();
		foreach($access as $u_acc)
		{
			extract($u_acc);
	?>
    <input type="checkbox" class="" value="<?php echo $acc_id?>" <?php if(in_array($acc_id,$user_access)){?> checked="checked" <?php } ?>name="access[]" id="access" /> <?php echo $acc_name?> 
    <?php } ?>
     </div>
  </div>
  
  
  
      <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Update User</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
	  </div>
      
     </form>
     </div>
   
  
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
 </td>	
</tr>
<?php  $ii++;} } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No users data found...</td>
</tr>
<?php } ?>
</table>
</div>
</div>
</div>
</div>
</div>               
              
              </div>
              
             
              
              </div>
</div>
<!-- Users End -->
</div>
              
             
              
              </div>
              </div>
</div>
<?php $this->load->view('channel/dash_sidebar'); ?>  