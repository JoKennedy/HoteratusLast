	<?php

			$hotel_detail			=	get_data(HOTEL,array('hotel_id'=>$hotel_id))->row()->currency;
			
			if ($hotel_detail  !=0)
			{

				$currency	=	get_data(TBL_CUR,array('currency_id'=>$hotel_detail))->row()->symbol;
			}
			else
			{
				$currency="$";
			}

			
			

			$checkin_date	=	str_replace("/","-",$_POST['dp1']);
			
			$prev			=	date('d-m-Y', strtotime('-1 day', strtotime($checkin_date)));
			
			$checkout_date	=	str_replace("/","-",$_POST['dp2']);
			
			$start 			=	strtotime($checkin_date) ;
			
			$end 			=   strtotime($checkout_date);

			$date1 = date_create_from_format('m/d/Y', $_POST['dp1']); 
			$date2 = date_create_from_format('m/d/Y', $_POST['dp2']); 
			$diff = $date1->diff($date2);
			
			$nights 		= 	$diff->days;
			
			$rooms 			=	$_POST['num_rooms'];
		
			$adult 			=	$_POST['num_person'];
			
			$child 			=	$_POST['num_child'];
			

			$resrve			=	$this->booking->get_reserve();

			$booking['background_type'] = (isset($booking['background_type'])) ? $booking['background_type'] : '0';
			$booking['background'] = (isset($booking['background'])) ? $booking['background'] : 'ffffff';

			if($booking['background_type'] == '0'){
				$booking['background'] = '#'.$booking['background'];
			}else{
				$booking['background'] = 'url('.base_url('uploads/'.$booking['background']).')';
			}
	?>
<style type="text/css">
	body{
		background: <?= $booking['background']; ?>;
		background-size: cover;
	}

	.container{
		background: white;
		height: auto;
	}

	.head{
		background: #<?= (isset($booking['header_color'])) ? $booking['header_color'] : '2993BC' ?>;
		color: white;
		height: 60px; 
		padding: 3px;
		padding-left: 10px;
	}
	.logo{
		width: auto;
	}
	.form-search{
		margin-top: 10px;
	}

	.form-control{
		width: auto;
		padding-right: 5px;
		padding-left: 5px;
	}
</style>
<div class="container" style="height: 30px;	background-color: #E5E3E2">
	
</div>
<div class="container" style="padding: 40px">
	<div class="logo col-sm-12 col-md-2">
		<img <?= (isset($booking['logo'])) ? 'src="'.base_url('uploads/'.$booking['logo']).'"' : '' ?> width="120" height="100">
	</div>
	<div class="logo col-sm-12 col-md-2">
		<h5><?= (isset($hotel['property_name'])) ? $hotel['property_name'] : '--' ?></h5>
		<h5><?= (isset($hotel['address'])) ? $hotel['address'] : '--' ?></h5>
		<h5><span class="fa fa-phone"></span> <?= (isset($hotel['mobile'])) ? $hotel['mobile'] : '--' ?></h5>
		<h5><span class="fa fa-envelope-o"></span> <?= (isset($hotel['email_address'])) ? $hotel['email_address'] : '--' ?></h5>
		<h5><span class="fa fa-globe"></span> <?= (isset($hotel['web_site'])) ? $hotel['web_site'] : '--' ?></h5>
	</div>
	<div class="col-sm-6 pull-right hidden-xs hidden-sm" style="margin-top: 15px;">
		<button id="btn-description" class="btn btn-primary btn-lg"><span class="fa fa-align-left"></span> Description</button>
		<button id="btn-photos" class="btn btn-primary btn-lg"><span class="fa fa-camera"></span> Photos</button>
		<button id="btn-maps" class="btn btn-primary btn-lg"><span class="fa fa-map-marker"></span> Maps</button>
	</div>
