toastr.options = {
    'closeButton': false,
    'debug': false,
    'newestOnTop': false,
    'progressBar': false,
    'positionClass': 'toast-top-right',
    'preventDuplicates': false,
    'onclick': null,
    'showDuration': '3000',
    'hideDuration': '1000',
    'timeOut': '5000',
    'extendedTimeOut': '1000',
    'showEasing': 'swing',
    'hideEasing': 'linear',
    'showMethod': 'fadeIn',
    'hideMethod': 'fadeOut'
};

$(document).ready(function () {
    $(document).on('submit', 'form', function (e) {
        if ($(this).hasClass('no_generic')) {
            return;
        }
        e.preventDefault();
        let formData = $(this).serialize();
        let action = $(this).attr('action');
        var method = $(this).attr('method');
        var loader = $(this).find("button[type='submit'] i, a[href='#finish'] i");
        var submitButton = $(this).find("button[type='submit'], a[href='#finish']");
        $(loader).removeClass('d-none');
        $(submitButton).prop('disabled', true);
        $.ajax({
            type: method,
            url: action,
            data: formData,
            success: function (response) {
                $(loader).addClass('d-none');
                $(submitButton).prop('disabled', false);
                if (response.message) {
                    toastr.success(response.message, 'Success');
                } else {
                    toastr.success('All Done', 'Success');
                }
                if (response.location)
                    window.location = response.location;
                if (response.modal){
                    $.ajax({
                        type: 'GET',
                        url: response.feed,
                        data: '',
                        processData: false,
                        success: function (data) {
                            $(response.modal + ' .modal-content').html(data);
                            $(response.modal).modal('show');
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
            },
            error: function (response) {
                $(loader).addClass('d-none');
                $(submitButton).prop('disabled', false);
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
});
