<?php
$this->load->view('channel/header');
foreach($integration as $cms){
  $image_content=$cms->image_content;
  $image=$cms->image;
  $content=$cms->content;
}
?>
<section class="integration_banner">
    <div class="container">

          <div class="row">
              <div class="col-sm-6">
                  <h3>Integrations</h3>
                  <p>
                    <?php echo $image_content;?>
                  </p>  </div>
              <div class="col-sm-6 pull-right">
                 <img src="<?php echo base_url();?>uploads/<?php echo $image;?>" class="img img-responsive">
              </div>
          </div>
    </div>
</section>

<section class="integration_content">
<div class="container">
<?php echo $content;?>

</div>
</section>

<?php
$this->load->view('channel/footer');
?>