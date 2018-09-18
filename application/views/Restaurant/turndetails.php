<div class="outter-wp">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Turn Details</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div class="clearfix"></div>
    <div class="graph-form">
  
            <div class="buttons-ui" style="float:right;">
                <a href="<?=site_url('pos/viewTurns/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']))?>" class="btn green">All Turns</a>
            </div>
            <div class="clearfix"></div>
            <div>
                <center>
                    <h2><?=strtoupper($turninfo['name'])?></h2></center>
            </div>
            <div class="col-md-12 form-group1 ">
                <div class="onoffswitch" style="float: left;">
                    <input type="checkbox" name="statusid" class="onoffswitch-checkbox" id="statusid" <?=($turninfo[ 'active']==1? 'checked': '')?> >
                    <label class="onoffswitch-label" for="statusid">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <form id="allproduct">
                <input type="hidden" name="turnid" value="<?=$turninfo[ 'posturnid']?>">
                <input type="hidden" name="isitem" value="1">
                <div class="graph-visual tables-main">
                    <div class="graph">
                        <div class="table-responsive">
                            <div class="clearfix"></div>
                            <table id="productlist" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th style="text-align:center; width: 10%;">Selected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($AllDetails)>0) {
                                
                                $i=0;
                                foreach ($AllDetails as  $value) {
                                    $i++;
                                   
                                    echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> 
                                    <td> '.$value['name'].'  </td> 
                                    <td> '.$value['Category'].'  </td> 
                                    <td style="text-align:center; width: 10%;"> <input type="checkbox" name="productid[]" value="'.$value['itemPosId'].'" '.($value['selected']==1?'checked':'').'>   </td> </tr>';

                                }                    
                               
                            } ?>
                                </tbody>
                            </table>
                                <?php if (count($AllDetails)==0) {echo '<h4>No Product Created!</h4>';} 
                      
                            ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"> </div>
                <div class="buttons-ui">
                    <a onclick="savedetails()" class="btn green">Assign Products</a>
                </div>
            </form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">

function savedetails()
{

      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveTurnDetails",
        data: $("#allproduct").serialize(),
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["success"]) {
                swal({
                    title: "Success",
                    text: "All Product Assigned!!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["message"],
                    icon: "warning",
                    button: "Ok!",
                });
            }

        }
    });
}
  
    
    $(document).ready(function() {
        $('#productlist').DataTable();
    });

</script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables-init.js"></script>