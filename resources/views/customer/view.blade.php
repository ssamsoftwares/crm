@extends('layouts.main')

@push('page-title')
    <title>All Customer & Comments Details</title>
@endpush

@push('heading')
    {{ 'All Customer & Comments Details -' }} {{ $customer->name }}
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

            input[switch]+label:after {

                left: -22px;
                margin-left: 25px;

            }

            input[switch]+label {
                width: 80px !important;
            }


            input[switch=bool]:checked+label {
                background-color: #f32f53;
            }


            /* input[switch=bool]+label {
                        background-color: #f32f53;
                    } */
        </style>
    @endpush

    <x-status-message />

    {{-- Customer Profile details --}}
    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border border-secondary rounded">
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ 'Customer Profile' }}</h5>
                    @role('superadmin')
                        <a href="{{ route('customer.bulkUploadCustomerEdit', ['customer' => $customer->id]) }}"
                            class="float-end btn btn-info"><i class="fa fa-pencil-square"></i></a>
                    @endrole
                </div>

                <div class="card-body">
                    <div class="row mt-lg-4">
                        <div class="col-4">
                            <b>Customer Name :</b>
                            <span>
                                {{ $customer->name }}
                            </span>&nbsp;&nbsp;
                            <a href="javascript:void(0)" class="text-danger"
                                onclick="addCustomerName(<?= $customer->id ?>)"><i class="fa fa-plus"></i></a>
                        </div>

                        <div class="col-4">
                            <strong>Email:</strong>
                            <span>
                                {{ $customer->email }}
                            </span>
                        </div>

                        <div class="col-4">
                            <strong>Phone Number :</strong>
                            <span>
                                {{ $customer->phone_number }}
                            </span>&nbsp;&nbsp;
                            <a href="javascript:void(0)" class="text-danger"
                                onclick="addCustomerPhoneNumber(<?= $customer->id ?>)"><i class="fa fa-plus"></i></a>
                        </div>
                        <hr />

                        <div class="col-4 mt-2">
                            <strong>Allot User Name :</strong>
                            <span>
                                {{ isset($customer->user->name) ? $customer->user->name : 'Not alloted' }}
                            </span>
                        </div>

                        <div class="col-4">
                            <strong>Status :</strong>
                            <strong>{{ Str::ucfirst($customer->status) }}</strong>
                        </div>

                        <div class="col-4">
                            <strong>{{ 'Communication Medium :' }}</strong>
                            <strong>
                                {{ isset($customer->communication_medium) ? Str::ucfirst($customer->communication_medium) : 'No Medium' }}
                            </strong>
                        </div>
                        <hr>

                        <div class="col-4 mt-2">
                            <strong>Alloted Date :</strong>
                            <strong>
                                {{ isset($customer->alloted_date) ? Carbon\Carbon::createFromTimestamp(strtotime($customer->alloted_date))->format('d-M-Y H:i:s') : '' }}
                            </strong>
                        </div>

                        <div class="col-4">
                            <strong>Company Name :</strong>
                            <strong>
                                {{ $customer->company_name }}
                            </strong>
                        </div>

                        {{-- <div class="col-4 d-flex">
                            <strong>Project Details :</strong> &nbsp;&nbsp;&nbsp;
                            <div class="project_details switch-container">
                                <input type="checkbox" id="project_details" name="project_details" switch="bool"
                                    value="No" style="width:100px;"
                                    {{ $customer->project_details === 'No' || is_null($customer->project_details) ? 'checked' : '' }}
                                    onclick="openProjectDetailsModal()">
                                <label for="project_details" data-on-label="No" data-off-label="Yes"></label>
                            </div>
                        </div> --}}
                        {{-- <div class="project_details switch-container">
                            <input type="checkbox" id="project_details" name="project_details" switch="bool"
                                value="No" style="width:100px;"
                                {{ $customer->project_details === 'No' || is_null($customer->project_details) ? 'checked' : '' }}
                                onclick="openProjectDetailsModal()">
                            <label for="project_details" data-on-label="No" data-off-label="Yes"></label>
                        </div> --}}


                        {{-- <div class="col-4 d-flex w-25">
                            <strong>Project Details :</strong> &nbsp;&nbsp;&nbsp;
                            <select name="project_details" id="project_details" class="form-control project_details" onchange="handleProjectDetailsChange()">
                                <option value="No" {{ $customer->project_details === 'No' || is_null($customer->project_details) ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ $customer->project_details == $customer->project_details ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div> --}}

                        <div class="col-4 d-flex w-25">
                            <strong>Project Details :</strong> &nbsp;&nbsp;&nbsp;
                            <select name="project_details" id="project_details" class="form-control project_details"
                                onchange="handleProjectDetailsChange()">
                                <option value="No">No</option>
                                <option value="Yes" {{ $customer->project_details === 'Yes' ? 'selected' : '' }}>Yes
                                </option>
                            </select>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <strong class="card-header">{{ __('Show All Comment From -') }} <i class="text-primary">
                        {{ $customer->name }}</i> </strong>
                <div class="row m-1 mt-4 justify-content-end d-flex">

                    <div class="col-md-8">
                        <a href="{{ route('user.addComments', $customer->id) }}" class="btn btn-primary btn-sm m-4"><i
                                class="fa fa-plus"></i> Add Comment</a>
                    </div>

                    <div class="col-md-4">
                        <div class="col-lg-12">
                            <x-search.table-search action="{{ route('customer.bulkUploadCustomerView', $customer->id) }}"
                                method="get" name="search"
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}"
                                btnClass="search_btn" />
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Date' }}</th>
                                <th>{{ 'Comments' }}</th>
                                <th>{{ 'Comments By' }}</th>
                                <th>{{ 'Action' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $i = 1; @endphp
                            @foreach ($customer->comments as $com)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $com->created_at->format('d-M-Y') }}</td>
                                    <td>{!! wordwrap(strip_tags(Str::ucfirst($com->comments)), 70, "<br />\n", true) !!}
                                        <br>
                                    </td>

                                    <td>{{ isset($com->user->name) ? Str::ucfirst($com->user->name) : '' }} </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm"
                                            onclick="editComment(<?= $com->id ?>)">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $comments->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    {{-- Comment Edit Model --}}

    <div class="modal fade" id="commentEditModel" tabindex="-1" aria-labelledby="commentEditModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.updateComments') }}" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="commentEditModelLabel">{{ 'Customer Details' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="customer_id" value="">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Comments <span class="text-danger">*</span></label>
                                <textarea id="elm1" name="comments"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Update Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Project Details Model --}}
    <div class="modal fade" id="projectDetailsModel" tabindex="-1" aria-labelledby="projectDetailsModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('customer.projectDetails') }}" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projectDetailsModelLabel">{{ 'Customer Project Details' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="user_id" value="{{ isset($customer->user->id) ?  $customer->user->id : '' }}">
                        <input type="hidden" name="project_details_status" id="project_details_status" value="">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Project Details <span class="text-danger">*</span></label>
                                <textarea id="project_details" name="project_details" class="form-control" required></textarea>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Customer Name Model --}}
    <div class="modal fade" id="addCustNameModel" tabindex="-1" aria-labelledby="addCustNameModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('customer.addcustNamePhoneNumber',$customer->id)}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCustNameModelLabel">{{ 'Add Name' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $customer->id }}">
                        <input type="hidden" name="user_id" value="{{ isset($customer->user->id) ?  $customer->user->id : '' }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="text" name="name" value="" class="form-control"
                                    placeholder="Enter Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Add Name</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Customer Phone Model --}}

    <div class="modal fade" id="addCustphoneNumberModel" tabindex="-1" aria-labelledby="addCustphoneNumberModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('customer.addcustNamePhoneNumber',$customer->id)}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCustphoneNumberModelLabel">{{ 'Add Phone Number' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $customer->id }}">
                        <input type="hidden" name="user_id" value="{{ isset($customer->user->id) ?  $customer->user->id : '' }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="text" name="phone_number" value="" class="form-control"
                                    placeholder="Enter Phone Number" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Add Phone Number</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function editComment(comment_id) {
            let url = `{{ url('customers-edit-comment/${comment_id}') }}`
            $.ajax({
                type: "GET",
                url: url,
                success: function(res) {
                    let model = $('#commentEditModel')
                    tinyMCE.get('elm1').setContent(res.data.comments)
                    $('input[name="id"]').val(res.data.id);
                    $('input[name="customer_id"]').val(res.data.customer_id);

                    $('.follow-up-status').val(res.data.fast_follow_up);
                    model.modal("show")
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
    {{-- <script>
        function openProjectDetailsModal() {
            var checkbox = document.getElementById('project_details');
            var hiddenInput = document.getElementById('project_details_status');

            if (checkbox.checked) {
                // Set the value to 'Yes' when checkbox is checked
                hiddenInput.value = 'Yes';
                $('#projectDetailsModel').modal('show');
            } else {
                // Set the value to 'No'
                hiddenInput.value = 'No';
            }
        }
    </script> --}}

    {{-- <script>
    function handleProjectDetailsChange() {
        var checkbox = document.getElementById('project_details');
        var hiddenInput = document.getElementById('project_details_status');

        if (checkbox.checked) {
            // Set the value to 'Yes' when checkbox is checked
            hiddenInput.value = 'Yes';

            // Initialize TinyMCE for the 'project_details' textarea
            tinymce.init({
                selector: '#project_details',
                height: 300,
                plugins: 'link code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
                menubar: false,
            });

            // Show the modal
            $('#projectDetailsModel').modal('show');
        } else {
            // Set the value to 'No'
            hiddenInput.value = 'No';

            // Destroy TinyMCE if it was previously initialized
            tinymce.get('project_details').destroy();
        }
    }
</script> --}}
    {{-- <script>
    tinymce.init({
        selector: '#project_details', // Your textarea ID
        height: 300,
        plugins: 'link code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
        menubar: false,
    });
</script> --}}

    <script>
        function handleProjectDetailsChange() {
            var select = document.getElementById('project_details');
            var hiddenInput = document.getElementById('project_details_status');

            if (select.value === 'Yes') {
                // Set the value to 'Yes' when 'Yes' option is selected
                hiddenInput.value = 'Yes';

                // Show the modal or perform any other action you need
                $('#projectDetailsModel').modal('show');
            } else {
                // Set the value to 'No' when 'No' option is selected
                hiddenInput.value = 'No';
            }
        }
    </script>


    {{-- Add Customer Name --}}
    <script>
        function addCustomerName() {
            $('#addCustNameModel').modal('show');
        }
    </script>

    <script>
        function addCustomerPhoneNumber() {
            $('#addCustphoneNumberModel').modal('show');
        }
    </script>
@endpush
