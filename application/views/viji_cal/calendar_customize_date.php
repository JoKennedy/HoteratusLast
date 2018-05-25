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
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> class="update_stop_sell_main">
            <?php } else { ?>
           <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> disabled="disabled">
            <?php } ?>
        </td>
	<?php } else  { ?>
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_new">
            <?php } else { ?>
            N/A
            <?php } ?>
        </td>
	<?php } } else { ?> 
	<td bgcolor="<?php //echo $color?>">
    <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d')){?> 
            <input id="<?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_M';?>" type="checkbox"  name="room[<?php echo $property_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" class="update_stop_sell_new">
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
				$manualreservation = $manualreservation;
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
		if($channel_reservation_count!=0)
		{
			$channelreservation	=	$this->reservation_model->channel_reservation_result(insep_encode($property_id),$current_start_date,$current_end_date);
		}
		else
		{
			$channelreservation		=	array();
		}
		$reservation = $this->inventory_model->cleanArray(array_merge($manualreservation,$channelreservation));
        
        $arr_pu=array();
        $reser = array();
        $date_list = 0;
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
            
            $startDate = DateTime::createFromFormat("d/m/Y",$value->start_date);
            $endDate = DateTime::createFromFormat("d/m/Y",$value->end_date);
            $periodInterval = new DateInterval("P1D");
           // $endDate->add( $periodInterval );
            $periods = new DatePeriod( $startDate, $periodInterval, $endDate );
            $re=array();
            $same=1;
            foreach($periods as $dates){ 
            //echo $dates->format("d/m/Y").'<br>';
                array_push($arr_pu,$dates->format("d/m/Y"));
                $reser[$dates->format("d/m/Y")][$date_list]= $reser_id.'~'.$reser_channel_id.'~~'.$booking_number;
                $same++;
                }
                $date_list++;
        }
		}
        /*echo '<pre>';
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
                if(empty($arr_unq))
                {
                    $arr_unq = array();
                }
                $o_loop = 1	;
                for($s=0;$s<=count($reser);$s++)
                {
                    
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
                        
						if($all_channel_id==0)
						{
							$reservation 			= 	single_manual_reservation(insep_encode($property_id),$current_start_date,$current_end_date,$get_reser);
							
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
							$room_id		= $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
							//print_r($room_id);
							if(count($room_id)!=0)
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
							}
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
					
                   /*echo '<pre>';
                    print_r($reservation);*/
                    if(count($reservation)!=0) 
                    {
                        //if(date('Y/m/d',strtotime(str_replace('/','-',$reservation['start_date'])))>=date('Y/m/d'))
                        /*if(in_array(date('d/m/Y'),$c))
                        {*/
                        $var = $reservation['start_date'];
                        $datea = str_replace('/', '-', $var);
                       	$start_dates  = date('Y-m-d', strtotime($datea));
						
						$cal_start_date = date('Y-m-d', strtotime(str_replace('/', '-', $today)));
						if($start_dates < $cal_start_date)
						{
							$start_dates = $cal_start_date; 
						}
                       
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
                        if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id)){
                    ?>
                    
                    <td colspan="<?php echo $colspn;?>" onmouseover="show_detais('<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>');" onmouseout="show_detais('<?php echo $reservation['reservation_id'].'_'.$reservation['channel_id'];?>');">
                    <?php if($reservation['start_date'] == $reservation['end_date']) { ?> 
					<?php if($reservation['channel_id'] == 0){ ?>
					<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_order/<?php echo insep_encode($reservation['reservation_id']); ?>">
					<?php } else { ?>
					<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_channel/<?php echo secure($reservation['channel_id']).'/'.insep_encode($reservation['reservation_id']); ?>">
					<?php  } ?>
                    <button type="button" style="display:block" class="<?php echo $btn_color.' '.$book_color.' '.$reservation['reservation_id'];?>"><?php if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id)){
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
					<a>
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
						$import_mapping_id 	= 		get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'roomtype_id'=>$reservation['roomTypeID'],'rate_type_id'=>$reservation['ratePlanID']))->row()->map_id;
						$property_id					= 		get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$reservation['channel_id'],'import_mapping_id'=>$import_mapping_id))->row()->property_id;
						
						$roomName 				=	 	@get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->property_name;
						
						/*$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$reservation['roomTypeID'],'rate_type_id'=>$reservation['ratePlanID']))->row()->map_id))->row()->property_id))->row()->property_name;*/
						if(!$roomName)
						{
							/*$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$reservation['roomTypeID'],'rateplan_id'=>$reservation['ratePlanID']))->row()->map_id))->row()->property_id))->row()->property_name;*/
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
                    <li>Price : <span class="pull-right"><?php echo $reservation['num_nights'] * $reservation['price'];?> <?php echo $currency;?></span></li>
                    </ul>
                    </div>
                    <?php } else { ?>
                    <?php if($reservation['channel_id'] == 0){ ?>
					<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_order/<?php echo insep_encode($reservation['reservation_id']); ?>">
					<?php } else { ?>
					<a target="_blank" href="<?php echo base_url(); ?>reservation/reservation_channel/<?php echo secure($reservation['channel_id']).'/'.insep_encode($reservation['reservation_id']); ?>">
					<?php  } ?>
                    <button type="button" style="display:block" class="<?php echo $btn_color.' '.$book_color.' '.$reservation['reservation_id'];?>"><?php if(!in_array($reservation['reservation_id'],$rer_name) || !in_array($reservation['channel_id'],$rer_cha_id)){
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
						$import_mapping_id 	= 		get_data('import_mapping',array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'roomtype_id'=>$reservation['roomTypeID'],'rate_type_id'=>$reservation['ratePlanID']))->row()->map_id;
						$property_id					= 		get_data(MAP,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$reservation['channel_id'],'import_mapping_id'=>$import_mapping_id))->row()->property_id;
						
						$roomName 				=	 	@get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->property_name;
						
						if(!$roomName)
						{
							/*$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$reservation['channel_id'],'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$reservation['roomTypeID'],'rateplan_id'=>$reservation['ratePlanID']))->row()->map_id))->row()->property_id))->row()->property_name;*/
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
                    <li>Price : <span class="pull-right"><?php echo $reservation['num_nights'] * $reservation['price'];?> <?php echo $currency;?></span></li>
                    </ul>
                    </div>
                    <?php } ?>
                    </td>
                <?php
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
                $o_loop++;
            }
            ?>
            </tr>
    <?php 
                }
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
<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
<?php } 
else { ?>
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
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
		<?php } 
		else { ?>
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
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo base_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
		<?php } 
		else { ?>
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
	<span class="pull-left rate_clr"><strong><?php echo ucfirst($rate_name);?></strong></span>
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
            <input type="checkbox"  name="rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> class="update_stop_sell_main_rate">
            <?php } else { ?>
           <input type="checkbox"  name="rate_room[<?php echo $property_id;?>][<?php echo $rate_type_id;?>][stop_sell][<?php echo $ss_da;?>]" value="1" <?php if($single->stop_sell=='1'){?> checked="checked"<?php } ?> disabled="disabled">
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
		<td class="ha2"><span class="pull-left"><?php echo ucfirst($rate_name);?> - <?php echo $v.' '.$name;?></span></td>
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
		<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo base_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
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
				<td class="ha2"><span class="pull-left"><?php echo ucfirst($rate_name);?> - <?php echo $v.' '.$name;?></span></td>
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
				<td class="ha2"><span class="pull-left"><?php echo ucfirst($rate_name);?> - Non refundable</span></td>
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
	$('.contents4').hide();               
});
</script>
