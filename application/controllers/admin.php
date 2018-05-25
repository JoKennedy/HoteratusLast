<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');

class admin extends CI_Controller
{
	public function __construct()
   {
		parent::__construct();	
		
		/*if(uri(3)!='getResrvationCronFromExpdiaTest')
		{
			$ip = $this->input->ip_address();
			if( !in_array($ip, explode(',', str_replace(' ', '', IPWHITELIST))) )
			{
				mail("datahernandez@gmail.com","Illegal Access From Hoteratus",$ip);
				?>
				<img width="1350" height="600" src="data:image/png;base64,<?php echo base64_encode(file_get_contents('user_assets/images/under.jpg'));?>" class="img-responsive" data="<?php echo insep_encode($ip); ?>">
				<?php
				die;
			}
		}*/

		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache"); 	
		$data=$this->admin_model->generall();
		$this->load->library('pagination');
		$this->load->helper('ckeditor');
		// Your own constructor code
	}
       
   function index()
   {
		$this->load->view('admin/login');
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
	
	
    function pageconfig($total_rows,$base,$perpage)
    {
       $perpage = $perpage;
       $pages = (ceil($total_rows/$perpage));
       $this->session->set_userdata('page',$pages);
       $urisegment=$this->uri->segment(4);
       $this->load->library('pagination');
       $config['base_url'] = base_url().'/admin/'.$base.'/';
       $config['total_rows'] = $total_rows;
       $config['per_page'] = $perpage;
       $config['num_links']= 3; // Number of "digit" links to show before/after the currently viewed page
       $config['full_tag_open'] = '';
       $config['full_tag_close'] = '';
       $config['cur_tag_open'] = '<li class="active"><a href="">';
       $config['cur_tag_close'] = '</li></a>';
       /*$config['first_link'] = '<li>First</li>';*/
       $config['first_link'] = 'First';
       $config['first_tag_open'] = '<li>';
       $config['first_tag_close'] = '</li>';
       $config['last_link'] = 'last';
       $config['last_tag_open'] = '<li>';
       $config['last_tag_close'] = '</li>';
       $config['prev_link'] = '<i class="fa fa-arrow-left"></i> Previous ';
       $config['prev_tag_open'] = '<li>';
       $config['prev_tag_close'] = '</li>';
       $config['next_link'] = ' Next <i class="fa fa-arrow-right"></i> ';
       $config['next_tag_open'] = '<li class="next">';
       $config['next_tag_close'] = '</li>';
       $config['num_tag_open'] = '<li>';
       $config['num_tag_close'] = '</li>';    
       $this->pagination->initialize($config);            
    }
    function pageconfig_new($total_rows,$base,$perpage,$link)
    {
       $perpage = $perpage;
       $pages = (ceil($total_rows/$perpage));
       $this->session->set_userdata('page',$pages);
       $urisegment=($this->uri->segment(5));
       $this->load->library('pagination');
       $config['base_url'] = base_url().'/admin/'.$base.'/'.$link.'/';
       $config['uri_segment'] = 5;
       $config['total_rows'] = ($total_rows);
       $config['per_page'] = ($perpage);
       $config['num_links']= 3; // Number of "digit" links to show before/after the currently viewed page
       $config['full_tag_open'] = '';
       $config['full_tag_close'] = '';
       $config['cur_tag_open'] = '<li class="active"><a href="">';
       $config['cur_tag_close'] = '</li></a>';
       /*$config['first_link'] = '<li>First</li>';*/
       $config['first_link'] = 'First';
       $config['first_tag_open'] = '<li>';
       $config['first_tag_close'] = '</li>';
       $config['last_link'] = 'last';
       $config['last_tag_open'] = '<li>';
       $config['last_tag_close'] = '</li>';
       $config['prev_link'] = '<i class="fa fa-arrow-left"></i> Previous ';
       $config['prev_tag_open'] = '<li>';
       $config['prev_tag_close'] = '</li>';
       $config['next_link'] = ' Next <i class="fa fa-arrow-right"></i> ';
       $config['next_tag_open'] = '<li class="next">';
       $config['next_tag_close'] = '</li>';
       $config['num_tag_open'] = '<li>';
       $config['num_tag_close'] = '</li>';    
       $this->pagination->initialize($config);            
    }
	function index1()
	{
		
		$data['records'] = $this->admin_model->chklogin();
		$username=$this->input->post('username');
		$pwd=$this->input->post('password');
		$query = $this->admin_model->getadmin($username);
		if($query)
		{
			$id=$query->id;
			$user=$query->login_name;
			$user_type=$query->user_type;
			$admin_type=$query->admin_type;
			$this->session->set_userdata('user',$user);
			$this->session->set_userdata('logged_user',$id);
			$this->session->set_userdata('logged_user_type',$user_type); 
			$this->session->set_userdata('admin_type',$admin_type);
			$this->session->userdata('logged_user_type');
			$id=$this->session->userdata('logged_user');
			redirect('admin/dashboard','refresh');
		}
	}
	function index2()
	{
			$login_check = $this->admin_model->chklogin();
			if($login_check){
			$username=$this->input->post('username');
			$pwd=$this->input->post('password');
			if($login_check)
			{
				$id=$login_check->id;
				$user=$login_check->login_name;
				$user_type=$login_check->user_type;
				$admin_type=$login_check->admin_type;
				$this->session->set_userdata('user',$user);
				$this->session->set_userdata('logged_user',$id);
				$this->session->set_userdata('logged_user_type',$user_type);
				$this->session->set_userdata('admin_type',$admin_type);				
			    $this->session->userdata('logged_user_type');
				$id=$this->session->userdata('logged_user');
				redirect('admin/dashboard','refresh');
			}
		}else{
			 $data['error']="Invalid Username and Password";
             redirect('admin');
		}
		
	}
	function login1($login="")
	{
		if($login==1)
		{
			
			$data['reg']="Your Password has been sent to you mail";
			$this->load->view('admin/login1',$data);
		}
		elseif($login==2)
		{
			$data['reg']="Your Password has been sent to you mail";
			$this->load->view('admin/login1',$data);
		}
	}
	
	
	function dashboard()
	{	
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');
		}
		else
		{
		$data= $this->admin_model->get_month_record();
		//print_r($data);die;
	    $datas['counts']=implode(",",$data);
		$this->load->view('admin/dashboard',$datas);  
		}
	}
	
