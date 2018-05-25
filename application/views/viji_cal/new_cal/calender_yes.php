<?php
$currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;

if($user_room_count = $this->inventory_model->user_room_count()!=0)
{
?>
<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n"><h4><a href="javascript:;">Calendar</a>
<i class="fa fa-angle-right"></i> Advanced Updates </h4></div>
</div>
</div>
</div> 
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
<div class="col-md-2 col-sm-2">
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
<a class="btn btn-default" href="<?php echo base_url();?>inventory/bulk_update/reservation_yes" role="button">Bulk Update</a>
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
}
$show_month 	= (array_unique($month));
$last_date   	= (array_unique($last));
?>
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
$last_day_this_month  = date('t');
foreach($period as $date)
{
	$i=$date->format("d");
	if($date->format("D")=='Sat' || $date->format("D")=='Sun')
	{
		$bg_clr = "#366092";
	}
	else
	{
		$bg_clr = "#8db4e2";
	}
?>
<td width="28" bgcolor="<?php echo $bg_clr;?>"><?php echo $date->format("d")?> <br>
  <?php echo $date->format("D")?></td>
<?php 
} 
?>
</tr>
</thead>
<tbody>
<?php 
foreach($all_room as $room) 
{
	extract($room);

	$all_rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id,'user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'))->result_array();

	$reservation_count 			= 	$this->inventory_model->reservation_count(insep_encode($property_id),$current_start_date,$current_end_date);

	$channel_reservation_count 	= 	$this->reservation_model->channel_reservation_count(insep_encode($property_id),$current_start_date,$current_end_date);

	if($non_refund==1)
	{
		$members = $member_count+$member_count;
	}
	else
	{
		$members = $member_count;
	}
