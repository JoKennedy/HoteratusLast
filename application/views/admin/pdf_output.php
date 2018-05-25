<table width="100%" style="width: 100%;">
    <thead>
        <tr>
		
            <th style="width: 106px; text-align:left;">Status</th>
			
            <th style="width: 106px; text-align:left;">Name</th>
			
            <th style="width: 106px; text-align:left;">Mail</th>
			
            <th style="width: 106px; text-align:left;">Mobile</th>
			
            <th style="width: 106px; text-align:left;">Room</th>
			
            <th style="width: 106px; text-align:left;">Channel</th>
			
            <th style="width: 106px; text-align:left;">Check In</th>
			
            <th style="width: 106px; text-align:left;">Check Out</th>
			
            <th style="width: 106px; text-align:left;">Booked Date</th>
			
            <th style="width: 106px; text-align:left;">Reservation Id</th>
			
            <th style="width: 106px; text-align:left;">Amount</th>
			
        </tr>
    </thead>

    <tbody>
	
    <?php
	/* echo '<pre>';
	print_r($reservation);
	die; */
	foreach($reservation as $row)
    {   
		?>
		<tr>
		<td><?php echo $row->status; ?></td>
		<td><?php echo $row->guest_name; ?></td>
		<td>
		<?php
			if($row->channel_id == 8) { echo 'N/A'; } 
			else if($row->channel_id == 2) { $book_data	=	get_data(BOOK_RESERV,array('id'=>$row->mobile),'email,telephone')->row_array();echo $book_data['email']!='' ? $book_data['email'] : 'N/A';}	
			else { echo $row->user_email; }
		?>
		</td>
		<td><?php if($row->channel_id == 8) { echo 'N/A'; } else if($row->channel_id == 2) { echo $book_data['telephone']!='' ? $book_data['telephone'] : 'N/A'; } else { echo $row->mobile; }?></td>
		<td>
		<?php 
		if($row->channel_id == 0)
		{
			$room_details = get_data(TBL_PROPERTY,array('property_id'=>$row->room_id),'property_name')->row_array();
			
			if(count($room_details)!='0') 
			{ 
				$roomName	=	ucfirst($room_details['property_name']);
			}
			if(isset($roomName))
			{
				echo ucfirst($roomName);
			}
			else
			{
				echo '"No Room Set"';
			}
		}
		if($row->channel_id == 1)
		{
			$roomtypeid 	=	$row->room_one;
			
			$rateplanid 	=	$row->room_two;
			
			$user_det 		=	get_data(EXP_RESERV,array('booking_id'=>$row->reservation_code),'user_id,hotel_id')->row_array();
			
			$roomdetails 	=	getExpediaRoom($roomtypeid,$rateplanid,$user_det['user_id'],$user_det['hotel_id']);
			
			if(count($roomdetails) !=0)
			{
				$roomtypeid = $roomdetails['roomtypeId'];
				
				$rateplanid = $roomdetails['rateplanid'];
			}
			
			$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rate_type_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;

			if(!$roomName){
				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$row->room_one,'rateplan_id'=>$row->room_two,'user_id'=>$user_det['user_id'],'hotel_id'=>$user_det['hotel_id']))->row()->map_id))->row()->property_id))->row()->property_name;
			}
			if(isset($roomName))
			{
				echo ucfirst($roomName);
			}
			else
			{
				echo '"No Room Set"';
			}
		}
		else if($row->channel_id == 2)
		{
			$user_dets	=	get_data(BOOK_ROOMS,array('roomreservation_id'=>$row->reservation_code),'user_id,hotel_hotel_id')->row_array();
		
			$roomName	=	get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data('import_mapping_BOOKING',array('B_room_id'=>$row->room_one, 'B_rate_id' => $row->room_two,'owner_id'=>$user_dets['user_id'],'hotel_id'=>$user_dets['hotel_hotel_id']))->row()->import_mapping_id))->row()->property_id))->row()->property_name;

			if(isset($roomName))
			{
				echo ucfirst($roomName);
			}
			else
			{
				echo '"No Room Set"';
			}
		}		
				
		?>
		</td>
		<td><?php echo $row->channel; ?></td>
		<td><?php echo $row->start_date; ?></td>
		<td><?php echo $row->end_date; ?></td>
		<td><?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$row->booking_date))); ?></td>
		<td><?php echo $row->reservation_code; ?></td>
		<td>
		<?php 
			if($row->channel_id==0)
			{
				echo get_data(TBL_CUR,array('currency_id'=>$row->currency_id))->row()->symbol.' '.number_format($row->price);
			}
			else
			{
				echo $row->currency_id.' '.$row->price;
			}
        ?>
		</td>	
		</tr>
		<?php
	}
	?>
	</tbody>
	</table>