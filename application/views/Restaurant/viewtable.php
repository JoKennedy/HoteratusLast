<style type="text/css">
.div-img {
    text-align: center;
    background-color: white;
}

.img {
    width: 50px;
    height: 50px;
    padding-bottom: 0px;
}

.div-img .img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    transform: scale(1.2);
    -ms-transform: scale(1.2);
    -moz-transform: scale(1.2);
    -webkit-transform: scale(1.2);
    -o-transform: scale(1.2);
    -webkit-transition: all 500ms ease-in-out;
    -moz-transition: all 500ms ease-in-out;
    -ms-transition: all 500ms ease-in-out;
    -o-transition: all 500ms ease-in-out;
}

.div-img:hover .img {
    transform: scale(1);
    -ms-transform: scale(1);
    -moz-transform: scale(1);
    -webkit-transform: scale(1);
    -o-transform: scale(1);
}
</style>
<div class="outter-wp">
    <div>
        <?php include("menu.php") ?>
    </div>
    <div class="graph-form">
        <div style="float: right;" class="buttons-ui">
            <a id="btback" data-toggle="tooltip" title="All Tables" class="btn blue" href="<?=site_url('pos/viewpos/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        </div>
        <div class="clearfix"></div>
        <div align="center">
            <h4>You are working with: <strong><?=$TableInfo['description']?></strong></h4></div>
        <div class="graph-visual tables-main">
            <div class="graph">
                <div class="table-responsive">
                    <div class="clearfix"></div>
                    <table id="invoice" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="20%">Product</th>
                                <th width="5%">Price</th>
                                <th width="5%">Quantity</th>
                                <th width="5%">Total</th>
                                <th width="5%">Add</th>
                                <th width="5%">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandtotal=0.00;
                            if (count($OrderInfo)>0) {

                            $i=0;
                            foreach ($OrderInfo as  $value) {
                                $i++;
                                echo' <tr id="itemid'.$value['itemid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td>'.$value['itemname'].'  </td> <td>'.number_format($value['price'], 2, '.', '').'</td> <td>'.$value['qty'].'</td> <td>'.number_format($value['price']*$value['qty'], 2, '.', '').'</td>
                                    <td  align="center"><a  onclick="additem('."'".$value['itemid']."','".$value['isitem']."'".');"> <i class="fa fa-plus"></i></a> </td> <td align="center"><a  onclick="deleteitem('."'".$value['itemid']."','".$value['isitem']."'".');"> <i class="fa fa-trash-o"></i></a> </td> </tr>  ';
                                $grandtotal+=($value['price']*$value['qty']);
                            }
                        } ?>
                        </tbody>
                    </table>
                    <?php if (count($OrderInfo)==0) {echo '<h4 id="noproduct">No product added!</h4>';} ?>
                    <div class="clearfix"></div>
                </div>
                <div align="right" id="grandtotal">
                    <h2><strong>Total Due:</strong> <?=number_format($grandtotal, 2, '.', '')?></h2>
                </div>
            </div>
            <div style="float: left;" class="buttons-ui">
                <a onclick=" cancelorden()" class="btn red">Cancel Order</a>
                <a onclick="ChargeInvoice()" class="btn green">Charge</a>
                <a href="#addwaiter" data-toggle="modal" class="btn blue">Assign Waiter</a>
                <a id="change" style="<?=(count($OrderInfo)==0?'display:none;':'')?>'" href="#changetableid" onclick="cleartable()" data-toggle="modal" class="btn orange">Change Table</a>
            </div>
            <div class="clearfix"></div>
            <div align="left" id="staff">
                <h3><strong>Waiter/s:</strong> <?=($waiter==''?'Not Assigned':$waiter)?></h3>
            </div>
        </div>
        <div class="graph-form" style="float: left; width: 50%;">
            <h3 align="center"> Categories</h3>
            <div class="table-responsive">
                <?php

                    if (count($Categories)>0) {
                       foreach ($Categories as  $value) {
                          echo '<div  class="col-md-4 div-img">
                                    <a onclick="CategoryItem('."this.id".');" id="'.$value['itemcategoryID'].'"><img  class="img"  src="'.$value['photo'].'"></a>
                                    <h4> '.$value['name'].' </h4>
                                </div>';
                       }
                    }

                    if (count($Recipes)>0) {

                          echo '<div  class="col-md-4 div-img">
                                    <a onclick="RecipeItem();" ><img  class="img"  src="/user_assets/images/Categories/recipedefault.png"></a>
                                    <h4> Recipes </h4>
                                </div>';
                    } 

                 ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="graph-form" style="float: right; width: 50%">
            <h3 align="center">Items</h3>
            <br>
            <div id="allitem">
                <h4 align="center">select a category to display the Items</h4>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div id="InvoiceOutHouse" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice</h4>
            </div>
            <div>

                    <h4>Solos pagos miembro no esta en el Hotel </h4>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="InvoiceInHouse" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice</h4>
            </div>
            <div>
               <h4>Cargar a Habiatacion on pagar </h4>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div id="addwaiter" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">All Wainer</h4>
            </div>
            <div id="msguser" class="alert alert-warning" style="display: none; text-align: center;">
                <strong>Warning!</strong> Select an Extra to Continue.
            </div>
            <div>
                <div class="graph" style>
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table id="tablestaff" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Occupation</th>
                                    <th>Add</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                            if (count($StaffInfo)>0) {

                            $i=0;
                            foreach ($StaffInfo as  $value) {
                                $i++;
                                echo' <tr id="staff'.$value['mystaffposid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td>'.$value['firstname'].' '.$value['lastname'].'  </td> <td>'.$value['occupation'].'</td>
                                    <td  align="center"><a id="'.$value['mystaffposid'].'" onclick="addstaff('."this.id".');"> <i class="fa fa-plus"></i></a> </td> <td align="center"><a id="'.$value['mystaffposid'].'" onclick="deletestaff('."this.id".');"> <i class="fa fa-trash-o"></i></a> </td> </tr>';

                            }
                        } ?>
                            </tbody>
                        </table>
                        <?php if (count($StaffInfo)==0) {echo '<h4 id="nostaff">No Staff added for This POS!</h4>';} ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="chargeToRoom" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Charge to Room</h4>
            </div>
            <div id="msguser" class="alert alert-warning" style="display: none; text-align: center;">
                <strong>Warning!</strong> Select an Extra to Continue.
            </div>
        </div>
    </div>
</div>
<div id="chargeToPerson" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Charge to Room</h4>
            </div>
            <div id="msguser" class="alert alert-warning" style="display: none; text-align: center;">
                <strong>Warning!</strong> Select an Extra to Continue.
            </div>
        </div>
    </div>
</div>
<div id="changetableid" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Table</h4>
            </div>
            <div class="buttons-ui">
                <a onclick=" availabletable(<?=$Posinfo['myposId']?>)" class="btn green">Available Table</a>
            </div>
            <div id="tableavailable">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var tableid = "<?= $TableInfo['postableid']?>";
var posid = "<?=$Posinfo['myposId']?>";

function ChargeInvoice() {

    swal({
      buttons: {

        roll: {
          text: "In-House",
          value: "inhouse",
        },
        catch: {
          text: "Out-House",
          value: "outhouse",
        },
      },
    }).then((n)=>{
        if(n=="outhouse")
        {
            $("#InvoiceOutHouse").modal();
      
        }
        else if(n=="inhouse")
        {
            $("#InvoiceInHouse").modal();
        }
        
    });
}

function closeChargetype()
{
    $("#ChargeInvoicef").hide('slow/400/fast', function() {
        $("#chargeToPerson").display();
    });
}
function cleartable() {
    $("#tableavailable").html('');
}

function cancelorden() {

   swal({
  text: 'Reason for Cancellation.',
  content: "input",
  button: {
    text: "type Reason!",
    closeModal: false,
  },
})
.then(name => {

});
   /* swal({
            title: "Are you sure?",
            text: "Do you want to cancel this order?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (!willDelete) { return; }
             var motivo ="";

            
            if(motivo!="")
            {
                $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo lang_url(); ?>pos/cancelorden",
                data: { "tableid": tableid },
                success: function(msg) {

                            if (msg['result']) {

                                swal({
                                    title: "Done!",
                                    text: "Orden cancelled Successfully!",
                                    icon: "success",
                                    button: "Ok!",
                                }).then((nt) => {
                                    window.location.assign($("#btback").attr('href'))
                                });
                            } else {

                                swal({
                                    title: "upps!, something went wrong!",
                                    text: "Please try again!",
                                    icon: "warning",
                                    button: "Ok!",
                                });
                            }


                        }
                    });
            }
            
        });*/
}

function changetable(newtable) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/changetable",
        data: { "newid": newtable, "oldid": tableid, "posid": posid },
        success: function(msg) {
            if (msg['result'] == 0) {
                alert(msg['result']);
                swal({
                    title: "Success",
                    text: "Done!!!, The table was changed",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    window.location.assign(msg['url']);
                });
            } else if (msg['result'] == 1) {
                swal({
                    title: "Upps, Sorry",
                    text: "This Table is no Available!",
                    icon: "warning",
                    button: "Ok!",
                });


            }

        }
    });

}

