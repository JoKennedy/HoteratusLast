 	

<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Housekeeping</li>
				<li class="active">Rooms Status</li>
			</ol>
		   </div>
			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">
				<div style="float: right; " class="buttons-ui">
			        <a href="#createstatus" data-toggle="modal" class="btn blue">Add New Status</a>
			    </div>
			    <div  class="clearfix"></div>					
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
		</div>

	</div>
</div>
<div id="createstatus" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> </button>
            </div>
            <div>
                <div class="graph-form">
                    <form id="statusC">
                        
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Status</label>
                            <input style="background:white; color:black;" name="statusname" id="statusname" type="text" placeholder="Status Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Code</label>
                            <input style="background:white; color:black;" name="code" id="code" type="text" placeholder="Status Code" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Color</label>
                            <input  style="background:white; color:black; text-align: center;" name="color" id="color" type="text" placeholder="Status Color"  required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveStatus()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>user_asset/back/js/colorpicker.js"></script>
<script type="text/javascript">
	$('#color').simpleColor();

	function saveStatus()
	{	


		if ($("#statusname").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Name To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#code").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Code To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#color").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Select a Status Color To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}

		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/saveStatus",
		        data: $("#statusC").serialize(),
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		            if (msg["success"]) {
		                swal({
		                    title: "Success",
		                    text: "Status Created!",
		                    icon: "success",
		                    button: "Ok!",
		                }).then((n) => {
		                    location.reload();
		                });
		            } else {

		                swal({
		                    title: "upps, Sorry",
		                    text:  msg["message"],
		                    icon: "warning",
		                    button: "Ok!",
		                });
		            }

		        }
		    });
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

</script>