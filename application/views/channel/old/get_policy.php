<div class="modal-body">
      <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Policy Name </label>
      <div class="col-sm-8">
        <input type="text" class="form-control policy_name" placeholder="" value="<?php echo $get_policy->policy_name;?>" name="policy_name" id="prior_checkin_days" min="1" required> 
    </div>
  </div>

  <input type="hidden" name="cp_id" value="<?php echo $get_policy->cp_id; ?>">

  <input type="hidden" name="policy_id" value="<?php echo $get_policy->policy_id; ?>">

   <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Policy Description </label>
      <div class="col-sm-8">
       <textarea class="form-control" name="policy_des"><?php echo $get_policy->description; ?></textarea>
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
   <option value="<?php echo $type;?>" <?php if($type==$get_policy->fee_type) {?> selected="selected" <?php } ?>><?php echo $key;?></option>
     <?php 
    }
   ?>
  
   </select>
      </div>
      <div class="col-md-6 col-sm-6">
      <input type="number" class="form-control cancel_fee_amount" placeholder="0" name="fee_amount" id="cfee_amount" value="<?php echo $get_policy->fee_amount;?>" min="1" required>
      </div>
      </div>
    </div>
  </div>
 
  
 <!--  <div class="well cwell"> 
<?php if($get_policy->status=='1')
{
  echo $get_policy->description;}else{ echo 'Free cancelation.';}?> </div> -->

      </div>
      
      <div class="modal-footer">
      
       <?php if($get_policy->status=='0'){?>
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="new_cancel_states">    
          <a data-remotes="true" href="javascript:;" class="new_cancel_passives" id="new_cancel_passives" type="new_poly" method="active" data-id="<?php echo $get_policy->policy_id;?>">
            <input type="hidden" id="new_current_status" value="<?php echo $get_policy->status?>" />
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
        <?php } else if($get_policy->status=='1') { ?>
     <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="pull-left policy-state-action" id="new_cancel_states">
            <a data-remotes="true" href="javascript:;" class="new_cancel_passives" id="new_cancel_passives" type="new_poly" method="passive" data-id="<?php echo $get_policy->policy_id;?>">
      <input type="hidden" id="new_current_status" value="<?php echo $get_policy->status?>" />
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