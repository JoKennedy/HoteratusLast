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
    <title>Admin- Channel Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="shortcut icon" type="image/png" href="<?php echo base_url();?>user_assets/images/logo_book.png"/>-->
    <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/animate.min.css" type="text/css">
    <link href="<?php echo base_url();?>admin_assets/fonts/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/yamm.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/jquery.dataTables.min.css" type="text/css">
</head>
<body>
<div class="dashboard">
<div class="row-fluid clearfix">
<div class="col-md-1 col-sm-1">
<?php $site_logo = get_data(CONFIG,array('id'=>1))->row()->site_logo; ?>
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$site_logo));?>" class="img img-responsive">
</div>
<div class="col-md-9 col-sm-9">
<div class="navbar yamm  navbar-default">
              <?php   $uri = $this->uri->segment(3); 
              ?>
                    <div class="navbar-header">
                      <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    </div>
                    <div id="navbar-collapse-1" class="navbar-collapse collapse">
                      <ul class="nav navbar-nav">
                      
                       <li class="<?php if(uri(3)=='dashboard'){ echo 'active ';}?>top_menu"><a href="<?php echo lang_url();?>admin/dashboard">Dashboard</a>  </li>
                        <!-- Classic list -->
                        <li class="dropdown top_menu <?php if(uri(3)=='all_hotels'|| uri(3)=='all_users' || uri(3)=='all_channels' || uri(3)=='hotel_details') { echo 'active'; }?>"><a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">Manage<b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            <li> 
                              <!-- Content container to add padding -->
                              <div class="yamm-content">
                                <div class="row">
                                  <ul class="col-sm-3 list-unstyled">
                                    <li>
                                      <p><strong> <i class="fa fa-home"></i> Hotels</strong></p>
                                    </li>
                                    <li><p><a href="<?php echo lang_url(); ?>admin/all_hotels"> All Hotels</a></p></li>
                                  </ul>
                                  <ul class="col-sm-3 list-unstyled">
                                    <li>
                                      <p><strong>  <i class="fa fa-user"></i> Hotel Users</strong></p>
                                    </li>

                                    <li><p><a href="<?php echo lang_url(); ?>admin/manage_users"> Hoteliers</a></p></li>
                                    <li><p><a href="<?php echo lang_url(); ?>admin/all_users"> All Users </a></p></li>
                                  

								  
                                    
                                  </ul>
                                  <ul class="col-sm-3 list-unstyled">
                                    <li>
                                      <p><strong> <i class="fa fa-globe"></i> Channels</strong></p>
                                    </li>
                                    <li><p><a href="<?php echo lang_url(); ?>admin/all_channels">All Channels </a></p></li><br>
                                   
                             </div>
                              </div>
                            </li>
                          </ul>
                        </li>
                        
                        <!-- Classic dropdown -->
						
                        <!--<li class="top_menu"><a href="<?php //echo base_url(); ?>reservation/reservationlist">Reservations</a> </li>-->
                        <li class="<?php if(uri(3)=='manage_reservation'){ echo 'active ';}?> top_menu"><a href="<?php echo lang_url();?>admin/manage_reservation/view">Reservations</a> </li>
                      
                       <li class="dropdown top_menu"><a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">Comms<b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            <li> 
                              <!-- Content container to add padding -->
                              <div class="yamm-content">
                                <div class="row">
                                  <ul class="col-sm-3 list-unstyled">
                                    <li><p><a href="<?php echo lang_url();?>admin/manage_email/view">Email Templates </a></p></li>
                                    
                                  </ul>
                                  <ul class="col-sm-3 list-unstyled">
                                   
                                    <li><p><a href="<?php echo lang_url(); ?>admin/send_newsletter"> Manage Newsletters </a></p></li>
                                   
                                  </ul>
                                   <ul class="col-sm-3 list-unstyled">
                                   
                                    <li><p><a href="<?php echo lang_url(); ?>admin/notifications"> Alerts/Announcements</a></p></li>
                                   
                                  </ul>
                                  <ul class="col-sm-3 list-unstyled">
                                   
                                    <li><p><a href="<?php echo lang_url(); ?>admin/contact"> Contact Us</a></p></li>
                                   
                                  </ul>

                                   <ul class="col-sm-3 list-unstyled">
                                   
                                    <li><p><a href="<?php echo lang_url(); ?>admin/Manage_TicketSupport"> Manage Ticket</a></p></li>
                                   
                                  </ul>
                                  
                                </div>
                              </div>
                            </li>
                          </ul>
                        </li>
                     
                      <li class="dropdown top_menu"><a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">Setup<b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            <li> 
                              <!-- Content container to add padding -->
                              <div class="yamm-content">
                                <div class="row">
                                  <ul class="col-sm-3 list-unstyled">
                                    <li>
                                      <p><strong>Manage Admin</strong></p>
                                    </li>
									<li><p><a href="<?php echo lang_url(); ?>admin/manage_admin_details"> Manage Admin Details </a></p></li>
                                    <li><p><a href="<?php echo lang_url(); ?>admin/admin_setting"> Admin Controls </a></p></li>
                                  </ul>
                                  <ul class="col-sm-3 list-unstyled">
                                   <li>
                                      <p><a href="javascript:;"><strong>Membership Plans</strong></a></p>
                                    </li>
                                    <li>
										<p><a href="<?php echo lang_url(); ?>admin/manage_payments">Manage Payments </a></p>
									</li>
                                    <li>
										<p><a href="<?php echo lang_url(); ?>admin/membership/view">Membership Plans</a></p>
                                    </li>
									<li>
                                      <p><a href="<?php echo lang_url(); ?>admin/mangecctypes/view">Manage CC Types</a></p>
                                    </li>
                                  </ul>
                                   <ul class="col-sm-3 list-unstyled">
              <li>
                <p><strong>CMS</strong></p>
              </li>
              <li><p><a href="<?php echo lang_url();?>admin/site_config"> Site Config </a></p></li>
              <li><p><a href="<?php echo lang_url();?>admin/about/view"> About us </a></p></li>
              <li><p><a href="<?php echo lang_url();?>admin/tc/view"> Terms and Conditions</a></p></li>
              <li><p><a href="<?php echo lang_url();?>admin/features/view">Features</a></p></li>
              <li><p><a href="<?php echo lang_url();?>admin/privacy/view"> Privacy Policy </a></p></li>
              <li><p><a href="<?php echo lang_url();?>admin/faq/view">FAQ</a></p></li>
           
                                              </ul>
                                                  <ul class="col-sm-3 list-unstyled">
				<li>
					<p><strong>CMS</strong></p>
				</li>
                <li><p><a href="<?php echo lang_url();?>admin/home/view">Home CMS</a></p></li>
				<li><p><a href="<?php echo lang_url();?>admin/multiproperty/view">Multiproperty</a></p></li>
				<li><p><a href="<?php echo lang_url();?>admin/connectchannels/view">Connected channels</a></p></li>
				<li><p><a href="<?php echo lang_url();?>admin/otherCms/view">Other CMS Pages</a></p></li>
                </ul>
                                  
                                </div>
                              </div>
                            </li>
                          </ul>
                        </li>
						
						<li class="<?php if(uri(3)=='theme_customize'){ echo 'active ';}?> top_menu"><a href="<?php echo lang_url();?>admin/theme_customize">Theme Customize </a> </li>
                        
                      </ul>
                    </div>
                </div>
</div>
<div class="col-md-2 col-sm-2">
<div class="pull-right top-right">
<a href="<?php echo lang_url(); ?>admin/log_out"><i class="fa fa-user"></i> Logout</a>

</div>
</div>

</div>
</div>