
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

	        	<?php
	        		$specialpermit=array();
					if ($User_Type==2) {
						$specialpermit=specialpermitids();
					}

					if ($User_Type==1 || in_array(1, $specialpermit)) {
						echo '<a class="btn orange">Full Update</a>';
					}
					if ($User_Type==1 || in_array(2, $specialpermit)) {
						echo '<a href="'.base_url().'bulkupdate/viewBulkUpdate" class="btn green">Bulk Update</a>';
					}
					if ($User_Type==1 || in_array(3, $specialpermit)) {
						echo '<a onclick="setcalendar()" class="btn blue">Add Reservation</a>';
					}
				?>
        		
        		
				
		 	</div>
			<div  class="clearfix"></div>	


		<div style="width: 100%; height:400px;"  class="table-responsive">

			<div id="calendario"> </div>
			<!--<?= $calendar ?>-->
		</div>
		<div style="text-align: left;">
			<br>
			<div class="col-md-2">
				<label class="check"><input onclick=" Calendario()" id="show" type="checkbox" >Show Reservation</label>
			</div>
			<div class="col-md-2">
				<label class="check"><input onclick=" showoption('ss',this.checked)" id="Sales" type="checkbox" >Stop Sales</label>
			</div>
			<div class="col-md-2">
				<label class="check"><input onclick=" showoption(this.id,this.checked)" id="cta" type="checkbox" >CTA</label>
			</div>
			<div class="col-md-2">
				<label class="check"><input onclick=" showoption(this.id,this.checked)" id="ctd" type="checkbox" >CTD</label>
			</div>
			<div class="clearfix"> </div>
				
		</div>

		
</div>
<div id="CreateReservation" class="modal fade" role="dialog"  style="z-index: 1400;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div align="center" id="headerinvoice">
            </div>
            <?php include("creationreservation.php")?>





</div>
</div>




<script type="text/javascript">
	

	function showoption(id,value)
	{
		$("."+id).css({
			display: (value?'':'none')
		});
	}
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