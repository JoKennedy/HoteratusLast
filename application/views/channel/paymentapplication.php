
<div class="modal-dialog">
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Payment Application</h4>
        </div>
        <div id="msgpayment" class="alert alert-warning" style="display: none; text-align: center;">
            <strong>Warning!</strong>
        </div>
        <div class="modal-body form">
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="paymentapplication" accept-charset="utf-8">
                    <div class="form-body">
                        <input type="hidden" id="invoiceid" name="" value="0" readonly="true">
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><strong>Payment Type</strong></label>
                            <select onchange="Method(this.value)" name="paymentTypeId" id="paymentTypeId" class="form-control1">
                                <?php

                                        if (count($payment['type'])>0) {
                                            echo '<option value="0">Select a payment Type</option>';
                                            foreach ($payment['type'] as $value) {

                                                echo '<option id = "'.$value['method'].'"  value="'.$value['paymenttypeid'].'">'.$value['description'].'</option>';
                                            }
                                        }
                                        else
                                        {
                                            echo '<option value="0">Does not have types of payments</option>';
                                        }

                                      ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Collection Type</strong></label>
                            <select name="providerid" id="providerid" class="form-control1">
                                <?php

                                        if (count($payment['method'])>0) {
                                            echo '<option value="0" >Select a Collection Type</option>';
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
                        <div class="col-md-6 form-group1" >
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
                        <div class="col-md-12 form-group1 metocc" style="display: none;">
                            <label class="control-label"><strong>Description</strong></label>
                            <input style="background:white; color:black; width: 100%;" Name="Description" placeholder="Type a Description">
                        </div>
                        <div class="col-md-6 form-group1 metocc" style="display: none;">
                            <label for="sendcvv" class="control-label"><strong>Send CVV  </strong></label>
                            <input id="sendcvv" value="1" class="input-small" type="checkbox" name="sendcvv">
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6 form-group1">
                                <label class="control-label"><strong>Amount To Pay </strong></label>
                                <input style="color:white; width: 75%; text-align: right;" onkeypress="return justNumbers(event);" type="text" id="amountdue" name="amountdue" value="0">
                                <input type="hidden" value=""  Name="nada" readonly="">
                            </div>
                             <div class="col-md-5 form-group1">
                                <label class="control-label"><strong>Total Discount </strong></label>
                                <input style="color:white; width: 75%; text-align: center;" onkeypress="return justNumbers(event);" type="text" id="discountP" name="discountP" value="0" readonly="">
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="clearfix"></div>
                    <div class="buttons-ui col-md-12">
                        <a type="button" class="btn red" data-dismiss="modal"><i class="fa fa-times"></i>Close</a>
                        <a id="submitpay" name="add" value="save" class="btn green"><i class="fa fa-check"></i> Submit Payment</a>
                        <?php  if ($User_Type==1 || in_array(3, $specialpermit)) {
              						echo '<a onclick="showccinfo()" class="metocc btn yellow" style="display: none;">Show CC Info.</a>';
              					} ?>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>

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
