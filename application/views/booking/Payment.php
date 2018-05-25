<?php
	$booking['background_type'] = (isset($booking['background_type'])) ? $booking['background_type'] : '0';
	$booking['background'] = (isset($booking['background'])) ? $booking['background'] : 'ffffff';

	if($booking['background_type'] == '0'){
		$booking['background'] = '#'.$booking['background'];
	}else{
		$booking['background'] = 'url('.base_url('uploads/'.$booking['background']).')';
	};
?>
<style type="text/css">
	body{
		background: <?= $booking['background']; ?>;
		background-size: cover;
	}

	.container{
		background: white;
		height: auto;
	}

	.head{
		background: #<?= (isset($booking['header_color'])) ? $booking['header_color'] : '' ?>;
		color: white;
		height: 60px; 
		padding: 3px;
		padding-left: 10px;
	}
	.logo{
		width: auto;
	}
	.form-search{
		margin-top: 10px;
	}

	/*.form-control{
		width: auto;
		padding-right: 5px;
		padding-left: 5px;
	}*/


  
    #slideShowImages img { /* The following CSS rules are optional. */
      border: 0.8em black solid;
      padding: 3px;
    }  
</style>


	

											

<div class="container" style="height: 30px;	background-color: #E5E3E2">
	
</div>
<div class="container" style="padding: 40px">
	<div class="logo col-sm-12 col-md-2">
		<img <?= (isset($booking['logo'])) ? 'src="'.base_url('uploads/'.$booking['logo']).'"' : '' ?> width="120" height="100">
	</div>
	<div class="logo col-sm-12 col-md-2">
		<h5><?= (isset($property['property_name'])) ? $property['property_name'] : '--' ?> || <?= ($hotel['property_name']) ? $hotel['property_name'] : '--' ?></h5>
		<h5><?= (isset($hotel['address'])) ? $hotel['address'] : '--' ?></h5>
		<h5><span class="fa fa-phone"></span> <?= (isset($hotel['mobile'])) ? $hotel['mobile'] : '--' ?></h5>
		<h5><span class="fa fa-envelope-o"></span> <?= (isset($hotel['email_address'])) ? $hotel['email_address'] : '--' ?></h5>
		<h5><span class="fa fa-globe"></span> <?= (isset($hotel['web_site'])) ? $hotel['web_site'] : '--' ?></h5>
	</div>
	<div class="col-sm-6 pull-right hidden-xs hidden-sm" style="margin-top: 15px;">
		<i style="font-family: times, serif; font-size:30pt; font-style:italic" color="lime">Checkout</i>
	</div>
</div>

<div class="head container">
	
