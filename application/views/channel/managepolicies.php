
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
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['policytypename'].'  </td> <td> '.$value['name'].'  </td> <td>'.($value['feetype']==1?'Amount':($value['feetype']==2?'Percentage':'Per Night')).'</td>
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
                  <form id ="createprovider" >

                  
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Name Policy</label>
                            <input style="background:white; color:black;" name="nametype" id="nametype" type="text" placeholder="Policy Type" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Description</label>
                            <textarea></textarea>
                            
                    </div>  
                    <div class="col-md-6 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Policy Type</label>
                        <select onchange="viewnote(this)" style="width: 100%; padding: 9px;" name="policytypeid" id="policytypeid" >
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
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="stock" id="stock" type="text" placeholder="Stock" required="">
                    </div>
                    <div class="col-md-6 form-group1">
                            <label class="control-label">Fee</label>
                            <select onchange="viewnote(this)" style="width: 100%; padding: 9px;" name="feetype" id="feetype" >
                            <option value="0" >Select a Fee Type</option>
                            <option value="1" >Fixed</option>
                            <option value="2" >Percentage</option>
                            <option value="3" >Per Night</option>
                        </select>    
                    </div>
                    <div class="col-md-6 form-group1">
                            <label class="control-label">Value</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="stock" id="stock" type="text" placeholder="Stock" required="">
                    </div>
                        
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="savePaymentM()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<div id="addPaymentUP" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Update  a  Payment Method</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="updateprovider" >
                    <input type="hidden" name="paymentmethodidup" id="paymentmethodidup">
                    <div class="col-md-12 form-group1 form-group1">
                        <label  class="control-label ">Provider Name</label>
                         <input style="background:white; color:black; " readonly="" id="providernameup" type="text" >
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Email</label>
                            <input style="background:white; color:black;" name="emailup" id="emailup" type="text" placeholder="E-Mail" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="apikeysup">
                            <label class="control-label">Api Key</label>
                            <input style="background:white; color:black; " name="apikeyup" id="apikeyup" type="text" placeholder="ApiKey" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="merchantidsup">
                            <label class="control-label">Merchantid</label>
                            <input style="background:white; color:black; " name="merchantidup" id="merchantidup" type="text" placeholder="Merchantid" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="publickeysup">
                            <label class="control-label">Publickey</label>
                            <input style="background:white; color:black; " name="publickeyup" id="publickeyup" type="text" placeholder="Publickey" required="">
                    </div>
                    <div class="col-md-12 form-group1" >
                        <label class="control-label">Status</label>
                        <div class="onoffswitch">
                            <input onchange="changestatus(this.checked)" type="checkbox" name="activeup" class="onoffswitch-checkbox" id="myonoffswitch" >
                        <label class="onoffswitch-label" for="myonoffswitch">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                        </div>
                    </div>
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="updatePaymentM()" class="btn green">Update</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    
    function viewnote(options)
    {
        valor=$(options).val();
        $("#apikeys").css('display',(valor==1 || valor==2 || valor==3?'':'none'));
        $("#merchantids").css('display',( valor==2 ?'':'none'));
        $("#publickeys").css('display',(valor==2 ?'':'none'));
    }
    function viewnoteup(valor)
    {
        $("#apikeysup").css('display',(valor==1 || valor==2 || valor==3?'':'none'));
        $("#merchantidsup").css('display',( valor==2 ?'':'none'));
        $("#publickeysup").css('display',(valor==2 ?'':'none'));
    }

    function savePaymentM()
    {
        if ($("#providerid").val()==0 || $("#providerid").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Select a Provider To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#email").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type a Email To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#apikey").val().length==0 && $("#apikeys").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Api Key To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
         if ( $("#merchantid").val().length==0 && $("#merchantids").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Merchantid To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
         if ( $("#publickey").val().length==0 && $("#publickeys").css("display")!='none') {
             swal({
                title: "upps, Sorry",
                text: "Type a Publickey To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }

         var data = $("#createprovider").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/savePaymentMethod",
            data: data,
            beforeSend: function() {
                showWait('Saving Payment Method');
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {

                unShowWait();
               if (msg['success']) {
                 swal({
                        title: "Success",
                        text: "Payment Method successfully created!",
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
                        text: "Payment Method was not created!",
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