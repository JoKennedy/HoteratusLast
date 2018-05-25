<?php  
/* helper for common functions */
function count_post_categories($id)
{
		$ci =& get_instance();
		$data['topics']=$ci->discussion_model->count_discussions($id);
		$data['posts']=$ci->discussion_model->count_posts($id);
		return $data;
}
function get_location($ip=NULL)
{
	$api_key='c5cc2cb2dbf33bc57cd8f0b02edfe0ad9f6b7097ad9a1780903f87d3477898a9';
	$ci =& get_instance();
	if($ip!=NULL)
	{
		if($ci->input->valid_ip($ip))
		{
			// Initialize session and set URL.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.ipinfodb.com/v3/ip-city/?key=$api_key&ip=$ip&format=json");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Set so curl_exec returns the result instead of outputting it.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Get the response and close the channel.
			$response = curl_exec($ch);
			if(curl_errno($ch))
			{
				return false;
			}
			curl_close($ch);
			return json_decode($response);
		}
		else
		return false;
	}
	else
	return false;
	
}
function get_agent()
{
	$ci =& get_instance();
	$ci->load->library('user_agent');
	if ($ci->agent->is_browser())
	{
	$agent = $ci->agent->browser().' '.$ci->agent->version();
	}
	elseif ($ci->agent->is_robot())
	{
	$agent = $ci->agent->robot();
	}
	elseif ($ci->agent->is_mobile())
	{
	$agent = $ci->agent->mobile();
	}
	else
	{
	$agent = 'Unidentified User Agent';
	}
	return $agent;
}
function get_data($table,$where=FALSE,$select=FALSE,$limit=FALSE)
{
	$ci =& get_instance();
	if($where)
	$ci->db->where($where);
	if($select)
	$ci->db->select($select);
	if($limit)
	$ci->db->limit($limit);
	return $ci->db->get($table);
}
function get_Users($result_mode="",$where=FALSE)
{
	$ci =& get_instance();
	if($where)
	$ci->db->where($where);
	$result=$ci->db->get("manage_users");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function user_id()
{
	$ci =& get_instance();
	if(admin_id()!='' && admin_type()=='1')
	{
		if($ci->session->userdata('ad_user_id')!='')
		{
			return $ci->session->userdata('ad_user_id');
		}
		else
		{
			return $ci->session->userdata('ch_user_id');
		}
	}
	else
	{
		return $ci->session->userdata('ch_user_id');
	}
}
function user_type()
{
	$ci =& get_instance();
	return $ci->session->userdata('ch_user_type');
}
function admin_id()
{
	$ci =& get_instance();
	return $ci->session->userdata('logged_user');
}
function admin_type()
{
	$ci =& get_instance();
	return $ci->session->userdata('admin_type');
}
function hotel_id()
{
	$ci =& get_instance();
	if(admin_id()!='' && admin_type()=='1')
	{
		if($ci->session->userdata('ad_hotel_id')!='')
		{
			return $ci->session->userdata('ad_hotel_id');
		}
		else
		{
			return $ci->session->userdata('ch_hotel_id');
		}
	}
	else
	{
		return $ci->session->userdata('ch_hotel_id');
	}
}
function owner_id()
{
	$ci =& get_instance();
	return $ci->session->userdata('owner_id');
}

function current_user_type()
{
	if(admin_id()!='' && admin_type()=='1')
	{
		return user_id();
	}
	else
	{
		if(user_type()=='1')
		{
			return user_id();
		}
		elseif(user_type()=='2')
		{
			return owner_id();
		}
	}
}

function user_view()
{
	if(admin_id()!='')
	{
		return $user_view=array('');
	}
	else
	{
		$redirect_access = get_data(ASSIGN,array('owner_id'=>owner_id(),'user_id'=>user_id(),'hotel_id'=>hotel_id()))->row();
		$access               = (array)json_decode($redirect_access->access);
		$user_access_view[]='';
		$user_access_edit[]='';
		foreach($access as $photo_id=>$photo_obj)
		{
			if(!empty($photo_obj))
			{
				$photo = (array)$photo_obj;
				if(isset($photo['view'])!='')
				{
					$user_access_view[]=$photo_id;
				}
				else
				{
					$user_access_view[]='';
				}
				if(isset($photo['edit'])!='')
				{
					$user_access_edit[]=$photo_id;
				}
				else
				{
					$user_access_edit[]='';
				}
			}
		}
		$user_view = array_filter($user_access_view);
		$user_edit = array_filter($user_access_edit);
		return $user_view;
	}
}
function user_edit()
{
	if(admin_id()!='')
	{
		return $user_view=array('');
	}
	else
	{
		$redirect_access = get_data(ASSIGN,array('owner_id'=>owner_id(),'user_id'=>user_id(),'hotel_id'=>hotel_id()))->row();
		$access               = (array)json_decode($redirect_access->access);
		$user_access_view[]='';
		$user_access_edit[]='';
		foreach($access as $photo_id=>$photo_obj)
		{
			if(!empty($photo_obj))
			{
				$photo = (array)$photo_obj;
				if(isset($photo['view'])!='')
				{
					$user_access_view[]=$photo_id;
				}
				else
				{
					$user_access_view[]='';
				}
				if(isset($photo['edit'])!='')
				{
					$user_access_edit[]=$photo_id;
				}
				else
				{
					$user_access_edit[]='';
				}
			}
		}
		$user_view = array_filter($user_access_view);
		$user_edit = array_filter($user_access_edit);
		return $user_edit;
	}
}

function all_reservation_count($type,$bdate,$user_hotel_id)
{
	$ci =& get_instance();
	$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
	$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
	$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id()));
	$query = $ci->db->get(ALL.' as A');
	if($query)
	{
		$all_api_new_book = $query->result_array();
	}
	$total_reser_count = 0;
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);
		$select = explode(',',$fetch_query_count);
		if($channel_id==2)
		{
			$hotel_id 	=	'hotel_hotel_id';
		}
		else
		{
			$hotel_id = 'hotel_id';
		}
		if($type=='reserve')
		{
			$chaReserCheckCount = $ci->db->select($select[0])->from($channel_table_name)->where_not_in($select[2],$select[13])->where(array('DATE_FORMAT('.$select[1].',"%Y-%m-%d")'=>$bdate,$hotel_id=>$user_hotel_id,$select[17]=>$hotel_channel_id))->count_all_results();
			$total_reser_count =$total_reser_count+$chaReserCheckCount;
		}
		else if($type=='cancel')
		{
			if($channel_id!=11 && $channel_id!=2)
			{
				$where = $select[1];
			}
			elseif($channel_id==11)
			{
				$where = $select[12];	
			}
			elseif($channel_id==2)
			{
				$where = 'modified_at';	
			}
			
			$chaReserCheckCount = $ci->db->select($select[0])->from($channel_table_name)->where(array('DATE_FORMAT('.$where.',"%Y-%m-%d")'=>$bdate,$hotel_id=>$user_hotel_id,$select[2]=>$select[13],$select[17]=>$hotel_channel_id))->count_all_results();
			$total_reser_count =$total_reser_count+$chaReserCheckCount;
		}
		else if($type=='arrival')
		{
			$chaReserCheckCount = $ci->db->select($select[0])->from($channel_table_name)->where_not_in($select[2],$select[13])->where(array('str_to_date('.$select[5].',"%Y-%m-%d")'=>$bdate,$hotel_id=>$user_hotel_id,$select[17]=>$hotel_channel_id))->count_all_results();
			$total_reser_count =$total_reser_count+$chaReserCheckCount;
		}
		else if($type=='depature')
		{
			$chaReserCheckCount = $ci->db->select($select[0])->from($channel_table_name)->where_not_in($select[2],$select[13])->where(array('str_to_date('.$select[6].',"%Y-%m-%d")'=>$bdate,$hotel_id=>$user_hotel_id,$select[17]=>$hotel_channel_id))->count_all_results();
			$total_reser_count =$total_reser_count+$chaReserCheckCount;
		}
		else if($type=='modify')
		{
			$chaReserCheckCount = $ci->db->select($select[0])->from($channel_table_name)->where(array($hotel_id=>$user_hotel_id,'DATE_FORMAT('.$select[15].',"%Y-%m-%d")'=>date('Y-m-d'),$select[2]=>$select[16],$select[17]=>$hotel_channel_id))->count_all_results();
			$total_reser_count =$total_reser_count+$chaReserCheckCount;
		}
		/* echo $ci->db->last_query().'<br>'; */
	}
	return $total_reser_count;
}

