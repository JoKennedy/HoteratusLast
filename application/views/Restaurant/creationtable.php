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
            <li class="active"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?></li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right;" class="buttons-ui">
        <a href="#createtable" data-toggle="modal" class="btn blue">Add New <?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?></a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="posList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: center;"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Name</th>
                            <th style="text-align: center;">Capacity</th>
                            <th width="10%" style="text-align: center;">Status</th>
                            <th width="10%" style="text-align: center;">Average Use Time</th>
                            <th width="5%" style="text-align: center;">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllTable)>0) {

                            $i=0;
                            foreach ($AllTable as  $value) {
                                $i++;
                                $update="'".$value['description']."','".$value['qtyPerson']."','".$value['postableid']."','".$value['averagetimeuse']."'";
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['description'].'  </td> <td align="center">'.$value['qtyPerson'].'</td> <td>'.($value['active']==1?'Active':'Deactive').'</td><td>'.$value['averagetimeuse'].'</td> <td><a href="#updatetable"  onclick =" showupdate('. $update.')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllTable)==0) {echo '<h4>No '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' Created!</h4>';} ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createtable" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            
                <h4 class="modal-title">Create a <?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 

            </div>
            <div>
                <div class="graph-form">
                    <form id="tablecre">
                        <input type="hidden" name="postid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Name</label>
                            <input style="background:white; color:black;" name="tablename" id="tablename" type="text" placeholder="<?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Capacity of People</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="Capacity" id="Capacity" type="text" placeholder="Capacity of People" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Average Use Time</label>
                            <input name="usetime" id="usetime" type="time" placeholder="Average Use Time" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveTable()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updatetable" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Update a <?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?></h4>
            </div>
            <div>
                <div class="graph-form">
                    <form id="tableup">
                        <input type="hidden" name="postidup" value="<?=$Posinfo['myposId']?>">
                        <input type="hidden" name="tableidup" id="tableidup" value="">
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Name</label>
                            <input style="background:white; color:black;" name="tablenameup" id="tablenameup" type="text" placeholder="<?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?> Capacity of People</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="Capacityup" id="Capacityup" type="text" placeholder="Capacity of People" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Average Use Time</label>
                            <input name="usetimeup" id="usetimeup" type="time" placeholder="Average Use Time" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="UpdateTable()" class="btn green">Update</a>
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
function UpdateTable() {
    var data = $("#tableup").serialize();

    if ($("#tablenameup").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Table Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#Capacityup").val().length < 1) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Capacity!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }else if ($("#usetimeup").val().length < 1) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Average Use Time!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }



    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/UpdateTable",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            if (msg == "0") {
                swal({
                    title: "Success",
                    text: "Table Update!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {
                swal({
                    title: "upps, Sorry",
                    text: "Table was not Updated!",
                    icon: "warning",
                    button: "Ok!",
                });
            }

            unShowWait();


        }
    });


}

function showupdate(nombre, capacidad, id,usetime) {
    $("#tablenameup").val(nombre);
    $("#tableidup").val(id);
    $("#Capacityup").val(capacidad);
    $("#usetimeup").val(usetime);
}

function saveTable() {

    var data = $("#tablecre").serialize()

    if ($("#tablename").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Table Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#Capacity").val().length < 1) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Capacity!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#usetime").val().length < 1) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Average Use Time!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveTable",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            if (msg == "0") {
                swal({
                    title: "Success",
                    text: "Table Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {
                swal({
                    title: "upps, Sorry",
                    text: "Table was not Created!",
                    icon: "warning",
                    button: "Ok!",
                });
            }

            unShowWait();


        }
    });

}
</script>