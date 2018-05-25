<?php $this->load->view('admin/header');?>

<div class="breadcrumbs">
<div class="row-fluid clearfix">
<i class="fa fa-home"></i> Manage Admin
</div>
</div>
<div class="manage">


<div class="row-fluid clearfix">
        <div class="col-md-12">
        <!--  <h3 class="page-title"> Dashboard </h3> -->
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="fa fa-home"></i>
              <a href="<?php echo lang_url(); ?>admin/dashboard">
                Home
              </a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li>
              <a href="<?php echo lang_url(); ?>admin/manage_admin_details">
                Manage Admin
              </a>
            </li>
            
          </ul>
        </div>
      </div>


<div id="page-wrapper">

<div class="container-fluid">
<div class="row">

 <div class="col-lg-12">
 <?php 
$success=$this->session->flashdata('success');									
if($success)
{
?> 
<div class="alert alert-success">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>Success! </strong> <?php echo $success;?>.
</div>
<?php 
}
?>  
<?php  
$error=$this->session->flashdata('error');										
if($error)	
{	
?> 
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>Oh! </strong><?php echo $error;?>.
</div>
<?php 
}
?>


                        <div class="panel panel-default">
                            <div class="panel-heading"><a href="<?php echo lang_url(); ?>admin/add_admin/"><button type="button" style="width: 157px; height: 33px; margin-top: -8px; float:left;" class="pull-right label label-success">Add New <i class="fa fa-plus"></i></button></a>
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Manage Admin</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                              <th>S.No.</th>
                                            <th>Admin</th>
                                            <th>Password</th>
                                            <th>Admin Roles</th>   
											<th>Country</th>
											<th>City</th>                                         
											<th>Status</th>
											<th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
											$i=0;
							  foreach($records as $row)
							   {
									//$username=$row->name;
								echo "<tr>";
								echo "<td>";
								echo $i=$i+1;
								echo "</td>";
								echo "<td>";
								?>
								<img src="<?php echo base_url();?>uploads/<?php echo $row->admin_image;?>" width="50" height="50" style="float:left"/>
								<?php
								echo $row->login_name;
								echo "<br/>";
								echo $row->email_id;
								echo "</td>";
								echo "<td>";
								echo $row->password;
								echo "</td>";
								echo "<td>";
								if(($row->admin_controlid)==1){echo "Admin";}
								else if(($row->admin_controlid)==2){echo "Sub-Admin";}
								else {echo "SupportAdmin";}
								echo "</td>";
								echo "<td>";
								echo $row->country;
								echo "</td>";
								echo "<td>";
								echo $row->city;
								echo "</td>";
								echo "<td>";
								if($row->status=='Active') { echo '<span class="label label-sm label-success">Active</span>' ;} 
								else { 
								echo '<span class="label label-sm label-danger">Inactive</span>'; }
								echo "</td>";
								echo "<td>";
								 
				$value=array('class'=>'edit');
				$pass_id=$this->admin_model->encryptIt($row->id);
				echo anchor('admin/edit_admin/'.$pass_id,'<i class="fa fa-pencil" title="edit"></i>',$value).'&nbsp;';
				
				$value=array('class'=>'delete','onclick'=>'return delcon();');
				echo anchor('admin/deleteadmin/'.$pass_id,'<i class="fa fa-times" title="delete"></i>',$value).'&nbsp;';
				
				            
				$value=array('class'=>'edit');
				echo anchor('admin/status_change/'.$pass_id,'<i class="fa fa-gear fa-fw" title="Change Status"></i> ',$value);
			//	echo "Change Status";
				
				
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