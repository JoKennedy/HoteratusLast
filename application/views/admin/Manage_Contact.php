<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-phone-square"></i> Manage Contact
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
      <h4>Contact</h4>
      <br><br>
      <!-- view -->

      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
                <th width="4%">S.No</th>
                <th >Name</th>
                <th >Email</th>
				<th >Date & Time </th>
                <th >Status</th>
                <th >Action</th>
              </tr>
            </thead>
           <tbody>
                                        <?php
                    if($records)
                    {
                      $i=0;
                foreach($records as $row)
                 {      
                  //$subject=$row->subject;
                echo "<tr>";
                echo "<td>";
                echo $i=$i+1;
                echo "</td>";
                
                echo "<td>";
                echo $row->name;
                echo "</td>";               
                echo "<td>";
                echo $row->email;
                echo "</td>"; 
				echo "<td>";
                echo $row->current_date_time;
                echo "</td>"; 
                //echo "<td width='30%'>". anchor('admin/message_detail/'.$row->support_id,$subject,$attr=array('title'=>"View details"))."</td>";
                echo "<td>";
                if($row->replay_status==1)
                {
                  echo '<span class="btn-success"> Reply </span></td>';
                }
                else
                {
                  echo '<span class="btn-danger"> Unreply </span></td>';
                }
                //  echo "</td><td>";
                echo "<td>";
                $l=" "; 
                $value=array('class'=>'edit');
                echo anchor('admin/contact_detail/'.$row->contact_id ,'<i class="fa fa-eye" title="View/Reply"></i>',$value).'&nbsp;';    
                               $value=array('class'=>'delete','onclick'=>'return delcon();');
                echo anchor('admin/delete_contact/'.$row->contact_id ,'<i class="fa fa-times" title="delete"></i>',$value);               
                //echo "<td>";
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
</script>