</div>
<form id="Pagos" method ="post" action=" <?php echo lang_url();?>booking/save_reservation" >
<div class="container" id="Payment">
		<div id="payment-methods-ctr" class="top-lvl-module" data-module-id="4065"><h2 class="section-title">Pay with</h2><div class="pmt-mthds module" >
        <legend>Select a payment option</legend>  

            <fieldset> 
            <input type="hidden" name="name" value="<?= $Info['name'] ?>" >
			<input type="hidden" name="phone" value="<?= $Info['phone'] ?>" >
			<input type="hidden" name="email" value="<?= $Info['email'] ?>" >
			<input type="hidden" name="street_name" value="<?= $Info['street_name'] ?>" >
			<input type="hidden" name="city_name" value="<?= $Info['city_name'] ?>" >
			<input type="hidden" name="province" value="<?= $Info['province'] ?>" >
			<input type="hidden" name="country" value="<?= $Info['country'] ?>" >
			<input type="hidden" name="zipcode" value="<?= $Info['zipcode'] ?>" >
			<input type="hidden" name="arrivaltime" value="<?= $Info['arrivaltime'] ?>" >
			<input type="hidden" name="notes" value="<?= $Info['notes'] ?>" >
			<input type="hidden" name="roomid" value="<?= $Info['roomid'] ?>" >
			<input type="hidden" name="rateid" value="<?= $Info['rateid'] ?>" >
			<input type="hidden" name="hotelid" value="<?= $Info['hotelid'] ?>" >
			<input type="hidden" name="night" value="<?= $Info['night'] ?>" >
			<input type="hidden" name="date1" value="<?= $Info['date1'] ?>" >
			<input type="hidden" name="date2" value="<?= $Info['date2'] ?>" >
			<input type="hidden" name="guests" value= "<?= $Info['guests'] ?>" >
			<input type="hidden" name="children" value="<?= $Info['children'] ?>"  >
			<input type="hidden" name="amount" value="<?= $Info['amount'] ?>"  >
			<input type="hidden" name="detailsprice" value="<?= $Info['detailsprice'] ?>"  >
			<input type="hidden" name="extrasid" value="<?= $extrasid ?>"  >
			<input type="hidden" name="extrasmontos" value="<?= $extrasmontos?>"  >



            <label>
            <input type="radio" id="payment_type" name="payment_type"  value="paypal" onclick=" return showpaypal()"> <img src="http://www.puromarketing.com/img/images/2014-04-30/20140430183833.jpg" width="200px" height="50px" />
            </label>
            <div class="container" id="paypal" style="display: none">

        <table width="100%" class="table" >
	<tr>
	<td  width="50%">	
        



        	</td>
  	<td width="50%" align="left">
		 <h4><b> Details </b></h4>
			<table class="table" style="border: hidden">
				
				<tr > 
					<td align="right" style="border: hidden"> Check In:</td>	
					<td style="border: hidden" ><?=$Info['date1']?></td>
				</tr> 
				<tr >
					<td align="right" style="border: hidden"> Check Out:</td>	
					<td style="border: hidden" ><?=$Info['date2']?></td>
				</tr> 
				<tr> 			
					<td align="right" style="border: hidden">  <?= ($Info['night']==1?"Night:":"Nights:")?></td>
					<td style="border: hidden"> <?=$Info['night']?></td>
				</tr>
				<tr> 			
					<td align="right" style="border: hidden"> Total Due:</td>
					<td style="border: hidden"> <?=$totaldue?></td>
				</tr>

				<tr>
					<td align="center">
						<!DOCTYPE html>

							<head>
							    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
							    <meta name="viewport" content="width=device-width, initial-scale=1">
							    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
							</head>

							<body>
							    <div id="paypal-button-container"></div>

							    <script>
							        paypal.Button.render({

							            env: 'sandbox', // sandbox | production
							            locale: 'en_US',
							            // PayPal Client IDs - replace with your own
							            // Create a PayPal app: https://developer.paypal.com/developer/applications/create
							            client: {
							                sandbox:    'AdBQuq3Z_hvOF2jei1DEtsiPHnO_cYsKCbJ3YXNP5A7h27muV8ufBN9CilUKwnKl2F-7qJFXNV5W3JzB',
							                production: '<insert production client id>'
							            },

							            // Show the buyer a 'Pay Now' button in the checkout flow
							            commit: true,
							                style: {	
											            label: 'pay',
											            fundingicons: true, // optional
											            branding: true, // optional
											            size:  'small', // small | medium | large | responsive
											            shape: 'rect',   // pill | rect
											            color: 'blue'   // gold | blue | silve | black
											        },
							            // payment() is called when the button is clicked
							            payment: function(data, actions) {

							                // Make a call to the REST api to create the payment
							                return actions.payment.create({
							                    payment: {
							                        transactions: [
							                            {
							                                amount: { total: <?=$totaldue ?>, currency: 'USD' }
							                            }
							                        ]
							                    }
							                });
							            },

							            // onAuthorize() is called when the buyer approves the payment
							            onAuthorize: function(data, actions) {

							                // Make a call to the REST api to execute the payment
							                return actions.payment.execute().then(function() {
							                    window.alert('Payment Complete!');
							                });
							            }

							        }, '#paypal-button-container');

							    </script>
							</body>
    
					</td>
				</tr>
			</table>
		

  	</td>
</tr>
</table>
  
		</div>
            <br> <br>
            <label>
            <input type="radio" id="payment_type" name="payment_type" value="braintree" onclick=" return showbraintree()"> <img src="http://fintechnews.sg/wp-content/uploads/2016/06/braintreepayments-1440x564_c.png" width="200px" height="50px" />
            </label>


