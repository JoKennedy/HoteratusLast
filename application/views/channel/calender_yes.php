<div class="content">
<?php
$currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;

if($user_room_count = $this->inventory_model->user_room_count()!=0)
{
?>

<?php 
if($this->session->flashdata('bulk_success')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('bulk_success');?>
</div>
<?php } ?>
<?php
if($action!='reservation_no')
{
if(count($all_room)!=0) 
{
?>
<div class="change_month_replace">
<div class="dash-b-n1 new-s">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12 new-k2">
<div class="dash-b-n2">
<div class="row">
<div class="col-md-2 col-sm-2 ">
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
<input type="hidden" name="cur_month" id="next_month" value="<?php echo date('m')+1;?>" />
<input type="hidden" name="cur_month" id="prev_month" value="<?php echo date('m')-1;?>" />
</div>
</div>
<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="javascipt:;" id="show_pop" role="button" data-toggle="modal" data-target="#myModal-p" data-backdrop="static" data-keyboard="false">Customize calendar</a>
</div>

<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>

<div class="col-md-2 col-sm-2 org">
<a class="btn btn-warning main_full_update_modal" href="javascript:;" role="button">Full Update</a><!--  myModal-p2-->
</div>
<?php } ?>
<div class="col-md-3 col-sm-3 dr">
<a href="javascript:;" class="pull-left mar-right prev_month display_none"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
<div class="dropdown pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu_item1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo date('F Y');?>	
    <span class="caret"></span>
  </button>
<ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="ajax_cal">
<?php 
for ($m=date('m'); $m<=date('m')+14; $m++) 
{
?>
	<li class="<?php if(date('m')==$m) { ?>active<?php } ?> change_month"custom="<?php echo $m;?>">
	
	<a href="javascript:;" ><?php echo date('F Y', mktime(0,0,0,$m, 1, date('Y'))); //echo date('F Y', mktime(0,0,0,$m));?></a></li>
	
	
<?php 
} 
?>
</ul>
</div>
<div class="dropdown pull-left">
</div>
<a href="javascript:;" class="pull-left mar-left next_month"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>
</div>
<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
<div class="col-md-3 col-sm-3 bor-o">
<a class="btn btn-default" href="<?php echo lang_url();?>inventory/bulk_update/reservation_yes" role="button">Bulk Update</a>
</div>
<?php  } ?>
</div>
</div>
<div id="customize_date">


<?php
$today = date('d/m/Y');
$today_date = date('Y/m/d');
$current_start_date 	=	date('Y-m-d');
$current_end_date 		=	date('Y-m-d',strtotime($current_start_date."+1 months"));
//$tomorrow = date('d/m/Y',strtotime($today_date."+1 months"));
if(date('d')=='01')
{
	$tomorrow = date('t/m/Y');
}
else
{
	$tomorrow = date('d/m/Y',strtotime($today_date."+1 months"));
}
$startDate = DateTime::createFromFormat("d/m/Y",$today);
$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
$periodInterval = new DateInterval("P1D");
$endDate->add( $periodInterval );
$period = new DatePeriod( $startDate, $periodInterval, $endDate );
$dates = array();
$months = array();
$month   = array();
$last   = array();
$da=array();
$fi = 1;
$li = 1;
$thead='';
foreach($period as $date)
{
	$dates[] 	= $date->format("d");
	$months[] 	= date('F Y',strtotime($date->format("Y/m/d")));
	$month[] 	= $date->format("m"); 
	$last[]     = $date->format("t"); 
	$da[]		= $date->format("d/m/Y");
	if($date->format("m")==date('m'))
	{
		$month_s	= 	date('F Y',strtotime($date->format("Y/m/d")));
		$fi++;
		
	}
	else
	{
		$month_l 	= 	date('F Y',strtotime($date->format("Y/m/d")));
		$li++;
	}
	
	if($date->format("D")=='Sat' || $date->format("D")=='Sun')
	{
		$bg_clr = "#366092";
	}
	else
	{
		$bg_clr = "#8db4e2";
	}
	
	$thead.='<td width="28" bgcolor="'.$bg_clr.'">'.$date->format("d").'<br>'.$date->format("D").'</td>';
	
}
$show_month 	= (array_unique($month));
$last_date   	= (array_unique($last));
?>

<input type="hidden" name="cal_start" id="cal_start" value="<?php echo $today; ?>" />
<input type="hidden" name="cal_end" id="cal_end" value="<?php echo $tomorrow; ?>"/>
<div id="resp_div" style="display: none"></div> 
<form id="main_cal">
<input type="hidden" name="alter_checkbox" id="alter_checkbox" />
<input type="hidden" name="alter_checkbox_rate" id="alter_checkbox_rate" />
<input type="hidden" id="show_ss" name="show_ss" value="0" />
<div class="content">
<div class="table table-responsive">
<table class="table table-bordered table_stricky " id="reservation_yes_tbl">
<thead>
<tr>
<th width="400" align="left"></th>
<?php 
$t_col = '0';
foreach($show_month as $val)  
{
	if($val==date('m')){ $c_head = $month_s; $col=$fi; $t_col=$t_col + $col;} else { $c_head = $month_l; $col=$li-1; $t_col=$t_col + $col;}
?>
<th colspan="<?php echo $col;?>" class="text-center tal_td_bor">
<h3>
<strong><?php echo $c_head; ?></strong>
</h3>
</th>
<?php 
}
?>
</tr>

<tr>
<td width="400" bgcolor="#8db4e2"></td>
<td width="7" bgcolor="#8db4e2">&nbsp;</td>
<?php 
$current_day_this_month = date('d'); 
$last_day_this_month  	= date('t');
echo $thead;
?>
</tr>
</thead>
<tbody>
<?php 
foreach($all_room as $room) 
{
	extract($room);

	$all_rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id,'user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'),'rate_type_id')->result_array();

	if($non_refund==1)
	{
		$members = $member_count+$member_count;
	}
	else
	{
		$members = $member_count;
	}
	$price_data='';
	$avail_data='';
	foreach($period as $date)
	{
		$ss_da = $date->format("d/m/Y");
		
		if($date->format("D")=='Sat' || $date->format("D")=='Sun')
		{
			$color='#669933';
		}	
		else
		{
			$color='#D9E4C3';
		}
		
		if(in_array($ss_da,$da))
		{
			$single = get_data(TBL_UPDATE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da),'availability,price,stop_sell')->row();

			if(count($single)!='0')
			{
				if($single->availability==0 || $single->stop_sell==1)
				{
					$color = '#FF0000';
				}
				if($single->availability==0)
				{
					$color = '#FF0000';
				}
				elseif($single->stop_sell==1)
				{
					$color = '#CC99FF';
				}
				elseif($single->availability < 0)
				{
					$color	= '#F4C327';
				}
				
				$price_data.= '<td>';
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$price_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Price">'.floatval($single->price).'</a>';
				} 
				else 
				{ 
					$price_data.= floatval($single->price); 
				}
				$price_data.= '</td>';
				
				$avail_data.= '<td width="28" bgcolor="'.$color.'" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$avail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Availability">'.$single->availability.'</a> ';
				} 
				else
				{
					$avail_data.=$single->availability;
				}
				$avail_data.='</td>';
				
				
			}
			else
			{
				$price_data.= '<td>';
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$price_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Price"> N/A </a>';
				} 
				else 
				{ 
					$price_data.='N/A';
				}
				$price_data.= '</td>';
				
				$avail_data.= '<td width="28" bgcolor="'.$color.'" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
				
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$avail_data.= '<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Availability"> N/A </a>';
				}
				else 
				{ 
					$avail_data.= 'N/A';
				} 
				$avail_data.= '</td>';
			}
		}
		else
		{
			$price_data.= '<td>';
			if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
				$price_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Price"> N/A </a>';
			}
			else
			{ 
				$price_data.='N/A';
			} 
			$price_data.= '</td>';
			
			$avail_data.= '<td width="28" bgcolor="'.$color.'" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
			
			if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
				$avail_data.= '<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Availability"> N/A </a>';
			}
			else 
			{ 
				$avail_data.= 'N/A';
			}
			$avail_data.= '</td>';
		}
	}
