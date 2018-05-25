<?php
		$currency = get_data(TBL_USERS,array('user_id'=>user_id()))->row()->currency;
		$this->db->order_by('property_id','desc');
		$all_room = get_data(TBL_PROPERTY,array('owner_id'=>user_id()))->result_array();
		$today = $start_date;
		$today_date = date('Y/m/d',strtotime($start_date));
		
		$date = $today;
		$d = date_parse_from_format("Y-m-d", $date);
		//echo $d["month"];

		$col_month = $d["month"];
		$tomorrow = $end_date;//date('d/m/Y',strtotime($today_date."+1 months"));//date("t/m/Y"); //date('d/m/Y',strtotime($today . "+1 months"));
		//echo $today.' - '.$tomorrow;
		$startDate = DateTime::createFromFormat("d/m/Y",$today);
		$endDate = DateTime::createFromFormat("d/m/Y",$tomorrow);
		$periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
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
		
		//echo $fi-1;
		//echo $li-1;
		$show_month = (array_unique($month));
		$last_date   = (array_unique($last));
		//print_r($last_date);
		
?>

<div class="content">
<div class="table table-responsive cls_table_advance">
<table class="table table-bordered ">

<tr>
<th></th>
<?php foreach($show_month as $val)  {
	
	if($val==$col_month){ $c_head = $month_s; $col=$fi-1;} else { $c_head = $month_l; $col=$li-1;}
	 ?>
<th colspan="<?php echo $col;?>" class="text-center">

<h3>
<strong><?php echo $c_head; ?></strong>
</h3> <!--Alternate for me Analyze site minder on room mapping.-->
<?php } ?>
</th>
</tr>

<tr>
<td></td>
<?php 
$current_day_this_month = date('d'); 
$last_day_this_month  = date('t');

		
		/*echo '<pre>';
		print_r($dates);
		print_r($months);*/
		
/*for($i=$current_day_this_month; $i<=$last_day_this_month; $i++)*/
foreach($period as $date){
	$i=$date->format("d");
?>
<td><?php echo $i;//$i<10 ? "0".$i : "".$i; ?></td>
<?php } ?>
</tr>
	
<!--Content Start LS5R2DEMWL  F8IELN7SGE 657 5196190088417730  9LANDMJNFA

ROLSFWZHXZ
-->
<?php 

	foreach($all_room as $room) {
		extract($room);

		$room_update = get_data(TBL_UPDATE,array('room_id'=>$property_id))->row();

		$reservation_count = $this->inventory_model->reservation_count(insep_encode($property_id));
			
		/*$start = explode('/',$room_update->start_date);
		$end = explode('/',$room_update->end_date);*/
		
		$start = explode(',',$room_update->start_date);
		$end = explode(',',$room_update->end_date);
		
		//echo $start[0];
		//echo $end[0];
		
		/*$da=array(); 
		for($ss=$start[0]; $ss<=$end[0]; $ss++)
		{
			$da[].=$ss;	
		}*/
		
		/*$da=array(); 
		for($ss=$start; $ss<=$end; $ss++)
		{
			$da[].=$ss;	
		}
		echo '<pre>';
		print_r($da);*/
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
<td class="ha2"><span class="pull-left mar-lef text-danger"><strong><?php echo ucfirst($property_name);?></strong></span> <div></div></td>
<?php 
//for($i=$current_day_this_month; $i<=$last_day_this_month; $i++)
foreach($period as $date)
{
	$i=$date->format("d");
	$ss_da = $date->format("d/m/Y");
	//echo $ss_da;
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
				//echo count($single);
				if(count($single)!='0')
					{
			    if($single->availability==0 || $single->stop_sell==1)
				{
					$color = 'tabl_red';
				}	
				?>
<td class="<?php echo $color;?>">
<?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol;?>
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Price"><?php echo ceil($single->price); ?> </a>
<!--<a href="javascript:;"><p class="col-gre"><span><?php //echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.''.ceil($single->price); ?></span></p></a>-->
<i class="fa fa-bed"></i>
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Availability"><?php echo ($single->availability); ?> </a>

<!--<a href="javascript:;" class="single_update" room_id="<?php //echo $property_id;?>" up_date="<?php //echo $ss_da;?>"><span data-toggle="tooltip" data-placement="top" title="Rooms still available">:<?php //echo $single->availability;?></span></a>-->

<i class="fa fa-moon-o"></i>
<a href="javascript:;" id="inline_username" class="inline_username" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit" data-title="Change Minimum Stay"><?php echo ($single->minimum_stay); ?> </a>

<!--<a href="javascript:;" class="single_update" room_id="<?php //echo $property_id;?>" up_date="<?php //echo $ss_da;?>">
<p><span data-toggle="tooltip" data-placement="top" title="Minimum stay"><i class="fa fa-moon-o"></i>:<?php //echo $single->minimum_stay;?></span></p>
</a>-->
</td>
<?php } else  { ?> <td class="<?php echo $color;?>"> N/A </td><?php } } else { ?> <td class="<?php echo $color;?>"> N/A </td> 	<?php }}?>
</tr>

