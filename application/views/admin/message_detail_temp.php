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
									
									<div id="page-wrapper">
                       <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Support
                        </div>
                        <div class="panel-body">
                            <div class="content">   
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
									
											
								<?php if(isset($view)) { ?>
									<h4><?php echo anchor('admin/manage_support','Message List'); ?></h4>
										
										<?php  if(isset($notfound)) { ?>
									   <div class="alert">
											<button class="close" data-dismiss="alert">x</button>
											<strong>Sorry! </strong> <?php echo $notfound;?>
                         			   </div>
								<?php } 
								
								  if(isset($reply_status)) { ?>
									   <div class="alert">
											<button class="close" data-dismiss="alert">x</button>
											<strong>Sorry! </strong> <?php echo $reply_status;?>
                         			   </div>
								<?php } 
								else {  ?> 	
									<?php $attributes=array('class'=>'form-horizontal','id'=>'reply_message_form','name'=>'reply_message_form');
									echo  form_open_multipart('admin/reply_message_for_support',$attributes); ?>   
								 <h4></h4>
								  
								<?php 	//print_r($result);
								foreach($result as $message)
										{
											$support_id=$message->support_id;
											$name=$message->name;
											$emailid=$message->emailid;
											$subject=$message->subject;
											$message1=$message->message;
											$replied_msg=$message->replied_message;
											
										?>
										
                                   <input type="hidden" id="user_email" name="user_email" value="<?php echo $emailid;?>" />
								    <input type="hidden" id="support_id" name="support_id" value="<?php echo $support_id;?>" />
									 <input type="hidden" id="message" name="message" value="<?php echo $message1;?>" />
								   <div class="form-group">
                                            <label><font color="#CC0000">*</font>User Name</label>
                                            <input class="form-control" name="name" value="<?php if(isset($name)){
											echo $name; }
											//echo set_value('login_name');
											?>">
											
                                        </div>
								   
								<div class="form-group">
                                            <label><font color="#CC0000">*</font>Email Id</label>
                                            <input class="form-control" name="emailid" value="<?php if(isset($emailid)){
											echo $emailid; }
											//echo set_value('login_name');
											?>">
											
                                        </div>
								<div class="form-group">
                                            <label><font color="#CC0000">*</font>Subject</label>
                                            <input class="form-control" name="subject" value="<?php if(isset($subject)){
											echo $subject; }
											//echo set_value('login_name');
											?>">
											
                                        </div>
								
								
								<div class="form-group">
                                            <label><font color="#CC0000">*</font>Message</label>
                                            <input class="form-control" name="message1" value="<?php if(isset($message1)){
											echo $message1; }
											//echo set_value('login_name');
											?>">
											
                                        </div>
								
								
								<!--<div class="center">
									<div class="holder" >
									<?php //echo $this->pagination->create_links();
									//echo $pagination;
									 ?>
									 </div>
								</div> -->
								
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="tooltip"><font color="#CC0000">*</font>Reply Message</label>
											<textarea class="span8" id="textareacontent" name="reply_message" title="Reply for above message"  ><?php echo $replied_msg; ?> </textarea>
											<?php if(form_error('reply_message')) { ?><br><font color="#CC0000"><?php echo form_error('reply_message'); ?></font><?php } ?>
											
										</div>
									</div> 
								</div>
								 <div class="form-actions" align="center">
								
								   <button type="submit" class="btn btn-info" name="reply" value="reply">Reply </button>
								   <button type="reset" class="btn btn-danger" name="reset" value="reset">Reset</button>
								</div>   
						 <?php } ?>
						 <?php }?>
						 <?php }?>			                  
						
								 <?php if(isset($edit)) { ?> 
								<h4><?php echo anchor('admin/manage_forum','Forum List'); ?></h4>
							   <h4>Edit Forum Details</h4>                
								
								<?php }?>
                            </div>
                            <!-- /.table-responsive -->							
                        </div>
                        <!-- /.panel-body -->
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
	   </div>
	      </div>
    <!-- /#wrapper -->
									
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