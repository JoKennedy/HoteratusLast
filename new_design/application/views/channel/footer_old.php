<?php $footer_contnt = get_data('site_config',array('id'=>'1'))->row();?>


<footer>
<div class="foot">
<div class="container">
<div class="row">
<div class="col-md-3 col-sm-3">
<h4>where to find us</h4>
<div class="foots"><span></span></div>
<div class="clearfix"></div>

<div class="icon-fn icon-fn2"><i class="fa fa-phone"></i><span>Phone : + <?php echo $footer_contnt->phno;?></span></div>
<br>


<div class="icon-fn icon-fn3"><i class="fa fa-envelope"></i><span>Email :  <a href="mailto:<?php echo $footer_contnt->email_id?>" class="eset"><?php echo $footer_contnt->email_id?></a></span></div>
<br>

<div class="icon-fn"><i class="fa fa-globe"></i><span><a href="<?php echo $footer_contnt->siteurl;?>" class="eset"><?php echo $footer_contnt->siteurl;?></a></span></div>

<div class="icon-fn icon-fn2 icon-fnk"><i class="fa fa-location-arrow pull-left"></i><span><?php echo $footer_contnt->address.', '.$footer_contnt->city.', '.$footer_contnt->state.' '.get_data('country',array('id'=>$footer_contnt->country))->row()->country_name;?></span></div>

</div>
<div class="col-md-3 col-sm-3">
<h4>Tweeter feeds</h4>

<div class="foots"><span></span></div>
<div class="clearfix"></div>
<div class="row padding-top-25">
           
<a class="twitter-timeline"  href="https://twitter.com/hoteratus" data-widget-id="651610092505165824">Tweets by @hoteratus</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          
</div>   
</div>

<div class="col-md-3 col-sm-3">
<h4>Our Links</h4>
<div class="foots"><span></span></div>
<div class="clearfix"></div>

<ul class="icon-fn2 list-unstyled set">
<li><i class="fa fa-circle"></i><a href="<?php echo lang_url();?>channel/our_links/about_us" class="eset">About us</a></li>
<li><i class="fa fa-circle"></i><a href="<?php echo lang_url();?>channel/our_links/terms" class="eset">Terms &amp; Conditions</a></li>
<li><i class="fa fa-circle"></i><a href="<?php echo lang_url();?>channel/our_links/privacy" class="eset">Privacy Policy</a></li>
<li><i class="fa fa-circle"></i><a href="<?php echo lang_url();?>channel/our_links/contact_us" class="eset">Contact</a></li>
<li><i class="fa fa-circle"></i><a href="<?php echo lang_url();?>channel/faq/view" class="eset">FAQ</a></li>
</ul>

</div>
<div class="col-md-3 col-sm-3">
<h4>Connect with us</h4>
<div class="foots"><span></span></div>
<div class="clearfix"></div>
<div class="icon-fn-2"></div>
<ul class="f-social cls_new_top">
	<li><a href="<?php echo $footer_contnt->twitter_url;?>" class="fa fa-twitter"></a></li>
	<li><a href="<?php echo $footer_contnt->facebook_url;?>" class="fa fa-facebook"></a></li>
	<li><a href="<?php echo $footer_contnt->pinterest;?>" class="fa fa-pinterest"></a></li>
	<li><a href="<?php echo $footer_contnt->google;?>" class="fa fa-google-plus"></a></li>
	<li><a href="<?php echo $footer_contnt->skype;?>" class="fa fa-skype"></a></li>
	<li><a href="<?php echo $footer_contnt->youtube;?>" class="fa fa-youtube"></a></li>
</ul>
</div>
</div>
</div>
</div>

<div class="main-foot">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<p class="text-center"><?php echo $footer_contnt->company_name?>. © 2016 All rights reserved</p>
</div>
</div>
</div>
</div>
</footer>
          
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
</body>

</html>