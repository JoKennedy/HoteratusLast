<div class="content container-fluid pad_adjust  mar-top-30">
<div class="row">

<div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">

 <div class="row clearfix">
<div class="col-sm-5 col-xs-12 pull-right">
<input class="form-control cls_in_com pull-right" id="myInput" onkeyup="myFunction()" placeholder="Search" type="text">  
</div>
 </div> 
<div class="clearfix"> </div> 

<div class="cls_commtable cls_comtaxtable cls_roomtable mar-top-20">
<div class="table-responsive">
<table class="table" id="myTable">
<thead>
<tr>
<th>Channel </th>
<th> Active      </th>
<th> Status </th>
</tr>
</thead>
<tbody>
<?php
if(count($all_con)!=0)
{
foreach($all_con as $connect)
{
  extract($connect);
  $cha = explode(',',$channel_id);
  $con_channel = $this->mapping_model->get_channel($cha);
  if($con_channel)
            {    
?>


<tr>
<td style="text-align: left !important;"><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>" style="height: 35px;width: 85px;">&nbsp;&nbsp; <?php echo $channel_name; ?> </td>
<td> <button type="button" class="btn <?php if($status == 'Active'){?> btn-success <?php }else if($status == "New"){?> btn-warning <?php }else if($status == "Process"){?> btn-danger <?php } ?>"> <?php if($status == 'Active'){?>Live<?php }elseif($status == 'New'){ ?>New<?php }elseif($status == 'Process'){?> Construction <?php } ?> </button> </td>
<td> <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $seo_url;?>" class="btn btn-info">  Connected </td>
</tr>

 <?php } else{ ?>

 <tr>
<td  style="text-align: left !important;"><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>" style="height: 35px;width: 85px;">&nbsp;&nbsp; <?php echo $channel_name; ?> </td>
<td> <button type="button" class="btn <?php if($status == 'Active'){?> btn-success <?php }else if($status == "New"){?> btn-warning <?php }else if($status == "Process"){?> btn-danger <?php } ?>"> <?php if($status == 'Active'){?>Live<?php }elseif($status == 'New'){ ?>New<?php }elseif($status == 'Process'){?> Construction <?php } ?> </button> </td>
<td>
 <?php if($status == 'Active'){ ?>
                          <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $seo_url;?>" class="btn btn-info name"><i class="fa fa-link"></i> connect</a>
                      <?php } else if($status == 'New'){ ?>
                          <a href="#" class="btn btn-info name"><i class="fa fa-chain-broken"></i> connect</a>
                      <?php } else if($status == "Process"){ ?>
                          <a href="#" class="btn btn-info name"><i class="fa fa-cog"></i> connect</a>
                      <?php } ?>
 </td>
</tr>

 <?php } } } else{ ?>

 <tr><td>You don't have any connected channels yet .. <a class="btn btn-primary" href="<?php echo lang_url();?>channel/all_channelsplan"><i class="fa fa-plus"></i>  Connect Channels</a></td></tr>

 <?php } ?>



</tbody>
</table>

<div id="no_res">

</div>

</div>
</div>

</div>
  
</div>
               
    </div>

    <?php $this->load->view('channel/dash_sidebar'); ?>      
  

<script>
function myFunction() {  
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr"); 
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) { 
    
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) 
      {         
        tr[i].style.display = "";        
      }
       else
      {       
        tr[i].style.display = "none";        
      }      
    }       
  }
}
</script>