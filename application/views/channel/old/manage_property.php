<style>
.gllpMap  { width: 960px; height: 220px;  } .mar-top7{ margin-top:7px;} 
</style>

 <div class="contents">
    <div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">

  <?php if($action=='billing_info'){ ?>   

  <?php 
if($this->session->flashdata('profile')!='')
{
  
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>   
    
<form class="form-horizontal" id="add_bill" name="add_bill" method="post" action="<?php echo lang_url();?>channel/add_bill">

<input type="hidden" name="bill_id" value="<?php if(isset($bill->bill_id)){echo $bill->bill_id; }?>">

    <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Company name *</label>
    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php if(isset($bill->company_name)){ echo $bill->company_name; } ?>">
    </div>
    </div>
   
      <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="form_type_list">
      <label class="">Town *</label>
      <input type="text" class="form-control" id="town" name="town" value="<?php if(isset($bill->town)){echo $bill->town;} ?>">
      </div>
      </div>
  </div>    
    
    
    <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Billing Address *</label>
    <input type="text" class="form-control" value="<?php if(isset($bill->address)){echo $bill->address; } ?>" id="address" name="address">
    </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">ZIP Code</label>
    <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php if(isset($bill->zip_code)){ echo $bill->zip_code; } ?>">
    </div>
    </div>
  </div>
  
  <div class="form-group form_list_top">

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone *</label>
    <input type="text" class="form-control" id="mobile" name="mobile" value="<?php if(isset($bill->mobile)){ echo $bill->mobile; }?>">
    </div>
    </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">VAT *</label>
    <input type="text" class="form-control" id="vat" name="vat" value="<?php if(isset($bill->vat)){echo $bill->vat; }?>">
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  
  <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
  <label class="">Registration Number*</label>
  <input type="text" class="form-control" id="reg_num" name="reg_num" value="<?php if(isset($bill->reg_num)){echo $bill->reg_num; } ?>">
  </div>
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12">
  <div class="form_type_list">
  <label class="">Billing Email *</label>
  <input type="email" class="form-control" id="email_address" name="email_address" value="<?php if(isset($bill->email_address)){echo $bill->email_address; } ?>">
  </div>
  </div>
  </div>  
    
    <div class="form-group form_list_top">
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Country :</label>
    <div class="select-style1">
    <?php if(isset($bill->country)){
    $c = $bill->country;
    }else{
    $c='';
    } ?>
    <select name="country">
    <?php $full_country = $this->admin_model->full_country();
    foreach($full_country as $full){
    ?>
    <option value="<?php echo $full->id; ?>" <?php if($full->id==$c){ echo "selected='selected'";}?>><?php echo $full->country_name; ?></option>
    <?php } ?>
    </select>
    </div>
    </div>
    </div>
    </div>  
    
    
    
    
<div class="form-group form_list_top">
      
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>

          <input class="btn btn-primary" type="submit" name="save" value="Submit">
    <?php } ?>
    </div>


    
    </form>


<?php } elseif($action=='property_info'){ ?>

<?php 
if($this->session->flashdata('profile')!='')
{  
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<div class="button gllpLatlonPicker">
<form class="form-horizontal" id="edit_property_forms" name="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update">

<input type="hidden" name="edit_hotel_id" value="<?php echo insep_encode($property->hotel_id)?>" id="edit_hotel_id"> 

<input type="hidden" name="redirect_url" value="property_info" id="redirect_url"> 


    <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Property name :</label>
     <input type="text" class="form-control" id="property_name" name="property_name" value="<?php echo $property->property_name;?>"> 
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Address :</label>
    <input type="text" class="form-control" id="address" name="address" value="<?php echo $property->address;?>">
    </div>
    </div>
  </div>  
  
  
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Currency :</label>
    <div class="select-style1">
      <?php $currencys = get_data(TBL_CUR)->result_array();?>
      <select name="currency">
      <?php foreach($currencys as $cur) { 
      extract($cur);?>
      <option value="<?php echo $currency_id; ?>" <?php if($currency_id==$property->currency) { ?> selected="selected" <?php } ?>> <?php echo $currency_name;?> </option>
      <?php } ?>
    </select>
    </div>
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
   <div class="form_type_list">
    <label class="">Country :</label>
    <div class="select-style1">
    <select name="country">
  <?php $countrys = get_data('country')->result_array();
  foreach($countrys as $value) { 
  extract($value);?>
    <option value="<?php echo $id;?>" <?php if($id==$property->country) { ?> selected="selected" <?php } ?>><?php echo $country_name;?></option>
    <?php } ?>
</select>
    </div>
    </div>
    </div>
    </div>  
    
  <div class="form-group form_list_top">
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Email :</label>
     <input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $property->email_address;?>">
    </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Url :</label>
     <input type="url" class="form-control" id="web_site" name="web_site" value="<?php echo $property->web_site;?>">
    </div>
    </div>
    </div>  
    
<div class="form-group form_list_top">

  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Phone :</label>
     <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $property->mobile;?>">
    </div>
    </div>

     <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">ZIP Code : </label>
    <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $property->zip_code;?>">
    </div>
    </div>
    
    </div>

    <div class="form-group form_list_top">
 

    <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="form_type_list">
    <label class="">Town : </label>
     <input type="text" class="form-control gllpSearchField" id="town" name="town" value="<?php echo $property->town?>">
    </div>
    </div>


   
  </div>
  <div class="form-group form_list_top">

     <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="form_type_list">
      <fieldset class="gllpLatlonPickers">
      <br>
      <div class="gllpMap"></div>
      <?php 
      $lat = substr($property->map_location, strpos( $property->map_location,",") + 1); 
      $lang1 = explode(",", $property->map_location);
      $lang = $lang1[0];      
      ?>
      <input type="hidden" class="gllpLatitude" value="<?php echo $lang?>" name="lat"/>
      <input type="hidden" class="gllpLongitude" value="<?php echo $lat?>" name="lang"/>
      <input type="hidden" class="gllpZoom" value="11"/>
      </fieldset> 
    </div>
    </div>
 
    </div>


   
  </div>
    
    
<div class="form-group form_list_top">      
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
  <input class="btn btn_update gllpSearchButton" type="submit" value="Submit">
  </div>
<?php } ?>
    

    
    
    </form>
    </div>

<?php } else if($action=="manage_newsletter"){ ?>

 <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>NewsLetter</h4>
  </div>  

<?php 
if($this->session->flashdata('newsletter_sucs')!='')
{
  
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('newsletter_sucs');?>
</div>
<?php } ?>

<form class="form-horizontal" id="newsletter" name="newsletter" method="post" action="<?php echo lang_url();?>channel/manage_newsletter">

<div  class="col-md-12">

          <div class="form-group">
          <p for="inputEmail3" class="col-sm-4 control-label">

          E-mail </p>

          <div class="col-sm-7">   

          <select   name="email[]" multiple class="form-control">
          <?php 
          foreach($useremail as  $useremail_add) { ?>
          <option selected value="<?php echo $useremail_add->email;?>"><?php echo $useremail_add->email;?></option> 
          <?php } ?>
          </select> 

          </div>

          </div>

            <div class="form-group">

              <p for="inputEmail3" class="col-sm-4 control-label">
              Subject </p>

            <div class="col-sm-7">

              <input type="text" class="form-control"  name="subject" id="subject">

            </div>

            </div>

          <div class="form-group">

          <p for="inputEmail3" class="col-sm-4 control-label">
          Description </p>

          <div class="col-sm-7">

          <textarea  id="aboutcontent" name="descrpation" class="form-control" style="height: 150px;"></textarea>

          </div>

          </div>

</div>
    
    
    
    

  
  

    
    
<div class="form-group form_list_top">      
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
  <input class="btn btn_update" type="submit" value="Submit" style="background:#2993bc; "></div>
<?php } ?>     
    
    </form>

