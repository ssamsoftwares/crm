@extends('layouts.main')

@push('page-title')
    <title>All Alloted Customer Report</title>
@endpush

@push('heading')
    {{ 'Alloted Customer Report' }}
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
    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h5>{{ __('Alloted User Customer Details Status Wise') }}</h5>
                    <strong>{{ 'User Name' }} :</strong> <i class="text-primary">{{ auth()->user()->name }}
                    </i> &nbsp;&nbsp;&nbsp;

                    <strong>{{ 'Email' }} :</strong> <i class="text-primary">
                        {{ auth()->user()->email }} </i>
                </div>

                <!-- Search Filter -->
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{route('statusWiseShowCustomerList')}}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ '#' }}</th>
                                    <th>{{ 'Customer Name' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Phone Number' }}</th>
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($customers as $cust)
                                    <tr>
                                        <td>{{ $customers->perPage() * ($customers->currentPage() - 1) + $loop->index + 1 }}
                                        </td>
                                        <td>{{ $cust->name }}</td>
                                        <td>{{ $cust->company_name }}</td>
                                        <td>{{ $cust->phone_number }}</td>
                                        <td>{{ Str::ucfirst($cust->status) }}</td>
                                        <td>
                                            <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}"
                                                class="btn btn-warning btn-sm">{{ 'Customer Profile' }}</a>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Customers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $customers->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
