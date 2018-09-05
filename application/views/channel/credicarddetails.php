<div class="modal-dialog">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Credit Card Info.</h4>
        </div>
        <div class="modal-body form">
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <div class="form-body">
                    <form id="ccinfo" accept-charset="utf-8">
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Credit Card Type</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($cctype)?$cctype:'')?>" Name="cctype" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Cardholder Name</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ccname)?$ccname:'')?>" Name="ccholder" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Card Number</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ccnumber)?$ccnumber:'')?>" id="ccnumber" Name="ccnumber" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>CVV</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($cccvv)?$cccvv:'')?>" Name="cccvv" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Expiration month</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ccmonth)?$ccmonth:'')?>" Name="ccmonth" readonly="">
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Expiration Year</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ccyear)?$ccyear:'')?>" Name="ccyear" readonly="">
                        </div>
                        <div class="col-md-12 form-group1" style="display: none;">
                            <label class="control-label"><strong>Expiration Year</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($country)?$country:'')?>" Name="cccountry" readonly="">
                        </div>
                        <div class="col-md-12 form-group1" style="display: none;">
                            <label class="control-label"><strong>Expiration Year</strong></label>
                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ChannelName)?$ChannelName:'Hoteratus')?>" Name="channelname" readonly="">
                        </div>
                        <div class="buttons-ui">
                            <a type="button" class="btn red" data-dismiss="modal"><i class="fa fa-times"></i>Close</a>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>