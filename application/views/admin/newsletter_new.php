<?php $this->load->view('admin/header'); ?>
<body> 
<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage NewsLetter
  </div>
  </div>
<div class="manage">
	     <div id="page-wrapper">
		 
            <div class="row">
            <div class="col-md-12">
            <?php 
				$success=$this->session->flashdata('success_news');									
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
               <!-- <div class="col-lg-12">
                    <h1 class="page-header">Manage NewsLetter </h1>
                </div>-->
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

                            Send NewsLetter


                        </div>
						 <div class="panel-body">

                            <div class="row">
                            
                            
                                    

							    <div class="col-lg-1">

								</div>

                                <div class="col-lg-12">
                                
                                <form id="send_news" class="form-horizontal clscommon_form" role="form" action="<?php echo lang_url()?>admin/send_newsletter" method="post" enctype="multipart/form-data">
							<!--<form class="form-horizontal" role="form">-->
								<div class="form-body">
									
									
									<div class="form-group">
										<label class="col-md-3 control-label">Send NewsLetter To</label>
										<div class="col-md-9">
											<div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="users" id="users1" value="1" > All Users</label>
												<label class="radio-inline">
												<input type="radio" name="users" id="users2" value="2" checked="checked"> All Subscribers</label>
											</div>
										</div>
									</div>
                                    <div id="all" style="display:none">
									<div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3"></label>
                                    <div class="col-sm-6">
                                    <select required="" class="form-control" name="all[]" multiple="multiple">
                                        <?php 
											$all = get_data(TBL_USERS,array('status'=>'1'))->result_array();
											foreach($all as $val)
											{
												extract($val);
										?>
                                        <option value="<?php echo $email_address;?>"><?php echo $email_address;?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    <div id="sub">
									<div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3"></label>
                                    <div class="col-sm-6">
                                    <select required="" class="form-control" name="sub[]" multiple="multiple">
                                    <?php 
											$sub = get_data(SUBSCRIBE,array('status'=>'Active'))->result_array();
											foreach($sub as $sub_val)
											{
												extract($sub_val);
										?>
                                       
                                        <option value="<?php echo $email?>"><?php echo $email?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                    </div>
                                    </div>
									<div class="form-group">
										<label for="u_fname" class="control-label col-sm-3">Description</label>
										<div class="col-sm-6">
											<textarea class="ckeditor form-control" name="description" rows="6" data-error-container="#editor2_error" required></textarea>
											<div id="editor2_error"></div>
										</div>
									</div>
								</div>
								<div class="form-actions fluid">
									<div class="col-md-offset-3 col-md-9">
										<input type="submit" name="submit" class="btn btn-success" value="Submit"/>
										<button type="reset" class="btn default">Reset</button>
									</div>
								</div>
							</form>
                            
									
                                </div>
								</div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                       
            </div>
            <!-- /.row -->
           </div> 
            
           <?php $this->load->view('admin/footer'); ?>
	   
	   
<!-- jQuery Version 1.11.0 -->

 <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
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
	
	$('#send_news').validate({
		rules:
		{
			subject:
			{
				required:true,
				regexp: /['"]/,
			},
			"all":  {required:true},
			"sub":  {required:true}
		},
		messages: {
        subject: {
            required: "Should not be blank",
            regexp: "Single quotes and double quotes not allowed"
        }
    }
	});

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


$('#users1').click(function() {
	
	$('#all').show();
	$('#sub').hide();
});
$('#users2').click(function() {
	
	$('#sub').show();
	$('#all').hide();
});


</script>