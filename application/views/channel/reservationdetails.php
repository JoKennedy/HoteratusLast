<!--outter-wp-->
<?php extract($reservationdetails)?>
<div class="outter-wp">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>reservation/reservationlist">Reservation List</a></li>
            <li class="active">Reservation Details</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
        <img src="data:image/png;base64,<?=$LogoReservation?>" alt=" " />
        <h2 style="color: black;"> Reservation from <?=$ChannelName?> </h2>
        <strong>Reservation Number</strong>
        <p class="text-muted">
            <?=$reservationNumber?>
        </p>
        <p class="text-muted">
            <strong>Check in:</strong>
            <?php
            $date = date_create($checkin);
            echo date_format($date, 'M d,Y');
            ?><strong>     
            Check out:</strong>
                <?php
            $date = date_create($checkout);
            echo date_format($date, 'M d,Y');
            ?>
        </p>
        <a class="btn <?=($statusId==0 || $statusId==3 ?'red':($statusId==1 || $statusId==4 ?'green':($statusId==2?'yellow':'blue')))?> six">
            <?=$status?>
        </a>
    </div>
    <div class="tab-main">
        <div class="tab-inner">
            <div id="tabs" class="tabs">
                <div class="">
                    <nav>
                        <ul>
                            <li><a href="#section-1" class="icon-shop"><i class="fa fa-info-circle"></i> <span>Details</span></a></li>
                            <li><a href="#section-2" class="icon-cup"><i class="fa fa-file-text-o"></i> <span>Invoices</span></a></li>
                            <li><a href="#section-3" class="icon-food"><i class="fa fa-envelope"></i> <span>Emails</span></a></li>
                            <li><a href="#section-4" class="icon-lab"><i class="fa fa-plus"></i> <span>Extras</span></a></li>
                            <li><a href="#section-5" class="icon-truck"> <i class="lnr lnr-history"></i><span> History</span></a></li>
                        </ul>
                    </nav>
                    <div class="content tab">
                        <section id="section-1">
                            <div style="text-align: center;">
                                <a class="btn green two">Edit Reservation</a>
                            </div>
                            <div class="col-md-6 profile-info">
                                <h3>Reservation Details </h3>
                                <div class="main-grid3">
                                    <div class="p-20">
                                        <div class="about-info-p">
                                            <strong>Room Type</strong>
                                            <p class="text-muted">
                                                <?=$roomTypeName?>
                                            </p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Room Number</strong>
                                            <p class="text-muted">
                                                <?=$roomNumber?>
                                            </p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Arrival Time</strong>
                                            <p class="text-muted">
                                                <?=($arrivalTime==''?'None':$arrivalTime)?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Number of night<?=($numberNight>1?'s':'')?></strong>
                                            <p class="text-muted">
                                                <?=$numberNight?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Number of Adults</strong>
                                            <p class="text-muted">
                                                <?=$numberAdults?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Number of Child</strong>
                                            <p class="text-muted">
                                                <?=$numberChilds?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Additional Channel Details</div>
                                    <div class="panel-body">
                                        <div class="about-info-p m-b-0">
                                            <strong>COMMISSION</strong>
                                            <p class="text-muted">
                                                <?=$commision?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Channel Room Name</strong>
                                            <p class="text-muted">
                                                <?=$channelRoomName?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Promo Code</strong>
                                            <p class="text-muted">
                                                <?=$promoCode?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Meals Include</strong>
                                            <p class="text-muted">
                                                <?=$mealsInclude?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Discount</strong>
                                            <p class="text-muted">
                                                <?=$discount?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 profile-info two">
                                <h3>Guest Information</h3>
                                <div class="main-grid3">
                                    <div class="p-20">
                                        <div class="about-info-p">
                                            <strong>Guest Name</strong>
                                            <p class="text-muted">
                                                <?=$guestFullName?>
                                            </p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Email</strong>
                                            <p class="text-muted">
                                                <?=$email?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Phone Number</strong>
                                            <p class="text-muted">
                                                <?=$mobiler?>
                                            </p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Address</strong>
                                            <p class="text-muted">
                                                <?=$address?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>City</strong>
                                            <p class="text-muted">
                                                <?=$city?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>State</strong>
                                            <p class="text-muted">
                                                <?=$state?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Country</strong>
                                            <p class="text-muted">
                                                <?=$country?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Zip Code</strong>
                                            <p class="text-muted">
                                                <?=$zipCode?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 graph-2 second">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Notes (Max 1000 chars)</div>
                                    <div class="panel-body">
                                        <p>
                                            <?=$notes?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="graph-visual tables-main">
                                <h2 class="inner-tittle">Rate Details</h2>
                                <div class="graph">
                                    <div class="tables">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Date</th>
                                                    <th>Room Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $begin = new DateTime($checkin);
                                                $ends = new DateTime($checkout);
                                                $daterange = new DatePeriod($begin, new DateInterval('P1D'), $ends);
                                                $i=0;
                                                foreach($daterange as $ran)
                                                {
                                                    $pricede = $rateDetailsPrice[$i];

                                                    $i++;
                                                    $string = date('d-m-Y',strtotime(str_replace('/','-',$ran->format('M d, Y'))));
                                                    $weekday = date('l', strtotime($string));

                                                

                                                    echo '  <tr class="'.($i%2?'active':'success').'">
                                                                <td>'.$weekday.'</td>
                                                                <td>'.$ran->format('M d, Y').'</td>
                                                                <td>'.$currency.' '.$pricede.'</td>
                                                            </tr>';
                                                }


                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 graph-2 second">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Payment Detail</div>
                                    <div class="panel-body">
                                        <div class="tables">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td> <strong>Total Stay:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?=$totalStay?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <strong>Total Extras:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?=$extrasInfo['total']?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <strong>Gran Total:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?=$grandtotal?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div align="center">
                                                <h2><strong>Total Due:</strong> <?=$grandtotal?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="section-2">
                            <h2 align="center">Invoice</h2>
                            <div>
                                <div id="msginvoice" class="alert alert-warning" style="display: none; text-align: center;">
                                    <strong>Warning!</strong> Select an invoice to Continue.
                                </div>
                                <div class="table-responsive">
                                    <div class="graph" >
                                        <div class="tables">
                                            <table id="summaryinvoice" class="table">
                                                <?php
                                                if( count($Invoice)>0)
                                                {
                                                    echo ' <thead>
                                                                <tr>
                                                                    <th width="10%">Invoice #</th>
                                                                    <th>Create Date</th>
                                                                    <th>Invoice total</th>
                                                                    <th>Total Paid</th>
                                                                    <th>Amount Due</th>
                                                                    <th>Edit</th>
                                                                    <th>Pay</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>';
                                                    $i=0;
                                                    foreach($Invoice  as $invo)
                                                    {                                                   
                                                        $i++;
                                                        echo '  <tr id="invo'.$i.'" class="'.($invo['due']>0?'active':'success').'">
                                                                    <td align="center"><a onclick="detailInvoice('.$invo['reservationinvoiceid'].')">'.$invo['number'].'</a></td>
                                                                    <td>'.date('m/d/Y', strtotime($invo['datecreate']) ).'</td>
                                                                    <td >'.$currency.' '.number_format($invo['Total'], 2, '.', ',').'</td>
                                                                    <td >'.$currency.' '.number_format($invo['totalPaid'], 2, '.', ',').'</td>
                                                                    <td class="'.($invo['due']>0?'danger':'success').'">'.$currency.' '.number_format($invo['due'], 2, '.', ',').'</td>
                                                                    <td > <a onclick="editInvoice('.$invo['reservationinvoiceid'].')"><i class="fa fa-pencil-square-o"></i> </a></td>
                                                                    <td > <a onclick=" payment('.$invo['reservationinvoiceid'].','.$invo['due'].')"><i class="fa fa-credit-card"></i> </a></td>
                                                                </tr>';
                                                    }

                                                    echo "</tbody>";
                                                } 
                                                else{
                                                    echo "<h4> Does not have invoices created</h4>";
                                                    $t="'".secure($channelId)."','".insep_encode($reservatioID)."'";
                                                    echo '<div style="text-align: center;"> 
                                                                <a onclick=" processinvoice('.$t.')" class="btn yellow two" id="processinvoice">Process Invoice</a>
                                                          </div>';
                                                }                              
                                                
                                                    ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align: center;" id="editInvoice">
                                </div>
                            </div>
                        </section>
                        <section id="section-3">
                            <div class="col-md-8 tab-content tab-content-in">
                                <div class="inbox-right">
                                    <div class="mailbox-content">
                                        <!--Compose New Message -->
                                        <div class="compose-mail-box">
                                            <div class="compose-bg">
                                                Guest Welcome Email
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-info">
                                                    Please fill details to send a new message
                                                </div>
                                                <form class="com-mail">
                                                    <input type="text" value="<?=$email?>" class="form-control1 control3" placeholder="To :">
                                                    <input type="text" class="form-control1 control3" placeholder="Subject :">
                                                    <textarea rows="6" class="form-control1 control2" placeholder="Message :"></textarea>
                                                    <input type="submit" value="Send Welcome Email">
                                                </form>
                                            </div>
                                        </div>
                                        <!--//Compose New Message -->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                            <div class="col-md-8 tab-content tab-content-in">
                                <div class="inbox-right">
                                    <div class="mailbox-content">
                                        <!--Compose New Message -->
                                        <div class="compose-mail-box">
                                            <div class="compose-bg">
                                                Reminder Email
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-info">
                                                    Please fill details to send a new message
                                                </div>
                                                <form class="com-mail">
                                                    <input type="text" value="<?=$email?>" class="form-control1 control3" placeholder="To :">
                                                    <input type="text" class="form-control1 control3" placeholder="Subject :">
                                                    <textarea rows="6" class="form-control1 control2" placeholder="Message :"></textarea>
                                                    <input type="submit" value="Send Message">
                                                </form>
                                            </div>
                                        </div>
                                        <!--//Compose New Message -->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                        </section>
                        <section id="section-4">
                            <div class="clearfix"></div>
                            <div class="graph-visual tables-main">
                                <h2 class="inner-tittle">Extras Details</h2>
                                <div class="buttons-ui">
                                    <a href="#ExtrasModal" role="button" class="btn blue" data-toggle="modal"> Add New <i class="fa fa-plus"></i></a>
                                </div>
                                <div class="table-responsive">
                                    <div class="graph">
                                        <div class="tables">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th> Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                if( $extrasInfo['qty']>0)
                                                {
                                                    $i=0;
                                                    foreach($extrasInfo['result']  as $extra)
                                                    {                                                   
                                                        $i++;
                                                        echo '  <tr id="extra'.$i.'" class="'.($i%2?'active':'success').'">
                                                                    <td>'.$i.'</td>
                                                                    <td>'.date('m/d/Y', strtotime($extra['extra_date']) ).'</td>
                                                                    <td>'.$extra['description'].'</td>
                                                                    <td style="text-align: right;">'.$currency.' '.number_format($extra['amount'], 2, '.', ',').'</td>
                                                                    <td align="center"> <a onclick="return delete_extras('.$extra['extra_id'].','.$reservatioID.','.$channelId.','."'(".$extra['description'].") by ".$fname." ".$lname."'".','."'extra".$i."'".');"><i class="fa fa-trash-o"></i> </a></td>
                                                                </tr>';
                                                    }
                                                }                               
                                                
                                            ?>
                                                </tbody>
                                            </table>
                                            <div align="center">
                                                <?=($extrasInfo['qty']==0?'<h5> This Reservation Has No Added Extras</h5>':'') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="section-5">
                            <div class="area-charts">
                                <div class="col-md-6 panel-chrt">
                                    <h3 class="sub-tittle">History</h3>
                                    <?php  if (count($historyInfo)>0) { 

                                            echo'<ul class="timeline" style="height:700px; overflow:auto; " >';
                                            foreach ($historyInfo as  $value) {
                                              echo'<li>
                                                    <div class="timeline-badge '.($value['extra_id']==0?'success':($value['extra_id']==1?'warning':'danger')).'"><i class="'.($value['extra_id']==0 || $value['extra_id']==1 ?'fa fa-check-circle-o':'fa fa-times-circle-o').'"></i></div>
                                                        <div class="timeline-panel">
                                                        <div class="timeline-heading">
                                                            <h4 class="timeline-title">'.($value['extra_id']==0?'Insert':($value['extra_id']==1?'Modify':'Delete')).' '.$value['history_date'].'</h4>
                                                        </div>
                                                        <div class="timeline-body">
                                                            <p>'.strtoupper($value['description']).'</p>
                                                        </div>
                                                    </div>
                                                </li>'; 
                                            } 

                                            echo '</ul>';
                                        }
                                        else
                                        {
                                            echo'<div class="stats-info graph"
                                                    <div class="stats">
                                                            <ul class="list-unstyled">
                                                                <h4 class="sub-tittle">This Reservation Has No History!!</h4>
                                                            </ul>
                                                        </div>
                                                </div>';
                                        }
                                        ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </div>
        </div>
        <script src="<?php echo base_url();?>user_asset/back/js/cbpFWTabs.js"></script>
    </div>
