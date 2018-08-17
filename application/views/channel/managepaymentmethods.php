<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Manage Payment Methods</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div style="float: right;" class="buttons-ui">
        <a href="#addPayment"  data-toggle="modal" class="btn blue">Add Payment Method</a>
    </div>
</div>
</div>
</div>
<div id="addPayment" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Config a New Payment Method</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="createprovider" >

                    <div class="col-md-12 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Provider Name</label>
                        <select onchange="viewnote(this)" style="width: 100%; padding: 9px;" name="providerid" id="providerid" >
                            <?php

                                    echo '<option value="0" >Select a Provider</option>';
                                    foreach ($AllProviders as $value) {
                                        
                                        echo '<option  value="'.$value['providerid'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                        </select>
                    </div>
                    <div class="col-md-12 form-group1">
                            <label class="control-label">Email</label>
                            <input style="background:white; color:black;" name="model" id="model" type="text" placeholder="E-Mail" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="apikeys">
                            <label class="control-label">Api Key</label>
                            <input style="background:white; color:black; " name="apikey" id="apikey" type="text" placeholder="ApiKey" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="merchantids">
                            <label class="control-label">Merchantid</label>
                            <input style="background:white; color:black; " name="merchantid" id="merchantid" type="text" placeholder="Merchantid" required="">
                    </div>
                    <div class="col-md-12 form-group1" style="display:none;" id="publickeys">
                            <label class="control-label">Publickey</label>
                            <input style="background:white; color:black; " name="publickey" id="publickey" type="text" placeholder="Publickey" required="">
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
<script type="text/javascript">
    
    function viewnote(options)
    {
        valor=$(options).val();
        $("#apikeys").css('display',(valor==1 || valor==2 || valor==3?'':'none'));
        $("#merchantids").css('display',( valor==2 ?'':'none'));
        $("#publickeys").css('display',(valor==2 ?'':'none'));
    }
</script>