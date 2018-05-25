<?php $this->load->view('admin/header');?>
<style>
.thead-top th
{
	border: 0px solid #ddd !important;
}

tr.thead-top
{
	border: 0px solid #ddd !important;
}
.bor-top-no{border:0px solid #ddd !important;}
.ui-datepicker
{
	z-index:3000 !important;
}
</style>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> Manage Reservation
  </div>
  </div>


  <div class="manage">
    <div class="row-fluid clearfix">
     
       <?php 
          if($action== "add") $title ="Add Template" ;
          else if($action== "edit") $title ="Edit Template" ;
          else if($action== "view_single" || $action== "view") $title ="View Reservation";
        ?>
      <div class="col-md-12">
	  
	  <?php 
	  echo $this->session->flashdata('success');
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
		
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
        <a href="<?php echo base_url(); ?>admin/export_reservation_xls"  style="float: right; margin-right: 10px; margin-top: 10px" class="btn btn-success btn-sm"> Export To XLS </a>

        <a href="<?php echo base_url(); ?>admin/export_reservation_pdf"  style="float: right; margin-right: 10px; margin-top: 10px" class="btn btn-warning btn-sm"> Export To PDF </a>  

      <br><br>
     <div class="col-md-12">
      <div class="col-md-3" align="right"> <strong>By Channel</strong> </div>  <div class="col-md-2" align="right"> <strong>By Hotel</strong> </div> 
      </div>
      <br>
      <!-- view -->
<?php if($action== "view"){?>
		<table class="table table-striped table-bordered table-hover table-full-width bor-top-no" id="sample_13">
                                <thead>
                                <tr class="thead-top">
                                <th class="center" colspan="">&nbsp;</th>
                                <th class="center" colspan="">&nbsp;</th>
                                <th class="center" colspan="">&nbsp;</th>
                                <th class="center" colspan="">&nbsp;</th>
                                <th class="center" colspan="5">&nbsp;</th>
                                </tr>
                                    <tr class="top-tr">
										<th>S.No.</th>
										<th>Guest Name</th>
                                        <th>Reser ID</th>
                                        <th>Property Name</th>
										<th>Start Date</th>
                                        <th>End Date</th>
										<th>Reservation Date</th>
										<th>Channel Name</th>
                                        <th>Room Name</th>                                        
										<th>Status</th>
										<th>Change Status</th>
										<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
								$i=0;
								if($reservation)
								{
								foreach($reservation as $row)
								{	

									$hotel_det = get_data(HOTEL,array('hotel_id'=>$row->hotel_id))->row();	
									$pass_id=$this->admin_model->encryptIt($row->reservation_id);
									$i++;
									$status=$row->status;
									if($status==11 || $status="new" ||$status == "Book" || $status == "Reserved" || $status == "Confirmed"){ $status_type='<button type="button" class="btn btn-info btn-sm">New booking</button>';}
									
									else if($status==12 || $status == "Modify") { $status_type='<button type="button" class="btn btn-success btn-sm">Modification</button>'; } 
									else if($status==13 || $status == "Cancel" || $status == "Canceled" || $status=="Cancelled") { $status_type='<button type="button" class="btn btn-danger btn-sm">Cancelled</button>';	}
									//echo $row->hotel_id;
								?>
								<tr>
								<td><?php echo $i; ?> </td>
								<td><?php echo $row->guest_name; ?> </td>


								<td><!--<a href="<?php //echo base_url(); ?>index.php/admin/view_reservation/<?php //echo $pass_id; ?>" title="View Reservation" ><?php// echo $row->reservation_code; ?> </a>--> 
								<?php 
								if($row->channel_id==2) { echo get_data(BOOK_ROOMS,array('room_res_id'=>$row->reservation_id))->row()->reservation_id.' - '.$row->reservation_code;} else { echo $row->reservation_code; } ?> 
									<p style="display:none">
									<?php
										if($row->channel_id!='' && $row->channel_id!=0)
										{
										 echo get_data(TBL_CHANNEL,array('channel_id'=>$row->channel_id))->row()->channel_name;
										}
										else
										{
											echo 'Hoteratus';
										}
									?>
									</p>
								</td>
									<td>
								<?php 
								if($hotel_det!=''){
									echo $hotel_det->property_name;								 
								}else
								{
								 echo  'No Property Set';
								} ?>
								</td>
								<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$row->start_date))); ?> </td>
								<td><?php echo date('M d,Y',strtotime(str_replace("/","-",$row->end_date))); ?></td>
								<td><?php echo date('M d,Y',strtotime($row->booking_date)); ?> </td>
								<td><?php
									if($row->channel_id!='' && $row->channel_id!=0)
									{
									 echo get_data(TBL_CHANNEL,array('channel_id'=>$row->channel_id))->row()->channel_name;
									}
									else
									{
										echo 'Hoteratus';
									}
								?></td>
								<td>
								<?php 
									if($row->channel_id==0)
									{
										$room_details = @get_data(TBL_PROPERTY,array('property_id'=>$row->room_id),'property_name')->row_array();
										if(count($room_details)!='0') { echo ucfirst($room_details['property_name']);}else { echo '"No Room Set"';}
									}
									elseif($row->channel_id == 11)
									{
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data(IM_RECO,array('CODE'=>$row->ROOMCODE))->row()->re_id))->row()->property_id))->row()->property_name;
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}
									else if($row->channel_id == 1)
									{
										$import_mapping_id 	= @get_data('import_mapping',array('user_id'=>$row->current_user_type,'hotel_id'=>$row->hotel_id,'roomtype_id'=>$row->roomtypeId,'rate_type_id'=>$row->rateplanid))->row()->map_id;
											
										
										$property_id		= @get_data(MAP,array('owner_id'=>$row->current_user_type,'hotel_id'=>$row->hotel_id,'channel_id'=>$row->channel_id,'import_mapping_id'=>$import_mapping_id))->row()->property_id;
										
										$roomName 			= @get_data(TBL_PROPERTY,array('property_id'=>$property_id))->row()->property_name;
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}
									elseif($row->channel_id==2)
									{
										$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data(BOOKING,array('B_room_id'=>$row->id,'B_rate_id'=>$row->roomtypeId))->row()->import_mapping_id))->row()->property_id))->row()->property_name;
										if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
									}else if($row->channel_id == 15)
								    {
								    	$roomName = @get_data(TBL_PROPERTY,array('property_id'=>get_data(MAP,array('channel_id'=>$row->channel_id,'import_mapping_id'=>get_data(IM_TRAVELREPUBLIC,array('RoomTypeId'=>$row->roomtypeId))->row()->map_id))->row()->property_id))->row()->property_name;
								    	
								    	if($roomName)
										{
											echo ucfirst($roomName);
										}
										else
										{
											echo '"No Room Set"';
										}
								    }
									?> 
                                	<p style="display:none"> 
									<?php
										if($row->hotel_id!='' && $row->hotel_id!=0)
										{
											echo @get_data(HOTEL,array('hotel_id'=>$row->hotel_id))->row()->property_name;
										}
									?>
									</p>
								</td>
								<td id="reserve_status">
								<?php
								if($row->channel_id == 0)
								{
									if($row->status=='Reserved'){ echo '<button type="button" class="btn btn-info btn-sm"> Reserved </button>';}
									elseif($row->status=='Confirmed'){	echo '<button type="button" class="btn btn-success btn-sm"> Confirmed </button>';}
									elseif($row->status=='Canceled'){	echo '<button type="button" class="btn btn-danger btn-sm"> Cancelled </button>';} 
								}
								else
								{
									echo $status_type;
								}
								?>
								 </td>
								<td>
								<?php 
								if($row->channel_id==0)
								{
								?>
								<select onChange="change_reservation(this.value,'<?php echo $row->reservation_id; ?>')" class="form-control">
									<option <?php if($row->status=='Reserved'){ echo "selected='selected'";}?> value="Reserved">Reserved</option>
									<option <?php if($row->status=='Confirmed'){ echo "selected='selected'";}?> value="Confirmed">Confirmed</option>
									<option <?php if($row->status=='Canceled'){ echo "selected='selected'";}?> value="Canceled">Canceled</option>
								</select>
								<?php
								}
								else
								{
									echo  '----';
								}
								?>
								</td>
								<?php
								
								echo "<td>";
								if($row->channel_id == 0)
								{
									$pass_id=$this->admin_model->encryptIt($row->reservation_id);
									$value=array('class'=>'delete','onclick'=>'return delcon();');
									echo anchor('admin/manage_reservation/delete/'.$pass_id,'<i class="fa fa-times" title="delete"></i>',$value);
								}
								else
								{
									echo '---';
								}
								echo "</td>";
							
								
								echo "</tr>";
								}
								}
								else
								{
									echo "<tr><td colspan='9'>No records found</td></tr>";
								}
					?>
                        </tbody>
                    </table>
      <?php
      }
      ?>
    <?php if($action== "edit"){?>
       <div class="panel-body">
       <div class="row">
        <div class="col-lg-1">
        </div>
      <div class="col-lg-9">
          <?php //echo form_open_multipart('admin/manage_email/update/'.$id); ?>
          <form action="<?php echo lang_url();?>admin/manage_email/update/<?php echo $id?>" id="edit_template" method="post" enctype="multipart/form-data">
          <span class="error"><?php echo validation_errors();?></span>
            <div class="form-group">
                <label><font color="#CC0000">*</font>Title</label>
                <input class="form-control"  name="title" value="<?php if(isset($title)){
 echo $title; }  ?>" readonly>
      
      <!--input type="hidden" name="id" value="<?php echo $id;?>"/>-->
                        </div>
      <div class="form-group">
          <label><font color="#CC0000">*</font>Subject</label>
          <input class="form-control" required name="subject" type="subject" value="<?php if(isset($subject)){
