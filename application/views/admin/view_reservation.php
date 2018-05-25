<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?> 
   <div id="page-wrapper">
 <div class="container-fluid">       
  <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reservation</h1>
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
        <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Reservation Details</h3>
    </div>
    <div class="panel-body">
        
           
   <?php
	foreach($reservation as $trow)
	{
	?>
   	<div class="row">
        <div class="col-lg-3 text-center">
          Guest Name 
        </div>
        <div class="col-lg-4 text-center">
            <?php echo $trow->guest_name;?>
        </div>
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Family Name 
        </div>
        <div class="col-lg-4 text-center">
             <?php echo $trow->family_name;?>
        </div>
     </div>
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Email Id 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->email;?>
        </div>

     </div>
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
         Mobile Number
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->mobile;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Reservation Code 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->reservation_code;?>
        </div>
        
     </div>
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Reservation Room 
        </div>
        <div class="col-lg-4 text-center">
          <?php echo get_data(TBL_PROPERTY,array('property_id'=>$trow->room_id))->row()->property_name; ?>
        </div>
        
     </div> 
<br>
     <div class="row">
        <div class="col-lg-3 text-center">
         Start Date
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->start_date;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
         End Date
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->end_date;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
        Price
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->price;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
        Reservation Status
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $trow->reservation_status;?>
        </div>
        
     </div>
      <br>
      <div class="row">
      <div class="col-md-4">
       <a href="<?php echo lang_url();?>admin/manage_reservation/view"> <button type="button" class="btn btn-info pull-right"> Back </button> </a>
        </div>
        </div>
        
     </div>
   	<?php
	}
	?>
       
    </div>
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

				