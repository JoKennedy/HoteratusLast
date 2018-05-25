<?php
ini_set('memory_limit', '-1');
ini_set('display_erros','1');
class admin_model extends CI_Model
{
	public function __construct()
    {
		$this->db->reconnect();
		$this->load->database();
        parent::__construct();
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
	
function decryptIt( $q ) {
 $ser=base64_decode(str_replace(array('-', '_'), array('+', '/'), $q));
 return unserialize($ser); 
 /*$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );*/
}

function encryptIt( $q ) {
$ser=serialize($q);
    return str_replace(array('+', '/'), array('-', '_'), base64_encode($ser));
    /*$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );*/
}
	function generall()
	{
				
		$data['ckeditor']=$this->ckeditor = array(
			'id' 	=> 	'textareacontent',
			'path'	=>	'js/ckeditor',
		
			//Optionnal values
				'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"1200px",	//Setting a custom width
				'height' 	=> 	'350px',	//Setting a custom height
				'filebrowserBrowseUrl' => base_url(). 'js/ckfinder/ckfinder.html',
          'filebrowserImageBrowseUrl' => base_url(). 'js/ckfinder/ckfinder.html?Type=Images',
          'filebrowserFlashBrowseUrl' => base_url(). 'js/ckfinder/ckfinder.html?Type=Flash',
          'filebrowserUploadUrl' => base_url().'js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
          'filebrowserImageUploadUrl' => base_url() . 'js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
          'filebrowserFlashUploadUrl' => base_url() . 'js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'					
			),
		
			//Replacing styles from the "Styles tool"
			'styles' => array(			
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Blue',
						'font-weight' 		=> 	'bold'
					)
				),
				
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 		=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)				
			)
		);	
		return $data;
	}
	
	function mail_settings()
	{ 
		$this->load->library('email');  
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);	 	    
	} 

  function get_admin($id="")
	{
		if($id!=""){
		$this->db->where('id',$id);
		$q = $this->db->get("manage_admin_details");
		}else{
			$this->db->where_not_in('user_type','1'); 
		 $q = $this->db->get("manage_admin_details");
	    }
		if($q->num_rows() > 0)
		{
		  return $q->result();
		}
		return false;
	}
		
	function chklogin()
	{
		$username=$this->input->post('username');
		$pwd=$this->input->post('password');
		if($username!='' && $pwd!='')
		{
			$q = $this->db->query("SELECT * FROM (`manage_admin_details`) WHERE (`user_type` = '1' OR `user_type` = '2') AND `password` = '".$pwd."' AND `login_name` = '".$username."'");
			if($q->num_rows() > 0)
			{
			  return $q->row();
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
	function admin_details_update($id,$image="")
	{
		 //$enc=$this->encryptIt($pwd);
				
			 if($this->input->post('cnfpassword')!=""){
			 	$newpassword=$this->input->post('cnfpassword');
			 }else{
                //get old_passsword
                $get_admin=$this->get_admin($id); 
			    foreach ($get_admin as $value) {
                 $newpassword=$value->password;
			    }
			 } 
		     $data=array(	
						'login_name'=>$this->input->post('login_name'),
						'password'=>$newpassword,
						'email_id'=>$this->input->post('email_id'),
						'admin_image'=>$image,
						'admin_controlid'=>$this->input->post('admin_controlid'),
						'country'=>$this->input->post('country'),
						'city'=>$this->input->post('city')
				);
			$this->db->where('id',$id);
			$this->db->update('manage_admin_details',$data);  
  	       return true;			 
	 }
		

	function fetchcountry()
{
	$query	=	$this->db->get('country'); 
	if($query->num_rows >= 1)
	{                
		return $query->result();			 
	}   
	else
	{      
		return false;		
	}
}
	
	
	function get_adminsetting($id='')
	{
		if($id!=""){
		 $this->db->where('admin_controlid',$id);
         $q = $this->db->get("admin_control");
		}else{
		$q = $this->db->get("admin_control");
	}
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
	}
	
	
	
	function admin_setting_update()
	{
			$q=$this->db->get("admin_control");
			$result=$q->result();
			foreach($result as $row1)
			{
			     $row=$row1->admin_controlid;
			 if($row!=1){
			$data=array(
							'admin_roles'=>$this->input->post('admin_roles'.$row.''),
							'manage_admin'=>$this->input->post('manage_admin'.$row.''),
							'manage_users'=>$this->input->post('manage_users'.$row.''),
							'manage_property'=>$this->input->post('manage_property'.$row.''),
							'manage_channel'=>$this->input->post('manage_channel'.$row.''),
							'manage_reservation'=>$this->input->post('manage_reservation'.$row.''),
							'manage_emailtemplate'=>$this->input->post('manage_emailtemplate'.$row.''),
							'manage_cms_page'=>$this->input->post('manage_cms_page'.$row.''),
							'support'=>$this->input->post('support'.$row.'')
					);
			
				
		$this->db->where('admin_controlid',$row);
		$this->db->update('admin_control',$data);  
	     }
		
		}
		return true;
		//die;		
	}
	
	

		

	
	function deleteadmindetails($id)
	{	
		$this->db->delete('manage_admin_details',array('id' => $id));       
		return true;
	}
	
	function change_status($id)
	{
		  $this->db->where('id',$id);        
        $query = $this->db->get('manage_admin_details');
        if($query->num_rows >= 1) 
	     {
           $row = $query->row();			  
           $oldstatus=$row->status;			
         }    
		if($oldstatus=="Active") 
		{ 
		     $newstatus="InActive";
		}
		else {
			     $newstatus="Active";
			}  		
		$data=array( 
				'status'=>$newstatus				
				);			
			$this->db->where('id',$id);  	
		    $this->db->update('manage_admin_details',$data);

			 return true;  
	}
	
	
	function entry_admin_details($image="")
	{//die;
		if($this->input->post('admin_role')=='1')
		{
			$role=1;
		}
		else if($this->input->post('admin_role')=='2')
		{
			$role=2;
		}
		else
		{
			$role=3;
		}
				 
				$data=array(	
								'login_name'=>$this->input->post('login_name'),
								'password'=>$this->input->post('password'),
								'email_id'=>$this->input->post('email_id'),
								'admin_image'=>$image,
								'admin_controlid'=>$role,							
								'country'=>$this->input->post('country'),
								'city'=>$this->input->post('city'),
								'status'=>"Active"					
						);
			
			$this->db->insert('manage_admin_details',$data);  
			$result=$this->db->affected_rows();
			return $result;	
		//die;
		
	}
	
	function entry_update($images1="")
	{
		$data = array(	'id'=>1,
						'login_name'=>$this->input->post('login_name'),
						'password'=>$this->input->post('password'),
						'name'=>$this->input->post('name'),
						'email_id'=>$this->input->post('email_id'),
						'admin_image'=>$images1,
						//'status'=>$this->input->post('status'),
					);
		$this->db->where('id',1);
		$o=$this->db->update('manage_admin_details',$data);  
		if($o)
		{
		return true;
		}
		else
		{
		return false;
		}
	}
	
	 function get_siteconfig()
	 {
	 //$this->load->database();
		$q = $this->db->get("site_config");
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
	}
	
	function site_config_update($site_logo,$user_defaultpic,$contactus_image)
	{
		//$query=$this->get_siteconfig();//die;
		/*if($query)
		{							
			foreach($query as $adminrow)
			{	
				$credit_mode=$adminrow->credit_mode;	
				$api_login_id=$adminrow->api_login_id;	
				$api_transaction_key=$adminrow->api_transaction_key;	
				$api_url_credit=$adminrow->api_url;
			}
		}	
		
		    $credit_mode=$this->input->post('credit_mode');
			if($credit_mode=="Credit Card(Sandbox)")
			{
				$api_url="https://test.authorize.net/gateway/transact.dll";
			}
			else
			{
				$api_url="https://secure.authorize.net/gateway/transact.dll";
			}
			
			$path="application/config/authorize_net.php";
			$credit_api_login_id='$config["api_login_id"]';
			$credit_api_login_id .= "=";
			$credit_api_login_id .= "'$api_login_id';";
			$credit_api_transaction_key='$config["api_transaction_key"]';
			$credit_api_transaction_key .= "=";
			$credit_api_transaction_key .= "'$api_transaction_key';";
			$credit_api_transaction_key='$config["api_transaction_key"]';
			$credit_api_transaction_key .= "=";
			$credit_api_transaction_key .= "'$api_transaction_key';";
			$credit_api_url_login='$config["api_url"]';
			$credit_api_url_login .= "=";
			$credit_api_url_login .= "'$api_url';";
			$php="<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
			$config = str_replace($php,$php,$php);
			$config .= str_replace($api_login_id, $this->input->post('api_login_id'),$credit_api_login_id);
			$config .= str_replace($api_transaction_key, $this->input->post('api_transaction_key'), $credit_api_transaction_key);
			$config .= str_replace($api_url_credit,$api_url,$credit_api_url_login);
			 $file = fopen($path, "wb");
				$suc=fwrite($file, $config);
				fclose($file);
			if ( !$suc)
			{
				 echo 'Unable to write the file';
			}
			else
			{
					$credit_mode=$this->input->post('credit_mode');
					if($credit_mode=="Credit Card(Sandbox)")
					{
						$api_url="https://test.authorize.net/gateway/transact.dll";
					}
					else
					{
						$api_url="https://secure.authorize.net/gateway/transact.dll";
					}
			
			$site_title=$this->input->post('site_title'); 
			$site_description=$this->input->post('site_description'); 
			$site_keyword=$this->input->post('site_keyword'); 
			

			 $site_logo=$_FILES['site_logo']['name'];     		 
			if($site_logo!="") 
			{  
				$ext = substr(strrchr($site_logo, "."), 1);		
				$sitelogo="logo.".$ext;    
			}
			else   
			{ 
				$sitelogo=$this->input->post('old_site_logo');   
			}
			
			$user_defaultpic=$_FILES['user_defaultpic']['name'];     		 
			if($user_defaultpic!="") 
			{  
				$ext = substr(strrchr($user_defaultpic, "."), 1);					
				$userdefaultpic="default_user.".$ext;    
			}
			else   
			{ 
				$userdefaultpic=$this->input->post('old_user_defaultpic');   
			}
			}*/
			
		
	
	
		$data['company_name'] = $this->input->post('company_name');
	    $data['contact_person'] = $this->input->post('contact_person');		
	    $data['email_id'] = $this->input->post('email_id');
		$data['address'] =  $this->input->post('address');
		$data['city'] = $this->input->post('city');
		$data['state'] =  $this->input->post('state');
		$data['country'] = $this->input->post('country');
		$data['phno'] = $this->input->post('phno');
		$data['fax_no'] = $this->input->post('fax_no');
$data['apikey'] = $this->input->post('apikey');
		
		
		$data['databases'] =$this->input->post('databases');
		
		$data['siteurl'] = $this->input->post('siteurl');
	    $data['facebook_url'] = $this->input->post('facebook_url');
	    $data['twitter_url'] =$this->input->post('twitter_url');
		$data['google'] =  $this->input->post('google');
	    $data['linkedin'] = $this->input->post('linkedin');
		$data['skype'] = $this->input->post('skype');
		$data['pinterest'] =  $this->input->post('pinterest');
		$data['youtube'] =$this->input->post('youtube');
		$data['flickr'] = $this->input->post('flickr');
		$data['site_title'] = $this->input->post('site_title');
		$data['meta_title'] = $this->input->post('meta_title');
		$data['site_description'] =  $this->input->post('site_description');
		
		$data['site_keyword'] = $this->input->post('site_keyword');
	    $data['site_logo'] =  $site_logo;
		$data['user_defaultpic'] =  $user_defaultpic;
		$data['contactus_image'] = $contactus_image;
		$data['users_dateformat'] =  $this->input->post('users_dateformat');
		
		$data['facebook_url'] =  $this->input->post('facebook_url');
		$data['twitter_url'] =  $this->input->post('twitter_url');
		$data['google'] =  $this->input->post('google');
		$data['linkedin'] =  $this->input->post('linkedin');
		$data['skype'] =  $this->input->post('skype');
		$data['pinterest'] =  $this->input->post('pinterest');
		
		$data['youtube'] =  $this->input->post('youtube');
		$data['flickr'] =  $this->input->post('flickr');
		
		$data['analytics'] =  $this->input->post('analytics');

                $data['Site_key'] =  $this->input->post('Site_key');
		
		$data['Secret_key'] =  $this->input->post('Secret_key');
		
		$data['map_location'] = $this->input->post('lat').','.$this->input->post('lang');

		$data['ticket_mail'] =  $this->input->post('ticket_mail');
		
		
		$this->db->where('id',1);
		$o=$this->db->update('site_config',$data);  
		if($o)
		{
		return true;
		}
		else
		{
		return false;
		}
	
	}
	
	function get_user_details($id,$result_mode="")
	{

		$this->db->select("u.user_id as uid,u.*");
		$this->db->from("manage_users as u");
		$this->db->where('u.status','active');
		$this->db->where('u.user_id',$id);
		$this->db->group_by('u.user_id');
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function add_user($id="")
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['user_name']=$user_name;
			$idata['mobile_number']=$phone_no;
			$idata['name']=$location;
			$idata['country']=$country;
			$idata['status']='active';
			if($id)
			{
				update_data('manage_users',$idata,array("user_id"=>$id));
			}
			else
			{
				$idata['password']=md5($password);
				$idata['created_date']=$date;
				insert_data('Users',$idata);
				$id=$this->db->insert_id();
			}
			
				return $id;
			//	return true;
		}
		else
			return false;

	}
	function get_email_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("Email_Templates");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function get_privacy_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("privacy");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
	}
	function get_membership()
	{
		$query = $this->db->get(TBL_PLAN);
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function get_membership_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("subscribe_plan");
		$this->db->where('plan_id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
	}
	function add_email($id="")
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['subject']=$subject;
			$idata['message']=$message;
			if($id)
			{
				update_data('Email_Templates',$idata,array("id"=>$id));
			}
			else
			{
				insert_data('Email_Templates',$idata);
				$id=$this->db->insert_id();
			}
				return $id;
			//	return true;
		}
		else
			return false;

	}    
	function get_usertemp($id)
	{
		$this->db->where('user_id',$id);
		$query=$this->db->get('manage_template');
		if($query->num_rows>=1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	function get_privact_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("privacy");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function add_privacy($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			if($image!='')
			{
				$idata['image']=$image;
			}
			if($id)
			{
				update_data('privacy',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('privacy',$idata);
				$id=$this->db->insert_id();
			}
			
				return $id;
			//	return true;
		}
		   else
			return false;
	}
	
	function add_membership($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			if($id)
			{	
				$udata['plan_types']=$plan_types;
				$udata['plan_name']= $plan_name;
				$udata['currency']=$currency;
				$udata['plan_price'] = $plan_price;
				$udata['base_plan'] = $plan_base;
				$udata['number_of_hotels'] = $number_of_hotels;
				$udata['number_of_channels']=$number_of_channels;
				$udata['currency'] =  $currency;
				$udata['price_type'] = $price_type;				
				$udata['plan_description'] =  $plan_description;
				update_data(TBL_PLAN,$udata,array("plan_id"=>$id));
			}
			else
			{
				$idata['plan_types']=$plan_types;
				if($plan_name=="Free")
				{
					$idata['plan_name']='Day Free Trial';
				}
				else
				{
					$idata['plan_name']=$plan_name;
				}
				$idata['currency']=$currency;
				$idata['base_plan'] = $plan_base;
				$idata['number_of_hotels'] = $number_of_hotels;
				$idata['number_of_channels']=$number_of_channels;
				$idata['plan_price']=$plan_price;
				$idata['number_of_channels']=$number_of_channels;
				$idata['price_type'] = $price_type;
				$idata['plan_description'] =  $plan_description;
				insert_data(TBL_PLAN,$idata);
				$id=$this->db->insert_id();
			}
				return $id;
		}
		   else
			return false;
	}
	
	function add_aboutus($id="",$image='')

	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('About',$idata,array("id"=>$id));
			}
			else
			{

				$idata['created_date']=$date;

				insert_data('About',$idata);

				$id=$this->db->insert_id();

			}

			

				return $id;

			//	return true;

		}

		else

			return false;

	}
	
	function get_About($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("About");
		//$this->db->where('id',$id);
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_About_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("About");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function add_home_cms($id="",$image='')
	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			if($type==1)
			{
				$idata['title']=$title;
			}
			$idata['type']=$type;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('home_cms',$idata,array("id"=>$id));
			}
			else
			{

				$idata['created_date']=$date;

				insert_data('home_cms',$idata);

				$id=$this->db->insert_id();

			}

			

				return $id;

			//	return true;


		}

		else

			return false;

	}
	
	function add_other_cms($id="",$image='')
	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			if($type==1)
			{
				$idata['title']=$title;
			}
			$idata['type']=$type;
			$idata['content']=$content;
			$idata['image']=$image;
			$idata['seo_url']=$this->seoUrl($title);
			if($id)
			{
				update_data('other_cms',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('other_cms',$idata);
				$id=$this->db->insert_id();
			}
			return $id;
			//return true;
		}
		else
		{
			return false;
		}
	}
	
	function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
	
	
	
	function get_home_cms($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("home_cms");
		$this->db->where('type','0');
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_home_cms1($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("home_cms");
		$this->db->where('type','1');
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_other_cms($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("other_cms");
		$this->db->where('type','1');
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_home_cms_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("home_cms");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_other_cms_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("other_cms");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_Tc_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("terms");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function get_faq_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("faq");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function add_faq($id="")
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['faq_question']=$title;
			$idata['faq_answer']=$content;
			if($id)
			{
				update_data('faq',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('faq',$idata);
				$id=$this->db->insert_id();
			}
				return $id;

			//	return true;

		}

		else

			return false;

	}
	function add_terms($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('terms',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('terms',$idata);
				$id=$this->db->insert_id();
			}
				return $id;

			//	return true;

		}

		else

			return false;

	}
	function getsupport()
	{
		$query=$this->db->get('support');
		if($query->num_rows>=1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function getcontact()
	{
		$this->db->order_by('contact_id','desc');
		$query=$this->db->get('contact_info');
		if($query->num_rows>=1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function get_country()
	{
		$query=$this->db->get('country');
		if($query->num_rows>=1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	function getsupport_count()
	{		
		$query=$this->db->get('support');
		if($query->num_rows()>=1)
		{
			$cnt=$query->num_rows();					
		}
		else
		{
			$cnt="0";			
		}
		return $cnt;
	}
	
	function getcontact_count()
	{		
		$query=$this->db->get('contact_info');
		if($query->num_rows()>=1)
		{
			$cnt=$query->num_rows();					
		}
		else
		{
			$cnt="0";			
		}
		return $cnt;
	}
	
	function getsupports($perpage,$urisegment)
	{		
		//$this->db->order_by('posted_datetime desc');
		$query = $this->db->get('support',$perpage,$urisegment);
		if($query->num_rows() >= 1)
		{ 	
			return $query->result();			
		} 		    
		return false;
	}
	
	//get support by id starts here	
	function get_support($id)
	{
		$this->db->where('support_id',$id);      
		$query = $this->db->get('support');
		if($query->num_rows() >= 1)
		{ 	
			return $query->result();			
		} 		    
		return false;	
	}
	
	function get_contact($id)
	{
		$this->db->where('contact_id 	',$id);      
		$query = $this->db->get('contact_info');
		if($query->num_rows() >= 1)
		{ 	
			return $query->result();			
		} 		    
		return false;	
	}
	
	//get support by id ends here	
	//delete support by id starts here	
	function delete_support($id)
	{
		$this->db->delete('support',array('support_id'=>$id));
		return $this->db->affected_rows();		
	}
	//delete support by id starts here	
	
	function delete_contact($id)
	{
		$this->db->delete('contact_info',array('contact_id'=>$id));
		return $this->db->affected_rows();		
	}
	
	function changestatus_support($id)
	{
		$this->db->where('support_id',$id);
		$query=$this->db->get('support');
		
		if($query->num_rows()>=1)
		{
			$row=$query->row();
			$oldstatus=$row->status;
		}
		if($oldstatus=='not_viewed' || $oldstatus=='')
		{
			$newstatus='viewed';
		}
		else
		{
			return true;
		}
		$data=array('status'=>$newstatus);
		$this->db->where('support_id',$id);
		$this->db->update('support',$data);
		return true;
	}
	
	function reply_support()
	{
		// get site config details 
		$site_config	=	$this->get_siteconfig();
		if($site_config)
		{
			foreach($site_config as $site_config)
			{
				$company_name	=	$site_config->company_name;
				$admin_emailid	=	$site_config->email_id;
			}
		}
		$reply=$this->input->post('reply_message');
		$sup_id=$this->input->post('support_id');		
		$wheredata = array('support_id'=>$this->input->post('support_id'));
		$this->db->where($wheredata);	  
        $query = $this->db->get('support');	
		$data=array('replied_message'=>$reply);
		$this->db->where('support_id',$sup_id);
		$this->db->update('support',$data);		
		/* if($query->num_rows() == 1)
        {   
			$row = $query->row();			
			 $email=$row->emailid;
			 $name=$row->name;
			 $message=$row->message;
			 $subject=$row->subject;	
			
				
			$data=array('replied_message'=>$reply);
			$this->db->where('support_id',$sup_id);
			$this->db->update('support',$data);
				
			// get email template	
			$this->db->where('id','11');  			
			$queryemail_temp=$this->db->get('email_templates'); 
			if($queryemail_temp->num_rows()>=1)  
			{ 
				$get_emailtemp=$queryemail_temp->row(); 
				$msg=$get_emailtemp->message; 
				$subject=$get_emailtemp->subject; 
				
				$mail_content=array(  
				'###Name###'=>$name, 
				'###subject###'=>$subject,	
				'###message###'=>$message,
				'###answer###'=>$reply);  
				
				$content=strtr($msg,$mail_content);  				  
				$msg=$content; 
			}
			else 
			{	
				$subject="Reply for your query";
				$msg="<p> Dear ".$name."<br><br>
				 <br><br>
					Thanks for your mail regarding ".$subject."
					<br><br>				
					Your Message : ".$message."<br><br>
					Answer From Fundraising :".$reply."
					Regards, <br><br>				
					Support,
					Floor Draw<br><br>
					  </p>"; 
			}     		
			$this->mail_settings();				
			$this->email->from($admin_emailid,$company_name);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($msg);
			$this->email->send();
			
			return true;		
		}
		else
		{
			return false;
		} */	
		return true;
	}
	
	
	function getrowsperpage()
	{
		$this->db->where('id','1');  	
		$query = $this->db->get('site_config');
		if($query->num_rows() == 1)
		{
			$row = $query->row();
			$rowsperpage=10;		 
		}		   
		else
		{
			$rowsperpage="10";
		}			
		return $rowsperpage;
	}
	
	
	function get_users($id='')
	{
		if($id!=""){
         $this->db->where('user_id',$id);
         $q = $this->db->get("manage_users");
		} else{
		$q = $this->db->get("manage_users");
		}
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
	}
	
	 function search_user($transfer="")
	 {  //die;
		$this->db->where('user_name',$transfer);
		$res=$this->db->get('manage_users');
		
		$r=$res->row();
		$r1=$r->user_id;
		//$this->db->join("user_details","user_details.user_id = earnings.user_id");
		$this->db->join("manage_users","manage_users.user_id = newsletter.user_id");
		$query=$this->db->get_where("newsletter",array('newsletter.user_id'=>$r1));
		
		if($query->num_rows() > 0)
			{
			return $query->result();
			}
			
				else
				{
					return false;
				}
   }
   
   
   function get_months()
{
		$q = $this->db->get("months");
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
}

function search_month($transfer="")
 {  //die;
		$year=date('Y');
		$query=  $this->db->query("select * from newsletter where created_date BETWEEN '$year-01-01' and '$year-01-31'");
		return $query->num_rows();
   }
   
   function get_month_count()
{
		$m=date('m');
		$query=  $this->db->query("select * from newsletter where created_date BETWEEN '01-$m-2014' and '31-$m-2014'");
		return $query->num_rows();
}


function get_month_record()
{
	$d=date('m');
	for($i=1;$i<=31;$i++)
	{
	$query=  $this->db->query("select * from newsletter where created_date='2014-$d-$i'");
	$count_view[]=$query->num_rows();
	}
	return $count_view;
	
}
  
  function get_monthly_record($i,$m)
{
	$query=  $this->db->query("select * from newsletter where created_date='$i-$m-2014'");
	return $query->num_rows();
}
//21/12/14
function getadmindata($id)
	{
		$this->session->set_userdata('id',$id);
		$a=$this->session->userdata('id');
		$this->db->where('id',$a);
		$q1 = $this->db->get('admin');
		return $q1->result();
	}

function add_temp($profile_image)	
{
	    $data=array(
					'template'=>$profile_image,
					'status'=>'active'
					);
			$query=$this->db->insert('manage_temp',$data);
			if($query)
				{
					return true;
				}
				else
				{
					return false;
				}
}
function get_all_channel($id="")
{
	if($id!=""){
      $this->db->where('channel_id',$id);
      $q = $this->db->get("manage_channel");
	} else{
	  $q = $this->db->get("manage_channel");
	}	
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
}	

function update_image($image){
        $id=$this->input->post('current_channel');
		$data=array(
					'image'=>$image				
					);		
		$this->db->where('channel_id',$id);  	
		$this->db->update('manage_channel',$data);
		return true;
}
function channel_change_status($id)
	{
		$this->db->where('channel_id',$id);        
		$query = $this->db->get('manage_channel');
		if($query->num_rows >= 1)
		{
			$row = $query->row();			
			$oldstatus=$row->status;			
		}    
		if($oldstatus=="Active")
		{ $newstatus="Inactive";
		} else {
			$newstatus="Active";
		}  		
		$data=array(
					'status'=>$newstatus				
					);		
		$this->db->where('channel_id',$id);  	
		$this->db->update('manage_channel',$data);
		return true;
	}
	function change_status_member($id)
	{
		$this->db->where('plan_id',$id);        
		$query = $this->db->get('subscribe_plan');
		if($query->num_rows >= 1)
		{
			$row = $query->row();			
			$oldstatus=$row->status;			
		}    
		if($oldstatus=="0")
		{ $newstatus="1";
		} else {
			$newstatus="0";
		}  		
		$data=array(
					'status'=>$newstatus				
					);		
		$this->db->where('plan_id',$id);  	
		$this->db->update('subscribe_plan',$data);
		return true;
	}
	
function get_property($owner_id='',$pid=''){
   if($pid!=""){
  	 	 $this->db->where('owner_id',$owner_id);
		 $this->db->where('hotel_id',$pid);
		 $this->db->where('hotel_type','1');
  	  	 $q = $this->db->get("manage_hotel");
	  }else{
		  $this->db->order_by('hotel_id','desc');
		  $this->db->where('hotel_type','1');
	  $q = $this->db->get("manage_hotel");
	  }
      if($q->num_rows() > 0)
		{
			if($pid!=""){
		return $q->row_array();
			}
			else
			{
				return $q->result();
			}
		}
		return false;}

function get_property_room($owner_id,$hotel_id,$property_id=''){
   if($property_id==""){
  	 	 $this->db->where('owner_id',insep_decode($owner_id));
		 $this->db->where('hotel_id',insep_decode($hotel_id));
  	  	 $q = $this->db->get("manage_property");
	  }else{
		  $this->db->where('owner_id',insep_decode($owner_id));
		 $this->db->where('hotel_id',insep_decode($hotel_id));
		  $this->db->where('property_id',insep_decode($property_id));
		  $this->db->order_by('property_id','desc');
	  $q = $this->db->get("manage_property");
	  }
      if($q->num_rows() > 0)
		{
			if($property_id!=""){
		return $q->row_array();
			}
			else
			{
				return $q->result();
			}
		}
		return false;
}

function get_reservation($pid='')
{
	$chaReserCheckCount = array();
	$this->db->select('reservation_id, guest_name, reservation_code, channel_id, room_id, hotel_id, start_date, end_date,booking_date, status, created_date as current_date_time');
	$q = $this->db->get("manage_reservation");
	$chaReserCheckCount = array_merge($chaReserCheckCount,$q->result());
	$chaReserCheckCount = array_merge($chaReserCheckCount,all_reservation_result_admin());
	if($chaReserCheckCount)
	{
		uasort($chaReserCheckCount,function($a,$b)
		{
			return strtotime($a->current_date_time)<strtotime($b->current_date_time)?1:-1;
		});  
		return $chaReserCheckCount;              
	}
}

 function chk_current_pwd()
 {
	//$admin_id=$this->session->userdata('admin_id');
	$admin_id=$this->input->post('id');
	$pwd=$this->input->post('pwd');
	$this->db->where('id',$admin_id);
	$this->db->where('password',$pwd);
	$que=$this->db->get('manage_admin_details');
	if($que->num_rows() >= 1) 
	{
		return true;
	}
	else
	{
		return false;
	}

}
function chk_exist_email($id)
{
	if($id=='')
	{
		$mail=$this->input->post('email');
		$this->db->where('email_id',$mail);
		$que=$this->db->get('manage_admin_details');
	}
	elseif($id!='')
	{
		$mail=$this->input->post('email');
		$this->db->where_not_in('id',$id);
		$this->db->where('email_id',$mail);
		$que=$this->db->get('manage_admin_details');	
	}
	if($que->num_rows >= 1) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
function chk_exist_LoginName($id)
{
	if($id=='')
	{
		$mail=$this->input->post('login_name');
		$this->db->where('login_name',$mail);
		$que=$this->db->get('manage_admin_details');
	}
	elseif($id!='')
	{
		$mail=$this->input->post('login_name');
		$this->db->where_not_in('id',$id);
		$this->db->where('login_name',$mail);
		$que=$this->db->get('manage_admin_details');	
	}
	if($que->num_rows >= 1) 
	{
		return true;
	}
	else
	{
		return false;
	}
}


function get_users_mail($mail_val)
{
		
		$this->db->where('email_id',$mail_val); 
		$query = $this->db->get('manage_admin_details');
		if($query->num_rows == 1)
		{	
			$site_config	=	$this->get_siteconfig();
			if($site_config)
			{
				foreach($site_config as $site_config)
				{
					$company_name	=	$site_config->company_name;
					$emailid	=	$site_config->email_id;
					$site_logo  = $site_config->site_logo;
				}
			}
			//echo $cmp_name=$company_name;
			//echo $email=$emailid;
			//die;
			
			$wheredata = array('email_id'=>$mail_val);
			$this->db->where($wheredata);	  
			$query = $this->db->get('manage_admin_details');
			
			//echo $this->db->last_query();
			
			if($query->num_rows == 1)
			{   
					$row 		=$query->row();
					$email		=$row->email_id;
					$login_name	=$row->login_name;
					$first_name	=$row->name;
					$user_id	=$row->id;
					$pwd		=$row->password;
					$this->db->where('id','8');  			
					$queryemail_temp=$this->db->get('Email_Templates'); 
					if($queryemail_temp->num_rows()==1)  
					{ 
						$get_emailtemp=$queryemail_temp->row(); 
						$msg=$get_emailtemp->message; 
						$subject=$get_emailtemp->subject; 
						//$user_id_for_link=$this->encryptIt($user_id);
						//$refid=$this->simple_encrypt($ref);
						//$basurl=base_url();
						//$link_for_change_password=$basurl."user/change_password1/".$user_id_for_link;
											
						$mail_content = array(
												'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$site_logo,
												'###SITENAME###'=>$company_name,
												'###PASSWORD###'=>$pwd,
											);
					 
						$content=strtr($msg,$mail_content);  				  
						$msg=$content;
						//die;
						$this->mail_settings();//	die;	
						$this->email->from($email);
						$this->email->to($email);
						$this->email->subject($subject);
						$this->email->message($msg);
						$this->email->send();
						return "Mail has been sent successfully";
					}
				}					
					else
					{
					return "Error in sending";
					}	
		} 
	}

function get_sitelogo()
{

	    $q = $this->db->get("site_config");
		if($q->num_rows() > 0)
		{	
		return $q->row();
		}
		return false;	
}

/*Start Subbaiah \*/

function get_userss()
{

	$query	=	$this->db->get(TBL_USERS);
	if($query->num_rows > 0)
	{
		return $query->result();
	}
}
function get_userdetails($user_id)
{
	$this->db->where('id',$user_id);
	$query	=	$this->db->get(TBL_USERS);
	if($query->num_rows > 0)
	{
		$row	=	$query->row();
		return $query->row(); 
	}

}

function get_subscribers()
{
	$query	=	$this->db->get('subscribers');
	if($query->num_rows > 0)
	{
		return $query->result();
	}
}	
function send_newsletter()
{
	if($this->input->post('all')!='' || $this->input->post('sub')!='')
	{
		if($this->input->post('all')!='')
		{
			$mail = $this->input->post('all');
		}
		else
		{
			$mail = $this->input->post('sub');
		}
		foreach($mail as $results)
		{
			$email			=	$results;
			$description	=	$this->input->post('description');
			$subject			=	"News Letter";
			//send_notification($from="",$email,$description,$subject);
			$this->load->library('email');
			$this->db->where('id',1);  	
			$queryy = $this->db->get('site_config');
			if($queryy->num_rows == 1)
			{
				$row 				= 	$queryy->row();
				$admin_email		=	$row->email_id;			 							 
				$companyname		=	$row->company_name;
			}
				$config['charset'] 	= "utf-8";
				$config['mailtype'] = "html";
				$config['wordwrap'] = TRUE;
				// $message 			= "Sending News letters";
				$subject			=	"News Letter";
				$this->email->initialize($config);
				$this->email->from($admin_email);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($description);
				$this->email->send();					
		}
		return true;	
	}
	else
	{
		$send_to	=	$this->input->post('users');
		if($send_to	=="1")
		{
			$query	=	$this->get_userss();
		}
		else
		{
			$query	=	$this->get_subscribers();
		}
		if($query)
		{
		foreach($query as $key => $results)
		{
			$email			=	$results->email;
			$description	=	$this->input->post('description');
			$subject			=	"News Letter";
			//send_notification($from="",$email,$description,$subject);
			$this->load->library('email');
			$this->db->where('id',1);  	
			$queryy = $this->db->get('site_config');
			if($queryy->num_rows == 1)
			{
				$row 				= 	$queryy->row();
				$admin_email		=	$row->email_id;			 							 
				$companyname		=	$row->company_name;
			}
				$config['charset'] 	= "utf-8";
				$config['mailtype'] = "html";
				$config['wordwrap'] = TRUE;
				// $message 			= "Sending News letters";
				$subject			=	"News Letter";
				$this->email->initialize($config);
				$this->email->from($admin_email);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($description);
				$this->email->send();				
		}
		return true;	
	}
		else
		{
			return false;
		}
	}
	
}

/*End Subbaiah */

// sharmila...
		
		// get channel...
		
		function get_channel()
	{
		$query = $this->db->get('channel_plan');
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	function get_channel_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("channel_plan");
		$this->db->where('channel_id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
	}
	
	function add_channel($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			

			if($id)
			{
				$udata['currency']=$currency;
				$udata['currency'] =  $currency;
				$udata['channel_price'] = $channel_price;
				update_data(channel_plan,$udata,array("channel_id"=>$id));
			}
			else
			{
				
				$idata['channel_plan']=$channel_plan;
				$idata['channel_type'] = $channel_type;
				$idata['channel_price'] = $channel_price;
				$idata['currency']=$currency;
				insert_data('channel_plan',$idata);
				$id=$this->db->insert_id();
			}
			
				return $id;
			//	return true;
		}
		   else
			return false;
	} 
	
	
		function change_status_channel($id)
	{
		$this->db->where('channel_id',$id);        
		$query = $this->db->get('channel_plan');
		if($query->num_rows >= 1)
		{
			$row = $query->row();			
			$oldstatus=$row->status;			
		}    
		if($oldstatus=="0")
		{ $newstatus="1";
		} else {
			$newstatus="0";
		}  		
		$data=array(
					'status'=>$newstatus				
					);		
		$this->db->where('channel_id',$id);  	
		$this->db->update('channel_plan',$data);
		// echo $this->db->last_query();die;
		return true;
	}
	

         function select_channel($id)
{
	if($id!=""){
      $this->db->where('channel_id',$id);
      $q = $this->db->get("manage_channel");
	} else{
	  $q = $this->db->get("manage_channel");
	}	
		if($q->num_rows() > 0)
		{
		return $q->result();
		}
		return false;
}	


	function edit_update(){
		$channel_id = $this->input->post('channel_id');
		$channel_name = $this->input->post('channel_name');
		$channel_username = $this->input->post('channel_username');
		$channel_password = $this->input->post('channel_password');
		$description = '';//$this->input->post('description');
		$based_in =  '';//$this->input->post('based_in');
		$founded_in =  '';//$this->input->post('founded_in');
		$available_language =  '';//$this->input->post('available_language');
		$available_country =  '';//$this->input->post('available_country');
		$channel_instruction =  '';//$this->input->post('channel_instructions');
		$authentication_requirements =  '';//$this->input->post('authentication_requirements');
		$supported_operations =  '';//implode(',',$this->input->post('supported_operations'));
		$data = array('channel_name'=>$channel_name,'channel_password'=>$channel_password,'channel_username'=>$channel_username,'description'=>$description,'based_in'=>$based_in,'founded_in'=>$founded_in,'available_language'=>$available_language,'available_country'=>$available_country,'channel_instruction'=>$channel_instruction,'authentication_requirements'=>$authentication_requirements,'supported_operations'=>$supported_operations);
		$this->db->where('channel_id',$channel_id);
		$res = $this->db->update('manage_channel',$data);
		// echo $this->db->last_query();die;
		if($res){
			return true;
		}
			return false;
	}


      function get_supportdet()
	{
		$query = $this->db->get('supported_operations');
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	} 

// 17/12/2015..

	function add_features($id="",$image='')
	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('features',$idata,array("features_id"=>$id));
			}
			else
			{

				$idata['created_date']=$date;

				insert_data('features',$idata);

				$id=$this->db->insert_id();
			}
				return $id;
		}
		else
			return false;
	}

	function get_features($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("features");
		//$this->db->where('id',$id);
		if($result_mode=="array")
		{
			$this->db->order_by('features_id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_features_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("features");
		$this->db->where('features_id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function add_channels($profile_image){

   $channel_name = $this->input->post('channel_name');

   $channel_username = $this->input->post('channel_username');

   $channel_password = $this->input->post('channel_password');

   $description = '';//$this->input->post('description');

   $based_in ='';// $this->input->post('based_in');

   $founded_in ='';// $this->input->post('founded_in');

   $available_language ='';// $this->input->post('available_language');

   $available_country ='';// $this->input->post('available_country');

   $channel_instruction = '';//$this->input->post('channel_instructions');

   $authentication_requirements = '';//$this->input->post('authentication_requirements');

   $supported_operations = '';//implode(',',$this->input->post('supported_operations'));
   
   $data = array('channel_name'=>$channel_name,'channel_password'=>$channel_password,'channel_username'=>$channel_username,'description'=>$description,'based_in'=>$based_in,'founded_in'=>$founded_in,'available_language'=>$available_language,'available_country'=>$available_country,'channel_instruction'=>$channel_instruction,'authentication_requirements'=>$authentication_requirements,'supported_operations'=>$supported_operations,'image'=>$profile_image,'logo_book'=>$profile_image,'logo_channel'=>$profile_image);

   /* echo '<pre>';

   print_r($data);die; */

   $res = $this->db->insert('manage_channel',$data);

   // echo $this->db->last_query();die;

   if($res){

       return true;

   }

       return false;

}


function get_user_det(){
		$this->db->where('User_Type','1');
		$this->db->order_by('user_id','desc');
		$ver = $this->db->get('manage_users');
		if($ver->num_rows() > 0){
			return $ver->result();
		}
			return false;
	}
	


    function add_users_det(){

       $this->load->library(array('passwordhash'));

    // echo 'asas';die;

    $user_email = $this->input->post('email_address');

    $property_name = $this->input->post('property_name');

    $mobile = $this->input->post('mobile');

    $user_name = $this->input->post('user_name');

    $website = $this->input->post('website');

    $first_name = $this->input->post('first_name');

    $last_name = $this->input->post('last_name');

    // echo $this->input->post('user_password');die;

    $t_hasher = new PasswordHash(8, FALSE);

    $hash = $t_hasher->HashPassword($this->input->post('user_password'));

    $data = array('user_name'=>$user_name,'password'=>$hash,'email_address'=>$user_email,'property_name'=>$property_name,'web_site'=>$website,'fname'=>$first_name,'lname'=>$last_name,'mobile'=>$mobile);

          if(insert_data(TBL_USERS,$data))

      {

          $user_id = $this->db->insert_id();

          $hotel = array(

                          'owner_id'=>$user_id,

                          'fname'=>$first_name,

                          'lname'=>$last_name,

                          'email_address'=>$user_email,

                          'property_name'=>$property_name,

                          'web_site'=>$website,

                          'mobile'=>$mobile,

                      );

          insert_data(HOTEL,$hotel);

          $hotel_id = $this->db->insert_id();

          $cdata['user_id']   = $user_id;

          $cdata['hotel_id']   = $hotel_id;

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

          $get_email_info        =    get_mail_template('6');

          $subject        =    $get_email_info['subject'];

          $template        =    $get_email_info['message'];

          $get_email_info1        =    get_mail_template('9');

          $subject1            =    $get_email_info1['subject'];

          $template1            =    $get_email_info1['message'];

          $data = array(

              '###USERNAME###'=>$this->input->post('first_name').' '.$this->input->post('last_name'),

              '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,

              '###SITENAME###'=>$admin_detail->company_name,

              '###LINK###'=>base_url().'channel/confirm/'.insep_encode($user_id),

              '###PASSWORD###'=>$hash,

              );

          $data1= array(

              '###USERNAME###'=>$this->input->post('first_name').' '.$this->input->post('last_name'),

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

                  '###USERNAME###'=>$this->input->post('first_name').' '.$this->input->post('last_name'),

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

          }



        return true;

      }

      else{

          return false;

      }

    }

		
	function fetch_users($user_id){
		//$user_id = insep_decode($user_id);
		$this->db->where('user_id',$user_id);
		$ver = $this->db->get('manage_users');
		if($ver->num_rows()==1){
			return $ver->row();
		}
			return false;
	}
	
	function edit_users(){
		// echo 'edit';die;
		$user_id = $this->input->post('user_id');
		$user_email = $this->input->post('email_address');
		$property_name = $this->input->post('property_name');
		$mobile = $this->input->post('mobile');
		$user_name = $this->input->post('user_name');
		$website = $this->input->post('website');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$data = array('user_name'=>$user_name,'email_address'=>$user_email,'property_name'=>$property_name,'web_site'=>$website,'fname'=>$first_name,'lname'=>$last_name,'mobile'=>$mobile);
		$this->db->where('user_id',$user_id);
		$ver = $this->db->update('manage_users',$data);
		if($ver){
		return true;
	}
		return false;
	}
	
	function delete_users($user_id){
		//$user_id = insep_decode($user_id);
		$this->db->where('user_id',$user_id);
		$this->db->delete('manage_users');
		return true;
	}
	
	function pms_delete_users($user_id){
		//$user_id = insep_decode($user_id);
		$this->db->where('partnar_id',$user_id);
		$this->db->delete('partners');
		return true;
	}
	
	 function get_all_channels($perpage,$urisegment)

    {
       $this->db->limit($perpage,$urisegment);

       $ver = $this->db->get('manage_channel');

       if($ver->num_rows()>0){

           return $ver->result_array();

       }

       else{

           return false;

       }

    }
	
	/*function hotel_name($channel_id){
		$res =  $this->db->query("select * from manage_hotel where find_in_set('".$channel_id."',connected_channel)");
		if($res->num_rows()>0){
			return $res->result();
		}
		else{
			return false;
		}
	}*/
	function hotel_name($channel_id){
		//$res =  $this->db->query("select user_membership_plan_details.*,manage_hotel.property_name from user_membership_plan_details JOIN manage_hotel.hotel_id = user_membership_plan_details.hotel_id  where find_in_set('".$channel_id."',user_membership_plan_details.connect_channels) AND user_membership_plan_details.plan_status = 1");
		$this->db->select('user_membership_plan_details.*,manage_hotel.property_name');
		$this->db->join('manage_hotel','manage_hotel.hotel_id = user_membership_plan_details.hotel_id');
		$this->db->where('user_membership_plan_details.plan_status',1);
		$this->db->where("FIND_IN_SET('".$channel_id."',user_membership_plan_details.connect_channels) !=", 0);
		$res = $this->db->get('user_membership_plan_details');
		if($res->num_rows()>0){
			return $res->result();
		}
		else{
			return false;
		}
	}
	
   function get_channels($hotel_id){
	   $this->db->where('hotel_id',$hotel_id);
	   $ver = $this->db->get('manage_hotel');
	   if($ver->num_rows()==1){
		   return $ver->row();
	   }
			return false;
   }
   /** Start Gayathri **/
   function save_channel_details($channel_id,$hotel_id){
   		$this->db->where('channel_id',$channel_id);
		$this->db->where('hotel_id',$hotel_id);
		$ver = $this->db->get('user_connect_channel');
		$get_urls = get_data("channel_urls",array("channel_id" => $channel_id))->row();
		$test_url = $get_urls->test_url;
		$live_url = $get_urls->live_url;
		$mode = $get_urls->mode;
		if($ver->num_rows() != 0)
		{
			$data['status'] = $this->input->post('inlineRadioOptions');
			$data['user_name'] = $this->input->post('user_name');
			$data['hotel_channel_id'] = $this->input->post('hotel_channel_id');
			$data['user_password'] = $this->input->post('user_password');
			$data['live_url'] = $live_url;
			$data['test_url'] = $test_url;
			$data['mode'] = $mode;
			if($this->input->post('web_id'))
			{
				$data['web_id'] = $this->input->post('web_id');
			}
			update_data(CONNECT,$data,array('channel_id'=>$channel_id,'hotel_id'=>$hotel_id));
			update_channel($channel_id,$data['status']);
			
		}
		return true;
   }
   function num_hotels()
	{
		$this->db->select("no_hotels");
		$count = $this->db->get('site_config');

		return $count->row()->no_hotels;
	}

   function get_plan_by_user_id($user_id, $hotel_id){

   		$this->db->select("user_membership_plan_details.*");
   		$this->db->select("subscribe_plan.plan_name");
   		$this->db->where("user_id",$user_id);
   		$this->db->where("hotel_id",$hotel_id);
		$this->db->where("plan_status",1);
   		$this->db->join("subscribe_plan","subscribe_plan.plan_id = user_membership_plan_details.buy_plan_id");
   		$plan = $this->db->get("user_membership_plan_details");
   		return $plan->result();
   }

   function get_expired_plan_by_user_id($user_id, $hotel_id){

   		$this->db->select("user_membership_plan_details.*");
   		$this->db->select("subscribe_plan.plan_name");
   		$this->db->where("user_id",$user_id);
   		$this->db->where("hotel_id",$hotel_id);
		$this->db->where("plan_status",2);
   		$this->db->join("subscribe_plan","subscribe_plan.plan_id = user_membership_plan_details.buy_plan_id");
   		$plan = $this->db->get("user_membership_plan_details");
   		return $plan->result();
   }

   function get_all_ind_plans(){
   		$this->db->where("base_plan","1");
   		$plans = $this->db->get("subscribe_plan");
   		return $plans->result();
   }
   /* Start Gayathri */
   function get_plan_details($planid){
   		$this->db->where("plan_id",$planid);
   		$this->db->where("base_plan","1");
   		$plans = $this->db->get("subscribe_plan");
   		return $plans->row();
   }

   function update_plan_by_user($data,$plan_id)
   {
   		$plandetails = get_data("user_membership_plan_details",array("user_buy_id"=>$plan_id))->row()->plan_status;
   		if($plandetails == 2)
   		{
   			$udata['plan_status'] = 1;
   		}
   		if($data->plan_types=='Free')
        {
            $plan = $data->plan_price;
            $plan_du = 'days';
        }
        elseif($data->plan_types =='Month')
        {
            $plan = 1;
            $plan_du = 'months';
        }
        elseif($data->plan_types =='Year')
        {
            $plan = 1;
            $plan_du = 'years';
        }
        $udata['connect_channels'] = implode(',',$this->input->post('subscribe_channel_id'));      
        $udata['disconnect_channels'] = "";
        $udata['plan_from'] = date("Y-m-d");
        $udata['plan_to'] = date("Y-m-d", strtotime("+".$plan.' '.$plan_du));
   		$udata['buy_plan_id'] = $data->plan_id;
   		$udata['buy_plan_price'] = $data->plan_price;
   		$udata['buy_plan_type'] = $data->plan_types;
   		$udata['buy_plan_currency'] = $data->currency;
   		$udata['total_channels'] = $data->number_of_channels;
   		update_data("user_membership_plan_details",$udata,array("user_buy_id"=>$plan_id));
   		return true;
   }

   function extend_plan($ext, $plan_id){
   		$plandetails = get_data("user_membership_plan_details",array("user_buy_id"=>$plan_id))->row()->plan_from;
   		$udata['plan_status'] = 1;
   		//date("Y-m-d", strtotime("+".$plan.' '.$plan_du,$dtime));
        $udata['plan_to'] = date('Y-m-d', strtotime("+".$ext.' days',strtotime($plandetails)));
        $udata['extend_plan'] = $ext;
   		update_data("user_membership_plan_details",$udata,array("user_buy_id"=>$plan_id));
   		return true;
   }

   /** End Gayathri **/
   
  function channel_name($chans,$perpage,$urisegment){

      $channel = explode(',',$chans);    

      $this->db->limit($perpage,$urisegment);

      $this->db->where_in('channel_id',$channel);

      $ver = $this->db->get('manage_channel');

      if($ver->num_rows()>0){

          return $ver->result();

      }

      else{

          return false;

      }

    }
   
   function user_name($user){
	   $this->db->order_by('user_id','desc');
	   $user = explode(',',$user);
	   $this->db->where_in('user_id',$user);
	   $ver = $this->db->get('manage_users');
	   if($ver->num_rows()>0){
		   return $ver->result();
	   }
	   else{
		   return false;
	   }
   }
   
   function property_name($channel){
	   $this->db->where('hotel_id',$channel);
	   $ver = $this->db->get('manage_property');
	   if($ver->num_rows()>0){
		   return $ver->result();
	   }
		   return false;
   }
   
   function property_id($pro){
	   $this->db->where('property_id',$pro);
	   $ver = $this->db->get('roommapping');
	   if($ver->num_rows()>0){
		   return $ver->result();
	   }
	   else{
		   return false;
	   }
   }
   
   function channels_name($ch){
	   $this->db->where('channel_id',$ch);
	   $ver = $this->db->get('manage_channel');
	   if($ver->num_rows()==1){
		   return $ver->row();
	   }
	   else{
		   return false;
	   }
   }
   
   function country_name($country){
	   $this->db->where('id',$country);
	   $ver = $this->db->get('country');
	   if($ver->num_rows()==1){
		   return $ver->row();
	   }
	   else{
		   return false;
	   }
   }
   
   function channel_details($hotel_id,$id){
	   $this->db->where('hotel_id',$hotel_id);
	   $this->db->where('channel_id',$id);
	   $ver = $this->db->get('user_connect_channel');
	   if($ver->num_rows()==1){
		   return $ver->row();
	   }
	   else{
		   return false;
	   }
   }
   
   function currency($currency){
	   $this->db->where('currency_id',$currency);
	   $ver = $this->db->get('currency');
	   if($ver->num_rows()==1){
		   return $ver->row();
	   }
	   else{
		   return false;
	   }
   }
   
   function get_all_users(){
	   $this->db->where('User_Type','1');
	   $this->db->where('status','1');
	   $this->db->where('acc_active','1');
	   $ver = $this->db->get('manage_users');
	   if($ver->num_rows()>0){
		   return $ver->result();
	   }
	   else{
		   return false;
	   }
   }
   
   function record_count() {
	$ver = $this->db->get('manage_users');
	if($ver->num_rows()>0){
		return $ver->num_rows();
	}
	else{
		return false;
	}
   }
   
   function add_user_det($hotel_id){
		 $owner_id = $this->input->post('owner_id');
		 $this->load->library(array('passwordhash'));
		 $total_success = $this->input->post('total_success');
		/*if($total_success!='')
		{
			$db_val='';
			for($i=1;$i<=$total_success;$i++)
			{
				if($this->input->post('view_'.$i)!='' || $this->input->post('edit_'.$i)!='')
				{
				if($this->input->post('view_'.$i)!='' && $this->input->post('edit_'.$i)=='')
				{
					 $insert_value = $this->input->post('view_'.$i).'~V';
				}
				else if($this->input->post('edit_'.$i)!='' && $this->input->post('view_'.$i)=='')
				{
					 $insert_value = $this->input->post('edit_'.$i).'^E';
				}
				else if($this->input->post('edit_'.$i)!='' && $this->input->post('view_'.$i)!=''){
					$insert_value = $this->input->post('edit_'.$i).'~V^E';
				}
				$db_val .=  $insert_value.' , ';
				}
			}
			   $value = rtrim($db_val,' , ');
			   
		}*/
		$value = json_encode($this->input->post('access_options'));
		 $access = $value;
	     $user_name = $this->input->post('user_name');
		 $email_address = $this->input->post('email_address');
		 $user_password = $this->input->post('user_password');
		 $confirm_password = $this->input->post('confirm_password');
		 $t_hasher = new PasswordHash(8, FALSE);
		 $hash = $t_hasher->HashPassword($this->input->post('user_password'));
		
			$data = array(
							'User_Type'=>'2',
							'owner_id'=>$owner_id,
							'user_name'=>$user_name,
							'email_address'=>$email_address,
							'password'=>$hash,
							'spass'=>$this->input->post('user_password'),
							'ipaddress' 	=> 	$_SERVER['REMOTE_ADDR'],
							'user_agent'	=>  $_SERVER['HTTP_USER_AGENT'],
							'status'	=>  '0',
							'acc_active'	=>  '0',
							);
				if(insert_data(TBL_USERS,$data)){
					$user_id = $this->db->insert_id();
					$data = array('user_id'=>$user_id,
					  'access'=>$value,
					  'owner_id'=>$owner_id,
					  'hotel_id'=>$hotel_id);
					if(insert_data('assign_priviledge',$data))
					{
						$priviledge_id = $this->db->insert_id();
						$this->db->where('priviledge_id',$priviledge_id);
						$res = $this->db->get('assign_priviledge');
						if($res->num_rows()==1){
							$ass = $res->row();
							$ass_id = $ass->user_id;
						}
						$this->db->where('hotel_id',$hotel_id);
						$ver = $this->db->get('manage_hotel');
						if($ver->num_rows()==1){
							$var = $ver->row();
						    $assign = $var->assigned_user;
							if($assign){
								$dean = $assign.','.$ass_id;
							}
							else{
								$dean = $ass_id;
							}
							$data = array('assigned_user'=>$dean);
							$this->db->where('hotel_id',$hotel_id);
							$res = $this->db->update('manage_hotel',$data);
							if($res){
								return true;
							}
							else{
								return false;
							}
						}
						else{
							$varia = $this->db->insert('manage_hotel',$data);
							if($varia){
								return true;
							}
							else{
								return false;
							}
						}
					}
				}
				else{
					return false;
				}
	}
	
	function get_user_name($user_id){
		$this->db->where('user_id',$user_id);
		$ver = $this->db->get('manage_users');
		if($ver->num_rows()==1){
			return $ver->row();
		}
		else{
			return false;
		}
	}
	
	function update_users($hotel_id){
		$user_name = $this->input->post('user_name');
		$email_address = $this->input->post('email_address');
		$town = $this->input->post('town');
		$address = $this->input->post('address');
		$zip_code = $this->input->post('zip_code');
		$mobile = $this->input->post('mobile');
		$currency_name = $this->input->post('currency_name');
		$country_name = $this->input->post('country_name');
		$data = array('email_address'=>$email_address,'town'=>$town,'address'=>$address,'zip_code'=>$zip_code,'mobile'=>$mobile,'currency'=>$currency_name,'country'=>$country_name);
		$this->db->where('hotel_id',$hotel_id);
		$ver = $this->db->update('manage_hotel',$data);
		if($ver){
			return true;
		}
		else{
			return false;
		}
	}

	function full_currency(){
		$ver = $this->db->get('currency');
		if($ver->num_rows()>0){
			return $ver->result();
		}
		else{
			return false;
		}
	}
	
	function full_country(){
		$ver = $this->db->get('country');
		if($ver->num_rows()>0){
			return $ver->result();
		}
		else{
			return false;
		}
	}
	 
	 function all_hotel($perpage,$urisegment)

    {

       $this->db->limit($perpage,$urisegment);
	   $this->db->where('status','1');
       $query = $this->db->get(HOTEL);

       if($query)

       {

           return $query->result_array();       

       }

       else

       {

           return false;

       }

    }
	
	function all_user_hotel()
	{
		$this->db->where('status','1');
		$query = $this->db->get(HOTEL);
		if($query)
		{
			return $query->result_array();       
		}
		else
		{
			return false;
		}
	}
	
	function all_users($perpage,$urisegment)

    {

       $this->db->limit($perpage,$urisegment);
	   $this->db->where('User_Type','1');
	   $this->db->where('status','1');
	   $this->db->where('acc_active','1');
       $query = $this->db->get(TBL_USERS);

       if($query)

       {

           return $query->result();       

       }

       else

       {

           return false;

       }

    }
	
	function reservation_all_channel()
   {
	   $all_channel = get_data(TBL_CHANNEL)->result_array();
	   if(count($all_channel)!=0)
	   {
		   $ch_name[]='Hoteratus';
		   foreach($all_channel as $channel)
		   {
			   extract($channel);
			   $ch_name[]=$channel_name;
		   }
		   
		   return json_encode($ch_name);
	   }
   }
   
   function reservation_all_hotel()
   {
	   $all_hotel = get_data(HOTEL,array('status'=>1))->result_array();
	   //$all_hotel = get_data(HOTEL)->result_array();
	   if(count($all_hotel)!=0)
	   {
		   foreach($all_hotel as $hotel)
		   {
			   extract($hotel);
			   $ho_name[]=$property_name;
		   }
		   
		   return json_encode($ho_name);
	   }
   }
function add_partner($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['right_content']=$content;
			$idata['image_content']=$image_content;
			if($image!='')
			{
				$idata['image']=$image;
			}
			if($id)
			{
				update_data('partner_cms',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('partner_cms',$idata);
				$id=$this->db->insert_id();
			}
			
				return $id;
			//	return true;
		}
		   else
			return false;
	}
	function get_partnercms_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("partner_cms");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
	}
	function confirm_pass($email,$pass){
 		$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
 		$salt = sha1(time().mt_rand());
		$new_key = substr($salt, 0, 40);
		 $data=array(	
				'password'=>$pass,
				'status'=>'Confirmed',
                                'apikey'=>$new_key
				);
			$this->db->where('email',$email);
			$this->db->update('partners',$data);  
		 
          $get_email_info        =    get_mail_template('17');
          $subject        =    $get_email_info['subject'];
          $template        =    $get_email_info['message'];
          $data = array(
              '###USERNAME###'=>$email,
              '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
              '###SITENAME###'=>$admin_detail->company_name,
              '###PASSWORD###'=>$pass,
              '###APIKEY###'=>$new_key,
              '###EMAIL###'=>$email,
              );
          $content_pop = strtr($template,$data);
          $this->mailsettings();
          $this->email->from($admin_detail->email_id);
          $this->email->to($email);
          $this->email->bcc($admin_detail->pms_support);
          $this->email->bcc("thirupathi@osiztechnologies.com");
          $this->email->subject($subject);
          $this->email->message($content_pop);
          if($this->email->send())
          {
           echo "Mail Has been sent"; 
          }

	}

	function add_integration($id="",$image='')
	{
		$date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['content']=$content;
			$idata['image_content']=$image_content;
			if($image!='')
			{
				$idata['image']=$image;
			}
			if($id)
			{
				update_data('integration_cms',$idata,array("id"=>$id));
			}
			else
			{
				$idata['created_date']=$date;
				insert_data('integration_cms',$idata);
				$id=$this->db->insert_id();
			}
			
				return $id;
			//	return true;
		}
		   else
			return false;
	}
	
	function get_integrationcms_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("integration_cms");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
	}




	function add_multiproperty($id="",$image='')

	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('multiproperty',$idata,array("id"=>$id));
			}
			else
			{

				$idata['created_date']=$date;

				insert_data('multiproperty',$idata);

				$id=$this->db->insert_id();

			}

			

				return $id;

			//	return true;

		}

		else

			return false;

	}
	
	function get_multiproperty($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("multiproperty");
		//$this->db->where('id',$id);
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_multiproperty_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("multiproperty");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	function add_connectchannels($id="",$image='')

	{
        $date=date("Y-m-d");
		if($this->input->post())
		{
			extract($this->input->post());
			$idata['title']=$title;
			$idata['content']=$content;
			$idata['image']=$image;
			if($id)
			{
				update_data('connectchannels',$idata,array("id"=>$id));
			}
			else
			{

				$idata['created_date']=$date;

				insert_data('connectchannels',$idata);

				$id=$this->db->insert_id();

			}

			

				return $id;

			//	return true;

		}

		else

			return false;

	}
	
	function get_connectchannels($id='',$result_mode="array")
	{
		$this->db->select("*");
		$this->db->from("connectchannels");
		//$this->db->where('id',$id);
		if($result_mode=="array")
		{
			$this->db->order_by('id','desc');
			return $result= $this->db->get()->result();
		}
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	
	function get_connectchannels_details($id,$result_mode="")
	{
		$this->db->select("*");
		$this->db->from("connectchannels");
		$this->db->where('id',$id);
		if($result_mode=="array")
			return $result= $this->db->get()->row_array();
		else
			return $result= $this->db->get()->row();
		//print_r($result);
	}
	

	function nofification(){
     $data=array(
           'type'=>$this->input->post('type'),
           'sendto'=>$this->input->post('sendto'),
           'title'=>$this->input->post('title'),
           'content'=>$this->input->post('content'),
           'status'=>'unseen',
     	);
        $this->db->insert('notifications',$data);
        return true;
	   

	}

	function delecte_notification($id){
		$this->db->where("n_id",$id);
		$this->db->set("delflag",1);
		$query = $this->db->update("notifications");
		if($query)
			return true;
		return false;
	}
	function getAllNotifications(){
		$this->db->where_in("type",array('1','2'));
		$this->db->where("delflag",0);
		$this->db->order_by("n_id","desc");
		$query = $this->db->get("notifications");
		return $query->result();
	}

	function time2string($timeline) {
    $periods = array('day' => 86400, 'hour' => 3600, 'minute' => 60);

    foreach($periods AS $name => $seconds){
        $num = floor($timeline / $seconds);
        $timeline -= ($num * $seconds);
        if($num!=0){
          $ret .= $num.' '.$name.(($num > 1) ? 's' : '').'';
       }
    }
    return trim($ret);
   }

   // 04/02/2016..

  function time_elapsed_string($datetime)
	{	 
		$time_ago = strtotime($datetime);
	    $cur_time   = time();
	    $time_elapsed   = $cur_time - $time_ago;
	    $seconds    = $time_elapsed ;
	    $minutes    = round($time_elapsed / 60 );
	    $hours      = round($time_elapsed / 3600);
	    $days       = round($time_elapsed / 86400 );
	    $weeks       = round($time_elapsed / 864000 );
	    // $weeks      = round($time_elapsed / 604800);
	  /*  $months     = round($time_elapsed / 2600640 );
	    $years      = round($time_elapsed / 31207680 );*/
	    // Seconds....
	    if($seconds <= 60){
	        return "Just now";
	    }
	    //Minutes....
	    else if($minutes <=60){
	        if($minutes==1){
	            return "one minute";
	        }
	        else{
	            return "$minutes minutes";
	        }
	    }
	    //Hours...
	    else if($hours <=24){
	        if($hours==1){
	            return "an hour";
	        }else{
	            return "$hours hrs";
	        }
	    }
	    //Days...
	    else if($days <= 10){
	        if($days==1){
	            return "yesterday";
	        }else{
	            return "$days days";
	        }
	    }
	    else{
	          return date('M d',strtotime(str_replace('/','-',$datetime)));
	        }
	}
function get_partner_details($id="")
	{
		if($id!=""){
		   $this->db->where('partnar_id',$id);	
           $sql= $this->db->get('partners');  
		}else{
			$sql=$this->db->get('partners');
		}
	    if($sql->num_rows() > 0){
	    	return $sql->result();
	    }else{
	    	return false;
	    }
	}
	function delete_partner($user_id){
		$this->db->where('partnar_id',$user_id);
		$this->db->delete('partners');
		return true;
	}
	function num_channels()
	{
		$count = $this->db->select('channel_id')->from('manage_channel')->
		where(array('status'=>'Active'))->count_all_results();
		return $count;
	}
	function num_channels_plan()
	{
		$count = $this->db->select('channel_id')->from('manage_channel')->
		where('status = "Active" OR status = "New"')->count_all_results();
		return $count;
	}

	function deletehotel($hotel_id){
		if($this->db->delete(HOTEL,array('hotel_id'=>$hotel_id))){

			$this->db->delete(CONNECT,array('hotel_id'=>$hotel_id));
			$this->db->delete(TBL_UPDATE,array('hotel_id'=>$hotel_id));
			$this->db->delete(RATE_TYPES,array('hotel_id'=>$hotel_id));
			$this->db->delete(RATE_BASE,array('hotel_id'=>$hotel_id));
			$this->db->delete(RATE_ADD,array('hotel_id'=>$hotel_id));
			$this->db->delete(RESERVATION,array('hotel_id'=>$hotel_id));
			$this->db->delete(MEMBERSHIP,array('hotel_id'=>$hotel_id));
			$this->db->delete(TBL_PROPERTY,array('hotel_id' => $hotel_id));
			$this->db->delete('import_mapping',array('hotel_id'=>$hotel_id));
			$this->db->delete(IM_RECO,array('hotel_id'=>$hotel_id));
			$this->db->delete(IM_GTA,array('hotel_id'=>$hotel_id));
			$this->db->delete('import_mapping_HOTELBEDS',array('hotel_id'=>$hotel_id));
			$this->db->delete('import_reservation_HOTELBEDS',array('hotel_id'=>$hotel_id));
			$this->db->delete(MAP,array('hotel_id'=>$hotel_id));
			$this->db->delete(BOOKING,array('hotel_id'=>$hotel_id));
			$this->db->delete(REC_RESERV,array('hotel_id'=>$hotel_id));
			$this->db->delete(GTA_RESERV,array('hotel_id'=>$hotel_id));				
			$this->db->delete(EXP_RESERV,array('hotel_id'=>$hotel_id));
			$this->db->delete(BOOK_RESERV,array('hotel_hotel_id'=>$hotel_id));
			$this->db->delete(BOOK_ROOMS,array('hotel_hotel_id'=>$hotel_id));
			return true;
		}else{
			return false;
		}
	}
	function fetch_access($getacc){
		$this->db->where('acc_id',$getacc);
		$query = $this->db->get('user_access');
		if($query->num_rows > 0)
		{
			return $query->row();
		}
			return false;
	}
	
	
	function get_priviledge($user_id,$owner_id){
		$this->db->where('user_id',$user_id);
		$this->db->where('owner_id',$owner_id);
		$query = $this->db->get("assign_priviledge");
		return $query->result();
	}
}


?>