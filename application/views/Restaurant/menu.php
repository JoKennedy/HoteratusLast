<nav class="navbar navbar-default">
  <div class="container-fluid " >
    <div class="navbar-header">
      <a class="navbar-brand active" ><?= $Posinfo['description']?></a>
    </div>
    <ul class="nav navbar-nav">
      <li ><a href="<?=site_url('pos/viewpos/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>"> <i class="fa fa-home"></i> Home</a></li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Maintenance <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="<?=site_url('pos/viewCreationtable/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Tables</a></li>
          <li><a href="<?=site_url('pos/viewEmployees/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>"">Employees</a></li>
          <li><a href="#">Other</a></li>
        </ul>
      </li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Inventory<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="<?=site_url('pos/viewCategories/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Categories</a></li>
          <li><a href="<?=site_url('pos/viewProducts/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Products</a></li>
          <li><a href="<?=site_url('pos/viewSuppliers/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Suppliers</a></li>
          <li><a href="#">Inventory</a></li>
        </ul>
      </li>
      <li><a href="#" >Configuration</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo base_url();?>channel/managepos"><span class="glyphicon glyphicon-user"></span>All POS</a></li>
    </ul>
  </div>
</nav>


<!--https://bootsnipp.com

https://bootsnipp.com/snippets/featured/bootstrap-material-wizard
-->



