<div class="dash-b4-n calender-n" >
<div class="row-fluid clearfix">
<div class="col-md-3 col-sm-3">
<div class="cal-lef">
<ul class="list-unstyled">

<li ><a href="<?php echo lang_url();?>channel/channel_listing" <?php if(uri(3)=='channel_listing') {?>class="lef1"<?php } ?>><i class="fa fa-asterisk"></i> All</a></li>
  
<?php $con = $count_all_channels-$connected_channel; ?>

  <li ><a href="<?php echo lang_url();?>channel/all_channel" <?php if(uri(3)=='all_channel') {?>class="lef1"<?php } ?>><i class="fa fa-link"></i> Connect(<?php if($con){echo $con;}else{echo 0;} ?>)</a></li>

  <li ><a href="<?php echo lang_url(); ?>channel/connected_channel"><i class="fa fa-exchange" <?php if(uri(3)=='connected_channel') {?>class="lef1" <?php } ?>></i> Connected Channels(<?php if($connected_channel){echo $connected_channel;}else{echo 0;} ?>)</a></li>

</ul>
</div>

</div>
<div class="col-md-9 col-sm-9" style="padding-right:0;">

<div class="bg-neww">
<!-- <div class="pa-n nn2"><h4><a href="#">Calendar</a>
    <i class="fa fa-angle-right"></i>
    Online Travel Agencies
</h4></div> -->

<div class="box-m hacker-list" id="hacker-list">
<div class="row">
<div class="col-md-3">
  <div class="input-group">
      <input class="form-control search" placeholder="Search for..." type="text">
      <!-- <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span> -->
    </div> <!-- /input-group -->
</div>
</div>
<br>

  <div class="row">
   <div class="channel_listing">
  <ul class="list-inline nomargin clearfix list">
    <?php
if(count($all_con)!=0)
{
foreach($all_con as $connect)
{
  extract($connect);
  $cha = explode(',',$channel_id);
  $con_channel = $this->mapping_model->get_channel($cha);
   
?><li class="col-sm-6">
       
         
            
               <?php if($con_channel)
            { ?>
                
                  <div class="row-fluid inr_cont clearfix">
                      <div class="col-sm-4">
                          <p class="name"><?php echo $channel_name; ?></p>
                      </div>
                      <div class="col-sm-4">
                       <button class="btn <?php if($status == 'Active'){?> btn-success <?php }else if($status == "New"){?> btn-warning <?php }else if($status == "Process"){?> btn-danger <?php } ?> btn-sm"><?php if($status == 'Active'){?>Live<?php }elseif($status == 'New'){ ?>New<?php }elseif($status == 'Process'){?> Construction <?php } ?> </button>
                      </div>
                      <div class="col-sm-4">

                           <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $seo_url;?>" class="btn btn-default active">
                            <i class="fa fa-link"></i> Connected</a>
                      </div>
                  </div>
               
                 <?php } else{ ?>
               
                  <div class="row-fluid inr_cont clearfix">
                      <div class="col-sm-4">
                          <p class="name"><?php echo $channel_name; ?></p>
                      </div>
                      <div class="col-sm-4">
                        <button class="btn <?php if($status == 'Active'){?> btn-success <?php }else if($status == "New"){?> btn-warning <?php }else if($status == "Process"){?> btn-danger <?php } ?> btn-sm"><?php if($status == 'Active'){?>Live<?php }elseif($status == 'New'){ ?>New<?php }elseif($status == 'Process'){?> Construction <?php } ?> </button>
                      </div>
                      <div class="col-sm-4">
                      <?php if($status == 'Active'){?>
                          <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $seo_url;?>" class="btn btn-default name"><i class="fa fa-link"></i> connect</a>
                      <?php }else if($status == 'New'){ ?>
                          <a href="#" class="btn btn-default name"><i class="fa fa-chain-broken"></i> connect</a>
                      <?php }else if($status == "Process"){ ?>
                          <a href="#" class="btn btn-default name"><i class="fa fa-cog"></i> connect</a>
                      <?php } ?>

                      </div>
                  </div>
            
                  <?php } ?>
         
           
     
         </li>
        <?php } ?>    </ul>    </div> <?php } else{?>

          <div class="col-md-9 col-sm-9">
          <div class="bb1">
          <br>
          <div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
          <h2 class="text-center">You don't have any connected channels yet</h2>
          <br>
          <a class="btn btn-primary" href="<?php echo lang_url();?>channel/all_channelsplan"><i class="fa fa-plus"></i>  Connect Channels</a>
</div>
</div>
<?php }  ?>

       
  </div>

 <!-- <div class="row">
<div class="col-md-4 col-sm-4">Displaying <b>1</b> - <b>12</b> of <b>42</b></div>
<div class="col-md-4 col-sm-4 pull-right">
<div class="pull-right"><nav>
  <ul class="pagination">
  
    <?php echo $this->pagination->create_links(); ?>
  
  </ul>
</nav></div>
</div>
</div> 
</div>         -->       
              
              </div>
              
             
              
              </div>
              </div>
</div>


	



