<?php 
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class partner_model extends CI_Model
{
   
	function mailsettings()
	{ 	  
		$this->load->library('email');  
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);	 	    
	} 
	public function check_partner($email)
	{
        $this->db->where('email',$email);
        $sql=$this->db->get('partners');
        if($sql->num_rows > 0){
        	return "exist";
        }
     
	}

	public function joinpartner()
	{
		$data = array(
									'country'=>$this->input->post('country'),
									'company'=>$this->input->post('company'),
									'website'=>$this->input->post('website'),
									'firstname'=>$this->input->post('contact'),
									'lastname'=>$this->input->post('lastname'),
									'email'=>$this->input->post('email'),
									'phone'=>$this->input->post('phone'),
									'comments' => $this->input->post('comments'),
								);
		$res = $this->db->insert('partners',$data);
		/*$this->db->where('id','16');  			
		$queryemail_temp=$this->db->get('Email_Templates'); 
		if($queryemail_temp->num_rows()==1)  
		{ 
			$get_emailtemp=$queryemail_temp->row(); 
			$msg=$get_emailtemp->message; 
			$subject=$get_emailtemp->subject; 
			$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
			$mail_content = array(
														'###COMPANYLOGO###'=>base_url().'uploads/logo/'.$admin_detail->site_logo,
														'###SITENAME###'=>$admin_detail->company_name,
														'###STATUS###'=>'Active',
														'###USERNAME###'=>$this->input->post('email')
													);
			$content=strtr($msg,$mail_content);  				  
			$msg=$content;
			$this->mailsettings();
			$this->email->from($admin_detail->email_id,$admin_detail->company_name);
			$this->email->to($this->input->post('email'));
			$this->email->subject($subject);
			$this->email->message($msg);
			$this->email->send();
		}*/
		return true;
	}

	
	
	
	
	
		
}  // end class

