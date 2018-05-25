<style>
.error
{
color:red;
}
</style>
<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
<?php $this->load->view('admin/common/script'); ?>
   <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Template</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-1"></div>
				<div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Add New Template
                            <a href="<?php echo lang_url(); ?>index.php/admin/manage_temp"><button type="button" style="width: 157px; height: 33px; margin-top: -8px;" class="pull-right label label-primary">Manage Template</button></a>
                        </div>

                        <div class="panel-body">
                            <div class="row">
							<div class="col-lg-1">
							</div>
                                <div class="col-lg-6">
								  <?php $attributes=array('class'=>'form form-horizontal','id'=>'addadmin');
                                 echo  form_open_multipart('admin/add_temp',$attributes); ?>   
                                        
										
                                        <div class="form-group">
                                            <label>Template</label>
										 <input name="admin_image" type="file" value=""onChange="showimagepreview(this)" />   
												  <br>
												  <div class="controls">
										<input type="hidden" name="hidimage" value="default_adminuser.jpg" >
											<img src="<?php echo base_url(); ?>uploads/admin_user/default_adminuser.jpg" class="img_profile" width="150px" height="150px">		<img class="img_profile_loading" src="<?php echo base_url(); ?>assets/img/loading.gif" style="position:absolute; top:40px; left:0;left: 60px; height: 30px;display: none; " />
												  </div>
                                        </div>
										
										
                                        <button type="submit" name="submit"class="btn btn-success">Save</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    <?php echo form_close(); ?>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
<script>
	$(document).ready(function(){
	$("#addadmin").validationEngine();
	});
	function showimagepreview(input) {
//alert('ss');
		 $("#img_profile img").css({"opacity":"0.3"});
		 	$(".img_profile_loading").css("display","");
if (input.files && input.files[0]) {
var filerdr = new FileReader();
filerdr.onload = function(e) {
$(".img_profile_loading").css("display","none");
$('.img_profile').attr('src', e.target.result);
}
filerdr.readAsDataURL(input.files[0]);
}
}
</script>
   