?>
	
	<tr class="p-b-o">
	<td rowspan="2" class="ha2 ss_main_row" width="400"><a href="javascript:;" class="show_e" onClick="toggle_visibility('contents_<?php echo $property_id;?>','show_plus_<?php echo $property_id;?>');">
	<span class="pull-left text-info"><strong><?php echo ucfirst($property_name);?></strong></span>
    <?php if($pricing_type==2) { ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php }else if($pricing_type==1 && $non_refund==1){?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } else if(count($all_rate_types)!=0){ ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } ?>
    </a></td>
	<td bgcolor="#a6a6a6">P</td>
	<?php 
	foreach($period as $date)
	{
		$i=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		/*if($i%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
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
			$single = get_data(TBL_UPDATE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();

		if(count($single)!='0')
		{
		if($single->availability==0 || $single->stop_sell==1)
		{
			$color = '#FF0000';
		}
	?>
	<td>
	<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
    		<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Price"><?php echo floatval($single->price); ?> </a><?php } else { ?><?php echo floatval($single->price); ?> <?php } ?></td>
	<?php } else  { ?>
	<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
	<?php } } else { ?> 
	<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
	<?php }}?>
	</tr>
	
	<tr class="p-b-o">
	  <td bgcolor="#a6a6a6">A</td>
	<?php 
	foreach($period as $date)
	{
		$i=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		/*if($i%2 == 0)
		{
			$color='#669933';
		}*/
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
			$single = get_data(TBL_UPDATE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
			if(count($single)!='0')
			{
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
		?>
	  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>"> 
	  <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Availability"><?php echo ($single->availability); ?> </a> <?php } else {?> <?php echo ($single->availability); ?> <?php } ?></td>
	<?php } else  { ?>  
	  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>">
	  <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
	<?php } } else { ?>   
	  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>">
	  <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
	<?php }}?>  
	</tr>

	<tr class="p-b-o ss_main">
    <td bgcolor="#a6a6a6">SS</td>
        
    <?php 
	foreach($period as $date)
	{
		$i=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		/*if($i%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
		if($date->format("D")=='Sat' || $date->format("D")=='Sun')
		{
			$color='#d9d9d9';
		}	
		else
		{
			$color='#FFFFFF';
		}
		if(in_array($ss_da,$da))
		{
			$single = get_data(TBL_UPDATE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
			if(count($single)!='0')
			{
				if($single->availability==0)
				{
					$color = '#FF0000';
				}
				elseif($single->stop_sell==1)
				{
					$color = '#CC99FF';
				}
			?>
	<td bgcolor="<?php //echo $color?>">
   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  value="1" <?php if($single->stop_sell=='1'){?> checked="checked" <?php } ?> name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" class="update_stop_sell_main">
            <?php } else { ?>
           <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> disabled="disabled">
            <?php } ?>
        </td>
	<?php } else  { ?>
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_main">
            <?php } else { ?>
            N/A
            <?php } ?>
        </td>
	<?php } } else { ?> 
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_main">
            <?php } else { ?>
            N/A
            <?php } ?>
            
        </td>
	<?php }}
	?>
    </tr>

	<?php
    if($reservation_count!=0 || $channel_reservation_count!=0)
    {
		if($reservation_count!=0)
		{
			$manualreservation = manual_reservation(insep_encode($property_id),$current_start_date,$current_end_date);
			
			if($manualreservation)
			{
				$manualreservation 		= 	$manualreservation;
			}
			else
			{
				$manualreservation		=	array();
			}
		}
		else
		{
			$manualreservation		=	array();
		}
		/* echo '<pre>';
		print_r($manualreservation); */
		if($channel_reservation_count!=0)
		{
			$channelreservation	=	$this->reservation_model->channel_reservation_result(insep_encode($property_id),$current_start_date,$current_end_date);
		}
		else
		{
			$channelreservation		=	array();
		}
		
		/* echo '<pre>'; print_r($channelreservation); */
		$reservation = $this->inventory_model->cleanArray(array_merge($manualreservation,$channelreservation));
        $arr_pu=array();
        $reser = array();
        $date_list = 0;
		//echo '<pre>';
		//echo $reservation[0]->start_date;
		//echo 'count '.count($reservation).'<br>';
		/* for($asvg=0;$asvg<=count($reservation);$asvg++)
		{
			if(date('Y/m/d',strtotime(str_replace('/','-',$reserkvation[$asvg]->start_date))) >= date('Y/m/d'))
			{	
				//print_r($reservation);
				//echo ($reservation['start_date']);
				$reservation = $reservation;
			}
			else if(date('Y/m/d',strtotime(str_replace('/','-',$reservation[$asvg]->start_date))) < date('Y/m/d'))
			{
				$replace_curr_data = date('d/m/Y');
				$reservation[$asvg]->start_date = $replace_curr_data;
				/* print_r($reservation);
				echo ($reservation['start_date']);  */
				 //$reservation = $reservation;
			//}
			
		//} */
	//print_r($reservation);
		/* if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date']))) >= date('Y/m/d'))
		{	
			//print_r($reservation);
			//echo ($reservation['start_date']);
			$reservation = $reservation;
		}
		else if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date']))) < date('Y/m/d'))
		{
			$replace_curr_data = date('d/m/Y');
			$reservation['start_date'] = $replace_curr_data;
			/* print_r($reservation);
			echo ($reservation['start_date']);  */
			 //$reservation = $reservation;
	//	} */
						
		if($reservation)
		{
        	foreach($reservation as $value)
			{
			 	$reser_id 			= $value->reservation_id;	
				$reser_channel_id 	= $value->channel_id;
				$booking_number		= $value->booking_number;	
				$var = $value->start_date;
				$datea = str_replace('/', '-', $var);
				$start_dates  = date('Y-m-d', strtotime($datea));
				
				$var1 = $value->end_date;
				$datea1 = str_replace('/', '-', $var1);
				$end_dates  = date('Y-m-d', strtotime($datea1));
				
				//$startDate = DateTime::createFromFormat("d/m/Y",$value->start_date);
				
				if(date('Y/m/d',strtotime(str_replace('/','-',$value->start_date))) >= date('Y/m/d'))
				{	
					$startDate = DateTime::createFromFormat("d/m/Y",$value->start_date);
				}
				else if(date('Y/m/d',strtotime(str_replace('/','-',$value->start_date))) < date('Y/m/d'))
				{
					$startDate = DateTime::createFromFormat("d/m/Y",date('d/m/Y'));
				}
				
				$endDate = DateTime::createFromFormat("d/m/Y",$value->end_date);
				$periodInterval = new DateInterval("P1D");
				//$endDate->add( $periodInterval );
				$periods = new DatePeriod( $startDate, $periodInterval, $endDate );
				$re=array();
				$same=1;
				foreach($periods as $dates)
				{ 
					//echo $dates->format("d/m/Y").'<br>';
					array_push($arr_pu,$dates->format("d/m/Y"));
					$reser[$dates->format("d/m/Y")][$date_list]= $reser_id.'~'.$reser_channel_id.'~~'.$booking_number;
					$same++;
				}
				$date_list++;
			}
		}
 /*       echo '<pre>';
        print_r($reser);*/
          $fillTo = array('key'=>'','count'=>0);
          foreach($reser as $k => $data) {
                  if (count($data) > $fillTo['count']) {
                  $fillTo['key'] = $k;
                  $fillTo['count'] = count($data);
              }
          }
         // echo 'test'.count($reser[$fillTo['key']]);
		  if(@$reser[$fillTo['key']])
		  {
          $fillData = $reser[$fillTo['key']];
          foreach($fillData as $k => &$data) {
              @$data['numberof'] = ''; //0
          }
		  }
          
          foreach($reser as $k => &$data) {
              if ($k === $fillTo['key'])
                  continue;
          
              $data = $data + $fillData;
          }
		  
		  //$reser = array_map('array_values', $reser);
         /*echo '<pre>';
        
        print_r($reser);
        
       
        print_r($arr_pu);*/
        
        
        if(count($reservation)!=0)
        {
            $a = $da;
            $b = $arr_pu;
            $c = array_intersect($a, $b);
			$c = $this->inventory_model->cleanArray($c);
            if(count($c)!=0)
            {
                $arr_name = array();
                $rer_name=array();
				$rer_cha_id=array();
				$rer_date_id=array();
                if(empty($arr_unq))
                {
                    $arr_unq = array();
                }
                $o_loop = 1	;
				/* echo '<pre>';
				print_r($reser); */
				//print_r(array_keys($reser));
				//echo count($reser).'<br>';
                /* for($s=0;$s<=count($reser);$s++)
                { */
					foreach($reser as $t_key=>$t_val)
					{
						foreach($t_val as $s_key=>$s_val)
						{
							$s = $s_key;
					/* 		
						}
					} */
					
					$t_array = array();
					$t_array[]=$reser;
                   // echo $s.'<br>';
                    if($s%2 == 0)
                    {
                        $book_color='show5';
                        $a_color   ='contents6';
                        $btn_color ='booked';
                    }
                    
                    else	
                    {
                        $book_color='show6';
                        $a_color   ='contents5';
                        $btn_color ='booked1'; 
                    }
            ?>
            <tr class="p-b-o contents4">
            <td class="ha2">&nbsp;</td>
            <td class="ha2">&nbsp;</td>
            <?php
            foreach($period as $date)
            {
                $j=$date->format("d");
                $ss_rr= $date->format("d/m/Y");
				//echo $ss_rr.' = '.$s.'<br>';
				/* echo '<pre>';
				print_r($reser); */
                if (@array_key_exists($ss_rr, $reser) )
                {
					$all_reservation_id = explode('~',@$reser[$ss_rr][$s]);
                    $get_reser			= @$all_reservation_id[0];
					$all_channel_id		= @$all_reservation_id[1];
					$all_booking		= explode('~~',@$reser[$ss_rr][$s]);
					$cha_booking		= @$all_booking[1];
                    if($get_reser<1)
                    {
                        echo '<td> </td>';
                    }
                    else
                    {
						//echo '<pre>';
						//echo $all_channel_id; CURDATE()
						if($all_channel_id==0)
						{
							$reservation = single_manual_reservation(insep_encode($property_id),$current_start_date,$current_end_date,$get_reser);
							
							if($reservation)
							{
								$reservation 		= 	$reservation;
							}
							else
							{
								$reservation		=	array();
							}
							
						}
						elseif($all_channel_id==11)
						{	
							//echo $all_channel_id;
							$room_id		= $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
							//print_r($room_id);
							if(count($room_id)!=0)
							{
								$reservation = array();
								foreach($room_id as $room_code)
								{
									extract($room_code);
									$reservation	= array_merge($reservation,$this->reservation_model->getSingleReservationReconline($get_reser,$CODE,$cha_booking));
								}
							}
							else
							{
								$reservation = array();
							}
							//print_r($reservation);
						}
						elseif($all_channel_id==1)
						{	
							//echo $get_reser.'<br>';
							/*$property_ids[]='';
							if(!in_array($property_id , $property_ids))
							{*/
								//$room_id		= $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
								/* echo '<pre>';
								print_r($room_id); */
								/* if(count($room_id)!=0)
								{
									$reservation = array();
									foreach($room_id as $room_code)
									{
										extract($room_code);
										$reservation	= array_merge($reservation,$this->reservation_model->getSingleReservationExpedia($get_reser,$roomtype_id,$rate_type_id,$rateplan_id,$cha_booking));
									}
								}
								else
								{
									$reservation = array();
								} */
								$reservation	= array_merge($reservation,$this->reservation_model->getSingleReservationExpedia($get_reser,$roomtype_id='',$rate_type_id='',$rateplan_id='',$cha_booking=''));
								/*$property_ids[] =$property_id;
							}*/
							//print_r($reservation);
						}
						elseif($all_channel_id==8)
						{	
							$room_id		= $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
							//print_r($room_id);
							if(count($room_id)!=0)
							{
								$reservation = array();
								foreach($room_id as $room_code)
								{
									extract($room_code);
									$reservation	= array_merge($reservation,$this->reservation_model->getSingleReservationGta($get_reser,$ID,$rateplan_id,$cha_booking));
								}
							}
							else
							{
								$reservation = array();
							}
							//print_r($reservation);
						}
						elseif($all_channel_id==2)
						{	
							//echo $get_reser.'<br>';
							$room_id		= $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
							//print_r($room_id);
							if(count($room_id)!=0)
							{
								$reservation = array();
								foreach($room_id as $room_code)
								{
									extract($room_code);
									$reservation	= array_merge($reservation,$this->reservation_model->getSingleReservationBooking($get_reser,$B_room_id,$B_rate_id,$cha_booking));
								}
							}
							else
							{
								$reservation = array();
							}
							//print_r($reservation);
						}
   						/* if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date']))) >= date('Y/m/d'))
						{	
							//print_r($reservation);
							//echo ($reservation['start_date']);
							$reservation = $reservation;
						}
						else if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date']))) < date('Y/m/d'))
						{
							$replace_curr_data = date('d/m/Y');
							$reservation['start_date'] = $replace_curr_data;
							//print_r($reservation);
							//echo ($reservation['start_date']); 
							 $reservation = $reservation;
						} */
					/*	echo '<pre>';  print_r($reservation); */
						if(count($reservation)!=0) 
						{
							//if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date'])))>=date('Y/m/d'))
							/*if(in_array(date('d/m/Y'),$c))
							{*/
							$var = $reservation['start_date'];
							$datea = str_replace('/', '-', $var);
							$start_dates  = date('Y-m-d', strtotime($datea));
						   
							$var1 = $reservation['end_date'];
							$datea1 = str_replace('/', '-', $var1);
							$end_dates  = date('Y-m-d', strtotime($datea1));
							
							$datetime1 = new DateTime($start_dates);
							$datetime2 = new DateTime($end_dates);
							$interval = $datetime1->diff($datetime2);
							
							$newend_dates = date('Y-m-d');
							$datetime1 = new DateTime($start_dates);
							$datetime2 = new DateTime($newend_dates);
							$interval2 = $datetime1->diff($datetime2);
							
							//echo $interval2->format('%a%');
							
							
							if($start_dates == date('Y-m-d') || $start_dates > date('Y-m-d'))
							{
								$colspn = $interval->format('%a%');//+1;
							}
							else if($start_dates <= date('Y-m-d'))
							{
								$colspn = $interval->format('%a%') - $interval2->format('%a%');//+1;
							}
							else
							{
								//$colspn = $interval->format('%a%');
							}
							if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id))
							{
								/* if(!in_array($reservation['start_date'],$rer_date_id))
								{ */
									//echo $reservation['start_date'].' = '.$ss_rr;
									/* if($reservation['start_date'] == $ss_rr)
									{ */
							//print_r($reservation);
							?>
								<td colspan="<?php echo $colspn;?>" onmouseover="show_detais('<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>');" onmouseout="show_detais('<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>');">
                    
								<?php 
								if($reservation['start_date'] == $reservation['end_date']) 
								{
								?>
								<?php if($reservation['channel_id'] == 0){ ?>
								<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_order/<?php echo insep_encode($reservation['reservation_id']); ?>">
								<?php } else { ?>
								<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_channel/<?php echo secure($reservation['channel_id']).'/'.insep_encode($reservation['reservation_id']); ?>">
								<?php  } ?>
									<button style="display:block" class="<?php echo $btn_color.' '.$book_color.' '.$reservation['reservation_id'];?>" type="button" target="_blank">
									<?php if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id)){
									if($reservation['channel_id']==2)
									{
										if($reservation['guest_name']!='')
										{
											$reservation_name 	=	$reservation['guest_name'];
										}
										else
										{
											$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('roomreservation_id'=>$reservation['reservation_code']))->row()->reservation_id),'first_name,last_name')->row();
											$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
										}
									}
									else
									{
										$reservation_name	=	$reservation['guest_name'];
									}
									//echo $reservation_name;
									}else { echo ''; } ?> 
									<?php
									if($reservation['channel_id']=='0')
									{
										  $book_logo = get_data(CONFIG,array('id'=>'1'))->row()->logo_book;
										  $path = "uploads/logo/small/";
									}
									else
									{
										 $book_logo = get_data(TBL_CHANNEL,array('channel_id'=>$reservation['channel_id']))->row()->logo_book;
										 $path = "uploads/small/";
									}
									?>
									<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($path.$book_logo));?>" class="pull-right ">
									</button>
									</a>
									<div class="<?php echo $a_color.' '.$reservation['reservation_id']?>" id="<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>">
									<ul>
									<li>Room Name : <span class="pull-right">
									<?php 
									if($reservation['channel_id']==0)
									{
										$room_details = get_data(TBL_PROPERTY,array('property_id'=>$reservation['room_id']),'property_name')->row_array();
										if(count($room_details)!='0') { echo ucfirst($room_details['property_name']);}else { echo '"No Room Set"';}
									}
									elseif($reservation['channel_id']==11)
									{
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$reservation['ROOMCODE']))->row()->re_id))->row()->property_id))->row()->property_name;
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}
									else if($reservation['channel_id'] == 1)
									{
										$import_mapping_id 	= get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'roomtype_id'=>$reservation['roomTypeID'],'rate_type_id'=>$reservation['ratePlanID']),'map_id,rateAcquisitionType,rateplan_id')->row();
										if($import_mapping_id->rateAcquisitionType=='SellRate')
										{
											$import_mapping_ids = $import_mapping_id->map_id;
										}
										else
										{
											$import_mapping_ids = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_type_id'=>$import_mapping_id->rateplan_id),'map_id,rateAcquisitionType,rateplan_id')->row(); 
											
											$import_mapping_ids = $import_mapping_ids->map_id;
										}
										$room_ids	=	get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$reservation['channel_id'],'import_mapping_id'=>$import_mapping_ids))->row();
										if($room_ids->property_id!='' && $room_ids->rate_id==0)
										{
											$roomName 	= @get_data(TBL_PROPERTY,array('property_id'=>$room_ids->property_id))->row()->property_name;
										}
										else
										{
											$rateRoomName 	= @get_data(RATE_TYPES,array('room_id'=>$room_ids->property_id,'rate_type_id'=>$room_ids->rate_id))->row();
											if($rateRoomName->rate_name!='')
											{
												$roomName = $rateRoomName->rate_name;
											}
											else
											{
												$roomName = '#'.$rateRoomName->uniq_id;
											}
										}
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}
									elseif($reservation['channel_id']==2)
									{
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$reservation['id'],'B_rate_id'=>$reservation['rate_id']))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}

									?></span></li>
									<li>Name : <span class="pull-right"><?php echo $reservation_name;?></span></li>
									<li>Booking ID : <span class="pull-right"><?php echo $reservation['reservation_code']?></span></li>
									<li>Arrival : <span class="pull-right"><?php echo $reservation['start_date']?></span></li> 
									<li>Departure : <span class="pull-right"><?php echo $reservation['end_date']?></span></li>
									<li>Days : <span class="pull-right"><?php echo $reservation['num_nights'];?></span></li>
									<li>Guest : <span class="pull-right"><?php echo $reservation['members_count'];?></span></li>
									<li>Price : <span class="pull-right"><?php  if($reservation['channel_id']==0){echo $reservation['num_nights'] * $reservation['price'];}else { echo  $reservation['price'];}?> <?php echo $currency;?></span></li>
									</ul>
									</div>
								<?php
								} 
								else 
								{
								?>
								<?php if($reservation['channel_id'] == 0){ ?>
								<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_order/<?php echo insep_encode($reservation['reservation_id']); ?>">
								<?php } else { ?>
								<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_channel/<?php echo secure($reservation['channel_id']).'/'.insep_encode($reservation['reservation_id']); ?>">
								<?php  } ?>
									<button style="display:block" class="<?php echo $btn_color.' '.$book_color.' '.$reservation['reservation_id'];?>" type="button">
									<?php if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id)){
									if($reservation['channel_id']==2)
									{
										if($reservation['guest_name']!='')
										{
											$reservation_name 	=	$reservation['guest_name'];
										}
										else
										{
											$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('roomreservation_id'=>$reservation['reservation_code']))->row()->reservation_id),'first_name,last_name')->row();
											$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
										}
									}
									else
									{
										$reservation_name	=	$reservation['guest_name'];
									}
									//echo $reservation_name;
									}else { echo ''; } ?> 
									<?php
									if($reservation['channel_id']=='0')
									{
										  $book_logo = get_data(CONFIG,array('id'=>'1'))->row()->logo_book;
										  $path = "uploads/logo/small/";
									}
									else
									{
										 $book_logo = get_data(TBL_CHANNEL,array('channel_id'=>$reservation['channel_id']))->row()->logo_book;
										 $path = "uploads/small/";
									}
									?>
									<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($path.$book_logo));?>" class="pull-right ">
									</button>
									</a>
									<div class="<?php echo $a_color.' '.$reservation['reservation_id']?>" id="<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>">
									<ul>
									<li>Room Name : <span class="pull-right">
									<?php 
										if($reservation['channel_id']==0)
										{
											$room_details = get_data(TBL_PROPERTY,array('property_id'=>$reservation['room_id']),'property_name')->row_array();
											if(count($room_details)!='0') { echo ucfirst($room_details['property_name']);}else { echo '"No Room Set"';}
										}
										elseif($reservation['channel_id']==11)
										{
											$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$reservation['ROOMCODE']))->row()->re_id))->row()->property_id))->row()->property_name;
											if($roomName)
											{
												echo ucfirst($roomName);
											}
											else
											{
												echo '"No Room Set"';
											}
										}
										else if($reservation['channel_id'] == 1)
										{
											
											$import_mapping_id 	= get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'roomtype_id'=>$reservation['roomTypeID'],'rate_type_id'=>$reservation['ratePlanID']),'map_id,rateAcquisitionType,rateplan_id')->row();
											if($import_mapping_id->rateAcquisitionType=='SellRate')
											{
												$import_mapping_ids = $import_mapping_id->map_id;
											}
											else
											{
												$import_mapping_ids = get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_type_id'=>$import_mapping_id->rateplan_id),'map_id,rateAcquisitionType,rateplan_id')->row(); 
												
												$import_mapping_ids = $import_mapping_ids->map_id;
											}
											$room_ids	=	get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$reservation['channel_id'],'import_mapping_id'=>$import_mapping_ids))->row();
											if($room_ids->property_id!='' && $room_ids->rate_id==0)
											{
												$roomName 	= @get_data(TBL_PROPERTY,array('property_id'=>$room_ids->property_id))->row()->property_name;
											}
											else
											{
												$rateRoomName 	= @get_data(RATE_TYPES,array('room_id'=>$room_ids->property_id,'rate_type_id'=>$room_ids->rate_id))->row();
												if($rateRoomName->rate_name!='')
												{
													$roomName = $rateRoomName->rate_name;
												}
												else
												{
													$roomName = '#'.$rateRoomName->uniq_id;
												}
											}
											if($roomName)
											{
												echo ucfirst($roomName);
											}
											else
											{
												echo '"No Room Set"';
											}
										}
										elseif($reservation['channel_id']==2)
										{
											$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$reservation['id'],'B_rate_id'=>$reservation['rate_id']))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
											if($roomName)
											{
												echo ucfirst($roomName);
											}
											else
											{
												echo '"No Room Set"';
											}
										}
									?></span></li>
									<li>Name : <span class="pull-right"><?php echo $reservation_name;?></span></li>
									<li>Booking ID : <span class="pull-right"><?php echo $reservation['reservation_code']?></span></li>
									<li>Arrival : <span class="pull-right"><?php echo $reservation['start_date']?></span></li> 
									<li>Departure : <span class="pull-right"><?php echo $reservation['end_date']?></span></li>
									<li>Days : <span class="pull-right"><?php echo $reservation['num_nights'];?></span></li>
									<li>Guest : <span class="pull-right"><?php echo $reservation['members_count'];?></span></li>
									<li>Price : <span class="pull-right"><?php if($reservation['channel_id']==0){ echo $reservation['num_nights'] * $reservation['price']; } else { echo $reservation['price'];}?> <?php echo $currency;?></span></li>
									</ul>
									</div>
								<?php 
								} 
								?>
						</td>
                		<?php
							
									//}
								//}
							}
                        /*}*/
						}
					}
                }
                else
                {
                   echo '<td> </td>';
                }
                @array_push($rer_name,$reservation['reservation_id']);
				@array_push($rer_cha_id,$reservation['channel_id']);
				@array_push($rer_date_id,$reservation['start_date']);
                $o_loop++;
            }
            ?>
            </tr>
    <?php 
				}
				}

            }
			//echo 'property_id'. $property_id.'<br>';
        }
    }
    ?>

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
		
				///$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_'.$v))->row()->d_total_amount;
	?>
