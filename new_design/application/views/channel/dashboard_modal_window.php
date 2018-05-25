<?php
if($view_modal=="cancelation")
{
?>
 <div class="portlet-title">
          <div class="caption col-md-11">
              <i class="fa fa-calendar-times-o font-green"></i>
              <span class="caption-subject font-green bold uppercase">Today's Cancelations</span>
          </div>
             <div class="actions actions2">
          </div>
      </div>
      <div class="portlet-body">
		<div class="table-scrollable table-scrollables">
            <table class="table table-bordered table-hover" id="example2">
            <thead>
            <tr>
            <th class="hidden-prints" style="display:none">#</th>
            <th class="hidden-prints"> Status </th>
            <th> Full Name </th>
            <th> Room Booked </th>
            <th class="hidden-prints"> Channel </th>
            <th> Check-in </th>
            <th> Check-out </th>
            <th class="hidden-print"> Booked Date </th>
            <th> Reserv ID </th>
            <th> Total Amount </th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $room_type = $this->reservation_model->reservationresult('cancel');
            if($room_type)
            {
                $admin = $this->reservation_model->get_admin();
				$i=1;
                foreach($room_type as $room)
                {  
                    $status=$room->status;
                    if($status==11 || $status == "Book" || $status == "BOOKED" ||  $status == "Commit" || $status == "Confirmed" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm hidden-print">New booking</button> <span class="display_none view-print">New booking</span>';
                    else if($status==12 || $status == "Modify" || $status == "MODIFIED" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm hidden-print">Modification</button> <span class="display_none view-print">Modification</span>';
                    else if($status==13 || $status == "Cancel" || $status="cancelled" || $status == "CANCELLED" || $status == "Canceled" || $status=="Cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm hidden-print">Cancellation</button> <span class="display_none view-print">Cancellation</span>';
					if($room->channel_id==2)
					{
						if($room->guest_name!='')
						{
							$reservation_name 	=	$room->guest_name;
						}
						else
						{
							$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id))->row();
							$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
						}
					}
					else
					{
						$reservation_name	=	$room->guest_name;
					}
            ?>
            <tr>
            <td class="hidden-prints" style="display:none"> <?php echo $i++;?></td>
            <td class="hidden-prints">
            <?php 
            if($room->channel_id == 0)
            {
            if($room->status=='Reserved'){ ?>
            
            <button type="button" class="btn btn-info btn-sm hidden-print"> <?php echo 'Reserved';?> </button> 
            
            <span class="display_none view-print">Reserved</span>
            
            <?php }elseif($room->status=='Confirmed'){ ?>
            
            <button type="button" class="btn btn-success btn-sm hidden-print"><?php echo 'Confirmed';?> </button> 
            
            <span class="display_none view-print">Confirmed</span>
            
            <?php }elseif($room->status=='Canceled'){ ?></button>
            
            <button type="button" class="btn btn-danger btn-sm hidden-print"> <?php echo 'Canceled';?> </button> 
            
            <span class="display_none view-print">Canceled</span>
            
            <?php }
            }
            else { echo $status_type;}?>
            
            
            
            
            </td>
            <td> 
            <?php if($room->channel_id == 0){ ?>
            <a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> <?php echo $reservation_name; ?></a> 
            <?php } else { ?>
            <a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>" class="hidden-print">  <?php echo $reservation_name; ?></a>
            <?php  } ?>
            <span class="display_none view-print"><?php echo $reservation_name; ?> </span> 
            </td>
            
            <td>
            <?php
            if($room->channel_id == 0)
            {
                $roomName= get_data(TBL_PROPERTY,array('property_id'=>$room->room_id))->row()->property_name;
            }
            else if($room->channel_id == 1)
            {
				$details = get_data("import_mapping",array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row();
                $roomtypeid = $room->roomtypeId;
                $rateplanid = $room->rateplanid;
                $roomdetails = getExpediaRoom($room->roomtypeId,$room->rateplanid,current_user_type(),hotel_id());
                if(count($roomdetails) !=0)
                {
                    $roomtypeid = $roomdetails['roomtypeId'];
                    $rateplanid = $roomdetails['rateplanid'];
                }
                $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
                if(!$roomName)
                {
                    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
                }
            }
            else if($room->channel_id == 11)
            {
                $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->re_id))->row()->property_id))->row()->property_name;
            }
            else if($room->channel_id == 8)
            {
                $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->room_id,'rateplan_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->GTA_id))->row()->property_id))->row()->property_name;
            }
			elseif($room->channel_id==2)
			{
				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$room->id,'B_rate_id'=>$room->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
			}
            elseif($room->channel_id==15)
            {
                $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
            }
			elseif($room->channel_id==17)
			{
				$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$room->roomtypeId,'RatePlanCode'=>$room->rateplanid))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
			}
            if($roomName)
            {
                echo ucfirst($roomName);
            }
            else
            {
                echo '"No Room Set"';
            }
            ?>
            </td>
            
            <td class="hidden-prints">
            <div>
            <?php
            if($room->channel_id!=0)
            {
            $cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$room->channel_id))->row();
            ?> 
            <img width="60" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" class="hidden-print"> 
            <p class="display_none view-print"><?php echo $cha_logo->channel_name?></p>
            <?php 
            }
            else
            
            {
            $cha_logo = get_data(TBL_SITE,array('id'=>'1'))->row();
            ?>
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$cha_logo->reservation_logo));?>" class="hidden-print">  
            <p class="display_none view-print">Hotelavailabilities</p>
            <?php
            }
            ?></div>
            </td>
            <td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->start_date))); ?></td>
            <td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->end_date))); ?></td>
            <td class="hidden-print">
            <?php if($room->channel_id == 0){ ?> 
            <a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> 
            <?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?> </a>
            <?php } else { ?>
            <a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>">
            <?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?></a>
            <?php  } ?>
            <span class="display_none view_print"> <?php echo date('M d,Y',strtotime(str_replace("/","-",$room->booking_date))); ?> </span>
            </td>   
            <td><?php if($room->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id.' - '.$room->reservation_code;} else { echo $room->reservation_code; } ?></td>
            <td><?php if($room->channel_id==0)
            { echo get_data(TBL_CUR,array('currency_id'=>$room->currency_id))->row()->symbol.' '.number_format($room->num_rooms*$room->price); } else { echo $room->CURRENCY.' '.$room->price;}?></td>
            
            </tr>
            <?php }}?>
            
            </tbody>
            </table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div>
      	</div>

