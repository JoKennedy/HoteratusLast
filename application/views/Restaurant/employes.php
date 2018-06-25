<div class="outter-wp">


			<div class="sub-heard-part">
				<ol class="breadcrumb m-b-0">
						<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
						<li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
						<li><a><?= $Posinfo['description']?></a></li>
						<li class="active">Employees</li>
				</ol>
		</div>

		<div >
			<?php include("menu.php") ?>
		</div>

		<div style="float: right; " class="buttons-ui">
				<a href="#createemployee"  data-toggle="modal" class="btn blue">Add New Employee</a>
		</div>
		<div class="clearfix" ></div>
		<div class="graph-visual tables-main">
				<div class="graph">
						<div class="table-responsive">
								<div class="clearfix"></div>
								<table id="employeeList" class="table table-bordered">
										<thead>
												<tr>
														<th>#</th>
														<th>Employee Name</th>
														<th>Employee Type</th>
														<th>Status</th>
														<th>Edit</th>
												</tr>
										</thead>
										<tbody>
													 <?php if (count($AllStaff)>0) {

														$i=0;
														foreach ($AllStaff as  $value) {
																$i++;
																echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['fullname'].'  </td> 
																<td> '.$value['stafftypename'].'  </td> <td> '.($value['active']==1?'Active':'Deactive').'</td> <td><a href="#updateproduct" onclick =" showupdate('."'".$value['firstname']."'".')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

														}
												} ?>
										</tbody>
								</table>
								<?php if (count($AllStaff)==0) {echo '<h4>No Employee Created!</h4>';} ?>
								<div class="clearfix"></div>
						</div>
				</div>
		</div>
</div>

<div id="createemployee" class="modal fade" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h4 class="modal-title">Create a Employee</h4>
								</div>
								<div>
										<div class="graph-form">
									<form id ="EmployeeC" >
										<input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
										<div class="col-md-12 form-group1">
												<label class="control-label" >First Name</label>
												<input style="background:white; color:black;" name="name" id ="name" type="text" placeholder="First Name" required="">
										</div>
										<div class="col-md-12 form-group1">
												<label class="control-label" >Last Name</label>
												<input style="background:white; color:black;" name="lastname" id ="lastname" type="text" placeholder="Last Name" required="">
										</div>
										 <div class="col-md-12 form-group1 form-last">
												<label style="padding:4px;" class="control-label controls">Occupation </label>
												<select style="width: 100%; padding: 9px;" name="staffType" id="staffType" >
														<?php

																		echo '<option value="0" >Select a Occupation</option>';
																		foreach ($AllStaffType as $value) {
																				$i++;
																				echo '<option  value="'.$value['stafftypeid'].'" >'.$value['name'].'</option>';
																		}
															?>
												</select>
										</div>
										 <div class="col-md-12 form-group1 form-last">
												<label style="padding:4px;" class="control-label controls">Gender </label>
												<select style="width: 100%; padding: 9px;" name="gender" id="gender" >
														<?php

																		echo '<option value="0" >Select a Gender</option>';
																		echo '<option  value="1" >Male</option>';
																		echo '<option  value="2" >Female</option>';

															?>
												</select>
										</div>

										<div class="col-md-12 form-group1">
												<label class="control-label" >Imagen</label>
												<input style="background:white; color:black;"   type="file" id="Image" name="Image">
										</div>
										<div id="respuesta"></div>
										<div class="clearfix"> </div>
										<br><br>
												<div class="buttons-ui">
												<a onclick="saveStaff()" class="btn green">Save</a>
												</div>
												

										<div class="clearfix"> </div>

									</form>
								</div>
								</div>
						</div>
				</div>
</div>


</div>
</div>
<script type="text/javascript">


		function saveStaff(){

 
			 
			var data =new FormData($("#EmployeeC")[0]);

				if($("#name").val().length <3  ){
						 swal({
							 title: "upps, Sorry",
								text: "Missing Field First Name!",
								icon: "warning",
								button: "Ok!",});
								return;
				}
				 else if($("#lastname").val()==0 ){
						 swal({
							 title: "upps, Sorry",
								text: "Missing Field Last Name!",
								icon: "warning",
								button: "Ok!",});
								return;
				}
				else if($("#staffType").val()==0  ){
						 swal({
							 title: "upps, Sorry",
								text: "Select a Occupation!",
								icon: "warning",
								button: "Ok!",});
								return;
				}
				else if($("#gender").val()==0  ){
						 swal({
							 title: "upps, Sorry",
								text: "Select a Gender!",
								icon: "warning",
								button: "Ok!",});
								return;
				}


					$.ajax({
					type: "POST",
					dataType: "json",
					contentType: false,
					processData:false,
					url: "<?php echo lang_url(); ?>pos/saveEmployee",
					data: data,  beforeSend:function() {
					showWait();
					setTimeout(function() {unShowWait();}, 10000);
				}
					,
					success: function(msg) {
						unShowWait();
						 if (msg["result"]=="0") {
							swal({
							 title: "Success",
								text: "Employee Created!",
								icon: "success",
								button: "Ok!",}).then((n)=>{
									location.reload();
								});
						}
						else {
							
							swal({
							 title: "upps, Sorry",
								text: "Employee was not Created! Error:" + msg["result"],
								icon: "warning",
								button: "Ok!",});
						}

						



					}
			});

					
		}

 
		function showupdate(nombre,id,category,type)
		{

				$("#productnameup").val(nombre);
				$("#itemPosId").val(id);
				$("#Categoryidup").val(category);
				$("#typeup").val(type);
				$("#photo").attr('src',$("#img"+id).attr('src'));
		}

		

</script>