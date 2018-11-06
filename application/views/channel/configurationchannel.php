<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/allChannelList">Channels List</a></li>
            <li class="active">Channels Set up</li>
        </ol>
    </div>
    <div class="col-md-4 graph-form ">
        <section >
            <div class="col-md-12 active">
               <center><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/" . $ChannelInfo['image']));?>" class="img img-responsive"> 
                <?php 

                if(isset($Config['user_connect_id']))
                {echo '<a href="'.base_url().'mapping/mappingRooms/'.insep_encode($Config['channel_id']).'" class="btn blue">Set mapping</a>'; 
                } ?>
               </center>
               <hr >
            </div>

            <div class="col-md-12 active">
               <center><h4>Supported Operations </h4></center>
                <hr >
               <?php
                    foreach ($AllSupport as  $value) {
                        echo '<p><i class="fa fa-check"></i>'.$value['operation_name'].'</p>';
                    }

               ?>


            </div>
           
        </section>

    </div>
    
    <div class="col-md-8 graph-form ">
        <section >
            <form id="configcreate">
                <input type="hidden" name="user_connect_id" value="<?=(isset($Config['user_connect_id'])?$Config['user_connect_id']:0)?>">
                <input type="hidden" name="channelid" value="<?=$ChannelInfo['channel_id']?>">
                <input type="hidden" name="test_url" value="<?=$urls['test_url']?>">
                <input type="hidden" name="live_url" value="<?=$urls['live_url']?>">
                <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Status</label>
                        <div class="col-md-8">
                            <div class="input-group input-icon right">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid" <?=(isset($Config['status'])?($Config['status']=='enabled'?'checked':''):'checked')?> >
                                    <label class="onoffswitch-label" for="statusid">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
                
                 <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Username</label>
                        <div class="col-md-8">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <input id="username" <?=($ChannelInfo['channel_id']==2 || $ChannelInfo['channel_id']==1?'readonly':'')?> value="<?=($ChannelInfo['channel_id']==2 || $ChannelInfo['channel_id']==1?$ChannelInfo['channel_username']:(isset($Config['user_name'])?$Config['user_name']:''))?>" name="username" class="form-control1 icon" type="text" placeholder="Username">
                            </div>
                        </div>
                </div>
                <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Password</label>
                        <div class="col-md-8">
                        <div class="input-group input-icon right">
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                            <input <?=($ChannelInfo['channel_id']==2 || $ChannelInfo['channel_id']==1?'readonly':'')?> value="<?=($ChannelInfo['channel_id']==2 || $ChannelInfo['channel_id']==1?$ChannelInfo['channel_password']:(isset($Config['user_password'])?$Config['user_password']:''))?>" type="password" name="password" id="password" class="form-control1 icon" placeholder="Password">
                        </div>
                    </div>
                </div>
                 <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Hotel ID</label>
                        <div class="col-md-8">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fa fa-h-square"></i>
                                </span>
                                <input value="<?=(isset($Config['hotel_channel_id'])?$Config['hotel_channel_id']:'')?>"  id="hotelid" name="hotelid" class="form-control1 icon" type="text" placeholder="Hotel ID">
                            </div>
                        </div>
                </div>
                <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Email Address for Reservations</label>
                        <div class="col-md-8">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input value="<?=(isset($Config['reservation_email'])?$Config['reservation_email']:'')?>" id="email" name="email" class="form-control1 icon" type="text" placeholder="Email Address">
                            </div>
                        </div>
                </div>
                <div class="form-group col-md-12">
                    <label style="text-align: right;" for="percentage" class="col-md-3 control-label">Commission Type</label>
                        <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-percentage"></i>
                                    <label  for="percentage" >Percentage</label>
                                    <input value="0"  id="percentage" name="CommissionType"  type="radio" <?=(isset($Config['CommissionType']) && $Config['CommissionType']==0?'checked':'')?>>
                                </span>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-money-bill-alt"></i>
                                    <label  for="money" >Money</label>
                                     <input value="1"  id="money" name="CommissionType"  type="radio" <?=(isset($Config['CommissionType']) && $Config['CommissionType']==1?'checked':'')?> >
                                </span>

                                
                            </div>
                        </div>
                </div>
                
                 <div class="form-group">
                    <label style="text-align: right;" class="col-md-3 control-label">Commission Amount</label>
                        <div class="col-md-8">
                            <div class="input-group input-icon right">
                                <span class="input-group-addon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </span>
                                <input value="<?=(isset($Config['amount'])?$Config['amount']:'')?>" id="amount" name="amount" class="form-control1 icon" type="text" placeholder="Type Commission Amount ">
                            </div>
                        </div>
                </div>
                <div class="buttons-ui">
                        <a onclick="saveConfig()" class="btn blue">Save</a>
                </div>
            </form>
        </section>

    </div>
    <div class="clearfix"> </div>

</div>
</div>
</div>
<script type="text/javascript">
    function saveConfig() {



    var data = $("#configcreate").serialize();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/saveconfigurationChannel",
        data: data,
        beforeSend: function() {
            showWait('Saving Set Up');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {

            unShowWait();
            if (msg['success']) {
                swal("The Set Up Saved Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
    }
</script>