<?php 
}
else if($view_modal=="reservation")
{
?>
<div class="portlet-title ">
<div class="caption col-md-11">
<i class="fa fa-calendar-plus-o font-green"></i>
<span class="caption-subject font-green bold uppercase">Today's Reservations</span>
</div>
<div class="actions actions1">

</div>
</div>
<div class="portlet-body">
<div class="table-scrollable table-scrollables">
<table class="table table-striped table-bordered" id="example1">
<thead>
<tr>
<th class="hidden-prints" style="display:none">#</th>
<th class="hidden-prints"> Status </th>
<th> Full Name </th>
<th> Room Booked </th>
<th class="hidden-prints"> Channel </th>
<th> Check-in </th>
<th> Check-out </th>
<th class="hidden-print"> Booked Date </th>
<th> Reserv ID </th>
<th> Total Amount </th>
</tr>
</thead>
<tbody>
<?php 
$room_type = $this->reservation_model->reservationresult('reserve');
/* echo '<pre>';
print_r($room_type); */
if($room_type)
{
	$admin = $this->reservation_model->get_admin();
	$i=1;
	foreach($room_type as $room)
	{  
		$status=$room->status;
		if($status==11 || $status == "Book" || $status == "BOOKED" ||  $status == "Commit" || $status == "Confirmed" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm hidden-print">New booking</button> <span class="display_none view-print">New booking</span>';
		else if($status==12 || $status == "Modify" || $status == "MODIFIED" || $status == "Modify" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm hidden-print">Modification</button> <span class="display_none view-print">Modification</span>';
		else if($status==13 || $status == "Cancel" || $status == "CANCELLED" || $status == "Cancel" || $status=="Cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm hidden-print">Cancellation</button> <span class="display_none view-print">Cancellation</span>';	
		if($room->channel_id==2)
		{
			if($room->guest_name!='')
			{
				$reservation_name 	=	$room->guest_name;
			}
			else
			{
				$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id))->row();
				$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
			}
		}
		else
		{
			$reservation_name	=	$room->guest_name;
		}
?>
<tr>
 <td class="hidden-prints" style="display:none"> <?php echo $i++;?></td>
<td class="hidden-prints">
<?php 
if($room->channel_id == 0)
{
if($room->status=='Reserved'){ ?>

<button type="button" class="btn btn-info btn-sm hidden-print"> <?php echo 'Reserved';?> </button> 

<span class="display_none view-print">Reserved</span>

<?php }elseif($room->status=='Confirmed'){ ?>

<button type="button" class="btn btn-success btn-sm hidden-print"><?php echo 'Confirmed';?> </button> 

<span class="display_none view-print">Confirmed</span>

<?php }elseif($room->status=='Canceled'){ ?></button>

<button type="button" class="btn btn-danger btn-sm hidden-print"> <?php echo 'Canceled';?> </button> 

<span class="display_none view-print">Canceled</span>

<?php }
}
else { echo $status_type;}?>




</td>
<td> 
<?php if($room->channel_id == 0){ ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> <?php echo $reservation_name; ?></a> 
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>" class="hidden-print">  <?php echo $reservation_name; ?></a>
<?php  } ?>
<span class="display_none view-print"><?php echo $reservation_name; ?> </span> 
</td>

<td>
<?php
if($room->channel_id == 0)
{
	$roomName= get_data(TBL_PROPERTY,array('property_id'=>$room->room_id))->row()->property_name;
}
else if($room->channel_id == 1)
{
	$details = get_data("import_mapping",array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row();
    $roomtypeid = $room->roomtypeId;
    $rateplanid = $room->rateplanid;
    $roomdetails = getExpediaRoom($room->roomtypeId,$room->rateplanid,current_user_type(),hotel_id());
    if(count($roomdetails) !=0)
    {
        $roomtypeid = $roomdetails['roomtypeId'];
        $rateplanid = $roomdetails['rateplanid'];
    }
    @$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    if(!$roomName)
    {
        @$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    }
}
else if($room->channel_id == 11)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->re_id))->row()->property_id))->row()->property_name;
}
else if($room->channel_id == 8)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->room_id,'rateplan_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->GTA_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==2)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$room->id,'B_rate_id'=>$room->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==15)
{
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==17)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$room->roomtypeId,'RatePlanCode'=>$room->rateplanid))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
if($roomName)
{
	echo ucfirst($roomName);
}
else
{
	echo '"No Room Set"';
}
?>
</td>

