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
<div class="col-md-10 col-sm-10" style="padding-right:0;">

<div class="bg-neww">

<div class="tab-content">
						<div class="tab-pane active" id="tab_default_1"><!-- tab1 -->
<div class="pa-n nn2"><h4><a href="#">My Property</a>   <i class="fa fa-angle-right"></i>  Policies </h4> </div>

<div class="box-m">
<div class="row">
<div class="col-md-12">


<br>


<div class="table-responsive">
<table class="table table-bordered">

<tr class="info">

<td> Status </td>

<td> Policy type </td>


</tr>
<?php 
	$all_polioies = get_data(POLICY,array('status'=>1))->result_array();
	if(count($all_polioies)!=0)
	{
		foreach($all_polioies as $polices)
		{
			extract($polices);
			if($policy_id==1)
			{
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$policy_status = get_data(PDEPOSIT,array('user_id'=>user_id(),'policy_id'=>$policy_id,'hotel_id'=>hotel_id()))->row();
				}
        else if(user_type()=='2'){
          $policy_status = get_data(PDEPOSIT,array('user_id'=>owner_id(),'policy_id'=>$policy_id,'hotel_id'=>hotel_id()))->row();
        }
				$p_staus= $policy_status->status;
				if($p_staus==0)
				{
					$cls='btn-danger';
				}
				else
				{
					$cls='btn-success';
				}
				
			}
			else if($policy_id==2)
			{
        if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
				$policy_status = get_data(PCANCEL,array('user_id'=>user_id(),'policy_id'=>$policy_id,'hotel_id'=>hotel_id()))->row();
        }
        else if(user_type()=='2'){
          $policy_status = get_data(PCANCEL,array('user_id'=>owner_id(),'policy_id'=>$policy_id,'hotel_id'=>hotel_id()))->row();
        }
				$p_staus= $policy_status->status;
				if($p_staus==0)
				{
					$cls='btn-danger';
				}
				else
				{
					$cls='btn-success';
				}
			}
			
?>
<tr>

<td> 
<?php if($policy_id==1 || $policy_id==2)
{
	?>
<button type="button" id="status_id_<?php echo $policy_id;?>"class="btn <?php if($policy_id==1 || $policy_id==2){ echo $cls;}else { echo 'btn-default';}?> btn-sm"> <?php if($p_staus==1){ echo 'Active';}elseif($p_staus==0){ echo 'Passive';}?>  </button> 
<?php } else if($policy_id==3){ echo '&nbsp;------------';	}?>
</td>
<td> <a href="javascript:;" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#<?php echo $modal_id; ?>"> <?php echo $policy_name;?> </a></td>
</tr>
<?php  } 
	}
	else
	{
		?>
		</tr>
		<tr style="display: table-row;">
		<td class="text-center text-danger" colspan="6">No records found...</td>
		</tr>
		<?php
	}
?>

</table>
</div>


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

<!-- DEPOSIT MODAL START-->

<div class="modal fade" id="deposit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Deposit policy  </h4>
      </div>
       <form class="form-horizontal" method="post" action="<?php echo lang_url();?>channel/policies/deposit">
		<input type="hidden" id="usr_cur_id" value="<?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code;?>" />
      <div class="modal-body">
      <?php 
      if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
      $doposit_details = get_data(PDEPOSIT,array('user_id'=>user_id()))->row();
      }
      else if(user_type()=='2'){
        $doposit_details = get_data(PDEPOSIT,array('user_id'=>owner_id()))->row();
      }
      ?>
		
          <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label"> Fee  </label>
    <div class="col-sm-8">
    <div class="row">
    <div class="col-md-6 ol-sm-6">
   
   <select class="form-control deposit_fee" name="fee_type" id="fee_type">
   <?php
   		$fee_type_array = array('1'=>'Please select','flat'=>'Fixed fee','%'=>'Fixed percentage','night'=>'Fixed fee per night');
		foreach($fee_type_array as $type=>$key)
		{
	?>
   <option value="<?php echo $type;?>" <?php if($type==$doposit_details->fee_type) {?> selected="selected" <?php } ?>><?php echo $key;?></option>
   <?php 
		}
	?>
	
   </select>
    </div>
     <div class="col-md-6 ol-sm-6">
    <input type="number" class="form-control fee_amount" placeholder="" name="fee_amount" id="fee_amount" min="1" value="<?php echo $doposit_details->fee_amount?>" reqired>
    </div>
    </div> 
     <input type="hidden" value="<?php if($doposit_details->status=='1'){echo $doposit_details->description;}else{ echo 'Entire reservation amount will be charged.';}?>" name="description" id="ddescription"/>
    </div>
  </div>
  <div class="well"> 
  <?php if($doposit_details->status=='1'){echo $doposit_details->description;}else{ echo 'Entire reservation amount will be charged.';}?>
  </div>
  
  
	   
      </div>
      
     	  
        <div class="modal-footer">
        <?php if($doposit_details->status==0){?>
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="policy_state">    
        	<a data-remotes="true" href="javascript:;" class="cls_passive" id="cls_passive" type="deposite" method="active" data-id="<?php echo $doposit_details->policy_id;?>">
            <input type="hidden" id="deposit_current_status" value="<?php echo $doposit_details->status?>" />
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
        <?php } else if($doposit_details->status==1) { ?>
      		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="policy_state">
            <a data-remotes="true" href="javascript:;" class="cls_passive" id="cls_passive" type="deposite" method="passive" data-id="<?php echo $doposit_details->policy_id;?>">
 			<input type="hidden" id="deposit_current_status" value="<?php echo $doposit_details->status?>" />
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
		<button type="submit" class="btn btn-primary">Save changes</button>
		<?php } ?>
   		</div>
        </form>
        
    </div>
  </div>
