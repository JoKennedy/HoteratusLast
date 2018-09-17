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
            <li class="active">Task</li>
        </ol>
    </div>
    <div>
        <?php include("menu.php") ?>
    </div>
    <div style="float: right; " class="buttons-ui">
        <a href="#createtask" data-toggle="modal" class="btn blue">Add New Task</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div class="clearfix"></div>
                <table id="tabletask" class="table table-bordered">
                    <thead>
                        <tr>
                            <th  width="5%">#</th>
                            <th>Staff Name</th>
                            <th>Task Description</th>
                            <th>Process</th> 
                            <th>Status</th>
                            <th>Date</th>
                            <th>Hour</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ALLTask)>0) {

                            $i=0;
                            foreach ($ALLTask as  $value) {
                                $i++;
                                $class=($value['proccess']<=10?'danger':($value['proccess']<=20?'warning':($value['proccess']<=50?'info':($value['proccess']<100?'inverse':'success'))));
                                
                                $update="'".$value['staffid']."','".$value['Description']."','".  $value['proccess']."','".date('m/d/Y',strtotime($value['enddate']))."','".$value['taskid']."','".$value['endtime']."'";

                                echo' <tr  class="'.($i%2?'active':'success').'"> <th scope="row">'.$i.' </th> <td> '.$value['staffname'].'  </td> 
                                <td> '.$value['Description'].'  </td> 
                                <td align="center"> <span class="percentage">'.$value['proccess'].'%</span> <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-'.$class.'" style="width: '.$value['proccess'].'%"></div></div></td>
                                <td align="center">'.($value['active']==1?'Active':'Deactive').'</td>
                                <td>'.date('m/d/Y',strtotime($value['enddate'])).'</td>
                                <td>'.$value['endtime'].'</td>
                                   <td><a  onclick ="showupdate('.$update.')"><i class="fa fa-cog"></i></a></td> </tr>  ';

                            }

                                                                
                                                              
                           
                        } ?>
                    </tbody>
                </table>
                <?php if (count($ALLTask)==0) {echo '<h4>No Task Created!</h4>';} 
                  else
                  { echo ' <div style"float:right;> <ul " class="pagination pagination-sm pager" id="myPager"></ul> </div>';}
                 ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div id="createtask" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Create a Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="taskC">
                        <input type="hidden" name="posid" id="posid" value="<?=$Posinfo['myposId']?>">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Assigned to</label>
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
                            <label class="control-label">Task Description</label>
                            <input style="background:white; color:black;" name="description" id="description" type="text" placeholder="Task Description (TODO)" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">DeadLine To Complete</label>
                            <input class="datepicker" style="background:white; color:black; text-align: center;" name="deadline" id="deadline" type="text" placeholder="DeadLine" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Hour</label>
                            <input style="background:white; color:black; width: 100%" name="endtime" id="endtime" type="text" placeholder="Hour" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Process Status</label>
                            <input  style="background:white; color:black; text-align: center;" name="process" id="process" type="text" onkeypress="return justNumbers(event);" required="" value="0" minlength="1" maxlength="3">
                        </div>

                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveTask()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updatetask" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title">Update a Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> 
            </div>
            <div>
                <div class="graph-form">
                    <form id="taskUP">
                        <input type="hidden" name="taskid" id="taskid" value="">
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Assigned to</label>
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
                                        echo '<option value="0">there are no employees created</option>'; 
                                    }
                                    
                                ?>
                            </select>
                            
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Task Description</label>
                            <input style="background:white; color:black;" name="descriptionup" id="descriptionup" type="text" placeholder="Task Description (TODO)" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">DeadLine To Complete</label>
                            <input class="datepicker" style="background:white; color:black; text-align: center;" name="deadlineup" id="deadlineup" type="text" placeholder="DeadLine" required="">
                        </div>
                         <div class="col-md-12 form-group1">
                            <label class="control-label">Hour</label>
                            <input style="background:white; color:black; width: 100%" name="endtimeup" id="endtimeup" type="text" placeholder="Hour" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Process Status</label>
                            <input  style="background:white; color:black; text-align: center;" name="processup" id="processup" type="text" onkeypress="return justNumbers(event);" required="" value="0" minlength="1" maxlength="3">
                        </div>

                        <div id="respuesta"></div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="updateTask()" class="btn green">Update</a>
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
<link href="<?php echo base_url();?>user_asset/back/css/jquery.timepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>user_asset/back/js/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    $('#endtime').timepicker({ 'timeFormat': 'h:i A' });
     $('#endtimeup').timepicker({ 'timeFormat': 'h:i A' });
    $(".datepicker").datepicker();
     var fecha = new Date($.now());
   
function saveTask() {

    var data = new FormData($("#taskC")[0]);

    if ($("#staffid").val()==0) {
        swal({
            title: "upps, Sorry",
            text: "Selected a employee Firts To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#description").val() <= 5) {
        swal({
            title: "upps, Sorry",
            text: "Type a Description to Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#deadline").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a DeadLine To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    $.ajax({
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false,
        url: "<?php echo lang_url(); ?>pos/saveTask",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Task Created!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    $("#createtask").fadeOut('fast');
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Task was not Created! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });

}
function updateTask() {

    var data = new FormData($("#taskUP")[0]);

    if ($("#staffidup").val()==0) {
        swal({
            title: "upps, Sorry",
            text: "Selected a employee Firts To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#descriptionup").val() <= 5) {
        swal({
            title: "upps, Sorry",
            text: "Type a Description to Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    } else if ($("#deadlineup").val().length <= 0) {

        swal({
            title: "upps, Sorry",
            text: "Type a DeadLine To Continue!",
            icon: "warning",
            button: "Ok!",
        });
        return;
    }


    $.ajax({
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false,
        url: "<?php echo lang_url(); ?>pos/updateTask",
        data: data,
        beforeSend: function() {
            showWait();
            setTimeout(function() { unShowWait(); }, 10000);
        },
        success: function(msg) {
            unShowWait();
            if (msg["result"] == "0") {
                swal({
                    title: "Success",
                    text: "Task Updated!",
                    icon: "success",
                    button: "Ok!",
                }).then((n) => {
                    location.reload();
                });
            } else {

                swal({
                    title: "upps, Sorry",
                    text: "Task was not Updated! Error:" + msg["result"],
                    icon: "warning",
                    button: "Ok!",
                });
            }





        }
    });

}


function showupdate(staffid, task, processu, enddate, id,endtime) {

    $("#staffidup").val(staffid);
    $("#descriptionup").val(task);
    $("#processup").val(processu);
    $("#taskid").val(id);
    $("#deadlineup").val(enddate);
    $("#endtimeup").val(endtime);
    $("#updatetask").modal();

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