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

<!-- <li class="heading">
    <a class="page-scroll" href="#page-top">Add Properties</a>
</li> -->
<li>
    <a class="page-scroll" href="#page-top">Get Properies</a>
</li>
<!-- <li>
    <a class="page-scroll" href="#channels">Channel List</a>
</li> 
<li>
    <a class="page-scroll" href="#setchannel">Set Channel</a>
</li> -->
<li>
    <a class="page-scroll" href="#roomtypes">Set and Update Room Types</a>
</li>
<li>
    <a class="page-scroll" href="#getroomtypes">Get Room Types</a>
</li>
<li>
    <a class="page-scroll" href="#removeroomtypes">Remove Room Types</a>
</li>
<li>
    <a class="page-scroll" href="#getchannel">Get Channels</a>
</li>
<li>
    <a class="page-scroll" href="#setchannel">Set Channels</a>
</li>
<li>
    <a class="page-scroll" href="#getchannelrooms">Get Channel Rooms</a>
</li>
<li>
    <a class="page-scroll" href="#maprooms">Map Rooms</a>
</li>
<li>
    <a class="page-scroll" href="#getmappedrooms">Get Mapped Rooms</a>
</li>
<li>
    <a class="page-scroll" href="#removemappedrooms">Remove Mapped Rooms</a>
</li>
<li>
    <a class="page-scroll" href="#setallocation">SetAllocations</a>
</li>
<li>
    <a class="page-scroll" href="#getallocation">GetAllocations</a>
</li>
<li>
    <a class="page-scroll" href="#getbooking">GetBookings</a>
</li>
<li>
    <a class="page-scroll" href="#setbooking">SetBookings</a>
</li>
<li>
    <a class="page-scroll" href="#instructions">Instructions</a>
</li>
<!-- <li>
    <a class="page-scroll" href="#setavailability">Update Availability</a>
</li>

<li>
    <a class="page-scroll" href="#getavailability">Get Availability</a>
</li> -->
<!--  
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
<?php /*
<section id="intro" class="intro-section  col-sm-12">
<div class="col-lg-12">
<h2>Set Properties</h2>
<h5>Usage </h5> 
<p>Want to set new property, you would send the following request.
</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/setProperty</h3>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
   <!--  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">JSON Request
    </a></li> -->
    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">XML Request</a></li>
    <li role="presentation"><a href="#profile1" aria-controls="profile1" role="tab" data-toggle="tab">XML Response</a></li>
    <li role="presentation"><a href="#err" aria-controls="err" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="profile">
        <div class="code-inr">
<pre>
<xmp>
<request>
<ApiKey>Partner ApiKey</ApiKey>
<Password>Partner Password</Password>
<SetProperty>
  <Property>
    <Name>Property Name</Name>
    <Address>Address</Address>
    <City>City Name</City>
    <Mobile>Property Contact Number</Mobile>
    <Email>Property Email</Email>
    <Url>Website</Url>
  </Property>
</SetProperty>
</request>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="profile1">
    <div class="code-inr">
      <pre>
<xmp>

<Response>
  <SetProperty>
    <Status>true</Status>
    <Property>
      <Id>Property Id</Id>
      <Name>Property Name</Name>
    </Property>
  </SetProperty>
</Response>

</xmp>
      </pre>
    </div>
</div>
<div role="tabpanel" class="tab-pane" id="err">
    <div class="code-inr">
      <pre>
     <b>XML Syntax Error</b> 
<xmp>
<Response>
  <SetProperty>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </SetProperty>
</Response>

</xmp>
<br>
  <b>Property Already Exists Error</b> 
<xmp>
<Response>
  <SetProperty>
    <Status>false</Status>
    <Error>Email Id is already used by another Property</Error>
  </SetProperty>
</Response>

</xmp>
<br>
  <b>Authentication Error</b> 
<xmp>
<Response>
  <SetProperty>
    <Status>false</Status>
    <Error>Invalid API Key/Invalid Password/Incorrect API or Password</Error>
  </SetProperty>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password  (required )</p> 
  </td>
  </tr>
  <tr>
    <td class="code">Name (required )</td>
      <td>
        String
      </td>
    <td>
    <p>Name of the property name</p> 
    
    
                </td>
  </tr>
  <tr>
    <td class="code">Address (required )</td>
      <td>
        String
      </td>
    <td>
    <p>Address of the Property</p> 
                </td>
  </tr>
 <tr>
  <td class="code">City (required )</td>
    <td>
      String
    </td>
  <td>
    <p>City Name of the Property</p> 
  </td>
</tr>
<tr>
  <td class="code">Mobile (required )</td>
    <td>
      String
    </td>
  <td>
    <p>Mobile Number of the Property</p> 
  </td>
</tr>
<tr>
  <td class="code">Email (required )</td>
    <td>
      String
    </td>
  <td>
    <p>Email ID of the Property</p> 
  </td>
</tr>
<tr>
  <td class="code">Url (required )</td>
    <td>
      String
    </td>
  <td>
    <p>Web Url of the Property</p> 
  </td>
</tr>
 </tbody>
</table>



</div>
</section>  */ ?>

 <section id="properties" class="property-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Get Properties</h2>
                  
                    <h5>Usage </h5>
                 
                    <p> Get single and multiple property details.</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getProperty</h3>
                    <div>
                      <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">XML Request All property</a></li>
  <li role="presentation"><a href="#single" aria-controls="single" role="tab" data-toggle="tab">XML Request Single property</a></li>
   <li role="presentation"><a href="#propertyresponse" aria-controls="propertyresponse" role="tab" data-toggle="tab">XML Response</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="all">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Partner Apikey</ApiKey>
  <Password>Partner Password</Password>
  <GetProperty>
    <Property>All</Property>
  </GetProperty>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="single">
    <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Partner Apikey</ApiKey>
  <Password>Partner Password</Password>
  <GetProperty>
    <Property>Property ID</Property>
  </GetProperty>
</request>
</xmp>

</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="propertyresponse">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <GetProperty>
    <Property>
      <Id>Property Id/Hotel Id</Id>
      <Name>Property Name</Name>
      <Address>Property Address</Address>
      <Mobile>Property Number</Mobile>
      <Email>Property Email</Email>
      <Url>Property Web Url/</Url>
    </Property>
  </GetProperty>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Passwrod</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">Property</td>
<td>
String/Integer
</td>
<td>
 <p>All - To fetch all the property details </br>
    Property Id / Hotel Id - To fetch individual property detais (required)</p>
</td>
</tr>
</tbody>
</table>
</section>

<!--Update and Set Room types start -->
<section id="roomtypes" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Create and Update Room types</h2>
                    <h5>Usage</h5>
<p> For Update room should have to provide RoomTypeId</p>

                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/setRoom</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#Setroom1" aria-controls="Setroom1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#Setroom2" aria-controls="Setroom2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#Setroom3" aria-controls="Setroom3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="Setroom1">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Partner Apikey</ApiKey>
  <Password>Partner Password</Password>
  <SetRooms>
    <propertyId>Property Id / Hotel Id</propertyId>
    <RoomTypes>
      <RoomType>
          <RoomTypeId>Room type id(Specify if you wnt update)</RoomTypeId>
          <Name>Room type Name 1</Name>
          <OccupancyAdults>Number of Adults</OccupancyAdults>
          <OccupancyChildren>Number of Children</OccupancyChildren>
          <SellingPeriod>1</SellingPeriod>
          <Description></Description>
          <Price>Price Per Night</Price>
          <PriceType>Number</PriceType>
      </RoomType>
      <RoomType>
          <RoomTypeId>Room type id(Specify if you wnt update)</RoomTypeId>
          <Name>Room type Name 2</Name>
          <OccupancyAdults>Number of Adults</OccupancyAdults>
          <OccupancyChildren>Number of Childern</OccupancyChildren>
          <SellingPeriod>1</SellingPeriod>
          <Description></Description>
          <Price>Price Per Night</Price>
          <PriceType>Number</PriceType>
      </RoomType>
    </RoomTypes>
  </SetRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="Setroom2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <SetRooms>
    <RoomTypes>
      <RoomType>
        <Success>true</Success>
        <RoomTypeId>Room ID</RoomTypeId>
        <RoomTypeIdMessage>Successfully Inserted</RoomTypeIdMessage>
      </RoomType>
      <RoomType>
        <Success>true</Success>
        <RoomTypeId>Room ID</RoomTypeId>
        <RoomTypeIdMessage>Successfully Inserted</RoomTypeIdMessage>
      </RoomType>
    </RoomTypes>
  </SetRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="Setroom3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <SetRooms>
    <Status>false</Status>
    <Error>Field is Missing/ Field Value Incorrect/Incorrect XML Formt/Authentication Error</Error>
  </SetRooms>
