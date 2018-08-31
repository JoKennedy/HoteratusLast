    <?php 
		if($result){
			foreach($result as $adjust){
	?>
	<div class="modal-body" id="selectadjust">
       
          <div class="form-group">
          
            <label for="inputEmail3" class="col-sm-3 control-label">Amount</label>
                <div class="col-sm-2">
                  <select class="form-control" name="amount_type" value="<?php echo $adjust->amount_type; ?>" required> <option> - </option>
                   <option> + </option> 
                  </select>
                </div>
        <div class="col-md-4 col-sm-4"> 
            <div class="input-group">
            <?php 
			$user = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
			$currency = $this->reservation_model->get_curreny_name($user->currency);
                  $currency_code = $currency->currency_code; 
                    ?>
            <span class="input-group-addon" id="basic-addon1" name="amount_code"><?php echo $currency_code; ?></span>
            <input type="number" class="form-control" name="inr_amount" id="inr_amount"  value="<?php echo $adjust->inr_code; ?>" required>
            </div> 
        </div>
        
        </div>
        <div class="clearfix"></div>
        
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Description <span class="errors">*</span></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="inputPassword3" name="description" value="<?php echo $adjust->description; ?>" required>
                    </div>
            </div>
            <div class="clearfix"></div>		
       </div>
   
		<?php } } ?>