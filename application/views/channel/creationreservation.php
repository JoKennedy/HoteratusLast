<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <div align="center">
        <h1><span class="label label-primary">Rooms and Rates</span></h1>
    </div>
</div>

<div class="col-md-12 label-info" align="center">
    <img src="<?php echo base_url()?>user_assets/images/man.png" class="img img-responsive ">
    <p style="font-size: 16px;" align="center">Please select your check-in and check-out dates as well as the total number of rooms and guests.</p>
</div>
<form id="findroomavailable">
    <div class="col-md-12 ">
        <div class="col-md-6 form-group1">
            <label class="control-label"><strong>Check-In</strong></label>
            <input style="background:white; color:black; text-align: center;" type="date" class="btn blue"  required="" id="date1Edit" name="date1Edit">
        </div>
        <div class="col-md-6 form-group1">
            <label class="control-label"><strong>Check-Out</strong></label>
            <input style="background:white; color:black; text-align: center;" type="date" class="btn blue"  required="" id="date2Edit" name="date2Edit">
        </div>
        <div class="col-md-6 form-group1">
            <label class="control-label"><strong>Room Qty</strong></label>
            <select style="width: 100%; padding: 9px; " id="numrooms" name="numrooms">
                <?php
    				$qry = $this->db->query("SELECT max(existing_room_count) as roommax , max(member_count) as membermax, max(children) as childrenmax
    					FROM `manage_property` WHERE  `hotel_id`='".hotel_id()."'")->result_array();
    				$roommax=$qry[0]['roommax'];
    				$membermax=$qry[0]['membermax'];
    				$childrenmax=$qry[0]['childrenmax'];
    				for ($i=1; $i<=$roommax; $i++) { 
    					  echo '<option value="'.$i.'">'.$i.($i==1?' Room':' Rooms'). '</option>';
    					}
    			?>
            </select>
        </div>
        <div class="col-md-6 form-group1">
            <label class="control-label"><strong>Adult Qty</strong></label>
            <select style="width: 100%; padding: 9px; " id="numadult" name="numadult">
                <?php

    				for ($i=1; $i<=$membermax; $i++) { 
    					  echo '<option value="'.$i.'">'.$i.($i==1?' Adult':' Adults'). '</option>';
    					}
    			?>
            </select>
        </div>
        <div class="col-md-6 form-group1">
            <label class="control-label"><strong>Child Qty</strong></label>
            <select style="width: 100%; padding: 9px; " id="numchild" name="numchild">

                <?php
                    echo '<option value="0">No Children</option>';
    				for ($i=1; $i<=$childrenmax; $i++) { 
    					  echo '<option value="'.$i.'">'.$i.($i==1?' Child':' Children'). '</option>';
    					}
    			?>
            </select>
        </div>
    </div>
    <div class="col-md-12 text-right">
        <button type="button" onclick="findroomavailable()"  class="btn btn-lg btn-info warning_1">Search</button>
    </div>
</form>
 <div class="clearfix"></div>



<div  id="roomsavailable" class="modal fade" role="dialog"  >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                
              <h2><span id="fechas" class="label label-primary">FEchas</span></h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            
            <div id="availabledetails">
               
            </div>
             <div class="clearfix"></div>
        </div>
    </div>
</div>
<div  id="infoReservation" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div class="label-primary">
                <h2><span id="fechas" class="label">Make a Reservation</span></h2>         
            </div> 

           <div class="graph-form">
                    <form id="ReserveC">
                        <h2><span id="fechas" class="label">Guest Information</span></h2>   
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Full Name</label>
                            <input style="background:white; color:black;" name="recipename" id="recipename" type="text" placeholder="Recipe Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="pricen" id="pricen" type="text" placeholder="Price" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">E-mail</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="totalC" id="totalC" type="text" placeholder="0.00" required="" readonly="true">
                        </div>
                        <div class="col-md-6 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Product List </label>
                            <select style="width: 100%; padding: 9px;" name="productid" id="productid">
                                <?php

                                    echo '<option  value="0" >Select a Product</option>';
                                    foreach ($ALLProducts as $value) {
                                        $i++;
                                        echo '<option value="'.$value['itemPosId'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Quantity</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="qty" id="qty" type="text" placeholder="Quantity" required="">
                        </div>
                        <div class="buttons-ui">
                            <a onclick="addProduct()" class="btn blue">Add</a>
                        </div>
                        <div class="graph">
                            <div class="table-responsive">
                                <div class="clearfix"></div>
                                <table id="allDetails" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th width="2%">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"> </div>
                                <br>
                                <br>
                                <div class="buttons-ui">
                                    <a onclick="saveRecipe();" class="btn green">Save</a>
                                </div>
                                <div class="clearfix"> </div>
                    </form>
                    </div>
                    </div>
                </div>
             <div class="clearfix"></div>
        </div>
    </div>
    
</div>

</div>
</div>


<script type="text/javascript">

function setcalendar()
{
    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date1Edit").attr('min', formatoDate(fecha));
    $("#date1Edit").val(formatoDate(fecha));
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatoDate(fecha));
    $("#date2Edit").val(formatoDate(fecha));
    $("#CreateReservation").modal();
}


$("#date1Edit").change(function(event) {
    var fecha = new Date($("#date1Edit").val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatoDate(fecha));
    $("#date2Edit").val(formatoDate(fecha));
});

function findroomavailable()
{

     $("#infoReservation").modal('show');  

   return;
     var data=$("#findroomavailable").serialize();
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>reservation/findRoomsAvailable",
        data: data,
        beforeSend: function() {
            showWait('Looking for available rooms for this dates');
            setTimeout(function() { unShowWait(); }, 100000);
        },
        success: function(msg) {
        $("#fechas").html(msg['header']);
          $("#availabledetails").html(msg['detail']);
          $("#roomsavailable").modal()
            unShowWait();
        }
    });
}
function showdetails(id)
{

    if( $("#"+id).css('display')=='none')
    {
        $("#"+id).css('display','');
    }
    else
    {
        $("#"+id).css('display','none');
    }
    
}
function reservethis(roomid,rateid,date1,date2,adult,numroom,numchild,numnight)
{
  
  $("#Createreservation").modal();  
  
}
</script>