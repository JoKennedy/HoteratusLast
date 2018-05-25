<div class="container-fluid pad_adjust  mar-top-30">
	<form class="" action="<?php echo lang_url();?>inventory/bulk_update/update" method="post">
	<input type="hidden" value="<?php echo $redirect_url?>" name="redirect_url" id="redirect_url" />
    <div class="row mar-bot30">
    <div class="col-md-3 col-sm-5 col-xs-12 cls_resp50">
		
		<div class="cls_bluk_sidebar">
		<h4>Channels </h4>
		<div class="clearfix mar-bot-20">
        <ul class="list-unstyled cls_bluk_list mar-no pad-no">
			<li class="col-md-7 col-sm-6 pad-no">
				<div class="cls_bulk_checkbox">
				<input type="checkbox" value="main" name="maincal" checked class="channel_update styled" id="checkbox16">
				<label for="checkbox16"> All (Main Calendatr) </label>
				</div>
			</li>
		<?php
		if(count($con_cha)!=0) 
		{
			$i=0;
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
			?>		
					<li class="col-md-5 col-sm-6 pad-no">
						<div class="cls_bulk_checkbox">
							<input id="channel_single_<?php echo $i;?>" class="styled channel_single channel_update" checked type="checkbox" name="channel_id[]" value="<?php echo $channel_id;?>">
							<label for="channel_single_<?php echo $i++;?>"> <?php echo $channel_name;?></label>
						</div>
					</li>
		<?php 	}
			} 
		}
		else 
		{  
		?>
		<li class="col-md-5 col-sm-6 pad-no">
			<div class="cls_bulk_checkbox">
				<label for="checkbox17"> No connected channel data found...</label>
			</div>
		</li>
		<?php 
		} 
		?>
        </ul>
        </div>
        <h4>What do you want to update?</h4>
        <ul class="list-unstyled cls_bluk_list">
			<li>
            <div class="cls_bulk_checkbox">
				<input type="checkbox" class="update_option styled" value="availability" id="availability" class="all_fun" name="updatevalue[]">
				 <label for="availability"> Availability </label>
            </div>
			</li>
			<li>
            <div class="cls_bulk_checkbox">
  			  <input type="checkbox" class="update_option styled" value="price" id="price" class="all_fun" name="updatevalue[]">
              <label for="price"> Price </label>
            </div>
			</li>
			<li>
            <div class="cls_bulk_checkbox">
          	  <input type="checkbox" class="update_option styled" value="minimum_stay" id="minimum_stay" class="all_fun" name="updatevalue[]">
              <label for="minimum_stay"> Minimum stay </label>
            </div>
			</li>
			<li>
            <div class="cls_bulk_checkbox">
       		  <input type="checkbox" class="update_option styled" value="cta" id="cta" class="all_fun" name="updatevalue[]">
              <label for="cta"> CTA ( Y - Yes / N - No ) </label>
            </div>
			</li>
			<li>
            <div class="cls_bulk_checkbox">
			  <input type="checkbox" class="update_option styled" value="ctd" id="ctd" class="all_fun" name="updatevalue[]">
              <label for="ctd"> CTD ( Y - Yes / N - No ) </label>
            </div>
			</li>
			<li>
            <div class="cls_bulk_checkbox">
			  <input type="checkbox" class="update_option styled" value="stop_sell" id="stop_sell" class="all_fun" name="updatevalue[]">
              <label for="stop_sell"> Stop sell </label>
            </div>
          </li>
          <li>
            <div class="cls_bulk_checkbox">
			  <input type="checkbox" class="update_option styled" value="open_room" id="open_room" class="all_fun" name="updatevalue[]">
              <label for="open_room"> Open Rooms </label>
            </div>
          </li>
        </ul>
        <div class="clearfix"> </div>
        <h4>Dates</h4>
		<?php
			$curr_date = date('d/m/Y');
			$date = strtotime("+7 day");
			$add_date = date('d/m/Y', $date);
		?>
		
		<div class="form-group">
		<label class="col-sm-4 control-label">Start date </label>
		<div class="col-sm-8">
			<input type="text" class="form-control widh" id="dp1" required name="start_date">
		</div>
		</div>
		<div class="clearfix"> </div>
		<div class="form-group">
		<label class="col-sm-4 control-label">End date </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control widh" id="dp1-p" required name="end_date">
		</div>
		</div>
		
		<div class="clearfix"> </div>
        <ul class="list-unstyled cls_bluk_list mar-no col-md-6 col-sm-6 col-xs-12">
		<li>
			<div class="cls_bulk_checkbox" style="margin-left: -20px">
			  <input id="bulk_days" class="styled" type="checkbox" checked id="">
			  <label for="bulk_days"> All </label>
			</div>
		</li>
		<?php 
		$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Fridays','Saturday');
		$values = array('1','2','3','4','5','6','7');
		$i=0;
		foreach($days as $key=>$day)
		{
		?>
		<li>
            <div class="cls_bulk_checkbox">
              <input id="checkbox<?php echo $i;?>" checked type="checkbox" name="days[]" class="bulk_days styled" value="<?php echo $values[$key]?>">
              <label for="checkbox<?php echo $i++;?>"> <?php echo $day;?> </label>
            </div>
		</li>
   
		<?php } ?>
 
        </ul>
        <div class="clearfix"> </div>
      </div>
    </div>
	<div class="col-md-9 col-sm-7 col-xs-12 cls_resp50">
	<?php 
	if($this->session->flashdata('bulk_success')!='')
	{
	?>
		<div class="alert alert-success">
		<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
		<?php echo $this->session->flashdata('bulk_success');?>
		</div>
	<?php 
	}
	elseif($this->session->flashdata('bulk_error')!='')
	{
	?>
		<div class="alert alert-danger">
		<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
		<?php echo $this->session->flashdata('bulk_error');?>
		</div>
	<?php 
	} 
	?>
		<?php if(count($bulk_room)!=0) {  ?>
		<div class="cls_bulk_upda">
		<h4>Room Name</h4>
		<div class="cls_bulk_in">
        <div class="table-responsive">
			<table class="table table-striped">           
            <tbody>
			<?php
			foreach($bulk_room as $room) { 
			extract($room);
			if($non_refund==1)
			{
			  $members = $member_count+$member_count;
			}
			else
			{
			  $members = $member_count;
			}
			?>
            <tr>
                <td><h3><?php echo ucfirst($property_name);?></h3>
                  <p><?php if($pricing_type==1) { echo 'Room based pricing';} else if($pricing_type=='2'){ echo 'Guest based pricing';}?></p>
				</td>
				
				<td class="price">
				<div class="input-group in-p">
    			  <span class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></span>
			      <input class="form-control price_value" placeholder="Price" type="text" id="room_<?php echo $property_id;?>" onkeyup="get_other_amount(this.value,this.id)" name="room[<?php echo $property_id;?>][price]">
			    </div>
				</td> 
				
				<td class="availability">
				<div class="input-group in-p">
    			  <span class="input-group-addon"><i class="fa fa-bed"></i></span>
			      <input class="form-control avail_value" placeholder="Availability" type="text" id="exampleInputAmount" name="room[<?php echo $property_id;?>][availability]">
			    </div>
				</td> 
				
				<td class="minimum_stay">
				<div class="input-group in-p">
    			  <span class="input-group-addon"><i class="fa fa-moon-o"></i></span>
			      <input class="form-control minimum_value" placeholder="Minimum stay" type="text" name="room[<?php echo $property_id;?>][minimum_stay]">
			    </div>
				</td> 
				
				<td class="cta">          
                <ul class="list-inline">
                <li>
                <center> <label> <strong>CTA</strong> </label> </center>
                <div class="cls_comradio">
				<label>
				<input type="radio" value="1" name="room[<?php echo $property_id;?>][cta]" title="Yes" class="cta_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				Y
				</label>
				</div>
				</li>
				<li> 
				<div class="cls_comradio">
				<label>
				<input type="radio" value="2" name="room[<?php echo $property_id;?>][cta]" title="No" class="cta_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				N
				</label>
				</div>                 
				</li>
                </ul>
				</td>
				<td class="ctd">              
				<ul class="list-inline">
				<li >
				<center> <label> <strong>CTD</strong> </label> </center>
				<div class="cls_comradio">
				<label>
				<input type="radio" name="room[<?php echo $property_id;?>][ctd]" value="1" title="Yes" class="ctd_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				Y
				</label>
				</div>
				</li>
				<li > 
				<div class="cls_comradio">
				<label>
				<input type="radio" name="room[<?php echo $property_id;?>][ctd]" value="2" title="No" class="ctd_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				
				</label>
				</div>                 
				</li>
				</ul>
                </td>
				<td>              
				<ul class="list-inline">
				<li class="stop_sell">
				<div class="checkbox clip-check check-primary cls_checkcont">
				<center>
				<input  class="stopsell stop_value" type="checkbox" id="room_<?php echo $property_id;?>_stop_sell" name="room[<?php echo $property_id;?>][stop_sell]" value="1">
				<label for="room_<?php echo $property_id;?>_stop_sell" class="cls_commlabel">Stop sell</label>
				</center>
				</div>
				</li>
				<li class="open_room">
				<div class="checkbox clip-check check-primary cls_checkcont">
				<center>
				<input  class="openroom open_value" type="checkbox" id="room_<?php echo $property_id;?>_open_room" name="room[<?php echo $property_id;?>][open_room]" value="1">
				<label for="room_<?php echo $property_id;?>_open_room" class="cls_commlabel">Open Rooms</label>
				</center>
				</div>
				</li>
				</ul>
                </td>   
            </tr>
			<?php  
			if($pricing_type==1 && $non_refund==1)
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
				<td class="cta">          
				<ul class="list-inline">
				<li>
				<center> <label> <strong>CTA</strong> </label> </center>
				<div class="cls_comradio">
				<label>
				<input type="radio" value="1" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="Yes" class="cta_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				Y
				</label>
				</div>
				</li>
				<li> 
				<div class="cls_comradio">
				<label>
				<input type="radio" value="2" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="No" class="cta_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				N
				</label>
				</div>                 
				</li>
				</ul>
				</td>
				<td class="cta">          
				<ul class="list-inline">
				<li>
				<center> <label> <strong>CTD</strong> </label> </center>
				<div class="cls_comradio">
				<label>
				<input type="radio" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="1" title="Yes" class="ctd_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				Y
				</label>
				</div>
				</li>
				<li> 
				<div class="cls_comradio">
				<label>
				<input type="radio" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="2" title="No" class="ctd_value">
				<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
				
				</label>
				</div>                 
				</li>
				</ul>
				</td>	
				<td>              
				<ul class="list-inline">
				<li class="stop_sell">
				<div class="checkbox clip-check check-primary cls_checkcont">
				<center>
				<input	class="stopsell stop_value" type="checkbox" id="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][stop_sell]" value="1">
				<label for="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" class="cls_commlabel">Stop sell</label>
				</center>
				</div>
				</li>
				<li class="open_room">
				<div class="checkbox clip-check check-primary cls_checkcont">
				<center>
				<input class="openroom open_value" type="checkbox" id="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" name="sub_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][open_room]" value="1">
				<label for="sub_room_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" class="cls_commlabel">Open Rooms</label>
				</center>
				</div>
				</li>
				</ul>
                </td>
			</td>
			</tr>
			<?php 
			}?>
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

				if($CountPerNight =  $this->db->query(' select count(*) as Total from roommapping where owner_id ="'.user_id().'" AND hotel_id="'.hotel_id().'"
					and property_id ="'.$property_id.'" and rate_id ="'.$rate_type_id.'" and chargetype =1')){
					$CountPerNight = $CountPerNight->row()->Total;
				}else{
					$CountPerNight = 0;
				}

				if($CountPerPerson =  $this->db->query(' select count(*) as Total from roommapping where owner_id ="'.user_id().'" AND hotel_id="'.hotel_id().'"
					and property_id ="'.$property_id.'" and rate_id ="'.$rate_type_id.'" and chargetype =2')){
					$CountPerPerson = $CountPerPerson->row()->Total;
				}else{
					$CountPerPerson = 0;
				}

				if($MaxPerPerson = $this->db->query(' select max(Adults) as Adults, max(children) as children, max(infants) as infants, 
					max(extra_adult) as extraadults, max(extra_child) as ExtraChildren, max(extra_infants) as extrainfant from roommapping where owner_id ="'.user_id().'" AND hotel_id="'.hotel_id().'"
					and property_id ="'.$property_id.'" and rate_id ="'.$rate_type_id.'" and chargetype =2')){
					$MaxPerPerson = $MaxPerPerson->row();
				}


					
    
?>
<tr>
<td><div class="g-top2"><b><?php echo $ratename;?></b><br/>
<span class="font-it"><?php if($CountPerNight >=1) { echo 'Room based pricing';} else if($CountPerNight ==0){ echo 'Guest based pricing';}?></span></div></td>

<?php if ($CountPerNight >=1)  { ?>
<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>
  <?php } else { ?> 
<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <label type="Text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td> 

<?php } ?>


<td class="availability"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control avail_value" placeholder="Availability" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][availability]">
    </div></div></td>
    
<td class="minimum_stay"><div class="g-top"><div class="input-group in-p">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" id="exampleInputAmount" class="form-control minimum_value" placeholder="Minimum stay" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][minimum_stay]">
    </div></div></td>
    
<td class="cta">
<ul class="list-inline">
<li>
<center> <label> <strong>CTA</strong> </label> </center>
<div class="cls_comradio">
<label>
<input type="radio" value="1" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][cta]" title="Yes" class="cta_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
Y
</label>
</div>
</li>
<li> 
<div class="cls_comradio">
<label>
<input type="radio" value="2" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][cta]" title="No" class="cta_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
N
</label>
</div>                 
</li>
</ul>
</td>
<td class="ctd">              
<ul class="list-inline">
<li >
<center> <label> <strong>CTD</strong> </label> </center>
<div class="cls_comradio">
<label>
<input type="radio" value="1" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][ctd]" title="No" class="ctd_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
Y
</label>
</div>
</li>
<li > 
<div class="cls_comradio">
<label>
<input type="radio" value="2" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][ctd]" title="No" class="ctd_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>

