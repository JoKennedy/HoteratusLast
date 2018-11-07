<div id="infoReservation" class="modal fade" role="dialog" style="z-index: 1800;">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="label-primary">
                <center><h2><span  class="label">Make a Reservation</span></h2></center>
            </div>
            <div class="graph-form">
                <form id="ReserveC">
                    <input type="hidden" name="roomid" id="roomid">
                    <input type="hidden" name="rateid" id="rateid">
                    <input type="hidden" name="checkin" id="checkin">
                    <input type="hidden" name="checkout" id="checkout">
                    <input type="hidden" name="child" id="child">
                    <input type="hidden" name="numroom" id="numroom">
                    <input type="hidden" name="newprice" id="newprice">
                    <input type="hidden" name="adult" id="adult">
                    <input type="hidden" name="username" id="username" value="<?=$fname.' '.$lname?>">
                    

                    <div style="float: left; width: 65%;">
                        <h4><span >Main Guest Information</span></h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">First Name</label>
                            <input style="background:white; color:black;" name="firstname" id="firstname" type="text" placeholder="First Name" required="">
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label">Last Name</label>
                            <input style="background:white; color:black;" name="lastname" id="lastname" type="text" placeholder="Last Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="phone" id="phone" type="text" placeholder="Phone Number" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">E-mail</label>
                            <input style="background:white; color:black;"  name="email" id="email" type="text" placeholder="E-Mail" required="" >
                        </div>
                        <div style="float:right;" class="col-md-6 form-group1">
                            
                            <input style="background:white; color:black;" type="checkbox" name="sendemail" id="sendemail" value="1" type="text" > <label for="sendemail">Send Confirmation Email?</label> 
                        </div>
                        <div class="clearfix"></div>
                        <hr size="40">
                        <div id="guestnames">
                        <h4>Additional Guests Names </h4>

                        <div id="allguest"></div>

                        <div class="clearfix"></div>
                        <hr size="40">      
                        </div>
                          <center>
                             <div class="col-md-12">
                                <label style="padding:4px;" class="control-label controls"><h3><span class="label label-danger">SOURCE</span></h3></label>
                                <select style="width: 100%; padding: 9px;" name="sourceid" id="sourceid">
                                    <?php

                                        $Channelss=$this->db->query("select * from agencies where active=1 and hotelid=".hotel_id()." order by Name")->result_array();

                                        echo '<option  value="-1" >Select a Reservation Source</option>';
                                        echo '<option style="background:#71FF33;" value="0" selected >Direct Reservation</option>';
                                        foreach ($Channelss as $value) {
                                            $i++;
                                            echo '<option  value="'.$value['AgencyId'].'" >'.$value['Name'].'</option>';
                                        }
                                  ?>
                                </select>
                                 <br>
                                <hr>
                            </div>
                        </center>
                        <h4>Address Information</h4>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Street Address</label>
                            <input style="background:white; color:black;"  name="address" id="address" type="text" placeholder="Street Address" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">City</label>
                            <input style="background:white; color:black;"  name="city" id="city" type="text" placeholder="City" required="" >
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">State</label>
                            <input style="background:white; color:black;"  name="state" id="state" type="text" placeholder="State" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label style="padding:4px;" class="control-label controls">Country</label>
                            <select style="width: 100%; padding: 9px;" name="countryid" id="countryid">
                                <?php

                                    $Country=$this->db->query("select * from country order by country_name")->result_array();

                                    echo '<option  value="0" >Select a Country</option>';
                                    foreach ($Country as $value) {
                                        $i++;
                                        echo '<option value="'.$value['id'].'" >'.$value['country_name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-8 form-group1">
                            <label class="control-label">Zip Code</label>
                            <br>
                            <input style="background:white; color:black; "  name="zipcode" id="zipcode" type="text" placeholder="Zip Code" required="">
                        </div>
                        <div class="col-md-4 form-group1">
                            <label class="control-label" style=" padding: 3px; " >Arrival Time</label>
                            <input style="width: 100%; padding: 9px; background:white; color:black;" name="arrival" id="arrival" type="time"  required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Notes</label>
                            <textarea id="note" name="note" placeholder="Type Your Notes"></textarea>
                        </div>    
                    </div>
                    <div style="float: left; width: 35%; text-align: left;" class="graph">

                        

                       <h3><span >Information Recap</span></h3>
                        <hr>
                        <table>
                            <tbody>
                                <tr style="padding: 2px;">
                                    <td><strong>Check-In:</strong></td>
                                    <td style="text-align: right;"><span id="checkinr"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Check-Out:</strong></td>
                                    <td style="text-align: right;"><span id="checkoutr"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <hr size="20">
                        <h5><strong >Charges</strong></h5>
                        <table id="totales">
                            <tbody>
                                <tr style="padding-bottom: 5px;">
                                    <td width="80%"><strong><span id="chargeinfo"></span></td>
                                    <td style="text-align: right;"><span id="totalstay" ></span></td>
                                </tr>
                                
                            </tbody>
                        </table>
                        
                        <div style="text-align: center;">

                            <h2>Due Now</h2>
                            <h3 id="totaldue"></h3>
                         
                        </div>
                        <center> <label style="padding:4px;" class="control-label controls"><h3><span class="label label-warning">Payment Application</span></h3></label></center>
                        <?php include("paymentreservations.php"); ?>
                         
                        <div class="buttons-ui">
                            <a onclick="saveReservation();" class="btn green">Book</a>
                        </div>
                        
                    </div>
                    <div class="clearfix"> </div>
                  
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>