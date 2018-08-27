<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Manage Rooms</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>


        <div style="float: right;" class="buttons-ui">
        <a href="#createroom" data-toggle="modal" class="btn blue">Add Room Type</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="roomList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Room Name</th>
                            <th>Pricing Type</th>
                            <th>Meal Plan</th>
                            <th>Rooms Quantity</th>
                            <th>Adult capacity</th>
                            <th>Children Capacity</th>
  
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($allRooms)>0) {

                            $i=0;
                            foreach ($allRooms as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> <a href="'.site_url('channel/viewroom/'.secure($value['hotel_id']).'/'.insep_encode($value['property_id'])).'">'.$value['property_name'].' </a> </td> <td>'.$value['PricingName'].'</td> <td>'.$value['meal_name'].'</td> <td> '.$value['existing_room_count'].' </td>  <td>'.$value['member_count'].'</td><td>'.$value['children'].'</td>  </tr>  ';

                            }
                        } ?>
                    </tbody>
                </table>
                <?php if (count($allRooms)==0) {echo '<h4> No Record Found!</h4>';} ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<div id="createroom" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Create a Room</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="roomC">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Room Name</label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="Room Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Occupancy Adults</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="adult" id="adult" type="text" placeholder="Occupancy Adults" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Occupancy Children</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="children" id="children" type="text" placeholder="Occupancy Children" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveRoom()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function saveRoom() {


    var data = $("#roomC").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/saveroom",
        data: data,
        beforeSend: function() {
            showWait('Saving Room');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("The Set Up Saved Correctly", {
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
</script>