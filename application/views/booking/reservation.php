<?php
	$booking['background_type'] = (isset($booking['background_type'])) ? $booking['background_type'] : '0';
	$booking['background'] = (isset($booking['background'])) ? $booking['background'] : 'ffffff';

	if($booking['background_type'] == '0'){
		$booking['background'] = '#'.$booking['background'];
	}else{
		$booking['background'] = 'url('.base_url('uploads/'.$booking['background']).')';
	};
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
		background: #<?= (isset($booking['header_color'])) ? $booking['header_color'] : '' ?>;
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

	/*.form-control{
		width: auto;
		padding-right: 5px;
		padding-left: 5px;
	}*/


  
    #slideShowImages img { /* The following CSS rules are optional. */
      border: 0.8em black solid;
      padding: 3px;
    }  
</style>


	

											

<div class="container" style="height: 30px;	background-color: #E5E3E2">
	
</div>
<div class="container" style="padding: 40px">
	<div class="logo col-sm-12 col-md-2">
		<img <?= (isset($booking['logo'])) ? 'src="'.base_url('uploads/'.$booking['logo']).'"' : '' ?> width="120" height="100">
	</div>
	<div class="logo col-sm-12 col-md-2">
		<h5><?= (isset($property['property_name'])) ? $property['property_name'] : '--' ?> || <?= ($hotel['property_name']) ? $hotel['property_name'] : '--' ?></h5>
		<h5><?= (isset($hotel['address'])) ? $hotel['address'] : '--' ?></h5>
		<h5><span class="fa fa-phone"></span> <?= (isset($hotel['mobile'])) ? $hotel['mobile'] : '--' ?></h5>
		<h5><span class="fa fa-envelope-o"></span> <?= (isset($hotel['email_address'])) ? $hotel['email_address'] : '--' ?></h5>
		<h5><span class="fa fa-globe"></span> <?= (isset($hotel['web_site'])) ? $hotel['web_site'] : '--' ?></h5>
	</div>
	<div class="col-sm-6 pull-right hidden-xs hidden-sm" style="margin-top: 15px;">
		<button id="btn-description" class="btn btn-primary btn-lg" onclick="return showdescripcion()"><span class="fa fa-align-left"></span> Description</button>
		<button id="btn-photos" class="btn btn-primary btn-lg" onclick="return showphoto()"><span class="fa fa-camera"></span> Photos</button>
		<button id="btn-maps" class="btn btn-primary btn-lg" onclick="return showmap()"><span class="fa fa-map-marker"></span> Maps</button>
	</div>
</div>
	<form method ="post" action=" <?php echo lang_url();?>booking/paymentprocess"  id="reserve_info">
<div class="head container">
	<h3>Make a Reservation</h3>
