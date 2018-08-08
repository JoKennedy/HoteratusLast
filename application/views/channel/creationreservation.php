<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <div align="center">
        <h1><span class="label label-primary">Rooms and Rates</span></h1>
    </div>
</div>
<div class="modal-body">
    <div class="col-md-12 label-info" align="center">
        <img src="<?php echo base_url()?>user_assets/images/man.png" class="img img-responsive ">
        <p style="font-size: 16px;" align="center">Please select your check-in and check-out dates as well as the total number of rooms and guests.</p>
    </div>
    <form id="findroomavailable">
        <div class="col-md-12 ">
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Check-In</strong></label>
                <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" id="date1Edit" name="date1Edit">
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Check-Out</strong></label>
                <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" id="date2Edit" name="date2Edit">
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Room Qty</strong></label>
                <select style="width: 100%; padding: 9px; " id="numrooms" name="numrooms">
                    <?php
                        $qry = $this->db->query("SELECT max(existing_room_count) as roommax , max(member_count) as membermax, max(children) as childrenmax
                            FROM `manage_property` WHERE  `hotel_id`='".hotel_id()."'")->result_array();
                        $roommax=$qry[0]['roommax'];
                        $membermax=$qry[0]['membermax'];
                        $childrenmax=$qry[0]['childrenmax'];
                        for ($i=1; $i<=$roommax; $i++) { 
                              echo '<option value="'.$i.'">'.$i.($i==1?' Room':' Rooms'). '</option>';
                            }
                    ?>
                </select>
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Adult Qty</strong></label>
                <select style="width: 100%; padding: 9px; " id="numadult" name="numadult">
                    <?php

                        for ($i=1; $i<=$membermax; $i++) { 
                              echo '<option value="'.$i.'">'.$i.($i==1?' Adult':' Adults'). '</option>';
                            }
                    ?>
                </select>
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Child Qty</strong></label>
                <select style="width: 100%; padding: 9px; " id="numchild" name="numchild">
                    <?php
                        echo '<option value="0">No Children</option>';
                        for ($i=1; $i<=$childrenmax; $i++) { 
                              echo '<option value="'.$i.'">'.$i.($i==1?' Child':' Children'). '</option>';
                            }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-12 text-right">
            <button type="button" onclick="findroomavailable()" class="btn btn-xs btn-info ">Search</button>
        </div>
    </form>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
<div id="infoReservation" class="modal fade" role="dialog" style="z-index: 1800;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="label-primary">
                <h2><span  class="label">Make a Reservation</span></h2>
            </div>
            <div class="graph-form">
                <form id="ReserveC">
                    <input type="hidden" name="roomid" id="roomid">
                    <input type="hidden" name="rateid" id="rateid">
                    <input type="hidden" name="checkin" id="checkin">
                    <input type="hidden" name="checkout" id="checkout">
                    <input type="hidden" name="child" id="child">
                    <input type="hidden" name="numroom" id="numroom">
                    <input type="hidden" name="adult" id="adult">

                    <div style="float: left; width: 65%;">
                        <h4><span >Guest Information</span></h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">First Name</label>
                            <input style="background:white; color:black;" name="firstname" id="firstname" type="text" placeholder="First Name" required="">
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label">Last Name</label>
                            <input style="background:white; color:black;" name="lastname" id="lastname" type="text" placeholder="Last Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="phone" id="phone" type="text" placeholder="Phone Number" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">E-mail</label>
                            <input style="background:white; color:black;"  name="email" id="email" type="text" placeholder="E-Mail" required="" >
                        </div>
                        <hr size="40">
                        <h4>Address Information</h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Street Address</label>
                            <input style="background:white; color:black;"  name="address" id="address" type="text" placeholder="Street Address" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">City</label>
                            <input style="background:white; color:black;"  name="city" id="city" type="text" placeholder="City" required="" >
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">State</label>
                            <input style="background:white; color:black;"  name="state" id="state" type="text" placeholder="State" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label style="padding:4px;" class="control-label controls">Country</label>
                            <select style="width: 100%; padding: 9px;" name="countryid" id="countryid">
                                <?php

                                    $Country=$this->db->query("select * from country order by country_name")->result_array();

                                    echo '<option  value="0" >Select a Country</option>';
                                    foreach ($Country as $value) {
                                        $i++;
                                        echo '<option value="'.$value['id'].'" >'.$value['country_name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-8 form-group1">
                            <label class="control-label">Zip Code</label>
                            <br>
                            <input style="background:white; color:black; "  name="zipcode" id="zipcode" type="text" placeholder="Zip Code" required="">
                        </div>
                        <div class="col-md-4 form-group1">
                            <label class="control-label" style=" padding: 3px; " >Arrival Time</label>
                            <input style="width: 100%; padding: 9px; background:white; color:black;" name="arrival" id="arrival" type="time"  required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Notes</label>
                            <textarea id="note" name="note" placeholder="Type Your Notes"></textarea>
                        </div>

                    </div>
                    <div style="float: right; width: 35%; text-align: left;" class="graph">
                        <h3><span >Stay Information</span></h3>
                        <hr>
                        <table>
                            <tbody>
                                <tr style="padding: 2px;">
                                    <td><strong>Check-In:</strong></td>
                                    <td style="text-align: right;"><span id="checkinr"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Check-Out:</strong></td>
                                    <td style="text-align: right;"><span id="checkoutr"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <hr size="20">
                        <h5><strong >Charges</strong></h5>
                        <table>
                            <tbody>
                                <tr style="padding-bottom: 5px;">
                                    <td width="70%"><strong><span id="chargeinfo"></span></td>
                                    <td style="text-align: right;"><span id="totalstay" ></span></td>
                                </tr>
                                <tr>
                                    <td width="70%" ><strong><span>Extras</span></td>
                                    <td style="text-align: right;"><span id="extrastotal" >0.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div style="text-align: center;">

                            <h2>Due Now</h2>
                            <h3 id="totaldue"></h3>

                        </div>
                        <div class="buttons-ui">
                            <a onclick="saveReservation();" class="btn green">Book</a>
                        </div>
                        
                    </div>
                    <div class="clearfix"> </div>
                  
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
</div>
</div>
<div id="roomsavailable" class="modal fade" role="dialog" style="z-index: 1600;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <h2><span id="fechas" class="label label-primary">FEchas</span></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div id="availabledetails">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
function setcalendar() {
    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date1Edit").attr('min', formatoDate(fecha));
    $("#date1Edit").val(formatoDate(fecha));
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatoDate(fecha));
    $("#date2Edit").val(formatoDate(fecha));
    $("#CreateReservation").modal('show');
}


$("#date1Edit").change(function(event) {
    var fecha = new Date($("#date1Edit").val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatoDate(fecha));
    $("#date2Edit").val(formatoDate(fecha));
});

function findroomavailable() {

    var data = $("#findroomavailable").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/findRoomsAvailable",
        data: data,
        beforeSend: function() {
            showWait('Looking for available rooms for this dates');
            setTimeout(function() { unShowWait(); }, 100000);
        },
        success: function(msg) {
            $("#fechas").html(msg['header']);
            $("#availabledetails").html(msg['detail']);
            $("#roomsavailable").modal()
            unShowWait();
        }
    });
}