	function admin_setting()
	{
		$sessionvar=$this->session->userdata('logged_user');
		//get admin control id
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data['records'] = $this->admin_model->get_adminsetting();
		$this->load->view('admin/admin_setting',$data);
		}
	}
	
	function admin_setting_updated()
	{ 
		$sessionvar=$this->session->userdata('logged_user');
			if($sessionvar=="")
			{
			//$this->load->view('admin_login',$data);
			redirect('admin/index','refresh');
			}
			else
			{
				$data['records']=$this->session->userdata('logged_user');
				$this->load->model('admin_model');  
						
				$query = $this->admin_model->get_adminsetting();
				
				 foreach($query as $r)
				{
					$ctrlid=$r->admin_controlid;
					$this->session->userdata('sess',$ctrlid);
					$this->admin_model->admin_setting_update();
				}
					$this->session->set_flashdata('success', "Admin Controls has been modified ");
				redirect('admin/admin_setting');   
			}
		
	} 
	
	function manage_admin_details()
	{
		$this->load->model('admin_model');
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data['records'] = $this->admin_model->get_admin();
		$this->load->view('admin/manage_admin_details',$data);
		}
	}
	
	function update_admin($id)
	{
		$urisegment=$this->uri->segment(4);
		$this->session->set_userdata('admin_id',$urisegment);
	     	$sessionvar=$this->session->userdata('logged_user');
			if($sessionvar=="")
			{
			//$this->load->view('admin_login',$data);
			redirect('admin/index','refresh');
			}
			else
			 {
				
			$admin_image=$_FILES['admin_image']['name'];
			if ($admin_image!="")
			{
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('admin_image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					redirect('admin/edit_admin/'.$id.'','refresh');
				}  
				$r=$this->admin_model->admin_details_update($id,$profile_image);
			}else
			{
				$get_image= $this->admin_model->get_admin($id);
				foreach ($get_image as $value) {
				    $admin_image1=$value->admin_image;
				}
				$r=$this->admin_model->admin_details_update($id,$admin_image1);
				$this->session->set_flashdata('success', "Admin user has been modified");
				redirect('admin/manage_admin_details','refresh');
			}		
			$this->session->set_flashdata('success', "Admin user has been modified");
			redirect('admin/manage_admin_details','refresh');
		
	}
	}
	
	function edit_admin($id)
	{
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		//exit;
		redirect('admin/index','refresh');
		}
		else
		{
		$data['records']=$this->session->userdata('logged_user');
		}
	    $query = $this->admin_model->get_admin($id);
		foreach($query as $r)
		{
			$data['id'] =  $r->id;
			$data['login_name'] =  $r->login_name;
			$data['password'] = $r->password;
			$data['name'] =  $r->name;
			$data['email_id'] = $r->email_id;
			$data['admin_image'] = $r->admin_image;
			$data['admin_controlid'] =  $r->admin_controlid;
			$data['country'] =  $r->country;
			$data['city'] =  $r->city;
		}
		
		$this->load->view('admin/edit_admin',$data);
	}
	
	function status_change($id)
	{
		$id=$this->admin_model->decryptIt($id);
		$session=$this->session->userdata('logged_user');
		if($session=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			$this->load->model('admin_model');  
			$d=$this->admin_model->change_status($id);
			 {		
				 // $query = $this->admin_model->getadmindata($id);
									 // foreach($query as $row)
									 // {
									 // $id=$row->id;
									 // $username=$row->name;
									 // $status=$row->status;
									// }
//echo $username;exit;									 
					$this->session->set_flashdata('success', "Admin Status Updated Successfully");
				redirect('admin/manage_admin_details','refresh');			
			}
		}
	}
	
	function deleteadmin($id)
	{
		$id=$this->admin_model->decryptIt($id);
		$session=$this->session->userdata('logged_user');
		if($session=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			$this->load->model('admin_model');  
			if($this->admin_model->deleteadmindetails($id))	
			 {	
				$this->session->set_flashdata('success', "Adminuser has been deleted ");
				redirect('admin/manage_admin_details','referesh');			
			}
		}
	}
	
	function add_admin()
	{
		//echo "sss";die;
		//$this->load->model('admin_model');
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
			else
			{
		$data['id']='';
		$data['email_id']='';
		$this->load->view('admin/add_admin',$data);
		}
	}
	
	function addnew_admin_details()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
			else
			{
			   $admin_image=$_FILES['admin_image']['name'];  
				if($admin_image!="")
				{
	                $rnumber = mt_rand(0,999999);
					$ext = substr(strrchr($admin_image, "."), 1);
					$filename=$rnumber;
					$profile_image=$filename.".".$ext;
					$config['upload_path'] ='uploads';
				    $config['allowed_types'] = 'gif|jpg|jpeg|png|tiff|bmp';
					$config['file_name']=$filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);	
					if($admin_image!="" & (!$this->upload->do_upload('admin_image')))
					{
						$error="Profile image ".$this->upload->display_errors();
						$this->session->set_flashdata('error',$error);
						redirect('admin/admin_registered','refresh');
					}
					$r=$this->admin_model->entry_admin_details($profile_image);
					redirect('admin/manage_admin_details','refresh');
				}
				else
				{
					$admin_image1="default_adminuser.jpg";
					$r=$this->admin_model->entry_admin_details($admin_image1);
					redirect('admin/manage_admin_details','refresh');
				}
		
		}
			  
	}
	
	
	function admin_profile()
	{ 
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		//exit;
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$data['records']=$this->session->userdata('logged_user');
		}
	
	   $query = get_data('manage_admin_details',array('id'=>$sessionvar))->result();  
		foreach($query as $r)
		{
			$data['id'] =  $r->id;
			$data['login_name'] =  $r->login_name;
			$data['password'] = $r->password;
			$data['name'] =  $r->name;
			$data['email_id'] = $r->email_id;
			$data['admin_image'] = $r->admin_image;
			$data['status'] =  $r->status;
		}
		$this->load->view('admin/profile_update',$data);  
	} 
	
	function profile_updated()
	{ 
			$sessionvar=$this->session->userdata('logged_user');
			if($sessionvar=="")
			{
			//$this->load->view('admin_login',$data);
			redirect('admin/index','refresh');
			}
			else
			{
			$data['records']=$this->session->userdata('logged_user');
			$id=$this->input->post('id');
			$this->load->model('admin_model');  
			//$query = $this->admin_model->get($sessionvar);
			$admin_image=$_FILES['admin_image']['name'];
			if ($admin_image!="")
			{
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('admin_image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					redirect('admin/admin_profile','refresh');
				}  
				$r=$this->admin_model->entry_update($profile_image);
			}else
			{
			    $hidimage=$this->input->post('hidimage');
				$r=$this->admin_model->entry_update($hidimage);
			}
			
		$query = get_data('manage_admin_details',array('id'=>$sessionvar))->result(); 
		foreach($query as $r)
		{
			$data['id'] =  $r->id;
			$data['login_name'] =  $r->login_name;
			$data['password'] = $r->password;
			$data['name'] =  $r->name;
			$data['email_id'] = $r->email_id;
			$data['admin_image'] = $r->admin_image;
			$data['status'] =  $r->status;
		}
		$this->session->set_flashdata("success","Admin has been Updated successfully");
		redirect('admin/admin_profile');
		//$this->load->view('admin/profile_update',$data); 
		  }
		
	} 
	
	
	function site_config()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$this->load->model('admin_model');
		//$this->load->library('session');
		//this->session->set_userdata('uid',$id);
		
	    $query = $this->admin_model->get_siteconfig();
		foreach($query as $r)
		{
		
	    $data['company_name'] =  $r->company_name;
	    $data['contact_person'] = $r->contact_person;		
	    $data['email_id'] = $r->email_id;
		$data['address'] =  $r->address;
		$data['city'] = $r->city;
		$data['state'] =  $r->state;
		$data['country'] = $r->country;
		$data['phno'] = $r->phno;
		$data['fax_no'] =  $r->fax_no;
$data['apikey'] =  $r->apikey;
		
		
		$data['databases'] =  $r->databases;
		
		$data['siteurl'] =  $r->siteurl;
	    $data['facebook_url'] =  $r->facebook_url;
	    $data['twitter_url'] = $r->twitter_url;
		$data['google'] =  $r->google;
	    $data['linkedin'] = $r->linkedin;
		$data['skype'] = $r->skype;
		$data['pinterest'] =  $r->pinterest;
		$data['youtube'] = $r->youtube;
		$data['flickr'] = $r->flickr;
		$data['site_title'] = $r->site_title;
		$data['meta_title'] = $r->meta_title;
		$data['site_description'] =  $r->site_description;
		
		$data['site_keyword'] =  $r->site_keyword;
	    $data['site_logo'] =  $r->site_logo;
	    $data['user_defaultpic'] = $r->user_defaultpic;
		$data['contactus_image'] = $r->contactus_image;
		$data['users_dateformat'] = $r->users_dateformat;
		
		$data['facebook_url'] = $r->facebook_url;
		$data['twitter_url'] = $r->twitter_url;
		$data['google'] = $r->google;
		$data['linkedin'] = $r->linkedin;
		$data['skype'] = $r->skype;
		$data['pinterest'] = $r->pinterest;	
		
		$lat = substr($r->map_location, strpos( $r->map_location,",") + 1); 
		$lang = explode(",", $r->map_location);
		
		$data['lat'] = $lat;
		$data['lang'] = $lang[0];
	
		
		$data['youtube'] = $r->youtube;
		$data['flickr'] = $r->flickr;
		
		$data['analytics'] = $r->analytics;
                $data['Site_key'] = $r->Site_key;
		$data['Secret_key'] = $r->Secret_key;

		$data['ticket_mail'] = $r->ticket_mail;
		
		}
		
		$this->load->view('admin/site_config',$data);
		}
	}
	
	function site_config_updated()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
				redirect('admin/index','refresh');
		}
		else
		{
			$data['records']=$this->session->userdata('logged_user');	
					
			$site_logo=$_FILES['site_logo']['name']; 
			if($site_logo!="")
			{
				/* unlink("uploads/logo/".$data['site_logo']);
				unlink("uploads/logo/small/".$data['site_logo']); */
				$rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($site_logo, "."), 1);
				$filename=$rnumber;
				$site_logo=$filename.".".$ext;
				$config['upload_path'] ='uploads/logo';
				$config['allowed_types'] = 'gif|jpg|jpeg|png|tiff|bmp';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($site_logo!="" & (!$this->upload->do_upload('site_logo')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					redirect('admin/site_config','refresh');
				}
				else
				{
					image_resizer1("uploads/logo/",$site_logo, 140, 80);
					//image_resizer("uploads/logo/small",$site_logo, 20, 19);
				}
			}
			else
			{
				$query = $this->admin_model->get_siteconfig();
				foreach($query as $r)
				{
					$data['site_logo'] = $r->site_logo;
				}
							//echo "sss";die;
				$site_logo=$data['site_logo'];
				 //$admin_image="11376Sunset.jpg";
			}
			
			$contactus_image=$_FILES['contactus_image']['name']; 
			if($contactus_image!="")
			{
				unlink("uploads/logo/".$data['contactus_image']);
				$rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($contactus_image, "."), 1);
				$filename=$rnumber;
				$contactus_image=$filename.".".$ext;
				$config['upload_path'] ='uploads/logo';
				$config['allowed_types'] = 'gif|jpg|jpeg|png|tiff|bmp';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($site_logo!="" & (!$this->upload->do_upload('contactus_image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					redirect('admin/site_config','refresh');
				}
				else
				{
					image_resizer("uploads/logo/",$contactus_image, 1600, 500);
				}
			}
			else
			{
				$query = $this->admin_model->get_siteconfig();
				foreach($query as $r)
				{
					$data['contactus_image'] = $r->contactus_image;
				}
							//echo "sss";die;
				$contactus_image=$data['contactus_image'];
				//$admin_image="11376Sunset.jpg";
			}
			
			$user_defaultpic=isset($_FILES['user_defaultpic']['name']); 
            if($user_defaultpic!="")
			{ 
				$rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($user_defaultpic, "."), 1);
				$filename=$rnumber;
				$user_defaultpic=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($user_defaultpic!="" & (!$this->upload->do_upload('user_defaultpic')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					redirect('admin/site_config','refresh');
				}
			}
			else
			{
				$query = $this->admin_model->get_siteconfig();
				foreach($query as $r)
				{
				$data['user_defaultpic'] = $r->user_defaultpic;
				}
					//echo "sss";die;
				$user_defaultpic=$data['user_defaultpic'];
			   //$admin_image="11376Sunset.jpg";
			}
			$r=$this->admin_model->site_config_update($site_logo,$user_defaultpic,$contactus_image);
			if($r)
			{
				$this->session->set_flashdata("success","Site configuration has been updated successfully");
			}
			redirect('admin/site_config','refresh');
		}
	}
	
	function theme_customize()
	{
		$sessionvar				=	$this->session->userdata('logged_user');
		
		if($sessionvar=="")
		{
				redirect('admin/index','refresh');
		}
		else
		{
			if($this->input->post('h_header_one')!='')
			{
				extract($this->input->post());
				
				foreach( $this->input->post() as $key=>$input)
				{
					$data[$key] = '#'.$input;
				}
				
				$theme_customize	=	get_data('theme_customize',array('theme_customize_id'=>1))->row_array();
				
				if(count($theme_customize)==0)
				{
					insert_data('theme_customize',$data);
					
					redirect('admin/theme_customize','refresh');
				}
				else
				{
					update_data('theme_customize',$data,array('theme_customize_id'=>1));
					
					$this->session->set_flashdata("success","Theme Customize has been updated successfully");
					
					redirect('admin/theme_customize','refresh');
				}
			}
			else
			{
				$data['theme_customize']	=	get_data('theme_customize',array('theme_customize_id'=>1))->row_array();
				
				$this->load->view('admin/theme_customize',$data);
			}
		}
	}
	
	
	//munish
	function manage_user($mode='',$id="")
	{
	
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'active':
								if(!$id)
									die();
						$email_address = get_data(TBL_USERS,array('user_id'=>$id))->row()->email_address;
						
					 update_data("manage_users",array("multiproperty"=>'Active'),array("user_id"=>$id));
					//send mail
					 
							
							$admin_mail = get_data('manage_admin_details',array('id'=>'1'))->row();
					
							$email=$this->email->from($admin_mail->email_id);
							$site_config	=	$this->admin_model->get_siteconfig();
							if($site_config)
							{
								foreach($site_config as $site_config)
								{
									$company_name	=	$site_config->company_name;
									$emailid	=	$site_config->email_id;
									$site_logo  = $site_config->site_logo;
								}
							}
						$this->db->where('id','16');  			
									$queryemail_temp=$this->db->get('Email_Templates'); 
									if($queryemail_temp->num_rows()==1)  
									{ 
										$get_emailtemp=$queryemail_temp->row(); 
										$msg=$get_emailtemp->message; 
										$subject=$get_emailtemp->subject; 
										$mail_content = array(
																'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$site_logo,
																'###SITENAME###'=>$company_name,
																'###STATUS###'=>'Active',
																'###USERNAME###'=>$email_address
															);
									 
										$content=strtr($msg,$mail_content);  				  
										$msg=$content;
										$this->mailsettings();
										$this->email->from($email,$company_name);
										$this->email->to($email_address);
										$this->email->subject($subject);
										$this->email->message($msg);
										$this->email->send();
										
									}
				
				   
				
								$this->session->set_flashdata("success","$email_address multiproperty option enabled successfully");
								redirect('admin/manage_user/view');
								break;
							case 'inactive':
								if(!$id)
									die();
							$email_address = get_data(TBL_USERS,array('user_id'=>$id))->row()->email_address;
						
							update_data("manage_users",array("multiproperty"=>'Deactive'),array("user_id"=>$id));
							$this->session->set_flashdata("success","$email_address multiproperty option disabled successfully");
								redirect('admin/manage_user/view');
								break;
							case 'view':
								$this->db->order_by('user_id','desc');
								$data["users"] =get_users('',array('User_Type'=>1));
								$this->load->view('admin/Manage_users',$data);
								break;
							case 'edit':
								if(!$id)
									die();
								$udata=$this->admin_model->get_user_details($id,"array");
								$data=array_merge($udata,$data);
								$this->load->view('admin/Manage_users',$data);
								break;
							case 'delete':
								if(!$id)
									die();
								delete_data("manage_users",array("user_id"=>$id));
								redirect('admin/manage_user/view');
								break;
							case 'status':
								if(!$id)
									die();
								$user=get_data("manage_users",array("user_id"=>$id))->row();
								//print_r($user);
								if($user->status=='1')
								{
									$status='0';
								}
								else
								$status='1';
								$this->session->set_flashdata("success","User status has been updated successfully");
								update_data("manage_users",array("status"=>$status),array("user_id"=>$id));
								redirect('admin/manage_user/view');
								break;
							case 'update':
								if(!$id)
									die();
								$redirect=$id;
								if($this->admin_model->add_user($id))
								{
								$this->session->set_flashdata("success","User has been updated successfully");
									redirect('admin/manage_user/view');
								}
								else
								{
									$this->session->set_flashdata("error","User has not been updated successfully");
									redirect('admin/manage_user/edit/'.$redirect);
								}
								break;
							
							default:
								# code...
								break;
						}
		}
	}
	
	
	function manage_email($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		/* $data['admin_logged']=$this->session->userdata('logged_user'); */
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'add':
				if($this->input->post('add_temp')!='')
				{
					echo '<pre>';
					print_r( $this->input->post() );
					foreach($this->input->post() as $key=>$in_value)
					{
						$datas[$key]=$in_value;
					}
					unset($datas['add_temp']);
					$datas['type']	=	1;
					insert_data('Email_Templates',$datas);
					$this->session->set_flashdata("success","Email Template added successfully");
					redirect('admin/manage_email/view');
				}
				else
				{
					$this->load->view('admin/Manage_emailtemplate',$data);
				}
				break;
			case 'view':
				$data["users"] =get_Emails();
				$this->load->view('admin/Manage_emailtemplate',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_email_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_emailtemplate',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("Email_Templates",array("id"=>$id));
				$this->session->set_flashdata("success","Email Template deleted successfully");
				redirect('admin/manage_email/view');
				break;
			case 'status':
				if(!$id)
					die();
				$user=get_data("Email_Templates",array("id"=>$id))->row();
				//print_r($user);
				if($user->status=='active')
				{
					$status='deactive';
				}
				else
				$status='active';
				$this->session->set_flashdata("success","Email_Template has been updated successfully");
				update_data("Email_Templates",array("status"=>$status),array("id"=>$id));
				redirect('admin/manage_email/view');
				break;
			case 'update':
				if(!$id)
					die();
				$redirect=$id;
				if($this->admin_model->add_email($id))
				{
				$this->session->set_flashdata("success","Email_Template has been updated successfully");
					redirect('admin/manage_email/view');
				}
				else
				{
					$this->session->set_flashdata("error","Email_Template has not been updated successfully");
					redirect('admin/manage_email/edit/'.$redirect);
				}
				break;
			
			default:
				# code...
				break;
		}
		}
	}
	function view_Template($id)
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			$data['temp']=$this->admin_model->get_usertemp($id);
			$this->load->view('admin/view_template',$data);  
		}
	}
	function privacy($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
			
				if($this->input->post('add_service'))
				{
				
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				    redirect('admin/privacy/view');
				}  
				  $r=$this->admin_model->add_privacy('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_privacy('',$admin_image1);
						$this->session->set_flashdata("success","Service has been inserted successfully");
						
				}
				redirect('admin/privacy/view');		
						
					
			
				}
				else
				{
					//$this->load->view('admin/admin_header');
					$this->load->view('admin/Manage_privacy',$data);
					//$this->load->view('admin/admin_footer');
				}
				break;
			case 'view':
				$this->db->order_by('id','desc');
				$data["users"] =get_data('privacy')->result();
				$this->load->view('admin/Manage_privacy',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_privacy_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_privacy',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("privacy",array("id"=>$id));
				redirect('admin/privacy/view');
				break;
			
			case 'update':
				
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				}  
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				  $this->session->set_flashdata("success","Service has been Edited successfully");
				  $r=$this->admin_model->add_privacy($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_privacy($id,$admin_image1='');
						$this->session->set_flashdata("success","Service has been Edited successfully");
				}
				 redirect('admin/privacy/view');
				break;
				default:
				# code...
				break;
		}
		}
	}

	function membership($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
			
				if($this->input->post('add_service'))
				{
					$id=$this->admin_model->add_membership();
					$this->session->set_flashdata("success","Plan has been inserted successfully");
					redirect('admin/membership/view');		
		        }
				else
				{
					$data['num_channels']=$this->admin_model->num_channels_plan();
					$data['num_hotels'] = $this->admin_model->num_hotels();
					$this->load->view('admin/Manage_membership',$data);
				}
				break;
			case 'view':
				$data["plan"] =$this->admin_model->get_membership();
				$this->load->view('admin/Manage_membership',$data);
				break;
			case 'edit':
				if(!$id)
				die();
				$data['num_channels']=$this->admin_model->num_channels_plan();
				$data['num_hotels'] = $this->admin_model->num_hotels();
				$udata=$this->admin_model->get_membership_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_membership',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("subscribe_plan",array("plan_id"=>$id));
				$this->session->set_flashdata("success","Plan has been deleted successfully");
				redirect('admin/membership/view');
				break;
			case 'update':
				        $id=$this->admin_model->add_membership($id);
						$this->session->set_flashdata("success","membership has been Edited successfully");
		        redirect('admin/membership/view');
				break;
				case 'status':
				        $id=$this->admin_model->change_status_member($id);
						$this->session->set_flashdata("success","status has been changed successfully");
				 redirect('admin/membership/view');
				break;
			default:
				# code...
				break;
		}
		}
	}
   
	function home($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
			$data["action"] =$mode;
		
			switch ($mode) 
			{
				case 'add':
					if($this->input->post('add_service'))
					{	
						$admin_image ="";
						$admin_image = $_FILES['image']['name'];				
					if ($admin_image!="")
					{
						$rnumber = mt_rand(0,999999);
						$ext = substr(strrchr($admin_image, "."), 1);
						$filename=$rnumber;
						$profile_image=$filename.".".$ext;
						$config['upload_path'] ='uploads';
						$config['allowed_types'] = '*';
						$config['file_name']=$filename;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);	
						if($admin_image!="" & (!$this->upload->do_upload('image')))
						{
							$error="Profile image ".$this->upload->display_errors();
							$this->session->set_flashdata('error',$error);
						}
						else
						{
							image_resizer("uploads/",$profile_image, 1600, 500);
						}
						$r=$this->admin_model->add_home_cms('',$profile_image);
					}
					else
					{
						$admin_image1="default.jpeg";
						$id=$this->admin_model->add_aboutus('',$admin_image1);
						$this->session->set_flashdata("success","Home cms has been inserted successfully");
					}
						redirect('admin/home/view');
					}
					else
					{
						$this->load->view('admin/manage_home_cms',$data);
					}
					break;
				case 'view':
					$data["users"] =$this->admin_model->get_home_cms();
					$data["users1"] =$this->admin_model->get_home_cms1();
					$this->load->view('admin/manage_home_cms',$data);
					break;
				case 'edit':
					if(!$id)
					die();
					$udata=$this->admin_model->get_home_cms_details($id,"array");
					$data=array_merge($udata,$data);
					$this->load->view('admin/manage_home_cms',$data);
					break;
				case 'delete':
					if(!$id)
					die();
					delete_data("home_cms",array("id"=>$id));
					$this->session->set_flashdata("success","Home cms has been deleted successfully");
					redirect('admin/home/view');
					break;
				case 'update':
					$admin_image="";
					$admin_image = $_FILES['image']['name'];				
					if ($admin_image!="")
					{
						$rnumber = mt_rand(0,999999);
						$ext = substr(strrchr($admin_image, "."), 1);
						$filename=$rnumber;
						$profile_image=$filename.".".$ext;
						$config['upload_path'] ='uploads';
						$config['allowed_types'] = '*';
						$config['file_name']=$filename;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);	
						if($admin_image!="" & (!$this->upload->do_upload('image')))
						{
							$error="Profile image ".$this->upload->display_errors();
							$this->session->set_flashdata('error',$error);
						   
						} 
						else
						{
							if($this->input->post('type')==1)
							{
								image_resizer("uploads/",$profile_image, 1600, 500);
							}
							else
							{
								image_resizer("uploads/",$profile_image, 222, 143);
							}
						}
						$this->session->set_flashdata("success","Home cms has been Updated successfully");
						$r=$this->admin_model->add_home_cms($id,$profile_image);
					}
					else
					{
						$admin_image1=$this->input->post('hidimage');
						$id=$this->admin_model->add_home_cms($id,$admin_image1);
						$this->session->set_flashdata("success","Home cms has been Updated successfully");
					}
					redirect('admin/home/view');
					break;
				default:
				break;
			}
		}
	}
	
	function otherCms($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
			$data["action"] =$mode;
		
			switch ($mode) 
			{
				case 'add':
					if($this->input->post('add_service'))
					{	
						$admin_image ="";
						$admin_image = $_FILES['image']['name'];				
						if ($admin_image!="")
						{
							$rnumber = mt_rand(0,999999);
							$ext = substr(strrchr($admin_image, "."), 1);
							$filename=$rnumber;
							$profile_image=$filename.".".$ext;
							$config['upload_path'] ='uploads';
							$config['allowed_types'] = '*';
							$config['file_name']=$filename;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);	
							if($admin_image!="" & (!$this->upload->do_upload('image')))
							{
								$error="Profile image ".$this->upload->display_errors();
								$this->session->set_flashdata('error',$error);
							}
							else
							{
								image_resizer("uploads/",$profile_image, 1600, 500);
							}
							$r=$this->admin_model->add_other_cms('',$profile_image);
						}
						else
						{
							$admin_image1="default.jpeg";
							$id=$this->admin_model->add_other_cms('',$admin_image1);
							$this->session->set_flashdata("success","CMS has been inserted successfully");
						}
						redirect('admin/otherCms/view');
					}
					else
					{
						$this->load->view('admin/manage_other_cms',$data);
					}
					break;
				case 'view':
					$data["users1"] =$this->admin_model->get_other_cms();
					$this->load->view('admin/manage_other_cms',$data);
					break;
				case 'edit':
					if(!$id)
					die();
					$udata=$this->admin_model->get_other_cms_details($id,"array");
					$data=array_merge($udata,$data);
					$this->load->view('admin/manage_other_cms',$data);
					break;
				case 'delete':
					if(!$id)
					die();
					delete_data("other_cms",array("id"=>$id));
					$this->session->set_flashdata("success","CMS has been deleted successfully");
					redirect('admin/otherCms/view');
					break;
				case 'update':
					$admin_image="";
					$admin_image = $_FILES['image']['name'];				
					if ($admin_image!="")
					{
						$rnumber = mt_rand(0,999999);
						$ext = substr(strrchr($admin_image, "."), 1);
						$filename=$rnumber;
						$profile_image=$filename.".".$ext;
						$config['upload_path'] ='uploads';
						$config['allowed_types'] = '*';
						$config['file_name']=$filename;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);	
						if($admin_image!="" & (!$this->upload->do_upload('image')))
						{
							$error="Profile image ".$this->upload->display_errors();
							$this->session->set_flashdata('error',$error);
						   
						} 
						else
						{
							if($this->input->post('type')==1)
							{
								image_resizer("uploads/",$profile_image, 1600, 500);
							}
							else
							{
								image_resizer("uploads/",$profile_image, 222, 143);
							}
						}
						$this->session->set_flashdata("success","CMS has been Updated successfully");
						$r=$this->admin_model->add_other_cms($id,$profile_image);
					}
					else
					{
						$admin_image1=$this->input->post('hidimage');
						$id=$this->admin_model->add_other_cms($id,$admin_image1);
						$this->session->set_flashdata("success","CMS has been Updated successfully");
					}
					redirect('admin/otherCms/view');
					break;
				default:
				break;
			}
		}
	}
	
	function about($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
      			if($this->input->post('add_service'))
				{	
			$admin_image ="";
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				  $r=$this->admin_model->add_aboutus('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_aboutus('',$admin_image1);
						$this->session->set_flashdata("success","About us has been inserted successfully");
				}
				 redirect('admin/about/view');
				 }else{
				 $this->load->view('admin/Manage_about',$data);
				 }
				break;
			case 'view':
				$data["users"] =$this->admin_model->get_About();
				$this->load->view('admin/Manage_about',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_About_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_about',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("About",array("id"=>$id));
			$this->session->set_flashdata("success","About us has been deleted successfully");
			redirect('admin/about/view');
				break;
			
			case 'update':
				$admin_image="";
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				} 
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				$this->session->set_flashdata("success","About Us has been Updated successfully");
				  $r=$this->admin_model->add_aboutus($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_aboutus($id,$admin_image1);
						$this->session->set_flashdata("success","About Us has been Updated successfully");
				}
				 redirect('admin/about/view');
				break;
            default:
     	break;
    	}
	 }
	}
	
	function tc($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'add':
				if($this->input->post('add_service'))
				{		
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				  $r=$this->admin_model->add_terms('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_terms('',$admin_image1);
						$this->session->set_flashdata("success","Terms and condition has been inserted successfully");
				}
				 redirect('admin/tc/view');
				 }else{
				 $this->load->view('admin/Manage_tc',$data);
				 }
				break;
			 case 'view':
				
				$this->db->order_by('id','desc');	
				$data["users"] =get_data('terms')->result();
				$this->load->view('admin/Manage_tc',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_Tc_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_tc',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("terms",array("id"=>$id));
				redirect('admin/tc/view');
				break;
			
			case 'update':
				echo $redirect=$id;
		
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				  $r=$this->admin_model->add_terms($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_terms($id,$admin_image1);
						$this->session->set_flashdata("success","Terms and condition has been inserted successfully");
				}
				 redirect('admin/tc/view');
				
				break;
			default:
				# code...
				break;
		}
		}
	}
