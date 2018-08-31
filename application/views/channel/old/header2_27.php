<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>user_assets/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url();?>user_assets/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>user_assets/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>user_assets/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>user_assets/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>user_assets/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>user_assets/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>user_assets/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>user_assets/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url();?>user_assets/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>user_assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>user_assets/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>user_assets/favicon/favicon-16x16.png">
<link rel="manifest" href="<?php echo base_url();?>user_assets/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo base_url();?>user_assets/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>	<?php echo $page_heading;?> </title>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('animate.min.css', true);?>
<?php echo theme_css('datepicker.css', true);?>
<?php echo theme_css('component.css', true);?>
<?php echo theme_css('demo.css', true);?>
<?php echo theme_css('showhide.css', true);?>
<?php echo theme_css('style_dash.css?version='.rand(0,9999).'', true);?>
<?php echo theme_css('font-awesome.min.css', true);?>
<?php echo theme_css('dataTables.bootstrap.css', true);?>
<style>
/* .datepicker td.day.disabled {
    color: #eeeeee;
}


.datepicker .active {
    background-color: #4b8df8 !important;
}

.datepicker .active, .datepicker .active:hover, .datepicker .selected, .datepicker .today {
    background-image: none !important;
    filter: none !important;
} */
</style>
</head>
<body>
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<div class="mp-pusher" id="mp-pusher">
<!-- mp-menu -->
<nav id="mp-menu" class="mp-menu">
<div class="mp-level">
<h2 class="icon icon-world"><a href="<?php echo lang_url();?>"><img src="<?php echo base_url();?>user_assets/images/logo.png" class="img-responsive" alt=""></a></h2>
<ul>

<li class="active"><a class="icon icon-wallet" href="<?php echo lang_url();?>channel/dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>
 Dashboard</a></li>

<li><a class="icon icon-diamond" href="<?php echo lang_url()?>reservation/reservationlist"><i class="fa fa-book" aria-hidden="true"></i>
 Reservation</a></li>

<li><a class="icon icon-food" href="<?php echo lang_url()?>inventory/advance_update"> <i class="fa fa-calendar" aria-hidden="true"></i>
 Calendar</a></li>

<li class="active">

<a class="icon icon-food active" href="<?php echo lang_url()?>channel/property_info"><i class="fa fa-home" aria-hidden="true"></i>
Property Info <i class="fa fa-angle-down"></i> </a> 

<ul class="submenu">

	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/property_info"><i class="fa fa-building fa-lg" aria-hidden="true"></i>
	Hotel Profiles</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_rooms"><i class="fa fa-bed fa-lg" aria-hidden="true"></i>
	Room Types</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/report_revenue"><i class="fa fa-bug" aria-hidden="true"></i>
	Reports</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_property"><i class="fa fa-h-square fa-lg" aria-hidden="true"></i>Manage Properties</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/change_password"><i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
	Change Password</a> </li>



	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_subusers"><i class="fa fa-users fa-lg" aria-hidden="true"></i>
	Manage Users</a> </li>



	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/payment_list"><i class="fa fa-money fa-lg" aria-hidden="true"></i>
	Payment methods</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/policies"><i class="fa fa-star fa-lg" aria-hidden="true"></i>
	Policies</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/tax_categories"><i class="fa fa fa-object-ungroup fa-lg" aria-hidden="true"></i> Tax categories</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/billing_info"><i class="fa fa-file-text" aria-hidden="true"></i>
	Billing Details</a> </li>
		
	<li><a class="icon icon-food" href="<?php echo lang_url()?>inventory/rate_management"><i class="fa fa-list-alt" aria-hidden="true"></i>
	Membership Plan</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_channel"><i class="fa fa-exchange" aria-hidden="true"></i>
	Channel Details</a> </li>
	
</ul> 

</li>
      
<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/channel_listing"><i class="fa fa-television" aria-hidden="true"></i>
 Channel</a> </li>

<li> <a class="icon icon-food" href="<?php echo lang_url()?>mapping/connectlist"> <i class="fa fa-cog" aria-hidden="true"></i>
 Mapping </a> </li>
 
 

</ul>