function showdetails(id) {

    if ($("#" + id).css('display') == 'none') {
        $("#" + id).css('display', '');
    } else {
        $("#" + id).css('display', 'none');
    }

}

function reservethis(roomid, rateid, date1, date2, adult, numroom, numchild, numnight,totalstay) {

    $("#checkinr").html(date1.substring(8,10)+'/'+date1.substring(5,7)+'/'+date1.substring(0,4));
    $("#checkoutr").html(date2.substring(8,10)+'/'+date2.substring(5,7)+'/'+date2.substring(0,4));
    $("#chargeinfo").html(numnight + (numnight>1?' Nights':' Night') + ' x ' + numroom + (numroom>1?' Rooms':' Room'));
    $("#totalstay").html(totalstay);
    $("#totaldue").html(totalstay);
    $("#roomid").val(roomid);
    $("#rateid").val(rateid);
    $("#checkin").val(date1);
    $("#checkout").val(date2);
    $("#child").val(numchild);
    $("#numroom").val(numroom);
    $("#adult").val(adult);
    
    $("#infoReservation").modal();

}

function saveReservation()
{

    if ($("#firstname").val()=="") {
        $("#firstname").focus();
        document.getElementById('firstname').setCustomValidity("First Name is required");
        return;
    }
    else
    {
        document.getElementById('firstname').setCustomValidity("");
    }
     if ($("#lastname").val()=="") {
        $("#lastname").focus();
        document.getElementById('lastname').setCustomValidity("Last Name is required");
        return;
    }
    else
    {
        document.getElementById('lastname').setCustomValidity("");
    }
   
    if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test($("#email").val()) == false) {
        $("#email").focus();
        document.getElementById('email').setCustomValidity("This Email is not valid");
        return;

    }
    else
    {
         document.getElementById('email').setCustomValidity("");
    }
    if ($("#arrival").val()=="") {
        $("#arrival").focus();
        document.getElementById('arrival').setCustomValidity("The Arrival Time is required");
        return;
    }
    else
    {
        document.getElementById('arrival').setCustomValidity("");
    }
   

    var data = $("#ReserveC").serialize();
    $.ajax({
        type: "POST",
        //dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/saveReservation",
        data: data,
        beforeSend: function() {
            showWait('Saving Reservation');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {

            unShowWait();
            alert(msg);
        }
    });
}
</script>