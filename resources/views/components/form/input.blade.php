<div @if($type == 'hidden') class="position-absolute top-0 start-0" @else class="mb-3" @endif>
    <label class="form-label" for="{{$name}}">{{$label}}</label>
    <input type="{{$type}}" class="form-control" id="{{$name}}" value="{{ $value ?? old($name) }}" name="{{$name}}" @if($disabled) disabled @endif  placeholder="{{$placeholder}}" @if($type == 'date') min="{{$min}}" @endif >
    @error($name)
        <div class="text-danger form-text">{{ $message }}</div>
    @enderror
</div>
