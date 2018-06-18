
<div class="outter-wp">


      <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li><a><?= $Posinfo['description']?></a></li>
            <li class="active">Products</li>
        </ol>
    </div>

    <div >
      <?php include("menu.php") ?>
    </div>

    <div style="float: right; " class="buttons-ui">
        <a href="#createproduct"  data-toggle="modal" class="btn blue">Add New Products</a>
    </div>
    <div class="clearfix" ></div>
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
                            <th>Photo</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                           <?php if (count($ALLProducts)>0) {

                            $i=0;
                            foreach ($ALLProducts as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td> 
                                <td> '.$value['Categoryname'].'  </td> <td> <img id="img'.$value['itemPosId'].'" width="50px" src="'.$value['photo'].'" > </td> <td>'.($value['active']==1?'Active':'Deactive').'</td> <td><a href="#updateproduct" onclick =" showupdate('."'".$value['name']."'".','.$value['itemPosId'].','.$value['itemcategoryid'].','.$value['type'].')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($ALLProducts)==0) {echo '<h4>No Products Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-lg pager" id="myPager"></ul> </div>';}
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Create a Product</h4>
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="ProductC" >
                    <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Product Name</label>
                        <input style="background:white; color:black;" name="productname" id ="productname" type="text" placeholder="Product Name" required="">
                    </div>
                     <div class="col-md-12 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Type of Category </label>
                        <select style="width: 100%; padding: 9px;" name="Categoryid" id="Categoryid" >
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
                        <select style="width: 100%; padding: 9px;" name="type" id="type" >
                            <?php

                                    echo '<option value="0" >Select a Type of Product</option>';
                                    echo '<option  value="1" >Product</option>';
                                    echo '<option  value="2" >Recipe</option>';
                                    echo '<option  value="3" >Service</option>';

                              ?>
                        </select>
                    </div>

                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Imagen</label>
                        <input style="background:white; color:black;"   type="file" id="Image" name="Image">
                    </div>
                    <div id="respuesta"></div>
                    <div class="clearfix"> </div>
                    <br><br>
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
<div id="updateproduct" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update a Product</h4>
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="ProductUP" >
                    <input type="hidden" name="itemPosId" id="itemPosId" value="">
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Product Name</label>
                        <input style="background:white; color:black;" name="productnameup" id ="productnameup" type="text" placeholder="Product Name" required="">
                    </div>
                     <div class="col-md-12 form-group1 form-last">
                        <label style="padding:4px;" class="control-label controls">Type of Category </label>
                        <select style="width: 100%; padding: 9px;" name="Categoryidup" id="Categoryidup" >
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
                        <select style="width: 100%; padding: 9px;" name="typeup" id="typeup" >
                            <?php

                                    echo '<option value="0" >Select a Type of Product</option>';
                                    echo '<option  value="1" >Product</option>';
                                    echo '<option  value="2" >Recipe</option>';
                                    echo '<option  value="3" >Service</option>';

                              ?>
                        </select>
                    </div>

                    <div class="col-md-12 form-group1">
                        <label class="control-label" >New Imagen</label>
                        <input style="background:white; color:black;"   type="file" id="Imageup" name="Imageup">
                    </div>
                    <div id="respuesta"></div>
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Actual Imagen </label>
                       <img style="width:200px;" src="" id="photo">
                    </div>
                    
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="updateProduct()" class="btn green">Save</a>
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


    function saveProduct(){

 
       
      var data =new FormData($("#ProductC")[0]);
        if($("#productname").val().length <3  ){
             swal({
               title: "upps, Sorry",
                text: "Missing Field Product Name!",
                icon: "warning",
                button: "Ok!",});
                return;
        }
         else if($("#Categoryid").val()==0 ){
             swal({
               title: "upps, Sorry",
                text: "Select a Category to Continue!",
                icon: "warning",
                button: "Ok!",});
                return;
        }
        else if($("#type").val()==0  ){
             swal({
               title: "upps, Sorry",
                text: "Select a Type to Continue!",
                icon: "warning",
                button: "Ok!",});
                return;
        }
        else if ( $("#Image").val().length <1 ) {
            swal({
               title: "upps, Sorry",
                text: "Missing Field Imagen!",
                icon: "warning",
                button: "Ok!",});
                return;
        }

          $.ajax({
          type: "POST",
          dataType: "json",
          contentType: false,
          processData:false,
          url: "<?php echo lang_url(); ?>pos/saveProduct",
          data: data,  beforeSend:function() {
          showWait();
          setTimeout(function() {unShowWait();}, 10000);
        }
          ,
          success: function(msg) {
            unShowWait();
             if (msg["result"]=="0") {
              swal({
               title: "Success",
                text: "Product Created!",
                icon: "success",
                button: "Ok!",}).then((n)=>{
                  location.reload();
                });
            }
            else {
              
              swal({
               title: "upps, Sorry",
                text: "Product was not Created! Error:" + msg["result"],
                icon: "warning",
                button: "Ok!",});
            }

            



          }
      });

          
    }
    function updateProduct(){

 
       
        var data =new FormData($("#ProductUP")[0]);
          if($("#productnameup").val().length <3  ){
               swal({
                 title: "upps, Sorry",
                  text: "Missing Field Product Name!",
                  icon: "warning",
                  button: "Ok!",});
                  return;
          }
           else if($("#Categoryidup").val()==0 ){
               swal({
                 title: "upps, Sorry",
                  text: "Select a Category to Continue!",
                  icon: "warning",
                  button: "Ok!",});
                  return;
          }
          else if($("#typeup").val()==0  ){
               swal({
                 title: "upps, Sorry",
                  text: "Select a Type to Continue!",
                  icon: "warning",
                  button: "Ok!",});
                  return;
          }


            $.ajax({
            type: "POST",
            dataType: "json",
            contentType: false,
            processData:false,
            url: "<?php echo lang_url(); ?>pos/updateProduct",
            data: data,  beforeSend:function() {
            showWait();
            setTimeout(function() {unShowWait();}, 10000);
          }
            ,
            success: function(msg) {
             
              unShowWait();
               if (msg["result"]=="0") {
                swal({
                 title: "Success",
                  text: "Product Updated!",
                  icon: "success",
                  button: "Ok!",}).then((n)=>{
                    location.reload();
                  });
              }
              else {
                
                swal({
                 title: "upps, Sorry",
                  text: "Product was not Updated!" + msg["result"],
                  icon: "warning",
                  button: "Ok!",});
              }

              



            }
        });
    
    }
 
    function showupdate(nombre,id,category,type)
    {

        $("#productnameup").val(nombre);
        $("#itemPosId").val(id);
        $("#Categoryidup").val(category);
        $("#typeup").val(type);
        $("#photo").attr('src',$("#img"+id).attr('src'));
    }


    $.fn.pageMe = function(opts){
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
    
    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }
    
    if (typeof settings.pagerSelector!="undefined") {
        pager = $(settings.pagerSelector);
    }
    
    var numItems = children.size();
    var numPages = Math.ceil(numItems/perPage);

    pager.data("curr",0);
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }
    
    var curr = 0;
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }
    
    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages<=1) {
        pager.find('.next_link').hide();
    }
    pager.children().eq(1).addClass("active");
    
    children.hide();
    children.slice(0, perPage).show();
    
    pager.find('li .page_link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev_link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next_link').click(function(){
        next();
        return false;
    });
    
    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }
     
    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }
    
    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;
        
        children.css('display','none').slice(startAt, endOn).show();
        
        if (page>=1) {
            pager.find('.prev_link').show();
        }
        else {
            pager.find('.prev_link').hide();
        }
        
        if (page<(numPages-1)) {
            pager.find('.next_link').show();
        }
        else {
            pager.find('.next_link').hide();
        }
        
        pager.data("curr",page);
        pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");
    
    }
};

function Paginar(numeroP=10)
{
  $('#proList').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:numeroP});
}
$(document).ready(function(){
    
  Paginar(10);
    
});

</script>