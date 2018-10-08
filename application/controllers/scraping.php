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
    public function competitiveset()
    {
    	is_login();
		$hotelid=hotel_id();
    	$data['page_heading'] = 'Competitive Set Analisis';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['allRooms']=$this->db->query("select a.*, case a.pricing_type when 1 then 'Room based pricing' when 2 then 'Guest based pricing' else 'Not available' end  PricingName, case when b.meal_name is null then 'No Plan' else b.meal_name end meal_name   from manage_property a left join meal_plan b on a.meal_plan=meal_id where hotel_id=$hotelid")->result_array();

		$this->views('channel/managerooms',$data);
    }
	public function ScrapearBooking($date,$HotelNameOut,$HotelOutId,$HotelId,$ChannelId)
	{
		$date1=$date;
		$date2=date('Y-m-d',strtotime($date."+1 days"));


		$agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";

		$referer = 'http://www.booking.com';
		$cookies = 'cookies.txt';
		$content= $this->cURL("https://www.booking.com/hotel/do/$HotelNameOut.html?checkin=$date1;checkout=$date2;dest_type=city;dist=0;group_adults=2;hapos=1;sb_price_type=total;type=total", '', $cookies, $referer, '',$agent); 


		$html= html_to_dom($content);
		$result='';
		$roomname='';

		foreach ($html->find('#available_rooms') as $Rooms) {
			
			foreach ($Rooms->find('tr') as $value) {
			 	
			 	if(strlen($value->find('.hprt-roomtype-name .hprt-roomtype-icon-link',0))>0)
			 	{	
			 		$roomname=$value->find('.hprt-roomtype-name .hprt-roomtype-icon-link',0)->text(); 		

			 	}
			 	if (strlen($roomname)>0 && strlen($value->find('.invisible_spoken',0))>0 && count($value->find('.hprt-price-price'))>0) {

			 		
			 		$person=$value->find('.invisible_spoken',0)->text();
			 		$prices=$value->find('.hprt-price-price',0)->text();
			 		$info['ChannelId']=$ChannelId;
			 		$info['RoomName']=$roomname;
			 		$info['HotelOutId']=$HotelOutId;
			 		$info['MaxPeople']=(string)$person;
			 		$info['DateCurrent']=$date1;
			 		$info['Prices']=(string)$prices;
			 		

			 		insert_data('HotelScrapingInfo',$info);
			 		//print_r($info);
			 	}
			 	

			 	
			 }       
			 
		}
		

		return;
	}
	public function scrapear2($date)
	{
		$date1=$date;
		$date2=date('Y-m-d',strtotime($date."+1 days"));


		$referer = 'http://www.hotelhunter.com';
		$cookies = 'cookies2.txt';
		$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '','Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3'); 

		while ($content=='Forbidden') {
			$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '','Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3'); 
		}
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
	public function ScrapingBooking($start)
	{
		$ConfigHoteles=$this->db->query("SELECT * FROM HotelsOut where active=1 and ChannelId=2")->result_array();
		$date=date('Y-m-d');
		foreach ($ConfigHoteles as  $HotelInfo) {

			 for ($i=$start; $i <($start+90) ; $i++) { 

				$this->ScrapearBooking(date('Y-m-d',strtotime($date."+$i days")),$HotelInfo['HotelNameChannel'],$HotelInfo['HotelsOutId'],$HotelInfo['HotelID'],$HotelInfo['ChannelId']) ;	 
			}
			echo "Propiedad ".$HotelInfo['HotelID'].'<br>';
		}
	}
	function index()
	{

		$date=date('Y-m-d',strtotime('2018-12-01'));
		$result='';

		

		for ($i=0; $i <1 ; $i++) { 


			$result.=$this->scrapear(date('Y-m-d',strtotime($date."+$i days"))) ;	 

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