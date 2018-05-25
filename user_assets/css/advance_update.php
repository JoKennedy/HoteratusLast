<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n"><h4><a href="<?php echo base_url();?>inventory">Calendar</a>
    <i class="fa fa-angle-right"></i>
    Advanced Updates
    </h4></div>
</div></div>
</div>
<?php
if($action!='reservation_no')
{
error_reporting(0);
$this->db->order_by('property_id','desc');
$all_room = get_data(TBL_PROPERTY,array('owner_id'=>user_id()))->result_array();
if(count($all_room)!=0) {
	?>
<div class="change_month_replace">

<div class="dash-b-n1 new-s">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12 new-k2">

<div class="dash-b-n2">
<div class="row">
<div class="col-md-1 col-sm-1">
 <div class="">
    <div class="on-2">
    	<div class="on-offswitch">
        <input type="checkbox" name="on-offswitch" class="on-offswitch-checkbox" id="myon-offswitch" checked>
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
<div class="col-md-2 col-sm-2 org">
<a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#myModal-p2">Full Update</a>
</div>
<div class="col-md-3 col-sm-3 dr">
<a href="javascript:;" class="pull-left mar-right prev_month display_none"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
<div class="dropdown pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo date('F');?>	
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
  <?php 
for ($m=date('m'); $m<=12; $m++) {
	?>
    <li class="<?php if(date('m')==$m) { ?>active<?php } ?> change_month"custom="<?php echo $m;?>"><a href="javascript:;" ><?php echo date('F', mktime(0,0,0,$m));?></a></li>
    <?php } ?>
   </ul>
</div>
<div class="dropdown pull-left">
</div>
<a href="javascript:;" class="pull-left mar-left next_month"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>
</div>
<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="<?php echo base_url();?>inventory/bulk_update" role="button">Bulk Update</a>
</div>
</div>
</div>

<div id="customize_date">
<?php
		$today = date('d/m/Y');
		$today_date = date('Y/m/d');
		$tomorrow = date('d/m/Y',strtotime($today_date."+1 months"));
		$startDate = DateTime::createFromFormat("d/m/Y",$today);
		$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
		$periodInterval = new DateInterval("P1D");
		$endDate->add( $periodInterval );
		$period = new DatePeriod( $startDate, $periodInterval, $endDate );
		$endDate->add( $periodInterval );
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
<!--Content Start-->
<div class="content">
<div class="table table-responsive cls_table_advance">
<table class="tables table-bordereds tal_td_bor" id="reservation_yes_tbl" style="width:100%">
<thead>
<tr>
<td class="tal_td_bor"></td>
<?php foreach($show_month as $val)  {
	
	if($val==date('m')){ $c_head = $month_s; $col=$fi-1;} else { $c_head = $month_l; $col=$li-1;}
	 ?>
<td colspan="<?php echo $col;?>" class="text-center tal_td_bor">
<h3>
<strong><?php echo $c_head; ?></strong>
</h3>
<?php } ?>
</td>
</tr>

<tr>
<td class="tal_td_bor"></td>
<?php 
$current_day_this_month = date('d'); 
$last_day_this_month  = date('t');
foreach($period as $date){
	$i=$date->format("d");
?>
<td class="tal_td_bor"><?php echo $i;?></td>
<?php } ?>
</tr>
</thead>
<?php 

	foreach($all_room as $room) 
	{
		extract($room);
		$room_update = get_data(TBL_UPDATE,array('room_id'=>$property_id))->row();
		$reservation_count = $this->inventory_model->reservation_count(insep_encode($property_id));
		$start = explode(',',$room_update->start_date);
		$end = explode(',',$room_update->end_date);
		if($non_refund==1)
		{
			$members = $member_count+$member_count;
		}
		else
		{
			$members = $member_count;
		}
?>
<tr class="p-b-o tbl_tr_top">
<td class="ha2 tal_td_bor"><span class="pull-left mar-lef text-danger"><strong><?php echo ucfirst($property_name);?></strong></span> <div></div></td>
<?php 
foreach($period as $date)
{
	$i=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($i%2 == 0)
	{
		$color='tabl_even';
	}
	
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da))
	{
	$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
	if(count($single)!='0')
		{
	if($single->availability==0 || $single->stop_sell==1)
	{
		$color = 'tabl_red';
	}	
?>
<td class="<?php echo $color;?> tal_td_bor">
<?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol;?>
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit" data-title="Change Price"><?php echo ceil($single->price); ?> </a>

<span title="Rooms still available"> R </span><!--<i class="fa fa-bed" title="Rooms still available"></i>-->
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit" data-title="Change Availability" title="Rooms still available"><?php echo ($single->availability); ?> </a>


<span title="Minimum Stay"> M </span> <!--<i class="fa fa-moon-o"></i>-->
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit" data-title="Change Minimum Stay"><?php echo ($single->minimum_stay); ?> </a>

</td>
<?php } else  { ?> <td class="<?php echo $color;?> tal_td_bor"> N/A </td><?php } } else { ?> <td class="<?php echo $color;?> tal_td_bor"> N/A </td> 	<?php }}?>
</tr>

<?php
if($reservation_count!=0)
{
	/*$this->db->where('start_date  >=',$today);
	$this->db->or_where('end_date  >=',$today);*/
	$this->db->order_by('reservation_id','desc');
	$reservation   = get_data(RESERVATION,array('room_id'=>$property_id))->result();
	$arr_pu=array();
	$reser = array();
	$date_list = 0;
	foreach($reservation as $value)
	{
		$reser_id = $value->reservation_id;	
		$var = $value->start_date;
		$datea = str_replace('/', '-', $var);
		$start_dates  = date('Y-m-d', strtotime($datea));
		
		$var1 = $value->end_date;
		$datea1 = str_replace('/', '-', $var1);
		$end_dates  = date('Y-m-d', strtotime($datea1));
		
		$startDate = DateTime::createFromFormat("d/m/Y",$value->start_date);
		$endDate = DateTime::createFromFormat("d/m/Y",$value->end_date);
		$periodInterval = new DateInterval("P1D");
		$endDate->add( $periodInterval );
		$periods = new DatePeriod( $startDate, $periodInterval, $endDate );
		$endDate->add( $periodInterval );
		$re=array();
		$same=1;
		foreach($periods as $dates){ 
			array_push($arr_pu,$dates->format("d/m/Y"));
			$reser[$dates->format("d/m/Y")][$date_list]= ($reser_id);
			$same++;
			}
			$date_list++;
	}
	
	  $fillTo = array('key'=>'','count'=>0);
	  foreach($reser as $k => $data) {
			  if (count($data) > $fillTo['count']) {
			  $fillTo['key'] = $k;
			  $fillTo['count'] = count($data);
		  }
	  }
	  
	  $fillData = $reser[$fillTo['key']];
	  foreach($fillData as $k => &$data) {
		  $data['numberof'] = 0;
	  }
	  
	  foreach($reser as $k => &$data) {
		  if ($k === $fillTo['key'])
			  continue;
	  
		  $data = $data + $fillData;
	  }
	/*echo '<pre>';
	
	print_r($reser);
	
	
	print_r($arr_pu);
*/	
	
	if(count($reservation)!=0)
	{
		$a = $da;
		$b = $arr_pu;
		$c = array_intersect($a, $b);
	    if(count($c)!=0)
		{
	?>
    <?php
	$arr_name = array();
	$rer_name=array();
	if(empty($arr_unq))
	{
		$arr_unq = array();
	}
	$o_loop = 1	;
	$rer_name = array();
	for($s=0;$s<=count($reser[$arr_pu[0]]);$s++)
	{
		
		if($s%2 == 0)
		{
			$book_color='#6876D7';
			$a_color   ='#fff';
		}
		
		else
		{
			$book_color='#74F4EF';
			$a_color   ='#000'; 
		}
		?>
        <tr class="tbl_tr_tops"> 
		 <td>
		 </td>
        <?php
		foreach($period as $date)
		{
			$j=$date->format("d");
			$ss_rr= $date->format("d/m/Y");
			if (@array_key_exists($ss_rr, $reser) )
			{
				$get_reser = @$reser[$ss_rr][$s];
				if($get_reser<1)
				{
					echo '<td> </td>';
				}
				else{
				$reservation = get_data(RESERVATION,array('reservation_id'=>$get_reser,'room_id'=>$property_id))->row_array();
				if(count($reservation)!=0)
				{
					
					$var = $reservation['start_date'];
					$datea = str_replace('/', '-', $var);
					$start_dates  = date('Y-m-d', strtotime($datea));
					
					$var1 = $reservation['end_date'];
					$datea1 = str_replace('/', '-', $var1);
					$end_dates  = date('Y-m-d', strtotime($datea1));
					
					$datetime1 = new DateTime($start_dates);
					$datetime2 = new DateTime($end_dates);
					$interval = $datetime1->diff($datetime2);
					if($start_dates == date('Y-m-d') || $start_dates > date('Y-m-d'))
					{
						$colspn = $interval->format('%a%')+1;
					}
					else if($start_dates <= date('Y-m-d'))
					{
						//$colspn = $interval->format('%a%')-1;
					}
					else
					{
						$colspn = $interval->format('%a%');
					}
					if(!in_array($reservation['reservation_id'],$rer_name)){
				?>
				<td style="background-color:<?php echo $book_color;	?>; text-align:center; cursor:pointer;" class="reser_pop <?php echo $book_color;?> " id="<?php echo $reservation['reservation_id']?>" colspan="<?php echo $colspn;?>">
                <?php if($reservation['start_date'] == $reservation['end_date']) { ?> 
                <div style="width:43px; word-wrap: break-word;">
                <a href="javascript:;" class="reser_pop" id="<?php echo $reservation['reservation_id']?>" style="color:<?php echo $a_color;?>"> <?php if(!in_array($reservation['reservation_id'],$rer_name)){echo $reservation['guest_name'];}else { echo ''; } ?> </a>
                </div>
                <?php } else {  ?>
                <a href="javascript:;" class="reser_pop" id="<?php echo $reservation['reservation_id']?>" style="color:<?php echo $a_color;?>"> <?php if(!in_array($reservation['reservation_id'],$rer_name)){echo $reservation['guest_name'];}else { echo ''; } ?> </a>
                <?php } ?>
                </td> 
			<?php
					}
				}
				}
			}
			else
			{
				echo '<td> </td>';
			}
			@array_push($rer_name,$reservation['reservation_id']);
			$o_loop++;
		}
		?>
        </tr>
        <?php
	}
	 ?>
    <?php
		}
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
	<tr class="col-gre2 tbl_tr_top">
	<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
<?php 
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($refun=='1')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['refund_amount'];
		}
	}
	elseif($refun=='2')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['non_refund_amount'];
		}
	}
	if($j%2 == 0)
	{
		$color='tabl_even';
	}
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da))
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($sub_rates)!='0')
		{
			if($sub_rate!=0)
			{
		?>
	<td class="<?php echo $color?> tal_td_bor" ><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval(str_replace(',','',$sub_rate));?></span></td>
	<?php } else { ?><td class="<?php echo $color?> tal_td_bor"> N/A </td > <?php }}else { ?> <td class="<?php echo $color?> tal_td_bor"> N/A </td ><?php }} else { ?> <td class="<?php echo $color?> tal_td_bor" tal_td_bor> N/A </td > <?php } }?>  
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
<tr class="col-gre2 tbl_tr_top">
<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
<?php 
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($refun=='1')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['refund_amount'];
		}
	}
	elseif($refun=='2')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['non_refund_amount'];
		}
	}
	if($j%2 == 0)
	{
		$color='tabl_even';
	}
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da)) 
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($sub_rates)!='0')
		{
			if($sub_rate!=0)
			{
	?>
<td class="<?php echo $color?> tal_td_bor"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rate);?></span></td>
<?php }else { ?> <td class="<?php echo $color?> tal_td_bor">N/A</td> <?php }  }else { ?> <td class="<?php echo $color?> tal_td_bor">N/A</td><?php }}else { ?> <td class="<?php echo $color?> tal_td_bor">N/A</td> <?php }} ?>
</tr>
<?php }
	}
} 
else if($pricing_type==1 && $non_refund==1)
{
	
	//$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_1'))->row()->d_total_amount; ?> 
    
    <tr class="col-gre2 tbl_tr_top">
	<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - Non refundable </td>
<?php
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($refun=='1')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['refund_amount'];
		}
	}
	elseif($refun=='2')
	{
		$sub_rates = get_data(RESERV,array('room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v))->row_array();
		if(count($sub_rates)!=0)
		{
			$sub_rate = $sub_rates['non_refund_amount'];
		}
	}
	if($j%2 == 0)
				{
					$color='tabl_even';
				}
				else
				{
					$color='tabl_add';
				}
	if(in_array($ss_da,$da))
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($sub_rates)!='0')
		{
			if($sub_rate!=0)
			{
	?>
<td class="<?php echo $color?> tal_td_bor"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rate);?></span></td>
<?php } else { ?><td class="<?php echo $color?> tal_td_bor"> N/A </td> <?php }}else {?> <td class="<?php echo $color?> tal_td_bor"> N/A </td> <?php  }}else {?> <td class="<?php echo $color?> tal_td_bor" > N/A </td> <?php } }  ?>
</tr> <?php 
}
}?>