//faq
	function faq($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'add':
				if($this->input->post('add_service'))
				{		
                 
				        $id=$this->admin_model->add_faq();
						$this->session->set_flashdata("success","FAQ has been inserted successfully");
				
				 redirect('admin/faq/view');
				 }else{
				 $this->load->view('admin/Manage_faq',$data);
				 }
				break;
			 case 'view':
				$data["users"] =get_data('faq')->result();
				$this->load->view('admin/Manage_faq',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_faq_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/Manage_faq',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("faq",array("id"=>$id));
				$this->session->set_flashdata("success","FAQ has been deleted successfully");
				redirect('admin/faq/view');
				break;
			
			case 'update':
				echo $redirect=$id;
		
                	    $id=$this->admin_model->add_faq($id);
						$this->session->set_flashdata("success","FAQ has been inserted successfully");
				 redirect('admin/faq/view');
			 break;
			default:
				# code...
				break;
		}
		}
	}
	function support()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			$data['records']= $this->admin_model->getsupport();
			$this->load->view('admin/Manage_support',$data);
		}
	}
	
	function contact()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			$data['records']= $this->admin_model->getcontact();
			$this->load->view('admin/Manage_Contact',$data);
		}
	}
	
	function log_out()
	{				
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{		
			//exit;
			redirect('admin/index','refresh');
		}
		else
		{
			//$array_items = array('logged_user' => $this->session->userdata('logged_user'));
			$this->session->unset_userdata('logged_user');		
			$this->session->unset_userdata('user');
			$this->session->unset_userdata('logged_user_type');
			$this->session->unset_userdata('admin_type');
			$this->session->unset_userdata('ad_hotel_id');	
			$this->session->unset_userdata('ad_user_id');	
			//$this->session->sess_destroy();		
			redirect('admin/index','refresh');
		}
	}
	
	
		
function manage_support()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');							
			}
			else
			{					
					$perpage = $this->admin_model->getrowsperpage();
					$urisegment=$this->uri->segment(4);
					$records=$this->admin_model->getsupports($perpage,$urisegment);
					if(!$records)
					{
						$data['notfound']="No Records";
						$data['view']="View";
						$this->load->view('admin/Manage_support',$data);	
					}	
					else
					{
						$data['view']="View";
						$total_rows = $this->admin_model->getsupport_count();
						$base="manage_support";
						$this->pageconfig($total_rows,$base);					
						$data['records']=$records;
						$this->load->view('admin/Manage_support',$data);	
					}
			}
	}
	
	function message_detail($id)
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');			
			}
			else
			{		
					$status_changed=$this->admin_model->changestatus_support($id);					
					$result=$this->admin_model->get_support($id);
					if(!$result)
					{
						$data['notfound']="No Message";
						$data['view']="View";
						$this->load->view('admin/message_detail',$data);	
					}	
					else
					{					
						$data['view']="View";
						$total_rows = $this->admin_model->getsupport_count();
						$base="message_detail";
						$this->pageconfig($total_rows,$base);					
						$data['result']=$result;
						$this->load->view('admin/message_detail',$data);	
					}					
			}
	}
	
	function contact_detail($id)
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');			
			}
			else
			{		
					//$status_changed=$this->admin_model->changestatus_support($id);					
					$result=$this->admin_model->get_contact($id);
					if(!$result)
					{
						$data['notfound']="No Message";
						$data['view']="View";
						$this->load->view('admin/contact_detail',$data);	
					}	
					else
					{					
						$data['view']="View";
						$total_rows = $this->admin_model->getcontact_count();
						$base="contact_detail";
						//$this->pageconfig($total_rows,$base);					
						$data['result']=$result;
						$this->load->view('admin/contact_detail',$data);	
					}					
			}
	}
	
	function sendreplymail()
	{
		$admin_id=$this->session->userdata('logged_user'); 
		if($admin_id=="")
		{
		redirect('/admin/index','referesh');
		}
		elseif($this->input->post('subject')!='')
		{
			$update['replay_status']='1';
			$current_date_time = get_data('contact_info',array('contact_id'=>$this->input->post('replay_id')))->row()->current_date_time;
			$update['current_date_time']	=	$current_date_time;
			update_data('contact_info',$update,array('contact_id'=>$this->input->post('replay_id')));
			
			$z=$this->input->post('user_email');
	
			$this->mailsettings();
			
			$admin_mail = get_data(CONFIG,array('id'=>'1'))->row();
	
			$this->email->from($admin_mail->email_id);
	
			$this->email->to($z);
	
			$this->email->subject($this->input->post('subject'));
	
			$this->email->message($this->input->post('message'));
	
			$this->email->send(); 
	
			$this->session->set_flashdata('success', "Mail send to corresponding user....");
	
			redirect('admin/contact','refresh');  
		} 
		else
		{
		$this->session->set_flashdata('error', "Due to some internal prblm mail can't be send..");
		redirect('admin/contact','refresh'); 
		}
	}
	
	function delete_support($id)
	{
		$session=$this->session->userdata('logged_user');
		if($session=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			if($this->admin_model->delete_support($id))	
			 {	
				$this->session->set_flashdata('success', "Comment has been deleted ");
				redirect('admin/support','refresh');	
		
			}
		}
	}
	
	function delete_contact($id)
	{
		$session=$this->session->userdata('logged_user');
		if($session=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
			if($this->admin_model->delete_contact($id))	
			 {	
				$this->session->set_flashdata('success', "Contacts has been deleted ");
				redirect('admin/contact','refresh');	
		
			}
		}
	}
	
 function reply_message_for_support()
	{
			$sessionvar=$this->session->userdata('logged_user');
			$data['admin_logged']=$this->session->userdata('logged_user');
			if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
			else
			{			
				$this->form_validation->set_rules('reply_message','Reply Message', 'required');		
				$this->form_validation->set_message('required',"%s required");		
				if ($this->form_validation->run() == FALSE)
				{
					$data['view']="View";
					$total_rows = $this->admin_model->getsupport_count();
					$base="message_detail";
					$this->pageconfig($total_rows,$base);					
					$data['result']=$result;
					$result=$this->admin_model->reply_support();
					redirect('admin/manage_support','refresh');	
					//$this->load->view('admin/message_detail',$data);	
				}
				else
				{
					$result=$this->admin_model->reply_support();							
					if(!$result)
					{								
						$this->session->set_flashdata('error', "Due to some problem you message cannot be sent right now..");		
						redirect('admin/manage_support','refresh');	
					}
					else
					{							
						$this->session->set_flashdata('success', "Your reply has been sent successfully.");	
						redirect('admin/manage_support','refresh');	
					} 
					
					if($result)
					{
						redirect('admin/manage_support','refresh');						
					}
				}
				}
			
	}
	function table()
	{
	$this->load->view('admin/table');
	}
	
	
