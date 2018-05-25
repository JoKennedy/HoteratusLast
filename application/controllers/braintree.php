<?php

require 'braintree/lib/Braintree.php';

class braintree extends Front_Controller {

      public function __construct()
       {
              parent::__construct();  
              
             $params = array(
                        "testmode"   => "on",
                        "merchantid" => "xmjfjmq2bx672x2t",
                        "publickey"  => "fz53jjpys38954dw",
                        "privatekey" => "7628fba41f691545c44b0d38e788083c",
                      );


              if ($params['testmode'] == "on")
              {
                Braintree_Configuration::environment('sandbox');
              }
              else
              {
                Braintree_Configuration::environment('production');
              }

              Braintree_Configuration::merchantId($params["merchantid"]);
              Braintree_Configuration::publicKey($params["publickey"]);
              Braintree_Configuration::privateKey($params["privatekey"]);


      }

      function suscribir()
      { 
        

            if(isset($_POST['payment_method_nonce']))
            {

                // Customer details
                $customer_firstname   = $_POST['c_firstname'];
                $customer_lastname    = $_POST['c_lastname'];
                $customer_email       = $_POST['c_email'];
                $customer_phonenumber = $_POST['c_phonenumber'];
                $customer_company= $_POST['c_company'];
                // EOF Customer details

            

                $result = Braintree_Customer::create([
                    'firstName' => $customer_firstname  ,
                    'lastName' => $customer_lastname ,
                    'company'=>$customer_company,
                    'email' => $customer_email ,
                    'phone' => $customer_phonenumber ,
                    'paymentMethodNonce' =>  $_POST['payment_method_nonce']
                ]);



                if (!isset($result->message)) 
                {
                    $customerid=$result->customer->id ;
                    if(isset($result->customer->paypalAccounts[0]))
                    {
                      //cuenta paypal
                      $paymentMethodToken =$result->customer->paypalAccounts[0]->token;
                    }
                    else
                    {
                      $paymentMethodToken =$result->customer->creditCards[0]->token;

                    }

                    
                } 
                else
                {
                   
                   echo $result->message;
                   return;
                }




                  $result = Braintree_Subscription::create([
                    'paymentMethodToken' => $paymentMethodToken,
                    'planId' => $_POST['BraintreePlanID']
                  ]);
                  echo $result->subscription->id;
                  print_r($result );

                  die; 
            }
            else
           {

           }
           



      }


      function finsuscripcion($id)
      {
        $subscription = Braintree_Subscription::find($id);
        print_r($subscription);
        die;

      }



      function test()
      {
        $this->load->view('channel/test');
      }

      function clienttoken()
      {

       
              //$braintree_cust_id = "60033487";
              // Generate the nonce and send it back
              try
              {
                $clientToken = Braintree_ClientToken::generate(array(
                  // use customerId to get a previous customer from the vault
                  // 'customerId' => $braintree_cust_id    // $braintree_cust_id is Fetch from DB
                ));
              }
              catch(Exception $e)
              {
                // cannot get the customer from the vault!!
                $clientToken = Braintree_ClientToken::generate();
              }

             
              return $clientToken; exit;

      }
        function clienttoken2()
      {

       
              //$braintree_cust_id = "60033487";
              // Generate the nonce and send it back
              try
              {
                $clientToken = Braintree_ClientToken::generate(array(
                  // use customerId to get a previous customer from the vault
                  // 'customerId' => $braintree_cust_id    // $braintree_cust_id is Fetch from DB
                ));
              }
              catch(Exception $e)
              {
                // cannot get the customer from the vault!!
                $clientToken = Braintree_ClientToken::generate();
              }

             
              echo $clientToken; exit;

      }
}
?>


 

