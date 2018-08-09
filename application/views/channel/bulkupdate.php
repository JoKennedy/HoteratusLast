<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">Calendar</a></li>
            <li class="active">Bulk Update</li>
        </ol>
    </div>
    <hr>
    <form accept-charset="utf-8" id="bulkinfo">
        <div class="col-md-3">
            <div class="col-md-12">
                <?php
                    echo '<div class="panel-default"> <div class="panel-heading"> <h3 class="panel-title">Channels</h3> </div>';

                    echo '<div class="panel-body"><div class="col-md-12 ">
                            <table>
                            <tbody>

                                <tr>
                                    <td><input type="checkbox" name="channelid[]" id="channelid" value="0" checked></td><td><label >&nbsp Hoteratus</label></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>';



                    foreach ($AllChannelConected as $channel) {

                        echo '<div class="col-md-12 ">
                                     <table>
                                        <tbody>
                                        <tr>
                                        <td><input type="checkbox" name="channelid[]" id="channelid" value="'.$channel['channel_id'].'" checked ></td>
                                        <td><label>&nbsp '.$channel['channel_name'].'</label></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>';
                    }
                    echo ' </div><div class="clearfix"> </div> </div>';



                    ?>
            </div>
            <div class="col-md-12">
                <div class="panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dates</h3> </div>
                    <div class="panel-body">
                        <div id="allDate" style="height:300px; overflow:auto;">
                            <div class=" form-group1">
                                <label class="control-label"><strong>Start Date</strong></label>
                                <input onchange="cambio(1)" class="date1" style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" name="date1Edit[]" id="date1">
                            </div>
                            <div class=" form-group1">
                                <label class="control-label"><strong>End Date</strong></label>
                                <input class="date2" style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" id="date1s" name="date2Edit[]">
                            </div>
                        </div>
                        <div class="buttons-ui">
                            <a onclick="addDate()" class="btn blue">Add More</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php
                echo '<div class="panel-default"> <div class="panel-heading"> <h3 class="panel-title">Days Of The Week</h3> </div> ';
                 echo '<div class="panel-body">';
                $days = array("Sunday"=>1, "Monday"=>2, "Tuesday"=>3, "Wednesday"=>4,"Thursday"=>5,"Friday"=>6, "Saturday"=>7);
                
                foreach ($days as $day => $key) {

                                echo '<div class="col-md-12 ">
                                             <table>
                                                <tbody>
                                                <tr>
                                                <td><input type="checkbox" name="days[]" id="days" value="'.$key.'" checked></td>
                                                <td><label>&nbsp '.$day.'</label></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>';
                            }
                            echo ' <div class="clearfix"> </div> </div> </div>';


                        ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">What do you want to update?</h3> </div>
                <div class="panel-body">
                    <?php
                        $disponible=array("1"=>"Availability","2"=>"Price","3"=>"Minimum stay","4"=>"CTA (Y-Yes/N-No)","5"=>"CTD (Y-Yes/N-No)","6"=>"Stop sell (Y-Yes/N-No)");

                      foreach ($disponible as $key => $opt) {

                                echo '<div class="col-md-4 ">
                                             <table>
                                                <tbody>
                                                <tr>
                                                <td><input type="checkbox" name="days[]" id="days" value="'.$key.'" ></td>
                                                <td><label>&nbsp '.$opt.'</label></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>';
                            }   

                    ?>
                       
                </div>
            </div>
            <div>
             <?php
                if(count($Rooms)>0)
                {
                    echo '<div class="col-md-12 graph">
                            <div class="tables ">
                                 <table class="table">
                                    <tbody>';
                    $i=0;
                    foreach ($Rooms as $value) {
                             $i++;   
                               
                                echo '<tr class="'.($i%2?'active':'success').'"> 
                                            <td width="10%">'.$value['property_name'].'<td> 
                                            <td class="form-group1 availa">
                                                <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="room['.$value['property_id'].'][availability]" id="availability" type="text" placeholder="Availability" >
                                            <td>
                                            <td class="form-group1">
                                                <input style="background:white; color:black;" onkeypress="return justNumbers(event);" name="room['.$value['property_id'].'][price]" id="price" type="text" placeholder="Price" >
                                            <td>
                                            <td class="form-group1">
                                                <input style="background:white; color:black;" name="room['.$value['property_id'].'][minimumstay]" id="signer" type="text" placeholder="Main Name" >
                                            <td>
                                            <td class="form-group1">
                                                <input style="background:white; color:black;" name="signer" id="signer" type="text" placeholder="Main Name" >
                                            <td>
                                            <td class="form-group1">
                                                <input style="background:white; color:black;" name="signer" id="signer" type="text" placeholder="Main Name" >
                                            <td>
                                            <td class="form-group1">
                                                <input style="background:white; color:black;" name="signer" id="signer" type="text" placeholder="Main Name" >
                                            <td> 
                                        </tr>';

                               }           
                                    
           
                    echo    '</tbody>
                                </table>
                            </div></div>';
                }
                else
                {
                    echo '<h3 style="text-align:center;">No Rooms Available</h3>';
                }
             ?>   
            </div>

        </div>
        <div class="buttons-ui">
            <a onclick="sendbulk()" class="btn blue">Update</a>
            <a class="btn red">Reset</a>
        </div>
    </form>
</div>
</div>
</div>
<script type="text/javascript">
var cantidad = 1;

function addDate() {
    cantidad++;
    $("#allDate").append('<hr style="border:2px;"> <h3>Range Date ' + cantidad + '</h3> <div class=" form-group1"> <label class="control-label"><strong>Start Date</strong></label><input onchange="cambio(' + cantidad + ')" class="date1" style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" id="date' + cantidad + '" name="date1Edit[]" > </div><div class=" form-group1">   <label class="control-label"><strong>End Date</strong></label><input class="date2" style="background:white; color:black; text-align: center;" type="date" class="btn blue" required="" id="date' + cantidad + 's" name="date2Edit[]" > </div>');
    setcalendar(cantidad);

    //$('#pagina').( 650 * (cantidad - 1));
    $('#pagina').css('height', '800');

}


function setcalendar(id) {
    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date" + id).attr('min', formatoDate(fecha));
    //$("#date1Edit").val(formatoDate(fecha));
    fecha.setDate(fecha.getDate() + dias);
    $("#date" + id + "s").attr('min', formatoDate(fecha));
    //$("#date2Edit").val(formatoDate(fecha));
}

function cambio(id) {
    var fecha = new Date($("#date" + id).val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
    $("#date" + id + "s").attr('min', formatoDate(fecha));
    $("#date" + id + "s").val(formatoDate(fecha));
}

function sendbulk() {
    falta = 0;

    $(".date1").each(function(index, el) {
        if ($(el).val() == '') {
            $(el).focus();
            swal({
                title: "upps, Sorry",
                text: "Complete a Date Range To Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        }


    });
    $(".date2").each(function(index, el) {
        if ($(el).val() == '') {
            $(el).focus();
            swal({
                title: "upps, Sorry",
                text: "Complete a Date Range To Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
        }


    });

    var data = $("#bulkinfo").serialize();
    $.ajax({
        type: "POST",
        //dataType: "json",
        url: "<?php echo lang_url(); ?>bulkupdate/bulkUpdateProcess",
        data: data,
        success: function(msg) {
            alert(msg);
        }
    });


}
setcalendar(1);
</script>