var base_url = document.getElementById('base_url').value;
var active   = document.getElementById('active').value;
user_id =  document.getElementById('user_id').value;

if(user_id!='')
{
	uid = user_id;
}
else
{
	uid='';
}



$().ready(function() { 

$("table#reservation_yes_tbl tr").each(function() {        
    var cell = $.trim($(this).find('td').text());
    if (cell.length == 0){
        console.log('empty');
        $(this).addClass('display_none');
    }                   
});



/*9751896864*/
 $('.inline_username').editable();

			if(active=='confirm')
			{
				$('#m_active').modal({backdrop: 'static',keyboard: false});
			}
			else if(active=='already')
			{
				$('#a_active').modal({backdrop: 'static',keyboard: false});
			}
			var form = $('#register_form');
			$.validator.addMethod('positiveNumber',
            function(value) {
                return Number(value) > 0;
            }, 'Enter a positive number.');
			
			jQuery.validator.addMethod("lettersonly", 
			function(value, element) {
       		 return this.optional(element) || /^[a-z,""]+$/i.test(value);
			}, "Letters only please");

jQuery.validator.addMethod("lettersonly2", 
			function(value, element) {
       		 return this.optional(element) || /^[a-z," "]+$/i.test(value);
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
			
			$('#contact_us').validate({
				rules:
				{
					name:
					{
						required:true,
						lettersonly:true,
					},
					email:
					{
						required:true,
						customemail:true
					},
					message_content:
					{
						required:true,
					}
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
			});
			
			$("#register_form").validate({
	
	// Rulse start here	
			rules: {
                    //account
                    fname: {
                        required: true,
						lettersonly: true,
                    },
					lname: {
                        required: true,
						lettersonly: true,
                    },
					address: {
                        required: true
                    },
                    town: {
                        required: true
                    },
                    country: {
                        required: true
                    },
					property_name: {
                        required: true,
						remote: {
									url:base_url+"channel/register_property_exists",
									type: "post",
									data:{
											  username: function()
											  {
												  return $("#property_name").val();
											  }
										 } 
							    },
						
                    },
					user_name: {
								//required: true,
								lettersonly: true,
								//minlength:5,
								remote: {
													  url:base_url+"channel/register_username_exists",
													  type: "post",
													  data: {
															  username: function()
															  {
																  return $("#username").val();
															  }
															}
													 },
							},
					email_address: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/register_email_exists",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#registeremail").val(); 
													}
												  }
										}
						},
					mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/register_phone_exists",
												type: "post",
												data: {
														email: function()
														{ 
															return $("#phone").val(); 
														}
													  }
										}
							},
					password : {required: true},
					cpassword : {required: true,equalTo:"#password"},
					web_site:{required: true,url: true},
					captcha:{required: true,equalTo:"#except_captcha"},
					accept:{required: true},
					currency:{required: true}
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
						$(element)
						.closest('.check-n').addClass('customErrorClass');
						$(element)
						.closest('.accept-n').addClass('customErrorClass');
						
						
                },
			unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); // set error class to the control group
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
						$(element)
						.closest('.check-n').removeClass('customErrorClass');
						$(element)
						.closest('.accept-n').removeClass('customErrorClass');
						
                },
			submitHandler: function(form)
			{
				var data=$("#register_form").serialize();
				//alert(data);
				$('#reg').css('cursor', 'pointer');
				$("#reg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							url: base_url+'channel/basics/UserRegister',
							data: data,
							success: function(result)
							{
								$('#register_form').trigger("reset");
								$('#success_msg').html(result);
								setTimeout(function()
								{
									 location.reload();
								},
								5000);
							}
						});
			}
				
		//errorClass: 'customErrorClass',
		//$('.nf-select').addClass('customErrorClass');

	});
	
         	$("#register_form2").validate({
	
	// Rulse start here	
			rules: {
                    //account
                    fname: {
                        required: true,
						lettersonly: true,
                    },
					lname: {
                        required: true,
						lettersonly: true,
                    },
					address: {
                        required: true
                    },
                    town: {
                        required: true
                    },
                    country: {
                        required: true
                    },
					property_name: {
                        required: true,
						remote: {
									url:base_url+"channel/register_property_exists/"+uid,
									type: "post",
									data:{
											  username: function()
											  {
												  return $("#property_name").val();
											  }
										 } 
							    },
						
                    },
					user_name: {
								//required: true,
								lettersonly: true,
								//minlength:5,
								remote: {
													  url:base_url+"channel/register_username_exists",
													  type: "post",
													  data: {
															  username: function()
															  {
																  return $("#username").val();
															  }
															}
													 },
							},
					email_address: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/register_email_exists/"+uid,
											type: "post",
											data: {
													email: function()
													{ 
														return $("#registeremail").val(); 
													}
												  }
										}
						},
					mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/register_phone_exists/"+uid,
												type: "post",
												data: {
														email: function()
														{ 
															return $("#phone").val(); 
														}
													  }
										}
							},
					password : {required: true},
					cpassword : {required: true,equalTo:"#password"},
					web_site:{required: true,url: true},
					captcha:{required: true,equalTo:"#except_captcha"},
					accept:{required: true},
					currency:{required: true},
					zip_code:{positiveNumber:true ,minlength:6,maxlength:6}
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
			/*submitHandler: function(form)
			{
				var data=$("#register_form").serialize();
				//alert(data);
				$('#reg').css('cursor', 'pointer');
				$("#reg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							url: base_url+'channel/basics/UserRegister',
							data: data,
							success: function(result)
							{
								$('#register_form').trigger("reset");
								$('#success_msg').html(result);
								setTimeout(function()
								{
									 location.reload();
								},
								5000);
							}
						});
			}*/
				
		//errorClass: 'customErrorClass',
		//$('.nf-select').addClass('customErrorClass');

	});
	
			$('#log_form').validate({
			rules :
			{
				login_email: 
				{
					required: true,
				},
				login_pwd :
				{
					required: true,
				}
		},
		/*messages:
		{
			email:
			{
				required : "Enter your email id",
				email	 : "Enter valid email id", 
				remote	 : "Enter email is does not exist"
			},
			password:"Enter your password",	
		},*/
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
		submitHandler: function(form)
		{
			var data=$("#log_form").serialize();
			//alert(data);
			$('#logg').css('cursor', 'pointer');
			$("#logg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
			$.ajax({
			type: "POST",
			url:  base_url+'channel/basics/Login',
			data: data,
			success: function(html)
			{
			  if(html==0)
			  {
				$('#log_form').trigger("reset");
				$("#logg").html('Sign In');
				$("#myModal").modal("hide");
				$('#myModal2').modal({backdrop: 'static',keyboard: false});	   
			  }
			  else if(html==1)
			  {
				 $("#myModal").modal("hide"); 
				 document.location=base_url+"channel/dashboard";
			  }
			  else if(html==2)
			  {
				  document.location=base_url+"channel/account_notify/deactive";
			  }
			  else if(html==3)
			  {
				  document.location=base_url+"channel/account_notify";
			  }	
		   }
	});
	}
	});
	
			$('#forgetpassword').validate({
			rules :
			{
				forget_email: 
				{
					required: true,
					email:true,
					 remote: {
								url: base_url+"channel/forget_email_exists",
								type: "post",
								data: {
										forget_email: function()
										{
											return $("#forget_email").val();
										}
									  }
							  },
					
				},
			},
			/*messages:
			{
				forget_email:
				{
					required : "Enter your email id",
					email	 : "Enter valid email id", 
					remote	 : "Enter email is does not exist"
				},
			},*/
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
			submitHandler: function(form)
			{
			var data=$("#forgetpassword").serialize();
			//alert(data);
			$('#forgg').css('cursor', 'pointer');
			$("#forgg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
			$.ajax({
			type: "POST",
			url: base_url+'channel/basics/ForgetPassword',
			data: data,
			success: function(html)
			{
				$("#forgg").html('Send');
				$('#forgetpassword').trigger("reset");
			  if(html==1)
			  {
				$('#add_ss1').css('display','block');
				$('#add_ss').css('display','none');
				$('#add_ss1').html('<div class="alert alert-success" id="login_error">Your password has been send to email. </div>');
			  }
			  else if(html=2)
			  {
			  	$('#add_ss1').css('display','block');
				$('#add_ss').css('display','none');
				  $('#add_ss1').html('<div class="alert alert-danger" id="login_error">Can not forget passeord. </div>');
			  }
		   }
	});
	}
	});
	
        	$('#card1').validate({
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
	
			$('#card2').validate({
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
			bill_zip:
			{
				required : true,
				number: true,
				positiveNumber:true ,
				minlength: 6,
				maxlength: 6
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
	
			$('#room_form').validate({
			rules :
			{
				property_name :
				{
					required: true,
					remote: {
							  url:base_url+"channel/add_room_exists",
							  type: "post",
							  data: {
									  username: function()
									  {
										  return $("#property_name").val();
									  }
									}
							 },
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
				pricing_type:{
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
					number : true,
					positiveNumber:true,
				},
				children:
				{
					required: true,
					number : true,
					positiveNumber:true,
				},
				existing_room_count:
				{
					required: true,
					number : true,
					positiveNumber:true,
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
				room_image:
				{
					required : true,
				},
				description:
				{
					required : true,
					maxlength:140
				},
				email: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/room_email_exists/",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#email").val(); 
													}
												  }
										}
						},
				mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/room_phone_exists/",
												type: "post",
												data: {
														mobile: function()
														{ 
															return $("#phone").val(); 
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
		
	});
	
			$("#user_form").validate({
	
	// Rulse start here	
			rules: {
                    //account
					user_name: {
								required: true,
								lettersonly: true,
								//minlength:5,
								remote: {
													  url:base_url+"channel/register_username_exists",
													  type: "post",
													  data: {
															  username: function()
															  {
																  return $("#username").val();
															  }
															}
													 },
							},
					password : {required: true},
					"access[]":{required: true},
					cpassword : {required: true,equalTo:"#password"},
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
	
			$('#upload_photos').validate({ 
			rules:
			{
				"hotel_image[]":{required:true}
			},
			errorPlacement: function(){
            return false;  // suppresses error message text
    		},
			submitHandler: function(form)
			{
				sendNow();
			}
			});
			
			$('#room_basic_form').validate({
			rules :
			{
				property_name :
				{
					required: true,
					remote: {
							  url:base_url+"channel/add_room_exists",
							  type: "post",
							  data: {
									  username: function()
									  {
										  return $("#property_name").val();
									  }
									}
							 },
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
				pricing_type:{
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
					number : true,
					positiveNumber:true,
				},
				children:
				{
					required: true,
					number : true,
					positiveNumber:true,
				},
				existing_room_count:
				{
					required: true,
					number : true,
					positiveNumber:true,
				},
				price:
				{
					required: true,
					positiveNumber:true ,
				},
				room_image:
				{
					required : true,
				},
				description:
				{
					required : true,
					maxlength:140
				},
				email: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/room_email_exists/",
											type: "post",
											data: {
													email: function()
													{ 
														return $("#email").val(); 
													}
												  }
										}
						},
				mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/room_phone_exists/",
												type: "post",
												data: {
														mobile: function()
														{ 
															return $("#phone").val(); 
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
		
	});
			
			function sendNow(){

   			var formData = new FormData($('form#upload_photos')[0]);

   			$.ajax({
					url: base_url+'channel/manage_photos/new',
					type: 'POST',
					data: formData,
					async: false,
					beforeSend:function ()
					{
						$("#heading_loader").show();
					},
					success: function (data) {
					$("#heading_loader").hide();
						$('#upload_photos').trigger("reset");
						  $('#img_rplae').html(data);
							/*$("#successdiv").show();
							setTimeout(function()
							{
								 document.getElementById("successdiv").style.display = "none";
							},
							5000);*/
					},
					cache: false,
					contentType: false,
					processData: false
    			});
    		return false;
			}
			
			
			$("#billing_form").validate({
	
	// Rulse start here	
			rules: {
                    //account
                    fname: {
                        required: true,
						lettersonly: true,
                    },
					lname: {
                        required: true,
						lettersonly: true,
                    },
					address: {
                        required: true
                    },
                    town: {
                        required: true
                    },
                    country: {
                        required: true
                    },
					property_name: {
                        required: true,
						remote: {
									url:base_url+"channel/register_property_exists/"+uid,
									type: "post",
									data:{
											  username: function()
											  {
												  return $("#property_name_b").val();
											  }
										 } 
							    },
						
                    },
					user_name: {
								//required: true,
								lettersonly: true,
								//minlength:5,
								remote: {
													  url:base_url+"channel/register_username_exists",
													  type: "post",
													  data: {
															  username: function()
															  {
																  return $("#username").val();
															  }
															}
													 },
							},
					email_address: {
							required: true,
							customemail:true,
							remote: {
											url: base_url+"channel/register_email_exists/"+uid,
											type: "post",
											data: {
													email: function()
													{ 
														return $("#email_address_b").val(); 
													}
												  }
										}
						},
					mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
								remote: {
												url:base_url+"channel/register_phone_exists/"+uid,
												type: "post",
												data: {
														email: function()
														{ 
															return $("#mobile_b").val(); 
														}
													  }
										}
							},
					password : {required: true},
					cpassword : {required: true,equalTo:"#password"},
					web_site:{required: true,url: true},
					captcha:{required: true,equalTo:"#except_captcha"},
					accept:{required: true},
					currency:{required: true},
					zip_code:{positiveNumber:true ,minlength:6,maxlength:6},
					
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
			/*submitHandler: function(form)
			{
				var data=$("#register_form").serialize();
				//alert(data);
				$('#reg').css('cursor', 'pointer');
				$("#reg").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							url: base_url+'channel/basics/UserRegister',
							data: data,
							success: function(result)
							{
								$('#register_form').trigger("reset");
								$('#success_msg').html(result);
								setTimeout(function()
								{
									 location.reload();
								},
								5000);
							}
						});
			}*/
				
		//errorClass: 'customErrorClass',
		//$('.nf-select').addClass('customErrorClass');

	});
	
			
			
			$('#customize_calender').validate({
			rules :
			{
				start_date	:
				{
					required: true,
					
				},
				end_date:{
					
					required: true,
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
			submitHandler: function(form)
			{
				var data=$("#customize_calender").serialize();
				//alert(data);
				$('#customize').css('cursor', 'pointer');
				$("#customize").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							url: base_url+'inventory/customize_calender',
							data: data,
							success: function(result)
							{
								//$('#customize_calender').trigger("reset");
								$(document).find('#myModal-p').modal("hide");
								$('#customize_date').html(result);
								/*setTimeout(function()
								{
									 location.reload();
								},
								5000);*/
								$(document).trigger('thiru');
								$("#customize").html('Continue');
								
							}
						});
			}
		
	});
	
			$('#customize_calenders').validate({
			rules :
			{
				start_date	:
				{
					required: true,
					
				},
				end_date:{
					
					required: true,
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
			submitHandler: function(form)
			{
				var data=$("#customize_calenders").serialize();
				//alert(data);
				$('#customizes').css('cursor', 'pointer');
				$("#customizes").html('<span class="fa fa-spinner fa-spin"></span> Please wait...');
				$.ajax({
							type: "POST",
							url: base_url+'inventory/customize_calender_no',
							data: data,
							success: function(result)
							{
								//$('#customize_calender').trigger("reset");
								$(document).find('#myModal-ps').modal("hide");
								$('#customize_dates').html(result);
								/*setTimeout(function()
								{
									 location.reload();
								},
								5000);*/
								$(document).trigger('thiru');
								$("#customizes").html('Continue');
								
							}
						});
			}
		
	});
			
			$('#tableData').paging({limit:7});  
			$('#tableData1').paging({limit:7});  
			$('#tableData2').paging({limit:7}); 
			$('#tableData3').paging({limit:7}); 
			
			/// Circle
			
			$('.myStathalf2').circliful();
			
			$('#myStathalf3').circliful();
			
			$('#myStathalf4').circliful();
			
			$('#myStathalf5').circliful();
			
			$('#myStathalf6').circliful();
			
			$('#myStathalf7').circliful();
			
			
			$(function(){
            Tags.bootstrapVersion = "2";
        
            $("#suggestOnClick").tags({
              restrictTo: ["alpha", "bravo", "charlie", "delta", "echo", "foxtrot", "golf", "hotel", "india"],
              suggestions: ["alpha", "bravo", "charlie", "delta", "echo", "foxtrot", "golf", "hotel", "india"],
              promptText: "Click here to add new tags",
              suggestOnClick: true
            });
			
			 $("#suggestOnClick2").tags({
              restrictTo: ["alpha", "bravo", "charlie", "delta", "echo", "foxtrot", "golf", "hotel", "india"],
              suggestions: ["alpha", "bravo", "charlie", "delta", "echo", "foxtrot", "golf", "hotel", "india"],
              promptText: "Click here to add new tags",
              suggestOnClick: true
            });
          });
		  
		  $("#open_btn").click(function() {
            $.FileDialog({multiple: true}).on('files.bs.filedialog', function(ev) {
                var files = ev.files;
                var text = "";
                files.forEach(function(f) {
                    text += f.name + "<br/>";
                });
                $("#output").html(text);
            }).on('cancel.bs.filedialog', function(ev) {
                $("#output").html("Cancelled!");
            });
        });
			
			$('.fancybox').fancybox(); 
			
			$("ul.holder").jPages({
			containerID : "hot_pho",
			perPage: 1
		});
			
			$('#channel_all').click(function(event) {  //on click
        
		 if(this.checked) { // check select status
            $('.channel_single').each(function() { //loop through each checkbox
                this.checked = true; 
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.channel_single').each(function() { //loop through each checkbox
                this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });
	
	 		$('.availability').hide();
			$('.price').hide();
			$('.minimum_stay').hide();
			$('.restrictions').hide();
			$('.cta').hide();
			$('.ctd').hide();
			$('.stop_sell').hide();
			$('.guest_based').hide();
			
			
			$('#update').attr('disabled','disabled');
	
			$('#availability').click(function(event) {  //on click
			
	        if(this.checked) { // check select status
            $('.availability').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.availability').show();
			    $('#update').removeAttr('disabled'); 
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.availability').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.availability').hide();
				 
				check_a1 = $("#price").is(":checked");
		 		check_a2 = $("#minimum_stay").is(":checked");
				check_a3 = $("#cta").is(":checked");
				check_a4 = $("#ctd").is(":checked");
				check_a5 = $("#stop_sell").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				}
				
				               
            });        
        }
    });
	
			$('#price').click(function(event) {  //on click
        
		 if(this.checked) { // check select status
            $('.price').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.price').show();
				$('#update').removeAttr('disabled'); 
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.price').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.price').hide();
				check_a1 = $("#availability").is(":checked");
		 		check_a2 = $("#minimum_stay").is(":checked");
				check_a3 = $("#cta").is(":checked");
				check_a4 = $("#ctd").is(":checked");
				check_a5 = $("#stop_sell").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				}                    
            });        
        }
    });
	
			$('#minimum_stay').click(function(event) {  //on click
        
		 if(this.checked) { // check select status
            $('.minimum_stay').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.minimum_stay').show();
			   $('#update').removeAttr('disabled'); 
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.minimum_stay').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.minimum_stay').hide(); 
				check_a1 = $("#availability").is(":checked");
		 		check_a2 = $("#price").is(":checked");
				check_a3 = $("#cta").is(":checked");
				check_a4 = $("#ctd").is(":checked");
				check_a5 = $("#stop_sell").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				}                   
            });        
        }
    });
	
			$('#cta').click(function(event) {  //on click
        
		 if(this.checked) { // check select status
            $('.cta').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.cta').show();
			   $('.restrictions').show();
			    $('#update').removeAttr('disabled'); 
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.cta').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.cta').hide(); 
				check = $("#ctd").is(":checked");
		 		check1 = $("#stop_sell").is(":checked");
				if(check || check1) 
				{
						$('.restrictions').show();
				}
				else
				{
						$('.restrictions').hide();
				}
				
				check_a1 = $("#availability").is(":checked");
		 		check_a2 = $("#price").is(":checked");
				check_a3 = $("#minimum_stay").is(":checked");
				check_a4 = $("#ctd").is(":checked");
				check_a5 = $("#stop_sell").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				}  
				                    
            });        
        }
    });
	
			$('#ctd').click(function(event) {  //on click
        
		 if(this.checked) { // check select status
            $('.ctd').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.ctd').show();
			   $('.restrictions').show();
				$('#update').removeAttr('disabled'); 
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.ctd').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.ctd').hide(); 
				check = $("#cta").is(":checked");
		 		check1 = $("#stop_sell").is(":checked");
				if(check || check1) 
				{
						$('.restrictions').show();
				}
				else
				{
						$('.restrictions').hide();
				}
				
				check_a1 = $("#availability").is(":checked");
		 		check_a2 = $("#price").is(":checked");
				check_a3 = $("#minimum_stay").is(":checked");
				check_a4 = $("#cta").is(":checked");
				check_a5 = $("#stop_sell").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				} 
				                    
            });        
        }
    });
	
			$('#stop_sell').click(function(event) {  //on click
         
		 if(this.checked) { // check select status
            $('.stop_sell').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.stop_sell').show();
			   $('.restrictions').show();
				$('#update').removeAttr('disabled'); 
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.stop_sell').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				$('.stop_sell').hide(); 
				check = $("#cta").is(":checked");
		 		check1 = $("#ctd").is(":checked");
				if(check || check1) 
				{
						$('.restrictions').show();
				}
				else
				{
						$('.restrictions').hide();
				}
				
				check_a1 = $("#availability").is(":checked");
		 		check_a2 = $("#price").is(":checked");
				check_a3 = $("#minimum_stay").is(":checked");
				check_a4 = $("#cta").is(":checked");
				check_a5 = $("#ctd").is(":checked");
				if(check_a1 || check_a2 || check_a3 || check_a4 || check_a5) 
				{
						$('#update').removeAttr('disabled');      
				}
				else
				{
						$('#update').attr('disabled','disabled');    
				}
				                    
            });        
        }
    });
	
			$('#add_non_r').click(function(event) {  //on click
			
	        if(this.checked) { // check select status
            $('.non_refund').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.non_refund').show();
			if($('#pricing_type').val()=='1')
			{
				$('.room_based').show();
			}
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.non_refund').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.non_refund').hide();
			
				if($('#pricing_type').val()=='1')
				{
					$('.room_based').hide();
				}
		 });        
        }
    });
	
			$('#add_non_rs').click(function(event) {  //on click
			
	        if(this.checked) { // check select status
            $('.non_refunds').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.non_refunds').show();
			if($('#pricing_types').val()=='1')
			{
				$('.room_baseds').show();
			}
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.non_refunds').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.non_refunds').hide();
			
				if($('#pricing_types').val()=='1')
				{
					$('.room_baseds').hide();
				}
		 });        
        }
    });
	
			$(document).on('click','#add_non_rsss',function(event)
			{
				if(this.checked) { // check select status
            $('.non_refunds').each(function() { //loop through each checkbox
               // this.checked = true; 
			   $('.non_refunds').show();
			if($('#pricing_typess').val()=='1')
			{
				$('.room_baseds').show();
			}
				
				//select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.non_refunds').each(function() { //loop through each checkbox
               // this.checked = false; 
				 //deselect all checkboxes with class "checkbox1"  
				 $('.non_refunds').hide();
			
				if($('#pricing_typess').val()=='1')
				{
					$('.room_baseds').hide();
				}
		 });        
        }
			});
			
			$(document).on('click','#show_all',function(event)
			{
				if(this.checked) { // check select status
	            	$('.show_all').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.show_all').show();
					//select all checkboxes with class "checkbox1"              
            		});
        	}else{
            		$('.show_all').each(function() { //loop through each checkbox
               		// this.checked = false; 
				 	//deselect all checkboxes with class "checkbox1"  
				 	$('.show_all').hide();
		 			});        
       		}
			});
			
			$(document).on('click','#cal_ms',function(event)
			{
				if(this.checked) { // check select status
	            	$('.cal_ms').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.cal_ms').show();
					//select all checkboxes with class "checkbox1"              
            		});
        	}else{
            		$('.cal_ms').each(function() { //loop through each checkbox
               		// this.checked = false; 
				 	//deselect all checkboxes with class "checkbox1"  
				 	$('.cal_ms').hide();
		 			});        
       		}
			});
			
			$(document).on('click','#cal_cta',function(event)
			{
				if(this.checked) { // check select status
	            	$('.cal_cta').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.cal_cta').show();
					//select all checkboxes with class "checkbox1"              
            		});
        	}else{
            		$('.cal_cta').each(function() { //loop through each checkbox
               		// this.checked = false; 
				 	//deselect all checkboxes with class "checkbox1"  
				 	$('.cal_cta').hide();
		 			});        
       		}
			});
			
			$(document).on('click','#cal_ctd',function(event)
			{
				if(this.checked) { // check select status
	            	$('.cal_ctd').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.cal_ctd').show();
					//select all checkboxes with class "checkbox1"              
            		});
        	}else{
            		$('.cal_ctd').each(function() { //loop through each checkbox
               		// this.checked = false; 
				 	//deselect all checkboxes with class "checkbox1"  
				 	$('.cal_ctd').hide();
		 			});        
       		}
			});
			
			$(document).on('click','#cal_ss',function(event)
			{
				if(this.checked) { // check select status
	            	$('.cal_ss').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.cal_ss').show();
					//select all checkboxes with class "checkbox1"              
            		});
        	}else{
            		$('.cal_ss').each(function() { //loop through each checkbox
               		// this.checked = false; 
				 	//deselect all checkboxes with class "checkbox1"  
				 	$('.cal_ss').hide();
		 			});        
       		}
			});
				
			$('.non_refund').hide();
			$('.room_based').hide();
			
			$(document).on('click','.channel_all',function(event)
			{
				
				if(this.checked) { 
				// check select status
	            	$('.channel_single').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.channel_single').prop('checked',true);
					//select all checkboxes with class "checkbox1"              
            		});
				}else{
				
						$('.channel_single').each(function() { //loop through each checkbox
						// this.checked = false; 
						//deselect all checkboxes with class "checkbox1"  
						$('.channel_single').prop('checked',false)
						});        
				}
				
			});
			
			$(document).on('click','.channel_all1',function(event)
			{
				
				if(this.checked) { 
				// check select status
	            	$('.channel_single1').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.channel_single1').prop('checked',true);
					//select all checkboxes with class "checkbox1"              
            		});
				}else{
					
						$('.channel_single1').each(function() { //loop through each checkbox
						// this.checked = false; 
						//deselect all checkboxes with class "checkbox1"  
						$('.channel_single1').prop('checked',false)
						});        
				}
				
			});
			
			$(document).on('click','#bulk_days',function(event)
			{
				
				if(this.checked) { 
				// check select status
	            	$('.bulk_days').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.bulk_days').prop('checked',true);
					//select all checkboxes with class "checkbox1"              
            		});
				}else{
					
						$('.bulk_days').each(function() { //loop through each checkbox
						// this.checked = false; 
						//deselect all checkboxes with class "checkbox1"  
						$('.bulk_days').prop('checked',false)
						});        
				}
				
			});
			
			$(document).on('click','.bulk_days',function(event)
			{
				$('#bulk_days').prop('checked',false);
				
			});
			
			$(document).on('click','.all_check_toggle',function(event)
			{
				
				if(this.checked) { 
				// check select status
	            	$('.channel_single2').each(function(){ //loop through each checkbox
               		// this.checked = true; 
			   		$('.channel_single2').prop('checked',true);
					//select all checkboxes with class "checkbox1"              
            		});
				}else{
						$('.channel_single2').each(function() { //loop through each checkbox
						// this.checked = false; 
						//deselect all checkboxes with class "checkbox1"  
						$('.channel_single2').prop('checked',false)
						});        	
				}
				
			});
			
			$(".save_button_master_calendar").on("click", function() {
	
					var vales="";
					$('.channel_single2').each(function(){ //loop through each checkbox
					if(this.checked)
					{
					     vales +=$(this).val()+",";
					}
            		});
					s = vales.substring(0, vales.length - 1);
					$('#channe_id_update').val(s);
					$(".master_calendar_form").submit();
					});
	
	
});

