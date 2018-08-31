<div class="sidebar-menu">
    <div class="logo">
        <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="<?php echo base_url();?>channel/dashboard"> <span id="logo"> <img style="width: 120px; height: 50px;" src="<?php echo base_url();?>user_assets/images/logo.png" alt="Logo"/></span> 
						
					</a>
    </div>
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    <!--/down-->
    <div class="down">
        <a><img src="<?php echo base_url();?>user_asset/back/images/admin.jpg"></a>
        <a><span class=" name-caret"><?=$fname.' '.$lname ?> </span></a>
        <!--<p><?= ($User_Type==1?'System Administrator':'Designated User')?> </p>-->
        <p style="font-size: 10px;"> <b><?= $HotelInfo['property_name'] ?></b></p>
        <ul>
            <link rel="stylesheet" href="<?php echo base_url();?>user_asset/back/css/icon-font.min.css" type='text/css' />
            <li><a class="tooltips" href="<?php echo base_url();?>channel/profile"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
            <li><a class="tooltips" href="<?php echo base_url();?>channel/settingsProperty"><span>Settings</span><i class="lnr lnr-cog"></i></a></li>
            <li><a class="tooltips" href="<?php echo lang_url();?>channel/logout"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
        </ul>
    </div>
    <!--//down-->

    <div class="menu">
        <ul id="menu">
			
			<?php 
				$sub=0;
				$item1=0;
				$item2=0;
				foreach ($menudata as  $value) {
					
					if($item2>0 && $item1 !=$value['order1'])
					{
						echo '</ul>';
					}
					if($item1 !=$value['order1']){echo '</li>';}
					if ($value['order2']==0 && $value['order3']==0) {
						echo '<li><a href="'.base_url().$value['href'].'"><i class="'.$value['iconclass'].'"></i> <span>'.$value['description'].'</span></a>';
						$sub=0;
						$item1=$value['order1'];
					}
					else if($value['order2']>0 && $value['order3']==0)
					{	$item2=$value['order2'];
						if($sub==0){$sub=1;echo '<ul id="menu-academico-sub">';}
						echo '<li id="'.$value['iconclass'].'"><a href="'.base_url().$value['href'].'">'.$value['description'].'</a></li>';


					}
						

				}
			?>
           
			 <li><a href="<?php echo base_url();?>channel/dashboard"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>

            <li id="menu-academico"><a href="<?php echo base_url();?>reservation/reservationlist"><i class="fa fa-book"></i> <span>Reservation</span> <span class="fa fa-angle-right" style="float: right"></span></a>

                <ul id="menu-academico-sub">
                    <li id="menu-academico-avaliacoes"><a href="">Reports</a></li>
                    <li id="menu-academico-boletim"><a href="">Payment Methods</a></li>
                    <li id="menu-academico-avaliacoes"><a href="">Tax Categories</a></li>
                </ul>
            </li>


            <li id="menu-academico"><a href="<?php echo base_url();?>channel/calendarfull"><i class="fa fa-calendar"></i> <span>Calendar</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub">
                    <li id="menu-academico-avaliacoes"><a href="">Bulk Update</a></li>
                </ul>
            </li>
            <li id="menu-academico"><a href="#"><i class="fa fa-home"></i> <span>Property Info</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub">
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/settingsProperty">Hotel Profiles</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/managerooms">Manage Rooms</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/manageusers">Manage Users</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/billingdetails">Biling Details</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/managepolicies">Policies</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/chargepassword">Charge Password</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/managemembership">Membership Plan</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo base_url();?>channel/managechannels">Channel Details</a></li>
                </ul>
            </li>
            <li id="menu-academico"><a href="#"><i class="fa fa-chain"></i> <span>Channel</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub">
                    <li id="menu-academico-avaliacoes"><a href="<?php echo lang_url();?>channel/allChannelList">All</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo lang_url();?>channel/allChannelList/<?php echo insep_encode(2);?>">Connect</a></li>
                    <li id="menu-academico-avaliacoes"><a href="<?php echo lang_url();?>channel/allChannelList/<?php echo insep_encode(1);?>">Connected Channel</a></li>
                </ul>
            </li>
            <li id="menu-academico"><a href="#"><i class="fa fa-gear"></i> <span>Mapping</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub">
                    <li id="menu-academico-avaliacoes"><a href="">All</a></li>
                </ul>
            </li>
            <li id="menu-academico"><a href="#"><i class="lnr lnr-chart-bars"></i> <span>Booking Engine</span> </a>
            </li>
            <li id="menu-academico"><a href="#"><i class="lnr lnr-layers"></i> <span  >Point Of Sale (POS)</span> <span class="fa fa-angle-right" ></span></a>
                <ul id="menu-academico-sub">
                    <li id="menu-academico-boletim"><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="clearfix"></div>
</div>
<script>
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
<link rel="stylesheet" href="<?php echo base_url();?>user_asset/back/css/vroom.css">
<script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/vroom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/TweenLite.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_asset/back/js/CSSPlugin.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/jquery.nicescroll.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url();?>user_asset/back/js/bootstrap.min.js"></script>
</body>

</html>