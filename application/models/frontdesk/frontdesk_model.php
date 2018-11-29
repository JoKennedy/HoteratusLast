<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class frontdesk_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //date_default_timezone_set('Asia/kolkata');
    }


    public function  deleteExtras($extra_id,$reser_id,$channel_id,$detail)
    {
    	
    }

}