echo $subject; }
//echo set_value('password'); ?>"/>
      
                            </div>
      <div class="form-group">
                            <label><font color="#CC0000">*</font>Message</label>
                          <textarea name="message" id="textareacontent" cols="30" rows="10" required ><?php echo $message;?></textarea>
      
                            </div>
                       <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>
                    <?php //echo form_close(); ?>
                </div>
                <!-- /.col-lg-6 (nested) -->
               
                <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
      <?php
      }
      ?>
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
<?php echo theme_css('jquery-ui.min.css', true);?>
<?php echo theme_js('jquery-ui.min.js', true);?>
<?php
$reservation_all_channel = $this->admin_model->reservation_all_channel();
$reservation_all_hotel   = $this->admin_model->reservation_all_hotel();
?>
<link rel="stylesheet" href="<?php echo base_url()?>user_assets/js/plugins/data-tables/DT_bootstrap_edit.css"/>
<script type="text/javascript" src="<?php echo base_url()?>user_assets/js/plugins/data-tables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>user_assets/js/plugins/data-tables/DT_bootstrap_edit.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/jquery.dataTables.columnFilter.js"></script>
<script>
function delcon()
{
	var del=confirm("Are you sure want to delete?");
	if(del)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function change_reservation(val,id)
{
	var dataString='val='+val+'&id='+id;
	$.ajax({
				type: 'POST',
				url: '<?php echo lang_url(); ?>admin/change_reservation', 
				data: dataString, 
				success: function(result) 
				{
					if(val=='Reserved')
					{
						$('#reserve_status').html('<button type="button" class="btn btn-info btn-sm">'+val+'</button>');
					}
					else if(val=='Confirmed')
					{
						$('#reserve_status').html('<button type="button" class="btn btn-success btn-sm">'+val+'</button>');		 
					}
					else if(val=='Canceled')
					{
						$('#reserve_status').html('<button type="button" class="btn btn-danger btn-sm">'+val+'</button>'); 
					}
				}
			});
}
$(document).ready(function()
{
	$.datepicker.regional[""].dateFormat = 'M d,yy';
	$.datepicker.setDefaults($.datepicker.regional['']);
	$('#sample_13').dataTable().columnFilter({ 	
	sPlaceHolder: "head:before",
	aoColumns: [ 
			null,
			null,
			{ sSelector: ".col1", type:"select",values: <?php echo $reservation_all_channel;?>  },
			{ sSelector: ".col2", type:"select",values: <?php echo $reservation_all_hotel;?>  },
			{ sSelector: ".col3", type:"date-range" },
			{ type: "date-range" },
			null,
			null
			]});
			
	setTimeout(function(){
	$('#sample_13_wrapper').find('.col1').find('.search_init').find("option:first").text('All Reservations'); // All Channel
	$('#sample_13_wrapper').find('.col1').find('.search_init').val('<?php //echo $channel_name;?>');
	$('#sample_13_wrapper').find('.col2').find('.search_init').find("option:first").text('All Hotels');
	$('#sample_13_wrapper').find('.col2').find('.search_init').removeAttr('onchange');
	},800);
});

</script>