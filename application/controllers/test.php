<?php 

	require_once(APPPATH.'controllers/tokenex.php');

	$tokenex = new tokenex();
	print_r($tokenex->Detokenizar('5181006650061267')); 

die;

 ?>