
<div class="outter-wp">
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
			<a href="#" id="username" data-type="text" data-pk="1" data-name="username" data-url="post.php" data-original-title="Enter username">superuser</a>
				<a href="#" id="group" data-type="select" data-name="group" data-pk="1" data-value="5" data-source="groups.php" data-original-title="Select group">Admin</a>
			<div class="graph-visual tables-main">


				<div class="graph">

				<div id="calendarid"></div>


			<?php// select `price_room_channel`('Deluxe Double Room','2019-03-02',2) ?>

				</div>

			</div>
		</div>

	</div>
</div>
<div  class="clearfix"></div>

<script type="text/javascript">
function CompetiveDisplay() {

	 var data = {  'yearid': $("#yearid").val(), 'monthid': $("#monthid").val(),'roomname':$("#roomtype").val(),'channelid':$("#channelid").val()};

	 $.ajax({
			 type: "POST",
			 url: '<?=lang_url()?>scraping/DisplayHTML',
			 data: data,
			 beforeSend: function() {
					 showWait('Please Wait');
					 setTimeout(function() { unShowWait(); }, 100000);
			 },
			 success: function(html) {
					 $("#calendarid").html(html);
					 $('.inline_username').editable({
							 url: function (params) {
									return saveChange(params);
							 }
					 });
					 unShowWait();

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
