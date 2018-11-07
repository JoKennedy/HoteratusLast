<div class="outter-wp">
<!--sub-heard-part-->
	<div class="sub-heard-part">
		<ol class="breadcrumb m-b-0">
			<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
			<li><?=$this->lang->line('agenciesmanager')?></li>
		</ol>
	</div>
<!--//sub-heard-part-->
	<div style="float: left;" class="buttons-ui">

		<select onchange="AllagenciesHTML()" id="groupid" class="green">
			<option value="0"><?=$this->lang->line('allgroups')?></option>
			<option value="1"><?=$this->lang->line('retailers')?></option>
			<option value="2"><?=$this->lang->line('wholesalers')?></option>
		</select>
		<select onchange="AllagenciesHTML()" id="categoryid" class="green">
		<option value="0"><?=$this->lang->line('allcategories')?></option>
			<?php if (count($Allagencycategories)>0) {

				foreach ($Allagencycategories as  $value) {
				echo '<option value="'.$value['agencycategoryid'].'">'.$value['name'].'</option>';
				}
			} ?>
		</select>
	</div>
<div style="float: right;" class="buttons-ui">
<a href="#addagency"  data-toggle="modal" class="btn blue"><?=$this->lang->line('addnewagency')?></a>
<a href="#addcategory"  data-toggle="modal" class="btn yellow"><?=$this->lang->line('addnewcategory')?></a>
</div>
<div class="clearfix"></div>
<div class="graph-visual tables-main">

<div class="graph">


<div id="Allagencies"></div>

</div>

</div>
</div>
</div>

<div id="addcategory" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <center><h4 class="modal-title"><?=$this->lang->line('newagencycategory')?></h4></center>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                <div class="graph-form">
                  <form id ="createtax" >

                    <div class="col-md-12 form-group1">
                            <label class="control-label"><?=$this->lang->line('namecategory')?></label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="<?=$this->lang->line('namecategory')?>" required="">
                    </div>                    
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="saveCategory()" class="btn green"><?=$this->lang->line('save')?></a>
                        </div>

                       
    

                    <div class="clearfix"> </div>

                  </form>
                </div>
                  <div class="graph">
		            <div class="table-responsive">
		                <div class="clearfix"></div>
		                <table id="proList" class="table table-bordered">
		                    <thead>
		                        <tr>
		                            <th width="10%">#</th>
		                            <th><?=$this->lang->line('agencycategory')?></th>
		                            <th width="10%"><?=$this->lang->line('status')?></th>
		                            <th width="10%"><?=$this->lang->line('edit')?></th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        <?php if (count($Allagencycategories)>0) {

		                            $i=0;
		                            foreach ($Allagencycategories as  $value) {
		                                $i++;
		                                $update="'".$value['agencycategoryid']."','".str_replace("'", "", $value['name'])."','".$value['active']."'";
		                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td>
		                                <td>'.($value['active']==1?$this->lang->line('active'):$this->lang->line('disabled')).'</td> <td> <a href="javascript:;" onclick="updateCategory('.$update.')" > <i class="fa fa-edit"></i></a></td>  </tr>   ';
		                            }
		                           

		                        } ?>
		                    </tbody>
		                </table>
		                <?php if (count($Allagencycategories)==0) {echo '<h4>'.$this->lang->line('noagencycategory').'!</h4>';} 
		                  
		                 ?>
		                <div class="clearfix"></div>
		            </div>
		        </div>
		     </div>
                </div>
            </div>
        </div>
</div>

<div id="editcategory" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
	                <div class="modal-header">
	                    
	                    <h4 class="modal-title"><?=$this->lang->line('updatecategoryagency')?></h4>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span> 
	                </div>
	              
	                <div class="graph-form">
	                  <form id ="updatecategory" >

						<input type="hidden" name="id" id="categoryid">
	                    <div class="col-md-12 form-group1">
	                            <label class="control-label"><?=$this->lang->line('namecategory')?></label>
	                            <input style="background:white; color:black;" name="name" id="nameup" type="text" placeholder="<?=$this->lang->line('namecategory')?>" required="">
	                    </div>  
	                    <div class="col-md-12 form-group1" >
	                        <label class="control-label"><?=$this->lang->line('status')?></label>
	                        <div class="onoffswitch">
	                            <input  type="checkbox" name="active" class="onoffswitch-checkbox" id="myonoffswitch" >
	                        <label class="onoffswitch-label" for="myonoffswitch">
	                            <span class="onoffswitch-inner"></span>
	                            <span class="onoffswitch-switch"></span>
	                        </label>
	                        </div>
	                    </div>                  
	                    <div class="clearfix"> </div>
	                    <br><br>
	                        <div class="buttons-ui">
	                        <a onclick="updatesCategory()" class="btn green"><?=$this->lang->line('update')?></a>
	                        </div>

	                
	                    <div class="clearfix"> </div>

	                  </form>
	                </div>
                  
		     </div>
        </div>
</div>

