@extends('layouts.main')

@push('page-title')
    <title> List of Alloted All Customer</title>
@endpush

@push('heading')
    {{ 'List of Alloted All Customer' }}
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

                <form action="{{ route('user.customersList') }}" method="get">
                    <div class="row m-2">
                        <div class="col-3">
                            <x-form.select label="Status" chooseFileComment="All" name="customer_status" id="customer_status"
                                :options="[
                                    'today' => 'Today',
                                    'high' => 'High',
                                    'medium' => 'Medium',
                                    'low' => 'Low',
                                ]" :selected="isset($_REQUEST['customer_status']) ? $_REQUEST['customer_status'] : ''" />
                        </div>

                        <div class="col-3">
                            <x-form.select label="Follow Up" chooseFileComment="All" name="follow_up" id="follow_up"
                                :options="[
                                    'npc' => 'NPC',
                                    'oon' => 'OON',
                                ]" :selected="isset($_REQUEST['follow_up']) ? $_REQUEST['follow_up'] : ''" />
                        </div>

                        <div class="col-4">
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
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Name' }}</th>
                                <th>{{ 'Email' }}</th>
                                <th>{{ 'Phone' }}</th>
                                <th>{{ 'Company Name' }}</th>
                                <th>{{ 'Follow Up' }}</th>
                                <th>{{ 'Status' }}</th>
                                <th>{{ 'Comments' }}</th>
                                <th>{{ 'Action' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($customers as $cust)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $cust->name }}</td>
                                    <td>{{ $cust->email }}</td>
                                    <td>{{ $cust->phone_number }}</td>
                                    <td>{{ $cust->company_name }}</td>

                                    <td>
                                        <select class="form-select follow-up-status" data-customer-id="{{ $cust->id }}">
                                            <option value="npc" {{ $cust->follow_up == 'npc' ? 'selected' : '' }}>NPC</option>
                                            <option value="oon" {{ $cust->follow_up == 'oon' ? 'selected' : '' }}>OON</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select class="form-select customer-status"
                                            data-customerStatus-id="{{ $cust->id }}">
                                            <option value="today" {{ $cust->status == 'today' ? 'selected' : '' }}>
                                                Today</option>
                                            <option value="high" {{ $cust->status == 'high' ? 'selected' : '' }}>
                                                High</option>

                                            <option value="medium" {{ $cust->status == 'medium' ? 'selected' : '' }}>
                                                Medium</option>

                                            <option value="low" {{ $cust->status == 'low' ? 'selected' : '' }}>
                                                Low</option>
                                        </select>
                                    </td>

                                    <td>
                                        <div class="action-btns text-center" role="group">
                                            <a href="{{route('user.addComments',$cust->id)}}" class="btn btn-primary btn-sm">Add</a>
                                            <a href="{{route('user.viewAllComments',$cust->id)}}" class="btn btn-info btn-sm">View/Edit</a>
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}" class="btn btn-warning btn-sm">{{'Customer Profile'}}</a>
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

<script>
    $(document).ready(function() {
        function updateStatus(url, idKey, statusKey, statusType) {
            return function() {
                var itemId = $(this).data(idKey);
                var selectedStatus = $(this).val();
                $.ajax({
                    type: 'PATCH',
                    url: url + '/' + itemId,
                    data: {
                        [statusKey]: selectedStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            console.log(statusType + ' updated successfully');
                        } else {
                            console.error('Failed to update ' + statusType + ':', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax request failed:', error);
                    }
                });
            };
        }

        // Follow-up status change
        $('.follow-up-status').change(updateStatus('/update-follow-up-status', 'customer-id', 'follow_up_status', 'Follow-up Status'));

        // Customer status change
        $('.customer-status').change(updateStatus('/update-customer-status', 'customerstatus-id', 'customer_status', 'Customer Status'));
    });
</script>

@endpush
