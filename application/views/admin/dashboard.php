<?php $this->load->view('admin/header');?>
<div class="breadcrumbs">
<div class="row-fluid clearfix">
<i class="fa fa-home"></i> Dashboard
<!-- <span class="pull-right"><a href="<?php //echo lang_url(); ?>channel/manage_rooms"><i class="fa fa-globe"></i> Extranet l <i class="fa fa-star-o"></i> </a></span> -->
</div>
</div>
<div class="manage">

<div class="row-fluid clearfix">
        <div class="col-md-12">
        <!--  <h3 class="page-title"> Dashboard </h3> -->
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="fa fa-home"></i>
              <a href="<?php echo lang_url(); ?>admin/dashboard">
                Home
              </a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li>
              <a href="<?php echo lang_url(); ?>admin/dashboard">
                Dashboard
              </a>
            </li>
            
          </ul>
        </div>
      </div>

<div class="row-fluid clearfix">

             
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="dashboard-stat blue">
            <div class="visual">
              <i class="fa fa-user"></i>
            </div>
            <div class="details">
			<?php $ho = $this->db->get('manage_hotel');
				  $h = $ho->num_rows();
			?>
              <div class="number">
              <?php echo $h; ?>
              </div>
              <div class="desc">
                Hoteliers
              </div>
            </div>
            <a href="<?php echo lang_url(); ?>admin/all_users" class="more">
               View Details <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="dashboard-stat green">
            <div class="visual">
              <i class="fa fa-server"></i>
            </div>
            <div class="details">
			<?php   $ch = $this->db->get('manage_channel');
					$c = $ch->num_rows();
			?>
              <div class="number">
              <?php echo $c; ?>
              </div>
              <div class="desc">
              Channels
              </div>
            </div>
            <a href="<?php echo lang_url(); ?>admin/all_channels" class="more">
                View Details <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
                
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="dashboard-stat purple">
            <div class="visual">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
			 <?php $p = $this->db->get('manage_property');
					 $p_count = $p->num_rows();
				?>
              <div class="number">
				<?php echo $p_count; ?>
              </div>
              <div class="desc">
                Property
              </div>
            </div>
            <a href="<?php echo lang_url(); ?>admin/all_hotels" class="more">
               View Details<i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
        
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="dashboard-stat yellow">
            <div class="visual">
              <i class="fa fa-life-ring"></i>
            </div>
            <div class="details">
				<?php $res = $this->db->get('manage_reservation');
					   $r = $res->num_rows();
				?>
              <div class="number">
               <?php echo $r; ?>
              </div>
              <div class="desc">
                Reservation
              </div>
            </div>
            <a href="<?php echo lang_url(); ?>admin/manage_reservation/view" class="more">
               View Details <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
            

</div>


<!--<div class="row-fluid clearfix">
<div class="col-md-6 mt20">
<h2>Hotel Status</h2>

<br>
<ul style="margin:0px; padding:0px;">

<li class="front-bor clearfix"> <h3>1</h3><span>Hotel failing to update channels</span> </li>
<li class="front-bor clearfix"> <h3>0</h3><span>Hotels with channels experiencing delayed updates</span> </li>
<li class="front-bor clearfix"> <h3>9</h3><span>Hotel with disabled channel connections</span> </li>
<li class="front-bor clearfix"> <h3>3</h3><span>Hotel with  channel connections awaiting activation</span> </li>


</ul>


</div>

<div class="col-md-6 mt20">
<h2>System Alerts</h2>

<h2>Channel Alerts</h2>

<div class="row clearfix">
<div class="col-md-12">
<div id="accordion" class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a href="#collapseeight" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle">
        CentralR - Issues with Transmitting Reservations
        </a><i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
      </h4>
    </div>
    <div class="panel-collapse collapse" id="collapseeight">
      <div class="panel-body">
       <h3>Heading</h3>
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it 
      </div>
    </div>
</div>
</div>
</div>
</div>

<h2>PMS Alerts</h2>


</div>

</div>


<div class="row-fluid clearfix">
<div class="col-md-6">
<h2>Reservations</h2>

<br>
<ul style="margin:0px; padding:0px;">

<li class="front-bor clearfix"> <h3>1</h3><span>Failed PMS reservation delivers today</span> </li>

</ul>
</div>
</div>-->

</div>
<?php $this->load->view('admin/footer');?>

