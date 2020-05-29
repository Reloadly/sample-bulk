if ($(".steps-validation").length){
    var form = $(".steps-validation").show();
    $(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Proceed to Payment'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            if (currentIndex == 1)
            {
                form.validate().settings.ignore = ':disabled,:hidden:not(.active input[type="hidden"].validate)';
                if (!form.valid())
                    return form.valid();
                if ($('ul[role="tablist"] li a.active').html() == "File")
                    return form.valid();
                $.ajax({
                    type: 'GET',
                    url: 'operators/detect/'+ $('#number').val()+'/'+ $('select[name="country"]').val(),
                    data: [],
                    success: function (operator) {
                        if (!operator){
                            toastr.error('Unable to Auto Detect Operator. Please select on next page.', 'Error');
                            return;
                        }
                        $('select[name="operator"]').val(operator.id);
                        $('#fx_rate').html(operator.fx_rate.toFixed(4) + " " + operator.destination_currency.code + " / " + operator.sender_currency.code);
                        $('#amount_currency_code').html(operator.sender_currency.code);
                        if (operator.denomination_type == 'RANGE')
                        {
                            $('#amount_input_div').removeClass('d-none');
                            $('#amount_select_div').addClass('d-none');
                            $('#text_amount').attr('min',operator.min_amount);
                            $('#text_amount').attr('max',operator.max_amount);
                            $('#select_amount').html('');
                            $('#select_amount').prop('disabled',true);
                            $('#text_amount').prop('disabled',false);
                        }else{
                            $('#amount_input_div').addClass('d-none');
                            $('#amount_select_div').removeClass('d-none');
                            $('#text_amount').prop('disabled',true);
                            $('#select_amount').prop('disabled',false);
                        }
                        $('#select_amount').html('');
                        $.each(operator.select_amounts,function(key,amount)
                        {
                            $("#select_amount").append('<option value=' + amount + '>' + amount + ' ' + operator.sender_currency.code + '</option>');
                        });
                    },
                    error: function (response) {
                        response = $.parseJSON(response.responseText);
                        $.each(response, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value, 'Error');
                                });
                            }
                        });
                    }
                });
            }
            else
                form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ':disabled,:hidden:not(.active input[type="hidden"].validate)';
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            form.find('a[href="#finish"]').append("<i class=\"fa fa-spinner fa-spin d-none\"></i>");
            form.submit();
        }
    });
    // Initialize validation
    $(".steps-validation").validate({
        ignore: ":disabled,:hidden",
        errorClass: 'danger',
        successClass: 'success',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });
}

