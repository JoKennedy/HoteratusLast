 	

<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li class="active">Channel List</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->


			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">

				<div class="graph">
					<div class="table-responsive">
						<div style="float: right;" class="buttons-ui">

								<input class="btn blue"  style="background-color: white; color: black;" id="buscar" type="text"  placeholder="Search" />
		 				</div>
		 				<div  class="clearfix"></div>	
						<table id="Channellist" class="table table-bordered" > 
							<thead> <tr>  <th>Channel</th> <th style="text-align: center;">Active</th> <th style="text-align: center;">Status</th>  </tr> 
							</thead> 
							<tbody> 

								<?php if (count($AllChannel)>0) {
											$i=0;
											foreach ($AllChannel as  $value) {
												$i++;
												if ($type=='') {
													$imagen=base64_encode(file_get_contents("uploads/".($value['image']==''?'168050.jpg':$value['image'])));
												$class_status=($value['status']==1?'success':($value['status']==2?'warning':($value['status']==3?'danger':'info')));

												$show_status=($value['status']==1?'<h5><span class="label label-success">Live</span></h5>':($value['status']==2?'<h5><span class="label label-warning">New</span></h5>':($value['status']==3?'<h5><span class="label label-danger">Construction</span></h5>':'Unchecked')));

												$conect= ($value['status']==3?'Conect': ($value['conect']==0?'Connect':'Connected'));

												$showconect=($value['status']==3?'fa fa-cog': ($value['conect']==0?'fa fa-chain-broken':'fa fa-link'));
												$link=($value['status']==1?lang_url().'channel/ConfigurationChannel/'.insep_encode($value['channel_id']):'#');

												echo' <tr  class="'.($i%2?'active':'').'"> <td style="text-align: left !important;"> <img  src="data:image/png;base64,'.$imagen.'" style="height: 35px;width: 85px;">&nbsp;&nbsp; '.$value['channel_name'].' </td> <td style="text-align:center;">'.$show_status.' </td> 
												<td style="text-align: center !important;" ><a class="label btn-info"   href="'.$link.'"   ><i class="'.$showconect.'"></i> '.$conect.'</a></td> </tr>  ';
												}
												else if((insep_decode($type)==2?0:insep_decode($type))==$value['conect'])
												{
													$imagen=base64_encode(file_get_contents("uploads/".($value['image']==''?'168050.jpg':$value['image'])));
													$class_status=($value['status']==1?'success':($value['status']==2?'warning':($value['status']==3?'danger':'info')));

													$show_status=($value['status']==1?'<h5><span class="label label-success">Live</span></h5>':($value['status']==2?'<h5><span class="label label-warning">New</span></h5>':($value['status']==3?'<h5><span class="label label-danger">Construction</span></h5>':'Unchecked')));
													$conect= ($value['status']==3?'Conect': ($value['conect']==0?'Connect':'Connected'));

													$showconect=($value['status']==3?'fa fa-cog': ($value['conect']==0?'fa fa-chain-broken':'fa fa-link'));
													$link=($value['status']==1?lang_url().'channel/ConfigurationChannel/'.insep_encode($value['channel_id']):'#');

													echo' <tr  class="'.($i%2?'active':'').'"> <td style="text-align: left !important;"> <img  src="data:image/png;base64,'.$imagen.'" style="height: 35px;width: 85px;">&nbsp;&nbsp; '.$value['channel_name'].' </td> <td style="text-align:center;">'.$show_status.' </td> <td style="text-align: center !important;">  <a class="label btn-info"  href="'.$link.'"   ><i class="'.$showconect.'"></i> '.$conect.'</a> </td> </tr> ';
												}

											}
									} ?> 
							</tbody> 
						</table> 
						<?php if (count($AllChannel)==0) {echo '<h4> No channels available!</h4>';}
						else{
							echo '<div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';
						} ?> 
						<div  class="clearfix"></div>
					</div>
		
				</div>
				
			</div>
			<!--//graph-visual-->
		</div>
		<!--//outer-wp-->
		 <!--footer section start-->
		
		<!--footer section end-->
	</div>
</div>

<script type="text/javascript">
function Paginar(numeroP = 10) {
    $('#Channellist').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>