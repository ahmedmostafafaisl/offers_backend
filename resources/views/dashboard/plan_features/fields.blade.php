<div class="col-md-6">
    <label class="form-label">Plan</label>
    <select name="plan_id" class="form-select" required>
        @foreach($plans as $p)
            <option value="{{ $p->id }}" @selected((string) old('plan_id', $row->plan_id ?? '') === (string) $p->id)>
                {{ $p->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control"
        rows="3">{{ old('description', $row->description ?? '') }}</textarea>
</div>
