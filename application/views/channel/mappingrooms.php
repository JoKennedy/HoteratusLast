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
        <h4 >Mapping configuration for <?= strtoupper($channelinfo['channel_name'])?> </h4>
    </div>
     <div style="float: right;" class="buttons-ui">
     
        
        <a onclick="setcalendar()" class="btn blue">Import Room Rate Information from Channel</a>
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
    <div class="col-md-6 form-group1 form-last">
        <label style="padding:4px;" class="control-label controls"><?=$channelinfo['channel_name']?> Rooms </label>
        <select style="width: 100%; padding: 9px;" name="productidup" id="productidup">
            <?php

                echo '<option  value="0" >Select a Room</option>';
                foreach ($roomsunmapped as $value) {
                    $i++;
                    echo '<option value="'.$value['import_mapping_id'].'" >'.$value['RoomName'].'</option>';
                }
          ?>
        </select>
    </div>
    <div class="col-md-6 form-group1 form-last">
        <label style="padding:4px; color:#00c6d7;" class="control-label controls">Channel Manager Rooms</label>
        <select style="width: 100%; padding: 9px;" name="productidup" id="productidup">
            <?php

                echo '<option  value="0" >Select a Room</option>';
                foreach ($roomsunmapped as $value) {
                    $i++;
                    echo '<option value="'.$value['itemPosId'].'" >'.$value['name'].'</option>';
                }
          ?>
        </select>
    </div>
     <div class="clearfix"></div>
    <div><h3 style="text-align:center; color:#00c6d7;">Rate Configuration</h3></div>
     <div class="clearfix"></div>
    <div class="col-md-6 form-group1">
        <label class="control-label">Rate Conversion Multiplier</label>
        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="qtyup" id="qtyup" type="text" placeholder="Rate Conversion" value="1" required="">
    </div>
    <div class="clearfix"></div>
    <div class="buttons-ui">
        <a onclick="saveMapping()" class="btn blue">Save</a>
    </div>
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
                                                <th>Room Type</th>
                                                <th>Conversion Rate</th>
                                                <th>Action</th>
                                                <th>Import</th>
                                        </tr>
                                                             </thead>
                                <tbody>';
                $i=0;
                foreach ($roomsmapped as  $value) {
                        $i++;
                        echo ' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
                            ' </th> <td>'.$value['property_id'].'  </td> <td>'.$value['rate_conversion'].'</td>
                                <td  align="center"><a ><i class="fa fa-check-circle fa-2x"></i></a> </td> 
                                <td  align="center"><a ><i class="fa fa-check-circle fa-2x"></i></a> </td></tr>';
                    /*[mapping_id] => 332
                    [owner_id] => 13
                    [hotel_id] => 13
                    [property_id] => 109
                    [rate_id] => 0
                    [channel_id] => 2
                    [import_mapping_id] => 78
                    [guest_count] => 0
                    [refun_type] => 0
                    [enabled] => enabled
                    [included_occupancy] => 0
                    [extra_adult] => 0
                    [extra_child] => 0
                    [single_quest] => 0
                    [update_rate] => 1
                    [update_availability] => 1
                    [rate_conversion] => 9.54199
                    [explevel] => 0*/

                    }
                   echo '</tbody></table></div> </div>';
            }
        }

    ?>
</div>
</div>
</div>  