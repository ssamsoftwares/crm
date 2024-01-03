<div class="col-xl-3 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <p class="text-truncate font-size-16 mb-2 fw-bold">{{ $heading }}</p>
                    <h4 class="mb-2">{{ $value }}</h4>
                    <p class="text-muted mb-0 fw-bold font-size-12 me-2">{{ $desc   }}</p>
                </div>
                <div class="avatar-sm">
                    <span class="avatar-title bg-light text-{{$color}} rounded-3">
                        <i class="mdi {{$icon}} font-size-24"></i>  
                    </span>
                </div>
            </div>                                            
        </div><!-- end cardbody -->
    </div><!-- end card -->
</div>