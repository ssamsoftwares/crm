@extends('layouts.main')

@push('page-title')
    <title>{{ __('Edit uploaded Customer') }}</title>
@endpush

@push('heading')
    {{ __('Edit Customer') }} : {{ $customer->name }}
@endpush

@section('content')
    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('customer.bulkUploadCustomerUpdate', [$customer->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" :value="$customer->id">
                        <h4 class="card-title mb-3">{{ __('Personal Details') }}</h4>

                        <div class="row">
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
                            <div class="col-lg-8">
                                <label for="logo">Image</label>
                                <input type="file" accept="image/*" name="image" id="image" class="form-control"
                                    onchange="loadFile(event)">
                            </div>
                            <div class="col-lg-4 mt-lg-2">
                                @if (!empty($customer->image))
                                    <img src="{{ asset($customer->image) }}" id="output" alt="" width="50"
                                        height="50">
                                @else
                                    <img src="" id="output" alt="" width="50" height="50">
                                @endif
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

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src)
        }
    };
</script>
@endpush
