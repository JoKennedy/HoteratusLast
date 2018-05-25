<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">
</div>
<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room">
	<div class="new-left-menu">
		<div class="nav-side-menu">
			<div class="menu-list">
				<?php $this->load->view('channel/property_sidebar');?>
			</div>
		</div>
	</div>
</div>            
</div>
</div>
</div>
</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">

<div class="bg-neww">

<div class="tab-content">
						<div class="tab-pane active" id="tab_default_1"><!-- tab1 -->
<div class="pa-n nn2"><h4><a href="<?php echo lang_url(); ?>reservation/payment_list">My Property</a>   <i class="fa fa-angle-right"></i>  Payment methods </h4> </div>

<div class="box-m">
<div class="row">
<div class="col-md-12">
<?php $error = $this->session->flashdata('error'); 
		if($error!="") {
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>
        <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
          <div class="pull-right"> <button type="button"  data-toggle="modal" data-target="#payment" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add payment Method </button></div>

          <?php } ?>
         

<div class="clearfix">  </div> <br> <div class="table-responsive"> <table
class="table table-bordered"> <tr> <?php  $dis=1;
$hotel_id =hotel_id();   
if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
{
  $cash_details = get_data('cash_details',array('user_id'=>user_id(),'hotel_id'=>$hotel_id))->row();  
 }   
else if(user_type()=='2')
{     
  $cash_details = get_data('cash_details',array('user_id'=>owner_id(),'hotel_id'=>$hotel_id))->row();   
} // print_r($cash_details);die; ?> 

<td> Active </td>

<td> Name </td>

<td> Provider </td>

</tr>

<tr>

	<td> <button type="button" class="btn <?php if($cash_details->status=='1'){?>btn-success<?php } else { ?>  btn-danger <?php } ?> btn-sm" id="cash_status_id_<?php echo $cash_details->cash_id?>"><?php if($cash_details->status=='1'){?>Yes<?php } else { echo 'No';}?> </button> </td>

<td> Cash </td>

<td> <a href="javascript:;" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#cash" > Cash </a> </td>

