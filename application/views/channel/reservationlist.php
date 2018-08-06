<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Reservation List</li>
        </ol>
    </div>
    <!--//sub-heard-part-->
    <div style="float: right;" class="buttons-ui">
     
        <a class="btn orange">Import Resevations Now</a>
        <a class="btn green">Export XLSX</a>
        <a onclick="setcalendar()" class="btn blue">Add Reservation</a>
    </div>
    <div class="clearfix"></div>
    <div class="graph-visual tables-main">
        <div class="graph">
            <div class="table-responsive">
                <div style="float: left;" class="buttons-ui">
                    <label class="control-label">Records</label>
                    <select id="mostrar" class="btn blue">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <select id="channels" class="btn blue">
                        <option value="">All Reservations</option>
                        <option value="0">Manual Booking</option>
                        <?php if (count($AllChannel)>0) {

										foreach ($AllChannel as  $value) {
											echo '<option value="'.$value['channel_id'].'">'.$value['channel_name'].'</option>';
										}
									} ?>
                    </select>
                    <select id="status" class="btn blue">
                        <option value="">All Status</option>
                        <option value="Canceled">Canceled</option>
                        <option value="Reserved">Reserved</option>
                        <option value="Modified">Modified</option>
                        <option value="No Show">No Show</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Unchecked">Unchecked</option>
                    </select>
                    <input id="date1" style="background-color: white; width:200px; " type="date" class="btn blue" value="" placeholder="">
                    <input id="date2" style="background-color: white; width:200px;" type="date" class="btn blue" value="" placeholder="">
                    <input class="btn blue" style="background-color: white; color: black;" id="buscar" type="text" placeholder="Type to filter" />
                </div>
                <div class="clearfix"></div>
                <table id="Reservationlist" class="table table-bordered" >
                    <thead>
                        <tr style="height:2px;">
                            <th>Status</th>
                            <th>Full Name</th>
                            <th>Room Booked</th>
                            <th>Room #</th>
                            <th>Channel</th>
                            <th>Checkin</th>
                            <th>Checkout</th>
                            <th>Booked</th>
                            <th>Reservation #</th>
                            <th >Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($AllReservationList)>0) {

											foreach ($AllReservationList as  $value) {
												$class_status=($value['status']==0?'danger':($value['status']==1?'info':($value['status']==2?'warning':($value['status']==3?'danger':($value['status']==4?'success':'active')))));

												$show_status=($value['status']==0?'Canceled':($value['status']==1?'Reserved':($value['status']==2?'Modified':($value['status']==3?'No Show':($value['status']==4?'Confirmed':'Unchecked')))));

												echo' <tr scope="row" class="'.$class_status.'"> <th scope="row">'.$show_status.' </th> <td> <a href="'.site_url('reservation/reservationdetails/'.secure($value['channel_id']).'/'.insep_encode($value['reservation_id'])).'">'.$value['Full_Name'].' </a> </td> <td>'.$value['roomName'].'</td> <td>'.$value['RoomNumber'].'</td> 
                                                <td style="text-align:center;"> <img  src="data:image/png;base64,'.$allLogo['LogoReservation'.$value['channel_id']].'"> 	<p style ="color: rgba(0, 0, 0, 0);">'.$value['channel_id'].'</p> </td>  <td>'.date('m/d/Y',strtotime($value['start_date'])).'</td> <td>'.date('m/d/Y',strtotime($value['end_date'])).'</td> <td>'.date('m/d/Y',strtotime($value['booking_date'])).'</td> <td>'.$value['reservation_code'].'</td> <td>'.number_format ( $value['price'] , 2 ,  "." , "," ).'</td> </tr>  ';

											}
									} ?>
                    </tbody>
                </table>
                <?php if (count($AllReservationList)==0) {echo '<h4> No Record Found!</h4>';} 

							else
							{

								echo '<label style="float: right;" id="totales" class="control-label"></label>';
							 echo ' <div style"float:left;> <ul " class="pagination pagination-lg pager" id="myPager"></ul> </div>';
							}

						?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!--//graph-visual-->
</div>
<!--//outer-wp-->
<!--footer section start-->
<!--footer section end-->
</div>
</div>
<div id="CreateReservation" class="modal fade" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div align="center" id="headerinvoice">
            </div>
            <?php include("creationreservation.php")?>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script language="javascript" type="text/javascript">
//<![CDATA[ 

var cont = 0;

