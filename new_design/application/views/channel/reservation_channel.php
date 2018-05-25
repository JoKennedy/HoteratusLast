<div class="dash-b">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">

  <div class="dash-b2">

        <div class="page-content">

        <div class="container">
                    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                    
                    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                    <!-- BEGIN PAGE BREADCRUMB -->
                    
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE CONTENT INNER -->
      <div class="row">
      <div class="col-md-12">
    <?php $error = $this->session->flashdata('error'); 
    if($error!="") {
      echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
    }
    $success = $this->session->flashdata('success');
    if($success!="") {
      echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
    } ?>
                        <!-- Begin: life time stats -->
  <div class="portlet light">
    <div class="portlet-title">

<!-- MOdel start -->
<div class="modal fade" id="myModal23" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" id="m12close" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        
        <h4 class="modal-title" id="myModalLabel"> Verification </h4>
      </div>
      <input type="hidden" id="channelid" value="<?php echo ($curr_cha_id); ?>"></input>
        <input type="hidden" id="resr_id" value="<?php echo $RESER_NUMBER; ?>"></input>
      <div id="show_credit_card"> 
       </div>
        <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
        <button type="button" id="password_check"  class="btn btn-warning">Search</button>
      <button type="button" style="visibility:hidden" id="seach_reserve_show" data-toggle="modal" data-target="#myModal3" class="btn btn-warning">Search</button>
      </div>
      
     
    </div>
  </div>
</div>


<div class="modal fade" id="myModal234" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="height: 300px;">
     <div class="modal-header">
        <button type="button" id="m12close" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
        <h4 class="modal-title" id="myModalLabel"> Card Details </h4>
      </div>
    <div class="modal-body">
         <div class="col-sm-12">
              <table class="summaryTable">
  <tbody>
  <?php if(isset($CC_TYPE)){ ?>
  <tr>
    <th>
      Credit Card Type
    </th>
    <td id="c_type">
      <b><?php //echo $CC_TYPE;?></b>
    </td>
  </tr>
  <?php } ?>
  <tr>
    <th>
      Cardholder Name
    </th>
    <td id="c_name">
      <b><?php //echo $CC_NAME;?></b>
    </td>
  </tr>
  <tr>
    <th>
      Card Number
    </th>
    <td id="c_number">
      <b><?php //echo $CC_NUMBER;?></b>
    </td>
  </tr>
  <tr>
    <th>
      Expiration month
    </th>
    <td id="res_exp_month">
     <?php //echo  $CC_DATE; ?>
    </td>
  </tr>
  <tr>
    <th>
        Expiration Year
    </th>
    <td id="res_exp_year">
	<?php //echo  $CC_YEAR; ?>
    </td>
  </tr>
  </tbody>
</table>
           </div>
         </div>
      
    </div>
  </div>
