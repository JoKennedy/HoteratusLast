<?php $this->load->view('admin/header'); ?>

<div class="breadcrumbs">
  <div class="row-fluid clearfix"> <i class="fa fa-home"></i> All Users </div>
</div>

<div class="manage hacker-list" id="hacker-list">
		<div class="row-fluid clearfix">
			<div class="row-fluid clearfix">
				<div class="col-md-12">
					<ul class="page-breadcrumb breadcrumb">
					<li>
				<form class="form-inline">
				
					<div class="form-group">
					<label class="sr-only" for="exampleInputEmail3">Name / Email</label>
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
		</div>
   
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
	$i=0;
	if($users){
	foreach($users as $aa)
	{
		/* echo '<pre>';
		print_r($users);die; */
		// extract($aa);
		if($i%5==0)
		{
	
  ?>

		<?php } ?>
		
<li class="col-md-3">
<div class="panel-group" id="accordion1">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle name" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $i; ?>">
			<?php echo $aa->user_name; ?>
			<i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
		<p class="born" value="<?php echo $aa->email_address; ?>"></p>
		
        </a>
      </h4>
    </div>
    <div id="collapseOne_<?php echo $i; ?>" class="panel-collapse collapse">
      <div class="panel-body">
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>First Name</strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php  if(isset($aa->fname)){echo ucfirst($aa->fname.'  '.$aa->lname);}?>
	   </div>
	   </h3>
	   <div class="clearfix">
	   </div>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Town </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php echo $aa->town; ?>
	   </div>
	   </h3>
	   <div class="clearfix">
	   </div>
	   <?php $country_name = $this->admin_model->country_name($aa->country); ?>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Country </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php if(isset($country_name->country_name)){echo $country_name->country_name; } ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Address </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php echo $aa->address; ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>ZIP Code </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php echo $aa->zip_code; ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Mobile </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php echo $aa->mobile; ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Email Address</strong> 
	   </div>
	   <div class="col-sm-7">
	   : <?php echo $aa->email_address; ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
	   <?php $currency = $this->admin_model->currency($aa->currency); ?>
       <h3 class="row">
	   <div class="col-sm-5">
	   <strong>Currency </strong>
	   </div>
	   <div class="col-sm-7">
	   : <?php if(isset($currency->currency_name)) echo $currency->currency_name; ?>
	   </div>
	   </h3>
	    <div class="clearfix">
	   </div>
        
      </div>
    </div>
</div>

</div>


</li>

<?php $i++;  if($i%5==0){ ?>





<?php } else { if($i==count($users)) {	 ?> 

<?php } } } }else{
		
		echo 'No Users found';
	} ?>
	
	
	 
	</ul>
</div>

</div> 

   
</div>

<?php $this->load->view('admin/footer'); ?>

<script>
$().ready(function()
{
	var options = { valueNames: [ 'name', 'born' ] };
	
	var hackerList = new List('hacker-list', options);
});
</script>


</body>
</html>
