@extends('layouts.main')

@push('page-title')
<title>{{ __('Edit Role')}}</title>
@endpush

@push('heading')
{{ 'Edit Role' }} : {{$role->name}}
@endpush

@section('content')

<x-status-message/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form method="post" action="{{ route('roles.update',[$role->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" id="" value="{{$role->id}}">
                    <h4 class="card-title mb-3">{{__(' Role Details')}}</h4>

                    <div class="row">
                        <div class="col-lg-12">
                           <x-form.input name="name" label="Role" :value="$role->name" :disabled="true" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">Permission</label>
                            @foreach($permission as $value)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $value->id }}" id="{{ $value->id }}"
                                    {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $value->id }}">
                                    {{ $value->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-primary mt-2" type="submit">{{__('update Role')}}</button>
                    </div>
                </form>
           </div>
        </div>
    </div>
</div>

@endsection
