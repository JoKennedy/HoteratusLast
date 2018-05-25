<style type="text/css">
  .few2 {
    margin-left: 27px;
}

</style>
<script type="text/javascript">
function get_number(){
        var number = document.getElementsByName("room_number");
        var numbers = "";
        for(var i = 0; i < number.length; i++){
            numbers += ","+number[i].value;
        }
        numbers = numbers.substr(1);
        $("input[name=existing_room_number]").val(numbers);
    }
</script>
 <div class="container-fluid pad_adjust  mar-top-30 cls_mapsetng">
  <div class=" mar-bot30">
  <div class="cls_pieside">
  <?php 

if($number_of_bedrooms==0){$r2=10; $a1=array('Enter the number of bedrooms');}else{$r2=0; $a1=array();} 

if($number_of_bathrooms==0){$r3=10; $a2=array('Enter the number of bathrooms');}else{$r3=0; $a2=array();}

if($area==0 || $area==''){$r4=10; $a3=array('Enter area');}else{$r4=0; $a3=array();}

if($amenities==''){$r5=20; $a4=array('Select amenities');}else{$r5=0; $a4=array();} 

if($count_room_photos==0){$r6=20; $a5=array('Upload photos');}else{$r6=0; $a5=array();}

$room_rate = 100 - ($r2+$r3+$r4+$r5+$r6);

$roo_array = array_merge($a1,$a2,$a3,$a4,$a5);

?>

            <h4>This room's content score</h4>
            <div class="pie-cnt">
              <div class="pie-wrapper">
                <div class="arc" data-value="50"></div>
                <span class="score"><?php echo $room_rate;?></span> </div>
              <p class="clearfix"> <span class="label-text pull-left">0</span> <span class="label-text pull-right">100</span> </p>
            </div>
            <ul class="multi-list">
            <?php if(count($roo_array)!=0){ foreach($roo_array as $roo) {?> 
              <li>
              <?php 
              if($roo=='Enter the number of bedrooms' || $roo=='Enter the number of bathrooms' || $roo=='Enter area') { ?>
              <a href="javascript:;" data-keyboard="false" data-backdrop="static" data-target="#myModal-tab-1" data-toggle="modal"> <?php echo $roo;?></a>
              <?php }
              elseif($roo=='Select amenities'){  ?>
              <a href="#tab6" data-toggle="tab"><i class="fa fa-genderless"></i> <?php echo $roo;?></a>
              <?php } else if($roo=='Upload photos'){?>
              <a href="#tab8" data-toggle="tab"><i class="fa fa-genderless"></i> <?php echo $roo;?></a>
              <?php } ?>
              </li>
            <?php } } ?>
            </ul>
          </div>

           <?php if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>  
      <p class="few2 text-center pull-left"><a href="<?php echo lang_url();?>channel/manage_rooms/delete/<?php echo insep_encode($property_id);?>" class="fa-trash-o"><i class="fa fa-trash-o text-danger"></i> Delete</a></p>
    <?php } ?>


      <div class="col-md-12 col-sm-4 col-xs-12 cls_resp50">
        <div class="cls_side_bar">
          <ul class="list-unstyled clearfix cls_menu_side">
            <li class="active"><a href="#tab1" data-toggle="tab" role="tab">  Basic </a> </li>
            <li><a href="#tab2" data-toggle="tab" role="tab">  Rooms # </a> </li>
            <li><a href="#tab3" data-toggle="tab" role="tab">  Extras </a> </li>
            <li><a href="#tab4" data-toggle="tab" role="tab">  Master Rate </a></li>
            <li><a href="#tab5" data-toggle="tab" role="tab">  Rate type </a> </li>
            <li><a href="#tab6" data-toggle="tab" role="tab">  Amentities </a> </li>
            <li><a href="#tab7" data-toggle="tab" role="tab">  Channel </a> </li>
            <li><a href="#tab8" data-toggle="tab" role="tab"> Photos </a> </li>
          </ul>
          
        </div>
      </div>
       <div class="col-md-12 col-sm-8 col-xs-12 cls_resp50 cls_triple">
        <div class="row cls_filterblk clearfix">
        <div class="col-md-7 col-sm-9 col-xs-12 cls_sort_in pull-right">
        <label class="cls_datalabel col-sm-5 col-xs-12 cls_resp35 text-right">Choose Room</label>
        <div class="col-sm-7 col-xs-12 cls_resp65 pad-no">
        <div class="select-style1">
        <select id="room_types" name="room_types"  onchange="return room_types(this.value);">

        <?php $room_name = $this->channel_model->get_room_name();

        if($room_name){

        foreach($room_name as $res){ ?>

        <option value="<?php echo insep_encode($res->property_id); ?>" <?php if($res->property_id==$property_id){ echo 'selected="selected"';} ?>><?php echo $res->property_name; ?></option>

        <?php } } ?>

        </select>
        </div>
        </div>
        </div>
        </div>

   

   