$(function() {
 
                if (localStorage.chkbx && localStorage.chkbx != '') {
                    $('#remember_me').attr('checked', 'checked');
                    $('#login_email').val(localStorage.usrname);
                    $('#login_pwd').val(localStorage.pass);
                } else {
                    $('#remember_me').removeAttr('checked');
                    $('#login_email').val('');
                    $('#login_pwd').val('');
                }
 
                $('#remember_me').click(function() {
 
                    if ($('#remember_me').is(':checked')) {
                        // save username and password
                        localStorage.usrname = $('#login_email').val();
                        localStorage.pass = $('#login_pwd').val();
                        localStorage.chkbx = $('#remember_me').val();
                    } else {
                        localStorage.usrname = '';
                        localStorage.pass = '';
                        localStorage.chkbx = '';
                    }
                });
            });
			
$('.tlClogo').bind('contextmenu', function(e) {
    return false;
});
			
$(document).on("click",".close",function() {
	
	$('#register_form').trigger("reset");
	$('#profile').trigger("reset");
	$('#card1').trigger("reset");
	$('#room_form').trigger("reset");
	$('#change_phone').trigger("reset");
	$('#Trash_Message').trigger("reset");
 	$("form input").removeClass("customErrorClass");
	$("form textarea").removeClass("customErrorClass"); 
	$("form select").removeClass("customErrorClass"); 
	$("form file").removeClass("customErrorClass"); 
	$("#check-n").removeClass("customErrorClass"); 
  $('.error').html('');
 // $("form label").removeClass("error");
  $("form class").removeClass("customErrorClass");
  $("form")[0].reset();
});