</div>
</nav>
<!-- /mp-menu -->
      
    
<!-- mp-menu -->
<div class="nav_fab_list">    
<nav id="mp-menu" class="mp-menu wid">
<div class="mp-level">
<h2 class="icon icon-world smal_logo">
<img src="<?php echo base_url();?>user_assets/images/logo.png" class="img-responsive" alt="">
</h2>
<ul>
<li class="active"><a class="icon icon-wallet" href="<?php echo lang_url();?>channel/dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>
 Dashboard</a></li>

<li><a class="icon icon-diamond" href="<?php echo lang_url()?>reservation/reservationlist"><i class="fa fa-book" aria-hidden="true"></i>
 Reservation</a></li>

<li><a class="icon icon-food" href="<?php echo lang_url()?>inventory/_update"> <i class="fa fa-calendar" aria-hidden="true"></i>
 Calendar</a></li>


<li>

<a class="icon icon-food active" href="<?php echo lang_url()?>channel/property_info"><i class="fa fa-home" aria-hidden="true"></i>
Property Info <i class="fa fa-angle-down"></i> </a> 

<ul class="submenu">

	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/property_info"><i class="fa fa-building fa-lg" aria-hidden="true"></i>
	Hotel Profiles</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_rooms"><i class="fa fa-bed fa-lg" aria-hidden="true"></i>
	Room Types</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/report_revenue"><i class="fa fa-bug" aria-hidden="true"></i>
	Reports</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_property"><i class="fa fa-h-square fa-lg" aria-hidden="true"></i>Manage Properties</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_subusers"><i class="fa fa-users fa-lg" aria-hidden="true"></i>
	Manage Users</a> </li>

	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/payment_list"><i class="fa fa-money fa-lg" aria-hidden="true"></i>
	Payment methods</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/policies"><i class="fa fa-star fa-lg" aria-hidden="true"></i>
	Policies</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>reservation/tax_categories"><i class="fa fa fa-object-ungroup fa-lg" aria-hidden="true"></i> Tax categories</a> </li> 
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/billing_info"><i class="fa fa-file-text" aria-hidden="true"></i>
	Billing Details</a> </li>
		
	<li><a class="icon icon-food" href="<?php echo lang_url()?>inventory/rate_management"><i class="fa fa-list-alt" aria-hidden="true"></i>
	Membership Plan</a> </li>
	
	<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/manage_channel"><i class="fa fa-exchange" aria-hidden="true"></i>
	Channel Details</a> </li>
	
</ul> 

</li>
       
<li><a class="icon icon-food" href="<?php echo lang_url()?>channel/channel_listing"><i class="fa fa-television" aria-hidden="true"></i>
 Channel</a> </li>

<li> <a class="icon icon-food" href="<?php echo lang_url()?>mapping/connectlist"> <i class="fa fa-cog" aria-hidden="true"></i>
 Mapping </a> </li>



    
<!--<li>
<a class="icon icon-food" href="#"><img src="img/li7.png" class="img-responsive" alt=""> Role Access</a>
</li>   --> 
</ul>
</div>
</nav>
    </div>
    <!-- mp-menu -->      

<div class="scroller push_mar"><!-- this is for emulating position fixed of the nav -->
    
    
<div class="scroller-inner">

<div class="content clearfix">
<div class="header_inner clearfix">
<div class="block block-40 clearfix">
<p><a href="#" id="trigger" class="menu-trigger" onClick="test()"></a></p>

</div>
<div class="user_acc">
<div class=" " role="navigation">
<div class=""> 
<div class="collapse navbar-collapse menu_bot">

<ul class="nav navbar-nav navbar-right">

<li class="dropdown list_nav">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">

<span class="admin_name"> <i class="fa fa-bell-o font-blue-hoki"></i> </span>
</a>
<?php  
	$this->db->where('delflag','0');
	$this->db->where('status','unseen');
	$this->db->where_in('user_id',array('0',current_user_type()));
	$this->db->where_in("hotel_id",array('0',hotel_id()));
	$this->db->where('reservation_id',0);
	$this->db->order_by('n_id','desc');
	$fetch=$this->db->get('notifications');
	$number=$fetch->num_rows(); 
