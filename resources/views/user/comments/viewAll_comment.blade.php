@extends('layouts.main')

@push('page-title')
    <title>All Comments</title>
@endpush

@push('heading')
    {{ 'Customer All Comments -' }} {{ $customer->name }}
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

    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    @role('user')
        <a href="{{ route('user.customersList') }}" class="btn btn-info btn-sm m-1">
            Customers </a>
    @endrole

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row m-1 mt-4 justify-content-end d-flex">

                    <div class="col-md-8">

                    </div>

                    <div class="col-md-4">
                        <div class="col-lg-12">
                            <x-search.table-search action="{{ route('user.viewAllComments', $customer->id) }}"
                                method="get" name="search"
                                value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Comments' }}</th>
                                <th>{{ 'Comments By' }}</th>
                                <th>{{ 'Created at' }}</th>
                                <th>{{ 'Updated at' }}</th>
                                <th>{{ 'Action' }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $i = 1; @endphp
                            @foreach ($customer->comments as $com)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{!! wordwrap(strip_tags($com->comments), 70, "<br />\n", true) !!}
                                    <br>
                                    <b class="text-primary">Fast Follow Up :</b> {{isset($com->fast_follow_up) ? Str::upper($com->fast_follow_up) : ''}}
                                    </td>

                                    <td>{{ isset($com->user->name) ? Str::ucfirst($com->user->name) : '' }} </td>

                                    <td>{{ $com->created_at->format('d-M-Y') }}</td>
                                    <td>{{ $com->updated_at->format('d-M-Y') }}</td>
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

                            <div class="col-lg-12 mt-4">
                                <label for="">Follow Up</label>
                                <select class="form-select follow-up-status" name="fast_follow_up">
                                    <option value="npc">NPC</option>
                                    <option value="oon">OON</option>
                                </select>
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
@endpush