?>
	
	<tr class="p-b-o">
	<td rowspan="2" class="ha2 ss_main_row" width="400"><a href="javascript:;" class="show_e" onClick="toggle_visibility('contents_<?php echo $property_id;?>','show_plus_<?php echo $property_id;?>');">
	<span class="pull-left text-info"><strong><?php echo ucfirst($property_name);?></strong></span>
    <?php if($pricing_type==2) { ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php }else if($pricing_type==1 && $non_refund==1){?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } else if(count($all_rate_types)!=0){ ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } ?>
    </a>
	</td>
	
	<td bgcolor="#a6a6a6">P</td>
		<?php echo $price_data;?>
	</tr>
	
	<tr class="p-b-o">
	  <td bgcolor="#a6a6a6">A</td>
		<?php echo $avail_data;	?>  
	</tr>

	<!-- Paste SS at Main -->
	<tr class="p-b-o ss_main show_content_<?php echo $property_id; ?>" id="stop_sale_<?php echo $property_id; ?>" style="display: none"> <td> show <td>	</tr>
	<tr class="p-b-o show_content_<?php echo $property_id; ?>" style="display: none"> <td> show <td>	</tr>
	
	<!-- Paste Reservations -->
   
    <!-- Past Gust Based -->
	<?php 
	
	// Past Rate Code
		
	}
}
?>
 
