 <div class="contents container-fluid pad_adjust  mar-top-30">
    <div class="row">

  <div class="col-md-11 col-sm-11 col-xs-12 cls_cmpilrigh">

  <div class="verify_det">

    <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Manage Rooms </h4>

  </div>

   <hr>

 <div class="verify_det clearfix">      
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{?>
 <a href="#M_room" class="cls_com_blubtn pull-right waves-effect waves-light m-r-5 m-b-10" data-animation="sidefall" data-plugin="custommodal" data-overlaySpeed="50" data-overlayColor="#000"><i class="fa fa-plus-circle"></i> Add New </a>
<?php } ?> 

  </div>  

 

  <?php 
if($this->session->flashdata('profile')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile');?>
</div>
<?php } ?>
<?php 
if($this->session->flashdata('profile_error')!='')
{
?>
<div class="alert alert-warning">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('profile_error');?>
</div>
<?php } ?>
    
  <div class="clk_history dep_his pay_tab_clr">
         
        
       <div class="table-responsive">
      <!--  <div class="pull-right">
<?php
if(user_type()=='1' || user_type()=='2' && in_array('4',user_edit()) || admin_id()!='' && admin_type()=='1' )
{?>
<a class="btn btn-primary hvr-sweep-to-right" data-toggle="modal" data-target="#M_room" href="javascript:;" data-backdrop="static" data-keyboard="false">Add New <i class="fa fa-plus"> </i></a>
<?php } ?>
 </div> -->
 
      <table class="table table-bordered table-hover"> 
      <?php $total_taxcategory = $this->reservation_model->total_taxcategory(); ?>
    <thead> 
      <tr class="info">
      <th>#</th>
      <!--<th>Room type</th>-->
      <th>Room name</th>
      <th>Pricing type</th>
      <th>Meal plan</th>
      <th>Rooms Quantity</th>
      <th>Adult capacity</th>
      </tr>
    </thead>
      <tbody>

    <?php if(count($active)!=0) { 
$ii=1;
/*echo '<pre>';
print_r($active);*/
foreach($active as $acc) {
  extract($acc);?>

<tr>
<td><?php echo $ii;?></td>
<td><a href="<?php echo lang_url();?>inventory/room_types/view/<?php echo insep_encode($property_id);?>"><?php echo ucfirst($property_name);?></a></td>
<td><?php if($pricing_type==1) { echo '<span class="label label-sm label-info">Room based pricing</span>';} else if($pricing_type=='2'){ echo '<span class="label label-sm label-success">Guest based pricing</span>';}?></td>
<td><?php if($meal_plan==0){ echo 'Not available';} else { echo $meal_plans = get_data(MEAL,array('meal_status'=>1,'meal_id'=>$meal_plan))->row()->meal_name;} ?></td>
<td><?php echo $existing_room_count; ?></td>
<td> <a href="<?php echo lang_url();?>inventory/room_types/view/<?php echo insep_encode($property_id);?>">
<?php echo $member_count; ?> </a> 
 </td>  
</tr><?php  $ii++; } } else { ?>
<tr>
<td colspan="6" class="text-center text-danger">No active room data found...</td>
</tr>
<?php } ?>
</tbody>
 </table>
      </div>
      </div>
    <div class="clearfix"></div>
   
    
</div>
  
</div>
         <?php $this->load->view('channel/dash_sidebar'); ?>      
    </div>
    </div><!-- /scroller-inner -->
   
    
</div><!-- /scroller -->

</div>
