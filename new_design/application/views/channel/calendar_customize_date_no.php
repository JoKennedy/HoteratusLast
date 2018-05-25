<?php
//error_reporting(0);
$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row();
$con_ch = $channel_id;
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
<input type="hidden" name="cal_start_<?php echo $con_ch; ?>" id="cal_start_<?php echo $con_ch; ?>" value="<?php echo $today; ?>" />
				
<input type="hidden" name="cal_end_<?php echo $con_ch; ?>" id="cal_end_<?php echo $con_ch; ?>" value="<?php echo $tomorrow; ?>"/>

<div id="resp_div_<?php echo $con_ch;?>" style="display: none"></div>

<div class="content fn-w-n">
<form method="post" action="<?php echo lang_url();?>inventory/reservation_update_no" class="master_calendar_form form-inline">

<input type="hidden" name="channe_id_update" id="channe_id_update" value="<?php echo $con_ch;?>"/>

<input type="hidden" name="alter_checkbox" id="alter_checkbox_<?php echo $con_ch;?>" />
		
<input type="hidden" name="alter_checkbox_refund" id="alter_checkbox_refund_<?php echo $con_ch;?>" />

<input type="hidden" name="alter_checkbox_rate" id="alter_checkbox_rate_<?php echo $con_ch;?>" />

<input type="hidden" name="alter_checkbox_rate_refund" id="alter_checkbox_rate_refund_<?php echo $con_ch;?>"  />

<div class="content_check">

<div class="table table-responsive">

<table class="table table-bordered reservation_no_channel">

<thead>

<tr>

<th width="400" align="left" valign="middle" bgcolor="#ffffff" style="background:#ffffff;"><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>"></th>



<?php 

$t_col='0';

