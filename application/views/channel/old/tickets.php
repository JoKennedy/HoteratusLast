  
<div class="contents">


<div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>
      Manage Tickets
    </h4>
  </div>
  
  

  <div class="verify_det clearfix">      
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{  ?>

<!-- <a class="cls_com_blubtn pull-right hvr-sweep-to-right add_property"  href="javascript:;">Add New <i class="fa fa-plus"> </i></a> -->

<a class="cls_com_blubtn pull-right hvr-sweep-to-right" data-toggle="modal" data-target="#myModal_support" href="javascript:;" data-backdrop="static" data-keyboard="false">Add New <i class="fa fa-plus"> </i></a>

<?php } ?>
</div>

  <?php $error = $this->session->flashdata('profile_error'); 
    if($error!="") {
      echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
    }
    $success = $this->session->flashdata('profile');
    if($success!="") {
      echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
    } ?>
  
  <div class="clk_history dep_his pay_tab_clr">
<div class="table table-responsive">


<table class="table table-bordered" id="tableData1">
<thead>
<tr class="info">
<th>#</th>
<!--<th>Room type</th>-->
<th>Subject</th>
<th>Description</th>
<th>Created</th>
<th>Status</th>
<th>Action</th>

</tr>
</thead>
<?php if(count($ticket_support)!=0) { 
$ii=1;
/*echo '<pre>';
print_r($active);*/
foreach($ticket_support as $acc) {
  extract($acc);?>
<tr>
<td><?php echo $ii;?></td>

<td><?php echo '<span class="label label-sm label-info">'.ucfirst($subject).'</span>';?></td>
<td><?php  echo $description;?></td>
<td>
<?php echo $created;     
          
        
            ?>
            </td>
<td>

  <?php if($status=='0') { ?> <span class="label label-sm label-warning"> Pending </span>
            <?php }elseif($status=='1') { ?> <span class="label label-sm label-success"> Resolved </span>
            <?php }elseif($status=='2') { ?> <span class="label label-sm label-warning"> Escalated </span>
                        <?php } ?>
</td>

 <td class="center "> <a href="<?php echo base_url(); ?>channel/view_support/<?php echo insep_encode($id); ?>"><i title="View" class="fa fa-eye"></i></a> &nbsp; &nbsp; <a href="<?php echo base_url(); ?>channel/delete_support/<?php echo insep_encode($id);?>" ><i title="Delete" class="fa fa-trash-o"></i></a></td>

</tr>
<?php  $ii++;} } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No active Ticket data found...</td>
</tr>
<?php } ?>
</table>
</div>
</div>
</div>
  
</div>
               
  <?php $this->load->view('channel/dash_sidebar'); ?>

  




<div class="modal fade dialog-2" id="myModal_support" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add Ticket</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="">
	 <center>
      <form action="<?php echo lang_url(); ?>channel/add_supporticket" role="form" class="form-horizontal" name="ticket_form" id="ticket_form" method="post"  novalidate="novalidate" >
		
		<div class="col-md-12">
		
		<div class="form-group" id="select_subject">

		<p for="inputEmail3" class="col-sm-4 control-label">

		Type <span class="errors">*</span></p>

		<div class="col-sm-7">

		<select id="subject" name="subject" onchange="get_optionval(this.value)" class="form-control">    
		<option value="support">Support</option>
		<option value="channelmanager">Channel Manager</option>
		<option value="bookingengine">Booking Engine</option>
		<option value="sales">Sales</option>
		<option value="billing">Billing</option>
		<option value="multiproperty">Multi property</option>
		<option value="password">Password</option>
		<option value="Other">Other</option>
		</select>

		</div>

		</div>
		
		<div class="form-group" style="display: none" id="other_subject">

		<p for="inputEmail3" class="col-sm-4 control-label">

		Subject <span class="errors">*</span></p>

		<div class="col-sm-7">

		<input type="text" name="subject_txt"  id="subject_txt" class="form-control">

		</div>

		</div>
		
		<div class="form-group">

		<p for="inputEmail3" class="col-sm-4 control-label">

		Description <span class="errors">*</span></p>

		<div class="col-sm-7">

		<textarea id="description" required name="description" class="form-control"></textarea>

		</div>

		</div>
		
		</div>
       
		<div class="form-group">
		<div class="col-sm-offset-2 col-sm-8">
		<!-- <button class="btn btn-success hover-shadow pull-right" type="submit" name"ticket_submit" id="ticket_submit">Search</button>  -->
		<input type="submit" class="cls_com_blubtn" name="save" id="ticket_submit" value="Submit">     
		</div>
		</div>  
     </form>
	 </center>
     </div>
 
  
  
  
   
   
   
   
</div>
</div>
</div>
</div>



<!-- add ticket section modal end -->


<!--  ticket section ends here   -->