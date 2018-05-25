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
    <a class="page-scroll" href="#page-top">Add Properties</a>
</li>
<li>
    <a class="page-scroll" href="#properties">Get Properies</a>
</li>
<li>
    <a class="page-scroll" href="#channels">Channel List</a>
</li>
<li>
    <a class="page-scroll" href="#setchannel">Set Channel</a>
</li>
<li>
    <a class="page-scroll" href="#roomtypes">Set and Update Room Types</a>
</li>
<li>
    <a class="page-scroll" href="#getroomtypes">Get Room Types</a>
</li>

<li>
    <a class="page-scroll" href="#setavailability">Update Availability</a>
</li>

<li>
    <a class="page-scroll" href="#getavailability">Get Availability</a>
</li>
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
<section id="intro" class="intro-section  col-sm-12">
<div class="col-lg-12">
<h2>Set Properties</h2>
<h5>Usage </h5> 
<p>Want to set new property, you would send the following request.
</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/setProperty</h3>
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
<SetProperty>
  <ApiKey>Partner ApiKey</ApiKey>
  <ApiEmail>Partner email</ApiEmail>
  <Property>
    <Name>Property Name</Name>
    <Address>Address</Address>
    <City>City Name</City>
  </Property>
</SetProperty>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="profile1">
    <div class="code-inr">
      <pre>
<xmp>
<SetPropertiesResponse>
<Status>true</Status>
  <Property>
    <Id>Property Id</Id>
    <Name>Property Name</Name>
  </Property>
</SetPropertiesResponse>
</xmp>
      </pre>
    </div>
</div>
<div role="tabpanel" class="tab-pane" id="err">
    <div class="code-inr">
      <pre>
     <b>Multiple property error</b> 
<xmp>
<SetPropertiesResponse>
   <Status>Multi property not applicable this account</Status>
</SetPropertiesResponse>
</xmp>
<br>
  <b>Authendication error</b> 
<xmp>
<SetPropertiesResponse>
   <Status>Authendication failed</Status>
</SetPropertiesResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
  </td>
  </tr>
  <tr>
    <td class="code">Name </td>
      <td>
        String
      </td>
    <td>
    <p>Name of the property name</p> 
    
    
                </td>
  </tr>
  <tr>
    <td class="code">Address</td>
      <td>
        String
      </td>
    <td>
    <p>Address of the Property</p> 
                </td>
  </tr>
 <tr>
  <td class="code">City <span class="label label-optional">optional</span></td>
    <td>
      String
    </td>
  <td>
    <p>City Name of the Property</p> 
  </td>
</tr>
 </tbody>
</table>



</div>
</section>

 <section id="properties" class="property-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Get Properties</h2>
                  
                    <h5>Usage </h5>
                 
                    <p> Get single and multiple property details.</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/getProperties</h3>
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
<GetProperties>
  <ApiKey>API Key</ApiKey>
  <Property>All</Property>
  <ApiEmail>Email Id</ApiEmail>
</GetProperties>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="single">
    <div class="code-inr">
<pre>
<xmp>
<GetProperties>
  <ApiKey>API Key</ApiKey>
  <Property>Property id/Hotel id</Property>
  <ApiEmail>Email Id</ApiEmail>
</GetProperties>
</xmp>

</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="propertyresponse">
    <div class="code-inr">
<pre>
<xmp>
<GetPropertiesResponse>
<Status>true</Status>
<Properties>
  <Property>
    <Id>Property Id / Hotel Id</Id>
    <Name>Property Name</Name>
  </Property>
  <Property>
     <Id>Property Id / Hotel Id</Id>
    <Name>Property Name</Name>
  </Property>
 </Properties>
 </GetPropertiesResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
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



