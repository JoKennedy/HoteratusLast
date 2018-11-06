<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Tax Categories</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div style="float: right;" class="buttons-ui">
        <a href="#addTax"  data-toggle="modal" class="btn blue">Add Tax Category</a>
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
                            <th>Tax Category</th>
                            <th width="10%">Tax rate (%)</th>
                            <th width="15%">Included in Price</th>
                            <th width="10%">Status</th>
                            <th width="10%">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllTaxCategories)>0) {

                            $i=0;
                            foreach ($AllTaxCategories as  $value) {
                                $i++;
                                $update="'".$value['taxid']."','".$value['name']."','".$value['taxrate']."','".$value['includedprice']."','".$value['active']."'";
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td> <td> '.$value['taxrate'].'%  </td> <td> '.($value['includedprice']==0?'No':'Yes').'  </td>
                                <td>'.($value['active']==1?'Active':'Deactive').'</td> <td> <a href="#updateTax" onclick ="updateT('.$update.')" data-toggle="modal"> <i class="fa fa-edit"></i></a></td>  </tr>   ';
                            }
                           

                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllTaxCategories)==0) {echo '<h4>No Tax Categories!</h4>';} 
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
<div id="addTax" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">New Tax Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="createtax" >

                    <div class="col-md-12 form-group1">
                            <label class="control-label">Tax Category Name</label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="Tax Category Name" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Tax Rate %</label>
                            <input onkeypress="return justNumbers(event);" style="background:white; color:black;" name="rate" id="rate" type="text" placeholder="Tax Rate %" required="">
                    </div>
                    <div class="col-md-12 form-group1" >
                        <label class="control-label">Included in price</label>
                        <div class="onoffswitch">
                            <input  type="checkbox" name="included" class="onoffswitch-checkbox" id="myonoffswitch" >
                        <label class="onoffswitch-label" for="myonoffswitch">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                        </div>
                    </div>
                    
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="saveTax()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<div id="updateTax" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Update Tax Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="updatetax" >
                    <input type="hidden" name="taxid" id="taxid">
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Tax Category Name</label>
                            <input style="background:white; color:black;" name="nameup" id="nameup" type="text" placeholder="Tax Category Name" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Tax Rate %</label>
                            <input onkeypress="return justNumbers(event);" style="background:white; color:black;" name="rateup" id="rateup" type="text" placeholder="Tax Rate %" required="">
                    </div>
                    <div class="col-md-12 form-group1" >
                        <label class="control-label">Included in price</label>
                        <div class="onoffswitch">
                            <input  type="checkbox" name="includedup" class="onoffswitch-checkbox" id="myonoffswitchup" >
                        <label class="onoffswitch-label" for="myonoffswitchup">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                        </div>
                    </div>
                    
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="updateTax()" class="btn green">Update</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    

    function saveTax()
    {
        if ($("#rate").val()==0 || $("#rate").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type Tax Rate % to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#name").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Tax Category Name To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        

         var data = $("#createtax").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/saveTax",
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
                        text: "Tax Category successfully created!",
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
                        text: "Tax Category was not created!",
                        icon: "error",
                        button: "Ok!",
                    });
               }
            }
        });
    }
    
    function updateT(taxid,name,taxrate,includedprice,active)  
    {   
        $("#nameup").val(name);
        $("#taxid").val(taxid);
        $("#rateup").val(taxrate);
        $('#myonoffswitchup').prop('checked', (includedprice==1?true:false));

    }
    function updateTax()
    {
        if ($("#rateup").val()==0 || $("#rateup").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Type Tax Rate % to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        if ( $("#nameup").val().length==0 ) {
             swal({
                title: "upps, Sorry",
                text: "Tax Category Name To Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
        }
        

         var data = $("#updatetax").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>channel/updateTax",
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
                        text: "Tax Category successfully Update!",
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
                        text: "Tax Category was not Update!",
                        icon: "error",
                        button: "Ok!",
                    });
               }
            }
        });
    }
    
</script>