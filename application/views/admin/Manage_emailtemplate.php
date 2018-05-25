<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Email Template
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
       <?php 
          if($action== "add") $pagetitle ="Add Template" ;
          else if($action== "edit") $pagetitle ="Edit Template" ;
          else if($action== "view_single" || $action== "view") $pagetitle ="View Template";
        ?>
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $pagetitle;?></h4>
	  <?php if($action== "view") { ?>
		<div class="panel-heading">
		<h3 class="panel-title"><a href="<?php echo lang_url(); ?>admin/manage_email/add"><button type="button" class="pull-right btn btn-primary">Add <i class="fa fa-plus"> </i></button></a></h3>
		</div>
	<?php } ?>
      <br><br>
      <!-- view -->
	  
<?php if($action== "view"){?>
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
                <th width="4%">S.No</th>
                <th width="29%">Title</th>
                <th width="38%">Subject</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=0;
              foreach($users as $row)
              {    
               $i++;
              ?>
                <tr>
                <td><?php echo $i; ?> </td>
                <td><?php echo $row->title; ?> </td>
                <td><?php echo $row->subject; ?> </td>
                <?php
                  echo "<td align='center'>";
                  $value=array('class'=>'edit');
                  echo anchor('admin/manage_email/edit/'.$row->id,'<i class="fa fa-pencil" title="Edit"></i>',$value);
				  if($row->type==1)
				  {
					$value=array('class'=>'delete','onclick'=>'return delcon();');
					echo anchor('admin/manage_email/delete/'.$row->id,'<i class="fa fa-remove" title="Delete" style="margin-left: 10px"></i>',$value);
				  }
				  echo "</td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
        </table>
        <?php
      }
      ?>
    <?php if($action== "edit" || $action== "add"){?>
	<div class="panel-body">
	<div class="row">
	<div class="col-lg-1">
	</div>
	<div class="col-lg-9">
	
	<?php if($action== "add")
	{
	?>
		<form action="<?php echo lang_url();?>admin/manage_email/add" id="edit_template" method="post" enctype="multipart/form-data">
		<input type="hidden" name="add_temp" value="add_temp">
	<?php 
	}
	else
	{
	?>
		<form action="<?php echo lang_url();?>admin/manage_email/update/<?php if(isset($id)){ echo $id; }?>" id="edit_template" method="post" enctype="multipart/form-data">
	<?php
	}
	?>
	<span class="error"><?php echo validation_errors();?></span>
	<div class="form-group">
	<label><font color="#CC0000">*</font>Title</label>
	<input class="form-control"  name="title" value="<?php if(isset($title)){ echo $title; }  ?>">
	</div>
	<div class="form-group">
	<label><font color="#CC0000">*</font>Subject</label>
	<input class="form-control" required name="subject" type="text" value="<?php if(isset($subject)){ echo $subject; }
	?>"/>
	</div>
	<div class="form-group">
	<label><font color="#CC0000"></font>Message</label>
	<textarea name="message" id="textareacontent" name="textareacontent" cols="30" rows="10" required ><?php if(isset($message)){ echo $message; }
	 ?></textarea>
	</div>
	<button type="submit" class="btn btn-success">Save</button>
	</form>
	</div>
	</div>
	</div>
      <?php
      }
      ?>
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'js/ckeditor/ckeditor.js'?>"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});

function delcon()
{
	var del	=	confirm("Are you sure want to delete");
	
	if(del)
	{
		return true;
	}
	else
	{
		return false;
	}
}

CKEDITOR.replace('textareacontent' ,
{    
                      
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
jQuery.validator.addMethod("lettersonly", 
      function(value, element) {
           return this.optional(element) || /^[a-z," "]+$/i.test(value);
      }, "Letters only please");
      
$.validator.addMethod("regexp", function(value, element, param) { 
  return this.optional(element) || !param.test(value); 
});
      
$('#edit_template').validate({
    rules:
    {
      subject:
      {
        required:true,
        regexp: /['"]/,
      },
	  title:
      {
        required:true,
      }
    },
  });

</script>
