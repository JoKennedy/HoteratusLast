<?php
/** 
 * cURL.php
 * Created on 22-Apr-2010 
 * Author PSS Team
 * Version 1.0.0
 */
class cURL {

     var $headers;
    
     var $user_agent;
   
     var $compression;
   
     var $cookie_file;
    
     var $proxy;

	 var $proxyAuther;
	 
	 var $callCount = 0;

	 public function __construct($cookies=TRUE,$cookie='cookies.txt',$compression='gzip,deflate',$proxy='')
	{
		$this->headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
           $this->headers[] = "Connection: Keep-Alive";
		   $this->headers[] = " Content-type: application/x-www-form-urlencoded";
           //$this->user_agent = "Mozilla/5.0 (Windows NT 6.1; rv:26.0) Gecko/20100101 Firefox/26.0";
           $this->user_agent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)";
           $this->compression=$compression;
           $this->proxy=$proxy;
           $this->cookies=$cookies;
           if ($this->cookies == TRUE) $this->cookie($cookie); 
		
		   /*if(in_array(strtolower(PHP_OS), array("win32", "windows", "winnt"))) $cookie=getcwd().'\\'.$cookie;
		   else $cookie=getcwd().'/'.$cookie;
			
			  echo "--11 $this->cookies--";

           if ($this->cookies == TRUE) $this->cookie($cookie); 

		   echo "--$this->cookies 222--";*/
	}
	 
    
     function cookie($cookie_file) {
          if (file_exists($cookie_file)) {
                $this->cookie_file=$cookie_file;
          } else { 
                $fp=@fopen($cookie_file,'w') or $this->error("The cookie file could not be opened. Make sure this directory has the correct permissions");
                $this->cookie_file=$cookie_file;
                fclose($fp);
          }
     }
    
     function get($url,$refer='') {
			
	      $process = curl_init();
		  curl_setopt($process, CURLOPT_URL, $url);
		  if ($refer==''){
			   curl_setopt($process, CURLOPT_AUTOREFERER, 1);
		  } else {
			   curl_setopt($process, CURLOPT_REFERER, $refer);			  
		  }
		 
		  curl_setopt($process, CURLOPT_HEADER, 1);
          curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
          curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
          curl_setopt($process,CURLOPT_ENCODING , $this->compression);
          curl_setopt($process, CURLOPT_TIMEOUT, 200);
		  curl_setopt($process, CURLOPT_FOLLOWLOCATION, 5);
		  curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
		  curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
		  curl_setopt($process, CURLOPT_VERBOSE, 1);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXYUSERPWD, $this->proxyAuther);
          curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
          $return = curl_exec($process);
          

		  $head = curl_getinfo($process, CURLINFO_HTTP_CODE);
			if(!curl_errno($process))
			{
					$code = curl_getinfo( $process );
					$dump = var_export($code, true);
					$fname = 'dump'.$this->callCount++.'.txt';
					//$this->writeToFile($fname,trim($dump));

			}

			curl_close($process);
          return $head.$return;
     }
   
     function post($url,$data,$refer='') {
          $process = curl_init($url);
		  if ($refer==''){
			   curl_setopt($process, CURLOPT_AUTOREFERER, 1);
		  } else {
			   curl_setopt($process, CURLOPT_REFERER, $refer);			  
		  }
		  curl_setopt($process, CURLOPT_HEADER, 1);
          curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
          curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
          curl_setopt($process, CURLOPT_ENCODING , $this->compression);
          curl_setopt($process, CURLOPT_TIMEOUT, 200);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXYUSERPWD, $this->proxyAuther);
          curl_setopt($process, CURLOPT_POSTFIELDS, $data);
          curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
		  curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($process, CURLOPT_FOLLOWLOCATION, 5);
          curl_setopt($process, CURLOPT_POST, 1);
		  curl_setopt($process, CURLOPT_VERBOSE, 1);
          $return = curl_exec($process);
          

		  $head = curl_getinfo($process, CURLINFO_HTTP_CODE);
			if(!curl_errno($process))
			{
					$code = curl_getinfo( $process );
					$dump = var_export($code, true);
					$fname = 'dump'.$this->callCount++.'.txt';
					//$this->writeToFile($fname,trim($dump));

			}

			curl_close($process);
          return $head.$return;
     }

	 function post1($url,$data,$refer='') {
          $process = curl_init($url);
		  if ($refer==''){
			   curl_setopt($process, CURLOPT_AUTOREFERER, 1);
		  } else {
			   curl_setopt($process, CURLOPT_REFERER, $refer);			  
		  }
		  curl_setopt($process, CURLOPT_HEADER, 1);
          curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
          curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
          if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
          curl_setopt($process, CURLOPT_ENCODING , $this->compression);
          curl_setopt($process, CURLOPT_TIMEOUT, 200);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
          if ($this->proxy) curl_setopt($process, CURLOPT_PROXYUSERPWD, $this->proxyAuther);
          curl_setopt($process, CURLOPT_POSTFIELDS, $data);
          curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
		  curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($process, CURLOPT_FOLLOWLOCATION, 5);
          //curl_setopt($process, CURLOPT_USERPWD, "info@trasteverino.it:katcafe");
		 // curl_setopt($process, CURLOPT_HTTPHEADER, "Authorization: Basic " . base64_encode('info@trasteverino.it' . ":" . 'katcafe'));
          curl_setopt($process, CURLOPT_POST, 1);
		  curl_setopt($process, CURLOPT_VERBOSE, 1);
          $return = curl_exec($process);
          

		  $head = curl_getinfo($process, CURLINFO_HTTP_CODE);
			if(!curl_errno($process))
			{
					$code = curl_getinfo( $process );
					$dump = var_export($code, true);
					$fname = 'dump'.$this->callCount++.'.txt';
					//$this->writeToFile($fname,trim($dump));

			}
			curl_close($process);

          return $head.$return;
     }
	function getCookies() {
		return $this->extractCookies(file_get_contents($this->cookie_file));
	}
	function getCookieValue($key) {
		$cookies=$this->extractCookies(file_get_contents($this->cookie_file));
		$len=count($cookies);
		$str=array();
		if($len>0)
		{
			for ($i=0;$i<$len;$i++)
			{
				if(trim($cookies[$i]['name'])==trim($key))
					$str=$cookies[$i]['value'];
			}
			
			return $str;
		}
	 }	
	function extractCookies($string) {
		$cookies = array();
		$lines = explode("\n", $string);
		// iterate over lines
		foreach ($lines as $line) {
			// we only care for valid cookie def lines
			if (isset($line[0]) && substr_count($line, "\t") == 6) {
				// get tokens in an array
				$tokens = explode("\t", $line);
				// trim the tokens
				$tokens = array_map('trim', $tokens);
				$cookie = array();
				// Extract the data
				$cookie['domain'] = $tokens[0];
				$cookie['flag'] = $tokens[1];
				$cookie['path'] = $tokens[2];
				$cookie['secure'] = $tokens[3];
				// Convert date to a readable format
				$cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);
				$cookie['name'] = $tokens[5];
				$cookie['value'] = $tokens[6];
				// Record the cookie.
				$cookies[] = $cookie;
			}
		}
		return $cookies;
	}
	 
	function writeToFile($fileName,$data) {
		$fh = fopen($fileName, 'w');
		fwrite($fh, $data);
		fclose($fh);
	}    
     function error($error) {
          echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
          die;
     }
}
?>