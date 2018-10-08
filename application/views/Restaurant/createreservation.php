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
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Inhouse</label>
                            <input  onclick="showinhouse(1)" name="rtype" id="rtype" type="radio" value="1" >
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label">OutHouse<input onclick="showinhouse(0)"  name="rtype" id="rtype" type="radio" checked="" value="0" ></label>
                            
                        </div>
                        <div id="findreservation" class="buttons-ui" style="display: none">
                            <a onclick="findreservation()" class="btn green">Search Reservations</a>
                        </div>

                        <div class="col-md-12 form-group1">
                            <label class="control-label">Main Name</label>
                            <input style="background:white; color:black;" name="signer" id="signer" type="text" placeholder="Main Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">
                                <?=($Posinfo['postypeID']==1?'Table':'Treatment Room')?>
                            </label>
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
                            <label class="control-label">Check-In</label>
                            <input style="background:white; color:black; width: 100%" name="hourtime1" id="hourtime1" type="text" placeholder="Hour" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Check-Out</label>
                            <input style="background:white; color:black; width: 100%" name="hourtime2" id="hourtime2" type="text" placeholder="Hour" required="">
                        </div>
                        <div id="room" class="col-md-12 form-group1" style="display: none">
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
<div id="InvoiceInHouse" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reservations</h4>
            </div>

            <div>
                <div id="idinhouse">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<link href="<?php echo base_url();?>user_asset/back/css/jquery.timepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>user_asset/back/js/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    $('#hourtime1').timepicker({ 'timeFormat': 'h:i A','step': 15   });
    $('#hourtime2').timepicker({ 'timeFormat': 'h:i A','step': 15   });

    $("#hourtime1").change(function(event) {
       $('#hourtime2').val($('#hourtime1').val());
    });

    function showinhouse (value) {

        $("#findreservation").css('display', (value==1?'':'none'));
        $("#room").css('display', (value==1?'':'none'));
        $("#signer").prop({'readonly': (value==1?true:false),value:''});
        $("#roomid").prop({'readonly': (value==1?true:false),value:''});
 

    }
    function findreservation()
    {   

         if($("#deadline").val()=='')
         {
            swal({
                    title: "upps, Sorry",
                    text: 'Missing Field Date',
                    icon: "warning",
                    button: "Ok!",
                });
            return;
         }   

         $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo lang_url(); ?>pos/reservationinhouse/"+$("#deadline").val()+"/1",
                data: { "returnhtml": true },
                beforeSend: function() {
                    showWait();
                    setTimeout(function() { unShowWait(); }, 10000);
                },
                success: function(msg) {
                    unShowWait();
                    if (msg["result"]) {

                        $("#idinhouse").html(msg["html"]);
                        $("#totaltopay").html($("#grandtotal").html());
                        $("#InvoiceInHouse").modal();

                    } else {

                        swal({
                            title: "upps, Sorry",
                            text: msg["html"],
                            icon: "warning",
                            button: "Ok!",
                        });
                    }

                }
            });

    }
    function bookroom (name,room) {
        $("#signer").val(name);
        $("#roomid").val(room);
        $("#InvoiceInHouse").modal('toggle');

    }
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
    } else if ($("#hourtime1").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a Check In Hour To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#hourtime2").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a Check Out Hour To Continue!",
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
            if (msg["success"]) {
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