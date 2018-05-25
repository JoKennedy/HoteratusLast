<?php $this->load->view('admin/header'); ?>
<style>
#Container .mix{
	display: none;
	width:100% !important;
}
#Container1 .mix{
	display: none;
	width:100% !important;
}
.li_width
{
	width:100%;
}
</style>
<div class="breadcrumbs">
<div class="row-fluid clearfix"> <?php //echo 'admin_id'.admin_id().'admin_type'.admin_type();?>
<i class="fa fa-home"></i> <?php echo ucfirst($channel->property_name);?>
<a href="<?php echo lang_url();?>channel/change_property/<?php echo secure($channel->hotel_id).'/'.secure(admin_id()).'/'.secure(admin_type());?>" target="_blank"> <span class="pull-right"><i class="fa fa-globe"></i> Extranet <i class="fa fa-star-o"></i> </span> </a>
</div>
</div>
<div class="manage">
<div class="row-fluid clearfix">
<div class="col-md-6" id="hacker-list">
<h2>Channels</h2>
<div class="row">
<div class="col-md-4">
 <div class="input-group">
      <input type="text" class="form-control search" placeholder="Channel Name" onkeypress="return count_lungth();" onkeyup="return count_lungth();" onkeydown="return count_lungth();" onblur="return count_lungth();" onchange="return count_lungth();" onfocus="return count_lungth();">
      <span class="input-group-btn">
      </span>
    </div>
</div>
<div class="col-md-8">
<div class="searcha">
<a href="javascript:;" class="filter active" data-filter="all">All</a>
<a href="javascript:;" data-filter=".cat" class="filter">Updates</a>
<a href="javascript:;">Failed Updates</a>
<a href="javascript:;">Config errors</a>
<a href="javascript:;">disabled</a>
<a href="javascript:;">activation</a>
</div>
</div>
</div>
<div class="row">
<nav>
  <ul class="pagination pull-right">
     <?php echo $this->pagination->create_links(); ?>
  </ul>
</nav>
</div>
<div id="Container">

<div class="row clearfix">
<?php 
      if($this->session->flashdata('cha_success')!='')
      {
        
      ?>
        <div class="alert alert-success">
          <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
            <?php echo $this->session->flashdata('cha_success');?>
        </div>
      <?php } ?>