function hide_login()
{
	//$(".clslogin_Box").modal("hide");
    $('#myModal2').find('#add_ss1').css('display','none');
	$('#myModal2').find('#add_ss').css('display','block');
	$(document).find('#myModal').modal("hide");
}

function refresh_forget()
{
	$('#myModal2').find('#add_ss1').css('display','none');
	$('#myModal2').find('#add_ss').css('display','block');
}

function hide_reg()
{
	//$(".clslogin_Box_p").modal("hide");
	$(document).find('#sigup ').modal("hide");
}

function hide_forget()
{
	//$(".clslogin_Box_c").modal("hide");
	$(document).find('#myModal2').modal("hide");
}

$(document).on('click','.fa-trash-o',function(){
	if(confirm('Are u sure want do delete the record?'))
	{
		//alert($(this).attr('custom'));
		//$('#tr_'+$(this).attr('custom')).empty();
		return true;
	}
	else
	{
		return false;
	}
});

$(document).on('change','#add_country',function()
{
	country_id = ($(this).val());
	$.ajax({
		type:'post',
		url:base_url+'locations/get_state',
		data:'country_id='+country_id,
		success:function(result)
		{
			$('#add_state_div').html(result);
		}
	});
});

$(document).on('change','#edit_country',function()
{
	country_id = ($(this).val());
	$.ajax({
		type:'post',
		url:base_url+'locations/edit_state',
		data:'country_id='+country_id,
		success:function(result)
		{
			$('#edit_state').html(result);
		}
	});
});




