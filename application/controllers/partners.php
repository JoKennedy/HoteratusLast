<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class partners extends CI_Controller {
 public function __construct()
   {
        parent::__construct();	
	/*	if(uri(3)!='getResrvationCronFromExpdiaTest')
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
		$this->load->model('partner_model');
		$this->load->helper('ckeditor');
        // Your own constructor code
   }

   function index()
   {
   	    $data['partner']=$this->db->get('partner_cms')->result();
     	$data['page_heading'] = 'Partners';
   	    $this->load->view('channel/header',$data);
		$this->load->view('channel/partners',$data);
		$this->load->view('channel/footer',$data);
	
   }
 
 function partner_join()
{
		
			$this->form_validation->set_rules('company','Company Name', 'required');
			$this->form_validation->set_rules('website','Website Name', 'required|valid_url');
			$this->form_validation->set_rules('agree','Terms and Conditions', 'required');
			$this->form_validation->set_rules('contact','Contact First Name', 'required');
			$this->form_validation->set_rules('company','Company Name', 'required');
			$this->form_validation->set_rules('email','Email Id', 'required|valid_email');
	 		$this->form_validation->set_message('required',"%s required");	
	 		$this->form_validation->set_message('valid_email',"%s incorrect");	
	 		$this->form_validation->set_message('valid_url',"%s Should be valid");							
			
			if ($this->form_validation->run() == FALSE)
				{		
					$data['page_heading'] = 'Partners';
					$data['partner']=$this->db->get('partner_cms')->result();
			   	    $this->load->view('channel/header',$data);
					$this->load->view('channel/partners',$data);
					$this->load->view('channel/footer',$data);	
				}
			else
			{
				//$data['error']="";
					$email=$this->input->post('email');
					if($this->partner_model->check_partner($email)=='exist')
					{							
						$this->session->set_flashdata('error'," Email  ".$email."  Already Exist .");
						//redirect('partners/index','refresh');
						
					    $data['page_heading'] = 'Partners';
					    $data['emailerror'] = " Email  ".$email."  Already Exist .";
					    $data['partner']=$this->db->get('partner_cms')->result();
				   	    $this->load->view('channel/header',$data);
						$this->load->view('channel/partners',$data);
						$this->load->view('channel/footer',$data);										
					}
					else
					{	
						$this->partner_model->joinpartner();				
						$this->session->set_flashdata('success'," New Partners Has Been Added");
						redirect('partners/success','refresh');						
					}
				
			}
		
  }

  function success(){
    $data['page_heading'] = 'Partners-Success';
    $this->load->view('channel/header',$data);
	$this->load->view('channel/thankyou',$data);
	$this->load->view('channel/footer',$data);	
  }
  function check_mail(){
  	$mail=$_REQUEST['mail'];
  	echo $this->partner_model->check_partner($mail);
  }



}//class ends
?>
