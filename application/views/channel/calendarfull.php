<div class="loading-circle-overlay" id="heading_loader" style="display:none">
  <div id="model-back">
    <div class="loadinh_bg">
      <div class="main_content_bg">
        <div class="details_bg">
          <div style="overflow:hidden;clear:both;">
            <div align="center" style="color:#003580; float: left; font-size: 15px; text-align:center; font-weight:bold;"><br> <!--<font size="-1" color="#A2A2A2" style="margin-left: 26px;">Please Wait...</font>-->
            <br>
            <img src="http://localhost/channel_live/user_assets/loader/loader.gif">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li class="active">Calendar</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->

	  		
			

	        <div style="float: right;" class="buttons-ui">
        		<a class="btn orange">Full Update</a>
        		<a class="btn green">Bulk Update</a>
				<a class="btn blue">Add Reservation</a>
		 	</div>
			<div  class="clearfix"></div>	

		<div class="table-responsive">

			<div id="calendario"> </div>
			<!--<?= $calendar ?>-->
		</div>
		<div class="form-group ">
			<br>
			<div class="col-sm-2">
				<label class="check"><input onclick=" Calendario()" id="show" type="checkbox" > Show Reservation</label>
			</div>
			<div class="col-sm-2">
				<label class="check"><input onclick=" Calendario()" id="Sales" type="checkbox" > Stop Sales</label>
			</div>
			<div class="col-sm-2">
				<label class="check"><input onclick=" Calendario()" id="cta" type="checkbox" > CTA</label>
			</div>
			<div class="col-sm-2">
				<label class="check"><input onclick=" Calendario()" id="ctd" type="checkbox" > CTD</label>
			</div>
			<div class="clearfix"> </div>
				
		</div>
		
</div>
</div>
</div>




<script type="text/javascript">
	


	function Calendario() {
		showWait();
		var base_url = '<?php echo lang_url();?>';
		var data={"show":($("#show").prop('checked')?1:0),"sales":($("#Sales").prop('checked')?1:0),"cta":($("#cta").prop('checked')?1:0),"ctd":($("#ctd").prop('checked')?1:0),};
		
		$.ajax({
			type: "POST",
			url:  base_url+'channel/Calendarview',
			data: data,
			beforeSend:function() {
          showWait('Update Calendar, Please Wait');
          setTimeout(function() {unShowWait();}, 100000);
        },
			success: function(html)
			{	
				$("#calendario").html(html);
				unShowWait();
			 
		   	}
		});
	}


	Calendario();
	
	

</script>