foreach($show_month as $val)  {



if($val==$col_month){ $c_head = $month_s; $col=$fi; $t_col=$t_col+$col;} else { $c_head = $month_l; $col=$li-1;$t_col=$t_col+$col;}

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
			//$all_propertyid = get_data('roommapping',array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$con_ch))->result();
			$all_propertyid = $this->channel_model->getMappedRoom_Rate($con_ch);
			if(count($all_propertyid!=0))
			{
				foreach($all_propertyid as $propertyid)
				{	
						
					$pid=$propertyid->property_id;
			
					$rate_id=$propertyid->rate_id;
			
					if($rate_id!="0"){
			
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
							$Mprice_data='';
							$Mavail_data='';
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
									$single = get_data(TBL_UPDATE,array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da),'availability,price,stop_sell')->row();
									
									if(count($single)!='0')
									{
										if($single->availability==0 || $single->stop_sell==1)
										{
											$pcolor = '#FF0000';
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
										
										$Mprice_data.='<td>';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Mprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Price">'.floatval($single->price).'</a>';
										}
										else
										{ 
											$Mprice_data.=floatval($single->price);
										}
										
										$Mprice_data.='</td>';
										
										
										$Mavail_data.='<td width="28" bgcolor="'.$color.'" class="w_bdr">';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Mavail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Availability">'.$single->availability.'</a>';
										}
										else
										{
											$Mavail_data.=$single->availability;
										}
										
										$Mavail_data.='</td>';
									}
									else  
									{
										$Mprice_data.='<td>';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Mprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Price"> N/A </a>';
										} 
										else 
										{
											$Mprice_data.='N/A';
										}
										
										$Mprice_data.='</td>';
										
										$Mavail_data.='<td width="28" bgcolor="'.$color.'?>" class="w_bdr">';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Mavail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Availability"> N/A </a>';
										}
										else
										{
											$Mavail_data.='N/A';
										} 
										
										$Mavail_data.='</td>';
									}
								}
								else 
								{
									$Mprice_data.='<td>';
									
									if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
									{
										$Mprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Price"> N/A </a>';
									} 
									else 
									{
										$Mprice_data.='N/A';
									}
									
									$Mprice_data.='</td>';
									
									$Mavail_data.='<td width="28" bgcolor="'.$color.'" class="w_bdr">';
									
									if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
									{
										$Mavail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'" data-url="'.lang_url().'inventory/inline_edit_channel" data-title="Change Availability"> N/A </a>';
									}
									else
									{
										$Mavail_data.='N/A';
									}
									
									$Mavail_data.='</td>';
								}
							}
							
						?>
						<tr class="p-b-o">
						   
							<td width="400" rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?> text-info"><strong><?php echo ucfirst($property_name);?></strong></td>
					
							<td bgcolor="#a6a6a6">P</td>
					
						   <?php  echo $Mprice_data; ?>
					
						</tr>
						
						<tr class="p-b-o">
					
							<td bgcolor="#a6a6a6">A</td>
					
							<?php  echo $Mavail_data; ?> 
					
						</tr>
					  
						<tr class="p-b-o msccc m_<?php echo $con_ch;?>" style="display:none;" id="ms_main_room_<?php echo $property_id.'_'.$con_ch;?>"> <td> show MS </td></tr>
						<!-- Main Room MS -->
						<tr class="p-b-o msccc s_<?php echo $con_ch;?>" style="display:none;" id="ss_main_room_<?php echo $property_id.'_'.$con_ch;?>"> <td> show SS </td></tr>
						<!-- Main Room SS -->
						<tr class="p-b-o msccc c1_<?php echo $con_ch;?>" style="display:none;" id="cta_main_room_<?php echo $property_id.'_'.$con_ch;?>"> <td> show CTA </td></tr>
						<!-- Main Room CTA -->
						<tr class="p-b-o msccc c2_<?php echo $con_ch;?>" style="display:none;" id="ctd_main_room_<?php echo $property_id.'_'.$con_ch;?>"> <td> show CTD </td></tr>
						<!-- Main Room CTD -->
					
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
					
								$sub_rates = get_data(RESERV,array('individual_channel_id'=>$con_ch,'owner_id'=>user_id(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row_array();
													
					
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
					
										<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
					
							  <?php }
					
									else 
					
									{ ?>
					
										<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							 <?php 	}
					
								}
					
								else 
					
								{ 
					
							?>
					
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php }
					
							}
					
							else { ?> 
					
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
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
					
						   <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
					
							<?php } 
					
							else { ?>
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							 <?php }}else { ?>
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
							<?php }} else { ?> 
					
							<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
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
					
						 <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
					
						<?php } 
					
						else { ?>
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}else { ?>
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }} else { ?> 
					
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
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
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability"><?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';}?> </a> <?php } else {?> <?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';} ?> <?php } ?></td>
					
						<?php } else  { ?>  
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php } } else { ?>   
					
						<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
					
						<?php }}?> 
					
						</tr>
					
						
					
						<tr style="display:none;" id="ms_sub_room_<?php echo $property_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show MS </td></tr>
						<!-- Sub Room MS -->
						<tr style="display:none;" id="ss_sub_room_<?php echo $property_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show SS </td></tr>
						<!-- Sub Room SS -->
						<tr style="display:none;" id="cta_sub_room_<?php echo $property_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show CTA </td></tr>
						<!-- Sub Room CTA -->
						<tr style="display:none;" id="ctd_sub_room_<?php echo $property_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show CTD </td></tr>
						<!-- Sub Room CTD -->
					
						
						<?php
							}
						}
					}
					if($rate_id!="0")
					{
						$main_rate_available = get_data(MAP,array('property_id'=>$property_id,'rate_id'=>$rate_id,'guest_count'=>'0','refun_type'=>'0','channel_id'=>$con_ch))->row_array();
						if(count($main_rate_available)!='0')
						{
							$Rprice_data='';
							$Ravail_data='';
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
									$single = get_data('room_rate_types_base',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_types_id'=>$rate_id,'separate_date'=>$ss_da))->row();

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
										
										$Rprice_data.='<td>';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Rprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Price">'.floatval($single->price).'</a>';
										}
										else
										{
											$Rprice_data.=floatval($single->price);
										}
										
										$Rprice_data.='</td>';
										
										$Ravail_data.='<td width="28" bgcolor="'.$color.'" class="w_bdr">';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Ravail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Availability">'.$single->availability.'</a>';
										}
										else
										{
											$Ravail_data.=$single->availability;
										}
										
										$Ravail_data.='</td>';
									}
									else  
									{
										$Rprice_data.='<td>';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Rprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Price"> N/A </a>';
										}
										else
										{
											$Rprice_data.='N/A';
										}
										
										$Rprice_data.='</td>';
										
										$Ravail_data.='<td width="28" bgcolor="'.$color.'" class="w_bdr">';
										
										if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
										{
											$Ravail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Availability"> N/A </a>';
										} 
										else
										{ 
											$Ravail_data.='N/A'; 
										} 
										
										$Ravail_data.='</td>';
									}
								}
								else
								{
									$Rprice_data.='<td>';
									
									if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
									{
										$Rprice_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price"  data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Price"> N/A </a>';
									} 
									else 
									{
										$Rprice_data.='N/A';
									}
									
									$Rprice_data.='</td>';
									
									$Ravail_data.='<td width="28" bgcolor="'.$color.'" class="w_bdr">';
									if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1')
									{
										$Ravail_data.='<a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="'.$ss_da.'-'.$property_id.'~'.$con_ch.'~'.$rate_id.'" data-url="'.lang_url().'inventory/inline_edit_channel_type" data-title="Change Availability"> N/A </a>';
									}
									else
									{
										$Ravail_data.='N/A';
									} 
									
									$Ravail_data.='</td>';
								}
							}
					?>
				
					<tr class="p-b-o">
				
						<td width="400" rowspan="2" align="left" class="ha2 msccrow_<?php echo $con_ch;?> rate_clr"><strong><?php echo ucfirst($rate_name);?></strong></td>
				
						<td bgcolor="#a6a6a6">P</td>
				
					   <?php  echo $Rprice_data;?>
				
					</tr>
				
					<tr class="p-b-o">
				
				
					<td bgcolor="#a6a6a6">A</td>
				
						<?php  echo $Ravail_data; ?> 
				
					</tr>
					
				    <tr class="p-b-o msccc m_<?php echo $con_ch;?>" style="display:none;" id="ms_main_rate_<?php echo $property_id.'_'.$rate_id.'_'.$con_ch;?>"> <td> show MS </td></tr>
					<!-- Main Rate MS -->
					<tr class="p-b-o msccc s_<?php echo $con_ch;?>" style="display:none;" id="ss_main_rate_<?php echo $property_id.'_'.$rate_id.'_'.$con_ch;?>"> <td> show SS </td></tr>
					<!-- Main Rate SS -->
					<tr class="p-b-o msccc c1_<?php echo $con_ch;?>" style="display:none;" id="cta_main_rate_<?php echo $property_id.'_'.$rate_id.'_'.$con_ch;?>"> <td> show CTA </td></tr>
					<!-- Main Rate CTA -->
					<tr class="p-b-o msccc c2_<?php echo $con_ch;?>" style="display:none;" id="ctd_main_rate_<?php echo $property_id.'_'.$rate_id.'_'.$con_ch;?>"> <td> show CTD </td></tr>
					<!-- Main Rate CTD -->
				
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
				
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
				
						  <?php }
				
								else 
				
								{ ?>
				
									<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
						 <?php 	}
				
							}
				
							else 
				
							{ 
				
						?>
				
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
						<?php }
				
						}
				
						else { ?> 
				
								<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>"  data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
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
				
					   <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
				
						<?php } 
				
						else { ?>
				
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
						 <?php }}else { ?>
				
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
						<?php }} else { ?> 
				
						<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
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
				
					 <td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
				
					<?php } 
				
					else { ?>
				
					<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
					<?php }}else { ?>
				
					<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
					<?php }} else { ?> 
				
					<td bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
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
							$single = get_data('room_rate_types_additional',array('individual_channel_id'=>$con_ch,'owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'rate_types_id'=>$rate_id,'room_id'=>$property_id,'separate_date'=>$ss_da,'guest_count'=>$v,'refun_type'=>$refun))->row();

				
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
				
					<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"> <?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability"><?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';}?> </a> <?php } else {?> <?php if($single->availability!='') { echo ($single->availability); } else { echo 'N/A';} ?> <?php } ?></td>
				
					<?php } else  { ?>  
				
					<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
					<?php } } else { ?>   
				
					<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr"><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'|'.$con_ch.'|'.$rate_id;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest_channel_type" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
				
					<?php }}?> 
				
					</tr>
					
					<tr style="display:none;" id="ms_sub_rate_<?php echo $property_id.'_'.$rate_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show MS </td></tr>
					<!-- Sub Rate MS -->
					<tr style="display:none;" id="ss_sub_rate_<?php echo $property_id.'_'.$rate_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show SS </td></tr>
					<!-- Sub Rate SS -->
					<tr style="display:none;" id="cta_sub_rate_<?php echo $property_id.'_'.$rate_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show CTA </td></tr>
					<!-- Sub Rate CTA -->
					<tr style="display:none;" id="ctd_sub_rate_<?php echo $property_id.'_'.$rate_id.'_'.$v.'_'.$refun.'_'.$con_ch;?>"> <td> show CTD </td></tr>
					<!-- Sub Rate CTD -->
				
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
<?php if(user_type()=='1' || user_type()=='2' && in_array('3',user_edit())|| admin_id()!='' && admin_type()=='1') {?>
<div class=" pull-right"> 

<!--<input type="button" class="btn btn-success" value="Update" data-toggle="modal" data-target="#channelsModal"/> -->

<input type="submit" class="btn btn-success" value="Update"/> 

<input type="reset" class="btn btn-danger" value="Reset"/>

</div>
<?php } ?>




</div>



</div>

</form>
</div>

<script>
//$('.inline_username').editable();
$('.inline_price').editable({
    step: 'any',
});

$('.inline_availability').editable();

$('.inline_minimum').editable();
</script>