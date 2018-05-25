<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');
class Channel extends Front_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->session->keep_flashdata('profile_error');
		$data["content"]["something"] = "bla bla";
		$this->dynamic_cms = get_data('other_cms',array('type'=>1),'id,seo_url,title')->result_array();  
		//load base libraries, helpers and models
        //if SSL is enabled in config force it here.
       /* if (config_item('ssl_support') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'))
		{
			$CI =& get_instance();
			$CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
			redirect($CI->uri->uri_string());
		}*/
	}
	function is_login()
	{		
		if(!user_id())
		redirect(base_url());
		return;
	}
	function is_admin()
	{	
		if(!admin_id())
		redirect(base_url());
		return;
	}
	function mailsettings_new()
	{ 	
		$config['protocol'] = 'sendmail';
	
		//$config['mailpath'] = '/usr/sbin/sendmail';
	
		$config['charset'] = 'iso-8859-1';
	
		$config['wordwrap'] = TRUE;
	
		$config = Array(
	
			'protocol' => 'smtp',
	
			'smtp_host' => 'server1.hotelavailabilities.com',
	
			'smtp_port' => 465,
	
			'smtp_user' => ' info@hotelavailabilities.com',
	
			'smtp_pass' => '4@-)nrunX~Sz',
	
			'mailtype' => 'html',
	
			'charset' => 'iso-8859-1'
	
		);
	
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");	
	
	} 
	function mailsettings()
	{ 	  
		$this->load->library('email');  
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);	 	    
	} 
	public function index()
	{
		//if(!user_id())
		//{
		$data['page_heading'] = 'Home';
		$this->view('channel/index',$data);
		//}
		//else
		//{
	//		redirect('channel/dashboard','refresh');
	///	}
	}
	
	/*Start register & my account exist fumctions \*/
	
	function username($user_id,$username)
	{
		if($user_id)
		{
			$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('user_name', $username);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )
			{ 
			
				return TRUE;
			} 
			else 
			{ 
			
				return FALSE;
			}
		}
		else
		{
			//$this->db->where('delete_trash','0');
			$this->db->where('user_name', $username);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	
	}


   function login()
	{
		if(user_id()=='')
		{
		$data['page_heading'] = 'Login';
		$this->load->view('channel/new_login',$data);
	    }
	   else{
	   		redirect('channel/dashboard','refresh');
	   }
	}
	
	
	function register_username_exists($user_id='')
	{
	if (array_key_exists('user_name',$_POST)) 
	{
	if ( $this->username($user_id, $this->input->post('user_name')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	function add_username($user_id,$username)
	{
		if($user_id)
		{
			$this->db->where_not_in('user_id',insep_decode($user_id));
			//$this->db->where('delete_trash','0');
			$this->db->where('user_name', $username);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )
			{ 
			
				return TRUE;
			} 
			else 
			{ 
			
				return FALSE;
			}
		}
		else
		{
			//$this->db->where('delete_trash','0');
			$this->db->where('user_name', $username);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	
	}
	
	function add_username_exists($user_id='')
	{
	if (array_key_exists('user_name',$_POST)) 
	{
	if ( $this->add_username($this->input->post('username'), $this->input->post('user_name')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	function email_exists($user_id, $email)
	{
		if($user_id)
		{
			/*$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('email_address', $email);
			$query = $this->db->get(TBL_USERS);*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."' AND owner_id NOT IN ('".$user_id."')) as total ")->row_array();
			/*if( $query->num_rows() > 0 )*/
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' UNION ALL SELECT 		 				'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."') as total ")->row_array();
			//$this->db->where('email_address', $email);
			//$query = $this->db->get(TBL_USERS);
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	
	function register_email_exists($user_id='')
	{
	if (array_key_exists('email_address',$_POST)) 
	{
	if ( $this->email_exists($user_id, $this->input->post('email_address')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	function add_email_exists($user_id, $email)
	{
		if($user_id)
		{
			/*$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('email_address', $email);
			$query = $this->db->get(TBL_USERS);*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."' AND owner_id NOT IN ('".$user_id."')) as total ")->row_array();
			/*if( $query->num_rows() > 0 )*/
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' UNION ALL SELECT 		 				'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."') as total ")->row_array();
			//$this->db->where('email_address', $email);
			//$query = $this->db->get(TBL_USERS);
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	
	function adduser_email_exists($user_id='')
	{
	if (array_key_exists('email_address',$_POST)) 
	{
	if ( $this->add_email_exists(insep_decode($this->input->post('email')), $this->input->post('email_address')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	function phone_exists($user_id, $phone)
	{
		
		if($user_id)
		{
			/*$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('mobile', $phone);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$phone."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$phone."' AND owner_id NOT IN ('".$user_id."')) as total ")->row_array();
 			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			/*$this->db->where('mobile', $phone);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(TBL_USERS);*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$phone."' UNION ALL SELECT 		 				'property_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$phone."') as total ")->row_array();
			if( $query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	function register_phone_exists($user_id='')
	{
	if (array_key_exists('mobile',$_POST)) 
	{
	if ( $this->phone_exists($user_id,$this->input->post('mobile')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	

	function propertyn_exists($user_id, $property_name)
	{
		
		if($user_id)
		{
			$this->db->where_not_in('owner_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('property_name', $property_name);
			$query = $this->db->get(HOTEL);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$this->db->where('property_name', $property_name);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(HOTEL);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	function register_property_exists($user_id='')
	{
	if (array_key_exists('property_name',$_POST)) 
	{
	if ( $this->propertyn_exists($user_id,$this->input->post('property_name')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	/*Stop register & my account exist fumctions */
	/*Start add & edit exist fumctions for hotel\*/
	
	function property_m_exists($user_id, $hotel_id, $email)
	{
		if($user_id)
		{
			if(is_numeric($hotel_id))
			{
				if(User_Type()=='2')
				{
					$user_id = get_data(TBL_USERS,array('user_id'=>$user_id),'owner_id')->row();
					$user_id = $user_id->owner_id;
				}
				$count = $this->db->select('user_id')->from(TBL_USERS)->where(array('user_id'=>$user_id,'email_address'=>$email))->count_all_results();
				
				if($count==0)
				{
					$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."' AND hotel_id NOT IN ('".$hotel_id."')) as total ")->row_array();
				}
				else
				{
					$query['total']	=	0;
				}
			}
			else
			{
				if(User_Type()=='2')
				{
					$user_id = get_data(TBL_USERS,array('user_id'=>$user_id),'owner_id')->row();
					$user_id = $user_id->owner_id;
				}
				
				$count = $this->db->select('user_id')->from(TBL_USERS)->where(array('user_id'=>$user_id,'email_address'=>$email))->count_all_results();

				if($count==0)
				{
					$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."') as total ")->row_array();
				}
				else
				{
					
					$query['total']	=	0;
				}
			}
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE email_address = '".$email."') as total ")->row_array();
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	}
	
	function property_email_exists($user_id='',$hotel_id='')
	{
		if (array_key_exists('email_address',$_POST)) 
		{
			if ( $this->property_m_exists($user_id, insep_decode($this->input->post('hotel_id')), $this->input->post('email_address')) == TRUE ) 
			{
				echo json_encode(FALSE);
			} 
			else 
			{
				echo json_encode(TRUE);
			}
		}
	}
	
	function property_p_exists_old($user_id, $hotel_id, $phone)
	{
		
		if($user_id)
		{
			/*$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('mobile', $phone);
			$query = $this->db->get(HOTEL);
			if( $query->num_rows() > 0 )*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$phone."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$phone."' AND hotel_id NOT IN ('".$hotel_id."')) as total ")->row_array();
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			/*$this->db->where('mobile', $phone);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(TBL_USERS);*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$phone."' UNION ALL SELECT 		 				'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$phone."') as total ")->row_array();
			if( $query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	
	function property_p_exists($user_id, $hotel_id, $email)
	{
		if($user_id)
		{
			if(is_numeric($hotel_id))
			{
				if(User_Type()=='2')
				{
					$user_id = get_data(TBL_USERS,array('user_id'=>$user_id),'owner_id')->row();
					$user_id = $user_id->owner_id;
				}
				$count = $this->db->select('user_id')->from(TBL_USERS)->where(array('user_id'=>$user_id,'mobile'=>$email))->count_all_results();
				
				if($count==0)
				{
					$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$email."' AND hotel_id NOT IN ('".$hotel_id."')) as total ")->row_array();
				}
				else
				{
					$query['total']	=	0;
				}
			}
			else
			{
				if(User_Type()=='2')
				{
					$user_id = get_data(TBL_USERS,array('user_id'=>$user_id),'owner_id')->row();
					$user_id = $user_id->owner_id;
				}
				
				$count = $this->db->select('user_id')->from(TBL_USERS)->where(array('user_id'=>$user_id,'mobile'=>$email))->count_all_results();

				if($count==0)
				{
					$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$email."' AND user_id NOT IN ('".$user_id."') UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$email."') as total ")->row_array();
				}
				else
				{
					
					$query['total']	=	0;
				}
			}
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$email."' UNION ALL SELECT 'hotel_id' AS t2 FROM ".HOTEL." WHERE mobile = '".$email."') as total ")->row_array();
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	}
	
	
	function property_phone_exists($user_id='',$hotel_id='')
	{
	if (array_key_exists('mobile',$_POST)) 
	{
	if ( $this->property_p_exists($user_id, insep_decode($this->input->post('hotel_id')), $this->input->post('mobile')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	function hotel_exists($user_id,$hotel_id,$property_name)
	{
		
		if($user_id)
		{
			$this->db->where_not_in('hotel_id',$hotel_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('property_name', $property_name);
			$query = $this->db->get(HOTEL);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$this->db->where('property_name', $property_name);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(HOTEL);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	function register_hotel_exists($user_id='',$hotel_id='')
	{
	if (array_key_exists('property_name',$_POST)) 
	{
	if ( $this->hotel_exists($user_id,insep_decode($this->input->post('hotel_id')),$this->input->post('property_name')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	/*Stop add & edit exist fumctions for hotel\*/
	
	/*Start add & edit exist fumctions for room\*/
	
	function add_room_email_exists($user_id, $email)
	{
		if($user_id)
		{
			$this->db->where_not_in('property_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('email', $email);
			$query = $this->db->get(TBL_PROPERTY);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 	
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE email_address = '".$email."' UNION ALL SELECT 		 				'property_id' AS t2 FROM ".TBL_PROPERTY." WHERE email = '".$email."') as total ")->row_array();
			//$this->db->where('delete_trash','0');
			//$this->db->where('email_address', $email);
			//$query = $this->db->get(TBL_USERS);
			if($query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	function room_email_exists($user_id='')
	{
	if (array_key_exists('email',$_POST)) 
	{
	if ( $this->add_room_email_exists($user_id, $this->input->post('email')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
		
	function add_room_phone_exists($user_id, $phone)
	{
		
		if($user_id)
		{
			$this->db->where_not_in('property_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('mobile', $phone);
			$query = $this->db->get(TBL_PROPERTY);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			/*$this->db->where('mobile', $phone);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(TBL_USERS);*/
			$query = $this->db->query(" SELECT COUNT(*) AS total FROM ( SELECT 'user_id' AS t1 FROM ".TBL_USERS." WHERE mobile = '".$phone."' UNION ALL SELECT 		 				'property_id' AS t2 FROM ".TBL_PROPERTY." WHERE mobile = '".$phone."') as total ")->row_array();
			if( $query['total'] > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	}
	function room_phone_exists($user_id='')
	{
	if (array_key_exists('mobile',$_POST)) 
	{
	if ( $this->add_room_phone_exists($user_id,$this->input->post('mobile')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	/*Stop add & edit exist fumctions for room\*/
	
	/*Start login & forget exist fumctions for email\*/
	
	function log_email_exists($user_id, $email)
	{
		if($user_id)
		{
			
			$this->db->where_not_in('user_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('email_address', $email);
			$query = $this->db->get(TBL_USERS);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			
			$this->db->where('email_address', $email);
			//$this->db->where('delete_trash','0');
			$this->db->where('acc_active','1');
			$this->db->where('status	','1');
			$query = $this->db->get(TBL_USERS);
			if($query->num_rows() > 0 )
			{ 
				
				return FALSE;
			} 
			else 
			{ 
				
				return TRUE;
			}
		}
	
	}
	
	function forget_email_exists($user_id='')
	{
	if (array_key_exists('forget_email',$_POST)) 
	{
	if ( $this->log_email_exists($user_id, $this->input->post('forget_email')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	
	/*Stop login & forget exist fumctions for email\*/
	
	/*Start connect hotel id exist fumctions\*/
	
	function hotel_id_exists($channel_id,$hotel_channel_id)
	{
		$user_id = current_user_type();
		if($user_id)
		{
			$this->db->where_not_in('user_id',$user_id);
			$this->db->where('channel_id', insep_decode($channel_id));
			$this->db->where('hotel_channel_id', $hotel_channel_id);
			$query = $this->db->get(CONNECT);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
		else
		{
			$this->db->where('hotel_channel_id', $hotel_channel_id);
			$this->db->where('channel_id', insep_decode($channel_id));
			$query = $this->db->get(CONNECT);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	}
	function connect_hotel_id_exists($user_id='')
	{
		if (array_key_exists('hotel_channel_id',$_POST)) 
		{
			if ( $this->hotel_id_exists($this->input->post('hotel_channel'),$this->input->post('hotel_channel_id')) == TRUE ) 
			{
				echo json_encode(FALSE);
			} 
			else 
			{
				echo json_encode(TRUE);
			}
		}
	}
	/*Stop connect hotel id exist fumctions\*/
	
	function basics($mode,$id="")
	{
		switch ($mode)
		{
			case 'Login':
			$login_check = $this->channel_model->check_login();
			if($login_check=='0')
			{
				echo '0';	
			}
			elseif($login_check=='1')
			{
				echo '1';	
			}
			elseif($login_check=='2')
			{
				echo '2';	
			}
			elseif($login_check=='3')
			{
				echo '3';	
			}
			break;
			
			case 'ForgetPassword':
			extract($this->input->post());
			
			$seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); 
			shuffle($seed);  
			$rand = '';
			foreach (array_rand($seed, 10) as $k) 
			{
			  $rand .= $seed[$k];
			}
			$password = $rand;
			
			$t_hasher = new PasswordHash(8, FALSE);
			$hash = $t_hasher->HashPassword($password);
			
			$updata['password']=$hash;
			if(update_data(TBL_USERS,$updata,array('email_address'=>$forget_email)))
			{
				$user_info = get_data(TBL_USERS,array('email_address'=>$forget_email))->row();
						
				$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
				
				$get_email_info		=	get_mail_template('1');
				
				$subject		=	$get_email_info['subject'];
				$template		=	$get_email_info['message'];
				
				$this->mailsettings();
				$this->email->from($admin_detail->email_id);
				$this->email->to($forget_email);
				$data = array(
					'###USERNAME###'=>$user_info->fname,
					'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
					'###SITENAME###'=>$admin_detail->company_name,
					'###EMAIL###'=>$user_info->email_address,
					'###PASSWORD###'=>$password,
					);
				$subject_data = array(
					'###SITENAME###'=>$admin_detail->company_name,
				);
				$subject_new = strtr($subject,$subject_data);
				$this->email->subject($subject_new);
				$content_pop = strtr($template,$data);
				//echo $content_pop; exit;
				$this->email->message($content_pop);
				if($this->email->send())
				{
					//send_notification($admin_detail->email_id,$this->input->post('email_address'),$content_pop,$subject_new);
				}
				echo '1';
			}
			else
			{
				echo '2';
			}
			break;
			
			case 'ChangePassword':
			$user_id = users('user_id');
			extract($this->input->post());
			$t_hasher = new PasswordHash(8, FALSE);
			$hash = $t_hasher->HashPassword($newpass);
			/*echo $user_id.'<br>'; 
			echo $newpass.'<br>';
			echo $hash;
			exit;*/
			$cdata['user_password']=$hash;
			if(update_data('users',$cdata,array('user_id'=>$user_id)))
			{
				$user_emal = get_data('users',array('user_id'=>$user_id))->row_array();
				$this->session->set_userdata('customer',$user_emal);
				
				$this->session->set_flashdata('success','Password has been updated successfully!!!');
				
				redirect('user/Account_Settings','refresh');
				//echo '1';
			}
			else
			{
				$this->session->set_flashdata('error','Password update error during. Please stry again!!!');
				redirect('user/Account_Settings','refresh');
//				echo '2';
			}
			break;
			
			case 'UserRegister':
			extract($this->input->post());
			$this->form_validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
			if($this->form_validation->run()===FALSE)
			{
				?>
				<div id="success" class="popup">
				<h2 class="text-center">Error!</h2>
				<div align="center">
				 The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?
				</div>
				</div>
				<?php
			}
			else
			{
			
				$t_hasher = new PasswordHash(8, FALSE);
			
				$hash = $t_hasher->HashPassword($this->input->post('password'));
				
				$rdata = array(
				
				'user_name'=>$this->input->post('user_name'),
				
				'fname'=>$this->input->post('fname'),
				
				'lname'=>$this->input->post('lname'),
				'email_address'=>$this->input->post('email_address'),
				'password'=>$hash,
				'country'=>$this->input->post('country'),
				'town'=>$this->input->post('town'),
				'property_name'=>$this->input->post('property_name'),
				'web_site'=>$this->input->post('web_site'),
				'mobile'=>$this->input->post('mobile'),
				
				'ipaddress' 	=> 	$_SERVER['REMOTE_ADDR'],
				
				'user_agent'	=>  $_SERVER['HTTP_USER_AGENT']
				/*'user_profilepics'=>$imgs,*/
				);
			
				if(insert_data(TBL_USERS,$rdata))
				{
					$user_id = $this->db->insert_id();
					
					$hotel = array(
									
									'owner_id'=>$user_id,
									
									'fname'=>$this->input->post('fname'),
									
									'lname'=>$this->input->post('lname'),
						
									'email_address'=>$this->input->post('email_address'),
						
									'country'=>$this->input->post('country'),
						
									'town'=>$this->input->post('town'),
						
									'property_name'=>$this->input->post('property_name'),
						
									'web_site'=>$this->input->post('web_site'),
						
									'mobile'=>$this->input->post('mobile'),
									/*'user_profilepics'=>$imgs,*/
								);
								
					insert_data(HOTEL,$hotel);
					$hotel_id = $this->db->insert_id();
					$cdata['user_id']   = $user_id;
					$cdata['hotel_id']   = $hotel_id;
					//$cdata['user_name']   = '';
					//$cdata['description']   = '';
					insert_data('cash_details',$cdata);
					$pcancel['user_id'] = $user_id;
					$pcancel['hotel_id']   = $hotel_id;
					insert_data(PCANCEL,$cdata);
					$pdeposit['user_id'] = $user_id;
					$pdeposit['hotel_id']   = $hotel_id;
					insert_data(PDEPOSIT,$cdata);
					$pothers['user_id'] = $user_id;
					$pothers['hotel_id']   = $hotel_id;
					insert_data(POTHERS,$cdata);
					
					$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
					$get_email_info		=	get_mail_template('6');
					$subject		=	$get_email_info['subject'];
					$template		=	$get_email_info['message'];
					
					$get_email_info1		=	get_mail_template('9');
					$subject1			=	$get_email_info1['subject'];
					$template1			=	$get_email_info1['message'];
									
					$data = array(
						'###USERNAME###'=>$this->input->post('fname').' '.$this->input->post('lname'),
						'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
						'###SITENAME###'=>$admin_detail->company_name,
						'###LINK###'=>base_url().'channel/confirm/'.insep_encode($user_id),
						'###PASSWORD###'=>$password,
						);
						
					
					$data1= array(
						'###USERNAME###'=>$this->input->post('fname').' '.$this->input->post('lname'),
						'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
						'###SITENAME###'=>$admin_detail->company_name,
						'###EMAIL###'=>$this->input->post('email_address'),
						'###PROPERTYNAME###'=>$this->input->post('property_name'),
						);
						
					$subject_data = array(
							'###PROPERTYNAME###'=>$this->input->post('property_name'),
							'###SITENAME###'=>$admin_detail->company_name,
					);
							
					$subject_data1 = array(
							'###USERNAME###'=>$this->input->post('fname').' '.$this->input->post('lname'),
					);
					
					$subject_new = strtr($subject,$subject_data);
					
					$content_pop = strtr($template,$data);
					
					$subject_new1 = strtr($subject1,$subject_data1);
					
					$content_pop1 = strtr($template1,$data1);
					
					$this->mailsettings();
					$this->email->from($admin_detail->email_id);
					$this->email->to($this->input->post('email_address'));
					$this->email->subject($subject_new);
					$this->email->message($content_pop);
					if($this->email->send())
					{
						
						$this->email->from($admin_detail->email_id);
						$this->email->to($admin_detail->acc_email);
						$this->email->subject($subject_new1);
						$this->email->message($content_pop1);
						$this->email->send();
					
						//send_notification($admin_detail->email_id,$this->input->post('email_address'),$content_pop,$subject_new);
						
						//send_notification($admin_detail->email_id,$admin_detail->acc_email,$content_pop1,$subject_new1);
						
						?>
						<div id="success" class="popup">
						<h2 class="text-center">Congratulation!</h2>
						<div align="center">
						 Please Activate your account.<br />An email has been sent to you for verification.<br />Check your email now and get started with your new experience!<br /><br />
							<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("user_assets/images/success.jpg"));?>" alt="" />
						</div>
					</div>
						<?php
					}
				}
			}
			break;
			
			default:
			break;
		}
	}
	public function recaptcha($str='')
	{     
		/* $str = "03AHJ_VutbSigFDlIYBYlygfJo7pDPwZX4T2PquF-p4U3RTZAejt15hg5yk2dMgvAe7nIlTDZo65r3fORRqgjbOkXteNU8AWpScP
jdC1vsubHe4e__QdP_mwUQJuEVCEK9dn6uT4_8B6m6dta67rlIB0AjvikjnUqHwo0OSTRVYHrrlhyl3P5hD7GVV68NB3h5nuCxbK
N42Q2xhcYveL0SVDwrw07EFuffK6jUt1bqDGGbhMyuKs05feE1B28amefbwu8k7DTeSBu5FGqB8icRRcpTBRpRRK9UFqHICCs2fE
YZRiz7VNJMx72f8FEwXYbjEKbiS-wNrX0Va_VlDehsY2cEFsYGvlRYSScc-DXyIyaNkn-pIdEo7tB62i-UB6ic1sLYZEYJk3TNtp
r5ciVq4Ig7g9nrHsQFE3I67VV_QMpYvjoGcoJS9V-jHBJ0pR69aH0s6QXSAK7sl2zB1Du5CpOuCjouvZVjRAjp2apNNGTmu2aLFu
T3nPo8I4nsaz3lNdf2oKeTJ1B0BZhJ51Y1Dzd1G6dcN2Wqo6hFev0K_Bt-tZ0-P5-CnmNIHtHQmMIf44j1wUb4Xwmf4V6P1Pte-V
2v4QC152ekNjbwP4-QACAcWKQBxFD8_jggQ79tdKI_1zfD2abAXxBME-3WDWlPY6irg_pnNf1oipGCE7WalYYUcnUZcXimH2OAk2
KyH2HVTZOUlgljvOVzL4a4N-lMznF0rWxqaVd1UHcSBClPDp5BPP3gS_P3F2ZHJmPOWN7tWi4d86ptehGUgW9qKh9REjfNLDmSoj
2uozm7pziJIocBc85iq-GxZwXKb3JiBigkMTt5iHr2KKWQjShMoYF64EtcP4S4buUFLPzbvOV83ClGaAPTR10Y6EJfAhaVXd61j3
bD3U3TIrrTIwwyqc8a5o8JBljUxGO5rg"; */
		$google_url="https://www.google.com/recaptcha/api/siteverify";
		$secret=get_data(CONFIG)->row()->Secret_key;
		$ip=$_SERVER['REMOTE_ADDR'];
		$url=$google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		$res = curl_exec($curl);
		curl_close($curl);
		$res= json_decode($res, true);
		//reCaptcha success check
		if($res['success'])
		{
		  return TRUE;
		}
		else
		{
		  $this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
		  return FALSE;
		}
	}
	
	function confirm($id)
	{
		if($id=='')
		{
			redirect('channel');
		}
		else
		{
			$user_detail =  get_data(TBL_USERS,array('user_id'=>insep_decode($id)))->row();
			if(count($user_detail)!=0)
			{
			if($user_detail->status==0 && $user_detail->acc_active==0)
			{
				$udata['status'] = '1';
				$udata['acc_active'] = '1';
				if(update_data('manage_users',$udata,array('user_id'=>insep_decode($id))))
				{
$hdata['status'] = '1';
update_data('manage_hotel',$hdata,array('owner_id'=>insep_decode($id)));
					$data['page_heading'] = 'Home';
					$data['confirm'] ='confirm';
					$this->view('channel/index',$data);
				}
			}
			else
			{
				$data['page_heading'] = 'Home';
				$data['confirm'] ='already';
				$this->view('channel/index',$data);
			}
			}
			else
			{
				$data['page_heading'] = 'Home';
				$data['confirm'] ='already';
				$this->view('channel/index',$data);
			}
		}
	}
	
    function dashboard()
    {
		if(admin_id()=='')
		{
			$this->is_login();
			$data['page_heading'] = 'Dashboard';
			$this->db->order_by('hotel_id','desc');
			$data['hotel_status'] = get_data(HOTEL,array('owner_id'=>current_user_type()))->result_array();
			$this->db->order_by('channel_id','desc');
			$data['recom_cha'] = $this->channel_model->recom_channel();
			$data['con_cha'] = $this->channel_model->hotel_channel();
			$data['con_cha1'] = $this->channel_model->get_connect_channels();
			if($data['con_cha1'])
			{
				foreach ($data['con_cha1'] as $channel) {
				$data['cha_status']['cha_status'.$channel->channel_id] = $this->channel_model->get_connect_channels_with_status($channel->channel_id);
				//$data['cha_status'.$channel->channel_id] = $this->channel_model->get_connect_channels_with_status($channel->channel_id);
				}
			}
			
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($user_details,$data);
			if(user_type()=='1')
			{
				if($this->session->userdata('last_page')=='')
				{
					$this->views('channel/dashboard',$data);
				}
				else
				{
					redirect($this->session->userdata('last_page'));
				}
			}
			elseif(user_type()=='2')
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
				$access_id = $user_view['1'];
				$user_access = get_data(TBL_ACCESS,array('acc_id'=>$access_id))->row_array();
				if(count($user_access)!=0)
				{
					if($user_access['acc_name']=='Dashboard' && $user_access['acc_id']==$access_id)
					{
						if($this->session->userdata('last_page')=='')
						{
							$this->views('channel/dashboard',$data);
						}
						else
						{
							redirect($this->session->userdata('last_page'));
						}
					}
					else
					{   
						if($this->session->userdata('last_page')=='')
						{
							redirect($user_access['link']);
						}
						else
						{
							redirect($this->session->userdata('last_page'));
						}
					}	
				}
			}
		}
		else if(admin_id()!='')
		{
			$this->is_admin();
			$data['page_heading'] = 'Dashboard';
			$data['con_cha1'] = $this->channel_model->get_connect_channels();
			if($data['con_cha1'])
			{
				foreach ($data['con_cha1'] as $channel) {
				$data['cha_status']['cha_status'.$channel->channel_id] = $this->channel_model->get_connect_channels_with_status($channel->channel_id);
				//$data['cha_status'.$channel->channel_id] = $this->channel_model->get_connect_channels_with_status($channel->channel_id);
				}
			}
			//$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			//$data= array_merge($user_details,$data)
			$this->views('channel/dashboard',$data);
		}
}
function filenotfound(){
		$data['page_heading'] = 'File Not Found';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$this->views('channel/file_notfound',$data);
}
	
	function logout(){
		$this->session->unset_userdata('ch_user_id');
		redirect(base_url());
	}
	
	function account_notify($mode='')
	{
		if($this->session->userdata('ch_user_mail')!='deactive')
		{
		if($mode=='')
		{
			$data['action']='notify';
		}
		elseif($mode=='deactive')
		{
			$data['action']='deactive';
		}
		else
		{
			$data['action']='success';
		}
		$this->load->view('channel/account_notify',$data);
		}
		elseif($this->session->userdata('ch_user_mail')=='deactive')
		{
			if($mode!='')
			{
				$data['action']='deactive';
				$this->load->view('channel/account_notify',$data);
			}
			else
			{
				redirect(base_url());
			}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function resend()
	{
		
			$seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789!@#$%^&*()'); 
			shuffle($seed);  
			$rand = '';
			foreach (array_rand($seed, 10) as $k) 
			{
			  $rand .= $seed[$k];
			}
			$password = $rand;
			
			$t_hasher = new PasswordHash(8, FALSE);
			$hash = $t_hasher->HashPassword($password);
			
			$updata['password']=$hash;
			if(update_data(TBL_USERS,$updata,array('email_address'=>$this->session->userdata('ch_user_mail'))))
			{
				$user_info = get_data(TBL_USERS,array('email_address'=>$this->session->userdata('ch_user_mail')))->row();
				$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
				$get_email_info		=	get_mail_template('6');
				$subject		=	$get_email_info['subject'];
				$template		=	$get_email_info['message'];
				
				$this->mailsettings();
				$this->email->from($admin_detail->email_id);
				$this->email->to($user_info->email_address);
				$data = array(
					'###USERNAME###'=>$user_info->fname,
					'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
					'###SITENAME###'=>$admin_detail->company_name,
					'###LINK###'=>base_url().'channel/confirm/'.insep_encode($user_info->user_id),
					'###PASSWORD###'=>$password,
					);
				$subject_data = array(
					'###SITENAME###'=>$admin_detail->company_name,
					'###PROPERTYNAME###'=>$this->input->post('property_name')
				);
				$subject_new = strtr($subject,$subject_data);
				$this->email->subject($subject_new);
				$content_pop = strtr($template,$data);
				$this->email->message($content_pop);
				$this->email->send();
				//send_notification($admin_detail->email_id,$this->input->post('email_address'),$content_pop,$subject_new);
				redirect('channel/account_notify/success','refresh	');
			}
	}
	
	function my_account()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(get_data(TBL_USERS,array('user_id'=>user_id()))->row()->User_Type=='1')
		{
		$data['page_heading'] = 'My Account';
		$this->db->order_by('id','desc');
		$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
		$this->db->order_by('property_id','desc');
		$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Active'))->result_array();
		$this->db->order_by('property_id','desc');
		$data['inactive'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Inactive'))->result_array();
		$this->db->order_by('user_id','desc');
		$data['sub_users'] = get_data(TBL_USERS,array('User_Type'=>'2','owner_id'=>user_id()))->result();
		if($this->input->post('fname')=='')
		{
			
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($data,$user_details);
			$this->views('channel/'.uri(3),$data);
		}
		else
		{
			/*echo '<pre>';
			print_r($this->input->post());*/
			$rdata = array(
							'fname'=>$this->input->post('fname'),
							
							'lname'=>$this->input->post('lname'),
				
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							//'property_name'=>$this->input->post('property_name'),
				
							//'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							/*'user_profilepics'=>$imgs,*/
						);
			if(update_data(TBL_USERS,$rdata,array('user_id'=>current_user_type())))
			{
				$this->session->set_flashdata('profile','Profile has been updated successfully!!!');
				redirect('channel/my_account','refresh');
			}
		}
		}
		else
		{
			redirect(base_url());
		}
		
	}
	
	function manage_credit_cards($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->User_Type=='1')
		{
		switch ($mode)
		{
			case'add':
			extract($this->input->post());
			$data = array(
						'user_id'=>current_user_type(),
						'c_fname'=>(string)safe_b64encode($c_fname),
						'c_lname'=>(string)safe_b64encode($c_lname),
						'card_number'=>(string)safe_b64encode($card_number),
						'exp_month'=>(string)safe_b64encode($month),
						'exp_year'=>(string)safe_b64encode($year),
						'cvv'=>(string)safe_b64encode($cvv),
						//'billing_zip'=>$bill_zip,
					);
			if(insert_data(TBL_CREDIT,$data))
			{
				$this->session->set_flashdata('profile','Your credit card details was added successfully');
				redirect('channel/manage_credit_cards','refresh');
			}
			break;
			case 'update':
			extract($this->input->post());
			$data = array(
						'user_id'=>current_user_type(),
						'c_fname'=>(string)safe_b64encode($c_fname),
						'c_lname'=>(string)safe_b64encode($c_lname),
						'card_number'=>(string)safe_b64encode($card_number),
						'exp_month'=>(string)safe_b64encode($month),
						'exp_year'=>(string)safe_b64encode($year),
						'cvv'=>(string)safe_b64encode($cvv),
						//'billing_zip'=>$bill_zip,
					);
			if(update_data(TBL_CREDIT,$data,array('id'=>insep_decode($card_id))))
			{
				$this->session->set_flashdata('profile','Credit card details has been updated successfully');
				redirect('channel/manage_credit_cards','refresh');
			}
			break;
			case'delete':
			$available = get_data(TBL_CREDIT,array('id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if(delete_data(TBL_CREDIT,array('id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Credit card details deleted successfully');
					redirect('channel/manage_credit_cards','refresh');
				}
			}
			else
			{
				redirect('channel/manage_credit_cards','refresh');
			}
			break;
			default:
			$data['page_heading'] = 'Credit Cards';
			$this->db->order_by('id','desc');
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>user_id()))->result_array();
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$this->db->order_by('property_id','desc');
			$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Active'))->result_array();
			$this->db->order_by('property_id','desc');
			$data['inactive'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Inactive'))->result_array();
			$this->db->order_by('user_id','desc');
			$data['sub_users'] = get_data(TBL_USERS,array('User_Type'=>'2','owner_id'=>user_id()))->result();
			$data= array_merge($data,$user_details);
			$this->views('channel/my_account',$data);
			break;
		}
		}
		else
		{
			redirect(base_url());
		}
		
	}
	
	function room_exists($user_id,$username)
	{
		if($user_id)
		{
			$this->db->where_not_in('property_id',$user_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('property_name', $username);
			$query = $this->db->get(TBL_PROPERTY);
			if( $query->num_rows() > 0 )
			{ 
			
				return TRUE;
			} 
			else 
			{ 
			
				return FALSE;
			}
		}
		else
		{
			//$this->db->where('delete_trash','0');
			$this->db->where('property_name', $username);
			$query = $this->db->get(TBL_PROPERTY);
			if( $query->num_rows() > 0 )
			{ 
				return TRUE;
			} 
			else 
			{ 
				return FALSE;
			}
		}
	
	
	}
	
	function add_room_exists($user_id='')
	{
	if (array_key_exists('property_name',$_POST)) 
	{
	if ( $this->room_exists($user_id, $this->input->post('property_name')) == TRUE ) 
	{
		echo json_encode(FALSE);
	} 
	else 
	{
		echo json_encode(TRUE);
	}
	}
	}
	function property_info()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
	
		$data['page_heading'] = 'Property Info';
		$data['action'] = 'property_info';
		$data['property'] = get_data(HOTEL,array('hotel_id'=>hotel_id()))->row();
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
		{
			$this->views('channel/manage_property',$data); 
		}
	}
	function manage_rooms($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
		{
		$data['page_heading'] = 'Manage Rooms';
		switch($mode)
		{
			case'add':
			extract($this->input->post());
			$idata['property_name']=$property_name;
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$idata['owner_id'] = current_user_type();
			}
			else if(user_type()=='2'){
				$idata['owner_id'] = current_user_type();
			}
			$idata['hotel_id'] = hotel_id();
			$idata['member_count'] = $member_count;
			//$idata['allotment'] = $allotment;
			$idata['children']= $children;
			//$idata['property_type'] =$property_type;
			$idata['pricing_type'] =$pricing_type;
			$idata['selling_period'] =$selling_period;
			$idata['droc'] =$droc;
			$idata['price'] = $price;
			if($description!='')
			{
				$idata['description'] =$description;
			}
			else
			{
				$idata['description'] ='';
			}
			//$idata['existing_room_count'] = $existing_room_count;
			if(isset($non_r)!='')
			{
				$idata['non_refund'] = $non_r;
			}
			$idata['status']='Active';
			$idata['created_date'] = date('y-m-d H:i:s');
			$imgs=$_FILES['room_image']['name'];
			if($imgs!='') 
			{
				if($r_image=upload_this("uploads/room_type/",'room_image'))
				{
					image_resizer("uploads/room_type/",$r_image, 128, 128);
					$idata['image'] = $r_image;
				}
			}
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$ad_ro = insert_data(TBL_PROPERTY,$idata);
			} 
			else if(user_type()=='2')
			{
				if(in_array('4',user_edit()))
				{
				  $ad_ro = insert_data(TBL_PROPERTY,$idata);
				}
				else
				{
					redirect(base_url());
				}
			}
			if($ad_ro)
			{
				$room_id = $this->db->insert_id();
				$add_room = get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row();
				if($add_room->pricing_type==2)
				{
					for($i=1;$i<=$member_count;$i++)
					{	
								$ndata['room_id'] = $room_id;
								$ndata['method'] = implode(',',$this->input->post('method_'.$i));
								$ndata['type'] =implode(',',$this->input->post('type_'.$i));
								$ndata['dis_amount']=implode($this->input->post('d_amt_'.$i));
								$ndata['total_amount'] =implode(',',$this->input->post('h_total_'.$i));
								if(isset($non_r)!='')
								{
									$ndata['n_method'] = implode(',',$this->input->post('n_method_'.$i));
									$ndata['n_type'] =implode(',',$this->input->post('n_type_'.$i));
									$ndata['n_dis_amount'] = implode(',',$this->input->post('n_d_amt_'.$i));
									$ndata['d_total_amount'] = implode(',',$this->input->post('n_h_total_'.$i));
								}
								$ndata['created'] = date('y-m-d H:i:s');
								insert_data('room_refunds',$ndata);
					}
				}
				else if($add_room->pricing_type==1 && $add_room->non_refund==1)
				{
					$i=1;
					/*for($i=1;$i<=$member_count;$i++)
					{	*/
								$ndata['room_id'] = $room_id;
								/*$ndata['room_id'] = $room_id;
								$ndata['method'] = implode(',',$this->input->post('method_'.$i));
								$ndata['type'] =implode(',',$this->input->post('type_'.$i));
								$ndata['dis_amount']=implode($this->input->post('d_amt_'.$i));
								$ndata['total_amount'] =implode(',',$this->input->post('h_total_'.$i));*/
								if(isset($non_r)!='')
								{
									if($this->input->post('r_n_method_'.$i)!='')
									{
										$ndata['n_method'] = implode(',',$this->input->post('r_n_method_'.$i));
										$ndata['n_type'] =implode(',',$this->input->post('r_n_type_'.$i));
										$ndata['n_dis_amount'] = implode(',',$this->input->post('r_n_d_amt_'.$i));
										$ndata['d_total_amount'] = implode(',',$this->input->post('r_n_h_total_'.$i));
									}
								}
								
								$ndata['created'] = date('y-m-d H:i:s');
								insert_data('room_refunds',$ndata);
					/*}*/
				}
				
				$this->session->set_flashdata('profile','Room added successfully!!!');
				redirect('channel/manage_rooms','refresh');
			}
			break;
			case 'delete':
			if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				$map_exists = get_data(MAP,array('property_id'=>insep_decode($id),'owner_id'=>current_user_type(),'hotel_id'=>hotel_id()))->num_rows();
				if($map_exists == 0)
				{
					if(delete_data(TBL_PROPERTY,array('property_id'=>insep_decode($id))))
					{
						$this->session->set_flashdata('profile','Room details deleted successfully');
						redirect('channel/manage_rooms','refresh');
					}
				}else{
					$this->session->set_flashdata('profile_error','You cannot delete the Rooms untill you delete the mapping of this room');
					redirect('channel/manage_rooms','refresh');
				}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			case 'status':
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if($available['status']=='Active')
				{
					$udata['status']='Inactive';
				}
				else
				{
					$udata['status']='Active';
				}
				if(update_data(TBL_PROPERTY,$udata,array('property_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Room status updated successfully');
					redirect('channel/manage_rooms','refresh');
				}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			case'update':
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				extract($this->input->post());
				/*echo '<pre>';
				print_r($this->input->post());
				exit;*/
				$count = $total_room;
				for($ii=1;$ii<=$count;$ii++)
				{
					$name = $this->input->post('property_name_'.$ii);
					if($name!='')
					{
						$udata['property_name'] =$this->input->post('property_name_'.$ii);
					}
				}
				
				for($ii=1;$ii<=$count;$ii++)
				{
					$name = $this->input->post('email_'.$ii);
					if($name!='')
					{
						$udata['email'] =$this->input->post('email_'.$ii);
					}
				}
				
				for($ii=1;$ii<=$count;$ii++)
				{
					$name = $this->input->post('mobile_'.$ii);
					if($name!='')
					{
						$udata['mobile'] =$this->input->post('mobile_'.$ii);
					}
				}
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$udata['owner_id'] = current_user_type();
				}
				else if(user_type()=='2'){
					$udata['owner_id'] = current_user_type();
				}
				$udata['hotel_id'] = hotel_id();
				$udata['member_count'] = $member_count;
				$udata['children']= $children;
				$udata['property_type'] =$property_type;
				$idata['pricing_type'] =$pricing_type;
				$idata['selling_period'] =$selling_period;
				$idata['droc'] =$droc;
				$udata['price'] = $price;
				$udata['description'] =$description;
				$udata['existing_room_count'] = $existing_room_count;
				$udata['modifies_date'] = date('y-m-d H:i:s');
				
				$udata['country'] = $country;
				$udata['state'] =$state;
				$udata['city'] = $city;
				$udata['address']=$address;
				$udata['zip'] = $zip;
				if($r_image=upload_this("uploads/room_type/",'room_image'))
					{
						image_resizer("uploads/room_type/",$r_image, 128, 128);
						unlink("uploads/room_type/".$available['image']);
						$udata['image'] = $r_image;
					}
					else if($id)
					$udata['image']=$available['image'];
				
				if(update_data(TBL_PROPERTY,$udata,array('property_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Room updated successfully!!!');
					redirect('channel/manage_rooms','refresh');
				}
			}
			
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			case'clone':
			extract($this->input->post());
			$this->db->query('insert into '.TBL_PROPERTY.' (property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count,  	  			 country, state,city, address, zip, email, mobile, connected_channel, status)
			 
			 select property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count, country, state,
			 city, address, zip, email, mobile, connected_channel, status
			 
			 from '.TBL_PROPERTY.'  where property_id="'.insep_decode($property_id).'"');
			 
			 $in_property_id =  $this->db->insert_id();
			 $count = $total_room;
			 for($ii=1;$ii<=$count;$ii++)
			 {
				$name = $this->input->post('property_name_c'.$ii);
				if($name!='')
				{
					$udata['property_name'] =$this->input->post('property_name_c'.$ii);
				}
			 }
			 $udata['price'] = $price; 
			 $udata['country'] 	 = '0';
			 $udata['state'] = '0';
			 $udata['city'] = '0';
			 $udata['address'] = '';
			 $udata['zip'] = '';
			 $udata['email'] = '';
			 $udata['mobile'] ='';
			 $udata['created_date'] = date('Y-m-d H:i:s');
			 if(update_data(TBL_PROPERTY,$udata,array('property_id'=>$in_property_id)))
			 {
				 $this->session->set_flashdata('profile','Credit card details deleted successfully');
				 redirect('channel/manage_rooms','refresh');
			 }
			break;
			default:
			$this->db->order_by('id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>user_id()))->result_array();
			}
			else if(user_type()=='2'){
				$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
			}
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$this->db->order_by('property_id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'Active'))->result_array();
			}
			else if(user_type()=='2'){
				$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'Active'))->result_array();
			}
			$this->db->order_by('property_id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$data['inactive'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'Inactive'))->result_array();
			}
			else if(user_type()=='2'){
				$data['inactive'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'hotel_id'=>hotel_id(),'status'=>'Inactive'))->result_array();	
			}
			$this->db->order_by('user_id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$data['sub_users'] = get_data(TBL_USERS,array('User_Type'=>'2','owner_id'=>user_id()))->result();
			}
			else if(user_type()=='2'){
				$data['sub_users'] = get_data(TBL_USERS,array('User_Type'=>'2','owner_id'=>owner_id()))->result();	
			}
			$data= array_merge($data,$user_details);
			$this->views('channel/view_rooms',$data);
			break;
		}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	// manage users...
	function manage_users($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(get_data(TBL_USERS,array('user_id'=>user_id()))->row()->User_Type=='1')
		{
		$data['page_heading'] = 'Manage Users';
		switch ($mode)
		{
			case 'UserAdd':
			extract($this->input->post());
			
			$t_hasher = new PasswordHash(8, FALSE);
		
			$hash = $t_hasher->HashPassword($this->input->post('password'));
			$rdata = array(
							'User_Type'=>'2',
							'owner_id'=>current_user_type(),
							//'access'=>implode(',',$access),
							'user_name'=>$this->input->post('user_name'),
							'email_address'=>$this->input->post('email_address'),
							//'password'=>$hash,
							'ipaddress' 	=> 	$_SERVER['REMOTE_ADDR'],
							'user_agent'	=>  $_SERVER['HTTP_USER_AGENT'],
							'status'	=>  '0',
							'acc_active'	=>  '0',
							);
		    
			if(insert_data(TBL_USERS,$rdata))
			{
				$this->session->set_flashdata('profile','User has been inserted successfully');
				 redirect('channel/manage_users','refresh');
			}
			break;
			case 'status':
			$available = get_data(TBL_USERS,array('user_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if($available['acc_active']=='1')
				{
					$udata['acc_active']='0';
					$udata['status']='0';
				}
				else
				{
					$udata['acc_active']='1';
					$udata['status']='1';
				}
				if(update_data(TBL_USERS,$udata,array('user_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','User status updated successfully');
					redirect('channel/manage_users','refresh');
				}
			}
			else
			{
				redirect('channel/manage_users','refresh');
			}
			break;
			case 'delete':
			$available = get_data(TBL_USERS,array('user_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if(delete_data(TBL_USERS,array('user_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','User details deleted successfully');
					redirect('channel/manage_users','refresh');
				}
			}
			else
			{
				redirect('channel/manage_users','refresh');
			}
			break;
			case'update':
			$available = get_data(TBL_USERS,array('user_id'=>insep_decode($id)))->row_array();	
			if(count($available)!=0)
			{
				extract($this->input->post());
				$count = $total_user;
				for($ii=1;$ii<=$count;$ii++)
				{
					$name = $this->input->post('user_name_e'.$ii);
					if($name!='')
					{
						$udata['user_name'] =$this->input->post('user_name_e'.$ii);
					}
					
					$email_address = $this->input->post('email_address_e'.$ii);
					if($name!='')
					{
						$udata['email_address'] =$this->input->post('user_email_e'.$ii);
					}
				}
				//$udata['access'] = implode(',',$access);
				//$udata['email_address'] = implode(',',$email_address);
				if(update_data(TBL_USERS,$udata,array('user_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','User updated successfully!!!');
					redirect('channel/manage_users','refresh');
				}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			
			default:
			$this->db->order_by('id','desc');
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($data,$user_details);
			$this->db->order_by('property_id','desc');
			$data['active'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Active'))->result_array();
			$this->db->order_by('property_id','desc');
			$data['inactive'] = get_data(TBL_PROPERTY,array('owner_id'=>current_user_type(),'status'=>'Inactive'))->result_array();
			$this->db->order_by('user_id','desc');
			$data['sub_users'] = get_data(TBL_USERS,array('User_Type'=>'2','owner_id'=>user_id()))->result();
			$this->views('channel/my_account',$data);
			break;
		}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function manage_photos($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		extract($this->input->post());
		switch($mode)
		{
			case 'get':
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($hotel_id)))->row_array();
			if(count($available)!=0)
			{
				$datas['hotel_photos'] = get_data(TBL_PHOTO,array('room_id'=>insep_decode($hotel_id)))->row_array();
				$this->partial('channel/room_photo',$datas);
			}
			else
			{
				echo '0';
			}
			break;
			case 'new':
			   $name_array = array();
			   $count = count($_FILES['hotel_image']['size']); // input field name
			   foreach($_FILES as $key=>$value)
			   for($s=0; $s<=$count-1; $s++) 
			   {
					$_FILES['hotel_image']['name']=$value['name'][$s];
					$_FILES['hotel_image']['type']    = $value['type'][$s];
					$_FILES['hotel_image']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['hotel_image']['error']       = $value['error'][$s];
					$_FILES['hotel_image']['size']    = $value['size'][$s];   
					$config['upload_path'] = 'uploads/room_photos/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$ext = explode(".", $_FILES['hotel_image']["name"]);
					$extension = end($ext);
					$encrypt=md5(date('Y-m-d H:i:s').rand(0,10));
					$image_name=$encrypt.'.'.$extension;
					$config['file_name']=$image_name;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->do_upload('hotel_image');
					$data = $this->upload->data();
					$name_array[] = $image_name;
					//$name_array[] =  $_FILES['hotel_image']['name'];
					image_resizer("uploads/room_photos/",$image_name, 650, 366);
			   }
				$names= implode(',',$name_array);
				$files = array('name'=> $names);
				foreach($files as $filename)  
				$result= $this->channel_model->add_photo($filename);
				if($result)
				{
					$data['hotel_photos'] = get_data(TBL_PHOTO,array('room_id'=>insep_decode($hotel_id)))->row_array();
					$this->partial('channel/room_photo',$data);
				}
			break;
			case 'remove':
			$value = get_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)))->row()->photo_names;
			$original_val = explode(',',$value);
			$devl_id = insep_decode($image);
			if(count($original_val)!=1)
			{
				foreach($original_val as $val)
				{
					if(($key = array_search($devl_id, $original_val)) !== false) {
						unset($original_val[$key]);
						$test_array = array_values($original_val);
					}
				}
				$insert_value=implode(',',$test_array);
				$updata['photo_names'] = $insert_value;
				if(update_data(TBL_PHOTO,$updata,array('photo_id'=>insep_decode($photo_id))))
				{	
					unlink('uploads/room_photos/'.$devl_id);
					$datas['hotel_photos'] = get_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)))->row_array();
					$this->partial('channel/room_photo',$datas);
				}
			}
			else
			{
				unlink('uploads/room_photos/'.$devl_id);
				delete_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)));
				$datas['hotel_photos'] = get_data(TBL_PHOTO,array('photo_id'=>insep_decode($photo_id)))->row_array();
				$this->partial('channel/room_photo',$datas);
			}
			break;
		}
		
	}
	
	function change_functions($count)
	{
		extract($this->input->post());
		for($i=1;$i<=$count;$i++)
		{
		?>
        <tr id="item<?php echo $i;?>">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_<?php echo $i;?>[]" id="method_<?php echo $i;?>">
		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>
		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="type_<?php echo $i;?>[]" id="type_<?php echo $i;?>">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>
		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>[]" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
		</div>
        </div>
	    <input type="hidden" value="<?php echo $base_price;?>" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>"><?php echo $base_price;?></p></div>
        </td>
        <td class="pa-t-pa-b non_refund <?php if($non_refun==0){?> display_none <?php } ?>">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_<?php echo $i;?>[]" id="n_method_<?php echo $i;?>">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>
		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="n_type_<?php echo $i;?>[]" id="n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>
	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>[]" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
   		</div>
        </div>
  		<input type="hidden" value="<?php echo $base_price;?>" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php echo $base_price;?></p></div>
        </td>
        </tr>
        
        </tr>
    <?php } }
	function all_channels()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'All Channels';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$data['all_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
		$data['connected_channel'] = $this->channel_model->count_connected_channels();
		$data['count_all_channels'] = $this->channel_model->count_all_channels();
		$this->views('channel/'.uri(3),$data);
	}
	
	function inventory()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Manage Users';
		$data['inventory'] = 'inventory';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$this->views('channel/inventory',$data);
	}
	
	function our_links($mode='')
	{
		switch($mode)
		{
			case"about_us":
			$data['page_heading'] = 'About Us';
			$about = get_data('About')->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/about_us',$data);		
			}
			else{
			redirect(base_url());
			}
			break;
			
			case"terms":
			$data['page_heading'] = 'Terms of Use';
			$about = get_data('terms')->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/terms',$data);		
			}
			else{
			redirect(base_url());
			}
			break;

			case"features":
			$data['page_heading'] = 'Features';
			$features = get_data('features')->row_array();
			if(count($features)!=0)
			{
				$data = array_merge($data,$features);
				$this->view('channel/features',$data);
			}
			else {
				redirect(base_url());
			}
			break;

			case"privacy":
			$data['page_heading'] = 'Privacy Policy';
			$about = get_data('privacy')->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/privacy',$data);		
			}
			else{
			redirect(base_url());
			}
			break;
			
			case"contact_us":
			$data['page_heading'] = 'Contact Us';
			if($this->input->post('contact')!='')
			{
				extract($this->input->post());
				$idata['name'] = $name;
				$idata['email'] = $email;
				$idata['message_content'] = $message_content;
				if(insert_data('contact_info',$idata))
				{
					$admin_detail 	= get_data(TBL_SITE,array('id'=>1))->row();
					
					$get_email_info	=	get_mail_template('7');
				
					$subject		=	$get_email_info['subject'];
					$template		=	$get_email_info['message'];
					
					$this->mailsettings();
					$this->email->from($email);
					$this->email->to($admin_detail->email_id);
					$data = array(
						'##NAME##'=>$name,
						'##EMAIL##'=>$email,
						'##DETAILS##'=>$message_content,
						);
					$subject_data = array(
						'###SITENAME###'=>$admin_detail->company_name,
					);
					$subject_new = strtr($subject,$subject_data);
					$this->email->subject($subject_new);
					$content_pop = strtr($template,$data);
					$this->email->message($content_pop);
					$this->email->send();
					
					$this->session->set_flashdata('contact_success','Message has been sent successfully!!!');
					redirect('channel/our_links/contact_us','refresh');
				}
			}
			else
			{
				$about = get_data('site_config')->row_array();
				if(count($about)!=0)
				{
					$data = array_merge($data,$about);
					$this->view('channel/contact_us',$data);		
				}
				else
				{
					redirect(base_url());
				}
			}
			break;
            case"multiproperty":
			$data['page_heading'] = 'Multiproperty';
			$about = get_data('multiproperty')->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/multiproperty',$data);		
			}
			else{
			redirect(base_url());
			}
			break;

			case"connectchannels":
			$data['page_heading'] = 'Connected Channels';
			$about = get_data('connectchannels')->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/connectchannels',$data);		
			}
			else{
				redirect(base_url());
			}
			break;
			default:
			redirect(base_url());
			break;
		}	
	}
	
	function cms($mode='',$id='')
	{
		switch($mode)
		{
			case"cms_page":
			$data['page_heading'] = 'About Us';
			$about = get_data('other_cms',array('type'=>1,'id'=>unsecure($id)))->row_array();
			if(count($about)!=0)
			{
				$data = array_merge($data,$about);
				$this->view('channel/about_us',$data);		
			}
			else
			{
				redirect(base_url());
			}
			break;
			default:
				redirect(base_url());
			break;
		}
	}
	
	function faq($mode="")
	{
		switch($mode)
		{
			case"view":
			$data['page_heading'] = 'FAQ';
			$data['faq'] = get_data('faq')->result_array();
			//if(count($data['faq'])!=0)
			//{
				//$data = array_merge($data,$about);
				$this->view('channel/faq',$data);		
			//}
			//else{
			//redirect(base_url());
			//}
			break;
			default:
			redirect(base_url());
			break;
		}
	}
	
	function policies($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		switch($mode)
		{
		case'deposit':
		extract($this->input->post());
		$udata['fee_type']=$fee_type;	
		$udata['fee_amount']=$fee_amount;	
		$udata['description']=$description;	
		$udata['updated']=date('Y-m-d H:i:s');
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$up_date = update_data(PDEPOSIT,$udata,array('user_id'=>current_user_type()));
		}
		else if(user_type()=='2')
		{
			if(in_array('4',user_edit()))
			{
				$up_date = update_data(PDEPOSIT,$udata,array('user_id'=>current_user_type()));
			}
			else
			{
				redirect(base_url());
			}
		}
		if($up_date)
		{
			redirect('channel/policies','refresh');
		}
		break;
		case'cancel':
		extract($this->input->post());
		$udata['prior_checkin_days'] = $prior_checkin_days;
		$udata['prior_checkin_time'] = $prior_checkin_time;
		$udata['fee_type']=$fee_type;	
		$udata['fee_amount']=$fee_amount;	
		$udata['description']=$description;	
		$udata['updated']=date('Y-m-d H:i:s');
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$up_cancel = update_data(PCANCEL,$udata,array('user_id'=>current_user_type()));
		}
		else if(user_type()=='2')
		{
			if(in_array('4',user_edit()))
			{
			  $up_cancel = update_data(PCANCEL,$udata,array('user_id'=>current_user_type()));
			}
			else 
			{
				redirect(base_url());
			}
		}
		if($up_cancel)
		{
			redirect('channel/policies','refresh');
		}
		break;
		case'other':
		extract($this->input->post());
		$udata['check_in_time'] = $check_in_time_hr.'.'.$check_in_time_mi;
		$udata['check_out_time'] = $check_out_time_hr.'.'.$check_out_time_mi;
		
		if(isset($valet_parking)!='')
		{
			$udata['valet_parking']=$valet_parking;
		}
		else{
			$udata['valet_parking']='0';
		}
		if(isset($smoking)!='')
		{	
			$udata['smoking']=$smoking;	
		}
		else{
			$udata['smoking']='0';
		}
		if(isset($pets)!='')
		{
			$udata['pets']=$pets;	
		}
		else
		{
			$udata['pets']='0';
		}
		if(isset($child_pricing)!='')
		{
			$udata['child_pricing ']=$child_pricing;	
		}
		else{
			$udata['child_pricing ']='0';
		}
		$udata['updated']=date('Y-m-d H:i:s');
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
		{
			$up_oth = update_data(POTHERS,$udata,array('user_id'=>current_user_type()));
		}
		else if(user_type()=='2')
		{
			if(in_array('4',user_edit()))
			{
			  $up_oth = update_data(POTHERS,$udata,array('user_id'=>current_user_type()));
			}
			else
			{
			  redirect(base_url());
			}
		}
		if($up_oth)
		{
			redirect('channel/policies','refresh');
		}
		break;
		default:
		$data['page_heading'] = 'Policies';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$this->views('channel/policies',$data);	
		break;
		}
	}
	
	function polices_status()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		extract($this->input->post());
		if($status_method=='active')
		{
		  $udata['status'] = '1';
		}
		elseif($status_method=='passive')
		{
			$udata['status'] = '0';
		}
		if($status_type=='deposite')
		{	
	        /*  if(user_type()=='1')
			 {
				$up_stat = update_data(PDEPOSIT,$udata,array('user_id'=>user_id())); 
			 }
			 else if(user_type()=='2')
			 {
				 if(in_array('4',user_edit()))
				 {
					$up_stat = update_data(PDEPOSIT,$udata,array('user_id'=>owner_id()));  
				 }
				 else
				 {
					redirect(base_url());
				 }
			 } */
			if(update_data(PDEPOSIT,$udata,array('user_id'=>owner_id())))
			{
				$doposit_details = get_data(PDEPOSIT,array('user_id'=>current_user_type()))->row();
				if($status_method=='active')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_passive" id="cls_passive" type="deposite" method="passive" data-id="<?php echo $doposit_details->policy_id;?>">
				<input type="hidden" id="deposit_current_status" value="<?php echo $doposit_details->status?>" />
				<div class="onoffswitch-wrap ">
				<div class="onoffswitch">
				<input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
				<label for="active-channel" class="onoffswitch-label"></label>
				</div>
				<label class="switch-label" for="active-channel">Active</label>
				</div>
				</a>
				<?php
				}
				elseif($status_method=='passive')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_passive" id="cls_passive" type="deposite" method="active" data-id="<?php echo $doposit_details->policy_id;?>">
        		<input type="hidden" id="deposit_current_status" value="<?php echo $doposit_details->status?>" />
				<div class="onoffswitch-wrap switch-label-deactivate">
          		<div class="onoffswitch">
			    <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				<label for="active-channel" class="onoffswitch-label"></label>
		        </div>
         		<label class="switch-label" for="active-channel">Passive</label>
        		</div>
				</a>
				<?php
				}
			}
		}
		elseif($status_type=='cancels')
		{
			/* 
			if(user_type()=='1')
			{
				$up_can = update_data(PCANCEL,$udata,array('user_id'=>user_id()));
			}
			else if(user_type()=='2')
			{
				if(in_array('4',user_edit()))
				{
					$up_can = update_data(PCANCEL,$udata,array('user_id'=>user_id()));
				}
				else
				{
				   redirect(base_url());	
				}
			} */
			if(update_data(PCANCEL,$udata,array('user_id'=>current_user_type())))
			{
				$cancel_details = get_data(PCANCEL,array('user_id'=>current_user_type()))->row();
				if($status_method=='active')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="passive" data-id="<?php echo $cancel_details->policy_id;?>">
				<input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
				<div class="onoffswitch-wrap ">
				<div class="onoffswitch">
				<input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
				<label for="active-channel" class="onoffswitch-label"></label>
				</div>
				<label class="switch-label" for="active-channel">Active</label>
				</div>
				</a>
				<?php
				}
				elseif($status_method=='passive')
				{
				?>
				<a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="active" data-id="<?php echo $cancel_details->policy_id;?>">
        		<input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
				<div class="onoffswitch-wrap switch-label-deactivate">
          		<div class="onoffswitch">
			    <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				<label for="active-channel" class="onoffswitch-label"></label>
		        </div>
         		<label class="switch-label" for="active-channel">Passive</label>
        		</div>
				</a>
				<?php
				}
			
			}
		}		
	}
	
	// Channel Section Start
	function channel_listing()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
			$data['page_heading'] = 'All Channels';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($data,$user_details);
			$data['all_con'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['all_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['con_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['count_all_channels'] = $this->channel_model->count_all_channels();
			$data['connected_channel'] = $this->channel_model->count_connected_channels();
			$this->views('channel/channel_listing',$data);
		}	
		else
		{
			redirect(base_url());
		}
	}

	function connected_channel()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
		if(user_membership(1)==0)
		{
			redirect("inventory/rate_management",'refresh');
		}
		$data['page_heading'] = 'Connected Channels';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['con_cha'] = $this->channel_model->connected_channelss();
		$data['connected_channel'] = $this->channel_model->count_connected_channels();
		$data['count_all_channels'] = $this->channel_model->count_all_channels();
		$this->views('channel/connected_channel_list',$data);
		}
		else
		{
			redirect(base_url());
		}
	}

	function all_channel()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
		if(user_membership(1)==0)
		{
			redirect("inventory/rate_management",'refresh');
		}
		$data['page_heading'] = 'All Channels';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$data['all_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
		$data['connected_channel'] = $this->channel_model->count_connected_channels();
		$data['count_all_channels'] = $this->channel_model->count_all_channels();
		$this->views('channel/un_connect',$data);
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function view_channel($view_channel)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
		if(user_membership(1)==0)
		{
			redirect("inventory/rate_management",'refresh');
		}
		$available = get_data(TBL_CHANNEL,array('seo_url'=>$view_channel))->row_array();
		$data['page_heading'] = 'View Channel';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data	= array_merge($data,$user_details);
		$result = $this->channel_model->get_channel_id($view_channel);
		if($result=='')
		{
			redirect('channel/channel_listing','refresh');
		}
		if(user_can_connect_channel() || in_array($result->channel_id, user_buy_channel()))
		{
			$chann 		= $result->channel_id;
			$data['ch'] = $this->channel_model->get_connect_channel($chann);
			if($chann == 5)
			{
				$data['askforconnection'] = get_data(HOTEL,array('hotel_id'=>hotel_id()))->row();
			}
			$data['channel_status'] = $this->db->select('user_connect_id')->from('user_connect_channel')->where(array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$available['channel_id']))->count_all_results();
			if(count($available)!=0)
			{
				$data= array_merge($data,$available);
				$this->views('channel/'.uri(3),$data);
			}
			else
			{
				redirect(base_url());
			}
		}
		else
		{
			$this->session->set_flashdata('channel_warning','Upgrade to your membership plan for access the channel connection.');
			redirect('inventory/rate_management','refresh');
		}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function channel_subscribe($channel_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Channel Subscribe';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$channel_subscribe = $this->channel_model->channel_subscribe();
		if($channel_subscribe!=0)
		{
			redirect('channel/subscribe_step/'.$channel_id);
		}
		else
		{
			$data['action'] = 'channel_subscribe';
			$data['channel_id'] = $channel_id;
			$this->views('channel/subscribe',$data);
			
		}
	}
	
	function subscribe_step($channel_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['channel_id'] = $channel_id;
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$channel_details = get_data(TBL_CHANNEL,array('channel_id'=>insep_decode($channel_id)))->row_array();
		$data['action'] = 'channel_subscribe';
		if(get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->channel_subscribe_status==1)
		{
			$data['page_heading'] = 'Connect Channel';
			$data= array_merge($data,$user_details);
			$data= array_merge($data,$channel_details);
			$this->views('channel/subscribe_step',$data);
		}
		else
		{	
			if($this->input->post('plan')=='')
			{
				$data['page_heading'] = 'Subscribe';
				$data= array_merge($data,$user_details);
				$this->views('channel/subscribe',$data);
			}
			else
			{
				extract($this->input->post());
				$this->session->unset_userdata('cha_type');
				$this->session->set_userdata('cha_type',$plan);
				redirect('channel/channel_subscribe_billing/'.$channel_id,'refresh');
			}
		}
	}
	
	function channel_subscribe_billing($channel_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['channel_ids'] = $channel_id;
		$data['action'] = 'channel_subscribe';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		if($this->session->userdata('cha_type')!='')
		{
			$data['cha_type'] = $this->session->userdata('cha_type');
			$data['page_heading'] = 'Billing';
			$subscribe_plan = get_data(CHA_PLAN,array('channel_id'=>$this->session->userdata('cha_type')))->row_array();
			$currency_tbl = get_data(TBL_CUR,array('currency_id'=>$subscribe_plan['currency']))->row_array();
			$data = array_merge($data,$user_details);
			$data = array_merge($data,$subscribe_plan);
			$data = array_merge($data,$currency_tbl);
			$data['card_count'] = $this->db->select('id ')->from(TBL_CREDIT)->where(array('user_id'=>current_user_type()))->count_all_results();
			if($data['card_count']!=0)
			{
				$data['cards'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
			}
			$this->views('channel/billing',$data);
		}
		else
		{
			redirect('channel/all_channels','refresh');
		}
	}
	
	function Buy_Now($payment_id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		
		if($this->session->userdata('cha_type')!='')
		{
			extract($this->input->post());
			
			/*echo '<pre>';
			
			print_r($this->input->post());
			
			exit;
			*/
		
			$rdata = array(
				
			'fname'=>$this->input->post('fname'),
			
			'lname'=>$this->input->post('lname'),
			'email_address'=>$this->input->post('email_address'),
			
			'town'=>$this->input->post('town'),
			'property_name'=>$this->input->post('property_name'),
			'mobile'=>$this->input->post('mobile'),
			
			'tax_office'=>$tax_office,
			
			'tax_id'=>$tax_id,
			
			'zip_code'=>$zip_code,
			
			'address'=>$address
		);
		
			update_data(TBL_USERS,$rdata,array('user_id'=>current_user_type()));
		
			if($this->input->post('free_subscribe')=='')
			{
				if($this->input->post('pay_paypal')!='')
				{
					$this->load->library('paypal_class');
					$admin_setting = get_data(ADMIN)->row();
					$payment_details = get_data(CHA_PLAN,array('channel_id'=>$this->session->userdata('cha_type')))->row();
					if($admin_setting->paypalmode =='0')
					{
						$this->paypal_class->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
					}
					else
					{
						$this->paypal_class->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
					}
					$payment_id = insep_encode($this->session->userdata('cha_type'));
					$this->paypal_class->add_field('currency_code', 'USD');
					$this->paypal_class->add_field('business', $admin_setting->paypal_emailid);
					$this->paypal_class->add_field('return', base_url().'channel/Payment_Success/'.$payment_id.'/'.$channel_id); // return url
					$this->paypal_class->add_field('cancel_return', base_url().'user/Payment_Cancel'); // cancel url
					$this->paypal_class->add_field('notify_url', base_url().'index.php/validate/validatePaypal'); // notify url
					$this->paypal_class->add_field('item_name', 'Buy '.$payment_details->channel_plan.' Package');
					$this->paypal_class->add_field('amount',$payment_details->channel_price);
					$this->paypal_class->add_field('quantity', 1);
					$this->paypal_class->submit_paypal_post();
				}
				else
				{
					if($use_existing_card=='true')
					{
						$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
						
						$card_details = get_data(TBL_CREDIT,array('id'=>$existing_card))->row();
						
						$payment_details = get_data(TBL_PLAN,array('plan_id'=>$this->session->userdata('cha_type')))->row();
						
						$state_s	=	$user_details->town;
						$country_c	=	get_data(TBL_COUNTRY,array('id'=>$user_details->country))->row()->country_name;
			
						$ucity		=	$user_details->address;
			
						$address	=	$user_details->address;
			
						$ph			=	$user_details->mobile;
			
						$usermail	=	$user_details->email_address;
			
						$username	=	(string)safe_b64decode($card_details->c_fname);
						
						$last_name 	= 	(string)safe_b64decode($card_details->c_lname);
			
						$paymentmethod		=	(string)safe_b64decode($card_details->card_type);
			
						$card_code			=	(string)safe_b64decode($card_details->cvv);
			
						$card_number		=	(string)safe_b64decode($card_details->card_number);
			
						$exp_month			=	(string)safe_b64decode($card_details->exp_month);
			
						$exp_year			=	(string)safe_b64decode($card_details->exp_year);
			
						$price				=	$payment_details->plan_price;
			
						//$txn_id				=	$this->input->post('txn_id');
			
							
					}
					elseif($use_existing_card=='false')
					{
						$cdata['user_id'] 		= current_user_type();
						$cdata['c_fname'] 		= (string)safe_b64encode($c_fname);
						$cdata['c_lname'] 		= (string)safe_b64encode($c_lname);
						$cdata['card_number'] 	= (string)safe_b64encode($card_number);
						$cdata['exp_month']   	= (string)safe_b64encode($month);
						$cdata['exp_year'] 		= (string)safe_b64encode($year);
						$cdata['cvv']           = (string)safe_b64encode($cvv);
						$cdata['card_type']     = (string)safe_b64encode($payment_card);
						
						if(insert_data(TBL_CREDIT,$cdata))
						{
							$existing_card_id = $this->db->insert_id();
							
							$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
						
							$card_details = get_data(TBL_CREDIT,array('id'=>$existing_card_id))->row();
							
							$payment_details = get_data(TBL_PLAN,array('plan_id'=>$this->session->userdata('cha_type')))->row();
							
							$state_s	=	$user_details->town;
	
							$country_c	=	get_data(TBL_COUNTRY,array('id'=>$user_details->country))->row()->country_name;
				
							$ucity		=	$user_details->address;
				
							$address	=	$user_details->address;
				
							$ph			=	$user_details->mobile;
				
							$usermail	=	$user_details->email_address;
				
							$username	=	(string)safe_b64decode($card_details->c_fname);
				
							$last_name	= 	(string)safe_b64decode($card_details->c_lname);
							
							$paymentmethod		=	(string)safe_b64decode($card_details->card_type);
				
							$card_code			=	(string)safe_b64decode($card_details->cvv);
				
							$card_number		=	(string)safe_b64decode($card_details->card_number);
				
							$exp_month			=	(string)safe_b64decode($card_details->exp_month);
				
							$exp_year			=	(string)safe_b64decode($card_details->exp_year);
				
							$price				=	$payment_details->plan_price;	
						}
					}
					
					//echo $paymentmethod;
					
					if(($paymentmethod=="visa") || ($paymentmethod=="amex") || ($paymentmethod=="mastercard") || ($paymentmethod=="discover") )  
						
					{ 		 
							$this->load->library('Authorize_net');      			     
			
							$cardtype=$paymentmethod;   
			
							$expiry_month=$exp_month;  
			
							$expiry_year=$exp_year;  
			
							$expiry_date=$expiry_month."/".$expiry_year;
			
							// Authorize.net lib 
							
							$x_first_name =$username;
			
							$x_last_name=$last_name;
			
							$x_address=$address;
			
							$x_city=$ucity;		  
			
							$x_state=$state_s; 
			
							//$x_state      =$this->input->post('x_state');
			
							$x_zip="";  
			
							$x_country=$country_c;
			
							$x_phone=$ph; 
			
							$x_card_num=$card_number;  
			
							$x_exp_date=$expiry_date; 
			
							$x_card_code=$card_code; 
			
							$amount=$price;  
			
							$x_desc="subscription";  
			
							$auth_net = array(
			
							'x_card_num'			=> $x_card_num,  
			
							'x_exp_date'			=> $x_exp_date, 
			
							'x_card_code'			=> $x_card_code, 
			
							'x_description'			=>$x_desc,    
			
							'x_amount'				=> $amount,  
			
							'x_first_name'			=> $x_first_name, 
			
							'x_last_name'			=> $x_last_name, 
			
							'x_address'				=> $x_address,
			
							'x_city'				=> $x_city,
			
							'x_state'				=>$x_state,
			
							'x_zip'					=> $x_zip, 
			
							'x_country'				=>$x_country,  
			
							'x_phone'				=> $x_phone,
			
							'x_email'				=> $usermail, 
			
							//'x_customer_ip'			=> $this->input->ip_address(),  
			
							);
							
							echo '<pre>';
							print_r($auth_net);
							
			
							if($paymentmethod == "amex") 
			
							{ 
			
								$this->session->set_userdata('sess_customer_paymentmethod','amex'); 
			
								$pattern = "/^([34|37]{2})([0-9]{13})$/";//American Express 
			
								if (preg_match($pattern,$card_number)) { $verified = true;	} else { $verified = false; 	}
			
							}
			
							elseif($paymentmethod == "discover")
			
							{
			
								$this->session->set_userdata('sess_customer_paymentmethod','discover');
			
								$pattern = "/^([6011]{4})([0-9]{12})$/";//Discover Card
			
								if (preg_match($pattern,$card_number)) {  $verified = true; 	} else {	$verified = false; 	}
			
							} 
			
							elseif($paymentmethod == "mastercard")
			
							{ 
								echo 'exit;';
							
								$this->session->set_userdata('sess_customer_paymentmethod','mastercard');
			
								$pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";//Mastercard
			
								if (preg_match($pattern,$card_number)) {  $verified = true; 	} else {	$verified = false;	}
			
							} 
			
							elseif($paymentmethod == "visa") 
			
							{
			
								$this->session->set_userdata('sess_customer_paymentmethod','visa');
			
								$pattern = "/^([4]{1})([0-9]{12,15})$/";//Visa
			
								if (preg_match($pattern,$card_number))  { 	$verified = true; 	} 	else {	$verified = false;	}
			
							} 
			
							else 
			
							{	
			
								$verified = false; 
			
							}
			
							
							if($verified==true)
			
							{  					
			
			
								$this->authorize_net->setData($auth_net);  
			
								// Try to AUTH_CAPTURE
			
								if( $this->authorize_net->authorizeAndCapture() )
			
								{					 
			
									$transaction_id=$this->authorize_net->getTransactionId();
			
									$results			=	$this->channel_model->updatetransaction($transaction_id);
									$payment_id = insep_encode($this->session->userdata('cha_type'));
									$this->channel_model->payment_success_mail(current_user_type(),$payment_id);
									if($results)
			
									{
										$this->session->set_flashdata('success',"Your payment has been processed and subscription has been confirmed!!!");
										
										redirect('channel/subscribe_step/'.$channel_id);
			
									}
			
								}
			
								else 
			
								{  	
										
										$this->session->set_flashdata('error',"Invalid card number");
			
										redirect('channel/subscribe_step/'.$channel_id);
			
										//$this->authorize_net->getError();								
			
								}
			
							}	
			
							else
			
							{	
							
									$this->session->set_flashdata('error',"Please check your Card Number. It is invalid");
			
									redirect('channel/subscribe_step/'.$channel_id);
							}
			
						}
				}
			}
			elseif($free_subscribe!='')
			{
				
				$payment_id = insep_encode($this->session->userdata('pay_type'));
				$data['channel_subscribe_status'] = '1';
				$data['channel_subscribe_planid']  = insep_decode($payment_id);
				$plan_details = get_data(CHA_PLAN,array('channel_id'=>insep_decode($payment_id)))->row();
				$plan_duration = $plan_details->channel_type;
				
				if($plan_duration=='Free')
				{
					$plan = $plan_details->channel_price;
					$plan_du = 'days';
					$data['channel_subscribe_from'] = date('Y-m-d');
					$data['channel_subscribe_to'] = date("Y-m-d", strtotime("+$plan $plan_du"));
					$data['channel_subscribe_status'] = '1';
					$data['channel_subscribe_method'] = 'FREE';
					$data['channel_subscribe_txnid'] = rand(0,9999999);
					if(update_data(TBL_USERS,$data,array('user_id'=>current_user_type())))
					{
						//$this->session_unset_userdata('pay_type');
						//$this->inventory_model->payment_success_mail(user_id(),$payment_id);
						//$this->set_sessions();
						//$this->load->view('user/Payment_Success',$data);
						//redirect('channel/Payment_Success/'.($payment_id),'refresh');
						redirect('channel/subscribe_step/'.$channel_id);
						
						echo 'Payment_Succsess';
					}
					else
					{
						echo 'errror';
					}
				}
			}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function Payment_Success($payment_id='',$channel_id='')
	{
		error_reporting(0);
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->session->userdata('cha_type')!='')
		{ 
			if($_REQUEST['txn_id']!='')
			{
				$data['channel_subscribe_txnid']= $_REQUEST['txn_id'];
				$data['channel_subscribe_planid']  = insep_decode($payment_id);
				$data['channel_subscribe_price'] = $_REQUEST['mc_gross'];
				$data['channel_subscribe_method'] = 'PAYPALL';
				$plan_details = get_data(TBL_PLAN,array('plan_id'=>insep_decode($payment_id)))->row();
				$plan_duration = $plan_details->plan_types;
				
				if($plan_duration=='Free')
				{
					$plan = $plan_details->plan_price;
					$plan_du = 'days';
				}
				elseif($plan_duration=='Month')
				{
					$plan = 1;
					$plan_du = 'months';
				}
				elseif($plan_duration=='Year')
				{
					$plan = 1;
					$plan_du = 'years';
				}
				$data['channel_subscribe_from'] = date('Y-m-d');
				$data['channel_subscribe_to'] = date("Y-m-d", strtotime("+$plan $plan_du"));
				$data['channel_subscribe_status'] = '1';
				if(update_data(TBL_USERS,$data,array('user_id'=>current_user_type())))
				{
					$this->session->unset_userdata('cha_type');
					$this->channel_model->payment_success_mail(current_user_type(),$payment_id);
					//$this->set_sessions();
					$data['page_heading'] = 'Payment Success';
					$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
					$data = array_merge($data,$user_details);
					//$this->views('channel/Payment_Success',$data);
					$this->session->set_flashdata('success',"Your payment has been processed and subscription has been confirmed!!!");
					redirect('channel/subscribe_step/'.$channel_id);
				}
				else
				{
					echo 'errror';
				}
			}
			else
			{
				$this->session->unset_userdata('cha_type');
				$data['page_heading'] = 'Payment Success';
				$user_mail = $this->channel_model->payment_success_mail(current_user_type(),$payment_id);
				$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
				$data = array_merge($data,$user_details);
				//$this->views('channel/Payment_Success',$data);
				$this->session->set_flashdata('success',"Your payment has been processed and subscription has been confirmed!!!");
				redirect('channel/subscribe_step/'.$channel_id);
			}
		 }
		else
		{
			redirect(base_url());
		}
	}
	
	function down_channel()
	{
        $this->load->dbutil();        
       $backup =& $this->dbutil->backup();        
       $this->load->helper('file');
       write_file('/uploads/mybackup.zip', $backup);        
       $this->load->helper('download');
       force_download('mybackup.zip', $backup);
	}
	
	function change_property($hotel_id,$admin_id='',$admin_type='')
	{
		if($admin_id=='' && $admin_type=='')
		{
			
			$this->is_login();
			$available = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>insep_decode($hotel_id)))->row_array();
			if($available!=0)
			{
				$this->session->unset_userdata('ch_hotel_id');
				$this->session->set_userdata('ch_hotel_id',insep_decode($hotel_id));
				redirect('channel/dashboard','refresh');
			}
			else
			{
				redirect('channel/dashboard','refresh');
			}
		}
		else if($admin_id!='' && $admin_id!='')
		{
			$this->is_admin();
			$user_id = get_data(HOTEL,array('hotel_id'=>unsecure($hotel_id)))->row()->owner_id;
			$available = get_data(HOTEL,array('owner_id'=>$user_id,'hotel_id'=>unsecure($hotel_id)))->row_array();
			$this->session->unset_userdata(' ad_user_id');
			$this->session->set_userdata('ad_user_id',$user_id);
			if($available!=0)
			{
				$this->session->unset_userdata('ad_hotel_id');
				$this->session->set_userdata('ad_hotel_id',unsecure($hotel_id));
				redirect('channel/dashboard','refresh');
			}
			else
			{
				redirect('channel/dashboard','refresh');
			}
		}
		
	}
	
	/*function manage_property($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Manage Property';
		switch($mode)
		{
			case'add':
			if($this->input->post('add_property')!='')
			{
				extract($this->input->post());
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$rdata = array(
			
							'owner_id'=>user_id(),
							
							'hotel_type'=>'0',
							
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							'status'=>'1'
						);
				}
			    else if(user_type()=='2'){
					if(in_array('4',user_edit()))
					{
					$rdata = array(
			
							'owner_id'=>owner_id(),	
							
							'hotel_type'=>'0',
							
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							'status'=>'1'
						);
				}
				else
				{
					redirect(base_url());
				}
				}
				if(insert_data(HOTEL,$rdata))
				{
					$hotel_id = $this->db->insert_id();
					if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
						$cdata['user_id']   = user_id();
					}
					else if(user_type()=='2'){
						$cdata['user_id']   = owner_id();
					}
					$cdata['hotel_id']   = $hotel_id;
					//$cdata['user_name']   = '';
					//$cdata['description']   = '';
					insert_data('cash_details',$cdata);
					if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$pcancel['user_id'] = user_id();
					}
					else if(user_type()=='2'){
						$pcancel['user_id'] = owner_id();
					}
					$pcancel['hotel_id']   = $hotel_id;
					insert_data(PCANCEL,$cdata);
					if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$pdeposit['user_id'] = user_id();
					}
					else if(user_type()=='2'){
					$pcancel['user_id'] = owner_id();
					}
					$pdeposit['hotel_id']   = $hotel_id;
					insert_data(PDEPOSIT,$cdata);
					if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$pothers['user_id'] = user_id();
					}
					else if(user_type()=='2'){
					$pcancel['user_id'] = owner_id();
					}
					$pothers['hotel_id']   = $hotel_id;
					insert_data(POTHERS,$cdata);							
					$this->session->set_flashdata('profile','Property has been inserted successfully!!!');
					redirect('channel/manage_property','refresh');
				}
			}
			else
			{
				$this->load->view('channel/add_property',$data);
			}
			
			
			break;
			
			case 'delete':
			$available = get_data(HOTEL,array('hotel_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if(delete_data(HOTEL,array('hotel_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Property details deleted successfully');
					echo '1';
				}
			}
			else
			{
				//redirect('channel/manage_rooms','refresh');
				echo '0';
			}
			break;
			
			case 'status':
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if($available['status']=='Active')
				{
					$udata['status']='Inactive';
				}
				else
				{
					$udata['status']='Active';
				}
				if(update_data(TBL_PROPERTY,$udata,array('property_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Room status updated successfully');
					redirect('channel/manage_rooms','refresh');
				}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			
			case'update':
			extract($this->input->post());
			if(!$edit_hotel_id)
			{
				
			}
			else
			{
				$rdata = array(
			
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							/*'user_profilepics'=>$imgs,*/
			/*			);
			  if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			  {
				if(update_data(HOTEL,$rdata,array('owner_id'=>user_id(),'hotel_id'=>insep_decode($edit_hotel_id))))
				{
					$this->session->set_flashdata('profile','Property has been updated successfully!!!');
					if($this->input->post('redirect_url')=='manage_property')
					{
						redirect('channel/manage_property','refresh');
					}
					elseif($this->input->post('redirect_url')=='property_info')
					{
						redirect('channel/property_info','refresh');
					}
				}
			}
			else if(user_type()=='2'){
					if(in_array('4',user_edit()))
					{
				if(update_data(HOTEL,$rdata,array('owner_id'=>owner_id(),'hotel_id'=>insep_decode($edit_hotel_id))))
				{
					$this->session->set_flashdata('profile','Property has been updated successfully!!!');
					if($this->input->post('redirect_url')=='manage_property')
					{
						redirect('channel/manage_property','refresh');
					}
					elseif($this->input->post('redirect_url')=='property_info')
					{
						redirect('channel/property_info','refresh');
					}
				}
					}
					else
					{
						redirect(base_url());
					}
			}
			}
			break;
			case'clone':
			extract($this->input->post());
			$this->db->query('insert into '.TBL_PROPERTY.' (property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count,  	  			 country, state,city, address, zip, email, mobile, connected_channel, status)
			 
			 select property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count, country, state,
			 city, address, zip, email, mobile, connected_channel, status
			 
			 from '.TBL_PROPERTY.'  where property_id="'.insep_decode($property_id).'"');
			 
			 $in_property_id =  $this->db->insert_id();
			 $count = $total_room;
			 for($ii=1;$ii<=$count;$ii++)
			 {
				$name = $this->input->post('property_name_c'.$ii);
				if($name!='')
				{
					$udata['property_name'] =$this->input->post('property_name_c'.$ii);
				}
			 }
			 $udata['price'] = $price; 
			 $udata['country'] 	 = '0';
			 $udata['state'] = '0';
			 $udata['city'] = '0';
			 $udata['address'] = '';
			 $udata['zip'] = '';
			 $udata['email'] = '';
			 $udata['mobile'] ='';
			 $udata['created_date'] = date('Y-m-d H:i:s');
			 if(update_data(TBL_PROPERTY,$udata,array('property_id'=>$in_property_id)))
			 {
				 $this->session->set_flashdata('profile','Credit card details deleted successfully');
				 redirect('channel/manage_rooms','refresh');
			 }
			break;
			
			case'edit':
			if(!$id)
			{
				
			}
			else
			{
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$available = get_data(HOTEL,array('owner_id'=>user_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				else if(user_type()=='2'){
					$available = get_data(HOTEL,array('owner_id'=>owner_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				if(count($available)!=0)
				{
					$data = array_merge($data,$available);
					$this->load->view('channel/edit_property',$data);
				}
			}
			break;
			
			case'view':
			if(!$id)
			{
				
			}
			else
			{
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$available = get_data(HOTEL,array('owner_id'=>user_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				else if(user_type()=='2'){
					$available = get_data(HOTEL,array('owner_id'=>owner_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				if(count($available)!=0)
				{
					$data = array_merge($data,$available);
					$this->load->view('channel/view_property',$data);
				}
			}
			break;
			default:
			$data['action'] = 'manage_property';
			$this->db->order_by('id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>user_id()))->result_array();
			}
			else if(user_type()=='2'){
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>owner_id()))->result_array();	
			}
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$this->db->order_by('hotel_id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$data['hotel'] = get_data(HOTEL,array('owner_id'=>user_id(),'status'=>'1'))->result_array();
			}
			else if(user_type()=='2'){
				$data['hotel'] = get_data(HOTEL,array('owner_id'=>owner_id(),'status'=>'1'))->result_array();
			}
			$data= array_merge($data,$user_details);
			$this->views('channel/manage_property',$data);
			break;
		}
	}*/

	function manage_property($mode='',$id='')
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Manage Property';
		switch($mode)
		{
			case'add':
			if($this->input->post('add_property')!='')
			{
				extract($this->input->post());
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
				{
					$rdata = array(
			
							'owner_id'=>current_user_type(),
							
							'hotel_type'=>'0',
							
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							'status'=>'1'
						);
				}
			    else if(user_type()=='2'){
					if(in_array('4',user_edit()))
					{
					$rdata = array(
			
							'owner_id'=>owner_id(),	
							
							'hotel_type'=>'0',
							
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							'status'=>'1'
						);
				}
				else
				{
					redirect(base_url());
				}
				}
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$hotel = get_data(HOTEL,array('owner_id'=>current_user_type(),'status'=>'1'))->result_array();
				}
				else if(user_type()=='2'){
					$hotel = get_data(HOTEL,array('owner_id'=>owner_id(),'status'=>'1'))->result_array();
				}
				$multiproperty = get_data("manage_users",array('user_id'=>current_user_type()));
				if($multiproperty->row()->multiproperty == "Active"){
					$num_hotels = get_data("subscribe_plan",array('plan_id'=>$multiproperty->row()->plan_id),'number_of_hotels')->row()->number_of_hotels;
					if(count($hotel) < $num_hotels){
						if(insert_data(HOTEL,$rdata))
						{
							$hotel_id = $this->db->insert_id();
							if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
								$cdata['user_id']   = user_id();
							}
							else if(user_type()=='2'){
								$cdata['user_id']   = current_user_type();
							}
							$cdata['hotel_id']   = $hotel_id;
							//$cdata['user_name']   = '';
							//$cdata['description']   = '';
							insert_data('cash_details',$cdata);
							if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
							$pcancel['user_id'] = current_user_type();
							}
							else if(user_type()=='2'){
								$pcancel['user_id'] = current_user_type();
							}
							$pcancel['hotel_id']   = $hotel_id;
							insert_data(PCANCEL,$cdata);
							if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
							$pdeposit['user_id'] = current_user_type();
							}
							else if(user_type()=='2'){
							$pcancel['user_id'] = current_user_type();
							}
							$pdeposit['hotel_id']   = $hotel_id;
							insert_data(PDEPOSIT,$cdata);
							if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
							$pothers['user_id'] = current_user_type();
							}
							else if(user_type()=='2'){
							$pcancel['user_id'] = current_user_type();
							}
							$pothers['hotel_id']   = $hotel_id;
							insert_data(POTHERS,$cdata);							
							$this->session->set_flashdata('profile','Property has been inserted successfully!!!');
							redirect('channel/manage_property','refresh');
						}
					}else{
						$this->session->set_flashdata('profile','You cannot add property more than '.$num_hotels);
						redirect('channel/manage_property','refresh');
					}
				}else{
					if(insert_data(HOTEL,$rdata))
					{
						$hotel_id = $this->db->insert_id();
						if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
							$cdata['user_id']   = user_id();
						}
						else if(user_type()=='2'){
							$cdata['user_id']   = owner_id();
						}
						$cdata['hotel_id']   = $hotel_id;
						//$cdata['user_name']   = '';
						//$cdata['description']   = '';
						insert_data('cash_details',$cdata);
						if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
						$pcancel['user_id'] = user_id();
						}
						else if(user_type()=='2'){
							$pcancel['user_id'] = owner_id();
						}
						$pcancel['hotel_id']   = $hotel_id;
						insert_data(PCANCEL,$cdata);
						if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
						$pdeposit['user_id'] = user_id();
						}
						else if(user_type()=='2'){
						$pcancel['user_id'] = owner_id();
						}
						$pdeposit['hotel_id']   = $hotel_id;
						insert_data(PDEPOSIT,$cdata);
						if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
						$pothers['user_id'] = user_id();
						}
						else if(user_type()=='2'){
						$pcancel['user_id'] = owner_id();
						}
						$pothers['hotel_id']   = $hotel_id;
						insert_data(POTHERS,$cdata);							
						$this->session->set_flashdata('profile','Property has been inserted successfully!!!');
						redirect('channel/manage_property','refresh');
					}
				}
				
			}
			else
			{
				$this->load->view('channel/add_property',$data);
			}
			
			
			break;
			
			case 'delete':
			if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
			{
				$available = get_data(HOTEL,array('hotel_id'=>insep_decode($id)))->row_array();
				if(count($available)!=0)
				{
					if(delete_data(HOTEL,array('hotel_id'=>insep_decode($id))))
					{
						$this->session->set_flashdata('profile','Property details deleted successfully');
						echo '1';
					}
				}
				else
				{
					echo '0';
				}
			}
			else
			{
				echo '0';
			}
			break;
			
			case 'status':
			$available = get_data(TBL_PROPERTY,array('property_id'=>insep_decode($id)))->row_array();
			if(count($available)!=0)
			{
				if($available['status']=='Active')
				{
					$udata['status']='Inactive';
				}
				else
				{
					$udata['status']='Active';
				}
				if(update_data(TBL_PROPERTY,$udata,array('property_id'=>insep_decode($id))))
				{
					$this->session->set_flashdata('profile','Room status updated successfully');
					redirect('channel/manage_rooms','refresh');
				}
			}
			else
			{
				redirect('channel/manage_rooms','refresh');
			}
			break;
			
			case'update':
			extract($this->input->post());
			if(!$edit_hotel_id)
			{
				
			}
			else
			{
				$rdata = array(
			
							'email_address'=>$this->input->post('email_address'),
				
							'country'=>$this->input->post('country'),
				
							'town'=>$this->input->post('town'),
							
							'address'=>$this->input->post('address'), 
							
							'zip_code'=>$this->input->post('zip_code'), 
				
							'property_name'=>$this->input->post('property_name'),
				
							'web_site'=>$this->input->post('web_site'),
				
							'mobile'=>$this->input->post('mobile'),
							
							'currency'=>$this->input->post('currency'),
							
							/*'user_profilepics'=>$imgs,*/
						);
			  if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			  {
				if(update_data(HOTEL,$rdata,array('owner_id'=>current_user_type(),'hotel_id'=>insep_decode($edit_hotel_id))))
				{
					$this->session->set_flashdata('profile','Property has been updated successfully!!!');
					if($this->input->post('redirect_url')=='manage_property')
					{
						redirect('channel/manage_property','refresh');
					}
					elseif($this->input->post('redirect_url')=='property_info')
					{
						redirect('channel/property_info','refresh');
					}
				}
			}
			else if(user_type()=='2'){
					if(in_array('4',user_edit()))
					{
				if(update_data(HOTEL,$rdata,array('owner_id'=>current_user_type(),'hotel_id'=>insep_decode($edit_hotel_id))))
				{
					$this->session->set_flashdata('profile','Property has been updated successfully!!!');
					if($this->input->post('redirect_url')=='manage_property')
					{
						redirect('channel/manage_property','refresh');
					}
					elseif($this->input->post('redirect_url')=='property_info')
					{
						redirect('channel/property_info','refresh');
					}
				}
					}
					else
					{
						redirect(base_url());
					}
			}
			}
			break;
			case'clone':
			extract($this->input->post());
			$this->db->query('insert into '.TBL_PROPERTY.' (property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count,  	  			 country, state,city, address, zip, email, mobile, connected_channel, status)
			 
			 select property_name, owner_id, member_count, children, property_type, pricing_type, selling_period, droc, price, image, description, existing_room_count, country, state,
			 city, address, zip, email, mobile, connected_channel, status
			 
			 from '.TBL_PROPERTY.'  where property_id="'.insep_decode($property_id).'"');
			 
			 $in_property_id =  $this->db->insert_id();
			 $count = $total_room;
			 for($ii=1;$ii<=$count;$ii++)
			 {
				$name = $this->input->post('property_name_c'.$ii);
				if($name!='')
				{
					$udata['property_name'] =$this->input->post('property_name_c'.$ii);
				}
			 }
			 $udata['price'] = $price; 
			 $udata['country'] 	 = '0';
			 $udata['state'] = '0';
			 $udata['city'] = '0';
			 $udata['address'] = '';
			 $udata['zip'] = '';
			 $udata['email'] = '';
			 $udata['mobile'] ='';
			 $udata['created_date'] = date('Y-m-d H:i:s');
			 if(update_data(TBL_PROPERTY,$udata,array('property_id'=>$in_property_id)))
			 {
				 $this->session->set_flashdata('profile','Credit card details deleted successfully');
				 redirect('channel/manage_rooms','refresh');
			 }
			break;
			
			case'edit':
			if(!$id)
			{
				
			}
			else
			{
			if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
			{
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
					$available = get_data(HOTEL,array('owner_id'=>current_user_type(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				else if(user_type()=='2'){
					$available = get_data(HOTEL,array('owner_id'=>owner_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				if(count($available)!=0)
				{
					$data = array_merge($data,$available);
					$this->load->view('channel/edit_property',$data);
				}
			}
			}
			break;
			
			case'view':
			if(!$id)
			{
				
			}
			else
			{
			if(user_type()=='1' || user_type()=='2' && in_array('4',user_view()) || admin_id()!='' && admin_type()=='1' )
			{
				if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$available = get_data(HOTEL,array('status'=>'1','owner_id'=>current_user_type(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				else if(user_type()=='2'){
					$available = get_data(HOTEL,array('status'=>'1','owner_id'=>owner_id(),'hotel_id'=>insep_decode($id)))->row_array();
				}
				if(count($available)!=0)
				{
					$data = array_merge($data,$available);
					$this->load->view('channel/view_property',$data);
				}
			}
			else
			{
				echo 'NO';
			}
				
			}
			break;
			default:
			$data['action'] = 'manage_property';
			$this->db->order_by('id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();
			}
			else if(user_type()=='2'){
			$data['credit_card'] = get_data(TBL_CREDIT,array('user_id'=>current_user_type()))->result_array();	
			}
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$this->db->order_by('hotel_id','desc');
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$data['hotel'] = get_data(HOTEL,array('owner_id'=>current_user_type(),'status'=>'1'))->result_array();
			}
			else if(user_type()=='2'){
				$data['hotel'] = get_data(HOTEL,array('owner_id'=>owner_id(),'status'=>'1'))->result_array();
			}
			$multiproperty = get_data("manage_users",array('user_id'=>current_user_type()));
			$data['can_add'] = 1;
			if($multiproperty->row()->multiproperty == "Active"){
				if($multiproperty->row()->plan_id!=0)
				{
					$data['num_hotels'] = get_data("subscribe_plan",array('plan_id'=>$multiproperty->row()->plan_id),'number_of_hotels')->row()->number_of_hotels;
				}
				else
				{
					$data['num_hotels'] = 0;
				}

				if(count($data['hotel']) < $data['num_hotels']){
					$data['can_add'] = 1;
				}else{
					$data['can_add'] = 0;
				}
			}
			$data= array_merge($data,$user_details);
			$this->views('channel/manage_property',$data);
			break;
		}
	}
	
	function unset_last_page()
	{
		if($this->session->userdata('last_page')!='')
		{
			$this->session->unset_userdata('last_page');
			echo '1';
		}
	}
	
	//  19/11/2015  change password...
    function change_password(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('old_pass')!='')
		{
		$old_pass = $this->input->post('old_pass'); 
		$new_pass = $this->input->post('new_pass');
		$confirm_pass = $this->input->post('confirm_pass');
		if($old_pass!='' && $new_pass!='' && $confirm_pass!='')
		{
			$result = $this->channel_model->get_password();
		 if($result)
		 {
			if($new_pass == $confirm_pass)
			{
				$res = $this->channel_model->update_password($new_pass);
				if($res)
				{
					$this->session->set_flashdata('success','Password has been updated successfully!!!');
					redirect('channel/change_password','refresh');
				}
				else
				{
					$this->session->set_flashdata('error','Error Occurred while Updating Password');
					redirect('channel/change_password','refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error','NewPassword and ConfirmPassword didnot Match');
					redirect('channel/change_password','refresh');
			}
		 }
		 else
		 {
			 $this->session->set_flashdata('error','OldPassword and NewPassword didnot Match');
			
		 }
		  redirect('channel/change_password','refresh');
		}
		}
		else{
		$data['page_heading'] = 'Change Password';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$this->views('channel/my_account',$data);		
		}
	}
	function oldpass($old_pass){
           $t_hasher = new PasswordHash(8, FALSE);
		   
		   $hash = get_data(TBL_USERS,array('user_id'=>user_id()))->row()->password;
		   
		   $check = $t_hasher->CheckPassword($old_pass, $hash);
           if ($check)
           {
               return FALSE;
           } 
           else 
           { 
               return TRUE;
           }
    }
    function oldpass_check(){
		
       if (array_key_exists('old_pass',$_POST)) 
		   
       {
		   
           if ( $this->oldpass($this->input->post('old_pass')) == TRUE ) 
           {
               echo json_encode(FALSE);
           } 
           else 
           {
               echo json_encode(TRUE);
           }
       }
    }
	
	//02/12/2015...
	
	// add users...
	
	function add_users()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('add')!=''){
		$data['page_heading'] = 'ReservationOrder';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$result = $this->channel_model->add_user();
		if($result)
		{
			$this->session->set_flashdata('profile','User has been inserted successfully');
			redirect('channel/property_info','refresh');
		}
		}
		else
		{
			$this->session->set_flashdata('ins_error','Error Occured While Inserting Users');
			redirect('channel/property_info','refresh');
		}
	}
	
	// get users...
	function get_edit(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('priviledge_id')!='')
		{
		$priviledge_id = $this->input->post('priviledge_id');
		$data['page_heading'] = 'Get User';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['result'] = $this->channel_model->get_user($priviledge_id);
		$this->load->view('channel/edit_user',$data);
		}
	}
	
	// update users...
	function users_update(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('save'))
		{
			$priviledge_id = $this->input->post('priviledge_id');
			$result = $this->channel_model->update_users($priviledge_id);
			if($result)
				{
					$this->session->set_flashdata('profile','User has been Updated successfully');
					redirect('channel/manage_subusers','refresh');
				}
			else
				{
					redirect('channel/manage_subusers','refresh');	
				}
	    }
    }
	// delete users...
	function users_delete($id,$user_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$u_id = insep_decode($id);
		$us_id = insep_decode($user_id);
		$result = $this->channel_model->delete_user($u_id,$us_id);
			if($result)
			{
				$this->session->set_flashdata('profile','User has been Deleted successfully');
				redirect('channel/manage_subusers','refresh');
			}
			else
			{
				redirect('channel/manage_subusers','refresh');
			}
	}
	function users_status($id,$user_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$u_id = insep_decode($id);
		$us_id = insep_decode($user_id);
		$user_status = get_data(TBL_USERS,array('user_id'=>($us_id)))->row()->status;
		if($user_status=='0')
		{
			$updata['status'] = '1';
			$updata['acc_active'] = '1';
		}
		else if($user_status=='1')
		{
			$updata['status'] ='0';
			$updata['acc_active'] ='0';
		}
		$result =update_data(TBL_USERS,$updata,array('user_id'=>($us_id)));
		if($result)
		{
			$this->session->set_flashdata('profile','User status has been updated successfully!!!');
			redirect('channel/manage_subusers','refresh');
		}
		else
		{
			redirect('channel/manage_subusers','refresh');
		}
	}
	
	

    function manage_subusers(){

        if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}

		$data['page_heading']	= 	'Manage Subusers';

		$data['action'] 		=	'property_info';

		$data['property'] 		=	get_data(HOTEL,array('hotel_id'=>hotel_id()))->row();

		$user_details 			=	get_data(TBL_USERS,array('user_id'=>current_user_type()))->row_array();

		$data	=	array_merge($data,$user_details);

		$this->views('channel/manage_property',$data);

    }


	
	// connected_channels...
	
	function connected_channels()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Connected Channels';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		/* $data['con_cha'] = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array(); */
		$data['con_cha'] = $this->channel_model->connected_channelss();
		$data['connected_channel'] = $this->channel_model->count_connected_channels();
		$data['count_all_channels'] = $this->channel_model->count_all_channels();
		$this->views('channel/'.uri(3),$data);
	}
	
	// all channels...
	function all_channelsplan()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'All Channels';
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$data['all_con'] = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
		$data['all_cha'] = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
		$data['con_cha'] = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
		$data['count_all_channels'] = $this->channel_model->count_all_channels();
		$data['connected_channel'] = $this->channel_model->count_connected_channels();
		$this->views('channel/'.uri(3),$data);
	}

       //25/01/2016..
    
function add_user_det(){

        if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
    
       if($this->input->post('add')!=''){
		   
		$user_access = $this->input->post('access_options');
		//echo '<pre>';
		//print_r($user_access);
		$user_access_options = json_encode($user_access);
		//exit;

       $data['page_heading'] = 'ReservationOrder';

       $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();

       $data= array_merge($user_details,$data);

       $result = $this->channel_model->add_user_det();

       if($result)

       {

           $this->session->set_flashdata('profile','User has been inserted successfully');

           redirect('channel/manage_subusers','refresh');

       }

       }

       else

       {

           $this->session->set_flashdata('ins_error','Error Occured While Inserting Users');

           redirect('channel/manage_subusers','refresh');

       }    

    }

    function billing_info(){
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$data['page_heading'] = 'Property Info';
		$data['action'] = 'billing_info';
		// $data['property'] = get_data(HOTEL,array('hotel_id'=>hotel_id()))->row();
		$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($data,$user_details);
		$data['bill'] = $this->channel_model->get_bill();
		if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || in_array('4',user_view()) || admin_id()!='' && admin_type()=='1')
		{
			$this->views('channel/manage_property',$data); 
		}
	}

	function add_bill()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if($this->input->post('save'))
		{
		 	$data['page_heading'] = 'Property Info';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data = array_merge($user_details,$data);
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
			{
				$result = $this->channel_model->add_bill();
			}
			else if(user_type()=='2')
			{
				if(in_array('4',user_edit()))
				{
					$result = $this->channel_model->add_bill();
				}
				else 
				{
					redirect(base_url());
				}
			}
			if($result)
			{
				$this->session->set_flashdata('profile','Billing Info Inserted successfully');
				redirect('channel/billing_info','refresh');
			}
			else
			{
				redirect('channel/billing_info','refresh');	
			}
	    }

	} 

    
	function update_notify(){
		$notify=$_REQUEST['notify'];
	    $data=array('status'=>'seen');
	    $this->db->where('n_id',$notify);
        $this->db->update('notifications',$data);
	    echo "ok";
				
	}
      // 09/02/2016...

    function new_notification()
    {
        if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
        $result = $this->channel_model->new_notification();
        echo $result;
    }

    function new_notification_result()
    {
       if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
        $data['new_result'] = $this->channel_model->new_notification_result();
        $this->load->view('channel/new_notification',$data);
    }
	
	// 12/02/2016..

	function askforconnection()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1')
		{
			$data['recipients'] = $this->input->post('recipients');
			$data['subject'] = $this->input->post('subject');
			$data['content'] = $this->input->post('content');
			$data['sender'] = $this->input->post('sender');
			$rs = $this->channel_model->save_connection_req_mail($data);
			if($rs)
			{
				echo 1;
			}
			else
			{
				echo "error";
			}
		}
	}
	
	function socket_test($param='',$param2='')
	{
		/* $data['reservation_today_count'] 	= 	$reservation_today_count= $this->reservation_model->reservationcounts('reserve',$param);
		$data['cancelation_today_count'] 	= 	$reservation_today_count= $this->reservation_model->reservationcounts('cancel',$param);
		$data['arrival_today_count'] 		= 	$reservation_today_count= $this->reservation_model->reservationcounts('arrival',$param);
		$data['depature_today_count']	 	= 	$reservation_today_count= $this->reservation_model->reservationcounts('depature',$param);
		//$data['reservation_modify_count'] 	= 	$reservation_today_count= $this->reservation_model->reservationcounts('modify');
		
		$today_room_count=$this->reservation_model->get_count_room('today',$param,$param2);
		$confirmed_reserve=$this->reservation_model->confirmed_reserve('today',$param,$param2);
		if($confirmed_reserve!=0)
		{
			$persent_today=round(($confirmed_reserve/($today_room_count+$confirmed_reserve))*100);
		}
		else
		{
			$persent_today = 0;
		}
		$data['persent_today']				=	$persent_today;
		
		$week_room_count=$this->reservation_model->get_count_room('week',$param,$param2);
		$confirmed_reserve_week=$this->reservation_model->confirmed_reserve('week',$param,$param2);
		if($confirmed_reserve_week!=0)
		{
			$persent_week=round(($confirmed_reserve_week/($week_room_count+$confirmed_reserve_week))*100);
		}
		else
		{
			$persent_week = 0;
		}
		$data['persent_week']				=	$persent_week;
		
		$month_room_count=$this->reservation_model->get_count_room('month',$param,$param2);
		$confirmed_reserve_month=$this->reservation_model->confirmed_reserve('month',$param,$param2);
		if($confirmed_reserve_month!=0)
		{
			$persent_month=round(($confirmed_reserve_month/($month_room_count+$confirmed_reserve_month))*100);
		}
		else
		{
			$persent_month = 0;
		}
		$data['persent_month']				=	$persent_month; */
		$data['test']	=	'check_test_data';

		return json_encode($data);
	}
	function test_sys_exec()
	{
		$query = mysql_query("INSERT INTO `test`(`random_data`,`php_call`)VALUES ('aaaaaaavvvvvvvvv','".$argv[3]."');")or die(mysql_error());
		if(!$query)
		{
			
		}
	}

	public function viewlog(){
		if(admin_id()=='')
        {
            $this->is_login();
        }
        else
        {
            $this->is_admin();
        }
        $filename = "log_".current_user_type().'.txt';
        if(file_exists(realpath(APPPATH . '/views/logs/' . $filename))){
        	$this->load->view('logs/log_'.current_user_type().'.txt');
        }
	}

	public function getusercredentials(){
		$xml = $this->input->post('xml');
		$data = get_data('booking_one_way_params',array('type'=>$xml))->row_array();
		echo json_encode($data);
	}

	// Start Gayathri 24/08/2016

	public function manage_channel()
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		if(user_type()=='1' || user_type()=='2' && in_array('5',user_edit()) || admin_id()!='' && admin_type()=='1' )
		{
			$data['page_heading'] = 'Manage Channels';
			$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
			$data= array_merge($data,$user_details);
			$channeldata = get_data(MEMBERSHIP,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'plan_status' => 1));
			$data['channeldata'] = array();
			$data['can_add_channel'] = 1;
			if($channeldata->num_rows() != 0)
			{
				$connect_cha = $channels = $channeldata->row()->connect_channels;
				$channels = explode(',', $channels);
				$plandetails = get_data(TBL_PLAN,array('plan_id' => $channeldata->row()->buy_plan_id,'base_plan' => 1));
				if($plandetails->num_rows() != 0)
				{
					$plandetails = $plandetails->row()->number_of_channels;
					if(count($channels) < $plandetails)
					{
						$data['can_add_channel'] = 1;
					}else{
						$data['can_add_channel'] = 0;
					}

				}else{
					$data['can_add_channel'] = 1;
				}
				if($connect_cha != ""){
					foreach($channels as $channel)
					{
						$channeldetails = get_data(TBL_CHANNEL,array('channel_id'=>$channel))->row_array();
						$data['channeldata'][] = (object)array(
								'channel_id' => $channeldetails['channel_id'],
								'channelname' => $channeldetails['channel_name'],
								'logo_channel' => $channeldetails['logo_channel'],
								'plan_id' => $channeldata->row()->user_buy_id,
								'status' => 1,
							);
					}
				}
				$dis_chan = $disconnectchannels = $channeldata->row()->disconnect_channels;
				$disconnectchannels = explode(',', $disconnectchannels);
				if($dis_chan != ""){
					foreach($disconnectchannels as $disconnectchannel)
					{
						$channeldetails = get_data(TBL_CHANNEL,array('channel_id'=>$disconnectchannel))->row_array();
						$data['channeldata'][] = (object)array(
								'channel_id' => $channeldetails['channel_id'],
								'channelname' => $channeldetails['channel_name'],
								'logo_channel' => $channeldetails['logo_channel'],
								'plan_id' => $channeldata->row()->user_buy_id,
								'status' => 0,
							);
					}
				}
			}
			$data['all_con'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['all_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['con_cha'] = get_data(TBL_CHANNEL,('status != ""'))->result_array();
			$data['count_all_channels'] = $this->channel_model->count_all_channels();
			$data['connected_channel'] = $this->channel_model->count_connected_channels();
			$this->views('channel/view_planchannel',$data);
		}	
		else
		{
			redirect(base_url());
		}
	}

	public function channel_status($channel_id,$plan_id)
	{
		if(admin_id()=='')
		{
			$this->is_login();
		}
		else
		{
			$this->is_admin();
		}
		$channel_id = insep_decode($channel_id);
		$plan_id = insep_decode($plan_id);

		$plan_details = get_data(MEMBERSHIP,array('user_buy_id'=>$plan_id))->row_array();
		$start = $connected_channel = explode(',', $plan_details['connect_channels']);
		$disco = $disconneced_channel = explode(',', $plan_details['disconnect_channels']);
		if(in_array($channel_id, $connected_channel))
		{
			$plandetails = get_data(TBL_PLAN,array('plan_id' => $plan_details['buy_plan_id'],'base_plan' => 1));
			if($plandetails->num_rows() != 0)
			{
				$plandetails = $plandetails->row()->number_of_channels;
				if(count($start) <= $plandetails)
				{
					if(($key = array_search($channel_id, $connected_channel)) !== false)
					{
						unset($connected_channel[$key]);
						$update['status'] = 'disabled';
						update_data(CONNECT,$update,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id));
					}
				}
			}
			$connectedchannel = implode(',', $connected_channel);
		}else{
			$plandetails = get_data(TBL_PLAN,array('plan_id' => $plan_details['buy_plan_id'],'base_plan' => 1));
			if($plandetails->num_rows() != 0)
			{
				$plandetails = $plandetails->row()->number_of_channels;
				if(count($start) < $plandetails)
				{		
					array_push($connected_channel,$channel_id);
					$connectedchannel = implode(',', $connected_channel);
					$update['status'] = 'enabled';

					update_data(CONNECT,$update,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id));
					$this->session->set_flashdata('profile','Status Changed Successfully');
				}else{
					$this->session->set_flashdata('error','You can connect upto '.$plandetails.' Channels');
					$connectedchannel = implode(',', $connected_channel);
				}
			}
		}
		if(in_array($channel_id, $disconneced_channel))
		{
			if(($key = array_search($channel_id, $disconneced_channel)) !== false)
			{
				$plandetails = get_data(TBL_PLAN,array('plan_id' => $plan_details['buy_plan_id'],'base_plan' => 1));
				if($plandetails->num_rows() != 0)
				{
					$plandetails = $plandetails->row()->number_of_channels;
					if(count($start) < $plandetails)
					{	
						unset($disconneced_channel[$key]);
						$update['status'] = 'enabled';
						update_data(CONNECT,$update,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id));
						$this->session->set_flashdata('profile','Status Changed Successfully');
					}else{

						$this->session->set_flashdata('error','You can connect upto '.$plandetails.' Channels');
					}
				}				
			}
			$disconnectchannels = implode(',', $disconneced_channel);
		}else{	
			//array_push($disconneced_channel,$channel_id);
			$plandetails = get_data(TBL_PLAN,array('plan_id' => $plan_details['buy_plan_id'],'base_plan' => 1));
			if($plandetails->num_rows() != 0)
			{
				$plandetails = $plandetails->row()->number_of_channels;
				if(count($start) <= $plandetails)
				{
					$update['status'] = 'disabled';
					update_data(CONNECT,$update,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=> $channel_id));
					array_unshift($disconneced_channel, $channel_id);
				}
			}
			$disconnectchannels = implode(',', $disconneced_channel);			
		}

		$data['connect_channels'] = rtrim($connectedchannel,',');
		$data['disconnect_channels'] = rtrim($disconnectchannels,',');
		update_data(MEMBERSHIP,$data,array('user_buy_id'=>$plan_id));
	
		redirect('channel/manage_channel','refresh');

	}
	
	function test_html()
	{             
		$this->load->view('html/dashboard_html');

	}
	
	function reservation_html()
	{
		$this->load->view('html/reservation');
	}
	
	function property_info_html()
	{
		$this->load->view('html/property_info');
	}
	
	function manage_room_html()
	{
		$this->load->view('html/manage_room');
	}
	
	function manage_users_html()
	{
		$this->load->view('html/manage_users');
	}
	
	function payments_html()
	{
		$this->load->view('html/payments');
	}
	
	function policies_html()
	{
		$this->load->view('html/policies');
	}
	
	function tax_categories_html()
	{
		$this->load->view('html/tax_categories');
	}
	
	function billing_html()
	{
		$this->load->view('html/billing');
	}
	
	function channel_details_html()
	{
		$this->load->view('html/channel_details');
	}
	
	function membersship_plan_html()
	{
		$this->load->view('html/membersship_plan');
	}
	
	function calender_html()
	{
		$this->load->view('html/calender');
	}
	
	function mapping_html()
	{
		$this->load->view('html/mapping');
	}
	
	function channel_list_html()
	{
		$this->load->view('html/channel_list');
	}
	
	function bulk_update_html()
	{
		$this->load->view('html/bulk_update');
	}
	
	function member_list_html()
	{
		$this->load->view('html/member_list');
	}
}
	