</div>
<div class="container" id="reservation">
	<div class="col-sm-6">
		<h4>Guest Information </h4>
		<div class="form-group" >
			<div class="col-sm-12" style="margin-bottom: 20px;">
				<input type="text" name="name" class="form-control" placeholder="Full Name" id="name" required="true" >	
			</div>

			<div class="col-sm-6">
				<input type="tel" name="phone" class="form-control" placeholder="Phone" id="phone" required="true"
				minlength="10" maxlength="15" onchange=" this.value = this.value.replace(/[^0-9]/g,'');">
	
			</div>
			<div class="col-sm-6" style="margin-bottom: 20px;">
				<input type="email" name="email" class="form-control" placeholder="Email"  id ="email" data-validation="email" required="true" >
				<span id="emailOK"></span>
			</div>
		</div>
		
		<h5>Address Information</h5>
		<div class="form-group">
			<div class="col-md-6 col-sm-6"><input name="street_name" type="text" class="form-control"  placeholder="Street Address"> </div>
            

            <div class="col-md-6 col-sm-6"><input name="city_name" type="text" class="form-control"  placeholder="City"><!--<select name="city_name" class="form-control" ><option value=""> Select City</option> </select>--></div>  
            <br><br><br>
            <div class="col-md-6 col-sm-6"><input name="province" type="text" class="form-control"  placeholder="State"><!--<select name="province" class="form-control" ><option value=""> Select State</option> </select>--></div>


			<div class="col-md-6 col-sm-6"><select name="country" class="form-control" ><option value=""> Select Country</option> 
			<?php $countrys = get_data('country')->result_array();
			foreach($countrys as $value) { 
			extract($value);?>
			<option value="<?php echo $id;?>"><?php echo $country_name;?></option>
			<?php } ?>
			</select></div>
			<br><br><br>


			<div class="col-md-6 col-sm-6"><input name="zipcode" type="text" class="form-control"  placeholder="Zip Code"> </div>

			<div class="col-md-6 col-sm-6"><input name="arrivaltime" type="text" class="form-control"   placeholder="Arrival Time" required="true">
			</div>
			<br><br><br>

			<div class="col-sm-12">
			<h5 style="margin-top: 0px;">Notes</h5>
			<p> <textarea name="notes" class="form-control" style="height:150px;"></textarea> </p>
			</div>
		</div>

		
	</div>
	<div class="col-sm-6">
		<h4>Reservation</h4>
		<div class="table-responsive">
			<table class="table">
				<tr>
					<td>Check-in	:	</td>
					<td>	<?= $_POST['dp1']; ?></td>
				</tr>
				<tr>
					<td>Check-out	:	</td>
					<td>	<?= $_POST['dp2']; ?></td>
				</tr>
				<tr>
					<td><?php echo($guests >1?"Adult":"Adults"); ?>	:	</td>
					<td>	<?= $guests; ?></td>
				</tr>
			</table>
		</div>

		<h4>Charges</h4>
		<div id="reservation_price">
		<div class="table-responsive">
			<table class="table">
				<tr>
					<td> <?php echo $_POST['night'].' '.($_POST['night']==1?'Night':'Nights');  ?> </td>
					<td>$<?= $_POST['amount'] * $_POST['night']; ?></td>
				</tr>
			</table>
		</div>

		<?php $extras = get_data("room_extras", array("room_id"=>$_POST['room']))->result_array();

			if(count($extras)>0)
			{
				echo "<h4>Extras</h4>";
			}
		 ?>
		
		<div class="table-responsive">
			<table class="table">
				<tbody>
				<?php 
					

					foreach($extras as $extra){
						switch($extra['structure']){
							case '1':
								$structure = 'Per Person';
								$subtotal = $extra['price']*intval($_POST['guests']);
							break;

							case '2':
								$structure = 'Per Night';
								$subtotal = $extra['price']*intval($_POST['night']);
							break;

							case '3':
								$structure = 'Per Stay';
								$subtotal = $extra['price'];
						}

						$total = $subtotal*intval($extra['taxes'])/100+$subtotal;
						echo '<tr>';
							echo '<td><input type="checkbox" name="extra['.$extra['extra_id'].']" value="'.$total.'" onclick ="return addExtra(this)" ></td>';
							echo '<td>'.$extra['name'].'('.$structure.')<td>';
							echo '<td>$'.$extra['price'].'</td>';
							echo '<td>$'.$subtotal.'</td>';
							echo '<td>%'.$extra['taxes'].'</td>';
							echo '<td>$'.$total.'</td>';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<td>Grand Total:</td>
						<td id="">$  <span id="grand_total"></span> </td>
					</tr>
				</table>
			</div>
		</div>
		<input type="hidden" name="roomid" value="<?php echo $property['property_id']; ?>" >
		<input type="hidden" name="rateid" value="<?php echo $rateid; ?>" >
		<input type="hidden" name="hotelid" value="<?php echo $hotel['hotel_id']; ?>" >
		<input type="hidden" name="night" value="<?php echo $_POST['night']; ?>" >
		<input type="hidden" name="date1" value="<?php echo $_POST['dp1']; ?>" >
		<input type="hidden" name="date2" value="<?php echo $_POST['dp2']; ?>" >
		<input type="hidden" name="guests" value="<?php echo $_POST['guests']; ?>" >
		<input type="hidden" name="children" value="<?php echo $_POST['children']; ?>" >
		<input type="hidden" name="amount" value="<?php echo $_POST['amount']; ?>" >
		<input type="hidden" name="detailsprice" value="<?php echo $_POST['price']; ?>" >
			<div class="col-sm-12">
			<button type="submit"  class="btn btn-primary" onclick=" return Validar();"> Book </button> 
			</div>

	</form>


	</div>
</div>
</div>
<div class="container" id="description" style="display: none">
	<div class="container jumbotron">
		<h3>Description</h3>
		<?= ( isset($property['description']) ? $property['description'] : '') ?>
	</div>
</div>
<div class="container" id="photos" style="display: none">
	<h3>Photos</h3>

	<div id="slideShowImages">
	<?php
	$hotel_photos = $this->db->query("select * from room_photos where room_id = ".$property['property_id'])->row_array();

	if(count($hotel_photos)!=0) 
	{
		
		$photos = explode(',',$hotel_photos['photo_names']); 
		foreach($photos as $val)
		{
			
		?>

	
	<div>
   		<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" alt="#"  title="#"/>
	</div>





	<?php 			
		}
		
	}
	else
	{
		echo "<h3>This room has no images </h3>";
	}
	
	?>
	  </div>  				

</div>
<div class="container" id="maps" style="display: none">
	<div class="container jumbotron">
		<h3>Maps</h3>
		<div id="map" style="width:100%;height:400px"></div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv3fAYIfLl5H6SmuZKJtUIx6MyNn9xDbs" type="text/javascript"></script>


<script type="text/javascript">

$('#grand_total').html(<?php echo $_POST['amount'] * $_POST['night']; ?>);

function showdescripcion()
{
	
	$("#photos").hide();
	$("#maps").hide();

	if ($("#description").is(":visible"))
	{
		$("#description").hide();
		$("#reservation").show();
	}
	else
	{
		$("#description").show();
		$("#reservation").hide();
	}

}

function showphoto()
{
	
	$("#maps").hide();
	$("#description").hide();

	if ($("#photos").is(":visible"))
	{
		$("#photos").hide();
		$("#reservation").show();
	}
	else
	{
		$("#photos").show();
		$("#reservation").hide();
	}

}

function showmap()
{
	
	$("#photos").hide();
	$("#description").hide();

	if ($("#maps").is(":visible"))
	{
		$("#maps").hide();
		$("#reservation").show();
	}
	else
	{
		$("#maps").show();
		$("#reservation").hide();
	}

}


function addExtra(valor){

	var monto=document.getElementById("grand_total").innerHTML;
	var suma =0;
	if (valor.checked)
	{
		
		suma=(parseFloat(monto)+parseFloat(valor.value));

	}
	else
	{
		suma=(parseFloat(monto)-parseFloat(valor.value));
	}

	$('#grand_total').html(suma);
 
}



function myMap() {
  var mapCanvas = document.getElementById("map");
/*
  var mapOptions = {
    center: new google.maps.LatLng(<?php $lat; ?>, <?php $lng; ?>),
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

$.validator.addMethod("customemail", 
  function(value, element) {
    return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
  }, 
  "Sorry, I've enabled very strict email validation"
);


jQuery.validator.addMethod("alphanumeric", function(value, element) {
			return this.optional(element) || /^[0-9\+]+$/i.test(value);
			}, "Numbers, and plues only please");

myMap();





function Validar()
{
	if($('#reserve_info').valid())
	{
		
	}
	else
	{
		
	}
}

$('#reserve_info').validate({

rules:
{
	name:
	{
	  required:true
	},
	phone:
	{
		required:true,
		alphanumeric : true,
		minlength:10,
		maxlength:15
	},
	email:
	{
		required:true,
		customemail:true
	}

},
errorPlacement: function (error, element) {
  return false;
},
highlight: function (element) { // hightlight error inputs
      $(element)
        .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
    },
unhighlight: function (element) { // revert the change done by hightlight
      $(element)
        .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
    },
});


</script>

