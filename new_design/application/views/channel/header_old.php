<!DOCTYPE html>
<html lang="en">
<head> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="Channel manager, Hotel channel manager, hotel reservation system, global distribution system, the best channel manager, distribution channel manager" />
<meta name="description" content="Unlimited Luxury Villas is a two-way online hotel channel manager for managing online sales channels easily, feeding all channels simultaneously!" />
<meta name="og:title" content="Unlimited Luxury Villas - Global Distribution system - Channel Manager" />
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
<title>Unlimited Luxury Villas - Channel Manager - <?php echo $page_heading;?> </title>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('animate.min.css', true);?>
<?php echo theme_fonts('font-awesome.css', true);?>
<?php echo theme_css('showhide.css', true);?>
<link rel="stylesheet" href="<?php echo base_url();?>user_assets/css/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/fixed-header.css">
<script src="<?php echo base_url();?>assets_pms/js/fix-header.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
var stickyNavTop = $('.navss').offset().top;
var stickyNav = function(){
var scrollTop = $(window).scrollTop(); 
if (scrollTop > stickyNavTop) { 
$('.navss').addClass('sticky');
} else {
$('.navss').removeClass('sticky'); 
}
};
stickyNav();
$(window).scroll(function() {
stickyNav();
});
});
</script>
<style>
.top{
	background:<?php echo $this->theme_customize['h_header_one']!='' ? $this->theme_customize['h_header_one']:'#07204d' ?>;
}
.top2{
	background:<?php echo $this->theme_customize['h_header_two']!='' ? $this->theme_customize['h_header_two']:'' ?>;
}
.f-social li a:before {
	background:<?php echo $this->theme_customize['h_header_one']!='' ? $this->theme_customize['h_header_one']:'#07204d' ?>;
	color: #fff;
	width: 38px;
	height: 38px;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	display: table;
	line-height: 38px;
	text-align: center;
	transition: .4s;
}
.cls_boc_cont{
	background-color:<?php echo $this->theme_customize['our_features']!='' ? $this->theme_customize['our_features']:'#073383' ?>;
    border-width: 0;
    box-shadow: 1px 1px 8px #d2d2cf;
    padding: 54px 35px 44.3618px;
	  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  overflow:hidden;
  position:relative;
}

.trial{
	padding-top:25px;
	padding-bottom:25px;
	background:<?php echo $this->theme_customize['free_trial']!='' ? $this->theme_customize['free_trial']:'#073383' ?> url(../images/bg_acc.jpg) no-repeat scroll 0 0/cover;
	
}

.foot{
	background-color:<?php echo $this->theme_customize['footer_one']!='' ? $this->theme_customize['footer_one']:'#000712' ?> ;
	background-position:top center;
	background-repeat:no-repeat;
	padding-top:15px;
	padding-bottom:15px;
}

.main-foot{
	background:<?php echo $this->theme_customize['footer_two']!='' ? $this->theme_customize['footer_two']:'#1253a3' ?> none repeat scroll 0 0;
	padding-top:15px;
	padding-bottom:15px;
}

.foot .f-social li a::before {
    background: <?php echo $this->theme_customize['footer_one']!='' ? $this->theme_customize['footer_one']:'#fb4b6f' ?> none repeat scroll 0 0;
    color: #ffffff;
    display: table;
    height: 38px;
    left: 0;
    line-height: 38px;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    transition: all 0.4s ease 0s;
    width: 38px;
}


</style>
</head>
<body>
<header>
<div class="top">
<div class="container">
<div class="row">
<div class="col-md-6 col-sm-6">
<ul class="f-social">
<?php $footer_contnt = get_data('site_config',array('id'=>'1'))->row();?>
<li><a href="<?php echo $footer_contnt->facebook_url;?>" class="fa fa-facebook"></a></li>
<li><a href="<?php echo $footer_contnt->twitter_url;?>" class="fa fa-twitter"></a></li>
<li><a href="<?php echo $footer_contnt->google;?>" class="fa fa-google-plus"></a></li>
<li><a href="<?php echo $footer_contnt->pinterest;?>" class="fa fa-pinterest"></a></li>
<li><a href="<?php echo $footer_contnt->skype;?>" class="fa fa-skype"></a></li>
<li><a href="<?php echo $footer_contnt->youtube;?>" class="fa fa-youtube"></a></li>
</ul>
</div>

