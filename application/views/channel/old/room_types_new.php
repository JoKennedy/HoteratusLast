
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
                <li class="active"> 
                  <a href="#tab_default_1" data-toggle="tab">
                  <i class="fa fa-info fa-lg"></i> Basics
                  </a>
                </li>
                 <li>
                  <a href="#tab_default_2" data-toggle="tab"> <!-- class="acc-mn" -->
                  <i class="fa fa-money fa-lg"></i> Master rate
                  </a>
                </li>

               


                 <li>
                  <a href="#tab_default_3" data-toggle="tab">
                  <i class="fa fa-sitemap fa-lg"></i> Rate types
                  </a>
                  </li>

                 <li>
                  <a href="#tab_default_4" data-toggle="tab">
                  <i class="fa fa-th-large fa-lg"></i> Amenities
                  </a>
                </li>
                  <li>
                  <a href="#tab_default_5" data-toggle="tab">
                  <i class="fa fa-globe fa-lg"></i> Channels
                  </a>
                </li>
                  <li>
                  <a href="#tab_default_6" data-toggle="tab">
                  <i class="fa fa-picture-o fa-lg"></i> Photos
                  </a>
                </li>
            </ul>
     <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>  
        
      <p class="few2 text-center"><a href="<?php echo lang_url();?>channel/manage_rooms/delete/<?php echo insep_encode($property_id);?>" class="fa-trash-o"><i class="fa fa-trash-o text-danger"></i> Delete</a></p>
            
</div></div></div></div>            
            
            
            
     </div>
</div>
</div>



</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">

<div class="bg-neww">
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>channel/manage_rooms">My Property</a>
    <i class="fa fa-angle-right"></i>
    <?php echo $property_name?> <!--<i class="fa fa-angle-right"></i> Connected Channels-->
</h4></div>
<div class="tab-content">

<!--Room Basics Start-->

<div class="tab-pane active" id="tab_default_1"><!-- tab1 -->


<div class="box-m2">
<div class="row">
<div class="col-md-9 col-sm-9">

<form action="<?php echo lang_url();?>inventory/update_basic_rooms" method="post" id="room_form_<?php echo $property_id?>">
<input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Name</span></div>
<div class="col-md-8 col-sm-8 ssw vi1">
  <div class="form-group">
    <input type="text" value="<?php echo ucfirst($property_name);?>" class="form-control widh" name="property_name_<?php echo $property_id?>" id="property_name_<?php echo $property_id?>">
  </div>
</div>
</div>

<!--<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Allotment</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"> 
  <div class="form-group">
    <input type="number" value="<?php //echo $allotment;?>" step="any" class="form-control widh" name="allotment" id="allotment">
  </div>
  </div>
</div>-->

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Room capacity</span></div>
<div class="col-md-8 col-sm-8 ssw vi1">
  <div class="form-group">
    <input type="number" value="<?php echo $member_count;?>" step="any" class="form-control widh" name="existing_room_count" id="existing_room_counts" min="1">
  </div>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Adult capacity</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"> 
  <div class="form-group">
    <input type="number" value="<?php echo $member_count;?>" step="any" class="form-control widh" name="member_count" id="add_member_counts"  min="1">
  </div>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Meal plan</span></div>
<div class="col-md-8 col-sm-8">
<select class="form-control" name="meal_plan" id="meal_plan">
<?php 
	$meal_plans = get_data(MEAL,array('meal_status'=>1))->result_array();
	foreach($meal_plans as $meal)
	{ 
		extract($meal);
?>
  <option value="<?php echo $meal_id;?>" <?php if($meal_id==$meal_plan) { ?> selected="selected"<?php  } ?>><?php echo $meal_name;?></option>
  <?php } ?>
</select>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Description</span></div>
<div class="col-md-8 col-sm-8">
<textarea id="description" name="description" class="ckeditor"><?php echo $description;?></textarea>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4">
<div class="s1 button ss">
  <input type="submit" value="Submit" class="btn btn-primary"></div> 
</div>
<div class="col-md-4 col-sm-4">
<div class="j1"><a href="javascript:;" class="pull-right" data-toggle="modal" data-target="#myModal-tab-1" data-backdrop="static" data-keyboard="false">More options</a></div>
</div>
</div>

</form>  
  
</div>

<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2" data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>

</div>





</div>               
</div>

<!--Room Basics End-->

<!--Room Master rate start-->

<div class="tab-pane" id="tab_default_2">

<div class="box-m2">
<div class="row">
<div class="col-md-9 col-sm-9">

 <form action="<?php echo lang_url();?>inventory/master_rate_update" method="post" id="master_rate_form">
<input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />
<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Pricing type</span></div>
<div class="col-md-8 col-sm-8">
<select name="pricing_type" id="pricing_types" class="form-control" >
	  
      <option value="1" <?php if($pricing_type==1) { ?> selected="selected" <?php } ?>>Room based pricing</option>
      <option value="2" <?php if($pricing_type==2) { ?> selected="selected" <?php } ?>>Guest based pricing</option>

      </select>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Non refundable</span></div>
<div class="col-md-8 col-sm-8">
<div class="checkbox">
  <label>
    <input type="checkbox" value="1" <?php if($non_refund=='1'){?> checked="checked"<?php } ?> name="non_r" id="add_non_rs" class="non_r" />
  </label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Base price / Per night</span></div>
<div class="col-md-8 col-sm-8">

  <div class="form-group">
    <label for="exampleInputAmount" class="sr-only"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></label>
    <div class="input-group in-p">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" class="form-control" placeholder="Price" id="add_price" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="<?php echo $price;?>">
    </div>
  </div>

</div>
</div>
<br/>

<div class="table table-responsive ma-k guest_baseds <?php if($pricing_type==1) { ?> display_none <?php } ?>">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th style="width:25px;">Number of guests</th>
        <th>Refundable</th>
        <th class="non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">Non refundable</th>
        </tr>
        </thead>
        <tbody class="data">
        <?php 
		$rate_details = get_data(RATE,array('room_id'=>$property_id))->result_array();
		if(count($rate_details)==0)
		{
			for($i=1;$i<=$member_count;$i++)
			{
			
		?>
        <tr id="item<?php echo $i;?>">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_<?php echo $i;?>[]" id="method_<?php echo $i;?>">
		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="type_<?php echo $i;?>[]" id="type_<?php echo $i;?>">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>[]" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
		</div>
        </div>
	    <input type="hidden" value="<?php echo $price;?>" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>"><?php echo $price;?></p></div>
        </td>
        <td class="pa-t-pa-b non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_<?php echo $i;?>[]" id="n_method_<?php echo $i;?>">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="n_type_<?php echo $i;?>[]" id="n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>[]" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
   		</div>
        </div>
  		<input type="hidden" value="<?php echo $price;?>" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php echo $price;?></p></div>
        </td>
        </tr>
        
        </tr>
        <?php
		 	}
		}
		else 
		{
			$i=1;
			foreach($rate_details as $rates)
			{
				extract($rates);
				$i++;
			?>
            <tr id="item<?php echo $i;?>">
            <tr>
            <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
            <td class="pa-t-pa-b">
            <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="method_<?php echo $i;?>[]" id="method_<?php echo $i;?>">
            <option value="+" <?php if($method=='+') { ?> selected="selected" <?php } ?>> + </option>
            <option value="-" <?php if($method=='-') { ?> selected="selected" <?php } ?>> - </option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="type_<?php echo $i;?>[]" id="type_<?php echo $i;?>">
            <option value="Rs" <?php if($type=="Rs") { ?> selected="selected"<?php } ?>> Rs </option>
            <option value="%" <?php if($type=="%") { ?> selected="selected"<?php } ?>> %</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3">
            <div class="ssw ki"> 
            <input type="text" value="<?php echo $dis_amount;?>" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>[]" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
            </div>
            </div>
            <input type="hidden" value="<?php echo $total_amount;?>" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>[]" class="tkk"/>
            <div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>"><?php echo $total_amount;?></p></div>
            </td>
            <td class="pa-t-pa-b non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">
            <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="n_method_<?php echo $i;?>[]" id="n_method_<?php echo $i;?>">
            <option value="+" <?php if($n_method=='+') { ?> selected="selected" <?php } ?>>+</option>
            <option value="-" <?php if($n_method=='-') { ?> selected="selected" <?php } ?>>-</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="n_type_<?php echo $i;?>[]" id="n_type_<?php echo $i;?>">
              <option value="Rs" <?php if($n_type=="Rs") { ?> selected="selected"<?php } ?>>Rs</option>
              <option value="%" <?php if($n_type=="%") { ?> selected="selected"<?php } ?>>%</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3">
            <div class="ssw ki"> 
            <input type="text" value="<?php echo $n_dis_amount?>" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>[]" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
            </div>
            </div>
            <input type="hidden" value="<?php echo $d_total_amount;?>" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>[]" class="tkk"/>
            <div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php echo $d_total_amount;?></p></div>
            </td>
            </tr>
        
        	</tr>
            <?php
			}
		}
		?>
        </tbody>
        </table>
</div>

<div class="table table-responsive room_baseds ma-k <?php if($pricing_type==2 || $non_refund==0) { ?> display_none <?php } ?>">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th class="room_baseds text-center" colspan="2">Non refundable</th>
        </tr>
        </thead>
        <tbody class="datas">
        <?php 
		if(count($rate_details)!=0)
		{
			?>
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b room_baseds">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1[]" id="n_method_1">
  		<option value="+"  <?php if($n_method=='+') { ?> selected="selected" <?php } ?>>+</option>
		<option value="-"  <?php if($n_method=='-') { ?> selected="selected" <?php } ?>>-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="r_n_type_1[]" id="n_type_1">
  		<option value="Rs" <?php if($n_type=="Rs") { ?> selected="selected"<?php } ?>>Rs</option>
 		<option value="%" <?php if($n_type=="%") { ?> selected="selected"<?php } ?>>%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="<?php echo $n_dis_amount?>" class="form-control widh cal_amt" name="r_n_d_amt_1[]" id="n_amt_1" custom="1" method="n_refun">
	    </div>
        </div>
  		<input type="hidden" value="<?php echo $d_total_amount;?>" id="n_h_total_1" name="r_n_h_total_1[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_1"><?php echo $d_total_amount;?></p></div>
        </td>
        </tr>
        </tr>
        <?php } else { ?>
        <tr id="item0">
        <tr>
        
        <td class="pa-t-pa-b room_baseds">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_<?php echo $i;?>[]" id="n_method_<?php echo $i;?>">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="r_n_type_<?php echo $i;?>[]" id="n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="0.00" class="form-control widh cal_amt" name="r_n_d_amt_<?php echo $i;?>[]" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
   		</div>
        </div>
  		<input type="hidden" value="<?php echo $price;?>" id="n_h_total_<?php echo $i;?>" name="r_n_h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php echo $price;?></p></div>
        </td>
        
        </tr>
        </tr>
        <?php 
		}
		?>
        </tbody>
        </table>

</div>

<div class="row">
<div class="col-md-4 col-sm-4">
<div class="s1 button ss">
  <input type="submit" value="Save" class="btn btn-primary"></div> 
</div>
</div>
  
  </form>
</div>

<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2" data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>

</div>





</div>

</div>

<!--Room Master rate end-->

<!-- Rate Types Start --> 

<div class="tab-pane" id="tab_default_3">


<div class="box-m2">
<div class="row">
<?php 
$count_rate_types = $this->inventory_model->count_rate_types(insep_encode($property_id));
if($count_rate_types==0) { 
?>
<div class="col-md-9 col-sm-9">


<div class="bb1">
<br>
<div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
<h2 class="text-center">You don't have any rate types yet</h2>
<p class="pad-top-20 text-center">You can apply your different pricing strategies and increase your sales by using rate types</p>
<br>
<div class="res-p"><center>
<?php if($subscribe_status==0) { ?>
<a class="btn btn-primary" href="<?php echo lang_url();?>inventory/rate_management"><i class="fa fa-plus"></i>  Add rate type</a>
<?php } else { ?>
<a class="btn btn-primary" href="javascript:;" data-target="#add_rate" data-toggle="modal"><i class="fa fa-plus"></i>  Add rate type</a>
<?php } ?>
</center></div>
</div>
</div>
<?php } else { 
$rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id))->result_array();

