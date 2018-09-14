<div class="outter-wp">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Reservations</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a href="#createbook" data-toggle="modal" class="btn blue">Add New Reservation</a>
    </div>
    <div class="clearfix"></div>

     <div style="width: 100%;" class="table-responsive">
         <center>
             <div class="col-md-2 form-group1">
                <label class="control-label">Date</label>
                <input onchange="showcalendar()" class="datepickers" style="background:white; color:black;" name="dateC" id="dateC" type="text" placeholder="Select a Date" required="">
            </div>
        </center>
        <div class="col-md-12 form-group1" id="calendario"> </div>
    </div>   
    <div class="clearfix"></div>
</div>
<div id="createbook" class="modal fade" role="dialog" aria-hidden="true">
       <?=include('createreservation.php')?>
</div>

</div>
</div>
<script type="text/javascript">
$('.datepickers').datepicker({minDate:new Date(),dateFormat: 'mm/dd/yy'});

function showcalendar()
{   
    var data={'dateC':$("#dateC").val(),'posid':'<?=$Posinfo['myposId']?>'};
    $.ajax({
        type: "POST",
        url:  '<?php echo lang_url(); ?>pos/calendarFull',
        data: data,
        beforeSend: function() {
            showWait('Update Calendar, Please Wait');
            setTimeout(function() { unShowWait(); }, 100000);
        },
        success: function(html) {
            $("#calendario").html(html);
            unShowWait();

        }
    });
}
$(document).ready(function() {
    $('.datepickers').datepicker( "setDate" , new Date() );
    showcalendar();
    
});




</script>