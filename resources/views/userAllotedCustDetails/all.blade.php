@extends('layouts.main')

@push('page-title')
    <title>All Customer Report</title>
@endpush

@push('heading')
    {{ 'All Customer Report' }}
@endpush

@section('content')
    @push('style')
     
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'No. of Alloted customer count' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>{{ 'Today' }}
                                    </td>
                                    <td>{{ $total['customerTodayStatusCount'] }}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'today'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>
                                <!-- ...High -->
                                <tr>
                                    <td>{{ 'High' }}</td>
                                    <td>{{ $total['customerHighStatusCount'] }}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'high'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>

                                <!-- ...Medium -->
                                <tr>
                                    <td>{{ 'Medium' }}</td>
                                    <td>{{ $total['customerMediumStatusCount']}}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'medium'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>

                                <!-- ...Low -->
                                <tr>
                                    <td>{{ 'Low' }}</td>
                                    <td>{{ $total['customerLowStatusCount']}}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'low'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>


                                <!-- ...No required -->
                                <tr>
                                    <td>{{ 'No required' }}</td>
                                    <td>{{ $total['customerNoReqStatusCount'] }}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'no_required'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>

                                <!-- ...No Status -->
                                <tr>
                                    <td>{{ 'No Status' }}</td>
                                    <td>{{ $total['customerNoStatusCount'] }}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'no_status'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')

@endpush
