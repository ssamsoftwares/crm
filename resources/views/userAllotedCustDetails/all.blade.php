@extends('layouts.main')

@push('page-title')
    <title>All Customer Report</title>
@endpush

@push('heading')
    {{ 'All Customer Report' }}
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
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="get">
                    {{-- <div class="row m-2">
                        @if (Auth::user()->hasRole('superadmin'))
                            <div class="col-lg-3">
                                <label for="">Alloted User</label>
                                <select name="user" id="" class="form-control selectUsers">
                                    <option value="">All</option>
                                    <option value="-1"
                                        {{ isset($_REQUEST['user']) && $_REQUEST['user'] == -1 ? 'selected' : '' }}>Not
                                        Allot</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}"
                                            {{ isset($_REQUEST['user']) && $_REQUEST['user'] == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-lg-5">
                            <x-form.input name="search" label="Search" type="text" placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-lg-1 mt-1">
                            <input type="submit" class="btn btn-primary mt-lg-4" value="Filter">
                        </div>

                    </div> --}}
                </form>


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
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'no required'])}}" class="btn btn-primary btn-sm">View Customers</a>
                                    </td>
                                </tr>

                                <!-- ...No Status -->
                                <tr>
                                    <td>{{ 'No Status' }}</td>
                                    <td>{{ $total['customerNoStatusCount'] }}</td>
                                    <td>
                                        <a href="{{route('statusWiseShowCustomerList', ['status' => 'no status'])}}" class="btn btn-primary btn-sm">View Customers</a>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