</div>

<!-- DEPOSIT MODAL END -->

<!-- CANCEl MODAL START -->

<div class="modal fade" id="cancel_policy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
	  
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancelation policy  </h4>
      </div>
     <?php
     if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
      $cancel_details = get_data(PCANCEL,array('user_id'=>user_id()))->row();	
      }
    else if(user_type()=='2'){
     $cancel_details = get_data(PCANCEL,array('user_id'=>owner_id()))->row();   
      }
      ?> 
     <form class="form-horizontal" method="post" action="<?php echo lang_url();?>channel/policies/cancel">
      <div class="modal-body">
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Days prior check-in  </label>
      <div class="col-sm-8">
<input type="number" class="form-control prior_checkin_days" placeholder="" value="<?php echo $cancel_details->prior_checkin_days;?>" name="prior_checkin_days" id="prior_checkin_days" min="1" required> 
    </div>
  </div>
  
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Time </label>
      <div class="col-sm-8">
      <div class="row">
      <div class="col-md-6 col-sm-6">
      <select class="form-control prior_checkin_time" name="prior_checkin_time" id="prior_checkin_time">
      <?php for($i=00;$i<=23;$i++)
	  {?>
      <option value="<?php if($i<'10') { echo '0'.$i;} else { echo $i; }?>" <?php if($cancel_details->prior_checkin_time==$i){?> selected <?php } ?> > <?php if($i<'10') { echo '0'.$i;} else {echo $i;}?></option>
      <?php } ?>
      </select>
      </div>
      <div class="col-md-6 col-sm-6">
      </div>
      </div>
    </div>
  </div>
  
       <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Fee </label>
      <div class="col-sm-8">
      <div class="row">
      <div class="col-md-6 col-sm-6">
      <select class="form-control cancel_fee" name="fee_type" id="cancel_fee_type">
   	  <?php
   		$fee_type_array = array('1'=>'Please select','flat'=>'Fixed fee','%'=>'Fixed percentage','night'=>'Fixed fee per night');
		foreach($fee_type_array as $type=>$key)
		{
	  ?>
   <option value="<?php echo $type;?>" <?php if($type==$cancel_details->fee_type) {?> selected="selected" <?php } ?>><?php echo $key;?></option>
  	 <?php 
		}
	 ?>
	
   </select>
      </div>
      <div class="col-md-6 col-sm-6">
      <input type="number" class="form-control cancel_fee_amount" placeholder="0" name="fee_amount" id="cfee_amount" value="<?php echo $cancel_details->fee_amount;?>" min="1" required>
      </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="description" id="cdescription" value="<?php if($cancel_details->status=='1'){echo $cancel_details->description;}else{ echo 'Free cancelation.';}?>" />
  
  <div class="well cwell"> 
<?php if($cancel_details->status=='1'){echo $cancel_details->description;}else{ echo 'Free cancelation.';}?> </div>

      </div>
      
      <div class="modal-footer">
      
       <?php if($cancel_details->status=='0'){?>
	    <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="policy_cancel_states">    
        	<a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="active" data-id="<?php echo $cancel_details->policy_id;?>">
            <input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
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
        <?php } else if($cancel_details->status=='1') { ?>
		 <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="policy_cancel_states">
            <a data-remotes="true" href="javascript:;" class="cls_cancel_passives" id="cls_cancel_passives" type="cancels" method="passive" data-id="<?php echo $cancel_details->policy_id;?>">
 			<input type="hidden" id="cancel_current_status" value="<?php echo $cancel_details->status?>" />
        <div class="onoffswitch-wrap ">
          <div class="onoffswitch">
            <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
            <label for="active-channel" class="onoffswitch-label"></label>
          </div>
          <label class="switch-label" for="active-channel">Active</label>
        </div>
		
		 <?php } ?>


