<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
		<i class="fa fa-calendar font-blue-hoki"></i>
		
	<?php 
	$reservation_today_count= $this->reservation_model->reservationcounts('reserve');
	$cancel_today_count= $this->reservation_model->reservationcounts('cancel');
	$alert_count = $reservation_today_count + $cancel_today_count;
	?>
	
	<input type="hidden" name="reservationcount" id="reservationcount" value="<?php echo $reservation_today_count; ?>">
    
    <input type="hidden" name="cancellationcount" id="cancellationcount" value="<?php echo $cancel_today_count; ?>">
	
    <span class="badge badge-success"><?php if($alert_count){ echo $alert_count;}else{echo '0';} ?></span>
	</a>
	
	<ul class="dropdown-menu">
		<li class="external">
				<span class="light"><a href="javascript:;">view log file</a></span>
		</li>
		<li>
			<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
				
			<li>
				<?php
				if($reservation_today_count!='0'){
				?>

					<!--<a class="more" data-toggle="modal" href="#reservationspop">-->
                    <a class="more details_modal" href="javascript:;" custom="reservation">
				
					   <?php 
	     					$date = date('d/m/Y');
  							$bdate = date('Y-m-d');
							$this->db->order_by('reservation_id','desc');
					   		$new_reservation = get_data(RESERVATION,array('booking_date'=>$bdate,'hotel_id'=>hotel_id(),'user_status'=>"Booking",'status'=>"Reserved"))->row();
							$saved_date=$new_reservation->created_date;
							$timediff = $this->admin_model->time_elapsed_string($saved_date);
						?>
						<span class="time"><?php echo $timediff; ?></span>
						<span class="details">
							<span class="label label-sm label-icon label-success">
								<i class="fa fa-calendar-plus-o"></i>
							</span> New reservation
					    </span>
						<span id="today_re">(<?php echo $reservation_today_count; ?>)</span>
					</a>
					
					
					<?php }
					else{ ?>
						<a class="more"  data-toggle="modal" href="javascript:;">
							<span class="label label-sm label-icon label-success">
							<i class="fa fa-calendar-times-o"></i>
							</span> New reservation (0) </span>
						</a>
						<?php } ?>
				</li>
<li>
<?php
if($cancel_today_count!=0){ 

	  
	  						$date = date('d/m/Y');
  							$bdate = date('Y-m-d');
							$this->db->order_by('reservation_id','desc');
					   		$new_cancel = get_data(RESERVATION,array('booking_date'=>$bdate,'hotel_id'=>hotel_id(),'status'=>"Canceled"))->row();
							$saved_date=$new_cancel->cancel_date;
							$timediff = $this->admin_model->time_elapsed_string($saved_date);
	?>

	<a class="more details_modal" href="javascript:;" custom="cancelation">
		
		<span class="time"><?php echo $timediff; ?></span>
		<span class="details">
			<span class="label label-sm label-icon label-danger">
				<i class="fa fa-calendar-times-o"></i>
			</span> New Cancelation 
		</span>
		<span id="today_ca">(<?php echo $cancel_today_count; ?>)</span>
		
	</a>
	
	<?php } else{  ?>
	<a class="more" data-toggle="modal" href="javascript:;">
		<span class="label label-sm label-icon label-danger">
		<i class="fa fa-calendar-times-o"></i>
		</span> New Cancelation (0) </span>
	</a>
	<?php } ?>
	
	</li> 
		</ul>
		</li>
	    </ul>
		