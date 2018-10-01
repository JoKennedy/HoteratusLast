<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class scraping extends Front_Controller {


	
 	public function __construct()
    {
    	require_once('simple_html_dom.php');
        
        parent::__construct();
        
        //load base libraries, helpers and models      
      
        
    }

public function scrapear($date)
{
	$date1=$date;
	$date2=date('Y-m-d',strtotime($date."+1 days"));


	$agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";

	$referer = 'http://www.hotelhunter.com';
	$cookies = 'cookies.txt';
	$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '',$agent); 
	$html= html_to_dom(str_replace('data-providername', 'name', $content));
	$result='';
	//echo $html;
	foreach ($html->find('#hc_htl_pm_rates_content') as $tarifainfo) {
	     	$result.= $date1.'hotelhunter';
		foreach ($tarifainfo->find('.hc-ratesmatrix__dealsrow') as  $value) {

			if ($value->name=='Booking.com' || $value->name=='Agoda.com') {

				$result.=  $value->name;
				$result.=  $value->find('.hc-ratesmatrix__roomrate',0);
				$result.=  $value->find('.hc-ratesmatrix__roomname',0).'<br>';
			}
			
			
		}       
		 
	}
	

	return ($result==''?$html:$result);
}
public function scrapear2($date)
{
	$date1=$date;
	$date2=date('Y-m-d',strtotime($date."+1 days"));


	$referer = 'http://www.hotelhunter.com';
	$cookies = 'cookies2.txt';
	$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '','Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3'); 
	$html= html_to_dom(str_replace('data-providername', 'name', $content));

	$result='';
	foreach ($html->find('#hc_htl_pm_rates_content') as $tarifainfo) {
	     	$result.= $date1.'combied';
		foreach ($tarifainfo->find('.hc-ratesmatrix__dealsrow') as  $value) {

			if ($value->name=='Booking.com' || $value->name=='Agoda.com') {

				$result.=  $value->name;
				$result.=  $value->find('.hc-ratesmatrix__roomrate',0);
				$result.=  $value->find('.hc-ratesmatrix__roomname',0).'<br>';
			}
			
			
		}       
		 
	}
	

	return ($result==''?$html:$result);
}
function index()
{

	$date=date('Y-m-d');
	$result='';

	for ($i=0; $i <30 ; $i++) { 

		if($i%2)
		{
			 $result.=$this->scrapear(date('Y-m-d',strtotime($date."+$i days"))) ;
		}
		else
		{
			$result.=$this->scrapear2(date('Y-m-d',strtotime($date."+$i days"))) ;
		}

		 

	}
	echo $result;

return;
// Create DOM from URL or file

	$html = file_get_html('https://www.hotelscombined.com/Hotel/Search?checkin=2018-10-07&checkout=2018-10-08&Rooms=1&adults_1=2&currencyCode=DOP&fileName=Lifestyle&languageCode=EN');
	//$html = file_get_html('https://www.booking.com/searchresults.es.html?&ss=Puerto+Plata+Province&ssne=Puerto+Plata+Province&ssne_untouched=Puerto+Plata+Province&region=1265&checkin_monthday=28&checkin_month=9&checkin_year=2018&checkout_monthday=29&checkout_month=9&checkout_year=2018&no_rooms=1&group_adults=2&group_children=0&b_h4u_keep_filters=&from_sf=1');

	echo $html; return;
$i = 1;
	foreach ($html->find('li.channels-content-item') as $video) {
	        
	        //echo $video;
	       foreach ($video->find('div.yt-lockup-content') as  $title) {
	       	
	       	echo $title->find('a.yt-uix-sessionlink', 0)->title;
	       	echo '<br>';

	       }

	        $i++;
	}
return;
	echo $html;
	return;

// creating an array of elements
	$videos = [];

	// Find top ten videos
	$i = 1;
	foreach ($html->find('li.expanded-shelf-content-item-wrapper') as $video) {
	        if ($i > 10) {
	                break;
	        }

	        // Find item link element
	        $videoDetails = $video->find('a.yt-uix-tile-link', 0);

	        // get title attribute
	        $videoTitle = $videoDetails->title;

	        // get href attribute
	        $videoUrl = 'https://youtube.com' . $videoDetails->href;

	        // push to a list of videos
	        $videos[] = [
	                'title' => $videoTitle,
	                'url' => $videoUrl
	        ];

	        $i++;
	}

	var_dump($videos);
	}

    function cURL($url, $posts, $cookies, $referer, $proxy,$agent){
    $headers = array (
        'Accept-Language: en-US;q=0.6,en;q=0.4',
    );

    $tiempo = time();

    
    //Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if($proxy){
        if(stristr($proxy, '@')){
            $datosproxy = explode('@', $proxy);
            curl_setopt($ch, CURLOPT_PROXY, $datosproxy[1]);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $datosproxy[0]);
            //echo $datosproxy[0];
        }else{
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
    }
    if($posts){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $page = curl_exec($ch);
    curl_close($ch);

    if($page){
        return $page;
    }
    return 'Forbidden';
} 

}