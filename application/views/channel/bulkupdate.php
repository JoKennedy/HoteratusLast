<div class="outter-wp" >
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
                                    <td><input class="channelid" type="checkbox" name="channelid[]" id="channelid" value="0" checked></td><td><label >&nbsp Hoteratus</label></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>';



                    foreach ($AllChannelConected as $channel) {

                        echo '<div class="col-md-12 ">
                                     <table>
                                        <tbody>
                                        <tr>
                                        <td><input class="channelid" type="checkbox" name="channelid[]" id="channelid" value="'.$channel['channel_id'].'" checked ></td>
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
                        <div id="allDate" style="height:400px; overflow:auto;">
                            <div class=" form-group1">
                                <label class="control-label"><strong>Start Date</strong></label>

                                <input onchange="cambio(1)" class="date1 blue datepickers" style="background:white; color:black; text-align: center;" type="text" required="" name="date1Edit[]" id="date1">

                            </div>
                            <div class=" form-group1">
                                <label class="control-label"><strong>End Date</strong></label>
                                <input class="date2 blue datepickers" style="background:white; color:black; text-align: center;" type="text" class="btn blue datepickers" required="" id="date1s" name="date2Edit[]">
                            </div>
                        </div>
                        <div class="buttons-ui">
                            <a onclick="addDate()" class="btn blue">Add More</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" >
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
                                                <td><input onchange="showcol(this)" type="checkbox" name="opt[]" id="opt" value="'.$key.'" ></td>
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
                                 <table  class="table">
                                    <tbody>';
                    $i=0;
                    foreach ($Rooms as $value) {
                             $i++;   
                               
                                echo '<tr class="'.($i%2?'active':'success').'"> 
                                            <td width="10%" >'.$value['property_name'].'</td> 

                                            <td  class="form-group1 availa" style="display:none;width:18%; " id="availa">
                                                <input width="100px" style="background:white; color:black;" onkeypress="return justNumbers(event);" name="room['.$value['property_id'].'][availability]" id="availability" type="text" placeholder="Availability" onchange="return validarmaximo('.$value['existing_room_count'].',this);" >
                                            </td>
                                            <td  class="form-group1 price" style="display:none;width:15%;" id="pricet">
                                                <input  style="background:white; color:black;  " onkeypress="return justNumbers(event);" name="room['.$value['property_id'].'][price]" id="price" type="text" placeholder="Price" >
                                            </td>
                                            <td  class="form-group1 minimum" style="display:none;width:18%;" id=minimumt>
                                                <input  style="background:white; color:black; " name="room['.$value['property_id'].'][minimumstay]" id="minimum" type="text" placeholder=Minimum Stay" >
                                            </td>
                                            <td width="14%" class="form-group1 cta" style="display:none; text-align:center;" id="ctat">
                                                <label for="cta" >CTA</label> <br>
                                                <input  name="room['.$value['property_id'].'][cta]" id="cta" type="radio" value="1" >
                                                <label for="cta">Y</label>
                                                <input   name="room['.$value['property_id'].'][cta]" id="cta" type="radio" value="0" >
                                                <label for="cta">N</label>
                                            </td>
                                            <td width="14%" class="form-group1 ctd" style="display:none; text-align:center;" id="ctdt">
                                                <label for="ctd" >CTD</label> <br>
                                                <input  name="room['.$value['property_id'].'][ctd]" id="ctd" type="radio" value="1" >
                                                <label for="ctd">Y</label>
                                                <input name="room['.$value['property_id'].'][ctd]" id="ctd" type="radio" value="0" >
                                                <label for="ctd">N</label>
                                            </td>
                                            <td width="15%" class="form-group1 stops" style="display:none; text-align:center;" id="sst">
                                                <label for="stops" >Stop Sales</label> <br>
                                                <input  name="room['.$value['property_id'].'][stops]" id="stops" type="radio" value="1" >
                                                <label for="stops">Y</label>
                                                <input  name="room['.$value['property_id'].'][stops]" id="stops" type="radio" value="0" >
                                                <label for="stops">N</label>
                                            </td> 
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
 var falta = 0;
   $('.datepickers').datepicker({minDate:new Date(),dateFormat: 'yy-mm-dd',});
function showcol(id)
{
    var va= id.value;
    var type=(va==1?'availa':(va==2?'price':(va==3?'minimum':(va==4?'cta':(va==5?'ctd':'stops')))))
    $("."+type).css({
        display: (id.checked?'':'none')
    });


    
}
function addDate() {
    cantidad++;
    $("#allDate").append('<hr style="border:2px;"> <h3>Range Date ' + cantidad + '</h3> <div class=" form-group1"> <label class="control-label"><strong>Start Date</strong></label><input onchange="cambio(' + cantidad + ')" class="date1 blue datepickers" style="background:white; color:black; text-align: center;" type="text"  required="" id="date' + cantidad + '" name="date1Edit[]" > </div><div class=" form-group1">   <label class="control-label"><strong>End Date</strong></label><input class="date2 blue datepickers" style="background:white; color:black; text-align: center;" type="text"  required="" id="date' + cantidad + 's" name="date2Edit[]" > </div>');
    setcalendar(cantidad);
    $('.datepickers').datepicker({minDate:new Date(),dateFormat: 'yy-mm-dd',});
    //$('#pagina').( 650 * (cantidad - 1));
    $('#pagina').css('height', '800');

}


function setcalendar(id) {
    var fecha = new Date($.now());
    var dias = 1; // Número de días a agregar
    $("#date" + id).datepicker({minDate: formatoDate(fecha)});
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
function verificarupdate()
{
    if($("td[id=availa]").css("display")!='none' && $("input[id=availability]").val()==""  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Type the availability to continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
            
    }
     if($("td[id=pricet]").css("display")!='none' && $("input[id=price]").val()==""  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Type the Price to continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
    }
     if($("td[id=minimumt]").css("display")!='none' && $("input[id=minimum]").val()==""  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Type the Minimum to continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
    }
     if($("td[id=ctat]").css("display")!='none' && !$("input[id=cta]").is(":checked")  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select a CTA Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
    }
     if($("td[id=ctdt]").css("display")!='none' && !$("input[id=ctd]").is(":checked")  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select a CTD Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
    }
     if($("td[id=sst]").css("display")!='none' && !$("input[id=stops]").is(":checked")  )
    {      
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select a Stop Sales Continue !",
                icon: "warning",
                button: "Ok!",
            });
            return false;
    }

}
function sendbulk() {
    falta = 0;

    if(verificarupdate()) return;


    if(!$("input[id=opt]").is(":checked"))
    {
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select what you want to update!",
                icon: "warning",
                button: "Ok!",
            });
            return;
    }

    if(!$("input[id=days]").is(":checked"))
    {
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select a Day To Continue!",
                icon: "warning",
                button: "Ok!",
            });
            return;
    }
      $(".date2").each(function(index, el) {
        if ($(el).val() == '') {
            $(el).focus();

            swal({
                title: "upps, Sorry",
                text: "Complete a Date Range To Continue!",
                icon: "warning",
                button: "Ok!",
            })
            return;
        }


    });


    $(".date1").each(function(index, el) {
        if ($(el).val() == '') {
            $(el).focus();
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Complete a Date Range To Continue!",
                icon: "warning",
                button: "Ok!",
            });

            return;
        }


    });

   if(!$("input[id=channelid]").is(":checked"))
    {
            falta=1;
            swal({
                title: "upps, Sorry",
                text: "Select a Channel To Continue!",
                icon: "warning",
                button: "Ok!",
            });
    }
    
       


if(falta==1)return;
    var data = $("#bulkinfo").serialize();
    $.ajax({
        type: "POST",
        //dataType: "json",
        url: "<?php echo lang_url(); ?>bulkupdate/bulkUpdateProcess",
        data: data,
        beforeSend: function() {
           
            $("#mensagesincro").css("display","");
            swal({
                title: "Proccess",
                text: "Update sent, when it is completed a message will be displayed on top!",
                icon: "info",
                button: "Ok!",
            });
        },
        
        success: function(msg) {
           alert(msg);
        }
    });


}
function validarmaximo(maximo,id)
{
    if (maximo<$(id).val()) {

        $(id).val(maximo);

    }
}
setcalendar(1);
</script>