<?php $this->load->view('admin/header'); ?>
<div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Admin Controls
  </div>
  </div>

 <div id="page-wrapper">
 <div class="container-fluid">
  <!-- <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Admin Controls</h1>
                </div>
                
            </div>  -->
    <div class="row">
               
<!-- /.col-lg-6 -->
<div class="col-lg-12">

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

      <br><br>
      <!-- view -->
	  <?php echo form_open('admin/admin_setting_updated'); ?>
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
                <th>Role Name</th>
            <th>Admin Roles</th>
            <th>Manage Admin</th>
            <th>Manage Users</th>
			<th>Manage Email Template</th>
			<th>Manage Channel</th>	
			<th>Manage Property</th>											
			<th>Manage Reservation</th>											
			<th>Manage CMS Page</th>
			<th>Support</th>
                          </tr>
                                        </thead>
                                        <tbody>
                                           <?php
$i=0;
foreach($records as $row)
{
if($row->admin_controlid>1)
{
echo "<tr>";
	echo "<td>".$row->role_name."</td>";
$ctrlid=$row->admin_controlid;
?>
<td>
	<div class="radio text-center">
		<input type="radio" name="admin_roles<?php echo $ctrlid; ?>" id="yesadmin_roles<?php echo $ctrlid; ?>" value="yes" <?php if($row->admin_roles=='yes'){ ?> checked="checked" <?php }?>>yes<br/>
		<input type="radio" name="admin_roles<?php echo $ctrlid; ?>" id="noadmin_roles<?php echo $ctrlid; ?>" value="no" <?php if($row->admin_roles=='no'){ ?> checked="checked"<?php }?>>no
	</div>
</td>

<td>
	<div class="radio text-center">
		<input type="radio" name="manage_admin<?php echo $ctrlid; ?>" id="yesmanage_admin<?php echo $ctrlid; ?>" value="yes"<?php if(($row->manage_admin)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
		<input type="radio" name="manage_admin<?php echo $ctrlid; ?>" id="nomanage_admin<?php echo $ctrlid; ?>" value="no" <?php if(($row->manage_admin)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>
<td>
	<div class="radio text-center">
		<input type="radio" name="manage_users<?php echo $ctrlid;?>" id="yesmanage_users<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_users)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
		<input type="radio" name="manage_users<?php echo $ctrlid;?>" id="nomanage_users<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_users)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>

<td>
	<div class="radio text-center">
		<input type="radio" name="manage_emailtemplate<?php echo $ctrlid;?>" id="yesmanage_emailtemplate<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_emailtemplate)=='yes'){ ?>checked="checked"<?php }?>>yes<br/><input type="radio" name="manage_emailtemplate<?php echo $ctrlid;?>" id="nomanage_emailtemplate<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_emailtemplate)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>
<td>
	<div class="radio text-center">
		<input type="radio" name="manage_channel<?php echo $ctrlid;?>" id="yesmanage_channel<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_channel)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
		<input type="radio" name="manage_channel<?php echo $ctrlid;?>" id="nomanage_channel<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_channel)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>
<td>
	<div class="radio text-center">
		<input type="radio" name="manage_property<?php echo $ctrlid;?>" id="yesmanage_property<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_property)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
		<input type="radio" name="manage_property<?php echo $ctrlid;?>" id="nomanage_property<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_property)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>

<td>
	<div class="radio text-center">
		<input type="radio" name="manage_reservation<?php echo $ctrlid;?>" id="yesmanage_reservation<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_reservation)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
		<input type="radio" name="manage_reservation<?php echo $ctrlid;?>" id="nomanage_reservation<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_reservation)=='no'){ ?>checked="checked"<?php }?>>no
	</div>
</td>


<td>
<div class="radio text-center">
	<input type="radio" name="manage_cms_page<?php echo $ctrlid;?>" id="yesmanage_cms_page<?php echo $ctrlid;?>" value="yes" <?php if(($row->manage_cms_page)=='yes'){ ?>checked="checked"<?php }?>>yes<br/>
	<input type="radio" name="manage_cms_page<?php echo $ctrlid;?>" id="nomanage_cms_page<?php echo $ctrlid;?>" value="no" <?php if(($row->manage_cms_page)=='no'){ ?>checked="checked"<?php } ?>>no
</div>
</td>

<td>
	<div class="radio text-center">
		<input type="radio" name="support<?php echo $ctrlid;?>" id="yessupport<?php echo $ctrlid;?>" value="yes" <?php if($row->support=='yes'){ ?> checked="checked" <?php }?>>yes<br/>
		<input type="radio" name="support<?php echo $ctrlid;?>" id="nosupport<?php echo $ctrlid;?>" value="no" <?php if($row->support=='no'){ ?> checked="checked"<?php }?>>no
	</div>
</td>
<?php
echo "</tr>";
}
}
?>
                                        </tbody>
        </table>
       <button type="submit" class="btn btn-success" style="margin: 20px 462px;">Save Changes</button>
	<?php echo form_close(); ?>
   
     
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
   <!-- /.panel -->

<!-- /.col-lg-6 -->
</div>
<!-- /.row -->
</div>  
</div>
</div>
</body>
