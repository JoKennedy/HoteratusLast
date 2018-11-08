	
<script type="text/javascript"> 
	showWait('<?=$this->lang->line('lookingfor')?>');
    setTimeout(function() { unShowWait(); }, 10000);
    jQuery(document).ready(function($) {
       unShowWait();
	});
</script>

	
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
			
			$resrve = $this->booking->get_reserve();
	

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

<div class="container graph" style="padding: 40px; ">
	<div class="logo col-sm-12 col-md-2">
		<?php
			 echo '<img  width="120" height="100" src="'.base_url().(strlen($hotel['Logo'])<5?"uploads/room_photos/noimage.jpg":$hotel['Logo']).'"" class="img-responsive" alt="">'
		?>
	</div>
	<div class="logo col-sm-12 col-md-2">
		<h5><?= (isset($hotel['property_name'])) ? $hotel['property_name'] : '--' ?></h5>
		<h5><?= (isset($hotel['address'])) ? $hotel['address'] : '--' ?></h5>
		<h5><span class="fa fa-phone"></span> <?= (isset($hotel['mobile'])) ? $hotel['mobile'] : '--' ?></h5>
		<h5><span class="fa fa-envelope-o"></span> <?= (isset($hotel['email_address'])) ? $hotel['email_address'] : '--' ?></h5>
		<h5><span class="fa fa-globe"></span> <?= (isset($hotel['web_site'])) ? $hotel['web_site'] : '--' ?></h5>
	</div>

	<div class="col-sm-12 hidden-xs hidden-sm" style="margin-top: 15px; ">
		<center>
			<button id="btn-reservation" class="btn btn-primary btn-lg"><span class="fa fa-hotel"> </span> <?=$this->lang->line('available')?></button>
			<button id="btn-description" class="btn btn-primary btn-lg"><span class="fa fa-align-left"></span> <?=$this->lang->line('description')?></button>
			<button id="btn-photos" class="btn btn-primary btn-lg"><span class="fa fa-camera"></span> <?=$this->lang->line('photos')?></button>
			<button id="btn-maps" class="btn btn-primary btn-lg"><span class="fa fa-map-marker"></span> <?=$this->lang->line('maps')?></button>
		</center>
	</div>
