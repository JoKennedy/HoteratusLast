<style type="text/css">
  
.cls_dash_reserv.cls_dash_prim {
  background-color:<?php echo $this->theme_customize['new_reservations']!='' ? $this->theme_customize['new_reservations']:'#3bc4fa' ?>;
}

.cls_dash_reserv.cls_dash_green{
  background:<?php echo $this->theme_customize['new_cancelations']!='' ? $this->theme_customize['new_cancelations']:'#28b779' ?>;
}

.cls_dash_reserv.cls_dash_orange{
background: <?php echo $this->theme_customize['arrivals']!='' ? $this->theme_customize['arrivals']:'#da542e' ?>; 
}

.cls_dash_reserv.cls_dash_blue{
background: <?php echo $this->theme_customize['departures']!='' ? $this->theme_customize['departures']:'#2255a4' ?>; 
}

.cls_1:hover .cls_dash_reserv.cls_dash_prim{
  background: <?php echo $this->theme_customize['new_reservations_hover']!='' ? $this->theme_customize['new_reservations_hover']:'#F74D4D' ?>;
}

.cls_2:hover .cls_dash_reserv.cls_dash_green {
  background: <?php echo $this->theme_customize['new_cancelations_hover']!='' ? $this->theme_customize['new_cancelations_hover']:'#f7c411' ?>;
}

.cls_3:hover .cls_dash_reserv.cls_dash_orange {
  background: <?php echo $this->theme_customize['arrivals_hover']!='' ? $this->theme_customize['arrivals_hover']:'#3bc4fa' ?>;
}

.cls_4:hover .cls_dash_reserv.cls_dash_blue {
    background: <?php echo $this->theme_customize['departures_hover']!='' ? $this->theme_customize['departures_hover']:'#28b779' ?>;
}

</style>


  <!--  <div class="container-fluid pad_adjust  mar-top-30">
    <div class="row">

<div class="col-md-12 col-sm-7 col-xs-12 cls_cmpilrigh"> -->

<div class="content">
  <div class="row mar-top-30">

  <?php