function pwd_chk()
	{		
		$password=$this->input->post('password');		
		  
			$query = $this->admin_model->chk_for_pwd($this->session->userdata('loginname'));
			
			
			foreach($query as $r)
			{
				$user=$r->login_name;
				if($password==$r->password)
				{			
					echo "Success";
				}	
					else
				{		
				echo "Error";
				}
			}
				
		
	}
	function manage_channel()
	{
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');
		}
		else
		{
		$this->load->view('admin/manage_channel');
	  }
	}
	
	function manage_property($mode='',$owner_id="",$hotel_id='')
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'view':
				$data["property"] =$this->admin_model->get_property();
				$this->load->view('admin/manage_property',$data);
			 break;
			case 'delete':
				if(!$owner_id)
				{
					redirect('admin/manage_property/view');
				}
				else
				{	
					delete_data("manage_hotel",array("property_id"=>$id));
					$this->session->set_flashdata("success","Property has been deleted successfully");
					redirect('admin/manage_property/view');
				}
				break;
			case 'status':
				if(!$owner_id)
				{
					redirect('admin/manage_property/view');
				}
				else
				{
				$user=get_data("manage_hotel",array("owner_id"=>insep_decode($owner_id),"hotel_id"=>insep_decode($hotel_id)))->row();
				//print_r($user);
				if($user->status=='0')
				{
					$status='1';
				}
				else
				$status='0';
				echo $status;
				update_data("manage_hotel",array("status"=>$status),array("hotel_id"=>insep_decode($hotel_id)));
				$this->session->set_flashdata("success","Property status has been updated successfully");
				redirect('admin/manage_property/view');
				}
				break;
			default:
				$data["action"] ='view';
				$data["property"] =$this->admin_model->get_property();
				$this->load->view('admin/manage_property',$data);
				break;
		}
		}
	}
	
	/*function manage_property_room($mode='',$owner_id="",$hotel_id="",$property_id='')
	{
		//$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'view':
			if(!$owner_id)
			{
				echo 'ssd'; exit;
				redirect('admin/manage_property/view');	
			}
			else
			{
				$data["property"] =$this->admin_model->get_property_room($owner_id,$hotel_id);
				$data['property_name']  = get_data(HOTEL,array('owner_id'=>insep_decode($owner_id),'hotel_id'=>insep_decode($hotel_id)))->row()->property_name;
				$this->load->view('admin/manage_room',$data);
			}
			 break;
			case 'delete':
				if(!$owner_id)
				{
					die();
				}
				else
				{
					$owner_id=insep_encode($this->admin_model->decryptIt($owner_id));
					$hotel_id=insep_encode($this->admin_model->decryptIt($hotel_id));
					$pass_id=$this->admin_model->decryptIt($property_id);
					delete_data("manage_property",array("property_id"=>($pass_id)));
					$this->session->set_flashdata("success","Room has been deleted successfully");
					redirect('admin/manage_property_room/view/'.$owner_id.'/'.$hotel_id);
				}
				break;
			case 'status':
				if(!$owner_id)
				{
					
				}
				else
				{
					$owner_id=insep_encode($this->admin_model->decryptIt($owner_id));
					$hotel_id=insep_encode($this->admin_model->decryptIt($hotel_id));
					$pass_id=$this->admin_model->decryptIt($property_id);
					$user=get_data("manage_property",array("property_id"=>($pass_id)))->row();
				//print_r($user);
				if($user->status=='Active')
				{
					$status='Inactive';
				}
				else
				{
					$status='Active';
				}
				
				$this->session->set_flashdata("success","Room status has been updated successfully");
				update_data("manage_property",array("status"=>$status),array("property_id"=>($pass_id)));
				redirect('admin/manage_property_room/view/'.$owner_id.'/'.$hotel_id);
				}
				break;
			default:
				redirect('admin/manage_property/view');	
				break;
		}
		}
	}*/
	
	function manage_reservation($mode,$id="")
	{
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'view':
				$data["reservation"] =$this->admin_model->get_reservation();
				/* echo '<pre>';
				print_r($data);
				die; */
				$this->load->view('admin/manage_reservation',$data);
			 break;
			case 'delete':
				if(!$id)
				die();
				$this->session->set_flashdata("success","Reservation details has been deletd successfully!!!");
				delete_data("manage_reservation",array("reservation_id"=>$id));
				redirect('admin/manage_reservation/view');
				break;
			case 'status':
				if(!$id)
					die();
				$user=get_data("manage_reservation",array("reservation_id"=>$id))->row();
				//print_r($user);
				if($user->status=='Active')
				{
					$status='Inactive';
				}
				else
				$status='Active';
				$this->session->set_flashdata("success","Reservation status has been updated successfully");
				update_data("manage_reservation",array("status"=>$status),array("reservation_id"=>$id));
				redirect('admin/manage_reservation/view');
				break;
			default:
				$data["reservation"] =$this->admin_model->get_reservation();
				$this->load->view('admin/manage_reservation',$data);
				break;
		}
		}
	}
	function view_property($owner_id,$id){
		$owner_id=$this->admin_model->decryptIt($owner_id);
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$data['property']='';
		$property = $this->admin_model->get_property($owner_id,$id);
		$data = array_merge($data,$property);
		$this->load->view('admin/view_property',$data);
	  }
	}
	
	function view_property_room($owner_id,$hotel_id,$property_id){
		$owner_id=insep_encode($this->admin_model->decryptIt($owner_id));
		$hotel_id=insep_encode($this->admin_model->decryptIt($hotel_id));
		$pass_id=insep_encode($this->admin_model->decryptIt($property_id));
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$data['property']='';
		$property = $this->admin_model->get_property_room($owner_id,$hotel_id,$pass_id);
		$data = array_merge($data,$property);
		$this->load->view('admin/view_property_room',$data);
	  }
	}
	
	function view_hotelier($id){
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$data['user']=$this->admin_model->get_users($id);
		$this->load->view('admin/view_hotelier',$data);
	  }
	}
	function view_reservation($id){
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$data['reservation']=$this->admin_model->get_reservation($id);
		$this->load->view('admin/view_reservation',$data);
	  }
	}
	function change_reservation(){
        $id=$_REQUEST['id'];
        $status=$_REQUEST['val'];
		update_data("manage_reservation",array("status"=>$status),array("reservation_id"=>$id));
	    echo "ok";
	}
	function channel_change_status($id)
	{
		//$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
		redirect('admin/index','refresh');//echo "aa";
		}
		else
		{
		$res=$this->admin_model->channel_change_status($id);
		if($res)
		{
			$this->session->set_flashdata("success","Channel status has been updated successfully");
			redirect('admin/manage_channel','refresh');
		}
	}
	}
	function update_image_old(){
	   $sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
			else
			{
				$channel_image="";
			   $channel_image=$_FILES['c_image']['name'];  
				if($channel_image!="")
				{
					unlink("uploads/".$this->input->post('hidimage'));
					unlink("uploads/small/".$this->input->post('hidimage'));
	                $rnumber = mt_rand(0,999999);
					$ext = substr(strrchr($channel_image, "."), 1);
					$filename=$rnumber;
					$profile_image=$filename.".".$ext;
					$config['upload_path'] ='uploads';
				    $config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['file_name']=$filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);	
					if($channel_image!="" & (!$this->upload->do_upload('c_image')))
					{
						$error="Profile image ".$this->upload->display_errors();
						//$this->session->set_flashdata('error',$error);
						//redirect('admin/admin_registered','refresh');
					}
					else
					{
						image_resizer1("uploads/",$profile_image, 250, 156);
					}
				$r=$this->admin_model->update_image($profile_image);
				redirect('admin/manage_channel','refresh');
			 }else{
				$hid_image=$this->input->post('hidimage');
				$r=$this->admin_model->update_image($hid_image);
				redirect('admin/manage_channel','refresh');
			}
		}	
	}
	
	function update_image(){
	   $sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
			else
			{
				$channel_image="";
			   $channel_image=$_FILES['c_image']['name'];  
				if($channel_image!="")
				{
					unlink("uploads/".$this->input->post('hidimage'));
					unlink("uploads/small/".$this->input->post('hidimage'));
					unlink("uploads/channel/".$this->input->post('hidimage'));
	                $rnumber = mt_rand(0,999999);
					$ext = substr(strrchr($channel_image, "."), 1);
					$filename=$rnumber;
					$profile_image=$filename.".".$ext;
					$config['upload_path'] ='uploads';
				    $config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['file_name']=$filename;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);	
					if($channel_image!="" & (!$this->upload->do_upload('c_image')))
					{
						$error="Profile image ".$this->upload->display_errors();
						//$this->session->set_flashdata('error',$error);
						//redirect('admin/admin_registered','refresh');
					}
					else
					{
						image_resizer1("uploads/",$profile_image, 250, 156);
					}
				$r=$this->admin_model->update_image($profile_image);
				redirect('admin/manage_channel','refresh');
			 }else{
				$hid_image=$this->input->post('hidimage');
				$r=$this->admin_model->update_image($hid_image);
				redirect('admin/manage_channel','refresh');
			}
		}	
	}
	
function chk_current_pwd()
{
	$res=$this->admin_model->chk_current_pwd();
	?>
	
   <?php
	if($res)
	{
		echo "1";
	}else{
		echo "0";
	}
	?>
<?php
}
function chk_exist_email($id='')
{
	$res=$this->admin_model->chk_exist_email($id);
	?>
	
<?php
if($res!="")
{
	echo '0';
}
else
{
	echo '1';
}
	?>
<?php
}

function chk_exist_LoginName($id='')
{
	$res=$this->admin_model->chk_exist_LoginName($id);
	if($res!="")
	{
		echo '0';
	}
	else
	{
		echo '1';
	}
}

function emailcheck()
{
  $mail_val=$_REQUEST['recovery_email'];
  $get_mail=$this->admin_model->get_users_mail($mail_val);
  echo $get_mail; exit;
}
/*Start Subbaiah \*/
function manage_payments($mode='',$id='')
{
	$sessionvar=$this->session->userdata('logged_user');
	if($sessionvar!='')
	{
		$data['action'] = $mode;
		switch($mode)
		{
			case "update":
			extract($this->input->post());
			$udata['paypal_emailid'] = $paypal_emailid;
			$udata['paypalmode']     = $paypalmode;
			if(update_data(ADMIN,$udata,array('id'=>1)))
			{
				redirect('admin/manage_payments','refresh');
			}
			break;
			default:
			// echo 'admin';die;
			$admin= get_data(ADMIN,array('id'=>1))->row_array();
			/* echo '<pre>';
			print_r($admin);die; */
			$data = array_merge($data,$admin);
			$this->load->view('admin/manage_payments',$data);
			break;
		}
	}
	else
	{
		redirect('admin','refresh');
	}
}
function send_newsletter()
{
	$sessionvar=$this->session->userdata('logged_user');
	if($sessionvar!='')
	{		
		if($this->input->post('submit'))
		{			
			$result	=	$this->admin_model->send_newsletter();
			if($result)
			{
				$this->session->set_flashdata('success_news',"Newsletter has been successfully send to Corresponding Users");
				redirect('admin/send_newsletter','refresh');
			}
			else
			{
				$this->session->set_flashdata('error',"Newsletter has send been successfully send to Corresponding Users");
				redirect('admin/send_newsletter','refresh');
			}
		}
		else
		{
				//$this->load->view('admin/admin_header');
				//$this->load->view('admin/admin_sidebar');
				$this->load->view('admin/newsletter_new');
				//$this->load->view('admin/admin_footer');
		}
	}
	else
	{
		redirect('admin','refresh');	
	}
				
}
function user_change_password()
{
	$this->load->library(array('passwordhash'));
	
	//print_r($this->input->post());
	
	$sessionvar=$this->session->userdata('logged_user');
	if($sessionvar!='')
	{
		extract($this->input->post());
		
		$t_hasher = new PasswordHash(8, FALSE);
		
		$hash = $t_hasher->HashPassword($this->input->post('newpass'));
		/*echo $hash;
		exit;*/
		$udata['password'] = $hash;
		if(update_data(TBL_USERS,$udata,array('user_id'=>insep_decode($user_id))))
		{
			$this->session->set_flashdata('success','User password has been successfully updated!!!');
			$pass_id=$this->admin_model->encryptIt(insep_decode($user_id));
			redirect('admin/view_hotelier/'.$pass_id,'refresh');
		}
		
	}
	else
	{
		redirect('admin','refresh');	
	}
	
}
function user_change_plan()
{
	extract($this->input->post());
	$sessionvar=$this->session->userdata('logged_user');
	if($sessionvar!='')
	{
		$plan_details = get_data(TBL_PLAN,array('plan_id'=>$change_plan))->row();
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
		$user['buy_plan_type'] = $plan_duration;
		$user['buy_plan_currency'] = $plan_details->currency;
		$user['buy_plan_id'] =$data['plan_id'] = $change_plan;
		$user['plan_from'] = $data['plan_from'] = date('Y-m-d');
		$user['plan_to'] = $data['plan_to'] = date("Y-m-d", strtotime("+$plan $plan_du"));
		$data['subscribe_status'] = '1';
		$user['buy_plan_price'] = $data['plan_price'] = $plan_details->plan_price;
		$user['total_channels'] = $plan_details->number_of_hotels;
		$user['plan_status'] = 1;
		$user['user_id'] = unsecure($user_id);
		$user['buy_plan_account'] = "2";
		$isexist = get_data('user_membership_plan_details',array("user_id" => unsecure($user_id),"buy_plan_account" => 2));
		if($isexist->num_rows != 0){
			$prev_hotels = $isexist->row()->total_channels;
			update_data("user_membership_plan_details",$user,array("user_id" => unsecure($user_id),"buy_plan_account" => 2));
		}else{
			$this->db->insert("user_membership_plan_details",$user);
		}
		if(update_data(TBL_USERS,$data,array('user_id'=>unsecure($user_id))))
		{
			redirect('admin/view_hotelier/'.$user_id,'refresh');
		}
	}
	else
	{
		redirect('admin','refresh');	
	}
	
				
}
  
/*End Subbaiah */	
// sharmila.....
// channel plan..
   
    function channel_plan($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			/* case 'add':
			
				if($this->input->post('add_service'))
				{
				$id=$this->admin_model->add_channel();
				$this->session->set_flashdata("success","Channel Plan has been inserted successfully");
				redirect('admin/channel_plan/view');		
		        }
				else
				{
					
					$this->load->view('admin/channel_plan',$data);
					
				}
				break; */
			case 'view':
				$data["plan"] =$this->admin_model->get_channel();
				$this->load->view('admin/channel_plan',$data);
				break;
			case 'edit':
				if(!$id)
				die();
				$udata=$this->admin_model->get_channel_details($id,"array");	
				$data=array_merge($udata,$data);
				$this->load->view('admin/channel_plan',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("channel_plan",array("channel_id"=>$id));
				redirect('admin/channel_plan/view');
				break;
			
			case 'update':
				        $id=$this->admin_model->add_channel($id);
						$this->session->set_flashdata("success","channel has been Edited successfully");
				        redirect('admin/channel_plan/view');
				break;
				case 'status':
				        $id=$this->admin_model->change_status_channel($id);
						$this->session->set_flashdata("success","status has been changed successfully");
				
				 redirect('admin/channel_plan/view');
				break;
			default:
				# code...
				break;
		}
		}
	}
   function channel_update($id=''){
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}else{
				$data['tmp'] = $this->admin_model->select_channel($id);
				 $this->load->view('admin/edit_manage',$data);
			}
			
	}
	
function edit_channeldet(){
	
	///print_r($this->input->post()); exit;
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				
				redirect('admin/index','refresh');
			}else{
				
				// echo $this->input->post('save'); die;
				 if($this->input->post('channel_id')){
					$result = $this->admin_model->edit_update();
					if($result){
						$this->session->set_flashdata("success","Channel has been updated successfully");
						redirect('admin/manage_channel','refresh');
					}
				} 
			}
	}
//17/12/2015..
function features($mode,$id="")
	{
		// echo 'hi hello'; die;
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
      			if($this->input->post('add_service'))
				{	
			$admin_image ="";
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				  $r=$this->admin_model->add_features('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_features('',$admin_image1);
						$this->session->set_flashdata("success","Features has been inserted successfully");
				}
				 redirect('admin/features/view');
				 }else{
				 $this->load->view('admin/Manage_about',$data);
				 }
				break;
			case 'view':
				$data["users"] =$this->admin_model->get_features();
				$this->load->view('admin/features',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_features_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/features',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("features",array("id"=>$id));
			$this->session->set_flashdata("success","Features has been deleted successfully");
			redirect('admin/features/view');
				break;
			
			case 'update':
				$admin_image="";
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				} 
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
					$this->session->set_flashdata("success","Features has been Updated successfully");
				  $r=$this->admin_model->add_features($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_features($id,$admin_image1);
						$this->session->set_flashdata("success","Features has been Updated successfully");
				}
				 redirect('admin/features/view');
				break;
            default:
     	break;
    	}
	 }
	}
	