<tr class="p-b-o contents_<?php echo $property_id;?>" style="display:none;">
<td class="ha2"><span class="pull-left"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?></span></td>
<td bgcolor="#a6a6a6" class="w_bdr">P</td>
<?php 
foreach($period as $date)
{
$j=$date->format("d");
$ss_da = $date->format("d/m/Y");
if($refun=='1')
{
	$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
	if(count($sub_rates)!=0)
	{
		$sub_rate = $sub_rates['refund_amount'];
	}
}
elseif($refun=='2')
{
	$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
	if(count($sub_rates)!=0)
	{
		$sub_rate = $sub_rates['non_refund_amount'];
	}
}
/*if($j%2 == 0)
{
	$color='tabl_even';
}
else
{
	$color='tabl_add';
}*/
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
	//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
	if(count($sub_rates)!='0')
	{
		if($sub_rate!=0)
		{
	?>
<td bgcolor="<?php echo $color;?>" class="w_bdr">
<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo $sub_rate;?> <?php } ?></td><?php } else { ?>
<td bgcolor="<?php echo $color;?>" class="w_bdr">
<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
 <?php }}else { ?>
<td bgcolor="<?php echo $color;?>" class="w_bdr">
<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
<?php }} else { ?> 
<td bgcolor="<?php echo $color;?>" class="w_bdr">
<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
<?php } }?>
</tr>
<?php 		}
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
		<tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;">
		<td class="ha2"><span class="pull-left"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?></span></td>
		<td bgcolor="#a6a6a6" class="w_bdr">P</td>
		<?php 
		foreach($period as $date)
		{
		$j=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		if($refun=='1')
		{
			$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['refund_amount'];
			}
		}
		elseif($refun=='2')
		{
			$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['non_refund_amount'];
			}
		}
		/*if($j%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
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
			//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
			if(count($sub_rates)!='0')
			{
				if($sub_rate!=0)
				{
		?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo $sub_rate;?></a><?php } else { ?> <?php echo $sub_rate;?> <?php   }?></td><?php } else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		 <?php }}else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php }} else { ?> 
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php } }?>  
		</tr>       
				<?php
			}
		}
	}
	else if($pricing_type==1 && $non_refund==1)
	{
		$v=1;
		$refun=2;
		?>
		<tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;">
		<td class="ha2"><span class="pull-left"><?php echo ucfirst($property_name);?> - Non refundable</span></td>
		<td bgcolor="#a6a6a6" class="w_bdr">P</td>
		<?php
		foreach($period as $date)
		{
		$j=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		if($refun=='1')
		{
			$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['refund_amount'];
			}
		}
		elseif($refun=='2')
		{
			$sub_rates = get_data(RESERV,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['non_refund_amount'];
			}
		}
		/*if($j%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
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
			//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
			if(count($sub_rates)!='0')
			{
				if($sub_rate!=0)
				{
		?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td><?php } else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php }}else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php }} else { ?> 
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php } }?>  
		</tr>
		
		<?php
	}
	?>
	<?php 
	if(count($all_rate_types)!=0)
	{
		foreach($all_rate_types as $rate_types)
		{
			extract($rate_types);
			$all_rates = get_data(RATE_TYPES_REFUN,array('room_id'=>$property_id,'uniq_id'=>$uniq_id,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->result_array();
	?>
            <tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;" >
            
			<td rowspan="2" class="ha2 ss_main_row_rate" width="400"><a href="javascript:;" class="show_e">
			<span class="pull-left rate_clr"><strong><?php echo $rate_name?ucfirst($rate_name):'#'.$uniq_id;?></strong></span>
    <?php if($pricing_type==2) { ?> <?php }else if($pricing_type==1 && $non_refund==1){?> <?php } ?>
    </a></td>
    
			<td bgcolor="#a6a6a6">P</td>
		<?php 
			foreach($period as $date)
			{
				$i=$date->format("d");
				$ss_da = $date->format("d/m/Y");
				/*if($i%2 == 0)
				{
					$color='tabl_even';
				}
				else
				{
					$color='tabl_add';
				}*/
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
					$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id))->row();
					if(count($single)!='0')
					{
						if($single->availability==0 || $single->stop_sell==1)
						{
							$color = '#FF0000';
						}
			?>
			<td>
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Price"><?php echo floatval($single->price); ?> </a><?php } else { ?><?php echo floatval($single->price); ?> <?php } ?></td>
			<?php } else  { ?>
			<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php } } else { ?> 
			<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php }}
		?>
	</tr>
	
			<tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;" >
	  <td bgcolor="#a6a6a6">A</td>
	<?php 
	foreach($period as $date)
	{
		$i=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		/*if($i%2 == 0)
		{
			$color='#669933';
		}*/
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
			$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id))->row();
			if(count($single)!='0')
			{
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
		?>
		<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_R';?>"> 
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Availability"><?php echo ($single->availability); ?> </a> <?php } else {?> <?php echo ($single->availability); ?> <?php } ?></td>
		<?php } else  { ?>  
		<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_R';?>">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php } } else { ?>   
		<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_R';?>">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php }}?>  
	</tr>
 
	<tr class="p-b-o ss_main_rate ss_contents_<?php echo $property_id;?>">
    <td bgcolor="#a6a6a6">SS</td>
        
    <?php 
	foreach($period as $date)
	{
		$i=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		/*if($i%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
		if($date->format("D")=='Sat' || $date->format("D")=='Sun')
		{
			$color='#d9d9d9';
		}	
		else
		{
			$color='#FFFFFF';
		}
		if(in_array($ss_da,$da))
		{
			$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id))->row();
			if(count($single)!='0')
			{
				if($single->availability==0)
				{
					$color = '#FF0000';
				}
				elseif($single->stop_sell==1)
				{
					$color = '#CC99FF';
				}
			?>
	<td bgcolor="<?php //echo $color?>">
   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
            <input type="checkbox"  name="s_rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> class="update_stop_sell_main_rate">
            <?php } else { ?>
           <input type="checkbox"  name="s_rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> disabled="disabled">
            <?php } ?>
        </td>
	<?php } else  { ?>
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input type="checkbox"  name="rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_new_rate">
            <?php } else { ?>
            N/A
            <?php } ?>
        </td>
	<?php } } else { ?> 
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input type="checkbox"  name="rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_new_rate">
            <?php } else { ?>
            N/A
            <?php } ?>
            
        </td>
	<?php }}
	?>
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
				
				///$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_'.$v))->row()->d_total_amount;
		
			?>
		<tr class="p-b-o contents_<?php echo $property_id;?>" style="display:none;">
		<td class="ha2"><span class="pull-left"><?php echo $rate_name?ucfirst($rate_name):'#'.$uniq_id;?> - <?php echo $v.' '.$name;?></span></td>
		<td bgcolor="#a6a6a6" class="w_bdr">P</td>
		<?php 
		foreach($period as $date)
		{
		$j=$date->format("d");
		$ss_da = $date->format("d/m/Y");
		if($refun=='1')
		{

			$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['refund_amount'];
			}
		}
		elseif($refun=='2')
		{
			$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
			if(count($sub_rates)!=0)
			{
				$sub_rate = $sub_rates['non_refund_amount'];
			}
		}
		/*if($j%2 == 0)
		{
			$color='tabl_even';
		}
		else
		{
			$color='tabl_add';
		}*/
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
			//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
			if(count($sub_rates)!='0')
			{
				if($sub_rate!=0)
				{
			?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo $sub_rate;?></a> <?php } else { ?> <?php echo $sub_rate;?> <?php } ?></td>
		<?php } 
		else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		 <?php }}else { ?>
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php }} else { ?> 
		<td bgcolor="<?php echo $color;?>" class="w_bdr">
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
		<?php } }?>
		</tr>
		<?php }
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
				<tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;">
				<td class="ha2"><span class="pull-left"><?php echo $rate_name?ucfirst($rate_name):'#'.$uniq_id;?> - <?php echo $v.' '.$name;?></span></td>
				<td bgcolor="#a6a6a6" class="w_bdr">P</td>
				<?php 
				foreach($period as $date)
				{
				$j=$date->format("d");
				$ss_da = $date->format("d/m/Y");
				if($refun=='1')
				{
					$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
					if(count($sub_rates)!=0)
					{
						$sub_rate = $sub_rates['refund_amount'];
					}
				}
				elseif($refun=='2')
				{
					$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
					if(count($sub_rates)!=0)
					{
						$sub_rate = $sub_rates['non_refund_amount'];
					}
				}
				/*if($j%2 == 0)
				{
					$color='tabl_even';
				}
				else
				{
					$color='tabl_add';
				}*/
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
					//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
					if(count($sub_rates)!='0')
					{
						if($sub_rate!=0)
						{
				?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
				<?php } 
				else { ?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				 <?php }}else { ?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php }} else { ?> 
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php } }?>  
				</tr>       
						<?php
					}
				}
			}
			else if($pricing_type==1 && $non_refund==1)
			{
				$v=1;
				$refun=2;
				?>
				<tr class="p-b-o contents_<?php echo $property_id;?>" style="display: none;">
				<td class="ha2"><span class="pull-left"><?php echo $rate_name?ucfirst($rate_name):'#'.$uniq_id;?> - Non refundable</span></td>
				<td bgcolor="#a6a6a6" class="w_bdr">P</td>
				<?php
				foreach($period as $date)
				{
				$j=$date->format("d");
				$ss_da = $date->format("d/m/Y");
				if($refun=='1')
				{
					$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
					if(count($sub_rates)!=0)
					{
						$sub_rate = $sub_rates['refund_amount'];
					}
				}
				elseif($refun=='2')
				{
					$sub_rates = get_data(RATE_ADD,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun,'rate_types_id'=>$rate_type_id))->row_array();
					if(count($sub_rates)!=0)
					{
						$sub_rate = $sub_rates['non_refund_amount'];
					}
				}
				/*if($j%2 == 0)
				{
					$color='tabl_even';

				}
				else
				{
					$color='tabl_add';
				}*/
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
					//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
					if(count($sub_rates)!='0')
					{
						if($sub_rate!=0)
						{
				?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
				<?php } 
				else { ?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php }}else { ?>
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php }} else { ?> 
				<td bgcolor="<?php echo $color;?>" class="w_bdr">
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit())){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php } }?>  
				</tr>
				<?php
			}
		}
	}
		
	}
}
?>
 
