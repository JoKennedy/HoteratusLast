<div class="dash-b4">
	<div class="row-fluid clearfix">
		<div class="col-md-12 col-sm-12">
			<div class="pa-n">
				<h4><a href="javascript:;">Calendar</a><i class="fa fa-angle-right"></i> Advanced Updates </h4>
			</div>
		</div>
	</div>
</div> 

<?php
if( $this->session->flashdata('bulk_success')!='' )
{
?>
	<div class="alert alert-success">
		<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
		<?php echo $this->session->flashdata('bulk_success');?>
	</div>
<?php
}
?>
<div class="change_month_replace">
	<?php require "calendar.php"; ?>
</div>
