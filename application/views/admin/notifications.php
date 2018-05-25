<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-send-o"></i> Alerts / Announcements
  </div>
  </div>
  <div class="manage">
    <div class="row-fluid clearfix">
   
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>Alerts / Announcements</h4>
      <br><br>
      <div class="row">
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
        </div>
      <?php 
      }
      $error=$this->session->flashdata('error');                   
      if($error)  { ?> 
        <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
        </div>
      <?php }?> 
                <!-- /.col-lg-6 -->
                 <div class="col-lg-1">

                </div>
            <div class="col-lg-10">
              <div class="col-lg-1">
              </div>
              <div class="col-lg-12">
                <form id="send_news" class="form-horizontal clscommon_form" role="form" action="<?php echo lang_url()?>admin/notifications" method="post" enctype="multipart/form-data">
                  <!--<form class="form-horizontal" role="form">-->
                  <div class="form-body">
                    <div class="form-group">
                      <label class="col-md-3 control-label">Type</label>
                      <div class="col-md-9">
                        <div class="radio-list">
                          <label class="radio-inline">
                          <input type="radio" name="type" id="users1" value="2" checked="checked"> Alerts</label>
                          <label class="radio-inline">
                          <input type="radio" name="type" id="users2" value="1" > Announcements</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Send To</label>
                      <div class="col-md-9">
                        <div class="radio-list">
                          <label class="radio-inline">
                          <input type="radio" name="sendto" id="sendto1" value="1" checked="checked"> Hotels</label>
                          <label class="radio-inline">
                          <input type="radio" name="sendto" id="sendto2" value="2" > White Labels</label>
                       <label class="radio-inline">
                          <input type="radio" name="sendto" id="sendto3" value="3" > All</label>
                        
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="u_fname" class="control-label col-sm-3">Title</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" name="title"  required/>                      <div id="editor2_error"></div>
                      </div>
                    </div>
                    <div class="form-group">
                              <label for="u_fname" class="control-label col-sm-3"><font color="#CC0000">*</font>Content</label>
                               <div class="col-sm-6">
                              <textarea id="textareacontent" class="form-control" name="content" type="text" ></textarea>
                      </div>
                      <span class="error">
                      <?php echo form_error('content');  ?></span>
                    </div>
                  </div>
                  <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="submit" class="btn btn-success" value="Submit"/>
                      <button type="reset" class="btn default">Reset</button>
                    </div>
                  </div>

                </form>
                  <br><br><br> 
              </div>           
            </div>
            <div class="col-lg-12">
              <div class="cls_box">
                <h4>Manage Alerts & Announcements</h4>
                
                <div class="panel-body">
                  <div class="table-responsive">
                  <form name="notification_delete" id="notification_delete" method="post" action="<?php echo lang_url();?>admin/notifications_delete">
                    <table class="table table-striped table-bordered table-hover table-full-width" id="notification_table">
                      <thead>
                        <tr>
                          <th ><input type="checkbox"  name="delete_all" id="delete_all" /><button class="btn btn-small btn-danger" onclick="return multiple_delete();" name="del_mul" type="submit"><i class="fa fa-trash-o"></i></button></th>
                          <th>S.No.</th>
                          <th>Type</th>
                          <th>Send To</th>
                          <th>Title</th>                     
                          <th>Content</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $i=0; ?>
                      <?php foreach ($notifications as $notf) {
                        $i++;
                       ?>
                        <tr>
                        <td><input class="d_check" type="checkbox" name="delete_multiple[]" value="<?php echo insep_encode($notf->n_id);?>"></td>
                          <td><?php echo $i; ?></td>
                          <?php if($notf->type == 1) {?>
                            <td>Announcements</td>
                          <?php }else if($notf->type == 2){?>
                            <td>Alerts</td>
                          <?php } ?>
                          <?php if($notf->sendto == 1) {?> 
                            <td>Hotels</td>
                          <?php }else if($notf->sendto == 2) {?>
                            <td>White Labels</td>
                          <?php }else if($notf->sendto == 3) {?>
                            <td>All</td>
                          <?php } ?>
                          <td><?php echo $notf->title;?></td>
                          <td><?php echo $notf->content;?></td>
                          <td><?php echo ucfirst($notf->status);?></td>
                          <td><a class="delete" onclick='return delcon();' href="<?php echo lang_url();?>admin/notifications_delete/<?php echo insep_encode($notf->n_id); ?>"><i class="fa fa-trash-o" title="delete"></i>
                          </a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    </table>
                    </form>
                  </div>
                </div>
              </div>
          </div>
           </div> 
            
      </div>
     </div>
    </div>
  </div>

<?php $this->load->view('admin/footer');?>
<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/ckeditor/ckeditor.js'?>"></script>

<script>



    $('#pay_form').submit(function(e){

    e.preventDefault();

    if(!$("#pay_form").validationEngine('validate') == true)
    {
    var body = $("html");
        body.animate({scrollTop:0}, function() { 
        })

      return false;
    }
  else
  {
    document.pay_form.submit();
  }
  });

$(document).ready(function(){
  $("#notification_table").DataTable();

  $("#delete_all").click(function(){
    if(this.checked){
      $(".d_check").each(function(){
        this.checked= true;
      });
    }else{
      $(".d_check").each(function(){
        this.checked= false;
      });
    }
  });
  
  
  $('#send_news').validate({
    rules:
    {
      subject:
      {
        required:true,
        regexp: /['"]/,
      },
      "all":  {required:true},
      "sub":  {required:true}
    },
    messages: {
        subject: {
            required: "Should not be blank",
            regexp: "Single quotes and double quotes not allowed"
        }
    }
  });

  });
  
  $('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
      
        case 'jpeg':
        case 'jpg':
        case 'png':
    
    case 'gif':
        case 'tiff':
        case 'bmp':
      
            $('#uploadButton').attr('disabled', false);
            break;
        default:
        $('#uploadButton').attr('disabled', true);
            alert('This is not an allowed file type.');
            this.value = '';
    }
});

CKEDITOR.replace('textareacontent' ,
{    
                      
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});


$('#users1').click(function() {
  
  $('#all').show();
  $('#sub').hide();
});
$('#users2').click(function() {
  
  $('#sub').show();
  $('#all').hide();
});


</script>
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
function multiple_delete(){
  var checked = false;
  $(".d_check").each(function(){
    if(this.checked){
      checked = true;
    }
  });
  if(checked){
    var del=confirm("Are you sure want to delete");
    if(del)
    {
    return true;
    }
    else
    {
    return false;
    }
  }else{
    alert("Select atleast One Notification");
    return false;
  }
}
</script>

