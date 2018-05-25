<div class="dash-b4-n calender-n" id="revenue_resss">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">

</div>


<div class="new-left-menu">
<div class="nav-side-menu">
 
        <div class="menu-list">
  
            <ul id="menu-content" class="menu-content out">
                <li>  
                  <a href="<?php echo lang_url();?>reservation/report_revenue"  <?php if(uri(3)=='report_revenue'){?>class="acc-mn"<?php } ?>>
                  <i class="fa fa-money fa-lg"></i> Revenue
                  </a>
                </li>
                 <li>
                  <a href="<?php echo lang_url();?>reservation/report_reservation" <?php if(uri(3)=='report_reservation'){?>class="acc-mn"<?php } ?>>
                  <i class="fa fa-calendar fa-lg"></i> Reservations
                  </a>
                </li>

                <li>
                  <a href="<?php echo lang_url();?>reservation/nights_revenue" <?php if(uri(3)=='nights_revenue'){?>class="acc-mn"<?php } ?>>
                  <i class="fa fa-moon-o fa-lg"></i> Nights</a>
                </li>
                


                <li>
                  <a href="<?php echo lang_url();?>reservation/report_guest" <?php if(uri(3)=='report_guest'){?>class="acc-mn"<?php } ?>>
                  <i class="fa fa-user fa-lg"></i>Guests </a>
                </li>  
                

                 <li>
                  <a href="<?php echo lang_url();?>reservation/average_revenue" <?php if(uri(3)=='average_revenue'){?>class="acc-mn"<?php } ?>>
                  <i class="fa fa-calculator fa-lg"></i> Average revenue
                  </a>
                  </li>

                 
            </ul>
     </div>
</div>
</div>



</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">
<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>inventory/inventory_dashboard">My Property</a>
    <i class="fa fa-angle-right"></i>
    Reports
     <i class="fa fa-angle-right"></i>
     Average revenue
</h4></div>
<div id="graph_replace">
<div class="col-md-12 datepickerBg">
<div class="row">
		 <div class="col-md-2"> 
		 
          <div class="form-wrapper marB10 marT10 pull-left">
		  
            <div class="btn-group text-left">
			
				<select value="selected" id="report_average" class="form-control" onchange="return average_report();">
					<?php 
					$total_filter = array("today"=>"Today","7"=>"Last 7 days","30"=>"Last 30 days");
					foreach($total_filter as $day_value=>$key)
					{
					?>
					<option value="<?php echo $day_value;?>" <?php if($days==$day_value){?> selected <?php } ?>><?php echo $key?></option>
					<?php } ?>
				
				</select>
				
            </div>
         
          </div>
		  
		  </div>
		   <div class="col-md-2"> 
		   
		    <div class="form-wrapper marB50 marT50 pull-right">
		  
            <div class="btn-group text-right">
			
				<select value="selected" id="channel_average" class="form-control" onchange="return average_report();">
				
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
		  
		  <div class="col-md-4"> 
		 <label class="col-md-2 mar-top7">From</label>
		  <div class="col-md-10"> 
			<input type="text" id="average_from_date" name="from_date" class="form-control">
		  </div>
		</div>
		
		<div class="col-md-4"> 
		  <label class="col-md-2 mar-top7">To</label>
			  <div class="col-md-10"> 
				<input type="text" id="average_to_date" class="form-control">
			  </div>
		  </div>
<div class="box-m">
<div class="chart-container">
<input type="hidden" value='<?php echo str_replace( '\/', '/',$graph);?>' id="revenue_date"/>		
<input type="hidden" value='<?php echo str_replace( '"', '',$graph1);?>' id="revenue_value"/>
<input type="hidden" value='<?php echo $graphnew;?>' id="revenue_graphnew"/>
<input type="hidden" value="Average revenue" id="show_text" name="show_text" />
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto 100px"></div>
</div>
<div id="regions_div" ></div>
</div> 
</div>              
</div>            
</div>        
</div>
</div>
</div>
</body>
</html>