<td class="hidden-prints">
<div>
<?php
if($room->channel_id!=0)
{
$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$room->channel_id))->row();
?> 
<img width="60" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" class="hidden-print"> 
<p class="display_none view-print"><?php echo $cha_logo->channel_name?></p>
<?php 
}
else
{
$cha_logo = get_data(TBL_SITE,array('id'=>'1'))->row();
?>
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$cha_logo->reservation_logo));?>" class="hidden-print">  
<p class="display_none view-print">Hotelavailabilities</p>
<?php
}
?></div>
</td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->start_date))); ?></td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->end_date))); ?></td>
<td class="hidden-print">
<?php if($room->channel_id == 0){ ?> 
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> 
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?> </a>
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>">
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?></a>
<?php  } ?>
<span class="display_none view_print"> <?php echo date('M d,Y',strtotime(str_replace("/","-",$room->booking_date))); ?> </span>
</td>   
<td><?php if($room->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id.' - '.$room->reservation_code;} else { echo $room->reservation_code; } ?></td>
<td><?php if($room->channel_id==0)
{ echo get_data(TBL_CUR,array('currency_id'=>$room->currency_id))->row()->symbol.' '.number_format($room->num_rooms*$room->price); } else { echo $room->CURRENCY.' '.$room->price;}?></td>

</tr>
<?php }}?>

