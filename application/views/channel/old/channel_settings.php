<div class="dash-b4-n">
<div class="row-fluid clearfix">

<div style="padding-right:0;" class="col-md-12 col-sm-12">

<div class="dash-b2">
<div class="row">
<div class="col-md-12"><h3> Channel Connection Configuration</h3></div>
</div>

<div class="map-s">
<div class="row">
<div class="col-md-12 button">
<div class="map_screen">

<div class="col-md-12 col-sm-12">
<div id="exp_succ">
</div>
<!--<a id="import_rates" class="btn btn-default pull-right" channel_id="<?php echo insep_encode($channel_id);?>" href="javascript:;" role="button"><i class="fa fa-download"></i> Import room rate information from channel</a>  -->

<?php
	if($User_Type=='2')
	{
		if(in_array('6',user_edit()))
		{
			?>
		<a id="import_rates" class="btn btn-default pull-right" channel_id="<?php echo insep_encode($channel_id);?>" href="javascript:;" role="button"><i class="fa fa-download"></i> Import room rate information from channel</a>

	 <?php
		}
	}
	else if($User_Type=='1')
	{
	?> 
		<a id="import_rates" class="btn btn-default pull-right" channel_id="<?php echo insep_encode($channel_id);?>" href="javascript:;" role="button"><i class="fa fa-download"></i> Import room rate information from channel</a>
	<?php } ?>

