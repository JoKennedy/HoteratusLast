		<?php 
		if($result){ 
			$kk=0;
			foreach($result as $list){
				$kk++;
			if($kk==1){	
		?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> 
				<?php $start_date = date('M d',strtotime(str_replace('/','-',$list->start_date))); 
				$end_date = date('M d',strtotime(str_replace('/','-',$list->end_date)));?>
				<?php echo $start_date; ?> - <?php echo $end_date; ?> 
				<?php $nit = $list->end_date-$list->start_date; ?>
				(<?php echo $nit; ?> Nights) - <?php echo $list->availability; ?> Rooms , <?php echo $list->minimum_stay; ?> Guests
				</h4>
			</div>
		<?php } ?>
      <div class="modal-body">
	  <?php $room_type = $this->reservation_model->get_roomtype($list->room_id);
	   // print_r($room_type);die;
	   if($room_type){  
	    foreach($room_type as $room){ ?>
		  <div class="room_info">		  
		  <div class="row">
		  <div class="col-md-3 col-sm-3">	 
		  <div><a href="#"><img src="<?php echo base_url(); ?>user_assets/images/ligh-7.jpg" class="img-responsive" alt=""></a></div>
		  </div>
		  <div class="col-md-6 col-sm-6"> 
	  <!--<h4>Dharani</h4>-->
		 <h4><?php echo $room->room_type; ?></h4> 
		 <!--<p> 3 Guests</p>--> 
		 <p><?php echo $list->minimum_stay; ?> Guests</p>
		 <p>Business Room</p>
		 <p>Bed types:</p>     
		 <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#<?php echo $kk; ?>" aria-expanded="false" aria-controls="collapseExample">    Room Detail </button>    	   
		  </div>      
		  <div class="col-md-3 col-sm-3">      
		  <h6>Avg. per night</h6>
		  <?php $price = $this->reservation_model->get_room_list(); ?>
		  <h2>₨<?php echo $price->price; ?></h2>
		  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal4"> Book This Room</button>
		  </div>
		  </div>  
		  <div class="row"> 
			<div class="col-md-12 col-sm-12">
			<div class="collapse" id="<?php echo $kk; ?>">
		<div class="more-info" style="display: block;">
			<div class="miProperties">    <div class="miProperties">
			<span class="detailtitle"><b>Description</b></span><br><span class="descDetail">
			dfsdf</span>
		</div>
			<div class="clear20"></div>
			</div>
			  <div class="miSummary">
			<ul>
			  <li>Check-in date<span> <?php echo $list->start_date; ?></span></li>
			  <li>Check-out date<span> <?php echo $list->end_date; ?></span></li>
			  <div class="clear10"></div>
			  <li>Rooms<span><?php echo $list->availability; ?></span></li>
			  <li>Guests<span><?php echo $list->minimum_stay; ?></span></li>
			  <?php $night = $list->end_date-$list->start_date; ?>
			  <li>Nights<span><?php echo $night; ?></span></li>
			  <div class="clear10"></div>
			  <?php $total = $night*100; ?>
			  <li><b>Total</b><span><b> ₨<?php echo $total; ?></b></span></li>
			</ul>
		  </div>
		<div class="clear"></div>
			<div class="clear15"></div>
		</div>
		</div>
		</div>
		</div>
		<div class="bor-dash mar-bot20"></div>
		</div>
	<?php } } ?>
	</div>
<?php } }  ?>