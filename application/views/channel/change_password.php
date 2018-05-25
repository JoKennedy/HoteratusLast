  

<!DOCTYPE html>
<html>

<body>
<div class="contents" >

	<div class="verify_det">
		<h4><a href="javascript:;">My Property</a>
			<i class="fa fa-angle-right"></i>
			Change Password
		</h4>
		
	</div>	

	<?php $error = $this->session->flashdata('error'); 
		if($error!="")
		{
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>
	
	
		<form class="form-horizontal" id="change_password" method="post" action="<?php echo lang_url();?>channel/change_password">


			<div class="form-group form_list_top">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form_type_list">
						<label class="">Old Password <span class="errors">*</span></label>
						<input type="password" class="form-control" id="old_pass" required name="old_pass" value="">
					</div>
				</div>
			</div>	  
		  
		  
			<div class="form-group form_list_top">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form_type_list">
						<label class="">New Password <span class="errors">*</span></label>
						  <input type="password" class="form-control" id="new_pass" name="new_pass" value="" required>
					</div>
				</div>	
			</div>
		
			<div class="form-group form_list_top">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form_type_list">
					<label class="">Confirm Password <span class="errors">*</span></label>
					<input type="password" class="form-control" required id="confirm_pass" name="confirm_pass" value="">
					</div>
				</div>		
			</div>

		  <input type="hidden" name="change_passwords" value="" id="change_passwords">	
			  
			<div class="form-group form_list_top">				
				<input class="cls_com_blubtn" type="button" value="Save" name="sumit" onclick="return Save();">
			</div>
		</form>
</div>
	
               
    
    <?php $this->load->view('channel/dash_sidebar'); ?>
</body>
</html>

<script type="text/javascript">
	function Save() {
		if (document.getElementById("old_pass").value=="" || document.getElementById("new_pass").value==""  || document.getElementById("confirm_pass").value==""  )
		{
			alert("Complete all Field To Continue");
		}
		else
		{
			document.forms["change_password"].submit();
		}
		
	}

</script>