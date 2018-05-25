<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
	     <div id="page-wrapper">
	     <div class="container-fluid">       
	      <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Channel Plan</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                <div class="col-lg-12">
				<?php 	  
									if(isset($error)){	?> 
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
						static $flag=0;
						?>
                        <!-- /.panel-heading -->
					<?php if($action== "view"){?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Manage Channel</h3>
                            </div>
                            <div class="panel-body">
							    <div class="table-responsive">
                                     <table class="table table-striped table table-striped table-bordered dTableR" id="dt_d">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
                                            <th>Plan Name</th>
                                            <th>Currency</th>
                                          
											<!--<th>Price</th>-->
											<th>Status</th>
											<th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <tbody>
                                        <?php
										
										
											$i=0;
							   foreach($plan as $row)
							   {
								   extract($row);		
									$i++;
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><?php  echo $channel_plan; ?> </td>
                                <td><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_name;?></td>
				      			
								<td><?php if($status==1){ echo '<span class="label label-sm label-success">Active</span>'; } else { echo '<span class="label label-sm label-danger">De-active</span>'; } ?> </td>
								<?php
								echo "<td>";
				$value=array('class'=>'edit');
				echo anchor('admin/channel_plan/edit/'.$channel_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';
				
				
					$value=array('class'=>'status');
					echo anchor('admin/channel_plan/status/'.$channel_id,'<i class="fa fa-gear" title="Change Status"></i>',$value);
					echo "</td>";
					echo "</tr>";
					}
					?>
                        </tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


		
        <!-- /.panel-body -->
		<?php
		}
		?>
		 <?php if($action== "edit"){?>
	   <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
			  <div class="col-lg-1"></div>
			  <div class="col-lg-6">
                    <?php echo form_open_multipart('admin/channel_plan/update/'.$channel_id); ?>
					<span class="error"><?php echo validation_errors();?></span>
                            <div class="form-group">
                                <label><font color="#CC0000">*</font>channel Name</label> &nbsp; :
                                
                           
                                 <?php echo $channel_plan;?>
                                 <!--<input type="radio" value="Free" name="plan_types" <?php //if($plan_types=="Free"){?> checked <?php //} ?>> Free 
                                 <input type="radio" value="Month" name="plan_types" <?php //if($plan_types=="Month"){?> checked <?php// } ?>> Month 
                                 <input type="radio" value="Year" name="plan_types" <?php //if($plan_types=="Year"){?> checked <?php //} ?>> Year -->
                                 
                                <!--<input class="form-control" required name="Plan_name" value="<?php //if(isset($plan_name)){echo $plan_name; }?>" type="text" >-->
                                
                            </div>
							
							  <div class="form-group">
                                <label><font color="#CC0000">*</font>channel Type</label> &nbsp; :
                                
                           
                                 <?php echo $channel_type;?>
                                
                            </div>
							
							<div class="form-group">
							
                                <label><font color="#CC0000">*</font>channel Price:</label> 
                                 <input type="text" class="form-control" name="channel_price" value="<?php echo $channel_price; ?>">
                                
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
                            <label><font color="#CC0000">*</font>Currency:</label>
                            <select class="form-control" name="currency" required>
							
                            <?php 
								
								$currency_details = get_data(TBL_CUR)->result_array();
								foreach($currency_details as $curr) {
									extract($curr);
							?>
<option value="<?php echo $currency_id;?>" <?php if($currency_id==$currency) { ?> selected <?php } ?> <?php if(in_array($currency_id,$al_plan)){?> disabled <?php } ?>> <?php echo $currency_name;?></option>
                            <?php } ?>
                            </select>
                      
                           
							
                            </div>    
                         
                        
														
					 <div class="form-group">
							
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
		
    <!-- /.row -->
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
<script>
$(document).ready(function(){
	$("#addservice").validationEngine();
	$('#plan_name').hide();
	
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
