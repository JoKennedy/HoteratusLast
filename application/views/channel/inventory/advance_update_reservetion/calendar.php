<style>

.booking_label label {
    color: #222222;
    display: inline-block;
    font-size: 14px;
    font-weight: 500;
    line-height: 22px;
    padding-left: 12px;
    position: relative;
    text-transform: initial;
    vertical-align: middle;
}


.booking_label label::before {  
    content: "";
    display: inline-block;
    height: 16px;
    left: 0;
    margin-left: -20px;
    position: absolute;
    top: 3px;
    transition: border 0.15s ease-in-out 0s, color 0.15s ease-in-out 0s;
    width: 16px;
    margin-right:3px;
	border-radius: 50%;
}

.booking_label label::after {
    color: #51cbfb;
    display: inline-block;
    font-size: 10px;
    height: 9px;
    left: 0;
    line-height: 9px;
    margin-left: -19px;
    padding-left: 3px;
    padding-top: 0;
    position: absolute;
    top: 6px;
    vertical-align: middle;
    width: 9px;
    margin-right:3px;
}

.booking_reserved label::before{
	 background:<?php echo $this->theme_customize['b_reserved']!='' ? $this->theme_customize['b_reserved']:'#180cff' ?>;
}

.booking_confirmed label::before{
	 background:<?php echo $this->theme_customize['b_confirmed']!='' ? $this->theme_customize['b_confirmed']:'#180cff' ?>;
}

.booking_checkin label::before{
	 background:<?php echo $this->theme_customize['b_canceled']!='' ? $this->theme_customize['b_canceled']:'#180cff' ?>;
}

.booking_checkout label::before{
	 background:<?php echo $this->theme_customize['b_pending']!='' ? $this->theme_customize['b_pending']:'#180cff' ?>;
}

.booking_cta label::before{
	 background: #f9c372;
}
.booking_ctd label::before{
	 background: #93c6ff;
}


.booked3{
  background:<?php echo $this->theme_customize['b_reserved']!='' ? $this->theme_customize['b_reserved']:'#180cff' ?>;
  
}

.booked1{
  background:<?php echo $this->theme_customize['b_confirmed']!='' ? $this->theme_customize['b_confirmed']:'#ff135a' ?>;
}

.booked2{
background: <?php echo $this->theme_customize['b_canceled']!='' ? $this->theme_customize['b_canceled']:'#fff' ?>; 
}

.booked4{
  background:<?php echo $this->theme_customize['b_pending']!='' ? $this->theme_customize['b_pending']:'#180cff' ?>;
  
}
.booked{
background: #366092; 
}

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
   autoclose: true;
}


.contents6::before {
	content:none;
}
.contents5::before {
	content:none;
}
.contents6::after{
	content:none;
}
.contents5:after{
	content:none;
}
.contents6::before {
	border-color: rgba(255, 255, 255, 0);
    border-bottom-color: rgba(255, 255, 255, 0);
	border-bottom-color: #fff;
	border-width: 10px;
    left: 95% !important;
}
.contents5::before {
	border-color: rgba(255, 255, 255, 0);
    border-bottom-color: rgba(255, 255, 255, 0);
	border-bottom-color: #fff;
	border-width: 10px;
    left: 95% !important;
}
</style>