?>
<ul class="dropdown-menu user_icon">
<li class="divider navbar-login-session-bg"></li>
<?php 
if($number)
{
	foreach($fetch->result() as $notification) 
	{
		$type	=	$notification->type;
		if($type==1)
		{
			$title='New Announcement';
			$alert='bullhorn';
			$lable='label-success';
		}
		else if($type==2)
		{
			$title='New Alerts';
			$alert='terminal';
			$lable='label-danger';
		}
		$saved_date=$notification->created_date;
		$timediff = $this->admin_model->time_elapsed_string($saved_date);

?>
<li> <a href="javascript:;" onclick="return update_announcement('<?php echo $notification->n_id; ?>','<?php echo $notification->type?>');">
<?php echo $title;?> <i class="fa fa-<?php echo $alert;?>"> </i>
</a></li>
<li class="divider"></li>
<?php
	}
}
else
{
?>
<li><a href="javascript:;">No Records Found!!! </a></li>
<?php
}
?>


</ul>

</li>

<?php 
	$reservation_today_count= $this->reservation_model->reservationcounts('reserve');
	$cancel_today_count= $this->reservation_model->reservationcounts('cancel');
	$modify_today_count= $this->reservation_model->reservationcounts('modify');
	$alert_count = $reservation_today_count + $cancel_today_count + $modify_today_count;
?>

<input type="hidden" name="reservationcount" id="reservationcount" value="<?php echo $reservation_today_count; ?>">
    
<input type="hidden" name="cancellationcount" id="cancellationcount" value="<?php echo $cancel_today_count; ?>">

<li class="dropdown list_nav">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 
        <span class="admin_name"> <i class="fa fa-calendar font-blue-hoki "></i>  </span>
    </a>
    <ul class="dropdown-menu user_icon">
        <li class="divider navbar-login-session-bg"></li>
         <li>
				<?php
				if($reservation_today_count!='0')
				{
					$cancel_today_counts= $this->reservation_model->reservationresult('reserve');
					if($cancel_today_counts!='')
					{
						$i=0;
						foreach( $cancel_today_counts as $vall)
						{
							if($i==0)
							{
								$saved_date = $vall->current_date_time.'<br>';
							}
							$i++;
							//break;
						}
					}
					$timediff = $this->admin_model->time_elapsed_string($saved_date);
				?>

                   <a class="more details_modal" href="javascript:;" custom="reservation">
					<span class="time"><?php echo $timediff; ?></span>
						<span class="details"> New reservation  </span>
						<span id="today_re">(<?php echo $reservation_today_count; ?>)</span>
					</a>
					
					
					<?php }
					else{ ?>
						<a class="more"  data-toggle="modal" href="javascript:;">
							<span class="details"> New reservation (0) </span>
						</a>
						<?php } ?>
				</li>
				<li class="divider"></li>
				<li>
<?php
if($cancel_today_count!=0)
{ 
	$cancel_today_counts= $this->reservation_model->reservationresult('cancel');
	if($cancel_today_counts!='')
	{
		$i=0;
		foreach( $cancel_today_counts as $vall)
		{
			if($i==0)
			{
				$saved_date = $vall->current_date_time;
			}
			//break;
		}
	}
	$timediff = $this->admin_model->time_elapsed_string($saved_date);
?>
	<a class="more details_modal" href="javascript:;" custom="cancelation">
		
		<span class="time"><?php echo $timediff; ?></span>
		<span class="details">	New Cancelation </span>
		<span id="today_ca">(<?php echo $cancel_today_count; ?>)</span>
		
	</a>
	
	<?php } else{  ?>
	<a class="more" data-toggle="modal" href="javascript:;">
		<span class="details"> New Cancelation (0) </span>
		 
	</a>
	<?php } ?>
	
	</li>
	<li class="divider"></li>
	<li>
<?php
if($modify_today_count!=0)
{
	$cancel_today_counts= $this->reservation_model->reservationresult('modify');
	if($cancel_today_counts!='')
	{
		$i=0;
		foreach( $cancel_today_counts as $vall)
		{
			if($i==0)
			{
				$saved_date = $vall->current_date_time;
			}
			//break;
		}
	}
	$timediff = $this->admin_model->time_elapsed_string($saved_date);
?>
<a class="more details_modal" href="javascript:;" custom="modify">	
<span class="time"><?php echo $timediff; ?></span>
<span class="details"> New Modification  </span>
<span id="today_ca">(<?php echo $modify_today_count; ?>)</span>
</a>
<?php
}
else
{
?>
<a class="more" data-toggle="modal" href="javascript:;">
<span class="details"> New Modification (0) </span>
</a>
<?php
}
?>
	
	</li>
<li class="divider"></li>
 
    </ul>
