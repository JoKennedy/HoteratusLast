
<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Housekeeping</li>
				<li class="active">Rooms Status</li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->


			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">

				<div class="graph">
				
				<?php

						if(count($AllRooms)>0)
						{

							echo ' <div id="tabs" class="tabs"><div class="vali-form">
                                            <div class="graph">
                                                <nav class="second">';
							foreach ($AllRooms as $value) {                     
	                            echo '<a>';
	                            echo $value['property_name'];
	                            echo '</a>';
                            }
                            echo '</nav>';                      
                                                 
                                   	       
                             foreach ($AllRooms as  $value) {
                                 echo '<div class="context">';
                               // $amenitiesdestails=$this->db->query("select * from room_amenities where type_id=".$value['id']." and (hotelid=0 or hotelid=".$Roominfo['hotel_id'].") ")->result_array();
                                $numbers =(explode(",", $value['existing_room_number']));
                                foreach ($numbers  as $number) {

	                                if(trim($number))
	                                {
	                                	echo '<div class="form-group">
							                        <div class="col-md-6">
							                            <div class="input-group input-icon right">
							                                <span class="input-group-addon">
							                                    '.$number.'
							                                </span>
							                                <input value=""  id="hotelid" name="hotelid" class="form-control1 icon" type="text" placeholder="Hotel ID">
							                                <center><a class="btn blue"><i class="far fa-edit"></i>Change Status</a></center>
							                            </div>
							                        </div>
							                </div>';
	                                }
	                                   
                                }
                                echo '</div>';

                            }
                            echo' </div></div></div>';                       
                                               
							
						}
						else
						{
							echo '<center><h2>No Rooms Created</h2></center>';
						}
						

					?>

			
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

</script>