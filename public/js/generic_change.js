$(document).on('click', '[data-toggle="change-status"]', function (e) {
    e.preventDefault();
    var url = $(this).attr('data-feed');
    var msg = $(this).attr('data-msg');

    swal.fire({
        title: 'Do you agree?',
        text: msg,
        type: 'warning',
        showCancelButton: !0,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: !0
    }).then(function (e) {
        if (e.value) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {},
                error: function (response) {
                    response = $.parseJSON(response.responseText);
                    $.each(response, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value, 'Error');
                            });
                        }
                    });
                },
                success: function (response) {
                    if (response.message) {
                        toastr.success(response.message, 'Success');
                    }
                    if (response.location) {
                        window.location = response.location;
                    }
                }
            });
        }
    });
});
