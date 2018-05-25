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

<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n">
<?php 
/*echo '<pre>';
print_r($bill);*/
if(isset($action)=='channel_subscribe') { ?>
<h4><a href="<?php echo lang_url();?>channel/all_channels">Channel</a>
    <i class="fa fa-angle-right"></i>
     <a href="<?php echo lang_url();?>channel/channel_subscribe">Channel Subscription</a><i class="fa fa-angle-right"></i>
     Billing
              </h4>
<?php } else { ?>
<h4><a href="<?php echo lang_url();?>inventory/advance_update">Calendar</a>
    <i class="fa fa-angle-right"></i>
    Overview

              </h4>
<?php } ?>
</div>
</div>
</div>
</div>
<br/>
<?php if(isset($pay_type)!='') { if((insep_decode($pay_type))=='1') { ?>

<form method="post" action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">
<input type="hidden" id="free_subscribe" value="free_subscribe" name="free_subscribe" />
<input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
<input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>

<div class="container">
<div class="row proper">
<div class="col-md-9 col-sm-9 center-block ftn">
<div class="bb1 sub-m">
<h3>Billing info</h3>
<hr>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="row">
<div class="col-md-4 col-sm-4">Trade name</div>
<div class="col-md-8 col-sm-8"><input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;}//$property_name?>" name="property_name"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Vat</div>
<div class="col-md-8 col-sm-8"><input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) {echo $bill->vat;}?>" name="vat"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Registration Number</div>
<div class="col-md-8 col-sm-8"><input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-4 col-sm-4">First name</div>
<div class="col-md-8 col-sm-8"><input id="fname" class="form-control" type="text" value="<?php //echo $fname;?>" name="fname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Last name</div>
<div class="col-md-8 col-sm-8"><input id="lname" class="form-control" type="text" value="<?php //echo $lname?>" name="lname"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">Phone</div>
<div class="col-md-8 col-sm-8"><input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">E-mail</div>
<div class="col-md-8 col-sm-8"><input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address"></div>
</div>
</div>
<div class="col-md-6 col-md-6">
<div class="row">
<div class="col-md-4 col-sm-4">Street Address</div>
<div class="col-md-8 col-sm-8"><input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}?>" name="address"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-8 col-sm-8 col-md-offset-4 col-sm-offset-4"><input id="address" class="form-control" type="text" placeholder="Street Address (cont'd)" value="<?php //echo $address;?>" name="address	"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">City</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo $bill->town;}?>" name="town"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Zip Code</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo $bill->zip_code; } }?>" name="zip_code"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Country</div>
<div class="col-md-8 col-sm-8"><?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;} else { echo "N/A";}?></div>
</div>

</div>
</div>

</div>
</div>
</div>
</div>

<br/>

<div class="container">
<div class="row">
<div class="col-md-9 col-sm-9 center-block ftn">
<div class="bb1 sub-m">
<h3>Subscriptions and services</h3>
<hr>
<?php if(isset($action)=='channel_subscribe') {?>
<p><span><b><?php echo $channel_type.' '.$channel_plan;?></b></span> <span class="pull-right"> $0</span></p>
<?php } else {  ?>
<p><span><b><?php echo $plan_types.' '.$plan_name;?></b></span> <span class="pull-right"> $0</span></p>
<?php } ?>
</div>
</div>
</div>
</div>

<br/>

<div class="container">
<div class="row">
<div class="col-md-9 col-sm-9 center-block ftn">
 <div class="checkbox">
    <label>
      <input type="checkbox" required value="1" name="subscribe_terms">  I Accept the <a href="<?php echo lang_url()?>our_links/terms">Terms and Conditions</a> and <a href="<?php echo lang_url();?>our_links/privacy">Privacy Terms</a>
    </label>
  </div>
  <div class="row">
<div class="col-md-12 col-md-12">
<button type="submit" class="btn btn-success col-md-12">SUBSCRIBE</button>
</div>
</div>
</div>
</div>
</div>

<br/>

</form>
 
