<?php

class tokenex extends Front_Controller {




	function Tokenizar($data)
	{

		$XML="<TokenAction>
			  <APIKey>2DBg2kiNMZ59QLS5Kpqj</APIKey>
			  <TokenExID>1257415279270941</TokenExID>
			  <Data>$data</Data>
			  <TokenScheme>fourTOKENfour</TokenScheme>
			</TokenAction>";

		$URL = "https://api.tokenex.com/TokenServices.svc/REST/Tokenize";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $XML);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			$output = curl_exec($ch);
			$mail_data = '<strong> Response </strong> <br>';
			$mail_data .= $output;

			$data_api=simplexml_load_string($output);

			if($data_api->Error!="")
			{
				return (string)$data_api->Error;
			}
			else
			{
				return (string)$data_api->Token;
			}
			
			

	}

	
function Detokenizar($data)
	{
					$XML="<DetokenizeAction>
					  <APIKey>j8pp5w5eNVJhEMIxo6O3</APIKey>
					  <TokenExID>1257415279270941</TokenExID>
					  <Token>$data</Token>
					</DetokenizeAction>";

			$URL = "https://api.tokenex.com/TokenServices.svc/REST/Detokenize";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $XML);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			$output = curl_exec($ch);
			$mail_data = '<strong> Response </strong> <br>';
			$mail_data .= $output;

			$data_api=simplexml_load_string($output);

		
			if($data_api->Error!="")
			{
				
				return (string)$data_api->Error;
			}
			else
			{
				return (string)$data_api->Value;
			}


	}
	
	function validarToken($data)
	{
					$XML="<ValidateTokenAction>
						  <APIKey>2DBg2kiNMZ59QLS5Kpqj</APIKey>
						  <TokenExID>1257415279270941</TokenExID>
						  <Token>$data</Token>
						</ValidateTokenAction>";

			$URL = "https://api.tokenex.com/TokenServices.svc/REST/ValidateToken";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $XML);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			$output = curl_exec($ch);
			$mail_data = '<strong> Response </strong> <br>';
			$mail_data .= $output;

			$data_api=simplexml_load_string($output);


			if($data_api->Error!="")
			{
				return (string)$data_api->Error;
			}
			else
			{
				return (string)$data_api->Valid;
			}


	}

	function DeleteToken($data)
	{
					

				$XML="<DeleteTokenAction>
					  <APIKey>2DBg2kiNMZ59QLS5Kpqj</APIKey>
					  <TokenExID>1257415279270941</TokenExID>
					  <Token>$data</Token>
					</DeleteTokenAction>";

			$URL = "https://api.tokenex.com/TokenServices.svc/REST/DeleteToken";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $XML);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			$output = curl_exec($ch);
			$mail_data = '<strong> Response </strong> <br>';
			$mail_data .= $output;

			$data_api=simplexml_load_string($output);

			if($data_api->Error!="")
			{
				return (string)$data_api->Error;
			}
			else
			{
				return (string)$data_api->Success;
			}


	}

}
?>