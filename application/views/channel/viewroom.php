<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li><a href="<?php echo base_url();?>channel/managerooms">Manage Rooms</a></li>
            <li class="active">
                <?=$Roominfo['property_name']?>
            </li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div class="tab-main">
        <div class="tab-inner">
            <div id="tabs" class="tabs">
                <div class="">
                    <nav>
                        <ul>
                            <li><a href="#section-1" class="icon-shop"><i class="fa fa-info-circle"></i> <span>Basic Info.</span></a></li>
                            <li><a href="#section-2" class="icon-cup"><i class="fa fa-sort-numeric-asc"></i> <span>Room Numbers & Amenities</span></a></li>
                            <li><a href="#section-3" class="icon-food"><i class="fa fa-cog"></i> <span>Rate Configuration</span></a></li>
                            <li><a href="#section-4" class="icon-lab"><i class="fa fa-plus"></i> <span>Extras & Photos </span></a></li>
                            <li><a href="#section-5" class="icon-truck"> <i class="fa fa-bar-chart-o"></i><span>Revenue Manage</span></a></li>
                        </ul>
                    </nav>
                    <div class="content tab">
                        <section id="section-1">
                            <div class="forms-main">
                                <div class="graph-form">
                                    <div class="validation-form">
                                        <!---->
                                        <form id="RoomInfo" onsubmit="return updatebaseinfo();">
                                            <input type="hidden" name="roomid" value="<?=insep_encode($Roominfo['property_id'])?>">
                                            <input type="hidden" name="hotelId" value="<?=insep_encode($Roominfo['hotel_id'])?>">
                                            <div class="vali-form">
                                                <div class="col-md-6 form-group1">
                                                    <label class="control-label">Room Name</label>
                                                    <input name="roomname" type="text" value="<?= $Roominfo['property_name'] ?>" placeholder="Room Name" required="">
                                                </div>
                                                <div class="col-md-6 form-group1 form-last">
                                                    <label class="control-label controls">Rooms Quantity</label>
                                                    <input type="text" id="qty" name="qty" value="<?= $Roominfo['existing_room_count'] ?>" placeholder="Quantity" required="">
                                                </div>
                                                <div class="col-md-6 form-group1">
                                                    <label class="control-label">Rooms Occupancy</label>
                                                    <input name="Occupancy" type="text" value="<?= $Roominfo['room_capacity'] ?>" placeholder="Rooms Occupancy" required="">
                                                </div>
                                                <div class="col-md-6 form-group1 form-last">
                                                    <label class="control-label controls">Adult capacity</label>
                                                    <input type="text" id="adult" name="adult" value="<?= $Roominfo['member_count'] ?>" placeholder="Adult capacity" required="">
                                                </div>
                                                <div class="col-md-6 form-group1">
                                                    <label class="control-label">Children capacity</label>
                                                    <input name="Children" type="text" value="<?= $Roominfo['children'] ?>" placeholder="Children Capacity" required="">
                                                </div>
                                                <div class="col-md-6 form-group1 form-last">
                                                    <label style="padding:4px;" class="control-label controls">Selling period </label>
                                                    <select style="width: 100%; padding: 9px;" name="selling_period">
                                                        <?php
                                                             $selling = array('Daily','Weekly','Monthly');  
                                                             $i=0;                                                 
                                                                echo '<option value="0" >Select a Selling Period</option>';
                                                                foreach ($selling as $sell=>$value) {
                                                                    $i++;                                    
                                                                    echo '<option  value="'.$i.'" '.($Roominfo['selling_period']==$i?'selected':'').'>'.$value.'</option>';
                                                                }
                                                          ?>
                                                    </select>
                                                </div>
                                                <div class="clearfix"> </div>
                                                <div class="col-md-6 form-group1">
                                                    <label class="control-label">Number of bathrooms</label>
                                                    <input name="bathrooms" type="text" value="<?= $Roominfo['number_of_bathrooms'] ?>" placeholder="Number of bathrooms" required="">
                                                </div>
                                                <div class="col-md-6 form-group1 form-last">
                                                    <label class="control-label controls">Area (m²) </label>
                                                    <input type="text" id="Area" name="Area" value="<?= $Roominfo['area'] ?>" placeholder="Area (m²)">
                                                </div>
                                                <div class="col-md-6 form-group1">
                                                    <label class="control-label">Description</label>
                                                    <textarea id="description" name="description" type="text" placeholder="Description" required="">
                                                        <?=rtrim(ltrim($Roominfo['description'])) ?>
                                                    </textarea>
                                                </div>
                                                <div class="col-md-6 form-group1 form-last">
                                                    <label style="padding:4px;" class="control-label controls">Meal Plan </label>
                                                    <select style="width: 100%; padding: 9px;" name="MealPlanid">
                                                        <?php
                                                            if (count($mealplan)>0) {
                                                                echo '<option value="0" >Select a Meal Plan</option>';
                                                                foreach ($mealplan as $value) {                                    
                                                                    echo '<option  value="'.$value['meal_id'].'" '.($Roominfo['meal_plan']==$value['meal_id']?'selected':'').'>'.$value['meal_name'].'</option>';
                                                                }
                                                            }
                                                            else 
                                                            {
                                                                echo '<option value="0">Does not have Meal Plan</option>';
                                                            }

                                                          ?>
                                                    </select>
                                                </div>
                                                <div class="clearfix"> </div>
                                            </div>
                                            <div class="col-md-6 form-group button-2">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <!--<button type="reset" class="btn btn-default">Reset</button>-->
                                            </div>
                                            <div class="clearfix"> </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            function updatebaseinfo() {

                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo lang_url(); ?>channel/saveBasicInfoRoom",
                                    data: $('#RoomInfo').serialize(),
                                    success: function(msg) {
                                        if (msg == 0) {
                                            swal({
                                                title: "Done!",
                                                text: "Info Update Successfully!",
                                                icon: "success",
                                                button: "Ok!",
                                            }).then(ms => {
                                                location.reload();
                                            });
                                        } else {
                                            swal({
                                                title: "Alert!",
                                                text: "Info did not Update Successfully!, Please Try again",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                        }
                                    }
                                });


                                return false;
                            }
                            </script>
                        </section>
                        <section id="section-2">
                            <div class="forms-main">
                                <div class="graph-form">
                                    <div class="validation-form">
                                        <h3>Room Numbers</h3>
                                        <form id="RoomNumber" onsubmit="return saveRoomNumber();">
                                            <input type="hidden" name="roomid" value="<?=insep_encode($Roominfo['property_id'])?>">
                                            <input type="hidden" name="hotelId" value="<?=insep_encode($Roominfo['hotel_id'])?>">
                                            <div class="vali-form">
                                                <?php   $numbers = explode(",", $Roominfo['existing_room_number']);
                                                        $count = intval($Roominfo['existing_room_count'] ); 

                                                            if($count > 0){
                                                                for($i=0;$i<$count;$i++) {
                                                                    $number = array_shift($numbers);
                                                                    echo '<div class="col-md-2 form-group1">';
                                                                        
                                                                        echo ' <input name="Roomnumber[]" type="text" value="'.$number.'" placeholder="Room #" required="">';
                                                                    echo '</div>';
                                                                }
                                                            }else{
                                                                echo "<h4>There aren't existing room</h4>";
                                                            }

                                                        ?>
                                            </div>
                                            <div class="col-md-12 form-group button-2">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <!--<button type="reset" class="btn btn-default">Reset</button>-->
                                            </div>
                                            <div class="clearfix"> </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="forms-main">
                                <div class="graph-form">
                                    <div class="validation-form">
                                        <h3>Amenities</h3>
                                        <form id="amenitiesInfo" onsubmit="return pres();">
                                            <input type="hidden" name="roomid" value="<?=insep_encode($Roominfo['property_id'])?>">
                                            <input type="hidden" name="hotelId" value="<?=insep_encode($Roominfo['hotel_id'])?>">
                                            <div class="vali-form">
                                                <div class="graph">
                                                    <nav class="second">
                                                        <?php 
                                                    foreach ($amenitiesType as  $value) {
                                                        echo '<a>';
                                                        echo $value['amenities_type'];
                                                        echo '</a>';
                                                    }
                                                    ?>
                                                    </nav>
                                                    <?php
                                                     foreach ($amenitiesType as  $value) {
                                                         echo '<div class="context">';
                                                        $amenitiesdestails=get_data('room_amenities',array('type_id' => $value['id'] ))->result_array();
                                                        
                                                        foreach ($amenitiesdestails as $ame) {

                                                           echo '<div class="col-md-4">
                                                           <input type="checkbox" name="amenitiesid[]" value="'.$ame['amenities_id'].'" '.(in_array($ame['amenities_id'], $amenitiesroom)?'checked':'').'>
                                                                    <label for="brand"><span></span>'.$ame['amenities_name'].'.</label>
                                                                    </div>';
                                                        }
                                                        echo '</div>';

                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-group button-2">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                            <div class="clearfix"> </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            function saveRoomNumber() {


                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo lang_url(); ?>channel/saveRoomNumber",
                                    data: $('#RoomNumber').serialize(),
                                    success: function(msg) {
                                        if (msg == 0) {
                                            swal({
                                                title: "Done!",
                                                text: "Room Number Update Successfully!",
                                                icon: "success",
                                                button: "Ok!",
                                            });
                                        } else {
                                            swal({
                                                title: "Alert!",
                                                text: "Room Number did not Update Successfully!, Please Try again",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                        }
                                    }
                                });


                                return false;
                            }

                            function pres() {


                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo lang_url(); ?>channel/saveAmenties",
                                    data: $('#amenitiesInfo').serialize(),
                                    success: function(msg) {
                                        if (msg == 0) {
                                            swal({
                                                title: "Done!",
                                                text: "Amenities Update Successfully!",
                                                icon: "success",
                                                button: "Ok!",
                                            });
                                        } else {
                                            swal({
                                                title: "Alert!",
                                                text: "Amenities did not Update Successfully!, Please Try again",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                        }
                                    }
                                });


                                return false;
                            }
                            $(function() {
                                $('.tabs nav a').on('click', function() {
                                    show_content($(this).index());
                                });

                                show_content(0);

                                function show_content(index) {
                                    // Make the content visible
                                    $('.tabs .context.visible').removeClass('visible');
                                    $('.tabs .context:nth-of-type(' + (index + 1) + ')').addClass('visible');

                                    // Set the tab to selected
                                    $('.tabs nav.second a.selected').removeClass('selected');
                                    $('.tabs navnav.second a:nth-of-type(' + (index + 1) + ')').addClass('selected');
                                }
                            });
                            </script>
                        </section>
                        <section id="section-3">
                            <div style="float: right;" class="buttons-ui">
                                <a href="#newRate" data-toggle="modal" class="btn blue">Create New Rate</a>
                            </div>
                        </section>
                        <section id="section-4">
                            <div class="graph-form">
                                <div style="float: right;" class="buttons-ui">
                                    <a class="btn blue">Create New Extra</a>
                                </div>
                                <div class="clearfix"></div>
                                <h3>Extras</h3>
                                <div class="table-responsive">
                                    <div class="graph">
                                        <div class="tables">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Description</th>
                                                        <th>Price</th>
                                                        <th>Tax</th>
                                                        <th>Total Including Tax</th>
                                                        <th> Edit</th>
                                                        <th> Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                if( count($extras)>0)
                                                {
                                                    $i=0;
                                                    foreach($extras as $extra)
                                                    {                                                   
                                                        $i++;
                                                        echo '  <tr id="extra'.$i.'" class="'.($i%2?'active':'success').'">
                                                                    <td>'.$i.'</td>
                                                                    <td>'.$extra['name'].'</td>
                                                                    <td > '.number_format($extra['price'], 2, '.', ',').'</td>
                                                                    <td >'.number_format($extra['taxes'], 2, '.', ',').'</td>
                                                                    <td >'.number_format($extra['price']*(1+($extra['taxes']/100)), 2, '.', ',').'</td>
                                                                    <td align="center"> <a onclick="return delete_extras();"><i class="fa fa-edit"></i> </a></td>
                                                                    <td align="center"> <a onclick="return delete_extras();"><i class="fa fa-trash-o"></i> </a></td>
                                                                </tr>';
                                                    }
                                                }                               
                                                
                                            ?>
                                                </tbody>
                                            </table>
                                            <div align="center">
                                                <?=( count($extras)==0?'<h5> This Room Has No Extras</h5>':'') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="graph">
                                <h3>Photos</h3>
                                <div class="validation-form">
                                    <div class="col-md-6 form-group1">
                                        <label class="control-label">Add New Photo</label>
                                        <input type="file" id="immm" style="color: black;" reqired name="hotel_image[]" multiple accept="image/png,image/gif,image/jpeg">
                                    </div>
                                    <div class="col-md-12 form-group button-2">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                        <!--<button type="reset" class="btn btn-default">Reset</button>-->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                        <section id="section-5">
                            <div class="graph-form">
                                <h4>Revenue Status</h4>
                                <div class="alert alert-success" style="display:none;" id="suc">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>¡Success!</strong> Information Update.
                                </div>
                                <div class="alert alert-success" style="display:none;" id="suca">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>¡Success!</strong> Revenue Active for Room Type:
                                    <?php echo $property_name;  ?>.
                                </div>
                                <div class="alert alert-warning" style="display:none;" id="sucd">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>¡Success!</strong> Revenue DeActive for Room Type:
                                    <?php echo $property_name;  ?>.
                                </div>
                                <div class="alert alert-warning" style="display:none;" id="alert1">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>¡Warning!</strong> It can only be used for more than 3 rooms.
                                </div>
                                <div class="onoffswitch">
                                    <input onchange=" activeRevenue(this.checked);" type="checkbox" name="RevenueStatus" class="onoffswitch-checkbox" id="myonoffswitch" <?=($Roominfo[ 'revenuertatus']==1? 'checked': '')?>>
                                    <label class="onoffswitch-label" for="myonoffswitch">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                                <div class="graph-form">
                                    <h4>Revenue Configurations </h4>
                                    <div class="clearfix"> </div>
                                    <div class="validation-form">
                                        <div class="col-md-12 form-group1 ">
                                            <label class="control-label controls">Percentage of Increase</label>
                                            <input onfocusout="updateRevenue()" name="percentage" id="percentage" type="text" value="<?= $Roominfo['percentage'] ?>" placeholder="Percentage of Increase" required="">
                                        </div>
                                        <div class="col-md-12 form-group1 ">
                                            <label class="control-label controls">Maximum Price Increase</label>
                                            <input onfocusout="updateRevenue()" name="maximun" id="maximun" type="text" value="<?= $Roominfo['maximun'] ?>" placeholder="Maximum Price Increase" required="">
                                        </div>
                                        <div class="clearfix"> </div>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            var hotelid = "<?=insep_encode($Roominfo['hotel_id'])?>";
                            var roomid = "<?=insep_encode($Roominfo['property_id'])?>";

                            function activeRevenue(Status) {


                                var percen = $("#percentage").val();
                                var maxi = $("#maximun").val();
                                var roomcaount = "<?php echo intval($Roominfo['existing_room_count'] ) ?>";

                                if ((roomcaount < 3 || roomcaount == "") && Status == true) {
                                    $('#alert1').toggle("slow");
                                    $('#myonoffswitch').prop('checked', false);
                                    setTimeout(function() { $('#alert1').fadeOut("slow"); }, 5000);
                                    return;
                                }

                                if ((percen == 0 || percen == "") && Status == true) {

                                    swal({
                                        title: "Alert!",
                                        text: "The percentage of increase must be greater than zero to activate Revenue",
                                        icon: "warning",
                                        button: "Ok!",
                                    });
                                    $('#myonoffswitch').prop('checked', false);
                                    return;
                                }
                                if ((maxi == 0 || maxi == "") && Status == true) {

                                    swal({
                                        title: "Alert!",
                                        text: "Maximum price must be greater than zero to activate revenue",
                                        icon: "warning",
                                        button: "Ok!",
                                    });
                                    $('#myonoffswitch').prop('checked', false);
                                    return;
                                }

                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo lang_url(); ?>channel/activeRevenue",
                                    data: { "roomid": roomid, "hotelId": hotelid, "revenuestatus": (Status == true ? 1 : 0) },
                                    success: function(msg) {
                                        if (Status == true) {

                                            $('#suca').toggle("slow");
                                            $('#sucd').fadeOut("fast");
                                            setTimeout(function() {
                                                $('#suca').fadeOut();
                                            }, 3000);

                                        } else {
                                            $('#sucd').show("slow");
                                            $('#suca').fadeOut("fast");
                                            setTimeout(function() {
                                                $('#sucd').fadeOut();
                                            }, 3000);
                                        }
                                    }
                                });
                            }

                            function updateRevenue() {
                                var percen = $("#percentage").val();
                                var maxi = $("#maximun").val();
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo lang_url(); ?>channel/updateRevenue",
                                    data: { "roomid": roomid, "hotelId": hotelid, "max": maxi, "per": percen },
                                    success: function(msg) {

                                        $('#suc').toggle();

                                        setTimeout(function() {
                                            $('#suc').fadeOut();
                                        }, 5000);
                                    }
                                });
                            }
                            </script>
                        </section>
                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </div>
        </div>
        <script src="<?php echo base_url();?>user_asset/back/js/cbpFWTabs.js"></script>
    </div>
