<div style='background: rgba(0, 0, 0, 0) url(data:image/png;base64,<?php echo base64_encode(file_get_contents("user_assets/images/inner_banner.jpg"));?>) repeat scroll 0 0;' class="banner_inner">
<div class="container">
			<h2>Faq</h2>
			<label></label>
			<div class="clearfix"></div>
</div>
</div>
<div class="about_cont">
<div class="container">
<div class="panel-group cls_faqs" id="accordion">
<?php
  if(count($faq)!=0)
  {
    $i=1;
    foreach($faq as $value) { 
    extract($value);    
      ?>

    <div class="panel panel-default">
        <div class="panel-heading <?php if($i==1){ echo 'active'; } ?>">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#panel_<?php echo $id; ?>"><i class="fa fa-minus"></i> <?php echo $faq_question;?> </a>
            </h4>
        </div>

        <div id="panel_<?php echo $id; ?>" class="panel-collapse collapse <?php if($i==1){ echo 'in'; } ?>">
            <div class="panel-body">            
               <p> <?php echo $faq_answer;?></p>
            </div>
        </div>
    </div>

      <?php $i++; }  } ?>

    
    
</div>
</div>
</div>
   