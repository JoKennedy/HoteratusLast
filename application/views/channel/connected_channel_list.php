<div class="content container-fluid pad_adjust  mar-top-30">
<div class="row">


<div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">

<div class="row clearfix">
<div class="col-sm-5 col-xs-12 pull-right">
<input class="form-control cls_in_com pull-right" id="myInput" onkeyup="myFunction()" placeholder="Search" type="text">    </div>
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
    if($con_cha)
    {
    foreach($con_cha as $connect)
    {
        $chan_img = $this->channel_model->channel_image($connect->channel_id); 
    ?>

<tr>
<td style="text-align: left !important;"><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$chan_img->image));?>" style="height: 35px;width: 85px;">&nbsp;&nbsp; <?php echo $chan_img->channel_name; ?> </td>
<td>  <button class="btn btn-success btn-sm">live</button> </td>

<td> <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $chan_img->seo_url;?>" class="btn btn-info active"><i class="fa fa-exchange"></i> Connected</a></td>
</tr> 
 <?php } }  else{ ?>

 <tr><td>You don't have any connected channels yet .. <a class="btn btn-primary" href="<?php echo lang_url();?>channel/all_channelsplan"><i class="fa fa-plus"></i>  Connect Channels</a></td></tr>

 <?php } ?>



</tbody>
</table>
</div>
</div>

</div>
  
</div>
          <?php $this->load->view('channel/dash_sidebar'); ?>      
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