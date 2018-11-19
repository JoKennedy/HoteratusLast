
<div class="outter-wp">
<!--sub-heard-part-->
	<div class="sub-heard-part">
		<ol class="breadcrumb m-b-0">
			<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
			<li>Developer</li>
			<li><a href="<?php echo base_url();?>developer/task">All Task</a></li>
			<li class="active">View Task</li>
		</ol>
	</div>
	<div style="float: right; " class="buttons-ui">
			<a href="#viewphotos" data-toggle="modal" class="btn blue"><?=infolang('showattached')?></a>
	</div>
	<div  class="clearfix"></div>
			<div class="graph-form">

							<div class="col-md-6 form-group1 form-last">
									<label style="padding:4px;" class="control-label controls"><?=infolang('status')?></label>
									<select style="width: 100%; padding: 9px;" name="statusid" id="statusid"  >
										<?php if (count($DeveloperTaskStatus)>0) {
												echo '<option value="0">'.infolang('selectstatus').'</option>';
												foreach ($DeveloperTaskStatus as  $value) {
														echo '<option value="'.$value['DeveloperTaskStatusId'].'" '.($value['DeveloperTaskStatusId']==$TaskInfo['StatusId']?'selected':'').'>'.$value['Description'].'</option>';
												}
										} ?>
									</select>
							</div>
							<div class="col-md-12 form-group1">
									<label class="control-label"><?=infolang('subject')?></label>
									<input style="background:white; color:black;" name="subject" id="subject" type="text" placeholder="<?=infolang('subject')?>" readonly="" value="<?=$TaskInfo['SubjectTask']?>">
							</div>
							<div class="col-md-12 form-group1">
									<label class="control-label"><?=infolang('description')?></label>
									<textarea  name="description" id="description" type="text" required="" disabled><?=$TaskInfo['Description']?></textarea>
							</div>

							<div class="clearfix"> </div>
			</div>
</div>
</div>
</div>
<div id="viewphotos" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <center><h4 class="modal-title"><?=infolang('filesattached')?></h4></center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div>
              <?php

								$files=explode('###',$TaskInfo['LinkFileAttached']);

								foreach ($files as $file) {
									if(strlen($file)>2)
									echo ' <img width="100%" src="'.base_url().$file.'" /> <hr>';
								}

							 ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