<tr >
    <td colspan="<?php echo $t_col+1; ?>" bgcolor="#366092" style="text-align:left; color:#fff; padding-left:20px;">
    <div class="col-md-10">
    <div class="col-md-2 pull-left">
      <div class="checkbox mar-top7">
        <label>
          <input type="checkbox" id="show_reservation" checked="checked"> Show Reservations  
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
			$con_ch = $channel_id;
			if($con_ch==2)
			{
				$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->row()->xml_type;
			}
			else
			{	
				$chk_allow = '';
			}
			if(($con_ch==2 && ($chk_allow==2 || $chk_allow==3))||$con_ch!=2)
			{
			$pid="";
			$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$con_ch))->row();
			$channelid=$cha_logo->channel_id;
?>
				<div class="change_month_replaces_<?php echo $con_ch;?>">
		
				<div class="dash-b-n1">
		
				<div class="row-fluid clearfix">
		
				<div class="col-md-12 col-sm-12 new-k2">
		
				<div class="dash-b-n2">
		
				<div class="row">
		
				<div class="col-md-3 col-sm-3"><div class="">
		
				<div class="on-2"><div class="on-offswitch">
		
					<input type="checkbox" name="on-offswitch" class="on-offswitch-checkbox reservationno" id="myon-offswitch" checked>
		
					<label class="on-offswitch-label" for="myon-offswitch">
		
						<span class="on-offswitch-inners reservationno"></span>
		
						<span class="on-offswitch-switchs"></span>
		
					</label>
		
				</div>
		
				</div>
		  
				<input type="hidden" name="cur_month" id="next_months" value="<?php echo date('m')+1;?>" />
		
				<input type="hidden" name="cur_month" id="prev_months" value="<?php echo date('m')-1;?>" />
		
				</div>
				
				</div>
		
				<div class="col-md-3 col-sm-3 bor-o">
		
		<a class="btn btn-default change_cal_custom" href="javascript:;" role="button" data-toggle="modal" data-target="#myModal-ps" current-channel="<?php echo $con_ch;?>" current-rate="<?php echo $rate_id;?>">Customize calendar</a>
		
		</div>
				
				<div class="col-md-3 col-sm-3 bor-o">
		
		<a href="javascript:;" class="pull-left mar-right prev_months display_none" current-channel="<?php echo $con_ch;?>" current-rate="<?php echo $rate_id;?>"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
		
		<div class="dropdown pull-left">
		
		  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu_item1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		
			<?php echo date('F Y');?>	
		
			<span class="caret"></span>
		
		  </button>
		
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="ajax_cal">
		
		  <?php 
		
		for ($m=date('m'); $m<=date('m')+14; $m++) {
		
			?>
		
			<li current-channel="<?php echo $con_ch;?>" class="<?php if(date('m')==$m) { ?>active<?php } ?> change_months" custom="<?php echo $m;?>"><a href="javascript:;" >
			
			<?php echo date('F Y', mktime(0,0,0,$m, 1, date('Y'))); //echo date('F Y', mktime(0,0,0,$m));?></a></li>
		
			<?php } ?>
		
		   </ul>
		
		</div>
		
		<a href="javascript:;" class="pull-left mar-left next_months" current-channel="<?php echo $con_ch;?>" current-rate="<?php echo $rate_id;?>"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>
		
		</div>
		<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
				<div class="col-md-3 col-sm-3 bor-o">
		
		<a class="btn btn-default" href="<?php echo base_url();?>inventory/bulk_update/reservation_no" role="button">Bulk Update</a>
		
		</div>
				<?php } ?>
				</div>	
		
				</div>
		
				<div id="customize_dates_<?php echo $con_ch;?>">
		
		<?php
		
				$today = date('d/m/Y');
		
				$today_date = date('Y/m/d');
		
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
		
				foreach($period as $date){
		
				$dates[] 	= $date->format("d");
		
				$months[] 	= date('F Y',strtotime($date->format("Y/m/d")));
		
				$month[] 	= $date->format("m"); 
		
				$last[]     = $date->format("t"); 
		
				$da[]		= $date->format("d/m/Y");
		
				if($date->format("m")==date('m'))
		
				{
		
					$month_s 	= date('F Y',strtotime($date->format("Y/m/d")));
		
					$fi++;
		
					
		
				}
		
				else
		
				{
		
					$month_l = date('F Y',strtotime($date->format("Y/m/d")));
		
					$li++;
		
				}
		
				}
		
				$show_month = (array_unique($month));
		
				$last_date   = (array_unique($last));
		
		?>
		
		
		
		
		
		<form method="post" action="<?php echo base_url();?>inventory/reservation_update_no" class="master_calendar_form form-inline">
		
		<input type="hidden" name="channe_id_update" id="channe_id_update" value="<?php echo $con_ch;?>"/>
		
		<input type="hidden" name="alter_checkbox" id="alter_checkbox_<?php echo $con_ch;?>" />
		
		<input type="hidden" name="alter_checkbox_refund" id="alter_checkbox_refund_<?php echo $con_ch;?>" />
		
		<input type="hidden" name="alter_checkbox_rate" id="alter_checkbox_rate_<?php echo $con_ch;?>" />
		
		<input type="hidden" name="alter_checkbox_rate_refund" id="alter_checkbox_rate_refund_<?php echo $con_ch;?>" />
		
		<div class="content_check">
		
		<div class="table table-responsive">
		
		<table class="table table-bordered reservation_no_channel table_stricky">
		
		<thead>
		
		<tr>
		
		<th width="400" align="left" valign="middle" bgcolor="#ffffff" style="background:#ffffff;"><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>"></th>
		
		<?php 
		
		$t_col='0';
		
		foreach($show_month as $val)  {
		
			
		
			if($val==date('m')){ $c_head = $month_s; $col=$fi; $t_col=$t_col+$col;} else { $c_head = $month_l; $col=$li-1;$t_col=$t_col+$col;}
		
			 ?>
		
		<th colspan="<?php echo $col;?>" class="text-center tal_td_bor">
		
		<h3>
		
		<strong><?php echo $c_head; ?></strong>
		
		</h3>
		
		<?php } ?>
		
		</tr>
		
		<tr>
		
				<td width="400" bgcolor="#8db4e2">
		
								<div class="checkbox col-md-6 mp">
		
							<label>
		
								<input type="checkbox" id="cal_m_<?php echo $con_ch;?>" value="1" onclick="msccc('m_<?php echo $con_ch;?>','<?php echo $con_ch;?>')">Min
		
							</label>
		
						</div>
		
						<div class="checkbox col-md-6 mp">
		
							<label>
		
								<input type="checkbox" id="cal_s_<?php echo $con_ch;?>" value="1" onclick="msccc('s_<?php echo $con_ch;?>','<?php echo $con_ch;?>')">Stop
		
							</label>
		
						</div>
		
						<div class="checkbox col-md-6 mp">
		
							<label>
		
								<input type="checkbox" id="cal_c1_<?php echo $con_ch;?>" value="1" onclick="msccc('c1_<?php echo $con_ch;?>','<?php echo $con_ch;?>')">CTA
		
							</label>
		
						</div>
		
						<div class="checkbox col-md-6 mp">
		
							<label>
		
								<input type="checkbox" id="cal_c2_<?php echo $con_ch;?>" value="1" onclick="msccc('c2_<?php echo $con_ch;?>','<?php echo $con_ch;?>')">CTD
		
							</label>
		
						</div>
		
		
		
				</td>
		
				
		
				<td width="7" bgcolor="#8db4e2">&nbsp;</td>
		
				<?php 
		
					$current_day_this_month = date('d'); 
		
					$last_day_this_month  = date('t');
		
					foreach($period as $date){
		
						$i=$date->format("d");
		
						if($date->format("D")=='Sat' || $date->format("D")=='Sun')
		
						{
		
							$bg_clr = "#366092";
		
						}
		
						else
		
						{
		
							$bg_clr = "#8db4e2";
		
						}
		
					?>
		
					<td width="28" bgcolor="<?php echo $bg_clr;?>"><?php echo $date->format("d")?> <br>  <?php echo $date->format("D")?></td>
		
				<?php } ?>
		
			</tr>
		
		</thead>
		
			<tbody>
			<?php
			$all_propertyid = $this->channel_model->getMappedRoom_Rate($channelid);
			if(count($all_propertyid!=0))
			{
				foreach($all_propertyid as $propertyid)
				{	
						
					$pid=$propertyid->property_id;
			
					$rate_id=$propertyid->rate_id;
			
					if($rate_id!="0")
					{
						$ratename= get_data('room_rate_types',array('rate_type_id'=>$rate_id))->row();
						if($ratename->rate_name!='')
						{
							$rate_name=$ratename->rate_name;
						}
						else
						{
							$rate_name='#'.$ratename->uniq_id;
						}
					}
					$all_room = get_data(TBL_PROPERTY,array('property_id'=>$pid,'status'=>'Active','owner_id'=>current_user_type(),'droc'=>'1'))->result_array();
					foreach($all_room as $room) 
					{
						extract($room);
						
						//$room_update = get_data(TBL_UPDATE,array('room_id'=>$property_id))->row();
					
						//$reservation_count = $this->inventory_model->reservation_count(insep_encode($property_id));
					
						/* if($room_update)
						{
							$start = explode(',',$room_update->start_date);
					
							$end = explode(',',$room_update->end_date);
						} */
						if($non_refund==1)
						{
							$members = $member_count+$member_count;
						}
						else
						{
							$members = $member_count;
						}
						if($rate_id=="0")
						{
							$main_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>'0','guest_count'=>'0','refun_type'=>'0','channel_id'=>$con_ch))->row_array(); //echo count($main_available); 			
							if(count($main_available)!=0)
							{
								
							?>
							<tr class="p-b-o">
							   
								<td width="400" rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?> text-info"><strong><?php echo ucfirst($property_name);?></strong></td>
						
								<td bgcolor="#a6a6a6">P</td>
						
							   <?php 
								foreach($period as $date)
								{
							
									$i=$date->format("d");
							
									$ss_da = $date->format("d/m/Y");
							
									/*if($i%2 == 0)
							
									{
							
										$color='tabl_even';
							
									}
							
									else
							
									{
							
										$color='tabl_add';
							
									}*/
							
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
										$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
										if(count($single)!='0')
										{
											if($single->availability==0 || $single->stop_sell==1)
											{
												$color = '#FF0000';
											}
							
								?>
							
								<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price"><?php echo floatval($single->price); ?> </a><?php } else { ?><?php echo floatval($single->price); ?> <?php } ?></td>
							
								<?php } else  { ?>
							
								<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
							
								<?php } } else { ?> 
							
								<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
							
								<?php }
								}
							   ?>
						
							</tr>
							
							<tr class="p-b-o">
						
								<td bgcolor="#a6a6a6">A</td>
						
								<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='#669933';
						
								}*/
						
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
						

						
										$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
						

									
						
								if(count($single)!='0')
						
								{
						
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
						
							?>
						
							  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Availability"><?php echo ($single->availability); ?> </a> <?php } else {?> <?php echo ($single->availability); ?> <?php } ?></td>
						
							<?php } else  { ?>  
						
							  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php } } else { ?>   
						
							  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }}?> 
						
							</tr>
						  
							<tr class="p-b-o msccc m_<?php echo $con_ch;?>">
						
									<td bgcolor="#a6a6a6">M</td>
						
									
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
						
	
						
										$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
						


						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}	?>
						
							<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price"><?php echo ceil($single->minimum_stay); ?> </a><?php } else { ?><?php echo ceil($single->minimum_stay); ?> <?php } ?></td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color;?>"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }}
						
							?>
						
								   
						
								</tr>
							
							<tr class="p-b-o msccc s_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">SS</td>
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{

									$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
						
					
									
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}	?>
						
							<td bgcolor="<?php //echo $color?>">
						
						   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
						
									<input type="checkbox" value="1" <?php if($single->stop_sell=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" class="update_stop_sell" <?php } else { ?> name="new_room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" <?php } ?> custom="<?php echo $con_ch;?>">
						
									<?php } else { ?>
						
								   <input type="checkbox"   value="1" <?php if($single->stop_sell=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" <?php } else { ?> name="new_room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" <?php } ?> disabled="disabled">
						
									<?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1">
						
									<?php } else { ?>
						
									N/A
						
									<?php } ?>
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1">
						
									<?php } else { ?>
						
									N/A
						
									<?php } ?>
						
									
						
								</td>
						
							<?php }}
						
							?>
						
							</tr>
						
							<tr class="p-b-o msccc c1_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">CTA</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
						

						
										$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
						
						
									
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}	?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
						
									<input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" class="update_stop_sell" <?php } else { ?> name="new_room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
						
									<?php } else { ?>
						
									<input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" <?php } else { ?> name="new_room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" <?php } ?> value="1" disabled="disabled">
						
									<?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" value="1" >
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" value="1" >
						
								</td>
						
							<?php }}?>
						
							</tr>
						
							<tr class="p-b-o msccc c2_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">CTD</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
									$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
									if(count($single)!='0')
									{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}
						
								?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit())){?> 
						
									<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" class="update_stop_sell" <?php } else { ?> name="new_room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
						
									<?php } else { ?>
						
									<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked" name="room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" <?php } else { ?> name="new_room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" <?php } ?> value="1" disabled="disabled">
						
									<?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" value="1" >
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" value="1"  >
						
								</td>
						
							<?php }}
						
							?>
						
							</tr>
						
							<?php 
							}
							
							if($pricing_type==2) 
							{
						//echo 'pricing_type'.$pricing_type;
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
										$sub_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
										if(count($sub_available)!=0)
										{
						
										?>
						
							<tr class="p-b-o">
						
								<td class="ha2"><span class="pull-left"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?></span></td>
						
								<td bgcolor="#a6a6a6" class="w_bdr">P</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$j=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								if($refun=='1')
						
								{

						
										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
				
									
						
									if(count($sub_rates)!=0)
						
									{
						
										$sub_rate = $sub_rates['refund_amount'];
						
									}
						
								}
						
								elseif($refun=='2')
						
								{
		
										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();


						
									if(count($sub_rates)!=0)
						
									{
						
										$sub_rate = $sub_rates['non_refund_amount'];
						
									}
						
								}
						
								/*if($j%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
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
						
									if(count($sub_rates)!='0')
						
									{
						
										if($sub_rate!=0)
						
										{
						
									?>
						
											<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
						
								  <?php }
						
										else 
						
										{ ?>
						
											<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								 <?php 	}
						
									}
						
									else 
						
									{ 					
								?>
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								<?php }
						
								}
						
								else { ?> 
						
										<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								<?php } 
						
								}
						
								?>
						
							  </tr>
						
							  <?php 	}
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
										$sub_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
										if(count($sub_available)!=0)
										{
						
										?>
						
							<tr class="p-b-o">
						
								<td class="ha2"><span class="pull-left"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?></span></td>
						
								<td bgcolor="#a6a6a6" class="w_bdr">P</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$j=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								if($refun=='1')
						
								{
						
										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();

						
						
									if(count($sub_rates)!=0)
						
									{
						
										$sub_rate = $sub_rates['refund_amount'];
						
									}
						
								}
						
								elseif($refun=='2')
						
								{

										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
										

						
									if(count($sub_rates)!=0)
						
									{
						

										$sub_rate = $sub_rates['non_refund_amount'];
						
									}
						
								}
						
								/*if($j%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
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
						
						
						
									if(count($sub_rates)!='0')
						
									{
						
										if($sub_rate!=0)
						
										{
						
								?>
						
							   <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
						
								<?php } 
						
								else { ?>
						
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								 <?php }}else { ?>
						
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								<?php }} else { ?> 
						
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
								<?php } }?>  
						
								</tr>       
						
										<?php
										}
						
									}
						
								}
						
							}
							else if($pricing_type==1 && $non_refund==1)
							{
						
								$v=1;
						
								$refun=2;
								$sub_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
								if(count($sub_available)!=0)
								{
						
							?>
						
							<tr class="p-b-o">
						
							<td rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?>"><span class="pull-left"><?php echo ucfirst($property_name);?> - Non refundable</span></td>
						
							<td bgcolor="#a6a6a6" class="w_bdr">P</td>
						
							<?php
						
							foreach($period as $date)
						
							{
						
								$j=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								if($refun=='1')
						
								{

						
										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();

						
						
									if(count($sub_rates)!=0)
						
									{
						
										$sub_rate = $sub_rates['refund_amount'];
						
									}
						
								}
						
								elseif($refun=='2')
						
								{
						
									
										$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();

						
				
						
									if(count($sub_rates)!=0)
						
									{
						
										$sub_rate = $sub_rates['non_refund_amount'];
						
									}
						
								}
						
								/*if($j%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
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
						
									//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
						
									
						
									if(count($sub_rates)!='0')
						
									{
						
										if($sub_rate!=0)
						
										{
						
							?>
						
							 <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
						
							<?php } 
						
							else { ?>
						
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }}else { ?>
						
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }} else { ?> 
						
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php } }?>  
						
							</tr>
						
							
						
							<tr class="p-b-o">
						
							<td bgcolor="#a6a6a6">A</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='#669933';
						
								}*/
						
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
						
	
										$single = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();


									
									
						
									
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0 && $single->availability!='')
						
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
									else
						
									{	
						
										$color='#D9E4C3';
						
									}
						
							?>
						
							<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability"><?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';}?> </a> <?php } else {?> <?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';} ?> <?php } ?></td>
						
							<?php } else  { ?>  
						
							<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php } } else { ?>   
						
							<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }}?> 
						
							</tr>
						
							
						
							<tr class="p-b-o msccc m_<?php echo $con_ch;?>">
						
									<td bgcolor="#a6a6a6">M</td>
						
									
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
	
						
										$single = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
															
								
						
									
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}	?>
						
							<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo ceil($single->minimum_stay); ?> </a><?php } else { ?><?php echo ceil($single->minimum_stay); ?> <?php } ?></td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color;?>"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
						
							<?php }
						
							}
						
							?>
						
								   
						
								</tr>
						
							
						
							<tr class="p-b-o msccc s_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">SS</td>
						
								
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
	
						
										$single = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();

		
						
								
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}
						
							?>
						
							<td bgcolor="<?php //echo $color?>">
						
						   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
						
									<input type="checkbox" <?php if($single->stop_sell=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" class="update_check" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" <?php } ?>  value="1"  custom="<?php echo $con_ch;?>">
						
									<?php } else { ?>
						
								   <input type="checkbox"  <?php if($single->stop_sell=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" <?php } ?> value="1" disabled="disabled">
						
									<?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit())){?> 
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" value="1">
						
									<?php } else { ?>
						
									N/A
						
									<?php } ?>
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit())){?> 
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" value="1">
						
									<?php } else { ?>
						
									N/A
						
									<?php } ?>
						
									
						
								</td>
						
							<?php }}
						
							?>
						
							</tr>
						
							
						
							<tr class="p-b-o msccc c1_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">CTA</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
						
						
										$single = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
						
						
									
									
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}
						
							?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
						
						 <input type="checkbox" <?php if($single->cta=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" class="update_check" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" <?php } ?> value="1" custom="<?php echo $con_ch;?>">
						
						 <?php } else { ?>
						
						 <input type="checkbox" <?php if($single->cta=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" <?php } ?> value="1" disabled="disabled"/>
						
						 <?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" value="1" >
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" value="1" >
						
								</td>
						
							<?php }}
						
							?>
						
							</tr>
						
								
						
							<tr class="p-b-o msccc c2_<?php echo $con_ch;?>">
						
								<td bgcolor="#a6a6a6">CTD</td>
						
							<?php 
						
							foreach($period as $date)
						
							{
						
								$i=$date->format("d");
						
								$ss_da = $date->format("d/m/Y");
						
								/*if($i%2 == 0)
						
								{
						
									$color='tabl_even';
						
								}
						
								else
						
								{
						
									$color='tabl_add';
						
								}*/
						
								if($date->format("D")=='Sat' || $date->format("D")=='Sun')
						
								{
						
									$color='#d9d9d9';
						
								}	
						
								else
						
								{
						
									$color='#FFFFFF';
						
								}
						
								if(in_array($ss_da,$da))
						
								{
						
	
										$single = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
					
						
									
									
						
								if(count($single)!='0')
						
								{
						
									if($single->availability==0)
						
									{
						
										$color = '#FF0000';
						
									}
						
									elseif($single->stop_sell==1)
						
									{
						
										$color = '#CC99FF';
						
									}
						
							?>
						
							<td bgcolor="<?php //echo $color?>">
						
							<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
						
									<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" class="update_check" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
						
									<?php } else { ?>
						
									<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked" name="channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" <?php } else { ?> name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" <?php } ?> value="1"  disabled="disabled">
						
									<?php } ?>
						
								</td>
						
							<?php } else  { ?>
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" value="1">
						
								</td>
						
							<?php } } else { ?> 
						
							<td bgcolor="<?php //echo $color?>">
						
									<input type="checkbox"  name="new_channel_room[<?php echo $property_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" value="1" >
						
								</td>
						
							<?php }
						
						}
						
						
						
							?>
						
							</tr>
						
							
							<?php
								}
							}
						}
						if($rate_id!="0")
						{
							$main_rate_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>'0','refun_type'=>'0','channel_id'=>$con_ch))->row_array();
							if(count($main_rate_available)!='0')
							{
						?>
					
					   <tr class="p-b-o">
					
							<td width="400" rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?> rate_clr"><strong><?php echo ucfirst($rate_name);?></strong></td>
					
							<td bgcolor="#a6a6a6">P</td>
					
						   <?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
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
					
						
					
									$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
					
						
								
					
							if(count($single)!='0')
					
							{
					
							if($single->availability==0 || $single->stop_sell==1)
					
							{
					
								$color = '#FF0000';
					
							}
					
						?>
					
						<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
					
						
					
						<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price"><?php echo floatval($single->price); ?> </a>
					
						
					
						<?php } else { ?><?php echo floatval($single->price); ?> <?php } ?></td>
					
						<?php } else  { ?>
					
						<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
						
					
						<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price">N/A </a>
					
						
					
						<?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?> 
					
						<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
						<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price">N/A </a>
					
						
					
						<?php } else { ?> N/A <?php } ?></td>
					
						<?php }}?>
					
						</tr>
					
					   <tr class="p-b-o">
					
							<td bgcolor="#a6a6a6">A</td>
					
							<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='#669933';
					
							}*/
					
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
					
								$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
	
								
								
					
							if(count($single)!='0')
					
							{
					
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
					
						?>
					
						  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Availability"><?php echo ($single->availability); ?> </a> <?php } else {?> <?php echo ($single->availability); ?> <?php } ?></td>
					
						<?php } else  { ?>  
					
						  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?>   
					
						  <td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}?> 
					
						</tr>
						
					   <tr class="p-b-o msccc m_<?php echo $con_ch;?>">
					
								<td bgcolor="#a6a6a6">M</td>
					
								
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
								
									 $single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
					
								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}	?>
					
						<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price"><?php echo ceil($single->minimum_stay); ?> </a><?php } else { ?><?php echo ceil($single->minimum_stay); ?> <?php } ?></td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color;?>"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}
					
						?>
						</tr>
					
					   <tr class="p-b-o msccc s_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">SS</td>
					
							
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
			
									$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
					
							
		
								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}	?>
					
						<td bgcolor="<?php //echo $color?>">
					
					   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  <?php if($single->stop_sell=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]"  class="update_stop_sell_rate" <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]"  <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
					
								<?php } else { ?>
					
							   <input type="checkbox"  <?php if($single->stop_sell=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]"  <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]"  <?php } ?> value="1"  disabled="disabled">
					
								<?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1">
					
								<?php } else { ?>
					
								N/A
					
								<?php } ?>
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1">
					
								<?php } else { ?>
					
								N/A
					
								<?php } ?>
					
								
					
							</td>
					
						<?php }}
					
						?>
					
						</tr>
					
					   <tr class="p-b-o msccc c1_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">CTA</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
		
									$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
	

								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}	?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]"  class="update_stop_sell_rate" <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]"  <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
					
								<?php } else { ?>
					
								<input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]"  <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]"  <?php } ?> value="1" disabled="disabled">
					
								<?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]" value="1" >
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][cta][<?php echo $ss_da;?>]" value="1" >
					
							</td>
					
						<?php }}
					
						?>
					
						</tr>
					
					   <tr class="p-b-o msccc c2_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">CTD</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					

									$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();
				
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}
					
							?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox" <?php if($single->ctd=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]"  class="update_stop_sell_rate" <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]"  <?php } ?> value="1"  custom="<?php echo $con_ch;?>">
					
								<?php } else { ?>
					
								<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked" name="room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]"  <?php } else { ?> name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]"  <?php } ?>  value="1"  disabled="disabled">
					
								<?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]" value="1" >
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][ctd][<?php echo $ss_da;?>]" value="1"  >
					
							</td>
					
						<?php }}
					
						?>
					
						</tr>
					
					   <?php 
							}
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
					
									if($rate_id!="0"){
										$sub_rate_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
										if(count($sub_rate_available)!='0')
										{
					
					?>
					
						<tr class="p-b-o">
					
							<td class="ha2"><span class="pull-left"><?php echo ucfirst($rate_name);?> - <?php echo $v.' '.$name;?></span></td>
					
							<td bgcolor="#a6a6a6" class="w_bdr">P</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$j=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							if($refun=='1')
					
							{
					
						
									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					
	
					
								
					
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['refund_amount'];
					
								}
					
							}
					
							elseif($refun=='2')
					
							{
					
			
									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					
				
								
								
					
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['non_refund_amount'];
					
								}
					
							}
					
							/*if($j%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
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
					
								if(count($sub_rates)!='0')
					
								{
					
									if($sub_rate!=0)
					
									{
					
								?>
					
										<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
					
							  <?php }
					
									else 
					
									{ ?>
					
										<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							 <?php 	}
					
								}
					
								else 
					
								{ 
					
							?>
					
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php }
					
							}
					
							else { ?> 
					
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php } 
					
							}
					
							?>
					
						  </tr>
					
						  <?php }
									}
					
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
					
									if($rate_id!="0"){
										$sub_rate_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
				if(count($sub_rate_available)!='0')
				{
					
									?>
					
						<tr class="p-b-o">
					
							<td class="ha2"><span class="pull-left"><?php echo ucfirst($rate_name);?> - <?php echo $v.' '.$name;?></span></td>
					
							<td bgcolor="#a6a6a6" class="w_bdr">P</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$j=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							if($refun=='1')
					
							{
					
								
									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					
								
								
					
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['refund_amount'];
					
								}
					
							}
					
							elseif($refun=='2')
					
							{
					

									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					
												
					
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['non_refund_amount'];
					
								}
					
							}
					
							/*if($j%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
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
					
					
					
								if(count($sub_rates)!='0')
					
								{
					
									if($sub_rate!=0)
					
									{
					
							?>
					
						   <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
					
							<?php } 
					
							else { ?>
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							 <?php }}else { ?>
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php }} else { ?> 
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php } }?>  
					
							</tr>       
							<?php
								}
					
								}
					
								}
					
							}
					
						}
						else if($pricing_type==1 && $non_refund==1)
						{
					
							$v=1;
					
							$refun=2;
							$sub_rate_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$con_ch))->row_array();
				if(count($sub_rate_available)!='0')
				{
					
						?>
					
						<tr class="p-b-o">
					
						<td rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?>"><span class="pull-left"><?php echo ucfirst($property_name);?> - Non refundable</span></td>
					
						<td bgcolor="#a6a6a6" class="w_bdr">P</td>
					
						<?php
					
						foreach($period as $date)
					
						{
					
							$j=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							if($refun=='1')
					
							{
					
				
					
									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					

			
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['refund_amount'];
					
								}
					
							}
					
							elseif($refun=='2')
					
							{
					
							
									$sub_rates = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
					
								if(count($sub_rates)!=0)
					
								{
					
									$sub_rate = $sub_rates['non_refund_amount'];
					
								}
					
							}
					
							/*if($j%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
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
					
								//$single = get_data(TBL_UPDATE,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da))->row();
					
								
					
								if(count($sub_rates)!='0')
					
								{
					
									if($sub_rate!=0)
					
									{
					
						?>
					
						 <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
					
						<?php } 
					
						else { ?>
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}else { ?>
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }} else { ?> 
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } }?>  
					
						</tr>
					
						<tr class="p-b-o">
					
						<td bgcolor="#a6a6a6">A</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='#669933';
					
							}*/
					
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
					
								if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
								{
									$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
								}
					
													
								
					
								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0 && $single->availability!='')
					
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
								else
					
								{	
					
									$color='#D9E4C3';
					
								}
					
						?>
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability"><?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';}?> </a> <?php } else {?> <?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';} ?> <?php } ?></td>
					
						<?php } else  { ?>  
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?>   
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}?> 
					
						</tr>
						
						<tr class="p-b-o msccc m_<?php echo $con_ch;?>">
					
								<td bgcolor="#a6a6a6">M</td>
					
								
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
								if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
								{
									$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
								}
					
					
								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}	?>
					
						<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo ceil($single->minimum_stay); ?> </a><?php } else { ?><?php echo ceil($single->minimum_stay); ?> <?php } ?></td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color;?>"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color;?>"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }
					
						}
					
						?>
					
							   
					
							</tr>
						  
						<tr class="p-b-o msccc s_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">SS</td>
					
							
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
	
									$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
				
								
					
					
							
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}
					
						?>
					
						<td bgcolor="<?php //echo $color?>">
					
					   <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  <?php if($single->stop_sell=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" class="update_check_rate" <?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]"  <?php } ?>  value="1"  custom="<?php echo $con_ch;?>">
					
								<?php } else { ?>
					
							   <input type="checkbox"  <?php if($single->stop_sell=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]"   <?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]"  <?php } ?> value="1" disabled="disabled">
					
								<?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" value="1">
					
								<?php } else { ?>
					
								N/A
					
								<?php } ?>
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> 
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][stop_sell]" value="1">
					
								<?php } else { ?>
					
								N/A
					
								<?php } ?>
					
								
					
							</td>
					
						<?php }}
					
						?>
					
						</tr>
						  
						<tr class="p-b-o msccc c1_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">CTA</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
							
									$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
					
								
								
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}
					
						?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
					
					 <input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" class="update_check_rate"<?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]"  <?php } ?>  value="1"  custom="<?php echo $con_ch;?>">
					
					 <?php } else { ?>
					
					 <input type="checkbox"  <?php if($single->cta=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]"   <?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]"  <?php } ?>  value="1" disabled="disabled"/>
					
					 <?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" value="1" >
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][cta]" value="1" >
					
							</td>
					
						<?php }}
					
						?>
					
						</tr>
							  
						<tr class="p-b-o msccc c2_<?php echo $con_ch;?>">
					
							<td bgcolor="#a6a6a6">CTD</td>
					
						<?php 
					
						foreach($period as $date)
					
						{
					
							$i=$date->format("d");
					
							$ss_da = $date->format("d/m/Y");
					
							/*if($i%2 == 0)
					
							{
					
								$color='tabl_even';
					
							}
					
							else
					
							{
					
								$color='tabl_add';
					
							}*/
					
							if($date->format("D")=='Sat' || $date->format("D")=='Sun')
					
							{
					
								$color='#d9d9d9';
					
							}	
					
							else
					
							{
					
								$color='#FFFFFF';
					
							}
					
							if(in_array($ss_da,$da))
					
							{
					
		
									$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();
				

					
					
							if(count($single)!='0')
					
							{
					
								if($single->availability==0)
					
								{
					
									$color = '#FF0000';
					
								}
					
								elseif($single->stop_sell==1)
					
								{
					
									$color = '#CC99FF';
					
								}
					
						?>
					
						<td bgcolor="<?php //echo $color?>">
					
						<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?>
					
								<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" class="update_check_rate" <?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]"  <?php } ?> value="1" custom="<?php echo $con_ch;?>">
					
								<?php } else { ?>
					
								<input type="checkbox"  <?php if($single->ctd=='1'){?> checked="checked"  name="channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]"   <?php } else { ?> name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]"  <?php } ?> value="1" disabled="disabled">
					
								<?php } ?>
					
							</td>
					
						<?php } else  { ?>
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" value="1">
					
							</td>
					
						<?php } } else { ?> 
					
						<td bgcolor="<?php //echo $color?>">
					
								<input type="checkbox"  name="new_channel_room_rate[<?php echo $property_id;?>][<?php echo $rate_id;?>][<?php echo $v;?>][<?php echo $refun;?>][<?php echo $ss_da;?>][ctd]" value="1" >
					
							</td>
					
						<?php }
					
					}
					
					
					
						?>
					
						</tr>
					
					 <?php
				}
					
					  } 
						}
					}
				}
			}
			?>
		</tbody>
		
		</table>
		<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			?>
		<div class=" pull-right"> 
		
		<!--<input type="button" class="btn btn-success" value="Update" data-toggle="modal" data-target="#channelsModal"/> -->
		
		<input type="submit" class="btn btn-success" value="Update"/> 
		
		<input type="reset" class="btn btn-danger" value="Reset"/>
		
		</div>
        <?php } ?>
		
		
		
		
		
		</div>
		
		
		
		</div>
		
		</form>
		<?php 
		
		/*else
		{
			echo 'hai';
		}*/
	?>
 