function add_channels(){
   $sessionvar=$this->session->userdata('logged_user');
   $data['admin_logged']=$this->session->userdata('logged_user');
   if($sessionvar=="")
       {
           redirect('admin/index','refresh');
       }else{
            $this->load->view('admin/add_channel',$data);
       }
}
function add_channelsdet(){
   // echo 'addvfkfkj';die;
   $sessionvar=$this->session->userdata('logged_user');
   $data['admin_logged']=$this->session->userdata('logged_user');
   if($sessionvar==""){
       redirect('admin/index','referesh');
   }
   else{
       // echo $this->input->post('save');die;  
	  $channel_image=$_FILES['c_image']['name'];  
	  if($channel_image!="")
	  {
		  $rnumber = mt_rand(0,999999);
		  $ext = substr(strrchr($channel_image, "."), 1);
		  $filename=$rnumber;
		  $profile_image=$filename.".".$ext;
		  $config['upload_path'] ='uploads';
		  $config['allowed_types'] = 'gif|jpg|jpeg|png';
		  $config['file_name']=$filename;
		  $this->load->library('upload', $config);
		  $this->upload->initialize($config);	
		  if($channel_image!="" & (!$this->upload->do_upload('c_image')))
		  {
			  $error="Profile image ".$this->upload->display_errors();
			  //$this->session->set_flashdata('error',$error);
			  //redirect('admin/admin_registered','refresh');
		  }
		  else
		  {
			  image_resizer1("uploads/",$profile_image, 250, 156);
		  }
	  }
	      $result = $this->admin_model->add_channels($profile_image);
       if($result){
           $this->session->set_flashdata('success','Channles Added Successfully');
           redirect('admin/manage_channel','referesh');
       }
   }
}	
function add_channelname($channel_id, $channel_name)
{
		if($channel_id)
		{
			$this->db->where_not_in('channel_id',$channel_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('channel_name', $channel_name);
			$query = $this->db->get(TBL_CHANNEL);
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
			$this->db->where('channel_name', $channel_name);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(TBL_CHANNEL);
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
	
function add_channelname_exists($channel_id='')
{
	if (array_key_exists('channel_name',$_POST)) 
	{
		if ( $this->add_channelname($channel_id,$this->input->post('channel_name')) == TRUE ) 
		{
			echo json_encode(FALSE);
		} 
		else 
		{
			echo json_encode(TRUE);
		}
	}
}
function add_channel_username($channel_id, $channel_username)
{
		if($channel_id)
		{
			$this->db->where_not_in('channel_id',$channel_id);
			//$this->db->where('delete_trash','0');
			$this->db->where('channel_username', $channel_username);
			$query = $this->db->get(TBL_CHANNEL);
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
			$this->db->where('channel_username', $channel_username);
			//$this->db->where('delete_trash','0');
			$query = $this->db->get(TBL_CHANNEL);
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
	
function add_channelusername_exists($channel_id='')
{
	if (array_key_exists('channel_username',$_POST)) 
	{
		if ( $this->add_channel_username($channel_id,$this->input->post('channel_username')) == TRUE ) 
		{
			echo json_encode(FALSE);
		} 
		else 
		{
			echo json_encode(TRUE);
		}
	}
}
//12/01/16....
function manage_users($section='',$user_id='')
{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
			//echo 'manage';die;
			$data["action"] =$section;
			switch ($section) 
			{
			case 'active':
								if(!$user_id)
								die();
					  	$user_id=$this->admin_model->decryptIt($user_id);
						$email_address = get_data(TBL_USERS,array('user_id'=>$user_id))->row()->email_address;
						
					 update_data("manage_users",array("multiproperty"=>'Active'),array("user_id"=>$user_id));

					//send mail
					 
							
							$admin_mail = get_data('manage_admin_details',array('id'=>'1'))->row();
					
							$email=$this->email->from($admin_mail->email_id);
							$site_config	=	$this->admin_model->get_siteconfig();
							if($site_config)
							{
								foreach($site_config as $site_config)
								{
									$company_name	=	$site_config->company_name;
									$emailid	=	$site_config->email_id;
									$site_logo  = $site_config->site_logo;
								}
							}
						$this->db->where('id','16');  			
									$queryemail_temp=$this->db->get('Email_Templates'); 
									if($queryemail_temp->num_rows()==1)  
									{ 
										$get_emailtemp=$queryemail_temp->row(); 
										$msg=$get_emailtemp->message; 
										$subject=$get_emailtemp->subject; 
										$mail_content = array(
																'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$site_logo,
																'###SITENAME###'=>$company_name,
																'###STATUS###'=>'Active',
																'###USERNAME###'=>$email_address
															);
									 
										$content=strtr($msg,$mail_content);  				  
										$msg=$content;
										$this->mailsettings();
										$this->email->from($admin_mail->email_id,$company_name);
										$this->email->to($email_address);
										$this->email->subject($subject);
										$this->email->message($msg);
										$this->email->send();
										
									}
				
				   
				
								$this->session->set_flashdata("success","$email_address multiproperty option enabled successfully");
								redirect('admin/manage_users/view');
								break;
							case 'inactive':
							$user_id=$this->admin_model->decryptIt($user_id);
								if(!$user_id)
								die();
							$email_address = get_data(TBL_USERS,array('user_id'=>$user_id))->row()->email_address;
						
							update_data("manage_users",array("multiproperty"=>'Deactive'),array("user_id"=>$user_id));
							$this->session->set_flashdata("success","$email_address multiproperty option disabled successfully");
								redirect('admin/manage_users/view');
								break;
				case 'add';
					if($this->input->post('save')){
						// echo 'new add';die;
						$result = $this->admin_model->add_users_det();
						if($result){
							$this->session->set_flashdata('success','Users details added Successfully');
							redirect('admin/manage_users','referesh');
						}
						else{
							$this->session->set_flashdata('error','Error Occurred While Adding user details');
							redirect('admin/manage_users','referesh');
						}
					}
					else{
					$data['action'] = 'add';
					$this->load->view('admin/users',$data);
					}
				break;
				case 'edit':
					if($this->input->post('save')){
						 // echo $this->input->post('save');die
						 $result = $this->admin_model->edit_users();
						 if($result){
							 $this->session->set_flashdata('success','Users Details Updated Successfully');
							 redirect('admin/manage_users','refresh');
						 }
						 else{
							 $this->session->set_flashdata('error','Error Occurred While Updating Users Details');
							 redirect('admin/manage_users','refresh');
						 }
					}
					else{
						
						$details = $this->admin_model->fetch_users(insep_decode($user_id));
						/*echo '<pre>';
						print_r($details);die;*/
						if(!$details){
							// echo 'fdfdf';
							redirect('admin/manage_users','refresh');
						}
						else{
							$data['user'] = $details;
							$data['action'] = 'edit';
							$this->load->view('admin/users',$data);
						}
					}
				break;
				case 'delete':
					$result = $this->admin_model->delete_users(insep_decode($user_id));
					if($result){
							$this->session->set_flashdata('success','Users details deleted Successfully');
							redirect('admin/manage_users','referesh');
						}
						else{
							$this->session->set_flashdata('error','Error Occurred While Deleting user details');
							redirect('admin/manage_users','referesh');
						}
				break;
				case 'bulk_delete':
					$orders	= $this->input->post('order');
					if($orders)
					{
						foreach($orders as $order)
						{
							$result = $this->admin_model->delete_users(insep_decode($order));
						}
						$this->session->set_flashdata('success','Users details deleted Successfully');
						redirect('admin/manage_users','referesh');
					}
					else
					{
						$this->session->set_flashdata('error','Error Occurred While Deleting user details');
						redirect('admin/manage_users','referesh');
					}
				break;
				case 'status':
				
				$id = insep_decode($user_id);
				$user=get_data("manage_users",array("user_id"=>$id))->row();
				//print_r($user);
				if($user->status=='1')
				{
					$status='0';
				}
				else
				$status='1';	
				$this->session->set_flashdata("success","User status has been updated successfully");
				update_data("manage_users",array("status"=>$status),array("user_id"=>$id));
				redirect('admin/manage_users','referesh');
				break;
				default:
					$data['users'] = $this->admin_model->get_user_det();
					$data['action'] = 'list';
					$this->load->view('admin/users',$data);
				break;
				
			}
		} 
	}

	function down_reserve(){
		//$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		//$data= array_merge($user_details,$data);
		$data['page_heading'] = 'manage_reservation';
		$user_details = get_data(TBL_USERS,array('User_Type'=>1))->row_array();
		$data= array_merge($user_details,$data);
		$t_date = date('Y-m-d');
		$filename = "Reservations-".$t_date.".xls";	
		$this->load->dbutil();
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\n";
		$from_date=$this->input->post('from_date');;
		$edate=$this->input->post('to_date');
		$status=$this->input->post('status');
		$room=$this->input->post('room');
		if($room!="" && $status!=""){
  $this->db->select('r.guest_name,r.start_date,r.end_date,r.room_id,r.status,r.reservation_code,p.property_name,h.property_name as Hotel');
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->where('r.hotel_id',$room);
$this->db->where('r.status',$status);
$this->db->from('manage_reservation as r');
$this->db->join('manage_property as p','r.room_id = p.property_id');
$this->db->join('manage_hotel as h', 'h.hotel_id = r.hotel_id');
$query = $this->db->get();
//$query =$this->db->get('manage_reservation');
		}else if($room=="" && $status!=""){
				 /*$this->db->select('guest_name,start_date,end_date,room_id,status,reservation_code');
$this->db->where("DATE_FORMAT(STR_TO_DATE(start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
	$this->db->where("DATE_FORMAT(STR_TO_DATE(end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->where('status',$status);
$query =$this->db->get('manage_reservation');*/
	 $this->db->select('r.guest_name,r.start_date,r.end_date,r.room_id,r.status,r.reservation_code,p.property_name,h.property_name as Hotel');
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->where('r.status',$status);
$this->db->from('manage_reservation as r');
$this->db->join('manage_property as p','r.room_id = p.property_id');
$this->db->join('manage_hotel as h', 'h.hotel_id = r.hotel_id');
$query = $this->db->get();
		}else if($status=="" && $room!=""){
				/* $this->db->select('guest_name,start_date,end_date,room_id,status,reservation_code');
$this->db->where("DATE_FORMAT(STR_TO_DATE(start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
 $this->db->where("DATE_FORMAT(STR_TO_DATE(end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->where('room_id',$room);
$query =$this->db->get('manage_reservation');*/
	 $this->db->select('r.guest_name,r.start_date,r.end_date,r.room_id,r.status,r.reservation_code,p.property_name,h.property_name as Hotel');
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->where('r.hotel_id',$room);
$this->db->from('manage_reservation as r');
$this->db->join('manage_property as p', 'r.room_id = p.property_id');
$this->db->join('manage_hotel as h', 'h.hotel_id = r.hotel_id');
$query = $this->db->get();
		}else{
	 $this->db->select('r.guest_name,r.start_date,r.end_date,r.room_id,r.status,r.reservation_code,p.property_name,h.property_name as Hotel');
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.start_date, '%d/%m/%Y'),'%d/%m/%Y') <",$from_date);
$this->db->where("DATE_FORMAT(STR_TO_DATE(r.end_date, '%d/%m/%Y'),'%d/%m/%Y') >",$edate);
$this->db->from('manage_reservation as r');
$this->db->join('manage_property as p','r.room_id = p.property_id');
$this->db->join('manage_hotel as h', 'h.hotel_id = r.hotel_id');
$query = $this->db->get();
		}
	$data = $this->dbutil->csv_from_result($query, $delimiter, $newline);		
		force_download($filename, $data);
		$this->views('admin/manage_reservation/view',$data);
	}
	
	function all_channels(){
      $sessionvar=$this->session->userdata('logged_user');
      $data['admin_logged']=$this->session->userdata('logged_user');
      if($sessionvar==""){
          redirect('admin/index','referesh');
      }else{
           $channel_name = $this->input->get('channel_name');
           $channel = get_data('manage_channel')->result_array();
           $perpage =15;
           $urisegment=$this->uri->segment(4);    
           $base="all_channels";
           $total_rows = count($channel);
           $this->pageconfig($total_rows,$base,$perpage);
              $data['channel'] = $this->admin_model->get_all_channels($perpage,$urisegment);
           $this->load->view('admin/all_channels',$data);
      }
    }
	
	function all_hotels(){
      $sessionvar=$this->session->userdata('logged_user');
      $data['admin_logged']=$this->session->userdata('logged_user');
      if($sessionvar==""){
          redirect('admin/index','referesh');
      }else{
      $hotels = get_data(HOTEL,array('status'=>'1'))->result_array();
       $perpage =    18;
       $urisegment=$this->uri->segment(4);    
         $base="all_hotels";
       $total_rows = count($hotels);
         $this->pageconfig($total_rows,$base,$perpage);
       $data['hotels'] = $this->admin_model->all_hotel($perpage,$urisegment);
       $this->load->view('admin/all_hotels',$data);
      }
    }
	
	function all_user_hotels()
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','referesh');
		}else
		{
			$data['hotels'] = $this->admin_model->all_user_hotel();
			$this->load->view('admin/all_hotels',$data);
		}
	}
	
	 function hotel_details($hotel_id=null){
      $sessionvar=$this->session->userdata('logged_user');
      $data['admin_logged']=$this->session->userdata('logged_user');
      if($sessionvar==""){
          redirect('admin/index','referesh');
      }else{
       $hotel_id = insep_decode($hotel_id);
       $data['channel'] = $this->admin_model->get_channels($hotel_id); 
       $channels = get_data(CONNECT,array('hotel_id'=>$hotel_id,'status'=>'enabled'),'channel_id')->result_array();
       $data['connected_channel_count'] = count($channels);
       $channel_ids = "";
       if(count($channels) != 0)
        {
	       foreach ($channels as $channel) {
	       		$channel_ids .= $channel['channel_id'].',';
	       }
	       $channel_ids = rtrim($channel_ids,',');
      	}
       $count = $this->db->select('channel_id')->from('manage_channel')->where_in('channel_id',explode(',',$channel_ids))->count_all_results();
       $perpage =5;
       $urisegment=($this->uri->segment(5));    
       $base="hotel_details";
       $total_rows = $count;
       $this->pageconfig_new($total_rows,$base,$perpage,insep_encode($hotel_id));
       $data['channel_name'] = $this->admin_model->channel_name($channel_ids,$perpage,$urisegment);
       $data['plan_details'] = $this->admin_model->get_plan_by_user_id($data['channel']->owner_id,$hotel_id);
       if(count($data['plan_details']) == 0)
       {
       		$data['plan_details'] = $this->admin_model->get_expired_plan_by_user_id($data['channel']->owner_id,$hotel_id);
       		$data['expired'] = 1;
       }
       $data['indplans'] = $this->admin_model->get_all_ind_plans();
       $this->load->view('admin/hotel_details',$data);
      }
    }
    // Starts Gayathri 

    function select_channels_details($plan_id='')
	{
		
		if($plan_id!='')
		{
			if(check_plan($plan_id,'check'))
			{
				$data['all_channels']	=	get_all_channels_available();
				$data['plan_details']	=	check_plan($plan_id,'get');	
                if($data['plan_details']['number_of_channels'] != 0){
				   $this->load->view('admin/select_channels',$data);
                }else{
                    echo 0;
                }
			}
			else
			{
				echo "No plans to be selected";
			}
		}
		else
		{
			echo "No plans to be selected";
		}
	}

    function save_indvidualplan($hotelid,$planid){
    	$sessionvar=$this->session->userdata('logged_user');
	      $data['admin_logged']=$this->session->userdata('logged_user');
	      if($sessionvar==""){
	          redirect('admin/index','referesh');
	      }else{
    	$planid = insep_decode($planid);
    	extract($this->input->post());
    	if($ind_plan != "Null"){ 
    		$getplandetails = $this->admin_model->get_plan_details($ind_plan);    		
    		if($this->admin_model->update_plan_by_user($getplandetails,$planid))
    		{
    			$this->session->set_flashdata("mem_success","Changes Saved Successfully!..");
    		}
    	}

    	if(@$extend_plan != 0){
    		if($this->admin_model->extend_plan($extend_plan, $planid)){
    			$this->session->set_flashdata("mem_success","Changes Saved Successfully!..");
    		}
    	}
    	redirect("admin/hotel_details/".$hotelid);
    }
    }

    function save_channel_details($channel_id,$hotel_id)
    {
    	$sessionvar=$this->session->userdata('logged_user');
	      $data['admin_logged']=$this->session->userdata('logged_user');
	      if($sessionvar==""){
	          redirect('admin/index','referesh');
	      }else{
    	$channel_id = insep_decode($channel_id);
    	$hotel_id = insep_decode($hotel_id);
    	if($this->admin_model->save_channel_details($channel_id,$hotel_id)){
    		$this->session->set_flashdata("cha_success","Changes Saved Successfully!..");
    	}
    	redirect("admin/hotel_details/".insep_encode($hotel_id));
    }
    }
    // End Gayathri
	
	 function all_users(){
      $sessionvar=$this->session->userdata('logged_user');
      $data['admin_logged']=$this->session->userdata('logged_user');
      if($sessionvar==""){
          redirect('admin/index','referesh');
      }else{
      $users = get_data(TBL_USERS,array('status'=>'1','acc_active'=>'1','User_Type'=>'1'))->result_array();
       $perpage =    18;
       $urisegment=$this->uri->segment(4);    
         $base="all_users";
       $total_rows = count($users);
         $this->pageconfig($total_rows,$base,$perpage);
       $data['users'] = $this->admin_model->all_users($perpage,$urisegment);
       $this->load->view('admin/all_users',$data);
      }
    }
	
	// add users..
	
	function add_users($hotel_id=null){
	   $sessionvar=$this->session->userdata('logged_user');
       $data['admin_logged']=$this->session->userdata('logged_user');
	   if($sessionvar==""){
		   redirect('admin/index','referesh');
	   }else{
		   $hotel_id = insep_decode($hotel_id);
		   if($this->input->post('add')!='')
		   {
			   $result = $this->admin_model->add_user_det($hotel_id);
			   if($result)
			   {
				   $this->session->set_flashdata('profile','User has been Added	 successfully');
				   redirect('admin/hotel_details/'.insep_encode($hotel_id),'refresh');
			   }
		   }
	   }
	}
	
	function edit_details($hotel_id){
		$sessionvar=$this->session->userdata('logged_user');
       $data['admin_logged']=$this->session->userdata('logged_user');
	   if($sessionvar==""){
		   redirect('admin/index','referesh');
	   }else{ 
		    $hotel_id = insep_decode($hotel_id);
		   if($this->input->post('add')!='')
		   {
			   $result = $this->admin_model->update_users($hotel_id);
			   if($result)
			   {
				   $this->session->set_flashdata('success','User Details Updated successfully');
				   redirect('admin/hotel_details/'.insep_encode($hotel_id),'refresh');
			   }
		   }
	   }
	}