$(document).on('change','#add_state',function()
{
	state_id = ($(this).val());
	$.ajax({
		type:'post',
		url:base_url+'locations/get_city',
		data:'state_id='+state_id,
		success:function(result)
		{
			$('#add_city_div').html(result);
		}
	});
});

$(document).on('change','#edit_state',function()
{
	state_id = ($(this).val());
	$.ajax({
		type:'post',
		url:base_url+'locations/edit_city',
		data:'state_id='+state_id,
		success:function(result)
		{
			$('#edit_city').html(result);
		}
	});
});

$(document).on('click','.room_photo',function()
{
	
		
	//alert($(this).attr('data-id'));
	$('#hotel_id').val($(this).attr('data-id'));
	$.ajax({
	type:'post',
	url :base_url+'channel/manage_photos/get',
	data:'hotel_id='+$(this).attr('data-id'),
	success:function(result)
	{
		$('#img_rplae').html(result);
	}
	});
	$('#myModal').modal({backdrop: 'static',keyboard: false});

});

$(document).on("change",".immm",function(){
	
	/*var input = document.getElementById('immm');
   if(input.files.length>4){
       $('.validation').css('display','block');
   }else{
       $('.validation').css('display','none');
   }*/
   
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
       
        case 'jpeg':
		 case 'jpg':
		 case 'png':
        
       
            $('#uploadButton').attr('disabled', false);
            break;
        default:
		$('#uploadButton').attr('disabled', true);
            alert('This is not an allowed file type.');
            this.value = '';
    }
})

$(document).on('click','.delete_photo',function(){
	
	if(confirm('Are u sure want do delete the photo?'))
	{
		//alert($(this).attr('custom'));
		$('#photo_id').val($(this).attr('data-id'));
		$.ajax({
				type:'post',
				url :base_url+'channel/manage_photos/remove',
				data:'photo_id='+$(this).attr('data-id')+'&image='+$(this).attr('custom'),
				success:function(result)
				{
					$('#img_rplae').html(result);
				}
			});
		
	}
	else
	{
		return false;
	}
});

$(document).on('change','#select_room',function(){
$.ajax({
	type:'post',
	url:base_url+'inventory/get_channel_room/'+$('#select_room').val(),
	success:function(result)
	{
		$('#cha_replace').html(result);
	}
});
});

$(window).load(function() {
	$("#heading_loader").fadeOut("slow");
});

$(window).load(function(){
				
				$.mCustomScrollbar.defaults.theme="inset"; //set "inset" as the default theme
				$.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default
				
				$("tab-n2").mCustomScrollbar({
					axis:"yx",
					scrollbarPosition:"outside"
				});
				
				$(".outer,.nested").mCustomScrollbar();
				
				$(".inner.horizontal-images").mCustomScrollbar({
					axis:"x",
					advanced:{autoExpandHorizontalScroll:true}
				});
				
			});

function showHide(id){ 

	 	//make sure there is an id attached to the div element

		if (document.getElementById){ 

			//set the obj variable to the element's id

			obj = document.getElementById(id); 

			//if the element is currently hidden, change the css style display to '' so it will now be visible

			if (obj.style.display == "none"){ 

				obj.style.display = "";

				$(".link").html("<a href=javascript:showHide(\"hideShowDiv\");><i class='fa fa-caret-right'></i> Less</a>");
				
		

				

			} else { 

				//if the element is currently visible, set the css style display to none to hide it again

				obj.style.display = "none"; 

				$(".link").html("<a href=javascript:showHide(\"hideShowDiv\");> <i class='fa fa-caret-right'></i> More</a>");

				

			} 

		} 

      }
	  
function showHide1(id){ 

	 	//make sure there is an id attached to the div element

		if (document.getElementById){ 

			//set the obj variable to the element's id

			obj = document.getElementById(id); 

			//if the element is currently hidden, change the css style display to '' so it will now be visible

			if (obj.style.display == "none"){ 

				obj.style.display = "";

				$(".link2").html("<a class='btn btn-default' href=javascript:showHide1(\"hideShowDiv2\");>All channels  <span class='caret'></span></a>");
				
		

				

			} else { 

				//if the element is currently visible, set the css style display to none to hide it again

				obj.style.display = "none"; 

				$(".link2").html("<a class='btn btn-default' href=javascript:showHide1(\"hideShowDiv2\");>All channels  <span class='caret'></span></a>");

				

			} 

		} 

      }
	  
function showHide2(id){ 

	 	//make sure there is an id attached to the div element

		if (document.getElementById){ 

			//set the obj variable to the element's id

			obj = document.getElementById(id); 

			//if the element is currently hidden, change the css style display to '' so it will now be visible

			if (obj.style.display == "none"){ 

				obj.style.display = "";

				$(".link3").html("<a class='btn btn-default' href=javascript:showHide2(\"hideShowDiv3\");>All <span class='caret'></span></a>");
				
		

				

			} else { 

				//if the element is currently visible, set the css style display to none to hide it again

				obj.style.display = "none"; 

				$(".link3").html("<a class='btn btn-default' href=javascript:showHide2(\"hideShowDiv3\");>All <span class='caret'></span></a>");

				

			} 

		} 

      }
	  

			
