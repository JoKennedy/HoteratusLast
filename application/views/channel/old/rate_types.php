
      <input type="hidden" name="property_id" id="property_id" value="<?php echo $room_id;?>" />
      
      <input type="hidden" name="uniq_id" value="<?php echo $uniq_id;?>" id="uniq_id"/>
      
      <input type="hidden" name="member_count" id="member_count" value="<?php $member_count =  get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->member_count; echo $member_count; ?>" />
      
      <div class="box-dialog row-pad-top-20">
 
  	  
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Name <span class="errors"> </span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="text" placeholder="Rate Type Name" id="property_name" name="property_name" class="form-control" value="<?php echo $rate_name;?>">
 </div>
 </div>
 
 	  <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Pricing Type <span class="errors"> </span></span></div>
	  <div class="col-md-8 col-sm-8">
      <?php if($pricing_type==1) { echo 'Room based pricing';}else if($pricing_type==2) { echo 'Guest based pricing';}?>
      <input type="hidden" value="<?php echo $pricing_type?>" name="pricing_type"/>
 	  <!--<select name="pricing_type" id="pricing_typess" class="form-control" >
      <option value="1" <?php //if($pricing_type==1) { ?> selected <?php //} ?>>Room based pricing</option>
      <option value="2" <?php //if($pricing_type==2) { ?> selected <?php //} ?>>Guest based pricing</option>
      </select>-->
	  </div>
	  </div>
 	  
	  <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Display room on calendar <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7">
      <input type="radio" value="1" <?php if($droc==1) { ?>checked="checked" <?php } ?> name="droc" id="droc"/> Yes 
      <input type="radio" value="2" <?php if($droc==2) { ?>checked="checked" <?php } ?> name="droc" id="droc1"/> No</div>
	  </div>
      
      <div class="row">
 
 	  <div class="col-md-3 col-sm-3"><span class="ne-k">Meal plan</span></div>
      
      <div class="col-md-8 col-sm-8">
        <select class="form-control" name="meal_plan" id="meal_plan">
        <?php 
            $meal_plans = get_data(MEAL,array('meal_status'=>1))->result_array();
            foreach($meal_plans as $meal)
            { 
                extract($meal);
        ?>
          <option value="<?php echo $meal_id;?>" <?php if($meal_plan==$meal_id) { ?> selected <?php } ?>><?php echo $meal_name;?></option>
          <?php } ?>
        </select>
        </div>
        
	  </div>
 
	  <!--<div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Non Refundable <span class="errors"> </span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="checkbox" value="1" name="non_r" id="add_non_rsss" class="non_r" <?php //if($non_refund==1) { ?> checked <?php //} ?> /></div>
	  </div>-->
      
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Base price / Per night<span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon">
	  <?php 
	  $currency = get_data(TBL_USERS,array('user_id'=>user_id()))->row()->currency;
	  echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" class="form-control" placeholder="Price" id="eadd_price1" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="<?php echo $price;//get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->price;?>">
    </div></div>
	  </div>
      
      <input type="hidden" name="base_price" id="base_price" value="<?php echo $price; ?>"/>
      
  	  </div>
      
  	  <div class="col-md-12 guest_baseds <?php if($pricing_type==1) { ?> display_none <?php } ?> ">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th>Number of guests</th>
        <th>Refundable</th>
        <th class="non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">Non refundable</th>
        </tr>
        </thead>
        <tbody class="data">
        <?php 
		$rate_refund = get_data(RATE_TYPES_REFUN,array('uniq_id'=>$uniq_id))->result_array();
		$i=0;
		foreach($rate_refund as $refund)
		{
			extract($refund);
			$i++;
		?>
        <input type="hidden" id="types_id" value="<?php echo $rate_type_id?>" name="rate_type_id_<?php echo $i; ?>">
	    <tr id="item<?php echo $i;?>">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="method_<?php echo $i;?>" id="method_1_<?php echo $i;?>">
            <option value="+" <?php if($method=='+') { ?> selected="selected" <?php } ?>> + </option>
            <option value="-" <?php if($method=='-') { ?> selected="selected" <?php } ?>> - </option>
            </select>
            </div>

       <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="type_<?php echo $i;?>" id="type_1_<?php echo $i;?>">
            <option value="Rs" <?php if($type=="Rs") { ?> selected="selected"<?php } ?>> Rs </option>
            <option value="%" <?php if($type=="%") { ?> selected="selected"<?php } ?>> %</option>
            </select>
            </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="<?php echo $dis_amount;?>" class="form-control widh cal_amt1" name="d_amt_<?php echo $i;?>" id="amt_1_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
		</div>
        </div>
	    <input type="hidden" value="<?php echo $total_amount;?>" id="h_total_1_<?php echo $i;?>" name="h_total_<?php echo $i; ?>" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_1_<?php echo $i;?>"><?php echo $total_amount;?></p></div>
        </td>
        <td class="pa-t-pa-b non_refunds  <?php if($non_refund=='0'){?> display_none <?php } ?> ">
             
        <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="n_method_<?php echo $i;?>" id="n_method_1_<?php echo $i;?>">
            <option value="+" <?php if($n_method=='+') { ?> selected="selected" <?php } ?>>+</option>
            <option value="-" <?php if($n_method=='-') { ?> selected="selected" <?php } ?>>-</option>
            </select>
            </div>
		        
        <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="n_type_<?php echo $i;?>" id="n_type_1_<?php echo $i;?>">
              <option value="Rs" <?php if($n_type=="Rs") { ?> selected="selected"<?php } ?>>Rs</option>
              <option value="%" <?php if($n_type=="%") { ?> selected="selected"<?php } ?>>%</option>
            </select>
            </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="<?php if($n_dis_amount!='') { echo $n_dis_amount; } else { echo '0.00';}?>" class="form-control widh cal_amt1" name="n_d_amt_<?php echo $i;?>" id="n_amt_1_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
   		</div>
        </div>
  		<input type="hidden" value="<?php if($d_total_amount!='') { echo $d_total_amount; } else { echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->price,2, '.', '');}?>" id="n_h_total_1_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_1_<?php echo $i;?>"><?php  if($d_total_amount!='') { echo $d_total_amount; } else { echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->price,2, '.', '');}?></p></div>
        </td>
        </tr>
        
        </tr>
        <?php 
		} 
		?>
        </tbody>
        </table>
        </div>
  </div>
  
	  <div class="col-md-12 room_baseds <?php if($pricing_type==2) { ?> display_none <?php } ?>">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th class="text-center" colspan="">Refundable</th>
        <th class="non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?> text-center" colspan="">Non refundable</th>
        </tr>
        </thead>
        <tbody class="datas">
        <tr id="item0">
        <?php 
		$rate_refund = get_data(RATE_TYPES_REFUN,array('uniq_id'=>$uniq_id))->result_array();
		$i=0;
		foreach($rate_refund as $refund)
		{
			extract($refund);
			$i++;
		?>
        <input type="hidden" id="types_id" value="<?php echo $rate_type_id?>" name="rate_type_id">
        <tr>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_e_method_1" id="r_e_method_1_<?php echo $i; ?>">
		<option value="+" <?php if($method=='+') { ?> selected="selected" <?php } ?>>+</option>
		<option value="-" <?php if($method=='-') { ?> selected="selected" <?php } ?>>-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="r_e_type_1" id="r_e_type_1_<?php echo $i; ?>">
        <option value="Rs" <?php if($type=='Rs') { ?> selected="selected" <?php } ?>>Rs</option>
        <option value="%" <?php if($type=='%') { ?> selected="selected" <?php } ?>> %</option>
        </select>
        </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="<?php echo $dis_amount;?>" class="form-control widh rcal_amt1" name="r_e_d_amt_1" id="r_e_amt_1_<?php echo $i;?>" custom="1" method="refun">
		</div>
        </div>
	    <input type="hidden" value="<?php echo $total_amount;?>" id="r_e_h_total_1_<?php echo $i;?>" name="r_e_h_total_1" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="r_e_total_1_<?php echo $i; ?>"><?php echo $total_amount;?></p></div>
        </td>
        <td class="pa-t-pa-b non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1" id="r_e_n_method_1_1">
  		<option value="+" <?php if($n_method=='+') { ?> selected="selected" <?php } ?>>+</option>
		<option value="-" <?php if($n_method=='-') { ?> selected="selected" <?php } ?>>-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="r_n_type_1" id="r_e_n_type_1_1">
  		<option value="Rs" <?php if($n_type=='Rs') { ?> selected="selected" <?php } ?>>Rs</option>
 		<option value="%" <?php if($n_type=='%') { ?> selected="selected" <?php } ?>>%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="<?php if($n_dis_amount!='') { echo $n_dis_amount; } else { echo '0.00';}?>" class="form-control widh rcal_amt1" name="r_n_d_amt_1" id="r_e_n_amt_1_1" custom="1" method="n_refun">
	    </div>
        </div>
  		<input type="hidden" value="<?php if($d_total_amount!='') { echo $d_total_amount; } else { echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->price,2, '.', '');}?>" id="r_e_n_h_total_1" name="r_n_h_total_1" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="r_e_n_total_1"><?php if($d_total_amount!='') { echo $d_total_amount; } else { echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row()->price,2, '.', '');}?></p></div>
        </td>
        </tr>
        <?php } ?>
        </tr>
        </tbody>
        </table>
        </div>
  </div>
     
    <div class="form-group">
		<div class="col-sm-offset-2 col-sm-8">
			<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
				<button class="btn btn-success hover-shadow pull-right" type="submit">Update</button> 
			<?php } ?>
		</div>
    </div>
  
    
     
	 <?php 
	 if($file_name!='')
	 {
	 ?>
 <div style="color:#659BE0"><?php echo base_url()."uploads/ICAL/roomtype/".$file_name;?></div>
 <?php } ?>
   
  
  
   
   
   
   
      