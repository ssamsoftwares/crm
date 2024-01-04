@extends('layouts.main')
@push('page-title')
    <title>{{ 'Customer Profile - ' }} {{ $customer->name }}</title>
@endpush

@push('heading')
    {{ 'Customer Profile -' }} {{ $customer->name }}
@endpush

@push('heading-right')
@endpush

@section('content')
    {{-- Customer Profile details --}}
    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border border-secondary rounded">
                <h5 class="card-header">{{ 'Customer Profile' }}</h5>
                <div class="card-body">
                    <div class="row mt-lg-4">
                        <div class="col-4">
                            <b>Customer Name :</b>
                            <span>
                                {{ $customer->name }}
                            </span>
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
                            </span>
                        </div>
                        <hr />

                        <div class="col-4">
                            <strong>Allot User Name :</strong>
                            <span>
                                {{ isset($customer->user->name) ? $customer->user->name : 'Not alloted' }}
                            </span>
                        </div>

                        <div class="col-4">
                            <strong>Status :</strong>
                            @if ($customer->status == 'today')
                                <span class="btn btn-primary btn-sm">{{ 'Today' }}</span>
                            @elseif ($customer->status == 'high')
                                <span class="btn btn-success btn-sm">{{ 'High' }}</span>
                            @elseif ($customer->status == 'medium')
                                <span class="btn btn-warning btn-sm">{{ 'Medium' }}</span>
                            @else
                                <span class="btn btn-danger btn-sm">{{ 'Low' }}</span>
                            @endif
                        </div>

                        <div class="col-4">
                            <strong>Follow Up :</strong>
                            <span>
                                {{ isset($customer->follow_up) ? Str::upper($customer->follow_up) : 'No Follow Up'}}
                            </span>
                        </div>

                        <div class="col-8">
                            <strong>Company Name :</strong>
                            <strong>
                                {{ $customer->company_name }}
                            </strong>
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
                <div class="justify-content-end d-flex">
                    {{-- <x-search.table-search action="{{route('customer.bulkUploadCustomerView')}}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" /> --}}
                </div>
                <div class="card-body shadow-lg p-3 mb-5 bg-white rounded">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ '#' }}</th>
                                <th>{{ 'Date' }}</th>
                                <th>{{ 'Comments' }}</th>
                                <th>{{ 'Actions' }}</th>

                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($customer->comments as $comment)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $comment->created_at->format('d-M-Y') }}</td>
                                    <td>{!! wordwrap(strip_tags($comment->comments), 70, "<br />\n", true) !!}</td>
                                    {{-- <td>
                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                </td> --}}
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
