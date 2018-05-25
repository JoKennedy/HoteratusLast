<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">
</div>
<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
            <ul id="menu-content" class="menu-content out">

                <li <?php if(uri(3)=='manage_property' || uri(3)=='property_info' || uri(3)=='payment_list') {?>class="active" <?php } ?>>
                  <a href="<?php echo lang_url();?>channel/property_info">
                  <i class="fa fa-h-square fa-lg"></i> Property Info
                  </a>
                </li>
                  
                <li>
                  <a href="<?php echo lang_url();?>channel/manage_rooms">
                  <i class="fa fa-bed fa-lg"></i> Manage Rooms
                  </a>
                </li>
        
                <li <?php if(uri(3)=='manage_subusers'){?> class="active" <?php } ?>>
                  <a href="<?php echo lang_url(); ?>channel/manage_subusers">
                  <i class="fa fa-users fa-lg"></i> Manage Users
                  </a>
                </li>

                <li <?php if(uri(3)=='edit_paypal'){?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>reservation/payment_list"><i class="fa fa-money fa-lg"></i> Payment methods </a></li>

                <li><a href="<?php echo lang_url(); ?>reservation/payment_policy"> <i class="fa fa-star fa-lg"></i> Policies   </a>  </li>

                <li><a href=""><i class="fa fa fa-object-ungroup fa-lg"></i> Tax categories </a> </li>

                 <li <?php if(uri(3)=='billing_info'){ ?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>channel/billing_info"><i class="fa fa-file-text"></i> Billing Details </a> </li>
              <!--  <li><a href=""><i class="fa fa-user fa-lg"></i> Users </a></li>
                <li><a href=""><i class="fa fa-users fa-lg"></i> My group </a></li>-->
</ul>
</div></div></div></div>            
</div>
</div>
</div>
</div>

<div class="col-md-10 col-sm-10" style="padding-right:0;">

<!-- edit paypal details -->
<div class="bg-neww">

<div class="tab-content">

<div class="tab-pane active" id="tab_default_1">

<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>reservation/payment_list">Payment methods</a>   <i class="fa fa-angle-right"></i>  Edit Paypal Details</h4> </div>

<div class="box-m">

<div class="row">

<div class="col-md-12">
<?php 
 if(($page_view)=='edit'){
 ?>
<form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/edit_paypal" method="post" id="edit_paypaldet">
 <?php //echo $pay->paypal_id;die; ?>
 <input type="hidden" name="paypal_id" value="<?php echo $pay->paypal_id; ?>">

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label"> Provider </label>
    <div class="col-sm-8" style="margin-top:7px">
	<?php $trans_type = $this->reservation_model->get_payment_namebyid($pay->paymentid);
	    echo $trans_type->payment_type; ?>
	
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Name <span class="error"> * </span></label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" name="pay_method_name" value="<?php if(isset($pay->pay_method_name)){echo $pay->pay_method_name; } ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Client Id <span class="error"> * </span> </label>
    <div class="col-sm-8">
	   <input type="text" class="form-control" id="inputPassword3" name="client_id" value="<?php if(isset($pay->client_id)){ echo $pay->client_id; } ?>" required>
      </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Client Secret <span class="error"> * </span></label>
    <div class="col-sm-8">
	   <input type="text" class="form-control" id="inputPassword3" name="client_secret" value="<?php if(isset($pay->client_secret)){ echo $pay->client_secret; } ?>" required>
      </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Paypal Accepted currencies <span class="error"> * </span></label>
		<div class="col-sm-8">
		   <input type="text" class="form-control" id="inputPassword3" name="paypal_currency"  value="<?php if(isset($pay->client_secret)){ echo $pay->paypal_currency; }?>" required>
		 </div>
  </div>
  
    <div class="form-group">
		 <label for="inputPassword3" class="col-sm-3 control-label"> Server <span class="error"> * </span></label>
		<div class="col-sm-8">
		  <select class="form-control" name="server" required> 
		  <option value="Live" <?php if(isset($pay->server)){ ?> selected="selected" <?php } ?>>Live</option>
		  <option value="Sandbox" <?php if(isset($pay->server)){ ?> selected="selected" <?php } ?>>Sandbox</option>
		  </select>
		</div>
	  </div>

	<div>
	
	<?php if($pay->status==0){?>
	<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
        <div class="pull-left policy-state-action" id="paypal_state">    
        	<a data-remotes="true" href="javascript:;" class="paypal_active" id="paypal_active" type="paypal" method="active" data-id="<?php echo $pay->paypal_id;?>">
            <input type="hidden" id="paypal_current_status" value="<?php echo $pay->status?>" />
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
        <?php } else if($pay->status==1) { ?>
      	<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>	
        <div class="pull-left policy-state-action" id="paypal_state">
            <a data-remotes="true" href="javascript:;" class="paypal_active" id="paypal_active" type="paypal" method="passive" data-id="<?php echo $pay->paypal_id;?>">
 			<input type="hidden" id="paypal_current_status" value="<?php echo $pay->status; ?>" />
        <div class="onoffswitch-wrap ">
          <div class="onoffswitch">
            <input type="checkbox" id="active-channel" class="onoffswitch-checkbox" name="active-channel" checked="">
            <label for="active-channel" class="onoffswitch-label"></label>
          </div>
          <label class="switch-label" for="active-channel">Active</label>
        </div>
			</a>
      </div>
	    <?php } } ?>
	    <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
		<button type="submit" name="save" value="save" class="btn btn-info pull-right"> Save </button>
		<?php } ?>
	</div>
 
</form>
<?php } else if(($page_view)=='add'){ ?>
 <form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/add_paypal" method="post">

<input type="hidden" value="<?php echo insep_encode($pay_id);?>" name="pay_method" />
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label"> Provider </label>
    <div class="col-sm-8">
			<?php $trans_type = $this->reservation_model->get_payment_namebyid($pay_id);
	  echo $trans_type->payment_type; ?>
	
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Name (en-US) </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" name="holder_name" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Client Id </label>
    <div class="col-sm-8">
	   <input type="text" class="form-control" id="inputPassword3" name="client_id" required>
      </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Client Secret </label>
    <div class="col-sm-8">
	   <input type="text" class="form-control" id="inputPassword3" name="client_secret" required>
      </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label"> Paypal Accepted currencies </label>
		<div class="col-sm-8">
		   <input type="text" class="form-control" id="inputPassword3" name="paypal_currency" required>
		 </div>
  </div>
  
    <div class="form-group">
		 <label for="inputPassword3" class="col-sm-3 control-label"> server </label>
		<div class="col-sm-8">
		  <select class="form-control" name="server" required> 
		  <option>Live</option>
		  <option>sandbox</option>
		  </select>
		</div>
	  </div>

	<div>
		<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
		<button type="submit" name="save" value="save" class="btn btn-success pull-right"> Save </button>
		<?php } ?>
	</div>
	
</form>


<?php } ?>
</div>

</div>

</div>  
             
</div><!-- end tab2 -->

</div>

</div>

</div>

<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Add payment method </h4>
      </div>
      <div class="modal-body">
	  
       <form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label"> Provider </label>
    <div class="col-sm-8">
      <select class="form-control"> <option>  Akbank </option></select>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Name (en-US)</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" placeholder="">
    </div>
  </div>
  
  
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</body>



</html>


