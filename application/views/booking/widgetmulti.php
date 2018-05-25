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
	<?= (isset($widget['custom_css'])) ? $widget['custom_css'] : '' ; ?>
</style>
<div class="<?= $floating; ?>">
	<div class="panel panel-<?= $theme; ?>">
		<?php  
			$widget['show_header'] = (isset($widget['show_header'])) ? $widget['show_header'] : '1';
			if($widget['show_header'] !== '0'){
				echo '<div class="panel-heading">Booking Online</div>';
			}
		?>
		<div class="panel-body">
			<form action="<?= lang_url().'booking/get_reservation'  ?>" target="<?= $target ?>" method="post">
				<?php
				if($allhotel->num_rows >0){
						echo '<div class="row" align="center" style = " margin: auto;   width: 50%;   height: 50%;">';
							echo '<div class="form-group">';
								echo '<label for="hotel_id">Destination</label>';
								echo '<select name="hotel_id" class="form-control">';
								//echo '<option value="0">0 Child</option>';
									 
								      $res1 = $allhotel->result_array();
								     
								     foreach ($res1 as $key ) {
								     	echo '<option value="'.insep_encode($key['hotel_id']).'">'.$key['property_name']. '</option>';
								     }
								      
								echo '</select>';
							echo '</div>';
						echo '</div>';
					}else{
						echo '<input type="hidden" name="num_child" value="0">';
					}
					?>
				<div class="<?= $layout ?>">
					<div class="form-group">
						<label for="dp1">Checkin</label>
						<input type="text" required name="dp1" id="dp1" class="form-control" onchange="return datechange();">
					</div>
				</div>
				<div class="<?= $layout ?>">
					<div class="form-group">
						<label for="dp1">Checkout</label>
						<input type="text" required name="dp2" id="dp2" class="form-control">
					</div>
				</div>
				<?php  
					$widget['guest_number'] = (isset($widget['guest_number'])) ? $widget['guest_number'] : '0';
					if($widget['guest_number'] !== '0'){
						echo '<div class="'.$layout.'">';
							echo '<div class="form-group">';
								echo '<label for="num_person">Adult</label>';
								
								echo '<select name="num_person" class="form-control">';
									
									   $qry1 = $this->db->query("SELECT max(member_count) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$widget['hotel_id']."'");
									      $res1 = $qry1->result_array();
									      $numAdult = $res1[0]['maxNumber'];
									      for ($i=1; $i<=$numAdult; $i++) { 
									        echo '<option value="'.$i.'">'.$i. ' Adult</option>';
									        
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
								echo '<label for="num_child">Children</label>';
								echo '<select name="num_child" class="form-control">';
								echo '<option value="0">0 Child</option>';
									 $qry1 = $this->db->query("SELECT max(children) as maxNumber FROM `manage_property` WHERE  `hotel_id`='".$widget['hotel_id']."'");
								      $res1 = $qry1->result_array();
								      $numChild = $res1[0]['maxNumber'];
								      for ($i=1; $i<=$numChild; $i++) { 
								        echo '<option value="'.$i.'">'.$i. ' Child</option>';
								      }
								echo '</select>';
							echo '</div>';
						echo '</div>';
					}else{
						echo '<input type="hidden" name="num_child" value="0">';
					}
				?>
				<div class="<?= $layout ?>">
					<input type="hidden" name="num_rooms" value="1">
					<input type="submit" value="Book" class="btn btn-<?= $theme; ?>">	
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