<?php
if($reservation_count!=0)
{
	$this->db->where('start_date  >=',$today);
	$this->db->or_where('end_date  >=',$today);
	$reservation   = get_data(RESERVATION,array('room_id'=>$property_id))->result();
	/*echo '<pre>';
	print_r($reservation);
	exit;*/
	$arr_pu=array();
	$reser = array();
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
		$periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
		$endDate->add( $periodInterval );
		$periods = new DatePeriod( $startDate, $periodInterval, $endDate );
		$endDate->add( $periodInterval );
		$re=array();
		foreach($periods as $dates){
			array_push($arr_pu,$dates->format("d/m/Y"));
			$reser[$dates->format("d/m/Y")] = $reser_id;
		}
	}
	
	/*$set_key = 1;
	$set_key_array=array();
	$set_key_value=array();
	foreach($period as $date)
	{		
		$j=$date->format("d");
		$ss_rr= $date->format("d/m/Y");
		if(in_array($ss_rr,$arr_pu))
		{
			array_push($set_key_array,$set_key);
			foreach($reservation as $value)
			{
				array_push($set_key_value,$value->reservation_id);
			}
		}
		$set_key++;
	}
	$filter_array = array_unique($set_key_value);
	
	print_r($set_key_array);
	
	print_r($filter_array);*/
	
	//$c = array_combine($set_key_array, $set_key_value);
	
	/*echo '<pre>';
	
	print_r($reser);
	
	
	print_r($arr_pu);
	
	exit;*/
	if(count($reservation)!=0)
	{
		$a = $da;
		$b = $arr_pu;
		$c = array_intersect($a, $b);
	    if(count($c)!=0)
		{
	?>
    <tr> 
    <td>
    </td>
    <?php
	$arr_name = array();
	$rer_name=array();
	if(empty($arr_unq))
	{
		$arr_unq = array();
	}
	$o_loop = 1	;
	$rer_name = array();
	foreach($period as $date)
	{		
		$j=$date->format("d");
		$ss_rr= $date->format("d/m/Y");
		if (@array_key_exists($ss_rr, $reser) )
		{
			$get_reser = @$reser[$ss_rr];
			
			$reservation = get_data(RESERVATION,array('reservation_id'=>$get_reser))->row();
			
			?>
            <td style="background-color:#0F3" class="reser_pop" id="<?php echo $reservation->reservation_id?>"> <a href="javascript:;" class="reser_pop" id="<?php echo $reservation->reservation_id?>"> <?php if(!in_array($reservation->guest_name,$rer_name)){echo $reservation->guest_name;}else { echo ''; }?> </a></td> 
        <?php
		}
		else
		{
			echo '<td> </td>';
		}
		array_push($rer_name,$reservation->guest_name);
		
		/*}*/
		
		$o_loop++;
	}
	/*echo '<pre>';
	print_r($arr_unq);*/
	/*echo '<pre>';
	print_r($arr_pu);*/
	 ?>
    
    </tr>
    <?
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
				//if($member_count >= $k)
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
			
			$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_'.$v))->row()->d_total_amount;
	
		?>
	<tr class="col-gre2">
	<td class="ha2" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
	<?php 
	//for($j=$current_day_this_month; $j<=$last_day_this_month; $j++)
	//{
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
		//echo $ss_da;
		$single = @get_data(TBL_UPDATE,array('room_id'=>$property_id,'separate_date'=>$ss_da))->row();
		if(count($single)!='0')
		{
		?>
	<td class="<?php echo $color?>"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval(str_replace(',','',$sub_rates));?></span></td>
	<?php } else { ?> <td class="<?php echo $color?>"> N/A </td ><?php }} else { ?> <td class="<?php echo $color?>"> N/A </td > <?php } }?>  
	</tr>
	<?php } 
	}
	else
	{
		for($k=1;$k<=$members;$k++)
		{
			
			if($member_count < $members)
			{
				//if($member_count >= $k)
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
			
			//$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_'.$v))->row()->total_amount;

	?>
<tr class="col-gre2">
<td class="ha2" style="width: 366px;"><?php echo ucfirst($property_name);?> - <?php echo $v.' '.$name;?> </td>
<?php 
//for($j=$current_day_this_month; $j<=$last_day_this_month; $j++)
//{
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
<td class="<?php echo $color?>"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($price);?></span></td>
<?php } else { ?> <td class="<?php echo $color?>">N/A</td><?php }}else { ?> <td class="<?php echo $color?>">N/A</td> <?php }} ?>
</tr>
<?php }
	}
} 
else if($pricing_type==1 && $non_refund==1)
{
	
	$sub_rates = get_data(RATE,array('room_unq_id'=>$property_id.'_1'))->row()->d_total_amount; ?> <tr class="col-gre2">
<td class="ha2" style="width: 366px;"><?php echo ucfirst($property_name);?> - Non refundable </td>
<?php
//for($j=$current_day_this_month; $j<=$last_day_this_month; $j++)
//{
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
<td class="<?php echo $color?>"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rates);?></span></td>
<?php } else {?> <td class="<?php echo $color?>"> N/A </td> <?php  }}else {?> <td class="<?php echo $color?>"> N/A </td> <?php } }  ?>
</tr> <?php 
}
}?>
<!--<tr class="col-gre2">
<td class="ha2" style="width: 366px;">Bungalow - 2 guests</td>
<?php for($i=$current_day_this_month; $i<=$last_day_this_month; $i++)
{
	?>
<td><span>₨100</span></td>
<?php } ?>

</tr>-->
<!--<tr class="col-gre2">
    <td class="ha2" style="width: 366px;">Bungalow - 2 guests Non refundable</td>
    <?php for($i=$current_day_this_month; $i<=$last_day_this_month; $i++)
    {
        ?>
    <td><span>₨100</span></td>
    <?php } ?>
    
    </tr>-->
    
<!--Content End-->
    	
</table>

</div>






</div>

<script>
$('.inline_username').editable();
</script>