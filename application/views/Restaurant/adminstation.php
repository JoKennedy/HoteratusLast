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
            <li class="active">Stations</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a onclick="return createstations();" class="btn blue">Add New Station</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="tablestation" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Station Name</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ALLStations)>0) {
   

                            $i=0;
                            foreach ($ALLStations as  $value) {
                                $i++;
                                
                                $update="'".$value['stationid']."','".$value['name']."','".$value['supervisorid']."','".$value['active']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> 
                                    <td> '.$value['name'].'  </td> 
                                    <td> '.$value['supervisor'].'  </td> 
                                    <td align="center">'.($value['active']==1?'Active':'Deactive').'</td> 
                                    <td><a href="#updatesupplier" onclick ="showupdate('.$update.')" data-toggle="modal"><i class="fa fa-cog"></i></a></td> 
                                    </tr> ';

                            }

                                                                
                                                              
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($ALLStations)==0) {echo '<h4>No Station Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createstation" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Create a Station</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="StationC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Station Name</label>
                            <input style="background:white; color:black;" name="name" id="name" type="text" placeholder="Station Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Manager</label>
                            <select style="width: 100%; padding: 9px; " id="staffid" name="staffid">
                                <?php
                                    if(count($StaffInfo)>0)
                                    {
                                        echo '<option value="0">Select a employee </option>'; 
                                        foreach ($StaffInfo as  $value) {
                                            echo '<option value="'.$value['mystaffposid'].'">'.$value['firstname'].' '.$value['lastname'].'=>'.$value['occupation'].'</option>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<option value="0">there are no employees created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Available Tables</label>
                            <div id="tableavailible"></div>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Available Employees</label>
                            <div id="staffavailible"></div>
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveStation()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updatestation" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Create a Station</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="StationUP">
                        <input type="hidden" name="stationid" id="stationid" value="">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Station Name</label>
                            <input style="background:white; color:black;" name="nameup" id="nameup" type="text" placeholder="Station Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Manager</label>
                            <select style="width: 100%; padding: 9px; " id="staffidup" name="staffidup">
                                <?php
                                    if(count($StaffInfo)>0)
                                    {
                                        echo '<option value="0">Select a employee </option>'; 
                                        foreach ($StaffInfo as  $value) {
                                            echo '<option value="'.$value['mystaffposid'].'">'.$value['firstname'].' '.$value['lastname'].'=>'.$value['occupation'].'</option>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<option value="0">There are no employees created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Available Tables</label>
                            <div id="tableavailibleup"></div>
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Available Employees</label>
                            <div id="staffavailibleup"></div>
                        </div>
                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="updateStation()" class="btn green">Update</a>
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
var posid = '<?php echo $Posinfo["myposId"]; ?>';

function createstations() {

    var data = { "stationid": 0, "posid": posid };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/infoStation",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            $("#tableavailibleup").html('');
            $("#staffavailibleup").html('');
            $("#tableavailible").html(msg['htmltable']);
            $("#staffavailible").html(msg['htmlstaff']);


        }
    });

    $("#createstation").modal();
}

function saveStation() {

    if ($("#name").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Station Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#staffid").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Manager to Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } 

    var data = $("#StationC").serialize();


     $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/saveStation",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

            if(msg['success'])
            {
                 swal({
                    title: "Success",
                    text: "Station Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            }
            else
            {
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
function updateStation() {

    if ($("#nameup").val().length < 3) {
        swal({
            title: "upps, Sorry",
            text: "Missing Field Station Name!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#staffidup").val() <= 0) {
        swal({
            title: "upps, Sorry",
            text: "Select a Manager to Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } 

    var data = $("#StationUP").serialize();


     $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/updateStation",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();

            if(msg['success'])
            {
                 swal({
                    title: "Success",
                    text: "Station Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            }
            else
            {
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


function showupdate(stationid, name, supervisorid, active) {

    var data = { "stationid": stationid, "posid": posid };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo lang_url(); ?>pos/infoStation",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            $("#tableavailible").html('');
            $("#staffavailible").html('');
            $("#tableavailibleup").html(msg['htmltable']);
            $("#staffavailibleup").html(msg['htmlstaff']);
        }
    });

    $("#nameup").val(name);
    $("#staffidup").val(supervisorid);
    $("#stationid").val(stationid);
    $("#updatestation").modal();
    /*  $("#addressup").val(address);
      $("#phoneup").val(phone);
      $("#cphoneup").val(cphone);
      $("#emailup").val(email);
      $("#supplierID").val(id);*/

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
    $('#tablestation').pageMe({ pagerSelector: '#myPager', showPrevNext: true, hidePageNumbers: false, perPage: numeroP });
}
$(document).ready(function() {

    Paginar(10);

});
</script>