function all_reservation_result($type,$bdate,$user_id="",$hotelid="")
{
	if($user_id == "")
	{
		$user_id = current_user_type();
	}
	if($hotelid == "")
	{
		$hotelid = hotel_id();
	}
	$ci =& get_instance();
	$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
	$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
	$ci->db->where(array('C.user_id'=>$user_id,'C.hotel_id'=>$hotelid));
	$query = $ci->db->get(ALL.' as A');
	if($query)
	{
		$all_api_new_book = $query->result_array();
	}
	
	$chaReserCheckCount = array();
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);
		if(( $channel_id==2 && ( $xml_type==1 || $xml_type==2) ) || $channel_id!=2)
		{
			$select = explode(',',$fetch_query_count);
			if($channel_id==2)
			{
				$hotel_id = 'hotel_hotel_id';
				$bk_details = 'AND hotel_id='.$hotel_channel_id.'';
			}
			else
			{
				$hotel_id = 'hotel_id';
				$bk_details = '';
			}
			if($type=='reserve')
			{
				$where_field	= 	$select[1];
				$status 		= 	$select[13];
				$status_check	=	"!";
			}
			else if($type=='arrival')
			{
				$where_field 	= 	$select[5];
				$status 	 	= 	$select[13];
				$status_check	=	"!";
			}
			else if($type=='depature')
			{
				$where_field 	= 	$select[6];
				$status 		= 	$select[13];
				$status_check	=	"!";
			}
			else if($type=='modify')
			{
				$where_field 	= 	$select[15];
				$status 		= 	$select[16];
				$status_check	=	"";
			}
			if($channel_id == 1)
			{
				$guest	=	"CONCAT(givenName , middleName , surname)";
			}
			else
			{
				$guest	=	$select[3];
			}
			
			if($type=='cancel')
			{
				if($channel_id!=11 && $channel_id!=2)
				{
					$where_field = $select[1];
				}
				elseif($channel_id==11)
				{
					$where_field = $select[12];	
				}
				elseif($channel_id==2)
				{
					$where_field = 'modified_at';	
				}
				$cahquery = $ci->db->query('SELECT `'.$select[0].'` as reservation_id, DATE_FORMAT('.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT('.$select[6].',"%d/%m/%Y") as end_date ,channel_id , '.$select[1].' as booking_date, '.$select[12].' as modified_date, '.$select[10].' , '.$guest.' as guest_name , '.$select[7].' as reservation_code, DATEDIFF('.$select[6].','.$select[5].') AS num_nights , '.$select[9].' as price , '.$select[4].' ,'.$select[2].'  as status , '.$select[8].' as CURRENCY , '.$select[11].' as roomtypeId , '.$select[12].' as rateplanid , '.$select[15].' FROM ('.$channel_table_name.') WHERE `user_id` = "'.$user_id.'" AND '.$select[2].' = "'.$select[13].'" AND '.$hotel_id.' = "'.$hotelid.'" AND DATE_FORMAT('.$where_field.',"%Y-%m-%d")= "'.$bdate.'" '.$bk_details.' ORDER BY '.$select[0].'');
			}
			else
			{
				$cahquery = $ci->db->query('SELECT `'.$select[0].'` as reservation_id, DATE_FORMAT('.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT('.$select[6].',"%d/%m/%Y") as end_date ,channel_id , '.$select[1].' as booking_date, '.$select[10].' , '.$guest.' as guest_name , '.$select[7].' as reservation_code, DATEDIFF('.$select[6].','.$select[5].') AS num_nights , '.$select[9].' as price , '.$select[4].' ,'.$select[2].'  as status , '.$select[8].' as CURRENCY , '.$select[11].' as roomtypeId , '.$select[12].' as rateplanid , '.$select[15].' FROM ('.$channel_table_name.') WHERE `user_id` = "'.$user_id.'" AND '.$select[2].' '.$status_check.'= "'.$status.'" AND '.$hotel_id.' = "'.$hotelid.'" AND DATE_FORMAT('.$where_field.',"%Y-%m-%d")= "'.$bdate.'" '.$bk_details.' ORDER BY '.$select[0].'');
				
			}
			/* echo $ci->db->last_query().'<br>'; */
			if($cahquery)
			{
				$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());
			}
		}
		else
		{
			$chaReserCheckCount	=	array();
		}
	}
	return $chaReserCheckCount;
}

