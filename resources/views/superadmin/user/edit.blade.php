@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit User')}}</title>
@endpush

@push('heading')
{{ __('Edit User') }}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{route('users.update',$user->id)}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <h4 class="card-title mb-3">{{__('User Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-6">
                           <x-form.input name="name" label="Name" :value="$user->name" />
                        </div>
                        <div class="col-lg-6">
                            <x-form.input name="email" label="Email Address" :value="$user->email" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <x-form.input name="password" label="Passsword" type="password"/>
                        </div>

                        <div class="col-lg-6">
                            <x-form.input name="confirm-password" label="Confirm Password" type="password"/>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Update User')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