<h4>Currently mapped to <?php echo $channelname;?><div class="i-cir pull-right"></div></h4>

    <?php
	if(user_type()==1)
	{
		
		$user_ids = user_id();
		$channel_details = get_data(MAP,array('owner_id'=>user_id(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->result_array();
		///echo count($channel_details);
	}
	else if(user_type()==2)
	{
		$user_ids = owner_id();
		$channel_details = get_data(MAP,array('owner_id'=>owner_id(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->result_array();
		//echo count($channel_details);
	}
	

    if(count($channel_details)!=0)
    {
    foreach($channel_details as $details)
	{
		extract($details);
		if($property_id!='' && $rate_id==0)
		{
			$property_name = get_data(TBL_PROPERTY,array('owner_id'=>$user_ids,'hotel_id'=>hotel_id(),'property_id'=>$property_id,'status'=>'Active'))->row()-> 	property_name;
		}
		if($property_id!='' && $rate_id!=0)
		{
			$property_name = get_data(RATE_TYPES,array('user_id'=>$user_ids,'hotel_id'=>hotel_id(),'room_id'=>$property_id,'rate_type_id'=>$rate_id))->row()->rate_name;
		}
		if($property_id!='' && $rate_id==0 && $guest_count!='' && $refun_type!='')
		{
			if($refun_type=='1')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest';
			}
			elseif($refun_type=='2')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest Non refundable';
			}
		}
		if($property_id!='' && $rate_id!='0' && $guest_count!='' && $refun_type!='')
		{
			if($refun_type=='1')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest';
			}
			elseif($refun_type=='2')
			{
				$property_name=$property_name.'-'.$guest_count.' Guest Non refundable';
			}
		}

    ?><div class="enable">Enabled</div></li>

    <?php

    $update_date = get_data(TBL_CHANNEL,array('channel_id'=>$channel_id))->row()->download_date;

    if($update_date=="")

    {

       $update_date="Still Not download";

    }

    ?>

    <div class="pull-right room_rate" style="padding-left:20px">

  <!--  <p>Room rates last review from channel <?php //echo $update_date;?><a role="button" href="<?php //echo base_url();?>mapping/export_map/<?php //echo insep_encode($channel_id).'/'.insep_encode($property_id);?>" class="btn btn-default"><i class="fa fa-download"></i> Retreive room rate information from Channel</a></p>  -->

   <?php
	if($User_Type=='2')
	{
		if(in_array('6',user_edit()))
		{
	?>
		<p>Room rates last review from channel <?php echo $update_date;?><a role="button" href="<?php echo lang_url();?>mapping/export_map/<?php echo insep_encode($channel_id).'/'.insep_encode($property_id);?>" class="btn btn-default"><i class="fa fa-download"></i> Retreive room rate information from Channel</a></p>
     <?php
	   }
	}
		else if($User_Type=='1')
		{
	?>
          <p>Room rates last review from channel <?php echo $update_date;?><a role="button" href="<?php echo lang_url();?>mapping/export_map/<?php echo insep_encode($channel_id).'/'.insep_encode($property_id);?>" class="btn btn-default"><i class="fa fa-download"></i> Retreive room rate information from Channel</a></p>	
	<?php } ?>

    </div>

    <ul class="list-unstyled">
	<li>
    <div class="">
   <!-- <i class="fa fa-map-signs"></i> <?php //echo $property_name;?> <div class="pull-right del-map"><p><i class="fa fa-close"></i><a href="<?php //echo base_url();?>mapping/remove_map/<?php //echo insep_encode($channel_id).'/'.insep_encode($mapping_id).'/'.secure($property_id);?>"> Delete Mapping </a><i class="fa fa-pencil"></i>

    <a href="<?php //echo base_url();?>mapping/maptochannel/<?php //echo insep_encode($channel_id).'/'.insep_encode($mapping_id);?>/update"> Update Mapping </a></p></div></div></li>  -->
    
     <?php
	if($User_Type=='2')
	{
		
	?>
		
    <i class="fa fa-map-signs"></i> <?php echo $property_name;?> 
    <?php 
		if(in_array('6',user_edit()))
		{
	?>
    <div class="pull-right del-map">
    <p><i class="fa fa-close"></i>
    <a href="<?php echo lang_url();?>mapping/remove_map/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id).'/'.secure($property_id);?>"> Delete Mapping </a>
    <i class="fa fa-pencil"></i>

    <a href="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id);?>/update"> Update Mapping </a></p></div>
	 <?php
	  }
	}
	 else if($User_Type=='1')
	  {
	?> 
	 <i class="fa fa-map-signs"></i> <?php echo $property_name;?> <div class="pull-right del-map"><p><i class="fa fa-close"></i><a href="<?php echo lang_url();?>mapping/remove_map/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id).'/'.secure($property_id);?>"> Delete Mapping </a><i class="fa fa-pencil"></i>

    <a href="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($mapping_id);?>/update"> Update Mapping </a></p></div></div>
	
	  <?php } ?>

    <li>

    </ul>

    <hr>

    <input type="hidden" name="property_ids" value="<?php echo $property_id; ?>">

    <input type="hidden" name="channel_ids" value="<?php echo $channel_id; ?>">

    <?php

    }

    }

    else

    {

    echo "No channel mapped";

    }

    ?>

<div class="row">
<div class="map_screen">
<div class="col-md-12 col-sm-12">
<h4> Not mapped to <?php echo $channelname;?><div class="i-cir pull-right"></div></h4>
<ul class="list-unstyled map_channel">
<?php
if($not_channel_details)
{
	foreach ($not_channel_details as $nc)
	{
		if($nc->non_refund==1)
		{
			$members = $nc->member_count+$nc->member_count;
		}
		else
		{
			$members = $nc->member_count;
		}
		  $main_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>'0','guest_count'=>'0','refun_type'=>'0','channel_id'=>$channel_id))->row_array();
		  if(count($main_available)=='0')
		  {
			  
?>

<form action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/main_room" method="post" id="main_room_map_<?php echo $nc->property_id;?>">
		<li class="text-info"><?php echo $nc->property_name;?>
        <?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
            <div class="pull-right seting_color">
            <a style="color:red;font-weight:bold;" href="javascript:;" class="main_room_map" data-id="<?php echo $nc->property_id;?>"><p>Map to Channel</p></a>
            </div>
            <?php } ?>
		</li>
</form>
<hr>
<?php } ?>
<?php 
if($nc->pricing_type==2) 
{
	if($nc->non_refund==1)
	{
		for($k=1;$k<=$members;$k++)
		{
		
			if($nc->member_count < $members)
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
			$sub_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
			if(count($sub_available)=='0')
			{
?>
<form id="sub_room_map_<?php echo $nc->property_id.'_'.$v.'_'.$refun?>" method="post" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/sub_room">
<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
<li><?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?>
<?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$v.'_'.$refun;?>" class="sub_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
</li>
</form>
<hr>
<?php 		}
		}
	}
	else
	{
		for($k=1;$k<=$members;$k++)
		{
			
			if($nc->member_count < $members)
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
			$sub_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
			if(count($sub_available)=='0')
			{
	?>
    <form id="sub_room_map_<?php echo $nc->property_id.'_'.$v.'_'.$refun?>" method="post" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/sub_room">
	<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
	<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
    <li><?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?>
    <?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
		<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$v.'_'.$refun;?>" class="sub_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
	</li>
    </form>
    <hr>
<?php 
			}
		}
	}
}
else if($nc->pricing_type==1 && $nc->non_refund==1)
{
	$v=1;
	$refun=2;
	$sub_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>'0','guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
	if(count($sub_available)=='0')
	{
?>
<form id="sub_room_map_<?php echo $nc->property_id.'_'.$v.'_'.$refun?>" method="post" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/sub_room">
	<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
	<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
<li><?php echo ucfirst($nc->property_name);?> - Non refundable
<?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$v.'_'.$refun;?>" class="sub_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
</li>
</form>
<hr>
<?php
	}
}
?>
<?php 
		$rate_types = $this->mapping_model->get_rate_types($nc->property_id);
		if($rate_types)
		{
			foreach($rate_types as $rate)
			{
				$main_rate_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>$rate->rate_type_id,'guest_count'=>'0','refun_type'=>'0','channel_id'=>$channel_id))->row_array();
				if(count($main_rate_available)=='0')
				{
 ?>
 <form id="main_rate_room_map_<?php echo $rate->rate_type_id;?>" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/<?php echo insep_encode($rate->rate_type_id); ?>" method="post">
 				<input type="hidden" name="sub_rate" value="<?php echo $rate->rate_type_id; ?>">
                <input type="hidden" value="" name="sub_rate_room_map" />
				<li class="text-info"><?php echo $rate->rate_name;?>
                <?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" class="main_rate_room_map" data-id="<?php echo $rate->rate_type_id;?>"><p>Map to Channel</p></a>
</div>
<?php } ?>
</li>
</form>
<hr>
<?php 
				}
if($rate->pricing_type==2) 
{
	if($rate->non_refund==1)
	{
		for($k=1;$k<=$members;$k++)
		{
		
			if($nc->member_count < $members)
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
			$sub_rate_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>$rate->rate_type_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
			if(count($sub_rate_available)=='0')
			{
?>
<form id="sub_rate_room_map_<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun?>" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/<?php echo insep_encode($rate->rate_type_id); ?>" method="post">
<input type="hidden" value="sub_rate_room_map" name="sub_rate_room_map" />
<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
<li><?php echo ucfirst($rate->rate_name);?> - <?php echo $v.' '.$name;?>
<?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun;?>" class="sub_rate_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
</li>
</form>
<hr>
<?php }
		}
	}
	else
	{
		for($k=1;$k<=$members;$k++)
		{
			
			if($nc->member_count < $members)
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
			$sub_rate_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>$rate->rate_type_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
			if(count($sub_rate_available)=='0')
			{
	?>
    <form id="sub_rate_room_map_<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun?>" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/<?php echo insep_encode($rate->rate_type_id); ?>" method="post">
<input type="hidden" value="sub_rate_room_map" name="sub_rate_room_map" />
<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
    <li><?php echo ucfirst($rate->rate_name);?> - <?php echo $v.' '.$name;?>
    <?php if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
		<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun;?>" class="sub_rate_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
	</li>
    </form>
    <hr>
<?php 
			}
		}
	}
}
else if($rate->pricing_type==1 && $rate->non_refund==1)
{
	$v=1;
	$refun=2;
	$sub_rate_available = get_data(MAP,array('property_id'=>$nc->property_id,'rate_id'=>$rate->rate_type_id,'guest_count'=>$v,'refun_type'=>$refun,'channel_id'=>$channel_id))->row_array();
	if(count($sub_rate_available)=='0')
	{
?>
<form id="sub_rate_room_map_<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun?>" action="<?php echo lang_url();?>mapping/maptochannel/<?php echo insep_encode($channel_id).'/'.insep_encode($nc->property_id);?>/<?php echo insep_encode($rate->rate_type_id); ?>" method="post">
<input type="hidden" value="sub_rate_room_map" name="sub_rate_room_map" />
<input type="hidden" value="<?php echo $v;?>" name="guest_count"/>
<input type="hidden" value="<?php echo $refun;?>" name="refun_type"/>
<li><?php echo ucfirst($rate->rate_name);?> - Non refundable
<?php  if(user_type()=='1' || user_type()=='2' && in_array('6',user_edit())) { ?>
<div class="pull-right seting_color">
<a style="color:red;font-weight:bold;" href="javascript:;" data-id="<?php echo $nc->property_id.'_'.$rate->rate_type_id.'_'.$v.'_'.$refun;?>" class="sub_rate_room_map"><p>Map to Channel</p></a>
</div>
<?php } ?>
</li>
</form>
<?php
	}
}
?>
<?php 		}
		}?>

<?php } 
}
else
{
		echo "No Rooms mapped";
}
?>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>               
</div>
</div>
</div>
</div>