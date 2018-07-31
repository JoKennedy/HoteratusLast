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
            <li class="active">Recipes</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a onclick="ClearRecipe();" href="#createrecipe" data-toggle="modal" class="btn blue">Add New Recipe</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="recList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Recipe Name</th>
                            <th>Price</th>
                            <th>Imagen</th>
                            <th width="5%">Status</th>
                            <th width="5%">Change of Price</th>
                            <th width="5%">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ALLRecipes)>0) {

                            $i=0;
                            foreach ($ALLRecipes as  $value) {
                                $i++;
                                $update="'".$value['recipeid']."','".$value['name']."','".$value['Active']."','".$value['price']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td> 
                                 <td>'.$value['price'].'</td> <td> <img id="img'.$value['recipeid'].'" width="50px" src="'.$value['photo'].'" > </td> <td>'.($value['Active']==1?'Active':'Deactive').'</td> <td> <a href="#priceh" onclick =" showPrice('.$update.')" data-toggle="modal"> <i class="fa fa-pencil-square-o"></i></a></td> <td><a href="#updaterecipe" onclick =" showupdate('.$update.')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';
                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($ALLRecipes)==0) {echo '<h4>No Recipe Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createrecipe" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Recipe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="RecipeC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Recipe Name</label>
                            <input style="background:white; color:black;" name="recipename" id="recipename" type="text" placeholder="Recipe Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Price</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="pricen" id="pricen" type="text" placeholder="Price" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Total Cost</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="totalC" id="totalC" type="text" placeholder="0.00" required="" readonly="true">
                        </div>
                        <div class="col-md-6 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Product List </label>
                            <select style="width: 100%; padding: 9px;" name="productid" id="productid">
                                <?php

                                    echo '<option  value="0" >Select a Product</option>';
                                    foreach ($ALLProducts as $value) {
                                        $i++;
                                        echo '<option value="'.$value['itemPosId'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Quantity</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="qty" id="qty" type="text" placeholder="Quantity" required="">
                        </div>
                        <div class="buttons-ui">
                            <a onclick="addProduct()" class="btn blue">Add</a>
                        </div>
                        <div class="graph">
                            <div class="table-responsive">
                                <div class="clearfix"></div>
                                <table id="allDetails" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th width="2%">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"> </div>
                                <br>
                                <br>
                                <div class="buttons-ui">
                                    <a onclick="saveRecipe();" class="btn green">Save</a>
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

<div id="updaterecipe" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Update a Recipe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="RecipeC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <input type="hidden" name="recipeid" id="recipeid" value="">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Recipe Name</label>
                            <input style="background:white; color:black;" name="recipenameup" id="recipenameup" type="text" placeholder="Recipe Name" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Price</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="priceup" id="priceup" type="text" placeholder="Price" required="" readonly="true">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Total Cost</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="totalCup" id="totalCup" type="text" placeholder="0.00" required="" readonly="true">
                        </div>
                        <div class="col-md-6 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Product List </label>
                            <select style="width: 100%; padding: 9px;" name="productidup" id="productidup">
                                <?php

                                    echo '<option  value="0" >Select a Product</option>';
                                    foreach ($ALLProducts as $value) {
                                        $i++;
                                        echo '<option value="'.$value['itemPosId'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Quantity</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="qtyup" id="qtyup" type="text" placeholder="Quantity" required="">
                        </div>
                        <div class="buttons-ui">
                            <a onclick="addProductup()" class="btn blue">Add</a>
                        </div>
                        <div class="graph">
                            <div class="table-responsive">
                                <div class="clearfix"></div>
                                <table id="allDetailsup" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th width="2%">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="clearfix"> </div>
                                <br>
                                <br>
                                <div class="buttons-ui">
                                    <a onclick="updateRecipe();" class="btn green">Update</a>
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
<div id="priceh" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
           
                <h4 class="modal-title">Price History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="ProductP">
                        <input type="hidden" name="itemPosIdP" id="itemPosIdP" value="">
                        <div class="graph-form">
                            <div class="col-md-8 form-group1">
                                <label class="control-label">Recipe Name</label>
                                <input style="background:white; color:black;" id="recipenameP" type="text" placeholder="Product Name" required="" readonly="true">
                            </div>
                            <div class="col-md-4 form-group1">
                               <img style="width:100px;" src="<?=$ALLRecipes[0]['photo']?>" id="photoP">
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="graph-form">
                            <div style="float: left;" class="col-md-12 form-group1">
                                <label class="control-label">New Price</label>
                                <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="newprice" id="newprice" type="text" placeholder="Price" required="">
                                <div style="float: right;" class="buttons-ui">
                                    <a onclick="savePrice()" class="btn green">Save</a>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                            <div id="tablepriceid"></div>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function ClearRecipeup() {
    $("#productidup").val("0");
    $("#allDetailsup tbody").html("");
    $("#recipenameup").val("");
    $("#priceup").val("0");
    $("#qtyup").val("0");
}
function ClearRecipe() {
    $("#productid").val("0");
    $("#allDetails tbody").html("");
    $("#recipename").val("");
    $("#pricen").val("0");
    $("#qty").val("0");
    $("#Image").val("");
}



function saveRecipe() {


    if ($("#recipename").val().length <= 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Recipe Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#pricen").val() == 0 || $("#pricen").val().length == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Price!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }



    var data = { "info": [], "posid": $("#posid").val(), "name": $("#recipename").val(), "price": $("#pricen").val() };
    var info = '';

    $('#allDetails tbody tr').each(function() {

        info = '{"id":"' + this.id.replace("tr", "") + '"';
        $(this).children("td").each(function(index) {

            if (index == 2) {
                info += ',"qty":"' + $(this).text().trim() + '"';
            }

        });
        info += '}';
        data["info"].push(JSON.parse(info));
    });

    if (data["info"].length == 0) {
        swal({
            title: "upps, Sorry",
            text: "You must add at least one item to save!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveRecipe",
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
                    text: "Price Saved!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Price was not Saved! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
}
function updateRecipe() {


    if ($("#recipenameup").val().length <= 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Recipe Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#priceup").val() == 0 || $("#priceup").val().length == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Price!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }



    var data = { "info": [], "recipeid": $("#recipeid").val(), "name": $("#recipenameup").val()};
    var info = '';

    $('#allDetailsup tbody tr').each(function() {

        info = '{"id":"' + this.id.replace("trup", "") + '"';
        $(this).children("td").each(function(index) {

            if (index == 2) {
                info += ',"qty":"' + $(this).text().trim() + '"';
            }

        });
        info += '}';
        data["info"].push(JSON.parse(info));
    });

    if (data["info"].length == 0) {
        swal({
            title: "upps, Sorry",
            text: "You must add at least one item to save!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/updateRecipe",
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
                    text: "Price Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Price was not Updated! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
}

function deleteitem(id) {

    $("#tr" + id).remove();
    totalcostoC();
}
function deleteitemup(id) {

    $("#trup" + id).remove();
    totalcostoUP();
}

function savePrice() {
    if ($("#newprice").val().length == 0 || $("#newprice").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Price!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/savePrice",
        data: { "price": $("#newprice").val(), "itemid": $("#itemPosIdP").val(),"type":0 },
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Price Saved!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Price was not Saved! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }
        }
    });
}

function showupdate(id, nombre, active, price) {
    ClearRecipeup();
    $("#recipenameup").val(nombre);
    $("#recipeid").val(id);
    $("#priceup").val(price);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/recipeInfo",
        data: { "id": id },
        success: function(msg) {
            $("#allDetailsup").append(msg['html']);
            totalcostoUP();
        }
    });

}

function totalcostoC() {
    var total = 0;
    $('#allDetails tbody tr').each(function() {


        $(this).children("td").each(function(index) {

            if (index == 4) {

                total = total + parseFloat($(this).text().trim());
            }

        });

    });

    $("#totalC").val(parseFloat(total));
}
function totalcostoUP() {
    var total = 0;
    $('#allDetailsup tbody tr').each(function() {


        $(this).children("td").each(function(index) {

            if (index == 4) {

                total = total + parseFloat($(this).text().trim());
            }

        });

    });

    $("#totalCup").val(parseFloat(total));
}
function showPrice(id, name, active, price) {

    $("#recipenameP").val(name);
    $("#newprice").val(0.00);
    $("#itemPosIdP").val(id);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/pricehistory",
        data: { "itemPosId": id, "type": 0 },
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            $("#tablepriceid").html(msg['html']);
        }
    });

}
function addProduct() {
    id = $("#productid").val();

    if (id == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Product!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#qty").val() == 0 || $("#qty").val().length == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Quantity!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#tr" + id).attr('id') != undefined) {
        swal({
            title: "upps, Sorry",
            text: "This ingredient is already included!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    var costo = 0;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/Productinfo",
        data: { "itemid": id },
        success: function(msg) {
            costo = (new Intl.NumberFormat("en-US").format(($("#qty").val() * msg['unitary'])));
            $("#allDetails").append('<tr id="tr' + (id) + '"> <td> ' + msg['name'] + '</td><td> ' + msg['UnitName'] + '</td><td> ' + $("#qty").val() + '</td><td> ' + msg['unitary'] + '</td><td> ' + costo + '</td> <td><a id="' + (id) + '" onclick="deleteitem(this.id);"> <i class="fa fa-trash-o"></i></a></td></tr>');
            $("#productid").val("0");
            $("#qty").val("0");
            totalcostoC();
        }
    });

}
function addProductup() {

    id = $("#productidup").val();

    if (id == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Product!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#qtyup").val() == 0 || $("#qtyup").val().length == 0) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Quantity!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#trup" + id).attr('id') != undefined) {
        swal({
            title: "upps, Sorry",
            text: "This ingredient is already included!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    var costo = 0;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/Productinfo",
        data: { "itemid": id },
        success: function(msg) {
            costo = (new Intl.NumberFormat("en-US").format(($("#qtyup").val() * msg['unitary'])));
            $("#allDetailsup").append('<tr id="trup' + (id) + '"> <td> ' + msg['name'] + '</td><td> ' + msg['UnitName'] + '</td><td> ' + $("#qtyup").val() + '</td><td> ' + msg['unitary'] + '</td><td> ' + costo + '</td> <td><a id="up' + (id) + '" onclick="deleteitemup(this.id);"> <i class="fa fa-trash-o"></i></a></td></tr>');
            $("#productidup").val("0");
            $("#qtyup").val("0");
            totalcostoUP();
        }
    });

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
    $('#recList').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>