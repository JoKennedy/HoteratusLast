<link href="<?php echo base_url();?>user_asset/back/css/style.css" rel='stylesheet' type='text/css' />
<script type="text/javascript"> 
	showWait();
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

.cm-spinner {
  height: 150px;
  width: 150px;
  border: 3px solid transparent;
  border-radius: 50%;
  border-top: 4px solid #f15e41;
  -webkit-animation: spin 4s linear infinite;
  animation: spin 4s linear infinite;
  position: relative;
}

.cm-spinner::before,
.cm-spinner::after {
  content: "";
  position: absolute;
  top: 6px;
  bottom: 6px;
  left: 6px;
  right: 6px;
  border-radius: 50%;
  border: 4px solid transparent;
}

.cm-spinner::before {
  border-top-color: #bad375;
  -webkit-animation: 3s spin linear infinite;
  animation: 3s spin linear infinite;
}

.cm-spinner::after {
  border-top-color: #26a9e0;
  -webkit-animation: spin 1.5s linear infinite;
  animation: spin 1.5s linear infinite;
}   
             
@-webkit-keyframes spin {
    from {
        -webkit-transform: rotate(0deg);
         transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}   
        
@keyframes spin {
    from {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
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
			<button id="btn-reservation" class="btn btn-primary btn-lg"><span class="fa fa-hotel"> </span> <?=$this->lang->line('reservation')?></button>
			<button id="btn-description" class="btn btn-primary btn-lg"><span class="fa fa-align-left"></span> <?=$this->lang->line('description')?></button>
			<button id="btn-photos" class="btn btn-primary btn-lg"><span class="fa fa-camera"></span> <?=$this->lang->line('photos')?></button>
			<button id="btn-maps" class="btn btn-primary btn-lg"><span class="fa fa-map-marker"></span> <?=$this->lang->line('maps')?></button>
		</center>
	</div>
</div>


<div class="container" id="reservation">


        <div class="modal-content">
        
            <div >
                <center><h2><span class="label label-primary"><?=$this->lang->line('makereservation')?></span></h2></center>

            </div>

            <div class="graph-form">
                <form id="ReserveC">
                    <input type="hidden" name="roomid" id="roomid"  value="<?=$roomid?>">
                    <input type="hidden" name="rateid" id="rateid"  value="<?=$rateid?>">
                    <input type="hidden" name="checkin" id="checkin"  value="<?=$date1?>">
                    <input type="hidden" name="checkout" id="checkout"  value="<?=$date2?>">
                    <input type="hidden" name="child" id="child"  value="<?=$children?>">
                    <input type="hidden" name="numroom" id="numroom" value="<?=$numroom?>">
                    <input type="hidden" name="totalstay" id="totalstay" value="<?=$totalstay?>">
                    <input type="hidden" name="adult" id="adult" value="<?=$guests?>">
                    <input type="hidden" name="adult" id="adult" value="<?=$guests?>">
                    <input type="hidden" name="paymentTypeId" id="paymentTypeId" >
                    <input type="hidden" name="providerid" id="providerid" >
                    <input type="hidden" name="hotelid" id="hotelid" value="<?=$hotel_id?>">
                    <input type="hidden" name="currency" id="currency" value="<?=$currency?>"> 

                    <div style="float: left; width: 65%;">
                        <h4><span ><?=$this->lang->line('mainguestv')?></span></h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('firstname')?></label>
                            <input style="background:white; color:black;" name="firstname" id="firstname" type="text" placeholder="<?=$this->lang->line('firstname')?>" required="">
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('lastname')?></label>
                            <input style="background:white; color:black;" name="lastname" id="lastname" type="text" placeholder="<?=$this->lang->line('lastname')?>" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('phone')?></label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="phone" id="phone" type="text" placeholder="<?=$this->lang->line('phone')?> Number" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('email')?></label>
                            <input style="background:white; color:black;"  name="email" id="email" type="text" placeholder="<?=$this->lang->line('email')?>" required="" >
                        </div>
                        <div class="clearfix"></div>
                        <hr size="40">
                        <div id="guestnames">
                        	<?php

                        		if($guests>1){
                        			echo '<h4>'.$this->lang->line('additionalname').' </h4>';

                        			for ($i=1; $i < $guests; $i++) { 
                        				echo '<div class="col-md-6 form-group1"><label class="control-label">'.$this->lang->line('guests').' #'.$i.'</label><input style="background:white; color:black;" name="guestname[]" id="guestname" type="text" placeholder="'.$this->lang->line('typeguest').' #'.$i.'" required=""></div>';
                        			}

                        			echo '<div class="clearfix"></div> <hr size="40">';
                        		}

                        	?>
                        </div>
                        <input type="hidden" name="sourceid" value="1">
                        
                        <h4><?=$this->lang->line('addressinfo')?></h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('street')?></label>
                            <input style="background:white; color:black;"  name="address" id="address" type="text" placeholder="<?=$this->lang->line('street')?>" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('city')?></label>
                            <input style="background:white; color:black;"  name="city" id="city" type="text" placeholder="<?=$this->lang->line('city')?>" required="" >
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><?=$this->lang->line('state')?></label>
                            <input style="background:white; color:black;"  name="state" id="state" type="text" placeholder="<?=$this->lang->line('state')?>" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label style="padding:4px;" class="control-label controls"><?=$this->lang->line('country')?></label>
                            <select style="width: 100%; padding: 9px;" name="countryid" id="countryid">
                                <?php

                                    $Country=$this->db->query("select * from country order by country_name")->result_array();

                                    echo '<option  value="0" >'.$this->lang->line('selectcountry').'</option>';
                                    foreach ($Country as $value) {
                                        $i++;
                                        echo '<option value="'.$value['id'].'" >'.$value['country_name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-8 form-group1">
                            <label class="control-label"><?=$this->lang->line('zipcode')?></label>
                            <br>
                            <input style="background:white; color:black; "  name="zipcode" id="zipcode" type="text" placeholder="<?=$this->lang->line('zipcode')?>" required="">
                        </div>
                        <div class="col-md-4 form-group1">
                            <label class="control-label" style=" padding: 3px; " ><?=$this->lang->line('arrivatime')?></label>
                            <input style="width: 100%; padding: 9px; background:white; color:black;" name="arrival" id="arrival" type="time"  required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><?=$this->lang->line('notes')?></label>
                            <textarea id="note" name="note" placeholder="<?=$this->lang->line('typenotes')?>"></textarea>
                        </div>    
                    </div>
                    <div style="float: left; width: 35%; text-align: left;" class="graph">

                        

                       <center><h3><span class="label label-default"><?=$this->lang->line('inforecap')?></span></h3></center>
                        <hr>
                        <table>
                            <tbody>
                                <tr style="padding: 2px;">
                                    <td><strong><?=$this->lang->line('checkin')?>:</strong></td>
                                    <td style="text-align: right;"><?=date('m/d/Y',strtotime($date1))?></td>
                                </tr>
                                <tr>
                                    <td><strong><?=$this->lang->line('checkout')?>:</strong></td>
                                    <td style="text-align: right;"><?=date('m/d/Y',strtotime($date2))?></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <hr size="20">
                        <h5><strong ><?=$this->lang->line('charges')?></strong></h5>
                        <table>
                            <tbody>
                                <tr style="padding-bottom: 5px;">
                                    <td><strong><?=$numroom.' '.($numroom==1?$this->lang->line('room'):$this->lang->line('rooms')).' x '.$numnight.' '.($numnight==1?$this->lang->line('night'):$this->lang->line('nights'))?> </td>
                                    <td style="text-align: right;"> &nbsp;<?=$currency.number_format(($totalstay*$numroom),2,'.',',')?></td>
                                </tr>
							
							<?php
								if(count($taxes)>0)
								{	$totaltaxes=0;
									
									foreach ($taxes as $tax) {
										$taxamount=($totalstay*$numroom)*($tax['taxrate']/100);
										echo ' <tr style="padding-bottom: 5px;">
			                                    <td><strong>'.$tax['name'].' </td>
			                                    <td style="text-align: right;"> &nbsp;'.$currency.number_format($taxamount,2,'.',',').'</td>
			                                </tr>';
			                             $totaltaxes+=($tax['includedprice']==0?$taxamount:0);   
									}
									
								}
							?>
							<div class="clearfix"></div>
                            </tbody>
                        </table>
                        
                        <div style="text-align: center;">

                            <h2><?=$this->lang->line('duenow')?></h2>
                            <center><h4><span class="label label-default"> <?=$currency.number_format(($totalstay*$numroom)+ $totaltaxes,2,'.',',')?></span></h4></center>
                         
                         
                        </div>
                                                 
                        <div class="buttons-ui">
                            <a onclick="checkout();" class="btn green">Check Out</a>
                        </div>
                        
                    </div>
                    <div class="clearfix"> </div>
                    <div id="checkoutid" class="modal fade" role="dialog" style="z-index: 1400;">
                        <div class="modal-dialog modal-lg" >
                            <div class="modal-content">
                                <?php include("paymentapplication.php")?>
                            </div>
                        </div>
                    </div>
                  
                </form>
            </div>
</div>

</div>
<div class="container" id="description" style="display: none">
	<div class="container jumbotron">
		<center><h1><span class="label label-warning"><?=$this->lang->line('description')?></span></h1></center>
		<br>
		<?= (isset($booking['description'])) ? $booking['description'] : '' ?>
	</div>
</div>
<div class="container" id="photos" style="display: none">
	  <center><h1><span class="label label-warning"><?=$this->lang->line('photos')?></span></h1></center>

	<?php 

	$allroom=$this->db->query("select * from manage_property where hotel_id=".$hotel['hotel_id'].' ')->result_array();
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



<script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/galeriaimg.js"></script>
<script>
	
	function reservationDone()
	{//4242424242424242
        
        
        $("#paymentTypeId").val(2);
        $("#providerid").val(5);
		var data = $("#ReserveC").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>booking/saveReservation",
            data: data,
            beforeSend: function() {
                showWait('<?=$this->lang->line('savingreservation')?>');
                setTimeout(function() { unShowWait(); }, 100000);
            },
            success: function(msg) {
                unShowWait();
               if (msg['success']) {
                 swal({
                        title: "Success",
                        text: "<?=$this->lang->line('reservationcreated')?>",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        window.location.href ="<?=lang_url()?>booking/widget/<?=insep_encode($hotel_id)?>";
                    });
               }
               else
               {
                    swal({
                        title: "Error",
                        text: "Reservation was not created!",
                        icon: "danger",
                        button: "Ok!",
                    });
               }
            }
        });
	}
	function checkout()
	{
		
		  if ($.trim($("#firstname").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('firstname'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#firstname").focus();
                });
                return;
            }
           else  if ($.trim($("#lastname").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('lastname'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#lastname").focus();
                });
                return;
            }
            else  if ($.trim($("#phone").val())=='' || $("#phone").val().length<10) {
            	if($.trim($("#phone").val())=='')
            	{
            		 swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('phone'))?>',
                    icon: "warning",
                    button: "Ok!",
	                }).then((n) => {
	                    $("#phone").focus();
	                });
	                return;
            	}
               	else if($("#phone").val().length<10)
            	{
            		 swal({
                    title: "Upps",
                    text: '<?=$this->lang->line('minimum10')?>',
                    icon: "warning",
                    button: "Ok!",
	                }).then((n) => {
	                    $("#phone").focus();
	                });
	                return;
            	}
            }
            else  if ($.trim($("#email").val())=='' || !validar_email($("#email").val())) {
            	if($.trim($("#email").val())=='')
            	{
            		 swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('email'))?>',
                    icon: "warning",
                    button: "Ok!",
	                }).then((n) => {
	                    $("#email").focus();
	                });
	                return;
            	}
               	else if(!validar_email($("#email").val()))
            	{
            		 swal({
                    title: "Upps",
                    text: '<?=$this->lang->line('emailinvalid')?>',
                    icon: "warning",
                    button: "Ok!",
	                }).then((n) => {
	                    $("#email").focus();
	                });
	                return;
            	}
            }
            else  if ($.trim($("#arrival").val())=='' || $("#arrival").val().length==0) {
            
            		 swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('arrivatime'))?>',
                    icon: "warning",
                    button: "Ok!",
	                }).then((n) => {
	                    $("#arrival").focus();
	                });
	                return;
            }

           

		$("#checkoutid").modal().fadeIn();
	}
	function processpayment()
	{
		 var d = new Date();
		  if ($.trim($("#ccholder").val())=='') {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('ccname'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#ccholder").focus();
                });
                return;
            }
           else  if ($.trim($("#ccnumber").val())=='' ) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('ccnumber'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#ccnumber").focus();
                });
                return;
            }
            else  if ($.trim($("#cccvv").val())=='' || $("#cccvv").val().length<3  ) {
                swal({
                    title: "Upps",
                    text: '<?=sprintf($this->lang->line('missingfield'),$this->lang->line('cccvv'))?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#ccnumber").focus();
                });
                return;
            }
            else  if ($("#ccyear").val()== d.getFullYear() && $("#ccmonth").val()<(d.getMonth()+1)) {
                swal({
                    title: "Upps",
                    text: '<?=$this->lang->line('expiredcc')?>',
                    icon: "warning",
                    button: "Ok!",
                }).then((n) => {
                    $("#ccyear").focus();
                });
                return;
            }

            $.ajax({
                type: "POST",
              dataType: "json",
                url: "<?php echo lang_url(); ?>reservation/ValidateCreditCard",
                data: {'cccard':$("#ccnumber").val()},
                success: function(msg) {
                   if (!msg['success']) {
                     swal({
                            title: "Warning",
                            text: '<?=$this->lang->line('ccinvalid')?>!!',
                            icon: "warning",
                            button: "Ok!",
                        }).then((n) => {
                    		$("#ccnumber").focus();
                		});

                     return;
                   }
                   else
                   {
                        reservationDone();
                   }
                  
                }
            });
    

	}

function validar_email( email ) 
{
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}

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

<?php 
	$location = explode(",",$hotel['map_location']);
	$lat = array_shift($location);
	$lng = array_shift($location);
?>

function myMap() {
  var mapCanvas = document.getElementById("map");

}
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


myMap();
</script>