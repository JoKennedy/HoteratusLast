<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">
</div>
<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
<?php $this->load->view('channel/property_sidebar');?>
</div></div></div></div>            
</div>
</div>
</div>
</div>
<div class="col-md-8 col-sm-8" style="padding-right:0;">

<div class="bg-neww">

<div class="tab-content">
<div class="tab-pane active" id="tab_default_1">
<div class="pa-n nn2">
	<h4><a href="<?php echo lang_url(); ?>reservation/payment_list">My Property</a>   
	<i class="fa fa-angle-right"></i>  Payment methods </h4> </div>

<div class="box-m">
<div class="row">

<?php $error = $this->session->flashdata('error'); 
		if($error!="") {
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>

<div class="col-md-12">

  <form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/get_bank_details" method="post">
  <?php 
		$bank_det = $this->reservation_model->get_bank(); 
		/* echo '<pre>';
		print_r($bank_det);die; */
		$bank_type = get_data('payment_list',array('pay_id'=>$bank_det->pay_id))->row();
	?>
	  <div class="form-group">
			<label for="inputEmail3" class="col-sm-3 control-label"> Provider </label>
				<div class="col-sm-8" style="margin-top:7px">
				 <?php if(isset($bank_type->payment_type)) { echo $bank_type->payment_type;} ?>
				</div>
	  </div>
	  
	  <div class="form-group">
			<label for="inputPassword3" class="col-sm-3 control-label"> Name  </label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="inputPassword3" name="user_name" value="<?php if($bank_det!=''){ echo $bank_det->user_name; }  ?>">
				</div>
	  </div>
	  
	  
	  
	  <div class="pull-right">
	  <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
       <button type="submit" name="save" value="save" class="btn btn-success pull-left btn-right"> Save </button>
	   
		 <button type="button"  data-toggle="modal" data-target="#addbank_account"  class="btn btn-info pull-right"> <i class="fa fa-plus-circle"> </i> Add Bank account  </button>
	  <?php } ?>
	  </div>
	  
	  <div class="clearfix"></div>

<p>&nbsp;</p>
<div class="table-responsive">
<table class="table table-bordered">

<tr>
<td> Bank name </td>
<td> Currency  </td>
</tr>
<?php $bank_details = $this->reservation_model->get_bankdetails();
	 if($bank_details){ 
	 foreach($bank_details as $bank){ 
	 ?>
<tr>

	<input type="hidden" name="bank_id" value="<?php echo $bank->bank_id; ?>" id="bank_<?php echo $bank->bank_id; ?>">
	 
	<td> <a href="javascript:;" onclick="return edit_bank_details('<?php echo $bank->bank_id; ?>');"> <?php echo $bank->bank_name; ?>  </a>
	</td>
	<?php $currency1 = $this->reservation_model->country_name_id($bank->currency);
		  $currency_codes = $currency1->currency_code; ?>
	
    <td> <?php echo $currency_codes; ?>  </td>

</tr>
	 

 <?php } }else{ ?>
<tr>
<td colspan="3">
 	  <div class="alert alert-danger text-center"><strong>No Bank Details Found.</strong></div>
      </td>
</tr>	  
 <?php } ?>
 
</table>
</div>
<br>

   <!--<div>
	<button type="submit" name="save" value="save" class="btn btn-success pull-right"> Save  </button>
	</div>-->
	<?php if($bank_det!='')
	{
		?>
	<div class="modal-footer">
	<?php if($bank_det->status==0){ ?>
	<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="bank_state">    
        <a data-remotes="true" href="javascript:;" class="bank_active" id="bank_active" type="banktransfer" method="active" data-id="<?php echo $bank_det->bank_info_id;?>">
            <input type="hidden" id="bank_current_status" value="<?php echo $bank_det->status; ?>" />
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
        <?php } else if($bank_det->status == 1) { ?>
      		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="bank_state">
         <a data-remotes="true" href="javascript:;" class="bank_active" id="bank_active" type="banktransfer" method="passive" data-id="<?php echo $bank_det->bank_info_id;?>">
 			<input type="hidden" id="bank_current_status" value="<?php echo $bank_det->status; ?>" />
        <div class="onoffswitch-wrap ">
          <div class="onoffswitch">
            <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
            <label for="active-channel" class="onoffswitch-label"></label>
          </div>
          <label class="switch-label" for="active-channel">Active</label>
        </div>
			</a>
      </div>
	    <?php } } ?>
	
		
	</div>
    <?php
	}
	?>
	
	</form>
	
