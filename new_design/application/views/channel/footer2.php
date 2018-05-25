<div class="admin-footer">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12"> <p class="text-center">Copyright  &copy; <?php echo date('Y');?> Unlimited Luxury Villas</p></div>
</div>
</div>
</div>

    <?php echo theme_js('jquery.min.js', true);?>
	<script type="text/javascript">
    $(document).on('ready thiru',function()
    {
    var offset = $('.navbar').height();
    $("html:not(.legacy) table.table_stricky").stickyTableHeaders({fixedOffset: offset});
    });
    </script>
    <?php echo theme_js('light-box.js', true);?>
	<?php echo theme_js('light-box2.js', true);?>
    <?php echo theme_js('bootstrap.min.js', true);?>
    <?php echo theme_js('jquery.validate.js', true);?>
    <?php echo theme_js('jquery.fittext.js', true);?>
    <?php echo theme_js('wow.min.js', true);?>
    <?php echo theme_js('creative.js', true);?>
    <?php echo theme_js('jquery.idealforms.js', true);?>
    <?php echo theme_js('jquery-ui.min.js', true);?>
    <?php echo theme_js('paging.js', true);?>
    <?php echo theme_js('jquery.pages.js', true);?>
    
    <link rel="stylesheet" href="<?php echo base_url()?>user_assets/js/plugins/data-tables/DT_bootstrap_edit.css"/>
    <script type="text/javascript" src="<?php echo base_url()?>user_assets/js/plugins/data-tables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>user_assets/js/plugins/data-tables/DT_bootstrap_edit.js"></script>
    
    <?php //echo theme_js('ZeroClipboard/ZeroClipboard.js', true);?>
    <?php //echo theme_js('TableTools.js', true);?>

    <?php 
	if(uri(3)!='advance_update' && uri(3)!='reservationlist') {
	echo theme_js('pre3.js', true);
	}?>	
    
    <?php echo theme_js('jquery.creditCardValidator.js', true);?>
    <?php echo theme_js('highcharts.js', true);?>
	<?php echo theme_js('highcharts-exporting.js', true);?>
    <?php echo theme_js('boots-select.js', true);?>
    <?php echo theme_js('text-editor2.js', true);?>
    <?php echo theme_js('circle.js', true);?>
    <?php echo theme_js('textbox-check2.js', true);?>
    <?php echo theme_js('custom-js-ver2.js', true);?>
    <?php echo theme_js('upload.js', true);?>
    <?php echo theme_js('ckeditor/ckeditor.js', true);?>
    <?php echo theme_js('bootstrap-editable.min.js?version='.rand(0,9999).'', true);?>
    <?php echo theme_js('table-fix2.js', true);?>
    <?php echo theme_js('jquery.dataTables.columnFilter.js', true);?>
    <?php
	if(uri(3)=='dashboard')
	{
	 	echo theme_js('ZeroClipboard/jquery.dataTables.js', true);
		echo theme_js('ZeroClipboard/dataTables.tableTools.js', true);
		echo theme_js('ZeroClipboard/dataTables.bootstrap.js', true);
	}
	?>

    <?php $reservation_all_channel = $this->reservation_model->reservation_all_channel(); //echo theme_js('jquery-ui.js', true);?>
    
	<?php //echo theme_js('socket_helper.js?version='.rand(0,9999).'', true);?>
    
    <script>
	/*$(document).on('ready thiru',function()
	{
		alert('sfsf');
		var offset = $('.navbar').height();
		$("html:not(.legacy) table").stickyTableHeaders({fixedOffset: offset});
	});*/
	<?php if(uri(3)=='dashboard')
	{?>
	/*$(document).ready(function() {
		
    var table = $('#example1').DataTable();
    var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.actions1');
	
	var table = $('#example2').DataTable();
    var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.actions2');
		
	var table = $('#example3').DataTable();
    var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.actions3');
	
	var table = $('#example4').DataTable();
    var tt = new $.fn.dataTable.TableTools( table );
    $( tt.fnContainer() ).insertBefore('div.actions4');
	});*/
	
	function modal_table(current_detail)
	{
		/*$.getScript('<?php echo base_url();?>user_assets/css/dataTables.bootstrap.css');
		$.getScript('<?php echo base_url();?>user_assets/js/ZeroClipboard/jquery.dataTables.js');
		$.getScript('<?php echo base_url();?>user_assets/js/ZeroClipboard/dataTables.tableTools.js');
		$.getScript('<?php echo base_url();?>user_assets/js/ZeroClipboard/dataTables.bootstrap.js')*/;
		
		/*var table = $('#example1').DataTable();
		var tt = new $.fn.dataTable.TableTools( table );
		$( tt.fnContainer() ).insertBefore('div.actions1');
		 dataTables.tableTools
		var table = $('#example2').DataTable();
		var tt = new $.fn.dataTable.TableTools( table );
		$( tt.fnContainer() ).insertBefore('div.actions2');
		
		var table = $('#example3').DataTable();
		var tt = new $.fn.dataTable.TableTools( table );
		$( tt.fnContainer() ).insertBefore('div.actions3');
		
		var table = $('#example4').DataTable();
		var tt = new $.fn.dataTable.TableTools( table );
		$( tt.fnContainer() ).insertBefore('div.actions4');*/
		
		//alert(current_detail);
		if(current_detail=='reservation')
		{
			var table =  $('#example1').DataTable();
			var tt = new $.fn.dataTable.TableTools( table );
			$( tt.fnContainer() ).insertBefore('div.actions1');
		}
		else if(current_detail=='cancelation')
		{
			var table = $('#example2').DataTable();
			var tt = new $.fn.dataTable.TableTools( table );
			$( tt.fnContainer() ).insertBefore('div.actions2');
		}
		else if(current_detail=='arrival')
		{
			var table = $('#example3').DataTable();
			var tt = new $.fn.dataTable.TableTools( table );
			$( tt.fnContainer() ).insertBefore('div.actions3');
		}
		else if(current_detail=='departure')
		{
			var table = $('#example4').DataTable();
			var tt = new $.fn.dataTable.TableTools( table );
			$( tt.fnContainer() ).insertBefore('div.actions4');
		}
		else if(current_detail=='modify')
		{
			var table = $('#example5').DataTable();
			var tt = new $.fn.dataTable.TableTools( table );
			$( tt.fnContainer() ).insertBefore('div.actions5');
		}
	}
<?php } ?>



	</script>
    
    <?php if(uri(3)=='report_revenue' || uri(3)=='average_revenue' || uri(3)=='report_guest' || uri(3)=='nights_revenue' || uri(3)=='report_reservation') { ?>
    <script type="text/javascript">
(function($){ // encapsulate jQuery
	$(function () {
		var graph=$('#revenue_date').val();
		var graph1=$('#revenue_value').val();
		var show_text = $('#show_text').val();
		var g=JSON.parse(graph);
		var g1=JSON.parse(graph1);
	    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: show_text
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: g,
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' '
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: show_text,
            data: g1
        }]
    });
});
})(jQuery);

