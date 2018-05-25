<?php $this->load->view('admin/common/header'); ?>

<body>

<?php $this->load->view('admin/common/menu'); ?>
 <div id="page-wrapper">
            
            <!-- /.row -->
           
            <div class="row">
               


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
									
									
									 <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                       <?php echo form_open_multipart('admin/team/add'); ?>
									<span class="error"><?php 
									//echo validation_errors();?></span>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Name</label>
                                            <input class="form-control" name="title" value="">
											<span class="error"><?php echo form_error('title');?></span>
											<!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Destination</label>
                                            <textarea class="form-control"   name="content" type="text" ></textarea>
											<span class="error"><?php echo form_error('content');?></span>
                                            </div>
											<div class="form-group">
                                            <label><font color="#CC0000">*</font> Image</label>
                                            <input  name="image" type="file" value="" onChange="showimagepreview(this)" />   
												  <span class="error"><?php echo form_error('image');?></span>
												  <div class="controls">
											<img src="<?php echo base_url(); ?>uploads/about/service.jpeg" class="img_profile" width="150px" height="150px">		<img class="img_profile_loading" src="<?php echo base_url(); ?>assets/img/loading.gif" style="position:absolute; top:40px; left:0;left: 60px; height: 30px;display: none; " />
												  </div>
                                            </div>
											 <div class="form-group">
                                            <label><font color="#CC0000">*</font>Facebook Url</label>
                                            <input class="form-control" name="facebook" value="" type="text" required >
											<span class="error"><?php echo form_error('facebook');?></span>
											<!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
										 <div class="form-group">
                                            <label><font color="#CC0000">*</font>Twitter Url</label>
                                            <input class="form-control" name="twitter" value="" type="text" required >
											<span class="error"><?php echo form_error('twitter');?></span>
											<!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
										 <div class="form-group">
                                            <label><font color="#CC0000">*</font>Linked In Url</label>
                                            <input class="form-control" name="linkedin" value="" type="text" required >
											<span class="error"><?php echo form_error('linkedin');?></span>
											<!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
										 <button type="submit" name="add_service" class="btn btn-success" value="add_service">ADD TEAMs</button>
                                        <button type="reset" class="btn btn-danger" type="reset">Reset</button>
                                    <?php echo form_close(); ?>
									
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
									
								</div></div>	
									
<?php $this->load->view('admin/common/script'); ?>

