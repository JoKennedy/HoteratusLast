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
                            <label class="control-label">Reservation Type</label>
                            <input style="background:white; color:black;" name="reservationtype" id="reservationtype" type="radio" placeholder="Main Name" required="">
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

<link href="<?php echo base_url();?>user_asset/back/css/jquery.timepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>user_asset/back/js/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    $('#hourtime1').timepicker({ 'timeFormat': 'H:i:A' });
    $('#hourtime2').timepicker({ 'timeFormat': 'h:i A' });
</script>