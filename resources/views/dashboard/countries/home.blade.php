@extends('dashboard.layout.app')

@section('body-class','2-column')
@section('page-name','Countries')

@push('css')
    <link rel="stylesheet" type="text/css" href="/css/pages/datatables.min.css">
@endpush

@section('content')
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-center align-items-center">
                        <h4 class="card-title"><i class="feather icon-globe"></i> Countries</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>ISO</th>
                                        <th>Name</th>
                                        <th>Currency</th>
                                        <th>Calling Codes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($countries as $country)
                                    <tr>
                                        <td>{{ $country['iso'] }}</td>
                                        <td>
                                            <img src="{{ $country['flag'] }}" width="20px" class="mr-1">
                                            {{ $country['name']}}
                                        </td>
                                        <td>{{ $country['currency_name'].' [ '.$country['currency_code'].' ]' }}</td>
                                        <td>{{ json_encode($country['calling_codes']) }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ISO</th>
                                        <th>Name</th>
                                        <th>Currency</th>
                                        <th>Calling Codes</th>
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
        $('.zero-configuration').DataTable();
    </script>
@endpush
