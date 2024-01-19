@extends('layouts.main')

@push('page-title')
    <title>All User Report</title>
@endpush

@push('heading')
    {{ 'All User Report' }}
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Show All User Alloted Customer List') }}</h5>
                    <strong>{{ 'User Name' }} :</strong> <i class="text-primary">{{ $user->name }}
                    </i> &nbsp;&nbsp;&nbsp;

                    <strong>{{ 'Email' }} :</strong> <i class="text-primary">
                        {{ $user->email }} </i>
                </div>

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
                                    <th>{{ '#' }}</th>
                                    <th>{{ 'Customer Name' }}</th>
                                    <th>{{ 'Company Name' }}</th>
                                    <th>{{ 'Phone Number' }}</th>
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
                                        <td>{{ ($customers->perPage() * ($customers->currentPage() - 1)) + $loop->index + 1 }}</td>

                                        <td>{{ $cust->name }}</td>
                                        <td>{{ $cust->company_name }}</td>
                                        <td>{{ $cust->phone_number }}</td>
                                        <td>{{ Str::ucfirst($cust->status )}}</td>
                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{route('customer.bulkUploadCustomerView',$cust->id)}}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>
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
@endpush
