<style type="text/css" media="screen">
.dt-buttons {
    float: left;
}

.buttons-excel,
.buttons-csv,
.buttons-copy,
.buttons-pdf,
.buttons-print {
    display: none;
}

.dataTables_filter input {
    color: black;
}
h3.popover-title {
  color:black;
}

</style>

<div class="outter-wp" style="height: 3000px;">
		<!--sub-heard-part-->
		  <div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
				<li>Housekeeping</li>
				<li class="active">Rooms Status</li>
			</ol>
		   </div>
			<div  class="clearfix"></div>

			<div class="graph-visual tables-main">

				<div style="float: left;" class="buttons-ui">
                <select onchange="changeList(this.value)" id="displaynumber" class="green">
                    <option value="10">10</option>
                    <option value="25" >25</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                    <option value="200">200</option>

                </select>
				        <select onchange="List()" id="HousekeepingStatusId" class="green">
				            <option value="-1" selected>All Status</option>
				            <?php if (count($AllStatus)>0) {

				                            foreach ($AllStatus as  $value) {
				                                echo '<option value="'.$value['value'].'">'.$value['text'].'</option>';
				                            }
				                        } ?>
				        </select>
                <select onchange="List()" id="RoomTypeId" class="green">
				            <option value="0" selected>All Rooms Type</option>
				            <?php if (count($AllRooms)>0) {

				                            foreach ($AllRooms as  $value) {
				                                echo '<option value="'.$value['property_id'].'">'.$value['property_name'].'</option>';
				                            }
				                        } ?>
				        </select>


				    </div>
				<div style="float: right; " class="buttons-ui">
			        <a href="#createstatus" data-toggle="modal" class="btn blue">Add New Status</a>
			        <a onclick="Export()" class="btn green">Export</a>
              <a onclick="ShowBulk()" class="btn red">Bulk Update Status</a>
			    </div>

          <div  class="clearfix"></div>
          <form id="informacion">
              <div class="bulkupdate graph" style="display:none;">
                <center>  <h4><span class="label label-default">Bulk Update Housekeeping Status</span></h4>

                  <div class="col-md-12 form-group1">
                      <label class="control-label">Housekeeping Status</label>
                      <select style="width: 100%; padding: 9px;" name="statusbulk" id="statusbulk">
                         <option value="0" selected>Select a Status</option>
                         <?php
                            if (count($AllStatus)>0) {
                              foreach ($AllStatus as  $value) {
                                  echo '<option value="'.$value['value'].'">'.$value['text'].'</option>';
                              }
                            }
                          ?>
                     </select>
                  </div>
                  <br>
                  <div class="col-md-12">

                      <a onclick="updatestatusbulk()" class="btn green">Update</a>
                  </div>
                  </center>

                  <div  class="clearfix"></div>
              </div>

      				<div class="graph">

      					<div id="AllRoomId"></div>

      				</div>
        </form>
				<div  class="clearfix"></div>
			</div>
		</div>

	</div>
</div>
<div id="createstatus" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Create a Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span> </button>
            </div>
            <div>
                <div class="graph-form">
                    <form id="statusC">

                        <div class="col-md-12 form-group1">
                            <label class="control-label">Status</label>
                            <input style="background:white; color:black;" name="statusname" id="statusname" type="text" placeholder="Status Name" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Code</label>
                            <input style="background:white; color:black;" name="code" id="code" type="text" placeholder="Status Code" required="">
                        </div>
                        <div class="col-md-12 form-group1">
                            <label class="control-label">Color</label>
                            <input  style="background:white; color:black; text-align: center;" name="color" id="color" type="text" placeholder="Status Color"  required="">
                        </div>
                        <div class="clearfix"> </div>
                        <br>
                        <br>
                        <div class="buttons-ui">
                            <a onclick="saveStatus()" class="btn green">Save</a>
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="export" class="modal fade" role="dialog" style="z-index: 1400; ">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div align="center">
                        <h1><span class="label label-primary">Options to Import</span></h1>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="buttons-ui">
                        <a onclick="csv()" class="btn orange">CSV</a>
                        <a onclick="Excel()" class="btn green">Excel</a>
                        <a onclick="PDF()" class="btn yellow">PDF</a>
                        <a onclick="PRINT()" class="btn blue">Print</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
</div>

