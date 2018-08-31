
 <div class="container-fluid pad_adjust  mar-top-30 cls_mapsetng">
  <div class=" mar-bot30">
      
      <?php $this->load->view('channel/report_sidebar'); ?>


<div class="col-md-12 col-sm-8 col-xs-12 cls_resp50 cls_triple">


<div id="graph_replace">
<div class="row cls_filterblk clearfix datepickerBg">

			<div class="col-sm-6 col-xs-12 cls_select_in">
                <div class="clearfix">
                  <div class="select-style1">
                    <select value="selected" id="report_revenue"  onchange="return revenue_report();">
					<?php 
					$total_filter = array("today"=>"Today","7"=>"Last 7 days","30"=>"Last 30 days");
					foreach($total_filter as $day_value=>$key)
					{
					?>
					<option value="<?php echo $day_value;?>" <?php if($days==$day_value){?> selected <?php } ?>><?php echo $key?></option>
					<?php } ?>
				
				</select>
                  </div>
                  <div class="select-style1">
                    <select value="selected" id="channel_revenue"  onchange="return revenue_report();">
				
				<option value="all">All</option>
				
					<?php 
						$channel = $this->reservation_model->channel_name();
						foreach($channel as $ch){
							$ch_id = $ch->channel_id;
							$ch_name = $ch->channel_name;
					?>
					<option value="<?php echo $ch_id;?>"><?php echo $ch_name; ?></option>
					
					<?php } ?>
				
				</select>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-xs-12 cls_dateblk">
                <div class="clearfix">
                  <div id="datetimepicker1" class="input-group date cls_resdate">
                   <input type="text" id="report_from_date" name="from_date" class="form-control" placeholder="From">
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                  <div id="datetimepicker2" class="input-group date cls_resdate">
                   	<input type="text" id="report_to_date" class="form-control" placeholder="To">
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
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
<!-- <div id="regions_div" ></div>
</div> -->               
 

</div>        
</div>
</div>
</div>
<?php $this->load->view('channel/dash_sidebar'); ?>
</body>
</html>