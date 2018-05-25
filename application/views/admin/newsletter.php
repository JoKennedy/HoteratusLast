<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title"> Manage NewsLetter
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo lang_url()?>admin">
								Home
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo lang_url();?>admin/send_newsletter">Send NewsLetter
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom boxless tabbable-reversed">
						
						<div class="tab-content">
							<div class="alert alert-success" id="success" style="display:none;">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success! </strong> <span id="success_msg"></span>
		</div>
							
							<?php 
		$success=$this->session->flashdata('success');									
		if($success)	{	?> 
		<div class="alert alert-success" style="display:block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Success! </strong> <?php echo $success;?>.
		</div><?php }?>  
		<?php  $error=$this->session->flashdata('error');										
		if($error)	{	?> 
		<div class="alert alert-error" style="display:block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Oh! </strong><?php echo $error;?>.
		</div><?php } ?>
							
							<!--<div class="tab-pane active" id="tab_4">
								<div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>View Menu Category
										</div>
										
									</div>
									</div>
									</div>-->
									
									
									
									
									<div class="portlet box green ">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-reorder"></i> Send NewsLetter 
								</div>
							<!--<div class="tools">
								<a href="" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="" class="reload">
								</a>
								<a href="" class="remove">
								</a>
							</div>-->
						</div>
						<div class="portlet-body form">
						 <form id="Users" class="form-horizontal clscommon_form" role="form" action="<?php echo lang_url()?>admin/send_newsletter" method="post" enctype="multipart/form-data">
							<!--<form class="form-horizontal" role="form">-->
								<div class="form-body">
									
									
									<div class="form-group">
										<label class="col-md-3 control-label">Send NewsLetter To</label>
										<div class="col-md-9">
											<div class="radio-list">
												<label class="radio-inline">
												<input type="radio" name="users" id="users1" value="1" > All Users</label>
												<label class="radio-inline">
												<input type="radio" name="users" id="users2" value="2" checked="checked"> All Subscribers</label>
											</div>
										</div>
									</div>
                                    <div id="all" style="display:none">
									<div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3"></label>
                                    <div class="col-sm-4">
                                    <select required="" class="form-control" name="all[]" multiple="multiple">
                                        <?php 
											$all = get_data('Users',array('status'=>'1'))->result_array();
											foreach($all as $val)
											{
												extract($val);
										?>
                                        <option value="<?php echo $email;?>"><?php echo $email;?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    <div id="sub">
									<div class="form-group">
                                    <label class="col-sm-3 control-label" for="inputEmail3"></label>
                                    <div class="col-sm-4">
                                    <select required="" class="form-control" name="sub[]" multiple="multiple">
                                    <?php 
											$sub = get_data('Subscribers',array('status'=>'Active'))->result_array();
											foreach($sub as $sub_val)
											{
												extract($sub_val);
										?>
                                       
                                        <option value="<?php echo $email?>"><?php echo $email?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                    </div>
                                    </div>
									<div class="form-group">
										<label for="u_fname" class="control-label col-sm-3">Description</label>
										<div class="col-sm-4">
											<textarea class="ckeditor form-control" name="description" rows="6" data-error-container="#editor2_error" required></textarea>
											<div id="editor2_error"></div>
										</div>
									</div>
									
								</div>
								<div class="form-actions fluid">
									<div class="col-md-offset-3 col-md-9">
										<input type="submit" name="submit" class="btn green" value="Submit"/>
										<button type="button" class="btn default">Cancel</button>
									</div>
								</div>
							</form>
						</div>
					</div>
									
							
									<div class="portlet-body form">
										<!-- BEGIN FORM-->

									

		
	

										<!-- END FORM-->
									</div>
								
							
							
							
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->