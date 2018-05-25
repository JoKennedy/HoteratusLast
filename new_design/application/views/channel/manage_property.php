<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">

</div>


<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
<?php $this->load->view('channel/property_sidebar');?>
</div></div></div></div>            
            
            
            
     </div>
</div>

</div>


</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">

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
	<?php
			if(user_type()=='2')
			{
				if(in_array('4',user_edit()))
				{
			?>
	<div class="row button">
	<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1"><input class="btn btn-primary" type="submit" value="Submit"></div>
	</div>
    <?php
			    }
			}
			else if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
			?> 
	<div class="row button">
	<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1">
	 <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
	<input class="btn btn-primary" type="submit" value="Submit"></div>
	 <?php } ?>
	</div>
   <?php } ?>
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
<?php if(isset($credit_card)) { if(count($credit_card)!=0){
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
<?php $i++;} }}else { ?>
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
</div>
</div>
</div>
</div>
</div>        
</div>
</div>
</div>
<!-- Credit End -->

<!-- Property Start -->
<div class="tab-pane <?php if(uri(3)=='manage_property' || uri(3)=='property_info' || uri(3)=='billing_info') {?>active	<?php } ?>" id="tab_default_3">
<div class="col-md-12 col-sm-12" style="padding-left:0;">

<div class="bg-neww">
<div class="pa-n nn2">
  <h4><a href="<?php echo lang_url();?>channel/property_info">Property Info</a>
<?php if(uri(3)=='property_info') { ?>
<i class="fa fa-angle-right"></i>
    Profile
    <?php } 
    if(uri(3)=='billing_info'){ ?>
    <i class="fa fa-angle-right"></i>
    Billing Info
   <?php } ?>
    </h4></div>

<div class="box-m">
<div class="row">
<?php 
if($action=='manage_property') { ?>
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
<?php if($can_add == 1){ 
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
?>
<a class="btn btn-primary hvr-sweep-to-right add_property" href="javascript:;">Add New <i class="fa fa-plus"> </i></a>
<?php } }?>
<?php if(isset($num_hotels) != ""){ ?>
 You Can Add Upto <?php echo $num_hotels; ?> Property 
<?php } ?>
<br/><br/>
<h3>Manage Property </h3>
<br/>
<div class="table table-responsive">
<table class="table table-bordered" id="tableData1">
<thead>
<tr class="info">
<th>#</th>
<th>Property Name</th>
<th>City</th>
<th>Country</th>
<!--<th>Status</th>-->
<th>Action</th>
</tr>
</thead>
<?php if(count($hotel)!=0) { 
$ii=1;
foreach($hotel as $hotel_value) {
extract($hotel_value); ?>

<tr id="del_<?php echo insep_encode($hotel_id);?>">
  
<td><?php echo $ii;?></td>
<td> <a href="javascript:;" class="view_property" data-id="<?php echo insep_encode($hotel_id);?>"><?php echo ucfirst($property_name);?></a></td>
<td><?php echo $town;?></td>
<td><?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?></td>

<td> 
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{
?>
<a href="javascript:;" class="edit_property" data-id="<?php echo insep_encode($hotel_id);?>"> <i class="fa fa-edit"> </i></a>
<!-- <a href="javascript:;" class="delete_property text-danger" data-id="<?php //echo insep_encode($hotel_id);?>"> <i class="fa fa-trash-o"> </i></a>-->
<?php  } ?>
</td>	
</tr>
<?php  $ii++;} } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No active room data found...</td>
</tr>
<?php } ?>

