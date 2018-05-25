  
<div class="content">
<!-- <div class="row">

<div class="col-md-9 col-sm-7 col-xs-12 cls_cmpilrigh"> -->

<div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>
    My Account
    </h4>
  </div>
  
  <hr>

  <?php 
if($this->session->flashdata('profile')!='')
{
  
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times;</span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
  
  
<form class="form-horizontal" id="register_form2" method="post" action="<?php echo lang_url();?>channel/my_account">


    <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Your Name *</label>
    <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname?>">
    </div>
    </div>
   
      <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="form_type_list">
      <label class="">Family Name *</label>
      <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>">
      </div>
      </div>
  </div>    
    
    
    <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Town *</label>
<input type="text" class="form-control" id="town" name="town" value="<?php echo $town?>">
    
    </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Address</label>
    <input type="text" class="form-control" id="address" name="address" value="<?php echo $address;?>">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">ZIP Code *</label>
    <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $zip_code;?>">
    </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone *</label>
    <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $mobile;?>">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  
  <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
  <label class="">Email*</label>
  <input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $email_address;?>">
  </div>
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
  <label class="">Currency*</label>
  <div class="">
  <?php $currencys = get_data(TBL_CUR)->result_array();?>
  <select name="currency" class="form-control">
  <?php foreach($currencys as $cur) { 
  extract($cur);?>
  <option value="<?php echo $currency_id; ?>" <?php if($currency_id==$currency) { ?> selected="selected" <?php } ?>> <?php echo $currency_name;?> </option>
  <?php } ?>
  </select>
  </div>
  </div>
  </div>

  </div>  
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Country :</label>
    <div class="">
      <select name="country" class="form-control">
      <?php $countrys = get_data('country')->result_array();
      foreach($countrys as $value) { 
      extract($value);?>
      <option value="<?php echo $id;?>" <?php if($id==$country) { ?> selected="selected" <?php } ?>><?php echo $country_name;?></option>
      <?php } ?>
      </select>
    </div>
    </div>
    </div>
    </div>  
    
    
    
    
  <div class="form-group form_list_top">    
    <input class="btn btn-primary" type="submit" value="Submit">
  </div>
    
    </form>
</div>
  

   <?php $this->load->view('channel/dash_sidebar'); ?>