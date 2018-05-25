<?php $this->load->view('admin/common/header'); ?>
<body>

<?php $this->load->view('admin/common/menu'); ?>
   
	   
	     <div id="page-wrapper">
            
            <!-- /.row -->
           
            <div class="row">
               
                <!-- /.col-lg-6 -->
                <div class="col-lg-50">
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
                           Manage Template
                        </div>
						<?php
						static $flag=0;
						?>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
							<div class="row">
							<?php
							if($temp)
							{
							foreach($temp as $trow)
							{
							?>
								<div class="col-md-4 col-sm-6 portfolio-item">
									<a data-toggle="modal" class="portfolio-link" href="#portfolioModal1">
										<div class="portfolio-hover">
											<div class="portfolio-hover-content">
												<i class="fa fa-plus fa-3x"></i>                            </div>
										</div>
									<img alt="" class="img-responsive" src="<?php echo lang_url(); ?>img/portfolio/roundicons.png">                    </a>
									<div class="portfolio-caption">
										<h4><?php echo $trow->template_title; ?></h4>
										<p class="text-muted"><?php echo $trow->template_content; ?></p>
									</div>
								</div>
								<?php
								}
								}
								else
								{
								}
								?>
							</div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
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

	   
<!-- jQuery Version 1.11.0 -->
   <?php $this->load->view('admin/common/script'); ?>