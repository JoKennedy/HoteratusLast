<?php $footer_contnt = get_data('site_config',array('id'=>'1'))->row();?>

<style type="text/css">

.logo1 img {
    height: 50px;
    width: 100px;
}


.sidebar-menu{
  background:<?php echo $this->theme_customize['sidebar_bg']!='' ? $this->theme_customize['sidebar_bg']:'333' ?>;
}

#menu li a{
  background-color:<?php echo $this->theme_customize['sidebag_li_bg']!='' ? $this->theme_customize['sidebag_li_bg']:'#333' ?>;
}

#menu li.active > a{
  background-color:<?php echo $this->theme_customize['sidebar_active']!='' ? $this->theme_customize['sidebar_active']:'#252525' ?>; 
}

#menu li a:hover{
   background-color:<?php echo $this->theme_customize['sidebar_active']!='' ? $this->theme_customize['sidebar_active']:'#252525' ?>;  
}

</style>

<div class="sidebar-menu">
<header class="logo1">

<img src="<?php echo base_url(); ?>uploads/logo/<?php echo $footer_contnt->contactus_image; ?>" alt="">

<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> 
</header>
<div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
<div class="menu">
        <ul id="menu" >

         <?php         
         
    if( admin_id()=='' && $User_Type=='2')
    {
       $redirect_access = get_data(ASSIGN,array('owner_id'=>owner_id(),'user_id'=>user_id(),'hotel_id'=>hotel_id()))->row();
           $access               = (array)json_decode($redirect_access->access);
           $user_access_view[]='';
           $user_access_edit[]='';
           foreach($access as $photo_id=>$photo_obj)
           {
                if(!empty($photo_obj))
                {
                    $photo = (array)$photo_obj;
          //echo $photo_id;
                    if(isset($photo['view'])!='')
                    {
                        $user_access_view[]=$photo_id;
                    }
                    else
                    {
                        $user_access_view[]='';
                  }
                    if(isset($photo['edit'])!='')
                    {
                        $user_access_edit[]=$photo_id;
            if($photo_id=='5')
            {
               $user_access_view[]=$photo_id;
            }
                    }
                    else
                    {
                        $user_access_edit[]='';
                    }
              }
            }
            $user_view = array_filter($user_access_view);
            $user_edit = array_filter($user_access_edit);
    }
    else
    { 
      $user_access= array();
    }

    $head_access = get_data(TBL_ACCESS,array('status'=>1))->result_array();
    foreach($head_access as $access)
    {
      extract($access);
      $links = substr($link, strpos( $link,"/") + 1); 
      $ctr = explode('/',$link);
    if(admin_id()=='' && $User_Type=='2') 
    {
      if(in_array($acc_id,$user_view)) 
      {         
      ?>
         

              <li class="<?php if(uri(3)==$links){?> active   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> active <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> active <?php } else if(uri(3)=='payment_list' && $acc_id==2){?> active <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> active <?php } else if(uri(3)=='room_types' && $acc_id==4){?> active <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> active <?php } else if(uri(3)=='policies'&& $acc_id==4){?> active <?php } else if(uri(3)=='tax_categories'&& $acc_id==3){?> active <?php } else if(uri(3)=='edit_paypal'&& $acc_id==2){?> active <?php } else if(uri(3)=='payment_bank'&& $acc_id==2){?> active <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> active <?php } else if(uri(3)=='settings'&& $acc_id==6){?> active <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> active <?php } else if(uri(3)=='all_channel'&& $acc_id==5){?> active <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> active <?php } ?> ddd">  

                <?php if($acc_id==4 || $acc_id==3 || $acc_id==9 || $acc_id==2) {
      if($acc_id==3) { ?>

        <a href="<?php echo lang_url().$link;?>"><i class="fa fa-calendar"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >
                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>inventory/bulk_update">  Bulk Updates  </a></li>
              </ul>

    <?php } else if($acc_id==4){ ?>

              <a href="<?php echo lang_url().$link;?>"><i class="fa fa-home"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/property_info"> Hotel Profiles </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_rooms"> Manage Rooms </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_subusers"> Manage Users </a></li>               

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/billing_info"> Billing Details </a></li>  

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/policies"> Policies </a></li> 

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/change_password"> Change Password </a></li>                 

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>inventory/rate_management">  Membership Plan </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_channel"> Channel Details </a></li>

              </ul>

    <?php } else if($acc_id==9) { ?>

              <a href="#<?php //echo base_url().$link;?>"><i class="fa fa-calendar"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>


              <ul id="menu-academico-sub" >

                <li id="menu-academico-boletim" ><a href="#">Trip-advisor</a></li>

                <li id="menu-academico-boletim" ><a href="#">Facebook</a></li>

              </ul>

      <?php } else if($acc_id==2) { ?>


       <a href="<?php echo lang_url().$link;?>"><i class="fa fa-book"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >                               

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/report_revenue"> Reports </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/payment_list"> Payment methods </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/tax_categories"> Tax Categories </a></li>                

              </ul>

      <?php } } else{         
       if($link=='channel/dashboard')
        {
            $fa_class = "fa fa-tachometer";
        }
        elseif($link=='channel/channel_listing')
        {
            $fa_class = "fa fa-cogs";
        }
        elseif($link=='mapping/connectlist')
        {
            $fa_class = "fa fa-cog";
        }
        else
        {
           $fa_class = "fa fa-home";
        }
        ?>

    <a href="<?php echo lang_url().$link;?>"><i class="<?php echo $fa_class; ?>"></i><span><?php echo $acc_name;?></span></a>

    <?php } ?>

    <a href="<?php echo lang_url().$link;?>"><?php echo $acc_name;?></a>

      <?php } }   elseif(isset($User_Type)=='1' || admin_id()!='' && admin_type()=='1') 
    { ?> 

      <li id="menu-academico-boletim" class="<?php if(uri(3)==$links){?> active   <?php } else if(uri(3)=='payment_policy' && $acc_id==4){?> active <?php } else if(uri(3)=='billing_info' && $acc_id==4){?> active <?php } else if(uri(3)=='manage_rooms' && $acc_id==4){?> active <?php } else if(uri(3)=='payment_list' && $acc_id==2){?> active <?php } else if(uri(3)=='manage_subusers' && $acc_id==4){?> active <?php } else if(uri(3)=='room_types' && $acc_id==4){?> active <?php } else if(uri(3)=='bulk_update'&& $acc_id==3){?> active <?php } else if(uri(3)=='policies'&& $acc_id==4){?> active <?php } else if(uri(3)=='tax_categories'&& $acc_id==2){?> active <?php } else if(uri(3)=='edit_paypal'&& $acc_id==4){?> active <?php } else if(uri(3)=='payment_bank'&& $acc_id==4){?> active <?php }else if(uri(3)=='reservation_order'&& $acc_id==2){?> active <?php } else if(uri(3)=='settings'&& $acc_id==6){?> active <?php }else if(uri(3)=='maptochannel'&& $acc_id==6){?> active <?php }  else if(uri(3)=='all_channel'&& $acc_id==5){?> active <?php } else if(uri(3)=='change_password' && $acc_id==4){ ?> active <?php } else if(uri(3)=='connected_channel'&& $acc_id==5){?> active <?php } ?> ddd">

      

  
     <?php if($acc_id==4 || $acc_id==3 || $acc_id==9 || $acc_id==2) {
      if($acc_id==3) { ?>

        <a href="<?php echo lang_url().$link;?>"><i class="fa fa-calendar"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >
                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>inventory/bulk_update">  Bulk Updates  </a></li>
              </ul>

    <?php } else if($acc_id==4){ ?>

              <a href="<?php echo lang_url().$link;?>"><i class="fa fa-home"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/property_info"> Hotel Profiles </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_rooms"> Manage Rooms </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_subusers"> Manage Users </a></li>               

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/billing_info"> Billing Details </a></li>  

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/policies"> Policies </a></li> 

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/change_password"> Change Password </a></li>                 

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>inventory/rate_management">  Membership Plan </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/manage_channel"> Channel Details </a></li>

              </ul>

    <?php } else if($acc_id==9) { ?>

              <a href="#<?php //echo base_url().$link;?>"><i class="fa fa-calendar"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>


              <ul id="menu-academico-sub" >

                <li id="menu-academico-boletim" ><a href="#">Trip-advisor</a></li>

                <li id="menu-academico-boletim" ><a href="#">Facebook</a></li>

              </ul>

      <?php } else if($acc_id==2) { ?>


       <a href="<?php echo lang_url().$link;?>"><i class="fa fa-book"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >                               

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/report_revenue"> Reports </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/payment_list"> Payment methods </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>reservation/tax_categories"> Tax Categories </a></li>                

              </ul>

      <?php } } else{         
        if($link=='channel/dashboard')
        {
            $fa_class = "fa fa-tachometer";
        }
        elseif($link=='channel/channel_listing')
        {
            $fa_class = "fa fa-cogs";
        }
        elseif($link=='mapping/connectlist')
        {
            $fa_class = "fa fa-cog";
        }
        else
        {
           $fa_class = "fa fa-home";
        }
		if($acc_name == 'Mapping'){
        ?>
		 <a href="#"><i class="<?php echo $fa_class; ?>"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >
			<?php
				$this->load->model('mapping_model');  
				$connect_channel = $this->mapping_model->connect_hotel();
				if($connect_channel)
				{
					foreach($connect_channel as $channel) 
					{
						if($channel->channel_id!='' && ( $channel->channel_id==2 && ( $channel->xml_type==3 || $channel->xml_type==2) ) || $channel->channel_id!=2)
						{
							$channel_con = $this->mapping_model->channel_connect($channel->channel_id);
							?>
							<li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($channel_con->channel_id); ?>">
							<?php echo  $channel_con->channel_name; ?></a> </li>
							<?php
						}
					}
				}
			?>
			
			
		</ul> 
		<?php } else if ($acc_name == 'Channel') {?>
			<a href="#"><i class="<?php echo $fa_class; ?>"></i><span>   <?php echo $acc_name;?> </span><span class="fa fa-angle-right" style="float: right"></span> </a>

            <ul id="menu-academico-sub" >
				<li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/channel_listing"> All </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/all_channel"> Connect </a></li>

                <li id="menu-academico-boletim" ><a href="<?php echo lang_url();?>channel/connected_channel"> Connected channel </a></li>
			</ul>
		<?php } else {?>
    <a href="<?php echo lang_url().$link;?>"><i class="<?php echo $fa_class; ?>"></i><span><?php echo $acc_name;?></span></a>


    <?php }}
     }  }?>
            
      </ul>
    </div>
  </div>
  <div class="clearfix"></div>	