</div>
<!-- MOdel End -->



      <div class="caption">
        <i class="icon-basket font-green-sharp"></i>
        <span class="caption-subject font-green-sharp bold uppercase">
        Reservation -&gt;  #<?php echo $RESER_NUMBER;?> </span>
        <span class="caption-helper"> <?php echo $RESER_DATE; ?> </span>
      </div>
              
      <div class="actions">
        <a href="<?php echo lang_url(); ?>reservation/reservationlist" class="btn btn-default btn-circle">
        <i class="fa fa-angle-left"></i>
        <span class="hidden-480">
        Back </span>
        </a>
        <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
        <div class="btn-group">
          <a class="btn blue-hoki btn-circle" href="javascript:;" data-toggle="dropdown">
          <i class="fa fa-cog"></i>
          <span class="hidden-480">
          Tools </span>
          <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu pull-right">
            <li>
               <a href="<?php echo lang_url(); ?>reservation/reservation_print/<?php echo $curr_cha_id.'/'.insep_encode($RESER_ID); ?>" target="_blank"> Print Voucher</a> 
            </li>
            <!-- <li>
              <a href="javascript:;">
              Export to Excel </a>
            </li> -->
            
          </ul>
        </div>
        <?php } ?>
      </div>
      
    </div>
	<?php 
	if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || in_array('2',user_view()) || admin_id()!='' && admin_type()=='1')
	{ 
	?>
	<div class="col-md-12 col-sm-6">
	<?php 
	if(!isset($is_card))
	{
	?>
	<a data-toggle="modal" class="btn blue show_credit_card">Display Credit Card Details <i class="fa fa-credit-card"></i></a> 
	<?php 
	}  
	?>
	<a id="pass_succ" data-toggle="modal" data-target="#myModal234" href="javascript:;" style="visibility:hidden"></a>
	<?php 
	if(unsecure($curr_cha_id) == 2)
	{ 
	?>
		<a href="<?php echo lang_url();?>reservation/bookingreport/cc/<?php echo insep_encode($reservationid);?>/<?php echo insep_encode($RESER_ID); ?>" class="btn yellow"> Notify to Booking.com - Invalid CC details </a>
		<a href="<?php echo lang_url();?>reservation/bookingreport/noshow/<?php echo insep_encode($reservation_code);?>/<?php echo insep_encode($RESER_ID); ?>" class="btn red"> Notify to Booking.com - No show </a>
	<?php 
	} 
	?>
	</div>
	<?php 
	} 
	?>
     
    <div class="portlet-body">
    <div class="tabbable">
      <ul class="nav nav-tabs nav-tabs-lg">
        <li class="active">
          <a href="#tab_1" data-toggle="tab">
          Details </a>
        </li>
         <li>
          <a href="#tab_2" data-toggle="tab">
          Invoices <span class="badge badge-success">
          <?php if($invoice_count!=0){echo $invoice_count;}else{ echo 0;} ?>
           </span>
          </a>
        </li>
        <li>
          <a href="#tab_3" data-toggle="tab">
          Emails </a>
        </li>
        <li>
            <a href="#tab_4" data-toggle="tab">
          Extras <span class="badge badge-success">
           <?php if($extra_count!=0){echo $extra_count;}else{echo 0;} ?> </span>
          </a>
        </li>
        <li>
          <a href="#tab_5" data-toggle="tab">
          History </a>
        </li>
      </ul>
      
      <div class="tab-content">
			<!--Details Section Start-->
			<div class="tab-pane active" id="tab_1">
          
          		<div class="row">
            
                    <div class="col-md-6 col-sm-12">
                    
                    <div class="portlet blue-hoki box">
                        <div class="portlet-title">
                          <div class="caption">
                            <i class="fa fa-calendar"></i>Reservation Details
                          </div>
                          <!--<div class="actions">
                            <a href="#myModal_autocomplete" role="button" class="btn btn-default" data-toggle="modal">
                            <i class="fa fa-pencil"></i> Edit </a>
                          </div>-->
                        </div>
                        <div class="portlet-body">
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Reservation ID #:
                            </div>
                            <div class="col-md-7 value">
                            <?php 
                           if(unsecure($curr_cha_id) == 2){
                            echo $reservationid; 
                          }else{
                            echo $reservation_code;
                          }
                            ?> <!--<span class="label label-info label-sm">
                              Email confirmation was sent </span>-->
                            </div>
                          </div>
                          <?php if(unsecure($curr_cha_id) == 2){ ?>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                            
                              Room Reservation ID #:
                            </div>
                            <div class="col-md-7 value">
                           <?php echo $reservation_code; ?> <!--<span class="label label-info label-sm">
                              Email confirmation was sent </span>-->
                            </div>
                          </div>
                          <?php } ?>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Channel:
                            </div>
                            <?php $channel = unsecure($curr_cha_id); ?>  <!--  Need to set dynamic id-->
                            <div class="col-md-7 value">
                              
                              <?php if($channel==0){echo 'Manual Booking';}
                              else{
                                $channel_name = $this->reservation_model->get_channel_name($channel);
                                echo $channel_name->channel_name;} ?>
                            </div>
                          </div>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Room:
                            </div>
                            <div class="col-md-7 value">
							<?php 
							if($channel == 1)
							{
								$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$channel,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeId,'rate_type_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;

								if(!$roomName)
								{
									$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$channel,'import_mapping_id'=>get_data('import_mapping',array('roomtype_id'=>$roomtypeId,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->map_id))->row()->property_id))->row()->property_name;
								}
							}
							elseif($channel == 11)
							{
								$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$channel,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$ROOMCODE,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->re_id))->row()->property_id))->row()->property_name;
							}
							else if($channel == 8)
							{
								$roomName = get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$channel,'import_mapping_id'=>get_data(IM_GTA,array('ID'=>$ROOMCODE,'rateplan_id'=>$rateplanid,'user_id'=>current_user_type(),'hotel_id'=>hotel_id()))->row()->GTA_id))->row()->property_id))->row()->property_name;
							}
							else if($channel == 5)
							{
								$htb_name = "";
								if(strpos($room_id, ',') !== FALSE){
								  $ids = explode(',', $room_id);
								  for($i=0; $i<count($ids); $i++){
									$name = get_data(TBL_PROPERTY,array('property_id'=>$ids[$i]));
									if($name->num_rows != 0){
									  $htb_name .= $name->row()->property_name." "; 
									}
								  }
								  if($htb_name != ""){
									$roomName = $htb_name;
								  }
								}else{
								  $name = get_data(TBL_PROPERTY,array('property_id'=>$room_id));
								  if($name->num_rows != 0){
									$roomName = $name->row()->property_name;
								  }
								}
							}
							else if($channel ==2)
							{
								$map = get_data('import_mapping_BOOKING',array('B_room_id'=>$roomtypeId,'B_rate_id'=>$rateplanid,'owner_id'=>current_user_type(),'hotel_id' => hotel_id()));
								if($map->num_rows != 0)
								{
									$map_id = $map->row()->import_mapping_id;
									$prop_id = get_data(MAP,array('channel_id'=>$channel,'import_mapping_id'=>$map_id));
									if($prop_id->num_rows != 0)
									{
										$prop_id = $prop_id->row()->property_id;
										$roomName = get_data(TBL_PROPERTY,array('property_id'=>$prop_id))->row()->property_name;
									}	
								}
							}
							else if($channel==17)
							{
								 $roomName = @get_data(TBL_PROPERTY,array('property_id'=>$ROOMCODE))->row()->property_name;
							}
              else if($channel==15)
              {
                 $roomName = @get_data(TBL_PROPERTY,array('property_id'=>$ROOMCODE))->row()->property_name;
              }
                            if(isset($roomName))
                            {
                            	echo ucfirst($roomName);
                            }
                            else
                            {
                            	echo '"No Room Set"';
                            }
                            ?>
                            </div>
                          </div>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Booking Status:
                            </div>
                            <div class="col-md-7 value">
                              <span class="label label-success">
                              <?php echo $status; ?> </span>
                            </div>
                          </div>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Check in:
                            </div>
                            <div class="col-md-7 value">
                              <?php echo $CHECKIN; ?> 
                            </div>
                          </div>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Check out:
                            </div>
                            <div class="col-md-7 value">	
                               <?php echo $CHECKOUT; ?> 
                            </div>
                          </div>
                          <div class="row static-info">
        
                            <div class="col-md-5 name">
                               Number of nights:
                            </div>
                            <div class="col-md-7 value">
                              <?php echo $nig;?>
                            </div>
                          </div>
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Number of Adults:
                            </div>
                            <div class="col-md-7 value">
                              <?php echo $ADULTS; ?>
                            </div>
                          </div>
                          
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Number of Children:
                            </div>
                            <div class="col-md-7 value">
                              <?php echo $CHILDREN; ?>
                            </div>
                          </div>
                          
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Number of Crib:
                            </div>
                            <div class="col-md-7 value">
                              <?php echo $CRIB; ?>
                            </div>
                          </div> 
                          <div class="row static-info">
                            <div class="col-md-5 name">
                               Grand Total:
                            </div>
                             
                            <div class="col-md-7 value">
                              <?php echo $CURRENCY;?> <?php
                              if(isset($grandtotal)){

                               echo $grandtotal; 
                              }else{
                                echo $subtotal;
                              }
                              ?> 
                            </div>
                          </div>
                        </div>
                      </div>
                    
                    </div>
            
           		 	<div class="col-md-6 col-sm-12">
              <div class="portlet blue-hoki box">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-user"></i>Guest Information
                  </div>
                  <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
                 <!-- <div class="actions">
                    <a href="#myModal_autocomplete2" role="button" class="btn btn-default" data-toggle="modal">
                    <i class="fa fa-pencil"></i> Edit </a>
                  </div>-->
                  <?php } ?>
                  
                </div>
                <div class="portlet-body">
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       Guest Name:
                    </div>
                    <div class="col-md-7 value">
                      <?php echo $guest_name; ?>
                    </div>
                  </div>
				  <?php if(isset($booker_name)){?> 
				  <div class="row static-info">
                    <div class="col-md-5 name">
                       Booker Name:
                    </div>
                    <div class="col-md-7 value">
                      <?php echo $booker_name; ?>
                    </div>
                  </div>
				  <?php } ?>
                  <?php if($channel == 2){ ?>
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       Booker Name:
                    </div>
                    <div class="col-md-7 value">
                      <?php echo $booker_name; ?>
                    </div>
                  </div>
                  <?php } ?> 
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       Email:
                    </div>
                    <div class="col-md-7 value">
                       <a href="mailto:<?php echo $email;?>"><?php echo $email; ?> </a>
                    </div>
                  </div>
                   <div class="row static-info">
                    <div class="col-md-5 name">
                       Address:
                    </div>
                    <div class="col-md-7 value">
                     <?php echo $street_name;?>
                    </div>
                  </div> 
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       City:
                    </div>
                    <div class="col-md-7 value">
                       <?php echo $city_name; ?>
                    </div>
                  </div>
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       Country:
                    </div>
                    <div class="col-md-7 value">
                      <?php echo  $country; ?>

                    </div>
                  </div>
                  <div class="row static-info">
                    <div class="col-md-5 name">
                       Phone Number:
                    </div>
                    <div class="col-md-7 value">
					 <?php echo  $phone; ?>
                    </div>
                  </div>
                </div>
              </div>
  </div>
  

					
            
				</div>

    			<div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="portlet blue-hoki box">
              <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-info"></i>Additional Channel Details
              </div>
            
            </div>

            <div class="portlet-body">
              <div class="row static-info">
                <div class="col-md-5 name">
                   COMMISSION:
                </div>
                <div class="col-md-7 value">
     			        <?php echo  $commission; ?>
                </div>
              </div>
              <?php if(isset($Contract_Details)){?> 
              <div class="row static-info">
                <div class="col-md-5 name">
                   Contract:
                </div>
                <div class="col-md-7 value">
                  <?php echo  $Contract_Details; ?>
                </div>
              </div>
              <?php } ?>
			  <?php if(isset($channel_room_name)){?> 
              <div class="row static-info">
                <div class="col-md-5 name">
                   Channel Room Name:
                </div>
                <div class="col-md-7 value">
                  <?php echo  $channel_room_name; ?>
                </div>
              </div>
              <?php } ?>
			  <?php if(isset($roomcode)){?> 
              <div class="row static-info">
                <div class="col-md-5 name">
                   Room Code:
                </div>
                <div class="col-md-7 value">
                  <?php echo  $roomcode; ?>
                </div>
              </div>
              <?php } ?>
              <?php if(isset($ruid) != ""){ ?>
              <div class="row static-info">
                <div class="col-md-5 name">
                   RUID:
                </div>
                <div class="col-md-7 value" style="word-wrap: break-word;">
                  <?php echo  $ruid; ?>
                </div>
              </div>
              <?php } ?>
              <?php if(isset($promocode) != ""){ ?>
              <div class="row static-info">
                <div class="col-md-5 name">
                   Promo Code:
                </div>
                <div class="col-md-7 value">
                  <?php echo  $promocode; ?>
                </div>
              </div>
              <?php } ?>
                <div class="row static-info">
                <div class="col-md-5 name">
                   Meals Include:
                </div>
                <div class="col-md-7 value">
                    <?php
					           if(unsecure($curr_cha_id) == 11){
                        $char = array('0'=>'None', '1'=>'Continental Breakfast', '2'=>'Buffet Breakfast', '3'=>'Half Board', '4'=>'Full Board');
                        foreach($char as $letter => $number) 
                        {
                          if($mealsinc==$letter)
                          {
                            echo $number;
                            break;
                          }
                        }
                    }else{
                      if($mealsinc != ""){
                        echo $mealsinc;
                      }else{
                        echo "NONE";
                      }
                    }      
					?>
                </div>
              </div>
              <div class="row static-info">
                <div class="col-md-5 name">
                   Discount:
                </div>
                <div class="col-md-7 value">
                    -----
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div class="portlet green box">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-comment"></i>Notes
              </div>
              
            </div>
            <div class="portlet-body">
              <div class="row static-info">
                <div class="col-md-10">
            <?php if($description!='') { echo $description;}else { echo 'N/A';}?>
            </div>
              </div>
            </div>
          </div>
        </div>
		</div>
        
        		<div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="portlet grey-cascade box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-table"></i>Rate Details
                        </div>
                        
                      </div>
                      <div class="portlet-body">
            
            
                                 <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
						<?php if(is_numeric($subtotal))
							{ ?>
                          <th>
                            <center>
                             Day
                           </center>
                          </th>
						  <?php } ?>
                          <th>
                            <center>
                             Date
                             </center>
                          </th>
                          <th>
                            <center>
                             Room Rate
                           </center>
                          </th>
                          
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
						if($channel != 8 && $channel != 1 && $channel != 2 && $channel!=17 && $channel!=15 && $channel!=5)
						{ 
							$originalstartDate = date('M d,Y',strtotime($start_date));
							$newstartDate = date("Y/m/d", strtotime($originalstartDate));
							$originalendDate = date('M d,Y',strtotime($end_date));
							$newendDate = date("Y/m/d", strtotime($originalendDate));
							$begin = new DateTime($newstartDate);
							$ends = new DateTime($newendDate);
							$daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
							foreach($daterange as $ran)
							{
								$string = date('d-m-Y',strtotime(str_replace('/','-',$ran->format('M d, Y'))));
								$weekday = date('l', strtotime($string)); 
                           ?>
								<tr>
									<td><center><?php echo $weekday; ?></center></td>
									<td><center><span class="label label-sm label-success"><?php echo $ran->format('M d, Y'); ?></span></center></td>
									<td><center><?php echo $CURRENCY; ?>  <?php echo $channel=='5' ? number_format((float)$price/$nig, 2, '.', '') : $price; ?></center></td>
								</tr>
                        <?php 
							} 
						}
						else
						{
							if(is_numeric($subtotal))
							{
								if(isset($perdayprice))
								{
									foreach($perdayprice as $date => $value)
									{
										foreach ($value as $ran => $val) 
										{
											$string = date('M d, Y',strtotime(str_replace('/','-',$ran)));
											$weekday = date('l', strtotime($string)); 
									 ?>
									<tr>
										<td><center><?php echo $weekday; ?></center></td>
										<td><center><span class="label label-sm label-success"><?php echo $string; ?></span></center></td>
										<td><center><?php  echo $channel=='5' ? number_format((float)$val, 2, '.', '') : $val; //echo $val;  ?> <?php  echo $CURRENCY; ?></center></td>
									</tr>
								<?php	} 
									} 
								}
							}
							else
							{
							?>
							<td><center><span class="label label-sm label-success"><?php echo $start_date.' - '.$end_date; ?></span></center></td>
							<td><center><?php  echo $subtotal;?></center></td>
							<?php
							}
						} 
						?>
                        </tbody>
                        </table>
                        
                        </div>
                      </div>
                    </div>
                  </div>
          </div>
			<?php if(is_numeric($subtotal))
							{ ?>
                <div class="row">
      <div class="col-md-6">
      </div>
      <div class="col-md-6">
        <div class="well">
          <div class="row static-info align-reverse">
            <div class="col-md-8 name">
               Total:
            </div>
            <div class="col-md-3 value">
              <?php echo $subtotal; ?> <?php echo $CURRENCY; ?>
            </div>
          </div>
          <div class="row static-info align-reverse">
            <div class="col-md-8 name">
               Extras:
            </div>
            <?php  
			$total_extras       	= $this->reservation_model->total_extras($curr_cha_id,$RESER_ID);
			$total_tax_reservation  = $this->reservation_model->total_tax_reservation($curr_cha_id,$RESER_ID);
            if($total_extras!=''){
                  $add = 0;
                $total_extras_result = $this->reservation_model->total_extras_result($curr_cha_id,$RESER_ID);
                if($total_extras_result){
                  foreach($total_extras_result as $total){
                      $total_ex = $total->amount;
                      $add = $total_ex + $add;  
                    }
            ?>
            <div class="col-md-3 value">
               <?php echo $add; ?> <?php $CURRENCY; ?>
            </div>
             <?php } }
                else
                { ?>
                  <div class="col-md-3 value">
                     <?php echo $add=0; ?> <?php echo $CURRENCY; ?>
                  </div>
                
               <?php }?>
          </div>

          <div class="row static-info align-reverse">
            <?php
              if($total_tax_reservation!=0 )
              {
                $taxes = get_data(R_TAX,array('hotel_id'=>hotel_id(),'user_id'=>user_id(),'reservation_id'=>$RESER_NUMBER,'channel_id'=>unsecure($curr_cha_id),'tax_included'=>'0'))->result_array();
              if(count($taxes)!=0)
              {
              $total_price = $subtotal;
              foreach($taxes as $valuue)
              {
              extract($valuue);
              $total_price=$total_price + $subtotal * $tax_price / 100;
             ?>
            <div class="col-md-8 name">
             <?php echo $tax_name;?> (<?php echo $tax_price;?>% Not Included) 
            </div>
            
            <div class="col-md-3 value">
              <?php echo $subtotal * $tax_price / 100;?> <?php echo $CURRENCY;?> 
            </div>
            <?php } }
            else
            {
              $total_price = '0';
            }
         }else if(isset($tax)){ ?>
          <div class="col-md-8 name">
            Tax:
          </div>
            
            <div class="col-md-3 value">
             <?php echo $tax; ?> <?php echo $CURRENCY; ?>
            </div>
         <?php }else if(isset($offer)){ ?>
          <div class="col-md-8 name">
            Offer:
          </div>
            
            <div class="col-md-3 value">
             <?php echo $offer; ?> <?php echo $CURRENCY; ?>
            </div>
         <?php }else if(isset($addon)){ ?>
            <div class="col-md-8 name">
            Addon:
          </div>
            
            <div class="col-md-3 value">
             <?php echo $addon; ?> <?php echo $CURRENCY; ?>
            </div>
            <?php }
           else
            {
              $total_price = '0';
            } 
    ?>
          </div> 

          <div class="row static-info align-reverse">
            <div class="col-md-8 name">
               <strong>Grand Total:</strong>
            </div>
            <?php if(isset($tax)){
              $tot = $subtotal + $tax + $add;
            }else if(isset($offer)){
              $tot = $subtotal + $offer + $add;
            }else if(isset($addon)){
                $tot = $subtotal + $addon + $add;
            }
            else{
              $tot = $subtotal + $add; 
            } ?>
            <div class="col-md-3 value">
                  <?php echo $tot; ?> <?php echo $CURRENCY; ?>
            </div>
          </div>

          <div class="row static-info align-reverse">
            <?php if($total_tax_reservation!=0)
            {
            $taxes = get_data(R_TAX,array('hotel_id'=>hotel_id(),'user_id'=>user_id(),'reservation_id'=>$RESER_NUMBER,'channel_id'=>unsecure($curr_cha_id),'tax_included'=>'yes'))->result_array();
            if(count($taxes)!=0)
            {
            $total_price = $subtotal;
            foreach($taxes as $valuue)
            {
            extract($valuue);
            $total_price=$total_price + $subtotal * $tax_price / 100;
            ?>
            <div class="col-md-8 name">
               <?php echo $tax_name;?> (<?php echo $tax_price;?>% Included in price):
            </div>
            <div class="col-md-3 value">
               <?php echo $subtotal * $tax_price / 100;?> <?php echo $CURRENCY;?> 
            </div>
            <?php } } } ?>
          </div> 
          <div class="row static-info align-reverse">
            <div class="col-md-8 name">
               Total Due:
            </div>
            <div class="col-md-3 value">
               <?php echo $tot; ?> <?php echo $CURRENCY; ?> 
            </div>
          </div>
        </div>
      </div>
    </div>
    
			    <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="portlet grey-cascade box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-money"></i>Add Payment
                        </div>
                      </div>
                      <div class="portlet-body">
            
                       <!--  <div class="btn-group">
                          <button id="sample_editable_1_new" class="btn green">
                            Add New <i class="fa fa-plus"></i>
                          </button>
                        </div> -->
            
            
              <div class="table-responsive">
            
                <form action="<?php echo lang_url(); ?>reservation/insert_payment/<?php echo insep_encode($RESER_ID); ?>" method="post" name="" id="insert_pay">
                <input type="hidden" name="curr_cha_id" value="<?php echo $curr_cha_id;?>" />
            
              <table class="table table-hover table-bordered table-striped">
              <thead>
              <tr>
              <th>Total</th>
              <th>Due</th>
              <th>Paid</th>
              <!--<th>Insert Payment</th>-->
              <th>Payment Method</th>
              <th>Notes</th>
              <th>Save</th>
              <th>Delete</th>
              </tr>
              </thead>
               <tbody> 
            <?php 
            if($add_pay_count!=0)
            { 
            	$paid=0;
				foreach($add_pay as $payment)
            	{ 
              ?>
             
              <tr>
            
              <?php 
                    $paid = $paid+$payment->paid_amount; 
                    $due = $tot-$paid;
					$remain = $due;
              ?>
            
              <td><?php echo $tot; ?> <?php echo $CURRENCY; ?></td>
            
              <td>
                <span class="label label-sm label-success">
                <?php
                      echo $due;
                    if($due < 0) 
                    {
                    echo "The due amount is negative";
                    }
                ?>
                </span>
              </td>
            
             <!-- <td><?php //echo $payment->paid_amount; ?> <?php //echo $reservation_channeldetails['CURRENCY']; ?></td>-->
            
              <td>
                <?php echo $payment->paid_amount; ?> <?php echo $CURRENCY; ?></td> 
            
              <td>
                 <span class="label label-sm label-success">
                 <?php echo $payment->payment_method; ?>
                  </span>
              </td>
            
              <td>
                <?php echo $payment->notes; ?>
              
              </td>
              
              <td>
            
              <a class="edit" href="javascript:;">
              Saved </a>
            
              </td>
            
              <td>
              <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
              <a class="delete" href="javascript:;" onclick="return delete_payment('<?php echo $payment->payment_id ?>','<?php echo $payment->reservation_id; ?>');">
              Delete </a>
              <?php } ?>
              </td>
              
              </tr>
              <?php } 
			}
			else 
			{ 
				$due=$tot; 
				$paid='0';
				$remain = $tot - $paid;
			?> <tr> <td colspan="8" align="center" class="text-info"> No Payment History Found!!! </td></tr>
            <?php 
			} ?>
            <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1'){ 
			if($remain!=0)
			{
			?>
            <tr>
            
              <td><input type="hidden" name="total_amount" id="total_amount" value="<?php echo $tot; ?>">
			  	<?php echo $tot; ?> <?php echo $CURRENCY; ?></td>
            
              <td><span class="label label-sm label-success" name="due_amount"></span></td>
              
              <td><input type="text" name="paid_amount" class="form-control"  id="paid_amount" value="" min="1"></td>
               
             <!-- <td><input class="form-control" id="insert_payment" placeholder="0.0" name="insert_payment" value="" type="text"></td>-->
            
              <td>
              <select class="form-control" name="payment_method" id="payment_method">
              <option selected="selected" value="Cash">Cash</option>
              <option value="Credit Card">Credit Card</option>
              <option value="Prepaid">Prepaid</option>
              <option value="Bank Transfer">Bank Transfer</option>
              <option value="Cheque">Cheque</option>
              </select>
              </td>
            
              <td>
              <textarea class="form-control maxlength-handler" rows="2" name="notes" id="notes" value="" maxlength="200">
              </textarea>
              </td>
              <td>
              <a class="edit" href="javascript:;" onclick="return post_form('<?php echo $due; ?>');">
              Save </a>
              </td>
              <td>
              <a class="delete" href="javascript:;">
              Delete </a>
              </td>
              
              </tr> 
            <?php }  
			}?>
            </tbody>
            </table>
            </form>
              </div>
              </div>
              </div>
              </div>
 				</div>
            <?php } ?> 
			</div>
            <!--Details Section End-->
            
            <!--Invoice Section Start-->
            <div class="tab-pane" id="tab_2">
  <div class="table-container">

  <div class="portlet-body">
  <div class="table-toolbar">
  <div class="row">
  <div class="col-md-6">
  <div class="btn-group">
