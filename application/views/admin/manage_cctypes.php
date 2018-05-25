<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage CC Types
  </div>
  </div>


  <div class="manage">
    <div class="row-fluid clearfix">
      <?php     
      if(isset($error)) { ?> 
        <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
        </div>
      <?php }?>     
      <?php 
      $success=$this->session->flashdata('success');                 
      if($success)  { ?> 
        <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success! </strong> <?php echo $success;?>.
        </div>
      <?php 
      }
      $error=$this->session->flashdata('error');                   
      if($error)  { ?> 
        <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
        </div>
      <?php }?> 
       <?php 
          if($action== "add") $pagetitle ="Add Template" ;
          else if($action== "edit") $pagetitle ="Edit Template" ;
          else if($action== "view_single" || $action== "view") $pagetitle ="View Template";
        ?>
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $pagetitle;?></h4>
	  <?php if($action== "view") { ?>
		<div class="panel-heading">
		<h3 class="panel-title"><button type="button" class="pull-right btn btn-primary" id="sample_editable_1_new" >Add <i class="fa fa-plus"> </i></button></a></h3>
		</div>
	<?php } ?>
      <br><br>
      <!-- view example -->
	  
<?php if($action== "view"){?>
      <table id="sample_editable_1" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
				<tr class="top-tr">
                <th width="4%">S.No</th>
                <th>Card Type</th>
                <th><center>Action | Status </center></th>
				</tr>
            </thead>
            <tbody>
				<?php
				$i=0;
				foreach($cctypes as $row)
				{    
					$i++;
					?>
					<tr custom="<?php echo $row['cc_type_id'];?>">
					<td><?php echo $i; ?> </td>
					<td><?php echo $row['cc_type_name']; ?> </td>
					
					<?php
					echo "<td align='center'>";
					$value=array('class'=>'edit');
					echo anchor('admin/manage_email/edit/'.$row['cc_type_id'],'Edit',$value).' | ';
					if
					($row['cc_type_status']==1)
					{
					?>
					<a href="javascript:;" class="change_status" id="change_status_<?php echo $row['cc_type_id'];?>" data-id="<?php echo $row['cc_type_id'];?>"> De-Activate </a>
					<?php } else { ?>
					<a href="javascript:;" class="change_status" id="change_status_<?php echo $row['cc_type_id'];?>" data-id="<?php echo $row['cc_type_id'];?>"> Activate </a>					
					<?php } ?>
					<?php
					echo "</td>";
					echo "</tr>";
				}
				?>
            </tbody>
        </table>
        <?php
      }
      ?>
<form action="<?php echo lang_url();?>admin/mangecctypes/add" method="post" id="save">
<input type="hidden" name="special" value="" id="special">
<input type="hidden" name="add_cc" value="add_cc">
</form>
<form action="<?php echo lang_url();?>admin/mangecctypes/update" method="post" id="form_update">
<input type="hidden" name="update" value="" id="update">
 <input type="hidden" name="update_id" value="" id="update_id">
</form>
    <?php if($action== "edit" || $action== "add"){?>
	<div class="panel-body">
	<div class="row">
	<div class="col-lg-1">
	</div>
	<div class="col-lg-9">
	
	<?php if($action== "add")
	{
	?>
		<form action="<?php echo lang_url();?>admin/manage_email/add" id="edit_template" method="post" enctype="multipart/form-data">
		<input type="hidden" name="add_temp" value="add_temp">
	<?php 
	}
	else
	{
	?>
		<form action="<?php echo lang_url();?>admin/manage_email/update/<?php if(isset($id)){ echo $id; }?>" id="edit_template" method="post" enctype="multipart/form-data">
	<?php
	}
	?>
	<span class="error"><?php echo validation_errors();?></span>
	<div class="form-group">
	<label><font color="#CC0000">*</font>Title</label>
	<input class="form-control"  name="title" value="<?php if(isset($title)){ echo $title; }  ?>">
	</div>
	<div class="form-group">
	<label><font color="#CC0000">*</font>Subject</label>
	<input class="form-control" required name="subject" type="text" value="<?php if(isset($subject)){ echo $subject; }
	?>"/>
	</div>
	<div class="form-group">
	<label><font color="#CC0000"></font>Message</label>
	<textarea name="message" id="textareacontent" name="textareacontent" cols="30" rows="10" required ><?php if(isset($message)){ echo $message; }
	 ?></textarea>
	</div>
	<button type="submit" class="btn btn-success">Save</button>
	</form>
	</div>
	</div>
	</div>
      <?php
      }
      ?>
      </div>
     </div>
    </div>
  </div>

<?php $this->load->view('admin/footer');?>	

<script src="<?php echo base_url()?>js/plugins/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo base_url()?>js/plugins/jquery.dataTables.js"></script>
<script src="<?php echo base_url()?>js/plugins/DT_bootstrap.js"></script>
<script src="<?php echo base_url()?>js/plugins/app.js"></script>
<script type="text/javascript">

