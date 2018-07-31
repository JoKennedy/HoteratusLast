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
            <li class="active">Suppliers</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a href="#createsupplier" data-toggle="modal" class="btn blue">Add New Supplier</a>
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
                            <th>Company Name</th>
                            <th>Representative Name</th>
                            <th>Phone/Mobile</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllSuppliers)>0) {

                            $i=0;
                            foreach ($AllSuppliers as  $value) {
                                $i++;
                                $update="'".$value['companyname']."','".$value['representativename']."','".  $value['address']."','".$value['phone']."','".$value['cellphone']."','".$value['email']."','".$value['supplierID']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['companyname'].'  </td> 
                                <td> '.$value['representativename'].'  </td> <td> '.$value['phone'].(strlen($value['phone'])>0 && strlen($value['cellphone'])>0?"/":"").$value['cellphone'].'</td> <td> '.$value['email'].'  </td>  
                                <td>'.($value['active']==1?'Active':'Deactive').'</td> <td><a href="#updatesupplier" onclick ="showupdate('.$update.')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllSuppliers)==0) {echo '<h4>No Supplier Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createsupplier" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Create a Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="SupplierC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Company Name</label>
                            <input style="background:white; color:black;" name="cname" id="cname" type="text" placeholder="Company Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Representative Name</label>
                            <input style="background:white; color:black;" name="rname" id="rname" type="text" placeholder="Representative Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Address</label>
                            <input style="background:white; color:black;" name="address" id="address" type="text" placeholder="Address" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone</label>
                            <input style="background:white; color:black;" name="phone" id="phone" type="text" placeholder="Phone" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Cell Phone</label>
                            <input style="background:white; color:black;" name="cphone" id="cphone" type="text" placeholder="Cell Phone" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Email</label>
                            <input style="background:white; color:black;" name="email" id="email" type="text" placeholder="Email" required="">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveSupplier()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updatesupplier" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Update a Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="Supplierup">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <input type="hidden" name="supplierID" id="supplierID" value="">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Company Name</label>
                            <input style="background:white; color:black;" name="cnameup" id="cnameup" type="text" placeholder="Company Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Representative Name</label>
                            <input style="background:white; color:black;" name="rnameup" id="rnameup" type="text" placeholder="Representative Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Address</label>
                            <input style="background:white; color:black;" name="addressup" id="addressup" type="text" placeholder="Address" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone</label>
                            <input style="background:white; color:black;" name="phoneup" id="phoneup" type="text" placeholder="Phone" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Cell Phone</label>
                            <input style="background:white; color:black;" name="cphoneup" id="cphoneup" type="text" placeholder="Cell Phone" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Email</label>
                            <input style="background:white; color:black;" name="emailup" id="emailup" type="text" placeholder="Email" required="">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="updateSupplier()" class="btn green">Update</a>
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
function saveSupplier() {



    var data = new FormData($("#SupplierC")[0]);

    if ($("#cname").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Company Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#rname").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Representative Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#address").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Address!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#phone").val() <= 0 && $("#cphone").val() <= 0 && $("#email").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "You must enter a contact method (phone, mobile or email)!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false,
        url: "<?php echo lang_url(); ?>pos/saveSupplier",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Supplier Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Supplier was not Created! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });


}

function updateSupplier() {



    var data = new FormData($("#Supplierup")[0]);

    if ($("#cnameup").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Company Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#rnameup").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Representative Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#addressup").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Address!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#phoneup").val() <= 0 && $("#cphoneup").val() <= 0 && $("#emailup").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "You must enter a contact method (phone, mobile or email)!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false,
        url: "<?php echo lang_url(); ?>pos/updateSupplier",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Supplier Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Supplier was not Updated! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });


}

function showupdate(cname, rname, address, phone, cphone, email, id) {
    $("#cnameup").val(cname);
    $("#rnameup").val(rname);
    $("#addressup").val(address);
    $("#phoneup").val(phone);
    $("#cphoneup").val(cphone);
    $("#emailup").val(email);
    $("#supplierID").val(id);

}


$.fn.pageMe = function(opts) {
    var $this = this,
        defaults = {
            perPage: 7,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);

    var listElement = $this.find('tbody');
    var perPage = settings.perPage;
    var children = listElement.children();
    var pager = $('.pager');

    if (typeof settings.childSelector != "undefined") {
        children = listElement.find(settings.childSelector);
    }

    if (typeof settings.pagerSelector != "undefined") {
        pager = $(settings.pagerSelector);
    }

    var numItems = children.size();
    var numPages = Math.ceil(numItems / perPage);

    pager.data("curr", 0);

    if (settings.showPrevNext) {
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }

    var curr = 0;
    while (numPages > curr && (settings.hidePageNumbers == false)) {
        $('<li><a href="#" class="page_link">' + (curr + 1) + '</a></li>').appendTo(pager);
        curr++;
    }

    if (settings.showPrevNext) {
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }

    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages <= 1) {
        pager.find('.next_link').hide();
    }
    pager.children().eq(1).addClass("active");

    children.hide();
    children.slice(0, perPage).show();

    pager.find('li .page_link').click(function() {
        var clickedPage = $(this).html().valueOf() - 1;
        goTo(clickedPage, perPage);
        return false;
    });
    pager.find('li .prev_link').click(function() {
        previous();
        return false;
    });
    pager.find('li .next_link').click(function() {
        next();
        return false;
    });

    function previous() {
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }

    function next() {
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }

    function goTo(page) {
        var startAt = page * perPage,
            endOn = startAt + perPage;

        children.css('display', 'none').slice(startAt, endOn).show();

        if (page >= 1) {
            pager.find('.prev_link').show();
        } else {
            pager.find('.prev_link').hide();
        }

        if (page < (numPages - 1)) {
            pager.find('.next_link').show();
        } else {
            pager.find('.next_link').hide();
        }

        pager.data("curr", page);
        pager.children().removeClass("active");
        pager.children().eq(page + 1).addClass("active");

    }
};

function Paginar(numeroP = 10) {
    $('#suppList').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>