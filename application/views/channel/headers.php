<!DOCTYPE HTML>
<html>

<head>
    <title>
        <?php echo $page_heading;?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript">
    addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);

    function hideURLbar() { window.scrollTo(0, 1); }
    </script>
    <!--iconos-->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>user_assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url();?>user_assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>user_assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>user_assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>user_assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>user_assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>user_assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>user_assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>user_assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url();?>user_assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>user_assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>user_assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>user_assets/favicon/favicon-16x16.png">
    <!--fin de iconos-->
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>user_asset/back/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>user_asset/back/css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="<?php echo base_url();?>user_asset/back/css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="<?php echo base_url();?>user_asset/back/css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <script src="<?php echo base_url();?>user_asset/back/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/amcharts.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/serial.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/light.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/radar.js"></script>
    <link href="<?php echo base_url();?>user_asset/back/css/barChart.css" rel='stylesheet' type='text/css' />
    <link href="<?php echo base_url();?>user_asset/back/css/fabochart.css" rel='stylesheet' type='text/css' />
    <!--clock init-->
    <script src="<?php echo base_url();?>user_asset/back/js/css3clock.js"></script>
    <!--Easy Pie Chart-->
    <!--skycons-icons-->
    <script src="<?php echo base_url();?>user_asset/back/js/skycons.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/jquery.easydropdown.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/sweetalert.min.js"></script>
    <script src="<?php echo base_url();?>user_asset/back/js/helpers.js"></script>


    <!--//skycons-icons-->
</head>

<body>

