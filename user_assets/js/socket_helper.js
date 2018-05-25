var hotel =  document.getElementById('soc_hotel').value;
var user =  document.getElementById('soc_user').value;
var ws, url = 'ws://192.168.1.23:5001';
//var ws, url = 'ws://192.168.1.23:5001';


window.onbeforeunload = function() {
	ws.send('quit');
};

window.onload = function() {
	try {
		ws = new WebSocket(url);
		var test = '{"path":"socket_test","hotel":"'+hotel+'","user":"'+user+'"}';
		ws.onopen = function(msg) 
		{
			ws.send(test);
		};
		ws.onmessage = function(msg) 
		{
			var response = JSON.parse(msg.data);
			$('#reservation_today_count').html(response['reservation_today_count']);
			$('#cancelation_today_count').html(response['cancelation_today_count']);
			$('#arrival_today_count').html(response['arrival_today_count']);
			$('#depature_today_count').html(response['depature_today_count']);
			$('#persent_today_div').attr('aria-valuenow',response['persent_today']);
			$('#progress_today').html(response['persent_today']+" %");
			$('#persent_week_div').attr('aria-valuenow',response['persent_today']);
			$('#progress_week').html(response['persent_week']+" %");
			$('#persent_month_div').attr('aria-valuenow',response['persent_today']);
			$('#progress_month').html(response['persent_month']+" %");
			if(response['persent_today']==100 || response['persent_today']=='100')
			{
				var persent_today_colr = '#ef1e25';
			}
			else
			{
				var persent_today_colr = '#26C281';
			}
			if(response['persent_week']==100 || response['persent_week']=='100')
			{
				var persent_week_colr = '#ef1e25';
			}
			else
			{
				var persent_week_colr = '#3598DC';
			}
			if(response['persent_month']==100 || response['persent_month']=='100')
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
		};
	}
	catch(exception) {
		write(exception);
	}
};

function write(text) {
	var date = new Date();
	var dateText = '['+date.getFullYear()+'-'+(date.getMonth()+1 > 9 ? date.getMonth()+1 : '0'+date.getMonth()+1)+'-'+(date.getDate() > 9 ? date.getDate() : '0'+date.getDate())+' '+(date.getHours() > 9 ? date.getHours() : '0'+date.getHours())+':'+(date.getMinutes() > 9 ? date.getMinutes() : '0'+date.getMinutes())+':'+(date.getSeconds() > 9 ? date.getSeconds() : '0'+date.getSeconds())+']';
	var terminal = document.getElementById('terminal');
	terminal.innerHTML = '<li>'+dateText+' '+text+'</li>'+terminal.innerHTML;
}