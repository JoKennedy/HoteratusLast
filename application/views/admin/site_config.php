<?php $this->load->view('admin/header');?>
<style>
.gllpMap	{ /*width: 880px;*/ height: 250px;  } .mar-top7{ margin-top:7px;} 
</style>
	<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-gear"></i> SITE CONFIG
  </div>
  </div>
  <div class="manage">
    <div class="row-fluid clearfix">
    
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>Site Config</h4>
      <br><br>
    <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
      <div class="content">   
        <?php 
        $success=$this->session->flashdata('success');                  
        if($success)  
        {
        ?> 
            <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success! </strong> <?php echo $success;?>.
            </div>
      	<?php 
        }
       	?>  
           <div class="row">
                            
                            
                                    

                  <div class="col-lg-1">

                </div>

                                <div class="col-lg-12">
                   <?php $attributes=array('class'=>'form form-horizontals','id'=>'siteconfig','name'=>'siteconfig');

                                  echo form_open_multipart('admin/site_config_updated/',$attributes); ?>
                  
                  <span class="error"><?php echo validation_errors();?></span>
                                 
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Company-Name</label>
                                            <input class="form-control validate[required]" name="company_name" value="<?php if(isset($company_name)){
                      echo $company_name; }
                      //echo set_value('login_name');
                      ?>">
                      
                      <!--input type="hidden" name="id" value=""/>-->
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Contact-Person</label>
                                            <input class="form-control validate[required]" name="contact_person" value="<?php if(isset($contact_person)){
                      echo $contact_person; }
                      //echo set_value('password'); ?>"/>
                      
                                            </div>
                                      </div>
                                      <div class="col-md-6">
                                      <div class="form-group">
                                      <label><font color="#CC0000">*</font>Email-id</label>
                                      <input class="form-control validate[required,custom[email]]" name="email_id"  value="<?php if(isset($email_id)){
                                      echo $email_id; }
                                      //echo set_value('password'); ?>"/>

                                      </div>
                                      </div>


                                      


                                        <div class="col-md-6">
                                        <div class="form-group">
                                        <label><font color="#CC0000">*</font>Address</label>
                                        <input class="form-control validate[required]" name="address" value="<?php if(isset($address)){
                                        echo $address; }
                                        //echo set_value('password'); ?>"/>
                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>City</label>
                                            <input class="form-control validate[required]"  name="city" value="<?php if(isset($city)){
                      echo $city; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('city')) {?><br><font color="#CC0000"><?php echo form_error('city'); ?></font><?php } ?>
                                          
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>State</label>
                                            <input class="form-control validate[required]"  name="state" value="<?php if(isset($state)){
                      echo $state; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('state')) {?><br><font color="#CC0000"><?php echo form_error('state'); ?></font><?php } ?>
                                          
                                        </div>        
                                        </div>
                                        <div class="col-md-6">            
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Country</label>
                                          <select name="country" id="country" value="" class="form-control validate[required]">

                    <?php $country1=$this->admin_model->get_country();?>

                                       <?php

                    foreach($country1 as $record)

                    {

                    ?>                  

                   <option <?php if($record->id==$country){ echo 'selected="selected"';}?> value="<?php echo $record->id; ?>"><?php echo $record->country_name; ?></option>

                    <?php

                    }

                    

                    ?>

                    </select> 
                      <?php if(form_error('country')) {?><br><font color="#CC0000"><?php echo form_error('country'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Phone No.</label>
                                            <input class="form-control validate[required]"  name="phno" value="<?php if(isset($phno)){
                      echo $phno; }
                      //echo set_value('phno'); ?>" type="number" min="1"/>
                      <?php if(form_error('phno')) {?><br><font color="#CC0000"><?php echo form_error('phno'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Fax-No.</label>
                                            <input class="form-control validate[required]"  name="fax_no" value="<?php if(isset($fax_no)){
                      echo $fax_no; }
                      //echo set_value('email_id'); ?>" type="number" min="1"/>
                      <?php if(form_error('fax_no')) {?><br><font color="#CC0000"><?php echo form_error('fax_no'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Contactus Image</label>
                                            <input type="file" name="contactus_image" value="<?php if(isset($contactus_image)){
                      echo $contactus_image; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('contactus_image')) {?><br><font color="#CC0000"><?php echo form_error('contactus_image'); ?></font><?php }  if(isset($site_logo)){
                      ?>
                      <img src="<?php echo base_url();?>uploads/logo/<?php echo $contactus_image;?>" width="150px"/><?php } ?>                                        
                                        </div>
                                        </div>
                                        
                    <!--<div class="form-group">
                                            <label><font color="#CC0000">*</font>Databases</label>
                                            <input class="form-control validate[required]"  name="databases" value="<?php //if(isset($databases)){
                      //echo $databases; }
                      //echo set_value('email_id'); ?>"/>
                      <?php //if(form_error('databases')) {?><br><font color="#CC0000"><?php //echo form_error('databases'); ?></font><?php //} ?>                                        
                                        </div>-->
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>SiteURL</label>
                                            <input class="form-control validate[required]"  name="siteurl" value="<?php if(isset($siteurl)){
                      echo $siteurl; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('siteurl')) {?><br><font color="#CC0000"><?php echo form_error('siteurl'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
    <!--<div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000"></font>API key</label>
                                            <input class="form-control validate[required]"  name="apikey" value="<?php //if(isset($apikey)){
                      //echo $apikey; }
                      //echo set_value('phno'); ?>" type="text" />
                      <?php //if(form_error('apikey')) {?><br><font color="#CC0000"><?php// echo form_error('apikey'); ?></font><?php //} ?>                                        
                                        </div>
                                        </div>-->
										 <div class="col-md-6">
                    <div class="form-group col-md-10">
                                            <label><font color="#CC0000"></font>API key</label>
                                            <input class="form-control validate[required]" id="apikey"  name="apikey" value="<?php if(isset($apikey)){
                      echo $apikey; }
                      //echo set_value('phno'); ?>" type="text" /> 
                    </div><br>
                    <div class="col-md-2">
    <input type="button" onclick="change_key()" class="apigenerate btn btn-info" value="Generate">
                      <?php if(form_error('apikey')) {?><br><font color="#CC0000"><?php echo form_error('apikey'); ?></font><?php } ?>                                        
                                        </div>

                                        </div>

                                        <div class="col-md-6">
                                      <div class="form-group">
                                      <label><font color="#CC0000">*</font>Ticket Admin Support E-Mail</label>
                                      <input class="form-control validate[required,custom[email]]" name="ticket_mail"  value="<?php if(isset($ticket_mail)){
                                      echo $ticket_mail; }
                                      //echo set_value('password'); ?>"/>

                                      </div>
                                      </div>
                                      
                                        <div class="col-md-12">
                                         <label><font color="#CC0000">*</font>Set Map Location</label>
                                        <fieldset class="gllpLatlonPicker">
                                        <div class="row" align="center">
                                        <div class="col-md-4">
    <input type="text" class="gllpSearchField form-control" placeholder="Search Location">
        </div>
        <div class="col-md-3">
    <input type="button" class="gllpSearchButton btn btn-info" value="search">
        </div>
        </div>
  <br>
    <div class="gllpMap"><!--Google Maps--></div>

    <!--lat/lon:-->
      <input type="hidden" class="gllpLatitude" value="<?php echo $lang?>" name="lat"/>
      <!--/-->
      <input type="hidden" class="gllpLongitude" value="<?php echo $lat?>" name="lang"/>
    <!--zoom:--> <input type="hidden" class="gllpZoom" value="11"/>
    <!--<input type="button" class="gllpUpdateButton" value="update map">-->
    
  </fieldset>
                    </div>
                                        </div>
                                        <div class="row mar-top7">
                                        <h4 class="text-center text-uppercase text-danger"> <u>SEO Details</u></h4>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Site Title</label>
                                            <input class="form-control validate[required]"  name="site_title" value="<?php if(isset($site_title)){
                      echo $site_title; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('site_title')) {?><br><font color="#CC0000"><?php echo form_error('site_title'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Meta Title</label>
                                            <input class="form-control validate[required]"  name="meta_title" value="<?php if(isset($meta_title)){
                      echo $meta_title; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('meta_title')) {?><br><font color="#CC0000"><?php echo form_error('meta_title'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Meta Descripton</label>
                                            <input class="form-control validate[required]"  name="site_description" value="<?php if(isset($site_description)){
                      echo $site_description; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('site_description')) {?><br><font color="#CC0000"><?php echo form_error('site_description'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Meta Keyword</label>
                                            <input class="form-control validate[required]"  name="site_keyword" value="<?php if(isset($site_keyword)){
                      echo $site_keyword; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('site_keyword')) {?><br><font color="#CC0000"><?php echo form_error('site_keyword'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                    <div class="form-group">
                                            <label><font color="#CC0000">*</font>Site Logo</label>
                                            <input type="file" name="site_logo" value="<?php if(isset($site_logo)){
                      echo $site_logo; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('site_logo')) {?><br><font color="#CC0000"><?php echo form_error('site_logo'); ?></font><?php }  if(isset($site_logo)){
                      ?>
                      <img src="<?php echo base_url();?>uploads/logo/<?php echo $site_logo;?>" width="75px"/><?php } ?>                                        
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <h4 class="text-center text-uppercase text-danger"> <u>Social Links</u></h4>
                                        <hr>
                    <!--<div class="form-group">
                                            <label><font color="#CC0000">*</font>User Default Pic</label>
                                            <input type="file" name="user_defaultpic" value="<?php if(isset($user_defaultpic)){
                      echo $user_defaultpic; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('user_defaultpic')) {?><br><font color="#CC0000"><?php echo form_error('user_defaultpic'); ?></font><?php } 
                      if(isset($user_defaultpic)){
                      ?>
                      <img src="<?php echo base_url();?>uploads/<?php echo $user_defaultpic;?>" width="75px"/><?php }
?>                      
                                        </div>-->
                    
                    <!--<div class="form-group">
                                            <label><font color="#CC0000">*</font>Users date Format</label>
                                            <input class="form-control validate[required]"  name="users_dateformat" value="<?php //if(isset($users_dateformat)){
                      //echo $users_dateformat; }
                      //echo set_value('email_id'); ?>"/>
                      <?php //if(form_error('users_dateformat')) {?><br><font color="#CC0000"><?php //echo form_error('users_dateformat'); ?></font><?php //} ?>                                        
                                        </div>-->
                                        <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Facebook Url</label>
                                            <input type="url"class="form-control validate[required]"  name="facebook_url" value="<?php if(isset($facebook_url)){
                      echo $facebook_url; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('facebook_url')) {?><br><font color="#CC0000"><?php echo form_error('facebook_url'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Twitter Url</label>
                                            <input type="url"class="form-control validate[required]"  name="twitter_url" value="<?php if(isset($twitter_url)){
                      echo $twitter_url; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('twitter_url')) {?><br><font color="#CC0000"><?php echo form_error('twitter_url'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Google Url</label>
                                            <input type="text" class="form-control validate[required]"  name="google" value="<?php if(isset($google)){
                      echo $google; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('google')) {?><br><font color="#CC0000"><?php echo form_error('google'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Linkedin Url</label>
                                            <input type="text" class="form-control validate[required]"  name="linkedin" value="<?php if(isset($linkedin)){
                      echo $linkedin; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('linkedin')) {?><br><font color="#CC0000"><?php echo form_error('linkedin'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Skype Url</label>
                                            <input type="text" class="form-control validate[required]"  name="skype" value="<?php if(isset($skype)){
                      echo $skype; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('skype')) {?><br><font color="#CC0000"><?php echo form_error('skype'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Pinterest Url</label>
                                            <input type="text" class="form-control validate[required]"  name="pinterest" value="<?php if(isset($pinterest)){
                      echo $pinterest; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('pinterest')) {?><br><font color="#CC0000"><?php echo form_error('pinterest'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Youtube Url</label>
                                            <input type="url"class="form-control validate[required]"  name="youtube" value="<?php if(isset($youtube)){
                      echo $youtube; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('youtube')) {?><br><font color="#CC0000"><?php echo form_error('youtube'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Flickr Url</label>
                                            <input type="text" class="form-control validate[required]"  name="flickr" value="<?php if(isset($flickr)){
                      echo $flickr; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('flickr')) {?><br><font color="#CC0000"><?php echo form_error('flickr'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        </div>
                                        
                                         <h4 class="text-center text-uppercase text-danger"> <u>Google Analytics</u></h4>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Analytics Code</label>
                                            <textarea class="form-control validate[required]" name="analytics"> <?php if(isset($analytics)){  echo $analytics; }?></textarea>
                      <?php if(form_error('analytics')) {?><br><font color="#CC0000"><?php echo form_error('analytics'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        </div>
                                        
                                        <h4 class="text-center text-uppercase text-danger"> <u>Google ReCaptcha</u></h4>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-12">
                                        	<div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Site key</label>
                                            <input type="text"class="form-control validate[required]"  name="Site_key" value="<?php if(isset($Site_key)){
                      echo $Site_key; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('Site_key')) {?><br><font color="#CC0000"><?php echo form_error('Site_key'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Secret key</label>
                                            <input type="text"class="form-control validate[required]"  name="Secret_key" value="<?php if(isset($Secret_key)){
                      echo $Secret_key; }
                      //echo set_value('email_id'); ?>"/>
                      <?php if(form_error('Secret_key')) {?><br><font color="#CC0000"><?php echo form_error('Secret_key'); ?></font><?php } ?>                                        
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        
                                       <div style="text-align:center">
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    <?php echo form_close(); ?>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                </div>            
                
            
                
          </div>
          </div>
          </div>
            
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDv3fAYIfLl5H6SmuZKJtUIx6MyNn9xDbs"></script>
<script src="<?php echo base_url();?>admin_assets/js/jquery-gmaps-latlon-picker.js"></script>
                     <script>
function delcon()
{
var del=confirm("Are you sure want to delete");
if(del)
{
return true;
}
else
{
return false;
}
}

function change_key(){
var len=25;
  var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  var randomstring = '';
  len = len || 20
  for (var i = 0; i < len; i++) {
    var rnum = Math.floor(Math.random() * chars.length);
    randomstring += chars.substring(rnum, rnum + 1);
  }
 randomstring;
 //document.getElementById.apikey(randomstring);
document.getElementById("apikey").value = randomstring;
}
</script>