<!-- tab-content cls-exchng-tab-cont -->
<div class="tab-content cls-exchng-tab-cont">

<!--Room Basics Start-->

<div id="tab1" class="tab-pane fade in active w3-animate-right" role="tabpanel">            
        <div class="row">
        <div class="col-sm-12 col-xs-12">
        <div class="cls_comfrmblk clearfix">

        <form action="<?php echo lang_url();?>inventory/update_basic_rooms" method="post" id="room_form_<?php echo $property_id?>">

        <input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />

        <div class="row">
        <div class="form-group clearfix">
        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">Name</label>
        <div class="col-sm-12 col-xs-12 pad-no">
        <input type="text"  value="<?php echo ucfirst($property_name);?>" class="form-control txtbox2" name="property_name_<?php echo $property_id?>" id="property_name_<?php echo $property_id?>">

        </div>
        </div>

        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">No.of Rooms</label>
        <div class="col-sm-12 col-xs-12 pad-no">

        <input type="number" value="<?php echo $existing_room_count;?>" step="any" class="form-control txtbox2" name="existing_room_count" id="existing_room_counts" min="1">
        </div>
        </div>
        </div>

        <div class="form-group clearfix">
        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">Room Capacity</label>
        <div class="col-sm-12 col-xs-12 pad-no">
        <input type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');
        " value="<?php echo $room_capacity;?>" step="any" class="form-control txtbox2" name="room_capacity" id="room_capacity" min="1">

        </div>
        </div>

        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">Adult capacity</label>
        <div class="col-sm-12 col-xs-12 pad-no">
         <input type="number" value="<?php echo $member_count;?>" step="any" class="form-control txtbox2" name="member_count" id="add_member_counts"  min="1">
        </div>
        </div>
        </div>

        <div class="form-group clearfix">
        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">Description</label>
        <div class="col-sm-12 col-xs-12 pad-no">              
        <textarea id="description" name="description" class="ckeditor form-control txtbox2"><?php echo $description;?></textarea>
        </div>
        </div>
        
        <div class="col-sm-6 col-xs-12 cls_resp50">
        <label class="cls_datalabel">Meal plan</label>
        <div class="col-sm-12 col-xs-12 pad-no">
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

        </div>                      
        </div>
        <div class="col-sm-12 col-xs-12 form-group clearfix">
        <?php if(user_type()=='2')
        {
        if(in_array('4',user_edit()))
        {
        ?>
        <input type="submit" value="Submit" class="cls_com_blubtn pull-right">
        <?php  
        }
        }
        else if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
        ?>
        <input type="submit" value="Submit" class="cls_com_blubtn pull-right">
        <?php } ?>   

        <div class="col-md-4 col-sm-4">
        <div class="j1"><a href="javascript:;" class="pull-right" data-toggle="modal" data-target="#myModal-tab-1" data-backdrop="static" data-keyboard="false">More options</a></div>
        </div>                   
        </div>
        </form>
        </div>
        </div>
        </div>
        </div>

<!--Room Basics End-->

<!-- Room Number Start -->
<div div id="tab2" class="tab-pane fade w3-animate-right" role="tabpanel">
    <legend>Rooms Numbers</legend>
    <div class="col-sm-8 col-sm-offset-2">
        <form action="<?php echo lang_url();?>inventory/update_numbers_rooms" method="post">
            <input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />
            <?php
                $numbers = explode(",", $existing_room_number);
                $count = intval($existing_room_count);
                if($count > 0){
                    for($i=0;$i<$count;$i++) {
                        $number = array_shift($numbers);
                        echo '<div class="col-sm-2" style="margin-top:3px;">';
                            echo '<input type="text" onchange="get_number()" value="'.$number.'" step="any" class="form-control txtbox2" name="room_number" min="1">';
                        echo '</div>';
                    }
                }else{
                    echo "There aren't existing room";
                }
           ?>
            <input type="hidden" name="existing_room_number" value="<?= $existing_room_number; ?>">
            <div class="col-sm-12 col-xs-12 form-group clearfix">
            <?php if(user_type()=='2')
            {
            if(in_array('4',user_edit()))
            {
            ?>
            <input type="submit" value="Submit" class="cls_com_blubtn pull-right">
            <?php  
            }
            }
            else if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){
            ?>
            <input type="submit" value="Submit" class="cls_com_blubtn pull-right">
            <?php } ?>                   
            </div>
        </form>        
    </div>