function graph_replace()
{
		var graph=$('#revenue_date').val();
		var graph1=$('#revenue_value').val();
		var show_text = $('#show_text').val();
		var g=JSON.parse(graph);
		var g1=JSON.parse(graph1);
	    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: show_text
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: g,
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' '
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: show_text,
            data: g1
        }]
    });
	
		reports_dates();
		reports_dates2();
		reports_dates3();
		reports_dates4();
		reports_dates5();
}


  </script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["geomap"]});
      google.setOnLoadCallback(drawMap);

      function drawMap() {
		  
		  var graph=$('#revenue_graphnew').val();
		  var g=JSON.parse(graph);
		
        var data = google.visualization.arrayToDataTable(g);

        var options = {};
        options['dataMode'] = 'regions';
		options['width'] = '1050px';
		options['height'] = '400px';

        var container = document.getElementById('regions_div');
        var geomap = new google.visualization.GeoMap(container);

        geomap.draw(data, options);
      };
	  
	function reports_dates(){

       var nowTemp = new Date();



       var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



       var checkin = $('#report_from_date').datepicker({



         onRender: function(date) {



           return date.valueOf() < now.valueOf() ? '' : '';



         }



       }).on('changeDate', function(ev) {



         if (ev.date.valueOf() > checkout.date.valueOf()) {



           var newDate = new Date(ev.date)



           newDate.setDate(newDate.getDate() + 1);



           checkout.setValue(newDate);



         }



         checkin.hide();

		 //revenue_report();

         $('#report_to_date')[0].focus();



       }).data('datepicker');



       var checkout = $('#report_to_date').datepicker({



         onRender: function(date) {



           return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';



         }



       }).on('changeDate', function(ev) {



         checkout.hide();

		 revenue_report();

       }).data('datepicker');



    };
	
	function reports_dates2(){

       var nowTemp = new Date();



       var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



       var checkin = $('#reservation_from_date').datepicker({



         onRender: function(date) {



           return date.valueOf() < now.valueOf() ? '' : '';



         }



       }).on('changeDate', function(ev) {



         if (ev.date.valueOf() > checkout.date.valueOf()) {



           var newDate = new Date(ev.date)



           newDate.setDate(newDate.getDate() + 1);



           checkout.setValue(newDate);



         }



         checkin.hide();

		 //revenue_report();

         $('#reservation_to_date')[0].focus();



       }).data('datepicker');



       var checkout = $('#reservation_to_date').datepicker({



         onRender: function(date) {



           return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';



         }



       }).on('changeDate', function(ev) {



         checkout.hide();

		 report_reservation();

       }).data('datepicker');



    };
	
	function reports_dates3(){

       var nowTemp = new Date();



       var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



       var checkin = $('#night_from_date').datepicker({



         onRender: function(date) {



           return date.valueOf() < now.valueOf() ? '' : '';



         }



       }).on('changeDate', function(ev) {



         if (ev.date.valueOf() > checkout.date.valueOf()) {



           var newDate = new Date(ev.date)



           newDate.setDate(newDate.getDate() + 1);



           checkout.setValue(newDate);



         }



         checkin.hide();

		 //revenue_report();

         $('#night_to_date')[0].focus();



       }).data('datepicker');



       var checkout = $('#night_to_date').datepicker({



         onRender: function(date) {



           return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';



         }



       }).on('changeDate', function(ev) {



         checkout.hide();

		 nights_report();

       }).data('datepicker');



    };
	
	function reports_dates4(){

       var nowTemp = new Date();



       var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



       var checkin = $('#average_from_date').datepicker({



         onRender: function(date) {



           return date.valueOf() < now.valueOf() ? '' : '';



         }



       }).on('changeDate', function(ev) {



         if (ev.date.valueOf() > checkout.date.valueOf()) {



           var newDate = new Date(ev.date)



           newDate.setDate(newDate.getDate() + 1);



           checkout.setValue(newDate);



         }



         checkin.hide();

		 //revenue_report();

         $('#average_to_date')[0].focus();



       }).data('datepicker');



       var checkout = $('#average_to_date').datepicker({



         onRender: function(date) {



           return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';



         }



       }).on('changeDate', function(ev) {



         checkout.hide();

		 average_report();

       }).data('datepicker');



    };
	
	function reports_dates5(){

       var nowTemp = new Date();



       var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



       var checkin = $('#guest_from_date').datepicker({



         onRender: function(date) {



           return date.valueOf() < now.valueOf() ? '' : '';



         }



       }).on('changeDate', function(ev) {



         if (ev.date.valueOf() > checkout.date.valueOf()) {



           var newDate = new Date(ev.date)



           newDate.setDate(newDate.getDate() + 1);



           checkout.setValue(newDate);



         }



         checkin.hide();

		 //revenue_report();

         $('#guest_to_date')[0].focus();



       }).data('datepicker');



       var checkout = $('#guest_to_date').datepicker({



         onRender: function(date) {



           return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';



         }



       }).on('changeDate', function(ev) {



         checkout.hide();

		 report_guest();

       }).data('datepicker');



    };
	  
	function revenue_report(){	

	 $("#heading_loader").fadeIn("slow");

	 var report_revenue = $('#report_revenue').val();

	 var channel_revenue = $('#channel_revenue').val();

	 var from_date = $('#report_from_date').val();

	 var to_date = $('#report_to_date').val();



	 if(report_revenue!='' && channel_revenue!='' && from_date == '' && to_date == ''){

	$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/last_seven_days');?>",

		data:{"channel_revenue":channel_revenue,"report_revenue":report_revenue},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	 }

	else if(report_revenue!='' && channel_revenue!='' && from_date!='' && to_date!='')

	 {

		$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/last_seven_days');?>",

		data:{"report_revenue":report_revenue,"channel_revenue":channel_revenue,"from_date":from_date,"to_date":to_date},

		success:function(msg){

			// alert(msg);

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	 }

		return false;

	}

    function nights_report(){

	$("#heading_loader").fadeIn("slow");

	 var report_night = $('#report_night').val();

	 var channel_night = $('#channel_night').val();

	 var from_date = $('#night_from_date').val();

	 var to_date = $('#night_to_date').val();

	 // alert(from_date);

	 if(report_night!='' && channel_night!='' && from_date == '' && to_date == ''){

	$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/nights_last_seven_days');?>",

		// data:"days="+days,

		data:{"report_night":report_night,"channel_night":channel_night},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	 }

	 else if(report_night!='' && channel_night!='' && from_date != '' && to_date != '')

	 {

		 $.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/nights_last_seven_days');?>",

		// data:"days="+days,

		data:{"report_night":report_night,"channel_night":channel_night,"from_date":from_date,"to_date":to_date},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	 }

	 return false;

	}

	function average_report(){

	$("#heading_loader").fadeIn("slow");

	 var report_average = $('#report_average').val();

	 var channel_average = $('#channel_average').val();

	 var from_date = $('#average_from_date').val();

	 var to_date = $('#average_to_date').val();

	  if(report_average!='' && channel_average!='' && from_date == '' && to_date == ''){

		$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/average_last_seven_days');?>",

		data:{"report_average":report_average,"channel_average":channel_average},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	  }

	  else if(report_average!='' && channel_average!='' && from_date != '' && to_date != '')

	  {

		  $.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/average_last_seven_days');?>",

		data:{"report_average":report_average,"channel_average":channel_average,"from_date":from_date,"to_date":to_date},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();

		}

		});

	  }

		return false;
	}

	function report_reservation(){

	$("#heading_loader").fadeIn("slow");

	 var report_reseravtion = $('#report_reseravtion').val();

	 var channel_reservation = $('#channel_reservation').val();

	 var from_date = $('#reservation_from_date').val();

	 var to_date = $('#reservation_to_date').val();

	 if(report_reseravtion!='' && channel_reservation!='' && from_date == '' && to_date == ''){

	$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/reservation_graph');?>",

		data:{"report_reseravtion":report_reseravtion,"channel_reservation":channel_reservation},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();

		}

		});

	  }

	  else if(report_reseravtion!='' && channel_reservation!='' && from_date!='' && to_date!='')

	 {

		 $.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/reservation_graph');?>",

		data:{"report_reseravtion":report_reseravtion,"channel_reservation":channel_reservation,"from_date":from_date,"to_date":to_date},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();
		}

		});

	 }

		return false;}

	function report_guest(){

	$("#heading_loader").fadeIn("slow");

	 var report_guests = $('#report_guests').val();

	 var channel_guests = $('#channel_guests').val();

	 var from_date = $('#guest_from_date').val();

	 var to_date = $('#guest_to_date').val();

	 if(report_guests!='' && channel_guests!='' && from_date == '' && to_date == ''){

		$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/guest_graph');?>",

		data:{"report_guests":report_guests,"channel_guests":channel_guests},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();

		}

		});

	 }

	 else if(report_guests!='' && channel_guests!='' && from_date != '' && to_date != '')

	 {

		$.ajax({

		type:"POST",

		url:"<?php echo site_url('reservation/guest_graph');?>",

		data:{"report_guests":report_guests,"channel_guests":channel_guests,"from_date":from_date,"to_date":to_date},

		success:function(msg){

			$('#graph_replace').html(msg);

			$("#heading_loader").fadeOut("slow");

			graph_replace();

			drawMap();

		}

		});

	 }

		return false;}

