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
        <a onclick="changestatus()" class="btn <?=($statusId==0 || $statusId==3 ?'red':($statusId==1 || $statusId==4 ?'green':($statusId==2?'yellow':'blue')))?> six">
            <?=$status?>
        </a>
        <div id="allStatus">
            <center>
                                <?php

                                     if ($statusId==1 || $statusId==2 || $statusId==3  ) {
                                        
                                        echo '<div style="text-align:center;" class="col-md-4 form-group1">';
                                        echo '<a onclick="applystatus(5)" style="width:200px;" class="btn blue six">Check in Room</a>';                          
                                        echo '</div>';
                                    }
                                    if ( $statusId==5) {
                                        
                                        echo '<div style="text-align:center;" class="col-md-12 form-group1">';
                                        echo '<a onclick="applystatus(6)" style="width:200px;" class="btn yellow six">Check Out Room</a>';                     
                                        echo '</div>';
                                    }
                                    if ($statusId==1 || $statusId==2 || $statusId==3) {
                                        
                                        echo '<div style="text-align:center;" class="col-md-4 form-group1">';
                                        echo '<a onclick="applystatus(3)" style="width:200px;" class="btn orange six">Mark Room No Show  </a>';                  
                                        echo '</div>';
                                    }

                                    if ( $channelId==0 && ($statusId==1 || $statusId==2 || $statusId==3 ) ) {
                                        
                                        echo '<div style="text-align:center;" class="col-md-4 form-group1">';
                                        echo '<a onclick="applystatus(0)" style="width:200px;" class="btn red ">Cancel Reservation</a>';                          
                                        echo '</div>';
                                    }

                                ?>
                            </center>
                            </div>
                            <div class="clearfix">    </div>                           
                            

    </div>
    <div class="tab-main">
        <div class="tab-inner">
            <div id="tabs" class="tabs">
                <div class="">
                    <nav>
                        <ul>
                            <li><a onclick="showtab(1);" class="icon-shop tab"><i class="fa fa-info-circle"></i> <span>Details</span></a></li>
                            <li><a onclick="showtab(2);" class="icon-cup"><i class="fa fa-file-text"></i> <span>Invoices</span></a></li>
                            <li><a onclick="showtab(3);" class="icon-food"><i class="fa fa-envelope"></i> <span>Emails</span></a></li>
                            <li><a onclick="showtab(4);" class="icon-lab"><i class="fa fa-plus"></i> <span>Extras</span></a></li>
                             <li><a onclick="showtab(6);" class="icon-lab"><i  class="fa fa-comments"></i> <span>Reservation Notes</span></a></li>
                            <li><a onclick="showtab(5);" class="icon-truck"> <i class="lnr lnr-history"></i><span> History</span></a></li>
                        </ul>
                    </nav>
                    <div class="content tab">
                        <section id="section-1" class="content-current sec">
                            <center>
                                <a href="#EditReservation" data-toggle="modal" class="btn green two">Edit Reservation</a>
                                <a href="#Printreservation" data-toggle="modal" target="_blank" class="btn red three">Print Checkin Form</a>

                               

                            </center>
                            <div class="col-md-6 profile-info">
                                <h3>Reservation Details </h3>
                                <div class="main-grid3">
                                    <div class="p-20">
                                        <div class="about-info-p">
                                            <strong>Room Type</strong>
                                            <p class="text-muted">
                                                <?=$roomTypeName?>
                                                    <a style="height: 10px; " onclick="roomstypeava()" class="blue two">Change</a>
                                            </p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Room Number</strong>
                                            <p class="text-muted">
                                                <?=$roomNumber?>
                                                    <a style="height: 10px; " onclick="RoomsAvailables()" class="green two">Change</a>
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
                                    <div class="panel-heading">Additional Agency Details</div>
                                    <div class="panel-body">
                                        <div class="about-info-p m-b-0">
                                            <strong>Agency Name</strong>
                                            <p class="text-muted">
                                                <?=isset($agencyname)?$agencyname:''?>
                                            </p>
                                        </div>
                                        <div class="about-info-p m-b-0">
                                            <strong>Commission</strong>
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
                                            <strong>Main Guest Name</strong>
                                            <p class="text-muted">
                                                <?=$guestFullName?>
                                            </p>
                                        </div>
                                        <?php
                                      
                                            if(isset($allguest))
                                            { 
                                                $guest1=explode(',', $allguest);
                                                if(strlen($allguest)>2)
                                                {
                                                     echo '<div class="about-info-p">
                                                    <strong>All Guest Name</strong>';
                                                        foreach ($guest1 as  $value) {
                                                           echo ' <p class="text-muted">
                                                                   '.$value.'
                                                                    </p>';
                                                        }
                                                        echo ' </div>';
                                                    }
                                                }
                                               


                                         ?>
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
                                    <div class="panel-heading">Guest Notes</div>
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

                                                    if(isset($totaltaxP))
                                                    {
                                                        $pricede+=$pricede*$totaltaxP;
                                                    }

                                                    echo '  <tr class="'.($i%2?'active':'success').'">
                                                                <td>'.$weekday.'</td>
                                                                <td>'.$ran->format('M d, Y').'</td>
                                                                <td>'.$currency.' '.number_format($pricede, 2, '.', ',').'</td>
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
                                                        <?php

                                                            $taxinfo=explode(',', $taxes);
                                                            $taxdata=$this->db->query("select * from taxcategories where hotelid=".hotel_id())->result_array();
                                                            $taxincluded=0;
                                                            foreach ($taxinfo as $taxvalue) {
                                                                $taxvalue=explode('*', $taxvalue);
                                                                if($taxvalue[2]==1)
                                                                {
                                                                    $taxincluded+=$totalStay*($taxvalue[1]/100);
                                                                }
                                                            }
                                                        ?>
                                                        <td> <strong>Rate Without Taxes:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?=number_format($totalStay-$taxincluded, 2, '.', ',')?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        if (isset($taxes) && strlen($taxes)>2) {
                                                            
                                                            
                                                            foreach ($taxinfo as  $tax) {
                                                                
                                                                $tax=explode('*', $tax);
                                                                $taxid= array_search($tax[0], array_column($taxdata, 'taxid'));
                                                                $TOTALTAX=$totalStay*($tax[1]/100);
                                                                echo '<tr>
                                                                        <td> <strong>'.$taxdata[$taxid]['name'].':&nbsp</strong></td>
                                                                        <td style="text-align: right;">
                                                                            '.number_format($TOTALTAX, 2, '.', ',').'
                                                                        </td>
                                                                    </tr>';                                                                                                       
                                                            }

                                                        }
                                                    ?>
                                                    <tr>
                                                        <td> <strong>Total Extras:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?=number_format($extrasInfo['total'], 2, '.', ',')?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> <strong>Gran Total After Tax:&nbsp</strong></td>
                                                        <td style="text-align: right;">
                                                            <?= number_format($grandtotal, 2, '.', ',')?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div align="center">
                                                <h2><strong>Total Due:</strong> <?=number_format($grandtotal, 2, '.', ',') ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="section-2" class="sec">
                            <h2 align="center">Invoice</h2>
                            <div>
                                <div id="msginvoice" class="alert alert-warning" style="display: none; text-align: center;">
                                    <strong>Warning!</strong> Select an invoice to Continue.
                                </div>
                                <div class="table-responsive">
                                    <div class="graph">
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
                        <section id="section-3" class="sec">
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
                        <section id="section-4" class="sec">
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
                        <section id="section-5" class="sec">
                            <div class="area-charts">
                                <div class="col-md-6 panel-chrt">
                                    <h3 class="sub-tittle">History</h3>
                
                                        <?php  if (count($historyInfo)>0) { 

                                            echo'<ul class="timeline" style="height:700px; overflow:auto; " >';
                                            foreach ($historyInfo as  $value) {
                                              echo'<li>
                                                    <div class="timeline-badge '.($value['extra_id']==0?'success':($value['extra_id']==1?'warning':'danger')).'"><i class="'.($value['extra_id']==0 || $value['extra_id']==1 ?'fa fa-check-circle':'fa fa-times-circle').'"></i></div>
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
                                            echo'<div class="stats-info graph">
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
                          <section id="section-6" class="sec">
                            <div class="col-md-4">
                                <form>
                                    <div class="col-md-12 form-group1">
                                        <textarea style="width: 100;" id="usernote" name="usernote" placeholder="Type a Note"></textarea>
                                    </div>
                                    <div class="buttons-ui">
                                        <a onclick="addNote()" class="btn blue">Add Note</a>
                                    </div>
                                </form>
                            </div>
                             <div class="chat-inner col-md-8">
                                    <!--/chat-inner-->
                                    <div class=" widget-shadow ">
                                        <h4 class="title3" style="background-color:#021F4E;">Users Notes</h4>
                                        <div class="scrollbar" id="style-2">
                                            <?php  

                                            if(count($ALLUsersNotes)>0)
                                            {   
                                                $i=0;
                                                foreach ($ALLUsersNotes as  $value) {
                                                    $i++;

                                                    if($i%2)
                                                    {
                                                        echo '  <div class="activity-row activity-row1 activity-right">
                                                            <div class="col-xs-3 activity-img"><span>'.$value['username'].'</span></div>
                                                            <div class="col-xs-9 activity-img1">
                                                                <div class="activity-desc-sub">
                                                                    <p>'.$value['description'].'</p>
                                                                    <span>'.date('m/d/Y h:m:s',strtotime($value['createdatetime'])).'</span>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"> </div>
                                                        </div>'; 
                                                    }
                                                    else
                                                    {   echo '  <div class="activity-row activity-row1 activity-left">
                                                            <div class="col-xs-9 activity-img2">
                                                                <div class="activity-desc-sub1">
                                                                    <p>'.$value['description'].'</p>
                                                                    <span class="right">'.date('m/d/Y h:m:s',strtotime($value['createdatetime'])).'</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3 activity-img"><span>'.$value['username'].'</span></div>
                                                            <div class="clearfix"> </div>
                                                        </div>';
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                    echo '  <div class="activity-row activity-row1 activity-right">
                                                    <div class="col-xs-3 activity-img"><span>Hoteratus</span></div>
                                                    <div class="col-xs-9 activity-img1">
                                                        <div class="activity-desc-sub">
                                                            <p><h3>There aren'."'".'t notes Created</h3></p>
                                                            <span>'.date('m/d/Y h:m:s').'</span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"> </div>
                                                </div>';
                                            }



                                        ?>
                                        </div>
                                        
                                    </div>
                                    <div class="clearfix"> </div>
                                    <!--/chat-inner-->
                            </div>
                            
                        </section>
                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </div>
        </div>
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
<div id="PaymentP" class="modal fade" role="dialog" aria-hidden="true">
    <?=include('paymentapplication.php')?>
</div>
<div id="ShowCC" class="modal fade" role="dialog" aria-hidden="true">
   <?=include('credicarddetails.php')?> 
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
<div id="EditReservation" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Reservation Details</h4>
                <div class="profile-widget" style="background-color: white;">
                    <img src="data:image/png;base64,<?=$LogoReservation?>" alt=" " />
                    <h2 style="color: black;"> Reservation from <?=$ChannelName?> </h2>
                    <strong>Reservation Number</strong>
                    <p class="text-muted">
                        <?=$reservationNumber?>
                    </p>
                    <strong>Total Stay</strong>
                    <p id="totalstayedit" class="text-muted">
                        <?=number_format($totalStay, 2, '.', ',')?>
                    </p>
                </div>
            </div>
            <form id="updateR">
                <input type="hidden" name="canalid" value="<?=$channelId?>">
                 <input type="hidden" name="reservaid" value="<?=$reservatioID?>">
          
            <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Check-In</strong></label>
                            <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" value="<?=date('Y-m-d',strtotime($checkin))?>" required="" id="date1Edit" name="date1Edit">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Check-Out</strong></label>
                            <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" value="<?=date('Y-m-d',strtotime($checkout))?>" required="" id="date2Edit" name="date2Edit">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Room Type</strong></label>
                            <input style="background:white; color:black;" name="roomtype" id="roomtype" type="text" placeholder="Room Type" value="<?=$roomTypeName?>" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Room Number</strong></label>
                            <input style="background:white; color:black;" name="roomnumber" id="roomnumber" value="<?=$roomNumber?>" type="text" placeholder="Room Number" required="">
                        </div>

                          <?php
            
                            echo '<div class="col-md-6 form-group1"><label class="control-label">Main Guest </label><input style="background:white; color:black;" name="mainguest" id="mainguest" type="text" placeholder="Type a Main Guest" required="" value="'.$guestFullName.'"></div>';

                            if(isset($allguest))
                            {
                                $guest1=explode(',', $allguest);

                                

                                for ($i=0; $i < count($guest1) ; $i++) { 
                                    
                                    echo '<div class="col-md-6 form-group1"><label class="control-label">Guest '.($i+1).' </label><input style="background:white; color:black;" name="guest[]" id="guest" type="text" placeholder="Type a Guest Name" required="" value="'.$guest1[$i].'"></div>';
                                }

                            }

                           
                        ?>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Email</strong></label>
                            <input style="background:white; color:black;" name="Email" id="Email" type="text" placeholder="Email" required="" value="<?=$email?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Phone</strong></label>
                            <input style="background:white; color:black;" name="Phone" id="Phone" type="text" placeholder="Phone" required="" value="<?=$mobiler?>">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label"><strong>Address</strong></label>
                            <input style="background:white; color:black;" name="Address" id="Address" type="text" placeholder="Address" required="" value="<?=$address?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>City</strong></label>
                            <input style="background:white; color:black;" name="City" id="City" type="text" placeholder="City" required="" value="<?=$city?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>State</strong></label>
                            <input style="background:white; color:black;" name="State" id="State" type="text" placeholder="State" required="" value="<?=$state?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Country</label>
                            <input style="background:white; color:black;" name="Country" id="Country" type="text" placeholder="Country" required="" value="<?=$country?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Zip Code</strong></label>
                            <input style="background:white; color:black;" name="ZipCode" id="ZipCode" type="text" placeholder="Zip Code" required="" value="<?=$zipCode?>">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
            <div class="col-md-12 buttons-ui">
                <a onclick="UpdateReservation()" class="btn green">Update</a>
            </div>
           
            
                        
                     
                  
            <div class="clearfix"></div>
            <br>
        </div>
    </div>
</div>

<div id="Printreservation" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><h4 class="modal-title">Check-in Form</h4></center>
            </div>
            <center>
                <a href="<?php echo lang_url(); ?>reservation/printreservation/<?php echo secure($channelId)?>/<?php echo insep_encode($reservatioID)?>/<?php echo insep_encode('spanish')?>" target="_blank" class="btn green fifth">Spanish</a>
                 <a href="<?php echo lang_url(); ?>reservation/printreservation/<?php echo secure($channelId)?>/<?php echo insep_encode($reservatioID)?>/<?php echo insep_encode('english')?>" target="_blank" class="btn red six">English</a>
            </center>
             

            <div class="clearfix"></div>
        </div>
    </div>
</div>



<div id="EditStatus" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Status</h4>
                <div class="profile-widget" style="background-color: white;">
                    <img src="data:image/png;base64,<?=$LogoReservation?>" alt=" " />
                    <h2 style="color: black;"> Reservation from <?=$ChannelName?> </h2>
                    <strong>Reservation Number</strong>
                    <p class="text-muted">
                        <?=$reservationNumber?>
                    </p>
                    <strong>Total Stay</strong>
                    <p id="totalstayedit" class="text-muted">
                        <?=number_format($totalStay, 2, '.', ',')?>
                    </p>
                </div>
            </div>
           
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="roomavailable" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rooms Numbers Available</h4>
            </div>
            <div id="allavailable">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="roomstypesavailable" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rooms Types Available</h4>
            </div>
            <div id="alltypeavailable">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="roomstypesavailable" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rooms Types Available</h4>
            </div>
            <div id="alltypeavailable">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="optupgrade" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="text-align: center;" class="modal-title">Upgrade Type </h4>
            </div>
            <div>
                <input type="hidden" name="chargenight" id="chargenight" type="text">
                <input type="hidden" name="nroomtype" id="nroomtype" type="text">
                <div class="buttons-ui">
                    <a onclick="changeroomtypeup(1)" class="btn yellow">Free</a>
                    <a onclick="changeroomtypeup(2)" class="btn green"><span id="preciopernig"></span> Avg. per Night</a>
                    <a onclick="setnewprice()" class="btn blue">Set Price per Night</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="optnewprice" class="modal fade" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="text-align: center;" class="modal-title">New Price  Avg. per Night </h4>
            </div>
            <div>
                <div class="col-md-12 form-group1">
                    <label class="control-label">New Price</label>
                    <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="newprice" id="newprice" type="text" placeholder="New Price" required="">
                </div>
                <div class="buttons-ui ">
                    <a onclick="changeroomtypeup(3)" class="btn blue">Apply</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--//content-inner-->
<?php $avgpernight=($totalStay/$numberNight);?>
<script type="text/javascript">
var resID = "<?=$reservatioID?>";
var userName = "<?=$fname.' '.$lname ?>";
var channelid = "<?=$channelId?>";
var checkin = "<?=$checkin?>";
var checkout = "<?=$checkout?>";
var roomtype = "<?=$roomTypeID?>";
var adults = "<?=$numberAdults?>";
var children = "<?=$numberChilds?>";
var avgpern = "<?=$avgpernight?>";
var currentdate="<?=date('Y-m-d')?>";

function setnewprice() {
    $("#newprice").val(0.00);
    $("#optnewprice").modal();
}

function roomstypeava() {


    var data = { 'date1Edit': checkin, 'date2Edit': checkout, 'numrooms': 1, 'numadult': adults, 'numchild': children, 'avg': avgpern, 'roomtype': roomtype };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/findroomtypesavailable",
        data: data,
        beforeSend: function() {
            showWait('Looking for available rooms for this dates');
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                $("#alltypeavailable").html(msg['html']);
                $("#roomstypesavailable").modal();
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
}

function changeroomtype(id, night, upgrade, chargenight) {
    if (upgrade == 1) {
        $("#preciopernig").html(chargenight);
        $("#chargenight").val(chargenight);
        $("#nroomtype").val(id);
        $("#optupgrade").modal();
    } else {

        var data = { 'date1Edit': checkin, 'date2Edit': checkout, 'numrooms': 1, 'numadult': adults, 'numchild': children, 'avg': avgpern, 'roomtype': roomtype, 'nroomtype': id, 'resid': resID, 'channelid': channelid, 'upgrade': upgrade, 'username': userName };
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>reservation/changeroomtype",
            data: data,
            beforeSend: function() {
                showWait('Charging Room Type');
                setTimeout(function() { unShowWait(); }, 100000);
            },
            success: function(msg) {
                unShowWait();

                if (msg['success']) {
                    swal("The Room Type Changed Correctly", {
                        icon: "success",
                    }).then(ms => {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Warning!",
                        text: msg['message'],
                        icon: "warning",
                        button: "Ok!",
                    });
                }
            }
        });
    }


}

function changeroomtypeup(opt) {

    var nprice = 0;
    if (opt == 2) {
        nprice = $("#chargenight").val();
    } else if (opt == 3) {
        if ($("#newprice").val() == 0 || $("#newprice").val().length == 0) {
            swal({
                title: "Warning!",
                text: 'Missing Field New Price',
                icon: "warning",
                button: "Ok!",
            });
            return;
        }
        nprice = $("#newprice").val();
    }

    var data = { 'date1Edit': checkin, 'date2Edit': checkout, 'numrooms': 1, 'numadult': adults, 'numchild': children, 'avg': avgpern, 'roomtype': roomtype, 'nroomtype': $("#nroomtype").val(), 'resid': resID, 'channelid': channelid, 'upgrade': 1, 'username': userName, 'opt': opt, 'nprice': nprice };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/changeroomtype",
        data: data,
        beforeSend: function() {
            showWait('Charging Room Type');
            setTimeout(function() { unShowWait(); }, 100000);
        },
        success: function(msg) {
            unShowWait();

            if (msg['success']) {
                swal("The Room Type Changed Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });



}

function changestatus() {
    $("#EditStatus").modal();
}

function applystatus(id) {

    var data = { 'resid': resID, 'statusid': id, 'username': userName };
  
    if(id==5 && checkin!=currentdate)
    {
       
        swal({
              title: "Check-In is out of date!",
              text: "Do you want to change the status anyway?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (!willDelete) {
                return;
              }else{
                completestatus(data);
              }
            });
    }
    else if(id==6 && checkout!=currentdate)
    {
       
        swal({
              title: "Check-Out is out of date!",
              text: "Do you want to change the status anyway?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (!willDelete) {
                return;
              }else{
                completestatus(data);
              }
            });
    }
    else
    {
        swal({
              title: "Change of status",
              text: "Do you want to change the status anyway?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (!willDelete) {
                return;
              }else{
                completestatus(data);
              }
            });
    }
    
}
function completestatus(data)
{
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/changeStatus",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("Status Changed", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });
}
function addNote() {
    if ($("#usernote").val() == "") {
        swal({
            title: "Warning!",
            text: "Type a Note To Continue",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
    var data = { 'resid': resID, 'note': $("#usernote").val(), 'username': userName, 'channelid': channelid };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/addnoteuser",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("Note Added", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });

}

function RoomsAvailables() {
    var data = { 'room_id': roomtype, 'checkin': checkin, 'checkout': checkout };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/RoomsAvailables",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                $("#allavailable").html(msg['html']);
                $("#roomavailable").modal();
            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });


}

function assingNumber(number) {
    var data = { 'roomnumber': number, 'resid': resID, 'channelid': channelid, 'username': userName, 'checkin': checkin, 'checkout': checkout, 'roomtype': roomtype };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/assingRoomNumbers",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg['success']) {
                swal("The Room Number was Assigned Correctly", {
                    icon: "success",
                }).then(ms => {
                    location.reload();
                });

            } else {
                swal({
                    title: "Warning!",
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });

}

function UpdateReservation() {

    $.ajax({
        type: "POST",
        url: "<?php echo lang_url(); ?>reservation/updatereservation",
        data: $("#updateR").serialize(),
        dataType:'json',
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {

            unShowWait();
            if (msg['success']) {
                swal({
                title: "Done!",
                text: "Reservation was Updated Successfully!",
                icon: "success",
                button: "Ok!",
                }).then(ms => {
                    location.reload();
                });

            }else{

                swal({
                title: "Warning!",
                text: "Something went Wromg!",
                icon: "danger",
                button: "Ok!",
                })
            }
            
        }
    });

}

function showtab(id)

{

    $(".sec").removeClass("content-current");
    $("#section-" + id).addClass("content-current");
}

var resid = '<?=$reservatioID;?>';

$(document).ready(function() {

    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date1Edit").attr('min', currentdate);
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', currentdate);

});


$("#date1Edit").change(function(event) {
    var fecha = new Date($("#date1Edit").val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatDate(fecha));
    $("#date2Edit").val(formatDate(fecha));
});


function detailInvoice(id) {
    var address = '<?=$address;?>';
    var name = "<?=$guestFullName;?>";
    var city = '<?=$city;?>';
    var country = '<?=$country;?>';
    var email = '<?=$email;?>';

    var data = { 'id': id, 'email': email, 'name': name, 'address': address, 'city': city, 'country': country };
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
});

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



$("#submitpay").click(function() {

    var pid = $("#paymentTypeId").val();
    var proid = $("#providerid").val();
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

    
    if (proid == 0 && pid != 1) {

        $('#msgpayment').removeClass();
        $('#msgpayment').addClass('alert alert-danger');
        $('#msgpayment').html('Select a Collection Type to Continue');
        $('#msgpayment').toggle("slow");
        setTimeout(function() {
            $('#msgpayment').fadeOut();
        }, 5000);
        return;
    }

    var data =  $("#paymentapplication").serialize() + "&"+$("#ccinfo").serialize()+"&invoiceid="+invoid+"&username="+userName;
   
    $.ajax({
        type: "POST",
        dataType:"json",
        url: "<?php echo lang_url(); ?>reservation/PaymentApplication",
        data: data,
        success: function(msg) {
            if (msg['success']) {

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
                    text: msg['message'],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });



});

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
    //  $("#editInvoice").html('<h4> Vamos a editar la factura id ' + invoiceid + ' </h4>  <a onclick= "saveinvoice(' + invoiceid + ')" class="btn yellow two" id="saveinvoice">Save Invoice</a>');
}

function saveinvoice(invoiceid) {
    //alert(invoiceid);
}
</script>