function partner($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
			
				if($this->input->post('add_service'))
				{
				
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				    redirect('admin/privacy/view');
				}  
				  $r=$this->admin_model->add_partner('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_partner('',$admin_image1);
						$this->session->set_flashdata("success","Partner CMS has been inserted successfully");
						
				}
				redirect('admin/partner/view');		
						
					
			
				}
				else
				{
					//$this->load->view('admin/admin_header');
					$this->load->view('admin/manage_partner',$data);
					//$this->load->view('admin/admin_footer');
				}
				break;
			case 'view':
				$this->db->order_by('id','desc');
				$data["users"] =get_data('partner_cms')->result();
				$this->load->view('admin/manage_partner',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_partnercms_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/manage_partner',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("partner_cms",array("id"=>$id));
				redirect('admin/partner/view');
				break;
			
			case 'update':
				
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				}  
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				  $this->session->set_flashdata("success","Service has been Edited successfully");
				  $r=$this->admin_model->add_partner($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_partner($id,$admin_image1='');
						$this->session->set_flashdata("success","Service has been Edited successfully");
				}
				 redirect('admin/partner/view');
				break;
				default:
				# code...
				break;
		}
		}
	}
function pms($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		switch ($mode) 
		{
			case 'view':
				$data["users"] =get_Partners();
				
				$this->load->view('admin/pms_integration',$data);
				break;
			case 'edit':
				$data['edit'] = 'edit';
				$data['partner_id'] = $id;
				$data['partner'] = get_data('partners',array('partnar_id'=>$id))->row_array();
				$data['hotels'] = $this->db->query("SELECT CONCAT(property_id, '_', owner_id) AS hotel FROM `partners_hotels` WHERE partner_id = '".$id."'")->result_array();

				$this->load->view('admin/pms_integration',$data);
				break;
			case 'bulk_delete':
				$orders	= $this->input->post('order');
				if($orders)
				{
					foreach($orders as $order)
					{
						$result = $this->admin_model->pms_delete_users(insep_decode($order));
					}
					$this->session->set_flashdata('success','PMS Users details has been deleted Successfully');
					redirect('admin/pms/view','referesh');
				}
				else
				{
					$this->session->set_flashdata('error','Error Occurred While Deleting PMS user details');
					redirect('admin/pms/view','referesh');
				}
			break;
			case 'update':
				$this->form_validation->set_rules('company','Company Name', 'required');
				$this->form_validation->set_rules('website','Website Name', 'required|valid_url');
				$this->form_validation->set_rules('contact','Contact First Name', 'required');
				$this->form_validation->set_rules('company','Company Name', 'required');
				$this->form_validation->set_rules('email','Email Id', 'required|valid_email');
		 		$this->form_validation->set_message('required',"%s required");	
		 		$this->form_validation->set_message('valid_email',"%s incorrect");	
		 		$this->form_validation->set_message('valid_url',"%s Should be valid");				
				if ($this->form_validation->run() == FALSE)
				{
					$data['edit'] = 'edit';
					$data['partner_id'] = $id;
					$data['partner'] = get_data('partners',array('partnar_id'=>$id))->row_array();
					$this->load->view('admin/pms_integration',$data);
				}else{
					$update['country'] = $this->input->post('country');
					$update['company'] = $this->input->post('company');
					$update['website'] = $this->input->post('website');
					$update['firstname'] = $this->input->post('contact');
					$update['lastname'] = $this->input->post('lastname');
					$update['email'] = $this->input->post('email');
					$update['phone'] = $this->input->post('phone');
					$update['comments'] = $this->input->post('comments');
					if($this->input->post('ip'))
					{
						$ip = $this->input->post('ip');
						$ipexists = FALSE;
						foreach($ip as $val)
						{
							if($val != "")
							{
								$ipexists = TRUE;
							}
						}
						if($ipexists)
						{
							$exip = get_data('partners',array('partnar_id'=>$id))->row()->ip;
							if($exip != "")
							{
								$exip_array = explode(',', $exip);
								$ip = array_merge($ip,$exip_array);
							}
							$ips = array_unique($ip);
							$iip = implode(',', $ips);
							$update['ip'] = $iip;
						}
					}
					update_data('partners',$update,array('partnar_id'=>$id));
					
					$this->db->query("DELETE FROM partners_hotels WHERE partner_id = '".$id."'");
					
					if(count($this->input->post('hotels')) != 0)
					{
						$hotels  = $this->input->post('hotels');
						foreach($hotels as $hotel)
						{

							$details = explode('_',$hotel);
							$insert['partner_id'] = $id;
							$insert['property_id'] = $details[0];
							$insert['owner_id'] = $details[1];
							
							insert_data('partners_hotels',$insert);
						}
					}
					$this->session->set_flashdata('success','PMS Users details has been Updated Successfully');
					redirect('admin/pms/view','referesh');
				}
			break;
			default:
				# code...
				break;
		}
		}
	}

	function addip()
	{
		$ip = $this->input->post('ip');
		$ipexists = FALSE;
		foreach($ip as $val)
		{
			if($val != "")
			{
				$ipexists = TRUE;
			}
		}
		$id = $this->input->post('partner_id');
		if($ipexists)
		{
			$exip = get_data('partners',array('partnar_id'=>$id))->row()->ip;
			if($exip != "")
			{
				$exip_array = explode(',', $exip);
				$ip = array_merge($ip,$exip_array);
			}
			$ips = array_unique($ip);
			$iip = implode(',', $ips);
			$data['ip'] = $iip;
			update_data('partners',$data,array('partnar_id'=>$id));
			echo 1;
		}else{
			echo 0;
		}
	}

	function removeip()
	{
		$id = $this->input->post('id');
		$ip = $this->input->post('ip');
		$ips = get_data('partners',array('partnar_id'=>$id))->row()->ip;
		$ipss = explode(',', $ips);
		if(($key = array_search($ip, $ipss)) !== false) {
		    unset($ipss[$key]);
		}
		$data['ip'] = implode(',', $ipss);
		update_data('partners',$data,array('partnar_id'=>$id));
		echo 1;
	}

	function confirmpass(){
		$email=$_REQUEST['email'];
		$pass=$_REQUEST['pass'];
		$this->admin_model->confirm_pass($email,$pass);
		$this->session->set_flashdata("success","".$email." Partner email has been confirmed");
		echo "ok";

	}

	function  getpartner(){
		$id=$this->input->post('id');
		$split_id=explode('_', $id);
        $this->db->where('partnar_id',$split_id[1]);
		echo $this->db->get('partners')->row()->email;

	}

	function integration($mode,$id="")
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{
		$data["action"] =$mode;
		
		switch ($mode) 
		{
			case 'add':
			
				if($this->input->post('add_service'))
				{
				
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				    redirect('admin/integration/view');
				}  
				  $r=$this->admin_model->add_integration('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_integration('',$admin_image1);
						$this->session->set_flashdata("success","PMS Integration CMS has been inserted successfully");
						
				}
				redirect('admin/integration/view');		
						
					
			
				}
				else
				{
					//$this->load->view('admin/admin_header');
					$this->load->view('admin/integration',$data);
					//$this->load->view('admin/admin_footer');
				}
				break;
			case 'view':
				$this->db->order_by('id','desc');
				$data["users"] =get_data('integration_cms')->result();
				$this->load->view('admin/integration',$data);
				break;
			case 'edit':
				if(!$id)
					die();
				$udata=$this->admin_model->get_integrationcms_details($id,"array");
				$data=array_merge($udata,$data);
				$this->load->view('admin/integration',$data);
				break;
			case 'delete':
				if(!$id)
					die();
				delete_data("integration_cms",array("id"=>$id));
				redirect('admin/integration/view');
				break;
			
			case 'update':
				
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				}  
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				  $this->session->set_flashdata("success","PMS Integration has been Edited successfully");
				  $r=$this->admin_model->add_integration($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_integration($id,$admin_image1='');
						$this->session->set_flashdata("success","PMS Integration has been Edited successfully");
				}
				 redirect('admin/integration/view');
				break;
				default:
				# code...
				break;
		}
		}
	}



	function multiproperty($mode,$id="")
	{

		$sessionvar=$this->session->userdata('logged_user');

		$data['admin_logged']=$this->session->userdata('logged_user');

		if($sessionvar=="")

			{

				redirect('admin/index','refresh');

			}

		else

		{

		$data["action"] =$mode;

		

		switch ($mode) 

		{

			case 'add':
      			if($this->input->post('add_service'))
				{	
			$admin_image ="";
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				  $r=$this->admin_model->add_multiproperty('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_multiproperty('',$admin_image1);
						$this->session->set_flashdata("success","Multiproperty us has been inserted successfully");
				}
				 redirect('admin/multiproperty/view');
				 }else{
				 $this->load->view('admin/multiproperty',$data);
				 }
				break;
			case 'view':

				$data["users"] =$this->admin_model->get_multiproperty();

				$this->load->view('admin/multiproperty',$data);

				break;

			case 'edit':

				if(!$id)

					die();

				$udata=$this->admin_model->get_multiproperty_details($id,"array");

				$data=array_merge($udata,$data);

				$this->load->view('admin/multiproperty',$data);

				break;

			case 'delete':

				if(!$id)

					die();

				delete_data("Multiproperty",array("id"=>$id));
			$this->session->set_flashdata("success","Multiproperty us has been deleted successfully");
			redirect('admin/multiproperty/view');

				break;

			

			case 'update':

				$admin_image="";
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				} 
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				$this->session->set_flashdata("success","Multiproperty Us has been Updated successfully");
				  $r=$this->admin_model->add_multiproperty($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_multiproperty($id,$admin_image1);
						$this->session->set_flashdata("success","Multiproperty Us has been Updated successfully");
				}
				 redirect('admin/multiproperty/view');
				break;
            default:
     	break;
    	}
	 }
	}




	function connectchannels($mode,$id="")
	{

		$sessionvar=$this->session->userdata('logged_user');

		$data['admin_logged']=$this->session->userdata('logged_user');

		if($sessionvar=="")

			{

				redirect('admin/index','refresh');

			}

		else

		{

		$data["action"] =$mode;

		

		switch ($mode) 

		{

			case 'add':
      			if($this->input->post('add_service'))
				{	
			$admin_image ="";
                	$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			     {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				}  
				  $r=$this->admin_model->add_connectchannels('',$profile_image);
				}else
				{
				        $admin_image1="default.jpeg";
				        $id=$this->admin_model->add_connectchannels('',$admin_image1);
						$this->session->set_flashdata("success","Connected Channels us has been inserted successfully");
				}
				 redirect('admin/connectchannels/view');
				 }else{
				 $this->load->view('admin/connectchannels',$data);
				 }
				break;
			case 'view':

				$data["users"] =$this->admin_model->get_connectchannels();

				$this->load->view('admin/connectchannels',$data);

				break;

			case 'edit':

				if(!$id)

					die();

				$udata=$this->admin_model->get_connectchannels_details($id,"array");

				$data=array_merge($udata,$data);

				$this->load->view('admin/connectchannels',$data);

				break;

			case 'delete':

				if(!$id)

					die();

				delete_data("connectchannels",array("id"=>$id));
			$this->session->set_flashdata("success","Connected Channels us has been deleted successfully");
			redirect('admin/connectchannels/view');

				break;

			

			case 'update':

				$admin_image="";
				$admin_image = $_FILES['image']['name'];				
				if ($admin_image!="")
			    {
			    $rnumber = mt_rand(0,999999);
				$ext = substr(strrchr($admin_image, "."), 1);
				$filename=$rnumber;
				$profile_image=$filename.".".$ext;
				$config['upload_path'] ='uploads';
				$config['allowed_types'] = '*';
				$config['file_name']=$filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);	
				if($admin_image!="" & (!$this->upload->do_upload('image')))
				{
					$error="Profile image ".$this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
				   
				} 
				else
				{
					image_resizer("uploads/",$profile_image, 1600, 500);
				}
				$this->session->set_flashdata("success","Connected Channels Us has been Updated successfully");
				  $r=$this->admin_model->add_connectchannels($id,$profile_image);
				}else
				{
				        $admin_image1=$this->input->post('hidimage');
				        $id=$this->admin_model->add_connectchannels($id,$admin_image1);
						$this->session->set_flashdata("success","Connected Channels Us has been Updated successfully");
				}
				 redirect('admin/connectchannels/view');
				break;
            default:
     	break;
    	}
	 }
	}
	
	
	//alerts and announcements


