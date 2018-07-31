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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>   
                
            </div>


                <div class="graph-form">
                    <form id="EmployeeC">
                       <div class="tab-main">
                            <div class="tab-inner">
                                <div id="tabs" class="tabs">
                                    <div class="">
                                        <nav >
                                            <ul>
                                                <li><a onclick="showtab(1);" class="icon-shop tab"><i class="fa fa-info-circle"></i> <span>User Informations</span></a></li>
                                                <li><a onclick="showtab(2);" class="icon-cup"><i class="fa fa-check-square"></i> <span>Hotel Access</span></a></li>
                                                <li><a onclick="showtab(3);" class="icon-food"><i class="fa fa-check-square-o"></i> <span>Options Access</span></a></li>
                                            </ul>
                                        </nav>
                                        <div class="content tab">
                                            <section  id="section-1" class="content-current sec">
                                                <div style="text-align: center"><h4>User Details</h4></div>
                                                
                                            </section>
                                            <section id="section-2" class="sec">
                                                <h4>Hotel Access</h4>
                                            </section>
                                            <section id="section-3" class="sec">
                                                <h4>Options Access</h4>
                                            </section>

                                        </div>
                                        <!-- /content -->
                                    </div>
                                    <!-- /tabs -->
                                </div>
                            </div>
                           
                        </div>
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
</div>

<script type="text/javascript">
function showtab(id)
{
    $(".sec").removeClass("content-current");
    $("#section-"+id).addClass("content-current");
}
function adduser() {
    $("#adduserid").modal();
}
//addnewuser
</script>