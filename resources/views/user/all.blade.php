@extends('layouts.main')

@push('page-title')
    <title>All Users</title>
@endpush

@push('heading')
    {{ 'Users' }}
@endpush

@section('content')
    @push('style')
    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="justify-content-end d-flex">
                    <x-search.table-search action="{{ route('users.index') }}" method="get" name="search"
                        value="{{ isset($_REQUEST['search']) ? $_REQUEST['search'] : '' }}" btnClass="search_btn" />
                    </div>

                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap mx-auto"
                            style="border-collapse: collapse; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>{{ 'Name' }}</th>
                                    <th>{{ 'Email' }}</th>
                                   <th>{{ 'Password' }}</th>
                                    {{-- <th>{{ 'Role' }}</th> --}}
                                    <th>{{ 'Status' }}</th>
                                    <th>{{ 'Actions' }}</th>

                                </tr>
                            </thead>

                            <tbody id="candidatesData">
                                @foreach ($data as $key => $user)
                                @if (!$user->hasRole('superadmin'))
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ !empty($user->normal_password)? $user->normal_password : NULL }}</td>
                                        <td>
                                            @if ($user->status == 'active')
                                            <a href="{{route('user.statusUpdate',$user->id)}}" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure Block This User')">Active</a>
                                            @else
                                            <a href="{{route('user.statusUpdate',$user->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure Active This User')">Block</a>
                                            @endif
                                        </td>

                                        {{-- <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    <label class="badge bg-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td> --}}

                                        </td>
                                        <td>
                                            <div class="action-btns text-center" role="group">

                                                <a href="{{ route('users.show',$user->id) }}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="{{ route('users.edit',$user->id) }}"
                                                    class="btn btn-info waves-effect waves-light edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                {{-- <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light del" onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->onEachSide(5)->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@push('script')
@endpush
