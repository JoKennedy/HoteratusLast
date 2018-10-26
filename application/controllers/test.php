<?php

ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends Front_Controller {



	public function __construct()
		{

			require_once('simple_html_dom.php');

				parent::__construct();

				//load base libraries, helpers and models
			
			
 			 

		}


		public function test2()
		{
			
				$date= new Datetime();

					
					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://www.expedia.com/infosite-api/1369424/getOffers?token=7e116a5fdfe32436875dc79ed791fa65aa05f27b&adults=2&children=0&chkin=1%2F26%2F2019&chkout=1%2F29%2F2019&exp_curr=USD",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_SSL_VERIFYPEER=>0,
					  CURLOPT_HTTPHEADER => array(
					    "accept: application/json, text/javascript, */*; q=0.01",
					    "accept-encoding: gzip, deflate, br",
					    "accept-language: es,en;q=0.9",
					    "cache-control: no-cache",
					    'cookie:  tpid=v.1,1; currency=USD; MC1=GUID=06b9d0da7fef40fc9003419316a65be5; ',					    
					    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
					    "x-requested-with: XMLHttpRequest"
					  ),
					));

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					if ($err) {
					  echo "cURL Error #:" . $err;
					} else {
					  echo $response;
					}


					
		}

		public function test3()
		{


					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://www.expedia.com/infosite-api/26701933/getOffers?clientid=KLOUD-HIWPROXY&token=9d54eb3004b503676d1c734b76748f5a982a5adb&brandId=0&countryId=201&isVip=false&chid=&partnerName=&partnerPrice=0&partnerCurrency=&partnerTimestamp=0&adults=2&children=0&chkin=2%2F12%2F2019&chkout=2%2F15%2F2019&hwrqCacheKey=06b9d0da-7fef-40fc-9003-419316a65be5HWRQ1540561259771&cancellable=false&regionId=7927&vip=false&=undefined&daysInFuture=&stayLength=&ts=1540565555422&evalMODExp=true&tla=MCO",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					   CURLOPT_SSL_VERIFYPEER=>0,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_HTTPHEADER => array(
					    "accept: application/json, text/javascript, */*; q=0.01",
					    "accept-encoding: gzip, deflate, br",
					    "accept-language: es,en;q=0.9",
					    "cache-control: no-cache",
					    					    
					    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
					    "x-requested-with: XMLHttpRequest"
					  ),
					));

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					if ($err) {
					  echo "cURL Error #:" . $err;
					} else {
					  echo $response;
					}
		}
}

 ?>




