<div class="outter-wp">
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li>
                <a>
                    <?= $Posinfo['description']?>
                </a>
            </li>
            <li class="active">Reservations</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a href="#createbook" data-toggle="modal" class="btn blue">Add New Reservation</a>
    </div>
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="tabletask" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Name</th>
                            <th>Marketing Prog.</th>
                            <th>
                                <?=($Posinfo['postypeID']==1?'Table':'Treatment Room')?>
                            </th>
                            <th>Date</th>
                            <th>Hour</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ALLReservation)>0) {

                            $i=0;
                            foreach ($ALLReservation as  $value) {
                                $i++;
                                $update="'".$value['mypostablereservationid']."','".$value['mypostableid']."','".  $value['datetimereservation']."','".$value['signer']."','".$value['Roomid']."','".$value['starttime1']."'"  ;
                                $date = date_create($value['datetimereservation']);
                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['signer'].'  </td> 
                                <td> '.$value['marketingp'].'  </td> <td>'.$value['tablename'].' </td> <td>'.date_format($date, 'm/d/Y').' </td>
                                 <td>'.$value['starttime1'].' </td> <td><a  onclick ="showupdate('.$update.')"><i class="fa fa-cog"></i></a></td> </tr>   ';

                            }

                                                                
                                                              
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($ALLReservation)==0) {echo '<h4>No Reservation Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div id="createbook" class="modal fade" role="dialog" aria-hidden="true">
   <?=include('createreservation.php')?>
</div>
<div id="updatebook" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a Reservation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>
            <div>
                <div class="graph-form">
                    <form id="bookUP">
                        <input type="hidden" name="resid" id="resid" value="">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Main Name</label>
                            <input style="background:white; color:black;" name="signerup" id="signerup" type="text" placeholder="Main Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">
                                <?=($Posinfo['postypeID']==1?'Table':'Treatment Room')?>
                            </label>
                            <select style="width: 100%; padding: 9px; " id="tableidup" name="tableidup">
                                <?php
                                    if(count($AllTable)>0)
                                    {
                                        echo '<option value="0">Select a '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' </option>'; 
                                        foreach ($AllTable as  $value) {
                                            echo '<option value="'.$value['postableid'].'">'.$value['description'].'==>Cap:'.$value['qtyPerson'].'</option>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<option value="0">there are no '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label ">Date</label>
                            <input class="datepickers" style="background:white; color:black;" name="deadlineup" id="deadlineup" type="text" placeholder="Select a Date" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Hour [18:00]</label>
                            <input style="background:white; color:black; width: 100%" name="hourtimeup" id="hourtimeup" type="time" placeholder="" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Room Number</label>
                            <input style="background:white; color:black; " name="roomidup" id="roomidup" type="text" placeholder="Room Number" required="">
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="updateReservation()" class="btn green">Update</a>
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
var fecha = new Date($.now());

$('.datepickers').datepicker({ minDate: new Date(), dateFormat: 'yy-mm-dd', });




function updateReservation() {

    var data = $("#bookUP").serialize();

    if ($("#signerup").val() <= 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Main Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#tableidup").val() == 0) {
        swal({
            title: "upps, Sorry",
            text: "Selected a Table  To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#deadlineup").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Selected a Date To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#hourtimeup").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a Hour To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/updateReservation",
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
                    text: "Book Update!",
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


function showupdate(id, tableid, dateti, signer, roomid, startime) {

    /*$update="'".$value['mypostablereservationid']."','".$value['mypostableid']."','".  $value['datetimereservation']."','".$value['signer']."','".$value['Roomid']."','".$value['starttime']."'"  ;*/
    $("#signerup").val(signer);
    $("#tableidup").val(tableid);
    $("#deadlineup").val(dateti);
    $("#roomidup").val(roomid);
    $("#hourtimeup").val(startime);
    $("#resid").val(id);
    $("#updatebook").modal();

}


$.fn.pageMe = function(opts) {
    var $this = this,
        defaults = {
            perPage: 7,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);

    var listElement = $this.find('tbody');
    var perPage = settings.perPage;
    var children = listElement.children();
    var pager = $('.pager');

    if (typeof settings.childSelector != "undefined") {
        children = listElement.find(settings.childSelector);
    }

    if (typeof settings.pagerSelector != "undefined") {
        pager = $(settings.pagerSelector);
    }

    var numItems = children.size();
    var numPages = Math.ceil(numItems / perPage);

    pager.data("curr", 0);

    if (settings.showPrevNext) {
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }

    var curr = 0;
    while (numPages > curr && (settings.hidePageNumbers == false)) {
        $('<li><a href="#" class="page_link">' + (curr + 1) + '</a></li>').appendTo(pager);
        curr++;
    }

    if (settings.showPrevNext) {
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }

    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages <= 1) {
        pager.find('.next_link').hide();
    }
    pager.children().eq(1).addClass("active");

    children.hide();
    children.slice(0, perPage).show();

    pager.find('li .page_link').click(function() {
        var clickedPage = $(this).html().valueOf() - 1;
        goTo(clickedPage, perPage);
        return false;
    });
    pager.find('li .prev_link').click(function() {
        previous();
        return false;
    });
    pager.find('li .next_link').click(function() {
        next();
        return false;
    });

    function previous() {
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }

    function next() {
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }

    function goTo(page) {
        var startAt = page * perPage,
            endOn = startAt + perPage;

        children.css('display', 'none').slice(startAt, endOn).show();

        if (page >= 1) {
            pager.find('.prev_link').show();
        } else {
            pager.find('.prev_link').hide();
        }

        if (page < (numPages - 1)) {
            pager.find('.next_link').show();
        } else {
            pager.find('.next_link').hide();
        }

        pager.data("curr", page);
        pager.children().removeClass("active");
        pager.children().eq(page + 1).addClass("active");

    }
};

function Paginar(numeroP = 10) {
    $('#tabletask').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>