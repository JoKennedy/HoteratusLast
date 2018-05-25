    <div class="modal-content" align="center">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
		<?php $room_detail = get_data(TBL_PROPERTY,array('property_id'=>$room_id))->row();echo ucfirst($room_detail->property_name);?> </h4>
      </div>
      <div class="modal-body">
      
      <div class="row">
      <div class="co-md-12 col-sm-12">
      
      <h3> Reservation Details <a href="<?php echo lang_url();?>reservation/reservation_print/<?php echo insep_encode($reservation_id);?>" target="_blank"><button type="button" class="btn btn-success pull-right"> <i class="fa fa-print"> </i> Print</button></a></h3>
      <table class="summaryTable1">
        <tbody>
        <tr>
          <th>
             Confirmation number 
          </th>
          <td>
            <b><?php echo '#'.$reservation_code;?></b>
          </td>
        </tr>
        <!--<tr>
          <th>
            Adult count
          </th>
          <td>
            <?php //echo $members_count;?>
      
      
          </td>
        </tr>-->
        
        <!--<tr>
          <th>
            Children count
          </th>
          <td>
            <?php //echo $children;?>
      
      
          </td>
        </tr>-->
        <tr>
          <th>
            Check-in date
          </th>
          <td>
            <?php echo date('M d,Y',strtotime(str_replace('/','-',$start_date))); ?>
          </td>
        </tr>
        <tr>
          <th>
            Check-out date
          </th>
          <td>
            <?php echo date('M d,Y',strtotime(str_replace('/','-',$end_date))); ?>
          </td>
        </tr>
      
        <tr>
          <th>
            Subtotal	
          </th>
          <td>
          <?php 
          $currency_id = get_data('manage_users',array('user_id'=>user_id()))->row()->currency;
          $currency_detail = get_data(TBL_CUR,array('currency_id'=>$currency_id))->row();
          
          $var = $start_date;
          $datea = str_replace('/', '-', $var);
          $start_dates  = date('Y-m-d', strtotime($datea));
          
          $var1 = $end_date;
          $datea1 = str_replace('/', '-', $var1);
          $end_dates  = date('Y-m-d', strtotime($datea1));
          
          $startDate = DateTime::createFromFormat("d/m/Y",$start_date);
          $endDate = DateTime::createFromFormat("d/m/Y",$end_date);
          $periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
          $endDate->add( $periodInterval );
          $periods = new DatePeriod( $startDate, $periodInterval, $endDate );
          $i=1;
          foreach($periods as $valuw)
          {
              $i++;
          }
          echo $currency_detail->symbol.' '.number_format($i * $price, 2, '.	', ',').' /-';
              ?>
      
            <?php ?>
          </td>
        </tr>
        <tr>
          <th>
            Grand total
          </th>
          <td>
           <?php echo $currency_detail->symbol.' '.number_format($i * $price, 2, '.	', ',').' /-';?>
          </td>
        </tr>
            <tr>
              <th>
                Status
              </th>
              <td>
              <?php
              if($status=='cancel')
              {
                  $cls= 'danger';	
              }
              else if($status=='Reserved')
              {
                  $cls= 'info';	
              }
              else if($status=='Confirmed')
              {
                  $cls= 'success';	
              }
              ?>
                <label class="label label-<?php echo $cls;?>"><?php echo ucfirst($status);?></label>
              </td>
            </tr>
            
            <tr>
              <th>
                Booked date
              </th>
              <td>
                 <?php echo date('F d , Y',strtotime(str_replace('/','-',$booking_date))); ?>
              </td>
            </tr>
            
        </tbody>
      </table>
      
      <p>&nbsp;</p>
      <h3> User Details </h3>
      <table class="summaryTable1">
        <tbody>
            <tr>
              <th>Name</th>
              <td>
                   <?php echo ucfirst($guest_name.' '.$family_name);?>
      
              </td>
            </tr>
            <tr>
              <th>Phone	</th>
              <td><?php echo $mobile?></td>
            </tr>
            <tr>
              <th>E-mail</th>
              <td><?php echo $email;?></td>
            </tr>
            <tr>
              <th>Street address</th>
              <td><?php echo $street_name;?></td>
            </tr>
            <tr>
              <th>Country</th>
              <td><?php if($country!='') { echo ucfirst(get_data(TBL_COUNTRY,array('id'=>$country))->row()->country_name);}else { echo '----';}?></td>
            </tr>
        </tbody>
      </table>

      <p>&nbsp;</p>
      <h3> Billing Info </h3>
      <table class="summaryTable1">
            <tbody>
                <tr>
                  <th>Payment Method</th>
                  <td>
                       <?php if($payment_method==1) { echo 'Cash';}else if($payment_method==2) { echo 'Cheque';}?>
          
                  </td>
                </tr>
            </tbody>
          </table>

      <p>&nbsp;</p>
      <h3> Room Details </h3>
      <table class="summaryTable1">
        <tbody>
            <tr>
              <th>Guest count</th>
              <td>
                    <?php echo $members_count;?>
      
              </td>
            </tr>
            <tr>
              <th>Meal plan</th>
              <td>
			  <?php 
			  echo $reser_meal_plan = $this->inventory_model->reser_meal_plan($room_id);
			  ?></td>
            </tr>
            <tr>
              <th>Extras</th>
              <td><?php if($room_detail->meal_extra==1) { echo 'Available';}else if($room_detail->meal_extra==0){ echo 'Not available';}?></td>
            </tr>
            <tr>
              <th>Check-in date</th>
              <td><?php echo date('M d,Y',strtotime(str_replace('/','-',$start_date))); ?></td>
            </tr>
            <tr>
              <th>Check-out date</th>
              <td><?php echo date('M d,Y',strtotime(str_replace('/','-',$end_date))); ?></td>
            </tr>
            
            <tr>
              <th>Total</th>
              <td><?php echo $currency_detail->symbol.' '.number_format($i * $price, 2, '.	', ',').' /-';?></td>
            </tr>
        </tbody>
      </table>
      
      <p>&nbsp;</p>
      <h3> Daily Price </h3>
      <table class="summaryTable1">
      <thead>
      <tr>
              <th>Date</th>
              <th>Price</th>	
            </tr>
      </thead>
        <tbody>
        <?php
		  $var = $start_date;
          $datea = str_replace('/', '-', $var);
          $start_dates  = date('Y-m-d', strtotime($datea));
          
          $var1 = $end_date;
          $datea1 = str_replace('/', '-', $var1);
          $end_dates  = date('Y-m-d', strtotime($datea1));
          
          $startDate = DateTime::createFromFormat("d/m/Y",$start_date);
          $endDate = DateTime::createFromFormat("d/m/Y",$end_date);
          $periodInterval = new DateInterval("P1D"); // 1-day, though can be more sophisticated rule
          $endDate->add( $periodInterval );
          $periods = new DatePeriod( $startDate, $periodInterval, $endDate );
          $i=1;
          foreach($periods as $value)
          {
			  
		?>  
        <tr>
              <td><?php echo date('M d,Y',strtotime(str_replace('/','-',$value->format("d/m/Y")))); ?></td>
              <td><?php echo $currency_detail->symbol.' '.number_format($i * $price, 2, '.	', ',').' /-'?></td>
        </tr>
        <?php 
		
		  }
		  ?>
        </tbody>
      </table>
      
       <p>&nbsp;</p>
      <h3> Notes </h3>
      <table class="summaryTable1">
        <tbody>
            <tr>
              <td><?php if($description!='') { echo $description; } else { echo 'No notes provide...';}?></td>
            </tr>
        </tbody>
      </table>
      
      
      <p>&nbsp;</p>
      <h3> Hotel Policies </h3>
      <?php 
	  		$cancel_details = get_data(PCANCEL,array('user_id'=>user_id()))->row();	
	  		$other_details = get_data(POTHERS,array('user_id'=>user_id()))->row();
		?> 
      <table class="summaryTable1">
        <tbody>
            <tr>
              <th>Cancellation</th>
              <td>
                    <?php echo $cancel_details->description;?>
      
              </td>
            </tr>
            <tr>
              <th>Check-in time</th>
              <td>After <?php echo $other_details->check_in_time;?> day of arrival.</td>
            </tr>
            <tr>
              <th>Check-out time</th>
              <td><?php echo $other_details->check_out_time;?> upon day of departure.</td>
            </tr>
            <tr>
              <th>Smoking</th>
              <td><?php if($other_details->smoking==1){?> Smoking is allowed.<?php } else { ?> Smoking is not allowed.<?php } ?></td>
            </tr>
            <tr>
              <th>Pets</th>
              <td>  <?php if($other_details->pets==0){?> No pets allowed. <?php } else { ?>Pets are allowed. <?php } ?></td>
            </tr>
        </tbody>
      </table>
      
      
      
      </div>
      
      </div>
      </div>
      
    </div>






