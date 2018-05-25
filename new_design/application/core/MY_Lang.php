	<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
// version 10 - May 10, 2012

class MY_Lang extends CI_Lang  {

	/**************************************************
	 configuration
	***************************************************/


	// languages
	var $languages = array(
		'en' 	=> 	'english',
		'sp'	=>	'spanish',
		'fr'	=>	'french',
		'it'	=>	'italian',		
		'ge' 	=>	'german'		
		);

	// Raja Ms - country wise language changes

	/* var $country_language = array(
		'NOR'	=>	'nw',
		'IN' 	=> 	'en',
		'US' 	=> 	'en',
		'dn'	=>	'dn',
		'fn' 	=>	'fn',
		'sw'	=>	'sw'
	); */


	// special URIs (not localized)
	var $special = array (
		""
	);
	
	// where to redirect if no language in URI
	var $default_uri = ''; 

	/**************************************************/
	
	
	function __construct()
	{
		parent::__construct();		
	
		global $CFG;
		global $URI;
		global $RTR;

		$country_language = array(
		'IN' 	=> 	'en',
		'SP'	=>	'sp',
		'FR' 	=> 	'fr',
		'IT'	=>	'it',
		'GE' 	=>	'ge',
		);
	
		$language = array(
		'en' 	=> 	'english',
		'sp'	=>	'spanish',
		'fr'	=>	'french',
		'it'	=>	'italian',		
		'ge' 	=>	'german'		
		);
		
/* 		$conn	= 	mysqli_connect('localhost', 'root', 'Osiz@123') or die('Can not connect');
		$db		=	mysqli_select_db($conn,'unlimited'); */
		$ip 	=	$_SERVER['SERVER_ADDR'];
		
		if($ip=='127.0.0.1') { $ip = "117.232.68.202"; }
		
		/* $details = @json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		if($details){
			$country = $details->country;
		} else {
			$country = "NO";
		} */
		$country = "IN";
		/* echo $country; die; */
		/* $query = mysqli_query($conn,"select * from country where status=1 AND country_symbol='$country'");
			$language = array();
		while ($row = mysqli_fetch_assoc($query)) {
			$language_id	= 	$row['language_id'];
			$querys			= 	mysqli_query($conn,"select * from site_language where id=$language_id");
			$result			=	mysqli_fetch_array($querys);
			$lang_abb 		= 	$result['lang_abb'];
			$lang_name 		= 	$result['lang_name'];
			$country_language[$row['country_symbol']] = $lang_abb;
			$language[$lang_abb] = $lang_name;
		}
		//echo $country; die;  
		print_r($language); die; */
		
		$segment 	= 	$URI->segment(1);
		$url_base 	= 	$URI->config->config['base_url'];
		$url_func 	= 	$URI->uri_string;
	 
		if (isset($language[$segment]))	
		{
			$language = $language[$segment];
			$CFG->set_item('language', $language);
		}
		else if($this->is_special($segment))
		{
			if (!array_key_exists($country,$country_language)){
				$country = 'IN';
			}
			$load_language = $country_language[$country];
			$language = $language[$load_language];
			$CFG->set_item('language', $language);
			//echo $country; die;
			header("Location: ".$url_base.$load_language , TRUE, 302);
			return;
		}
		else
		{
			if (!array_key_exists($country,$country_language)){
				$country = 'IN';
			}
			$load_language = $country_language[$country];
			$language = $language[$load_language];
			$CFG->set_item('language', $language);
			header("Location: ".$url_base.$load_language."/". $url_func , TRUE, 302); exit;
			//header("Location: " . $CFG->site_url($this->localized($this->default_uri)), TRUE, 302);
			//exit;
		}
	}
	
	// get current language
	// ex: return 'en' if language in CI config is 'english' 
	function lang()
	{
		global $CFG;		
		$language = $CFG->item('language');
		
		$lang = array_search($language, $this->languages);
		if ($lang)
		{
			return $lang;
		}
		
		return NULL;	// this should not happen
	}
	
	function is_special($uri)
	{
		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special))
		{
			return TRUE;
		}
		if(isset($this->languages[$uri]))
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function switch_uri($lang)
	{
		$CI =& get_instance();

		$uri = $CI->uri->uri_string();
		if ($uri != "")
		{
			$exploded = explode('/', $uri);
			if($exploded[0] == $this->lang())
			{
				$exploded[0] = $lang;
			}
			$uri = implode('/',$exploded);
		}
		return $uri;
	}
	
	// is there a language segment in this $uri?
	function has_language($uri)
	{
		$first_segment = NULL;
		
		$exploded = explode('/', $uri);
		if(isset($exploded[0]))
		{
			if($exploded[0] != '')
			{
				$first_segment = $exploded[0];
			}
			else if(isset($exploded[1]) && $exploded[1] != '')
			{
				$first_segment = $exploded[1];
			}
		}
		
		if($first_segment != NULL)
		{
			return isset($this->languages[$first_segment]);
		}
		
		return FALSE;
	}
	
	// default language: first element of $this->languages
	function default_lang()
	{
		foreach ($this->languages as $lang => $language)
		{
			return $lang;
		}
	}
	
	// add language segment to $uri (if appropriate)
	function localized($uri)
	{
		if($this->has_language($uri)
				|| $this->is_special($uri)
				|| preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri))
		{
			// we don't need a language segment because:
			// - there's already one or
			// - it's a special uri (set in $special) or
			// - that's a link to a file
		}
		else
		{
			$uri = $this->lang() . '/' . $uri;
		}
		
		return $uri;
	}
	
}

/* End of file */