</script>
    <?php } ?>
  
<!-- Credit -->
<div class="modal fade dialog-2 " id="M_credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add your credit card details</h4>
      </div>
      <div class="modal-body sign-up-m">
     
   
    <form role="form" class="form-horizontal cls_mar_top" name="card" id="card2" method="post" action="<?php echo lang_url();?>channel/manage_credit_cards/add">
    
    
            
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
First Name <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder="First Name" id="c_fname" name="c_fname" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Last Name <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder="Last Name" id="c_lname" name="c_lname" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Credit card number <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder="Credit card number" id="card_number" name="card_number" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

Exp. month <span class="errors">*</span></p>
    <div class="col-sm-6">
   
      <select name="month" class="form-control" >
      <option value=""></option>
      <?php for($i=1; $i<=12; $i++) { ?>
      <option value="<?php echo $i;?>"><?php echo $i;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 Exp. year <span class="errors">*</span></p>
    <div class="col-sm-6">
  
     <select name="year" class="form-control" >
     <option value=""></option>
     <?php 
	 $curr_year = date('Y');
	 $end_year = date("Y", strtotime("+15 years"));
	 for($i=$curr_year; $i<=$end_year; $i++) { ?>
      <option value="<?php echo $i;?>"><?php echo $i;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
CVV <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="password" placeholder="CVV" id="cvv" name="cvv" class="form-control">
    </div>
  </div>
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3"> Billing zip  <span class="errors">*</span></p>
    <div class="col-sm-6">
      <input type="text" placeholder=" Billing zip" id="bill_zip" name="bill_zip"class="form-control">
    </div>
  </div>-->
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Add Card</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
  
</form>
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
<!-- Credit	 -->

<!-- Room -->
<?php if(uri(3)=='manage_rooms') { ?>
<div class="modal fade dialog-3 " id="M_room" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add room</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="room_form" id="room_form" method="post" action="<?php echo lang_url();?>channel/manage_rooms/add" enctype="multipart/form-data">
      
      <div class="box-dialog row-pad-top-20">
 
  	  <!--<div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Type <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"><select name="property_type" id="property_type" class="form-control" >
	  <?php /*$room_types = get_data(TBL_ROOM,array('status'=>'1'))->result_array();
	  foreach($room_types as $room) 
	  { extract($room);*/ ?>
      <option value="<?php //echo $room_id;?>"><?php //echo $room_type;?></option>
      <?php /*}*/ ?>
      </select></div>
 </div>-->
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Name <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="text" placeholder="Room Name" id="property_name" name="property_name" class="form-control"></div>
 </div>
 
 	  <!--<div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Allotment <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="number" placeholder="Allotment" id="allotment" name="allotment" class="form-control" value="1"></div>
 </div>-->
 
	  <!--<div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Capacity <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="number" placeholder="Room capacity" id="add_existing_room_count" name="existing_room_count" class="form-control" value="2" min="1"></div>
 </div>-->
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Occupancy Adults <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="number" placeholder="Occupancy Adults" id="add_member_count" name="member_count" class="form-control" value="2" min="1"></div>
 </div>
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Occupancy Childrer <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"> <input type="number" placeholder="Occupancy Childer" id="children" name="children" class="form-control" value="0"></div>
 </div>
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Pricing Type <span class="errors">*</span></span></div>
	<div class="col-md-8 col-sm-8 mar-top10">
		<!--<select name="pricing_type" id="pricing_type" class="form-control" >
		<option value="1" selected="selected">Room based pricing</option>
		<option value="2" >Guest based pricing</option>
		</select>-->
		Room based pricing
		<input type="hidden" value="1" name="pricing_type">
	</div>
 </div>
 
	  <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Selling Period <span class="errors">*</span></span></div>
 <div class="col-md-8 col-sm-8"><select name="selling_period" id="selling_period" class="form-control" >
	  
      <option value="1" selected="selected">Daily</option>
      <option value="2">Weekly</option>
      <option value="3" >Monthly</option>
      </select></div>
 </div>
 
      <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Description <span class="errors"></span></span></div>
 <div class="col-md-8 col-sm-8"><textarea class="form-control" rows="2" placeholder="Describe the room type. Maximum of 140 characters." name="description" id="description"></textarea></div>
 </div>
 
	  <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Display room on calendar <span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="radio" value="1" checked="checked" name="droc" id="droc"/> Yes <input type="radio" value="2" name="droc" id="droc1"/> No</div>
	  </div>
 
	  <!--<div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Non Refundable <span class="errors"> </span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="checkbox" value="1" name="non_r" id="add_non_r" class="non_r" /></div>
	  </div>-->
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Base price / Per night <?php //echo $currency;?><span class="errors">*</span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><div class="input-group in-p open">
      <div class="input-group-addon"><?php echo get_data(TBL_CUR,array('currency_id'=>$currency))->row()->currency_code; ?></div>
      <input type="text" class="form-control" placeholder="Price" id="add_price" name="price" onblur="set_prices(this.value);" onchange="set_prices(this.value);" onkeyup="set_prices(this.value);" value="100.00">
    </div></div>
	  </div>
      <input type="hidden" name="base_price" id="base_price" value="100.00"/>
      <div class="row">
	  <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Image<span class="errors"></span></span></div>
	  <div class="col-md-8 col-sm-8 mar-top7"><input type="file" name="room_image" id="room_image" class="form-control"/></div>
	  </div>
  	  </div>
      <div class="col-md-6">

  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
Room Name <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="Room Name" id="property_name" name="property_name" class="form-control">
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

Room Type <span class="errors">*</span></p>
    <div class="col-sm-7">
		   
      <select name="property_type" id="property_type" class="form-control" >
	  <?php /* $room_types = get_data(TBL_ROOM,array('status'=>'1'))->result_array();
	  foreach($room_types as $room) 
	  { extract($room); */ ?>
      <option value="<?php //echo $room_id;?>"><?php //echo $room_type;?></option>
      <?php /* } */ ?>
      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

	Pricing type <span class="errors">*</span></p>
    <div class="col-sm-7">
		   
      <select name="property_type" id="pricing_type" class="form-control" >
	  
      <option value="1" >Room based pricing</option>
      <option value="2" selected="selected">Guest based pricing</option>

      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

Selling Period <span class="errors">*</span></p>
    <div class="col-sm-7">
		   
      <select name="property_type" id="selling_period" class="form-control" >
	  
      <option value="1" selected="selected">Daily</option>
      <option value="2">Weekly</option>
      <option value="2" >Monthly</option>
      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 Room capacity  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="existing_room_count" id="existing_room_counts" class="form-control" >
     <?php /* for($e=1; $e<=99; $e++) { */ ?>
      <option value="<?php //echo $e;?>"><?php //echo $e;?></option>
      <?php /* } */ ?>
      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 Occupancy Adults<span class="errors">*</span></p>
    <div class="col-sm-7">
  	<input type="number" id="member_count" name="member_count" class="form-control" value="2" min="1"/>
     <select name="member_count" id="member_count" class="form-control" >
       <?php /* for($i=1; $i<=34; $i++) { */ ?>
      <option value="<?php //echo $i;?>"><?php //echo $i;?></option>
      <?php /* } */ ?>
      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 Occupancy Childer
  <span class="errors">*</span></p>
    <div class="col-sm-7">
  <input type="number" id="children" name="children" class="form-control" value="2" min="1"/>
     <select name="children" id="children" class="form-control" >
     <?php /* for($c=0; $c<=9; $c++) { */ ?>
      <option value="<?php //echo $c;?>"><?php //echo $c;?></option>
      <?php /* } */ ?>
      </select>
    </div>
  </div>-->
  
  <!--<div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Description <span class="errors">*</span></p>
    <div class="col-sm-7">
    <textarea class="form-control" name="description" id="description"></textarea>
    </div>
  </div>-->
   
 <!--<div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Add Room</button> 

    </div>
  </div>-->
  
  </div>
  
  	  
      <!--<div class="col-md-6">
      <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Default Room Price <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="price" id="price" name="price" class="form-control">
    </div>
  </div>
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Country <span class="errors">*</span></p>
    <div class="col-sm-7">
    <select class="form-control" name="country" id="add_country">
    <?php /* $country = get_data('country')->result_array();
	foreach($country as $value) { 
	extract($value); */ ?>
    <option value="<?php //echo $id;?>"><?php //echo $country_name;?></option>
    <?php /* } */ ?>
	</select>
    </div>
  </div>
  
    <div id="add_state_div">
    </div>
  
    <div id="add_city_div">
    </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Address <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="address" id="address" name="address" class="form-control">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Zip <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="zip" id="zip" name="zip" class="form-control">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Email <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="email" placeholder="Email" id="email" name="email" class="form-control">
    </div>
  </div>
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Mobile <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="mobile" id="phone" name="mobile" class="form-control">
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Display room on calendar <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="radio" value="1" checked="checked" name="droc" id="droc"/> Yes <input type="radio" value="2" name="droc" id="droc1"/> No
    </div>
  </div>
  
  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">	
Non refundable <span class="errors"> </span></p>
    <div class="col-sm-7">
      <input type="checkbox" value="1" name="non_r" />
    </div>
  </div>
  
  
  
  
    <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3"> Image  <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="file" name="room_image" onchange="get_file()" id="room_image" class="form-control"/>
<span style="color:red;display:none" id="error_cont" ></span>
    </div>
  </div>
  </div>-->
  
  	  <div class="col-md-12 guest_based">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th>Number of guests</th>
        <th>Refundable</th>
        <th class="non_refund">Non refundable</th>
        </tr>
        </thead>
        <tbody class="data">
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">1</span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_1[]" id="method_1">
  		<option value="+">+</option>
  		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="type_1[]" id="type_1">
  		<option value="Rs">Rs</option>
  		<option selected value="%"> %</option>
		</select>
        </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
    	<input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_1[]" id="amt_1" custom="1" method="refun">
	    </div>
        </div>
  		<input type="hidden" value="100.00" id="h_total_1" name="h_total_1[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_1">100.00</p></div>
        </td>
        <td class="pa-t-pa-b non_refund">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_1[]" id="n_method_1">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="n_type_1[]" id="n_type_1">
  		<option value="Rs">Rs</option>
 		<option selected value="%">%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_1[]" id="n_amt_1" custom="1" method="n_refun">
	    </div>
        </div>
  		<input type="hidden" value="100.00" id="n_h_total_1" name="n_h_total_1[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_1">100.00</p></div>
        </td>
        </tr>
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">2</span></div></td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="method_2[]" id="method_2">
  		<option value="+">+</option>
  		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="type_2[]" id="type_2">
		<option value="Rs">Rs</option>
        <option selected value="%"> %</option>
		</select>
        </div>

		<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh cal_amt" name="d_amt_2[]" id="amt_2" custom="2" method="refun">
   		</div>
        </div>
  		<input type="hidden" value="100.00" id="h_total_2" name="h_total_2[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="total_2">100.00</p></div>
        </td>
        <td class="pa-t-pa-b non_refund">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="n_method_2[]" id="n_method_2">
  		<option value="+">+</option>
 		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="n_type_2[]" id="n_type_2">
  		<option value="Rs">Rs</option>
  		<option selected value="%">%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
		<input type="text" value="0.00" class="form-control widh cal_amt" name="n_d_amt_2[]" id="n_amt_2" custom="2" method="n_refun">
		</div>
        </div>
 		<input type="hidden" value="100.00" id="n_h_total_2" name="n_h_total_2[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="n_total_2">100.00</p></div>
        </td>
        </tr>
        </tr>
        </tbody>
        </table>
        </div>
  </div>
  
	  <div class="col-md-12 room_based_add">
      <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th class="room_based_add text-center" colspan="2">Non refundable</th>
        </tr>
        </thead>
        <tbody class="datas">
        <tr id="item0">
        <tr>
        <td class="pa-t-pa-b room_based_add">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control" name="r_n_method_1[]" id="r_n_d_method_1">
  		<option value="+">+</option>
		<option value="-">-</option>
		</select>
		</div>

		<div class="col-md-3 col-sm-3"> 
		<select class="form-control" name="r_n_type_1[]" id="r_n_d_type_1">
  		<option value="Rs">Rs</option>
 		<option selected value="%">%</option>
		</select>
        </div>

	 	<div class="col-md-3 col-sm-3">
		<div class="ssw ki"> 
	    <input type="text" value="0.00" class="form-control widh a_r_cal_amt" name="r_n_d_amt_1[]" id="r_n_d_amt_1" custom="1" method="n_refun">
	    </div>
        </div>
  		<input type="hidden" value="100.00" id="r_n_d_h_total_1" name="r_n_h_total_1[]" class="tkk"/>
		<div class="col-md-3 col-sm-3"><p class="tk" id="r_n_d_total_1">100.00</p></div>
        </td>
        </tr>
        </tr>
        </tbody>
        </table>
        </div>
  </div>
     
      <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Add Room</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->
    </div>
  </div>
  
     </form>
     </div>
   
  
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>
<?php } ?>
<!-- Room -->

<!-- User -->

<div class="modal fade dialog-2 " id="M_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content" id="success_msg">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add User</h4>	

      </div>

      <div class="modal-body sign-up-m">

	  

     <div class="row">

      <form role="form" class="form-horizontal cls_mar_top" name="user_form" id="user_form" method="post" action="<?php echo lang_url();?>channel/manage_users/UserAdd" enctype="multipart/form-data">

      <div class="col-md-12">

  

  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Name <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="text" placeholder="User Name" id="user_name" name="user_name" class="form-control">

    </div>

  </div>

  

  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Email <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="email" placeholder="User Email" id="email_address" name="email_address" class="form-control">

    </div>

  </div>

  

  	 <!-- <div class="form-group">

    <p class="col-sm-4 control-label" for="inputPassword3">

   Password <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="password" placeholder="Password" id="password" name="password" class="form-control">

    </div>

  </div>

	

      <div class="form-group">

    <p class="col-sm-4 control-label" for="inputPassword3">

Confirm Password <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="password" placeholder="Confirmpassword" id="cpassword" name="cpassword" class="form-control">

    </div>

  </div>

  

 	<div class="form-group">

    <p class="col-sm-4 control-label" for="inputPassword3">

    User Access <span class="errors">*</span></p>

    <div class="col-sm-7 nf-select">

    <?php 

		/* $access = get_data(TBL_ACCESS,array('status'=>1))->result_array(); */?>

		<input type="checkbox" id="checkAll">All

		<?php 

		/* foreach($access as $u_acc)

		{

			extract($u_acc); */

	?>

    <input type="checkbox" class="select_name" value="<?php //echo $acc_id?>" name="access[]" id="access" /> <?php //echo $acc_name?> 

    <?php /* } */ ?>

     </div>

  </div>  -->

  

  

  

   <div class="form-group">

    <div class="col-sm-offset-2 col-sm-8">

	

      <button class="btn btn-success hover-shadow pull-right" type="submit">Add User</button> 

	  

     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->

    </div>

  </div>

  

	  </div>

      

     </form>

     </div>

   

      </div>

      

    </div>

  </div>

</div>
<!--<div class="modal fade dialog-2 " id="M_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="success_msg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add User</h4>
      </div>
      <div class="modal-body sign-up-m">
     <div class="row">
      <form role="form" class="form-horizontal cls_mar_top" name="user_form" id="user_form" method="post" action="<?php //echo base_url();?>channel/manage_users/UserAdd" enctype="multipart/form-data">
      <div class="col-md-12">
  
	  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputEmail3">
User Name <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="text" placeholder="User Name" id="user_name" name="user_name" class="form-control">
    </div>
  </div>
  
  	  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Password <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="password" placeholder="Password" id="password" name="password" class="form-control">
    </div>
  </div>
	
      <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
Confirm Password <span class="errors">*</span></p>
    <div class="col-sm-7">
      <input type="password" placeholder="Confirmpassword" id="cpassword" name="cpassword" class="form-control">
    </div>
  </div>
  
 	  <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">
User Access <span class="errors">*</span></p>
    <div class="col-sm-7 nf-select">
    <?php 
		//$access = get_data(TBL_ACCESS,array('status'=>1))->result_array();
		//foreach($access as $u_acc)
		//{
			//extract($u_acc);
	?>
    <input type="checkbox" class="" value="<?php //echo $acc_id?>" name="access[]" id="access" /> <?php //echo $acc_name?> 
    <?php //} ?>
     </div>
  </div>
  
  
  
      <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button class="btn btn-success hover-shadow pull-right" type="submit">Add User</button> 
     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>
    </div>
  </div>
  
	  </div>
      
     </form>
     </div>
   
  
  
   
   
   
   
      </div>
      
    </div>
  </div>
</div>-->

<div class="modal fade dialog-2 " id="add_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content" id="success_msg">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title pad-b text-uppercase text-center" id="myModalLabel">Add User</h4>	

      </div>

      <div class="modal-body sign-up-m">

	  

     <div class="row">

      <form role="form" class="form-horizontal cls_mar_top" name="add_users" id="add_users" method="post" action="<?php echo lang_url();?>channel/add_user_det" enctype="multipart/form-data">

      <div class="col-md-12">

  <!--<div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

     User Name <span class="errors">*</span></p>

    <div class="col-sm-7">

	  <select class="form-control" value="selected" name="user_name[]">

	    <?php 

		/* $users = $this->channel_model->all_users();
		if($users!='')
		{

		    foreach($users as $as)

			{ */

		?>

	   <option value="<?php //echo $as->user_id; ?>"><?php //echo $as->user_name; ?></option>

	   

	  <?php /* } } else { */ ?>
      <option value=""> </option>	
      <?php /* } */ ?>

	  </select>

    </div>

  </div>-->
  
   <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Name <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="text" placeholder="User Name" id="username" name="user_name" class="form-control">

    </div>

  </div>
 	
  
 <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Email <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="email" placeholder="User Email" id="user_email" name="email_address" class="form-control">

    </div>

  </div>
  
  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Password <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Password">

    </div>

  </div>
  
  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    Confirm Password <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password">

    </div>

  </div>
  
  <div class="form-group">
  
<div class="col-sm-12 nf-select">
<div class="col-md-1">
   </div>
<div class="col-md-10">
     <table class="table table-striped table-bordereds table-hover">
     <thead>
     <tr>
     <th> User Access <span class="errors">*</span></th>
     <th> View </th>
     <th> Edit</th>
     </tr>
     <tbody>
     <tr> <td> All </td> <td> <input type="checkbox" id="select_all"> </td> <td> <input type="checkbox" id="selectall"></td> </tr>
     <?php 	
		$access = get_data(TBL_ACCESS)->result_array();
		foreach($access as $u_acc)
		{
			extract($u_acc);
			
	 ?>
     <tr> 
     <td> <?php echo $acc_name;?> </td> 
    <?php 
	if($acc_id!='5')
	{
	?>
     <td> <input type="checkbox" class="my_checkbox edit_<?php echo $acc_id; ?>"  onclick="return view_check('<?php echo $acc_id; ?>');"value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][view]" id="view_<?php echo $acc_id; ?>"> </td> 
    <?php } else { ?> <td> </td><?php } ?>
    <?php
	if($acc_id!='8' && $acc_id!='1')
	{
	?>
     <td><input type="checkbox" class="mycheckbox" onclick="return edit_check('<?php echo $acc_id; ?>');" value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][edit]" id="edit_<?php echo $acc_id; ?>"> </td>
    <?php
	}
	else
	{
	?>
    <td> </td>
    <?php 
	} 
	?>
     
     </tr>
     <?php 
		}
     ?>
     </tbody>
     </thead>
     </table>
    </div>
    