</div>
<!-- Room Number End -->

<!-- Room Extras Start -->
<div id="tab3" class="tab-pane fade w3-animate-right" role="tabpanel">  
    <h3>In Development</h3>
</div>
<!-- Room Extras End -->

<!--Room Master rate start-->
<div id="tab4" class="tab-pane fade w3-animate-right" role="tabpanel">            
    <div class="row">
    <div class="col-sm-12 col-xs-12">
    <div class="cls_comfrmblk clearfix">
    <form action="<?php echo lang_url();?>inventory/master_rate_update" method="post" id="master_rate_form">

    <input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />

    <div class="row form-group clearfix">
    <div class="col-sm-6 col-xs-12 cls_resp50">
    <label class="cls_datalabel">Pricing type</label>
    <div class="col-sm-12 col-xs-12 pad-no">
    
    Room based pricing

    <input type="hidden" name="pricing_type" value="1">
    
    </div>
    </div>
    <div class="col-sm-6 col-xs-12 cls_resp50">
    <label class="cls_datalabel">Base price / Per night</label>
    <div class="col-sm-12 col-xs-12 pad-no">
    <div class="input-group cls_prigrp">   

     <input type="text" aria-describedby="basic-addon2" class="form-control" placeholder="Price" id="add_price" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="<?php echo $price;?>">
    <span class="input-group-addon" id="basic-addon2"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></span> </div>
    </div>


    </div>

    <div class="col-sm-12 col-xs-12 pad-no">
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
    //echo $member_count;
    /*echo '<pre>';
    print_r($rate_details);*/
    if(count($rate_details)==0 || count($rate_details)==1 )
    {
      for($i=1;$i<=$member_count;$i++)
      {
      
    ?>
        <tr id="item<?php echo $i;?>">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
        <td class="pa-t-pa-b">
        
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_<?php echo $i;?>" id="method_<?php echo $i;?>">
    <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="type_<?php echo $i;?>" id="type_<?php echo $i;?>">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
    </div>
        </div>
        
      <input type="hidden" value="<?php echo $price;?>" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>" class="tkk"/>
        
    <div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>"><?php echo $price;?></p></div>
        
        </td>
        <td class="pa-t-pa-b non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_<?php echo $i;?>" id="n_method_<?php echo $i;?>">
      <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>
<div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="n_type_<?php echo $i;?>" id="n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>
    

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
      </div>
        </div>
        
      <input type="hidden" value="<?php echo $price;?>" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>" class="tkk"/>
        
    <div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php echo $price;?></p></div>
        </td>
        </tr>
        
        </tr>
        <?php
      }
    }
    else if(count($rate_details) >1 ) 
    {
      $i=0;
      foreach($rate_details as $rates)
      {
        /*for($a=1;$a<=$member_count;$a++)
        {*/
        extract($rates);
        $i++;
      ?>
            <tr id="item<?php echo $i;?>">
            <tr>
            <td class="pa-t-pa-b"><div class="sp"><span class="gray"><?php echo $i;?></span></div></td>
            <td class="pa-t-pa-b">
            
            <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="method_<?php echo $i;?>" id="method_<?php echo $i;?>">
            <option value="+" <?php if($method=='+') { ?> selected="selected" <?php } ?>> + </option>
            <option value="-" <?php if($method=='-') { ?> selected="selected" <?php } ?>> - </option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="type_<?php echo $i;?>" id="type_<?php echo $i;?>">
            <option value="Rs" <?php if($type=="Rs") { ?> selected="selected"<?php } ?>> Rs </option>
            <option value="%" <?php if($type=="%") { ?> selected="selected"<?php } ?>> %</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3">
            <div class="ssw ki"> 
            <input type="text" value="<?php if($dis_amount!='') { echo $dis_amount; } else { echo '0.00'; }?>" class="form-control widh cal_amt" name="d_amt_<?php echo $i;?>" id="amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
            </div>
            </div>
            
            <input type="hidden" value="<?php if($total_amount!='') { echo number_format((float)$total_amount, 2, '.', ','); } else { echo number_format((float)$price, 2, '.', ','); }?>" id="h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>" class="tkk"/>
            
            <div class="col-md-3 col-sm-3"><p class="tk" id="total_<?php echo $i;?>"><?php if($total_amount!='') { echo number_format((float)$total_amount, 2, '.', ','); } else { echo number_format((float)$price, 2, '.', ','); }?></p></div>
            
            </td>
            <td class="pa-t-pa-b non_refunds <?php if($non_refund=='0'){?> display_none <?php } ?>">
            
            <div class="col-md-3 col-sm-3">        
            <select class="form-control" name="n_method_<?php echo $i;?>" id="n_method_<?php echo $i;?>">
            <option value="+" <?php if($n_method=='+') { ?> selected="selected" <?php } ?>>+</option>
            <option value="-" <?php if($n_method=='-') { ?> selected="selected" <?php } ?>>-</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3"> 
            <select class="form-control" name="n_type_<?php echo $i;?>" id="n_type_<?php echo $i;?>">
              <option value="Rs" <?php if($n_type=="Rs") { ?> selected="selected"<?php } ?>>Rs</option>
              <option value="%" <?php if($n_type=="%") { ?> selected="selected"<?php } ?>>%</option>
            </select>
            </div>
    
            <div class="col-md-3 col-sm-3">
            <div class="ssw ki"> 
            <input type="text" value="<?php if($n_dis_amount!='') { echo $n_dis_amount; } else { echo '0.00'; }?>" class="form-control widh cal_amt" name="n_d_amt_<?php echo $i;?>" id="n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
            </div>
            </div>
            
            <input type="hidden" value="<?php if($d_total_amount!='') { echo number_format((float)$d_total_amount, 2, '.', ','); } else { echo  number_format((float)$price, 2, '.', ','); }?>" id="n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>" class="tkk"/>
            
            <div class="col-md-3 col-sm-3"><p class="tk" id="n_total_<?php echo $i;?>"><?php if($d_total_amount!='') { echo number_format((float)$d_total_amount, 2, '.', ','); } else { echo number_format((float)$price, 2, '.', ','); }?></p></div>
            
            </td>
            </tr>
        
          </tr>
            <?php
      /*}*/
    } }
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
    $sin_rate_details = get_data(RATE,array('room_id'=>$property_id))->row_array();
    if(count($sin_rate_details)==1)
    {
      ?>
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b room_baseds">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1" id="r_n_method_1">
      <option value="+"  <?php if($sin_rate_details['n_method']=='+') { ?> selected="selected" <?php } ?>>+</option>
    <option value="-"  <?php if($sin_rate_details['n_method']=='-') { ?> selected="selected" <?php } ?>>-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
    <select class="form-control" name="r_n_type_1" id="r_n_type_1">
      <option value="Rs" <?php if($sin_rate_details['n_type']=="Rs") { ?> selected="selected"<?php } ?>>Rs</option>
    <option value="%" <?php if($sin_rate_details['n_type']=="%") { ?> selected="selected"<?php } ?>>%</option>
    </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="<?php echo $sin_rate_details['n_dis_amount'];?>" class="form-control widh r_cal_amt" name="r_n_d_amt_1" id="r_n_amt_1" custom="1" method="n_refun">
      </div>
        </div>
      <input type="hidden" value="<?php echo $sin_rate_details['d_total_amount'];?>" id="r_n_h_total_1" name="r_n_h_total_1" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="r_n_total_1"><?php echo $sin_rate_details['d_total_amount'];?></p></div>
        </td>
        </tr>
        </tr>
        <?php } else { 
    $i=1;?>
        <tr id="item0">
        <tr>
        
        <td class="pa-t-pa-b room_baseds">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_<?php echo $i;?>" id="r_n_method_<?php echo $i;?>">
      <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="r_n_type_<?php echo $i;?>" id="r_n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh r_cal_amt" name="r_n_d_amt_<?php echo $i;?>" id="r_n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
      </div>
        </div>
      <input type="hidden" value="<?php echo $price;?>" id="r_n_h_total_<?php echo $i;?>" name="r_n_h_total_<?php echo $i; ?>" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="r_n_total_<?php echo $i;?>"><?php echo $price;?></p></div>
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
    </div>
    <div class="col-sm-12 col-xs-12 form-group clearfix">
      <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
    <input type="submit" value="Save" class="btn cls_com_blubtn pull-right">
  <?php } ?>   
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>

