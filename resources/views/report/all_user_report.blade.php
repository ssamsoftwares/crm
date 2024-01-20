@extends('layouts.main')

@push('page-title')
    <title>All User Report</title>
@endpush

@push('heading')
    {{ 'All User Report' }}
@endpush

@section('content')
    @push('style')

    @endpush

    <x-status-message />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{route('allUsersReport')}}" method="get">
                    <div class="row m-2">
                            <div class="col-lg-3">
                                <label for="">Alloted User</label>
                                <select name="user" id="" class="form-control selectUsers">
                                    <option value="">All</option>
                                    @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ isset($_REQUEST['user']) && $_REQUEST['user'] == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>

                                    @endforeach
                                </select>
                            </div>

                        <div class="col-lg-2 mt-lg-4 btn-sm">
                            <input type="submit" class="btn btn-primary" value="Filter">
                            <a href="{{route('allUsersReport')}}" class="btn btn-secondary">Reset</a>
                        </div>

                    </div>
                </form>


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th>{{ '#' }}</th> --}}
                                    <th>{{ 'User Name' }}</th>
                                    <th>{{ 'User Email' }}</th>
                                    <th>{{ 'No. of Alloted customer count' }}</th>
                                    <th>{{ 'Actions' }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($usersWithCustomerCount as $user)
                                    <tr>
                                        <td>{{ ($usersWithCustomerCount->perPage() * ($usersWithCustomerCount->currentPage() - 1)) + $loop->index + 1 }}</td>

                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->customer_count}}</td>
                                        <td>
                                            <div class="action-btns text-center" role="group">
                                                <a href="{{route('userAllotedCustomerDetails',$user->id)}}"
                                                    class="btn btn-primary waves-effect waves-light view">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $usersWithCustomerCount->appends(request()->query())->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->





@endsection

@push('script')

<script>
    $(document).ready(function () {
        $('.selectUsers').select2();
    });
</script>
@endpush
