<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Mapping Rooms</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
        <h4>Mapping configuration for <?= strtoupper($channelinfo['channel_name'])?> </h4>
    </div>
    <div style="float: left;" class="buttons-ui">
        <?php if($channelinfo['channel_id']==9) echo '<a href="#newroom" data-toggle="modal"  class="btn blue">Add New Room</a>'; ?>
    </div>
    <div style="float: right;" class="buttons-ui">
        <a onclick="importrooms('<?=insep_encode($channelinfo['channel_id'])?>')" class="btn blue">Import Room Rate Information from Channel</a>
        <?php if($channelinfo['channel_id']==2) echo '<a class="btn orange">Import Past Resevations Now</a>'; ?>
    </div>
    <div class="clearfix"></div>
    <?php 
        if(count($roomsmapped)==0 && count($roomsunmapped)==0)
        {
            echo '<h2 style="text-align:center; color:#00c6d7">NEED TO IMPORT THE ROOM FOR MAPPING</>';
        }
        else
        {   
            if(count($roomsunmapped)>0)
            {
    ?>
    <form id="mappingrooms" accept-charset="utf-8">
        <input type="hidden" name="channelid" value="<?=$channelinfo['channel_id']?>">
        <div class="col-md-6 form-group1 form-last">
            <label style="padding:4px;" class="control-label controls"><strong><?=$channelinfo['channel_name']?> Rooms</strong> </label>
            <select style="width: 100%; padding: 9px;" name="roomchannelid" id="roomchannelid">
                <?php

                echo '<option  value="0" >Select a Room</option>';
                foreach ($roomsunmapped as $value) {
                    $i++;
                    echo '<option value="'.$value['import_mapping_id'].'" >'.$value['RoomName'].'('.$value['roomid'].'-'.$value['rateid'].')'.'</option>';
                }
          ?>
            </select>
        </div>
        <div class="col-md-6 form-group1 form-last">
            <label style="padding:4px; color:#00c6d7;" class="control-label controls"><strong>Channel Manager Rooms</strong></label>
            <select style="width: 100%; padding: 9px;" name="roomid" id="roomid">
                <?php

                echo '<option  value="0" >Select a Room</option>';
                foreach ($AllRooms as $value) {
                    $i++;
                    echo '<option value="'.$value['property_id'].'" >'.$value['property_name'].'</option>';
                }
          ?>
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 form-group1">
            <div class="panel-default">
                <div class="panel-heading">
                    <h3 style="text-align: center;" class="panel-title">Rate Configuration</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-6 form-group1">
                        <label class="control-label">Exchange Rate</label>
                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="convertion" id="convertion" type="text" placeholder="Rate Conversion" value="1" required="" 
                        data-toggle="tooltip" data-placement="top" title="If the channel and the channel manager have the same currency type '1' else type the conversion rate. Example: Channel Manager have Dollar and Channel Mexican pesos Type = 18.91" >

                    </div>
                    <div class="col-md-6 form-group1">
                        <label class="control-label">Increase by Promotion</label>
                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="promotion" id="promotion" type="text" placeholder="Increase by Promotion" value="0" required="" data-toggle="tooltip" data-placement="right" title="Type the percentage of the promotion">

                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 form-group1">
            <div class="panel-default">
                <div class="panel-heading">
                    <h3 style="text-align: center;" class="panel-title">Full Update</h3>
                </div>
                <div class="panel-body">
                    <?php
                        $disponible=array("1"=>"Availability","2"=>"Price","3"=>"Minimum stay","4"=>"CTA","5"=>"CTD","6"=>"Stop sell");

                      foreach ($disponible as $key => $opt) {

                                echo '<div class="col-md-4 ">
                                             <table>
                                                <tbody>
                                                <tr>
                                                <td><input  type="checkbox" name="opt[]" id="opt'.$key.'" value="'.$key.'" checked ></td>
                                                <td><label for="opt'.$key.'">&nbsp '.$opt.'</label></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>';
                            }   

                    ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="buttons-ui">
            <a onclick="saveMapping()" class="btn blue" >Save</a>
        </div>
    </form>
    <?php 
            }
            else
            {
                 echo '<h2 class="" style="text-align:center; color:#00c6d7">All Rooms Types Were Mapped</h2>';
            }
            if(count($roomsmapped)>0)
            {
              echo  '<div class="graph">
                <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table class="table table-bordered">
                                <thead>
                                        <tr>
                                                <th>#</th>
                                                <th >Room Type</th>
                                                <th style="text-align:center;" >Conversion Rate</th>
                                                <th style="text-align:center;" >Promotion Percentage</th>
                                                <th style="text-align:center;" >Status</th>
                                                <th style="text-align:center; width:10%;">Action</th>
                                                <th style="text-align:center;">Import</th>
                                        </tr>
                                                             </thead>
                                <tbody>';
                $i=0;
                foreach ($roomsmapped as  $value) {
                        $i++;
                        $update="'".$value['room_name']."(".$value['room_id'].")','".$value['property_id']."','".$value['updatetypes']."','".$value['currencypromotion']."','".$value['mapping_id']."','".$value['enabled']."','".$value['promotion']."'";

                        echo ' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
                            ' </th> <td><strong>'.$value['property_name'].'</strong> Mapped to '.$channelinfo['channel_name'].' ['.$value['room_name'].'('.$value['room_id'].')  </td> <td style="text-align:center;">'.$value['currencypromotion'].'</td><td style="text-align:center;">'.$value['promotion'].'</td><td style="text-align:center;"><h5><span class="label label-'.(strtoupper($value['enabled'])=='ENABLED'?'success':'danger').'">'.strtoupper($value['enabled']).'</span></h5></td>
                                <td  align="center"><a onclick="editMapping('.$update.')" data-toggle="tooltip" data-placement="top" title="Update Mapping"><i class="fa fa-pencil-square-o fa-2x"></i></a> <a onclick="deleteMapping('.$value['mapping_id'].')" data-toggle="tooltip" data-placement="top" title="Delete Mapping" ><i class="fa fa-trash fa-2x"></i></a></td> 
                                <td  align="center"><a ><i class="fa fa-check-circle  fa-2x"></i></a> </td></tr>';
                    }
                   echo '</tbody></table></div> </div>';
                  
            }
        }

    ?>
