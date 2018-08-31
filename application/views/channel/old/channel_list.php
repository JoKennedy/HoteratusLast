   <div class="content">

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

     <div class="col-md-3 col-sm-6 col-xs-12 cls_resp50">
    	<div class="cls_conn_chan_blk">
    	  <h4><a href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($channel_con->channel_id); ?>" class="btn txt_setng"><i class="fa fa-building" aria-hidden="true"></i> <?php echo  $channel_con->channel_name; ?></a></h4>
          <button type="button" class="btn <?php if($channel->status=='enabled'){ echo 'btn-success'; }else{ echo 'btn-danger'; } ?>"><?php echo ucfirst($channel->status);?></button>
	      <h4><?php echo count($count);?> Rooms mapped</h4>          
          <a href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($channel_con->channel_id); ?>" class="btn txt_setng">Settings</a>
    	  </div>
	  </div>


		<?php
		} 
		}
		}
		else 
		{ 
		echo '<div class="alert alert-danger text-center"><strong>No Channels Connected.</strong></div>';
		} ?>
		
   
    <!--  <div class="col-md-3 col-sm-6 col-xs-12 cls_resp50">
    	<div class="cls_conn_chan_blk">
    	  <h4><a href=""><i class="fa fa-building" aria-hidden="true"></i> Expedia</a></h4>
          <button type="button" class="btn btn-danger">Disabled</button>
	      <h4>7 Rooms mapped</h4>
          <button type="button" class="btn txt_setng"><i class="fa fa-cog"></i>Settings</button>
    	  </div>
	  </div> -->
    
     
     
   
 
    
     </div>
         
<?php $this->load->view('channel/dash_sidebar'); ?>      