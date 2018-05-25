<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">
</div>
<div class="new-left-menu">
<div class="nav-side-menu">
<div class="menu-list">
<div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
            <?php $this->load->view('channel/property_sidebar');?>
</div></div></div></div>            
</div>
</div>
</div>
</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">
<?php $error = $this->session->flashdata('error'); 
		if($error!="") {
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>
<div class="bg-neww">
<div class="pull-right">
<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
 <button type="button"  data-toggle="modal" data-target="#addtax" data-keyboard="false" data-backdrop="static" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add tax categories </button>
<?php } ?>
 </div>
<div class="tab-content">
<div class="tab-pane active" id="tab_default_1"><!-- tab1 -->
<div class="pa-n nn2"><h4><a href="<?php echo lang_url();?>inventory/inventory_dashboard">My Property</a>   <i class="fa fa-angle-right"></i>  Tax categories
 </h4> </div>

<div class="box-m">
<div class="row">
<div class="col-md-12">
<br>
<div class="table-responsive">
<table class="table table-bordered">

<?php $total_taxcategory = $this->reservation_model->total_taxcategory(); ?>

<tr class="info">

<td>  Tax category  </td>

<td> Tax rate (%)  </td>

<td>Included in price  </td>

</tr>

<?php if($total_taxcategory!=''){
      $total_taxcategoryresults	= $this->reservation_model->total_taxcategoryresults();
	  if($total_taxcategoryresults){ 
	  $taxcat=0;
	  foreach($total_taxcategoryresults as $categories){ $taxcat++;
?>

<tr id="remove_<?php echo $categories->tax_id;?>">

<td><a href="javascript:;" onclick="return edit_taxcat('<?php echo $categories->tax_id ;?>');"> <?php echo $categories->user_name; ?> </a> </td>

<td> <?php echo $categories->tax_rate; ?> </td>

<td> <a href="javascript:;" onclick="return edit_taxcat('<?php echo $categories->tax_id ;?>');"> <?php if($categories->included_price=='0') { echo 'No';} else { echo $categories->included_price; } ?> </a>  </td>

</tr>

<!-- edit tax category -->

<?php } } } else{ ?>
<tr>
<td colspan="3">
<div class="alert alert-danger text-center"><strong>No Tax Categories Found.</strong></div>
</td>
</tr>
<?php }?>
</table>
</div>
</div>

</div>

</div>   
            
</div>

</div>
              
</div>
              
             
</div>

</div>

</div>

<!-- add categories -->

<div class="modal fade" id="addtax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	<form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/add_taxcategory" method="post" id="add_taxcategory">
      <div class="modal-header">
	  
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		
        <h4 class="modal-title" id="myModalLabel">Add tax category </h4>
		
      </div>
	  
      <div class="modal-body">
		
        <div class="clearfix"></div>
        
        
        <div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label">Name <span class="errors">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="inputEmail3" name="user_name" required>
				</div>
        </div>
        <div class="clearfix"></div>
        
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label"> Included in price </label>
				<div class="col-sm-1" align="left">
					<input type="checkbox" class="form-control" id="" value="yes" name="included_price">
				</div>
		</div>
        <div class="clearfix"></div>
        
		<div class="form-group">
		<label for="inputPassword3" class="col-sm-4 control-label"> Tax Rate <span class="errors">*</span></label>
			<div class="col-sm-8">
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">%</span>
					<input type="text" class="form-control" name="tax_rate" required>
				</div>
			</div>
		</div>
        <div class="clearfix"></div>
     
        
      </div>
      
        <div class="modal-footer">
		
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="save" value="add">Save changes</button>
		
      </div>
      </form>
    </div>
  </div>
</div>


<!-- end add -->

<!-- end edit -->

<div class="modal fade" id="edit_tax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	
  <div class="modal-dialog" role="document">
  
    <div class="modal-content">
	
		<form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/edit_taxcategory" method="post" id="edit_taxcategory">
		
		
		
		  <div class="modal-header">
		  
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			
			<h4 class="modal-title" id="myModalLabel">Edit tax category </h4>
			
		  </div>
		  
      <div class="modal-body" id="tax_cat">
		
      </div>
	  
      </form>
	  
    </div>
  </div>
</div>

<!-- end edit -->

<script>
 function taxcategories(id){
	  var del_tax = $('#deltax_'+id).val();
		// alert(del_tax);
	 if(confirm('Are u sure want to delete the taxcategories?'))
	 {
	 $.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/delete_taxcategories",
		data:{"del_tax":del_tax},
		success:function(msg){
			$('#delete_tax').html('msg');
			$('#remove_'+id).remove();
			$('#edit_tax').modal('hide');
		}
	 });
	 return false;
	 }
	 else
	 {
		 return false;
	 }
 }
 </script>

 <script>
 function edit_taxcat(id){
	 $("#heading_loader").fadeIn("slow");
	 $.ajax({
		type:"POST",
		url:"<?php echo lang_url(); ?>reservation/gettax",
		data:"tax_id="+id,
		success:function(msg){
			$('#tax_cat').html(msg);
			$('#edit_tax').modal({backdrop: 'static',keyboard: false});
			$("#heading_loader").fadeOut("slow");
		}
	});
	return false;
 }
 </script>

</body>

</html>