</div>
</div>
</div>
<div id="newroom" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a Room For <?= strtoupper($channelinfo['channel_name'])?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div>
                <div class="graph-form">
                    <form id="newroomC">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Room Name</label>
                            <input style="background:white; color:black;" name="nroomname" id="nroomname" type="text" placeholder="Room Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Room ID</label>
                            <input style="background:white; color:black;" name="nroomid" id="nroomid" type="text" placeholder="Room ID" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <div class="buttons-ui">
                            <a onclick="saveNRoom()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="EditMapping" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update a Room For <?= strtoupper($channelinfo['channel_name'])?> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div>
                <div class="graph-form">
                    <form id="mappingroomsup" accept-charset="utf-8">
                        <input type="hidden" name="mapping_id" id="mapping_id" >
                        <div class="col-md-12 form-group1 " >
                            <div class="onoffswitch" style="float: right;">
                                <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid" >
                                <label class="onoffswitch-label" for="statusid">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls"><strong><?=$channelinfo['channel_name']?> Room Name</strong> </label>
                           <input style="background:white; color:black;"  name="roomnameup" id="roomnameup" type="text" placeholder="Room Name"  required="" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px; color:#00c6d7;" class="control-label controls"><strong>Channel Manager Rooms</strong></label>
                            <select style="width: 100%; padding: 9px;" name="roomidup" id="roomidup">
                                <?php

                                    echo '<option  value="0" >Select a Room</option>';
                                    foreach ($AllRooms as $value) {
                                        $i++;
                                        echo '<option value="'.$value['property_id'].'" >'.$value['property_name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 form-group1">
                            <div class="panel-default">
                                <div class="panel-heading">
                                    <h3 style="text-align: center;" class="panel-title">Rate Configuration</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-6 form-group1">
                                        <label class="control-label">Exchange Rate</label>
                                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="convertionup" id="convertionup" type="text" placeholder="Rate Conversion"  required=""
                                        data-toggle="tooltip" data-placement="top" title="If the channel and the channel manager have the same currency type '1' else type the conversion rate. Example: Channel Manager have Dollar and Channel Mexican pesos Type = 18.91">

                                    </div>
                                     <div class="col-md-6 form-group1">
                                        <label class="control-label">Increase by Promotion</label>
                                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="promotionup" id="promotionup" type="text" placeholder="Rate Conversion"  required=""
                                        data-toggle="tooltip" data-placement="top" title="Type the percentage of the promotion">

                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        
                        <div class="clearfix"></div>
                        <div class="col-md-12 form-group1">
                            <div class="panel-default">
                                <div class="panel-heading">
                                    <h3 style="text-align: center;" class="panel-title">Full Update</h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                            $disponible=array("1"=>"Availability","2"=>"Price","3"=>"Minimum stay","4"=>"CTA","5"=>"CTD","6"=>"Stop sell");

                                          foreach ($disponible as $key => $opt) {

                                                    echo '<div class="col-md-4 ">
                                                                 <table>
                                                                    <tbody>
                                                                    <tr>
                                                                    <td><input  type="checkbox" name="optup[]" id="optup'.$key.'" value="'.$key.'" ></td>
                                                                    <td><label for="optup'.$key.'">&nbsp '.$opt.'</label></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>';
                                                }   

                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="buttons-ui">
                            <a onclick="updateMapping()" class="btn blue">Update</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function saveMapping() {
    if ($("#roomchannelid").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Channel Room To Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
    if ($("#roomid").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Channel Manager Room To Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return false;
    }
    if ($("#convertion").val() <= 0 || $("#convertion").val().lenght == 0) {
        swal({
            title: "upps, Sorry",
            text: "Type a convertion to Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return false;
    }

    var data = $("#mappingrooms").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>mapping/savemappingroom",
        data: data,
        beforeSend: function() {
            showWait('Saving Mapping');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("The Mapping Saved Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });

}

function saveNRoom() {
    if ($("#nroomname").val().lenght == 0) {
        swal({
            title: "upps, Sorry",
            text: "Type a Room Name To Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
    if ($("#nroomid").val().lenght == 0) {
        swal({
            title: "upps, Sorry",
            text: "Type a Room ID To Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    var data = $("#newroomC").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>mapping/saveNRoom",
        data: data,
        beforeSend: function() {
            showWait('Saving a New Room');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("The Room Saved Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });

}

function importrooms(id) {
    var data = { 'channel_id': id };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>mapping/getchannel",
        data: data,
        beforeSend: function() {
            showWait('Importing Rooms Information');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['result'] == '1') {
                swal("All Rooms information were Import Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['content'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
}

function editMapping(roomname,roomid,updatetype,convertion,mapping_id,enable,promotion) {

    $("#roomnameup").val(roomname);
    $("#roomidup").val(roomid);

    updatetype.split(',').map(function(n) {
        $("#optup"+n).attr('checked',true);
    });
    $("#statusid").attr('checked',(enable=='enabled'?true:false));
    $("#convertionup").val(convertion);
    $("#promotionup").val(promotion);
    $("#mapping_id").val(mapping_id);
    $("#EditMapping").modal();
}
function updateMapping() {
 
    if ($("#roomidup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Channel Manager Room To Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return false;
    }
    if ($("#convertionup").val() <= 0 || $("#convertionup").val().lenght == 0) {
        swal({
            title: "upps, Sorry",
            text: "Type a convertion to Continue !",
            icon: "warning",
            button: "Ok!",
        });
        return false;
    }

    var data = $("#mappingroomsup").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>mapping/updatemappingroom",
        data: data,
        beforeSend: function() {
            showWait('Updating Mapping');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

            if (msg['success']) {
                swal("The Mapping Updated Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });

}
function deleteMapping(id)
{
    swal({
      title: "Are you sure?",
      text: "Do you want delete this mapping?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

         $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>mapping/deletemappingroom",
        data: {'id':id},
        beforeSend: function() {
            showWait('Deleting Mapping');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

            if (msg['success']) {
                swal("Mapping Deleted Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });

        swal(" Your Mapping has been deleted!", {
          icon: "success",
        });


      } else {
        swal("Your Mapping is safe!");
      }
    });
}
</script>