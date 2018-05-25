<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
 <?php //$this->load->view('admin/common/script'); ?>
   <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
				<?php 	  
									if(isset($error))	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?> 		
									<?php 
									 $success=$this->session->flashdata('success');									
										if($success)	{	?> 
										<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Success! </strong> <?php echo $success;?>.
									</div><?php }?>  
									<?php  $error=$this->session->flashdata('error');										
										if($error)	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?> 
                    <h1 class="page-header">Admin Profile</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Admin Details
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-6">
								 <?php $attributes=array('class'=>'form form-horizontal','id'=>'updateadmin');
                                 echo  form_open_multipart('admin/profile_updated',$attributes); ?>   
                                        <div class="form-group">
                                            <label>Login-Name</label>
                                            <input class="form-control validate[required]" name="login_name" value="<?php 
											echo $login_name;?>">
											
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" name="password" type="password" value="<?php if(isset($password)){
											echo $password; }
											//echo set_value('password'); ?>"/>
											
                                            </div>
										<div class="form-group">
                                            <label>Name</label>
                                            <input class="form-control" name="name" value="<?php if(isset($name)){
											echo $name; }
											//echo set_value('name');
										?>"/>
											
                                         </div>
										<div class="form-group">
                                            <label>Email-id</label>
                                            <input class="form-control validate[required,custom[email]]"  name="email_id" id="email_id" value="<?php if(isset($email_id)){
											echo $email_id; }
											//echo set_value('email_id'); ?>" onChange="return chk_exist_email(this.value);"/>
                                          <div id="chk_exist_mail"> <font color="red"><div id="">
Mail id is already exist</div>
</font> </div>
                                        </div>
										<div class="form-group">
                                            <label>Admin-Image</label>
										 <input name="admin_image" type="file" value=""onChange="showimagepreview(this)" />   
												  <br>
												  <div class="controls">
										<input type="hidden" name="hidimage" value="<?php echo $admin_image;?>">
											<img src="<?php echo base_url(); ?>uploads/<?php echo $admin_image;?>" class="img_profile" width="150px" height="150px">		<img class="img_profile_loading" src="<?php echo base_url(); ?>assets/img/loading.gif" style="position:absolute; top:40px; left:0;left: 60px; height: 30px;display: none; " />
												  </div>
                                        </div>
     <!--<div class="form-group">
     <label>Status</label>
    <select name="status" id="status">
    <option value="Active" <?php //if($status=="Active"){ echo 'selected="selected"';}?>>Active</option>
     <option  <?php //if($status=="DeActive"){ echo 'selected="selected"';};?> value="DeActive">DeActive</option>
  </select>
                                        </div>-->
                                       
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                 
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
	$("#updateadmin").validationEngine();
	 $("#chk_exist_mail").hide();
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

function chk_exist_email(email)
{
  $.ajax({
  type:"POST",
  url:"<?php echo lang_url(); ?>index.php/admin/chk_exist_email/<?php echo $id;?>",
  data:"email="+email,  
  success:function(res)
  {
      if(res==1)
	  {
      	$("#chk_exist_mail").hide();
	  }
	  else
	  {
		 $('#email_id').val('');
		 $("#chk_exist_mail").show(); 
	  }
  }
});
}

$('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
      
        case 'jpeg':
        case 'jpg':
        case 'png':
		
		case 'gif':
        case 'tiff':
        case 'bmp':
      	$('.img_profile').show();
            $('#uploadButton').attr('disabled', false);
            break;
        default:
        $('#uploadButton').attr('disabled', true);
            alert('This is not an allowed file type.');
				$('.img_profile').hide();
            this.value = '';
    }
});
	</script>
   