?>
<div class="col-md-9 col-sm-9" id="original_container" style="display: block;">
      <!-- orders table -->
      <div class="col-lg-12">
        <div class="table-wrapper orders-table section">
          <!-- table and controller -->
          <div>
            <div class="row filter-block">

              <div class="pull-right">
                <a id="new-rate"  class="btn btn-success" href="javascript:;" data-target="#add_rate" data-toggle="modal"><i class="fa fa-plus"></i> Add rate type</a>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="width-form-full">
            </div>
            <div class="row">
              <table class="table table-hover" id="original-container">
                <thead>

                <tr>
                  <th class="col-md-4">
                    Name
                  </th>
                  <th class="col-md-4">
                    <span class="line"></span>
                    Meal plan
                  </th>
                  <th class="col-md-4">
                    <span class="line"></span>
                    Eligible
                  </th>
                </tr>

                </thead>
                <tbody id="table-body">
                <?php 
				$i=1;
				foreach($rate_types as $rate) { extract($rate); $i++; ?>
                <tr class="item" id="hr_variant_rate_58768" style="">
                
  					<td class="handle"><a data-remote="true" href="<?php echo lang_url();?>inventory/manage_rate_types/edit/<?php echo $uniq_id;?>"><?php echo $rate_name?></a>
                      <br>
                      <span class="subtext"><?php if($pricing_type=='1'){?>Room based pricing<?php } else { ?>Guest based pricing <?php } ?></span>
                      <span class="hide">2</span>
                      <span class="hide">2</span>
                    </td>
                    <td class="handle"><?php echo get_data(MEAL,array('meal_id'=>$meal_plan))->row()->meal_name;?></td>
                    <td class="handle">Yes</td>	
				</tr>
                <?php } ?>
                <!-- row -->
                </tbody>
              </table>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <!-- end orders table -->
      </div>
    </div>
<?php } ?>
<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2" data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>
</div>
</div>
</div>
<!-- Rate Types End --> 

<!-- amenities start  -->

<div class="tab-pane" id="tab_default_4">

<div class="box-m2">
<div class="row">
<div class="col-md-9 col-sm-9">
<?php $get_ammenties = explode(',',$amenities);?>
<form method="post" id="update_amenities">
<input type="hidden" value="<?php echo $property_id?>" name="property_id"/>
<?php $am_types = get_data(AMENITIES_TYPE)->result_array();
foreach($am_types as $amm){
	extract($amm);
	/*echo 'count	'.*///$amm_count = $this->inventory_model->getallexist_subcatcntbymaincat($id);
	//$end_catlimit=$amm_count-8; 
	//$start_catlimit=8;
?>
<div class="bb1">
<h4><?php echo $amenities_type;?></h4>
<div class="row">
<?php
$amm = get_data(AMENITIES,array('type_id'=>$id),'','8')->result_array();
$even = true;
	$t=0; 
	foreach($amm as $value)
	{
		extract($value);
		if($t%4==0)
		{
?>
<div class="col-md-6 col-sm-6">
<?php } ?>

<div class="checkbox">
  <label>
    <input type="checkbox" value="<?php echo $amenities_id?>" <?php if(in_array($amenities_id,$get_ammenties)){?> checked="checked"<?php } ?> class="update_amenities" name="update_amenities[]">
    <?php $length = strlen($amenities_name); if($length!='30') { echo ucfirst($amenities_name);}else{ ?>
    <abbr title="<?php echo ucfirst($amenities_name);?>"><?php substr(0,30,ucfirst($amenities_name));?>...</abbr>
    <?php } ?>
  </label>
</div>
<?php $t++; if($t%4==0){
	 $even = !$even; 
 if($even) { ?> <?php } else { ?>
<a href="javascript:;" data-toggle="modal" data-target="#myModal_<?php echo $id?>" data-keyboard="false" data-backdrop="static">More</a>

<?php } ?>
</div>
<?php  } } ?>
</div>
</div>
<br/>
<?php } ?>
</form>
</div>

<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2" data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>

</div>

</div>



</div>

<!-- amenities end  -->

<!-- connected_channel end  -->

<div class="tab-pane" id="tab_default_5"><!-- tab5 -->

<div class="box-m2">
<div class="row">
<div class="col-md-9 col-sm-9">


<?php if($connected_channel=='') { ?>
<div class="bb1">
<br>
<div class="reser"><center><i class="fa fa-globe"></i></center></div>
<h2 class="text-center">You don't have any online channels configured for this room type yet</h2>
<p class="pad-top-20 text-center">You can sell your rooms from hundreds of online sales channels</p>
<br>
<div class="res-p"><center><a class="btn btn-primary" href="<?php echo lang_url();?>channel/all_channels"><i class="fa fa-plus"></i>  Add Channel</a></center></div>
</div>
<?php }  else { 
$connected = $this->inventory_model->connected_channel($connected_channel);
foreach($connected as $connect)
{
	extract($connect);
	
?>

<div class="col-md-3 col-sm-3">

<a class="sser" href="javascript:;"><div class="ss2">
<img class="img img-responsive" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>">
<h4 class="text-center"><i class="fa fa-link"></i> <?php echo $channel_name;?></h4></div>
</a>
</div>

<?php } } ?>





  
  
</div>

<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2" data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>

</div>





</div>


</div>

<!-- connected_channel end  -->

<!-- photos start -->

<div class="tab-pane" id="tab_default_6">

<div class="box-m2">
<div class="row">
<div class="col-md-9 col-sm-9">



<div class="bb1">


<?php $hotel_photos = get_data(TBL_PHOTO,array('room_id'=>$property_id))->row_array();?>
<div class="row">
<div class="col-md-4 col-sm-4"><?php // echo count($hotel_photos);?> <!--photo--></div>

<div class="col-md-8 col-sm-8 pull-right">
<form method="post" id="upload_photos" enctype="multipart/form-data" action="<?php echo lang_url();?>inventory/manage_photos/new">
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo insep_encode($property_id);?>"/>
<div class="col-md-7 col-sm-2">
<input type="file" id="immm" class="immm" reqired name="hotel_image[]" multiple  accept="image/png,image/gif,image/jpeg">
<!--<div class="validation" style="display:none;"> Upload Max 4 Files allowed </div>-->
</div>
<div class="col-md-4 col-sm-4 col-md-offset-1 col-sm-offset-1"><input class="btn btn-success" type="submit" value="Upload" id="uploadButton" disabled="disabled"></div>
</form> <!--<div class="pull-right"><a href="#" id="open_btn" class="btn btn-success">Add Photo</a></div>--></div></div>

</div>

<br/>
<div class="new-galery" id="img_rplae">
<ul class="list-unstyled row">
<?php 

if(count($hotel_photos)!=0) { $photos = explode(',',$hotel_photos['photo_names']); 
foreach($photos as $val)
{
?>
<li class="col-md-3 col-sm-3">
<div class="box-y">
<a class="fancybox" href="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" data-fancybox-group="gallery">
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" alt="" class="img img-responsive img-thumbnail" /></a>
<div class="overbox-y">

	    <!--<div class="title overtext pull-left"> <a href="#" data-toggle="modal" data-target="#myModa-f1"><span class="ne-pk"><i class="fa fa-edit"></i></span></a> </div>-->
  <div class="title overtext text-center pull-right"> <a href="javascript:;"  class="delete_photo" data-id="<?php echo insep_encode($hotel_photos['photo_id']);?>" custom="<?php echo insep_encode($val);?>"><span class="ne-pk"><i class="fa fa-close"></i></span></a> </div>
	  </div>

</div></li>
<?php } } else { ?>
<div class="alert alert-danger text-center"> No photos data found!!!</div>
<?php } ?>


</ul>
</div>




  
  
</div>

<div class="col-md-3 col-sm-3">
<?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>
<div class="rig-box-l">
<p class="bor-b">This room's content score
<a href="javascript:;" class="pull-right"><i class="fa fa-refresh"></i></a></p>

<div class="myStathalf2"  data-dimension="200" data-text="<?php echo $room_rate;?>%" data-info="New Clients" data-width="10" data-fontsize="21" data-percent="<?php echo $room_rate;?>" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-icon="fa-task"></div>
<div class="pa-j"><span>0</span> <span class="pull-right">100</span></div>
<br/>
<ul class="list-unstyled few">
<?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> <?php echo $roo;?></a></li>
<?php } } ?>
<!--<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter description</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter policy</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bedrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter the number of bathrooms</a></li>
<li><a href="javascript:;"><i class="fa fa-genderless"></i> Enter area</a></li>-->
</ul>


</div>

<div class="bu">
<div class="pull-left"><i class="fa fa-lightbulb-o"></i></div> <p>Entering correct room type information is crucial to accurate reservation management and increasing online sales.</p>
</div>


</div>

</div>





</div>





</div>

<!-- photos end -->

</div>
              
              </div>
              
             
              
              </div>
              </div>
</div>

<!--<div id="suggestOnClick"></div>-->

<?php
$am_typess = get_data(AMENITIES_TYPE)->result_array();
foreach($am_typess as $amms){
	extract($amms);
	$amm_count = $this->inventory_model->getallexist_subcatcntbymaincat($id);
	$end_catlimit=$amm_count-8; 
	$start_catlimit=8;
?>
<div class="modal fade dialog-2" id="myModal_<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel"><?php echo $amenities_type;?></h4>
      </div>
      <form method="post" id="update_amenities_modal">
     <!-- <input type="hidden" value="<?php //echo $property_id?>" name="property_id"/>-->
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <?php 
       	$sub_count  = $this->inventory_model->getallexist_subcatcntallcat($id,$start_catlimit,$end_catlimit);
		$values =  count($sub_count);
		$a=0;
		$j=0;
		foreach($sub_count as $val)
		{
			$j++;
			extract($val);
		if($a%4==0)
		{
		?>
       <div class="col-md-6 col-sm-6">
       <?php } ?>
<div class="checkbox">
  <label>
    <input type="checkbox" value="<?php echo $amenities_id?>" <?php if(in_array($amenities_id,$get_ammenties)){?> checked="checked"<?php } ?>name="update_amenities[]" class="update_amenities_modal">
    <?php echo $amenities_name;?>  
  </label>
</div>
<?php $a++; if($a%4==0){ ?>
</div>
<?php } else { if($j==$values){?> </div> <?php } } }  ?>
 </div>
       </div>
       </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="button" data-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<div class="modal fade" id="myModal-tab-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">More options </h4>
      </div>
      <form action="<?php echo lang_url();?>inventory/update_more_options" method="post" id="more_options">
      <input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />
      
      <div class="modal-body">
      <div class="calen-2 paf">
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Selling period :</span></div>
        <div class="col-md-8 col-sm-8">
        <?php $selling = array('Daily','Weekly','Monthly');?>
        <select name="selling_period" id="selling_period" class="form-control" >
        <?php 
        $i=1;
        foreach($selling as $sell=>$key) { ?>
              <option value="<?php echo $i++;?>"><?php echo $key;?></option>
        <?php } ?>
              </select>
        </div>
        </div>
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Open to direct channels :</span></div>
        <div class="col-md-8 col-sm-8">
        <div class="checkbox">
          <label>
            <input type="checkbox" value="1" <?php if($droc==1) { ?> checked="checked" <?php } ?> name="droc" id="droc">
          </label>
        </div>
        </div>
        </div>
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Children Capacity :</span></div>
        <div class="col-md-8 col-sm-8 ssw vi1">
          <div class="form-group">
            <input type="number" value="<?php echo $children;?>" step="any" class="form-control widh" name="children" id="children">
          </div>
        </div>
        </div>
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Number of bedrooms:</span></div>
        <div class="col-md-8 col-sm-8 ssw vi1">
          <div class="form-group">
            <input type="text" value="<?php echo $number_of_bedrooms;?>" class="form-control widh" name="number_of_bedrooms" id="number_of_bedrooms">
          </div>
        </div>
        </div>
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Number of bathrooms :</span></div>
        <div class="col-md-8 col-sm-8 ssw vi1">
          <div class="form-group">
            <input type="text" value="<?php echo $number_of_bathrooms;?>" class="form-control widh"  name="number_of_bathrooms" id="number_of_bathrooms">
          </div>
        </div>
        </div>
        
        <div class="row">
        <div class="col-md-4 col-sm-4"><span class="ne-k">Area (m²) :</span></div>
        <div class="col-md-8 col-sm-8 ssw vi1">
          <div class="form-group">
            <input type="number" value="<?php echo $area;?>" class="form-control widh" name="area" id="area" min="1">
          </div>
        </div>
        </div>
        
        </div>
	  </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      
      </form>
      
    </div>
  </div>
</div>

<div class="modal fade dialog-3 " id="add_rate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add rate type</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="rate_type" id="rate_type" method="post" action="<?php echo lang_url();?>inventory/manage_rate_types/add" enctype="multipart/form-data">
      <input type="hidden" name="property_id" id="property_id" value="<?php echo $property_id;?>" />
      
      <input type="hidden" name="member_count" id="member_count" value="<?php echo $member_count;?>" />
      
      <div class="box-dialog row-pad-top-20">
 
  	  
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Name <span class="errors"> </span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="text" placeholder="Room Name" id="property_name" name="property_name" class="form-control"></div>
 </div>
 
 	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Pricing Type <span class="errors"> </span></span></div>
 <div class="col-md-8 col-sm-8"><select name="pricing_type" id="pricing_type" class="form-control" >
	  
      <option value="1" selected="selected">Room based pricing</option>
      <option value="2" >Guest based pricing</option>

      </select></div>
 </div>
 	  
	  <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Display room on calendar <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="radio" value="1" checked="checked" name="droc" id="droc"/> Yes <input type="radio" value="2" name="droc" id="droc1"/> No</div>
	  </div>
      
      <div class="row">
 
 	  <div class="col-md-3 col-sm-3"><span class="ne-k">Meal plan</span></div>
      
      <div class="col-md-8 col-sm-8">
        <select class="form-control" name="meal_plan" id="meal_plan">
        <?php 
            $meal_plans = get_data(MEAL,array('meal_status'=>1))->result_array();
            foreach($meal_plans as $meal)
            { 
                extract($meal);
        ?>
          <option value="<?php echo $meal_id;?>"><?php echo $meal_name;?></option>
          <?php } ?>
        </select>
        </div>
        
	  </div>
 
	  <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Non Refundable <span class="errors"> </span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="checkbox" value="1" name="non_r" id="add_non_r" class="non_r" /></div>
	  </div>
      
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Base price / Per night<span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" class="form-control" placeholder="Price" id="add_price" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="100.00">
    </div></div>
	  </div>
      
      <input type="hidden" name="base_price" id="base_price" value="100.00"/>
      
  	  </div>
      
  	  <div class="col-md-12 guest_based">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th style="width:25px;">Number of guests</th>
        <th>Refundable</th>
        <th class="non_refund">Non refundable</th>
        </tr>
        </thead>
        <tbody class="data">
        <?php 
		for($i=1;$i<=$member_count;$i++)
		{
		?>
	    <tr id="item<?php echo $i;?>">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_<?php echo $i;?>[]" id="method_<?php echo $i;?>">
		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="type_<?php echo $i;?>[]" id="type_<?php echo $i;?>">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>[]" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
		</div>
        </div>
	    <input type="hidden" value="100.00" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>">100.00</p></div>
        </td>
        <td class="pa-t-pa-b non_refund  display_none ">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_<?php echo $i;?>[]" id="n_method_<?php echo $i;?>">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="n_type_<?php echo $i;?>[]" id="n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>[]" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
   		</div>
        </div>
  		<input type="hidden" value="100.00" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>">100.00</p></div>
        </td>
        </tr>
        
        </tr>
        <?php 
		} 
		?>
        </tbody>
        </table>
        </div>
  </div>
  
	  <div class="col-md-12 room_based">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th class="room_based text-center" colspan="2">Non refundable</th>
        </tr>
        </thead>
        <tbody class="datas">
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b room_based">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1[]" id="n_method_1">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="r_n_type_1[]" id="n_type_1">
  		<option value="Rs">Rs</option>
 		<option selected value="%">%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="r_n_d_amt_1[]" id="n_amt_1" custom="1" method="n_refun">
	    </div>
        </div>
  		<input type="hidden" value="100.00" id="n_h_total_1" name="r_n_h_total_1[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_1">100.00</p></div>
        </td>
        </tr>
        </tr>
        </tbody>
        </table>
        </div>
  </div>
     
      <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Create</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
     </form>
     </div>
   
  
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade dialog-3 " id="edit_rate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" id="document">
  
  </div>
</div>