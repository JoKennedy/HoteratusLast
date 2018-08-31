<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n">
<?php if(isset($action)=='channel_subscribe') { ?>
<h4><a href="<?php echo lang_url();?>channel/all_channels">Channel</a>
    <i class="fa fa-angle-right"></i>
     Channel Subscription
              </h4>
<?php } else { ?>           
<h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>
     Advanced Rates Management
              </h4>   
<?php } ?>
</div>
</div>
</div>
</div>

<br/>

<div class="container">
<div class="row">
<div class="col-md-4 col-sm-4"><div class="bb1 sub-m">
<div class="lik"><center><i class="fa fa-list-alt"></i></center></div>
<hr>
<?php if(isset($action)=='channel_subscribe') { ?>
<form action="<?php echo lang_url();?>channel/subscribe_step/<?php echo $channel_id;?>" method="post">
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

<div class="row">
<div class="col-md-12 col-md-12">
<button type="submit" class="btn btn-success col-md-12">SUBSCRIBE</button>
</div>
</div>

</form>
<?php } else { ?>
<form action="<?php echo lang_url();?>inventory/rate_management" method="post">
<?php 
$subscribe_plans = get_data(TBL_PLAN,array('status'=>1))->result_array();
if(count($subscribe_plans)!=0)
{
	foreach($subscribe_plans as $plan)
	{
		extract($plan);
		?>
    <div class="check-1">
<div class="radio">
  <label>
    <input type="radio" name="plan" id="optionsRadios1" value="<?php echo $plan_id;?>" checked>
    <?php if($plan_types=='Free') {echo $plan_price.' '.$plan_name; } else { echo $plan_name; }?> <br/>
    <?php if($plan_types=='Free') { ?> 
    <span class="gt"><?php echo 'Free';?></span>
    <?php } else { 
	$currency_tbl = get_data(TBL_CUR,array('currency_id'=>$currency))->row_array();
	?>
    <span class="gt"><?php echo $currency_tbl['symbol'].$plan_price.' '.$currency_tbl['currency_code'].' / '.$plan_types;?> </span>
    <?php } ?>
  </label>
</div>
</div>    
        <?php
	}
}
?>

<div class="row">
<div class="col-md-12 col-md-12">
<button type="submit" class="btn btn-success col-md-12">SUBSCRIBE</button>
</div>
</div>

</form>
<?php } ?>
<hr>
<center><a href="#">Do you need help? Click here.</a></center>
</div></div>
<?php if(isset($action)=='channel_subscribe') { ?>
<div class="col-md-8 col-sm-8">
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
</div>
<?php }else{ ?>
<div class="col-md-8 col-sm-8">
<div class="bb1">
<h4>Advanced Rates Management</h4>
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
</div>
<?php } ?>


</div>
</div>

<br/>