</table>

</div>
</div>
<!--Content End-->
</div>
</div>
</div>
</div>
</div>
<?php } } else if($action=='reservation_no') { 
error_reporting(0);
$this->db->order_by('property_id','desc');
$all_room = get_data(TBL_PROPERTY,array('owner_id'=>user_id()))->result_array();
if(count($all_room)!=0) {
?>
<div class="change_month_replaces">
<div class="dash-b-n1">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12 new-k2">

<div class="dash-b-n2">
<div class="row">
<div class="col-md-1 col-sm-1"><div class="">
    <div class="on-2"><div class="on-offswitch">
        <input type="checkbox" name="on-offswitch" class="on-offswitch-checkbox" id="myon-offswitch" checked>
        <label class="on-offswitch-label" for="myon-offswitch">
            <span class="on-offswitch-inners reservationno"></span>
            <span class="on-offswitch-switchs"></span>
        </label>
    </div>
    </div>
       
<input type="hidden" name="cur_month" id="next_months" value="<?php echo date('m')+1;?>" />
<input type="hidden" name="cur_month" id="prev_months" value="<?php echo date('m')-1;?>" />
</div></div>
<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="javascript:;" role="button" data-toggle="modal" data-target="#myModal-ps">Customize calendar</a>
</div>
<div class="col-md-5 col-sm-5 dr">
<ul class="list-inline">
<li class="orgs"><a class="btn btn-warning" href="javascript:;" role="button" data-toggle="modal" data-target="#myModal-p2">Full Update</a></li> 

<li>

<!-- <a href="#" class="pull-left mar-right"><img src="<?php //echo base_url();?>user_assets/images/pre.png"></a>
<div class="dropdown pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    September	
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">Two Weeks</a></li>
    <li><a href="#">Weekly</a></li>
  </ul>
</div>
<a href="#" class="mar-left"><img src="<?php //echo base_url();?>user_assets/images/next.png"></a> -->


<a href="javascript:;" class="pull-left mar-right prev_months display_none"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
<div class="dropdown pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo date('F');?>	
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
  <?php 
for ($m=date('m'); $m<=12; $m++) {
	?>
    <li class="<?php if(date('m')==$m) { ?>active<?php } ?> change_months"custom="<?php echo $m;?>"><a href="javascript:;" ><?php echo date('F', mktime(0,0,0,$m));?></a></li>
    <?php } ?>
   </ul>
</div>
<a href="javascript:;" class="pull-left mar-left next_months"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>


</li>

<li class="orgs"><a class="btn btn-default" href="<?php echo base_url();?>inventory/bulk_update" role="button">Bulk Update</a></li>
</ul>




</div>
<div class="col-md-4 col-sm-4 chec-n">
<label class="checkbox-inline">
  <input type="checkbox" id="show_all" value="1" checked="checked"> Show all rates
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="cal_ms" value="1" checked="checked"> Minimum stay 
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="cal_cta" value="1" checked="checked"> CTA
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="cal_ctd" value="1" checked="checked"> CTD
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="cal_ss" value="1" checked="checked"> Stop sell 
</label>
</div>
</div>
</div>
<div id="customize_dates">
<?php
		$today = date('d/m/Y');
		$today_date = date('Y/m/d');
		$tomorrow = date('d/m/Y',strtotime($today_date."+1 months"));
		$startDate = DateTime::createFromFormat("d/m/Y",$today);
		$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
		$periodInterval = new DateInterval("P1D");
		$endDate->add( $periodInterval );
		$period = new DatePeriod( $startDate, $periodInterval, $endDate );
		$endDate->add( $periodInterval );
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

<div class="content fn-w-n">
<form method="post" action="<?php echo base_url();?>inventory/reservation_update_no" class="master_calendar_form">
<input type="hidden" name="channe_id_update" id="channe_id_update"/>
<div class="table table-responsive imp">
<table class="table table-bordered tbl_reser_no" style="width:100%" id="tbl_reser_no">
<thead>
<tr>
<th colspan="2"></th>
<?php foreach($show_month as $val)  {
	
	if($val==date('m')){ $c_head = $month_s; $col=$fi-1;} else { $c_head = $month_l; $col=$li-1;}
	 ?>
<th colspan="<?php echo $col;?>" class="text-center tal_td_bor">
<h3>
<strong><?php echo $c_head; ?></strong>
</h3>
<?php } ?>
</th>
</tr>
<tr>
<td colspan="2"></td>
<?php 
$current_day_this_month = date('d'); 
$last_day_this_month  = date('t');
foreach($period as $date){
	$i=$date->format("d");
?>
<td>
<div class="text-center"><?php echo $date->format("D");?></div>
<div class="text-center"><b><?php echo $date->format("d")?></b></div>
<div class="text-center"><?php echo $date->format("M")?></div>
</td>
<?php } ?>
</tr>

</thead>
<tbody>
<?php 

	foreach($all_room as $room) 
	{
		extract($room);
		$room_update = get_data(TBL_UPDATE,array('room_id'=>$property_id))->row();
		$reservation_count = $this->inventory_model->reservation_count(insep_encode($property_id));
		$start = explode(',',$room_update->start_date);
		$end = explode(',',$room_update->end_date);
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
<td class="ha2 tal_td_bor">
<span class="fnt-se text-danger"><strong><?php echo ucfirst($property_name);?></strong></span> </td>
<td>
   <div class="" style="margin-top:8px;" title="Price"> <a href="javascript:;" title="Price"> P </a> </div>
   <div class="" style="margin-top:10px;" title="Available rooms"> <a href="javascript:;" title="Available rooms"> R </a> </div>
   <div class="cal_ms" style="margin-top:8px;" title="Minimum Stay"> <a href="javascript:;" title="Minimum Stay"> M </a></div>
   <div class="cal_ss" style="margin-top:10px;" title="Stop sales"> <a href="javascript:;" title="Stop sales"> SS </a></div> 
   <div class="cal_cta" style="margin-top:10px;" title="Close to Arrival"> <a href="javascript:;" title="Close to Arrival"> CTA </a></div>
   <div class="cal_ctd" style="margin-top:20px;" title="Close to Departure"> <a href="javascript:;" title="Close to Departure"> CTD </a></div>
   </td>
<?php foreach($period as $date)
{$ss_da = $date->format("d/m/Y");
	?>
    
   
<td>

<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>

<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Update Availability" style="font-size:15px"><span title="Click to update the availability"></span>&nbsp;<i class="fa fa-home" title="Click to update the availability"></i></a>
</div>
<div class="g-top-n ji pull-left cal_ms">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo base_url()?>inventory/inline_edit_no" data-title="Update Minimustay"><span title="Click to update the minimum stay"></span>&nbsp;<i class="fa fa-moon-o" title="Click to update the minimum stay"></i></a>
</div>
<div class="clearfix"></div>
<div class="cal_ss">
<!--<span title="Stop sell ( Yes / NO )">SS</span>-->
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" title="Stop sell ( Yes / NO )" class="">
    </label>
  </div>
 </div> 
 
<div class="cal_cta">
<!--<span title="Close to Arrival">CTA</span>-->
<div class="checkbox">
    <label>
     <input type="checkbox" name="room[<?php echo $property_id;?>][cta][<?php echo $ss_da;?>]" value="1" title="Close to Arrival ( Yes / NO )" class="">
    </label>
  </div>
</div>  
 <div class="clearfix"></div>
<div class="cal_ctd">
<!--<span title="Close to Departure">CTD</span>-->
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php echo $property_id;?>][ctd][<?php echo $ss_da;?>]" value="1" title="Close to Departure ( Yes / NO )" class="">
    </label>
  </div>
  


 </div>  
 <div class="clearfix"></div>
 <div class="g-top-n ji">
 
 </div>
 
</td>
<?php } ?>

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
			
			$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_'.$v))->row()->d_total_amount;
	
		?>
	<tr class="col-gre2 show_all">
	<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
    <td>
    <div class="" style="margin-top:8px;" title="Price"> <a href="javascript:;" title="Price"> P </a> </div>
    </td>
<?php 
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($j%2 == 0)
	{
		$color='tabl_even';
	}
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da))
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($single)!='0')
		{
		?>
	<td class="<?php echo $color?> tal_td_bor" >
    <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>
	</td>
	<?php } else { ?> <td class="<?php echo $color?> tal_td_bor"> 
    
    <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>
     </td > <?php }} else { ?> <td class="<?php echo $color?> tal_td_bor" tal_td_bor> 
    <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>
    </td > <?php } }?>  
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
				}
				else
				{
					$name = 'Guest';
					$v = ceil($k/2);
				}
			}
			else
			{
				$name = 'Guest';
				$v = $k;
				
			}
	?>
