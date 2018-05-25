<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------
   
$config =
	array( 
		// set on "base_url" the relative url that point to HybridAuth Endpoint     
		'base_url'=>'/user/endpoint',      
 
		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "dj0yJmk9WGVwOU1oUkl5WG5ZJmQ9WVdrOWRFcDBOWFZUTkdrbWNHbzlNVGt6TXpJMk5qYzJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD1kNg--", "secret" => "3f26fd41fb9e51b7bd7ccbdf6165efb7ac0a35aa" ),
			),

			"AOL"  => array (
				"enabled" => true
			),
	
			"Google" => array (
                               "enabled" => true,
                               "keys"    => array ( "id" => "183981089774-rtqb8k92rk9633ri12m6bcikknbnm3s4.apps.googleusercontent.com", "secret" => "Az1SnViey4jpst5-anLM7hHH"),
                       ),
			
	 
			"Facebook" => array ( 
				"enabled" => true,  
				"keys"    => array ( "id" => "374667776030631", "secret" => "5db4640745240d45fc4b76c881332ae1" ),
			  
			),  
      
			"Twitter" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "phf8e67oEBUDJuFZyjgIEDnMf", "secret" => "P1tRzxIdUgdFBHfX9ZBqEsXmyrpsuqfXd6ADXH8JVp53CQMbAE" )
			),


			// windows live 
			"Live" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"LinkedIn" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "6ehjoprzsps1", "secret" => "MHy1NOLqyFbNaqRC" )
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */