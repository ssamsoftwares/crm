@extends('layouts.main')

@push('page-title')
    <title>All Customer Comments Details</title>
@endpush

@push('heading')
    {{ 'All Customer Comments Details -' }} {{ $customer->name }}
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
        </style>
    @endpush

    <x-status-message />

    {{-- Customer Profile details --}}
    <a href="{{ route('customers') }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Show All Comment') }}</h5>
                    <strong>{{ 'Name' }} :</strong> <i class="text-primary">
                        {{ $customer->name }}</i> &nbsp;&nbsp;&nbsp;

                    <strong>{{ 'Company Name ' }} :</strong> <i class="text-primary">
                        {{ $customer->company_name }}</i>
                </div>

                <div class="row m-1 mt-4 justify-content-end d-flex">

                    <div class="col-md-8">
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm m-4"
                            onclick="addCustomerComment(<?= $customer->id ?>)"><i class="fa fa-plus"></i> Add Comment</a>

                        <a href="javascript:void(0)" class="btn btn-success btn-sm m-4"
                            onclick="projectDetailsData(<?= $customer->id ?>)"> Add/Edit Project Details <i
                                class="fa fa-eye"></i> </a>
                    </div>

                    <div class="col-md-4">

                            <x-search.table-search action="{{ route('customer.customerAllComment', $customer->id) }}"
                                method="get" name="search"
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
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
                                @foreach ($comments as $com)
                                    <tr>
                                        <td>{{ ($comments->perPage() * ($comments->currentPage() - 1)) + $loop->index + 1 }}</td>

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
                    </div>
                    {{ $comments->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    {{-- Comment Add Model --}}
    <div class="modal fade" id="addCommentModel" tabindex="-1" aria-labelledby="addCommentModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.storeComments') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addCommentModelLabel">{{ 'Add Comment' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Comments <span class="text-danger">*</span></label>
                                {{-- <textarea id="elm1" name="comments"></textarea> --}}
                                <textarea name="comments" id="" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Add Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                                {{-- <textarea id="elm1" name="comments"></textarea> --}}
                                <textarea id="editCommentId" name="comments" class="form-control"></textarea>
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
                <form action="{{ route('customer.customerProjectDetailsAddEdit', $customer->id) }}" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projectDetailsModelLabel">{{ 'Customer Project Details' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value=" {{ isset($customer->id) ? $customer->id : '' }}">
                        <input type="hidden" name="user_id"
                            value="{{ isset($customer->user->id) ? $customer->user->id : '' }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Project Details <span class="text-danger">*</span></label>
                                <textarea id="project_details" name="project_details" class="form-control" required>{!! !empty($customer->project_details) ? $customer->project_details : '' !!}
                                </textarea>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    {{-- Add Comment --}}
    <script>
        function addCustomerComment() {
            $('#addCommentModel').modal('show');
        }
    </script>

    {{-- Edit Comment --}}
    <script>
        function editComment(comment_id) {
            let url = `{{ url('customers-edit-comment/${comment_id}') }}`
            $.ajax({
                type: "GET",
                url: url,
                success: function(res) {
                    let model = $('#commentEditModel')
                    // tinyMCE.get('elm1').setContent(res.data.comments)
                    $('textarea[name="comments"]').val(stripHtmlTags(res.data.comments));
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

        function stripHtmlTags(html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            return doc.body.textContent || "";
        }
    </script>

    {{-- Project Details Script --}}
    <script>
        function projectDetailsData() {
            $('#projectDetailsModel').modal('show');
        }
    </script>
@endpush
