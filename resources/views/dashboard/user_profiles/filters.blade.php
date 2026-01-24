<div class="col-md-3">
    <select class="form-select" name="type">
        <option value="">All Types</option>
        <option value="customer" @selected(request('type') === 'customer')>customer</option>
        <option value="provider" @selected(request('type') === 'provider')>provider</option>
        <option value="employee" @selected(request('type') === 'employee')>employee</option>
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="user_id">
        <option value="">User ID</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('user_id') === (string) $u->id)>#{{ $u->id }} -
                {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="linked_user_id">
        <option value="">Linked User ID</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('linked_user_id') === (string) $u->id)>#{{ $u->id }} -
                {{ $u->name }}</option>
        @endforeach
    </select>
</div>
