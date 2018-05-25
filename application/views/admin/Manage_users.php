<?php $this->load->view('admin/common/header'); ?>
<body>

<?php $this->load->view('admin/common/menu'); ?>
   
	   
	     <div id="page-wrapper">
            <div class="container-fluid">
           <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Hotelier</h1>
					<?php if(uri(4)=="view"){?>
					<div class="panel-heading"><a href="<?php echo lang_url(); ?>index.php/manage_user/update/"><button type="button" style="width: 157px; height: 33px; margin-top: -8px; float:left;" class="pull-right label label-success">Add New <i class="fa fa-plus"></i></button></a>
					<?php } ?>
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Manage Admin</h3>
                            </div>
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
              if($action== "add") $title ="Add Hotelier" ;
              else if($action== "edit") $title ="Edit Hotelier" ;
              else if($action== "view_single" || $action== "view") $title ="View Hotelier" ;
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
                               <table class="table table-striped table-bordered table-hover table-full-width" id="sample_2"><strong></strong>
                                    <thead>
                                        <tr>
											<th>S.No.</th>
                                            <th>Username</th>
                                            <th>Email</th>
											<!--<th>Country</th>-->
											<th>Status</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											$i=0;
							  foreach($users as $row)
							   {		
							   	$pass_id=$this->admin_model->encryptIt($row->user_id);
									$i++;
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><?php echo $row->user_name; ?> </td>
								<td><a href="<?php echo lang_url(); ?>index.php/admin/view_hotelier/<?php echo $pass_id; ?>" title="View Template" ><?php echo $row->email_address; ?> </a></td>
								<?php
								// $country_detail	=$this->admin_model->fetchcountry();	
		// if($country_detail)
		// {
		// foreach($country_detail as $country_detail)
			// {
				// $countryid 		=	$country_detail->id;
				// $countryname	=	$country_detail->country_name;
			// }}	?>
								<!--<td><?php //echo $row->country; ?> </td>-->
								<td><?php if($row->status==1){ echo '<span class="label label-sm label-success">Active</span>'; } else { echo '<span class="label label-sm label-danger">De-active</span>'; } ?> </td>
								<?php
								echo "<td>";
								$user_id = $row->user_id;
							// $pass_id=$this->admin_model->encryptIt($row->user_id);
							
				$value=array('class'=>'edit');
				echo anchor('admin/manage_user/edit/'.$user_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';
						
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/manage_user/delete/'.$user_id,'<i class="fa fa-times" title="delete"></i>',$value);
				
				            
				$value=array('class'=>'edit','onclick'=>'');
				echo anchor('admin/manage_user/status/'.$user_id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
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
						<?php
						}
						?>
						 <?php if($action== "edit"){?>
						 <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php echo form_open_multipart('admin/manage_user/update/'); ?>
									<span class="error"><?php echo validation_errors();?></span>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>User Email</label>
                                            <input type="email" class="form-control" name="login_name" value="<?php if(isset($email_address)){
											echo $email_address; }	?>">
											
											<!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>User Name</label>
                                            <input class="form-control" required name="user_name" type="user_name" value="<?php if(isset($user_name)){
											echo $user_name; }
											//echo set_value('password'); ?>"/>
											
                                        </div>
										
                                        <button type="submit" name="save" value="save" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger" type="reset">Reset</button>
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
						
						 <?php if($action== "add"){?>
						 <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php echo form_open_multipart('admin/manage_user/update/'); ?>
									<span class="error"><?php echo validation_errors();?></span>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>User Email</label>
                                            <input type="email" class="form-control" name="login_name" value="">

                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>User Name</label>
                                            <input class="form-control" required name="user_name" type="user_name" value="">
											
                                        </div>
										
                                        <button type="submit" name="save" value="save" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger" type="reset">Reset</button>
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

	   
