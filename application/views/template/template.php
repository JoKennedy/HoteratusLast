


<div class="outter-wp">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Template </li>
			</ol>
		   </div>
	  <!--//sub-heard-part-->
			
		<div style="float: right; " class="buttons-ui">
	        <a href="#TemplateList" data-toggle="modal" class="btn blue">Add New Template</a>
    	</div>
			<div  class="clearfix"></div>			 

			<div class="graph-visual tables-main">

				<div class="graph">
				
					<div class="table-responsive">
		                <div class="clearfix"></div>
		                <table style="color:black;" id="TemplateListTable" class="table table-bordered">
		                    <thead>
		                        <tr>
		                            <th>#</th>
		                            <th>Template Type</th>
		                            <th>Status</th>
		                            <th width="5%">Edit</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        <?php if (count($Templates)>0) {

		                            $i=0;
		                            foreach ($Templates as  $value) {
		                                $i++;
		                                
		                                echo' <tr  class="'.($i%2?'warning':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['Name'].'  </td> 
		                              	 <td>'.($value['Active']==1?'Active':'Deactive').'</td>  <td><a href="javascript:;" onclick ="Showtemplate('."'".insep_encode($value['TemplateTypeId'])."'".')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';
		                            }
		                           
		                        } ?>
		                    </tbody>
		                </table>
		                <?php if (count($Templates)==0) {echo '<h4>No Template Created!</h4>';} 
		                  ?>
		                <div class="clearfix"></div>
		            </div>
			
				</div>
				
			</div>
			<!--//graph-visual-->
		</div>
		<!--//outer-wp-->
		 <!--footer section start-->
		
		<!--footer section end-->
	</div>
</div>
<div id="TemplateList" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a New Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
                <div class="graph-form">
                  
                        <div class="col-md-12 form-group1 form-last">
                            <center><label style="padding:4px; font-size: 18px;" class="control-label controls">Template Type </label></center>
                            <select  style="width: 100%; padding: 9px;" name="TemplateTypeId" id="TemplateTypeId">
                                <?php
                                	if(count($TemplatesType)>0)
                                	{	

                                		echo '<option value="0" >Select a Template Type</option>';
                                		foreach ($TemplatesType as  $value) {
                                			echo '<option value="'.insep_encode($value['TemplateTypeId']).'" >'.$value['Name'].'</option>';
                                		}
                                	}
                                    else
                                    {
                                    	echo '<option value="-1" >All Template Were created</option>';
                                    }
                              ?>
                            </select>

                            <div class="buttons-ui">
                            	<a onclick="CreateTemplate()" class="btn green">Create Template</a>
                        	</div>
                        </div>
                        <div class="clearfix">   </div>                  	
                        
                </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url().'js/ckeditor/ckeditor.js'?>"></script>
<script type="text/javascript">
function Showtemplate(templateid)
{	 
	showWait('Loading Page');
	window.location.href ="<?php echo lang_url(); ?>template/edittemplate/"+templateid;
}
function CreateTemplate(templateid)
{	 
	showWait('Loading Page');
	window.location.href ="<?php echo lang_url(); ?>template/edittemplate/"+$("#TemplateTypeId").val();
}
</script>
