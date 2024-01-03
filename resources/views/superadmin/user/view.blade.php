@extends('layouts.main')
@push('page-title')
    <title>{{ 'User - ' }} {{ $user->name }}</title>
@endpush

@push('heading')
    {{ 'User Detail' }}
@endpush

@push('heading-right')
@endpush

@section('content')

    {{-- User details --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <h5 class="card-header">{{ 'User Details' }}</h5>
                <div class="card-body">

                    <h5 class="card-title">
                        <span>Name :</span>
                        <span>
                            {{ $user->name }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Email :</span>
                        <span>
                            {{ $user->email }}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Password :</span>
                        <span>
                            {{ !empty($user->normal_password) ? $user->normal_password  : NULL}}
                        </span>
                    </h5>
                    <hr>

                    <h5 class="card-title">
                        <span>Role :</span>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $v)
                                    <label class="badge bg-success">{{ $v }}</label>
                                @endforeach
                            @endif

                    </h5>
                    <hr>
                </div>
            </div>
        </div>


    </div>

@endsection
