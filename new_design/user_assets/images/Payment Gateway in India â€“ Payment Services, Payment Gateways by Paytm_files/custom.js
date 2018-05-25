/*var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-56273196-1']);
_gaq.push(['_trackPageview']);

(function () {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
})();*/

$(document).ready(function () {
    $("a").click(function (event) {
        var eventTitle;
        if ($(this).attr("title") != "") {
            eventTitle = $(this).attr("title");
        } else {
            eventTitle = $(this).attr("href");
        }
        _gaq.push(['_trackEvent', window.location.pathname, 'click', eventTitle]);
    });
    $("input").click(function (event) {
        var eventTitle = $(this).attr("title");

        _gaq.push(['_trackEvent', window.location.pathname, 'submit', eventTitle]);
    });
});

$(document).ready(function () {
    $('#reachOut').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later


        fields: {
            reply_to: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Email id is required'
                    },
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    }


                }
            },
            subject: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: ''
                    }


                }
            },
            body: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Please enter message'
                    }


                }
            },
            name: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Please enter Name'
                    }


                }
            },
            locations: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Please select the location'
                    }


                }
            },
            partnerWithUS: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Please select the value'
                    }


                }
            },
            phone: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Phone Number is required'
                    },
                    regexp: {
                        regexp: /^(\d{10})$/,
                        message: 'Enter 10 digit mobile number'
                    }
                }
            },
            websiteURL: {
                trigger: 'blur',
                validators: {
                    regexp: {
                        regexp:/^[a-zA-Z0-9\-\.]+\.(com|in|co|co.in|org|net|mil|edu|COM|IN|CO|CO.IN|ORG|NET|MIL|EDU)$/,
                        //regexp: /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i,
                        message: 'Enter valid Website URL'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {
        // Prevent submit form
        e.preventDefault();

        var $form = $(e.target),
            validator = $form.data('bootstrapValidator');
        var param = {};
        param['email'] = validator.getFieldElements('reply_to').val();
        param.subject = validator.getFieldElements('subject').val();
        param['00N9000000Cnf4T'] = validator.getFieldElements('body').val();
        param['mobile'] = validator.getFieldElements('phone').val();
        param['first_name'] = validator.getFieldElements('name').val();
        param['00N9000000BuTtt'] = validator.getFieldElements('locations').val();
        param['00N9000000BuTtw'] = validator.getFieldElements('partnerWithUS').val();
        param['00N9000000AyX4C'] = validator.getFieldElements('websiteURL').val();
        param['oid'] = '00D90000000xmjO';
        param['lead_source'] = 'Partner';
        ajaxFormSubmit(param);



    });

    function ajaxFormSubmit(param) {


        var postData = param;// decodeURIComponent($.param(param));

        var formURL = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';

        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            success: function (response, textStatus, jqXHR) {
                successMessage = 'Thank you! Our representative will get back to you shortly.';
                /*var responseMsg = JSON.parse(response);
                if (responseMsg.status == 0) {
                    successMessage = 'Thank you! Our representative will get back to you shortly.';

                } else if (responseMsg.status == 1) {
                    successMessage = 'Something went wrong.';
                } else if (responseMsg.status == 2) {
                    successMessage = responseMsg.message + '. Please correct the following errors and retry again';
                    $.each(responseMsg.data, function (key, val) {
                        successMessage += '<br/>' + val;
                    });
                } else {
                    successMessage = 'Something went wrong.';
                }*/

                $('#submit').val('Thank you');

                //buttonLink.removeAttr("disabled");
                $('#alert-msg').html(successMessage).slideDown(400).delay(7000).fadeOut(400).html();
                $('#reachOut').bootstrapValidator('resetForm', true);


            },
            error: function (jqXHR, textStatus, errorThrown) {
                successMessage = 'Thank you! Our representative will get back to you shortly.';
               /* successMessage = 'Something went wrong';*/
                $('#submit').val('Thank you.');

                //buttonLink.removeAttr("disabled");
                $('#alert-msg').html(successMessage).slideDown(400).delay(7000).fadeOut(400).html();

                $('#reachOut').bootstrapValidator('resetForm', true);
            }
        });

    }
});