$(function(){
	
	window.prettyPrint && prettyPrint();
		
		/*  var nowDate = new Date();
			var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
			$('#dp1-p-c').datepicker({
				autoclose: true,
				format: 'mm-dd-yyyy',
				//startDate: today,
				minDate: new Date,
				todayHighlight:true,

			});
			var today = new Date();
			$('#dp1-p-c').datepicker({
				autoclose: true,
				format: 'dd-mm-yyyy',
				startDate: today,
				todayHighlight:true,
			});*/
			
			/*$('#dp1-p2').datepicker({
				autoclose: true,
				format: 'mm-dd-yyyy'
			});
			
			
			$('#dpYears').datepicker();
			$('#dpMonths').datepicker();
			
			
			var startDate = new Date(2012,1,20);
			var endDate = new Date(2012,1,25);*/
		
			
/*$('#datepicker_start').datepicker({
				autoclose: true,
				format: 'mm-dd-yyyy'
			});*/
			
			
			
		/*var nowTemp = new Date();
       	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var now1 = '';
        var checkins = $('#datepicker_start').datepicker({
          onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          if (ev.date.valueOf() > checkouts.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 30);
            checkouts.setValue(newDate);
			
		}
         checkins.hide();
          $('#datepicker_end')[0].focus();
		  return now1 = newDate;
		     }).data('datepicker');
		
		var checkouts = $('#datepicker_end').datepicker({
          onRender: function(date) {	
            return date.valueOf() > now1.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkouts.hide();
        }).data('datepicker');
		
		
			*/
			
			

   		   $('#dp1-p1').datepicker({
				autoclose: true,
               format: 'dd/mm/yyyy'
           });

           $('#dp1-p2').datepicker({

               autoclose: true,
               format: 'dd/mm/yyyy'
           });

        // disabling dates
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('#dp1').datepicker({
          onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 1);
            checkout.setValue(newDate);
          }
          checkin.hide();
          $('#dp1-p')[0].focus();
        }).data('datepicker');
        var checkout = $('#dp1-p').datepicker({
          onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkout.hide();
        }).data('datepicker');
		});
		
		
		
		function change_values(id)
		{
			$('#add_existing_room_count').val(id);
			
		}
		
		function set_prices(price)
		{
			$('.tk').html(numberWithCommas(parseFloat(price).toFixed(2)));
			$('.tkk').val(numberWithCommas(parseFloat(price).toFixed(2)));
			$('#base_price').val(numberWithCommas(parseFloat(price).toFixed(2)));
		}
		
		function numberWithCommas(x) {

		var	 xx = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	   	  //var nethaji =  xx.toFixed(2);
		 return xx;
		}
		/*var counter = 1;
		$("#add_existing_room_count").on('change','input[name^="first_name"]',function(event){
		  event.preventDefault();
		  counter++;
		  var newRow = $('<tr><td><input type="text" name="first_name' +
			counter + '"/></td><td><input type="text" name="last_name' +
			counter + '"/></td><td><a class="deleteRow"> x </a></td></tr>');
		  $('table.authors-list').append(newRow);
		});
		
		 $("table.authors-list").on('click','.deleteRow',function(event){
		
		   $(this).closest('tr').remove();
		 });*/
		 
var itemNum = 1;
$(document).on("change",'#add_member_count', function() {
    var count = this.value; 
	var add_non_r = $("#add_non_r").is(":checked");
	var base_price = document.getElementById('base_price').value;
	if(add_non_r)
	{
		non_refun = '1';
	}
	else
	{
		non_refun = '0';
	}
	$('#add_existing_room_count').val(count);
	if($('#pricing_type').val()=='2')
	{
		$.ajax({
		type:'POST',
		url:base_url+'channel/change_functions/'+count,
		data:"non_refun="+non_refun+'&base_price='+base_price,
		success:function(result)
		{
			$('.data').html(result);
		}
		
	});
	}
})

/*$(document).on("change",'#add_member_counts', function() {
    var count = this.value; 
	$('#existing_room_counts').val(count);
})*/

$(document).on("change",'#add_member_counts', function() {
    var count = this.value; 
	var existing_room_counts = $('#existing_room_counts').val();
	
	if(existing_room_counts <= count )
	{
		$('#existing_room_counts').val(count);
	}
})

$(document).on("blur",'#add_member_counts', function() {
    var count = this.value; 
	var existing_room_counts = $('#existing_room_counts').val();
	
	if(existing_room_counts <= count )
	{
		$('#existing_room_counts').val(count);
	}
})

$(document).on("change",'#existing_room_counts', function() {
    var count = this.value; 
	var existing_room_counts = $('#add_member_counts').val();
	
	if(existing_room_counts <= count )
	{
		$('#existing_room_counts').val(count);
	}
})

$(document).on("blur",'#existing_room_counts', function() {
    var count = this.value; 
	var existing_room_counts = $('#add_member_counts').val();
	
	if(existing_room_counts <= count )
	{
		$('#existing_room_counts').val(count);
	}
})



$(document).on("change",'#pricing_type',function() { 

//alert($('#pricing_type').val());

//

if($('#pricing_type').val()=='2')
{
	$('.guest_based').show();

	$('.room_based').hide();
}
else if($('#pricing_type').val()=='1')
{
	$('#add_non_r').removeAttr('checked',false)
	$('.guest_based').hide();

	$('.room_based').hide();
}
});

$(document).on("change",'#pricing_types',function() { 

//alert($('#pricing_type').val());

//$('#add_non_rs').removeAttr('checked',false)

if($('#pricing_types').val()=='2')
{
	
	$('.guest_baseds').show();

	$('.room_baseds').hide();
}
else if($('#pricing_types').val()=='1')
{
	$('.guest_baseds').hide();

	$('.room_baseds').show();
}
});

$(document).on("change",'#pricing_typess',function() { 

//alert($('#pricing_type').val());

//$('#add_non_rs').removeAttr('checked',false)

if($('#pricing_typess').val()=='2')
{
	
	$('.guest_baseds').show();

	$('.room_baseds').hide();
}
else if($('#pricing_typess').val()=='1')
{
	$('.guest_baseds').hide();

	$('.room_baseds').show();
}
});

