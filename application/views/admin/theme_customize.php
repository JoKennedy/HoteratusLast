<?php $this->load->view('admin/header');?>
<style>
.gllpMap	{ /*width: 880px;*/ height: 250px;  } .mar-top7{ margin-top:7px;} 
</style>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>


  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-gear"></i> Theme Customize
  </div>
  </div>
  <div class="manage">
    <div class="row-fluid clearfix">
    
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>Theme Customize</h4>
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
				<div class="col-lg-12">
				<?php $attributes=array('class'=>'form form-horizontals','id'=>'siteconfig','name'=>'siteconfig');
				echo form_open_multipart('admin/theme_customize/',$attributes); ?>

				<span class="error"><?php echo validation_errors();?></span>
					<h4 class="text-center text-uppercase text-danger"> <u>Header</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label>Home Header One <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="h_header_one" value="<?php if(isset($theme_customize['h_header_one'])){ echo $theme_customize['h_header_one']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Home Feature One <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="h_header_two" value="<?php if(isset($theme_customize['h_header_two'])){ echo $theme_customize['h_header_two']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Inner Header <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="inner_header" value="<?php if(isset($theme_customize['inner_header'])){ echo $theme_customize['inner_header']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Inner Feature Two <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="inner_header_menu_hover" value="<?php if(isset($theme_customize['inner_header_menu_hover'])){ echo $theme_customize['inner_header_menu_hover']; } ?>">
						</div>
						</div>
					</div>

					<!-- sidebar starts here -->

					<h4 class="text-center text-uppercase text-danger"> <u>Sidebar</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label> Sidebar Background <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="sidebar_bg" value="<?php if(isset($theme_customize['sidebar_bg'])){ echo $theme_customize['sidebar_bg']; } ?>">
						</div>
						</div>

						<div class="col-md-6">
						<div class="form-group">
							<label> Sidebar List background <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="sidebag_li_bg" value="<?php if(isset($theme_customize['sidebag_li_bg'])){ echo $theme_customize['sidebag_li_bg']; } ?>">
						</div>
						</div>

						<div class="col-md-6">
						<div class="form-group">
							<label>Sidebar List  Active <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="sidebar_active" value="<?php if(isset($theme_customize['sidebar_active'])){ echo $theme_customize['sidebar_active']; } ?>">
						</div>
						</div>	

					</div>

					<!-- sidebar ends here -->

					<!-- Calendar section starts here -->

					<h4 class="text-center text-uppercase text-danger"> <u>Calendar Status</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label> Booking Reserved <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="b_reserved" value="<?php if(isset($theme_customize['b_reserved'])){ echo $theme_customize['b_reserved']; } ?>">
						</div>
						</div>

						<div class="col-md-6">
						<div class="form-group">
							<label>  Booking Confirmed <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="b_confirmed" value="<?php if(isset($theme_customize['b_confirmed'])){ echo $theme_customize['b_confirmed']; } ?>">
						</div>
						</div>
 
						<div class="col-md-6">
						<div class="form-group">
							<label> Check In <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="b_canceled" value="<?php if(isset($theme_customize['b_canceled'])){ echo $theme_customize['b_canceled']; } ?>">
						</div>
						</div>	

						<div class="col-md-6">
						<div class="form-group">
							<label> Check Out <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="b_pending" value="<?php if(isset($theme_customize['b_pending'])){ echo $theme_customize['b_pending']; } ?>">
						</div>
						</div>	

					</div>

					<!-- calendar ends here -->

					<h4 class="text-center text-uppercase text-danger"> <u>Home Content</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label>Our Features <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="our_features" value="<?php if(isset($theme_customize['our_features'])){ echo $theme_customize['our_features']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Free Trial <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="free_trial" value="<?php if(isset($theme_customize['free_trial'])){ echo $theme_customize['free_trial']; } ?>">
						</div>
						</div>
					</div>




					<h4 class="text-center text-uppercase text-danger"> <u>Dashboard Content</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label>New Reservations <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="new_reservations" value="<?php if(isset($theme_customize['new_reservations'])){ echo $theme_customize['new_reservations']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>New Cancelations <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="new_cancelations" value="<?php if(isset($theme_customize['new_cancelations'])){ echo $theme_customize['new_cancelations']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Arrivals <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="arrivals" value="<?php if(isset($theme_customize['arrivals'])){ echo $theme_customize['arrivals']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Departures <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="departures" value="<?php if(isset($theme_customize['departures'])){ echo $theme_customize['departures']; } ?>">
						</div>
						</div>

						<!--  hover effects starts  -->

						<div class="col-md-6">
						<div class="form-group">
							<label>New Reservations Hover <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="new_reservations_hover" value="<?php if(isset($theme_customize['new_reservations_hover'])){ echo $theme_customize['new_reservations_hover']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>New Cancelations  Hover <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="new_cancelations_hover" value="<?php if(isset($theme_customize['new_cancelations_hover'])){ echo $theme_customize['new_cancelations_hover']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Arrivals  Hover <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="arrivals_hover" value="<?php if(isset($theme_customize['arrivals_hover'])){ echo $theme_customize['arrivals_hover']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Departures  Hover <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="departures_hover" value="<?php if(isset($theme_customize['departures_hover'])){ echo $theme_customize['departures_hover']; } ?>">
						</div>
						</div>

						<!--  hover effects ends here -->
						
					</div>
					<h4 class="text-center text-uppercase text-danger"> <u>Footer</u></h4>
					<hr>
					<div class="row">
						<div class="col-md-6">
						<div class="form-group">
							<label>Footer One <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="footer_one" value="<?php if(isset($theme_customize['footer_one'])){ echo $theme_customize['footer_one']; } ?>">
						</div>
						</div>
						<!-- <div class="col-md-6">
						<div class="form-group">
							<label>Footer Two <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="footer_two" value="<?php if(isset($theme_customize['footer_two'])){ echo $theme_customize['footer_two']; } ?>">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Inner Footer <font color="#CC0000">*</font> </label>
							<input type="text" class="form-control jscolor" name="inner_footer" value="<?php if(isset($theme_customize['inner_footer'])){ echo $theme_customize['inner_footer']; } ?>">
						</div>
						</div> -->
					</div>
					
					
					<div style="text-align:center">
					<button type="submit" class="btn btn-success">Save Changes</button>
					<button type="reset" class="btn btn-danger">Reset</button>
					</div>
					<?php echo form_close(); ?>
					</br>
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
<script src="<?php echo base_url();?>admin_assets/js/jquery-gmaps-latlon-picker.js"></script>
<script src="<?php echo base_url();?>admin_assets/js/jscolor.js"></script>
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