</div>
</div>
</div>
<div id="newRate" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Rate Type</h4>
            </div>
            <div>
                <div class="col-md-12 form-group1">
                    <label class="control-label">Rate Type Name</label>
                    <input style="background:white; color:black;" name="recipename" id="recipename" type="text" placeholder="Rate Type Name" required="">
                </div>
                <div class="col-md-12 form-group1 form-last">
                    <label style="padding:4px;" class="control-label controls">Meal Plan </label>
                    <select style="width: 100%; padding: 9px;" name="productid" id="productid">
                        <?php

                            echo '<option  value="0" >Select a Meal Plan</option>';
                            foreach ($ALLProducts as $value) {
                                $i++;
                                echo '<option value="'.$value['itemPosId'].'" >'.$value['name'].'</option>';
                            }
                      ?>
                    </select>
                </div>
               
               
                <div style="text-align: center; padding:15px;" class="col-md-12 form-group1 form-last">
                    <h3 ">Refundable</h3>
                    <hr>
                </div>
                 <div class="col-md-4 form-group1 form-last">
                    <label style="padding:4px;" class="control-label controls">Type</label>
                    <select style="width: 100%; padding: 9px;" name="productid" id="productid">
                        <option  value="0" >Select a Type</option>
                        <option  value="1" >Add</option>
                        <option  value="2" >Subtract</option>
                    </select>
                </div>
                 <div class="col-md-4 form-group1 form-last">
                    <label style="padding:4px;" class="control-label controls">Fee</label>
                    <select style="width: 100%; padding: 9px;" name="productid" id="productid">
                        <option  value="0" >Select a Fee</option>
                        <option  value="1" >Fixed</option>
                        <option  value="2" >Percentage</option>
                    </select>
                </div>
                <div class="col-md-4 form-group1">
                    <label class="control-label">Value</label>
                    <input onkeypress="return justNumbers(event);" style="background:white; color:black;" name="value" id="value" type="text" placeholder="Value" required="">
                </div>

                <div class="buttons-ui">
                    <a onclick="saveRecipe();" class="btn green">Save</a>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
new CBPFWTabs(document.getElementById('tabs'));
</script>