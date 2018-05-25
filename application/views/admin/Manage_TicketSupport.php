<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-phone-square"></i> Manage Ticket
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

      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4>Manage Ticket</h4>
      <br><br>
      <!-- view -->

      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
                      <th class="center">Sl.No</th>
                      <th class="center">Subject </th>
                      <th class="center">User Name</th>
                      <th class="center">Created</th>
                      <th class="center">Status</th>
                      <th class="center">Actions</th>
              </tr>
            </thead>
           <tbody>
                                        <?php
                    if($team)
                    {
                      $i=0;
                foreach($team as $taken)
                 {      
                  extract($taken);
                  $userdetails = get_data(TBL_USERS,array('user_id'=>$user_id))->row();
                  //$subject=$row->subject;
                echo "<tr>";
                echo "<td>";
                echo $i=$i+1;
                echo "</td>";
                
                echo "<td>";
                echo $subject;
                echo "</td>";               
                echo "<td>";
                echo $userdetails->fname.' '.$userdetails->lname;
                echo "</td>"; 
				        echo "<td>";
                echo $created;
                echo "</td>"; 
                //echo "<td width='30%'>". anchor('admin/message_detail/'.$row->support_id,$subject,$attr=array('title'=>"View details"))."</td>";
                echo "<td>";?>
               
                
        
        <div class="col-md-9">
      <select name="status" class="" id="status_form_<?php echo $id; ?>">
            <option value="0" <?php if($status=='0') { ?> selected="selected" <?php } ?>> Pending</option>
            <option value="1" <?php if($status=='1') { ?> selected="selected" <?php } ?>> Resolved</option>
            <option value="2" <?php if($status=='2') { ?> selected="selected" <?php } ?>> Escalated</option>
            </select></div>
    <div class="col-md-3" style="padding:0px;"> <button type="button" class="btn btn-success" onClick="save_status(<?php echo $id; ?>)" style="float:left;margin-left:-12px;">Save</button></div>


               
            <?php   echo "<td>";


             
            echo anchor('admin/Manage_View_Support/'.$seo_url.'','<i class="fa fa-eye" title="View Suport"></i> '); 
            
            echo anchor('admin/delete_ticket_support/'.insep_encode($id).'','<i class="fa fa-trash-o" title="Delete Support"></i> '); 
                
                echo "</td></tr>";
                }
                }
                else
                {
                /* echo "<tr>";
                echo "<td>";
                echo "No result found.";
                echo "</td>";
                echo "</tr>"; */
                }
                ?>
              </tbody>
        </table>
      </div>
     </div>
    </div>
  </div>
  


<?php $this->load->view('admin/footer');?>

  <script src="<?php echo base_url();?>admin_assets/js/jquery.dataTables.min.js"></script>




<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});

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

function save_status(id)
{  
  show_animation();
  $.post("<?php echo lang_url().'admin/Manage_Ticket_Status'; ?>", { id: id, status: $('#status_form_'+id).val()}, function(data){
    setTimeout('hide_animation()', 500);
  });
}

function show_animation()
{
  $('#saving_container').css('display', 'block');
  $('#saving').css('opacity', '.8');
}

function hide_animation()
{
  $('#saving_container').fadeOut();
}

</script>

<div id="saving_container" style="display:none;">
  <div id="saving" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
  <img id="saving_animation" src="<?php echo base_url('admin_user/storing_animation.gif');?>" alt="saving" style="z-index:100001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%"/>
  <div id="saving_text" style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">Loading</div>
</div>