</div>
<!--Paginas modales
Agregar Extras
-->
<div id="ExtrasModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Extras</h4>
            </div>
            <div id="msguser" class="alert alert-warning" style="display: none; text-align: center;">
                <strong>Warning!</strong> Select an Extra to Continue.
            </div>
            <div class="modal-body form">
                <form onsubmit="return saveExtra();" class="form-horizontal form-row-seperated" id="addExtras">
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <div class="form-body">
                            <?php 
                        if(count($extrastoroom)>0)
                        {
                        ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <td> <b> Description </b> </td>
                                            <td> </td>
                                            <td> <b> Price </b> </td>
                                            <td>Sub Total</td>
                                            <td>Tax</td>
                                            <td>Total</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 


                                      foreach($extrastoroom as $extraa){
                                          switch($extraa['structure']){
                                          case '1':
                                            $structure = 'Per Person';
                                            $subtotal = $extraa['price']*intval($numberAdults);
                                          break;

                                          case '2':
                                            $structure = 'Per Night';
                                            $subtotal = $extraa['price']*intval($numberNight);
                                          break;

                                          case '3':
                                            $structure = 'Per Stay';
                                            $subtotal = $extraa['price'];
                                          }

                                          $inserta="si";
                                          $contar=0;

                                      if($extrasInfo['result'])
                                      {
                                            foreach ($extrasInfo['result']as $value) {
                                              if($extraa['name']==$value['description'])
                                              {
                                                $inserta="no";
                                              }
                                              
                                            }
                                          }

                                              if($inserta=="si")
                                              {
                                                  $contar=1;
                                                  $total = $subtotal*intval($extraa['taxes'])/100+$subtotal;
                                                    echo '<tr>';
                                                    echo '<td><input type="checkbox" id="'.$extraa['extra_id'].'" name="extra['.$extraa['extra_id'].']" value="'.$total.'" desc="'.$extraa['name'].'"></td>';
                                                    echo '<td>'.$extraa['name'].'('.$structure.')<td>';
                                                    echo '<td>$'.$extraa['price'].'</td>';
                                                    echo '<td>$'.$subtotal.'</td>';
                                                    echo '<td>%'.$extraa['taxes'].'</td>';
                                                    echo '<td>$'.$total.'</td>';
                                                    echo '</tr>';
                                              }

                                      }
                                          if ($contar==0 )
                                          {
                                            echo"<h4> All extras were added</4> ";
                                          }
                                      }
                          else 
                          {
                            echo"<h4>This room has no extras</4> ";
                          }

                          ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="buttons-ui">
                            <a type="button" class="btn red" data-dismiss="modal"><i class="fa fa-times"></i>Close</a>
                            <a id="submitextra" name="add" value="save" class="btn green"><i class="fa fa-check"></i> Add</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--//outer-wp-->
