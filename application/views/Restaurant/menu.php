<nav class="navbar navbar-default ">
    
    
    <div >

            <a class="navbar-brand active">
                      <b><?= $Posinfo['description']?></b>
            </a>

        <div class="navbar-header page-scroll">
            <button id="menuresponsive" type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="statusbar" class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?=site_url('pos/viewpos/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>"> <i class="fa fa-home"></i> Home</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Settings <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
            if($Posinfo['postypeID']==1 || $Posinfo['postypeID']==3)
            { ?>
                            <li><a href="<?=site_url('pos/viewCreationtable/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Tables</a></li>
                            <?php }  ?>
                            <li><a href="<?=site_url('pos/viewEmployees/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>" ">Employees</a></li>
          <li><a href="<?=site_url( 'pos/viewCategories/'.secure($Posinfo[ 'hotelId']). '/'.insep_encode($Posinfo[ 'myposId']))?>">Categories</a>
                            </li>
                            <li><a href="<?=site_url('pos/viewProducts/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Products</a></li>
                            <?php
            if($Posinfo['postypeID']==1)
            { ?>
                                <li><a href="<?=site_url('pos/viewRecipes/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Recipes</a></li>
                                <?php }  ?>
                                <li><a href="<?=site_url('pos/viewSuppliers/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Suppliers</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Inventory<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=site_url('pos/viewInventory/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>">Inventory</a></li>
                    </ul>
                </li>
                <li><a href="#">Configuration</a></li>
            </ul>
            <ul  class="nav navbar-nav navbar-right">
                <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<!--https://bootsnipp.com

https://bootsnipp.com/snippets/featured/bootstrap-material-wizard
-->