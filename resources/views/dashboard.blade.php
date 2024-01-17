@extends('layouts.main')

@push('page-title')
    <title>{{ 'Dashboard' }}</title>
@endpush

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

        @media screen and (max-width: 676px) and (min-width: 100px) {
            .hihi {
                display: flex;
                flex-direction: column;

            }

            .hihi select {
                width: 74vw;
            }

            .my-search {
                width: 80vw;
            }
        }

        @media screen and (max-width: 400px) and (min-width: 100px) {
            .table.dataTable>tbody>tr.child ul.dtr-details>li {
                word-wrap: pre-wrap !important;
            }
        }
    </style>
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

                <form action="{{ route('dashboard') }}" method="get">
                    <div class="row m-2 hihi">

                        @if (Auth::user()->hasRole('superadmin'))
                        <div class="col-3">
                            <label for="">Alloted User</label>
                            <select name="user" id="" class="form-control selectUsers">
                                <option value="">All</option>
                                <option value="-1" {{ isset($_REQUEST['user']) && $_REQUEST['user'] == -1 ? 'selected' : '' }}>Not Allot</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ isset($_REQUEST['user']) && $_REQUEST['user'] == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-3">
                            <x-form.select label="Communication Medium" chooseFileComment="All" name="communication_medium"
                                id="communication_medium" :options="[
                                    'phone' => 'Phone',
                                    'skype' => 'Skype',
                                    'whatsApp' => 'WhatsApp',
                                ]" :selected="isset($_REQUEST['communication_medium'])
                                    ? $_REQUEST['communication_medium']
                                    : ''" />
                        </div>

                        <div class="col-4 my-search">
                            <x-form.input name="search" label="Search" type="text" placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-2">
                            <input type="submit" class="btn btn-primary mt-lg-4" value="Filter">
                        </div>

                    </div>
                </form>


                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; white-space: pre-wrap !important;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                @if (auth()->user()->hasRole('superadmin'))
                                    <th>{{ 'Allot User' }}</th>
                                @endif

                                <th>{{ 'Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Company Name' }}</th>
                                <th>{{ 'Follow Up' }}</th>
                                <th>{{ 'Communication Medium' }}</th>
                                <th>{{ 'Status' }}</th>
                                <th>{{ 'Actions' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($total['customerTodayStatus'] as $cust)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    @if (auth()->user()->hasRole('superadmin'))
                                        <td>{{ isset($cust->user->name) ? $cust->user->name : 'Not Allot User' }}
                                        </td>
                                    @endif

                                    <td>{{ $cust->name }}</td>
                                    <td>{{ isset($cust->email) ? $cust->email : 'Not found email' }}</td>
                                    <td>{{ $cust->phone_number }}</td>
                                    <td>{{ isset($cust->company_name) ? Str::ucfirst($cust->company_name) : 'Not found company' }}
                                    </td>

                                    <td>
                                        <select class="form-select follow-up-status"
                                            data-customer-id="{{ $cust->id }}">
                                            <option value="" disabled selected>--Select followUp--</option>
                                            <option value="npc">
                                                NPC</option>
                                            <option value="oon">
                                                OON</option>

                                            <option value="busy">
                                                Busy</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select class="form-select communication-medium"
                                            data-custmedium-id="{{ $cust->id }}">
                                            <option value="" disabled selected>--Select medium--</option>
                                            <option value="phone"
                                                {{ $cust->communication_medium == 'phone' ? 'selected' : '' }}>
                                                Phone</option>
                                            <option value="skype"
                                                {{ $cust->communication_medium == 'skype' ? 'selected' : '' }}>
                                                Skype</option>

                                            <option value="whatsApp"
                                                {{ $cust->communication_medium == 'whatsApp' ? 'selected' : '' }}>
                                                WhatsApp</option>

                                        </select>
                                    </td>

                                    <td>
                                        <select class="form-select customer-status"
                                            data-customerStatus-id="{{ $cust->id }}">
                                            <option value="" disabled selected>--Select status--</option>
                                            <option value="today" {{ $cust->status == 'today' ? 'selected' : '' }}>
                                                Today</option>
                                            <option value="high" {{ $cust->status == 'high' ? 'selected' : '' }}>
                                                High</option>

                                            <option value="medium" {{ $cust->status == 'medium' ? 'selected' : '' }}>
                                                Medium</option>

                                            <option value="low" {{ $cust->status == 'low' ? 'selected' : '' }}>
                                                Low</option>

                                            <option value="no required"
                                                {{ $cust->status == 'no required' ? 'selected' : '' }}>
                                                No required</option>
                                        </select>
                                    </td>

                                    <td>
                                        @role('superadmin')
                                            <a href="{{ route('customer.bulkUploadCustomerEdit', ['customer' => $cust->id]) }}"
                                                class="btn btn-info waves-effect waves-light edit btn-sm">
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}"
                                                class="btn btn-warning btn-sm">{{ 'Customer Profile' }}</a>
                                        @endrole


                                        @role('user')
                                            <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}"
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


@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectUsers').select2();

            $('.customer-status').change(function() {
                var element = $(this);
                var customerId = element.data('customerstatus-id');
                console.log(customerId);
                var selectedStatus = element.val();

                $.ajax({
                    type: 'PATCH',
                    url: '/update-customer-status/' + customerId,
                    data: {
                        customer_status: selectedStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            console.log('Status updated successfully');
                        } else {
                            console.error('Failed to update status:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed:', error);
                    }
                });
            });
        });
    </script>


    {{-- Customer Communication Medium Change --}}
    <script>
        $(document).ready(function() {
            $('.selectUsers').select2();

            $('.communication-medium').change(function() {
                var element = $(this);
                var custId = element.data('custmedium-id');
                console.log(custId);
                var selectedStatus = element.val();

                $.ajax({
                    type: 'PATCH',
                    url: '/update-communication-medium/' + custId,
                    data: {
                        communication_medium: selectedStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            console.log(' Communication medium updated successfully');
                        } else {
                            console.error('Failed to update medium:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed:', error);
                    }
                });
            });
        });
    </script>


    {{-- Update Fast FollowUp Status --}}
    <script>
        $(document).ready(function() {
            function updateFollowUpStatus(customerId, followUpStatus) {
                $.ajax({
                    type: 'POST',
                    url: '/update-follow-up-status/' + customerId,
                    data: {
                        follow_up_status: followUpStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            console.log('Follow-up status updated successfully');
                        } else {
                            console.error('Failed to update follow-up status:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed:', error);
                    }
                });
            }

            // Follow-up status change
            $('.follow-up-status').change(function() {
                var customerId = $(this).data('customer-id');
                var followUpStatus = $(this).val();
                updateFollowUpStatus(customerId, followUpStatus);
            });
        });
    </script>
@endpush
