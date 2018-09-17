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
            <li class="active">Gift Card</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a href="#createGiftCard" data-toggle="modal" class="btn blue">Create a Gift Card</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="giftcardtable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th  width="5%">#</th>
                            <th>Gift Number</th>
                            <th>Secret Code</th>
                            <th>Buyer Name</th>
                            <th>Assigned To</th>
                            <th>Transferable</th>
                            <th>Amount</th>
                            <th style="text-align:center; width: 5%;">Print</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllGiftCard)>0) {

                            $i=0;
                            foreach ($AllGiftCard as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> 
                                <td> '.$value['giftcardnumber'].'  </td> 
                                <td> '.$value['secrectcode'].'  </td>
                                <td>'.$value['buyername'].' </td>
                                <td>'.$value['assignedto'].' </td>
                                <td>'.($value['transferable']==1?'Yes':'No').' </td>
                                <td>'.number_format($value['amount'], 2, '.', '').' </td> 
                                <td style="text-align:center;"><a onclick="printcard()"><i class="fa fa-print"></i></a></td>
                                </tr>   ';

                            }

                                                                
                                                              
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllGiftCard)==0) {echo '<h4>No Gift Card Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createGiftCard" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Gift Card</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="GiftCardC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Assigned to</label>
                            <input style="background:white; color:black;"  name="assignedto" id="assignedto" type="text" placeholder="Assigned To" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Buyer Name</label>
                            <input style="background:white; color:black;"  name="buyername" id="buyername" type="text" placeholder="Buyer Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Gift Card Amount</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="amount" id="amount" type="text" placeholder="Gift Card Amount" required="">
                        </div>
                         <div class="col-md-3 form-group1">
                                <label class="control-label"><strong>Transferable</strong></label>
                        </div>
                        <div class="col-md-3 form-group1">
                            <label class="control-label">Yes</label>
                            <input name="rtype" id="rtype" type="radio" value="1" >
                        </div>
                         <div class="col-md-3 form-group1">
                            <label class="control-label">No<input  name="rtype" id="rtype" type="radio" checked="" value="0" ></label>
                            
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveGiftCard()" class="btn green">Create</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>




<script type="text/javascript">
    function printcard()
    {
        
        swal({
            title: "upps, Sorry",
            text: "Connect a printer to continue" ,
            icon: "warning",
            button: "Ok!",
        });
    }
function saveGiftCard() {

    var data = $("#GiftCardC").serialize();

    if ($("#amount").val()==0 || $("#amount").val().length==0 ) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Amount!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveGiftCard",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["success"]) {
                swal({
                    title: "Success",
                    text: "Gift Card Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Gift Card no Created!" ,
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });


}


function Paginar() {
     $('#giftcardtable').DataTable();

}
$(document).ready(function() {

    Paginar();

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