</Response>
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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code" span class="label label-optional">RoomTypeId</td>
<td>
Integer
</td>
<td>
 <p>Specify if you want to update the room types. Otherwise no need(Remove the tag)</p>
</td>
</tr>

<tr>
<td class="code">Name</td>
<td>
Integer
</td>
<td>
 <p>Name of the Room type  (required ) </p>
</td>
</tr>

<tr>
<td class="code" >OccupancyAdults</td>
<td>
Integer
</td>
<td>
 <p>Number of Adults  (required ) </p>
</td>
</tr>
<tr>
<td class="code" >OccupancyChildren</td>
<td>
Integer
</td>
<td>
 <p>Number of Children  (required ) </p>
</td>
</tr>
<tr>
<td class="code" span class="label label-optional">Description</td>
<td>
String
</td>
<td>
 <p>Description about the room types</p>
</td>
</tr>
<tr>
<td class="code" >SellingPeriod</td>
<td>
Integer
</td>
<td>
 <p> 
 1 - Daily <br>
 2 - Weekly <br>
 3 - Monthly <br>  (required )
 </p>
</td>
</tr>
<tr>
<td class="code" >Price</td>
<td>
Integer
</td>
<td>
 <p>Price Per Night  (required ) </p>
</td>
</tr>
<tr>
<td class="code" >PriceType</td>
<td>
Integer
</td>
<td>
 <p>
   1 - Room based pricing <br>
   2 - Guest based pricing <br> (required )
 </p>
</td>
</tr>

</tbody>
</table>

<br>






</div>
</section>
<!-- Room types End -->


<!--Get  and Set Room types start -->
<section id="getroomtypes" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Get Room types</h2>
                    <h5>Usage</h5>

                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getRoom</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getroom1" aria-controls="Setroom1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getroom2" aria-controls="getroom2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#getroom3" aria-controls="getroom3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getroom1">
      <div class="code-inr">
      <b>All RoomTypes</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetRooms>
    <propertyId>Property Id / Hotel Id</propertyId>
    <RoomTypes>All(For All Rooms under Property id)</RoomTypes>
  </GetRooms>
</request>
</xmp>
</pre>
<br>
<b>Get RoomType By Room ID</b>
<br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetRooms>
    <propertyId>Property Id / Hotel Id</propertyId>
    <RoomTypes>
      <RoomTypeId>RoomType Id</RoomTypeId>
      <RoomTypeId>RoomType Id</RoomTypeId>
    </RoomTypes>
  </GetRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getroom2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <GetRooms>
    <RoomTypes>
      <RoomType>
        <Id>Room Type ID</Id>
        <Name>Room Name</Name>
        <OccupancyAdults>Number Of Adults</OccupancyAdults>
        <OccupancyChildren>Number Of Children</OccupancyChildren>
        <SellingPeriod>Number</SellingPeriod>
        <Description>Description (If any)</Description>
        <Price>Price Per Night</Price>
        <PriceType>Number</PriceType>
      </RoomType>
    </RoomTypes>
  </GetRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getroom3">
<div class="code-inr">
<b>Property Not exist</b>
<pre>
<xmp>
<Response>
  <GetRooms>
    <Status>false</Status>
    <Error>No Room Types Found Under the Property</Error>
  </GetRooms>
</Response>

</xmp>
</pre>
<br>
<b>Room types not found </b>
<pre>
<xmp>
<Response>
  <GetRooms>
    <RoomTypes>
      <RoomType>
        <Id>1</Id>
        <Error>Room Not Exists</Error>
      </RoomType>
    </RoomTypes>
  </GetRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code" <span class="label label-optional">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code" <span class="label label-optional">RoomTypeId</td>
<td>
Integer
</td>
<td>
 <p>Room Id  (required ) </p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Get Room types End -->
<!--Remove Room types start -->
<section id="removeroomtypes" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Remove Room types</h2>
                    <h5>Usage</h5>

                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/removeRoom</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#removeroom1" aria-controls="removeroom1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#removeroom2" aria-controls="removeroom2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#removeroom3" aria-controls="removeroom3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="removeroom1">
      <div class="code-inr">
      <b>Delete All RoomTypes</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <RemoveRooms>
    <propertyId>Property Id/Hotel Id</propertyId>
    <RoomTypes>All</RoomTypes>
  </RemoveRooms>
</request>
</xmp>
</pre>
<br>
<b>Delete RoomType By Room ID</b>
<br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <RemoveRooms>
    <propertyId>Property Id/Hotel Id</propertyId>
    <RoomTypes>
      <RoomTypeId>RoomType Id</RoomTypeId>
      <RoomTypeId>RoomType Id</RoomTypeId>
    </RoomTypes>
  </RemoveRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="removeroom2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <RemoveRooms>
    <RoomTypes>
      <RoomType>
        <RoomTypeId>RoomType Id</RoomTypeId>
        <Success>Deleted Successfully</Success>
      </RoomType>
    </RoomTypes>
  </RemoveRooms>
</Response>

</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="removeroom3">
<div class="code-inr">
<b>Property Not exist</b>
<pre>
<xmp>
<Response>
  <RemoveRooms>
    <Status>false</Status>
    <Error>No Room Types Found Under the Property</Error>
  </RemoveRooms>
</Response>

</xmp>
</pre>
<br>
<b>Room types not found </b>
<pre>
<xmp>
<Response>
  <RemoveRooms>
    <RoomTypes>
      <RoomType>
        <Id>1</Id>
        <Error>Room Not Exists</Error>
      </RoomType>
    </RoomTypes>
  </RemoveRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code" <span class="label label-optional">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code" <span class="label label-optional">RoomTypeId</td>
<td>
Integer
</td>
<td>
 <p>Room Id  (required ) </p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Remove Room types End -->
<!--Get Channel start -->
<section id="getchannel" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Get Channel</h2>
                    <h5>Usage</h5>
<p>This method allowed user to get channel list. User can fetch all Active channels from
system.</p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getChannel</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getchannel1" aria-controls="getchannel1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getchannel2" aria-controls="getchannel2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#getchannel3" aria-controls="getchannel3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getchannel1">
      <div class="code-inr">
      <b>Get All Channels</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetChannels>
    <Channels>All</Channels>
  </GetChannels>
</request>
</xmp>
</pre>
<br>
<b>Get All Channels By Id</b>
<br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetChannels>
    <Channels>
      <ChannelID>Channel Id</ChannelID>
    </Channels>
  </GetChannels>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getchannel2">
    <div class="code-inr">
<pre>
<xmp>
Response>
  <GetChannels>
    <Channels>
      <Channel>
        <Id>Channel Id</Id>
        <Name>Channel Name</Name>
      </Channel>
      ...
    </Channels>
  </GetChannels>
</Response>

</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getchannel3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <GetChannels>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </GetChannels>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code"><span class="label label-optional">Optional</span>ChannelID</td>
<td>
Integer
</td>
<td>
 <p>In Channels tag user should provide either “All” or
Channel Id value.</p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Get Channels End -->
<!--Set Channel start -->
<section id="setchannel" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Set Channel</h2>
                    <h5>Usage</h5>
