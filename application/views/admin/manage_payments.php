<?php $this->load->view('admin/header'); ?>
<body>

	<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Payment Details
  </div>
  </div>
	   <div class="manage">
	     <div id="page-wrapper">
		 
            <div class="row">
            <div class="col-md-12">
            <?php 
				$success=$this->session->flashdata('success');									
				if($success)	
				{
			?> 
			<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success! </strong> <?php echo $success;?>.
			</div>
			<?php 
				}
			?>  
            </div>
               <!-- <div class="col-lg-6">
                    <h1 class="page-header">Manage Payment Details</h1>
                </div>  -->
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
             
            <div class="row">
               
                <!-- /.col-lg-6 -->
                 <div class="col-lg-1">

								</div>
								  <div class="col-lg-10">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            Paypal Payment Details

                        </div>
						 <div class="panel-body">

                            <div class="row">
                            
                            
                                    

							    <div class="col-lg-1">

								</div>

                                <div class="col-lg-6">
									 <?php $attributes=array('class'=>'form form-horizontal','id'=>'pay_form','name'=>'pay_form');

                                  echo form_open_multipart('admin/manage_payments/update/',$attributes); ?>
								  
									<span class="error"><?php echo validation_errors();?></span>
                                    
                                    <input type="hidden" value="<?php echo $id;?>" name="admin_id">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Paypal Email</label>
                                            <input class="form-control validate[required]" name="paypal_emailid" value="<?php echo $paypal_emailid;?>" type="email">
											
											<!--input type="hidden" name="id" value=""/>-->
                                        </div>
                                        
										<div class="form-group">
                                            <label><font color="#CC0000">*</font>Payment Mode</label>
                                            
                                            <input type="radio" value="0" <?php if($paypalmode==0) { ?> checked <?php } ?> name="paypalmode"> Local
                                            
                                            <input type="radio" value="1" <?php if($paypalmode==1) { ?> checked <?php } ?>name="paypalmode"> Live
                                                                                  
                                        </div>
										
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    <?php echo form_close(); ?>
                                </div>
								</div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-heading -->
                       
                        <!-- /.panel-body -->
                      <!--</div>
                   /.panel -->
              
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
           
		   </div>
            
          
	   
	   <?php $this->load->view('admin/footer'); ?>
	   
	   
<!-- jQuery Version 1.11.0 -->
<script>



    $('#pay_form').submit(function(e){

    e.preventDefault();

    if(!$("#pay_form").validationEngine('validate') == true)
    {
		var body = $("html");
				body.animate({scrollTop:0}, function() { 
				})

      return false;
    }
	else
	{
		document.pay_form.submit();
	}
	});

$(document).ready(function(){

	//$("#siteconfig").validationEngine();
	
	

	});
	
	$('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
      
        case 'jpeg':
        case 'jpg':
        case 'png':
		
		case 'gif':
        case 'tiff':
        case 'bmp':
      
            $('#uploadButton').attr('disabled', false);
            break;
        default:
        $('#uploadButton').attr('disabled', true);
            alert('This is not an allowed file type.');
            this.value = '';
    }
});

</script>