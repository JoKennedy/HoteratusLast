<?php $this->load->view('admin/common/header'); ?>
<body>

<?php $this->load->view('admin/common/menu'); ?> 
 <div class="col-lg-12">
 	<?php 	  
									if(isset($error))	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?> 		
									<?php 
									 $success=$this->session->flashdata('success');									
										if($success)	{	?> 
										<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Success! </strong> <?php echo $success;?>.
									</div><?php }?>  
									<?php  $error=$this->session->flashdata('error');										
										if($error)	{	?> 
									 <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Oh! </strong><?php echo $error;?>.
									</div>
									<?php }?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Transactions Panel</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                       <?php
							if($temp)
							{
							foreach($temp as $trow)
							{
							?>
								<div class="row">
                    <div class="col-lg-4 text-center">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                .col-lg-4
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                .col-lg-4
                            </div>
                        </div>
                    </div>
                 
                </div>
								<?php
								}
								}
								else
								{
								}
								?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

				<?php $this->load->view('admin/common/script'); ?>