<?php } elseif($action=="manage_property"){ ?>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">
  <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Manage Property</h4>
  </div>  
  <hr>

 <?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>

      <div class="verify_det clearfix">      
<?php
$get_property_cnt = get_data('manage_property',array('owner_id'=>user_id(),'hotel_id'=>hotel_id()))->num_rows();
  
$plan_pty = get_data(MEMBERSHIP,array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'plan_status'=>1))->row();

$plan_det = get_data(TBL_PLAN,array('plan_id'=>$plan_pty->buy_plan_id,'base_plan'=>'2'))->row();

if(isset($plan_det->number_of_hotels))
{
  $Hotels = $plan_det->number_of_hotels;
}
else
{
  $Hotels =1;
}


if($Hotels > $get_property_cnt){  
  
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' && $plan_pty->template_type=='2')
{  ?>

<a class="cls_com_blubtn pull-right hvr-sweep-to-right add_property"  href="javascript:;">Add New <i class="fa fa-plus"> </i></a>

<?php } } ?>
 

  </div>  

  <div class="clk_history dep_his pay_tab_clr">
         
        
       <div class="table-responsive">
     
      <table class="table table-bordered table-hover"> 
     <thead>
<tr class="info">
<th>#</th>
<th>Property Name</th>
<th>City</th>
<th>Country</th>
<th>Action</th>
</tr>
</thead>
    <thead>
      <?php if(count($hotel)!=0) { 
$ii=1;
foreach($hotel as $hotel_value) {
extract($hotel_value); ?>
<tr id="del_<?php echo insep_encode($hotel_id);?>">
  
<td><?php echo $ii;?></td>
<td> <a href="javascript:;" class="view_property" data-id="<?php echo insep_encode($hotel_id);?>"><?php echo ucfirst($property_name);?></a></td>
<td><?php echo $town;?></td>
<td><?php echo get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name;?></td>

<td> 
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{
?>
<a href="javascript:;" class="edit_property" data-id="<?php echo insep_encode($hotel_id);?>"> <i class="fa fa-edit"> </i></a>
<?php  } ?>
</td> 
</tr>
<?php  $ii++;} } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No active room data found...</td>
</tr>
<?php } ?>
    </thead>
 </table>
      </div>
      </div>
    <div class="clearfix"></div>
   
    
