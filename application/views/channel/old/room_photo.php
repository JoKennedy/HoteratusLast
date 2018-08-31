<ul class="list-unstyled row" id="hot_pho">
<?php if(count($hotel_photos)!=0) { $photos = explode(',',$hotel_photos['photo_names']); 
foreach($photos as $val)
{
?>
<li class="col-md-3 col-sm-3">
<div class="box-y">
<a class="fancybox" href="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" data-fancybox-group="gallery">
<img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" alt="" class="img img-responsive img-thumbnail" /></a>
<div class="overbox-y">

	    <!--<div class="title overtext pull-left"> <a href="#" data-toggle="modal" data-target="#myModa-f1"><span class="ne-pk"><i class="fa fa-edit"></i></span></a> </div>-->
  <div class="title overtext text-center pull-right"> <a href="javascript:;"  class="delete_photo" data-id="<?php echo insep_encode($hotel_photos['photo_id']);?>" custom="<?php echo insep_encode($val);?>"><span class="ne-pk"><i class="fa fa-close"></i></span></a> </div>
	  </div>

</div></li>
<!--<li class="col-md-3 col-sm-3"><a class="fancybox" href="data:image/png;base64,<?php //echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" data-fancybox-group="gallery"><img src="data:image/png;base64,<?php //echo base64_encode(file_get_contents("uploads/room_photos/".$val));?>" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><a href="javascript:;" class="delete_photo" data-id="<?php //echo insep_encode($hotel_photos['photo_id']);?>"custom="<?php echo insep_encode($val);?>"><i class="fa fa-close"></i></a></div></li>-->
<?php }  } else {  ?>
<div class="alert alert-danger text-center"> No photos data found!!!</div>
<?php } ?>


</ul>


<!--hmm1.create sub user per hoelier and give rights for each sub user.
2.Give a login for each sub user.
3. Multiple image uploading with gallery section.
3.Complete Inventory section without API. Single and bulk updation used to our database.

http://phptrends.com/dig_in/payments?page=1

http://phptrends.com/

-->