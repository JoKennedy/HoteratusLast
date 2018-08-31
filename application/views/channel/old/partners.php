
<?php
foreach($partner as $cms){
  $image_content=$cms->image_content;
  $image=$cms->image;
  $right_content=$cms->right_content;
}
?>
<section class="partner_banner">
    <img src="<?php echo base_url();?>uploads/<?php echo $image;?>" class="img img-responsive">
    <div class="overlay_cont">
      <div class="container">
      <h3>Become a Partner</h3>
      <p><?php echo $image_content;?></p>
      </div>

    </div>
</section>

<section class="partner_content">
<div class="container">
<?php 
                   $success=$this->session->flashdata('success');
                    if($success)  { ?> 
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success!</strong> <?php echo $success;?>.
                  </div><?php }?>  
                  <?php  $error=$this->session->flashdata('error');
                    if($error)  { ?> 
                   <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Oh !</strong><?php echo $error;?>.
                  </div>
                  <?php }?>  
                   <?php if(isset($emailerror)){ ?>
                  <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Oh !</strong><?php echo $emailerror;?>.
                  </div>
                  <?php } ?>       
    <div class="row">
      <div class="header-title text-center">
      <div class="line-before"></div>
      <h4 class="head" style="width: 200px;">We look forward to working with you!</h4>
      <div class="line-after"></div></div>
      
    </div>

    <div class="row">
      <div class="col-sm-7">
        <div class="partner-form">


            <h3>How to Get Started</h3>
            <p>If your company is interested in integrating with us, 
            please read our policies and fill out the form.</p>
            <form role="form" method="post" action="<?php echo lang_url()?>partners/partner_join">
            <div class="form-group">
              <label for="">Country: *</label>
               <select class="form-control" name="country">
    <?php $country = get_data('country')->result_array();
  foreach($country as $value) { 
  extract($value); ?>
    <option value="<?php echo $id;?>"><?php echo $country_name;?></option>
    <?php } ?>
  </select>
            </div>
          <!--   <div class="form-group">
              <label for="">Company Type: *</label>
              <select class="form-control">
                <option value="Property Management System">Property Management System</option>
                <option value="Revenue Manager">Revenue Manager</option>
                <option value="Website Builder">Website Builder</option>
                <option value="Other">Other</option>
              </select>
            </div>         
            <div class="form-group">
              <label for="">How many properties (hotels, hostels, B&Bs, guesthouses, etc) do you have in your network?</label>
              <select id="property" name="" class="form-control">
                <option value="Please select number">Please select number</option>
                <option value="0 - I'm just starting!">0 - I'm just starting!</option>
                <option value="1 - 10">1 - 10</option>
                <option value="11 - 50">11 - 50</option>
                <option value="51 - 100">51 - 100</option>
                <option value="101 - 500">101 - 500</option>
                <option value="501 - 1000">501 - 1000</option>
                <option value="1000+">1000+</option>
                <option value="We're not that type of partner">We're not that type of partner</option>
              </select>
            </div>-->    
            


         <div class="form-group">
                              <label for="">Company Name: *</label>
              <input type="text" name="company"id="company"  class="form-control" value="<?php echo set_value('company');?>">
          <?php if(form_error('company')) {?><font color="#CC0000"><?php echo form_error('company'); ?></font><?php } ?>
          </div>
                    
<div class="form-group">
                            <label for="">Company Website: *</label>
              <input type="text" name="website"id="website" class="form-control" value="<?php echo set_value('website');?>">
          <?php if(form_error('website')) {?><font color="#CC0000"><?php echo form_error('website'); ?></font><?php } ?>
          </div>
            
                              
<div class="form-group">
                            <label for="">Contact Name: *</label>
 <div class="row">
                <div class="col-sm-6">
              <input type="text" name="contact"id="contact" class="form-control" value="<?php echo set_value('contact');?>">
                  First
          <?php if(form_error('contact')) {?><font color="#CC0000"><?php echo form_error('contact'); ?></font><?php } ?>
                </div>
                <div class="col-sm-6">
                  <input type="text" name="lastname"id="lastname" class="form-control">
                  Last
               
                </div>
              </div>


                            
          </div> 
            

            <div class="form-group">
                          <label for="">Email: *</label>
              <input type="text" name="email"id="email" onblur="check_mail(this.value)" class="form-control" value="<?php echo set_value('email');?>">
          <?php if(form_error('email')) {?><font color="#CC0000"><?php echo form_error('email'); ?></font><?php } ?>
          <span id="error_mail" style="color:red"></span>
          </div>

        

            <div class="form-group">
              <label for="">Phone:</label>
              <input type="text" name="phone"id="phone" class="form-control">
            </div>
        <!--     <div class="form-group">
              <label for="">Your Technology Platform and Language:</label>
              <input type="text" class="form-control">
              <p>Please let us know what your technology stack looks like and the language / languages your application is written in. This will give us information to help you with your integration and get your questions answered quickly.</p>
            </div> -->
            <div class="form-group">
              <label for="">comments:</label>
              <textarea id="comments" name="comments" class="form-control"></textarea>
            </div>          
            <div class="checkbox">
              <label>
                <input  id="agree" name="agree" type="checkbox"> I have read and agree to the Terms and Conditions
              </label>
               <?php if(form_error('agree')) {?><font color="#CC0000"><?php echo form_error('agree'); ?></font><?php } ?>
            </div>
            <div class="s2 button">
            <button type="submit" class="btn btn-success hvr-sweep-to-right">Submit</button>
            </div>
          </form>

        </div>
      </div>
      <div class="col-sm-5">
          <div class="partner_right">
             <?php echo $right_content;?>
          </div>
      </div>
    </div>

</div>
</section>





