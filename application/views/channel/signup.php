<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title><?=$page_heading?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Client Signup Form template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login sign up Responsive web template, SmartPhone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<style type="text/css" media="screen">

/*--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
--*/
/*--reset--*/
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,dl,dt,dd,ol,nav ul,nav li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;}
article, aside, details, figcaption, figure,footer, header, hgroup, menu, nav, section {display: block;}
ol,ul{list-style:none;margin:0px;padding:0px;}
blockquote,q{quotes:none;}
blockquote:before,blockquote:after,q:before,q:after{content:'';content:none;}
table{border-collapse:collapse;border-spacing:0;}
/*-- start editing from here --*/
a{text-decoration:none;}
.txt-rt{text-align:right;}/* text align right */
.txt-lt{text-align:left;}/* text align left */
.txt-center{text-align:center;}/* text align center */
.float-rt{float:right;}/* float right */
.float-lt{float:left;}/* float left */
.clear{clear:both;}/* clear float */
.pos-relative{position:relative;}/* Position Relative */
.pos-absolute{position:absolute;}/* Position Absolute */
.vertical-base{	vertical-align:baseline;}/* vertical align baseline */
.vertical-top{	vertical-align:top;}/* vertical align top */
nav.vertical ul li{	display:block;}/* vertical menu */
nav.horizontal ul li{	display: inline-block;}/* horizontal menu */
img{max-width:100%;}
/*-- end reset --*/
body {
    font-family: 'Open Sans', sans-serif;
    background: url("<?=base_url()?>user_asset/images/slider-2.jpg")repeat center 0px; 
	background-size: cover;
}
h1 {
    font-size: 3em;
    text-align: center;
    color: #fff;
    font-weight: 100;
    font-family: 'Old Standard TT', serif;
    letter-spacing: 5px;
}
/*-- main --*/
.main {
    padding: 2em 0 0;
}
.main-agilerow {
    width: 35%;
    margin: 2em auto;
    background: rgba(105, 106, 106, 0.32);
    -webkit-box-shadow: 0px 0px 7px 2px rgba(0, 0, 0, 0.8);
    -moz-box-shadow: 0px 0px 7px 2px rgba(0, 0, 0, 0.8);
    box-shadow: 0px 0px 7px 2px rgba(0, 0, 0, 0.8);
}
/*-- contact-form --*/
.signup-wthreetop {
    padding: 1.5em;
    background:rgba(255, 87, 34, 0.64);
}
.signup-wthreetop h2 {
    font-size: 2em;
    color: #fff;
    font-family: 'Old Standard TT', serif;
}
.signup-wthreetop p {
    font-size: 0.8em;
    color: #ddd;
    line-height: 1.8em;
    letter-spacing: 4px;
    margin-top: .5em;
} 
.contact-wthree {
    padding: 0.5em 2em 2.5em;
}
.form-w3step1 {
    background:rgba(37, 37, 37, 0.61);
    padding: 1.5em;
}
.contact-wthree h3 {
    font-size: 1.2em;
    color: #fff;
    margin: 1em 0 .5em;
    font-family: 'Old Standard TT', serif;
}
.contact-wthree input[type="text"],.contact-wthree input[type="email"],.contact-wthree input[type="password"], select {
    width: 93%;
    color: #fff;
    background: none;
    outline: none;
    font-size: .9em;
    padding: .6em 1em;
    margin-bottom: 1em;
    border: solid 1px #bfbfbf;
    -webkit-appearance: none; 
	font-family: 'Open Sans', sans-serif;
	-webkit-transition: 0.5s all; 
    -moz-transition: 0.5s all;
	transition: 0.5s all;
} 
 select {
    width: 93%;
    color: #fff;
    background: none;
    outline: none;
    font-size: .9em;
    padding: .6em 1em;
    margin-bottom: 1em;
    border: solid 1px #bfbfbf;
    -webkit-appearance: none; 
	font-family: 'Open Sans', sans-serif;
	-webkit-transition: 0.5s all; 
    -moz-transition: 0.5s all;
	transition: 0.5s all;
} 
.contact-wthree input[type="submit"] {
    outline: none;
    color: #fff;
    padding: 0.6em 1em;
    font-size: 1em; 
    -webkit-appearance: none;
    background: #FF5722;
    border: 2px solid #FF5722;
    -webkit-transition: 0.5s all; 
    -moz-transition: 0.5s all;
	transition: 0.5s all;
    width: 100%;
    cursor: pointer;
} 
input.agileits-btm {
    margin-bottom: 0 !important;
}
.contact-wthree input[type="submit"]:hover {
    background: transparent;
    color: #FF5722;
    letter-spacing: 3px;
}
::-webkit-input-placeholder {
   color: #fff;   
   font-weight:300;
} 
:-moz-placeholder { /* Firefox 18- */
   color: #fff; 
} 
::-moz-placeholder {  /* Firefox 19+ */
   color: #fff;  
} 
:-ms-input-placeholder {  
   color: #fff;  
} 
/*-- check box --*/ 
.agileits-row2 input[type="checkbox"] {
    display: none;
}
.agileits-row2 input[type="checkbox"]+label {
    position: relative;
    padding-left: 1.8em;
    border: none;
    outline: none;
    font-size: 1em;
    color: #fff;
    cursor: pointer;
    display: -webkit-inline-box; 
	margin: 1.5em 0;
}
.agileits-row2 input[type="checkbox"]+label span:first-child {
    width: 13px;
    height: 13px;
    border: 1px solid #C8C8C8;
    position: absolute;
    left: 0;
    top: 3px; 
}
.agileits-row2 input[type="checkbox"]:checked+label span:first-child:before {
    content: "";
    background: url("<?=base_url()?>user_asset/images/tick.png")no-repeat;
    position: absolute;
    left: 2px;
    top: 2px;
    font-size: 10px;
    width: 10px;
    height: 10px;
}
/*-- //check box --*/
/*-- //main --*/
/*-- copyright --*/
.w3copyright-agile {
    margin: 3em 0;
    text-align: center;
}
.w3copyright-agile p {
    font-size: 0.9em;
    color: #fff;
    line-height: 1.8em;
    letter-spacing: 2px;
	font-weight: 300;
}
.w3copyright-agile p a{
    color: #fff;
}
.w3copyright-agile p a:hover{
    color: #FF5722; 
	-webkit-transition: 0.5s all;
	-moz-transition: 0.5s all;
	-o-transition: 0.5s all;
	-ms-transition: 0.5s all;
	transition: 0.5s all;
}
/*-- //copyright --*/ 
/*-- responsive-design --*/ 
@media(max-width:1280px){
.main-agilerow {
    width: 40%; 
}
}
@media(max-width:1080px){
h1 {
    font-size: 2.8em; 
}
.main-agilerow {
    width: 44%;
}
.contact-wthree input[type="text"], .contact-wthree input[type="email"], .contact-wthree input[type="password"] {
    width: 91%; 
}
.signup-wthreetop h2 {
    font-size: 1.8em; 
}
}
@media(max-width:1024px){
.main-agilerow {
    width: 50%;
}
} 
@media(max-width:800px){
h1 {
    font-size: 2.6em;
    letter-spacing: 3px;
}
.main-agilerow {
    width: 55%;
}
.form-w3step1 { 
    padding: 1.2em;
}
}
@media(max-width:768px){
.main-agilerow {
    width: 62%;
}
}
@media(max-width:736px){
.main {
    padding: 1.5em 0 0;
}
h1 {
    font-size: 2.5em; 
}
.signup-wthreetop {
    padding: 1.2em 1.5em; 
}
.signup-wthreetop p { 
    letter-spacing: 3px; 
} 
}
@media(max-width:667px){
.main-agilerow {
    width: 65%;
	margin: 1.5em auto;

}
.signup-wthreetop p { 
    margin-top: .2em;
}
.contact-wthree {
    padding: 0.5em 1.5em 2em;
}
.w3copyright-agile p { 
    letter-spacing: 1px; 
}
}
@media(max-width:568px){
h1 {
    font-size: 2.3em;
    letter-spacing: 1px;
}
.signup-wthreetop h2 {
    font-size: 1.5em;
}
.signup-wthreetop p {
    letter-spacing: 1px;
}
.contact-wthree h3 {
    font-size: 1.1em; 
}
.contact-wthree input[type="text"], .contact-wthree input[type="email"], .contact-wthree input[type="password"] {
    width: 90%;
    font-size: .8em;
}
.w3copyright-agile {
    margin: 2em 0; 
    padding: 0 1em;
}
.agileits-row2 input[type="checkbox"]+label { 
    font-size: 0.9em; 
}
}
@media(max-width:480px){
.main-agilerow {
    width: 85%; 
}
.contact-wthree input[type="submit"] { 
    font-size: 0.9em; 
}
.w3copyright-agile p {
    font-size: 0.8em; 
}
}
@media(max-width:414px){
h1 {
    font-size: 1.8em; 
}
.main {
    padding: 1em 0 0;
}
.main-agilerow { 
    margin: 1em auto;
}
.signup-wthreetop h2 {
    font-size: 1.3em;
}
.signup-wthreetop {
    padding: 1.2em 1em;
}
.form-w3step1 {
    padding: 1em .8em;
}
.contact-wthree input[type="text"], .contact-wthree input[type="email"], .contact-wthree input[type="password"] {
    width: 88%; 
}
.contact-wthree h3 {
    font-size: 1em;
}
} 
@media(max-width:375px){
.contact-wthree {
    padding: 0.2em 1em 1.5em;
}
.signup-wthreetop p {
    font-size: 0.75em; 
}
}
@media(max-width:320px){
h1 {
    font-size: 1.6em;
}
.signup-wthreetop h2 {
    font-size: 1.2em;
}
.signup-wthreetop {
    padding: 1em 1em;
}
.main-agilerow {
    margin: 0.8em auto;
    width: 90%;
}
.contact-wthree input[type="text"], .contact-wthree input[type="email"], .contact-wthree input[type="password"] {
    width: 87%;
	font-size: .7em;
}
.agileits-row2 input[type="checkbox"]+label { 
    font-size: 0.8em; 
}
.w3copyright-agile p {
    letter-spacing: 0px;
}
}
/*-- //responsive-design --*/
	