<div class="container" id="braintree" style="display: none">
<table width="100%" class="table" >
	<tr>
	<td  width="50%">	
        <h4>Braintree</h4>
        	</td>
  	<td width="50%" align="left">
		 <h4><b> Details </b></h4>
			<table class="table" style="border: hidden">
				
				<tr > 
					<td align="right" style="border: hidden"> Check In:</td>	
					<td style="border: hidden" ><?=$Info['date1']?></td>
				</tr> 
				<tr >
					<td align="right" style="border: hidden"> Check Out:</td>	
					<td style="border: hidden" ><?=$Info['date2']?></td>
				</tr> 
				<tr> 			
					<td align="right" style="border: hidden">  <?= ($Info['night']==1?"Night:":"Nights:")?></td>
					<td style="border: hidden"> <?=$Info['night']?></td>
				</tr>
				<tr> 			
					<td align="right" style="border: hidden"> Total Due:</td>
					<td style="border: hidden"> <?=$totaldue?></td>
				</tr>

				<tr>
					<td align="center">

						<!DOCTYPE html>

							<head>
							    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
							    <meta name="viewport" content="width=device-width, initial-scale=1">
							    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
							    <script src="https://js.braintreegateway.com/web/3.11.0/js/client.min.js"></script>
							    <script src="https://js.braintreegateway.com/web/3.11.0/js/paypal-checkout.min.js"></script>
							</head>

							<body>
							    <div id="Braintree-button-container"></div>

							    <script>

							        var BRAINTREE_SANDBOX_AUTH = 'eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiJjMDFhZmRkM2Y1OTJmNWVhNTNlMzE5MWQwYmIyMWVjYjM5NzNlZGM1MzkwNDZiMjJmNTA2ODEyNzIzZmRlMTJifGNsaWVudF9pZD1jbGllbnRfaWQkc2FuZGJveCQ0ZHByYmZjNnBoNTk1Y2NqXHUwMDI2Y3JlYXRlZF9hdD0yMDE3LTA0LTI2VDIzOjI2OjU5Ljg3OTA3ODYwNiswMDAwXHUwMDI2bWVyY2hhbnRfaWQ9M3cydHR2d2QyNDY1NDhoZCIsImNvbmZpZ1VybCI6Imh0dHBzOi8vYXBpLnNhbmRib3guYnJhaW50cmVlZ2F0ZXdheS5jb206NDQzL21lcmNoYW50cy8zdzJ0dHZ3ZDI0NjU0OGhkL2NsaWVudF9hcGkvdjEvY29uZmlndXJhdGlvbiIsImNoYWxsZW5nZXMiOltdLCJlbnZpcm9ubWVudCI6InNhbmRib3giLCJjbGllbnRBcGlVcmwiOiJodHRwczovL2FwaS5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tOjQ0My9tZXJjaGFudHMvM3cydHR2d2QyNDY1NDhoZC9jbGllbnRfYXBpIiwiYXNzZXRzVXJsIjoiaHR0cHM6Ly9hc3NldHMuYnJhaW50cmVlZ2F0ZXdheS5jb20iLCJhdXRoVXJsIjoiaHR0cHM6Ly9hdXRoLnZlbm1vLnNhbmRib3guYnJhaW50cmVlZ2F0ZXdheS5jb20iLCJhbmFseXRpY3MiOnsidXJsIjoiaHR0cHM6Ly9jbGllbnQtYW5hbHl0aWNzLnNhbmRib3guYnJhaW50cmVlZ2F0ZXdheS5jb20vM3cydHR2d2QyNDY1NDhoZCJ9LCJ0aHJlZURTZWN1cmVFbmFibGVkIjpmYWxzZSwicGF5cGFsRW5hYmxlZCI6dHJ1ZSwicGF5cGFsIjp7ImRpc3BsYXlOYW1lIjoiYmFyY28uMDMtZmFjaWxpdGF0b3JAZ21haWwuY29tIiwiY2xpZW50SWQiOiJBV3VZdnFnMGtaN2Y5S0V4TVpqZU53T3RjQV8yZVhnOWpMZy1QSnBGX0pnYk44M0YyVml5aEdnV2JCNDg4RGU3MFpucGRBZEI2TUNqekNqSyIsInByaXZhY3lVcmwiOiJodHRwczovL2V4YW1wbGUuY29tIiwidXNlckFncmVlbWVudFVybCI6Imh0dHBzOi8vZXhhbXBsZS5jb20iLCJiYXNlVXJsIjoiaHR0cHM6Ly9hc3NldHMuYnJhaW50cmVlZ2F0ZXdheS5jb20iLCJhc3NldHNVcmwiOiJodHRwczovL2NoZWNrb3V0LnBheXBhbC5jb20iLCJkaXJlY3RCYXNlVXJsIjpudWxsLCJhbGxvd0h0dHAiOnRydWUsImVudmlyb25tZW50Tm9OZXR3b3JrIjpmYWxzZSwiZW52aXJvbm1lbnQiOiJvZmZsaW5lIiwidW52ZXR0ZWRNZXJjaGFudCI6ZmFsc2UsImJyYWludHJlZUNsaWVudElkIjoibWFzdGVyY2xpZW50MyIsImJpbGxpbmdBZ3JlZW1lbnRzRW5hYmxlZCI6dHJ1ZSwibWVyY2hhbnRBY2NvdW50SWQiOiJVU0QiLCJjdXJyZW5jeUlzb0NvZGUiOiJVU0QifSwiY29pbmJhc2VFbmFibGVkIjpmYWxzZSwibWVyY2hhbnRJZCI6IjN3MnR0dndkMjQ2NTQ4aGQiLCJ2ZW5tbyI6Im9mZiJ9';

							        // Render the PayPal button

							        paypal.Button.render({

							            // Pass in the Braintree SDK

							            braintree: braintree,

							            // Pass in your Braintree authorization key
							            locale: 'en_US',
							            client: {
							                sandbox: BRAINTREE_SANDBOX_AUTH,
							                production: '<insert production auth key>'
							            },

							            // Set your environment

							            env: 'sandbox', // sandbox | production

							            // Wait for the PayPal button to be clicked

							            payment: function(data, actions) {

							                // Make a call to create the payment

							                return actions.payment.create({
							                    payment: {
							                        transactions: [
							                            {
							                                amount: { total: <?=$totaldue?>, currency: 'USD' }
							                            }
							                        ]
							                    }
							                });
							            },

							            // Wait for the payment to be authorized by the customer

							            onAuthorize: function(data, actions) {

							                // Call your server with data.nonce to finalize the payment

							                console.log('Braintree nonce:', data.nonce);

							                // Get the payment and buyer details

							                return actions.payment.get().then(function(payment) {
							                    console.log('Payment details:', payment);
							                });
							            }

							        }, '#Braintree-button-container');

							    </script>
							</body>
					</td>
				</tr>
			</table>
		

  	</td>
