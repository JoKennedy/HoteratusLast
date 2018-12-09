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
                <label class="control-label"><strong>Number of Rooms</strong></label>
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
                <label class="control-label"><strong>Number of Adults</strong></label>
                <select style="width: 100%; padding: 9px; " id="numadult" name="numadult">
                    <?php

                        for ($i=1; $i<=$membermax; $i++) { 
                              echo '<option value="'.$i.'">'.$i.($i==1?' Adult':' Adults'). '</option>';
                            }
                    ?>
                </select>
            </div>
            <div class="col-md-6 form-group1">
                <label class="control-label"><strong>Number of Children</strong></label>
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

<script type="text/javascript">

var taxes='';

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
            taxes= JSON.parse(msg['taxes']);
            $("#CreateReservation").modal().fadeOut();
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
   
    $("#newprice").val((formatMoney(totalstay)!=formatMoney(newmonto)?newmonto:-1));
    totalstay=(totalstay!=newmonto?newmonto:totalstay)*numroom;


    if(taxes.length>0)
    { var taxhtml ='';
        var totaltax=0;
        var taxvalue =0;
        var stay=totalstay;
        jQuery.each( taxes, function( i, val ) {
            taxvalue=stay*(val.taxrate/100);
             taxhtml +='<tr style="padding-bottom: 5px;"><td><strong>'+val.name+' </td><td style="text-align: right;"> &nbsp;'+formatMoney(taxvalue)+'</td></tr>';
            if(val.includedprice==0)
            {
                totaltax+=taxvalue;
            }
        });

        $("#totales tbody").append(taxhtml);
    }
    
    $("#checkinr").html(date1.substring(8,10)+'/'+date1.substring(5,7)+'/'+date1.substring(0,4));
    $("#checkoutr").html(date2.substring(8,10)+'/'+date2.substring(5,7)+'/'+date2.substring(0,4));
    $("#chargeinfo").html(numnight + (numnight>1?' Nights':' Night') + ' x ' + numroom + (numroom>1?' Rooms':' Room'));
    $("#totalstay").html(formatMoney(totalstay));
    $("#totaldue").html(formatMoney(totalstay+totaltax));
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

    $("#roomsavailable").modal().fadeOut();
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