<div class="col-md-6 col-sm-6 pull-right">
<ul class="list-inline pull-right ss">
<?php if(user_id()=='') { ?>
	<li class="s1"><a href="<?php echo base_url(); ?>channel/login" class="btn btn-success hvr-sweep-to-right">Signup / Login</a></li>
	<!--<li class="s2"><a href="<?php echo base_url(); ?>channel/login" class="btn btn-success hvr-sweep-to-right">Login</a></li>-->
<?php }else { ?>
	<li class="s1"><a href="<?php echo base_url();?>channel/dashboard" class="btn btn-primary hvr-sweep-to-right">Dashboard</a></li>
	<li class="s2 tlClogo"><a href="<?php echo base_url();?>channel/logout" class="btn btn-success hvr-sweep-to-right">Logout</a></li>
<?php } ?>
</ul>
</div>

</div>
</div>
</div>
<input type="hidden" value="<?php echo site_url().$this->lang->lang();?>" id="base_url" name="base_url">
<?php if(uri(3)=='confirm'){?>
<input type="hidden" value="<?php echo $confirm;?>" id="active">
<?php } else { ?>
<input type="hidden" id="active">
<?php } ?>
<input type="hidden" value="<?php echo user_id();?>" name="user_id" id="user_id">
<input type="hidden" value="<?php echo hotel_id();?>" name="hotel_id" id="hotel_id">

<div class="container">
<div class="top2">
<div class="row">
<div class="col-md-2 col-sm-2">
<?php $site_logo = get_data(CONFIG,array('id'=>1))->row()->site_logo; ?>
<a href="<?php echo base_url();?>" title="Go to website">
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$site_logo));?>" class="img img-responsive"> 
</a>
</div>
<div class="col-md-10 col-sm-10 pull-right button">
<nav class="navbar navbar-default">
  <div class="">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse pad-no" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav pull-right">
        <li <?php if(uri(2)=='' || uri(2)=='channel' && uri(3)!='our_links') { ?> class="active" <?php } ?>><a href="<?php echo base_url();?>">Home</span></a></li>
     
        <li <?php if(uri(4)=='features') { ?>class="active" <?php }?>><a href="<?php echo base_url();?>channel/our_links/features">Features</a></li>
		
        <li <?php if(uri(4)=='multiproperty') { ?>class="active" <?php }?>><a href="<?php echo base_url();?>channel/our_links/multiproperty">Multiproperty</a></li>
		<?php 
		if(count($this->dynamic_cms)!=0)
		{
			foreach($this->dynamic_cms as $cms_value)
			{
				?>
				<li <?php //if(uri(1)=='' || uri(1)=='channel' && uri(2)!='our_links') { ?> <?php //} ?>><a href="<?php echo base_url().'channel/cms/cms_page/'.secure($cms_value['id']);?>"><?php echo $cms_value['title'];?></span></a></li>
				<?php
			}
		}
		?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>
</div>
</div>
</div>
<?php if(uri(2)=='' || uri(2)=='channel' && uri(3)!='our_links' && uri(3)!='faq' && uri(4)!='cms_page') { ?>

<div class="banner">
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
  <?php 
  		$home_silder = get_data('home_cms',array('type'=>'1'))->result_array();
		$i=0;
		foreach($home_silder as $val)
		{
			extract($val);
			
	?>
    <div class="item <?php if($i==0){?>active <?php } ?>">
      <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('uploads/'.$image));?>" alt="..." style="width:100% !important">
      <div class="carousel-caption col-md-8">
          <h3><?php echo $title?></h3>
        <?php echo $content;?>
      </div>
    </div>
    <?php $i++;} ?>
  </div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>
<?php } ?>
</header>