</li>

 <?php
                        if(user_type()=='1')
                        {
                            $current_name = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'1'))->row();
                        }
                        elseif(user_type()=='2')
                        {
                            $current_name = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'1'))->row();
                        }
                        else if(admin_id()!='' && admin_type()=='1')
                        {
                            $current_name = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'1'))->row();
                        }
                        ?>

<li class="dropdown list_nav">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 
        <span class="admin_name"> <?php echo ucfirst($current_name->property_name);?> ( Id:<?php echo $current_name->hotel_id;?> ) </span>
        <span class="glyphicon glyphicon-chevron-down icon_admn"></span>
    </a>
        <?php 
        if(admin_id()=='')
        {
        ?>
        <ul class="dropdown-menu user_icon">
        <li class="divider navbar-login-session-bg"></li>
            <?php
            if(user_type()=='1')
            {
            $user_hotel = get_data(HOTEL,array('status'=>'1','owner_id'=>user_id()),'hotel_id,property_name')->result_array();
            }
            else if(user_type()=='2')
            {           
                $user_hotel = $this->channel_model->assign_hotels();
            }
            if(is_array($user_hotel))
            {
            foreach($user_hotel as $hotel)
            {
            extract($hotel);
            ?>
            <li <?php if($hotel_id==hotel_id()) { ?>class="active"<?php } ?>><a href="<?php echo lang_url();?>channel/change_property/<?php echo insep_encode($hotel_id);?>"><?php echo ucfirst($property_name);?> ( Id:<?php echo $hotel_id;?> )</a></li>
            <li class="divider"></li>
            <?php
             } ?>
            <li><a href="<?php echo lang_url();?>channel/manage_property"> Add New Property </a></li>
            <li class="divider"></li>
            <?php
            }
            else
            {
            ?>
            <li><a href="<?php echo lang_url();?>channel/manage_property"> Add New Property </a></li>
            <li class="divider"></li>
            <?php } ?>
        
    </ul>
    <?php } ?>
</li>

<?php 
    if(admin_id()=='')
    {
    $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
   /* echo '<pre>';
    print_r($user_details);die;*/
    ?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <span class="glyphicon glyphicon-user"></span>
        <span class="admin_name"><?php if($user_details->fname!='') { echo ucfirst($user_details->fname); } else if($user_details->fname=='') { echo ucfirst($user_details->user_name); }?></span>
        <span class="glyphicon glyphicon-chevron-down icon_admn"></span>
    </a>
    <ul class="dropdown-menu user_icon">
            <li>
            <div class="navbar-login">
            <div class="row">
            <div class="col-lg-3">
            <p class="text-center">
            <span class="glyphicon glyphicon-user icon-size"></span>
            </p>
            </div>
            <div class="col-lg-9">
            <p class="text-left"><?php if($user_details->fname!='') { echo ucfirst($user_details->fname); } else if($user_details->fname=='') { echo ucfirst($user_details->user_name); }?></p>
            <p class="text-left small"><?php if($user_details->email_address!='') { echo $user_details->email_address; } ?></p>
            <!--<p class="text-left">
            <a href="#" class="btn btn-primary btn-sm btn-prof">Profile</a>
            </p>-->
            </div>
            </div>
            </div>
            </li>
            <li class="divider navbar-login-session-bg"></li>
            <li><a href="<?php echo lang_url();?>channel/my_account">Account Settings <span class="glyphicon glyphicon-cog pull-right"></span></a></li>
            


            <!--<li class="divider"></li>
            <li><a href="#">User stats <span class="glyphicon glyphicon-stats pull-right"></span></a></li>
            <li class="divider"></li>
            <li><a href="#">Messages <span class="badge pull-right"> 42 </span></a></li>
            <li class="divider"></li>
            <li><a href="#">Favourites Snippets <span class="glyphicon glyphicon-heart pull-right"></span></a></li>-->
            <li class="divider"></li>
            <li><a href="<?php echo lang_url();?>channel/manage_newsletter">Support <span class="glyphicon glyphicon-cog fa fa-users pull-right"></span></a></li>
            <li class="divider"></li>

            <li><a href="<?php echo lang_url();?>channel/tickets"> Tickets <span class="glyphicon glyphicon-cog fa fa-cogs pull-right"></span></a></li>
            <li class="divider"></li>


            <li><a href="<?php echo lang_url();?>channel/logout">Sign Out <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
            </ul>
            </li>

<?php }else{?>
<script>
	location.href= '<?php echo lang_url();?>channel/logout';
</script>
<?php }?>

