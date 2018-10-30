<style type="text/css" media="screen">
 .change_price span {
    background-color: #ebd66f;
    cursor: pointer;
    font-size: 12px;
    padding: 10px;
    text-transform: capitalize;
    vertical-align: middle;
}  
.change_price .inr_cont {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #f7f7f7;
    border-color: #31b0d5 #dddddd #dddddd;
    border-image: none;
    border-style: solid;
    border-width: 5px 1px 1px;
    box-shadow: 0 2px 2px #939393;
    display: none;
    height: auto;
    padding: 5px;
    position: absolute;
    top: 42px;
    width: 100%;
} 
.change_price .inr_cont p {
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
}

.change_price .inr_cont input {
    height: 35px;
    margin: 0 0 5px;
    width: 100%;
}


</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <div align="center">
        <h1><span class="label label-primary">Rooms and Rates</span></h1>
    </div>
</div>
<div class="modal-body">
    <div class="col-md-12 label-info" align="center">

        <img style="width: 200px; height: 90px;" src="<?php echo base_url();?>user_asset/back/images/reception.png" >
        <p style="font-size: 16px; font-style: bold;" align="center">Please select your check-in and check-out dates as well as the total number of rooms and guests.</p>
    </div>
    <form id="findroomavailable">
        <div class="col-md-12 ">
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Check-In</strong></label>
                <input class="datepickers" style="background:white; color:black; text-align: center;" type="text" class="btn blue" required="" id="date1Edit" name="date1Edit">
            </div> 
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Check-Out</strong></label>
                <input class="datepickers" style="background:white; color:black; text-align: center;" type="text" class="btn blue" required="" id="date2Edit" name="date2Edit">
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
<div id="infoReservation" class="modal fade" role="dialog" style="z-index: 1800;">
    <div class="modal-dialog " id="reserid">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="label-primary">
                <center><h2><span  class="label">Make a Reservation</span></h2></center>
            </div>
            <div class="graph-form">
                <form id="ReserveC">
                    <input type="hidden" name="roomid" id="roomid">
                    <input type="hidden" name="rateid" id="rateid">
                    <input type="hidden" name="checkin" id="checkin">
                    <input type="hidden" name="checkout" id="checkout">
                    <input type="hidden" name="child" id="child">
                    <input type="hidden" name="numroom" id="numroom">
                    <input type="hidden" name="newprice" id="newprice">
                    <input type="hidden" name="adult" id="adult">
                    <input type="hidden" name="username" id="username" value="<?=$fname.' '.$lname?>">
                    

                    <div style="float: left; width: 65%;">
                        <h4><span >Main Guest Information</span></h4>
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
                        <div style="float:right;" class="col-md-6 form-group1">
                            
                            <input style="background:white; color:black;" type="checkbox" name="sendemail" id="sendemail" value="1" type="text" > <label for="sendemail">Send Confirmation Email?</label> 
                        </div>
                        <div class="clearfix"></div>
                        <hr size="40">
                        <div id="guestnames">
                        <h4>Names Guests </h4>

                        <div id="allguest"></div>

                        <div class="clearfix"></div>
                        <hr size="40">
                        </div>
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

                        <center>

                             <div class="col-md-12 form-group1">
                                <label style="padding:4px;" class="control-label controls"><h1><span class="label label-danger">SOURCE</span></h1></label>
                                <select style="width: 80%; padding: 9px;" name="sourceid" id="sourceid">
                                    <?php

                                        $Channelss=$this->db->query("select channel_name, channel_id from manage_channel order by channel_name")->result_array();

                                        echo '<option  value="-1" >Select a Reservation Source</option>';
                                        echo '<option style="background:#71FF33;" value="0" selected >Direct Reservation</option>';
                                        foreach ($Channelss as $value) {
                                            $i++;
                                            echo '<option  value="'.$value['channel_id'].'" >'.$value['channel_name'].'</option>';
                                        }
                                  ?>
                                </select>
                                 <br>
                                <hr>
                            </div>

                        </center>


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
                        <div class="col-md-12 form-group1">
                            <?php include("paymentreservations.php"); ?>
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

