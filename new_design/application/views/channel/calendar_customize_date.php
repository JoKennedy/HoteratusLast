<?php
$this->db->order_by('property_id','desc');
$all_room = get_data(TBL_PROPERTY,array('status'=>'Active','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'))->result_array();

$currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;
$current_start_date 	=	date('Y-m-d',strtotime(str_replace('/','-',$start_date)));
$current_end_date 		=	date('Y-m-d',strtotime(str_replace('/','-',$end_date)));

$today = $start_date;
$today_date = date('Y/m/d',strtotime($start_date));
$date = $today;
$d = date_parse_from_format("Y-m-d", $date);
$col_month = $d["month"];
$tomorrow = $end_date;
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
<table class="table table-bordered " id="reservation_yes_tbl">
<thead>
<tr>
<th width="400" align="left"></th>
<?php 
$t_col = '0';
foreach($show_month as $val)  
{
	if($val==$col_month){ $c_head = $month_s; $col=$fi; $t_col=$t_col + $col;} else { $c_head = $month_l; $col=$li-1; $t_col=$t_col + $col;}
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
echo $thead ;
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
	$minimum_data='';
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
			$single = get_data(TBL_UPDATE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da),'availability,price,stop_sell,minimum_stay')->row();

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
				
				$minimum_data.= '<td width="28" bgcolor="" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$minimum_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Minimum Stay">'.$single->minimum_stay.'</a> ';
				} 
				else
				{
					$minimum_data.=$single->minimum_stay;
				}
				$minimum_data.='</td>';
				
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
				
				$minimum_data.= '<td width="28" bgcolor="" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
				
				if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
				{
					$minimum_data.= '<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Minimum Stay"> N/A </a>';
				}
				else 
				{ 
					$minimum_data.= 'N/A';
				} 
				$minimum_data.= '</td>';
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
			
			$minimum_data.= '<td width="28" bgcolor="" class="w_bdr '.str_replace('/','_',$ss_da).'_'.$property_id.'_M'.'">';
			
			if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
				$minimum_data.= '<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="'.$ss_da.'-'.$property_id.'" data-url="'.lang_url().'inventory/inline_edit_no" data-title="Change Minimum Stay"> N/A </a>';
			}
			else 
			{ 
				$minimum_data.= 'N/A';
			}
			$minimum_data.= '</td>';
		}
	}
?>
	
	<tr class="p-b-o">
	<td rowspan="3" class="ha2 ss_main_row" width="400"><a href="javascript:;" class="show_e" onClick="toggle_visibility('contents_<?php echo $property_id;?>','show_plus_<?php echo $property_id;?>');">
	<span class="pull-left text-info"><strong><?php echo ucfirst($property_name);?></strong></span>
    <?php if($pricing_type==2) { ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php }else if($pricing_type==1 && $non_refund==1){?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } else if(count($all_rate_types)!=0){ ?> <i class="fa fa-plus show_plus_<?php echo $property_id;?>"></i> <?php } ?>
    </a>
	</td>
	
	<td bgcolor="#a6a6a6">P</td>
		<?php echo $price_data;?>
	</tr>
	
	<tr class="p-b-o">
	  <td bgcolor="#a6a6a6">A</td>
	  <?php echo $avail_data;?>
	</tr>
	
	<tr class="p-b-o">
	  <td bgcolor="#a6a6a6">M</td>
	  <?php echo $minimum_data;?>
	</tr>

	<!-- Paste SS at Main -->
	<tr class="p-b-o ss_main show_content_<?php echo $property_id; ?>" id="stop_sale_<?php echo $property_id; ?>" style="display: none"> <td> show <td>	</tr>
	<tr class="p-b-o show_content_<?php echo $property_id; ?>" style="display: none"> <td> show <td>	</tr>
	
	<!-- Paste Reservations -->
   
    <!-- Past Gust Based -->

	<?php 

	// Past Rate Code
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

<script>
//$('.inline_username').editable();
$('.inline_price').editable({
    step: 'any',
});

$('.inline_availability').editable();

$('.inline_minimum').editable();
$("table#reservation_yes_tbl tbody tr").each(function() {        
    var cell = $.trim($(this).find('td').text());
    if (cell.length == 0){
        //console.log('empty');
         $(this).remove();
     } 
	//$('.contents4').hide();               
});
</script>