<p>This method allows user to set the username and password of extranet in Channel manager database for easy access of extranet page from interface. </p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/setChannel</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#setchannel1" aria-controls="setchannel1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#setchannel2" aria-controls="setchannel2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#setchannel3" aria-controls="setchannel3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="setchannel1">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <SetChannels>
    <PropertyId>Property/Hotel Id</PropertyId>
    <Channels>
    <Channel>
      <ChannelId>Channel Id</ChannelId>
      <UserName>Channel UserName</UserName>
      <UserPassword>Channel Password</UserPassword>
      <HotelChannelId>Channel Hotel Id</HotelChannelId>
      <ReservationEmail>Channel Email Id</ReservationEmail>
      <ChannelFields></ChannelFields>
    </Channel>
    </Channels>
  </SetChannels>
</request>
</xmp>
</pre>
<br>
<b>ChannelFields(Field Only Required For Particular Channel)</b>
<br>
<b>GTA</b>
<pre>
<xmp>
  <WEBID></WEBID>
</xmp>
</pre>
<br>
<b>GTA</b>
<pre>
<xmp>
  <XMLConnectivity></XMLConnectivity>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="setchannel2">
    <div class="code-inr">
<pre>
<xmp>

<Response>
  <SetChannel>
    <Channels>
      <Channel>
        <Id>Channel Id</Id>
        <Message>Success Message</Message>
      </Channel>
    </Channels>
  </SetChannel>
</Response>


</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="setchannel3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <GetChannels>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </GetChannels>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">ChannelID</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">UserName</td>
<td>
String
</td>
<td>
 <p>Channel Username(Required)</p>
</td>
</tr>
<tr>
<td class="code">UserPassword</td>
<td>
String
</td>
<td>
 <p>Channel Password(Required)</p>
</td>
</tr>
<tr>
<td class="code">HotelChannelId</td>
<td>
Integer
</td>
<td>
 <p>Channel Hotel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">ReservationEmail</td>
<td>
String
</td>
<td>
 <p>Reservation Email Address For Channel(Required)</p>
</td>
</tr>
<tr>
<td class="code">WEBID</td>
<td>
String
</td>
<td>
 <p>Unique Id provided by GTA(Required)</p>
</td>
</tr>
<tr>
<td class="code">XMLConnectivity</td>
<td>
Integer
</td>
<td>
 <p>
 1- 1 way (Import Reservation Only)<br>
 2- 2 ways (Import Rates-Update Availabilities-Rates-Restrictions) <br>
 3- 1 way (Update Rates And Availability Only) <br>
 (Required)</p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Set Channels End -->

<!--Get Channel Rooms start -->
<section id="getchannelrooms" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Get Channel Rooms</h2>
                    <h5>Usage</h5>
<p>This method returns all rooms for channel Id given and stored in Hotelavailabilities database.</p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getChannelRooms</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getchannelrooms1" aria-controls="getchannelrooms1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getchannelrooms2" aria-controls="getchannelrooms2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#getchannelrooms3" aria-controls="getchannelrooms3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getchannelrooms1">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <ChannelRooms>
    <PropertyId>Property/Hotel Id</PropertyId>
    <Rooms>
    <Channel>
      <ChannelId>Channel Id</ChannelId>
      <HotelChannelId>Hotel Channel Id</HotelChannelId>
    </Channel>
    </Rooms>
  </ChannelRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getchannelrooms2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <ChannelRooms>
    <Rooms>
      <Room>
        <ChannelId>Channel Id</ChannelId>
        <RoomID>Channel Room Id</RoomID>
        <RoomName>Channel Room Name</RoomName>
      </Room>
    </Rooms>
  </ChannelRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getchannelrooms3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <ChannelRooms>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </ChannelRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">ChannelId</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">HotelChannelId</td>
<td>
integer
</td>
<td>
 <p>Hotel Id provided by the Channel(Required)</p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Get Channel Rooms End -->
<!--Map Rooms start -->
<section id="maprooms" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Map Rooms</h2>
                    <h5>Usage</h5>
<p>This request map an existing Hotelavailabilities room type with channel room type.</p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/maprooms</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#maprooms1" aria-controls="maprooms1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#maprooms2" aria-controls="maprooms2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#maprooms3" aria-controls="maprooms3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="maprooms1">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <MapRooms>
    <PropertyId>Prperty/Hotel Id</PropertyId>
    <MapRoom>
      <ChannelId>Channel Id</ChannelId>
      <RoomTypeId>Room Id</RoomTypeId>
      <ChannelRoomId>Channel Room Id</ChannelRoomId>
      <UpdateRate></UpdateRate>
      <UpdateAvailability></UpdateAvailability>
      <RateConversion></RateConversion>
      <Status></Status>
      <MappingFields/>
    </MapRoom>
    <MapRoom>
      <ChannelId>Channel Id</ChannelId>
      <RoomTypeId>Room Id</RoomTypeId>
      <ChannelRoomId>Channel Room Id</ChannelRoomId>
      <UpdateRate></UpdateRate>
      <UpdateAvailability></UpdateAvailability>
      <RateConversion></RateConversion>
      <Status></Status>
      <MappingFields/>
    </MapRoom>            
  </MapRooms>
</request>
</xmp>
</pre>
<br>
<b>MappingFields(Field Differs Based On Channel Id)</b>
<b>Reconline</b>
<pre>
<xmp>
<DoubleOcc></DoubleOcc>
<TripleOcc></TripleOcc>
<DoublePlusChild></DoublePlusChild>
<RollawayAdult></RollawayAdult>
<RollawayChild></RollawayChild>
<MaxStay></MaxStay>
<Crib></Crib>
<Advance></Advance>
</xmp>
</pre>
<br>
<b>Expedia</b>
<pre>
<xmp>
<MaxStay></MaxStay>
</xmp>
</pre>
<br>
<b>Booking.com</b>
<pre>
<xmp>
<MaxStay></MaxStay>
<SingleUse></SingleUse>
</xmp>
</pre>
<br>
<b>Hotelbeds</b>
<pre>
<xmp>
<MaximumNumberOfDays></MaximumNumberOfDays>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="maprooms2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <MapRooms>
    <MapRoom>
      <Status>true/false</Status>
      <MapId>Map Id</MapId>
      <ChannelId>Channel Id</ChannelId>
      <RoomTypeId>Room Id</RoomTypeId>
      <ChannelRoomId>Channel Room Id</ChannelRoomId>
      <Message>Message</Message>
    </MapRoom>
    <MapRoom>
      <Status>true/false</Status>
      <MapId>Map Id</MapId>
      <ChannelId>Channel Id</ChannelId>
      <RoomTypeId>Room Id</RoomTypeId>
      <ChannelRoomId>Channel Room Id</ChannelRoomId>
      <Message>Message</Message>
    </MapRoom>
  </MapRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="maprooms3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <MapRooms>
    <MapRoom>
      <Status>false</Status>
      <ChannelId>Channel Id</ChannelId>
      <RoomTypeId>Room Id</RoomTypeId>
      <ChannelRoomId>Channel Room Id</ChannelRoomId>
      <Message>Message</Message>
    </MapRoom>
  </MapRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">Property Id</td>
<td>
Integer
</td>
<td>
 <p>Property/Hotel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">ChannelId</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">RoomTypeId</td>
<td>
integer
</td>
<td>
 <p>Room Id At Hotelavailability(Required)</p>
</td>
</tr>
<tr>
<td class="code">ChannelRoomId</td>
<td>
Integer
</td>
<td>
 <p>Channel Room Id Provided by the Hotelavailabilities(Required)</p>
</td>
</tr>
<tr>
<td class="code">UpdateRate</td>
<td>
Boolean
</td>
<td>
 <p>1- Yes<br>0 - No<br>(Required)</p>
</td>
</tr>
<tr>
<td class="code">UpdateAvailability</td>
<td>
Boolean
</td>
<td>
 <p>1- Yes<br>0 - No<br>(Required)</p>
</td>
</tr>
<tr>
<td class="code">RateConversion</td>
<td>
Integer
</td>
<td>
 <p>Optional Decimal number Cannot be lower than 0.5(Optional)</p>
