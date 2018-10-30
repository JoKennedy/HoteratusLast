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
					  CURLOPT_URL => "https://www.expedia.com/infosite-api/534766/getOffers?token=d008a921a2b22f55c3cc8bc6cbe34621bc908f15&adults=2&children=0&chkin=4%2F3%2F2019&chkout=4%2F6%2F2019&exp_curr=USD",
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
					    'cookie:  tpid=v.1,1; currency=USD; MC1=GUID=d1fcf9857bd94040a3bbaea209ddb3fc; ',					    
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
  CURLOPT_URL => "https://www.expedia.com/infosite-api/26701933/getOffers?clientid=KLOUD-HIWPROXY&token=c869d30e1f4b19156fe60958fdb919cebf13ecda&brandId=0&countryId=201&isVip=false&chid=&partnerName=HSR&partnerPrice=244.61&partnerCurrency=USD&partnerTimestamp=1540621828798&adults=2&children=0&chkin=4%2F1%2F2019&chkout=4%2F4%2F2019&hwrqCacheKey=d1fcf985-7bd9-4040-a3bb-aea209ddb3fcHWRQ1540621830537&cancellable=false&regionId=7927&vip=false&=undefined&exp_dp=244.61&exp_ts=1540621828798&exp_curr=USD&swpToggleOn=false&exp_pg=HSR&daysInFuture=&stayLength=&ts=1540622554091&evalMODExp=true&tla=MCO",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",

  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 5594fdf8-b763-1322-08f7-a18517c939db",
    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36",
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