$reservation_today_count= $this->reservation_model->reservationcounts('reserve');
$cancel_today_count= $this->reservation_model->reservationcounts('cancel');
$arrival_today_count= $this->reservation_model->reservationcounts('arrival');
$depature_today_count= $this->reservation_model->reservationcounts('depature');
?> 

	<div class="col-md-3 col-sm-6 cls_1">      
	<div class="cls_dash_reserv  cls_dash_prim">
	<div class="clearfix">      
	<div class="cls_resericon">
	<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
	</div>
	<div class="cls_resertxt pull-left">  
	<h4>
	<span>New Reservations </span>
	</h4>
	</div>
	<div class="pull-right details">  
	<h4>
	<span><a class="more details_modal" href="javascript:;" custom="reservation"> Details  </a> <i class="fa fa-angle-right" aria-hidden="true"></i>
	</span> 
	</h4>
	</div>
	</div>
	<div class="num">
	<span><?php echo $reservation_today_count; ?></span>
	</div>  
	</div>
	</div>

	<div class="col-md-3 col-sm-6 cls_2">
	<div class="cls_dash_reserv cls_dash_green">
	<div class="clearfix">

	<div class="cls_resericon">
	<i class="fa fa-calendar-minus-o" aria-hidden="true"></i>

	</div>
	<div class="cls_resertxt pull-left">  
	<h4>
	<span>NewCancellations</span>
	</h4>
	</div>
	<div class="pull-right details">  
	<h4>
	<span><a class="more details_modal" href="javascript:;" custom="cancelation"> Details </a> <i class="fa fa-angle-right" aria-hidden="true"></i>
	</span> 
	</h4>
	</div>
	</div>
	<div class="num2">
	<span><?php echo $cancel_today_count; ?></span>
	</div> 
	</div>
	</div>
	
	<div class="col-md-3 col-sm-6 cls_3">
	<div class="cls_dash_reserv cls_dash_orange">
	<div class="clearfix">

	<div class="cls_resericon">
	<i class="fa fa-user" aria-hidden="true"></i>
	</div>
	<div class="cls_resertxt pull-left">  
	<h4>
	<span>Arrivals</span>
	</h4>
	</div>
	<div class="pull-right details">  
	<h4>
	<span><a class="more details_modal" href="javascript:;" custom="arrival"> Details </a> <i class="fa fa-angle-right" aria-hidden="true"></i>
	</span> 
	</h4>
	</div>
	</div>
	<div class="num3">
	<span><?php echo $arrival_today_count; ?></span>
	</div> 
	</div>
	</div>
	
	<div class="col-md-3 col-sm-6 cls_4">
	<div class="cls_dash_reserv cls_dash_blue">
	<div class="clearfix">

	<div class="cls_resericon">
	<i class="fa fa-road" aria-hidden="true"></i>
	</div>
	<div class="cls_resertxt pull-left">  
	<h4>
	<span>Departures</span>
	</h4>
	</div>
	<div class="pull-right details">  
	<h4>
	<span> <a class="more details_modal" href="javascript:;" custom="departure"> Details </a> <i class="fa fa-angle-right" aria-hidden="true"></i>
	</span> 
	</h4>
	</div>
	</div>
	<div class="num4">
	<span><?php echo $depature_today_count; ?></span>
	</div> 
	</div>
	</div>
    
	

	</div>
    
    <div class="row mar-top-30   wow fadeInUp">
      
      <div class="col-md-6 col-sm-12">
      <div class="cls_dash_box">
      <h5>Channel Status</h5>
      
      <ul class="cls_dashlist" id="table1">
        <?php if(($con_cha1)!=0) { 
        $style='done';
        $content='';
          
       /* echo '<pre>';
        print_r($con_cha1);die;*/

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

      <li><i class="fa fa-check-circle-o text-danger" aria-hidden="true"></i>  <?php echo ucfirst($channel_name);?> <span class="pull-right"> <?php echo $content;?> </span> </li>

      <?php 
} } else{ ?>
  
  <li>No Records Found!! </li>
  
    <!--   <li><i class="fa fa-check-circle-o text-danger" aria-hidden="true"></i>  Reconline <span class="pull-right"> Channel Disabled </span> </li>
      <li><i class="fa fa-check-circle-o text-danger" aria-hidden="true"></i>  GTA <span class="pull-right"> Channel Disabled </span> </li>
       <li><i class="fa fa-check-circle-o text-success" aria-hidden="true"></i>  Booking.com </li> -->
     <?php } ?>
      </ul>
      
      </div>
      </div>
      
    
    <div class="col-md-6 col-sm-12">


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



		<div class="cls_dash_box">
		<h5>Occupancy</h5>      
		<div class="cls_chan_grp">
		<div class="col-md-4">     
		<div class="pie_progress" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_today;?>">
		</div>
		</div>
		<div class="col-md-4">     
		<div class="pie_progress1" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_week;?>">
		</div>
		</div>
		<div class="col-md-4">     
		<div class="pie_progress2" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $persent_month;?>">
		</div>
		</div>
		
		<div class="row week-det">
		<div class="col-md-4 col-sm-4  time_details">
		<?php echo $persent_today;?> %
		<p>Today</p>
		</div>
     
		<div class="col-md-4 col-sm-4  time_details">
		<?php echo $persent_week;?> %
		<p>week</p>
		</div>
    
		<div class="col-md-4  col-sm-4 time_details">
		<?php echo $persent_month;?> %
		<p>month</p>
		</div>
		</div>
		</div>
      
		</div>
      </div>
    
    </div>
    
    <div class="row">
    
      <div class="col-md-6 col-sm-12">
      <div class="cls_dash_box">
      <h5>Feeds</h5>      
      <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#dash1" id="announce_tab">Announcements</a></li>
      <li><a data-toggle="tab" href="#dash2" id="alerts_tab">Alerts</a></li>
      </ul>

      <div class="tab-content">

		<div id="dash1" class="tab-pane fade in active">

		<?php	         
          $this->db->where("delflag","0");
          $this->db->where("type","1");          
          $this->db->where_in('user_id',array('0',current_user_type()));
          $this->db->where_in("hotel_id",array('0',hotel_id()));
          $this->db->where_in("sendto",array('1','3'));
          $this->db->order_by("n_id","desc");
          $fetch=$this->db->get('notifications');  
		 /*  echo $this->db->last_query();
          echo '<pre>';
          print_r($fetch->result());die; */
		if(count($fetch->result())!=0)
		{
			foreach ($fetch->result() as $value) {
			$saved_date=$value->created_date;
			$timediff = $this->admin_model->time_elapsed_string($saved_date);
			?>

			<div class="input-group cls_dashalert">
			<span class="input-group-btn">
			<button class="btn btn-secondary" type="button"><i class="fa fa-volume-off" aria-hidden="true"></i>
			</button>
			</span>
			<a href="#announcementsnews<?php echo $value->n_id;?>" class="form-control btn waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin=	"custommodal" data-overlayspeed="200" data-overlaycolor="#000"><span class="pull-left"><?php echo $value->title;?> </span><span class="pull-right"> <?php echo $timediff;?> </span></a>
			</div>
			<div class="modal-demo" id="announcementsnews<?php echo $value->n_id;?>" style="">
			<button onclick="Custombox.close();" class="close" type="button">
			<span>×</span><span class="sr-only">Close</span>
			</button>
			<h4 class="custom-modal-title"><?php echo $value->title;?></h4>
			<hr>
			<div class="custom-modal-text">
			<?php echo $value->content;?>
			</div>
			</div>
			<?php
			}
		}
		else
		{
		?>
		<center>
		<div class="input-group text-center alert-danger alert">
		No Records Found!!
		</div>
		</center>
		<?php
		}
        ?>

      </div>

		<div id="dash2" class="tab-pane fade">
					<?php           
                    $this->db->where("delflag","0");
                    $this->db->where("type","2");
                    $this->db->where_in("sendto",array('1','3'));
                    $this->db->order_by("n_id","desc");
                    $fetch=$this->db->get('notifications');
					if(count($fetch->result())!=0)
					{
                    foreach ($fetch->result() as $value) {
                    $saved_date=$value->created_date;
                    $timediff = $this->admin_model->time_elapsed_string($saved_date);                           
                    ?>        
					<div class="input-group cls_dashalert">
					
					<span class="input-group-btn">
					<button class="btn btn-secondary" type="button"><i class="fa fa-volume-off" aria-hidden="true"></i>
					<i class="fa fa-volume-off" aria-hidden="true"></i>
					</button>
					</span>
					
					<a href="#alertsnews<?php echo $value->n_id;?>" class="form-control btn waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin=	"custommodal" data-overlayspeed="200" data-overlaycolor="#000"><span class="pull-left"><?php echo $value->title;?> </span>
					<span class="pull-right"> <?php echo $timediff;?> </span></a>
					</div>
					
					<div class="modal-demo" id="alertsnews<?php echo $value->n_id;?>" style="">
					<button onclick="Custombox.close();" class="close" type="button">
					<span>×</span><span class="sr-only">Close</span>
					</button>
					<h4 class="custom-modal-title"><?php echo $value->title;?></h4>
					<hr>
					<div class="custom-modal-text">
					<?php echo $value->content;?>
					</div>
					</div>
				<?php
				}
			}
			else
			{
			?>
			<center>
			<div class="input-group text-center alert-danger alert">
			No Records Found!!
			</div>
			</center>
			<?php
			}
        ?>  
		</div>
		</div>	
		</div>
	</div>
      
      
      
      <div class="col-md-6 col-sm-12">
      <div class="cls_dash_box ">
      <h5> Top Channels, Last 30 days </h5>
      
      <div class="cls_chan_grp single-data">
       <?php 
                $reservation_channel = $this->reservation_model->reservation_channel();
                if($reservation_channel){  $i=0;
                foreach ($reservation_channel as $reser) {  
                extract($reser);
        $i++;
                 $channel_name = $this->reservation_model->channel_names($channel_id);
               ?>
                
      <div class="input-group">
    <!-- <input type="text" class="form-control" placeholder="Booking.com" aria-describedby="basic-addon1" value="<?php echo $channel_name->channel_name; ?>"> -->    
      <p class="form-control" ><?php echo $channel_name->channel_name; ?></p>
      <span class="input-group-addon" id="basic-addon1"><?php echo $total; ?></span>
      </div>
      <?php } }else{ ?>
	  <center>
      <div class="input-group text-center alert-danger alert">
      No Records Found!!
      </div>
	  </center>
    <?php } ?>

      </div>
      
      </div>
      </div>
    
    

    </div>

