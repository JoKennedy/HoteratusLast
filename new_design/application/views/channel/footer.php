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
 

</ul></div>

<div class="col-md-4 col-sm-4 col-xs-12">
<h3>More</h3>
<ul class="site-links">
 <li><a href="#">CMS 1</a></li>
 <li><a href="#">CMS 2</a></li>
 <li><a href="#">CMS 3</a></li>
</ul></div>

<div class="col-md-4 col-sm-4 col-xs-12">
<h3>Newsletter</h3>

<p>Join our newsletter to keep updated.</p>

<form>
  <div class="form-group">
    <input class="form-control" id="" placeholder="Enter your E-mail" type="text">
  </div>
   <div class="form-group">
  <button class="foot_btn">Sign Up Now</button>
  </div>
  
  
  </form>

</div>


<div class="clearfix"></div>

<hr>

<div class="col-md-6 col-sm-6 col-xs-12">
<h3>Need Assistance?</h3>
<ul class="location">
<li><i class="fa fa-phone" aria-hidden="true"></i>Phone : + 5719347603</li>
<li><i class="fa fa-envelope" aria-hidden="true"></i>info@hoteratus.com</li>

<li><i class="fa fa-globe" aria-hidden="true"></i>www.hoteratus.com</li>

<li><i class="fa fa-location-arrow " aria-hidden="true"></i>2170 Gaborone Place, Dulles, Virginia United States</li>
</ul>
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
<div class="social_f text-center">
              <ul>
                <li class="ic"><a title="Facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                <li class="ic"><a title="Twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                <li class="ic"><a title="Linedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                <li class="ic"><a title="Google plus" href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
            </div>



</div>
</footer>
<div class="main-foot">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<p class=" pull-left">Hoteratus - Hospitality Software Solutions . &copy; 2017 All rights reserved</p> <p class="pull-right"><span id="siteseal"><script async="" type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=OR5YY6nVpnA8BHdAzIjeetTj7f8kFwVGkb28obR7CMe7n9iA0eV5WuvuV3aL"></script><img style="cursor:pointer;cursor:hand" src="https://seal.godaddy.com/images/3/en/siteseal_gd_3_h_l_m.gif" onclick="verifySeal();" alt="SSL site seal - click to verify"></span>
</p></div>
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
<?php echo theme_js('jquery.creditCardValidator.js', true);?>
<?php echo theme_js('channel_helper.js', true);?>
<?php echo theme_js('boots-select.js', true);?>
<?php echo theme_js('text-editor2.js', true);?>
<?php echo theme_js('circle.js', true);?>
<?php echo theme_js('textbox-check2.js', true);?>
<?php echo theme_js('custom-js-ver2.js', true);?>
<?php echo theme_js('upload.js', true);?>
<?php echo theme_js('ckeditor/ckeditor.js', true);?>
<?php echo theme_js('show-hide2.js', true);?>
<?php echo theme_js('bootstrap-editable.min.js', true);?>


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
 <script>
// makes sure the whole site is loaded
jQuery(window).load(function() {
        // will first fade out the loading animation
	jQuery("#status").fadeOut();
        // will fade out the whole DIV that covers the website.
	jQuery("#preloader").delay(1000).fadeOut("slow");
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

</body>

</html>



 
 