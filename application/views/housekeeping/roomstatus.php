<style type="text/css" media="screen">
.dt-buttons {
    float: left;
}

.buttons-excel,
.buttons-csv,
.buttons-copy,
.buttons-pdf,
.buttons-print {
    display: none;
}

.dataTables_filter input {
    color: black;
}
</style> 	

<div class="outter-wp" style="height: 3000px;">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Housekeeping</li>
				<li class="active">Rooms Status</li>
			</ol>
		   </div>
			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">
				    <div style="float: left;" class="buttons-ui">

				        <select onchange="List()" id="HousekeepingStatusId" class="green">
				            <option value="0">All Status</option>
				            <?php if (count($AllStatus)>0) {

				                            foreach ($AllStatus as  $value) {
				                                echo '<option value="'.$value['HousekeepingStatusId'].'">'.$value['Name'].'</option>';
				                            }
				                        } ?>
				        </select>
				    </div>
				<div style="float: right; " class="buttons-ui">
			        <a href="#createstatus" data-toggle="modal" class="btn blue">Add New Status</a>
			        <a onclick="Export()" class="btn green">Export</a>
			    </div>
			    <div  class="clearfix"></div>					
				<div class="graph">
					
					<div id="AllRoomId"></div>
			
				</div>
				<div  class="clearfix"></div>
			</div>
		</div>

	</div>
</div>
<div id="createstatus" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> </button>
            </div>
            <div>
                <div class="graph-form">
                    <form id="statusC">
                        
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Status</label>
                            <input style="background:white; color:black;" name="statusname" id="statusname" type="text" placeholder="Status Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Code</label>
                            <input style="background:white; color:black;" name="code" id="code" type="text" placeholder="Status Code" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Color</label>
                            <input  style="background:white; color:black; text-align: center;" name="color" id="color" type="text" placeholder="Status Color"  required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveStatus()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="export" class="modal fade" role="dialog" style="z-index: 1400; ">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div align="center">
                        <h1><span class="label label-primary">Options to Import</span></h1>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="buttons-ui">
                        <a onclick="csv()" class="btn orange">CSV</a>
                        <a onclick="Excel()" class="btn green">Excel</a>
                        <a onclick="PDF()" class="btn yellow">PDF</a>
                        <a onclick="PRINT()" class="btn blue">Print</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
