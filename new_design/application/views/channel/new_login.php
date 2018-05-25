<!DOCTYPE html>
<?php $footer_contnt = get_data('site_config',array('id'=>'1'))->row();?>

<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

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
        <meta charset="utf-8" />
        <title>User Login | Hoteratus - Hospitality Software Solutions</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		 <?php echo theme_fonts('font-awesome.css', true);?>
		<link href="<?php echo base_url(); ?>user_assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />  

		<link href="<?php echo base_url(); ?>user_assets/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
		
		<!--<link rel="shortcut icon" href="<?php //echo base_url(); ?>uploads/logo/favicon.ico"> -->

		</head>

		<link href="<?php echo base_url(); ?>user_assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />  

		<link href="<?php echo base_url(); ?>user_assets/css/uniform.default.css" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo base_url(); ?>user_assets/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo base_url(); ?>user_assets/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
		<link href="<?php echo base_url(); ?>user_assets/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>user_assets/css/login-5.min.css" rel="stylesheet" type="text/css" />


		<link href="<?php echo base_url(); ?>user_assets/css/select2.min.css" rel="stylesheet" type="text/css" />

		<link href="<?php echo base_url(); ?>user_assets/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
		<style>
		.customErrorClass
		{
			border: 2px solid #f00 !important;
		}
		.error
		{
			color: red !important;
		}
		</style>
		
       
    <!-- END HEAD -->
