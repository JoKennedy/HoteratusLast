<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
   
	   
	     <div id="page-wrapper">
  <div class="container-fluid">        <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Rooms On <?php echo ucfirst($property_name);?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
           
            <div class="row">
               
                <!-- /.col-lg-6 -->
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
             
              if($action== "view_single" || $action== "view") $title ="Manage Rooms" ;
              ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <?php echo $title; ?>
                        </div>
						<?php
						static $flag=0;
						?>
                        <!-- /.panel-heading -->
					<?php if($action== "view"){?>
                        <div class="panel-body">
                            <div class="table-responsive">
							<?php echo form_open('admin/admin_setting_updated'); ?>
                                <table class="table table-striped table-bordered table-hover table-full-width" id="sample_2">
                                <thead>
                                    <tr>
										<th>S.No.</th>
                                        <th>Property Name</th>
										<th>City</th>
                                        <th>Country</th>
							     		<th>Status</th>
										<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                              <?php
							  $i=0;
							  if($property){
							  foreach($property as $row)
							   {		
							   		$owner_id=$this->admin_model->encryptIt($row->owner_id);
									$hotel_id=$this->admin_model->encryptIt($row->hotel_id);
									$pass_id=$this->admin_model->encryptIt($row->property_id);
									$i++;
									$user = get_data(TBL_USERS,array('user_id'=>$row->owner_id))->row();
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><a href="<?php echo lang_url(); ?>index.php/admin/view_property_room/<?php echo $owner_id.'/'.$hotel_id.'/'.$pass_id; ?>" title="View Room" ><?php echo $row->property_name; ?> </a></td>
								
								<!--<td><?php //echo number_format((float)$row->price, 2, '.', '');?> </td>-->
								<td><?php echo $user->town; ?> </td>
								<!--<td><?php //if($row->pricing_type=='2') { echo 'Guest based pricing';}elseif($row->pricing_type=='1'){ echo 'Room based pricing';}?> </td>-->
                                <td> <?php echo get_data(TBL_COUNTRY,array('id'=>$user->country))->row()->country_name;?></td>
								<td> 
									<?php if($row->status=='Inactive') { echo '<span class="label label-sm label-danger">Inactive</span>';} else { echo '<span class="label label-sm label-success">Active</span>'; } ?>  </td>
								<?php
								echo "<td>";
								$pass_id=$this->admin_model->encryptIt($row->property_id);
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/manage_property_room/delete/'.$owner_id.'/'.$hotel_id.'/'.$pass_id,'<i class="fa fa-times" title="delete"></i>',$value);
				
				            
				$value=array('class'=>'edit','onclick'=>'');
				echo anchor('admin/manage_property_room/status/'.$owner_id.'/'.$hotel_id.'/'.$pass_id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
			//	echo "Change Status";
				
				
								echo "</td>";
								echo "</tr>";
								}
							  }/*else{
							  	echo "<tr><td colspan='9'>No records found</td></tr>";
							  }*/
								?>
                                    </tbody>
                                </table>
								
                            </div>
                            <!-- /.table-responsive -->							
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
<!--Can you please provide the what are the details to be edit. You should be login front end ( By using admin credentials ) then only the edit option provide for you.-->