<div class="col-md-12 datepickerBg">
<div class="col-sm-6 col-xs-12 cls_select_in">
                <div class="clearfix">
                  <div class="select-style1">
                   <select value="selected" id="report_cancellation" onchange="return report_cancellation();">
					<?php 
					$total_filter = array("today"=>"Today","7"=>"Last 7 days","30"=>"Last 30 days");
					foreach($total_filter as $day_value=>$key)
					{
					?>
					<option value="<?php echo $day_value;?>" <?php if($days==$day_value){?> selected <?php } ?>><?php
						if($days!='today')
						{
						if($days==$day_value){ echo $key.'('.$start_dates.'-'.$end_dates.')';}else { echo $key;}}else { echo $key;}?></option>
					<?php } ?>
				
				</select>
                  </div>
                  <div class="select-style1">                  

                   
				<select value="selected" id="channel_cancellation" onchange="return report_cancellation();">
				
					<option value="all">All</option>
				
					<?php 
						$channel = $this->reservation_model->channel_name();
						foreach($channel as $ch){
							$ch_id = $ch->channel_id;
							$ch_name = $ch->channel_name;
					?>
					 <option value="<?php echo $ch_id;?>" <?php if($channel_plan==$ch_id){?> selected <?php } ?>><?php echo $ch_name; ?></option>
					
					<?php } ?>
				
				</select>
                  </div>
                </div>
              </div>

		  			


		  <div class="col-sm-6 col-xs-12 cls_dateblk">
                <div class="clearfix">
                  <div id="datetimepicker1" class="input-group date cls_resdate">
                  <input type="text" id="cancel_from_date" name="from_date" class="form-control" placeholder="From" value="<?php echo $from_date; ?>">
                   
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                  <div id="datetimepicker2" class="input-group date cls_resdate">
                  	<input type="text" id="cancel_to_date"   class="form-control" placeholder="To" value="<?php echo $to_date; ?>">
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                </div>
              </div>
    
<div class="box-m">

	<div class="chart-container">
	
		<input type="hidden" value='<?php echo str_replace( '\/', '/',$graph);?>' id="revenue_date"/>		
		<input type="hidden" value='<?php echo str_replace( '"', '',$graph1);?>' id="revenue_value"/>
		<input type="hidden" value='<?php echo $graphnew;?>' id="revenue_graphnew"/>
        <input type="hidden" value="Cancellation" id="show_text" name="show_text" />
							
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto 100px"></div>					
			
		</div>

<!-- 	<div id="regions_div" ></div> -->

</div>               
       