<!--Room Master rate end-->

<!-- Rate Types Start --> 

<div id="tab5" class="tab-pane fade w3-animate-right" role="tabpanel">
    <div class="cls_comm_in">

    <?php 
    $count_rate_types = $this->inventory_model->count_rate_types(insep_encode($property_id));
    if($count_rate_types==0) { 
    ?>
    <div class="reser">
    <center><i class="fa fa-sitemap"></i></center>
    </div>
    <h2 class="text-center">You don't have any rate types yet</h2>
    <p class="pad-top-20 text-center">You can apply your different pricing strategies and increase your sales by using rate types</p>

    <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>

    <div class="clearfix">

    <a href="#add_rate" class="cls_com_blubtn pull-right waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000">
    <i class="fa fa-plus-circle"></i> Add rate type </a>
 
    </div>
    <?php } ?>
    <?php } else { 
    $rate_types = get_data(RATE_TYPES,array('room_id'=>$property_id))->result_array();
    if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
    <div class="clearfix">
    <a href="#add_rate" class="cls_com_blubtn pull-right waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000"><i class="fa fa-plus-circle"></i> Add rate type </a>
    </div>

  <?php } ?>
  <div class="cls_commtable cls_roomtable">
  <div class="table-responsive">
  <table class="table" id="original-container">
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
  <th class="col-md-4">
  <span class="line"></span>
  Action
  </th>
  </tr>

  </thead>
  <tbody id="table-body">
  <?php 
  $i=1;
  foreach($rate_types as $rate) { extract($rate); $i++; ?>
  <tr id="hr_variant_rate_<?php echo insep_encode($uniq_id);?>">
  <td> 
  <a data-remote="true" href="<?php echo lang_url();?>inventory/manage_rate_types/edit/<?php echo $uniq_id;?>">
  <?php  if($rate_name!=''){ echo $rate_name; } 
  else { echo '#'.$uniq_id;}?>
  </a>
  <br>
  <span class="subtext"><?php if($pricing_type=='1'){?>Room based pricing<?php } else { ?>Guest based pricing <?php } ?></span>
  <span class="hide">2</span>
  <span class="hide">2</span> </td>
  <td> <?php echo get_data(MEAL,array('meal_id'=>$meal_plan))->row()->meal_name;?> </td>
  <td> <?php if($droc=='1') { ?>Yes <?php } else { echo 'No';}?>  </td>
  <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
  <td><a href="javascript:;" title="Delete this rate type"class="del_rate" data-id="<?php echo insep_encode($uniq_id);?>" room-id="<?php echo insep_encode($room_id);?>"> <i class="fa fa-trash-o text-danger"> </i></a>   </td>
  <?php }
  else {?>
  <td><a href="javascript:;"> <i class="fa fa-trash-o text-danger"> </i></a></td> 
  <?php }
  ?>
  </tr>
  <?php } ?>
  </tbody>
  </table>
  </div>
  </div>
  <?php } ?>
  </div>
  </div>
