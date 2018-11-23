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

<div class="outter-wp" style="height: 2000px;">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Manage Reports</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>

        <div class="col-md-12">
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Start Date</strong></label>
                <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="startdate" name="startdate">
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>End Date</strong></label>
                <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="enddate" name="enddate">
            </div>
        </div>

<div class="col-md-12 buttons-ui">
     <div class="col-md-4 form-group1">
        <button onclick="viewreport(1)" style="width: 100%;" type="button" class="btn btn-info" >Registered Guests(In-House)</button>
    </div>
     <div class="col-md-4 form-group1">
        <a onclick="viewreport(2)" style="width: 100%;" type="button" class="btn btn-info ">All Reservations</a>
    </div>
     <div class="col-md-4 form-group1">
        <button onclick="viewreport(3)"  style="width: 100%;" type="button" class="btn btn-info" >Arrivals</button>
    </div>
     <div class="col-md-4 form-group1">
        <button onclick="viewreport(4)" style="width: 100%;" type="button" class="btn btn-info">Departure</button>
    </div>
    <div class="col-md-4 form-group1">
        <button onclick="viewreport(7)" style="width: 100%;" type="button" class="btn btn-info" >Occupancy Report</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">Room Changes</button>
    </div>
    <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info" >Room Status Report</button>
    </div>
     <div class="col-md-4 form-group1">
        <button onclick="viewreport(5)" style="width: 100%;" type="button" class="btn btn-info ">Cancelations</button>
    </div>

     <div class="col-md-4 form-group1">
        <button onclick="viewreport(6)" style="width: 100%;" type="button" class="btn btn-info ">No Show</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">Outstanding Balance </button>
    </div>

</div>


</div>
</div>
</div>
<div id="reportview" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="reportviewsize" class="modal-content">
            <div class="modal-header">

               <center> <h4 class="modal-title">Report View</h4></center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div>
                 <div id="reportid"></div>
            </div>
        </div>
    </div>
</div>
<div id="orderoccupancy" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div  class="modal-content">
            <div class="modal-header">

               <center> <h4 class="modal-title">Occupancy Report</h4></center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
              <div class="col-md-12 form-group1 form-last">
                  <label style="padding:4px;" class="control-label controls">Show By </label>
                  <select style="width: 100%; padding: 9px;" name="occupancyid" id="occupancyid">
                      <?php
                          echo '<option  value="1" >Day</option>';
                          echo '<option  value="2" >Week</option>';
                          echo '<option  value="3" >Month</option>';
                    ?>
                  </select>
              </div>
        </div>
    </div>
</div>
<div id="export" class="modal fade" role="dialog" style="z-index: 1400;">
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
</div>
<script type="text/javascript">

    $(".datepicker").datepicker();

    function viewreport(idreport)
    {

        if ($("#startdate").val().length==0 ) {
             swal({
                    title: "upps, Sorry",
                    text: "Missing Field Start Date",
                    icon: "warning",
                    button: "Ok!",
                });
             return;
        }
         if ($("#enddate").val().length==0 ) {
             swal({
                    title: "upps, Sorry",
                    text: "Missing Field End Date",
                    icon: "warning",
                    button: "Ok!",
                });
             return;
        }

        if(idreport==7)
        {
          $("#orderoccupancy").modal();
        }
        var data={'startdate':$("#startdate").val(),'enddate':$("#enddate").val(),'reportid':idreport};
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>reports/reporttype",
            data: data,
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                unShowWait();
                $("#reportid").html(msg['html']);

                document.title=msg['title'];
                orderby();
                $("#reportview").modal();

            }
        });
    }
</script>
<script>

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
    $("#export").modal();
}
function orderby()
{
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[ 0, "asc" ]]
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