$("#pagination").click(function(e) {
    var li = e.target.parentNode;
    var id = li.id;

    $("#pagination li").each(function() {
        if ($(this).attr('id') != 'previous' || $(this).attr('id') != 'next') {
            if ($(this).attr('id') == id) {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        }
    });

    calculate2(id);
});

document.querySelector("#buscar").onkeyup = function() {
    $TableFilter("#Reservationlist", this.value);
}
document.querySelector("#channels").onchange = function() {
    $TableFilter3("#Reservationlist", this.value);
}

document.querySelector("#mostrar").onchange = function() {
   Paginar(this.value);
}

document.querySelector("#status").onchange = function() {
    $TableFilter5("#Reservationlist", this.value);

}
document.querySelector("#date1").onchange = function() {

}



$TableFilter = function(id, value) {
    var rows = document.querySelectorAll(id + ' tbody tr');
    var max = $("#mostrar").val();
    var re = 0;
    cont = 0;

    for (var i = 0; i < rows.length; i++) {
        var showRow = false;

        var row = rows[i];
        row.style.display = 'none';

        for (var x = 0; x < row.childElementCount; x++) {
            if (row.children[x].textContent.toLowerCase().indexOf(value.toLowerCase().trim()) > -1) {
                showRow = true;
                re++;
                break;
            }
        }

        if (showRow) {
            cont++;
        }
        if (showRow && re < max) {
            row.style.display = null;
        }
    }

    $calculate(cont);
}

$TableFilter2 = function(id, value) {
    var rows = document.querySelectorAll(id + ' tbody tr');
    cont = 0;
    for (var i = 0; i < rows.length; i++) {
        var showRow = false;

        var row = rows[i];
        row.style.display = 'none';

        cont++;


        if (i < value) {
            row.style.display = null;
        }
    }

    $calculate(cont);
}


$TableFilter3 = function(id, value) {
    var rows = document.querySelectorAll(id + ' tbody tr');
    var max = $("#mostrar").val();
    cont = 0;
    var r = 0;

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        r = 1;
        cont++;
        if (i < max) {
            row.style.display = null;

        }

    }

    if (value != '') {
        cont = 0;

        for (var i = 0; i < rows.length; i++) {
            var showRow = false;
            r = 0;
            var row = rows[i];
            row.style.display = 'none';


            if (row.children[4].textContent.toLowerCase().indexOf(value.toLowerCase().trim()) > -1) {
                showRow = true;
                cont++;
                r = 1;
            }


            if (showRow && cont < max) {
                row.style.display = null;
            }
        }

    }

    if (r == 0) { cont = 0; }
    $calculate(cont);


}

$TableFilter4 = function(id, value) {
    var rows = document.querySelectorAll(id + ' tbody tr');
    var max = $("#mostrar").val();
    cont = 0;

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        row.style.display = null;

    }


    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];

        row.style.display = 'none';
        cont++;

        if (i < max) {
            row.style.display = null;
        }

    }

    $calculate(cont);

}

$TableFilter5 = function(id, value) {
    var rows = document.querySelectorAll(id + ' tbody tr');
    var max = $("#mostrar").val();
    var r = 0;
    cont = 0;
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        cont++;
        r = 1;

        if (i < max) {
            row.style.display = null;
        }
    }


    if (value != '') {
        r = 0;
        cont = 0;

        for (var i = 0; i < rows.length; i++) {


            var showRow = false;

            var row = rows[i];
            row.style.display = 'none';


            if (row.children[0].textContent.toLowerCase().indexOf(value.toLowerCase().trim()) > -1) {
                showRow = true;
                cont++;
                r = 1;
            }


            if (showRow && cont < max) {
                row.style.display = null;
            }
        }
    }

    if (r == 0) { cont = 0; }
    $calculate(cont);

}
$calculate = function(cont) {
    var rows = document.querySelectorAll('#Reservationlist tbody tr');
    var max = $("#mostrar").val();

    var mult = 0;
    var ht = '';



    mult = cont / max;

    if (mult % 1 != 0) {
        mult++;
    }


    ht += '<li id="preveous" class="disabled"><a ><i class="fa fa-angle-left"></i></a></li>';

    for (var i = 1; i <= mult; i++) {

        if (i == 1) {
            ht += '<li  class="active" id="' + i + '"><a >' + i + '</a></li>';
        } else if (i <= 5) {
            ht += '<li id="' + i + '"><a >' + i + '</a></li>';
        }
    }

    if (mult > 5) {
        ht += '<li id="next" > <a ><i class="fa fa-angle-right"></i></a></li>';
    } else {
        ht += '<li id="next" class="disabled" ><a ><i class="fa fa-angle-right"></i></a></li>';
    }

    $("#totales").html("Showing " + (cont >= 1 ? 1 : 0) + " to " + (cont > max ? max : cont) + " of " + cont + " entries");
    $("#pagination").html(ht);


    /*<li class="disabled"><a href="#"><i class="fa fa-angle-left"></i></a></li>
													<li class="active"><a href="#">1</a></li>
													<li><a href="#">7</a></li>
													<li><a href="#">3</a></li>
													<li><a href="#">4</a></li>
													<li><a href="#">5</a></li>
													*/

}


function calculate2(nume) {
    var rows = document.querySelectorAll('#Reservationlist tbody tr');
    var max = $("#mostrar").val();

    var mult = 0;
    var ht = '';


    mult = cont / max;

    if (mult % 1 != 0) {
        mult++;
    }


    var numero = (nume == 1 ? 1 : ((nume - 1) * max) + 1);
    var maximo = (cont >= (nume * max) ? nume * max : (max < cont ? max : cont + 1) - numero);
    $("#totales").html("Showing " + (cont >= 1 ? numero : 0) + " to " + (maximo < 0 ? cont : maximo) + " of " + cont + " entries");


    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];

        row.style.display = 'none'

        if (i >= (cont >= 1 ? numero : 0) && i <= (maximo < 0 ? cont : maximo)) {
            row.style.display = null;
        }
    }

}



function Paginar(numeroP=10)
{
    $("#myPager").html("");
  $('#Reservationlist').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:numeroP});
}
$(document).ready(function(){
    
  Paginar(10);
    
});
</script>