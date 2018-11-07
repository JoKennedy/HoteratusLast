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
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="userList" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th style="text-align:center;">Edit</th>
                            <th width="5%" style="text-align:center;">Activate/Inactivate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($UsersInfo)>0) {

                                                        $i=0;
                                                        foreach ($UsersInfo as  $value) {
                                                                $i++;
                                                                $update="'".$value['user_id']."','".$value['fname']."','".$value['lname']."','".$value['email_address']."','".$value['user_name']."','".$value['menuitemids']."','".$value['hotelids']."'";

                                                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['fullname'].'  </td> 
                                                                <td> '.$value['user_name'].'  </td>  <td> '.$value['email_address'].'  </td>
                                                                <td> '.($value['status']==1?'Active':'Deactive').'</td> <td style="text-align:center;"><a   onclick =" showupdate('.$update.')" ><i class="fa fa-cog"></i></a></td> 
                                                                <td style="text-align:center;"><a  onclick =" activeInactive('.$value['user_id'].','.$value['status'].')" ><i class="fa fa-unlock-alt"></i></a></td>
                                                                </tr>   ';

                                                        }
                                                } ?>
                    </tbody>
                </table>
                <?php if (count($UsersInfo)==0) {echo '<h4>No User Created!</h4>';} 
                                else
                                { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                                    ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="adduserid" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a New User</h4>
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
                                            <li><a onclick="showtab(3);" class="icon-food"><i class="fa fa-check-square"></i> <span>Options Access</span></a></li>
                                        </ul>
                                    </nav>
                                    <div class="content tab">
                                      
                                                <section id="section-1" class="content-current sec">
                                                    <div class="graph">
                                                        <div style="text-align: center">
                                                            <h3>User Details</h43></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">First Name</label>
                                                            <input style="background:white; color:black;" name="fname" id="fname" type="text" placeholder="Firts Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Last Name</label>
                                                            <input style="background:white; color:black;" name="lname" id="lname" type="text" placeholder="Last Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Email</label>
                                                            <input style="background:white; color:black;" name="email" id="email" type="text" placeholder="Email" onblur="return validaremail('email')" required="">
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </div>
                                                    <div class="clearfix"> </div>
                                                     <div class="graph">
                                                         <div style="text-align: center">
                                                            <h3>Access Information</h3></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Username</label>
                                                            <input style="background:white; color:black;" name="username" id="username" type="text" placeholder="User Name" onblur="return validarusername('username')" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Password</label>
                                                            <input style="background:white; color:black;" name="password" id="password" type="password" placeholder="Password" onblur="return verifica_clave('password')" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Confirm Password</label>
                                                            <input style="background:white; color:black;" name="repassword" id="repassword" type="password" placeholder="Confirnm Password" onblur="return clavesiguales('password','repassword') " required="">
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

                                                           echo '<div class="col-md-6 checkbox">
                                                           <label>
                                                           <input type="checkbox" name="hotelid[]" id="hotelid" value="'.$hotel['hotel_id'].'" >
                                                                    '.$hotel['property_name'].'.</label>
                                                                    </div>';
                                                        }
                                                        echo ' <div class="clearfix"> </div> </div>';
                                            ?>
                                                </section>
                                                <section id="section-3" class="sec">
                                                    <h4>Options Access</h4>
                                                    <?php
                                                        $item1=1;
                                                        $main=0;
                                                       $menudata= $this->db->query("select * from menuitem where active=1 order by order1,order2,order3")->result_array();

                                                        
                                                        foreach ($menudata as  $value) {
                    
                                                            if($item1 !=$value['order1']){echo ' <div class="clearfix"> </div> </div>';}


                                                            if ($value['order2']==0 && $value['order3']==0) {
                                                                echo '<div class="graph" >';

                                                                echo '<div class="col-md-6 checkbox">
                                                                    <label >
                                                                    <input class="'."main".$value['order1'].'" onchange="'.($value['menuitemid']==1?'this.checked=!this.checked;':'changeval('."'item".$value['order1']."'".',this)').'" id="menuitemid" type="checkbox" name="menuitemid[]" value="'.$value['menuitemid'].'" '.($value['menuitemid']==1?'checked':'').'>
                                                                    <strong>'.$value['description'].'</strong></label>
                                                                    </div>';
                                                                $sub=0;
                                                                $item1=$value['order1'];
                                                                $main=$value['menuitemid'];
                                                            }
                                                            else if($value['order2']>0 && $value['order3']==0)
                                                            {   
                                                                 echo '<div class="col-md-6 checkbox">
                                                                 <label>
                                                                    <input class="item'.$value['order1'].'" onchange="checkmain('."'main".$value['order1']."'".',this.checked)" type="checkbox" id="menuitemid" name="menuitemid[]" value="'.$value['menuitemid'].'" >
                                                                    '.$value['description'].'</label>
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
<div id="updateuserid" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update a User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div class="graph-form">
                <form id="formuserinfoup" >
                    <input type="hidden" name="useridup" id="useridup" value="">
                    <div class="tab-main">
                        <div class="tab-inner">
                            <div id="tabs" class="tabs">
                                <div class="">
                                    <nav>
                                        <ul>
                                            <li><a onclick="showtab2(1);" class="icon-shop tab"><i class="fa fa-info-circle"></i> <span>User Informations</span></a></li>
                                            <li><a onclick="showtab2(2);" class="icon-cup"><i class="fa fa-check-square"></i> <span>Hotel Access</span></a></li>
                                            <li><a onclick="showtab2(3);" class="icon-food"><i class="fa fa-check-square"></i> <span>Options Access</span></a></li>
                                        </ul>
                                    </nav>
                                    <div class="content tab">
                                      
                                                <section id="section2-1" class="content-current sec2">
                                                    <div class="graph">
                                                        <div style="text-align: center">
                                                            <h3>User Details</h43></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Firts Name</label>
                                                            <input style="background:white; color:black;" name="fnameup" id="fnameup" type="text" placeholder="Firts Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Last Name</label>
                                                            <input style="background:white; color:black;" name="lnameup" id="lnameup" type="text" placeholder="Last Name" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Email</label>
                                                            <input style="background:white; color:black;" name="emailup" id="emailup" type="text" placeholder="Email" onblur="return validaremail('emailup')" required="">
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </div>
                                                    <div class="clearfix"> </div>
                                                     <div class="graph">
                                                         <div style="text-align: center">
                                                            <h3>Access Information</h3></div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Username</label>
                                                            <input style="background:white; color:black;" name="usernameup" id="usernameup" type="text" placeholder="User Name" onblur="return validarusername('usernameup')" required="" readonly="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Password</label>
                                                            <input style="background:white; color:black;" name="passwordup" id="passwordup" type="password" placeholder="Password" onblur="return verifica_clave('passwordup')" required="">
                                                        </div>
                                                        <div class="col-md-12 form-group1">
                                                            <label class="control-label">Confirm Password</label>
                                                            <input style="background:white; color:black;" name="repasswordup" id="repasswordup" type="password" placeholder="Confirnm Password" onblur="return clavesiguales('passwordup',repasswordup') " required="">
                                                        </div>
                                                        <div class="clearfix"> </div>
                                                    </div>
                                                </section>
                                                <section id="section2-2" class="sec2">
                                                    <h4>Hotel Access</h4>
                                                    <?php
                                                         echo '<div class="graph" >';
                                                    
                                                        
                                                        foreach ($hoteles as $hotel) {

                                                           echo '<div class="col-md-6 checkbox">
                                                           <label>
                                                           <input type="checkbox" name="hotelidup[]" id="hotelidup" value="'.$hotel['hotel_id'].'" >
                                                                    '.$hotel['property_name'].'.</label>
                                                                    </div>';
                                                        }
                                                        echo ' <div class="clearfix"> </div> </div>';
                                            ?>
                                                </section>
                                                <section id="section2-3" class="sec2">
                                                    <h4>Options Access</h4>
                                                    <?php
                                                        $item1=1;
                                                    
                                                        
                                                        foreach ($menudata as  $value) {
                    
                                                            if($item1 !=$value['order1']){echo ' <div class="clearfix"> </div> </div>';}


                                                            if ($value['order2']==0 && $value['order3']==0) {
                                                                echo '<div class="graph" >';

                                                                echo '<div class="col-md-6 checkbox">
                                                                    <label>
                                                                    <input class="'."mains".$value['order1'].'" onchange="'.($value['menuitemid']==1?'this.checked=!this.checked;':'changeval('."'items".$value['order1']."'".',this)').'" id="menuitemidup" type="checkbox" name="menuitemidup[]" value="'.$value['menuitemid'].'" '.($value['menuitemid']==1?' checked':'').' >
                                                                    <strong>'.$value['description'].'</strong></label>
                                                                    </div>';
                                                                $sub=0;
                                                                $item1=$value['order1'];
                                                            }
                                                            else if($value['order2']>0 && $value['order3']==0)
                                                            {   
                                                                 echo '<div class="col-md-6 checkbox">
                                                                    <label>
                                                                    <input class="items'.$value['order1'].'" onchange="checkmain('."'mains".$value['order1']."'".',this.checked)" type="checkbox" id="menuitemidup" name="menuitemidup[]" value="'.$value['menuitemid'].'" >'.$value['description'].'</label>
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
                        <a onclick="updateUser()" class="btn green">Save</a>
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
            title: "Oops, Sorry",
            text: "Missing Field: Email!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
    else if ($("#username").val().length < 1 || !usern.checkValidity()) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Username!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if ($("#password").val().length < 3 || !cad.checkValidity()) {
        swal({
            title: "Oops, Sorry",
            text: "Wrong Password!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if ($("#repassword").val().length < 3 || !cad2.checkValidity()) {
        swal({
            title: "Oops, Sorry",
            text: "Wrong Passport, please confirnm Password!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if (!$("input[id=hotelid]").is(":checked")) {
        swal({
            title: "Oops, Sorry",
            text: "Select a Hotel Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if (!$("input[id=menuitemid]").is(":checked")) {
        swal({
            title: "Oops, Sorry",
            text: "Select a Menu Item Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    var data=$("#formuserinfo").serialize();
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/savenewuserassg",
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
                    text: "User Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "Oops, Sorry",
                    text: msg["msg"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });
   
}

function showupdate(id,fname,lname,email,username,menuid,hotelid)
{
    $("#fnameup").val(fname);
    $("#lnameup").val(lname);
    $("#emailup").val(email);
    $("#usernameup").val(username);
    $("#useridup").val(id);


    var hotelarray=hotelid.split(",");
     var menuarray=menuid.split(",");
    var valori=0;

     $("input[id=hotelidup").each(function (index) {  
       valori=$(this).val();
       id=this;

       hotelarray.forEach( function(valor, indice, array) {

             if(valori==valor)
             {
                id.checked=1 
             }
        });

    });
    $("input[id=menuitemidup]").each(function (index) {  
       valori=$(this).val();
       id=this;

       menuarray.forEach( function(valor, indice, array) {

             if(valori==valor)
             {
                id.checked=1 
             }
        });

    });

    $("#updateuserid").modal();  

}
function updateUser() {
    var email = document.getElementById('emailup');
    var usern = document.getElementById('usernameup');

      if ($("#fnameup").val().length < 2) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: First Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#lnameup").val().length < 2) {
        swal({
            title: "Oops, Sorry",
            text: "Missing field: Last Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }else if ($("#emailup").val().length < 3 || !email.checkValidity() ) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Email!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

     else if (!$("input[id=hotelidup]").is(":checked")) {
        swal({
            title: "Oops, Sorry",
            text: "Select a Hotel Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }
     else if (!$("input[id=menuitemidup]").is(":checked")) {
        swal({
            title: "Oops, Sorry",
            text: "Select a Menu Item Access to continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }

    var data=$("#formuserinfoup").serialize();
        $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>channel/updatenewuserassg",
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
                    text: "User Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: msg["msg"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });
   
}
function activeInactive(id,status)
{
    swal({
      title: "Are you sure?",
      text: "You will change the status of this user!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo lang_url(); ?>channel/updatenewuserassgActive",
                data: {'userid':id,'status':status},
                beforeSend: function() {
                    showWait();
                    setTimeout(function() { unShowWait(); }, 10000);
                },
                success: function(msg) {
                    unShowWait();
                    if (msg["success"]) {
                        swal("User "+(status==0?'activated':'disabled'), {
                          icon: "success",
                        }).then((n) => {
                                location.reload();
                        });
                    } else {

                        swal({
                            title: "upps, Sorry",
                            text: msg["msg"],
                            icon: "warning",
                            button: "Ok!",
                        });
                    }
                }
            });

      } else {
        swal("No change made!");
      }
    });
}
function showtab(id) {
    $(".sec").removeClass("content-current");
    $("#section-" + id).addClass("content-current");
}
function showtab2(id) {
    $(".sec2").removeClass("content-current");
    $("#section2-" + id).addClass("content-current");
}
function checkmain(id, value) {

    if ( $("input[class=" + id + "]").is(":checked") == false && value) {
        $("input[class=" + id + "]").prop("checked", true);
    }

}

function changeval(id,opt) { 

    if ($("input[class=" + id + "]").is(":checked")) {
        $(opt).prop("checked", true);
    }

}

function adduser() {
    $("#adduserid").modal();
}

function validaremail(id) {


    var email = document.getElementById(id);
    var emailval = $("#"+id).val();

    if (emailval.length == 0) {
        return false;
    }

    if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(emailval) == false) {

        email.setCustomValidity("This Email is not valid");
        return false;

    }

    
}

function validarusername(id) {
    var usern = document.getElementById(id);
    usern.setCustomValidity("");
    var username = $("#"+id).val();
    if (username.length == 0) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: '<?php echo lang_url(); ?>channel/usernameused',
        data: { "username": username },
        success: function(html) {
          
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

function verifica_clave(id) {
    var cadena = $("#"+id).val();
    var cad = document.getElementById(id);

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

function clavesiguales(id,id2) {

    var cad2 = document.getElementById(id);

    if ($("#"+id).val() != $("#"+id2).val()) {
        cad2.setCustomValidity("You must repeat the same password");
        return false;
    } else {
        cad2.setCustomValidity("");
        return true;
    }
}
//addnewuser
</script>