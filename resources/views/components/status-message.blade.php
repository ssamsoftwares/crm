@if ($message = Session::get('status'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if( count($errors) > 0 )
    <div class="alert alert-danger">
        <p>{{ 'Some of the provided values are not correct!'}}</p>
    </div>
@endif