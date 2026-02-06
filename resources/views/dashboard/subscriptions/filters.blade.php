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
        <option value="quarterly" @selected(request('type') === 'quarterly')>quarterly</option>
        <option value="semi_annual" @selected(request('type') === 'semi_annual')>semi-annual</option>
        <option value="annual" @selected(request('type') === 'annual')>annual</option>
    </select>
</div>

<div class="col-md-2">
    <select class="form-select" name="is_active">
        <option value="">All Status</option>
        <option value="1" @selected(request('is_active') === '1')>Active</option>
        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
    </select>
</div>