</a>
      </div>
	    <?php } ?>
		
        <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <button type="submit" class="btn btn-primary">Save changes</button>
		<?php } ?>
      </div>
      
      </form>
    </div>
  </div>
</div>

<!-- CANCEL MODAL END -->

<!-- OTHER MODAL START -->

<div class="modal fade" id="other_polhhicy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Other policies  </h4>
      </div>
       <?php 
     if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
	   $other_details = get_data(POTHERS,array('user_id'=>user_id()))->row();
	   }
     else if(user_type()=='2' || admin_id()!='' && admin_type()=='1'){
      $other_details = get_data(POTHERS,array('user_id'=>owner_id()))->row();
     }
	   $check_in_time_mi = substr($other_details->check_in_time, strpos( $other_details->check_in_time,".") + 1); 
	   $lang = explode(".", $other_details->check_in_time);
	   $check_in_time_hr = $lang[0];
	   
	   $check_out_time_mi = substr($other_details->check_out_time, strpos( $other_details->check_out_time,".") + 1); 
	   $out = explode(".", $other_details->check_out_time);
	   $check_out_time_hr = $out[0];
 
	   ?> 
       <form class="form-horizontal" method="post" action="<?php echo lang_url();?>channel/policies/other">
       
       <div class="modal-body">
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Check-in time </label>
      <div class="col-sm-8">
     <div class="row">
      <div class="col-md-6 col-sm-6">
      <select class="form-control" name="check_in_time_hr" id="check_in_time_hr">
      <?php for($i=00;$i<=23;$i++)
	  {?>
      <option value="<?php if($i<'10') { echo '0'.$i;} else { echo $i; }?>" <?php if($check_in_time_hr==$i){?> selected <?php } ?> > <?php if($i<'10') { echo '0'.$i;} else {echo $i;}?></option>
      <?php } ?>
      </select>
      </div>
      <div class="col-md-6 col-sm-6">
      <select class="form-control" name="check_in_time_mi" id="check_in_time_mi">
      <?php for($i=00;$i<=59;$i++)
	  {?>
      <option value="<?php if($i<'10') { echo '0'.$i;} else { echo $i; }?>" <?php if($check_in_time_mi==$i){?> selected <?php } ?> > <?php if($i<'10') { echo '0'.$i;} else {echo $i;}?></option>
      <?php } ?>
      </select>
      </div>
      </div>
    </div>
  </div>
  
  
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Check-out time </label>
      <div class="col-sm-8">
     <div class="row">
      <div class="col-md-6 col-sm-6">
      <select class="form-control" name="check_out_time_hr" id="check_out_time_hr">
      <?php for($i=00;$i<=23;$i++)
	  {?>
      <option value="<?php if($i<'10') { echo '0'.$i;} else { echo $i; }?>" <?php if($check_out_time_hr==$i){?> selected <?php } ?> > <?php if($i<'10') { echo '0'.$i;} else {echo $i;}?></option>
      <?php } ?>
      </select>
      </div>
      <div class="col-md-6 col-sm-6">
      <select class="form-control" name="check_out_time_mi" id="check_out_time_mi">
      <?php for($i=00;$i<=59;$i++)
	  {?>
      <option value="<?php if($i<'10') { echo '0'.$i;} else { echo $i; }?>" <?php if($check_out_time_mi==$i){?> selected <?php } ?> > <?php if($i<'10') { echo '0'.$i;} else {echo $i;}?></option>
      <?php } ?>
      </select>
      </div>
      </div>
    </div>
  </div>
  
     <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Valet parking </label>
      <div class="col-sm-8">
     <input type="checkbox" name="valet_parking" id="valet_parking" value="1" <?php  if($other_details->valet_parking==1){?> checked="checked"<?php } ?>>
    </div>
  </div>
  
   <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Smoking </label>
      <div class="col-sm-8">
     <input type="checkbox" name="smoking" id="smoking" value="1" <?php  if($other_details->smoking==1){?> checked="checked"<?php } ?>>
    </div>
  </div>
  
   <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label">Pets </label>
      <div class="col-sm-8">
     <input type="checkbox" name="pets" id="pets" value="1" <?php  if($other_details->pets==1){?> checked="checked"<?php } ?>>
    </div>
  </div>
      
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label">Child pricing </label>
      <div class="col-sm-8">
     <input type="checkbox" name="child_pricing" id="child_pricing" value="1" <?php  if($other_details->child_pricing==1){?> checked="checked"<?php } ?>/>
    </div>
  </div>
  </div>
      
       <div class="modal-footer">
	   <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
       <button type="submit" class="btn btn-primary">Save changes</button>
	   <?php } ?>
       </div>
       </form>
    </div>
  </div>
</div>

<!-- OTHER MODAL START -->




</body>

</html>