</div>
<div class="head container">
	<form method="post" class="form-inline form-search">
		<div class="col-xs-2" style="width: auto">
			<input type="text" name="dp1" id="dp1" class="form-control" value="<?= $_POST['dp1']; ?>" onchange="return datechange();">
		</div>
		<div class="col-xs-2" style="width: auto">
			<input type="text" name="dp2" id="dp2" class="form-control " value="<?= $_POST['dp2']; ?>">
		</div>
		<div class="col-sm-2 hidden-xs" style="width: auto">
			<select name="num_rooms" id="" class="form-control ">
				<option value="1">1 Rooms</option>
				
			</select>
		</div>
		<div class="col-sm-2  hidden-xs hidden-sm" style="width: auto">
			<select name="num_person" id="" class="form-control ">
			<option value="<?= $adult?>"> <?= $adult?> Adult</option>
			<?php $qry1 = $this->db->query("SELECT max(member_count) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$hotel_id."'");
			$res1 = $qry1->result_array();
			$numAdult = $res1[0]['maxNumber'];
			for ($i=1; $i<=$numAdult; $i++) { 
			echo '<option value="'.$i.'">'.$i. ' Adult</option>';

			} ?>
			</select>
		</div>
		<div class="col-sm-2  hidden-xs hidden-sm" style="width: auto">
			<select name="num_child" id="" class="form-control ">
				<option value="<?= $child?>" id=""><?= $child?> Child</option>
				<?php $qry1 = $this->db->query("SELECT max(children) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$hotel_id."'");
								      $res1 = $qry1->result_array();
								      $numChild = $res1[0]['maxNumber'];
								      for ($i=1; $i<=$numChild; $i++) { 
								        echo '<option value="'.$i.'">'.$i. ' Child</option>';
								      } ?>
			</select> 
		</div>
		<div class="col-xs-2">
			<input type="hidden" name="hotel_id" value="<?= $_POST["hotel_id"]; ?>">
			<input type="submit" value="Search" class="btn btn-success">
		</div>
	</form>
</div>
<div class="container" id="reservation">
	<?php 
			if($resrve)
			{
				/* echo '<pre>'; */
				$a1=array("a"=>"red","b"=>"green");
				$a2=array("c"=>"blue","b"=>"yellow");
				$oneDimensionalArray = call_user_func_array('array_merge', $resrve);
				//echo array_sum(array_column($oneDimensionalArray, 'price'));
				/* print_r($oneDimensionalArray); */
				$newArray = array();
				$newArray1 = array();
				$i=0;
				foreach($oneDimensionalArray as $room_value) {
					if(@$room_value['rate_types_id']=='')
					{
						if(empty($room_value['price']) || $room_value['price']=='0.00')
						{	
							$newArray[$room_value['room_id'].'_'.$i]	=	$room_value['base_price'];
						}
						else
						{
							$newArray[$room_value['room_id'].'_'.$i]	=	$room_value['price'];
						}
					}
					else
					{
						if(empty($room_value['price']) || $room_value['price']=='0.00')
						{	
							$newArray1[$room_value['room_id'].'_'.$room_value['rate_types_id'].'_'.$i]	=	$room_value['base_price'];
						}
						else
						{
							$newArray1[$room_value['room_id'].'_'.$room_value['rate_types_id'].'_'.$i]	=	$room_value['price'];
						}
					}
					$i++;
				}
				/*echo $room_day_price;
				echo $rate_day_price;*/
				/*print_r($newArray);
				print_r($newArray1);*/
				$price = 0;	
				foreach($newArray as $key => $value){
				    $exp_key = explode('_', $key);
				    if($exp_key[0] == '3'){
				    	$price = $value;	
				         $arr_result[$exp_key[0]] = $price;
				    }
				}

				/*print_r($arr_result);
				echo '</pre>';	*/
				$i	=	0;
				$dis_room_id=array();
				$dis_rate_id=array();
				$room_price=0;
				foreach($oneDimensionalArray as $room_value) {
				
					$i++;
				
					if(@$room_value['rate_types_id']=='')
					{
						$room_name	=	$room_value['property_name'];
	
					}
					else
					{
						if($room_value['rate_name']=='')
						{
							$rate_name	=	'#'.$room_value['uniq_id'];
						}
						else
						{
							$rate_name	=	$room_value['rate_name'];
						}
						
						$room_name	=	$room_value['property_name'].' ( '.$rate_name.' ) ';
 					}

					if($room_value['image']=='')
					{
						$room_photo	=	$this->reservation_model->get_photo($room_value['room_id']);
						
						if($room_photo=="no") {
						
							if($room_value['image']=="") {
							
								$photo	=	'uploads/room_photos/noimage.jpg';
								
							}else {
							
								$photo	=	'uploads/room_type/'.$room_value['image'];
							}
						}else {
						
							$photo	=	'uploads/room_photos/'.$room_photo;
						}
					}
					else
					{
						$photo	=	'uploads/room_type/'.$room_value['image'];
					}
						if(!in_array($room_value['room_id'],$dis_room_id) || !in_array(@$room_value['rate_types_id'],$dis_rate_id))
						{

						if(@$room_value['rate_types_id']=='')
						{	
							$price		=	0;	
							$price_d	=	'';
							foreach($newArray as $key => $value){
							    $exp_key = explode('_', $key);
							    if($exp_key[0] == $room_value['room_id']){
									$price_d	.=	$value.',';
							    	$arr_result_day[$exp_key[0]] = $price_d;
							    	$price 		=	$value;	
							        $arr_result[$exp_key[0]] = $price;
							    }
							}
						}
						else
						{
							$price 		=	0;	
							$price_d	=	'';
							foreach($newArray1 as $key => $value){
							    $exp_key = explode('_', $key);
							    if($exp_key[0] == $room_value['room_id']){
									$price_d	.=	$value.',';
							    	$arr_result_day[$exp_key[0]] = $price_d;	
							    	$price 		= 	$value;
							        $arr_result[$exp_key[0]] = $price;
							    }
							}
						}

						echo	'<div class="room_info">
											<div class="row">
											<div class="col-md-3 col-sm-3">
											<div><a href="javascript:;"><img src="'.base_url().$photo.'" class="img-responsive" alt=""></a></div>
											</div>
											<div class="col-md-6 col-sm-6">
											<h4>'.$room_name.'</h4>
											<p> '.$room_value['member_count'].' Member Count </p>
											<p>Number of bedroom : '.$room_value['number_of_bedrooms']	.'</p>
											<button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExampled_'.$room_value['room_id'].$i.'" aria-expanded="false" aria-controls="collapseExample"> Room Info </button>
											</div>
											<div class="col-md-3 col-sm-3">
											<h6>Avg. per night</h6>
											<h2 id="changed_price_'.$room_value['room_id'].$i.'" class="'.$currency.'">'.$currency.''.$arr_result[$room_value['room_id']].'</h2>
											<form method ="post" action="'.lang_url().'booking/set_reservation'.'">
												<input type="hidden" name="rate" value="'.@$room_value['rate_types_id'].'">
												<input type="hidden" name="room" value="'.$room_value['room_id'].'">
												<input type="hidden" name="grand" value="'.$arr_result[$room_value['room_id']].'">
												<input type="hidden" name="rooms" value="'.$rooms.'" >
												<input type="hidden" name="night" value="'.$nights.'" >
												<input type="hidden" name="guests" value="'.$adult.'" >
												<input type="hidden" name="children" value="'.$child.'" >
												<input type="hidden" name="dp1" value="'.$checkin_date.'" >
												<input type="hidden" name="dp2" value="'.$checkout_date.'" >
												<input type="hidden" name="amount" value="'.$arr_result[$room_value['room_id']].'">
												<input type="hidden" name="price" value="'.insep_encode($arr_result_day[$room_value['room_id']]).'">
												<button type="submit" id="res_'.$room_value['room_id'].$i.'" class="btn btn-info "> Book This Room</button>
											</form>
											</div>
											</div>
											<div class="row"> 
											<div class="col-md-12 col-sm-12">
											<div class="collapse" id="collapseExampled_'.$room_value['room_id'].$i.'">
											<div class="more-info" style="display: block;">
											<div class="miProperties">    <div class="miProperties">
											<span class="detailtitle"><b>Description</b></span><br><span class="descDetail">
											'.$room_value['description'].'</span>
											</div>
											<div class="clear20"></div>
											</div>
											<div class="miSummary">
											<ul>
											<li>Check-in date<span>'.$checkin_date.'</span></li>
											<li>Check-out date<span> '.$checkout_date.'</span></li>
											<div class="clear10"></div>
											<li>Rooms<span>'.$rooms.'</span></li>
											<li>Guests<span>'.$adult.'</span></li>
											<li>Nights<span>'.$nights.'</span></li>
											<div class="clear10"></div>
											<li><b>Total</b><span><b> '.$currency.''.$arr_result[$room_value['room_id']]*$nights.'</b></span></li>
											</ul>
											</div>
											<div class="clear"></div>
											<div class="clear15"></div>
											</div>
											</div>
											</div>
											</div>
											<div class="bor-dash mar-bot20"></div>
								</div>';
								
					}
					array_push($dis_room_id,$room_value['room_id']);
					array_push($dis_rate_id,@$room_value['rate_types_id']);
				}		
			}
			else
			{
				echo '<div class="room_info">
						<div class="row" style="padding:30px;"> No Rooms are available..</div></div>';
				
			}
	?>
</div>
<div class="container" id="description" style="display: none">
	<div class="container jumbotron">
		<h3>Description</h3>
		<?= (isset($booking['description'])) ? $booking['description'] : '' ?>
	</div>
</div>
<div class="container" id="photos" style="display: none">
	<h3>Photos</h3>
</div>
<div class="container" id="maps" style="display: none">
	<div class="container jumbotron">
		<h3>Maps</h3>
		<div id="map" style="width:100%;height:400px"></div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv3fAYIfLl5H6SmuZKJtUIx6MyNn9xDbs" type="text/javascript"></script>
<script>
	$("#dp1").datepicker({
		minDate:new Date()
	});


	$("#dp2").datepicker({
		minDate:new Date()
	});


$("#btn-description").on("click", function(){
	$("#reservation").hide();
	$("#photos").hide();
	$("#maps").hide();
	$("#description").show();
});

$("#btn-photos").on("click", function(){
	$("#reservation").hide();
	$("#maps").hide();
	$("#description").hide();
	$("#photos").show();
});

$("#btn-maps").on("click", function(){
	$("#reservation").hide();
	$("#photos").hide();
	$("#description").hide();
	$("#maps").show();
});

<?php 
	$location = explode(",",$hotel['map_location']);
	$lat = array_shift($location);
	$lng = array_shift($location);
?>

function myMap() {
  var mapCanvas = document.getElementById("map");
 /* var mapOptions = {
    center: new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>),
    zoom: 7,
    panControl: false,
    zoomControl: true,
    mapTypeControl: false,
    scaleControl: false,
    streetViewControl: false,
    overviewMapControl: false,
    rotateControl: false   
  };
  var map = new google.maps.Map(mapCanvas, mapOptions);
  
  var marker = new google.maps.Marker({
    position: mapOptions.center,
    map: map
  });*/
}


function datechange()
{
	var fecha = $("#dp1").datepicker("getDate");
    fecha.setDate(fecha.getDate() + 1); 
    $("#dp2").datepicker( "option", "minDate", fecha);
}
myMap();
</script>