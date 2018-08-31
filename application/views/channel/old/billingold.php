<style>
form #card_number {
background-image: url(../images.png), url(../images.png);
  background-position: 2px -121px, 260px -61px;
  background-size: 120px 361px, 120px 361px;
  background-repeat: no-repeat;
  padding-left: 54px;
  /*width: 225px;*/
}
form #card_number.visa {
  background-position: 2px -163px, 260px -61px;
}
form #card_number.visa_electron {
  background-position: 2px -205px, 260px -61px;
}
form #card_number.mastercard {
  background-position: 2px -247px, 260px -61px;
}
form #card_number.maestro {
  background-position: 2px -289px, 260px -61px;
}
form #card_number.discover {
  background-position: 2px -331px, 260px -61px;
}
form #card_number.valid.visa {
  background-position: 2px -163px, 260px -87px;
}
form #card_number.valid.visa_electron {
  background-position: 2px -205px, 260px -87px;
}
form #card_number.valid.mastercard {
  background-position: 2px -247px, 260px -87px;
}
form #card_number.valid.maestro {
  background-position: 2px -289px, 260px -87px;
}
form #card_number.valid.discover {
  background-position: 2px -331px, 260px -87px;
}
.vertical {
  overflow: hidden;
}
</style>

<

<!-- <div class="col-md-8 col-sm-7 col-xs-12 cls_cmpilrigh"> -->

