<?php 
/* 	  if($result){ 
	  foreach($result as $value){	  
	  echo $value->room_id;	 
	  } } */  
?> 
<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n"><h4>
Reservations
</h4></div>
</div></div>
</div>
<div class="booking">
<div class="container new-s">
<div class="row">
<div class="col-md-12 col-sm-12">
<div class="bb1">
<br/>
<div class="reser"><center><i class="fa fa-calendar"></i></center></div>
<h2 class="text-center">You don't have any reservations yet</h2>
<p class="pad-top-20 text-center">You can manage reservations coming from all channels, or you can add reservations manually</p>
<br/>
<div class="res-p"><center><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus"></i> Add reservation</a></center></div>
</div>
</div>
</div>
</div>
</div>
<!-- dialog box -->
<div class="dial">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> More options</h4>
      </div>
      <div class="modal-body">
      <div class="inner-dia">
       <h4>Rate types</h4>
       <div class="top-3">
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n"> Apartment - 1 guest</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n"> Apartment - 1 guest Non refundable</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests Non refundable  </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Minimum stay   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select id="basic" class="selectpicker show-tick form-control" data-live-search="true">
          <option>Do not Change</option>
          <option selected>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
          <option>13</option>
          <option>14</option>
          <option>15</option>
          <option>16</option>
          <option>17</option>
          <option>18</option>
          <option>19</option>
          <option>20</option>
          <option>21</option>
          <option>22</option>
          <option>23</option>
          <option>24</option>
          <option>25</option>
          <option>26</option>
          <option>27</option>
          <option>28</option>
          <option>29</option>
          <option>30</option>
          <option>31</option>
        </select>
      </div>
    </div>
  </form>
       </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Stop sell   </span></div>
      <div class="col-md-3 col-sm-3 new-no">    
      <form class="form-horizontal" role="form">
    <div class="form-group">
      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>  
       </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to arrival (CTA)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
       </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to departure (CTD)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">
      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
       </div>
       </div>
       </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"> ok </button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Modal -->
<!--<div class="dial2">
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
     <a aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" id="btnGroupDrop1"> Dropdown  <span class="caret"></span>
        </a> 
        <ul aria-labelledby="btnGroupDrop1" class="dropdown-menu">
          <li><a href="#">Dropdown link</a></li>
          <li><a href="#">Dropdown link</a></li>
        </ul>
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Room Search </h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <div class="col-md-4 col-sm-4">
        <div class="blu">
        <img src="assets/images/man.png" class="img img-responsive pull-left">
        <p>Please select your check-in and check-out dates as well as total numbers of rooms and guests.</p>
        </div>
        </div>
        <div class="col-md-4 col-sm-4">
        <form>
  <div class="form-group box-p">
    <div class="input-group">
      <div class="input-group-addon">Check-in date</div>
      <input type="text" class="form-control" id="dp1" placeholder="09-09-2015">
    </div>
  </div>
  <div class="form-group box-p2">
    <select class="form-control">
  <option>1 Rooms</option>
  <option>2 Rooms</option>
  <option>3 Rooms</option>
  <option>4 Rooms</option>
</select>
</div>
  </form>
        </div>
        <div class="col-md-4 col-sm-4">
        <form>
  <div class="form-group box-p">
    <div class="input-group">
      <div class="input-group-addon">Check-out date</div>
      <input type="text" class="form-control" id="dp1-p" placeholder="09-09-2015">
    </div>
  </div>
  <div class="form-group box-p2">
    <select class="form-control">
  <option>1 Rooms</option>
  <option>2 Rooms</option>
  <option>3 Rooms</option>
  <option>4 Rooms</option>
</select>
</div>
  </form>
        </div>
        </div>
        <div class="bor-dash"></div>
      </div>
      <div class="modal-footer">
        <button type="button" data-toggle="modal" data-target="#myModal3" class="btn btn-warning">Search</button>
      </div>
    </div>
  </div>
