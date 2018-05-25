<?php $this->load->view('admin/common/header'); ?>
<body>

<?php $this->load->view('admin/common/menu'); ?>
   
	   
	     <div id="page-wrapper">
            
            <!-- /.row -->
           
            <div class="row">
               
                <!-- /.col-lg-6 -->
                <div class="col-lg-50">
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
									<br/>
									<a href="<?php echo base_url(); ?>index.php/admin/admin_registered/"><button type="button" style="width: 157px; height: 33px; margin-top: -8px;" class="pull-right label label-primary">Add New Record</button></a><br/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Manage Admin
                        </div>
						<?php
						static $flag=0;
						?>
                        <!-- /.panel-heading -->
						 
						 
						 
						 
                        <div class="panel-body">
						
                            <div class="table-responsive">
							
							<?php echo form_open('admin/admin_setting_updated'); ?>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
											<th>S.No.</th>
                                            <th>Admin</th>
                                            <th>Password</th>
                                            <th>Admin Roles</th>   
											<th>Country</th>
											<th>City</th>                                         
											<th>Status</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											$i=0;
							  foreach($records as $row)
							   {								
								echo "<tr>";
								echo "<td>";
								echo $i=$i+1;
								echo "</td>";
								echo "<td>";
								?>
								<img src="<?php echo base_url();?>uploads/<?php echo $row->admin_image;?>" width="50" height="50" style="float:left"/>
								<?php
								echo $row->login_name;
								echo "<br/>";
								echo $row->email_id;
								echo "</td>";
								echo "<td>";
								echo $row->password;
								echo "</td>";
								echo "<td>";
								if(($row->admin_controlid)==1){echo "SuperAdmin";}
								else if(($row->admin_controlid)==2){echo "Admin";}
								else {echo "SupportAdmin";}
								echo "</td>";
								echo "<td>";
								echo $row->country;
								echo "</td>";
								echo "<td>";
								echo $row->city;
								echo "</td>";
								echo "<td>";
								echo $row->status;
								echo "</td>";
								echo "<td>";
								
				$value=array('class'=>'edit');
				echo anchor('admin/manage_admin_details_update_view/'.$row->id,'<i class="fa fa-pencil" title="edit"></i>',$value);
				
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/deleteadmin/'.$row->id,'<i class="fa fa-times" title="delete"></i>',$value);
				
				            
				$value=array('class'=>'edit','onclick'=>'');
				echo anchor('admin/status_change/'.$row->id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
			//	echo "Change Status";
				
				
								echo "</td>";
								echo "</tr>";
								}
								?>
                                    </tbody>
                                </table>
								
                            </div>
                            <!-- /.table-responsive -->							
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
              
						<?php
								//$value=array('class'=>'edit');
				//echo anchor('admin/admin_registered/','<i class="fa fa-pencil">Add new record</i>',$value); 
				?>
				
				
				
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

	   
<!-- jQuery Version 1.11.0 -->
   <?php $this->load->view('admin/common/script'); ?>