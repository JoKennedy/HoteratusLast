<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-3 col-sm-3">
<div class="cal-lef">
<ul class="list-unstyled">

	<li ><a href="<?php echo lang_url();?>channel/all_channelsplan" <?php if(uri(3)=='all_channelsplan') {?>class="lef1" <?php } ?>><i class="fa fa-asterisk"></i> All</a></li>

	<?php $con = $count_all_channels-$connected_channel; ?>
	
	<li ><a href="<?php echo lang_url();?>channel/all_channels" <?php if(uri(3)=='all_channels') {?>class="lef1" <?php } ?>><i class="fa fa-link"></i> Connect(<?php if($con){echo $con;}else{echo 0;} ?>)</a></li>
	
	<li ><a href="<?php echo lang_url(); ?>channel/connected_channels" <?php if(uri(3)=='connected_channels') {?>class="lef1" <?php } ?>><i class="fa fa-link"></i> Connected Channels(<?php if($connected_channel){echo $connected_channel;}else{echo 0;} ?>)</a></li>
	
</ul>
</div>

</div>
<div class="col-md-9 col-sm-9" style="padding-right:0;">

<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>channel/connected_channels">Connected Channels</a>
    <!--<i class="fa fa-angle-right"></i>-->
  <!--  All-->
</h4></div>

<div class="box-m">
<div class="row">
<div class="col-md-3">
 
</div>
</div>
<br/>
<div class="row">
<?php


    if($con_cha)

    {

    // print_r($con_cha);die;

    foreach($con_cha as $connect)

    {

    ?>

    <div class="col-md-3 col-sm-3">

    <a class="sser" href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($connect->channel_id);?>"><div class="ss2">

    <?php $chan_img = $this->channel_model->channel_image($connect->channel_id); ?>

    <img class="img img-responsive" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$chan_img->image));?>">

    <h4 class="text-center"><i class="fa fa-link"></i> Connected</h4></div>

    </a>

    </div>

    <?php } }

 else 
	{
	?>
<div class="col-md-12 col-sm-12">
<div class="bb1">
<br>
<div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
<h2 class="text-center">You don't have any connected channels yet</h2>
<br>
<center><a class="btn btn-primary text-center" href="<?php echo lang_url();?>channel/all_channelsplan"><i class="fa fa-plus"></i>  Connect Channels</a></center>
</div>
</div>
<?php 
} 
?>
</div>


<br/>



</div>               
              
              </div>
              
             
              
              </div>
              </div>
</div>







