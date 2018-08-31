<div style="background-image:url(data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>); background-position:top center; background-repeat:no-repeat; padding-top:150px; padding-bottom:150px;"> 

</div>


<html >
<head>
  <meta charset="UTF-8">
  <title>Pricing</title>
  
  
  
      <style>

*, *:before, *:after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
}

body {
  font: 14px/1 'Open Sans', sans-serif;
  color: #555;
  background: #eee;
}

h1 {
  padding: 50px 0;
  font-weight: 400;
  text-align: center;
}

p {
  margin: 0 0 20px;
  line-height: 1.5;
}

main {
  min-width: 320px;
  max-width: 1120px;
  padding: 50px;
  margin: 0 auto;
  background: #fff;
}

section {
  display: none;
  padding: 20px 0 0;
  border-top: 1px solid #ddd;
}

input {
  display: none;
}

label {
  display: inline-block;
  margin: 0 0 -1px;
  padding: 15px 25px;
  font-weight: 900;
  text-align: center;
  color: #bbb;
  border: 1px solid transparent;
}

label:before {
  font-family: fontawesome;
  font-weight: normal;
  margin-right: 10px;
}

/*
label[for*='1']:before {
  content: '\f1cb';
}

label[for*='2']:before {
  content: '\f17d';
}


label[for*='3']:before {
  content: '\f16b';
}

label[for*='4']:before {
  content: '\f1a9';
}
*/
label:hover {
  color: #888;
  cursor: pointer;
}

input:checked + label {
  color: #555;
  border: 1px solid #ddd;
  border-top: 2px solid orange;
  border-bottom: 1px solid #fff;
}

#tab1:checked ~ #content1,
#tab2:checked ~ #content2,
#tab3:checked ~ #content3,
#tab4:checked ~ #content4 {
  display: block;
}

@media screen and (max-width: 650px) {
  label {
    font-size: 0;
  }

  label:before {
    margin: 0;
    font-size: 18px;
  }
}
@media screen and (max-width: 400px) {
  label {
    padding: 15px;
  }
}

    </style>

 

</head>

<body>


<main>
  
  <input id="tab1" type="radio" name="tabs" checked>
  <label for="tab1">Individual Property</label>
    
  <input id="tab2" type="radio" name="tabs">
  <label for="tab2">Multi Property</label>

    
  <section id="content1">
   


<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">  
 
    <title>CSS3 Feature Table</title> 
    <style type="text/css">    
/*General styles*/
body
{
  margin: 0;
  padding: 0;
  
}

#main
{
  width: 1000px;
  margin: 50px auto 0 auto;
  background: white;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  padding: 30px;
  border: 1px solid #adaa9f;
  -moz-box-shadow: 0 2px 2px #9c9c9c;
  -webkit-box-shadow: 0 2px 2px #9c9c9c;
}

