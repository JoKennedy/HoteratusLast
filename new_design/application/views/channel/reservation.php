<?php 
// echo '<pre>';
// print_r($result); 
?>
 <?php /*if($result){ 
	  foreach($result as $value){	  
	  echo $value->room_id;	 
	  } } */?> 
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
<?php //echo $id; ?>
</div>
<!-- Modal -->
<div class="dial2">
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">  
  <div class="modal-content">	  
      <div class="modal-header">	 
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <form action="<?php echo lang_url(); ?>reservation/add_reservation" method="post" id="pass_field" ">
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
        <!--<button type="submit" class="btn btn-warning" name="search" value="search" data-toggle="modal" data-target="#myModal3">Search</button>-->
	<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" name="search" value="search">Search</button>
	<!--<button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" name="search" value="search">Search</button>-->
      </div>		 
    </div>
	</form>	
  </div>
</div>
</div>

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">	
	<?php if($result){ 
	  foreach($result as $value){	  
	  echo $value->room_id;	 
	  } } ?> 
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
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary">Continue</button>
      </div>
    </div>
  </div>
</div>
<!-- end dialog box -->

<!-- sharmila -->
<script>

$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('New message to ' + recipient)
  modal.find('.modal-body input').val(recipient)
})
</script>



</body>
</html>