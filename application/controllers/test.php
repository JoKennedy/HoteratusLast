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
					  CURLOPT_URL => "https://www.expedia.es/infosite-api/534766/getOffers?clientid=KLOUD-HIWPROXY&token=4fb4fe08b296f4621047719b49cf70342d4088a0&brandId=3901&countryId=50&isVip=false&chid=&partnerName=HSR&partnerPrice=146.6&partnerCurrency=EUR&partnerTimestamp=".$date->getTimestamp()."&adults=2&children=0&chkin=24%2F10%2F2018&chkout=25%2F10%2F2018&hwrqCacheKey=440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416&cancellable=false&regionId=2851&vip=false&=undefined&exp_dp=146.6&exp_ts=".$date->getTimestamp()."&exp_curr=EUR&swpToggleOn=false&exp_pg=HSR&daysInFuture=&stayLength=&ts=1540412851309&evalMODExp=true&tla=DCF",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_SSL_VERIFYPEER =>0,
					  CURLOPT_HTTPHEADER => array(
					    "accept: application/json, text/javascript, */*; q=0.01",
					    "accept-encoding: gzip, deflate, br",
					    "accept-language: es,en;q=0.9",
					    "cache-control: no-cache",
					    "cookie: _gid=GA1.2.625488834.1540411499; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; DUAID=716c4d9a-dfba-4b43-9682-9a6e5aeee40c; MC1=GUID=716c4d9adfba4b4396829a6e5aeee40c; OIP=gdpr|-1,ab.gdpr|1; ak_bmsc=D1F725E7A755FCC10748F746D3DCD88A08122BD887550000D9CCD05B141DEE00~plWW7vA1eeORcHZWfhCNZ3fRxY07Wr4vKz+toG7QRS4ecJvraZznNh09U635OO2v8ZjB32IVoQ8v+chx9anqgSrty9tqxWWZz2w1mYvxNeoT3FJzPeuWVuG1Goh6jJlNQxA+bEOt8N/KMEBR7FVuphaMEWQBUpMiAKo/DZR+QlUgC9MFA0sPjKv2w/ZStG2hhAd4XVbpMiOciegGLSRpXt9J5svd/i/7ChkUoUcZRRTao=; CONSENTMGR=ts:1540411930584%7Cconsent:false; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0102%3Bexp-session$_st:1540413730595$ses_id:1540410976492%3Bexp-session; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; HMS=e52aa8f8-ea52-440f-a555-878c77a02c4d; tpid=v.1,9; iEAPID=0; currency=EUR; linfo=v.4,|0|0|255|1|0||||||||3082|0|0||0|0|0|-1|-1; JSESSION=e4c77c06-dbc2-45da-ae19-90306fb2491d; aspp=v.1,0|||||||||||||; AMCVS_C00802BE5330A8350A490D4C%40AdobeOrg=1; AMCV_C00802BE5330A8350A490D4C%40AdobeOrg=-179204249%7CMCIDTS%7C17829%7CMCMID%7C10422282565004023911248397125712894566%7CMCAAMLH-1541015773%7C7%7CMCAAMB-1541015773%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1540418173s%7CNONE%7CMCAID%7CNONE; AB_Test_TripAdvisor=A; GDPRb=1; qualtrics_sample=false; qualtrics_SI_sample=true; QSI_HistorySession=https%3A%2F%2Fwww.expedia.es%2FPuerto-Plata-Hoteles-Lifestyle-Tropical-Beach-Resort-Spa-All-Inclusive.h534766.Informacion-Hotel%3Fchkin%3D25%252F10%252F2018%26chkout%3D26%252F10%252F2018%26rm1%3Da2%26hwrqCacheKey%3D440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416%26cancellable%3Dfalse%26regionId%3D2851%26vip%3Dfalse%26c%3D1e98c988-70fb-4fc9-b14a-948ff1b52589%26%26exp_dp%3D146.6%26exp_ts%3D".$date->getTimestamp()."%26exp_curr%3DEUR%26swpToggleOn%3Dfalse%26exp_pg%3DHSR~1540411191551; HSEWC=0; s_ppn=page.Hotels.Infosite.Information; s_cc=true; rlt_marketing_code_cookie=; currency=EUR; linfo=v.4,|0|0|255|1|0||||||||3082|0|0||0|0|0|-1|-1; JSESSION=e4c77c06-dbc2-45da-ae19-90306fb2491d; abucket=CgHgI1vQzNg9oCmkGAALAg==; aspp=v.1,0|||||||||||||; AMCVS_C00802BE5330A8350A490D4C%40AdobeOrg=1; AMCV_C00802BE5330A8350A490D4C%40AdobeOrg=-179204249%7CMCIDTS%7C17829%7CMCMID%7C10422282565004023911248397125712894566%7CMCAAMLH-1541015773%7C7%7CMCAAMB-1541015773%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1540418173s%7CNONE%7CMCAID%7CNONE; AWSELB=5DC5036312F7D9CBE037BAAE6FE1CFD2E9AFA03402B745C089600146556C3703EF2BDD06BCA560D595349023389858DB7A7015E99626B7A7B8F3280A0A6803D1A9F43A507A; AB_Test_TripAdvisor=A; GDPRb=1; qualtrics_sample=false; qualtrics_SI_sample=true; HMS=e52aa8f8-ea52-440f-a555-878c77a02c4d; tpid=v.1,9; iEAPID=0; cesc=%7B%22entryPage%22%3A%5B%22page.Hotels.Infosite.Information%22%2C1540410973825%5D%7D; s_ppn=page.Hotels.Infosite.Information; s_cc=true; rlt_marketing_code_cookie=; HSEWC=0; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; OIP=gdpr|1,ab.gdpr|1; __gads=ID=cdcdc7e8aa1b8772:T=1540411964:S=ALNI_MaXSHdhcArz7OtoJ4bepwwnKLGOzA; cesc=%7B%22entryPage%22%3A%5B%22page.Hotels.Infosite.Information%22%2C1540410973825%5D%7D; CONSENTMGR=ts:1540412188225%7Cconsent:true; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0$_sn:1$_ss:0$_pn:4%3Bexp-session$_st:1540413988233$ses_id:1540410976492%3Bexp-session; _tq_id.TV-721872-1.76d3=bec9e6628c7d4823.1540411499.0.1540411499..; _gcl_au=1.1.1531971318.1540411499; __gads=ID=aa66a20545afd036:T=1540411494:S=ALNI_MbeUZYD6jTkjEU7jgMCZuqfaEr9EQ; _ga=GA1.2.91656075.1540411499; _gid=GA1.2.625488834.1540411499; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; DUAID=716c4d9a-dfba-4b43-9682-9a6e5aeee40c; MC1=GUID=716c4d9adfba4b4396829a6e5aeee40c; OIP=gdpr|-1,ab.gdpr|1; ak_bmsc=D1F725E7A755FCC10748F746D3DCD88A08122BD887550000D9CCD05B141DEE00~plWW7vA1eeORcHZWfhCNZ3fRxY07Wr4vKz+toG7QRS4ecJvraZznNh09U635OO2v8ZjB32IVoQ8v+chx9anqgSrty9tqxWWZz2w1mYvxNeoT3FJzPeuWVuG1Goh6jJlNQxA+bEOt8N/KMEBR7FVuphaMEWQBUpMiAKo/DZR+QlUgC9MFA0sPjKv2w/ZStG2hhAd4XVbpMiOciegGLSRpXt9J5svd/i/7ChkUoUcZRRTao=; CONSENTMGR=ts:1540411930584%7Cconsent:false; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0$_sn:1$_ss:0$_pn:2%3Bexp-session$_st:1540413730595$ses_id:1540410976492%3Bexp-session; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP",
					    "postman-token: 9e8eec45-d335-331a-167d-b473d9fa67b1",
					    "referer: https://www.expedia.es/Puerto-Plata-Hoteles-Lifestyle-Tropical-Beach-Resort-Spa-All-Inclusive.h534766.Informacion-Hotel?adults=2&children=0&chkin=24%2F10%2F2018&chkout=25%2F10%2F2018&hwrqCacheKey=440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416&cancellable=false&regionId=2851&vip=false&=undefined&exp_dp=146.6&exp_ts=".$date->getTimestamp()."&exp_curr=EUR&exp_pg=HSR&daysInFuture=&stayLength=&ts=".$date->getTimestamp(),
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