<?php 
if(unsecure($curr_cha_id)=='11')
{
	$reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='1')
{
	$reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='8')
{
	$reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='5')
{
  $reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='2')
{
  $reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='17')
{
  $reservation_id = $RESER_ID;
}
else if(unsecure($curr_cha_id)=='15')
{
  $reservation_id = $RESER_ID;
}
if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
{
	if($invoice_count==0)
	{
?>
<a href="<?php echo lang_url();?>reservation/invoice_create/<?php echo $curr_cha_id.'/'.insep_encode($reservation_id);?>" target="_blank" class="btn green">Add new <i class="fa fa-plus"></i></a>
<?php } 
}
?>
  </div>
  </div>

  </div>
  </div>
  </div><table class="table table-striped table-bordered table-hover" id="datatable_invoices">
  <thead>

  </thead></table><table class="table table-striped table-hover table-bordered" id="sample_editable_1">
  <thead>
  <tr>
  <th>
  Invoice Number
  </th>
  <th>
  Created Date
  </th>
  <th>
  Invoice Total
  </th>
  <th>
  Amount Due
  </th>
  <th>
  Edit
  </th>
  <th>
  Delete
  </th>
  </tr>
  </thead>
  <tbody>
   <?php if($invoice){ 
    /*  echo '<pre>';
      print_r($invoice);die;*/
      ?>
  <tr>
  <td>
  <?php echo $invoice->invoice_id; ?>

  </td>
  <td>
  <?php echo $invoice->created; ?>
  </td>
  <td>
  <?php echo $tot; ?>
  </td>
  <td class="center">
   <?php if(isset($due)){echo $due;}else{echo 0;} ?>
  </td>
  <td>
  <?php
  if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
{
	?>
<a class="edit" href="<?php echo lang_url(); ?>reservation/invoice_edit/<?php echo $curr_cha_id.'/'.insep_encode($reservation_id); ?>">
  Edit </a>
  <?php } ?>
  </td>
  <td>
  <?php 
  if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
{?>
<a class="delete" href="javascript:;" onclick="return invoice_delete('<?php echo $invoice->id; ?>','<?php echo $reservation_id; ?>');">
  Delete </a>
  <?php } ?>
  </td>
  </tr>
<?php } else{?>
  </tbody>
  </table>

  <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
    <tbody>
      <div class="alert alert-danger">
        <center>
        No Invoices Found !!!
      </center>
      </div>
      <?php } ?>
    </tbody>
   </table>
  </div>




  </div>
			<!--Invoice Section End-->
            
            <!--Email Section Start-->
            <div class="tab-pane" id="tab_3">
            <div class="table-container">
            <div class="row">
            <div class="col-md-12">
            <div class="portlet box blue-hoki">
            <div class="portlet-title">
            <div class="caption">
              <i class="fa fa-envelope"></i>Guest Welcome Email
            </div>
            
            </div>
            <div class="portlet-body">
            <form action="<?php echo lang_url();?>reservation/welcome_email" id="welcome_email" method="post">
            <input type="hidden" value="<?php echo insep_encode($reservation_id);?>" name="reservation_id"/>
            <input type="hidden" name="curr_cha_id" value="<?php echo $curr_cha_id; ?>">
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
            <label class="control-label" for="title">Email</label>
             <input id="title" class="form-control" placeholder="Enter an email address. If none entered, then the reservation's email will be used ..." type="email" name="user_email" value="<?php echo $email; ?>" <?php if($email != "") { ?>readonly="readonly" <?php } ?>>
            </div>
            <div class="form-group">
            <label class="control-label" for="title">Title</label>
            <input id="title" class="form-control" placeholder="Enter an email subject ..." type="text" name="email_title" value="<?php if(isset($welcome['email_title'])){ echo $welcome['email_title'];}?>">
            </div>
            <div class="form-group">
            <label class="control-label" for="message">Message</label>
            <textarea class="form-control" id="message" rows="5" placeholder="Enter a text that you want to send. For example ask the arrival time or to thank the guest for booking at your hotel ..." name="email_message"><?php if(isset($welcome['email_message'])){ echo $welcome['email_message'];}?></textarea>
            </div>
            <div class="form-group">
            <div class="checkbox-list">
              <label for="closeButton">
              <input id="closeButton" value="1" checked="checked" class="input-small" type="checkbox" name="copy_message[]">Copy email to your address </label>
              <label for="addBehaviorOnToastClick">
              <input id="addBehaviorOnToastClick" value="2" class="input-small" type="checkbox" name="copy_message[]">Send 2 days after departure </label>
            </div>
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
            <?php
            if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
            {
            ?>
            <button type="submit" class="btn green" id="showtoast">Send Welcome Email</button>
            <button type="reset" class="btn red" id="cleartoasts">Reset</button>
            <?php } ?>
            </div>
            </div>
            </form>
            </div>
            </div>
            </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <div class="portlet box blue-hoki">
                        <div class="portlet-title">
                          <div class="caption">
                            <i class="fa fa-envelope"></i>Reminder Email
                          </div>
                          
                        </div>
                  <div class="portlet-body">
                  <form action="<?php echo lang_url();?>reservation/reminder_email" id="reminder_email" method="post">
                  <input type="hidden" value="<?php echo insep_encode($reservation_id);?>" name="reservation_id"/>
                  <input type="hidden" name="curr_cha_id" value="<?php echo $curr_cha_id; ?>">
                  <input type="hidden" name="mail_type" id="mail_type"/>
                    <div class="row">
                      <div class="col-md-12">
            
                      <div class="form-group">
                          <label class="control-label" for="title">Email</label>
                          <input id="title" class="form-control" placeholder="Enter an email address. If none entered, then the reservation's email will be used ..." type="email" name="user_email" <?php if($email != ""){?> readonly="readonly" <?php } ?> value="<?php echo $email; ?>">
                        </div>
            
                        <div class="form-group">
                          <label class="control-label" for="title">Title</label>
                          <input id="title" class="form-control" value="<?php if(isset($remainder['email_title'])){ echo $remainder['email_title'];}?>" placeholder="Enter an email subject ..." type="text" name="email_title"<?php if(isset($welcome['email_title'])){ echo $welcome['email_title'];}?>>
                        </div>
            
                         <div class="form-group">
                          <label class="control-label" for="message">Message</label>
                          <textarea class="form-control" id="message" rows="10" placeholder="Write here some standard info that you want to send to your guest (information on arrival time or other usefull info that will be sent by default" name="email_message">Dear <?php if(isset($guest_name)){echo $guest_name; }?>
            
            Please remember your booking No. <?php if(isset($reservation_code)){echo $reservation_code; }?>
            
            Check in: <?php if(isset($start_date)){echo $start_date; }?> 
            Check out: <?php if(isset($end_date)){echo $end_date; }?>
            
            <?php //$get_property_name = $this->reservation_model->get_property_name($reservation_details['room_id']); ?>
            
            Best regards from <?php echo ucfirst(get_data(HOTEL,array('hotel_id'=>hotel_id()))->row()->property_name); ?>
            
            <?php //echo $get_property_name->property_name; ?>
            
            <?php echo $bill->address; ?>
            
            <?php echo $bill->mobile; ?>
            
            <?php echo $bill->email_address; ?>
            </textarea>
                        </div>
                        
                        <div class="form-group">
                          <div class="checkbox-list">
                            <label for="closeButton">
                            <input id="closeButton" value="1" <?php if(isset($remainder['copy_message'])!='') { if($remainder['copy_message']==1){ ?> checked="checked" <?php } } else { ?> checked="checked"<?php } ?> class="input-small" type="checkbox" name="copy_message[]">Copy email to your address </label>
                            
                            
                          </div>
                            <label class="col-md-6 control-label">How many days before arrival to send email automatically?</label>
                          <div class="col-md-3">
                            <select class="form-control input-sm" name="remainder_days">
                              <option selected="selected">---</option>
                              <?php
                              for($d=2; $d<=15; $d++)
                              {
                              ?>
                              <option value="<?php echo $d;?>" <?php if(isset($remainder['remainder_days'])!='') { if($remainder['remainder_days']==$d){?> selected="selected" <?php } } ?>><?php echo $d;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
						<?php
                        if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
                        {
                        ?>
                        <button type="submit" class="btn blue mail_type" id="save">Save Settings</button>
                        <button type="submit" class="btn green mail_type" id="send">Send Email Now</button>
                        <button type="reset" class="btn red" id="clearlasttoast">Reset</button>
                        <?php
                        }
                        ?>
                      </div>
                    </div>
                    </form>
                    
                  </div>
                </div>
              </div>
            </div>
                          </div>
            </div>
            <!--Email Section Start-->
            
	  		<!--Extras Section End-->
            <div class="tab-pane" id="tab_4">
        <div class="table-container">
        <div class="portlet-body">
       <div class="table-toolbar">
        <div class="row">
          <div class="col-md-6">
            <div class="btn-group">
            <?php
			if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
			{
			?>
              <a href="#myModal_autocomplete3" role="button" class="btn green" data-toggle="modal"> Add new <i class="fa fa-plus"></i></a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
      <thead>
      <tr>
        <th>
           #
        </th>
        <th>
           Date
        </th>
        <th>
           Description
        </th>
        <th>
           Amount
        </th>
        <th>
           Edit
        </th>
        <th>
           Delete
        </th>
      </tr>
      </thead>
      <tbody>
        <?php if($extra){ $i=0;
              foreach($extra as $ex){ $i++;
         ?>
      <tr>
        <td>
        <?php echo $i; ?>
        </td>
        <td>
          <?php echo date('M d,Y',strtotime(str_replace('/','-',$ex->extra_date))); ?>
        </td>
        <td>
           <?php echo $ex->description; ?>
        </td>
        <td class="center">
          <?php echo $curr_cha_currency; ?> <?php echo $ex->amount; ?>
        </td>
        <td>
        <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
			{?>
          <a href="#javascript:;" onclick="return edit_extras('<?php echo $ex->extra_id; ?>')">
          Edit </a>
          <?php } ?>
        </td>
        <td>
        <?php if(user_type()=='1' || user_type()=='2' && in_array('2',user_edit()) || admin_id()!='' && admin_type()=='1')
			{?>
          <a class="delete" href="javascript:;" onclick="return delete_extras('<?php echo $ex->extra_id; ?>','<?php echo $ex->reservation_id; ?>','<?php echo $ex->description; ?>')">
          Delete </a>
          <?php } ?>
        </td>
      </tr>
      <?php } }else{ ?>
      </tbody>
      </table>
      <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
        <tbody>
      <div class="alert alert-danger">
        <center>
        No Extras Found!!!
      </center>
      </div>
        <?php } ?>
      </tbody>
      </table>
    </div>  
    </div>
    </div>
		 
            <div id="myModal_autocomplete3" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
          <h4 class="modal-title">Extras</h4>
        </div>
        <div class="modal-body form">
          <form action="<?php echo lang_url(); ?>reservation/add_extras" method="post" class="form-horizontal form-row-seperated" id="add_extras">
            
      <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
         <input type="hidden" name="curr_cha_id" value="<?php echo $curr_cha_id; ?>">
        
          <div class="form-body">
            
            <div class="form-group">
              <label class="control-label col-md-3">Description <span class="errors">*</span></label>
              <div class="col-md-9">
                <input class="form-control" name="description" placeholder="Description" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Price <span class="errors">*</span></label>
              <div class="col-md-9">
                <input class="form-control" name="price" placeholder="65.5" type="text">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="add" value="save" class="btn btn-primary"><i class="fa fa-check"></i> Add</button>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
                        
			<div id="myModal_autocomplete4" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title">Edit Extras</h4>
            </div>
            <div class="modal-body form">
              <form action="<?php echo lang_url(); ?>reservation/edit_extras" method="post" class="form-horizontal form-row-seperated" id="edit_extra">
                


          <div class="portlet-body form">
            <!-- BEGIN FORM-->
            
              <div class="form-body" id="selectextra">
              
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name="save" value="edit" class="btn btn-primary"><i class="fa fa-check"></i> Save changes</button>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
			<!--Extras Section End-->
            
            <!--History Section Start-->
            <div class="tab-pane" id="tab_5">
              <div id="history-timeline">
                <h2>TODAY</h2>
                 <?php 
                $get_history_details = $this->reservation_model->get_history_details($curr_cha_id,$reservation_id);
                if($get_history_details){
                foreach($get_history_details as $hist){
                 ?>
                    <div class="event">
        
                    <div class="left-column">
                    <time>
        
        
                    <span class="date"> <?php echo date('M d,Y',strtotime(str_replace('/','-',$hist->history_date))); ?></span>
                    <span class="clock"> <?php echo date('g:i a',strtotime(str_replace('/','-',$hist->history_date))); ?></span>
                    </time>
                    </div>
                    <div class="right-column">
                    <div class="description">
                    <p><b><?php echo $guest_name; ?>  </b> 
                      <?php 
                    if($hist->amount=='' && $hist->extra_id==0 && $hist->description=='')
                    {   
                        echo 'Guest Update Info '.  $room->description;
                    }
                    else if($hist->extra_id && $hist->amount)
                    {
                    echo $hist->description. 'Edit the Extras Old Amount  ' .'<del class="errors">'.$hist->old_amount.'</del>'. ' New Amount  '.  $hist->amount;
                    }
                    else if($hist->extra_id==0)
                    {
                    echo 'Add Extras '.   $hist->description;
                    } 
                    else if($hist->amount=='')
                    {
                    echo 'Remove the Extras Details '.  $hist->description;
                    }
                    ?> </p>
                    </div>
                    </div>
        
                    </div>
                    <?php } }else{ ?>
        
                    <div class="note note-danger">
                    <h4 class="block"> No History Found</h4>
                    </div>
                    <?php } ?>
                    </div>
         	</div>
          	<!--History Section Start-->
    </div>
    <!-- End: life time stats -->
  </div>
</div>
<!-- END PAGE CONTENT INNER -->
</div>
</div>

</div>

</div>
</div>
</div>



<script>

function resend_confirm(id,method){
	 if(method=='cancel')
	 {
	 	var can_options = $('input[name=can_op]:checked', '#cancel_form').val()
	 }
	 else
	 {
		 var can_options ='';
	 }
	 $.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/resend_confirmation",
		data:{id,method,can_options},
		success:function(msg){
			location.reload();
		}
	 });
	 return false;
 }
 
