<div>
    <label for="{{ $id }}">{{ $label }}</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $id }}" value="active"
            @if ($value === 'active') checked @endif>
        <label class="form-check-label" for="{{ $id }}">
            Active
        </label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $id }}"
            value="block" @if ($value === 'block') checked @endif>
        <label class="form-check-label" for="{{ $id }}">
            Block
        </label>
    </div>
    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