</div>
</div>-->
<div class="dial2">
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">  
  <div class="modal-content">	  
      <div class="modal-header">	 
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	 <!-- <form action="<?php //echo base_url(); ?>reservation/add_reservation" method="post" id="pass_field">-->
	  <form method="POST" id="pass_field" onsubmit="return reservation_room();">
	  <div class="modal-title">		
      <h4 class="modal-title" id="myModalLabel">Room Search </h4>	 
      </div>      
      <div class="modal-body">
      <div class="row">
      <div class="col-md-4 col-sm-4">
      <div class="blu">
      <img src="<?php echo base_url(); ?>user_assets/images/man.png" class="img img-responsive pull-left">
      <p>Please select your check-in and check-out dates as well as total numbers of rooms and guests.</p>
        </div>
        </div>
        <div class="col-md-4 col-sm-4">        
  <div class="form-group box-p">
  <?php 
		$current_date = date('d/m/Y');
		$date = strtotime("+7 day");
		$end_date = date('d/m/Y', $date);
  ?>
    <div class="input-group">
      <div class="input-group-addon">Check-in date</div>
      <input type="text" class="form-control" name="start_date" id="dp1" value="<?php echo $current_date; ?>">
    </div>
  </div>
  <div class="form-group box-p2">
 <select class="form-control" name="room">
  <?php for($j=1; $j<=35; $j++){ ?>
  <option value="<?php echo $j; ?>"><?php if($j == '1'){echo $j.'Room';}else{echo $j.'Rooms';} ?></option>
  <?php } ?>  
</select>
</div>  
  </div>
  <div class="col-md-4 col-sm-4">        
  <div class="form-group box-p">
    <div class="input-group">
      <div class="input-group-addon">Check-out date</div>
      <input type="text" class="form-control" id="dp1-p" name="end_date" value="<?php echo $end_date; ?>">
    </div>
  </div>
  <div class="form-group box-p2">
   <select class="form-control" name="adult">
	<?php for($i=1; $i<=45; $i++){ ?>
  <option value="<?php echo $i; ?>"><?php if($i == '1'){echo $i.'adult';}else{echo $i.'adults';} ?></option>
	<?php } ?> 
    </select> 
</div>  
        </div>
        </div>
        <div class="bor-dash"></div>
      </div>
      <div class="modal-footer">
	<!--<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" name="search" value="search">Search</button>-->
	<button type="submit" class="btn btn-warning" name="search" value="search"><span id="reserve_room">Search</span></button>	
      </div>		 
    </div>
	</form>	
  </div>
</div>
</div>

	<!--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">		
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Number of Gueste per Room</h4>
      </div>
      <div class="modal-body">	 
        <form action="<?php echo lang_url(); ?>" method="post">
          <div class="form-group">
            <label for="recipient-name" class="control-label">Room1</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Room2</label>
             <input type="text" class="form-control" id="recipient-name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        <button type="button" class="btn btn-primary">Continue</button>
      </div>
    </div>
  </div>
</div>-->
<!-- end dialog box -->

<div class="dial2">
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" >
 <div class="modal-content" id="roomsearch_results"> 
 </div>
  </div>
</div>
</div>