<!--<div class="col-md-6">
     
		<h5>View</h5>
    <?php 	
		//$access = get_data(TBL_ACCESS)->result_array();?>

		<input type="checkbox" id="select_all"> All

		<?php 
        /*$a=0;
		foreach($access as $u_acc)
		{
			extract($u_acc);
			if($acc_id!='5')
			{*/
	    ?>
	<br>
    <input type="checkbox" class="my_checkbox edit_<?php //echo $acc_id; ?>"  onclick="return view_check('<?php //echo $acc_id; ?>');"value="<?php //echo $acc_id;?>" name="access_options[<?php //echo $acc_id;?>][view]" id="view_<?php //echo $acc_id; ?>"> <?php //echo $acc_name;?> 
		
    <?php //$a++;} else{ echo '<br>';}} ?>
	
		<input type="hidden" name="total_success" value="<?php //echo $a; ?>">
     </div>-->
     
     <!--<div class="col-md-6">
<h5>Edit</h5>
    <?php 	
		//$access = get_data(TBL_ACCESS)->result_array();?>

		<input type="checkbox" id="selectall"> All

		<?php 
        /*$a=0;
		 
		foreach($access as $u_acc)

		{

			extract($u_acc);
			if($acc_id!='8' && $acc_id!='1')
			{*/

	?>
	<br>
    <input type="checkbox" class="mycheckbox" onclick="return edit_check('<?php //echo $acc_id; ?>');" value="<?php //echo $acc_id;?>" name="access_options[<?php //echo $acc_id;?>][edit]" id="edit_<?php //echo $acc_id; ?>"> <?php //echo $acc_name;?> 
		
    <?php //$a++;} else{ echo '<br>';}} ?>
		
		<input type="hidden" name="total_success" value="<?php //echo $a; ?>">
		
     </div>-->
