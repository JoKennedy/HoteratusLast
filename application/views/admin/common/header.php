<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<style>
.error{color: red;}
</style>
    <title>Channel Manager</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>css/plugins/morris.css" rel="stylesheet">
    <link href="<?php echo base_url().'css/validationEngine.jquery.css'?>" rel="stylesheet" type="text/css" />
    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>js/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>js/plugins/select2/select2-metronic.css"/>
    <link rel="stylesheet" href="<?php echo base_url()?>js/plugins/data-tables/DT_bootstrap_edit.css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<!-- jQuery -->
    <script src="<?php echo base_url();?>js/jquery-1.11.0.min.js"></script>
   
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url().'js/jquery.validationEngine-en.js'?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url().'js/jquery.validationEngine.js'?>" type="text/javascript" charset="utf-8"></script>
    <!-- Morris Charts JavaScript -->
<!--	
    <script src="<?php echo base_url();?>admin_css/js/jquery.js"></script>

    <script src="<?php echo base_url();?>admin_css/js/plugins/morris/raphael.min.js"></script>

    <script src="<?php echo base_url();?>admin_css/js/plugins/morris/morris.min.js"></script>

    <script src="<?php echo base_url();?>admin_css/js/plugins/morris/morris-data.js"></script>
    
<script type='text/javascript' src="<?php echo base_url();?>js/jquery-1.9.1.js"></script>           
                      
<script src="<?php echo base_url();?>js/highcharts.js"></script>
<script src="<?php echo base_url();?>js/exporting.js"></script>-->
<script>
/*$(function(){
var height=$('body').height()-$('.navbar-ex1-collapse').height()+50;
$('.side-nav').css('height',height);
	});*/
</script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="<?php echo base_url()?>js/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/plugins/data-tables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/plugins/data-tables/DT_bootstrap_edit.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php echo base_url()?>js/app.js"></script>
    <script src="<?php echo base_url()?>js/table-advanced.js"></script>
    <script>
    jQuery(document).ready(function() {       
       App.init();
       TableAdvanced.init();
    });
    </script>
</head>