<div class="content">
<div class="">
<div class="row">
<div class="dash-b-n1 new-s">
	<div class="row-fluid clearfix">
		<div class="col-md-12 col-sm-12 new-k2">
			<div class="dash-b-n2 mar-top-30">
				<div class="row">
					<div class="col-md-2 col-sm-2">
					<label class="switch">
						<input class="switch-input reservationyes" type="checkbox" name="on-offswitch" id="myon-offswitch" checked>
						<span class="switch-label" data-on="Channel" data-off="Main"></span> 
						<span class="switch-handle"></span> 
					</label>
					<input type="hidden" name="cur_month" id="next_month" value="<?php echo $nextMonthDate->format('d/m/Y');?>" />
					<input type="hidden" name="cur_month" id="prev_month" value="<?php echo $prevMonthDate->format('d/m/Y');?>" />
					</div>
						<!--<div class="col-md-2 col-sm-2">
						<div class="">
							<div class="on-2">
								<div class="on-offswitch">
									<input type="checkbox" name="on-offswitch" class="on-offswitch-checkbox reservationyes" id="myon-offswitch" checked>
									<label class="on-offswitch-label" for="myon-offswitch">
										<span class="on-offswitch-inner reservationyes"></span>
										<span class="on-offswitch-switch"></span>
									</label>
								</div>
							</div>
							
						</div>
						</div>-->
					<div class="col-md-2 col-sm-2 bor-o">
						<a class="btn btn-default" href="javascipt:;" id="show_pop" role="button" data-toggle="modal" data-target="#myModal-p" data-backdrop="static" data-keyboard="false">Customize calendar</a>
					</div>
					<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1') { ?>
						<div class="col-md-2 col-sm-2 org">
							<a class="btn btn-warning main_full_update_modal" href="javascript:;" role="button">Full Update</a><!--  myModal-p2-->
						</div>
					<?php } ?>
					<div class="col-md-3 col-sm-3 dr">
						
						<a href="javascript:;" class="pull-left mar-right prev_month "><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
						<div class="dropdown pull-left">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu_item1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<?php echo $startDate->format('F Y');?>	
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="ajax_cal" style="height: 300px;overflow-y: auto;">
								<?php

								$start    = (new DateTime(date('Y-m-d', strtotime('-12 month'))))->modify('first day of this month');
								$end      = (new DateTime($endMonthDate->format('Y-m-d')))->modify('first day of next month');
								$interval = DateInterval::createFromDateString('1 month');
								$monthPeriod   = new DatePeriod($start, $interval, $end);

								foreach( $monthPeriod as $date ) { ?>
									<li class="<?php if($startDate->format('m-y')==$date->format('m-y')) { ?>active<?php } ?> change_month"custom="<?php echo $date->format('d/m/Y');?>">
										<a href="javascript:;" ><?php echo $date->format('F Y'); ?></a>
									</li>
								<?php } ?>
							</ul>
						</div>
						<div class="dropdown pull-left"></div>
						<a href="javascript:;" class="pull-left mar-left next_month"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>
						<a class="btn btn-lg btn-default" href="#" onclick="$.post('change_month',{'nr_pr_date':'<?php echo '01/'.date('m/Y');?>'}, function(resp){$('.change_month_replace').html(resp);});" role="button"><i class="fa fa-calendar"></i></a>
					</div>
					<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1') {?>
						<div class="col-md-3 col-sm-3 bor-o">
							<a class="btn btn-default" href="<?php echo site_url('inventory/bulk_update/reservation_yes');?>" role="button">Bulk Update</a>
							<button type="button" data-toggle="modal" data-target="#myModal2"  class="btn btn-default" style="color:white;background:blue" color="White"> Add Reservation </button> 
						</div>
					<?php } ?>
				</div>
			</div>
			<div id="customize_date">
				<input type="hidden" name="cal_start" id="cal_start" value="<?php echo $startDate->format('d/m/Y'); ?>" />
				<input type="hidden" name="cal_end" id="cal_end" value="<?php echo $endDate->format('d/m/Y'); ?>"/>
				<div id="resp_div" style="display: none"></div> 
				<form id="main_cal">
					<input type="hidden" name="alter_checkbox" id="alter_checkbox" />
					<input type="hidden" name="alter_checkbox_rate" id="alter_checkbox_rate" />
					<input type="hidden" id="show_ss" name="show_ss" value="0" />
					<div class="">
						<div class="table table-responsive">
							<table class="table table-bordered table_stricky " id="reservation_yes_tbl">
								<thead>
									<tr>
										<th width="400" align="left"></th>
										<?php foreach( $months as $month => $colspan ) { ?>
											<th colspan="<?php echo $colspan;?>" class="text-center tal_td_bor">
												<h3><strong><?php echo $month; ?></strong></h3>
											</th>
										<?php } ?>
									</tr>
									<tr>
										<td width="400" bgcolor="#3ac4fa"></td>
										<td width="7" bgcolor="#3ac4fa">&nbsp;</td>
										<?php foreach($period as $date) { ?>
											<td width="28" bgcolor="<?php echo ($date->format("D")=='Sat' || $date->format("D")=='Sun') ? "#366092" : "#3ac4fa";?>"><?php echo $date->format("d")?> <br> <?php echo $date->format("D")?></td>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach($properties as $property) { ?>
									<tr class="p-b-o">
										<td rowspan="3" class="ha2 ss_main_row" width="400">
											<a
												href="javascript:;"
												class="show_e"
												onClick="toggle_visibility('contents_<?php echo $property->property_id;?>','show_plus_<?php echo $property->property_id;?>');"
											>
												<span class="pull-left text-info"><strong><?php echo ucfirst($property->property_name);?></strong></span>
												<?php if( $property->pricing_type == 2 || ( $property->pricing_type == 1 && $property->non_refund == 1 ) || count($property->rate_types) ) { ?>
													<i class="fa fa-plus show_plus_<?php echo $property->property_id;?>"></i>
												<?php } ?>
									   	</a>
								   	</td>
										<td bgcolor="#a6a6a6">P</td>
										<?php
										foreach($period as $date)
										{
											$separate_date = $date->format("d/m/Y");
											
											$update = isset($property->updates[$separate_date]) ? $property->updates[$separate_date] : null;
											
											if( $update )
											{
											?>
												<td>
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_price editable editable-click"
															data-type="number"
															data-name="price"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Price"
														>
															<?php echo floatval($update->price); ?>
														</a>
													<?php	} else { ?>
														<?php echo floatval($update->price); ?>
													<?php	} ?>
												</td>
											<?php	
											}
											else
											{
												?>
												<td>
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_price editable editable-click"
															data-type="number"
															data-name="price"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Price"
														>
															<?php echo 'N/A'; ?>
														</a>
													<?php	} else { ?>
														<?php echo 'N/A'; ?>
													<?php	} ?>
												</td>
												<?php
											}
										}
										?>
									</tr>
									<tr class="p-b-o">
										<td bgcolor="#a6a6a6">A</td>
										<?php 
										foreach($period as $date)
										{
											$separate_date = $date->format("d/m/Y");
											
											$update = isset($property->updates[$separate_date]) ? $property->updates[$separate_date] : null;
											
											$color = ($date->format("D")=='Sat' || $date->format("D")=='Sun') ? '#669933' : '#D9E4C3';
											$color_class = ($date->format("D")=='Sat' || $date->format("D")=='Sun') ? 'tabl_add' : 'tabl_even';
											
											if( $update )
											{
												/* if($update->availability==0)
												{
													$color = '#FF0000';
												}
												elseif($update->stop_sell==1)
												{
													$color = '#FF0000';
												}
												elseif($update->availability<0)
												{
													$color = '#F4C327';
												} */
												if($update->cta==1)
												{
													$color = '#f9c372';
												}
												if($update->ctd==1)
												{
													$color = '#93c6ff';
												}
												if($update->availability==0 || $update->stop_sell==1)
												{
													$color = '#FF0000';
												}
												if($update->availability==0)
												{
													$color = '#FF0000';
												}
												elseif($update->stop_sell==1)
												{
													$color = '#CC99FF';
												}
												elseif($update->availability < 0)
												{
													$color	= '#F4C327';
												}
												?>
												<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$separate_date).'_'.$property->property_id.'_M';?>"> 
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_availability editable editable-click"
															data-type="number"
															data-name="availability"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Availability"
														>
															<?php echo floatval($update->availability); ?>
														</a>
													<?php	} else { ?>
														<?php echo floatval($update->availability); ?>
													<?php	} ?>
												</td>
												<?php	
											}
											else
											{
												?>
												<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$separate_date).'_'.$property->property_id.'_M';?>">
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_availability editable editable-click"
															data-type="number"
															data-name="availability"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Availability"
														>
															<?php echo 'N/A'; ?>
														</a>
													<?php	} else { ?>
														<?php echo 'N/A'; ?>
													<?php	} ?>
												</td>
												<?php
											}
										}
										?>  
									</tr>
									
									<tr class="p-b-o">
										<td bgcolor="#a6a6a6">M</td>
										<?php 
										foreach($period as $date)
										{
											$separate_date = $date->format("d/m/Y");
											
											$update = isset($property->updates[$separate_date]) ? $property->updates[$separate_date] : null;
											
											$color = ($date->format("D")=='Sat' || $date->format("D")=='Sun') ? '#669933' : '#D9E4C3';
											$color_class = ($date->format("D")=='Sat' || $date->format("D")=='Sun') ? 'tabl_add' : 'tabl_even';
											
											if( $update )
											{
												/* if($update->availability==0)
												{
													$color = '#FF0000';
												}
												elseif($update->stop_sell==1)
												{
													$color = '#FF0000';
												}
												elseif($update->availability<0)
												{
													$color = '#F4C327';
												} */
												?>
												<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$separate_date).'_'.$property->property_id.'_M';?>"> 
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_availability editable editable-click"
															data-type="number"
															data-name="minimum_stay"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Minimum Stay"
														>
															<?php echo floatval($update->minimum_stay); ?>
														</a>
													<?php	} else { ?>
														<?php echo floatval($update->minimum_stay); ?>
													<?php	} ?>
												</td>
												<?php	
											}
											else
											{
												?>
												<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$separate_date).'_'.$property->property_id.'_M';?>">
													<?php if( $date >= $today && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1' ) { ?>
														<a
															href="javascript:;"
															id="inline_username"
															class="inline_username inline_availability editable editable-click"
															data-type="number"
															data-name="minimum_stay"
															data-pk="<?php echo $separate_date.'-'.$property->property_id;?>"
															data-url="<?php echo site_url('inventory/inline_edit_no')?>"
															data-title="Change Minimum Stay"
														>
															<?php echo 'N/A'; ?>
														</a>
													<?php	} else { ?>
														<?php echo 'N/A'; ?>
													<?php	} ?>
												</td>
												<?php
											}
										}
										?>  
									</tr>
									

									<tr class="p-b-o cta_main show_content_<?php echo $property->property_id; ?>" id="cta_<?php echo $property->property_id; ?>" style="display: none"> <td> show <td>	</tr>

									<tr class="p-b-o ctd_main show_content_<?php echo $property->property_id; ?>" id="ctd_<?php echo $property->property_id; ?>" style="display: none"> <td> show <td>	</tr>

									<tr class="p-b-o ss_main show_content_<?php echo $property->property_id; ?>" id="stop_sale_<?php echo $property->property_id; ?>" style="display: none"> <td> show <td>	</tr>
									

									<tr class="p-b-o show_content_<?php echo $property->property_id; ?>" style="display: none"> <td> show <td>	</tr>
									<?php } ?>
									<tr>
										<td colspan="<?php echo $startDate->diff($endDate)->format('%a') + 3; ?>" bgcolor="#3ac4fa" style="text-align:left; color:#fff; padding-left:20px;">
											<div class="col-md-12">
												<div class="col-md-3 pull-left">
												<div class="cls_bulk_checkbox">
												  <input id="show_reservation" class="styled" type="checkbox" onclick="changevalue()">
												  <label for="show_reservation"> Show Reservations</label>
												</div>
												<body onload="funcion_inicial()"> </body>
													<!--<div class="checkbox mar-top7">
														<label><input type="checkbox" id="show_reservation"> Show Reservations</label>
													</div>-->	
												</div>
									
												<div class="col-md-3 pull-left">
												<div class="cls_bulk_checkbox">
												  <input id="stop_sell_main" class="styled" type="checkbox">
												  <label for="stop_sell_main"> Stop Sell</label>
												</div>
													<!--<div class="checkbox mar-top7">
														<label><input type="checkbox" id="stop_sell_main">  Stop Sell</label>
													</div>-->
												</div>

												<div class="col-md-3 pull-left">
													<div class="cls_bulk_checkbox">
													  <input id="cta_main" class="styled" type="checkbox">
													  <label for="cta_main"> CTA</label>
													</div>
												</div>

												<div class="col-md-3 pull-left">
													<div class="cls_bulk_checkbox">
													  <input id="ctd_main" class="styled" type="checkbox">
													  <label for="ctd_main"> CTD</label>
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_reserved">
												  <label for="">Booking</label>
												  </div>
												</div>
												</div>												

												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_confirmed">
												  <label for="">Confirmed</label>
												  </div>
												</div>
												</div>


												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_checkin">
												  <label for=""> Check - In</label>
												  </div>
												</div>
												</div>	

												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_checkout">
												  <label for=""> Check - Out</label>
												  </div>
												</div>
												</div>	

												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_cta">
												  <label for=""> CTA</label>
												  </div>
												</div>
												</div>

												<div class="col-md-2 pull-left">
												<div class="">
												<div class="booking_label booking_ctd">
												  <label for=""> CTD</label>
												  </div>
												</div>
												</div>

											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>

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
        
        <h4 class="modal-title" id="myModalLabel"> Rooms and Rates </h4>
        
    
      </div>
  
      <div class="modal-body">
      
      
        <div class="row">
            
    
        <form id="reserve" method="post" novalidate="novalidate">  
        <div class="col-md-4 col-sm-4">
        <div class="blu">
        <img src="<?php echo base_url()?>user_assets/images/man.png" class="img img-responsive pull-left">
        <p>Please select your check-in and check-out dates as well as the total number of rooms and guests.</p>
        </div>
        </div>
        <div class="col-md-4 col-sm-4">
  <div class="form-group box-p">
    <div class="input-group">
      <div class="input-group-addon">Check-in date</div>

      <input type="text" class="form-control" id="dp11" value="<?php echo date('Y-m-d');?>"  onchange="return datechange();" >
	  <input type="hidden" class="form-control" id="dp1_new" name="dp1" value="<?php echo date('d/m/Y');?>">

    </div>
  </div>
  <div class="form-group box-p2">
    <select class="form-control" id="num_rooms" name="num_rooms">
  <?php
		   $qry = $this->db->query("SELECT max(existing_room_count) as maxNumber FROM `manage_property` WHERE `owner_id`='".current_user_type()."' AND `hotel_id`='".hotel_id()."'");
			$res = $qry->result_array();
			$numRooms = $res[0]['maxNumber'];

