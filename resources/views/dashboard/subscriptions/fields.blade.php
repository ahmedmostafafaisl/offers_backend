<div class="col-md-6">
    <label class="form-label">User</label>
    <select name="user_id" class="form-select" required>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('user_id', $row->user_id ?? '') === (string) $u->id)>
                {{ $u->name }} (#{{ $u->id }})</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Plan</label>
    <select name="plan_id" class="form-select" required>
        @foreach($plans as $p)
            <option value="{{ $p->id }}" @selected((string) old('plan_id', $row->plan_id ?? '') === (string) $p->id)>
                {{ $p->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-select" required>
        <option value="quarterly" @selected(old('type', $row->type ?? '') === 'quarterly')>quarterly</option>
        <option value="semi_annual" @selected(old('type', $row->type ?? '') === 'semi_annual')>semi-annual</option>
        <option value="annual" @selected(old('type', $row->type ?? '') === 'annual')>annual</option>
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Active</label>
    <select name="is_active" class="form-select">
        <option value="1" @selected((string) old('is_active', $row->is_active ?? 1) === '1')>Active</option>
        <option value="0" @selected((string) old('is_active', $row->is_active ?? 1) === '0')>Inactive</option>
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Start Date</label>
    <input type="date" name="start_date" class="form-control" required
        value="{{ old('start_date', $row->start_date ?? '') }}">
</div>

<div class="col-md-3">
    <label class="form-label">Expiration Date</label>
    <input type="date" name="expiration_date" class="form-control" required
        value="{{ old('expiration_date', $row->expiration_date ?? '') }}">
</div>
