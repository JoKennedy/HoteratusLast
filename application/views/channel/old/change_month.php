<?php
error_reporting(0);
$this->db->order_by('property_id','desc');
$all_room = get_data(TBL_PROPERTY,array('owner_id'=>user_id()))->result_array();
$currency = get_data(TBL_USERS,array('user_id'=>user_id()))->row()->currency;
?>
<div class="dash-b-n1 new-s">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12 new-k2">

<div class="dash-b-n2">
<div class="row">
<div class="col-md-1 col-sm-1"><div class="">
    <div class="on-2"><div class="on-offswitch">
        <input type="checkbox" name="on-offswitch" class="on-offswitch-checkbox" id="myon-offswitch" checked>
        <label class="on-offswitch-label" for="myon-offswitch">
            <span class="on-offswitch-inner reservationyes"></span>
            <span class="on-offswitch-switch"></span>
        </label>
    </div>
    </div>
    
<input type="hidden" name="cur_month" id="next_month" value="<?php if($nr_pr_date!=12) { echo $nr_pr_date+1; } else { echo $nr_pr_date-1; }?>" />
 
<input type="hidden" name="cur_month" id="prev_month" value="<?php if($nr_pr_date!=1) { echo $nr_pr_date-1; } else { echo $nr_pr_date+1; }?>" />
 
</div></div>
<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="#" role="button" data-toggle="modal" data-target="#myModal-p">Customize calendar</a>
</div>
<div class="col-md-2 col-sm-2 org">
<a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#myModal-p2">Full Update</a>
</div>
<div class="col-md-3 col-sm-3 dr">
<a href="javascript:;" class="pull-left mar-right prev_month <?php if($nr_pr_date==date('m')){?> display_none <?php } ?>"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
<div class="dropdown pull-left">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
<?php 
  $dd = date('Y').'-'.$nr_pr_date.'-'.date('d');
  echo format_date1($dd);
?>	
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
  <?php 
for ($m=date('m'); $m<=12; $m++) {
	?>
    <li class="<?php if($nr_pr_date==$m) { ?>active<?php } ?> change_month"  custom="<?php echo $m;?>"><a href="javascript:;" ><?php echo date('F', mktime(0,0,0,$m));?></a></li>
    <?php } ?>
   </ul>
</div>
<div class="dropdown pull-left">
</div>
<a href="javascript:;" class="pull-left mar-left next_month <?php if($nr_pr_date==12){?> display_none <?php } ?>"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>
</div>
<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="<?php echo lang_url();?>inventory/bulk_update" role="button">Bulk Update</a>
</div>
</div>
</div>

<?php
		if($nr_pr_date!=date('m'))
		{
			$today = date('d/m/Y', mktime(0, 0, 0, $nr_pr_date, 1, date('Y'))); 
			$tomorrow = date('t/m/Y', mktime(0, 0, 0, $nr_pr_date, 1, date('Y')));
			$date = $today;
			$d = date_parse_from_format("Y-m-d", $date);
			$col_month = $d["month"];
		}
		else
		{
			$today 		= date('d/m/Y');	
			$tomorrow	= date("t/m/Y");
			$date = $today;
			$d = date_parse_from_format("Y-m-d", $date);
			$col_month = $d["month"];
		}
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
		$da[]		= $date->format("d/m/Y");;
		if($date->format("m")==$col_month)
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
<div id="customize_date">

<div class="content">
<div class="table table-responsive cls_table_advance">
<table class="tables table-bordereds tal_td_bor" id="reservation_yes_tbl" style="width:100%">
<thead>
<tr>
<td class="tal_td_bor"></td>
<?php foreach($show_month as $val)  {
	
	if($val==$col_month){ $c_head = $month_s; $col=$fi-1;} else { $c_head = $month_l; $col=$li-1;}
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
<div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Price"><span title="Price"> <?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol;?></span><?php echo ceil($single->price); ?> </a>
</div>
<div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Availability" title="Rooms still available"><span title="Rooms still available"> R </span> <?php echo ($single->availability); ?> </a>
</div>
<div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Minimum Stay"><span title="Minimum Stay"> M </span><?php echo ($single->minimum_stay); ?> </a>
</div>

</td>
<?php } else  { ?> <td class="<?php echo $color;?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center"> N/A</div> </td><?php } } else { ?> <td class="<?php echo $color;?> tal_td_bor"> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td> 	<?php }}?>
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
	<td class="<?php echo $color?> tal_td_bor" ><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval(str_replace(',','',$sub_rate));?></span></div></td>
	<?php } else { ?><td class="<?php echo $color?> tal_td_bor"> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td > <?php }}else { ?> <td class="<?php echo $color?> tal_td_bor"> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td ><?php }} else { ?> <td class="<?php echo $color?> tal_td_bor" tal_td_bor> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td > <?php } }?>  
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
<td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rate);?></span></div></td>
<?php }else { ?> <td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div></td> <?php }  }else { ?> <td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div></td><?php }}else { ?> <td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div></td> <?php }} ?>
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
<td class="<?php echo $color?> tal_td_bor"><div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rate);?></span></div></td>
<?php } else { ?><td class="<?php echo $color?> tal_td_bor"> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td> <?php }}else {?> <td class="<?php echo $color?> tal_td_bor"> <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td> <?php  }}else {?> <td class="<?php echo $color?> tal_td_bor" > <div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">N/A</div> </td> <?php } }  ?>
</tr> <?php 
}
}?>

</table>

</div>
</div>

</div>

</div>
</div>
</div>

<!--sankarialex@gmail.com
alexander9716-->
<script>
$('.inline_username').editable();
$("table#reservation_yes_tbl tr").each(function() {        
    var cell = $.trim($(this).find('td').text());
    if (cell.length == 0){
        console.log('empty');
        $(this).addClass('display_none');
    }                   
});
</script>