</tbody>
</table>
</div>
<div class="modal-footer">
<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> 
</div>
</div>
<?php 
}else if($view_modal=="arrival")
{
?>
 <div class="portlet-title">
          <div class="caption col-md-11">
              <i class="fa fa-users font-green"></i>
              <span class="caption-subject font-green bold uppercase">Today's Arrivals</span>
          </div>
             <div class="actions actions3">
              </div>
      </div>
      <div class="portlet-body">
          <div class="table-scrollable table-scrollables ">
               <table class="table table-bordered table-hover" id="example3">
				<thead>
<tr>
<th class="hidden-prints" style="display:none">#</th>
<th class="hidden-prints"> Status </th>
<th> Full Name </th>
<th> Room Booked </th>
<th class="hidden-prints"> Channel </th>
<th> Check-in </th>
<th> Check-out </th>
<th class="hidden-print"> Booked Date </th>
<th> Reserv ID </th>
<th> Total Amount </th>
</tr>
</thead>

<tbody>
<?php 
$room_type = $this->reservation_model->reservationresult('arrival');
if($room_type)
{
	$admin = $this->reservation_model->get_admin();
	$i=1;
	foreach($room_type as $room)
	{  
		$status=$room->status;
		if($status==11 || $status == "Book" ||  $status == "Commit" || $status == "BOOKED" || $status == "Confirmed" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm hidden-print">New booking</button> <span class="display_none view-print">New booking</span>';
		else if($status==12 || $status == "Modify" || $status == "MODIFIED" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm hidden-print">Modification</button> <span class="display_none view-print">Modification</span>';
		else if($status==13 || $status == "Cancel" || $status == "CANCELLED" || $status == "Cancel" || $status=="Cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm hidden-print">Cancellation</button> <span class="display_none view-print">Cancellation</span>';
		if($room->channel_id==2)
		{
			if($room->guest_name!='')
			{
				$reservation_name 	=	$room->guest_name;
			}
			else
			{
				$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id))->row();
				$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
			}
		}
		else
		{
			$reservation_name	=	$room->guest_name;
		}		
?>
<tr>
 <td class="hidden-prints" style="display:none"> <?php echo $i++;?></td>
<td class="hidden-prints">
<?php 
if($room->channel_id == 0)
{
if($room->status=='Reserved'){ ?>

<button type="button" class="btn btn-info btn-sm hidden-print"> <?php echo 'Reserved';?> </button> 

<span class="display_none view-print">Reserved</span>

<?php }elseif($room->status=='Confirmed'){ ?>

<button type="button" class="btn btn-success btn-sm hidden-print"><?php echo 'Confirmed';?> </button> 

<span class="display_none view-print">Confirmed</span>

<?php }elseif($room->status=='Canceled'){ ?></button>

<button type="button" class="btn btn-danger btn-sm hidden-print"> <?php echo 'Canceled';?> </button> 

<span class="display_none view-print">Canceled</span>

<?php }
}
else { echo $status_type;}?>




</td>

<td> 
<?php if($room->channel_id == 0){ ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> <?php echo $reservation_name; ?></a> 
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>" class="hidden-print">  <?php echo $reservation_name; ?></a>
<?php  } ?>
<span class="display_none view-print"><?php echo $reservation_name; ?> </span> 
</td>

<td>
<?php
if($room->channel_id == 0)
{
	$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$room->room_id))->row()->property_name;
}
else if($room->channel_id == 1)
{
	$details = get_data("import_mapping",array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row();
    $roomtypeid = $room->roomtypeId;
    $rateplanid = $room->rateplanid;
    $roomdetails = getExpediaRoom($room->roomtypeId,$room->rateplanid,current_user_type(),hotel_id());
    if(count($roomdetails) !=0)
    {
        $roomtypeid = $roomdetails['roomtypeId'];
        $rateplanid = $roomdetails['rateplanid'];
    }
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    if(!$roomName)
    {
        $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    }
}
else if($room->channel_id == 11)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->re_id))->row()->property_id))->row()->property_name;
}
else if($room->channel_id == 8)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->room_id,'rateplan_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id' => hotel_id()))->row()->GTA_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==2)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$room->id,'B_rate_id'=>$room->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==17)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$room->roomtypeId,'RatePlanCode'=>$room->rateplanid))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==15)
{
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
}
if($roomName)
{
	echo ucfirst($roomName);
}
else
{
	echo '"No Room Set"';
}
?>
</td>

<td class="hidden-prints">
<div>
<?php
if($room->channel_id!=0)
{
$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$room->channel_id))->row();
?> 
<img width="60" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" class="hidden-print"> 
<p class="display_none view-print"><?php echo $cha_logo->channel_name?></p>
<?php 
}
else
{
$cha_logo = get_data(TBL_SITE,array('id'=>'1'))->row();
?>
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$cha_logo->reservation_logo));?>" class="hidden-print">  
<p class="display_none view-print">Hotelavailabilities</p>
<?php
}
?></div>
</td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->start_date))); ?></td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->end_date))); ?></td>
<td class="hidden-print">
<?php if($room->channel_id == 0){ ?> 
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> 
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?> </a>
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>">
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?></a>
<?php  } ?>
<span class="display_none view_print"> <?php echo date('M d,Y',strtotime(str_replace("/","-",$room->booking_date))); ?> </span>
</td>    
<td><?php if($room->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id.' - '.$room->reservation_code;} else { echo $room->reservation_code; } ?></td>
<td><?php if($room->channel_id==0)
{ echo get_data(TBL_CUR,array('currency_id'=>$room->currency_id))->row()->symbol.' '.number_format($room->num_rooms*$room->price); } else { echo $room->CURRENCY.' '.$room->price;}?></td>

