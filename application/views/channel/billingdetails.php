<div class="outter-wp">
    <!--sub-heard-part-->

    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Billing Details</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    
    <?php 

    $company_name=(isset($BillInfo['company_name'])?$BillInfo['company_name']:'');
    $town=(isset($BillInfo['town'])?$BillInfo['town']:'');
    $address=(isset($BillInfo['address'])?$BillInfo['address']:'');
    $zip_code=(isset($BillInfo['zip_code'])?$BillInfo['zip_code']:'');
    $mobile=(isset($BillInfo['mobile'])?$BillInfo['mobile']:'');
    $vat=(isset($BillInfo['vat'])?$BillInfo['vat']:'');
    $reg_num=(isset($BillInfo['reg_num'])?$BillInfo['reg_num']:'');
    $email_address=(isset($BillInfo['email_address'])?$BillInfo['email_address']:'');
    $country=(isset($BillInfo['country'])?$BillInfo['country']:'');
    $Logo=(isset($BillInfo['Logo'])?$BillInfo['Logo']:'');
    ?>
    <div class="graph-form">
                    <form id="BillingInfo">

                        <div class="col-md-6 form-group1">
                            <label class="control-label">Company Name</label>
                            <input style="background:white; color:black;" name="cname" id="cname" type="text" placeholder="Company Name" value="<?=$company_name?>" required="" class="required">
                        </div>

                        <div class="col-md-6 form-group1">
                            <label class="control-label">City</label>
                            <input style="background:white; color:black;" name="city" id="city" type="text" placeholder="City" required="" value="<?=$town?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label style="padding:4px;" class="control-label controls">Country </label>
                            <select style="width: 100%; padding: 9px;" name="country" id="country" >
                                <?php

                                                                        echo '<option value="0" >Select a Country</option>';
                                                                        foreach ($Allcountry as $value) {
                                                                                $i++;
                                                                                echo '<option  value="'.$value['id'].'" '.($country==$value['id']?'selected':'').' >'.$value['country_name'].'</option>';
                                                                        }
                                                            ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Billing Address</label>
                            <input style="background:white; color:black;" name="address" id="address" type="text" placeholder="Billing Address" required="" value="<?=$address?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Zip Code</label>
                            <input style="background:white; color:black;" name="zipcode" id="zipcode" type="text" placeholder="Zip Code" required="" value="<?=$zip_code?>">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone Number</label>
                            <input style="background:white; color:black;" name="phone" id="phone" type="text" placeholder="Phone Number" required="" value="<?=$mobile?>">
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label">VAT </label>
                            <input style="background:white; color:black;" name="vat" id="vat" type="text" placeholder="Vat" required="" value="<?=$vat?>">
                        </div>
                         <div class="col-md-6 form-group1">
                            <label class="control-label">Registration Number </label>
                            <input style="background:white; color:black;" name="rnumber" id="rnumber" type="text" placeholder="Registration Number " required="" value="<?=$reg_num?>">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Billing Email</label>
                            <input style="background:white; color:black;" onkeyup="return validaemail(this.id)" name="bemail" id="bemail" type="text" placeholder="Billing Email" required=""  value="<?=$email_address?>">
                        </div>
                        <center>
                            <div class="col-md-3">
                                        <div>
                                            <?php
                                                echo '<img src="'.base_url().(strlen($Logo)<5?"uploads/room_photos/noimage.jpg":$Logo).'"" class="img-responsive" alt="">'
                                              ?>
                                            
                                        </div>
                            </div>
                            </center>
                            <div class="col-md-12 form-group1">

                                     <label class="control-label">Imagen</label>
                                    <input style="background:white; color:black;" type="file" id="Image" name="Image">
                            </div>
                        
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveBilling()" class="btn green">Update</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
    </div>

</div>
</div>
</div>
<script type="text/javascript">
function saveBilling()
{ 

    var email = document.getElementById('bemail');


    if ($("#cname").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Company Name!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#cname").focus();
        });

        return;
    } else if ($("#city").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: City!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#city").focus();
        });
        return;
    }else if ($("#country").val() == 0) {
        swal({
            title: "Oops, Sorry",
            text: "Select a country to continue!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#country").focus();
        });
        return;
    }else if ($("#address").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Address!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#address").focus();
        });
        return;
    }else if ($("#phone").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Phone Number!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#phone").focus();
        });
        return;
    }else if ($("#vat").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: VAT!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#vat").focus();
        });
        return;
    }else if ($("#rnumber").val().length < 1) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Registration Number!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#rnumber").focus();
        });
        return;
    }
    else if ($("#bemail").val().length < 1 || !validaemail('bemail')) {
        swal({
            title: "Oops, Sorry",
            text: "Missing Field: Email!",
            icon: "warning",
            button: "Ok!",
        }).then((n) => {
            $("#bemail").focus();
        });
        return;
    }

     
         $.ajax({
            type: "POST",
            dataType: "json",
            contentType: false,
            processData: false,
            url: "<?php echo lang_url(); ?>channel/saveBillingDetails",
            data:  new FormData($("#BillingInfo")[0]),
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                console.log(msg);
                unShowWait();
                if (msg["success"]) {
                    swal({
                        title: "Success",
                        text: "Billing Details "+(msg["value"]==1?'Created':'Updated')+" !",
                        icon: "success",
                        button: "Ok!",
                    }).then((n) => {
                        location.reload();
                    });
                } else {

                    swal({
                        title: "Oops, Sorry",
                        text: "Billing Details Did not Update! Error:"+msg["message"],
                        icon: "warning",
                        button: "Ok!",
                    });
                }





            }
        });


}

function validaemail(id) {


    var email = document.getElementById(id);
    var emailval = $("#"+id).val();

    if (emailval.length == 0) {
        return false;
    }

    if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(emailval) == false) {

        email.setCustomValidity("This Email is not valid");
        return false;

    } 
    else{
        email.setCustomValidity("");
        return true;
    }   
}  

  
</script>