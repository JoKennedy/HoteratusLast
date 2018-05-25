<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">

</div>


<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
<?php $this->load->view('channel/property_sidebar');?>
</div></div></div></div>            
            
         
<?php
/*echo '<pre>';
print_r($plan_details);*/
//echo $plan_details;
 //die;
?>  
            
     </div>
</div>

</div>


</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">

<div class="bg-neww">

<div class="tab-content">

<!-- Subscribe Start -->
<div class="tab-pane <?php if(uri(3)=='rate_management') {?>active<?php } ?>" id="tab_default_3">
<div class="col-md-4 col-sm-4"><div class="bb1 sub-m">
<div class="lik"><center><i class="fa fa-list-alt"></i></center></div>
<hr>
<?php if(isset($action)=='channel_subscribe') { ?>
<form action="<?php echo lang_url();?>channel/subscribe_step/<?php echo $channel_id;?>" method="post">
<div class="row" style="height: 300px; overflow-y: scroll;">
<?php 
$subscribe_plans = get_data(CHA_PLAN,array('status'=>1))->result_array();
if(count($subscribe_plans)!=0)
{
	$i=0;
	foreach($subscribe_plans as $plan)
	{
		extract($plan);

		$i++;
		?>
    <div class="check-1">
<div class="radio">
  <label>
    <input type="radio" name="plan" id="optionsRadios1" value="<?php echo $channel_id;?>" <?php if($i==1) { ?>checked <?php } ?>>
    <?php if($channel_type=='Free') {echo $channel_price.' '.$channel_plan; } else { echo $channel_plan; }?> <br/>
    <?php if($channel_type=='Free') { ?> 
    <span class="gt"><?php echo 'Free';?></span>
    <?php } else { 
	$currency_tbl = get_data(TBL_CUR,array('currency_id'=>$currency))->row_array();
	?>
    <span class="gt"><?php echo $currency_tbl['symbol'].$channel_price.' '.$currency_tbl['currency_code'].' / '.$channel_type;?> </span>
    <?php } ?>
  </label>
</div>
</div>    
        <?php
	}
}
?>
</div>
<div class="row">
<div class="col-md-12 col-md-12">
<button type="submit" class="btn btn-success col-md-12">SUBSCRIBE</button>
</div>
</div>

</form>
<?php } else { ?>
<form action="<?php echo lang_url();?>inventory/rate_management" method="post">
<div class="row" style="height: 300px; overflow-y: scroll;">
<?php 
$subscribe_plans = get_data(TBL_PLAN,array('status'=>1,"base_plan"=>1))->result_array();
if(count($subscribe_plans)!=0)
{
  
	foreach($subscribe_plans as $plan)
	{
		extract($plan);
    $free_plan_exist = get_data(MEMBERSHIP,array('user_id'=>current_user_type(),"hotel_id"=>hotel_id(),'buy_plan_type' => 'Free'))->num_rows();
		?>
    
  <?php if($plan_types=='Free') {
    if($free_plan_exist == 0){
  ?>
    <div class="check-1">
<div class="radio">
  <label>
    <input type="radio" name="plan" id="optionsRadios1" value="<?php echo $plan_id;?>" class="plan_details">
  <?php }
  }else{ ?>
  <div class="check-1">
<div class="radio">
  <label>
    <input type="radio" name="plan" id="optionsRadios1" value="<?php echo $plan_id;?>" class="plan_details">
  <?php } ?>
    <?php if($plan_types=='Free') {
      
      if($free_plan_exist == 0){
          echo $plan_price.' '.$plan_name; 
        }
      } else { echo $plan_name; }?> <br/>
    <?php if($plan_types=='Free') { ?> 
    <?php if($free_plan_exist == 0){ ?>
    <span class="gt"><?php echo 'Free';?></span>
    <?php } ?>
    <?php } else { 
	$currency_tbl = get_data(TBL_CUR,array('currency_id'=>$currency))->row_array();
	?>
    <span class="gt"><?php echo $currency_tbl['symbol'].$plan_price.' '.$currency_tbl['currency_code'].' / '.$plan_types;?> </span>
    <?php } ?>
     <?php if($plan_types=='Free') {
    if($free_plan_exist == 0){
  ?>
        </label>
      </div>
      </div>
    <?php } }else{?>
      </label>
      </div>
      </div>
    <?php } ?>    
        <?php
	}
}
?>
<input type="hidden" id="current_plan_id" />
</div>
<?php if($user_subscribe_pladn==0){?>
<div class="row display_none" id="enable_button_subscribe">
<div class="col-md-12 col-md-12">
<button type="button" class="btn btn-success col-md-12 select_channels_details">SUBSCRIBE</button>
</div>
</div>
<?php } ?>
</form>
<?php } ?>
<hr>
<center><a href="#">Do you need help? Click here.</a></center>
</div></div>
<div class="col-md-8 col-sm-8">
<?php if(isset($action)=='channel_subscribe') { ?>

<div class="bb1">
<h4>Channel Subscribe</h4>
<hr>
<ul>
<li>Multiple rates & rate types support</li>
<li>Rate management grid allowing you to set up and change daily or monthly rate</li>
<li>Special pricing for holiday, weekend or other short-term event periods</li>
<li>Special pricing for adults and children</li>
<li>Negotiated rates with flexible Commissions/Discounts for travel agents, sources of business etc</li>
<li>Surcharge for extra persons</li>
<li>Group discounts/surcharges</li>
</ul>
</div>

<?php }else{ ?>
<div class="bb1_a">
<h4><strong>Your Membership Plan is:</strong> <span id="show_plan_name"> <?php if($plan_details=='NO') { ?> You have not selected any Membership Plan <?php } 
else
{
	$curr_details 			= get_data(TBL_CUR,array('currency_id'=>$plan_details['buy_plan_currency']))->row()->currency_code;
	
	$buy_plan_details	 	= get_data(TBL_PLAN,array('plan_id'=>$plan_details['buy_plan_id']))->row_array();
	
	if(count($buy_plan_details)!=0)
	{
		echo $buy_plan_details['plan_name'].' ( '.$plan_details['buy_plan_price'].' '.$curr_details.' / '.$plan_details['buy_plan_type'].' ) ';
	}
	$exp_date = explode('-',$plan_details['plan_to']);
}

?>
</span></h4>
<hr>
<?php echo get_data('cms_membership',array('auto_id'=>'1'))->row()->info;?>
<hr>
<?php if($plan_details!='NO') { ?>
<strong class="<?php if($plan_details['plan_status']==2){?>alert-danger<?php } ?>">Membership Plan Expire<?php if($plan_details['plan_status']==2){?>d<?php } else { ?>s<?php } ?> on:</strong> <span class="<?php if($plan_details['plan_status']==2){?>alert-danger<?php } ?>" id="expires_date"> <?php echo $exp_date[2].'/'.$exp_date[1].'/'.$exp_date[0];?> </span>
<?php }  else { ?>
<strong >Membership Plan Expires  on:</strong> <span id="expires_date">	__/__/____ </span>
<?php } ?>

</div>

<?php } if($this->session->flashdata('channel_warning')!=''){?>
<div class="alert alert-info mar-top7"> 
<?php echo '<strong>'.$this->session->flashdata('channel_warning').'</strong>'; ?>
</div>
<?php } ?>
</div>
</div>
<!-- Subscribe End -->

</div>



</div>
</div>
</div>
</div>

<!-- Full Refresh -->
<div class="modal fade dia-2" id="myModal-p2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form method="post" id="select_channels_subscribe" action="<?php echo lang_url();?>inventory/rate_management">
    <div class="modal-content select_channels_subscribe">
    </div>
    </form>
  </div>
</div>
<!-- Full Refresh -->