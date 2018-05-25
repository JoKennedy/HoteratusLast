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
