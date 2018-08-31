<div class="banner-n">
<h3 class="text-center">Thank You!</h3>
</div>
<?php 
   $success=$this->session->flashdata('success');
    if($success)  { ?> 
    <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Success!</strong> <?php echo $success;?>.
  </div><?php }?>  
  <?php  $error=$this->session->flashdata('error');
    if($error)  { ?> 
   <div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Oh !</strong><?php echo $error;?>.
  </div>
  <?php }?>
<section class="content-n">
<div class="container">

    <div class="row">
      <div class="header-title text-center">
      <div class="line-before"></div>
      <h4 class="head" style="width: 200px;">Thank You!</h4>
      <div class="line-after"></div></div>      
    </div>

    <div class="row text-center">
            
      <h4>Congratulations, your information has been submitted</h4>
      <p>Your information has been successfully received. A representative will be in touch with you shortly.
      In the meantime, take a look at our API documentation for tips on how to get started.</p>

      <div class="s2 button">
         <a target="_blank" type="" href="<?php echo lang_url(); ?>pms" id="show_document" class="btn btn-success hvr-sweep-to-right">API documentation</a>
      </div>

    </div>

</div>
</section>

