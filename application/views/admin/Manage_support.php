<?php $this->load->view('admin/common/header'); ?>
<body>
<?php $this->load->view('admin/common/menu'); ?>
	     <div id="page-wrapper">
         <div class="container-fluid">
            <div class="row">
               
                <!-- /.col-lg-6 -->
                <div class="col-lg-12">
                <h1 class="page-header">
 Manage Support

</h1>
				<?php 	  
									if(isset($error))	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?> 		
									<?php 
									 $success=$this->session->flashdata('success');									
										if($success)	{	?> 
										<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Success! </strong> <?php echo $success;?>.
									</div><?php }?>  
									<?php  $error=$this->session->flashdata('error');										
										if($error)	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                         Support 
                        </div>
						<?php
						static $flag=0;
						?>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
							<?php echo form_open('admin/admin_setting_updated'); ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
											<th>S.No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
											<th>Subject</th>
                                            <!--<th>Status</th>-->   
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										if($records)
										{
											$i=0;
							  foreach($records as $row)
							   {			
									$subject=$row->subject;
								echo "<tr>";
								echo "<td>";
								echo $i=$i+1;
								echo "</td>";
								
								echo "<td>";
								echo $row->name;
								echo "</td>";								
								echo "<td>";
								echo $row->emailid;
								echo "</td>";	
								echo "<td width='30%'>". anchor('admin/message_detail/'.$row->support_id,$subject,$attr=array('title'=>"View details"))."</td>";
								//echo "<td>";
								//echo $row->status;
								//echo "</td><td>";
								echo "<td>";
								$l=" ";	
								$value=array('class'=>'edit');
								echo anchor('admin/message_detail/'.$row->support_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';		
                               $value=array('class'=>'delete','onclick'=>'return delcon();');
								echo anchor('admin/delete_support/'.$row->support_id,'<i class="fa fa-times" title="delete"></i>',$value);								
								//echo "<td>";
								echo "</td></tr>";
								}
								}
								else
								{
								echo "<tr>";
								echo "<td>";
								echo "No result found.";
								echo "</td>";
								echo "</tr>";
								}
								?>
                                    </tbody>
                                </table>
								
                            </div>
                            <!-- /.table-responsive -->							
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
              
						
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
           </div>  
           </div>
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

	   
<!-- jQuery Version 1.11.0 -->
   