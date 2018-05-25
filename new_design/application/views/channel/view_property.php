
    
<input type="hidden" name="edit_hotel_id" value="<?php echo insep_encode($hotel_id)?>" id="edit_hotel_id"> 
  <div class="col-md-12">
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Property Name <span class="errors"> </span></p>
    <div class="col-sm-7">
    
    <p class="mar-top7"> : <?php echo $property_name;?></p>
<!-- <input type="text" placeholder="Property Name" id="property_name" name="property_name" class="form-control" value="">-->
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Town <span class="errors"></span></p>
    <div class="col-sm-7">
<p class="mar-top7">: <?php echo $town;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Address <span class="errors"></span></p>
    <div class="col-sm-7">
<p class="mar-top7">: <?php echo $address;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
ZIP Code <span class="errors"></span></p>
    <div class="col-sm-7">
<p class="mar-top7">: <?php echo $zip_code;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Mobile <span class="errors"></span></p>
    <div class="col-sm-7">
 <p class="mar-top7">: <?php echo $mobile;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Email Id <span class="errors"></span></p>
    <div class="col-sm-7">
<p class="mar-top7">: <?php echo $email_address;?></p>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Url<span class="errors"></span></p>
    <div class="col-sm-7">
 <p class="mar-top7">: <a href="<?php echo $web_site;?>" target="_blank"> <?php echo $web_site;?> </a></p>
    </div>
  </div>
  
  
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Country <span class="errors"> </span></p>
    <div class="col-sm-7">
     <p class="mar-top7">: <?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?></p>
    <?php $all_country = get_data(TBL_COUNTRY)->result(); ?>
    
    </div>
  </div>
  
  
  <div class="form-group">
    <div class="col-sm-12">
      <!--<button type="submit" name="edit_property" value="edit_property" id="edit_property" class="btn btn-success hover-shadow pull-right">Update</button>--> 
    <button data-dismiss="modal" class="btn btn-success pull-right" type="button">Close</button>
    </div>
    
  </div>
  
  
  
  
  
  </div>
  
  

  