<tr >
<td colspan="<?php echo $t_col+1; ?>" bgcolor="#366092" style="text-align:left; color:#fff; padding-left:20px;">
<div class="col-md-10">

<div class="col-md-2 pull-left">
	<div class="checkbox mar-top7">
	<label>
	  <input type="checkbox" id="show_reservation"> Show Reservations  
	</label>
	</div>
</div>

<div class="col-md-2 pull-left">
	<div class="checkbox mar-top7">
	<label>
	  <input type="checkbox" id="stop_sell_main">  Stop Sell 
	</label>
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

<!-- Calender Design Part -->


</div>
</div>
</div>
</div>


<?php 
}
else if($action=='reservation_no') 
{ 
$rate_id="";

$rate_name="";

if(count($all_room)!=0) 
{
	if($all_channel)
	{
		foreach($all_channel as $con_chs)
		{
			extract($con_chs);
			//echo $con_ch = $channel_id.'<br>';
			?>
			<div align="center" class="channel_calendar" id="channel_<?php echo insep_encode($channel_id);?>" custom="<?php echo insep_encode($channel_id);?>">
			<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("user_assets/loader/1310.gif"));?>">
			</div>
			<?php
?>
<?php  } 
	} 
	else 
	{  ?> <div class="bb1">

<br>

<div class="reser"><center><i class="fa fa-globe"></i></center></div>

<h2 class="text-center">You don't have any online channels configured for this room type yet</h2>

<p class="pad-top-20 text-center">You can sell your rooms from hundreds of online sales channels</p>

<br>

<div class="res-p"><center><a class="btn btn-primary" href="<?php echo lang_url();?>mapping/connectlist"><i class="fa fa-plus"></i>  Map To Channel</a></center></div>

</div> <?php } } } 
// past reservation_no_code
}else { ?>
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
<?php } ?>

<div class="modal fade dialog-3 " id="modal_single_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" id="single_update">
  </div>
</div>

<div class="dial2">
<div class="modal fade dialog-3 " id="reservation_user_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" id="reservation_user">
  </div>
</div>
</div>

<!-- Customize Calender -->
<div class="modal fade dia-l" id="myModal-p" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
  <form class="mar-right" id="customize_calender" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Customize Calendar</h4>
      </div>
      <div class="modal-body">
        <div class="calen-2">
        <?php
		$curr_date = date('d/m/Y');
		$date = strtotime("+7 day");
		$add_date = date('d/m/Y', $date);
		?>
        
        <div class="row">
        <div class="col-md-6 col-sm-6">
        <h4>Start Date:</h4>
		<div class="form-group">
		<input type="text" class="form-control widh" value="<?php echo $curr_date;?>" id="datepicker_start" name="start_date">
		</div>
        </div>
        <div class="col-md-6 col-sm-6">
        <h4>End Date:</h4>
        <div class="form-group">
        <input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_end" name="end_date">
        </div>
        </div>
        </div>
        
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="customize">Continue</button>
      </div>
    </div>
     </form>
  </div>
</div>

<div class="modal fade dia-l" id="myModal-ps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
  <form class="mar-right" id="customize_calenders" method="post">
  <input type="hidden" name="custom_channel_id" id="custom_channel_id" />
  <input type="hidden" name="custom_channel_rate_id" id="custom_channel_rate_id" />
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Customize Calendar</h4>
      </div>
      <div class="modal-body">
        <div class="calen-2">
        <?php
		$curr_date = date('d/m/Y');
		$date = strtotime("+7 day");
		$add_date = date('d/m/Y', $date);
		?>
        
        <div class="row">
        <div class="col-md-6 col-sm-6">
        <h4>Start Date:</h4>
		<div class="form-group">
		<input type="text" class="form-control widh" value="<?php echo $curr_date;?>" id="datepicker_starts" name="start_date">
		</div>