</div>

				</div>
			
				</div>

				</div>

				</div>

<?php }} } else {  ?> <div class="bb1">

<br>

<div class="reser"><center><i class="fa fa-globe"></i></center></div>

<h2 class="text-center">You don't have any online channels configured for this room type yet</h2>

<p class="pad-top-20 text-center">You can sell your rooms from hundreds of online sales channels</p>

<br>

<div class="res-p"><center><a class="btn btn-primary" href="<?php echo base_url();?>mapping/connectlist"><i class="fa fa-plus"></i>  Map To Channel</a></center></div>

</div> <?php } } }}else { ?>
<div class="dash-b4">
<div class="col-md-12 col-sm-12">
<div class="bb1">
<br>
<div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
<h2 class="text-center">You don't have any room types yet</h2>
<p class="pad-top-20 text-center"><!--You can apply your different pricing strategies and increase your sales by using rate types--></p>
<br>
<div class="res-p"><center>
<a class="btn btn-primary" href="<?php echo base_url();?>channel/manage_rooms"><i class="fa fa-plus"></i>  Add room type</a>
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
        <h4 class="modal-title" id="myModalLabel">Customize Calender</h4>
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
        <h4 class="modal-title" id="myModalLabel">Customize Calender</h4>
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
  		$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$values = array('1','2','3','4','5','6','7');
		foreach($days as $key=>$day)
		{
  ?>
  <div class="checkbox">
    <label>
      <input type="checkbox" checked name="days[]" value="<?php echo $values[$key]?>" class="reser_no_days"><?php echo $day?> 
    </label>
  </div>
  <?php } ?>
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