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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Create a Reservation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="bookC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Main Name</label>
                            <input style="background:white; color:black;" name="signer" id="signer" type="text" placeholder="Main Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><?=($Posinfo['postypeID']==1?'Table':'Treatment Room')?></label>
                            <select style="width: 100%; padding: 9px; " id="tableid" name="tableid">
                                <?php
                                    if(count($AllTable)>0)
                                    {
                                        echo '<option value="0">Select a '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' </option>'; 
                                        foreach ($AllTable as  $value) {
                                            echo '<option value="'.$value['postableid'].'">'.$value['description'].'==>Cap:'.$value['qtyPerson'].'</option>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<option value="0">there are no '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Date</label>
                            <input class="datepickers" style="background:white; color:black;" name="deadline" id="deadline" type="text" placeholder="Select a Date" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Hour [18:00]</label>
                            <input style="background:white; color:black; width: 100%" name="hourtime" id="hourtime" type="time" placeholder="Main Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Room Number</label>
                            <input style="background:white; color:black; " name="roomid" id="roomid" type="text" placeholder="Room Number" required="">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveReservation()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
<script type="text/javascript">
$('.datepickers').datepicker({minDate:new Date(),dateFormat: 'mm/dd/yy'});

function showcalendar()
{   
    var data={'dateC':$("#dateC").val()};
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

function saveReservation() {

    var data = $("#bookC").serialize();

    if ($("#signer").val() <= 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Main Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#tableid").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Selected a Table  To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#deadline").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Selected a Date To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#hourtime").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a Hour To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveReservation",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["success"] ) {
                swal({
                    title: "Success",
                    text: "Book Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["msg"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });

}



</script>