</style>
<!-- //Custom Theme files -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Old+Standard+TT:400,400i,700" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'><!--web font-->
<script type="text/javascript" src="<?php echo base_url();?>user_asset/js/jquery-2.1.4.min.js"></script>
<!-- //web font -->
</head>
<body>
	<!-- main -->
	<div class="main main-agileits">
		
		<div class="main-agilerow"> 
			<div class="signup-wthreetop">
				<center><h1>New Account</h1></center>
			</div>	
			<div class="contact-wthree">
				<form onsubmit="validarsigup() " method="post">
					<div class="form-w3step1">
						<input type="text" name="firstnameup" placeholder="Firstname" required="">
						<input type="text" name="lastnameup" placeholder="Lastname" required="">
						<input type="text" onblur="return validarpropertyname()" id="propertynameup" name="propertynameup" placeholder="Firstname" required="">  
						<input type="text" id="phoneup" name="phoneup"  placeholder="Phone" required="">
						<input type="text" id="cityup" name="cityup" placeholder="City" required="">
						<select onchange="clear2()" id="countryup" name="countryup" class="has-padding has-border" style="width: 95%" required="">
                            <?php

                            	$countries=$this->db->query("SELECT * FROM country where country_status= 'active'")->result_array();
                                if (count($countries)>0) {
                                    echo '<option value="0" >Select a Country</option>';
                                    foreach ($countries as $value) {

                                        echo '<option  value="'.$value['id'].'">'.$value['country_name'].'</option>';


                                    }
                                }
                                else
                                {
                                    echo '<option value="0">Does not have countries</option>';
                                }

                              ?>
                        </select>
						<input type="text" id="websiteup" name="websiteup"  placeholder="WebSite" required="">
						<input type="email" onblur="return validaremail()" class="email agileits-btm" id="emailup" name="emailup" placeholder="Email" required=""> 
						 

					</div> 
					<h3></h3>
					<div class="form-w3step1">  
						<input type="text" onblur="return validarusername()" id="usernameup" name="usernameup" placeholder="User Name" required="">
						<input type="password" onblur="return verifica_clave()" id="passwordup" name="passwordup" placeholder="Password" required="">
						<input type="password" onblur="return clavesiguales() " style="width: 95%" id="repasswordup" name="repasswordup" placeholder="Confirm Password" required="">
					</div>



					<div class="agileits-row2 w3ls-formrow2">
						<input type="checkbox" id="brand2" value="">
						<label for="brand2">I agree to the <a href="#" class="menu__link" data-toggle="modal" data-target="#terms">Terms</a> and <a href="#" class="menu__link" data-toggle="modal" data-target="#privacy">Privacy Policy</a> </label>
					</div>  
					<input type="submit" value="Sign up">
				</form>
			</div>  
		</div>	
	</div>	
