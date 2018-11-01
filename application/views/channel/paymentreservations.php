 <form id="paymentapplication" accept-charset="utf-8">
                    <div class="form-body">
                        <input type="hidden" id="invoiceid" name="" value="0" readonly="true">
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><strong>Payment Method</strong></label>
                            <select onchange="Method(this.value)" name="paymentTypeId" id="paymentTypeId" class="form-control1">
                                <?php

                                        if (count($payment['type'])>0) {
                                            echo '<option value="0"   >Select a payment Type</option>';
                                            foreach ($payment['type'] as $value) {
                                                
                                                echo '<option id = "'.$value['method'].'"   value="'.$value['paymenttypeid'].'">'.$value['description'].'</option>';
                                            }
                                        }
                                        else
                                        {
                                            echo '<option value="0">Does not have types of payments</option>';
                                        }

                                      ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Collection Type</strong></label>
                            <select name="providerid" id="providerid" class="form-control1">
                                <?php

                                        if (count($payment['method'])>0) {
                                            echo '<option value="0" onclick="Method(0)"  >Select a Collection Type</option>';
                                            foreach ($payment['method'] as $value) {
                                                
                                                echo '<option  value="'.$value['providerid'].'">'.$value['name'].'</option>';


                                            }
                                        }
                                        else
                                        {
                                            echo '<option value="0">Does not have Collection Type</option>';
                                        }

                                      ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1" >
                            <label class="control-label"><strong>Currency</strong></label>
                            <select name="currency" id="currency" class="form-control1">
                                <?php

                                       
                                            echo '<option value="0" >Select a Currency</option>';
                                            foreach ($Currencies as $value) {
                                                
                                                echo '<option  value="'.$value['currency_code'].'"'.((isset($currency)?$currency:'USD')==$value['currency_code']?'selected':'').' >'.$value['currency_code'].'</option>';
                                            }

                                      ?>
                            </select>
                        </div>

                        <div class="form-group1 metocc" style="display: none;">
                            <div class="form-body">
                                    <form id="ccinfo" accept-charset="utf-8">
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label class="control-label"><strong>Credit Card Type</strong></label>
                                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($cctype)?$cctype:'')?>" Name="cctype" id="cctype" >
                                        </div>
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label class="control-label"><strong>Cardholder Name</strong></label>
                                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ccname)?$ccname:'')?>" Name="ccholder" id="ccholder">
                                        </div>
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label class="control-label"><strong>Card Number</strong></label>
                                            <input onkeypress="return justNumbers(event);" style="background:white; color:black; width: 100%;" value="<?=(isset($ccnumber)?$ccnumber:'')?>" id="ccnumber" Name="ccnumber"  >
                                        </div>
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label class="control-label"><strong>CVV</strong></label>
                                            <input onkeypress="return justNumbers(event);"  style="background:white; color:black; width: 100%;" value="<?=(isset($cccvv)?$cccvv:'')?>" Name="cccvv" id="cccvv">
                                        </div>
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label  class="control-label"><strong>Expiration month</strong></label>
                                            <input onkeypress="return justNumbers(event);"  style="background:white; color:black; width: 100%;" value="<?=(isset($ccmonth)?$ccmonth:'')?>" Name="ccmonth" id="ccmonth" >
                                        </div>
                                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                                            <label   class="control-label"><strong>Expiration Year</strong></label>
                                            <input onkeypress="return justNumbers(event);" style="background:white; color:black; width: 100%;" value="<?=(isset($ccyear)?$ccyear:'')?>" Name="ccyear" id="ccyear" >
                                        </div>
                                        <div class="col-md-12 form-group1" style="display: none;">
                                            <label class="control-label"><strong>Expiration Year</strong></label>
                                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($country)?$country:'')?>" Name="cccountry" >
                                        </div>
                                        <div class="col-md-12 form-group1" style="display: none;">
                                            <label class="control-label"><strong>Expiration Year</strong></label>
                                            <input style="background:white; color:black; width: 100%;" value="<?=(isset($ChannelName)?$ChannelName:'Hoteratus')?>" Name="channelname" >
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                        </div>
                        <div class="col-md-12 form-group1 " style="display: none;">
                            <label class="control-label"><strong>Description</strong></label>
                            <input style="background:white; color:black; width: 100%;" Name="Description" placeholder="Type a Description">
                        </div>
                        <div class="col-md-6 form-group1 metoccc" style="display: none;">
                            <label for="sendcvv" class="control-label"><strong>Send CVV  </strong></label>
                            <input id="sendcvv" value="1" class="input-small" type="checkbox" name="sendcvv">
                        </div>

                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                </form>

<script type="text/javascript">
    function showccinfo() {
        $("#ShowCC").modal();
    }
    function Method(methodid) {

        if (methodid > 1) {
            $(".metocc").show();
        } else {
            $(".metocc").hide();
            return;
        }
    }
</script>