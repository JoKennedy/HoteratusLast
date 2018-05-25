<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?> 
  <div id="page-wrapper">
 <div class="container-fluid">       
  <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">View Room</h1>
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
        <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Room Details</h3>
    </div>
    <div class="panel-body">
	<div class="row">
        <div class="col-lg-3 text-center">
          Hotel User Name 
        </div>
        <div class="col-lg-4 text-center">
        <?php $pass_id=$this->admin_model->encryptIt($owner_id);?>
           <a href="<?php echo lang_url();?>admin/view_hotelier/<?php echo $pass_id; ?>" target="_blank"> <?php 
			$user_details = get_data(TBL_USERS,array('user_id'=>$owner_id))->row();
			echo ucfirst($user_details->fname.' '.$user_details->lname);?>
            </a>
        </div>
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Hotel Name 
        </div>
        <div class="col-lg-4 text-center">
        <?php $pass_id=$this->admin_model->encryptIt($owner_id);?>
           <a href="<?php echo lang_url();?>admin/view_hotelier/<?php echo $pass_id; ?>" target="_blank"> <?php 
			$user_details = get_data(HOTEL,array('owner_id'=>$owner_id,'hotel_id'=>$hotel_id))->row();
			echo ucfirst($user_details->property_name);?>
            </a>
        </div>
     </div>
     <br>
   	<div class="row">
        <div class="col-lg-3 text-center">
          Room Name 
        </div>
        <div class="col-lg-4 text-center">
            <?php echo $property_name;?>
        </div>
     </div>
     <!--<br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Room Type 
        </div>
        <div class="col-lg-4 text-center">
             <?php //echo $property_type;?>
        </div>
     </div>-->
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Room price 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $price;?>
        </div>

     </div>
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Room Capacity 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $existing_room_count;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Occupancy Adults 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $member_count;?>
        </div>
        
     </div>
     <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Occupancy Children 
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $children;?>
        </div>
        
     </div>
      <br>
     <div class="row">
        <div class="col-lg-3 text-center">
          Pricing Type 
        </div>
        <div class="col-lg-4 text-center">
           <?php if($pricing_type=='2') { echo 'Guest based pricing';}elseif($pricing_type=='1'){ echo 'Room based pricing';}?>
        </div>
        
     </div> 
<br>
<div class="row">
        <div class="col-lg-3 text-center">
          Selling Period
        </div>
        <div class="col-lg-4 text-center">
           <?php if($selling_period=='3') { echo 'Monthly';}else if($selling_period=='2') { echo 'Weekly';}elseif($selling_period=='1'){ echo 'Daily';}?>
        </div>
        
     </div> 
<br>
     <div class="row">
        <div class="col-lg-3 text-center">
         Connected Channel
        </div>
        <div class="col-lg-4 text-center">
           <?php
		  
           if($connected_channel!='')
		   { 
		   
		   		$channels = explode(',',$connected_channel);
				$name='';
				foreach($channels as $cha)
				{
					if($cha==0)
					{
						
						$name =  'No channels connected';	
					}
					else
					{
						  $name.=get_data(TBL_CHANNEL,array('channel_id'=>$cha))->row()->channel_name.' , ';
					}
				}
			}
			else
			{
				
				$name =  'No channels connected';
			}
			echo trim($name,' , ');?>
        </div>
        
     </div>
 <br>    
     <div class="row">
        <div class="col-lg-3 text-center">
          Description
        </div>
        <div class="col-lg-4 text-center">
           <?php echo $description?>
        </div>
        
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

				