@extends('dashboard.layout.app')

@section('body-class','2-column')
@section('page-name','Topups')

@push('css')
    <link rel="stylesheet" type="text/css" href="/css/pages/datatables.min.css">
@endpush

@section('content')
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-center align-items-center">
                        <h4 class="card-title"><i class="feather icon-codepen"></i> Topup History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Reloadly Id</th>
                                        <th>Operator</th>
                                        <th>Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($topups as $topup)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($topup['scheduled_datetime'],$topup['timezone']['utc'][0])->format('Y-m-d H:i T') }}</td>
                                        <td>{{ $topup['transaction_id'] }}</td>
                                        <td>
                                            <img src="{{ $topup['file_entry']['country']['flag'] }}" width="20px" class="mr-1">
                                            {{ $topup['file_entry']['country']['name'].' '.$topup['file_entry']['operator']['name'] }}
                                        </td>
                                        <td>{{ $topup['file_entry']['number'] }}</td>
                                        <td>{{ $topup['file_entry']['estimates']['amount'] }}</td>
                                        <td>
                                            @switch($topup['status'])
                                                @case('PENDING')
                                                <div class="badge badge-pill badge-primary">Pending</div>
                                                @break
                                                @case('SUCCESS')
                                                    @if(isset($topup['pin']) && sizeof($topup['pin']) > 0)
                                                        <button class="btn btn-sm round btn-success" data-toggle="modal-feed" data-target="#modal_lg" data-feed="topups/{{ $topup['id'] }}/pin_detail">Pin Available</button>
                                                    @else
                                                        <div class="badge badge-pill badge-success">Success</div>
                                                    @endif
                                                @break
                                                @case('FAIL')
                                                <button class="btn btn-sm round btn-danger" data-toggle="modal-feed" data-target="#modal_lg" data-feed="topups/{{ $topup['id'] }}/failed">Failed</button>
                                                @break
                                                @case('PENDING_PAYMENT')
                                                <div class="badge badge-pill badge-secondary">Pending Payment</div>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Reloadly Id</th>
                                        <th>Operator</th>
                                        <th>Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="d-none">
        <div class="dataTables_length dataTables_custom dataTables_filter custom-status-filter">
            <label>Status
                <select class="custom-select custom-select-sm form-control form-control-sm">
                    <option value="any">Any</option>
                    <option value="PENDING">Pending</option>
                    <option value="SUCCESS">Success</option>
                    <option value="FAILED">Failed</option>
                    <option value="Pin Available">Pin</option>
                </select>
            </label>
            <label class="mx-5"> Has Transaction Id
                <input type="checkbox" class="form-control form-control-sm" style="height: auto;">
            </label>
        </div>
    </div>
    @include('dashboard.layout.modals')
@endsection

@push('js')
    <script src="/js/datatable/pdfmake.min.js"></script>
    <script src="/js/datatable/vfs_fonts.js"></script>
    <script src="/js/datatable/datatables.min.js"></script>
    <script src="/js/datatable/datatables.buttons.min.js"></script>
    <script src="/js/datatable/buttons.html5.min.js"></script>
    <script src="/js/datatable/buttons.print.min.js"></script>
    <script src="/js/datatable/buttons.bootstrap.min.js"></script>
    <script src="/js/datatable/datatables.bootstrap4.min.js"></script>
    <script src="/js/generic_change.js"></script>
    <script>
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var selectedStatus = $('#search-status .dataTables_length.dataTables_custom.dataTables_filter.custom-status-filter select').val();
                var showTransactions = $('#search-status .dataTables_length.dataTables_custom.dataTables_filter.custom-status-filter input[type="checkbox"]:checked').length > 0;
                if (
                    !(typeof selectedStatus === 'undefined' || selectedStatus === 'any' ||  selectedStatus.toLowerCase() == data[5].toLowerCase())
                    ||
                    (showTransactions && data[1] == 'NOT_AVAILABLE')
                )
                    return false;
                return true;
            }
        );
        window.dtView =  $('.zero-configuration').DataTable({
            dom: '<"row justify-content-between mb-2"l<"#search-status">f>rt<"row justify-content-center mt-2"p>',
        });
        $('div#search-status').html($('.dataTables_length.dataTables_custom.dataTables_filter.custom-status-filter'));
        $('#search-status .dataTables_length.dataTables_custom.dataTables_filter.custom-status-filter select').change(function () {
            window.dtView.draw();
        });
        $('#search-status .dataTables_length.dataTables_custom.dataTables_filter.custom-status-filter input[type="checkbox"]').on('change', function () {
            window.dtView.draw();
        });
    </script>
@endpush
