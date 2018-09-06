<div class="outter-wp">
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
            <button style="width: 100%;" type="button" class="btn btn-info" >Group by Date</button>
        </div>
         <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info ">Group by Users</button>
        </div>
         <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info" >Group by Product</button>
        </div>
         <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info">Group by Category</button>
        </div>
        <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info" >Summarized Report </button>
        </div>
         <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info ">Detailed Report</button>
        </div>
         <div class="col-md-4 form-group1">
            <button style="width: 100%;" type="button" class="btn btn-info ">Orders Cancelled</button>
        </div>
       
    </div>
    <div class="clearfix"></div>
    <div id="resultreport">
        
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
               
            }
        });
    }
    function clearresult()
    {
        $("#resultreport").html("");
    }

   
</script>