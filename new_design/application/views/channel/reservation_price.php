<?php $currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;?>
<input type="hidden" name="oringinal_price" value="<?php echo $price;?>" id="oringinal_price">
<div class="table-responsive">
<table class="table">
<tr>
<?php $tot_amount=$price;?>
<td >  <span class="num_night"><?php echo $night;?></span> <span class="nights"><?php echo $nights;?></span>   </td>
<td id=""> <?php echo $currency;?> <span id="actualamount" class="cal_price"> <?php echo $tot_amount;?></span>   </td>
</tr>
<!--
<?php 
  $taxes = get_data(TAX,array('user_id'=>current_user_type(),'included_price'=>'0'))->result_array();
  if(count($taxes)!=0)
  {
	  $total_price = $price;
      foreach($taxes as $valuue)
      {
          extract($valuue);
		  $total_price=$price + $price * $tax_rate / 100;
  ?>
<tr>
<td>  <?php echo $user_name;?> (<?php echo $tax_rate;?>%):    </td>
<td class="">  <?php echo $currency;?> <span class="cal_price"> <?php echo $price * $tax_rate / 100;?></span>  </td>
</tr> 
<?php }
 } 
 else
 {
	 $total_price=$price;
 }?>
-->
<tr>
<td>Grand total :  </td>
<td id=""> <?php echo $currency;?> <span id="grand_total"><?php echo $tot_amount;?></span></td>
</tr>

</table>
</div>
<h3 class="text-center">DUE NOW  </h3>
<input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id;?>"/>
<input type="hidden" name="rate_type_id" id="rate_type_id" value="<?php echo $rate_type_id;?>"/>
<input type="hidden" name="price_day" id="price_day" value="<?php echo $price_day;?>"/>
<h3  class="text-center" id=""> <?php echo $currency;?><span id="due_now"> <?php echo $tot_amount;?></span></h3>
