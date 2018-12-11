 <?php
ini_set('memory_limit', '-1');
ini_set('display_errors', '1');
defined('BASEPATH') OR exit('No direct script access allowed');

class sendemail extends Front_Controller
{	

	public function __construct()
	{
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

	public function sendmailreservation($ReservationsID=0,$sendguest=1)
	{

			$resinfo=$this->reservation_model->addtaxesprice($this->db->query("select *,STR_TO_DATE(start_date ,'%d/%m/%Y') CHECKIN,RoomNumber,STR_TO_DATE(end_date,'%d/%m/%Y')  CHECKOUT from manage_reservation where reservation_id in ($ReservationsID) ")->result_array());
			$hotelinfo=$this->db->query("select * from manage_hotel where hotel_id =".$resinfo[0]['hotel_id'])->row_array();
			$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
			$variable['###TOTAL###']=0;
			$variable['###ROOMNUMBER###']='';
			$variable['###RESERVATIONNUMBER###']='';

			foreach ($resinfo as  $value) {
				$variable['###ROOMNUMBER###'].=(strlen($variable['###ROOMNUMBER###'])>1?',':'').$value['RoomNumber'];
				$variable['###RESERVATIONNUMBER###']=(strlen($variable['###RESERVATIONNUMBER###'])>1?',':'').$value['reservation_code'];
				$variable['###TOTAL###']+=$value['price'];
			}
			
			$TEMPLATEH    =  $this->db->query("select * from TemplateHotel where TemplateTypeId=2 and HotelId=".$resinfo[0]['hotel_id'])->row_array();

            $TEMPLATEG    =  $this->db->query("select * from TemplateHotel where TemplateTypeId=1 and HotelId=".$resinfo[0]['hotel_id'])->row_array();

            $variable['###CHECKIN###']=$resinfo[0]['CHECKIN'];
            $variable['###CHECKOUT###']=$resinfo[0]['CHECKOUT'];;
            $variable['###FIRSTNAME###']=$resinfo[0]['guest_name'];;
            $variable['###LASTNAME###']=$resinfo[0]['last_name'];;
            $variable['###ALLGUESTNAME###']=$resinfo[0]['guestname'];;
            $variable['###HOTELNAME###']=$hotelinfo['property_name'];
            $variable['###NUMBERROOM###']=count($resinfo);
            $variable['###HOTELLOGO###']='<img style="width:100px; " src="'.base_url().(strlen($hotelinfo['Logo'])<5?"uploads/room_photos/noimage.jpg":$hotelinfo['Logo']).'" class="img-responsive" alt="Logo Hotel">';
            $variable['###LINKRESERVATION###']='';//numeros id y hotel encriptado
            
			$GuestEmail=$resinfo[0]['email'];
			$HotelEmail=$hotelinfo['email_address'];

			if($sendguest==1 && count($TEMPLATEG)>0 && strlen($GuestEmail)>5 )
			{
				$GuestMessage=strtr($TEMPLATEG['Message'],$variable);
				$GuestSubject=strtr($TEMPLATEG['Subject'],$variable);
				$this->mailsettings();
				$this->email->from($admin_detail->email_id);
	            $this->email->to($GuestEmail);
	            $this->email->subject($GuestSubject);
	            $this->email->message($GuestMessage);
	            $this->email->send();
			}
			
			if(count($TEMPLATEH)>0 && strlen($HotelEmail)>5 )
			{
				$HotelMessage=strtr($TEMPLATEH['Message'],$variable);
				$HotelSubject=strtr($TEMPLATEH['Subject'],$variable);
				$this->mailsettings();
				$this->email->from($admin_detail->email_id);
	            $this->email->to($HotelEmail);
	            $this->email->subject($HotelSubject);
	            $this->email->message($HotelMessage);
	            $this->email->send();
			}
			

			
           

			




	}
}