<tr class="col-gre2 show_all">
<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
<td><div class="" style="margin-top:8px;" title="Price"> <a href="javascript:;" title="Price"> P </a> </div>
    </td>
<?php 
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	if($j%2 == 0)
	{
		$color='tabl_even';
	}
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da)) 
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($single)!='0')
		{
	?>
<td class="<?php echo $color?> tal_td_bor">
<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td>
<?php } else { ?> <td class="<?php echo $color?> tal_td_bor">
<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td><?php }}else { ?> <td class="<?php echo $color?> tal_td_bor">
<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td> <?php }} ?>
</tr>
<?php }
	}
} 
else if($pricing_type==1 && $non_refund==1)
{
	$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_1'))->row()->d_total_amount; ?> 
    <tr class="col-gre2 show_all">
	<td class="ha2 tal_td_bor" style="width: 366px;"><?php echo ucfirst($property_name);?> - Non refundable </td>
    <td><div class="" title="Price"> <a href="javascript:;" title="Price"> P </a> </div>
    </td>
<?php
foreach($period as $date)
{
	$j=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	$v=1;
	$refun=2;
	if($j%2 == 0)
	{
		$color='tabl_even';
	}
	else
	{
		$color='tabl_add';
	}
	if(in_array($ss_da,$da))
	{
		$single = get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($single)!='0')
		{
	?>
<td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td>
<?php } else {?> <td class="<?php echo $color?> tal_td_bor"> <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div> </td> <?php  }}else {?> <td class="<?php echo $color?> tal_td_bor" > <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div> </td> <?php } }  ?>
</tr> <?php 
}
?>
<?php } ?>

