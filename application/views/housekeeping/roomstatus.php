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
h3.popover-title {
  color:black;
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
                <select onchange="changeList(this.value)" id="displaynumber" class="green">
                    <option value="10">10</option>
                    <option value="25" >25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                    <option value="200">200</option>

                </select>
				        <select onchange="List()" id="HousekeepingStatusId" class="green">
				            <option value="-1" selected>All Status</option>
				            <?php if (count($AllStatus)>0) {

				                            foreach ($AllStatus as  $value) {
				                                echo '<option value="'.$value['value'].'">'.$value['text'].'</option>';
				                            }
				                        } ?>
				        </select>
                <select onchange="List()" id="RoomTypeId" class="green">
				            <option value="0" selected>All Rooms Type</option>
				            <?php if (count($AllRooms)>0) {

				                            foreach ($AllRooms as  $value) {
				                                echo '<option value="'.$value['property_id'].'">'.$value['property_name'].'</option>';
				                            }
				                        } ?>
				        </select>


				    </div>
				<div style="float: right; " class="buttons-ui">
			        <a href="#createstatus" data-toggle="modal" class="btn blue">Add New Status</a>
			        <a onclick="Export()" class="btn green">Export</a>
              <a onclick="ShowBulk()" class="btn red">Bulk Update Status</a>
			    </div>

          <div  class="clearfix"></div>
          <form id="informacion">
              <div class="bulkupdate graph" style="display:none;">
                <center>  <h4><span class="label label-default">Bulk Update Housekeeping Status</span></h4>

                  <div class="col-md-12 form-group1">
                      <label class="control-label">Housekeeping Status</label>
                      <select style="width: 100%; padding: 9px;" name="statusbulk" id="statusbulk">
                         <option value="0" selected>Select a Status</option>
                         <?php
                            if (count($AllStatus)>0) {
                              foreach ($AllStatus as  $value) {
                                  echo '<option value="'.$value['value'].'">'.$value['text'].'</option>';
                              }
                            }
                          ?>
                     </select>
                  </div>
                  <br>
                  <div class="col-md-12">

                      <a onclick="updatestatusbulk()" class="btn green">Update</a>
                  </div>
                  </center>

                  <div  class="clearfix"></div>
              </div>

      				<div class="graph">

      					<div id="AllRoomId"></div>

      				</div>
        </form>
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

<script src="<?php echo base_url();?>user_asset/back/js/colorpicker.js"></script>
<script type="text/javascript">
var bulkupdate =0;
var vcount =0;
	$('#color').simpleColor();
  function updatestatusbulk(){

    if ($("#statusbulk").val()==0) {
       swal({
            title: "upps, Sorry",
            text: "Select a Status To Continue!",
            icon: "warning",
            button: "Ok!",
          });
          return;
    }

    $('input[class=select]:checked').each(function() {
      vcount =1;
    });

    if(vcount==0)
    {
      swal({
           title: "upps, Sorry",
           text: "Select a Room Number To Continue!",
           icon: "warning",
           button: "Ok!",
         });
         return;
    }
vcount=0;

    $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo lang_url(); ?>housekeeping/updateStatusBulk",
          data: $("#informacion").serialize(),
          success: function(msg) {
              if (msg["success"]) {
                $('input[class=select]:checked').each(function() {
                  $("#row"+$(this).prop("id")).css('background-color',msg['color']);
                    $("#row"+$(this).prop("id")+' a').attr('data-value',msg['id']);
                    $("#row"+$(this).prop("id")+' a').html(msg['name']);
                  $(this).prop("checked",false);
                });

                ShowBulk();

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
  function ShowBulk()
  {
    bulkupdate =(bulkupdate==0?1:0);
    $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));

  }
  function changeList(number)
  {
      $('#myTable').DataTable({
         dom: 'Bfrtip',
         "destroy":true,
         "displayLength": number,
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
         "order": [[ 0, "asc" ]]
     });

     if(bulkupdate==1){
       $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));
     }

  }
	function List()
	{
		$.ajax({
		        type: "POST",
		        //dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/RoomListHTML",
		        data: {'status':$("#HousekeepingStatusId").val(),'roomid':$("#RoomTypeId").val()},
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		           $("#AllRoomId").html(msg);
               $('.inline_username').editable({
                 url: function (params) {
                    return updateStatus(params);
                 }
             });
		             $('#myTable').DataTable({
		                dom: 'Bfrtip',
                    "displayLength": $("#displaynumber").val(),
		                buttons: [
		                    'copy', 'csv', 'excel', 'pdf', 'print'
		                ],
		                "order": [[ 0, "asc" ]]
		            });
                $('#myTable_paginate').click(function(){
                  $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));
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
	function updateStatus(params)
	{
    var data={'name':params['name'],'pk':params['pk'],'value':params['value']};
    pk=params['pk'].split(',');

		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/updateStatus",
		        data: data,
		        success: function(msg) {
		            if (msg["success"]) {
		               $("#row"+pk[0]+"r"+pk[1]).css('background-color',msg['color']);
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
