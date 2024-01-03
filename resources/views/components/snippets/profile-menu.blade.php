@php
$user = auth()->user();
@endphp

@if($show == 'true' && !empty($user))
    {{-- Profile menu --}}
    <div class="dropdown d-inline-block user-dropdown">
        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{-- <img class="rounded-circle header-profile-user" src="assets images users avatar-1.jpg"
                alt="Header Avatar"> --}}
            @if($user->hasRole('superadmin') || $user->hasRole('user') )
                <span class="d-xl-inline-block ms-1">{{ $user->name }}</span>
            @else
                <span class="d-xl-inline-block ms-1">{{ session('student_name') }}</span>
            @endif
            <i class="mdi mdi-chevron-down d-xl-inline-block"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            @if($user->hasRole('superadmin') || $user->hasRole('user'))
                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
            @else
                <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a>
            @endif
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout
            </a>
        </div>
    </div>
@endif
