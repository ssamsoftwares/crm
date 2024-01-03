@extends('layouts.main')

@push('page-title')
<title>{{__('Edit Profile')}}</title>
@endpush

@push('heading')
{{ __('Edit Profile') }}
@endpush

@section('content')

<x-status-message />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    <h4 class="card-title mb-3">Personal Details</h4>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="name" label="Name" :value="$user->name"/>
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address" :value="$user->email"/>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit">{{__('Update Details')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('profile.update_password') }}">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Change Password')}}</h4>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="current_password" label="Old Password" type="password"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="password" label="New Password" type="password"/>
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="password_confirmation" label="Confirm Password" type="password" />
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit">{{__('Change Password')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