<?php if(isset($pay_type)!='') { if((insep_decode($pay_type))=='1') { ?>  


  <div class="col-md-8 col-sm-7 col-xs-12 cls_cmpilrigh">

  <form method="post" class="form-horizontal mar_top_det " action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">

   
<input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
<input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>
<input type="hidden" id="free_subscribe" value="<?= $plan_types?>" name="free_subscribe" />

    <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Trade name</label>
     <input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;}//$property_name?>" name="property_name">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Vat</label>
     <input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) {echo $bill->vat;}?>" name="vat">
    </div>
    </div>
  </div>    
    
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Registration Number</label>
     <input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone</label>
     <input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">E-mail</label>
     <input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
    <label class="">Street Address</label>
     <input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}?>" name="address">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">City</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo $bill->town;}?>" name="town">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Zip Code</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo $bill->zip_code; } }?>" name="zip_code">
    </div>
    </div>
    </div>  
    
     <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">Country : <?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;} else { echo "N/A";}?></label>
   
    </div>
    </div>
    </div>  
    </div>
  
    </form>

    <?php } else{ ?>

    <form method="post" class="form-horizontal mar_top_det " action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">

    <div class="col-md-8 col-sm-7 col-xs-12 cls_cmpilrigh">

    <input type="hidden" id="free_subscribe" value="<?= $plan_types?>" name="free_subscribe" />
    <input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
    <input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>


    <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Trade name</label>
     <input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;}//$property_name?>" name="property_name">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Vat</label>
     <input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) {echo $bill->vat;}?>" name="vat">
    </div>
    </div>
  </div>    
    
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Registration Number</label>
     <input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone</label>
     <input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">E-mail</label>
     <input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
    <label class="">Street Address</label>
     <input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}?>" name="address">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">City</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo $bill->town;}?>" name="town">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Zip Code</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo $bill->zip_code; } }?>" name="zip_code">
    </div>
    </div>
    </div>  
    
     <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">Country : <?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;} else { echo "N/A";}?></label>
    
    </div>
    </div>
    </div> 



     <div class="verify_det mar_top_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>
      Payment
    </h4>

    <?php if($card_count!=0)
      {
        ?>
      <div class="pull-right ui-select card_hide" id="card_options">
         <select name="use_existing_card" id="use_existing_card" data-payment-method-id="935643660" data-currency="USD" class="select2 existing_card_selector form-control">
      <option value="true">Saved credit cards</option>
      <option value="false">Another payment method</option>
            </select>
      </div>
    <?php } ?>

  </div> 

      <div class="col-md-12 col-sm-4 col-xs-12 cls_resp50 mar_top_det pad-no">
        <div class="cls_side_bar">
          <ul class="list-unstyled clearfix cls_menu_side">
            <li class="active"><a href="#tab1" data-toggle="tab" role="tab">  Credit Card </a> </li>
            <li><a href="#tab2" data-toggle="tab" role="tab">  paypal </a></li>
          </ul>
          
        </div>
      </div>

      <div class="col-md-12 col-sm-8 col-xs-12 cls_resp50 cls_triple pad-no">
        <div class="tab-content cls-exchng-tab-cont">


          <div id="tab1" class="tab-pane fade in active w3-animate-right" role="tabpanel">            
          <div class="clk_history dep_his pay_tab_clr">


          <?php
        //  if($card_count==0)
          if(0==0)
          {
          ?>
          <div id="new_cards" class="form-wrapper col-md-9">
          <input type="hidden" name="payment_card" id="payment_card" />
          <div class="row">
          <div class="col-md-4 col-sm-4">First Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text" class="form-control" id="c_fname" name="c_fname"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Last Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control" id="c_lname" name="c_lname"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Card number</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12"><input type="text" class="form-control" id="card_number" name="card_number"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12">
          <input type="password" class="form-control" id="cvv" name="cvv"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Expiration</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-4">
          <select name="month" id="year" class="form-control">
          <?php 
          $curr_mn = date('m');
          for($i=1; $i<=12; $i++) { ?>
          <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select>  
          </div>
          <div class="col-md-4">

          <select name="year" id="year" class="form-control">
          <?php 
          $curr_year = date('Y');
          $end_year = date("Y", strtotime("+15 years"));
          for($i=$curr_year; $i<=$end_year; $i++) {?>
          <option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select> 
          </div>
          </div>
          </div>

          </div>
          <?php } else { ?>

          <div id="new_card" class="form-wrapper col-md-9 display_none">
          <input type="hidden" name="payment_card" id="payment_card" />
          <div class="row">
          <div class="col-md-4 col-sm-4">First Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text" class="form-control" id="c_fname" name="c_fname"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Last Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control" id="c_lname" name="c_lname"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Card number</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12"><input type="text" class="form-control" id="card_number" name="card_number"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12">
          <input type="password" class="form-control" id="cvv" name="cvv"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Expiration</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-4">
          <select name="month" id="year" class="form-control">
          <?php 
          $curr_mn = date('m');
          for($i=1; $i<=12; $i++) { ?>
          <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select>  
          </div>
          <div class="col-md-4">

          <select name="year" id="year" class="form-control">
          <?php 
          $curr_year = date('Y');
          $end_year = date("Y", strtotime("+15 years"));
          for($i=$curr_year; $i<=$end_year; $i++) {?>
          <option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select> 
          </div>
          </div>
          </div>

          </div>

          <div class="col-md-9" id="existing_card_fields" style="display: block;">
          <table class="table table-hover marB5">
          <tbody>
          <?php 
          $i=0;
          foreach($cards as $card) { 
          $i++;
          extract($card);?>
          <tr id="spree_creditcard_1051">

          <td style="min-width: 100px;">
          <input type="radio" value="<?php echo $id;?>" name="existing_card" id="existing_card_<?php echo $id;?>" <?php  if($i==1) { ?> checked="checked" <?php } ?> />  
          <label class="marL5" for="existing_card_1051">
          <?php echo safe_b64decode($c_fname).' '.safe_b64decode($c_lname); ?>
          </label>
          </td>
          <td style="min-width: 100px;">
          <label for="existing_card_1051">
          <span class="end_card_number"> ending in <?php echo $last3chars = substr(safe_b64decode($card_number), -4);  ?></span>
          </label>
          </td>
          <td style="min-width: 36px;">
          <label for="existing_card_1051">
          <img src="//d2uyahi4tkntqv.cloudfront.net/assets/creditcards/icons/master-9f945a9733126eeb4f12a592830ae2eb.png" class="card_image" alt="Master">
          </label>
          </td>
          </tr>
          <?php } ?>
          </tbody>
          </table>
          </div>
          <?php } ?>
          </div>


          </div>


          <div id="tab2" class="tab-pane fade w3-animate-right" role="tabpanel">            
          <div class="clk_history dep_his pay_tab_clr">        

          <input type="hidden" name="pay_paypal"  id="pay_paypal" value="1"/>
          <div class="row">
          <div style="padding: 15px;" class="text-center">
          <img width="150" src="//d2uyahi4tkntqv.cloudfront.net/assets/paypal-5f6928dea999eac2a0a4cb2bff07f87c.png" class="paypal_image " alt="Paypal">
          <p class="marT20">
          PayPal lets you send payments quickly and securely online using a credit card or bank account.
          </p>
          </div>
          </div>
          </div>
          </div> 
           </div> 
            </div> 
            </div>

             <div class="col-md-4 col-sm-5 col-xs-12 cls_cmpilef">
    <div class="cls_prin50 back_time clearfix">
    <ul class="list-unstyled">
    <li><strong><?php echo $plan_name;?>:</strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Total :  </strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Grand Total :  </strong><?php echo $symbol.$plan_price;?></li>       
    </ul>
    <div class="accept">
    <div class="cls_bulk_checkbox">
    <!-- <input id="checkbox8" class="styled" type="checkbox"> -->

    <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required  class="styled">

    <label for="order_terms_of_service"> I accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and condition</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy"> Privacy Terms</a> </label>
    </div>
    </div>
    <button class="btn btn_subscribe" type="submit">subscribe</button>
    </div>
    </div>
    
    </form>




    <?php } } ?>
    
    <?php if(isset($cha_type)!='') { if(($cha_type)=='3') { ?>
   
        <form method="post" class="form-horizontal mar_top_det " action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">

        <div class="col-md-8 col-sm-7 col-xs-12 cls_cmpilrigh">

    <input type="hidden" id="free_subscribe" value="<?= $plan_types?>" name="free_subscribe" />
    <input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
    <input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>


    <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Trade name</label>
     <input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;}//$property_name?>" name="property_name">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Vat</label>
     <input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) {echo $bill->vat;}?>" name="vat">
    </div>
    </div>
  </div>    
    
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Registration Number</label>
     <input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone</label>
     <input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">E-mail</label>
     <input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
    <label class="">Street Address</label>
     <input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}?>" name="address">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">City</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo $bill->town;}?>" name="town">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Zip Code</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo $bill->zip_code; } }?>" name="zip_code">
    </div>
    </div>
    </div>  
    
     <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">Country : <?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;} else { echo "N/A";}?></label>
    
    </div>
    </div>
    </div>  
    </div>




 <div class="col-md-4 col-sm-5 col-xs-12 cls_cmpilef">
    <div class="cls_prin50 back_time clearfix">
    <ul class="list-unstyled">
    <li><strong><?php echo $plan_name;?>:</strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Total :  </strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Grand Total :  </strong><?php echo $symbol.$plan_price;?></li>       
    </ul>
    <div class="accept">
    <div class="cls_bulk_checkbox">
    <!-- <input id="checkbox8" class="styled" type="checkbox"> -->

    <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required  class="styled">

    <label for="checkbox8"> I accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and condition</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy"> Privacy Terms</a> </label>
    </div>
    </div>
    <button class="btn btn_subscribe" type="submit">subscribe</button>
    </div>
    </div>

    </form>

    <?php } else{ ?>  


    <form method="post" class="form-horizontal mar_top_det " action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">

    <div class="col-md-8 col-sm-7 col-xs-12 cls_cmpilrigh">

    <input type="hidden" id="free_subscribe" value="<?= $plan_types?>" name="free_subscribe" />
    <input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
    <input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>


    <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Trade name</label>
     <input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;}//$property_name?>" name="property_name">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Vat</label>
     <input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) {echo $bill->vat;}?>" name="vat">
    </div>
    </div>
  </div>    
    
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Registration Number</label>
     <input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone</label>
     <input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">E-mail</label>
     <input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
    <label class="">Street Address</label>
     <input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}?>" name="address">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">City</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo $bill->town;}?>" name="town">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Zip Code</label>
     <input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo $bill->zip_code; } }?>" name="zip_code">
    </div>
    </div>
    </div>  
    
     <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
     <div class="form_type_list">
    <label class="">Country : <?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;} else { echo "N/A";}?></label>
    
    </div>
    </div>
    </div> 


      <div class="verify_det mar_top_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>
      Payment
    </h4>

    <?php if($card_count!=0)
      {
        ?>
      <div class="pull-right ui-select card_hide" id="card_options">
         <select name="use_existing_card" id="use_existing_card" data-payment-method-id="935643660" data-currency="USD" class="select2 existing_card_selector form-control">
      <option value="true">Saved credit cards</option>
      <option value="false">Another payment method</option>
            </select>
      </div>
    <?php } ?>

  </div>  
      <div class="col-md-12 col-sm-4 col-xs-12 cls_resp50 mar_top_det pad-no">
        <div class="cls_side_bar">
          <ul class="list-unstyled clearfix cls_menu_side">
            <li class="active"><a href="#tab1" data-toggle="tab" role="tab">  Credit Card </a> </li>
            <li><a href="#tab2" data-toggle="tab" role="tab">  paypal </a></li>
          </ul>
          
        </div>
      </div>
      <div class="col-md-12 col-sm-8 col-xs-12 cls_resp50 cls_triple pad-no">
        <div class="tab-content cls-exchng-tab-cont">


          <div id="tab1" class="tab-pane fade in active w3-animate-right" role="tabpanel">            
          <div class="clk_history dep_his pay_tab_clr">


          <?php
          if($card_count==0)
          {
          ?>
          <div id="new_cards" class="form-wrapper col-md-9">
          <input type="hidden" name="payment_card" id="payment_card" />
          <div class="row">
          <div class="col-md-4 col-sm-4">First Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text" class="form-control" id="c_fname" name="c_fname"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Last Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control" id="c_lname" name="c_lname"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Card number</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12"><input type="text" class="form-control" id="card_number" name="card_number"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12">
          <input type="password" class="form-control" id="cvv" name="cvv"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Expiration</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-4">
          <select name="month" id="year" class="form-control">
          <?php 
          $curr_mn = date('m');
          for($i=1; $i<=12; $i++) { ?>
          <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select>  
          </div>
          <div class="col-md-4">

          <select name="year" id="year" class="form-control">
          <?php 
          $curr_year = date('Y');
          $end_year = date("Y", strtotime("+15 years"));
          for($i=$curr_year; $i<=$end_year; $i++) {?>
          <option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select> 
          </div>
          </div>
          </div>

          </div>
          <?php } else { ?>

          <div id="new_card" class="form-wrapper col-md-9 display_none">
          <input type="hidden" name="payment_card" id="payment_card" />
          <div class="row">
          <div class="col-md-4 col-sm-4">First Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text" class="form-control" id="c_fname" name="c_fname"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Last Name</div>
          <div class="col-md-8 col-sm-8"><div class="col-md-12"><input type="text"  class="form-control" id="c_lname" name="c_lname"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Card number</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12"><input type="text" class="form-control" id="card_number" name="card_number"></div></div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">CVV2 / Card Code</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-12">
          <input type="password" class="form-control" id="cvv" name="cvv"></div>
          </div>
          </div>

          <br>

          <div class="row">
          <div class="col-md-4 col-sm-4">Expiration</div>
          <div class="col-md-8 col-sm-8">
          <div class="col-md-4">
          <select name="month" id="year" class="form-control">
          <?php 
          $curr_mn = date('m');
          for($i=1; $i<=12; $i++) { ?>
          <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select>  
          </div>
          <div class="col-md-4">

          <select name="year" id="year" class="form-control">
          <?php 
          $curr_year = date('Y');
          $end_year = date("Y", strtotime("+15 years"));
          for($i=$curr_year; $i<=$end_year; $i++) {?>
          <option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
          <?php } ?>
          </select> 
          </div>
          </div>
          </div>

          </div>

          <div class="col-md-9" id="existing_card_fields" style="display: block;">
          <table class="table table-hover marB5">
          <tbody>
          <?php 
          $i=0;
          foreach($cards as $card) { 
          $i++;
          extract($card);?>
          <tr id="spree_creditcard_1051">

          <td style="min-width: 100px;">
          <input type="radio" value="<?php echo $id;?>" name="existing_card" id="existing_card_<?php echo $id;?>" <?php  if($i==1) { ?> checked="checked" <?php } ?> />  
          <label class="marL5" for="existing_card_1051">
          <?php echo safe_b64decode($c_fname).' '.safe_b64decode($c_lname); ?>
          </label>
          </td>
          <td style="min-width: 100px;">
          <label for="existing_card_1051">
          <span class="end_card_number"> ending in <?php echo $last3chars = substr(safe_b64decode($card_number), -4);  ?></span>
          </label>
          </td>
          <td style="min-width: 36px;">
          <label for="existing_card_1051">
          <img src="//d2uyahi4tkntqv.cloudfront.net/assets/creditcards/icons/master-9f945a9733126eeb4f12a592830ae2eb.png" class="card_image" alt="Master">
          </label>
          </td>
          </tr>
          <?php } ?>
          </tbody>
          </table>
          </div>
          <?php } ?>
          </div>


          </div>


          <div id="tab2" class="tab-pane fade w3-animate-right" role="tabpanel">            
          <div class="clk_history dep_his pay_tab_clr">        

          <input type="hidden" name="pay_paypal"  id="pay_paypal" value=""/>
          <div class="row">
          <div style="padding: 15px;" class="text-center">
          <img width="150" src="//d2uyahi4tkntqv.cloudfront.net/assets/paypal-5f6928dea999eac2a0a4cb2bff07f87c.png" class="paypal_image " alt="Paypal">
          <p class="marT20">
          PayPal lets you send payments quickly and securely online using a credit card or bank account.
          </p>
          </div>
          </div>
          </div>
          </div>  
           </div>  
            </div>  

            </div>

            <div class="col-md-4 col-sm-5 col-xs-12 cls_cmpilef">
    <div class="cls_prin50 back_time clearfix">
    <ul class="list-unstyled">
    <li><strong><?php echo $plan_name;?>:</strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Total :  </strong><?php echo $symbol.$plan_price;?></li>
    <li><strong>Grand Total :  </strong><?php echo $symbol.$plan_price;?></li>       
    </ul>
    <div class="accept">
    <div class="cls_bulk_checkbox">
    <!-- <input id="checkbox8" class="styled" type="checkbox"> -->

    <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required  class="styled">

    <label for="checkbox8"> I accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and condition</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy"> Privacy Terms</a> </label>
    </div>
    </div>
    <button class="btn btn_subscribe" type="submit">subscribe</button>
    </div>
    </div>
    
    </form>




    <?php } } ?>
          
         
          
        </div>


         

      </div>


     

    </div>
  
   


  </div>

</div>
  </div>
   <?php $this->load->view('channel/dash_sidebar'); ?>


