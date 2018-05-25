<div class="col-md-12 datepickerBg">
<div class="row">
<div class="col-md-4">
          <div class="form-wrapper marB10 marT10 pull-left">
		  
            <div class="btn-group text-left">
			
				<select value="selected" id="report_revenue" class="form-control" onchange="return revenue_report();">

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
         
          </div>
		  </div>
		  <div class="col-md-2">
		   <div class="form-wrapper marB10 marT10 pull-left">
		  
            <div class="btn-group text-left">
			
				<select value="selected" id="channel_revenue" class="form-control" onchange="return revenue_report();">
				
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
		  
		  <div class="col-md-3">
			<label class="col-md-2 mar-top7">From</label>		  
			  <div class="col-md-8"> 
				<input type="text" id="report_from_date" value="<?php echo $from_date; ?>" name="from_date" class="form-control">
				
			  </div>
		  </div>
		  
		  <div class="col-md-3"> 
		  <label class="col-md-2 mar-top7">To</label>
			  <div class="col-md-8"> 
				<input type="text" id="report_to_date" value="<?php echo $to_date; ?>" class="form-control">
			  </div>
		  </div>
		  
          <div class="clearfix"></div>
		  </div>
        </div>

<div class="box-m">
<div class="chart-container">

<input type="hidden" value='<?php echo str_replace( '\/', '/',$graph);?>' id="revenue_date"/>		
<input type="hidden" value='<?php echo str_replace( '"', '',$graph1);?>' id="revenue_value"/>
<input type="hidden" value='<?php echo $graphnew;?>' id="revenue_graphnew"/>

<input type="hidden" value="Revenue" id="show_text" name="show_text" />
						
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto 100px"></div>					
</div>
<div id="regions_div" ></div>
</div> 