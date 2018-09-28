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
            <li class="active">Inventory</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <!--<a href="#createproduct" data-toggle="modal" class="btn blue">Add New Products</a>-->
        <a onclick="StockActual()" class="btn green">Receiving Product</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="proList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>Unit</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th width="10%">Min Stock</th>
                            <th width="10%">Actual Stock</th>
                            <th>Price</th>
                            <th>Imagen</th>
                            <th width="5%">Status</th>
                            <th width="5%">Change of Price</th>
                            <th width="5%">kardex</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllInventory)>0) {

                            $i=0;
                            foreach ($AllInventory as  $value) {
                                $i++;
                                $update="'".$value['name']."','".$value['itemPosId']."','".$value['itemcategoryid']."','".$value['type']."','".
                                $value['brand']."','".$value['model']."','".$value['stock']."','".$value['unitid']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td> 
                                <td> '.$value['Categoryname'].'  </td> <td>'.$value['unitname'].'</td><td>'.$value['brand'].'</td> <td>'.$value['model'].'</td> <td>'.$value['stock'].'</td> <td>'.$value['existencia'].'</td> <td>'.$value['price'].'</td> <td> <img id="img'.$value['itemPosId'].'" width="50px" src="'.$value['photo'].'" > </td> <td>'.($value['active']==1?'Active':'Deactive').'</td> <td> <a href="#priceh" onclick =" showPrice('.$update.')" data-toggle="modal"> <i class="fa fa-edit"></i></a></td> <td><a  onclick ="showkardex('."'".$value['itemPosId']."','".$value['name']."'".')"><i class="fa fa-inbox" aria-hidden="true"></i></a></td> </tr>   ';
                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllInventory)==0) {echo '<h4>No Products Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createproduct" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="ProductC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Product Name</label>
                            <input style="background:white; color:black;" name="productname" id="productname" type="text" placeholder="Product Name" required="">
                        </div>
                        <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Type of Category </label>
                            <select style="width: 100%; padding: 9px;" name="Categoryid" id="Categoryid">
                                <?php

                                    echo '<option value="0" >Select a Type of Category</option>';
                                    foreach ($AllCategories as $value) {
                                        $i++;
                                        echo '<option  value="'.$value['itemcategoryID'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Type of Product </label>
                            <select style="width: 100%; padding: 9px;" name="type" id="type">
                                <?php

                                    echo '<option value="0" >Select a Type of Product</option>';
                                    echo '<option  value="1" >Product</option>';
                                    echo '<option  value="2" >Recipe</option>';
                                    echo '<option  value="3" >Service</option>';

                              ?>
                            </select>
                        </div>
                          <div class="col-md-12 form-group1 form-last">
                            <label style="padding:4px;" class="control-label controls">Unit of Measurement</label>
                            <select style="width: 100%; padding: 9px;" name="unitid" id="unitid">
                                <?php

                                    echo '<option value="0" >Select a Unit of Measurement</option>';
                                    foreach ($AllUnits as $value) {
                                        $i++;
                                        echo '<option  value="'.$value['unitid'].'" >'.$value['name'].'</option>';
                                    }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Brand</label>
                            <input style="background:white; color:black;" name="brand" id="brand" type="text" placeholder="Brand" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Model</label>
                            <input style="background:white; color:black;" name="model" id="model" type="text" placeholder="Model" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Stock</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="stock" id="stock" type="text" placeholder="Stock" required="">
                        </div>
                          <div  class="col-md-12 form-group1">
                            <label class="control-label">Price</label>
                            <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="pricen" id="pricen" type="text" placeholder="Price" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Imagen</label>
                            <input style="background:white; color:black;" type="file" id="Image" name="Image">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveProduct()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="stockactual" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              
                <h4 class="modal-title">Receiving Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
              
                    <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="height:2px;">
                                <th  style="text-align:center; width:10%;">#</th>
                                <th>Product</th>
                                <th  style="text-align:center; width:20%;" >Receive product</th>

                            </tr>
                        </thead>
                        <tbody>
            
                        <?php
                    
                            if (count($AllInventory)>0) {
                                
                                $i=0;
                                foreach ($AllInventory as  $value) {
                                    $i++;
                                    echo ' <tr scope="row" class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th>  <td>'.$value['name'].'</td> <td style="text-align:center;"><a onclick="showNewStock('."'".$value['itemPosId']."','".$value['name']."'".')"><i class="fa fa-upload" aria-hidden="true"></i></a></td> </tr>  ';

                                }
                            }
                        ?>
                                </tbody> </table> </div></div></div>
                   
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
                                <label class="control-label">Product Name</label>
                                <input style="background:white; color:black;" id="productnameP" type="text" placeholder="Product Name" required="" readonly="true">
                            </div>
                            <div class="col-md-4 form-group1">
                                <label class="control-label">Imagen </label>
                                <img style="width:100px;" src="" id="photoP">
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
                             <div  style="float: right;"  class="buttons-ui">
                                <a onclick="savePrice()" class="btn green">Save</a>
                             </div>
                        </div>

                         
                        <div  class="clearfix"> </div>
                          <div  id="tablepriceid"></div>
                          
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
<div id="updatestock" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <center>
                <h4 class="modal-title">Update Stock</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" />
                <span aria-hidden="true">&times;</span> 
            </center>
            </div>
            <div class="graph-form">
                <center><h3><span id="producn"></span></h3></center>
                <form id="formstock" accept-charset="utf-8">
                    <input type="hidden" value="" id="productids" name="productids">       
                    <div class="col-md-12 form-group1">
                        <label class="control-label">New Stock</label>
                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="stocks" id="stocks" type="text" placeholder="New Stock" required="">
                    </div>
                    <div class="buttons-ui">
                        <a onclick="saveStock()" class="btn blue">Update Stock</a>
                    </div> 
                </form>
                 
            </div>
        </div>  
    </div>
</div>
<div id="showkardex" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              
                <h4 class="modal-title">Kardex</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            
            <div class="graph-form">
                <center><h4><span id="namep"></span></h4></center> 
                <div id="infokardex">
                    
                </div>
            </div>
                    
        </div>
    </div>
</div>
<script type="text/javascript">
    function showkardex(id,namep)
    {  

        $("#namep").html(namep);

        $("#showkardex").modal().fadeIn('slow/4000', () =>{
             $.ajax({
            type: "POST",
            //dataType: "json",
            url: "<?php echo lang_url(); ?>pos/showstock",
            data: {'id':id},
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                unShowWait();
                   $("#infokardex").html(msg);
                   $('#myTable2').DataTable();
                }
            });
        });
    }
     function StockActual()
     {
        $("#stockactual").modal().fadeIn('slow/4000');
     }
     function showNewStock(id,name)
     {  $("#producn").html(name);
        $("#productids").val(id);
        $("#stocks").val(0);
        $("#updatestock").modal().fadeIn('slow/4000');
         
     }
     function saveStock()
     {

        if($("#productids").val().length==0 || $("#productids").val()==0)
        {
            swal({
                    title: "upps, Sorry",
                    text:'Missing Field Stock',
                    icon: "warning",
                    button: "Ok!",
                });

            return;
        }
        var data = $("#formstock").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>pos/saveStock",
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
                        text: "Stock Updated!",
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
    function savePrice()
    {
        if ($("#newprice").val().length == 0 || $("#newprice").val()==0 ) {
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
            data: {"price":$("#newprice").val(),"itemid":$("#itemPosIdP").val(),"type":1 },
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
    function saveProduct() {



        var data = new FormData($("#ProductC")[0]);
        if ($("#productname").val().length < 3) {
            swal({
                title: "upps, Sorry",
                text: "Missing Field Product Name!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        } else if ($("#Categoryid").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Category to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        } else if ($("#type").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Type to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        } else if ($("#unitid").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Unit of Measurement!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        }
        else if ($("#pricen").val().length == 0 || $("#pricen").val()==0 ) 
        {
            swal({
                title: "upps, Sorry",
                text: "Missing Field Price!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        }  
        else if ($("#Image").val().length < 1) {
            swal({
                title: "upps, Sorry",
                text: "Missing Field Imagen!",
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
            url: "<?php echo lang_url(); ?>pos/saveProduct",
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
                        text: "Product Created!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        location.reload();
                    });
                } else {

                    swal({
                        title: "upps, Sorry",
                        text: "Product was not Created! Error:" + msg["result"],
                        icon: "warning",
                        button: "Ok!",
                    });
                }





            }
        });


    }

    function updateProduct() {



        var data = new FormData($("#ProductUP")[0]);
        if ($("#productnameup").val().length < 3) {
            swal({
                title: "upps, Sorry",
                text: "Missing Field Product Name!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        } else if ($("#Categoryidup").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Category to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        } else if ($("#typeup").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Type to Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        }
        else if ($("#unitidup").val() == 0) {
            swal({
                title: "upps, Sorry",
                text: "Select a Unit of Measurement!",
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
            url: "<?php echo lang_url(); ?>pos/updateProduct",
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
                        text: "Product Updated!",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        location.reload();
                    });
                } else {

                    swal({
                        title: "upps, Sorry",
                        text: "Product was not Updated!" + msg["result"],
                        icon: "warning",
                        button: "Ok!",
                    });
                }





            }
        });

    }

    function showupdate(nombre, id, category, type, brand, model, stock,unitid) {

        $("#productnameup").val(nombre);
        $("#itemPosId").val(id);
        $("#Categoryidup").val(category);
        $("#typeup").val(type);
        $("#brandup").val(brand);
        $("#modelup").val(model);
        $("#stockup").val(stock);
        $("#unitidup").val(unitid);
        $("#photo").attr('src', $("#img" + id).attr('src'));
    }

    function showPrice(nombre, id, category, type, brand, model, stock) {

      $("#productnameP").val(nombre);
      $("#newprice").val(0.00);
      $("#photoP").attr('src', $("#img" + id).attr('src'));
      $("#itemPosIdP").val(id);

      $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>pos/pricehistory",
            data: {"itemPosId":id,"type":1},
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


    function list()
    {
          $('#myTable').DataTable();
          
    }
    $(document).ready(function() {

      list();
        
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