<div id="addagency" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <center><h4 class="modal-title"><?=$this->lang->line('newagency')?></h4></center>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               		<span aria-hidden="true">&times;</span> 
                </div>
                 <div class="clearfix"></div>
                

                <div class="form">
                  <form id ="agencyC" >
			
                    <div class="col-md-12 form-group1">
                            <label class="control-label"><?=$this->lang->line('agencyname')?></label>
                            <input style="background:white; color:black;" name="agencyname" id="agencyname" type="text" placeholder="<?=$this->lang->line('agencyname')?>" required="">
                    </div>
                     <div class="col-md-12 form-group1">
                            <label class="control-label">
                                <?=$this->lang->line('categoriesagency')?>
                            </label>
                            <select style="width: 100%; padding: 9px; " id="acategoryid" name="acategoryid">
                                <?php
                                    if(count($Allagencycategories)>0)
                                    {
                                        echo '<option value="0">'.$this->lang->line('selectcategoryagency').'</option>'; 
                                        foreach ($Allagencycategories as  $value) {
		                                	echo '<option value="'.$value['agencycategoryid'].'">'.$value['name'].'</option>';
		                            	}
                                    }
                                    else
                                    {
                                        echo '<option value="0">'.$this->lang->line('noagencycategory').' created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
					
                   	<div class="col-md-12 form-group1">
                   		<center><label class="control-label"><?=$this->lang->line('commissiontype')?> </label></center>
                   		<div class="clearfix"></div>
                   		<center>
                   		<div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-percentage"></i>
                                    <label  for="percentage" ><?=$this->lang->line('percentage')?></label>
                                    <input value="2"  id="percentage" name="CommissionType"  type="radio" >
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                    <label  for="money" ><?=$this->lang->line('money')?></label>
                                     <input value="1"  id="money" name="CommissionType"  type="radio" >
                                </span>    
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <label  for="netrate" ><?=$this->lang->line('netrate')?></label>
                                     <input value="0"  id="netrate" name="CommissionType"  type="radio" >
                                </span>    
                            </div>
                        </div>
                   	</div>
					</center>
                </div>
                
               	  <div class="form-group">
                    <center><label class="col-md-12 control-label"><?=$this->lang->line('commissionamount')?></label></center>
                        <div class="col-md-12">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                <input onkeypress="return justNumbers(event);" value="" id="amount" name="amount" class="form-control1 icon" type="text" placeholder="<?=sprintf($this->lang->line('typesomething'),$this->lang->line('commissionamount'))?>">
                            </div>
                        </div>
               	 </div>
                 
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="createagency()" class="btn green"><?=$this->lang->line('save')?></a>
                        </div>

                
                    <div class="clearfix"> </div>

                  </form>
            </div>
        </div>
</div>
<div id="updateagency" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <center><h4 class="modal-title"><?=$this->lang->line('newagency')?></h4></center>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                <div class="form">
                  <form id ="agencyUP" >
	
					<input type="hidden" name="id" id="agencyid">
                    <div class="col-md-12 form-group1">
                            <label class="control-label"><?=$this->lang->line('agencyname')?></label>
                            <input style="background:white; color:black;" name="agencyname" id="agencynameup" type="text" placeholder="<?=$this->lang->line('agencyname')?>" required="">
                    </div>
                     <div class="col-md-12 form-group1">
                            <label class="control-label">
                                <?=$this->lang->line('categoriesagency')?>
                            </label>
                            <select style="width: 100%; padding: 9px; " id="categoryidup" name="categoryid">
                                <?php
                                    if(count($Allagencycategories)>0)
                                    {
                                        echo '<option value="0">'.$this->lang->line('selectcategoryagency').'</option>'; 
                                        foreach ($Allagencycategories as  $value) {
		                                	echo '<option value="'.$value['agencycategoryid'].'">'.$value['name'].'</option>';
		                            	}
                                    }
                                    else
                                    {
                                        echo '<option value="0">'.$this->lang->line('noagencycategory').' created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
					
                   	<div class="col-md-12 form-group1">
                   		<center><label class="control-label"><?=$this->lang->line('commissiontype')?> </label></center>
                   		<div class="clearfix"></div>
                   		<center>
                   		<div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-percentage"></i>
                                    <label  for="percentage" ><?=$this->lang->line('percentage')?></label>
                                    <input value="2"  id="percentageup" name="CommissionType"  type="radio" >
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                    <label  for="money" ><?=$this->lang->line('money')?></label>
                                     <input value="1"  id="moneyup" name="CommissionType"  type="radio" >
                                </span>    
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <label  for="netrate" ><?=$this->lang->line('netrate')?></label>
                                     <input value="0"  id="netrateup" name="CommissionType"  type="radio" >
                                </span>    
                            </div>
                        </div>
                   	</div>
					</center>
                </div>
                
                 <div class="form-group">
                    <center><label class="col-md-12 control-label"><?=$this->lang->line('commissionamount')?></label></center>
                        <div class="col-md-12">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                <input onkeypress="return justNumbers(event);" value="" id="amountup" name="amount" class="form-control1 icon" type="text" placeholder="<?=sprintf($this->lang->line('typesomething'),$this->lang->line('commissionamount'))?>">
                            </div>
                        </div>
                </div>

                    <div class="col-md-12 form-group1" >
                        <label class="control-label"><?=$this->lang->line('status')?></label>
                        <div class="onoffswitch">
                            <input  type="checkbox" name="active" class="onoffswitch-checkbox" id="myonoffswitchup" >
                        <label class="onoffswitch-label" for="myonoffswitchup">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                        </div>
                    </div>                  
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="updateagencies()" class="btn green"><?=$this->lang->line('update')?></a>
                        </div>

                
                    <div class="clearfix"> </div>

                  </form>
                </div>
                  
            </div>
        </div>
</div>

<script type="text/javascript">
	function updateagencies()
	{
		 if ($.trim($("#agencynameup").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('agencyname'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#agencynameup").focus();
                });
                return;
            }
            else if ($("#acategoryidup").val()==0) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('selectsomethingtocontinue'),$this->lang->line('categoriesagency'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#acategoryidup").focus();
                });
                return;
            }
           
            else if (!$("#percentageup").prop('checked') && !$("#moneyup").prop('checked') && !$("#netrateup").prop('checked')) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('selectsomethingtocontinue'),$this->lang->line('commissiontype'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#percentageup").focus();
                });
                return;
            }
          
            $.ajax({
                type: "POST",
             	dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/updateagency",
                data: $("#agencyUP").serialize(),
                beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
                success: function(msg) {
                	unShowWait();
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
                            text: '<?=$this->lang->line('su')?>!!',
                            icon: "success",
                            button: "Ok!",
                        }).then((n) => {
                    		AllagenciesHTML();
                		});
                        
                   }
                  
                }
            });
	}
	function updateagency(id,catid,groupid,name,value,type,active)
	{	
		$("#agencyid").val(id);
		$("#agencynameup").val(name);
		$("#categoryidup").val(catid);
		$("#agroupidup").val(groupid);
		$("#amountup").val(value);

		$("#"+(type==0?'netrateup':(type==1?'moneyup':'percentageup'))).prop('checked',true);

		$("#myonoffswitchup").prop('checked', (active==1?true:false))
		$("#updateagency").modal();
	}
	function createagency()
	{
		 if ($.trim($("#agencyname").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('agencyname'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#agencyname").focus();
                });
                return;
            }
            else if ($("#acategoryid").val()==0) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('selectsomethingtocontinue'),$this->lang->line('categoriesagency'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#acategoryid").focus();
                });
                return;
            }
          
            else if (!$("#percentage").prop('checked') && !$("#money").prop('checked') && !$("#netrate").prop('checked')) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('selectsomethingtocontinue'),$this->lang->line('commissiontype'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#percentage").focus();
                });
                return;
            }
          
            $.ajax({
                type: "POST",
             	dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/saveagency",
                data: $("#agencyC").serialize(),
                beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
                success: function(msg) {
                	unShowWait();
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
	function updateCategory(id,name,active)

	{	
		$("#categoryid").val(id);
		$("#nameup").val(name);
		$("#myonoffswitch").prop('checked',active==0?false:true);
		$("#editcategory").modal().fadeIn('slow');

	}
 	function saveCategory() {
 		
 		 if ($.trim($("#name").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('namecategory'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#name").focus();
                });
                return;
            }
            $.ajax({
                type: "POST",
              dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/saveagencycategory",
                data: {'name':$("#name").val()},
                success: function(msg) {
                   if (!msg['success']) {
                     swal({
                            title: "Upps",
                            text: '<?=$this->lang->line('sww')?>!!',
                            icon: "warning",
                            button: "Ok!",
                        }).then((n) => {
                    		$("#name").focus();
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
                    		document.location.reload();
                		});
                        
                   }
                  
                }
            });
 	}
 	function updatesCategory() {
 		
 		 if ($.trim($("#nameup").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('namecategory'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#nameup").focus();
                });
                return;
            }
            $.ajax({
                type: "POST",
              dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/updateagencycategory",
                data: {'name':$("#nameup").val(),'active':($("#myonoffswitch").prop('checked')?1:0),'id':$("#categoryid").val()},
                success: function(msg) {
                   if (!msg['success']) {
                     swal({
                            title: "Upps",
                            text: '<?=$this->lang->line('sww')?>!!',
                            icon: "warning",
                            button: "Ok!",
                        }).then((n) => {
                    		$("#name").focus();
                		});

                     return;
                   }
                   else
                   {
                   	 swal({
                            title: "",
                            text: '<?=$this->lang->line('su')?>!!',
                            icon: "success",
                            button: "Ok!",
                        }).then((n) => {
                    		document.location.reload();
                		});
                        
                   }
                  
                }
            });
 	}
 	function AllagenciesHTML()
 	{
 		$.ajax({
        type: "POST",
        //dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/agenciesHTML",
        data: {'categoryid':$("#categoryid").val()},
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

           $("#Allagencies").html(msg);
            $('.inline_username').editable(/*{
             url: 'post.php' 
          }*/);

             $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "order": [[ 7, "desc" ]]
            });


        }
    });
 	}

 	jQuery(document).ready(function($) {
 		AllagenciesHTML();
 	});
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