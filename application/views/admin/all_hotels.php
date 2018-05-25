
<?php $this->load->view('admin/header'); ?>

<div class="breadcrumbs">
  <div class="row-fluid clearfix"> <i class="fa fa-home"></i> All Hotels </div>
</div>

<div class="manage hacker-list" id="hacker-list">
			<div class="row-fluid clearfix">
				<div class="col-md-12">
					<ul class="page-breadcrumb breadcrumb">
					<li>
					<form class="form-inline">
					
						<div class="form-group">
						<label class="sr-only" for="exampleInputEmail3">Name / Address</label>
						<input type="text" class="form-control search" id="exampleInputEmail3" placeholder="Name / Address">
						</div>
						
						<div class="form-group">
						<label class="sr-only" for="exampleInputPassword3">All White Labels</label>
						<select class="form-control">
						<option>All White Labels</option>
						</select>
						</div>
						
					</form>
						</li>
					</ul>
				</div>
			</div>
   <?php $error = $this->session->flashdata('error'); 
    if($error!="") {
      echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
    }
    $success = $this->session->flashdata('success');
    if($success!="") {
      echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
    } ?>
   
  <div class="row-fluid clearfix">
  
 <div class="row-fluid clearfix">
   <div class="col-md-12 col-sm-12">
<nav>
  <ul class="pagination pull-right">
    <?php echo $this->pagination->create_links(); ?>
	
  </ul>
</nav>
</div>
</div>
    
	<ul class="list list-inline">
  
<?php 
	// $hotels = get_data(HOTEL)->result_array();

	$i=0;
	if($hotels){
	foreach($hotels as $aa)
	{
		extract($aa);
		if($i%7==0)
		{
?>
	<?php } ?>
	<li class="col-md-4">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="panel-group" id="accordion2">
						
					<div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
						 
							<a href="<?php echo lang_url(); ?>admin/hotel_details/<?php echo insep_encode($hotel_id); ?>" class="name">
							<?php echo $property_name;?>
							<p class="born" value="<?php echo $address; ?>"></p>
							</a>
                            <a href="<?php echo lang_url();?>admin/all_hotels_delete/<?php echo insep_encode($hotel_id); ?>"> <i class="fa fa-trash-o text-danger pull-right m_mar_23" title="Delete Hotel"> </i> </a>
						  </h4>
                          
						</div>
						
						<div id="collapsesix_<?php echo $i;?>"  class="panel-collapse collapse">
						  <div class="panel-body">
						  <h4 class="text-info">Conected Hotels</h4>
						  <h3></h3>
						  </div>
						</div>
							
					</div>

				<?php $i++;  if($i%7==0){ ?>

				</div>
			</div>
		</div>
	</li>
<?php } else { if($i==count($hotels)) { ?> 
	</ul>
</div>

</div>
</div>
</div>
	<?php } } } }else{
		echo 'No Hotels found';
	} ?>
</div> 
</div>
<?php $this->load->view('admin/footer'); ?>

<script>
$().ready(function()
{
	var options = { valueNames: [ 'name', 'born' ] };
	
	var hackerList = new List('hacker-list', options);
});

$(document).on('click','.fa-trash-o',function(){
	if(confirm('Are u sure want do delete the record?'))
	{
		//alert($(this).attr('custom'));
		//$('#tr_'+$(this).attr('custom')).empty();
		return true;
	}
	else
	{
		return false;
	}
});
</script>

</body>
</html>