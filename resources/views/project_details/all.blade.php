@extends('layouts.main')

@push('page-title')
    <title>All Customer Project Details</title>
@endpush

@push('heading')
    {{ 'All Customer Project Details' }}
@endpush

@section('content')
    @push('style')
       
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('customer.projectDetailsList') }}" method="get">
                    <div class="row m-2">
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

                        <div class="col-lg-2 mt-1 mt-lg-4">
                            <input type="submit" class="btn btn-primary" value="Filter">
                            <a href="{{ route('customer.projectDetailsList') }}" class="btn btn-secondary">Reset</a>
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
                                    <th>{{ 'Project Details' }}</th>
                                    @if (Auth::user()->hasRole('superadmin'))
                                        <th>{{ 'Allot User' }}</th>
                                    @endif
                                    <th>{{ 'Customer Name' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($paginatedCustomers as $cust)
                                    <tr>

                                        <td>
                                            {{ ($paginatedCustomers->currentPage() - 1) * $paginatedCustomers->perPage() + $loop->index + 1 }}
                                        </td>

                                        <td>{!! wordwrap(strip_tags(Str::ucfirst($cust->project_details)), 80, "<br />\n", true) !!}
                                            <br>
                                        </td>
                                        @if (Auth::user()->hasRole('superadmin'))
                                            <td class="text-danger">
                                                {{ isset($cust->user->name) ? $cust->user->name : 'Not Allot' }}</td>
                                        @endif

                                        <td>{{ isset($cust->name) ? $cust->name : '' }}</td>

                                        <td>{{ isset($cust->company_name) ? Str::ucfirst($cust->company_name) : '' }}</td>

                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-info btn-sm"
                                                onclick="editprojectdetails(<?= $cust->id ?>)"> <i
                                                    class="fa fa-pencil-square"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $paginatedCustomers->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    {{-- Project Details Edit Model --}}

    <div class="modal fade" id="projectDetailsEditModel" tabindex="-1" aria-labelledby="projectDetailsEditModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('customer.updateProjectDetails') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projectDetailsEditModelLabel">
                            {{ 'Edit Customer Project Details' }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="user_id" value="">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Project Details Comment <span class="text-danger">*</span></label>
                                <textarea name="project_details" id="" cols="30" rows="10" class="form-control"></textarea>
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

    <script>
        $(document).ready(function() {
            $('.selectUsers').select2();
        });

        function editprojectdetails(projectdetails_id) {
            let url = `{{ url('edit-project-details/${projectdetails_id}') }}`
            console.log("Requesting URL: ", url);
            $.ajax({
                type: "GET",
                url: url,
                success: function(res) {
                    console.log("res", res)
                    let model = $('#projectDetailsEditModel')
                    // tinyMCE.get('elm1').setContent(res.data.project_details_comment)
                    $('input[name="id"]').val(res.data.id);
                    $('input[name="id"]').val(res.data.id);
                    $('input[name="user_id"]').val(res.data.user_id);
                    $('textarea[name="project_details"]').val(stripHtmlTags(res.data.project_details));

                    model.modal("show");
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
@endpush