/*Features table------------------------------------------------------------*/
.features-table
{
  width: 100%;
  margin: 0 auto;
  border-collapse: separate;
  border-spacing: 0;
  text-shadow: 0 1px 0 #fff;
  color: #2a2a2a;
  background: #fafafa;  
  background-image: -moz-linear-gradient(top, #fff, #eaeaea, #fff); /* Firefox 3.6 */
  background-image: -webkit-gradient(linear,center bottom,center top,from(#fff),color-stop(0.5, #eaeaea),to(#fff)); 
}

.features-table td
{
  height: 50px;
  line-height: 50px;
  padding: 0 20px;
  border-bottom: 1px solid #cdcdcd;
  box-shadow: 0 1px 0 white;
  -moz-box-shadow: 0 1px 0 white;
  -webkit-box-shadow: 0 1px 0 white;
  white-space: nowrap;
  text-align: center;
}

/*Body*/
.features-table tbody td
{
  text-align: center;
  font: normal 12px Verdana, Arial, Helvetica;
  width: 150px;
}

.features-table tbody td:first-child
{
  width: auto;
  text-align: left;
}

.features-table td:nth-child(2),.features-table td:nth-child(3)
{
  background: #A9DFBF;
  background: rgba(144,144,144,0.15);
  border-right: 1px solid white;
}

.features-table td:nth-child(4)
{
  background: ##A9DFBF;
  background: rgba(144,144,144,0.15);
  border-right: 1px solid white;
}


.features-table td:nth-child(6)
{
  background: #e7f3d4;  
  background: rgba(184,243,85,0.3);
}

/*Header*/
.features-table thead td
{
  font: bold 1.3em 'trebuchet MS', 'Lucida Sans', Arial;  
  -moz-border-radius-topright: 10px;
  -moz-border-radius-topleft: 10px; 
  border-top-right-radius: 10px;
  border-top-left-radius: 10px;
  border-top: 1px solid #eaeaea; 
}

.features-table thead td:first-child
{
  border-top: none;
}

/*Footer*/
.features-table tfoot td
{
  font: bold 1.4em Georgia;  
  -moz-border-radius-bottomright: 10px;
  -moz-border-radius-bottomleft: 10px; 
  border-bottom-right-radius: 10px;
  border-bottom-left-radius: 10px;
  border-bottom: 1px solid #dadada;
}

.features-table tfoot td:first-child
{
  border-bottom: none;
}
    </style> 

    <div id="main">
    
    <table class="features-table">
        <thead>
      
          <tr>
            <td></td>
         <td>Free Trial</td>
         <td> Calendar</td>
         <td> Deluxe</td>
         <td> Premium </td>
         <td> Unlimited</td>


          </tr>
        </thead>

        <tfoot>
          <tr>
           <td></td>
            <td>Free</td>
           <td>$5</td>
           <td>$69</td>
           <td>$79</td>
           <td>$89</td>
          </tr>
        </tfoot>
                 
        <tbody>
          <tr>
            <td>Front Desk</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
	 </tr>

             <td>Multi Language and Multi Currency</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

	      <td>Customer Relations Management (CRM)</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              
          </tr>

             <td>Online Guest Satisfaction Surveys </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

          <tr>
            <td>Housekeeping </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
                <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>  
                <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
          <tr>
            <td>Unlimited Points of Sale</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>

            
          <tr>
            <td>Customizable Reports </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>

            <tr>
            <td>Competitive Set Analytics Dashboard <br> 
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             

          </tr>

            <tr>
            <td>Reputation Management System</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td>6 Website Booking Engine Templates<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">with Payment Gateway</font></a> </div>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>


          </tr>
            <tr>
            <td>Rate Management System</td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td>Facebook Booking Button </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td> Management Mobile App </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          
</tr>
            <td> Guests Mobile App </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

</tr>
            <td> Booking Widget for your website </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

 <tr>
            <td> Accounting Module </td>
           <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>
<tr>
            <td>Human Resources Module </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

<tr>
            <td> Purchasing </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>
<tr>
            <td> Costs Module</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>


          </tr>

           <tr>
             <td colspan="6" style="text-align:center; background-color:#8c8c8c; font-weight:bold; color:#FFF;">Interfaces</td>
           </tr>
            </tr>
            <tr>
            <td>Channel Manager: 3 Channels<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td> 
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	    </tr>


<tr>
            <td>Channel Manager: 8 Channels<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
      <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>  
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	             </tr>

<tr>
            <td>Channel Manager: Unlimited Channels <br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
      <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

            <tr>
<tr>
            <td>GDS Connectivity</td>
           <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
           </tr>


            <td> Quickbooks Interface </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

            <tr>
        <td colspan="6" style="text-align:center; background-color:#8c8c8c; font-weight:bold; color:#FFF;">Services</td>
      </tr>

           <tr>
            <td>24 x 7 Live Chat Support </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

               <tr>
            <td>Setup &amp; Training </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

  
          </tr>

     
	<td>Unlimited No. of Users</td>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

           </tr>

               <tr>
            <td>Get Started</td>
             <td><a href="fs" target="_blank" >Get now</a></td>
               <td><a href="fs" target="_blank" >Buy now</a></td>
                 <td><a href="fs" target="_blank" >Buy now</a></td>
                 <td><a href="fs" target="_blank" >Buy now</a></td>
		 <td><a href="fs" target="_blank" >Buy now</a></td>
             
             
          </tr>


        </tbody>
    </table>

  </div>
 </body>
</html>




  </section>
    
  <section id="content2">


    </style> 

    <div id="main">
    
    <table class="features-table">
        <thead>
       
          <tr>
            <td></td>
          <td>Free Trial</td>
         <td> Calendar</td>
         <td> Deluxe </td>
         <td> Premium</td>
         <td> Unlimited</td>


          </tr>
        </thead>

        <tfoot>
          <tr>
            <td></td>
            <td>Free</td>
           <td>$29</td>
           <td>$139</td>
           <td>$159</td>
           <td>$189</td>
          </tr>
        </tfoot>
                 
        <tbody>
         <tr>
          <td>Front Desk</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
	 </tr>

             <td>Multi Language and Multi Currency</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

	      <td>Customer Relations Management (CRM)</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              
          </tr>

             <td>Online Guest Satisfaction Surveys </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

          <tr>
            <td>Housekeeping </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
                <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>  
                <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
          <tr>
            <td>Unlimited Points of Sale</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>

            
          <tr>
            <td>Customizable Reports </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>

            <tr>
            <td>Competitive Set Analytics Dashboard <br> 
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             

          </tr>

            <tr>
            <td>Reputation Management System</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td>6 Website Booking Engine Templates<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">with Payment Gateway</font></a> </div>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>
            <tr>
            <td>Rate Management System</td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td>Facebook Booking Button </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
          </tr>
            <tr>
            <td> Management Mobile App </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          
</tr>
            <td> Guests Mobile App </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

</tr>
            <td> Booking Widget for your website </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

 <tr>
            <td> Accounting Module </td>
           <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>
<tr>
            <td>Human Resources Module </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

<tr>
            <td> Purchasing </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>
<tr>
            <td> Costs Module</td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

         

          </tr>

           <tr>
             <td colspan="6" style="text-align:center; background-color:#8c8c8c; font-weight:bold; color:#FFF;">Interfaces</td>
           </tr>
            </tr>
            <tr>
            <td>Channel Manager: 3 Channels<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td> 
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	    </tr>


<tr>
            <td>Channel Manager: 8 Channels<br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
      <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>  
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	             </tr>

<tr>
            <td>Channel Manager: Unlimited Channels <br><div class="visible-desktop">
                                <a href="" title="Channel Manager"><font style="font-size:11px;">View the list of Channel Managers</font></a> </div>
      <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

            <tr>
<tr>
            <td>GDS Connectivity</td>
           <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
           </tr>


            <td> Quickbooks Interface </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
             <td><img src="/utility/images/no-icon.png" width="16" height="16" alt="cross"></td>
	     <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>          </tr>

            <tr>
        <td colspan="6" style="text-align:center; background-color:#8c8c8c; font-weight:bold; color:#FFF;">Services</td>
      </tr>

           <tr>
            <td>24 x 7 Live Chat Support </td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

          </tr>

               <tr>
            <td>Setup &amp; Training </td>
             <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

  
          </tr>

     
	<td>Unlimited No. of Users</td>
         <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
            <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>
              <td><img src="/utility/images/yes-icon.gif" width="16" height="16" alt="check"></td>

           </tr>

         

               <tr>
            <td>Get Started</td>
             <td><a href="fs" target="_blank" >Get now</a></td>
               <td><a href="fs" target="_blank" >Buy now</a></td>
                 <td><a href="fs" target="_blank" >Buy now</a></td>
                 <td><a href="fs" target="_blank" >Buy now</a></td>
		 <td><a href="fs" target="_blank" >Buy now</a></td>
             
             
          </tr>


             
             
          </tr>


        </tbody>
    </table>

  </div>
 </body>


    
</main>
  
  
</body>
</html>