</div>
<div class="bor-dash mar-bot20"></div>
<div class="clearfix"></div>
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
				<?php
				$qry = $this->db->query("SELECT max(existing_room_count) as roommax , max(member_count) as membermax, max(children) as childrenmax
                            FROM `manage_property` WHERE  `hotel_id`='".$hotel_id."'")->result_array();
                        $roommax=$qry[0]['roommax'];
                        $membermax=$qry[0]['membermax'];
                        $childrenmax=$qry[0]['childrenmax'];
                        for ($i=1; $i<=$roommax; $i++) { 
                              echo '<option value="'.$i.'" '.($rooms==$i?'selected':'').'>'.$i.' '.($i==1?$this->lang->line('room'):$this->lang->line('rooms')). '</option>';
                            }
                   ?>
				
			</select>
		</div>
		<div class="col-sm-2  hidden-xs hidden-sm" style="width: auto">
			<select name="num_person" id="" class="form-control ">
			
			<?php
			$numAdult = $membermax;
			for ($i=1; $i<=$numAdult; $i++) { 
			echo '<option value="'.$i.'" '.($adult==$i?'selected':'').'>'.$i.' '.($i==1?$this->lang->line('adult'):$this->lang->line('adults')). '</option>';

			} ?>
			</select>
		</div>
		<div class="col-sm-2  hidden-xs hidden-sm" style="width: auto">
			<select name="num_child" id="" class="form-control ">
				<option value="0" id="" <?=($child==0?'selected':'')?> > <?=$this->lang->line('nochildren')?></option>
				<?php 
					      $numChild = $childrenmax;
					      for ($i=1; $i<=$numChild; $i++) { 
					        echo '<option value="'.$i.'" '.($child==$i?'selected':'').'>'.($i==1?$this->lang->line('child'):$this->lang->line('children')).'</option>';
					      } ?>
			</select> 
		</div>
		<div class="col-xs-2">
			<input type="hidden" name="hotel_id" value="<?= $_POST["hotel_id"]; ?>">
			<input type="hidden" name="currencyid" value="<?=$hotel['currency']?>">
			<input type="submit" value="<?=$this->lang->line('search')?>" class="btn btn-success">
		</div>
	</form>
</div>
<div class="bor-dash"></div>
<div class="container" id="reservation">
	<?php 
			echo $resrve['detail'];
			
				
	?>
</div>
<div class="container" id="description" style="display: none">
	<div class="container jumbotron">
		<h3><?=$this->lang->line('description')?></h3>
		<?= (isset($booking['description'])) ? $booking['description'] : '' ?>
	</div>
</div>
<div class="container" id="photos" style="display: none">
	 <center><h1><span class="label label-warning"><?=$this->lang->line('photos')?></span></h1></center>

	<?php 

	$allroom=$this->db->query("select * from manage_property where hotel_id=".$hotel['hotel_id'].' order by `order`')->result_array();
		//
		
			foreach ($allroom as  $room) {
				
			$roomphotos=$this->db->query("SELECT * FROM room_photos where room_id =".$room['property_id'])->result_array();
			if(count($roomphotos)>0)
			{
			echo '<center><h3><span class="label label-primary">'.$room['property_name'].'</span></h3></center>';
		?>


		<CENTER>
                                         
          <div class="fotorama">
         <?php 

            $i=0;
            foreach ($roomphotos as $value) {
                $i++;
               
                echo ' <img src="'.base_url().$value['photo_names'].'" />';
            }

            echo '</div> </CENTER>';
        }//final if
        }//finale foer
         ?>                          
	
</div>
<div class="container" id="maps" style="display: none">
	  <div class="map-bottm">
        <h3 class="inner-tittle two">Map</h3>
        <div class="graph gllpMap">
            <iframe width="900" height="600" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/view?key=AIzaSyDYtqUCg0ts6msMJ-WY59w4BnUy5CS5O0Y&center=<?=$hotel['map_location']?>&zoom=20" allowfullscreen id="mapa">
            </iframe>
        </div>
    </div>
</div>


<form id="setreservation" style="display: none;" action="<?= lang_url().'booking/set_reservation'?>"  method="post">

	<input type="text" name="hotel_id" value="<?= $_POST["hotel_id"]; ?>">
	<input type="text" name="data" id="data">
	<input type="submit" value="<?=$this->lang->line('search')?>" class="btn btn-success">

</form>

<script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/galeriaimg.js"></script>
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
$("#btn-reservation").on("click", function(){
	$("#reservation").show();
	$("#photos").hide();
	$("#description").hide();
	$("#maps").hide();
});



function showdetails(id) {

    if ($("#" + id).css('display') == 'none') {
        $("#" + id).css('display', '');
    } else {
        $("#" + id).css('display', 'none');
    }

}


function datechange()
{
	var fecha = $("#dp1").datepicker("getDate");
    fecha.setDate(fecha.getDate() + 1); 
    $("#dp2").datepicker( "option", "minDate", fecha);
}
jQuery(function() {

    // llamada al plugin
    jQuery('.gridder').gridderExpander({
        scroll: true,  // activar/desactivar auto-scroll
        scrollOffset: 30,  // distancia en píxeles de margen al hacer scroll
        scrollTo: "panel", // hacia donde se hace el auto-scroll
        animationSpeed: 400, // duración de la animación al hacer clic en elemento
        animationEasing: "easeInOutExpo", // tipo de animación
        showNav: true,  // activar/desactivar navegación
        nextText: "<i class='fa fa-arrow-right'></i>", // texto para pasar a la siguiente imagen
        prevText: "<i class='fa fa-arrow-left'></i>", // texto para pasar a la imagen anterior
        closeText: "<i class='fa fa-times'></i>", // texto del botón para cerrar imagen expandida
        onStart: function(){
            //código que se ejecuta cuando Gridder se inicializa
        },
        onContent: function(){
            //código que se ejecuta cuando Gridder ha cargado el contenido
        },
        onClosed: function(){
            //código que se ejecuta al cerrar Gridder
        }
    });

});

function reservethis(roomid, rateid, date1, date2, adult, numroom, numchild, numnight,totalstay,idreplace) {

	var data =roomid+"*"+rateid+"*"+date1+"*"+date2+"*"+adult+"*"+numroom+"*"+numchild+"*"+numnight+"*"+totalstay+"*"+idreplace;
     $("#data").val(data);
     $("#setreservation").submit();

   /* $("#checkinr").html(date1.substring(8,10)+'/'+date1.substring(5,7)+'/'+date1.substring(0,4));
    $("#checkoutr").html(date2.substring(8,10)+'/'+date2.substring(5,7)+'/'+date2.substring(0,4));
    $("#chargeinfo").html(numnight + (numnight>1?' Nights':' Night') + ' x ' + numroom + (numroom>1?' Rooms':' Room'));
    $("#totalstay").html(formatMoney(totalstay*numroom));
    $("#totaldue").html(formatMoney(totalstay*numroom));
    $("#roomid").val(roomid);
    $("#rateid").val(rateid);
    $("#checkin").val(date1);
    $("#checkout").val(date2);
    $("#child").val(numchild);
    $("#numroom").val(numroom);
    $("#adult").val(adult);
    $("#allguest").html('');


    $("#guestnames").css('display',(adult<=1?'none':''));
    for (var i = 1; i < adult; i++) {
        
        $("#allguest").append( '');
    }
    $("#roomsavailable").modal().fadeOut();
    $("#infoReservation").modal();*/

}



</script>