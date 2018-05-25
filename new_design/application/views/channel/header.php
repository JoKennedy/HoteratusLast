<!DOCTYPE html>
<html lang="en">
<head> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="Channel manager, Hotel channel manager, hotel reservation system, global distribution system, the best channel manager, distribution channel manager, guest review express, point of sale, facebook button, booking engine, hotel website, property management system (PMS), revenue management, rate management system,customer relations management, OTA, online travel agency, " />
<meta name="description" content="Hoteratus is a two-way online hotel channel manager for managing online sales channels easily, feeding all channels simultaneously, providing also the services of property management system, unlimited point of sale,rate management system, revenue management, front desk, customized websites, facebook booking button, booking engine, tripadvisor instant booking, guest review express, guests satisfaction survey and more " />
<meta name="og:title" content="Hoteratus- Hospitality Sotfware Solutions" />
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url();?>user_assets/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url();?>user_assets/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url();?>user_assets/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>user_assets/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url();?>user_assets/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url();?>user_assets/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url();?>user_assets/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url();?>user_assets/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>user_assets/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url();?>user_assets/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>user_assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>user_assets/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>user_assets/favicon/favicon-16x16.png">
<link rel="manifest" href="<?php echo base_url();?>user_assets/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo base_url();?>user_assets/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<title>Unlimited Luxury Villas - Channel Manager - <?php echo $page_heading;?> </title>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('animate.min.css', true);?>
<?php echo theme_fonts('font-awesome.css', true);?>
<?php echo theme_css('showhide.css', true);?>

<link rel="stylesheet" href="<?php echo base_url();?>user_assets/css/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets_pms/css/fixed-header.css">
<script src="<?php echo base_url();?>assets_pms/js/fix-header.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
var stickyNavTop = $('.navss').offset().top;
var stickyNav = function(){
var scrollTop = $(window).scrollTop(); 
if (scrollTop > stickyNavTop) { 
$('.navss').addClass('sticky');
} else {
$('.navss').removeClass('sticky'); 
}
};
stickyNav();
$(window).scroll(function() {
stickyNav();
});
});
</script>
<style>
.top{
	background:<?php echo $this->theme_customize['h_header_one']!='' ? $this->theme_customize['h_header_one']:'#07204d' ?>;
}
.top2{
	background:<?php echo $this->theme_customize['h_header_two']!='' ? $this->theme_customize['h_header_two']:'' ?>;
}
.f-social li a:before {
	background:<?php echo $this->theme_customize['h_header_one']!='' ? $this->theme_customize['h_header_one']:'#07204d' ?>;
	color: #fff;
	width: 38px;
	height: 38px;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	display: table;
	line-height: 38px;
	text-align: center;
	transition: .4s;
}
.cls_boc_cont{
	background-color:<?php echo $this->theme_customize['our_features']!='' ? $this->theme_customize['our_features']:'#073383' ?>;
    border-width: 0;
    box-shadow: 1px 1px 8px #d2d2cf;
    padding: 54px 35px 44.3618px;
	  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  overflow:hidden;
  position:relative;
}

.trial{
	padding-top:25px;
	padding-bottom:25px;
	background:<?php echo $this->theme_customize['free_trial']!='' ? $this->theme_customize['free_trial']:'#073383' ?> url(../images/bg_acc.jpg) no-repeat scroll 0 0/cover;
	
}

.foot{
	background-color:<?php echo $this->theme_customize['footer_one']!='' ? $this->theme_customize['footer_one']:'#000712' ?> ;
	background-position:top center;
	background-repeat:no-repeat;
	padding-top:15px;
	padding-bottom:15px;
}

.main-foot{
	background:<?php echo $this->theme_customize['footer_two']!='' ? $this->theme_customize['footer_two']:'#1253a3' ?> none repeat scroll 0 0;
	padding-top:15px;
	padding-bottom:15px;
}

.foot .f-social li a::before {
    background: <?php echo $this->theme_customize['footer_one']!='' ? $this->theme_customize['footer_one']:'#fb4b6f' ?> none repeat scroll 0 0;
    color: #ffffff;
    display: table;
    height: 38px;
    left: 0;
    line-height: 38px;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    transition: all 0.4s ease 0s;
    width: 38px;
}