for ($i=1; $i<=$numRooms; $i++) { 
  echo '<option value="'.$i.'">'.$i. ' Rooms</option>';
}
  ?>

</select>
</div>
  <div class="form-group box-p2">
    <select class="form-control" id="num_child" name="num_child">
    <option value="0">0 Child</option>
    <?php
      $qry1 = $this->db->query("SELECT max(children) as maxNumber FROM `manage_property` WHERE `owner_id`='".current_user_type()."' AND `hotel_id`='".hotel_id()."'");
      $res1 = $qry1->result_array();
      $numChild = $res1[0]['maxNumber'];
      for ($i=1; $i<=$numChild; $i++) { 
        echo '<option value="'.$i.'">'.$i. ' Child</option>';
      }
    ?>
</select>
</div>
  
        </div>
        <div class="col-md-4 col-sm-4">
        
  <div class="form-group box-p">
    <div class="input-group">
      <div class="input-group-addon">Check-out date</div>
      <input type="text" class="form-control" id="dp11-p" value="<?php echo date("Y-m-d", strtotime("+1 day"));?>">
	  <input type="hidden" class="form-control" id="dp1-p_new" name="dp2" value="<?php echo date("d/m/Y", strtotime("+1 day"));?>">
    </div>
  </div>
  <div class="form-group box-p2">
    <select class="form-control" id="num_person" name="num_person">
  <?php
   $qry1 = $this->db->query("SELECT max(member_count) as maxNumber FROM `manage_property` WHERE `owner_id`='".current_user_type()."' AND `hotel_id`='".hotel_id()."'");
      $res1 = $qry1->result_array();
      $numAdult = $res1[0]['maxNumber'];
      for ($i=1; $i<=$numAdult; $i++) { 
        echo '<option value="'.$i.'">'.$i. ' Adult</option>';
        
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
        <h4 class="modal-title" id="myModalLabel"> Make a Reservation  </h4>
      </div>
      <div class="modal-body">
      <form id="reserve_info" method="post">
        <div class="row">
          <div class="co-md-6 col-sm-7">      
            <h5> Guest Information </h5>
            <input type="hidden" name="date1" id="date1" />
            <input type="hidden" name="date2" id="date2" />
            <input type="hidden" name="numrooms" id="nrooms" />
            <input type="hidden" name="numpersons" id="npersons" />
            <input type="hidden" name="numchilds" id="nchilds" />
            <div class="col-md-12 col-sm-12"><input name="first_name" type="text" class="form-control"  placeholder="Full Name"> </div>
  
            <br><br><br>
            <div class="col-md-6 col-sm-6"><input name="phone" type="text" class="form-control"  placeholder="Phone"> </div>
              
            <div class="col-md-6 col-sm-6"><input name="email" type="text" class="form-control"  placeholder="Email">
			<div class="checkbox">
			<input type="checkbox" id="guestmail" name="guestmail" value="guestmail" style="margin-left: 0px; margin-top: 3px;">
			<label for="guestmail">Send email to guest</label>
			</div>
			</div>
			
			<h5> Address Information </h5>
			<div class="col-md-6 col-sm-6"><input name="street_name" type="text" class="form-control"  placeholder="Street Address"> </div>
            

            <div class="col-md-6 col-sm-6"><input name="city_name" type="text" class="form-control"  placeholder="City"><!--<select name="city_name" class="form-control" ><option value=""> Select City</option> </select>--></div>  
            <br><br><br>
            <div class="col-md-6 col-sm-6"><input name="province" type="text" class="form-control"  placeholder="State"><!--<select name="province" class="form-control" ><option value=""> Select State</option> </select>--></div>


			<div class="col-md-6 col-sm-6"><select name="country" class="form-control" ><option value=""> Select Country</option> 
			<?php $countrys = get_data('country')->result_array();
			foreach($countrys as $value) { 
			extract($value);?>
			<option value="<?php echo $id;?>"><?php echo $country_name;?></option>
			<?php } ?>
			</select></div>
			<br><br><br>


			<div class="col-md-6 col-sm-6"><input name="zipcode" type="text" class="form-control"  placeholder="Zip Code"> </div>

			<div class="col-md-6 col-sm-6"><input name="arrivaltime" type="text" class="form-control"  placeholder="Arrival Time">
			</div>
			<br><br><br>

			<div class="col-sm-12">
			<h5 style="margin-top: 0px;">Notes</h5>
			<p> <textarea name="notes" class="form-control" style="height:150px;"></textarea> </p>
			</div>
            <!--<div class="option_date col-sm-6">
                <div class="col-sm-6 text-left" >
                option date
                </div>
                <div class="col-sm-6 text-right optiondate">
                14-02-2016
                </div> 
            </div>
            <div class="noti-ico col-sm-6">
              <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="here you can select the date by which the payment must be made to confirm the booking"><i class="fa fa-info"></i></button>
            </div>
			-->
          </div>
          <div class="co-md-6 col-sm-5">
            <h5> Reservation</h5>
            <?php $date = $this->reservation_model->get_room(); 
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
           
            <div id="reservation_price">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                  <td >  <span class="num_night"></span> <span class="nights"></span>   </td>
                  <td id=""> <?php echo $currency;?> <span id="actualamount" class="cal_price"></span>   </td>
                  </tr>
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
              <input type="hidden" name="rate_type_id" id="rate_type_id" value=""/>
              <input type="hidden" name="price_day" id="price_day" value=""/>
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
      
        <div class="extra_payment">
		
		<div class="row proper">
		
		<div style="" class="bb1 sub-m col-md-10 col-sm-10 col-md-offset-1">
		
		<h3 class="pull-left">Payment</h3>
		<?php 
		$card_count=0;
		if($card_count!=0)
		{
		?>
		<div class="pull-right ui-select card_hide" id="card_options" style="display: none;">
		<select name="use_existing_card" id="use_existing_cards" data-payment-method-id="935643660" data-currency="USD" class="select2 existing_card_selector form-control"><option value="true">Saved credit cards</option>
		<option value="false">Another payment method</option>
		</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>


		<p align="center" class="alert-danger" id="show_error"></p>		
		
		<div id="paymentMethods" class="tabbable tabs-left">
		
			<ul id="paymentList" class="nav nav-tabs" style="height: 45px !important;">
				
				<li id="935648652" class="active">
					<a class="pay_type cash_pay" data-type="cash" data-toggle="tab" href="#pay_at_hotel" >Cash</a>
				</li>
				<li id="935643660" class="">
					<a class="pay_type cc_pay" data-type="cc" data-toggle="tab" href="#ns-935643660" class="cc_detail" >Credit Card</a>
				</li>
				<li id="935648652" class="">
					<a class="pay_type PayPal " data-type="pp" data-toggle="tab" href="#ns-935648652">PayPal</a>
				</li>
				<li id="935648652" class="">
					<a class="pay_type bank_transfer " data-type="bt" data-toggle="tab" href="#bank_transfer">Bank Transfer</a>
				</li>
				
			</ul>

			<div class="tab-content">
				<div id="ns-935643660" class="tab-pane card_hide">
				  <?php if($card_count==0)
				  {
					  ?>
					<div id="new_cards" class="form-wrapper col-md-9">
					<input type="hidden" name="payment_card" id="payment_card" />
					<div class="row">
					<div class="col-md-4 col-sm-4">Select Card</div>
					<div class="col-md-8 col-sm-8"><div class="col-md-12">
					<select name="card_type" id="card_type" class="form-control ignore">
					<?php 
					$CCTYPES = get_data(CCTYPES)->result_array();
					if(count($CCTYPES)!=0)
					{
						foreach($CCTYPES as $value) {
							extract($value); ?>
					<option value="<?php echo $cc_type_id;?>"><?php echo $cc_type_name;?></option>
					<?php } } else {  ?>
					<option value="0">No Need</option>
					<?php } ?>
					</select>
					</div></div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Cardholder Name</div>
					<div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control ignore" id="card_name" name="card_name"></div>
					</div>
					</div>

					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Card number</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-12"><input type="text" class="form-control ignore" id="card_number" name="card_number"></div></div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-12">
					<input type="password" class="form-control ignore" id="security_code" name="security_code"></div>
					</div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Expiration</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-6">
					<select name="exp_month" id="exp_month" class="form-control ignore">
					 <?php 
					 $curr_mn = date('m');
					 for($i=1; $i<=12; $i++) { ?>
					 <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
					<?php } ?>
					</select>  
					</div>
					<div class="col-md-6">
					
					<select name="exp_year" id="exp_year" class="form-control ignore">
					<?php 
					$curr_year = date('Y');
					$end_year = date("Y", strtotime("+15 years"));
					for($i=$curr_year; $i<=$end_year; $i++) { ?>
					<option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
					<?php } ?>
					</select> 
					</div>
					</div>
					</div>

					</div>
					<?php } else { 
            ?>
					
					<div id="new_card" class="form-wrapper col-md-9" style="display: none;">
					<input type="hidden" name="payment_card" id="payment_card" />
					<div class="row">
					<div class="col-md-4 col-sm-4">Select Card</div>
					<div class="col-md-8 col-sm-8"><div class="col-md-12">
					<select name="card_type" id="card_type" class="form-control ignore">
					<?php 
					$CCTYPES = get_data(CCTYPES)->result_array();
					if(count($CCTYPES)!=0)
					{
						foreach($CCTYPES as $value) {
							extract($value); ?>
					<option value="<?php echo $cc_type_id;?>"><?php echo $cc_type_name;?></option>
					<?php } } else {  ?>
					<option value="0">No Need</option>
					<?php } ?>
					</select>
					</div></div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Cardholder Name</div>
					<div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control ignore" id="card_name" name="card_name"></div>
					</div>
					</div>

					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Card number</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-12"><input type="text" class="form-control ignore" id="card_number" name="card_number"></div></div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-12">
					<input type="password" class="form-control ignore" id="security_code" name="security_code"></div>
					</div>
					</div>
					
					<br>
					
					<div class="row">
					<div class="col-md-4 col-sm-4">Expiration</div>
					<div class="col-md-8 col-sm-8">
					<div class="col-md-6">
					<select name="exp_month" id="exp_month" class="form-control ignore">
					 <?php 
					 $curr_mn = date('m');
					 for($i=1; $i<=12; $i++) { ?>
					 <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
					<?php } ?>
					</select>  
					</div>
					<div class="col-md-6">
					
					<select name="exp_year" id="exp_year" class="form-control ignore">
					<?php 
					$curr_year = date('Y');
					$end_year = date("Y", strtotime("+15 years"));
					for($i=$curr_year; $i<=$end_year; $i++) { ?>
					<option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
					<?php } ?>
					</select> 
					</div>
					</div>
					</div>

					</div>
					
					<div class="col-md-9" id="existing_card_fields" style="display: block;">
					<table class="table table-hover marB5">
					<tbody>
				<?php 
				$i=0;
				foreach($cards as $card) { 
				$i++;
				extract($card);?>
					<tr id="spree_creditcard_1051">
					
					  <td style="min-width: 100px;">
						  <input type="radio" value="<?php echo $id;?>" name="existing_card" id="existing_card_<?php echo $id;?>" <?php  if($i==1) { ?> checked="checked" <?php } ?> />  
						<label class="marL5" for="existing_card_1051">
						  <?php echo safe_b64decode($c_fname).' '.safe_b64decode($c_lname); ?>
						</label>
					  </td>
					  <td style="min-width: 100px;">
						<label for="existing_card_1051">
						  <span class="end_card_number"> ending in <?php echo $last3chars = substr(safe_b64decode($card_number), -4);  ?></span>
						</label>
					  </td>
					  <td style="min-width: 36px;">
						<label for="existing_card_1051">
							  <img src="//d2uyahi4tkntqv.cloudfront.net/assets/creditcards/icons/master-9f945a9733126eeb4f12a592830ae2eb.png" class="card_image" alt="Master">
						</label>
					  </td>
					</tr>
					<?php } ?>
				</tbody>
					</table>
					</div>
					<?php } ?>
					</div>
				  
				<div id="ns-935648652" class="tab-pane pay_hide">
				<input type="hidden" name="pay_paypal"  id="pay_paypal"/>
				<div class="row">
				<div style="padding: 15px;" class="text-center">
				<img width="150" src="//d2uyahi4tkntqv.cloudfront.net/assets/paypal-5f6928dea999eac2a0a4cb2bff07f87c.png" class="paypal_image " alt="Paypal">
				<p class="marT20">
				PayPal lets you send payments quickly and securely online using a credit card or bank account.
				</p>
				</div>
				</div>
				</div>
				
				<div id="bank_transfer" class="tab-pane">
					<div class="row form-wrapper col-md-9">
					<div class="col-md-4 col-sm-4 mar-top7">Select Bank</div>
					<div class="col-md-8 col-sm-8"><div class="col-md-12">
					<select name="bank_type" id="bank_type" class="form-control ignore">
					<option value="">--- select bank ---</option>
					<?php 
					$BTYPES = get_data(BANK,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->result_array();
					if(count($BTYPES)!=0)
					{
						foreach($BTYPES as $value) {
							extract($value); ?>
					<option value="<?php echo $bank_id;?>"><?php echo $bank_name;?></option>
					<?php } } else {  ?>
					<option value="">No banks are available!!!</option>
					<?php } ?>
					</select>
					</div></div>
					</div>
					
					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Account Name</div>
					<div class="col-md-8 col-sm-8 mar-top7"><div class="col-md-12 acc_name">
					SBI
					</div></div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Currency</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 currency">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Bank name</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 bank_name">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Branch name</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 branch_name">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Branch code</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 branch_code">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Swift code</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 swift_code">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">IBAN</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 iban">
					SBI
					</div>
					</div>
					</div>

					<div class="row form-wrapper col-md-9 show_detail">
					<div class="col-md-4 col-sm-4 mar-top7">Account Number</div>
					<div class="col-md-8 col-sm-8 mar-top7">
					<div class="col-md-12 acc_number">
					SBI
					</div>
					</div>
					</div>
					
				</div>
			
				<div id="pay_at_hotel" class="tab-pane active">
				<div class="row mar-top7" align="centers">
				<?php
					$hotel_detail			=	get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->currency;
					if($hotel_detail!=0)
					{
						$currency_code	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->currency_code;
					}
					else
					{
						$currency_code	=	get_data(TBL_CUR,array('currency_id'=>"1"))->row()->currency_code;
					}
				?>
				<p>1. Pay at hotel.</p>
				<p>2. You will not be charged until your stay.</p>
				<p>3. Pay the hotel directly in <?php echo $currency_code; ?>. <?php echo get_data(TBL_SITE,array('id'=>1))->row()->company_name;?> will not charge you.</p>
				</div>
				</div>
			</div>
			
		  </div>
		  
		</div>
		
		</div>

            <!--<div class="col-sm-9 fn center-block">

              <div class="form-inline">
                <div class="row">
                  <div class="form-group">
                    <select class="form-control ignore m_cc_details" name="card_type" id="card_type">
                      <option value="Credit card">Credit card</option>
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
                      <img src="data:image/png;base64,<?php //echo base64_encode(file_get_contents("user_assets/images/card.png"));?>" />
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
                    <select class="form-control ignore m_cc_details" name="exp_month" id="exp_month">
                      <option value="">Expiration month</option>
                        <?php
                        /* for($i=1;$i<=12;$i++){
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        } */
                        ?>
                    </select>
                  </div>
                  <div class="form-group">/</div>
                  <div class="form-group">
                    <select class="form-control ignore m_cc_details" name="exp_year" id="exp_year">
                        <option value="">Expiration year</option>
                        <?php
                        /*  $year=date("Y");
                        for($i=$year;$i<=$year+15;$i++){
                          echo '<option value="'.$i.'">'.$i.'</option>';
                        } */
                        ?>
                    </select>
                  </div>
                </div>
              </div>

          </div>-->
        </div>
		<input type="hidden" name="payment_type" id="payment_type" value="cash">
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

<?php $this->load->view('channel/dash_sidebar'); ?>

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


<script type="text/javascript" src="https://hoteratus.com/user_assets/js/jquery.min.js"></script>
<?php 
$data['calendar'] = 'Advance Update';

                $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
                $data= array_merge($data,$user_details);
$this->load->view('channel/dash_sidebar',$data); ?>

<?php  $available1= get_data('ConfigUsers',array('userid'=>current_user_type(),'Hotelid'=>hotel_id()))->row_array();

if(count($available1)!=0)
{
	if($available1['CalenderShowR']==1)
	{	?>
		<script>
		//document.getElementById("show_reservation").click();
		function funcion_inicial(){

		var ch =document.getElementById("show_reservation");
			if(ch.checked){
				ch.checked=false;
				}
				
			//ch.click();
			ch=null;

		}
		</script>

	<?php }
}
?>
<script>

  function changevalue()
  {
  	var base_url = document.getElementById('base_url').value;
  		
  	if(document.getElementById("show_reservation").checked)
  	{
  		var valor =1;
  	}
  	else
 	{
 		var valor =0;
  	}	
  	
  		$.ajax({
				type: "POST",
				url: base_url+'inventory/saveuserconfig',
				data: "valor="+valor,
				success: function(result)
				{
					
				}
			});
  	
  }

</script>

<script type="text/javascript">
$('#seach_reserve').click(function()
{

 
	
 // $("#preloader").fadeIn("slow");
  if($('#reserve').valid())
  {
    var checkin_d=moment($('#dp11').val()).format("YYYY-MM-DD") ;
	if(checkin_d.match(/-/g)){
		checkin_d = checkin_d.split('-');
		checkin_d = checkin_d[2]+'/'+checkin_d[1]+'/'+checkin_d[0];
	}
	
	$('#dp1_new').val(checkin_d);


    var checkout_d= moment($('#dp11-p').val()).format("YYYY-MM-DD") ;
	if(checkout_d.match(/-/g)){
		checkout_d = checkout_d.split('-');
		checkout_d = checkout_d[2]+'/'+checkout_d[1]+'/'+checkout_d[0];
	}

	$('#dp1-p_new').val(checkout_d);
    var num_rooms=$('#num_rooms').val();
    var num_person=$('#num_person').val();
    var num_child=$('#num_child').val();




   /*  if(num_rooms>num_person)
    {
		$("#preloader").fadeOut("slow");
      $("#seach_reserve_show").trigger('click');
      $('#myModal3').find('#detail').html("OOPS!!!");
      var html1="Guest count must be equal or greater than room count<br></br></br>*Close the window to search again";
      $('#myModal3').find('#list_rooms').html(html1);
    }
    else
    { */
      $.ajax({
      data : $("#reserve").serialize(),
      url: "<?php echo site_url('reservation/get_reservation');?>", 
      success: function(result){
      $("#preloader").fadeOut("slow");
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
      $('#nchilds').val(num_child);
      getreserve();
      }
      });
    /* } */
  }
});

function book_this_room(id)
{
  $("#preloader").fadeIn("slow");
  var amount=$('#res_'+id).attr('data-amount');
  var grand=$('#res_'+id).attr('data-grand');
  var night=$('#res_'+id).attr('data-night');
  var room_id=$('#res_'+id).attr('data-room');
  var rate_type_id=$('#res_'+id).attr('data-rate');
  var price_day=$('#res_'+id).attr('data-price');
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
  $('#rate_type_id').val(rate_type_id);
  $('#price_day').val(price_day);
  var vales=0;
  $('.cal_price').each(function()
  {
    vales =parseInt(vales)+parseInt($(this).text());
  });
  s = vales;
   //$('#grand_total').html(s);
  $('#due_now').html(s);
  $.ajax({
  url:'<?php echo site_url('reservation/reservation_price');?>',
  type:'post',
  data:'price='+grand+"&nights="+night+"&amount="+amount+"&room_id="+room_id+"&rate_type_id="+rate_type_id+"&price_day="+price_day+"&num_person="+$('#num_person').val()+"&roomqty="+$('#nrooms').val(),
  success:function(result)
  {
    var gethtml=result.split("~~~");
    $('#reservation_price').html(result);
    var d=$('#optiondate_val').html();
    $('.optiondate').html(d);
    $("#preloader").fadeOut("slow");
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
	$('')
	$('.inr_cont').hide();
});

$('.close_amount').click(function(){
	$('')
	$('.inr_cont').hide();
});
 

}
$(document).ready(function(){

$('.show_detail').hide();

$('#dp1').datepicker({
     format: "dd/mm/yyyy",
     autoclose: true,
}).on('changeDate', function (ev) {
     $(this).datepicker('hide');
});

$('#dp1-p').datepicker({
     format: "dd/mm/yyyy",
     autoclose: true,
}).on('changeDate', function (ev) {
     $(this).datepicker('hide');
});

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
/* street_name:
{
  required:true,
},
country:
{
  required:true,
},
province:
{
  required:true,
},
city_name:
{
  required:true,
},
zipcode:
{
  required:true,
}, */
last_name:
{
  required:true,
},
phone:
{
  required:true,
  alphanumeric : true,
  minlength:10,
  maxlength:15
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
bank_type:
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
		$("#preloader").fadeIn("slow");

    var paymm = $('#payment_type').val();

    if(paymm!='pp'){

		$.ajax({
					data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
					url: "<?php echo site_url('reservation/save_reservation');?>",
					dataType:"json",
					success: function(result)
					{
						var res = result;
            
						if(res['result']!='1')
						{
							$('#m12close').trigger('click');
							$('#m13close').trigger('click');
							$('#m14close').trigger('click');
							$("#preloader").fadeOut("slow");
							$("#purchase_show").trigger('click');
							$('#thanks_booking').find('#bookresponse').html(res['message']);
             // document.getElementById("pay_now").click();
						}
						else
						{
							//$('#show_error').html(res['error']);
							$("#preloader").fadeOut("slow");
						}
					}
				});
  }
  else
  {
    $.ajax({
                data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
                url: "<?php echo site_url('reservation/save_reservation');?>",
                success: function(result)
                {
                    if(result)
                    {
                        $('#m12close').trigger('click');
                        $('#m13close').trigger('click');
                        $('#m14close').trigger('click');
                        $("#preloader").fadeOut("slow");
                        $("#purchase_show").trigger('click');
                        $('#thanks_booking').find('#bookresponse').html(result);
                    //   document.getElementById("pay_now").click();
                    }
                    /*else
                    {
                        $('#show_error').html(res['error']);
                        $("#preloader").fadeOut("slow");
                    }*/
                }
                });
  }
	}
})


</script>

<script>
 function filter_search(){
   $('#filt_search').html('');
   $.ajax({
    type:"POST",
    url:"<?php echo site_url('reservation/filter_res'); ?>",
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
      $('#sample_13_wrapper').find('.col1').find('.search_init').find("option:first").text('All Reservations'); // All Channel
	  $('#sample_13_wrapper').find('.col1').find('.search_init').val('<?php echo $channel_name;?>');
      $('#sample_13_wrapper').find('.col2').find('.search_init').find("option:first").text('All Status');
	  $('#sample_13_wrapper').find('.col2').find('.search_init').removeAttr('onchange');
    },200);

});

$('.addpayment .btn').on('click', function(e) { 
	$('.extra_payment').slideToggle();
	//$('.cc_detail').trigger('click');
	$('#bank_type').removeClass('customErrorClass');
	$('#bank_type').addClass('ignore');
	e.preventDefault(); 
	$("#preloader").fadeIn("slow");
	var card_type=$('#card_type').val();
	var security_code=$('#security_code').val();
	var card_name=$('#card_name').val();
	var card_number=$('#card_number').val();
	var exp_month=$('#exp_month').val();
	var exp_year=$('#exp_year').val();
	setTimeout(function(){	
	if($('.extra_payment').css('display') == 'block')
	{
		$("#preloader").fadeOut("slow");
		  
		if($('#use_existing_card').val()=='false')
		{
			$('#card_type').removeClass('ignore');
			$('#security_code').removeClass('ignore');
			$('#card_name').removeClass('ignore');
			$('#card_number').removeClass('ignore');
			$('#exp_month').removeClass('ignore');
			$('#exp_year').removeClass('ignore');
		}
		
		if(card_type!='' && security_code!="" && card_name!="" && card_number!="" && exp_month!=""&& exp_year!=""  && $('#reserve_info').valid())
		{
			$("#preloader").fadeIn("slow");
			$.ajax({
			data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
			url: "<?php echo site_url('reservation/save_reservation');?>", 
			dataType:"json",
			success: function(result){
				var res = result;
				if(res['result']!='1')
				{
					$('#m12close').trigger('click');
					$('#m13close').trigger('click');
					$('#m14close').trigger('click');
					$('.extra_payment').slideToggle();
					$("#preloader").fadeOut("slow");
					$("#purchase_show").trigger('click');
					$('#thanks_booking').find('#bookresponse').html(res['message']);
				}
				else
				{
					$('#show_error').html(res['error']);
					$("#preloader").fadeOut("slow");
				}
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
		
		if($('#use_existing_card').val()=='false')
		{
			$('#card_type').addClass('ignore');
			$('#security_code').addClass('ignore');
			$('#card_name').addClass('ignore');
			$('#card_number').addClass('ignore');
			$('#exp_month').addClass('ignore');
			$('#exp_year').addClass('ignore');
		}
		
		if(card_type!='' && security_code!="" && card_name!="" && card_number!="" && exp_month!=""&& exp_year!=""  && $('#reserve_info').valid())
		{
			$("#preloader").fadeIn("slow");
			$.ajax({
			data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
			url: "<?php echo site_url('reservation/save_reservation');?>", 
			dataType:"json",
			success: function(result){
			/* console.log(result); */
			var res = result;
			if(res['result']!='1')
			{
				$('#m12close').trigger('click');
				$('#m13close').trigger('click');
				$('#m14close').trigger('click');
				$('.extra_payment').slideToggle();
				$("#preloader").fadeOut("slow");
				$("#purchase_show").trigger('click');
				$('#thanks_booking').find('#bookresponse').html(res['message']);
			}
			else
			{
				$('#show_error').html(res["error"]);
				$("#preloader").fadeOut("slow");
			}
			}
			});
		}
		else
		{		
			$("#preloader").fadeOut("slow");
		}
	}
	},500);
	$(this).find('i').toggleClass('fa-angle-down fa-angle-up');
});

$(document).on('click','.PayPal',function(){ 

$('#exp_year').addClass('ignore');

$('#bank_type').removeClass('customErrorClass');
	
$('#bank_type').addClass('ignore');

cart_type = ($('#use_existing_cards').val());
	
	if(cart_type!='false')
	{
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
	}
});


$(document).on('click','.bank_transfer',function(){ 

	$('#bank_type').removeClass('ignore');
	
	/* cart_type = ($('#use_existing_cards').val());
	
	if(cart_type!='false')
	{ */
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
	/* } */
});

$(document).on('change','#bank_type',function(){ 
	/* console.log($(this).val()); */
	$("#preloader").fadeIn("slow");
	$.ajax({
			type:"POST",
			data : "bank_type="+$(this).val(),
			url: "<?php echo site_url('reservation/get_bank_details');?>", 
			dataType:'json',
			success: function(result)
			{
				var res = result;
				/* console.log(res['result']); */
				$("#preloader").fadeOut("slow");
				if(res['result']=='1')
				{
					var content = res['content'];
					$('.acc_name').html(content['account_owner']);
					$('.currency').html(content['currency']);
					$('.bank_name').html(content['bank_name']);
					$('.branch_name').html(content['branch_name']);
					$('.branch_code').html(content['branch_code']);
					$('.swift_code').html(content['swift_code']);
					$('.iban').html(content['iban']);
					$('.acc_number').html(content['account_number']);
					$('.show_detail').show();
				}
				else if(res['result']=='0')
				{
					$('.show_detail').hide();
				}
				/* $('#m12close').trigger('click');
				$('#m13close').trigger('click');
				$('#m14close').trigger('click');
				$("#preloader").fadeOut("slow");
				$("#purchase_show").trigger('click');
				$('#thanks_booking').find('#bookresponse').html(result); */
			}
			});
});

$(document).on('change','#use_existing_cards',function(){
	cart_type = ($('#use_existing_cards').val());
	
	if(cart_type=='false')
	{
		$('#new_card').show();
		$('#existing_card_fields').hide();
		$('#card_type').removeClass('ignore');
		$('#security_code').removeClass('ignore');
		$('#card_name').removeClass('ignore');
		$('#card_number').removeClass('ignore');
		$('#exp_month').removeClass('ignore');
		$('#exp_year').removeClass('ignore'); 
	}
	else
	{
		$('#new_card').hide();
		$('#existing_card_fields').show();
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
		
	}
});

$(document).on('click','.pay_type',function(){
	//console.log($(this).attr('data-type'));
	$('#payment_type').val($(this).attr('data-type'));
});

$(document).on('click','.cash_pay',function(){
	$('#card_type').removeClass('customErrorClass');
	$('#security_code').removeClass('customErrorClass');
	$('#card_name').removeClass('customErrorClass');
	$('#card_number').removeClass('customErrorClass');
	$('#exp_month').removeClass('customErrorClass');
	$('#exp_year').removeClass('customErrorClass');
	$('#bank_type').removeClass('customErrorClass');
	$('#card_type').addClass('ignore');
	$('#security_code').addClass('ignore');
	$('#card_name').addClass('ignore');
	$('#card_number').addClass('ignore');
	$('#exp_month').addClass('ignore');
	$('#exp_year').addClass('ignore');
	$('#bank_type').addClass('ignore');
});

$(document).on('click','.cc_pay',function(){

	$('#bank_type').removeClass('customErrorClass');
	
	$('#bank_type').addClass('ignore');
	
	var cart_type = ($('#use_existing_cards').val());
	
	if(cart_type=='false')
	{
		$('#card_type').removeClass('ignore');
		$('#security_code').removeClass('ignore');
		$('#card_name').removeClass('ignore');
		$('#card_number').removeClass('ignore');
		$('#exp_month').removeClass('ignore');
		$('#exp_year').removeClass('ignore');
	}
});




function change_roomtype(rid,channel_id,room_id)
{
  $("#preloader").fadeIn("slow");
  
  $.ajax({
    type: "POST",
    url: base_url+'reservation/update_room',
    data:{"reservation_id":rid,"channel_id":channel_id,"room_id":room_id},
    success: function(result)
    {
      $("#preloader").fadeOut("slow");      
    }
  });
}




</script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
          type="text/javascript"></script>
  <!-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css"
        rel="Stylesheet" type="text/css" /> -->
    <script type="text/javascript">
        $(function () {
           	$("#dp11").datepicker({
				minDate:new Date(),
				dateFormat: 'yy-mm-dd'
				});

		          $("#dp11-p").datepicker({
				minDate:new Date(),
				dateFormat: 'yy-mm-dd'
				});
        });

        function datechange()
		{
		var fecha = $("#dp11").datepicker("getDate");
	    fecha.setDate(fecha.getDate() + 1); 
	    $("#dp11-p").datepicker( "option", "minDate", fecha);
		}


    </script>


