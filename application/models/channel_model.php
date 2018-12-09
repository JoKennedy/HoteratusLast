<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class channel_model extends CI_Model
{
	public function check_login()
	{
		$this->db->where('email_address',$this->security->xss_clean($this->input->post('login_email')));
		$this->db->or_where('user_name',$this->security->xss_clean($this->input->post('login_email')));
		//$this->db->where('user_password',$this->input->post('login_pwd'));
		$res = $this->db->get(TBL_USERS);


		if($res->num_rows()>0)
		{
			$result = $res->row();
			$t_hasher = new PasswordHash(8, FALSE);
			$hash = $result->password;
			$check = $t_hasher->CheckPassword($this->security->xss_clean($this->input->post('login_pwd')), $hash);


			if($check)
			{
				if($result->attempt_cnt<=6 && $result->pw_ck=='0' || $result->pw_ck=='2')
					{
						if($result->pw_ck=='0'){
					 	$pdata = array('pw_ck'=>'1');
					 	$Updatess = update_data(TBL_USERS,$pdata,array('user_id'=>$result->user_id));
					   }

						$Adata = array('attempt_cnt'=>'0');
						$Update_attempt = update_data(TBL_USERS,$Adata,array('user_id'=>$result->user_id));

					if($result->status==0 && $result->acc_active==0)
					{  // inactive status..
						$this->session->set_userdata('ch_user_mail',$result->email_address);
						return 3;
					}
					else if($result->status==0 || $result->acc_active==0)
					{  // inactive status..
						$this->session->set_userdata('ch_user_mail','deactive');
						return 2;
					}
					else
					{
						if($result->User_Type=='1')
						{
							$this->session->set_userdata('ch_user_id',$result->user_id);
							$this->session->set_userdata('ch_user_type',$result->User_Type);
							$hotel_id = get_data(HOTEL,array('owner_id'=>$result->user_id))->row()->hotel_id;
							$this->session->set_userdata('ch_hotel_id',$hotel_id);

							$language = ($result->language != "") ? $result->language : "english";
        					$this->session->set_userdata('site_lang', $language);

							if($result->pw_ck=='2')
							{
							   return 1;
							}
							/*elseif($result->pw_ck=='1')
							{	*/
								return 12;
							/*}*/
						}
						elseif($result->User_Type=='2')
						{
							$assingn_hotel = $this->db->query("select * from assignedhotels where userid=".$result->user_id)->row_array();
							// print_r($assingn_hotel);die;
							$hotel_id = $this->db->query("select * from manage_hotel where hotel_id in (".$assingn_hotel['hotelids'].") limit 1")->row()->hotel_id;
							$language = ($result->language != "") ? $result->language : "english";
        					$this->session->set_userdata('site_lang', $language);
							$this->session->set_userdata('ch_user_id',$result->user_id);
							$this->session->set_userdata('owner_id',$result->owner_id);
							$this->session->set_userdata('ch_user_type',$result->User_Type);
							$this->session->set_userdata('ch_hotel_id',$hotel_id);
							$this->session->set_userdata('specialpermitid',$assingn_hotel['specialpermitids']);
							if($result->pw_ck=='2')
							{
							   return 1;
							}

							return 12;
						}
					}
				}
				else
				{
					return 10;
				}
		  }
			else
			{
			$attempt_cnt = $result->attempt_cnt;
			if($attempt_cnt!='')
			{
				if($attempt_cnt<=5)
				{
					$Cnt = $attempt_cnt+1;
			    }
			    else
			    {
			    	$Cnt = $result->attempt_cnt;
			    }
			}
			else
			{
				$Cnt = 1;
			}

	     	$login_cnt = $result->attempt_cnt;

			if($login_cnt<=6)
			{
				if($result->pw_ck=='0')
				{
			 		$pdata = array('pw_ck'=>'1');
			 		$Updatess = update_data(TBL_USERS,$pdata,array('user_id'=>$result->user_id));
			    }

				$Udate = array('attempt_cnt'=>$Cnt);
				$update_user = update_data(TBL_USERS,$Udate,array('user_id'=>$result->user_id));
				if($login_cnt==6)
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

						$old_password = $result->user_password;

						$org_pass = json_decode($old_password,true);

						$new_count = count($org_pass);

						if($new_count>=4)
						{
							$new = array($hash=>"1");

							$passs = array_shift($org_pass);

							$insert_pass = array_merge($org_pass, $new);

							$encode_password = json_encode($insert_pass);
						}
						else
						{
							$new = array($hash=>"1");

							$insert_pass = array_merge($org_pass, $new);

							$encode_password = json_encode($insert_pass);
						}

						$updata['password']=$hash;

						$updata['user_password'] = $encode_password;


						/*echo '<pre>';
						print_r($updata);die;*/

						if(update_data(TBL_USERS,$updata,array('user_id'=>$result->user_id)))
						{
						$user_info = get_data(TBL_USERS,array('user_id'=>$result->user_id))->row();

						$admin_detail = get_data(TBL_SITE,array('id'=>1))->row();

						$get_email_info		=	get_mail_template('27');

						/*echo '<pre>';
						print_r($get_email_info);die;*/

						$subject		=	$get_email_info['subject'];
						$template		=	$get_email_info['message'];

						//echo $admin_detail->email_id.'  '.$user_info->email_address;die;

						$this->mailsettings();
						$this->email->from($admin_detail->email_id);
						$this->email->to($user_info->email_address);
						$data = array(
						'###NAME###'=>$result->fname.' '.$result->lname,
						'###password###'=>$password,
						'###SITENAME###'=>$admin_detail->company_name,
						);

						$subject_data = array(
						'###SITENAME###'=>$admin_detail->company_name,
						);
						$subject_new = strtr($subject,$subject_data);

						$this->email->subject($subject_new);

						$content_pop = strtr($template,$data);
						//echo $content_pop; die;
						$this->email->message($content_pop);

						if($this->email->send())
						{
						//send_notification($admin_detail->email_id,$this->input->post('email_address'),$content_pop,$subject_new);
						}
						 return 8;
						}
						else
						{
						 return false;
						}

				}
			}
			//	return 0;
			}

		}
		else
		{

			return 0;  // no account..
		}
	}
	function changestatus($id)
	{
		$this->db->query("update user_connect_channel set status = case when status='enabled' then 'disabled' else 'enabled' end where user_connect_id=$id ");
		$status=$this->db->query("select status from user_connect_channel where user_connect_id=$id ")->row_array()['status'];
		return  array('success' =>true,'status'=>$status );
	}

	function propertynameused($propertyname)
	{
		$result=$this->db->query("select * from manage_hotel where property_name='$propertyname'")->result_array();

		if (count($result)>0) {
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function emailused($email)
	{
		$result=$this->db->query("select * from manage_users where email_address='$email'")->result_array();

		if (count($result)>0) {
			return '1';
		}
		else
		{
			return '0';
		}
	}
	function usernameused($username)
	{
		$result=$this->db->query("select * from manage_users where user_name='$username'")->result_array();

		if (count($result)>0) {
			return '1';
		}
		else
		{
			return '0';
		}
	}
	function changerPassword($oldpass,$newpass)
	{
			$user_id = user_id();
			$hasherpass = new PasswordHash(8, FALSE);

			$user_emal = get_data('manage_users',array('user_id'=>$user_id))->row_array();

			$check = $hasherpass->CheckPassword($oldpass, $user_emal['password']);


			if ($check) {

				$hash = $hasherpass->HashPassword($newpass);
				$cdata['password']=$hash;
				update_data('manage_users',$cdata,array('user_id'=>$user_id));

				return '0';
			}
			else
			{
				return '2';
			}
	}
	function savenewuserassg($infouser)
	{
		$hasherpass = new PasswordHash(8, FALSE);
		$data['User_Type']=2;
		$data['owner_id']=user_id();
		$data['multiproperty']='Deactive';
		$data['user_name']=$infouser['username'];
		$data['fname']=$infouser['fname'];
		$data['lname']=$infouser['lname'];
		$data['email_address']=$infouser['email'];
		$data['password']=$hasherpass->HashPassword($infouser['password']);
		$data['ipaddress']=$_SERVER['REMOTE_ADDR'];
		$data['user_agent']= $_SERVER['HTTP_USER_AGENT'];
		$data['status']=1;
		$data['acc_active']=1;
		$data['pw_ck']=2;

		if(insert_data('manage_users',$data))
		{
			$id=getinsert_id();

			$data2['userid']=$id;
			$data2['hotelids']=implode(",", $infouser['hotelid']);
			$data2['menuitemids']=implode(",", $infouser['menuitemid']);
			$data2['specialpermitids']='';

			insert_data('assignedhotels',$data2);

			return true;

		}
		return false;

		# User_Type, owner_id, access, multiproperty, user_name, fname, lname, password, spass, mobile, town, address, zip_code, property_name, connected_channel, web_site, email_address, country, currency, tax_office, tax_id, transaction_id, plan_id, plan_price, plan_from, plan_to, user_password, payment_method, subscribe_status, status, acc_active, created_date, channel_subscribe_txnid, channel_subscribe_planid, channel_subscribe_price, channel_subscribe_from, channel_subscribe_to, channel_subscribe_method, channel_subscribe_status, ipaddress, user_agent, attempt_cnt, pw_ck
	}
	function updatenewuserassg($infouser)
	{
		$hasherpass = new PasswordHash(8, FALSE);
		$data['fname']=$infouser['fnameup'];
		$data['lname']=$infouser['lnameup'];
		$data['email_address']=$infouser['emailup'];
		if(strlen($infouser['passwordup'])>5)
		{
			$data['password']=$hasherpass->HashPassword($infouser['passwordup']);
		}




		if(update_data('manage_users',$data,array('user_id'=>$infouser['useridup'])))
		{

			$data2['hotelids']=implode(",", $infouser['hotelidup']);
			$data2['menuitemids']=implode(",", $infouser['menuitemidup']);
			$data2['specialpermitids']='';
			$exist=$this->db->query("select * from assignedhotels where userid=".$infouser['useridup'])->result_array();
			if(count($exist)>0)
			{
				update_data('assignedhotels',$data2,array('userid'=>$infouser['useridup']));
			}
			else
			{
				$data2['userid']=$infouser['useridup'];
				insert_data('assignedhotels',$data2);
			}

			return true;

		}
		return false;
	}
	function saveBillingDetails($billd)
	{
		$hotelid=hotel_id();
		$exist=get_data('bill_info',array('hotel_id'=>$hotelid))->result_array();
		$data['company_name']=$billd['cname'];
		$data['town']=$billd['city'];
		$data['address']=$billd['address'];
		$data['zip_code']=$billd['zipcode'];
		$data['mobile']=$billd['phone'];
		$data['vat']=$billd['vat'];
		$data['reg_num']=$billd['rnumber'];
		$data['email_address']=$billd['bemail'];
		$data['country']=$billd['country'];

		$result['success']=false;
		$result['message']='';
		if(strlen($_FILES["Image"]['name'])>5)
		{
			 $file = $_FILES["Image"];
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Billing/";


		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		    	$result['message']='Error, el archivo no es una imagen';
		    }
		    else if ($size > 1024*1024)
		    {
		    	$result['message']='Error, el tamaño máximo permitido es un 1MB';

		    }
		    else if ($width > 500 || $height > 500)
		    {
		    	$result['message']='Error la anchura y la altura maxima permitida es 500px';
		    }
		    else if($width < 60 || $height < 60)
		    {
		    	$result['message']='Error la anchura y la altura mínima permitida es 60px';
		    }
		    else

		    {

		       	$src = $carpeta.hotel_id().$nombre;
			    move_uploaded_file($ruta_provisional, $src);

			    $data['Logo']=$src;
		    }

		}

		if (count($exist)>0) {

			update_data('bill_info',$data,array('hotel_id'=>$hotelid));
			$result['success']=true;
			$result['value']=2;
		}
		else
		{	$data['hotel_id']=$hotelid;
			$data['user_id']=user_id();
			insert_data('bill_info',$data);
			$result['success']=true;
			$result['value']=1;
		}

		return $result;
	}
	function savePaymentMethod()
	{
		$data['providerid']=$_POST['providerid'];
		$data['email']=$_POST['email'];
		$data['apikey']=$_POST['apikey'];
		$data['merchantid']=$_POST['merchantid'];
		$data['publickey']=$_POST['publickey'];
		$data['active']=1;
		$data['hotelid']=hotel_id();
		if (insert_data('paymentmethod',$data)) {
			return true;
		}
		else
		{
			return false;
		}
	}
	function updatePaymentMethod()
	{

		$data['email']=$_POST['emailup'];
		$data['apikey']=$_POST['apikeyup'];
		$data['merchantid']=$_POST['merchantidup'];
		$data['publickey']=$_POST['publickeyup'];
		$data['active']=(isset($_POST['activeup'])?1:0);

		if (update_data('paymentmethod',$data,array("paymentmethodid"=>$_POST['paymentmethodidup']))) {
			return true;
		}
		else
		{
			return false;
		}
	}
	function saveTax()
	{
		$data['name']=$_POST['name'];
		$data['hotelid']=hotel_id();
		$data['taxrate']=$_POST['rate'];
		$data['includedprice']=(isset($_POST['included'])?1:0);
		$data['active']=1;
		if (insert_data('taxcategories',$data)) {
			return true;
		}
		else
		{
			return false;
		}
	}
	function updateTax()
	{
		$data['name']=$_POST['nameup'];
		$data['taxrate']=$_POST['rateup'];
		$data['includedprice']=(isset($_POST['includedup'])?1:0);
		if (update_data('taxcategories',$data,array("taxid"=>$_POST['taxid']))) {
			return true;
		}
		else
		{
			return false;
		}
	}
	function savePolicyType()
	{
		$data['name']=$_POST['nametype'];
		$data['hotelid']=hotel_id();
		$data['active']=1;
		if (insert_data('policytype',$data)) {
			return true;
		}
		else
		{
			return false;
		}
	}
	function savePolicy()
	{

		$data['hotelid']=hotel_id();
		$data['description']=$_POST['policydescription'];
		$data['Name']=$_POST['policyname'];
		$data['daysbefore']=$_POST['daysbefore'];
		$data['feetype']=$_POST['feetype'];
		$data['amount']=$_POST['amount'];
		$data['policytype']=$_POST['policytypeid'];
		$data['active']=1;
		if (insert_data('policies',$data)) {
			return true;
		}
		else
		{
			return false;
		}

		# policyid, hotelid, description, Name, daysbefore, feetype, amount, policytype, active

	}
	function propertyinfoupdate($propertyinfo)
	{
		$localidad=str_replace(array('(',')'), '', $propertyinfo['localidad']);

		$data =array("map_location"=>$localidad,"property_name"=>$propertyinfo['propertyname'],"address"=>$propertyinfo['address'],"email_address"=>$propertyinfo['email'],"web_site"=>$propertyinfo['website'],"currency"=>$propertyinfo['currencyid'],"country"=>$propertyinfo['countryid'],"mobile"=>$propertyinfo['Phone'],"zip_code"=>$propertyinfo['zipcode'],"town"=>$propertyinfo['town']);
		if (isset($_FILES["Image"]["name"]) && strlen($_FILES["Image"]["name"])>5)
		{

		    $file = $_FILES["Image"];



		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Users/";


		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {

		      $result["message"]= "Error, el archivo no es una imagen";
		      $result['success']=false;
		    }
		    else

		    {

		        $src = $carpeta.'user'.hotel_id().user_id().$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['Logo']="/".$src;



		    }

		}

		if( update_data('manage_hotel',$data,array('hotel_id' =>hotel_id())))
		{
			echo "0";
		}
		else
		{
			echo "1";
		}
	}
	function savepropertyinfo($propertyinfo)
	{
		$localidad=str_replace(array('(',')'), '', $propertyinfo['localidad']);

		$data =array("map_location"=>$localidad,"property_name"=>$propertyinfo['propertyname'],"address"=>$propertyinfo['address'],"email_address"=>$propertyinfo['email'],"web_site"=>$propertyinfo['website'],"currency"=>$propertyinfo['currencyid'],"country"=>$propertyinfo['countryid'],"mobile"=>$propertyinfo['Phone'],"zip_code"=>$propertyinfo['zipcode'],"town"=>$propertyinfo['town'],"owner_id"=>(user_type()=='1'?user_id():owner_id()));

		# hotel_id, owner_id, assigned_user, hotel_type, fname, lname, mobile, town, address, zip_code, property_name, connected_channel, web_site, email_address, country, currency, tax_office, map_location, tax_id, transaction_id, plan_id, plan_price, plan_from, plan_to, payment_method, subscribe_status, status, created_date, channel_subscribe_txnid, channel_subscribe_planid, channel_subscribe_price, channel_subscribe_from, channel_subscribe_to, channel_subscribe_method, channel_subscribe_status, Logo

		$result['success']=false;
		if( insert_data(HOTEL,$data))
		{
			$result['success']=true;
			$result['id']=getinsert_id();
		}

		echo json_encode($result);
	}
	function user_channel()
	{
		if(user_type()=='1')
		{
			$query = $this->db->query('SELECT GROUP_CONCAT('.TBL_PROPERTY.'.connected_channel) AS connected_channel FROM '.TBL_PROPERTY.' where owner_id='.user_id().'');
		}
		else if(user_type()=='2')
		{
			$query = $this->db->query('SELECT GROUP_CONCAT('.TBL_PROPERTY.'.connected_channel) AS connected_channel FROM '.TBL_PROPERTY.' where owner_id='.owner_id().'');
		}
		else if(admin_id()!='' && admin_type()=='1')
		{
			$query = $this->db->query('SELECT GROUP_CONCAT('.TBL_PROPERTY.'.connected_channel) AS connected_channel FROM '.TBL_PROPERTY.' where owner_id='.user_id().'');
		}
		$row = $query->row();
		$getvl=$row->connected_channel;
        $exp_hids=explode(",",$getvl);
		$connected_channel = array_unique($exp_hids);
		$this->db->select('*');
		$this->db->where_in('channel_id',$connected_channel);
		$channel = $this->db->get(TBL_CHANNEL);
		return $channel->result_array();

	}
	function allChannelList(){
		$hotel_id=hotel_id();


		$result = $this->db->query("SELECT case status when 'Active' then 1 when 'new' then 2 when 'Process' then 3 else 0 end status, a.channel_name,image,
			(select count(*) from user_connect_channel where hotel_id= $hotel_id and channel_id=a.channel_id ) conect, channel_id
			FROM manage_channel a  order by channel_name")->result_array();

			return $result;
	}


	public function ReservationShow($roomnumber, $date1, $roomtypeid, $availability, $price, $room_update_id)
	{
		$hotel_id=hotel_id();
		$repuesta='';
		$contador=0;
                
                for ($i=0; $i <=30; $i++) {
                                            
			$fecha=date('Y-m-d',strtotime($date1."+$i days"));

			$path="uploads/logo/small/475551.png";
                        $result =$this->db->query("SELECT datediff(STR_TO_DATE(end_date ,'%d/%m/%Y'),STR_TO_DATE(start_date ,'%d/%m/%Y')) noche,RoomNumber, 0 channelid,reservation_id, STR_TO_DATE(start_date ,'%d/%m/%Y') date1, STR_TO_DATE(end_date ,'%d/%m/%Y') date2 ,status FROM `manage_reservation` WHERE  '$fecha' between STR_TO_DATE(start_date ,'%d/%m/%Y') and DATE_ADD(STR_TO_DATE(end_date ,'%d/%m/%Y'), INTERVAL -1 DAY)
			and hotel_id=$hotel_id and RoomNumber='$roomnumber' and room_id=$roomtypeid and status <> 'Canceled' and status <> 'No Show' ")->row_array();
                        
                        
                        
			if(!isset($result['noche']))
			{	$path="uploads/small/channels_booking.gif";
				$result=$this->db->query("SELECT datediff(a.departure_date,a.arrival_date) noche, a.arrival_date date1 , a.departure_date date2, a.RoomNumber, 2 channelid, a.room_res_id reservation_id, a.status
					from import_reservation_BOOKING_ROOMS a
					left join import_reservation_BOOKING b on a.import_reserv_id=b.import_reserv_id
					left join import_mapping_BOOKING c on a.id= c.B_room_id and a.rate_id = c.B_rate_id
					left join roommapping d on c.import_mapping_id=d.import_mapping_id and d.channel_id=2
					left join manage_property e on d.property_id = e.property_id
					WHERE  '$fecha' between a.arrival_date and
					DATE_ADD(a.departure_date, INTERVAL -1 DAY)
					and a.hotel_hotel_id=$hotel_id and a.RoomNumber='$roomnumber' and e.property_id=$roomtypeid and a.status <>'cancelled'")->row_array();
			}
			if(!isset($result['noche']))
			{	$path="uploads/small/76728.png";
				$result=$this->db->query("SELECT datediff(a.departure,a.arrival) noche, STR_TO_DATE(a.arrival ,'%Y-%m-%d') date1 , STR_TO_DATE(a.departure ,'%Y-%m-%d') date2, a.RoomNumber, 1 channelid, a.import_reserv_id reservation_id, a.type
				from import_reservation_EXPEDIA a
				left join import_mapping c on a.roomTypeID= c.roomtype_id and a.ratePlanID = c.rate_type_id
				left join roommapping d on c.map_id=d.import_mapping_id and d.channel_id=1
				left join manage_property e on d.property_id = e.property_id
				WHERE  '$fecha' between STR_TO_DATE(a.arrival ,'%Y-%m-%d')  and
				DATE_ADD(STR_TO_DATE(a.departure ,'%Y-%m-%d') ,INTERVAL -1 DAY)
				and a.hotel_id=$hotel_id and a.RoomNumber='$roomnumber' and e.property_id=$roomtypeid  and a.Type <>'Cancel'")->row_array();
			
			}

			$start_date = $result['date1'];
            $end_date = $result['date2'];

            if (isset($result['noche'])){

				if(strtotime($result['date1'])<date('Y-m-d'))
				{
					$result['date1']=date('Y-m-d');
				}
				$result['noche']= ceil(abs(strtotime($result['date2']) - strtotime($result['date1'])) / 86400);

				$color=($result['status']=='Checkin'?'#096fbf':($result['status']=='Checkout'?'#FF5733':'#52c748') )  ;

				if ($contador>0) {
					$repuesta .= '<td bgcolor="#E5E7E9"> </td>'; //COLSPAN="'.$contador.'" 
					$contador=0;
				}
                               $fecha2 = $fecha; 
                               for ($a = 0; $a < $result['noche']; $a++) {
                                   
                                   $vAvailability = $availability[($i+$a)];
                                   $vPrice = $price[($i + $a)];
                                   $vRoomUpdateId = $room_update_id[($i + $a)];

                                   if($a == 0 || $result['noche'] == ($a+1)){
                                       $end = ($result['noche'] == ($a+1)) ? "end" : "start";
                                        $repuesta .= '<td id="drag'. str_replace("-", "_", $fecha2).$roomnumber.'" draggable="true" ondragstart="drag(event)" data-type="'.$end.'" data-room_id="'.$roomtypeid.'" data-roomnumber="'.$roomnumber.'" data-date="'.$fecha2.'" data-availability="'.$vAvailability.'" data-price="'.$vPrice.'" data-room_update_id="'.$vRoomUpdateId.'" data-start_date="'.$start_date.'" data-end_date="'.$end_date.'" data-reservation="'.$result['reservation_id'].'" onclick="gotoreser('."'".site_url('reservation/reservationdetails/'.secure($result['channelid']).'/'.insep_encode($result['reservation_id']))."'".')" bgcolor="'.$color.'"> <div data-reservation="'.$result['reservation_id'].'" style="width: 100%; height: 20px; cursor: pointer; text-align: center;"> <i class="fas fa-arrows-alt" style="color: white;margin-top: 3px;"></i> </div> </td>';//COLSPAN="'.$result['noche'].'"
                                   }else{
                                        $repuesta .= '<td id="drag'. str_replace("-", "_", $fecha2).$roomnumber.'" data-availability="'.$vAvailability.'" data-price="'.$vPrice.'" data-room_id="'.$roomtypeid.'" data-room_update_id="'.$vRoomUpdateId.'" data-roomnumber="'.$roomnumber.'" data-date="'.$fecha2.'" data-start_date="'.$start_date.'" data-end_date="'.$end_date.'" data-reservation="'.$result['reservation_id'].'" onclick="gotoreser('."'".site_url('reservation/reservationdetails/'.secure($result['channelid']).'/'.insep_encode($result['reservation_id']))."'".')" bgcolor="'.$color.'" style="text-align: center; cursor: pointer; background-image: url(data:image/png;base64,'. base64_encode(file_get_contents($path)).');background-repeat: no-repeat; background-position: center;"> </td>';//COLSPAN="'.$result['noche'].'"
                                        
                                   } 
                                   $fecha2 = date('Y-m-d', strtotime($fecha2."+1 days"));
                               }
                               $i += $result['noche']-1;
			} else {
				$repuesta .= '<td class="room-filed" bgcolor="#E5E7E9" data-reservation="0" data-date="'.$fecha.'" data-availability="'.$availability[$i].'" data-room_id="'.$roomtypeid.'" data-price="'.$price[$i].'" data-room_update_id="'.$room_update_id[$i].'" data-roomnumber="'.$roomnumber.'"> </td>';

			}
		}


		return $repuesta;


	}
	function calendarFull()
	{

		$ss=$_POST['sales'];
		$cta=$_POST['cta'];
		$ctd=$_POST['ctd'];
		$showr=$_POST['show'];
		$hotelid=hotel_id();
		$dataini=(date('m')==$_POST['monthid'] && date('Y')==$_POST['yearid']?date('Y-m-d'):date('Y-m-d',strtotime($_POST['yearid'].'-'.$_POST['monthid'].'-01')));

		if($_POST['opt']==2)
		{
			$test=get_data('ConfigUsers',array('UserID'=>user_id()))->row_array();

			if(count($test)>0)
			{

				update_data('ConfigUsers',array('CalenderShowR'=>$showr),array('ConfigUsersID'=>$test['ConfigUsersID']));

			}
			else
			{
 				insert_data('ConfigUsers',array('CalenderShowR'=>$showr,'hotelID'=>$hotelid,'UserID'=>user_id()));
			}
		}

		$date1=$dataini;
		$date2=date('Y-m-d',strtotime($date1.'+31 days'));
		$fecha = new DateTime();
		$fecha->modify('last day of this month');
		$lastday= $fecha->format('d');



		$hotel_id=hotel_id();


		$html='<table    class="tablanew" border=1 cellspacing=0 cellpadding=2 bordercolor="#B2BABB" > ';
		 $header1='<thead> <tr> <th style="text-align:center; width: auto; "> Room Name </th>';
		 $header2=' <thead> <tr> <th bgcolor="#E5E7E9"> </th> <th bgcolor="#E5E7E9"></th>';

		 $mes= '';
		 for ($i=0; $i <=30 ; $i++) {
		 	$datereal=date('Y-m-d',strtotime($date1."+$i days"));


		 	 if ($mes!=date('Y M',strtotime($date1."+$i days"))) {
		 	 	$mes=date('Y M',strtotime($date1."+$i days"));

		 	 	$dia =date('d',strtotime($date1."+$i days"));

		 	 	$header1 .='<th  COLSPAN="'.($i==0?($lastday-($dia-1))+1:30-($i-1)) .'" style="text-align:center; margin: 15px; padding: 15px;"> '.$mes.' </th>';


		 	 }
		 	 $dias=date('D',strtotime($date1."+$i days"));
		 	 $header2.= '<th bgcolor="'.($dias=='Sat' || $dias=='Sun'?'#FBFCFC':'#E5E7E9').'" style=" font-size:15px; text-align:center;  margin: 10px; padding: 5px;">'.date('d',strtotime($date1."+$i days")).'<br>'.$dias.'</th>';
                }


		 $header1 .=' </tr> </thead> ';
		 $header2 .='	</tr> </thead>';



		$room=$this->db->query("select * from manage_property where hotel_id = $hotel_id order by `order` ")->result_array();

		$body='<tbody ondrop="drop(event)" ondragover="allowDrop(event)">';

                $price = array();
                $availability = array();	
                $room_update_id = array();
                $int = 0;
                foreach ($room as $value) {
                
			$roomid=$value['property_id'];
			$ratetype=$this->db->query("select * from ratetype where roomid = ".$value['property_id']." order by name ")->result_array();

			$precio='<tr> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; " >P</td>';
			$avai='<tr> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">A</td>';
			$min = '<tr> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">M</td>';
			$ctas = '<tr class="cta" style="display:'.($cta==1?'':'none').'; "><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">CTA</td>';
			$ctds = '<tr class="ctd" style="display:'.($ctd==1?'':'none').'; "><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">CTD</td>';
			$sss = '<tr class="ss" style="display:'.($ss==1?'':'none').'; "><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; "> SS</td>';
			$body .='<tr>  <td ROWSPAN="4" style=" margin: 5px; padding:5px;">'.$value['property_name'].(count($ratetype)>0?'<i onclick="showrate(this,'.$value['property_id'].')" class="fa show_plus_221 fa-plus"></i>':'').'</td> </tr> ';
			$room2='';
			//fa show_plus_221 fa-minus
			$roomnumber=explode(",", $value['existing_room_number']);

			if(count($roomnumber)<$value['existing_room_count'])
			{
				$faltan=$value['existing_room_count']-count($roomnumber);

				for ($i=0; $i < $faltan; $i++) {
					array_push($roomnumber, "f$i");
				}
			}


			$dato=null;

			$datos= $this->db->query("select *,STR_TO_DATE(separate_date ,'%d/%m/%Y') as datereal from room_update where hotel_id = $hotel_id  and room_id =".$value['property_id']." and STR_TO_DATE(separate_date ,'%d/%m/%Y') between '$date1' and '$date2' and individual_channel_id=0 order by STR_TO_DATE(separate_date ,'%d/%m/%Y')")->result_array();
                        
                        for ($i=0; $i <= 30; $i++) {
		 		$datereal=date('Y-m-d',strtotime($date1."+$i days"));
		 			$dato=null;
		 			$idfound=array_search(date('Y-m-d',strtotime($datereal)), array_column($datos,'datereal'));
		 			if(!$idfound===false || $idfound ===0)
		 			{
		 				$dato=$datos[$idfound];
		 			}
                                
                                if(isset($dato['price'])){
                                    array_push($price, floatval($dato['price']));
                                }
                                
                                if(isset($dato['availability'])){
                                   array_push($availability, intval($dato['availability']));
                                }
                                
                                if(isset($dato['room_update_id'])){
                                    array_push($room_update_id, $dato['room_update_id']);
                                }
                                                                
                                $Editprices=(isset($dato['price'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); "  href="javascript:;" class="inline_username "  data-type="number" data-name="price" data-pk="'.$datereal.','.$roomid.',0,'.$hotelid.'" " data-title="Change Price">'.floatval($dato['price']).'</a>':'Null');
				$Editava=(isset($dato['availability'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); "  href="javascript:;" class="inline_username "  data-type="number" data-name="availability" data-pk="'.$datereal.','.$roomid.',0,'.$hotelid.'"  data-title="Change Availability">'.intval($dato['availability']).'</a>':'Null');
				$Editminimum=(isset($dato['minimum_stay'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); "  href="javascript:;" class="inline_username "  data-type="number" data-name="minimum_stay" data-pk="'.$datereal.','.$roomid.',0,'.$hotelid.'"  data-title="Change Minimum Stay">'.intval($dato['minimum_stay']).'</a>':'Null');

		 		$precio.='<td style="font-size: 12px; text-align:center;" >'.$Editprices.'</td>';
				$avai.='<td style="font-size: 12px;  text-align:center; background-color: '.(isset($dato['availability'])?($dato['availability']<=0?'#C0392B':'#F8F9F9'):'#C0392B').';" > '.$Editava.' </td>';
				$min.='<td style="font-size: 12px; text-align:center; "> '.$Editminimum.' </td>';
				$ctas.='<td style="font-size: 12px; text-align:center; "> <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.',0,'.$hotelid.'" name ="cta" type="checkbox" '.(isset($dato['cta'])=='1'?($dato['cta']==1?'checked':''):'').' /> </td>';
				$ctds.='<td style="font-size: 12px; text-align:center; "> <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.',0,'.$hotelid.'" name ="ctd" type="checkbox" '.(isset($dato['ctd'])=='1'?($dato['ctd']==1?'checked':''):'').' /> </td>';
				$sss.='<td style="font-size: 12px; text-align:center; " > <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.',0,'.$hotelid.'" name ="stop_sell" type="checkbox" '.(isset($dato['stop_sell'])?($dato['stop_sell']==1?'checked':''):'').' /> </td>';
		 	}

			$precio.='</tr>';
			$avai.='</tr>';
			$min .='</tr>';
			$ctas .='</tr>';
			$ctds .='</tr>';
			$sss .='</tr>';
			$body .=$precio.$avai.$min.$ctas.$ctds.$sss;
                        
                               
                        
			foreach ($ratetype as  $rate) {

				$precio='<tr style="display:none" class="rate'.$rate['roomid'].'"> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; " >P</td>';
				$avai='<tr style="display:none" class="rate'.$rate['roomid'].'"> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">A</td>';
				$min = '<tr style="display:none" class="rate'.$rate['roomid'].'"> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">M</td>';
				$ctas = '<tr class="rate'.$rate['roomid'].'cta" style="display:none"><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">CTA</td>';
				$ctds = '<tr class="rate'.$rate['roomid'].'ctd" style="display:none"><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; ">CTD</td>';
				$sss = '<tr class="rate'.$rate['roomid'].'ss" style="display:none"><td></td><td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; "> SS</td>';
				$body .='<tr style="display:none" class="rate'.$rate['roomid'].'">  <td ROWSPAN="4" style="margin: 5px; padding:5px; color:#5dade2;">'.$rate['name'].'</td> </tr> ';
				$room2='';

				$dato=null;

				$datosr= $this->db->query("select *,STR_TO_DATE(separate_date ,'%d/%m/%Y') as datereal from room_rate_types_base where hotel_id = $hotel_id  and room_id =".$rate['roomid']." and rate_types_id=".$rate['ratetypeid']." and STR_TO_DATE(separate_date ,'%d/%m/%Y') between '$date1' and '$date2' and individual_channel_id=0 order by STR_TO_DATE(separate_date ,'%d/%m/%Y') ")->result_array();

					for ($i=0; $i <=30 ; $i++) {
					$datereal=date('Y-m-d',strtotime($date1."+$i days"));

					$dator=null;
					$idfoundr=array_search(date('Y-m-d',strtotime($datereal)), array_column($datosr,'datereal'));
					if(!$idfoundr===false || $idfoundr===0)
					{
						$dator=$datosr[$idfoundr];
					}

					$EditpricesR=(isset($dator['price'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); " href="javascript:;" class="inline_username "  data-type="number" data-name="price" data-pk="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'"  data-title="Change Price">'.floatval($dator['price']).'</a>':'Null');
					$Editavar=(isset($dator['availability'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); "  href="javascript:;" class="inline_username "  data-type="number" data-name="availability" data-pk="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'"  data-title="Change Availability">'.intval($dator['availability']).'</a>':'Null');
					$Editminimumr=(isset($dator['minimum_stay'])?'<a style="border-bottom-color: rgba(255, 255, 255, 0.15); "  href="javascript:;" class="inline_username "  data-type="number" data-name="minimum_stay" data-pk="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'"  data-title="Change Minimum Stay">'.intval($dator['minimum_stay']).'</a>':'Null');

					$precio.='<td style="font-size: 12px; text-align:center;" >'.$EditpricesR.'</td>';
					$avai.='<td style="font-size: 12px;  text-align:center; background-color: '.(isset($dator['availability'])?($dator['availability']<=0?'#C0392B':'#F8F9F9'):'#C0392B').';" > '.$Editavar.' </td>';
					$min.='<td style="font-size: 12px; text-align:center; "> '.$Editminimumr.' </td>';
					$ctas.='<td style="font-size: 12px; text-align:center; "> <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'" name ="cta" type="checkbox" '.(isset($dator['cta'])=='1'?($dator['cta']==1?'checked':''):'').'/> </td>';
					$ctds.='<td style="font-size: 12px; text-align:center; "> <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'" name ="ctd" type="checkbox" '.(isset($dator['ctd'])=='1'?($dator['ctd']==1?'checked':''):'').'  /> </td>';
					$sss.='<td style="font-size: 12px; text-align:center; " > <input onchange="saveChange2(this)" value="'.$datereal.','.$roomid.','.$rate['ratetypeid'].','.$hotelid.'" name ="stop_sell" type="checkbox" '.(isset($dator['stop_sell'])?($dator['stop_sell']==1?'checked':''):'').' /> </td>';
				}

				$precio.='</tr>';
				$avai.='</tr>';
				$min .='</tr>';
				$ctas .='</tr>';
				$ctds .='</tr>';
				$sss .='</tr>';

				$body .=$precio.$avai.$min.$ctas.$ctds.$sss;


		 	}
				if ($showr==1) {
					//	$allreservations=$this->reservation_model->AllReservationList();
					foreach ($roomnumber as  $rooms) {
							$housekeepingstatus=$this->db->query("select * from housekeepingstatus where HousekeepingStatusId= `RoomStatusHousekeeping` ($roomid,'$rooms') limit 1 ")->row_array();
							$attributes=$this->db->query("select * from room_attributes where AttributeId in(select AttributeIds from room_number_attributes where RoomId =$roomid and RoomNumber ='$rooms' ) order by AttributeCode")->result_array();
							$atributetext='';
							$statusk=(isset($housekeepingstatus['Code'])?'<strong><span style="font-size:12px; " data-toggle="tooltip" data-placement="right" title="'.$housekeepingstatus['Name'].'">'.$housekeepingstatus['Code'].'</span></strong> ':'');
							foreach ($attributes as $attribute) {
									$atributetext .='<strong><span style="font-size:8px; " data-toggle="tooltip" data-placement="right" title="'.$attribute['AttributeName'].'">'.$attribute['AttributeCode'].'</span></strong> ';
							}
							$room2 .='<tr> <td style="background-color:'.(isset($housekeepingstatus['Code'])?$housekeepingstatus['Color']:'').'"> '.$statusk.' '.$atributetext.'</td> <td bgcolor="#E5E7E9" style="font-size: 12px; text-align:center; "> '.$rooms.'</td>';
							$room2 .= $this->ReservationShow($rooms, $date1, $roomid, $availability, $price, $room_update_id);
					}
				}
				$int++;

			$room2.='</tr>';
			$body.=($showr==1?$room2:'');
		}


		$body .='</tbody> </table>';
		return $html.$header1.$header2.$body;


	}
	function showRoomNumbers()
	{

	}
	function saveRateType($data)
	{
		$data['hotelid']=hotel_id();
		$data['created']=date('Y-m-d h:m:s');
		$data['active']=1;
		try {

			if(insert_data('ratetype',$data))
			{
				echo json_encode(array('success'=>true));
			}
			else
			{
				echo json_encode(array('success'=>false));
			}

		} catch (Exception $e) {

			echo json_encode(array('success'=>false,'message'=>$e));
		}

	}
	function updateRateType($data)
	{
		$rateid=$data['ratetypeid'];
		$data['active']=(isset($data['statusid'])?1:0);
		unset($data['ratetypeid']);
		unset($data['statusid']);
		try {

			if(update_data('ratetype',$data,array('ratetypeid'=>$rateid)))
			{
				echo json_encode(array('success'=>true));
			}
			else
			{
				echo json_encode(array('success'=>false));
			}

		} catch (Exception $e) {

			echo json_encode(array('success'=>false,'message'=>$e));
		}
	}
	function saveAmenties($roomid,$hoteId,$amenitiesid)
	{
		$ids='';
		foreach ($amenitiesid as $value) {
			$ids.=$value.',';
		}

		$data['amenities']=$ids;
		if(update_data('manage_property',$data,array('property_id'=>$roomid,'hotel_id'=>$hoteId)))
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	function saveAmenety($AmenetyId,$AmenetyName)
	{
		$data['type_id']=$AmenetyId;
		$data['amenities_name']=$AmenetyName;
		$data['HotelId']=hotel_id();
		$data['status']=1;
		$data['created_date']=date('Y-m-d H-m-s');

		$result['success']=false;
		$result['message']='Something went wrong';

		if(insert_data('room_amenities',$data))
		{	$result['success']=true;
			echo json_encode($result);
		}
		else
		{	$result['success']=false;
			echo json_encode($result);
		}

		//# amenities_id, type_id, amenities_name, status, created_date, modified_date

	}
	function saveAttribute($data)
	{
		$result['success']=false;
		$result['message']='Something went wrong';

		if(insert_data('room_attributes',$data))
		{	$result['success']=true;
			echo json_encode($result);
		}
		else
		{	$result['success']=false;
			echo json_encode($result);
		}
	}
	function savenewextra($data)
	{
		$result['success']=false;
		$result['message']='Something went wrong';

		if(insert_data('room_extras',$data))
		{	$result['success']=true;
			echo json_encode($result);
		}
		else
		{	$result['success']=false;
			echo json_encode($result);
		}
	}
	function loadAttributes($data)
	{
		$this->db->query("delete from room_number_attributes where RoomId =".$data['RoomId']);
		$datos['RoomId']=$data['RoomId'];
		$datos['Active']=1;
		foreach ($data['AttributeId'] as $key => $value) {
			$datos['RoomNumber']=$key;
			$AttributeIds='';
			foreach ($value as  $va) {
				$AttributeIds.= (strlen($AttributeIds)>0?',':'').$va;
			}
			$datos['AttributeIds']=$AttributeIds;
			insert_data('room_number_attributes',$datos);

		}

		$result['success']=true;
		echo json_encode($result);
	}
	function saveBasicInfoRoom($data)
	{
		$roomid=insep_decode($data['roomid']);
		$hotelid=insep_decode($data['hotelId']);
		$info['property_name']=$data['roomname'];
		$info['existing_room_count']=$data['qty'];
		$info['room_capacity']=$data['Occupancy'];
		$info['member_count']=$data['adult'];
		$info['children']=$data['Children'];
		$info['selling_period']=$data['selling_period'];
		$info['number_of_bathrooms']=$data['bathrooms'];
		$info['area']=$data['Area'];
		$info['description']=$data['description'];
		$info['meal_plan']=$data['MealPlanid'];
		$info['showwidget']=(isset($data['showwidget'])?1:0);


		if(update_data('manage_property',$info,array('property_id'=>$roomid,'hotel_id'=>$hotelid)))
		{
			return 0;
		}
		else
		{
			return 1;
		}

	}
	function saveRoomNumber($roomid,$hoteId,$roomNumber)
	{
		$ids='';
		foreach ($roomNumber as $value) {
			$ids.=$value.',';
		}
		$data['existing_room_number']=$ids;
		if(update_data('manage_property',$data,array('property_id'=>$roomid,'hotel_id'=>$hoteId)))
		{
			return 0;
		}
		else
		{
			return 1;
		}

	}
	function activeRevenue($roomid,$hotelId,$Status)
	{
		$info['revenuertatus']=$Status;
		if(update_data('manage_property',$info,array('property_id'=>$roomid,'hotel_id'=>$hotelId)))
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	function updateRevenue($roomid,$hotelId,$maximo,$Percentage)
	{
		$info['maximun']=$maximo;
		$info['percentage']=$Percentage;
		if(update_data('manage_property',$info,array('property_id'=>$roomid,'hotel_id'=>$hotelId)))
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	function user_channel_hotel()
	{



		// echo TBL_CHANNEL;
		// echo "<br/>";
		// echo MAP;
		// echo "<br/>";
		// echo  CONNECT;

		$query = $this->db->query('SELECT GROUP_CONCAT(C.channel_id) AS connected_channel FROM `'.CONNECT.'` C JOIN `'.MAP.'` M  ON M.channel_id=C.channel_id where C.user_id='.current_user_type().' AND C.hotel_id='.hotel_id().' AND C.status="enabled"');
		$row = $query->row();

		$getvl=$row->connected_channel;

        $exp_hids	=explode(",",$getvl);
		$connected_channel = array_unique($exp_hids);

		// echo $connected_channel;
		// die;
		$this->db->select('channel_id,channel_name');
		$this->db->where_in('channel_id',$connected_channel);
		$channel = $this->db->get(TBL_CHANNEL);
		// print_r($channel->result_array());
		// die;
		return $channel->result_array();
	}



    function recom_channel()

    {

		if(user_type()=='1')
		{
       		$query = $this->db->query('SELECT GROUP_CONCAT(manage_hotel.connected_channel) AS connected_channel FROM `manage_hotel` where owner_id='.user_id().' AND hotel_id='.hotel_id().'');
		}
		elseif(user_type()=='2')
		{
			$query = $this->db->query('SELECT GROUP_CONCAT(manage_hotel.connected_channel) AS connected_channel FROM `manage_hotel` where owner_id='.owner_id().' AND hotel_id='.hotel_id().'');
		}

       $row = $query->row();

       $getvl=$row->connected_channel;

       $exp_hids=explode(",",$getvl);

       $connected_channel = array_unique($exp_hids);

       $this->db->select('*');

       $this->db->where('status','active');

       $this->db->where_not_in('channel_id',$connected_channel);

       $channel = $this->db->get(TBL_CHANNEL);

       return $channel->result_array();

    }




    function hotel_channel()

    {
        if(user_type()=='1')
        {
                $query = $this->db->query('SELECT GROUP_CONCAT(manage_hotel.connected_channel) AS connected_channel FROM `manage_hotel` where owner_id='.hotel_id().'');
        }else if(user_type()=='2'){
                $query = $this->db->query('SELECT GROUP_CONCAT(manage_hotel.connected_channel) AS connected_channel FROM `manage_hotel` where owner_id='.owner_id().'');
        }
        $row = $query->row();

       $getvl=$row->connected_channel;

       $exp_hids=explode(",",$getvl);

       $connected_channel = array_unique($exp_hids);

       $this->db->select('*');

       $this->db->where_in('channel_id',$connected_channel);

       $channel = $this->db->get(TBL_CHANNEL);

       return $channel->result_array();

    }





	function add_photo($filename)
	{
		$ho_id = get_data(TBL_PHOTO,array('room_id'=>insep_decode($this->input->post('hotel_id'))))->row_array();
		print "ho_id=>\n";print_r($ho_id);
		if(count($ho_id)!=0)
		{
			$value = get_data(TBL_PHOTO,array('room_id'=>insep_decode($this->input->post('hotel_id'))))->row()->photo_names;
			$udata['room_id']=insep_decode($this->input->post('hotel_id'));
			$udata['photo_names'] = $value.','.$filename;
			print "udata=>";print_r($udata);
			if(update_data(TBL_PHOTO,$udata,array('room_id'=>insep_decode($this->input->post('hotel_id')))))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$idata['room_id']=insep_decode($this->input->post('hotel_id'));
			$idata['photo_names'] = $filename;
			print "idata=>";print_r($idata);
			if(insert_data(TBL_PHOTO,$idata))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

	}

	// Channel Section Start
	function count_all_channels()
	{
		$count = $this->db->select('channel_id')->from(TBL_CHANNEL)->
		where('status != ""')->count_all_results();
		return $count;
	}
	function channel_subscribe()
	{
		 $channel_subscribe = get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->channel_subscribe_status;
		 return $channel_subscribe;
	}
	function updatetransaction($transaction_id)
	{
		$payment_id = insep_encode($this->session->userdata('cha_type'));
		$plan_details = get_data(CHA_PLAN,array('channel_id'=>insep_decode($payment_id)))->row();
		$plan_duration = $plan_details->channel_type;
		$data['channel_subscribe_txnid']= $transaction_id;
		$data['channel_subscribe_planid']  = insep_decode($payment_id);
		$data['channel_subscribe_price'] = $plan_details->channel_price;
		$data['channel_subscribe_method'] = 'CRIDIT CARD';

				if($plan_duration=='Month')
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
					return true;
				}
				else
				{
					return false;
				}
	}
	function payment_success_mail($user_id='',$payment_id)
	{

		$get_email_info		=	get_mail_template('14');

		$email_subject1= $get_email_info['subject'];

		$email_content1= $get_email_info['message'];
		if(insep_decode($payment_id)==1)
		{
			$plan_names = get_data(CHA_PLAN,array('channel_id'=>insep_decode($payment_id)))->row();
			$plan_name = $plan_names->channel_price.' '.$plan_names->channel_plan;
		}
		else
		{
			$plan_name = get_data(CHA_PLAN,array('channel_id'=>insep_decode($payment_id)))->row()->channel_plan;
		}

		$row=get_data(TBL_USERS,array('user_id'=>current_user_type()))->row();

	  	$aa=array(

		'###USERNAME###'=>$row->fname.' '.$row->lname,

		'###id###'=>$row->transaction_id,

	    '###TYPE###'=>$plan_name,

		'###Validity###'=>$row->plan_to,

		'###status###'=>'Success',

		);

	$email_content=strtr($email_content1,$aa);
	//print_r($email_content);
	$admin_mail = get_data('site_config',array('id'=>'1'))->row();

	$this->mailsettings();

	$this->email->from($admin_mail->email_id);

	$this->email->to($row->email_address);

	$this->email->subject($email_subject1);

	$this->email->message($email_content);

	if($this->email->send())
	{
		return true;
	}
	else{
		return false;
	}
	}


	/*function mailsettings()
	{
		$this->load->library('email');
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);
	}*/

	function mailsettings()
	{
		$this->load->library('email');
		$config['wrapchars'] = 76;  // Character count to wrap at.
		$config['priority'] = 1;  // Character count to wrap at.
		$config['mailtype'] = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
		$config['charset'] = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
		$this->email->initialize($config);
	}


	// Channel Section End

         function get_password(){
		$this->db->where('user_id',$this->session->userdata('ch_user_id'));
		$res = $this->db->get('manage_users');
		if($res->num_rows>0){
			$result = $res->row();
			$t_hasher = new PasswordHash(8, FALSE);
			$hash = $result->password;
			$check = $t_hasher->CheckPassword($this->input->post('old_pass'), $hash);
			if($check)
			{
				return true;
			}
			else
			{
				return false;
			}
			return true;
		}
		else{return false;}

	}

	/*function update_password($new_pass)
	{
		$t_hasher = new PasswordHash(8, FALSE);
		$hash = $t_hasher->HashPassword($new_pass);
		$res = array('password'=>$hash);
		$this->db->where('user_id',$this->session->userdata('ch_user_id'));
		$result = $this->db->update('manage_users',$res);
		if($result)
		{
			return true;
		}
			return false;
	}*/


	function update_password($new_pass)
	{

		$t_hasher = new PasswordHash(8, FALSE);

		$hash121212 = $t_hasher->HashPassword($new_pass);

		$user_password_det = get_data(TBL_USERS,array('user_id'=>$this->session->userdata('ch_user_id')));


		if($user_password_det->num_rows()==1)
		{
			$password_det = $user_password_det->row();

			$old_password = $password_det->user_password;

			$org_pass = json_decode($old_password,true);


			$check12 = '';

			if(is_array($org_pass))
			{
				  foreach($org_pass as $key=>$value){

		    		$check12 .= $t_hasher->CheckPassword($new_pass, $key);
		    	}
			}


			/*if (array_key_exists($hash121212,$org_pass))
			{
				echo 'sdsdjsd';die;
			    return false;
			}*/
			if($check12!='')
			{
				//echo 'dsdsdsdsd isfdd';die;
				return false;
			}
			else
			{
				//echo 'dskdjklsdjl';die;

				$new_count = count($org_pass);

				if($new_count>=4)
				{

					if(is_array($new_pass))
					{
						$t_hasher = new PasswordHash(8, FALSE);

			            $hash = $t_hasher->HashPassword($new_pass);

						$new = array($hash=>"1");
					}


					$passs = array_shift($org_pass);

					$insert_pass = array_merge($org_pass, $new);

					print_r($insert_pass );
					die;

					$encode_password = json_encode($insert_pass);

					$update_pass = array('password'=>$hash,'user_password'=>$encode_password,'pw_ck'=>'2');

					$UpdatePassword = update_data(TBL_USERS,$update_pass,array('user_id'=>$this->session->userdata('ch_user_id')));

					if($UpdatePassword)
					{
						return true;
					}
					else
					{
						return false;
					}

				}
				else
				{

					$t_hasher = new PasswordHash(8, FALSE);

					$hash = $t_hasher->HashPassword($new_pass);

					$new = array($hash=>"1");


					$insert_pass = '';

					$encode_password = json_encode($insert_pass);

					$update_pass = array('password'=>$hash,'user_password'=>$encode_password,'pw_ck'=>'2');

					$UpdatePassword = update_data(TBL_USERS,$update_pass,array('user_id'=>$this->session->userdata('ch_user_id')));

					if($UpdatePassword)
					{
						return true;
					}
					else
					{
						return false;
					}
			 }
		   }
	}
}

	/*Start hiding from IE Mac \*/
	function assign_hotels()
	{
		$this->db->select('H.hotel_id,H.property_name');
		$this->db->join(ASSIGN.' as A','A.hotel_id=H.hotel_id');
		$this->db->where('A.user_id',user_id());
		$query = $this->db->get(HOTEL.' as H')->result_array();
		if(count($query)!=0)
		{
			return $query;
		}
		else
		{
			return false;
		}
	}
	/*Stop hiding from IE Mac */
	//02/12/2015...
	function all_users()
	{
		$this->db->where('hotel_id',hotel_id());
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('owner_id',user_id());
		}
		else if(user_type()=='2'){
			$this->db->where('owner_id',owner_id());
		}
		$res = $this->db->get('assign_priviledge');
		if($res->num_rows>0)
		{
			$user_assign = $res->result();
			$assign_user[]='';
			foreach($user_assign  as $assign)
			{
				$assign_user[]= $assign->user_id;
			}

		$this->db->where('owner_id',user_id());
		$this->db->where_not_in('user_id',$assign_user);
		$this->db->where('User_Type','2');
		$res = $this->db->get('manage_users');

		if($res->num_rows > 0)
		{
			return $res->result();
		}
		else
		{
			return false;
		}
		}else
		{
			$this->db->where('owner_id',user_id());
			$this->db->where('User_Type','2');
			$res = $this->db->get('manage_users');
			if($res->num_rows > 0)
			{
				return $res->result();
			}
			else
			{
				return false;
			}
		}
	}

	function add_user()
	{
		$user_name = implode(',',$this->security->xss_clean($this->input->post('user_name')));
		$this->db->where('user_id',$user_name);
		$ded = $this->db->get('manage_users');
		if($ded->num_rows==1){
			$deal = $ded->row();
			$dean = $deal->user_id;
		$access = implode(',',$this->security->xss_clean($this->input->post('access')));
		$data = array('user_id'=>$dean,
					  'access'=>$access,
					  'owner_id'=>current_user_type(),
					  'hotel_id'=>hotel_id());
		$res = $this->db->insert('assign_priviledge',$data);
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('manage_hotel');
		if($res->num_rows == 1)
		{
			$user_assign = $res->row();
			$assign = $user_assign->assigned_user;
			if($assign)
			{
			  $assign_det = $assign.','.$dean;
		    }
			else
			{
				 $assign_det = $dean;
			}
		$result = array('assigned_user'=>$assign_det);
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->update('manage_hotel',$result);
	   }
	   else
	   {
		   $res = $this->db->insert('manage_hotel',$result);
	   }
	   return true;
	}
	else
	{
		return false;
	}
	}

	// update user...
	function update_user($priviledge_id)
	{
		$user_name = $this->security->xss_clean($this->input->post('user_name'));
		$access = implode(',',$this->security->xss_clean($this->input->post('access')));
		$data = array('user_id'=>$user_name,
					  'access'=>$access,
					  );
		$this->db->where('owner_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$this->db->where('priviledge_id',$priviledge_id);
		$res = $this->db->update('assign_priviledge',$data);
		if($res)
		{
			return true;
		}
		else{
			return false;
		}
	}

	// delete users..
	function delete_user($u_id,$us_id)
	{
		$result = $this->del_users();
		$res = explode(',',$result->assigned_user);
		$del = array_search($us_id,$res);
		unset($res[$del]);
		$this->db->where('priviledge_id',$u_id);
		$this->db->delete('assign_priviledge');
		$data = array('assigned_user'=>implode(',',$res));
		$this->db->where('owner_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$this->db->update('manage_hotel',$data);
		delete_data(TBL_USERS,array('user_id'=>$us_id));
		return true;
	}

	// delete users....
	function del_users(){
		$this->db->where('owner_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('manage_hotel');
		if($res->num_rows == 1)
		{
			return $res->row();
		}
			return false;
	}

	// get access..
	function get_access(){
		$this->db->where('owner_id',user_id());
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('assign_priviledge');
		if($res->num_rows > 0){
			return $res->result();
		}
			return false;
	}

	//get_user_list...
	function get_user_list(){
		$this->db->order_by('priviledge_id','desc');
		// if(user_type()=='1'){
		$this->db->where('owner_id',current_user_type());
		/* }
		else if(user_type()=='2'){
			$this->db->where('owner_id',owner_id());
		} */
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('assign_priviledge');
		if($res->num_rows > 0)
		{
			return $res->result();
		}
		else
		{
			return false;
		}
	}

	//get user name
	 function get_username($user_id)
	{
		$this->db->where('owner_id',current_user_type());
		$this->db->where('user_id',$user_id);
		$this->db->where('User_Type',2);
		$query = $this->db->get('manage_users');
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
		{
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

	function get_user($priviledge_id){
		$this->db->where('priviledge_id',$priviledge_id);
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('owner_id',user_id());
		}
		else if(user_type()=='2'){
			$this->db->where('owner_id',owner_id());
		}
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('assign_priviledge');
		if($res->num_rows >0){
			return $res->result();
		}
			return false;
	}



function update_users($priviledge_id)
{
	$user_access = $this->security->xss_clean($this->input->post('access_options'));

	$access = json_encode($user_access);

    $data = array(
					'access'=>$access,
                 );
    $this->db->where('owner_id',current_user_type());

	$this->db->where('user_id',insep_decode($this->input->post('user_id')));

    $this->db->where('hotel_id',hotel_id());

    $this->db->where('priviledge_id',insep_decode($priviledge_id));

    $res = $this->db->update('assign_priviledge',$data);
	$t_hasher = new PasswordHash(8, FALSE);
	$hash = $t_hasher->HashPassword($this->security->xss_clean($this->input->post('user_password')));
	$udata['user_name'] = $this->security->xss_clean($this->input->post('user_name'));
	$udata['email_address'] = $this->security->xss_clean($this->input->post('email_address'));
	$udata['spass'] = $this->security->xss_clean($this->input->post('user_password'));
	$udata['password'] = $hash;
	update_data(TBL_USERS,$udata,array('user_id'=>insep_decode($this->input->post('user_id'))));

    if($res)
    {
		return true;
	}
    else
	{
        return false;
    }
}



	// count_connected_channels...
	function count_connected_channels(){
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',owner_id());
		}
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('user_connect_channel');
		return $res->num_rows;
	}

	function connected_channelss(){
		if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$this->db->where('user_id',current_user_type());
		}
		else if(user_type()=='2'){
			$this->db->where('user_id',owner_id());
		}
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('user_connect_channel');
		if($res->num_rows>0){
			return $res->result();
		}
			return false;
	}



    // channel_image...

    function channel_image($channel_id){

       $this->db->where('channel_id',$channel_id);
       $res = $this->db->get('manage_channel');

       if($res->num_rows == 1){

           return $res->row();

       }

           return true;

    }



    function get_room_name(){

    if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){

       $this->db->where('owner_id',current_user_type());

    }

    else if(user_type()=='2'){

       $this->db->where('owner_id',owner_id());

    }

       $this->db->where('hotel_id',hotel_id());

	   $this->db->where('status','Active');

	   $this->db->order_by('property_id','desc');

       $res = $this->db->get('manage_property');

       if($res->num_rows>0){

           return $res->result();

       }

       else{

           return false;

       }

    }


    // 25/01/2016...

    function add_bill()
	{

		$bill_id = $this->security->xss_clean($this->input->post('bill_id'));
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$res = $this->db->get('bill_info');
		$qry = $res->row();
		if($res->num_rows()==0)
		{
			// echo 'insert';die;
			$company_name = $this->security->xss_clean($this->input->post('company_name'));
			$town = $this->security->xss_clean($this->input->post('town'));
			$address = $this->security->xss_clean($this->input->post('address'));
			$zip_code = $this->security->xss_clean($this->input->post('zip_code'));
			$mobile = $this->security->xss_clean($this->input->post('mobile'));
			$vat = $this->security->xss_clean($this->input->post('vat'));
			$reg_num = $this->security->xss_clean($this->input->post('reg_num'));
			$email_address = $this->security->xss_clean($this->input->post('email_address'));
			$country = $this->security->xss_clean($this->input->post('country'));
			$data = array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'company_name'=>$company_name,'town'=>$town,'address'=>$address,'zip_code'=>$zip_code,'mobile'=>$mobile,'vat'=>$vat,'reg_num'=>$reg_num,'email_address'=>$email_address,'country'=>$country);
			// print_r($data);die;
			$ver = $this->db->insert('bill_info',$data);
			if($ver)
			{
				return true;
			}
			else
			{
				return false;
			}
	    }
	    else
		{
			// echo 'update';die;
		    $company_name = $this->security->xss_clean($this->input->post('company_name'));
			$town = $this->security->xss_clean($this->input->post('town'));
			$address = $this->security->xss_clean($this->input->post('address'));
			$zip_code = $this->security->xss_clean($this->input->post('zip_code'));
			$mobile = $this->security->xss_clean($this->input->post('mobile'));
			$vat = $this->security->xss_clean($this->input->post('vat'));
			$reg_num = $this->security->xss_clean($this->input->post('reg_num'));
			$email_address = $this->security->xss_clean($this->input->post('email_address'));
			$country = $this->security->xss_clean($this->input->post('country'));
			$data = array('company_name'=>$company_name,'town'=>$town,'address'=>$address,'zip_code'=>$zip_code,'mobile'=>$mobile,'vat'=>$vat,'reg_num'=>$reg_num,'email_address'=>$email_address,'country'=>$country);
			/*echo '<pre>';
			print_r($data);die;*/
			$this->db->where('bill_id',$bill_id);
			$asp = $this->db->update('bill_info',$data);
			if($asp)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	function get_bill(){
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$ver = $this->db->get('bill_info');
		/*echo '<pre>';
		print_r($ver->row());die;*/
		if($ver->num_rows()==1){
			return $ver->row();
		}
		else{
			return false;
		}
	}


function add_user_det()
{
	/*$total_success = $this->input->post('total_success');
	if($total_success!='')
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
	   // echo rtrim($db_val,' , ');
	} */
	$user_access = $this->security->xss_clean($this->input->post('access_options'));
	$access = json_encode($user_access);
	$user_name = $this->security->xss_clean($this->input->post('user_name'));
	$email_address = $this->security->xss_clean($this->input->post('email_address'));
	$user_password = $this->security->xss_clean($this->input->post('user_password'));
	$confirm_password = $this->security->xss_clean($this->input->post('confirm_password'));
	$t_hasher = new PasswordHash(8, FALSE);
	$hash = $t_hasher->HashPassword($this->security->xss_clean($this->input->post('user_password')));
	$data = array(
						'User_Type'=>'2',
						'owner_id'=>user_id(),
						//'access'=>implode(',',$access),
						'user_name'=>$user_name,
						'email_address'=>$email_address,
						'password'=>$hash,
						'spass'=>$this->security->xss_clean($this->input->post('user_password')),
						'ipaddress' 	=> 	$_SERVER['REMOTE_ADDR'],
						'user_agent'	=>  $_SERVER['HTTP_USER_AGENT'],
						'status'	=>  '0',
						'acc_active'	=>  '0',
				);
	if(insert_data(TBL_USERS,$data))
	{
		$user_id = $this->db->insert_id();
		$data = array(	'user_id'=>$user_id,
						'access'=>$access,
						'owner_id'=>current_user_type(),
						'hotel_id'=>hotel_id());
		if(insert_data('assign_priviledge',$data))
		{
			$priviledge_id = $this->db->insert_id();
			$this->db->where('priviledge_id',$priviledge_id);
			$res = $this->db->get('assign_priviledge');
			if($res->num_rows()==1)
			{
				$ass = $res->row();
				$ass_id = $ass->user_id;
				$this->db->where('hotel_id',hotel_id());
				$ver = $this->db->get('manage_hotel');
				if($ver->num_rows()==1)
				{
					$var = $ver->row();
					$assign = $var->assigned_user;
					if($assign)
					{
						$dean = $assign.','.$ass_id;
					}
					else
					{
						$dean = $ass_id;
					}
					$data = array('assigned_user'=>$dean);
					$this->db->where('hotel_id',hotel_id());
					$res = $this->db->update('manage_hotel',$data);
					if($res)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					$varia = $this->db->insert('manage_hotel',$data);
					if($varia)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
		}
	}
	else
	{
		return false;
	}
}
function get_connect_channel($chann=''){
    	if($chann!=""){
	    	$this->db->where('channel_id',$chann);
	    	$this->db->where('user_id',current_user_type());
	    	$this->db->where('hotel_id',hotel_id());
	    	$ver = $this->db->get('user_connect_channel');
        }else{
        	$this->db->where('user_id',current_user_type());
	    	$this->db->where('hotel_id',hotel_id());
	    	$ver = $this->db->get('user_connect_channel');
        }
    	if($ver->num_rows()==1){
    		return $ver->row();
    	}
    	else{
    		return false;
    	}
    }


function get_connect_channels(){

	$userid='';
	$hotelid=hotel_id();
	$today=date('Y-m-d');

	$sql = "SELECT a.status,b.channel_name,
			(select error_message from channel_error where channel_id  = a.channel_id  and STR_TO_DATE(error_date_time ,'%m/%d/%Y') = '$today' ORDER BY error_date_time DESC LIMIT 1  ) message
			FROM user_connect_channel a
			left join manage_channel b on a.channel_id =b.channel_id
			where  a.hotel_id = $hotelid ";
	$ver = $this->db->query($sql);
        if($ver->num_rows()>0){
    		return $ver->result();
    	}
    	else{
    		return false;
    	}
    }
function allChannelsConnect(){

	$userid='';
	$hotelid=hotel_id();
	$today=date('Y-m-d');

	$sql = "SELECT a.status,b.channel_name, a.channel_id
			FROM user_connect_channel a
			left join manage_channel b on a.channel_id =b.channel_id
			where a.hotel_id = $hotelid ";
	$ver = $this->db->query($sql);
        if($ver->num_rows()>0){
    		return $ver->result_array();
    	}
    	else{
    		return false;
    	}
    }
    function get_connect_channels_with_status($channel_id){
    	$yesterday = date('m/d/Y',strtotime("-1 days"));
    	$yesterday = $yesterday." 11:59:59 am";
    	$sql = "SELECT error_message FROM channel_error WHERE user_id='".current_user_type()."' AND hotel_id='".hotel_id()."' AND channel_id='".$channel_id."' AND error_date_time > '".$yesterday."'  AND error_date_time <= '".date('m/d/Y h:i:s a', time())."' ORDER BY error_date_time DESC LIMIT 1 ";
    	$query = $this->db->query($sql);
    	if($query->num_rows() == 0){
    		return 0;
    	}else{
    		foreach($query->result() as $message)
    			return $message->error_message;
    	}
    }

	function get_channel_id($view_channel)
	{
		$this->db->where('seo_url',$view_channel);
		$ver = $this->db->get('manage_channel');
		// print_r($ver->row());die;
		if($ver->num_rows()==1)
		{
			return $ver->row();
		}
		else
		{
			return false;
		}
	}


    function new_notification()
    {
         $this->db->where('status','unseen');
         $fetch=$this->db->get('notifications');
         if($fetch->num_rows>0)
         {
            return $fetch->num_rows();
         }
         else
         {
            return '0';
         }
    }

    function new_notification_result()
    {
        $this->db->where('status','unseen');
        $this->db->order_by('n_id','desc');
        $fetch=$this->db->get('notifications');
        if($fetch->num_rows>0)
        {
            return $fetch->result();
        }
        else
        {
            return false;
        }
    }

     //12/02/2016...

    function total_today_reservation()
	{
		$this->db->where('user_id',current_user_type());
		$this->db->where('hotel_id',hotel_id());
		$this->db->where('status','unseen');
		$this->db->where_not_in('reservation_id',0);
		$ver = $this->db->get('notifications');
		if($ver->num_rows>0)
		{
			return $ver->num_rows();
		}
		else
		{
			return false;
		}
	}
function getMappedRoom_Rate($channel_id)
	{
		$query = $this->db->query("(
		SELECT * FROM `roommapping`
		WHERE `owner_id` = '".current_user_type()."' AND `hotel_id` = '".hotel_id()."' AND `channel_id` = '".$channel_id."' AND `rate_id` ='0'
		GROUP BY `property_id`
		)
		UNION ALL
		(
		SELECT * FROM `roommapping`
		WHERE `owner_id` = '".current_user_type()."' AND `hotel_id` = '".hotel_id()."' AND `channel_id` = '".$channel_id."' AND `rate_id` !='0'
		GROUP BY `rate_id`
		)
		ORDER BY `property_id`");

		/* $query = $this->db->query("(
		SELECT * FROM `roommapping`
		WHERE `owner_id` = '".current_user_type()."' AND `hotel_id` = '".hotel_id()."' AND `channel_id` = '".$channel_id."' AND `rate_id` ='0'
		)
		UNION ALL
		(
		SELECT * FROM `roommapping`
		WHERE `owner_id` = '".current_user_type()."' AND `hotel_id` = '".hotel_id()."' AND `channel_id` = '".$channel_id."' AND `rate_id` !='0'
		)
		ORDER BY `property_id`"); */

		if($query)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	function save_connection_req_mail($data)
	{
		$this->db->insert('save_connection_req_mail',$data);
		$askforconnection = get_data(HOTEL,array('hotel_id'=>hotel_id()))->row();
		$ch = $this->get_connect_channel(5);
		$senderdetails = get_data(CONFIG,array('id'=>1))->row()->req_mail;
		$content = "Dear Xml Department,<br><br>
Kindly could you enable xml connection for Property:".$askforconnection->property_name."<br> Id ".$ch->hotel_channel_id."<br>

Hotel User ".$ch->user_name."<br>

Address: ".$askforconnection->address.",".$askforconnection->town."-".$askforconnection->zip_code."<br>

Email: ".$askforconnection->email_address."<br>

Web: ".$askforconnection->web_site."<br><br>


Please enable reservation push method to <br>
url:".base_url()."reservation/getHotelbedsReservation/".insep_encode(5)."<br><br>


Thanks for cooperation<br>
Best regards from<br>
hoteratus team.";

		$this->mailsettings();
		if($senderdetails != ""){
			$this->email->from($senderdetails);
		}else{
			$this->email->from('connectivity@hoteratus.com');
		}

		$this->email->to($data['recipients']);

		$this->email->subject($data['subject']);

		$this->email->message($content);

		if($this->email->send())
		{
			return true;
		}
		else{
			return false;
		}
	}


/* sharmila starts here 0803  */

	function delete($table,$field,$value)
    {
        $this->db->where($field,$value);
        $delete = $this->db->delete($table,$data);
        if($delete)
        {
           return true;
        }
        return false;
    }

     function insert($table,$data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

     function get_details_unique($table,$field=null,$value=null,$field1=null,$value1=null)
    {
		if($field!='')
		{
			$this->db->where($field, $value);
		}
		if($field1!='')
		{
			$this->db->where($field1, $value1);
		}
		$q = $this->db->get($table);
        return $q->row();
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

    function email_newsletter()
	{
		$email = $this->input->post('email');
		$email_content1 = $this->input->post('descrpation');
		$email_subject1 = $this->input->post('subject');
        foreach ($email as  $email_address) {
	   $aa=array(
         	'###status###'=>'Success',
         );
	    $email_content=strtr($email_content1,$aa);

		$admin_mail = get_data('site_config',array('id'=>'1'))->row();
		$this->mailsettings();
		$this->email->from($admin_mail->email_id);
		$this->email->to($email_address);
		$this->email->subject($email_subject1);
		$this->email->message($email_content);
		$this->email->send();
         }
            return true;
	}


    /* sharmila ends here 0803  */


}
