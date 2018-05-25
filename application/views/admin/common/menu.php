<div id="wrapper">

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="<?php echo lang_url(); ?>index.php/admin/dashboard">Channel manager</a>
</div>
<!-- Top Menu Items -->
<ul class="nav navbar-right top-nav">


<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-fw fa-key"></i> <b class="caret"></b></a>
<ul class="dropdown-menu alert-dropdown">
<li>
<a href="<?php echo lang_url(); ?>index.php/admin/site_config"> <i class="fa fa-fw fa-gear"></i> Site Config<span class="label label-default"></span></a>
</li>
</ul>
</li>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->session->userdata('user'); ?> <b class="caret"></b></a>
<ul class="dropdown-menu">
<li>
<a href="<?php echo lang_url(); ?>index.php/admin/admin_profile"><i class="fa fa-fw fa-user"></i> Profile</a>
</li>
<li class="divider"></li>
<li>
<a href="<?php echo lang_url(); ?>index.php/admin/log_out"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
</li>
</ul>
</li>
</ul>
<?php $this->load->view('admin/common/sidebar'); ?>
</nav>