
<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n"><h4><a href="#">Calendar</a>
    <i class="fa fa-angle-right"></i>
    Simple Updates


              </h4></div>
              </div></div>
</div>


<div class="dash-b-n1 new-s">
<div class="row-fluid clearfix">
<div class="col-md-3 col-sm-3 new-k">
<form action="<?php echo lang_url();?>inventory/single_update/update" method="post">

<select class="selectpicker form-control" id="select_room" name="up_property">
     <?php 
	 	$total_room_types = get_data(TBL_ROOM,array('status'=>1))->result_array();
		$i=0;
		foreach($total_room_types as $toatl)
		{
			extract($toatl);
			
			$count = $this->inventory_model->count_room($room_id);
			if($count!=0)
			{
				
				
	 ?>
        <optgroup label="<?php echo $room_type;?>">
        <?php
				$this->db->order_by('property_id','desc');
				$get_room = get_data(TBL_PROPERTY,array('property_type'=>$room_id))->result_array();
				
				foreach($get_room as $av_room)
				{
					extract($av_room);
					
					if($i==0)
					{
						$pro_id = $property_id;
					}
		?>
          <option value="<?php echo $property_id; ?>" <?php if($property_single==$property_id){?> selected <?php } ?>><?php echo ucfirst($property_name);?></option>
          <?php } ?>
        </optgroup>
     <?php
			$i++;}
		}
	?>
        
        
        
      </select>

<div class="sec">
      
<div class="row">
<div class="col-md-5 col-sm-5">Channels:</div>
<div class="col-md-7 col-sm-7">

<div class="link2">
 			<a href="javascript:showHide1('hideShowDiv2');" class="btn btn-default">All channels  <span class="caret"></span></a>
 		</div>
        <div id="hideShowDiv2" style="display:none;" class="drop-bo">
        <div class="close-n pull-right"><a href="javascript:showHide1('hideShowDiv2');"><i class="fa fa-close"></i></a></div>
        <div class="clearfix"></div>
        <div class="main-s">
          <div class="checkbox">
    <div id="cha_replace">
     <?php 
			$get_channel_id = explode(',',get_data(TBL_PROPERTY,array('property_id'=>$property_single))->row()->connected_channel);
			$get_channel = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
			if(count($get_channel_id)!='')
			{
				foreach($get_channel as $channel)
				{
					extract($channel);
					if(in_array($channel_id,$get_channel_id))
					{
		?>
 	  <div class="checkbox">
  		 <label>	<input type="checkbox"  name="up_channel[]" class="channel_single" value="<?php echo $channel_id; ?>" <?php if(in_array($channel_id,$single)) {?> checked <?php } ?>>	<?php echo $channel_name?> 	</label>
	  </div>
      
      	<?php 		}
				}
			}
			else
			{
		  ?>
          <div class="checkbox text-danger">
  		 <label> No connected channel in active </label>
	  </div>
          <?php 
			}
			?>
            </div>
  </div>
  </div>
        </div>


</div>
</div>  
<!--<div class="row">
<div class="col-md-12"><p class="text-right"><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-commenting-o"></i> More options</a></p></div>
</div>-->    
</div>

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">Start date:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <input type="text" class="form-control widh" value="" id="dp1" required name="start_date">
  </div>
  </div>
</div>   

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">End date:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <input type="text" class="form-control widh" value="" id="dp1-p" required name="end_date">
  </div>
</div>
</div> 

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">Availability:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <input type="number" class="form-control widh" id="availability" name="availability"  min="0">
  </div>
</div>
</div>  



<div class="row">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">Rate:</p></div>
<div class="col-md-7 col-sm-7">

  <div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
    <div class="input-group in-p">
      <div class="input-group-addon">Rs</div>
      <input type="number" class="form-control" id="exampleInputAmount" min="0" name="price">
    </div>
  </div>

</div>
</div>



 
<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">Minimum stay:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <select class="form-control" name="minimum_stay" id="minimum_stay"> 
    <option value="0"> Do not change </option>
    <?php for($m=1;$m<=31;$m++) { ?>
    <option value="<?php echo $m;?>"> <?php echo $m;?> </option>
    <?php } ?>
    </select>
  </div>
</div>
</div>

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5">Stop sell	:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <select class="form-control" name="stop_sell" id="stop_sell"> 
    <option value="0"> Do not change </option>
    <option value="1"> Yes </option>
    <option value="2"> No </option>
    </select>
  </div>
</div>
</div>

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5"><!--Closed to arrival (--> CTA <!--)-->:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <select class="form-control" name="cta" id="cta"> 
    <option value="0"> Do not change </option>
    <option value="1"> Yes </option>
    <option value="2"> No </option>
    </select>
  </div>
</div>
</div>

<div class="row ssw">
<div class="col-md-5 col-sm-5"><p class="pad-t-5"><!--Closed to departure (--> CTD <!--)-->:</p></div>
<div class="col-md-7 col-sm-7">
  <div class="form-group">
    <select class="form-control" name="ctd" id="ctd"> 
    <option value="0"> Do not change </option>
    <option value="1"> Yes </option>
    <option value="2"> No </option>
    </select>
  </div>
</div>
</div>
    
<div class="row">
<div class="col-md-5 col-sm-5">Days:</div>
<div class="col-md-7 col-sm-7">

