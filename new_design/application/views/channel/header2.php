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
<?php //echo theme_js('pace.min.js', true);?>
<?php //echo theme_css('pace.css', true);?>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('animate.min.css', true);?>
<?php echo theme_fonts('font-awesome.css', true);?>
<?php echo theme_css('style.css?version='.rand(0,9999).'', true);?>
<?php echo theme_css('left-menu.css', true);?>
<?php echo theme_css('lightbox.css', true);?>
<?php echo theme_css('datepicker.css', true);?>
<?php echo theme_css('custom-scroll-ver.css', true);?>
<?php echo theme_css('boot-select.css', true);?>
<?php echo theme_css('text-editor.css', true);?>
<?php echo theme_css('textbox-check.css', true);?>
<?php echo theme_css('bootstrap-editable.css', true);?>
<?php echo theme_css('jquery-ui.min.css', true);?>
<?php
if(uri(3)=='reservation_order' || uri(3)=='reservation_channel')
{
   echo theme_css('components-rounded.css', true);
   
   echo theme_css('invoice.css', true);    
}
?>
<?php echo theme_css('layout.min.css', true);?>
<?php echo theme_css('light2.min.css', true);?>
<?php echo theme_css('components.min.css', true);?>
<?php echo theme_css('plugins.min.css', true);?>
<?php echo theme_css('custom.css', true);?>
<?php echo theme_css('dataTables.bootstrap.css', true);?>
<style>

.page-header.navbar{background-color:#f1f1f1; box-shadow: 1px 5px 3px rgba(0,0,0,0.2);}

.nav-dash .navbar-default .navbar-nav > .active > a, .nav-dash .navbar-default .navbar-nav > .active > a:focus, .nav-dash .navbar-default .navbar-nav > .active > a:hover{
background: <?php echo $this->theme_customize['inner_header_menu_hover']!='' ? $this->theme_customize['inner_header_menu_hover']:'#fb4b6f' ?>; 
color:#FFF;
}
.nav-dash .navbar-default .navbar-nav > li > a:focus, .nav-dash .navbar-default .navbar-nav > li > a:hover{
background: <?php echo $this->theme_customize['inner_header_menu_hover']!='' ? $this->theme_customize['inner_header_menu_hover']:'#fb4b6f' ?>; 
color:#FFF;
}

.admin-footer{
	background-color:<?php echo $this->theme_customize['inner_footer']!='' ? $this->theme_customize['inner_footer']:'#F5F5F5' ?>;
	padding-bottom:20px;
	padding-top:20px;
}

.dashboard-stat.blue-madison .more {
color:#FFF;
background-color:<?php echo $this->theme_customize['new_reservations']!='' ? $this->theme_customize['new_reservations']:'#4884b8' ?>;
}

.dashboard-stat.blue .more {
color:#FFF;
background-color:<?php echo $this->theme_customize['new_reservations']!='' ? $this->theme_customize['new_reservations']:'#258fd7' ?>;
}

.dashboard-stat.red .more {
color:#fff;
background-color:<?php echo $this->theme_customize['new_cancelations']!='' ? $this->theme_customize['new_cancelations']:'#e53e49' ?>;
}

.dashboard-stat.green-dark .more {
color:#FFF;
background-color:<?php echo $this->theme_customize['arrivals']!='' ? $this->theme_customize['arrivals']:'#46a595' ?>;
}

.dashboard-stat.green-jungle .more {
color:#FFF;
background-color:<?php echo $this->theme_customize['arrivals']!='' ? $this->theme_customize['arrivals']:'#23b176' ?>;
}

.dashboard-stat.blue-hoki .more {
color:#FFF;
background-color:<?php echo $this->theme_customize['departures']!='' ? $this->theme_customize['departures']:'#5e7694' ?>;
}

.portlet.blue,.portlet.box.blue>.portlet-title,.portlet>.portlet-body.blue,.dashboard-stat.blue {
background-color:<?php echo $this->theme_customize['new_reservations']!='' ? $this->theme_customize['new_reservations']:'#3598dc' ?>;
}

.portlet.box.green-meadow>.portlet-title,.portlet.green-meadow,.portlet>.portlet-body.green-meadow,.dashboard-stat.green-meadow {
background-color:<?php echo $this->theme_customize['new_cancelations']!='' ? $this->theme_customize['new_cancelations']:'#1BBC9B' ?>;
}

.portlet.box.red>.portlet-title,.portlet.red,.portlet>.portlet-body.red,.dashboard-stat.red {
background-color:<?php echo $this->theme_customize['new_cancelations']!='' ? $this->theme_customize['new_cancelations']:'#e7505a' ?>;
}


.portlet.box.green-jungle>.portlet-title,.portlet.green-jungle,.portlet>.portlet-body.green-jungle,.dashboard-stat.green-jungle,.mt-element-step .step-line .done .mt-step-title:after,.mt-element-step .step-line .done .mt-step-title:before,.mt-element-list .list-default.ext-1.mt-list-head .list-count.last,.mt-element-list .list-default.group .list-toggle-container .list-toggle.done,.mt-element-list .list-simple.group .list-toggle-container .list-toggle.done {
background-color:<?php echo $this->theme_customize['arrivals']!='' ? $this->theme_customize['arrivals']:'#26C281' ?>;
}

.dashboard-stat.blue-hoki {
background-color:<?php echo $this->theme_customize['departures']!='' ? $this->theme_customize['departures']:'#67809F' ?>;
}

.datepickerBg {
background: #f7f7f7 none repeat scroll 0 0;
border-bottom: 1px solid #e8e9ee; padding:10px 0 10px 35px;
}



.datepickerBg .dateRangeButton .caret {
margin:10px;
}

.mar-no{margin:0px !important;}

.top-right .dropdown {
border-left: 1px solid #cccccc;
font-size: 17px;
height: 50px;
padding:9px 0px 8px 10px;
}

.top-right a i{padding:17px 11px 8px;}


.full-width
{
width: 100% !important;
}
.error
{
color:#F00;
font-size:14px;
}
.mar-top10
{
margin-top:15px;
}

#heading_loader {
background: rgba(255,255,255,0.8);
margin: 0 auto;
min-height: 150px;
width:100%;
margin-top:-50px;
}
.loadinh_bg {

margin-top:350px !important;
}
#heading_loader {
bottom: 0;
left: 0;
position: fixed;
right: 0;
top: 0;
z-index: 10000;
background:#ggg;
}
.loadinh_bg {
border-radius: 10px;
margin: 200px auto 0;
padding:10px;
width:150px;
}
.loader
{
border: 2px solid #555555 !important;
font-style: italic;
height: 75px;
position: relative;
}
</style>
</head>
<body>

