<!--outter-wp-->
<?php
$pais=$this->db->query("select * from country where id=$country")->row_array();

$lastactivity=$this->db->query("select * from new_history where Userid=$user_id order by history_date desc limit 10 ")->result_array();

?>
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Profile</li>
        </ol>
    </div>
    <!--//sub-heard-part-->
    <!--/profile-->
    <h3 class="sub-tittle pro">Profile</h3>
    <div class="profile-widget">
        <center>
        <?php
    		echo '<img style="width:100px;" src="'.base_url().(strlen($userimage)<5?"uploads/room_photos/noimage.jpg":$userimage).'"" class="img-responsive" alt="">'
    	  ?>
    	  </center>
        <h2><?=$fname.' '.$lname ?> </h2>
        <p>
            <?= ($User_Type==1?'System Administrator':'Designated User')?>
        </p>
    </div>
    <!--/profile-inner-->
    <div class="profile-section-inner">
        <div class="col-md-6 profile-info">
            <h3 class="inner-tittle">Personal Information </h3>
            <div class="main-grid3">
                <div class="p-20">
                    <div class="about-info-p">
                        <strong>Full Name</strong>
                        <br>
                        <p class="text-muted">
                            <?=$fname.' '.$lname ?>
                        </p>
                    </div>
                    <div class="about-info-p">
                        <strong>Mobile</strong>
                        <br>
                        <p class="text-muted">
                            <?=$mobile?>
                        </p>
                    </div>
                    <div class="about-info-p">
                        <strong>Email</strong>
                        <br>
                        <p class="text-muted">
                            <a href="mailto:<?= $email_address ?>">
                                <?=$email_address?>
                            </a>
                        </p>
                    </div>
                    <div class="about-info-p m-b-0">
                        <strong>Location</strong>
                        <br>
                        <p class="text-muted">
                            <?=$town.', '.$pais['country_name']?>
                        </p>
                    </div>
                </div>
            </div>
            <center>
            <div class="col-md-6">
                        <div>
                        	<?php
                        		echo '<img src="'.base_url().(strlen($userimage)<5?"uploads/room_photos/noimage.jpg":$userimage).'"" class="img-responsive" alt="">'
                        	  ?>
                            
                        </div>
            </div>
            
         	<div class="col-md-12 form-group1">

         		<form id="imagensend" accept-charset="utf-8">
         			 <label class="control-label">Imagen</label>
	            	<input style="background:white; color:black;" type="file" id="Image" name="Image">

         		</form>
         		
         		<div class="buttons-ui">
                       <a onclick="saveimage()" class="btn green">Update Imagen</a>
              </div>
	           	</center>
          	</div>
          	  
        </div>
        <div class="col-md-6 profile-info two">
            <h3 class="inner-tittle">Activity </h3>
            <div class="main-grid3 p-skill">
                <ul class="timeline">

                	<?php

                	 foreach ($lastactivity as  $value) {

                	 	echo '<li>';
                	 	if($value['extra_id']==0)
                	 	{
                	 		echo ' 
                	 				<div class="timeline-badge info"><i class="fa fa-smile-o"></i></div>
			                        <div class="timeline-panel">
			                            <div class="timeline-heading">
			                                <h4 class="timeline-title"><a href="profile.html">'.$fname.' '.$lname .'</a></h4>
			                            </div>
			                            <div class="timeline-body">
			                                <p class="time">'.(date('m/d/Y h:m:s',strtotime($value['history_date']))).'</p>
			                                <p>'.$value['description'].'</p>
			                            </div>
			                        </div>';
                	 	}
                	 	else if($value['extra_id']==1)
                	 	{
                	 		echo ' <div class="timeline-badge success"><i class="fa fa-check-circle-o"></i></div>
		                        <div class="timeline-panel">
		                            <div class="timeline-heading">
		                                <h4 class="timeline-title"><a href="profile.html">'.$fname.' '.$lname .'</a></h4>
		                            </div>
		                            <div class="timeline-body">
		                                <p class="time">'.(date('m/d/Y h:m:s',strtotime($value['history_date']))).'</p>
		                                <p>'.$value['description'].'</p>
		                            </div>
		                        </div>';
                	 	}
                	 	if($value['extra_id']==2)
                	 	{
                	 		echo '<div class="timeline-badge danger"><i class="fa fa-times-circle-o"></i></div>
			                        <div class="timeline-panel">
			                            <div class="timeline-heading">
			                                <h4 class="timeline-title"><a href="profile.html">'.$fname.' '.$lname .'</a></h4>
			                            </div>
			                            <div class="timeline-body">
			                                <p class="time">'.(date('m/d/Y h:m:s',strtotime($value['history_date']))).'</p>
			                                <p>'.$value['description'].'</p>
			                            </div>
			                        </div>';
                	 	}
                	 	echo '</li>';
                	 }

                	?>
                    
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!--/map-->
       
     
    </div>
    <!--//profile-inner-->
    <!--//profile-->
</div>
</div>
</div>
<script type="text/javascript">
	
	function saveimage()
	{
		if ($("#Image").val().length < 1) {
	        swal({
	            title: "upps, Sorry",
	            text: "Missing Field Imagen!",
	            icon: "warning",
	            button: "Ok!",
	        });
	        return;
    	}
    
        var data = new FormData($("#imagensend")[0]);
        $.ajax({
            type: "POST",
            dataType: "json",
            contentType: false,
            processData: false,
            url: "<?php echo lang_url(); ?>channel/saveuserimage",
            data: data,
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                unShowWait();
                if (msg["success"]) {
                    swal({
                        title: "Success",
                        text: "Imagen Changed!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        location.reload();
                    });
                } else {

                    swal({
                        title: "upps, Sorry",
                        text: msg["message"],
                        icon: "warning",
                        button: "Ok!",
                    });
                }





            }
        });
	}

</script>
