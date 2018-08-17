

<div class="outter-wp">
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
        <button style="width: 100%;" type="button" class="btn btn-info" >Registered Guests(In-House)</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">All Reservations</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info" >Arrivals</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info">Departure</button>
    </div>
    <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info" >Occupancy Report</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">Room Changes</button>
    </div>
    <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info" >Room Status Report</button>
    </div>
     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">Cancelations</button>
    </div>

     <div class="col-md-4 form-group1">
        <button style="width: 100%;" type="button" class="btn btn-info ">No Show</button>
    </div>  
   
</div>


</div>
</div>
</div>

<script type="text/javascript">
    
    $(".datepicker").datepicker();
</script>