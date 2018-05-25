<?php $this->load->view('admin/header'); ?>
<body>
<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-globe"></i>Edit Channel
  </div>
  </div>
			<div class="manage">
		  <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
			  <div class="col-lg-12">
			  <?php 
			 $success=$this->session->flashdata('success');									
				if($success){?> 
				<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Success! </strong> <?php echo $success;?>
			</div><?php }?>  
                    <h1 class="page-header">Manage Channel</h1>
                </div>
			  <div class="col-lg-1"></div>
			  <div class="col-lg-6">
			   <?php 
			   
							if($tmp){
							  foreach($tmp as $row)
							   {		
							$cid = $row->channel_id;
							?>
				
					<form method="post" id="edit_channels" action="<?php echo lang_url(); ?>admin/edit_channeldet" name="edit_channels">
					
					<span class="error"><?php echo validation_errors();?></span>
                      
					<input type="hidden" name ="channel_id" value="<?php echo $row->channel_id; ?>">	
                        
                        <div class="form-group">
						
                            <label>Channel Name: <font color="#CC0000">*</font></label>
                              <input class="form-control" name="channel_name" id="channel_name" value="<?php if(isset($row->channel_name)){echo $row->channel_name; }?>" type="double" min="1" max="30">	
							  
                        </div>
                         
						  <div class="form-group">
                            <label>Channel Password: <font color="#CC0000">*</font></label>
                              <input class="form-control" required name="channel_password" value="<?php if(isset($row->channel_password)){echo $row->channel_password; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel User name: <font color="#CC0000">*</font></label>
                              <input class="form-control" required name="channel_username" value="<?php if(isset($row->channel_username)){echo $row->channel_username; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
                            <label>Channel Description: <font color="#CC0000">*</font></label>
                              <textarea class="form-control" id="textareacontent" required name="description" value="<?php if(isset($row->description)){echo $row->description; }?>" type="double" min="1" max="30"><?php echo $row->description; ?></textarea>
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel Based In: <font color="#CC0000">*</font></label>
                              <input class="form-control"  name="based_in" value="<?php if(isset($row->based_in)){echo $row->based_in; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel Founded In: <font color="#CC0000">*</font></label>
                              <input class="form-control" id="dp1-p1" name="founded_in" value="<?php if(isset($row->founded_in)){echo $row->founded_in; }?>" type="double" min="1" max="30">
							 
							  
                        </div>	
						
						 <div class="form-group">
                            <label>Channel Available Language: <font color="#CC0000">*</font></label>
                              <input class="form-control" name="available_language" value="<?php if(isset($row->available_language)){echo $row->available_language; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Channel Country: <font color="#CC0000">*</font></label>
                              <input class="form-control" name="available_country" value="<?php if(isset($row->available_country)){echo $row->available_country; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Channel Instructions: <font color="#CC0000">*</font></label>
							
                              <textarea class="form-control" name="channel_instructions" value="<?php if(isset($row->channel_instruction)){echo $row->channel_instruction; }?>" type="double" min="1" max="30"><?php echo $row->channel_instruction; ?></textarea>	
							  
                        </div> 	
						
						<div class="form-group">
						
                            <label>Channel Authentication: <font color="#CC0000">*</font></label>
                              <input class="form-control" name="authentication_requirements" value="<?php if(isset($row->authentication_requirements)){echo $row->authentication_requirements; }?>" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Supported Operations: <font color="#CC0000">*</font></label>
							<?php $support = $this->admin_model->get_supportdet();
							
								foreach($support as $port){
									
									$support_op = explode(',',$row->supported_operations);
							 ?>
								
							<input type="checkbox" name="supported_operations[]" value="<?php echo $port->operations_id; ?>" <?php  if(in_array($port->operations_id,$support_op)){echo 'checked';} ?>><?php echo $port->operation_name; ?>
                          
						<?php } ?>
						
                        </div>
						
                        <button type="submit" name="save" onclick="return check_channel();" value="save" id="edit_ch" class="btn btn-success">Save Changes</button>
                   </form>
							<?php } } ?>
                </div>
               
            </div>
			
         </div>
        </div>
		</div>
<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
CKEDITOR.replace('textareacontent' ,
{                     
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
jQuery.validator.addMethod("lettersonly", 
			function(value, element) {
       		 return this.optional(element) || /^[a-z," "]+$/i.test(value);
			}, "Letters only please");
			
$.validator.addMethod("regexp", function(value, element, param) { 
  return this.optional(element) || !param.test(value); 
});	
</script>

<script>
  $("#edit_channels").validate({
			rules: {
                  channel_name: {
                        required: true,
						 remote: {
							url:"<?php echo lang_url(); ?>admin/channel_nameexists",
							type:"post",
							data:{
								username:function(){
									return $("#channel_name").val();
								}
							}
						},
                    },
					channel_password: {
                        required: true,
                    },
					 channel_username: {
                        required: true,
						remote:{
							url:"<?php echo lang_url(); ?>admin/channel_usernameexists",
							type:"post",
							data:{
								username:function(){
									return $("#channel_username").val();
								}
							}
						},
						
                    },
					 description: {
                        required: true,
                    },
					 based_in: {
                        required: true,
                    },
					 founded_in: {
                        required: true,
                    },
					 available_language: {
                        required: true,
                    },
					 available_country: {
                        required: true,
                    },
					
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
			submitHandler: function(form)
			{
				var data=$("#edit_channels").serialize();
				// alert(data);
				$('#edit_ch').css('cursor', 'pointer');
				$("#edit_ch").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							data: data,
							success: function(result)
							{
								$('#edit_channels').trigger("reset");
								setTimeout(function()
								{
									 location.reload();
								},
								5000);
							}
						});
			} 
	
</script>
<!--<script>
(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#edit_channels").validate({
                rules: {
                    channel_name: 
					{
						required:true,
						remote: {
							url:"<?php //echo lang_url(); ?>admin/channel_nameexists",
							type:"post",
							data:{
								username:function(){
									return $("#channel_name").val();
								}
							}
						},
					},
						
                    email: {
                        required: true,
                        email: true
                    },
                    channel_password: {
                        required: true
                    },
                    description: "required",
                    based_in: "required",
                    founded_in: "required",
                    available_country: "required",
                    available_language: "required",
                },
                messages: {
                    channel_name: "Please enter your Channel Name",
                    channel_password: {
                        required: "Please provide a Password",
                    },
                    // agree: "Please accept our policy"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);
</script>  -->
<script>
(function($,W,D,undefined)
{
    $(D).ready(function()
    {

         //form validation rules
         $("#edit_channels").validate({
             rules:
             {
                email:
                {
                    required: true,
                    email: true,
                    "remote":
                    {
                      url: '<?php echo lang_url(); ?>admin/channel_nameexists',
                      type: "post",
                      data:
                      {
                          channel_name: function()
                          {
                              return $('#edit_channels :input[name="channel_name"]').val();
                          }
                      }
                    }
                },
                name:
                {
                    required: true,
                    minlength: 3
                },
                password:
                {
                    required: true,
                    minlength: 8
                },
                password_repeat:
                {
                    required: true,
                    equalTo: password,
                    minlength: 8
                }
             },
             messages:
             {
                 email:
                 {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address.",
                    remote: jQuery.validator.format("{0} is already taken.")
                 },
                 name: "Please enter your name.",
                 password: "Please enter a password.",
                 password_repeat: "Passwords must match."
             },
             submitHandler: function(form)
             {
                form.submit();
             }
         });

    });

})(jQuery, window, document);
</script>