<table class="table table-bordered">
      <thead>
        <tr>
          <th>Status </th>
          <th>Full name </th>
          <th> 	Channel </th>
          <th> Check-in date </th>
          <th> Check-out date  </th>
          <th> Booked Date  </th>
        </tr>
      </thead>
      <tbody>
			  <?php  $admin = $this->reservation_model->get_admin();
			   if($result){
			   foreach($result as $res){ ?>
        <tr>
		
        <td><button type="button" class="btn btn-info btn-sm"> Reserved  </button> </td>
          <td> <a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($res->reservation_id); ?>"> <?php echo $res->guest_name; ?></a> </td>
          <td><div><?php echo $admin->name; ?> </div>
          <div><?php echo $res->reservation_code; ?> </div></td>
          <td><?php echo date('M d, Y',strtotime($res->start_date)); ?></td>
          <td><?php echo date('M d, Y',strtotime($res->end_date)); ?></td>
           <td> <a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($res->reservation_id); ?>"> <?php echo date('M d, Y',strtotime($res->booking_date)); ?> </a></td>				   
        </tr>
			  <?php } }else{ ?>

      </tbody>
	  
    </table>
     
		<div class="alert alert-success text-center">
			<strong> No Filter Results Found <br/><br/><br/>
				<a href="<?php echo lang_url(); ?>reservation/reservationlist">Remove Filter</a>
			</strong>
		</div>
		
<?php } ?>
	 
