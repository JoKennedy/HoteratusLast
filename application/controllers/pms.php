<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class pms extends CI_Controller {
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
		$this->load->model('partner_model');
		$this->load->helper('ckeditor');
		$this->load->model('api_model');
		$this->load->library('Passwordhash');
        // Your own constructor code
   }

   function index()
   {
     	$this->load->view('channel/pmsdocument');
	
   }
 function integration()
   {

      $data['integration']=$this->db->get('integration_cms')->result();
      $data['page_heading'] = 'PMS Integration';
      $this->load->view('channel/pmsintegration',$data);  
  
   }
   /*
 function CreateLogin(){

     $dataPOST = trim(file_get_contents('php://input'));
     $xmlData = simplexml_load_string($dataPOST);
    
     $auth=$xmlData->Auth;
     $apikey=$auth->ApiKey;
     $apiemail=$auth->Apiemail;
$data=array();
     try{
        if(isset($auth)){
         $apiemail=$auth->Apiemail;
         //check partner
        $check=$this->api_model->check_partner($apikey,$apiemail);
      
         if($check=="no"){
           throw new Exception("Authendication failed");
         }else{
             $CreateLogin=$xmlData->CreateLogin;
             if($CreateLogin->Yourname=="" || $CreateLogin->PropertyName=="" || $CreateLogin->Phone=="" || $CreateLogin->Username=="" || $CreateLogin->Password=="")
             {
               throw new Exception("Field missing");
             }
             else{
              $t_hasher = new PasswordHash(8, FALSE);
              $pass = $t_hasher->HashPassword($CreateLogin->Password);
               $data1=array(
                'fname'=>(string)$CreateLogin->Yourname, 
                'lname'=>(string)$CreateLogin->Lastname,
                'property_name'=>(string)$CreateLogin->PropertyName,
                'mobile'=>(string)$CreateLogin->Phone,
                'web_site'=>(string)$CreateLogin->Website,
                'email_address'=>(string)$CreateLogin->Email, 
                'user_name'=>(string)$CreateLogin->Username,
                'password'=>(string)$pass
                );
             
              $check=$this->api_model->insert_user($data1);
              $data['success']="ok";

           }
 
         }

       }

        }
     catch (Exception $e) {
        //alert the user then kill the process
       $data['failure']=$e->getMessage();

    }

 echo json_encode($data);
     
   

   }*/
    function CreateLogin(){
     $dataPOST = trim(file_get_contents('php://input'));
     $xmlData = simplexml_load_string($dataPOST);
     $auth=$xmlData->Auth;
     $apikey=$auth->ApiKey;
     $ApiEmail=$auth->ApiEmail;
     $data=array();
     try{
        if(isset($auth)){
         $ApiEmail=$auth->ApiEmail;
         //check partner
        $check=$this->api_model->check_partner($apikey,$ApiEmail);
      
         if($check=="no"){
           throw new Exception("Authendication failed");
         }else{
             $ApiEmail=$auth->ApiEmail;
             $CreateLogin=$xmlData->CreateLogin;
             if($CreateLogin->Yourname=="" || $CreateLogin->PropertyName=="" || $CreateLogin->Phone=="" || $CreateLogin->Username=="" || $CreateLogin->Password=="")
             {
               throw new Exception("Field missing");
             }
             else{
              $t_hasher = new PasswordHash(8, FALSE);
              $pass = $t_hasher->HashPassword($CreateLogin->Password);
               $data1=array(
                'fname'=>(string)$CreateLogin->Yourname, 
                'lname'=>(string)$CreateLogin->Lastname,
                'property_name'=>(string)$CreateLogin->PropertyName,
                'mobile'=>(string)$CreateLogin->Phone,
                'web_site'=>(string)$CreateLogin->Website,
                'email_address'=>$ApiEmail, 
                'user_name'=>(string)$CreateLogin->Username,
                'password'=>(string)$pass,
                'acc_active'=>1
                );
              $check=$this->api_model->insert_user($data1);
              if($check=="no"){
              $data['failure']="Email id already exist";
              }else{
              $data['success']="ok";
              }
           }
         }
       }
      }
     catch (Exception $e) {
       $data['failure']=$e->getMessage();
     }
     echo json_encode($data);
   }
   function getProperties(){
   
     $dataPOST = trim(file_get_contents('php://input'));
     $xmlData = simplexml_load_string($dataPOST);
     $apikey=$xmlData->ApiKey;
     $ApiEmail=(string)$xmlData->ApiEmail;
     $Property=$xmlData->Property;
    $data=array();
     try{
        //if(isset($apikey)){
         //check partner
        $check=$this->api_model->check_partner($apikey,$ApiEmail);
         if($check=="no"){
           throw new Exception("Authendication failed");
         }else{
            //get hotelier id
            $user_id=$this->api_model->get_userid($ApiEmail);
            // get properties
            if($Property=="All"){
              $property=$this->api_model->getproperty($user_id);
            }else{
             $property=$this->api_model->getproperty($user_id,$Property); 
            }
            $data['success']="ok";
            $data['property']=$property;
           }
       //}
      }
     catch (Exception $e) {
       $data['failure']=$e->getMessage();
     }
 echo json_encode($data);
   }

	
}//class ends
?>
