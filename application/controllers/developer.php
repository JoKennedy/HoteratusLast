<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class developer extends Front_Controller {



 	  public function __construct()
    {
        parent::__construct();

    }
    public function task()
    {
      is_login();
      $hotelid=hotel_id();
      $data['page_heading'] = 'Developer Task';
      $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
      $data= array_merge($user_details,$data);
      $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
      $this->views('developer/task',$data);
    }
}
