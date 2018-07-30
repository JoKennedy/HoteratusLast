 

 function delete_extras(id, res, channelid, des, fila) {
     swal({
             title: "Are you sure?",
             text: "Do you want to Delete this Extra?",
             icon: "warning",
             buttons: true,
             dangerMode: true,
         })
         .then((willDelete) => {
             if (willDelete) {
                 $.ajax({
                     type: "POST",
                     url: "<?php echo lang_url(); ?>reservation/delete_extra",
                     data: { "extra_id": id, "reservation_id": res, "channelId": channelid, "description": des },
                     success: function(msg) {
                         //alert(msg);
                         fila2 = document.getElementById(fila);

                         fila2.style.display = "none";

                     }
                 });
                 swal("Extra removed", {
                     icon: "success",
                 }).then(ms => {
                     location.reload();
                 });
             } else {
                 return false;
             }
         });
 }

 $('#submitextra').click(function() {
    $('#addExtras').submit();
})

function saveExtra() {
    var formulariop = document.getElementById('addExtras');
    var selected = 0;
    var exist = 0;
    var ids = new Array(25);
    var count = 0;
    var rid = '<?php echo insep_encode($reservatioID); ?>';
    var cid = '<?php echo $channelId; ?>';
    var user = '<?php echo $fname." ".$lname;?>';

    for (var i = 0; i < formulariop.elements.length; i++) {
        if (formulariop.elements[i].name.indexOf("extra") !== -1) {
            exist = 1;
            if (formulariop.elements[i].checked) {
                selected = 1;

                ids[count] = formulariop.elements[i].id + ',' + formulariop.elements[i].value + ',' + $("#" + formulariop.elements[i].id).attr('desc');
                count++;

            }
        }
    }

    if (exist == 0) {
        $('#msguser').removeClass();
        $('#msguser').addClass('alert alert-danger');
        $('#msguser').html('There are no extras to add');
        $('#msguser').toggle("slow");
        setTimeout(function() {
            $('#msguser').fadeOut();
        }, 5000);
        return false;
    }
    if (selected == 0) {
        $('#msguser').removeClass();
        $('#msguser').addClass('alert alert-warning');
        $('#msguser').html('Select an Extra to Continue');
        $('#msguser').toggle("slow");
        setTimeout(function() {
            $('#msguser').fadeOut();
        }, 5000);
        return false;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/saveExtras",
        data: { "extraId": ids, "reservationId": rid, "channelId": cid, "userName": user },
        success: function(msg) {
            swal({
                title: "Done!",
                text: "Extras Added Successfully!",
                icon: "success",
                button: "Ok!",
            }).then(ms => {
                location.reload();
            });
        }
    });


    return false;
}

function payment(invoiceid, due) {

    if (due <= 0) {

        $('#msginvoice').removeClass();
        $('#msginvoice').addClass('alert alert-warning');
        $('#msginvoice').html('This invoice has no outstanding balance');
        $('#msginvoice').toggle("slow");
        setTimeout(function() {
            $('#msginvoice').fadeOut();
        }, 5000);
        return;

    }
    $('#amountdue').val(Number(due).toFixed(2));
    $('#invoiceid').val(invoiceid);
    $('#PaymentP').modal({
        show: 'false'
    });

}

function Method(methodid) {

    if (methodid == 'cc') {
        $("#metocc").show();
    } else {
        $("#metocc").hide();
        return;
    }
}

$("#submitpay").click(function() {

    var pid=$("#paymentTypeId").val();
    var metid=$("#paymentmethod").val(); 
    var invoid=$('#invoiceid').val();
    var amount=$('#amountdue').val();
    var user = '<?php echo $fname." ".$lname;?>';

    if (amount <= 0) {

        $('#msginvoice').removeClass();
        $('#msginvoice').addClass('alert alert-warning');
        $('#msginvoice').html('This invoice has no outstanding balance');
        $('#msginvoice').toggle("slow");
        setTimeout(function() {
            $('#msginvoice').fadeOut();
        }, 5000);
        return;

    }

    if (pid==0) {

        $('#msgpayment').removeClass();
        $('#msgpayment').addClass('alert alert-danger');
        $('#msgpayment').html('Select a Payment Type to Continue');
        $('#msgpayment').toggle("slow");
        setTimeout(function() {
        $('#msgpayment').fadeOut();
        }, 5000);
         return;
    }

    if (metid==0 && pid !=2) {

        $('#msgpayment').removeClass();
        $('#msgpayment').addClass('alert alert-danger');
        $('#msgpayment').html('Select a Collection Type to Continue');
        $('#msgpayment').toggle("slow");
        setTimeout(function() {
        $('#msgpayment').fadeOut();
        }, 5000);
         return;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/invoicepaymentapply",
        data: {  "reservationinvoiceid": invoid, "paymenttypeid": pid,'amount':amount,'paymentmethod':metid,'username':user},
        success: function(msg) {

            if (msg==0) {

                swal({
                    title: "Done!",
                    text: "Payment Applied Successfully!",
                    icon: "success",
                    button: "Ok!",
                    }).then(ms => {
                    location.reload();
                });
            }
            else
            {
                swal({
                    title: "Warning!",
                    text: msg,
                    icon: "warning",
                    button: "Ok!",
                    });
            }
          
        }
    });

})

function processinvoice(channelid, reservationid) {
    
      var user = '<?php echo $fname." ".$lname;?>';
      alert(user);
     $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/reservationinvoicecreate",
        data: {  "reservationId": reservationid, "channelId": channelid,'username':user},
        success: function(msg) {
            swal({
                title: "Done!",
                text: "Invoice created Successfully!",
                icon: "success",
                button: "Ok!",
            }).then(ms => {
                location.reload();
            });
        }
    });
}

function editInvoice(invoiceid)
{
    $("#editInvoice").html('<h4> Vamos a editar la factura id '+invoiceid+' </h4>  <a onclick= "saveinvoice('+invoiceid+')" class="btn yellow two" id="saveinvoice">Save Invoice</a>');
}

function saveinvoice(invoiceid)
{
    alert(invoiceid);
}