@extends('layouts.main')

@push('page-title')
    <title>{{ __('Customer Comment') }}</title>
@endpush

@push('heading')
    {{ __('Customer Comment-') }} {{ $comment->customer->name }}
@endpush

@section('content')
    <x-status-message />
    <div class="btn">
        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm m-1">
            <i class="fa fa-backward"></i> Back
        </a>
        <a href="{{route('user.viewAllComments',$comment->customer->id)}}" class="float-end btn btn-info btn-sm m-1"><i class="fa fa-eye"></i> All Comments</a>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <h5 class="card-header text-light bg-secondary">{{ 'Customer Details' }}</h5>
                <div class="card-body">
                    <form action="{{route('user.updateComments')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$comment->id}}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="customer_id" value="{{ $comment->customer->id }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Comments <span class="text-danger">*</span></label>
                                <textarea id="elm1" name="comments">{{$comment->comments}}</textarea>
                                <span class="text-danger">
                                    @error('comments')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-4 text-center">Update Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
