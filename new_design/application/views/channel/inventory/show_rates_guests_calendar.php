<?php

	/**
	* Modify: 12/11/2016 Spyros Ziogas
	*
	* Controller: ajax		(/application/controllers/ajax.php) :: change_month
	*
	* Models: inventory_advance_update_model		(/application/models/inventory_advance_update_model.php)
	*
	* Views: channel/inventory/show_rates_guests_calendar		(/application/views/channel/inventory/show_rates_guests_calendar.php)
	*
	* Tables: TBL_USERS (manage_users) :: TBL_PROPERTY (manage_property) :: TBL_UPDATE (room_update) :: RATE_TYPES (room_rate_types)
	*
	*/
?>
<?php

$da=array();

foreach($period as $show_dates)
{
	$da[]		= $show_dates->format("d/m/Y");
}

//$all_room = get_data(TBL_PROPERTY,array('property_id'=>$property_id,'status'=>'Active','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'),'property_id,non_refund,member_count,property_name,pricing_type')->result_array();

if(count($properties)!=0)
{
	foreach($properties as $room) 
	{
		extract((array)$room);

		if($non_refund==1)
		{
			$members = $member_count+$member_count;
		}
		else
		{
			$members = $member_count;
		}
		//$rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id,'user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'droc'=>'1'))->result_array();
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
						$sub_rates = get_data(RESERV,array(
							'individual_channel_id'=>'0',
							'owner_id'=>current_user_type(),
							'hotel_id'=>hotel_id(),
							'room_id'=>$property_id,
							'separate_date'=>$ss_da,
							'guest_count'=>$v,
							'refun_type'=>$refun
						))->row_array();
						
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
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td><?php } else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					 <?php }}else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php }} else { ?> 
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php } }?>
					</tr>
				<?php 						
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
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td><?php } else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					 <?php }}else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php }} else { ?> 
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
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
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td><?php } else { ?>
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php }}else { ?>
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php }} else { ?> 
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun;?>" data-url="<?php echo lang_url()?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php } }?>  
			</tr>
			<?php
		}
		
		
		
		if(count($rate_types)!=0)
		{
			foreach($rate_types as $rate_types)
			{
				extract((array)$rate_types);
				
var_dump($rate_types_refun);die;				
				
				$all_rates = get_data(RATE_TYPES_REFUN,array(
					'room_id'=>$property_id,
					'uniq_id'=>$uniq_id,
					'user_id'=>current_user_type(),
					'hotel_id'=>hotel_id()
				))->result_array();
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
						$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id),'availability,price,stop_sell')->row();
						if(count($single)!='0')
						{
							if($single->availability==0 || $single->stop_sell==1)
							{
								$color = '#FF0000';
							}
				?>
				<td>
				<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Price"><?php echo floatval($single->price); ?> </a><?php } else { ?><?php echo floatval($single->price); ?> <?php } ?></td>
				<?php } else  { ?>
				<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
				<?php } } else { ?> 
				<td><?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
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
				$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id),'availability,price,stop_sell')->row();
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
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Availability"><?php echo ($single->availability); ?> </a> <?php } else {?> <?php echo ($single->availability); ?> <?php } ?></td>
			<?php } else  { ?>  
			<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_R';?>">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php } } else { ?>   
			<td width="28" bgcolor="<?php echo $color;?>" class="w_bdr <?php echo str_replace('/','_',$ss_da).'_'.$property_id.'_R';?>">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php echo str_replace('/','_',$ss_da).'_'.$property_id;?>" data-type="number" data-name="availability" data-pk="<?php echo $ss_da.'-'.$property_id.'~'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php }}?>  
		</tr>
	 
		<tr class="p-b-o ss_main_rate ss_contents_<?php echo $property_id;?>">
		<td bgcolor="#a6a6a6">SS</td>
			
		<?php 
		foreach($period as $date)
		{
			$i=$date->format("d");
			$ss_da = $date->format("d/m/Y");
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
				$single = get_data(RATE_BASE,array('individual_channel_id'=>'0','owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'room_id'=>$property_id,'separate_date'=>$ss_da,'rate_types_id'=>$rate_type_id),'availability,price,stop_sell')->row();
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
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a> <?php } else { ?> <?php echo floatval($sub_rate);?> <?php } ?></td>
			<?php } 
			else { ?>
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			 <?php }}else { ?>
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
			<?php }} else { ?> 
			<td bgcolor="<?php echo $color;?>" class="w_bdr">
			<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
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
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { ?> <?php echo floatval($sub_rate);?> <?php   }?></td>
					<?php } 
					else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					 <?php }}else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php }} else { ?> 
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
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
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php echo floatval($sub_rate);?></a><?php } else { echo floatval($sub_rate); }?></td>
					<?php } 
					else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php }}else { ?>
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit()) || admin_id()!='' && admin_type()=='1'){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php }} else { ?> 
					<td bgcolor="<?php echo $color;?>" class="w_bdr">
					<?php if(date('Y/m/d',strtotime(str_replace('/','-',$ss_da)))>=date('Y/m/d') && user_type()=='1' || user_type()=='2' && in_array('3',user_edit())){?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php echo $ss_da.'-'.$property_id.','.$v.'~'.$refun.'>'.$rate_type_id;?>" data-url="<?php echo lang_url()?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php } else { ?> N/A <?php } ?></td>
					<?php } }?>  
					</tr>
					<?php
				}
			}
		}
	}
}