</tbody>
</table>

</div>
<input type="button" class="btn btn-success" value="Update" data-toggle="modal" data-target="#channelsModal"/> 
<input type="reset" class="btn btn-danger" value="Reset"/>
</form>




</div>
</div>







</div>
</div>
</div>
</div>

<?php }} ?>



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

<div class="row">
  <div class="col-md-6 col-sm-6">
  <b class="ne-k">Rooms mapped to</b></div>
  <div class="col-md-6 col-sm-6"><select class="form-control" name="room_map">
  <option>Any Channel</option>
  <option>2</option>
</select></div>
</div>
  
  
  
  <div class="top-ma">
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="channel_all" class="channel_all">
     All 
  </label>
</div>
</div>

<?php 
$con_cha = $this->channel_model->user_channel();
if(count($con_cha)!=0) { foreach($con_cha as $connected){ extract($connected);?>
<div class="checkbox">
  <label>
    <input type="checkbox" checked class="channel_single" name="channel_id[]" id="channel_single" value="<?php echo $channel_id?>">
      <?php echo $channel_name;?>
  </label>
</div>

<?php } } else {  ?>
<li><div class="close-i"><i class="fa fa-close"></i> No channel data found...</div></li>
<?php } ?>

</div>
        </div>
        <div class="col-md-6 col-sm-6">
        <h4>End Date:</h4>
        
  <div class="form-group">
    <input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_end" name="end_date">
  </div>

  <!-- date -->
  <b>Days</b> 
  <div class="top-ma">
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked>
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
      <input type="checkbox" checked name="days[]" value="<?php echo $values[$key]?>"><?php echo $day?> 
    </label>
  </div>
  <?php } ?>
