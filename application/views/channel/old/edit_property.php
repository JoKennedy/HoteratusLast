
    
<input type="hidden" name="edit_hotel_id" value="<?php echo insep_encode($hotel_id)?>" id="edit_hotel_id"> 
<input type="hidden" name="redirect_url" value="manage_property" id="redirect_url"> 
  <div class="col-md-12">
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Property Name <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Property Name" id="property_name" name="property_name" class="form-control" value="<?php echo $property_name;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Town <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Town" id="town" name="town" class="form-control" value="<?php echo $town;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Address <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Address" id="address" name="address" class="form-control" value="<?php echo $address;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
ZIP Code <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="ZIP Code" id="zip_code" name="zip_code" class="form-control" value="<?php echo $zip_code;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Mobile <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Mobile" id="mobile" name="mobile" class="form-control" value="<?php echo $mobile;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Email Id <span class="errors">*</span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Email Id" id="email_address" name="email_address" class="form-control" value="<?php echo $email_address;?>">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Url<span class="errors"></span></p>
    <div class="col-sm-7">
 <input type="text" placeholder="Web Site" id="web_site" name="web_site" class="form-control" value="<?php echo $web_site;?>">
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Country <span class="errors">*</span></p>
    <div class="col-sm-7">
    <?php 
		$all_country = get_data(TBL_COUNTRY)->result();
	?>
    <select class="form-control" name="country">
    <?php foreach($all_country as $val){?>
    <option value="<?php echo $val->id?>" <?php if($val->id==$country) { ?> selected <?php } ?>> <?php echo $val->country_name;?></option>
    <?php } ?>
    </select>
    </div>
  </div>
  
  
  <div class="form-group">
    <div class="col-sm-12">
      <button type="submit" name="edit_property" value="edit_property" id="edit_property" class="btn btn-success hover-shadow pull-right">Update</button> 
    <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
    </div>
    
  </div>
  
  
  
  
  
  </div>
  
  

  