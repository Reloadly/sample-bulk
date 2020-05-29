@push('css')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/css/pages/sweetalert2.min.css">
@endpush

<div class="modal fade text-left" id="modal_sm" tabindex="-1" role="dialog" aria-labelledby="modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<div class="modal fade text-left" id="modal_lg" tabindex="-1" role="dialog" aria-labelledby="modal_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

@push('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/js/pages/sweetalert2.all.min.js"></script>
    <script>
        $(document).on('click','[data-toggle="modal-feed"]',function (e) {
            e.preventDefault();
            var modal = $(this).attr('data-target');
            var feed = $(this).attr('data-feed');
            $.ajax({
                type: 'GET',
                url: feed,
                data: '',
                processData: false,
                success: function (data) {
                    $(modal + ' .modal-content').html(data);
                    $(modal).modal('show');
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
        $(document).on('click', '[data-toggle="delete-feed"]', function (e) {
            e.preventDefault();
            let elem = $(this);
            let url = $(this).attr('data-feed');
            let msg = 'You won\'t be able to revert this!';
            let confirmButtonText = 'Yes, remove it!';
            let swalCancelText = 'The record has not been deleted.';
            let swalConfirmText = 'The record has been deleted.';
            let swalConfirmTitle = 'Deleted!';

            if (elem.attr('data-msg') !== undefined) {
                msg = elem.attr('data-msg');
            }

            if (elem.attr('data-confirm-button-text') !== undefined) {
                confirmButtonText = elem.attr('data-confirm-button-text');
            }

            if (elem.attr('data-swal-cancel-text') !== undefined) {
                swalCancelText = elem.attr('data-swal-cancel-text');
            }

            if (elem.attr('data-swal-confirm-text') !== undefined) {
                swalConfirmText = elem.attr('data-swal-confirm-text');
            }

            if (elem.attr('data-swal-confirm-title') !== undefined) {
                swalConfirmTitle = elem.attr('data-swal-confirm-title');
            }

            swal.fire({
                title: 'Are you sure?',
                text: msg,
                type: 'warning',
                showCancelButton: !0,
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'No, cancel!',
                reverseButtons: !0

            }).then(function (e) {
                if (e.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
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
                        success: function (data) {
                            swal.fire({
                                title: swalConfirmTitle,
                                text: swalConfirmText,
                                type: 'success',
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-brand'
                            }).then(function () {
                                window.location.reload(1);
                            });
                        }
                    });
                } else {
                    swal.fire({
                        title: 'Cancelled!',
                        text: swalCancelText,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-brand'
                    })
                }
            });
        });
    </script>
@endpush
