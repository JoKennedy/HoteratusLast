<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/animate.min.css" type="text/css">
    <link href="<?php echo base_url();?>assets_pms/fonts/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/style.css" type="text/css">
 <!-- fixed header -->
 <link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/fixed-header.css">
    <script src="<?php echo base_url();?>assets_pms/js/fix-header.js" type="text/javascript"></script>
    <title>welcome</title>
    

    <script type="text/javascript">
        $(document).ready(function() {
            // grab the initial top offset of the navigation 
            var stickyNavTop = $('.navss').offset().top;
            
            // our function that decides weather the navigation bar should have "fixed" css position or not.
            var stickyNav = function(){
                var scrollTop = $(window).scrollTop(); // our current vertical position from the top
                     
                // if we've scrolled more than the navigation, change its position to fixed to stick to top,
                // otherwise change it back to relative
                if (scrollTop > stickyNavTop) { 
                    $('.navss').addClass('sticky');
                } else {
                    $('.navss').removeClass('sticky'); 
                }
            };

            stickyNav();
            // and run it again every time you scroll
            $(window).scroll(function() {
                stickyNav();
            });
        });
    </script>
 <!-- end fixed header -->

</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">


    <div class="clearfix">
    <div class="left-menu">
    <!-- Navigation -->
    
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="heading">
                        <a class="page-scroll" href="#page-top">Login</a>
                    </li>
                   <!--  <li>
                        <a class="page-scroll" href="#about">Reservation</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Login</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Booking</a>
                    </li> -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
    </nav>      
    
    </div>

    <div class="right-cont clearfix">
    <!-- Intro Section -->
    <section id="intro" class="intro-section  col-sm-12">
                <div class="col-lg-12">
                    <h2>Login</h2>
                    <h3>Introduction - Welcome</h3>
                    <h5>Usage </h5>
                    <p>To create a login and property you would send the following request.
</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/pms/CreateLogin</h3>
                    <div>

                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                       <!--  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">JSON Request
                        </a></li> -->
                        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">XML Request</a></li>
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane" id="home">
                            <div class="code-inr">
                    <pre>
{
   "Error": {
       "Id": 3,
       "Message": "Invalid user or user password"
   }
}
{
   "Error": {
       "Id": 3,
       "Message": "Invalid user or user password"
   }
}
{
   "Error": {
       "Id": 3,
       "Message": "Invalid user or user password"
   }
}
                    </pre>
                    </div>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="code-inr">
                    <pre>
                   <xmp>
                  
                      <SetLogin>
                        <Auth>
                            <ApiKey>Vendor ApiKey</ApiKey>
                            <ApiKey>Vendor email</ApiKey>
                            <ApiKey>Vendor password</ApiKey>
                        </Auth>
                        <CreateLogin>
                            <Yourname>Your Name</Yourname>
                            <Lastname>Last Name</Lastname>
                            <PropertyName>PropertyName</PropertyName>
                            <Phone>Customer family name</Phone>
                            <Website>Website</Website>
                            <Email>Customer email address</Email>
                            <Currency>EUR</Currency>
                            <Breakfast>IN</Breakfast>
                            <Username>User name</Username>
                            <Password>Password</Password>
                        </CreateLogin>
                        </SetLogin>
                        </xmp>
                    </pre>
                    </div>
                        </div>
                      </div>

                    </div>

                    <h5>Request</h5>
                 <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 30%">Field</th>
                        <th style="width: 10%">Type</th>
                        <th style="width: 70%">Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="code">Auth/UserId <span class="label label-optional">optional</span></td>
                          <td>
                            String
                          </td>
                        <td>
                        <p>Users unique ID     (required with Auth/UserPassword)</p> 
                        
                        
                                    </td>
                      </tr>
                      <tr>
                        <td class="code">Auth/UserPassword <span class="label label-optional">optional</span></td>
                          <td>
                            String
                          </td>
                        <td>
                        <p>Users password        (required with Auth/UserId)</p> 
                        
                        
                                    </td>
                      </tr>
                      <tr>
                        <td class="code">Auth/UserToken <span class="label label-optional">optional</span></td>
                          <td>
                            String
                          </td>
                        <td>
                        <p>Users auth token (see AssociateUserToPMS call to generate a Token)</p> 
                        
                        
                                    </td>
                      </tr>
                      <tr>
                        <td class="code">Auth/VendorId</td>
                          <td>
                            String
                          </td>
                        <td>
                        <p>Your Vendor ID</p> 
                        
                        
                                    </td>
                      </tr>
                      <tr>
                        <td class="code">Auth/VendorPassword</td>
                          <td>
                            String
                          </td>
                        <td>
                        <p>Your Vendor Password</p> 
                        
                        
                                    </td>
                      </tr>
                    </tbody>
                  </table>

                    

                </div>
    </section>



    </div>
    </div>

    <script src="<?php echo base_url();?>assets_pms/js/jquery.js"></script>
    <script src="<?php echo base_url();?>assets_pms/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets_pms/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url();?>assets_pms/js/wow.min.js"></script>
    <script src="<?php echo base_url();?>assets_pms/js/creative.js"></script>

    <!-- Scrolling Nav JavaScript -->
    <script src="<?php echo base_url();?>assets_pms/js/jquery.easing.min.js"></script>
    <script src="<?php echo base_url();?>assets_pms/js/scrolling-nav.js"></script>

</body>

</html>
