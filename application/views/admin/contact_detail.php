<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-reply"></i> Contact Details
  </div>
  </div>
  <div class="manage">
    <div class="row-fluid clearfix">
    
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>Contact Details</h4>
      <br><br>
    <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-6">
      <div class="content">   
                  
                   <?php    
      if(isset($error)) { ?> 
       <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
      </div>
      <?php }?>     
      
      <?php 
       $success=$this->session->flashdata('success');                 
        if($success)  { ?> 
        <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success! </strong> <?php echo $success;?>.
      </div><?php }?>  
      <?php  $error=$this->session->flashdata('error');                   
        if($error)  { ?> 
       <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
      </div>
      <?php }?> 
                      
                <?php if(isset($view)) { ?>
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
                  echo  form_open_multipart('admin/sendreplymail',$attributes); ?>   
                <?php  
                foreach($result as $message)
                    {
                      $support_id=$message->contact_id;
                      $name=$message->name;
                      $emailid=$message->email;
                      $message1=$message->message_content;
                      
                      
                    ?>
                    
<input type="hidden" id="user_email" name="user_email" value="<?php echo $emailid;?>" />
<input type="hidden" id="support_id" name="replay_id" value="<?php echo $support_id;?>" />
<input type="hidden" id="message" name="message" value="<?php echo $message1;?>" />
<div class="form-group">
                    <label><font color="#CC0000">*</font><strong>User Name :</strong> </label>
  <?php if(isset($name)){echo $name; }?>                      
                   </div>
                   
                <div class="form-group">
                                            <label><font color="#CC0000">*</font><strong>Email Id :</strong></label>

                        <?php if(isset($emailid)){echo $emailid; }?>
                                        </div>
                
                <div class="form-group">
                                            <label><font color="#CC0000">*</font><strong>Message :</strong></label>
                        <?php if(isset($message1)){echo $message1; }?>
                      
                                        </div>
                                        
                                <div class="form-group">
                                            <label><font color="#CC0000">*</font>Subject</label>
                                            <input class="form-control" name="subject" required>
                                        </div>
                
                
                <!--<div class="center">
                  <div class="holder" >
                  <?php //echo $this->pagination->create_links();
                  //echo $pagination;
                   ?>
                   </div>
                </div> -->
                
                <div class="form-group">
                  <div class="span12">
                    <div class="row-fluid">
                      <label class="form-label span4" for="tooltip"><font color="#CC0000">*</font>Reply Message</label>
                <textarea class="form-control" id="textareacontent" name="message" title="Reply for above message" style="height:150px;" required></textarea>                                     <?php if(form_error('message')) { ?><br><font color="#CC0000"><?php echo form_error('message'); ?></font><?php } ?>
                      
                    </div>
                  </div> 
                </div>
                 <div class="form-actions" align="center">
                
                   <button type="submit" class="btn btn-success" name="submit" value="reply">Reply </button>
                                   
                                   <a href="<?php echo lang_url(); ?>admin/contact"><button type="button" class="btn btn-info" name="reply" value="reply">Back </button> </a>
                </div>  
                <br>
                <br>
                <br>
             <?php } ?>
             <?php }?>
             <?php }?>                        
            
                 <?php if(isset($edit)) { ?> 
                <h4><?php echo anchor('admin/manage_forum','Forum List'); ?></h4>
                 <h4>Edit Forum Details</h4>                
                
                <?php }?>
                            </div>
                            </div>
                            </div>
            
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
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