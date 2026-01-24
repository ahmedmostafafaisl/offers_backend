<div class="col-md-3">
    <select class="form-select" name="category_id">
        <option value="">All Categories</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected((string) request('category_id') === (string) $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="user_id">
        <option value="">All Providers</option>
        @foreach($providers as $p)
            <option value="{{ $p->id }}" @selected((string) request('user_id') === (string) $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="is_active">
        <option value="">All Status</option>
        <option value="1" @selected(request('is_active') === '1')>Active</option>
        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
    </select>
</div>

<div class="col-md-3">
    <input class="form-control" name="city" value="{{ request('city') }}" placeholder="City (AR or EN)">
</div>
