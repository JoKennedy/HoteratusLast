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
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<style>
.error{color: red;}
</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="root" >
    <title style="color: white">Hotel Channel Manager- Admin Login</title>
    <link href="<?php echo base_url().'css/validationEngine.jquery.css'?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url();?>js/jquery-1.11.0.min.js"></script>
	 <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url().'js/jquery.validationEngine-en.js'?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url().'js/jquery.validationEngine.js'?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
    
    
</head>
<body style="background-color:white">

    <div class="container">
	
        <div class="row">

		<div class="col-md-12 center login-header" style="display: block;float: none !important;margin-left: auto !important;
    margin-right: auto !important;text-align: center;">
           		<?php
         $query = $this->admin_model->get_siteconfig();
		foreach($query as $r)
			{
			$logo =  $r->site_logo;
		}
		    ?>
	    <img src="<?php echo base_url();?>uploads/logo/<?php echo $logo;?>" />
	    <br>
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign In</h3>
                    </div>
<?php
			if (isset($error))
			{?>
				 <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $error;?>
				
            </div>
			<?php
			}else{?>
			   <div class="alert alert-info">
              
                Please login with your Username and Password.
				
            </div>
			<?php }?>
			
                    <div class="panel-body">
					<?php $attributes=array('class'=>'form form-horizontal','id'=>'admin_login','name'=>'admin_login');
                                  echo form_open('admin/index2',$attributes); ?>
					   <fieldset>
                                <div class="form-group">
                                    <input class="form-control validate[required]" placeholder="Username" name="username" size="50" autofocus="" type="text" value="<?php echo set_value('username'); ?>">
									<span class="error"><?php echo form_error('title');?></span>
									                                </div>
                                <div class="form-group">
                                    <input class="form-control validate[required]" placeholder="Password" name="password" value="<?php echo set_value('password'); ?>" size="50" type="password">
								
									                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" value="Remember Me" type="checkbox">Remember Me
                                    </label>
                                </div>
								 <a data-target=".forget-modal" data-toggle="modal" class="forget" href="javascript:;">Forgot your password?</a>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login
                            </button>
							</fieldset>
                        <?php echo form_close(); ?>        
						</div>
                </div>
            </div>
        </div>
    </div>
       
<div class="modal fade forget-modal" tabindex="-1" role="dialog" aria-labelledby="myForgetModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
           				<button type="button" class="close" data-dismiss="modal">
					<span class="fa fa-times"></span>
					<span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Recovery password</h4>
			</div>
            <div class="modal-body" id="add_ss"> </div>
     <?php $attribute=array('class'=>'form','id' => 'reco','name' => 'reg_form'); ?>
	<?php echo form_open_multipart('builder/login',$attribute); ?>
			<div class="modal-body">
			<span style="color:red;display:none;" id="result_check2" ></span>
				<p>Type your email account</p>

				<input type="email" name="recovery_email" id="recovery_email" class="form-control txtfield validate[required]" autocomplete="off">
		
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-custom" id="recover_recovers">Recovery</button>
			</div>
            <?php echo form_close();?>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
    
</div>
	
	<script type="text/javascript">

$('#forget_pwd').click(function(){
$('#modal_dialog').show();
});

$(document).ready(function(){
	//$("#reco").validationEngine();
	$("#admin_login").validationEngine();
	$("#recovery_email").validationEngine();
	});
	
	$('#reco').validate({
		rules:
		{
			recovery_email:
			{
				required : true,
			}
		},
		submitHandler:function()
		{
			var maval=$('#recovery_email').val();
			//alert(maval);
			var dataString = 'recovery_email='+ maval;
	   	 	$.ajax({
		url : '<?php echo lang_url();?>index.php/admin/emailcheck',
		data: dataString, 
		type: 'POST',
		success: function(resp) 
		{ 
			$('#recovery_email').val('');
			if(resp=="Mail has been sent successfully")
			{
				$('#add_ss').html('<div class="alert alert-success" id="login_error">Your password has been send to email. <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button></div>');
			}
			else
			{
				$('#add_ss').html('<div class="alert alert-danger" id="login_error">Can not forget passeord. <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button></div>');
			}
		}
	});
		}
	});


$('#recover_recover').click(function(){
if($("#recovery_email").validationEngine('validate') == true)
{
return false;
}
var maval=$('#recovery_email').val();
//alert(maval);
var dataString = 'recovery_email='+ maval;
	     $.ajax({
		 url : '<?php echo lang_url();?>index.php/admin/emailcheck',
		 data: dataString, 
		 type: 'POST',
		 success: function(resp) { 
		 if(resp=="Mail has been sent successfully")
		 {
		var reg="1";
		window.location.href="<?php  echo lang_url().'index.php/admin/login1/' ?>"+reg; 
		 }
		 else
		 {
		 var reg="2";
		window.location.href="<?php  echo lang_url().'index.php/admin/login1/' ?>"+reg; 
		 }
		 }
});
});






function show_box()
{	alert('//');
		$('#ssttst').show();
}



function show_login()
{	//alert('//');
		$('#modal_dialog').hide();
}




</script>
 
</body>

</html>

