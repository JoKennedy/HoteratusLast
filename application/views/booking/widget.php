<?php  
	$widget['layout'] = (isset($widget['layout'])) ? $widget['layout'] : '0';
	switch($widget['layout']){
		case '0':
			$layout = 'col-xs-6';
		break;

		case '1':
			$layout = 'col-xs-12';
		break;

		case '2':
			$layout = 'col-xs-3';
		break;
	}

	$widget['floating_position'] = (isset($widget['floating_position'])) ? $widget['floating_position'] : '0';
	switch($widget['floating_position']){
		case '0':
			$floating = '';
		break;

		case '1':
			$floating = 'col-xs-6';
		break;

		case '2':
			$floating = 'col-xs-6 col-xs-offset-3';
		break;

		case '3':
			$floating = 'col-xs-6 col-xs-offset-6';
		break;
	}

	$widget['open_page'] = (isset($widget['open_page'])) ? $widget['open_page'] : '0';
	switch($widget['open_page']){
		case '0';
			$target = '_blank';
		break;

		case '1';
			$target = '_parent';
		break;
	}

	$widget['theme'] = (isset($widget['theme'])) ? $widget['theme'] : '0';
	switch($widget['theme']){
		case '0':
			$theme = 'default';
		break;
		case '1':
			$theme = 'primary';
		break;
		case '2':
			$theme = 'success';
		break;
		case '3':
			$theme = 'info';
		break;
		case '4':
			$theme = 'warning';
		break;
		case '5':
			$theme = 'danger';
	}

	$widget['font'] = (isset($widget['font'])) ? $widget['font'] : '0';
	switch($widget['font']){
		case '0':
			$font = '';
		break;
		case '1':
			$font = 'arial';
		break;
		case '2':
			$font = 'Times New Roman';
		break;
		case '3':
			$font = 'courier';
		break;
		case '4':
			$font = 'verdana';
	}
?>
<style>
	body{
		overflow: hidden;
		font-family: <?= $font; ?>;
	}
	.middle{
		margin-top: 30vh;
	}

	.bottom{
		margin-top: 75vh;
	}
	.datepicker{
		z-index: 2000;
	}
	<?= (isset($widget['custom_css'])) ? $widget['custom_css'] : '' ; ?>
