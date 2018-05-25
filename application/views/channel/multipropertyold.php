
<div style="background-image:url(data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>); background-position:top center; background-repeat:no-repeat; padding-top:150px; padding-bottom:150px;"> 

<h3 class="text-center white"><?php echo $title;?></h3>
</div>

<section class="content-n">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<div class="header-title"><div class="line-before"></div><h4 style="width: 200px;" class="head"><?php echo $title;?></h4><div class="line-after"></div></div>
<?php echo $content?>



</div>
</div>
</div>
</section>

   