@extends('layouts.main')

@push('page-title')
    <title>{{ __('Customer Comment') }}</title>
@endpush

@push('heading')
    {{ __('Customer Comment-') }} {{ $customer->name }}
@endpush

@section('content')
    <x-status-message />

    <div class="btn">
        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">
            <i class="fa fa-backward"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <p  class="card-header text-light bg-secondary"> {{ 'Add Comment' }}</p>
                <div class="card-body">
                    <form action="{{ route('user.storeComments') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Comments <span class="text-danger">*</span></label>
                                <textarea id="elm1" name="comments"></textarea>
                                <span class="text-danger">
                                    @error('comments')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-4 text-center">Add Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header  m-0">
                   <b class="text-danger"> {{ 'Letest 5 Comment' }}</b>
                    <a href="{{route('user.viewAllComments',$customer->id)}}" class="float-end btn btn-info btn-sm">View All Comments</a>
                </div>
                <div class="card-body">
                    @foreach ($customer->comments as $comment)
                    <div class="row shadow-lg p-1 mb-2 bg-white rounded">
                        <div class="col-8">
                            <h5 class="card-title">
                                <i>{{$comment->customer->name}}</i>
                            </h5>
                        </div>
                        <div class="col-4 float-end text-center">
                            <span>{{ $comment->customer->created_at->format('F j, Y \a\t g:i a') }}</span>
                        </div>
                        <hr>
                        <div class="col-11">
                            <p>{!! $comment->comments  !!}

                            </p>
                        </div>
                        <div class="col-1 float-end">
                            <a href="{{route('user.editComment',$comment->id )}}" class="btn btn-info btn-hover-danger btn-sm">Edit</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> --}}

@endsection

@push('script')
@endpush
