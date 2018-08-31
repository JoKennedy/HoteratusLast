<div class="page-content">
    <div class="container">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Widget settings form goes here
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="portlet light">
            <div class="portlet-body">
                <div class="invoice">
                    <div class="row invoice-logo">
                        <div class="col-xs-6 invoice-logo-space">
                            <h1><strong> Invoice </strong></h1>
                        </div>
                        <div class="col-xs-6">
                            <p>
                                #
                                <?php echo $invoice_number;?> /
                                <?php echo date('M d,Y');?><span class="muted">
                         </span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-8">
                            <?php 
                      //echo '<pre>';
                      //print_r($hotel_details);
                        ?>
                            <address>
                                <?php $site_logo = get_data(CONFIG,array('id'=>1))->row()->site_logo; ?>
                                <!--img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$site_logo));?>" class="img img-responsive hidden-prints" -->
                                <strong><?php echo ucfirst( (isset($bill_details['company_name'])?$bill_details['company_name']:""));?> ,</strong>
                                <br>
                                <?php echo (isset($bill_details['address'])?$bill_details['address']:"") ;?> ,
                                <br>
                                <?php if(  (isset($bill_details['country'])?$bill_details['country']:"")  != ""){
                            echo get_data(TBL_COUNTRY,array('id'=>(isset($bill_details['country'])?$bill_details['country']:"") ))->row()->country_name.' '.$bill_details['zip_code']; }else{
                                    echo (isset($bill_details['zip_code'])?$bill_details['zip_code']:"") ;
                                }?> ,
                                <br>
                                <a href="mailto:<?php echo (isset($bill_details['email_address'])?$bill_details['email_address']:'');?>">
                                    <?php echo (isset($bill_details['email_address'])?$bill_details['email_address']:'');?> ,</a>
                                <br>
                                <abbr title="Phone"></abbr>
                                <?php echo (isset($bill_details['mobile'])?$bill_details['mobile']:'') ;?> ,
                                <br>
                                <strong>VAT:</strong>
                                <?php echo (isset($bill_details['vat'])?$bill_details['vat']:'');?> .
                            </address>
                        </div>
                        <div class="col-xs-4 invoice-payment">
                            <h3>Client:</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <strong>Name:</strong>
                                    <?php 
                          if(unsecure($curr_cha_id)==0) { ?>
                                    <a href="javascript:;" id="inline_username" class="inline_username" data-type="text" data-name="guest_name" data-pk="<?php echo $reservation_id;?>" data-url="<?php echo lang_url()?>reservation/edit_guest_details" data-title="Change Name">
                                        <?php echo ucfirst($guest_name);
                          }
                          else {
                              echo ucfirst($guest_name);
                          }
                          ?> </a>
                                </li>
                                <li>
                                    <strong>Email:</strong>
                                    <?php 
                          if(unsecure($curr_cha_id)==0) { ?>
                                    <a href="javascript:;" id="inline_username" class="inline_username" data-type="email" data-name="email" data-pk="<?php echo $reservation_id;?>" data-url="<?php echo lang_url()?>reservation/edit_guest_details" data-title="Change Email">
                                        <?php echo ucfirst($email);?> </a>
                                    <?php
                          }
                          else
                          {
                              echo ucfirst($email);
                          }
                          ?>
                                </li>
                                <li>
                                    <strong>Address:</strong>
                                    <?php
                          if(unsecure($curr_cha_id)==0) {
                              ?>
                                        <a href="javascript:;" id="inline_username" class="inline_username" data-type="textarea" data-name="street_name" data-pk="<?php echo $reservation_id;?>" data-url="<?php echo lang_url()?>reservation/edit_guest_details" data-title="Change Address"><?php if($street_name!='' && $street_name!=0) { echo $street_name; } else { echo '<strong>N/A</strong>'; }?></a>
                                        <?php
                          }
                          else
                          {
                              echo $street_name;
                          }
                          ?>
                                </li>
                                <li>
                                    <strong>City:</strong>
                                    <?php
                          if(unsecure($curr_cha_id)==0) {
                              ?>
                                        <a href="javascript:;" id="inline_username" class="inline_username" data-type="text" data-name="city_name" data-pk="<?php echo $reservation_id;?>" data-url="<?php echo lang_url()?>reservation/edit_guest_details" data-title="Change City">
                                            <?php echo $city_name;?> </a>
                                        <?php
                          }
                          else
                          {
                              echo $city_name;
                          }
                          ?>
                                </li>
                                <li>
                                    <strong>Country:</strong>
                                    <?php
                          if(unsecure($curr_cha_id)==0) {
                              ?>
                                        <a href="javascript:;" id="inline_username" class="inline_username" data-source="<?php echo lang_url();?>reservation/invoice_all_country" data-type="select" data-name="country" data-pk="<?php echo $reservation_id;?>" data-value="<?php echo $country;?>" data-url="<?php echo lang_url()?>reservation/edit_guest_details" data-title="Change Country">
                                            <?php  if($country!='' && $country!=0 ){ echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name; } else { echo '<strong>N/A</strong>';}}else { echo $country; }?></a>
                                </li>
                                <!-- <li>
                          <strong>Phone</strong> <?php //echo $reservation_details['mobile'];?>
                        </li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="post" id="add_ex_in" action="<?php echo lang_url();?>reservation/add_ex_invoice">
                                <input type="hidden" name="curr_cha_id" value="<?php echo $curr_cha_id;?>" />
                                <input type="hidden" id="in_val" name="in_val" />
                                <input type="hidden" value="<?php echo insep_encode($reservation_id);?>" name="reservation_id" />
                                <input type="hidden" value="<?php echo $invoice_number;?>" name="invoice" />
                                <table class="table table-striped table-bordereds table-hover">
                                    <thead>
                                        <tr>
                                            <th width="2%">
                                                <input id="check_all" class="formcontrol hidden-print" type="checkbox" />
                                            </th>
                                            <th width="20%">Item</th>
                                            <th width="20%">Description</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text" data-type="productCode" name="itemNo[]" id="itemNo_1" class="form-control" autocomplete="off" value="Booking">
                                            </td>
                                            <td>
                                                <input type="text" data-type="productName" name="itemName[]" id="itemName_1" class="form-control" autocomplete="off" value="Reservation for <?php echo  date('M d,Y',strtotime(str_replace('/','-',$start_date))).'-'.date('M d,Y',strtotime(str_replace('/','-',$end_date)));?>">
                                            </td>
                                            <td>
                                                <input type="text" name="total[]" id="total_1" class="form-control totalLinePrice changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php if(unsecure($curr_cha_id) == " 8 " || unsecure($curr_cha_id) == "1 "){ echo $totalprice; }else{ echo $num_rooms*$price; }?>">
                                            </td>
                                        </tr>
                                        <?php
                        if($extra_count!='0')
                        {
                            $in=1;
                            $ex_amount='0';
                            foreach($extra as $ex_val)
                            {
                                $in++;
                                $ex_amount=$ex_amount + $ex_val->amount;
                                ?>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <input type="text" data-type="productCode" name="itemNo[]" id="itemNo_<?php echo $in; ?>" class="form-control" autocomplete="off" value="Extra">
                                                </td>
                                                <td>
                                                    <input type="text" data-type="productName" name="itemName[]" id="itemName_<?php echo $in; ?>" class="form-control" autocomplete="off" value="<?php echo $ex_val->description;?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="total[]" id="total_<?php echo $in; ?>" class="form-control totalLinePrice changesNo" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php echo $ex_val->amount;?>">
                                                </td>
                                            </tr>
                                            <?php
                            }
                        }
                        else
                        {
                            $ex_amount = 0;
                        }
                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-xs-12 col-sm-3 col-md-3 col-lg-3 hidden-print'>
                            <button class="btn btn-danger delete" type="button">- Delete</button>
                            <button class="btn btn-success addmore" type="button">+ Add More</button>
                        </div>
                        <div class='col-xs-12 col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-sm-5 col-md-5 col-lg-5 invoice-block'>
                            <form class="form-inline">
                                <div class="form-group">
                                    <label>Subtotal: &nbsp;</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <?php 
                            if(unsecure($curr_cha_id)==0)
                            {                           
                                if($currency!='' || $currency!=0) { echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; }
                            }
                            else
                            {
                                echo $cha_currency;
                            }
                            ?>
                                        </div>
                                        <input type="number" class="form-control" id="subTotal" placeholder="Subtotal" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php if(unsecure($curr_cha_id) != 8 &&  unsecure($curr_cha_id) !=1) { echo $sub_total = $ex_amount + $num_rooms * $price; }else{ echo $sub_total = $ex_amount + $totalprice;}?>">
                                    </div>
                                </div>
                                <!--<div class="form-group mar-top7 ">
                        <label>Tax: &nbsp;</label>
                        <div class="input-group">
                            <div class="input-group-addon"><?php //if($currency!='' || $currency!=0) { echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; };?></div>
                            <input type="number" class="form-control" id="tax" placeholder="Tax" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                    </div>-->
                                <?php if(isset($tax)) { ?>
                                <div class="form-group mar-top7">
                                    <label>Tax: &nbsp;</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="tax" placeholder="Tax" value="<?php echo $tax; ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group mar-top7">
                                    <label>Tax: &nbsp;</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="tax" placeholder="Tax" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group mar-top7">
                                    <label>Total: &nbsp;</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <?php 
                            if(unsecure($curr_cha_id)==0)
                            {
                                if($currency!='' || $currency!=0) { echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; }
                            }
                            else
                            {
                                echo $cha_currency;
                            }
                            ?>
                                        </div>
                                        <input type="number" class="form-control" id="totalAftertax" placeholder="Total" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php if(isset($tax)){ echo $price; }else { echo $sub_total; }?>">
                                    </div>
                                </div>
                                <!--<div class="form-group mar-top7">
                        <label>Amount Paid: &nbsp;</label>
                        <div class="input-group">
                            <div class="input-group-addon"><?php //if($currency!='' || $currency!=0) { echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; };?></div>
                            <input type="number" class="form-control" id="amountPaid" placeholder="Amount Paid" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;">
                        </div>
                    </div>-->
                                <div class="form-group mar-top7">
                                    <label>Amount Due: &nbsp;</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <?php 
                            if(unsecure($curr_cha_id)==0)
                            {
                                if($currency!='' || $currency!=0) 
                                {
                                    echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; 
                                }
                            }
                            else
                            {
                                echo $cha_currency;
                            }
                            ?>
                                        </div>
                                        <input type="number" class="form-control amountDue" id="amountDue" placeholder="Amount Due" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" value="<?php if(isset($tax)){ echo $price; }else { echo $sub_total; }?>">
                                    </div>
                                </div>
                                <div class="form-group mar-top7">
                                    <a onclick="javascript:window.print();" class="btn btn-lg blue hidden-print margin-bottom-5">
                      Print <i class="fa fa-print"></i>
                      </a>
                                    <a class="btn btn-lg green hidden-print margin-bottom-5 in_ext">
                      Submit Your Invoice <i class="fa fa-check"></i>
                      </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>