<!--footer section start
                                        <footer>
                                           <p>&copy 2016 Augment . All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">W3layouts.</a></p>
                                        </footer>
                                    //footer section end-->
<div id="PaymentP" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Payment Application</h4>
            </div>
            <div id="msgpayment" class="alert alert-warning" style="display: none; text-align: center;">
                <strong>Warning!</strong> Select an Extra to Continue.
            </div>
            <div class="modal-body form">
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <div class="form-body">
                        <div class="form-group">
                            <label for="paymentTypeId" style="text-align: right; " class="col-sm-4 control-label">Payment Type</label>
                            <div class="col-sm-6">
                                <select name="paymentTypeId" id="paymentTypeId" class="form-control1">
                                    <?php

                                            if (count($payment['type'])>0) {
                                                echo '<option value="0" onclick="Method(0)"  >Select a payment Type</option>';
                                                foreach ($payment['type'] as $value) {
                                                    
                                                    echo '<option id = "'.$value['method'].'" onclick="Method(this.id)"  value="'.$value['method'].','.$value['paymenttypeid'].'">'.$value['description'].'</option>';


                                                }
                                            }
                                            else
                                            {
                                                echo '<option value="0">Does not have types of payments</option>';
                                            }

                                          ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;" id="metocc">
                            <label for="paymentmethod" style="text-align: right; " class="col-sm-4 control-label">Collection Type</label>
                            <div class="col-sm-6">
                                <select name="paymentmethod" id="paymentmethod" class="form-control1">
                                    <?php

                                            if (count($payment['method'])>0) {
                                                echo '<option value="0" onclick="Method(0)"  >Select a Collection Type</option>';
                                                foreach ($payment['method'] as $value) {
                                                    
                                                    echo '<option  value="'.$value['paymentmethodid'].'">'.$value['descripcion'].'</option>';


                                                }
                                            }
                                            else
                                            {
                                                echo '<option value="0">Does not have Collection Type</option>';
                                            }

                                          ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amountdue" style="text-align: right; " class="col-sm-4 control-label">Amount Due</label>
                            <div class="col-sm-6">
                                <input style="text-align: right; " id="amountdue" name="" value="0" readonly="true">
                                <input type="hidden" id="invoiceid" name="" value="0" readonly="true">
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="buttons-ui">
                        <a type="button" class="btn red" data-dismiss="modal"><i class="fa fa-times"></i>Close</a>
                        <a id="submitpay" name="add" value="save" class="btn green"><i class="fa fa-check"></i> Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="InvoiceDetail" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice</h4>
            </div>
           <div align="center" id="headerinvoice">         
                
            </div>
            <div>
                <div id="tableinvoice">               
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--//content-inner-->
<script type="text/javascript">
new CBPFWTabs(document.getElementById('tabs'));

