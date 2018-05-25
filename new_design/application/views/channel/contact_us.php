
<header>
 <div class="main_menu inner_menu">
      <div class="">
        <nav class="navbar navbar-default cbp-af-header">
          <div class="container"> 
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
              <a class="navbar-brand" href="<?php echo lang_url();?>"><img src="<?php echo base_url()?>user_assets/images/logo.png" alt=""></a> </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
             <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav pull-right">
                <li <?php if(uri(2)=='' || uri(2)=='channel' && uri(3)!='our_links') { ?> class="active" <?php } ?>><a href="<?php echo lang_url();?>">Home  <span class="sr-only">(current)</span></a></li>
                <li <?php if(uri(4)=='features') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/features"> Features </a></li>
                <li <?php if(uri(4)=='multiproperty') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/multiproperty"> Multiproperty   </a></li>
                <li <?php if(uri(4)=='contact_us') { ?>class="active" <?php }?>><a href="<?php echo lang_url()?>channel/our_links/contact_us"> Contact us </a></li>
                <li><a href="#" data-toggle="modal" data-target="#login"> <i class="fa fa-sign-in" aria-hidden="true"></i> </a></li>
                <li><a href="#" data-toggle="modal" data-target="#reg">  <i class="fa fa-user-plus" aria-hidden="true"></i></a></li>

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
</header>

<div class="banner_inner">
<div class="container">
			<h2>contact Us</h2>
			<label></label>
			<div class="clearfix"></div>
</div>
</div>

<div class="about_cont">
<div class="container">
<div class="row">
<div class="col-md-8 col-sm-8">
<div class="cont_cont">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore 
et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut 
aliquip ex ea commodo consequat ut aliquip ex ea commodo consequat.</p>
    
     <div class="">
        <form class="form-horizontal">
        
            <div class="form_type reg_type">
            <div class="col-md-6"><input class="form-control lock" placeholder="Name" type="tex"></div>     <div class="col-md-6"><input class="form-control lock" placeholder="Email Id" type="tex"></div>  
            </div>
            
             <div class="form_type reg_type">
            <div class="col-md-12"> 
           <textarea class="form-control text_contact" placeholder="your message"></textarea>
            </div>
            </div> 
            
            

            <div class="clearfix">
            </div>
            
            <div class="col-md-12">
            <button class="btn btn_login" style="width:100%;"> send message</button>
			</div>	
            <div class="clearfix">
            </div>
            
              <div class="clearfix"> </div>
        </form>
    </div>
        </div>
		  </div>
    
    <div class="col-md-4 col-sm-4 ">
    <div class="head_conta">
    <h4>Contact Us</h4>
    </div> 
         <table class="table tab_list_cont">
<tbody><tr>
<td>Address  </td>
<td>   Lorem ipsum dolor sit amet, <br>
consectetur, adipiscing elit</td>
</tr>
<tr>
<td>Phone  </td>
<td> +00 9876543210</td>
</tr>
<tr>
<td>Email </td>
<td> info@companyname.com, <br>  support@companyname.com</td>
</tr>
<tr>
<td> </td>
<td>

</td>
</tr>
</tbody></table> 
        
         <div class="head_conta">
        <h4>Follow with us</h4>
        </div>    
    <div class="social_f">
              <ul>
                <li class="ic"><a title="Facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                <li class="ic"><a title="Twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                <li class="ic"><a title="Linedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                <li class="ic"><a title="Google plus" href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
        
        
    </div>  
    </div>
</div>
</div>
   