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
        <button style="width: 100%;" type="button" class="btn btn-info" >Group by Date</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">Group by Users</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info" >Group by Item</button>
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
   
</div>


</div>
</div>
</div>

<script type="text/javascript">
    
    $(".datepicker").datepicker();
</script>