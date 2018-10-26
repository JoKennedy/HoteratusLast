
<div class="outter-wp" style="height:5000px;">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Sales And Marketing</li>
				<li class="active">Competive Set Analisis</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->

			<div style="float: left;" >
					<div class="col-md-12 form-group1">
							<select onchange="CompetiveDisplay()" style="width: 100%; padding: 9px;" name="monthid" id="monthid">
									<?php

							$month=array("1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");
							$hoy=array('dia' =>date('d') , 'mes' =>date('m'),'year' =>date('Y'));
								foreach ($month as $key=> $value) {
										$i++;
										echo '<option   value="'.$key.'"'.($key==$hoy['mes']?'selected':'').' >'.$value.'</option>';
								}
						?>
							</select>
					</div>

			</div>
			<div style="float: left;" >
						<div class="col-md-12 form-group1">
							<select onchange="CompetiveDisplay()" style="width: 100%; padding: 9px;" name="yearid" id="yearid">
									<?php

								$hoy=date('Y');

								for ($i=$hoy; $i <=$hoy+1  ; $i++) {
									 echo '<option  value="'.$i.'"'.($i==$hoy?'selected':'').' >'.$i.'</option>';
								 }
						?>
							</select>
					</div>

			</div>
			<div style="float: left;" >
				<div class="col-md-12 form-group1">
						<select onchange="CompetiveDisplay()" style="width: 100%; padding: 9px;" name="roomtype" id="roomtype">
								<?php
										foreach ($allRooms as  $value) {
											 echo '<option  value="'.$value['value'].'" >'.$value['text'].'</option>';
										}
								?>
						</select>
				</div>
			</div>
			<div style="float: left;" >
				<div class="col-md-12 form-group1">
						<select onchange="CompetiveDisplay()" style="width: 100%; padding: 9px;" name="channelid" id="channelid">
								<?php
										foreach ($allChannel as  $value) {
											 echo '<option  value="'.$value['HotelOtaId'].'" >'.$value['Name'].'</option>';
										}
								?>
						</select>
				</div>
			</div>
			<div  class="clearfix"></div>
		<!--	<a href="#" id="username" data-type="text" data-pk="1" data-name="username" data-url="post.php" data-original-title="Enter username">superuser</a>
				<a href="#" id="group" data-type="select" data-name="group" data-pk="1" data-value="5" data-source="groups.php" data-original-title="Select group">Admin</a>-->


				<div class="table-responsive" id="calendarid"></div>
				<div id="bulkupdate" style="display: none;">
      				 <?php include('bulkupdate.php'); ?>
				</div>
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
				 url: '<?=lang_url()?>scraping/DisplayHTML',
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
		CompetiveDisplay();
  $('#username').editable({
                step: 'any',
            });
  $('#group').editable();
});
</script>
