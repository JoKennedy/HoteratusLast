<div class="dash-b4-n">
<div class="row-fluid clearfix">

<div style="padding-right:0;" class="col-md-12 col-sm-12">

<div class="dash-b2 map-b2">
<div id="exp_succ">
</div>
<div class="row">

<div class="col-md-12">
<?php if($this->session->flashdata('map_insert')!='')
{
	?>
<div class="alert alert-success" align="center"> <?php echo $this->session->flashdata('map_insert');?> <button aria-label="Close" data-dismiss="alert" class="close" type="button"> <span aria-hidden="true">&times; </i></span></button></div>
<?php } ?>

<?php if($this->session->flashdata('map_remove')!='')
{
	?>
<div class="alert alert-warning" align="center"> <?php echo $this->session->flashdata('map_remove');?> <button aria-label="Close" data-dismiss="alert" class="close" type="button"> <span aria-hidden="true">&times; </i></span></button></div>
<?php } ?>

<?php if($this->session->flashdata('map_update')!='')
{
	?>
<div class="alert alert-info" align="center"> <?php echo $this->session->flashdata('map_update');?> <button aria-label="Close" data-dismiss="alert" class="close" type="button"> <span aria-hidden="true">&times; </i></span></button></div>
<?php } ?>

<?php if($this->session->flashdata('import_rate_error')!= ""){ ?>
<div class="alert alert-warning" align="center"> <?php echo $this->session->flashdata('import_rate_error');?> <button aria-label="Close" data-dismiss="alert" class="close" type="button"> <span aria-hidden="true">&times; </i></span></button></div>
<?php } ?>
<?php if($this->session->flashdata('import_success')!= ""){ ?>
<div class="alert alert-success" align="center"> <?php echo $this->session->flashdata('import_success');?> <button aria-label="Close" data-dismiss="alert" class="close" type="button"> <span aria-hidden="true">&times; </i></span></button></div>
<?php } ?>


<h3>Channel Connection Configuration</h3></div>
</div>