</div>
</div>

</div>               
</div>

</div>        
      </div> 
              
      </div>
              
<div class="col-md-2 col-sm-2" style="padding-right:0;">

<div class="alert  new_info alert-info hidden-tablet marT0">
        <i class="fa fa fa-lightbulb-o pull-left"></i>
        Accepting various types of payment methos from online channels helps increasing your sales and guest satisfaction.<br><br>Information needed to activate each payment method may vary depending on payment providers. If you don't know how to configure your payment methods, you can <a target="_blank" href="http://support.hotelrunner.com">contact us</a>.
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
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Add payment method </h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label"> Provider </label>
    <div class="col-sm-8">
      <select class="form-control"> <option>  Akbank </option></select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Name </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" placeholder="">
    </div>
  </div>
  
  
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


 <div class="modal fade" id="addbank_account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/payment_bank" method="post" id="bank_det">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Add bank account </h4>
      </div>
	  
      <div class="modal-body">
	 
	 
	  
       <div class="form-group">
		<label for="account_owner" class="col-sm-4 control-label">Account owner <span class="errors">*</span></label>
		   <div class="col-sm-8">
		   <input type="text" class="form-control" id="account_owner" name="account_owner" required>
		   </div>
       </div>
       
      
       <div class="form-group">
		  <label for="inputPassword3" class="col-sm-4 control-label">Currency</label>
		   <div class="col-sm-8">
			<select class="form-control" name="currency" required>
			<?php $currency = $this->reservation_model->get_currency_name(); 
			foreach($currency as $cur_name){
				$currency_id  = $cur_name->currency_id;
				$currency_name = $cur_name->currency_code; ?>
			<option value="<?php echo $currency_id; ?>"> <?php echo $currency_name; ?> </option>
			<?php } ?>
			</select>
		   </div>
       </div>
	   
       <div class="form-group">
		<label for="bank_name" class="col-sm-4 control-label">Bank name <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="bank_name" name="bank_name" required>
		   </div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label">Branch name <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="inputEmail3" name="branch_name" required>
		   </div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> Branch code <span class="errors">*</span></label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="inputEmail3" name="branch_code" required>
			</div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> Swift code <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="swift_code" name="swift_code" required>
		   </div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> IBAN <span class="errors">*</span></label>
		   <div class="col-sm-8">
				<input type="text" class="form-control" id="inputEmail3" name="iban" required>
		   </div>
       </div>
       
        <div class="form-group">
		   <label for="inputEmail3" class="col-sm-4 control-label"> Account Number <span class="errors">*</span></label>
			   <div class="col-sm-8">
					<input type="text" class="form-control" id="inputEmail3"  name="account_number" required>
					
			   </div>
       </div>
       
      </div>
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" name="save" value="save" class="btn btn-primary">Save changes</button>
		
      </div>
	  
	  </form>
    </div>
  </div>
</div>
<!-- edit modal -->

<div class="modal fade" id="editbank_account" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
	<form class="form-horizontal" method="post" id="edit_bank_detailsss" action="<?php echo lang_url(); ?>reservation/edit_bankdetailss">
	<input type="hidden" name="bank_id" value="<?php echo $bank->bank_id; ?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Edit bank account </h4>
      </div>
	  
      <div class="modal-body" id="edit_results">  
	  </div>
	  </form>
	
    </div>
  </div>
</div>

<!-- edit modal -->

<script>
 function delete_bankdetails(id){
	 $('#del_bankdetails').html('');
	 var del_bank = $('#del_'+id).val();
	 if(confirm('Are u sure want to delete the Bankdetails?'))
	 {
	 $.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/	",
		data:{"del_bank":del_bank},
		success:function(msg){
			$('#del_bankdetails').html('msg');
			// $('#editbank_account_'+id).modal('hide');
			$('#editbank_account').modal('hide');
			 location.reload();
		}
	 });
	 return false;
	}
	else
	{
		return false;
	}
	 
 }
 </script>
 
 
 <script>
 function edit_bank_details(id){
	 $.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/edit_bankdetails",
		data:"bank_id="+id,
		success:function(msg){
			$('#edit_results').html(msg);
			$('#editbank_account').modal({backdrop: 'static',keyboard: false});
			// location.reload();
		}
	 });
	 return false;
 }
 </script>
 
</html>


