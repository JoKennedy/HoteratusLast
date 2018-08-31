<?php require_once(APPPATH.'controllers/braintree.php');?>

<html>

<body>

<div class="alert alert-danger" style="display:none;" id="alert1">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>¡Warning!</strong> <div id="mensage"></div>
</div>


<div class="container-fluid pad_adjust  mar-top-30 cls_mapsetng">
  <div class=" mar-bot30">
    <div class="verify_det">
      <h4><a href="javascript:;">My Property</a>
        <i class="fa fa-angle-right"> </i> Subscription Info </h4>
      </div>  

            <form method="post" id="payment-form" action="<?php echo lang_url();?>braintree/suscribir" >
                <section>
                  
                  <table cellpadding="50" cellspacing="10">
                    <tbody>
                      <tr>
                        <td width="65%">

                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form_type_list">
                                <label class="">Trade name</label>
                                 <input id="c_company" class="form-control" type="text" value="<?php if(isset($bill->company_name)) {echo $bill->company_name;} ?>" name="c_company" placeholder="Company Name" autocomplete="off">
                                </div>
                              </div>
                            </div>
                            <br>
                             <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form_type_list">
                                <label class="">First Name</label>
                                 <input id="c_firstname" class="form-control" type="text" value="" name="c_firstname" placeholder="First Name" autocomplete="off">
                                </div>
                              </div>
                            </div>
                            <br>
                             <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form_type_list">
                                <label class="">Last Name</label>
                                 <input id="c_lastname" class="form-control" type="text" value="" placeholder="Last Name" autocomplete="off" name="c_lastname">
                                </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form_type_list">
                                <label class="">Email</label>
                                 <input id="c_email" class="form-control" type="text" value="<?php if(isset($bill->email_address)) {echo $bill->email_address;}?>" placeholder="Email" autocomplete="off" name="c_email">
                                </div>
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form_type_list">
                                <label class="">Phone Number</label>
                                 <input id="c_phonenumber" class="form-control" type="text" value="<?php if(isset($bill->mobile)) {echo $bill->mobile;} ?>"  placeholder="Phone Number" autocomplete="off" name="c_phonenumber">
                                </div>
                              </div>
                            </div>
                            <br>
                          <div class="bt-drop-in-wrapper">
                                <div id="bt-dropin"></div>
                            </div>
                        </td>
                        <td width="35%" valign="top">
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

                <input id="nonce" name="payment_method_nonce" type="hidden" />
                <input id="BraintreePlanID" name="BraintreePlanID" value="<?php echo $BraintreePlanID; ?>" type="hidden" />

           
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
              if (validacion() )
              {
                form.submit();
              }
              
            });
          });
        });
       

        function validacion() {

          var company = document.getElementById("c_company").value;
          var firstname = document.getElementById("c_firstname").value;
          var lastname = document.getElementById("c_lastname").value;
           var email = document.getElementById("c_email").value;
            var phonenumber = document.getElementById("c_phonenumber").value;
     

          if (company == null || company.length == 0) {

            $("#mensage").text("Missing Field Trade Name"); 
            $('#alert1').toggle("slow");  
             setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
             return false;
          }
          else if (firstname == null || firstname.length == 0) {
            $("#mensage").text("Missing Field First Name"); 
            $('#alert1').toggle("slow");  
            setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
            return false;
          }

          else if (lastname == null || lastname.length == 0) {
             $("#mensage").text("Missing Field Last Name");
            $('#alert1').toggle("slow");  
             setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
             return false;
          }
         
         else if (phonenumber == null || phonenumber.length == 0) {
             $("#mensage").text("Missing Field Phone Number");
            $('#alert1').toggle("slow");  
             setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
             return false;
          }

          else if (email == null || email.length == 0) {
             $("#mensage").text("Missing Field Last Name");
            $('#alert1').toggle("slow");  
             setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
             return false;
          }


          else if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(email) != true) 
          {

             $("#mensage").text("Wrong Email");
            $('#alert1').toggle("slow");  
             setTimeout(function()
                {
                  $('#alert1').fadeOut();
                },3000);
             return false;
          }

           
          return true;
        }


    </script>
    <script src="javascript/demo.js"></script>
</body>
</html>

<?php $this->load->view('channel/dash_sidebar'); ?>