<li class="display_none">
<a href="#editadd_rate" class="btn waves-effect waves-light m-r-5 m-b-10 editadd_rate" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000"></a>
</li>
<!-- Rate Types End --> 

<!-- amenities start  -->

<div id="tab6" class="tab-pane fade w3-animate-right" role="tabpanel">
          <div class="box-m2">
<div class="row">
<div class="col-md-12 col-sm-12">
<?php $get_ammenties = explode(',',$amenities);?>
<form method="post" id="update_amenities">
<input type="hidden" value="<?php echo $property_id?>" name="property_id"/>
<?php $am_types = get_data(AMENITIES_TYPE)->result_array();
foreach($am_types as $amm){
  extract($amm);
  /*echo 'count '.*///$amm_count = $this->inventory_model->getallexist_subcatcntbymaincat($id);
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

 
                        
<div class="">
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
<!-- <a href="javascript:;" data-toggle="modal" data-target="#myModal_<?php echo $id?>" data-keyboard="false" data-backdrop="static">More</a> -->

<a href="#myModal_<?php echo $id?>" class="waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000">
 More </a>



<?php } ?>
</div>
<?php  } } ?>
</div>
</div>
<br/>
<?php } ?>
</form>
</div>
</div>

</div>
      
          </div>

<!-- amenities end  -->

<!-- connected_channel end  -->

