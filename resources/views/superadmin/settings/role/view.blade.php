@extends('layouts.main')
@push('page-title')
    <title>{{ 'Role - ' }} {{ $role->name }}</title>
@endpush

@push('heading')
    {{ 'Role Detail' }}
@endpush

@push('heading-right')
@endpush

@section('content')

    {{-- Role details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ 'Role Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Role :</span>
                        <span>
                            {{ $role->name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Permission :</span>
                        @if (!empty($rolePermissions))
                            @foreach ($rolePermissions as $v)
                                <label class="label label-success">{{ $v->name }},</label>
                            @endforeach
                        @endif
                    </h5>
                    <hr>
                </div>
            </div>
        </div>


    </div>

@endsection
