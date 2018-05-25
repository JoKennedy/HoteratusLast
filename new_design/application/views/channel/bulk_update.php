<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n">
  <h4><a href="<?php echo lang_url();?>inventory/advance_update">Calendar</a> <i class="fa fa-angle-right"> </i><i class="fa fa-angle-right"> </i> Bulk Updates </h4>
</div>
</div>
</div> 
</div>
<div class="">
<div class="row-fluid clearfix bg_gray">
<?php 
if($this->session->flashdata('bulk_success')!='')
{
?>
    <div class="alert alert-success">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
    <?php echo $this->session->flashdata('bulk_success');?>
    </div>
<?php }elseif($this->session->flashdata('bulk_error')!='')
{
?>
    <div class="alert alert-danger">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
    <?php echo $this->session->flashdata('bulk_error');?>
    </div>
<?php } ?>
<form class="" action="<?php echo lang_url();?>inventory/bulk_update/update" method="post">
<input type="hidden" value="<?php echo $redirect_url?>" name="redirect_url" id="redirect_url" />
<div class="col-md-3 col-sm-3 new-k new-bg">
<b>What do you want to update? <?php $currency = $hotel_detail['currency']; ?> </b>
 <div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="availability" id="availability" class="all_fun" name="updatevalue[]">
    Availability
  </label>
</div> 
<div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="price" id="price" class="all_fun" name="updatevalue[]">
    Price
  </label>
</div> 
<div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="minimum_stay" id="minimum_stay" class="all_fun" name="updatevalue[]">
    Minimum stay
  </label>
</div>
<div class="checkbox">  
  <label>
    <input type="checkbox" class="update_option" value="cta" id="cta" class="all_fun" name="updatevalue[]">
    CTA <b>( Y - Yes / N - No )</b>
  </label>
</div> 
<div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="ctd" id="ctd" class="all_fun" name="updatevalue[]">
    CTD <b>( Y - Yes / N - No )</b>
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="stop_sell" id="stop_sell" class="all_fun" name="updatevalue[]">
    Stop sell
  </label>
</div>  

<div class="checkbox">
  <label>
    <input type="checkbox" class="update_option" value="open_room" id="open_room" class="all_fun" name="updatevalue[]">
    Open Rooms
  </label>
</div>

<br/>
<b>Dates</b>  

<?php
$curr_date = date('d/m/Y');
$date = strtotime("+7 day");
$add_date = date('d/m/Y', $date);
?>
<div class="row top-3">
<div class="col-md-6 col-sm-6"><span class="ne-n">Start date:</span></div>
<div class="col-md-6 col-sm-6">
  <div class="form-group">
    <input type="text" class="form-control widh" value="" id="dp1" required name="start_date">
  </div>
 </div>
</div>
<div class="row top-3">
<div class="col-md-6 col-sm-6"><span class="ne-n">End date:</span></div>
<div class="col-md-6 col-sm-6">
  <div class="form-group">
    <input type="text" class="form-control widh" value="" id="dp1-p" required name="end_date">
  </div>
</div>
</div>
<br/>
<!--<input type="hidden" value="" name="days[]" />-->
 
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="bulk_days">
    All
  </label>
</div>
</div>
<?php 
    $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $values = array('1','2','3','4','5','6','7');
    foreach($days as $key=>$day)
    {
  ?>
  <div class="checkbox">
    <label>
      <input type="checkbox" checked name="days[]" class="bulk_days" value="<?php echo $values[$key]?>"><?php echo $day?> 
    </label>
  </div>
  <?php } ?>

</div>

<div class="col-md-7 col-sm-7 new-k4" style="padding-left:0; padding-right:0;">