</label>
</div>                 
</li>
</ul>
</td>
<td>
<ul class="list-inline">
<li class="stop_sell">
<div class="checkbox clip-check check-primary cls_checkcont">
<center>
<input  class="stopsell stop_value" type="checkbox" id="rate_<?php echo $property_id.'_'.$rate_type_id;?>_stop_sell" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell]" value="1">
<label for="rate_<?php echo $property_id.'_'.$rate_type_id;?>_stop_sell" class="cls_commlabel">Stop sell</label>
</center>
</div>
</li>
<li class="open_room">
<div class="checkbox clip-check check-primary cls_checkcont">
<center>
<input  class="openroom open_value" type="checkbox" id="rate_<?php echo $property_id.'_'.$rate_type_id;?>_open_room" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][open_room]" value="1">
<label for="rate_<?php echo $property_id.'_'.$rate_type_id;?>_open_room" class="cls_commlabel">Open Rooms</label>
</center>
</div>
</li>
</ul>
</td>
</tr>

<?php if($CountPerPerson>=1) { 
	for ($i=1; $i <= $MaxPerPerson->Adults ; $i++) { 
		?>

<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' '.$i.' Adults';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "$i A" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>

<?php 

} 
for ($i=1; $i <= $MaxPerPerson->children ; $i++) { ?>

<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' '.$i.' Children';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "$i C" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>
<?php }
for ($i=1; $i <= $MaxPerPerson->infants ; $i++) { 

		?>
<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' '.$i.' Infants';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "$i I" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>

<?php }
if ($MaxPerPerson->extraadults == 1) { ?>

<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' Extra Adult';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "Ext. A" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>
<?php }
if ($MaxPerPerson->ExtraChildren == 1) { ?>

<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' Extra Children';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "Ext. C" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>
<?php }
if ($MaxPerPerson->extrainfant == 1) { ?>

<tr class="price">
<td><div class="g-top2"><b><?php echo $ratename.' Extra Infant';?></b><br/>
<span class="font-it"> Guest based pricing </span></div></td>

<td class="price"><div class="gree g-top"><div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" id="rate_<?php echo $uniq_id;?>_<?php echo $property_id;?>_<?php echo $pricing_type;?>" class="form-control price_value" placeholder="Price <?php echo "Ext. I" ?>" name="rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][price]">
    </div></div></td>

</tr>

<?php }
}
//aqui Trabajo lo de precio por personas


