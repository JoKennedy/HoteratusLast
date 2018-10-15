
<div class="outter-wp">
	<div class="sub-heard-part">
		<ol class="breadcrumb m-b-0">
			<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
			<li>Sales And Marketing</li>
			<li class="active">Config Competive Set Analisis</li>
		</ol>
	</div>
<div  class="clearfix"></div>
	<div class="graph-visual tables-main">
		<div class="graph">
			<div class="tab-main">
				<div class="tab-inner">
					<div id="tabs" class="tabs">
						<div class="">
							<nav>
								<ul>
									<li><a onclick="showtab(1);" href="#section-1" class="icon-shop"><i class="fa fa-info-circle"></i> <span>Properties and Ota's</span></a></li>
									<li><a onclick="showtab(2);" href="#section-2" class="icon-cup"><i class="fa fa-cogs"></i> <span>Rooms Mapping</span></a></li>
								</ul>
							</nav>
						<div class="content tab">
							<section  id="section-1" class="content-current sec" >
								<div  class="forms-main">
									<div class="graph-form">
										<form class=""  method="post">
										<div class="vali-form">
											<div class="graph">

											<nav class="second" >
												<?php
													foreach ($AllOtas as  $ota) 
													{
														echo '<a style="height:150px;" id="ota'.$ota['HotelOtaId'].'">';
														echo $ota['Name'];
														echo '</a>';
													}
												?>
											</nav>

											<?php
											foreach ($AllOtas as  $ota) {
													echo '<div class="context col-md-12">  ';
													$i=1;
													echo '<table class="table table-bordered">
													<thead>
													<tr>
													<th  width="5%">#</th>
													<th>Hotel Name</th>
													<th>'.$ota['Name'].' Name</th>
													<th>Active</th>
													</tr>
													</thead>';
													$hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1")->result_array();
												foreach ($hotels as $hotel) {

														echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
														<td> <input  style="background:white; color:black; width:100%" type="input" id="'.$hotel['HotelsOutId'].'" name="'.$hotel['HotelsOutId'].'" value="'.$hotel['HotelName'].'" > </td>
														<td> <input style="background:white; color:black; width:100%" type="input" id="'.$hotel['HotelsOutId'].'" name="'.$hotel['HotelsOutId'].'" value="'.$hotel['HotelNameChannel'].'" > </td>
														<td><center><a href="javascript:;">'.($hotel['Active']==1?'Active':'Deactive').' <i class="fa fa-exchange-alt"></i></a></center></td></tr>	 ';
														$i++;
												}

												while ($i <= 6) {

													echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
													<td> <input  style="background:white; color:black; width:100%" type="input" id="'.$i.'" name="'.$i.'" placeholder="Property Name"> </td>
													<td> <input style="background:white; color:black; width:100%" type="input" id="'.$i.'" name="'.$i.'" placeholder="'.$ota['Name'].' Property Name" > </td>
													<td><center>No Created</center></td></tr>	 ';
													$i++;
												}

												echo '</table> </div>';

												}
												?>

												</div>
												</div>
											<div class="buttons-ui">
												<a onclick="saveTask()" class="btn green">Save</a>
											</div>


										</form>
									</div>
									<div class="clearfix"></div>
								</div>
							</section>
							<div class="clearfix"></div>
							<section id="section-2" class="sec">
							<div class="forms-main">
							<div  class="forms-main">
							<div class="graph-form">

							<form class=""  method="post">

							<div class="vali-form">

							<div class="graph">

							<nav class="second" >
							<?php
							foreach ($AllOtas as  $ota) {
							echo '<a style="height:150px;" id="ota'.$ota['HotelOtaId'].'">';
							echo $ota['Name'];
							echo '</a>';
							}
							?>
							</nav>

							<?php
							foreach ($AllOtas as  $ota) {
								echo '<div class="context col-md-12">  ';
								$i=1;
								$hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1")->result_array();
								foreach ($hotels as  $hotel) {
									echo '<center><h1><span class="label label-primary">'.$hotel['HotelName'].'</span></h1></center>';

									$Rooms=$this->db->query("SELECT a.RoomName,a.MaxPeople,a.HotelOutId,b.RoomID,a.ChannelId
											FROM HotelScrapingInfo a
											left join HotelOutRoomMapping b on a.RoomName=b.RoomOutName and a.HotelOutId=b.HotelOutId
											where a.HotelOutId=".$hotel['HotelsOutId']."
											group by a.HotelOutId,a.RoomName order by a.RoomName")->result_array();
									if(count($Rooms)>0)
									{	$i=1;
										echo '<table class="table table-bordered">
												<thead>
												<tr>
												<th  width="5%">#</th>
												<th>Room Name '.$hotel['HotelName'].'('.$ota['Name'].')</th>
												<th>Room Name Hoteratus</th>
												<th>Active</th>
												</tr>
												</thead>';
										foreach ($Rooms as $room) {

											$roomNameC='<a style="padding: 0px;" href="#" class="inline_username" data-type="select" data-pk="'.$room['RoomName'].','.$room['HotelOutId'].','.$room['ChannelId'].'" data-value="'.$room['RoomID'].'" data-source="'.lang_url().'channel/allroomtest" title="Select Room Type"></a>';

											echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
											<td> '.$room['RoomName'].' </td>
											<td>'.$roomNameC.'</td>
											<td><center><a href="javascript:;">'.($hotel['Active']==1?'Active':'Deactive').' <i class="fa fa-exchange-alt"></i></a></center></td></tr>	 ';
											$i++;
									
										}
										echo '</table>';
									}
								}
								echo '</div>';
							}
							?>

							</div>
							</div>
					
							</form>
							</div>
							<div class="clearfix"></div>
							</div>
							</div>
							<div class="clearfix"></div>
							</section>
						</div>
					<!-- /content -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
<script>

function showotas(id,opt)
{

}
function showtab(id)
{
$(".sec").removeClass("content-current");
$("#section-" + id).addClass("content-current");
}
$(function() {
$('.tabs nav a').on('click', function() {
show_content($(this).index());
});

show_content(0);

function show_content(index) {
// Make the content visible
$('.tabs .context.visible').removeClass('visible');
$('.tabs .context:nth-of-type(' + (index + 1) + ')').addClass('visible');

// Set the tab to selected
$('.tabs nav.second a.selected').removeClass('selected');
$('.tabs navnav.second a:nth-of-type(' + (index + 1) + ')').addClass('selected');
}
});

jQuery(document).ready(function($) {
	 $('.inline_username').editable({
             url: function (params) {
                   return saveChange(params);
                }
          });
});

function saveChange(params)
{	
	var data={'name':params['name'],'pk':params['pk'],'value':params['value']};

    $.ajax({
        type: "POST",
        dataType: "json",
        url: '<?=lang_url()?>scraping/savemaping',
        data:data,
        success:function(m)
        {
        	if(m['success'])
        	{
        		 swal({
                    title: "Save",
                    text: 'Done',
                    icon: "success",
                    button: "Ok!",
                });
        	}else{
        		swal({
                    title: "Upps ",
                    text: 'Something Went Wrong',
                    icon: "warning",
                    button: "Ok!",
                });
        	}
        }
    });
   return;
}
</script>