</td>
</tr>
<tr>
<td class="code">DoubleOcc</td>
<td>
string
</td>
<td>
 <p>Room Price For Two Persons per Day. Ex: If price is 200 and DoubleOcc is +10%, At Extranet 220 is updated for DoubleOcc(Required)</p>
</td>
</tr>
<tr>
<td class="code">TripleOcc</td>
<td>
string
</td>
<td>
 <p>Room Price For Three Persons per Day. Ex: If price is 200 and TripleOcc is +10%, At Extranet 220 is updated for TripleOcc(Required)</p>
</td>
</tr>
<tr>
<td class="code">DoublePlusChild</td>
<td>
string
</td>
<td>
 <p>Room Price For Three Persons per Day. Ex: If price is 200 and DoublePlusChild is +10%, At Extranet 220 is updated for DoublePlusChild(Required)</p>
</td>
</tr>
<tr>
<td class="code">DoublePlusChild</td>
<td>
string
</td>
<td>
 <p>Room Price For Three Persons per Day. Ex: If price is 200 and DoublePlusChild is +10%, At Extranet 220 is updated for DoublePlusChild(Required)</p>
</td>
</tr>
<tr>
<td class="code">RollawayAdult</td>
<td>
string
</td>
<td>
 <p>Price for a adult rollaway per day(Required)</p>
</td>
</tr>
<tr>
<td class="code">RollawayChild</td>
<td>
string
</td>
<td>
 <p>Price for a child rollaway per day(Required)</p>
</td>
</tr>
<tr>
<td class="code">MaxStay</td>
<td>
string
</td>
<td>
 <p>Enter the amount of days a customer can stay as a maximum.0 = Standard (no max stay defined). If max stay is defined it must be >= min stay otherwise the update will be refused(Required)</p>
</td>
</tr>
<tr>
<td class="code">Crib</td>
<td>
Integer/Float
</td>
<td>
 <p>Price fro a crib per day(Required)</p>
</td>
</tr>
<tr>
<td class="code">Advance</td>
<td>
Integer
</td>
<td>
 <p> Enter the amount of days. A booking can be made before arrival. 0 = booking can be made up to the day of arrival. 1 = Standard booking can be made up to one day before arrival.
(Required)</p>
</td>
</tr>

<tr>
<td class="code">SingleUse</td>
<td>
Integer
</td>
<td>
 <p>Price for single use (one adult).(Required)</p>
</td>
</tr>
<tr>
<td class="code">MaximumNoOfDays</td>
<td>
Integer
</td>
<td>
 <p>
Maximum No Of Days must be greater than the Minimum Stay otherwise the update will be refused. (Required)</p>
</td>
</tr>
</tbody>
</table>

<br>
</div>
</section>
<!--Map Rooms End -->
<!--Get Mapped Rooms start -->
<section id="getmappedrooms" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Get Mapped Rooms</h2>
                    <h5>Usage</h5>
<p>This method returns all rooms which are mapped with the Channel Rooms.</p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getMappedRooms</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getmappedrooms1" aria-controls="getmappedrooms1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getmappedrooms2" aria-controls="getmappedrooms2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#getmappedrooms3" aria-controls="getmappedrooms3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getmappedrooms1">
      <div class="code-inr">
<br>
<b>Get All Mapped Rooms</b>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetMappedRooms>
    <PropertyId>Property/Hotel Id</PropertyId>
    <MappedRooms>All</MappedRooms>
  </GetMappedRooms>
</request>
</xmp>
</pre>
<br>
<b>Mapped rooms by Channel Id</b>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetMappedRooms>
    <PropertyId>Property/Hotel Id</PropertyId>
    <MappedRooms>
      <Channels>
        <ChannelId>Channel Id</ChannelId>
        <ChannelId>Channel Id</ChannelId>
        ...
      </Channels>
    </MappedRooms>
  </GetMappedRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getmappedrooms2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <GetMappedRooms>
    <Rooms>
      <Room>
        <Id>Channel Id</Id>
        <MapId>Mapping Id</MapId>
        <RoomTypeId>Roomtype Id</RoomTypeId>
        <ChannelRoomId>Channel Rooms Id</ChannelRoomId>
        <Status>Status Of the Room(enabled/disabled)</Status>
      </Room>
    </Rooms>
  </GetMappedRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getmappedrooms3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <GetMappedRooms>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </GetMappedRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">ChannelId</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Required)</p>
</td>
</tr>

</tbody>
</table>

<br>






</div>
</section>
<!--Get Mapped Rooms End -->
<!--Remove Mapped Rooms start -->
<section id="removemappedrooms" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Remove Mapped Rooms</h2>
                    <h5>Usage</h5>
<p>This method removes the mapped rooms.</p>
                 
<h3>Action URL: https://hotelavailabilities.com/rest/pms/removeMappedRooms</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#removemappedrooms1" aria-controls="removemappedrooms1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#removemappedrooms2" aria-controls="removemappedrooms2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#removemappedrooms3" aria-controls="removemappedrooms3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="removemappedrooms1">
      <div class="code-inr">

<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <RemoveMappedRooms>
    <PropertyId>Property/Hotel Id</PropertyId>
    <RemoveRooms>
      <MapId>Mapping Id</MapId>
    </RemoveRooms>
  </RemoveMappedRooms>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="removemappedrooms2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <RemoveMappedRooms>
    <Status>true</Status>
    <Success>Success Message</Success>
  </RemoveMappedRooms>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="removemappedrooms3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <RemoveMappedRooms>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </RemoveMappedRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">MapId</td>
<td>
Integer
</td>
<td>
 <p>Mapping Id(Required)</p>
</td>
</tr>

</tbody>
</table>

<br>






</div>
</section>
<!--Remove Mapped Rooms End -->
<!-- Set Allocation start -->
<section id="setallocation" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>SetAllocations</h2>
                    <h5>Usage</h5>

<p>An allocation sets the number of rooms available during any specific time frame. Prices of room can be set and their restrictions also like Min and Close to arrival with this method.</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/setAllocation</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#setallocation1" aria-controls="setallocation1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#setallocation2" aria-controls="setallocation2" role="tab" data-toggle="tab">XML Response </a></li>
     <li role="presentation"><a href="#setallocation3" aria-controls="setallocation3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="setallocation1">
      <div class="code-inr">
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <SetAllocation>
    <PropertyId>Property/Hotel Id</PropertyId>  
    <DateRange>
      <StartDate>Start Date</StartDate>
      <EndDate>End Date</EndDate>
    </DateRange>
    <Allocations>
      <Allocation>
        <Channels>
          <Channel>Channel Id</Channel>
          ....
        </Channels>
        <Weekdays>  
          <Mon>1</Mon>
          <Tue>1</Tue>
          <Wed>1</Wed>
          <Thu>1</Thu>
          <Fri>1</Fri>
          <Sat>1</Sat>
          <Sun>1</Sun>
        </Weekdays>
        <RoomTypeId>1</RoomTypeId>
        <Availability>8</Availability>
        <Min>2</Min>
        <Price>150</Price>
        <CTA>0</CTA>
        <CTD>0</CTD>
        <StopSell>0</StopSell>
        <OpenRoom>1</OpenRoom>                
      </Allocation>
      ....      
    </Allocations>
  </SetAllocation>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="setallocation2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <SetAllocation>
    <Status>true</Status>
    <Success>Updated Successfully</Success>
  </SetAllocation>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="setallocation3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <SetAllocation>
    <Allocations>
      <Status>false</Status>
      <ChannelId>8</ChannelId>
      <Error>Room Is Not Mapped</Error>
    </Allocations>
    <Allocations>
      <Status>false</Status>
      <ChannelId>11</ChannelId>
      <Error>Room Is Not Mapped</Error>
    </Allocations>
    <Allocations>
      <Status>false</Status>
      <ChannelId>8</ChannelId>
      <Error>Room Is Not Mapped</Error>
    </Allocations>
  </SetAllocation>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">StartDate</td>
<td>
Date
</td>
<td>
 <p>From Date To Update. Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">EndDate</td>
<td>
Date
</td>
<td>
 <p>End Date To Update. Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">Channel</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Required)</p>
