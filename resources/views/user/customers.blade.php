@extends('layouts.main')

@push('page-title')
    <title>All Customer</title>
@endpush

@push('heading')
    {{ 'All Customer' }}
@endpush

@section('content')
    @push('style')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .ri-eye-line:before {
                content: "\ec95";
                position: absolute;
                left: 13px;
                top: 5px;
            }

            a.btn.btn-primary.waves-effect.waves-light.view {
                width: 41px;
                height: 32px;
            }

            .action-btns.text-center {
                display: flex;
                gap: 10px;
            }

            .ri-pencil-line:before {
                content: "\ef8c";
                position: absolute;
                left: 13px;
                top: 5px;
            }

            a.btn.btn-info.waves-effect.waves-light.edit {
                width: 41px;
                height: 32px;
            }

            table.dataTable>tbody>tr.child ul.dtr-details>li {
                white-space: nowrap !important;
            }
        </style>
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row m-1 mt-4 justify-content-end d-flex">

                    <div class="col-md-8">

                    </div>

                    <div class="col-md-4">
                        <div class="col-lg-12">
                            <x-search.table-search action="{{ route('user.customersList') }}" method="get" name="search"
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ 'Customer Photo' }}</th>
                                <th>{{ 'Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Company Name' }}</th>
                                <th>{{ 'Comments' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($customers as $cust)
                                <tr>

                                    <td>
                                        @if (!empty($stu->image))
                                            <img src="{{ asset($stu->image) }}" alt="studentImg" width="85">
                                        @else
                                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ799fyQRixe5xOmxYZc3kAy6wgXGO-GHpHSA&usqp=CAU"
                                                alt="" width="85">
                                        @endif
                                    </td>
                                    <td>{{ $cust->name }}</td>
                                    <td>{{ $cust->email }}</td>
                                    <td>{{ $cust->phone_number }}</td>
                                    <td>{{ $cust->company_name }}</td>
                                    <td>
                                        <div class="action-btns text-center" role="group">
                                            <a href="{{route('user.addComments',$cust->id)}}" class="btn btn-success btn-sm">Add/Edit</a>
                                            <a href="{{route('user.viewAllComments',$cust->id)}}" class="btn btn-info btn-sm">View</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $customers->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')

@endpush
