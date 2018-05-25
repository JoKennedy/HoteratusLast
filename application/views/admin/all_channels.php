<?php $this->load->view('admin/header'); ?>

	<div class="breadcrumbs">
	  <div class="row-fluid clearfix"> <i class="fa fa-home"></i> All Channels </div>
	</div>
    
    

<div class="manage hacker-list" id="hacker-list">
			<div class="row-fluid clearfix">
				<div class="col-md-12">
					<ul class="page-breadcrumb breadcrumb">
						<li>
					<form class="form-inline" method="post" action="<?php echo lang_url(); ?>admin/all_channels">
						<div class="form-group">
						
						<label class="sr-only" for="exampleInputEmail3">Name</label>
						
						<input type="text" name="channel_name" class="form-control search" id="exampleInputEmail3" placeholder="Channel Name">
						
						</div>
					</form>
						</li>
					</ul>
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
	$amm = $channel;
	$i=0;
	if($amm){
	foreach($amm as $aa)
	{
		extract($aa);
		if($i%5==0)
		{
?>
<!--<div class="col-md-4">-->

<?php 	} ?>
<li class="col-md-4">
<div class="panel-group" id="accordion">

<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle name" data-toggle="collapse"   data-parent="#accordion" href="#collapsesix_<?php echo $i;?>">
    <?php echo $channel_name;?>
        <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
		</a>
      </h4>
    </div>

	<?php  
		   $hotel_name = $this->admin_model->hotel_name($channel_id);
		   if($hotel_name){
			   $name= '';
		   foreach($hotel_name as $hotel){
			   $name.=$hotel->property_name.' , ';
		   }
	  ?>
	
	  
     
    <div id="collapsesix_<?php echo $i;?>"  class="panel-collapse collapse">
      <div class="panel-body">
	  <h4 class="text-info">Connected Hotels</h4>
      <h3><?php echo ucfirst(trim($name,' , ')); ?></h3>
      <br>
      <h4 class="text-info">Channel Status</h4>
      <select class="form-control" name="channel_status" onchange="changestatus(this.value,<?php echo $channel_id; ?>)">
      	<option value="Active" <?php if($status == "Active"){ echo "selected";} ?>>Live</option>
      	<option value="New" <?php if($status == "New"){ echo "selected";} ?>>New</option>
      	<option value="Process" <?php if($status == "Process"){ echo "selected";} ?>>Under Construction</option>
      </select>
      </div>
    </div>
	<?php  }else{
			   ?>
			 <div id="collapsesix_<?php echo $i; ?>"  class="panel-collapse collapse">
      <div class="panel-body">
	  
      <h3>No Hotels Found</h3>
       <h4 class="text-info">Channel Status</h4>
      <select class="form-control" name="channel_status" onchange="changestatus(this.value,<?php echo $channel_id; ?>)">
      	<option value="Active" <?php if($status == "Active"){ echo "selected";} ?>>Live</option>
      	<option value="New" <?php if($status == "New"){ echo "selected";} ?>>New</option>
      	<option value="Process" <?php if($status == "Process"){ echo "selected";} ?>>Under Construction</option>
      </select>
        
      </div>
    </div>
		   <?php } ?>	
</div>

</div>
</li>
<?php $i++;  if($i%5==0){ ?>

<!--</div>-->
<?php } else { if($i==count($amm)) { ?> 

<!--</div>-->
	<?php } } } }else{
		echo 'No channels found';
	} ?>

</ul>



</div> 
   
</div>

<?php $this->load->view('admin/footer'); ?>
<script>
$().ready(function()
{
	var options = { valueNames: [ 'name', 'born' ] };
	
	var hackerList = new List('hacker-list', options);
});

function changestatus(status,channel_id){
	$.ajax({
		url:"<?php echo lang_url();?>admin/change_channel_status/"+status+"/"+channel_id,
		dataType:"json",
		success:function(res){
			console.log(res);
		}
	});
}
</script>

</body>
</html>