<!--<div class="row">
  <div class="col-md-6 col-sm-6">
  <b class="ne-k">Rooms mapped to</b></div>
  <div class="col-md-6 col-sm-6"><select class="form-control" name="room_map">
  <option>Any Channel</option>
  <option>2</option>
</select></div>
</div>-->
  
  
  
  <!--<div class="top-ma">


<?php 
//$con_cha = $this->channel_model->user_channel();
//if(count($con_cha)!=0) {
	?>
    <div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="channel_all" class="channel_all1">
     All 
  </label>
</div>
</div>
    <?php 
	 //foreach($con_cha as $connected){ extract($connected);?>
<div class="checkbox">
  <label>
    <input type="checkbox" checked class="channel_single1" name="channel_id[]" id="channel_single1" value="<?php //echo $channel_id?>">
      <?php //echo $channel_name;?>
  </label>
</div>

<?php //} } else {  ?>
<div class="close-i"><i class="fa fa-close"></i> No channel data found...</div>
<?php //} ?>

</div>-->
        </div>
        <div class="col-md-6 col-sm-6">
        <h4>End Date:</h4>
        
  <div class="form-group">
    <input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_ends" name="end_date">
  </div>

  <!-- date -->
  <!--<b>Days</b>--> 
  <!--<div class="top-ma">
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="reser_no_days">
    All
  </label>
</div>
</div>

<?php 
  		/* $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$values = array('1','2','3','4','5','6','7');
		foreach($days as $key=>$day)
		{ */
  ?>
  <div class="checkbox">
    <label>
      <input type="checkbox" checked name="days[]" value="<?php //echo $values[$key]?>" class="reser_no_days"><?php //echo $day?> 
    </label>
  </div>
  <?php //} ?>
</div>-->
  <!-- end date -->
  
        </div>
        </div>
        
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="customizes">Continue</button>
      </div>
    </div>
     </form>
  </div>
</div>
<!-- Customize Calender -->

<!-- Full Refresh -->
<div class="modal fade dia-2" id="myModal-p2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form method="post" id="main_full_update">
    <div class="modal-content main_full_update">
    </div>
    </form>
  </div>
</div>
<!-- Full Refresh -->

<!-- Full Update -->
<div class="modal fade dia-2" id="channelsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="channels-header">Channels to update</h4>
      </div>
      <div class="modal-body">
        <div class="form-group vertical-top clearfix">

          <div class="multiselect multiselect-channels width-form-full">
            
            <?php 
			$con_cha = $this->channel_model->user_channel();
			/*echo '<pre>';
			print_r($con_cha);*/
		
			
			if(count($con_cha)!=0) { 
			//echo 'con_cha'.count($con_cha);	die;
			?>
            <label class="check_header">
              <input type="checkbox" value="1" name="all_check" multiple="multiple" id="all_check_toggle" class="all_check_toggle" checked="checked"> All
            </label>
            <?php
			foreach($con_cha as $connected){ extract($connected);?>
			<div class="check_list">
			<label>
		    <input type="checkbox" checked class="channel_single2" name="channel_id[]" id="channel_single2" value="<?php echo $channel_id?>">
		    <?php echo $channel_name;?>
			</label>
			</div>
			<?php } } else {  ?>
			<div class="close-i"><i class="fa fa-close"></i> No connected channel data found...</div>
			<?php } ?>
          </div>
        </div>
      </div>
        <div class="modal-footer" id="channels-footer">
         <button data-dismiss="modal" class="pull-left btn btn-default" type="button">Close</button>
        <a data-dismiss="modal" class="btn btn-primary save_button_master_calendar" href="javascript:;">Update</a>
      </div>
    </div>
  </div>
</div>
</div>
<?php $this->load->view('channel/dash_sidebar'); ?>
<!-- Full Update -->



<!--<a class="link" href="javascript:;" rel="div1">Link 1</a>
<a class="link" href="javascript:;" rel="div2">Link 2</a>
<a class="link" href="javascript:;" rel="div3">Link 3</a>

<div class="content" id="div1">Content 1</div>
<div class="content" id="div2">Content 2</div>
<div class="content" id="div3">Content 3</div>
<script>

$('.link').click(function(e){

e.preventDefault();

var content = $(this).attr('rel');

$('.active').removeClass('active');

$(this).addClass('active');

$('.content').hide();

$('#'+content).show();

});

</script>
-->