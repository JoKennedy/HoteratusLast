<style type="text/css">
    .inline_username{
        color: black;
    }
</style>
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url(); ?>channel/dashboard">Home</a></li>
            <li class="active">Front Desk Calendar</li>
        </ol>
    </div>
    <!--//sub-heard-part-->
    <div style="float: left;" class="buttons-ui">
        <div class="col-md-7 form-group1">
            <select onchange="Calendario(0, 1)" style="width: 100%; padding: 9px;" name="monthid" id="monthid">
                <?php
                $showreservation = (isset($userConfig['CalenderShowR']) ? $userConfig['CalenderShowR'] : 0);

                $month = array("1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September", "10" => "October", "11" => "November", "12" => "December");
                $hoy = array('dia' => date('d'), 'mes' => date('m'), 'year' => date('Y'));
                foreach ($month as $key => $value) {
                    $i++;
                    echo '<option   value="' . $key . '"' . ($key == $hoy['mes'] ? 'selected' : '') . ' >' . $value . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-5 form-group1">
            <select onchange="Calendario(0, 1)" style="width: 100%; padding: 9px;" name="yearid" id="yearid">
                <?php
                $lastyear = date('Y', strtotime($YearM));

                for ($i = $hoy['year']; $i <= $lastyear; $i++) {
                    echo '<option  value="' . $i . '"' . ($i == $hoy['year'] ? 'selected' : '') . ' >' . $i . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div style="float: right;" class="buttons-ui">
        <?php
        $specialpermit = array();
        if ($User_Type == 2) {
            $specialpermit = specialpermitids();
        }

        //if ($User_Type == 1 || in_array(1, $specialpermit)) {
            echo '<a onclick="setfullUpdate()" class="btn orange">Full Update</a>';
        //}
       // if ($User_Type == 1 || in_array(2, $specialpermit)) {
            echo '<a href="' . base_url() . 'bulkupdate/viewBulkUpdate" class="btn green">Bulk Update</a>';
       // }
       // if ($User_Type == 1 || in_array(3, $specialpermit)) {
            echo '<a onclick="setcalendar()" class="btn blue">Add Reservation</a>';
       // }
        ?>
    </div>
    <div class="clearfix"></div>
    <div style="width: 100%; height:400px; padding-right:2%;" class="table-responsive">
        <div id="calendario"> </div>
        <!--<?= $calendar ?>-->
    </div>
    <div style="text-align: left;">
        <br>
        <div class="col-md-2">
            <label class="check">
                <input onclick=" Calendario(this.checked, 2)" id="show" type="checkbox" <?= ($showreservation == 0 ? '' : 'checked') ?>>Show Reservations</label>
        </div>
        <div class="col-md-2">
            <label class="check">
                <input onclick=" showoption('ss', this.checked)" id="Sales" type="checkbox">Stop Sales</label>
        </div>
        <div class="col-md-2">
            <label class="check">
                <input onclick=" showoption(this.id, this.checked)" id="cta" type="checkbox">CTA</label>
        </div>
        <div class="col-md-2">
            <label class="check">
                <input onclick=" showoption(this.id, this.checked)" id="ctd" type="checkbox">CTD</label>
        </div>


        <div class="clearfix"> </div>
    </div>
</div>
<div id="CreateReservation" class="modal fade" role="dialog" style="z-index: 1400;">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <?php include("creationreservation.php") ?>
        </div>
    </div>
</div>
<div id="fullupdate" class="modal fade" role="dialog" style="z-index: 1400;">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button id="idclose" type="button" class="close" data-dismiss="modal">&times;</button>
                <div align="center">
                    <h1><span class="label label-primary">Full Update</span></h1>
                </div>
            </div>
            <div class="modal-body">
                <form id="calendarfull"  accept-charset="utf-8">
                    <div class="col-md-12 ">
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>Start Date</strong></label>
                            <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="startdate" name="startdate">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label"><strong>End Date</strong></label>
                            <input style="background:white; color:black; text-align: center;" type="text" class="btn blue datepicker" required="" id="enddate" name="enddate">
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div align="center">
                            <h2><span class="label label-warning">Select the Channels</span></h3>
                        </div>
                        <?php
                        echo '<div class="graph" >';

                        if (count($AllChannel) > 0) {
                            foreach ($AllChannel as $channel) {

                                echo '<div class="col-md-6 checkbox">
	                           <label>
	                           <input type="checkbox" name="channel_id[]" id="channel_id" value="' . $channel['channel_id'] . '" checked>
	                                    ' . $channel['channel_name'] . '.</label>
	                                    </div>';
                            }
                        } else {
                            echo ' <div align="center">
				                    <h3><span class="label label-danger">No Channels Connected</span></h3>
				                </div>';
                        }

                        echo ' <div class="clearfix"> </div> </div>';
                        ?>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" onclick="sendUpdate()" class="btn btn-xs btn-success ">Start Update</button>
                    </div>
                </form>


                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php include("inforeservationcreate.php"); ?>
<?php include("findroomcreate.php"); ?>
<script type="text/javascript">

    var base_url = '<?php echo lang_url(); ?>';

    var showr = '<?= $showreservation ?>';

    function showrate(obj, id)
    {

        if ($(obj).hasClass('fa-plus'))
        {
            $(obj).removeClass('fa-plus');
            $(obj).addClass('fa-minus');
            $(".rate" + id).css({
                display: ''
            });

            $(".rate" + id + 'ctd').addClass('ctd');
            $(".rate" + id + 'cta').addClass('cta');
            $(".rate" + id + 'ss').addClass('ss');

            if ($("#Sales").prop('checked'))
            {
                $(".ss").css({
                    display: ''
                });
            }
            if ($("#cta").prop('checked'))
            {
                $(".cta").css({
                    display: ''
                });
            }
            if ($("#ctd").prop('checked'))
            {
                $(".ctd").css({
                    display: ''
                });
            }


        } else
        {
            $(obj).removeClass('fa-minus');
            $(obj).addClass('fa-plus');
            $(".rate" + id).css({
                display: 'none'
            });
            $(".rate" + id + 'ctd').removeClass('ctd');
            $(".rate" + id + 'cta').removeClass('cta');
            $(".rate" + id + 'ss').removeClass('ss');

            $(".rate" + id + 'cta').css({
                display: 'none'
            });

            $(".rate" + id + 'ctd').css({
                display: 'none'
            });

            $(".rate" + id + 'ss').css({
                display: 'none'
            });
        }
    }
    function sendUpdate()
    {

        $.ajax({
            type: "POST",
            url: base_url + 'inventory/main_full_update',
            data: $("#calendarfull").serialize(),
            success: function (html) {
                alert(html);

            }
        });
    }
    function setfullUpdate() {
        $(".datepicker").datepicker({minDate: new Date(), dateFormat: 'yy-mm-dd'});
        $("#fullupdate").modal();
    }


    function showoption(id, value) {
        $("." + id).css({
            display: (value ? '' : 'none')
        });
    }

    function Calendario(obj, opt) {

        if (opt == 2) {
            if (showr == ($("#show").prop('checked') ? 1 : 0))
            {
                opt = 1;
            }
        }
        var data = {"show": ($("#show").prop('checked') ? 1 : 0), "sales": ($("#Sales").prop('checked') ? 1 : 0), "cta": ($("#cta").prop('checked') ? 1 : 0), "ctd": ($("#ctd").prop('checked') ? 1 : 0), 'yearid': $("#yearid").val(), 'monthid': $("#monthid").val(), 'opt': opt};

        $.ajax({
            type: "POST",
            url: base_url + 'channel/Calendarview',
            data: data,
            beforeSend: function () {
                showWait('Update Calendar, Please Wait');
                setTimeout(function () {
                    unShowWait();
                }, 100000);
            },
            success: function (html) {
                $("#calendario").html(html);
                $('.inline_username').editable({
                    url: function (params) {
                        return saveChange(params);
                    }
                });
                unShowWait();

            }
        });
    }
    function gotoreser(urlr)
    {
        window.location = urlr;
    }
    function saveChange(params)
    {
        var data = {'name': params['name'], 'pk': params['pk'], 'value': params['value']};


        $.ajax({
            type: "POST",
            //dataType: "json",
            url: base_url + 'bulkupdate/savechangecalendar',
            data: data
        });
        return;
    }
    function saveChange2(params)
    {
        var data = {'name': $(params).attr('name'), 'pk': $(params).val(), 'value': ($(params).prop('checked') == true ? '1' : 0)};
        $.ajax({
            type: "POST",
            //dataType: "json",
            url: base_url + 'bulkupdate/savechangecalendar',
            data: data
        });
        return;
    }
    Calendario(0, 1);

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text"), roomnumber, reservation, roomNow,
                reservationNow, obj, startDate, endDate, nowDate, msj_txt,
                nItem = document.getElementById(data).cloneNode(true),
                $i = 0, price = "", availability = false, room_update_id = "",
                latToomUpdateId = "", room_id, type = "";
        ev.target.appendChild(nItem);
        reservation = ev.target.attributes.getNamedItem('data-reservation').value;
        roomnumber = ev.target.attributes.getNamedItem('data-roomnumber').value;
        roomNow = document.getElementById(data).attributes.getNamedItem('data-roomnumber').value;

        if (parseInt(reservation) && parseInt(roomnumber) !== parseInt(roomNow)) {
            swal("<?= html_entity_decode(lang("msj_attention")) ?>", "<?= lang("msj_availability_date") ?>", "error");
            ev.target.innerHTML = "";
        } else {
            startDate = document.getElementById(data).attributes.getNamedItem('data-start_date').value;
            endDate = document.getElementById(data).attributes.getNamedItem('data-end_date').value;
            reservationNow = document.getElementById(data).attributes.getNamedItem('data-reservation').value;
            nowDate = ev.target.attributes.getNamedItem('data-date').value;
            type = document.getElementById(data).attributes.getNamedItem('data-type').value;
            room_id = ev.target.attributes.getNamedItem('data-room_id').value;

            /**
             * Get list of last rooms id
             */
            $(ev.target).parent().find("td").each(function () {
                if (endDate === $(this).data("date")) {
                    return false;
                }

                if (startDate === $(this).data("date")) {
                    latToomUpdateId = latToomUpdateId + $(this).data("room_update_id") + ",";
                } else if (latToomUpdateId) {
                    latToomUpdateId = latToomUpdateId + $(this).data("room_update_id") + ",";
                }
            });

            /**
             * Get list of new rooms id
             */
            $(ev.target).parent().find("td").each(function () {
                if (nowDate === $(this).data("date")) {
                    return false;
                }
                if (price) {
                    price = price + $(this).data("price") + ",";
                    room_update_id = room_update_id + $(this).data("room_update_id") + ",";
                }
                if (startDate === $(this).data("date")) {
                    price = price + $(this).data("price") + ",";
                    room_update_id = room_update_id + $(this).data("room_update_id") + ",";
                }
                availability = (parseInt($(this).data("availability"))) ? true : false;
                $i++;
            });

            if (!availability) {
                swal("<?= html_entity_decode(lang("msj_attention")) ?>", "<?= lang("msj_availability_date") ?>", "error");
                ev.target.innerHTML = "";
                return false;
            }

            if (Date.parse(nowDate) > Date.parse(startDate) && Date.parse(nowDate) > Date.parse(endDate)) {
                msj_txt = "<?= html_entity_decode(lang("msj_increase_days")) ?>";
            } else {
                msj_txt = "<?= html_entity_decode(lang("msj_decrease_days")) ?>";
            }

            if (roomnumber === roomNow) {
                if (Date.parse(nowDate) > Date.parse(startDate) && Date.parse(nowDate) > Date.parse(endDate)) {
                    if (Date.parse(nowDate) > Date.parse(startDate)) {
                        endDate = nowDate;
                    } else {
                        startDate = nowDate;
                    }
                } else {
                    if (type === "end") {
                        endDate = nowDate;
                    } else {
                        startDate = nowDate;
                    }
                }
            }

            /**
             * Confirm alert
             */
            swal({
                title: "<?= html_entity_decode(lang("msj_are_you_sure")) ?>",
                text: msj_txt,
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((confirm) => {
                if (confirm) {
                    obj = {roomnumber: roomnumber, reservation: reservationNow,
                        startDate: startDate, endDate: endDate, price: price, room_id: room_id,
                        room_update_id: room_update_id, latToomUpdateId: latToomUpdateId};
                    $.post(base_url + 'bulkupdate/savechangeReservation', obj, function (data) {
                        Calendario(0, 1);
                    });
                } else {
                    ev.target.innerHTML = "";
                }
            });
        }
    }
</script>