<input type="hidden" value="<?php echo site_url().$this->lang->lang();?>/" id="base_url" name="base_url">
<input type="hidden" value="" name="user_id" id="user_id">
<input type="hidden" value="" name="hotel_id" id="hotel_id">
<input type="hidden" value="" id="active">
    <body class="trial">
        <!-- BEGIN : LOGIN PAGE 5-1 -->
        <div class="user-login-5" style="background-image:url(data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/wide4.jpg"));?>)">
            <div class="row bs-reset" style="position:relative;">
                <div class="col-md-12 bs-reset">
                <!--<div class="" style=" background: rgba(95, 175, 5, 0.5) none repeat scroll 0 0;
    color: #fff;
    font-size: 32px;
    left: 20%;
    padding: 10px 40px;
    position: absolute;
    text-transform: capitalize;
    top:300px;
    width: 23%;
    z-index: 999;"> hotel    </div>--> <!--<div class="" style=" background: rgba(16, 188, 211, 0.5) none repeat scroll 0 0;
    color: #fff;
    font-size: 32px;
    left: 43%;
    padding: 10px 35px;
    position: absolute;
    text-transform: capitalize;
    top: 300px;
    width: 32%;
    z-index: 999;">avalibilities </div>-->
               <div class="login-bg" >
               
                <?php $site_logo = get_data(CONFIG,array('id'=>1))->row()->site_logo; ?>
						<a href="<?php echo lang_url();?>" title="Go to website" class="text-center">
						<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$site_logo));?>" class="login-logo" title="Go to website"> 
						</a> 
                        
                        <Div class="col-md-3 col-sm-3"> </Div>
                        <div class="col-md-6  col-sm-6 login-container bs-reset col-md-offset-3">
                    <div class="login-content">
                      
						
		<form action="javascript:;" class="login-form" method="post" id="log_form" style="margin-top:35%;">
        <div class="login-text">  
					  <h1>Hoteratus - Hospitality Software Solutions</h1>
                        <p> Please enter your details to access Hoteratus - Hospitality Software Solutions. If you don't have an account yet, you can click the link to create one </p></div>
			<div class="row">
				<div class="col-xs-6">
                <p>User Name :</p>
					<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="login_email" id="login_email" required/> </div>
				<div class="col-xs-6">
                                <p>Password :</p>

					<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="login_pwd" id="login_pwd" required/> </div>
			</div>
			<div class="alert alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				<span>Enter any username and password. </span>
			</div>
			<div class="row">
				<div class="col-sm-6">
				<div class="rem-password">
					<p> 	<input type="checkbox" id="remember_me" class="rem-checkbox" /> Remember Me
					
					</p>
				</div>
				</div>
			<div class="col-sm-6">
            
            <div class="forgot-password">
					<a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
			</div>
            
            </div>
            </div>
            
            <div class="row">
            
            
			<div class="col-sm-6" style="margin-top:10px;"><p><a href="javascript:;" id="register-btn" class="uppercase">Create an account</a></p>
            				

								</div>

               
                <div class="col-sm-6 text-right">
				<button class="btn blue" type="submit" id="logg">Sign In</button>
			</div>
			</div>
		</form>
		
						
                        <!-- BEGIN FORGOT PASSWORD FORM -->
                        <form class="forget-form" action="javascript:;" method="post" id="forgetpassword" style="margin-top:35%;">
                            <h3 class="font-green" style="padding-top:15px;">Forgot Password ?</h3>
                            <p class="error"> Wrong username or password, if you don't remember you access data enter you e-mail to take it back.  </p>
							<div id="add_ss1"></div>
                            <div class="form-group">
                                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" id="forget_email" name="forget_email" /> </div>
                            <div class="form-actions">
							    
                                <button type="button" id="back-btn" class="btn grey btn-default">Back</button>  

                                <button type="submit" class="btn blue btn-success uppercase pull-right" id="forgg">Submit</button>
                            </div>
                        </form>
						<!-- END FORGOT PASSWORD FORM -->
                    </div>
					
					<!-- register form   -->
 <form class="register-form" action="<?php echo lang_url();?>channel/basics/UserRegister" id="register_form" method="post">
	<h3 class="font-green">Sign Up</h3>
	<p class="hint"> Enter your personal details below: </p>
		<div class="row">
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">First Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="First Name" id ="fname" name="fname" required/> 
			</div>
			
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Last Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Last Name" id="lname" name="lname" required/> 
			</div> 
		</div>
		<div class="row">
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Property Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Property Name" id="property_name" name="property_name" required/> 
			</div>
			
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Phone</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="+449842080276" name="mobile" id="mobile" required/> 
			</div> 
		</div>
		
	<!--	<div class="row">
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Property Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Property Name" id="property_name" name="property_name" required/> 
			</div>
			
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Phone</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Phone" name="mobile" id="mobile" required/> 
			</div> 
		</div>  -->
		
		<div class="row">
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">City</label>
				<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="City" name="town" id="town" required/>
			</div>
						
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">Country</label>
								
				<select class="form-control" name="country">
				<?php $country = get_data('country')->result_array();
				foreach($country as $value) { 
				extract($value); ?>
				<option value="<?php echo $id;?>"><?php echo $country_name;?></option>
				<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">Website</label>
				<input class="form-control form-control-solid placeholder-no-fix" type="url" autocomplete="off" placeholder="http://www.domain.com" id="web_site" name="web_site"/> 
			</div>
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">E-mail</label>
				<input class="form-control form-control-solid placeholder-no-fix" type="email" autocomplete="off" placeholder="E-mail" id="registeremail" name="email_address" required/> 
			</div> 
		</div>
		<p class="hint"> Enter your preferred account details below: </p>
		
        <div class="row">
		<div class="form-group col-md-6">
		<label class="control-label visible-ie8 visible-ie9">Username</label>
		<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="user_name" id="username" /> 
		</div>
		
		<div class="form-group col-md-6 col-sm-6">
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" /> 
		</div>
        </div>
         <div class="row">
        
        <div class="form-group col-md-6 col-sm-6">
		<label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
		<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="cpassword" id="cpassword">
		</div>
        <div class="form-group col-md-6 col-sm-6">
          <script src='https://www.google.com/recaptcha/api.js'></script>
      	  	<div class="g-recaptcha" data-sitekey="<?php echo get_data(CONFIG)->row()->Site_key;?>"></div>
        </div>

        
        </div>
		
		<!--<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">Username</label>
		<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="user_name" id="username" /> 
		</div>
		
		<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" /> 
		</div>  -->


		
		 <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="checkbox" id="c2" name="accept" class="accept-n"/> &nbsp;&nbsp;
    I Accept <a href="<?php echo lang_url();?>channel/our_links/terms">The Terms </a> and <a href="<?php echo lang_url();?>channel/our_links/privacy">Privacy Policy</a>
    </div>
    <div class="col-md-6 col-sm-6">
  
    </div>
    </div>  
<!--<div class="clearfix"> 
		<div class="form-group">
        <div class="pull-left col-md-6">
			<label >
			
			 <input type="checkbox" id="c2" name="accept" class="accept-n"/>
             <p> I Accept <a href="<?php //echo base_url();?>channel/our_links/terms">The Terms </a> and <a href="<?php //echo base_url();?>channel/our_links/privacy">Privacy Policy</a></p>

			</label>
            </div>
            <div class="pull-right col-md-6">
            
            </div>
            </div>
		</div>-->

		
        
        		<div class="form-actions">
		<button type="button" id="back_btn" class="btn grey btn-default">Back</button>  
		
		<button type="submit" id="reg" class="btn btn-success uppercase pull-right">Submit</button>
		</div>
</form>
                        
	<div class="login-footer">
		<div class="row bs-reset">
			<div class="col-xs-4 bs-reset">
				<ul class="login-social">
					<li>
						<a href="<?php echo $footer_contnt->facebook_url;?>">
							<i class="icon-social-facebook"></i>
						</a>
					</li>
					<li>
						<a href="<?php echo $footer_contnt->twitter_url;?>">
							<i class="icon-social-twitter"></i>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="icon-social-dribbble"></i>
						</a>
					</li>
				</ul>
			</div>
			<div class="col-xs-8 bs-reset">
				<div class="login-copyright text-right">
					<p>Unlimited Luxury Villas Signup/Login</p>
				</div>
			</div>
		</div>
	</div>
</div>
			   </div>
			   
                </div>
                
</div>
</div>
        <!-- END : LOGIN PAGE 5-1 -->
		
        <!--[if lt IE 9]>
		
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 

<![endif]-->

        <!-- BEGIN CORE PLUGINS -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/app.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/jquery.uniform.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/jquery.validate.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/additional-methods.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/select2.full.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>user_assets/js/jquery.backstretch.min.js" type="text/javascript"></script>


<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js" type="text/javascript"></script> 
		
		
		
<script>
$(document).ready(function(){
	
$.validator.addMethod('positiveNumber',
	function(value) {
		return Number(value) > 0;
	}, 'Enter a positive number.');
	
jQuery.validator.addMethod("lettersonly", 
function(value, element) {
 return this.optional(element) || /^[a-z,""]+$/i.test(value);
}, "Letters only please");

jQuery.validator.addMethod("lettersonly2", 
function(value, element) {
 return this.optional(element) || /^[a-z," "]+$/i.test(value);
}, "Letters only please");

$.validator.addMethod("customemail", 
		function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, 
		"Sorry, I've enabled very strict email validation"
	);
	
$('#log_form').validate({
	rules :
	{
		login_email: 
		{
			required: true,
		},
		login_pwd :
		{
			required: true,
		}
},

errorPlacement: function(){
	return false;  // suppresses error message text
	},
highlight: function (element) { // hightlight error inputs
		$(element)
			.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
		$(element)
			.closest('.form-control').addClass('customErrorClass');
	},
unhighlight: function (element) { // revert the change done by hightlight
		$(element)
			.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
		$(element)
			.closest('.form-control').removeClass('customErrorClass');
	},
submitHandler: function(form)
{
	var data=$("#log_form").serialize();
	//alert(data);
	$('#logg').css('cursor', 'pointer');
	$("#logg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
	$.ajax({
	type: "POST",
	url:  base_url+'channel/basics/Login',
	data: data,
	success: function(html)
	{
	  if(html==0)
	  {
		$('#log_form').trigger("reset");
		$("#logg").html('Sign In');
		//$("#myModal").modal("hide");
		$('#forget-password').trigger('click');
		//$('#myModal2').modal({backdrop: 'static',keyboard: false});	   
	  }
	  else if(html==1)
	  {
		 // $("#myModal").modal("hide"); 
		 document.location=base_url+"channel/dashboard";
	  }
	  else if(html==2)
	  {
		  document.location=base_url+"channel/account_notify/deactive";
	  }
	  else if(html==3)
	  {
		  document.location=base_url+"channel/account_notify";
	  }	
   }
});
}
});

$('#forgetpassword').validate({
	rules :
	{
		forget_email: 
		{
			required: true,
			email:true,
			 remote: {
						url: base_url+"channel/forget_email_exists",
						type: "post",
						data: {
								forget_email: function()
								{
									return $("#forget_email").val();
								}
							  }
					  },
			
		},
	},
	
	/* errorPlacement: function(){
	return false;  // suppresses error message text
	}, */
	messages : 
			{
				forget_email:{ remote :'Enter the valid email address.'}
			},
/* highlight: function (element) { // hightlight error inputs
		$(element)
			.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
		$(element)
			.closest('.form-control').addClass('customErrorClass');
	},
unhighlight: function (element) { // revert the change done by hightlight
		$(element)
			.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
		$(element)
			.closest('.form-control').removeClass('customErrorClass');
	}, */
	submitHandler: function(form)
	{
	var data=$("#forgetpassword").serialize();
	//alert(data);
	$('#forgg').css('cursor', 'pointer');
	$("#forgg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
	$.ajax({
	type: "POST",
	url: base_url+'channel/basics/ForgetPassword',
	data: data,
	success: function(html)
	{
		$("#forgg").html('Send');
		$('#forgetpassword').trigger("reset");
	  if(html==1)
	  {
		$('#add_ss1').css('display','block');
		$('#add_ss').css('display','none');
		$('#add_ss1').html('<div class="alert alert-success" id="login_error">Your password has been send to your email. </div>');
	  }
	  else if(html=2)
	  {
		$('#add_ss1').css('display','block');
		$('#add_ss').css('display','none');
		  $('#add_ss1').html('<div class="alert alert-danger" id="login_error">Can not forget password. </div>');
	  }
	setTimeout(function()
	{
		$('#add_ss1').html('');
		$('#back-btn').trigger('click');
	},
	5000);
   }
});
}
});
});

$("#register_form").validate({

// Rulse start here	
	rules: {
			//account
			fname: {
				required: true,
				lettersonly2: true,
			},
			lname: {
				required: true,
				lettersonly: true,
			},
			address: {
				required: true
			},
			town: {
				required: true
			},
			country: {
				required: true
			},
			property_name: {
				required: true,
				remote: {
							url:"<?php echo lang_url();?>channel/register_property_exists",
							type: "post",
							data:{
									  username: function()
									  {
										  return $("#property_name").val();
									  }
								 } 
						},
				
			},
			user_name: {
						required: true,
						//lettersonly: true,
						//minlength:5,
						remote: {
											  url:"<?php echo lang_url();?>channel/register_username_exists",
											  type: "post",
											  data: {
													  username: function()
													  {
														  return $("#username").val();
													  }
													}
											 },
					},
			email_address: {
					required: true,
					customemail:true,
					remote: {
									url:"<?php echo lang_url();?>channel/register_email_exists",
									type: "post",
									data: {
											email: function()
											{ 
												return $("#registeremail").val(); 
											}
										  }
								}
				},
			mobile: {
						required: true,
						//number: true,
						minlength: 10,
						maxlength: 15,
						positiveNumber:true,
						remote: {
										url:"<?php echo lang_url();?>channel/register_phone_exists",
										type: "post",
										data: {
												email: function()
												{ 
													return $("#phone").val(); 
												}
											  }
								}
					},
			password : {required: true},
			cpassword : {required: true,equalTo:"#password"},
			web_site:{required: true,url: true},
			// captcha:{required: true,equalTo:"#except_captcha"},
			accept:{required: true},
			// currency:{required: true}
		},
		
	errorPlacement: function(){
	return false;  // suppresses error message text
	},
/*invalidHandler: function(form, validator){
		var body = $("html, body");
		body.animate({scrollTop:0}, '500', 'swing', function() { 
		})*/
		 
	highlight: function (element) { // hightlight error inputs
			$(element)
				.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
			$(element)
				.closest('.form-control').addClass('customErrorClass');
				$(element)
				.closest('.check-n').addClass('customErrorClass');
				$(element)
				.closest('.accept-n').addClass('customErrorClass');
				
				
		},
	unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
			$(element)
				.closest('.form-control').removeClass('customErrorClass');
				$(element)
				.closest('.check-n').removeClass('customErrorClass');
				$(element)
				.closest('.accept-n').removeClass('customErrorClass');
				
		},
	submitHandler: function(form)
	{
		var data=$("#register_form").serialize();
		//alert(data);
		$('#reg').css('cursor', 'pointer');
		$("#reg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
		$.ajax({
					type: "POST",
					url: base_url+'channel/basics/UserRegister',
					data: data,
					success: function(result)
					{
						$('#register_form').trigger("reset");
						$('#success_msg').html(result);
						$('#myModal3').modal({backdrop: 'static',keyboard: false});	   
						setTimeout(function()
						{
							location.reload();
						},
						5000);
					} 
						
				});
	}
		
//errorClass: 'customErrorClass',
//$('.nf-select').addClass('customErrorClass');

});
$(function() {
  
   if (localStorage.chkbx && localStorage.chkbx != '') {
			$('#remember_me').attr('checked', 'checked');
			$('#login_email').val(localStorage.usrname);
			$('#login_pwd').val(localStorage.pass);
		} else {
			$('#remember_me').removeAttr('checked');
			$('#login_email').val('');
			$('#login_pwd').val('');
		}
 
$('#remember_me').click(function() {

			if ($('#remember_me').is(':checked')) {
				// save username and password
				localStorage.usrname = $('#login_email').val();
				localStorage.pass = $('#login_pwd').val();
				localStorage.chkbx = $('#remember_me').val();
			} else {
				localStorage.usrname = '';
				localStorage.pass = '';
				localStorage.chkbx = '';
			}
		});
});
</script>

<script src="<?php echo base_url(); ?>user_assets/js/login-5.min.js" type="text/javascript"></script>

</body>

</html>


<div class="modal fade dialog-2 " id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-titles pad-b text-success" id="myModalLabel"><strong>Register for an account!</strong></h4>
      </div>
      <div class="modal-body sign-up-m" align="center">
     
   
    <form id="register_form" method="post" action="<?php echo lang_url();?>channel/basics/UserRegister">
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="fname" placeholder="Your Name" name="fname">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="lname" placeholder="Family Name" name="lname">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="property_name" placeholder="Property Name" name="property_name">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control"   placeholder="9942369685" name="mobile" id="phone">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
    <input type="text" class="form-control" id="town" placeholder="City" name="town">
    </div>
    <div class="col-md-6 col-sm-6">
    <select class="form-control" name="country">
    <?php $country = get_data('country')->result_array();
	foreach($country as $value) { 
	extract($value); ?>
    <option value="<?php echo $id;?>"><?php echo $country_name;?></option>
    <?php } ?>
	</select>
    </div>
    
    </div>
    <div class="row">
    
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="username" placeholder="Username" name="user_name">
    </div>
    <div class="col-md-6 col-sm-6">
    <input type="email" class="form-control" id="registeremail" placeholder="E-mail" name="email_address">
     
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="password" class="form-control"   placeholder="Password" name="password" id="password">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="password" class="form-control"  placeholder="Verify Your Password" name="cpassword" id="cpassword">
    </div>
    </div>
    
    <div class="row">
    
    <div class="col-md-12 col-sm-12">
     	<input type="url" class="form-control" id="exampleInputEmail1" placeholder="http://domain.com" name="web_site">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <?php  //$captcha = create_captcha();
	  //echo $captcha['image'];?>
      <input type="hidden" value="<?php //echo $captcha['word']?>" id="except_captcha" name="except_captcha"/>
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" name="captcha"  style="padding-right:3px" id="captcha" class="form-control" placeholder="Type the Image" />
    </div>
    </div>
    	
    <div class="row">
    
     <div class="col-md-6 col-sm-6 check-n">
     <input type="checkbox" id="c2" name="accept" class="accept-n"/> I Accept <a href="<?php echo lang_url();?>channel/our_links/terms">The Terms </a> and <a href="<?php echo lang_url();?>channel/our_links/privacy">Privacy Policy</a>
</div>

<div class="col-md-6 col-sm-6">
    <button class="cls_btn1 btn-block hvr-sweep-to-right" type="submit" id="reg">Sign up</button>
    </div>
    </div>
    
    </form>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>


<!-- Modal -->
<!-- Login -->
<div class="modal fade dialog-1" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Take Control</h4>
      </div>
      <div class="modal-body">
     <form id="log_form" method="post">
      
    	<div class="log-n">  
       <div class="cls_login_fomr_pad">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-user"></i></div>
      <input type="text" class="form-control" id="login_email" placeholder="Username or E-Mail" name="login_email">
    </div>
    </div>
    <div class="cls_login_fomr_pad">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-key"></i></div>
      <input type="password" class="form-control" id="login_pwd" placeholder="Password" name="login_pwd">
    </div>
    </div>
    <div class="cls_login_fomr_pad">
    <a href="#" data-toggle="modal" data-target="#myModal2" class="forget-pass" onclick="hide_login();">Forgot the password?</a>
    
    </div>
    </div>
   
   		<div class="cls_login_fomr_pad s3"> 
   <div class="row">
    <div class="col-md-6">
   <button class="cls_btn1 btn-block hvr-sweep-to-right" type="submit" id="logg">Sign In</button>
       </div>
       <div class="col-md-6 check-n">
       <input type="checkbox" id="remember_me" name="cc" /> Stay connected


       </div>
       
 
    </div>
   </div>
   
   </form>
   
   
   
      </div>
      
    </div>
  </div>
</div>
<!-- Login -->

<!-- Forget -->
<div class="modal fade dialog-1 " id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b" id="myModalLabel">Forgot access data</h4>
        <p>Wrong username or password, if you don't remember you access data enter you e-mail to take it back. 
        </p>
      </div>
      <div class="modal-body" id="add_ss">
     <form id="forgetpassword" method="post">
      
    <div class="log-n">  
      <div class="cls_login_fomr_pad">
    <div class="input-group">
      <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
      <input type="text" class="form-control" id="forget_email" placeholder="E-Mail Address" name="forget_email">
    </div>
    <label for="forget_email" class="error"></label>
    </div>
    <div class="cls_login_fomr_pad">
    <a href="javascript:;" data-toggle="modal" data-target="#myModal" onclick="hide_forget();">Back to login</a>
     </div>
    </div>
   
   <div class="cls_login_fomr_pad s3"> 
   <div class="row">
    <div class="col-md-6">
   <button class="cls_btn1 btn-block hvr-sweep-to-right" type="submit" id="forgg">Send</button>
       </div>
    </div>
   </div>
   </form>
         </div>
        <div class="modal-body" id="add_ss1" style="display:none">
          </div>
    </div>
  </div>
</div>
<!-- Forget -->

<!-- Register -->
<div class="modal fade dialog-2 " id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-titles pad-b text-success" id="myModalLabel"><strong>Register for an account!</strong></h4>
      </div>
      <div class="modal-body sign-up-m" align="center">
     
   
    <form id="register_form" method="post" action="<?php echo lang_url();?>channel/basics/UserRegister">
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="fname" placeholder="Your Name" name="fname">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="lname" placeholder="Family Name" name="lname">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="property_name" placeholder="Property Name" name="property_name">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control"   placeholder="9942369685" name="mobile" id="phone">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
    <input type="text" class="form-control" id="town" placeholder="City" name="town">
    </div>
    <div class="col-md-6 col-sm-6">
    <select class="form-control" name="country">
    <?php $country = get_data('country')->result_array();
	foreach($country as $value) { 
	extract($value); ?>
    <option value="<?php echo $id;?>"><?php echo $country_name;?></option>
    <?php } ?>
	</select>
    </div>
    
    </div>
    <div class="row">
    
    <div class="col-md-6 col-sm-6">
     <input type="text" class="form-control" id="username" placeholder="Username" name="user_name">
    </div>
    <div class="col-md-6 col-sm-6">
    <input type="email" class="form-control" id="registeremail" placeholder="E-mail" name="email_address">
     
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <input type="password" class="form-control"   placeholder="Password" name="password" id="password">
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="password" class="form-control"  placeholder="Verify Your Password" name="cpassword" id="cpassword">
    </div>
    </div>
    
    <div class="row">
    
    <div class="col-md-12 col-sm-12">
     	<input type="url" class="form-control" id="exampleInputEmail1" placeholder="http://domain.com" name="web_site">
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-6 col-sm-6">
     <?php  //$captcha = create_captcha();
	  //echo $captcha['image'];?>
      <input type="hidden" value="<?php //echo $captcha['word']?>" id="except_captcha" name="except_captcha"/>
    </div>
    <div class="col-md-6 col-sm-6">
     <input type="text" name="captcha"  style="padding-right:3px" id="captcha" class="form-control" placeholder="Type the Image" />
    </div>
    </div>
    	
    <div class="row">
    
     <div class="col-md-6 col-sm-6 check-n">
     <input type="checkbox" id="c2" name="accept" class="accept-n"/> I Accept <a href="<?php echo lang_url();?>channel/our_links/terms">The Terms </a> and <a href="<?php echo lang_url();?>channel/our_links/privacy">Privacy Policy</a>
</div>

<div class="col-md-6 col-sm-6">
    <button class="cls_btn1 btn-block hvr-sweep-to-right" type="submit" id="reg">Sign up</button>
    </div>
    </div>
    
    </form>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
<!-- Register -->

<!-- Account Activate-->
<div class="modal fade dialog-2 " id="m_active" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b" id="myModalLabel"></h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
    Thank you! Your Account is activated! You can now login to your account!
  
   
   
   
   
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
    Sorry! Your activation id invalid of already activated!
      </div>
    </div>
  </div>
</div>
<!-- Account Activate-->