</div>
<div id="changestatus" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Change Status</h4>
                    <button id="closechange" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> </button>
            </div>
            <div>
                <div class="graph-form">
                    <form id="statusUP">
                    	<input type="hidden" name="roomid" id="roomid" value="">
                    	<input type="hidden" id="oldstatusid" value="">
                    	<input type="hidden" id="RoomNumber" name="RoomNumber" value="">
                         <div class="col-md-12 form-group1">
                           <center> <label class="control-label">Room Number</label>
                            <h3><span class="label label-primary" id="roomnumber"></span></h3></center>
                        </div>
                        <div class="col-md-12 form-group1">
                            <center><label class="control-label">Room Type</label>
                            <h3><span class="label label-primary" id="roomtype"></span></h3></center>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Status</label>
                            <select style="width: 100%; padding: 9px; "  id="StatusId" name="StatusId" >
			            		<?php if (count($AllStatus)>0) {

			                            foreach ($AllStatus as  $value) {
			                                echo '<option id="'.$value['Color'].'" value="'.$value['HousekeepingStatusId'].'">'.$value['Name'].'</option>';
			                            }
			                        } ?>
				        	</select>
                        </div>
                       
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="updateStatus()" class="btn green">Change</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>user_asset/back/js/colorpicker.js"></script>
<script type="text/javascript">
	$('#color').simpleColor();


	function changeStatus(roomid,roomnumber,statusid,roomtype)
	{	
		$("#roomid").val(roomid);
		$("#oldstatusid").val(statusid);
		$("#roomnumber").html(roomnumber);
		$("#RoomNumber").val(roomnumber);
		$("#roomtype").html(roomtype);
		$("#StatusId").val(statusid);
		$("#changestatus").modal();
	}
	function List()
	{
		$.ajax({
		        type: "POST",
		        //dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/RoomListHTML",
		        data: {'status':$("#HousekeepingStatusId").val()},
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		           $("#AllRoomId").html(msg);

		             $('#myTable').DataTable({
		                dom: 'Bfrtip',
		                buttons: [
		                    'copy', 'csv', 'excel', 'pdf', 'print'
		                ],
		                "order": [[ 0, "asc" ]]
		            });


		        }
		    });
	}
	function saveStatus()
	{	


		if ($("#statusname").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Name To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#code").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Code To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#color").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Select a Status Color To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}

		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/saveStatus",
		        data: $("#statusC").serialize(),
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		            if (msg["success"]) {
		                swal({
		                    title: "Success",
		                    text: "Status Created!",
		                    icon: "success",
		                    button: "Ok!",
		                }).then((n) => {
		                    location.reload();
		                });
		            } else {

		                swal({
		                    title: "upps, Sorry",
		                    text:  msg["message"],
		                    icon: "warning",
		                    button: "Ok!",
		                });
		            }

		        }
		    });
	}
	function updateStatus()
	{	

		
		if ($("#StatusId").val()==0 || $("#StatusId").val()==null) {
			 swal({
            title: "upps, Sorry",
            text: "Select a Status To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}
		if ($("#oldstatusid").val()==$("#StatusId").val()) {
			 swal({
            title: "upps, Sorry",
            text: "You Must Select a Different Status To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}
		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/updateStatus",
		        data: $("#statusUP").serialize(),
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		            if (msg["success"]) {
		                swal({
		                    title: "Success",
		                    text: "Status Changed!",
		                    icon: "success",
		                    button: "Ok!",
		                }).then((n) => {
		                	//<a  >'.$value['HousekeepingStatus'].' </a>


		                	var update ="'"+$("#roomid").val()+"','"+$("#RoomNumber").val()+"','"+$("#StatusId").val()+"','"+$("#name"+$("#roomid").val()+"r"+$("#RoomNumber").val()).html()+"'";
		                	var combo = document.getElementById("StatusId");
							var selected = combo.options[combo.selectedIndex].text;
							$("#row"+$("#roomid").val()+"r"+$("#RoomNumber").val()).css('background-color',combo.options[combo.selectedIndex].id);

		                    $("#"+$("#roomid").val()+"r"+$("#RoomNumber").val())
		                    .html('<h3><span  class="label label-primary"><a style="color:white" onclick="changeStatus('+update+')" data-toggle="tooltip" data-placement="bottom" title="Change Status">'+selected+' <i class="fa fa-exchange-alt"></i></a></span></h3>');
		                    $("#closechange").trigger('click');

		                });
		            } else {

		                swal({
		                    title: "upps, Sorry",
		                    text:  msg["message"],
		                    icon: "warning",
		                    button: "Ok!",
		                });
		            }

		        }
		    });
	}
	$(function() {
	    $('.tabs nav a').on('click', function() {
	        show_content($(this).index());
	    });

	    show_content(0);

	    function show_content(index) {
	        // Make the content visible
	        $('.tabs .context.visible').removeClass('visible');
	        $('.tabs .context:nth-of-type(' + (index + 1) + ')').addClass('visible');

	        // Set the tab to selected
	        $('.tabs nav.second a.selected').removeClass('selected');
	        $('.tabs navnav.second a:nth-of-type(' + (index + 1) + ')').addClass('selected');
	    }
	});

	$(document).ready(function() {

  		List();
    
	});
	function csv() {
    	$(".buttons-csv").trigger("click");
	}

	function Excel() {
	    $(".buttons-excel").trigger("click");
	}

	function PDF() {
	    $(".buttons-pdf").trigger("click");
	}

	function PRINT() {
	    $(".buttons-print").trigger("click");
	}

	function Export() {
		List();
	    $("#export").modal();
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