<div class="link3">
 			<a href="javascript:showHide2('hideShowDiv3');" class="btn btn-default">All <span class="caret"></span></a>
 		</div>
        <div id="hideShowDiv3" style="display:none;" class="drop-bo2">
        <div class="close-n pull-right"><a href="javascript:showHide1('hideShowDiv3');"><i class="fa fa-close"></i></a></div>
        <div class="clearfix"></div>
 
  <div class="main-s">
  <?php 
  		$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$values = array('1','2','3','4','5','6','7');
		foreach($days as $key=>$day)
		{
  ?>
  <div class="checkbox">
    <label>
      <input type="checkbox" checked name="days[]" value="<?php echo $values[$key]?>"><?php echo $day?> 
    </label>
  </div>
  <?php } ?>
  
  </div>
  
  
        </div>


</div>
</div>   

<div class="field-box pull-right">
      <input type="submit" value="Apply" name="commit" class="btn btn-primary">
    </div>
    
</form>
</div>

<div class="col-md-9 col-sm-9 new-k2">

<div class="dash-b-n2">
<div class="row">
<div class="col-md-2 col-sm-2">

<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Monthly	
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">Two Weeks</a></li>
    <li><a href="#">Weekly</a></li>
  </ul>
</div>
</div>
<div class="col-md-3 col-sm-3 col-md-offset-3 col-sm-offset-3">
<center>
<a href="#" class="btn btn-default pull-left mar-right"><i class="fa fa-angle-left"></i></a>
<form class="pull-left mar-right">
  <div class="form-group">
    <input type="text" class="form-control widh" value="" id="dp1-p2" placeholder="Go to date">
  </div>
  </form>
<a href="#" class="btn btn-default pull-left"><i class="fa fa-angle-right"></i></a></center>
</div>
<div class="col-md-4 col-sm-4"><div class="pull-right">
    <div class="onoffswitch">
        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
        <label class="onoffswitch-label" for="myonoffswitch">
            <span class="onoffswitch-inner"></span>
            <span class="onoffswitch-switch"></span>
        </label>
    </div>

</div></div>
</div>
</div>


<div class="tab-n2 content inner horizontal-images n-table">
<div class="table table-responsive">
<table class="table table-bordered ">
<tr>
<th style="min-width: 200px;"></th>
<th colspan="4">
<h3>
<strong>August 2015</strong>
</h3>
</th>
<th colspan="27"><h3><strong>September 2015</strong></h3></th>
</tr>
<tr>
<td></td>
<td class="wid-100">28</td>
<td class="wid-100 ha">29</td>
<td class="wid-100 ha">30</td>
<td class="wid-100">31</td>
<td class="wid-100">1</td>
<td class="wid-100">2</td>
<td class="wid-100">3</td>
<td class="wid-100">4</td>
<td class="wid-100 ha">5</td>
<td class="wid-100 ha">6</td>
<td class="wid-100">7</td>
<td class="wid-100">8</td>
<td class="wid-100">9</td>
<td class="wid-100">10</td>
<td class="wid-100">11</td>
<td class="wid-100 ha">12</td>
<td class="wid-100 ha">13</td>
<td class="wid-100">14</td>
<td class="wid-100">15</td>
<td class="wid-100">16</td>
<td class="wid-100">17</td>
<td class="wid-100">18</td>
<td class="wid-100 ha">19</td>
<td class="wid-100 ha">20</td>
<td class="wid-100">21</td>
<td class="wid-100">22</td>
<td class="wid-100">23</td>
<td class="wid-100">24</td>
<td class="wid-100">25</td>
<td class="wid-100 ha">26</td>
<td class="wid-100 ha">27</td>
</tr>
<tr>
<td colspan="32"><span class="online pull-left">Online</span>
<div class="link">
 			<a href="javascript:showHide('hideShowDiv');"><i class="fa fa-caret-right"></i>
 More</a>
 		</div>
</td>
</tr>
<tr>
<td class="ha2">Apartment - 1 guest</td>
<td class="ha3" colspan="31"></td>
</tr>
</table>


<table id="hideShowDiv" style="display:none;" class="table table-bordered n-table2">
<tr>
<td class="ha2" style="width: 200px;">Apartment - 1 guest</td>
<td class="ha3"></td>
</tr>
<tr>
<td class="ha2">Apartment - 1 guest</td>
<td class="ha3" colspan="31"></td>
</tr>
<tr>
<td class="ha2">Apartment - 1 guest</td>
<td class="ha3" colspan="31"></td>
</tr>
</table>
</div>






</div>








</div>

</div>
</div>





   

<!-- dialog box -->
<div class="dial">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">More options</h4>
      </div>
      <div class="modal-body">
      <div class="inner-dia">
       <h4>Rate types</h4>
       
       
       <div class="top-3">
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest Non refundable</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests Non refundable  </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Minimum stay   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select id="basic" class="selectpicker show-tick form-control" data-live-search="true">
          <option>Do not Change</option>
          <option selected>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
          <option>13</option>
          <option>14</option>
          <option>15</option>
          <option>16</option>
          <option>17</option>
          <option>18</option>
          <option>19</option>
          <option>20</option>
          <option>21</option>
          <option>22</option>
          <option>23</option>
          <option>24</option>
          <option>25</option>
          <option>26</option>
          <option>27</option>
          <option>28</option>
          <option>29</option>
          <option>30</option>
          <option>31</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Stop sell   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to arrival (CTA)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to departure (CTD)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       </div>
       
       
       
       
       
       
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">ok</button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- end dialog box -->

