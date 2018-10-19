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
			
				$this->db->query("insert into test(testcol) values('dd')");
 			 echo 'si';

		}



}

 ?>
