


<div class="outter-wp">
		<!--sub-heard-part-->
	  <!--//sub-heard-part-->
	  	
	  		<center><h2><span id="fechas" class="label label-primary">Variables</span></h2></center>
	  		<div class="graph">
		  		<?php

		  			foreach ($Variables as  $variable) {
	  				 echo'<center><div class="col-md-3 form-group1" >
	                            <strong>'.$variable['Name'].'</strong>
	                            <p class="text-muted">
	                               '.$variable['Code'].'
	                            </p>
	                         </div></center>';
		  			}
		  		?>
		  		<div class="clearfix"></div>
	  		</div> 

			<div class="graph-visual tables-main">

				<div class="graph">
					<form id="TemplateC"  method="post" action="<?=lang_url()?>template/templatesave" accept-charset="utf-8"  enctype="multipart/form-data">
						<div class="buttons-ui">
                            	<a onclick="saveTemplate()" class="btn green">Save Template</a>
                        </div>
                        <input type="hidden" name="TemplateTypeId" value="<?=$Templates['TemplateTypeId']?>">
						<div class="col-md-12 form-group1">
	                            <label class="control-label">Subject</label>
	                            <input style="background:white; color:black;" name="Subject" id="Subject" type="text" placeholder="Type a Subject" required="" value="<?=$Templates['Subject']?>">
	                    </div>
						<div class="col-md-12 form-group1">
							<label class="control-label">Email Body </label>
							<textarea  id="textareacontent" name="Message" required ><?php echo $Templates['Message']; 
							 ?></textarea>

						</div>
					</form>
					<div class="clearfix"></div>
				</div>
			
			</div>
		
			<!--//graph-visual-->
		<!--//outer-wp-->
		 <!--footer section start-->
		
		<!--footer section end-->
</div>
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

		setTimeout(function() { $("#cke_1_contents").css({'height':700}); unShowWait(); }, 1000);

		
	});
	  showWait('Loading Page');
</script>
<script type="text/javascript" charset="utf-8" async defer>
	
function saveTemplate()
{
	if($("#Subject").val().length==0)
	{
		 swal({
                title: "Upps",
                text: 'Missing Field Subject',
                icon: "warning",
                button: "Ok!",
	            }).then((n) => {
	        		$("#Subject").focus();
	    		});
	    		return;
	}

	$("#cke_9").trigger('click');

	return;
}

</script>