</table>
</div>
</div>
</div>
<?php } 
else if($action=='property_info') { ?>
<div class="col-md-12 button">
<?php 
if($this->session->flashdata('property_info')!='')
{
	
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('property_info');?>
</div>
<?php } ?>
<form id="edit_property_forms" name="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update">

<input type="hidden" name="edit_hotel_id" value="<?php echo insep_encode($property->hotel_id)?>" id="edit_hotel_id"> 

<input type="hidden" name="redirect_url" value="property_info" id="redirect_url"> 

<!--<div class="row proper2">
<div class="col-md-2 col-sm-2">Your Name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="fname" name="fname" value="<?php //echo $property->fname?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Family Name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="lname" name="lname" value="<?php //echo $property->lname;?>"></div>
</div>-->

<div class="row proper2">
<div class="col-md-2 col-sm-2">Property name</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="property_name" name="property_name" value="<?php echo $property->property_name;?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Address</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="address" name="address" value="<?php echo $property->address;?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Town</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="town" name="town" value="<?php echo $property->town?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">ZIP Code</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $property->zip_code;?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Currency <?=$property->currency?></div>
<div class="col-md-6 col-sm-6">
<?php $currencys = get_data(TBL_CUR)->result_array();?>
<select name="currency" class="form-control">
<?php foreach($currencys as $cur) { 
extract($cur);?>
   <option value="<?php echo $currency_id; ?>" <?php if($currency_id==$property->currency) { ?> selected="selected" <?php } ?>> <?php echo $currency_name;?> </option>
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
    <option value="<?php echo $id;?>" <?php if($id==$property->country) { ?> selected="selected" <?php } ?>><?php echo $country_name;?></option>
    <?php } ?>
</select></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Email</div>
<div class="col-md-6 col-sm-6"><input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $property->email_address;?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Url</div>
<div class="col-md-6 col-sm-6"><input type="url" class="form-control" id="web_site" name="web_site" value="<?php echo $property->web_site;?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Phone</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $property->mobile;?>"></div>
</div>

<div class="row button">
<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1">
<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
	<input class="btn btn-primary" type="submit" value="Submit"></div>
<?php } ?>
</div>
</form>
</div>
<?php } 

else if($action=='billing_info') { ?>
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

<form id="add_bill" name="add_bill" method="post" action="<?php echo lang_url();?>channel/add_bill">

<input type="hidden" name="bill_id" value="<?php if(isset($bill->bill_id)){echo $bill->bill_id; }?>">

<div class="row proper2">
<div class="col-md-2 col-sm-2">Company name <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="company_name" name="company_name" value="<?php if(isset($bill->company_name)){ echo $bill->company_name; } ?>"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Town <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="town" name="town" value="<?php if(isset($bill->town)){echo $bill->town;} ?>">
</div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Billing Address <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php if(isset($bill->address)){echo $bill->address; } ?>" id="address" name="address"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">ZIP Code <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php if(isset($bill->zip_code)){ echo $bill->zip_code; } ?>">
</div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Phone <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="mobile" name="mobile" value="<?php if(isset($bill->mobile)){ echo $bill->mobile; }?>">
</div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">VAT <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="vat" name="vat" value="<?php if(isset($bill->vat)){echo $bill->vat; }?>">
</div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Registration Number<span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="reg_num" name="reg_num" value="<?php if(isset($bill->reg_num)){echo $bill->reg_num; } ?>">
</div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Billing Email <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6">
  <input type="email" class="form-control" id="email_address" name="email_address" value="<?php if(isset($bill->email_address)){echo $bill->email_address; } ?>">
</div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Country <span class="error">*</sapn></div>
<div class="col-md-6 col-sm-6"> 
  <?php if(isset($bill->country)){
    $c = $bill->country;
  }else{
    $c='';
  } ?>
  <select class="form-control" name="country">
  <?php $full_country = $this->admin_model->full_country();
        foreach($full_country as $full){
   ?>
    <option value="<?php echo $full->id; ?>" <?php if($full->id==$c){ echo "selected='selected'";}?>><?php echo $full->country_name; ?></option>
    <?php } ?>
</select></div>
</div>

<div class="row button">
<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1">
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>

					<input class="btn btn-primary" type="submit" name="save" value="Submit">
	  <?php } ?>
		
</div>
</div>
</form>
</div>
<?php } 