<script src="<?php echo base_url();?>user_asset/back/js/colorpicker.js"></script>
<script type="text/javascript">
var bulkupdate =0;
var vcount =0;
	$('#color').simpleColor();
  function updatestatusbulk(){

    if ($("#statusbulk").val()==0) {
       swal({
            title: "upps, Sorry",
            text: "Select a Status To Continue!",
            icon: "warning",
            button: "Ok!",
          });
          return;
    }

    $('input[class=select]:checked').each(function() {
      vcount =1;
    });

    if(vcount==0)
    {
      swal({
           title: "upps, Sorry",
           text: "Select a Room Number To Continue!",
           icon: "warning",
           button: "Ok!",
         });
         return;
    }
vcount=0;

    $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo lang_url(); ?>housekeeping/updateStatusBulk",
          data: $("#informacion").serialize(),
          success: function(msg) {
              if (msg["success"]) {
                $('input[class=select]:checked').each(function() {
                  $("#row"+$(this).prop("id")).css('background-color',msg['color']);
                    $("#row"+$(this).prop("id")+' a').attr('data-value',msg['id']);
                    $("#row"+$(this).prop("id")+' a').html(msg['name']);
                  $(this).prop("checked",false);
                });

                ShowBulk();

              } else {
                  swal({
                      title: "upps, Sorry",
                      text:  msg["message"],
                      icon: "warning",
                      button: "Ok!",
                  });
              }
          }
      });
  }
  function ShowBulk()
  {
    bulkupdate =(bulkupdate==0?1:0);
    $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));

  }
  function changeList(number)
  {
      $('#myTable').DataTable({
         dom: 'Bfrtip',
         "destroy":true,
         "displayLength": number,
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ],
         "order": [[ 0, "asc" ]]
     });

     if(bulkupdate==1){
       $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));
     }

  }
	function List()
	{
		$.ajax({
		        type: "POST",
		        //dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/RoomListHTML",
		        data: {'status':$("#HousekeepingStatusId").val(),'roomid':$("#RoomTypeId").val()},
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		           $("#AllRoomId").html(msg);
               $('.inline_username').editable({
                 url: function (params) {
                    return updateStatus(params);
                 }
             });
		             $('#myTable').DataTable({
		                dom: 'Bfrtip',
                    "displayLength": $("#displaynumber").val(),
		                buttons: [
		                    'copy', 'csv', 'excel', 'pdf', 'print'
		                ],
		                "order": [[ 0, "asc" ]]
		            });
                $('#myTable_paginate').click(function(){
                  $(".bulkupdate").css('display',(bulkupdate==1?'':'none'));
                });
		        }
		    });
	}
	function saveStatus()
	{


		if ($("#statusname").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Name To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#code").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Type a Status Code To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}else if ($("#color").val().length==0) {
			 swal({
            title: "upps, Sorry",
            text: "Select a Status Color To Continue!",
            icon: "warning",
            button: "Ok!",
	        });
	        return;
		}

		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/saveStatus",
		        data: $("#statusC").serialize(),
		        beforeSend: function() {
		            showWait();
		            setTimeout(function() { unShowWait(); }, 10000);
		        },
		        success: function(msg) {
		            unShowWait();
		            if (msg["success"]) {
		                swal({
		                    title: "Success",
		                    text: "Status Created!",
		                    icon: "success",
		                    button: "Ok!",
		                }).then((n) => {
		                    location.reload();
		                });
		            } else {

		                swal({
		                    title: "upps, Sorry",
		                    text:  msg["message"],
		                    icon: "warning",
		                    button: "Ok!",
		                });
		            }

		        }
		    });
	}
	function updateStatus(params)
	{
    var data={'name':params['name'],'pk':params['pk'],'value':params['value']};
    pk=params['pk'].split(',');

		  $.ajax({
		        type: "POST",
		        dataType: "json",
		        url: "<?php echo lang_url(); ?>housekeeping/updateStatus",
		        data: data,
		        success: function(msg) {
		            if (msg["success"]) {
		               $("#row"+pk[0]+"r"+pk[1]).css('background-color',msg['color']);
		            } else {
		                swal({
		                    title: "upps, Sorry",
		                    text:  msg["message"],
		                    icon: "warning",
		                    button: "Ok!",
		                });
		            }
		        }
		    });
	}
	$(function() {
	    $('.tabs nav a').on('click', function() {
	        show_content($(this).index());
	    });

	    show_content(0);

	    function show_content(index) {
	        // Make the content visible
	        $('.tabs .context.visible').removeClass('visible');
	        $('.tabs .context:nth-of-type(' + (index + 1) + ')').addClass('visible');

	        // Set the tab to selected
	        $('.tabs nav.second a.selected').removeClass('selected');
	        $('.tabs navnav.second a:nth-of-type(' + (index + 1) + ')').addClass('selected');
	    }
	});

	$(document).ready(function() {

  		List();

	});
	function csv() {
    	$(".buttons-csv").trigger("click");
	}

	function Excel() {
	    $(".buttons-excel").trigger("click");
	}

	function PDF() {
	    $(".buttons-pdf").trigger("click");
	}

	function PRINT() {
	    $(".buttons-print").trigger("click");
	}

	function Export() {
		List();
	    $("#export").modal();
	}

  var settings = {
  "async": true,
  "crossDomain": true,
  "url": "https://www.expedia.es/infosite-api/534766/getOffers?clientid=KLOUD-HIWPROXY&token=4fb4fe08b296f4621047719b49cf70342d4088a0&brandId=3901&countryId=50&isVip=false&chid=&partnerName=HSR&partnerPrice=146.6&partnerCurrency=EUR&partnerTimestamp=1540407968963&adults=2&children=0&chkin=24%2F10%2F2018&chkout=25%2F10%2F2018&hwrqCacheKey=440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416&cancellable=false&regionId=2851&vip=false&=undefined&exp_dp=146.6&exp_ts=1540407968963&exp_curr=EUR&swpToggleOn=false&exp_pg=HSR&daysInFuture=&stayLength=&ts=1540412851309&evalMODExp=true&tla=DCF",
  "method": "GET",
  "headers": {
    "accept": "application/json, text/javascript, */*; q=0.01",
    "x-requested-with": "XMLHttpRequest",
    "user-agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
    "referer": "https://www.expedia.es/Puerto-Plata-Hoteles-Lifestyle-Tropical-Beach-Resort-Spa-All-Inclusive.h534766.Informacion-Hotel?adults=2&children=0&chkin=24%2F10%2F2018&chkout=25%2F10%2F2018&hwrqCacheKey=440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416&cancellable=false&regionId=2851&vip=false&=undefined&exp_dp=146.6&exp_ts=1540407968963&exp_curr=EUR&exp_pg=HSR&daysInFuture=&stayLength=&ts=1540412851304",
    "accept-encoding": "gzip, deflate, br",
    "accept-language": "es,en;q=0.9",
    "cookie": "_gid=GA1.2.625488834.1540411499; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; DUAID=716c4d9a-dfba-4b43-9682-9a6e5aeee40c; MC1=GUID=716c4d9adfba4b4396829a6e5aeee40c; OIP=gdpr|-1,ab.gdpr|1; ak_bmsc=D1F725E7A755FCC10748F746D3DCD88A08122BD887550000D9CCD05B141DEE00~plWW7vA1eeORcHZWfhCNZ3fRxY07Wr4vKz+toG7QRS4ecJvraZznNh09U635OO2v8ZjB32IVoQ8v+chx9anqgSrty9tqxWWZz2w1mYvxNeoT3FJzPeuWVuG1Goh6jJlNQxA+bEOt8N/KMEBR7FVuphaMEWQBUpMiAKo/DZR+QlUgC9MFA0sPjKv2w/ZStG2hhAd4XVbpMiOciegGLSRpXt9J5svd/i/7ChkUoUcZRRTao=; CONSENTMGR=ts:1540411930584%7Cconsent:false; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0$_sn:1$_ss:0$_pn:2%3Bexp-session$_st:1540413730595$ses_id:1540410976492%3Bexp-session; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; HMS=e52aa8f8-ea52-440f-a555-878c77a02c4d; tpid=v.1,9; iEAPID=0; currency=EUR; linfo=v.4,|0|0|255|1|0||||||||3082|0|0||0|0|0|-1|-1; JSESSION=e4c77c06-dbc2-45da-ae19-90306fb2491d; aspp=v.1,0|||||||||||||; AMCVS_C00802BE5330A8350A490D4C%40AdobeOrg=1; AMCV_C00802BE5330A8350A490D4C%40AdobeOrg=-179204249%7CMCIDTS%7C17829%7CMCMID%7C10422282565004023911248397125712894566%7CMCAAMLH-1541015773%7C7%7CMCAAMB-1541015773%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1540418173s%7CNONE%7CMCAID%7CNONE; AB_Test_TripAdvisor=A; GDPRb=1; qualtrics_sample=false; qualtrics_SI_sample=true; QSI_HistorySession=https%3A%2F%2Fwww.expedia.es%2FPuerto-Plata-Hoteles-Lifestyle-Tropical-Beach-Resort-Spa-All-Inclusive.h534766.Informacion-Hotel%3Fchkin%3D25%252F10%252F2018%26chkout%3D26%252F10%252F2018%26rm1%3Da2%26hwrqCacheKey%3D440e1ef3-b70a-451a-be0d-37b4e8072665HWRQ1540407963416%26cancellable%3Dfalse%26regionId%3D2851%26vip%3Dfalse%26c%3D1e98c988-70fb-4fc9-b14a-948ff1b52589%26%26exp_dp%3D146.6%26exp_ts%3D1540407968963%26exp_curr%3DEUR%26swpToggleOn%3Dfalse%26exp_pg%3DHSR~1540411191551; HSEWC=0; s_ppn=page.Hotels.Infosite.Information; s_cc=true; rlt_marketing_code_cookie=; currency=EUR; linfo=v.4,|0|0|255|1|0||||||||3082|0|0||0|0|0|-1|-1; JSESSION=e4c77c06-dbc2-45da-ae19-90306fb2491d; abucket=CgHgI1vQzNg9oCmkGAALAg==; aspp=v.1,0|||||||||||||; AMCVS_C00802BE5330A8350A490D4C%40AdobeOrg=1; AMCV_C00802BE5330A8350A490D4C%40AdobeOrg=-179204249%7CMCIDTS%7C17829%7CMCMID%7C10422282565004023911248397125712894566%7CMCAAMLH-1541015773%7C7%7CMCAAMB-1541015773%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1540418173s%7CNONE%7CMCAID%7CNONE; AWSELB=5DC5036312F7D9CBE037BAAE6FE1CFD2E9AFA03402B745C089600146556C3703EF2BDD06BCA560D595349023389858DB7A7015E99626B7A7B8F3280A0A6803D1A9F43A507A; AB_Test_TripAdvisor=A; GDPRb=1; qualtrics_sample=false; qualtrics_SI_sample=true; HMS=e52aa8f8-ea52-440f-a555-878c77a02c4d; tpid=v.1,9; iEAPID=0; cesc=%7B%22entryPage%22%3A%5B%22page.Hotels.Infosite.Information%22%2C1540410973825%5D%7D; s_ppn=page.Hotels.Infosite.Information; s_cc=true; rlt_marketing_code_cookie=; HSEWC=0; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; OIP=gdpr|1,ab.gdpr|1; __gads=ID=cdcdc7e8aa1b8772:T=1540411964:S=ALNI_MaXSHdhcArz7OtoJ4bepwwnKLGOzA; cesc=%7B%22entryPage%22%3A%5B%22page.Hotels.Infosite.Information%22%2C1540410973825%5D%7D; CONSENTMGR=ts:1540412188225%7Cconsent:true; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0$_sn:1$_ss:0$_pn:4%3Bexp-session$_st:1540413988233$ses_id:1540410976492%3Bexp-session; _tq_id.TV-721872-1.76d3=bec9e6628c7d4823.1540411499.0.1540411499..; _gcl_au=1.1.1531971318.1540411499; __gads=ID=aa66a20545afd036:T=1540411494:S=ALNI_MbeUZYD6jTkjEU7jgMCZuqfaEr9EQ; _ga=GA1.2.91656075.1540411499; _gid=GA1.2.625488834.1540411499; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; DUAID=716c4d9a-dfba-4b43-9682-9a6e5aeee40c; MC1=GUID=716c4d9adfba4b4396829a6e5aeee40c; OIP=gdpr|-1,ab.gdpr|1; ak_bmsc=D1F725E7A755FCC10748F746D3DCD88A08122BD887550000D9CCD05B141DEE00~plWW7vA1eeORcHZWfhCNZ3fRxY07Wr4vKz+toG7QRS4ecJvraZznNh09U635OO2v8ZjB32IVoQ8v+chx9anqgSrty9tqxWWZz2w1mYvxNeoT3FJzPeuWVuG1Goh6jJlNQxA+bEOt8N/KMEBR7FVuphaMEWQBUpMiAKo/DZR+QlUgC9MFA0sPjKv2w/ZStG2hhAd4XVbpMiOciegGLSRpXt9J5svd/i/7ChkUoUcZRRTao=; CONSENTMGR=ts:1540411930584%7Cconsent:false; utag_main=v_id:0166a7a628ec0002fb3b507e3bea03073018a06b00bd0$_sn:1$_ss:0$_pn:2%3Bexp-session$_st:1540413730595$ses_id:1540410976492%3Bexp-session; s_ppvl=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP; s_ppv=page.Hotels.Infosite.Information%2C31%2C15%2C1869%2C1920%2C969%2C1920%2C1080%2C1%2CP",
    "cache-control": "no-cache",
    "postman-token": "f4c36ef9-9efe-24d2-ab2b-017d56535f61"
  }
}

$.ajax(settings).done(function (response) {
  console.log(response);
});
</script>

<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>user_asset/back/js/datatables/datatables-init.js"></script>