$("#reserid").css({
    width:screen.width-200,
    height:screen.height
});
$('.datepickers').datepicker();
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
            setTimeout(function() {unShowWait(); }, 10000);
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
function formatMoney(n, c, d, t) {
  var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;

  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

function format(val)
{
    var num = val;
    if(!isNaN(num)){
    num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    num = num.split('').reverse().join('').replace(/^[\.]/,'');
    
    }
     
   return num;
}

function reservethis(roomid, rateid, date1, date2, adult, numroom, numchild, numnight,totalstay,idreplace) {
    var pricenew=$("#price_"+idreplace).html().replace(',','');
    var newmonto= pricenew*numnight;

    $("#newprice").val((totalstay!=newmonto?newmonto:-1));
    totalstay=(totalstay!=newmonto?newmonto:totalstay);

    $("#checkinr").html(date1.substring(8,10)+'/'+date1.substring(5,7)+'/'+date1.substring(0,4));
    $("#checkoutr").html(date2.substring(8,10)+'/'+date2.substring(5,7)+'/'+date2.substring(0,4));
    $("#chargeinfo").html(numnight + (numnight>1?' Nights':' Night') + ' x ' + numroom + (numroom>1?' Rooms':' Room'));
    $("#totalstay").html(formatMoney(totalstay*numroom));
    $("#totaldue").html(formatMoney(totalstay*numroom));
    $("#roomid").val(roomid);
    $("#rateid").val(rateid);
    $("#checkin").val(date1);
    $("#checkout").val(date2);
    $("#child").val(numchild);
    $("#numroom").val(numroom);
    $("#adult").val(adult);
    $("#allguest").html('');


    $("#guestnames").css('display',(adult<=1?'none':''));
    for (var i = 1; i < adult; i++) {
        
        $("#allguest").append( '<div class="col-md-6 form-group1"><label class="control-label">Guest #'+i+'</label><input style="background:white; color:black;" name="guestname[]" id="guestname" type="text" placeholder="Type a Guest Name #'+i+'" required=""></div>');
    }
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
   
    if($("#paymentTypeId").val()==0)
    {
        swal({
                title: "Missing Field",
                text: "Select a Method Type To Continue!",
                icon: "warning",
                button: "Ok!",
                });
        return;
    }

    if($("#paymentTypeId").val()>1)
    {
        if($("#providerid").val()==0)
        {
            swal({
                    title: "Missing Field",
                    text: "Select a Collection Type To Continue!",
                    icon: "warning",
                    button: "Ok!",
                    });
            return;
        }


        if($("#providerid").val()>0)
        {
            
            if($("#cctype").val().length==0)
                {
                    swal({
                            title: "Missing Field",
                            text: "Type a CC Type To Continue!",
                            icon: "warning",
                            button: "Ok!",
                            });
                    return;
                }
                else if($("#ccholder").val().length==0)
                {
                    swal({
                            title: "Missing Field",
                            text: "Type a CC Holder To Continue!",
                            icon: "warning",
                            button: "Ok!",
                            });
                    return;
                }
                else if($("#ccnumber").val().length==0)
                {
                    swal({
                            title: "Missing Field",
                            text: "Type a CC Number To Continue!",
                            icon: "warning",
                            button: "Ok!",
                            });
                    return;
                }

                else if($("#ccmonth").val().length==0)
                {
                    swal({
                            title: "Missing Field",
                            text: "Type a CC Month To Continue!",
                            icon: "warning",
                            button: "Ok!",
                            });
                    return;
                }
                else if($("#ccyear").val().length==0)
                {
                    swal({
                            title: "Missing Field",
                            text: "Type a CC Year To Continue!",
                            icon: "warning",
                            button: "Ok!",
                            });
                    return;
                }
        
             
        }



    }


    if($("#providerid").val()>0)
    {

            $.ajax({
                type: "POST",
              dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/ValidateCreditCard",
                data: {'cccard':$("#ccnumber").val()},
                success: function(msg) {
                   if (!msg['success']) {
                     swal({
                            title: "Warning",
                            text: "Credic Card Invalid!",
                            icon: "warning",
                            button: "Ok!",
                        });

                     return;
                   }
                   else
                   {
                        reservationDone();
                   }
                  
                }
            });
    } 
    else{
        reservationDone();
    }




    
}

function reservationDone()
{
    var data = $("#ReserveC").serialize();
    $.ajax({
        type: "POST",
      dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/saveReservation",
        data: data,
        beforeSend: function() {
            showWait('Saving Reservation');
            setTimeout(function() { unShowWait(); }, 100000);
        },
        success: function(msg) {
        
            unShowWait();
           if (msg['success']) {
             swal({
                    title: "Success",
                    text: "Reservation successfully created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    window.location.href =msg['url'];
                });
           }
           else
           {
                swal({
                    title: "Error",
                    text: "Reservation was not created!",
                    icon: "danger",
                    button: "Ok!",
                });
           }
        }
    });
}


</script>