if ($("div#dpz-single-file").length){
    Dropzone.autoDiscover = false;
    $("div#dpz-single-file").dropzone({
        paramName: "csv",
        url: '/file/upload',
        params: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        uploadMultiple: false,
        acceptedFiles: '.csv',
        init: function () {
            this.on("maxfilesexceeded", function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
            this.on("sending",function (file,xhr,formData) {
                formData.append("country_id", $('select[name="country"]').val());
            });
            this.on("success",function (file,response) {
                $('input[name="file_id"]').val(response.file_id);
                if (!response.operator){
                    toastr.error('Unable to Auto Detect Operator. Please select on next page.', 'Error');
                    return;
                }
                $('select[name="operator"]').val(response.operator.id);
                $('#fx_rate').html(response.operator.fx_rate.toFixed(4) + " " + response.operator.destination_currency.code + " / " + response.operator.sender_currency.code);
                $('#amount_currency_code').html(response.operator.sender_currency.code);
                if (response.operator.denomination_type == 'RANGE')
                {
                    $('#amount_input_div').removeClass('d-none');
                    $('#amount_select_div').addClass('d-none');
                    $('#text_amount').attr('min',response.operator.min_amount);
                    $('#text_amount').attr('max',response.operator.max_amount);
                    $('#select_amount').html('');
                    $('#select_amount').prop('disabled',true);
                    $('#text_amount').prop('disabled',false);
                }else{
                    $('#amount_input_div').addClass('d-none');
                    $('#amount_select_div').removeClass('d-none');
                    $('#text_amount').prop('disabled',true);
                    $('#select_amount').prop('disabled',false);
                }
                $('#select_amount').html('');
                $.each(response.operator.select_amounts,function(key,amount)
                {
                    $("#select_amount").append('<option value=' + amount + '>' + amount + ' ' + response.operator.sender_currency.code + '</option>');
                });
            });
            this.on("error",function (file,response) {
                $.each(response, function (key, value) {
                    if ($.isPlainObject(value)) {
                        $.each(value, function (key, value) {
                            toastr.error(value, 'Error');
                        });
                    }
                });
            })
        }
    });
}

$(document).on('change','#country',function () {
    $.ajax({
        type: 'GET',
        url: 'api/countries/'+this.value+'/operators',
        data: [],
        success: function (response) {
            $("#operator").html('');
            $.each(response,function(key,operator)
            {
                $("#operator").append('<option value=' + operator.id + '>' + operator.name + '</option>');
            });
            var operator = response[0];
            $('#fx_rate').html(operator.fx_rate.toFixed(4) + " " + operator.destination_currency.code + " / " + operator.sender_currency.code);
            $('#amount_currency_code').html(operator.sender_currency.code);
            if (operator.denomination_type == 'RANGE')
            {
                $('#amount_input_div').removeClass('d-none');
                $('#amount_select_div').addClass('d-none');
                $('#text_amount').attr('min',operator.min_amount);
                $('#text_amount').attr('max',operator.max_amount);
                $('#select_amount').html('');
                $('#select_amount').prop('disabled',true);
                $('#text_amount').prop('disabled',false);
            }else{
                $('#amount_input_div').addClass('d-none');
                $('#amount_select_div').removeClass('d-none');
                $('#text_amount').prop('disabled',true);
                $('#select_amount').prop('disabled',false);
            }
            $('#select_amount').html('');
            $.each(operator.select_amounts,function(key,amount)
            {
                $("#select_amount").append('<option value=' + amount + '>' + amount + ' ' + operator.sender_currency.code + '</option>');
            });
        },
        error: function (response) {
            response = $.parseJSON(response.responseText);
            $.each(response, function (key, value) {
                if ($.isPlainObject(value)) {
                    $.each(value, function (key, value) {
                        toastr.error(value, 'Error');
                    });
                }
            });
        }
    });
});
$(document).on('change','#operator',function () {
    $.ajax({
        type: 'GET',
        url: 'api/operators/'+this.value,
        data: [],
        success: function (operator) {
            $('#fx_rate').html(operator.fx_rate.toFixed(4) + " " + operator.destination_currency.code + " / " + operator.sender_currency.code);
            $('#amount_currency_code').html(operator.sender_currency.code);
            if (operator.denomination_type == 'RANGE')
            {
                $('#amount_input_div').removeClass('d-none');
                $('#amount_select_div').addClass('d-none');
                $('#text_amount').attr('min',operator.min_amount);
                $('#text_amount').attr('max',operator.max_amount);
                $('#select_amount').html('');
                $('#select_amount').prop('disabled',true);
                $('#text_amount').prop('disabled',false);
            }else{
                $('#amount_input_div').addClass('d-none');
                $('#amount_select_div').removeClass('d-none');
                $('#text_amount').prop('disabled',true);
                $('#select_amount').prop('disabled',false);
            }
            $('#select_amount').html('');
            $.each(operator.select_amounts,function(key,amount)
            {
                $("#select_amount").append('<option value=' + amount + '>' + amount + ' ' + operator.sender_currency.code + '</option>');
            });
        },
        error: function (response) {
            response = $.parseJSON(response.responseText);
            $.each(response, function (key, value) {
                if ($.isPlainObject(value)) {
                    $.each(value, function (key, value) {
                        toastr.error(value, 'Error');
                    });
                }
            });
        }
    });
});
$(document).on('change','#select_amount',function () {
    var fx_rate = parseFloat($('#fx_rate').html().split(' ')[0]);
    fx_rate = fx_rate * $(this).val();
    fx_rate = fx_rate.toFixed(4);
    $('#sending_amount').html(fx_rate +' '+ $('#fx_rate').html().split(' ')[1]);
});
if ($('#text_amount').length)
    $('#text_amount').keyup(function () {
        var fx_rate = parseFloat($('#fx_rate').html().split(' ')[0]);
        fx_rate = fx_rate * $(this).val();
        fx_rate = fx_rate.toFixed(4);
        $('#sending_amount').html(fx_rate +' '+ $('#fx_rate').html().split(' ')[1]);
    });
