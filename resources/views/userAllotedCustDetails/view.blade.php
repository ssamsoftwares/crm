@extends('layouts.main')

@push('page-title')
    <title>All Alloted Customer Report</title>
@endpush

@push('heading')
    {{ 'Alloted Customer Report' }}
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
                    <h5>{{ __('Alloted User Customer Details Status Wise') }}</h5>
                    <strong>{{ 'User Name' }} :</strong> <i class="text-primary">{{ auth()->user()->name }}
                    </i> &nbsp;&nbsp;&nbsp;

                    <strong>{{ 'Email' }} :</strong> <i class="text-primary">
                        {{ auth()->user()->email }} </i>
                </div>

                <!-- Search Filter -->

                <form action="{{ route('statusWiseShowCustomerList', ['status' => $status, 'search' => $search]) }}"
                    method="get">
                    <div class="row m-2">
                        <div class="col-lg-5">
                        </div>
                        <div class="col-lg-5">
                            <x-form.input name="search" label="" type="search" placeholder="Search....."
                                value="{{ $search }}" />
                        </div>

                        <div class="col-lg-2 mt-lg-4">
                            <input type="submit" class="btn btn-primary " value="Search">
                            <a href="{{ route('statusWiseShowCustomerList', ['status' => $status]) }}"
                                class="btn btn-secondary">Reset</a>
                        </div>

                    </div>
                </form>


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
   
@endpush