</style>
<div class="<?= $floating; ?>"> 
	<div class="panel panel-<?= $theme; ?>">
		<?php  
			$widget['show_header'] = (isset($widget['show_header'])) ? $widget['show_header'] : '1';
			if($widget['show_header'] !== '0'){
				echo '<div class="panel-heading"><center>'.$this->lang->line('bookingonline').'</center></div>';
			}

		?>
		<div class="panel-body">
			<form action="<?= lang_url().'booking/roomsinformation'?>" target="<?= $target ?>" method="post">
				<div class="<?= $layout ?>">
					<div class="form-group">
						<label for="dp1"><?=$this->lang->line('checkin')?></label>
						<input type="text" required name="dp1" id="dp1" autocomplete="false" class="form-control" onchange="return datechange();">
					</div>
				</div>
				<div class="<?= $layout ?>">
					<div class="form-group">
						<label for="dp1"><?=$this->lang->line('checkout')?></label>
						<input type="text" required autocomplete="false"  name="dp2" id="dp2" class="form-control">
					</div> 
				</div>
				<?php
					echo '<div class="'.$layout.'">';
							echo '<div class="form-group">';
								echo '<label for="num_rooms">'.$this->lang->line('numberroom').'</label>';
								
								echo '<select name="num_rooms" class="form-control">';
									
									   $qry = $this->db->query("SELECT max(existing_room_count) as roommax , max(member_count) as membermax, max(children) as childrenmax
                            FROM `manage_property` WHERE  `hotel_id`='".$hotel['hotel_id']."'")->result_array();
                        $roommax=$qry[0]['roommax'];
                        $membermax=$qry[0]['membermax'];
                        $childrenmax=$qry[0]['childrenmax'];
                        for ($i=1; $i<=$roommax; $i++) { 
                              echo '<option value="'.$i.'">'.$i.' '.($i==1?$this->lang->line('room'):$this->lang->line('rooms')). '</option>';
                            }


								echo '</select>';

							echo '</div>';
						echo '</div>';

				?>

				<?php  
					$widget['guest_number'] = (isset($widget['guest_number'])) ? $widget['guest_number'] : '0';
					if($widget['guest_number'] !== '0'){
						echo '<div class="'.$layout.'">';
							echo '<div class="form-group">';
								echo '<label for="num_person">'.$this->lang->line('numberadult').'</label>';
								
								echo '<select name="num_person" class="form-control">';
									
									   $qry1 = $this->db->query("SELECT max(member_count) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$hotel['hotel_id']."'");
									      $res1 = $qry1->result_array();
									      $numAdult = $res1[0]['maxNumber'];
									      for ($i=1; $i<=$numAdult; $i++) { 
									        echo '<option value="'.$i.'">'.$i.' '.($i==1?$this->lang->line('adult'):$this->lang->line('adults')). '</option>';
									        
									      }


								echo '</select>';

								  

  

							echo '</div>';
						echo '</div>';
					}else{
						echo '<input type="hidden" name="num_person" value="1">';
					}

					$widget['ask_children'] = (isset($widget['ask_children'])) ? $widget['ask_children'] : '0';
					if($widget['ask_children'] !== '0'){
						echo '<div class="'.$layout.'">';
							echo '<div class="form-group">';
								echo '<label for="num_child">'.$this->lang->line('numberchildren').'</label>';
								echo '<select name="num_child" class="form-control">';
								echo '<option value="0">'.$this->lang->line('nochildren').'</option>';
									 $qry1 = $this->db->query("SELECT max(children) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$hotel['hotel_id']."'");
								      $res1 = $qry1->result_array();
								      $numChild = $res1[0]['maxNumber'];
								      for ($i=1; $i<=$numChild; $i++) { 
								        echo '<option value="'.$i.'">'.($i==1?$this->lang->line('child'):$this->lang->line('children')).'</option>';
								      }
								echo '</select>';
							echo '</div>';
						echo '</div>';
					}else{
						echo '<input type="hidden" name="num_child" value="0">';
					}


						echo '<div class="'.$layout.'">';
							echo '<div class="form-group">';
								echo '<label for="languageid">'.$this->lang->line('language').'</label>';
								
								echo '<select onchange="changeLanguage(this.value)" name="languageid" class="form-control">';
								
								 $language=$this->session->userdata('site_lang');   	
										
								echo '<option value="english" '.($language=='english'?'selected':'').'>English</option>'; 
                                echo '<option value="spanish" '.($language=='spanish'?'selected':'').'>Spanish</option>';
                                echo '<option value="french" '.($language=='french'?'selected':'').'>French</option>';
                                echo '<option value="german" '.($language=='german'?'selected':'').'>German</option>';  


								echo '</select>';

							echo '</div>';
						echo '</div>';
				?>

				  
                               
				<div class="<?= $layout ?>">
					
					<input type="hidden" name="hotel_id" value="<?=insep_encode($hotel['hotel_id'])?>">
					<input type="submit" value="<?=$this->lang->line('search')?>" class="btn btn-<?= $theme; ?>">	
				</div>
			</form>				
		</div>	
	</div>
</div>
<script>
	$("#dp1").datepicker({
		minDate:new Date()
	});


	$("#dp2").datepicker({
		minDate:new Date()
	});


function datechange()
{
	var fecha = $("#dp1").datepicker("getDate");
    fecha.setDate(fecha.getDate() + 1); 
    $("#dp2").datepicker( "option", "minDate", fecha);
}

</script>

<script type="text/javascript">
                            function changeLanguage (language) {
                                window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/'+language;
                            }

</script>