</div>
</div>

   <div class="form-group">

    <div class="col-sm-offset-2 col-sm-8">

      <button class="btn btn-success hover-shadow pull-right" type="submit" name="add" value="save">Add User</button>

     <!-- <button class="btn btn-default hover-shadow pull-right" type="submit">Cancel</button>-->

    </div>

  </div>

  

	  </div>

      

     </form>

     </div>

   

      </div>

      

    </div>

  </div>

</div>
<!-- User -->

<!--Gallery-->
<div class="gallery-new">
<div class="modal fade dialog-3" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Photos</h4>
      </div>
      <div class="modal-body light-box-gal">

<div class="row">
<div class="col-md-2 col-sm-2">
<span>Select Photos</span>
</div>
<form method="post" id="upload_photos" enctype="multipart/form-data" action="<?php echo lang_url();?>channel/manage_photos/new">
<input type="hidden" id="hotel_id" name="hotel_id" />
<div class="col-md-2 col-sm-2">
<input type="file" id="immm" class="immm" reqired name="hotel_image[]" multiple  accept="image/png,image/gif,image/jpeg">
<!--<div class="validation" style="display:none;"> Upload Max 4 Files allowed </div>-->
</div>
<div class="col-md-4 col-sm-4 col-md-offset-1 col-sm-offset-1"><input class="btn btn-success" type="submit" value="Upload" id="uploadButton" disabled="disabled"></div>
</form>
</div>
<br/>

<div class="new-galery" id="img_rplae">
<ul class="list-unstyled row" id="hot_pho">
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
<li class="col-md-3 col-sm-3"><a class="fancybox" href="<?php echo  base_url()?>user_assets/images/lig-1.jpg" data-fancybox-group="gallery"><img src="<?php echo  base_url()?>user_assets/images/ligh-1.jpg" alt="" class="img img-responsive img-thumbnail" /></a><div class="close-icon"><i class="fa fa-close"></i></div></li>
</ul>
</div>
      </div>
    </div>
  </div>
</div>
</div>
<!--Gallery-->

