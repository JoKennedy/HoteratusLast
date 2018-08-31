<style>
.error{color: #ff1a1a;}
.main_menu{
  background:<?php echo $this->theme_customize['h_header_one']!='' ? $this->theme_customize['h_header_one']:'rgba(0, 0, 0, 0.5) none repeat scroll 0 0' ?>;
  opacity: 0.80;
}

.why{
  background:<?php echo $this->theme_customize['h_header_two']!='' ? $this->theme_customize['h_header_two']:'#fff' ?>;
}

.content2{
background: <?php echo $this->theme_customize['inner_header_menu_hover']!='' ? $this->theme_customize['inner_header_menu_hover']:'#fff' ?>; 
}
</style>

<!DOCTYPE html>
<html lang="en">
<head> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="Channel manager, Hotel channel manager, hotel reservation system, global distribution system, the best channel manager, distribution channel manager, guest review express, point of sale, facebook button, booking engine, hotel website, property management system (PMS), revenue management, rate management system,customer relations management, OTA, online travel agency, " />
<meta name="description" content="Hoteratus is a two-way online hotel channel manager for managing online sales channels easily, feeding all channels simultaneously, providing also the services of property management system, unlimited point of sale,rate management system, revenue management, front desk, customized websites, facebook booking button, booking engine, tripadvisor instant booking, guest review express, guests satisfaction survey and more " />
<meta name="og:title" content="Hoteratus- Hospitality Sotfware Solutions" />
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
<title><?php echo get_data(CONFIG)->row()->company_name;?> | <?php echo $page_heading;?> </title>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('animate.min.css', true);?>
<?php echo theme_fonts('font-awesome.css', true);?>
<?php echo theme_css('showhide.css', true);?>
<link rel="stylesheet" href="<?php echo base_url();?>user_assets/css/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/fixed-header.css">
<script src="<?php echo base_url();?>assets_pms/js/fix-header.js" type="text/javascript"></script>
</head>
<body>
<!-- <div id="preloader">
  <div id="status">&nbsp;</div>
</div> -->
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<header>
    <div class="main_menu">
      <div class="">
        <nav class="navbar navbar-default cbp-af-header">
          <div class="container"> 
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
              <a class="navbar-brand" href="<?php echo lang_url();?>" ><img src="<?php echo base_url();?>user_assets/images/logo.png" alt=""></a> </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav pull-right">
                <li <?php if(uri(2)=='' || uri(2)=='channel' && uri(3)!='our_links') { ?> class="active" <?php } ?>><a href="<?php echo lang_url();?>">Home  <span class="sr-only">(current)</span></a></li>
                <li <?php if(uri(4)=='features') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/features"> Features </a></li>
                <li <?php if(uri(4)=='multiproperty') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/multiproperty"> Prices   </a></li>
                <li <?php if(uri(4)=='contact_us') { ?>class="active" <?php }?>><a href="<?php echo lang_url()?>channel/our_links/contact_us"> Contact us </a></li>

                <?php $user_id = user_id(); 
                  if($user_id!=''){ ?>

              <li><a href="<?php echo lang_url(); ?>channel/dashboard"> Dashboard </a></li>

             <?php }else{ ?>
				
              <li class="display_none"><a href="#login" class="btn login_effect waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal"
              data-overlaySpeed="50" data-overlayColor="#000"><i class="fa fa-sign-in" aria-hidden="true"></i></a></li>

              <li class="display_none"><a href="#reg" class="btn reg_effect waves-effect waves-light m-r-5 m-b-10" data-animation="push" data-plugin="custommodal"
              data-overlaySpeed="200" data-overlayColor="#000"><i class="fa fa-user-plus" aria-hidden="true"></i></a></li> 

              <li class="display_none"><a href="#forget" class="btn forget_content waves-effect waves-light m-r-5 m-b-10" data-animation="push" data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#000"><i class="fa fa-user-plus" aria-hidden="true"></i></a></li> 


              <li><a href="javascript:;" class="login_effect_no" data-type="login"> <!--<i class="fa fa-sign-in" aria-hidden="true"></i>--> Sign in  </a></li>
             
              <li><a href="javascript:;" class="login_effect_no" data-type="register">  <!--<i class="fa reg_effect_no fa-user-plus" aria-hidden="true"></i>--> Sign Up</a></li>

              <?php } ?>

              </ul>
            </div>
            <!-- /.navbar-collapse --> 
          </div>
          <!-- /.container-fluid --> 
        </nav>
      </div>
      <div class="container">
        <div class="cls_top_banner_sec">
          <div class="row ">
            <div class="col-md-12 col-lg-12 col-sm-10">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php if(uri(4)!='about_us' && uri(4)!='features' && uri(4)!='contact_us' && uri(3)!='faq' && uri(4)!='terms' && uri(4)!='multiproperty' && uri(4)!='terms' && uri(4)!='privacy' && uri(4)!='cms_page') {  ?>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
        <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
        <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
      </ol>
      <div class="carousel-inner">

        <?php 
        $sliders = get_data('home_cms',array('type'=>1))->result();
        if($sliders){ 
        $i=1;          
            foreach($sliders as $slider){
                       ?>
          <div class="item <?php if($i==1){ echo 'active'; } ?>">
           <img src="<?php echo base_url(); ?>uploads/<?php echo $slider->image; ?>" alt="">
          <div class="carousel-caption">
          <div class="cls_buy_sell_bg">
          <?php echo $slider->title; ?></h4>
          <p><?php echo $slider->content; ?></p>
          </div>
          </div>
          </div> 
        
        <?php $i++; } } ?>

         <!--  <div class="item active">
           <img src="<?php echo base_url();?>user_assets/images/inner_banner.jpg" alt="">
          <div class="carousel-caption">
          <div class="cls_buy_sell_bg">
          <h4>Reputation  <span> Management System </span></h4>
          <p>Allows properties to quickly interact with the customers and provide exceeded expectations.</p>
          </div>
          </div>
          </div> -->


      </div>
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="fa fa-long-arrow-left" aria-hidden="true"> </span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="fa fa-long-arrow-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
  <?php } ?>
  </header>
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

<!--login Modal -->
<div id="login" class="modal-demo modal-dialog modal_list modal-content">
	<button type="button" class="close login_close" onclick="Custombox.close();">
	<span>&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
	<hr>
	<div class="custom-modal-text">
  <div id="lock_cnt" class="error"></div>
		<form class="login-form  form_login" method="post" id="log_form" novalidate="novalidate">
		<div class="login_content">
		</div>		
		</form>
	</div>
</div>
<!--login Modal End -->

<!--Register Modal -->
<div id="reg" class="modal-demo modal-dialog modal_list modal-content">
	<button type="button" class="close reg_close" onclick="Custombox.close();">
	<span>&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
	<hr>
	<div class="custom-modal-text">
		<form class="register-form" id="register_form" method="post" novalidate="novalidate" style="display: block;">
		<div class="reg_content">
		</div>
		</form>
	</div>
</div>    
<!--register Modal End-->


<!--  forget password   -->

<div id="forget" class="modal-demo modal-dialog modal_list modal-content">
  <button type="button" class="close reg_close" onclick="Custombox.close();">
  <span>&times;</span><span class="sr-only">Close</span>
  </button>
  <h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
  <hr>
  <div class="custom-modal-text">
  <div class="modal-body" id="add_ss">
    <form class="register-form" id="forgetpassword" method="post" novalidate="novalidate" style="display: block;">
    <div id="forget_content">
    </div>
    </form>
    </div>
    <div class="modal-body" id="add_ss1" style="display:none">
    </div>
  </div>
</div>

<!--  forget password end -->
