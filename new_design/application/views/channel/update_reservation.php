	
	<form class="form-horizontal" method="POST" onsubmit="return edit_reservation_room();" id="edit_room">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Edit guest details </h4>
      </div>
	<div class="modal-body">      
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label">First name</label>
			<div class="col-sm-8">
			<input type="text" class="form-control" id="inputEmail3" name="guest_name" value="<?php echo $room->guest_name; ?>" required>
			</div>
		</div>
  
	  <div class="form-group">
			<label for="inputPassword3" class="col-sm-4 control-label">Last name</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="inputPassword3" name="last_name" value="<?php echo $room->last_name; ?>" required>
			</div>
	  </div>
  
	  <div class="form-group">
			<label for="inputPassword3" class="col-sm-4 control-label">E-mail</label>
			<div class="col-sm-8">
			  <input type="email" class="form-control"  name="email" id="inputPassword3" value="<?php echo $room->email; ?>" required>
			</div>
	  </div>
  
	  <div class="form-group">
			<label for="inputPassword3" class="col-sm-4 control-label">Phone</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="inputPassword3" name="mobile" value="<?php echo $room->mobile; ?>" required>
			</div>
	  </div>
  
	  <div class="form-group">
			<label for="inputPassword3" class="col-sm-4 control-label"> Country </label>
				<div class="col-sm-8">
					  <select class="form-control" name="country" required>
						  <option value=""> select </option>
						  <?php $country = $this->reservation_model->get_country_name();
									foreach($country as $country_name) {
									$country_id=$country_name->id;
									$country_name=$country_name->country_name;
									?>
									<option value="<?php echo $country_id; ?>" <?php if($room->country == $country_id){echo "selected=selected";} ?>><?php echo $country_name; ?></option>
									<?php } ?>
					  </select>
				</div>
	  </div>
  
  <div class="form-group">
		<label for="inputPassword3" class="col-sm-4 control-label" name="province"> Province </label>
			<div class="col-sm-8">
				  <select class="form-control" name="province" required>
					  <option value="">select</option>
					  <?php $province_name=$this->reservation_model->get_country_name();
								foreach($province_name as $province) {
								$province_id=$province->id;
								$province_con=$province->country_name;
								?>
								<option value="<?php echo $province_id; ?>" <?php if($room->province == $province_id){echo "selected=selected";} ?>><?php echo $province_con; ?></option>
								<?php } ?>
				  </select>
			</div>
  </div>
	  <div class="form-group">
		  <label for="inputEmail3" class="col-sm-4 control-label">
		  Street address </label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" name="street_name" id="inputEmail3" value="<?php echo $room->street_name; ?>" required>
			</div>
	  </div>
  
	 <div class="form-group">
		  <label for="inputEmail3" class="col-sm-4 control-label">
		  city </label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="inputEmail3" name="city_name" value="<?php echo $room->city_name; ?>" required>
			</div>
		  </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" class="close" data-dismiss="modal" aria-label="Close">cancel</button>
        <button type="submit" class="btn btn-default"  name="save" value="save" id="edit_reserve">Save</button>
      </div>
</form>