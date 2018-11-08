<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');

class template extends Front_Controller
{
	public function __construct()
   {
		parent::__construct();	
		
	}


	function admintemplate()
	{
		is_login();
    	$data['page_heading'] = 'Template';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['TemplatesType']= $this->db->query("SELECT a.*, b.TemplateHotelid,b.Subject,b.Message,b.Userid,b.HotelId 
		FROM TemplateType a
		left join TemplateHotel b on a.TemplateTypeId=b.TemplateTypeId and b.hotelid=".hotel_id()."
		where a.active=1 and b.TemplateTypeId is null")->result_array();
		$data['Templates']=$this->db->query("SELECT a.*, b.TemplateHotelid,b.Subject,b.Message,b.Userid,b.HotelId 
		FROM TemplateType a
		left join TemplateHotel b on a.TemplateTypeId=b.TemplateTypeId and b.hotelid=".hotel_id()."
		where a.active=1 and b.TemplateTypeId is not null")->result_array();
		$this->views('template/template',$data);
	}

	function edittemplate($id)
	{	
		is_login();
		$TemplateTypeId=insep_decode($id);
    	$data['page_heading'] = 'Template';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>hotel_id()))->row_array();
		$data['Templates']=$this->db->query("SELECT a.*, b.TemplateHotelid,b.Subject,b.Message,b.Userid,b.HotelId 
		FROM TemplateType a
		left join TemplateHotel b on a.TemplateTypeId=b.TemplateTypeId and b.hotelid=".hotel_id()."
		where a.active=1 and a.TemplateTypeId=".$TemplateTypeId." limit 1")->row_array();
		$data['Variables']=$this->db->query("select * from Variables where VariableID in (".$data['Templates']['Variable'].")")->result_array();
		$this->views('template/templateedit',$data);
	}

	function templatesave()
	{
		$exist=$this->db->query("select * from TemplateHotel where TemplateTypeId=".$_POST['TemplateTypeId']." and hotelid=".hotel_id())->result_array();

		
		$data['Subject']=$_POST['Subject'];
		$data['Message']=$_POST['Message'];
		if(count($exist)>0)
		{
			update_data('TemplateHotel',$data,array('TemplateTypeId'=>$_POST['TemplateTypeId'],'hotelid'=>hotel_id()));
		}
		else
		{
			$data['TemplateTypeId']=$_POST['TemplateTypeId'];
			$data['hotelid']=hotel_id();
			insert_data('TemplateHotel',$data);

		}
		
		redirect('template/admintemplate');
	}


}