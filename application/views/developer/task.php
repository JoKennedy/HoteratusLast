
<div class="outter-wp">
<!--sub-heard-part-->
	<div class="sub-heard-part">
		<ol class="breadcrumb m-b-0">
			<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
			<li>Developer</li>
			<li class="active"><?=infolang('task')?></li>
		</ol>
	</div>
	
	<div  class="clearfix"></div>
	<div style="float: left;" class="buttons-ui">
			<select onchange="list()" id="closed" class="green">
					<option value="0"><?=infolang('open')?></option>
					<option value="1"><?=infolang('closed')?></option>
			</select>
			<select onchange="list()" id="developerid" class="green">
					<option value="0"><?=infolang('alldeveloper')?></option>
					<?php if (count($Developers)>0) {

							foreach ($Developers as  $developer) {
									echo '<option value="'.$developer['DeveloperId'].'">'.$developer['FirstName'].' '.$developer['LastName'].'</option>';
							}
					} ?>
			</select>
			<select onchange="list()" id="status" class="green">
					<option value="0"><?=infolang('allstatus')?></option>
					<?php if (count($DeveloperTaskStatus)>0) {

							foreach ($DeveloperTaskStatus as  $status) {
									echo '<option value="'.$status['DeveloperTaskStatusId'].'">'.$status['Description'].'</option>';
							}
					} ?>
			</select>
			<input  id="date1" style="background-color: white; width:200px; " type="text" class="green datepickers" value="" placeholder="">
			<input  id="date2" style="background-color: white; width:200px;" type="text" class="green datepickers" value="" placeholder="">
	</div>
	<div style="float: right; " class="buttons-ui">
			<a href="#createtask" data-toggle="modal" class="btn blue"><?=infolang('addnewtask')?></a>
			<a href="#createstaff" data-toggle="modal" class="btn green"><?=infolang('addnewstaff')?></a>
	</div>
<div  class="clearfix"></div>
	<div class="graph-visual tables-main">
		<div class="graph">
			<div id="Tasklist"></div>
		</div>
	</div>
