<?php $contact_det = get_data('home_cms',array('id'=>14))->row(); ?>
<?php if($contact_det->image!='' && $contact_det->image!='default.jpeg') {  ?>
<div style='background: rgba(0, 0, 0, 0) url(data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$contact_det->image));?>) repeat scroll 0 0;' class="banner_inner">
<div class="container">
			<h2><?php echo $contact_det->title;?></h2>
			<label></label>
			<div class="clearfix"></div>
</div>
</div>
<?php } ?>

<div class="about_cont">
<div class="container">
<div class="row">
<div class="col-md-8 col-sm-8">
<div class="cont_cont">
<?php if($this->session->flashdata('contact_success')!='')
{?>
<div role="alert" class="alert alert-success alert-dismissible fade in">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true"><i class="fa fa-times"> </i></span></button>
<?php echo $this->session->flashdata('contact_success');?>
</div>
<?php } ?>


    <p><?php echo $contact_det->content ?></p>
    <?php
if(user_id()!='')
{
    $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row();
}
?>

     <div class="">
        <form class="form-horizontal" action="<?php echo lang_url();?>channel/our_links/contact_us" id="contact_us" method="post">
        
         

            <div class="form_type reg_type">
            <div class="col-md-6">
             <input type="text" class="form-control lock" id="exampleInputEmail3" placeholder="Your Name" name="name" value="<?php if(user_id()!='') { echo $user_details->fname;} ?>" required>
            </div>    
            <div class="col-md-6">
               <input type="email" class="form-control lock" id="exampleInputPassword3" placeholder="Your Email" name="email" value="<?php if(user_id()!='') { echo $user_details->email_address;} ?>" required>
            </div>  
            </div>

             
            


             <div class="form_type reg_type">
            <div class="col-md-12"> 
           <textarea class="form-control text_contact"  placeholder="Your Message" name="message_content" required></textarea>

            </div>
            </div> 
            
            

            <div class="clearfix">
            </div>
            
            <div class="col-md-12">
            <input class="btn btn_login" type="submit" style="width:100%;" value="send message" name="contact">            
            
            </div>  
            <div class="clearfix">
            </div>
            
              <div class="clearfix"> </div>
        </form>
    </div>
        </div>
          </div>
    
    <div class="col-md-4 col-sm-4 ">
    <div class="head_conta">
    <h4>Contact Us</h4>
    </div> 
         <table class="table tab_list_cont">
<tbody>
<tr>
<td>Company Name </td>
<td><?php echo $about->company_name;?></td>

</tr>
<tr>
<td>Address  </td>
<td>  <?php echo $about->address.', '.$about->city.', '.$about->state.', '.get_data('country', array('id'=>$about->country))->row()->country_name;?></td>
</tr>
<tr>
<td>Phone  </td>
<td> +<?php echo $about->phno;?></td>
</tr>
<tr>
<td>Email </td>
<td> <a href="mailto:<?php echo $about->email_id;?>"><?php echo $about->email_id;?></a>, <?php if($about->acc_email!=''){ ?> <br> <a href="mailto:<?php echo $about->acc_email;?>"><?php echo $about->acc_email; } ?></a> </td>

</tr>
<tr>
<td><strong>Web</strong></td>
<td><a target="_blank" href="<?php echo $about->siteurl;?>"><?php echo $about->siteurl;?></a></td>
</tr>
<tr>
<td> </td>
<td>

</td>
</tr>
</tbody></table> 
        
         <div class="head_conta">
        <h4>Follow with us</h4>
        </div>    
    <div class="social_f">
              <ul>
                 <li class="ic"><a title="Facebook" href="<?php echo $about->facebook_url; ?>"><i class="fa fa-facebook"></i></a></li>
                <li class="ic"><a title="Twitter" href="<?php echo $about->twitter_url; ?>"><i class="fa fa-twitter"></i></a></li>
                <li class="ic"><a title="Linedin" href="<?php echo $about->linkedin; ?>"><i class="fa fa-linkedin"></i></a></li>
                <li class="ic"><a title="Google plus" href="<?php echo $about->google; ?>"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
        
        
    </div>  
    </div>
</div>
</div>
   
<!-- map section start -->

<!--<div id="map-canvas"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv3fAYIfLl5H6SmuZKJtUIx6MyNn9xDbs" type="text/javascript"></script>-->
<script type="text/javascript">
<?php 
/* $lat = substr($about->map_location, strpos( $about->map_location,",") + 1); 
$lang = explode(",", $about->map_location); */
?>
      /* function initialize() {
          var myLatlng = new google.maps.LatLng(<?php echo $lang[0];?>, <?php echo $lat;?>);
      var mapOptions = {
          center: myLatlng,
          zoom: 11,
          streetViewControl: false
      };
      var map = new google.maps.Map(document.getElementById("map-canvas"),
              mapOptions);
      var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          //title: "Dadamy N.E.D"
      });
      }
      google.maps.event.addDomListener(window, 'load', initialize); */
  </script>

<!--  map section ends here -->