</div>
  <!-- end date -->
  
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

<div class="row">
  <div class="col-md-6 col-sm-6">
  <b class="ne-k">Rooms mapped to</b></div>
  <div class="col-md-6 col-sm-6"><select class="form-control" name="room_map">
  <option>Any Channel</option>
  <option>2</option>
</select></div>
</div>
  
  
  
  <div class="top-ma">
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked id="channel_all" class="channel_all1">
     All 
  </label>
</div>
</div>

<?php 
$con_cha = $this->channel_model->user_channel();
if(count($con_cha)!=0) { foreach($con_cha as $connected){ extract($connected);?>
<div class="checkbox">
  <label>
    <input type="checkbox" checked class="channel_single1" name="channel_id[]" id="channel_single1" value="<?php echo $channel_id?>">
      <?php echo $channel_name;?>
  </label>
</div>

<?php } } else {  ?>
<li><div class="close-i"><i class="fa fa-close"></i> No channel data found...</div></li>
<?php } ?>

</div>
        </div>
        <div class="col-md-6 col-sm-6">
        <h4>End Date:</h4>
        
  <div class="form-group">
    <input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_ends" name="end_date">
  </div>

  <!-- date -->
  <b>Days</b> 
  <div class="top-ma">
<div class="n-che"> 
<div class="checkbox">
  <label>
    <input type="checkbox" value="" checked>
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
      <input type="checkbox" checked name="days[]" value="<?php echo $values[$key]?>"><?php echo $day?> 
    </label>
  </div>
  <?php } ?>