</div>

<?php $this->load->view('channel/dash_sidebar'); ?>
  
		<!-- </div>
		   
		</div>
		</div>


		</div>

		</div> -->

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

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug --> 


<div class="modal bs-modal-lg fade" id="today_reservation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog critical_pop_vin modal_list" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<span class="caption-subject font-green bold uppercase display_none view-print">Today's Reservations</span>
		<div class="today_reservation">
		</div>
      </div>
  
    </div>
  </div>
</div>

<div class="modal bs-modal-lg fade" id="today_cancelation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog critical_pop_vin modal_list" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<span class="caption-subject font-green bold uppercase display_none view-print">Today's Cancelations</span>
		<div class="today_cancelation">
		</div>
      </div>
  
    </div>
  </div>
</div>

<div class="modal bs-modal-lg fade" id="today_arrival" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog critical_pop_vin modal_list" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<span class="caption-subject font-green bold uppercase display_none view-print">Today's Arrivals</span>
		<div class="today_arrival">
		</div>
      </div>
  
    </div>
  </div>
</div>
 
<div class="modal bs-modal-lg fade" id="today_departure" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog critical_pop_vin modal_list" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<span class="caption-subject font-green bold uppercase display_none view-print">Today's Departures</span>
		<div class="today_departure">
		</div>
      </div>
  
    </div>
  </div>
</div>

<div class="modal bs-modal-lg fade" id="today_modify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog critical_pop_vin modal_list" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<span class="caption-subject font-green bold uppercase display_none view-print">Today's Modifications</span>
		<div class="today_modify">
		</div>
      </div>
  
    </div>
  </div>
</div>
