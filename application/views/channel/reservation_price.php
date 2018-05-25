


<?php $currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol;?>
<input type="hidden" name="oringinal_price" value="<?php echo $price;?>" id="oringinal_price">
<div class="table-responsive">
<table class="table">
<tr>
   <h5> Charges </h5>
<?php $tot_amount=$price*$_POST['roomqty'];?>
<td >  <span class="num_night"><?php echo $night;?></span> <span class="nights"><?php echo $nights." x ".$_POST['roomqty'].($_POST['roomqty']==1?" Room":" Rooms");?></span>   </td>
<td id=""> <?php echo $currency;?> <span id="actualamount" class="cal_price"> <?php echo $tot_amount;?></span>   </td>
</tr>

<tr>
 
<td>Sub total :  </td>
<td id=""> <?php echo $currency;?> <span id="grand_total"><?php echo $tot_amount;?></span></td>
</tr>

</table>

</div>


        <?php $extras = get_data("room_extras", array("room_id"=>$room_id))->result_array();

              if(count($extras)>0)
              {
                echo "<h5>Extras</h5>";
              }
             ?>
            
            <div class="table-responsive">
              <table class="table">
                <tbody>
                <?php 
                  

                  foreach($extras as $extra){
                    switch($extra['structure']){
                      case '1':
                        $structure = 'Per Person';
                        $subtotal = $extra['price']*intval($_POST['num_person']);
                      break;

                      case '2':
                        $structure = 'Per Night';
                        $subtotal = $extra['price']*intval($night);
                      break;

                      case '3':
                        $structure = 'Per Stay';
                        $subtotal = $extra['price'];
                    }

                    $total = $subtotal*intval($extra['taxes'])/100+$subtotal;
                    echo '<tr>';
                      echo '<td><input type="checkbox" name="extra['.$extra['extra_id'].']" value="'.$total.'" onclick ="return addExtra(this)" ></td>';
                      echo '<td>'.$extra['name'].'('.$structure.')<td>';
                      echo '<td>$'.$extra['price'].'</td>';
                      echo '<td>$'.$subtotal.'</td>';
                      echo '<td>%'.$extra['taxes'].'</td>';
                      echo '<td>$'.$total.'</td>';
                    echo '</tr>';
                  }
                ?>
                </tbody>
              </table>
             
            </div>

<h3 class="text-center">DUE NOW  </h3>
<input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id;?>"/>
<input type="hidden" name="rate_type_id" id="rate_type_id" value="<?php echo $rate_type_id;?>"/>
<input type="hidden" name="price_day" id="price_day" value="<?php echo $price_day;?>"/>
<h3  class="text-center" id=""> <?php echo $currency;?><span id="due_now"> <?php echo $tot_amount;?></span></h3>

<script type="text/javascript">

function addExtra(valor){

  var monto=document.getElementById("due_now").innerHTML;
  var suma =0;
  if (valor.checked)
  {
    
    suma=(parseFloat(monto)+parseFloat(valor.value)*<?=$_POST['roomqty']?>);

  }
  else
  {
    suma=(parseFloat(monto)-parseFloat(valor.value)*<?=$_POST['roomqty']?>);
  }

  $('#due_now').html(suma);
 
}

</script>