</div>
</div>


<!-- add property modal start -->

<div class="modal fade dialog-2 " id="add_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add Property</h4>
      </div>
      <div class="modal-body sign-up-m">
    <div class="row">
  
    <form role="form" class="form-horizontal cls_mar_top" name="add_property_form" id="add_property_form" method="post" action="<?php echo lang_url();?>channel/manage_property/add" enctype="multipart/form-data">
  
  <div id="add_property_modal">
  </div>  
  </form>
   </div>
      </div>
      
    </div>
  
  </div>
</div>

<div class="modal fade dialog-2 " id="edit_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update Property</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
   
  <form role="form" class="form-horizontal cls_mar_top" name="edit_property_forms" id="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update" enctype="multipart/form-data">
  
  <div id="edit_property_modal">
  </div>  
  </form>
  
   
   </div>
   
   
      </div>
      
    </div>
  
  </div>
</div>

<!-- add property modal end   -->

<!-- view property show   -->

<div class="modal fade dialog-2 " id="view_property_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document" >
  
  <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">View Property</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
   
  <form role="form" class="form-horizontal cls_mar_top" name="edit_property_forms" id="edit_property_forms" method="post" action="<?php echo lang_url();?>channel/manage_property/update" enctype="multipart/form-data">
  
  <div id="view_property_modal">
  </div>  
  </form>
   
   </div>
      </div>
    </div>
  </div>
</div>

<!-- view property end   -->