</ul>
</div>
</div>
</div>
    </div> 
    </div>
</div>

<input type="hidden" value="<?php echo $this->uri->segment(2)?>" id="current_url" name="current_url">           
<input type="hidden" value="<?php echo site_url().$this->lang->lang();?>/" id="base_url" name="base_url">
<?php if(uri(3)=='confirm'){?>
<input type="hidden" value="<?php echo $confirm;?>" id="active">
<?php } else { ?>
<input type="hidden" id="active">
<?php } ?>
<input type="hidden" value="<?php echo user_id();?>" name="user_id" id="user_id">
<input type="hidden" value="<?php echo hotel_id();?>" name="hotel_id" id="hotel_id">
<input type="hidden" value="<?php echo insep_encode(current_user_type());?>" name="soc_hotel" id="soc_hotel">
<input type="hidden" value="<?php echo insep_encode(hotel_id());?>" name="soc_user" id="soc_user">
<input type="hidden" value="<?php echo uri(3);?>" name="view_support" id="view_support">
<script>
function update_announcement(id,type)
{
  // alert(id);
  if(type=='1')
  {
  	$('#announce_tab').trigger('click');
	$('.scroller').scrollTop( $(document).height() );
  }
  else if(type=='2')
  {
	  $('#alerts_tab').trigger('click');
	  $('.scroller').scrollTop( $(document).height() );
  }
 
  $.ajax({
      type:"POST",
      url:"<?php echo lang_url(); ?>reservation/update_announcement",
      data:{"note_id":id},
	  success:function()
	  {
		 $('#header_notification_bar').load("<?php echo lang_url(); ?>channel/new_notification_result");  
	  }
  });
   return false;
}
</script>

<script>
//setInterval(Notification_Count, 5000);
function Notification_Count()
{
 var old_count = document.getElementById('number_count').value;
$.post('<?php echo lang_url('channel/new_notification'); ?>/',

function(data)
{
if(parseInt(data) > parseInt(old_count))
{
  $('#header_notification_bar').load("<?php echo lang_url(); ?>channel/new_notification_result");
}
// load_more();
}
);
}

</script>

<script>
//setInterval(Reservation_Count, 5000);
//setInterval(Cancellation_Count, 5000);
function Reservation_Count()
{
	 var old_count = document.getElementById('reservationcount').value;
	 $.post('<?php echo lang_url('reservation/reservation_count'); ?>/',
	function(data)
	{
		if(parseInt(data) > parseInt(old_count))
		{
		  $('#header_notification_bar1').load("<?php echo lang_url(); ?>reservation/new_reservation_count");
		  $('#reservation_today_count').load("<?php echo lang_url(); ?>reservation/reservation_today_count");
		  $('#cancelation_today_count').load("<?php echo lang_url(); ?>reservation/cancelation_today_count");
		}
		else if(parseInt(data) < parseInt(old_count))
		{
			$('#header_notification_bar1').load("<?php echo lang_url(); ?>reservation/new_reservation_count");
		 	$('#reservation_today_count').load("<?php echo lang_url(); ?>reservation/reservation_today_count");
			$('#cancelation_today_count').load("<?php echo lang_url(); ?>reservation/cancelation_today_count");
		}
	});
}

function Cancellation_Count()
{
	var old_count = document.getElementById('cancellationcount').value;
	$.post('<?php echo lang_url('reservation/cancellation_count'); ?>/',
	function(data)
	{
		if(parseInt(data) > parseInt(old_count))
		{
		  $('#header_notification_bar1').load("<?php echo lang_url(); ?>reservation/new_cancellation_count");
		  $('#reservation_today_count').load("<?php echo lang_url(); ?>reservation/reservation_today_count");
		  $('#cancelation_today_count').load("<?php echo lang_url(); ?>reservation/cancelation_today_count");
		}
		else if(parseInt(data) < parseInt(old_count))
		{
			$('#header_notification_bar1').load("<?php echo lang_url(); ?>reservation/new_cancellation_count");
		 	$('#reservation_today_count').load("<?php echo lang_url(); ?>reservation/reservation_today_count");
			$('#cancelation_today_count').load("<?php echo lang_url(); ?>reservation/cancelation_today_count");
		}
	});
}
</script>