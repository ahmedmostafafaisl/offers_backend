<div class="col-md-3">
    <select class="form-select" name="user_id">
        <option value="">All Users</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('user_id') === (string) $u->id)>{{ $u->name }}
                (#{{ $u->id }})</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="plan_id">
        <option value="">All Plans</option>
        @foreach($plans as $p)
            <option value="{{ $p->id }}" @selected((string) request('plan_id') === (string) $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-2">
    <select class="form-select" name="type">
        <option value="">All Types</option>
        <option value="monthly" @selected(request('type') === 'monthly')>monthly</option>
        <option value="annually" @selected(request('type') === 'annually')>annually</option>
    </select>
</div>

<div class="col-md-2">
    <select class="form-select" name="is_active">
        <option value="">All Status</option>
        <option value="1" @selected(request('is_active') === '1')>Active</option>
        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
    </select>
</div>