<?php
	if($User_Type=='2')
	{
		if(in_array('6',user_edit()))
		{
			?>
		<a id="import_rates" class="btn btn-default pull-right" channel_id="<?php echo insep_encode($channel_id);?>" href="javascript:;" role="button"><i class="fa fa-download"></i> Import room rate information from channel</a>

	 <?php
		}
	}
	if($User_Type=='1' || (admin_id()!='' && admin_type()=='1'))
	{
	?> 
		<a id="import_rates" class="btn btn-default pull-right" channel_id="<?php echo insep_encode($channel_id);?>" href="javascript:;" role="button"><i class="fa fa-download"></i> Import room rate information from channel</a>
<?php } ?>
<?php
if($User_Type=='1' || admin_id()!='' && admin_type()=='1' || ($User_Type=='2' && in_array('6',user_edit())))
{
?>
<h4> <?php echo $channeldetails['channel_name'];?> Room Mapping</h4>
<br>
<?php if(isset($import_need)=='') { ?>
<form class="form-horizontal" method="post" name="set_roommap" id="set_roommap" action="<?php echo lang_url();?>mapping/save_mapping" onsubmit="return validate();">
<input type="hidden" value="mapping_id" id="mapping_id" name="mapping_insert" />
<div class="row">

<div class="col-md-12 col-sm-12">	
<?php
if(($channel_id)=='1')
{
	if(count($expedia)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='11')
{
	if(count($reconline)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='2')
{
	if(count($booking)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='8')
{
	if(count($gta)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='5')
{
	if(count($hotelbeds)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='17')
{
	if(count($bnow)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='15')
{
	if(count($travel)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
elseif(($channel_id)=='14')
{
	if(count($wbeds)=='0')
	{
		$mapp_rooms_details = "already_mapped";
	}
	else{ $mapp_rooms_details = ""; }
}
else
{
	$mapp_rooms_details = "";
}
if($mapp_rooms_details!='already_mapped')
{
?>

<div class="col-md-12 col-sm-12">
<div class="room_form">

<?php
	if(user_type()==1 || admin_id()!='' && admin_type()=='1')
	{
		$user_ids = user_id();
	}
	else if(user_type()==2)
	{
		$user_ids = owner_id();
	}
	$propertyid="";
	$enabled="";
  	$rate_id="";
	$included_occupancy="";
	$extra_adult="";
	$extra_child="";
	$single_guest="";
	$rate_conversion = "";
?>

 
  
  </div>
  
	<label for="exampleInputName2"></label>
	
</div>
</div>

<div class="col-md-12">
<div class="form-group col-sm-2">
<label for="exampleInputName2"><?php echo $channeldetails['channel_name'].' '.'Rooms :';?></label>
  
  </div>
 
<div class="form-group col-sm-3">

<select class="form-control" id="import_mapping_id" name="import_mapping_id">
<?php
if(($channel_id)=='1')
{
	if(count($expedia)!=0)
	{
		foreach($expedia as $ex_val)
		{
			if($ex_val->name!='')
			{
				echo '<option value="'.$ex_val->map_id.'">'.$ex_val->roomtype_name.' - '.$ex_val->roomtype_id.' - '.$ex_val->distributionModel.' - '.$ex_val->name.'</option>';
				
			}
			else
			{
				echo '<option value="'.$ex_val->map_id.'">'.$ex_val->roomtype_name.'</option>';
			}
		}
	}
}
elseif(($channel_id)=='11')
{
	if(count($reconline)!=0)
	{
		foreach($reconline as $re_val)
		{
			echo '<option value="'.$re_val->re_id.'">'.$re_val->CODE.' - '.$re_val->IDROOM.'</option>';
		}
	}
}
elseif(($channel_id)=='2')
{
	if(count($booking)!=0)
	{
		foreach($booking as $bk_val)
		{
			if($bk_val->B_rate_id==0)
			{
				echo '<option value="'.$bk_val->import_mapping_id.'">'.$bk_val->room_name.'</option>';
			}
			else
			{
				echo '<option value="'.$bk_val->import_mapping_id.'">'.$bk_val->room_name.' - '.$bk_val->rate_name.'</option>';
			}
		}
	}
}
elseif(($channel_id)=='8')
{
	if(count($gta)!=0)
	{
		foreach($gta as $gt_val)
		{
			echo '<option value="'.$gt_val->GTA_id.'">'.$gt_val->RoomType.' - '.$gt_val->Description.'-'.$gt_val->ID.'('.$gt_val->rateplan_code.'/'.$gt_val->RateBasis.')'.'-Occupancy('.$gt_val->MaxOccupancy.')-'.$gt_val->contract_type.'</option>';
		}
	}
}
elseif(($channel_id)=='5')
{
	if(count($hotelbeds)!=0)
	{
		foreach($hotelbeds as $re_val)
		{
			echo '<option value="'.$re_val->map_id.'">'.$re_val->contract_name.' - '.$re_val->roomtype.' - '.$re_val->characterstics.' - '.$re_val->sequence.'</option>';
		}
	}
}
elseif(($channel_id)=='17')
{
	if(count($bnow)!=0)
	{
		foreach($bnow as $re_val)
		{
			echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->RoomTypeName.' - '.$re_val->RateTypeName.'</option>';
		}
	}
}
elseif(($channel_id)=='15')
{
	if(count($travel)!=0)
	{
		foreach($travel as $re_val)
		{
			echo '<option value="'.$re_val->map_id.'">'.$re_val->Description.'</option>';
		}
	}
}
elseif(($channel_id)=='14')
{
	if(count($wbeds)!=0)
	{
		foreach($wbeds as $re_val)
		{
			echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->nameRoomType.' ( '.$re_val->codeRate.' : '.$re_val->nameRoomFeature.' : '.$re_val->boardCodeBase.' : '.$re_val->maximumPaxes.' : '.$re_val->supportSaleSystem.' : '.$re_val->boardCodeBase.' ) </option>';
		}
	}
}

else
{
?>
<option value="" > No rooms are imported </option>
<?php	
}
?>
</select>	
</div>
  
<div class="form-group col-sm-7">
  	
    <label class="col-sm-4 control-label" for="inputPassword3">Channel Manager Room : </label>
	
	 <div class="form-group col-sm-6">
     
     <input type="hidden" value="" name="guest_count" id="guest_count"/>
	 <input type="hidden" value="" name="refun_type" id="refun_type"/>
     <input type="hidden" value="" id="rate_type" name="rate_type" />
     <input type="hidden" name="channel_id" value="<?php echo insep_encode($channel_id); ?>">
     <input type="hidden" name="room_ids" value="" id="room_ids">
     
     <select class="form-control channel_manager_room" id="channel_manager_room" name="channel_manager_room">
<?php
if($not_channel_details)
{
	foreach ($not_channel_details as $nc)
	{
		if($nc->non_refund==1)
		{
			$members = $nc->member_count+$nc->member_count;
		}
		else
		{
			$members = $nc->member_count;
		}
?>
<option value="<?php echo $nc->property_id;?>" guest_count="0" refun="0" mapp_type="main_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php echo $nc->property_name;?> </option>
<?php 
if($nc->pricing_type==2) 
{
	if($nc->non_refund==1)
	{
		for($k=1;$k<=$members;$k++)
		{
		
			if($nc->member_count < $members)
			{
				if($k%2 == 0)
				{
					$name = 'Guest Non refundable';
					$v = ceil($k/2);
					$refun = '2';
				}
				else
				{
					$name = 'Guest';
					$v = ceil($k/2);
					$refun = '1';
				}
			}
			else
			{
				$name = 'Guest';
				$v = $k;
				$refun = '1';
			}
?>

<option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?> </option>

<?php 	
		}
	}
	else
	{
		for($k=1;$k<=$members;$k++)
		{
			
			if($nc->member_count < $members)
			{
				if($k%2 == 0)
				{
					$name = 'Guest Non refundable';
					$v = ceil($k/2);
					$refun=2;
				}
				else
				{
					$name = 'Guest';
					$v = ceil($k/2);
					$refun=1;
				}
			}
			else
			{
				$name = 'Guest';
				$v = $k;
				$refun=1;
				
			}
	?>
    <option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?> </option>
    
<?php 
		}
	}
}
else if($nc->pricing_type==1 && $nc->non_refund==1)
{
	$v=1;
	$refun=2;
?>
<option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php echo ucfirst($nc->property_name);?> - Non refundable </option>

<?php
}
?>
<?php 
		$rate_types = $this->mapping_model->get_rate_types($nc->property_id);
		if($rate_types)
		{
			foreach($rate_types as $rate)
			{

 ?>
 
 <option value="<?php echo $rate->rate_type_id;?>" guest_count="0" refun="0" mapp_type="main_rate" property="<?php echo insep_encode($nc->property_id);?>"> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?></option>
 
<?php 

if($rate->pricing_type==2) 
{
	if($rate->non_refund==1)
	{
		for($k=1;$k<=$members;$k++)
		{
		
			if($nc->member_count < $members)
			{
				if($k%2 == 0)
				{
					$name = 'Guest Non refundable';
					$v = ceil($k/2);
					$refun = '2';
				}
				else
				{
					$name = 'Guest';
					$v = ceil($k/2);
					$refun = '1';
				}
			}
			else
			{
				$name = 'Guest';
				$v = $k;
				$refun = '1';
			}

?>
<option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - <?php echo $v.' '.$name;?> </option>
<?php 
		}
	}
	else
	{
		for($k=1;$k<=$members;$k++)
		{
			
			if($nc->member_count < $members)

			{
				if($k%2 == 0)
				{
					$name = 'Guest Non refundable';
					$v = ceil($k/2);
					$refun=2;
				}
				else
				{
					$name = 'Guest';
					$v = ceil($k/2);
					$refun=1;
				}
			}
			else
			{
				$name = 'Guest';
				$v = $k;
				$refun=1;
				
			}

	?>
    <option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - <?php echo $v.' '.$name;?> </option>
<?php 

		}
	}
}
else if($rate->pricing_type==1 && $rate->non_refund==1)
{
	$v=1;
	$refun=2;
?>
<option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>"> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - Non refundable </option>

<?php
}
?>
<?php 		}
		}?>

<?php } 
}
?>
</select>
 
 </div>
 
	
  
  </div>
 </div> 
 </div> 
<div class="row room-rate-map">
<div class="col-md-12"><h3>Room Rate Mapping</h3></div>

<div class="col-md-12">
<h4>Settings<div class="i-cir pull-right"></div></h4>

<div class="room_form">

<div class="form-group">
<label class="col-sm-3 control-label" for="inputPassword3">Enabled</label>
<div class="col-sm-6">
<label class="radio-inline">
<input type="radio" value="enabled" checked="checked" id="inlineRadio2" name="optionenable"> Enabled
</label>
<label class="radio-inline">
<input type="radio" value="disabled" id="inlineRadio3" name="optionenable"> Disabled
</label>
</div>
</div>

<?php
if($channel_id==17)
{
	$add_class = 'bnow_map';
?>

<div class="form-group">
<label class="col-sm-3 control-label" for="inputPassword3">Price Type</label>
<div class="col-sm-6">
<label class="radio-inline">
<input type="radio" value="BRP" checked="checked" id="price_type1" name="price_type" class="price_type"> Base Room Price
</label>
<label class="radio-inline">
<input type="radio" value="OBP" id="price_type2" name="price_type" class="price_type"> Occupancy Based Price
</label>
</div>
</div>

<?php
}
else
{
	$add_class = '';
}
?>

<?php 
	$form_details = $channeldetails['mapping_requirements'];
	if($form_details!="")
	{
?> 

<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Rate Conversion Multiplier  <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $rate_conversion; ?>">
</div>
<div class="col-sm-5">
<label for="exampleInputName2">Optional Decimal number Cannot be lower than 0.5</label>
</div>
</div>

<?php 
	$full_value=explode(",",$form_details);
	if($channel_id=='17')
	{
		$arr_count = count($full_value);
	}
	$i=0;
	foreach($full_value as $val)
	{
		$field_split=explode("-",$val);
		if($channel_id=='17')
		{
			if($i+1 == $arr_count-1 || $i+1 == $arr_count)
			{
				$add_class = '';
			}
		}
?>
	<div class="form-group <?php echo $add_class;?>">
	<label class="col-sm-3 control-label" for="inputEmail3"><?php echo trim($field_split[0]);?>  <i class="fa fa-exclamation-circle" title="<?php echo $field_split[1];?>"></i></label>
	<input type="hidden" value="<?php echo trim($field_split[1]);?>" name="title_<?php echo trim($field_split[0]);?>" />
	<div class="col-sm-4">
	<input type="text" name="<?php echo trim($field_split[0]);?>" placeholder="" id="<?php echo trim($field_split[0]);?>" class="form-control" value="">
	</div>
	<div class="col-sm-5">
	<label for="exampleInputName2"><?php echo $field_split[1];?></label>
	</div>
	</div>
<?php
	$i++;
	}
}
else if($channel_id != 5 && $channel_id != 8 && $channel_id != 14)
{
?>

<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Rate Conversion Multiplier  <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $rate_conversion;?>">
</div>
<div class="col-sm-5">
<label for="exampleInputName2">Optional Decimal number Cannot be lower than 0.5</label>
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Included Occupancy  <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" name="included_occupancy" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $included_occupancy;?>">
</div>
<div class="col-sm-5">
<label for="exampleInputName2"></label>
</div>
</div>
  
<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Extra Adult rate  <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" value="<?php echo $extra_adult;?>" name="extra_adult" placeholder="" id="inputEmail3" class="form-control">
</div>
<div class="col-sm-5">
<label for="exampleInputName2"></label>
</div>
</div>
  
<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Extra Child Rate <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" value="<?php echo $extra_child;?>" name="extra_child" placeholder="" id="inputEmail3" class="form-control">
</div>
<div class="col-sm-5">
<label for="exampleInputName2"></label>
</div>
</div>
  
<div class="form-group">
<label class="col-sm-3 control-label" for="inputEmail3">Single guest count  <i class="fa fa-exclamation-circle"></i></label>
<div class="col-sm-4">
<input type="text" value="<?php echo $single_guest;?>" name="single_guest" placeholder="" id="inputEmail3" class="form-control">
</div>
<div class="col-sm-5">
<label for="exampleInputName2"></label>
</div>
</div>
<?php
}else{
?>
<div class="form-group">
    <label class="col-sm-3 control-label" for="inputEmail3">Rate Conversion Multiplier  <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-4">
      <input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $rate_conversion;?>">
    </div>
    <div class="col-sm-5">
      <label for="exampleInputName2">Optional Decimal number Cannot be lower than 0.5</label>
    </div>
  </div>
<?php }?>
<?php  if($channel_id == 1){ ?>
<div class="form-group">
	<label class="col-sm-3 control-label" for="inputPassword3">Option</label>
		<div class="col-sm-6">
			<label class="radio-inline">
				<input type="radio"  value="room" id="room_level" name="levelmapp"> Room Level
			</label>
			<label class="radio-inline">
				<input type="radio" checked="checked" value="rate" id="rate_levl" name="levelmapp"> Rate Level
			</label>
		</div>
</div>
<?php } ?>
	
  
<div class="form-group">
	<label class="col-sm-3 control-label" for="inputPassword3">Update</label>
		<div class="col-sm-6">
			<label class="radio-inline">
				<input type="checkbox" value="1" checked="checked" id="rate" name="rate"> Rate
			</label>
			<label class="radio-inline">
				<input type="checkbox" value="1" checked="checked" id="availabilites" name="availabilites"> Availabilities
			</label>
		</div>
</div>

<hr>
<div align="center"> 
<button type="button" id="save_clicking" class="btn btn-info text-center"> Save </button> 
</div>
	 
</div>
</div>
</div>
<?php } else {  ?> <div class="alert alert-info" align="center"> All rooms are mapped!!!</div><?php } ?>
</form>
<?php } else { ?> <div class="alert alert-warning" align="center"> <?php echo $import_need;?></div><?php } 
}
?>
<div class="row">
<div class="col-md-12 col-sm-12">
<h4>Currently mapped to <?php echo $channeldetails['channel_name'];?>
<div class="i-cir pull-right"></div></h4>

   <ul class="list-unstyled">

    <?php
	if(user_type()==1 || admin_id()!='' && admin_type()=='1')
	{
		$user_ids = user_id();
	}
	else if(user_type()==2)
	{
		$user_ids = owner_id();
	}
	
	if(count($channel_details)!=0)
    {
    foreach($channel_details as $details)
	{
		extract($details);
		if($property_id!='' && $rate_id==0)
		{
			$property_name = get_data(TBL_PROPERTY,array('owner_id'=>$user_ids,'hotel_id'=>hotel_id(),'property_id'=>$property_id,'status'=>'Active'))->row()->property_name;
		}
		if($property_id!='' && $rate_id!=0)
		{
			$property_name = get_data(RATE_TYPES,array('user_id'=>$user_ids,'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_type_id'=>$rate_id))->row();
			
			if($property_name->rate_name!='') { $property_name = ucfirst($property_name->rate_name); } else { $property_name = '#'. $property_name->uniq_id;}
		}
		if($property_id!='' && $rate_id==0 && $guest_count!='' && $refun_type!='')
		{
			if($refun_type=='1')
			{
			   $property_name=$property_name.'-'.$guest_count.' Guest';
			}
			elseif($refun_type=='2')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest Non refundable';
			}
		}
		if($property_id!='' && $rate_id!='0' && $guest_count!='' && $refun_type!='')
		{
			if($refun_type=='1')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest';
			}
			elseif($refun_type=='2')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest Non refundable';
			}
		}

    ?>

    <?php

    $update_date = get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row()->download_date;

    if($update_date=="")

    {
		
       $update_date="NULL";

    }

    ?>
	
    
 
	<li>
    	<div class="col-md-5 pull-left">
	 	 <?php echo $property_name;?> 
         <p class="text-<?php echo $enabled =='enabled' ? 'success' : 'danger';?>"> <?php echo ucfirst($enabled);?></p>
         <p> <small>Mapped to <?php echo $channeldetails['channel_name'];?> room rate <i class="fa fa-long-arrow-right fa-1"> </i> 
         <em> 
		 <?php 
		 if($channel_id=='1')
		 {
		 	$map_room = get_data('import_mapping',array('map_id'=>$import_mapping_id))->row();
			if($map_room->name!='')
			{
				echo $map_room->roomtype_name.' - '.$map_room->roomtype_id.' - '.$map_room->distributionModel.' - '.$map_room->name;
			}
			else
			{
				echo $map_room->roomtype_name;
			}
			 
		 }
		 else if($channel_id=='11')
		 {
			$map_room = get_data(IM_RECO,array('re_id'=>$import_mapping_id))->row(); echo $map_room->CODE.' - '.$map_room->IDROOM; 
		 }
		 else if($channel_id=='2')
		 {
			$map_room = get_data(BOOKING,array('import_mapping_id'=>$import_mapping_id))->row();
			if($map_room->B_rate_id==0)
			{
				echo $map_room->room_name; 
			}
			else
			{
				echo $map_room->room_name.' - '.$map_room->rate_name; 
			}
		 }
		  else if($channel_id=='8')
		 {
			$map_room = get_data(IM_GTA,array('GTA_id'=>$import_mapping_id))->row(); 
			
				echo $map_room->RoomType.' - '.$map_room->Description.'-'.$map_room->ID.'('.$map_room->rateplan_code.'/'.$map_room->RateBasis.')-Occupancy ('.$map_room->MaxOccupancy.')-'.$map_room->contract_type;  

		 }
		 else if($channel_id=='5')
		 {
			$map_room = get_data("import_mapping_HOTELBEDS_ROOMS",array('map_id'=>$import_mapping_id))->row();
			
			echo $map_room->contract_name.' - '.$map_room->roomtype.' - '.$map_room->characterstics.' - '.$map_room->sequence; 
		 }
		 else if($channel_id=='17')
		 {
			$map_room = get_data(IM_BNOW,array('import_mapping_id'=>$import_mapping_id))->row(); echo $map_room->RoomTypeName.' - '.$map_room->RateTypeName; 
		 }
		 else if($channel_id=='15')
		 {
			$map_room = get_data(IM_TRAVELREPUBLIC,array('map_id'=>$import_mapping_id))->row();
			 echo $map_room->Description; 
		 }
		 else if($channel_id=='14')
		 {
			$map_room = get_data(IM_WBEDS,array('import_mapping_id'=>$import_mapping_id),'import_mapping_id, nameRoomType, codeRate, nameRoomFeature , boardCodeBase, maximumPaxes, supportSaleSystem, boardCodeBase')->row();
			
			echo $map_room->nameRoomType.' ( '.$map_room->codeRate.' : '.$map_room->nameRoomFeature.' : '.$map_room->boardCodeBase.' : '.$map_room->maximumPaxes.' : '.$map_room->supportSaleSystem.' : '.$map_room->boardCodeBase.' ) '; 
		 }
		 ?>
         
         </em></small></p>
          </div>
          <div class="col-md-7 pull-right">
          <?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()) || admin_id()!='' && admin_type()=='1') { ?>
          <div class="col-md-6">
          <div class="pull-right del-map"><p><i class="fa fa-close"></i><a href="<?php echo lang_url();?>mapping/remove_map/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id).'/'.secure($property_id);?>"> Delete Mapping </a><i class="fa fa-pencil"></i>

    <a href="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id);?>/update"> Update Mapping </a></p></div>
	      </div>
          <?php } ?>
    	  <div class="room_rate col-md-6">
		  <?php
		if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit()))
		{
			if($channel_id=='17')
			{
				$ctr	=	'bnow';
				$mp		=	$import_mapping_id;
			}
			else if($channel_id == '15')
			{
				$ctr	=	'travelrepublic';
				$mp		=	$import_mapping_id;
			}else if($channel_id == '14')
			{
				$ctr	=	'wbeds';
				$mp		=	$import_mapping_id;
			}else{
				$ctr	=	'mapping';
				$mp		=	'';
			}
			?>
          <p>
          <?php if($channel_id != 2 && $explevel!='OBP'){ ?>
          <a role="button" href="<?php echo lang_url().$ctr?>/importRates/<?php echo insep_encode($channel_id).'/'.insep_encode($property_id).'/'.$rate_id.'/'.$guest_count.'/'.$refun_type.'/'.$mp;?>" class="btn btn-default pull-right" title="Last import <?php echo $update_date;?>"><i class="fa fa-download"></i> Import Rates </a>
          <?php } ?>
           <a role="button" href="<?php echo lang_url().$ctr?>/importAvailabilities/<?php echo insep_encode($channel_id).'/'.insep_encode($property_id).'/'.$rate_id.'/'.$guest_count.'/'.$refun_type.'/'.$mp;?>" class="btn btn-default pull-right" title="Last import <?php echo $update_date;?>"><i class="fa fa-download"></i> Import Availabilities </a>
           </p>
        <!--<p class="pull-left"> Last import <?php //echo $update_date;?> </p>  -->
            <?php
		
		}
?>
    </div>
    </div>
	</li>


	
    
    <input type="hidden" name="property_ids" value="<?php echo $property_id; ?>">

    <input type="hidden" name="channel_ids" value="<?php echo $channel_id; ?>">

    <?php

    }

    }

    else

    {

    echo "<div class='alert alert-danger' align='center'>No rooms are mapped!!!</div>";

    }

    ?>
    </ul>

</div>	
</div>

</div>
               
</div>
</div>
</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/jquery.min.js"></script> 

<script type="text/javascript">
$(document).ready(function(){
$('.bnow_map').hide();
	var room_name=$( "#room_name option:selected" ).text();
	var room_id=$( "#room_name option:selected" ).val();
 /* var select_rate = $("#room_name option:selected").text();
  $('#select_rate').html(select_rate);*/
	$('#select_room').html(room_name);
	$('#room_id').val(room_id);
$.validator.addMethod('positiveNumber',
function(value) {
    return Number(value) > 0;
}, 'Enter a positive number.');

jQuery.validator.addMethod("lettersonly", 
function(value, element) {
	 return this.optional(element) || /^[a-z,""]+$/i.test(value);
}, "Letters only please");

$.validator.addMethod("customemail", 
	function(value, element) {
		return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
	}, 
	"Sorry, I've enabled very strict email validation"
);
$('#set_roommap').validate({
rules:
{
included_occupancy:
{
	required:true,
    number:true

},
extra_adult:
{
	required:true,
    number:true

},
extra_child:
{
	required:true,
    number : true

},
single_guest:
{
	required:true,
    number:true
},
		
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
$('#save_clicking').click(function(){
   if($('#set_roommap').valid()){
    $('#set_roommap').submit();
   }
});
});
function set_room(val){
	var room_name=$( "#room_name option:selected" ).text();
	var room_id=$( "#room_name option:selected" ).val();
	$('#room_ids').val(room_id);
	$('#select_room').html(room_name);
	$('#room_ids').val(room_id);
}

 $('#room_name').change(function(){
  // alert('hi hello');
    var room_name=$('#room_name').val();
    // alert(room_name);
    if(room_name)
    {
     $.ajax({

     type: "POST",

     url: "<?php echo lang_url(); ?>mapping/get_rate_types",

     data: {'val':room_name},

     beforeSend : function()

     {

       $('#rate_type').html('<option>loading..</option>');

     },

     success: function(msg)

     {

      // alert(msg);

       $('#rate_type').html(msg);

     }

    });

    }

    });

function validate() {

	var checkbox1 = document.getElementById('rate').checked;
    var checkbox2 = document.getElementById('availabilites').checked;

    if ((checkbox1 || checkbox2 ) == false) 
	{
            alert("Please Select Atleast one checkbox value");
            return false;
    } 
}

</script>

