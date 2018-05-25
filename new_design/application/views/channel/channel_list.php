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
<h4>Connected Channels <div class="i-cir pull-right"></div></h4>
<?php 
if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
{
$owner_id= user_id();
}
elseif(user_type()=='2')
{
$owner_id = owner_id();
}
/* echo '<pre>';
print_r($connect_channel); */
if($connect_channel)
{
	foreach($connect_channel as $channel) 
	{
		if($channel->channel_id!='' && ( $channel->channel_id==2 && ( $channel->xml_type==3 || $channel->xml_type==2) ) || $channel->channel_id!=2)
		{
			$channel_con = $this->mapping_model->channel_connect($channel->channel_id);
			$count=$this->mapping_model->get_all_mapped_rooms_count($channel->channel_id);
	?>	
		<ul class="list-unstyled">
		
			<li><div class=""><i class="fa fa-map-signs"></i> <?php echo  $channel_con->channel_name; ?></div></li>
			<li><div class="enable" style="color:<?php if($channel->status=='enabled'){ echo 'green';}else {echo 'red';}?>"><?php echo ucfirst($channel->status);?>
			<div class="pull-right seting_color" style="cursor:pointer"><p><a href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($channel_con->channel_id); ?>">Settings</a></p></div></div></li>
			<li><div class="enable"><?php echo count($count);?> Rooms mapped</div></li>
			
		</ul>
		<hr>
	<?php
		} 
	}
}
else 
{ 
	echo '<div class="alert alert-danger text-center"><strong>No Channels Connected.</strong></div>';
}
?>



</div>
</div>
</div>
</div>
</div>               
</div>
</div>
</div>
</div>