<div class="mb-3">
    <label class="form-label" for="{{$name}}">{{$label}}</label>
    <select class="form-control" id="{{$name}}" name="{{$name}}">
        <option value="">{{$chooseFileComment}}</option>
        @foreach ($options as $option)
            <option value="{{$values[$loop->index]}}"  data-select="{{ $selected }}"  data-old="{{ old($name) }}"
                @if($selected)
                    @selected($selected == $values[$loop->index])
                @else
                    @selected(old($name) == $values[$loop->index])
                @endif>
                {{$option}}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="text-danger form-text">{{$message}}</div>
    @enderror
</div>
