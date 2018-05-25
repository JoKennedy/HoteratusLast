<?php 
if($result){
	foreach($result as $categories){
 ?> 		
        <div class="clearfix"></div>
        <input type="hidden" name="tax_id" value="<?php echo $categories->tax_id; ?>">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-4 control-label">Name <span class="error"> *</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="inputEmail3" value="<?php echo $categories->user_name; ?>" name="user_name" required>
					</div>
			</div>
        
        	<div class="clearfix"></div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-4 control-label"> Included in price </label>
					<div class="col-sm-1" align="left">
					     
						<input type="checkbox" class="form-control" name="included_price" value="yes" <?php  if($categories->included_price=='yes'){echo 'checked';} ?>>
					</div>
			</div>
            <div class="clearfix"></div>
        
			<div class="form-group">
			<label for="inputPassword3" class="col-sm-4 control-label"> Tax Rate <span class="error"> *</span></label>
					<div class="col-sm-8">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">%</span>
							<input type="text" class="form-control" value="<?php echo $categories->tax_rate; ?>" name="tax_rate" required >
						</div>
					</div>
		</div>
        	<div class="clearfix"></div>
			
			 <div class="modal-footer">
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
				<button type="button" class="btn btn-danger pull-left" id="deltax_<?php echo $categories->tax_id; ?>" onclick="return taxcategories('<?php echo $categories->tax_id; ?>');" value="<?php echo $categories->tax_id; ?>"><span id="delete_tax">Delete</span></button>
		<?php } ?>
		 
        <button type="button" class="btn btn-default login_close" onclick="Custombox.close();" data-dismiss="modal">Close</button>
       
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
        <button type="submit" class="btn btn-primary" name="saveas" value="edit">Save changes</button>
		
		<?php } ?>
		
      </div>
        
     
<?php } } ?>
     