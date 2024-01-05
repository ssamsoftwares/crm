@extends('layouts.main')

@push('page-title')
    <title>{{ __('Edit uploaded Customer') }}</title>
@endpush

@push('heading')
    {{ __('Edit Customer') }} : {{ $customer->name }}
@endpush
@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <form method="post" action="{{ route('customer.bulkUploadCustomerUpdate', [$customer->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$customer->id}}">
                        <h4 class="card-title mb-3">{{ __('Personal Details') }}</h4>


                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Allot User</label>
                                <select class="selectUsers form-control" name="user_id">
                                    <option value="">-- Select User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $customer->user_id == $user->id  ? 'selected' : '' }} >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <x-form.input name="name" label="Customer Name" :value="$customer->name" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="email" label="Email" :value="$customer->email" />
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.input name="phone_number" label="Phone Number" :value="$customer->phone_number" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.input name="company_name" label="Company Name" :value="$customer->company_name" />
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
                                    ]" :selected="$customer->status" />
                            </div>

                            <div class="col-lg-6">
                                <x-form.select name="follow_up" label="Follow Up" chooseFileComment="--Select Follow Up--"
                                    :options="[
                                       'npc' => 'NPC',
                                        'oon' => 'OON'
                                    ]" :selected="$customer->follow_up" />
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary mt-2" type="submit">{{ __('Update Customer') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.selectUsers').select2();
});
</script>
@endpush

