/**
 * Site : http:www.smarttutorials.net
 * @author muni
 */
	      
//adds extra table rows
var i=$('table tr').length;
$(".addmore").on('click',function(){
	
	$('#in_val').val(i);
	html = '<tr class="extra_tr">';
	html += '<td><input class="case" type="checkbox"/></td>';
	html += '<td><input type="text" data-type="productCode" name="eitemNo_'+i+'" id="itemNo_'+i+'" class="form-control " autocomplete="off" value="Extra"></td>';
	html += '<td><input type="text" data-type="productName" name="eitemName_'+i+'" id="itemName_'+i+'" class="form-control" autocomplete="off"></td>';
	html += '<td><input type="text" name="etotal_'+i+'" id="total_'+i+'" class="form-control changesNo totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"></td>';
	html += '</tr>';
	$('table').append(html);
	i++;
});

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal();
});

//autocomplete script


//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = 0;
	price =0;
	if( quantity!='' && price !='' ) $('#total_'+id[1]).val( (parseFloat(price)*parseFloat(quantity)).toFixed(2) );	
	calculateTotal();
});

$(document).on('change keyup blur','#tax',function(){
	calculateTotal();
});

//total price calculation 
function calculateTotal(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(2) );
	tax = $('#tax').val();
	if(tax != '' && typeof(tax) != "undefined" ){
		taxAmount = subTotal * ( parseFloat(tax) /100 );
		$('#taxAmount').val(taxAmount.toFixed(2));
		total = subTotal + taxAmount;
	}else{
		$('#taxAmount').val(0);
		total = subTotal;
	}
	$('#totalAftertax').val( total.toFixed(2) );
	calculateAmountDue();
}

$(document).on('change keyup blur','#amountPaid',function(){
	calculateAmountDue();
});

//due amount calculation
function calculateAmountDue(){
	amountPaid = $('#amountPaid').val();
	total = $('#totalAftertax').val();
	if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
		amountDue = parseFloat(total) - parseFloat( amountPaid );
		$('.amountDue').val( amountDue.toFixed(2) );
	}else{
		total = parseFloat(total).toFixed(2);
		$('.amountDue').val( total );
	}
}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

//datepicker
$(function () {
    $('#invoiceDate').datepicker({});
});