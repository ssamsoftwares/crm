@extends('layouts.main')

@push('page-title')
    <title>{{ 'Dashboard' }}</title>
@endpush

@push('heading')
    {{ 'Dashboard' }}
@endpush

@section('content')
    {{-- quick info --}}
    @role('superadmin')
        <div class="row">
            <x-design.card heading="Total Users" value="{{ $total['users'] }}" icon="mdi-account-convert" desc="Users" />
            <x-design.card heading="Total Customers" value="{{ $total['customers'] }}" icon="mdi-account-convert"
                desc="Customers" />
        </div>
    @endrole

    @role('user')
        <div class="row">
            <x-design.card heading="Total Customers" value="{{ $total['allotCustomerUser'] }}" icon="mdi-account-convert"
                desc="Customers" />
        </div>
    @endrole

    <h4 class="card-title mt-4 mb-4">{{ __('This is the list of all customers whose status is today ') }}</h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('dashboard') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                @if (auth()->user()->hasRole('superadmin'))
                                    <th>{{ 'Allot User' }}</th>
                                @endif

                                <th>{{ 'Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Follow Up' }}</th>
                                <th>{{ 'Status' }}</th>
                                <th>{{ 'Actions' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($total['customerTodayStatus'] as $custStatus)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    @if (auth()->user()->hasRole('superadmin'))
                                        <td>{{ isset($custStatus->user->name) ? $custStatus->user->name : 'Not Allot User' }}
                                        </td>
                                    @endif

                                    <td>{{ $custStatus->name }}</td>
                                    <td>{{ $custStatus->email }}</td>
                                    <td>{{ $custStatus->phone_number }}</td>
                                    <td>{{ Str::upper($custStatus->follow_up) }}</td>
                                    <td>{{ Str::ucfirst($custStatus->status) }}</td>

                                    <td>
                                        @role('superadmin')
                                            <a href="{{ route('customer.bulkUploadCustomerEdit', ['customer' => $custStatus->id]) }}"
                                                class="btn btn-info waves-effect waves-light edit btn-sm">
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            <a href="{{ route('customer.bulkUploadCustomerView', $custStatus->id) }}"
                                                class="btn btn-warning btn-sm">{{ 'Customer Profile' }}</a>
                                        @endrole


                                        @role('user')
                                            <a href="{{ route('customer.bulkUploadCustomerView', $custStatus->id) }}"
                                                class="btn btn-warning btn-sm">{{ 'Customer Profile' }}</a>
                                        @endrole


                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $total['customerTodayStatus']->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