<!-- Channel start -->
<section id="channels" class="channel-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Get Channels</h2>
                  
                    <h5>Usage </h5>
                 
                    <p> Get all and particular channel details.</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/getChannels</h3>
                    <div>
                      <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#call" aria-controls="call" role="tab" data-toggle="tab">XML Request All Channels</a></li>
  <li role="presentation"><a href="#csingle" aria-controls="csingle" role="tab" data-toggle="tab">XML Request Single Channel</a></li>
   <li role="presentation"><a href="#cres" aria-controls="cres" role="tab" data-toggle="tab">XML Response Channel</a></li>
</ul>

                      <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="call">
      <div class="code-inr">
<pre>
<xmp>
<GetChannels>
    <ApiKey>API Key</ApiKey>
    <Channel>All</Channel>
    <ApiEmail>Email Id</ApiEmail>
</GetChannels>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="csingle">
    <div class="code-inr">
<pre>
<xmp>
<GetChannels>
  <ApiKey>API Key</ApiKey>
  <Channel>Channel id</Channel>
  <ApiEmail>Email Id</ApiEmail>
</GetChannels>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="cres">
<div class="code-inr">
<pre>
<xmp>
<GetChannelsResponse>
<Status>true</Status>
<Channels>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
 </Channels>
 </GetChannelsResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">Channel</td>
<td>
String/Integer
</td>
<td>
 <p>All - To fetch all the Channel details </br>
    Channel Id  - To fetch individual channel detais (required)</p>
</td>
</tr>
</tbody>
</table>
</section>
<!-- Channel End -->


<!-- Set Channel start -->
<section id="setchannel" class="setchannel-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Get Channels</h2>
                  
                    <h5>Usage </h5>
                 
                    <p> Get all and particular channel details.</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/setChannels</h3>
                    <div>
                      <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#setcha1" aria-controls="setcha1" role="tab" data-toggle="tab">XML Request All Channels</a></li>
  <li role="presentation"><a href="#setcha2" aria-controls="setcha22" role="tab" data-toggle="tab">XML Request Single Channel</a></li>
   <li role="presentation"><a href="#setcha3" aria-controls="setcha3" role="tab" data-toggle="tab">XML Response Channel</a></li>
</ul>

                      <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="call">
      <div class="code-inr">
<pre>
<xmp>
<SetChannel>
    <ApiKey>API Key</ApiKey>
    <ApiEmail>Email Id</ApiEmail>
    <PropertyId>ID</PropertyId>
    <Channels>
      <Channel>
        <ChannelId>Channel Id</ChannelId>
        <UserName>Username</UserName >
        <UserPassword>Password</UserPassword>
        <ReservationEmail>Reservation Email Id</ReservationEmail>
      </Channel>
      <Channel>
        <ChannelId>Channel Id</ChannelId>
        <UserName>Username</UserName >
        <UserPassword>Password</UserPassword>
        <UserPassword>Password</UserPassword>
        <ReservationEmail>Reservation Email Id</ReservationEmail>
      </Channel>
    </Channels>
</SetChannels>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="csingle">
    <div class="code-inr">
<pre>
<xmp>
<GetChannels>
  <ApiKey>API Key</ApiKey>
  <Channel>Channel id</Channel>
  <ApiEmail>Email Id</ApiEmail>
</GetChannels>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="cres">
<div class="code-inr">
<pre>
<xmp>
<GetChannelsResponse>
<Status>true</Status>
<Channels>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
 </Channels>
 </GetChannelsResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">Channel</td>
<td>
String/Integer
</td>
<td>
 <p>All - To fetch all the Channel details </br>
    Channel Id  - To fetch individual channel detais (required)</p>
</td>
</tr>
</tbody>
</table>
</section>
<!--Set Channel End -->



<!--Update and Set Room types start -->
<section id="roomtypes" class="roomtype-section col-sm-12">
 <div class="col-lg-12">
                    <h2>Create and Update Room types</h2>
                    <h5>Usage</h5>
