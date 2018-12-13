<div class="sidebar-menu">
    <div class="logo">
        <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="<?php echo base_url();?>channel/dashboard"> <span id="logo"> <img style="width: 120px; height: 50px;" src="<?php echo base_url();?>user_assets/images/logo.png" alt="Logo"/></span> 
						
					</a>
    </div>
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    <!--/down-->
    <div class="down">
       <center>
        <?php
    		echo '<a><img style="width:100px;" src="'.base_url().(strlen($userimage)<5?"uploads/room_photos/noimage.jpg":$userimage).'"" class="img-responsive" alt=""></a>'
    	  ?>
    	  </center>
        <a><span class="name-caret"><?=$fname.' '.$lname ?> </span></a>
        <!--<p><?= ($User_Type==1?'System Administrator':'Designated User')?> </p>-->
        <p style="font-size: 14px;"> <b><?= $HotelInfo['property_name'] ?></b></p>
        <ul>
            <link rel="stylesheet" href="<?php echo base_url();?>user_asset/back/css/icon-font.min.css" type='text/css' />
            <li><a class="tooltips" href="<?php echo base_url();?>channel/profile"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
            <li><a class="tooltips" href="<?php echo base_url();?>channel/settingsProperty"><span>Settings</span><i class="lnr lnr-cog"></i></a></li>
            <li><a class="tooltips" href="<?php echo lang_url();?>channel/logout"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
        </ul>
    </div>
    <!--//down-->

    <div class="menu">
    	<div  id="boxscroll5" >
                 <div class="wrapper">  
		        <ul id="menu" >
					                                                    
					<?php 

					$channelConnected=$this->db->query("SELECT a.*,b.channel_name 
							FROM user_connect_channel a
							left join manage_channel b on a.channel_id=b.channel_id
							where hotel_id=".hotel_id()." and a.channel_id<>39;")->result_array();
						$sub=0;
						$item1=0;
						$item2=0;
						$order4=0;
						if(!isset($menudata))
						{
							if($User_Type==1)
							{
								$menudata= $this->db->query("select * from menuitem order by order1,order2,order3")->result_array();
							}
							else if($User_Type==2)
							{
								$r=$this->db->query("select * from assignedhotels where userid =".$user_id)->row_array();
								$menudata=$this->db->query("select * from menuitem where menuitemid in (".$r['menuitemids'].") order by order1,order2,order3")->result_array();
							}
						}
						foreach ($menudata as  $value) {
							$language=$this->session->userdata('site_lang');   
							$description=''; 
							switch ($language) {
								case 'english':
									$description=$value['description']; 
									break;
								case 'spanish':
									$description=$value['spanish']; 
									break;
								case 'french':
									$description=$value['french']; 
									break;
								case 'german':
									$description=$value['german']; 
									break;
								case 'italian':
									$description=$value['italian']; 	
									break;
								default:
									$description=$value['description']; 
									break;
							}							
							if($sub==1 && $item1 !=$value['order1'])
							{
								echo '</ul>';
							}
							if($item1 !=$value['order1']){echo '</li>';}


							if ($value['order2']==0 && $value['order3']==0) {
								
								echo '<li><a href="'.(strlen($value['href'])>0?base_url().$value['href']:'#').'"><i class="'.$value['iconclass'].'"></i> <span>'.$description.'</span>
								'.($value['flecha']==1?'<span class="fa fa-angle-right" style="text-align: right; "></span>':'').'</a>';
								$sub=0;
								$item1=$value['order1'];
							}
							else if($value['order2']>0 && $value['order3']==0)
							{	$item2=$value['order2'];
								if($sub==0){$sub=1;echo '<ul id="menu-academico-sub">';}

								if($value['flecha']==2)
								{
									if (count($channelConnected)==0) {
										echo '<li id="menu-academico-avaliacoes"><a href="#">No Channels Connected</a></li>';
									}
									else
									{
										echo '<div style="width:200px; height:150px; overflow:auto; "  >';
										foreach ($channelConnected as  $channel) {
										echo '<li id="menu-academico-avaliacoes"><a href="'.base_url().'mapping/mappingRooms/'.insep_encode($channel['channel_id']).'">'.$channel['channel_name'].'</a></li>';
										}
										echo '</div>';
									}
								}
								else if ($value['order1']==4) {										
										if ($order4==0) {
											echo '<div style="width:200px; height:250px; overflow:auto; "  >';
											$order4=1;
										}
										echo '<li id="menu-academico-avaliacoes"><a href="'.(strlen($value['href'])>0?base_url().$value['href']:'#').'">'.$description.'</a></li>';
										
								}
								else
								{	
									echo '<li id="menu-academico-avaliacoes"><a href="'.(strlen($value['href'])>0?base_url().$value['href']:'#').'">'.$description.'</a></li>';
								}
							}
						}
						if($sub==1){echo '</ul>';}
						echo '</li>';

					?>
			        </ul>
			</div>
	</div>

    </div>
</div>
<div class="clearfix"></div>
</div>
<script>
	 $(document).ready(function() {

	 	$("#boxscroll5").css('height',screen.height-400);
        $("#boxscroll5").niceScroll("#boxscroll5 .wrapper", { boxzoom: true }); // hw acceleration enabled when using wrapper
        $("#boxscroll5").css('overflow','');
    });
var toggle = true;

$(".sidebar-icon").click(function() {
    if (toggle) {
        $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
        $("#menu span").css({ "position": "absolute" });
    } else {
        $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
        setTimeout(function() {
            $("#menu span").css({ "position": "relative" });
        }, 400);
    }

    toggle = !toggle;
});
</script>
<!--js -->

</body>

</html>