<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="57x57" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="60x60" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="76x76" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="120x120" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="144x144" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="152x152" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="icon" type="image/png" sizes="192x192"  href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="icon" type="image/png" sizes="96x96" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= (isset($booking['logo'])) ? base_url('uploads/'.$booking['logo']) : '' ?>">
<link rel="manifest" href="<?php echo base_url();?>user_assets/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo base_url();?>user_assets/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= (isset($hotel['property_name'])) ? $hotel['property_name'] : $page_heading; ?></title>
<?php echo theme_css('bootstrap.min.css', true);?>
<?php echo theme_css('datepicker.css', true);?>
<?php echo theme_css('component.css', true);?>
<?php echo theme_css('demo.css', true);?>
<?php echo theme_css('style_dash.css?version='.rand(0,9999).'', true);?>


<?php echo theme_css('style_sidebar.css', true);?>


<?php echo theme_css('font-awesome.min.css', true);?>

<?php echo theme_css('dataTables.bootstrap.css', true);?>

<?php echo theme_css('jquery-ui.min.css', true);?>
 <script src="<?php echo base_url();?>user_asset/back/js/helpers.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url();?>user_asset/back/js/jquery-1.10.2.min.js"></script>
<?php echo theme_js('jquery-ui.min.js', true);?>
<link  href="<?php echo base_url();?>user_asset/back/css/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="<?php echo base_url();?>user_asset/back/js/fotorama.js"></script> <!-- 16 KB -->

<script src="<?php echo base_url();?>user_asset/back/js/sweetalert.min.js"></script>

</head>
<body>
<style>
#WindowLoad
{
    position:fixed;
    top:0px;
    left:0px;
    z-index:3200;
    filter:alpha(opacity=65);
   -moz-opacity:65;
    opacity:0.65;
    background:#999;
}
</style>