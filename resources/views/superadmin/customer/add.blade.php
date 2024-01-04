@extends('layouts.main')

@push('page-title')
    <title>{{ __('Add Customer') }}</title>
@endpush

@push('heading')
    {{ __('Add Customer') }}
@endpush

@section('content')
    <x-status-message />

    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
        <i class="fa fa-backward"></i> Back
    </a>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h4 class="card-title mb-3">{{ __('Personal Details') }}</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="name" label="Customer Name" />
                            </div>
                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email" />
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="phone_number" label="Phone Number" />
                            </div>
                            <div class="col-lg-6">
                                <x-form.input name="company_name" label="Company Name" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.select name="customer_status" label="Status" chooseFileComment="--Select Status--"
                                    :options="[
                                        'today' => 'Today',
                                        'high' => 'High',
                                        'medium' => 'Medium',
                                        'low' => 'Low',
                                    ]" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.select name="follow_up" label="Follow Up" chooseFileComment="--Select Follow Up--"
                                    :options="[
                                        'npc' => 'NPC',
                                        'oon' => 'OON'
                                    ]" />
                            </div>

                        </div>

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Add Customer') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
