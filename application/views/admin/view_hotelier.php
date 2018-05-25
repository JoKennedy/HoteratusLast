<?php $this->load->view('admin/header');?>
<body>

  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Hotelier
  </div>
  </div>
<div class="manage">
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> User/Hotelier Details</h3>
    </div>
    <div class="panel-body">
   <?php
	foreach($user as $trow)
	{
	?>
     <div class="col-md-6">
     <h3 class="text-success"> 	<u> User / Hotelier Account Information </u></h3>
     <br>
     <div class="row">  
   
        <div class="col-lg-3 text-center">
         <strong> First Name :</strong>
        </div>
        <div class="col-lg-4 text-centers">
            <?php echo $trow->fname;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>Last Name :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $trow->lname;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>Town :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $trow->town;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>Address :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $trow->address;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>ZIP Code :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $trow->zip_code;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Mobile :</strong>  
        </div>
        <div class="col-lg-4 text-centers">
           <?php echo $trow->mobile;?>
        </div>

     </div> <br>
     
     <!--<div class="row">
        <div class="col-lg-3 text-center">
          <strong>Property Name :</strong>
        </div>
        <div class="col-lg-4 text-centers">
           <?php echo $trow->property_name;?>
        </div>
        
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Url :</strong>
        </div>
        <div class="col-lg-4 text-centers">
           <a href="<?php echo $trow->web_site;?>" target="_blank"><?php echo $trow->web_site;?></a>
        </div>
        
     </div> <br>-->
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Email Id :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $trow->email_address;?>
        </div>
     </div> <br>
      
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Currency :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php $curr = get_data(TBL_CUR,array('currency_id'=>$trow->currency))->row(); echo $curr->currency_name;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Country :</strong>
        </div>
        <div class="col-lg-4 text-centers">
        <?php if($trow->country){ ?>
        <?php echo get_data(TBL_COUNTRY,array('id'=>$trow->country))->row()->country_name;?>
        <?php } ?>
        </div>
     </div> <br>
     

   	 </div>
     <?php if($trow->multiproperty == "Active"){ ?>
     <?php 
        $this->db->where("user_id",$trow->user_id);
        $this->db->where("buy_plan_account",2);
        $this->db->where("plan_status",1);
        $multi_plan = $this->db->get("user_membership_plan_details");
     ?>
       <div class="col-md-6">
     <h3 class="text-danger">   <u> User / Hotelier Package Information </u></h3>
     <br>
     <form method="post" name="change_pass" id="change_pass" action="<?php echo lang_url();?>admin/user_change_plan">
        <?php $user_id=$this->admin_model->encryptIt($trow->user_id); ?>

     <input type="hidden" value="<?php echo $user_id;?>" name="user_id">

      <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Plan Types :</strong>
        </div>
        
        <div class="col-lg-4 text-centers">

             <?php   if($multi_plan->num_rows != 0) { echo $multi_plan->row()->buy_plan_type; } else { echo 'No plan types active';}?>
        </div>
     </div> <br>
        
     <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Plan Name :</strong>
        </div>
        <div class="col-lg-4 text-centers">
              <?php  if($multi_plan->num_rows != 0) { echo $multi_plan->row()->buy_plan_type; } else { echo '-----';}?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Currency :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php if($multi_plan->num_rows != 0) { $curr = get_data(TBL_CUR,array('currency_id'=>$multi_plan->row()->buy_plan_currency))->row(); echo $curr->currency_name;} else { echo '-----';}?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Plan Duration :</strong>
        </div>
        <div class="col-lg-5 text-centers">
             <?php if($multi_plan->num_rows != 0) {  echo $multi_plan->row()->plan_from.' - '.$multi_plan->row()->plan_to;} else { echo '-----';}?>
        </div>
     </div> <br>


     
     <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Plan Price :</strong>
        </div>
        <div class="col-lg-5 text-centers">
             <?php if($multi_plan->num_rows != 0) {  echo $multi_plan->row()->buy_plan_price.' '.$curr->symbol;} else { echo '-----';}?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-4 text-center">
          <strong>Change plan :</strong>
        </div>
        <div class="col-lg-8 text-centers">
        <?php 
        $this->db->where("base_plan", 2);
        $this->db->order_by('plan_id','desc');
        $all_plan = get_data(TBL_PLAN)->result_array();
        
        ?>
            <select name="change_plan" class="form-control" required>
            <option value="" > Select Plan</option>
            <?php 
            if(count($all_plan)!=0)
            {
                foreach($all_plan as $value)
                {
                    extract($value);
            ?>
                <?php if($multi_plan->num_rows != 0){ ?>
                  <option value="<?php echo $plan_id;?>" <?php if($multi_plan->row()->buy_plan_id == $plan_id) { ?> selected <?php } ?>> <?php echo $plan_name;?> </option>
                <?php }else{ ?>
                   <option value="<?php echo $plan_id;?>"> <?php echo $plan_name;?> </option> 
                <?php } ?>
            <?php 
                }
            }
            ?>
                  </select>
        </div>
     </div> <br>
    
     <div class="row">
         <div class="form-group">
         <div class="col-md-12">
         <div class="col-md-3">
         </div>
          <div class="col-md-9">
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="<?php echo lang_url();?>admin/manage_users/view"> <button type="button" class="btn btn-danger">Cancel</button> </a>
       </div>
        </div>
        </div>
    </div>
                                        
     </form>
     
     </div> 
     <?php } ?>

   	<?php
	}
	?>
       
    </div>
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

/*$('#change_pass').submit(function(e){

    e.preventDefault();
alert('sds');
    if(!$("#change_pass").validationEngine('validate') == true)
    {
		var body = $("html");
				body.animate({scrollTop:0}, function() { 
				})

      return false;
    }
	else
	{
		document.change_pass.submit();
	}
	});*/
	
	$('#change_pass').validate({
		rules :
		{
			newpass:
			{
				/*minlength: 6,
				maxlength: 8,*/
				required: true,
			},
			conpass:
			{
				required: true,
				equalTo: "#newpass",
			}
		},
		messages:
		{
			oldpass:
			{
				required : "Enter your old password.",
				remote	 : "Old password does't match."
			},
		},
		errorPlacement: function(){
            return false;  // suppresses error message text
        },
		highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					$(element)
						.closest('.custom').addClass('customErrorClass');
                },
		unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					$(element)
						.closest('.custom').removeClass('customErrorClass');
						},
		/*submitHandler: function(form)
		{
			var data=$("#changepassword").serialize();
			//alert(data);
			$.ajax({
			type: "POST",
			url: '<?php //echo base_url();?>user/basics/ChangePassword',
			data: data,
			 beforeSend:function ()
	 {
	   $("#heading_loader").show();
	 },
			success: function(html)
			{
		$("#heading_loader").hide();	
			  if(html==1)
			  {
				$("div.clslogin_Box").modal("hide");				   
				//location.reload();
				$('#add_cc').html('<div class="alert alert-success" id="login_error">  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Your password has been updated successfully. </div>');
				location.reload();
				//document.location="<?php //echo base_url()?>user/user_profile";				
			  }
			  else if(html=2)
			  {
				  $('#add_ccc').html('<div class="alert alert-danger" id="login_error">  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Password update error. </div>');
			  }
		   }
	});
	}*/
	});
</script>

				