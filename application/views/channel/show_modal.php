<?php
if($show_modal=='login')
{
?>
<div class="form-group">
<p>User Name :</p>
<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Username" name="login_email" id="login_email" required="" type="text">
</div>
<div class="form-group">
<p>Password :</p>
<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Password" name="login_pwd" id="login_pwd" required="" type="password"> </div>
<div class="clearfix"></div>
<div class="pull-left cont_list mar-top-10">
<div class="checkbox">
<label>
<input value="1" type="checkbox" id="remember_login">
<!-- <span class="cr"><i class="cr-icon fa fa-square"></i></span> -->
Remember Me
</label>
</div>
</div>
<div class="pull-right cont_list mar-top-10">
<a class="tar_create" href="javascript:;" data-type="log_register">create an account</a>   <br>	
<a class="tar_create" href="javascript:;" data-type="forget">forgot password ?</a>
</div>
<div class="cont_list mar-top-10">
<button type="submit" class="btn btn_trial" id="logg">Sign in</button>
</div>
<?php
}
?>
<?php
if($show_modal=='register' || $show_modal=='log_register')
{
?>
	<div class="col-xs-6">
	<label class="pull-left">First Name</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="First Name" id="fname" name="fname" required="" type="text"> 
	</div>

	<div class="col-xs-6">
	<label class="pull-left">Last Name</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Last Name" id="lname" name="lname" required="" type="text"> 
	</div> 

	<div class="col-xs-6">
	<label class="pull-left">Property Name</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Property Name" id="property_name" name="property_name" required="" type="text"> 
	</div>

	<div class="col-xs-6">
	<label class="pull-left">Phone</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="+449842080276" name="mobile" id="mobile" required="" type="text"> 
	</div> 

	<div class="col-xs-6">
	<label class="pull-left">City</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="City" name="town" id="town" required="" type="text">
	</div>
				
	<div class="col-xs-6">
	<label class="pull-left">Country</label>
				
	<select class="form-control" name="country" autocomplete="on" >
	<?php $country = get_data('country')->result_array();
	foreach($country as $value) { 
	extract($value); ?>
	<option value="<?php echo $id;?>"><?php echo $country_name;?></option>
	<?php } ?>
	</select>
	</div>

	<div class="col-xs-6">
	<label class="pull-left">Website</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="http://www.domain.com" id="web_site" name="web_site" type="url" value="http://"> 
	</div>

	<div class="col-xs-6">
	<label class="pull-left">E-mail</label>
	<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="E-mail" id="registeremail" name="email_address" required="" type="email"> 
	</div> 

	<div class="col-xs-6">
	<label class="pull-left">Username</label>
	<input type="text" id="username" name="user_name" placeholder="Username" autocomplete="off" class="form-control placeholder-no-fix"> 
	</div>

	<div class="col-xs-6">
	<label class="pull-left">Password</label>
	<input type="password" name="password" placeholder="Password" id="password" autocomplete="off" class="form-control placeholder-no-fix"> 
	</div>

	<div class="col-xs-6">
	<label class="pull-left">Re-type Your Password</label>
	<input type="password" id="cpassword" name="cpassword" placeholder="Re-type Your Password" autocomplete="off" class="form-control placeholder-no-fix">
	</div>
	<div class="col-xs-6">
	<script src='https://www.google.com/recaptcha/api.js'></script>
    <div class="g-recaptcha" data-sitekey="<?php echo get_data(CONFIG)->row()->Site_key;?>"></div>
	</div>
	<div class="row">
	<div class="col-md-6 col-sm-6"></div>
	</div>  
	<div class="col-md-6 col-sm-6">
	<div class="checkbox">
	<label>
	<input type="checkbox" name="accept" required>
	<!--<span class="cr"><i class="cr-icon fa fa-square"></i></span>-->
	I accept <a href="<?php echo lang_url();?>channel/our_links/terms">Terms</a> and <a href="<?php echo lang_url();?>channel/our_links/privacy">policy</a>
	</label>
	</div>
	</div>
	
	<button type="submit" class="btn btn_trial" id="reg_btn">submit</button>
<?php
}
?>

<?php
if($show_modal=='forget')
{	
?>

<div class="form-group">
<input type="text" class="form-control form-control-solid placeholder-no-fix" autocomplete="off" id="forget_email" placeholder="E-Mail Address" name="forget_email">
</div>

<label for="forget_email" class="error"></label>

<div class="cont_list mar-top-10">
<button type="submit" class="btn btn_trial" id="forgg">Submit</button>
</div>

<?php } ?>