<div id="tab7" class="tab-pane fade w3-animate-right" role="tabpanel">            
            <div class="row">

            <?php 
  $connected_channels = get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row();
  if($connected_channels->connected_channel!='')
  {
     $cha = explode(',',$connected_channels->connected_channel);
     $channelss = $this->inventory_model->get_channels($cha);
    if($channelss){
    foreach($channelss as $channels){
  ?>
            <div class="col-sm-4 col-xs-12 ">
            <div class="grid cls_changrid">
            <figure class="effect-julia"> 
            <a class="sser" href="<?php echo lang_url(); ?>mapping/settings/<?php echo insep_encode($channels->channel_id);?>">
            <img style="height: 250px;width: 350px;" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$channels->image));?>" alt="chan_booking" class="img-responsive center-block"/>

            <figcaption>
            <div>
              <p><?php echo $channels->channel_name;?></p>        
            </div>
            </figcaption>
            </figure>
            <div class="grheadblk">
            <p class="grhead"> <?php echo $channels->channel_name;?> </p>

            </div>
            </a>
            </div>
            </div>
              
            <?php } } } else { ?>

            <div class="bb1">
            <br>
            <div class="reser"><center><i class="fa fa-globe"></i></center></div>
            <h2 class="text-center">You don't have any online channels configured for this room type yet</h2>
            <p class="pad-top-20 text-center">You can sell your rooms from hundreds of online sales channels</p>
            <br>
            <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
            <div class="res-p">
            <center><a class="btn btn-primary" href="<?php echo lang_url();?>channel/channel_listing"><i class="fa fa-plus"></i>  Map Channel</a>
            </center>
            </div>
            <?php } ?>
            </div>

            <?php }?>
      
            </div>            
          </div>

<!-- connected_channel end  -->

<!-- photos start -->

<div id="tab8" class="tab-pane fade w3-animate-right" role="tabpanel">              
            <div class="row">
        
          <div class="col-sm-12 col-xs-12">
            <div class="cls_phblk clearfix">
            <?php  
$hotel_photos = get_data(TBL_PHOTO,array('room_id'=>$property_id))->row_array();
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>

            <div class="col-sm-12 col-xs-12">

            <form method="post" id="upload_photos" enctype="multipart/form-data" action="<?php echo lang_url();?>inventory/manage_photos/new">
            <input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo insep_encode($property_id);?>"/>
            <div class="col-md-7 col-sm-2">        
            <div class="col-sm-8 col-xs-12 pad-no">
            <div class="pull-right">
            <div class="input-group file-upload">

            <div class="input-group-addon">
            <div class="fileUpload btn btn-primary">
            
            <input type="file" id="immm" class="immm" reqired name="hotel_image[]" multiple  accept="image/png,image/gif,image/jpeg">
            </div>
            </div>
           <!--  <input id="uploadFile1" class="form-control" placeholder="No file Chosen" disabled="disabled"> -->
            </div>
            </div>
            </div>
            <div class="col-sm-4 col-xs-12 pad-no">
            <input class="cls_uplbtn cls_com_blubtn" type="submit" value="Upload" id="uploadButton" disabled="disabled">
            
            </div>
            </div>
            </form>
            </div>
            <?php } ?>
            </div>

          </div>


          </div>

          <br/>
        <div class="new-galery" id="img_rplae">
        <ul class="list-unstyled row">
        <?php 
        if(count($hotel_photos)!=0) 
        {
        $photos = explode(',',$hotel_photos['photo_names']); 
        foreach($photos as $val)
        {
        ?>
        <li class="col-md-3 col-sm-3">
        <div class="box-y">
        <a class="fancybox" href="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" data-fancybox-group="gallery">
        <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" alt="" class="img img-responsive img-thumbnail" /></a>
        <?php if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="overbox-y">
        <div class="title overtext text-center pull-right"> <a href="javascript:;"  class="delete_photo" data-id="<?php echo insep_encode($hotel_photos['photo_id']);?>" custom="<?php echo insep_encode($val);?>"><span class="ne-pk"><i class="fa fa-close"></i></span></a> </div>
        </div>
        <?php } ?>

        </div></li>
        <?php } } else { ?>
        <div class="alert alert-danger text-center"> No photos data found!!!</div>
        <?php } ?>


        </ul>
        </div>

        </div>

<!-- photos end -->

    </div>

    </div>



    </div>
    <?php $this->load->view('channel/dash_sidebar'); ?>
    </div>
    <!--</div> -->



<!--  modal popup start   -->

