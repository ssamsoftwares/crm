<div class="mx-2 mt-4">
    <form action="{{ $action }}" method="{{ $method }}">
        <div class="input-group">
            <input type="search" class="form-control {{ $class }}" id="{{ $id }}"
                name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}">
            <input type="hidden" name="{{ $roleName }}" value="{{ $catVal }}">
            <div class="input-group-append">
                <button type="{{ $btn_type }}" class="btn btn-primary {{ $btnClass }}">Search</button>
                <a href="{{ $action }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>