</td>
</tr>
<tr>
<td class="code">Weekdays</td>
<td>
Boolean
</td>
<td>
 <p>
Mon - If Set to 1, restriction applies to Monday<br>
Tue - If Set to 1, restriction applies to Tuesday<br>
Wed - If Set to 1, restriction applies to Wednesday<br>
Thu - If Set to 1, restriction applies to Thursday<br>
Fri - If Set to 1, restriction applies to Friday<br>
Sat - If Set to 1, restriction applies to Saturday<br>
Sun - If Set to 1, restriction applies to Sunday<br>
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">RoomTypeId</td>
<td>
Integer
</td>
<td>
 <p>Room Id To Update
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">Availability</td>
<td>
Integer
</td>
<td>
 <p>No Of Rooms To Update
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">Min</td>
<td>
Integer
</td>
<td>
 <p>Minimum Stay
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">Price</td>
<td>
Enum
</td>
<td>
 <p>Room Price
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">CTA</td>
<td>
Boolean
</td>
<td>
 <p>Closed To Arrival<br>
 0 - Unset<br>
 1 - Set<br>
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">CTD</td>
<td>
Boolean
</td>
<td>
 <p>Closed To Departure<br>
 0 - Unset<br>
 1 - Set<br>
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">StopSell</td>
<td>
Boolean
</td>
<td>
 <p>Stop the Room Sale<br>
 0 - No Action<br>
 1 - Stop<br>
 (Required)</p>
</td>
</tr>
<tr>
<td class="code">OpenRoom</td>
<td>
Boolean
</td>
<td>
 <p>Open The Room For Sale<br>
 0 - No Action<br>
 1 - Open<br>
 (Required)</p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--SetAllocations End -->

<!-- Get Allocation start -->
<section id="getallocation" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>GetAllocations</h2>
                    <h5>Usage</h5>

<p>This method allows user to get Allocation info from Hotelsvailabilities channel manager. Method will return all rooms every day details with all infos (Availability, Price, Minimum Stay, Close To Arrival) .</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getAllocation</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getallocation1" aria-controls="getallocation1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getallocation2" aria-controls="getallocation2" role="tab" data-toggle="tab">Sample XML Response </a></li>
     <li role="presentation"><a href="#getallocation3" aria-controls="getallocation3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getallocation1">
      <div class="code-inr">
      <b>All Channel Allocations</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetAllocations>
    <PropertyId>Property/Hotel Id</PropertyId>
    <DateFrom>From Date</DateFrom>
    <DateTo>To Date</DateTo>
  </GetAllocations>
</request>
</xmp>
</pre>
<br>
<b>Channel Based Allocations</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetAllocations>
    <PropertyId>Property/Hotel Id</PropertyId>
    <DateFrom>From Date</DateFrom>
    <DateTo>To Date</DateTo>
    <ChannelID>Channel Id</ChannelID>
    ....
  </GetAllocations>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getallocation2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <GetAllocation>
    <RoomAvailability RoomId="8" ChannelID="11">
      <Date day="2016-09-25">
        <Availability>8</Availability>
        <Price>150</Price>
        <MinimumStay>2</MinimumStay>
        <CTA>0</CTA>
        <CTD>0</CTD>
        <StopSell>0</StopSell>
        <OpenRoom>1</OpenRoom>
      </Date>
      <Date day="2016-09-19">
        <Availability>8</Availability>
        <Price>150</Price>
        <MinimumStay>2</MinimumStay>
        <CTA>0</CTA>
        <CTD>0</CTD>
        <StopSell>0</StopSell>
        <OpenRoom>1</OpenRoom>
      </Date>
      
    </RoomAvailability>
  </GetAllocation>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getallocation3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <ChannelRooms>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </ChannelRooms>
</Response>

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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">StartDate</td>
<td>
Date
</td>
<td>
 <p>From Date. Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">EndDate</td>
<td>
Date
</td>
<td>
 <p>End Date . Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">ChannelID</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Optional)</p>
</td>
</tr>

</tbody>
</table>

<br>






</div>
</section>
<!--GetAllocations End -->
<!-- Get Booking start -->
<section id="getbooking" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>GetBookings</h2>
                    <h5>Usage</h5>

<p>This method allows user to get all Booking info from Hotelsvailabilities channel manager. Method will return all Connected Channel Reservation .</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/getBookings</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getbooking1" aria-controls="getbooking1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#getbooking2" aria-controls="getbooking2" role="tab" data-toggle="tab">Sample XML Response </a></li>
     <li role="presentation"><a href="#getbooking3" aria-controls="getbooking3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getbooking1">
      <div class="code-inr">
      <b>Get All Bookings</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetBookings>
    <PropertyId>Property/Hotel Id</PropertyId>
    <DateFrom>From Date</DateFrom>
    <DateTo>End Date</DateTo>
  </GetBookings>
</request>
</xmp>
</pre>
<br>
<b>Channel Based Booking</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <GetBookings>
    <PropertyId>Property/Hotel Id</PropertyId>
    <DateFrom>From Date</DateFrom>
    <DateTo>To Date</DateTo>
    <ChannelID>Channel Id</ChannelID>
  </GetBookings>
</request>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getbooking2">
    <div class="code-inr">