<p> For Update room should have to provide RoomTypeId</p>

                 
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/setRoom</h3>
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
<SetRooms>
  <ApiKey>API Key</ApiKey>
  <ApiEmail>Email Id</ApiEmail>
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
      <Name>Room type Name 2</Name>
      <OccupancyAdults>Number of Adults</OccupancyAdults>
      <OccupancyChildren>Number of Children</OccupancyChildren>
      <SellingPeriod>1</SellingPeriod>
      <Description></Description>
      <Price>Price Per Night</Price>
      <PriceType>Number</PriceType>
    </RoomType>
 </RoomTypes>
</SetRooms>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="Setroom2">
    <div class="code-inr">
<pre>
<xmp>
<SetPropertiesResponse>
 <RoomTypes>
  <RoomType>
    <Success>true</Success>
    <RoomTypeId>51</RoomTypeId>
    <RoomTypeIdMessage>Successfully inserted</RoomTypeIdMessage>
  </RoomType>
  <RoomType>
    <Success>true</Success>
    <RoomTypeId>Room Type Id</RoomTypeId>
    <RoomTypeIdMessage>Successfully inserted</RoomTypeIdMessage>
  </RoomType>
 </RoomTypes>
</SetPropertiesResponse>

</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="Setroom3">
<div class="code-inr">
<pre>
<xmp>
<SetPropertiesResponse>
 <RoomTypes>
  <RoomType>
    <Success>false</Success>
    <RoomTypeIdMessage>Check your request.Field missing</RoomTypeIdMessage>
  </RoomType>
  </RoomTypes>
</SetPropertiesResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
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
Integer
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

                 
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/getRoomTypes</h3>
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
<pre>
<xmp>
<GetRoomTypes>
  <ApiKey>API Key</ApiKey>
  <ApiEmail>Email Id</ApiEmail>
  <propertyId>Property Id / Hotel Id</propertyId>
</GetRoomTypes>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getroom2">
    <div class="code-inr">
<pre>
<xmp>
<GetRoomTypesResponse>
<Status>true</Status>
  <RoomTypes>
    <RoomType>
        <Id>Room type Id</Id>
        <Name>Room type Name</Name>
        <OccupancyAdults>Integer</OccupancyAdults>
        <OccupancyChildren>Integer</OccupancyChildren>
        <SellingPeriod>Integer</SellingPeriod>
        <Description>Description</Description>
        <Price>price</Price>
        <PriceType>Integer (1 or 2)</PriceType>
     </RoomType>
     <RoomType>
        <Id>Room type Id</Id>
        <Name>Room type Name</Name>
        <OccupancyAdults>Integer</OccupancyAdults>
        <OccupancyChildren>Integer</OccupancyChildren>
        <SellingPeriod>Integer</SellingPeriod>
        <Description>Description</Description>
        <Price>500</Price>
        <PriceType>Integer (1 or 2)</PriceType>
     </RoomType>
    </RoomTypes>
</GetRoomTypesResponse>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getroom3">
<div class="code-inr">
<b>Property Not exist</b>
<pre>
<xmp>
<GetRoomTypesResponse>
 <RoomTypes>Property Not Exist</RoomTypes>
</GetRoomTypesResponse>
</xmp>
</pre>
<br>
<b>Room types not found </b>
<pre>
<xmp>
<GetRoomTypesResponse>
 <RoomTypes>No Roomtypes found</RoomTypes>
</GetRoomTypesResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id (required )</p> 
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
</tbody>
</table>

<br>






</div>
</section>
<!--Get Room types End -->
<!--Get Availabilty Start -->
<section id="getavailability" class="getavailability-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Get Availability</h2>
                  
                    <h5>Usage </h5>
                 
                    <p> Get all availability between date.</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/getAvailability</h3>
                    <div>
                      <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#getavail1" aria-controls="getavail1" role="tab" data-toggle="tab">XML Request</a></li>
  <li role="presentation"><a href="#getavail2" aria-controls="getavail2" role="tab" data-toggle="tab">XML Response</a></li>
   <li role="presentation"><a href="#getavail3" aria-controls="getavail3" role="tab" data-toggle="tab">XML Response Error</a></li>
</ul>

                      <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="getavail1">
      <div class="code-inr">