<!--Single Update-->
<div class="modal fade dialog-2" id="M_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Customize calendar</h4>
      </div>
      <form method="post" action="<?php echo lang_url();?>inventory/single_room">
      <div class="modal-body pop-n-b">
     <select class="selectpicker form-control" id="select_room" name="property_single">
     <?php 
	 	$total_room_types = get_data(TBL_ROOM,array('status'=>1))->result_array();
		$i=0;
		foreach($total_room_types as $toatl)
		{
			extract($toatl);
			
			$count = $this->inventory_model->count_room($room_id);
			if($count!=0)
			{
				
				
	 ?>
        <optgroup label="<?php echo $room_type;?>">
        <?php
				$this->db->order_by('property_id','desc');
				$get_room = get_data(TBL_PROPERTY,array('property_type'=>$room_id))->result_array();
				
				foreach($get_room as $av_room)
				{
					extract($av_room);
					
					if($i==0)
					{
						$pro_id = $property_id;
					}
		?>
          <option value="<?php echo $property_id; ?>"><?php echo ucfirst($property_name);?></option>
          <?php } ?>
        </optgroup>
     <?php
			$i++;}
		}
	?>
        
        
        
      </select>
      <hr>
      <p>Channels </p>
      <div class="check-bo">
      
      <div class="checkbox">
		<label>	<input type="checkbox" name="channel_all" checked="checked" class="channel_all" id="channel_all" value="all"> All </label>
	  </div>
       <div id="cha_replace">
     <?php 
			$get_channel_id = explode(',',get_data(TBL_PROPERTY,array('property_id'=>$pro_id))->row()->connected_channel);
			$get_channel = get_data(TBL_CHANNEL,array('status'=>'Active'))->result_array();
			if(count($get_channel_id)!='')
			{
				foreach($get_channel as $channel)
				{
					extract($channel);
					if(in_array($channel_id,$get_channel_id))
					{
		?>
 	  <div class="checkbox">
  		 <label>	<input type="checkbox" checked="checked" name="channel_single[]" class="channel_single" value="<?php echo $channel_id; ?>" >	<?php echo $channel_name?> 	</label>
	  </div>
      
      	<?php 		}
				}
			}
			else
			{
		  ?>
          <div class="checkbox text-danger">
  		 <label> No connected channel in active </label>
	  </div>
          <?php 
			}
			?>
            </div>
      </div>
      
      
      </div>
      <div class="modal-footer ne-blu">
        <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Continue</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--Single Update-->



<!-- Modal -->

<!-- Modal -->

<!-- Modal -->


<!-- Modal -->
<div class="modal fade" id="myModa-f1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit photo</h4>
      </div>
      <div class="modal-body">
      <div class="row">
      <div class="col-md-3 col-sm-3"><img src="<?php echo base_url();?>user_assets/images/ligh-1.jpg" class="img img-responsive img-thumbnail">
      </div>
      <div class="col-md-9 col-sm-9">
       <div id="suggestOnClick" class="col-md-12"></div>
       </div>
       </div>
       <div class="row">
      <div class="col-md-9 col-sm-9 col-sm-offset-3 col-md-offset-3">
       <div id="suggestOnClick2" class="col-md-12"></div>
       </div>
       </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->


<?php if(uri(2)=='channel' || uri(2)=='inventory' && uri(3)!='dashboard') { ?>
<script>
$().ready(function()
{
	var form = $('#register_form');
	$.validator.addMethod('positiveNumber',
	function(value) {
		return Number(value) > 0;
	}, 'Enter a positive number.');
	
	jQuery.validator.addMethod("lettersonly", 
	function(value, element) {
	 return this.optional(element) || /^[a-z,""]+$/i.test(value);
	}, "Letters only please");
	
	/*jQuery.validator.addMethod("customemail", function(value, element) 
	{
		return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
	}, "Please enter a valid email address.");*/
	
	//custom validation rule
	$.validator.addMethod("customemail", 
		function(value, element) {
			return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
		}, 
		"Sorry, I've enabled very strict email validation"
	);
<?php if(uri(3)!='advance_update'){ if(isset($active)) { if(count($active)!=0) { 
$i=1;
foreach($active as $acc) {
	extract($acc);?>
$('#room_form_<?php echo $property_id?>').validate({
			rules :
			{
				property_name_<?php echo $property_id?> :
				{
					required: true,
					/*remote: {
							  url:base_url+"channel/add_room_exists/<?php //echo $property_id;?>",
							  type: "post",
							  data: {
									  property_name: function()
									  {
										  return $("#property_name_<?php //echo $property_id;?>").val();
									  }
									}
							 },*/
				},
				property_type:
				{
					required: true,
					
				},
				allotment:
				{
					required: true,
					number : true,
					positiveNumber:true,
				},
				pricing_type:
				{
					required: true,
				},
				selling_period:
				{
					required: true,
				},
				droc:
				{
					required: true,
				},
				member_count:
				{
					required: true,
				},
				children:
				{
					required: true,
				},
				existing_room_count:
				{
					required: true,
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
				image:
				{
					required : true,
				},
				description:
				{
					required : true,
				},
				email_<?php echo $i?>: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/room_email_exists/<?php echo $property_id;?>",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#edit_email_<?php echo $i?>").val(); 
													}
												  }
										}
						},
				mobile_<?php echo $i?>: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/room_phone_exists/<?php echo $property_id;?>",
												type: "post",
												data: {
														mobile: function()
														{ 
															return $("#edit_phone_<?php echo $i?>").val(); 
														}
													  }
										}
							},
				zip:{required : true,positiveNumber:true ,minlength:6,maxlength:6},
				address:
				{
					required : true,
				},
				city:
				{
					required : true,
				},
				state:
				{
					required : true,
				},
				country:
				{
					required : true,
				}
				
			},
		
			messages:
			{
				card_number: "Please enter your credit card number",
				month: "Please select  your year month",
				year: "Please select  your exp. month",
				state: "Please select your state",
				cvv  :"Please enter your cvv number",
				bill_zip: "Please enter your bill zip",
			},
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
		
	})
	
$('#room_clone_<?php echo $i?>').validate({
			rules :
			{
				property_name_c<?php echo $i?> :
				{
					required: true,
					remote: {
							  url:base_url+"channel/add_room_exists/",
							  type: "post",
							  data: {
									  property_name: function()
									  {
										  return $("#property_name_c<?php echo $i;?>").val();
									  }
									}
							 },
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
			},
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
	})
<?php $i++;} } } }?>

<?php if(uri(3)!='advance_update'){ if(isset($inactive)) { if(count($inactive)!=0){
	$j=1;
	foreach($inactive as $iacc) {
	extract($iacc);?>
$('#room_form_i_<?php echo $j?>').validate({
			rules :
			{
				property_name_<?php echo $j?> :
				{
					required: true,
					remote: {
							  url:base_url+"channel/add_room_exists/<?php echo $property_id;?>",
							  type: "post",
							  data: {
									  property_name: function()
									  {
										  return $("#property_names_<?php echo $j;?>").val();
									  }
									}
							 },
				},
				property_type:
				{
					required: true,
				},
				pricing_type:
				{
					required: true,
				},
				selling_period:
				{
					required: true,
				},
				droc:
				{
					required: true,
				},
				member_count:
				{
					required: true,
				},
				children:
				{
					required: true,
				},
				existing_room_count:
				{
					required: true,
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
				image:
				{
					required : true,
				},
				description:
				{
					required : true,
				},
				email_<?php echo $j?>: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/room_email_exists/<?php echo $property_id;?>",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#edit_emails_<?php echo $j?>").val(); 
													}
												  }
										}
						},
				mobile_<?php echo $j?>: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/room_phone_exists/<?php echo $property_id;?>",
												type: "post",
												data: {
														mobile: function()
														{ 
															return $("#edit_phones_<?php echo $j?>").val(); 
														}
													  }
										}
							},
				zip:{required : true,positiveNumber:true ,minlength:6,maxlength:6},
				address:
				{
					required : true,
				},
				city:
				{
					required : true,
				},
				state:
				{
					required : true,
				},
				country:
				{
					required : true,
				}
				
			},
		
			messages:
			{
				card_number: "Please enter your credit card number",
				month: "Please select  your year month",
				year: "Please select  your exp. month",
				state: "Please select your state",
				cvv  :"Please enter your cvv number",
				bill_zip: "Please enter your bill zip",
			},
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
		
	})

