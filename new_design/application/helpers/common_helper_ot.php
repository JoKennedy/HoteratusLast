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
function get_data($table,$where)
{
	$ci =& get_instance();
	return $ci->db->where($where)->get($table);
}
function get_Users($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("manage_users");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function user_id()
{
	$ci =& get_instance();
	return $ci->session->userdata('user_id');
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
	if(!$from)
	$from=admin_config()->email_id;
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
	'smtp_user' => 'vivek.developer@osiztechnologies.com',
	'smtp_pass' => 'iamnotlosero',
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
		$data->string="Deactive";
		$data->new_status=1;
	}
	return $data;
}
function time_ago($date_time)
{
		$date2= date_create(date('Y-m-d H:i:s'));
		$date1= date_create($date_time);
		$diff=date_diff($date1,$date2);
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
//munish
function get_Emails($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("Email_Templates");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_privacy($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("privacy");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_About($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("About");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_faq($result_mode=""){
	$ci =& get_instance();
	$result=$ci->db->get("faq");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_terms($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("terms");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}
function get_membership($result_mode="")
{
	$ci =& get_instance();
	$result=$ci->db->get("plan_details");
	if($result_mode=="array")
		return $result->result_array();
	else
		return $result->result();
}


?>