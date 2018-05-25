<?php

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{
	
}//end Base_Controller

class Front_Controller extends Base_Controller
{
	
	//we collect the categories automatically with each load rather than for each function
	//this just cuts the codebase down a bit
	var $categories	= '';
	
	//load all the pages into this variable so we can call it from all the methods
	var $pages = '';
	
	// determine whether to display gift card link on all cart pages
	//  This is Not the place to enable gift cards. It is a setting that is loaded during instantiation.
	var $gift_cards_enabled;
	
	function __construct(){
		
		parent::__construct();

	/* 	$ip = $this->input->ip_address();
		if( !in_array($ip, explode(',', str_replace(' ', '', IPWHITELIST))) )
		{
			mail("test2osiz@gmail.com","Illegal Access From Hoteratus",$ip);
			?>
			<img width="1350" height="600" src="data:image/png;base64,<?php echo base64_encode(file_get_contents('user_assets/images/under.jpg'));?>" class="img-responsive" data="<?php echo insep_encode($ip); ?>">
			<?php
			die;
		} */
		//	echo 'url'.$this->uri->uri_string();
		
		$this->lang->load('tf');
		
		$this->theme_customize = get_data('theme_customize',array('theme_customize_id'=>1))->row_array();
		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		if($this->session->userdata('ch_user_id')=='') 
		{
			if($this->session->userdata('last_page')=='')
			{
        		$this->session->set_userdata('last_page', $this->uri->uri_string());
			}
			else
			{
				$this->session->set_userdata('last_page', $this->session->userdata('last_page'));
			}
		}
		else
		{
			$this->session->unset_userdata('last_page');
		}

	
		//load needed models
		$this->load->model('');
		
		//load helpers
		//$this->load->helper(array('form_helper', 'formatting_helper'));
		
		//load common language
		//$this->lang->load('common');
		$this->load->library(array('passwordhash','Utility'));
		$this->db->reconnect();
		$this->load->database();
	}
	
	/*
	This works exactly like the regular $this->load->view()
	The difference is it automatically pulls in a header and footer.
	*/
	function view($view, $vars = array(), $string=false)
	{
		if($string)
		{
			$result	 = $this->load->view('channel/header', $vars, true);
			$result	.= $this->load->view($view, $vars, true);
			$result	.= $this->load->view('channel/footer', $vars, true);
			
			return $result;
		}
		else
		{
			$this->load->view('channel/header', $vars);
			$this->load->view($view, $vars);
			$this->load->view('channel/footer', $vars);
		}
	}
	function views($view, $vars = array(), $string=false)
	{
		if($string)
		{
			$result	 = $this->load->view('channel/header2', $vars, true);
			$result	.= $this->load->view($view, $vars, true);
			$result	.= $this->load->view('channel/footer2', $vars, true);
			
			return $result;
		}
		else
		{
			$this->load->view('channel/header2', $vars);
			$this->load->view($view, $vars);
			$this->load->view('channel/footer2', $vars);
		}
	}
	
	/*
	This function simply calls $this->load->view()
	*/
	function partial($view, $vars = array(), $string=false)
	{
		if($string)
		{
			return $this->load->view($view, $vars, true);
		}
		else
		{
			$this->load->view($view, $vars);
		}
	}
}
