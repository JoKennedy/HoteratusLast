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
				if(!isset($menudata))
				{
					if($User_Type==1)
					{
						$menudata= get_data('menuitem', array('active'=>1))->result_array();
					}
				}
				foreach ($menudata as  $value) {
					
					if($sub==1 && $item1 !=$value['order1'])
					{
						echo '</ul>';
					}

					if($item1 !=$value['order1']){echo '</li>';}


					if ($value['order2']==0 && $value['order3']==0) {
						
						echo '<li><a href="'.(strlen($value['href'])>0?base_url().$value['href']:'#').'"><i class="'.$value['iconclass'].'"></i> <span>'.$value['description'].'</span>
						'.($value['flecha']==1?'<span class="fa fa-angle-right" style="text-align: right; "></span>':'').'</a>';
						$sub=0;
						$item1=$value['order1'];
					}
					else if($value['order2']>0 && $value['order3']==0)
					{	$item2=$value['order2'];
						if($sub==0){$sub=1;echo '<ul id="menu-academico-sub">';}

						if($value['flecha']==2)
						{

                    		echo '<li id="menu-academico-avaliacoes"><a href="">All</a></li>';

						}
						else
						{
							echo '<li id="menu-academico-avaliacoes"><a href="'.(strlen($value['href'])>0?base_url().$value['href']:'#').'">'.$value['description'].'</a></li>';
						}
						


					}
						

				}
				if($sub==1){echo '</ul>';}
				echo '</li>';

			?>

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