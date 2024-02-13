@extends('layouts.main')

@push('page-title')
    <title>{{ 'Dashboard' }}</title>
@endpush

@push('style')
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
        <div class="col-lg-12">
            <div class="card">

                <form action="{{ route('dashboard') }}" method="get">
                    <div class="row m-2 hihi">

                        @if (Auth::user()->hasRole('superadmin'))
                            <div class="col-lg-3 hihi">
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



                        <div class="col-lg-4 my-search">
                            <x-form.input name="search" label="Search" type="text" placeholder="Search....."
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" />
                        </div>

                        <div class="col-lg-2 mt-lg-4">
                            <input type="submit" class="btn btn-primary" value="Filter">
                            <a href="{{route('dashboard')}}" class="btn btn-secondary">Reset</a>
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
                                    @if (auth()->user()->hasRole('superadmin'))
                                        <th>{{ 'Allot User' }}</th>
                                    @endif

                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Email' }}</th>
                                    <th>{{ 'Phone' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Follow Up' }}</th>
                                    <th>{{ 'Comments' }}</th>
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
                                        <td>{{ ($total['customerTodayStatus']->currentPage() - 1) * $total['customerTodayStatus']->perPage() + $loop->index + 1 }}</td>

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

                                                    <option value="not Required">
                                                        Not Required</option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                                onclick="viewCustomerComment(<?= $cust->id ?>)">View Comment</a>
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

                                                <option value="no_required"
                                                    {{ $cust->status == 'no_required' ? 'selected' : '' }}>
                                                    No required</option>
                                            </select>
                                        </td>

                                        <td>
                                            <div class="action-btns text-center" role="group">
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

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $total['customerTodayStatus']->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div>

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

    {{-- View Comment --}}
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
@endpush