</tr>
</table>
</div>

<br> <br>
<div> 
<label>
	
            <input  checked="true"  type="radio" id="payment_type" name="payment_type" value="card" onclick=" return showcard()"> Credit or Debit Card <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvIApPEnUPhhmektpOyoRM8Ofx82fKJL1JHv5kGE8aJGgn3yXxGg" width="200px" height="50px" />
            </label>
            </div>



<div class="container" id="card" >

	<table width="100%" class="table" >
	<tr>
	<td  width="50%">	

				<div class="row">

					<div class="col-md-8 col-sm-8">Cardholder Name
						<div ><input type="text"  class="form-control ignore" id="card_name" name="card_name" autocomplete="false" required="true"></div>
					</div>

				</div>	
				<br>
							
				<div class="row">
					<div class="col-md-8 col-sm-8">Card number
						<div><input type="text" class="form-control ignore" id="card_number" name="card_number" autocomplete="false" required="true" ></div>
					</div>
				</div>
				
				<br>

				<div class="row">
					<div class="col-md-8 col-sm-8"> CVV / Card Code
						<div><input type="password" class="form-control ignore" id="security_code"  autocomplete="false" name="security_code" > </div>
					</div>
				</div>
				<br>
								
								
				<div class="row">
					<div class="col-md-4 col-sm-4"><b> Expiration </b> <br> Month
						<select name="exp_month" id="exp_month" class="form-control ignore" required="true">
						<?php 
						$curr_mn = date('m');
						for($i=1; $i<=12; $i++) { ?>
						<option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
						<?php } ?>
						</select>  

					</div> 

					<div class="col-md-4 col-sm-4"> <br> Year
						<select name="exp_year" id="exp_year" class="form-control ignore" required="true">
						<?php 
						$curr_year = date('Y');
						$end_year = date("Y", strtotime("+15 years"));
						for($i=$curr_year; $i<=$end_year; $i++) { ?>
						<option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
						<?php } ?>
						</select> 
					</div>
				</div>

								

			 
  	</td>
  	<td width="50%" align="left">
		 <h4><b> Details </b></h4>
			<table class="table" style="border: hidden">
				
				<tr > 
					<td align="right" style="border: hidden"> Check In:</td>	
					<td style="border: hidden" ><?=$Info['date1']?></td>
				</tr> 
				<tr >
					<td align="right" style="border: hidden"> Check Out:</td>	
					<td style="border: hidden" ><?=$Info['date2']?></td>
				</tr> 
				<tr> 			
					<td align="right" style="border: hidden">  <?= ($Info['night']==1?"Night:":"Nights:")?></td>
					<td style="border: hidden"> <?=$Info['night']?></td>
				</tr>
				<tr> 			
					<td align="right" style="border: hidden"> Total Due:</td>
					<td style="border: hidden"> <?=$totaldue?></td>
				</tr>
			</table>
			<div align="center"> 
               <button type="button" class="btn btn-info text-center" onclick="return purchase_click();"> Book </button> 
             </div>

  	</td>