<pre>
<xmp>
<Response>
  <GetBookings>
    <GetBooking>
      <ChannelId>11</ChannelId>
      <IDRSV>REW275310688</IDRSV>
      <IDHOTEL>40219</IDHOTEL>
      <HOTEL>Ammon Zeus Hotel</HOTEL>
      <HOTELCITY>Kallithea</HOTELCITY>
      <CURRENCY>EUR</CURRENCY>
      <ROOMCODE>DSV</ROOMCODE>
      <RATELEVELCODE>BAR</RATELEVELCODE>
      <RATECODE>BAR</RATECODE>
      <CHECKIN>2016-11-01T00:00:00+01:00</CHECKIN>
      <CHECKOUT>2016-11-02T00:00:00+01:00</CHECKOUT>
      <REVENUE>200</REVENUE>
      <ROOMNUMBER>1</ROOMNUMBER>
      <ADULTS>2</ADULTS>
      <CHILDREN>0</CHILDREN>
      <CRIB>0</CRIB>
      <REMARK>HAI</REMARK>
      <STATUS>11</STATUS>
      <RSVCREATE>2016-03-19T07:51:00+01:00</RSVCREATE>
      <MEALSINC>0</MEALSINC>
      <BSAGENT/>
      <CCIDCARDORGANISATION>0</CCIDCARDORGANISATION>
      <CCNUMBER/>
      <CCEXPIRYDATE>0</CCEXPIRYDATE>
      <CCHOLDER/>
      <NAME>A</NAME>
      <FIRSTNAME>Vijikumar</FIRSTNAME>
      <TITLE>Mr.</TITLE>
      <ADDRESS1>Madurai</ADDRESS1>
      <ZIP>625006</ZIP>
      <CITY>Madurai</CITY>
      <COUNTRY>IN</COUNTRY>
      <TELEPHONE1>919786751889</TELEPHONE1>
      <EMAIL>vijikumar.job@gmail.com</EMAIL>
      <IDLANGUAGE>4</IDLANGUAGE>
      <COMMISSION>8</COMMISSION>
      <CORRCHECKIN>2016-11-01T00:00:00+01:00</CORRCHECKIN>
      <CORRCHECKOUT>2016-11-02T00:00:00+01:00</CORRCHECKOUT>
      <CORRRATE>200</CORRRATE>
      <CORRCOMM>8</CORRCOMM>
      <CORRROOMTYPE>DSV</CORRROOMTYPE>
      <CORRROOMNUMBER>1</CORRROOMNUMBER>
      <REASONCODE>0</REASONCODE>
      <COMMCODE>1</COMMCODE>
      <CORRRATECODE>BAR</CORRRATECODE>
      <CORRADULTS>2</CORRADULTS>
      <CORRCHILDREN>0</CORRCHILDREN>
      <CORRCRIB>0</CORRCRIB>
      <RATETYPE>0</RATETYPE>
      <IDBOOKINGSOURCE>30002</IDBOOKINGSOURCE>
      <PUBLICFLAG>1</PUBLICFLAG>
      <CANCEL>2016-11-01T18:00:00+01:00</CANCEL>
      <GUARANTEETYPE>XX</GUARANTEETYPE>
      <current_date_time>2016-09-03 15:57:16</current_date_time>
    </GetBooking>
    <GetBooking>
      <ChannelId>11</ChannelId>
      <IDRSV>REW243023104</IDRSV>
      <IDHOTEL>40219</IDHOTEL>
      <HOTEL>Ammon Zeus Hotel</HOTEL>
      <HOTELCITY>Kallithea</HOTELCITY>
      <CURRENCY>EUR</CURRENCY>
      <ROOMCODE>DGV</ROOMCODE>
      <RATELEVELCODE>BAR</RATELEVELCODE>
      <RATECODE>BAR</RATECODE>
      <CHECKIN>2016-11-01T00:00:00+01:00</CHECKIN>
      <CHECKOUT>2016-11-02T00:00:00+01:00</CHECKOUT>
      <REVENUE>111</REVENUE>
      <ROOMNUMBER>1</ROOMNUMBER>
      <ADULTS>2</ADULTS>
      <CHILDREN>1</CHILDREN>
      <CRIB>0</CRIB>
      <REMARK>vijidatings@gmail.com</REMARK>
      <STATUS>11</STATUS>
      <RSVCREATE>2016-03-21T13:45:47+01:00</RSVCREATE>
      <MEALSINC>4</MEALSINC>
      <BSAGENT>123</BSAGENT>
      <CCIDCARDORGANISATION>0</CCIDCARDORGANISATION>
      <CCNUMBER/>
      <CCEXPIRYDATE>0</CCEXPIRYDATE>
      <CCHOLDER/>
      <NAME>C2</NAME>
      <FIRSTNAME>Child 2</FIRSTNAME>
      <TITLE>Mr.</TITLE>
      <ADDRESS1>Dindigul</ADDRESS1>
      <ZIP>624006</ZIP>
      <CITY>Dindigul</CITY>
      <COUNTRY>IN</COUNTRY>
      <TELEPHONE1>919786751889</TELEPHONE1>
      <EMAIL>vijidatings@gmail.com</EMAIL>
      <IDLANGUAGE>4</IDLANGUAGE>
      <COMMISSION>8</COMMISSION>
      <CORRCHECKIN>2016-11-01T00:00:00+01:00</CORRCHECKIN>
      <CORRCHECKOUT>2016-11-02T00:00:00+01:00</CORRCHECKOUT>
      <CORRRATE>111</CORRRATE>
      <CORRCOMM>8</CORRCOMM>
      <CORRROOMTYPE>DGV</CORRROOMTYPE>
      <CORRROOMNUMBER>1</CORRROOMNUMBER>
      <REASONCODE>0</REASONCODE>
      <COMMCODE>1</COMMCODE>
      <CORRRATECODE>BAR</CORRRATECODE>
      <CORRADULTS>2</CORRADULTS>
      <CORRCHILDREN>1</CORRCHILDREN>
      <CORRCRIB>0</CORRCRIB>
      <RATETYPE>0</RATETYPE>
      <IDBOOKINGSOURCE>30002</IDBOOKINGSOURCE>
      <PUBLICFLAG>1</PUBLICFLAG>
      <CANCEL>2016-11-01T18:00:00+01:00</CANCEL>
      <GUARANTEETYPE>XX</GUARANTEETYPE>
      <current_date_time>2016-09-03 15:57:17</current_date_time>
    </GetBooking>
    <GetBooking>
      <ChannelId>11</ChannelId>
      <IDRSV>REW927395072</IDRSV>
      <IDHOTEL>40219</IDHOTEL>
      <HOTEL>Ammon Zeus Hotel</HOTEL>
      <HOTELCITY>Kallithea</HOTELCITY>
      <CURRENCY>EUR</CURRENCY>
      <ROOMCODE>DGV</ROOMCODE>
      <RATELEVELCODE>BAR</RATELEVELCODE>
      <RATECODE>BAR</RATECODE>
      <CHECKIN>2016-11-01T00:00:00+01:00</CHECKIN>
      <CHECKOUT>2016-11-02T00:00:00+01:00</CHECKOUT>
      <REVENUE>200</REVENUE>
      <ROOMNUMBER>1</ROOMNUMBER>
      <ADULTS>2</ADULTS>
      <CHILDREN>0</CHILDREN>
      <CRIB>0</CRIB>
      <REMARK>Hai Hai</REMARK>
      <STATUS>11</STATUS>
      <RSVCREATE>2016-03-21T11:01:16+01:00</RSVCREATE>
      <MEALSINC>0</MEALSINC>
      <BSAGENT>123</BSAGENT>
      <CCIDCARDORGANISATION>2</CCIDCARDORGANISATION>
      <CCNUMBER>XXXXXXxxxxxx0007</CCNUMBER>
      <CCEXPIRYDATE>1216</CCEXPIRYDATE>
      <CCHOLDER>MasterCard</CCHOLDER>
      <NAME>G</NAME>
      <FIRSTNAME>Guesdt</FIRSTNAME>
      <TITLE>Mr.</TITLE>
      <ADDRESS1>Madurai </ADDRESS1>
      <ZIP>625006</ZIP>
      <CITY>Madurai</CITY>
      <COUNTRY>IN</COUNTRY>
      <TELEPHONE1>919786751889</TELEPHONE1>
      <EMAIL>vijikumar.job@gmail.com</EMAIL>
      <IDLANGUAGE>4</IDLANGUAGE>
      <COMMISSION>8</COMMISSION>
      <CORRCHECKIN>2016-11-01T00:00:00+01:00</CORRCHECKIN>
      <CORRCHECKOUT>2016-11-02T00:00:00+01:00</CORRCHECKOUT>
      <CORRRATE>200</CORRRATE>
      <CORRCOMM>8</CORRCOMM>
      <CORRROOMTYPE>DGV</CORRROOMTYPE>
      <CORRROOMNUMBER>1</CORRROOMNUMBER>
      <REASONCODE>0</REASONCODE>
      <COMMCODE>1</COMMCODE>
      <CORRRATECODE>BAR</CORRRATECODE>
      <CORRADULTS>2</CORRADULTS>
      <CORRCHILDREN>0</CORRCHILDREN>
      <CORRCRIB>0</CORRCRIB>
      <RATETYPE>0</RATETYPE>
      <IDBOOKINGSOURCE>30002</IDBOOKINGSOURCE>
      <PUBLICFLAG>1</PUBLICFLAG>
      <CANCEL>2016-11-01T18:00:00+01:00</CANCEL>
      <GUARANTEETYPE>GX</GUARANTEETYPE>
      <current_date_time>2016-09-03 15:57:17</current_date_time>
    </GetBooking>
    <GetBooking>
      <ChannelID>1</ChannelID>
      <Booking Id="725973339" type="Book" createDateTime="2016-09-12T11:50:00Z" source="A-Expedia" status="pending"/>
      <Hotel Id="16133453"/>
      <RoomStay roomTypeID="201675252" ratePlanID="208354593A">
        <StayDate arrival="2016-10-23" departure="2016-10-30"/>
        <GuestCount adult="1" child="0" childAge="0"/>
        <PerDayRates currency="EUR">
          <PerDayRate stayDate="2016-10-23" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-24" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-25" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-26" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-27" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-28" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="2016-10-29" baseRate="25.20" promoName="10 ekptwsi 3days"/>
          <PerDayRate stayDate="" baseRate="" promoName=""/>
        </PerDayRates>
        <Total amountAfterTaxes="176.40" amountOfTaxes="" currency="EUR"/>
        <PaymentCard cardCode="VI" cardNumber="4921817786056825" seriesCode="747" expireDate="0419">
          <CardHolder name="Bestun J Salah" address="333 108th Avenue NE" city="Bellevue" stateProv="WA" country="GB" postalCode="PL68AZ"/>
        </PaymentCard>
      </RoomStay>
      <PrimaryGuest>
        <Name givenName="Bestun Salih" middleName="" surname="Karim"/>
      </PrimaryGuest>
      <SpecialRequests>
        <SpecialRequest><attributes>1 single bed</attributes></SpecialRequest>
        <SpecialRequest><attributes>Non-Smoking</attributes></SpecialRequest>
        <SpecialRequest><attributes>Hotel Collect Booking  Collect Payment From Guest</attributes></SpecialRequest>
        <SpecialRequest><attributes/></SpecialRequest>
      </SpecialRequests>
    </GetBooking>
    <GetBooking>
      <ChannelID>8</ChannelID>
      <Booking Id="498276" BookingRef="028/750703/1" Status="Confirmed" ContractId="21586" ContractType="Static" RatePlanId="31776" RatePlanCode="RO" RatePlanName="Room Only" PropertyId="21204" PropertyName="HILTON - JNBABC" City="JNB" ArrivalDate="2015-09-01" DepartureDate="2015-09-02" Nights="1" LeadName="John Smith" TotalAdults="2" TotalChildren="1" TotalCots="0" TotalCost="510" TotalRoomsCost="450.0" TotalOffers="0.00" TotalSupplements="60.00" TotalExtras="0" TotalAdjustments="0" TotalTax="total" CurrencyCode="ZAR" ModifiedDate="2015-08-12T15:12:34">
        <Contact Name="" Email=""/>
        <Rooms>
          <Room Id="116851" RoomCategory="STD" RoomType="DBL" Quantity="1">
            <Availability>
              <StayDates>
                <StayDate Date="2015-09-01" Available="true"/>
              </StayDates>
            </Availability>
            <Rates>
              <StayDates>
                <StayDate Date="2015-09-01" NettCost="450" Tax="0" Adhoced="false" Supplement1ExtraBedNettCost="60" Supplement2ExtraBedNettCost="0.0" SupplementSharingBedNettCost="0.0" SupplementCotNettCost="0.0" SupplementTax="0.0"/>
              </StayDates>
            </Rates>
          </Room>
          <Room Id="116852" RoomCategory="STD" RoomType="DBL" Quantity="1">
            <Availability>
              <StayDates>
                <StayDate Date="2015-09-01" Available="true"/>
              </StayDates>
            </Availability>
            <Rates>
              <StayDates>
                <StayDate Date="2015-09-01" NettCost="450" Tax="0" Adhoced="false" Supplement1ExtraBedNettCost="60" Supplement2ExtraBedNettCost="0.0" SupplementSharingBedNettCost="0.0" SupplementCotNettCost="0.0" SupplementTax="0.0"/>
              </StayDates>
            </Rates>
          </Room>
        </Rooms>
        <Passengers>
          <Passenger Name="John Smith" RoomTag="Adult"/>
          <Passenger Name="Jane Smith" RoomTag="Adult"/>
          <Passenger Name="Jimmy Smith" RoomTag="14"/>
          <ModifiedDate Date="2015-08-12T15:12:34"/>
        </Passengers>
      </Booking>
    </GetBooking>
    <GetBooking>
      <ChannelID>2</ChannelID>
      <reservations>
        <reservation>
          <commissionamount>58.80</commissionamount>
          <currencycode>EUR</currencycode>
          <customer>
            <address/>
            <cc_cvc/>
            <cc_expiration_date>MDEvMjAyNg</cc_expiration_date>
            <cc_name>dmlqaSBB</cc_name>
            <cc_number>NTEyMzQ1Njc4OTAxMjM0Ng</cc_number>
            <cc_type>TWFzdGVyQ2FyZA</cc_type>
            <city>.</city>
            <company/>
            <countrycode>in</countrycode>
            <dc_issue_number/>
            <dc_start_date/>
            <email/>
            <first_name>viji</first_name>
            <last_name>A</last_name>
            <remarks>I am travelling for business and I may be using a business credit card.You have a booker that prefers communication by emailSpecial Requests  Special Requests</remarks>
            <telephone>9876543210</telephone>
            <zip/>
          </customer>
          <date>2016-07-21</date>
          <hotel_id>1535511</hotel_id>
          <hotel_name>Test Hotel for HotelAvailabilities</hotel_name>
          <id>436806946</id>
          <reservation_extra_info>
            <flags>
              <flag name="no_address_reservation"/>
              <flag name="booker_is_genius"/>
            </flags>
          </reservation_extra_info>
          <rooms>
            <room>
              <arrival_date>2016-07-21</arrival_date>
              <commissionamount>58.80</commissionamount>
              <currencycode>EUR</currencycode>
              <departure_date>2016-07-23</departure_date>
              <extra_info>This suite has a bathrobe and minibar</extra_info>
              <facilities>Minibar, Telephone, Hairdryer, Bathrobe, Refrigerator, Desk, Ironing Facilities, Free toiletries, Slippers</facilities>
              <guest_name>viji A</guest_name>
              <id>153551106</id>
              <info>Enjoy a convenient Breakfast at the property for € 12 per person, per night. Children and Extra Bed Policy: All children are welcome. All further children or adults are charged  EUR 25 per night for extra beds. The maximum number of extra beds in a room is 1.  Deposit Policy: No deposit will be charged.  Cancellation Policy: If cancelled or modified up to 2 days before date of arrival,  no fee will be charged. If cancelled or modified later or in case of no-show, the total price of the reservation will be charged. </info>
              <max_children>0</max_children>
              <meal_plan>Enjoy a convenient Breakfast at the property for EUR 12 per person, per night.</meal_plan>
              <name>Suite</name>
              <numberofguests>2</numberofguests>
              <prices>
                <price date="2016-07-21" genius_rate="no" rate_id="7108957" aomunt="188"/>
                <price date="2016-07-22" genius_rate="no" rate_id="7108957" aomunt="188"/>
              </prices>
              <remarks/>
              <roomreservation_id>1036768118</roomreservation_id>
              <smoking/>
              <totalprice>392</totalprice>
              <addons>
                <addon>
                  <name>Internet</name>
                  <nights>2</nights>
                  <persons>2</persons>
                  <price_mode>3</price_mode>
                  <price_per_unit>8</price_per_unit>
                  <totalprice>16</totalprice>
                  <type>21</type>
                </addon>
              </addons>
            </room>
            <room>
              <arrival_date>2016-07-21</arrival_date>
              <commissionamount>60.80</commissionamount>
              <currencycode>EUR</currencycode>
              <departure_date>2016-07-23</departure_date>
              <extra_info>This suite has a bathrobe and minibar</extra_info>
              <facilities>Minibar, Telephone, Hairdryer, Bathrobe, Refrigerator, Desk, Ironing Facilities, Free toiletries, Slippers</facilities>
              <guest_name>viji A</guest_name>
              <id>153551106</id>
              <info>Enjoy a convenient Breakfast at the property for € 12 per person, per night. Children and Extra Bed Policy: All children are welcome. All further children or adults are charged  EUR 25 per night for extra beds. The maximum number of extra beds in a room is 1.  Deposit Policy: No deposit will be charged.  Cancellation Policy: If cancelled or modified up to 2 days before date of arrival,  no fee will be charged. If cancelled or modified later or in case of no-show, the total price of the reservation will be charged. </info>
              <max_children>0</max_children>
              <meal_plan>Enjoy a convenient Breakfast at the property for EUR 12 per person, per night.</meal_plan>
              <name>Suite</name>
              <numberofguests>2</numberofguests>
              <prices>
                <price date="2016-07-21" genius_rate="no" rate_id="7108957" aomunt="188"/>
                <price date="2016-07-22" genius_rate="no" rate_id="7108957" aomunt="188"/>
              </prices>
              <remarks/>
              <roomreservation_id>1036768119</roomreservation_id>
              <smoking/>
              <totalprice>392</totalprice>
              <addons>
                <addon>
                  <name>Internet</name>
                  <nights>2</nights>
                  <persons>2</persons>
                  <price_mode>3</price_mode>
                  <price_per_unit>8</price_per_unit>
                  <totalprice>16</totalprice>
                  <type>21</type>
                </addon>
              </addons>
            </room>
          </rooms>
          <status>new</status>
          <time>12:56:11</time>
          <totalprice>392</totalprice>
        </reservation>
      </reservations>
    </GetBooking>
  </GetBookings>
