<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand active" ><?= $Posinfo['description']?></a>
    </div>
    <ul class="nav navbar-nav">
      <li ><a href="<?=site_url('pos/viewpos/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>"> <i class="fa fa-home"></i> Home</a></li>
      <li><a href="#">Maintenance</a></li>
      <li><a href="#">Configuration</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo base_url();?>channel/managepos"><span class="glyphicon glyphicon-user"></span>All POS</a></li>
    </ul>
  </div>
</nav>
<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
