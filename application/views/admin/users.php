<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Hotelier
  </div>
  </div>
  <div class="manage">
    <div class="row">
                <div class="col-lg-12">
                  <!--  <h1 class="page-header">Manage Hotelier</h1>  -->
					<?php if(uri(4)=='manage_users'){?>
					<div class="panel-heading"><a href="<?php echo lang_url(); ?>admin/manage_users/add"><button type="button" style="width: 157px; height: 33px; margin-top: -8px; float:left;" class="pull-right label label-success">Add New <i class="fa fa-plus"></i></button></a>
					<?php } ?>
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Manage Admin</h3>
                            </div>
                </div>
            </div>
    <div class="row-fluid clearfix">
    <?php     
          if(isset($error)) { ?> 
           <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oh! </strong><?php echo $error;?>.
          </div>
          <?php }?>     
          <?php 
           $success=$this->session->flashdata('success');                 
            if($success)  { ?> 
            <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success! </strong> <?php echo $success;?>.
          </div><?php }?>  
          <?php  $error=$this->session->flashdata('error');                   
            if($error)  { ?> 
           <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oh! </strong><?php echo $error;?>.
          </div>
          <?php }?> 
       <?php 
			if($action== "add") $title ="Add Hotelier" ;
              else if($action== "edit") $title ="Edit Hotelier" ;
              else if($action== "view_single" || $action== "list") $title ="View Hotelier" ;
        ?>
		
      <div class="col-md-12">
      <div class="table-responsive">
	  
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
      <br><br>
      <!-- view -->
	  <?php if($action== "list"){
		 if($users){$i=0; ?>
     <?php echo form_open('admin/manage_users/bulk_delete', array('id'=>'delete_form', 'onsubmit'=>'return submit_form();', 'class="form-inline"')); ?>
        <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0" style="width:100%">
        
			<thead>
				<tr class="top-tr">
               		<th><input type="checkbox" id="gc_check_all" /> <button type="submit" class="btn btn-small btn-danger"><i class="fa fa-trash-o"></i></button></th>
					<th>S.No.</th>
					<th>Username</th>
					<th>Email</th>
					<th>Status</th>
					<th>Multi property Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
	   foreach($users as $row)
	   {
			
			$multiproperty=$row->multiproperty;
			$user_id = insep_encode($user_id= $row->user_id);
			$pass_id=$this->admin_model->encryptIt($row->user_id);
									$i++;
		?>
		<tr>
        <td><input name="order[]" type="checkbox" value="<?php echo insep_encode($row->user_id); ?>" class="gc_check"/></td>
		<td><?php echo $i; ?> </td>
		<td><?php echo $row->user_name; ?> </td>
		<td><a href="<?php echo lang_url(); ?>index.php/admin/view_hotelier/<?php echo $pass_id; ?>" title="View Template" ><?php echo $row->email_address; ?> </a></td>
		<td><?php if($row->status==1){ echo '<span class="label label-sm label-success">Active</span>'; } else { echo '<span class="label label-sm label-danger">De-active</span>'; } ?> </td>

			<td align="center" style="cursor:pointer"><?php if($multiproperty=='Deactive'){ $value=array('class'=>'delete','onclick'=>'return delcon1();');
				echo anchor('admin/manage_users/active/'.$pass_id,'<span class="label label-sm label-success">Active</span>',$value);
				 } else {  $value=array('class'=>'delete','onclick'=>'return delcon1();');
				echo anchor('admin/manage_users/inactive/'.$pass_id,'<span class="label label-sm label-danger">De-active</span>',$value);} ?> </td>
		<?php
		echo "<td>";
		
		
	// $pass_id=$this->admin_model->encryptIt($row->user_id);
	
		$value=array('class'=>'edit');
		echo anchor('admin/manage_users/edit/'.$user_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';
			
		$value=array('class'=>'edit','onclick'=>'');
		echo anchor('admin/manage_users/status/'.$user_id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);

		$value=array('class'=>'delete','onclick'=>'return delcon();');
		echo anchor('admin/manage_users/delete/'.$user_id,'<i class="fa fa-trash-o" title="delete"></i>',$value);
		//	echo "Change Status";

		echo "</td>";
		echo "</tr>";
		}
		?>
			</tbody>
		</table>
      <?php
	  echo form_close();
	  } } ?>
    <?php if($action== "edit"){?>
     <div class="row">
	 <div class="col-lg-6">
		<form method="post" id="edit_users" action="<?php echo lang_url(); ?>admin/manage_users/edit">
		<span class="error"><?php echo validation_errors();?></span>
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Email</label>
				<input type="email" id="email_address" class="form-control" name="email_address" value="<?php echo $user->email_address ?>">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>Property Name</label>
				<input type="text" class="form-control" name="property_name" id="property_name" value="<?php echo $user->property_name; ?>">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Mobile</label>
				<input type="text" class="form-control" name="mobile" value="<?php echo $user->mobile; ?>">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Name</label>
				<input type="text" class="form-control" name="user_name" id="username" value="<?php echo $user->user_name; ?>">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>Website</label>
				<input class="form-control" required name="website" type="url" value="<?php echo $user->web_site; ?>"/>
			</div>
			
			 <div class="form-group">
				<label><font color="#CC0000">*</font>First Name</label>
				<input class="form-control" required name="first_name" type="text" value="<?php echo $user->fname; ?>"/>
				
			</div>
			 <div class="form-group">
				<label><font color="#CC0000">*</font>Last Name</label>
				<input class="form-control" required name="last_name" type="text" value="<?php echo $user->lname; ?>"/>
				<input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
			</div>
			
			<button type="submit" name="save" value="save" class="btn btn-success">Save Changes</button>
			<button type="reset" class="btn btn-danger">Reset</button>
	   </form>
	</div>
	
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
        <!-- /.panel-body -->
      <?php
      }
      ?>
       <?php if($action== "add"){?>
         <div class="panel-body">
                        <div class="row">
                                <div class="col-lg-1">
                </div>
               <div class="col-lg-6">
		<form method="post" id="add_users" name="add_users" action="<?php echo lang_url(); ?>admin/manage_users/add">
		<span class="error"><?php echo validation_errors();?></span>
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Email</label>
				<input type="email" class="form-control" name="email_address" id="email_address" value="">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>Property Name</label>
				<input type="text" class="form-control" name="property_name" id="property_name" value="">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Mobile</label>
				<input type="text" class="form-control" name="mobile" value="">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Name</label>
				<input type="text" class="form-control" name="user_name" id="username" value="">
			</div>
			
			<div class="form-group">
				<label><font color="#CC0000">*</font>User Password</label>
				<input class="form-control" name="user_password" type="password" value=""/>
				
			</div>
			
			 <div class="form-group">
				<label><font color="#CC0000">*</font>Website</label>
				<input class="form-control"  name="website" type="url" value=""/>
			</div>
			
			 <div class="form-group">
				<label><font color="#CC0000">*</font>First Name</label>
				<input class="form-control" name="first_name" type="text" value=""/>
				
			</div>
			 <div class="form-group">
				<label><font color="#CC0000">*</font>Last Name</label>
				<input class="form-control" name="last_name" required type="text" value=""/>
				
			</div>
			<button type="submit" name="save" value="save" class="btn btn-success">Save</button>
			<button type="reset" class="btn btn-danger">Reset</button>
	  </form>
	</div>
                 <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
            <?php
            }
            ?>
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
<script>
function delcon()
{
var del=confirm("Are you sure want to delete");
if(del)
{
return true;
}
else
{
return false;
}
}



</script>
 <script>
 function delcon1()
{
	var del=confirm("Are you sure want Activate Multiproperty");
	if(del)
		{
		 return true;
		}
		else
		{
		 return false;
		}
}

 $(document).ready(function(){
  $("#addabout").validationEngine();
  $("#addabout1").validationEngine();
  });

function showimagepreview(input) {
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

<script type="text/javascript">

$("#edit_users").validate({
	rules: {
   user_name: {
				required: true,
				 remote: {
									  url:"<?php echo lang_url();?>channel/register_username_exists",
									  type: "post",
									  data: {
											  username: function()
											  {
												  return $("#username").val();
											  }
											}
									 },
			}, 

   user_password : {required: true},
   
   mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
							},
   
   email_address: {
							required: true,
							customemail:true,
						 remote: {
										  url:"<?php echo lang_url();?>channel/register_email_exists",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#email_address").val(); 
													}
												  }
										}
						}, 

   property_name: {
			   required: true,

			  remote: {
						 url:"<?php echo lang_url();?>channel/register_property_exists",

						 type: "post",

						 data: {
								 username: function()
								 {
									 return $("#property_name").val();

								 }

							   }

					  }, 

		   },

   website : {required: true},

   first_name : {required: true},

   last_name:{required: true},
},

errorPlacement: function(){

return false;  // suppresses error message text

},
highlight: function (element) { // hightlight error inputs

   $(element)

	   .closest('.nf-select').addClass('customErrorClass'); // set error class to the control group

   $(element)

	   .closest('.form-control').addClass('customErrorClass');

	   $(element)

	   .closest('.check-n').addClass('customErrorClass');

	   $(element)

	   .closest('.accept-n').addClass('customErrorClass');
},

unhighlight: function (element) { // revert the change done by hightlight

   $(element)

	   .closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group

   $(element)

	   .closest('.form-control').removeClass('customErrorClass');

	   $(element)

	   .closest('.check-n').removeClass('customErrorClass');

	   $(element)

	   .closest('.accept-n').removeClass('customErrorClass');
},
});

 $(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});

$('#gc_check_all').click(function(event) 
{ 
	if(this.checked)
	{ 
		$(".gc_check").each(function()
		{ 
			this.checked = true;
		});
	}
	else
	{
		$('.gc_check').each(function() 
		{
			this.checked = false; 
		});        
	}
});

function submit_form()
{
	if($(".gc_check:checked").length > 0)
	{
		return confirm('Are u sure want to delete the users?');
	}
	else
	{
		alert('No users selected to delete!!!');
		return false;
	}
}

</script>    	