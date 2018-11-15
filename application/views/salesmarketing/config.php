
<div class="outter-wp">
	<div class="sub-heard-part">
		<ol class="breadcrumb m-b-0">
			<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
			<li>Sales And Marketing</li>
			<li class="active">Config Competive Set Analysis</li>
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
									<li><a onclick="showtab(3);" href="#section-2" class="icon-cup"><i class="fa fa-cogs"></i> <span>Local Rooms Mapping</span></a></li>
								</ul>
							</nav>
						<div class="content tab">
							<section  id="section-1" class="content-current sec" >
								<div  class="forms-main">
									<div class="graph-form">
										<form id="roomsout"  method="post">
										<div class="vali-form">
											<div class="graph">

											<nav class="second" >
												<?php
													foreach ($AllOtas as  $ota)
													{
														echo '<a style="height:150px;" > ';
														echo $ota['Name'];
														echo '</a>';
													}
												?>
											</nav>

											<?php
											foreach ($AllOtas as  $ota) {

													$mainhotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1 and main=1")->row_array();
													echo '<div class="context col-md-12">  ';
													$i=1;

													echo '<input  style="background:white; color:black; width:100%" type="input"  name="main_'.$ota['HotelOtaId'].'_'.(isset($mainhotels['HotelsOutId'])?$mainhotels['HotelsOutId']:$i).'_1" value="'.@$mainhotels['HotelName'].'" placeholder="Main Property Name">
													 <input style="background:white; color:black; width:100%" type="input"  name="main_'.$ota['HotelOtaId'].'_'.(isset($mainhotels['HotelsOutId'])?$mainhotels['HotelsOutId']:$i).'_2"
													value="'.@$mainhotels['HotelNameChannel'].'" placeholder="'.$ota['Name'].' Main Property Name" >
													<input style="background:white; color:black; width:100%" type="text"  name="main_'.$ota['HotelOtaId'].'_'.(isset($mainhotels['HotelsOutId'])?$mainhotels['HotelsOutId']:$i).'_3"
													value="'.@$mainhotels['MinimumStay'].'" placeholder="'.$ota['Name'].' Main Minimum Stay" onkeypress="return justNumbers(event);" > ';
													$i++;

													echo '<table class="table table-bordered">
													<thead>
													<tr>
													<th width="5%">#</th>
													<th>Hotel Name</th>
													<th>'.$ota['Name'].' Name</th>
													<th width="5%">Minimum Stay</th>
													<th width="5%">Active</th>
													</tr>
													</thead>';
													$hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1 and main=0")->result_array();
												foreach ($hotels as $hotel) {

														echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
														<td> <input  style="background:white; color:black; width:100%" type="input" id="'.$hotel['HotelsOutId'].'" name="update_'.$ota['HotelOtaId'].'_'.$hotel['HotelsOutId'].'_1" value="'.$hotel['HotelName'].'" > </td>
														<td> <input style="background:white; color:black; width:100%" type="input" id="'.$hotel['HotelsOutId'].'" name="update_'.$ota['HotelOtaId'].'_'.$hotel['HotelsOutId'].'_2" value="'.$hotel['HotelNameChannel'].'" > </td>
														<td> <input style="background:white; color:black; width:100%" type="input" id="'.$hotel['HotelsOutId'].'" name="update_'.$ota['HotelOtaId'].'_'.$hotel['HotelsOutId'].'_3" value="'.@$hotel['MinimumStay'].'" onkeypress="return justNumbers(event);"> </td>
														<td><center><a href="javascript:;">'.($hotel['Active']==1?'Active':'Deactive').' <i class="fa fa-exchange-alt"></i></a></center></td></tr>	 ';
														$i++;
												}

												while ($i <= 6 && $ota['HotelOtaId']==2) {

													echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
													<td> <input  style="background:white; color:black; width:100%" type="input" id="'.$i.'" name="new_'.$ota['HotelOtaId'].'_'.$i.'_1" placeholder="Property Name"> </td>
													<td> <input style="background:white; color:black; width:100%" type="input" id="'.$i.'" name="new_'.$ota['HotelOtaId'].'_'.$i.'_2" placeholder="'.$ota['Name'].' Property Name" > </td>
													<td> <input style="background:white; color:black; width:100%" type="input" id="'.$i.'" name="new_'.$ota['HotelOtaId'].'_'.$i.'_3" placeholder="Stay" onkeypress="return justNumbers(event);"> </td>
													<td><center>No Created</center></td></tr>	 ';
													$i++;
												}

												echo '</table> </div>';

												}
												?>

												</div>
												</div>
											<div class="buttons-ui">
												<a onclick="saveProps()" class="btn green">Save</a>
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
									$hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1 and main=0")->result_array();

									foreach ($hotels as  $hotel) {
										echo '<center><h1><span class="label label-primary">'.$hotel['HotelName'].'</span></h1></center>';

										$Rooms=$this->db->query("SELECT a.RoomName,a.MaxPeople,a.HotelOutId,b.RoomNameLocal,a.ChannelId,b.MaxPleopleLocal,HotelOutRoomMappingId
												FROM HotelScrapingInfo a
												left join HotelOutRoomMapping b on trim(a.RoomName)=trim(b.RoomOutName) and a.HotelOutId=b.HotelOutId and trim(a.MaxPeople) =trim(b.MaxPleopleOut)
												where a.HotelOutId=".$hotel['HotelsOutId']."
												group by a.HotelOutId,a.RoomName,MaxPeople order by a.RoomName")->result_array();
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

												$roomNameC='<a id="mapping'.$room['HotelOutRoomMappingId'].'" style="padding: 0px;" href="#" class="inline_username" data-type="select" data-pk="'.$room['RoomName'].','.$room['HotelOutId'].','.$room['ChannelId'].','.$room['MaxPeople'].'" data-value="'.trim($room['RoomNameLocal']).','.trim($room['MaxPleopleLocal']).'" data-source="'.lang_url().'scraping/allmainroom/'.$room['ChannelId'].'/1" title="Select Room Type"></a>';

												echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
												<td> '.$room['RoomName'].'-'.$room['MaxPeople'].' </td>
												<td>'.$roomNameC.'</td>
												<td><center><a onclick="droproomout('.$room['HotelOutRoomMappingId'].')" href="javascript:;">'.($hotel['Active']==1?'Active':'Deactive').' <i class="fa fa-exchange-alt"></i></a></center></td></tr>	 ';
												$i++;

											}
											echo '</table>';
										}
										else{
											echo '<center><a onclick="FindRoomtype('.$ota['HotelOtaId'].')" class="btn blue">Find Room Types</button></a>';
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
							<section id="section-3" class="sec">
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
									$hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$ota['HotelOtaId']." and active=1 and main=1")->result_array();

									foreach ($hotels as  $hotel) {
										echo '<center><h1><span class="label label-primary">'.$hotel['HotelName'].'</span></h1></center>';

										$Rooms=$this->db->query("SELECT a.RoomName,a.MaxPeople,a.HotelOutId,b.RoomNameLocal,a.ChannelId,b.MaxPleopleLocal,RoomID,HotelOutRoomMappingLocalid
												FROM HotelScrapingInfo a
												left join HotelOutRoomMappingLocal b on trim(a.RoomName)=trim(b.RoomNameLocal)  and trim(a.MaxPeople) =trim(b.MaxPleopleLocal)
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

												$roomNameC='<a id="local'.$room['HotelOutRoomMappingLocalid'].'" style="padding: 0px;" href="#" class="inline_username2" data-type="select" data-pk="'.$room['RoomName'].','.$room['HotelOutId'].','.$room['ChannelId'].','.$room['MaxPeople'].'" data-value="'.$room['RoomID'].'" data-source="'.lang_url().'channel/allroomtest" title="Select Room Type"></a>';

												echo '<tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>
												<td> '.$room['RoomName'].'-'.$room['MaxPeople'].' </td>
												<td>'.$roomNameC.'</td>
												<td><center><a onclick="droproomlocal('.$room['HotelOutRoomMappingLocalid'].')"  href="javascript:;">'.($hotel['Active']==1?'Active':'Deactive').' <i class="fa fa-exchange-alt"></i></a></center></td></tr>	 ';
												$i++;

											}
											echo '</table>';
										}
										else{
											echo '<center><a onclick="FindRoomtype('.$ota['HotelOtaId'].')" class="btn blue">Find Room Types</button></a>';
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
function droproomout(id)
{
	$("#mapping"+id).attr('data-value','');
	$("#mapping"+id).html('Empty');

	var data= {'id':id};

	$.ajax({
			type: "POST",
			dataType: "json",
			url: '<?=lang_url()?>salesmarketing/deletemapping',
			data:data,
			success:function(m)
			{
				if(m['success'])
				{
					swal({
                    title: "Success",
                    text: "Done!",
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


}
function droproomlocal(id)
{
	$("#local"+id).attr('data-value','');
	$("#local"+id).html('Empty');

	var data= {'id':id};

	$.ajax({
			type: "POST",
			dataType: "json",
			url: '<?=lang_url()?>salesmarketing/deletemappinglocal',
			data:data,
			success:function(m)
			{
				if(m['success'])
				{
					swal({
                    title: "Success",
                    text: "Done!",
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


}
function FindRoomtype(cid)
{
	var data={'channelid':cid};
	$.ajax({
			type: "POST",
			dataType: "json",
			url: '<?=lang_url()?>scraping/findroomtype',
			data:data,
			beforeSend: function() {
					showWait('Searching Rooms Types');
					setTimeout(function() { unShowWait(); }, 36000);
			},
			success:function(m)
			{

				unShowWait();
				if(m['success'])
				{
					 swal({
									title: "Done",
									text: m['message'],
									icon: "success",
									button: "Ok!",
							}).then((m)=>{
								window.location.reload();
							});
				}else{
					swal({
									title: "Upps ",
									text: m['message'],
									icon: "warning",
									button: "Ok!",
							});
				}
			}
	});
}
function saveProps()
{
	var data= $("#roomsout").serialize();

	$.ajax({
			type: "POST",
			dataType: "json",
			url: '<?=lang_url()?>scraping/saveproperty',
			data:data,
			success:function(m)
			{
				if(m['success'])
				{
					swal({
                    title: "Success",
                    text: "Done!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
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
	  $('.inline_username2').editable({
             url: function (params) {
                   return saveChange2(params);
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
function saveChange2(params)
{
	var data={'name':params['name'],'pk':params['pk'],'value':params['value']};

    $.ajax({
        type: "POST",
        dataType: "json",
        url: '<?=lang_url()?>scraping/savemapinglocal',
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
