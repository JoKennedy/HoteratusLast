<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN SIDEBAR -->

<!-- END SIDEBAR -->
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN THEME PANEL -->

<!-- END THEME PANEL -->
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
<?php
$reservation_today_count= $this->reservation_model->reservationcounts('reserve');
$cancel_today_count= $this->reservation_model->reservationcounts('cancel');
$arrival_today_count= $this->reservation_model->reservationcounts('arrival');
$depature_today_count= $this->reservation_model->reservationcounts('depature');
?>     
<div class="page-toolbar">
<div class="text-muted"> <i class="fa fa-calendar font-blue"></i> &nbsp;&nbsp;<?php echo date('d F, Y');?> </div>
</div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<div class="page-title">
                  <i class="fa fa-tachometer font-blue-dark"></i>&nbsp;&nbsp;
                  <span class="caption-subject font-blue-dark bold uppercase"> DASHBOARD</span> <div id="socket_test"> </div>
              </div>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
<!-- MODAL RESERVATIONS-->
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
  <div class="no_user">
        <div class="icon_user clr1">
            <img src="<?php echo base_url();?>user_assets/images/inn_1.png" class="img-responsive" alt="">
            </div>
        <div class="user_list fc1">
            <h3>No.of users</h3>
            <h2>15,000</h2>
            </div>
        </div>
</div>
<div class="modal fade bs-modal-lg" id="today_reservation" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" style="width:100%">
<div class="modal-contents">
<div class="portlet light bordereds">
<span class="caption-subject font-green bold uppercase display_none view-print">Today's Reservations</span>
<div class="today_reservation">
</div>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<!-- MODAL CANCELATIONS-->
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<div class="no_user">
        <div class="icon_user clr2">
            <img src="<?php echo base_url();?>user_assets/images/inn_2.png" class="img-responsive" alt="">
            </div>
        <div class="user_list fc2">
            <h3>No. of Hyip Scripts</h3>
            <h2>15,000</h2>
            </div>
        </div>
</div>
<div class="modal fade bs-modal-lg" id="today_cancelation" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg" style="width:100%">
                  <div class="modal-contents">
                       <div class="portlet light bordereds">
                       <span class="caption-subject font-green bold uppercase display_none view-print">Today's Cancelations</span>
                       <div class="today_cancelation">
				      </div>
  					</div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>

<!-- MODAL ARRIVALS-->          
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
  <div class="no_user">
        <div class="icon_user clr3">
            <img src="<?php echo base_url();?>user_assets/images/inn_3.png" class="img-responsive" alt="">
            </div>
        <div class="user_list fc3">
            <h3>No. of Investments</h3>
            <h2>15,000</h2>
            </div>
        </div>
</div>
<div class="modal fade bs-modal-lg" id="today_arrival" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:100%">
    	<div class="modal-contents">
        	<div class="portlet light bordered">
            	<span class="caption-subject font-green bold uppercase display_none view-print">Today's Arrivals</span>
                	<div class="today_arrival">
						
						
					</div>
					</div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
    
<!-- MODAL DEPARTURES-->          
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
  <div class="no_user">
        <div class="icon_user clr4">
            <img src="<?php echo base_url();?>user_assets/images/inn_4.png" class="img-responsive" alt="">
            </div>
        <div class="user_list fc4">
            <h3>No. of Exchanges</h3>
            <h2>15,000</h2>
            </div>
        </div>
</div>
<div class="modal fade bs-modal-lg" id="today_departure" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg" style="width:100%">
                  <div class="modal-contents">
                       <div class="portlet light bordered">
                      <span class="caption-subject font-green bold uppercase display_none view-print">Today's Departures</span>
                      
                      <div class="today_departure">
			
      
            
        </div>
  </div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>

<div class="modal fade bs-modal-lg" id="today_modify" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" style="width:100%">
<div class="modal-contents">
<div class="portlet light bordereds">
<span class="caption-subject font-green bold uppercase display_none view-print">Today's Modifications</span>
<div class="today_modify">
</div>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

</div>
<br />


<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->
<div class="row">
<div class="col-md-6 col-sm-6">
  <!-- BEGIN PORTLET-->
   <div class="portlet light bordered">
      <div class="portlet-title tabbable-line">
          <div class="caption">
              <i class="fa fa-globe font-green-sharp"></i>
              <span class="caption-subject font-green-sharp bold uppercase">Channel Status</span>
          </div>
          
      </div>
<div class="portlet-body">
<div class="mt-element-list">
<div class="scroller" style="height: 339px;" data-always-visible="1" data-rail-visible="0">
<div class="mt-list-container list-default">

