<?php $this->load->view('admin/header'); ?>
<body>
 <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-globe"></i>Add Channel
  </div>
  </div>
	  
		<div class="manage">
		  <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
			  <div class="col-lg-12"><?php 
									 $success=$this->session->flashdata('success');									
										if($success){?> 
										<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Success! </strong> <?php echo $success;?>.
										<strong>Success! </strong> <?php echo $success;?>.
									</div><?php }?>  
                    <h3 class="page-header">Manage Channel</h3>
                </div>
			  <div class="col-lg-1"></div>
			  <div class="col-lg-6">
			   
					<form method="post" id="add_channels" name="add_channels" action="<?php echo lang_url(); ?>admin/add_channelsdet">
					
					<span class="error"><?php echo validation_errors();?></span>
					
                        
                        <div class="form-group">
						
                            <label>Channel Name: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]"  name="channel_name" id="channel_name" value="" type="double" min="1" max="30">	
							  
                        </div>
                         
						  <div class="form-group">
                            <label>Channel Password: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]"  name="channel_password" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel User name: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]"  name="channel_username" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
                            <label>Channel Description: <font color="#CC0000">*</font></label>
                              <textarea class="form-control validate[required]" id="textareacontent"  name="description" value="" type="double" min="1" max="30"></textarea>
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel Based In: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]"  name="based_in" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						 <div class="form-group">
                            <label>Channel Founded In: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]" id="dp1-p1" name="founded_in" value="" type="double" min="1" max="30">
							 
							  
                        </div>	
						
						 <div class="form-group">
                            <label>Channel Available Language: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]" name="available_language" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Channel Country: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]" name="available_country" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Channel Instructions: <font color="#CC0000">*</font></label>
							
                              <textarea class="form-control validate[required]" name="channel_instructions" value="" type="double" min="1" max="30"></textarea>	
							  
                        </div> 	
						
						<div class="form-group">
						
                            <label>Channel Authentication: <font color="#CC0000">*</font></label>
                              <input class="form-control validate[required]" name="authentication_requirements" value="" type="double" min="1" max="30">	
							  
                        </div>
						
						<div class="form-group">
						
                            <label>Supported Operations: <font color="#CC0000">*</font></label>
							<?php $support = $this->admin_model->get_supportdet();
							
								foreach($support as $port){
									
									// $support_op = explode(',',$row->supported_operations);
							 ?>
								
							<input type="checkbox" name="supported_operations[]" value="<?php echo $port->operations_id; ?>" ><?php echo $port->operation_name; ?>
                          
						<?php } ?>
						
                        </div>
						
                        <button type="submit" name="save" onclick="return check_channel();" value="save" id="edit_ch" class="btn btn-success">Save Changes</button>
                   </form>
							
                </div>
               
            </div>
			
         </div>
        </div>
		</div>
		<?php $this->load->view('admin/footer'); ?>
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
	$('#add_channels').submit(function(e){

    e.preventDefault();

    if(!$("#add_channels").validationEngine('validate') == true)
    {
		var body = $("html, body");
				body.animate({scrollTop:0}, '500', 'swing', function() { 
				})
				
		return false;
    }
	else
	{
		document.add_channels.submit();
	}
	});
</script>



<script>
  $('#dp1-p1').datepicker({
				autoclose: true,
               format: 'yyyy'
           });
</script>