</div>
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
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Full Refresh</h4>
      </div>
      <div class="modal-body">
        <div class="calen-2">
        
        <div class="row">
        <div class="col-md-12 col-sm-12">
        <p><b>This feature uploads your complete availability, rates and other settings from myallocator to the channels you select.</b></p>
        <p>Normally only changes you make on myallocator are sent to a channel. resulting in discrepancies if the availability is different on a channel compared to myallocator to begin with.</p>
        <p>To avoid overbookings we strongly advise to do a Full Refresh after you setup a new channel, or after you mapped a room you previosly hadn't mapped.</p>
        <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Refresh all channels
  </label>
</div>
<div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Refresh selected channels 
  </label>
</div>
<textarea class="form-control" rows="3"></textarea>
        </div>        
        </div>
        
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Start Refresh</button>
      </div>
    </div>
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
            <label class="check_header">
              <input type="checkbox" value="1" name="all_check" multiple="multiple" id="all_check_toggle" class="all_check_toggle" checked="checked"> All
            </label>
            <?php 
			$con_cha = $this->channel_model->user_channel();
			if(count($con_cha)!=0) { foreach($con_cha as $connected){ extract($connected);?>
			<div class="check_list">
			<label>
		    <input type="checkbox" checked class="channel_single2" name="channel_id[]" id="channel_single2" value="<?php echo $channel_id?>">
		    <?php echo $channel_name;?>
			</label>
			</div>
			<?php } } else {  ?>
			<li><div class="close-i"><i class="fa fa-close"></i> No channel data found...</div></li>
			<?php } ?>
          </div>
        </div>
      </div>
        <div class="modal-footer" id="channels-footer">
        <a data-dismiss="modal" class="pull-left btn-default" aria-hidden="true" href="#">Close</a>
        <a data-dismiss="modal" class="btn btn-primary save_button_master_calendar" href="javascript:;">Update</a>
      </div>
    </div>
  </div>
