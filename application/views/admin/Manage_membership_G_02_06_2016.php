<?php $this->load->view('admin/header');?>
<body>

	    <!--  <div id="page-wrapper">
	     <div class="container-fluid">        -->
<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Membership
  </div>
  </div>

	      <!-- <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Membership</h1>
                </div>
                
            </div> -->
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

					<?php 
          if($action== "add") $title ="Add Membership " ;
          else if($action== "edit") $title ="Edit Membership " ;
          else if($action== "view_single" || $action== "view") $title ="View Membership ";
        ?>
			
                    <div class="panel panel-default">
                        <?php if(uri(3)=='view'){?>
						   <a href="<?php echo base_url(); ?>admin/membership/add"><button type="button" style="width: 157px; height: 33px; margin-top: -8px;" class="pull-right label label-primary">Add Membership</button></a>
						<?php } ?>
                    </div>
					<div class="col-md-12">
					<div class="table-responsive">
					<div class="cls_box">
					<h4><?php echo $title;?></h4>
					<br><br>

					<?php static $flag=0; ?>

					<?php if($action== "view"){ ?>

                        <div class="panel panel-default">
                            
                            <div class="panel-body">
							    <div class="table-responsive">
				<!-- <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0"> -->
                <table class="display table table-hover table-bordered" id="example" cellspacing="0" style="width:100%">

                <thead>
                        <tr class="top-tr">
                        <th>S.No.</th>
                        <th>Plan Name</th>
                        <th>Currency</th>
                        <th>Duration</th>
						<th>Price</th>
						<th>Status</th>
						<th>Action</th>
                        </tr>
                </thead>
                                       
      			<tbody>
                    <?php $i=0;
					foreach($plan as $row)
					   {
						   extract($row);		
							$i++;
						?>
						<tr>
						<td><?php echo $i; ?> </td>
						<td><?php if($plan_types=="Free") { echo $plan_price.' '.$plan_name;} else { echo $plan_name; } ?> </td>
			            <td><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_name;?></td>
						<td><?php echo $plan_types;   ?> </td>
			  			<td><?php if($plan_types=="Free") { echo 'Trial';} else { echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol.$plan_price;} ?> </td>
						<td><?php if($status==1){ echo '<span class="label label-sm label-success">Active</span>'; } else { echo '<span class="label label-sm label-danger">De-active</span>'; } ?> </td>
						<?php
						echo "<td>";
			$value=array('class'=>'edit');
			echo anchor('admin/membership/edit/'.$plan_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';

			/*$value=array('class'=>'delete','onclick'=>'return delcon();');
			echo anchor('admin/membership/delete/'.$plan_id,'<i class="fa fa-times" title="delete"></i>',$value).'&nbsp;';*/
			$value=array('class'=>'status');
			echo anchor('admin/membership/status/'.$plan_id,'<i class="fa fa-gear" title="Change Status"></i>',$value);
			echo "</td>";
			echo "</tr>";
			}
			?>
    		</tbody>        
            </table>
            </div>
        </div>
    </div>
</div>	
        <!-- /.panel-body -->
	<?php } ?>

	<?php if($action== "edit"){ ?>
	   <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
			  <div class="col-lg-1"></div>
			  <div class="col-lg-6">
                    <?php echo form_open_multipart('admin/membership/update/'.$plan_id); ?>
					<span class="error"><?php echo validation_errors();?></span>
                            <div class="form-group">
                                <label><font color="#CC0000">*</font>Plan Type</label> &nbsp; :

                                 <?php //echo $plan_types;?>
                                 
                                  	<input type="radio" value="Free"  <?php if($plan_types=='Free'){ echo 'checked';}?> name="plan_types"  class="plan_name" required> Free 
									<input type="radio" value="Month" <?php if($plan_types=='Month'){ echo 'checked';}?> name="plan_types" class="plan_name" required> Month 
                             		<input type="radio" value="Year"  <?php if($plan_types=='Year'){ echo 'checked';}?>name="plan_types" class="plan_name" required> Year 
                                
                            </div>
                        
                        <div class="form-group">
                            <label><font color="#CC0000">*</font>Plan Name : </label>
                           <input class="form-control" required name="plan_name" type="text" value="<?php echo $plan_name; ?>"  id="plan_name"	>
                        </div>
                        
                        <?php  
							$cur_id =  $currency;
							$TBL_PLAN = get_data(TBL_PLAN)->result();
								$al_plan = array();
								foreach($TBL_PLAN as $plan) {
									if($cur_id!=$plan->currency)
									{
										$al_plan[] = $plan->currency;
									}
								}
								
								 ?> 	
                        <div class="form-group" id="currency">
                            <label><font color="#CC0000">*</font>Currency</label>
                            <select class="form-control" name="currency" required	>
                            <?php 
							
								
								$currency_details = get_data(TBL_CUR)->result_array();
								foreach($currency_details as $curr) {
									extract($curr);
							?>
<option value="<?php echo $currency_id;?>" <?php if($currency_id==$currency) { ?> selected <?php } ?>> <?php echo $currency_name;?></option>
                            <?php } ?>
                            </select>
                      
							
                            </div>    
                            
                            <div class="form-group" id="currency">
                            <label><font color="#CC0000">*</font>No.of Channels </label>
                            <select class="form-control" name="number_of_channels"  required	>
                            <?php 
							if($num_channels!=0)
							{
								for($nc=1;$nc<=$num_channels;$nc++)
								{
							?>
                            <option value="<?php echo $nc;?>"  <?php if($number_of_channels==$nc) { ?> selected <?php } ?>> <?php echo $nc;?></option>
                            <?php 
								}
							}
							else
							{
								?>
                                 <option value="0" selected> 0</option>
                                <?php
							}
							 ?>
                            </select>
                            </div>
							
					 <div class="form-group">
                            <label><font color="#CC0000">*</font> <span id="plan_price"><?php if($plan_types!='Free') { ?>  Price <?php } else { ?> No of days for free <?php } ?> </span></label>
                             <input class="form-control" required name="plan_price" value="<?php if(isset($plan_price)){echo $plan_price; }?>" type="double" min="1" max="30">
							
                            </div>
						
                        <button type="submit"  name="add_service" class="btn btn-success">Save Changes</button>
                    <?php echo form_close(); ?>
                </div>
                <!-- /.col-lg-6 (nested) -->
               
                <!-- /.col-lg-6 (nested) -->
            </div>
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
		<?php
		}
?>
			 <?php if($action== "add"){ ?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12"><div class="col-lg-1"></div>
                        <div class="col-lg-6">
                    <div class="row">
						<?php $attributes=array('class'=>'form form-horizontal','id'=>'addservice');
                         echo  form_open_multipart('admin/membership/add',$attributes); ?>   
						<span class="error"><?php echo validation_errors();?></span>
                        
                        <div class="form-group">
                            <label><font color="#CC0000">*</font>Plan Type</label> &nbsp; :
                             
                             <input type="radio" value="Free" name="plan_types"  class="plan_name" required> Free 
                             <input type="radio" value="Month" name="plan_types" class="plan_name" required> Month 
                             <input type="radio" value="Year" name="plan_types" class="plan_name" required> Year 
							
                        </div>
                        
						        <div class="form-group">
                                    <label><font color="#CC0000">*</font>Plan Name</label>
									
									<input class="form-control" required name="plan_name" type="text" id="plan_name">
									
							    </div>
                            <div class="form-group" id="currency">
                            <label><font color="#CC0000">*</font>Currency</label>
                            <select class="form-control" name="currency"  required	>
                            <?php 
								$TBL_PLAN = get_data(TBL_PLAN)->result();
								$al_plan = array();
								foreach($TBL_PLAN as $plan) {
									
									$al_plan[] = $plan->currency;
								}
								$currency_details = get_data(TBL_CUR)->result_array();
								foreach($currency_details as $curr) {
									extract($curr);
							?>
                            <option value="<?php echo $currency_id;?>"> <?php echo $currency_name;?></option>
                            <?php } ?>
                            </select>
                            </div>
                            
                            <div class="form-group" id="currency">
                            <label><font color="#CC0000">*</font>No.of Channels </label>
                            <select class="form-control" name="number_of_channels"  required	>
                            <?php 
							if($num_channels!=0)
							{
								for($nc=1;$nc<=$num_channels;$nc++)
								{
							?>
                            <option value="<?php echo $nc;?>"> <?php echo $nc;?></option>
                            <?php 
								}
							}
							else
							{
								?>
                                 <option value="0" selected> 0</option>
                                <?php
							}
							 ?>
                            </select>
                            </div>

                                  <div class="form-group" id="price_based">
                                    <label><font color="#CC0000" >*</font> <span id="plan_price"> Plan price </span></label>
                                  
									<input class="form-control" required name="plan_price" type="double" min="1">
                                  </div>
							
									
								 <button type="submit" name="add_service" class="btn btn-success" value="Add Services">Add Membership</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                            <?php echo form_close(); ?>
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
            <!-- /.panel -->
      
				
        <!-- /.col-lg-6 -->
    </div>
    <!-- /.row -->
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
</script>
<script>
$(document).ready(function(){
	$("#addservice").validationEngine();
	// $('#plan_name').hide();
	
	});
$(document).on('click','.plan_name',function()
{
	if(($(this).attr('value'))=='Free')
	{
		$('#plan_price').html('No of days for free');
		//$('#currency').hide();
	}
	else
	{
		$('#plan_price').html('Plan price');
		//$('#currency').show();
	}
	$('#plan_name').val($(this).attr('value')).show();
});

</script>
	   
<!-- jQuery Version 1.11.0 -->