function all_reservation_result_admin()
{
	$ci =& get_instance();
	$all_api_new_book = get_data(ALL)->result_array();
	$chaReserCheckCount = array();
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);
		$select = explode(',',$fetch_query_count);
		if($channel_id==2)
		{
			$hotel_id = 'hotel_hotel_id';
		}
		else
		{
			$hotel_id = 'hotel_id';
		}
		$cahquery = $ci->db->query('SELECT `'.$select[0].'` as reservation_id, DATE_FORMAT('.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT('.$select[6].',"%d/%m/%Y") as end_date ,channel_id , '.$select[1].' as booking_date, '.$select[10].' , '.$select[3].' as guest_name , '.$select[7].' as reservation_code, DATEDIFF('.$select[6].','.$select[5].') AS num_nights , '.$select[9].' as price , '.$select[4].' ,'.$select[2].'  as status , '.$select[8].' as CURRENCY , '.$select[11].' as roomtypeId , '.$select[12].' as rateplanid , '.$select[15].' , '.$hotel_id.' as hotel_id, user_id as current_user_type FROM ('.$channel_table_name.') ORDER BY '.$select[0].'');
		/* echo $ci->db->last_query().'<br>'; */
		if($cahquery)
		{
			$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());
		}
	}
	return $chaReserCheckCount;
}

function all_ical_count($check_date)
{
	$ci =& get_instance();

	$manual=$ci->db->query('SELECT * FROM '.RESERVATION.' WHERE DATE_FORMAT(`start_date`,"%Y-%m-%d") <= "'.$check_date.'" AND DATE_FORMAT(`end_date`,"%Y-%m-%d") >= "'.$check_date.'" AND DATE_FORMAT(`end_date`,"%Y-%m-%d") !="'.$check_date.'"');
    $manaul_count =  $manual->num_rows();
	
	$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
	$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
	$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id()));
	$query = $ci->db->get(ALL.' as A');
	if($query)
	{
		$all_api_new_book = $query->result_array();
	}
	$total_reser_count = 0;
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);
		$select = explode(',',$ical_query);
		$det=$ci->db->query('SELECT * FROM '.$channel_table_name.' WHERE DATE_FORMAT('.$select[1].',"%Y-%m-%d") <= "'.$check_date.'" AND DATE_FORMAT('.$select[2].',"%Y-%m-%d") >= "'.$check_date.'" AND DATE_FORMAT('.$select[2].',"%Y-%m-%d") !="'.$check_date.'"');
		$chaIcalCheckCount =  $det->num_rows();
		$total_reser_count = $total_reser_count+$chaIcalCheckCount;
	}
	return $manaul_count + $total_reser_count;
}
function all_room_count()
{
	$ci =& get_instance();
	$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
	$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
	$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id()));
	$query = $ci->db->get(ALL.' as A');
	if($query)
	{
		$all_api_new_book = $query->result_array();
	}
	$total_room_count = 0;
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field); 
		$select = explode(',',$fetch_query_all);
		$chaRommCheckCount = $ci->db->select($select[0])->from($import_mapping_table)->where(array('hotel_id'=>hotel_id()))->count_all_results();
		$total_room_count  = $total_room_count+$chaRommCheckCount;
	}
	//echo $total_room_count;
	return $total_room_count;
}

