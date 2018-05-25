<?php $this->load->view('admin/header');?>
<div class="breadcrumbs">
<div class="row-fluid clearfix">
<i class="fa fa-home"></i> Manage Admin
</div>
</div>
	 <div class="manage">
    <div class="row-fluid clearfix">


   <div  class="col-md-12">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-1"></div>
				<div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Add new Admin
                        </div>
                        <div class="panel-body">
                            <div class="row">
							<div class="col-lg-1">
							</div>
                                <div class="col-lg-6">
								 <form action="<?php echo lang_url(); ?>admin/addnew_admin_details" method="post" id="addadmin" name="addadmin" class="form form-horizontal" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Login-Name</label>
                                            <input class="form-control validate[required]" name="login_name" id="login_name" onChange="return chk_exist_LoginName(this.value);">	
											<font color="#F21515"><?php echo form_error('login_name'); ?></font>
											<div id="chk_exist_login_name"> 
											<font color="red"><div id="">Login-Name already exist</div></font> 
											</div>
                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Password</label>
                                            <input class="form-control validate[required]"  name="password" type="password" value=""/>
											<font color="#F21515"><?php echo form_error('password'); ?></font>			
                                            </div>
										<div class="form-group">
                                            <label><font color="#CC0000">*</font>Email-id</label>
                                            <input class="form-control validate[required,custom[email]]"  name="email_id" id="email_id" color="#F21515" value="" onChange="return chk_exist_email(this.value);"/>
												<font color="#F21515"><?php echo form_error('email_id'); ?></font>
												 <div id="chk_exist_mail"> <font color="red"><div id="">Mail id is already exist</div></font> </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Admin-Image</label>
										 <input name="admin_image" type="file" value=""onChange="showimagepreview(this)" />   
												  <br>
												  <div class="controls">
										<input type="hidden" name="hidimage" value="default_adminuser.jpg" >
											<img src="<?php echo base_url(); ?>uploads/admin_user/default_adminuser.jpg" class="img_profile" width="150px" height="150px">		<img class="img_profile_loading" src="<?php echo base_url(); ?>user_assets/loader/loading.gif" style="position:absolute; top:40px; left:0;left: 60px; height: 30px;display: none; " />
												  </div>
                                        </div>
										
                                            <label>Country<font color="#CC0000">*</font></label>
                                           	
                                         
										 <div class="form-group">
										 <select name="country" id="country" value="" class="form-control validate[required]">
											<option value="">---Select country---</option>
											<?php $country=$this->admin_model->get_country();?>
                                       <?php
										if($country)
										{
										foreach($country as $record)
										{
										if($record->country_status=="active"){
										?>									
									 <option value="<?php echo $record->country_name; ?>"><?php echo $record->country_name; ?> </option>
										<?php
										}
										}
										} 
										?>
										</select>
										 </div>
										 
										 <div class="form-group">
                                            <label>City<font color="#CC0000">*</font></label>
                                            <input class="form-control validate[required]" name="city" value=""/>
											<font color="#F21515"><?php echo form_error('city'); ?></font>
                                         </div>
                                        <div class="form-group">
                                            <label>Admin Role<font color="#CC0000">*</font></label>
                                            <select name="admin_role" id="status" class="form-control validate[required]">
											 <option value="1" selected="selected" >Admin</option>
											 <option value="2" >Sub-Admin</option>
											</select>
                                        </div>
										
                                      <button type="submit" name="save" value="save" class="btn btn-success">Save</button>
                                
								</form>
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
        <!-- /#page-wrapper -->
</div>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
	<?php $this->load->view('admin/footer');?>
   