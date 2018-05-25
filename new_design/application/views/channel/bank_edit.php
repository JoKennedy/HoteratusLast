<?php
	if($result){
	foreach($result as $bank){
 ?>  
	  
	  <div class="form-group">
		<label for="account_owner" class="col-sm-4 control-label">Account owner <span class="errors">*</span></label>
		   <div class="col-sm-8">
		   <input type="text" class="form-control" id="account_owner" name="account_owner" value="<?php echo $bank->account_owner; ?>" required>
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
			<option value="<?php echo $currency_id; ?>" <?php if($bank->currency == $currency_id){echo "selected=selected";} ?>> <?php echo $currency_name; ?> </option>
			<?php } ?>
			</select>
		   </div>
       </div>
	   
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label">Bank name <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="inputEmail3" name="bank_name" value="<?php echo $bank->bank_name; ?>" required>
		   </div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label">Branch name <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="inputEmail3" name="branch_name" value="<?php echo $bank->branch_name; ?>" required>
		   </div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> Branch code <span class="errors">*</span></label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="inputEmail3" name="branch_code" value="<?php echo $bank->branch_code; ?>" required>
			</div>
       </div>
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> Swift code <span class="errors">*</span></label>
		   <div class="col-sm-8">
			<input type="text" class="form-control" id="inputEmail3" name="swift_code" value="<?php echo $bank->swift_code; ?>" required>
		   </div>
       </div>
	   
       
       <div class="form-group">
		<label for="inputEmail3" class="col-sm-4 control-label"> IBAN <span class="errors">*</span></label>
		   <div class="col-sm-8">
				<input type="text" class="form-control" id="inputEmail3" name="iban" value="<?php echo $bank->iban; ?>" required>
		   </div>
       </div>
	   
       
        <div class="form-group">
		   <label for="inputEmail3" class="col-sm-4 control-label"> Account Number <span class="errors">*</span></label>
			   <div class="col-sm-8">
					<input type="text" class="form-control" id="inputEmail3" name="account_number" value="<?php echo $bank->account_number;?>" required>
			   </div>
       </div>
	   
	   <div class="modal-footer">
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
		<button class="btn btn-danger pull-left" type="button" id="del_<?php echo $bank->bank_id; ?>" onclick="return delete_bankdetails('<?php echo $bank->bank_id;?>');" value="<?php echo $bank->bank_id; ?>"><span id="del_bankdetails">Delete</span></button>
		<?php } ?>
		
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
        <button type="submit" name="save" value="save" class="btn btn-primary">Save changes</button>
		<?php } ?>
		
		
		
      </div>
      
	 
	<?php } } ?>