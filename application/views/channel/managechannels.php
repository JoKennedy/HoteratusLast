
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Manage Channels</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
     <div style="float: right;  " class="buttons-ui">
        <a href="<?=base_url()?>channel/allChannelList"  class="btn blue">Add Channel <i class="fa fa-plus"> </i></a>

    </div>

    <div class="clearfix"></div>
     <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="suppList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Channel Name</th>
                            <th style="text-align:center;">Status</th>
                            <th width="5%" style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllChannelConected)>0) {

                            $i=0;
                            foreach ($AllChannelConected as  $value) {
                                $i++;
                                
                                echo' <tr  class="'.($i%2?'active':'').'"> <th scope="row">'.$i.' </th> <td> '.$value['channel_name'].'  </td> 
                                <td style="text-align:center;" id="status'.$value['user_connect_id'].'"> '.($value['status']=='enabled'?'<h5><span class="label label-success">Enable</span></h5>':'<h5><span class="label label-danger">Disabled</span></h5>').'  </td> <td style="text-align:center;"><a onclick ="changestatus('.$value['user_connect_id'].')" ><i id="ico'.$value['user_connect_id'].'"  class="'.($value['status']=='enabled'?'fa fa-ban':'fa fa-check-square-o').'"></i></a></td> </tr>   ';

                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllChannelConected)==0) {echo '<h4>No Channel Conected!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
   

</div>
</div>
</div>
<script type="text/javascript">

function changestatus(id) {


      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/changestatus",
        data: {"id":id},
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["success"]) {
                swal({
                    title: "Success",
                    text: "Status Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    $("#status"+id).html((msg['status']=='enabled'?'<h5><span class="label label-success">Enable</span></h5>':'<h5><span class="label label-danger">Disabled</span></h5>'));
                    $("#ico"+id).removeClass((msg['status']!='enabled'?'fa fa-ban':'fa fa-check-square-o'));
                    $("#ico"+id).addClass((msg['status']=='enabled'?'fa fa-ban':'fa fa-check-square-o'));
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Channel not Updated! Error",
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });
}
    
function Paginar(numeroP = 10) {
    $('#suppList').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>