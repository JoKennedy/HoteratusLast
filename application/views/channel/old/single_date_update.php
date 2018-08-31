<div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Set update on calender</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="rate_type" id="rate_type" method="post" action="<?php echo lang_url();?>inventory/manage_rate_types/update" enctype="multipart/form-data">
      <input type="hidden" name="property_id" id="property_id" value="<?php echo $room_id;?>" />
      
      <input type="hidden" name="uniq_id" value="<?php echo $separate_date;?>" id="uniq_id"/>
      
      <div class="box-dialog row-pad-top-20">

      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Price <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon">
	  <?php 
	  $currency = get_data(TBL_USERS,array('user_id'=>user_id()))->row()->currency;
	  echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->symbol; ?></div>
      <input type="text" class="form-control" placeholder="Price" id="set_price1" name="price" value="<?php echo $price;?>">
    </div></div>
	  </div>
      
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Availability <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon"><i class="fa fa-bed"></i></div>
      <input type="text" class="form-control" placeholder="Availability" id="availability" name="availability" value="<?php echo $availability;?>">
    </div></div>
	  </div>
      
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Minimum Stay <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon"><i class="fa fa-moon-o"></i></div>
      <input type="text" class="form-control" placeholder="Minimum Stay" id="minimum_stay" name="minimum_stay" value="<?php echo $minimum_stay;?>">
    </div></div>
	  </div>
      
  	  </div>
  	  
      <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Set on Calendar </button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
     </form>
     </div>
   
  
  
   
   
   
   
      </div>
      
    </div>