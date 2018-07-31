<div class="outter-wp">


      <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li><a><?= $Posinfo['description']?></a></li>
            <li class="active">Categories</li>
        </ol>
    </div>

    <div >
      <?php include("menu.php") ?>
    </div>

    <div style="float: right; " class="buttons-ui">
        <a href="#createcategory"  data-toggle="modal" class="btn blue">Add New Categories</a>
    </div>
    <div class="clearfix" ></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="posList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Photo</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                           <?php if (count($AllCategories)>0) {

                            $i=0;
                            foreach ($AllCategories as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['name'].'  </td> 
                                <td> <img id="img'.$value['itemcategoryID'].'" width="50px" src="'.$value['photo'].'" > </td> <td>'.($value['active']==1?'Active':'Deactive').'</td> <td><a href="#updatecategory" onclick =" showupdate('."'".$value['name']."'".','.$value['itemcategoryID'].')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllCategories)==0) {echo '<h4>No Categories Created!</h4>';} ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div id="createcategory" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Create a Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="CategoryC" >
                    <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Category Name</label>
                        <input style="background:white; color:black;" name="categoryname" id ="categoryname" type="text" placeholder="Category Name" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >Imagen</label>
                        <input style="background:white; color:black;"   type="file" id="Image" name="Image">
                    </div>
                    <div id="respuesta"></div>
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="saveCategory()" class="btn green">Save</a>
                        </div>
                        

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<div id="updatecategory" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   
                    <h4 class="modal-title">Update a Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="categoryup" onsubmit="" >
                    <input type="hidden" name="posidup" value="<?=$Posinfo['myposId']?>">
                    <input type="hidden" name="itemcategoryidup" id="itemcategoryidup" value="">
                    <div class="col-md-6 form-group1">
                        <label class="control-label" >Category Name</label>
                        <input style="background:white; color:black;" name="categorynameup" id ="categorynameup" type="text" placeholder="Table Name" required="">
                    </div>
                    <div class="col-md-12 form-group1">
                        <label class="control-label" >New Imagen</label>
                        <input style="background:white; color:black;"   type="file" id="photoup" name="photoup">
                    </div>
                     <div class="col-md-12 form-group1">
                        <label class="control-label" >Actual Imagen</label>
                        <img width="250px" src="" alt="" id="photo">
                    </div>

                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="UpdateCategory()" class="btn green">Update</a>
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


    function saveCategory(){

 
       
      var data =new FormData($("#CategoryC")[0]);
        if($("#categoryname").val().length <3  ){
             swal({
               title: "upps, Sorry",
                text: "Missing Field Category Name!",
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
          url: "<?php echo lang_url(); ?>pos/saveCategory",
          data: data,  beforeSend:function() {
          showWait();
          setTimeout(function() {unShowWait();}, 10000);
        }
          ,
          success: function(msg) {

             if (msg["result"]=="0") {
              swal({
               title: "Success",
                text: "Category Created!",
                icon: "success",
                button: "Ok!",}).then((n)=>{
                  location.reload();
                });
            }
            else {
              
              swal({
               title: "upps, Sorry",
                text: "Category was not Created! Error: " + msg["result"],
                icon: "warning",
                button: "Ok!",});
            }

            unShowWait();



          }
      });

          
    }

    function UpdateCategory()
    {
       var data =new FormData($("#categoryup")[0]);
        if($("#categorynameup").val().length <3  ){
             swal({
               title: "upps, Sorry",
                text: "Missing Field Category Name!",
                icon: "warning",
                button: "Ok!",});
                return;
        }



          $.ajax({
          type: "POST",
          dataType: "json",
          contentType: false,
          processData:false,
          url: "<?php echo lang_url(); ?>pos/updateCategory",
          data: data,  beforeSend:function() {
          showWait();
          setTimeout(function() {unShowWait();}, 10000);
        }
          ,
          success: function(msg) {

             if (msg["result"]=="0") {
              swal({
               title: "Success",
                text: "Category Update!",
                icon: "success",
                button: "Ok!",}).then((n)=>{
                  location.reload();
                });
            }
            else {
              
              swal({
               title: "upps, Sorry",
                text: "Category was not Update! Error:" + msg["result"],
                icon: "warning",
                button: "Ok!",});
            }

            unShowWait();



          }
      });
        
    }
    function showupdate(nombre,id)
    {

        $("#categorynameup").val(nombre);
        $("#itemcategoryidup").val(id);
        $("#photo").attr('src',$("#img"+id).attr('src'));
    }

    

</script>