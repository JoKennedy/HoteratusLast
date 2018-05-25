<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> FAQ
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
          if($action== "add") $title ="Add Faq " ;
          else if($action== "edit") $title ="Edit Faq " ;
          else if($action== "view_single" || $action== "view") $title ="View Faq ";
        ?>
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
      <br><br>
      <!-- view -->
<?php if($action== "view"){?>
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
                <th>S.No.</th>
                                          
                                            <th>Faq Question</th>
                                            <th>Faq Answer</th>
                      <th>Action</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                            <?php
                $i=0;
                if($users){
                foreach($users as $row)
                 {    
                  $i++;
                ?>
                <tr>
                <td><?php echo $i; ?> </td>
                <td><?php echo $row->faq_question; ?> </td>
                <td><?php echo $row->faq_answer; ?> </td>
                <?php
                echo "<td>";
                
        $value=array('class'=>'edit');
        echo anchor('admin/faq/edit/'.$row->id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';
        
        $value=array('class'=>'delete','onclick'=>'return delcon();');
        echo anchor('admin/faq/delete/'.$row->id,'<i class="fa fa-trash-o" title="delete"></i>',$value);
        
                    
        // $value=array('class'=>'edit','onclick'=>'');
        // echo anchor('admin/manage_user/status/'.$row->id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
      //  echo "Change Status";
        
        
                echo "</td>";
                echo "</tr>";
                }
              }else{
                ?>
                                <tr><td colspan="5">No records found</td></tr>
                <?php
                  }
                ?>
                                    </tbody>
        </table>
        <?php
      }
      ?>
    <?php if($action== "edit"){?>
     <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-1">
                </div>
                <div class="col-lg-6">
                <?php $attributes=array('class'=>'form form-horizontal','id'=>'addteam1');
                                  echo form_open_multipart('admin/faq/update/'.$id,$attributes); ?>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Faq Question</label>
                                            <textarea class="form-control" required name="title"  ><?php if(isset($faq_question)){echo $faq_question; }?></textarea>
 
                      <span class="error"><?php echo form_error('title');?></span>
                      <!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Faq Answer</label>
                                            <textarea class="form-control" id="faq_content" required name="content"  ><?php if(isset($faq_answer)){
                      echo $faq_answer; }
                      ?></textarea>
                      <span class="error"><?php echo form_error('content');?></span>
                                            </div>
                      <button type="submit" class="btn btn-success">Save Changes</button>
                                        <button type="reset" class="btn btn-danger" type="reset">Reset</button>
                                    <?php echo form_close(); ?>
                                    <br>
                                    <br>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
        <!-- /.panel-body -->
      <?php
      }
      ?>
       <?php if($action== "add"){?>
              <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-1">
                </div>
                <div class="col-lg-6">
                <?php $attributes=array('class'=>'form form-horizontal','id'=>'addteam');
                                  echo form_open_multipart('admin/faq/add',$attributes); ?>
                                  
                  <span class="error"><?php echo validation_errors();?></span>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Faq Question</label>
                                            <textarea class="form-control" required name="title"  ><?php if(isset($faq_question)){echo $faq_question; }?></textarea>
                      
                                        </div>
                                        <div class="form-group">
                                            <label><font color="#CC0000">*</font>Faq Answer</label>
                                            <textarea class="form-control" id="faq_content" required name="content"  ><?php if(isset($faq_answer)){
                      echo $faq_answer; }
                      ?></textarea>
                      </div>
                    
                    
                     <button type="submit" name="add_service" class="btn btn-success" value="add_service">ADD FAQ</button>
                                        <button type="reset" class="btn btn-danger" type="reset">Reset</button>
                                    <?php echo form_close(); ?>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                               
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
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
  $("#addteam").validationEngine();
  $("#addteam1").validationEngine();
  });
</script>
     
<!-- jQuery Version 1.11.0 -->
 
 <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
CKEDITOR.replace('faq_content' ,
{    
                      
filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=userfiles/',
filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script> 