function all_api_price($check_date,$section,$type,$channels='')
{
	//echo $section;
	$ci =& get_instance();
	/* echo 'channels = '.$channels;  */
	if($channels!='')
	{
		$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
		$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
		$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id(),'C.channel_id'=>$channels));
		$query = $ci->db->get(ALL.' as A');
		if($query)
		{
			$all_api_new_book = $query->result_array();
		}
	}
	else
	{
		$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
		$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
		$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id()));
		$query = $ci->db->get(ALL.' as A');
		if($query)
		{
			$all_api_new_book = $query->result_array();
		}
	}
	/* echo $ci->db->last_query().'<br>'; */
	if($type=='Price' && $section!='Average')
	{
		$total_price_count = 0;
	}
	elseif($type=='Country_Price' || $section=='Average')
	{
		$total_price_count=array();
	}
	
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);
		$select = explode(',',$reports_query);
		if($channel_id==2 && $section=="Revenue")
		{
			$hotel_id = 'hotel_hotel_id';
			$channel_table = "import_reservation_BOOKING";
			$cre_date = "date";
		}
		else
		{
			if($channel_id==2)
			{
				$hotel_id = 'hotel_hotel_id';
			}
			else
			{
				$hotel_id = 'hotel_id';
			}
			$channel_table = $channel_table_name;
			$cre_date = $select[0];
		}
		if($section=='Revenue')
		{
			$select_column = "SUM(".$select[1].")";
		}
		elseif($section=='Nights')
		{
			$select_column = "SUM(DATEDIFF(".$select[4].",".$select[3]."))";
		}
		elseif($section=='Average')
		{
			if($type=='Price')
			{
				$select_column = "SUM(".$select[1].") AS price , COUNT(".$select[5].") AS user_id";
			}
			elseif($type=='Country_Price')
			{
				$select_column = "SUM(".$select[1].") AS prices , COUNT(".$select[5].") AS user_id";
			}
		}
		elseif($section=='Reservation')
		{
			$select_column =	"COUNT(".$select[6].")";
		}
		elseif($section=='Guests')
		{
			$select_column =	"COUNT(".$select[7].")";
		}
		if($type=='Price')
		{
			if($section!='Average')
			{
				$select_column = $select_column.' AS price';
			}
			$det=$ci->db->query('SELECT '.$select_column.', `'.$cre_date.'` FROM '.$channel_table.' WHERE DATE_FORMAT('.$cre_date.',"%Y-%m-%d") = "'.$check_date.'" AND `user_id` = "'.current_user_type().'" AND '.$hotel_id.' = "'.hotel_id().'"');
			//echo $ci->db->last_query().'<br>';
			if($section!='Average')
			{
				$chaReportPriceCount =  $det->row('price');
			}
			else if($section=='Average')
			{
				$chaReportPriceCount =  $det->row_array();
			}
			if($chaReportPriceCount)
			{
				if($section!='Average')
				{
					$total_price_count = $total_price_count+$chaReportPriceCount;
				}
				else if($section=='Average')
				{
					$total_price_count = array_merge($total_price_count,$chaReportPriceCount);
				}
			}
		}
		elseif($type=='Country_Price')
		{
			if($section!='Average')
			{
				$select_column = $select_column.' AS prices';
			}
			if($channel_id!=2)
			{
				$det=$ci->db->query('SELECT '.$select_column.', `'.$cre_date.'` , `'.$select[2].'` as country_name FROM '.$channel_table.' WHERE DATE_FORMAT('.$cre_date.',"%Y-%m-%d") = "'.$check_date.'" AND `user_id` = "'.current_user_type().'" AND '.$hotel_id.' = "'.hotel_id().'"');
			}
			elseif($channel_id==2 && $section=='Revenue')
			{
				$det=$ci->db->query('SELECT '.$select_column.', `'.$cre_date.'` , `'.$select[2].'` as country_name FROM '.$channel_table.' WHERE DATE_FORMAT('.$cre_date.',"%Y-%m-%d") = "'.$check_date.'" AND `user_id` = "'.current_user_type().'" AND '.$hotel_id.' = "'.hotel_id().'"');
			}
			elseif($channel_id==2 && $section=='Nights' || $section=='Average' || $section=='Reservation' || $section=='Guests')
			{
				if($section=='Nights')
				{
					$selection = "SUM(DATEDIFF(R.departure_date,R.arrival_date)) AS prices";
				}
				else if($section=='Average')
				{
					$selection = "SUM(R.".$select[1].") AS prices , COUNT(R.".$select[5].") AS user_id";
				}
				else if($section=='Reservation')
				{
					$selection = "COUNT(R.".$select[6].") AS prices";
				}
				else if($section=='Guests')
				{
					$selection = "COUNT(R.".$select[7].") AS prices";
				}
				$det=$ci->db->query('SELECT '.$selection.', R.date_time , B.countrycode as country_name FROM import_reservation_BOOKING_ROOMS R JOIN import_reservation_BOOKING B ON R.reservation_id=B.id WHERE DATE_FORMAT(R.date_time,"%Y-%m-%d") = "'.$check_date.'" AND R.user_id = "'.current_user_type().'" AND R.hotel_hotel_id = "'.hotel_id().'"');
			}
			//echo $ci->db->last_query().'<br>';
			if($det)
			{
				$total_price_count = array_merge($total_price_count,$det->result_array());
			}
		}
			
	}
	return $total_price_count;
}