<?php
$available = get_data(ICAL,array('room_id'=>($property_id)))->row_array();
if(count($available)!=0)
{
?>
<div class="row mar-top7">
<div class="col-md-3" align="right">
ICAL LINK :
</div>
<div class="col-md-9" align="left">
<a> <?php echo base_url().'uploads/ICAL/room/'.$available['ical_link'];?> </a>
</div>
</div>
<?php 
}
?>


<?php
$am_typess = get_data(AMENITIES_TYPE)->result_array();
foreach($am_typess as $amms){
  extract($amms);
  $amm_count = $this->inventory_model->getallexist_subcatcntbymaincat($id);
  $end_catlimit=$amm_count-8; 
  $start_catlimit=8;
?>

<div id="login" class="modal-demo modal-dialog modal_list modal-content">
    <button type="button" class="aminities_modal close login_close" onclick="Custombox.close();">
    <span>&times;</span><span class="sr-only">Close</span>
    </button>
    <h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
    <hr>
    <div class="custom-modal-text">
        <form class="login-form  form_login" method="post" id="log_form" novalidate="novalidate">
        <div class="login_content">
        </div>      
        </form>
    </div>
</div>

<div class="modal-demo modal-dialog modal_list modal-content" id="myModal_<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="aminities_modal modal-dialog dialog2" role="document">
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
<div class="">
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
    <?php if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
    { ?>
      <div class="modal-footer">
        <button class="btn btn-primary" type="button" data-dismiss="modal">Done</button>
      </div>
    <?php } ?>
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
        <div class="col-md-4 col-sm-4"><span class="ne-k">Area (mÂ²) :</span></div>
        <div class="col-md-8 col-sm-8 ssw vi1">
          <div class="form-group">
            <input type="number" value="<?php echo $area;?>" class="form-control widh" name="area" id="area" min="1">
          </div>
        </div>
        </div>
        
        </div>
    </div>
      <div class="modal-footer">
    <?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit())){ ?>
        <button type="submit" class="btn btn-primary">Save</button>
    <?php } ?>
      </div>
      
      </form>
      
    </div>
  </div>
</div>