$('#room_clone_i<?php echo $j?>').validate({
			rules :
			{
				property_name_c<?php echo $j?> :
				{
					required: true,
					remote: {
							  url:base_url+"channel/add_room_exists/",
							  type: "post",
							  data: {
									  property_name: function()
									  {
										  return $("#property_name_i<?php echo $j;?>").val();
									  }
									}
							 },
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
			},
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
	})
<?php $j++;} } } }?>

<?php
if(uri(3)!='advance_update'){
	if(isset($sub_users))
	{
if(count($sub_users)!=0) { 
$ii=1;
foreach($sub_users as $users) {
	/*extract($acc)*/;?>
	
$("#user_form_<?php echo $ii;?>").validate({
	
	// Rulse start here	
			rules: {
                    //account
					user_name_e<?php echo $ii;?>: {
								required: true,
								lettersonly: true,
								//minlength:5,
								remote: {
													  url:base_url+"channel/register_username_exists/<?php echo $users->user_id;?>",
													  type: "post",
													  data: {
															  user_name: function()
															  {
																  return $("#user_name_e<?php echo $ii;?>").val();
															  }
															}
													 },
							},
					user_email_e<?php echo $ii;?>: {
								required: true,
								customemail:true,
								remote: {
													  url:base_url+"channel/register_email_exists/<?php echo $users->user_id;?>",
													  type: "post",
													  data: {
															  email_address: function()
															  {
																  return $("#user_email_e<?php echo $ii;?>").val();
															  }
															}
													 },
							},
					"access[]":{required: true},
                },
				
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
		/*invalidHandler: function(form, validator){
	            var body = $("html, body");
				body.animate({scrollTop:0}, '500', 'swing', function() { 
				})*/
				 
			highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
				//errorClass: 'customErrorClass',
				//$('.nf-select').addClass('customErrorClass');

	});

<?php $ii++;} } } }?>

<?php 
if(uri(3)!='advance_update'){
	if(isset($credit_card)){
	if(count($credit_card)!=0) {
	
	$cc=1;
	foreach($credit_card as $value) { extract($value); ?>
	
	$('#card_<?php echo $cc;?>').validate({
		rules :
		{
			card_number :
			{
				required: true,
				creditcard: true
			},
			month:
			{
				required: true,
				
			},
			year:
			{
				required: true,
			},
			cvv:
			{
				required: true,
				positiveNumber:true ,
				minlength: 3,
				maxlength: 3
			},
			
			bill_zip:
			{
				required : true,
				number: true,
				positiveNumber:true ,
				minlength: 6,
				maxlength: 6
			},
			c_fname:
			{
				required: true,
				lettersonly: true,
			},
			c_lname:
			{
				required: true,
				lettersonly: true,
			},
			
		},
		
		messages:
		{
			card_number: "Please enter your credit card number",
			month: "Please select  your year month",
			year: "Please select  your exp. month",
			state: "Please select your state",
			cvv  :"Please enter your cvv number",
			bill_zip: "Please enter your bill zip",
		},
		errorPlacement: function(){
            return false;  // suppresses error message text
    		},
		highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.nf-select').addClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').addClass('customErrorClass');
			},
		unhighlight: function (element) { // revert the change done by hightlight
				$(element)
					.closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
				$(element)
					.closest('.form-control').removeClass('customErrorClass');
			},
		
	});
	
	<?php $cc++;} }} }?>

});
</script>
<?php } ?>
</body>

</html>


<!--http://www.snapdeal.com/flash-sale/yu/yunique-->



<script>
function get_file()
{

var extensions = new Array("jpg","jpeg","gif","png","bmp");
 
/*
// Alternative way to create the array
 
var extensions = new Array();
 
extensions[1] = "jpg";
extensions[0] = "jpeg";
extensions[2] = "gif";
extensions[3] = "png";
extensions[4] = "bmp";
*/
 
var image_file = document.room_form.room_image.value;

var image_length = document.room_form.room_image.value.length;
 
var pos = image_file.lastIndexOf('.') + 1;
 
var ext = image_file.substring(pos, image_length);
 
var final_ext = ext.toLowerCase();
 
for (i = 0; i < extensions.length; i++)
{
    if(extensions[i] == final_ext)
    {
    $('#error_cont').css('display','none');
    return true;
    }
}
$('#error_cont').css('display','block');
var htm="You must upload an image file with one of the following extensions: "+ extensions.join(', ') +".";
$('#error_cont').html(htm);
$('#room_image').val("");
return false;
}