else if($action=='edit') { ?>
<div class="col-md-12 button">
<?php 
if($this->session->flashdata('profile_edit')!='')
{
  
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile_edit');?>
</div>
<?php } ?>
<div class="box-m new-s">
<div class="row">
<div class="col-md-12 row-pad-top-20">
<form id="edit_bill" name="edit_bill" method="post" action="<?php echo lang_url();?>channel/edit_bill">

<div class="row proper2">
<div class="col-md-2 col-sm-2">Company name</div>
<div class="col-md-6 col-sm-6">
  <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $bill->company_name; ?>">
</div>
</div>

<input type="hidden" name="bill_id" value="<?php echo $bill->bill_id; ?>">

<div class="row proper2">
<div class="col-md-2 col-sm-2">Town</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="town" name="town"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Billing Address</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="address" name="address"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">ZIP Code</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="zip_code" name="zip_code"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Phone</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="mobile" name="mobile"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">VAT</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="vat" name="vat"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Registration Number</div>
<div class="col-md-6 col-sm-6"><input type="text" class="form-control" value="<?php echo $bill->company_name; ?>" id="reg_num" name="reg_num"></div>
</div>

<div class="row proper2">
<div class="col-md-2 col-sm-2">Billing Email</div> 
<div class="col-md-6 col-sm-6"><input type="email" class="form-control" value="<?php echo $bill->company_name; ?>" id="email_address" name="email_address"></div>
</div>
<div class="row proper2">
<div class="col-md-2 col-sm-2">Country </div>
<div class="col-md-6 col-sm-6">

  <select class="form-control" name="country">
  <?php $full_country = $this->admin_model->full_country();
        foreach($full_country as $full){
   ?>
    <option value="<?php echo $full->id; ?>"><?php echo $full->country_name; ?></option>
    <?php } ?>
</select></div>
</div>


<div class="row button">
<div class="col-md-6 col-sm-6 col-md-offset-2 col-sm-offset-2 s1"><input class="btn btn-primary" type="submit" name="save" value="Submit"></div>
</div>

</form>
</div>
</div>
</div>
</div>
<?php } ?>



</div>
</div>               
</div>
</div>
</div>
<!-- Property End -->

<!-- Users Start -->
<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
<div class="tab-pane <?php if(uri(3)=='manage_subusers') {?>active	<?php } ?>" id="tab_default_4">
<div class="col-md-12 col-sm-12" style="padding-left:0;">

<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>manage_property">My Property</a>
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

<a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#add_user" href="javascript:;" data-backdrop="static" data-keyboard="false">Add Users <i class="fa fa-plus"> </i></a>

