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
                <form id="formuserinfo" >
                    <div class="tab-main">
                        <div class="tab-inner">
                            <div id="tabs" class="tabs">
                                <div class="">
                                    <nav>
                                        <ul>
                                            <li><a onclick="showtab(1);" class="icon-shop tab"><i class="fa fa-info-circle"></i> <span>User Informations</span></a></li>
                                            <li><a onclick="showtab(2);" class="icon-cup"><i class="fa fa-check-square"></i> <span>Hotel Access</span></a></li>
                                            <li><a onclick="showtab(3);" class="icon-food"><i class="fa fa-check-square-o"></i> <span>Options Access</span></a></li>
                                        </ul>
                                    </nav>
                                    <div class="content tab">
                                      
                                                <section id="section-1" class="content-current sec">
                                                    <div class="graph">
                                                        <div style="text-align: center">
                                                            <h3>User Details</h43></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Firts Name</label>
                                                            <input style="background:white; color:black;" name="fname" id="fname" type="text" placeholder="Firts Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Last Name</label>
                                                            <input style="background:white; color:black;" name="lname" id="lname" type="text" placeholder="Last Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Email</label>
                                                            <input style="background:white; color:black;" name="email" id="email" type="text" placeholder="Email" onblur="return validaremail()" required="">
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </div>
                                                    <div class="clearfix"> </div>
                                                     <div class="graph">
                                                         <div style="text-align: center">
                                                            <h3>Access Information</h3></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">User Name</label>
                                                            <input style="background:white; color:black;" name="username" id="username" type="text" placeholder="User Name" onblur="return validarusername()" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Password</label>
                                                            <input style="background:white; color:black;" name="password" id="password" type="text" placeholder="Password" onblur="return verifica_clave()" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Confirm Password</label>
                                                            <input style="background:white; color:black;" name="repassword" id="repassword" type="text" placeholder="Confirnm Password" onblur="return clavesiguales() " required="">
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </div>
                                                </section>
                                                <section id="section-2" class="sec">
                                                    <h4>Hotel Access</h4>
                                                    <?php
                                             echo '<div class="graph" >';
                                                        $hoteles=get_data('manage_hotel',array('owner_id' => $user_id ))->result_array();
                                                        
                                                        foreach ($hoteles as $hotel) {

                                                           echo '<div class="col-md-6">
                                                           <input type="checkbox" name="hotelid[]" id="hotelid" value="'.$hotel['hotel_id'].'" >
                                                                    <label for="brand"><span></span>'.$hotel['property_name'].'.</label>
                                                                    </div>';
                                                        }
                                                        echo ' <div class="clearfix"> </div> </div>';
                                            ?>
                                                </section>
                                                <section id="section-3" class="sec">
                                                    <h4>Options Access</h4>
                                                    <?php
                                                        $item1=1;
                                                       $menudata= get_data('menuitem', array('active'=>1))->result_array();
                                                        
                                                        foreach ($menudata as  $value) {
                    
                                                            if($item1 !=$value['order1']){echo ' <div class="clearfix"> </div> </div>';}


                                                            if ($value['order2']==0 && $value['order3']==0) {
                                                                echo '<div class="graph" >';

                                                                echo '<div class="col-md-6">
                                                                    <input onchange="changeval(this.id)" id="item'.$value['order1'].'" type="checkbox" name="menuitemid[]" value="'.$value['menuitemid'].'" >
                                                                    <label for="brand"><span></span><strong>'.$value['description'].'</strong></label>
                                                                    </div>';
                                                                $sub=0;
                                                                $item1=$value['order1'];
                                                            }
                                                            else if($value['order2']>0 && $value['order3']==0)
                                                            {   
                                                                 echo '<div class="col-md-6">
                                                                    <input class="item'.$value['order1'].'" onchange="checkmain('.$value['order1'].',this.checked)" type="checkbox" id="menuitemid" name="menuitemid[]" value="'.$value['menuitemid'].'" >
                                                                    <label for="brand"><span></span>'.$value['description'].'</label>
                                                                    </div>';
                                                            }

                                                        }
                                                      
                                                        echo ' <div class="clearfix"> </div> </div>';

    
                                            ?>
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
                        <a onclick="saveUser()" class="btn green">Save</a>
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
function saveUser() {
        var email = document.getElementById('email');
        var usern = document.getElementById('username');
         var cad = document.getElementById('password');
         var cad2 = document.getElementById('repassword');

      if ($("#fname").val().length < 2) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Firts Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#lname").val().length < 2) {
        swal({
            title: "upps, Sorry",
            text: "Missing Last Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }else if ($("#email").val().length < 3 || !email.checkValidity() ) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Email!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
    else if ($("#username").val().length < 1 || !usern.checkValidity()) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field User Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if ($("#password").val().length < 3 || !cad.checkValidity()) {
        swal({
            title: "upps, Sorry",
            text: "Wrong Password!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if ($("#repassword").val().length < 3 || !cad2.checkValidity()) {
        swal({
            title: "upps, Sorry",
            text: "Wrong Confirnm Password!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if (!$("input[id=hotelid]").is(":checked")) {
        swal({
            title: "upps, Sorry",
            text: "Select a Hotel Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if (!$("input[id=hotelid]").is(":checked")) {
        swal({
            title: "upps, Sorry",
            text: "Select a Hotel Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
   
}

function showtab(id) {
    $(".sec").removeClass("content-current");
    $("#section-" + id).addClass("content-current");
}

function checkmain(id, value) {

    if ($("#item" + id).prop("checked") == false && value) {
        $("#item" + id).prop("checked", true);
    }

}

function changeval(id) {

    if ($("input[class=" + id + "]").is(":checked")) {
        $("#" + id).prop("checked", true);
    }

}

function adduser() {
    $("#adduserid").modal();
}

function validaremail() {


    var email = document.getElementById('email');
    var emailval = $("#email").val();

    email.setCustomValidity("Test");

    if (emailval.length == 0) {
        return false;
    }

    if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(emailval) == false) {

        email.setCustomValidity("This Email is not valid");
        return false;

    }

    var data = { "email": emailval };
    $.ajax({
        type: "POST",
        url: '<?php echo lang_url(); ?>channel/emailused',
        data: data,

        success: function(html) {

            if (html.trim() != '0') {
                email.setCustomValidity("This Email already exists");
                return false;
            } else {
                email.setCustomValidity("");
                return true;
            }
        }
    });
}

function validarusername() {
    var usern = document.getElementById('username');
    usern.setCustomValidity("");
    var username = $("#username").val();
    if (username.length == 0) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: '<?php echo lang_url(); ?>channel/usernameused',
        data: { "username": username },
         beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(html) {
            unShowWait();
            if (html.trim() != 0) {
                usern.setCustomValidity("This UserName already exists");
                return false;
            } else {
                usern.setCustomValidity("");
                return true;
            }
        }
    });
}

function verifica_clave() {
    var cadena = $("#password").val();
    var cad = document.getElementById('password');

    if (cadena.length == 0) { return false; }
    var expresionR = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)/;
    var resultado = expresionR.test(cadena);

    if (resultado != true || cadena.length < 8) {
        $('#msguser').html('');
        cad.setCustomValidity("Minimum 8 characters without spaces, must include uppercase, lowercase and numbers");
        return false;
    } else {
        cad.setCustomValidity("");
        return true;
    }
}

function clavesiguales() {

    var cad2 = document.getElementById('repassword');

    if ($("#password").val() != $("#repassword").val()) {
        cad2.setCustomValidity("You must repeat the same password");
        return false;
    } else {
        cad2.setCustomValidity("");
        return true;
    }
}
//addnewuser
</script>