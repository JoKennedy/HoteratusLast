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
            <span class="on-offswitch-inners reservationno"></span>
            <span class="on-offswitch-switchs"></span>
        </label>
    </div>
    </div>
    
<input type="hidden" name="cur_months" id="next_months" value="<?php if($nr_pr_date!=12) { echo $nr_pr_date+1; } else { echo $nr_pr_date-1; }?>" />
 
<input type="hidden" name="cur_months" id="prev_months" value="<?php if($nr_pr_date!=1) { echo $nr_pr_date-1; } else { echo $nr_pr_date+1; }?>" />
 
</div></div>

<div class="col-md-2 col-sm-2 bor-o">
<a class="btn btn-default" href="#" role="button" data-toggle="modal" data-target="#myModal-ps">Customize calendar</a>
</div>

<div class="col-md-5 col-sm-5 dr">
<ul class="list-inline">
<li class="orgs"><a class="btn btn-warning" href="#" role="button" data-toggle="modal" data-target="#myModal-p2">Full Update</a></li> &nbsp;&nbsp;&nbsp;&nbsp;

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


<a href="javascript:;" class="pull-left mar-right prev_months <?php if($nr_pr_date==date('m')){?> display_none <?php } ?>"><img src="<?php echo base_url();?>user_assets/images/pre.png"></a>
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
    <li class="<?php if($nr_pr_date==$m) { ?>active<?php } ?> change_months"  custom="<?php echo $m;?>"><a href="javascript:;" ><?php echo date('F', mktime(0,0,0,$m));?></a></li>
    <?php } ?>
   </ul>
</div>
<a href="javascript:;" class="pull-left mar-left next_months <?php if($nr_pr_date==12){?> display_none <?php } ?>"><img src="<?php echo base_url();?>user_assets/images/next.png"></a>


</li> &nbsp; &nbsp;&nbsp;

<li class="orgs"><a class="btn btn-default" href="<?php echo lang_url();?>inventory/bulk_update" role="button">Bulk Update</a></li>
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
<div id="customize_dates">

<div class="content fn-w-n">
<form method="post" >
<div class="table table-responsive imp">
<table class="table table-bordered tbl_reser_no" style="width:100%" id="tbl_reser_no">
<thead>
<tr>
<th colspan="2"></th>
<?php foreach($show_month as $val)  {
	
	if($val==$col_month){ $c_head = $month_s; $col=$fi-1;} else { $c_head = $month_l; $col=$li-1;}
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
   <div class="cal_cta" style="margin-top:25px;" title="Close to Arrival"> <a href="javascript:;" title="Close to Arrival"> CTA </a></div>
   <div class="cal_ctd" style="margin-top:25px;" title="Close to Departure"> <a href="javascript:;" title="Close to Departure"> CTD </a></div>
   </td>
<?php foreach($period as $date)
{$ss_da = $date->format("d/m/Y");
	?>
    
   
<td>

<div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price"><span title="Click to update the price"></span><i class="fa fa-money" title="Click to update the price"></i></a>
</div>

<div class="g-top-n ji pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Availability" style="font-size:15px"><span title="Click to update the availability"></span><i class="fa fa-home" title="Click to update the availability"></i></a>
</div>

<div class="g-top-n ji pull-left cal_ms" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="minimum_stay" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Minimustay"><span title="Click to update the minimum stay"></span><i class="fa fa-moon-o" title="Click to update the minimum stay"></i></a>
</div>

<div class="cal_ss pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php echo $property_id;?>][stop_sell]" value="1" title="Stop sell ( Yes / NO )" class="">
    </label>
  </div>
 </div> 
 
<div class="cal_cta pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">
<div class="checkbox">
    <label>
     <input type="checkbox" name="room[<?php echo $property_id;?>][cta]" value="1" title="Close to Arrival ( Yes / NO )" class="">
    </label>
  </div>
</div>  

<div class="cal_ctd pull-left" style="clear:both; overflow:hidden; text-align:center !important;" align="center">

<div class="checkbox">
    <label>
      <input type="checkbox" name="room[<?php echo $property_id;?>][ctd]" value="1" title="Close to Departure ( Yes / NO )" class="">
    </label>
  </div>
  


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
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>
	</td>
	<?php } else { ?> <td class="<?php echo $color?> tal_td_bor"> 
    
    <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div>
     </td > <?php }} else { ?> <td class="<?php echo $color?> tal_td_bor" tal_td_bor> 
    <div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price" style="font-size:13px"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
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
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td>
<?php } else { ?> <td class="<?php echo $color?> tal_td_bor">
<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
</div></td><?php }}else { ?> <td class="<?php echo $color?> tal_td_bor">
<div class="g-top-n ji pull-left">
<a href="javascript:;" id="inline_username" class="inline_username pull-right" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id;?>" data-url="" data-title="Update Price"><span title="Click to update the price"></span>&nbsp;<i class="fa fa-money" title="Click to update the price"></i></a>
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
<td class="<?php echo $color?> tal_td_bor"><span><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.intval($sub_rates);?></span></td>
<?php } else {?> <td class="<?php echo $color?> tal_td_bor"> N/A </td> <?php  }}else {?> <td class="<?php echo $color?> tal_td_bor" > N/A </td> <?php } }  ?>
</tr> <?php 
}
?>
<?php } ?>

</tbody>
</table>

</div>
<input type="submit" class="btn btn-success" value="Update"/> 
<input type="reset" class="btn btn-danger" value="Reset"/>
</form>




</div>

</div>

</div>
</div>
</div>

<!--sankarialex@gmail.com
alexander9716-->
<script>
$('.inline_username').editable();
</script>