<div class="col-md-12">
<div class="panel-group" id="accordion1">
<ul class="list list-inline">
<?php 
		$hotel_id = $channel->hotel_id;
		$chans = $connected_channel_count;
				$i=0;
			if($chans){
				// $channel_name = $this->admin_model->channel_name($chans);
				foreach($channel_name as $name){
					$i++;
		?>
        <li class="li_width panel-group">
		<div class="panel panel-default mix <?php if($i==2) { echo 'cat';}?>">
    <div class="panel-heading">
	
      <h4 class="panel-title">
		
        <a class="accordion-toggle name" data-toggle="collapse" data-parent="#accordion" href="#collapsethree_<?php echo $i; ?>">
          <?php echo $name->channel_name; ?>
		  <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
        </a>
			
      </h4>
	  
    </div>
	
    <div id="collapsethree_<?php echo $i; ?>"  class="panel-collapse collapse">
      <div class="panel-body">
	  <?php $id = $name->channel_id;
			$channel_details = $this->admin_model->channel_details($hotel_id,$id);
      if($channel_details){
	  ?>
   
	   <form class="form-horizontal" method="post" action ="<?php echo lang_url();?>admin/save_channel_details/<?php  echo insep_encode($id); ?>/<?php echo insep_encode($hotel_id);?>">
          <div class="form-group">
                <label for="" class="col-sm-2 control-label">status</label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="" value="enabled" <?php if($channel_details->status=='enabled'){echo 'checked="checked"';} ?>> enabled
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="" value="disabled" <?php if($channel_details->status=='disabled'){echo 'checked="checked"';} ?>> disabled
                  </label>
                </div>
              </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-4">
              <input type="text" name="user_name" class="form-control" id="" value="<?php if(isset($channel_details->status)){ echo $channel_details->user_name; } ?>" placeholder="">
            </div>
            <label for="" class="col-sm-2 control-label">Hotel Id</label>
            <div class="col-sm-4">
              <input type="text" name="hotel_channel_id" class="form-control" id="" value="<?php if(isset($channel_details->status)){echo $channel_details->hotel_channel_id; } ?>" placeholder="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-4">
              <input type="password" name="user_password" class="form-control" id="" value="<?php if(isset($channel_details->user_password)){ echo $channel_details->user_password; } ?>" placeholder="">
            </div>
            <?php if($id == 8) { ?>
            <label for="" class="col-sm-2 control-label">Web ID</label>
            <div class="col-sm-4">
              <input type="text" name="web_id" class="form-control" id="" value="<?php if(isset($channel_details->web_id)){echo $channel_details->web_id; } ?>" placeholder="">
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <div class="col-sm-4 pull-right">
              <input type="submit" class="form-control btn btn-primary" value="Save">
            </div>
          </div>
        </form> 
       <?php }else{ ?>
        <div class="panel-body">
          <h3>This Channel is not yet connected</h3>
        </div>
       <?php } ?>
      </div>
    </div>
</div>
		</li>
        
<?php } }else{ ?>
<li class="li_width panel-group">
	<div class="panel panel-default">
    <div class="panel-heading">
	
      <h4 class="panel-title">
		
          No Channels Connected
        	
      </h4>
	  
    </div>
    
</div>
</li>
  <?php } ?>
</ul>
</div>
<div class="alert alert-danger text-center li_width sh_hi" style="display:none"> No result found!!!</div>
</div>
</div>
</div>
</div>
<div class="col-md-6" id="hacker-list2">
<h2>Hotel Users <span class="pull-right">
			
		<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#add_user" data-backdrop="static" data-keyboard="false">Add Users</a></span></h2> 
		
<div class="row">
<div class="col-md-4">
 <div class="input-group">
      <input type="text" class="form-control search" placeholder="User Name" onkeypress="return count_lungth1();" onkeyup="return count_lungth1();" onkeydown="return count_lungth1();" onblur="return count_lungth1();" onchange="return count_lungth1();" onfocus="return count_lungth1();">
      <span class="input-group-btn">
      
      </span>
    </div><!-- /input-group -->
</div>
<div class="col-md-8">
<div class="searcha pull-right">
</div>
</div>
</div>
<br>
<div id="Container1">
<div class="row clearfix">
<div class="col-md-12">
<div class="panel-group" id="accordion">
<ul class="list list-inline">
<?php 
if($this->session->flashdata('profile')!='')
{
	
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
	<?php $user = $channel->assigned_user;
		$i=0;
		if($user){
			$user_name = $this->admin_model->user_name($user);
      //print_r($user_name);
			foreach($user_name as $us){
        $assign_user = $this->admin_model->get_priviledge($us->user_id,$us->owner_id);
        foreach ($assign_user as $user) {
          $access = json_decode($user->access);
          $user_access_view='';
          $user_access_edit='';
          foreach($access as $photo_id=>$photo_obj)
          {
            if(!empty($photo_obj))
            {
              $photo = (array)$photo_obj;
              //echo 'menu'.$photo_id.'<br>';
              //echo 'edit'.$photo['edit'].'<br>';
              //echo 'view'.$photo['view'].'<br>';
              
              if(isset($photo['view'])!='')
              {
                //echo 'ee'.$photo = $photo_id;
                $fetch_access = $this->admin_model->fetch_access($photo_id);
                $user_access_view.=$fetch_access->acc_name.' , ';
              }
              else
              {
                $user_access_view.='';
              }
              if(isset($photo['edit'])!='')
              {
                $fetch_access1 = $this->admin_model->fetch_access($photo_id);
                $user_access_edit.=$fetch_access1->acc_name.' , ';
                //echo 'vv'.$photo = $photo_id;
              }
              else
              {
                $user_access_edit.='';
              }
            }
          }
        }
        
				$i++;
	?>
    <li class="li_width panel-group">
	
  	<div class="panel panel-default mixs <?php if($i==1) { echo 'inactive';} ?>">
	
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle name" data-toggle="collapse" data-parent="#accordion1" href="#collapsesix_<?php echo $i; ?>">
         <?php echo $us->user_name; ?>
         <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
        </a>
      </h4>
    </div>
    <div id="collapsesix_<?php echo $i; ?>" class="panel-collapse collapse ">
      <div class="panel-body">
      
       <h3> <div class="col-md-5"> <strong>User Name</strong> </div> <div class="col-md-7"> <strong>:</strong>  <?php echo $us->user_name; ?> </div> </h3><div class="clearfix"> </div>
	   
       <h3> <div class="col-md-5"> <strong>Email</strong> </div> <div class="col-md-7"> <strong>:</strong> <?php echo $us->email_address; ?> </div>	</h3><div class="clearfix"> </div>
	   
       <h3> <div class="col-md-5"> <strong>View:</strong> </div> <div class="col-md-7"> <strong>:</strong> <?php echo trim($user_access_view,' , '); ?> </div> </h3><div class="clearfix"> </div>
       <h3> <div class="col-md-5"> <strong>Edit:</strong> </div> <div class="col-md-7"> <strong>:</strong> <?php echo trim($user_access_edit,' , '); ?> </div> </h3><div class="clearfix"> </div>
	          
      </div>
    </div>
</div>

	</li>
		<?php } }else{ ?>
    <li class="li_width panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					  No Users Found
				  </h4>
				</div>
			</div>
	</li>
		<?php } ?>
</ul>
</div>
		<div class="alert alert-danger text-center li_width sh_hi1" style="display:none"> No result found!!!</div>
		
</div>
</div>
</div>
</div>
</div>
<div class="row-fluid clearfix">
  <div class="col-md-6">
    <h2>Details <span class="pull-right">

	 <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#edit_details" data-backdrop="static" data-keyboard="false">Edit details</a>
	
	    <a href="javascript:;" class="btn btn-primary">Switch white label</a>
	    </span>
    </h2>
    <div class="row">
      <?php 
      if($this->session->flashdata('success')!='')
      {
      	
      ?>
        <div class="alert alert-success">
          <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
            <?php echo $this->session->flashdata('success');?>
        </div>
      <?php } ?>
      <div class="col-md-4">

      </div>
      <div class="col-md-8">

      </div>
    </div>
    <br>

    <div class="red-bor clearfix"> <h3>White Label</h3><span >Tourism Apps Independents </span> </div>
    <div class="white-bor clearfix"><h3>First Name</h3>
      <span><?php echo $channel->fname.'  ',$channel->lname; ?>
      </span>
    </div>
    <div class="red-bor clearfix"> <h3>User Name</h3><span>
      <?php $user = $channel->owner_id;
      	  $user_nam = $this->admin_model->get_user_name($user);
      	  if($user_nam){
           echo $user_nam->user_name;
          }
       ?>
      </span>
    </div>
    <div class="white-bor clearfix"><h3>Address</h3>
      <span><?php echo $channel->address; ?>
      </span>
    </div>
    <div class="red-bor clearfix"><h3>Contact</h3><span><?php echo $channel->mobile; ?></span> </div>
    <div class="white-bor clearfix"><h3> Timezone</h3><span>Europe</span>  </div>
    <div class="red-bor clearfix"> <h3>Master Rates</h3><span>Enabled 
    </span> </div>
    <div class="white-bor clearfix"><h3>City</h3>
    <span><?php echo $channel->town; ?>
     </span>
      </div>
    <div class="red-bor clearfix"> <h3>Country</h3>
    <?php if($channel->country){
     $country = $this->admin_model->country_name($channel->country);
     }
     ?>
    <span><?php if(isset($country->country_name)){echo $country->country_name;} ?></span> </div>
    <div class="white-bor clearfix"><h3>Zip Code</h3>
      <span><?php echo $channel->zip_code; ?>
      </span>
    </div>
    <div class="red-bor clearfix"> <h3>Email Address</h3><span><?php echo $channel->email_address; ?></span> </div>
</div>
<div class="col-md-6" id="hacker-list3">
<h2>Room Types </h2>
<div class="row">
<div class="col-md-4">
 <div class="input-group">
      <input type="text" class="form-control search" placeholder="Room Name" onkeypress="return count_lungth2();" onkeyup="return count_lungth2();" onkeydown="return count_lungth2();" onblur="return count_lungth2();" onchange="return count_lungth2();" onfocus="return count_lungth2();">
      <span class="input-group-btn">
       
      </span>
    </div>
</div>
<div class="col-md-8">
<nav>
  <ul class="pagination pull-right">
    <li>
     <!-- <a href="#" aria-label="Previous">
       <i class="fa fa-arrow-left"></i>
      </a>-->
    </li>
    <!--<li><a href="#">1</a></li>
    <li><a href="#">2</a></li>-->
   
    <li>
      <!--<a href="#" aria-label="Next">
       <i class="fa fa-arrow-right"></i>
      </a>-->
    </li>
  </ul>
</nav>
</div>
</div>
<br>
<div class="row clearfix">
<div class="col-md-12">
<div class="panel-group" id="accordion2">
<ul class="list list-unstyled">
	<?php  
			$hotel = $channel->hotel_id;
			$i=0;
			$property_name = $this->admin_model->property_name($hotel);
			if($property_name){
			foreach($property_name as $proper){
				$i++;
	?>
  	<li class="li_width panel-group">
  	<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle name" data-toggle="collapse" data-parent="#accordion" href="#collapseeight_<?php echo $i; ?>">

        <?php echo $proper->property_name; ?>
        </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
      </h4>
    </div>
	
    <div id="collapseeight_<?php echo $i; ?>" class="panel-collapse collapse">
      <div class="panel-body">
	  <h3><b>Mapped in Channels</b></h3>
	  <?php $pro =  $proper->property_id; ?>
	  <?php $property_id = $this->admin_model->property_id($pro);
			if($property_id){
			foreach($property_id as $prop){
				$ch = $prop->channel_id;
				$channels = $this->admin_model->channels_name($ch);
	  ?>
	   
       <h3><?php echo $channels->channel_name; ?></h3>
       
			<?php } }else{ ?>
				<div class="panel-body">
					<h3>No Mapping Channels Found</h3>
				</div>
			<?php } ?>
      </div>
    </div>
</div>
	</li>
			<?php } }
			  else{ ?>
              <li class="li_width panel-group">
			  <div class="panel panel-default">
    <div class="panel-heading">
	
      <h4 class="panel-title">
		
       
        No Rooms found
       
			
      </h4>
	  
    </div>
    
</div>
			   </li>
		    <?php  }	?>
</ul>
</div>
<div class="alert alert-danger text-center li_width sh_hi2" style="display:none"> No result found!!!</div>
</div>
</div>
</div>
<?php if($plan_details){ ?>
<?php foreach($plan_details as $plan_detail){ ?>
<div class="col-md-6">
<form name="membership_save" method="post" action="<?php echo lang_url();?>admin/save_indvidualplan/<?php echo insep_encode($plan_detail->hotel_id);?>/<?php echo insep_encode($plan_detail->user_buy_id);?>">
<h2>Membership Plan<span class="pull-right">

   <button type="submit" class="btn btn-primary" >Save Changes</button>
      </span></h2>
<div class="row">
<?php 
      if($this->session->flashdata('mem_success')!='')
      {
        
      ?>
        <div class="alert alert-success">
          <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
            <?php echo $this->session->flashdata('mem_success');?>
        </div>
      <?php } ?>
<div class="col-md-4">

</div>
<div class="col-md-8">

</div>
</div>
<br>

<div class="red-bor clearfix"> <h3>Plan Types</h3><span><?php echo $plan_detail->buy_plan_type; ?></span> </div>
<div class="white-bor clearfix"><h3>Plan Name</h3>
<span><?php echo $plan_detail->plan_name; ?></span>
  </div>
<div class="red-bor clearfix"> <h3>Currency</h3><span>
<?php  
  $currency_details = get_data(TBL_CUR)->result_array();
  foreach($currency_details as $curr) {
    extract($curr);
    if($currency_id == $plan_detail->buy_plan_currency){
      echo $currency_name;
    }
  }
?>
</span> </div>
<div class="white-bor clearfix"><h3>Plan Duration </h3>
<span <?php if(isset($expired)){ ?> style="color: red;" <?php } ?>><?php echo $plan_detail->plan_from; ?> - <?php echo $plan_detail->plan_to; ?> <?php if(isset($expired)){ ?> -Expired <?php }?></span>
<div class="white-bor clearfix"><h3>Plan Duration</h3>
<span <?php if(isset($expired)){ ?> style="color: red;" <?php } ?>><?php echo $plan_detail->plan_from; ?> - <?php echo $plan_detail->plan_to; ?> <?php if(isset($expired)){ ?> -Expired <?php }?></span>

  </div>
<div class="red-bor clearfix"><h3>Plan Price</h3><span><?php echo $plan_detail->buy_plan_price; ?></span> </div>
<div class="white-bor clearfix"><h3>Change plan</h3><span>
  <select name="ind_plan" id="ind_plan" class="form-control" onchange="choose_channel(this.value);">
    <?php if($indplans) {?>
       <option value="Null">Change Plan</option>
      <?php foreach($indplans as $indplan){?>
        <option value="<?php echo $indplan->plan_id; ?>"><?php echo $indplan->plan_name; ?></option>
      <?php } ?>
    <?php } ?>
  </select>
</span>  </div>
<div class="red-bor clearfix"><h3>Extend Plan</h3><span><input class="form-control" name="extend_plan" id="extend_plan" value="<?php echo $plan_detail->extend_plan; ?>" /></span> </div>
</form>
</div>
<?php } 
} ?>
</div>
</div>
<div class="modal fade dialog-2" id="add_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add User</h4>	
      </div>
      <div class="modal-body sign-up-m">
	  
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="add_users" id="add_users" method="post" action="<?php echo lang_url();?>admin/add_users/<?php echo insep_encode($hotel); ?>" enctype="multipart/form-data">
      <div class="col-md-12">
		
		<input type="hidden" name="owner_id" value="<?php echo $channel->owner_id; ?>">
  
   <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    User Name <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <input type="text" placeholder="User Name" id="channel_username" name="user_name" class="form-control">
    </div>
  </div>
 	
  
 <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    User Email <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <input type="email" placeholder="User Email" id="registeremail" name="email_address" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    User Password <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <input type="password" id="password" name="user_password" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    Confirm Password <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <input type="password" id="confirm_password" name="confirm_password" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    Currency <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <select class="form-control" name="currency_name">
	  <?php $full_curre = $this->admin_model->full_currency(); 
			foreach($full_curre as $full_curr){
	  ?>
	  <option value="<?php echo $full_curr->currency_id ?>"><?php echo $full_curr->currency_name; ?></option>
			<?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
    Country <font color="#CC0000">*</font></p>
    <div class="col-sm-7">
      <select class="form-control" name="country_name">
	  <?php $full_coun = $this->admin_model->full_country();
			foreach($full_coun as $full_c){
	  ?>
	  <option value="<?php echo $full_c->id; ?>">
			<?php echo $full_c->country_name; ?>
	  </option>
	  <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
    User Access <font color="#CC0000">*</font></p>
    <div class="col-sm-7 nf-select">
<div class="col-md-6">
<h5>Edit</h5>
    <?php 	
		$access = get_data(TBL_ACCESS,array('status'=>1))->result_array();?>
		<input type="checkbox" id="selectall"> All
		<?php 
         $a=1;
		 
		foreach($access as $u_acc)
		{
			extract($u_acc);
	?>
	<br>
  <?php if($acc_name != "Dashboard"){ ?>
    <input type="checkbox" class="mycheckbox" value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][edit]" id="edit_<?php echo $acc_id;?>" onclick="return edit_check('<?php echo $acc_id;?>');"> <?php echo $acc_name;?> 
		
    <?php $a++;} } ?>
		
		<input type="hidden" name="total_success" value="<?php echo $a; ?>">
		
     </div>
	 
	 <div class="col-md-6">
		<h5>View</h5>
    <?php 	
		$access = get_data(TBL_ACCESS,array('status'=>1))->result_array();?>
		<input type="checkbox" id="select_all"> All
		<?php 
         $a=1;
		 
		foreach($access as $u_acc)
		{
			extract($u_acc);
	?>
	<br>
  <?php if($acc_name != "Channel"){ ?>
    <input type="checkbox" class="my_checkbox" value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][view]" id="view_<?php echo $acc_id;?>"> <?php echo $acc_name;?> 
		
    <?php $a++;} } ?>
	
		<input type="hidden" name="total_success" value="<?php echo $a; ?>">
     </div>
  </div>
  </div>
   <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit" name="add" value="save">Add User</button>
    </div>
  </div>
	  </div>
      
     </form>
     </div>
   
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade dialog-2" id="edit_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Edit Hotel Details</h4>	
      </div>
      <div class="modal-body sign-up-m">
	  
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="add_users" id="edit_details" method="post" action="<?php echo lang_url();?>admin/edit_details/<?php echo insep_encode($hotel); ?>" enctype="multipart/form-data">
      <div class="col-md-12">
  
	   <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		User Name <font color="#CC0000">*</font></p>
		<?php  
			 $user_id = $channel->owner_id;
			 $user_name = $this->admin_model->get_user_name($user_id);?>
			 
		<div class="col-sm-7">
		  <input type="text" placeholder="User Name" id="channel_username" name="user_name" class="form-control" value="<?php if(isset($user_name))echo $user_name->user_name; ?>">
		</div>
	  </div>
  
	  <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		User Email <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		  <input type="email" placeholder="User Email" id="registeremail" name="email_address" class="form-control" value="<?php echo $channel->email_address; ?>">
		</div>
	  </div>
	  
	   <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		City <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		  <input type="text" placeholder="User Name" id="channel_username" name="town" class="form-control" value="<?php echo $channel->town; ?>">
		</div>
	  </div>
	  
	   <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		Address <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		  <input type="text" placeholder="User Name" id="address" name="address" class="form-control" value="<?php echo $channel->address; ?>">
		</div>
	  </div>
	  
	   <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		Zip Code <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		  <input type="text" placeholder="User Name" id="zip_code" name="zip_code" class="form-control" value="<?php echo $channel->zip_code; ?>">
		</div>
	  </div>
	  <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		Mobile <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		  <input type="text" placeholder="User Name" id="mobile" name="mobile" class="form-control" value="<?php echo $channel->mobile; ?>">
		</div>
	  </div>
	  <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		Currency <font color="#CC0000">*</font></p>
		<div class="col-sm-7">
		 
		  <select class="form-control" name="currency_name" id="currency_name">
		   <?php $full_currency = $this->admin_model->full_currency();
					foreach($full_currency as $full){
		  ?>
		  <option value="<?php echo $full->currency_id ?>" <?php if($full->currency_id==$channel->currency){echo 'selected="selected"';} ?>><?php echo $full->currency_name; ?></option>
					<?php } ?>
		  </select>
		</div>
	  </div>
	  <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		Country <font color="#CC0000">*</font></p>
		
		<div class="col-sm-7">
		
		  <select class="form-control" name="country_name">
		  <?php $full_country = $this->admin_model->full_country();
				foreach($full_country as $con){
		  ?>
			<option value="<?php echo $con->id; ?>" <?php if($con->id==$channel->country){echo 'selected="selected"';} ?>><?php echo $con->country_name; ?>
			</option>
				<?php } ?>
		  </select>
		</div>
	  </div>
  
  
   <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit" name="add" value="save">Save Changes</button>
    </div>
  </div>
	  </div>
      
     </form>
     </div>
   
      </div>
      
    </div>
  </div>
</div>

<!-- Full Refresh -->
<div class="modal fade dia-2" id="myModal-p2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form method="post" id="select_channels_to_subscribe" action="<?php echo lang_url();?>admin/save_indvidualplan/<?php echo insep_encode($plan_detail->hotel_id);?>/<?php echo insep_encode($plan_detail->user_buy_id);?>">
    <div class="modal-content select_channels_to_subscribe">
    </div>
    </form>
  </div>
</div>
<!-- Full Refresh -->

<?php $this->load->view('admin/footer'); ?>

<script src="<?php echo base_url();?>admin_assets/js/jquery.mixitup.js"></script>
    <script>

function choose_channel(plan_id){
  $.ajax({
    type: "POST",
    url:'<?php echo lang_url(); ?>admin/select_channels_details/'+plan_id,
    
    success: function(result)
    {
      
      if(result != "0"){
        $('.select_channels_to_subscribe').html(result);
        $('#myModal-p2').modal({backdrop: 'static',keyboard: false});
        
      }
      else{
       /* $('<input>').attr({
            type: 'hidden',
            name: 'ical_plan',
            value: plan_id,
        }).appendTo('#select_channels_subscribe');
        $("#select_channels_subscribe").submit();*/
      }

      //$('#show_credit_card').html(result);
  //    $('#myModal23').modal({backdrop: 'static',keyboard: false});      
    }
  });
}
    function edit_check(id){
      $("#view_"+id).attr("checked",true);
    }
	$(function(){
	$('#Container').mixItUp();
	//$('#Container1').mixItUps();	
	});
	$().ready(function()
	{
		var options = { valueNames: [ 'name', 'born' ] };
	
		var hackerList = new List('hacker-list', options);
		
		var options = { valueNames: [ 'name', 'born' ] };
	
		var hackerList2 = new List('hacker-list2', options);
		
		var options = { valueNames: [ 'name', 'born' ] };
	
		var hackerList3 = new List('hacker-list3', options);
	});
	
	function count_lungth()
	{
		var count = $("#accordion1 li").length;
		
		if(count==0)
		{
			$('.sh_hi').show();
		}
		else
		{
			$('.sh_hi').hide();
		}
	}
	
	function count_lungth1()
	{
		var count = $("#accordion li").length;
		
		if(count==0)
		{
			$('.sh_hi1').show();
		}
		else
		{
			$('.sh_hi1').hide();
		}
	}
	
	function count_lungth2()
	{
		var count = $("#accordion2 li").length;
		
		if(count==0)
		{
			$('.sh_hi2').show();
		}
		else
		{
			$('.sh_hi2').hide();
		}
	}
	
	jQuery.validator.addMethod("lettersonly", 
			function(value, element) {
       		 return this.optional(element) || /^[a-z," "]+$/i.test(value);
			}, "Letters only please");
			
$.validator.addMethod("regexp", function(value, element, param) { 
  return this.optional(element) || !param.test(value); 
});	

/*jQuery.validator.addMethod("customemail", function(value, element) 
			{
   				return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
			}, "Please enter a valid email address.");*/
			
			//custom validation rule
			$.validator.addMethod("customemail", 
				function(value, element) {
					return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
				}, 
				"Sorry, I've enabled very strict email validation"
			);
			
	
	</script>
	
	 <script>
  $("#add_users").validate({
			rules: {
                  user_name: {
                        required: true,
						 remote: {
							url:"<?php echo lang_url(); ?>channel/register_username_exists",
							type:"post",
							data:{
								username:function(){
									return $("#channel_username").val();
								}
							}
						},
                    },
					user_password: {
                        required: true,
                    },
					confirm_password : {required: true,equalTo:"#password"},
					email_address: {
							required: true,
							customemail:true,
							remote: {
											url: "<?php echo lang_url(); ?>channel/register_email_exists",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#registeremail").val(); 
													}
												  }
										}
						},
					
                },
				
			errorPlacement: function(){
					return false; 
    		},
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
			
	
</script>

<script language="javascript">

$(function(){

    $("#selectall").change(function () {

         $(".mycheckbox").prop('checked', $(this).prop('checked'));

    });

    $(".mycheckbox").change(function(){

        if($(".mycheckbox").length == $(".mycheckbox:checked").length)

        $("#selectall").attr("checked", "checked");

			else

        $("#selectall").removeAttr("checked");

    });

});

</script>

<script language="javascript">

$(function(){
    $("#select_all").change(function () {

         $(".my_checkbox").prop('checked', $(this).prop('checked'));

    });

    $(".my_checkbox").change(function(){

        if($(".my_checkbox").length == $(".my_checkbox:checked").length)

        $("#select_all").attr("checked", "checked");

			else

        $("#select_all").removeAttr("checked");

    });

});

</script>
    
</body>
</html>