$('#imagesaaaa').change(function(){

	var file_data = $('input[type="file"]')[0].files;

	$('#display_error').empty();

	

	var error=0;

	for(var i = 0;i<file_data.length;i++){

		FileUploadPath = file_data[i].name;
		$('#display_error').append('<div style="font-size: 14px">'+file_data[i].name+'</div>');
		var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

		if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {



		} else {

			//alert(file_data[i].name+' is not a valid file. Only allowed file types are gif, png, bmp, jpeg and jpg.');

			$('#display_error').append('<div class="dell alert alert-danger alert-dismissible" role="alert" style="width: 500px; font-size: 14px">'+file_data[i].name+' is not a valid file.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

			var error=1;
$('#btn').hide();
		}

	}
	if(error==0){

		$('#btn').show();

	}

});
<?php
if(uri(3)=='Payment_Success')
{
?>
setTimeout(function()
{
	 document.location=base_url+"channel/dashboard";
},
5000);
<?php } ?>

 $('form.idealforms').idealforms({

      silentLoad: false,

      rules: {
        'hotl_id': 'required',
		'username': 'required username ajax',
        'email': 'required email',
        'password': 'required pass',
        'confirmpass': 'required equalto:password',
        'date': 'required date',
        'picture': 'required extension:jpg:png',
        'website': 'url',
        'hobbies[]': 'minoption:2 maxoption:3',
        'phone': 'required phone',
        'zip': 'required zip',
        'options': 'select:default',
      },

      errors: {
        'username': {
          ajaxError: 'Username not available'
        }
      },

      onSubmit: function(invalid, e) {
        e.preventDefault();
        $('#invalid')
          .show()
          .toggleClass('valid', ! invalid)
          .text(invalid ? (invalid +' invalid fields') : 'All good!');
      }
    });

 $('form.idealforms').find('input, select, textarea').on('change keyup', function() {
      $('#invalid').hide();
    });

 $('form.idealforms').idealforms('addRules', {
      'comments': 'required minmax:50:200'
    });

 $('.prev').click(function(){
      $('.prev').show();
      $('form.idealforms').idealforms('prevStep');
    });

 $('.next').click(function(){
      $('.next').show();
      $('form.idealforms').idealforms('nextStep');
    });

$(document).ready(function(){
                $.datepicker.regional[""].dateFormat = 'M d,yy';
                $.datepicker.setDefaults($.datepicker.regional['']);
				$('#sample_13').dataTable().columnFilter({ 	
 			
			sPlaceHolder: "head:before",
 			aoColumns: [ 
						null,
						{ sSelector: ".col2", type:"select",values: [ 'New Booking', 'Cancelled', 'Modification', 'Pending' ]  },
						null,
						null,
						{ sSelector: ".col1", type:"select",values: <?php echo $reservation_all_channel;?>  },
						{ sSelector: ".col3", type:"date-range" },
						{ type: "date-range" },
						null,
						null
						]});
					});
						
toggle_visibility = function (id,cls){
var property		= 	id.split('_');
var property_id 	=	property[1];
var data ='MainCalendr';
var cal_start = $('#cal_start').val();
var cal_end = $('#cal_end').val();
var clss = $("."+cls).attr('class');

if(clss=='fa fa-plus '+cls || clss=='fa '+cls+' fa-plus')
{
	$("#heading_loader").fadeIn("slow");
	$("."+cls).switchClass('fa-plus', 'fa-minus');
	$.ajax({
				type: "POST",
				url: base_url+'ajax/showRatesGuestsCalendar',
				data: "source="+data+"&cal_start="+cal_start+"&cal_end="+cal_end+"&property_id="+property_id,
				success: function(result)
				{
					$('.show_content_'+property_id+':last').after(result);	
					$(".ss_main_rate").each(function() { 
					$(this).hide();
					});
					var e = $("."+id);
					
					$('.inline_price').editable({
						step: 'any',
					});

					$('.inline_availability').editable();

					$('.inline_minimum').editable();
					
					$("table#reservation_yes_tbl tbody tr").each(function() {        
					var cell = $.trim($(this).find('td').text());
					if (cell.length == 0){
						 $(this).remove();
					 } 
					//$('.contents4').hide();               
					});

					e.toggle();
					
					$("#heading_loader").fadeOut("slow");
				}
			});
}
else
{
	$("."+cls).switchClass('fa-minus', 'fa-plus');
	$(".contents_"+property_id).remove();
	var e = $("."+id);
	e.toggle();
}

}

show_detais = function (id){

/*var clss = $("."+cls).attr('class');

if(clss=='fa fa-plus '+cls || clss=='fa '+cls+' fa-plus')

{

	$("."+cls).switchClass('fa-plus', 'fa-minus');

}

else

{

$("."+cls).switchClass('fa-minus', 'fa-plus');

}*/

var e = $("#"+id);

e.toggle();
}

msccc = function (id,cls){
	
	var data		= 	id.split('_');
	var data 		=	data[0];
	var cal_start 	= 	$('#cal_start_'+cls).val();
	var cal_end 	= 	$('#cal_end_'+cls).val();	
	$('#cal_'+id).each(function() { 
		if(this.checked)
		{
			$("#heading_loader").fadeIn("slow");
			$.ajax({
				type: "POST",
				url: base_url+'ajax/showRestictions',
				data: "source="+data+"&cal_start="+cal_start+"&cal_end="+cal_end+"&channel="+cls,
				success: function(result)
				{
					$('#resp_div_'+cls).html(result);
					
					$('#resp_div_'+cls+' .mm_main_room').each(function(){
						var this_id=$(this).attr('room_id');
						var this_html=$(this).html();
						$('#ms_main_room_'+this_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .mm_main_rate').each(function(){
						var this_id=$(this).attr('room_id');
						var rate_id=$(this).attr('rate_id');
						var this_html=$(this).html();
						$('#ms_main_rate_'+this_id+'_'+rate_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .ss_main_room').each(function(){
						var this_id=$(this).attr('room_id');
						var this_html=$(this).html();
						$('#ss_main_room_'+this_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .ss_main_rate').each(function(){
						var this_id=$(this).attr('room_id');
						var rate_id=$(this).attr('rate_id');
						var this_html=$(this).html();
						$('#ss_main_rate_'+this_id+'_'+rate_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .cta_main_room').each(function(){
						var this_id=$(this).attr('room_id');
						var this_html=$(this).html();
						$('#cta_main_room_'+this_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .cta_main_rate').each(function(){
						var this_id=$(this).attr('room_id');
						var rate_id=$(this).attr('rate_id');
						var this_html=$(this).html();
						$('#cta_main_rate_'+this_id+'_'+rate_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .ctd_main_room').each(function(){
						var this_id=$(this).attr('room_id');
						var this_html=$(this).html();
						$('#ctd_main_room_'+this_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls+' .ctd_main_rate').each(function(){
						var this_id=$(this).attr('room_id');
						var rate_id=$(this).attr('rate_id');
						var this_html=$(this).html();
						$('#ctd_main_rate_'+this_id+'_'+rate_id+'_'+cls).html(this_html);
					})
					
					$('#resp_div_'+cls).html('');
					
					rowsp = parseInt($('.msccrow_'+cls).attr('rowspan'))+1;
					$('.msccrow_'+cls).attr('rowspan',rowsp);
					$('.inline_minimum').editable();
					var e = $("."+id);
					e.toggle();
					
					$("#heading_loader").fadeOut("slow");
				}
			});
		}
		else
		{
			rowsp = parseInt($('.msccrow_'+cls).attr('rowspan'))-1;
	        $('.msccrow_'+cls).attr('rowspan',rowsp);
			$("."+id).html('');
			var e = $("."+id);
			e.toggle();
		}
    });


}

</script>


<script type="text/javascript" src="<?php echo base_url();?>admin_assets/js/list.js"></script>

<script>
$().ready(function()
{
  var options = { valueNames: [ 'name', 'born' ] };
  
  var hackerList = new List('hacker-list', options);
});
</script>

<?php
  if($this->session->userdata('last_page')!='')
  {
	  ?>
      <script type="text/javascript">
	  $(window).load(function() 
	  {
			setTimeout(function()
			{
				$.ajax({
			type:'POST',
			url:base_url+'channel/unset_last_page',
			success:function(result)
			{
				
			}
		});
			},
			3000);
		});
	  </script>
      <?php
  }
?>

<script>

$("#add_users").submit(function(){

var checked = $("#add_users input:checked").length > 0;

if (!checked){

alert("Please check at least one checkbox");

return false;

}

});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>
<?php echo theme_js('jquery-asPieProgress.js', true);?>
<script type="text/javascript">
    jQuery(document).ready(function($){
		
		var persent_today=$('#persent_today').val();
		if(persent_today==100 || persent_today=='100')
		{
			var persent_today_colr = '#ef1e25';
		}
		else
		{
			var persent_today_colr = '#26C281';
		}
		
		var persent_week=$('#persent_week').val();
		if(persent_week==100 || persent_week=='100')
		{
			var persent_week_colr = '#ef1e25';
		}
		else
		{
			var persent_week_colr = '#3598DC';
		}
		
		var persent_month=$('#persent_month').val();
		if(persent_month==100 || persent_month=='100')
		{
			var persent_month_colr = '#ef1e25';
		}
		else
		{
			var persent_month_colr = '#6881A0';
		}
        $('.pie_progress').asPieProgress({
			
			 barcolor: persent_today_colr,
        });
		$('.pie_progress1').asPieProgress({
			 barcolor: persent_week_colr,
        });
		$('.pie_progress2').asPieProgress({
			 barcolor: persent_month_colr,
        });
    });  
</script>



<script>
function edit_check(id)
{
	if($('#edit_'+id).is(":checked"))
	{
		$('#edit_'+id).prop('checked',true);
		$('.edit_'+id).prop('checked',true);
	}
 }

function view_check(id)
{
	if($('#view_'+id).is(":checked"))
	{
         
	}
	else
	{
		$('#edit_'+id).prop('checked',false);
	}
 }
</script>


<script>
 function edit_user(id){
	 $("#heading_loader").fadeIn("slow");
	 $.ajax({
		type:"POST",
		url:"<?php echo site_url('channel/get_edit'); ?>",
		data:"priviledge_id="+id,
		success:function(msg){
			$('#edit_users').html(msg);
			$('#user_edit').modal({backdrop: 'static',keyboard: false});
			$("#heading_loader").fadeOut("slow");
		}
	});
	return false;
 }
 </script>

<script>
function edit_check(id)
{
  if($('#edit_'+id).is(":checked"))
  {
    $('#edit_'+id).prop('checked',true);
    $('.edit_'+id).prop('checked',true);
  }
 }

function view_check(id)
{
  if($('#view_'+id).is(":checked"))
  {
         
  }
  else
  {
    $('#edit_'+id).prop('checked',false);
  }
 }
</script>

<?php echo theme_js('jquery.slimscroll.min.js', true);?>
<?php echo theme_js('app.min.js', true);?>
<?php echo theme_js('auto.js', true);?>
<?php echo theme_js('bootstrap-hover-dropdown.min.js', true);?>
<?php echo theme_js('channel_helper.js?version='.rand(0,9999).'', true);?>