<script src="<?php echo base_url();?>user_asset/js/sweetalert.min.js"></script>
<script type="text/javascript">

	var base_url = '<?php echo lang_url();?>';
	  function validarsigup() {



            if ($("#countryup").val() == 0) {
                var count = document.getElementById('countryup');
                count.setCustomValidity("Select a Country to Continue");
                return false;
            }


            if (!$("#accept-terms").prop('checked') ) {
                $('#msguser').html('Please accept terms and policy');
                $('#msguser').toggle("slow");
                setTimeout(function() {
                    $('#msguser').fadeOut();
                }, 5000);
                return false;
            }

            $.ajax({
                type: "POST",
                url: base_url + 'channel/newregister',
                data: $("#signup").serialize(),
                success: function(html) {
                    swal({
                        title: "Done!",
                        text: "User created Successfully!",
                        icon: "success",
                        button: "Ok!",
                    });

                    location.reload();
                }
            });



            return false;
        }
      function validarpropertyname() {

            var property = $('#propertynameup').val();
            var pro = document.getElementById('propertynameup');

            if (property.length == 0) {
                return false;
            }

            var data = { "propertyname": property };

            $.ajax({
                type: "POST",
                url: base_url + 'channel/propertynameused',
                data: data,
                success: function(html) {

                    if (html.trim() != '0') {

                        pro.setCustomValidity("This property already exists");
                        return false;
                    } else {
                        pro.setCustomValidity("");
                        return true;
                    }
                }
            });
        }


        function validaremail() {


            var email = document.getElementById('emailup');
            var emailval = $("#emailup").val();


            if (emailval.length == 0) {
                return false;
            }

            if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(emailval) == false) {

                email.setCustomValidity("This Email is not valid");
                return false;

            }

            var data = { "email": emailval };
            $.ajax({
                type: "POST",
                url: base_url + 'channel/emailused',
                data: data,
                success: function(html) {

                    if (html.trim() != '0') {
                        email.setCustomValidity("This Email already exists");
                    } else {
                        email.setCustomValidity("");
                        return true;
                    }
                }
            });
        }

        function validarusername() {


            var usern = document.getElementById('usernameup');
            var username = $("#usernameup").val();
            if (username.length == 0) {
                return false;
            }
            var data = { "username": username };

            $.ajax({
                type: "POST",
                url: base_url + 'channel/usernameused',
                data: data,
                success: function(html) {

                    if (html.trim() != 0) {
                        usern.setCustomValidity("This UserName already exists");
                        return false;
                    } else {
                        usern.setCustomValidity("");
                        return true;
                    }
                }
            });
        }

        function verifica_clave() {
            var cadena = $("#passwordup").val();
            var cad = document.getElementById('passwordup');

            if (cadena.length == 0) { return false; }
            var expresionR = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)/;
            var resultado = expresionR.test(cadena);

            if (resultado != true || cadena.length < 8) {
                $('#msguser').html('');
                cad.setCustomValidity("Minimum 8 characters without spaces, must include uppercase, lowercase and numbers");
                return false;
            } else {
                cad.setCustomValidity("");
                return true;
            }
        }

        function clavesiguales() {

            var cad2 = document.getElementById('repasswordup');

            if ($("#passwordup").val() != $("#repasswordup").val()) {
                cad2.setCustomValidity("You must repeat the same password");
                return false;
            } else {
                cad2.setCustomValidity("");
                return true;
            }
        }


</script>
</html>