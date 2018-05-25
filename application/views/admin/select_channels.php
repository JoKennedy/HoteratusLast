
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#ind_plan').prop('selectedIndex',0);"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" align="center">Select up to <?php echo $plan_details['number_of_channels']?> channels to connect your hotels.</h4>
      </div>
      <div id="full_update_success">
      <div class="modal-body">
        <div class="calen-2">
        
        <div class="row">
        <div class="col-md-12 col-sm-12">
       		<div class="col-md-12 col-sm-12">
<div class="content_full" id="div1">
</div>
<input type="hidden" value="<?php echo $plan_details['plan_id'];?>" name="ind_plan"/>
<div class="multiselect multiselect-channels width-form-full well content_full nf-select'" id="div2">
            
            <?php 
			if(count($all_channels)!=0)
			{
			?>
            <!--<label class="check_header">
              <input type="checkbox" value="1" name="all_check" multiple="multiple" id="all_check_toggle" class="all_check_toggle" checked="checked"> All
            </label>-->
            <?php
			foreach ($all_channels as $room_value) { extract($room_value); ?>
			<div class="check_list">
			<label>
		    <input type="checkbox" class="channel_single2_full" name="subscribe_channel_id[]" id="channel_single2" value="<?php echo $channel_id?>">
		    <?php echo $channel_name;?>
			</label>
			</div>
			<?php } } else {  ?>
			<div class="close-i"><i class="fa fa-close"></i> No connected channel data found...</div>
			<?php } ?>
          </div>
          
          <label for="subscribe_channel_id[]" class="error" style="display: inline-block;"></label>
</div>
        </div>        
        </div>
        
        </div>
      </div>
      
       <!--<progress id="progress" value="0"></progress>
		<span id="display"></span>-->

	     <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="$('#ind_plan').prop('selectedIndex',0);">Cancel</button>
        <button type="submit" class="btn btn-danger" id="full_update"> Continue</button>
      </div>
      </div>
<script type="text/javascript">
$('#select_channels_to_subscribe').validate({
			rules :
			{
				"subscribe_channel_id[]" : { required: true,
				
				maxlength: <?php echo $plan_details['number_of_channels']?>
                             },
			},
			/*errorPlacement: function(){
            return false;  // suppresses error message text
    		},*/
			messages : 
				{
					"subscribe_channel_id[]":{ 	
												
												maxlength :'Select no more than <?php echo $plan_details['number_of_channels']?> channels',
												required : " Select channel to connect "
											},
				},
			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
	});
</script>

