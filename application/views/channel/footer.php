<style type="text/css">
footer{
background: <?php echo $this->theme_customize['footer_one']!='' ? $this->theme_customize['footer_one']:'rgba(0, 0, 0, 0.5) none repeat scroll 0 0' ?>; 
}
</style>


<?php $footer_contnt = get_data('site_config',array('id'=>'1'))->row();?>
<footer>
<div class="container">
<div class="col-md-4 col-sm-4 col-xs-12">
<h3>About Us</h3>
<ul class="site-links">
<li><a href="<?php echo lang_url()?>channel/our_links/about_us">About</a></li>
<li><a href="<?php echo lang_url()?>channel/our_links/terms">Terms & Conditions</a></li>
<li><a href="<?php echo lang_url()?>channel/our_links/privacy">Privacy Policy</a></li>
<li><a href="<?php echo lang_url()?>channel/our_links/contact_us">Contact</a></li>
<li><a href="<?php echo lang_url()?>channel/faq/view">FAQ</a></li> 
</ul>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<h3>More</h3>
<ul class="site-links">
<?php
if(count($this->dynamic_cms)!=0)
{
	foreach($this->dynamic_cms as $cms_value)
	{
		?>
		<li><a href="<?php echo lang_url().'channel/cms/cms_page/'.secure($cms_value['id']);?>"><?php echo $cms_value['title'];?></a></li> 
		<?php
	}
}
else
{
?>
	<li><a href="javascript:;">No more CMS available</a></li>
<?php
}
?>

</ul>
</div>

<div class="col-md-3 col-sm-3 col-xs-12">
<h3>Tweeter Feeds </h3>
          
<a class="twitter-timeline"  href="https://twitter.com/hoteratus" data-widget-id="651610092505165824">Tweets by @hoteratus</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>


</div>


<div class="clearfix" style="border-bottom: 3px solid #1b96aa;"></div>
<br>
<div class="col-md-6 col-sm-6 col-xs-12">
<h3>Need Assistance?</h3>
<ul class="location">
<li><i class="fa fa-phone" aria-hidden="true"></i>Phone : + <?php echo $footer_contnt->phno; ?></li>
<li><i class="fa fa-envelope" aria-hidden="true"></i><?php echo $footer_contnt->email_id; ?></li>

<li><i class="fa fa-globe" aria-hidden="true"></i><?php echo $footer_contnt->siteurl; ?></li>

<li><i class="fa fa-location-arrow " aria-hidden="true"></i><?php if($footer_contnt->address!=''){ echo $footer_contnt->address; } ?>,<?php if($footer_contnt->city!=''){ echo $footer_contnt->city; } ?>,<?php if($footer_contnt->state!=''){ echo $footer_contnt->state; } ?></li>
</ul>
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
<div class="social_f text-center">
  <ul>
	<li class="ic"><a title="Facebook" href="<?php echo $footer_contnt->facebook_url; ?>"><i class="fa fa-facebook"></i></a></li>
	<li class="ic"><a title="Twitter" href="<?php echo $footer_contnt->twitter_url; ?>"><i class="fa fa-twitter"></i></a></li>
	<li class="ic"><a title="Linedin" href="<?php echo $footer_contnt->linkedin; ?>"><i class="fa fa-linkedin"></i></a></li>
	<li class="ic"><a title="Google plus" href="<?php echo $footer_contnt->google; ?>"><i class="fa fa-google-plus"></i></a></li>
  </ul>
</div>
</div>
</div>
</footer>
<div class="main-foot">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<p class=" pull-left">Hoteratus - Hospitality Software Solutions . &copy; 2017 All rights reserved</p> <!--<p class="pull-right"><span id="siteseal"><script async="" type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=OR5YY6nVpnA8BHdAzIjeetTj7f8kFwVGkb28obR7CMe7n9iA0eV5WuvuV3aL"></script><img style="cursor:pointer;cursor:hand" src="https://seal.godaddy.com/images/3/en/siteseal_gd_3_h_l_m.gif" onclick="verifySeal();" alt="SSL site seal - click to verify"></span>
</p>--></div>
</div>
</div>
</div>          
<?php echo theme_js('jquery.min.js', true);?>
<?php echo theme_js('light-box.js', true);?>
<?php echo theme_js('light-box2.js', true);?>
<?php echo theme_js('bootstrap.min.js', true);?>
<?php echo theme_js('jquery.validate.js', true);?>
<?php echo theme_js('jquery.fittext.js', true);?>
<?php echo theme_js('wow.min.js', true);?>
<?php echo theme_js('creative.js', true);?>
<?php echo theme_js('jquery-ui.min.js', true);?>
<?php echo theme_js('paging.js', true);?>
<?php echo theme_js('jquery.pages.js', true);?>
<?php echo theme_js('pre3.js', true);?> 

<?php echo theme_js('boots-select.js', true);?>
<?php echo theme_js('text-editor2.js', true);?>
<?php echo theme_js('circle.js', true);?>
<?php echo theme_js('textbox-check2.js', true);?>
<?php echo theme_js('custom-js-ver2.js', true);?>
<?php echo theme_js('upload.js', true);?>
<?php echo theme_js('ckeditor/ckeditor.js', true);?>
<?php //echo theme_js('show-hide2.js', true);?>
<?php echo theme_js('bootstrap-editable.min.js', true);?>
<?php echo theme_js('moment-with-locales.js', true);?>
<?php echo theme_js('bootstrap-datetimepicker.js', true);?>
<link href="<?php echo base_url();?>user_assets/custombox/dist/custombox.min.css" rel="stylesheet">
<script>
var resizefunc = [];
</script>
<script src="<?php echo base_url();?>user_assets/custombox/fastclick.js"></script>
<script src="<?php echo base_url();?>user_assets/custombox/dist/custombox.min.js"></script>
<script src="<?php echo base_url();?>user_assets/custombox/jquery.core.js"></script>
<script src="<?php echo base_url();?>user_assets/custombox/jquery.app.js"></script>

 <script>
// makes sure the whole site is loaded
jQuery(window).load(function() {
        // will first fade out the loading animation
  //jQuery("#status").fadeOut();
        // will fade out the whole DIV that covers the website.
  //jQuery("#preloader").delay(1000).fadeOut("slow");
  jQuery("#preloader").fadeOut("slow");
})
</script>

<script type="text/javascript">
jQuery(function ($) {
    var $active = $('#accordion .panel-collapse.in').prev().addClass('active');
    $active.find('a').prepend('<i class="fa fa-minus"></i>');
    $('#accordion .panel-heading').not($active).find('a').prepend('<i class="fa fa-plus"></i>');
    $('#accordion').on('show.bs.collapse', function (e) {
        $('#accordion .panel-heading.active').removeClass('active').find('.fa').toggleClass('fa-plus fa-minus');
        $(e.target).prev().addClass('active').find('.fa').toggleClass('fa-plus fa-minus');
    })
});
</script>

<?php echo theme_js('jquery.creditCardValidator.js', true);?>
<?php echo theme_js('channel_helper.js', true);?>


<!-- Account Activate-->
<div class="modal fade dialog-2 " id="m_active" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b" id="myModalLabel"></h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
    Thank you! Your Account is activated! If you want to log into your account, you can do it in section login to the site
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
<div class="modal fade dialog-2 " id="a_active" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b" id="myModalLabel"></h4>
      </div>
      <div class="modal-body sign-up-m">
    Sorry! Your activation id invalid or already activated!
      </div>
    </div>
  </div>
</div>


</body>

</html>



 
 