function notifications()
{
	$sessionvar=$this->session->userdata('logged_user');
	$data['notifications']  = $this->admin_model->getAllNotifications();

	if($sessionvar!='')
	{		
		if($this->input->post('submit'))
		{			
			$result	=	$this->admin_model->nofification();
			if($result)
			{

				$this->session->set_flashdata('success',"Nofification has been send successfully send to Corresponding Users");
				redirect('admin/notifications');
			}
			else
			{
				$this->session->set_flashdata('error',"Nofification has send been successfully send to Corresponding Users");
				redirect('admin/notifications');
			}
		}
		else
		{
			$this->load->view('admin/notifications',$data);
		}
	}
	else
	{
		redirect('admin','refresh');	
	}
				
}

function notifications_delete($id = ""){
	if($id != ""){
		$n_id = insep_decode($id);
		if($this->admin_model->delecte_notification($n_id)){
			$this->session->set_flashdata('success',"Nofification has been Deleted successfully");
			redirect('admin/notifications');
		}else{
			$this->session->set_flashdata('error',"Error in Notification Delete.Try Again!.");
			redirect('admin/notifications');
		}
	}else{
		$del_all = $this->input->post("delete_multiple");
		if(count($del_all) != 0){
			foreach ($del_all as $d_id) {
				$n_id = insep_decode($d_id);
				if($this->admin_model->delecte_notification($n_id)){
					$this->session->set_flashdata('success',"Nofification has been Deleted successfully");
				}else{
					$this->session->set_flashdata('error',"Error in Notification Delete.Try Again!.");
				}
			}		
		}
		redirect('admin/notifications');
	}
}
function partners($section='',$user_id=''){
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
			//echo 'manage';die;
			$data["action"] =$section;
			switch ($section) 
			{
				case 'delete':
					$result = $this->admin_model->delete_partner($user_id);
					if($result){
							$this->session->set_flashdata('success','Partner details deleted Successfully');
							redirect('admin/partners','referesh');
						}
						else{
							$this->session->set_flashdata('error','Error Occurred While Deleting Partner details');
							redirect('admin/partners','referesh');
			}
				break;
				case 'status':
				$id = $user_id;
				$user=get_data("partners",array("partnar_id"=>$id))->row();
				if($user->status=='Confirmed')
				{
					$status='Not Confirmed';
				}
				else{
				     $status='Confirmed';
	                 $admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
	                 $get_email_info        =    get_mail_template('17');
			         $subject        =    $get_email_info['subject'];
			         $template        =    $get_email_info['message'];
			         
			         $data = array(
			              '###USERNAME###'=>$user->firstname,
			              '###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
			              '###SITENAME###'=>$admin_detail->company_name,
			              '###APIKEY###'=>$admin_detail->apikey,
			              '###EMAIL###'=>$user->email,
			              );
				     $content_pop = strtr($template,$data);
				     $this->mailsettings();
				     $this->email->from($admin_detail->email_id);
				     $this->email->to($user->email);
				     $this->email->subject($subject);
				     $this->email->message($content_pop);
					 $this->email->send();
					
				}	
				$this->session->set_flashdata("success","Partner status has been updated successfully");
				update_data("partners",array("status"=>$status,"apikey"=>$admin_detail->apikey),array("partnar_id"=>$id));
				redirect('admin/partners','referesh');
				break;
				default:
					$data['users'] = $this->admin_model->get_partner_details();
					$data['action'] = 'list';
					$this->load->view('admin/partners',$data);
				break;
				
			}
		} 
	}
	
	function all_hotels_delete($hotel_id)
{
	$sessionvar=$this->session->userdata('logged_user');
	if($sessionvar!='')
	{
		$available = get_data(HOTEL,array('hotel_id'=>insep_decode($hotel_id)))->row_array();
		if(count($available)!=0)
		{
			if($this->admin_model->deletehotel(insep_decode($hotel_id))){
				$this->session->set_flashdata('success','Hotel has been deleted successfully!!!');
				redirect('admin/all_hotels','refresh');
			}
			else
			{
				$this->session->set_flashdata('error','Hotel delete error occur. Please try again!!!');
				redirect('admin/all_hotels','refresh');
			}
			/*$updata['subscribe_status']='0';
			$updata['status']='0';
			if(update_data(HOTEL,$updata,array('hotel_id'=>insep_decode($hotel_id))))
			{
				$this->session->set_flashdata('success','Hotel has been deleted successfully!!!');
				redirect('admin/all_hotels','refresh');
			}
			else
			{
				$this->session->set_flashdata('error','Hotel delete error occur. Please try again!!!');
				redirect('admin/all_hotels','refresh');
			}*/
		}
		else
		{
			$this->session->set_flashdata('error','Hotel delete error occur. Please try again!!!');
			redirect('admin/all_hotels','refresh');
			
		}
	}
	else
	{
		$this->session->set_flashdata('error','Hotel delete error occur. Please try again!!!');
		redirect('admin','refresh');
	}
}

	function change_channel_status($status,$channel_id){
		$this->db->where("channel_id",$channel_id);
		$this->db->set('status',$status);
		$update = $this->db->update(TBL_CHANNEL);
		if($update)
		{
			echo 1;
		}
	}
	
	function mangecctypes($mode='',$id='')
	{
		/* print_r($this->input->post());
		die; */
		$data['action']	=	$mode;
		$sessionvar=$this->session->userdata('logged_user');
		
		if($sessionvar!='')
		{
			switch ($mode) 
			{
				case 'view':
				$data['cctypes']	=	get_data('credit_card_types')->result_array();
				$this->load->view('admin/manage_cctypes',$data);
				break;
				case 'add':
				if($this->input->post('add_cc')!='')
				{
					extract($this->input->post());
					$exist = get_data('credit_card_types',array('cc_type_name'=>$special));
					if($exist->num_rows==0)
					{
						$idata['cc_type_name']	=	$special;
						$idata['seo_url']		=	$this->admin_model->seoUrl($special);
						insert_data('credit_card_types',$idata);
						$this->session->set_flashdata('success','The CC name added successfully!!!');
						redirect('admin/mangecctypes/view','refresh');
						
					}
					else
					{
						$this->session->set_flashdata('error','Enter CC name already exist!!!');
						redirect('admin/mangecctypes/view','refresh');
					}
				}
				else
				{
					redirect('admin/mangecctypes/view','refresh');
				}
				break;
				case "update":
				if($this->input->post('add_cc')=='')
				{
					extract($this->input->post());
					$this->db->where_not_in('cc_type_id',$update_id);
					$exist = get_data('credit_card_types',array('cc_type_name'=>$update));
					if($exist->num_rows==0)
					{
						$idata['cc_type_name']	=	$update;
						$idata['seo_url']		=	$this->admin_model->seoUrl($update);
						update_data('credit_card_types',$idata,array("cc_type_id"=>$update_id));
						$this->session->set_flashdata('success','The CC name updated successfully!!!');
						redirect('admin/mangecctypes/view','refresh');
					}
					else
					{
						$this->session->set_flashdata('error','Enter CC name already exist!!!');
						redirect('admin/mangecctypes/view','refresh');
					}
				}
				break;
				case 'status':
				extract($this->input->post());
				$cctypes	=	get_data('credit_card_types',array('cc_type_id'=>$source),'cc_type_status')->row_array();
				if(count($cctypes)!=0)
				{
					if($cctypes['cc_type_status']=='1')
					{
						$udata['cc_type_status']	=	'0';
					}
					else
					{
						$udata['cc_type_status']	=	'1';
					}
					if(update_data('credit_card_types',$udata,array('cc_type_id'=>$source)))
					{
						echo '1';
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
				default:
				break;
			
			}
		}
		else
		{
			redirect('admin','refresh');
		}
	}

	/* sharmila starts here  */

	function Manage_TicketSupport($mode='',$id="")
	{		
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{			
			$this->db->order_by('id','desc');
			$data["team"] =get_data('Manage_User_Support')->result_array();		
			
		$data['page_title']	=  'User Support';
		$this->load->view('admin/Manage_TicketSupport',$data);
	    }
	}

	

	function Manage_Ticket_Status($mode='',$id="")
	{		
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{				
				redirect('admin/index','refresh');
			}
		else
		{				
			    extract($this->input->post());
				if(update_data("Manage_User_Support",array("status"=>$status),array("id"=>$id)))
				{
					echo '1';
				}
				else
				{
					echo '0';
				}
	    }
	}

	function Manage_View_Support($id='')
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{	    
		    $data['single_view'] = get_data('Manage_User_Support',array('seo_url'=>$id))->row();
		  /*  echo '<pre>';
		    print_r($data);die;*/
		    $this->load->view('admin/Manage_View_Support',$data);
	    }
	}

	function Replay_To_User()
	{
	
	$sessionvar=$this->session->userdata('logged_user');
	//$data['admin_logged']=$this->session->userdata('logged_user');
	if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}	
	else
	{
		$date = date('Y-m-d h:i:s');
		extract($this->input->post());
	
	$data['support_id'] = $support_id;
	$data['from_id'] = $from_id;
	$data['to_id'] = $to_id;
	$data['is_admin'] = $is_admin;
	$data['message'] = $replay;
	$data['created'] = $date;
	if(insert_data('Replay_User_Suport',$data))
	{				
	?>
	<ul class="chats">
	<?php 
	$this->db->order_by('id','desc');
	$get_replay = get_data('Replay_User_Suport',array('support_id'=>$support_id))->result_array();
	if(count($get_replay)!='0')
	{

	foreach($get_replay as $val)
	{
	extract($val);
	?>
	<li <?php if($is_admin=='1')
	{
	echo "class='in'";
	}
	else
	{
	echo "class='out'";
	}?>>
	<?php if($is_admin=='1')
	{	
	$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
	?>
	<img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/<?php echo $name->admin_image;?>"/>
	<?php } else { 
	$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
	?>
	<img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/126899.jpg"/>
	<?php } ?>
	<div class="message">
	<span class="arrow">
	</span>
	<a href="javascript:;" class="name">
	<?php if($is_admin=='1')
	{
	$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
	if(count($name)!='0')
	{
		if($name->name!='')
		{
			echo $name->name;
		}
		else
		{
			echo 'Admin';
		}
	}
	else
	{
		echo 'Admin';
	}
	}
	else
	{
	$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
	if(count($name!=''))
	{
		echo $name->fname.' '.$name->lname;
	}
	}?>
	</a>
	<span class="datetime">
	at <?php echo date('d-M-y h:i a',strtotime($created))?>
	</span>
	<span class="body">
	<?php echo $message?>
	</span>
	</div>
	</li>
	<?php } } else { ?>
	<li class="out">
	<div class="message">
	<span class="body">
	No Records Found...
	</span>
	</div>
	</li>
	<?php } ?>

	</ul>
	<?php
	}
	else
	{
	echo '0';
	}
	//redirect('admin/admin/Manage_User_Support/single_view/'.$seo_url);
	}

	}


	function ajax_message()
	{
	extract($this->input->post());
	?>
	<ul class="chats">
	<?php 
	$this->db->order_by('id','desc');
	$get_replay = get_data('Replay_User_Suport',array('support_id'=>$support_id))->result_array();
	if(count($get_replay)!='0')
	{

	foreach($get_replay as $val)
	{
	extract($val);
	?>
	<li <?php if($is_admin=='1')
	{
	echo "class='in'";
	}
	else
	{
	echo "class='out'";
	}?>>
		<?php if($is_admin=='1')
	{	
		$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
	?>
	<img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/<?php echo $name->admin_image;?>"/>
	<?php } else { 
		$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
	?>
	<img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/126899.jpg"/>
	<?php } ?>
	<div class="message">
		<span class="arrow">
		</span>
		<a href="javascript:;" class="name">
			<?php if($is_admin=='1')
			{
				$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
				if(count($name)!='0')
				{
					if($name->name!='')
					{
						echo $name->name;
					}
					else
					{
						echo 'Admin';
					}
				}
				else
				{
					echo 'Admin';
				}
			}
			else
			{
				$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
				if(count($name!=''))
				{
					echo $name->fname.' '.$name->lname;
				}
			}?>
		</a>
		<span class="datetime">
			 at <?php echo date('d-M-y h:i a',strtotime($created))?>
		</span>
		<span class="body">
			 <?php echo $message?>
		</span>
	</div>
	</li>
	<?php } } else { ?>
	<li class="out">
	<div class="message">
		<span class="body">
			No Records Found...
		</span>
	</div>
	</li>
	<?php } ?>

	</ul>
	<?php

	}

	function delete_ticket_support($id='')
	{
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
			{
				redirect('admin/index','refresh');
			}
		else
		{	    
		if(delete_data("Manage_User_Support",array("id"=>insep_decode($id))))
				{
				delete_data('Replay_User_Suport',array('support_id'=>insep_decode($id)));
				$this->session->set_flashdata("success","Support has been deleted successfully!!!");
				}else
				$this->session->set_flashdata("error","Support has not been deleted successfully!!!");
				redirect('admin/Manage_TicketSupport','referesh');
		}
	}

	function export_reservation_xls_old($id="")
	{
		$id=$this->admin_model->decryptIt($id);
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{
			$chaReserCheckCount = array();	
	
			$query = $this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id FROM `manage_reservation` AS e order by e.reservation_id desc");
			if($query)
			{
				$chaReserCheckCount = array_merge($chaReserCheckCount,$query->result());
			}
		
			$this->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query');
			
			$this->db->join(CONNECT.' as C','A.channel_id=C.channel_id');

			$query = $this->db->get(ALL.' as A');

			if($query)
			{
				$all_api_new_book = $query->result_array();
			}
			
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
				
				if($channel_id==1 || $channel_id==17)
				{
					$room_re_o	= $select[11];	
					$room_re_t	= $select[12];
				}
				if($channel_id==8)
				{
					$room_re_o	= $select[4];	
					$room_re_t	= $select[12];
				}
				if($channel_id==11 || $channel_id==2)
				{
					$room_re_o	= $select[4];	
					$room_re_t	= $select[11];
				}

				$cahquery = $this->db->query('SELECT ( CASE WHEN R.'.$select[2].' ="11" THEN "Reservation" WHEN R.'.$select[2].' ="12" THEN "Modification" WHEN R.'.$select[2].' ="12" THEN "Cancel" ELSE R.'.$select[2].' END )as status , R.'.$select[3].' as guest_name , R.'.$select[9].' as price , R.'.$select[7].' as reservation_code , R.'.$select[1].' as booking_date , R.'.$select[8].' as currency_id , C.channel_name as channel, C.channel_id , R.'.$select[18].' as mobile , R.'.$select[19].' as user_email , R.'.$room_re_o.' as room_one , R.'.$room_re_t.' as room_two , DATE_FORMAT(R.'.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT(R.'.$select[6].',"%d/%m/%Y") as end_date FROM ('.$channel_table_name.' AS R) JOIN '.TBL_CHANNEL.' AS C ON R.channel_id = C.channel_id');

				$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());

			}
			if($chaReserCheckCount)
			{	
				$fpp		= 	fopen('php://output', 'w');
				
				$files	=	"ManageReservation-".time()."-download.xls";
				
				/* $header	=	array("#","Status","Name","Mail","Mobile","Room","Channel","Check In","Check Out","Booked Date","Reservation Id","Amount"); */
				
				$header	=	array("#","Name");
						  
				fputcsv($fpp, $header);
				
				$num_column = count($header);
				
				$ji=0;
				
				foreach($chaReserCheckCount as $row)
				{
					$ji++;
					
					$status		=	$row->status;
					
					$guest_name	=	$row->guest_name;
					
					if($row->channel_id == 8) { $user_email	=	'N/A'; } 
					else if($row->channel_id == 2) { $book_data	=	get_data(BOOK_RESERV,array('id'=>$row->mobile),'email,telephone')->row_array(); $book_data['email']!='' ? $user_email	=	$book_data['email'] : $user_email	=	'N/A';}	
					else { $user_email	=	$row->user_email; }
					
					
					if($row->channel_id == 8) { $mobile	=	'N/A'; } else if($row->channel_id == 2) {	$book_data['telephone']!='' ? $mobile	=	$book_data['telephone'] : $mobile	=	'N/A'; } else { $mobile	=	$row->mobile; }
					
					if($row->channel_id == 0)
					{
						$room_details = get_data(TBL_PROPERTY,array('property_id'=>$row->room_id),'property_name')->row_array();
						
						if(count($room_details)!='0') 
						{ 
							$roomName	=	ucfirst($room_details['property_name']);
						}
						if(isset($roomName))
						{
							$roomName	=	ucfirst($roomName);
						}
						else
						{
							$roomName	=	'"No Room Set"';
						}
					}
					if($row->channel_id == 1)
					{
						$roomtypeid 	=	$row->room_one;
						
						$rateplanid 	=	$row->room_two;
						
						$user_det 		=	get_data(EXP_RESERV,array('booking_id'=>$row->reservation_code),'user_id,hotel_id')->row_array();
						
						$roomdetails 	=	getExpediaRoom($roomtypeid,$rateplanid,$user_det['user_id'],$user_det['hotel_id']);
						
						if(count($roomdetails) !=0)
						{
							$roomtypeid = $roomdetails['roomtypeId'];
							
							$rateplanid = $roomdetails['rateplanid'];
						}
						
						$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rate_type_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;

						if(!$roomName){
							$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rateplan_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;
						}
						if(isset($roomName))
						{
							$roomName	=	ucfirst($roomName);
						}
						else
						{
							$roomName	=	'"No Room Set"';
						}
					}
					else if($row->channel_id == 2)
					{
						$user_dets	=	get_data(BOOK_ROOMS,array('roomreservation_id'=>$row->reservation_code),'user_id,hotel_hotel_id')->row_array();
					
						$roomName	=	get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping_BOOKING',array('B_room_id'=>$row->room_one, 'B_rate_id' => $row->room_two,'owner_id'=>$user_dets['user_id'],'hotel_id'=>$user_dets['hotel_hotel_id']))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

						if(isset($roomName))
						{
							$roomName	=	ucfirst($roomName);
						}
						else
						{
							$roomName	=	'"No Room Set"';
						}
					}

					$channel			=	$row->channel;

					$start_date			=	$row->start_date;

					$end_date			=	$row->end_date;
					
					$booking_date		=	date('M d,Y h:i:s A',strtotime(str_replace("/","-",$row->booking_date)));
					
					$reservation_code	=	$row->reservation_code;
					
					if($row->channel_id==0)
					{
						$price	=	get_data(TBL_CUR,array('currency_id'=>$row->currency_id))->row()->symbol.' '.number_format($row->price);
					}
					else
					{
						$price	=	$row->currency_id.' '.$row->price;
					}
					
					/* $arr122		=	array($ji,$status,$guest_name,$user_email,$mobile,$roomName,$channel,$start_date,$end_date,$booking_date,$reservation_code,$price); */
					
					$arr122		=	array($ji,$guest_name);
					
					fputcsv($fpp, $arr122);
				}
				header('Content-Encoding: UTF-8');
				header('Content-type: application/csv; charset=UTF-8');
				header('Content-Disposition: attachment; filename='.$files);
				echo "\xEF\xBB\xBF"; 
			}
		}
	}

	function export_reservation_pdf($id="")
	{
		$this->load->library('M_pdf');	
		$sessionvar=$this->session->userdata('logged_user');
		$data['admin_logged']=$this->session->userdata('logged_user');
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{				

		$chaReserCheckCount = array();	
	
		$query = $this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id FROM `manage_reservation` AS e order by e.reservation_id desc");

		$chaReserCheckCount = array_merge($chaReserCheckCount,$query->result());
	
		$this->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query');
		
		$this->db->join(CONNECT.' as C','A.channel_id=C.channel_id');

		$query = $this->db->get(ALL.' as A');

		if($query)
		{
			$all_api_new_book = $query->result_array();
		}
		
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
			
			if($channel_id==1 || $channel_id==17)
			{
				$room_re_o	= $select[11];	
				$room_re_t	= $select[12];
			}
			if($channel_id==8)
			{
				$room_re_o	= $select[4];	
				$room_re_t	= $select[12];
			}
			if($channel_id==11 || $channel_id==2)
			{
				$room_re_o	= $select[4];	
				$room_re_t	= $select[11];
			}

			$cahquery = $this->db->query('SELECT ( CASE WHEN R.'.$select[2].' ="11" THEN "Reservation" WHEN R.'.$select[2].' ="12" THEN "Modification" WHEN R.'.$select[2].' ="12" THEN "Cancel" ELSE R.'.$select[2].' END )as status , R.'.$select[3].' as guest_name , R.'.$select[9].' as price , R.'.$select[7].' as reservation_code , R.'.$select[1].' as booking_date , R.'.$select[8].' as currency_id , C.channel_name as channel, C.channel_id , R.'.$select[18].' as mobile , R.'.$select[19].' as user_email , R.'.$room_re_o.' as room_one , R.'.$room_re_t.' as room_two , DATE_FORMAT(R.'.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT(R.'.$select[6].',"%d/%m/%Y") as end_date FROM ('.$channel_table_name.' AS R) JOIN '.TBL_CHANNEL.' AS C ON R.channel_id = C.channel_id');
			
			/* echo $this->db->last_query(); */
			$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());

		}
		
		 /* die; */

			$this->data["reservation"]	=	$chaReserCheckCount;	
			
			/* echo '<pre>';
			print_r($this->data);
			die; */
			$html= $this->load->view('admin/pdf_output',$this->data,true);
		
			/* die; */ 
			 
			$pdfFilePath ="Manage Reservation-".time()."-download.pdf";
			
			$pdf = $this->m_pdf->load();
			
			$html = $pdf->WriteHTML($html);
			
			$pdf->Output($pdfFilePath, "D");

		}
	}

	function export_reservation_xls($id="")
	{
		$id=$this->admin_model->decryptIt($id);
		
		$sessionvar=$this->session->userdata('logged_user');
		
		$data['admin_logged']=$this->session->userdata('logged_user');
		
		if($sessionvar=="")
		{
			redirect('admin/index','refresh');
		}
		else
		{				
		
			$filename = "Reservations-".time().".xls";	
			
			$chaReserCheckCount = array();	
	
			$query = $this->db->query("select e.status,e.guest_name, 'Hoteratus' AS channel, e.start_date,e.end_date,e.created_date as booking_date,e.reservation_code,e.price,e.email as user_email,e.mobile,e.room_id,e.channel_id,e.currency_id FROM `manage_reservation` AS e order by e.reservation_id desc");

			$chaReserCheckCount = array_merge($chaReserCheckCount,$query->result());
		
			$this->db->select('A.auto_id,A.channel_id,A.channel_table_name,A.import_mapping_table,A.fetch_query_count,A.fetch_query_all,A.ical_query,A.reports_query');
			
			$this->db->join(CONNECT.' as C','A.channel_id=C.channel_id');

			$query = $this->db->get(ALL.' as A');

			if($query)
			{
				$all_api_new_book = $query->result_array();
			}
			
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
				
				if($channel_id==1 || $channel_id==17)
				{
					$room_re_o	= $select[11];	
					$room_re_t	= $select[12];
				}
				if($channel_id==8)
				{
					$room_re_o	= $select[4];	
					$room_re_t	= $select[12];
				}
				if($channel_id==11 || $channel_id==2)
				{
					$room_re_o	= $select[4];	
					$room_re_t	= $select[11];
				}

				$cahquery = $this->db->query('SELECT ( CASE WHEN R.'.$select[2].' ="11" THEN "Reservation" WHEN R.'.$select[2].' ="12" THEN "Modification" WHEN R.'.$select[2].' ="12" THEN "Cancel" ELSE R.'.$select[2].' END )as status , R.'.$select[3].' as guest_name , R.'.$select[9].' as price , R.'.$select[7].' as reservation_code , R.'.$select[1].' as booking_date , R.'.$select[8].' as currency_id , C.channel_name as channel, C.channel_id , R.'.$select[18].' as mobile , R.'.$select[19].' as user_email , R.'.$room_re_o.' as room_one , R.'.$room_re_t.' as room_two , DATE_FORMAT(R.'.$select[5].',"%d/%m/%Y") as start_date, DATE_FORMAT(R.'.$select[6].',"%d/%m/%Y") as end_date FROM ('.$channel_table_name.' AS R) JOIN '.TBL_CHANNEL.' AS C ON R.channel_id = C.channel_id');

				$chaReserCheckCount = array_merge($chaReserCheckCount,$cahquery->result());

			}
			
			$test	=	'<table width="100%" style="width: 100%;">
						
						<thead>
						
							<tr>
						
							<th style="width: 106px; text-align:left;">Status</th>
							
							<th style="width: 106px; text-align:left;">Name</th>
							
							<th style="width: 106px; text-align:left;">Mail</th>
							
							<th style="width: 106px; text-align:left;">Mobile</th>
							
							<th style="width: 106px; text-align:left;">Room</th>
							
							<th style="width: 106px; text-align:left;">Channel</th>
							
							<th style="width: 106px; text-align:left;">Check In</th>
							
							<th style="width: 106px; text-align:left;">Check Out</th>
							
							<th style="width: 106px; text-align:left;">Booked Date</th>
							
							<th style="width: 106px; text-align:left;">Reservation Id</th>
							
							<th style="width: 106px; text-align:left;">Amount</th>
							
							</tr>
							
						</thead>
						
						<tbody>';
						
						foreach($chaReserCheckCount as $row)
						{
							if($row->channel_id == 8) { $user_email	=	'N/A'; } 
							else if($row->channel_id == 2) { $book_data	=	get_data(BOOK_RESERV,array('id'=>$row->mobile),'email,telephone')->row_array();	$book_data['email']!='' ? $user_email	=	$book_data['email'] : $user_email	=	'N/A';}	
							else { $user_email	=	$row->user_email; }
							
							
							if($row->channel_id == 8) { $mobile	=	'N/A'; } else if($row->channel_id == 2) { 	$book_data['telephone']!='' ? $mobile	=	$book_data['telephone'] : $mobile	=	'N/A'; } else { $mobile	=	$row->mobile; }
							
							if($row->channel_id == 0)
							{
								$room_details = get_data(TBL_PROPERTY,array('property_id'=>$row->room_id),'property_name')->row_array();
								
								if(count($room_details)!='0') 
								{ 
									$roomName	=	ucfirst($room_details['property_name']);
								}
								if(isset($roomName))
								{
									$roomName	=	($roomName);
								}
								else
								{
									$roomName	=	 '"No Room Set"';
								}
							}
							if($row->channel_id == 1)
							{
								$roomtypeid 	=	$row->room_one;
								
								$rateplanid 	=	$row->room_two;
								
								$user_det 		=	get_data(EXP_RESERV,array('booking_id'=>$row->reservation_code),'user_id,hotel_id')->row_array();
								
								$roomdetails 	=	getExpediaRoom($roomtypeid,$rateplanid,$user_det['user_id'],$user_det['hotel_id']);
								
								if(count($roomdetails) !=0)
								{
									$roomtypeid = $roomdetails['roomtypeId'];
									
									$rateplanid = $roomdetails['rateplanid'];
								}
								
								$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rate_type_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;

								if(!$roomName){
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rateplan_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;
								}
								if(isset($roomName))
								{
									$roomName	=	 ucfirst($roomName);
								}
								else
								{
									$roomName	=	 '"No Room Set"';
								}
							}
							else if($row->channel_id == 2)
							{
								$user_dets	=	get_data(BOOK_ROOMS,array('roomreservation_id'=>$row->reservation_code),'user_id,hotel_hotel_id')->row_array();
							
								$roomName	=	get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping_BOOKING',array('B_room_id'=>$row->room_one, 'B_rate_id' => $row->room_two,'owner_id'=>$user_dets['user_id'],'hotel_id'=>$user_dets['hotel_hotel_id']))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

								if(isset($roomName))
								{
									$roomName	=	ucfirst($roomName);
								}
								else
								{
									$roomName	=	 '"No Room Set"';
								}
							}
							
							if($row->channel_id==0)
							{
								$price	=	get_data(TBL_CUR,array('currency_id'=>$row->currency_id))->row()->symbol.' '.number_format($row->price);
							}
							else
							{
								$price	=	$row->currency_id.' '.$row->price;
							}
							
							$test	.='<tr>
							<td>'.$row->status.'</td>
							<td>'.$row->guest_name.'</td>
							<td>'.$user_email.'</td>
							<td>'.$mobile.'</td>
							<td>'.$roomName.'</td>
							<td>'.$row->channel.'</td>
							<td>'.$row->start_date.'</td>
							<td>'.$row->end_date.'</td>
							<td>'.date('M d,Y h:i:s A',strtotime(str_replace("/","-",$row->booking_date))).'</td>
							<td>'.$row->reservation_code.'</td>
							<td>'.$price.'</td></tr>';
						}
						
						$test	.='</tbody>
								</table>';
						header('Content-Encoding: UTF-8');
						header('Content-type: application/csv; charset=UTF-8');
						header("Content-type: application/vnd.ms-excel");
						header("Content-Disposition: attachment; filename=".$filename);
						echo $test;
		}
	}



	
}//class ends
?>