<?php } else { ?>
<form method="post" action="<?php echo lang_url();?>inventory/Buy_Now" id="billing_form">
<input type="hidden" value="<?php echo $subscribe_channel_id;?>" name="subscribe_channel_id"/>
<input type="hidden" value="<?php echo $pay_type;?>" name="subscribe_plan_id"/>
<div class="container">
<div class="row proper">

<div class="col-md-8 col-sm-8 center-block">
<div class="bb1 sub-m">
<h3>Billing info</h3>
<hr>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="row">
<div class="col-md-4 col-sm-4">Trade name</div>
<div class="col-md-8 col-sm-8"><input id="property_name_b" class="form-control" type="text" value="<?php if(isset($bill->company_name)) { echo $bill->company_name;}//$property_name?>" name="property_name"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Vat</div>
<div class="col-md-8 col-sm-8"><input id="tax_office" class="form-control" type="text" value="<?php if(isset($bill->vat)) { echo $bill->vat;}?>" name="vat"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Registration Number</div>
<div class="col-md-8 col-sm-8"><input id="tax_id" class="form-control" type="text" value="<?php if(isset($bill->reg_num)) {echo $bill->reg_num;}?>" name="reg_num"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-4 col-sm-4">First name</div>
<div class="col-md-8 col-sm-8"><input id="fname" class="form-control" type="text" value="<?php //echo $fname;?>" name="fname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Last name</div>
<div class="col-md-8 col-sm-8"><input id="lname" class="form-control" type="text" value="<?php //echo $lname?>" name="lname"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">Phone</div>
<div class="col-md-8 col-sm-8"><input id="mobile_b" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>" name="mobile"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">E-mail</div>
<div class="col-md-8 col-sm-8"><input id="email_address_b" class="form-control" type="email" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" name="email_address"></div>
</div>
</div>
<div class="col-md-6 col-md-6">
<div class="row">
<div class="col-md-4 col-sm-4">Street Address</div>
<div class="col-md-8 col-sm-8"><input id="address" class="form-control" type="text" value="<?php if(isset($bill->address)) {echo $bill->address;}//$address;?>" name="address"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-8 col-sm-8 col-md-offset-4 col-sm-offset-4"><input id="address" class="form-control" type="text" placeholder="Street Address (cont'd)" value="<?php //echo $address;?>" name="address	"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">City</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if(isset($bill->town)) {echo  $bill->town;}//$town;?>" name="town"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Zip Code</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if(isset($bill->zip_code)) {if($bill->zip_code==0) { ?>NA <?php } else { ?> <?php echo$bill->zip_code; } }?>" name="zip_code"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Country</div>
<div class="col-md-8 col-sm-8"><?php if(isset($bill->country)) {echo @get_data(TBL_COUNTRY,array('id'=>$bill->country))->row()->country_name;}else { echo "N/A";}?></div>
</div>

</div>
</div>

</div>
</div>



<div class="col-md-4 col-sm-4">
<div style="">
              <div style="max-height: 605px;" class="bb1 sub-m">
                <h3>Charges</h3>
                <hr>
                <div class="marB20" id="dynamic-right-nav">    <div class="clearfix"></div>
    <div class="total-line marT0">
          <span class="title">
                <?php echo $plan_name;?>
          </span>
          <span class="total-line-value">
                <?php echo $symbol.$plan_price;?>
          </span>
    </div>


    <div class="clearfix"></div>
    <hr>
    <div class="total-line">
      <span class="title">Total</span>
      <span class="total-line-value"><?php echo $symbol.$plan_price;?></span>
    </div>
    <div class="clearfix"></div>
    <hr class="total-line-separator">
    <div class="grand-total">
      <span class="total-line-name marT5">Grand total</span>

        <span class="total-line-value">
              <strong><?php echo $symbol.$plan_price;?></strong>
        </span>
      <!--<p></p>-->
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="clearfix"></div>

</div>
                <div class="clearfix"></div>

                    <div id="main_promotion_container">
                      <!--<p style="max-height: 24px">
                        <input type="checkbox" value="1" style="margin-right: 3px;" name="has_promo_code" id="has_promo_code" data-target="#promotion_container" class="toggle_target_check">
                        <label style="font-weight: normal !important; display: inline;" for="has_promo_code">I have a coupon or promotion code.</label>
                      </p>-->

                      <!--<div style="display: none;" id="promotion_container">
                        <div class="promotionCodeDiv"></div>

                        <div class="coupon_code_container">
                          <input type="text" placeholder="Coupon code" name="coupon_code" id="coupon_code" class="form-control inline-input coupon_code">
                          <a id="apply-code" data-original-text="Apply" class="pull-right btn btn-primary btn-xs" href="javascript:;">Apply</a>
                        </div>
                        <div style="display: none;" class="loading marT5 marL5 pull-left">
                          <img src="//d2uyahi4tkntqv.cloudfront.net/assets/spinner-c9cceea148d28b3bca5db5cbdb6b9a1a.gif" alt="Spinner">
                        </div>
                        <div class="clearfix"></div>
                      </div>-->
                    </div>
                <div class="pay_button_container">
  <div class="marT5">
    <p style="max-height: 24px">
      <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required >
      <label style="font-weight: normal !important; display: inline;" for="order_terms_of_service">I Accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and Conditions</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy">Privacy Terms</a></label>
    </p>

    <div class="clearfix"></div>
    
    <br>
    <div class="row">
<div class="col-md-12 col-md-12">
<button class="btn btn-success col-md-12" type="submit">SUBSCRIBE</button>
</div>
</div>
  </div>
</div>
              </div>
            </div>
</div>


</div>
</div>

<br/>

<div class="container">
<div class="row proper">
<div style="" class="bb1 sub-m col-md-8 col-sm-8">
  <h3 class="pull-left">Payment</h3>
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
  <div class="clearfix"></div>
  <hr>

  





  <div id="paymentMethods" class="tabbable tabs-left">
    <ul id="paymentList" class="nav nav-tabs">
          <li id="935643660" class="active">
            <a data-toggle="tab" href="#ns-935643660">Credit Card</a>
          </li>
          <li id="935648652" class="">
            <a data-toggle="tab" href="#ns-935648652">PayPal</a>
          </li>
    </ul>

    <div class="tab-content">
          
			<div id="ns-935643660" class="tab-pane active card_hide">
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
          
          		<div id="ns-935648652" class="tab-pane  pay_hide">
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
</div>

</form>
<br/>

<?php } } ?>

