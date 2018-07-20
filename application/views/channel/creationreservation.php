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
<div class="col-md-12 ">
    <div class="col-md-6 form-group1">
        <label class="control-label"><strong>Check-In</strong></label>
        <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" value="" required="" id="date1Edit" name="date1Edit">
    </div>
    <div class="col-md-6 form-group1">
        <label class="control-label"><strong>Check-Out</strong></label>
        <input style="background:white; color:black; text-align: center;" type="date" class="btn blue" value="" required="" id="date2Edit" name="date2Edit">
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
    <button type="button" id="seach_reserve" class="btn btn-lg btn-info warning_1">Search</button>
</div>
<script type="text/javascript">
$("#addreservation").click(function(event) {
    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date1Edit").attr('min', formatDate(fecha));
    $("#date1Edit").val(formatDate(fecha));
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatDate(fecha));
    $("#date2Edit").val(formatDate(fecha));
});


$("#date1Edit").change(function(event) {
    var fecha = new Date($("#date1Edit").val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
    $("#date2Edit").attr('min', formatDate(fecha));
    $("#date2Edit").val(formatDate(fecha));
});
</script>