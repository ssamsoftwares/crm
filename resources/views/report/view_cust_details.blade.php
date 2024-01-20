@extends('layouts.main')

@push('page-title')
    <title>All User Report</title>
@endpush

@push('heading')
    {{ 'All User Report' }}
@endpush

@section('content')
    @push('style')

    @endpush

    <x-status-message />
    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h5>{{ __('Show All User Alloted Customer List') }}</h5>
                    <strong>{{ 'User Name' }} :</strong> <i class="text-primary">{{ $user->name }}
                    </i> &nbsp;&nbsp;&nbsp;

                    <strong>{{ 'Email' }} :</strong> <i class="text-primary">
                        {{ $user->email }} </i>
                </div>

                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('userAllotedCustomerDetails',['userId' => $user->id]) }}" method="get" name="search"
                        value="{{$search}}" btnClass="search_btn" />
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
                                @foreach ($customers as $cust)
                                    <tr>
                                        <td>{{ ($customers->perPage() * ($customers->currentPage() - 1)) + $loop->index + 1 }}</td>

                                        <td>{{ $cust->name }}</td>
                                        <td>{{ $cust->company_name }}</td>
                                        <td>{{ $cust->phone_number }}</td>
                                        <td>{{ Str::ucfirst($cust->status )}}</td>
                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{route('customer.bulkUploadCustomerView',$cust->id)}}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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

@endpush
