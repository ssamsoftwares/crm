@extends('layouts.main')

@push('page-title')
    <title>All Customer</title>
@endpush

@push('heading')
    {{ 'All Customer' }}
@endpush

@section('content')
    @push('style')

    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <form action="{{ route('customers') }}" method="get">
                    <div class="row m-2">
                        @if (Auth::user()->hasRole('superadmin'))
                            <div class="col-lg-3">
                                <label for="">Alloted User</label>
                                <select name="user" id="" class="form-control userFilter">
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

                        <div class="col-lg-2">
                            @php
                                $customerStatus = config('constant.customer_status');
                            @endphp
                            <x-form.select label="Status" chooseFileComment="All" name="customer_status"
                                id="customer_status" :options="$customerStatus" :selected="isset($_REQUEST['customer_status']) ? $_REQUEST['customer_status'] : ''" />
                        </div>


                        <div class="col-lg-3">
                            <x-form.input name="search" label="Search" type="text" placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-lg-2">
                            <x-form.input name="date" label="Date" type="date"
                                value="{{ isset($_REQUEST['date']) ? $_REQUEST['date'] : '' }}" />
                        </div>

                        <div class="col-lg-2 mt-lg-4">
                            <input type="submit" class="btn btn-primary" value="Filter">
                            <a href="{{route('customers')}}" class="btn btn-secondary">Reset</a>
                        </div>

                    </div>
                </form>

                @if (Auth::user()->hasRole('superadmin'))
                    <div class="row m-1">
                        <div class="col-lg-6">
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
                                    <th>{{'Date'}}</th>
                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Phone' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Fast Follow Up' }}</th>
                                    <th>{{ 'Comments' }}</th>
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

                                        <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->index + 1 }}</td>

                                        @if (Auth::user()->hasRole('superadmin'))
                                            <td class="text-danger">
                                                {{ isset($cust->user->name) ? $cust->user->name : 'Not Allot' }}</td>
                                        @endif
                                        <td>{{ $cust->created_at }}</td>
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
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                                onclick="viewCustomerComment(<?= $cust->id ?>)">View Comment</a>
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

                                                <option value="no_required"
                                                    {{ $cust->status == 'no_required' ? 'selected' : '' }}>
                                                    No required</option>
                                            </select>
                                        </td>

                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{ route('customer.bulkUploadCustomerView', $cust->id) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="{{ route('customer.customerAllComment', $cust->id) }}"
                                                    class="btn btn-warning btn-sm">Comment</a>
                                            </div>

                                            <div class="mt-2">
                                                {{-- <strong>{{ 'Last Updated' }} :</strong> --}}
                                                <span>{{ $cust->last_updated }}</span>
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

    {{-- Cooment Model Form --}}

    <div class="modal fade" id="commentViewModel" tabindex="-1" aria-labelledby="commentViewModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-dark" id="commentViewModelLabel">{{ 'View Comments' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="commentList"></ul>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('script')

    {{-- View Customer Comment --}}

    <script>
        function viewCustomerComment(customerId) {
            $.ajax({
                url: '/getCustomerComment/' + customerId,
                type: 'GET',
                success: function(response) {
                    var comments = response.comments;
                    var commentList = $('#commentList');
                    commentList.empty();

                    if (comments.length > 0) {
                        comments.forEach(function(comment) {
                            var formattedDate = new Date(comment.created_at).toLocaleDateString(
                                'en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });

                            var listItem = $('<li></li>');
                            var commentContainer = $('<div></div>');
                            var commentText = $('<strong>' + comment.comments + '</strong>');
                            var dateText = $('<span style="color: red; font-weight: bold;">' + ' - ' +
                                formattedDate + '</span>')

                            commentContainer.append(commentText);
                            commentContainer.append(dateText);
                            listItem.append(commentContainer);
                            commentList.append(listItem);
                        });
                    } else {
                        commentList.append('<strong>No comments available.</strong>');
                    }

                    // Show the modal
                    $('#commentViewModel').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>

    {{-- Allot multiple customer to user --}}
    <script>
        $(document).ready(function() {
            $('.selectUsers').select2();
            $('.userFilter').select2();

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