<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
               
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <div class="col-md-2 col-sm-2">
                        <?php $site_logo = get_data(CONFIG,array('id'=>1))->row()->site_logo; ?>
						<a href="<?php echo base_url();?>" title="Go to website">
						<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$site_logo));?>" class="img img-responsive" title="Go to website" style="max-height: 60px"> 
						</a> 
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="nav-dash">
                        <nav class="navbar navbar-default">
                          <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                              <button aria-expanded="false" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                              </button>
                            </div>
     <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
      <?php
	  if( admin_id()=='' && $User_Type=='2')
	  {
		   $redirect_access = get_data(ASSIGN,array('owner_id'=>owner_id(),'user_id'=>user_id(),'hotel_id'=>hotel_id()))->row();
           $access               = (array)json_decode($redirect_access->access);
           $user_access_view[]='';
           $user_access_edit[]='';
           foreach($access as $photo_id=>$photo_obj)
           {
                if(!empty($photo_obj))
                {
                    $photo = (array)$photo_obj;
					//echo $photo_id;
                    if(isset($photo['view'])!='')
                    {
                        $user_access_view[]=$photo_id;
                    }
                    else
                    {
                        $user_access_view[]='';
                 	}
                    if(isset($photo['edit'])!='')
                    {
                        $user_access_edit[]=$photo_id;
						if($photo_id=='5')
						{
							 $user_access_view[]=$photo_id;
						}
                    }
                    else
                    {
                        $user_access_edit[]='';
                    }
           		}
            }
            $user_view = array_filter($user_access_view);
            $user_edit = array_filter($user_access_edit);
	  }
	  else
	  { 
		  $user_access= array();
	  }

	  $head_access = get_data(TBL_ACCESS,array('status'=>1))->result_array();
	  foreach($head_access as $access)
	  {
		  extract($access);
		  $links = substr($link, strpos( $link,"/") + 1); 
		  $ctr = explode('/',$link);
		if(admin_id()=='' && $User_Type=='2') 
		{
			if(in_array($acc_id,$user_view)) 
			{ 
			?>
			<li class="<?php if(uri(3)==$links){?> active   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> active <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> active <?php } else if(uri(3)=='payment_list' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> active <?php } else if(uri(3)=='room_types' && $acc_id==4){?> active <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> active <?php } else if(uri(3)=='policies'&& $acc_id==4){?> active <?php } else if(uri(3)=='tax_categories'&& $acc_id==3){?> active <?php } else if(uri(3)=='edit_paypal'&& $acc_id==4){?> active <?php } else if(uri(3)=='payment_bank'&& $acc_id==4){?> active <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> active <?php } else if(uri(3)=='settings'&& $acc_id==6){?> active <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> active <?php } else if(uri(3)=='all_channel'&& $acc_id==5){?> active <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> active <?php } ?> ddd">  <?php if(uri(3)==$links){?> <div class="arrow-up"></div>   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='payment_list' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='room_types' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='policies'&& $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='tax_categories'&& $acc_id==3){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='edit_paypal'&& $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='payment_bank'&& $acc_id==4){?> <div class="arrow-up"></div> <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='settings'&& $acc_id==6){?> <div class="arrow-up"></div> <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='all_channel'&& $acc_id==5){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> <div class="arrow-up"></div> <?php } ?>
			<?php if($acc_id==4 || $acc_id==3 || $acc_id==9) { 
				if($acc_id==3) { 
					?>
					<a href="<?php echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
					<ul class="dropdown-menu">
				<li><a href="<?php echo base_url();?>inventory/bulk_update">Bulk Updates</a></li>
				<!--<li><a href="<?php //echo base_url();?>reservation/payment_list">More</a></li>-->
			  </ul>
					<?php
					}
					else if($acc_id==4){ ?>
			<a href="<?php echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo base_url();?>channel/manage_rooms">Hotel Profiles</a></li>
				<li><a href="<?php echo base_url();?>channel/manage_rooms">Room Types</a></li>
				<li><a href="<?php echo base_url();?>reservation/report_revenue">Reports</a></li>
				<li><a href="<?php echo base_url();?>channel/manage_property">Manage Properties</a></li>
			  </ul>
			<?php } else if($acc_id==9) {
				?>
               <a href="#<?php //echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
			<ul class="dropdown-menu">
				<li><a href="#">Trip-advisor</a></li>
				<li><a href="#">Facebook</a></li>
			  </ul>
                <?php }} else { ?>
			<a href="<?php echo base_url().$link;?>"><?php echo $acc_name;?></a>
			<?php } ?>
			
		   </li>
			
			<?php }
			else { /*echo 's';*/} 
		} 
		elseif(isset($User_Type)=='1' || admin_id()!='' && admin_type()=='1') 
		{ 
		?>
         <li class="<?php if(uri(3)==$links){?> active   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> active <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> active <?php } else if(uri(3)=='payment_list' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> active <?php } else if(uri(3)=='room_types' && $acc_id==4){?> active <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> active <?php } else if(uri(3)=='policies'&& $acc_id==4){?> active <?php } else if(uri(3)=='tax_categories'&& $acc_id==3){?> active <?php } else if(uri(3)=='edit_paypal'&& $acc_id==4){?> active <?php } else if(uri(3)=='payment_bank'&& $acc_id==4){?> active <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> active <?php } else if(uri(3)=='settings'&& $acc_id==6){?> active <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> active <?php }  else if(uri(3)=='all_channel'&& $acc_id==5){?> active <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> active <?php } ?> ddd">  <?php if(uri(3)==$links){?> <div class="arrow-up"></div>   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='payment_list' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='room_types' && $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='policies'&& $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='tax_categories'&& $acc_id==3){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='edit_paypal'&& $acc_id==4){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='payment_bank'&& $acc_id==4){?> <div class="arrow-up"></div> <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='settings'&& $acc_id==6){?> <div class="arrow-up"></div> <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='all_channel'&& $acc_id==5){?> <div class="arrow-up"></div> <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> <div class="arrow-up"></div> <?php } ?>
        <?php if($acc_id==4 || $acc_id==3 || $acc_id==9) {
			if($acc_id==3) { 
				?>
               <a href="<?php echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
		        <ul class="dropdown-menu">
            <li><a href="<?php echo base_url();?>inventory/bulk_update">Bulk Updates</a></li>
            <!--<li><a href="<?php //echo base_url();?>reservation/payment_list">More</a></li>-->
          </ul>
                <?php
                }else if($acc_id==4){ ?>
        <a href="<?php echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
        <ul class="dropdown-menu">
	        <li><a href="<?php echo base_url();?>channel/property_info">Hotel Profiles</a></li>
            <li><a href="<?php echo base_url();?>channel/manage_rooms">Room Types</a></li>
            <li><a href="<?php echo base_url();?>reservation/report_revenue">Reports</a></li>
           <!--  <li><a href="<?php echo base_url();?>reservation/payment_list">More</a></li> -->
            <li><a href="<?php echo base_url();?>channel/manage_property">Manage Properties</a></li>
          </ul>
        <?php }else if($acc_id==9) {
				?>
               <a href="#<?php //echo base_url().$link;?>" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $acc_name;?></a>
			<ul class="dropdown-menu">
				<li><a href="#">Trip-advisor</a></li>
				<li><a href="#">Facebook</a></li>
			  </ul>
                <?php }} else { ?>
        <a href="<?php echo base_url().$link;?>"><?php echo $acc_name;?></a>
        <?php } ?>
       </li>
        
        <?php } }  ?>
       
        
      </ul>
                              
                            </div>
                            
                            <!-- /.navbar-collapse -->
                          </div><!-- /.container-fluid -->
                        </nav>
                        </div>
                    </div>

	<div class="col-md-4 col-sm-4 pull-right nopadding">
            <?php  
                $this->db->where('delflag','0');
                $this->db->where('status','unseen');
                $this->db->where_in('user_id',array('0',current_user_type()));
				$this->db->where_in("hotel_id",array('0',hotel_id()));
				$this->db->where('reservation_id',0);
				$this->db->order_by('n_id','desc');
                $fetch=$this->db->get('notifications');
                $number=$fetch->num_rows(); ?>
                    <ul class="nav navbar-nav pull-right">
					
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
						<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <input type="hidden" name="number_count" id="number_count" value="<?php echo $number; ?>">
                                <span class="badge badge-danger" id="new_notification"><?php echo $number;?></span>
                                <i class="fa fa-bell-o font-blue-hoki"></i>
                                <span class="badge badge-danger"> <?php echo $number;?> </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    
                                        <span class="light"><a href="<?php echo base_url(); ?>channel/viewlog" target="_blank" >view log file</a></span>
                                    
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">

                  <?php 
                  if($number){
                    
                  foreach ($fetch->result() as $notification) {
                  $type=$notification->type;
                  if($type==1){
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
                  // $timediff=$this->admin_model->time2string(time()-strtotime($saved_date)).' ago';
                  ?>
                    <li class="notify" id="id_<?php echo $notification->n_id;?>">

                        <a href="javascript:;" onclick="return update_announcement('<?php echo $notification->n_id; ?>','<?php echo $notification->type?>');">

                            <span class="time"><?php echo $timediff;?></span>
                            <span class="details">
                                <span class="label label-sm label-icon <?php echo $lable;?>">
                                    <i class="fa fa-<?php echo $alert;?>"></i>
                                </span> <?php echo $title;?> </span>
                        </a>
                    </li>

                    <?php } }else{ ?>  
                          <li>
                            <center>No Notifications Found !!! </center>
                          </li>
                    <?php } ?>
                                     
                                    </ul>
                                </li>
                            </ul>
                        </li>

<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar1">
	<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
		<i class="fa fa-calendar font-blue-hoki"></i>
		
	<?php 
	$reservation_today_count= $this->reservation_model->reservationcounts('reserve');
	$cancel_today_count= $this->reservation_model->reservationcounts('cancel');
	$modify_today_count= $this->reservation_model->reservationcounts('modify');
	$alert_count = $reservation_today_count + $cancel_today_count + $modify_today_count;
	?>
	
	<input type="hidden" name="reservationcount" id="reservationcount" value="<?php echo $reservation_today_count; ?>">
    
    <input type="hidden" name="cancellationcount" id="cancellationcount" value="<?php echo $cancel_today_count; ?>">
	
    <span class="badge badge-success"><?php if($alert_count){ echo $alert_count;}else{echo '0';} ?></span>
	</a>
	
	<ul class="dropdown-menu">
		<li class="external">
				<span class="light"><a href="<?php echo base_url(); ?>channel/viewlog" target="_blank">view log file</a></span>
		</li>
		<li>
			<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
				
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
						<span class="details">
							<span class="label label-sm label-icon label-success">
								<i class="fa fa-calendar-plus-o"></i>
							</span>New reservation
					    </span>
						<span id="today_re">(<?php echo $reservation_today_count; ?>)</span>
					</a>
					
					
					<?php }
					else{ ?>
						<a class="more"  data-toggle="modal" href="javascript:;">
							<span class="details">
							<span class="label label-sm label-icon label-success">
							<i class="fa fa-calendar-times-o"></i>
							</span> New reservation (0) </span>
						</a>
						<?php } ?>
				</li>
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
		<span class="details">
			<span class="label label-sm label-icon label-danger">
				<i class="fa fa-calendar-times-o"></i>
			</span> New Cancelation 
		</span>
		<span id="today_ca">(<?php echo $cancel_today_count; ?>)</span>
		
	</a>
	
	<?php } else{  ?>
	<a class="more" data-toggle="modal" href="javascript:;">
		<span class="details">
			<span class="label label-sm label-icon label-danger">
				<i class="fa fa-calendar-times-o"></i>
			</span> New Cancelation (0)
		</span>
		 
	</a>
	<?php } ?>
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
<span class="details">
<span class="label label-sm label-icon label-info">
<i class="fa fa-calendar-times-o"></i>
</span> New Modification 
</span>
<span id="today_ca">(<?php echo $modify_today_count; ?>)</span>
</a>
<?php
}
else
{
?>
<a class="more" data-toggle="modal" href="javascript:;">
<span class="details">
<span class="label label-sm label-icon label-info">
<i class="fa fa-calendar-times-o"></i>
</span> New Modification (0) </span>
</a>
<?php
}
?>
	
	</li> 
		</ul>
		</li>
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
						 <li class="dropdown dropdown-hotel">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                               <i class="fa fa-building-o font-blue-hoki"></i>
                                <span class="username username-hide-on-mobile"> <?php echo ucfirst($current_name->property_name);?> ( Id:<?php echo $current_name->hotel_id;?> ) </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <?php 
							if(admin_id()=='')
							{
							?>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <?php
								if(user_type()=='1')
								{
									$user_hotel = get_data(HOTEL,array('status'=>'1','owner_id'=>user_id()),'hotel_id,property_name')->result_array();
								}
								else if(user_type()=='2')
								{
									//$user_hotel = get_data(HOTEL,array('status'=>'1','owner_id'=>owner_id(),''))->result_array();
									$user_hotel = $this->channel_model->assign_hotels();
								}
								if(is_array($user_hotel))
								{
								foreach($user_hotel as $hotel)
								{
									extract($hotel);
								 ?>
								<li <?php if($hotel_id==hotel_id()) { ?>class="active"<?php } ?>><a href="<?php echo base_url();?>channel/change_property/<?php echo insep_encode($hotel_id);?>"><?php echo ucfirst($property_name);?> ( Id:<?php echo $hotel_id;?> )</a></li>
								<?php
								}
								?>
								<li><a href="<?php echo base_url();?>channel/manage_property"> Add New Property </a> </li>
								<?php
								}
								else
								{
								?>
								<li><a href="<?php echo base_url();?>channel/manage_property"> Add New Property </a> </li>
								<?php
								}
								?>
                            </ul>
                            <?php
							}
							?>
                        </li>
                        <!-- END NOTIFICATION DROPDOWN -->
                 		<?php 
						if(admin_id()=='')
						{
						$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
						?>
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                               <i class="fa fa-user font-blue-hoki"></i>
                                <span class="username username-hide-on-mobile"> <?php if($user_details->fname!='') { echo ucfirst($user_details->fname); } else if($user_details->fname=='') { echo ucfirst($user_details->user_name); }?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                            <?php if($User_Type!='2')
							 {
							?>
							<li><a href="<?php echo base_url();?>channel/my_account">Myaccount</a></li>
							<?php 
							 } 
							?>
							<li><a href="<?php echo base_url();?>channel/logout"  class="tlClogo">Logout</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                        
                        
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                       
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                    </div>
                </div>
                <!-- END TOP NAVIGATION MENU -->
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
</div>

<div id="heading_loader" class="loading-circle-overlay" >
  <div id="model-back">
    <div class="loadinh_bg">
      <div class="main_content_bg">
        <div class="details_bg">
          <div style="overflow:hidden;clear:both;">
            <div  align="center" style="color:#003580; float: left; font-size: 15px; text-align:center; font-weight:bold;"><br> <!--<font size="-1" color="#A2A2A2" style="margin-left: 26px;">Please Wait...</font>-->
            <br />
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("user_assets/loader/loader.gif"));?>" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function update_announcement(id,type)
{
  // alert(id);
  if(type=='1')
  {
  	$('#announce_tab').trigger('click');
  }
  else if(type=='2')
  {
	  $('#alerts_tab').trigger('click');
  }
 
  $.ajax({
      type:"POST",
      url:"<?php echo base_url(); ?>reservation/update_announcement",
      data:{"note_id":id},
	  success:function()
	  {
		 $('#header_notification_bar').load("<?php echo base_url(); ?>channel/new_notification_result");  
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
$.post('<?php echo base_url('channel/new_notification'); ?>/',

function(data)
{
if(parseInt(data) > parseInt(old_count))
{
  $('#header_notification_bar').load("<?php echo base_url(); ?>channel/new_notification_result");
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
	 $.post('<?php echo base_url('reservation/reservation_count'); ?>/',
	function(data)
	{
		if(parseInt(data) > parseInt(old_count))
		{
		  $('#header_notification_bar1').load("<?php echo base_url(); ?>reservation/new_reservation_count");
		  $('#reservation_today_count').load("<?php echo base_url(); ?>reservation/reservation_today_count");
		  $('#cancelation_today_count').load("<?php echo base_url(); ?>reservation/cancelation_today_count");
		}
		else if(parseInt(data) < parseInt(old_count))
		{
			$('#header_notification_bar1').load("<?php echo base_url(); ?>reservation/new_reservation_count");
		 	$('#reservation_today_count').load("<?php echo base_url(); ?>reservation/reservation_today_count");
			$('#cancelation_today_count').load("<?php echo base_url(); ?>reservation/cancelation_today_count");
		}
	});
}

function Cancellation_Count()
{
	var old_count = document.getElementById('cancellationcount').value;
	$.post('<?php echo base_url('reservation/cancellation_count'); ?>/',
	function(data)
	{
		if(parseInt(data) > parseInt(old_count))
		{
		  $('#header_notification_bar1').load("<?php echo base_url(); ?>reservation/new_cancellation_count");
		  $('#reservation_today_count').load("<?php echo base_url(); ?>reservation/reservation_today_count");
		  $('#cancelation_today_count').load("<?php echo base_url(); ?>reservation/cancelation_today_count");
		}
		else if(parseInt(data) < parseInt(old_count))
		{
			$('#header_notification_bar1').load("<?php echo base_url(); ?>reservation/new_cancellation_count");
		 	$('#reservation_today_count').load("<?php echo base_url(); ?>reservation/reservation_today_count");
			$('#cancelation_today_count').load("<?php echo base_url(); ?>reservation/cancelation_today_count");
		}
	});
}
</script>

 