<ul>
<?php if(($con_cha1)!=0) { 
  $style='done';
$content='';
    foreach($con_cha1 as $connected){
$channel_name=$this->channel_model->channel_image($connected->channel_id)->channel_name;
$status=$connected->status;
if($status=="enabled" && $cha_status['cha_status'.$connected->channel_id] == "0"){
$style='done';
$content='';
}else if($status=="loginfailed"){
$style='error';
$content='<div class="list-error"> <a href="javascript:;"><font color="ed6b75">                                                      
Connection to Channel disabled</font></a> </div>';
}else if($status=="Waiting"){
$style='warning';
$content='<div class="list-warning"> <a href="javascript:;"><font color="F1C40F">                                                      
Delayed Updatings</font></a> </div>';
}else if($status == "disabled"){
$style='warning';
$content='<div class="list-warning"> <a href="javascript:;"><font color="F1C40F">Channel Disabled</font></a> </div>';
}else if($cha_status['cha_status'.$connected->channel_id] != "0"){
    $style='error';
    if(strlen($cha_status['cha_status'.$connected->channel_id]) <= 50){
        $content='<div class="list-error"> <a data-toggle="modal" href="#channel_status'.$connected->channel_id.'"><font color="ed6b75">'.$cha_status['cha_status'.$connected->channel_id].'</font></a> </div>';
    }else{
        $chastatus = substr($cha_status['cha_status'.$connected->channel_id], 0, 51).'....';
        $content='<div class="list-error"> <a data-toggle="modal" href="#channel_status'.$connected->channel_id.'"><font color="ed6b75">'.$chastatus.'</font></a> </div>';
    }
}
?>
<li class="mt-list-item">
<div class="list-icon-container <?php echo $style;?>">                                                       
    <i class="fa fa-check"></i>                                                     
</div> 
<?php echo $content;?>                                                  
<div class="list-item-content">
<h5 class="uppercase bold">
    <a href="javascript:;"><?php echo ucfirst($channel_name);?></a>
</h5>

</div>
</li>

<?php 
} } else{ ?>
  <div class="col-sm-12">
  <div class="alert alert-info">
  <center><b>
    No channels Connected Yet !!!
  </b>
  </center>
   </div>
 </div>
<?php }  ?>