</tr>
<?php 
  $paymentlist = $this->reservation_model->get_paymentlist();
  $hotel_id = hotel_id();
	if($paymentlist){
		$i=0;
	foreach($paymentlist as $list){
		$i++;
		if($list->pay_id=='1')
		{
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1')
      {
			$pay_method_status1 = get_data('bank_info',array('pay_id'=>$list->pay_id,'user_id'=>user_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->user_name;
			}
			else if(user_type()=='2')
      {
			$pay_method_status1 = get_data('bank_info',array('pay_id'=>$list->pay_id,'user_id'=>owner_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->user_name;	
			}
		}
		else if($list->pay_id=='2')
		{
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$pay_method_status1 = get_data('paypal_details',array('paymentid'=>$list->pay_id,'user_id'=>user_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->pay_method_name;
			}
			else if(user_type()=='2'){
			$pay_method_status1 = get_data('paypal_details',array('paymentid'=>$list->pay_id,'user_id'=>owner_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->pay_method_name;	
			}
		}
		else if($list->pay_id=='3')
		{
			if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
			$pay_method_status1 = get_data('cash_details',array('pay_id'=>$list->pay_id,'user_id'=>user_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->user_name;
			}
			else if(user_type()=='2'){
			$pay_method_status1 = get_data('cash_details',array('pay_id'=>$list->pay_id,'user_id'=>owner_id(),'hotel_id'=>$hotel_id))->row();
			$pay_method_status = $pay_method_status1->status;
			$pay_method_name = $pay_method_status1->user_name;
			}
		}
 ?>
<tr>
<td> <button type="submit" class="btn <?php if($pay_method_status=='1'){ ?> btn-success <?php } else { ?> btn-danger <?php } ?> btn-sm"> <?php if($pay_method_status=='1'){echo 'yes';}else{echo 'No';} ?> </button> </td>

<td> <?php echo $pay_method_name;?></td>

<td> 
<?php if($list->pay_id=='1'){ ?>

<a href="<?php echo lang_url(); ?>reservation/payment_bank"><?php echo $list->payment_type; ?></a>

<?php }elseif($list->pay_id=='2'){ ?>

<a href="<?php echo lang_url(); ?>reservation/edit_paypal"><?php echo $list->payment_type; ?></a>

<?php }elseif($list->pay_id=='3'){ ?>
<a href="javascript:;" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#cash" > Cash </a> </td>
<?php } ?>
</td>

</tr>

	<?php } }else { $i=0;} ?>


</table>
</div>

<p> Displaying all <?php echo $dis+$i;?> </p>

</div>
</div>
</div>               
</div><!-- end tab2 -->
<div class="tab-pane" id="tab_default_2"><!-- tab2 -->tab2</div><!-- end tab2 -->
<div class="tab-pane" id="tab_default_3"><!-- tab3 -->tab3</div><!-- end tab3 -->
<div class="tab-pane" id="tab_default_4"><!-- tab4 -->tab4</div><!-- end tab4 -->
<div class="tab-pane" id="tab_default_5"><!-- tab4 -->
<div class="box-m new-ta">
<div class="table table-responsive">
<table class="table table-bordered">
<tr class="info">
<th class="bor-lef-1"><span>Date res.</span></th>
<th><span>Period of stay</span>
<select class="form-control" style="width:100px;">
  <option>All</option>
  <option>Today's arrivals</option>
  <option>Today's Departures</option>
  <option>Checked in</option>
  <option>Tomorrow Arrivals</option>
  <option>Next 3 days Arrival</option>
  <option>Next 7 days Arrival</option>
</select>
</th>
<th><span>Family Name</span></th>
<th><span>Reservation Code</span></th>
<th><span>Pax</span></th>
<th><span>night(s)</span></th>
<th><span>Extra Details</span></th>
<th><span>Room name</span></th>
<th><span>Status</span></th>
<th><span>Reservation Details</span> </th>
</tr>
<tr>
<td class="bor-lef-1">
<span>01/09/2015 12:48:43</span>
</td>
<td>in 01/09/2015 out 02/09/2015</td>
<td>fhfh</td>
<td>010950640A <a href="#"><i class="fa fa-print"></i></a></td>
<td>autosubmit bbliverate <img src="assets/images/oc.jpg" class="pad-top7"></td>
<td>3 </td>
<td>1</td>
<td>51094<br/> 
test (3 pax) ()
<select class="form-control pad-top7" style="width:100px;">
  <option>please assign room</option>
  <option selected>test :   3</option>
  <option>-No Room-</option>
</select>
<a class="btn btn-default pad-top7" href="#" role="button">Update</a>
</td>
<td><span class="bl">Confirmed</span><br/><span>autosubmit <br/> bbliverate</span><br/><br/> <center><a href="#" class="pri-i"><i class="fa fa-print"></i><br/> Voucher</a></center> </td>
<td><a href="#">Details</a><br/> Total 300.00</td>
</tr>
<tr class="info">
<td colspan="10">
<div class="col-md-4 col-sm-4 center-block ftn bun-n">
<span class="pull-left">Budget of:</span> <select class="form-control pull-left" style="width:100px;">
  <option>Select month</option>
  <option selected>January</option>
  <option>February</option>
  <option>March</option>
  <option>April</option>
  <option>May</option>
  <option>June</option>
  <option>July</option>
  <option>August</option>
  <option>September</option>
  <option>October</option>
  <option>November</option>
  <option>December</option>
</select><a class="btn btn-default pull-left" href="#" role="button">Go</a></div></td>
</tr>
</table>
</div>

</div>


</div><!-- end tab5 -->
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



<div class="dial3">
<div class="modal fade" id="myModal-p1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">New Room Type</h3>
      </div>
      <div class="modal-body">
       <div class="box-dialog row-pad-top-20">
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Type Name</span></div>
 <div class="col-md-8 col-sm-8"> <input type="text" class="form-control" id="exampleInputEmail1" placeholder="E.g. double bed room"></div>
 </div>    
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Description</span></div>
 <div class="col-md-8 col-sm-8"><textarea class="form-control" rows="2" placeholder="Describe the room type. Maximum of 1000 characters."></textarea></div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Number of rooms</span></div>
 <div class="col-md-4 col-sm-4"><select class="form-control">
  <option>1 room</option>
  <option>2 rooms</option>
  <option>3 rooms</option>
  <option>4 rooms</option>
  <option>5 rooms</option>
  <option>6 rooms</option>
  <option>7 rooms</option>
  <option>8 rooms</option>
  <option>9 rooms</option>
  <option>10 rooms</option>
  <option>11 rooms</option>
  <option>12 rooms</option>
  <option>13 rooms</option>
  <option>14 rooms</option>
  <option>15 rooms</option>
  <option>16 rooms</option>
  <option>17 rooms</option>
  <option>18 rooms</option>
  <option>19 rooms</option>
  <option>20 rooms</option>
  <option>21 rooms</option>
  <option>22 rooms</option>
  <option>23 rooms</option>
  <option>24 rooms</option>
  <option>25 rooms</option>
  <option>26 rooms</option>
  <option>27 rooms</option>
  <option>28 rooms</option>
  <option>29 rooms</option>
  <option>30 rooms</option>
  <option>31 rooms</option>
  <option>32 rooms</option>
  <option>33 rooms</option>
  <option>34 rooms</option>
  <option>35 rooms</option>
  <option>36 rooms</option>
  <option>37 rooms</option>
  <option>38 rooms</option>
  <option>39 rooms</option>
  <option>40 rooms</option>
  <option>41 rooms</option>
  <option>42 rooms</option>
  <option>43 rooms</option>
  <option>44 rooms</option>
  <option>45 rooms</option>
  <option>46 rooms</option>
  <option>47 rooms</option>
  <option>48 rooms</option>
  <option>49 rooms</option>
  <option>50 rooms</option>
  <option>51 rooms</option>
  
  
</select></div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Type</span></div>
 <div class="col-md-9 col-sm-9"> 
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Private Room
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Shared Room (dorm)
  </label>
</div>
Shared rooms are sold by the bed, private rooms only by full rooms
 </div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Occupancy</span></div>
 <div class="col-md-9 col-sm-9"><select class="form-control">
  <option>1 persion</option>
  <option>2 persions</option>
  <option>3 persions</option>
  <option>4 persions</option>
  <option>5 persions</option>
  <option>6 persions</option>
  <option>7 persions</option>
  <option>8 persions</option>
  <option>9 persions</option>
  <option>10 persions</option>
  <option>11 persions</option>
  <option>12 persions</option>
  <option>13 persions</option>
  <option>14 persions</option>
  <option>15 persions</option>
  <option>16 persions</option>
  <option>17 persions</option>
  <option>18 persions</option>
  <option>19 persions</option>
  <option>20 persions</option>
  <option>21 persions</option>
  <option>22 persions</option>
  <option>23 persions</option>
  <option>24 persions</option>
  <option>25 persions</option>
  <option>26 persions</option>
  <option>27 persions</option>
  <option>28 persions</option>
  <option>29 persions</option>
  <option>30 persions</option>
  <option>31 persions</option>
  <option>32 persions</option>
  <option>33 persions</option>
  <option>34 persions</option>
  <option>35 persions</option>
  <option>36 persions</option>
  <option>37 persions</option>
  <option>38 persions</option>
  <option>39 persions</option>
  <option>40 persions</option>
  <option>41 persions</option>
  <option>42 persions</option>
  <option>43 persions</option>
  <option>44 persions</option>
  <option>45 persions</option>
  <option>46 persions</option>
  <option>47 persions</option>
  <option>48 persions</option>
  <option>49 persions</option>
  <option>50 persions</option>
  <option>51 persions</option>  
</select>
<p>Enter how many persons can stay in this room type.<br/>
For a private room, the Occupancy field is the Max number of People who can occupy the room.<br/>
For a shared dormitory, Occupancy is the total number of beds, and one person per bed is assumed. 
</p>
</div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Dorm Gender</span></div>
 <div class="col-md-9 col-sm-9"> 
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Mixed
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Males
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Females
  </label>
</div>
Room gender is only applicable for dorms 
 </div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Images</span></div>
 <div class="col-md-9 col-sm-9"> 
 <input type="file" id="exampleInputFile">
Images are only required should you wish to use the TripConnect InstantBooking feature. They are currently not used anywhere else.
 </div>
 </div>
 <div class="row button">
 <div class="col-md-9 col-sm-9 col-md-offset-3 col-sm-offset-3 s1"> 
<a class="btn btn-primary hvr-sweep-to-right" href="#">Add</a>
 </div>
 </div>
 </div>
       
       
       
       
      </div>
      
    </div>
  </div>
</div>
</div>
<!-- end dialog box -->

<!-- Modal -->
<div class="modal fade lag" id="myModal-pt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        
        <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th>Number of guests</th>
        <th>Refundable</th>
        <th>Non refundable</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">1</span></div></td>
        <td class="pa-t-pa-b">

        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div>
        </td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div></td>
        </tr>
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">2</span></div></td>
        <td class="pa-t-pa-b">

        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div>
        </td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div></td>
        </tr>
        </tbody>
        </table>
        </div>
        
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	
	  <form class="form-horizontal" method="post" action="<?php echo lang_url(); ?>reservation/get_payment_method" id="get_payment" name="get_payment">
	  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Add payment method </h4>
      </div>
      <div class="modal-body">
      
	  <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> Provider </label>
		<div class="col-sm-8">
		  <select class="form-control" name="provider" required> 
		  <?php $paypal_add = $this->reservation_model->get_payment_name();
		     foreach($paypal_add as $pay_add){
				 $pay_id = $pay_add->pay_id;
				 $pay_name = $pay_add->payment_type; 
		  ?>
		  <option value="<?php echo $pay_id; ?>"><?php echo $pay_name; ?></option>
			 <?php } ?>
		  </select>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="inputPassword3" class="col-sm-4 control-label">Name </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="inputPassword3" placeholder="Name" name="method_name">
		</div>
	  </div>
	  
     </div>
	 
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" name="select_paypal" value="select_paypal" id="select_paypal"><span id="sel_paypal">Save changes</span></button>
	  </div>
	  
	   </form>
    </div>
  </div>
</div>


<div class="modal fade" id="cash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/edit_cash" method="post" id="cash_edit">
	<?php 
		$cash_det = $this->reservation_model->get_cash();
		$cash_main = get_data('payment_list',array('pay_id'=>$cash_det->pay_id))->row();
	?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Edit Cash Details  </h4>
      </div>
      <div class="modal-body">
	  
  <div class="form-group">		
    <label for="inputEmail3" class="col-sm-4 control-label"> Provider </label>
    <div class="col-sm-8" style="margin-top:7px">
Cash
     <?php //if(isset($cash_main)) { echo $cash_main->payment_type;} ?>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> Name  <span class="errors">*</span></label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" placeholder="" name="user_name" value="<?php echo $cash_det->user_name; ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> Description  <span class="errors">*</span></label>
    <div class="col-sm-8">
	  <textarea id="txtEditor" name="user_des" required class="form-control"><?php  echo $cash_det->description; ?>
    </textarea> 
 </div>
  </div>
  
  

      </div>
	  
 <div class="modal-footer">
	<?php if($cash_det->status==0){ ?>
  <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="cash_state">    
        	<a data-remotes="true" href="javascript:;" class="cash_active" id="cash_active" type="cash" method="active" data-id="<?php echo $cash_det->cash_id;?>">
            <input type="hidden" id="cash_current_status" value="<?php echo $cash_det->status; ?>" />
        		<div class="onoffswitch-wrap switch-label-deactivate">
          			<div class="onoffswitch">
			            <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel">
				            <label for="active-channel" class="onoffswitch-label"></label>
		          	</div>
         					<label class="switch-label" for="active-channel">Passive</label>
        		</div>

			</a>
		</div>
    <?php } ?>
        <?php } else if($cash_det->status == 1) { ?>

        <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
      		
        <div class="pull-left policy-state-action" id="cash_state">
            <a data-remotes="true" href="javascript:;" class="cash_active" id="cash_active" type="cash" method="passive" data-id="<?php echo $cash_det->cash_id;?>">
 			<input type="hidden" id="cash_current_status" value="<?php echo $cash_det->status; ?>" />
        <div class="onoffswitch-wrap ">
          <div class="onoffswitch">
            <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
            <label for="active-channel" class="onoffswitch-label"></label>
          </div>
          <label class="switch-label" for="active-channel">Active</label>
        </div>
			</a>
      </div>
	    <?php } ?>
      <?php } ?>
	<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
		<button type="submit" name="save" value="save" class="btn btn-info pull-right"> Save Changes</button>
    <?php }?>
	</div>
	
	  </form>
    </div>
  </div>
</div>
<script>
function get_payapl(){
	 $('#sel_paypal').html('');
	var select_paypal = $('#select_paypal').val();
	$.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/get_payment_method",
		data:{"select_paypal":select_paypal},
		beforeSend:function(){
			$('#sel_paypal').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
			$('#sel_paypal').attr('disabled',true);
		},
		complete:function(){
			$('#sel_paypal').html('Save changes');
			$('#sel_paypal').attr('disabled',false);
		},
		success:function(msg){
			$('#sel_paypal').html('msg');
		}
	});
	 return false;
}
</script>

</body>

</html>


