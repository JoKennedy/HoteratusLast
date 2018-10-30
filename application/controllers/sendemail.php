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

	public function sendmailreservation($ReservationsID=0)
	{

			$reservationsinfo=$this->db->query("select * from manage_reservation where reservation_id in ($ReservationsID) ")->result_array();
			$hotelinformation=$this->db->query("select * from manage_hotel where hotel_id =".hotel_id())->row_array();
			$roomnumber=count($reservationsinfo);
			$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();
			$hotelinfo    =   get_mail_template('12');
            $guestinfo    =   get_mail_template('11');
            $forgest='';

			$html='<center><span style="font-size:28px;">Room Qty: '.$roomnumber.'</span></center>
			<div class="graph">
			<div class="table-responsive">
			<div class="clearfix"></div>
			<table class="table table-bordered">
			<tbody>
			';
			
			$i=0;
			$pricing=0;
			$number="";
			foreach ($reservationsinfo as  $reserva) {
				$i++;
				$html.='<tr><td style="font-size:20px;">Info Room #'.$i.' </td></tr>';
				$html.="<tr><td>Confirmation number:".$reserva['reservation_code']." </td></tr>";
				$html.="<tr><td>Guest Name:".$reserva['guest_name']." ".$reserva['last_name']." </td></tr>";
				$html.="<tr><td>Check-In Date:".$reserva['start_date']." </td></tr>";
				$html.="<tr><td>Check-Out Date:".$reserva['end_date']." </td></tr>";
				$html.="<tr><td>No.of Nights:".$reserva['num_nights']." </td></tr>";
				$html.="<tr><td>Arrival Time:".$reserva['arrivaltime']." </td></tr>";
				$html.="<tr><td>Adult Count:".$reserva['members_count']." </td></tr>";
				$html.="<tr><td>Child Count:".$reserva['children']." </td></tr>";
				$html.="<tr><td> Order Total:".$reserva['price']." </td></tr>";
				$html.="<tr><td> </td></tr>";
				$pricing+=$reserva['price'];
				$number.=(strlen($number)>1?',':'').$reserva['reservation_code'];

			}
			$html.='
			<tbody>
			</table>
			<center><h1><span class="label label-primary">Total:'.$pricing.'</span></h1></center>
			';
			$forgest['###USERNAME###']=$reservationsinfo[0]['guest_name'].' '.$reservationsinfo[0]['last_name'];
			$forgest['###COMPANYLOGO###']=base_url().'uploads/logo/'.$admin_detail->site_logo;
			$forgest['###SITENAME###']=$admin_detail->company_name;
			$forgest['###STATUS###']='Reserved';
			$forgest['###PROPERTYUSER###']=$hotelinformation['property_name'];
			$forgest['###CONFIRMRESERVATION###']=$html;
			$forgest['###SITELINK###']=base_url();
			$forgest['###RESERLINK###']='#';
			$email=$reservationsinfo[0]['email'];

			$guestmessage=strtr($guestinfo['message'],$forgest);

			

			$subject1 = array(
                    '###SITENAME###'=>$admin_detail->company_name,
                    '{RESERVATIONCODE}'=>$number,
                );

			$guestsubject = strtr($guestinfo['subject'],$subject1);

			$this->mailsettings();
			$this->email->from($admin_detail->email_id);

            $this->email->to($email);

            $this->email->subject($guestsubject);

            $this->email->message($guestmessage);

            $this->email->send();
           

			




	}
}
