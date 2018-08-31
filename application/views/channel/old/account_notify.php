<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>	Account Activate </title>
    <?php echo theme_css('bootstrap.min.css', true);?>
    <?php echo theme_css('animate.min.css', true);?>
    <?php echo theme_fonts('font-awesome.css', true);?>
    <?php echo theme_css('style.css', true);?>
 

</head>
<body class="login">
<div class="container">
  
<div class="row">
   <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4 mar-top">
   
   <div class="cls_login_form_c">
   <?php if($action=='deactive') {?>
   Sorry!!! Your account has been deactivated. Please Contact the Admin <strong><?php echo get_data(TBL_SITE,array('id'=>1))->row()->email_id;?></strong>.
   <?php } else { ?>
   Please activate your account or your new e-mail address, we have sent a verification link to <strong><?php echo $this->session->userdata('ch_user_mail');?></strong>
   <?php if($action=='notify') {?>
   <a href="<?php echo lang_url();?>channel/resend">Click </a> to send verification code again to your email!
   <?php } } ?>
   Kindly let us know if you need further assistance.
   </div>
   
</div>
  
  </div>
  </div>







    <!-- jQuery -->
    
<!-- end jQuery -->
</body>

</html>
