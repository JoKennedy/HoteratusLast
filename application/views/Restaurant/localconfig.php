<div class="outter-wp" >
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Local Configurations</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div class="clearfix"></div>

    <div class="graph-form">
        <form id="SchedulePos">
           <div class="col-md-12 form-group1 " >
              <div class="onoffswitch" style="float: left;">
                  <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid" <?=($Posinfo['active']==1?'checked':'')?> >
                  <label class="onoffswitch-label" for="statusid">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div>
          </div>
          <div class="clearfix"></div>

            <div class="col-md-4 form-group1 form-last">
                    <label style="padding:4px;" class="control-label controls">Day of the Week</label>
                    <select style="width: 100%; padding: 9px;" name="day" id="day">
                        <?php

                            $days = array(7=>"Sunday", 1=>"Monday", 2=>"Tuesday", 3=>"Wednesday",4=>"Thursday",5=>"Friday", 6=>"Saturday");
                             echo '<option  value="0" >Select a Day</option>';
                            foreach ($days as $key  =>$day ) {

                                echo '<option value="'.$key.'" >'.$day.'</option>';

                            }
                        ?>
                    </select>
            </div>

                <div class="col-md-4 form-group1">
                  <label class="control-label">Open</label>
                  <input style="background:white; color:black; width: 100%" name="hourtime1" id="hourtime1" type="text" placeholder="Hour" required="">
                </div>
                <div class="col-md-4 form-group1">
                    <label class="control-label">Close</label>
                    <input style="background:white; color:black; width: 100%" name="hourtime2" id="hourtime2" type="text" placeholder="Hour" required="">
                </div>
                 <div class="buttons-ui col-md-12 form-group1">
                    <a onclick="saveDay()" class="btn green">Add Schedule</a>
                </div>
             </form>

            <div class="clearfix"> </div>
            <br>
            <br>
            <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="tabletask" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Day of the Week</th>
                            <th>Open</th>
                            <th>Close</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (count($AllSchedule)>0) {
                            
                            $i=0;
                            foreach ($AllSchedule as  $value) {
                                $i++;
                               
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> 
                                <td> '.$days[$value['daysofweek']].'  </td> 
                                <td> '.$value['startdate'].'  </td> 
                                <td>'.$value['enddate'].' </td> </tr>   ';

                            }

                                                                
                                                              
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllSchedule)==0) {echo '<h4>No Schedule Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
            <div class="clearfix"> </div>
       
    </div>
</div>
</div>
</div>
<link href="<?php echo base_url();?>user_asset/back/css/jquery.timepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>user_asset/back/js/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    var posid="<?=$Posinfo['myposId']?>";
    $('#hourtime1').timepicker({ 'timeFormat': 'h:i A','step': 15  });
    $('#hourtime2').timepicker({ 'timeFormat': 'h:i A','step': 15   });

    function saveDay () {
        
    if ($("#day").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Day to Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#hourtime1").val() == '') {
        swal({
            title: "upps, Sorry",
            text: "Selected a Opening Time To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#hourtime2").val() =='') {

        swal({
            title: "upps, Sorry",
            text: "Selected a Closing Time To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

 var data ={'posid':posid,'day':$("#day").val(),'hourtime1':$("#hourtime1").val(),'hourtime2':$("#hourtime2").val()}        
     $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/SaveLocalConfig",
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
                    text: "Schedule Updated!",
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