function all_confirmed_reserve($type,$param,$param2)
{
	$ci =& get_instance();
	$ci->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query,C.xml_type,C.hotel_channel_id');
	$ci->db->join(CONNECT.' as C','A.channel_id=C.channel_id');
	$ci->db->where(array('C.user_id'=>user_id(),'C.hotel_id'=>hotel_id()));
	$query = $ci->db->get(ALL.' as A');
	if($query)
	{
		$all_api_new_book = $query->result_array();
	}
	$total_confirm_count = 0;
	foreach($all_api_new_book as $table_field)
	{
		extract($table_field);

		$select = explode(',',$fetch_query_count);
		if($channel_id==2)
		{
			$hotel_id = 'hotel_hotel_id';
		}
		else
		{
			$hotel_id = 'hotel_id';
		}
		if($type=='today')
		{
			$det=$ci->db->query('SELECT * FROM `'.$channel_table_name.'` WHERE ( str_to_date(`'.$select[5].'`, "%Y-%m-%d") <= CURDATE() AND str_to_date(`'.$select[6].'`, "%Y-%m-%d") > CURDATE() ) AND '.$hotel_id.'='.$param.' AND '.$select[17].'="'.$hotel_channel_id.'" AND user_id='.$param2.' AND '.$select[2].'!="'.$select[13].'"');
			$chaConfirmCheckCount =  $det->num_rows();
			$total_confirm_count = $total_confirm_count+$chaConfirmCheckCount;
		}
		else if($type=='week')
		{
			$det=$ci->db->query('SELECT `'.$select[0].'` FROM `'.$channel_table_name.'` WHERE ( ( str_to_date(`'.$select[5].'`, "%Y-%m-%d") >= CURDATE() AND str_to_date(`'.$select[5].'`, "%Y-%m-%d") <= ADDDATE(CURDATE(), INTERVAL 7 DAY) ) OR ( str_to_date(`'.$select[6].'`, "%Y-%m-%d") > CURDATE() AND str_to_date(`'.$select[6].'`, "%Y-%m-%d") <= ADDDATE(CURDATE(), INTERVAL 7 DAY) ) ) AND '.$hotel_id.'='.$param.' AND '.$select[17].'="'.$hotel_channel_id.'" AND user_id='.$param2.' AND '.$select[2].'!="'.$select[13].'"');
			$chaConfirmCheckCount =  $det->num_rows();
			$total_confirm_count = $total_confirm_count+$chaConfirmCheckCount;
		}
		else if($type=='month')
		{
			$det=$ci->db->query('SELECT `'.$select[0].'` FROM `'.$channel_table_name.'` WHERE ( ( str_to_date(`'.$select[5].'`, "%Y-%m-%d") >= CURDATE() AND str_to_date(`'.$select[5].'`, "%Y-%m-%d") <= ADDDATE(CURDATE(), INTERVAL 30 DAY) ) OR ( str_to_date(`'.$select[6].'`, "%Y-%m-%d") > CURDATE() AND str_to_date(`'.$select[6].'`, "%Y-%m-%d") <= ADDDATE(CURDATE(), INTERVAL 30 DAY) ) ) AND '.$hotel_id.'='.$param.' AND '.$select[17].'="'.$hotel_channel_id.'" AND user_id='.$param2.' AND '.$select[2].'!="'.$select[13].'"');
			$chaConfirmCheckCount =  $det->num_rows();
			$total_confirm_count = $total_confirm_count+$chaConfirmCheckCount;
		}
	}
	/* echo $ci->db->last_query().'<br>'; */
	return $total_confirm_count;
}

function manual_reservation($property_id,$start_date,$end_date)
{
	$ci =& get_instance();
	$manualreservation = $ci->db->query("SELECT reservation_id, start_date, end_date ,channel_id , reservation_code as booking_number FROM `".RESERVATION."` WHERE ( (DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') >= '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') OR ( (DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') > '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') ) ) AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND room_id='".insep_decode($property_id)."' AND status NOT IN('Canceled') ORDER BY reservation_id DESC");
	if($manualreservation)
	{
		 return $manualreservation->result();
	}
	else
	{
		return false;
	}
}

function single_manual_reservation($property_id,$start_date,$end_date,$get_reser)
{
	$ci =& get_instance();
	$single_manual_reservation = $ci->db->query("SELECT members_count,price,guest_name,num_nights,num_rooms,room_id,reservation_id, start_date, end_date ,channel_id , reservation_code FROM `".RESERVATION."` WHERE ( (DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') >= '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(start_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') OR ( (DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') > '".$start_date."' AND DATE_FORMAT(STR_TO_DATE(end_date,'%d/%m/%Y'),'%Y-%m-%d') <= '".$end_date."') ) ) AND `reservation_id` = '".$get_reser."' AND user_id ='".current_user_type()."' AND hotel_id = '".hotel_id()."' AND room_id='".insep_decode($property_id)."' AND status NOT IN('Canceled') ORDER BY reservation_id DESC");

	if($single_manual_reservation)
	{
		 return $single_manual_reservation->row_array();
	}
	else
	{
		return false;
	}
}

function booking_hotel_id()
{
	$bk_details = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>2))->row()->hotel_channel_id;
	return $bk_details;
}


function user_membership($status)
{
	$ci =& get_instance();
	if($status==3)
	{
		$chk_status = 2;
	}
	else if($status==4)
	{
		$chk_status=1;
	}
	else if($status==5)
	{
		$chk_status=3;
	}
	else
	{
		$chk_status = $status;
	}
	
	if($chk_status != 2){
		$det=$ci->db->query('SELECT * FROM (`'.MEMBERSHIP.'`) WHERE `plan_status` = "'.$chk_status.'" AND `user_id`='.current_user_type().' AND `hotel_id`='.hotel_id().' AND `plan_to` >=CURDATE() ORDER BY user_buy_id DESC' );
	}else{
		$det=$ci->db->query('SELECT * FROM (`'.MEMBERSHIP.'`) WHERE `plan_status` = "'.$chk_status.'" AND `user_id`='.current_user_type().' AND `hotel_id`='.hotel_id().' ORDER BY user_buy_id DESC' );
	
	}
	if($status==1 || $status==2 || $status==5)
	{
		return  $det->num_rows();
	}
	else if($status==3 || $status==4)
	{
		return  $det->row_array();
	}
}

function get_all_channels_available()
{
		$channels = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
		if(count($channels)!=0)
		{
			return $channels;
		}
		else
		{
			return false;
		}
}

function check_plan($plan_id='',$type='')
{
	if($plan_id!='' && $type!='')   
	{
			$plan_details = get_data(TBL_PLAN,array('plan_id'=>$plan_id,'status'=>1))->row_array();
			if(count($plan_details)!=0)
			{
				if($type=='check')
				{
					return true;
				}
				elseif($type=='get')
				{
					return $plan_details;					
				}
			}
			else
			{
				return false;
			}
	}
	else
	{
		return false;
	}
}