var channelid='<?=$channelId;?>';
var resid='<?=$reservatioID;?>';


function imprimir(divtoprint){
  var objeto=document.getElementById(divtoprint);  //obtenemos el objeto a imprimir
  var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
  ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
  ventana.document.close();  //cerramos el documento
  ventana.print();  //imprimimos la ventana
  ventana.close();  //cerramos la ventana
}
function detailInvoice(id)
{   
    var address='<?=$address;?>';
    var name='<?=$guestFullName;?>';
    var city ='<?=$city;?>';
    var country='<?=$country;?>';
    var email ='<?=$email;?>';
    var data = {'id':id,'email':email,'name':name,'address':address,'city':city,'country':country};
    $("#headerinvoice").html('');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/invoiceheader",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            $("#headerinvoice").html(msg['html']);

        }
    });
    

    $("#InvoiceDetail").modal();
}
function delete_extras(id, res, channelid, des, fila) {
    swal({
            title: "Are you sure?",
            text: "Do you want to Delete this Extra?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo lang_url(); ?>reservation/delete_extra",
                    data: { "extra_id": id, "reservation_id": res, "channelId": channelid, "description": des },
                    beforeSend: function() {
                        showWait();
                        setTimeout(function() { unShowWait(); }, 10000);
                    },
                    success: function(msg) {
                        unShowWait();
                        fila2 = document.getElementById(fila);

                        fila2.style.display = "none";

                    }
                });
                swal("Extra removed", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                return false;
            }
        });
}