</tr>
<?php }}?>

</tbody>
				
              </table>
          </div>
<div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div>
      </div>

<?php 
} 
else if($view_modal=="departure")
{
?>
<div class="portlet-title ">
<div class="caption col-md-11">
<i class="fa fa-road font-green"></i>
<span class="caption-subject font-green bold uppercase">Today's Departures</span>
</div>
<div class="actions actions4">
</div>
</div>
      <div class="portlet-body">
     
          <div class="table-scrollable table-scrollables">
			<table class="table table-bordered table-hover" id="example4">
				<thead>
					<tr>
						<th class="hidden-prints" style="display:none">#</th>
						<th class="hidden-prints"> Status </th>
						<th> Full Name </th>
						<th> Room Booked </th>
						<th class="hidden-prints"> Channel </th>
						<th> Check-in </th>
						<th> Check-out </th>
						<th class="hidden-print"> Booked Date </th>
						<th> Reserv ID </th>
						<th> Total Amount </th>
					</tr>
				</thead>
<tbody>
<?php 
$room_type = $this->reservation_model->reservationresult('depature');
if($room_type)
{
	$admin = $this->reservation_model->get_admin();
	$i=1;
	foreach($room_type as $room)
	{  
		$status=$room->status;
		if($status==11 || $status == "Book" ||  $status == "Commit" || $status == "BOOKED" || $status == "Confirmed" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm hidden-print">New booking</button> <span class="display_none view-print">New booking</span>';
		else if($status==12 || $status == "Modify" || $status == "MODIFIED" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm hidden-print">Modification</button> <span class="display_none view-print">Modification</span>';
		else if($status==13 || $status == "Cancel" || $status == "CANCELLED" || $status == "Cancel" || $status=="Cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm hidden-print">Cancellation</button> <span class="display_none view-print">Cancellation</span>';
		if($room->channel_id==2)
		{
			if($room->guest_name!='')
			{
				$reservation_name 	=	$room->guest_name;
			}
			else
			{
				$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id))->row();
				$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
			}
		}
		else
		{
			$reservation_name	=	$room->guest_name;
		}		
?>
<tr>
 <td class="hidden-prints" style="display:none"> <?php echo $i++;?></td>
<td class="hidden-prints">
<?php 
if($room->channel_id == 0)
{
if($room->status=='Reserved'){ ?>

<button type="button" class="btn btn-info btn-sm hidden-print"> <?php echo 'Reserved';?> </button> 

<span class="display_none view-print">Reserved</span>

<?php }elseif($room->status=='Confirmed'){ ?>

<button type="button" class="btn btn-success btn-sm hidden-print"><?php echo 'Confirmed';?> </button> 

<span class="display_none view-print">Confirmed</span>

<?php }elseif($room->status=='Canceled'){ ?></button>

<button type="button" class="btn btn-danger btn-sm hidden-print"> <?php echo 'Canceled';?> </button> 

<span class="display_none view-print">Canceled</span>

<?php }
}
else { echo $status_type;}?>




</td>

<td> 
<?php if($room->channel_id == 0){ ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> <?php echo $reservation_name; ?></a> 
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>" class="hidden-print">  <?php echo $reservation_name; ?></a>
<?php  } ?>
<span class="display_none view-print"><?php echo $reservation_name; ?> </span> 
</td>

<td>
<?php
if($room->channel_id == 0)
{
	$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$room->room_id))->row()->property_name;
}
else if($room->channel_id == 1)
{
	$details = get_data("import_mapping",array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row();
    $roomtypeid = $room->roomtypeId;
    $rateplanid = $room->rateplanid;
    $roomdetails = getExpediaRoom($room->roomtypeId,$room->rateplanid,current_user_type(),hotel_id());
    if(count($roomdetails) !=0)
    {
        $roomtypeid = $roomdetails['roomtypeId'];
        $rateplanid = $roomdetails['rateplanid'];
    }
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    if(!$roomName)
    {
        $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    }
}
else if($room->channel_id == 11)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE))->row()->re_id))->row()->property_id))->row()->property_name;
}
else if($room->channel_id == 8)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->room_id,'rateplan_id'=>$room->rateplanid))->row()->GTA_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==2)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$room->id,'B_rate_id'=>$room->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==17)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$room->roomtypeId,'RatePlanCode'=>$room->rateplanid))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==15)
{
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
}
if($roomName)
{
	echo ucfirst($roomName);
}
else
{
	echo '"No Room Set"';
}
?>
</td>

