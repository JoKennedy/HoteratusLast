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
                            <li><a href="#section-2" class="icon-cup"><i class="fas fa-sort-numeric-up"></i> <span>Room Numbers & Amenities</span></a></li>
                            <li><a href="#section-3" class="icon-food"><i class="fa fa-cog"></i> <span>Rate Configuration & Attributes</span></a></li>
                            <li><a href="#section-4" class="icon-lab"><i class="fa fa-plus"></i> <span>Extras & Photos </span></a></li>
                            <li><a href="#section-5" class="icon-truck"> <i class="fas fa-chart-line"></i><span>Revenue Manager</span></a></li>
                        </ul>
                    </nav>
                    <div class="content tab">
                        <section  id="section-1">
                            <div style="height:4000px;" class="forms-main">
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

                                                <div class="form-group1 col-md-6">
                                                    <label  class="control-label controls">Show In Widget</label>
                                                            <div class="input-group input-icon right">
                                                                <div class="onoffswitch">
                                                                    <input type="checkbox" name="showwidget" class="onoffswitch-checkbox" id="showwidget" <?=($Roominfo['showwidget']==1?'checked':'')?> >
                                                                    <label class="onoffswitch-label" for="showwidget">
                                                                        <span class="onoffswitch-inner"></span>
                                                                        <span class="onoffswitch-switch"></span>
                                                                    </label>
                                                                </div>
                                                        </div>
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

                                        <div class="buttons-ui" style="float:left;">
                                            <center><h3>Amenities</h3></center>
                                        </div>
                                        <div class="buttons-ui" style="float:right;">
                                            <a onclick="ShowaddAmenities()" class="btn green"><i class="fas fa-plus"></i> Add a New Amenity </a>
                                        </div>
                                        <div class="clearfix"></div>


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
                                                        $amenitiesdestails=$this->db->query("select * from room_amenities where type_id=".$value['id']." and (hotelid=0 or hotelid=".$Roominfo['hotel_id'].") ")->result_array();

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
                                <div id="newamenety" class="modal fade" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h4 class="modal-title">Create a New Amenities</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </div>
                                            <div>
                                                <div class="graph-form">
                                                    <form id="AmenityC">
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">
                                                                Amenity Type
                                                            </label>
                                                            <select style="width: 100%; padding: 9px; " id="AmenityTypeId" name="AmenityTypeId">
                                                                <?php
                                                                    if(count($amenitiesType)>0)
                                                                    {
                                                                        echo '<option value="0">Select a Amenity Type </option>';
                                                                        foreach ($amenitiesType as  $value) {
                                                                            echo '<option value="'.$value['id'].'">'.$value['amenities_type'].'</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Amenity Name</label>
                                                            <input style="background:white; color:black;"  name="AmenityName" id="AmenityName" type="text" placeholder="Type a Amenity Name" required="">
                                                        </div>

                                                        <div class="clearfix"> </div>
                                                        <br>
                                                        <br>
                                                        <div class="buttons-ui">
                                                            <a onclick="addAmenities()" class="btn green"><i class="far fa-save"></i>Save</a>
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                function ShowaddAmenities()
                                {
                                    $("#newamenety").modal();
                                }
                                function addAmenities()
                                {
                                    if($("#AmenityTypeId").val()==0 || $("#AmenityTypeId").val()=='' )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Select a Amenity Type to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                        return;
                                    }
                                    else if($("#AmenityName").val()==0 || $("#AmenityName").val()=='' )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Amenity Name to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                        return;
                                    }


                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "<?php echo lang_url(); ?>channel/saveAmenty",
                                        data: $("#AmenityC").serialize(),
                                        beforeSend: function() {
                                            showWait();
                                            setTimeout(function() { unShowWait(); }, 10000);
                                        },
                                        success: function(msg) {
                                            unShowWait();
                                            if (msg["success"]) {
                                                swal({
                                                    title: "Success",
                                                    text: "New Amenety was Add!",
                                                    icon: "success",
                                                    button: "Ok!",
                                                }).then((n) => {
                                                    location.reload();
                                                });
                                            } else {
                                                swal({
                                                    title: "upps, Sorry",
                                                    text:  msg["message"],
                                                    icon: "warning",
                                                    button: "Ok!",
                                                });
                                            }
                                        }
                                    });
                                }

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

                            <div style="float: left;" >
                                <h3>Rate Type</h3>
                            </div>
                            <div style="float: right;" class="buttons-ui">
                                <a href="#newRate" data-toggle="modal" class="btn blue">Create New Rate</a>
                            </div>
                            <div class="clearfix"></div>

                            <div class="table-responsive">
                                <div class="graph">
                                    <div class="tables">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Rate Type Name</th>
                                                    <th>Meal Plan</th>
                                                    <th>Pricing Type</th>
                                                    <th align="center" width="5%"> Edit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if( count($ratetype)>0)
                                                {
                                                    $i=0;
                                                    foreach($ratetype as $rate)
                                                    {
                                                        $update="'".$rate['ratetypeid']."','".$rate['name']."','".$rate['mealplanid']."','".$rate['pricingtype']."','".$rate['type']."','".$rate['fee']."','".$rate['value']."','".$rate['active']."'";
                                                        $i++;
                                                        echo '  <tr id="extra'.$i.'" class="'.($i%2?'active':'success').'">
                                                                    <td>'.$i.'</td>
                                                                    <td>'.$rate['name'].'</td>
                                                                    <td > '.$rate['meal_name'].'</td>
                                                                    <td >'.$rate['PricingName'].'</td>
                                                                    <td align="center"> <a onclick="showratetype('.$update.')"><i class="fa fa-edit"></i> </a></td>
                                                                </tr>';
                                                    }
                                                }

                                            ?>
                                            </tbody>
                                        </table>
                                        <div align="center">
                                            <?=( count($ratetype)==0?'<h4>This Room Has No Rate Type</h4>':'') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div style="float: left;" class="buttons-ui">
                                <h3>Rooms Attributes</h3>
                            </div>
                             <div style="float: right;" class="buttons-ui">
                                <a href="#newattribute" data-toggle="modal" class="btn blue">Add New Attribute</a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="vali-form">
                                <form id="addAttributes" accept-charset="utf-8" onsubmit="loadAttributes()">
                                    <input type="hidden" name="RoomId" value="<?=$Roominfo['property_id']?>">
                                    <div  class="graph">
                                        <nav class="second">
                                        <?php

                                          $numbers = explode(",", $Roominfo['existing_room_number']);
                                            $count = count($Roominfo['existing_room_count'] );

                                                if($count > 0){

                                                    foreach ($numbers as $number) {
                                                        echo '<a style="padding:2px;">';
                                                        echo $number ;
                                                        echo '</a>';
                                                    }
                                                }else{
                                                    echo "<h4>There aren't existing room</h4>";
                                                }
                                        ?>
                                        </nav>
                                        <?php
                                        $numbers =(explode(",", $Roominfo['existing_room_number']));
                                         foreach ($numbers as  $number) {
                                             echo '<div class="context">';
                                             $attributeIds=$this->db->query("select AttributeIds from room_number_attributes where RoomId =".$Roominfo['property_id']." and RoomNumber='$number' ")->row_array();

                                            $attributeIds = (count($attributeIds)>0?explode(",", $attributeIds['AttributeIds']):array());

                                            foreach ($Attributes as $Attribute) {

                                               echo '<div class="col-md-4">
                                               <input id="AttributeId'.$number.$Attribute['AttributeId'].'" type="checkbox" name="AttributeId['.$number.'][]" value="'.$Attribute['AttributeId'].'" '.(in_array($Attribute['AttributeId'], $attributeIds)?'checked':'').'>
                                                        <label for="AttributeId'.$number.$Attribute['AttributeId'].'"><span></span>'.$Attribute['AttributeName'].'-'.$Attribute['AttributeCode'].'</label>
                                                        </div>';
                                            }
                                            echo '</div>';

                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 form-group button-2">
                                            <a onclick="loadAttributes()" class="btn btn-primary">Save</a>
                                    </div>
                                </form>


                            </div>
                            <div class="table-responsive">
                                <div class="graph">
                                    <div class="tables">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Attribute Code</th>
                                                    <th>Attribute Name</th>
                                                    <th>Status</th>
                                                    <th align="center" width="5%"> Edit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if( count($Attributes)>0)
                                                {
                                                    $i=0;
                                                    foreach($Attributes as $Attribute)
                                                    {
                                                        $update="'".$Attribute['AttributeId']."','".$Attribute['AttributeCode']."','".$Attribute['AttributeName']."','".$Attribute['Active']."'";
                                                        $i++;
                                                        echo '  <tr id="extra'.$i.'" class="'.($i%2?'active':'success').'">
                                                                    <td>'.$i.'</td>
                                                                    <td>'.$Attribute['AttributeCode'].'</td>
                                                                    <td > '.$Attribute['AttributeName'].'</td>
                                                                    <td >'.($Attribute['Active']==1?'Active':'Deactive').'</td>
                                                                    <td align="center"> <a onclick="showratetype('.$update.')"><i class="fa fa-edit"></i> </a></td>
                                                                </tr>';
                                                    }
                                                }

                                            ?>
                                            </tbody>
                                        </table>
                                        <div align="center">
                                            <?=( count($Attributes)==0?'<h4>This Room Has No Attribute</h4>':'') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div id="newattribute" class="modal fade" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h4 class="modal-title">Create a New Attribute</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </div>
                                            <div>
                                                <div class="graph-form" >
                                                    <form id="AttributeC" >
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Attribute Name</label>
                                                            <input style="background:white; color:black;"  name="AttributeName" id="AttributeName" type="text" placeholder="Type a Attribute Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Attribute Code</label>
                                                            <input style="background:white; color:black;"  name="AttributeCode" id="AttributeCode" type="text" placeholder="Type a Attribute Code" required="" >
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                        <div class="buttons-ui">
                                                            <a onclick="addAttribute()" class="btn green"><i class="far fa-save"></i> Save</a>
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <script type="text/javascript">
                                function loadAttributes()
                                {
                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "<?php echo lang_url(); ?>channel/loadAttributes",
                                        data: $("#addAttributes").serialize(),
                                        beforeSend: function() {
                                            showWait();
                                            setTimeout(function() { unShowWait(); }, 10000);
                                        },
                                        success: function(msg) {
                                            unShowWait();
                                            if (msg["success"]) {
                                                swal({
                                                    title: "Success",
                                                    text: "New Attribute was Add!",
                                                    icon: "success",
                                                    button: "Ok!",
                                                });
                                            } else {
                                                swal({
                                                    title: "upps, Sorry",
                                                    text:  msg["message"],
                                                    icon: "warning",
                                                    button: "Ok!",
                                                });
                                            }
                                        }
                                    });


                                }
                                function addAttribute()
                                {
                                    if ($("#AttributeName").val().length==0  )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Attibute Name to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            }).then((n) => {
                                               $("#AttributeName").focus();
                                            });

                                        return;
                                    }
                                    else if($("#AttributeCode").val().length==0 )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Attibute Code to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            }).then((n) => {
                                               $("#AttributeCode").focus();
                                            });
                                        return;
                                    }

                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "<?php echo lang_url(); ?>channel/saveAttribute",
                                        data: $("#AttributeC").serialize(),
                                        beforeSend: function() {
                                            showWait();
                                            setTimeout(function() { unShowWait(); }, 10000);
                                        },
                                        success: function(msg) {
                                            unShowWait();
                                            if (msg["success"]) {
                                                swal({
                                                    title: "Success",
                                                    text: "New Attribute was Add!",
                                                    icon: "success",
                                                    button: "Ok!",
                                                });
                                            } else {
                                                swal({
                                                    title: "upps, Sorry",
                                                    text:  msg["message"],
                                                    icon: "warning",
                                                    button: "Ok!",
                                                });
                                            }
                                        }
                                    });


                                }


                            </script>
                        </section>
                        <section id="section-4">
                            <div class="graph-form">
                                <div style="float: right;" class="buttons-ui">
                                    <a href="#newextra" data-toggle="modal" class="btn blue" class="btn blue">Create New Extra</a>
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
                                                        <th style="text-align: center;"> Edit</th>
                                                        <th style="text-align: center;"> Delete</th>
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
                                                                    <td align="center"> <a onclick="return delete_extras();"><i class="fas fa-trash-alt"></i> </a></td>
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
                                    <div class="col-md-12 form-group1">
                                        <form id="roomimages"   accept-charset="utf-8">
                                            <input type="hidden" name="roomid" value="<?=$Roominfo['property_id']?>">
                                            <div class="col-md-6 form-group1">
                                                <label class="control-label">Add New Images</label>
                                                <input type="file" id="Image" style="color: black;" reqired name="Image[]" multiple accept="image/png,image/gif,image/jpeg">
                                            </div>
                                            <div class="col-md-12 form-group button-2">
                                                <a onclick="saveimage()" class="btn btn-primary">Upload</a>
                                            </div>
                                        </form>
                                    </div>
                                    <h4>All images uploaded</h4>
                                    <div class="col-md-12 form-group1 graph">

                                              <ul class="gridder">
                                             <?php

                                                $i=0;
                                                foreach ($roomphotos as $value) {
                                                    $i++;
                                                    echo '<li class=" gridder-list" data-griddercontent="#content'.$i.'">';
                                                    echo ' <img id="super'.$value['photo_id'].'" src="'.base_url().$value['photo_names'].'" />';
                                                }
                                             ?>
                                            </ul>

                                              <?php

                                                $i=0;
                                                foreach ($roomphotos as $value) {
                                                    $i++;

                                                    echo '<div id="content'.$i.'" class="gridder-content"><center> <img id="'.$value['photo_id'].'" src="'.base_url().$value['photo_names'].'" /> </center></div>';
                                                }
                                             ?>



                                    </div>

                                </div>


                                <div class="clearfix"></div>
                            </div>
                            <div id="newextra" class="modal fade" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                               <center>
                                                <h4 class="modal-title">Create a New Extra</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span> </center>
                                            </div>
                                            <div>
                                                <div class="graph-form" >
                                                    <form id="ExtraC" >
                                                        <input type="hidden" name="roomid" value="<?=insep_encode($Roominfo['property_id'])?>">
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Extra Name</label>
                                                            <input style="background:white; color:black;"  name="ExtraName" id="ExtraName" type="text" placeholder="Type a Extra Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Price</label>
                                                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="ExtraPrice" id="ExtraPrice" type="text" placeholder="Type a Price" required="" >
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Tax</label>
                                                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="ExtraTax" id="ExtraTax" type="text" placeholder="Type a Tax" required="" value="0" >
                                                        </div>
                                                         <div class="col-md-12 form-group1">
                                                            <label class="control-label">Application</label>
                                                             <select style="width: 100%; padding: 9px; " id="structureid" name="structureid">
                                                                <option value="0">Select a Application</option>
                                                                <option value="1">Per Person</option>
                                                                <option value="2">Per Night</option>
                                                                <option value="3">Per Stay</option>
                                                             </select>
                                                        </div>




                                                        <div class="clearfix"> </div>
                                                        <div class="buttons-ui">
                                                            <a onclick="addExtra()" class="btn green"><i class="far fa-save"></i> Save</a>
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/galeriaimg.js"></script>
                            <script type="text/javascript">
                            function deleteimage(obj)
                            {
                                var id =$(obj).parent().parent().find( "img" ).attr('id');
                                $(obj).parent().parent().find( ".next" ).trigger('click');
                                $("#super"+id).css('display','none');
                                $("#"+id).remove();
                                $("#"+id).remove();

                                $.ajax({
                                    url: '<?php echo lang_url(); ?>channel/deleteimage',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {id:id},
                                });



                            }

                            function addExtra()
                            {
                                if ($("#ExtraName").val().length==0  )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Extra Name to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            }).then((n) => {
                                               $("#ExtraName").focus();
                                            });

                                        return;
                                    }
                                    else if($("#ExtraPrice").val().length==0 ||$("#ExtraPrice").val()==0 )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Price to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            }).then((n) => {
                                               $("#ExtraPrice").focus();
                                            });
                                        return;
                                    }
                                    else if($("#structureid").val().length==0 ||$("#structureid").val()==0 )
                                    {
                                        swal({
                                                title: "upps, Sorry",
                                                text:  "Type a Application to Continue",
                                                icon: "warning",
                                                button: "Ok!",
                                            }).then((n) => {
                                               $("#structureid").focus();
                                            });
                                        return;
                                    }

                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "<?php echo lang_url(); ?>channel/savenewextra",
                                        data: $("#ExtraC").serialize(),
                                        beforeSend: function() {
                                            showWait();
                                            setTimeout(function() { unShowWait(); }, 10000);
                                        },
                                        success: function(msg) {
                                            unShowWait();
                                            if (msg["success"]) {
                                                swal({
                                                    title: "Success",
                                                    text: "New Extra was Add!",
                                                    icon: "success",
                                                    button: "Ok!",
                                                });

                                                location.reload();
                                            } else {
                                                swal({
                                                    title: "upps, Sorry",
                                                    text:  msg["message"],
                                                    icon: "warning",
                                                    button: "Ok!",
                                                });
                                            }
                                        }
                                    });
                            }
                               jQuery(function() {

                                    // llamada al plugin
                                    jQuery('.gridder').gridderExpander({
                                        scroll: true,  // activar/desactivar auto-scroll
                                        scrollOffset: 30,  // distancia en píxeles de margen al hacer scroll
                                        scrollTo: "panel", // hacia donde se hace el auto-scroll
                                        animationSpeed: 400, // duración de la animación al hacer clic en elemento
                                        animationEasing: "easeInOutExpo", // tipo de animación
                                        showNav: true,  // activar/desactivar navegación
                                        nextText: "<i class='fa fa-arrow-right'></i>", // texto para pasar a la siguiente imagen
                                        prevText: "<i class='fa fa-arrow-left'></i>", // texto para pasar a la imagen anterior
                                        closeText: "<i class='fa fa-times'></i>", // texto del botón para cerrar imagen expandida
                                        deleteItem:'<i class="fa fa-trash" aria-hidden="true"></i>',
                                        onStart: function(){
                                            //código que se ejecuta cuando Gridder se inicializa
                                        },
                                        onContent: function(){
                                            //código que se ejecuta cuando Gridder ha cargado el contenido
                                        },
                                        onClosed: function(){
                                            //código que se ejecuta al cerrar Gridder
                                        }
                                    });

                                });

                                function saveimage()
                                    {
                                        if ($("#Image").val().length < 1) {
                                            swal({
                                                title: "upps, Sorry",
                                                text: "Missing Field Imagen!",
                                                icon: "warning",
                                                button: "Ok!",
                                            });
                                            return;
                                        }

                                    var data = new FormData($("#roomimages")[0]);
                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        contentType: false,
                                        processData: false,
                                        url: "<?php echo lang_url(); ?>channel/uploadRoomImagen",
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
                                                    text: "Imagen Changed!",
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

                            </script>
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
            <div id="createratetype">
                <form id="ratetypeC" accept-charset="utf-8">
                    <input type="hidden" name="roomid" value="<?=$Roominfo['property_id']?>">
                    <div class="col-md-12 form-group1">
                        <label class="control-label">Rate Type Name</label>
                        <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="Rate Type Name" required="">
                    </div>
                    <div class="col-md-12 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Meal Plan </label>
                        <select style="width: 100%; padding: 9px;" name="mealplanid" id="mealplanid">
                            <?php

                                echo '<option  value="0" >Select a Meal Plan</option>';
                                foreach ($mealplan as $value) {
                                    $i++;
                                    echo '<option value="'.$value['meal_id'].'" >'.$value['meal_name'].'</option>';
                                }
                          ?>
                        </select>
                    </div>
                    <div class="col-md-12 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Pricing Type </label>
                        <select style="width: 100%; padding: 9px;" name="pricingtype" id="pricingtype">
                            <option value="0">Select a Pricing Type</option>
                            <option value="1">Room Based Pricing</option>
                            <option value="2">Per Day</option>
                            <option value="3">Per Occupancy</option>
                        </select>
                    </div>
                    <div style="text-align: center; padding:15px;" class="col-md-12 form-group1 form-last">
                        <h3 ">Refundable</h3>
                        <hr>
                    </div>
                     <div class="col-md-4 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls ">Type</label>
                        <select style="width: 100%; padding: 9px; " name="type" id="type">
                            <option  value="0" >Select a Type</option>
                            <option  value="1" >Add</option>
                            <option  value="2" >Subtract</option>
                        </select>
                    </div>
                     <div class="col-md-4 form-group1 form-last">
                        <label style="padding:4px; " class="control-label controls ">Fee</label>
                        <select style="width: 100%; padding: 9px; " name="fee" id="fee">
                            <option  value="0 " >Select a Fee</option>
                            <option  value="1 " >Fixed</option>
                            <option  value="2 " >Percentage</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group1">
                        <label class="control-label ">Value</label>
                        <input onkeypress="return justNumbers(event);" style="background:white; color:black; " name="value" id="value" type="text" placeholder="Value" required=" ">
                    </div>


                    <div class="buttons-ui">
                        <a onclick="saveratetype();" class="btn green">Save</a>
                    </div>

                </form>
                 <div class="clearfix"></div>
            </div>


        </div>
    </div>
</div>
<div id="updateRate " class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal ">&times;</button>
                <h4 class="modal-title ">Update Rate Type</h4>
            </div>
            <div id="createratetype ">
                <form id="ratetypeUP " accept-charset="utf-8 ">
                    <input type="hidden " name="ratetypeid " id="ratetypeid " value=" ">
                    <div class="col-md-12 form-group1 " >
                        <div class="onoffswitch " style="float: right; ">
                            <input type="checkbox " name="statusid " class="onoffswitch-checkbox " id="statusid " >
                            <label class="onoffswitch-label " for="statusid ">
                                <span class="onoffswitch-inner "></span>
                                <span class="onoffswitch-switch "></span>
                            </label>
                        </div>
                    </div>
                   <div class="col-md-12 form-group1 ">
                        <label class="control-label ">Rate Type Name</label>
                        <input style="background:white; color:black; " name="name " id="nameup " type="text " placeholder="Rate Type Name " required=" ">
                    </div>
                    <div class="col-md-12 form-group1 form-last ">
                        <label style="padding:4px; " class="control-label controls ">Meal Plan </label>
                        <select style="width: 100%; padding: 9px; " name="mealplanid " id="mealplanidup ">
                            <?php

                                echo '<option  value="0 ">Select a Meal Plan</option>';
                                foreach ($mealplan as $value) {
                                    $i++;
                                    echo '<option value=" '.$value['meal_id '].' " >'.$value['meal_name'].'</option>';
                                }
                          ?>
                        </select>
                    </div>
                    <div class="col-md-12 form-group1 form-last ">
                        <label style="padding:4px; " class="control-label controls ">Pricing Type </label>
                        <select style="width: 100%; padding: 9px; " name="pricingtype " id="pricingtypeup ">
                            <option  value="0 " >Select a Pricing Type</option>
                            <option  value="1 " >Room Based Pricing</option>
                            <option  value="2 " >Per Day</option>
                            <option  value="3 " >Per Occupancy</option>
                        </select>
                    </div>


                    <div style="text-align: center; padding:15px; " class="col-md-12 form-group1 form-last ">
                        <h3 ">Refundable</h3>
                        <hr>
                    </div>
                    <div class="col-md-4 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Type</label>
                        <select style="width: 100%; padding: 9px;" name="type" id="typeup">
                            <option value="0">Select a Type</option>
                            <option value="1">Add</option>
                            <option value="2">Subtract</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Fee</label>
                        <select style="width: 100%; padding: 9px;" name="fee" id="feeup">
                            <option value="0">Select a Fee</option>
                            <option value="1">Fixed</option>
                            <option value="2">Percentage</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group1">
                        <label class="control-label">Value</label>
                        <input onkeypress="return justNumbers(event);" style="background:white; color:black;" name="value" id="valueup" type="text" placeholder="Value" required="">
                    </div>
                    <div class="buttons-ui">
                        <a onclick="updateratetype();" class="btn green">Save</a>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
new CBPFWTabs(document.getElementById('tabs'));
</script>
<script type="text/javascript">
function saveratetype() {
    if ($("#name").val() == "") {
        swal({
            title: "upps, Sorry",
            text: 'Missing Field Rate Type Name',
            icon: "warning",
            button: "Ok!",
        });
        $("#name").focus();
        return;

    } else if ($("#mealplanid").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Meal Plan to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#mealplanid").focus();
        return;
    } else if ($("#pricingtype").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Pricing Type to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#pricingtype").focus();
        return
    } else if ($("#type").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Refundable Type to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#type").focus();
        return
    } else if ($("#fee").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Refundable Fee to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#fee").focus();
        return
    } else if ($("#value").val() == 0 || $("#value").val() == "") {
        swal({
            title: "upps, Sorry",
            text: 'Type a Refundable Value to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#value").focus();
        return
    }

    var data = $("#ratetypeC").serialize();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/saveRateType",
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
                    text: "Rate Type Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["msg"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });

}

function showratetype(id, name, mealplan, pricing, type, fee, value, active) {
    $("#ratetypeid").val(id);
    $("#nameup").val(name);
    $("#mealplanidup").val(mealplan);
    $("#pricingtypeup").val(pricing);
    $("#typeup").val(type);
    $("#feeup").val(fee);
    $("#valueup").val(value);
    $("#statusid").attr('checked', (active == '1' ? true : false));
    $("#updateRate").modal();
}

function updateratetype() {
    if ($("#nameup").val() == "") {
        swal({
            title: "upps, Sorry",
            text: 'Missing Field Rate Type Name',
            icon: "warning",
            button: "Ok!",
        });
        $("#nameup").focus();
        return;

    } else if ($("#mealplanidup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Meal Plan to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#mealplanidup").focus();
        return;
    } else if ($("#pricingtypeup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Pricing Type to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#pricingtypeup").focus();
        return
    } else if ($("#typeup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Refundable Type to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#typeup").focus();
        return
    } else if ($("#feeup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: 'Select a Refundable Fee to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#feeup").focus();
        return
    } else if ($("#valueup").val() == 0 || $("#value").val() == "") {
        swal({
            title: "upps, Sorry",
            text: 'Type a Refundable Value to Continue',
            icon: "warning",
            button: "Ok!",
        });
        $("#valueup").focus();
        return
    }

    var data = $("#ratetypeUP").serialize();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/updateRateType",
        data: data,
        beforeSend: function() {
            showWait('Updating Rate Type');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["success"]) {
                swal({
                    title: "Success",
                    text: "Rate Type Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["msg"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });

}
</script>
