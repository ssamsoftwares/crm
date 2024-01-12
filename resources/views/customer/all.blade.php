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

                <form action="{{ route('customers') }}" method="get">
                    <div class="row m-2">
                        @if (Auth::user()->hasRole('superadmin'))
                        <div class="col-3">
                            <label for="">Alloted User</label>
                            <select name="user" id="" class="form-control">
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

                        <div class="col-2">
                            <x-form.select label="Status" chooseFileComment="All" name="customer_status"
                                id="customer_status" :options="[
                                    'today' => 'Today',
                                    'high' => 'High',
                                    'medium' => 'Medium',
                                    'low' => 'Low',
                                    'no required' => 'No required',
                                ]" :selected="isset($_REQUEST['customer_status']) ? $_REQUEST['customer_status'] : ''" />
                        </div>

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

                        <div class="col-3">
                            <x-form.input name="search" label="Search" type="text" placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-1">
                            <input type="submit" class="btn btn-primary mt-lg-4" value="Filter">
                        </div>

                    </div>
                </form>

                @if (Auth::user()->hasRole('superadmin'))
                    <div class="row m-1">
                        <div class="col-md-6">
                            <form action="{{ route('assignCustomer') }}" method="post" id="assignCustomerForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-8 mt-4">
                                        <select class="selectUsers form-control" name="user_id">
                                            <option value="">-- Select User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-lg-4 mt-4">
                                        <input type="hidden" name="c_ids" id="c_ids">
                                        <button type="button" id="allotCustomersFromUser" class="btn btn-info btn-sm">
                                            Allot
                                            Customer</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    @if (Auth::user()->hasRole('superadmin'))
                                        <th>
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </th>
                                    @endif
                                    <th>{{ '#' }}</th>
                                    @if (Auth::user()->hasRole('superadmin'))
                                        <th>{{ 'Allot User' }}</th>
                                    @endif
                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Phone' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Fast Follow Up' }}</th>
                                    <th> {{ 'Communication' }}<br> {{ 'Medium' }}</th>
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
                                        @if (Auth::user()->hasRole('superadmin'))
                                            <td>
                                                <input type="checkbox" class="form-check-input" name="selected_customers[]"
                                                    value="{{ $cust->id }}"
                                                    @if ($cust->user_id != null) checked disabled @endif>
                                            </td>
                                        @endif
                                        <td>{{ $i++ }}</td>
                                        @if (Auth::user()->hasRole('superadmin'))
                                            <td class="text-danger">
                                                {{ isset($cust->user->name) ? $cust->user->name : 'Not Allot' }}</td>
                                        @endif
                                        <td>{{ $cust->name }}</td>

                                        <td>{{ $cust->phone_number }}</td>
                                        <td>{{ isset($cust->company_name) ? Str::ucfirst($cust->company_name) : '' }}</td>

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
                                                data-customerstatus-id="{{ $cust->id }}">
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
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="{{route('customer.customerAllComment',$cust->id)}}" class="btn btn-warning btn-sm">Comment</a>


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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Allot multiple customer to user --}}
    <script>
        $(document).ready(function() {
            $('.selectUsers').select2();

            $('#allotCustomersFromUser').on('click', function(e) {
                var allVals = [];
                $('input[name="selected_customers[]"]:checked:not(:disabled)').each(function() {
                    allVals.push($(this).val());
                });
                if (allVals.length <= 0) {
                    e.preventDefault();
                    alert('Please select at least one customer.');
                    return false;
                }
                if ($('.selectUsers').val() == "") {
                    alert('Please select user.');
                    return false;
                }
                $('#c_ids').val(allVals)

                $('#assignCustomerForm').submit();
            });
            $('#selectAll').on('change', function() {
                $('input[name="selected_customers[]"]:not(:disabled)').prop('checked', $(this).prop(
                    'checked'));
            });
        });
    </script>

    {{-- Customer Status Change --}}
    <script>
        $(document).ready(function() {
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
