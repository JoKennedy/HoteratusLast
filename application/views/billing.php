<?php require_once(APPPATH.'controllers/braintree.php');?>

<html>

<body>



<div class="container-fluid pad_adjust  mar-top-30 cls_mapsetng">
  <div class=" mar-bot30">
    <div class="verify_det">
      <h4><a href="javascript:;">My Property</a>
        <i class="fa fa-angle-right"></i>
         Billing Info
        </h4>
      </div>  

            <form method="post" id="payment-form" action="<?php echo lang_url();?>braintree/suscribir">
                <section>
                  
                  <table cellpadding="0" cellspacing="0">>
                    <tbody>
                      <tr>
                        <td width="65%">
                          <div class="bt-drop-in-wrapper">
                                <div id="bt-dropin"></div>
                            </div>
                        </td>
                        <td width="35%">
                                <div class="cls_prin50 back_time clearfix">
                                  <ul class="list-unstyled">
                                  <li><strong><?php echo $plan_name;?>:</strong><?php echo $symbol.$plan_price;?></li>
                                  <li><strong>Total :  </strong><?php echo $symbol.$plan_price;?></li>
                                  <li><strong>Grand Total :  </strong><?php echo $symbol.$plan_price;?></li>       
                                  </ul>
                                    <div class="accept">
                                        <div class="cls_bulk_checkbox">
                                            <input type="checkbox" value="1" style="margin-right: 3px;" name="order_terms_of_service" id="order_terms_of_service" required  class="styled">

                                            <label for="order_terms_of_service"> I accept the <a target="blank" id="terms_and_conditions_link" href="<?php echo lang_url()?>our_links/terms">Terms and condition</a> and <a target="blank" id="privacy_terms_link" href="<?php echo lang_url();?>our_links/privacy"> Privacy Terms</a> </label>
                                        </div>
                                    </div>
                                    <button class="btn btn_subscribe" type="submit">subscribe</button>
                                </div>
                        </td>
                      </tr>
                    </tbody>

                  </table>

                  

                   
                </section>

                <input id="nonce" name="payment_method_nonce" type="text" />
                

           
            </form>
  </div>
</div>

    
<?php $braintre = new braintree();
 ?>
    <script src="https://js.braintreegateway.com/web/dropin/1.9.4/js/dropin.min.js"></script>
    <script>
        var form = document.querySelector('#payment-form');
        var client_token = "<?php echo(Braintree_ClientToken::generate()); ?>";

        braintree.dropin.create({
          authorization: client_token,
          selector: '#bt-dropin',
          paypal: {
            flow: 'vault'
          }
        }, function (createErr, instance) {
          if (createErr) {
            console.log('Create Error', createErr);
            return;
          }
          form.addEventListener('submit', function (event) {
            event.preventDefault();

            instance.requestPaymentMethod(function (err, payload) {
              if (err) {
                console.log('Request Payment Method Error', err);
                return;
              }

              // Add the nonce to the form and submit
              document.querySelector('#nonce').value = payload.nonce;
              form.submit();
            });
          });
        });
    </script>
    <script src="javascript/demo.js"></script>
</body>
</html>