$('#submitextra').click(function() {
    $('#addExtras').submit();
})

function saveExtra() {
    var formulariop = document.getElementById('addExtras');
    var selected = 0;
    var exist = 0;
    var ids = new Array(25);
    var count = 0;
    var rid = '<?php echo insep_encode($reservatioID); ?>';
    var cid = '<?php echo $channelId; ?>';
    var user = '<?php echo $fname." ".$lname;?>';

    for (var i = 0; i < formulariop.elements.length; i++) {
        if (formulariop.elements[i].name.indexOf("extra") !== -1) {
            exist = 1;
            if (formulariop.elements[i].checked) {
                selected = 1;

                ids[count] = formulariop.elements[i].id + ',' + formulariop.elements[i].value + ',' + $("#" + formulariop.elements[i].id).attr('desc');
                count++;

            }
        }
    }

    if (exist == 0) {
        $('#msguser').removeClass();
        $('#msguser').addClass('alert alert-danger');
        $('#msguser').html('There are no extras to add');
        $('#msguser').toggle("slow");
        setTimeout(function() {
            $('#msguser').fadeOut();
        }, 5000);
        return false;
    }
    if (selected == 0) {
        $('#msguser').removeClass();
        $('#msguser').addClass('alert alert-warning');
        $('#msguser').html('Select an Extra to Continue');
        $('#msguser').toggle("slow");
        setTimeout(function() {
            $('#msguser').fadeOut();
        }, 5000);
        return false;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/saveExtras",
        data: { "extraId": ids, "reservationId": rid, "channelId": cid, "userName": user },
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            swal({
                title: "Done!",
                text: "Extras Added Successfully!",
                icon: "success",
                button: "Ok!",
            }).then(ms => {
                location.reload();
            });
        }
    });


    return false;
}