<?php if(count($bulk_room)!=0) {  ?>

<div class="table table-responsive table-bordered tab-srin">
<table class="table table-striped">
<thead>
<tr>
<th style="width:130px;" class="text-center">Room name</th>
<th style="width:130px;" class="text-center price">Rate </th>
<th style="width:130px;" class="text-center availability">Availability</th>
<th style="width:130px;" class="text-center minimum_stay">Minimum stay </th>
<th class="text-center restrictions">Restrictions</th>
</tr>
</thead>
<tbody>
<?php foreach($bulk_room as $room) { 
extract($room);
if($non_refund==1)
{
  $members = $member_count+$member_count;
}
else
{
  $members = $member_count;
}?>
<tr>
<td><div class="g-top2"><b><?php echo ucfirst($property_name);?></b><br/>
<span class="font-it"><?php if($pricing_type==1) { echo 'Room based pricing';} else if($pricing_type=='2'){ echo 'Guest based pricing';}?></span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" onkeyup="get_other_amount(this.value,this.id)" id="room_<?php echo $property_id;?>"class="form-control price_value" placeholder="Price" name="room[<?php echo $property_id;?>][price]">
    </div></div></td>
    
<td class="availability"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control avail_value" placeholder="Availability" name="room[<?php echo $property_id;?>][availability]">
    </div></div></td>
    
<td class="minimum_stay"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control minimum_value" placeholder="Minimum stay" name="room[<?php echo $property_id;?>][minimum_stay]">
    </div></div></td>
    
<td>
<ul class="list-inline">
<li class="cta">
<label>CTA</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

<input type="radio" value="1" name="room[<?php echo $property_id;?>][cta]" title="Yes" class="cta_value">

<input type="radio" value="2" name="room[<?php echo $property_id;?>][cta]" title="No" class="cta_value">
</div>
</li>
<li class="ctd"><label>CTD</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

      <input type="radio" name="room[<?php echo $property_id;?>][ctd]" value="1" title="Yes" class="ctd_value">
    <input type="radio" name="room[<?php echo $property_id;?>][ctd]" value="2" title="No" class="ctd_value">

  </div>
</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input  class="stopsell stop_value" type="checkbox" id="room_<?php echo $property_id;?>_stop_sell" name="room[<?php echo $property_id;?>][stop_sell]" value="1">
    </label>
  </div>
</li>

<li class="open_room"><label>Open Rooms</label>
<div class="checkbox">
    <label>
      <input  class="openroom open_value" type="checkbox" id="room_<?php echo $property_id;?>_open_room" name="room[<?php echo $property_id;?>][open_room]" value="1">
    </label>
  </div>
</li>
</ul>
</td>
</tr>
<?php
if($pricing_type==2) 
{
  if($non_refund==1)
  {
    for($k=1;$k<=$members;$k++)
    {
      if($member_count < $members)
      {
          if($k%2 == 0)
          {
            $name = 'Guest Non refundable';
            $v = ceil($k/2);
            $refun = '2';
            $col_name = 'non_refund_amount';
            
          }
          else
          {
            $name = 'Guest';
            $v = ceil($k/2);
            $refun = '1';
            $col_name = 'refund_amount';
            
          }
        }
      else
      {
          $name = 'Guest';
          $v = $k;
          $refun = '1';
          $col_name = 'refund_amount';
          
        }
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="sroom_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>" class="form-control price_value" placeholder="Price" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability"><!--<div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control " placeholder="Availability" name="room[<?php //echo $property_id;?>][availability]">
    </div></div>--></td>
    
<td class="minimum_stay"><!--<div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control" placeholder="Minimum stay" name="room[<?php //echo $property_id;?>][minimum_stay]">
    </div></div>--></td>
    
<td>
<!--<ul class="list-inline">
<li class="cta">
<label>CTA</label>

<div class="checkbox">
    <label>
      <input type="checkbox" value="1" name="room[<?php //echo $property_id;?>][cta]"> Yes / No
    </label>
  </div>

</li>
<li class="ctd"><label>CTD</label>
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php //echo $property_id;?>][ctd]" value="1"> Yes / No
    </label>
  </div>
</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php //echo $property_id;?>][stop_sell]" value="1"> Yes / No
    </label>
  </div>
</li>
</ul>-->

</td>
</tr>
<?php
    }
  }
  else
  {
    for($k=1;$k<=$members;$k++)
    {
      if($member_count < $members)
      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun=2;
          $col_name = 'non_refund_amount';
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun=1;
          $col_name = 'refund_amount';
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun=1;
        $col_name = 'refund_amount';
        
      }
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?></span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="sroom_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>" class="form-control price_value" placeholder="Price" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability">
<!--<div class="g-top"><div class="input-group in-p">
<div class="input-group-addon"><i class="fa fa-bed"></i></div>
  <input type="text" id="exampleInputAmount" class="form-control " placeholder="Availability" name="room[<?php //echo $property_id;?>][availability]">
</div>
</div>-->
</td>
    
<td class="minimum_stay">
<!--<div class="g-top"><div class="input-group in-p">
<div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
<input type="text" id="exampleInputAmount" class="form-control" placeholder="Minimum stay" name="room[<?php //echo $property_id;?>][minimum_stay]">
</div>
</div>-->
</td>
    
<td>
<!--<ul class="list-inline">
<li class="cta">
<label>CTA</label>

<div class="checkbox">
    <label>
      <input type="checkbox" value="1" name="room[<?php //echo $property_id;?>][cta]"> Yes / No
    </label>
  </div>
</li>
<li class="ctd"><label>CTD</label>
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php //echo $property_id;?>][ctd]" value="1"> Yes / No
    </label>
  </div>

</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php //echo $property_id;?>][stop_sell]" value="1"> Yes / No
    </label>
  </div>
</li>
</ul>-->

</td>
</tr>
<?php
    }
  }
}
else if($pricing_type==1 && $non_refund==1)
{
  $v=1;
  $refun=2;
  $col_name = 'non_refund_amount';
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo ucfirst($property_name);?> - Non refundable</span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text"  id="sroom_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>" class="form-control price_value" placeholder="Price" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control avail_value" placeholder="Availability" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][availability]">
    </div></div></td>
    
<td class="minimum_stay"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control minimum_value" placeholder="Minimum stay" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][minimum_stay]">
    </div></div></td>
    
<td>
<ul class="list-inline">
<li class="cta">
<label>CTA</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">
<input type="radio" value="1" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="Yes" class="cta_value">

<input type="radio" value="2" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="No" class="cta_value">
</div>
</li>
<li class="ctd"><label>CTD</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

      <input type="radio" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="1" title="Yes" class="ctd_value">
    <input type="radio" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="2" title="No" class="ctd_value">

  </div>
</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input   class="stopsell stop_value" type="checkbox" id="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][stop_sell]" value="1">
    </label>
  </div>
<!--<select class="form-control" name="room[<?php //echo $property_id;?>][stop_sell]">
  <option value="0">-</option>
  <option value="1">Yes</option>
  <option value="2">No</option>
</select>-->
</li>

<li class="open_room"><label>Open Rooms</label>
<div class="checkbox">
    <label>
      <input class="openroom open_value" type="checkbox" id="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][open_room]" value="1">
    </label>
  </div>
<!--<select class="form-control" name="room[<?php //echo $property_id;?>][stop_sell]">
  <option value="0">-</option>
  <option value="1">Yes</option>
  <option value="2">No</option>
</select>-->
</li>
</ul>


</td>
</tr>
<?php 
}
?>
<?php
if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
{ 
  $all_rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id,'user_id'=>user_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->result_array();
}
else if(user_type()=='2' )
{ 
  $all_rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id,'user_id'=>owner_id(),'hotel_id'=>hotel_id(),'droc'=>'1'))->result_array();
}
if(count($all_rate_types)!=0)
{
  foreach($all_rate_types as $rate_types)
  {
    extract($rate_types);
    
    if($rate_name!='')
    {
      $ratename=ucfirst($rate_name);
    }
    else
    {
      $ratename='#'.$uniq_id;
    }
    
    if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
    {
      $all_rates = get_data(RATE_TYPES_REFUN,array('room_id'=>$property_id,'uniq_id'=>$uniq_id,'user_id'=>user_id(),'hotel_id'=>hotel_id()))->result_array();
    }
    else if(user_type()=='2')
    {
      $all_rates = get_data(RATE_TYPES_REFUN,array('room_id'=>$property_id,'uniq_id'=>$uniq_id,'user_id'=>owner_id(),'hotel_id'=>hotel_id()))->result_array();
    }
    
?>
<tr>
<td><div class="g-top2"><b><?php echo $ratename;?></b><br/>
<span class="font-it"><?php if($pricing_type==1) { echo 'Room based pricing';} else if($pricing_type=='2'){ echo 'Guest based pricing';}?></span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>
    
<td class="availability"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control avail_value" placeholder="Availability" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][availability]">
    </div></div></td>
    
<td class="minimum_stay"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control minimum_value" placeholder="Minimum stay" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][minimum_stay]">
    </div></div></td>
    
<td>
<ul class="list-inline">
<li class="cta">
<label>CTA</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

<input type="radio" value="1" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][cta]" title="Yes" class="cta_value">

<input type="radio" value="2" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][cta]" title="No" class="cta_value">
</div>
</li>
<li class="ctd"><label>CTD</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">
<input type="radio" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][ctd]" value="1" title="Yes" class="ctd_value">
<input type="radio" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][ctd]" value="2" title="No" class="ctd_value">
</div>
</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input  class="stopsell stop_value" type="checkbox" id="rate_<?php echo $property_id;?>_stop_sell" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell]" value="1">
    </label>
  </div>
