<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Property
  </div>
  </div>


  <div class="manage">
    <div class="row-fluid clearfix">
    <?php     
          if(isset($error)) { ?> 
           <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oh! </strong><?php echo $error;?>.
          </div>
          <?php }?>     
          <?php 
           $success=$this->session->flashdata('success');                 
            if($success)  { ?> 
            <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success! </strong> <?php echo $success;?>.
          </div><?php }?>  
          <?php  $error=$this->session->flashdata('error');                   
            if($error)  { ?> 
           <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Oh! </strong><?php echo $error;?>.
          </div>
          <?php }?> 
       <?php 
         if($action== "view_single" || $action== "view") $title ="Manage Property" ;
        ?>
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
      <br><br>
      <!-- view -->
<?php if($action== "view"){?>
      <table class="table table-striped table-bordered table-hover table-full-width" id="sample_2">
                                <thead>
                                    <tr>
										<th>S.No.</th>
                                        <th>Property Name</th>
										<th>City</th>
                                        <th>Country</th>
							     		<th>Status</th>
										<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                              <?php
							  $i=0;
							  if($property){
							  foreach($property as $row)
							   {		
							   		$pass_id=$this->admin_model->encryptIt($row->hotel_id);
									$owner_id=$this->admin_model->encryptIt($row->owner_id);
									
									$i++;
									$user = get_data(TBL_USERS,array('user_id'=>$row->owner_id))->row();
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><a href="<?php echo lang_url(); ?>index.php/admin/view_property/<?php echo $owner_id.'/'.$pass_id; ?>" title="View Property" ><?php echo $row->property_name; ?> </a></td>
								
								<!--<td><?php //echo number_format((float)$row->price, 2, '.', '');?> </td>-->
								<td><?php echo $row->town; ?> </td>
								<!--<td><?php //if($row->pricing_type=='2') { echo 'Guest based pricing';}elseif($row->pricing_type=='1'){ echo 'Room based pricing';}?> </td>-->
                                <td> <?php if($row->country!='' || $row->country!=0) { echo get_data(TBL_COUNTRY,array('id'=>$row->country))->row()->country_name; } else { echo '---';}?></td>
								<td> 
									<?php if($row->status=='0') { echo '<span class="label label-sm label-danger">Inactive</span>';} else { echo '<span class="label label-sm label-success">Active</span>'; } ?>  </td>
								<?php
								echo "<td>";
								$pass_id=$this->admin_model->encryptIt($row->hotel_id);
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/manage_property/delete/'.insep_encode($row->owner_id).'/'.insep_encode($row->hotel_id),'<i class="fa fa-times" title="delete"></i>',$value).'&nbsp;';
				
				            
				$value=array('class'=>'edit','onclick'=>'');
				echo anchor('admin/manage_property/status/'.insep_encode($row->owner_id).'/'.insep_encode($row->hotel_id),'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
				
				/*echo anchor('admin/manage_property_room/view/'.insep_encode($row->owner_id).'/'.insep_encode($row->hotel_id),'<i class="fa fa-eye fa-fw" title="View Property Room"></i> ');*/
			//	echo "Change Status";
				
				
								echo "</td>";
								echo "</tr>";
								}
							  }/*else{
							  	echo "<tr><td colspan='9'>No records found</td></tr>";
							  }*/
								?>
                                    </tbody>
                                </table>
        <?php
      }
      ?>
    
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
    <script type="text/javascript" src="<?php echo base_url().'js/ckeditor/ckeditor.js'?>"></script>

     <script>
function delcon()
{
var del=confirm("Are you sure want to delete");
if(del)
{
return true;
}
else
{
return false;
}
}
</script>
 <script>
 
 $(document).ready(function(){
  $("#addabout").validationEngine();
  $("#addabout1").validationEngine();
  });

function showimagepreview(input) {
     $("#img_profile img").css({"opacity":"0.3"});
      $(".img_profile_loading").css("display","");
if (input.files && input.files[0]) {
var filerdr = new FileReader();
filerdr.onload = function(e) {
$(".img_profile_loading").css("display","none");
$('.img_profile').attr('src', e.target.result);
}
filerdr.readAsDataURL(input.files[0]);
}
}

$('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
      
        case 'jpeg':
        case 'jpg':
        case 'png':
    
    case 'gif':
        case 'tiff':
        case 'bmp':
        $('.img_profile').show();
            $('#uploadButton').attr('disabled', false);
            break;
        default:
        $('#uploadButton').attr('disabled', true);
            alert('This is not an allowed file type.');
        $('.img_profile').hide();
            this.value = '';
    }
});

</script>
 <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
CKEDITOR.replace('aboutcontent' ,
{    
                      
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>
