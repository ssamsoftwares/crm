<div class="vertical-menu">

    <div data-simplebar class="h-100">
        <!-- User details -->

        <div class="user-profile text-center mt-3">
            @if (auth()->check())
                @if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('user') || auth()->user()->hasRole('manager'))
                    <div class="mt-3">
                        <h4 class="font-size-16 mb-1">Hello {{ ucfirst(request()->user()->name) }}</h4>
                        <span class="text-muted">
                            <i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                            {{ ucfirst(request()->user()->role) }}
                        </span>
                    </div>
                @elseif (session()->has('student_name'))
                    <div class="mt-3">
                        <h4 class="font-size-16 mb-1">Hello {{ ucfirst(session('student_name')) }}</h4>
                        <span class="text-muted">

                        </span>
                    </div>
                @endif
            @endif
        </div>



        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @auth

                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="ri-vip-crown-2-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @role('superadmin')
                        <li class="menu-title">Users</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>User</span>
                            </a>

                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('users.index') }}">View All</a></li>
                                <li><a href="{{ route('users.create') }}">Add New</a></li>
                            </ul>

                        </li>


                        <li class="menu-title">Customers</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Customers</span>
                            </a>

                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('customers') }}">View All</a></li>
                                <li><a href="{{ route('customer.importFileView') }}">Upload Customers</a></li>
                            </ul>

                        </li>
                    @endrole


                    @role('user')
                    <li>
                        <a href="{{route('user.customersList')}}" class="waves-effect">
                            <i class="ri-vip-crown-2-line"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    @endrole

                @endauth

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