</li>

<li class="open_room"><label>Open Rooms</label>
<div class="checkbox">
    <label>
      <input  class="openroom open_value" type="checkbox" id="rate_<?php echo $property_id;?>_open_room" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][open_room]" value="1">
    </label>
  </div>
</li>
</ul>
</td>
</tr>

<?php
if($pricing_type==2) 
{
  if($non_refund==1)
  {
    for($k=1;$k<=$members;$k++)
    {
      if($member_count < $members)
      {
          if($k%2 == 0)
          {
            $name = 'Guest Non refundable';
            $v = ceil($k/2);
            $refun = '2';
            $col_name = 'non_refund_amount';
            
          }
          else
          {
            $name = 'Guest';
            $v = ceil($k/2);
            $refun = '1';
            $col_name = 'refund_amount';
            
          }
        }
      else
      {
          $name = 'Guest';
          $v = $k;
          $refun = '1';
          $col_name = 'refund_amount';
          
        }
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo $ratename;?> - <?php echo $v.' '.$name;?> </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="srate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_<?php echo $uniq_id;?>" class="form-control price_value" placeholder="Price" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability"></td>
    
<td class="minimum_stay"></td>
    
<td>
</td>
</tr>
<?php
    }
  }
  else
  {
    for($k=1;$k<=$members;$k++)
    {
      if($member_count < $members)
      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun=2;
          $col_name = 'non_refund_amount';
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun=1;
          $col_name = 'refund_amount';
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun=1;
        $col_name = 'refund_amount';
        
      }
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo $ratename;?> - <?php echo $v.' '.$name;?></span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="srate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_<?php echo $uniq_id;?>" class="form-control price_value" placeholder="Price" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability">
</td>
    
<td class="minimum_stay">
</td>
    
<td>
</td>
</tr>
<?php
    }
  }
}
else if($pricing_type==1 && $non_refund==1)
{
  $v=1;
  $refun=2;
  $col_name = 'non_refund_amount';
?>
<tr>
<td><div class="g-top2"><span class="font-it"><?php echo $ratename;?> - Non refundable</span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text"  id="srate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_<?php echo $uniq_id;?>" class="form-control" placeholder="Price" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $col_name?>]">
    </div></div></td>
    
<td class="availability"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control avail_value" placeholder="Availability" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][availability]">
    </div></div></td>
    
<td class="minimum_stay"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control minimum_value" placeholder="Minimum stay" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][minimum_stay]">
    </div></div></td>
    
<td>
<ul class="list-inline">
<li class="cta">
<label>CTA</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

<input type="radio" class="cta_value" value="1" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="Yes">

<input type="radio" class="cta_value" value="2" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="No">
</div>

</li>
<li class="ctd"><label>CTD</label>
<p style="margin-bottom: 2px;"> Y &#160; N </p> 
<div class="clearfix">

      <input type="radio" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" title="Yes" class="ctd_value">
    <input type="radio" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="2" title="No" class="ctd_value">

  </div>
</li>
<li class="stop_sell"><label>Stop sell</label>
<div class="checkbox">
    <label>
      <input   class="stopsell stop_value" type="checkbox" id="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][stop_sell]" value="1">
    </label>
  </div>
</li>

<li class="open_room"><label>Open Rooms</label>
<div class="checkbox">
    <label>
      <input class="openroom open_value" type="checkbox" id="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][open_room]" value="1">
    </label>
  </div>
</li>
</ul>


</td>
</tr>
<?php 
}
?>
<?php 
  }
}
?>
<?php } ?>

