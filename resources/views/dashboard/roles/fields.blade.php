<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-12">
    <label class="form-label">Permissions</label>
    <div class="row g-2">
        @foreach($permissions as $p)
            <div class="col-md-3">
                <label class="border rounded p-2 w-100 d-flex gap-2 align-items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $p->name }}" @checked(in_array($p->name, old('permissions', $selected ?? [])))>
                    <span class="small">{{ $p->name }}</span>
                </label>
            </div>
        @endforeach
    </div>
</div>