function availabletable(posid) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/availabletable",
        data: { "posid": posid },
        success: function(msg) {
            if (msg['result']) {
                $("#tableavailable").html(msg['html']);
            } else {
                swal({
                    title: "upps, Sorry",
                    text: "No Available Table!",
                    icon: "warning",
                    button: "Ok!",
                });

                $("#tableavailable").html(msg['html']);
            }

        }
    });
}

function addstaff(id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/exists_staff",
        data: { "staffid": id, "tableid": tableid },
        success: function(msg) {

            if (msg['status'] == 1 || msg['status'] == -1) {
                swal({
                        title: "Are you sure?",
                        text: (msg['status'] == -1 ? "Do you want to create a new order for this table?" : "This table has an assigned waiter/s, do you want to change?"),
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (!willDelete) { return; }
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "<?php echo lang_url(); ?>pos/addstaff",
                            data: { "staffid": id, "tableid": tableid },
                            success: function(msg) {
                                $("#staff").html(msg['staff']);
                                swal({
                                    title: "Done!",
                                    text: "Staff Added Successfully!",
                                    icon: "success",
                                    button: "Ok!",
                                });
                            }
                        });


                    });
            }
        }
    });
}

function CategoryItem(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>pos/allitem",
        data: { "catid": id, "tableid": tableid },
        success: function(msg) {
            $("#allitem").html(msg)
        }
    });
}