</style>
</head>
<body>
 <div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<header>
    <div class="main_menu">
      <div class="">
        <nav class="navbar navbar-default cbp-af-header">
          <div class="container"> 
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
              <a class="navbar-brand" href="<?php echo lang_url();?>" ><img src="<?php echo lang_url();?>user_assets/images/logo.png" alt=""></a> </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav pull-right">
                <li <?php if(uri(2)=='' || uri(2)=='channel' && uri(3)!='our_links') { ?> class="active" <?php } ?>><a href="<?php echo lang_url();?>">Home  <span class="sr-only">(current)</span></a></li>
                <li <?php if(uri(4)=='features') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/features"> Features </a></li>
                <li <?php if(uri(4)=='multiproperty') { ?>class="active" <?php }?>><a href="<?php echo lang_url();?>channel/our_links/multiproperty"> Multiproperty   </a></li>
                <li <?php if(uri(4)=='contact_us') { ?>class="active" <?php }?>><a href="<?php echo lang_url()?>channel/our_links/contact_us"> Contact us </a></li>
                <li><a href="#" data-toggle="modal" data-target="#login"> <i class="fa fa-sign-in" aria-hidden="true"></i> </a></li>
                <li><a href="#" data-toggle="modal" data-target="#reg">  <i class="fa fa-user-plus" aria-hidden="true"></i></a></li>

              </ul>
            </div>
            <!-- /.navbar-collapse --> 
          </div>
          <!-- /.container-fluid --> 
        </nav>
      </div>
      <div class="container">
        <div class="cls_top_banner_sec">
          <div class="row ">
            <div class="col-md-12 col-lg-12 col-sm-10">
              
            </div>
          </div>
        </div>
      </div>
    </div>
	<?php if(uri(4)!='about_us' && uri(4)!='features' && uri(4)!='contact_us' && uri(3)!='faq' && uri(4)!='terms' && uri(4)!='multiproperty' && uri(4)!='privacy' ) { ?>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
        <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
        <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item"> <img src="<?php echo base_url();?>user_assets/images/inner_banner.jpg" alt="">
<div class="carousel-caption">
      <div class="cls_buy_sell_bg">
                <h4>Rate <span>  Management System </span></h4>
                <p>Allows properties to increase their yield by managing the rates according to their occupancy levels</p>
              </div>
      </div>
</div>
        <div class="item"> <img src="<?php echo base_url();?>user_assets/images/banner1.png" alt="">
<div class="carousel-caption">
       <div class="cls_buy_sell_bg">
                <h4>Back - End  <span> Management System </span></h4>
                <p>Provides you with all the tools to ensure exceeded revenue and lower operational costs</p>
              </div>
      </div>
</div>
        <div class="item active"> <img src="<?php echo base_url();?>user_assets/images/inner_banner.jpg" alt="">
<div class="carousel-caption">
        <div class="cls_buy_sell_bg">
                <h4>Reputation  <span> Management System </span></h4>
                <p>Allows properties to quickly interact with the customers and provide exceeded expectations.</p>
              </div>
      </div>
</div>


      </div>
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="fa fa-long-arrow-left" aria-hidden="true"> </span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="fa fa-long-arrow-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
	<?php } ?>
  </header>
  
  
  
  <!--login Modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_list" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <div class="login-text">  
          <h1>Hoteratus - Hospitality Software Solutions</h1></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	          <div class="login-text">  
					  
                        <p> Please enter your details to access Hoteratus - Hospitality Software Solutions. If you don't have an account yet, you can click the link to create one </p></div>
        <div class="login-content">
                      
		<form action="javascript:;" class="login-form  form_login" method="post" id="log_form" novalidate="novalidate">

			<form class="form-horizontal form_login">
<div class="form-group">
	
                <p>User Name :</p>
					<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Username" name="login_email" id="login_email" required="" type="text">
</div>
<div class="form-group">

                                <p>Password :</p>

					<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Password" name="login_pwd" id="login_pwd" required="" type="password"> </div>
<div class="clearfix">
            </div>
			<div class="pull-left cont_list mar-top-10">
            <div class="checkbox">
          <label>
            <input value="" type="checkbox">
            <span class="cr"><i class="cr-icon fa fa-square"></i></span>
            Remember Me
          </label>
        </div>
            </div>
			
			<div class="pull-right cont_list mar-top-10">
          
			 <a class="tar_create" href="#" data-toggle="modal" data-target="#forgot">create an account</a>   <br>	
			 <a href="#" data-toggle="modal" data-target="#forgot">forgot password ?</a>
            </div>
			
</form>				
		
	
                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn_trial">Sign in</button>
      </div>
    </div>
  </div>
</div>



  <!--register Modal -->
