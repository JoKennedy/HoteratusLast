<div class="outter-wp" style="height: 3000px;">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Employee Schedule</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div class="clearfix"></div>
    <?php 

    $company_name=(isset($BillInfo['company_name'])?$BillInfo['company_name']:'');
    $town=(isset($BillInfo['town'])?$BillInfo['town']:'');
    $address=(isset($BillInfo['address'])?$BillInfo['address']:'');
    $zip_code=(isset($BillInfo['zip_code'])?$BillInfo['zip_code']:'');
    $mobile=(isset($BillInfo['mobile'])?$BillInfo['mobile']:'');
    $vat=(isset($BillInfo['vat'])?$BillInfo['vat']:'');
    $reg_num=(isset($BillInfo['reg_num'])?$BillInfo['reg_num']:'');
    $email_address=(isset($BillInfo['email_address'])?$BillInfo['email_address']:'');
    $country=(isset($BillInfo['country'])?$BillInfo['country']:'');

    ?>
    <div class="graph-form">
        <form id="SchedulePos">
           <div class="col-md-12 form-group1 " >
              <div class="onoffswitch" style="float: left;">
                  <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid" >
                  <label class="onoffswitch-label" for="statusid">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div>
          </div>
          <div class="clearfix"></div>
           <div class="col-md-3" >
                <?php
                echo '<div class="panel-default"> <div class="panel-heading"> <h3 class="panel-title">Days Of The Week</h3> </div> ';
                 echo '<div class="panel-body">';
                $days = array("Sunday"=>1, "Monday"=>2, "Tuesday"=>3, "Wednesday"=>4,"Thursday"=>5,"Friday"=>6, "Saturday"=>7);
                
                foreach ($days as $day => $key) {

                                echo '<div class="col-md-12 ">
                                             <table>
                                                <tbody>
                                                <tr>
                                                <td><input type="checkbox" name="days[]" id="days'.$key.'" value="'.$key.'" checked></td>
                                                <td><label for="days'.$key.'">&nbsp '.$day.'</label></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>';
                            }
                            echo ' <div class="clearfix"> </div> </div> </div>';


                        ?>
            </div>
            <div class="col-md-9 form-group1">
                <div class="col-md-6 form-group1">
                  <label class="control-label">Open</label>
                  <input style="background:white; color:black; width: 100%" name="hourtime1" id="hourtime1" type="text" placeholder="Hour" required="">
                </div>
                <div class="col-md-6 form-group1">
                    <label class="control-label">Close</label>
                    <input style="background:white; color:black; width: 100%" name="hourtime2" id="hourtime2" type="text" placeholder="Hour" required="">
                </div>
                 <div class="buttons-ui col-md-12 form-group1">
                    <a onclick="add()" class="btn green">Add Schedule</a>
                </div>
            </div>

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
                                $update="'".$value['mypostablereservationid']."','".$value['mypostableid']."','".  $value['datetimereservation']."','".$value['signer']."','".$value['Roomid']."','".$value['starttime']."'"  ;
                                $date = date_create($value['datetimereservation']);
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['signer'].'  </td> 
                                <td> '.$value['marketingp'].'  </td> <td>'.$value['tablename'].' </td> <td>'.date_format($date, 'm/d/Y').' </td>
                                 <td>'.$value['starttime'].' </td> <td><a  onclick ="showupdate('.$update.')"><i class="fa fa-cog"></i></a></td> </tr>   ';

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
        </form>
    </div>
</div>
</div>
</div>
<link href="<?php echo base_url();?>user_asset/back/css/jquery.timepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>user_asset/back/js/jquery.timepicker.min.js"></script>
<script type="text/javascript">
$('#hourtime1').timepicker({ 'timeFormat': 'H:i:A' });
$('#hourtime2').timepicker({ 'timeFormat': 'h:i A' });
function saveCategory() {



    var data = new FormData($("#CategoryC")[0]);
    if ($("#categoryname").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Category Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#Image").val().length < 1) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Imagen!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    $.ajax({
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false,
        url: "<?php echo lang_url(); ?>pos/saveCategory",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {

            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Category Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Category was not Created! Error: " + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }

            unShowWait();



        }
    });


}
</script>