
<!-- <div class="col-md-11 col-sm-11 col-xs-12 cls_cmpilrigh"> -->
<div class="content">
  <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Tax Categories</h4>
  </div>  
  

    <div class="verify_det clearfix">      
<?php  if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1'){ ?>
 <!-- <button type="button" data-toggle="modal" data-target="#addtax" data-keyboard="false"  data-backdrop="static" class="btn btn-success "> -->
 <a href="#addtax" class="cls_com_blubtn pull-right waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000">
 <i class="fa fa-plus-circle"></i> Add tax categories </a>

<?php } ?>

  </div>  

  <?php $error = $this->session->flashdata('error'); 
		if($error!="") {
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>

		
  <div class="clk_history dep_his pay_tab_clr">
         
        
       <div class="table-responsive">
     


 
      <table class="table table-bordered table-hover"> 
      <?php $total_taxcategory = $this->reservation_model->total_taxcategory(); ?>
		<thead> 
		<tr>

		<th width="40%">  Tax category   </th>
		<th width="30%">  Tax rate (%)  </th>
		<th width="30%"> Included in price    </th>			
		</tr>
		</thead>
		  <tbody>

		<?php if($total_taxcategory!=''){
		      $total_taxcategoryresults	= $this->reservation_model->total_taxcategoryresults();
			  if($total_taxcategoryresults){ 
			  $taxcat=0;
			  foreach($total_taxcategoryresults as $categories){ $taxcat++;
		?>

		<tr id="remove_<?php echo $categories->tax_id;?>">

		<td><a href="javascript:;" onclick="return edit_taxcat('<?php echo $categories->tax_id ;?>');"> <?php echo $categories->user_name; ?> </a> </td>

		<td> <?php echo $categories->tax_rate; ?> </td>

		<td> 
		 	<a href="javascript:;" onclick="return edit_taxcat('<?php echo $categories->tax_id ;?>');"> <?php if($categories->included_price=='0') { echo 'No';} else { echo $categories->included_price; } ?> </a>

			<li class="display_none">
			<a href="#edit_tax" class="btn waves-effect waves-light m-r-5 m-b-10 edit_tax" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000"></a></li>
		  </td>

		</tr>

<!-- edit tax category -->

<?php } } } else{ ?>
<tr>
<td colspan="3">
<div class="alert alert-danger text-center"><strong>No Tax Categories Found.</strong></div>
</td>
</tr>
<?php }?>
</tbody>
 </table>
      </div>
      </div>
    <div class="clearfix"></div>
   
    
</div>
  
<?php $this->load->view('channel/dash_sidebar'); ?>

<!-- add categories -->

<div id="addtax" class="modal-demo modal-dialog modal_list modal-content">
  <button type="button" class="close login_close" onclick="Custombox.close();">
  <span>&times;</span><span class="sr-only">Close</span>
  </button>
  <h4 class="custom-modal-title">
  <?php echo get_data(CONFIG)->row()->company_name;?> Add Tax Categories</h4>
  <hr>
  <div class="custom-modal-text">
    <form class="form-horizontal" action="<?php echo lang_url(); ?>reservation/add_taxcategory" method="post" id="add_taxcategory">
    <div class="login_content">

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
<button onclick="Custombox.close();" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="save" value="add">Save changes</button>
    </div>    
    </form>
  </div>
</div>


<div id="edit_tax" class="modal-demo modal-dialog modal_list modal-content">
	<button type="button" class="close reg_close" onclick="Custombox.close();">
	<span>&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="custom-modal-title"><?php echo get_data(CONFIG)->row()->company_name;?></h4>
	<hr>
	<div class="custom-modal-text">
		<form class="form-horizontal register-form" action="<?php echo lang_url(); ?>reservation/edit_taxcategory" method="post" id="edit_taxcategory" style="display: block;">		
		<div class="reg_content" id="tax_cat">
		</div>
		</form>
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
			$('.edit_tax').trigger('click');
			/*$('#edit_tax').modal({backdrop: 'static',keyboard: false});*/
			$("#heading_loader").fadeOut("slow");
		}
	});
	return false;
 }
 </script>

</body>

</html>