</Response>

</xmp>
</pre>
<br>
<b>Zero Results</b>
<br>
<pre>
<xmp>
<Response>
  <GetBookings>
    <GetBooking>
      <ChannelId>1</ChannelId>
      <Status>true</Status>
      <Message>Zero Results</Message>
    </GetBooking>
    <GetBooking>
      <ChannelId>11</ChannelId>
      <Status>true</Status>
      <Message>Zero Results</Message>
    </GetBooking>
  </GetBookings>
</Response>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getbooking3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <ChannelRooms>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </ChannelRooms>
</Response>

<Response>
  <GetBookings>
    <Status>false</Status>
    <Error>From Date Must be less than the To Date</Error>
  </GetBookings>
</Response>


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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">StartDate</td>
<td>
Date
</td>
<td>
 <p>From Date. Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">EndDate</td>
<td>
Date
</td>
<td>
 <p>End Date . Date Format YYYY-mm-dd (Required).</p>
</td>
</tr>
<tr>
<td class="code">ChannelID</td>
<td>
Integer
</td>
<td>
 <p>Channel Id(Optional)</p>
</td>
</tr>

</tbody>
</table>

<br>






</div>
</section>
<!--Get Bookings End -->
<!-- Set Booking start -->
<section id="setbooking" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>GetBookings</h2>
                    <h5>Usage</h5>

