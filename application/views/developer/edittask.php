
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
				<label class="control-label"><?=infolang('category')?></label>
				<input style="background:white; color:black;" name="category" id="category" type="text" placeholder="<?=infolang('category')?>" value="<?=$TaskInfo['Category']?>">
		</div>
		<div class="col-md-12 form-group1">
				<label class="control-label"><?=infolang('subcategory')?></label>
				<input style="background:white; color:black;" name="subcategory" id="subcategory" type="text" placeholder="<?=infolang('subcategory')?>"  value="<?=$TaskInfo['SubCategory']?>">
		</div>
		<div class="col-md-12 buttons-ui">
				<a onclick="updateTask()" class="btn blue"><?=infolang('update')?></a>
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
	<div  class="clearfix"></div>
	<div class="col-md-12">
		<form>
			<div class="col-md-12 form-group1">
				<textarea style="width: 100;" id="usernote" name="usernote" placeholder="Type a Comment"></textarea>
			</div>
			<div class="buttons-ui">
				<a onclick="addNote()" class="btn blue">Add Comment</a>
			</div>
		</form>
	</div>
		<div class="chat-inner col-md-12">
			<!--/chat-inner-->
			<div class=" widget-shadow ">
				<h4 class="title3" style="background-color:#021F4E;">Follow up</h4>
				<div class="scrollbar" id="style-2" >
					<?php  
					$ALLUsersNotes=$this->db->query("SELECT a.*, concat(b.fname,' ' , b.lname) Username
					FROM DeveloperTaskFollow a
					left join manage_users b on a.Userid=b.user_id
					where
					a.DeveloperTaskId=".$TaskInfo['DeveloperTaskId']." order by CreateDate desc")->result_array();
					
					if(count($ALLUsersNotes)>0)
					{   
						$i=0;
						foreach ($ALLUsersNotes as  $value) {
							$i++;
							if($i%2)
							{
								echo '  <div class="activity-row activity-row1 activity-right">
									<div class="col-xs-2 activity-img"><span style="color:blue;">'.$value['Username'].'</span></div>
									<div class="col-xs-10 activity-img1">
										<div class="activity-desc-sub">
											<p>'.$value['Description'].'</p>
											<span>'.date('m/d/Y h:m:s',strtotime($value['CreateDate'])).'</span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>'; 
							}
							else
							{   echo '  <div class="activity-row activity-row1 activity-left">
									<div class="col-xs-10 activity-img2">
										<div class="activity-desc-sub1">
											<p>'.$value['Description'].'</p>
											<span class="right">'.date('m/d/Y h:m:s',strtotime($value['CreateDate'])).'</span>
										</div>
									</div>
									<div class="col-xs-2 activity-img"><span style="color:blue;">'.$value['Username'].'</span></div>
									<div class="clearfix"> </div>
								</div>';
							}
						}
					}
					else
					{
							echo '  <div class="activity-row activity-row1 activity-right">
							<div class="col-md-2 activity-img"><span>Hoteratus</span></div>
							<div class="col-md-10 activity-img1">
								<div class="activity-desc-sub">
									<p><h3>'.infolang('therearenocomments').'</h3></p>
									<span>'.date('m/d/Y h:m:s').'</span>
								</div>
							</div>
							<div class="clearfix"> </div>
						</div>';
					}



				?>
				</div>
				
			</div>
			<div class="clearfix"> </div>
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
function addNote() {
	username="<?=$fname.' '.$lname ?>";
	if($("#usernote").val().trim().length==0)
	{
		swal({
			title: "upps, Sorry",
			text: "Type a Comment to Continue",
			icon: "warning",
			button: "Ok!",});
			return;
	}

	$.ajax({
			type: "POST",
			dataType: "json",
			url: "<?php echo lang_url(); ?>developer/addNote",
			data: {"description":$("#usernote").val().trim(),"taskid":"<?=$TaskInfo['DeveloperTaskId']?>"},  
			beforeSend:function() {
			showWait();
			setTimeout(function() {unShowWait();}, 10000);
			},
			success: function(msg) {
				if (msg["success"]) {
				swal({
				title: "Success",
					text: "Comment added!",
					icon: "success",
					button: "Ok!",}).then((n)=>{
					location.reload();
					});
				}
				else {
					swal({
					title: "upps, Sorry",
						text: "Something went wrong" ,
						icon: "warning",
						button: "Ok!",});
					}
					unShowWait();
				}
			});
}
function updateTask() {


	$.ajax({
			type: "POST",
			dataType: "json",
			url: "<?php echo lang_url(); ?>developer/updatetask",
			data: {"category":$("#category").val().trim(),"subcategory":$("#subcategory").val().trim(),"statusid":$("#statusid").val().trim(),"taskid":"<?=$TaskInfo['DeveloperTaskId']?>"},  
			beforeSend:function() {
			showWait();
			setTimeout(function() {unShowWait();}, 10000);
			},
			success: function(msg) {
				if (msg["success"]) {
				swal({
				title: "Success",
					text: "Task Updated!",
					icon: "success",
					button: "Ok!",}).then((n)=>{
					location.reload();
					});
				}
				else {
					swal({
					title: "upps, Sorry",
						text: "Something went wrong" ,
						icon: "warning",
						button: "Ok!",});
					}
					unShowWait();
				}
			});
}

</script>