</div>
</div>
</div>
<div id="createtask" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <center><h4 class="modal-title"><?=infolang('createtask')?></h4></center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div>
                <div class="graph-form">
                    <form id="TaskC">
						<div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls"><?=infolang('priority')?></label>
                            <select style="width: 100%; padding: 9px;" name="priority" id="priority">
															<?php if (count($Priorities)>0) {
																	foreach ($Priorities as  $Priorityid) {
																			echo '<option value="'.$Priorityid['value'].'">'.$Priorityid['text'].'</option>';
																	}
															} ?>
                            </select>
                        </div>
						<div class="col-md-12 form-group1">
								<label class="control-label"><?=infolang('category')?></label>
								<input style="background:white; color:black;" name="category" id="category" type="text" placeholder="<?=infolang('category')?>" value="">
						</div>
						<div class="col-md-12 form-group1">
								<label class="control-label"><?=infolang('subcategory')?></label>
								<input style="background:white; color:black;" name="subcategory" id="subcategory" type="text" placeholder="<?=infolang('subcategory')?>"  value="">
						</div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><?=infolang('subject')?></label>
                            <input style="background:white; color:black;" name="subject" id="subject" type="text" placeholder="<?=infolang('subject')?>" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><?=infolang('description')?></label>
                            <textarea  name="description" id="description" type="text" required=""> </textarea>
                        </div>
                        <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls"><?=infolang('developer')?></label>
                            <select style="width: 100%; padding: 9px;" name="DeveloperId" id="DeveloperId">
															<?php if (count($Developers)>0) {
																	echo '<option value="0">'.infolang('selectdeveloper').'</option>';
																	foreach ($Developers as  $developer) {
																			echo '<option value="'.$developer['DeveloperId'].'">'.$developer['FirstName'].' '.$developer['LastName'].'</option>';
																	}
															} ?>
                            </select>
                        </div>

                        <div class="col-md-12 form-group1">
                            <label class="control-label"><?=infolang('files')?></label>
                            <input style="background:white; color:black;" type="file" id="Image" name="Image[]" multiple accept="image/png,image/gif,image/jpeg">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="savetask()" class="btn green"><?=infolang('save')?></a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
		$('.datepickers').datepicker();
		$(".datepickers").change(function(event) {
				list();
		});
		function savetask()
		{

			if ($.trim($("#subject").val())=='') {

				swal({
						title: "Upps",
						text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('subject'))?>',
						icon: "warning",
						button: "Ok!",
				}).then((n) => {
						$("#subject").focus();
				});
				return;
			}
			else if ($.trim($("#description").val())=='') {

				swal({
						title: "Upps",
						text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('description'))?>',
						icon: "warning",
						button: "Ok!",
				}).then((n) => {
						$("#description").focus();
				});
				return;
			}

			var data = new FormData($("#TaskC")[0]);
			$.ajax({
					type: "POST",
					dataType: "json",
					contentType: false,
					processData: false,
					url: "<?php echo lang_url(); ?>developer/savetask",
					data: data,
					beforeSend: function() {
							showWait();
							setTimeout(function() { unShowWait(); }, 10000);
					},
					success: function(msg) {
							unShowWait();

							if (msg["success"]) {
									swal({
											title: "Success",
											text: "<?=$this->lang->line('tasksaved')?>",
											icon: "success",
											button: "Ok!",
									}).then((n) => {
											list();

											$(".close").trigger('click');
											$("#TaskC").trigger("reset");

									});
							} else {

									swal({
											title: "upps, Sorry",
											text: msg["message"],
											icon: "warning",
											button: "Ok!",
									});
							}
					}
			});

		}
		function list() {

			$.ajax({
				type: "POST",
				//dataType: "json",
				url: "<?php echo lang_url(); ?>developer/taskhtml",
				data: {'date1':$("#date1").val(),'date2':$("#date2").val(),'developerid':$("#developerid").val(),'status':$("#status").val(),
					'closed':$("#closed").val(),},
				beforeSend: function() {
						showWait();
						setTimeout(function() { unShowWait(); }, 10000);
				},
				success: function(msg) {
						unShowWait();
						$("#Tasklist").html('');
					$("#Tasklist").html(msg);
						$('.inline_username').editable({
							url: function (params) {
								return saveChange(params);
							}
						});
					$('#myTable').DataTable({"order": [[ 0, "asc" ]]});
					
				}
			 });

		}
		function saveChange(params)
		{

			if(params['name']=='PercentageProccess')
			{
				params['value']=params['value']>100?100:params['value'];
				if(params['value']>=100)$("#percentage"+params['pk']).html(params['value']+'%');
				let value =params['value'];
				let classe=(value<=10?'danger':(value<=20?'warning':(value<=50?'info':(value<100?'inverse':'success'))));
				$("#class"+params['pk']).removeClass();
				$("#class"+params['pk']).addClass('progress-bar progress-bar-'+classe+'');
				$("#class"+params['pk']).css('width',value+'%');


			}
			var data={'name':params['name'],'pk':params['pk'],'value':params['value']};
		    $.ajax({
		        type: "POST",
		        //dataType: "json",
		        url:  '<?=lang_url()?>developer/savechange',
		        data:data,
						beforeSend: function() {
 							 showWait();
 							 setTimeout(function() { unShowWait(); }, 10000);
 					 },
					 success: function(msg) {
						 unShowWait();

					 }
		    });
		   return;
		}
		$(document).ready(function() {

		  list();

		});
		function deletetask(id)
		{

			swal({
							title: "<?=infolang('areyousure')?>",
							text: "<?=infolang('doyouwantdelete')?>",
							icon: "warning",
							buttons: true,
							dangerMode: true,
					})
					.then((willDelete) => {
							if (!willDelete) { return; }
							$.ajax({
									type: "POST",
									//dataType: "json",
									url:  '<?=lang_url()?>developer/deletetask',
									data:{'id':id},
									beforeSend: function() {
											 showWait();
											 setTimeout(function() { unShowWait(); }, 10000);
									 },
								 success: function(msg) {
									 unShowWait();
									 $('#row'+id).remove();
									 swal({
											 title: "Success",
											 text: "<?=$this->lang->line('taskdeleted')?>",
											 icon: "success",
											 button: "Ok!",
									 });
								 }
							});
					});






		}
		function closedtask(id)
		{

			swal({
							title: "<?=infolang('areyousure')?>",
							text: "<?=infolang('doyouwantclose')?>",
							icon: "warning",
							buttons: true,
							dangerMode: true,
					})
					.then((willDelete) => {
							if (!willDelete) { return; }
							$.ajax({
									type: "POST",
									//dataType: "json",
									url:  '<?=lang_url()?>developer/closetask',
									data:{'id':id},
									beforeSend: function() {
											 showWait();
											 setTimeout(function() { unShowWait(); }, 10000);
									 },
								 success: function(msg) {
									 unShowWait();
									 $('#rowclosed'+id).html('<i class="fas fa-lock"></i>');
									 $('#row'+id).remove();
									 swal({
											 title: "Success",
											 text: "<?=$this->lang->line('taskclosed')?>",
											 icon: "success",
											 button: "Ok!",
									 });
								 }
							});
					});
		}
</script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables-init.js"></script>