<td class="hidden-prints">
<div>
<?php
if($room->channel_id!=0)
{
$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$room->channel_id))->row();
?> 
<img width="60" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" class="hidden-print"> 
<p class="display_none view-print"><?php echo $cha_logo->channel_name?></p>
<?php 
}
else
{
$cha_logo = get_data(TBL_SITE,array('id'=>'1'))->row();
?>
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$cha_logo->reservation_logo));?>" class="hidden-print">  
<p class="display_none view-print">Hotelavailabilities</p>
<?php
}
?></div>
</td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->start_date))); ?></td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->end_date))); ?></td>
<td class="hidden-print">
<?php if($room->channel_id == 0){ ?> 
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> 
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?> </a>
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>">
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?></a>
<?php  } ?>
<span class="display_none view_print"> <?php echo date('M d,Y',strtotime(str_replace("/","-",$room->booking_date))); ?> </span>
</td>    
<td><?php if($room->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id.' - '.$room->reservation_code;} else { echo $room->reservation_code; } ?></td>
<td><?php if($room->channel_id==0)
{ echo get_data(TBL_CUR,array('currency_id'=>$room->currency_id))->row()->symbol.' '.number_format($room->num_rooms*$room->price); } else { echo $room->CURRENCY.' '.$room->price;}?></td>

</tr>
<?php }}?>

</tbody>
				
              </table>
               
          </div>
<div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div>
      </div>

<?php
}
else if($view_modal=="modify")
{
?>
<div class="portlet-title ">
<div class="caption col-md-11">
<i class="fa fa-road font-green"></i>
<span class="caption-subject font-green bold uppercase">Today's Modifications</span>
</div>
<div class="actions actions5">
</div>
</div>
      <div class="portlet-body">
     
          <div class="table-scrollable table-scrollables">
			<table class="table table-bordered table-hover" id="example5">
				<thead>
					<tr>
						<th class="hidden-prints" style="display:none">#</th>
						<th class="hidden-prints"> Status </th>
						<th> Full Name </th>
						<th> Room Booked </th>
						<th class="hidden-prints"> Channel </th>
						<th> Check-in </th>
						<th> Check-out </th>
						<th class="hidden-print"> Booked Date </th>
						<th> Reserv ID </th>
						<th> Total Amount </th>
					</tr>
				</thead>
<tbody>
<?php 
$room_type = $this->reservation_model->reservationresult('modify');
if($room_type)
{
	$admin = $this->reservation_model->get_admin();
	$i=1;
	foreach($room_type as $room)
	{  
		$status=$room->status;
		if($status==11 || $status == "Book" ||  $status == "Commit" || $status == "Confirmed" || $status == "new") $status_type='<button type="button" class="btn btn-info btn-sm hidden-print">New booking</button> <span class="display_none view-print">New booking</span>';
		else if($status==12 || $status == "Modify" || $status == "modified") $status_type='<button type="button" class="btn btn-success btn-sm hidden-print">Modification</button> <span class="display_none view-print">Modification</span>';
		else if($status==13 || $status == "Cancel" || $status == "Cancel" || $status=="Cancelled") $status_type='<button type="button" class="btn btn-danger btn-sm hidden-print">Cancellation</button> <span class="display_none view-print">Cancellation</span>';
		if($room->channel_id==2)
		{
			if($room->guest_name!='')
			{
				$reservation_name 	=	$room->guest_name;
			}
			else
			{
				$reservation_name	=	get_data(BOOK_RESERV,array('id'=>get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id))->row();
				$reservation_name	=	$reservation_name->first_name.' '.$reservation_name->last_name;
			}
		}
		else
		{
			$reservation_name	=	$room->guest_name;
		}
?>
<tr>
 <td class="hidden-prints" style="display:none"> <?php echo $i++;?></td>
<td class="hidden-prints">
<?php 
if($room->channel_id == 0)
{
if($room->status=='Reserved'){ ?>

<button type="button" class="btn btn-info btn-sm hidden-print"> <?php echo 'Reserved';?> </button> 

<span class="display_none view-print">Reserved</span>

<?php }elseif($room->status=='Confirmed'){ ?>

<button type="button" class="btn btn-success btn-sm hidden-print"><?php echo 'Confirmed';?> </button> 

<span class="display_none view-print">Confirmed</span>

<?php }elseif($room->status=='Canceled'){ ?></button>

<button type="button" class="btn btn-danger btn-sm hidden-print"> <?php echo 'Canceled';?> </button> 

<span class="display_none view-print">Canceled</span>

<?php }
}
else { echo $status_type;}?>




</td>

<td> 
<?php if($room->channel_id == 0){ ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> <?php echo $reservation_name; ?></a> 
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>" class="hidden-print">  <?php echo $reservation_name; ?></a>
<?php  } ?>
<span class="display_none view-print"><?php echo $reservation_name; ?> </span> 
</td>

<td>
<?php
if($room->channel_id == 0)
{
	$roomName= @get_data(TBL_PROPERTY,array('property_id'=>$room->room_id))->row()->property_name;
}
else if($room->channel_id == 1)
{
	$details = get_data("import_mapping",array('roomtype_id'=>$room->roomtypeId,'rate_type_id'=>$room->rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row();
    $roomtypeid = $room->roomtypeId;
    $rateplanid = $room->rateplanid;
    $roomdetails = getExpediaRoom($room->roomtypeId,$room->rateplanid,current_user_type(),hotel_id());
    if(count($roomdetails) !=0)
    {
        $roomtypeid = $roomdetails['roomtypeId'];
        $rateplanid = $roomdetails['rateplanid'];
    }
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    if(!$roomName)
    {
        $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeid,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
    }
}
else if($room->channel_id == 11)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$room->ROOMCODE))->row()->re_id))->row()->property_id))->row()->property_name;
}
else if($room->channel_id == 8)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$room->room_id,'rateplan_id'=>$room->rateplanid))->row()->GTA_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==2)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$room->id,'B_rate_id'=>$room->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==17)
{
	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_BNOW,array('InvTypeCode'=>$room->roomtypeId,'RatePlanCode'=>$room->rateplanid))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
}
elseif($room->channel_id==15)
{
    $roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$room->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$room->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
}
if($roomName)
{
	echo ucfirst($roomName);
}
else
{
	echo '"No Room Set"';
}
?>
</td>