function edit_extras(id){
// alert('sdsdd');
  $.ajax({
    type:"POST",
    url:"<?php echo lang_url(); ?>reservation/get_extras",
    data:"extra_id="+id,
    success:function(msg){
      $('#selectextra').html(msg); 
      $('#myModal_autocomplete4').modal({backdrop: 'static',keyboard: false});
    }
  });
    return false;
}
</script>

<script>
function delete_extras(id,res,des){
  // alert(res);
 if(confirm('Are u sure want to Delete this Extras Details?')){
    $.ajax({
      type:"POST",
      url:"<?php echo lang_url(); ?>reservation/delete_extra",
      data:{"extra_id":id,"reservation_id":res,"description":des},
      success:function(msg){
        location.reload();
      }
    });
    return false;
 }
 else{
  return false;
 }
}
</script>

<script>
function update_note(id,value){
  // alert(id);
  $.ajax({
    type:"POST",
    url:"<?php echo lang_url(); ?>reservation/update_notes",
    data:{"reservation_id":id,"user_note":value},
    success:function(msg){
		if(parseInt(msg)==0)
		{
			location.href("<?php echo lang_url();?>");
		}
      // alert(msg);
      //location.reload();
    }
  });
  return false;

} 

</script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/jquery.min.js"></script> 
<script type='text/javascript'>

  $('#password_check').click(function(){
    var val=$('#password_credit').val();
    var res_id = $("#resr_id").val();
    var channel_id = $("#channelid").val();
    if(val!="" && res_id != "" && channel_id != ""){
    $.ajax({
    type:"POST",
    dataType:"json",
    url:"<?php echo lang_url(); ?>reservation/password_check",
    data: {"password":val,"resr_id":res_id,"channel_id":channel_id},
    success:function(msg){
      var res=$.trim(msg);
      if(msg=="no"){
        $('#err_msg').html('Password Incorrect');
      }else if(msg=="noo"){
        $('#err_msg').html("You don't have permission to access");
      }else{
        $('#c_number').html(msg['CC_NUMBER']);
        $('#c_name').html(msg['CC_NAME']);
        $('#res_exp_month').html(msg['CC_DATE']);
        $('#res_exp_year').html(msg['CC_YEAR']);
        if(msg['CC_TYPE']){
          $("#c_type").html(msg['CC_TYPE']);
        }
        $('#m12close')[0].click();
        $('#pass_succ')[0].click();
      }
    }
    });
  }
  });


    function post_form(id)
    {
      var paid_amount = parseInt(document.getElementById('paid_amount').value);
      if(paid_amount > parseInt(id))
      {
        alert('Please Enter Correct Amount');
        return false; 
      } 
      else
      {
        $('#insert_pay').submit();
        return false;
      }
    }

