<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Partners
  </div>
  </div>
  <div class="manage">
    <div class="row">
    
   </div>
  </div>
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
			if($action== "view_single" || $action== "list") $title ="View Partners" ;
        ?>
		
      <div class="col-md-12">
      <div class="table-responsive">
	  
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
      <br><br>
      <!-- view -->
	  <?php if($action== "list"){
		 if($users){$i=0; ?>
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
					<th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
				    <th>Website</th>
					<th>Status</th>
					<th>Action</th>
                     </tr>
               </thead>
               <tbody>
                <?php
			   $i=0;
			  foreach($users as $row)
			   {		
			   	$pass_id=$this->admin_model->encryptIt($row->partnar_id);
					$i++;
				?>
				<tr>
				<td><?php echo $i; ?> </td>
				<td><?php echo $row->firstname; ?> </td>
				<td><?php echo $row->email; ?> </a></td>
				<td><?php echo $row->website; ?> </a></td>
				<td><?php if($row->status=='Confirmed'){ echo '<span class="label label-sm label-success">Confirmed</span>'; } else { echo '<span class="label label-sm label-danger">Not Confirmed</span>'; } ?> </td>
				<?php
				echo "<td>";
				$partnar_id = $row->partnar_id;
							// $pass_id=$this->admin_model->encryptIt($row->partnar_id);
							
						
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/partners/delete/'.$partnar_id,'<i class="fa fa-times" title="delete"></i>',$value);
				
				            
				$value=array('class'=>'edit','onclick'=>'');
				echo anchor('admin/partners/status/'.$partnar_id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
			//	echo "Change Status";
				
				
								echo "</td>";
								echo "</tr>";
								}
								?>
                                    </tbody>
        </table>
        <?php
	  } }
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
 

</script>

<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});

</script>    