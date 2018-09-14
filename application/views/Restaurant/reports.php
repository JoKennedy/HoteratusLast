
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
<div class="outter-wp" style="height: 4000px;">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Salest Reports</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
        
        <div class="col-md-12">
            <div class="col-md-4 form-group1">
                <label class="control-label"><strong>Start Date</strong></label>
                <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="startdate" name="startdate">
            </div>
            <div class="col-md-4 form-group1">
                <label class="control-label"><strong>End Date</strong></label>
                <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="enddate" name="enddate">
            </div>
            <div class="col-md-4 form-group1">

                <button onclick="clearresult()"  type="button" class="btn btn-warning">Clear Results</button>
            </div>
        </div>

    <div class="col-md-12 buttons-ui">
         <div class="col-md-4 form-group1">
            <button onclick="ShowReports(7)" style="width: 100%;" type="button" class="btn btn-info" >Group by Order</button>
        </div>
         <div class="col-md-4 form-group1">
            <button onclick="ShowReports(1)" style="width: 100%;" type="button" class="btn btn-info" >Group by Date</button>
        </div>
         <div class="col-md-4 form-group1">
            <button onclick="ShowReports(2)" style="width: 100%;" type="button" class="btn btn-info ">Group by Users and Date</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(9)" style="width: 100%;" type="button" class="btn btn-info ">Group by Employee and Date</button>
        </div>
         <div class="col-md-4 form-group1">
            <button onclick="ShowReports(8)" style="width: 100%;" type="button" class="btn btn-info ">Orders Cancelled</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Day Sales</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Closure of the Day</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Cost of the day</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Inventory</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Hours worked by employees</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Profit and loss report</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Sales report by product type</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Product popularity report</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Recipe</button>
        </div>
        <div class="col-md-4 form-group1">
            <button onclick="ShowReports(0)" style="width: 100%;" type="button" class="btn btn-info ">Product Prices</button>
        </div>

    </div>
    <div class="clearfix"></div>
    <div id="resultreport">
        
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
</div>

<script type="text/javascript">
    var posid="<?=$Posinfo['myposId']?>";
    $(".datepicker").datepicker();

    function ShowReports(type) {

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


        var data={'startdate':$("#startdate").val(),'enddate':$("#enddate").val(),'type':type,'posid':posid};
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>pos/salesReport",
            data: data,
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                unShowWait();
                $("#resultreport").html(msg['html']);
                orderby();
               
            }
        });
    }
    function clearresult()
    {
        $("#resultreport").html("");
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
        "order": [[ 1, "desc" ]]
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