function payment(invoiceid, due) {

    if (due <= 0) {

        $('#msginvoice').removeClass();
        $('#msginvoice').addClass('alert alert-warning');
        $('#msginvoice').html('This invoice has no outstanding balance');
        $('#msginvoice').toggle("slow");
        setTimeout(function() {
            $('#msginvoice').fadeOut();
        }, 5000);
        return;

    }
    $('#amountdue').val(Number(due).toFixed(2));
    $('#invoiceid').val(invoiceid);
    $('#PaymentP').modal({
        show: 'false'
    });

}

function Method(methodid) {

    if (methodid == 'cc') {
        $("#metocc").show();
    } else {
        $("#metocc").hide();
        return;
    }
}

$("#submitpay").click(function() {

    var pid = $("#paymentTypeId").val();
    var metid = $("#paymentmethod").val();
    var invoid = $('#invoiceid').val();
    var amount = $('#amountdue').val();
    var user = '<?php echo $fname." ".$lname;?>';

    if (amount <= 0) {

        $('#msginvoice').removeClass();
        $('#msginvoice').addClass('alert alert-warning');
        $('#msginvoice').html('This invoice has no outstanding balance');
        $('#msginvoice').toggle("slow");
        setTimeout(function() {
            $('#msginvoice').fadeOut();
        }, 5000);
        return;

    }

    if (pid == 0) {

        $('#msgpayment').removeClass();
        $('#msgpayment').addClass('alert alert-danger');
        $('#msgpayment').html('Select a Payment Type to Continue');
        $('#msgpayment').toggle("slow");
        setTimeout(function() {
            $('#msgpayment').fadeOut();
        }, 5000);
        return;
    }

    if (metid == 0 && pid.substring(0, 2) != 'ca') {

        $('#msgpayment').removeClass();
        $('#msgpayment').addClass('alert alert-danger');
        $('#msgpayment').html('Select a Collection Type to Continue');
        $('#msgpayment').toggle("slow");
        setTimeout(function() {
            $('#msgpayment').fadeOut();
        }, 5000);
        return;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/invoicepaymentapply",
        data: { "reservationinvoiceid": invoid, "paymenttypeid": pid, 'amount': amount, 'paymentmethod': metid, 'username': user },
        success: function(msg) {

            if (msg == 0) {

                swal({
                    title: "Done!",
                    text: "Payment Applied Successfully!",
                    icon: "success",
                    button: "Ok!",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg,
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });

})

function processinvoice(channelid, reservationid) {

    var user = '<?php echo $fname." ".$lname;?>';
    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/reservationinvoicecreate",
        data: { "reservationId": reservationid, "channelId": channelid, 'username': user },
        success: function(msg) {
            swal({
                title: "Done!",
                text: "Invoice created Successfully!",
                icon: "success",
                button: "Ok!",
            }).then(ms => {
                location.reload();
            });
        }
    });
}

function editInvoice(invoiceid) {
    $("#editInvoice").html('<h4> Vamos a editar la factura id ' + invoiceid + ' </h4>  <a onclick= "saveinvoice(' + invoiceid + ')" class="btn yellow two" id="saveinvoice">Save Invoice</a>');
}

function saveinvoice(invoiceid) {
    alert(invoiceid);
}
</script>