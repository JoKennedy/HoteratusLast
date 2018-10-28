
<div class="outter-wp" style="height:5000px;">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Sales And Marketing</li>
				<li class="active">Competive Set Analysis</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->

			<div style="float: left;" >
				<div class="col-md-12 form-group1">
						<select onchange="CompetiveDisplay()" style="width: 100%; padding: 9px;" name="channelid" id="channelid">

								<?php
										echo '<option  value="0" >Hoteratus</option>';
										foreach ($allChannel as  $value) {
											 echo '<option  value="'.$value['channel_id'].'" >'.$value['channel_name'].'</option>';
										}
								?>
						</select>
				</div>
			</div>
			<div  class="clearfix"></div>
				<div class="table-responsive" id="closeyearid"></div>
				<?php

					$alllist=$this->reservation_model->AllReservationList('2017-01-01','2017-12-31',0,('1,2,4,5,6,7'));


					print_r($alllist);

				?>
		</div>

	</div>
</div>
<div  class="clearfix"></div>



<script type="text/javascript">

	var jsoninfo;
	function BulkUpdate()
	{
		$("#bulkupdate").css('display','');
	}
	function CompetiveDisplay() {

		 var data = {  'yearid': $("#yearid").val(), 'monthid': $("#monthid").val(),'roomname':$("#roomtype").val(),'channelid':$("#channelid").val()};
		 $("#bulkupdate").css('display','none');
		 a =new Date($("#yearid").val()+'-'+$("#monthid").val()+'-01') ;
		 a.setDate(a.getDate()+1);
		 
		 $.ajax({
				 type: "POST",
				 url: '<?=lang_url()?>salesmarketing/DisplayHTML',
				 data: data,
				 dataType:'json',
				 beforeSend: function() {
						 showWait('Please Wait');
						 setTimeout(function() { unShowWait(); }, 1000000);
				 },
				 success: function(html) {
				 
				 		$("#calendarid").html('');
				 		$("#jsoninfo").val('');
						 $("#calendarid").html(html['html']);
						$("#jsoninfo").val(html['json']);
						$("#channel_id").val($("#channelid").val());
						 $('.inline_username').editable({
								 url: function (params) {
										return saveChange(params);
								 }
						 });
						 $('.datepickers').datepicker({minDate:a,dateFormat: 'yy-mm-dd',});
						 unShowWait();

				 }
		 });
	}
	function SaveBulkUpdate()
	{
		validar=1;
		var channelid='';
			$(".channelid").each(function(index, el) {
		         if( $(el).prop("checked") )
		         {
		         	channelid+=$(el).val();
		         }
	         
	    	});

	    	if(channelid.length==0)
	    	{	validar=0;
	    		swal({
	                title: "upps, Sorry",
	                text: "Select a Channel To Continue!",
	                icon: "warning",
	                button: "Ok!",
	            })
	    		return ;
	    	}
	    	$(".date2").each(function(index, el) {
	        if ($(el).val() == '') {
	        	validar=0;
	            swal({
	                title: "upps, Sorry",
	                text: "Complete a Date Range To Continue!",
	                icon: "warning",
	                button: "Ok!",
	            }).then((n)=>{
	            	$(el).focus();
	            });
	            return false;
	        }


	    });

	    $(".date1").each(function(index, el) {
	        if ($(el).val() == '') {
	        	validar=0;
	            swal({
	                title: "upps, Sorry",
	                text: "Complete a Date Range To Continue!",
	                icon: "warning",
	                button: "Ok!",
	            }).then((n)=>{
	            	$(el).focus();
	            });

	            return false;
	        }


	    });

	    if(validar==0)return;
		$.ajax({
			 type: "POST",
			 url: '<?=lang_url()?>bulkupdate/bulkUpdateAnalisis',
			 data: $("#BulkUpdateF").serialize(),
			 //dataType:'json',
			 beforeSend: function() {
					 //showWait('Please Wait');
					 //setTimeout(function() { unShowWait(); }, 1000000);
			 },
			 success: function(html) {
			 		alert(html);
			 }
	 });
	}
</script>



 <script type="text/javascript">
	$(document).ready(function () {
		//CompetiveDisplay();
  $('#username').editable({
                step: 'any',
            });
  $('#group').editable();
});
</script>