<td class="hidden-prints">
<div>
<?php
if($room->channel_id!=0)
{
$cha_logo = get_data(TBL_CHANNEL,array('channel_id'=>$room->channel_id))->row();
?> 
<img width="60" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/channel/".$cha_logo->logo_channel));?>" class="hidden-print"> 
<p class="display_none view-print"><?php echo $cha_logo->channel_name?></p>
<?php 
}
else
{
$cha_logo = get_data(TBL_SITE,array('id'=>'1'))->row();
?>
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/logo/".$cha_logo->reservation_logo));?>" class="hidden-print">  
<p class="display_none view-print">Hotelavailabilities</p>
<?php
}
?></div>
</td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->start_date))); ?></td>
<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$room->end_date))); ?></td>
<td class="hidden-print">
<?php if($room->channel_id == 0){ ?> 
<a href="<?php echo lang_url(); ?>reservation/reservation_order/<?php echo insep_encode($room->reservation_id); ?>" class="hidden-print"> 
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?> </a>
<?php } else { ?>
<a href="<?php echo lang_url(); ?>reservation/reservation_channel/<?php echo secure($room->channel_id).'/'.insep_encode($room->reservation_id); ?>">
<?php echo date('M d,Y h:i:s A',strtotime(str_replace("/","-",$room->booking_date))); ?></a>
<?php  } ?>
<span class="display_none view_print"> <?php echo date('M d,Y',strtotime(str_replace("/","-",$room->booking_date))); ?> </span>
</td>    
<td><?php if($room->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$room->reservation_id))->row()->reservation_id.' - '.$room->reservation_code;} else { echo $room->reservation_code; } ?></td>
<td><?php if($room->channel_id==0)
{ echo get_data(TBL_CUR,array('currency_id'=>$room->currency_id))->row()->symbol.' '.number_format($room->num_rooms*$room->price); } else { echo $room->CURRENCY.' '.$room->price;}?></td>

</tr>
<?php }}?>

</tbody>
				
              </table>
               
          </div>
<div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> </div>
      </div>

<?php
}
?>