</tr>
</table>
 			

             <div class="col-md-2 col-sm-2"> <br> 
			  </div>
	</div>

</div>



 </fieldset>


        </div> 

        </div> 








</div>

</div>

</form>

<script type="text/javascript">


function showpaypal()
{
    $("#braintree").hide();
    $("#card").hide();
    $("#paypal").show();
    
}

function showbraintree()
{
    $("#paypal").hide();
     $("#card").hide();
    $("#braintree").show();
    
    
}

function showcard()
{
    $("#paypal").hide();
    $("#braintree").hide();
     $("#card").show();
    
    
}


function purchase_click()
{
  
   	if($('#Pagos').valid())
	{
		//$("#preloader").fadeIn("slow");

    	
    var paymm = $('input:radio[name=payment_type]:checked').val();

    if(paymm=='card')
    {
    	$('#Pagos').submit();
    }

    return;

	  if(paymm!='pp'){

			$.ajax({
						data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
						url: "<?php echo site_url('reservation/save_reservation');?>",
						dataType:"json",
						success: function(result)
						{
							var res = result;
	            
							if(res['result']!='1')
							{
								$('#m12close').trigger('click');
								$('#m13close').trigger('click');
								$('#m14close').trigger('click');
								$("#preloader").fadeOut("slow");
								$("#purchase_show").trigger('click');
								$('#thanks_booking').find('#bookresponse').html(res['message']);
	             // document.getElementById("pay_now").click();
							}
							else
							{
								$('#show_error').html(res['error']);
								$("#preloader").fadeOut("slow");
							}
						}
					});
	  }
	  else
	  {
	    $.ajax({
	                data : $("#reserve").serialize()+"&"+$("#reserve_info").serialize(),
	                url: "<?php echo site_url('reservation/save_reservation');?>",
	                success: function(result)
	                {
	                    if(result)
	                    {
	                        $('#m12close').trigger('click');
	                        $('#m13close').trigger('click');
	                        $('#m14close').trigger('click');
	                        $("#preloader").fadeOut("slow");
	                        $("#purchase_show").trigger('click');
	                        $('#thanks_booking').find('#bookresponse').html(result);
	                    //   document.getElementById("pay_now").click();
	                    }
	                    /*else
	                    {
	                        $('#show_error').html(res['error']);
	                        $("#preloader").fadeOut("slow");
	                    }*/
	                }
	                });
	  }
	}
}

function valida()
{

}




</script>