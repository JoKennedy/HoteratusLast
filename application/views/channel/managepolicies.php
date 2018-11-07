
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Manage Policies</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div style="float: right;" class="buttons-ui">
        <a href="#addPolicy"  data-toggle="modal" class="btn blue">Add New Policy</a>
        <a href="#addPolicytype"  data-toggle="modal" class="btn orange">Add New Policy Type</a>
    </div>
     <div class="clearfix"></div>
     <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="proList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Police Type</th>
                            <th>Police Name</th>
                            <th>Fee Type</th>
                            <th width="10%">Status</th>
                            <th width="10%">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllPolicies)>0) {

                            $i=0;
                            foreach ($AllPolicies as  $value) {
                                $i++;
                                /*$update="'".$value['paymentmethodid']."','".$value['name']."','".$value['email']."','".$value['apikey']."','".$value['merchantid']."','".$value['publickey']."','".$value['active']."','".$value['providerid']."'";*/
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['policytypename'].'  </td> <td> '.$value['Name'].'  </td> <td>'.($value['feetype']==1?'Amount':($value['feetype']==2?'Percentage':'Per Night')).'</td>
                                 <td>'.($value['active']==1?'Active':'Deactive').'</td> <td> <a href="#addPaymentUP" onclick ="updatePayment()" data-toggle="modal"> <i class="fa fa-pencil-square-o"></i></a></td>  </tr>   ';
                            }
                           

                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllPolicies)==0) {echo '<h4>No Policy Configured!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<div id="addPolicytype" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">New Policy Type</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="createpolicetype" >
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Name Policy Type</label>
                            <input style="background:white; color:black;" name="nametype" id="nametype" type="text" placeholder="Policy Type" required="">
                    </div>

                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="savePolicyType()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<div id="addPolicy" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Set Up a New Policy</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="createplicy" >

                  
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Policy Name</label>
                            <input style="background:white; color:black;" name="policyname" id="policyname" type="text" placeholder="Policy Type" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Description</label>
                            <textarea id="policydescription" name="policydescription"></textarea>
                            
                    </div>  
                    <div class="col-md-6 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Policy Type</label>
                        <select  style="width: 100%; padding: 9px;" name="policytypeid" id="policytypeid" >
                            <?php

                                    echo '<option value="0" >Select a Policy Type</option>';
                                    foreach ($Policytype as $value) {
                                        
                                        echo '<option  value="'.$value['policytypeid'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group1">
                            <label class="control-label">Days Before</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="daysbefore" id="daysbefore" type="text" placeholder="Stock" required="">
                    </div>
                    <div class="col-md-6 form-group1">
                            <label class="control-label">Fee</label>
                            <select style="width: 100%; padding: 9px;" name="feetype" id="feetype" >
                            <option value="0" >Select a Fee Type</option>
                            <option value="1" >Fixed</option>
                            <option value="2" >Percentage</option>
                            <option value="3" >Per Night</option>
                        </select>    
                    </div>
                    <div class="col-md-6 form-group1">
                            <label class="control-label">Value</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="amount" id="amount" type="text" placeholder="Value" required="">
                    </div>
                        
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="savePolicy()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    
   
    function savePolicy()
    {
        if ($("#policyname").val()=='' || $("#policyname").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type a Policy Name To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#policydescription").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type a Policy description To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#policytypeid").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Select a Policy Type To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        

         var data = $("#createplicy").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/savePolicy",
            data: data,
            beforeSend: function() {
                showWait('Saving a new Policy');
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {

                unShowWait();
               if (msg['success']) {
                 swal({
                        title: "Success",
                        text: "Policy successfully created!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        window.location.reload();
                    });
               }
               else
               {
                    swal({
                        title: "Error",
                        text: "Policy was not created!",
                        icon: "error",
                        button: "Ok!",
                    });
               }
            }
        });
    }
    
    function updatePayment(paymentmethodid,providername,email,apikey,merchantid,publickey,active,providerid)  
    {   viewnoteup(providerid);
        $("#paymentmethodidup").val(paymentmethodid);
        $("#providernameup").val(providername);
        $("#emailup").val(email);
        $("#apikeyup").val(apikey);
        $("#merchantidup").val(merchantid);
        $("#publickeyup").val(publickey);
        $('#myonoffswitch').prop('checked', (active==1?true:false));

    }
    function updatePaymentM()
    {
        if ( $("#emailup").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type a Email To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#apikeyup").val().length==0 && $("#apikeysup").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Api Key To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
         if ( $("#merchantidup").val().length==0 && $("#merchantidsup").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Merchantid To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
         if ( $("#publickeyup").val().length==0 && $("#publickeysup").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Publickey To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }

         var data = $("#updateprovider").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/updatePaymentMethod",
            data: data,
            beforeSend: function() {
                showWait('Update Payment Method');
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {

                unShowWait();
               if (msg['success']) {
                 swal({
                        title: "Success",
                        text: "Payment Method successfully updated!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        window.location.reload();
                    });
               }
               else
               {
                    swal({
                        title: "Error",
                        text: "Payment Method was not updated!",
                        icon: "error",
                        button: "Ok!",
                    });
               }
            }
        });
    }
    function savePolicyType()
    {
          if ( $("#nametype").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type a Name Policy Type To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
         var data = $("#createpolicetype").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/savePolicyType",
            data: data,
            beforeSend: function() {
                showWait('Saving Policy Type');
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {

                unShowWait();
               if (msg['success']) {
                 swal({
                        title: "Success",
                        text: "Policy Type successfully created!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        window.location.reload();
                    });
               }
               else
               {
                    swal({
                        title: "Error",
                        text: "Policy Type was not created!",
                        icon: "error",
                        button: "Ok!",
                    });
               }
            }
        });


    }
    
</script>