


<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Template </li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->
			

			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">

				<div class="graph">
					<form id="TemplateC" onsubmit="return saveTemplate();" method="post" action="<?=lang_url()?>template/templatesave" accept-charset="utf-8"  enctype="multipart/form-data">
						<div class="buttons-ui">
                            	<a onclick="saveTemplate()" class="btn green">Save Template</a>
                            	<<button type="submit">done</button>
                        </div>
						<div class="col-md-12 form-group1">
	                            <label class="control-label">Subject</label>
	                            <input style="background:white; color:black;" name="Subject" id="Subject" type="text" placeholder="Type a Subject" required="" value="<?=$Templates['Subject']?>">
	                    </div>
						<div class="col-md-12 form-group1">
							<label class="control-label">Email Body </label>
							<textarea  id="textareacontent" name="textareacontent" required ><?php echo $Templates['Message']; 
							 ?></textarea>

						</div>
					</form>
					<div class="clearfix"></div>
				</div>
			
			</div>
				
			</div>
			<!--//graph-visual-->
		</div>
		<!--//outer-wp-->
		 <!--footer section start-->
		
		<!--footer section end-->
	</div>
</div>


<script type="text/javascript" src="<?php echo base_url().'js/ckeditor/ckeditor.js'?>"></script>
<script type="text/javascript">

	CKEDITOR.replace('textareacontent' ,
	{    
	                      
	filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
	filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	});


	jQuery(document).ready(function($) {

		setTimeout(function() { $("#cke_1_contents").css('height',700); unShowWait(); }, 1000);

		
	});
	  showWait('Loading Page');
</script>
<script type="text/javascript" charset="utf-8" async defer>
	
function saveTemplate()
{
		$.ajax({
                type: "POST",
             	//dataType: "json",
             	contentType: false,
        		processData: false,
                url: "<?php echo lang_url(); ?>template/templatesave",
                data: new FormData($("#TemplateC")[0]),
                beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
                success: function(msg) {

                	unShowWait();
                	alert(msg);
                	return false;
                	
                   if (!msg['success']) {
                     swal({
                            title: "Upps",
                            text: '<?=$this->lang->line('sww')?>!!',
                            icon: "warning",
                            button: "Ok!",
                        }).then((n) => {
                    		$("#agencyname").focus();
                		});

                     return;
                   }
                   else
                   {
                   	 swal({
                            title: "",
                            text: '<?=$this->lang->line('ss')?>!!',
                            icon: "success",
                            button: "Ok!",
                        }).then((n) => {
                        	$("#agencyC")[0].reset();
                    		AllagenciesHTML();
                		});
                        
                   }
                  
                }
            });
}

</script>