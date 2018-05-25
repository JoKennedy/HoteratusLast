<?php $this->load->view('admin/common/header'); ?>
<body>

<?php $this->load->view('admin/common/menu'); ?>
   <div id="page-wrapper">
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
                    <h1 class="page-header">Admin Profile</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Admin Details
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php echo form_open_multipart('admin/profile_updated'); ?>
									<span class="error"><?php echo validation_errors();?></span>
                                        <div class="form-group">
                                            <label>Login-Name</label>
                                            <input class="form-control" name="login_name" value="<?php if(isset($login_name)){
											echo $login_name; }
											//echo set_value('login_name');
											?>">
											
                                        </div>
										
										<input type="hidden" name="id" value="<?php echo $login_name;?>  " />
										
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" name="password" type="password" value="<?php if(isset($password)){
											echo $password; }
											//echo set_value('password'); ?>"/>
											
                                            </div>
										<div class="form-group">
                                            <label>Name</label>
                                            <input class="form-control" name="name" value="<?php if(isset($name)){
											echo $name; }
											//echo set_value('name');
										?>"/>
											
                                         </div>
										<div class="form-group">
                                            <label>Email-id</label>
                                            <input class="form-control"  name="email_id" value="<?php if(isset($email_id)){
											echo $email_id; }
											//echo set_value('email_id'); ?>"/>
                                          
                                        </div>
                                        <div class="form-group">
                                            <label>Admin-Image</label>
											 <input type="file" name="admin_image" value="<?php if(isset($file)){
										echo $admin_image; }
										//echo set_value('admin_image'); 
										?>"/>
										<img src="<?php echo base_url();?>uploads/<?php echo $admin_image;?>" width="75px"/>
                                        </div>
                                        
										<div class="form-group">
                                            <label>Status</label>
                                            <select name="status" id="status">
    <option value="Active"<?php if ($status === 'Active'){?> selected="selected"<?php }?>>Active</option>
     <option value="DeActive"<?php if ($status === 'DeActive'){?> selected="selected"<?php }?>>DeActive</option>
  </select>
                                        </div>
                                       
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    <?php echo form_close(); ?>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <?php //$this->load->view('admin/common/script'); ?>