function delete_payment(id,value){
  if(confirm('Are u sure want to Delete this Payment Details'))
  {
  $.ajax({
    type:"POST",
    url:"<?php echo lang_url(); ?>reservation/delete_payment",
    data: {"payment_id":id,"reservation_id":value},
    success:function(msg){
      location.reload();
    }
  });
  return false;
}
else
{
  return false;
}
}
</script>
<script>
function invoice_delete(id,value)
{
  if(confirm('Are You sure want to Delete this Invoice Details'))
  {
  $.ajax({
      type:"POST",
      url:"<?php echo lang_url(); ?>reservation/invoice_delete",
      data:{"invoice_id":id,"reservation_id":value},
      success:function(msg)
      {
        // alert(msg);
        location.reload();
      }
  });
      return false;
    }
    else
    {
      return false;
    }
}
</script>

<script>
function update_reservation()
{
  // alert('dfdjjhgj');
  $.ajax({
    type:"POST",
    url:"<?php echo lang_url(); ?>reservation/update_reservation",
    data:$('#update_res').serialize(),
    success:function(msg)
    {
      $("#myModal_autocomplete").modal('hide');
      $('#myModal_autocomplete').modal({backdrop: 'static', keyboard: false});
      location.reload();
    }
  });
  return false;
}
</script>