$(document).on('change','.cal_amt',function(){
	
	custom = $(this).attr('custom');
	//alert(custom);
	
	add_price = parseInt(document.getElementById('add_price').value);
	
	var n_r_method = $(this).attr('method');
	//alert(n_r_method);
	if(n_r_method=='refun')
	{
		//alert(add_price);
		method = document.getElementById('method_'+$(this).attr('custom')).value;
		type   = document.getElementById('type_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('amt_'+$(this).attr('custom')).value);
		if(document.getElementById('amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#total_'+custom).html(total_amount);
		$('#h_total_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#total_'+custom).html(total_amount);
			$('#h_total_'+custom).val(total_amount);
		}
		
	}
	else
	{
		//alert(add_price);
		method = document.getElementById('n_method_'+$(this).attr('custom')).value;
		type   = document.getElementById('n_type_'+$(this).attr('custom')).value;
		amount = parseInt(document.getElementById('n_amt_'+$(this).attr('custom')).value);
		if(document.getElementById('n_amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#n_total_'+custom).html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#n_h_total_'+custom).val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#n_total_'+custom).html(total_amount);
			$('#n_h_total_'+custom).val(total_amount);
		}
	}
	///alert(total_amount);
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});


$(document).on('change','.r_cal_amt',function(){
	
	custom = $(this).attr('custom');
	//alert(custom);
	
	add_price = parseInt(document.getElementById('add_price').value);
	
	var n_r_method = $(this).attr('method');
	//alert(n_r_method);
	if(n_r_method=='refun')
	{
		//alert(add_price);
		method = document.getElementById('method_'+$(this).attr('custom')).value;
		type   = document.getElementById('type_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('amt_'+$(this).attr('custom')).value);
		if(document.getElementById('amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#total_'+custom).html(total_amount);
		$('#h_total_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#total_'+custom).html(total_amount);
			$('#h_total_'+custom).val(total_amount);
		}
		
	}
	else
	{
		//alert(add_price);
		method = document.getElementById('r_n_method_'+$(this).attr('custom')).value;
		//alert(method);
		type   = document.getElementById('r_n_type_'+$(this).attr('custom')).value;
		//alert(type);
		amount = parseInt(document.getElementById('r_n_amt_'+$(this).attr('custom')).value);
		if(document.getElementById('r_n_amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#r_n_total_'+custom).html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#r_n_h_total_'+custom).val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#r_n_total_'+custom).html(total_amount);
			$('#r_n_h_total_'+custom).val(total_amount);
		}
	}
	//alert(total_amount);
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});


$(document).on('change','.cal_amt1',function(){
	
	custom = $(this).attr('custom');
	add_price = parseInt(document.getElementById('eadd_price1').value);
	//alert(add_price);
	var n_r_method = $(this).attr('method');
	
	if(n_r_method=='refun')
	{
		method = document.getElementById('method_1_'+$(this).attr('custom')).value;
		type   = document.getElementById('type_1_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('amt_1_'+$(this).attr('custom')).value);
		if(document.getElementById('amt_1_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#total_1_'+custom).html(total_amount);
		$('#h_total_1_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#total_1_'+custom).html(total_amount);
			$('#h_total_1_'+custom).val(total_amount);
		}
		
	}
	else
	{
		method = document.getElementById('n_method_1_'+$(this).attr('custom')).value;
		type   = document.getElementById('n_type_1_'+$(this).attr('custom')).value;
		amount = parseInt(document.getElementById('n_amt_1_'+$(this).attr('custom')).value);
		if(document.getElementById('n_amt_1_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#n_total_1_'+custom).html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#n_h_total_1_'+custom).val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#n_total_1_'+custom).html(total_amount);
			$('#n_h_total_1_'+custom).val(total_amount);
		}
	}
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});

$(document).on('change','.rcal_amt1',function(){
	
	custom = $(this).attr('custom');
	add_price = parseInt(document.getElementById('eadd_price1').value);
	var n_r_method = $(this).attr('method');
	
	if(n_r_method=='refun')
	{
		method = document.getElementById('method_1_'+$(this).attr('custom')).value;
		type   = document.getElementById('type_1_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('amt_1_'+$(this).attr('custom')).value);
		if(document.getElementById('amt_1_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#total_1_'+custom).html(total_amount);
		$('#h_total_1_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#total_1_'+custom).html(total_amount);
			$('#h_total_1_'+custom).val(total_amount);
		}
		
	}
	else
	{
		method = document.getElementById('r_e_n_method_1_'+$(this).attr('custom')).value;
		type   = document.getElementById('r_e_n_type_1_'+$(this).attr('custom')).value;
		amount = parseInt(document.getElementById('r_e_n_amt_1_'+$(this).attr('custom')).value);
		if(document.getElementById('r_e_n_amt_1_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#r_e_n_total_1').html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#r_e_n_h_total_1').val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#r_e_n_total_1').html(total_amount);
			$('#r_e_n_h_total_1').val(total_amount);
		}
	}
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});

$(document).on('change','.cal_amt2',function(){
	
	custom = $(this).attr('custom');
	//alert(custom);
	
	add_price = parseInt(document.getElementById('add_price1').value);
	
	var n_r_method = $(this).attr('method');
	//alert(n_r_method);
	if(n_r_method=='refun')
	{
		//alert(add_price);
		method = document.getElementById('r_t_method_'+$(this).attr('custom')).value;
		type   = document.getElementById('r_t_type_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('r_t_amt_'+$(this).attr('custom')).value);
		if(document.getElementById('r_t_amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#r_t_total_'+custom).html(total_amount);
		$('#r_t_h_total_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#r_t_total_'+custom).html(total_amount);
			$('#r_t_h_total_'+custom).val(total_amount);
		}
		
	}
	else
	{
		//alert(add_price);
		method = document.getElementById('r_t_n_method_'+$(this).attr('custom')).value;
		type   = document.getElementById('r_t_n_type_'+$(this).attr('custom')).value;
		amount = parseInt(document.getElementById('r_t_n_amt_'+$(this).attr('custom')).value);
		if(document.getElementById('r_t_n_amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#r_t_n_total_'+custom).html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#r_t_n_h_total_'+custom).val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#r_t_n_total_'+custom).html(total_amount);
			$('#r_t_n_h_total_'+custom).val(total_amount);
		}
	}
	///alert(total_amount);
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});

$(document).on('change','.r_cal_amt2',function(){
	
	custom = $(this).attr('custom');
	//alert(custom);
	
	add_price = parseInt(document.getElementById('add_price1').value);
	
	var n_r_method = $(this).attr('method');
	//alert(n_r_method);
	if(n_r_method=='refun')
	{
		//alert(add_price);
		method = document.getElementById('method_'+$(this).attr('custom')).value;
		type   = document.getElementById('type_'+$(this).attr('custom')).value;
		
		amount = parseInt(document.getElementById('amt_'+$(this).attr('custom')).value);
		if(document.getElementById('amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			//alert(total_amount);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
			
		}
		
		
		//alert(total_amount);
		$('#total_'+custom).html(total_amount);
		$('#h_total_'+custom).val(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#total_'+custom).html(total_amount);
			$('#h_total_'+custom).val(total_amount);
		}
		
	}
	else
	{
		//alert(add_price);
		method = document.getElementById('rr_t_n_method_'+$(this).attr('custom')).value;
		//alert(method);
		type   = document.getElementById('rr_t_n_type_'+$(this).attr('custom')).value;
		//alert(type);
		amount = parseInt(document.getElementById('rr_t_n_amt_'+$(this).attr('custom')).value);
		if(document.getElementById('rr_t_n_amt_'+$(this).attr('custom')).value!='')
		{
		if(type=='Rs')
		{
			if(method=='+')
			{
				cal_method = 'add';
			}
			else
			{
				cal_method = 'minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		else
		{
			if(method=='+')
			{
				cal_method = 'flat_percent_add';
			}
			else
			{
				cal_method = 'flat_percent_minus';
			}
			var total_amount = calculate(add_price,amount,cal_method);
		}
		$('#rr_t_n_total_'+custom).html(total_amount);
		$(this).val(numberWithCommas(parseFloat(amount).toFixed(2)));
		$('#rr_t_n_h_total_'+custom).val(total_amount);
		}
		else
		{
			var total_amount = calculate(add_price,'0.00',cal_method);
			$(this).val('0.00');
			$('#rr_t_n_total_'+custom).html(total_amount);
			$('#rr_t_n_h_total_'+custom).val(total_amount);
		}
	}
	//alert(total_amount);
	/*alert($(this).attr('custom'));
	alert($(this).attr('method'));*/
});


function calculate(e,n,t)
{
	var r = 0;
	switch (t) {
                case "flat_percent_add":
                    r = parseFloat(e) * (1 + parseFloat(n) / 100);
                    break;
				case "flat_percent_minus":
                    r = parseFloat(e) * (1 - parseFloat(n) / 100);
                    break;
                case "add":
                    r = parseFloat(e) + parseFloat(n);
                    break;
                case "minus":
                    r = parseFloat(e) - parseFloat(n);
                    break;
                default:
            }
            return numberWithCommas(r.toFixed(2));
}


$(document).on('click','.update_amenities',function(){
	setTimeout(function()
	{
		update_data = $('#update_amenities').serialize()+'&'+$('#update_amenities_modal').serialize();
		$.ajax({
			type:'POST',
			data:update_data,
			url:base_url+'inventory/update_amenities',
			success:function(result)
			{
				
			}
		});
	},
	2000);
});

$(document).on('click','.update_amenities_modal',function(){
	setTimeout(function()
	{
		update_data = $('#update_amenities_modal').serialize()+'&'+$('#update_amenities').serialize();
		$.ajax({
			type:'POST',
			data:update_data,
			url:base_url+'inventory/update_amenities',
			success:function(result)
			{
				
			}
		});
	},
	2000);
});




$(function() {
        $('#card_number').validateCreditCard(function(result) {
			/*$('.log').html('<strong>Card type: </strong>' + (result.card_type == null ? '-' : result.card_type.name)
			 + '<br><strong>Valid: </strong>' + result.valid
			 + '<br><strong>Length valid: </strong>' + result.length_valid
			 + '<br><strong>Luhn valid: </strong>' + result.luhn_valid);*/
			 
			card_type =  result.card_type == null ? '-' : result.card_type.name
			 
			 $('#payment_card').val(card_type);
			if(result.card_type == null)
            {
               // $('#card_number').removeClass();
            }
            else
            {
                $('#card_number').addClass(result.card_type.name);
            }
            
            if(!result.valid)
            {
                $('#card_number').removeClass("valid");
            }
            else
            {
                $('#card_number').addClass("valid");
            }
            
        });
    });

$(document).on('change','#use_existing_card',function(){
	cart_type = ($('#use_existing_card').val());
	if(cart_type=='false')
	{
		$('#new_card').show();
		$('#existing_card_fields').hide();
	}
	else
	{
		$('#new_card').hide();
		$('#existing_card_fields').show();
	}
});

$(document).on('click','#935643660',function()
{
	$('#pay_paypal').val('');
	$('#card_options').show();
});

$(document).on('click','#935648652',function()
{
	$('#pay_paypal').val('paypal');
	$('#card_options').hide();
});

$(document).on("click", "a[data-remote=true]", function(e){
	
	$("#heading_loader").fadeIn("slow");
	
    e.preventDefault();
	
	$.ajax({
		type:'post',
		url : $(this).attr('href'),
		success:function(output)
		{
			$('#document').html(output);
			$('#edit_rate').modal({backdrop: 'static',keyboard: false});
			$("#heading_loader").fadeOut("slow");
		}
	});
    
	//$.getScript($(this).attr('href'));
	
});


$(document).on("submit", "form[data-remote=true]", function(e){
    e.preventDefault();
    $.getScript($(this).attr('action'))
});

$(document).on("click",".change_month",function(){
	//alert($(this).attr('custom'));
	$("#heading_loader").fadeIn("slow");
	$.ajax({
		type:'post',
		data:'nr_pr_date='+$(this).attr('custom'),
		url:base_url+'inventory/change_month',
		success: function(result){
			$('.change_month_replace').html(result);
			$('.inline_username').editable();
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on("click",'.prev_month',function(){
	$("#heading_loader").fadeIn("slow");
	nr_pr_date = $('#prev_month').val();
	method     = "prev"
	$.ajax({
		type:'post',
		data:'nr_pr_date='+nr_pr_date+"&method="+method,
		url:base_url+'inventory/change_month',
		success: function(result){
			$('.change_month_replace').html(result);
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on("click",'.next_month',function(){
	$("#heading_loader").fadeIn("slow");
	nr_pr_date = $('#next_month').val();
	method     = "next"
	$.ajax({
		type:'post',
		data:'nr_pr_date='+nr_pr_date+"&method="+method,
		url:base_url+'inventory/change_month',
		success: function(result){
			$('.change_month_replace').html(result);
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on("click",".change_months",function(){
	//alert($(this).attr('custom'));
	$("#heading_loader").fadeIn("slow");
	$.ajax({
		type:'post',
		data:'nr_pr_date='+$(this).attr('custom'),
		url:base_url+'inventory/change_month_no',
		success: function(result){
			$('.change_month_replaces').html(result);
			$('.inline_username').editable();
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on("click",'.prev_months',function(){
	$("#heading_loader").fadeIn("slow");
	nr_pr_date = $('#prev_months').val();
	method     = "prev"
	$.ajax({
		type:'post',
		data:'nr_pr_date='+nr_pr_date+"&method="+method,
		url:base_url+'inventory/change_month_no',
		success: function(result){
			$('.change_month_replaces').html(result);
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on("click",'.next_months',function(){
	$("#heading_loader").fadeIn("slow");
	nr_pr_date = $('#next_months').val();
	method     = "next"
	$.ajax({
		type:'post',
		data:'nr_pr_date='+nr_pr_date+"&method="+method,
		url:base_url+'inventory/change_month_no',
		success: function(result){
			$('.change_month_replaces').html(result);
			$("#heading_loader").fadeOut("slow");
			$(document).trigger('thiru');
		}
		
	});
});

$(document).on('click','.single_update',function(){
	/*alert($(this).attr('room_id'));
	alert($(this).attr('up_date'));*/
	$("#heading_loader").fadeIn("slow");
	$.ajax({
		type:'post',
		url:base_url+'inventory/single_date_update',
		data:'room_id='+$(this).attr('room_id')+"&up_date="+$(this).attr('up_date'),
		success:function(result)
		{
			$('#single_update').html(result);
			$('#modal_single_update').modal({backdrop: 'static',keyboard: false});
			$("#heading_loader").fadeOut("slow");
		}
	});
});

$(document).on('click','.reser_pop',function()
{
	//alert($(this).attr('id'));
	$("#heading_loader").fadeIn("slow");
	
	$.ajax({
		type:'post',
		url:base_url+'inventory/get_reservation_details',
		data:'reservation_id='+$(this).attr('id'),
		success:function(result)
		{
			$('#reservation_user').html(result);
			$('#reservation_user_details').modal({backdrop: 'static',keyboard: false});
			$("#heading_loader").fadeOut("slow");
		}
	});
	return false;
});

$(document).on('click','.reservationyes',function()
{
	$("#heading_loader").fadeIn("slow");
	$(this).addClass('reservationno');
	$(this).removeClass('reservationyes');
	window.location=base_url+"inventory/advance_update/reservation_no";
	
});

$(document).on('click','.reservationno',function()
{
	$("#heading_loader").fadeIn("slow");
	$(this).addClass('reservationyes');
	$(this).removeClass('reservationno');
	window.location=base_url+"inventory/advance_update";
});

$(document).on('click','#cls_passive',function()
{
	var me = $(this);
	$("#heading_loader").fadeIn("slow");
	status_type = ($(this).attr('type'));
	status_method = ($(this).attr('method'));
	status_id     = ($(this).attr('data-id'));
	$.ajax({
		type:'POST',
		url:base_url+'channel/polices_status',
		data:'status_type='+status_type+'&status_method='+status_method,
		success:function(result)
		{
			$("#heading_loader").fadeOut("slow");
			
			$('#policy_state').html(result);
			if(status_method=='passive')
			{
				$('#status_id_'+status_id).html('Passive').switchClass('btn-success', 'btn-danger');
			}
			else if(status_method=='active')
			{
				$('#status_id_'+status_id).html('Active').switchClass('btn-danger', 'btn-success');	
			}
		},
	});
	return false;
	
});

$(document).on('click','#cls_cancel_passives',function()
{
	var me = $(this);
	$("#heading_loader").fadeIn("slow");
	status_type = ($(this).attr('type'));
	status_method = ($(this).attr('method'));
	status_id     = ($(this).attr('data-id'));
	$.ajax({
		type:'POST',
		url:base_url+'channel/polices_status',
		data:'status_type='+status_type+'&status_method='+status_method,
		success:function(result)
		{
			$("#heading_loader").fadeOut("slow");
			$('#policy_cancel_states').html(result);
			if(status_method=='passive')
			{
				$('#status_id_'+status_id).html('Passive').switchClass('btn-success', 'btn-danger');
			}
			else if(status_method=='active')
			{
				$('#status_id_'+status_id).html('Active').switchClass('btn-danger', 'btn-success');	
			}
		},
	});
	return false;
});

$(document).on('click','#paypal_active',function()
{
	var me = $(this);
	$("#heading_loader").fadeIn("slow");
	status_type = ($(this).attr('type'));
	status_method = ($(this).attr('method'));
	status_id     = ($(this).attr('data-id'));
	$.ajax({
		type:'POST',
		url:base_url+'reservation/payment_status',
		data:'status_type='+status_type+'&status_method='+status_method,
		success:function(result)
		{
			$("#heading_loader").fadeOut("slow");
			
			$('#paypal_state').html(result);
		},
	});
	return false;
	
});


$(document).on('change','.deposit_fee',function()
{
	deposit_fee = ($(this).val());
	deposit_current_status = ($('#deposit_current_status').val());
	if(deposit_current_status=='1' || deposit_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			$('#ddescription').val('Entire reservation amount will be charged');
			$('.well').html('Entire reservation amount will be charged');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge '+fee_amount+' '+usr_cur_id+' at reservation.');
			$('.well').html('Property will charge '+fee_amount+' '+usr_cur_id+' at reservation.');
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge '+fee_amount+' % at reservation.');
			$('.well').html('Property will charge '+fee_amount+' % at reservation.');
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge Rate for one night at reservation.');
			$('.well').html('Property will charge Rate for one night at reservation.');
		}
	}
	else
	{
		$('#ddescription').val('Entire reservation amount will be charged');
		$('.well').html('Entire reservation amount will be charged');
	}
});


$(document).on('change','.fee_amount',function()
{
	deposit_fee =  ($('#fee_type').val());
	deposit_current_status = ($('#deposit_current_status').val());
	if(deposit_current_status=='1' || deposit_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			$('#ddescription').val('Entire reservation amount will be charged');
			$('.well').html('Entire reservation amount will be charged');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge '+fee_amount+' '+usr_cur_id+' at reservation.');
			$('.well').html('Property will charge '+fee_amount+' '+usr_cur_id+' at reservation.');
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge '+fee_amount+' % at reservation.');
			$('.well').html('Property will charge '+fee_amount+' % at reservation.');
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#fee_amount').val();
			$('#ddescription').val('Property will charge Rate for one night at reservation.');
			$('.well').html('Property will charge Rate for one night at reservation.');
		}
	}
	else
	{
		$('#ddescription').val('Entire reservation amount will be charged');
		$('.well').html('Entire reservation amount will be charged');
	}
});


$(document).on('change','.cancel_fee',function()
{
	deposit_fee = ($(this).val());
	cancel_current_status = ($('#cancel_current_status').val());
	if(cancel_current_status=='1' || cancel_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			
			
		}
	}
	else
	{
		$('#cdescription').val('Free cancelation');
		$('.cwell').html('Free cancelation');
	}
});


$(document).on('change','.cancel_fee_amount',function()
{
	deposit_fee = ($('#cancel_fee_type').val());
	cancel_current_status = ($('#cancel_current_status').val());
	if(cancel_current_status=='1' || cancel_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			
			
		}
	}
	else
	{
		$('#cdescription').val('Free cancelation');
		$('.cwell').html('Free cancelation');
	}
});

$(document).on('change','.cancel_fee_amount',function()
{
	deposit_fee = ($('#cancel_fee_type').val());
	cancel_current_status = ($('#cancel_current_status').val());
	if(cancel_current_status=='1' || cancel_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			
			
		}
	}
	else
	{
		$('#cdescription').val('Free cancelation');
		$('.cwell').html('Free cancelation');
	}
});

$(document).on('change','.prior_checkin_time',function()
{
	deposit_fee = ($('#cancel_fee_type').val());
	cancel_current_status = ($('#cancel_current_status').val());
	if(cancel_current_status=='1' || cancel_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			
			
		}
	}
	else
	{
		$('#cdescription').val('Free cancelation');
		$('.cwell').html('Free cancelation');
	}
});

$(document).on('change','.prior_checkin_days',function()
{
	deposit_fee = ($('#cancel_fee_type').val());
	cancel_current_status = ($('#cancel_current_status').val());
	if(cancel_current_status=='1' || cancel_current_status==1)
	{
		if(deposit_fee=='1' || deposit_fee==1)
		{
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a penalty. No refunds for no shows or early checkouts.');
		}
		else if(deposit_fee=='flat')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' '+usr_cur_id+' penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='%')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a '+fee_amount+' % penalty. No refunds for no shows or early checkouts.');
			
		}
		else if(deposit_fee=='night')
		{
			usr_cur_id = $('#usr_cur_id').val();
			fee_amount = $('#cfee_amount').val();
			prior_checkin_time = $('#prior_checkin_time').val();
			prior_checkin_days = $('#prior_checkin_days').val();
			$('#cdescription').val('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			$('.cwell').html('Cancelations or changes made after '+prior_checkin_time+':00 on '+prior_checkin_days+' days before checkin date are subject to a Rate for one night penalty. No refunds for no shows or early checkouts.');
			
			
		}
	}
	else
	{
		$('#cdescription').val('Free cancelation');
		$('.cwell').html('Free cancelation');
	}
});




//// test 

var HrPriceCalculation = {};

HrPriceCalculation = function(e) {
    return {
		
        change_value_with_sign: function(e) {
            var t = e.val(),
                n = parseFloat(Math.abs(HrPriceCalculation.format_number(e.val())) * e.data("sign")).toFixed(2);
            if (isNaN(parseFloat(n))) return t;
            HrPriceCalculation.calculate_prompt(e, n), e.val(HrPriceCalculation.number_with_precision(n, 2))
        },
		
        calculate_all: function() {
            e.each(e(".consider_data_sign"), function(t, n) {
                HrPriceCalculation.change_value_with_sign(e(n))
            })
        },
		
        calculate_prompt: function(t, n) {
            try {
                var r = t.parent("div").prev("div").children("select").val();
                e(t).data("baseReference") ? base_reference_val = e(t.data("baseReference")).val() : base_reference_val = e(t).closest("td").prev("td").find(".base-reference").val();
                var i = HrPriceCalculation.calculate(HrPriceCalculation.format_number(base_reference_val || 0), r, n);
                t.next("span").val(i), t.next("span").text(i)
            } catch (s) {}
        },
		
        calculate: function(e, t, n) {
            var r = 0;
            switch (t) {
                case "flat_percent":
                    r = parseFloat(e) * (1 + parseFloat(n) / 100);
                    break;
                case "flat_rate":
                    r = parseFloat(e) + parseFloat(n);
                    break;
                case "per_item":
                    r = parseFloat(e) + parseFloat(n);
                    break;
                default:
            }
            return HrPriceCalculation.number_with_precision(Math.max(r, 0), 2)
        },
		
        format_number: function(e) {
            return (e + "").replace(t.delimiter, "").replace(t.seperator, ".")
        },
		
        number_with_precision: function(e, n) {
            return e = parseFloat(e).toFixed(2), parts = ("" + e + "").split("."), parts[0] = parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1" + t.delimiter), parts.join(t.seperator)
        },
		
        number_to_currency: function(e, t) {
            if (t == "TL") var n = "%n %u";
            else var n = "%u%n";
            return n.replace("%u", t).replace("%n", e)
        },
		
        number_to_currency_p: function(e, t) {
            if (t == "TL") var n = "%n %u";
            else var n = "%u%n";
            return HrPriceCalculation.number_with_precision(n.replace("%u", t).replace("%n", e))
        }
    }
}

	

    $(document).on('click','#cash_active',function()

    {

    var me = $(this);

    $("#heading_loader").fadeIn("slow");

    status_type = ($(this).attr('type'));

    status_method = ($(this).attr('method'));

    status_id = ($(this).attr('data-id'));

    $.ajax({

       type:'POST',

       url:base_url+'reservation/cash_status',

       data:'status_type='+status_type+'&status_method='+status_method,

       success:function(result)

       {

           $("#heading_loader").fadeOut("slow");
		   
		   if(status_method=='passive')
			{
				$('#cash_status_id_'+status_id).html('No').switchClass('btn-success', 'btn-danger');
			}
			else if(status_method=='active')
			{
				$('#cash_status_id_'+status_id).html('Yes').switchClass('btn-danger', 'btn-success');	
			}
			

           $('#cash_state').html(result);

       },

    });

    return false;

    });

$(document).on('click','#bank_active',function()
{
	var me = $(this);
	$("#heading_loader").fadeIn("slow");
	status_type = ($(this).attr('type'));
	status_method = ($(this).attr('method'));
	status_id     = ($(this).attr('data-id'));
	$.ajax({
		type:'POST',
		url:base_url+'reservation/bank_status',
		data:'status_type='+status_type+'&status_method='+status_method,
		success:function(result)
		{
			$("#heading_loader").fadeOut("slow");
			
			$('#bank_state').html(result);
		},
	});
	return false;
	
});


// sharmila....
// edit guest details...
$("#edit_form").validate({
	
	// Rulse start here	
			rules: {
                     guest_name: {
                        required: true,
						lettersonly: true,
                    },
					last_name: {
                        required: true,
						lettersonly: true,
                    },
					
					email: {
							required: true,
							customemail:true,
						},
					mobile: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 12,
								positiveNumber:true,
							},
					
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
	// bank  details...
	
	$("#bank_det").validate({
		
			rules: {
				
					account_owner: {
                        required: true,
						lettersonly2: true,
                    },
					
					bank_name:{
						required: true,
						lettersonly2: true,
					},
					branch_name:{
						required: true,
						lettersonly2: true,
					},
					branch_code: {
								required: true,
								number: true,
								minlength: 3,
								maxlength: 10,
								positiveNumber:true,
							},
					swift_code: {
								required: true,
								number: true,
								minlength: 6,
								maxlength: 12,
								positiveNumber:true,
					},
					iban: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 15,
								positiveNumber:true,
					},
					account_number: {
								required: true,
								number: true,
								minlength: 8,
								maxlength: 12,
								positiveNumber:true,
					},
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
	// edit bank details...
	
	$("#edit_bank_detailsss").validate({
		
			rules: {
					account_owner: {
                        required: true,
						lettersonly2: true,
                    },
					
					bank_name:{
						required: true,
						lettersonly2: true,
					},
					branch_name:{
						required: true,
						lettersonly2: true,
					},
					branch_code: {
								required: true,
								number: true,
								minlength: 3,
								maxlength: 10,
								positiveNumber:true,
							},
					swift_code: {
								required: true,
								number: true,
								minlength: 6,
								maxlength: 12,
								positiveNumber:true,
					},
					iban: {
								required: true,
								number: true,
								minlength: 10,
								maxlength: 15,
								positiveNumber:true,
					},
					account_number: {
								required: true,
								number: true,
								minlength: 8,
								maxlength: 12,
								positiveNumber:true,
					},
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});

// add adjustment ...	
	
$('#add_adjust').validate({
				rules:
				{
					description:
					{
						required:true,
					},
					inr_amount:
					{
						required:true,
						
					}
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
	});

// edit adjustment..

$('#edit_adjust').validate({
				rules:
				{
					description:
					{
						required:true,
						
					},
					inr_amount:
					{
						required:true,
					}
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
	});

	
	// edit cash..
	
	$('#cash_edit').validate({
				rules:
				{
					user_name:
					{
						required:true,
						lettersonly2: true,
					},
					
					user_des:
					{
						required:true,
						lettersonly2: true,
					},
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
	});
	
	
	// add paypal details...
	
	$("#add_paypaldet").validate({
		
			rules: {
				
					holder_name: {
                        required: true,
						lettersonly2: true,
                    },
					
					client_id: {
								required: true,
								number: true,
								minlength: 3,
								maxlength: 10,
								positiveNumber:true,
							},
					client_secret: {
								required: true,
								lettersonly: true,
					},
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
	
	// edit paypal details...
	
	$("#edit_paypaldet").validate({
		
			rules: {
				
					pay_method_name: {
                        required: true,
						lettersonly2: true,
                    },
					
					client_id: {
								required: true,
								number: true,
								minlength: 3,
								maxlength: 10,
								positiveNumber:true,
							},
					client_secret: {
								required: true,
								///lettersonly: true,
					},
                },
				
			errorPlacement: function(){
				
					return false; 
					
    		},
				 
			highlight: function (element) { 
                    $(element)
                        .closest('.nf-select').addClass('customErrorClass'); 
					$(element)
						.closest('.form-control').addClass('customErrorClass');
                },
			unhighlight: function (element) { 
                    $(element)
                        .closest('.nf-select').removeClass('customErrorClass'); 
					$(element)
						.closest('.form-control').removeClass('customErrorClass');
                },
	});
	
	
	// add tax category...
	 $('#add_taxcategory').validate({
				rules:
				{
					user_name:
					{
						required:true,
						lettersonly2: true,
					},
					tax_rate:
					{
						required: true,
								number: true,
								positiveNumber:true,
					},
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
	}); 
	
	// edit tax category...
	 $('#edit_taxcategory').validate({
				rules:
				{
					user_name:
					{
						required:true,
						lettersonly2: true,
					},
					tax_rate:
					{
						required: true,
								number: true,
								positiveNumber:true,
					},
					
				},
				errorPlacement: function (error, element) {
					return false;
				},
				highlight: function (element) { // hightlight error inputs
						  $(element)
							  .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
					  },
			    unhighlight: function (element) { // revert the change done by hightlight
						  $(element)
							  .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
					  },
	}); 

	/* sharmila  */

$(document).ready(function () {
    $("#datepicker_start").datepicker({
        dateFormat: "dd/mm/yy",
        minDate: 0,
        onSelect: function () {
            var dt2 = $('#datepicker_end');
            var startDate = $(this).datepicker('getDate');
            //add 30 days to selected date
            startDate.setDate(startDate.getDate() + 30);
            var minDate = $(this).datepicker('getDate');
            //minDate of dt2 datepicker = dt1 selected day
            dt2.datepicker('setDate', minDate);
            //sets dt2 maxDate to the last day of 30 days window
            dt2.datepicker('option', 'maxDate', startDate);
            //first day which can be selected in dt2 is selected date in dt1
            dt2.datepicker('option', 'minDate', minDate);
            //same for dt1
            $(this).datepicker('option', 'minDate', minDate);
			
			$('#datepicker_end').focus();
						
        }
    })
	var startDate = $('#datepicker_start').datepicker('getDate');
	startDate.setDate(startDate.getDate() + 30);
    $('#datepicker_end').datepicker({
        dateFormat: "dd/mm/yy",
		minDate: 0,	
		maxDate: startDate
		
    });
	
	$("#datepicker_starts").datepicker({
        dateFormat: "dd/mm/yy",
        minDate: 0,
        onSelect: function () {
            var dt2 = $('#datepicker_end');
            var startDate = $(this).datepicker('getDate');
            //add 30 days to selected date
            startDate.setDate(startDate.getDate() + 30);
            var minDate = $(this).datepicker('getDate');
            //minDate of dt2 datepicker = dt1 selected day
            dt2.datepicker('setDate', minDate);
            //sets dt2 maxDate to the last day of 30 days window
            dt2.datepicker('option', 'maxDate', startDate);
            //first day which can be selected in dt2 is selected date in dt1
            dt2.datepicker('option', 'minDate', minDate);
            //same for dt1
            $(this).datepicker('option', 'minDate', minDate);
			
			$('#datepicker_end').focus();
						
        }
    })
	var startDate = $('#datepicker_starts').datepicker('getDate');
	startDate.setDate(startDate.getDate() + 30);
    $('#datepicker_ends').datepicker({
        dateFormat: "dd/mm/yy",
		minDate: 0,	
		maxDate: startDate
		
    });
	
});