function users($parameter='')
{
	$ci =& get_instance();
	$customer = $ci->session->userdata('customer');
	return $customer[$parameter];
}
function user_data($user_id="")
{
	$ci =& get_instance();
	if($user_id)
	$ci->db->where(array('id'=>$user_id));
	$user=$ci->db->get('Users')->row();
	return $user;
}
function get_mail_template($id)
{
	return get_data('Email_Templates',array( 'id'=>$id))->row_array();
}
function update_data($table,$data,$where)
{
	/*echo '<pre>';
	print_r ($data);
	 exit;*/
	$ci =& get_instance();
	if($ci->db->update($table, $data, $where))
	return true;
	else
	return false;
}
function insert_data($table,$data)
{
	$ci =& get_instance();
	if($ci->db->insert($table,$data)) 
	return true;
	else
	return false;
}
function delete_data($table,$where='')
{
	$ci =& get_instance();
	if($where)
	$ci->db->where($where);
	else return false;
	if($ci->db->delete($table,$where))
	return true;
	else
	return false;
}
function send_notification($from="",$to,$content='',$subject='')
{
	//if(!$from)
	//$from=$this->config->item('email');
	$ci =& get_instance();
	$ci->load->library('email');	
	$ci->email->clear();
	$config['protocol'] = 'sendmail';
	//$config['mailpath'] = '/usr/sbin/sendmail';
	$config['charset'] = 'iso-8859-1';
	$config['wordwrap'] = TRUE;
	//$config['smtp_host'] = $getsmtp_hostname; // SMTP Server Address.
	//$config['smtp_user'] = $getsmtp_hostusername; // SMTP Username.
	//$config['smtp_pass'] = $getsmtp_hostpass; // SMTP Password.
	//$config['smtp_port'] = '25'; // SMTP Port.
	//$config['smtp_timeout'] = '5'; // SMTP Timeout (in seconds)
	$config = Array(
	'protocol' => 'smtp',
	'smtp_host' => 'ssl://smtp.googlemail.com',
	'smtp_port' => 465,
	'smtp_user' => 'thirupathi@osiztechnologies.com',
	'smtp_pass' => 'thirupass',
	'mailtype'  => 'html', 
	'charset'   => 'iso-8859-1'
	);
	$ci->email->initialize($config);	
	$ci->email->set_newline("\r\n");
	$ci->email->to($to);
	$ci->email->from($from);
	$ci->email->subject($subject);
	$ci->email->message($content);
	if($ci->email->send())
	{
		$ci->email->clear();
		return true;
	}
	else {
		return false;
	}
}
function admin_config($field="")
{
	$ci =& get_instance();
	if($field)
	$ci->db->select($field); 
	return $ci->db->get('Site_Config')->row();
}
function site_config_array($field="")
{
	$ci =& get_instance();
	if($field)
	$ci->db->select($field); 
	return $ci->db->get('Site_Config')->row_array();
}
function get_status($status)
{
	$data=new stdClass();
	if($status==1)
	{
		$data->string="Active";
		$data->new_status=0;
	}
	else
	{
		$data->string="De-active";
		$data->new_status=1;
	}
	return $data;
}
function time_ago($date_time)
{
		echo $date2= date_create(date('Y-m-d H:i:s'));
		$date1= date_create($date_time);
		$diff=date_diff($date1,$date2);
		die;
		$left='0 sec ago';
		if($date1 < $date2)
		{
			if($diff->s != 0)
			$left = $diff->s.' sec ago';
			if($diff->i != 0)
			$left = $diff->i.' mins ago';
			if($diff->h != 0)
			$left = $diff->h.' hours ago';
			if($diff->d != 0)
			$left = $diff->d.' days ago';
			if($diff->m != 0)
			$left = $diff->m.' months ago';
			if($diff->y != 0)
			$left = $diff->y.' years ago';
		}
		
	return $left;
}
function lefttime($date3)
{
		$date1= date_create(date('Y-m-d H:i:s'));
		$date2= date_create($date3);
		$diff=date_diff($date1,$date2);
		if($date1 < $date2)
		{
			if($diff->s != 0)
			$left = $diff->s.' sec left';
			if($diff->i != 0)
			$left = $diff->i.' mins left';
			if($diff->h != 0)
			$left = $diff->h.' hours left';
			if($diff->d != 0)
			$left = $diff->d.' days left';
			if($diff->m != 0)
			$left = $diff->m.' months left';
			if($diff->y != 0)
			$left = $diff->y.' years left';
		}
		else
		{
			$left="expired";
		}
	return $left;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function upoad_file($index,$folder)
{
	//print_r($_FILES);
	$ci =& get_instance();
	$config['upload_path'] = $folder;
	$config['allowed_types'] = 'gif|jpg|png';
	$config['encrypt_name'] = true;	
	$config['max_size'] = '100';
	$config['max_width'] = '1024';
	$config['max_height'] = '768';
	$ci->load->library('upload');
	// Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
	$ci->upload->initialize($config);
	if($ci->upload->do_upload($index))
	{
		$updata=$ci->upload->data();
		return $updata['file_name'];
		//return true;
	}
	return false;
}
function sort_by_order ($a, $b)
{
    return $a->distance - $b->distance;
}
function get_car_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Car_details");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_airport_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Airports");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_mile_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Car_price_details");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_airport_booking_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Car_airport_booking");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_slider_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Car_slider");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_areaprice_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Area_to_area_price");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
//8/11
function get_shifts_details($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Shift_Type");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function merchant_id()
{
   $ci =& get_instance();
   return $ci->session->userdata('merchant_id');
}
function merchant_data($id=FALSE)
{
  $ci =& get_instance();
  if(!$id)
  $id=merchant_id();
  $ci->db->where('id',$id);
  return $ci->db->get("Merchants")->row();
}
function uri($no)
{
  $ci =& get_instance();
  
  return $ci->uri->segment($no);
}
function timing_html($timing=FALSE)
{
 
  $html="";
  $days=array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
  $html.='
	 <div class="form-group">
		<span class="col-md-12"><b>Note</b> : Your Restaurant is closed in particular day , Please select time <b> 00:00 </b></span>
	</div>
	<hr>
	';
  foreach($days as $day){
  $first_opening=$first_close=$second_opening=$second_close="";
  if(isset($timing->$day->first_opening)) $first_opening=$timing->$day->first_opening;
  if(isset($timing->$day->first_opening)) $first_close=$timing->$day->first_close;
  if(isset($timing->$day->first_opening)) $second_opening=$timing->$day->second_opening;
  if(isset($timing->$day->first_opening)) $second_close=$timing->$day->second_close;
  
  			$html.='<div class="form-group">
				<label class="control-label col-md-3">'.ucfirst($day).'</label>
				<div class="col-md-9">
					<div class="input-group conl-md-6">
						<input type="text" name="'.$day.'_delivery_opening" value="'.$first_opening.'" title="'.$day.' delivery opening" class="form-control timepicker timepicker-24">
						<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
						</span>
					</div>
					<div class="input-group conl-md-6">
						<input type="text" name="'.$day.'_delivery_closing" value="'.$first_close.'" title="'.$day.' delivery closing" class="form-control timepicker timepicker-24">
						<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
						</span>
					</div>
					<div class="input-group conl-md-6">
						<input type="text" name="'.$day.'_pickup_opening" value="'.$second_opening.'" title="'.$day.' pickup opening" class="form-control timepicker timepicker-24">
						<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
						</span>
					</div>
					<div class="input-group conl-md-6">
						<input type="text" name="'.$day.'_pickup_closing" value="'.$second_close.'" title="'.$day.' pickup closing" class="form-control timepicker timepicker-24">
						<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
						</span>
					</div>
				</div>
			</div>';
  }
  return $html;
}

function notify_element($class,$message,$link=FALSE)
{
	 
	 $notify_element='<div class="alert alert-block alert-'.$class.' fade in">
	 <button type="button" class="close" data-dismiss="alert">X</button>
		<h4 class="alert-heading">Success!</h4>
	 <p>'.$message.'</p>';
	 if($link)
	 {
	  $notify_element.='<p>
		 <a class="btn green" href="'.$link.'">
			  Click Here
		 </a>
	 </p>';
	 }
	 $notify_element.='</div>';
	 return $notify_element;
							
							
}

function insep_encode($value){
	$skey= "SuPerEncKey2010a";
	if(!$value){return false;}
	$text = $value;
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
	return trim(safe_b64encode($crypttext));
}

function insep_decode($value){
	$skey= "SuPerEncKey2010a";
	if(!$value){return false;}
	$crypttext = safe_b64decode($value);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
	return trim($decrypttext);
}
 function safe_b64encode($string) {
	
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

	function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
	
	function format_filename($filename){
		$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
		$newname = str_replace(".","_",$withoutExt);
		$extensionss = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = $newname.".".$extensionss;
		$filename = preg_replace('/[^A-Za-z0-9\.\']/', '_', $filename);
		return $filename;
	}
	
	function unsecure( $q ) 
	{
		$ser=base64_decode(str_replace(array('-', '_'), array('+', '/'), $q));
		return unserialize($ser); 
	}
	
	function secure( $q ) 
	{
		$ser=serialize($q);
    	return str_replace(array('+', '/'), array('-', '_'), base64_encode($ser));
	}
	
	function gestatus($status)
	{
		$data=new stdClass();
		if($status=="fail")
		{
		$data->string="success";
		$data->new_status="success";
		}
		else
		{
		$data->string="fail";
		$data->new_status="fail";
		}
		return $data;
	}
	function sumtransactionss($result_mode)
	{
		$ci =& get_instance();
		$ci->db->select_sum('amount');
		$ci->db->where("action_type",$result_mode);
                $ci->db->where("payment_status",'success');
		return $ci->db->get("trans_history");
	}
	function admininstr($id)
	{
		$ci =& get_instance();
		$ci->db->where('product_id',$id);
		return $ci->db->get('inst');
	}
	function get_Emails($result_mode="")
	{
		$ci =& get_instance();
		$ci->db->order_by('id','desc');
		$result=$ci->db->get("Email_Templates");
		if($result_mode=="array")
			return $result->result_array();
		else
			return $result->result();
	}
	
	function format_date($date){	
	if ($date != '' && $date != '0000-00-00')
	{
		$d	= explode('-', $date);
	
		$m	= Array(
		'January'
		,'February'
		,'March'
		,'April'
		,'May'
		,'June'
		,'July'
		,'August'
		,'September'
		,'October'
		,'November'
		,'December'
		);
	
		return $m[$d[1]-1].' '.$d[0]; 
	}
	else
	{
		return false;
	}
	}
	
	function format_date1($date){	
	if ($date != '' && $date != '0000-00-00')
	{
		$d	= explode('-', $date);
	
		$m	= Array(
		'January'
		,'February'
		,'March'
		,'April'
		,'May'
		,'June'
		,'July'
		,'August'
		,'September'
		,'October'
		,'November'
		,'December'
		);
	
		return $m[$d[1]-1]; 
	}
	else
	{
		return false;
	}
	}
	function get_Partners($result_mode="")
	{
		$ci =& get_instance();
		$result=$ci->db->get("partners");
		if($result_mode=="array")
			return $result->result_array();
		else
			return $result->result();
	}
	function _datebetween($start,$end)
	{
		$startTimeStamp = strtotime($start);
		$endTimeStamp = strtotime($end);
		$timeDiff = abs($endTimeStamp - $startTimeStamp);
		$numberDays = $timeDiff/86400;
		return $numberDays = intval($numberDays);
	}	
	
	//07/06/2016 Vijikumar
	
	function user_buy_channel()
	{
		$ci =& get_instance();
		$chk_status = 1;
		$det=$ci->db->query('SELECT * FROM (`'.MEMBERSHIP.'`) WHERE `plan_status` = "'.$chk_status.'" AND `user_id`='.current_user_type().' AND `hotel_id`='.hotel_id().' AND `plan_to` >=CURDATE() ORDER BY user_buy_id DESC' );
		if($det)
		{
			$result =  $det->row_array();
			return explode(',',$result['connect_channels']);
		}
	}
	
	function cleanArray($array)
    {
		$ci =& get_instance();
        if (is_array($array))
        {
            foreach ($array as $key => $sub_array)
            {
                $result = cleanArray($sub_array);
                if ($result === false)
                {
                    unset($array[$key]);
                }
                else
                {
                    $array[$key] = $result;
                }
            }
        }

        if (empty($array))
        {
            return false;
        }

        return $array;
    }

    // Start Gayathri 24/08/2016

    function user_can_connect_channel()
	{
		$ci =& get_instance();
		$chk_status = 1;
		$det=$ci->db->query('SELECT * FROM (`'.MEMBERSHIP.'`) WHERE `plan_status` = "'.$chk_status.'" AND `user_id`='.current_user_type().' AND `hotel_id`='.hotel_id().' AND `plan_to` >=CURDATE() ORDER BY user_buy_id DESC' );
		if($det)
		{
			$result = $det->row_array();
			$connected_channel = explode(',', $result['connect_channels']);
			if(count($connected_channel) < $result['total_channels'])
			{
				return true;
			}else{
				return false;
			}
		}
	}

	function update_channel($channel_id,$status)
	{
		$ci =& get_instance();
		$chk_status = 1;
		$det=$ci->db->query('SELECT * FROM (`'.MEMBERSHIP.'`) WHERE `plan_status` = "'.$chk_status.'" AND `user_id`='.current_user_type().' AND `hotel_id`='.hotel_id().' AND `plan_to` >=CURDATE() ORDER BY user_buy_id DESC' );
		if($det)
		{
			$result =  $det->row_array();
			$connected_channel = explode(',',$result['connect_channels']);
			$disconnected_channel = explode(',', $result['disconnect_channels']);
			if($status == "enabled")
			{
				if(!in_array($channel_id, $connected_channel))
				{
					array_unshift($connected_channel, $channel_id);				
				}
				if(in_array($channel_id, $disconnected_channel))
				{
					if(($key = array_search($channel_id, $disconnected_channel)) !== false)
					{
						unset($disconnected_channel[$key]);				
					}
				}
			}else if($status == "disabled")
			{
				if(in_array($channel_id, $connected_channel))
				{
					if(($key = array_search($channel_id, $connected_channel)) !== false)
					{
						unset($connected_channel[$key]);				
					}			
				}
				if(!in_array($channel_id, $disconnected_channel))
				{
					array_unshift($disconnected_channel, $channel_id);	
				}
			}
			
			$data['connect_channels'] = rtrim(implode(',', $connected_channel),',');
			$data['disconnect_channels'] = rtrim(implode(',', $disconnected_channel),',');
			update_data(MEMBERSHIP,$data,array('plan_status'=>1,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()));
			return true;
		}
		return false;
	}
	
	function fetchColumn($table,$array_keys)
	{
		$ci =& get_instance();
		$result = $ci->db->query("SHOW COLUMNS from ".$table);
		if($result)
		{
			$query = $result->result();
			foreach($query as $value)
			{
				$column_name[]=$value->Field;
			}
			$result=array_diff($array_keys,$column_name);
			if($result)
			{
				foreach($result as $column)
				{
					$ci->db->query("ALTER TABLE $table ADD $column VARCHAR( 255 ) NOT NULL");
				}
			}
		}
	}
	
	function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	function getExpediaRoom($roomtypeid,$rateplan_id,$user_id,$hotel_id)
    {
        $ci =& get_instance();
        $ci->db->where('user_id',$user_id);
        $ci->db->where('hotel_id',$hotel_id);
        $ci->db->where('roomtype_id',$roomtypeid);
        $ci->db->where('rate_type_id',$rateplan_id);
        $details = $ci->db->get("import_mapping")->row();
        if(count($details) == 0)
        {
            $ci->db->where('user_id',$user_id);
            $ci->db->where('hotel_id',$hotel_id);
            $ci->db->where('roomtype_id',$roomtypeid);
            $ci->db->where('rateplan_id',$rateplan_id);
            $details = $ci->db->get("import_mapping")->row();
        }
        if($details->rateAcquisitionType != 'Linked' && $details->rateAcquisitionType != 'Derived')
        {
            $data['roomtypeId']         =   $details->roomtype_id;
            $data['rateplanid']         =   $details->rate_type_id;
            return $data;
        }else{
            if($details->rateAcquisitionType == "Linked")
            {
                $mapdetails = get_data("import_mapping",array('rate_type_id'=>$details->rateplan_id,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row();
                $rateplanid = $details->rateplan_id;
                $roomtypeid = $mapdetails->roomtype_id;
                $data = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
            }else if($details->rateAcquisitionType == "Derived"){
                $roomtypeid = $roomtypeid;
                $type = get_data("import_mapping",array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$details->rateplan_id,'user_id'=>$user_id,'hotel_id'=>$hotel_id))->row();
                $rateplanid = $details->rateplan_id;
                $data = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);

            }
            $data = getExpediaRoom($roomtypeid,$rateplanid,$user_id,$hotel_id);
            
            return $data;
        }/*
        return $data;*/
    }
	
	function lang_url()
	{
		$ci =& get_instance();
		return site_url().$ci->lang->lang().'/';
	}

?>