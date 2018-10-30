 <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center><h3 class="modal-title"><span class="label label-primary">Bulk Update Competitive Set Analysis</span></h3></center>
               <a class="close" onclick="closebulk()"><i class="fas fa-times-circle" ></i></a>
            </div>
            <div>
                <div class="graph-form">
                    <form id="BulkUpdateF">
                        <input type="hidden" name="jsoninfo" id="jsoninfo">
                        <input type="hidden" name="channel_id" id="channel_id">
                          <div class="col-md-12">
                                <?php
                                    echo '<div class="panel-default"> <div class="panel-heading"> <h3 class="panel-title">Channels</h3> </div>';

                                    echo '<div class="panel-body"><div class="col-md-4 ">
                                            <table>
                                            <tbody>

                                                <tr>
                                                    <td><input class="channelid" type="checkbox" name="channelid[]" id="channelid0" value="0" checked></td><td><label for="channelid0" >&nbsp Hoteratus</label></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>';

                                    $AllChannelConected=$this->db->query("SELECT a.*,b.channel_name 
                                    FROM user_connect_channel a
                                    left join manage_channel b on a.channel_id=b.channel_id
                                    where hotel_id=".hotel_id()." and a.channel_id <>39 order by b.channel_name")->result_array();
                                    foreach ($AllChannelConected as $channel) {

                                        echo '<div class="col-md-4 ">
                                                     <table>
                                                        <tbody>
                                                        <tr>
                                                        <td><input class="channelid" type="checkbox" name="channelid[]" id="channelid'.$channel['channel_id'].'" value="'.$channel['channel_id'].'" checked ></td>
                                                        <td><label for="channelid'.$channel['channel_id'].'">&nbsp '.$channel['channel_name'].'</label></td>
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
                                        <div id="allDate" class="table-responsive">
                                            <div class="form-group1 col-md-6">
                                                <label class="control-label"><strong>Start Date</strong></label>

                                                <input onchange="cambio(1)" class="date1 blue datepickers" style="background:white; color:black; text-align: center;" type="text" required="" name="date1Edit[]" id="date1">

                                            </div>
                                            <div class="form-group1 col-md-6">
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
                        <div class="buttons-ui">
                            <a onclick="SaveBulkUpdate()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" charset="utf-8" async defer>
    $('.datepickers').datepicker({minDate:new Date(),dateFormat: 'yy-mm-dd',});

    var cantidad = 1;
    var fecha = new Date($.now());
    function addDate() {
    cantidad++;
    $("#allDate").append('<hr style="border:2px;"> <h3>Range Date ' + cantidad + '</h3> <div class="form-group1 col-md-6"> <label class="control-label"><strong>Start Date</strong></label><input onchange="cambio(' + cantidad + ')" class="date1 blue datepickers" style="background:white; color:black; text-align: center;" type="text"  required="" id="date' + cantidad + '" name="date1Edit[]" > </div><div class="form-group1 col-md-6">   <label class="control-label"><strong>End Date</strong></label><input class="date2 blue datepickers" style="background:white; color:black; text-align: center;" type="text"  required="" id="date' + cantidad + 's" name="date2Edit[]" > </div>');
    $('.datepickers').datepicker({minDate:new Date(),dateFormat: 'yy-mm-dd',});
    setcalendar(cantidad);
        
   
    }
    function cambio(id) {
    var fecha = new Date($("#date" + id).val());
    var dias = 2; // Número de días a agregar
    fecha.setDate(fecha.getDate() + dias);
        $("#date" + id + "s").attr('min', formatoDate(fecha));
        $("#date" + id + "s").val(formatoDate(fecha));
    }

    function setcalendar(id) {
        
        var dias = 1; // Número de días a agregar
        $("#date" + id).datepicker({minDate: formatoDate(fecha)});
        //$("#date1Edit").val(formatoDate(fecha));
        fecha.setDate(fecha.getDate() + dias);
        $("#date" + id + "s").attr('min', formatoDate(fecha));
        //$("#date2Edit").val(formatoDate(fecha));
    }

    jQuery(document).ready(function($) {
        setcalendar(1);
    });
 function closebulk()
 {
    $("#bulkupdate").css('display','none');
 }
</script>