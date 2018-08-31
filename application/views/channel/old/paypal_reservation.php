<div class="container cls_comm_ashbg cls_printmain">
 <div class="row">





<div style="margin-top:120px;" class="text-center">
        <section class="product_image">
          <img height="155" width="280" src="http://cdn-cms1.hotelrunner.com/assets/photos/original/e029028d-3771-4302-8103-10ad144e1b92.png">
        </section>
        <div class="clearfix"></div>         
    <?php     
    if($get_reservation!=''){
    foreach ($get_reservation as $reser) 
    {
      $room_name = get_data(TBL_PROPERTY,array("property_id"=>$reser->room_id))->row()->property_name;

      if($other_details->smoking==1)
    {
      $smoke = 'Smoking is allowed';
    }
    else if($other_details->smoking==0)
    {
      $smoke = 'Smoking is not allowed.';
    }
    if($other_details->smoking==1)
    {
      $pets = 'Pets are allowed';
    }
    else if($other_details->smoking==0)
    {
      $pets = 'No pets allowed';
    }

     ?>
        <div class="row" align="center">
        <div class="co-md-12 col-sm-12">        
        <h5> Thank you <?php echo ucfirst($reser->guest_name); ?> your order is complete!
        Your confirmation number is #<?php echo $reser->reservation_code; ?> </h5>

        <table class="summaryTable">
        <tbody>
        <tr>
        <th>
        Confirmation number 
        </th>
        <td>
        <b><?php  echo $reser->reservation_code; ?></b>
        </td>
        </tr>
        <tr>
        <th>
        No.of Rooms
        </th>
        <td>
        <?php  echo $reser->num_rooms; ?>        
        </td>
        </tr>
        <tr>
        <th>
        No.of Adult
        </th>
        <td>
        <?php  echo $reser->members_count; ?>        
        </td>
        </tr>
        <tr>
        <th>
        No.of Child
        </th>
        <td>
        <?php  echo $reser->children; ?>                
        </td>
        </tr>
        <tr>
        <th>
        Room Type
        </th>
        <td>
        <?php  echo $room_name; ?>                
        </td>
        </tr>
        <tr>
        <th>
        Check-in date
        </th>
        <td>
        <?php  echo $reser->start_date; ?>                
        </td>
        </tr>
        <tr>
        <th>
        Check-out date
        </th>
        <td>
        <?php  echo $reser->end_date; ?>                
        </td>
        </tr>

        <tr>
        <th>
        No.of Nights
        </th>
        <td>
        <?php  echo $reser->num_nights; ?>                
        </td>
        </tr>
        <tr>
        <th>
        Order Total
        </th>
        <td>
        <?php  echo $reser->num_rooms; ?>                
        </td>
        </tr>
        </tbody>

        </table>
        <p>&nbsp;</p>
        <h3>Hotel Policies</h3>
        <table class="summaryTable">

        <tbody>

        <tr>

        <th>Cancellation</th>

        <td>

        <?php  echo $cancel_details->description; ?>        

        </td>

        </tr>

        <tr>

        <th>Check-in time</th>

        <td>After <?php  echo $other_details->check_in_time; ?> day of arrival.</td>

        </tr>

        <tr>

        <th>Check-out time</th>

        <td><?php  echo $other_details->check_out_time; ?> upon day of departure.</td>

        </tr>

        <tr>

        <th>Smoking</th>

        <td><?php  echo $smoke; ?></td>

        </tr>

        <tr>

        <th>Pets</th>

        <td><?php  echo $pets; ?></td>

        </tr>

        </tbody>
        </table>


        </div>

        </div>
     <?php  } }else{ ?>

     <div class="row" align="center">
        <div class="co-md-12 col-sm-12">

        <h5>No Results Found !!</h5>
        </div>
        </div>


      <? } ?>

      </div>

</div>
</div>
