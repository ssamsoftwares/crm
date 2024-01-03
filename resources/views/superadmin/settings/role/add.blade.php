@extends('layouts.main')

@push('page-title')
<title>{{ __('Add New Role')}}</title>
@endpush

@push('heading')
{{ __('Add New Role') }}
@endpush

@section('content')

<x-status-message/>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{route('roles.store')}}" enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3">{{__('Role Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-12">
                           <x-form.input name="name" label="Role"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">Permission</label>
                            @foreach($permission as $value)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $value->id }}" id="{{ $value->id }}" name="permission[]">
                                <label class="form-check-label" for="{{ $value->id }}">
                                    {{ $value->name }}
                                </label>
                            </div>
                            @endforeach

                            @error('permission')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('Add Role')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