<?php if(isset($cha_type)!='') { if(($cha_type)=='3') { ?>

<form method="post" action="<?php echo lang_url();?>channel/Buy_Now" id="billing_form">
<input type="hidden" id="free_subscribe" value="free_subscribe" name="free_subscribe" />
<input type="hidden" value="<?php echo $channel_ids?>" name="channel_id" id="channel_id" />
<div class="container">
<div class="row proper">
<div class="col-md-9 col-sm-9 center-block ftn">
<div class="bb1 sub-m">
<h3>Billing info</h3>
<hr>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="row">
<div class="col-md-4 col-sm-4">Trade name</div>
<div class="col-md-8 col-sm-8"><input id="property_name_b" class="form-control" type="text" value="<?php echo $property_name?>" name="property_name"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Tax office</div>
<div class="col-md-8 col-sm-8"><input id="tax_office" class="form-control" type="text" value="<?php echo $tax_office;?>" name="tax_office"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Tax ID</div>
<div class="col-md-8 col-sm-8"><input id="tax_id" class="form-control" type="text" value="<?php echo $tax_id;?>" name="tax_id"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">First name</div>
<div class="col-md-8 col-sm-8"><input id="fname" class="form-control" type="text" value="<?php echo $fname;?>" name="fname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Last name</div>
<div class="col-md-8 col-sm-8"><input id="lname" class="form-control" type="text" value="<?php echo $lname?>" name="lname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Phone</div>
<div class="col-md-8 col-sm-8"><input id="mobile_b" class="form-control" type="text" value="<?php echo $mobile; ?>" name="mobile"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">E-mail</div>
<div class="col-md-8 col-sm-8"><input id="email_address_b" class="form-control" type="email" value="<?php echo $email_address;?>" name="email_address"></div>
</div>
</div>
<div class="col-md-6 col-md-6">
<div class="row">
<div class="col-md-4 col-sm-4">Street Address</div>
<div class="col-md-8 col-sm-8"><input id="address" class="form-control" type="text" value="<?php echo $address;?>" name="address"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-8 col-sm-8 col-md-offset-4 col-sm-offset-4"><input id="address" class="form-control" type="text" placeholder="Street Address (cont'd)" value="<?php //echo $address;?>" name="address	"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">City</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php echo $town;?>" name="town"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Zip Code</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if($zip_code==0) { ?>NA <?php } else { ?> <?php echo $zip_code; } ?>" name="zip_code"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Country</div>
<div class="col-md-8 col-sm-8"><?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?></div>
</div>

</div>
</div>

</div>
</div>
</div>
</div>

<br/>

<div class="container">
<div class="row">
<div class="col-md-9 col-sm-9 center-block ftn">
<div class="bb1 sub-m">
<h3>Subscriptions and services</h3>
<hr>
<?php if(isset($action)=='channel_subscribe') {?>
<p><span><b><?php echo $channel_type.' '.$channel_plan;?></b></span> <span class="pull-right"> $0</span></p>
<?php } else {  ?>
<p><span><b><?php echo $plan_types.' '.$plan_name;?></b></span> <span class="pull-right"> $0</span></p>
<?php } ?>
</div>
</div>
</div>
</div>

<br/>

<div class="container">
<div class="row">
<div class="col-md-9 col-sm-9 center-block ftn">
 <div class="checkbox">
    <label>
      <input type="checkbox" required value="1" name="subscribe_terms">  I Accept the <a href="<?php echo lang_url()?>our_links/terms">Terms and Conditions</a> and <a href="<?php echo lang_url();?>our_links/privacy">Privacy Terms</a>
    </label>
  </div>
  <div class="row">
<div class="col-md-12 col-md-12">
<button type="submit" class="btn btn-success col-md-12">SUBSCRIBE</button>
</div>
</div>
</div>
</div>
</div>

<br/>

</form>
 
<?php } else { ?>
<form method="post" action="<?php echo lang_url();?>channel/Buy_Now" id="billing_form">
<input type="hidden" name="channel_id" value="<?php echo $channel_ids;?>" id="channel_id" />
<div class="container">
<div class="row proper">

<div class="col-md-8 col-sm-8 center-block">
<div class="bb1 sub-m">
<h3>Billing info</h3>
<hr>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="row">
<div class="col-md-4 col-sm-4">Trade name</div>
<div class="col-md-8 col-sm-8"><input id="property_name_b" class="form-control" type="text" value="<?php echo $property_name?>" name="property_name"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Tax office</div>
<div class="col-md-8 col-sm-8"><input id="tax_office" class="form-control" type="text" value="<?php echo $tax_office;?>" name="tax_office"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Tax ID</div>
<div class="col-md-8 col-sm-8"><input id="tax_id" class="form-control" type="text" value="<?php echo $tax_id;?>" name="tax_id"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">First name</div>
<div class="col-md-8 col-sm-8"><input id="fname" class="form-control" type="text" value="<?php echo $fname;?>" name="fname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Last name</div>
<div class="col-md-8 col-sm-8"><input id="lname" class="form-control" type="text" value="<?php echo $lname?>" name="lname"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Phone</div>
<div class="col-md-8 col-sm-8"><input id="mobile_b" class="form-control" type="text" value="<?php echo $mobile; ?>" name="mobile"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">E-mail</div>
<div class="col-md-8 col-sm-8"><input id="email_address_b" class="form-control" type="email" value="<?php echo $email_address;?>" name="email_address"></div>
</div>
</div>
<div class="col-md-6 col-md-6">
<div class="row">
<div class="col-md-4 col-sm-4">Street Address</div>
<div class="col-md-8 col-sm-8"><input id="address" class="form-control" type="text" value="<?php echo $address;?>" name="address"></div>
</div>
<br/>
<!--<div class="row">
<div class="col-md-8 col-sm-8 col-md-offset-4 col-sm-offset-4"><input id="address" class="form-control" type="text" placeholder="Street Address (cont'd)" value="<?php //echo $address;?>" name="address	"></div>
</div>
<br/>-->
<div class="row">
<div class="col-md-4 col-sm-4">City</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php echo $town;?>" name="town"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Zip Code</div>
<div class="col-md-8 col-sm-8"><input id="text" class="form-control" type="text" value="<?php if($zip_code==0) { ?>NA <?php } else { ?> <?php echo $zip_code; } ?>" name="zip_code"></div>
</div>
<br/>
<div class="row">
<div class="col-md-4 col-sm-4">Country</div>
<div class="col-md-8 col-sm-8"><?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?></div>
</div>

</div>
</div>

</div>
</div>



<div class="col-md-4 col-sm-4">
<div style="">
              <div style="max-height: 605px;" class="bb1 sub-m">
                <h3>Charges</h3>
                <hr>
                <div class="marB20" id="dynamic-right-nav">    <div class="clearfix"></div>
    <div class="total-line marT0">
          <span class="title">
                <?php echo $channel_plan;?>
          </span>
          <span class="total-line-value">
                <?php echo $symbol.$channel_price;?>
          </span>
    </div>


    <div class="clearfix"></div>
    <hr>
    <div class="total-line">
      <span class="title">Total</span>
      <span class="total-line-value"><?php echo $symbol.$channel_price;?></span>
    </div>
    <div class="clearfix"></div>
    <hr class="total-line-separator">
    <div class="grand-total">
      <span class="total-line-name marT5">Grand total</span>

        <span class="total-line-value">
              <strong><?php echo $symbol.$channel_price;?></strong>
        </span>
      <!--<p></p>-->
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="clearfix"></div>

</div>
                <div class="clearfix"></div>

                    <div id="main_promotion_container">
                    </div>
                <div class="pay_button_container">
  <div class="marT5">
    <p style="max-height: 24px">
      <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required >
      <label style="font-weight: normal !important; display: inline;" for="order_terms_of_service">I Accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and Conditions</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy">Privacy Terms</a></label>
    </p>

    <div class="clearfix"></div>
    
    <br>
    <div class="row">
<div class="col-md-12 col-md-12">
<button class="btn btn-success col-md-12" type="submit">SUBSCRIBE</button>
</div>
</div>
  </div>
</div>
              </div>
            </div>
</div>


</div>
</div>

<br/>

<div class="container">
<div class="row proper">
<div style="" class="bb1 sub-m col-md-8 col-sm-8">
  <h3 class="pull-left">Payment</h3>
  <?php if($card_count!=0)
		  {
			  ?>
  			<div class="pull-right ui-select card_hide" id="card_options">
	       <select name="use_existing_card" id="use_existing_card" data-payment-method-id="935643660" data-currency="USD" class="select2 existing_card_selector form-control">						 			<option value="true">Saved credit cards</option>
			<option value="false">Another payment method</option>
            </select>
      </div>
	  <?php } ?>
  <div class="clearfix"></div>
  <hr>

  





  <div id="paymentMethods" class="tabbable tabs-left">
    <ul id="paymentList" class="nav nav-tabs">
          <li id="935643660" class="active">
            <a data-toggle="tab" href="#ns-935643660">Credit Card</a>
          </li>
              
          <!--<li hr-method="true" payment-id="935644527" id="935644527" name="order[payments_attributes][][payment_method_id]" rel="pm_tab1" data-currency="USD" class="">
            <a data-toggle="tab" href="#ns-935644527">HR Funds</a>
          </li>-->
          <li id="935648652" class="">
            <a data-toggle="tab" href="#ns-935648652">PayPal</a>
          </li>
    </ul>

    <div class="tab-content">
          
          <div id="ns-935643660" class="tab-pane active card_hide">
          <?php if($card_count==0)
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
			for($i=$curr_year; $i<=$end_year; $i++) { ?>
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
			for($i=$curr_year; $i<=$end_year; $i++) { ?>
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
                  <?php echo $c_fname.' '.$c_lname; ?>
                </label>
              </td>
              <td style="min-width: 100px;">
                <label for="existing_card_1051">
                  <span class="end_card_number"> ending in <?php echo $last3chars = substr($card_number, -4);  ?></span>
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

          <div id="ns-935644527" class="tab-pane">
            <div class="row">
  <div class="text-center">
    <h1>
      <i class="fa fa-money"></i>
    </h1>

    <h3 class="">
          You don't have enough funds
    </h3>

    <p>$0</p>
        <a onclick="window.top.location.href='https://viji-hotel.hotelrunner.com/admin/account/store/credit/balance?lc=1';" class="btn btn-primary" href="javascript:;">Deposit funds</a>
  </div>
</div>

          </div>
          
          <div id="ns-935648652" class="tab-pane pay_hide">
          <input type="hidden" name="pay_paypal"  id="pay_paypal"/>
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
</div>

</form>
<br/>

<?php } }?>