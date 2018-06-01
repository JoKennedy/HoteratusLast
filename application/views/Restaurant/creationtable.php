<div class="outter-wp">
      <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li><a><?= $Posinfo['description']?></a></li>
            <li class="active">Tables</li>
        </ol>
    </div>

    <div >
      <?php include("menu.php") ?>
    </div>
    <div style="float: right;" class="buttons-ui">
        <a href="#createtable"  data-toggle="modal" class="btn blue">Add New Table</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="posList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Table Name</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                           <?php if (count($AllTable)>0) {

                            $i=0;
                            foreach ($AllTable as  $value) {
                                $i++;
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['description'].'  </td> <td>'.$value['qtyPerson'].'</td> <td>'.($value['active']==1?'Active':'Deactive').'</td> <td><a href="#updatetable"                    onclick =" showupdate('."'".$value['description']."'".','.$value['qtyPerson'].','.$value['postableid'].')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }
                        } ?>
                    </tbody>
                </table>
                <?php if (count($AllTable)==0) {echo '<h4>No Table Created!</h4>';} ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div id="createtable" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Create a Table</h4>
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="tablecre" >
                    <input type="hidden" name="postid" value="<?=$Posinfo['myposId']?>">
                    <div class="col-md-6 form-group1">
                        <label class="control-label" >Table Name</label>
                        <input style="background:white; color:black;" name="tablename" id ="tablename" type="text" placeholder="Table Name" required="">
                    </div>
                    <div class="col-md-6 form-group1">
                        <label class="control-label" >Table Capacity of People</label>
                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="Capacity" id ="Capacity" type="text" placeholder="Capacity of People" required="">
                    </div>
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="saveTable()" class="btn green">Save</a>
                        </div>

                    <div class="clearfix"> </div>

                  </form>
                </div>
                </div>
            </div>
        </div>
</div>
<div id="updatetable" class="modal fade" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update a Table</h4>
                </div>
                <div>
                    <div class="graph-form">
                  <form id ="tableup" >
                    <input type="hidden" name="postidup" value="<?=$Posinfo['myposId']?>">
                    <input type="hidden" name="tableidup" id="tableidup" value="">
                    <div class="col-md-6 form-group1">
                        <label class="control-label" >Table Name</label>
                        <input style="background:white; color:black;" name="tablenameup" id ="tablenameup" type="text" placeholder="Table Name" required="">
                    </div>
                    <div class="col-md-6 form-group1">
                        <label class="control-label" >Table Capacity of People</label>
                        <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="Capacityup" id ="Capacityup" type="text" placeholder="Capacity of People" required="">
                    </div>
                    <div class="clearfix"> </div>
                    <br><br>
                        <div class="buttons-ui">
                        <a onclick="UpdateTable()" class="btn green">Update</a>
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

    function UpdateTable()
    {
        var data =$("#tableup").serialize();
        
        if($("#tablenameup").val().length <3  ){
             swal({
               title: "upps, Sorry",
                text: "Missing Field Table Name!",
                icon: "warning",
                button: "Ok!",});
                return;
        }
        else if ($("#Capacityup").val().length <1) {
            swal({
               title: "upps, Sorry",
                text: "Missing Field Capacity!",
                icon: "warning",
                button: "Ok!",});
                return;
        }

        
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo lang_url(); ?>pos/UpdateTable",
          data: data,
          beforeSend:function() {
          showWait();
          setTimeout(function() {unShowWait();}, 10000);
        }
          ,
          success: function(msg) {
            if (msg=="0") {
              swal({
               title: "Success",
                text: "Table Update!",
                icon: "success",
                button: "Ok!",}).then((n)=>{
                  location.reload();
                });
            }
            else {
              swal({
               title: "upps, Sorry",
                text: "Table was not Updated!",
                icon: "warning",
                button: "Ok!",});
            }

            unShowWait();


          }
      });

        
    }
    function showupdate(nombre,capacidad,id)
    {
        $("#tablenameup").val(nombre);
        $("#tableidup").val(id);
        $("#Capacityup").val(capacidad);
    }

    function saveTable() {

    var data =$("#tablecre").serialize()

        if($("#tablename").val().length <3  ){
             swal({
               title: "upps, Sorry",
                text: "Missing Field Table Name!",
                icon: "warning",
                button: "Ok!",});
                return;
        }
        else if ($("#Capacity").val().length <1) {
            swal({
               title: "upps, Sorry",
                text: "Missing Field Capacity!",
                icon: "warning",
                button: "Ok!",});
                return;
        }

        
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo lang_url(); ?>pos/saveTable",
          data: data,
          beforeSend:function() {
          showWait();
          setTimeout(function() {unShowWait();}, 10000);
        }
          ,
          success: function(msg) {
            if (msg=="0") {
              swal({
               title: "Success",
                text: "Table Created!",
                icon: "success",
                button: "Ok!",}).then((n)=>{
                  location.reload();
                });
            }
            else {
              swal({
               title: "upps, Sorry",
                text: "Table was not Created!",
                icon: "warning",
                button: "Ok!",});
            }

            unShowWait();


          }
      });

    }

</script>