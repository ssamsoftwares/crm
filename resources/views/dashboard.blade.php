@extends('layouts.main')

@push('page-title')
    <title>{{'Dashboard'}}</title>
@endpush

@push('heading')
    {{ 'Dashboard'}}
@endpush

@section('content')


{{-- quick info --}}
@role('superadmin')
<div class="row">
    <x-design.card heading="Total Users" value="{{$total['users']}}" icon="mdi-account-convert" desc="Users"/>
    <x-design.card heading="Total Customers" value="{{$total['customers']}}" icon="mdi-account-convert" desc="Customers"/>
</div>
@endrole

@role('user')
<div class="row">
    <x-design.card heading="Total Customers" value="{{$total['allotCustomerUser']}}" icon="mdi-account-convert" desc="Customers"/>
</div>
@endrole


@endsection
