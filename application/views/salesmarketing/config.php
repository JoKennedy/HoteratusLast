
<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Sales And Marketing</li>
				<li class="active">Config Competive Set Analisis</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->


			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">

				<div class="graph">
				
				<div class="tab-main">
        <div class="tab-inner">
            <div id="tabs" class="tabs">
                <div class="">
                    <nav>
                        <ul>
                            <li><a href="#section-1" class="icon-shop"><i class="fa fa-info-circle"></i> <span>Properties and Ota's</span></a></li>
                            <li><a href="#section-2" class="icon-cup"><i class="fas fa-sort-numeric-up"></i> <span>Room Numbers & Amenities</span></a></li>
                            <li><a href="#section-3" class="icon-food"><i class="fa fa-cog"></i> <span>Rate Configuration & Attributes</span></a></li>
                            <li><a href="#section-4" class="icon-lab"><i class="fa fa-plus"></i> <span>Extras & Photos </span></a></li>
                            <li><a href="#section-5" class="icon-truck"> <i class="fas fa-chart-line"></i><span>Revenue Manage</span></a></li>
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
			<!--//graph-visual-->
		</div>
		<!--//outer-wp-->
		 <!--footer section start-->
		
		<!--footer section end-->
	</div>
</div>

<script type="text/javascript">

</script>