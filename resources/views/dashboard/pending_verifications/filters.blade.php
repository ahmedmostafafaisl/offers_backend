<div class="col-md-3">
    <select class="form-select" name="type">
        <option value="">All Types</option>
        @foreach(['employee', 'provider', 'customer'] as $t)
            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="verified">
        <option value="">Verified? (All)</option>
        <option value="1" @selected(request('verified') === '1')>Verified</option>
        <option value="0" @selected(request('verified') === '0')>Not Verified</option>
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="requester_user_id">
        <option value="">Requester</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('requester_user_id') === (string) $u->id)>#{{ $u->id }} -
                {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="target_user_id">
        <option value="">Target</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('target_user_id') === (string) $u->id)>#{{ $u->id }} -
                {{ $u->name }}</option>
        @endforeach
    </select>
</div>
