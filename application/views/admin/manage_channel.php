<?php $this->load->view('admin/header');?>
  
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Channel
  </div>
  </div>
  <div class="manage">
    <div class="row-fluid clearfix">
			  <div class="row">
					<div class="panel-heading"><a href="<?php echo lang_url(); ?>index.php/admin/add_channels/"><button type="button" style="width: 157px; height: 33px; margin-top: -8px; float:left;" class="pull-right label label-success">Add New <i class="fa fa-plus"></i></button></a>
					<h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Manage Admin</h3>
					</div>
				</div>
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


      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>View Channels</h4>
      <br><br>
	  
      <!-- view -->
	
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
                                        <tr class="top-tr">
											<th>S.No.</th>
                                            <th>Channel Name</th>
                                            <th>Channel Username</th>
                                            <th>Channel Password</th>
                                            <th>Channel Image</th>
											<th>Status</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                             <?php $tmp=$this->admin_model->get_all_channel();
							$i=0;
							  foreach($tmp as $row)
							   {		
									$i++;
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><?php echo $row->channel_name; ?> </td>
								<td><?php echo $row->channel_username; ?> </td>
								<td><?php echo $row->channel_password; ?> </td>
								<td class="center">
								  <?php 
								    if($row->image!=""){
								  ?>
							<a id="change_model"  data-toggle="modal" href="#myModal"></a>
							<img class="change_image" id="change_image<?php echo $row->channel_id;?>" data-image="<?php echo $row->image;?>" data-id="<?php echo $row->channel_id;?>" src="<?php echo base_url();?>uploads/<?php echo $row->image; ?>" width="60px" height="60px" >
							<?php
							}else{
							?>
							<a id="change_model"  data-toggle="modal" href="#myModal"></a>
							<img class="change_image" id="change_image<?php echo $row->channel_id;?>" data-image="no-image.png" data-id="<?php echo $row->channel_id;?>" src="<?php echo base_url();?>uploads/no-image.png" width="60px" height="60px" >
							<?php	
							}  
							?>
								</td>  
								<td><?php if($row->status=='Active') { echo '<span class="label label-sm label-success">Active</span>' ;} else { echo '<span class="label label-sm label-danger">Inactive</span>';} ?>  </td>
								<?php
								//$cid=$this->admin_model->encryptIt($row->channel_id);
                                                                    $cid = $row->channel_id;
								echo "<td>";
                                                    echo anchor('admin/channel_update/'.$cid,'<i class="fa fa-pencil" title="edit"></i>').'&nbsp;';        
                                               
						  	$value=array('class'=>'edit','onclick'=>'');
							echo anchor('admin/channel_change_status/'.$cid,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
								echo "</td>";
								echo "</tr>";
							}
					       ?>
                            </tbody>
        </table>
        
        </div>
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
$(document).ready(function(){
$(".change_image").click(function(){
 var id=this.id;
 var image=$('#'+id).attr('data-image');
 $('#hidimage').val(image);
 var data_id=$('#'+id).attr('data-id');
 $('#current_channel').val(data_id);
 $('.img_profile').attr('src', '<?php echo base_url(); ?>uploads/'+image);
 $('#change_model').trigger('click');
});
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
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});
CKEDITOR.replace('features_content' ,
{    
                      
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>     