<div id="add_rate" class="modal-demo modal-dialog modal_list modal-content">
  <button type="button" class="close login_close" onclick="Custombox.close();">
  <span>&times;</span><span class="sr-only">Close</span>
  </button>
  <h4 class="custom-modal-title">
  <?php echo get_data(CONFIG)->row()->company_name;?> Add Tax Categories</h4>
  <hr>
  <div class="custom-modal-text">

    <form role="form" class="form-horizontal cls_mar_top" name="rate_type" id="rate_type" method="post" action="<?php echo lang_url();?>inventory/manage_rate_types/add" enctype="multipart/form-data">
      <input type="hidden" name="property_id" id="property_id" value="<?php echo insep_encode($property_id);?>" />
      
      <input type="hidden" name="member_count" id="member_count" value="<?php echo insep_encode($member_count);?>" />
    <div class="login_content">

    <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">Name <span class="errors">*</span></label>
    <div class="col-sm-8">
    <input type="text" placeholder="Rate Type Name" id="property_name" name="property_name" class="form-control">
    </div>
    </div>
        <div class="clearfix"></div>


        <?php $room_detial = get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row();?>
        <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">Pricing Type <span class="errors">*</span></label>
    <div class="col-sm-8">
      <?php
      if($room_detial->pricing_type=='1')
      {
      echo 'Room based pricing';
      }
      else if($room_detial->pricing_type=='2')
      {
      echo 'Guest based pricing';
      }

      ?>
      <input type="hidden" value="<?php echo $room_detial->pricing_type;?>" name="pricing_type"/> 
    </div>
    </div>
        <div class="clearfix"></div>


        <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">Display room on calendar <span class="errors">*</span></label>
    <div class="col-sm-8">
    <input type="radio" value="1" checked="checked" name="droc" id="droc"/> Yes <input type="radio" value="2" name="droc" id="droc1"/> No
    </div>
    </div>
        <div class="clearfix"></div>

        
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-4 control-label"> Meal plan </label>
        <div class="col-sm-8">  
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
        <div class="clearfix"></div>
        
    <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> Base price / Per night <span class="errors">*</span></label>

    <?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?>

      <div class="col-sm-8">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Price" id="add_price1" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="<?php echo get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price;?>">
        </div>
      </div>
      </div>
   

         <input type="hidden" name="base_price" id="base_price" value="<?php echo get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price;?>"/>


        <div style="width: 900px;margin-left: 10px;" class="form-group guest_based <?php if($room_detial->pricing_type==1) { ?> display_none <?php } ?>">
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
        <select class="form-control" name="method_<?php echo $i;?>" id="r_t_method_<?php echo $i;?>">
    <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="type_<?php echo $i;?>" id="r_t_type_<?php echo $i;?>">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh cal_amt2" name="d_amt_<?php echo $i;?>" id="r_t_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="refun">
    </div>
        </div>
      <input type="hidden" value="100.00" id="r_t_h_total_<?php echo $i;?>" name="h_total_<?php echo $i; ?>" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="r_t_total_<?php echo $i;?>"><?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?></p></div>
        </td>
        <td class="pa-t-pa-b non_refund  display_none ">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_<?php echo $i;?>" id="r_t_n_method_<?php echo $i;?>">
      <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="n_type_<?php echo $i;?>" id="r_t_n_type_<?php echo $i;?>">
          <option value="Rs">Rs</option>
          <option selected value="%">%</option>
        </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh cal_amt2" name="n_d_amt_<?php echo $i;?>" id="r_t_n_amt_<?php echo $i;?>" custom="<?php echo $i?>" method="n_refun">
      </div>
        </div>
      <input type="hidden" value="100.00" id="r_t_n_h_total_<?php echo $i;?>" name="n_h_total_<?php echo $i; ?>" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="r_t_n_total_<?php echo $i;?>"><?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?></p></div>
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
        <div class="clearfix"></div>



        <div style="width: 900px;margin-left: 10px;" class="form-group room_based <?php if($room_detial->pricing_type==2) { ?> display_none <?php } ?>">    
    <div class="col-sm-8">
    <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th class="text-center" colspan="">Refundable</th>
        
        <th class="non_refund text-center" colspan="">Non refundable</th>
        </tr>
        </thead>
        <tbody class="datas">
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">
        <div class="select-style1">        
          <select class="form-control" name="r_method_1" id="rr_t_method_1">
          <option value="+">+</option>
          <option value="-">-</option>
          </select>
        </div>
    </div>

    <div class="col-md-3 col-sm-3"> 
        <select class="form-control" name="r_type_1" id="rr_t_type_1">
        <option value="Rs">Rs</option>
        <option selected value="%"> %</option>
        </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh r_cal_amt2" name="r_d_amt_1" id="rr_t_amt_1" custom="1" method="refun">
    </div>
        </div>
      <input type="hidden" value="<?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?>" id="rr_t_h_total_1" name="r_h_total_1" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="rr_t_total_1"><?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?></p></div>
        </td>
        <td class="pa-t-pa-b non_refund">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1" id="rr_t_n_method_1">
      <option value="+">+</option>
    <option value="-">-</option>
    </select>
    </div>

    <div class="col-md-3 col-sm-3"> 
    <select class="form-control" name="r_n_type_1" id="rr_t_n_type_1">
      <option value="Rs">Rs</option>
    <option selected value="%">%</option>
    </select>
        </div>

    <div class="col-md-3 col-sm-3">
    <div class="ssw ki"> 
      <input type="text" value="0.00" class="form-control widh r_cal_amt2" name="r_n_d_amt_1" id="rr_t_n_amt_1" custom="1" method="n_refun">
      </div>
        </div>
      <input type="hidden" value="<?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?>" id="rr_t_n_h_total_1" name="r_n_h_total_1" class="tkk"/>
    <div class="col-md-3 col-sm-3"><p class="tk" id="rr_t_n_total_1"><?php echo number_format((float)get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->price,2, '.', '');?></p></div>
        </td>
        </tr>
        </tr>
        </tbody>
        </table>
        </div>
    </div>
    </div>
        
        
<button onclick="Custombox.close();" type="button" class="btn btn-default" data-dismiss="modal">Close</button>

<button class="cls_com_blubtn hover-shadow pull-right" type="submit">Create</button>     
    
    </form>
    </div>  
</div>
</div>




 <div id="editadd_rate" class="modal-demo modal-dialog modal_list modal-content">
  <button type="button" class="close reg_close" onclick="Custombox.close();">
  <span>&times;</span><span class="sr-only">Close</span>
  </button>
  <h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
  <hr>
  <div class="custom-modal-text">
    <form role="form" class="form-horizontal cls_mar_top" name="rate_type" id="rate_type" method="post" action="<?php echo lang_url();?>inventory/manage_rate_types/update" enctype="multipart/form-data">
    <div class="edit_document">
    </div>
    </form>
  </div>
</div> 
<!--  edit rate types ends  -->