<div class="dial2">
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Do Quick Reservation  </h4>
      </div>
      <div class="modal-body">
      
      <div class="row">
      <div class="co-md-6 col-sm-7">
      
      <h5> Guest Information </h5>
      
      <p class="col-md-6 col-sm-6"><input type="text" class="form-control"  placeholder="First Name"> </p>
      
      <p class="col-md-6 col-sm-6"><input type="text" class="form-control"  placeholder="Last Name"> </p>
       
      <p class="col-md-6 col-sm-6"><input type="text" class="form-control"  placeholder="Phone"> </p>
        
      <p class="col-md-6 col-sm-6"><input type="text" class="form-control"  placeholder="Email"> </p>
      
      <small> We may call you at this number Check-out info will be sent to this adress  </small>
      
      <h5>Notes</h5>
      <p> <textarea class="form-control" style="height:150px;"> </textarea> </p>
      </div>
      <div class="co-md-6 col-sm-5">
      <h5> Reservation</h5>
	  
      <div class="table-responsive">
      <table class="table">
      <tr>
      <td> Check-in  :   </td>
      <td>     </td>
      </tr>
      <tr>
      <td>  Check-out :    </td>
      <td> Oct 13, 2015  </td>
      </tr>
      </table>
      </div>
      <h5> Charges </h5>
      <div class="table-responsive">
      
      <table class="table">
      <tr>
      <td>  1 night   </td>
      <td>  ₨200    </td>
      </tr>
      
      <tr>
      <td>  VAT (0%):    </td>
      <td>  ₨20  </td>
      </tr>
      
      <tr>
      <td>Grand total :  </td>
      <td> ₨200 </td>
      </tr>
      
      </table>
      </div>
      
      <h3 class="text-center">DUE NOW  </h3>
      
      <h3  class="text-center">₨200 </h3>
      <hr>
     <div align="center"> <button type="button" class="btn btn-info text-center"data-toggle="modal" data-target="#thanks_booking"> Purchase </button> </div>
      <hr>
      
      </div>
      </div>
      </div>
      
    </div>
  </div>
</div>
</div>

<div class="dial2">
<div class="modal fade" id="thanks_booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">	
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Siva </h4>
      </div>
      <div class="modal-body">
      <div class="row">
      <div class="co-md-12 col-sm-12">
      <h5> Thank you priya lal, your order is complete!
Your confirmation number is #R411221341 </h5>
<table class="summaryTable">
  <tbody>
  <tr>
    <th>
       Confirmation number 
    </th>
    <td>
      <b>R411221341</b>
    </td>
  </tr>
  <tr>
    <th>
      Guest count
    </th>
    <td>
      1
    </td>
  </tr>
  <tr>
    <th>
      Room Type
    </th>
    <td>
      Dharani 
    </td>
  </tr>
  <tr>
    <th>
      Check-in date
    </th>
    <td>
      Oct 14, 2015
    </td>
  </tr>
  <tr>
    <th>
      Check-out date
    </th>
    <td>
      Oct 15, 2015
    </td>
  </tr>

  <tr>
    <th>
      Daily Average Rate
    </th>
    <td>
      ₨200
    </td>
  </tr>
  <tr>
    <th>
      Order Total
    </th>
    <td>
      ₨200
    </td>
  </tr>
      <tr>
        <th>
          Balance due
        </th>
        <td>
          ₨200
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
              Free cancelation.

        </td>
      </tr>
      <tr>
        <th>Check-in time</th>
        <td>After 15:00 day of arrival.</td>
      </tr>
      <tr>
        <th>Check-out time</th>
        <td>11:00 upon day of departure.</td>
      </tr>
      <tr>
        <th>Smoking</th>
        <td>Smoking is allowed.</td>
      </tr>
      <tr>
        <th>Pets</th>
        <td>No pets allowed</td>
      </tr>
  </tbody>
</table>
      </div>
      </div>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
<script>
function reservation_room(){
	$('#reserve_room').html('');	
	$.ajax({
		type:"POST",
		url:"<?php echo lang_url();?>reservation/add_reservation",
		data:$('#pass_field').serialize(),
		beforeSend:function(){
			$('#reserve_room').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
			$('#reserve_room').attr('disabled',true);
		},
		complete:function(){
			$('#reserve_room').html('Search');
			$('#reserve_room').attr('disabled',false);
		},
		success:function(msg){
			// alert(msg);
			$('#roomsearch_results').html(msg);
			$("#myModal2").modal('hide');
			$('#myModal3').modal({backdrop: 'static', keyboard: false}) 

		}
		});
		return false;
}
</script>
