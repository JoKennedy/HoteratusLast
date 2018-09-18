<div class="outter-wp" style="height: 4000px;">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Turns</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div class="clearfix"></div>
     <div style="float: right; " class="buttons-ui">
        <a href="#turncreate" data-toggle="modal" class="btn blue">Add New Turn</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="turnslist" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Turn Name</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllTurns)>0) {

                            $i=0;
                            foreach ($AllTurns as  $value) {
                                $i++;
                                $update="'".$value['posturnid']."','".$value['name']."','".$value['active']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> 
                                <td> <a href="'.site_url('pos/viewTurnDetails/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']).'/'.insep_encode($value['posturnid'])).'" >'.$value['name'].'</a>  </td> 
                                <td>'.($value['active']==1?'<h5><span class="label label-success">Enable</span></h5>':'<h5><span class="label label-danger">Disabled</span></h5>').'</td> 
                                <td><a onclick ="showupdate('.$update.')" ><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllTurns)==0) {echo '<h4>No Turn Created!</h4>';}   ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</div>
<div id="turncreate" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Turn</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="turnC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Turn Name</label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="Turn Name" required="">
                        </div>
                        <div class="buttons-ui">
                            <a onclick="saveTurn()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="turnupdate" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Turn</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="turnU">
                        <input type="hidden" name="turnid" id="turnid" value="">
                            <div class="col-md-12 form-group1 " >
                        <div class="onoffswitch" style="float: left;">
                              <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid"  >
                              <label class="onoffswitch-label" for="statusid">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                           <div class="clearfix"> </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Turn Name</label>
                            <input style="background:white; color:black;" name="nameU" id="nameU" type="text" placeholder="Turn Name" required="">
                        </div>
                        <div class="buttons-ui">
                            <a onclick="updateTurn()" class="btn green">Update</a>
                        </div>
                     
                    </form>
                </div>
            </div>
        </div>
           <div class="clearfix"> </div>
    </div>
</div>
</div>
</div>


<script type="text/javascript">


function saveTurn() {

    if ($("#name").val() == 0 || $("#name").val().length==0 ) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Turn Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } 

    var data = $("#turnC").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveTurn",
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
                    text: "Turn Saved!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["message"],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });


}

function showupdate(id,name,status)
{
    $("#turnid").val(id);
    $("#nameU").val(name);
    $("#statusid").prop('checked',(status==1?true:false));
    $("#turnupdate").modal().fadeIn('fast');
}
function updateTurn() {

    if ($("#nameU").val() == 0 || $("#nameU").val().length==0 ) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Turn Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } 

    var data = $("#turnU").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/updateTurn",
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
                    text: "Turn Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["message"],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });


}

$(document).ready(function() {
    $('#turnslist').DataTable();
});
/*
*/
</script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables-init.js"></script>