<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Image</label>
    <input name="image" type="file" class="form-control">
    @if($row && $row->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $row->image) }}"
                style="width:200px;height:200px;border-radius:12px;object-fit:cover;border:1px solid #eee;">
        </div>
    @endif
</div>
