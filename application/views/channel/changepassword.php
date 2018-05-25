<style type="text/css" media="screen">
.errores {
    box-shadow: 0 0 5px 1px red;
}

input:focus.invalid {
    outline: none;
}
</style>
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>

    <div class="col-md-6 graph-2">
        <h3 class="inner-tittle two">Change of Password </h3>
        <div id="msguser" class="alert alert-danger" style="display: none; text-align: center;">
            <strong>Danger!</strong> Invalid Password.
        </div>
        <form onsubmit="return changepass()" id="formchange" class="form-horizontal">
            <div class="grid-1">
                <div class="form-body">
                    <div class="form-group">
                        <label for="currentpassword" class="col-sm-4 control-label">Current Password</label>
                        <div class="col-sm-6">
                            <input onclick="changeread(this.id)" onblur="changeunread(this.id)" readonly="true" type="password" class="form-control" id="currentpassword" name="currentpassword" placeholder="Password" required=""> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newpassword" class="col-sm-4 control-label">New Password</label>
                        <div class="col-sm-6">
                            <input onclick="changeread(this.id)" onblur="verifica_clave(this.id)" readonly="true" type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" required=""> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="repassword" class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-6">
                            <input onclick="changeread(this.id)" onblur="clavesiguales(this.id)" readonly="true" type="password" class="form-control" id="repassword" name="repassword" placeholder="Confirm Password" required=""> 
                        </div>
                    </div>
                </div>
                <div class="col-sm-offset-2" style="text-align: center;">
                    <button type="submit" class="btn btn-default">Change Password</button>
                </div>
            </div>
        </form>
        
    </div>
</div>

</div>
</div>

<script type="text/javascript" charset="utf-8" async defer>
        var base_url = '<?php echo lang_url();?>';

        function changepass() {

            if ($("#currentpassword").val().length == 0) {

                $('#msguser').html('Enter your current password');
                $('#msguser').toggle("slow");
                setTimeout(function() {
                    $('#msguser').fadeOut();
                }, 5000);
                $("#currentpassword").addClass("errores");

                return false;
            }
            if ($("#newpassword").val().length == 0) {

                $('#msguser').html('Enter your New password');
                $('#msguser').toggle("slow");
                setTimeout(function() {
                    $('#msguser').fadeOut();
                }, 5000);
                $("#newpassword").addClass("errores");

                return false;
            }
            if ($("#repassword").val().length == 0) {

                $('#msguser').html('Enter your Confirm password');
                $('#msguser').toggle("slow");
                setTimeout(function() {
                    $('#msguser').fadeOut();
                }, 5000);
                $("#repassword").addClass("errores");

                return false;
            }
            $.ajax({
                type: "POST",
                url: base_url + 'channel/changerPassword',
                data: $("#formchange").serialize(),
                success: function(html) {
                    if (html == 0) {
                        swal({
                            title: "Done!",
                            text: "Password Update Successfully!",
                            icon: "success",
                            button: "Ok!",
                        }).then(ms => {
                            $("#formchange")[0].reset();
                        });
                    } else if (html == 1) {
                        $('#msguser').html('Enter your Confirm password');
                        $('#msguser').toggle("slow");
                        setTimeout(function() {
                            $('#msguser').fadeOut();
                        }, 5000);
                        $("#repassword").addClass("errores");

                        return false;
                    } else if (html == 2) {
                        $('#msguser').html('Enter your Current password Correct');
                        $('#msguser').toggle("slow");
                        setTimeout(function() {
                            $('#msguser').fadeOut();
                        }, 5000);
                        $("#currentpassword").addClass("errores");

                        return false;
                    } else {
                        alert(html);
                    }

                }
            });
            return false;

        }

        function changeread(valu) {

            $("#" + valu).prop("readonly", false);
        }

        function changeunread(valu) {

            $("#" + valu).prop("readonly", true);
            $("#currentpassword").removeClass("errores");
        }

        function verifica_clave(valu) {

            var cadena = $("#newpassword").val();
            var cad = document.getElementById('newpassword');

            if (cadena.length == 0) { $("#" + valu).prop("readonly", true); return false; }
            var expresionR = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)/;
            var resultado = expresionR.test(cadena);

            if (resultado != true || cadena.length < 8) {
                $("#newpassword").addClass("errores");
                cad.setCustomValidity("Minimum 8 characters without spaces, must include uppercase, lowercase and numbers");
                return false;
            } else {
                cad.setCustomValidity("");
                $("#newpassword").removeClass("errores");
                $("#" + valu).prop("readonly", true);
                return true;
            }
        }

        function clavesiguales(valu) {


            var cad2 = document.getElementById('repassword');

            if ($("#newpassword").val() != $("#repassword").val()) {
                cad2.setCustomValidity("You must repeat the same password");

                $("#repassword").addClass("errores");
                return false;
            } else {
                cad2.setCustomValidity("");
                $("#repassword").removeClass("errores");
                $("#" + valu).prop("readonly", true);
                return true;
            }
        }
</script>