<br/><br/>
<h3>Manage Users</h3>
<br/>
<div class="table table-responsive">
<table class="table table-bordered" id="tableData3">
<thead>
<tr class="info">
<th>#</th>
<th>User Name</th>
<th>User Password</th>
<!--<th>User Email</th>-->
<th>Access</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<?php
	$assign_user = $this->channel_model->get_user_list();
	if($assign_user)
	{
		$i=0;
		foreach($assign_user as $users)
	   	{
			$access				= (array)json_decode($users->access);
			$user_access_view='';
			$user_access_edit='';
			foreach($access as $photo_id=>$photo_obj)
			{
				if(!empty($photo_obj))
				{
					$photo = (array)$photo_obj;
					//echo 'menu'.$photo_id.'<br>';
					//echo 'edit'.$photo['edit'].'<br>';
					//echo 'view'.$photo['view'].'<br>';
					
					if(isset($photo['view'])!='')
					{
						//echo 'ee'.$photo = $photo_id;
						$fetch_access = $this->channel_model->fetch_access($photo_id);
						$user_access_view.=$fetch_access->acc_name.' , ';
					}
					else
					{
						$user_access_view.='';
					}
					if(isset($photo['edit'])!='')
					{
						$fetch_access1 = $this->channel_model->fetch_access($photo_id);
						$user_access_edit.=$fetch_access1->acc_name.' , ';
						//echo 'vv'.$photo = $photo_id;
					}
					else
					{
						$user_access_edit.='';
					}
				}
			}
						 
	    $i++;
		$get = $this->channel_model->get_username($users->user_id);
		/*$get_access = explode(',',$users->access);
		// print_r($get_access);die;
		$user_access='';
  	    foreach($get_access as $getacc)
		{
			// echo $getacc;
			$fetch_access = $this->channel_model->fetch_access($getacc);
			$user_access.=$fetch_access->acc_name.' , ';
		}*/
		 ?>
<tr>
	<td><?php echo $i; ?></td>
	<td><?php echo $get->user_name; ?></td>
    <td><?php echo $get->spass; ?></td>
    <!--<td><?php //echo $get->email_address; ?></td>-->
	<td> 
<ul class="manage_pro list-unstyled">
<?php if($user_access_view!='')
{
?>
<li> <div>View :</div>
<?php 

     echo trim($user_access_view,' , ');
?>
</li>
<?php } ?>
<?php if($user_access_edit!='')
{
?>
<li> <div>Edit :</div>
<?php 
     echo trim($user_access_edit,' , ');
?>
</li>
<?php } ?></ul>
</td>
 <td><?php if($get->status=='0') { ?> <span class="label label-sm label-danger">In-active</span> <?php }elseif($get->status=='1') { ?> <span class="label label-sm label-success">Active</span> <?php } ?></td>

<td>
	<a href="javascript:;" onclick="return edit_user('<?php echo $users->priviledge_id; ?>');"> <i class="fa fa-pencil" title="Edit this user"></i></a> &nbsp;
    
    <a href="<?php echo lang_url();?>channel/users_status/<?php echo insep_encode($users->priviledge_id).'/'.insep_encode($users->user_id);?>" class="">
    <?php if($get->status=='0') { ?>
    <i class="fa fa-check-square-o" title="Active this user"> </i>
    <?php } else { ?>
    <i class="fa fa-ban" title="In-active this user"> </i>
    <?php } ?>
    </a> &nbsp;
	
    <a href="<?php echo lang_url();?>channel/users_delete/<?php echo insep_encode($users->priviledge_id).'/'.insep_encode($users->user_id);?>" class=""> <i class="fa fa-trash-o text-danger" title="Delete this user" custom="<?php echo $i;?>"> </i></a>
	
    <!-- Edit User-->
	

 </td>	
</tr>
   <?php }  } else { ?>
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
<?php } ?>
<!-- Users End -->

<!-- Users Edit start -->

<div class="modal fade dialog-2 " id="user_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">
  
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
	  
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update User</h4>
      </div>
	  
      <div class="modal-body sign-up-m">
      <div class="row">
		<form role="form" class="form-horizontal cls_mar_top" method="post" action="<?php echo lang_url();?>channel/users_update" enctype="multipart/form-data" name="user_edits" id="user_edits">
		  <div class="col-md-12" id="edit_users">
			
		  </div>
	  
		</form>
	   
     </div>
	 
	</div>
	 
    </div>
      
    </div>
  
  </div>
</div>

<!-- user edit -->

</div>
</div>
</div>
</div>
<?php 
if($action=='manage_property') {?>

<div class="modal fade dialog-2 " id="edit_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update Property</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
   
  <form role="form" class="form-horizontal cls_mar_top" name="edit_property_forms" id="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update" enctype="multipart/form-data">
  
  <div id="edit_property_modal">
  </div>  
  </form>
  
   
   </div>
   
   
      </div>
      
    </div>
  
  </div>
</div>

<div class="modal fade dialog-2 " id="view_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">View Property</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
   
  <form role="form" class="form-horizontal cls_mar_top" name="edit_property_forms" id="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update" enctype="multipart/form-data">
  
  <div id="view_property_modal">
  </div>  
  </form>
   
   </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade dialog-2 " id="add_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add Property</h4>
      </div>
      <div class="modal-body sign-up-m">
    <div class="row">
	
		<form role="form" class="form-horizontal cls_mar_top" name="add_property_form" id="add_property_form" method="post" action="<?php echo lang_url();?>channel/manage_property/add" enctype="multipart/form-data">
  
	<div id="add_property_modal">
  </div>  
  </form>
   </div>
      </div>
      
    </div>
  
  </div>
</div>
<?php } ?>
