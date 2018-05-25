<div class="admin-footer">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12"> <p class="text-center">Hoteratus - Hospitality Software Solutions . © 2017 All rights reserved</p></div>
</div>
</div>
</div>
    <!-- jQuery -->
     <link href="<?php echo base_url(); ?>admin_assets/css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
   
    <script src="<?php echo base_url();?>admin_assets/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/wow.min.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/creative.js"></script>
    <script src="<?php echo base_url();?>admin_assets/js/jquery.validationEngine.js"></script>
    <script src="<?php echo base_url(); ?>admin_assets/js/jquery.validationEngine-en.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url();?>admin_assets/js/list.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>admin_assets/js/jquery.validate.min.js"></script>
    
    

<script type="text/javascript">
$(document).ready(function(){
$('.top_menu').click(function(){
$('.top_menu').removeClass('active');
  $(this).addClass('active');
})
})
</script>
<script>
<?php 
if(uri(3)=='add_admin' || uri(3)=='edit_admin')
{
?>
$('#addadmin').submit(function(e){

    e.preventDefault();

    if(!$("#addadmin").validationEngine('validate') == true)
    {
		var body = $("html, body");
				body.animate({scrollTop:0}, '500', 'swing', function() { 
				})
				
		return false;
    }
	else
	{
		document.addadmin.submit();
	}
	});
	 $('#editadmin').submit(function(e){
    e.preventDefault();
    if(!$("#editadmin").validationEngine('validate') == true)
    {
		var body = $("html, body");
		body.animate({scrollTop:0}, '500', 'swing', function() { 
				})
		return false;
    }
	else
	{
		//$('#editadmin').submit();
		document.editadmin.submit();
	}
	});
	
	$(document).ready(function(){
	/*if(!$("#addadmin").validationEngine('validate')==true)
	{
		alert("Fg");
	}
 	$("#addadmin").validationEngine();*/
	$("#chk_exist_mail").hide();
	$("#chk_exist_login_name").hide();
	$("#chk_current_pwd").hide();
	$("#chk_current_pwd1").hide();

	$("#change_pass").hide();
	chk_exist_email('<?php echo $email_id?>');
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
  url:"<?php echo lang_url(); ?>admin/chk_exist_email/<?php echo $id;?>",
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

function chk_exist_LoginName(LoginName)
{
  $.ajax({
  type:"POST",
  url:"<?php echo lang_url(); ?>admin/chk_exist_LoginName/<?php echo $id;?>",
  data:"login_name="+LoginName,  
  success:function(res)
  {
	  if(res==1)
	  {
      	$("#chk_exist_login_name").hide();
	  }
	  else
	  {
		 $('#login_name').val('');
		 $("#chk_exist_login_name").show(); 
	  }
  }
});
}

function chk_current_pwd(pwd,id)
{
	
	if(pwd!='')
	{
		
  		$.ajax({
				  type:"POST",
				  url:"<?php echo lang_url(); ?>admin/chk_current_pwd",
				  data:"pwd="+pwd+"&id="+id,  
				  success:function(res)
				  {
						if(res==1)
						{
							$("#chk_current_pwd").show();
							$("#chk_current_pwd1").hide();
							$("#change_pass").show();
						}
						else
						{
							$('#current').val('');
							$('#newpassword').val('');
							$('#cnfpassword').val('');
							$("#chk_current_pwd").hide();
							$("#chk_current_pwd1").show();
							$("#change_pass").hide();
						}
						 
				  }
			});
	}
	else
	{
		 $("#change_pass").hide();
	}
}
<?php
}
?>

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

<!-- end jQuery -->

</body>

</html>