</div>



<!-- Full Update -->
<!--

EBAY DETAILS:

lingeswariepay@gmail.com  ANU@13 (pen drive).

vijiepay@gmail.ocm viji@10 (pen drive).

subbaiah.job@gmail.com   subbaiah@10 (Slipper).

vishnuepay@gmail.com  vishnu@12 (pen drive).

mailmekannanbe@gmail.com  kannan@87 (Headset).

kannanck@gmail.com (Slipper).

G1028S g1297@5397s


Completed Functionality For Reservation Yes:

1.YES – NO switch.

2. Dropdown and Next Prev buttons to be able to choose month to display on
calendar. Maximum one month per view only.

3. Calendar should fit whole screen for whole month.

4. Reservations show on calendar ( using static content from database) and see hotelrunner reservations for more info on vouchers (popup).

5. Customize calendar ( start date and end date fuctionality completed).

6. Bulk update should link to bulk update page.

8973335835

Hi,
Changes are completed. Please Check it.
http://channelmanager.osiztechnologies.com/inventory/advance_update/http://channelmanager.osiztechnologies.com/inventory/advance_update/reservation_no​
http://channelmanager.osiztechnologies.com/reservation/reservationlist​ ( Using static Content ).
http://channelmanager.osiztechnologies.com/channel/policies​ ( Policies Section ).
http://channelmanager.osiztechnologies.com/reservation/tax_categories​ ( Tax Categories ).
http://channelmanager.osiztechnologies.com/reservation/payment_list​ ( Payment List ).






-->