function RecipeItem() {
    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>pos/allRecipe",
        data: { "posid": posid },
        success: function(msg) {
            $("#allitem").html(msg)
        }
    });
}

function additem(id, isitem) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/additem",
        data: { "itemid": id, "tableid": tableid, "isitem": isitem },
        success: function(msg) {

            if (msg['success']) {
                $('#noproduct').html('');
                $('#invoice tbody').html(msg['html']);
                $('#grandtotal').html(msg['total']);
                $('#change').attr('style', '');

            } else {

            }
        }
    });

}

function deleteitem(id, isitem) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/deleteitem",
        data: { "itemid": id, "tableid": tableid, "isitem": isitem },
        success: function(msg) {

            if (msg['success']) {
                $('#invoice tbody').html(msg['html']);
                $('#grandtotal').html(msg['total']);
            } else {

            }
        }
    });

}

function reloj() {
    var now = new Date();
    hora = now.getHours()
    minuto = now.getMinutes()
    segundo = now.getSeconds()

    $("#reloj").html('System Time ' + hora + ':' + (minuto <= 9 ? '0' + minuto : minuto) + ':' + (segundo <= 9 ? '0' + segundo : segundo));

}
reloj();
setInterval(function() { reloj(); }, 1000);
</script>
</div>
</div>