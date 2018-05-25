
<div class="why">
<div class="container">
<div class="row">

<div class="col-md-4">
<div class="why_sec wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.9s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.9s; animation-name: fadeInUp;">
<div class="why_icon">
<i class="fa fa-share-alt" aria-hidden="true"></i>

</div>
<div class="cont_why">
<h3><?php echo $home_page_f->title; ?></h3>
<p><?php echo $home_page_f->content; ?></p>  
</div>
</div>
</div>

<div class="col-md-4">
<div class="why_sec wow fadeInUp" data-wow-duration="0.9s" data-wow-delay="1.2s" style="visibility: visible; animation-duration: 0.9s; animation-delay: 1.2s; animation-name: fadeInUp;">
<div class="why_icon">
<i class="fa fa-book" aria-hidden="true"></i>

</div>
<div class="cont_why">
<h3><?php echo $home_page_s->title; ?></h3>
<p><?php echo $home_page_s->content; ?></p>  
</div>
</div>
</div>

<div class="col-md-4">
<div class="why_sec wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="1.5s" style="visibility: visible; animation-duration: 1.2s; animation-delay: 1.5s; animation-name: fadeInUp;">
<div class="why_icon">
<i class="fa fa-facebook" aria-hidden="true"></i>

</div>
<div class="cont_why">
<h3><?php echo $home_page_t->title; ?></h3>
<p><?php echo $home_page_t->content; ?></p>  
</div>
</div>
</div>
<div class="clearfix"></div>
</div>


</div> 
</div>


<section class="features" id="features">
  <div class="container">
    <div class="col-md-6 wow slideInLeft" style="visibility: visible; animation-name: slideInLeft;">
    <?php $feature_image = get_data('home_cms',array('id'=>10))->row(); ?>
      <center>
        <img src="<?php echo base_url();?>uploads/<?php echo $feature_image->image; ?>" class="img-responsive mar-top-50">
      </center>
    </div>
    <div class="col-md-6 wow slideInRight" style="visibility: visible; animation-name: slideInRight;">
      <div class="heading wow bounceIn" style="visibility: visible; animation-name: bounceIn;">
      <h2> Features</h2>
      </div>
      <div class="fea_list">
        <ul>
          <li>
          <div class="fea_media media">
            <div class="media-left">
              <a href="#">
                <div class="fea_sec">
                  <div class="inr_sec">
                    <p><i class="fa fa-laptop"></i></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading"><?php echo $home_feature_f->content; ?></h4>
            </div>
          </div>
          </li>
          <li>
          <div class="fea_media media">
            <div class="media-left">
              <a href="#">
                <div class="fea_sec">
                  <div class="inr_sec">
                    <p><i class="fa fa-cloud-upload"></i></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading"> <?php echo $home_feature_s->content; ?> </h4>
            </div>
          </div>
          </li>
          <li>
          <div class="fea_media media">
            <div class="media-left">
              <a href="#">
                <div class="fea_sec">
                  <div class="inr_sec">
                    <p><i class="fa fa-exchange"></i></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading"><?php echo $home_feature_t->content; ?></h4>
            </div>
          </div>
          </li>
          <li>
          <div class="fea_media media">
            <div class="media-left">
              <a href="#">
                <div class="fea_sec">
                  <div class="inr_sec">
                    <p><i class="fa fa-money"></i></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="media-body">
              <h4 class="media-heading"><?php echo $home_feature->content; ?></h4>
            </div>
          </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="content2">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<div class="clearfix colelem shared_content" id="u38180-4" data-muse-uid="U38180" data-muse-type="txt_frame" data-content-guid="u38180-4_content"><!-- content -->
      <h3 id="u38180-2">Hoteratus Hospitality Software Solutions </h3>
     </div>
<p align="justify" class="top-10">
<?php $home_left = get_data('home_cms',array('id'=>8))->row();?>
<?php echo $home_left->content?>
<br/> <br/>
<?php $home_right = get_data('home_cms',array('id'=>9))->row();?>
<?php echo $home_right->content?> 
</p>
</div>
</div>
</div>
</section>




