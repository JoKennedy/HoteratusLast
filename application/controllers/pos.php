<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');
class POS extends Front_Controller {

	function is_login()
	{
		if(!user_id())
		redirect(base_url());
		return;
	}

	function ordenhtml($data)
	{
		$html='';
		$grandtotal=0.00;
		$i=0;
		$result='';
	    foreach ($data as  $value) {
	        $i++;
		    $html.=' <tr id="'.$value['itemid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td>'.$value['itemname'].'  </td> <td>'.number_format($value['price'], 2, '.', '').'</td> <td>'.$value['qty'].'</td> <td>'.number_format($value['price']*$value['qty'], 2, '.', '').'</td>
		    	 <td  align="center"><a id="'.$value['itemid'].'" onclick="additem('."this.id".');"> <i class="fa fa-plus"></i></a> </td> <td align="center"><a id="'.$value['itemid'].'" onclick="deleteitem('."this.id".');"> <i class="fa fa-trash-o"></i></a> </td>  </tr>  ';
		    $grandtotal+=($value['price']*$value['qty']);
	   }
	   	$result['grandtotal']=$grandtotal;
	   	$result['html']=$html;
		return $result;
	}

	function viewpos($hotelid,$posid)
	{
		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'All The Tables';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllTable']=$this->db->query("SELECT a.*,(select count(*)  from mypostablereservation b where b.mypostableid=a.postableid and  datetimereservation='$today' ) appointment, (select count(*) from orderslist  where mypostableid= a.postableid and active =1 ) used FROM mypostable a where  myposId=$posid ")->result_array();
		$this->views('Restaurant/main',$data);
	}

	function viewCreationtable($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'New Table';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllTable']=$this->db->query("SELECT a.*,(select count(*)  from mypostablereservation b where b.mypostableid=a.postableid and  datetimereservation='$today' ) appointment, (select count(*) from orderslist  where mypostableid= a.postableid and active =1 ) used FROM mypostable a where  myposId=$posid ")->result_array();
		$this->views('Restaurant/creationtable',$data);
	}
	function viewCategories($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Categories';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllCategories']=$this->db->query("SELECT * FROM itemcategory  where  posId=$posid ")->result_array();
		$this->views('Restaurant/categories',$data);
	}
	function viewProducts($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Products';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllCategories']=$this->db->query("SELECT * FROM itemcategory  where  posId=$posid ")->result_array();
		$data['AllUnits']=$this->db->query("SELECT * FROM units  order by name ")->result_array();
		$data['ALLProducts']=$this->db->query("SELECT a.*, b.name Categoryname, (select price from itemprice  where itemid=a.itemPosId and isitem=1 ORDER BY `datetime` DESC LIMIT 1) price, c.name unitname
												FROM itempos a
												left join itemcategory b on a.itemcategoryID = b.itemcategoryid
												left join units c on a.unitid = c.unitid
												where b.posid=$posid ")->result_array();
		$this->views('Restaurant/products',$data);
	}
	function viewRecipes($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Recipes';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['ALLProducts']=$this->db->query("SELECT a.*, b.name Categoryname, (select price from itemprice  where itemid=a.itemPosId and isitem=1 ORDER BY `datetime` DESC LIMIT 1) price, c.name unitname
												FROM itempos a
												left join itemcategory b on a.itemcategoryID = b.itemcategoryid
												left join units c on a.unitid = c.unitid
												where b.posid=$posid ")->result_array();
		$data['ALLRecipes']=$this->db->query("SELECT a.*,  (select price from itemprice  where itemid=a.recipeid and isitem=0 ORDER BY `datetime` 
												DESC LIMIT 1) price
												FROM recipes a
												where a.posid=$posid and a.active=1 ")->result_array();

		$this->views('Restaurant/recipes',$data);


		#detalle de recetas
		/*select a.name, b.quantity,c.name, (select price from itemprice  where itemid=b.itemid ORDER BY `datetime` DESC LIMIT 1)  unitary, 
		b.quantity*(select price from itemprice  where itemid=b.itemid ORDER BY `datetime` DESC LIMIT 1) importe,
		d.name UnitName
		from recipes a
		left join recipedetails b on a.recipeid =b.recipeid
		left join itempos c on b.itemid=c.itemposid
		left join units d on c.unitid =d.unitid*/
	}
	function viewEmployees($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Employees';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllStaffType']=$this->db->query("SELECT * FROM stafftype  where  hotelid=$hotelid ")->result_array();
		$data['AllStaff']=$this->db->query("SELECT a.*, b.name stafftypename, concat(firstname,' ' , lastname) fullname 
											FROM mystaffpos a
											left join stafftype b on a.stafftypeid = b.stafftypeid  
											where  a.hotelid=$hotelid ")->result_array();
		$this->views('Restaurant/employes',$data);
	}
	function viewSuppliers($hotelid,$posid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Suppliers';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['AllSuppliers']=$this->db->query("SELECT * FROM suppliers  where  myposid=$posid ")->result_array();
		$this->views('Restaurant/suppliers',$data);
	}
	function viewtable($hotelid,$posid,$tableid)
	{

		$hotelid= unsecure($hotelid);
		$posid =insep_decode($posid);
		$tableid=insep_decode($tableid);
		$this->is_login();
		$hotelid=hotel_id();
		$today=date('Y-m-d');
    	$data['page_heading'] = 'Table';
    	$user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
		$data= array_merge($user_details,$data);
		$data['AllHotel']= get_data('manage_hotel',array('owner_id'=>user_id()))->result_array();
		$data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
		$data['Posinfo']=$this->db->query("SELECT a.*, b.description postype, c.numbertable  FROM mypos a left join postype b on a.postypeid=b.postypeid left join myposdetails c on a.myposId=c.myposId where hotelid=$hotelid and a.myposId=$posid ")->row_array();
		$data['TableInfo']=$this->db->query("SELECT * FROM mypostable  where postableid =$tableid ")->row_array();
		$data['OrderInfo']=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty,c.name itemname,
		ifnull((SELECT  price FROM itemprice WHERE ITEMID=b.itemid and isitem =1 AND 'DATETIME' <= a.datetime ORDER BY 'datetime' DESC LIMIT 1),0) price from orderslist a left join  orderlistdetails b on a.ordersListid = b.ordersListid left join itempos c on b.itemid=c.itemPosId where a.mypostableid= $tableid and a.active =1 group by b.itemid")->result_array();

		$data['StaffInfo']=$this->db->query("SELECT a.*, b.name occupation
			FROM mystaffpos a
			left join stafftype b on a.stafftypeid = b.stafftypeid
			where a.hotelid =$hotelid ")->result_array();

		$data['waiter']=(count($data['OrderInfo'])==0 || $data['OrderInfo'][0]['StaffCode']==0 ?'':$this->db->query("select concat(firstname,' ',lastname) name from mystaffpos where mystaffposid =".$data['OrderInfo'][0]['StaffCode'])->row()->name);

		$data['Categories']=$this->db->query("SELECT * from itemcategory where posid= $posid")->result_array();

		
		$this->views('Restaurant/viewtable',$data);
	}

	function allitem($catid='')
	{
		$html='';
		if($catid=='')
		{
			$catid=$_POST['catid'];
		}
		$allitem=$this->db->query("SELECT * from itempos where itemcategoryid=$catid  and active=1")->result_array();

		if (count($allitem)==0) {

			$html= '<h4 align="center"> This category does not have assigned items </4>';
		}
		else
		{
			foreach ($allitem as  $value) {

				$html .= '<div  class="col-md-4 div-img">
                                    <a onclick="additem('."this.id".');" id="'.$value['itemPosId'].'"><img class="img"  src="'.$value['photo'].'"></a>
                                    <h4> '.$value['name'].' </h4>

                                </div> <br>';
			}
		}

		echo $html.' <div class="clearfix"></div>';
	}
	function additem()
	{

		$itemid=$_POST['itemid'];
		$tableid=$_POST['tableid'];
		$html='';
		$ordenid='';
		$data= array();

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if(count($OrderInfo)>0)
		{
			$ordenid=$OrderInfo['ordersListid'];
			$info['ordersListid']=$ordenid;
			$info['itemid']=$itemid;
			$info['qty']=1;
			if(!insert_data('orderlistdetails',$info))
			{
				$data['success']=true;
				echo json_encode($data);
				return;
			}

		}
		else
		{
			$main['mypostableid']= $tableid;
			$main['StaffCode']= '';
			$main['active']= 1;
			insert_data('orderslist',$main);

			$info['ordersListid']=$this->db->insert_id();
			$info['itemid']=$itemid;
			$info['qty']=1;

			if(!insert_data('orderlistdetails',$info))
			{
				$data['success']=true;
				echo json_encode($data);
				return;
			}




		}

			$OrderInfo=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty,c.name itemname,
				ifnull((SELECT  price FROM itemprice WHERE ITEMID=b.itemid and isitem =1 AND `datetime` <= a.datetime ORDER BY `datetime` DESC LIMIT 1),0) price from orderslist a left join  orderlistdetails b on a.ordersListid = b.ordersListid left join itempos c on b.itemid=c.itemPosId where a.mypostableid= $tableid and a.active =1 group by b.itemid order by b.orderlistdetailid ")->result_array();




	     if (count($OrderInfo)>0) {


	    	$datahtml=$this->ordenhtml($OrderInfo);

		}

		$data['total']='<h2><strong>Total Due:</strong>'.number_format($datahtml['grandtotal'], 2, '.', '').'</h2>' ;
		$data['html']=$datahtml['html'];
		$data['success']=true;
		echo json_encode($data);

	}
	function deleteitem()
	{

		$itemid=$_POST['itemid'];
		$tableid=$_POST['tableid'];
		$html='';
		$ordenid='';
		$data= array();

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if(count($OrderInfo)>0)
		{
			$ordenid=$OrderInfo['ordersListid'];
			$info['ordersListid']=$ordenid;
			$info['itemid']=$itemid;
			$info['qty']=1;

			$this->db->query("delete from  orderlistdetails where itemid=$itemid and ordersListid=$ordenid  limit 1");

				$OrderInfo=$this->db->query("SELECT a.*,b.itemid,b.orderlistdetailid,sum(b.qty) qty,c.name itemname,
				ifnull((SELECT  price FROM itemprice WHERE ITEMID=b.itemid and isitem=1 AND DATETIME <= a.datetime ORDER BY datetime DESC LIMIT 1),0) price from orderslist a left join  orderlistdetails b on a.ordersListid = b.ordersListid left join itempos c on b.itemid=c.itemPosId where a.mypostableid= $tableid and a.active =1 group by b.itemid order by b.orderlistdetailid")->result_array();
		}


	     if (count($OrderInfo)>0) {

	    	$datahtml=$this->ordenhtml($OrderInfo);
		}

		$data['total']='<h2><strong>Total Due:</strong>'.number_format($datahtml['grandtotal'], 2, '.', '').'</h2>' ;
		$data['html']=$datahtml['html'];
		$data['success']=true;
		echo json_encode($data);

	}
	function exists_staff()
	{

		$tableid=$_POST['tableid'];

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (!isset($OrderInfo['StaffCode'])) {

			$result['status']='-1';

		}
		else
		{
			if(strlen(isset($OrderInfo['StaffCode']))>0)
			{
				$result['status']='1';
			}
			else
			{
				$result['status']='0';
			}
		}

		echo json_encode($result);

	}
	function addstaff()
	{
		$tableid=$_POST['tableid'];
		$staffid=$_POST['staffid'];
		$orderid='';
		$oldstaff='';

		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (!isset($OrderInfo['StaffCode'])) {

			$main['mypostableid']= $tableid;
			$main['StaffCode']= '';
			$main['active']= 1;
			insert_data('orderslist',$main);
			$oldstaff='';
			$orderid=$this->db->insert_id();
		}
		else
		{
			$orderid=$OrderInfo['ordersListid'];
			$oldstaff=$OrderInfo['StaffCode'];
		}

			$update['StaffCode']=$staffid;

			$staff=get_data('mystaffpos',array('mystaffposid'=>$staffid))->row_array();

			$result['staff']='';
			if(count($staff)>0)
			{
				$result['staff']='<h3><strong>Waiter/s:</strong>'.$staff['firstname'].' '.$staff['lastname'].'</h3>';
			}
			else
			{
				$result['staff']='<h3><strong>Waiter/s:</strong> Not Assigned</h3>';
			}

			update_data('orderslist',$update,array('ordersListid'=>$orderid));

			echo json_encode($result);

	}

	function cancelorden()
	{


		$tableid=$_POST['tableid'];
		$OrderInfo=$this->db->query("SELECT * from orderslist  where mypostableid= $tableid and active =1 limit 1 ")->row_array();

		if (isset($OrderInfo['ordersListid'])) {
			$orderid=$OrderInfo['ordersListid'];
		}
		else {
			$data['result']=false;
			echo json_encode($data);
			return;
		}
		$update['active']=0;
		if(update_data('orderslist',$update,array('ordersListid'=>$orderid)))
		{
			$data['result']=true;
		}
		else {
			$data['result']=false;
		}
		echo json_encode($data);
		return;
	}
	function availabletable()
	{
		$myposid=$_POST['posid'];

		$available=$this->db->query("select *
																from mypostable a
																where
																(select count(*) from orderslist where mypostableid= a.postableid and active= 1 )=0
																and myposid=$myposid")->result_array();


		if (count($available)==0) {
			$data['html']='<h1 align="center">No Available Table</1>';
			$data['result']=false;
			echo json_encode($data);
			return;
		}

		$html='';
		$html.='<div class="graph">
				<div class="table-responsive">
						<div class="clearfix"></div>
						<table id="tablestaff" class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Table Name</th>
												<th>Capacity</th>
												<th>Change</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($available as  $value) {
						$i++;
						$html.=' <tr id="table'.$value['postableid'].'"  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['description'].'  </td> <td>'.$value['qtyPerson'].'</td>
								<td  align="center"><a id="'.$value['postableid'].'" onclick="changetable('."this.id".');">
								 <i class="fa fa-check-circle fa-2x"></i></a> </td> </tr>';


					}
					$html.='</tbody>
									</table>';
					$data['html']=$html;
					$data['result']=true;
					echo json_encode($data);
	}

	function changetable()
	{
			$oldtable=$_POST['oldid'];
			$newtable=$_POST['newid'];
			$available=$this->db->query("SELECT * from orderslist  where mypostableid= $newtable and active =1 limit 1 ")->result_array();

			if (count($available)>0) {
				$data['result']=1;
				echo json_encode($data);
			}
			$orderid=$this->db->query("SELECT * from orderslist  where mypostableid= $oldtable and active =1 limit 1 ")->row_array()['ordersListid'];
			$update['mypostableid']=$newtable;
			if(update_data('orderslist',$update,array('ordersListid'=>$orderid)))
			{
				$data['result']=0;
				$data['url']=site_url('pos/viewtable/'.secure(hotel_id()).'/'.insep_encode($_POST['posid']).'/'.insep_encode($newtable));
				echo json_encode($data);
			}
	}
	function savePOS()
	{
			$data['hotelId']=hotel_id();
			$data['userid']=user_id();
			$data['active']=1;
			$data['postypeID']=$_POST['typeposid'];
			$data['description']=$_POST['posname'];
			if(insert_data('mypos',$data))
			{
				echo "0";
			}
			else {
				echo "1";
			}
	}
	function saveTable()
	{
			$data['myposid']=$_POST['postid'];
			$data['active']=1;
			$data['qtyPerson']=$_POST['Capacity'];
			$data['description']=$_POST['tablename'];
			if(insert_data('mypostable',$data))
			{
				echo "0";
			}
			else {
				echo "1";
			}

	}
	function updateTable()
	{
			$data['myposid']=$_POST['postidup'];
			$data['active']=1;
			$data['qtyPerson']=$_POST['Capacityup'];
			$data['description']=$_POST['tablenameup'];
			
			if(update_data('mypostable',$data,array('postableid' =>$_POST['tableidup'] )))
			{
				echo "0";
			}
			else {
				echo "1";
			}

	}
	function LoadImage()
	{
		echo (var_dump($_FILES));
		echo (var_dump($_POST));
		return;
		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      echo "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      echo "Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        echo "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        echo "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$nombre;
		        move_uploaded_file($ruta_provisional, $src);
		        echo '<img id="pathimagen" src="/'.$src.'">';
		    }
		}
	}
	function saveCategory()
	{		
		$result["result"]='';

		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posid'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
				$data['posid']=$_POST['posid'];
				$data['name']=$_POST['categoryname'];
				$data['active']=1;

				if(insert_data('itemcategory',$data))
				{
					$result["result"]= "0";
				}
				else 
				{
					$result["result"]= "1";
				}

		    }

		    echo json_encode($result);

		}
	
	}
	function updateCategory()
	{		
		$result["result"]='';
		if (strlen($_FILES["photoup"]["name"])>0)
		{

		    $file = $_FILES["photoup"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Categories/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]="Error, el archivo no es una imagen"; 
		     
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]= "Error, el tamaño máximo permitido es un 1MB";
		        
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]="Error la anchura y la altura maxima permitida es 500px";
		         
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]="Error la anchura y la altura mínima permitida es 60px";
		          return;
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posidup'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
		    }
		}

				if(strlen( $result["result"])==0)
				{
					$data['posid']=$_POST['posidup'];
					$data['name']=$_POST['categorynameup'];
					update_data('itemcategory',$data,array('itemcategoryID' =>$_POST['itemcategoryidup'] ));
					$result["result"]=$result["result"]="0";
				}
				

				echo json_encode($result);
			
	}
	function saveProduct()
	{		
		$result["result"]='';

		if (isset($_FILES["Image"]))
		{

		    $file = $_FILES["Image"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Product/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['Categoryid'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
				$data['itemcategoryid']=$_POST['Categoryid'];
				$data['name']=$_POST['productname'];
				$data['type']=$_POST['type'];
				$data['code']='item';
				$data['brand']=$_POST['brand'];;
				$data['model']=$_POST['model'];;
				$data['stock']=$_POST['stock'];;
				$data['unitid']=$_POST['unitid'];
				$data['active']=1;

				if(insert_data('itempos',$data))
				{
					
					
					$datal['itemid']=$this->db->insert_id();
					$datal['price']=$_POST['pricen'];
					insert_data('itemprice',$datal);

					$result["result"]= "0";
				}
				else 
				{
					$result["result"]= "1";
				}

		    }

		    echo json_encode($result);

		}
	
	}
	function updateProduct()
	{		
		$result["result"]='';
		if (strlen($_FILES["Imageup"]["name"])>0)
		{

		    $file = $_FILES["Imageup"];


		   	
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Product/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]="Error, el archivo no es una imagen"; 
		     
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]= "Error, el tamaño máximo permitido es un 1MB";
		        
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]="Error la anchura y la altura maxima permitida es 500px";
		         
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]="Error la anchura y la altura mínima permitida es 60px";
		          return;
		    }
		    else

		    {	
		    	
		        $src = $carpeta.$_POST['posidup'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;
		    }
		}

				if(strlen( $result["result"])==0)
				{
					$data['itemcategoryid']=(int)$_POST['Categoryidup'];
					$data['type']=$_POST['typeup'];
					$data['name']=$_POST['productnameup'];
					$data['brand']=$_POST['brandup'];
					$data['model']=$_POST['modelup'];
					$data['stock']=$_POST['stockup'];
					$data['unitid']=$_POST['unitidup'];
					update_data('itempos',$data,array('itemPosId' =>$_POST['itemPosId'] ));
					$result["result"]="0";
				}


				echo json_encode($result);
			
	}
	function saveSupplier()
	{		
		$result["result"]='';

	    $data['companyname']=$_POST['cname'];
		$data['representativename']=$_POST['rname'];
		$data['address']=$_POST['address'];
		$data['phone']=$_POST['phone'];
		$data['cellphone']=$_POST['cphone'];
		$data['email']=$_POST['email'];
		$data['active']=1;
		$data['myposid']=$_POST['posid'];


		if(insert_data('suppliers',$data))
		{
			$result["result"]= "0";
		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

		# supplierID, myposid, companyname, representativename, address, phone, cellphone, email, active

	}
	function updateSupplier()
	{		
		$result["result"]='';

	    $data['companyname']=$_POST['cnameup'];
		$data['representativename']=$_POST['rnameup'];
		$data['address']=$_POST['addressup'];
		$data['phone']=$_POST['phoneup'];
		$data['cellphone']=$_POST['cphoneup'];
		$data['email']=$_POST['emailup'];
		


		if(update_data('suppliers',$data,array('supplierID' =>$_POST['supplierID'])))
		{
			$result["result"]= "0";
		}
		else 
		{
			$result["result"]= "1";
		}

		 echo json_encode($result);

		# supplierID, myposid, companyname, representativename, address, phone, cellphone, email, active

	}
	function saveEmployee()
	{		
		$result["result"]='';

		
		if (isset($_FILES["Image"]) && strlen($_FILES["Image"]["name"])>0)
		{

		    $file = $_FILES["Image"];
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Employee/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	

		        $src = $carpeta.$_POST['posid'].$_POST['name'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;

		    }

		}


			if( $result["result"]=="")
			{
				$data['firstname']=$_POST['name'];
				$data['lastname']=$_POST['lastname'];
				$data['gender']=$_POST['gender'];
				$data['stafftypeid']=$_POST['staffType'];
				$data['hotelid']=hotel_id();
				$data['active']=1;
			    if(insert_data('mystaffpos',$data))
				{
					$result["result"]="0";
				}
				else 
				{
					$result["result"]= "1";
				}
			}
			
		    echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}
	function updateEmployee()
	{		
		$result["result"]='';

		
		if (isset($_FILES["Imageup"]) && strlen($_FILES["Imageup"]["name"])>0)
		{

		    $file = $_FILES["Image"];
		    $nombre = $file["name"] ;
		    $tipo = $file["type"];
		    $ruta_provisional = $file["tmp_name"];
		    $size = $file["size"];
		    $dimensiones = getimagesize($ruta_provisional);
		    $width = $dimensiones[0];
		    $height = $dimensiones[1];
		    $carpeta = "user_assets/images/Employee/";

		    
		    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
		    {
		      $result["result"]= "Error, el archivo no es una imagen"; 
		    }
		    else if ($size > 1024*1024)
		    {
		      $result["result"]="Error, el tamaño máximo permitido es un 1MB";
		    }
		    else if ($width > 500 || $height > 500)
		    {
		        $result["result"]= "Error la anchura y la altura maxima permitida es 500px";
		    }
		    else if($width < 60 || $height < 60)
		    {
		        $result["result"]= "Error la anchura y la altura mínima permitida es 60px";
		    }
		    else

		    {	

		        $src = $carpeta.$_POST['posid'].$_POST['name'].$nombre;
		        move_uploaded_file($ruta_provisional, $src);


			    $data['photo']="/".$src;

		    }

		}


			if( $result["result"]=="")
			{
				$data['firstname']=$_POST['nameup'];
				$data['lastname']=$_POST['lastnameup'];
				$data['gender']=$_POST['genderup'];
				$data['stafftypeid']=$_POST['staffTypeup'];
				$data['active']=1;
			    if(update_data('mystaffpos',$data,array('mystaffposid' =>$_POST['mystaffposid'])))
				{
					$result["result"]="0";
				}
				else 
				{
					$result["result"]= "1";
				}
			}
			
		    echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}
	function pricehistory()
	{
		$itemPosId=$_POST['itemPosId'];
		$isitem=$_POST['type'];

		$available=$this->db->query("select * from itemprice where itemid = $itemPosId and isitem=$isitem order by datetime desc ")->result_array();

		if (count($available)==0) {
			$data['html']='<h1 align="center">No Available Price</1>';
			$data['result']=false;
			echo json_encode($data);
			return;
		}

		$html='';
		$html.='<br>
				<div  class="graph">
				<div style="height: 200px"  class="table-responsive">
						
						<table class="table table-bordered">
								<thead>
										<tr>
												<th>#</th>
												<th>Price </th>
												<th>Date Time</th>
										</tr>
															 </thead>
								<tbody>';
					$i=0;
					foreach ($available as  $value) {
						$i++;
						$html.=' <tr class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.
							' </th> <td>'.$value['price'].'  </td> <td>'.date("F j, Y, g:i a",strtotime($value['datetime'])).'</td>  </tr>';


					}
					$html.='</tbody>
									</table>
									</div> 
									</div>';
					$data['html']=$html;
					$data['result']=true;
					echo json_encode($data);
	}
	function savePrice()
	{		
		$result["result"]='';

		$data['itemid']=$_POST['itemid'];
		$data['price']=$_POST['price'];
		$data['isitem']=1;

		if(insert_data('itemprice',$data))
		{
			$result["result"]="0";
		}
		else 
		{
			$result["result"]= "1";
		}


		echo json_encode($result);

		# mystaffposid, firstname, lastname, gender, stafftypeid, myposid, active, photo

	
	}

}



