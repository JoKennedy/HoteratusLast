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
            <li class="active">Reservation List</li>
        </ol>
    </div>
    <!--//sub-heard-part-->
    <div style="float: right;" class="buttons-ui">
        <a class="btn orange">Import Reservations Now</a>
        <a onclick="Export()" class="btn green">Export</a>
        <a onclick="setcalendar()" class="btn blue">Add Reservation</a>
    </div>
    <div class="clearfix"></div>
    <div style="float: left;" class="buttons-ui">

        <select onchange="list()" id="channels" class="green">
            <option value="">All Reservations</option>
            <option value="0">Direct Booking</option>
            <?php if (count($AllChannel)>0) {

                            foreach ($AllChannel as  $value) {
                                echo '<option value="'.$value['channel_id'].'">'.$value['channel_name'].'</option>';
                            }
                        } ?>
        </select>
        <select onchange="list()" id="status" class="green">
            <option value="">All Status </option>
            <option value="0">Canceled</option>
            <option value="1">Reserved</option>
            <option value="2">Modified</option>
            <option value="3">No Show</option>
            <option value="4">Confirmed</option>
            <option value="5">Checkin</option>
            <option value="6">Checkout</option>
            <option value="7">Unchecked</option>
        </select>
        <input  id="date1" style="background-color: white; width:200px; " type="text" class="green datepickers" value="" placeholder="">
        <input  id="date2" style="background-color: white; width:200px;" type="text" class="green datepickers" value="" placeholder="">
    </div>
    <div class="clearfix"></div>
    <div id="reservationlist">
    </div>
     <div class="clearfix"></div>
</div>
    <!--//outer-wp-->
    <!--footer section start-->
    <!--footer section end-->
    <div id="CreateReservation" class="modal fade" role="dialog" style="z-index: 1400;">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <?php include("creationreservation.php")?>
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
</div>
<?php include("inforeservationcreate.php");?>
<?php include("findroomcreate.php");?>
<script>
    


$('.datepickers').datepicker();
$(".datepickers").change(function(event) {
    list();
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
    $("#export").modal();
}
function list()
{
       $.ajax({
        type: "POST",
        //dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/reservationlisthtml",
        data: {'date1':$("#date1").val(),'date2':$("#date2").val(),'channels':$("#channels").val(),'status':$("#status").val()},
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

           $("#reservationlist").html(msg);
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
$(document).ready(function() {

  list();
    
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