</ul>
</div>
</div>
</div></div>
</div>
<!-- END PORTLET-->
</div>
<?php if(($con_cha1)!=0) { 
    foreach($con_cha1 as $connected){ ?>
        <div class="modal fade bs-modal-lg" id="channel_status<?php echo $connected->channel_id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                   <div class="portlet light bordered">

                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-terminal"></i> Channel Errors 
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php echo $cha_status['cha_status'.$connected->channel_id]; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> 
                    </div> 
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div class="col-md-6 col-sm-6">
  <!-- BEGIN PORTLET-->
  <div class="portlet light bordered">
      <div class="portlet-title tabbable-line">
          <div class="caption">
              <i class="fa fa-rss font-green-sharp"></i>
              <span class="caption-subject font-green-sharp bold uppercase">Feeds</span>
          </div>
          <ul class="nav nav-tabs">
            <!-- <li class="active">
                <a href="#tab_1_3" id="error_tab"  data-toggle="tab"> Errors </a>
            </li> -->
              <li class="active">
                  <a href="#tab_1_1" id="announce_tab" data-toggle="tab"> Announcements </a>
              </li>
              <li>
                  <a href="#tab_1_2"id="alerts_tab"  data-toggle="tab"> Alerts </a>

              </li>
          </ul>
      </div>
      <div class="portlet-body">
          <!--BEGIN TABS-->
          <div class="tab-content">

            <!-- error -->
			<!--<div class="tab-pane active" id="tab_1_3">
      <div class="scroller" style="height: 290px;" data-always-visible="1" data-rail-visible1="1">
      <ul class="feeds">
          <li> 
            <?php i/*f(($con_cha1)!=0) {
                    $err=0;
                  foreach($con_cha1 as $cha){
                    $err++;
                      
                     $channel_name=$this->channel_model->channel_image($cha->channel_id)->channel_name;  
                      $saved_date=$cha->connect_date;
                  $timediff = $this->admin_model->time_elapsed_string($saved_date);*/
             ?>
              <?php //if($cha->status!='enabled'){ ?>
            <a class="more" data-toggle="modal" href="#alertsnews_<?php //echo $err; ?>">
                  <div class="col1">
                      <div class="cont">
                          <div class="cont-col1">
                             <div class="label label-sm label-warning">
                              <i class="fa fa-terminal"></i>
                          </div>
                          </div>
                          
                          <div class="cont-col2">
                           
                              <div class="desc"> <?php //echo $channel_name; ?> </div>
                             
                          </div>
                          
                      </div>
                  </div>
                   <div class="col2">
                      <div class="date"> <?php //echo $timediff; ?> </div>
                  </div> 
              </a>
               <?php //} ?>
              <div class="modal fade bs-modal-lg" id="alertsnews_<?php //echo $err; ?>" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="portlet light bordered">
 
 <div class="portlet">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-terminal"></i> Channel Errors </div>
       
    </div>
    <div class="portlet-body"><?php //echo $cha->status; ?>
    </div>
    </div>
   </div>
     <div class="modal-footer">
              <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div> </div>
    </div>
    </div>
              <?php //} } else{ ?>
                  <div class="alert alert-success">
                   <center>
                    No Errors Occurred !!!
                   </center>
                  </div>
                <?php //s} ?>
            </li>


      </ul>
  </div>
</div>-->
			<!--error--> 

            <div class="tab-pane active" id="tab_1_1">            
            <div id="anounceref">
            	<div class="scroller" style="height: 339px;" data-always-visible="1" data-rail-visible="0">
                	<ul class="feeds">
          <?php
           //$this->db->where('status','unseen');
          $this->db->where("delflag","0");
           $this->db->where("type","1");          
           $this->db->where_in('user_id',array('0',user_id()));
           $this->db->where_in("hotel_id",array('0',hotel_id()));
		   $this->db->where_in("sendto",array('1','3'));
		    $this->db->order_by("n_id","desc");
           $fetch=$this->db->get('notifications');
         
          foreach ($fetch->result() as $value) {
           $saved_date=$value->created_date;
             $timediff = $this->admin_model->time_elapsed_string($saved_date);
             // $timediff=$this->admin_model->time2string(time()-strtotime($saved_date)).' ago';
             
          ?>                <li >
                             <a class="more" data-toggle="modal" href="#announcementsnews<?php echo $value->n_id;?>">
                                  <div class="col1">
                                      <div class="cont">
                                          <div class="cont-col1">
                                             <div class="label label-sm label-default">
                                              <i class="fa fa-bullhorn"></i>
                                          </div>
                                          </div>
                                          <div class="cont-col2">
                                              <div id="announ_<?php echo $value->n_id;?>" class="desc"><?php echo $value->title;?> </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col2">
                                      <div class="date"> <?php echo $timediff;?> </div>
                                  </div>
                              </a>
                          </li>
<!-- MODAL ANNOUNCEMENTS NEWS 1-->
<div class="modal fade bs-modal-lg" id="announcementsnews<?php echo $value->n_id;?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="portlet light bordered">
     
     <div class="portlet">
      <div class="portlet-title">
          <div class="caption">
              <i class="fa fa-bullhorn"></i> <?php echo $value->title;?> </div>
         
      </div>
      <div class="portlet-body"> <?php echo $value->content;?>
  </div>
                 <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div> </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
            </div>
              <!-- /.modal-dialog -->
          </div>

          <?php
        }
        ?>
                      </ul>
                  </div>
                  </div>
              </div>
              <div class="tab-pane" id="tab_1_2">
              <div id="alertref">
                  <div class="scroller" style="height: 290px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="feeds">
                       <?php
           //$this->db->where('status','unseen');
           $this->db->where("delflag","0");
           $this->db->where("type","2");
		   $this->db->where_in("sendto",array('1','3'));
		      $this->db->order_by("n_id","desc");
           $fetch=$this->db->get('notifications');
          foreach ($fetch->result() as $value) {
           $saved_date=$value->created_date;
               $timediff = $this->admin_model->time_elapsed_string($saved_date);
              //$timediff=$this->admin_model->time2string(time()-strtotime($saved_date)).' ago';
             
          ?>        
                          <li>   <a class="more" data-toggle="modal" href="#alertsnews<?php echo $value->n_id;?>">
                                  <div class="col1">
                                      <div class="cont">
                                          <div class="cont-col1">
                                             <div class="label label-sm label-warning">
                                              <i class="fa fa-terminal"></i>
                                          </div>
                                          </div>
                                          <div class="cont-col2">
                                              <div class="desc"> <?php echo $value->title;?> </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col2">
                                      <div class="date"> <?php echo $timediff;?> </div>
                                  </div>
                              </a>
                          </li>
  
  <!-- MODAL ALERTS NEWS 1-->
<div class="modal fade bs-modal-lg" id="alertsnews<?php echo $value->n_id;?>" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="portlet light bordered">
     
     <div class="portlet">
      <div class="portlet-title">
          <div class="caption">
              <i class="fa fa-terminal"></i> <?php echo $value->title;?></div>
         
      </div>
      <div class="portlet-body"> <?php echo $value->content;?> </div>
  </div>
                 <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div> </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
          </div>
          <?php
        }
        ?>              
                      </ul>
                  </div>
                  </div>
              </div>
</div> </div> </div> </div> </div> 
<style>
  #examplePie {
    max-width: 350px;
  }
  
  .pie-progress {
    max-width: 150px;
    margin: 0 auto;
  }
  
  .pie-progress svg {
    width: 100%;
  }
  
  .pie-progress-xs {
    max-width: 50px;
  }
  
  .pie-progress-sm {
    max-width: 100px;
  }
  
  .pie-progress-lg {
    max-width: 200px;
  }
  
  
</style>

<div class="row">

<div class="col-md-6 col-sm-6">
<?php
$today_room_count=$this->reservation_model->get_count_room('today');
$confirmed_reserve=$this->reservation_model->confirmed_reserve('today');
if($confirmed_reserve!=0)
{
	$persent_today=round(($confirmed_reserve/($today_room_count+$confirmed_reserve))*100);
}
else
{
	$persent_today = 0;
}
$week_room_count=$this->reservation_model->get_count_room('week');
$confirmed_reserve_week=$this->reservation_model->confirmed_reserve('week');
if($confirmed_reserve_week!=0)
{
	$persent_week=round(($confirmed_reserve_week/($week_room_count+$confirmed_reserve_week))*100);
}
else
{
	$persent_week = 0;
}
$month_room_count=$this->reservation_model->get_count_room('month');
$confirmed_reserve_month=$this->reservation_model->confirmed_reserve('month');
if($confirmed_reserve_month!=0)
{
	$persent_month=round(($confirmed_reserve_month/($month_room_count+$confirmed_reserve_month))*100);
}
else
{
	$persent_month = 0;
}
?>
<input type="hidden" value="<?php echo $persent_today;?>" id="persent_today" name="persent_today" />

<input type="hidden" value="<?php echo $persent_week;?>" id="persent_week" name="persent_week" />

<input type="hidden" value="<?php echo $persent_month;?>" id="persent_month" name="persent_month" />

<div class="portlet light bordered">
      <div class="portlet-title tabbable-line">
          <div class="caption">
              <i class="fa fa-area-chart font-green-sharp"></i>
              <span class="caption-subject font-green-sharp bold uppercase">Occupancy</span>
          </div>
          
      </div>
<div class="row">
      <div class="portlet-body">
   
<div class="col-md-4">     
<div class="pie_progress" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_today;?>">
    <div class="pie_progress__number"><?php echo $persent_today;?> %</div>
    <div class="pie_progress__label">today</div>
</div>

</div>
<div class="col-md-4">     
<div class="pie_progress1" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_week;?>">
    <div class="pie_progress__number"><?php echo $persent_week;?> %</div>
    <div class="pie_progress__label">week</div>
</div>

</div>
<div class="col-md-4">     
<div class="pie_progress2" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_month;?>">
    <div class="pie_progress__number"><?php echo $persent_month;?>%</div>
    <div class="pie_progress__label">month</div>
</div>

</div>

</div></div></div></div>


<div class="col-md-6 col-sm-6">
  <div class="portlet light tasks-widget bordered">
      <div class="portlet-title">
          <div class="caption">
              <i class="fa fa-pie-chart font-green-sharp"></i>
              <span class="caption-subject font-green bold uppercase">Top Channels, Last 30 days</span>
              
          </div>
         
      </div>
      <div class="portlet-body">
           <ul class="list-group">
             
             <?php 
                $reservation_channel = $this->reservation_model->reservation_channel();
                if($reservation_channel){  $i=0;
                foreach ($reservation_channel as $reser) {  
				extract($reser);
				$i++;
                 $channel_name = $this->reservation_model->channel_names($channel_id);
               ?>
                <?php if($i/2==1){ ?>

                <li class="list-group-item bg-blue-sharp">
                <?php echo $channel_name->channel_name; ?>
               <span class="badge badge-success"><?php echo $total; ?></span> 
                </li> 
               
              <?php } else{ ?>

                <li class="list-group-item list-group-item-info">
                <?php echo $channel_name->channel_name; ?>
               <span class="badge badge-info"><?php echo $total; ?></span> 
                </li> 

              <?php } ?>
                <?php } }else{ ?>
                <div class="row">
                <div class="alert alert-danger">
                 <center>
                  No channels Found !!!
                </center>
                </div>
              </div>
                <?php } ?>
          </ul>
          
      </div>
  </div>
</div>
</div>

</div>
</div></div>