if($pricing_type==1 && $non_refund==1)
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
	
<td class="cta">
<ul class="list-inline">
<li>
<center> <label> <strong>CTA</strong> </label> </center>
<div class="cls_comradio">
<label>
<input type="radio" class="cta_value" value="1" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="Yes">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
Y
</label>
</div>
</li>
<li> 
<div class="cls_comradio">
<label>
<input type="radio" class="cta_value" value="2" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][cta]" title="No">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
N
</label>
</div>                 
</li>
</ul>
</td>

<td class="ctd">
<ul class="list-inline">
<li>
<center> <label> <strong>CTA</strong> </label> </center>
<div class="cls_comradio">
<label>
<input type="radio" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" title="Yes" class="ctd_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
Y
</label>
</div>
</li>
<li> 
<div class="cls_comradio">
<label>
<input type="radio" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][ctd]" value="2" title="No" class="ctd_value">
<span class="cr"><i class="cr-icon fa fa-circle"></i></span>
N
</label>
</div>                 
</li>
</ul>
</td>

<td>
<ul class="list-inline">
<li class="stop_sell">
<div class="checkbox clip-check check-primary cls_checkcont">
<center>
<input   class="stopsell stop_value" type="checkbox" id="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][stop_sell]" value="1">
<label for="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_stop_sell" class="cls_commlabel">Stop sell</label>
</center>
</div>
</li>
<li class="open_room">
<div class="checkbox clip-check check-primary cls_checkcont">
<center>
<input class="openroom open_value" type="checkbox" id="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" name="sub_rate[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][<?php echo $v;?>][<?php echo $refun;?>][open_room]" value="1">
<label for="sub_rate_<?php echo $property_id;?>_<?php echo $v;?>_<?php echo $refun;?>_open_room" class="cls_commlabel">Open Rooms</label>
</center>
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
}
?>
            </tbody>
          </table>
		  
        </div>
        </div>
          <div class="clearfix">
            <div class="cls_bulk_btn pull-right">
			  <button type="submit" class="cls_com_blubtn" id="update">Update</button>
				<button type="reset" class="cls_com_yelbtn">Reset</button>
            </div>
          </div>
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
	</div>
	</form>
	<?php $this->load->view('channel/dash_sidebar'); ?>
</div>