<pre>
<xmp>
<GetAvailability>
    <ApiKey>API Key</ApiKey>
    <ApiEmail>Email Id</ApiEmail>
    <propertyId>Property Id / Hotel Id </propertyId>
    <From>YYYY-MM-DD</From>
    <To>YYYY-MM-DD</To>
</GetAvailability>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="getavail2">
    <div class="code-inr">
<pre>
<xmp>
<GetChannels>
  <ApiKey>API Key</ApiKey>
  <Channel>Channel id</Channel>
  <ApiEmail>Email Id</ApiEmail>
</GetChannels>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="getavail3">
<div class="code-inr">
<pre>
<xmp>
<GetChannelsResponse>
<Status>true</Status>
<Channels>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
 </Channels>
 </GetChannelsResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">From</td>
<td>
Date - YYYY-MM-DD
</td>
<td>
 <p>From Date - Date should be YYYY-MM-DD format  (required ) </p>
</td>
</tr>
<tr>
<td class="code">To</td>
<td>
Date - YYYY-MM-DD
</td>
<td>
 <p>To Date - Date should be YYYY-MM-DD format  (required ) </p>
</td>
</tr>
</tbody>
</table>
</section>
<!-- GEt Availability End -->


<!--Set Availabilty Start -->
<section id="setavailability" class="setavailability-section col-sm-12">
            <div class="col-lg-12">
                    <h2>Set and Update Availability</h2>
                 
                    <h5>Usage </h5>
                    <p> Set all availability between date.</p>
<h3>Action URL: http://channelmanager.osiztechnologies.com/api/setAvailability</h3>
                    <div>
                      <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#setavail1" aria-controls="setavail1" role="tab" data-toggle="tab">XML Request</a></li>
  <li role="presentation"><a href="#setavail2" aria-controls="setavail2" role="tab" data-toggle="tab">XML Response</a></li>
   <li role="presentation"><a href="#setavail3" aria-controls="setavail3" role="tab" data-toggle="tab">XML Response Error</a></li>
</ul>

                      <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="setavail1">
      <div class="code-inr">
<pre>
<xmp>
<SetAvailability>
    <ApiKey>API Key</ApiKey>
    <ApiEmail>Email Id</ApiEmail>
    <propertyId>Property Id / Hotel Id </propertyId>
    <StartDate>YYYY-MM-DD</StartDate>
    <EndDate>YYYY-MM-DD</EndDate>
</SetAvailability>
</xmp>
</pre>
</div>
  </div>
<div role="tabpanel" class="tab-pane" id="setavail2">
    <div class="code-inr">
<pre>
<xmp>
<GetChannels>
  <ApiKey>API Key</ApiKey>
  <Channel>Channel id</Channel>
  <ApiEmail>Email Id</ApiEmail>
</GetChannels>
</xmp>
</pre>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="setavail3">
<div class="code-inr">
<pre>
<xmp>
<GetChannelsResponse>
<Status>true</Status>
<Channels>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
  <Channel>
    <Id>Channel Id</Id>
    <Name>Channel Name</Name>
  </Channel>
 </Channels>
 </GetChannelsResponse>
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
  <td class="code">ApiEmail</td>
    <td>
      String
    </td>
  <td>
   <p>Partner Email id       (required )</p> 
  </td>
  </tr>
<tr>
<td class="code">PropertyId</td>
<td>
Integer
</td>
<td>
 <p>property Id / Hotel Id  (required ) </p>
</td>
</tr>
<tr>
<td class="code">StartDate</td>
<td>
Date - YYYY-MM-DD
</td>
<td>
 <p>From Date - Date should be YYYY-MM-DD format  (required ) </p>
</td>
</tr>
<tr>
<td class="code">EndDate</td>
<td>
Date - YYYY-MM-DD
</td>
<td>
 <p>To Date - Date should be YYYY-MM-DD format  (required ) </p>
</td>
</tr>
</tbody>
</table>
</section>
<!-- Set Availability End -->


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