var TableEditable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
				//alert(aData[1]);
                <!--jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';-->
               
              <!--  jqTds[2].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[2] + '">';-->
                <!--jqTds[3].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[3] + '">';-->
				if(aData[1]=='')
				{
					 jqTds[1].innerHTML = '<input type="text" class="form-control input-small" onKeyUp="special();" id="sp" value="">';
					 jqTds[2].innerHTML = '<a href="" class="save" title="save">Save</a>  <a href="" class="cancel" title="cancel">Cancel</a>';
				}
				else
				{ 	$('#update').val(aData[1]);
					 jqTds[1].innerHTML = '<input type="text" class="form-control input-small" onKeyUp="update();" id="up" value="' + aData[1] + '">';
					 jqTds[2].innerHTML = '<a class="update" href="">Save</a> <a class="cancel" href="">Cancel</a>';
				}
               
                /* jqTds[2].innerHTML = '<a class="cancel" href="">Cancel</a>'; */
				/* jqTds[5].innerHTML = ''; */
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
                oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 5, false);
                oTable.fnDraw();
            }

            function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
                oTable.fnDraw();
            }

            var oTable = $('#sample_editable_1').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
                ]
            });

            jQuery('#sample_editable_1_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#sample_editable_1_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            /* jQuery('#sample_editable_1_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); */ // initialize select2 dropdown

            var nEditing = null;

            $('#sample_editable_1_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '<a class="edit" href=""><i class="fa fa-pencil" title="Edit"></i></a> <a class="cancel" data-mode="new" href=""><i class="fa fa-remove" title="Delete" style="margin-left: 10px"></i></a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            $('#sample_editable_1 a.delete').on('click', function (e) {
               

                if (confirm("Are you sure to delete this row ?") == false) {
                    return;
                }
				else
				{
					return href;
				}

               /* var nRow = $(this).parents('tr')[0];
                oTable.fnDeleteRow(nRow);
                alert("Deleted! Do not forget to do some ajax to sync with backend :)");*/
            });

            $('#sample_editable_1 a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
					 var nRow = $(this).parents('tr')[0];
					 oTable.fnDeleteRow(nRow);
					 location.reload();
                    /*restoreRow(oTable, nEditing);
                    nEditing = null;*/
                }
            });

            $('#sample_editable_1 a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                    nEditing = null;
                    alert("Updated! Do not forget to do some ajax to sync with backend :)");
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
			$('#sample_editable_1 a.save').live('click', function (e) {
                e.preventDefault();
				var name = document.getElementById('sp').value;
				if(name=='')
				{
					alert('Enter the doctor qualification');
				}
				else	
				{
					$('#save').submit();
				}
			});
			$('#sample_editable_1 a.update').live('click', function (e) {
                e.preventDefault();
				var nRow = $(this).parents('tr').attr('custom');
				
	 			
				var name = document.getElementById('up').value;
				if(name=='')
				{
					alert('Enter the card type');
				}
				else	
				{
					$('#update_id').val(nRow);
					$('#form_update').submit();
				}
			});
			
        }

    };

}();
jQuery(document).ready(function() {       
   App.init();
   TableEditable.init();
});
function special()
{
	
	var name = document.getElementById('sp').value;
	$('#special').val(name);
	//alert(name);
} 
function update()
{
	  
	var name = document.getElementById('up').value;
	$('#update').val(name);
	//alert(name);
}
function del()
{
	var del=confirm('Are u sure want to delete the record?');
	if(del)
	{
		return href;
	}
	else
	{
		return false;
	}
}


/* $(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
});
 */
function delcon()
{
	var del	=	confirm("Are you sure want to delete");
	
	if(del)
	{
		return true;
	}
	else
	{
		return false;
	}
}

jQuery.validator.addMethod("lettersonly", 
      function(value, element) {
           return this.optional(element) || /^[a-z," "]+$/i.test(value);
      }, "Letters only please");
      
$.validator.addMethod("regexp", function(value, element, param) { 
  return this.optional(element) || !param.test(value); 
});
      
$('#edit_template').validate({
    rules:
    {
      subject:
      {
        required:true,
        regexp: /['"]/,
      },
	  title:
      {
        required:true,
      }
    },
  });
  
$(document).on('click','.change_status',function(){
	var curr_id	=	$(this).attr('data-id');
	$.ajax({
				type: "POST",
				url: "<?php echo lang_url()?>admin/mangecctypes/status",
				data: "source="+$(this).attr('data-id'),
				success: function(result)
				{
					/* console.log(result);
					console.log($('#change_status_'+curr_id).html()); */
					if(result==1)
					{
						var status = $('#change_status_'+curr_id).html();
						console.log(status);
						if(status==' De-Activate ')
						{
							console.log(status);
							$('#change_status_'+curr_id).html(' Activate ');
						}
						else if(status==' Activate ')
						{
							$('#change_status_'+curr_id).html(' De-Activate ');
						}
					}
					else
					{
						
					}
						
				}
			});
});

</script>