<style>
#WindowLoad
{
    position:fixed;
    top:0px;
    left:0px;
    z-index:3200;
    filter:alpha(opacity=65);
   -moz-opacity:65;
    opacity:0.65;
    background:#999;
}
</style>

    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">
                <!-- header-starts -->
                <div class="header-section">
                    <!--menu-right-->
                    <div class="top_menu">
                        <div class="main-search">
                            <form>
                                <input type="text" value="Search" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'Search';}" class="text" />
                                <input type="submit" value="">
                            </form>
                            <div class="close"><img src="<?php echo base_url();?>user_asset/back/images/cross.png" /></div>
                        </div>
                        <div class="srch">
                            <button></button>
                        </div>
                        <script type="text/javascript">
                        $('.main-search').hide();
                        $('button').click(function() {
                            $('.main-search').show();
                            $('.main-search text').focus();
                        });
                        $('.close').click(function() {
                            $('.main-search').hide();
                        });
                        </script>
                        <!--/profile_details-->
                        <div class="profile_details_left">
                            <ul class="nofitications-dropdown">
                                <li class="dropdown note dra-down">
                                    <div id="dd" class="wrapper-dropdown-3" tabindex="1" >
                                    
                                        
                                   
                                        <span><?= "ID [".$HotelInfo['hotel_id']."]" ?></span>
                                    
                                        <ul class="dropdown"  >
                                            <div style="height:500px; " id="boxscroll4">
                                                <div class="wrapper">
                                                    
                                               
                                                <?php

                                                if($User_Type==1)
                                                {
                                                    $AllHotel= get_data('manage_hotel', array('owner_id'=>$user_id))->result_array();
                                                }
                                                else if($User_Type==2)
                                                {
                                                    $r=$this->db->query("select * from assignedhotels where userid =".$user_id)->row_array();
                                                    $AllHotel=$this->db->query("select * from manage_hotel where hotel_id in (".$r['hotelids'].")")->result_array();
                                                }
                                                foreach ($AllHotel as  $value) {
													echo '<li><a onclick="changeproperty('."'".insep_encode($value['hotel_id'])."'".')" href="'.lang_url().'channel/change_property/'.insep_encode($value['hotel_id']).'" id="'.$value['hotel_id'].'" class="english">'.$value['property_name'].' [id:'.$value['hotel_id'].']</a></li>';
												}

												?>
                                            </div>
                                             </div>
                                        </ul>
                                    </div>
                                     
                                    <script type="text/javascript">

                                        function changeproperty(hotelid)
                                        {
                                            var data={"hotelid":hotelid};
                                             $.ajax({
                                                    type: "POST",
                                                    dataType: "json",
                                                    url: "<?php echo lang_url(); ?>channel/change_property",
                                                    data: data,
                                                    beforeSend: function() {
                                                        showWait();
                                                        setTimeout(function() { unShowWait(); }, 10000);
                                                    },
                                                    success: function(msg) {

                                                        var repeticion=0;
                                                        for(i=0;i<location.href.length;i++){
                                                            if(location.href.charAt(i) == "/"){
                                                                repeticion++;
                                                                if(repeticion>5)
                                                                {
                                                                    
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        if (repeticion>5) {
                                                            location.href="<?php echo lang_url(); ?>channel/Dashboard";
                                                        }
                                                        else
                                                        {
                                                            location.reload();
                                                        }
                                                       
                                                        
                                                         

                                                       }
                                                });
                                        }
                                        $(document).ready(function() {

                                            $("#boxscroll4").niceScroll("#boxscroll4 .wrapper", { boxzoom: true }); // hw acceleration enabled when using wrapper

                                        });
                                    </script>
                                    <script type="text/javascript">
                                    function DropDown(el) {
                                        this.dd = el;
                                        this.placeholder = this.dd.children('span');
                                        this.opts = this.dd.find('ul.dropdown > li');
                                        this.val = '';
                                        this.index = -1;
                                        this.initEvents();
                                    }
                                    DropDown.prototype = {
                                        initEvents: function() {
                                            var obj = this;

                                            obj.dd.on('click', function(event) {
                                                $(this).toggleClass('active');
                                                return false;
                                            });

                                            obj.opts.on('click', function() {
                                                var opt = $(this);
                                                obj.val = opt.text();
                                                obj.index = opt.index();
                                                obj.placeholder.text(obj.val);
                                                location.href = $(this).children("a").attr("href");
                                            });
                                        },
                                        getValue: function() {
                                            return this.val;
                                        },
                                        getIndex: function() {
                                            return this.index;
                                        }
                                    }

                                    $(function() {

                                        var dd = new DropDown($('#dd'));

                                        $(document).click(function() {
                                            // all dropdowns
                                            $('.wrapper-dropdown-3').removeClass('active');
                                        });

                                    });
                                    </script>
                                </li>
                                <!--<li class="dropdown note">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope-o"></i> <span class="badge">3</span></a>
                                    <ul class="dropdown-menu two first" style="width: 5px">
                                        <li>
                                            <div class="notification_header">
                                                <h3>You have 3 new messages  </h3>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/1.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet</p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li class="odd">
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/in.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet </p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/in1.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet </p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="notification_bottom">
                                                <a href="#">See all messages</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>-->
                                <li class="dropdown note">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell-o"></i> <span class="badge">5</span></a>
                                    <ul class="dropdown-menu two">
                                        <li>
                                            <div class="notification_header">
                                                <h3>You have 5 new notification</h3>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/in.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet</p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li class="odd">
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/in5.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet </p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="user_img"><img src="<?php echo base_url();?>user_asset/back/images/in8.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p>Lorem ipsum dolor sit amet </p>
                                                    <p><span>1 hour ago</span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="notification_bottom">
                                                <a href="#">See all notification</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown note">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i> <span class="badge blue1">9</span></a>
                                    <ul class="dropdown-menu two">
                                        <li>
                                            <div class="notification_header">
                                                <h3>You have 9 pending task</h3>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Database update</span><span class="percentage">40%</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar yellow" style="width:40%;"></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Dashboard done</span><span class="percentage">90%</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar green" style="width:90%;"></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Mobile App</span><span class="percentage">33%</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar red" style="width: 33%;"></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Issues fixed</span><span class="percentage">80%</span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar  blue" style="width: 80%;"></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="notification_bottom">
                                                <a href="#">See all pending task</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <!--//profile_details-->
                    </div>
                    <!--//menu-right-->
                    <div class="clearfix"></div>
                </div>
                <!-- //header-ends -->
                <!--//content-inner-->
                <!--/sidebar-menu-->
