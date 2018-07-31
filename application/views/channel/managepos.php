<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Manage POS</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>


        <div style="float: right;" class="buttons-ui">
        <a href="#createpos"  data-toggle="modal" class="btn blue">Add New POS</a>
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
                            <th>POS Name</th>
                            <th>POS Type</th>
                            <th>POS Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($allPos)>0) {

                            $i=0;
                            foreach ($allPos as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> <a href="'.site_url('pos/viewpos/'.secure($value['hotelId']).'/'.insep_encode($value['myposId'])).'">'.$value['description'].' </a> </td> <td>'.$value['postype'].'</td> <td>'.($value['active']==1?'Active':'Deactive').'</td>  </tr>  ';

                            }
                        } ?>
                    </tbody>
                </table>
                <?php if (count($allPos)==0) {echo '<h4>No POS Created!</h4>';} ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div id="createpos" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Create a New POS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="crateposid" >

                    <div class="col-md-6 form-group1">
                        <label class="control-label" >POS Name</label>
                        <input style="background:white; color:black;" name="posname" id ="posname" type="text" placeholder="Pos Name" required="">
                    </div>
                    <div class="col-md-6 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Type Of POS </label>
                        <select style="width: 100%; padding: 9px;" name="typeposid" id="typeposid" >
                            <?php

                                    echo '<option value="0" >Select a Type of POS</option>';
                                    foreach ($Postype as $value) {
                                        $i++;
                                        echo '<option  value="'.$value['POSTypeID'].'" >'.$value['Description'].'</option>';
                                    }
                              ?>
                        </select>
                    </div>
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="savePOS()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
function savePOS() {

var data =$("#crateposid").serialize()

    if($("#posname").val().length <3  ){
         swal({
           title: "upps, Sorry",
            text: "Missing Field POS Name!",
            icon: "warning",
            button: "Ok!",});
            return;
    }
    else if ($("#typeposid").val()==0) {
        swal({
           title: "upps, Sorry",
            text: "Missing Field Type Of POS!",
            icon: "warning",
            button: "Ok!",});
            return;
    }

    

 
  $.ajax({
      type: "POST",
      dataType: "json",
      url: "<?php echo lang_url(); ?>pos/savePOS",
      data: data,
      beforeSend:function() {
      showWait();
    }
      ,
      success: function(msg) {
        if (msg=="0") {
          swal({
           title: "Success",
            text: "POS Created!",
            icon: "success",
            button: "Ok!",}).then((n)=>{
              location.reload();
            });
        }
        else {
          swal({
           title: "upps, Sorry",
            text: "POS was not Created!",
            icon: "warning",
            button: "Ok!",});
        }

        unShowWait();


      }
  });

}

</script>
</div>
</div>