<?php } elseif($action=="manage_subusers"){ ?>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">
  <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Manage Property</h4>
  </div> 


  <hr>

  <div class="verify_det clearfix">      
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{  ?>
<!-- <a class="cls_com_blubtn pull-right hvr-sweep-to-right add_property"  href="javascript:;">Add New <i class="fa fa-plus"> </i></a> -->

<!-- <a href="#add_user" class="cls_com_blubtn pull-right waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000">
 <i class="fa fa-plus-circle"></i> Add Users<i class="fa fa-plus"> </i> </a> -->


 <a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#add_user" href="javascript:;" data-backdrop="static" data-keyboard="false">Add Users <i class="fa fa-plus"> </i></a>

<?php } ?>
 

  </div> 

  <?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">

<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>

    
  <div class="clk_history dep_his pay_tab_clr">
         
        
       <div class="table-responsive">



     
      <table class="table table-bordered table-hover"> 
     <thead>
<tr class="info">
<th>#</th>
<th>User Name</th>
<th>User Password</th>
<!--<th>User Email</th>-->
<th>Access</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
      <tbody>

  <?php
  $assign_user = $this->channel_model->get_user_list();
  if($assign_user)
  {
    $i=0;
    foreach($assign_user as $users)
      {
      $access       = (array)json_decode($users->access);
      $user_access_view='';
      $user_access_edit='';
      foreach($access as $photo_id=>$photo_obj)
      {
        if(!empty($photo_obj))
        {
          $photo = (array)$photo_obj;          
          if(isset($photo['view'])!='')
          {
            //echo 'ee'.$photo = $photo_id;
            $fetch_access = $this->channel_model->fetch_access($photo_id);
            $user_access_view.=$fetch_access->acc_name.' , ';
          }
          else
          {
            $user_access_view.='';
          }
          if(isset($photo['edit'])!='')
          {
            $fetch_access1 = $this->channel_model->fetch_access($photo_id);
            $user_access_edit.=$fetch_access1->acc_name.' , ';
          }
          else
          {
            $user_access_edit.='';
          }
        }
      }
             
      $i++;
    $get = $this->channel_model->get_username($users->user_id);  
     ?>

<tr>
  <td><?php echo $i; ?></td>
  <td><?php echo $get->user_name; ?></td>
    <td><?php echo $get->spass; ?></td>
    <!--<td><?php //echo $get->email_address; ?></td>-->
  <td> 
<ul class="manage_pro list-unstyled">
<?php if($user_access_view!='')
{
?>
<li> <div>View :</div>
<?php 

     echo trim($user_access_view,' , ');
?>
</li>
<?php } ?>
<?php if($user_access_edit!='')
{
?>
<li> <div>Edit :</div>
<?php 
     echo trim($user_access_edit,' , ');
?>
</li>
<?php } ?></ul>
</td>
 <td><?php if($get->status=='0') { ?> <span class="label label-sm label-danger">In-active</span> <?php }elseif($get->status=='1') { ?> <span class="label label-sm label-success">Active</span> <?php } ?></td>

<td>
  <a href="javascript:;" onclick="return edit_user('<?php echo $users->priviledge_id; ?>');"> <i class="fa fa-pencil" title="Edit this user"></i></a> &nbsp;

  <li class="display_none">
   <a href="#user_edit" class="btn waves-effect waves-light m-r-5 m-b-10 user_edit" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000"></a> 
  </li>
    
    <a href="<?php echo lang_url();?>channel/users_status/<?php echo insep_encode($users->priviledge_id).'/'.insep_encode($users->user_id);?>" class="">
    <?php if($get->status=='0') { ?>
    <i class="fa fa-check-square-o" title="Active this user"> </i>
    <?php } else { ?>
    <i class="fa fa-ban" title="In-active this user"> </i>
    <?php } ?>
    </a> &nbsp;
  
    <a href="<?php echo lang_url();?>channel/users_delete/<?php echo insep_encode($users->priviledge_id).'/'.insep_encode($users->user_id);?>" class=""> <i class="fa fa-trash-o text-danger" title="Delete this user" custom="<?php echo $i;?>"> </i></a>
  
    <!-- Edit User-->
  

 </td>  
</tr>
<?php }  } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No users data found...</td>
</tr>
<?php } ?>


</tbody>
 </table>
      </div>
      </div>
    <div class="clearfix"></div>
   
    
</div>
</div>

<?php } ?>

<?php $this->load->view('channel/dash_sidebar'); ?>

</div>

  
</div>
               
    </div>
    </div><!-- /scroller-inner -->
   
    
</div><!-- /scroller -->

</div>




<!-- Users Edit start -->

<!-- <div id="user_edit" class="modal-demo modal-dialog modal_list modal-content">
  <button type="button" class="close reg_close" onclick="Custombox.close();">
  <span>&times;</span><span class="sr-only">Close</span>
  </button>
  <h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
  <hr>
  <div class="custom-modal-text">   

      <form role="form" class="form-horizontal cls_mar_top register-form" method="post" action="<?php echo lang_url();?>channel/users_update" enctype="multipart/form-data" name="user_edits" id="user_edits" style="display: block;">
    <div class="reg_content" id="edit_users">
    </div>
    </form>
  </div>
</div>  -->

<!-- user edit -->

<!-- Users Edit start -->

<div class="modal fade dialog-2 " id="user_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">
  
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
    
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Update User</h4>
      </div>
    
      <div class="modal-body sign-up-m">
      <div class="row">
    <form role="form" class="form-horizontal cls_mar_top" method="post" action="<?php echo lang_url();?>channel/users_update" enctype="multipart/form-data" name="user_edits" id="user_edits">
      <div class="col-md-12" id="edit_users">
      
      </div>
    
    </form>
     
     </div>
   
  </div>
   
    </div>
      
    </div>
  
  </div> 
<!-- user edit -->