</tbody>
</table>
</div>

<div class="room3" align="center">
<button type="submit" class="btn btn-primary" id="update">Update</button>
<button type="reset" class="btn btn-warning">Reset</button>
</div>

<?php } else { ?>

<div class="">
<br>
<div class="reser"><center><i class="fa fa-calendar"></i></center></div>
<h2 class="text-center">You don't have any room types yet</h2>
<p class="pad-top-20 text-center"><!--You can manage reservations coming from all channels, or you can add reservations manually--></p>
<br>
<div class="res-p"><center><a class="btn btn-primary" href="<?php echo lang_url();?>channel/manage_rooms"><i class="fa fa-plus"></i> Add room type </a></center></div>
</div>

<?php } ?>
  
</div>

<div class="col-md-2 col-sm-2 new-k3 new-bg">
<b>Channels</b>
<!--<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="channel_all">
     All 
  </label>
</div>-->
<div class="checkbox">
<label>
  <input type="checkbox" value="main" name="maincal" checked class="channel_update"> All ( Main Calendar )
</label>
</div>      
<?php
if(count($con_cha)!=0) 
{
	foreach($con_cha as $connected)
	{ 
		extract($connected);
		if($channel_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row()->xml_type;
		}
		else	
		{
			$chk_allow='';
		}
		if(($channel_id==2 && ($chk_allow==2 || $chk_allow==3))||$channel_id!=2)
		{
	?>		<div class="checkbox">
			  <label>
				<input type="checkbox" checked class="channel_single channel_update" name="channel_id[]" id="channel_single" value="<?php echo $channel_id;?>">
				  <?php echo $channel_name;?>
			  </label>
			</div>
<?php 	}
	} 
}
else 
{  
?>
<br /> <br />
<div class="close-i"><i class="fa fa-close"></i> No connected channel data found...</div>
<?php 
} 
?>
</div>
</form>
</div>
</div>