<div class="modal fade reg_wdth" id="reg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_list" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <div class="login-text">  
          <h1>sign in</h1></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	       <form class="register-form" action="http://192.168.1.23/unlimited_new_design/en/channel/basics/UserRegister" id="register_form" method="post" novalidate="novalidate" style="display: block;">
		<div class="row">
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">First Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="First Name" id="fname" name="fname" required="" type="text"> 
			</div>
			
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Last Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Last Name" id="lname" name="lname" required="" type="text"> 
			</div> 
		</div>
		<div class="row">
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Property Name</label>
			<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Property Name" id="property_name" name="property_name" required="" type="text"> 
			</div>
			
			<div class="col-xs-6">
			<label class="control-label visible-ie8 visible-ie9">Phone</label>
			<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="+449842080276" name="mobile" id="mobile" required="" type="text"> 
			</div> 
		</div>
		
		<div class="row">
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">City</label>
				<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="City" name="town" id="town" required="" type="text">
			</div>
						
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">Country</label>
								
				<select class="form-control" name="country">
								<option value="1">Afghanistan</option>
								<option value="2">Albania</option>
								<option value="3">Algeria</option>
								<option value="4">American Samoa</option>
								<option value="5">Andorra</option>
								<option value="6">Angola</option>
								<option value="7">Anguilla</option>
								<option value="8">Antarctica</option>
								<option value="9">Antigua and Barbuda</option>
								<option value="10">Argentina</option>
								<option value="11">Armenia</option>
								<option value="12">Aruba</option>
								<option value="13">Australia</option>
								<option value="14">Austria</option>
								<option value="15">Azerbaijan</option>
								<option value="16">Bahamas</option>
								<option value="17">Bahrain</option>
								<option value="18">Bangladesh</option>
								<option value="19">Barbados</option>
								<option value="20">Belarus</option>
								<option value="21">Belgium</option>
								<option value="22">Belize</option>
								<option value="23">Benin</option>
								<option value="24">Bermuda</option>
								<option value="25">Bhutan</option>
								<option value="26">Bolivia</option>
								<option value="27">Bosnia and Herzegovina</option>
								<option value="28">Botswana</option>
								<option value="29">Bouvet Island</option>
								<option value="30">Brazil</option>
								<option value="31">British Indian Ocean Territory</option>
								<option value="32">Brunei Darussalam</option>
								<option value="33">Bulgaria</option>
								<option value="34">Burkina Faso</option>
								<option value="35">Burundi</option>
								<option value="36">Cambodia</option>
								<option value="37">Cameroon</option>
								<option value="38">Canada</option>
								<option value="39">Cape Verde</option>
								<option value="40">Cayman Islands</option>
								<option value="41">Central African Republic</option>
								<option value="42">Chad</option>
								<option value="43">Chile</option>
								<option value="44">China</option>
								<option value="45">Christmas Island</option>
								<option value="46">Cocos (Keeling) Islands</option>
								<option value="47">Colombia</option>
								<option value="48">Comoros</option>
								<option value="49">Congo</option>
								<option value="50">Congo, the Democratic Republic of the</option>
								<option value="51">Cook Islands</option>
								<option value="52">Costa Rica</option>
								<option value="53">Cote D'Ivoire</option>
								<option value="54">Croatia</option>
								<option value="55">Cuba</option>
								<option value="56">Cyprus</option>
								<option value="57">Czech Republic</option>
								<option value="58">Denmark</option>
								<option value="59">Djibouti</option>
								<option value="60">Dominica</option>
								<option value="61">Dominican Republic</option>
								<option value="62">Ecuador</option>
								<option value="63">Egypt</option>
								<option value="64">El Salvador</option>
								<option value="65">Equatorial Guinea</option>
								<option value="66">Eritrea</option>
								<option value="67">Estonia</option>
								<option value="68">Ethiopia</option>
								<option value="69">Falkland Islands (Malvinas)</option>
								<option value="70">Faroe Islands</option>
								<option value="71">Fiji</option>
								<option value="72">Finland</option>
								<option value="73">France</option>
								<option value="74">French Guiana</option>
								<option value="75">French Polynesia</option>
								<option value="76">French Southern Territories</option>
								<option value="77">Gabon</option>
								<option value="78">Gambia</option>
								<option value="79">Georgia</option>
								<option value="80">Germany</option>
								<option value="81">Ghana</option>
								<option value="82">Gibraltar</option>
								<option value="83">Greece</option>
								<option value="84">Greenland</option>
								<option value="85">Grenada</option>
								<option value="86">Guadeloupe</option>
								<option value="87">Guam</option>
								<option value="88">Guatemala</option>
								<option value="89">Guinea</option>
								<option value="90">Guinea-Bissau</option>
								<option value="91">Guyana</option>
								<option value="92">Haiti</option>
								<option value="93">Heard Island and Mcdonald Islands</option>
								<option value="94">Holy See (Vatican City State)</option>
								<option value="95">Honduras</option>
								<option value="96">Hong Kong</option>
								<option value="97">Hungary</option>
								<option value="98">Iceland</option>
								<option value="99">India</option>
								<option value="100">Indonesia</option>
								<option value="101">Iran, Islamic Republic of</option>
								<option value="102">Iraq</option>
								<option value="103">Ireland</option>
								<option value="104">Israel</option>
								<option value="105">Italy</option>
								<option value="106">Jamaica</option>
								<option value="107">Japan</option>
								<option value="108">Jordan</option>
								<option value="109">Kazakhstan</option>
								<option value="110">Kenya</option>
								<option value="111">Kiribati</option>
								<option value="112">Korea, Democratic People's Republic of</option>
								<option value="113">Korea, Republic of</option>
								<option value="114">Kuwait</option>
								<option value="115">Kyrgyzstan</option>
								<option value="116">Lao People's Democratic Republic</option>
								<option value="117">Latvia</option>
								<option value="118">Lebanon</option>
								<option value="119">Lesotho</option>
								<option value="120">Liberia</option>
								<option value="121">Libyan Arab Jamahiriya</option>
								<option value="122">Liechtenstein</option>
								<option value="123">Lithuania</option>
								<option value="124">Luxembourg</option>
								<option value="125">Macao</option>
								<option value="126">Macedonia, the Former Yugoslav Republic of</option>
								<option value="127">Madagascar</option>
								<option value="128">Malawi</option>
								<option value="129">Malaysia</option>
								<option value="130">Maldives</option>
								<option value="131">Mali</option>
								<option value="132">Malta</option>
								<option value="133">Marshall Islands</option>
								<option value="134">Martinique</option>
								<option value="135">Mauritania</option>
								<option value="136">Mauritius</option>
								<option value="137">Mayotte</option>
								<option value="138">Mexico</option>
								<option value="139">Micronesia, Federated States of</option>
								<option value="140">Moldova, Republic of</option>
								<option value="141">Monaco</option>
								<option value="142">Mongolia</option>
								<option value="143">Montserrat</option>
								<option value="144">Morocco</option>
								<option value="145">Mozambique</option>
								<option value="146">Myanmar</option>
								<option value="147">Namibia</option>
								<option value="148">Nauru</option>
								<option value="149">Nepal</option>
								<option value="150">Netherlands</option>
								<option value="151">Netherlands Antilles</option>
								<option value="152">New Caledonia</option>
								<option value="153">New Zealand</option>
								<option value="154">Nicaragua</option>
								<option value="155">Niger</option>
								<option value="156">Nigeria</option>
								<option value="157">Niue</option>
								<option value="158">Norfolk Island</option>
								<option value="159">Northern Mariana Islands</option>
								<option value="160">Norway</option>
								<option value="161">Oman</option>
								<option value="162">Pakistan</option>
								<option value="163">Palau</option>
								<option value="164">Palestinian Territory, Occupied</option>
								<option value="165">Panama</option>
								<option value="166">Papua New Guinea</option>
								<option value="167">Paraguay</option>
								<option value="168">Peru</option>
								<option value="169">Philippines</option>
								<option value="170">Pitcairn</option>
								<option value="171">Poland</option>
								<option value="172">Portugal</option>
								<option value="173">Puerto Rico</option>
								<option value="174">Qatar</option>
								<option value="175">Reunion</option>
								<option value="176">Romania</option>
								<option value="177">Russian Federation</option>
								<option value="178">Rwanda</option>
								<option value="179">Saint Helena</option>
								<option value="180">Saint Kitts and Nevis</option>
								<option value="181">Saint Lucia</option>
								<option value="182">Saint Pierre and Miquelon</option>
								<option value="183">Saint Vincent and the Grenadines</option>
								<option value="184">Samoa</option>
								<option value="185">San Marino</option>
								<option value="186">Sao Tome and Principe</option>
								<option value="187">Saudi Arabia</option>
								<option value="188">Senegal</option>
								<option value="189">Serbia and Montenegro</option>
								<option value="190">Seychelles</option>
								<option value="191">Sierra Leone</option>
								<option value="192">Singapore</option>
								<option value="193">Slovakia</option>
								<option value="194">Slovenia</option>
								<option value="195">Solomon Islands</option>
								<option value="196">Somalia</option>
								<option value="197">South Africa</option>
								<option value="198">South Georgia and the South Sandwich Islands</option>
								<option value="199">Spain</option>
								<option value="200">Sri Lanka</option>
								<option value="201">Sudan</option>
								<option value="202">Suriname</option>
								<option value="203">Svalbard and Jan Mayen</option>
								<option value="204">Swaziland</option>
								<option value="205">Sweden</option>
								<option value="206">Switzerland</option>
								<option value="207">Syrian Arab Republic</option>
								<option value="208">Taiwan, Province of China</option>
								<option value="209">Tajikistan</option>
								<option value="210">Tanzania, United Republic of</option>
								<option value="211">Thailand</option>
								<option value="212">Timor-Leste</option>
								<option value="213">Togo</option>
								<option value="214">Tokelau</option>
								<option value="215">Tonga</option>
								<option value="216">Trinidad and Tobago</option>
								<option value="217">Tunisia</option>
								<option value="218">Turkey</option>
								<option value="219">Turkmenistan</option>
								<option value="220">Turks and Caicos Islands</option>
								<option value="221">Tuvalu</option>
								<option value="222">Uganda</option>
								<option value="223">Ukraine</option>
								<option value="224">United Arab Emirates</option>
								<option value="225">United Kingdom</option>
								<option value="226">United States</option>
								<option value="227">United States Minor Outlying Islands</option>
								<option value="228">Uruguay</option>
								<option value="229">Uzbekistan</option>
								<option value="230">Vanuatu</option>
								<option value="231">Venezuela</option>
								<option value="232">Viet Nam</option>
								<option value="233">Virgin Islands, British</option>
								<option value="234">Virgin Islands, U.s.</option>
								<option value="235">Wallis and Futuna</option>
								<option value="236">Western Sahara</option>
								<option value="237">Yemen</option>
								<option value="238">Zambia</option>
								<option value="239">Zimbabwe</option>
								</select>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">Website</label>
				<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="http://www.domain.com" id="web_site" name="web_site" type="url"> 
			</div>
			<div class="col-xs-6">
				<label class="control-label visible-ie8 visible-ie9">E-mail</label>
				<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="E-mail" id="registeremail" name="email_address" required="" type="email"> 
			</div> 
		</div>
		<p class="hint"> Enter your preferred account details below: </p>
		
        <div class="row">
		<div class="form-group col-md-6">
		<label class="control-label visible-ie8 visible-ie9">Username</label>
		<input class="form-control placeholder-no-fix" autocomplete="off" placeholder="Username" name="user_name" id="username" type="text"> 
		</div>
		
		<div class="form-group col-md-6 col-sm-6">
		<label class="control-label visible-ie8 visible-ie9">Password</label>
		<input class="form-control placeholder-no-fix" autocomplete="off" id="password" placeholder="Password" name="password" type="password"> 
		</div>
        </div>
         <div class="row">
        
        <div class="form-group col-md-6 col-sm-6">
		<label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
		<input class="form-control placeholder-no-fix" autocomplete="off" placeholder="Re-type Your Password" name="cpassword" id="cpassword" type="password">
		</div>
        <div class="form-group col-md-6 col-sm-6">
          <script type="text/javascript" async="" src="https://www.gstatic.com/recaptcha/api2/r20170223111436/recaptcha__en.js"></script><script src="https://www.google.com/recaptcha/api.js"></script>
      	  	<div class="g-recaptcha" data-sitekey="6LduEyETAAAAAALgeMHm8BYz0GC_4p4vzbR-EfNV"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?k=6LduEyETAAAAAALgeMHm8BYz0GC_4p4vzbR-EfNV&amp;co=aHR0cDovLzE5Mi4xNjguMS4yMzo4MA..&amp;hl=en&amp;v=r20170223111436&amp;size=normal&amp;cb=s6nu3c775n9y" title="recaptcha widget" scrolling="no" name="undefined" width="304" height="78" frameborder="0"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea></div></div>
        </div>

        
        </div>
		
		
		 <div class="row">
    <div class="col-md-6 col-sm-6">
    <div class="checkbox">
          <label>
            <input value="" type="checkbox">
            <span class="cr"><i class="cr-icon fa fa-square"></i></span>
           I accept <a href="">Terms</a> and <a href="">policy</a>
          </label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
  
    </div>
    </div>  

</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn_trial">submit</button>
      </div>
    </div>
  </div>
</div>
    
