<?php $this->load->view('admin/header');?>
<div class="breadcrumbs">
<div class="row-fluid clearfix">
<i class="fa fa-home"></i> Manage Admin
</div>
</div>
	 <div class="manage">
    <div class="row-fluid clearfix">
<div  class="col-md-12">
            
            <div class="row">
			<div class="col-lg-1">
			</div>
                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Admin Details
                        </div>
                        <div class="panel-body">
                            <div class="row">
							    <div class="col-lg-1">
								</div>
                                <div class="col-lg-6">
								  <?php $attributes=array('class'=>'form form-horizontal','id'=>'editadmin','name'=>'editadmin');
                                  echo form_open_multipart('admin/update_admin/'.$id,$attributes); ?>
									<!--<span class="error"><?php echo validation_errors();?></span>-->
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Login-Name</label>
                                            <input class="form-control validate[required]" name="login_name" value="<?php
											echo $login_name; ?>">
											<font color="#F21515"><?php echo form_error('login_name'); ?></font>
										 </div>
                                        <div class="form-group">
                                            <label>Current-Password</label>
                                            <input class="form-control" name="password" type="password" value="" onChange="return chk_current_pwd(this.value,<?php echo $id;?>);" id="current"/>
											<font color="#F21515"><?php echo form_error('password'); ?></font>
                                            
                                            <div id="chk_current_pwd"> <font color="green"><div id="">Password Match</div></font></div>
                                             <div id="chk_current_pwd1"> <font color="red"><div id="">Current passwpord does not match</div></font></div>
                                            										
										</div>
                                        <div id="change_pass">
											<div class="form-group">
                                            <label>New Password</label>
                                            <input class="form-control" name="newpassword" type="password" id="newpassword" />
											<font color="#F21515"><?php echo form_error('newpassword'); ?></font>
										    </div>
											<div class="form-group">
                                            <label>Confirm-Password</label>
                                            <input class="form-control validate[equals[newpassword]]" name="cnfpassword" type="password"  id="cnfpassword"/>
											<font color="#F21515"><?php echo form_error('cnfpassword'); ?></font>
										    </div>
                                            </div>
										<div class="form-group">
                                            <label><font color="#CC0000">*</font>Email-id</label>
                                            <input class="form-control validate[required,custom[email]]"  name="email_id" id="email_id" value="<?php echo $email_id;?>" onChange="return chk_exist_email(this.value);"/>
											<font color="#F21515"><?php echo form_error('email_id'); ?></font>
                                            <div id="chk_exist_mail"> <font color="red"><div id="">Mail id is already exist</div></font></div>
										 </div>
                                        <div class="form-group">
                                            <label>Admin-Image</label>
											 <input type="file" onChange="showimagepreview(this)" name="admin_image" value="<?php 
											 //if(isset($file)){
										echo $admin_image; //}
										//echo set_value('admin_image'); 
										?>"/>
										<img class="img_profile" width="150px" height="150px" src="<?php echo base_url();?>uploads/<?php echo $admin_image;?>"/>
										
                                        </div>
                                       <div class="form-group">
                                            <label>Admin Role</label>
                                            <select name="admin_controlid" id="admin_controlid" class="form-control">
    										<option value="1"<?php if ($admin_controlid === '1'){?> selected="selected"<?php }?>> Admin </option>
										    <option value="2"<?php if ($admin_controlid === '2'){?> selected="selected"<?php }?>> Sub-Admin </option>
											</select>
                                        </div>										
										<div class="form-group">
                                            <label>Country</label>
                                            <select name="country" id="country" value="" class="form-control validate[required]">
										<?php $country1=$this->admin_model->get_country();?>
                                       <?php
										foreach($country1 as $record)
										{
										?>									
									 <option <?php if($record->country_name==$country){ echo 'selected="selected"';}?> value="<?php echo $record->country_name; ?>"><?php echo $record->country_name; ?> </option>
										<?php
										}
										
										?>
										</select>                                        
                                        </div>
										<div class="form-group">
                                            <label>City</label>
                                            <input class="form-control validate[required]"  name="city" value="<?php echo $city;?>"/>  
											<font color="#F25151"><?php echo  form_error('city'); ?></font>                                        
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
			</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
<?php $this->load->view('admin/footer');?>