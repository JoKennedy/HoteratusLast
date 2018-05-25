		<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Full Update</h4>
      </div>
      <div id="full_update_success">
      <div class="modal-body">
        <div class="calen-2">
        
        <div class="row">
        <div class="col-md-12 col-sm-12">
        <!--<p><b>This feature uploads your complete availability, rates and other settings from myallocator to the channels you select.</b></p>
        <p>Normally only changes you make on myallocator are sent to a channel. resulting in discrepancies if the availability is different on a channel compared to myallocator to begin with.</p>
        <p>To avoid overbookings we strongly advise to do a Full Refresh after you setup a new channel, or after you mapped a room you previosly hadn't mapped.</p>-->
<?php
$curr_date = date('d/m/Y');
$date = strtotime("+7 day");
$add_date = date('d/m/Y', $date);
?>
 <div class="col-md-6 col-sm-6">
        <h4>Start Date:</h4>
		<div class="form-group">
		<input type="text" class="form-control widh" value="<?php echo $curr_date;?>" id="datepicker_full_start" name="datepicker_full_start">
 </div>
 </div>
 
 <div class="col-md-6 col-sm-6">
        <h4>End Date:</h4>
        
  <div class="form-group">
    <input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_full_end" name="datepicker_full_end">
  </div>

        </div>
        <div class="col-md-6 col-sm-6">

  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="all" class="link" rel="div1">
    Refresh all channels
  </label>

</div>
<div class="col-md-6 col-sm-6">

  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="sync" class="link" rel="div1">
      Synchronize Calendars
  </label>

</div>
<div class="col-md-12 col-sm-12">

  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios2" value="select" checked class="link" rel="div2">
    Refresh selected channels 
  </label>

</div>
<div class="col-md-12 col-sm-12">
<div class="content_full" id="div1">
</div>
<div class="multiselect multiselect-channels width-form-full well content_full" id="div2">
<?php 
if(count($mapped_rooms)!=0)
{
?>
	<!--<label class="check_header">
	  <input type="checkbox" value="1" name="all_check" multiple="multiple" id="all_check_toggle" class="all_check_toggle" checked="checked"> All
	</label>-->
	<?php
	foreach ($mapped_rooms as $room_value) 
	{ 
		extract($room_value); 
		if($channel_id==2)
		{
			$chk_allow = get_data(CONNECT,array('user_id'=>current_user_type(),'hotel_id'=>hotel_id(),'channel_id'=>$channel_id))->row()->xml_type;
		}
		else	
		{
			$chk_allow='';
		}
		if(($channel_id==2 && ($chk_allow==2 || $chk_allow==3))||$channel_id!=2)
		{
	?>
	<div class="check_list">
	<label>
	<input type="checkbox" checked class="channel_single2_full" name="channel_id[]" id="channel_single2" value="<?php echo $channel_id?>">
	<?php echo $channel_name;?>
	</label>
	</div>
	<?php }
	} 
} 
else 
{
?>
<div class="close-i"><i class="fa fa-close"></i> No connected channel data found...</div>
<?php 
} 
?>
</div>
</div>
        </div>        
        </div>
        
        </div>
      </div>
      
       <!--<progress id="progress" value="0"></progress>
		<span id="display"></span>-->

	     <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" id="full_update"> Start Update</button>
      </div>
      </div>
<!--anuangusamy@gmail.com-->