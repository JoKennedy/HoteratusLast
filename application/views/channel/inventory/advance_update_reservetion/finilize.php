<div class="modal fade dialog-3 " id="modal_single_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document" id="single_update"></div>
</div>

<div class="dial2">
	<div class="modal fade dialog-3 " id="reservation_user_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document" id="reservation_user"></div>
	</div>
</div>

<!-- Customize Calender -->
<div class="modal fade dia-l" id="myModal-p" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form class="mar-right" id="customize_calender" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Customize Calendar</h4>
				</div>
				<div class="modal-body">
					<div class="calen-2">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<h4>Start Date:</h4>
								<div class="form-group">
									<input type="text" class="form-control widh" value="<?php echo $curr_date;?>" id="datepicker_start" name="start_date">
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<h4>End Date:</h4>
								<div class="form-group">
									<input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_end" name="end_date">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="customize">Continue</button>
				</div>
			</div>
		</form>
  </div>
</div>

<div class="modal fade dia-l" id="myModal-ps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form class="mar-right" id="customize_calenders" method="post">
			<input type="hidden" name="custom_channel_id" id="custom_channel_id" />
			<input type="hidden" name="custom_channel_rate_id" id="custom_channel_rate_id" />
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Customize Calendar</h4>
				</div>
				<div class="modal-body">
					<div class="calen-2">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<h4>Start Date:</h4>
								<div class="form-group">
									<input type="text" class="form-control widh" value="<?php echo $curr_date;?>" id="datepicker_starts" name="start_date">
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<h4>End Date:</h4>
								<div class="form-group">
									<input type="text" class="form-control widh" value="<?php echo $add_date;?>" id="datepicker_ends" name="end_date">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="customizes">Continue</button>
				</div>
			</div>
		</form>
  </div>
</div>
<!-- Customize Calender -->

<!-- Full Refresh -->
<div class="modal fade dia-2" id="myModal-p2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form method="post" id="main_full_update">
			<div class="modal-content main_full_update"></div>
		</form>
	</div>
</div>
<!-- Full Refresh -->

<!-- Full Update -->
<div class="modal fade dia-2" id="channelsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 id="channels-header">Channels to update</h4>
			</div>
			<div class="modal-body">
				<div class="form-group vertical-top clearfix">
					<div class="multiselect multiselect-channels width-form-full">
						<?php if(count($con_cha)!=0) { ?>
							<label class="check_header">
								<input type="checkbox" value="1" name="all_check" multiple="multiple" id="all_check_toggle" class="all_check_toggle" checked="checked"> All
							</label>
							<?php
							foreach($con_cha as $connected)
							{
								
							?>
								<div class="check_list">
									<label>
										<input type="checkbox" checked class="channel_single2" name="channel_id[]" id="channel_single2" value="<?php echo $connected['channel_id']?>">
										[<?php echo $connected['channel_name'];?>]
									</label>
								</div>
							<?php
							}
						}
						else
						{
						?>
							<div class="close-i"><i class="fa fa-close"></i> No connected channel data found...</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="modal-footer" id="channels-footer">
				<button data-dismiss="modal" class="pull-left btn btn-default" type="button">Close</button>
				<a data-dismiss="modal" class="btn btn-primary save_button_master_calendar" href="javascript:;">Update</a>
			</div>
		</div>
	</div>
</div>
<!-- Full Update -->
