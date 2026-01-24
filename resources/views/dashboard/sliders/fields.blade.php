<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="1" @selected((string) old('status', $row->status ?? 1) === '1')>Active</option>
        <option value="0" @selected((string) old('status', $row->status ?? 1) === '0')>Inactive</option>
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Image</label>
    <input name="image" type="file" class="form-control" {{ $row ? '' : 'required' }}>
    @if($row && $row->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $row->image) }}"
                style="width:90px;height:90px;border-radius:12px;object-fit:cover;border:1px solid #eee;">
        </div>
    @endif
</div>