<p>This method allows user to set Booking informations.</p>
<h3>Action URL: https://hotelavailabilities.com/rest/pms/setBookings</h3>
<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#setbooking1" aria-controls="setbooking1" role="tab" data-toggle="tab">XML Request </a></li>
    <li role="presentation"><a href="#setbooking2" aria-controls="setbooking2" role="tab" data-toggle="tab">Sample XML Response </a></li>
     <li role="presentation"><a href="#setbooking3" aria-controls="setbooking3" role="tab" data-toggle="tab">XML Response Error</a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="setbooking1">
      <div class="code-inr">
      <b>Set Bookings</b>
      <br>
<pre>
<xmp>
<request>
  <ApiKey>Api Key</ApiKey>
  <Password>Password</Password>
  <SetBookings>
    <PropertyId>Property Id</PropertyId>
    <Bookings>
      <Booking>
        <ReservationId>Reservation ID</ReservationId>
        <CheckIn></CheckIn>
        <CheckOut></CheckOut>
        <Status></Status>
        <Date></Date>
        <Rooms>
          <Room>
            <RoomTypeId></RoomTypeId>
            <RoomsPerday></RoomsPerday>
            <MembersCount></MembersCount>
            <Price></Price>
            <Currency></Currency>
          </Room>
        </Rooms>
        <Customer>
          <Name></Name>
          <Email></Email>
          <Telephone></Telephone>
        </Customer>
        <PaymentCard>
          <Name></Name>
          <Cvv></Cvv>
          <CardNumber></CardNumber>
          <CardType></CardType>
          <CardExpiryMonth></CardExpiryMonth>
          <CardExpiryYear></CardExpiryYear>
        </PaymentCard>
      </Booking>
    </Bookings>
  </SetBookings>
</request>
</xmp>
</pre>

</div>
  </div>
<div role="tabpanel" class="tab-pane" id="setbooking2">
    <div class="code-inr">
<pre>
<xmp>
  <Response>
    <SetBookings>
        <Status>true</Status>
        <Success>Reservation Added Successfully</Success>
    </SetBookings>
</Response>
</xmp>
</pre>

</div>
</div>
<div role="tabpanel" class="tab-pane" id="setbooking3">
<div class="code-inr">
<pre>
<xmp>
<Response>
  <SetBookings>
    <Status>false</Status>
    <Error>Incorrect XML Format</Error>
  </SetBookings>
</Response>


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
    <td class="code">ApiKey</td>
      <td>
        String
      </td>
    <td>
     <p>Partner API key  (required )</p> 
    </td>
  </tr>
  <tr>
  <td class="code">Password</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Password (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>Property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">ReservationId</td>
<td>
Integer
</td>
<td>
 <p>ReservationId (required ) </p>
</td>
</tr>
<tr>
<td class="code">CheckIn</td>
<td>
Date
</td>
<td>
 <p>CheckIn Date. Date Format dd-mm-YYYY (Required).</p>
</td>
</tr>
<tr>
<td class="code">CheckOut</td>
<td>
Date
</td>
<td>
 <p>CheckOut Date . Date Format dd-mm-YYYY (Required).</p>
</td>
</tr>
<tr>
<td class="code">Status</td>
<td>
String
</td>
<td>
 <p>1. Book <br>
 2.Modify <br>
 3.Cancel</p>
</td>
</tr>

<tr>
<td class="code">Date</td>
<td>
Date
</td>
<td>
 <p>Booked Date . Date Format dd-mm-YYYY (Required).</p>
</td>
</tr>
<tr>
<td class="code">RoomTypeId</td>
<td>
Integer
</td>
<td>
 <p>Room Id at Hotelavailabilities</p>
</td>
</tr>
<tr>
<td class="code">RoomsPerday</td>
<td>
Integer
</td>
<td>
 <p>No of Rooms per day</p>
</td>
</tr>
<tr>
<td class="code">MembersCount</td>
<td>
Integer
</td>
<td>
 <p>No of Occupants</p>
</td>
</tr>
<tr>
<td class="code">Price</td>
<td>
Float
</td>
<td>
 <p>Total Price</p>
</td>
</tr>
<tr>
<td class="code">Currency</td>
<td>
String
</td>
<td>
 <p>Currency At Hotelavailabilities</p>
</td>
</tr>
<tr>
<td class="code">Name</td>
<td>
String
</td>
<td>
 <p>Name of the Customer</p>
</td>
</tr>
<tr>
<td class="code">Email</td>
<td>
String
</td>
<td>
 <p>Email of the Customer</p>
</td>
</tr>
<tr>
<td class="code">Telephone</td>
<td>
Integer
</td>
<td>
 <p>Telephone no of the Customer</p>
</td>
</tr>
<tr>
<td class="code">Name</td>
<td>
String
</td>
<td>
 <p>Name Of the Card Holder</p>
</td>
</tr>
<tr>
<td class="code">Cvv</td>
<td>
Integer
</td>
<td>
 <p>Cvv Of the Card</p>
</td>
</tr>
<tr>
<td class="code">CardNumber</td>
<td>
Integer
</td>
<td>
 <p>Credit Card Number</p>
</td>
</tr>
<tr>
<td class="code">CardType</td>
<td>
String
</td>
<td>
 <p>Credit Card Type</p>
</td>
</tr>
<tr>
<td class="code">CardExpiryMonth</td>
<td>
Integer
</td>
<td>
 <p>Credit Card Expiry Month</p>
</td>
</tr>
<tr>
<td class="code">CardExpiryYear</td>
<td>
Integer
</td>
<td>
 <p>Credit Card Expiry Year</p>
</td>
</tr>
</tbody>
</table>

<br>






</div>
</section>
<!--Set Bookings End -->
<!-- Get Allocation start -->
<section id="instructions" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>PMS Request Instructions</h2>
                    <h5>Usage</h5>

<p>For Each Request User must specify the header.</p>

<div>
<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#getallocation1" aria-controls="header" role="tab" data-toggle="tab">Header To be send </a></li>
  </ul>
<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="header">
      <div class="code-inr">
      <br>
<pre>
Content-type: text/xml; charset=utf-8
</pre>
<br>
<b>Sample Request</b>
<br>
<pre>
<xmp>
$headers = array( 
  "Content-type: text/xml; charset=utf-8",
);
</xmp>
</pre>
<br>

</div>
  </div>

</div>
</div>
</div>
</section>
<!--GetAllocations End -->
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
