<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Manage Users</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div style="float: right;" class="buttons-ui">
        <a onclick="adduser()" class="btn blue">Add User</a>
    </div>
    <div class="clearfix"></div>
</div>
<div id="adduserid" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create a Employee</h4>
            </div>
            <div>
                <div class="graph-form">
                    <form id="EmployeeC">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">First Name</label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="First Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Last Name</label>
                            <input style="background:white; color:black;" name="lastname" id="lastname" type="text" placeholder="Last Name" required="">
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
                            <a onclick="saveStaff()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
function adduser() {
    $("#adduserid").modal();
}
//addnewuser
</script>