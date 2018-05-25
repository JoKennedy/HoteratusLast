<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?> 
  <div id="page-wrapper">
 <div class="container-fluid">       
  <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">View Property</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
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
        <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Property Details</h3>
    </div>
    <div class="panel-body">
	 <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Property User Name :</strong>
        </div>
        <div class="col-lg-4 text-centers">
        <?php $pass_id=$this->admin_model->encryptIt($owner_id);?>
           <a href="<?php echo lang_url();?>admin/view_hotelier/<?php echo $pass_id; ?>" target="_blank"> <?php 
			$user_details = get_data(TBL_USERS,array('user_id'=>$owner_id))->row();
			echo ucfirst($user_details->fname.' '.$user_details->lname);?>
            </a>
        </div>
     </div> <br>
     
   	 <div class="row">
        <div class="col-lg-3 text-center">
         <strong> Hotel Name : </strong>
        </div>
        <div class="col-lg-4 text-centers">
            <?php echo $property_name;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>Town :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $town;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>Address :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $address;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
         <strong>ZIP Code :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $zip_code;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Mobile :</strong>  
        </div>
        <div class="col-lg-4 text-centers">
           <?php echo $mobile;?>
        </div>

     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Email Id :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <?php echo $email_address;?>
        </div>
     </div> <br>
     
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Url :</strong>
        </div>
        <div class="col-lg-4 text-centers">
             <a href="<?php echo $web_site?>" target="_blank"> <?php echo $web_site;?> </a>
        </div>
     </div> <br>
       	
     <div class="row">
        <div class="col-lg-3 text-center">
          <strong>Country :</strong>
        </div>
        <div class="col-lg-4 text-centers">
           <?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?>
        </div>
     </div> <br>
    

     
     

       
    </div>
</div>
</div>
</div>
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

				